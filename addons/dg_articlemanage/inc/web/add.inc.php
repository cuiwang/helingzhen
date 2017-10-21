<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/1
 * Time: 10:09
 */
global $_W,$_GPC;
$num=pdo_fetchcolumn("select count(*) from ".tablename('dg_article_author')." where openid=:openid and uniacid=:uniacid",array(":openid"=>$_GPC['openid'],":uniacid"=>$_GPC['uniacid']));
if(empty($num)){
    $insert=array(
        'uniacid'=>$_GPC['uniacid'],
        'openid'=>$_GPC['openid'],
        'nickname'=>$_GPC['nickname'],
        'avatar'=>$_GPC['avatar'],
        'createtime'=>TIMESTAMP
    );
    $result=pdo_insert('dg_article_author',$insert);
    $id=pdo_insertid();
}
header("Content-type:application/json");
$res=array();
$res['success']=0;
if(!empty($result)){
    $user=pdo_fetch("select * from ".tablename('dg_article_author')." where openid=:openid",array(":openid"=>$_GPC['openid']));
    $res=$user;
    if(empty($res['money'])){
        $res['money']=0;
    }
    $res['createtime']=date("Y/m/d H:i:s",$res['createtime']);
    $res['success']=1;
}
echo json_encode($res);


