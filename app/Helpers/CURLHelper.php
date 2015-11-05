<?php namespace App\Helpers;
use Log;
/** 
* Send a POST requst using cURL 
* @param string $url to request 
* @param array $post values to send 
* @param array $options for cURL 
* @return string 
*/ 
class CURLHelper {
	public static function post($url, array $post = NULL, array $options = array()) 
	{ 
	    $defaults = array( 
	        CURLOPT_POST => 1, 
	        CURLOPT_HEADER => 0, 
	        CURLOPT_URL => $url, 
	        CURLOPT_FRESH_CONNECT => 1, 
	        CURLOPT_RETURNTRANSFER => 1, 
	        CURLOPT_FORBID_REUSE => 1, 
	        CURLOPT_TIMEOUT => 10, 
	        CURLOPT_POSTFIELDS => http_build_query($post) 
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

	/** 
	* Send a GET requst using cURL 
	* @param string $url to request 
	* @param array $get values to send 
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