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
                'douban_rating' => $m['douban_rating'],
                'imdb_rating' => $m['imdb_rating']
            );
        }
        return $result;
    }

    public static function getSubtitle($imdbId, $languages = [])
    {
        $subs = Subtitle::select(
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
            $subs = $subs->whereIn('ISO639_2', $languages);
        }
        $subs = $subs->get();
        if ($subs->isEmpty()) {
            $subs = SubtitleDataService::getSubtitle($imdbId, $languages);
        } else {
            $subs = $subs->all();
        }
        return $subs;
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