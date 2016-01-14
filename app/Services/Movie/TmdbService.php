<?php namespace App\Services\Movie;
use App\Helpers\CURLHelper as CURL;

/**
 * Api request limit: 40 per 10 seconds
 * Class TmdbService
 * @package App\Services\Movie
 */
class TmdbService {
    private static $baseUrl = 'http://image.tmdb.org/t/p/';
    private static $apiUrl = 'http://api.themoviedb.org/3/';
    private static $apiKey = null;

    public static function getBackdropImageUrl($apiKey, $imdgId, $size='w780') {
        self::$apiKey = $apiKey;
        $backdropPath = self::findBackdropByImdb($imdgId);
        $backdropUrl = self::$baseUrl . $size . '/' . $backdropPath;
        return $backdropUrl;
    }

    public static function searchMovie($apiKey, $movieTitle) {
        self::$apiKey = $apiKey;
        $resp =  self::searchMovieByTitle($movieTitle);
        return json_decode($resp, true);

    }

    public static function buildPosterFullPath($fileName, $size='w180') {
        return self::$baseUrl . $size . '/' . $fileName;
    }

    public static function buildBackdropFullPath($fileName, $size='w780') {
        return self::$baseUrl . $size . '/' . $fileName;
    }

    public static function getImdbIdByMovieId($apiKey, $movieId){
        $movieUrl = self::$apiUrl . 'movie/' . $movieId;
        $params = array('api_key'=>$apiKey);
        $response = CURL::get(@$movieUrl, $params);
        $response = json_decode($response, true);
        return $response['imdb_id'];
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

    private static function searchMovieByTitle($title) {
        $searchMovieUrl = self::$apiUrl . 'search/movie';
        $params = array('query'=>urlencode($title), 'api_key'=>self::$apiKey);
        return CURL::get($searchMovieUrl,$params);
    }

}