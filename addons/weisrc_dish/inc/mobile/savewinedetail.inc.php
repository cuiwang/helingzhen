<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$id = intval($_GPC['orderid']);

$order = pdo_fetch("SELECT a.* FROM " . tablename($this->table_savewine_log) . " AS a LEFT JOIN " . tablename($this->table_stores) . " AS b ON a.storeid=b.id  WHERE a.id ={$id} AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT 20");
if (empty($order)) {
    message('订单不存在');
}

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid AND id=:id ORDER BY displayorder DESC", array(':weid' => $weid, ':id' => $order['storeid']));

include $this->template($this->cur_tpl . '/savewinedetail');