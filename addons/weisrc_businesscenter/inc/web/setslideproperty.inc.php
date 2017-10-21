<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$type = $_GPC['type'];
$data = intval($_GPC['data']);
empty($data) ? ($data = 1) : $data = 0;
if (!in_array($type, array('status'))) {
    die(json_encode(array("result" => 0)));
}
pdo_update($this->table_slide, array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
die(json_encode(array("result" => 1, "data" => $data)));