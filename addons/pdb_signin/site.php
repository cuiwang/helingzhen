<?php
/**
 * 我要签到模块微站定义
 *
 * 请遵循开源协议，本模块源码允许二次修改和开发，但必须注明作者和出处，如不遵守，我们将保留追求的权利。
 * @author PHPDB
 * @url http://www.phpdb.net/
 */
defined('IN_IA') or exit('Access Denied');

class Pdb_signinModuleSite extends WeModuleSite {

	public function doWebList() {
		global $_GPC, $_W;
		load()->func('tpl');
		//显示资源列表；
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = ' and rl.name != "" ';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND (rl.name LIKE '%{$_GPC['keyword']}%' or a.title like '%{$_GPC['keyword']}%' ) ";
		} 
		
		if (isset($_GPC['status'])) {
			$condition .= " AND a.status = '" . intval($_GPC['status']) . "'";
		}else{
			$_GPC['status'] = 1;
		}
		$sql = "SELECT * FROM " . tablename('pdb_signin') . " as a ".
				" left join ". tablename('rule') . " as rl " .
				" on a.rid = rl.id ".
				" WHERE a.uniacid = '{$_W['uniacid']}' $condition ORDER BY a.status DESC,a.id DESC LIMIT " . 
				($pindex - 1) * $psize . ',' . $psize;
		// echo $sql;exit;
		$list = pdo_fetchall($sql);
		// print_r($list);exit;
		
		$sql = "SELECT COUNT(*) FROM " . tablename('pdb_signin') . " as a ".
				" left join ". tablename('rule') . " as rl " .
				" on a.rid = rl.id ".
				" WHERE a.uniacid = '{$_W['uniacid']}' $condition ";
		$total = pdo_fetchcolumn($sql);
		$pager = pagination($total, $pindex, $psize);
		
		include $this->template('list');
	}

}

//函数区：

//读取关键词的函数：
function getKeywords($rid){
	global $_W;
	$sql = "select * from ".tablename('rule_keyword') . " where rid = '{$rid}' and uniacid = '{$_W['uniacid']}' ";
	$keywords = pdo_fetchall($sql);
	$arr = array();
	if (is_array($keywords)){
		foreach ($keywords as $keyword){
			$arr[] = $keyword['content'];
		}
	}
	return join(',',$arr);
	// print_r($arr);exit;
}