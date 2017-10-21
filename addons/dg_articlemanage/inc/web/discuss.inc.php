<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/18
 * Time: 15:24
 */
global $_W,$_GPC;
load()->func('tpl');
$uniacid=$_W['uniacid'];
$op=!empty($_GPC['op']) ? $_GPC['op'] : "display";
$pindex=max(1,intval($_GPC['page']));
$psize=10;
$total=pdo_fetchcolumn("select count(*) from ".tablename('dg_article_dis')." where uniacid=:uniacid",array(":uniacid"=>$uniacid));
$sql="select A1.*,A2.title from ".tablename('dg_article_dis')." A1 inner join ".tablename("dg_article")." A2 on A1.aritcle_id=A2.id where A1.uniacid=:uniacid order by id desc limit ".intval($pindex-1)*$psize.",".$psize;
$parms=array(":uniacid"=>$uniacid);
$list=pdo_fetchall($sql,$parms);
$pager=pagination($total,$pindex,$psize);
if($op=='on'){
    $id=$_GPC['id'];
    $st=pdo_fetch("select * from ".tablename('dg_article_dis')." where uniacid=:uniacid and id=:id",array(':uniacid'=>$uniacid,":id"=>$id));
    $result=array();
    if($st['status']==1){
        $data=array(
            'status'=>2,
        );
        $result['res']=1;
    }else{
        $data=array(
            'status'=>1,
        );
    }
    $up=pdo_update('dg_article_dis',$data,array('id'=>$id));
    header("Content-type:application/json");
    echo json_encode($result);
    exit;
}
if($op=="delete"){
    $id=$_GPC['id'];
    pdo_delete('dg_article_dis',array('id'=>$id));
    header("Content-type:application/json");
    echo json_encode($result);
    exit();
}
include $this->template('discuss');