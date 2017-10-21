<?php
/**
 * @require PHP>=5.3
 */
require_once __DIR__ . '/KdtApiOauthProtocol.php';
require_once __DIR__ . '/SimpleHttpClient.php';

class KdtApiOauthClient {
	private static $apiEntry = 'https://open.koudaitong.com/api/oauthentry';
	
	public function get($accessToken, $method, $params = array()) {
		return $this->parseResponse(
			SimpleHttpClient::get(self::$apiEntry, $this->buildRequestParams($accessToken, $method, $params))
		);
	}
	
	public function post($accessToken, $method, $params = array(), $files = array()) {
		return $this->parseResponse(
			SimpleHttpClient::post(self::$apiEntry, $this->buildRequestParams($accessToken, $method, $params), $files)
		);
	}
	

	
	private function parseResponse($responseData) {
		$data = json_decode($responseData, true);
		if (null === $data) throw new Exception('response invalid, data: ' . $responseData);
		return $data;
	}
	
	private function buildRequestParams($accessToken, $method, $apiParams) {
		if (!is_array($apiParams)) $apiParams = array();
		$pairs = $this->getCommonParams($accessToken, $method);
		foreach ($apiParams as $k => $v) {
			if (isset($pairs[$k])) throw new Exception('参数名冲突');
			$pairs[$k] = $v;
		}
		
		return $pairs;
	}
	
	private function getCommonParams($accessToken, $method) {
		$params = array();
		$params[KdtApiOauthProtocol::TOKEN_KEY] = $accessToken;
		$params[KdtApiOauthProtocol::METHOD_KEY] = $method;
		return $params;
	}
}
