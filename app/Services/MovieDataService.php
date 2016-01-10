<?php namespace App\Services;

use Log;
use App\Models\Movie;
use App\Services\Movie\BoxOfficeService;
use App\Services\Movie\OmdbService;
use App\Services\Movie\DoubanService;
use App\Services\Movie\TmdbService;
use App\Services\Movie\ImdbBoxOfficeService as IMDB;

class MovieDataService
{

    public static function getAllWeekly()
    {
        $week = date('W');
        $year = date('Y');
        self::getWeekly($year, $week);
    }

    public static function getWeekly($year, $week, $count = 10)
    {
        $result = BoxOfficeService::getAllWeekly($year, $week, $count);
        for ($idx = 0; $idx < $count; $idx++) {
            $title = $result['movies'][$idx];
            $movie = self::setupBasicData($title);
            if ($movie === null) continue;
            $movie->douban_rating = DoubanService::getDoubanRatingByImdbId($movie->imdb_id);
            $movie->ranking = $idx + 1;
            $movie->backdrop_url = TmdbService::getBackdropImageUrl(env('TMDB_API_KEY'), $movie->imdb_id);
            $movie->save();
        }
    }

    public static function getTopTen() {
        $result = IMDB::topTen();
        for ($idx=0; $idx<count($result); $idx++) {
            $m = $result[$idx];
            $movie = Movie::firstOrNew(['imdb_id'=>$m['imdbId']]);
            $movie->title = $m['title'];
            self::setupBasicData($movie);
            $movie->douban_rating = DoubanService::getDoubanRatingByImdbId($movie->imdb_id);
            $movie->ranking = $idx + 1;
            $movie->backdrop_url = TmdbService::getBackdropImageUrl(env('TMDB_API_KEY'), $movie->imdb_id);
            $movie->save();
        }

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
        return TmdbService::getBackdropImageUrl($imdbId, $size, $apiKey);
    }
}