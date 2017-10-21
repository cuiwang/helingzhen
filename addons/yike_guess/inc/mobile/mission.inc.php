<?php
/**
 * Created by PhpStorm.
 * 任务列表
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
$user = pdo_fetch('select * from '.tablename('yike_members').'  where uid = :uid', array(':uid' => $_W['member']['uid']));
// 转化数组
$ok_task = unserialize($user['ok_task']);
$date = array();
foreach ($sets['task']['integral'] as $key => $value) {
    if($key == open){
        $date[$key]['name'] = '首次使用';
        $date[$key]['task'] = $value;
        if($user['is_one'] == 1){
            $date[$key]['ok'] = 1;
        }else{
            $date[$key]['ok'] = 0;
        }
    }
    if ($key == one) {
        $date[$key]['name'] = '当日第一次分享';
        $date[$key]['task'] = $value;
        if($ok_task['task']['one'] == 1){
            $date[$key]['ok'] = 1;
        }else{
            $date[$key]['ok'] = 0;
        }
    }
    if ($key == two) {
        $date[$key]['name'] = '当日第二次分享';
        $date[$key]['task'] = $value;
        if($ok_task['task']['two'] == 1){
            $date[$key]['ok'] = 1;
        }else{
            $date[$key]['ok'] = 0;
        }
    }
    if ($key == three) {
        $date[$key]['name'] = '当日第三次分享';
        $date[$key]['task'] = $value;
        if($ok_task['task']['three'] == 1){
            $date[$key]['ok'] = 1;
        }else{
            $date[$key]['ok'] = 0;
        }
    }
    if ($key == ones) {
        $date[$key]['name'] = '当日第一次竞猜';
        $date[$key]['task'] = $value;
        if($ok_task['task']['ones'] == 1){
            $date[$key]['ok'] = 1;
        }else{
            $date[$key]['ok'] = 0;
        }
    } 
}
    if (!empty($date)) {
        show_jsonp(1,$date,$callback);
    }