<?php 
	global $_W,$_GPC;
	$id = $_GPC['id'];
	$openid = m('user') -> getOpenid();
	$flag = '0';
	$differtime = time()-7776000;
	//第一次进入界面
	if(empty($id)){
		$result = pdo_fetchall("select * from".tablename('weliam_indiana_consumerecord')."where createtime > '{$differtime}' and openid = '{$openid}' and uniacid = '{$_W['uniacid']}' and num<>0 and period_number<>''  order by id desc limit 0,10");
		foreach($result as$key=>$value){
		$period = m('period')->getPeriodByPeriod_number($value['period_number']);
		$goods = m('goods')->getGoods($period['goodsid']);
		$result[$key]['title'] = $goods['title'];
		$result[$key]['periods'] = $goods['periods'];
		$result[$key]['periodid'] = $period['id'];
		
	}
		if(!empty($result[9]['id'])){
			$id = $result[9]['id'];
		}else{
			$id = '-1';
		}
	include $this->template("consume");
	}else{
		//刷新加载充值记录
		$result = pdo_fetchall("select * from".tablename('weliam_indiana_consumerecord')."where createtime > '{$differtime}' and openid = '{$openid}' and uniacid = '{$_W['uniacid']}' and id<'{$id}' order by id desc limit 0,10");
		foreach($result as$key=>$value){
			$period = m('period')->getPeriodByPeriod_number($value['period_number']);
			$goods = m('goods')->getGoods($period['goodsid']);
			$result[$key]['title'] = $goods['title'];
			$result[$key]['periods'] = $goods['periods'];
			$result[$key]['periodid'] = $period['id'];
			$row = array(
				'periodid' => $result[$key]['periodid'],
				'periods' => $result[$key]['periods'],
				'title' => $result[$key]['title'],
				'createtime' => date('Y-m-d H:i:s',$result[$key]['createtime']),
				'num' => $result[$key]['num']
			);
			$info[]=$row;
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