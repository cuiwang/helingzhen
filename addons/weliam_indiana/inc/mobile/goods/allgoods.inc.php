<?php
	global $_GPC,$_W;
	$openid = m('user') -> getOpenid();
	
	/*********判定商品分类**********/
	$op = !empty($_GPC['op'])?$_GPC['op']:'go';
	$oop = !empty($_GPC['oop'])?$_GPC['oop']:'fast';
	$fl = $_GPC['fl'];					//自定义专区
	switch($op){
		case 'go':$condition='';$condition_period='';break;
		case 'all':$condition='';$condition_period='';break;
		default:$condition=' and category_parentid='.$op;$condition_period=' and b.category_parentid='.$op;break;
	}
	switch($oop){
		case 'fast':$rank='sort desc,scale desc,createtime desc';$rank_period='a.sort desc,a.scale desc,a.createtime desc';break;
		case 'new':$rank='isnew desc,createtime desc';$rank_period='a.isnew desc,a.createtime desc';break;
		case 'hot':$rank='periods desc,scale desc';$rank_period='a.periods desc,a.scale desc';break;
		case 'priceup':$rank='zong_codes asc,scale asc';$rank_period='a.zong_codes asc,a.scale asc';break;
		case 'pricedown':$rank='zong_codes desc,scale desc';$rank_period='a.zong_codes desc,a.scale desc';break;
	}
	/*******判定是否是搜索*******/
	$search = !empty($_GPC['search'])?$_GPC['search']:'no';
	switch($search){
		case 'no':$condition_search='';$condition_search_period='';break;
		default:$condition_search=" and title LIKE '%".$search."%' ";$condition_search_period=" and b.title LIKE '%".$search."%' ";break;
	}
	/******判定加载页数****/
	$page = !empty($_GPC['page'])?$_GPC['page']:0;
	$pagenum = !empty($_GPC['pagenum'])?$_GPC['pagenum']:8;
	$pagestart = $page * $pagenum;
	
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
	
	/************检索分类商品数据*************/
	$datam = array(
		':uniacid'=>$_W['uniacid'],
		':status'=>2
	);
	$data = array(
		':uniacid'=>$_W['uniacid'],
		':status'=>1
	);
	$sql_allgoods = "select * from".tablename('weliam_indiana_goodslist')."where uniacid = :uniacid ".$condition_search." and status = :status ".$condition." order by ".$rank." limit ".$pagestart.",".$pagenum;
	$sql_allperiod = "select a.scale,a.period_number,a.canyurenshu,a.shengyu_codes,a.zong_codes,a.periods,a.goodsid,b.merchant_id,b.title,picarr,b.init_money from".tablename('weliam_indiana_period')." a,".tablename('weliam_indiana_goodslist')." b  where a.goodsid = b.id ".$condition_search_period." and a.uniacid = :uniacid and a.status = :status ".$condition_period." order by ".$rank_period." limit ".$pagestart.",".$pagenum;
	if(($oop == 'fast' || $oop == 'hot' || $oop == 'priceup' || $oop == 'pricedown') && ($op != 'go')){
		//条件检索period表
		$goodslist = pdo_fetchall($sql_allperiod,$data);
		foreach($goodslist as $key => $value){
			$goodslist[$key]['picarr'] = tomedia($value['picarr']);
			$goodslist[$key]['id'] = $value['goodsid'];
		}
		die(json_encode(array('status'=>1,'result'=>$goodslist)));exit;			//传回返回值
	}elseif(($oop == 'new') && ($op != 'go')){
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
		die(json_encode(array('status'=>1,'result'=>$goodslist)));exit;		//传回返回值
	}
	
	/******首次进入*****/
	if($op == 'go'){
		/*******获取自定义全部分类开始*******/
		$category = m('category')->getList(array('enabled'=>1,'order'=>'id asc','by'=>'','parentid'=>'0'));
		/*******获取自定义全部分类结束*******/
		include $this->template('goods/allgoods');
	}
?>