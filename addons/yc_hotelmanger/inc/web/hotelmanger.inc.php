<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;
$uniacid = $this->_weid;
$pindex = max(1, intval($_GPC['page']));
$psize = $this->psize;
$list = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . ' WHERE uniacid = ' . $uniacid . ' ORDER BY listorder desc, id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->hotel) . ' WHERE uniacid = ' . $uniacid);
$pager = pagination($total, $pindex, $psize);
include $this->template($this->temp_url . 'lList');
