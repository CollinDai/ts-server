<?php namespace App\Helpers;
use Log;
/** 
* Send a POST requst using cURL 
* @param string $url to request 
* @param array $post parameters to send 
* @param array $options for cURL 
* @return string 
*/ 
class CURLHelper {
	public static function post($url, $post, array $header = array()) 
	{ 
	    $options = array( 
	        CURLOPT_POST => TRUE, 
	        CURLOPT_HEADER => FALSE, 
	        CURLOPT_URL => $url, 
	        CURLOPT_FRESH_CONNECT => TRUE, 
	        CURLOPT_RETURNTRANSFER => TRUE, 
	        CURLOPT_FORBID_REUSE => TRUE, 
	        CURLOPT_TIMEOUT => 10,
	        CURLOPT_HTTPHEADER => $header,
	        CURLOPT_POSTFIELDS => $post
	    ); 

	    $ch = curl_init(); 
	    curl_setopt_array($ch, $options); 
	    if( ! $result = curl_exec($ch)) 
	    { 
	        Log::error(curl_error($ch)); 
	        return 'Error';
	    } 
	    curl_close($ch); 
	    return $result; 
	} 

	/** 
	* Send a GET requst using cURL
	* GET is default when CURLOPT_POST is not set to true
	* @param string $url to request 
	* @param array $get parameters to send 
	* @param array $options for cURL 
	* @return string 
	*/ 
	public static function get($url, array $get = NULL, array $options = array()) 
	{    
		$curlUrl = $url;
		Log::debug('CURLHelper#url: '.$url);
		Log::debug('CURLHelper#param: ', $get);
		if (!empty($get)) {
			$curlUrl = $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get);
		}
	    $defaults = array( 
	        CURLOPT_URL => $curlUrl, 
	        CURLOPT_HEADER => 0, 
	        CURLOPT_RETURNTRANSFER => TRUE, 
	        CURLOPT_TIMEOUT => 15
	    ); 
	    
	    $ch = curl_init(); 
	    curl_setopt_array($ch, ($options + $defaults)); 
	    if( ! $result = curl_exec($ch)) 
	    { 
	        trigger_error(curl_error($ch)); 
	    } 
	    curl_close($ch); 
	    return $result; 
	} 
}