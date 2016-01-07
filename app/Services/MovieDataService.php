<?php namespace App\Services;
use Log;
use App\Models\Movie;
use App\Models\Subtitle;
use App\Services\Movie\BoxOfficeService;
use App\Services\Movie\OmdbService;
use App\Services\Movie\DoubanService;
use App\Services\Movie\SubtitleService;
use App\Services\Movie\TmdbService;

class MovieDataService {

	public static function getAllWeekly() {
		$week = date('W')-2;
        if ($week <= 0) $week = 0;
		$year = date('Y');
		self::getWeekly($year, $week);
	}

	public static function getWeekly($year, $week, $count=10) {
		$result = BoxOfficeService::getAllWeekly($year, $week, $count);
		for ($idx=0; $idx<$count; $idx++) {
			$t = $result['movies'][$idx];
			$omdbJson = OmdbService::searchByTitle($t);
			if ($omdbJson['Response'] === 'False') continue;
			$movie = Movie::firstOrNew(['imdb_id' => $omdbJson['imdbID']]);
			$movie->imdb_id = $omdbJson['imdbID'];
			$movie->title = $t;
			$movie->short_info = $omdbJson['Plot'];
			$movie->imdb_rating = $omdbJson['imdbRating'];
			$movie->poster_url = $omdbJson['Poster'];
			$movie->douban_rating = DoubanService::getDoubanRatingByImdbId($movie->imdb_id);
			$movie->ranking = $idx + 1;
		    $movie->backdrop_url = TmdbService::getBackdropImageUrl(env('TMDB_API_KEY'),$movie->imdb_id);
            if ($movie->imdb_id === 'tt4354930') {
                dd($movie);
            }
			$movie->save();
		}
	}

	public static function getSubtitle($imdbId, array $languages) {
		$subtitleService = new SubtitleService();
    	$subtitleService->login(
            env('OPENSUBTITLE_USERNAME'),
            env('OPENSUBTITLE_PASSWORD'),
            'en',
            env('OPENSUBTITLE_USERAGENT'));
    	$resp = $subtitleService->searchSubtitle($imdbId,$languages);
    	$result = [];
    	if (empty($resp['data'])) {
    		return $result;
    	}
    	foreach ($resp['data'] as $sub) {
    		$s = new Subtitle();
    		$s->imdb_id = $imdbId;
    		$s->file_id = $sub['IDSubtitleFile'];
			$s->file_name = $sub['SubFileName'];
			$s->duration = $sub['SubLastTS'];
			$s->download_count = (int)$sub['SubDownloadsCnt'];
			$s->download_link = $sub['SubDownloadLink'];
			$s->file_size = (int)$sub['SubSize'];
			$s->add_date = $sub['SubAddDate'];
			$s->language = $sub['LanguageName'];
			$s->ISO639 = $sub['ISO639'];
			$s->ISO639_2 = $sub['SubLanguageID'];
			$s->save();
			$result[] = $s;
    	}
    	return $result;
	}

	public static function downloadSubtitle($subId) {
		$subtitleService = new SubtitleService();
        $subtitleService->login(
        	env('OPENSUBTITLE_USERNAME'),
            env('OPENSUBTITLE_PASSWORD'),
            'en',
            env('OPENSUBTITLE_USERAGENT'));
        $resp = $subtitleService->downloadSubtitle([$subId]);
        if ($resp !== '') {
	        $sub = Subtitle::find($subId);
	        $sub->content = $resp;
	        $sub->save();
	    }
        return $resp;
	}

	public static function getMovieBackdropUrl($imdbId, $size=780) {
		$apiKey = env('TMDB_API_KEY');
		return TmdbService::getBackdropImageUrl($imdbId, $size, $apiKey);
	}
}