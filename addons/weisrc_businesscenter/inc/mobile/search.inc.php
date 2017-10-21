<?php
global $_GPC, $_W;
$weid = $this->_weid;
$fromuser = $this->_fromuser;
$title = "微商圈";

if (isset($_COOKIE[$this->_auth2_openid])) {
    $from_user = $_COOKIE[$this->_auth2_openid];
    $nickname = $_COOKIE[$this->_auth2_nickname];
    $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
    $userinfo = $this->setUserInfo();
    if (!empty($userinfo)) {
        $from_user = $userinfo["openid"];
        $nickname = $userinfo["nickname"];
        $headimgurl = $userinfo["headimgurl"];
    }
}

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));

//#share
$share_image = tomedia($setting['share_image']);
$share_title = empty($setting['share_title']) ? $setting['title'] : $setting['share_title'];
$share_desc = empty($setting['share_desc']) ? $setting['title'] : $setting['share_desc'];
$share_url = empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index') : $setting['share_url'];
include $this->template($this->cur_tpl . '/search');