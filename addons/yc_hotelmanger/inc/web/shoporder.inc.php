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
$id = intval($_GPC['id']);
$where = '';
$params = array();
$where .= ' uniacid = ' . $this->_weid;
        
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
    } 
} 

$pindex = max(1, intval($_GPC['page']));
$psize = $this->psize;
$shoporder = pdo_fetchall('SELECT * FROM ' . tablename($this->shoporder) . ' WHERE ' . $where . '  ORDER BY id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

$total = pdo_fetchcolumn('SELECT COUNT(*)  FROM ' . tablename($this->shoporder) . ' WHERE' . $where, $params);
$pager = pagination($total, $pindex, $psize);

include $this->template('shoporder');
