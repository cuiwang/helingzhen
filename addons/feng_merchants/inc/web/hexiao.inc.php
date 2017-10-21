<?php
global $_W, $_GPC;
$this->backlists();
$merchant=$this->merchant();
$roles = pdo_fetch("select * from".tablename('tg_user_role')."where uniacid={$_W['uniacid']} and merchantid={$merchant['id']}");
$nodes=array();
if($roles){
	$nodes = unserialize($roles['nodes']);
}
load()->func('tpl');
$op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$status = !empty($_GPC['status']) ? $_GPC['status'] : 1;
//商品展示
if ($status == 1) {
	$rule = pdo_fetch("select id from " . tablename('rule') . 'where uniacid=:uniacid and module=:module and name=:name', array(
		':uniacid' => $_W['uniacid'],
		':module' => 'feng_fightgroups',
		':name' => "拼团核销入口"
	));
	if ($rule) {
		$set = pdo_fetch("select content from " . tablename('rule_keyword') . 'where uniacid=:uniacid and rid=:rid', array(
			':uniacid' => $_W['uniacid'],
			':rid' => $rule['id']
		));
	}
	if (checksubmit('keysubmit')) {
		$keyword = empty($_GPC['keyword']) ? '核销' : $_GPC['keyword'];
		if (empty($rule)) {
			$rule_data = array(
				'uniacid' => $_W['uniacid'],
				'name' => '拼团核销入口',
				'module' => 'feng_fightgroups',
				'displayorder' => 0,
				'status' => 1
			);
			pdo_insert('rule', $rule_data);
			$rid          = pdo_insertid();
			$keyword_data = array(
				'uniacid' => $_W['uniacid'],
				'rid' => $rid,
				'module' => 'feng_fightgroups',
				'content' => trim($keyword),
				'type' => 1,
				'displayorder' => 0,
				'status' => 1
			);
			pdo_insert('rule_keyword', $keyword_data);
		} else {
			pdo_update('rule_keyword', array(
				'content' => trim($keyword)
			), array(
				'rid' => $rule['id']
			));
		}
		message('核销关键字设置成功!', referer(), 'success');
	}
	include $this->template('web/hexiao');
} elseif ($status == 2) {
	if ($op == 'display') {
		$list = pdo_fetchall("select * from" . tablename('tg_store') . "where uniacid='{$_W['uniacid']}' and merchantid='{$merchant['id']}' ");
	} elseif ($op == 'post') {
		$id = $_GPC['id'];
		if ($id) {
			$item = pdo_fetch("select * from" . tablename('tg_store') . "where uniacid='{$_W['uniacid']}' and id = '{$id}'");
		}
		if (checksubmit('storesubmit')) {
			$id   = $_GPC['id'];
			$data = array(
				'uniacid' => $_W['uniacid'],
				'storename' => $_GPC['storename'],
				'address' => $_GPC['address'],
				'tel' => $_GPC['tel'],
				'lng' => $_GPC['map']['lng'],
				'lat' => $_GPC['map']['lat'],
				'status' => $_GPC['qiyongstatus'],
				'merchantid'=>$merchant['id']
			);
			if (trim($data['storename']) == '') {
				message('必须填写门店名称！', referer(), 'error');
				exit;
			}
			if ($id) {
				pdo_update('tg_store', $data, array(
					'id' => $id
				));
			} else {
				pdo_insert('tg_store', $data);
			}
			message('操作成功！', $this->createWebUrl('hexiao', array(
				'status' => 2
			)), 'success');
		}
	} elseif ($op == 'delete') {
		$id = $_GPC['id'];
		pdo_delete('tg_store', array(
			'id' => $id
		));
		message('删除成功！', referer(), 'success');
	}
	include $this->template('web/hexiao');
} elseif ($status == 3) {
	if ($op == 'display') {
		$list = pdo_fetchall("select * from" . tablename('tg_saler') . "where uniacid='{$_W['uniacid']}' and merchantid='{$merchant['id']}' ");
		foreach ($list as $key => $value) {
			$storeid_arr = explode(',', $value['storeid']);
			$storename   = '';
			foreach ($storeid_arr as $k => $v) {
				if ($v) {
					$store = pdo_fetch("select * from" . tablename('tg_store') . "where id='{$v}'");
					$storename .= $store['storename'] . "/";
				}
			}
			$storename               = substr($storename, 0, strlen($storename) - 1);
			$list[$key]['storename'] = $storename;
		}
	} elseif ($op == 'post') {
		$id = $_GPC['id'];
		if ($id) {
			$saler       = pdo_fetch("select * from" . tablename('tg_saler') . "where uniacid='{$_W['uniacid']}' and id = '{$id}'");
			$storeid_arr = explode(',', $saler['storeid']);
			$storename   = '';
			foreach ($storeid_arr as $k => $v) {
				if ($v) {
					$stores[$k] = pdo_fetch("select * from" . tablename('tg_store') . "where id='{$v}' and uniacid='{$_W['uniacid']}'");
				}
			}
		}
		if (checksubmit('salersubmit')) {
			$id       = $_GPC['id'];
			$str      = '';
			$storeids = $_GPC['storeids'];
			if ($storeids) {
				foreach ($storeids as $key => $value) {
					if ($value) {
						$str .= $value . ",";
					}
				}
			}
			$data = array(
				'uniacid' => $_W['uniacid'],
				'openid' => $_GPC['openid'],
				'storeid' => $str,
				'status' => $_GPC['salerstatus'],
				'merchantid'=>$merchant['id']
			);
			if ($data['openid'] == '') {
				message('必须选择核销员！', referer(), 'error');
				exit;
			}
			$info             = $this->getfansinfo($data['openid']);
			$data['avatar']   = $info['avatar'];
			$data['nickname'] = $info['nickname'];
			if ($id) {
				pdo_update('tg_saler', $data, array(
					'id' => $id
				));
			} else {
				pdo_insert('tg_saler', $data);
			}
			message('操作成功！', $this->createWebUrl('hexiao', array(
				'status' => 3
			)), 'success');
		}
	} elseif ($op == 'delete') {
		$id = $_GPC['id'];
		pdo_delete('tg_saler', array(
			'id' => $id
		));
		message('删除成功！', referer(), 'success');
	}
	include $this->template('web/hexiao');
} elseif ($status == 4) {
	$con     = "uniacid='{$_W['uniacid']}' and status=1   and merchantid='{$merchant['id']}'";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		$con .= " and storename LIKE '%{$keyword}%' ";
	}
	$ds = pdo_fetchall("select * from" . tablename('tg_store') . "where $con");
	include $this->template('web/query_store');
	exit;
} elseif ($status == 5) {
	$con     = "uniacid='{$_W['uniacid']}' ";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		$con .= " and nickname LIKE '%{$keyword}%'";
	}
	$ds = pdo_fetchall("select * from" . tablename('tg_member') . "where $con");
	include $this->template('web/query_saler');
	exit;
}
?>