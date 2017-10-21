<?php
	global $_GPC,$_W;
	$id = intval($_GPC['id']);
	$uniacid=$_W['uniacid'];
	$openid = m('user') -> getOpenid();
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$page = !empty($_GPC['page'])?$_GPC['page']:0;
	$pagenum = $page*10;
	$myCart_num = pdo_fetchcolumn("select count(*) from".tablename('weliam_indiana_cart')."where openid = '{$openid}' and uniacid={$_W['uniacid']}");
	if ($operation == 'display') {
		if($id){
			$goods = m('goods')->getGoods($id);
			$period = pdo_fetch("select * from".tablename('weliam_indiana_period')."where uniacid = '{$_W['uniacid']}' and goodsid = '{$goods['id']}' and periods='{$goods['periods']}'");
		}else{
			$periodid =  $_GPC['periodid'];/*商品期ID*/
			$period = m('period')->getPeriodById($periodid);
			$goods = m('goods')->getGoods($period['goodsid']);
		}
		$couponmess = m('coupon')->coupon_message($goods['id']);
		$prizemember = m('member')->getInfoByOpenid($period['openid']);
		$mycodes = pdo_fetch("select * from".tablename('weliam_indiana_record')."where uniacid = '{$_W['uniacid']}' and openid = '{$openid}' and period_number = '{$period['period_number']}'");
		if($mycodes){
			$myallcodes = unserialize($mycodes['code']);
			if($mycodes['count'] > 6){
				$mydelcodes = array_slice($myallcodes,0,6);
			}else{
				$mydelcodes = $myallcodes;
			}
		}
		$advs_length = 0;
		$advs = pdo_fetchall("select * from " . tablename('weliam_indiana_goods_atlas') . " where g_id = '{$goods['id']}'");
	        foreach ($advs as &$adv) {
	        	if (substr($adv['link'], 0, 5) != 'http:') {
	                $adv['link'] = "http://" . $adv['link'];
	            }
			$advs_length++;
	        }
		
	    unset($adv);
		/********** 判断机器人是否当机开始       **********/
			/*$machines = m('machine')->get_MachinesInfoByPeriodnNumber($period['period_number']);
			$machine_left_time = time() - $machines['timebucket']*3;
			if($machines['status'] == 1 && $machine_left_time > $machines['next_time']){
				m('machine')->marchine_cir($machines['period_number'] , $machines['timebucket'] , $machines['machine_num']);
			}*/
		/********** 判断机器人是否当机结束       **********/
		$list = pdo_fetchall("select * from".tablename('weliam_indiana_consumerecord')."where uniacid = '{$_W['uniacid']}' and period_number = '{$period['period_number']}' order by createtime desc limit ".$pagenum.",10");
		foreach ($list as $key => $value) {
			$init_money = m('goods')->getListByPeriod_number($value['period_number']);
			$list[$key]['num'] = $list[$key]['num']/$init_money['init_money'];
			$member = m('member')->getInfoByOpenid($value['openid']);
			$list[$key]['avatar'] = $member['avatar'];
			$list[$key]['nickname'] = $member['nickname'];
		}
		include $this->template('detail');
		exit;
	}
	if ($operation == 'detail') {
		$goods = pdo_fetch("SELECT content FROM ".tablename('weliam_indiana_goodslist')." WHERE uniacid = '{$uniacid}' and id = '{$id}' ");
		include $this->template('content');
		exit;
	}
	
	if($operation == 'refresh'){
		//刷新加载参与人
		$period_number = $_GPC['period_number'];
		$list = pdo_fetchall("select * from".tablename('weliam_indiana_consumerecord')."where uniacid = '{$_W['uniacid']}' and period_number = '{$period_number}' order by createtime desc limit ".$pagenum.",10");
		foreach ($list as $key => $value) {
			$init_money = m('goods')->getListByPeriod_number($value['period_number']);
			$list[$key]['num'] = $list[$key]['num']/$init_money['init_money'];
			$member = m('member')->getInfoByOpenid($value['openid']);
			$list[$key]['avatar'] = $member['avatar'];
			$list[$key]['nickname'] = $member['nickname'];
			$list[$key]['createtime'] = date("Y-m-d H:i:s",$list[$key]['createtime']);
		}
		echo json_encode($list);
		exit;
	}
?>