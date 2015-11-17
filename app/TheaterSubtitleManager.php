<?php namespace App;
use Log;
use App\Services\MovieDataService;
use App\Models\Movie;
class TheaterSubtitleManager {
	public static function getTopTenWeekly($lan='eng') {
		$topTenMovies = Movie::orderBy('ranking')->take(10)->get();
		$result = array();
		// Log::debug($topTenMovies);
		foreach ($topTenMovies as $m) {
			$result[] = array(
				'title' =>$m['title'],
				'poster_url' => $m['poster_url']
				);
		}
		Log::debug($result);
		return $result;
	}
}