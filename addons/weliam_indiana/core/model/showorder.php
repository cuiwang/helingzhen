<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Showorder {
	public function getList($args = array()) {
		global $_W;
		$page = !empty($args['page'])? intval($args['page']): 1;
		$pagesize = !empty($args['pagesize'])? intval($args['pagesize']): 10;
		$random = !empty($args['random'])? $args['random'] : false;
		$order = !empty($args['order'])? $args['order'] : '';
		$orderby = !empty($args['by'])? $args['by'] : '';
		$condition =  "and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		$status = !empty($args['status'])? $args['status']:'';
		if (!empty($status)) {
			$condition .=  " and status = :status ";
			$params[':status'] = $status;
		}
		
		$keywords = !empty($args['keywords'])? $args['keywords'] : '';
		if (!empty($keywords)) {
			$condition .= " AND 'title' LIKE :title ";
			$params[':title'] = '%' . trim($keywords) . '%';
		} 
		if($one){
			$sql = "SELECT * FROM " . tablename('weliam_indiana_goodslist') . " where 1 {$condition}";
			$list = pdo_fetch($sql, $params);
		}else{
			if (!$random) {
			$sql = "SELECT * FROM " . tablename('weliam_indiana_record') . " where 1 {$condition} ORDER BY {$order} {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
			} else{
				$sql = "SELECT * FROM " . tablename('weliam_indiana_record') . " where 1 {$condition}";
			}
			$list = pdo_fetchall($sql, $params);
		}
		return $list;
	} 
} 
