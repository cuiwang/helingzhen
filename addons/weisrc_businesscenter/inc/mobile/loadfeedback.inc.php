<?php
global $_GPC, $_W;
$storeid = intval($_GPC['storeid']);
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = " WHERE weid = " . $_W['uniacid'] . " AND storeid={$storeid} AND status=1";

$list = pdo_fetchall("SELECT *,date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d') as date FROM " . tablename($this->table_feedback) . " {$condition} ORDER BY displayorder DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_feedback) . " {$condition} ");

$result = array(
    'data' => $list,
    'status' => 10,
    'page' => 11
);
if (count($list) == 0) {
    $result['status'] = 0;
}
if ($psize * $pindex + count($list) <= $total) {
    $result['status'] = 10;
} else {
    $result['status'] = 1;
}

die(json_encode($result));