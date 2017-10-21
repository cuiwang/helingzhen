<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
global $_W, $_GPC;
session_start();
$op = !empty ($_GPC['op']) ? $_GPC['op'] : "display";
$from_user = $_W['fans']['from_user'];
$settings = $this->module['config'];
$uniacid = $_W['uniacid'];
$id = $_GPC['id'];
$modulename = $this->modulename;

if ($op == "display" && !empty($settings['zdy_domain']) && empty($_GET['user_json'])) {
	message("多域名用户丢失");
}
$cgc_baoming_activity = new cgc_baoming_activity();
$activity = $cgc_baoming_activity->getOne($id);
$userinfo = getFromUser($settings, $modulename);
$userinfo = json_decode($userinfo, true);

if (empty($activity['friend_send'])) {
  message("没开启这个选项");
}

if (empty ($userinfo['openid'])) {
	message("没抓到用户信息，可能借用授权服务号没配置好，或者入口错误");
}

$from_user = $userinfo['openid'];

if (empty ($id)) {
	message("id不得为空");
}

if ($op == "display") {
	$user = pdo_fetch("SELECT * FROM " . tablename("cgc_baoming_user") . " WHERE uniacid=$uniacid and openid=:openid and activity_id=:activity_id and is_pay=1 and is_give<>2 and hx_status=0", array(':openid' => $from_user, ':activity_id' => $id));
	
	if (!$user) {
		message('未找到记录!');
	}
	
	if(!empty($user['hx_status'])){
		message('已被使用!');
	}
	
	if($user['is_give']==1){
	  message('赠送过，不可在转赠!');
	}
	
	
	$md5 = md5("{$_W['uniacid']}{$_W['config']['setting']['authkey']}".$user['id']);
	$_url = murl('entry', array('m' => $this->module['name'], 'do' => 'give', 'id' => $id, 'user_id' => $user['id'], 'openid' => $user['openid'],'op' => 'give_confirm', 'give_info' => $md5), true, true);
		$activity['share_title'] = str_replace("#nickname#", $userinfo['nickname'], $activity['share_title']);

	$activity['share_desc'] = str_replace("#nickname#", $userinfo['nickname'], $activity['share_desc']);
	include $this->template('give');
} else if ($op == "give_confirm") {
	$user_id = $_GPC['user_id'];
		
	if (empty ($user_id)) {
		message("参数不正确!");
	}
	

	$give_info = $_GPC['give_info'];
	
	 $md5 = md5("{$_W['uniacid']}{$_W['config']['setting']['authkey']}".$user_id);
	
	if ($md5!=$give_info) {
		message("系统异常了!");
	}
	
	$user = pdo_fetch("SELECT * FROM " . tablename("cgc_baoming_user") . " WHERE uniacid=$uniacid and id=:id and activity_id=:activity_id and is_pay=1 ", array(':id' => $user_id, ':activity_id' => $id));
	
	if ($user['openid']==$userinfo['openid']) {
		message("不能领取自己的券!");
	}
	
	if (!$user) {
		message('未找到记录!');
	}
	
	if(!empty($user['hx_status'])){
		message('已被使用!');
	}
	
	if($user['is_give']==2){
	  message('已被转赠!');
	}
	
	if (checksubmit('submit')) {
		$data = $user;
		//新增赠送记录
		unset($data['id']);
		$data['headimgurl'] = $userinfo['headimgurl'];
		$data['openid'] = $userinfo['openid'];
		$data['nickname'] = $userinfo['nickname'];
		$data['sex'] = $userinfo['sex'];
		$data['tel'] = $_GPC['tel'];
		$data['realname'] = $_GPC['realname'];
		$data['addr'] = $_GPC['addr'];
		$data['wechat_no'] = $_GPC['wechat_no'];
		$data['is_give'] = 1;
		$data['is_pay'] = 1;
		$data['give_openid'] = $user['openid'];
		$data['createtime'] = TIMESTAMP;
		
		$ret = pdo_insert('cgc_baoming_user', $data);
		if ($ret) {//更新原先记录
			$ret = pdo_update('cgc_baoming_user', array('is_give' => 2,'is_pay' =>0), array('id' => $user['id']));
			if ($ret) {
				message('操作成功',murl('entry', array('m' => $this->module['name'], 'do' => 'enter', 'id' => $id,'form'=>'login'),true,true));
			} else {
				message('操作失败');
			}
		} else {
			message('操作失败');
		}
	}
	
	include $this->template('give_confirm');
}
