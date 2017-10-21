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
		$vfrom = $_GPC['do'];
		if ($rvote['votepay']==1) {
			if ($_W['account']['level'] == 4) {
				$u_uniacid = $uniacid;
			}else{
				$u_uniacid = $cfg['u_uniacid'];
			}
			$pays = pdo_fetch("SELECT payment FROM " . tablename('uni_settings') . " WHERE uniacid='{$u_uniacid}' limit 1");
			$pay = iunserializer($pays['payment']);

			if (!empty($_GPC['paymore'])) {
				$paymore = iunserializer(base64_decode(base64_decode($_GPC['paymore'])));
				//print_r($paymore);
			}
			$payordersn = pdo_fetch("SELECT id,payyz,ordersn FROM " . tablename($this->table_order) . " WHERE rid='{$rid}' AND from_user = :from_user AND paytype = 2 ORDER BY id DESC,paytime DESC limit 1", array(':from_user'=>$from_user));
			$voteordersn = pdo_fetch("SELECT id FROM " . tablename($this->table_log) . " WHERE rid='{$rid}' AND from_user = :from_user AND ordersn = :ordersn  AND tptype =3 ORDER BY id DESC limit 1", array(':from_user'=>$from_user,':ordersn'=>$paymore['ordersn']));

		}
		if ($rdisplay['ipannounce'] == 1) {
			$announce = pdo_fetchall("SELECT nickname,content,createtime,url FROM " . tablename($this->table_announce) . " WHERE rid= '{$rid}' ORDER BY id DESC");
		}
		//赞助商
		if ($rdisplay['isvotexq'] == 1) {

			$advs = pdo_fetchall("SELECT advname,link,thumb FROM " . tablename($this -> table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND rid= '{$rid}'");
			$advarr = $this->get_advs($rid);
		}
		if ($rvote['isanswer'] == 1) {
			$answer = $this->get_answer($rid);
			$answers = iunserializer($answer['answer']);
		}
		//查询自己是否参与活动
		if(!empty($from_user)) {
		    $mygift = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));
		    $voteer = pdo_fetch("SELECT * FROM ".tablename($this->table_voteer)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));

		}
		//查询是否参与活动
		if(!empty($tfrom_user)) {
		    $user = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $tfrom_user,':rid' => $rid));
			if ($user['status'] != 1 && $tfrom_user != $from_user) {
				$urlstatus =  $_W['siteroot'] .'app/'.$this->createMobileUrl('photosvote',array('rid'=> $rid));
				echo "<script>alert('ID:".$user['uid']." 号选手正在审核中，请查看其他选手，谢谢！');location.href='".$urlstatus."';</script>";
				die();
		  		//message('该选手正在审核中，请查看其他选手，谢谢！',$this->createMobileUrl('photosvote',array('rid'=> $rid)),'error');
		  	}
			if ($user['ewm'] && $user['haibao'] && file_exists($user['haibao'])) {
				$ewmurl = tomedia($user['haibao']).'?v=' . $rid;
			}else{
				$url_send = $_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'duli'=> '1', 'fromuser' => $from_user, 'tfrom_user' => $tfrom_user));

				$url_jieguo = $this->wxdwz($url_send);
				$url = $url_jieguo['short_url'];

				$qrcode = array(
					//'expire_seconds' => '2592000',
					'action_name' => 'QR_LIMIT_SCENE',//QR_LIMIT_SCENE  QR_LIMIT_STR_SCENE
					'action_info' => array(
									'scene'	=> array(
												'scene_id' => cutstr($rid, '1') . $user['uid'],
												//'scene_str' => 't998'
											),
									),
				);
				$qrcodearr = base64_encode(iserializer($qrcode));
			}
		    if ($user) {
				$yuedu = $tfrom_user.$from_user.$rid.$uniacid;
				//setcookie("user_yuedu", -10000);
			   if ($_COOKIE["user_yuedup"] != $yuedu) {
					 pdo_update($this->table_users, array('hits' => $user['hits']+1,), array('rid' => $rid, 'from_user' => $tfrom_user));
					 setcookie("user_yuedup", $yuedu, time()+3600*24);
				}
		    }
		}
		$rjifen = pdo_fetch("SELECT is_open_jifen,is_open_jifen_sync,jifen_vote,jifen_vote_reg,jifen_reg FROM ".tablename($this->table_jifen)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$picarrs =  pdo_fetchall("SELECT id, photos,from_user FROM ".tablename($this->table_users_picarr)." WHERE from_user = :from_user AND rid = :rid ORDER BY isfm DESC ", array(':from_user' => $user['from_user'],':rid' => $rid));

		$starttime=mktime(0,0,0);//当天：00：00：00
		$endtime = mktime(23,59,59);//当天：23：59：59
		$times = '';
		$times .= ' AND createtime >=' .$starttime;
		$times .= ' AND createtime <=' .$endtime;
		$uservote = pdo_fetch("SELECT * FROM ".tablename($this->table_log)." WHERE from_user = :from_user  AND tfrom_user = :tfrom_user AND rid = :rid", array(':from_user' => $from_user,':tfrom_user' => $tfrom_user,':rid' => $rid));
		$uallonetp = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename($this->table_log).' WHERE from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid ORDER BY createtime DESC', array(':from_user' => $from_user, ':tfrom_user' => $tfrom_user,':rid' => $rid));
		$udayonetp = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename($this->table_log).' WHERE from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':from_user' => $from_user, ':tfrom_user' => $tfrom_user,':rid' => $rid));
		$unrname = !empty($user['realname']) ? $user['realname'] : $user['nickname'] ;

		$title = $unrname . '正在参加'. $rbasic['title'] .'，快来为'.$unrname.'投票吧！';

		$fmimage = $this->getpicarr($uniacid,$rid, $tfrom_user,1);

if (!empty($rshare['sharelink'])) {
	$_share['link'] = $rshare['sharelink'];
}else{
		$_share['link'] =$_W['siteroot'] .'app/'.$this->createMobileUrl('shareuserview', array('rid' => $rid,'duli'=> '2', 'fromuser' => $from_user, 'tfrom_user' => $tfrom_user));//分享URL
}
		 $_share['title'] = $unrname . '正在参加'. $rbasic['title'] .'，快来为'.$unrname.'投一票吧！';
		$_share['content'] = $unrname . '正在参加'. $rbasic['title'] .'，快来为'.$unrname.'投一票吧！';
		$_share['imgUrl'] =  $this->getphotos($fmimage['photos'],$user['avatar'],  $rbasic['picture']);



		$templatename = $rbasic['templates'];
		if ($templatename != 'default' && $templatename != 'stylebase') {
			require FM_CORE. 'fmmobile/tp.php';
		}
		$toye = $this->templatec($templatename,$_GPC['do']);
		include $this->template($toye);
