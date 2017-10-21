<?php
	global $_W,$_GPC;
	ob_clean();
	ob_start();
	$openid = m('user') -> getOpenid();
	$type = $_GPC['type'];
	$child = $_GPC['childcategory'];
	$parent = $_GPC['parentcategory'];
	$op = $_GPC['op'];
	$category = $_GPC['category'];
	if($_GPC['type']=='child'){
		$parent = $_GPC['parentcategory'];
		$child = $_GPC['childcategory'];
		if($parent=='allgoods'){
			if($child=='人气'){
				$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 and uniacid={$_W['uniacid']} and status=2 order by periods,canyurenshu desc limit 0,5";
			}elseif($child=='最新'){
				$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 and uniacid={$_W['uniacid']} and status=2 order by createtime desc limit 0,5";
				
			}
			$goodses = pdo_fetchall($sql);
			$periods = array();
			foreach($goodses as $key=>$value){
				$periods[$key] = m('period')->getPeriodByGoods($value,'');
				$periods[$key]['picarr'] = $value['picarr'];
				$periods[$key]['title']= $value['title'];
				$periods[$key]['init_money']= $value['init_money'];
			}
		}else{
			
			$parentCategory=m('category')->getCategoryByName($parent);
			if($child=='人气'){
				$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 and uniacid={$_W['uniacid']} and category_parentid={$parentCategory['id']} and status=2 order by periods,canyurenshu desc limit 0,5";
			}elseif($child=='最新'){
				$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 and uniacid={$_W['uniacid']} and category_parentid={$parentCategory['id']} and status=2 order by createtime desc limit 0,5";
			}
			$goodses = pdo_fetchall($sql);
			$periods = array();
			foreach($goodses as $key=>$value){
				$periods[$key] = m('period')->getPeriodByGoods($value,'');
				$periods[$key]['picarr'] = $value['picarr'];
				$periods[$key]['title']= $value['title'];
				$periods[$key]['init_money']= $value['init_money'];
			}
		}
		
		
	}elseif($_GPC['type']=='updown'){
		$parent = $_GPC['parentcategory'];
		if($parent=='allgoods'){
			$periods = m('period')->getList(array('random'=>true,'orderby'=>' shengyu_codes asc ','status'=>1 , 'num' => 0));
			foreach($periods as $key=>$value){
				$goods = m('goods')->getGoods($value['goodsid']);
				$periods[$key]['title']= $goods['title'];
				$periods[$key]['picarr']= $goods['picarr'];
				$periods[$key]['init_money']= $goods['init_money'];
			}
		}else{
			$parentCategory=pdo_fetch("SELECT * FROM " . tablename('weliam_indiana_category') . " where uniacid = '{$_W['uniacid']}' and name='{$parent}'");
			$goodses = pdo_fetchall("SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where uniacid = '{$_W['uniacid']}' and category_parentid='{$parentCategory['id']}' and status=2 ");
			$period=array();
			foreach($goodses as $k=>$v){
				$period[$k] = m('period')->getPeriodByGoods($v,'');
				$period[$k]['title']= $v['title'];
				$period[$k]['picarr']= $v['picarr'];
				$period[$k]['init_money']= $v['init_money'];
			}
			for($i=0;$i<count($period)-1;$i++ ){     
			    for($j=0;$j<count($period)-1-$i;$j++){ 
			        if($period[$j]['shengyu_codes'] > $period[$j+1]['shengyu_codes']){
			            $tmp = $period[$j];
			            $period[$j] = $period[$j+1];
			            $period[$j+1] = $tmp;
			        } 
			    }
			}
			
			for($i=0;$i<5;$i++){
				if(!empty($period[$i])){
					$periods[] = $period[$i];
				}
				
			}
		}
		
		
	}elseif($_GPC['type']=='allupdown'){
		$parent = $_GPC['parentcategory'];
		$op=$_GPC['op'];
		if($parent=='allgoods'){
			if($op=='up'){
				$periods = m('period')->getList(array('random'=>true,'orderby'=>' shengyu_codes asc ','status'=>1,'num' => 0));
			}else{
				$periods = m('period')->getList(array('random'=>true,'orderby'=>' shengyu_codes desc ','status'=>1,'num' => 0));
			}
			foreach($periods as $key=>$value){
				$goods = m('goods')->getGoods($value['goodsid']);
				$periods[$key]['title']= $goods['title'];
				$periods[$key]['picarr']= $goods['picarr'];
				$periods[$key]['init_money']= $goods['init_money'];
			}
		}else{
			
			$parentCategory=m('category')->getCategoryByName($parent);
			$goodses = m('goods')->getListByCategory($parentCategory['id']);
			$period=array();
			foreach($goodses as $k=>$v){
				$period[$k] = m('period')->getPeriodByGoods($v,'');
				$period[$k]['title']= $v['title'];
				$period[$k]['picarr']= $v['picarr'];
				$period[$k]['init_money']= $v['init_money'];
			}
			if($op=='up'){
				for($i=0;$i<count($period)-1;$i++ ){     
				    for($j=0;$j<count($period)-1-$i;$j++){ 
				        if($period[$j]['shengyu_codes'] > $period[$j+1]['shengyu_codes']){
				            $tmp = $period[$j];
				            $period[$j] = $period[$j+1];
				            $period[$j+1] = $tmp;
				        } 
				    }
				}
			}else{
				for($i=0;$i<count($period)-1;$i++ ){     
				    for($j=0;$j<count($period)-1-$i;$j++){ 
				        if($period[$j]['shengyu_codes'] < $period[$j+1]['shengyu_codes']){
				            $tmp = $period[$j];
				            $period[$j] = $period[$j+1];
				            $period[$j+1] = $tmp;
				        } 
				    }
				}
				
			}
			$periods = array();
			for($i=0;$i<5;$i++){
				if(!empty($period[$i])){
					$periods[] = $period[$i];
				}
				
			}
			
			
		}
		
		
	}else{
		$m = $_GPC['category'];
		$Category=m('category')->getCategoryByName($m);
		$goodses = m('goods')->getListByCategory($Category['id']);
		$periods = array();
		foreach($goodses as $key=>$value){
			$period[$key] = m('period')->getPeriodByGoods($value,'');
			$period[$key]['picarr'] = $value['picarr'];
			$period[$key]['title']= $value['title'];
			$period[$key]['init_money']= $value['init_money'];
		}
		for($i=0;$i<5;$i++){
				if(!empty($period[$i])){
					$periods[] = $period[$i];
				}
				
			}
	}
	
	include $this -> template('category');
	$contents = ob_get_contents();
	ob_clean();
	$result = array("result" => $contents);
	die(json_encode($result));
	
?>
