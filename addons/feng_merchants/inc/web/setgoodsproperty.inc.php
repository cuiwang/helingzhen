<?php
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('new', 'hot', 'recommand', 'discount'))) {
			$data = ($data==1?'0':'1');
			pdo_update("tg_goods", array("is" . $type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('isshow'))) {
			$data = ($data==1?'0':'1');
			pdo_update("tg_goods", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if($type=='isshow2'){
			$data = ($data==1?'3':'1');
			pdo_update("tg_goods", array("isshow" => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));	
?>