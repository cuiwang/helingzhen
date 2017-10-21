<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/10/8
 * Time: 12:37
 */
global $_W,$_GPC;
load()->func('tpl');
$pindex=max(1,$_GPC['page']);
$psize=5;
$aid=$_GPC['aid'];
$uniacid=$_W['uniacid'];
if($_GPC['op']=='on'){
    $sql="select * from ".tablename('dg_article')." where uniacid=:uniacid and author_id=:aid and status=2";
}else{
    $sql="select * from ".tablename('dg_article')." where uniacid=:uniacid and author_id=:aid";
}
$parms=array(':uniacid'=>$uniacid,":aid"=>$aid);
$article=pdo_fetchall($sql,$parms);
include $this->template('myarticle');