<?php
include "TopSdk.php"; date_default_timezone_set('Asia/Shanghai');
$settings = $_W['setting']['copyright'];
$appkey=$_REQUEST['appkey'];
$_SESSION['v_code']=rand(1000,9999);
$mobile=$_REQUEST['phone'];
$secret=$_REQUEST['secret'];
 $c = new TopClient;
$c->appkey = $appkey;
$c->secretKey = $secret;
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req->setExtend("123454");
$req->setSmsType("normal");
$req->setSmsFreeSignName($_REQUEST['qianming']);
$req->setSmsParam("{\"name\":\"".$_REQUEST['name']."\",\"phone\":\"".$_REQUEST['phonenum']."\",\"v_code\":\"".$_SESSION['v_code']."\"}");
$req->setRecNum($mobile);
$req->setSmsTemplateCode($_REQUEST['moban']);
$resp = $c->execute($req);
echo "发送成功";
?>


