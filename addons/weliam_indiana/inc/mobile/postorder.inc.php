<?php
global $_W,$_GPC;
	if (empty($_GPC['id'])) {
        message('抱歉，参数错误！', '', 'error');
    }
    $openid = m('user') -> getOpenid();
	$sid = intval($_GPC['id']);//商品ID
	$periods =  $_GPC['periods'];//该商品第几期
	$period_number = pdo_fetch("SELECT period_number FROM " . tablename('weliam_indiana_period') . " WHERE goodsid = :goodsid and periods=:periods",array(':goodsid'=>$sid,':periods'=>$periods));
	$ordersn=date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
	$proplemess = pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_member')." WHERE uniacid = '{$_W['uniacid']}' and openid ='{$openid}' ");
	if (empty($proplemess)) {
		message('请先填写您的资料！', $this->createMobileUrl('prodata'), 'warning');
	}

	$data=array(
		'openid'=>$openid,
		'nickname'=>$proplemess['nickname'],
		'uniacid'=>$_W['uniacid'],
		'goodsid'=>$sid,
		'ordersn'=>$ordersn,
		'status'=>0,
		'count'=>$_GPC['count'],
		'createtime' => TIMESTAMP,
		'period_number'=>$period_number['period_number']
	);

	if(pdo_insert('weliam_indiana_record',$data))
	{
		$orderid = pdo_insertid();
//		message('提交成功！',$this->createMobileUrl('pay',array('id'=>$orderid)),'success');
		header("location:".$this->createMobileUrl('pay', array('id' => $orderid)));
	}else{
		message('提交失败！');
	}
?>