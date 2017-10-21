<?php
	global $_W,$_GPC;
	$op = $_GPC['op'];
	$id = intval($_GPC['id']);
	$uniacid = $_W['uniacid'];
	$openid = m('user') -> getOpenid();
	$goods = pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_goodslist')." WHERE uniacid = {$_W['uniacid']} and id = '{$id}' ");
	$period = pdo_fetch("SELECT zong_codes,shengyu_codes,periods,goodsid FROM " . tablename('weliam_indiana_period') . " WHERE goodsid = :goodsid and periods=:periods",array(':goodsid'=>$id,':periods'=>$goods['periods']));
	if($op=='submit'){
		$sid = intval($_GPC['id']);//商品ID
		$periods =  $_GPC['periods'];//该商品第几期
		$count = $_GPC['count'];/*购买数量*/
		$period_number = pdo_fetch("SELECT period_number FROM " . tablename('weliam_indiana_period') . " WHERE goodsid = :goodsid and periods=:periods",array(':goodsid'=>$sid,':periods'=>$periods));
		$ordersn=date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
		$data=array(
			'openid'=>$openid,
			'nickname'=>$proplemess['nickname'],
			'uniacid'=>$_W['uniacid'],
			'goodsid'=>$sid,
			'ordersn'=>$ordersn,
			'status'=>0,
			'count'=>$count,
			'createtime' => TIMESTAMP,
			'period_number'=>$period_number['period_number']
		);
		
		if(pdo_insert('weliam_indiana_record',$data))
		{
			$orderid = pdo_insertid();
			header("location:".$this->createMobileUrl('pay', array('id' => $orderid)));
		}else{
			message('提交失败！');
		}
	}elseif($op=='tocart'){
		$period_number=$_GPC['periodnumber'];
		$titlelist = m('goods')->getListByPeriod_number($period_number);
		$title = "<span style='color: #FF002C;'>(".$titlelist['init_money']."元/人次)</span>".$_GPC['title'];
		$thisperiod = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$period_number}'");
		$goods = pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_goodslist')." WHERE uniacid = {$_W['uniacid']} and id = '{$thisperiod['goodsid']}' ");
		//检索商品已经购买数量
			$sum = pdo_fetchcolumn("select sum(count) from".tablename('weliam_indiana_record')."where openid = '{$openid}' and period_number='{$period_number}' and uniacid={$_W['uniacid']}");
			//检索商品最大数
			$maxnum = pdo_fetchcolumn("select maxnum from".tablename('weliam_indiana_goodslist')."where uniacid={$_W['uniacid']} and id = '{$thisperiod['goodsid']}'");
			//剩余商品数
			if($maxnum == 0){
				$left = -1;
			}else{
				$left = $maxnum - $sum;
			}
			if($left==0){
				$addnum=0;
			}else{
				$addnum = 1;
			}
		
		if($thisperiod['status']!=1){
			pdo_delete('weliam_indiana_cart',array('period_number'=>$v['period_number']));
			$myCarts = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']}");	
				$money=0;
				$num = 0;
				foreach($myCarts as $key=>$value){
					$goodslist = m('goods')->getListByPeriod_number($value['period_number']);
					$money +=$value['num']*$goodslist['init_money'];
					$num++;
				}
			die(json_encode(array("result" => 1,"num"=>$num,"money"=>$money,'why'=>'no')));	
		}else{
			if($_GPC['type']=='remove'){
				pdo_delete('weliam_indiana_cart',array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid));
			}else{
				$myCart_this = pdo_fetch("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']} and period_number='{$period_number}'");	
				if(!empty($myCart_this)){
					if($_GPC['type']=='down'){
						pdo_update('weliam_indiana_cart',array('num'=>$myCart_this['num']-$addnum),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid));
					}elseif($_GPC['type']=='input'){
						$num = $_GPC['num'];
						pdo_update('weliam_indiana_cart',array('num'=>$num),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid));
					}elseif($_GPC['type']=='up'){
						pdo_update('weliam_indiana_cart',array('num'=>$myCart_this['num']+$addnum),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid));
					}else{
						pdo_update('weliam_indiana_cart',array('num'=>$addnum),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid));
					}
				}else{
					
					
					$ip = $_W['clientip']; //获取当前用户的ip 
					$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}"; 
					$json = @file_get_contents($api);//调用新浪IP地址库 
					$arr = json_decode($json,true);
					if(pdo_insert('weliam_indiana_cart',array('num'=>$addnum,'uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid,'title'=>$title,'ip'=>$_W['clientip'],'ipaddress'=>$arr['province'].$arr['city']))){
						
					}
				}
			}
		}
		
		$myCarts = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']}");	
		$money=0;
		$num = 0;
		foreach($myCarts as$key=>$value){
			$goodslist = m('goods')->getListByPeriod_number($value['period_number']);
			$money +=$value['num']*$goodslist['init_money'];
			$num++;
		}
		die(json_encode(array("result" => 1,"num"=>$num,"money"=>$money)));	
	}elseif($op=='cart_detail'){
		/*删除已经揭晓的期记录*/
		$myCart = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']}");	
		foreach($myCart as $k=>$v){
			$period = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$v['period_number']}'");
			//检索商品已经购买数量
			$sum = pdo_fetchcolumn("select sum(count) from".tablename('weliam_indiana_record')."where openid = '{$openid}' and period_number='{$v['period_number']}' and uniacid={$_W['uniacid']}");
			//检索商品最大数
			$maxnum = pdo_fetchcolumn("select maxnum from".tablename('weliam_indiana_goodslist')."where uniacid={$_W['uniacid']} and id = '{$period['goodsid']}'");
			//剩余商品数
			if($maxnum == 0){
				$left = -1;
			}else{
				$left = $maxnum - $sum;
			}
			$myCart[$k]['left'] = $left;
			if($period['status']!=1){
				pdo_delete('weliam_indiana_cart',array('period_number'=>$v['period_number']));
			}
		}

		$period_number=$_GPC['periodnumber'];
		$thisperiod = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$period_number}'");
		$goods = pdo_fetch("SELECT * FROM ".tablename('weliam_indiana_goodslist')." WHERE uniacid = {$_W['uniacid']} and id = '{$thisperiod['goodsid']}' ");
		$summ = pdo_fetchcolumn("select sum(count) from".tablename('weliam_indiana_record')."where openid = '{$openid}' and period_number='{$period_number}' and uniacid={$_W['uniacid']}");
		if($summ == ''){
			$summ = 0;
		}
		if($goods['maxnum']-$summ <= 0 && $goods['maxnum'] != 0){
			$addnum = 0;
		}else if($thisperiod['shengyu_codes'] == 0){
			$addnum = 0;
		}else{
			$addnum = 1;
		}	
		file_put_contents(WELIAM_INDIANA."/params.log", var_export($addnum, true).PHP_EOL, FILE_APPEND);
		if($_GPC['opp']=='rightnow'){
			$title = "<span style='color: #FF002C;'>(".$goods['init_money']."元/人次)</span>".$_GPC['title'];
			if($period_number){
				$myCart_this = pdo_fetch("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']} and period_number='{$period_number}'");	
				if(!empty($myCart_this)){
					pdo_update('weliam_indiana_cart',array('num'=>$addnum),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid));
				}else{
					$ip = $_W['clientip']; //获取当前用户的ip 
					$api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}"; 
					$json = @file_get_contents($api);//调用新浪IP地址库 
					$arr = json_decode($json,true);
					pdo_insert('weliam_indiana_cart',array('num'=>$addnum,'uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid,'title'=>$title,'ip'=>$_W['clientip'],'ipaddress'=>$arr['province'].$arr['city']));
				}
				
			}
		}elseif($_GPC['opp']=='addtocart'){
			$title = "<span style='color: #FF002C;'>(".$goods['init_money']."元/人次)</span>".$_GPC['title'];
			if($period_number){
				$myCart_this = pdo_fetch("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']} and period_number='{$period_number}'");	
				if(!empty($myCart_this)){
					pdo_update('weliam_indiana_cart',array('num'=>$addnum),array('uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid));
				}else{
					pdo_insert('weliam_indiana_cart',array('num'=>$addnum,'uniacid'=>$_W['uniacid'],'period_number'=>$period_number,'openid'=>$openid,'title'=>$title));
				}
				
			}
		}
		$myCart = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']}");	
		$myCart_num = count($myCart);
		$money = 0;
		foreach($myCart as $key=>$value){
			$period = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$value['period_number']}'");
			//检索商品已经购买数量
			$sum = pdo_fetchcolumn("select sum(count) from".tablename('weliam_indiana_record')."where openid = '{$openid}' and period_number='{$value['period_number']}' and uniacid={$_W['uniacid']}");
			//检索商品最大数
			$maxnum = pdo_fetchcolumn("select maxnum from".tablename('weliam_indiana_goodslist')."where uniacid={$_W['uniacid']} and id = '{$period['goodsid']}'");
			//剩余商品数
			if($maxnum==0){
				$left = -1;
			}else{
				$left = $maxnum - $sum;
			}
			
			$myCart[$key]['left'] = $left;
			$goods = m('goods')->getGoods($period['goodsid']);
			$myCart[$key]['shengyu_codes'] = $period['shengyu_codes'];
			$myCart[$key]['zong_codes'] = $period['zong_codes'];
			$myCart[$key]['picarr'] = $goods['picarr'];
			$myCart[$key]['periods'] = $period['periods'];
			$myCart[$key]['init_money'] = $goods['init_money'];
			$myCart[$key]['goodsid'] = $goods['id'];
			$money +=$value['num']*$goods['init_money'];
		}
		include $this->template('cart_detail');exit;
	}elseif($op=='totalcart'){
		/*删除已经揭晓的期记录*/
		$myCart = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']}");	
		foreach($myCart as $k=>$v){
			$period = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$v['period_number']}'");
			if($period['status']!=1){
				pdo_delete('weliam_indiana_cart',array('period_number'=>$v['period_number']));
			}
			//过滤负数商品
			if(is_int($v['num']) || $v['num']<1){
				pdo_delete('weliam_indiana_cart',array('period_number'=>$v['period_number']));
			}
			//过滤限购商品
			
		}
		//查询我的购物车中购买的人次数
		$myCart = pdo_fetchall("select * from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']}");	
		$money = 0;
		foreach($myCart as $key=>$value){
			$goodslist = m('goods')->getListByPeriod_number($value['period_number']);
			$money += $value['num']*$goodslist['init_money'];
		}
		if($money==0){
			message("购物车为空！！！",$this->createMobileUrl('index'),'error');
		}
		$ordersn=date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
		$data=array(
			'openid'=>$openid,
			'uniacid'=>$_W['uniacid'],
			'ordersn'=>$ordersn,
			'status'=>0,
			'num'=>$money,
			'createtime' => TIMESTAMP
		);
		
		if(pdo_insert('weliam_indiana_rechargerecord',$data))
		{
			$orderid = pdo_insertid();
			header("location:".$this->createMobileUrl('pay', array('id' => $orderid)));
		}else{
			message('提交失败！');
		}
	}
	
	include $this->template('cart');
?>