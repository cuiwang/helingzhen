<?php
/**
 * Created by PhpStorm.
 * 签到任务是否开启
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
$set = unserialize($integral['sets']);

// var_dump($set);
$date = array();
$date['on_off']['test'] = $set['on_off']['task'];
$date['on_off']['signin'] = $set['on_off']['signin'];
// var_dump($date);
if (!empty($date)) {
    show_jsonp(1,$date,$callback);
}