<?php
	global $_W,$_GPC;
	$this -> backlists();
	$merchant=$this->merchant();
	$roles = pdo_fetch("select * from".tablename('tg_user_role')."where uniacid={$_W['uniacid']} and merchantid={$merchant['id']}");
	$nodes=array();
	if($roles){
		$nodes = unserialize($roles['nodes']);
	}
	if (checksubmit('hand')){
		$hexiaoma = $_GPC['hexiaoma'];
		$order = pdo_fetch("select hexiaoma from".tablename('tg_order')."where uniacid={$_W['uniacid']} and hexiaoma = {$hexiaoma} and is_hexiao=1 and status=2 ");
		if($order){
			pdo_update('tg_order', array('status' => 4, 'is_hexiao'=>2,'veropenid' => 'houtai','sendtime'=>TIMESTAMP,'gettime'=>TIMESTAMP), array('hexiaoma' => $hexiaoma));
		}else{
			message("未找到核销订单！");exit;
		}
		
		message('确认核销操作成功！', referer(), 'success');
	}
	include $this -> template('web/handhexiao');
?>