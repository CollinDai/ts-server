<?php namespace App\Services;
use Log;
use App\Models\Movie;
use App\Services\Movie\BoxOfficeService;
use App\Services\Movie\OmdbService;
use App\Services\Movie\DoubanService;
class MovieDataService {
	public static function getTopTenWeekly() {
		$titles = BoxOfficeService::topTen();
		foreach ($titles as $t) {
			$movie = new Movie();
			$omdbJson = OmdbService::searchByTitle($t);
			// dd($omdbJson["imdbID"]);
			$movie->imdb_id = $omdbJson['imdbID'];
			$movie->title = $t;
			$movie->short_info = $omdbJson['Plot'];
			$movie->imdb_rating = $omdbJson['imdbRating'];
			$movie->poster_url = $omdbJson['Poster'];
			$movie->douban_rating = DoubanService::getDoubanRatingByImdbId($movie->imdb_id);
			// dd($movie);
			$movie->save();
		}
	}
	public static function getAllWeekly() {
		$week = date('W')-2;
		$year = date('Y');
		$result = BoxOfficeService::getAllWeekly($year, $week);
		$counts = count($result['movies']);
		// $counts = 1;
		for ($idx=0; $idx<$counts; $idx++) {
			$t = $result['movies'][$idx];
			$omdbJson = OmdbService::searchByTitle($t);
			if ($omdbJson['Response'] === 'False') continue;
			$movie = Movie::firstOrNew(['imdb_id' => $omdbJson['imdbID']]);
			$movie->imdb_id = $omdbJson['imdbID'];
			$movie->title = $t;
			$movie->short_info = $omdbJson['Plot'];
			$movie->imdb_rating = $omdbJson['imdbRating'];
			$movie->poster_url = $omdbJson['Poster'];
			$movie->douban_rating = DoubanService::getDoubanRatingByImdbId($movie->imdb_id);
			$movie->ranking = $idx + 1;
		
			$movie->save();
		}
	}
}