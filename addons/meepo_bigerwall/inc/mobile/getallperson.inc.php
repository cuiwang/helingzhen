<?php
 global $_GPC, $_W;
	   $weid = $_W['uniacid'];
	   $rid = intval($_GPC['rid']);
	      
	   $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weixin_flag') . " WHERE weid = '{$weid}' AND rid='{$rid}'");
	   $all = intval($total);
       die($all);