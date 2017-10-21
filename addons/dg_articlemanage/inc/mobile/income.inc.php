<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/5
 * Time: 17:27
 */
global $_W,$_GPC;
load()->func('tpl');
$uniacid=$_W['uniacid'];
$aid=$_GPC['aid'];
$sql="select * from ".tablename('dg_article_author')." where uniacid=:uniacid and id=:aid";
$parms=array(":uniacid"=>$uniacid,":aid"=>$aid);
$author=pdo_fetch($sql,$parms);
$scale=floatval($author['scale']);
$lastsql="select * from ".tablename("dg_article_income")." where uniacid=:uniacid and author_id=:aid order by createtime desc limit 1";
$last=pdo_fetch($lastsql,$parms);
if(!empty($last)){
    $lasttime=$last['createtime'];
    $pcondition="and pay_time>$lasttime";
    $scondition="and shang_time>$lasttime";
}

$articlesql="select id from ".tablename('dg_article')." where uniacid=$uniacid and author_id=$aid";
$pall=pdo_fetch("select sum(pay_money) pay from ".tablename('dg_article_payment')." where order_status=1 and uniacid=$uniacid and article_id in ($articlesql)");
$sall=pdo_fetch("select sum(shang_money) shang from ".tablename('dg_article_shang')." where shang_status=1 and uniacid=$uniacid and article_id in ($articlesql)");
$all=$pall['pay']*$scale+$sall['shang']*$scale;

$paysql=pdo_fetch("select sum(pay_money) pay from ".tablename('dg_article_payment')." where order_status=1 and uniacid=$uniacid ".$pcondition." and article_id in($articlesql)");
$shangsql=pdo_fetch("select sum(shang_money) shang from ".tablename('dg_article_shang')." where shang_status=1 and uniacid=$uniacid ".$scondition." and article_id in($articlesql)");
$pay=$paysql['pay']*$scale;
$shang=$shangsql['shang']*$scale;

$sum=$pay+$shang;//可提现金额
if($_GPC['submit']){
    header("Content-type:application/json");
    $ordernum=$this->build_order_sn();
    if($sum<1){
        $fmdata = array(
            "success" => -1,
            "data" =>"当前金额小于1元,不能提现"
        );
        echo json_encode($fmdata);
        exit;
    }
    $data=array(
        "uniacid"=>$uniacid,
        "author_id"=>$aid,
        "income_money"=>$sum,
        "createtime"=>TIMESTAMP,
        "income_status"=>1,
        "batch_num"=>$ordernum
    );
    pdo_insert("dg_article_income",$data);
    $fmdata=array(
        "success"=>1,
        "data"=>"正在结算"
    );
    echo json_encode($fmdata);
    exit;
}

$insql="select * from ".tablename("dg_article_income")." where author_id=:aid and uniacid=:uniacid order by createtime desc";
$insum=pdo_fetch("select sum(income_money) insum from ".tablename("dg_article_income")." where author_id=:aid and uniacid=:uniacid",$parms);
$incomesum=$insum['insum'];
$income=pdo_fetchall($insql,$parms);
foreach($income as &$item){
    $item['income_status']=$item['income_status']==1? "处理中" : "已结算";
}
include $this->template('income');