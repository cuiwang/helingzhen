<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function menu_languages() {
	$languages = array(
		array('ch'=>'简体中文', 'en'=>'zh_CN'),
		array('ch'=>'繁体中文TW', 'en'=>'zh_TW'),
		array('ch'=>'繁体中文HK', 'en'=>'zh_HK'),
		array('ch'=>'英文', 'en'=>'en'),
		array('ch'=>'印尼', 'en'=>'id'),
		array('ch'=>'马来', 'en'=>'ms'),
		array('ch'=>'西班牙', 'en'=>'es'),
		array('ch'=>'韩国', 'en'=>'ko'),
		array('ch'=>'意大利 ', 'en'=>'it'),
		array('ch'=>'日本', 'en'=>'ja'),
		array('ch'=>'波兰', 'en'=>'pl'),
		array('ch'=>'葡萄牙', 'en'=>'pt'),
		array('ch'=>'俄国', 'en'=>'ru'),
		array('ch'=>'泰文', 'en'=>'th'),
		array('ch'=>'越南', 'en'=>'vi'),
		array('ch'=>'阿拉伯语', 'en'=>'ar'),
		array('ch'=>'北印度', 'en'=>'hi'),
		array('ch'=>'希伯来', 'en'=>'he'),
		array('ch'=>'土耳其', 'en'=>'tr'),
		array('ch'=>'德语', 'en'=>'de'),
		array('ch'=>'法语', 'en'=>'fr')
	);
	return $languages;
}


function menu_get($id) {
	global $_W;
	$id = intval($id);
	if (empty($id)) {
		return array();
	}
	$menu_info = pdo_get('uni_account_menus', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if (!empty($menu_info)) {
		return $menu_info;
	} else {
		return array();
	}
}

function menu_construct_createmenu_data($data_array, $is_conditional = false) {
	$menu = array();
	if (empty($data_array) || empty($data_array['button']) || !is_array($data_array)) {
		return $menu;
	}
	foreach ($data_array['button'] as $button) {
		$temp = array();
		$temp['name'] = preg_replace_callback('/\:\:([0-9a-zA-Z_-]+)\:\:/', create_function('$matches', 'return utf8_bytes(hexdec($matches[1]));'), $button['name']);
		$temp['name'] = urlencode($temp['name']);
		if (empty($button['sub_button'])) {
			$temp['type'] = $button['type'];
			if ($button['type'] == 'view') {
				$temp['url'] = urlencode($button['url']);
			} elseif ($button['type'] == 'click') {
				if (!empty($button['media_id']) && empty($button['key'])) {
					$temp['media_id'] = urlencode($button['media_id']);
					$temp['type'] = 'media_id';
				} elseif (empty($button['media_id']) && !empty($button['key'])) {
					$temp['type'] = 'click';
					$temp['key'] = urlencode($button['key']);
				}
			} elseif ($button['type'] == 'media_id' || $button['type'] == 'view_limited') {
				$temp['media_id'] = urlencode($button['media_id']);
			} elseif ($button['type'] == 'miniprogram') {
				$temp['appid'] = trim($button['appid']);
				$temp['pagepath'] = urlencode($button['pagepath']);
				$temp['url'] = urlencode($button['url']);
			} else {
				$temp['key'] = urlencode($button['key']);
			}
		} else {
			foreach ($button['sub_button'] as $sub_button) {
				$sub_temp = array();
				$sub_temp['name'] = preg_replace_callback('/\:\:([0-9a-zA-Z_-]+)\:\:/', create_function('$matches', 'return utf8_bytes(hexdec($matches[1]));'), $sub_button['name']);
				$sub_temp['name'] = urlencode($sub_temp['name']);
				$sub_temp['type'] = $sub_button['type'];
				if ($sub_button['type'] == 'view') {
					$sub_temp['url'] = urlencode($sub_button['url']);
				} elseif ($sub_button['type'] == 'click') {
					if (!empty($sub_button['media_id']) && empty($sub_button['key'])) {
						$sub_temp['media_id'] = urlencode($sub_button['media_id']);
						$sub_temp['type'] = 'media_id';
					} elseif (empty($sub_button['media_id']) && !empty($sub_button['key'])) {
						$sub_temp['type'] = 'click';
						$sub_temp['key'] = urlencode($sub_button['key']);
					}
				} elseif ($sub_button['type'] == 'media_id' || $sub_button['type'] == 'view_limited') {
					$sub_temp['media_id'] = urlencode($sub_button['media_id']);
				} elseif ($sub_button['type'] == 'miniprogram') {
					$sub_temp['appid'] = trim($sub_button['appid']);
					$sub_temp['pagepath'] = urlencode($sub_button['pagepath']);
					$sub_temp['url'] = urlencode($sub_button['url']);
				} else {
					$sub_temp['key'] = urlencode($sub_button['key']);
				}
				$temp['sub_button'][] = $sub_temp;
			}
		}
		$menu['button'][] = $temp;
	}

	if (empty($is_conditional) || empty($data_array['matchrule']) || !is_array($data_array['matchrule'])) {
		return $menu;
	}

	if($data_array['matchrule']['sex'] > 0) {
		$menu['matchrule']['sex'] = $data_array['matchrule']['sex'];
	}
	if($data_array['matchrule']['group_id'] != -1) {
		$menu['matchrule']['tag_id'] = $data_array['matchrule']['group_id'];
	}
	if($data_array['matchrule']['client_platform_type'] > 0) {
		$menu['matchrule']['client_platform_type'] = $data_array['matchrule']['client_platform_type'];
	}
	if(!empty($data_array['matchrule']['province'])) {
		$menu['matchrule']['country'] = urlencode('中国');
		$menu['matchrule']['province'] = urlencode($data_array['matchrule']['province']);
		if(!empty($data_array['matchrule']['city'])) {
			$menu['matchrule']['city'] = urlencode($data_array['matchrule']['city']);
		}
	}
	if(!empty($data_array['matchrule']['language'])) {
		$inarray = 0;
		$languages = menu_languages();
		foreach ($languages as $key => $value) {
			if(in_array($data_array['matchrule']['language'], $value, true)) {
				$inarray = 1;
				break;
			}
		}
		if($inarray === 1) {
			$menu['matchrule']['language'] = $data_array['matchrule']['language'];
		}
	}

	return $menu;
}


function menu_update_currentself() {
	global $_W;
	$account_api = WeAccount::create();
	$default_menu_info = $account_api->menuCurrentQuery();
	if (is_error($default_menu_info)) {
		return error(-1, $default_menu_info['message']);
	}
	if (empty($default_menu_info['is_menu_open']) || empty($default_menu_info['selfmenu_info'])) {
		return error(-1, '暂无默认菜单或默认菜单未开启，请先创建！<div><a class="btn btn-primary" href="' . url('platform/menu/post', array('type' => MENU_CURRENTSELF)) . '">是</a> &nbsp;&nbsp;<a class="btn btn-default" href="' . referer() . '">否</a></div>');
	}
	$default_menu = $default_menu_info['selfmenu_info'];
	$default_sub_button = array();
	if (!empty($default_menu['button'])) {
		foreach ($default_menu['button'] as $key => &$button) {
			if (!empty($button['sub_button'])) {
				$default_sub_button[$key] = $button['sub_button'];
			}
			ksort($button);
		}
		unset($button);
	}
	ksort($default_menu);
	$wechat_menu_data = base64_encode(iserializer($default_menu));
	$all_default_menus = pdo_getall('uni_account_menus', array('uniacid' => $_W['uniacid'], 'type' => MENU_CURRENTSELF), array('data', 'id'), 'id');
	if (!empty($all_default_menus)) {
		foreach ($all_default_menus as $menus_key => $menu_data) {
			if (empty($menu_data['data'])) {
				continue;
			}
			$single_menu_info = iunserializer(base64_decode($menu_data['data']));
			if (!is_array($single_menu_info) || empty($single_menu_info['button'])) {
				continue;
			}
			foreach ($single_menu_info['button'] as $key => &$single_button) {
				if (!empty($default_sub_button[$key])) {
					$single_button['sub_button'] = $default_sub_button[$key];
				} else {
					unset($single_button['sub_button']);
				}
				ksort($single_button);
			}
			unset($single_button);
			ksort($single_menu_info);
			$local_menu_data = base64_encode(iserializer($single_menu_info));
			if ($wechat_menu_data == $local_menu_data) {
				$default_menu_id = $menus_key;
			}
		}
	}

	if (!empty($default_menu_id)) {
		pdo_update('uni_account_menus', array('status' => STATUS_ON), array('id' => $default_menu_id));
		pdo_update('uni_account_menus', array('status' => STATUS_OFF), array('uniacid' => $_W['uniacid'], 'type' => MENU_CURRENTSELF, 'id !=' => $default_menu_id));
	} else {
		$insert_data = array(
			'uniacid' => $_W['uniacid'],
			'type' => MENU_CURRENTSELF,
			'group_id' => -1,
			'sex' => 0,
			'data' => $wechat_menu_data,
			'client_platform_type' => 0,
			'area' => '',
			'menuid' => 0,
			'status' => STATUS_ON
		);
		pdo_insert('uni_account_menus', $insert_data);
		$insert_id = pdo_insertid();
		pdo_update('uni_account_menus', array('title' => '默认菜单_'.$insert_id), array('id' => $insert_id));
		pdo_update('uni_account_menus', array('status' => STATUS_OFF), array('uniacid' => $_W['uniacid'], 'type' => MENU_CURRENTSELF, 'id !=' => $insert_id));
	}
	return true;
}


function menu_update_conditional() {
	global $_W;
	$account_api = WeAccount::create();
	$conditional_menu_info = $account_api->menuQuery();
	if (is_error($conditional_menu_info)) {
		return error(-1, $conditional_menu_info['message']);
	}
	pdo_update('uni_account_menus', array('status' => STATUS_OFF), array('uniacid' => $_W['uniacid'], 'type' => MENU_CONDITIONAL));
	if (!empty($conditional_menu_info['conditionalmenu'])) {
		foreach ($conditional_menu_info['conditionalmenu'] as $menu) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'type' => MENU_CONDITIONAL,
				'group_id' => isset($menu['matchrule']['tag_id']) ? $menu['matchrule']['tag_id'] : (isset($menu['matchrule']['group_id']) ? $menu['matchrule']['group_id'] : '-1'),
				'sex' => $menu['matchrule']['sex'],
				'client_platform_type' => $menu['matchrule']['client_platform_type'],
				'area' => trim($menu['matchrule']['country']) . trim($menu['matchrule']['province']) . trim($menu['matchrule']['city']),
				'data' => base64_encode(iserializer($menu)),
				'menuid' => $menu['menuid'],
				'status' => STATUS_ON,
			);
			if (!empty($menu['matchrule'])) {
				$menu_id =  pdo_getcolumn('uni_account_menus', array('uniacid' => $_W['uniacid'], 'menuid' => $menu['menuid'], 'type' => MENU_CONDITIONAL), 'id');
			}
			if (!empty($menu_id)) {
				$data['title'] = '个性化菜单_' . $menu_id;
				pdo_update('uni_account_menus', $data, array('uniacid' => $_W['uniacid'], 'id' => $menu_id));
			} else {
				pdo_insert('uni_account_menus', $data);
				$insert_id = pdo_insertid();
				pdo_update('uni_account_menus', array('title' => '个性化菜单_'.$insert_id), array('id' => $insert_id));
			}
		}
	}
	return true;
}


function menu_delete($id) {
	global $_W;
	$menu_info = menu_get($id);
	if (empty($menu_info)) {
		return error(-1, '菜单不存在或已经删除');
	}
	if ($menu_info['status'] == STATUS_OFF) {
		pdo_delete('uni_account_menus', array('uniacid' => $_W['uniacid'], 'id' => $id));
		return error(0, '删除菜单成功！');
	}
	if ($menu_info['type'] == MENU_CONDITIONAL && $menu_info['menuid'] > 0 && $menu_info['status'] != STATUS_OFF) {
		$account_api = WeAccount::create($_W['acid']);
		$result = $account_api->menuDelete($menu_info['menuid']);
		if (is_error($result)) {
			return error(-1, $result['message']);
		}
		pdo_delete('uni_account_menus', array('uniacid' => $_W['uniacid'], 'id' => $id));
	}
	return true;
}


function menu_push($id) {
	global $_W;
	$menu_info = menu_get($id);
	if (empty($menu_info)) {
		return error(-1, '菜单不存在或已删除');
	}
	if ($menu_info['status'] == STATUS_OFF) {
		$post = iunserializer(base64_decode($menu_info['data']));
		if (empty($post)) {
			return error(-1, '菜单数据错误');
		}
		$is_conditional = (!empty($post['matchrule']) && $menu_info['type'] == MENU_CONDITIONAL) ? true : false;
		$menu = menu_construct_createmenu_data($post, $is_conditional);

		$account_api = WeAccount::create();
		$result = $account_api->menuCreate($menu);
		if (is_error($result)) {
			return error(-1, $result['message']);
		}
		if ($menu_info['type'] == MENU_CURRENTSELF) {
			pdo_update('uni_account_menus', array('status' => '1'), array('id' => $menu_info['id']));
			pdo_update('uni_account_menus', array('status' => '0'), array('id !=' => $menu_info['id'], 'uniacid' => $_W['uniacid'], 'type' => MENU_CURRENTSELF));
		} elseif ($menu_info['type'] == MENU_CONDITIONAL) {
						if ($post['matchrule']['group_id'] != -1) {
				$menu['matchrule']['groupid'] = $menu['matchrule']['tag_id'];
				unset($menu['matchrule']['tag_id']);
			}
			$status = pdo_update('uni_account_menus', array('status' => STATUS_ON, 'menuid' => $result), array('uniacid' => $_W['uniacid'], 'id' => $menu_info['id']));
		}
		return true;
	}
		if ($menu_info['status'] == STATUS_ON && $menu_info['type'] == MENU_CONDITIONAL && $menu_info['menuid'] > 0) {
		$account_api = WeAccount::create();
		$result = $account_api->menuDelete($menu_info['menuid']);
		if (is_error($result)) {
			return error(-1, $result['message']);
		} else {
			pdo_update('uni_account_menus', array('status' => STATUS_OFF), array('id' => $menu_info['id']));
			return true;
		}
	}
}