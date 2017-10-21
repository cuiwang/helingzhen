<?php
	global $_GPC,$_W;
	$share_data = $this->module['config'];
	$uniacid=$_W['uniacid'];
	$openid = m('user') -> getOpenid();
	//幻灯片
	$advs = pdo_fetchall("select * from " . tablename('weliam_indiana_adv') . " where enabled=1 and weid= '{$_W['uniacid']}' order by displayorder desc");
	foreach ($advs as &$adv) {
		if (substr($adv['link'], 0, 5) != 'http:') {
			$adv['link'] = "http://" . $adv['link'];
		}
	}
	unset($adv);
	$goodsessort = pdo_fetchall("select * from".tablename('weliam_indiana_goodslist')."where uniacid = {$_W['uniacid']} and status = '2' and ishot = '1' and sort>0 order by sort desc");
	foreach ($goodsessort as $k => $v) {
		$period = pdo_fetch("SELECT scale,periods,period_number,canyurenshu,shengyu_codes,zong_codes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and goodsid = '{$v['id']}' and periods = {$v['periods']}");
		$goodsessort[$k]['scale'] = $period['scale'];
		$goodsessort[$k]['period_number'] = $period['period_number'];
		$goodsessort[$k]['canyurenshu'] = $period['canyurenshu'];
		$goodsessort[$k]['shengyu_codes'] = $period['shengyu_codes'];
		$goodsessort[$k]['zong_codes'] = $period['zong_codes'];
		$goodsessort[$k]['periods'] = $period['periods'];
	}
	
	$goodses = pdo_fetchall("select * from".tablename('weliam_indiana_goodslist')."where uniacid = {$_W['uniacid']} and status = '2' and ishot = '1' and sort<1 order by id desc");
	foreach ($goodses as $key => $value) {
		$period = pdo_fetch("SELECT scale,periods,period_number,canyurenshu,shengyu_codes,zong_codes FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = '{$_W['uniacid']}' and goodsid = '{$value['id']}' and periods = {$value['periods']}");
		$goodses[$key]['scale'] = $period['scale'];
		$goodses[$key]['period_number'] = $period['period_number'];
		$goodses[$key]['canyurenshu'] = $period['canyurenshu'];
		$goodses[$key]['shengyu_codes'] = $period['shengyu_codes'];
		$goodses[$key]['zong_codes'] = $period['zong_codes'];
		$goodses[$key]['periods'] = $period['periods'];
	}
	
	$max = array();
	//冒泡排序定首页热门商品排列顺序
	for($i=0 ; $i<sizeof($goodses) ; $i++ ){
		for($j=sizeof($goodses)-1; $j>0 ;$j-- ){
			if($goodses[$j]['scale']>$goodses[$j-1]['scale']){
				$max = $goodses[$j];
				$goodses[$j] = $goodses[$j-1];
				$goodses[$j-1] = $max;
			}
		}
	}
	//新品
	$pindex = 1;
	$psize = 3;
	$args = array('page' => $pindex, 'pagesize' => $psize,'isnew'=>1, 'status' => 2, 'order' => 'id desc', 'by' => '');
	$s_pos = m('goods') -> getList($args);
	$myCart_num = pdo_fetchcolumn("select count(*) from".tablename('weliam_indiana_cart')."where openid = '{$openid}' and uniacid = {$_W['uniacid']}");
	
	//最新揭晓z
	$jiexiaoperiods = pdo_fetchall("select * from".tablename("weliam_indiana_period")."where uniacid = {$_W['uniacid']} and status = 2  order by endtime desc limit 0,3");
	foreach($jiexiaoperiods as$key=>$value){
		$goods=m('goods')->getGoods($value['goodsid']);
		$jiexiaoperiods[$key]['picarr'] = $goods['picarr'];
		$jiexiaoperiods[$key]['init_money'] = $goods['init_money'];
	}
	if(empty($jiexiaoperiods)){
		$jiexiaoperiods = pdo_fetchall("select * from".tablename("weliam_indiana_period")."where uniacid = {$_W['uniacid']} and status in(3,4,5,6,7)  order by endtime desc limit 0,3");
		foreach($jiexiaoperiods as$key=>$value){
			$goods=m('goods')->getGoods($value['goodsid']);
			$jiexiaoperiods[$key]['picarr'] = $goods['picarr'];
			$jiexiaoperiods[$key]['init_money'] = $goods['init_money'];
		}
	}
	if($_GPC['type']=='changeStatus'){
		$periodnumber = $_GPC['periodnumber'];
		if(pdo_update("weliam_indiana_period",array('status'=>3),array('period_number'=>$periodnumber))){
			$period=m('period')->getPeriodByPeriod_number($periodnumber);
			die(json_encode(array("result"=>1,"name"=>$period['nickname'],"openid"=>$period['openid'])));
		}else{
			die(json_encode(array("result"=>0)));
		}
	}else{
		$template = $this->module['config']['style'];
		if($template == 'yungou'){
			header("location:".'../../app/' .$this->createMobileUrl('allgoods'));
		}else{
			include $this->template('index');

		}
		
		
	}
	
?>