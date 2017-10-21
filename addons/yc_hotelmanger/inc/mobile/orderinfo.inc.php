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
$hotel = $_GPC['hotel'];
if($hotel){
    $sql = 'SELECT o.*, h.* FROM ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h on o.hotelid = h.id where  o.uniacid=' . $this->_weid .'  and o.order_id = ' . $order_id;
}else{
    $sql = 'SELECT o.*, h.* FROM ' . tablename($this->order) . ' o left join ' . tablename($this->hotel) . ' h on o.hotelid = h.id where  o.uniacid=' . $this->_weid . ' and o.openid=\'' . $this->openid . '\' and o.order_id = ' . $order_id;
    $comment = pdo_fetch( 'SELECT * FROM ' . tablename($this->hotelComment) . ' where  uniacid=' . $this->_weid . ' and orderid = ' . $order_id);
}
$info = pdo_fetch($sql);
include $this->template('orderinfo');
