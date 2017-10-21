<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/2
 * Time: 18:35
 */
global $_W,$_GPC;

load()->func('tpl');
$uniacid=$_W['uniacid'];
if(!empty($_GPC['id'])){
    $tempid=intval($_GPC['id']);
    $row=pdo_fetch("select A1.*,A2.openid from ".tablename("dg_article_income")." A1 INNER JOIN ".tablename("dg_article_author")." A2
    ON A1.author_id=A2.id where A1.uniacid=:uniacid and A1.id=:id",array(":uniacid"=>$uniacid,":id"=>$tempid));
    if(empty($row)||$row['income_status']!=1){
        exit;
    }
    $result=$this->pay_cash($row['openid'],$row['income_money']);
    if($result['errno']==0){
        pdo_update("dg_article_income",array("income_status"=>2,'income_time'=>time(),'transaction_id'=>$result['payment_no']),array("id"=>$row['id']));
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
$condition="A1.uniacid=:uniacid";
$parms[":uniacid"]=$uniacid;
if(!empty($_GPC['status'])){
    $condition.=" and A2.income_status=:status";
    $parms[":status"]=$_GPC['status'];
}
if(!empty($starttime)){
    $condition.=" and A2.createtime>:starttime";
    $parms[':starttime']=$starttime;
}
if(!empty($endtime)){
    $condition.=" and A2.createtime<:endtime";
    $parms[':endtime']=$endtime;
}
$sql="select A1.avatar,A1.nickname,A1.realname,A1.scale, A2.* from ".tablename('dg_article_author')." A1 inner join ".tablename("dg_article_income")." A2 on A1.id=A2.author_id
where ".$condition." order by A2.createtime desc limit ".($pindex-1)*$psize.",".$psize;
$income=pdo_fetchall($sql,$parms);
foreach($income as &$item){
        $item['income_status']=$item['income_status']==1 ? "处理中" : "已结算";
}
$total=pdo_fetchcolumn("select count(*) from ".tablename("dg_article_income")." where uniacid=:uniacid",array(":uniacid"=>$uniacid));

$pager = pagination($total, $pindex, $psize);

include $this->template('finance');