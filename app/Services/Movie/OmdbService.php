<?php namespace App\Services\Movie;

use Log;
use App\Helpers\CURLHelper as CURL;

class OmdbService {
	public static function searchByTitle($title) {
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
		$omdbResp = searchByTitle($title);
		$omdbJson = json_decode($omdbResp, true);
		Log::debug($omdbJson);
		return $omdbJson['imdbID'];
	}

	public static function getPosterUrlByTitle($title) {

	}



	public static function getImdbRatingByTitle($title) {

	}
}