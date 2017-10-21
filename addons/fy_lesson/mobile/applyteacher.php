<?php
/**
 * 讲师中心
 */
$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('applyteacher');
if (isset($_COOKIE[$this->_auth2_openid])) {
    $openid = $_COOKIE[$this->_auth2_openid];
    $nickname = $_COOKIE[$this->_auth2_nickname];
    $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
    if (isset($_GPC['code'])) {
        $userinfo = $this->oauth2();
        if (!empty($userinfo)) {
            $openid = $userinfo["openid"];
            $nickname = $userinfo["nickname"];
            $headimgurl = $userinfo["headimgurl"];
        } else {
            message('授权失败!');
        }
    } else {
        if (!empty($this->_appsecret)) {
            $this->getCode($url);
        }
    }
}

load()->model('app');
load()->func('tpl');

/* 基本设置 */
$setting = pdo_fetch("SELECT manageopenid,apply_teacher,teacher_income,sitename,sharelink,footnav,copyright,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

if ($setting['teacher_income'] == 0) {
    message("系统没有开启讲师入驻！", "", "warning");
}

/* 分享设置 */
load()->model('mc');
$uid = mc_openid2uid($openid);
$sharelink = unserialize($setting['sharelink']);
$shareurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('uid' => $uid));

/* 会员信息 */
$lessonmember = pdo_fetch("SELECT a.*,b.nickname AS mnickname FROM " . tablename($this->table_member) . " a LEFT JOIN " . tablename('mc_members') . " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.openid='{$openid}'");

/* 讲师信息 */
$teacherlog = pdo_fetch("SELECT * FROM " . tablename($this->table_teacher) . " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");

$title = "申请讲师";

if ($op == 'display') {
    if ($teacherlog['status'] == 1 || $teacherlog['status'] == 2) {
        header("Location:" . $this->createMobileUrl('teachercenter'));
    }
} elseif ($op == 'postteacher') {

    $data = array();
    $data['teacher'] = trim($_GPC['teacher']);
    $data['qq'] = trim($_GPC['qq']);
    $data['qqgroup'] = trim($_GPC['qqgroup']);
    $data['teacherdes'] = trim($_GPC['teacherdes']);
    $data['status'] = 2;

    if (!empty($_FILES['weixin_qrcode']['name'])) {
        $data['weixin_qrcode'] = $this->uploadpic($_FILES['weixin_qrcode']);
    }
    if (!empty($_FILES['teacherphoto']['name'])) {
        $data['teacherphoto'] = $this->uploadpic($_FILES['teacherphoto']);
    }

    if (empty($data['teacher'])) {
        message("请填写讲师名称");
    }
    if (empty($data['qq'])) {
        message("请填写联系QQ");
    }
    if (empty($data['teacherdes'])) {
        message("请填写讲师介绍");
    }
    if (empty($data['teacherphoto']) && empty($teacherlog['teacherphoto'])) {
        message("请上传讲师相片");
    }

    $manage = explode(",", $setting['manageopenid']);
    if (empty($teacherlog)) {
        $data['uid'] = $uid;
        $data['openid'] = $openid;
        $data['uniacid'] = $uniacid;
        $data['addtime'] = time();

        pdo_insert($this->table_teacher, $data);
        foreach ($manage as $manageopenid) {
            $sendneworder = array(
                'touser' => $manageopenid,
                'template_id' => $setting['apply_teacher'],
                'url' => "",
                'topcolor' => "#7B68EE",
                'data' => array(
                    'first' => array(
                        'value' => urlencode("您有一条新的讲师入驻申请，请及时审核"),
                        'color' => "#428BCA",
                    ),
                    'keyword1' => array(
                        'value' => trim($_GPC['teacher']),
                        'color' => "#428BCA",
                    ),
                    'keyword2' => array(
                        'value' => "讲师入驻申请",
                        'color' => "#428BCA",
                    ),
                    'remark' => array(
                        'value' => urlencode("详情请登录网站后台查看！"),
                        'color' => "#222222",
                    ),
                )
            );
            $this->send_template_message(urldecode(json_encode($sendneworder)), $_W['acid']);
        }
        message("提交申请成功，等待管理员审核", $this->createMobileUrl("teachercenter"), "success");
    } else {
        pdo_update($this->table_teacher, $data, array('openid' => $openid));
        foreach ($manage as $manageopenid) {
            $sendneworder = array(
                'touser' => $manageopenid,
                'template_id' => $setting['apply_teacher'],
                'url' => "",
                'topcolor' => "#7B68EE",
                'data' => array(
                    'first' => array(
                        'value' => urlencode("您有一条新的讲师入驻申请，请及时审核"),
                        'color' => "#428BCA",
                    ),
                    'keyword1' => array(
                        'value' => trim($_GPC['teacher']),
                        'color' => "#428BCA",
                    ),
                    'keyword2' => array(
                        'value' => "微课堂讲师入驻申请",
                        'color' => "#428BCA",
                    ),
                    'remark' => array(
                        'value' => urlencode("详情请登录网站后台查看！"),
                        'color' => "#222222",
                    ),
                )
            );
            $this->send_template_message(urldecode(json_encode($sendneworder)), $_W['acid']);
        }
        message("重新提交申请成功", $this->createMobileUrl("teachercenter"), "success");
    }
}

include $this->template('applyteacher');
?>