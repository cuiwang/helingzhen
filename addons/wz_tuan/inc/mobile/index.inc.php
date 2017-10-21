<?php

session_start();
$this->getuserinfo();
$_SESSION['goodsid'] = '';
$_SESSION['tuan_id'] = '';
$_SESSION['groupnum'] = '';
global $_W, $_GPC;
load()->model('mc');
$reslut = $_GPC['result'];
$share_data = $this->module['config'];
if($share_data['share_imagestatus'] == ''){
	$shareimage =$this->module['config']['share_image'];
}
if($share_data['share_imagestatus'] == 1){
	$shareimage =$this->module['config']['share_image'];
}
if($share_data['share_imagestatus'] == 2){
	$result = mc_fetch($_W['member']['uid'], array('credit1', 'credit2','avatar','nickname'));
	$shareimage = $result['avatar'];
}
if($share_data['share_imagestatus'] == 3){
	$shareimage =$this->module['config']['share_image'];
}

if ($this -> module['config']['mode'] == 1) {
	$category = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
	//幻灯片
	$advs = pdo_fetchall("select * from " . tablename('wz_tuan_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
	foreach ($advs as &$adv) {
		if (substr($adv['link'], 0, 5) != 'http:') {
			$adv['link'] = "http://" . $adv['link'];
		}
	}
	unset($adv);
	$condition = '';
	if (!empty($_GPC['type'])) {
		switch ($_GPC['type']) {
			case 'isnew':
				$condition .= " AND isnew = 1";
				break;
			case 'ishot':
				$condition .= " AND ishot = 1";
				break;
			case 'isrecommand':
				$condition .= " AND isrecommand = 1";
				break;
			default:
				$condition .= " AND isdiscount = 1";
				break;
		}
	}
	if (!empty($_GPC['gid'])) {
		$cid = intval($_GPC['gid']);
		$condition .= " AND fk_typeid = '{$cid}'";
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$goodses = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND isshow = 1 $condition ORDER BY displayorder desc LIMIT " . (1 - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wz_tuan_goods') . "WHERE uniacid = '{$_W['uniacid']}' AND isshow = 1 $condition ");
	$pager = pagination($total, $pindex, $psize);
	include $this -> template('simpindex');
} else {
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	if ($operation == 'display') {
		$category = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		foreach ($category as $key => $value) {
			if (!empty($value['description'])) {
				$pindex = max(1, intval($_GPC['page']));
				$psize = intval($value['description']);
				$sqlmess = " LIMIT 0" . ',' . $psize;
			}
			$category[$key]['goodses'] = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND isshow = 1 AND fk_typeid = '{$value['id']}' ORDER BY displayorder DESC, id desc" . $sqlmess);
		}
		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('wz_tuan_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		unset($adv);
	}
	if ($operation == 'search') {
		$condition = '';
		if (!empty($_GPC['gid'])) {
			$cid = intval($_GPC['gid']);
			$condition .= " AND fk_typeid = '{$cid}'";
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND gname LIKE '%{$_GPC['keyword']}%'";
		}
		$goodses = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND isshow = 1 $condition ");
	}
	include $this -> template('index');
}
?>