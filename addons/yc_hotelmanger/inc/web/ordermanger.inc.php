<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;

if (!$this->seearr) {
    message('请先配置基本设置!', $this->createWebUrl('Setting'), 'success');
}


$hotelid = intval($_GPC['hotelid']);
$roomid = intval($_GPC['roomid']);
$mid = intval($_GPC['mid']);
$where = '';
$params = array();
$where .= ' uniacid = ' . $this->_weid;

if ($hotelid) {
    $where .= ' and hotelid = ' . $hotelid;
    $params[':hotelid'] = $hotelid;
}
if ($roomid) {
    $where .= ' and roomid = ' . $roomid;
    $params[':roomid'] = $roomid;
}
if ($mid) {
    $where .= ' and mid = ' . $mid;
    $params[':mid'] = $mid;
}
if ($_GPC['status'] != '') {
    $status = intval($_GPC['status']);
    switch ($status) {
        case 1:
            $where .= ' and order_status=1';
            $params[':order_status'] = 1;
            break;

        case 2:
            $where .= ' and order_status=2';
            $params[':order_status'] = 2;
            break;

        case 3:
            $where .= ' and order_status=3';
            $params[':order_status'] = 3;
            break;

        case 4:
            $where .= ' and order_status=4';
            $params[':order_status'] = 4;
            break;

        case 5:
            $where .= ' and order_status=5';
            $params[':order_status'] = 5;
            break;

        case 6:
            $where .= ' and order_status=6';
            $params[':order_status'] = 6;
            break;

        case 7:
            $where .= ' and order_status=7';
            $params[':order_status'] = 7;
            break;
        case 0:
            $where .= ' and order_status=0';
            $params[':order_status'] = 0;
            break;
    }
} else {
    $status = 8;
}
$pindex = max(1, intval($_GPC['page']));
$psize = $this->psize;
$orderlist = pdo_fetchall('SELECT * FROM ' . tablename($this->order) . ' WHERE ' . $where . '  ORDER BY order_id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

$total = pdo_fetchcolumn('SELECT COUNT(*)  FROM ' . tablename($this->order) . ' WHERE' . $where, $params);
$pager = pagination($total, $pindex, $psize);

include $this->template('orderlist');
