<?php
/**
 * Created by PhpStorm.
 * 分享任务
 * User: yike
 * Date: 2016/6/13
 * Time: 15:14
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$callback = $_GPC['callback'];
$last_share = pdo_fetch('select * from '.tablename('yike_members').' where uid = :uid', array(':uid' => $_W['member']['uid']));
$setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$user = pdo_fetch('select * from '.tablename('mc_members').' where uid = :uid', array(':uid' => $_W['member']['uid']));
$set = unserialize($setdata['sets']);
$ok_task = unserialize($last_share['ok_task']);
if (date('Y-m-d', $last_share['share_time']) == date('Y-m-d', time())) {
    $share_num = $last_share['share_num'] + 1;
}else{
    $share_num = 1;
}
if($share_num < 4){
    if($share_num == 1){
        if(!empty($set['task']['integral']['one'])){
            $money = $user['credit1'] + $set['task']['integral']['one'];
            $add_money = $set['task']['integral']['one'];
            $ok_task['task']['one'] = 1;
            if(date('Y-m-d', time()) == ((date('Y-m', time())).'-1')){
                $add_money1 = $set['task']['integral']['one'];
            }else{
                $user1 = pdo_fetch(' SELECT * FROM '.tablename('yike_members').' WHERE uid = :uid',array(':uid' => $_W['member']['uid']));
                $add_money1 = $user1['add_money'] + $set['task']['integral']['one'];
            }
            $data1 = array(
                'credit1' => $money
            );
            pdo_update('mc_members', $data1, array(
                'uid' => $user['uid']
            ));
            $data2 = array(
                'uid' => $user['uid'],
                'uniacid' => $_W['uniacid'],
                'money' => $add_money,
                'type' => 1,
                'balance' => $money,
                'create_time' => time(),
                'name' => '分享'
            );
            pdo_insert('yike_guess_balance', $data2);
            $data3 = array(
                'share_num' => $share_num,
                'share_time' => time(),
                'ok_task' => serialize($ok_task),
                'add_money' => $add_money1
            );
            pdo_update('yike_members', $data3, array(
                'uid' => $user['uid']
            ));
        }

    }elseif($share_num == 2){
        if(!empty($set['task']['integral']['two'])){
            $money = $user['credit1'] + $set['task']['integral']['two'];
            $add_money = $set['task']['integral']['two'];
            $ok_task['task']['two'] = 1;
            if(date('Y-m-d', time()) == ((date('Y-m', time())).'-1')){
                $add_money1 = $set['task']['integral']['two'];
            }else{
                $user1 = pdo_fetch(' SELECT * FROM '.tablename('yike_members').' WHERE uid = :uid',array(':uid' => $_W['member']['uid']));
                $add_money1 = $user1['add_money'] + $set['task']['integral']['two'];
            }
            $data1 = array(
                'credit1' => $money
            );
            pdo_update('mc_members', $data1, array(
                'uid' => $user['uid']
            ));
            $data2 = array(
                'uid' => $user['uid'],
                'uniacid' => $_W['uniacid'],
                'money' => $add_money,
                'type' => 1,
                'balance' => $money,
                'create_time' => time(),
                'name' => '分享'
            );
            pdo_insert('yike_guess_balance', $data2);
            $data3 = array(
                'share_num' => $share_num,
                'share_time' => time(),
                'ok_task' => serialize($ok_task),
                'add_money' => $add_money1
            );
            pdo_update('yike_members', $data3, array(
                'uid' => $user['uid']
            ));
        }
    }elseif($share_num == 3){
        if(!empty($set['task']['integral']['three'])){
            $money = $user['credit1'] + $set['task']['integral']['three'];
            $add_money = $set['task']['integral']['three'];
            $ok_task['task']['three'] = 1;
            if(date('Y-m-d', time()) == ((date('Y-m', time())).'-1')){
                $add_money1 = $set['task']['integral']['three'];
            }else{
                $user1 = pdo_fetch(' SELECT * FROM '.tablename('yike_members').' WHERE uid = :uid',array(':uid' => $_W['member']['uid']));
                $add_money1 = $user1['add_money'] + $set['task']['integral']['three'];
            }
            $data1 = array(
                'credit1' => $money
            );
            pdo_update('mc_members', $data1, array(
                'uid' => $user['uid']
            ));
            $data2 = array(
                'uid' => $user['uid'],
                'uniacid' => $_W['uniacid'],
                'money' => $add_money,
                'type' => 1,
                'balance' => $money,
                'create_time' => time(),
                'name' => '分享'
            );
            pdo_insert('yike_guess_balance', $data2);
            $data3 = array(
                'share_num' => $share_num,
                'share_time' => time(),
                'ok_task' => serialize($ok_task),
                'add_money' => $add_money1
            );
            pdo_update('yike_members', $data3, array(
                'uid' => $user['uid']
            ));
        }
    }
    show_jsonp(1,array('result' => '分享成功'),$callback);
}
