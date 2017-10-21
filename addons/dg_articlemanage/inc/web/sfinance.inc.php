<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/24
 * Time: 10:40
 */
global $_W,$_GPC;
load()->func('tpl');
$uniacid=$_W['uniacid'];
if(!empty($_GPC['id'])){
    $tempid=intval($_GPC['id']);
    $row=pdo_fetch("select * from ".tablename("dg_article_sharep")." where uniacid=:uniacid and id=:id",array(":uniacid"=>$uniacid,":id"=>$tempid));
    if(empty($row)||$row['share_status']!=1){
        exit;
    }
    $result=$this->pay_cash($row['openid'],$row['share_money']);
    if($result['errno']==0){
        pdo_update("dg_article_sharep",array("share_status"=>2,'share_time'=>time(),'transaction_id'=>$result['payment_no']),array("id"=>$row['id']));
    }
    $fmdata = array(
        "success" => $result['errno'],
        "msg" => $result['error'],
    );
    header('content-type:application/json;charset=utf8');
    echo json_encode($fmdata);
    exit();
}

$pindex=max(1,intval($_GPC['page']));
$psize=10;
$starttime=empty($_GPC['time']['start']) ? TIMESTAMP- 86399 * 6 : strtotime($_GPC['time']['start']);
$endtime=empty($_GPC['time']['end']) ? TIMESTAMP + 86400 : strtotime($_GPC['time']['end']);
$parms=array();
$condition="uniacid=:uniacid";
$parms[":uniacid"]=$uniacid;
if(!empty($_GPC['status'])){
    $condition.=" and share_status=:status";
    $parms[":status"]=$_GPC['status'];
}
if(!empty($starttime)){
    $condition.=" and createtime>:starttime";
    $parms[':starttime']=$starttime;
}
if(!empty($endtime)){
    $condition.=" and createtime<:endtime";
    $parms[':endtime']=$endtime;
}
$sql="select * from ".tablename('dg_article_sharep')." where ".$condition." order by createtime desc limit ".($pindex-1)*$psize.",".$psize;
$income=pdo_fetchall($sql,$parms);
foreach($income as &$item){
    $item['share_status']=$item['share_status']==1 ? "处理中" : "已结算";
}
$total=pdo_fetchcolumn("select count(*) from ".tablename("dg_article_sharep")." where uniacid=:uniacid",array(":uniacid"=>$uniacid));

$pager = pagination($total, $pindex, $psize);

include $this->template('sfinance');