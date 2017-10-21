<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/19
 * Time: 10:05
 */
global $_W,$_GPC;
load()->func('tpl');
$uniacid=$_W['uniacid'];
$id=$_GPC['id'];
$sql="select * from ".tablename('dg_article_dis')." where uniacid=:uniacid and id=:id";
$parms=array(":uniacid"=>$uniacid,":id"=>$id);
$user=pdo_fetch($sql,$parms);
if(checksubmit()){
    $reply=$_GPC['reply'];
    $data=array(
        'reply'=>$reply
    );
    pdo_update("dg_article_dis",$data,array('id'=>$id));
    message('回复成功',$this->createweburl('discuss'),"success");

}
include $this->template('reply');