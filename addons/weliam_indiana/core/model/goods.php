<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Goods {
	public function getList($args = array()) {
		global $_W;
		$page = !empty($args['page'])? intval($args['page']): 1;
		$pagesize = !empty($args['pagesize'])? intval($args['pagesize']): 10;
		$random = !empty($args['random'])? $args['random'] : false;
		$order = !empty($args['order'])? $args['order'] : '';
		$orderby = !empty($args['by'])? $args['by'] : '';
		$condition =  "and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		$id = !empty($args['id'])? $args['id']:'';
		if (!empty($id)) {
			$condition .=  " and id = :id";
			$params[':id'] = $id;
		}
		$status = !empty($args['status'])? $args['status']:'';
		if (!empty($status)) {
			$condition .=  "and status = :status";
			$params[':status'] = $status;
		}
		$isnew = !empty($args['isnew'])? 1 : 0;
		if (!empty($isnew)) {
			$condition .= " and isnew=1";
		} 
		$ishot = !empty($args['ishot'])? 1 : 0;
		if (!empty($ishot)) {
			$condition .= " and ishot=1";
		} 
		$keywords = !empty($args['keywords'])? $args['keywords'] : '';
		if (!empty($keywords)) {
			$condition .= "AND 'title' LIKE :title";
			$params[':title'] = '%' . trim($keywords) . '%';
		} 
		
		if (!$random) {
			$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 {$condition} ORDER BY {$order} {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
		} else{
				$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 {$condition}";
		}
		$list = pdo_fetchall($sql, $params);
		return $list;
	} 
	function getGoods($id){
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 {$condition} and id='{$id}'";
		$goods = pdo_fetch($sql, $params);
		return $goods;
	}
	function getListByCategory($categoryid=0){
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 {$condition} and category_parentid='{$categoryid}' and status=2";
		$goodses = pdo_fetchall($sql, $params);
		return $goodses;
	}
	function getListBypcID($pid=0,$cid=0){
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 {$condition} and category_parentid='{$pid}' and category_childid='{$cid}' and status=2";
		$goodses = pdo_fetchall($sql, $params);
		return $goodses;
	}
	function getListByChildName($name=''){
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 {$condition}  and status=2";
		$goodses1 = pdo_fetchall($sql, $params);
		$goodses=array();
		foreach($goodses1 as $key=>$value){
			$category=m('category')->getCategoryByid($value['category_childid']);
			if($name==$category['name']){
				$goodses[$key]=$value;
			}
			
		}
		return $goodses;
	}
	function getListByPeriod_number($period_number = ''){
		//通过期号获取物品信息
		global $_W,$_GPC;
		$period = pdo_fetch("select * from".tablename('weliam_indiana_period')."where uniacid = '{$_W['uniacid']}' and period_number = '{$period_number}'");
		$goodslist = pdo_fetch("select init_money from".tablename('weliam_indiana_goodslist')."where uniacid = '{$_W['uniacid']}' and id = '{$period['goodsid']}' ");
		return $goodslist;
	}
	function getCartNumber($openid = ''){
		//检索购物车商品数量
		global $_W,$_GPC;
		$cart_num = pdo_fetchcolumn("select count(id) from".tablename('weliam_indiana_cart')."where openid='{$openid}' and uniacid={$_W['uniacid']}");
		return $cart_num;
	}
	
	function checkGoods($goodsid = '' , $periods = '',$id=''){
		//检测商品同期期数
		global $_W;
		$num = pdo_fetch("select id from".tablename('weliam_indiana_period')."where uniacid=:uniacid and goodsid=:goodsid and periods=:periods",array(':uniacid'=>$_W['uniacid'],':goodsid'=>$goodsid,':periods'=>$periods));
		if($num > 1){
			$result = pdo_fetchall("select id from".tablename('weliam_indiana_period')."where uniacid=:uniacid and goodsid=:goodsid and periods=:periods",array(':uniacid'=>$_W['uniacid'],':goodsid'=>$goodsid,':periods'=>$periods));
			foreach($result as $key=>$value){
				if($value['id'] != $id){
					pdo_delete("weliam_indiana_period",array('id'=>$value['id']));
				}
			}
		}
		
		$last_period = pdo_fetchall("select id,period_number from".tablename('weliam_indiana_period')." where goodsid=:goodsid and periods<:periods and status=:status",array(':goodsid'=>$goodsid,':periods'=>$periods,':status'=>1));
		if(!empty($last_period)){
			foreach($last_period as $key=>$value){
				m('codes')->createtime_winer($value['id'],$value['period_number']);
			}
		}
		
		//检测当前期是否开奖
		$is_createwiner = pdo_fetch("select id,shengyu_codes,status,period_number from".tablename("weliam_indiana_period")." where uniacid=:uniacid and goodsid=:goodsid and periods=:periods",array(':uniacid'=>$_W['uniacid'],':goodsid'=>$goodsid,':periods'=>$periods));
		if($is_createwiner['status'] == 1 && $is_createwiner['shengyu_codes'] < 1){
			m('codes')->createtime_winer($value['id'],$is_createwiner['period_number']);
			m('codes')->create_newgoods($goodsid);
		}
	}
} 
