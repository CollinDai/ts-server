<?php namespace App\Helpers;
use App\Helpers\CURLHelper as CURL;
class XMLRPC_Client {
    private $url;
    function __construct($url, $autoload=true) {
        $this->url = $url;
        $this->methods = array();
        if ($autoload) {
            $resp = $this->call('system.listMethods', null);
            $this->methods = $resp;
        }
    }
    public function call($method, $params = null) {
        $postData = xmlrpc_encode_request($method, $params);
        $httpHeader = array('Content-Type: text/xml;charset=UTF-8');
        $curlResult = CURL::post($this->url, $postData, $httpHeader);
        if ($curlResult === 'Error') {
            return [];
        }
        return xmlrpc_decode($curlResult);
    }
}
