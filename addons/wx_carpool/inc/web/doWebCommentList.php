<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/6 0006
 * Time: 下午 1:31
 */

require_once(dirname(__FILE__) . "/../../model/comment.php");

$orderCount = commentModel::getListCount();

$orderList = commentModel::getOrderListByPage();

$pages=intval($orderCount/10)+1;

$p=1;
if(isset($_GPC['p'])){

    if(intval($_GPC['p'])>0){
        $p=intval($_GPC['p']);
    }
}

include $this->template('CommentList');

