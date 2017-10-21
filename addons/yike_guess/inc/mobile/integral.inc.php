<?php
/**
 * Created by PhpStorm.
 * 积分任务
 * User: yike
 * Date: 2016/6/6
 * Time: 10:57
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$callback = $_GPC['callback'];
$setd = pdo_fetch("SELECT sets FROM " .tablename('yike_guess_sysset'). ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$set = unserialize($setd['sets']);
$complete = pdo_fetch("SELECT complete FROM " .tablename('yike_members'). ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$com = explode(' ',$complete['complete']);
foreach ($set['task'] as $key => $v) {
	
	if (in_array($key,$com)) {
		$set['task']['is_'.$key] = 1;
	} else {
		$set['task']['is_'.$key] = 0;
	}	
};
show_jsonp(1,array('result' => $set),$callback);