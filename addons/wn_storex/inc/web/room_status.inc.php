<?php

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC;
load()->model('mc');

$ops = array('getDate', 'submitPrice', 'updatelot', 'updatelot_create', 'updatelot_submit');
$op = in_array(trim($_GPC['op']), $ops) ? trim($_GPC['op']) : 'display';

$hotelid = $_GPC['hotelid'];

if ($op == 'getDate') {
	if (empty($_GPC['start']) || empty($_GPC['end'])) {
		die(json_encode(array('result' => 0, 'error' => '请选择时间')));
	}
	$btime = strtotime($_GPC['start']);
	$etime = strtotime($_GPC['end']);
	// 日期列
	$days = ceil(($etime - $btime) / 86400);
	$pagesize = 10;
	$totalpage = ceil($days / $pagesize);
	$page = intval($_GPC['page']);
	if ($page > $totalpage) {
		$page = $totalpage;
	} elseif ($page <= 1) {
		$page = 1;
	}
	$currentindex = ($page - 1) * $pagesize;
	$start = date('Y-m-d', strtotime(date('Y-m-d') . "+$currentindex day"));
	$btime = strtotime($start);
	$etime = strtotime(date('Y-m-d', strtotime("$start +$pagesize day")));
	$date_array = array();
	$date_array[0]['date'] = $start;
	$date_array[0]['day'] = date('j', $btime);
	$date_array[0]['time'] = $btime;
	$date_array[0]['month'] = date('m', $btime);
	for ($i = 1; $i <= $pagesize; $i++) {
		$date_array[$i]['time'] = $date_array[$i - 1]['time'] + 86400;
		$date_array[$i]['date'] = date('Y-m-d', $date_array[$i]['time']);
		$date_array[$i]['day'] = date('j', $date_array[$i]['time']);
		$date_array[$i]['month'] = date('m', $date_array[$i]['time']);
	}
	$list = pdo_getall('storex_room', array('hotelid' => $hotelid, 'weid' => $_W['uniacid'], 'is_house' => 1));
	$sql = "SELECT * FROM " . tablename('storex_room_price');
	$sql .= " WHERE roomdate >= " . $btime;
	$sql .= " AND roomdate < " . ($etime + 86400);
	$room_price = pdo_fetchall($sql);
	foreach ($list as $key => $value) {
		$item = array();
		if (!empty($room_price)) {
			foreach ($room_price as $val) {
				if ($val['roomid'] == $value['id']) {
					$item[] = $val;
				}
			}
		}
		$flag = 0;
		if (!empty($item)) {
			$flag = 1;
		}
		$list[$key]['price_list'] = array();
		if ($flag == 1) {
			for ($i = 0; $i <= $pagesize; $i++) {
				$k = $date_array[$i]['time'];
				foreach ($item as $p_key => $p_value) {
					//判断价格表中是否有当天的数据
					if ($p_value['roomdate'] == $k) {
						$list[$key]['price_list'][$k]['status'] = $p_value['status'];
						if (empty($p_value['num'])) {
							$list[$key]['price_list'][$k]['num'] = '无房';
						} elseif ($p_value['num'] == -1) {
							$list[$key]['price_list'][$k]['num'] = '不限';
						} else {
							$list[$key]['price_list'][$k]['num'] = $p_value['num'];
						}
						$list[$key]['price_list'][$k]['roomid'] = $value['id'];
						$list[$key]['price_list'][$k]['hotelid'] = $hotelid;
						$list[$key]['price_list'][$k]['has'] = 1;
						break;
					}
				}
				//价格表中没有当天数据
				if (empty($list[$key]['price_list'][$k])) {
					$list[$key]['price_list'][$k]['num'] = '不限';
					$list[$key]['price_list'][$k]['status'] = 1;
					$list[$key]['price_list'][$k]['roomid'] = $value['id'];
					$list[$key]['price_list'][$k]['hotelid'] = $hotelid;
				}
			}
		} else {
			//价格表中没有数据
			for ($i = 0; $i <= $pagesize; $i++) {
				$k = $date_array[$i]['time'];
				$list[$key]['price_list'][$k]['num'] = '不限';
				$list[$key]['price_list'][$k]['status'] = 1;
				$list[$key]['price_list'][$k]['roomid'] = $value['id'];
				$list[$key]['price_list'][$k]['hotelid'] = $hotelid;
			}
		}
	}
	$data = array();
	$data['result'] = 1;
	ob_start();
	include $this->template('room_status_list');
	$data['code'] = ob_get_contents();
	ob_clean();
	die(json_encode($data));
}

if ($op == 'submitPrice') {
	$roomid = intval($_GPC['roomid']);
	$price = $_GPC['price'];
	$pricetype = $_GPC['pricetype'];
	$date = $_GPC['date'];
	$roomprice = getRoomPrice($hotelid, $roomid, $date);
	if ($pricetype == 'num') {
		$roomprice['num'] = $_GPC['price'];
	} else {
		$roomprice['status'] = $_GPC['status'];
	}
	if (empty($roomprice['id'])) {
		pdo_insert('storex_room_price', $roomprice);
	} else {
		pdo_update('storex_room_price', $roomprice, array("id" => $roomprice['id']));
	}
	die(json_encode(array('result' => 1, 'hotelid' => $hotelid, 'roomid' => $roomid, 'pricetype' => $pricetype, 'price' => $price)));
}

if ($op == 'updatelot') {
	//批量修改房价
	$startime = time();
	$firstday = date('Y-m-01', time());
	//当月最后一天
	$endtime = strtotime(date('Y-m-d', strtotime("$firstday +1 month -1 day")));
	$rooms = pdo_getall('storex_room', array('hotelid' => $hotelid, 'is_house' => 1));
	include $this->template('room_status_lot');
	exit();
}

if ($op == 'updatelot_create') {
	$rooms = $_GPC['rooms'];
	if (empty($rooms)) {
		die("");
	}
	$days = $_GPC['days'];
	$days_arr = implode(',', $days);
	$rooms_arr = implode(',', $rooms);
	$start = $_GPC['start'];
	$end = $_GPC['end'];
	$list = pdo_getall('storex_room', array('id' => $rooms));
	$num = pdo_getall('storex_room_price', array(), array('num'), 'roomid');
	ob_start();
	include $this->template('room_status_lot_list');
	$data['result'] = 1;
	$data['code'] = ob_get_contents();
	ob_clean();
	die(json_encode($data));
}

if ($op == 'updatelot_submit') {
	$rooms = $_GPC['rooms'];
	$rooms_arr = explode(',', $rooms);
	$days = $_GPC['days'];
	$days_arr = explode(',', $days);
	$nums = $_GPC['num'];
	$statuses = $_GPC['status'];
	$start = strtotime($_GPC['start']);
	$end = strtotime($_GPC['end']);
	foreach ($rooms_arr as $v) {
		for ($time = $start; $time <= $end; $time+=86400) {
			$week = date('w', $time);
			if (in_array($week, $days_arr)) {
				$roomprice = getRoomPrice($hotelid, $v, date('Y-m-d', $time));
				$roomprice['num'] = empty($nums[$v]) ? '-1' : intval($nums[$v]);
				$roomprice['status'] = $statuses[$v];
				if (empty($roomprice['id'])) {
					pdo_insert('storex_room_price', $roomprice);
				} else {
					pdo_update('storex_room_price', $roomprice, array('id' => $roomprice['id']));
				}
			}
		}
	}
	message('批量修改房量房态成功!', $this->createWebUrl('room_status', array('hotelid' => $hotelid)), 'success');
}
$startime = time();
$firstday = date('Y-m-01', time());
//当月最后一天
$endtime = strtotime(date('Y-m-d', strtotime("$firstday +1 month -1 day")));
include $this->template('room_status');