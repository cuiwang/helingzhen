<?php
	// 
	//  index.inc.php
	//  <project>
	//  首页商品数据传递加载
	//  Created by Administrator on 2016-04-23.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	
	global $_GPC,$_W;
	load()->func('communication');
	ihttp_request('http://'.$_SERVER["HTTP_HOST"].'/addons/weliam_indiana/core/api/checkmachine.api.php', array('uniacid' => $_W['uniacid']),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
	$share_data = $this->module['config'];
	$uniacid=$_W['uniacid'];
	$openid = m('user') -> getOpenid();
	/******判定加载页数****/
	$page = !empty($_GPC['page'])?$_GPC['page']:0;
	$pagenum = !empty($_GPC['pagenum'])?$_GPC['pagenum']:8;
	$pagestart = $page * $pagenum;
	/*******判定加载类型和加载条件*******/
	$op = !empty($_GPC['op'])?$_GPC['op']:'go';
	/*************公告加载*************/
	$notice = pdo_fetchall("SELECT * FROM " . tablename('weliam_indiana_notice') . " WHERE enabled = 1 and uniacid = '{$_W['uniacid']}' ORDER BY id DESC");
	
	switch($op){
		case 'fast':$rank='sort desc,scale desc,createtime desc';break;
		case 'new':$rank='isnew desc,createtime desc';break;
		case 'hot':$rank='periods desc,scale desc';break;
		case 'priceup':$rank='zong_codes asc,scale asc';break;
		case 'pricedown':$rank='zong_codes desc,scale desc';break;
	}
	/******幻灯片开始****/
	$sql_adv = "select * from " . tablename('weliam_indiana_adv') . " where enabled=:enabled and weid= :weid order by displayorder desc";
	$data_adv = array(
		':enabled'=>1,
		':weid'=>$_W['uniacid']
	);
	$advs = pdo_fetchall($sql_adv,$data_adv);
	foreach ($advs as &$adv) {
		if (substr($adv['link'], 0, 5) != 'http:') {
			$adv['link'] = "http://" . $adv['link'];
		}
	}
	unset($adv);
	/******幻灯片结束****/
	$mtotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_member') . " WHERE uniacid = '{$_W['uniacid']}'");
	$mtotals = str_split($mtotal);
	/*print_r($mtotals);exit;*/
	$navis = pdo_fetchall("SELECT * FROM " . tablename('weliam_indiana_navi') . " WHERE uniacid = '{$_W['uniacid']}' and enabled = 1 ORDER BY displayorder DESC");
	$datam = array(
		':uniacid'=>$_W['uniacid'],
		':status'=>2
	);
	$data = array(
		':uniacid'=>$_W['uniacid'],
		':status'=>1
	);
	$sql_allgoods = "select * from".tablename('weliam_indiana_goodslist')."where uniacid = :uniacid and status = :status order by ".$rank." limit ".$pagestart.",".$pagenum;
	$sql_allperiod = "select scale,period_number,canyurenshu,shengyu_codes,zong_codes,periods,goodsid from".tablename('weliam_indiana_period')."where uniacid = :uniacid and status = :status order by ".$rank." limit ".$pagestart.",".$pagenum;
	
	if($op == 'fast' || $op == 'hot' || $op == 'priceup' || $op == 'pricedown'){
		//条件检索period表
		$goodslist = pdo_fetchall($sql_allperiod,$data);
		foreach($goodslist as $key => $value){
			$goods = m('goods')->getGoods($value['goodsid']);
			$goodslist[$key]['id'] = $value['goodsid'];
			$goodslist[$key]['merchant_id'] = $goods['merchant_id'];
			$goodslist[$key]['title'] = $goods['title'];
			$goodslist[$key]['picarr'] = tomedia($goods['picarr']);
			$goodslist[$key]['init_money'] = $goods['init_money'];
		}
		die(json_encode(array('status'=>1,'result'=>$goodslist)));exit;			//传回返回值
	}elseif($op == 'new'){
		//按照商品时间和是否被定义新品排序
		$goodslist = pdo_fetchall($sql_allgoods,$datam);
		foreach($goodslist as $key => $value){
			$sql_goodslist = "SELECT scale,period_number,canyurenshu,shengyu_codes,zong_codes,periods,goodsid FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = :uniacid and goodsid = :goodsid and periods = :periods";
			$data_goodslist = array(
				':uniacid'=>$_W['uniacid'],
				':goodsid'=>$value['id'],
				':periods'=>$value['periods']
			);
			$result_goodslist = pdo_fetch($sql_goodslist,$data_goodslist);
			$goodslist[$key]['id'] = $value['id'];
			$goodslist[$key]['scale'] = $result_goodslist['scale'];
			$goodslist[$key]['period_number'] = $result_goodslist['period_number'];
			$goodslist[$key]['canyurenshu'] = $result_goodslist['canyurenshu'];
			$goodslist[$key]['shengyu_codes'] = $result_goodslist['shengyu_codes'];
			$goodslist[$key]['zong_codes'] = $result_goodslist['zong_codes'];
			$goodslist[$key]['periods'] = $result_goodslist['periods'];
			$goodslist[$key]['goodsid'] = $result_goodslist['goodsid'];
			$goodslist[$key]['picarr'] = tomedia($value['picarr']);
		}
		die(json_encode(array('status'=>1,'result'=>$goodslist)));exit;			//传回返回值
	}
	
	if($op == 'announce'){
		//最新揭晓商品获取
		/*****最新揭晓商品选择开始*****/
		$announce_num = !empty($_GPC['announce_num'])?$_GPC['announce_num']:8;
		$announce_page = !empty($_GPC['announce_page'])?$_GPC['announce_page']:0;
		$announce_pagestart = $announce_num * $announce_page;
		/*****最新揭晓商品选择结束*****/
		$sql_announce = "select id,goodsid,periods,endtime,avatar,openid,nickname,partakes,code,period_number from".tablename('weliam_indiana_period')."where uniacid = :uniacid and status in(2,3,4,5,6,7) order by endtime desc limit ".$announce_pagestart.",".$announce_num;
		$data_announce = array(
			':uniacid'=>$_W['uniacid']
		);
		$result_announce = pdo_fetchall($sql_announce,$data_announce);
		foreach($result_announce as $key => $value){
			$goods = m('goods')->getGoods($value['goodsid']);
			$result_announce[$key]['picarr'] = tomedia($goods['picarr']);
			$result_announce[$key]['init_money'] = $goods['init_money'];
			$result_announce[$key]['title'] = $goods['title'];
			$result_announce[$key]['nowtime'] = time();
			$result_announce[$key]['change_endtime'] = date('Y-m-d H:i:s',$value['endtime']);
		}
		die(json_encode(array('status'=>1,'result'=>$result_announce)));exit;			//传回返回值
	}
	if($op == 'go'){
		include $this->template('goods/index');
	}
?>