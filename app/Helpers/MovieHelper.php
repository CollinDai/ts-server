<?php namespace App\Helpers;

use \DateTime;

class MovieHelper {
    public static function isMovieJustReleased($dateStr, $dateFormat) {
        $releaseDate = DateTime::createFromFormat($dateFormat, $dateStr);
        $releaseDateStr = $releaseDate->format('Y-m-d');
        $releaseYear = $releaseDate->format('Y');
        $todayStr = date('Y-m-d');
        $todayYear = date('Y');
        return $releaseDateStr <= $todayStr && $releaseYear >= ($todayYear - 1);
    }
}