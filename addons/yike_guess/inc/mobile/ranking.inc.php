<?php
/**
 * Created by PhpStorm.
 * 积分排行榜
 * User: yike
 * Date: 2016/6/8
 * Time: 17:32
 */
global $_W, $_GPC;
$callback = $_GPC['callback'];
if($_GPC['op'] == 'all'){
    $list = pdo_fetchall('select * from '.tablename('mc_members').' where uniacid = :uniacid order by credit1 desc limit 0,20', array(':uniacid' => $_W['uniacid']));
    $setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid']
    ));
    $set = unserialize($setdata['sets']);
    $bet_name = $set['bet_name']['bet_name'];
    if($list){
        show_jsonp(1,array('list' => $list ,'bet_name' => $bet_name),$callback);
    }else{
        show_jsonp(1,array('bet_name' => $bet_name ,'list' => '暂无排行'),$callback);
    }
}elseif($_GPC['op'] == 'month'){
    $list = pdo_fetchall('select y.add_money ,m.* from '.tablename('yike_members').' y left join '.tablename('mc_members').' m on m.uid = y.uid where y.uniacid = :uniacid order by y.add_money desc limit 0,20', array(':uniacid' => $_W['uniacid']));
    $setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid']
    ));
    $set = unserialize($setdata['sets']);
    $bet_name = $set['bet_name']['bet_name'];
    if($list){
        show_jsonp(1,array('bet_name' => $bet_name ,'list' => $list),$callback);
    }else{
        show_jsonp(1,array('bet_name' => $bet_name ,'list' => '暂无排行'),$callback);
    }
}
