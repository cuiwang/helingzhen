<?php
			global $_GPC, $_W;
			$orderno = $_GPC['orderno'];
			$data=array();
			$data['status']=6;
			$data['is_tuan']=2;
			$res = pdo_update('wz_tuan_order', $data, array('orderno' => $orderno));
			echo "<script>location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('result'=>'success'))."';</script>";
			exit;
?>