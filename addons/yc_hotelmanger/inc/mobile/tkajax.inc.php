<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W; 
$order_id = $_GPC['order_id'];
$seearr = $this->seearr;
$data['order_status'] = 6;

if (pdo_update($this->order, $data, array('order_id' => $order_id))) {
    if ($seearr['istplnotice'] == 1) {
        $this->get_hotelroomtui($this->seearr, $order_id, 2);
        $this->get_hoteluserkk($this->seearr, $order_id);
    }
    $tishi = '申请成功！请等待审核';
} else {
    $tishi = '申请失败！';
}

echo json_encode($tishi);
