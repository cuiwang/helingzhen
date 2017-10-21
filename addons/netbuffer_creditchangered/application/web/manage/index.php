<?php
defined ( 'IN_IA' ) or exit('Access Denied,your ip is:'.$_SERVER['REMOTE_ADDR'].',We have recorded the source of attack.');
$keyword=$_GPC['keyword'];
$pageIndex=$_GPC['page']==""?1:$_GPC['page'];
$pageSize=40;
if(!$keyword==""){
	$total=pdo_fetch("select count(*) total from ".tablename($this->paylogtable)." where uniacid=:uniacid and dwnick like '%{$keyword}%'",
			array(":uniacid"=>$_W['uniacid']))['total'];
	$list=pdo_fetchall("select * from ".tablename($this->paylogtable)." where uniacid=:uniacid and dwnick like '%{$keyword}%' limit ".$pageSize*($pageIndex-1).",".
			$pageSize,array(":uniacid"=>$_W["uniacid"]));
}else{
	$total=pdo_fetch("select count(*) total from ".tablename($this->paylogtable)." where uniacid=:uniacid",
			array(":uniacid"=>$_W['uniacid']))['total'];
	$list=pdo_fetchall("select * from ".tablename($this->paylogtable)." where uniacid=:uniacid limit ".$pageSize*($pageIndex-1).",".
			$pageSize,array(":uniacid"=>$_W["uniacid"]));
}
$pagination = pagination($total,$pageIndex,$pageSize);
include $this->template("web/index");