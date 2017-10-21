<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$setting = $this->getSetting();
$cur_nave = 'restlist';

if ($setting['mode'] == 1) {
    $jump_url = $this->createMobileUrl('detail', array('id' => $setting['storeid']), true);
    header("location:$jump_url");
}

$areaid = intval($_GPC['areaid']);
$typeid = intval($_GPC['typeid']);
$sortid = intval($_GPC['sortid']);

if ($sortid == 0) {
    $sortid = 2;
}

$method = 'waprestlist'; //method
$host = $this->getOAuthHost();
$authurl = $host . 'app/' . $this->createMobileUrl($method, array('typeid' => $typeid, 'areaid' => $areaid), true) . '&authkey=1';
$url = $host . 'app/' . $this->createMobileUrl($method, array('typeid' => $typeid, 'areaid' => $areaid), true);
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
        } else {
            message('授权失败!');
        }
    } else {
        if (!empty($this->_appsecret)) {
            $this->getCode($url);
        }
    }
}

$fans = $this->getFansByOpenid($from_user);
if (empty($fans)) {
    $this->addFans($nickname, $headimgurl);
} else {
    $this->updateFans($nickname, $headimgurl, $fans['id']);
}
$fans = $this->getFansByOpenid($from_user);
if ($fans['status'] == 0) {
    die('系统调试中！');
}

$lat = trim($_GPC['lat']);
$lng = trim($_GPC['lng']);
$isposition = 0;
if (!empty($lat) && !empty($lng)) {
    $isposition = 1;
    setcookie($this->_lat, $lat, TIMESTAMP + 3600 * 12);
    setcookie($this->_lng, $lng, TIMESTAMP + 3600 * 12);
} else {
    if (isset($_COOKIE[$this->_lat])) {
        $isposition = 1;//0的时候才跳转
        $lat = $_COOKIE[$this->_lat];
        $lng = $_COOKIE[$this->_lng];
    }
}

if (empty($from_user)) {
    message('会话已过期，请重新发送关键字!');
}

$pindex = max(1, intval($_GPC['page']));
$psize = $this->more_store_psize;
$strwhere = " where weid = :weid and is_show=1 AND is_list=1 AND deleted=0 ";
$limit = " LIMIT "  . ($pindex - 1) * $psize . ',' . $psize;

if ($areaid != 0) {
    $strwhere .= "  AND areaid={$areaid} ";
}

if ($typeid != 0) {
    $strwhere .= " AND typeid={$typeid} ";
}

//所属区域
$area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid), 'id');
$curarea = "全城";
if (!empty($area[$areaid]['name'])) {
    $curarea = $area[$areaid]['name'];
}
//门店类型
$shoptype = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid), 'id');
$curtype = "门店类型";
if (!empty($shoptype[$typeid]['name'])) {
    $curtype = $shoptype[$typeid]['name'];
}
$cursort = "综合排序";
if ($sortid == 1) {
    $cursort = "正在营业";
} else if ($sortid == 2) {
    $cursort = "距离优先";
}
$this->resetHour();

if ($sortid == 1) {
    $restlist = pdo_fetchall("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_stores) . " {$strwhere
} ORDER BY is_rest DESC,displayorder DESC, id DESC " . $limit, array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));
} else if ($sortid == 2 && !empty($lat)) {
    $restlist = pdo_fetchall("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_stores) . " {$strwhere} ORDER BY dist, displayorder DESC,id DESC" . $limit, array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));
} else {
    $restlist = pdo_fetchall("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_stores) . " {$strwhere} ORDER BY is_rest DESC,displayorder DESC, id DESC" . $limit, array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));
}

if (!empty($restlist)) {
    foreach ($restlist as $key => $value) {
        $good_count = pdo_fetchcolumn("SELECT sum(sales) FROM " . tablename($this->table_goods) . " WHERE storeid=:id ", array(':id' => $value['id']));
        $restlist[$key]['sales'] = intval($good_count);

        if ($value['is_newlimitprice'] == 1) {
            $couponlist = pdo_fetchall("select * from " . tablename($this->table_coupon) . " WHERE storeid=:storeid AND :time<endtime AND type=3 ORDER BY gmoney desc,id DESC LIMIT 10", array(':storeid' => $value['id'], ':time' => TIMESTAMP));
            foreach ($couponlist as $key2 => $value2) {
                $newlimitprice .= $value2['title'] . ';';
            }
            $restlist[$key]['newlimitprice'] = $newlimitprice;
        }
        if ($value['is_oldlimitprice'] == 1) {
            $couponlist = pdo_fetchall("select * from " . tablename($this->table_coupon) . " WHERE storeid=:storeid AND :time<endtime AND type=4 ORDER BY gmoney
desc,id DESC LIMIT 10", array(':storeid' => $value['id'], ':time' => TIMESTAMP));
            foreach ($couponlist as $key3 => $value3) {
                $oldlimitprice .= $value3['title'] . ';';
            }
            $restlist[$key]['oldlimitprice'] = $oldlimitprice;
        }
    }
}
//message($areaid);
$slide = $this->getAllSlides();
$share_title = !empty($setting['share_title']) ? str_replace("#username#", $nickname, $setting['share_title']) : "您的朋友{$nickname}邀请您来吃饭";
$share_desc = !empty($setting['share_desc']) ? str_replace("#username#", $nickname, $setting['share_desc']) : "最新潮玩法，快来试试！";
$share_image = !empty($setting['share_image']) ? tomedia($setting['share_image']) : tomedia("../addons/weisrc_dish/icon.jpg");
$share_url = $host . 'app/' . $this->createMobileUrl('waprestlist', array(), true);

setcookie('global_sid_' . $weid,'',time()-1);
include $this->template($this->cur_tpl . '/restlist');