<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/10
 * Time: 15:24
 */
global $_W,$_GPC;
load()->func('tpl');
$uniacid=$_W['uniacid'];
$openid=$_W['openid'];
$nickname=$_W['fans']['tag']['nickname'];
$avatar=tomedia($_W['fans']['tag']['avatar']);
$sex=$_W['fans']['tag']['sex'];
$cfg=$this->module['config'];
$cfg['dg_article_recharge']=empty($cfg['dg_article_recharge'])? 5 :floatval($cfg['dg_article_recharge']);
$cfg['dg_article_scale']=empty($cfg['dg_article_scale'])? 0.3 :floatval($cfg['dg_article_scale']);
$op=!empty($_GPC['op'])?$_GPC['op']:"display";
/*
 * 用户信息
 * */
$sql="select * from ".tablename('dg_article_user')." where uniacid=:uniacid and openid=:openid ";
$parms=array(":uniacid"=>$uniacid,":openid"=>$openid);
$user=pdo_fetch($sql,$parms);
/*$recsql="select * from ".tablename('dg_article_recharge')." where uniacid=:uniacid and openid=:openid order by rec_time";
$recparms=array(":unicaid"=>$uniacid,":openid"=>$openid);
$userrec=pdo_fetch($recsql,$recparms);*/
if($user['end_time']<TIMESTAMP&&!empty($user['end_time'])){
    $updata=array(
        'info_status'=>1,
    );
    pdo_update("dg_article_user",$updata,array('id'=>$user['id']));
}
/*
 * 购买成为会员
 * */

function ordersubmit($orderid,$moneynum){
    global $_W,$_GPC;
    $month=intval($_GPC['month']);
    $uniacid=$_W['uniacid'];
    $openid=$_W['openid'];
    $data=array();
    $data['uniacid']=$uniacid;
    $data['openid']=$openid;
    $data['recharge']=$moneynum;
    $data['out_trade_no']=$orderid;
    $data['rec_status']=0;
    $data['rec_time']=time();
    $data['month']=$month;
    pdo_insert('dg_article_recharge',$data);
}
$kjSetting=$this->findKJsetting();
function getpayment($money,$kjSetting){
    global $_W;
    $money= floatval($money);
    $money=(int)($money*100);
    $jsApi = new JsApi_pub($kjSetting);
    $openid=$_W['openid'];
    $unifiedOrder = new UnifiedOrder_pub($kjSetting);
    $unifiedOrder->setParameter("openid", "$openid");//商品描述
    $unifiedOrder->setParameter("body", "阅读会员支付");//商品描述
    $timeStamp = time();
    $out_trade_no =  date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    $unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
    $unifiedOrder->setParameter("total_fee", $money);//总金额
    $notifyUrl = $_W['siteroot'] . "addons/dg_articlemanage/recharge.php";
    $unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
    $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
    $prepay_id = $unifiedOrder->getPrepayId();
    $jsApi->setPrepayId($prepay_id);
    $jsApiParameters = $jsApi->getParameters();

    //插入数据到赞赏表中

    ordersubmit($out_trade_no,$money);
    return $jsApiParameters;
}

if($op=="post"){
    $rec=floatval($_GPC['rec']);
    $pay_parameters=getpayment($rec,$kjSetting);
    $data=$pay_parameters;
    header("Content-type:application/json");
    echo $data;
    exit();
}

/*
 * 判断是否是作者
 * */
$author=pdo_fetch("select * from ".tablename("dg_article_author")." where uniacid=:uniacid and openid=:openid",array(":uniacid"=>$uniacid,":openid"=>$openid));
$authorurl=$this->createmobileurl('income',array('aid'=>$author['id']));

$pubarticle=$this->createmobileurl('pubarticle',array('aid'=>$author['id']));

$myarticle=$this->createmobileurl('myarticle',array('aid'=>$author['id']));
include $this->template('center');