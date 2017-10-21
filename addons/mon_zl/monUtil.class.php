<?php

/**
 * Class MonUtil
 * 工具类
 */
class MonUtil
{

	public static $DEBUG = false;

	public static $IMG_TITLE_BG = 1;
	public static $IMG_SHAKE_BG = 2;
	public static $IMG_INDEX_BG = 3;
	public static $IMG_SHARE_BG = 4;


	/**
	 * author: 012wz QQ:微赞
	 * @param $url
	 * @return string
	 */
	public static function str_murl($url)
	{
		global $_W;

		return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

	}


	/**
	 * author: 012wz QQ:微赞
	 * 检查手机
	 */
	public static function  checkmobile()
	{

		if (!MonUtil::$DEBUG) {
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if (strpos($user_agent, 'MicroMessenger') === false) {
				echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
				exit();
			}
		}


	}

	/**
	 * author:012wz QQ 微赞
	 * 获取哟规划信息
	 * @return array|mixed|stdClass
	 */
	public static function  getClientCookieUserInfo($cookieKey)
	{
		global $_GPC;
		$session = json_decode(base64_decode($_GPC[$cookieKey]), true);
		return $session;

	}


	/**
	 * author: 012wz QQ:微赞
	 * @param $openid
	 * @param $accessToken
	 * @return unknown
	 * cookie保存用户信息
	 */
	public static function setClientCookieUserInfo($userInfo = array(), $cookieKey)
	{

		if (!empty($userInfo) && !empty($userInfo['openid'])) {
			$cookie = array();
			foreach ($userInfo as $key => $value)
				$cookie[$key] = $value;
			$session = base64_encode(json_encode($cookie));

			isetcookie($cookieKey, $session, 1 * 3600 * 1);

		} else {

			message("获取用户信息错误");
		}


	}


	public static function getpicurl($url)
	{
		global $_W;
		return $_W ['attachurl'] . $url;

	}


	public static function  emtpyMsg($obj, $msg)
	{
		if (empty($obj)) {
			message($msg);
		}
	}

	public static function defaultImg($img_type,$zl='')
	{
		switch ($img_type) {
			//首页

			case MonUtil::$IMG_SHARE_BG:
				if (!empty($zl)&&!empty($zl['share_bg'])) {
					return MonUtil::getpicurl($zl['share_bg']);
				}
				$img_name = "text-share.png";
				break;
		}
		return "../addons/mon_zl/images/" . $img_name;

	}


}