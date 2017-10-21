<?php
global $_GPC, $_W;
$storeid = intval($_GPC['storeid']);
$nickname = trim($_GPC['nick']);
$content = trim($_GPC['content']);
$fromuser = trim($_GPC['fromuser']);

//        if (isset($_COOKIE[$this->_auth2_openid])) {
//            $fromuser = $_COOKIE[$this->_auth2_openid];
//            $nickname = $_COOKIE[$this->_auth2_nickname];
//        }

if (isset($_GPC['anonymous'])) {
    if ($_GPC['anonymous'] == 1) {
        $nickname = '匿名';
    }
}
if (empty($nickname)) {
//    $nickname = '匿名';
    $result['msg'] = '不允许匿名留言.';
    die(json_encode($result));
}

$data = array(
    'weid' => $_W['uniacid'],
    'storeid' => $storeid,
    'from_user' => $fromuser,
    'nickname' => $nickname,
    'content' => $content,
    'dateline' => TIMESTAMP
);

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
if (!empty($setting)) {
    if ($setting['feedback_check_enable'] == 1) {
        $data['status'] = 0;
    } else {
        $data['status'] = 1;
    }
} else {
    $data['status'] = 1;
}

$result = array(
    'status' => 0,
    'msg' => '留言失败，请稍后重试...'
);

if (empty($data['from_user'])) {
    $result['msg'] = '会话已过期,请从微信界面重新发送关键字进入.';
    die(json_encode($result));
}
$date = date('Y-m-d');
$flag = pdo_fetch("SELECT * FROM " . tablename($this->table_feedback) . " WHERE from_user = :from_user and storeid=:storeid
and  date_format(FROM_UNIXTIME(dateline), '%Y-%m-%d') = :date ", array(':from_user' => $fromuser, ':date' => $date, ':storeid' => $storeid));
if (!empty($flag)) {
    $result['msg'] = '您今天已经留过言了!';
    die(json_encode($result));
}

if (!isset($_GPC['anonymous'])) {
    if (empty($data['nickname'])) {
        $result['msg'] = '请输入昵称.';
        die(json_encode($result));
    }
}

if (empty($data['content'])) {
    $result['msg'] = '请输入留言内容.';
    die(json_encode($result));
}

$rowcount = pdo_insert($this->table_feedback, $data);
if ($rowcount > 0) {
    fans_update($data['from_user'], array('nickname' => $nickname));
    $result['status'] = 1;
    $result['msg'] = '留言成功!';
}
echo json_encode($result);