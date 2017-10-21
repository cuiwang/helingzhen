<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;
$order_id = $_POST['order_id'];
$seearr = $this->seearr;
$data['order_status'] = 5;

if (pdo_update($this->order, $data, array('order_id' => $order_id))) {
    if ($seearr['istplnotice'] == 1) {
        $this->get_hotelutud($this->seearr, $order_id);
        $this->get_hotelroomtui($this->seearr, $order_id);
    }
    $tishi = '预定取消成功！';
} else {
    $tishi = '预定取消失败！';
}
echo json_encode($tishi);
