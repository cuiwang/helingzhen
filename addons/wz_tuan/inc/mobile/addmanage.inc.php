<?php
	global $_W,$_GPC;
	session_start();
	$goodsid = $_SESSION['goodsid'];
	$openid = $_W['openid'];
	$this->getuserinfo();
	if($_GPC['op']=='select'){
		$id=$_GPC['id'];
        $moren =  pdo_fetch("SELECT * FROM".tablename('wz_tuan_address')."where status=1 and openid='$openid'");
        pdo_update('wz_tuan_address',array('status' => 0),array('id' => $moren['id']));
        pdo_update('wz_tuan_address',array('status' =>1),array('id' => $id));
		if($goodsid!=''){
			echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('orderconfirm')."';</script>";
			exit;
		}
	}
	$address = pdo_fetchall("select * from " . tablename('wz_tuan_address')."where openid='{$openid}' ");
	include $this->template('addmanage');
?>