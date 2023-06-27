<?php

if (function_exists('httpRequest') === false) {
	function httpRequest($url, $params = [], $method = 'GET')
	{
		$ch = curl_init();
		if ($method == 'GET') {
			$url = $url . '?' . http_build_query($params);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	
}
