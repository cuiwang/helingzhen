<?php
    
    $id = $_GPC['id'];
    $openid = $_W['openid'];
    $uniacid = $_W['uniacid'];
    $sql = "select * from ".tablename('hao_water_setting')." where uniacid=".$_W['uniacid']." "; 
    $setting = pdo_fetch($sql);
    $order = pdo_fetch("SELECT * FROM ".tablename('hao_water_order')." WHERE openid = :openid AND uniacid = :uniacid AND id = :id LIMIT 1", array(':openid' => $openid,':uniacid' => $uniacid,':id' => $id));
    $address = pdo_fetch("SELECT * FROM ".tablename('hao_water_address')." WHERE openid = :openid AND uniacid = :uniacid LIMIT 1", array(':openid' => $openid,':uniacid' => $uniacid));
    $member = pdo_fetch("SELECT * FROM ".tablename('hao_water_member')." WHERE openid = :openid AND uniacid = :uniacid LIMIT 1", array(':openid' => $openid,':uniacid' => $uniacid));
    $order_status = pdo_fetchall("SELECT * FROM ".tablename('hao_water_order_status')." WHERE openid = :openid AND uniacid = :uniacid AND ordersn = :ordersn", array(':openid' => $openid,':uniacid' => $uniacid,':ordersn' => $order['ordersn'] ));

    include $this->template('orderdetail');
   
?>