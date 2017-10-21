<?php
/**
 * 我的足迹
 */

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('history');
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
$setting = pdo_fetch("SELECT sitename,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$shareuid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$shareuid));

$pindex =max(1,$_GPC['page']);
$psize = 10;


$condition = " b.uniacid='{$uniacid}' AND b.openid='{$openid}' ";

$lessonlist = pdo_fetchall("SELECT a.id,a.bookname,a.images,a.price,a.buynum+a.virtual_buynum AS buynum, b.addtime FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_lesson_history). " b ON a.id=b.lessonid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize);
foreach($lessonlist as $key=>$value){
	$lessonlist[$key]['addtime'] = date('Y-m-d H:i', $value['addtime']);
	$lessonlist[$key]['seccount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE uniacid = '{$uniacid}' AND parentid = '{$value['id']}'");
}



if($op=='ajaxgetlist'){
	echo json_encode($lessonlist);
}else{
	$title = '我的足迹';
	include $this->template('history');
}

?>