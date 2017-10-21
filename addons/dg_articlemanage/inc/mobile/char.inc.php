<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/15
 * Time: 17:41
 */
global $_W,$_GPC;
$parentid=$_GPC['parentid'];
$uniacid=$_W['uniacid'];
$sql="select * from ".tablename('dg_article_category')."where parentid=:parentid order by displayorder desc";
$list=pdo_fetchall($sql,array(":parentid"=>$parentid));
$data=array();
$data['list']=$list;
header('Content-type: application/json');
echo json_encode($data);
