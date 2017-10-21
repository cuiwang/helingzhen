<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$storeid = intval($_GPC['storeid']);

$savenumber = $this->get_save_number($weid);
$result = array('status' => 1);

if (empty($from_user)) {
    $result = array('status' => 0);
    echo json_encode($result);
    exit;
}

$data = array(
    'weid' => $weid,
    'storeid' => $storeid,
    'from_user' => $from_user,
    'savenumber' => $savenumber,
    'title' => $_GPC['title'],
    'username' => $_GPC['username'],
    'tel' => $_GPC['usermobile'],
    'remark' => '',
    'displayorder' => 0,
    'status' => 0,
    'dateline' => TIMESTAMP
);
pdo_insert($this->table_savewine_log, $data);
echo json_encode($result);