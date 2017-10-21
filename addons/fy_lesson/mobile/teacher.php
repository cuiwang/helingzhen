<?php
/**
 * 讲师课程
 */
 
/* 讲师id */
$teacherid = intval($_GPC['teacherid']);
$uid = intval($_GPC['uid']);

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('teacher', array('teacherid'=>$teacherid, 'uid'=>$uid));
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
$setting = pdo_fetch("SELECT shareteacher,sitename,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));
$config = $this->module['config'];

/* 更新微课程会员信息 */
$this->updatelessonmember($openid,$uid);

/* 讲师信息 */
$teacher = pdo_fetch("SELECT * FROM " .tablename($this->table_teacher). " WHERE uniacid='{$uniacid}' AND id='{$teacherid}'");
if(empty($teacher)){
	message("该讲师不存在或已被删除！", "", "error");
}

/* 判断当前用户是否为讲师 */
if($openid==$teacher['openid']){
	$teacherself = true;
}

/* 查询是否收藏该课程 */
$collect = $collect = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_collect). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND outid='{$teacherid}' AND ctype=2 LIMIT 1");

$pindex =max(1,$_GPC['page']);
$psize = 10;

/* 讲师名下课程列表 */
$condition = " b.uniacid='{$uniacid}' AND b.id='{$teacherid}' AND a.status=1 ";

$lesson_list = pdo_fetchall("SELECT a.*,b.teacher,b.teacherphoto,b.teacherdes FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition} ORDER BY a.displayorder DESC,a.addtime DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize);

$student_num = 0;
foreach($lesson_list as $key=>$value){
	$visit = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_lesson_history) . " WHERE lessonid='{$value['id']}' ");
	if($value['price']>0){
		$student_num += $value['virtual_buynum']+$value['buynum'];
	}else{
		$student_num += $value['virtual_buynum']+$value['buynum']+$visit;
	}
	$lesson_list[$key]['price'] = $value['price']>0?$value['price'].' 元':'免费';
	if($value['price']>0){
		$lesson_list[$key]['virtualandbuynum'] = $value['virtual_buynum']+$value['buynum'];
	}else{
		$lesson_list[$key]['virtualandbuynum'] = $value['virtual_buynum']+$value['buynum']+$visit;
	}
	$lesson_list[$key]['seccount'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_son). " WHERE uniacid='{$uniacid}' AND parentid='{$value['id']}' AND status=1");
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE {$condition}");

if($op=='display'){
	$title = $teacher['teacher']."讲师主页";

	/* 分享信息 */
	load()->model('mc');
	$uid = mc_openid2uid($openid);

	$shareteacher = unserialize($setting['shareteacher']);
	$shareteacher['title'] = $shareteacher['title']?str_replace("【讲师名称】","[".$teacher['teacher']."]",$shareteacher['title']):substr(strip_tags(htmlspecialchars_decode($teacher['teacherdes'])), 0, 240);

	include $this->template('teacher');
}elseif($op=='ajaxgetlesson'){
	echo json_encode($lesson_list);
}

?>