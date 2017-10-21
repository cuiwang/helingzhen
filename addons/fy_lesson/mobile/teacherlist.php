<?php
/**
 * 讲师列表
 */
 
$uid = intval($_GPC['uid']);

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('teacherlist', array('uid'=>$uid));
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
$setting = pdo_fetch("SELECT sharelink,logo,sitename,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));


/* 更新微课程会员信息 */
$this->updatelessonmember($openid,$uid);

$pindex =max(1,$_GPC['page']);
$psize = 10;

$condition = " uniacid='{$uniacid}' AND status=1 ";
$keyword = trim($_GPC['keyword']);
if(!empty($keyword)){
	$condition .= " AND teacher LIKE '%{$keyword}%' ";
}
$teacherlist = pdo_fetchall("SELECT id,teacher,teacherdes,teacherphoto FROM " .tablename($this->table_teacher). " WHERE {$condition} ORDER BY id DESC  LIMIT " . ($pindex-1) * $psize . ',' . $psize);
foreach($teacherlist as $key=>$value){
	$teacherlist[$key]['teacherdes'] = strip_tags(htmlspecialchars_decode($value['teacherdes']));
	$teacherlist[$key]['lessonCount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " WHERE uniacid='{$uniacid}' AND teacherid='{$value['id']}' AND status=1");
}

if($op=='display'){
	$title = "讲师列表";
	/* 分享设置 */
	load()->model('mc');
	$shareuid = mc_openid2uid($openid);
	$sharelink = unserialize($setting['sharelink']);
	$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$shareuid));

	include $this->template('teacherlist');
}elseif($op=='ajaxgetteacherlist'){
	echo json_encode($teacherlist);
}

?>