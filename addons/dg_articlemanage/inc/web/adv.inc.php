<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/20
 * Time: 15:56
 */
global $_W,$_GPC;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] :"display";
$uniacid=$_W['uniacid'];
if($operation=='post'){
    $id=$_GPC['id'];
    $sql="select * from ".tablename('dg_article_adv')." where uniacid=:uniacid and id=:id";
    $parms=array(":uniacid"=>$uniacid,":id"=>$id);
    $banner=pdo_fetch($sql,$parms);
    if(checksubmit()){
        empty($_GPC['adg_name'])&&message('幻灯片标题没有设置');
        empty($_GPC['adv_img'])&&message('幻灯片没有上传');
        $data=array(
            'uniacid'=>$uniacid,
            'displayorder'=>$_GPC['displayorder'],
            'adg_name'=>$_GPC['adg_name'],
            'adv_img'=>$_GPC['adv_img'],
            'adv_href'=>$_GPC['adv_href'],
            'adv_status'=>$_GPC['adv_status'],
            'createtime'=>TIMESTAMP
        );
        if(empty($_GPC['id'])){
            $re=pdo_insert('dg_article_adv',$data);
        }else{
            $re=pdo_update('dg_article_adv',$data,array('id'=>$id));
        }
        if($re){
            message('设置成功',referer(),"success");
        }
    }
}
if($operation=='delete'){
    $id=$_GPC['id'];
    $re=pdo_delete('dg_article_adv',array('id'=>$id));
    if($re){
        message('删除成功',referer(),"success");
    }
}
$list=pdo_fetchall("select * from ".tablename('dg_article_adv')." where uniacid=$uniacid order by displayorder desc");
include $this->template('adv');