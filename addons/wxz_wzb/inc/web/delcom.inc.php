<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$idArr = ($_GPC['idArr']);

pdo_query("delete from ".tablename('wxz_wzb_comment')." where id in (".implode(',',$idArr).")");

echo json_encode('1');


