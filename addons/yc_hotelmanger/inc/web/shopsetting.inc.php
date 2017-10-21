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
$list = pdo_fetchall('SELECT * FROM ' . tablename($this->shop) . ' WHERE uniacid = ' . $uniacid . ' ORDER BY goods_sort desc, id DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->shop) . ' WHERE uniacid = ' . $uniacid);
$pager = pagination($total, $pindex, $psize); 

if($_GPC['op']=='del'){
    
    $id=intval($_GPC['id']);
    $re=pdo_delete($this->shop, array('id' => $id, 'uniacid' => $this->_weid));
    if ($re) {
		 message("删除成功！", referer(), "success");
    }
}
include $this->template('shoplist');
