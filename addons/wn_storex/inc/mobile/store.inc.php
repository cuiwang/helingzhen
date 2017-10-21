<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
$ops = array('store_list', 'store_detail', 'store_comment');

$op = in_array($_GPC['op'], $ops) ? trim($_GPC['op']) : 'error';

check_params();

//获取店铺列表
if ($op == 'store_list') {
	$keyword = trim($_GPC['keyword']);
	$setting = get_storex_set();
	$storex_bases = pdo_getall('storex_bases', array('weid' => $_W['uniacid'], 'status' => 1, 'title LIKE' => '%' . $keyword . '%'), array(), '', 'displayorder DESC');
	foreach ($storex_bases as $key => $info) {
		if (!empty($_GPC['lat']) && !empty($_GPC['lng'])) {
			$lat = trim($_GPC['lat']);
			$lng = trim($_GPC['lng']);
			$distance = distanceBetween($info['lng'], $info['lat'], $lng, $lat);
			$distance = round($distance / 1000, 2);
			$storex_bases[$key]['distances'] = $distance;
			if (!empty($info['distance'])) {
				if ($distance > $info['distance']) {
					unset($storex_bases[$key]);
					continue;
				}
			}
		}
		if (!empty($_GPC['city'])) {
			$city = code2city(trim($_GPC['city']));
			if ($city != $info['location_c']) {
				unset($storex_bases[$key]);
				continue;
			}
		}
		$storex_bases[$key]['thumb'] = tomedia($info['thumb']);
		$info['thumbs'] = iunserializer($info['thumbs']);
		$timestart = strexists($info['timestart'],':');
		if ($timestart) {
			$storex_bases[$key]['timestart'] = $info['timestart'];
		} else {
			$storex_bases[$key]['timestart'] = date("G:i", $info['timestart']);
		}
		$timeend = strexists($info['timeend'],':');
		if ($timeend) {
			$storex_bases[$key]['timeend'] = $info['timeend'];
		} else {
			$storex_bases[$key]['timeend'] = date("G:i", $info['timeend']);
		}
		if (!empty($info['thumbs'])) {
			$storex_bases[$key]['thumbs'] = format_url($info['thumbs']);
		}
	}
	if ($setting['version'] == 0) {//单店
		if (!empty($storex_bases) && count($storex_bases) > 1) {
			foreach ($storex_bases as $val) {
				$storex_bases = array();
				$storex_bases[] = $val;
				break;
			}
		}
	}
	$store_list = array();
	$store_list['version'] = $setting['version'];
	$store_list['stores'] = $storex_bases;
	message(error(0, $store_list), '', 'ajax');
}
//获取某个店铺的详细信息
if ($op == 'store_detail') {
	$setting = pdo_get('storex_set', array('weid' => $_W['uniacid']));
	$store_id = intval($_GPC['store_id']);//店铺id
	$store_detail = pdo_get('storex_bases', array('weid' => $_W['uniacid'], 'id' => $store_id));
	if (empty($store_detail)) {
        message(error(-1, '店铺不存在'), '', 'ajax');
    } else {
        if ($store_detail['status'] == 0) {
            message(error(-1, '店铺已隐藏'), '', 'ajax');
        }
    }
	if (!empty($store_detail['store_info'])) {
		$store_detail['store_info'] = htmlspecialchars_decode($store_detail['store_info']);
	}
	$store_detail['thumb'] = tomedia($store_detail['thumb']);
	if (!empty($store_detail['thumbs'])) {
		$store_detail['thumbs'] =  iunserializer($store_detail['thumbs']);
		$store_detail['thumbs'] = format_url($store_detail['thumbs']);
	}
	if (!empty($store_detail['detail_thumbs'])) {
		$store_detail['detail_thumbs'] =  iunserializer($store_detail['detail_thumbs']);
		$store_detail['detail_thumbs'] = format_url($store_detail['detail_thumbs']);
	}
	if ($store_detail['store_type'] == 1) {
		$store_extend_info = pdo_get($store_detail['extend_table'], array('weid' => $_W['uniacid'], 'store_base_id' => $store_id));
		if (!empty($store_extend_info)) {
			unset($store_extend_info['id']);
			if (empty($store_extend_info['device'])) {
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
				$store_extend_info['device'] = iunserializer($store_extend_info['device']);
			}
			$store_detail = array_merge($store_detail, $store_extend_info);
		}
	}
	$store_detail['version'] = $setting['version'];
	message(error(0, $store_detail), '', 'ajax');
}
//获取店铺的所有评论
if ($op == 'store_comment') {
	$id = intval($_GPC['id']);
	$store_detail = pdo_get('storex_bases', array('weid' => $_W['uniacid'], 'id' => $id), array('id', 'store_type'));
	if (!empty($store_detail)) {
		if ($store_detail['store_type'] == 1) {
			$table = 'storex_room';
		} else {
			$table = 'storex_goods';
		}
	} else {
		message(error(-1, '店铺不存在'), '', 'ajax');
	}
	$comments = pdo_fetchall("SELECT c.*,g.id AS gid,g.title FROM " . tablename('storex_comment') ." c LEFT JOIN " . tablename($table) ." g ON c.goodsid = g.id WHERE c.hotelid = :hotelid AND g.weid = :weid ORDER BY c.createtime DESC", array(':hotelid' => $id, ':weid' => $_W['uniacid']));
 	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM" . tablename('storex_comment') . " c LEFT JOIN " . tablename($table) . " g ON c.goodsid = g.id WHERE c.hotelid = :hotelid AND g.weid = :weid", array(':hotelid' => $id, ':weid' => $_W['uniacid']));
	if (!empty($comments)) {
		foreach ($comments as $k => $info) {
			$comments[$k]['createtime'] = date('Y-m-d H:i:s', $info['createtime']);
			$uids[] = $info['uid'];
		}
		$uids = array_unique($uids);
		if (!empty($uids)) {
  			$user_info = pdo_getall('mc_members', array('uid' => $uids), array('uid', 'avatar', 'nickname'), 'uid');
			if (!empty($user_info)) {
				foreach ($user_info as &$val) {
					if (!empty($val['avatar'])) {
						$val['avatar'] = tomedia($val['avatar']);
					}
				}
			}
			foreach ($comments as $key => $infos) {
				$comments[$key]['user_info'] = array();
				if (!empty($user_info[$infos['uid']])) {
					$comments[$key]['user_info'] = $user_info[$infos['uid']];
				}
			}
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$comment_list = array();
	if ($total <= $psize) {
		$comment_list['list'] = $comments;
	} else {
		if ($pindex > 0) {
			$comment_list_array = array_chunk($comments, $psize, true);
			if (!empty($comment_list_array[($pindex - 1)])) {
				foreach ($comment_list_array[($pindex - 1)] as $val) {
					$comment_list['list'][] = $val;
				}
			}
		} else {
			$comment_list['list'] = $comments;
		}
	}
	$comment_list['psize'] = $psize;
	$comment_list['result'] = 1;
	$page_data = get_page_array($total, $pindex, $psize);
	$comment_list['total'] = $total;
	$comment_list['isshow'] = $page_data['isshow'];
	if ($page_data['isshow'] == 1) {
		$comment_list['nindex'] = $page_data['nindex'];
	}
	message(error(0, $comment_list), '', 'ajax');
}