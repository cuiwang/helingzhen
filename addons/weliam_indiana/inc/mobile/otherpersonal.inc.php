<?php
	global $_W,$_GPC;
	$openid = $_GPC['openid'];
	$id = $_GPC['id'];
	if(empty($openid)){
		message('参数错误！');
	}
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'all';
	$member = m('member')->getInfoByOpenid($openid);
	
	//判定是否是刷新
	if(empty($id)){
		
	
	if ($operation == 'all') {
		$ar = pdo_fetchall("SELECT period_number,count ,id FROM " . tablename('weliam_indiana_record') . " WHERE uniacid = '{$_W['uniacid']}' and openid ='{$openid}' and status =1 ORDER BY id DESC limit 0,5");
		if (!empty($ar)) {
			foreach($ar as $key=>$value) {
				$period=pdo_fetch("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and period_number ='{$value['period_number']}'");
				$goods=pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
				if ($goods) {
					$p_record[$key] = array_merge($period, $goods);
					$p_record[$key]['allcount']=$value['count'];
				}
			}

		}
		if(!empty($ar[4]['id'])){
			$id = $ar[4]['id'];
		}else{
			$id = '-1';
		}
	}elseif($operation == 'get'){
		$period = pdo_fetchall("select id,nickname,goodsid,periods,code,endtime,zong_codes,status,partakes,period_number from".tablename('weliam_indiana_period')."where uniacid={$_W['uniacid']} and openid = '{$openid}' order by id desc limit 0,5");
		if($period){
			foreach ($period as $key => $value) {
				$goods = m('goods')->getGoods($value['goodsid']);
				$period[$key]['title'] = $goods['title'];
				$period[$key]['picarr'] = $goods['picarr'];
			}
		}
		if(!empty($period[4]['id'])){
			$id = $period[4]['id'];
		}else{
			$id = '-1';
		}
	}elseif($operation == 'share'){
		$period = pdo_fetchall("select * from".tablename('weliam_indiana_showprize')."where uniacid = '{$_W['uniacid']}' and openid = '{$openid}' and status=2 order by id desc limit 0,6");
		foreach($period as $key => $value){
			$period[$key]['thumbs'] = unserialize($value['thumbs']);
					}
		if(!empty($period[5]['id'])){
			$id = $period[5]['id'];
		}else{
			$id = '-1';
		}
	}
		include $this->template('otherpersonal');
		
	}else{
		//
		//
		//夺宝纪录页面刷新
		if ($operation == 'all') {
		$ar = pdo_fetchall("SELECT period_number,count ,id FROM " . tablename('weliam_indiana_record') . " WHERE uniacid = '{$_W['uniacid']}' and openid ='{$openid}' and status =1 and id<'{$id}' ORDER BY id DESC limit 0,10");
		if (!empty($ar)) {
			foreach($ar as $key=>$value) {
				$period=pdo_fetch("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and period_number ='{$value['period_number']}'");
				$goods=pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
				if($goods){
					if(!empty($period['endtime'])){
						$period['endtime'] = date("Y-m-d H:i:s",$period['endtime']);
					}
					$row = array(
						'id' => $period['id'],
						'nickname' => $period['nickname'],
						'avatar' => $period['avatar'],
						'goodsid' => $period['goodsid'],
						'periods' => $period['periods'],
						'openid' => $period['openid'],
						'code' => $period['code'],
						'endtime'=>$period['endtime'],
						'zong_codes' => $period['zong_codes'],
						'shengyu_codes' => $period['shengyu_codes'],
						'status' => $period['status'],
						'period_number' => $period['period_number'],
						'partakes' => $period['partakes'],
						'title' => $goods['title'],
						'picarr' => tomedia($goods['picarr']),
						'count' => $value['count'],
						'nowtime'=>time()
					);
					$info[]=$row;
				}
			}
		}
		for( $i=10;empty($ar[$i]['id'])&&$i>-1;$i--){
			$id = $ar[$i-1]['id'];
		}
		if(empty($id)){
			$id = -1;
		}
		$data = array(
			'success'=>$id,
			'list'=>$info,	
			'openid'=>$openid,
				);
		echo json_encode($data);	
		
	//中奖纪录页面刷新	
	}elseif($operation == 'get'){
		$period = pdo_fetchall("select id,nickname,goodsid,periods,code,endtime,zong_codes,status,partakes,period_number from".tablename('weliam_indiana_period')."where uniacid={$_W['uniacid']} and id<'{$id}' and openid = '{$openid}' and id<'{$id}' order by id desc limit 0,4");
		if($period){
			foreach ($period as $key => $value) {
				$goods = m('goods')->getGoods($value['goodsid']);
				$period[$key]['title'] = $goods['title'];
				$period[$key]['picarr'] = $goods['picarr'];
				
				if(!empty($period[$key]['endtime'])){
					$period[$key]['endtime'] = date("Y-m-d H:i:s",$period[$key]['endtime']);
				}
				$row = array(
					'id' => $period[$key]['id'],
					'nickname' => $period[$key]['nickname'],
					'goodsid' => $period[$key]['goodsid'],
					'periods' => $period[$key]['periods'],
					'code' => $period[$key]['code'],
					'endtime' => $period[$key]['endtime'],
					'zong_codes' => $period[$key]['zong_codes'],
					'status' => $period[$key]['status'],
					'partakes' => $period[$key]['partakes'],
					'period_number' => $period[$key]['period_number'],
					'title' => $goods['title'],
					'picarr' => tomedia($goods['picarr'])
				);
				$info[]=$row;	
			}
		}
		for( $i=10;empty($period[$i]['id'])&&$i>-1;$i--){
			$id = $period[$i-1]['id'];
		}
		if(empty($id)){
			$id = -1;
		}
		$data = array(
			'success'=>$id,
			'list'=>$info,	
			'openid'=>$openid,
				);
		echo json_encode($data);
		
	//晒单页面刷新	
	}elseif($operation == 'share'){
		$period = pdo_fetchall("select * from".tablename('weliam_indiana_showprize')."where uniacid = '{$_W['uniacid']}' and openid = '{$openid}' and status=2 and id<'{$id}' order by id desc limit 0,6");
		foreach($period as $key => $value){
			$period[$key]['thumbs'] = unserialize($value['thumbs']);
			
			$row = array(
				'id' => $period[$key]['id'],
				'title' => $period[$key]['title'],
				'createtime'=>date("Y-m-d H:i:s",$period[$key]['createtime']),
				'detail' => $period[$key]['detail'],
				'thumb' => $period[$key]['thumbs'][0]
			);
					}
		for( $i=6;empty($period[$i]['id'])&&$i>-1;$i--){
			$id = $period[$i-1]['id'];
		}
		if(empty($id)){
			$id = -1;
		}
		$data = array(
			'success'=>$id,
			'list'=>$info,
			'openid'=>$openid
				);
		echo json_encode($data);
	}
		
	}
	
?>