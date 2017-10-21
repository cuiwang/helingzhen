<?php
/**
 * 收藏课程/讲师
 */

$ctype = intval($_GPC['ctype']); /* 收藏类型 */

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('collect', array('ctype'=>$ctype));
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
$setting = pdo_fetch("SELECT sharelink,sitename,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$shareuid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$shareuid));

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " b.uniacid='{$uniacid}' AND b.openid='{$openid}' ";
if($ctype==1){
	$title = '我收藏的课程';
	$condition .= "  AND b.ctype=1 ";
	$lessonlist = pdo_fetchall("SELECT a.id,a.bookname,a.images,a.price,a.buynum,a.virtual_buynum FROM " . tablename($this->table_lesson_parent) . " a LEFT JOIN " .tablename($this->table_lesson_collect). " b ON a.id=b.outid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize);
	foreach($lessonlist as $key=>$value){
		$lessonlist[$key]['price'] = $value['price']>0?$value['price']:'免费';
		$lessonlist[$key]['seccount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_son) . " WHERE uniacid = '{$uniacid}' AND parentid = '{$value['id']}'");
		$visit = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_history) . " WHERE lessonid='{$value['id']}' ");
		if($value['price']>0){
			$lessonlist[$key]['buynum_total'] = $value['buynum'] + $value['virtual_buynum'];
		}else{
			$lessonlist[$key]['buynum_total'] = $value['buynum'] + $value['virtual_buynum'] + $visit;
		}
	}

}elseif($ctype==2){
	$title = '我收藏的讲师';
	$condition .= "  AND b.ctype=2 ";
	$teacherlist = pdo_fetchall("SELECT a.id,a.teacher,a.teacherdes,a.teacherphoto FROM " . tablename($this->table_teacher) . " a LEFT JOIN " .tablename($this->table_lesson_collect). " b ON a.id=b.outid WHERE {$condition} ORDER BY b.addtime DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize);
	foreach($teacherlist as $key=>$value){
		$teacherlist[$key]['teacherdes'] = strip_tags(htmlspecialchars_decode($value['teacherdes']));
	}
}


if($op=='ajaxgetlesson'){
	echo json_encode($lessonlist);
}elseif($op=='ajaxgetteacher'){
	echo json_encode($teacherlist);
}else{
	include $this->template('collect');
}

?>