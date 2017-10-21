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


$order_id = $_GPC['order_id'];
$list = pdo_fetch('SELECT * FROM ' . tablename($this->order) . ' WHERE uniacid = ' . $this->_weid . ' and order_id =' . $order_id);

if (!$list) {
    message('该订单信息不存在！', $this->createWebUrl('orderlist'), 'error');
}


load()->model("mc");
$result = mc_fansinfo($list['openid'], $_W['acid'], $_W['uniacid']);
$list['nickname'] = $result['nickname'];
include $this->template('orderinfo');
