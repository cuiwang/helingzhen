<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/1
 * Time: 18:50
 */
global $_W,$_GPC;
$op=$_GPC['op'] ? $_GPC['op'] :"display";
load()->func('tpl');
load()->model('mc');
$pindex=max(1,intval($_GPC['page']));
$psize=10;
$uniacid=$_W['uniacid'];
$keyword=$_GPC['keyword'];
if(!empty($keyword)){
    $condition=" and title like '%".$keyword."%'";
}
$sql="select A1.*,A2.title from ".tablename("dg_article_shang")." A1 inner join ".tablename("dg_article")." A2 on A1.article_id=A2.id where A1.uniacid=:uniacid and shang_status=1".$condition." order by shang_time desc limit ".intval($pindex-1)*$psize.",".$psize;
$parms=array(":uniacid"=>$uniacid);
$list=pdo_fetchall($sql,$parms);
foreach($list as &$item){
    $user=mc_fetch($item['oauth_openid']);
    $item['nickname']=$user['nickname'];
}
$acoment=pdo_fetch("select count(0) acount,ifnull(sum(A1.shang_money),0) zong from ".tablename("dg_article_shang")." A1 inner join ".tablename("dg_article")." A2 on A1.article_id=A2.id where A1.uniacid=:uniacid and shang_status=1".$condition,array(":uniacid"=>$uniacid));
$total=$acoment['acount'];
$sum=$acoment['zong'];
include $this->template('admire');



