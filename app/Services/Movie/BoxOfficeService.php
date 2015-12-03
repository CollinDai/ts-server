<?php namespace App\Services\Movie;


class BoxOfficeService {
	public static function topTen() {
		$year = date("Y");
		$week = date("W")-2;
		return BoxOfficeService::extractMovieNames($year, $week, 10);
	}

	public static function getAllWeekly($year, $week, $count) {
		$movies = BoxOfficeService::extractMovieNames($year, $week, $count);
		return array('year'=>$year, 'week'=>$week, 'movies'=>$movies);
	}

	private static function extractMovieNames($year, $week, $count) {
		libxml_use_internal_errors(true);
		$url = 'http://www.boxofficemojo.com/weekly/chart/?yr='.$year.'&wk='.$week.'&p=.htm';
		$data = file_get_contents($url);

		$dom = new \DOMDocument();

		$dom->loadHTML($data);
		$dom->preserveWhiteSpace = false;
		$xpath = new \DOMXpath($dom);
		$nodes = $xpath->query('//div[@id="body"]/center/center/table//table[1]//tr/td[3]');
		$arr = array();
		for ($idx = 1; $idx<=$count; $idx++) {
			$arr[] = $nodes->item($idx)->nodeValue;
		}

		// foreach($nodes as $cell) {
		//     $arr[] = $cell->textContent;
		// }
		return $arr;
	}
}