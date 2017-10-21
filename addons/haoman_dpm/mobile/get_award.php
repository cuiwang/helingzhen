<?php
global $_GPC, $_W;
$rid = intval($_GPC['id']);
$uniacid = $_W['uniacid'];

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {

	header("HTTP/1.1 301 Moved Permanently");
	header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
	exit();
}

$fansID = $_W['member']['uid'];
$credit1 = $_W['member']['credit1'];


$from_user = $_W['fans']['from_user'];
$avatar = $_W['fans']['tag']['avatar'];
$nickname = $_W['fans']['nickname'];

load()->model('account');
$_W['account'] = account_fetch($_W['acid']);
$cookieid = '__cookie_haoman_dpm_201606186_' . $rid;
$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
if ($_W['account']['level'] != 4) {
    $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
    $avatar = $cookie['avatar'];
	$nickname = $cookie['nickname'];
}


if (empty($from_user)) {
	$this->message(array("success" => 2,'level'=>1, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
}



//开始抽奖咯
$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
if ($reply == false) {
	$this->message(array("success" => 2,'level'=>1, "msg" => '规则出错！...'), "");
}

if ($reply['isqhbshow'] != 1) {
	//活动已经暂停,请稍后...
	$this->message(array("success" => 2,'level'=>1, "msg" => '活动还没开始或是已经结束，请关注大屏幕的提示。'), "");
}

 if ($reply['isqhb'] == 1) {
 	//活动已经暂停,请稍后...
 	$this->message(array("success" => 2,'level'=>1, "msg" => '活动已结束，请关注大屏幕的提示。'), "");
 }


if (!empty($reply['share_url'])) {
	//判断是否为关注用户
	$fansID = $_W['member']['uid'];
	$follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where uid=:uid and uniacid=:uniacid order by `fanid` desc", array(":uid" => $fansID, ":uniacid" => $uniacid));
	if ($follow == 0) {
		$this->message(array("success" => 3,'level'=>1, "msg" => '您还未关注公共账号！'), "");
	}

}
//判断是否为关注用户
$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = " . $rid . " and from_user='" . $from_user . "'and isbaoming=0");
if ($fans == false) {
	$this->message(array("success" => 5,'level'=>1, "msg" => '获取不到您的会员信息，请刷新页面重试!'), "");
}

if(empty($nickname)){
    $nickname = $fans['nickname'];
}

if(empty($avatar)){
    $avatar = tomedia($fans['avatar']);
}


//if ($fans['todaynum'] >= $reply['most_num_times'] && $reply['most_num_times'] > 0) {
//	//$this->message('', '超过当日限制次数');
//	$this->message(array("success" => 4,'level'=>1, "msg" => '您超过抽奖次数了!'), "");
//}

//所有奖品
$gift = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . " where rid = :rid and uniacid=:uniacid and turntable=2 order by Rand()", array(':rid' => $rid, ':uniacid' => $uniacid));

$rate = 1;
foreach ($gift as $giftxiao) {
	if ($giftxiao['probalilty'] < 1 && $giftxiao['probalilty'] > 0 && $giftxiao['awardstotal'] - $giftxiao['prizedraw'] >= 1) {
		$temp = explode('.', $giftxiao['probalilty']);
		$temp = pow(10, strlen($temp[1]));
		$rate = $temp < $rate ? $rate : $temp;
	}
}
$prize_arr = array();
$isgift = false;
foreach ($gift as $row) {
	if ($row['awardstotal'] - $row['prizedraw'] >= 1 and floatval($row['awardspro']) > 0) {
		$item = array(
			'id' => $row['id'],
			'prize' => $row['prizetype'],
			'v' => $row['awardspro'] * $rate,
		);
		$prize_arr[] = $item;
		$isgift = true;
	}
	if($row['awardstotal'] - $row['prizedraw'] >= 1 and floatval($row['awardspro']) == 0){
        $isgift = true;
    }
	$zprizepro += $row['awardspro'];
}

if ((100 - $zprizepro) > 0) {
	$item = array(
		'id' => 0,
		'prize' => '好可惜！没有中22',
		'v' => (100 - $zprizepro) * $rate,
	);
	$prize_arr[] = $item;
}

//点数概率
$level=array();

//所有奖品
if ($isgift) {
	$last_time = strtotime(date("Y-m-d", mktime(0, 0, 0)));

	//开始抽奖咯
	foreach ($prize_arr as $key => $val) {
		$arr[$val['id']] = $val['v'];
	}
	$prizetype = $this->get_rand($arr); //根据概率获取奖项id



    //拉黑用户
    if ($fans['is_back'] == 1) {
        $prizetype = -1;
        pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
        $data = array(
            'msg' => '好可惜!！没有抽中！!',
            'level'=>1,
            'success' => 11,
        );
        $this->message($data);
        exit();
    }
    //拉黑用户



if($reply['most_num_times']>0){
	$cnum = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and turntable=2 and from_user =:from_user and hbpici=:hbpici",array(':from_user'=>$from_user,':rid'=>$rid,':hbpici'=>$reply['hbpici']));

	if ($cnum >= $reply['most_num_times'] && $reply['most_num_times'] != 0){
		$prizetype = -1;
		pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
		$data = array(
			'msg' => '好可惜!！没有抽中！!',
			'level'=>1,
			'success' => 11,
		);
		$this->message($data);
		exit();
	}
}



	if ($fans['qhb_awardnum'] >= $reply['award_times'] && $reply['award_times'] != 0) {
		$prizetype = -1;
		 pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
		$data = array(
			'msg' => '好可惜!！没有抽中！!',
			'level'=>1,
			'success' => 11,
		);
		$this->message($data);
		exit();
	} else {

			if ($prizetype > 0) {
				$status = 1;
				$consumetime = '';
				$awardinfo = pdo_fetch("select * from " . tablename('haoman_dpm_prize') . " where  id='" . $prizetype . "'");



				if($awardinfo['ptype'] == 1){
					$prizetype = $_GPC['cardrowid'];
					$awardinfo = pdo_fetch("select * from " . tablename('haoman_dpm_prize') . " where  id='" . $prizetype . "'");
				}

				switch ($awardinfo['ptype']) {
					case 0:

                        if( $reply['most_money'] > 0||$reply['total_num']>0){
                            $money = pdo_fetchall("select credit,hbpici from " . tablename('haoman_dpm_award') . " where rid = " . $rid . " and turntable=2 and prizetype =0");
                            $most_money ='';
                            foreach ($money as $v){
                                $most_money +=$v['credit']/100;

                                if($v['hbpici']==$reply['hbpici']){
                                    $only_money +=$v['credit']/100;
                                }
                            }
                            if($only_money >= $reply['total_num']&&$reply['total_num']!=0){
                                $prizetype = -1;
                                pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
                                $data = array(
                                    'msg' => '好可惜!！没有抽中！!',
                                    'level'=>1,
                                    'success' => 11,
                                );
                                $this->message($data);
                                exit();
                            }
                            if($most_money >= $reply['most_money']&&$reply['most_money']!=0){
                                $prizetype = -1;
                                pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
                                $data = array(
                                    'msg' => '好可惜!！没有抽中！!',
                                    'level'=>1,
                                    'success' => 11,
                                );
                                $this->message($data);
                                exit();
                            }
                        }

						$credit = (mt_rand($awardinfo['credit'], $awardinfo['credit2']));
						if ($credit < 100) {
							//中奖记录保存
							$insert = array(
								'uniacid' => $uniacid,
								'rid' => $rid,
								'turntable' => 2,
								'from_user' => $from_user,
								'avatar' => $avatar,
								'nickname' => $nickname,
								'mobile' => $fans['mobile'],
								'awardname' => $awardinfo['prizename'],
								'awardsimg' => $awardinfo['awardsimg'],
								'prizetype' => 0,
								'credit' => $credit,
								'hbpici' => $reply['hbpici'],
								'prize' => $prizetype,
								'createtime' => time(),
								'consumetime' => $consumetime,
								'iszhuangyuan' => $awardinfo['sort'],
								'status' => 1,
							);

                          	$nu = $credit/100;
							$actions = "恭喜您抽中：".$awardinfo['prizename']."，获得红包".$nu."元";
							$temp = pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));
							if($temp == false){
								$data = array(
									'msg' => '好可惜!！没有抽中!!！',
									'level'=>1,
									'success' => 11,
								);
								$this->message($data);

							}else{
								pdo_insert('haoman_dpm_award', $insert);
								pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1,'qhb_awardnum' => $fans['qhb_awardnum'] + 1,'totalnum' => $fans['totalnum'] + $credit, 'zhongjiang' => 1), array('id' => $fans['id']));
								$this->sendText($from_user,$actions);
							}

						} else {
							//中奖记录保存
							$insert = array(
								'uniacid' => $uniacid,
								'rid' => $rid,
								'turntable' => 2,
								'from_user' => $from_user,
								'avatar' => $avatar,
								'nickname' => $nickname,
								'mobile' => $fans['mobile'],
								'awardname' => $awardinfo['prizename'],
								'awardsimg' => $awardinfo['awardsimg'],
								'prizetype' => 0,
								'credit' => $credit,
								'hbpici' => $reply['hbpici'],
								'prize' => $prizetype,
								'createtime' => time(),
								'consumetime' => $consumetime,
                                'iszhuangyuan' => $awardinfo['sort'],
								'status' => 2,
							);

							$record['fee'] = $credit / 100; //红包金额；
							$record['openid'] = $from_user;
                            $user['nickname'] = $nickname;
                            /*红包新商户订单号生成方式*/
							$user['fansid'] = $rid.$fans['id'];
                            /*红包新商户订单号生成方式*/

							$actions = "恭喜您抽中：".$awardinfo['prizename']."，获得红包".$record['fee']."元";
							//更新提现状态
							$temp = pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));

							if($temp == false){
								$data = array(
									'msg' => '好可惜!!！没有抽中!！',
									'level'=>1,
									'success' => 11,
								);
								$this->message($data);
								exit();
							}else{
				        		
				        		$temps = pdo_insert('haoman_dpm_award', $insert);
                                $awardid = pdo_insertid();
								$tempss = pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1,'qhb_awardnum' => $fans['qhb_awardnum'] + 1,'zhongjiang' => 1), array('id' => $fans['id']));
								$sendhongbao = $this->sendhb($record, $user);
//								if (is_error($sendhongbao['isok'])) {
//
//									$awardinfo['prizename'] = $awardinfo['prizename'] . "虽然您中了红包，但是我们不真发哦！";
//								} else {

									if ($sendhongbao['isok']) {

										$this->sendText($from_user,$actions);

									} else {

										if(!empty($reply['hb_lose_openid'])){
											$actions = "亲爱的管理员，有粉丝红包领取失败！\n原因：".$sendhongbao['error_msg'];
											$this->sendText($reply['hb_lose_openid'],$actions);
										}
                                        pdo_update('haoman_dpm_award', array('status' => 1), array('id' => $awardid));

                                        $inserts = array(
                                            'uniacid' => $uniacid,
                                            'rid' => $rid,
                                            'from_user' => $from_user,
                                            'money' => $credit / 100,
                                            'why_error' => $sendhongbao['error_msg']."**".$sendhongbao['code'],
                                            'createtime' => time(),
                                        );

                                        pdo_insert('haoman_dpm_whyerror', $inserts);

										$awardinfo['prizename'] = $awardinfo['prizename'] . "红包发送失败,你可以在我的奖品中心申请提现！";
								     	$msg = empty($reply['lose_hb'])?"红包发送失败,已为您存入我的奖品中！":$reply['lose_hb'];
											$data = array(
											'success' => 6,
											'level'=>1,
											  // 'msg'=>$sendhongbao['error_msg'],
											'msg' => $msg,
										);
										$this->message($data);
										exit();
									}

							}

						}

						break;

					case 1:

						//中奖记录保存
						$insert = array(
							'uniacid' => $uniacid,
							'rid' => $rid,
							'turntable' => 2,
							'avatar' => $avatar,
							'nickname' => $nickname,
							'mobile' => $fans['mobile'],
							'from_user' => $from_user,
							'awardname' => $awardinfo['prizename'],
							'awardsimg' => $awardinfo['awardsimg'],
							'card_id' => $awardinfo['couponid'],
							'prizetype' => 1,
							'prize' => $prizetype,
							'hbpici' => $reply['hbpici'],
							'createtime' => time(),
							'consumetime' => $consumetime,
                            'iszhuangyuan' => $awardinfo['sort'],
							'status' => 2,
						);
					
						$actions = "恭喜您抽中：".$awardinfo['prizename']."，获得卡券一张";
                        if($awardinfo['awardstotal']-$awardinfo['prizedraw']>0) {
                            $temp = pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));
                        }
						if($temp == false){

							$data = array(
								'msg' => '好可惜!!！没有抽中！',
								'level'=>1,
								'success' => 11,
							);
							$this->message($data);

						}else{
							pdo_insert('haoman_dpm_award', $insert);
							pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1,'qhb_awardnum' => $fans['qhb_awardnum'] + 1, 'zhongjiang' => 1), array('id' => $fans['id']));
							$this->sendText($from_user,$actions);
							
							
						}
						
						break;

					case 2:
						$djtitle = $_W['uniacid'].sprintf('%d', time());
						//中奖记录保存
						$insert = array(
							'uniacid' => $uniacid,
							'rid' => $rid,
							'turntable' => 2,
							'avatar' => $avatar,
							'nickname' => $nickname,
							'mobile' => $fans['mobile'],
							'from_user' => $from_user,
							'title' => $djtitle,
							'awardname' => $awardinfo['prizename'],
							'awardsimg' => $awardinfo['awardsimg'],
							'jifen' => $awardinfo['jifen'],
							'prizetype' => 2,
							'prize' => $prizetype,
							'hbpici' => $reply['hbpici'],
							'createtime' => time(),
							'consumetime' => $consumetime,
                            'iszhuangyuan' => $awardinfo['sort'],
							'status' => 1,
						);

						$actions = "恭喜您抽中：".$awardinfo['prizename'].",您的兑奖码是:".$djtitle;
						$temp = pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));

						if($temp == false){

							$data = array(
								'msg' => '好可惜!！没有抽中！',
								'level'=>1,
								'success' => 11,
							);
							$this->message($data);

						}else{
							pdo_insert('haoman_dpm_award', $insert);
							pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1,'qhb_awardnum' => $fans['qhb_awardnum'] + 1,'zhongjiang' => 1), array('id' => $fans['id']));
							$this->sendText($from_user,$actions);
							
							
						}
						
						break;
					case 3:
						$jifen = (mt_rand($awardinfo['jifen'], $awardinfo['jifen2']));
						//中奖记录保存
						$insert = array(
							'uniacid' => $uniacid,
							'rid' => $rid,
							'turntable' => 2,
							'avatar' => $avatar,
							'nickname' => $nickname,
							'mobile' => $fans['mobile'],
							'from_user' => $from_user,
							'awardname' => $awardinfo['prizename'],
							'awardsimg' => $awardinfo['awardsimg'],
							'jifen' => $jifen,
							'prizetype' => 1,
							'prize' => $prizetype,
							'hbpici' => $reply['hbpici'],
							'createtime' => time(),
							'consumetime' => $consumetime,
                            'iszhuangyuan' => $awardinfo['sort'],
							'status' => 2,
						);

						$actions = "恭喜您抽中：".$jifen."积分";

						$temp = pdo_insert('haoman_dpm_award', $insert);

						if($temp == false){

							$data = array(
								'msg' => '好可惜!!！没有抽中！',
								'level'=>1,
								'success' => 11,
							);
							$this->message($data);

						}else{


							$this->sendText($from_user,$actions);
							pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1,'awardnum' => $fans['awardnum'] + 1,'qhb_awardnum' => $fans['qhb_awardnum'] + 1, 'zhongjiang' => 1), array('id' => $fans['id']));
							pdo_update('haoman_dpm_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));

							mc_credit_update($fansID, 'credit1', $jifen, array($fansID, '咻一咻活动抽中' . $jifen . '积分'));


						}

						break;

					default :
						pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));

						$data = array(
							'msg' => '好可惜！没有中奖!！',
							'level'=>1,
							'success' => 11,
							'height' => 240,
						);
						$this->message($data);
						exit();

				}

				if (!empty($awardinfo['awardsimg'])) {
					$awardinfo['awardsimg'] = toimage($awardinfo['awardsimg']);
				}

				if (empty($awardinfo['prizename'])) {
					$awardinfo['prizename'] = "卡券ID设置错误";
				}

				if (!empty($awardinfo['credit'])) {
					$awardinfo['credit'] = $credit;
				}
				if (!empty($awardinfo['jifen'])) {
					$awardinfo['jifen'] = $jifen;
				}

				$data = array(
					'award' => $awardinfo,
					'msg' => '中奖了！',
					'level' => $level,
					'success' => 1,
					'prizetype' => $prizetype,
					'ptype' => $awardinfo['ptype'],
				);


				$this->message($data);
				exit();
			} else {
				pdo_update('haoman_dpm_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
				$data = array(
					'msg' => '好可惜!！没有抽中！!',
					'level'=>1,
					'success' => 11,
				);
				$this->message($data);
                exit();

			}
	}
} else {
	$last_time = strtotime(date("Y-m-d", mktime(0, 0, 0)));
	pdo_update('haoman_dpm_fans', array('today_most_times' => $fans['today_most_times'] + 1,'todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
	$data = array(
		'msg' => '奖品被抽完了，下次赶早哦！',
		'level'=>1,
		'success' => 11,
	);
}


$this->message($data);