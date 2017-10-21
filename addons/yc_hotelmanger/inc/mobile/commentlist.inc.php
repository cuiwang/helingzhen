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
$hotel_id = intval($_GPC['hotelid']);
    $sql = 'SELECT * FROM ' . tablename($this->hotelComment) .' where  hotelid='.$hotel_id.' and uniacid=' . $this->_weid ;
    $list = pdo_fetchall($sql);
    include $this->template('commentlist');
