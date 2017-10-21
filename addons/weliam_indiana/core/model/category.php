<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Category {
	public function getList($args = array()) {
		global $_W;
		$fetch = !empty($args['fetch'])? $args['fetch'] : 0;
		$order = !empty($args['order'])? $args['order'] : '';
		$orderby = !empty($args['by'])? $args['by'] : '';
		$condition =  "and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		$id = !empty($args['id'])? $args['id']:'';
		if (!empty($id)) {
			$condition .=  " and id = :id ";
			$params[':id'] = $id;
		}
		if ($args['parentid']!='') {
			$condition .=  " and parentid = :parentid ";
			$params[':parentid'] = $args['parentid'];
		}
		$enabled = !empty($args['enabled'])? 1 : 0;
		if (!empty($enabled)) {
			$condition .= " and enabled=1 ";
		} 
		if($fetch){
			$sql = "SELECT * FROM " . tablename('weliam_indiana_category') . " where 1 {$condition}";
			$list = pdo_fetch($sql, $params);
		}else{
			$sql = "SELECT * FROM " . tablename('weliam_indiana_category') . " where 1 {$condition} ORDER BY {$order} {$orderby}";
			$list = pdo_fetchall($sql, $params);
		}
		
		return $list;
	} 
	public function getCategoryByName($name = '') {
			global $_W;
			$array=array();
			$sql = "SELECT * FROM " . tablename('weliam_indiana_category') . " where 1  and name='{$name}' and uniacid = '{$_W['uniacid']}'";
			$category = pdo_fetch($sql);
			return $category;
	} 
	public function getCategoryByNamePid($name = '',$pid=0) {
			global $_W;
			$array=array();
			$sql = "SELECT * FROM " . tablename('weliam_indiana_category') . " where 1 and name='{$name}' and parentid='{$pid}' and uniacid = '{$_W['uniacid']}'";
			$category = pdo_fetch($sql);
			return $category;
	}
	public function getCategoryByid($id=0) {
			global $_W;
			$array=array();
			$sql = "SELECT * FROM " . tablename('weliam_indiana_category') . " where 1 and id='{$id}' and uniacid = '{$_W['uniacid']}'";
			$category = pdo_fetch($sql);
			return $category;
	}
} 
