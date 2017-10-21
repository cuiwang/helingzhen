<?php 
	// 
	//  winner.inc.php
	//  <project>
	//  商品往期获奖者
	//  Created by haoran on 2016-01-22.
	//  Copyright 2016 haoran. All rights reserved.
	// 
	global $_W,$_GPC;
	$goodsid = $_GPC['goodsid'];
	$page = !empty($_GPC['page'])?$_GPC['page']:0;
	$pagenum = $page*15;
	$result = pdo_fetchall("select nickname ,id,goodsid,periods,avatar,openid,code,partakes,endtime,status from".tablename('weliam_indiana_period')."where goodsid = '{$goodsid}' and uniacid = '{$_W['uniacid']}' order by periods desc limit ".$pagenum.",15 ");
	if($page == 0){
		$goodsimage = pdo_fetch("select picarr from".tablename('weliam_indiana_goodslist')."where id = '{$goodsid}' and uniacid='{$_W['uniacid']}'");
		$goodsimage = tomedia($goodsimage['picarr']);
		include $this->template('member/winner');
	}else{
		foreach($result as $key=>$value){
			$result[$key]['avatar'] = tomedia($value['avatar']);
			$result[$key]['endtime'] = date('m-d H:i:s',$value['endtime']);
		}
		echo json_encode($result);
	}
	
	?>