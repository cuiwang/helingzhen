<?php
/**
 * Created by PhpStorm.
 * 积分明细
 * User: yike
 * Date: 2016/6/6
 * Time: 15:44
 */
global $_W, $_GPC;
$callback = $_GPC['callback'];
$list = pdo_fetchall('select * from '.tablename('yike_guess_balance').' where uid = :id order by id desc', array(':id' => $_W['member']['uid']));
$setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$set = unserialize($setdata['sets']);
$bet_name = $set['bet_name']['bet_name'];
if($list){
    show_jsonp(1,array('list' => $list , 'bet_name' => $bet_name),$callback);
}else{
    show_jsonp(0,array('list' => '暂无明细' , 'bet_name' => $bet_name),$callback);
}
