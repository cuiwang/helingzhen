<?php
/**
 * 讲师收入提现记录

 */

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('lessoncashlog', array('op'=>$_GPC['op']));
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

/* 基本设置 */
$setting = pdo_fetch("SELECT is_sale,cash_lower,cash_type,sitename,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$uid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));

$pindex =max(1,$_GPC['page']);
$psize = 10;

$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_cashlog). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lesson_type=2 ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize);
foreach($list as $key=>$value){
	if($value['status']==0){
		$list[$key]['statu'] = "待打款";
	}elseif($value['status']==1){
		$list[$key]['statu'] = "已打款";
	}elseif($value['status']==-1){
		$list[$key]['statu'] = "无效佣金";
	}
	$list[$key]['disposetime'] = $value['status']!=0?date("Y-m-d", $value['disposetime']):"";
	$list[$key]['remark'] = $value['remark']?$value['remark']:"";
	$list[$key]['addtime'] = date("Y-m-d", $value['addtime']);
}
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_cashlog). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lesson_type=2");

$title = "讲师收入提现记录(".$total.")";

if(!$_W['isajax']){
	include $this->template('lessoncashlog');
}else{
	echo json_encode($list);
}


?>