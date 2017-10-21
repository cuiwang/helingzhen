<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;

$cur_nave = 'home';
$setting = $this->getSetting();
$title = empty($setting) ? "微餐厅" : $setting['title'];

$method = 'index'; //method
$host = $this->getOAuthHost();
$authurl = $host . 'app/' . $this->createMobileUrl($method, array(), true) . '&authkey=1';
$url = $host . 'app/' . $this->createMobileUrl($method, array(), true);
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
$slide = $this->getAllSlides();

if ($fans['status'] == 0) {
    die('系统调试中！');
}

$sub = 0;
if ($this->_accountlevel == 4) {
    $userinfo = $this->getUserInfo($from_user);
    if ($userinfo['subscribe'] == 1) {
        $sub = 1;
    }
} else {
    if ($_W['fans']['follow'] == 1) {
        $sub = 1;
    }
}

//门店类型
$shoptypes = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid), 'id');

$typecount = count($shoptypes);

$areaid = intval($_GPC['areaid']);
$typeid = intval($_GPC['typeid']);
$sortid = intval($_GPC['sortid']);
if ($sortid == 0) {
    $sortid = 2;
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

$pindex = max(1, intval($_GPC['page']));
$psize = $this->more_store_psize;
$strwhere = " where weid = :weid and is_show=1 AND is_list=1 AND deleted=0 ";
$limit = " LIMIT "  . ($pindex - 1) * $psize . ',' . $psize;

if ($areaid != 0) {
    $strwhere .= " AND areaid={$areaid} ";
}

if ($typeid != 0) {
    $strwhere .= " AND typeid={$typeid} ";
}
if ($sortid == 1) {
    $restlist = pdo_fetchall("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_stores) . " {$strwhere
} ORDER BY is_rest DESC,displayorder DESC, id DESC " . $limit, array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));
} else if ($sortid == 2 && !empty($lat)) {
    $restlist = pdo_fetchall("SELECT *,(lat-:lat)*(lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_stores) . " {$strwhere
} ORDER BY dist, displayorder DESC,id DESC " . $limit, array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));
} else {
    $restlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " {$strwhere} ORDER BY is_rest DESC,displayorder DESC, id DESC"  . $limit, array(':weid' => $weid));
}

if (!empty($restlist)) {
    foreach ($restlist as $key => $value) {
        $good_count = pdo_fetchcolumn("SELECT sum(sales) FROM " . tablename($this->table_goods) . " WHERE storeid=:id ", array(':id' => $value['id']));
        $restlist[$key]['sales'] = intval($good_count);
        $newlimitprice = '';
        $oldlimitprice = '';
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

$ispop = 0;
if ($setting['tiptype'] == 1) { //关注后隐藏
    if ($sub == 0) {
        $ispop = 1;
    }
} else if ($setting['tiptype'] == 2) {
    $ispop = 1;
}

$follow_title = !empty($setting['follow_title']) ? $setting['follow_title'] : "立即关注";
$follow_desc = !empty($setting['follow_desc']) ? $setting['follow_desc'] : "欢迎关注智慧点餐点击马上加入,助力品牌推广 ";
$follow_image = !empty($setting['follow_logo']) ? tomedia($setting['follow_logo']) : tomedia("../addons/weisrc_dish/icon.jpg");
$tipqrcode = tomedia($setting['tipqrcode']);
$tipbtn = intval($setting['tipbtn']);
$follow_url = $setting['follow_url'];
$this->checkRechargePrice($from_user);


$share_title = !empty($setting['share_title']) ? str_replace("#username#", $nickname, $setting['share_title']) : "您的朋友{$nickname}邀请您来吃饭";
$share_desc = !empty($setting['share_desc']) ? str_replace("#username#", $nickname, $setting['share_desc']) : "最新潮玩法，快来试试！";
$share_image = !empty($setting['share_image']) ? tomedia($setting['share_image']) : tomedia("../addons/weisrc_dish/icon.jpg");
$share_url = $host . 'app/' . $this->createMobileUrl('index', array('agentid' => $fans['id']), true);

include $this->template($this->cur_tpl . '/index');