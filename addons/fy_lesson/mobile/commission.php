<?php
/**
 * 分销中心
 */

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('commission', array('op'=>$_GPC['op']));
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
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

/* 分享设置 */
load()->model('mc');
$fansinfo = mc_fansinfo($openid, $_W['acid'], $_W['uniacid']);
$uid = $fansinfo['uid'];
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$uid));

if($setting['is_sale']==0){
	message("该功能不存在或已关闭！", "", "warning");
}

/* 课程会员信息 */
$lessonmember = pdo_fetch("SELECT vip FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");
if($setting['sale_rank']==2 && $lessonmember['vip']==0){
	message("您不是VIP用户，无法使用该功能！", "", "warning");
}

if($op=='display'){
	$title = "分销中心";
	$member = pdo_fetch("SELECT a.uid,a.parentid,a.nopay_commission,a.pay_commission,a.agent_level,a.addtime,b.nickname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.openid='{$openid}'");

	/* 如果存在上级会员，则获取上级会员昵称 */
	if($member['parentid']>0){
		$parent = pdo_fetch("SELECT nickname FROM " .tablename('mc_members'). " WHERE uniacid='{$uniacid}' AND uid='{$member['parentid']}'");
	}

	/* 如果存在分销级别，则获取分销级别名称 */
	$levelname = "默认级别";
	if($member['agent_level']>0){
		$level = pdo_fetch("SELECT levelname FROM " .tablename($this->table_commission_level). " WHERE uniacid='{$uniacid}' AND id='{$member['agent_level']}'");
		if(!empty($level)){
			$levelname = $level['levelname'];
		}
	}

	/* 计算我的团队成员数量 */
	$teamlist = pdo_fetchall("SELECT uid FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND parentid='{$uid}'");
	/* 一级会员人数 */
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND parentid='{$uid}'");

	/* 推广海报二维码 */
	if($setting['poster_type']==1){
		$posterUrl = $this->createMobileUrl('qrcode');
	}elseif($setting['poster_type']==2){
		$posterUrl = $this->createMobileUrl('qrcodes');
	}

	
	include $this->template('cindex');
}elseif($op=='commissionlog'){
	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_commission_log). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize);
	foreach($list as $key=>$value){
		$list[$key]['comtype'] = $value['bookname']=='VIP分销订单'?'VIP分销':'课程分销';
		$list[$key]['addtime'] = date("Y-m-d", $value['addtime']);
	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_commission_log). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");

	$title = "佣金明细(".$total.")";

	if(!$_W['isajax']){
		include $this->template('commissionlog');
	}else{
		echo json_encode($list);
	}
}elseif($op=='cashlog'){
	$pindex =max(1,$_GPC['page']);
	$psize = 10;

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_cashlog). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lesson_type=1 ORDER BY id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize);
	foreach($list as $key=>$value){
		if($value['cash_way']==1){
			$list[$key]['cash_way'] = '帐户余额';
		}elseif($value['cash_way']==2){
			$list[$key]['cash_way'] = '微信钱包';
		}elseif($value['cash_way']==3){
			$list[$key]['cash_way'] = '支付宝';
		}

		if($value['status']==0){
			$list[$key]['statu'] = "待打款";
		}elseif($value['status']==1){
			$list[$key]['statu'] = "已打款";
		}elseif($value['status']==-1){
			$list[$key]['statu'] = "无效佣金";
		}
		$list[$key]['remark'] = $value['remark']?$value['remark']:"";
		$list[$key]['addtime'] = date("Y-m-d", $value['addtime']);
	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename($this->table_cashlog). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lesson_type=1");

	$title = "佣金提现明细(".$total.")";

	if(!$_W['isajax']){
		include $this->template('cashlog');
	}else{
		echo json_encode($list);
	}
}elseif($op=='cash'){
	if($fansinfo['follow'] != 1 && $setting['cash_follow']==1){
		message("亲，需要关注公众号才能提现哦~", $this->createMobileUrl('follow'), "error");
	}
	$title = "佣金提现";
	$setting_cashway = unserialize($setting['cash_way']);
	$member = pdo_fetch("SELECT a.*,b.nickname,b.avatar FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.openid='{$openid}'");
	
	if($member['nopay_commission'] < $setting['cash_lower']){
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
			message("请输入提现帐号", $this->createMobileUrl('commission', array('op'=>'cash')), "error");
		}
		if($cash_num > $member['nopay_commission']){
			message("您的可提现佣金额度为{$member['nopay_commission']}元", "", "error");
		}
		if($cash_num < $setting['cash_lower']){
			message("当前系统最低提现额度为{$setting['cash_lower']}元", "", "error");
		}

		/**
		 * 减少会员可提现佣金和增加会员已提现佣金
		 */
		$upmember = array(
			'nopay_commission'	=> $member['nopay_commission'] - $cash_num,
			'pay_commission'	=> $member['pay_commission'] + $cash_num,
		);
		$succ = pdo_update($this->table_member, $upmember, array('id'=>$member['id']));

		if($succ){
			$cashlog = array(
				'uniacid'	  => $uniacid,
				'cash_way'	  => $cash_way, //1.提现到余额  2.提现到微信钱包 3.支付宝
				'pay_account' => $pay_account,
				'uid'		  => $member['uid'],
				'openid'	  => $member['openid'],
				'cash_num'    => $cash_num,
				'lesson_type' => 1, /* 提现类型 1.分销佣金提现 2.课程收入提现 */
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
				$result = mc_credit_update($member['uid'], 'credit2', $cash_num, array('1'=>'微课堂分销佣金提现'));

				if($result){
					$cashlog['status']       = 1;
					$cashlog['disposetime']  = time();
					$cashlog['remark']		 = "";

					pdo_insert($this->table_cashlog, $cashlog);
					message("提现成功，佣金已发放到您的账户余额！", $this->createMobileUrl('commission'), "success");
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
						$this->send_template_message(urldecode(json_encode($sendneworder)),$viporder['acid']);
					}

					message("提交申请成功，请等待管理员审核！", $this->createMobileUrl('commission'), "success");
				}elseif($setting['cash_type']==2){ /* 提现方式为自动提现到微信零钱钱包 */
					$post = array('total_amount'=>$cash_num, 'desc'=>'用户申请微课堂佣金提现');
					$fans = array('openid'=>$member['openid'], 'nickname'=>$member['nickname']);
					$result = $this->companyPay($post,$fans);

					if($result['result_code']=='SUCCESS'){
						$cashlog['status']           = 1;
						$cashlog['disposetime']      = strtotime($result['payment_time']);
						$cashlog['partner_trade_no'] = $result['partner_trade_no'];
						$cashlog['payment_no']	     = $result['payment_no'];
						$cashlog['remark']			 = "";

						pdo_insert($this->table_cashlog, $cashlog);
						message("提现成功，佣金已发放到您的微信钱包！", $this->createMobileUrl('commission'), "success");

					}else{
						/*回滚操作*/
						$rollback = array(
							'nopay_commission'	=> $member['nopay_commission'],
							'pay_commission'	=> $member['pay_commission'],
						);
						pdo_update($this->table_member, $rollback, array('id'=>$member['id']));
						
						message($result['return_msg'], $this->createMobileUrl("commission",array('op'=>'cash')), "error");
					}
				}
			}
		}

	}

	include $this->template('cash');
}

?>