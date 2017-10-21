<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/23
 * Time: 10:55
 */
global $_W,$_GPC;
load()->func('tpl');
$uniacid=$_W['uniacid'];
$openid=$_W['openid'];
$nickname=$_W['fans']['tag']['nickname'];
$avatar=$_W['fans']['tag']['avatar'];
$cfg=$this->module['config'];
$cfg['dg_article_scale']=empty($cfg['dg_article_scale'])? 0.3 :floatval($cfg['dg_article_scale']);
$sql="select * from ".tablename('dg_article_sharep')." where openid=:openid and uniacid=:uniacid order by createtime desc limit 1";
$parms=array(":openid"=>$openid,":uniacid"=>$uniacid);
$fromer=pdo_fetch($sql,$parms);
if(!empty($fromer)){
    $last=$fromer['createtime'];
    $condition=" and `pay_time`>$last";
}
$ssql="select sum(pay_money) pay from ".tablename('dg_article_payment')." where order_status=1 and fromer=:fromer and uniacid=:uniacid";
$sparms=array(":fromer"=>$openid,":uniacid"=>$uniacid);
$sharepay=pdo_fetch($ssql,$sparms);
$all=floatval($sharepay['pay'])*floatval($cfg['dg_article_scale']);

$csql="select sum(pay_money) pay from ".tablename('dg_article_payment')." where order_status=1".$condition." and fromer=:fromer and uniacid=:uniacid";
$cparms=array(":fromer"=>$openid,":uniacid"=>$uniacid);
$pay=pdo_fetch($csql,$cparms);
$sum=floatval($pay['pay'])*floatval($cfg['dg_article_scale']);
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
        "openid"=>$openid,
        'nickname'=>$nickname,
        'avatar'=>$avatar,
        "share_money"=>$sum,
        "createtime"=>TIMESTAMP,
        "share_status"=>1,
        "batch_num"=>$ordernum
    );
    pdo_insert("dg_article_sharep",$data);
    $fmdata=array(
        "success"=>1,
        "data"=>"正在结算"
    );
    echo json_encode($fmdata);
    exit;
}

$insql="select * from ".tablename("dg_article_sharep")." where openid=:openid and uniacid=:uniacid order by createtime desc";
$insum=pdo_fetch("select sum(share_money) insum from ".tablename("dg_article_sharep")." where openid=:openid and uniacid=:uniacid",$parms);
$incomesum=$insum['insum'];
$income=pdo_fetchall($insql,$parms);
if(!empty($income)){
    foreach($income as &$item){
        $item['share_status'] = $item['share_status']==1 ? "处理中" : "已结算";
    }
}

include $this->template('sharep');