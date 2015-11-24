<?php namespace App;
use Log;
use App\Services\MovieDataService;
use App\Services\Movie\SubtitleService;
use App\Models\Movie;
class TheaterSubtitleManager {
	public static function getTopTenWeekly($lan='eng') {
		$topTenMovies = Movie::orderBy('ranking')->take(10)->get();
		$result = array();
		// Log::debug($topTenMovies);
		foreach ($topTenMovies as $m) {
			$result[] = array(
				'imdb_id' =>$m['imdb_id'],
				'title' =>$m['title'],
				'poster_url' => $m['poster_url']
				);
		}
		Log::debug($result);
		return $result;
	}

	public static function searchSubtitle($imdbId, $languages) {
    	$subtitleService = new SubtitleService();
    	$subtitleService->login();
    	$resp = $subtitleService->searchSubtitle($imdbId,$languages);
    	$subtitles = array();
    	if (empty($resp['data'])) {
    		return $subtitles;
    	} else {
    		foreach ($resp['data'] as $sub) {
    			$subtitles[] = [
    			'file_id'=>$sub['IDSubtitleFile'],
    			'file_name'=>$sub['SubFileName'],
    			'duration'=>$sub['SubLastTS'],
    			'download_count'=>$sub['SubDownloadsCnt'],
    			'download_link'=>$sub['SubDownloadLink'],
    			'file_size'=>$sub['SubSize'],
    			'language'=>$sub['LanguageName'],
                'ISO639'=>$sub['ISO639'],
                'ISO639_2'=>$sub['SubLanguageID']
    			];
    		}
    		return $subtitles;
    	}
	}
}