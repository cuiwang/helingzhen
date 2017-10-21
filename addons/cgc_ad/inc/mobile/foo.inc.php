<?php
global $_W, $_GPC;
$weid = $_W['uniacid'];
//这个判断入口的，不要改变位置，不然报错
$_SESSION['enter_control'] = true;
$quan = $this->get_quan();
$member = $this->get_member();
$mid = $member['id'];
$quan_id = $quan['id'];
$id = $_GPC['id'];
$op = empty ($_GPC['op']) ? "display" : $_GPC['op'];


//组团拉人
if ($op == 'group_detail') {
	$pid = $_GPC['pid'];
	$form = $_GPC['form'];
	$id = $_GPC['id'];
	header("location:" . $this->createMobileUrl($form, array (
		"quan_id" => $quan_id,
		'pid' => $pid,
		'src' => $pid,
		'id' => $id
	)));
	exit ();
}

if ($op == 'guanzhu') {
	if ($_GPC['code'] != md5($quan['guanzhu_key'])) {
		$this->returnError('联系系统管理员设置入口');
	}
	header("location:" . $this->createMobileUrl("index", array (
		"quan_id" => $quan_id
	)));
	exit ();
}

if ($op == 'location') {
	$latitude = $_GPC['latitude'];
	$longitude = $_GPC['longitude'];
	if (empty ($latitude) || empty ($longitude)) {
		$this->returnError('位置获取失败');
	}
	// 百度反地址查询接口
	$config = $this->settings;

	$config['bd_ak'] = empty ($config['bd_ak']) ? '0E6si87VUIfCrFX3KYHzewtKsNr2keD2' : $config['bd_ak'];
	$url = "http://api.map.baidu.com/geocoder/v2/?ak=" . $config['bd_ak'] . "&location=" . $latitude . "," . $longitude . "&output=json&pois=0";
	load()->func('communication');
	$response = ihttp_get($url);
	if (!is_error($response)) {
		$data = @ json_decode($response['content'], true);
		if (empty ($data) || $data['status'] != 0) {
			$this->returnError('位置获取失败：' . $data['message'] . '(' . $data['status'] . ')');
		} else {
			$data = $data['result'];
			$city = '';
			$district = "";
			$province = "";
			if (!empty ($data['addressComponent'])) {
				$city = $data['addressComponent']['city'];
				$province = $data['addressComponent']['province'];
				$district = $data['addressComponent']['district'];
			}
			if (empty ($city)) {
				$this->returnError('城市获取失败');
			}

			$city2 = $province . "|" . $city . "|" . $district;

			pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET last_city=:city where id=:id', array (
				':id' => $mid,
				':city' => $city2
			));
			
		   $temp_city = 0;
           if(!empty($quan['city'])){           
			$quan['city'] = str_replace("市", "", $quan['city']);
			$quan['city'] = str_replace("或", "|", $quan['city']);
			$city2 = str_replace("市", "", $city2);
			$member_city_arr = explode('|', $city2);
			$city_arr = explode('|', $quan['city']);
			$temp_city = 0;
			foreach ($member_city_arr as $value) {
				if (in_array($value, $city_arr)) {
					$temp_city = 1;
					break;
				}
			}
			
           } else {
            $temp_city =1;
           }
			//广告地区限制
			$adv_id = intval($_GPC['id']);
			if (!empty ($adv_id)) {
				$temp_city = 0;
				$adv = $this->get_adv();
				
				if (!empty($adv)) {
					$adv['city'] = str_replace("市", "", $adv['city']);
					$adv['city'] = str_replace("或", "|", $adv['city']);
					$city_arr = explode('|', $adv['city']);
					foreach ($member_city_arr as $value) {
						if (in_array($value, $city_arr) || strexists($member['last_city'],$value)) {
							$temp_city = 1;
							break;
						}
					}
				}
				
				if ($temp_city == 1) {
					$this->returnSuccess('城市定位成功', 1);
				}
				$this->returnSuccess('城市定位出错', -1);
			}
			//广告地区限制

			if ($temp_city == 1) {
				$this->returnSuccess('城市定位成功', 1);
			}
			$this->returnSuccess('城市定位成功', -2);
		}
	} else {
		$this->returnError('位置获取失败，请重试');
	}
	exit();

}

if ($op == 'help') {
	$subscribe = $member['follow'];
	$from_user = $member['openid'];
	$pid = $_GPC['pid'];
	$form = $_GPC['form'];
	$id = $_GPC['id'];
	$src = $_GPC['src'];
	$temp_help = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_help') . " WHERE weid=" . $weid . " AND quan_id=" . $quan_id . " AND mid=" . $pid . " AND helper_id=" . $mid);
	if (empty ($temp_help)) {
		$data10 = array (
			'weid' => $weid,
			'quan_id' => $quan_id,
			'mid' => $pid,
			'helper_id' => $mid,
			'create_time' => TIMESTAMP,

			
		);
		pdo_insert("cgc_ad_help", $data10);
		$ret = pdo_update("cgc_ad_member", array (
			'rob_next_time' => time()
		), array (
			'id' => $pid
		));
	}

	header("location:" . $this->createMobileUrl($form, array (
		"quan_id" => $quan_id,
		'pid' => $pid,
		'id' => $id,
		'src' => $src
	)));
	exit();

}

if ($op == 'qr_get_hb'){
	$form = empty ($_GPC['form']) ? "qr_detail" : $_GPC['form'];
	header("location:" . $this->createMobileUrl($form, array (
		"quan_id" => $quan_id,
		'id' => $_GPC['id'],
		'op'=>$_GPC['op']
	)));
	exit();
}

if ($op == 'display') {
	$pid = $_GPC['pid'];
	$form = empty ($_GPC['form']) ? "index" : $_GPC['form'];
	header("location:" . $this->createMobileUrl($form, array (
		"quan_id" => $quan_id,
		'pid' => $pid,
		'id' => $id,
	)));
	exit ();
} else {
  $pid = $_GPC['pid'];
  $form = empty ($_GPC['form']) ? "index" : $_GPC['form'];
  header("location:" . $this->createMobileUrl($form, array (
		"quan_id" => $quan_id,
		'pid' => $pid,
		'id' => $id,
		'op' => $_GPC['op'],
		'task_id' => $_GPC['task_id'],
	)));
	exit ();

}
