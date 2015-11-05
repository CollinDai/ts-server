<?php namespace App\Services\Movie;

use Log;
use App\Helpers\CURLHelper as CURL;

class OmdbService {
	public static function searchByTitle($title) {
		$title = OmdbService::removeYearFromTitle($title);
		$url = 'http://www.omdbapi.com/';
		$params = array(
			'plot'=>'short',
			'r'=>'json',
			'y'=>date("Y"),
			't'=>$title
			);
		$response = CURL::get($url, $params);
		Log::debug($response);
		return json_decode($response, true);
	}

	public static function getImdbIdByTitle($title) {
		$title = OmdbService::removeYearFromTitle($title);
		$omdbResp = searchByTitle($title);
		$omdbJson = json_decode($omdbResp, true);
		Log::debug($omdbJson);
		return $omdbJson['imdbID'];
	}

	public static function removeYearFromTitle($title) {
		$year = substr($title, -6);
		if (preg_match('/\(\d{4}\)/', $year) === 1) {
			return trim(substr($title, 0, strlen($title)-6));
		} else {
			return trim($title);
		}
	}

	public static function getPosterUrlByTitle($title) {

	}



	public static function getImdbRatingByTitle($title) {

	}
}