<?php
global $_W,$_GPC;
$conf = pdo_get('navlange_pay_conf',array('uniacid'=>$_W['uniacid']));
if(empty($conf)) {
	pdo_insert('navlange_pay_conf',array('uniacid'=>$_W['uniacid']));
	$conf = pdo_get('navlange_pay_conf',array('uniacid'=>$_W['uniacid']));
}
if(checksubmit()) {
	if(!empty($_GPC['debug']) && $_GPC['debug'] == '1') {
		$debug = '1';
	} else {
		$debug = '0';
	}
	pdo_update('navlange_pay_conf',array('debug'=>$debug),array('uniacid'=>$_W['uniacid']));
	if(!empty($_FILES['cert']['name']) || !empty($_FILES['key']['name'])) {
		load()->func('file');
		$dir = IA_ROOT . '/attachment/wxpay/' . $_W['uniacid'];
		if(!is_dir($dir)) {
			mkdir($dir,0777,true);
		}
		if(!empty($_FILES['cert']['name'])) {
			$cert = $dir . '/' . iconv("UTF-8", "GB2312", $_FILES["cert"]["name"]);
			file_move($_FILES['cert']['tmp_name'], $cert);
		}
		if(!empty($_FILES['key']['name'])) {
			$key = $dir . '/' . iconv("UTF-8", "GB2312", $_FILES["key"]["name"]);
			file_move($_FILES['key']['tmp_name'], $key);
		}
	}	
	message('设置成功！','refresh','success');
}
if(checksubmit('redpacket_conf')) {
	pdo_update('navlange_pay_conf',array('redpacket_wish'=>$_GPC['wish']),array('uniacid'=>$_W['uniacid']));
	message('设置成功！','refresh','success');
}
include $this->template('conf');
?>