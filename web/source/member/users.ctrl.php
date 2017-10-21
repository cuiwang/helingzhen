<?php
/**
 * [Weizan System] Copyright (c) 2014 WEIZANCMS.COM
 
 */
defined('IN_IA') or exit('Access Denied');
include_once 'common/common.inc.php';
global $_W,$_GPC;
if(!$_W['isfounder']) {
    message('不能访问, 需要创始人权限才能访问.');
}
$dos = array('xf', 'cz');
$do = in_array($do, $dos) ? $do : 'display';
if($do == "xf") {
    $uid = $_GPC["uid"];
    if(intval($uid) <= 0) message("用户ID不存在.");
    $sql = 'SELECT * FROM ' . tablename('users') . " WHERE uid=:uid LIMIT 1";
    $user = pdo_fetch($sql, array(":uid"=>$uid));
    if(empty($user)) message("用户不存在");
    list($list,$pager) = getAllRecords("users_credits_record",array("credittype"=>array("op"=>"=","val"=>"credit2")),$uid);
    include $this->template('member/users');
    exit;
}elseif($do == "cz") {
    $uid = $_GPC["uid"];
    if(intval($uid) <= 0) message("用户ID不存在.");
    $sql = 'SELECT * FROM ' . tablename('users') . " WHERE uid=:uid LIMIT 1";
    $user = pdo_fetch($sql, array(":uid"=>$uid));
    if(empty($user)) message("用户不存在");
    $index = max(1, intval($_GPC['page']));
    $size = 15;
    $page = ($index - 1) * $size;
    $list = pdo_fetchall("SELECT * FROM ".tablename("uni_payorder")." WHERE uid=:uid ORDER BY order_time DESC LIMIT $page,$size",array(":uid"=>$uid));
    $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename("uni_payorder")." WHERE uid=:uid", array(":uid"=>$uid));
    $pager = pagination($total, $index, $size);
    include $this->template('member/users');
    exit;
}
if($_W["ispost"] && $_W["isajax"]) {
    try{
        $error = array("code"=>0,"message"=>"uid为空");
        if(empty($_GPC["uid"]) || intval($_GPC["uid"]) <= 0) die(json_encode($error));
        $error = array("code"=>0,"message"=>"交易币为空");
        if(empty($_GPC["credit2"])) die(json_encode($error));
        $remark = "线下付款，管理员手工充值";
        if(doubleval($_GPC["credit2"]) <= 0) $remark = "管理员扣除";
        $result = user_credits_update(intval($_GPC["uid"]),"credit2",doubleval($_GPC["credit2"]),array($_W["uid"], $remark));
        if($result) {
            if(doubleval($_GPC["credit2"]) > 0) {
                pdo_insert("uni_payorder",array("orderid"=>date("YmdHis").sprintf ( '%06d', rand(0, 999999)),"status"=>1,"pay_time"=>TIMESTAMP,"money"=>doubleval($_GPC["credit2"]),"order_time"=>TIMESTAMP,"uid"=>$_GPC["uid"],"credittype"=>"credit2"));
            }
            die(json_encode(array("code"=>1,"message"=>"操作成功")));
        }
        die(json_encode(array("code"=>0,"message"=>$result["message"])));
    }catch (Exception $e){
        die(json_encode(array("code"=>0,"message"=>$e->getMessage())));
    }
    die(json_encode(array("code"=>0,"message"=>"操作失败")));
}
$index = max(1, intval($_GPC['page']));
$size = 15;
$page = ($index - 1) * $size;
$where="";
if(!empty($_GPC["search"]) && !empty($_GPC["search"])) {
    $search = $_GPC["search"];
    if(!empty($search["username"])) {
        $username = $search["username"];
        $where = "WHERE username='$username'";
    }
}

$list = pdo_fetchall("SELECT * FROM ".tablename("users")." $where LIMIT $page,$size");
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename("users")." $where");
$pager = pagination($total, $index, $size);
template('member/users');