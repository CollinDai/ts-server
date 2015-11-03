<?php namespace App\Services\Movie;

libxml_use_internal_errors(true);

function extractFilmNames($url) {
	$data = file_get_contents($url);

	$dom = new \DOMDocument();

	$dom->loadHTML($data);
	$dom->preserveWhiteSpace = false;
	$xpath = new \DOMXpath($dom);
	$nodes = $xpath->query('//div[@id="body"]/center/center/table//table[1]//tr[position()>1 and position()<=11]/td[3]');

	$arr = array();

	foreach($nodes as $cell) {
	    $arr[] = $cell->textContent;
	}
	return $arr;
}

