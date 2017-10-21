<?php
global $_W,$_GPC;
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'all';
	$openid = m('user') -> getOpenid();
	$id = $_GPC['id'];
	if(empty($id)){
	    $ar = pdo_fetchall("SELECT period_number,count,id FROM " . tablename('weliam_indiana_record') . " WHERE uniacid = '{$_W['uniacid']}' and openid ='{$openid}' and status =1 ORDER BY id DESC ");
		
		if (!empty($ar)) {
			foreach($ar as $key=>$item) {
		  		$res[$key]['period_number']= $ar[$key]['period_number'];
				$res[$key]['count']= $ar[$key]['count'];
				$res[$key]['id'] = $ar[$key]['id'];
			}
			
		$number=0;
		foreach($res as $key=>$value) {
			if ($operation == 'all') {
				$period=pdo_fetch("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and period_number ='{$res[$key]['period_number']}' ");
				$goods=pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
				if ($goods) {
					$p_record[$number] = array_merge($period, $goods);
					$p_record[$number]['allcount']=$res[$key]['count'];
				}
			}elseif ($operation == 'now') {
				$period=pdo_fetch("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and period_number ='{$res[$key]['period_number']}' and status <= 2");
				$goods=pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
				if ($goods) {
					$p_record[$number] = array_merge($period, $goods);
					$p_record[$number]['allcount']=$res[$key]['count'];
				}
			}else{
				$period=pdo_fetch("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and period_number ='{$res[$key]['period_number']}' and status > 2");
				$goods=pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
				if ($goods) {
					$p_record[$number] = array_merge($period, $goods);
					$p_record[$number]['allcount']=$res[$key]['count'];
				}
			}
			if(!empty($period)){
				$number++;
			}
			//限制 		
			if($number == 5){
				$id = $res[$key]['id'];
				include $this->template('order');
				exit;
			}
		}
	}
				$id = -1;
				include $this->template('order');	
	}else{
		$ar = pdo_fetchall("SELECT period_number,count,id FROM " . tablename('weliam_indiana_record') . " WHERE uniacid = '{$_W['uniacid']}' and openid ='{$openid}' and status =1 and id<'{$id}' ORDER BY id DESC ");
		if (!empty($ar)) {
			foreach($ar as $key=>$item) {
		  		$res[$key]['period_number']= $ar[$key]['period_number'];
				$res[$key]['count']= $ar[$key]['count'];
				$res[$key]['id'] = $ar[$key]['id'];
			}
		$number=0;
		foreach($res as $key=>$value) {
			if ($operation == 'all') {
				$period=pdo_fetch("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and period_number ='{$res[$key]['period_number']}'");
				$goods=pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
				if ($goods) {
					$p_record[$number] = array_merge($period, $goods);
					$p_record[$number]['allcount']=$value;
					//判定时间是否为空
					if(empty($period['endtime']) || $period['endtime']>time()){
						$period['endtime'] = '待揭晓';
						$period['status'] = '待揭晓';
						$period['code'] = '待揭晓';
						$period['nickname'] = "待揭晓</a>";
					}else{
						$period['endtime'] = date('Y-m-d H:i:s',$period['endtime']);
						$period['nickname'] = $period['nickname']."</a>(本期共参与<strong>".$period['partakes']."</strong>人次)";
					}
					$row = array(
							'id' => $period['id'],
							'nickname' => $period['nickname'],
							'avatar' => $period['avatar'],
							'goodsid' => $period['goodsid'],
							'periods' => $period['periods'],
							'openid' => $period['openid'],
							'code' => $period['code'],
							'endtime' => $period['endtime'],
							'zong_codes' => $period['zong_codes'],
							'shengyu_codes' => $period['shengyu_codes'],
							'canyurenshu' => $period['canyurenshu'],
							'status' => $period['status'],
							'period_number' => $period['period_number'],
							'partakes' => $period['partakes'],
							'title' => $goods['title'],
							'picarr' => tomedia($goods['picarr']),
							'allcount' => $res[$key]['count']
					);
					$info[] = $row;
				}
			}elseif ($operation == 'now') {
				$period=pdo_fetch("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and period_number ='{$res[$key]['period_number']}' and status <= 2");
				$goods=pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
				if ($goods) {
					$p_record[$number] = array_merge($period, $goods);
					$p_record[$number]['allcount']=$value;
					//判定时间是否为空
					if(empty($period['endtime']) || $period['endtime']>time()){
						$period['endtime'] = '待揭晓';
						$period['status'] = '待揭晓';
						$period['code'] = '待揭晓';
						$period['nickname'] = "待揭晓</a>";
					}else{
						$period['endtime'] = date('Y-m-d H:i:s',$period['endtime']);
					}
					$row = array(
							'id' => $period['id'],
							'nickname' => $period['nickname'],
							'avatar' => $period['avatar'],
							'goodsid' => $period['goodsid'],
							'periods' => $period['periods'],
							'openid' => $period['openid'],
							'code' => $period['code'],
							'endtime' => $period['endtime'],
							'zong_codes' => $period['zong_codes'],
							'shengyu_codes' => $period['shengyu_codes'],
							'canyurenshu' => $period['canyurenshu'],
							'status' => $period['status'],
							'period_number' => $period['period_number'],
							'partakes' => $period['partakes'],
							'title' => $goods['title'],
							'picarr' => tomedia($goods['picarr']),
							'allcount' => $res[$key]['count']
					);
					$info[] = $row;
				}
			}else{
				$period=pdo_fetch("SELECT id,nickname,avatar,goodsid,periods,openid,code,endtime,zong_codes,shengyu_codes,canyurenshu,status,period_number,partakes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and period_number ='{$res[$key]['period_number']}' and status > 2");
				$goods=pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
				if ($goods) {
					$p_record[$number] = array_merge($period, $goods);
					$p_record[$number]['allcount']=$value;
					//判定时间是否为空
					if(empty($period['endtime']) || $period['endtime']>time()){
						$period['endtime'] = '待揭晓';
						$period['status'] = '待揭晓';
						$period['code'] = '待揭晓';
						$period['nickname'] = "待揭晓</a>";
					}else{
						$period['endtime'] = date('Y-m-d H:i:s',$period['endtime']);
					}
					$row = array(
							'id' => $period['id'],
							'nickname' => $period['nickname'],
							'avatar' => $period['avatar'],
							'goodsid' => $period['goodsid'],
							'periods' => $period['periods'],
							'openid' => $period['openid'],
							'code' => $period['code'],
							'endtime' => $period['endtime'],
							'zong_codes' => $period['zong_codes'],
							'shengyu_codes' => $period['shengyu_codes'],
							'canyurenshu' => $period['canyurenshu'],
							'status' => $period['status'],
							'period_number' => $period['period_number'],
							'partakes' => $period['partakes'],
							'title' => $goods['title'],
							'picarr' => tomedia($goods['picarr']),
							'allcount' => $res[$key]['count']
					);
					$info[] = $row;
				}
			}
			if(!empty($period)){
				$number++;
			}
			//限制 			
			if($number == 5){
				$id = $res[$key]['id'];
				$data = array(
					'success' => $id,
					'list' => $info
				);
				echo json_encode($data);
				exit;
				}
			
		}
	}
				
				if($number == 0){
					$id = -1;
				}else{
					$id = 0;
				}
				$data = array(
					'success' => $id,
					'list' => $info
				);
				echo json_encode($data);
					
	}
	
?>