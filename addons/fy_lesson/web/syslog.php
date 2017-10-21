<?php
/**
 * 日志管理
 */

if ($operation == 'display'){
	/* 所有操作员 */
	$admin_list = pdo_fetchall("SELECT uid,username FROM " .tablename($this->table_users));
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	$condition = " uniacid=:uniacid ";
	$params = array(':uniacid' => $uniacid);
	if($_W['user']['groupid']!=0){
		$condition .= " AND admin_uid=:admin_uid ";
		$params[':admin_uid'] = $_W['uid'];
	}
	if(!empty($_GPC['function'])){
		$condition .= " AND function LIKE :function ";
		$params[':function'] = "%".$_GPC['function']."%";
	}
	if($_GPC['log_type']>0){
		$condition .= " AND log_type=:log_type ";
		$params[':log_type'] = $_GPC['log_type'];
	}
	if($_W['user']['groupid']==0 && $_GPC['admin_uid']>0){
		$condition .= " AND admin_uid=:admin_uid ";
		$params[':admin_uid'] = $_GPC['admin_uid'];
	}
	if (!empty($_GPC['time']['start'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$endtime = !empty($endtime) ? $endtime + 86399 : 0;
		if (!empty($starttime)) {
			$condition .= " AND addtime >= :starttime ";
			$params[':starttime'] = $starttime;
		}
		if (!empty($endtime)) {
			$condition .= " AND addtime <= :endtime ";
			$params[':endtime'] = $endtime;
		}
	}

	$list = pdo_fetchall("SELECT * FROM " .tablename($this->table_syslog). " WHERE {$condition} ORDER BY addtime DESC LIMIT " .($pindex - 1) * $psize. ',' . $psize, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename($this->table_syslog). " WHERE {$condition}", $params);
	$pager = pagination($total, $pindex, $psize);

}else if ($operation == 'delete'){
	$aid = intval($_GPC['aid']);
	if($aid>0){
		$article = pdo_fetch("SELECT * FROM " .tablename($this->table_article). " WHERE uniacid='{$uniacid}' AND id='{$aid}'");
		if(empty($article)){
			message("该文章公告不存在！", "", "error");
		}
	}

	$res = pdo_delete($this->table_article, array('uniacid'=>$uniacid, 'id'=>$aid));
	if($res){
		$this->addSysLog($_W['uid'], $_W['username'], 2, "文章公告", "删除ID:{$aid}的文章公告");
	}
	message("删除成功", $this->createWebUrl('article'), "success");
}

include $this->template('syslog');

?>