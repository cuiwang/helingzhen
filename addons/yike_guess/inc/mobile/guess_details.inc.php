<?php
/**
 * Created by PhpStorm.
 * 竞猜详情
 * User: yike
 * Date: 2016/6/6
 * Time: 10:45
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$callback = $_GPC['callback'];
$user = pdo_fetch('select * from '.tablename('mc_members').' where uid = :uid', array(':uid' => $_W['member']['uid']));
$guess = pdo_fetch('select * from '.tablename('yike_guess_guess').' where id = :id', array(':id' => $_GPC['id']));
if($guess['play_id'] == 1 || empty($guess['play_id'])){
    $guess['home_image'] = tomedia($guess['home_image']);
    $guess['guest_iamge'] = tomedia($guess['guest_iamge']);
    $guess['image'] = tomedia($guess['image']);
    $win_num = pdo_fetchcolumn('select count(*) from '.tablename('yike_guess_order').' where guess_id = :guess_id and bet = 1', array(':guess_id' => $guess['id']));
    $flat_num = pdo_fetchcolumn('select count(*) from '.tablename('yike_guess_order').' where guess_id = :guess_id and bet = 2', array(':guess_id' => $guess['id']));
    $transport_num = pdo_fetchcolumn('select count(*) from '.tablename('yike_guess_order').' where guess_id = :guess_id and bet = 3', array(':guess_id' => $guess['id']));
    $all_num = $win_num + $flat_num + $transport_num;
    $win = array(
        'num' => $win_num,
        'proportion' => $win_num/$all_num
    );
    $flat = array(
        'num' => $flat_num,
        'proportion' => $flat_num/$all_num
    );
    $transport = array(
        'num' => $transport_num,
        'proportion' => $transport_num/$all_num
    );
    $setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid']
    ));
    $set = unserialize($setdata['sets']);
    $bet_name = $set['bet_name']['bet_name'];
    show_jsonp(1,array('bet_name' => $bet_name ,'guess' => $guess, 'all_num' => $all_num, 'win' => $win, 'flat' => $flat, 'transport' => $transport, '$user' => $user),$callback);
}elseif($guess['play_id'] == 2){
    $guess['contest'] = unserialize($guess['contest']);
    $guess['image'] = tomedia($guess['image']);
    $all_num =  pdo_fetchcolumn('select count(*) from '.tablename('yike_guess_order').' where guess_id = :guess_id', array(':guess_id' => $guess['id']));
    foreach($guess['contest'] as $k => $v){
        $guess['contest'][$k]['id'] = $k;
        $guess['contest'][$k]['image'] = tomedia($v['image']);
        $num = pdo_fetchcolumn('select count(*) from '.tablename('yike_guess_order').' where guess_id = :guess_id and bet = :bet', array(':guess_id' => $guess['id'], ':bet' => $k));
        $guess['contest'][$k]['zcl'] =  $num/$all_num;
    }
    for($h = 0 ;$h < count($guess['contest'])-1 ;$h ++){
        for($i = 0;$i < count($guess['contest'])-$h-1 ;$i ++){
            if($guess['contest'][$i]['odds']>$guess['contest'][$i+1]['odds']){
                $kong = $guess['contest'][$i+1];
                $guess['contest'][$i+1] = $guess['contest'][$i];
                $guess['contest'][$i] = $kong;
            }
        }
    }
    $setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid']
    ));
    $set = unserialize($setdata['sets']);
    $bet_name = $set['bet_name']['bet_name'];
    show_jsonp(1,array('guess' => $guess, 'all_num' => $all_num, '$user' => $user ,'bet_name' => $bet_name),$callback);
}


