<?php
/**
 * fwei_survey 微调研
 * ============================================================================
 * * 版权所有 2005-2012 fwei.net，并保留所有权利。
 *   网站地址: http://www.fwei.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: fwei.net $
 *
*/
global $_GPC,  $_W;
$uniacid=$_W["uniacid"];
		
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$where = $_GPC['title'] ? " AND title LIKE '{$_GPC['title']}'" : '';

$list = pdo_fetchall( 'SELECT * FROM '.tablename('fwei_survey').' WHERE uniacid = :uniacid '.$where.' ORDER BY sid DESC  LIMIT '. ($pindex -1) * $psize . ',' .$psize , array(':uniacid' => $uniacid));
$total = pdo_fetchcolumn( 'SELECT COUNT(*) FROM '.tablename('fwei_survey').' WHERE uniacid = :uniacid'.$where, array(':uniacid' => $uniacid) );
$pager = pagination($total, $pindex, $psize);

include $this->template('list');