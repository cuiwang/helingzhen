<?php
global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];
		$content = intval($_GPC['content']*100);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

            header("HTTP/1.1 301 Moved Permanently");
            header("Location: {$this->createMobileUrl('other',array('type'=>1,'id'=>$rid))}");
            exit();
        }

		//网页授权借用开始（特殊代码）

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

		//网页授权借用结束（特殊代码）
		if (empty($from_user)) {
			$this->message(array("success" => 0, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
		}



		$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

		$num = $reply['share_acid'];

			$num = $num < 100 ? 100 : $num;

		$num2 = $reply['tx_most'];

		$num2 = $num2 < 500 ? 500 : $num2;

		$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = " . $rid . " and from_user='" . $from_user . "'");

		if(empty($nickname)){
			$nickname = $fans['nickname'];
		}
        $hb_award = pdo_fetchall("select * from " . tablename('haoman_dpm_hb_award') . " where rid = :rid and  status = 1 and prizetype = 0   and from_user=:from_user and uniacid = :uniacid",array(':rid'=>$rid,':from_user'=>$from_user,'uniacid'=>$uniacid));

		$num0 =0;
		foreach($hb_award as $v){
			$num0 +=$v['credit'];
		}


		$award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where rid = :rid and status = 1 and prizetype = 0  and from_user=:from_user and uniacid = :uniacid",array(':rid'=>$rid,':from_user'=>$from_user,'uniacid'=>$uniacid));
		$nums =0;
		foreach($award as $k){
			$nums +=$k['credit'];
		}

       $nums=$nums+$num0*100;

		if($nums<$num){
			$data = array(
				'success' => 0,
				'msg' =>'账户金额未达到提现标准！',
			);
			echo json_encode($data);
			exit();
		}
		if ($fans == false) {
			$data = array(
				'success' => 0,
				'msg' => '保存数据错误！',
			);
		}
		else {
			if (intval($nums) >= intval($num2)) {
				// if (intval($fans['totalnum']) > $num && intval($fans['totalnum']) == $content && intval($fans['totalnum']) == intval($nums)) {

					$insert = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'from_user' => $from_user,
						'avatar' => $avatar,
						'nickname' => $nickname,
						'mobile' => $fans['mobile'],
						'fansID' => 0,
						'awardname' => intval($nums),
						'prizetype' => 0,
						'awardsimg' => CLIENT_IP,
						'credit' => 0,
						'prize' => 0,
						'createtime' => time(),
						'consumetime' => 0,
						'status' => 0,
					);
					$temps = pdo_update('haoman_dpm_fans', array('totalnum' => 0, 'sharetime' => $fans['sharetime'] + 1), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid']));

					$tempss = pdo_update('haoman_dpm_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));
					pdo_update('haoman_dpm_hb_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));

					$temp = pdo_insert('haoman_dpm_cash', $insert);
					$data = array(
						'success' => 1,
						'msg' => '提现申请成功！',
					);
				// } else {
				// 	$data = array(
				// 		'success' => 0,
				// 		'msg' => '提现申请失败！！',
				// 	);
				// }
			} else {

			if ($reply['xf_condition'] == 0) {

				// if (intval($fans['totalnum']) > 0 && intval($fans['totalnum']) == $content && intval($fans['totalnum']) == intval($nums)) {

					$insert = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'from_user' => $from_user,
						'avatar' => $avatar,
						'nickname' => $nickname,
						'mobile' => $fans['mobile'],
						'fansID' => 0,
						'awardname' => intval($nums),
						'prizetype' => 0,
						'awardsimg' => CLIENT_IP,
						'credit' => 0,
						'prize' => 0,
						'createtime' => time(),
						'consumetime' => 0,
						'status' => 0,
					);
					$temps = pdo_update('haoman_dpm_fans', array('totalnum' => 0, 'sharetime' => $fans['sharetime'] + 1), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid']));

					$tempss = pdo_update('haoman_dpm_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));

				    pdo_update('haoman_dpm_hb_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));

					$temp = pdo_insert('haoman_dpm_cash', $insert);
					$data = array(
						'success' => 1,
						'msg' => '提现申请成功！',
					);
				// } else {
				// 	$data = array(
				// 		'success' => 0,
				// 		'msg' => '提现申请失败！',
				// 	);
				// }
			} elseif ($reply['xf_condition'] == 1) {

				// if (intval($fans['totalnum']) > $num && intval($fans['totalnum']) == $content && intval($fans['totalnum']) == intval($nums)) {

					$insert = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'from_user' => $from_user,
						'avatar' => $avatar,
						'nickname' => $nickname,
						'mobile' => $fans['mobile'],
						'fansID' => 0,
						'awardname' => intval($nums),
						'prizetype' => 0,
						'credit' => 0,
						'awardsimg' => CLIENT_IP,
						'prize' => 0,
						'createtime' => time(),
						'consumetime' => 0,
						'status' => 1,
					);
					$credit = intval($nums);
					$record['fee'] = $credit / 100; //红包金额；
					$record['openid'] = $from_user;
					$user['nickname'] = $nickname;
                    /*红包新商户订单号生成方式*/
                    $user['fansid'] = $rid.$fans['id'];
                    /*红包新商户订单号生成方式*/
					$sendhongbao = $this->sendhb($record, $user);
					if ($sendhongbao['isok']) {
						//更新提现状态
						$temp = pdo_insert('haoman_dpm_cash', $insert);
						$temps = pdo_update('haoman_dpm_fans', array('totalnum' => 0, 'sharetime' => $fans['sharetime'] + 1), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid']));
						$tempss = pdo_update('haoman_dpm_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));
						 pdo_update('haoman_dpm_hb_award', array('status' => 2), array('rid' => $rid, 'from_user' => $from_user, 'uniacid' => $_W['uniacid'], 'prizetype' => 0));

						if ($temp == false) {
							$data = array(
								'success' => 0,
								'msg' => '提现申请失败！3',
							);
						} else {
							$data = array(
								'success' => 1,
								'msg' => '提现申请成功！',
							);
						}

						$hbstatus = 2;

					} else {

                        $inserts = array(
                            'uniacid' => $uniacid,
                            'rid' => $rid,
                            'from_user' => $from_user,
                            'money' => $credit / 100,
                            'why_error' => $sendhongbao['error_msg']."**".$sendhongbao['code'],
                            'createtime' => time(),
                        );
                        pdo_insert('haoman_dpm_whyerror', $inserts);
						if(!empty($reply['hb_lose_openid'])){
							$actions = "亲爱的管理员，有粉丝提现失败！\n原因：".$sendhongbao['error_msg'];
							$this->sendText($reply['hb_lose_openid'],$actions);
						}
                        $msg = empty($reply['lose_hb'])?"提现失败，请稍后在试！":$reply['lose_hb'];

						$data = array(
							'success' => 0,
//                            'msg' => $sendhongbao['error_msg'],
							'msg' => $msg,
						);

					}
			}
		}
		}

		echo json_encode($data);