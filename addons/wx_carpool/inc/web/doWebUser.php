<?php
/**
 * Created by PhpStorm.
 * User: start
 * Date: 2016/12/25
 * Time: 17:09
 */

/**
 * 用户管理
 */
require_once(dirname(__FILE__) . "/../../model/user.php");
global $_GPC,$_W;

$userList=userModel::getUserList();
$userCount=userModel::getUserCount();
$pages=intval($userCount/10)+1;
/*获得分页信息*/
$p=1;
if(isset($_GPC['p'])){
    if(intval($_GPC['p'])>0){
        $p=intval($_GPC['p']);
    }
}
include $this->template('user');