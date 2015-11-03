<?php namespace App\Services\Movie;

use App\Helpers\CURLHelper as CURL;


/**
 * 150 times per hour per IP
 */
class DoubanService {
	public static function searchMovieByImdbId($imdbId) {
		$url = 'http://api.douban.com/v2/movie/search';
		$params = array('q'=>$imdbId);
		$response = CURL::get($url, $params);
		return $response;
	}

	public static function getDoubanRatingByImdbId($imdbId) {
		$resp = json_decode(DoubanService::searchMovieByImdbId($imdbId), true);
		$subject = $resp['subjects'][0];
		$rating = $subject['rating']['average'];
		return $rating;
	}
}