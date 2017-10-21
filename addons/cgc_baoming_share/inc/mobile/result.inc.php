<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
global $_W, $_GPC;

$op = !empty ($_GPC['op']) ? $_GPC['op'] : "display";

$settings = $this->module['config'];
$uniacid = $_W['uniacid'];
$modulename = $this->modulename;
$userinfo = getFromUser($settings, $modulename);
$userinfo = json_decode($userinfo, true);

$from_user = $userinfo['openid'];

$id = $_GPC['id'];
if ($op == "display") {
	$cgc_baoming_activity = new cgc_baoming_activity();

	if (!empty ($id)) {
		$activity = $cgc_baoming_activity->getOne($id);
	}

	$cgc_baoming_user = new cgc_baoming_user();
	/*	if (empty($activity['yq_mode']) && (empty($activity['my_mode']))) {
			$user = $cgc_baoming_user->selectByUser($from_user, $id);
		} else {
			//多抽奖码的
			$user = $cgc_baoming_user->selectByConcatUser($from_user, $id);
		}*/

	$record = $cgc_baoming_user->selectRecords($from_user, $id);
	$user = $record[0];

	$cj_num = count($record);

	$activity['share_title'] = str_replace("#nickname#", $user['nickname'], $activity['share_title']);

	$activity['share_desc'] = str_replace("#nickname#", $user['nickname'], $activity['share_desc']);

	$activity['share_url'] = get_share_url($activity, $activity['share_url'], $from_user, $settings);
	
	if (empty ($activity['my_mode'])) {
		if (!empty ($activity['yq_mode'])) {
			include $this->template('result');
		} else {
			include $this->template('oldresult');
		}
	}else if($activity['my_mode']=='2'){
		include $this->template('apple_result');
	}else if($activity['my_mode']=='3'){
		include $this->template('result3');
	}else if($activity['my_mode']=='4'){
		include $this->template('result4');
	} else {
		include $this->template('newresult');
	}
	exit ();
}

//只要分享到朋友圈得到抽奖码即可，其他不管
if ($op == "post") {
	if (empty ($id)) {
		exit (json_encode(array (
			"code" => -5,
			"msg" => "活动不存在"
		)));
	}
	$cgc_baoming_activity = new cgc_baoming_activity();
	$activity = $cgc_baoming_activity->getOne($id);

	if (empty ($activity)) {
		exit (json_encode(array (
			"code" => -6,
			"msg" => "活动不得为空"
		)));
	}
	$cgc_baoming_user = new cgc_baoming_user();
	$user = $cgc_baoming_user->selectByUserShare($from_user, $id);

	if (empty ($user)) {
		exit (json_encode(array (
			"code" => -8,
			"msg" => "用户不存在"
		)));
	}

	if (count($user) >= 2) {
		foreach ($user as $item) {
			if ($item['yq_type'] == 2) {
				exit (json_encode(array (
					"code" => -9,
					"msg" => "你已经分享过！"
				)));
			}
		}
	}

	$info = "分享成功";

	$data = array (
		"uniacid" => $user[0]['uniacid'],
		"activity_id" => $user[0]['activity_id'],
		"openid" => $user[0]['openid'],
		"nickname" => $user[0]['nickname'],
		"headimgurl" => $user[0]['headimgurl'],
		"tel" => $user[0]['tel'],
		"realname" => $user[0]['realname'],
		"addr" => $user[0]['addr'],
		"wechat_no" => $user[0]['wechat_no'],
		"createtime" => TIMESTAMP,

		
	);

	$zj_status = 0;
	$cj_code = getNextAvaliableCjcode($id, $activity['cj_code_start'],$activity);

	$data['cj_code'] = $cj_code;
	$data['share_status'] = 1;
	$data['zj_status'] = $zj_status;
	$data['yq_type'] = 2; //分享朋友圈

	$temp = $cgc_baoming_user->insert($data);

	if (empty ($temp)) {
		exit (json_encode(array (
			"code" => -1,
			"msg" => "新增抽奖码失败"
		)));
	} else {

		exit (json_encode(array (
			"code" => 1,
			"zj_status" => 1,
			"msg" => "分享成功"
		)));
	}
}