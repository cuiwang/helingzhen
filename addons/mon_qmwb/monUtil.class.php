<?php

/**
 * Class MonUtil
 * 工具类
 */
class MonUtil
{

	public static $DEBUG = false;

	public static $IMG_INDEX_BG = 1;
	public static $IMG_PPT1 = 2;
	public static $IMG_PPT2 = 3;
	public static $IMG_BOTTOM_ADD = 4;
	public static $IMG_SHARE_BG = 5;

	public static $IMG_WB_BOX_IMG = 6;
	public static $IMG_WB_KW_BG = 7;
	public static $IMG_WB_BTN_BG = 8;



	/**
	 * author: codeMonkey QQ:631872807
	 * @param $url
	 * @return string
	 */
	public static function str_murl($url)
	{
		global $_W;

		return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

	}


	/**
	 * author: codeMonkey QQ:631872807
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
	 * author:codeMonkey QQ 631872807
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
	 * author: codeMonkey QQ:631872807
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

	public static function defaultImg($img_type,$qmwb='')
	{
		switch ($img_type) {
			//首页
			case MonUtil::$IMG_INDEX_BG:
				if (!empty($qmwb)&&!empty($qmwb['bg_img'])) {
					return MonUtil::getpicurl($qmwb['bg_img']);
				}
				$img_name = "bgd.png";
				break;
			case MonUtil::$IMG_PPT1:
				if (!empty($qmwb)&&!empty($qmwb['ppt1'])) {
					return MonUtil::getpicurl($qmwb['ppt1']);
				}
				$img_name = "185c897ef9e65b944ee262.jpg";
				break;
			case MonUtil::$IMG_PPT2:
				if (!empty($qmwb)&&!empty($qmwb['ppt2'])) {
					return MonUtil::getpicurl($qmwb['ppt2']);
				}
				$img_name = "e2d0f3ab1ff60f071d1db5.jpg";
				break;
			case MonUtil::$IMG_BOTTOM_ADD:
				if (!empty($qmwb)&&!empty($qmwb['bottom_ad'])) {
					return MonUtil::getpicurl($qmwb['bottom_ad']);
				}
				$img_name = "3a7cb13a7d8d96675fde76.jpg";
				break;
			case MonUtil::$IMG_SHARE_BG:
				if (!empty($qmwb)&&!empty($qmwb['share_bg'])) {
					return MonUtil::getpicurl($qmwb['share_bg']);
				}
				$img_name = "help.png";
				break;
		}
		return "../addons/mon_qmwb/images/" . $img_name;

	}

	public static function  defaultWBImg($img_type,$address='') {
		switch ($img_type) {
			//首页
			case MonUtil::$IMG_WB_BOX_IMG:
				if (!empty($address)&&!empty($address['box_img'])) {
					return MonUtil::getpicurl($address['box_img']);
				}
				$img_name = "scene_ico2.jpg";
				break;
			case MonUtil::$IMG_WB_KW_BG:
				if (!empty($address)&&!empty($address['kw_bg'])) {
					return MonUtil::getpicurl($address['kw_bg']);
				}
				$img_name = "page2.jpg";
				break;
			case MonUtil::$IMG_WB_BTN_BG:
				if (!empty($address)&&!empty($address['btn_kw_img'])) {
					return MonUtil::getpicurl($address['btn_kw_img']);
				}
				$img_name = "treasure_tit.png";
				break;

		}

		return "../addons/mon_qmwb/images/" . $img_name;
	}

	public static function exportexcel($data = array(), $title = array(), $dc ,$filename = 'report')
	{
		header("Content-type:application/octet-stream");
		header("Accept-Ranges:bytes");
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=" . $filename . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		//导出xls 开始
		if (!empty($title)) {
			foreach ($title as $k => $v) {
				if ($dc == 1) {
					$title[$k] = iconv("UTF-8", "GB2312", $v);
				} else if ($dc == 2) {
					$title[$k] = $v;
				}

			}
			$title = implode("\t,", $title);
			echo "$title\n";
		}

		if (!empty($data)) {
			foreach ($data as $key => $val) {
				foreach ($val as $ck => $cv) {
					if ($dc == 1) {
						$data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
					} else {
						$data[$key][$ck] = $cv;
					}
				}
				$data[$key] = implode("\t,", $data[$key]);
			}

			echo implode("\n", $data);
		}
	}

}