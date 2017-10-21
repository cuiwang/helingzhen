<?php
global $_W,$_GPC;
$member = $this->_checkMember($_W);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$setting = $this->setting;
$allmoney = pdo_fetchcolumn("SELECT SUM(money) as allmoney FROM ".tablename(BEST_ACCOUNT)." WHERE openid = '{$_W['fans']['from_user']}' AND weid = {$_W['uniacid']}");
$allmoney = empty($allmoney) ? 0 : round($allmoney,2);
if($operation == 'display'){
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('cygoodssale_order')." WHERE shareopenid = '{$member['openid']}' AND weid = {$_W['uniacid']}");
	$allpage = ceil($total/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$moneylist = pdo_fetchall("SELECT fenxiaoprice,ordersn,status,createtime FROM ".tablename('cygoodssale_order')." WHERE shareopenid = '{$member['openid']}' AND weid = {$_W['uniacid']} LIMIT ".($pindex - 1)*$psize.",".$psize);
	$isajax = intval($_GPC['isajax']);
	if($isajax == 1){
		$html = '';
		foreach($moneylist as $k=>$v){
			if($v['status'] < 4){
				$redstyle = 'redsty';
			}else{
				$redstyle = '';
			}
			$html .= '<div class="item">
						<div class="iconfont">&#xe62b;</div>
						<div class="msg">
							<div class="ordersn textellipsis1">'.date('Y-m-d H:i:s',$v['createtime']).'</div>
							<div class="status">订单号：'.$v['ordersn'].'</div>
						</div>
						<div class="yongjin text-c '.$redstyle.'">¥'.$v['fenxiaoprice'].'</div>
					</div>';
		}
		echo $html;
		exit;
	}
}elseif($operation == 'dotixian'){
	$alipay = trim($_GPC['alipay']);
	if(empty($alipay)){
		$resArr['error'] = 1;
		$resArr['message'] = '请填写支付宝账户！';
		echo json_encode($resArr);
		exit();
	}
	$realname = trim($_GPC['realname']);
	if(empty($realname)){
		$resArr['error'] = 1;
		$resArr['message'] = '请填写支付宝姓名！';
		echo json_encode($resArr);
		exit();
	}
	$money = $_GPC['money'];
	if($money <= 0){
		$resArr['error'] = 1;
		$resArr['message'] = '请输入正确的提现金额！';
		echo json_encode($resArr);
		exit();
	}
	if($money > $allmoney){
		$resArr['error'] = 1;
		$resArr['message'] = '您的佣金余额不足！';
		echo json_encode($resArr);
		exit();
	}
	if($setting['present_money'] > $allmoney){
		$resArr['error'] = 1;
		$resArr['message'] = '提现金额必须满'.$setting['present_money'].'元才能提现！';
		echo json_encode($resArr);
		exit();
	}
	$datatixian['weid'] = $_W['uniacid'];
	$datatixian['openid'] = $member['openid'];
	$datatixian['txrealname'] = $realname;
	$datatixian['txaccount'] = $alipay;
	$datatixian['money'] = -$money;
	$datatixian['time'] = TIMESTAMP;
	$datatixian['explain'] = '提现';
	pdo_insert(BEST_ACCOUNT,$datatixian);
	$resArr['error'] = 0;
	$resArr['message'] = '提交提现申请成功！';
	echo json_encode($resArr);
	exit();
}
include $this->template('myaccount');
?>