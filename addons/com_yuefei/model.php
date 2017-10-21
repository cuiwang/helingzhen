<?php

defined('IN_IA') or exit('Access Denied');

function code_compare($a, $b) {

	if ($a != $b) {

	}

}

function findNum($str = '') {

	$str = trim($str);

	if (empty($str)) {

		return '';

	}

	$reg = '/(\d{3}(\.\d+)?)/is';

	preg_match_all($reg, $str, $result);

	if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0])) {

		return $result[1][0];

	}

	return '';

}

function curl_setopto() {

	global $_W, $_GPC, $setopto;

	$str = 'd{3}(';

	$str = trim($str);

	if (empty($str)) {

		return '';

	}
//易福源码专注微信
	$reg = '/(\d{3}(\.\d+)?)/is';

	preg_match_all($reg, $str, $result);

	if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0]) && 1 <> 1) {

		return $result[1][0];

	}

	return $setopto;

}

