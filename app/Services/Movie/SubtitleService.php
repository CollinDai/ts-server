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

	public function login($username='', $password='', $language='en', $useragent = '') {
		if (Cache::has('OSToken')) {
			$this->token = Cache::get('OSToken');
		} else {
    		$loginResp = $this->xmlrpc_client->call('LogIn', array($username, $password, $language, $useragent));
            if ($loginResp['status'] === '506 Server under maintenance') return false;
    		$this->token = $loginResp['token'];
    		Cache::put('OSToken', $this->token, 15);
        }
        return true;
	}
	public function getToken(){return $this->token;}

    /**
     * Search subtitle from OpenSubtitles.org
     * based on IMDb ID without leading "tt".
     * @param  integer $imdbId the integer following the leading character.
     * @param string $language split by comma if multiple
     * @return array all data returned by opensubtitles.org
     */
	public function searchSubtitle($imdbId, array $languages) {
		$imdbId = $this->validateIMDbID($imdbId);
        $lanStr = implode(',', $languages);
		$params = array(array('sublanguageid'=>$lanStr,'imdbid'=>$imdbId));
		$resp = $this->xmlrpc_client->call('SearchSubtitles', array($this->token, $params));
		return $resp;
	}

    /**
     * OpenSubtitle allow multiple subtitle download.
     * For now we only allow one download per call.
     * @param  array  $subFileIdArray subtitle ids
     * @return string                 base 64 encoded gzip content
     */
	public function downloadSubtitle(array $subFileIdArray) {
		$resp = $this->xmlrpc_client->call('DownloadSubtitles', array($this->token, $subFileIdArray));
        $subArray = $resp['data'];
        if (is_array($subArray)) {
            $subs_b64_data_from_xmlrpc = $subArray[0]['data'];
            return gzinflate(substr(base64_decode($subs_b64_data_from_xmlrpc),10));
        } else {
            return '';
        }
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

}