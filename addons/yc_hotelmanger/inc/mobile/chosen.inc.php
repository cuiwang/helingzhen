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

$level = $this->hotel_level;



 $list = pdo_fetchall('SELECT * FROM ' . tablename($this->room) . 'WHERE  
 status = 1 
 and uniacid =' . $this->_weid.' 
 and ischosen=1 order by id desc
 ');
 include $this->template('chosen');
 exit;



