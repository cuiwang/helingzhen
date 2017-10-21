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
$order_id = intval($_GPC['order_id']);
$hotel_id = intval($_GPC['hotel_id']);
if(empty($hotel_id)){
    $sql = 'SELECT o.*, h.* FROM ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h on o.hotelid = h.id where  o.uniacid=' . $this->_weid . ' and o.openid=\'' . $this->openid . '\' and o.order_id = ' . $order_id;
    $info = pdo_fetch($sql);
    include $this->template('ordercomment');
}else{

    $data = array(
        'uniacid'    => $this->_weid,
        'hotelid'  => $hotel_id,
        'orderid'     => $order_id,
        'point'=>$_GPC['point'],
        'content'=>$_GPC['comment']
    );
    pdo_insert($this->hotelComment, $data);
    $data1 = array(
        'commentstatus'  => 1
    );

    pdo_update($this->order, $data1, array('uniacid' => $this->_weid,'order_id'=>$order_id));

    $url =$this->createMobileUrl('orderinfo',array('order_id'=> $order_id));
    Header("Location:$url");
    exit;
}
