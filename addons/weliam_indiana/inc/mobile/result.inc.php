<?php
global $_W,$_GPC;
	if (empty($_GPC['id'])) {
        message('抱歉，参数错误！', '', 'error');
    }
    $openid = m('user') -> getOpenid();
	$id = intval($_GPC['id']);
	$uniacid=$_W['uniacid'];
	$goods = pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_comcode')." WHERE uniacid = '{$uniacid}' and pid = '{$id}' ");	
	$arecord = unserialize($goods['arecord']);
	foreach($arecord as $key => $value){
		if($value['nickname'] != base64_encode(base64_decode($value['nickname']))){
			$arecord[$key]['nickname'] = $value['nickname'];
		}else{
			$arecord[$key]['nickname'] = base64_decode($value['nickname']);
		}
	}
	include $this->template('result');
?>