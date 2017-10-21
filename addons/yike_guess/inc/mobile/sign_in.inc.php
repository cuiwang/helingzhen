<?php
/**
 * Created by PhpStorm.
 * 签到信息
 * User: yike
 * Date: 2016/6/6
 * Time: 11:28
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$callback = $_GPC['callback'];
// 查询
$integral = pdo_fetch('select * from '.tablename('yike_guess_sysset'));
// 转化数组
$sets = unserialize($integral['sets']);
$date = array();
foreach ($sets['sets']['report'] as $key => $value) {
    if($key == report1){
        $date[$key]['name'] = '每日签到';
        $date[$key]['sets'] = $value;
    }
    if ($key == report2) {
        $date[$key]['name'] = '连续签到3天';
        $date[$key]['sets'] = $value;
    }
    if ($key == report3) {
        $date[$key]['name'] = '连续签到7天';
        $date[$key]['sets'] = $value;
    }
    if ($key == report4) {
        $date[$key]['name'] = '连续签到10天';
        $date[$key]['sets'] = $value;
    }
    if ($key == report5) {
        $date[$key]['name'] = '连续签到20天';
        $date[$key]['sets'] = $value;
    }  
}
// var_dump($date);
$setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$set = unserialize($setdata['sets']);
$bet_name = $set['bet_name']['bet_name'];
    if (!empty($date)) {
        show_jsonp(1,array('$date' => $date ,'bet_name' =>$bet_name),$callback);
    }