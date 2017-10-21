<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 * @time 2016年1月26日 01：35 
 */
defined('IN_IA') or exit('Access Denied');

if ($_GPC['vfrom'] == 'photosvote') {
	$turl = $this->createMobileUrl('photosvote', array('rid' => $rid));
} elseif ($_GPC['vfrom'] == 'tuser') {
	$turl = $this->createMobileUrl('tuser', array('rid' => $rid));
} elseif ($_GPC['vfrom'] == 'tuserphotos') {
	$turl = $this->createMobileUrl('tuserphotos', array('rid' => $rid));
} else {
	$turl = referer();
}

if ($reply['subscribe'] == 1) {//判断关注情况
	if ($follow != 1) {
		$fmdata = array(
			"success" => -2,
			"linkurl" => $reply['shareurl'],
			"msg" => '请关注后参与活动，3秒后跳转到关注页面...',
		);
		echo json_encode($fmdata);
		exit;	
	}
}
if($now <= $reply['tstart_time'] || $now >= $reply['tend_time']) {//判断活动时间是否开始及提示
		
	if ($now <= $reply['tstart_time']) {
		$fmdata = array(
			"success" => -1,
			"msg" => $reply['ttipstart'],
		);
		echo json_encode($fmdata);
		exit;	
	}
	if ($now >= $reply['tend_time']) {
		$fmdata = array(
			"success" => -1,
			"msg" => $reply['ttipend'],
		);
		echo json_encode($fmdata);
		exit;	
	}
}

//查询是否参与活动
if(!empty($tfrom_user)) {
	$user = pdo_fetch("SELECT uid,realname,nickname,limitsd,photosnum,hits,xnphotosnum,xnhits  FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $tfrom_user,':rid' => $rid));
	if (empty($user)) {
		$url = $_W['siteroot'] .'app/'. $turl;
		//header("location:$url");
		$fmdata = array(
			"success" => -2,
			"linkurl" => $url,
			"msg" => '此用户还没有参与活动，3秒后返回...',
		);
		echo json_encode($fmdata);
		exit;	
	}
}else{
	$url = $_W['siteroot'] .'app/'. $turl;
	//header("location:$url");
	$fmdata = array(
		"success" => -2,
		"linkurl" => $url,
		"msg" => '没有此用户，3秒后返回...',
	);
	echo json_encode($fmdata);
	exit;	
}

$starttime=mktime(0,0,0);//当天：00：00：00
$endtime = mktime(23,59,59);//当天：23：59：59
$times = '';
$times .= ' AND createtime >=' .$starttime;
$times .= ' AND createtime <=' .$endtime;
if ($reply['isipv'] == 1) {//ip限制
	$mineip = getip();	
	$iplist = pdo_fetchall('SELECT * FROM '.tablename($this->table_iplist).' WHERE uniacid= :uniacid  AND  rid= :rid order by `createtime` desc ', array(':uniacid' => $uniacid, ':rid' => $rid));
	
	$totalip = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid  AND rid= :rid AND ip = :ip  '.$times.' order by `ip` desc ', array(':uniacid' => $uniacid, ':rid' => $rid, ':ip' => $mineip));
	
	$limitip = empty($reply['limitip']) ? '2' : $reply['limitip'] ;
	if ($totalip > $limitip && $reply['ipstopvote'] == 1) {
		$ipurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('stopip', array('from_user' => $from_user, 'rid' => $rid));
		$fmdata = array(
			"success" => 3,
			"linkurl" => $ipurl,
			"msg" => '你存在刷票的嫌疑或者您的网络不稳定，请重新进入！',
		);
		echo json_encode($fmdata);
		exit;	
	}
	
	$mineipz = sprintf("%u",ip2long($mineip));
	foreach ($iplist as $i) {
		$iparrs = iunserializer($i['iparr']);
		$ipstart = sprintf("%u",ip2long($iparrs['ipstart']));
		$ipend = sprintf("%u",ip2long($iparrs['ipend']));					
		if ($mineipz >= $ipstart && $mineipz <= $ipend) {						
			$ipdate = array(
				'rid' => $rid,
				'uniacid' => $uniacid,
				'avatar' => $avatar,
				'nickname' => $nickname,
				'from_user' => $from_user,
				'ip' => $mineip,
				'hitym' => 'tvote',
				'createtime' => time(),
			);
			$ipdate['iparr'] = getiparr($ipdate['ip']);
			pdo_insert($this->table_iplistlog, $ipdate);
			if ($reply['ipstopvote'] == 1) {
				$ipurl = $_W['siteroot'] .'app/'.$this->createMobileUrl('stopip', array('from_user' => $from_user, 'rid' => $rid));
				
				$fmdata = array(
					"success" => 3,
					"linkurl" => $ipurl,
					"msg" => '你存在刷票的嫌疑或者您的网络不稳定，请重新进入！',
				);
				echo json_encode($fmdata);
				exit;	
			}
			break;
		}
	}
}

if($_GPC['vote'] == '1') {
	if ($tfrom_user == $from_user) {//自己不能给自己投票
		$msg = '您不能为自己投票';
		$fmdata = array(
			"success" => -1,
			"msg" => $msg,
		);
		echo json_encode($fmdata);
		exit;	
	}
	if (!empty($reply['limitsd']) && !empty($reply['limitsdps'])) {// 全体投票限速
		$zf = date('H',time()) * 60 + date('i',time());
		$timeduan = intval((1440 / $reply['limitsd'])*($zf / 1440));//总时间段 288 当前时间段
		$cstime = $timeduan*$reply['limitsd'] * 60+mktime(0,0,0);//初始限制时间
		$jstime = ($timeduan+1)*$reply['limitsd'] * 60+mktime(0,0,0);//结束限制时间


		$tptimes = '';
		$tptimes .= ' AND createtime >=' .$cstime;
		$tptimes .= ' AND createtime <=' .$jstime;
		$limitsdvote = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND tfrom_user = :tfrom_user AND rid = :rid '.$tptimes.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':tfrom_user' => $tfrom_user, ':rid' => $rid));	// 全体当前时间段投票总数


		if ($cstime > 0) {
			if ($limitsdvote >= $reply['limitsdps']) {
				$msg = '亲，投票的速度太快了';
				$fmdata = array(
					"success" => -1,
					"msg" => $msg,
				);
				echo json_encode($fmdata);
				exit;	
			}
		}
	}
	if (!empty($user['limitsd'])){//个人单独投票限速
		$zf = date('H',time()) * 60 + date('i',time());
		$timeduan = intval((1440 / $user['limitsd'])*($zf / 1440));//总时间段 288 当前时间段
		$cstime = $timeduan*$user['limitsd'] * 60+mktime(0,0,0);//初始限制时间
		$jstime = ($timeduan+1)*$user['limitsd'] * 60+mktime(0,0,0);//结束限制时间


		$tptimesgr = '';
		$tptimesgr .= ' AND createtime >=' .$cstime;
		$tptimesgr .= ' AND createtime <=' .$jstime;
		$limitsdvotegr = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND tfrom_user = :tfrom_user AND rid = :rid '.$tptimesgr.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':tfrom_user' => $tfrom_user, ':rid' => $rid));	//每几分钟投几票 个人
		if ($user['limitsd'] > 0)  {
			if ($limitsdvotegr >= 1) {
				$msg = '亲，您投票的速度太快了';
				$fmdata = array(
					"success" => -1,
					"msg" => $msg,
				);
				echo json_encode($fmdata);
				exit;	
			}
		}
	}
	$fansmostvote = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND rid = :rid ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from_user, ':rid' => $rid));	//总共可以投几次
	if ($fansmostvote >= $reply['fansmostvote']) { //活动期间一共可以投多少次票限制（全部人）
		$msg = '在此活动期间，你总共可以投 '.$reply['fansmostvote'].' 票，目前你已经投完！';
		$fmdata = array(
			"success" => -1,
			"msg" => $msg,
		);
		echo json_encode($fmdata);
		exit;	
	}
	$daytpxz = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from_user,':rid' => $rid));//当天共投多少参赛者
	if ($daytpxz >= $reply['daytpxz']) {//每天总共投票的次数限制（全部人）
		$msg = '您当前最多可以投票'.$reply['daytpxz'].'个参赛选手，您当天的次数已经投完，请明天再来';
		$fmdata = array(
			"success" => -1,
			"msg" => $msg,
		);
		echo json_encode($fmdata);
		exit;	
	}
	$allonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from_user, ':tfrom_user' => $tfrom_user,':rid' => $rid));
	if ($allonetp >= $reply['allonetp']) {//在活动期间，给某个人总共投的票数限制（单个人）
		$msg = '您总共可以给他投票'.$reply['allonetp'].'次，您已经投完！';
		$fmdata = array(
			"success" => -1,
			"msg" => $msg,
		);
		echo json_encode($fmdata);
		exit;	
	}
	$dayonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from_user, ':tfrom_user' => $tfrom_user,':rid' => $rid));
	if ($dayonetp >= $reply['dayonetp']) {//每天总共可以给某个人投的票数限制（单个人）
		$msg = '您当天最多可以给他投票'.$reply['dayonetp'].'次，您已经投完，请明天再来';
		$fmdata = array(
			"success" => -1,
			"msg" => $msg,
		);
		echo json_encode($fmdata);
		exit;	
	}

	$votedate = array(
		'uniacid' => $uniacid,
		'rid' => $rid,
		'tptype' => '1',
		'avatar' => $avatar,
		'nickname' => $nickname,
		'from_user' => $from_user,
		'afrom_user' => $fromuser,
		'tfrom_user' => $tfrom_user,
		'ip' => getip(),
		'createtime' => $now
	);
	$votedate['iparr'] = getiparr($votedate['ip']);
	pdo_insert($this->table_log, $votedate);
	pdo_update($this->table_users, array('photosnum'=> $user['photosnum']+1,'hits'=> $user['hits']+1), array('rid' => $rid, 'from_user' => $tfrom_user,'uniacid' => $uniacid));
	
	if ($_W['account']['level'] == 4 && !empty($reply['messagetemplate'])) {
		$tuservote = array('rid' => $rid,'tfrom_user' => $tfrom_user,'from_user' => $from_user,'nickname' => $nickname,'realname' => $nickname,'createtime' => $now);
		$messagetemplate = $reply['messagetemplate'];
		$this->sendMobileVoteMsg($tuservote,$from_user, $messagetemplate);
	}
	$user['realname'] = !empty($user['realname']) ? $user['realname'] : $user['nickname'] ;
			
			
	$str = array('#编号#'=>$user['uid'],'#参赛人名#'=>$user['realname']);
	$res = strtr($reply['votesuccess'],$str);										
	$msg = '恭喜您成功的为编号为： '.$user['uid'].' ,姓名为： '.$user['realname'].' 的参赛者投了一票！';
	$msg = empty($res) ? $msg : $res ;
	$nowuser = pdo_fetch("SELECT photosnum, xnphotosnum FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $tfrom_user,':rid' => $rid));
	$photosnum = $nowuser['photosnum'] + $nowuser['xnphotosnum'];
	$ljtp = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename($this->table_log)." WHERE rid= ".$rid."") + pdo_fetchcolumn("SELECT sum(xnphotosnum) FROM ".tablename($this->table_users)." WHERE rid= ".$rid."");//累计投票
	$fmdata = array(
		"success" => 1,
		"photosnum" => $photosnum,
		"ljtp" => $ljtp,
		"msg" => $msg
	);
	echo json_encode($fmdata);
	exit;
}