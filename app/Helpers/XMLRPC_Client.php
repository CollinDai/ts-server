<?php namespace App\Helpers;

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
        $post = xmlrpc_encode_request($method, $params);
        $context = stream_context_create(array(
        	'http' => array(
			    'method' => "POST",
			    'header' => "Content-Type: text/xml;charset=UTF-8",
			    'content' => $post
				)
			)
        );
        // return $post;
        return xmlrpc_decode(file_get_contents($this->url, false, $context));
    }
}
