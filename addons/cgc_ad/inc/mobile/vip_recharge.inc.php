<?php

 global $_W, $_GPC;

 $weid = $_W['uniacid'];
 $quan_id = intval($_GPC['quan_id']);
 $member = $this -> get_member();
 $from_user = $member['openid'];
 $quan=$this->get_quan();
 $mid = $member['id'];
 $config = $this ->settings;
 $op = empty($_GPC['op'])?"display":$_GPC['op'];

 $cgc_ad_vip_rule = new cgc_ad_vip_rule();

 if($op == 'display'){
  $vip_rules = $cgc_ad_vip_rule->getRulesByQuan($weid, $quan['id']);
  include $this -> template('vip_recharge');
  exit();
 }else if($op == 'post'){
   $vip_id = $_GPC['vip_id'];
   if (empty($vip_id)){
    $this -> returnError('请选择充值vip等级');
   }
 	
 	$vip_rule = $cgc_ad_vip_rule->getOne($vip_id);
 	
 	if (empty($vip_rule)){
      $this -> returnError('充值vip选项出错');
 	}
 	
 	$data = array(
 			'weid'=>$weid,
 			'quan_id'=>$quan['id'],
 			'mid'=>$mid,
 			'openid'=>$from_user,
 			'nickname'=>$member['nickname'],
 			'headimgurl'=>$member['headimgurl'],
 			'vip_id'=>$vip_rule['id'],
 			'vip_name'=>$vip_rule['vip_name'],
 			'vip_recharge'=>$vip_rule['vip_recharge'],
 			'status'=>0,
 			'createtime'=>time()
 			);
 	pdo_insert('cgc_ad_vip_pay', $data);
 	
 	$vip_pay_id = pdo_insertid();
 	
 	if($vip_pay_id > 0){
 	
 		$tid = 'vip'.TIMESTAMP . $vip_pay_id;
 	
 		// 生成订单和支付参数
 		$params = array(
 				'tid' => $tid, // 充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
 				'ordersn' => $vip_pay_id, // 收银台中显示的订单号
 				'title' => $quan['aname'] . '充值vip', // 收银台中显示的标题
 				'fee' => $vip_rule['vip_recharge'], // 收银台中显示需要支付的金额,只能大于 0
 				'openid' => $from_user,
 				'pay_type' => $this -> settings['pay_type']
 		);
 	
 		// 调用pay方法
 		$params = $this -> payz($params);
 
 		if($config['pay_type'] == 1 || $config['pay_type'] == 2){
 			$this -> returnSuccess('', json_encode($params));
 		}
 		$this -> returnSuccess('', base64_encode(json_encode($params)));
 	}else{
 		$this -> returnError('发表失败，请重试');
 	}
 	
 }




