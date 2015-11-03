<?php namespace App\Services\Movie;

use App\Helpers\XMLRPC_Client;

class SubtitleService {
	private $token;
	private $xmlrpc_client;
	public function test() {
		$rpc = "http://phpxmlrpc.sourceforge.net/server.php";
		$osapi = 'http://api.opensubtitles.org/xml-rpc';
		$client = new XMLRPC_Client($osapi, false);
		$params = array(array('sublanguageid'=>'eng','imdbid'=>3659388));
		$resp = $client->call('SearchSubtitles', array('r33nnkv62ok98fv847ijnigtp1',$params));
		return $resp;
	}
	public function loginOpenSubtitle($username, $password, $language, $useragent) {
		$osapi = 'http://api.opensubtitles.org/xml-rpc';
		$this->xmlrpc_client = new XMLRPC_Client($osapi, false);
		$this->token = $this->xmlrpc_client->call('LogIn', array($username, $password, $language, $useragent));
	}

/**
 * Search subtitle from OpenSubtitle.com based on IMDb ID 
 * without leading "tt".
 * return download link
 * @param  integer $imdbId the integer following the leading character.
 * @return string         the zip download link
 */
	public function searchSubtitle($imdbId) {
		$params = array(array('sublanguageid'=>'eng','imdbid'=>$imdbId));
		$resp = $this->xmlrpc_client->call('SearchSubtitles', array($this->token, $params));
		return $resp;
	}

	public function downloadSubtitle($toPath) {
		
	}
}