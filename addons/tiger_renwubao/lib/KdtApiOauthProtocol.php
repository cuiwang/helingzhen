<?php


class KdtApiOauthProtocol
{
	const METHOD_KEY = 'method';
	const TOKEN_KEY = 'access_token';

	const ERR_INVALID_TOKEN_VALUE = 40009;
	const ERR_INVALID_TOKEN = 40010;
	const ERR_INVALID_TOKEN_INFO = 40011;
	const ERR_METHOD_NOT_SUPPORTED = 400012;

	public static function doc()
	{
		return array(
			'params' => array(
				self::TOKEN_KEY => array(
					'type' => 'String',
					'required' => true,
					'desc' => '商户通过Oauth2.0授权给开发者的AccessToken',
				),
				self::METHOD_KEY => array(
					'type' => 'String',
					'required' => true,
					'desc' => 'API接口名称',
				),
			),

		);
	}
}