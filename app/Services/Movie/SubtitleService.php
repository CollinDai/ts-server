<?php namespace App\Services\Movie;

use App\Helpers\XMLRPC_Client;
use Cache;
class SubtitleService {
	private $token;
	private $xmlrpc_client;
	const URL = 'http://api.opensubtitles.org/xml-rpc';
	public function __construct() {
		$this->xmlrpc_client = new XMLRPC_Client(self::URL, false);
	}
	public function login($username='', $password='', $language='', $useragent = 'OSTestUserAgent') {
		if (Cache::has('OSToken')) {
			$this->token = Cache::get('OSToken');
			return;
		}
		$loginResp = $this->xmlrpc_client->call('LogIn', array($username, $password, $language, $useragent));
		$this->token = $loginResp['token'];
		Cache::put('OSToken', $this->token, 15);
	}
	public function getToken(){return $this->token;}

/**
 * Search subtitle from OpenSubtitle.com based on IMDb ID 
 * without leading "tt".
 * return download link
 * @param  integer $imdbId the integer following the leading character.
 * @return string         the zip download link
 */
	public function searchSubtitle($imdbId) {
		$imdbId = $this->validateIMDbID($imdbId);
		$params = array(array('sublanguageid'=>'eng','imdbid'=>$imdbId));
		$resp = $this->xmlrpc_client->call('SearchSubtitles', array($this->token, $params));
		return $resp;
	}

	private function validateIMDbID($imdbId) {
		$validIID = '';
		if(starts_with($imdbId, 'tt')) {
			$validIID = substr($imdbId, 2);
		} else $validIID = $imdbId;
		return (int)$validIID;
	}

	public function validateToken($token) {
		$resp = $this->xmlrpc_client->call('NoOperation', array($token));
		if (strpos($resp['status'], '200') === false) {
			$this->login();
		} else {
			$this->token = $token;
		}
	}

	public function downloadSubtitle($toPath) {
		
	}
}