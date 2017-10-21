<?php
global $_W,$_GPC;
$id = intval($_GPC['id']);
$weid = $_W['uniacid'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = '';
$type = empty($_GPC['type']) ? 0:intval($_GPC['type']);
if($type > 0){
		$condition .= " AND team='$type'";
}
$bahe_status = pdo_fetchcolumn("SELECT `bahe_status` FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid", array(':weid'=>$weid,':rid'=>$id));
$sql = "SELECT * FROM  ".tablename('weixin_bahe_team')." WHERE weid=:weid AND rid=:rid $condition ORDER BY point DESC,createtime DESC LIMIT ".($pindex - 1) * $psize.",{$psize}"; 
$list  = pdo_fetchall($sql,array(':weid'=>$weid,':rid'=>$id));
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_bahe_team') . " WHERE  rid=:rid AND weid=:weid $condition", array(':rid' => $id,':weid'=>$weid));
$pager = pagination($total, $pindex, $psize);
include $this->template('bahe_phb');