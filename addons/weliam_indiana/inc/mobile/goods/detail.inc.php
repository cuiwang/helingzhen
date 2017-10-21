<?php
	// 
	//  detail.inc.php
	//  <project>
	//  商品详情页数据加载
	//  Created by Administrator on 2016-04-23.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	global $_GPC,$_W;
	$op = !empty($_GPC['op'])?$_GPC['op']:'goods';   //判定需要获取的信息是商品信息还是消费者信息

	if($op == 'goods'){
		//获取商品信息
		$openid = m('user') -> getOpenid();
		$goods_id = intval($_GPC['id']);			//商品id
		$goods_periods = $_GPC['periods'];	//商品期数
		$goods_periodid = $_GPC['periodid'];	//本期商品id
		if($goods_id){
			$detail_goodslist = m('goods')->getGoods($goods_id);
			$sql_period_param = " id,goodsid,periods,nickname,avatar,openid,partakes,code,endtime,uniacid,shengyu_codes,zong_codes,createtime,period_number,canyurenshu,scale,status,recordid ";
			$sql_period = "select ".$sql_period_param."from ".tablename('weliam_indiana_period')."where uniacid = :uniacid and goodsid = :goodsid and periods = :periods";
			$data_period = array(
				':uniacid' => $_W['uniacid'],
				':goodsid' => $goods_id,
				':periods' => $detail_goodslist['periods']
			);
			$period = pdo_fetch($sql_period,$data_period);
		}else{
			$sql_period_param = " id,goodsid,periods,nickname,avatar,openid,partakes,code,endtime,uniacid,shengyu_codes,zong_codes,createtime,period_number,canyurenshu,scale,status,recordid ";
			$sql_period = "select ".$sql_period_param."from ".tablename('weliam_indiana_period')."where uniacid = :uniacid and id = :id";
			$data_period = array(
				':uniacid' => $_W['uniacid'],
				':id' => $goods_periodid
			);
			$period = pdo_fetch($sql_period,$data_period);
			$detail_goodslist = m('goods')->getGoods($period['goodsid']);
		}
		m('goods')->checkGoods($period['goodsid'],$period['periods'],$period['id']);
		$period['title'] = $detail_goodslist['title'];
		$period['init_money'] = $detail_goodslist['title'];
		$period['nowtime'] = time();
		$period['goods_picture'] = tomedia($detail_goodslist['picarr']);	//商品展示图片
		
		/*********检索自身购买数据**********/
		$sql_codes = "select * from".tablename('weliam_indiana_record')." where uniacid = :uniacid and openid = :openid and period_number = :period_number";
		$data_codes = array(
			':uniacid' => $_W['uniacid'],
			':openid' => $openid,
			':period_number' => $period['period_number']
		);
		$mycodes = pdo_fetch($sql_codes,$data_codes);
		if($mycodes){
			//检测个人夺宝码
			$myallcodes = unserialize($mycodes['code']);
			if($mycodes['count'] > 6){
				$mydelcodes = array_slice($myallcodes,0,6);
			}else{
				$mydelcodes = $myallcodes;
			}
		}
		
		/**********详情页面幻灯片**********/
		$advs_length = 0;
		$sql_advs = "select * from " . tablename('weliam_indiana_goods_atlas') . " where g_id = :g_id";
		$data_advs = array(
			':g_id' => $goods_id
		);
		
		$advs = pdo_fetchall($sql_advs,$data_advs);
        foreach ($advs as &$adv) {
        	if (substr($adv['link'], 0, 5) != 'http:') {
                $adv['link'] = "http://" . $adv['link'];
            }
			$advs_length++;
        }
	    unset($adv);
		
		/******商品详情页面跳转*****/
		include $this->template('goods/detail');exit;
	}
	
	if($op == 'detail'){
		//商品详情数据检索
		$goods_id = intval($_GPC['id']);
		$sql_goods_detail = "select content from".tablename('weliam_indiana_goodslist')." where uniacid = :uniacid and id = :id";
		$data_goods_detail = array(
			':uniacid' => $_W['uniacid'],
			':id' => $goods_id
		);
		
		$goods = pdo_fetch($sql_goods_detail,$data_goods_detail);
		
		include $this->template('goods/content');exit;
	}
	
	if($op == 'customer'){
		//加载商品消费记录
		$period_number = $_GPC['period_number'];
		/******判定加载页数****/
		$page = !empty($_GPC['page'])?$_GPC['page']:0;
		$pagenum = !empty($_GPC['pagenum'])?$_GPC['pagenum']:8;
		$pagestart = $page * $pagenum;
		/*******判定加载类型和加载条件*******/
		
		/*******检索消费者数据******/
		$sql_customer = "select * from".tablename('weliam_indiana_consumerecord')." where uniacid = :uniacid and period_number = :period_number order by createtime desc limit ".$pagestart.",".$pagenum;
		$data_customer = array(
			':uniacid' => $_W['uniacid'],
			':period_number' => $period_number
		);
		
		$customer_list = pdo_fetchall($sql_customer,$data_customer);
		foreach($customer_list as $key => $value){
			$member = m('member')->getInfoByOpenid($value['openid']);
			$init_money = m('goods')->getListByPeriod_number($value['period_number']);
			$customer_list[$key]['num'] = $value['num']/$init_money['init_money'];		//将购买金额换算成购买次数
			$customer_list[$key]['avatar'] = $member['avatar'];
			$customer_list[$key]['nickname'] = $member['nickname'];
			$customer_list[$key]['createtime'] = date("Y-m-d H:i:s",$value['createtime']);
		}
		
		die(json_encode(array('status'=>1,'result'=>$customer_list)));exit;		//json返回信息
	}
