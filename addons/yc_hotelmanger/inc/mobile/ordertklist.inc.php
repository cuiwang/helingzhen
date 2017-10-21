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
$orderlist = pdo_fetchall('SELECT * FROM ' . tablename($this->order) . 'WHERE uniacid=' . $this->_weid . '  and openid=\'' . $this->openid . '\' and order_status = 6  ORDER BY ordertime DESC');
$orderjie = pdo_fetchall('SELECT * FROM ' . tablename($this->order) . 'WHERE uniacid=' . $this->_weid . ' and openid=\'' . $this->openid . '\' and order_status = 7  ORDER BY ordertime DESC ');
include $this->template('ordertklist');
