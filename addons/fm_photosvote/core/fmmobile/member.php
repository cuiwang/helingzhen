<?php
/**
 * 女神来了模块定义
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 * (c) Copyright 2016 FantasyMoons. All Rights Reserved.
 */
defined('IN_IA') or exit('Access Denied');

$regtitlearr = iunserializer($rdisplay['regtitlearr']);
$vfrom = $_GPC['do'];

if ($rdisplay['ipannounce'] == 1) {
	$announce = pdo_fetchall("SELECT nickname,content,createtime,url FROM " . tablename($this -> table_announce) . " WHERE rid= '{$rid}' ORDER BY id DESC");
}
//赞助商
if ($rdisplay['isvotexq'] == 1) {
	$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this -> table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND rid= '{$rid}' AND issuiji = 1");

	if (!empty($advs)) {
		$adv = array_rand($advs);
		$advarr = array();
		$advarr['thumb'] .= toimage($advs[$adv]['thumb']);
		$advarr['advname'] .= cutstr($advs[$adv]['advname'], '10');
		$advarr['link'] .= $advs[$adv]['link'];
	}
}

//查询自己是否参与活动
if (!empty($from_user)) {
	$user = pdo_fetch("SELECT * FROM " . tablename($this -> table_users) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user, ':rid' => $rid));
	$voteer = pdo_fetch("SELECT * FROM " . tablename($this -> table_voteer) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user, ':rid' => $rid));
	if (!empty($voteer)) {
		pdo_update($this -> table_voteer, array('lasttime' => time()), array('rid'=>$rid, 'from_user'=>$from_user) );
	}
	//$tagname = $this -> gettagname($user['tagid'], $user['tagpid'], $rid);

	//$fmimage = $this -> getpicarr($uniacid, $rid, $tfrom_user, 1);
	//$picarrs = pdo_fetchall("SELECT id, photos,from_user,isfm FROM " . tablename($this -> table_users_picarr) . " WHERE from_user = :from_user AND rid = :rid ORDER BY isfm DESC", array(':from_user' => $user['from_user'], ':rid' => $rid));
	//$votes = $this->gettvotes($rid, $from_user, $rvote['indexpx']);
	$level = $this -> fmvipleavel($rid, $uniacid, $user['from_user']);
	$mygift = $this->getmygift($rid,$from_user);
	if ($rvote['isbbsreply'] == 1) {//开启评论
		//取得用户列表
		$bbsreply = pdo_fetchall("SELECT avatar,nickname,from_user,tfrom_user,content,zan,createtime FROM " . tablename($this -> table_bbsreply) . " WHERE (from_user = :from_user OR tfrom_user = :tfrom_user) AND rid = :rid ORDER BY `createtime` DESC", array(':from_user' => $from_user,':tfrom_user' => $from_user, ':rid' => $rid));
		$btotal = $this -> getcommentnum($rid, $uniacid, $from_user, 1);
	}
	$msg = $this->getmsg($rid, $from_user);

	$now = time();

	$unrname = !empty($user['realname']) ? $user['realname'] : $user['nickname'];

	$title = $unrname . '正在参加' . $rbasic['title'] . '，快来为' . $unrname . '投票吧！';
if (!empty($rshare['sharelink'])) {
	$_share['link'] = $rshare['sharelink'];
}else{
	$_share['link'] = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('shareuserview', array('rid' => $rid, 'duli' => '1', 'fromuser' => $from_user, 'tfrom_user' => $from_user));
	//分享URL
}
	$_share['title'] = $unrname . '正在参加' . $rbasic['title'] . '，快来为' . $unrname . '投票吧！';
	$_share['content'] = $unrname . '正在参加' . $rbasic['title'] . '，快来为' . $unrname . '投票吧！';
	//$_share['imgUrl'] = !empty($user['photo']) ? toimage($user['photo']) : toimage($user['avatar']);
	$_share['imgUrl'] = $this -> getphotos($fmimage['photos'], $user['avatar'], $rbasic['picture']);
}
$templatename = $rbasic['templates'];
if ($templatename != 'default' && $templatename != 'stylebase') {
	require FM_CORE . 'fmmobile/tp.php';
}
$toye = $this -> templatec($templatename, $_GPC['do']);
include $this -> template($toye);
