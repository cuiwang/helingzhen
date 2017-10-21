<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
if ($rshare['isopentime']) {
	if ($_COOKIE["user_limittime"]) {
		echo "<script>location.href='" . $rshare['open_url'] . "';</script>";
		die();
	}else{
		if (empty($from_user) || $follow != 1) {
			setcookie("user_limittime", '1', time()+$rshare['open_limittime']*60);
		}
	}

}
$regtitlearr = iunserializer($rdisplay['regtitlearr']);
$vfrom = $_GPC['do'];
if ($rvote['votepay'] == 1) {
	if ($_W['account']['level'] == 4) {
		$u_uniacid = $uniacid;
	} else {
		$u_uniacid = $cfg['u_uniacid'];
	}
	$pays = pdo_fetch("SELECT payment FROM " . tablename('uni_settings') . " WHERE uniacid='{$u_uniacid}' limit 1");
	$pay = iunserializer($pays['payment']);

	if (!empty($_GPC['paymore'])) {
		$paymore = iunserializer(base64_decode(base64_decode($_GPC['paymore'])));
		//print_r($paymore);
	}
	$payordersn = pdo_fetch("SELECT id,payyz,ordersn FROM " . tablename($this -> table_order) . " WHERE rid='{$rid}' AND from_user = :from_user AND paytype = 2 ORDER BY id DESC,paytime DESC limit 1", array(':from_user' => $from_user));
	$voteordersn = pdo_fetch("SELECT id FROM " . tablename($this -> table_log) . " WHERE rid='{$rid}' AND from_user = :from_user AND ordersn = :ordersn  AND tptype =3 ORDER BY id DESC limit 1", array(':from_user' => $from_user, ':ordersn' => $paymore['ordersn']));

}
if ($rdisplay['ipannounce'] == 1) {
	$announce = pdo_fetchall("SELECT nickname,content,createtime,url FROM " . tablename($this -> table_announce) . " WHERE rid= '{$rid}' ORDER BY id DESC");
}

//赞助商
if ($rdisplay['isvotexq'] == 1) {
	$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this -> table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND rid= '{$rid}'");
	$advarr = $this -> get_advs($rid);
}
if ($rvote['isanswer'] == 1) {
	$answer = $this -> get_answer($rid);
	$answers = iunserializer($answer['answer']);
}
//查询自己是否参与活动
if (!empty($from_user)) {
	$mygift = pdo_fetch("SELECT * FROM " . tablename($this -> table_users) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user, ':rid' => $rid));
	$voteer = pdo_fetch("SELECT * FROM " . tablename($this -> table_voteer) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user, ':rid' => $rid));
}
//查询是否参与活动
if (!empty($tfrom_user)) {
	$user = pdo_fetch("SELECT * FROM " . tablename($this -> table_users) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $tfrom_user, ':rid' => $rid));
	if ($user['status'] != 1 && $tfrom_user != $from_user) {
		$urlstatus = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('photosvote', array('rid' => $rid));
		echo "<script>alert('ID:" . $user['uid'] . " 号选手正在审核中，请查看其他选手，谢谢！');location.href='" . $urlstatus . "';</script>";
		die();
		//message('该选手正在审核中，请查看其他选手，谢谢！',$this->createMobileUrl('photosvote',array('rid'=> $rid)),'error');
	}
	if ($rdisplay['openqr']) {
		if ($user['ewm'] && $user['haibao'] && file_exists($user['haibao'])) {
			$ewmurl = tomedia($user['haibao']) . '?v=' . $rid;
		} else {
			$url_send = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('shareuserview', array('rid' => $rid, 'duli' => '1', 'fromuser' => $from_user, 'tfrom_user' => $tfrom_user));

			$url_jieguo = $this -> wxdwz($url_send);
			$url = $url_jieguo['short_url'];

			$qrcode = array(
			//'expire_seconds' => '2592000',
			'action_name' => 'QR_LIMIT_SCENE', //QR_LIMIT_SCENE  QR_LIMIT_STR_SCENE
			'action_info' => array('scene' => array('scene_id' => cutstr($rid, '1') . $user['uid'],
			//'scene_str' => 't998'
			), ), );
			$qrcodearr = base64_encode(iserializer($qrcode));
		}
	}
	if ($user) {
		$paihangcha = $this -> GetPaihangcha($rid, $tfrom_user, $rdisplay['indexpx']);
		$yuedu = $from_user . $rid . $uniacid;
		if (time() == mktime(0, 0, 0)) {
			setcookie("user_yuedu", -10000);
		}
		//
		if ($_COOKIE["user_yuedu"] != $yuedu) {
			pdo_update($this -> table_users, array('hits' => $user['hits'] + 1), array('rid' => $rid, 'from_user' => $tfrom_user));
			setcookie("user_yuedu", $yuedu, time() + 3600 * 24);
		}
		//print_r($tfrom_user);
	} else {
		$url = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('photosvote', array('rid' => $rid));
		header("location:$url");
		exit ;
	}
	$tagname = $this -> gettagname($user['tagid'], $user['tagpid'], $user['tagtid'], $rid);
	$source = pdo_fetch("SELECT title FROM " . tablename($this -> table_source) . " WHERE rid = '{$rid}' AND id = :id", array(':id' => $user['sourceid']));
	$school = pdo_fetch("SELECT title FROM " . tablename($this -> table_school) . " WHERE rid = '{$rid}' AND id = :id", array(':id' => $user['schoolid']));
}
$sharenum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> table_data) . " WHERE tfrom_user = :tfrom_user and rid = :rid", array(':tfrom_user' => $tfrom_user, ':rid' => $rid)) + pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> table_data) . " WHERE fromuser = :fromuser and rid = :rid", array(':fromuser' => $tfrom_user, ':rid' => $rid)) + $user['sharenum'];

//$picarr = $this->getpicarr($uniacid,$reply['tpxz'],$tfrom_user,$rid);
$fmimage = $this -> getpicarr($uniacid, $rid, $tfrom_user, 1);
$picarrs = pdo_fetchall("SELECT id, photos,from_user,isfm FROM " . tablename($this -> table_users_picarr) . " WHERE from_user = :from_user AND rid = :rid ORDER BY isfm DESC", array(':from_user' => $user['from_user'], ':rid' => $rid));
$level = $this -> fmvipleavel($rid, $uniacid, $user['from_user']);
$rjifen = pdo_fetch("SELECT is_open_jifen,is_open_jifen_sync,jifen_vote,jifen_vote_reg,jifen_reg FROM " . tablename($this -> table_jifen) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
$zsgift = pdo_fetchall("SELECT *, SUM(giftnum) as num FROM " . tablename($this -> table_user_zsgift) . ' WHERE rid = :rid AND tfrom_user = :tfrom_user ' . $uni . ' GROUP BY giftid ASC', array(':rid' => $rid, ':tfrom_user' => $tfrom_user));
foreach ($zsgift as $key => $value) {
	$g = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ' . $uni . '', array(':id' => $value['giftid']));
	$zsgift[$key]['title'] = cutstr($g['gifttitle'], '4');
	$zsgift[$key]['images'] = tomedia($g['images']);
}
$total_gift = $this -> getgiftnum($rid, $tfrom_user, $uni);
	if ($rvote['isopengiftlist'] && $rjifen['is_open_jifen']) {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_user_zsgift) . ' WHERE rid = :rid AND tfrom_user = :tfrom_user '.$uni.'', array(':rid' => $rid,':tfrom_user' => $tfrom_user));
		$giftdata = pdo_fetchall("SELECT * FROM " . tablename($this->table_user_zsgift) . ' WHERE rid = :rid AND tfrom_user = :tfrom_user '.$uni.' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':rid' => $rid,':tfrom_user' => $tfrom_user));

		foreach ($giftdata as $key => $value) {
			//print_r($value);
			$g = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ' . $uni . '', array(':id' => $value['giftid']));
			$giftdata[$key]['title'] = cutstr($g['gifttitle'], '10');
			$giftdata[$key]['des'] = empty($g['description']) ?  $g['gifttitle'] : $g['description'] ;
			$giftdata[$key]['images'] = tomedia($g['images']);
			$giftdata[$key]['lasttime'] = date('m-d H:i', $value['lasttime']);
			$giftdata[$key]['piaoshu'] = $g['piaoshu'];
			$giftdata[$key]['jifen'] = $g['jifen'];
			$giftdata[$key]['time'] = date('Y-m-d h:i:s', $value['lasttime']);
			$giftdata[$key]['username'] = '<div style="display: inline;"><img class="ysimg pull-left" src="'.$this->getname($rid, $value['from_user'],'20', 'avatar').'" width="30" height="30" style="border-radius: 30px;display: inherit;    border-radius: 30px;margin-right: 10px;"></div><div style="display: inline;"><span class="ystext">'.$this->getname($rid, $value['from_user']).'</span>';

		}
		$pager = pagination($total, $pindex, $psize);
	}




if ($rbasic['isdaojishi'] == 1) {
	$starttime = mktime(0, 0, 0);
	//当天：00：00：00
	$endtime = mktime(23, 59, 59);
	//当天：23：59：59
	$times = '';
	$times .= ' AND createtime >=' . $starttime;
	$times .= ' AND createtime <=' . $endtime;

	$uservote = pdo_fetch("SELECT * FROM " . tablename($this -> table_log) . " WHERE from_user = :from_user  AND tfrom_user = :tfrom_user AND rid = :rid", array(':from_user' => $from_user, ':tfrom_user' => $tfrom_user, ':rid' => $rid));
	$uallonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this -> table_log) . ' WHERE from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid  ORDER BY createtime DESC', array(':from_user' => $from_user, ':tfrom_user' => $tfrom_user, ':rid' => $rid));

	$udayonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this -> table_log) . ' WHERE from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid ' . $times . ' ORDER BY createtime DESC', array(':from_user' => $from_user, ':tfrom_user' => $tfrom_user, ':rid' => $rid));

}

if ($rdisplay['isvoteusers']) {
	$voteuserlist = pdo_fetchall('SELECT avatar,nickname FROM ' . tablename($this -> table_log) . ' WHERE rid = :rid  AND tfrom_user = :tfrom_user GROUP BY `nickname` ORDER BY `id` DESC LIMIT 5', array(':rid' => $rid, ':tfrom_user' => $tfrom_user));
}

if ($rvote['isbbsreply'] == 1) {//开启评论
	//取得用户列表
	$bbsreply = pdo_fetchall("SELECT avatar,nickname,from_user,content,zan,createtime FROM " . tablename($this -> table_bbsreply) . " WHERE tfrom_user = :tfrom_user AND rid = :rid AND is_del = 0 AND status = 1 ORDER BY `id` DESC LIMIT 10", array(':tfrom_user' => $tfrom_user, ':rid' => $rid));
	$btotal = $this -> getcommentnum($rid, $uniacid, $tfrom_user);
}
if ($rbasic['isdaojishi']) {
	$votetime = $rbasic['votetime'] * 3600 * 24;
	$isvtime = TIMESTAMP - $user['createtime'];
	$ttime = $votetime - $isvtime;

	if ($ttime > 0) {
		$totaltime = $ttime;
	} else {
		$totaltime = 0;
	}
}

$now = time();
if ($now - $rdisplay['xuninum_time'] > $rdisplay['xuninumtime']) {
	pdo_update($this -> table_reply_display, array('xuninum_time' => $now, 'xuninum' => $rdisplay['xuninum'] + mt_rand($rdisplay['xuninuminitial'], $rdisplay['xuninumending'])), array('rid' => $rid));
}
$unrname = !empty($user['realname']) ? $user['realname'] : $user['nickname'];

$title = $unrname . '正在参加' . $rbasic['title'] . '，快来为' . $unrname . '投票及拉票吧！';

if (!empty($rbody)) {
	$rbody_tuser = iunserializer($rbody['rbody_tuser']);
}
if (!empty($rshare['sharelink'])) {
	$_share['link'] = $rshare['sharelink'];
} else {
	$_share['link'] = $_W['siteroot'] . 'app/' . $this -> createMobileUrl('shareuserview', array('rid' => $rid, 'duli' => '1', 'fromuser' => $from_user, 'tfrom_user' => $tfrom_user));
	//分享URL
}
$_share['title'] = $unrname . '正在参加' . $rbasic['title'] . '，快来为' . $unrname . '投票吧！';
$_share['content'] = $unrname . '正在参加' . $rbasic['title'] . '，快来为' . $unrname . '投票吧！';
$_share['imgUrl'] = !empty($user['haibao']) ? tomedia($user['haibao']) : $this -> getphotos($fmimage['photos'], $user['avatar'], $rbasic['picture']);

$templatename = $rbasic['templates'];
if ($templatename != 'default' && $templatename != 'stylebase') {
	require FM_CORE . 'fmmobile/tp.php';
}
$toye = $this -> templatec($templatename, $_GPC['do']);
include $this -> template($toye);
