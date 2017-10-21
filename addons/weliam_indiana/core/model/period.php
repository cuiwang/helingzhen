<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Period {
	public function getList($args = array()) {
		global $_W;
		$page = !empty($args['page'])? intval($args['page']): 1;
		$pagesize = !empty($args['pagesize'])? intval($args['pagesize']): 10;
		$random = !empty($args['random'])? $args['random'] : false;
		$orderby = !empty($args['orderby'])? $args['orderby'] : '';
		$condition =  " and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		$status = !empty($args['status'])? $args['status']:'';
		$num = $args['num'];
		if($status){
			$condition .=  " and status = :status ";
			$params[':status'] = $status;
		}
		if (!$random) {
			$sql = "SELECT * FROM " . tablename('weliam_indiana_period') . " where 1 {$condition} ORDER BY {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
		} else{
			$sql = "SELECT * FROM " . tablename('weliam_indiana_period') . " where 1 {$condition} ORDER BY {$orderby} limit {$num},5";
		}
		$list = pdo_fetchall($sql, $params);
		return $list;
	} 
	function getPeriodById($id){
		global $_W;
		$condition =  "and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT * FROM " . tablename('weliam_indiana_period') . " where 1 {$condition} and (id LIKE '{$id}' or period_number LIKE '{$id}')";
		$period = pdo_fetch($sql, $params);
		return $period;
	}
	function getPeriodByPeriod_number($period_number){
		global $_W;
		$condition =  "and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT * FROM " . tablename('weliam_indiana_period') . " where 1 {$condition} and period_number='{$period_number}'";
		$period = pdo_fetch($sql, $params);
		return $period;
	}
	function getPeriodByGoods($goods=array(),$conten=''){
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT zong_codes,shengyu_codes,periods,goodsid,scale,period_number FROM " . tablename('weliam_indiana_period') . " where 1 {$condition} and goodsid='{$goods['id']}' and periods={$goods['periods']} {$content}";
		$period = pdo_fetch($sql, $params);
		return $period;
	}
} 
