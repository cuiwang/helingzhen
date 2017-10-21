<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('display','upload');
$do = in_array($do, $dos) ? $do : 'display';
$t = $_GPC['t'];

if($do == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
		
	$types = empty($_GPC['types']) ? array() : $_GPC['types'];
	if(!empty($types)){
		$condition = array();
		$where = '';
		foreach ($types as $type) {
			$condition[] = " type='{$type}' ";;
		}
		$where = " AND (".implode('OR', $condition).") ";
	}
		
		

}

if($foo == 'upload') {
		
	if (checksubmit()) {

		$type = $_GPC['type'];

		$media = array(
				'filename' => $_FILES['media']['name'],
				'filelength' => $_FILES['media']['size'],
				'content-type' => $_FILES['media']['type']
		);

		$contentTypes = array('jpeg','audio/mp3','video/mpeg4',);

		$token = account_weixin_token($_W['account']);

		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type={$type}";
		$extra = array(
										);
		$response = ihttp_request($url, $media, $extra);

		exit;

								
												
																																												
		$first = array('type' => $type,'media' => $_FILES['media']['name']);

				
								
		message('上传成功.');
	}
}

template('platform/resource');
