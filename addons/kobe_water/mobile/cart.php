<?php

    $uniacid = $_W['uniacid'];
    $fans = $_W['fans'];
    if(empty($fans['nickname'])){
    	load()->model('mc');
    	$fans = mc_oauth_userinfo();
    }
    $sql = "select * from ".tablename('hao_water_setting')." where uniacid=".$_W['uniacid']." "; 
    $setting = pdo_fetch($sql);
    $openid = $fans['openid'];
    $member = pdo_fetch("SELECT * FROM ".tablename('hao_water_member')." WHERE openid = :openid AND uniacid = :uniacid LIMIT 1", array(':openid' => $openid,':uniacid' => $uniacid));
    if(!empty($member)){
       $carts = pdo_fetchall("SELECT * FROM ".tablename('hao_water_cart')." WHERE openid = :openid AND uniacid = :uniacid", array(':openid' => $openid,':uniacid' => $uniacid));
       $count = pdo_fetchall("SELECT sum(shop_count) as count FROM ".tablename('hao_water_cart')." WHERE openid = :openid AND uniacid = :uniacid", array(':openid' => $openid,':uniacid' => $uniacid));
       include $this->template('cart');
    }else{
        include $this->template('register');
    }

?>