<?php
	global $_GPC,$_W;
	$category = m('category')->getList(array('enabled'=>1,'order'=>'id asc','by'=>'','parentid'=>'0'));
	$openid = m('user') -> getOpenid();
	$id = $_GPC['id'];
	$page = !empty($_GPC['page'])?$_GPC['page']:0;
	$pagenum = $page*10;
	$advs = pdo_fetchall("select * from " . tablename('weliam_indiana_adv') . " where enabled=1 and weid= '{$_W['uniacid']}' order by displayorder desc");
	foreach ($advs as &$adv) {
		if (substr($adv['link'], 0, 5) != 'http:') {
			$adv['link'] = "http://" . $adv['link'];
		}
	}
	unset($adv);
	if(empty($id)){
		$args = array('status' => '0', 'order' => 'id desc', 'by' => '','random'=>true);
		$periods = pdo_fetchall("SELECT zong_codes,shengyu_codes,periods,goodsid,scale,period_number,id FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = {$_W['uniacid']} and status=:status order by scale desc , id desc limit ".$pagenum.",10 ",array(':status'=>1));
		$myCart_num = pdo_fetchcolumn("select count(*) from".tablename('weliam_indiana_cart')."where openid = '{$openid}' and uniacid={$_W['uniacid']}");	
		foreach($periods as $key=>$value){
			$goods = m('goods')->getGoods($value['goodsid']);
			$periods[$key]['title']= $goods['title'];
			$periods[$key]['picarr']= $goods['picarr'];
			$periods[$key]['init_money']= $goods['init_money'];
		}
		if(!empty($periods[9]['id'])){
			$id = $periods[9]['id'];
		}else{
			$id = '-1';
		}
		include $this->template('allgoods');
	}else{
		$periods = pdo_fetchall("SELECT zong_codes,shengyu_codes,periods,goodsid,scale,period_number,id FROM " . tablename('weliam_indiana_period') . " WHERE uniacid = {$_W['uniacid']} and status=:status  order by scale desc , id desc limit ".$pagenum.",10 ",array(':status'=>1));
		$myCart_num = pdo_fetchcolumn("select count(*) from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']}");	
		foreach($periods as $key=>$value){
			$goods = m('goods')->getGoods($value['goodsid']);
			$periods[$key]['title']= $goods['title'];
			$periods[$key]['picarr']= $goods['picarr'];
			$periods[$key]['init_money']= $goods['init_money'];
		}
			foreach ($periods as $item){
			$row=array(
				'zong_codes'=>$item['zong_codes'],
				'shengyu_codes'=>$item['shengyu_codes'],
				'periods'=>$item['periods'],
				'goodsid'=>$item['goodsid'],
				'scale'=>$item['scale'],
				'period_number'=>$item['period_number'],
				'title'=>$item['title'],
				'picarr'=>tomedia($item['picarr']),
				'init_money' =>$item['init_money']
			);
			$info[]=$row;			
		}	
		for( $i=10;empty($periods[$i]['id'])&&$i>-1;$i--){
			$id = $periods[$i-1]['id'];
		}
		if(empty($id)){
			$id = -1;
		}
		$data = array(
			'success' => $id,
			'list' => $info
		);
		echo json_encode($data);
	}
	
?>