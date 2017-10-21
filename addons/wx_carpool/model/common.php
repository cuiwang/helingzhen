<?php
/**
 * Created by PhpStorm.
 * User: start
 * Date: 2016/12/25
 * Time: 16:27
 */

require_once(dirname(__FILE__) . "/user.php");


class commonModel{
    /**
     * 公共入口
     */
    static function entry(){
        self::autoRegUser();
    }

    /**
     * 自动注册用户
     */
    static function autoRegUser(){
        global $_W,$_GPC;
        $openid=$_W['openid'];
        if(trim($openid)==""){
            return;
        }
        $user=userModel::getUserByOpenId($openid);
        if(!$user){
            /*未查找到用户,创建新用户*/
            userModel::createUser();
        }

    }

}