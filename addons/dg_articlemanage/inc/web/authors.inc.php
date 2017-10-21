<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/31
 * Time: 12:29
 */
global $_W,$_GPC;
load()->func('tpl');
$pindex=max(1,intval($_GPC['page']));
$psize=5;
$uniacid=$_W['uniacid'];
$condition=" uniacid=:uniacid ";
if(!empty($_GPC['keyword'])){
    $keyword=$_GPC['keyword'];
    $condition.=" and nickname like '%".$keyword."%'";
}
$sql="select * from ".tablename('dg_article_author')." where ".$condition."order by createtime desc limit ".intval($pindex-1)*$psize.",".$psize;
$parms=array(":uniacid"=>$uniacid);
$records=pdo_fetchall($sql,$parms);
$total=pdo_fetchcolumn("select count(*) from ".tablename('dg_article_author')." where $condition",$parms);

foreach($records as &$item){
    $msql="select sum(income_money) money from ".tablename('dg_article_income')." where uniacid=:uniacid and author_id=:aid";
    $mparms=array(":uniacid"=>$uniacid,":aid"=>$item['id']);
    $money=pdo_fetch($msql,$mparms);
    $item['money']=floatval($money['money']);
}

if(!empty($_GPC['del'])){
    $id=$_GPC['id'];
    pdo_delete('dg_article_author',array('id'=>$id,'uniacid'=>$uniacid));

    header('content-type:application/json;charset=utf8');

    $fmdata = array(
        "success" => 1,
        "data" =>"删除成功",
    );

    echo json_encode($fmdata);
    exit;

}
$pager=pagination($total,$pindex,$psize);
include $this->template('authors');