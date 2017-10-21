<?php
		global $_GPC, $_W;
		$this->getuserinfo();
		$transid = $_GPC['transid'];
		$order_out = pdo_fetch("select * from".tablename('wz_tuan_order') . "where transid = '{$transid}'");
		$op = $_GPC['op'];
		if(!empty($order_out['orderno'])){
			$res=$this->refund($order_out['orderno'],'',1);
			if($res=='success'){
				echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('mygroup',array('result'=>'success','op'=>$op))."';</script>";
				exit;
			}else{
				echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('mygroup',array('result'=>'fail','op'=>$op))."';</script>";
				exit;
			}
		}else{
			echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('mygroup',array('result'=>'orderfail','op'=>$op))."';</script>";
			exit;
		}
?>