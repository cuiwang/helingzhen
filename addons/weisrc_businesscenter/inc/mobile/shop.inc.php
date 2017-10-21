<?php
global $_GPC, $_W;
$weid = $this->_weid;
$fromuser = $this->_fromuser;
$title = "微商圈";

$userinfo = $this->setUserInfo();
if (!empty($userinfo)) {
    $from_user = $userinfo["openid"];
    $nickname = $userinfo["nickname"];
    $headimgurl = $userinfo["headimgurl"];
}
//        echo $nickname;

$id = intval($_GPC['id']);
if (empty($id)) {
    message('没有相关数据!');
}

$level_star = array(
    '1' => '★',
    '2' => '★★',
    '3' => '★★★',
    '4' => '★★★★',
    '5' => '★★★★★'
);

$stores = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 AND id=:id", array(':weid' => $weid, ':id' => $id));

$pcate = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $stores['pcate']));
$ccate = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $stores['ccate']));
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));

$cityid = $stores['cityid'];
if (!empty($cityid)) {
    $city = pdo_fetch("SELECT * FROM " . tablename($this->table_city) . " WHERE weid = :weid AND id=:id ORDER BY displayorder DESC LIMIT 1", array(':weid' => $weid, ':id' => $cityid));

    $showcity = '';
    if (!empty($setting['showcity'])) {
        $showcity = '<a href="' . $this->createMobileUrl('shop', array('do' => 'citylist', 'cityid' => $cityid)) . '" style="display: inline;background:none;">' . $city['name'] . '</a>&gt;';
        $pcateurl = $this->createMobileUrl('shop', array('do' => 'list', 'cityid' => $cityid, 'cid' => $pcate['id']));
    }

    $page_nave = $showcity . '<a href="' . $pcateurl . '" style="display: inline;background:none;">' . $pcate['name'] . '</a>&gt;<a href="#" style="display: inline;background:none;">' . $ccate['name'] . '</a>';
}

//幻灯片
$slide = pdo_fetchall("SELECT * FROM " . tablename($this->table_slide) . " WHERE weid = :weid AND storeid=:storeid AND status=1 ORDER BY displayorder DESC", array(':weid' => $weid, ':storeid' => $id));

if (empty($nickname)) {
    if (!empty($_W['fans']['from_user'])) {
        $user = fans_search($_W['fans']['from_user']);
        $nickname = $user['nickname'];
    }
}

$feedbacklist = pdo_fetchall("SELECT *,date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d') as date FROM " . tablename($this->table_feedback) . "  WHERE
weid= :weid AND storeid=:storeid AND status=1 ORDER BY displayorder DESC,id DESC LIMIT 10", array(':weid' =>
    $_W['uniacid'], ':storeid' => $id));

//#share
$share_image = tomedia($stores['logo']);
$share_title = empty($stores['share_title']) ? $stores['title'] : $stores['share_title'];
$share_desc = empty($stores['share_desc']) ? $stores['title'] : $stores['share_desc'];
$share_url = empty($stores['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('shop', array('id' => $stores['id'])) : $stores['share_url'];
include $this->template($this->cur_tpl . '/shop');