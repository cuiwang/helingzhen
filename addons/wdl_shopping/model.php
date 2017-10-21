<?php
 
if (!function_exists('img_url')) {
	


if(!function_exists('img_url')) {
	function img_url($img = '') {
		global $_W;
		if (empty($img)) {
			return "";
		}
		if (substr($img, 0, 6) == 'avatar') {
			return $_W['siteroot'] . "resource/image/avatar/" . $img;
		}
		if (substr($img, 0, 8) == './themes') {
			return $_W['siteroot'] . $img;
		}
		if (substr($img, 0, 1) == '.') {
			return $_W['siteroot'] . substr($img, 2);
		}
		if (substr($img, 0, 5) == 'http:') {
			return $img;
		}
		return $_W['attachurl'] . $img;
	}
	}
}