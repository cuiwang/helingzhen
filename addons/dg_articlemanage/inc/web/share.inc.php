<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/24
 * Time: 14:27
 */
global $_W,$_GPC;
load()->func("tpl");
$uniacid=$_W['uniacid'];
$share=pdo_fetch("select * from ".tablename("dg_article_share")."where uniacid=:uniacid",array(":uniacid"=>$uniacid));

if(checksubmit()){
    empty($_GPC['share_title']) && message("分享标题没有设置");
    empty($_GPC['share_img']) && message("分享照片没有设置");
    empty($_GPC['share_desc']) && message("分享描述没有设置");
    $data=array(
        "uniacid"=>$uniacid,
        "sharetitle"=>$_GPC['share_title'],
        "shareimg"=>$_GPC['share_img'],
        "sharedesc"=>$_GPC['share_desc']
    );
    if(empty($share)){
        pdo_insert("dg_article_share",$data);
    }else{
        pdo_update("dg_article_share",$data,array("id"=>$share['id']));
    }
    message("设置成功","","success");
}
include $this->template("share");
