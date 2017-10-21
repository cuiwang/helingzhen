<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
global $_W, $_GPC;

$op = !empty ($_GPC['op']) ? $_GPC['op'] : "display";
$id = $_GPC['id'];
$settings = $this->module['config'];
$STYLE_PATH = constant("STYLE_PATH");
$uniacid = $_W['uniacid'];
$modulename = $this->modulename;
$user_json = getFromUser($settings, $modulename);
$userinfo = json_decode($user_json, true);
$from_user = $userinfo['openid'];
$this->forward($id, $from_user);

$cgc_baoming_activity = new cgc_baoming_activity();
$activity = $cgc_baoming_activity->getOne($id);

$cgc_baoming_user = new cgc_baoming_user();
if (!empty ($activity['join_num'])) {
	$count = $cgc_baoming_user->getTotal("uniacid=$uniacid and activity_id=$id  and cj_code!=''");
	if ($count >= $activity['join_num']) {
		message("参与人数" . $count . "个。超过最大参与人数" . $activity['join_num']);
	}
}

if ($op == "display") {

	if (!empty ($settings['valid_time']) && empty ($_GPC['sign'])) {
		exit ("error valid time1");
	}

	if (!empty ($settings['valid_time']) && !empty ($_GPC['sign'])) {
		if ((time() - intval($_GPC['sign'])) > (intval($settings['valid_time']))) {
			exit ("error valid time");
		}
	}

	if (empty ($activity)) {
		message("活动不得为空");
	}

	$activity['share_title'] = str_replace("#nickname#", $userinfo['nickname'], $activity['share_title']);

	$activity['share_desc'] = str_replace("#nickname#", $userinfo['nickname'], $activity['share_desc']);

	$activity['share_url'] = get_random_domain($activity['share_url']);

	$settings['share_guide_info'] = empty ($activity['share_guide_info']) ? $settings['share_guide_info'] : $activity['share_guide_info'];
	$share_guide = empty ($activity['share_guide']) ? $settings['share_guide'] : $activity['share_guide'];
	if (empty ($share_guide)) {
		$share_guide = "$STYLE_PATH/images/share-guide.jpg?v=1";
	} else {
		$share_guide = tomedia($share_guide);
	}
	include $this->template('share');
	exit ();
}

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
	$user = $cgc_baoming_user->selectByUser($from_user, $id);

	if (empty ($user)) {
		exit (json_encode(array (
			"code" => -8,
			"msg" => "用户不存在"
		)));
	}

	if (!empty ($user['share_status'])) {
		exit (json_encode(array (
			"code" => 1
		)));
	}

	$cj_code = getNextAvaliableCjcode($id, $activity['cj_code_start']);
	//中奖模式
	$zj_status = 0;

	$succ_url = $this->createMobileUrl("succ", array (
		'op' => "display",
		'id' => $_GPC['id'],
		'ticket' => $_GPC['ticket']
	));

	$info = "报名成功";

	if (!empty ($activity['award_mode'])) {
		if (!empty ($activity['award_chance'])) {
			$award_chance = mt_rand(1, 100);
			if (intval($activity['award_chance']) >= $award_chance) {
				$zj_status = 1;
				$info = $activity['award_info'];
				$succ_url = $activity['award_url'];
			} else {
				$info = $activity['not_award_info'];
				$succ_url = $activity['not_award_url'];
			}
		} else {
			$info = $activity['not_award_info'];
			$succ_url = $activity['not_award_url'];
		}
	}

	$data = array (
		"cj_code" => $cj_code,
		"share_status" => 1,
		"zj_status" => $zj_status,
		"parent_id" => $_COOKIE["cgc_baoming_share_parent_" . $uniacid . "_" . $id],

		"createtime" => TIMESTAMP
	);

	$temp = $cgc_baoming_user->modify($user['id'], $data);

	if (empty ($temp)) {
		exit (json_encode(array (
			"code" => -1,
			"msg" => "更新抽奖码失败"
		)));
	} else {
		if ($from_user != $_COOKIE["cgc_baoming_share_parent_" . $uniacid . "_" . $id]) {
			update_parent($activity);
		}
		exit (json_encode(array (
			"code" => 1,
			"zj_status" => 1,
			"succ_url" => $succ_url,
			"info" => $info
		)));
	}
}