<?php

   $id = $_GPC['id'];
   $shop = pdo_fetch("SELECT * FROM ".tablename('hao_water_goods')." WHERE id=:id LIMIT 1",array('id'=>$id));
   
   include $this->template('shopdetail');
?>