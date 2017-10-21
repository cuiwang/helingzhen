<?php 
	// 
	//  newannounce.inc.php
	//  <project>
	//  最新揭晓
	//  Created by haoran on 2016-01-18.
	//  Copyright 2016 haoran. All rights reserved.
	// 
	global $_W,$_GPC;
	$page = !empty($_GPC['page'])?$_GPC['page']:0;
	$pagenum = $page*20;
	$newannounce = pdo_fetchall("select  id,goodsid,periods,nickname,openid,partakes,endtime,status,zong_codes from".tablename("weliam_indiana_period")."where uniacid = {$_W['uniacid']} and endtime>0 order by endtime desc limit ".$pagenum.",20");
	foreach($newannounce as$key=>$value){
		$goods=m('goods')->getGoods($value['goodsid']);
		$newannounce[$key]['init_momey'] = $goods['init_momey'];
		$newannounce[$key]['picarr'] = $goods['picarr'];
		$newannounce[$key]['init_money'] = $goods['init_money'];
		$newannounce[$key]['title'] = $goods['title'];
		$newannounce[$key]['price'] = $goods['price'];
	}
	if($page == 0){
		//第一次进入跳转页面
		include $this->template('newannounce');
	}else{
		//加载新的信息
		foreach($newannounce as $key=>$value){
			$newannounce[$key]['picarr'] = tomedia($newannounce[$key]['picarr']);
			$newannounce[$key]['endtime'] = date('Y-m-d H:s:i', $newannounce[$key]['endtime']);
		}
		echo json_encode($newannounce);
	}	
	
	?>