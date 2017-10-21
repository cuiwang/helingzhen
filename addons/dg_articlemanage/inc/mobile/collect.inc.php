<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/20
 * Time: 12:58
 */
global $_W,$_GPC;
load()->func('tpl');
$id=$_GPC['id'];
$openid=$_W['openid'];
$uniacid=$_W['uniacid'];
$op=!empty($_GPC['op']) ? $_GPC['op'] : "display";
$pindex=max(1,intval($_GPC['page']));
$psize=5;
$csql="select * from ".tablename('dg_article_collect')." where openid=:openid and uniacid=:uniacid order by createtime desc limit ".intval($pindex-1)*$psize.",".$psize;
$parms=array(":openid"=>$openid,":uniacid"=>$uniacid);
if($op=="col"){
    $sql="select * from ".tablename('dg_article_user')." where openid=:openid and uniacid=:uniacid";
    $user=pdo_fetch($sql,$parms);
    $colsql="select count(*) from ".tablename('dg_article_collect')." where article_id=:id and openid=:openid";
    $colparms=array(":id"=>$id,":openid"=>$openid);
    $iscol=pdo_fetchcolumn($colsql,$colparms);
    header("Content-type:application/json");
    if(empty($user)){
        $res=array(
            'msg'=>'请先完善信息，才能收藏该文章'
        );
        echo json_encode($res);
        exit;
    }
    if(!empty($iscol)){
        $res=array(
            'msg'=>'你已经收藏过该文章了'
        );
        echo json_encode($res);
        exit;
    }
    $data=array(
        'uniacid'=>$uniacid,
        'article_id'=>$id,
        'uid'=>$user['id'],
        'openid'=>$openid,
        'createtime'=>TIMESTAMP
    );
    $inser=pdo_insert('dg_article_collect',$data);
    if(!empty($inser)){
        $res=array(
            'result'=>1,
            'msg'=>'收藏成功！'
        );
        echo json_encode($res);
        exit;
    }
}
if($op=="my"){
    $colsql="select count(*) from ".tablename('dg_article_collect')." where openid=:openid";
    $colparms=array(":openid"=>$openid);
    $iscol=pdo_fetchcolumn($colsql,$colparms);
    header("Content-type:application/json");
    $res=array('result'=>0);
    if(empty($iscol)){
        $res=array(
            'result'=>1,
            'msg'=>'你还没有收藏文章！'
        );
        echo json_encode($res);
        exit;
    }
    echo json_encode($res);
    exit;

}

$collect=pdo_fetchall($csql,$parms);
foreach($collect as $item){
    $asql="select * from ".tablename('dg_article')." where uniacid=:uniacid and id=:id order by createtime";
    $aparms=array(":uniacid"=>$uniacid,":id"=>$item['article_id']);
    $list=pdo_fetch($asql,$aparms);
    $list['thumb']=tomedia($list['thumb']);
    $article[]=$list;
}
if($op=='mo'){
    $data=array(
        'list'=>$article
    );
    header("Content-type:application/json");
    echo json_encode($data);
    exit;
}
include $this->template('collect');