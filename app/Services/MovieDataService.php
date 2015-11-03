<?php namespace App\Services;
use Log;
use App\Models\Movie;
use App\Models\Boxoffice;
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
		$result = BoxOfficeService::getAllWeekly();

		for ($idx=0; $idx<count(result['movie']); $idx++) {
			$t = result['movie'][$idx];
			if (!Movie::where('title', '=', $t)->exists()) {
				$movie = new Movie();
				$omdbJson = OmdbService::searchByTitle($t);
				$movie->imdb_id = $omdbJson['imdbID'];
				$movie->title = $t;
				$movie->short_info = $omdbJson['Plot'];
				$movie->imdb_rating = $omdbJson['imdbRating'];
				$movie->poster_url = $omdbJson['Poster'];
				$movie->douban_rating = DoubanService::getDoubanRatingByImdbId($movie->imdb_id);
				$movie->save();
			}
		}
	}
}