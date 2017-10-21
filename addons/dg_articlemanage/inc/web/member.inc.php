<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/12
 * Time: 11:50
 */
global $_W,$_GPC;
load()->func("tpl");
$uniacid=$_W['uniacid'];
$pindex=max(1,intval($_GPC['page']));
$psize=10;
$keyword=$_GPC['keyword'];
if(!empty($keyword)){
    $condition=" nickname like '%".$keyword."%' and ";
}
$sql="select * from ".tablename('dg_article_user')." where ".$condition." uniacid=:uniacid and info_status=2 order by id desc limit ".intval($pindex-1)*$psize.",".$psize;
$parms=array(":uniacid"=>$uniacid);
$records=pdo_fetchall($sql,$parms);
$total=pdo_fetchcolumn("select count(*) from ".tablename('dg_article_user')." where uniacid=:uniacid and info_status=2",$parms);
$pager=pagination($total,$pindex,$psize);
include $this->template('member');