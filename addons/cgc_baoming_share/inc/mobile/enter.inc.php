<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
global $_W, $_GPC;

$op = !empty($_GPC['op']) ? $_GPC['op'] : "display";
$id = $_GPC['id'];
$user_id = $_GPC['user_id'];
$cgc_baoming_activity = new cgc_baoming_activity();
$cgc_baoming_user = new cgc_baoming_user();

if (!empty($id)) {
	$activity = $cgc_baoming_activity->getOne($id);
} else {
	message("活动id不得为空");
}

if (empty($activity)) {
	message("不存在此活动" . $id);
}


$settings = $this->module['config'];

$uniacid = $_W['uniacid'];
$modulename = $this->modulename;
$openid = empty($_W['openid']) ? $_GPC['fromuser'] : $_W['openid'];
$user_json = getFromUser($settings, $modulename);

$userinfo = json_decode($user_json, true);

$pid = getparent($id);

$url = get_random_domain($settings['zdy_domain']);

$form = empty($_GPC['form']) ? "login" : $_GPC['form'];

if(!empty($user_id)){
	//$this->forward2($id,$user_id, $_W['openid'], $activity);
}


$user = $cgc_baoming_user->selectByUser($userinfo['openid'], $id);

if (empty($user_json)) {
	exit("user_json error");
}


if (!empty($url)) {
	$url = $url . '/app/' . murl('entry', array('m' => $this->module['name'], 'do' => $form, 'id' => $id, 'sign' => time(), 'ticket' => $userinfo['openid'], 'user_json' => $user_json, 'pid' => $pid, 'fromuser' => $openid, 'follow' => $_GPC['follow'],'user_id'=>$user_id));
} else {
	if ($activity['activity_type'] == '2' && $form=="login") {
		$url = $this->createMobileUrl('pay', array('id' => $id, 'sign' => time(), 'ticket' => $userinfo['openid'], 'user_id' => $_GPC['user_id'], 'fromuser' => $openid, 'follow' => $_GPC['follow'],'user_id'=>$user_id));
	} else if ($activity['activity_type'] == '1'  && $form=="login") {
		$url = $this->createMobileUrl('coupon', array('id' => $id, 'sign' => time(), 'ticket' => $userinfo['openid'],'user_id' => $_GPC['user_id'],  'fromuser' => $openid, 'follow' => $_GPC['follow'],'user_id'=>$user_id));
	} else if ($activity['activity_type'] == '0' && $user['zj_status']=='1' && $_GPC['flag']=='1'){
		$url = $this->createMobileUrl('luck', array('id' => $id, 'sign' => time(), 'ticket' => $userinfo['openid'], 'fromuser' => $openid, 'follow' => $_GPC['follow'],'user_id' => $_GPC['user_id'], 'user_id'=>$user_id));
	}else {
		$url = $this->createMobileUrl($form, array('id' => $id, 'sign' => time(), 'ticket' => $userinfo['openid'], 'fromuser' => $openid, 'follow' => $_GPC['follow'],'user_id'=>$user_id));
	}
	
}
header("location:".$url);
	    
      
 



        
     