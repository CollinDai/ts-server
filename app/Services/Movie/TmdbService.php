<?php namespace App\Services\Movie;
use App\Helpers\CURLHelper as CURL;

class TmdbService {
    private static $baseUrl = 'http://image.tmdb.org/t/p/';
    private static $apiUrl = 'http://api.themoviedb.org/3/';
    private static $apiKey = null;

    public static function getBackdropImageUrl($apiKey, $imdgId, $size=780) {
        self::$apiKey = $apiKey;
        $backdropPath = self::findBackdropByImdb($imdgId);
        $backdropUrl = self::$baseUrl . 'w' . $size . '/' . $backdropPath;
        return $backdropUrl;
    }

    private static function findBackdropByImdb($imdbId) {
        $findUrl = self::$apiUrl . 'find/' . $imdbId;
        $params = array('external_source'=>'imdb_id','api_key'=>self::$apiKey);
        $response = CURL::get($findUrl, $params);
        $backdropPath = self::extractBackdropUrlFromFindResponse($response);
        return $backdropPath;
    }

    private static function extractBackdropUrlFromFindResponse($resp) {
        $resp = json_decode($resp, true);
        if (empty($resp['movie_results'])) return '';
        $movieResults = $resp['movie_results'];
        return $movieResults[0]['backdrop_path'];
    }


}