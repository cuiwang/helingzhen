<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$setting = $this->getSetting();
$cur_nave = 'detail';
$id = intval($_GPC['id']);

$do = 'coupon';
$method = 'coupon'; //method
$host = $this->getOAuthHost();
$authurl = $host . 'app/' . $this->createMobileUrl($method, array('id' => $id), true) . '&authkey=1';
$url = $host . 'app/' . $this->createMobileUrl($method, array('id' => $id), true);
if (isset($_COOKIE[$this->_auth2_openid])) {
    $from_user = $_COOKIE[$this->_auth2_openid];
    $nickname = $_COOKIE[$this->_auth2_nickname];
    $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
    if (isset($_GPC['code'])) {
        $userinfo = $this->oauth2($authurl);
        if (!empty($userinfo)) {
            $from_user = $userinfo["openid"];
            $nickname = $userinfo["nickname"];
            $headimgurl = $userinfo["headimgurl"];
            $sex = $userinfo["sex"];
        } else {
            message('授权失败!');
        }
    } else {
        if (!empty($this->_appsecret)) {
            $this->getCode($url);
        }
    }
}

$coupon = pdo_fetch("SELECT * FROM " . tablename($this->table_coupon) . " where weid = :weid AND id=:id ORDER BY displayorder DESC LIMIT 1", array(':weid' => $weid, ':id' => $id));

if (empty($coupon)) {
    message('数据不存在!');
}

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid AND id=:id ORDER BY displayorder DESC LIMIT 1", array(':weid' => $weid, ':id' => $coupon['storeid']));

include $this->template($this->cur_tpl . '/coupon');