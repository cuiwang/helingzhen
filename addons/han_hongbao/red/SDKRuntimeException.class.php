<?php

//decode by QQ:270656184 http://www.yunlu99.com/
defined('IN_IA') or die('Access Denied');
class SDKRuntimeException extends Exception
{
	public function errorMessage()
	{
		return $this->getMessage();
	}
}