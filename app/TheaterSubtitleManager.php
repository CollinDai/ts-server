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
        $subs = Subtitle::select(
        	'file_id',
			'file_name',
			'file_size',
			'add_date',
			'duration',
			'download_count',
			'language',
			'ISO639'
        	)->where('imdb_id', $imdbId);
        if (!empty($languages)) {
	        $subs = $subs->whereIn('ISO639_2', $languages);
	    }
        $subs = $subs->get();
        if ($subs->isEmpty()) {
            $subs = MovieDataService::getSubtitle($imdbId, $languages);
        }
        return $subs;
    }

    public static function downloadSubtitle($subId) {
    	$subContent = Subtitle::find($subId)->content;
    	if ($subContent===null) {
    		$subContent = MovieDataService::downloadSubtitle($subId);
    	}
    	return $subContent;
    	
    }

}