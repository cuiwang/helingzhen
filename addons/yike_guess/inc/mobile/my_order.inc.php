<?php
/**
 * Created by PhpStorm.
 * 订单
 * User: yike
 * Date: 2016/6/6
 * Time: 13:50
 */
global $_W, $_GPC;
$callback = $_GPC['callback'];
if($_GPC['op'] == 'list'){
    $order = pdo_fetchall('select o.id as order_id, o.bet, g.* from '.tablename('yike_guess_order').' o left join '.tablename('yike_guess_guess').' g on g.id = o.guess_id where o.user_id = :user_id and g.is_open = 0 order by id desc', array(':user_id' => $_W['member']['uid']));
    foreach($order as $k => $v){
        if($v['play_id'] == 2){
            $contest = unserialize($v['contest']);
            foreach($contest as $k1 => $v1){
                if($v['bet'] == $k1){
                    $order[$k]['bet'] = $v1['name'];
                }
            }
        }
    }
    show_jsonp(1,array('order' => $order),$callback);
}elseif($_GPC['op'] == 'open_list'){
    $order = pdo_fetchall('select o.id as order_id, o.bet, g.* from '.tablename('yike_guess_order').' o left join '.tablename('yike_guess_guess').' g on g.id = o.guess_id where o.user_id = :user_id and g.is_open = 1 order by id desc', array(':user_id' => $_W['member']['uid']));
    foreach($order as $k => $v){
        if($v['play_id'] == 2){
            $contest = unserialize($v['contest']);
            foreach($contest as $k1 => $v1){
                if($v['bet'] == $k1){
                    $order[$k]['bet'] = $v1['name'];
                }
            }
        }
    }
    show_jsonp(1,array('order' => $order),$callback);
}elseif($_GPC['op'] == 'order_details'){
    $order = pdo_fetch('select * from '.tablename('yike_guess_order').' where id = :id', array(':id' => $_GPC['order_id']));
    $guess = pdo_fetch('select * from '.tablename('yike_guess_guess').' where id = :id', array(':id' => $order['guess_id']));
    if($guess['play_id'] == 2){
        $contest = unserialize($guess['contest']);
        foreach($contest as $k => $v){
            if($order['bet'] == $k){
                $order['bet'] = $v['name'];
                $order['odds'] = $v['odds'];
            }
        }
    }
    show_jsonp(1,array('order' => $order, 'guess' => $guess),$callback);
}