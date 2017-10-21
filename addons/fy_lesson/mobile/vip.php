<?php
/**
 * 个人中心
 */

$uid = intval($_GPC['uid']);
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('vip', array('uid'=>$uid));
if (isset($_COOKIE[$this->_auth2_openid])) {
	$openid = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$openid = $userinfo["openid"];
			$nickname = $userinfo["nickname"];
			$headimgurl = $userinfo["headimgurl"];
		} else {
			message('授权失败!');
		}
	} else {
		if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
	}
}

/* 更新微课程会员信息 */
$this->updatelessonmember($openid,$uid);

/* 基本设置 */
$setting = pdo_fetch("SELECT buysucc,is_sale,vip_sale,self_sale,level,commission,viporder_commission,vipserver,vipdesc,sitename,vipdiscount,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$uid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));

/* 系统会员中心 */
$mc_memberurl = $_W['siteroot'] .'app/'."index.php?i={$uniacid}&c=mc";

$memberinfo = pdo_fetch("SELECT uid,credit1,credit2,nickname,avatar FROM " .tablename('mc_members'). " WHERE uniacid='{$uniacid}' AND uid='{$uid}' LIMIT 1");

/* 课程会员信息 */
$lessonmember = pdo_fetch("SELECT * FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");
$vipserver = unserialize($setting['vipserver']);


if($op=='display'){
	$title = '我的VIP服务';

	$setting['vipdesc'] = htmlspecialchars_decode($setting['vipdesc']);

	include $this->template('vip');
}elseif($op=='buyvip'){
	$viptime = intval($_GPC['viptime']);
	$vipmoney = 0;

	foreach($vipserver as $value){
		if($viptime==$value['viptime']){
			$vipmoney = $value['vipmoney'];
		}
	}

	if(empty($vipmoney)){
		message("购买会员服务信息错误！", $this->createMobileUrl('vip'), "error");
	}

	/* 构造购买会员订单信息 */
	$orderdata = array(
		'acid'	   => $_W['account']['acid'],
		'uniacid'  => $uniacid,
		'ordersn'  => date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8),
		'uid'	   => $uid,
		'openid'   => $openid,
		'viptime'  => $viptime,
		'vipmoney' => $vipmoney,
		'addtime'  => time(),
	);


	/* 检查当前分销功能是否开启且课程价格大于0 */
	if($setting['is_sale']==1 && $setting['vip_sale']==1){
		$orderdata['commission1'] = 0;
		$orderdata['commission2'] = 0;
		$orderdata['commission3'] = 0;

		if($setting['self_sale']==1){
			/* 开启分销内购，一级佣金为购买者本人 */
			$orderdata['member1'] = $uid;
			$orderdata['member2'] = $this->getParentid($uid);
			$orderdata['member3'] = $this->getParentid($orderdata['member2']);
		}else{
			/* 关闭分销内购 */
			$orderdata['member1'] = $this->getParentid($uid);;
			$orderdata['member2'] = $this->getParentid($orderdata['member1']);
			$orderdata['member3'] = $this->getParentid($orderdata['member2']);
		}

		$vipordercom = unserialize($setting['viporder_commission']); /* 课程订单佣金比例 */
		$settingcom = unserialize($setting['commission']);/* 全局佣金比例 */
		if($orderdata['member1']>0){
			/* 查询用户是否属于其他分销代理级别 */
			$member1 = pdo_fetch("SELECT agent_level FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND uid='{$orderdata['member1']}'");
			$com_level = pdo_fetch("SELECT commission1 FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' AND id='{$member1['agent_level']}'");

			if($vipordercom['commission1']>0){
				$orderdata['commission1'] = round($vipmoney*$vipordercom['commission1']*0.01, 2);
			}else{
				if($com_level['commission1']>0){
					$orderdata['commission1'] = round($vipmoney*$com_level['commission1']*0.01, 2);
				}else{
					$orderdata['commission1'] = round($vipmoney*$settingcom['commission1']*0.01, 2);
				}
			}
		}
		if($orderdata['member2']>0 && in_array($setting['level'], array('2','3'))){
			/* 查询用户是否属于其他分销代理级别 */
			$member2 = pdo_fetch("SELECT agent_level FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND uid='{$orderdata['member2']}'");
			$com_level = pdo_fetch("SELECT commission2 FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' AND id='{$member2['agent_level']}'");

			if($vipordercom['commission2']>0){
				$orderdata['commission2'] = round($vipmoney*$vipordercom['commission2']*0.01, 2);
			}else{
				if($com_level['commission2']>0){
					$orderdata['commission2'] = round($vipmoney*$com_level['commission2']*0.01, 2);
				}else{
					$orderdata['commission2'] = round($vipmoney*$settingcom['commission2']*0.01, 2);
				}
			}
		}
		if($orderdata['member3']>0 && $setting['level']==3){
			/* 查询用户是否属于其他分销代理级别 */
			$member3 = pdo_fetch("SELECT agent_level FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND uid='{$orderdata['member3']}'");
			$com_level = pdo_fetch("SELECT commission3 FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' AND id='{$member3['agent_level']}'");

			if($vipordercom['commission3']>0){
				$orderdata['commission3'] = round($vipmoney*$vipordercom['commission3']*0.01, 2);
			}else{
				if($com_level['commission3']>0){
					$orderdata['commission3'] = round($vipmoney*$com_level['commission3']*0.01, 2);
				}else{
					$orderdata['commission3'] = round($vipmoney*$settingcom['commission3']*0.01, 2);
				}
			}
		}
	}

	if(!empty($openid)){
		$result = pdo_insert($this->table_member_order, $orderdata);
		$orderid = pdo_insertid();
	}
	
	if($result){
		header("Location:".$this->createMobileUrl('pay', array('orderid'=>$orderid, 'ordertype'=>'buyvip')));
	}else{
		message("写入订单信息失败，请重试！", $this->createMobileUrl('vip'), "error");
	}
}elseif($op=='ajaxgetlist'){
	/* 购买会员VIP服务订单 */
	$pindex =max(1,$_GPC['page']);
	$psize = 5;

	$memberorder_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_member_order). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND status=1 ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize);
	foreach($memberorder_list as $key=>$value){
		$memberorder_list[$key]['addtime'] = date('Y-m-d H:i', $value['addtime']);
		$memberorder_list[$key]['paytime'] = $value['paytime']>0?date('Y-m-d H:i', $value['paytime']):'';
		$memberorder_list[$key]['status']  = $value['status']==0?'未支付':'购买成功';
		if($value['paytype']=='credit'){
			$memberorder_list[$key]['paytype'] = '余额支付';
		}elseif($value['paytype']=='wechat'){
			$memberorder_list[$key]['paytype'] = '微信支付';
		}elseif($value['paytype']=='alipay'){
			$memberorder_list[$key]['paytype'] = '支付宝支付';
		}elseif($value['paytype']=='vipcard'){
			$memberorder_list[$key]['paytype'] = '服务卡支付';
		}
	}
	echo json_encode($memberorder_list);

}elseif($op=='vipcard'){
	$title = 'VIP服务卡开通服务';

	if(checksubmit('submit')){
		$password = trim($_GPC['card_password']);
		$vipcard = pdo_fetch("SELECT * FROM " .tablename($this->table_vipcard). " WHERE uniacid=:uniacid AND password=:password AND is_use=0 AND validity>:time ", array(':uniacid'=>$uniacid, ':password'=>$password, ':time'=>time()));
		if(!$vipcard){
			message("该服务卡不存在或已被使用", "", "warning");
		}

		$updata = array();
		$updata['vip'] = 1;
		if($lessonmember['vip']==0 || time()>$lessonmember['validity']){
			$updata['validity'] = time()+$vipcard['viptime']*86400;
		}elseif($lessonmember['vip']==1){
			$updata['validity'] = $lessonmember['validity']+$vipcard['viptime']*86400;
		}
		$updata['pastnotice'] = 0;
		$result = pdo_update($this->table_member, $updata, array('uniacid'=>$uniacid, 'openid'=>$openid));

		if($result){
			/* 构造购买会员订单信息 */
			$orderdata = array(
				'acid'		=> $_W['account']['acid'],
				'uniacid'	=> $uniacid,
				'ordersn'	=> date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8),
				'uid'		=> $uid,
				'openid'	=> $openid,
				'viptime'	=> $vipcard['viptime'],
				'vipmoney'	=> '0.00',
				'paytype'	=> 'vipcard',
				'status'	=> 1,
				'paytime'	=> time(),
				'refer_id'	=> $vipcard['id'],
				'addtime'	=> time(),
			);
			pdo_insert($this->table_member_order, $orderdata);

			$vipcardData = array(
				'is_use'	=> 1,
				'nickname'	=> $memberinfo['nickname'],
				'uid'		=> $uid,
				'openid'	=> $openid,
				'ordersn'	=> $orderdata['ordersn'],
				'use_time'	=> $orderdata['addtime'],
			);
			pdo_update($this->table_vipcard, $vipcardData, array('uniacid'=>$uniacid,'id'=>$vipcard['id']));

			/* 发送模版消息 */
			$sendmessage = array(
				'touser'      => $openid,
				'template_id' => $setting['buysucc'],
				'url'         => $_W['siteroot'] ."app/index.php?i={$uniacid}&c=entry&do=vip&m=fy_lesson",
				'topcolor'    => "#7B68EE",
				'data'        => array(
					 'name'	  => array(
						 'value' => "开通/续费VIP服务：[".urlencode($vipcard['viptime'])."]天",
						 'color' => "#26b300",
					 ),
					 'remark' => array(
						 'value' => urlencode("\\n过期时间：".date('Y-m-d H:i:s', $updata['validity'])),
						 'color' => "#e40606",
					 ),
			 
				  )
			);
			$this->send_template_message(urldecode(json_encode($sendmessage)),$orderdata['acid']);

			message("本次开通VIP服务时长{$vipcard['viptime']}天", $this->createMobileUrl('vip'), "success");
		}else{
			message("更新会员VIP状态失败，请稍候重试", "", "error");
		}
		
	}
	

	include $this->template('vip');
}

?>