<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Record {
	public function getList($args = array()) {
		global $_W;
		$page = !empty($args['page'])? intval($args['page']): 1;
		$pagesize = !empty($args['pagesize'])? intval($args['pagesize']): 10;
		$random = !empty($args['random'])? $args['random'] : false;
		$orderby = !empty($args['orderby'])? $args['orderby'] : '';
		$condition =  "and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		$status = !empty($args['status'])? $args['status']:'';
		$unstatus = !empty($args['unstatus'])? $args['unstatus']:'';
		$period_number = !empty($args['period_number'])? $args['period_number']:'';
		if($period_number){
			$condition .=  " and period_number = :period_number ";
			$params[':period_number'] = $period_number;
		}
		if($status){
			$condition .=  " and status = :status ";
			$params[':status'] = $status;
		}
		if($unstatus){
			$condition .=  " and status <> 0 ";
		}
		if (!$random) {
			$sql = "SELECT * FROM " . tablename('weliam_indiana_record') . " where 1 {$condition} ORDER BY {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
		} else{
			$sql = "SELECT * FROM " . tablename('weliam_indiana_record') . " where 1 {$condition} ORDER BY {$orderby}";
		}
		$list = pdo_fetchall($sql, $params);
		return $list;
	} 
	function getRecordById($id){
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT * FROM " . tablename('weliam_indiana_record') . " where 1 {$condition} and id='{$id}'";
		$period = pdo_fetch($sql, $params);
		return $period;
	}
	function getRecordByOpenid($openid){
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT * FROM " . tablename('weliam_indiana_record') . " where 1 {$condition} and openid='{$openid}'";
		$period = pdo_fetch($sql, $params);
		return $period;
	}
	
} 
