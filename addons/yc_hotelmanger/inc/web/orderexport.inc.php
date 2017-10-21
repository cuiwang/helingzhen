<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;
$hotelid = intval($_GPC['hotelid']);
$roomid = intval($_GPC['roomid']);
$mid = intval($_GPC['mid']);
$status = intval($_GPC['status']);
$where = '';
$where .= ' uniacid = ' . $this->_weid;

if ($hotelid) {
    $where .= ' and hotelid = ' . $hotelid;
}
if ($roomid) {
    $where .= ' and roomid = ' . $roomid;
}
if ($mid) {
    $where .= ' and mid = ' . $mid;
}


switch ($status) {
    case 1:
        $where .= ' and order_status=1';
        break;

    case 2:
        $where .= ' and order_status=2';
        break;

    case 3:
        $where .= ' and order_status=3';
        break;

    case 4:
        $where .= ' and order_status=4';
        break;

    case 5:
        $where .= ' and order_status=5';
        break;

    case 6:
        $where .= ' and order_status=6';
        break;
}
//int(94)
//int(232)
//array(2) {
//  [0]=>
//  int(73)
//  [1]=>
//  int(230)
//}
/* bug可能存在bug, 将此后代码到:switch 的结束大括号代码移到switch的结束外面 */
$orderlist = pdo_fetchall('SELECT * FROM ' . tablename($this->order) . ' WHERE ' . $where . '  ORDER BY order_id DESC ');
$data = array();

foreach ($orderlist as $k => $val) {
    $data[$k]['order_on'] = $val['order_on'];
    $data[$k]['order_name'] = $val['order_name'];
    $data[$k]['phone'] = $val['phone'];
    $data[$k]['hotelname'] = $this->get_hotelname($val['hotelid']);
    $data[$k]['tancan'] = $val['goods_name'];
    $data[$k]['yu_legth'] = $val['yu_legth'];
    $data[$k]['ordertime'] = $val['ordertime'];
    $data[$k]['sintdate'] = date('Y-m-d', $val['sintdate']) . ' ' . $val['xtime'];
    $data[$k]['soutdate'] = date('Y-m-d', $val['soutdate']);

    switch ($val['mode']) {
        case 'wechat':
            $data[$k]['mode'] = '微信支付';
            break;

        default:
            break;
    }
//int(164)
//int(178)
//array(2) {
//  [0]=>
//  int(161)
//  [1]=>
//  int(177)
//}
    /* bug可能存在bug, 将此后代码到:switch 的结束大括号代码移到switch的结束外面 */
    switch ($val['mode']) {
        case "credit":
            $data[$k]['mode'] = '余额支付';
            break;

        default:
            break;
    }
//int(173)
//int(178)
//array(2) {
//  [0]=>
//  int(170)
//  [1]=>
//  int(177)
//}
    /* bug可能存在bug, 将此后代码到:switch 的结束大括号代码移到switch的结束外面 */
    switch ($val['mode']) {
        case "delivery":
            $data[$k]['mode'] = '进店付款';
    }

    switch ($val['order_status']) {
        case 0:
            $data[$k]['order_status'] = '未支付';
            break;

        case 1:
            $data[$k]['order_status'] = '已支付';
            break;

        case 2:
            $data[$k]['order_status'] = '进店付款';
            break;

        case 3:
            $data[$k]['order_status'] = '已入住';
            break;

        case 4:
            $data[$k]['order_status'] = '已完成';
            break;

        case 5:
            $data[$k]['order_status'] = '已取消';
            break;

        case 6:
            $data[$k]['order_status'] = '退款中';
            break;

        case 7:
            $data[$k]['order_status'] = '已退款';
    }

    $data[$k]['totalcpice'] = $val['totalcpice'];
}

$this->exportexcel($data, array('订单号', '顾客姓名', '电话', '酒店名称', '套餐', '房间数量', '预定时间', '预入住时间', '预离店时间', '支付方式', '订单状态', '订单总金额'), time());
