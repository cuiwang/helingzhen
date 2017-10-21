<?php 
	global $_W,$_GPC;
	
	$openid = m('user') -> getOpenid();
	$flag = '0';
	$id = $_GPC['id'];
	if(empty($openid)){
		
		message('请从微信登陆', '', 'error');
		
	}	
	$differtime = time()-7776000;
	if(empty($id)){
		$result = pdo_fetchall("select * from".tablename('weliam_indiana_rechargerecord')."where createtime > '{$differtime}' and openid = '{$openid}' and status=1 and uniacid = '{$_W['uniacid']}' and type = 1  order by id desc limit 0,10");
		if(empty($result)){
			//设置检索为空标记
			$flag = '-1';
			}
		if(!empty($result[9]['id'])){
				$id = $result[9]['id'];
			}else{
				$id = '-1';
			}
	include $this->template("recharge_records");
	}else{
		$result = pdo_fetchall("select * from".tablename('weliam_indiana_rechargerecord')."where createtime > '{$differtime}' and openid = '{$openid}' and status=1 and uniacid = '{$_W['uniacid']}' and type = 1 and id<'{$id}'  order by id desc limit 0,10");
		foreach($result as $key=>$value){
			$row = array(
				'status' => $result[$key]['status'],
				'createtime' =>date('Y-m-d H:i:s', $result[$key]['createtime']),
				'num' => $result[$key]['num'],
				'paytype' => $value['paytype']
			);
			$info[] = $row;
		}
		for( $i=10;empty($result[$i]['id'])&&$i>-1;$i--){
			$id = $result[$i-1]['id'];
		}
		if(empty($id)){
			$id = -1;
		}
		$data = array(
			'success' => $id,
			'list' => $info
		);
		echo json_encode($data);
	}
	
	
?>