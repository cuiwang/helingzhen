<?php
/**
 * [WECHAT 2017]
 
 */
defined('IN_IA') or exit('Access Denied');
$_W['page']['title'] = '商家设置-粉丝营销';

uni_user_permission_check('activity_store_list');

$dos = array('display', 'post','delete', 'import', 'sync');
$do = in_array($do, $dos) ? $do : 'display';

if($do == 'post') {
	$id = intval($_GPC['id']);
	if($id > 0) {
		$location = pdo_get('activity_stores', array('id' => $id, 'uniacid' => $_W['uniacid']));
		if (empty($location)) {
			message('商家不存在', referer(), 'info');
		}
		if (COUPON_TYPE == WECHAT_COUPON) {
			$location_info = $coupon_api->LocationGet($location['location_id']);
			if(is_error($location_info)) {
				message("从微信获取门店信息失败,错误详情:{$location_info['message']}", referer(), 'error');
			}
			$update_status = $location_info['business']['base_info']['update_status'];
			$location['open_time_start'] = '8:00';
			$location['open_time_end'] = '24:00';
			$open_time = explode('-', $location['open_time']);
			if(!empty($open_time)) {
				$location['open_time_start'] = $open_time[0];
				$location['open_time_end'] = $open_time[1];
			}
			$location['category'] = iunserializer($location['category']);
			$location['categorys'] = $location['category'];
			$location['category'] = rtrim(implode('-', $location['category']), '-');
			$location['address'] = $location['provice'].$location['city'].$location['district'].$location['address'];
			$location['baidumap'] = array('lng' => $location['longitude'], 'lat' => $location['latitude']);
			$photo_lists = iunserializer($location['photo_list']);
			$location['photo_list'] = array();
			if(!empty($photo_lists)) {
				foreach($photo_lists as $li) {
					if(!empty($li['photo_url'])) {
						$location['photo_list'][] = $li['photo_url'];
					}
				}
			}
		} else {
			$location['category'] = iunserializer($location['category']);
			$location['photo_list'] = iunserializer($location['photo_list']);
			foreach ($location['photo_list'] as &$photo) {
				$photo = $photo['photo_url'];
			}
			$location['opentime'] = explode('-', $location['open_time']);
			$location['open_time_start'] = $location['opentime'][0];
			$location['open_time_end'] = $location['opentime'][1];
			$item = $location;
		}
	}else {
		$item['open_time_start'] = '8:00';
		$item['open_time_end'] = '24:00';
	}
	if(checksubmit('submit')) {
		if (COUPON_TYPE == WECHAT_COUPON && $id) {
			if(empty($location['location_id'])) {
				message('门店正在审核中或审核未通过，不能更新门店信息', referer(), 'error');
			}
			if($update_status == 1) {
				message('服务信息正在更新中，尚未生效，不允许再次更新', referer(), 'error');
			}
			$data['telephone'] = trim($_GPC['telephone']) ? trim($_GPC['telephone']) : message('门店电话不能为空');
			if(empty($_GPC['photo_list'])) {
				message('门店图片不能为空');
			} else {
				foreach($_GPC['photo_list'] as $val) {
					if(empty($val)) continue;
					$data['photo_list'][] = array('photo_url' => $val);
				}
			}
			$data['avg_price'] = intval($_GPC['avg_price']);
			if(empty($_GPC['open_time_start']) || empty($_GPC['open_time_end'])) {
				message('营业时间不能为空');
			} else {
				$data['open_time'] = $_GPC['open_time_start'] . '-' . $_GPC['open_time_end'];
			}
			$data['recommend'] = urlencode(trim($_GPC['recommend']));
			$data['special'] = trim($_GPC['special']) ? urlencode(trim($_GPC['special'])) : message('特色服务不能为空');
			$data['introduction'] = urlencode(trim($_GPC['introduction']));
			$data['poi_id'] = $location['location_id'];
			$status = $coupon_api->LocationEdit($data);
			if(is_error($status)) {
				message($status['message'], '', 'error');
			} else {
				unset($data['poi_id']);
				$data['photo_list'] = iserializer($data['photo_list']);
				$data['recommend'] = $_GPC['recommend'];
				$data['special'] = trim($_GPC['special']);
				$data['introduction'] = trim($_GPC['introduction']);
				pdo_update('activity_stores', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
				message('门店信息已提交，等待微信审核', url('activity/store'), 'success');
			}
		}
		$store_data = array(
			'business_name' => trim($_GPC['business_name']),
			'branch_name' => trim($_GPC['branch_name']),
			'category' => array(
				'cate' => trim($_GPC['class']['cate']),
				'sub' => trim($_GPC['class']['sub']),
				'clas' => trim($_GPC['class']['clas'])
			),
			'province' => trim($_GPC['reside']['province']),
			'city' => trim($_GPC['reside']['city']),
			'district' => trim($_GPC['reside']['district']),
			'address' => trim($_GPC['address']),
			'longitude' => trim($_GPC['baidumap']['lng']),
			'latitude' => trim($_GPC['baidumap']['lat']),
			'avg_price' => intval($_GPC['avg_price']),
			'telephone' => trim($_GPC['telephone']),
			'open_time' => trim($_GPC['open_time_start']). '-'.trim($_GPC['open_time_end']),
			'recommend' => trim($_GPC['recommend']),
			'special' => trim($_GPC['special']),
			'introduction' => trim($_GPC['introduction']),
		);
		if (empty($store_data['business_name'])) {
			message('门店名称不能为空');
		}
		if(empty($store_data['category']['cate'])) {
			message('门店类目不能为空');
		}
		if (empty($store_data['province']) || empty($store_data['city']) || empty($store_data['district']) || empty($store_data['address'])) {
			message('请设置门店所在省、市、区及详细地址');
		}
		if (empty($store_data['longitude']) || empty($store_data['latitude'])) {
			message('请选择门店所在地理位置坐标');
		}
		if (empty($store_data['telephone'])) {
			message('门店电话不能为空');
		}
		if(empty($store_data['open_time'])) {
			message('请设置营业时间');
		}
		if(empty($_GPC['photo_list'])) {
			message('门店图片不能为空');
		}
		foreach($_GPC['photo_list'] as $photourl) {
			if (!empty($photourl)) {
				$store_data['photo_list'][] = array('photo_url' => $photourl);
			}
		}
		if (!empty($id)) {
				$insert = $store_data;
				unset($insert['sid']);
				$insert['source'] = COUPON_TYPE;
				$insert['category'] = iserializer($insert['category']);
				$insert['photo_list'] = iserializer($insert['photo_list']);
				pdo_update('activity_stores',$insert,array('id' => $id, 'uniacid' => $_W['uniacid']));
			message('门店信息更新成功', url('activity/store'), 'success');
		} else {
			$insert = $store_data;
			$insert['uniacid'] = $_W['uniacid'];
			$insert['source'] = COUPON_TYPE;
			unset($insert['sid']);
			$insert['category'] = iserializer($insert['category']);
			$insert['photo_list'] = iserializer($insert['photo_list']);
			$insert['status'] = 1;
			$result = pdo_insert('activity_stores', $insert);
			if (COUPON_TYPE == WECHAT_COUPON) {
				$insert['status'] = 2;
				$store_data['sid'] = pdo_insertid();
				$status = $coupon_api->LocationAdd($store_data);
				if(is_error($status)) {
					pdo_delete('activity_stores', array('uniacid' => $_W['uniacid'], 'id' => $store_data['sid']));
					message($status['message'], '', 'error');
				}
			}
		}
		message('门店添加成功', url('activity/store'), 'success');
	}
}

if($do == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$limit = 'ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ", {$psize}";
	$total  = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('activity_stores')." WHERE uniacid = :uniacid AND source = :source", array(':uniacid' => $_W['uniacid'], ':source' => COUPON_TYPE));
	$list = pdo_getslice('activity_stores',  array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array($pindex, $psize));
	$pager = pagination($total,$pindex,$psize);
	foreach($list as &$key) {
		$key['category'] = iunserializer($key['category']);
		$key['category_'] = implode('-', $key['category']);
	}
}
if($do =='delete') {
	$id = intval($_GPC['id']);
	$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_clerks') . ' WHERE uniacid = :uniacid AND storeid = :id', array(':id' => $id, ':uniacid' => $_W['uniacid']));
	$count = intval($count);
	if($count > 0) {
		message("该门店下有{$count}名店员.请将店员变更到其他门店后,再进行删除操作", referer(), 'error');
	}
	pdo_delete('activity_stores',array('id' => $id, 'uniacid' => $_W['uniacid']));
	if (COUPON_TYPE == WECHAT_COUPON) {
		$location = pdo_fetch('SELECT status,location_id FROM ' . tablename('activity_stores') . ' WHERE uniacid = :aid AND id = :id', array(':aid' => $_W['uniacid'], ':id' => $id));
		if(!empty($location['location_id'])) {
			$status = $coupon_api->LocationDel($location['location_id']);
		}
		if(is_error($status)) {
			message("删除本地门店数据成功<br>通过微信接口删除微信门店数据失败,请登陆微信公众平台手动删除门店<br>错误原因：{$status['message']}", url('activity/store/list'), 'error');
		}
	}
	message('删除成功',referer(), 'success');
}
if($do == 'import') {
	$begin = intval($_GPC['begin']);
	$data = $coupon_api->LocationBatchGet(array('begin' => $begin));
	if(is_error($data)) {
		message($data['message'], referer(), 'error');
	}
	if (empty($begin)) {
		pdo_update('activity_stores', array('status' => 3), array('uniacid' => $_W['uniacid'], 'source' => 2));
	}
	$location = $data['business_list'];
	$status2local = array('', 3, 2, 1, 3);
	if(!empty($location)) {
		foreach($location as $row) {
			$isexist = array();
			$store_info = array();
			$store_info = $row['base_info'];
									if(!empty($store_info['sid'])) {
				$select_type = 'sid';
				$isexist = pdo_getcolumn('activity_stores', array('uniacid' => $_W['uniacid'], 'id' => $store_info['sid']), 'id');
			}
			if(empty($isexist)) {
				$select_type = 'poi_id';
				$isexist = pdo_get('activity_stores', array('uniacid' => $_W['uniacid'], 'location_id' => $store_info['poi_id']));
			}

			$store_info['uniacid'] = $_W['uniacid'];
			$store_info['status'] = $status2local[$store_info['available_state']];
			$store_info['location_id'] = $store_info['poi_id'];
			$category_temp = explode(',', $store_info['categories'][0]);
			$store_info['category'] = iserializer(array('cate' => $category_temp[0], 'sub' => $category_temp[1], 'clas' => $category_temp[2]));
			$store_info['photo_list'] = iserializer($store_info['photo_list']);
			$store_info['source'] = 2;
			$storeid = $select_type == 'poi_id' ? $store_info['poi_id'] : $store_info['sid'];
			unset($store_info['categories'], $store_info['poi_id'], $store_info['update_status'], $store_info['available_state'],$store_info['offset_type'], $store_info['type'], $store_info['sid']);
			if(empty($isexist)) {
				pdo_insert('activity_stores', $store_info);
			} else {
				if ($select_type == 'poi_id') {
					$result = pdo_update('activity_stores', $store_info, array('uniacid' => $_W['uniacid'], 'location_id' => $storeid));
				} else {
					$result = pdo_update('activity_stores', $store_info, array('uniacid' => $_W['uniacid'], 'id' => $storeid));
				}
				$result = pdo_update('activity_stores', $store_info, array('uniacid' => $_W['uniacid'], 'id' => $storeid));
			}
		}
		message('正在导入微信门店,请不要关闭浏览器...', url('activity/store/import', array('begin' => $begin + 50)), 'success');
	}
	message('导入门店成功', url('activity/store/display'), 'success');
}
if($do == 'sync') {
	$type = trim($_GPC['type']);
	if ($type == '1') {
		$cachekey = "storesync:{$_W['uniacid']}";
		$cache = cache_delete($cachekey);
	}
	activity_store_sync();
	message(error(0, '更新门店信息成功'), url('activity/store'), 'ajax');
}
template('activity/store');