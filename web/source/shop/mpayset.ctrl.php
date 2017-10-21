<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
if(!$_W['isfounder']) {
    message('不能访问, 需要创始人权限才能访问.');
}
function get_settings()
{
$value = pdo_fetchcolumn("SELECT value FROM ".tablename("core_settings")." WHERE `key`=:key",array(":key"=>"member"));
if(empty($value)) 
{
return array();
}
return @iunserializer($value);
}
$item = get_settings();

if($_W["ispost"] && checksubmit()) {
    $save_db["value"] = @iserializer($_GPC["save"]);
    if(empty($item)) {
        $save_db["key"] = "member";
        pdo_insert("core_settings",$save_db);
    }else{
        pdo_update("core_settings",$save_db, array("key"=>"member"));
    }
    $packages = $_GPC["packages"];
    foreach($packages as $k=>$v){
        pdo_update("uni_group",array("price"=>$v["price"],"hide"=>$v["hide"]),array("id"=>$k));
    }
    $groups = $_GPC["groups"];
    foreach($groups as $k=>$v){
        pdo_update("users_group",array("discount"=>$v["discount"]),array("id"=>$k));
    }
    message("操作成功",url("shop/mpayset/payset"));
}
if(!empty($item)){
    $save = $item;
}elseif(empty($item) && !empty($_GPC["save"])) {
    $save = $_GPC["save"];
}else{
    $save = array(
        "dx_UnitPrice"=>20,
        "tx_date"=>7
    );
}
$groups = pdo_fetchall("SELECT id, name, discount FROM ".tablename('users_group')." ORDER BY id ASC");
        template('shop/mpay');