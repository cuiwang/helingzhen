<?php
//任务审核页面
global $_W, $_GPC;
$weid = $_W['uniacid'];
$quan_id = intval($_GPC['quan_id']);
$id = intval($_GPC['id']);
$member = $this->get_member();
$from_user = $member['openid'];
$quan = $this->get_quan();
$sys_config = $this->module['config'];

$adv = $this->get_adv();
$mid = $member['id'];
$op = empty($_GPC['op']) ? "display" : $_GPC['op'];
$config = $this->settings;

if (empty($member['is_kf'])) {
    $this->returnError("没权限");
}

if ($adv['status'] == 1) {
    $this->returnError("审核通过了");
}

if ($op == 'display') {
    include $this->template('adv_check');
    exit();
} else if ($op == 'check') {
    $cgc_ad_adv = new cgc_ad_adv();
    $ret = $cgc_ad_adv->modify($id, array("status" => "1"));
    if (!empty($ret)) {

        if (empty($sys_config['pay_after_msg']) && !empty($config['tuisong'])) {
            $_userlist = pdo_fetchall('SELECT openid FROM ' . tablename('cgc_ad_member') . " where weid=" . $weid . " and quan_id={$quan['id']} and type=1 and message_notify =1 and status=1");
            $_url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('index', array('quan_id' => $quan['id'])), 2);
            $htt = str_replace('"', "'", htmlspecialchars_decode($config['tuisong']));
            //红包通知信息更换 by 20170212
            $msg = str_replace('{num}', $ret['total_num'], $config['tuisong']);
            $msg = str_replace('{money}', $ret['total_amount'], $msg);
            $msg = str_replace('{type}', $this->get_modelName($ret['model']), $msg);
            $_tdata = array(
                'first' => array('value' => '系统通知', 'color' => '#173177'),
                'keyword1' => array('value' => $msg, 'color' => '#173177'),
                'keyword2' => array('value' => date('Y-m-d H:i:s', time()), 'color' => '#173177'),
                'keyword3' => array('value' => '抢钱通知', 'color' => '#173177'),
                'remark' => array('value' => '点击详情进入', 'color' => '#173177'),
            );
            foreach ($_userlist as $key => $r) {
                if ($config['is_type'] == 1) {
                    $a = sendTemplate_common($r['openid'], $config['template_id'], $_url, $_tdata);
                } else {
                    $a = post_send_text($r['openid'], $htt);
                }
            }
        }


        $this->returnSuccess("审核通过了");
    } else {
        $this->returnError("审核失败");
    }

} else if ($op == 'msg') {
    $task_member = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_member') .
        " WHERE   weid=" . $weid . " AND quan_id=" . $quan_id . " and id='{$task['mid']}'");
    $msg = $_GPC['content'];
    if (empty($msg)) {
        $this->returnError('发送的消息不能为空');
    }

    $msg = "任务发布人" . $adv['nickname'] . "给您的回复:" . $msg;

    if ($config['is_type'] == 1) {
        $_tdata = array(
            'first' => array('value' => '任务反馈！', 'color' => '#173177'),
            'keyword1' => array('value' => '任务模式红包', 'color' => '#173177'),
            'keyword2' => array('value' => $msg, 'color' => '#173177'),
            'remark' => array('value' => '请尽快修改任务后，提醒广告主审核。', 'color' => '#173177'),
        );
        $_url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('task_detail', array('quan_id' => $quan['id'], 'id' => $id)), 2);
        $a = sendTemplate_common($task_member['openid'], $config['task_template_id'], $_url, $_tdata);
    } else {
        $a = post_send_text($task_member['openid'], $msg);
    }


    if (is_error($a)) {
        $this->returnError("发送消息失败，用户未关注，或者客服消息已经48小时过去了");
    } else {
        $this->returnSuccess("发送消息成功");
    }
} else if ($op == 'send_taskmsg') {
    include $this->template('send_taskmsg');
    exit();
}
    

					



	
	
	
		
		
		
		
		
		
						



							



						


						



			
						

							