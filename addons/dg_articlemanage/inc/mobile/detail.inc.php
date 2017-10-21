<?php

function ordersubmit($orderid,$moneynum){
    global $_W,$_GPC;
    $article_id=intval($_GPC['id']);
    $fromer=$_GPC['fuser'];
    $uniacid=$_W['uniacid'];
    $openid=$_W['openid'];
    if($_W["account"]["level"]<4){
        $openid=$_SESSION['oauth_openid'];
    }
    $data=array(
        "uniacid"=>$uniacid,
        "article_id"=>$article_id,
        "openid"=>$openid,
        "oauth_openid"=>empty($_SESSION['oauth_openid'])?$_W['fans']['from_user']:$_SESSION['oauth_openid'],
        "pay_money"=>$moneynum,
        "out_trade_no"=>$orderid,
        "order_status"=>0,
        "pay_time"=>time(),
        'fromer'=>$fromer
    );
    pdo_insert('dg_article_payment',$data);
}
$kjSetting=$this->findKJsetting();
function getpayment($money,$kjSetting){
    global $_W;
    $money= floatval($money);
    $money=(int)($money*100);
    $jsApi = new JsApi_pub($kjSetting);
    $openid=$_W['openid'];
    if($_W["account"]["level"]<4){
        $openid=$_SESSION['oauth_openid'];
    }
    $unifiedOrder = new UnifiedOrder_pub($kjSetting);
    $unifiedOrder->setParameter("openid", "$openid");//商品描述
    $unifiedOrder->setParameter("body", "文章付费阅读");//商品描述
    $timeStamp = time();
    $out_trade_no =  date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    $unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
    $unifiedOrder->setParameter("total_fee", $money);//总金额
    $notifyUrl = $_W['siteroot'] . "addons/dg_articlemanage/notify.php";
    $unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
    $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
    $prepay_id = $unifiedOrder->getPrepayId();
    $jsApi->setPrepayId($prepay_id);
    $jsApiParameters = $jsApi->getParameters();

    //插入数据到赞赏表中

    ordersubmit($out_trade_no,$money);
    return $jsApiParameters;
}

global $_GPC, $_W;
$id = intval($_GPC['id']);
$uniacid=$_W['uniacid'];
$openid=$_W['openid'];
$detail = pdo_fetch("SELECT * FROM " . tablename('dg_article') . " WHERE `id`=:id and uniacid=:uniacid", array(':id'=>$id,':uniacid' => $uniacid));
if (!empty($detail)) {
    pdo_update('dg_article', array('clickNum' => $detail['clickNum'] + 1), array('id' => $detail['id']));
}
$shareimg = toimage($detail['thumb']);
$url=$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$id,'uniacid'=>$uniacid,'fuser'=>$openid),true),2);
$fromer=$_GPC['fuser'];
if($_W["account"]["level"]<4){
    $openid=$_SESSION['oauth_openid'];
}
$user=pdo_fetch("select * from ".tablename('dg_articlelike')."where tid=:id and openid=:openid and uniacid=:uniacid",array(":id"=>$id,":openid"=>$openid,":uniacid"=>$uniacid));
if(!empty($user)){
    $like=1;
}
$member=pdo_fetch("select * from ".tablename('dg_article_user')." where uniacid=:uniacid and openid=:openid",array(":uniacid"=>$uniacid,":openid"=>$openid));
$pay_status=0;
$pay_count=0;
$pay_parameters="{}";
$payid=$_GPC['payid'];
$pay_count=$detail['pay_num'];
if($detail['pay_money']>0&&$member["info_status"]!=2){
    $pay_status=pdo_fetchcolumn("SELECT order_status FROM ".tablename('dg_article_payment')." WHERE order_status=1 AND openid=:openid AND article_id=:article_id and uniacid=:uniacid",array(":openid"=>$openid,":article_id"=>$id,':uniacid' => $uniacid));
    $pay_count=pdo_fetchcolumn("SELECT count(0) FROM ".tablename('dg_article_payment')." WHERE order_status=1 AND article_id=:article_id and uniacid=:uniacid",array(":article_id"=>$id,':uniacid' => $uniacid));
    //如果不存在或者没有支付则替换为文章简介
    $pay_count=$pay_count+$detail['pay_num'];
    if(empty($pay_status)){
        $detail['content']=htmlspecialchars_decode($detail['description']);
    }
    if($payid==1){
        $pay_parameters=getpayment($detail['pay_money'],$kjSetting);
        $data=$pay_parameters;
        header("Content-type:application/json");
        echo $data;
        exit();
    }
}

$sql="select * from ".tablename('dg_article_shang')." where article_id=:article_id and uniacid=:uniacid and shang_status=1 order by shang_time desc limit 5";
$parms=array(':uniacid'=>$uniacid,':article_id'=>$id);
$shang=pdo_fetchall($sql,$parms);
foreach($shang as &$item){
    $fansinfo=mc_fetch($item['openid']);
    $item['headimg']=$fansinfo['avatar'];
}

$ssql="select count(*) from ".tablename('dg_article_shang')." where openid=:openid and uniacid=:uniacid and article_id=:article_id and shang_status=1";
$sparms=array(":openid"=>$openid,":uniacid"=>$uniacid,":article_id"=>$id);
$sstatus=pdo_fetchcolumn($ssql,$sparms);
$pcount=pdo_fetchcolumn("select count(*) from ".tablename('dg_article_shang')." where shang_status=1 and uniacid=:uniacid and article_id=:article_id",$parms);

//$sql = "SELECT * FROM ".tablename("fineness_adv_er")." AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM ".tablename("fineness_adv_er").")-(SELECT MIN(id) FROM ".tablename("fineness_adv_er")."))+(SELECT MIN(id) FROM ".tablename("fineness_adv_er").")) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
//$randAdv = pdo_fetch($sql);
//if($randAdv){
//    $randAdv['thumb'] = (strpos($randAdv['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $randAdv['thumb'] : $randAdv['thumb'];
//}

$wechat=  pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE acid=:acid AND uniacid=:uniacid limit 1", array(':acid' => $uniacid,':uniacid' => $uniacid));
if(!empty($detail['template'])) {
    include $this->template($detail['templatefile']);
    exit;
}
function randomFloat($min = 0, $max = 1) {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}
$sdol=round(randomFloat(1,5),2);

/*留言展示*/
$disql="select * from ".tablename("dg_article_dis")." where uniacid=:uniacid and aritcle_id=:id and status=2";
$disparms=array(":uniacid"=>$uniacid,":id"=>$id);
$dis=pdo_fetchall($disql,$disparms);

/*作者*/
$autorid=$detail['author_id'];
$asql="select * from ".tablename('dg_article_author')." where uniacid=:uniacid and id=:id";
$aprams=array(":uniacid"=>$uniacid,":id"=>$autorid);
$author=pdo_fetch($asql,$aprams);

if(!empty($_GPC['disid'])){
    $artid=$_GPC['id'];
    $disid=$_GPC['disid'];
    $result=pdo_fetch("select * from ".tablename('dg_article_diszan')." where uniacid=:uniacid and disid=:disid and artid=:aritid",array(":uniacid"=>$uniacid,":disid"=>$disid,":aritid"=>$artid));
    if(empty($result)){
        $insert=array(
            'uniacid'=>$uniacid,
            'disid'=>$disid,
            'artid'=>$artid,
            'openid'=>$openid,
            'createtime'=>TIMESTAMP
        );
        pdo_insert('dg_article_diszan',$insert);
    }
    $zannum=pdo_fetchcolumn("select count(*) from ".tablename('dg_article_dis')." where uniacid=:uniacid and aritcle_id=:aid and id=:disid",array(":uniacid"=>$uniacid,":aid"=>$artid,":disid"=>$disid));
    $up=array(
      'zannum'=>$zannum
    );
    pdo_update("dg_article_dis",$up,array("id"=>$disid));
    $res=array();
    header("Content-type:application/json");
    $res['zannum']=$zannum;
    echo json_encode($res);
    exit;
}

$colsql="select count(*) from ".tablename('dg_article_collect')." where article_id=:id and openid=:openid";
$colparms=array(":id"=>$detail['id'],":openid"=>$openid);
$iscol=pdo_fetchcolumn($colsql,$colparms);
include $this->template('detail5');
exit;