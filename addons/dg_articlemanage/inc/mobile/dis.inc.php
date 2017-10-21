<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/18
 * Time: 11:38
 */
global $_W,$_GPC;
$id=$_GPC['aid'];
$discuss=$_GPC['discuss'];
$openid=$_W['openid'];
$nickname=$_W['fans']['tag']['nickname'];
$avatar=$_W['fans']['tag']['avatar'];
$uniacid=$_W['uniacid'];
header("Content-type:application/json");
$data=array(
    'uniacid'=>$uniacid,
    'aritcle_id'=>$id,
    'openid'=>$openid,
    'nickname'=>$nickname,
    'avatar'=>$avatar,
    'discuss'=>$discuss,
    'createtime'=>TIMESTAMP
);
if(empty($discuss)){
    $res=array(
        'msg'=>'评论不能为空！'
    );
    echo json_encode($res);
    exit;
}
$result=pdo_insert('dg_article_dis',$data);
if($result){
    $res=array(
        'msg'=>'留言成功'
    );
    echo json_encode($res);
}