<?php
/**
 * 女神来了模块定义
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 * (c) Copyright 2016 FantasyMoons. All Rights Reserved.
 */
defined('IN_IA') or exit('Access Denied');
//查询自己是否参与活动
$now = time();
if (!empty($from_user)) {
	if($now <= $rbasic['tstart_time'] || $now >= $rbasic['tend_time']) {//判断活动时间是否开始及提示

		if ($now <= $rbasic['tstart_time']) {
			$fmdata = array(
				"success" => -1,
				"msg" => $rbasic['ttipstart'],
			);
			echo json_encode($fmdata);
			exit;
		}
		if ($now >= $rbasic['tend_time']) {
			$fmdata = array(
				"success" => -1,
				"msg" => $rbasic['ttipend'],
			);
			echo json_encode($fmdata);
			exit;
		}
	}
	$rjifen = pdo_fetch("SELECT * FROM ".tablename($this->table_jifen)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	if (!empty($tfrom_user)) {
		/**if ($from_user == $tfrom_user) {
			$fmdata = array(
				"success" => -1,
				"msg" => '自己不能给自己送礼物',
			);
			echo json_encode($fmdata);
			exit;
		}**/
		$usertogift = pdo_fetch("SELECT lasttime FROM " . tablename($this -> table_user_zsgift) . ' WHERE tfrom_user = :tfrom_user AND from_user = :from_user AND rid = :rid ORDER BY lasttime DESC limit 1', array(':tfrom_user' => $tfrom_user,':from_user' => $from_user,':rid' => $rid));

		$limittime = time() - 120;
		/**if ($usertogift['lasttime'] > $limittime) {
			$fmdata = array(
				"success" => -1,
				"msg" => '手速太快了，休息会再来',
			);
			echo json_encode($fmdata);
			exit;
		}**/

		$gift_times  = $this->gettpnum($rid, $from_user, '', '9');
		if ($gift_times >= $rjifen['jifen_gift_times']) {
			$fmdata = array(
				"success" => -1,
				"msg" => '今日已达最大送礼物次数，请明天再来',
			);
			echo json_encode($fmdata);
			exit;
		}
		$tuser = pdo_fetch("SELECT * FROM " . tablename($this -> table_users) . " WHERE from_user = :from_user and rid = :rid", array(':from_user' => $tfrom_user, ':rid' => $rid));

		$fmimage = $this->getpicarr($uniacid,$rid, $tfrom_user,1);

		$giftid = $_GPC['giftid'];
		$item = pdo_fetch("SELECT * FROM " . tablename($this -> table_jifen_gift) . ' WHERE id = :id ', array(':id' => $giftid));

		if (empty($item)) {
			$data = array(
				'success' => -1,
				'msg' => '没有找到您要送的礼物，请选择其他礼物'
			);
			echo json_encode($data);
			exit ;
		}
		$shuapiao = pdo_fetch("SELECT from_user , ip FROM ".tablename($this->table_shuapiao)." WHERE (from_user = :from_user OR ip = :ip) and rid = :rid LIMIT 1", array(':from_user' => $from_user,':ip' => getip(),':rid' => $rid));

		if($from_user == $shuapiao['from_user'] || $shuapiao['ip'] == getip()) {
			$msg = '你已被加入黑名单，无法赠送礼物';
			$fmdata = array(
				"success" => -1,
				"msg" => $msg,
			);
			echo json_encode($fmdata);
			exit;
		}else{
			$usergift = pdo_fetch("SELECT * FROM " . tablename($this -> table_user_gift) . ' WHERE giftid = :giftid AND from_user = :from_user AND rid = :rid AND status = 1 ', array(':giftid' => $giftid,':from_user' => $from_user,':rid' => $rid));

			if (!empty($usergift) && $usergift['giftnum'] > 0) {
				pdo_update($this->table_user_gift, array('giftnum' => $usergift['giftnum'] - 1, 'lasttime' => time()), array('rid' => $rid,'giftid' => $giftid, 'from_user'=>$from_user));
				$data = array(
					'uniacid' => $uniacid,
					'rid' => $rid,
					'giftid' => $giftid,
					'giftnum' => 1,
					'status' => 3,
					'from_user' => $from_user,
					'tfrom_user' => $tfrom_user,
					'lasttime' => time(),
					'createtime' => time(),
				);
				pdo_insert($this->table_user_zsgift, $data);
			}else{
				$userjf = $this->cxjifen($rid, $from_user);
				if ($item['jifen'] > $userjf) {
					$data = array(
						'success' => -1,
						'flag' => 1,
						'msg' => '您当前没有足够的积分兑换该礼物,请充值'
					);
					echo json_encode($data);
					exit ;
				}else{
					$data = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'giftid' => $giftid,
						'giftnum' => 1,
						'status' => 2,
						'from_user' => $from_user,
						'tfrom_user' => $tfrom_user,
						'lasttime' => time(),
						'createtime' => time(),
					);
					pdo_insert($this->table_user_zsgift, $data);
					$this->jsjifen($rid, $from_user, $item['jifen'],$item['gifttitle'],'zs');
				}
			}

			pdo_update($this->table_jifen_gift, array('dhnum' => $item['dhnum'] + 1), array('rid' => $rid,'id' => $giftid));
			$vote_times = $item['piaoshu'];
			preg_match('/\\((.*?)\\)/', $_SERVER['HTTP_USER_AGENT'], $mobileinfo);

			$votedate = array(
				'uniacid' => $uniacid,
				'rid' => $rid,
				'tptype' => '4',
				'vote_times' => $vote_times,
				'avatar' => $avatar,
				'nickname' => $nickname,
				'from_user' => $from_user,
				'afrom_user' => $fromuser,
				'tfrom_user' => $tfrom_user,
				'ip' => getip(),
				'mobile_info' => $mobileinfo['1'],
				'createtime' => $now
			);
			$votedate['iparr'] = getiparr($votedate['ip']);
			pdo_insert($this->table_log, $votedate);

			$tuser['realname'] = $this->getname($rid, $tfrom_user);




			$this->counter($rid, $from_user, $tfrom_user,'gift');
			pdo_update($this->table_users, array('photosnum'=>$tuser['photosnum']+$vote_times,'hits'=> $tuser['hits']+$vote_times), array('rid' => $rid, 'from_user' => $tfrom_user));
			pdo_update($this->table_reply_display, array('ljtp_total' => $rdisplay['ljtp_total']+$vote_times,'cyrs_total' => $rdisplay['cyrs_total']+$vote_times), array('rid' => $rid));//增加总投票 总人气
			$nickname = $this->getname($rid, $from_user);
			if ($vote_times > 0) {
				$fuhao = '增加了';
				$tcontent = '恭喜您，' . $nickname . '为您投了'.$vote_times.'票<br />' . $tmsg;
			}else{
				$fuhao = '减少了';
				$vote_times = str_replace("-", "", $vote_times);
				$tcontent = $nickname . '为您'.$fuhao.$vote_times.'票<br />' . $tmsg;
			}
			$msg = '赠送礼物成功，为编号： '.$tuser['uid'].' ,姓名为： '.$tuser['realname'].' 的参赛者 '.$fuhao.$vote_times.' 票！';

			$this->addmsg($rid,$from_user,$tfrom_user,'投票消息',$msg,'1');
			$this->addmsg($rid,$tfrom_user,'','被投票消息',$tcontent,'2');
			if ($_W['account']['level'] == 4 && !empty($rhuihua['messagetemplate'])) {
				$tuservote = array('rid' => $rid,'tfrom_user' => $tfrom_user,'from_user' => $from_user,'vote_times' => $vote_times,'nickname' => $nickname,'realname' => $nickname,'createtime' => $now);
				$messagetemplate = $rhuihua['messagetemplate'];
				$this->sendMobileVoteMsg($tuservote,$from_user, $messagetemplate);
			}

			$nowuser = pdo_fetch("SELECT photosnum, xnphotosnum FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $tfrom_user,':rid' => $rid));
			$photosnum = $nowuser['photosnum'] + $nowuser['xnphotosnum'];
			$ljtp = $rdisplay['ljtp_total'] + $rdisplay['xunips'];//累计投票
			$cyrs = $rdisplay['cyrs_total'] + $rdisplay['xuninum'];//累计人气
			$fmdata = array(
				"success" => 1,
				"photosnum" => $photosnum,
				"ljtp" => $ljtp+$vote_times,
				"cyrs" => $cyrs+$vote_times,
				"msg" => $msg
			);
			echo json_encode($fmdata);
			exit;
		}
	}else{
		$fmdata = array(
			"success" => -1,
			"msg" => '没有找到该参赛者',
		);
		echo json_encode($fmdata);
		exit;
	}

}else{
	$fmdata = array(
		"success" => -1,
		"msg" => '数据校验错误，缺少唯一识别码，请退出重新打开',
	);
	echo json_encode($fmdata);
	exit;
}

