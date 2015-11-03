<?php namespace App\Services\Movie;


class BoxOfficeService {
	public static function topTen() {
		$year = date("Y");
		$week = date("W")-2;
		return BoxOfficeService::extractMovieNames($year, $week);
	}

	public static function getAllWeekly() {
		$year = date("Y");
		$week = date("W")-2;
		$movies = BoxOfficeService::extractMovieNames($year, $week);
		return array('year'=>$year, 'week'=>$week, 'movies'=>$movies);
	}

	private static function extractMovieNames($year, $week) {
		$url = 'http://www.boxofficemojo.com/weekly/chart/?yr='.$year.'&wk='.$week.'&p=.htm';
		$data = file_get_contents($url);

		$dom = new \DOMDocument();

		$dom->loadHTML($data);
		$dom->preserveWhiteSpace = false;
		$xpath = new \DOMXpath($dom);
		$nodes = $xpath->query('//div[@id="body"]/center/center/table//table[1]//tr/td[3]');

		$arr = array();

		foreach($nodes as $cell) {
		    $arr[] = $cell->textContent;
		}
		return $arr;
	}
}