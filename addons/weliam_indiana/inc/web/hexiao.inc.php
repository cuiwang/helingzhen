<?php
global $_W, $_GPC;
load()->func('tpl');
$op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$status = !empty($_GPC['status']) ? $_GPC['status'] : 3;

	
$pindex = max(1, intval($_GPC['page']));
$psize = 15;
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
	include $this->template('hexiao');
} elseif ($status == 2) {
	//核销记录
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_hexiao') . " WHERE uniacid = '{$_W['uniacid']}'");
	$list =  pdo_fetchall("select * from".tablename('weliam_indiana_hexiao')."where uniacid='{$_W['uniacid']}' order by id asc ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize); 
	foreach($list as $key => $value){
		$person = m('member')->getInfoByOpenid($value['hexiaoperson']);
		$usedperson = m('member')->getInfoByOpenid($value['usedperson']);
		$list[$key]['personname'] = $person['nickname'];
		$list[$key]['usedpersonname'] = $usedperson['nickname'];
		$list[$key]['time'] = date("Y-m-d H:i:s",$list[$key]['createtime']);
	}
	$pager = pagination($total, $pindex, $psize);
	$op = 'record';
	include $this->template('hexiao');
} elseif ($status == 3) {
	if ($op == 'display') {
		$list = pdo_fetchall("select * from" . tablename('weliam_indiana_saler') . "where uniacid='{$_W['uniacid']}'");
		foreach ($list as $key => $value) {
			$storeid_arr = explode(',', $value['storeid']);
			$storename   = '';
			foreach ($storeid_arr as $k => $v) {
				if ($v) {
					$store = pdo_fetch("select * from" . tablename('weliam_indiana_merchant') . "where id='{$v}'");
					$storename .= $store['name'] . "/";
				}
			}
			$storename               = substr($storename, 0, strlen($storename) - 1);
			$list[$key]['storename'] = $storename;
		}
	} elseif ($op == 'post') {
		$id = $_GPC['id'];
		if ($id) {
			$saler       = pdo_fetch("select * from" . tablename('weliam_indiana_saler') . "where uniacid='{$_W['uniacid']}' and id = '{$id}'");
			$storeid_arr = explode(',', $saler['storeid']);
			$storename   = '';
			foreach ($storeid_arr as $k => $v) {
				if ($v) {
					$stores[$k] = pdo_fetch("select * from" . tablename('weliam_indiana_merchant') . "where id='{$v}' and uniacid='{$_W['uniacid']}'");
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
				'status' => $_GPC['salerstatus']
			);
			if ($data['openid'] == '') {
				message('必须选择核销员！', referer(), 'error');
				exit;
			}
			
			$info             = m('member')->getInfoByOpenid($data['openid']);
			$data['avatar']   = $info['avatar'];
			$data['nickname'] = $info['nickname'];
			if ($id) {
				pdo_update('weliam_indiana_saler', $data, array(
					'id' => $id
				));
			} else {
				pdo_insert('weliam_indiana_saler', $data);
			}
			message('操作成功！', $this->createWebUrl('hexiao', array(
				'status' => 3
			)), 'success');
		}
	} elseif ($op == 'delete') {
		$id = $_GPC['id'];
		pdo_delete('weliam_indiana_saler', array(
			'id' => $id
		));
		message('删除成功！', referer(), 'success');
	}
	include $this->template('hexiao');
} elseif ($status == 4) {
	$con     = "uniacid='{$_W['uniacid']}'";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		$con .= " and name LIKE '%{$keyword}%' ";
	}
	$ds = pdo_fetchall("select * from" . tablename('weliam_indiana_merchant') . "where $con");
	include $this->template('query_store');
	exit;
} elseif ($status == 5) {
	$con     = "uniacid='{$_W['uniacid']}' ";
	$keyword = $_GPC['keyword'];
	if ($keyword != '') {
		$con .= " and nickname LIKE '%{$keyword}%'";
	}
	$ds = pdo_fetchall("select * from" . tablename('mc_mapping_fans') . "where $con");
	foreach($ds as $key=>$value){
		$k = m('member')->getInfoByOpenid($value['openid']);
		$ds[$key]['avatar'] = $k['avatar'];
	}
	include $this->template('query_saler');
	exit;
}
?>