<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */
defined('IN_IA') or exit('Access Denied');
include_once 'common/common.inc.php';
global $_W,$_GPC;
$username=trim($_GPC['username']);
$user=pdo_get('users',array('username'=>$username));
$user['level']=pdo_getcolumn('users_group',array('id'=>$user['groupid']),'name');
		//这个操作被定义用来呈现 管理中心导航菜单
        if($_W['ispost'] && $_W['isajax']) {
            die(json_encode(array("code"=>1, "message"=>"操作成功.")));
        }
        $settings = get_settings();
        $service = explode("|",$settings["service_qqs"]);
        $qqs = array();
        foreach($service as $ser) {
            list($name,$qq) = explode("-",$ser);
            $qqs[] = array(
                "name"=>$name,
                "qq"=>$qq
            );
        }
		template('member/payhost');
