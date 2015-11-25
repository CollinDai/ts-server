<?php namespace App;
use Log;
use App\Services\MovieDataService;
use App\Services\Movie\SubtitleService;
use App\Models\Movie;
use App\Models\Subtitle;
class TheaterSubtitleManager {
	public static function getTopTenWeekly($lan='eng') {
		$topTenMovies = Movie::orderBy('ranking')->take(10)->get();
		$result = array();
		// Log::debug($topTenMovies);
		foreach ($topTenMovies as $m) {
			$result[] = array(
				'imdb_id' =>$m['imdb_id'],
				'title' =>$m['title'],
				'poster_url' => $m['poster_url']
				);
		}
		Log::debug($result);
		return $result;
	}

    public static function getSubtitle($imdbId, $languages=[]) {
        $subs = Subtitle::where('imdb_id', $imdbId);
        if ($subs->isEmpty()) {
            MovieDataService::getSubtitle($imdbId, []);
        }
        if (!empty($languages)) {
            $subs = $subs->whereIn('ISO639_2', $languages);
        }
        $subs = $subs->get();
        return $subs;
    }
}