<?php namespace App\Services\Movie;

use Log;
use \DateTime;
use App\Helpers\CURLHelper as CURL;
/**
 * TODO intelligently return the search result order by
 * release year descendently
 */
class OmdbService {
    private static $URL = 'http://www.omdbapi.com/';
	public static function searchByTitle($title) {
		$year = date('Y');
		for ($idx=0; $idx<3; $idx++) {
			$omdbRespJson = self::search($title, $year-$idx);
			if (self::isMovieJustReleased($omdbRespJson)) {
				return $omdbRespJson;
			}
		}
		return null;
	}

    public static function searchByImdb($imdbId) {
        $params = array(
            'i'=>$imdbId,
            'r'=>'json',
            'plot'=>'short'
        );
        $response = CURL::get(self::$URL, $params);
        return json_decode($response, true);
    }

	private static function search($title, $year) {
		$title = OmdbService::removeYearFromTitle($title);
		$params = array(
				'plot'=>'short',
				'r'=>'json',
				'y'=>$year,
				't'=>$title
		);
		$response = CURL::get(self::$URL, $params);
		return json_decode($response, true);
	}

	public static function removeYearFromTitle($title) {
		$year = substr($title, -6);
		if (preg_match('/\(\d{4}\)/', $year) === 1) {
			return trim(substr($title, 0, strlen($title)-6));
		} else {
			return trim($title);
		}
	}
	private static function isMovieJustReleased($omdbRespJson)
	{
		return $omdbRespJson['Response'] !== 'False' && self::isJustReleased($omdbRespJson);
	}

	private static function isJustReleased($omdbResp) {
		if ($omdbResp['Released']==='N/A') {
			return false;
		} else {
			$releaseDate = DateTime::createFromFormat('d M Y', $omdbResp['Released']);
            $releaseDateStr = $releaseDate->format('Y-m-d');
            $releaseYear = $releaseDate->format('Y');
            $todayStr = date('Y-m-d');
            $todayYear = date('Y');
			return $releaseDateStr <= $todayStr && $releaseYear >= ($todayYear - 1);
		}
	}
}