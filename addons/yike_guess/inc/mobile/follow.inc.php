<?php
/**
 * Created by PhpStorm.
 * 关注查询
 * User: yike
 * Date: 2016/6/15
 * Time: 15:18
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$set = unserialize($setdata['sets']);
if($set['follow']['open'] == 1){
    $follow = $_W['fans']['follow'];
    $href = $set['follow']['href'];
    show_jsonp(1,array('follow' => $follow, 'href' => $href),$callback);
}else{
    $follow = 1;
    show_jsonp(1,array('follow' => $follow),$callback);
}
