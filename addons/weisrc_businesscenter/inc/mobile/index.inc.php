<?php
global $_GPC, $_W;
$weid = $this->_weid;

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

//幻灯片
$slide = pdo_fetchall("SELECT * FROM " . tablename($this->table_slide) . " WHERE weid = :weid AND storeid=0 AND position=1 AND status=1 AND :time > starttime AND :time < endtime ORDER BY
 displayorder DESC,id DESC LIMIT 6", array(':weid' => $weid, ':time' => TIMESTAMP));

//一级分类
$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = :weid AND parentid=0  ORDER BY displayorder DESC", array(':weid' => $weid));
//推荐分类
$category_first = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = :weid AND parentid<>0 AND isfirst=1 ORDER BY displayorder DESC", array(':weid' => $weid));
//推荐商家
$hotstores = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 AND isfirst=1  AND (isvip=0 OR (isvip=1 AND unix_timestamp(now()) > vip_start AND unix_timestamp(now()) < vip_end)) ORDER BY displayorder DESC ", array(':weid' => $weid));

//#share
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $weid));
$title = empty($setting) ? "微商圈" : $setting['title'];
$share_image = tomedia($setting['share_image']);
$share_title = empty($setting['share_title']) ? $setting['title'] : $setting['share_title'];
$share_desc = empty($setting['share_desc']) ? $setting['title'] : $setting['share_desc'];
$share_url = empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array(), true) : $setting['share_url'];

include $this->template($this->cur_tpl . '/index');