<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('edit', 'delete', 'deleteall', 'showall', 'status', 'query', 'getbusiness', 'display');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$where = ' WHERE `weid` = :weid';
	$params = array(':weid' => $_W['uniacid']);
	
	if (!empty($_GPC['keywords'])) {
		$where .= ' AND `title` LIKE :title';
		$params[':title'] = "%{$_GPC['keywords']}%";
	}
	$sql = 'SELECT COUNT(*) FROM ' . tablename('storex_bases') . $where;
	$total = pdo_fetchcolumn($sql, $params);
	
	if ($total > 0) {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$list = pdo_getall('storex_bases', array('weid' => $_W['uniacid'], 'title LIKE' => "%{$_GPC['keywords']}%"), array(), '', 'displayorder DESC', ($pindex - 1) * $psize . ',' . $psize);
		$pager = pagination($total, $pindex, $psize);
		if (!empty($list)) {
			foreach ($list as $key => &$value) {
				$value['store_entry'] = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=wn_storex&do=display&id=' . $value['id'] . '#/StoreIndex/' . $value['id'];
				$value['mc_entry'] = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=wn_storex&do=display&id=' . $value['id'] . '#/Home/Index';
			}
			unset($value);
		}
	}
	
	if (!empty($_GPC['export'])) {
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF";
		/* 输出表头 */
		$filter = array(
			'title' => '酒店名称',
			'level' => '星级',
			'roomcount' => '房间数',
			'phone' => '电话',
			'status' => '状态',
		);
		foreach ($filter as $key => $value) {
			$html .= $value . "\t,";
		}
		$html .= "\n";
		if (!empty($list)) {
			$status = array('隐藏', '显示');
			foreach ($list as $key => $value) {
				foreach ($filter as $index => $title) {
					if ($index != 'status') {
						$html .= $value[$index] . "\t, ";
					} else {
						$html .= $status[$value[$index]] . "\t, ";
					}
				}
				$html .= "\n";
			}
		}
		/* 输出CSV文件 */
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=全部数据.csv");
		echo $html;
		exit();
	}
	include $this->template('hotel');
}

if ($op == 'edit') {
	$id = intval($_GPC['id']);
	$hotel_level_config = array(5 => '五星级酒店', 4 => '四星级酒店', 3 => '三星级酒店', 2 => '两星级以下', 15 => '豪华酒店', 14 => '高档酒店', 13 => '舒适酒店', 12 => '经济型酒店', );
	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('店铺名称不能是空！', '', 'error');
		}
		if (!is_numeric($_GPC['distance'])) {
			message('距离必须是数字！', '', 'error');
		}
		$common_insert = array(
			'weid' => $_W['uniacid'],
			'title' => trim($_GPC['title']),
			'store_type' => intval($_GPC['store_type']),
			'thumb'=>$_GPC['thumb'],
			'address' => $_GPC['address'],
			'location_p' => $_GPC['district']['province'],
			'location_c' => $_GPC['district']['city'],
			'location_a' => $_GPC['district']['district'],
			'lng' => $_GPC['baidumap']['lng'],
			'lat' => $_GPC['baidumap']['lat'],
			'phone' => $_GPC['phone'],
			'mail' => $_GPC['mail'],
			'displayorder' => $_GPC['displayorder'],
			'timestart' => $_GPC['timestart'],
			'timeend' => $_GPC['timeend'],
			'description' => $_GPC['description'],
			'content' => $_GPC['content'],
			'store_info' => $_GPC['store_info'],
			'traffic' => $_GPC['traffic'],
			'status' => $_GPC['status'],
			'distance' => intval($_GPC['distance']),
			'skin_style' => trim($_GPC['skin_style']),
			'category_set' => intval($_GPC['category_set']),
		);
		$common_insert['thumbs'] = empty($_GPC['thumbs']) ? '' : iserializer($_GPC['thumbs']);
		$common_insert['detail_thumbs'] = empty($_GPC['detail_thumbs']) ? '' : iserializer($_GPC['detail_thumbs']);
		if ($_GPC['store_type']) {
			$common_insert['extend_table'] = 'storex_hotel';
			$insert = array(
				'weid' => $_W['uniacid'],
				'sales' => $_GPC['sales'],
				'level' => $_GPC['level'],
				'brandid' => $_GPC['brandid'],
				'businessid' => $_GPC['businessid'],
			);
			if ($_GPC['device']) {
				$devices = array();
				foreach ($_GPC['device'] as $key => $device) {
					if ($device != '') {
						$devices[] = array('value' => $device, 'isshow' => intval($_GPC['show_device'][$key]));
					}
				}
				$insert['device'] = empty($devices) ? '' : iserializer($devices);
			}
		}
		if (empty($id)) {
			pdo_insert('storex_bases', $common_insert);
			if ($_GPC['store_type']) {
				$insert['store_base_id'] = pdo_insertid();
				pdo_insert('storex_hotel', $insert);
			}
		} else {
			if ($common_insert['store_type'] == 1 && $common_insert['category_set'] == 2) {
				pdo_update('storex_room', array('status' => 0), array('hotelid'=> $id, 'weid' => $_W['uniacid'], 'is_house' => 2));
			} elseif ($common_insert['store_type'] == 1 && $common_insert['category_set'] == 1) {
				pdo_update('storex_room', array('status' => 1), array('hotelid'=> $id, 'weid' => $_W['uniacid'], 'is_house' => 2));
			}
			pdo_update('storex_bases', $common_insert, array('id' => $id));
			if ($_GPC['store_type']) {
				pdo_update($common_insert['extend_table'], $insert, array('store_base_id' => $id));
			}
		}
		message('店铺信息保存成功!', $this->createWebUrl('storemanage'), 'success');
	}
	$storex_bases = pdo_get('storex_bases', array('id' => $id));
	$item = pdo_get('storex_hotel', array('store_base_id' => $id));
	if (empty($item['device'])) {
		$devices = array(
			array('isdel' => 0, 'value' => '有线上网'),
			array('isdel' => 0, 'isshow' => 0, 'value' => 'WIFI无线上网'),
			array('isdel' => 0, 'isshow' => 0, 'value' => '可提供早餐'),
			array('isdel' => 0, 'isshow' => 0, 'value' => '免费停车场'),
			array('isdel' => 0, 'isshow' => 0, 'value' => '会议室'),
			array('isdel' => 0, 'isshow' => 0, 'value' => '健身房'),
			array('isdel' => 0, 'isshow' => 0, 'value' => '游泳池')
		);
	} else {
		$devices = iunserializer($item['device']);
	}
	
	//品牌
	$brands = pdo_getall('storex_brand', array('weid' => $_W['uniacid']));
	
	$sql = 'SELECT `title` FROM ' . tablename('storex_business') . ' WHERE `weid` = :weid AND `id` = :id';
	$params[':id'] = intval($item['businessid']);
	$params[':weid'] = intval($_W['uniacid']);
	$item['hotelbusinesss'] = pdo_fetchcolumn($sql, $params);
	$storex_bases['thumbs'] =  iunserializer($storex_bases['thumbs']);
	$storex_bases['detail_thumbs'] =  iunserializer($storex_bases['detail_thumbs']);
	if ($id) {
		$item = array_merge($item, $storex_bases);
	}
	include $this->template('hotel_form');
}

if ($op == 'delete') {
	$id = intval($_GPC['id']);
	$store = pdo_get('storex_bases', array('id' => $id), array('store_type'));
	if ($store['store_type'] == 1) {
		pdo_delete('storex_room', array('hotelid' => $id, 'weid' => $_W['uniacid']));
	} else {
		pdo_delete('storex_goods', array('store_base_id' => $id, 'weid' => $_W['uniacid']));
	}
	pdo_delete('storex_bases', array('id' => $id, 'weid' => $_W['uniacid']));
	pdo_delete('storex_categorys', array('store_base_id' => $id, 'weid' => $_W['uniacid']));
	message('店铺信息删除成功!', referer(), 'success');
}

if ($op == 'deleteall') {
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		$id = intval($_GPC['id']);
		$store = pdo_get('storex_bases', array('id' => $id), array('store_type'));
		if ($store['store_type'] == 1) {
			pdo_delete('storex_room', array('hotelid' => $id, 'weid' => $_W['uniacid']));
		} else {
			pdo_delete('storex_goods', array('store_base_id' => $id, 'weid' => $_W['uniacid']));
		}
		pdo_delete('storex_bases', array('id' => $id, 'weid' => $_W['uniacid']));
		pdo_delete('storex_categorys', array("store_base_id" => $id, 'weid' => $_W['uniacid']));
	}
	$this->web_message('店铺信息删除成功！', '', 0);
	exit();
}

if ($op == 'showall') {
	if ($_GPC['show_name'] == 'showall') {
		$show_status = 1;
	} else {
		$show_status = 0;
	}
	foreach ($_GPC['idArr'] as $k => $id) {
		$id = intval($id);
		if (!empty($id)) {
			pdo_update('storex_bases', array('status' => $show_status), array('id' => $id));
		}
	}
	$this->web_message('操作成功！', '', 0);
	exit();
}

if ($op == 'status') {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		message('抱歉，传递的参数错误！', '', 'error');
	}
	$temp = pdo_update('storex_bases', array('status' => $_GPC['status']), array('id' => $id));
	if ($temp == false) {
		message('抱歉，刚才操作数据失败！', '', 'error');
	} else {
		message('状态设置成功！', referer(), 'success');
	}
}

if ($op == 'query') {
	$kwd = trim($_GPC['keyword']);
	$ds = pdo_getall('storex_hotel', array('weid' => $_W['uniacid'], 'title LIKE' => "%{$kwd}%"), array('id','title','description','thumb'));
	foreach ($ds as &$value) {
		$value['thumb'] = tomedia($value['thumb']);
	}
	unset($value);
	include $this->template('query');
}

if ($op == 'getbusiness') {
	$kwd = trim($_GPC['keyword']);
	$ds = pdo_getall('storex_business', array('weid' => $_W['uniacid'], 'title LIKE' => "%{$kwd}%"));
	include $this->template('business_query');
	exit();
}