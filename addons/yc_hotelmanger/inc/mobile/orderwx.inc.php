<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $_GPC;
global $_W;

 load()->model("mc"); 
    $uid= mc_openid2uid($this->openid);
     if(!$uid){
        $uid=$_W['fans']['uid'];
    }   
    $userjf= pdo_fetch('SELECT uid,credit3 FROM ' . tablename($this->members) . ' WHERE uniacid = ' . $this->_weid . ' and uid= '.$uid);
    $levelt=pdo_fetchall('SELECT levelname,ordercount,discount FROM ' . tablename($this->mlevel) . ' WHERE uniacid = ' . $this->_weid .' ORDER BY ordercount DESC'); 
    $zk='';
    foreach ($levelt as $key => $level) {
        if($level['ordercount']>$userjf['credit3']){
            continue;
        }else{
            $zk= $level['discount'];
            break;
        }
    } 
    $zk=$zk==0?1:$zk;
if (!$this->is_weixin()) {
    message('请在微信中打开');
}
load()->model("activity");
$order_on = $_GPC['order_on'];
$orderlist = pdo_fetch('SELECT * FROM ' . tablename($this->order) . 'WHERE  uniacid =' . $this->_weid . ' and openid =\'' . $this->openid . '\' and order_on =' . $order_on);

if (!$orderlist) {
    message('订单支付链接错误，无法查找此订单!', '', 'error');
}


if ($orderlist['order_status']) {
    message('订单已付款!', '', 'error');
}
$fee = floatval($orderlist['totalcpice']*$zk);

if ($fee <= 0) {
    message('支付错误, 金额小于0');
}
$params['tid'] = $orderlist['order_on'];
$params['user'] = $_W['fans']['from_user'];
$params['fee'] = $fee;
$params['title'] = $orderlist['goods_name'];
$params['ordersn'] = $orderlist['order_on'];
$params['virtual'] = true; 
include $this->template('orderwx');
