<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/2
 * Time: 14:51
 */
global $_W,$_GPC;
load()->func('tpl');
$id=$_GPC['id'];
$uniacid=$_W['uniacid'];
$sql="select * from ".tablename("dg_article_author")." where id=:id and uniacid=:uniacid";
$parms=array(":id"=>$id,":uniacid"=>$uniacid);
$user=pdo_fetch($sql,$parms);
if(checksubmit()){
    $data=array(
        'nickname'=>$_GPC['nickname'],
        'mobile'=>$_GPC['mobile'],
        'realname'=>$_GPC['realname'],
        'scale'=>$_GPC['scale']
    );
    $re=pdo_update('dg_article_author',$data,array('id'=>$id));
    if($re){
        message("操作成功",referer(),"success");
    }
}
$authorurl=substr($this->createmobileurl('myarticle',array('aid'=>$id,'op'=>'on')),2);
$author=$_W['siteroot'].'app/'.$authorurl;
include $this->template('set');