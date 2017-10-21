<?php
/**
 * 讲师收入提现
 */

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('lessoncash', array('op'=>$_GPC['op']));
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
$setting = pdo_fetch("SELECT manageopenid,newcash,is_sale,cash_lower,cash_type,cash_way,sitename,sharelink,footnav,copyright,front_color,teacherlist,cash_follow FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$uid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));

$title = "讲师课程收入提现";

if($op=='display'){
	load()->model('mc');
	$fansinfo = mc_fansinfo($openid, $_W['acid'], $_W['uniacid']);
	if($fansinfo['follow'] != 1 && $setting['cash_follow']==1){
		message("亲，需要关注公众号才能提现哦~", $this->createMobileUrl('follow'), "error");
	}
	$setting_cashway = unserialize($setting['cash_way']);
	$member = pdo_fetch("SELECT a.*,b.nickname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.openid='{$openid}'");
	
	if($member['nopay_lesson'] < $setting['cash_lower']){
		message("当前提现最低额度为{$setting['cash_lower']}元，您的可提现额度不够", "", "warning");
	}

	$lastcashlog = pdo_fetch("SELECT pay_account FROM " .tablename($this->table_cashlog). " WHERE uniacid=:uniacid AND uid=:uid AND cash_way=3 ORDER BY id DESC LIMIT 1", array(':uniacid'=>$uniacid, ':uid'=>$uid));

	if(checksubmit('submit')){
		$cash_way = intval($_GPC['cash_way']); //1.提现到余额 2.提现到微信钱包
		$cash_num = floatval($_GPC['cash_num']);
		$pay_account = trim($_GPC['pay_account']);
		
		if(empty($cash_way)){
			message("请选择提现方式", "", "error");
		}
		if($cash_way==3 && empty($pay_account)){
			message("请输入提现帐号", $this->createMobileUrl('lessoncash'), "error");
		}
		if($cash_num > $member['nopay_lesson']){
			message("您的可提现佣金额度为{$member['nopay_lesson']}元", "", "error");
		}
		if($cash_num < $setting['cash_lower']){
			message("当前系统最低提现额度为{$setting['cash_lower']}元", "", "error");
		}

		/**
		 * 减少会员可提现佣金和增加会员已提现佣金
		 */
		$upmember = array(
			'nopay_lesson'	=> $member['nopay_lesson'] - $cash_num,
			'pay_lesson'	=> $member['pay_lesson'] + $cash_num,
		);
		$succ = pdo_update($this->table_member, $upmember, array('id'=>$member['id']));

		if($succ){
			$cashlog = array(
				'uniacid'	  => $uniacid,
				'cash_way'	  => $cash_way, //1.提现到余额  2.提现到微信钱包
				'pay_account' => $pay_account,
				'uid'		  => $member['uid'],
				'openid'	  => $member['openid'],
				'cash_num'    => $cash_num,
				'lesson_type' => 2, /* 提现类型 1.分销佣金提现 2.课程收入提现 */
				'addtime'	  => time(),
			);
			
			if($cash_way==1){
				$cashlog['cash_type'] = 2; //提现到余额默认为自动到账
			}elseif($cash_way==3){
				$cashlog['cash_type'] = 1; //提现到支付宝默认为管理员审核
			}else{
				$cashlog['cash_type'] = $setting['cash_type'];
			}

			if($cash_way==1){/*  1.提现到余额 */
				load()->model('mc');
				$result = mc_credit_update($member['uid'], 'credit2', $cash_num, array('1'=>'微课堂讲师收入提现'));

				if($result){
					$cashlog['status']       = 1;
					$cashlog['disposetime']  = time();
					$cashlog['remark']		 = "";

					pdo_insert($this->table_cashlog, $cashlog);
					message("提现成功，佣金已发放到您的账户余额！", $this->createMobileUrl('teachercenter'), "success");
				}

			}elseif($cash_way==2 || $cash_way==3){/*  2.提现到微信钱包 3.提现到支付宝 */
				if($cashlog['cash_type']==1){ /* 提现方式为管理员审核 */
					$cashlog['status'] = 0;
					pdo_insert($this->table_cashlog, $cashlog);

					/* 模版消息通知管理员 */
					$manage = explode(",", $setting['manageopenid']);
					foreach($manage as $manageopenid){
						$sendneworder = array(
							'touser'      => $manageopenid,
							'template_id' => $setting['newcash'],
							'url'         => "",
							'topcolor'    => "#7B68EE",
							'data'        => array(
								'first'=> array(
									'value' => urlencode("亲，您收到一条新的用户提现申请"),
									'color' => "#428BCA",
								),
								'keyword1'  => array(
									'value' => $member['nickname'],
									'color' => "#428BCA",
								),
								'keyword2'  => array(
									'value' => date('Y-m-d H:i', time()),
									'color' => "#428BCA",
								),
								'keyword3'  => array(
									'value' => urlencode($cash_num."元"),
									'color' => "#428BCA",
								),
								'keyword4'  => array(
									'value' => urlencode("微信零钱钱包"),
									'color' => "#428BCA",
								),
								'remark'	=> array(
									'value' => urlencode("详情请登录网站后台查看！"),
									'color' => "#222222",
								),
							)
						);
						$this->send_template_message(urldecode(json_encode($sendneworder)),$_W['acid']);
					}
					message("提交申请成功，请等待管理员审核！", $this->createMobileUrl('lessoncashlog'), "success");
				}elseif($cashlog['cash_type']==2){ /* 提现方式为自动提现到微信零钱钱包 */
					$post = array('total_amount'=>$cash_num, 'desc'=>'讲师申请课程收入提现');
					$fans = array('openid'=>$member['openid'], 'nickname'=>$member['nickname']);
					$result = $this->companyPay($post,$fans);

					if($result['result_code']=='SUCCESS'){
						$cashlog['status']           = 1;
						$cashlog['disposetime']      = strtotime($result['payment_time']);
						$cashlog['partner_trade_no'] = $result['partner_trade_no'];
						$cashlog['payment_no']	     = $result['payment_no'];
						$cashlog['remark']			 = "";

						pdo_insert($this->table_cashlog, $cashlog);
						message("提现成功，提现金额已发放到您的微信钱包！", $this->createMobileUrl('lessoncashlog'), "success");

					}else{
						/*回滚操作*/
						$rollback = array(
							'nopay_lesson'	=> $member['nopay_lesson'],
							'pay_lesson'	=> $member['pay_lesson'],
						);
						pdo_update($this->table_member, $rollback, array('id'=>$member['id']));
						
						message($result['return_msg'], $this->createMobileUrl("lessoncash"), "error");
					}
				}
			}
		}

	}
}






include $this->template('lessoncash');


?>