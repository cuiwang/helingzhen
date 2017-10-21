<?php
global $_GPC, $_W;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$total = pdo_fetchcolumn("select sum(res) from " . tablename('weixin_vote') . " where weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
echo json_encode(intval($total)); 