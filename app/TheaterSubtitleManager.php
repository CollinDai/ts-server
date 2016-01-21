<?php namespace App;

use App\Models\Movie;
use App\Models\Subtitle;
use App\Services\MovieDataService;
use App\Services\SubtitleDataService;
use Log;

class TheaterSubtitleManager
{
    public static function getTopTenWeekly($lan = 'eng')
    {
        $topTenMovies = Movie::orderBy('ranking')->take(10)->get();
        $result = array();
        foreach ($topTenMovies as $m) {
            $result[] = array(
                'imdb_id' => $m['imdb_id'],
                'title' => $m['title'],
                'poster_url' => $m['poster_url'],
                'backdrop_url' => $m['backdrop_url'],
                'tomato_meter' => $m['tomato_meter'],
                'imdb_rating' => $m['imdb_rating']
            );
        }
        return $result;
    }

    public static function getSubtitle($imdbId, $languages = [])
    {
        $subsBuilder = Subtitle::select(
            'imdb_id',
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
            $subsBuilder = $subsBuilder->whereIn('ISO639_2', $languages);
        }
        $subsCollection = $subsBuilder->get();
        if ($subsCollection->isEmpty()) {
            return SubtitleDataService::getSubtitle($imdbId, $languages);
        } else {
            return $subsCollection->all();
        }
    }

    public static function searchMovie($title) {
        return MovieDataService::searchMovie($title);
    }

    public static function downloadSubtitle($subId)
    {
        $sub = Subtitle::find($subId);
        if ($sub === null || $sub->content === null) {
            return SubtitleDataService::downloadSubtitle($subId);
        } else {
            return $sub->content;
        }

    }

}