<?php
defined ( 'IN_IA' ) or exit('Access Denied,your ip is:'.$_SERVER['REMOTE_ADDR'].',We have recorded the source of attack.');
$openid=$_W['fans']['from_user'];
$list=pdo_fetchall("select dtime,dcredit from ".tablename($this->paylogtable)." where dopenid=:dopenid and uniacid=:uniacid and dissuccess=1",
		array(":dopenid"=>$openid,":uniacid"=>$_W["uniacid"]));
include $this->template("exchangerecord");