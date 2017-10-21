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


echo $order_id = $_GPC['order_id'];
$delete = pdo_delete($this->order, array('order_id' => $order_id, 'uniacid' => $this->_weid));

if ($delete) {
    message('删除成功！', referer(), 'success');
    return;
}


message("抱歉，操作数据失败！", '', "error");
