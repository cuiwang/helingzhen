<?php 
	global $_W,$_GPC;
	$uniacid=$_W['uniacid'];
       $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($op == 'display'){
			$singlehotel   = pdo_fetch("select * from " . tablename($this->hotelmanager) . " where status=1 and uniacid= '{$_W['uniacid']}' ");
		}elseif($op == 'open'){
			$hotelid     = intval($_GPC['hotelid']);

			$data = array(
					'uniacid'    => $_W['uniacid'],
					'hotelid'  => $hotelid,
					'status'     => 1
			);
			pdo_insert($this->hotelmanager, $data);
			message('开启单店模式成功！', $this->createWebUrl('singlehotel', array('op' => 'display')), 'success');
		} elseif ($op == 'close') {
			$data = array(
					'status'  => 0
			);
			pdo_update($this->hotelmanager, $data, array('uniacid' => $uniacid));
			message('关闭单店模式成功！', $this->createWebUrl('singlehotel', array('op' => 'display')), 'success');
		} elseif($op =='change'){
			$data = array(
					'status'  => 0
			);
			pdo_update($this->hotelmanager, $data, array('uniacid' => $uniacid));
			$hotelid     = intval($_GPC['hotelid']);

			$data1 = array(
					'uniacid'    => $_W['uniacid'],
					'hotelid'  => $hotelid,
					'status'     => 1
			);
			pdo_insert($this->hotelmanager, $data1);
			message('修改单店模式成功！', $this->createWebUrl('singlehotel', array('op' => 'display')), 'success');
		}
    $list = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . ' WHERE uniacid = '.$uniacid);

		include $this->template('singlehotel');
		exit;