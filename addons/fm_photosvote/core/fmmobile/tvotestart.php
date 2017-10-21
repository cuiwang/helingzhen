<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 * @time 2016年1月26日 01：35
 */
defined('IN_IA') or exit('Access Denied');

if ($_SERVER['HTTP_REFERER']) {
	if ($_GPC['vfrom'] == 'photosvote') {
		$turl = $this->createMobileUrl('photosvote', array('rid' => $rid));
	} elseif ($_GPC['vfrom'] == 'tuser') {
		$turl = $this->createMobileUrl('tuser', array('rid' => $rid));
	} elseif ($_GPC['vfrom'] == 'tuserphotos') {
		$turl = $this->createMobileUrl('tuserphotos', array('rid' => $rid));
	} else {
		$turl = referer();
	}
	if ($_W['account']['level'] == 4) {
		$u_uniacid = $uniacid;
	}else{
		$u_uniacid = $cfg['u_uniacid'];
	}
if ($rshare['subscribe'] == 1) {//判断关注情况
	if ($follow != 1) {
		$fmdata = array(
			"success" => -2,
			"linkurl" => empty($_W['account']['subscribeurl']) ? $rshare['shareurl'] : $_W['account']['subscribeurl'],
			"msg" => '请关注后参与活动，3秒后跳转到关注页面...',
		);
		echo json_encode($fmdata);
		exit;
	}
}


//if($now <= $rbasic['tstart_time'] || $now >= $rbasic['tend_time']) {//判断活动时间是否开始及提示

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
//}

//查询是否参与活动
if(!empty($tfrom_user)) {
	$user = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $tfrom_user,':rid' => $rid));
	$rjifen = pdo_fetch("SELECT is_open_jifen,is_open_jifen_sync,jifen_vote,jifen_vote_reg,jifen_reg FROM ".tablename($this->table_jifen)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
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
	if ($rbasic['isdaojishi']) {
		$limit_dtime = ($now - $user['createtime'])/86400;
		if ($limit_dtime >= $rbasic['votetime'] ) {
			$fmdata = array(
				"success" => -1,
				"msg" => $rbasic['ttipvote'],
			);
			echo json_encode($fmdata);
			exit;
		}

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

$paymore = empty($_GPC['paymore']) ? '' : $_GPC['paymore'];
$payordersn = empty($_GPC['payordersn']) ? '' : $_GPC['payordersn'];
$voteordersn = empty($_GPC['voteordersn']) ? '' : $_GPC['voteordersn'];

if (!empty($voteordersn)) {
	$fmdata = array(
		"success" => -2,
		"linkurl" => $turl,
		"msg" => '请勿刷票，否则拉入黑名单！',
	);
	echo json_encode($fmdata);
	exit;
}
preg_match('/\\((.*?)\\)/', $_SERVER['HTTP_USER_AGENT'], $mobileinfo);
$vote_times = !empty($paymore['vote_times']) ? $paymore['vote_times'] : max(1, intval($_GPC['vote_times']));

	if (!empty($paymore) && !empty($payordersn) && $rvote['votepay'] == 1 && $_W['account']['level'] == 4) {
		if ($paymore['paystatus'] == 'success' && $paymore['vote'] == '1' && $paymore['votepay'] == '1' && $paymore['ordersn'] == $payordersn['ordersn'] && $paymore['payyz'] == $payordersn['payyz']) {
			$tfrom_user = $paymore['tfrom_user'];
			$votedate = array(
				'uniacid' => $uniacid,
				'rid' => $rid,
				'tptype' => '3',
				'vote_times' => $vote_times,
				'avatar' => $avatar,
				'nickname' => $nickname,
				'from_user' => $from_user,
				'afrom_user' => $fromuser,
				'tfrom_user' => $tfrom_user,
				'ordersn' => $paymore['ordersn'],
				'ip' => getip(),
				'mobile_info' => $mobileinfo['1'],
				'createtime' => $now
			);
			$votedate['iparr'] = !empty($_GPC['lbslocal']) ? $_GPC['lbslocal'] : getiparr($votedate['ip']);
			pdo_insert($this->table_log, $votedate);
			pdo_update($this->table_order, array('ispayvote' => '1'), array('ordersn' => $paymore['ordersn']));

			$user['realname'] = $this->getname($rid, $tfrom_user);
			$str = array('#编号#'=>$user['uid'],'#参赛人名#'=>$user['realname'],'#投票票数#'=>$vote_times,'#积分#'=>$rjifen['jifen_vote']*$vote_times);
			$res = strtr($rvote['votesuccess'],$str);
			$msg = '恭喜您 '.$user['uid'].' 号参赛者 '.$user['realname'].' 投了 '.$vote_times.' 票！';

			if ($rjifen['is_open_jifen']) {
				$msg .= '<br />并增加'.$rjifen['jifen_vote']*$vote_times.'积分';
			}
			$msg = empty($res) ? $msg : $res ;
			$this->counter($rid, $from_user, $tfrom_user,'tp',$rvote['unimoshi']);
			//增加积分
			$this->addjifen($rid, $from_user, $tfrom_user,array($nickname,$avatar,$sex,$msg),array($uniacid, $vote_times, $rdisplay['ljtp_total'],$user['photosnum'],$user['hits'],$rdisplay['cyrs_total']));
			if ($_W['account']['level'] == 4 && !empty($rhuihua['messagetemplate'])) {
				$tuservote = array('rid' => $rid,'tfrom_user' => $tfrom_user,'from_user' => $from_user,'vote_times'=> $vote_times, 'nickname' => $nickname,'realname' => $nickname,'createtime' => $now);
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
				"msg" => $msg,
				"linkurl" => $turl
			);
			echo json_encode($fmdata);
			exit;
		}
	}else{
		if($_GPC['vote'] == '1' && $this->votecode($rid, $from_user,'fmoons') == $_GPC['vcd']) {
			if (empty($from_user)) {
				$fmdata = array(
						"success" => -1,
								"flaga" => 1,
						"msg" => '投票人出错'
					);
				echo json_encode($fmdata);
				exit();
			}/**
			if ($rvote['votenumpiao'] == 1 && ($rvote['giftvote'] || $rvote['votepay'])) {

			}else{

				/**if ($tfrom_user == $from_user) {//自己不能给自己投票
					$msg = '您不能为自己投票';
					$fmdata = array(
						"success" => -1,
								"flaga" => 1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit;
				}**
			}**/

			if (!empty($rvote['limitsd_voter']) && !empty($rvote['limitsdps_voter'])) {// 全体投票者限速

				$limitspeed = $this->limitSpeed($rid, $rvote['limitsd_voter'], $from_user, 'voter');
				if ($limitspeed['cstime'] > 0) {
					if ($limitspeed['limitsdvote'] >= $rvote['limitsdps_voter']) {
						$msg = '亲，您的投票的速度太快了，休息一会再来';
						$fmdata = array(
							"success" => -1,
								"flaga" => 1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit;
					}
				}
			}
			if (!empty($rvote['limitsd']) && !empty($rvote['limitsdps'])) {// 全体参赛者限速
				$limitspeed = $this->limitSpeed($rid, $rvote['limitsd'], $tfrom_user);

				if ($limitspeed['cstime'] > 0) {
					if ($limitspeed['limitsdvote'] >= $rvote['limitsdps']) {
						$msg = '亲，该参赛者此时间段已获得最大票数，请休息会再来';
						$fmdata = array(
							"success" => -1,
								"flaga" => 1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit;
					}
				}
			}
			if (!empty($user['limitsd'])){//单个参赛者限速
				$limitspeed = $this->limitSpeed($rid, $user['limitsd'], $tfrom_user);
				if ($user['limitsd'] > 0)  {
					if ($limitspeed['limitsdvote'] >= 1) {
						$msg = '亲，该参赛者此时间段已获得最大票数，请休息会再来';
						$fmdata = array(
							"success" => -1,
								"flaga" => 1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit;
					}
				}
			}

			if (!empty($rvote['usersmostvote'])) {
				if (time() <= $rvote['voteendtime'] && time() > $rvote['votestarttime']) {
					$usersmostvote = pdo_fetch('SELECT photosnum, xnphotosnum FROM '.tablename($this->table_users).' WHERE from_user = :tfrom_user AND rid = :rid limit 1', array(':tfrom_user' => $tfrom_user, ':rid' => $rid));	//总共可以参赛者总共可以得票数
					$mostvote = $usersmostvote['photosnum'] + $usersmostvote['xnphotosnum'];
					if ($mostvote >= $rvote['usersmostvote']) { //总共可以参赛者总共可以得票数
						if ($rvote['votepay'] && $rvote['votenumpiao']) {

						}else{
							$msg = '在 '.date('Y年m月d日', $rvote['votestarttime']) .' ~ '. date('Y年m月d日', $rvote['voteendtime']) .'期间，Ta总共可以获得 '.$rvote['usersmostvote'].' 票，无法在增加票数，感谢您的参与！';
							$fmdata = array(
								"success" => -1,
								"flaga" => 1,
								"msg" => $msg,
							);
							echo json_encode($fmdata);
							exit;
						}
					}
				}
			}
		$voteer = pdo_fetch("SELECT chance,is_user_chance FROM ".tablename($this->table_voteer)." WHERE from_user = :from_user and rid = :rid LIMIT 1", array(':from_user' => $from_user,':rid' => $rid));

		if ($voteer['chance'] < 1) {

			$tpxz_status = $this->gettpxz_status($rid, $from_user, $tfrom_user, '1', $rvote['fansmostvote']);
			if (!$tpxz_status) { //活动期间一共可以投多少次票限制（全部人）
				$msg = '在此活动期间，你总共可以投 '.$rvote['fansmostvote'].' 次，目前你已经投完！';

				if ($rvote['votepay'] && $rvote['votenumpiao'] && $_GPC['votepay']) {

				}else{
					$msg = '在此活动期间，你总共可以投 '.$rvote['fansmostvote'].' 次，目前你已经投完！';
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit;
				}
			}

			$tpxz_status = $this->gettpxz_status($rid, $from_user, $tfrom_user, '2', $rvote['daytpxz']);//每天总共投票的次数限制（全部人）
			if (!$tpxz_status) {//每天总共投票的次数限制（全部人）
				$msg = '您每天最多可以投 '.$rvote['daytpxz'].' 次，您当天的次数已经投完，请明天再来';
				if (($rvote['votepay'] || $rvote['giftvote']) && $rvote['votenumpiao'] ) {
					if ($_GPC['votepay']) {

					}else{
						$fmdata = array(
							"success" => -3,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit;
					}
				}else{
					$fmdata = array(
						"success" => -3,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit;
				}
			}

			$tpxz_status = $this->gettpxz_status($rid, $from_user, $tfrom_user, '3', $rvote['allonetp']);
			if (!$tpxz_status) {//在活动期间，给某个人总共投的票数限制（单个人）

				if ($rvote['votepay'] && $rvote['votenumpiao'] && $_GPC['votepay']) {

				}else{
					$msg = '您总共可以给他投 '.$rvote['allonetp'].' 次，您已经投完！';
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit;
				}
			}

			$tpxz_status = $this->gettpxz_status($rid, $from_user, $tfrom_user, '4', $rvote['dayonetp']);
			if (!$tpxz_status) {//每天总共可以给某个人投的票数限制（单个人）
				if ($rvote['votepay'] && $rvote['votenumpiao'] && $_GPC['votepay']) {

				}else{
					$msg = '您当天最多可以给他投 '.$rvote['dayonetp'].' 次，您已经投完，请明天再来';
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit;
				}
			}

			if ($rvote['unimoshi'] == 1) {

				$tpxz_status = $this->gettpxz_status($rid, $from_user, $tfrom_user, '5', $rvote['uni_fansmostvote']);
				if (!$tpxz_status) { //活动期间一共可以投多少次票限制（全部人）
					if ($rvote['votepay'] && $rvote['votenumpiao'] && $_GPC['votepay']) {

					}else{
						$msg = '在此活动期间，你总共可以投 '.$rvote['uni_fansmostvote'].' 次，目前你已经投完！';
						$fmdata = array(
							"success" => -1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit;
					}
				}

				$tpxz_status = $this->gettpxz_status($rid, $from_user, $tfrom_user, '6', $rvote['uni_daytpxz']);
				if (!$tpxz_status) {//每天总共投票的次数限制（全部人）
					$msg = '您每天最多可以投 '.$rvote['uni_daytpxz'].' 次，您当天的次数已经投完，请明天再来';
					if (($rvote['votepay'] || $rvote['giftvote']) && $rvote['votenumpiao'] ) {
						if ($_GPC['votepay']) {

						}else{
							$fmdata = array(
								"success" => -3,
								"msg" => $msg,
							);
							echo json_encode($fmdata);
							exit;
						}
					}else{
						$fmdata = array(
							"success" => -1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit;
					}
				}

				$tpxz_status = $this->gettpxz_status($rid, $from_user, $tfrom_user, '7', $rvote['uni_allonetp']);
				if (!$tpxz_status) {//在活动期间，给某个人总共投的票数限制（单个人）
					if ($rvote['votepay'] && $rvote['votenumpiao'] && $_GPC['votepay']) {

					}else{
						$msg = '您总共可以给他投 '.$rvote['uni_allonetp'].' 次，您已经投完！';
						$fmdata = array(
							"success" => -1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit;
					}
				}

				$tpxz_status = $this->gettpxz_status($rid, $from_user, $tfrom_user, '8', $rvote['uni_dayonetp']);
				if (!$tpxz_status) {//每天总共可以给某个人投的票数限制（单个人）
					if ($rvote['votepay'] && $rvote['votenumpiao'] && $_GPC['votepay']) {

					}else{
						$msg = '您当天最多可以给他投 '.$rvote['uni_dayonetp'].' 次，您已经投完，请明天再来';
						$fmdata = array(
							"success" => -1,
							"msg" => $msg,
						);
						echo json_encode($fmdata);
						exit;
					}
				}
			}


		}

			if ($rvote['votenumpiao']==1 || $rvote['voteerinfo']==1) {
				if ($_GPC['up_voteer'] == 1 && $rvote['voteerinfo'] == 1) {

					$update_voteer = $this->updatevoteer($rid, $from_user,$_GPC['vote_user_realname'],$_GPC['vote_user_mobile']);
					if (!empty($update_voteer)) {
						$fmdata = array(
							"success" => -1,
							"msg" => $update_voteer,
						);
						echo json_encode($fmdata);
						exit;
					}
				}
			}

			if ($_GPC['votepay'] == 1 && $_GPC['paystatus'] != 'success') {
				//付款
				$ordersn = date('ymdhis') . random(4, 1);

				$price = $rvote['votepayfee'] * $vote_times;
				$datas = array(
					'uniacid' => $uniacid,
					'weid' => $uniacid,
					'rid' => $rid,
					'from_user' => $from_user,
					'tfrom_user' => $tfrom_user,
					'fromuser' => $fromuser,
					'ordersn' => $ordersn,
					'payyz' => '',
					'title' => $rvote['votepaytitle'],
					'price' => $price,
					'vote_times' => $vote_times,
					'realname' => $nickname,
					'status' => '0',
					'paytype' => '2',
					'ispayvote' => '2',
					'ip' => getip(),
					'createtime' => time(),
				);
				$datas['iparr'] = !empty($_GPC['lbslocal']) ? $_GPC['lbslocal'] : getiparr($datas['ip']);
				pdo_insert($this->table_order, $datas);
				$log = pdo_get('core_paylog', array('uniacid' => $uniacid, 'module' => $this->module['name'], 'tid' => $ordersn));
				//在pay方法中，要检测是否已经生成了paylog订单记录，如果没有需要插入一条订单数据
				//未调用系统pay方法的，可以将此代码放至自己的pay方法中，进行漏洞修复
				if (empty($log)) {
			        $log = array(
		                'uniacid' => $uniacid,
		                'acid' => $_W['acid'],
		                'openid' => $from_user,
		                'module' => $this->module['name'], //模块名称，请保证$this可用
		                'tid' => $ordersn,
		                'fee' => $price,
		                'card_fee' => $price,
		                'status' => '0',
		                'is_usecard' => '0',
			        );
			        pdo_insert('core_paylog', $log);
				}

				$toparams = array();
				$toparams['tid'] = $ordersn;
				$toparams['rid'] = $rid;
				$toparams['user'] = $from_user;
				$toparams['fee'] = $price;
				$toparams['title'] = $rvote['votepaytitle'];
				$toparams['content'] = $rvote['votepaydes'];
				$toparams['ordersn'] = $ordersn;
				$toparams['module'] = $this->module['name'];
				$toparams['payyz'] = random(8);
				$toparams['virtual'] = false;
				$entoparams = base64_encode(json_encode($toparams));
				$fmdata = array(
						"success" => 1,
						"flag" => 1,
						"votenum" => $vote_times,
						"votefee" => sprintf('%.2f', $price),
						"params" => $entoparams,
						"toparams" => $toparams,
						"msg" => '付款中',
					);
				echo json_encode($fmdata);
				exit();
			}else{
				$shuapiao = pdo_fetch("SELECT from_user , ip FROM ".tablename($this->table_shuapiao)." WHERE (from_user = :from_user OR ip = :ip OR ua = :ua) and rid = :rid LIMIT 1", array(':from_user' => $from_user,':ip' => getip(), ':ua' => $mobileinfo['1'],':rid' => $rid));

				if($from_user == $shuapiao['from_user'] || $shuapiao['ip'] == getip() || $shuapiao['ua'] == $mobileinfo['1']) {
					$msg = '你已被加入黑名单！';
					$fmdata = array(
						"success" => -1,
						"msg" => $msg,
					);
					echo json_encode($fmdata);
					exit;
				}else{
					$votedate = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'tptype' => '1',
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

					$votedate['iparr'] = !empty($_GPC['lbslocal']) ? $_GPC['lbslocal'] : getiparr($votedate['ip']);
					pdo_insert($this->table_log, $votedate);
					$this->counter($rid, $from_user, $tfrom_user,'tp',$rvote['unimoshi']);
					$user['realname'] = $this->getname($rid, $tfrom_user);
					$str = array('#编号#'=>$user['uid'],'#参赛人名#'=>$user['realname'],'#投票票数#'=>$vote_times,'#积分#'=>$rjifen['jifen_vote']*$vote_times);
					$res = strtr($rvote['votesuccess'],$str);
					$msg = '恭喜您 '.$user['uid'].' 号参赛者 '.$user['realname'].' 投了 '.$vote_times.' 票！';

					if ($rjifen['is_open_jifen']) {
						$msg .= '<br />并增加'.$rjifen['jifen_vote']*$vote_times.'积分';
					}
					$msg = empty($res) ? $msg : $res ;
					//增加积分
					$this->addjifen($rid, $from_user, $tfrom_user,array($nickname,$avatar,$sex,$msg),array($uniacid, $vote_times, $rdisplay['ljtp_total'],$user['photosnum'],$user['hits'],$rdisplay['cyrs_total']));
					if ($rvote['isanswer'] == 1) {
						if ($voteer['chance'] > 0) {
							pdo_update($this->table_voteer, array('chance -=' => 1), array('rid' => $rid, 'from_user'=>$from_user));//写入答题
						}
					}
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
			}
		}else{
			$fmdata = array(
				"success" => -1,
				"msg" => '投票异常，请重新进入活动投票'
			);
			echo json_encode($fmdata);
			exit;
		}
	}
}else {
  	$fmdata = array(
		"success" => -1,
		"msg" => '请勿刷票，否则拉入黑名单！[Do not brush votes, or pull into the blacklist]  ip: '.getip() . ' is recorded',
	);
	echo json_encode($fmdata);
	exit;
}