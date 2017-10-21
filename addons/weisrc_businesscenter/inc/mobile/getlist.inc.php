<?php
global $_GPC, $_W;
$weid = intval($_GET['weid']);
$cid = intval($_GPC['cid']);

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
$pindex = max(1, intval($_GPC['page']));
$psize = empty($setting) ? 5 : intval($setting['pagesize']);
$condition = '';

$cid = intval($_GPC['cid']);
if (empty($cid)) {
    exit;
}

$condition .= " AND pcate={$cid}";
//商家列表
$stores = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 {$condition} ORDER BY displayorder DESC,status DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $weid));

$result = array();
foreach ($stores as $key => $value) {
    $result[] = array(
        'id' => $value['id'], 'img' => $value['logo'], 'title' => $value['title'], 'telephone' => $value['tel'], 'address' => $value['address']
    );
}
exit(json_encode($result));