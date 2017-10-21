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


$id = $_GPC['hotelid'];
$hotel = pdo_fetch('SELECT title,thumb,thumbs FROM ' . tablename($this->hotel) . 'WHERE  status = 1 and uniacid =' . $this->_weid . ' and id =' . $id);
$thumbs = iunserializer($hotel['thumbs']);
include $this->template('thumb');
