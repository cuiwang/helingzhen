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


$hotelid = $_GPC['hotelid'];
$hotellist = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . ' WHERE  uniacid = ' . $this->_weid . ' ORDER BY id DESC');
$where = '';
if ($hotelid != 0) {
    $where = " hotelid = " . $hotelid . " and ";
}
if ($hotellist) {
    $uniacid = $this->_weid;
    $pindex = max(1, intval($_GPC['page']));
    $psize = $this->psize;
    $list = pdo_fetchall('SELECT * FROM ' . tablename($this->room) . ' WHERE ' . $where . '  uniacid = ' . $uniacid . '  ORDER BY id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->room) . ' WHERE uniacid = ' . $uniacid);
    $pager = pagination($total, $pindex, $psize);
}


include $this->template($this->temp_url . 'room');
