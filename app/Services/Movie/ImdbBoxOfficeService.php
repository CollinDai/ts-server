<?php namespace App\Services\Movie;
/**
 * Created by PhpStorm.
 * User: Peike
 * Date: 1/9/2016
 * Time: 10:33 PM
 */

class ImdbBoxOfficeService {
    public static function topTen() {
        libxml_use_internal_errors(true);
        $url = 'http://www.imdb.com/chart/';
        $data = file_get_contents($url);
        $dom = new \DOMDocument();
        $dom->loadHTML($data);
        $dom->preserveWhiteSpace = false;
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//div[@id="boxoffice"]//td[@class="titleColumn"]/a');
        $resultArr = array();
        for ($idx=0; $idx<$nodes->length; $idx++) {
            $domNode = $nodes->item($idx);
            $title = $domNode->nodeValue;
            $imdbId = self::extractImdb($domNode->attributes->getNamedItem('href')->nodeValue);
            if ($imdbId !== null) {
                $resultArr[] = array('title'=>$title, 'imdbId'=>$imdbId);
            }
        }
        return $resultArr;
    }

    private static function extractImdb($href) {
        preg_match('/tt\d{7}/i',$href, $matches);
        if (!empty($matches)) {
            return $matches[0];
        } else {
            return null;
        }
    }
}