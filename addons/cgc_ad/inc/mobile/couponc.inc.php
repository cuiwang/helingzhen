<?php

global $_W, $_GPC;
$weid = $_W['uniacid'];
$quan_id = intval($_GPC['quan_id']);
$id = intval($_GPC['id']);
$member = $this->get_member();
$from_user = $member['openid'];
$subscribe = $member['follow'];
$quan = $this->get_quan();
$adv = $this->get_adv();
$couponc = $this->get_couponc();
$config = $this->settings;
$mid = $member['id'];
$op = empty($_GPC['op']) ? "display" : $_GPC['op'];

if ($op == 'display') {
	// 卡券介绍
	// 判断是否为卡券模式
	if($adv['model']!=8){
		$this->returnError('本次不为卡券模式');
	}
	
	$btn_name='立即使用';
	
	//判断是否已念券
	if(!$couponc){
		$btn_name = '马上领取';
	}
	else{
		if($couponc['status']==1){
			$btn_name = '卡券已使用';
		}else if($couponc['couponc_valid_date'] < time()){
			$btn_name = '卡券已过期';
		}
	}
	
	$_url=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('detail',array('quan_id'=>$quan_id,'id'=>$adv['id'],'model'=>$adv['model'])), 2);
	
	include $this->template('couponc');
    exit();
}
else if($op=='couponc'){
	
	// 判断是否为卡券模式
	if($adv['model']!=8){
		$this->returnError('不为卡券模式');
	}

	//判断是否已念券
	if(!$couponc){
		$this->returnError('你还未领取卡券');
	}
	
	if($couponc['status']==1){
		$this->returnError('卡券已经核销');
	}
	
	if(!empty($adv['hx_pass'])){
		$hx_pass=$_GPC['hx_pass'];
		$hx_pass=trim($hx_pass);
		if(empty($hx_pass)){
			$this->returnError('请输入核销密码');
		}
		if($hx_pass!=$adv['hx_pass']){
			$this->returnError('核销密码错误，请重输');
		}
	}
	
	pdo_update('cgc_ad_couponc', array('status' => 1,'update_time'=>time(),'status_time'=>time()), array('weid' => $_W['uniacid'], 'id' => $couponc['id']));
	
	$this->returnSuccess('核销成功！',array(
		'start' => 9
	));

}
