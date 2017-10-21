<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');


function code_verify($uniacid, $receiver, $code) {
	$data = pdo_fetch('SELECT * FROM ' . tablename('uni_verifycode') . ' WHERE uniacid = :uniacid AND receiver = :receiver AND verifycode = :verifycode AND createtime > :createtime', array(':uniacid' => $uniacid, ':receiver' => $receiver, ':verifycode' => $code, ':createtime' => time() - 1800));
	if(empty($data)) {
		return false;
	}
	return true;
}


function utility_image_rename($image_source_url, $image_destination_url) {
	global $_W;
	load()->func('file');
	if (empty($image_source_url) || !parse_path($image_source_url)) {
		return false;
	}
	if (!strexists($image_source_url, $_W['siteroot'])) {
		$img_local_path = file_remote_attach_fetch($image_source_url);
		if (is_error($img_local_path)) {
			return false;
		}
		$img_source_path = ATTACHMENT_ROOT . $img_local_path;
	} else {
		$img_local_path = substr($image_source_url, strlen($_W['siteroot']));
		$img_path_params = explode('/', $img_local_path);
		if ($img_path_params[0] != 'attachment') {
			return false;
		}
		$img_source_path = IA_ROOT . '/' . $img_local_path;
	}
	$result = copy($img_source_path, $image_destination_url);
	return $result;
}
