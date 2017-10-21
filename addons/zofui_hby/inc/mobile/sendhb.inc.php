<?php 
	global $_W,$_GPC;
	
	if(!$_W['isajax']){
		die('页面不存在');
	}	
	if(strtotime($this->module['config'][starttime]) > time() || time() > strtotime($this->module['config'][endtime])){
		die;
	}
	
	
	$msql = "SELECT * FROM " . tablename('zofui_hby_activity') . " WHERE `id` = :id";
	$lastmoney = pdo_fetch($msql , array(
		':id' => $_GPC['actid']
	));
	
	if($_POST['getprize'] == 1){
		//红包被领完了 echo 1
		if($lastmoney['lastmoney'] < 1){
			echo json_encode(array('status' => '1'));die;
		}
		
		//领的红包够多了 echo 2;
		$tsql = "SELECT COUNT(*) FROM " . tablename('zofui_hby_hblog') . " WHERE `uniacid` = :uniacid AND `actid` = :actid AND `openid` = :openid";		
		$total = pdo_fetchcolumn($tsql,array('uniacid'=>$_W['uniacid'],'actid'=>$_GPC['actid'],'openid'=>$_GPC['openid']));
		if($total >= $this->module['config']['prizenum']){
			echo json_encode(array('status' => '2'));die;
		}
		
 		//发红包 echo 3 发送成功,echo 4 发送失败
		$fee = rand($this->module['config']['minfee']*100,$this->module['config']['maxfee']*100)/100;//金额
		$arr['openid'] = $_GPC['openid'];
		$arr['hbname'] = '红包雨';
		$arr['body'] = "红包雨红包";
		$arr['fee'] = $fee;
		$res = $this->sendhongbaoto($arr);
 		if($res['result_code'] == 'SUCCESS'){
			$intodb = array(
				'uniacid' => $_W['uniacid'],
				'actid' => $_GPC['actid'],
				'openid' => $_GPC['openid'],				
				'money' => $fee,
				'time' => time()
			);
			pdo_insert('zofui_hby_hblog',$intodb);
			pdo_update('zofui_hby_activity', array('lastmoney'=>$lastmoney['lastmoney'] - $fee) , array('id' => $_GPC['actid']));
			
			echo json_encode(array('status' => '3','fee'=>$fee));die;
		}else{
			echo json_encode($res);
			die;
		}
	}else{
			echo json_encode(array('status' => '0'));die;
	}
	
	
	
	