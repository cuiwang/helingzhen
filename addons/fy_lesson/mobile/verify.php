<?php
/**
 * 验证类
 */

$type = intval($_GPC['type']);
if($type==1){
	$password = trim($_GPC['code']);
	$price = trim($_GPC['price']);
	if(empty($password)){
		$data = array(
			'code' => -1,
			'msg'  => "请输入课程优惠码",
		);
		$this->resultJson($data);
	}

	$coupon = pdo_fetch("SELECT * FROM " .tablename($this->table_coupon). " WHERE uniacid=:uniacid AND password=:password", array(':uniacid'=>$uniacid, ':password'=>$password));
	if(empty($coupon)){
		$data = array(
			'code' => 1,
			'msg'  => "课程优惠码不存在",
		);
		$this->resultJson($data);
	}
	if($coupon['is_use']==1){
		$data = array(
			'code' => 2,
			'msg'  => "课程优惠码已被使用",
		);
		$this->resultJson($data);
	}
	if($coupon['validity'] < time()){
		$data = array(
			'code' => 3,
			'msg'  => "课程优惠码已过期",
		);
		$this->resultJson($data);
	}
	if($price < $coupon['conditions']){
		$data = array(
			'code' => 4,
			'msg'  => "付款金额大于".$coupon['conditions']."元才可使用该优惠码",
		);
		$this->resultJson($data);
	}

	$data = array(
		'code' => 0,
	);
	if($coupon['amount'] >= $price){
		$data['total'] = 0;
		$data['amount'] = $price;
	}else{
		$data['total'] = $price - $coupon['amount'];
		$data['amount'] = $coupon['amount'];
	}
	$this->resultJson($data);
}

?>