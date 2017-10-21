<?php
/**
 * cjwy_survey 金表数据
 * 易 福 源 码 网 ============================================================================
*/
global $_GPC,  $_W;
$uniacid=$_W["uniacid"];
		
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$where = $_GPC['title'] ? " AND title LIKE '{$_GPC['title']}'" : '';

$list = pdo_fetchall( 'SELECT * FROM '.tablename('cjwy_survey').' WHERE uniacid = :uniacid '.$where.' ORDER BY sid DESC  LIMIT '. ($pindex -1) * $psize . ',' .$psize , array(':uniacid' => $uniacid));
$total = pdo_fetchcolumn( 'SELECT COUNT(*) FROM '.tablename('cjwy_survey').' WHERE uniacid = :uniacid'.$where, array(':uniacid' => $uniacid) );
$pager = pagination($total, $pindex, $psize);

include $this->template('list');