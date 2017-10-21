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

$cgc_baoming_user = new cgc_baoming_user();
if (!empty ($activity['join_num'])) {
  $count = $cgc_baoming_user->getTotal("uniacid=$uniacid and activity_id=$id  and cj_code!=''");
  if ($count >= $activity['join_num']) {
    message("参与人数" . $count . "个。超过最大参与人数" . $activity['join_num']);
  }
}


$userinfo = getFromUser($settings, $modulename,$activity);

$userinfo = json_decode($userinfo, true);

$this->valid($id, $userinfo['openid']);

//获得父亲id
$pid = getParent($id);

if ($op == "display") {
	$qrcode = tomedia('qrcode_'.$_W['acid'].'.jpg');

    if (!empty ($activity['total_zj'])) {
        $total_zj = $cgc_baoming_user->selectTotal_zj($id);
        if ($total_zj >= $activity['total_zj']) {
            message("中奖名额已经用完");
        }
    }

    if (!empty ($settings['valid_time']) && empty ($_GPC['sign'])) {
        exit ("error valid time1");
    }

    if (!empty ($settings['valid_time']) && !empty ($_GPC['sign'])) {
        if ((time() - intval($_GPC['sign'])) > (intval($settings['valid_time']))) {
            exit ("error valid time");
        }
    }


	if (empty ($id)) {
		message("id不得为空");
	}

	

    $from_user = $userinfo['openid'];
    $openid = empty($_W['openid']) ? $_GPC['fromuser'] : $_W['openid'];
   
    
	//$follow = 1;
    
	//新模板,强制关注,直接在当前页面显示
    if ($activity['must_guanzhu']){
    	 $follow = sfgz_user($openid,$userinfo['unionid']);
        if (empty($follow)) {
            $user = $cgc_baoming_user->selectByUser($from_user, $activity['id']);
            if (!empty($user['cj_code'])){
              $this->forward($id, $from_user, $activity);
            }
        } else {
            $this->forward($id, $from_user, $activity);
        }
    } else {
        $this->forward($id, $from_user, $activity);
    }


    $curr_token = $from_user . time();

    if (empty ($_SESSION[$this->modulename . "_user_" . $uniacid . "token"])) {
        $_SESSION[$this->modulename . "_user_" . $uniacid . "token"] = $curr_token;
    } else {
        $curr_token = $_SESSION[$this->modulename . "_user_" . $uniacid . "token"];
    }

    $activity['share_title'] = str_replace("#nickname#", $userinfo['nickname'], $activity['share_title']);
    $activity['share_desc'] = str_replace("#nickname#", $userinfo['nickname'], $activity['share_desc']);
    /*$activity['share_url'] = get_random_domain($activity['share_url']);*/
    
	$activity['share_url'] = get_share_url($activity, $activity['share_url'], $from_user, $settings);
    if (empty($activity['new_login_mode'])) {
        include $this->template('login');
    } else if ($activity['new_login_mode'] == '2') {
        include $this->template('apple_login');
    } else if ($activity['new_login_mode'] == '3') {
        include $this->template('login3');
    } else {
        include $this->template('jihelogin');
    }
    exit();
}

if ($op == "ajax") {
    $location = $_GPC['location'];
    setcookie($this->modulename . "_user_" . $uniacid . "_location", $location, time() + 3600);
    exit (json_encode(array(
        "code" => "1",
        "msg" => "上报地址" . $location . "成功"
    )));
}

if ($op == "yzm") {

    if (empty ($settings["valid_sms"])) {
        exit (json_encode(array(
            "code" => -2,
            "msg" => "error"
        )));
    }

    $id = $_GPC['id'];
    $cgc_baoming_activity = new cgc_baoming_activity();
    if (!empty ($id)) {
        $activity = $cgc_baoming_activity->getOne($id);
    }
    if (empty ($activity)) {
        exit (json_encode(array(
            "code" => -3,
            "msg" => "活动不存在"
        )));
    }

    $tel = $_GPC['tel'];

    $yzm = mt_rand(1000, 9999);

    $resp = send_ali_sms(array(
        "ali_appkey" => $settings['ali_appkey'],
        "ali_smssign" => $settings['ali_smssign'],
        "ali_secretkey" => $settings['ali_secretkey'],
        "ali_smstemplate" => $settings['ali_smstemplate']
    ), $tel, $yzm, $activity['title']);

    if (($resp['code'] != 1)) {
        exit (json_encode(array(
            "code" => $resp['code'],
            "msg" => $resp['msg'] . "||" . $resp['sub_code']
        )));
    }

    $_SESSION["yzm"] = $yzm;
    $_SESSION["tel"] = $tel;
    $_SESSION["send_time"] = time();

    exit (json_encode(array(
        "code" => 1,
        "msg" => "发送成功"
    )));
}

if ($op == "post") {
    /*$token = $_SESSION[$this->modulename . "_user_" . $uniacid . "token"];
    if (empty ($_GPC['curr_token'])) {
        exit (json_encode(array (
            "code" => -1,
            "msg" => "页面token为空"
        )));
    }

    if (empty ($token)) {
        exit (json_encode(array (
            "code" => -1,
            "msg" => "系统token为空"
        )));
    }

    if ($token != $_GPC['curr_token']) {
        exit (json_encode(array (
            "code" => -9,
            "msg" => $token . "不要重复提交" . $_GPC['curr_token']
        )));
    }*/


    $from_user = $userinfo['openid'];

    $cgc_baoming_activity = new cgc_baoming_activity();
    $activity = $cgc_baoming_activity->getOne($id);


    $id = $_GPC['id'];
    if (empty ($id)) {
        exit (json_encode(array(
            "code" => -2,
            "msg" => "活动id不存在"
        )));
    }

    $this->fans();

    $cgc_baoming_user = new cgc_baoming_user();

/*	if($activity['draw_num'] == 1){
		$user = $cgc_baoming_user->selectByUser($from_user, $activity['id']);
		if ($user) {
	        $temp = $cgc_baoming_user->modify($user['id'], array("tel" => trim($_GPC['tel'])));
	        exit(json_encode(array(
	            "code" => 0,
	            "msg" => "成功"
	        )));
	    }
	}
    */


    $cgc_baoming_activity = new cgc_baoming_activity();

    $activity = $cgc_baoming_activity->getOne($id);

    if (empty ($activity)) {
        exit (json_encode(array(
            "code" => -3,
            "msg" => "活动不存在"
        )));
    }

    if (!empty ($settings["valid_sms"])) {
        $ret = valid_sms_post(array(
            "tel" => $_GPC['tel'],
            "yzm" => $_GPC['yzm']
        ));
        if (($ret['code'] != 1)) {
            exit (json_encode($ret));
        }

    }

    if (empty ($userinfo['openid'])) {
        exit (json_encode(array(
            "code" => -5,
            "msg" => "用户信息丢失"
        )));
    }

    $data = array(
        "uniacid" => $_W['uniacid'],
        "activity_id" => $_GPC['id'],
        "openid" => $userinfo['openid'],
        "nickname" => $userinfo['nickname'],
        "headimgurl" => $userinfo['headimgurl'],
        "tel" => trim($_GPC['tel']),
        "realname" => trim($_GPC['realname']),
        "addr" => trim($_GPC['addr']),
        "wechat_no" => trim($_GPC['wechat_no']),
        "createtime" => TIMESTAMP,
        "pay_money" => $activity['pay_money'],
        "is_pay" => 0,
        "ordersn" => time() . mt_rand(1, 10000),
        "custom1"=> trim($_GPC['custom1']),
        "custom2"=> trim($_GPC['custom2']),
        "custom3"=> trim($_GPC['custom3']),
    );

    if (!empty ($activity['code_type'])) {
        $cj_code = getNextAvaliableCjcode($id, $activity['cj_code_start'], $activity);
        $data['cj_code'] = $cj_code;
        $data['share_status'] = 1;
    }

    $baoming_user_id = $cgc_baoming_user->insert($data);

    if (empty ($baoming_user_id)) {
        exit (json_encode(array(
            "code" => -3,
            "msg" => "信息更新失败"
        )));
    } else {
        if (($activity['activity_type'] == 1) && $data['share_status'] == 1) {
            $cgc_baoming_activity->add_pay_num($activity, $_GPC['id']);
        }
    }

    //是否写入微擎系统表
    if (!empty ($activity['tj_sys'])) {
        mc_update($userinfo['openid'], array(
            'mobile' => trim($_GPC['tel']),
            'realname' => $userinfo['nickname'],
            'address' => trim($_GPC['addr'])
        ));
    }


    //是否产生报名积分
    if (!empty ($activity['credit1_sys'])) {
        load()->model('mc');
        $uid = mc_openid2uid($userinfo['openid']);
        mc_credit_update($uid, 'credit1', $activity['credit1_sys']);
    }


    if (!empty ($activity['code_type']) && $from_user != $_COOKIE["cgc_baoming_share_parent_" . $uniacid . "_" . $id]) {
        update_parent($activity, $userinfo);
    }

    $url = $this->createMobileUrl("share", array(
        'id' => $_GPC['id'],
        'user_id' =>$baoming_user_id,
        'sign' => time()
    ));

    $_SESSION[$this->modulename . "_user_" . $uniacid . "token"] = "";

    //新模板,强制关注
    if ($activity['must_guanzhu']) {
        $openid = $_W['openid'];
        $follow = sfgz_user($openid, $userinfo['unionid']);
    }
 

    //支付
    if ($baoming_user_id > 0 && $activity['activity_type']==2 && empty($activity['pay_time_point'])) {
        $tid = $baoming_user_id;
        // 生成订单和支付参数
        $params = array(
            'tid' => $tid,
            'activity_id' => $_GPC['id'],

            // 充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            /*	'ordersn' => $baoming_user_id, // 收银台中显示的订单号
                'title' => $activity['title'] . '报名费', // 收银台中显示的标题
                'fee' => $activity['pay_money'], // 收银台中显示需要支付的金额,只能大于 0
                'openid' => $from_user,
                'pay_type' => 2*/
        );

        // 调用pay方法
        //$params = $this->payz($params);

        header('Content-Type:application/json; charset=utf-8');
        $ret = array('code' => 1, 'info' => '成功', 'data' => json_encode($params));
        exit(json_encode($ret));
    }

    exit(json_encode(array("code" => 1, "msg" => "成功", "url" => $url)));
  }
