<?php
/**
 * Copyright 2011 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class BitlyComponent extends Object {
	var $user;
	var $api_key;
 
	/**
	 * set bit.ly username and api key
	 *
	 * @param $user string
	 * @param $api_key string
	 */
	
	function setApiInfo($user, $api_key) {
		$this->user = $user;
		$this->api_key = $api_key;
	}
 
	/**
	 * call bit.ly api to shorten the url
	 *
	 * @param $long_url string
	 */
	
	function shorten($long_url) {
		if (!empty($cache['BitlyLink']['id'])) {
			return 'http://bit.ly/'.$cache['BitlyLink']['hash'];
		}
 
		$params = array();
		$params['login'] = $this->user;
		$params['apiKey'] = $this->api_key;
		$params['version'] = '2.0.1';
		$params['format'] = 'json';
		$params['longUrl'] = $long_url;
 
		$url = 'http://api.bit.ly/shorten?'.http_build_query($params);
		
		$data = json_decode(file_get_contents($url), true);
		if ($data['errorCode'] == 0) {
			$result = array_pop(array_values($data['results']));
			return $result['shortUrl'];
		} else {
			return false;
		}
	}
}
?>