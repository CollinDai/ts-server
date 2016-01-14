<?php namespace App\Services;

use Log;
use App\Models\Movie;
use App\Services\Movie\BoxOfficeService;
use App\Services\Movie\OmdbService;
use App\Services\Movie\DoubanService;
use App\Services\Movie\TmdbService as TMDB;
use App\Services\Movie\ImdbBoxOfficeService as IMDB;

class MovieDataService
{
    public static function getTopTen() {
        $result = IMDB::topTen();
        for ($idx=0; $idx<count($result); $idx++) {
            $m = $result[$idx];
            $movie = Movie::firstOrNew(['imdb_id'=>$m['imdbId']]);
            $movie->title = $m['title'];
            self::setupBasicData($movie);
            $movie->douban_rating = DoubanService::getDoubanRatingByImdbId($movie->imdb_id);
            $movie->ranking = $idx + 1;
            $movie->backdrop_url = TMDB::getBackdropImageUrl(env('TMDB_API_KEY'), $movie->imdb_id);
            $movie->save();
        }

    }

    public static function searchMovie($title) {
        $searchResult = TMDB::searchMovie(env('TMDB_API_KEY'),$title);
        $searchResult = $searchResult['results'];
        $ret = array();
        foreach($searchResult as $result) {
            $title = $result['original_title'];
            $posterPath = TMDB::buildPosterFullPath($result['poster_path']);
            $backdropPath = TMDB::buildBackdropFullPath($result['backdrop_path']);
            $imdbId = TMDB::getImdbIdByMovieId(env('TMDB_API_KEY'), $result['id']);
            $imdbRating = OmdbService::getImdbRatingByImdbId($imdbId);
            $doubanRating = '0.0';//DoubanService::getDoubanRatingByImdbId($imdbId);
            $ret[] = array(
                'imdb_id'=>$imdbId,
                'title'=>$title,
                'poster_url'=>$posterPath,
                'backdrop_url'=>$backdropPath,
                'douban_rating' => $doubanRating,
                'imdb_rating' => $imdbRating
            );
        }
        return $ret;
    }

    private static function setupBasicData($movie)
    {
        $omdbJson = OmdbService::searchByImdb($movie->imdb_id);
        $movie->short_info = $omdbJson['Plot'];
        $movie->poster_url = $omdbJson['Poster'];
        $movie->imdb_rating = $omdbJson['imdbRating'];
    }

    public static function getMovieBackdropUrl($imdbId, $size = 780)
    {
        $apiKey = env('TMDB_API_KEY');
        return TMDB::getBackdropImageUrl($imdbId, $size, $apiKey);
    }
}