<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $_GPC;
global $_W;

if (!$this->is_weixin()) {
    message('请在微信中打开');
}


$hotelid = $_GPC['hotelid'];
$sintdate = $_GPC['sintdate'];
$soutdate = $_GPC['soutdate'];
$room = pdo_fetchall('SELECT * FROM ' . tablename($this->room) . ' WHERE uniacid =' . $this->_weid . ' and status = 1 and  hotelid =' . $hotelid);
$html = '';

foreach ($room as $k => $val) {
    $html .= '<div class="hotel_yo" style="transform-origin: 0px 0px 0px; opacity: 1; transform: scale(1, 1);"><div class="hotel_list">';
    $html .= '<div class="hotel_list_title clearfix" style="cursor: pointer;">';
    $html .= '<div class="title_main"><dl class="sub_main"><dt>' . $val['title'] . '</dt><dd>床型：' . $val['bed'] . '</dd><dd>宽带：' . $val['wifit'] . '</dd></dl></div>';
    $html .= '<div class="title_pic"><img src="' . tomedia($val['thumb']) . '"></div>';
    $html .= '<div class="title_book">';
    $sql = 'SELECT sum(yu_legth) as total FROM ' . tablename($this->order) . ' WHERE uniacid = ' . $this->_weid . ' and hotelid =' . $val['hotelid'] . ' and roomid =' . $val['id'] . ' and order_status !=4 and order_status !=5 and order_status !=6 and sintdate >=' . $sintdate;
    $order_coune = pdo_fetch($sql);
    $order_coune['total'];

    if ($val['score'] <= $order_coune['total']) {
        $html .= ' <span> 抢光了</span>';
    } else {
        $copicr = pdo_fetch('SELECT cprice FROM ' . tablename($this->momy) . 'WHERE uniacid=' . $this->_weid . ' and status=1 and hotelid =' . $val['hotelid'] . ' and roomid =' . $val['id'] . ' ORDER BY cprice ASC ');
        $html .= '<span><label>¥</label>' . $copicr['cprice'] . '</span>起';
    }

    $html .= '<div class="show_off"><span class="arrow"></span></div>';
    $html .= '</div></div>';
    $html .= '<ul class="hotel_list_detail none" style="height: auto;">';
    $momy = pdo_fetchall('SELECT * FROM ' . tablename($this->momy) . 'WHERE uniacid=' . $this->_weid . ' and status=1 and hotelid =' . $val['hotelid'] . ' and roomid =' . $val['id']);

    foreach ($momy as $k => $m) {
        $html .= '<li class="clearfix" room-data="6048796">';
        $html .= '<div class="room-policy"><span class="policy_title">' . $m['motitle'] . '</span><span class="policy_gift"></span><span class="policy_break"></span></div>';
        $html .= '<div class="room_price"><span class="total_price" style="font-size:14px;"><label>¥</label>' . $m['cprice'] . '</span><span class="fan_price">门市价<label>¥</label>' . $m['oprice'] . '</span></div>';
        $html .= '<div class="room_book">';

        if ($val['score'] <= $order_coune['total']) {
            $html .= '<label class="qiamg">抢光了</label>';
        } else {
            $html .= '<a class="yd" ><label  onclick="curt(' . $m['id'] . ');">详情</label></a>';
        }

        $html .= '</div>';
        $html .= '</li>';
    }

    $html .= '</ul></div></div>';
}

echo json_encode($html);
