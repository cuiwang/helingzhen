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

$cid = intval($_GPC['cid']);
$aid = intval($_GPC['aid']);
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

$no_more_data = 0;
$condition_store = ' AND (isvip=0 OR (isvip=1 AND unix_timestamp(now()) > vip_start AND unix_timestamp(now()) < vip_end)) ';

$level_star = array(
    '1' => '★',
    '2' => '★★',
    '3' => '★★★',
    '4' => '★★★★',
    '5' => '★★★★★'
);

if (empty($cid)) {
    //全部类别
    $categorys = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE 1=1 AND parentid=0 AND weid=:weid ORDER BY displayorder DESC", array(':weid' => $weid));
} else {
    //按类别
    $category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE 1=1 AND id={$cid} AND weid=:weid", array(':weid' => $weid));
    //属于父级
    if (empty($category['parentid'])) {
        $categorys = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE 1=1 AND (parentid={$category['id']} OR id={$category['id']}) AND weid=:weid ORDER BY parentid,displayorder DESC", array(':weid' => $weid), 'id');
        $categoryids = implode("','", array_keys($categorys));
        $condition_store .= " AND pcate={$cid} ";
    } else {
        //子级
        $categorys = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE 1=1 AND (parentid={$category['parentid']} OR id={$category['parentid']}) AND weid=:weid ORDER BY parentid,displayorder DESC", array(':weid' => $weid), 'id');
        $condition_store .= " AND ccate = {$category['id']} ";
    }
}

$areas = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " WHERE 1=1 AND weid=:weid ORDER BY displayorder DESC, id DESC", array(':weid' => $weid), 'id');
if ($aid != 0) {
    $cur_area = pdo_fetch("SELECT * FROM " . tablename($this->table_area) . " WHERE 1=1 AND id=:id AND weid=:weid", array(':weid' => $weid, ':id' => $aid));

    $condition_store .= " AND aid = {$aid} ";
}

//#share
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $weid));

$pindex = max(1, intval($_GPC['page']));
$psize = empty($setting) ? 5 : intval($setting['pagesize']);
//商家列表 //搜索处理
$keyword = trim($_GPC['keyword']);
$orderStr = " top DESC,displayorder DESC,status DESC,id DESC ";

if (!empty($keyword)) {
    $condition_store = " AND (title like '%{$keyword}%' OR address like '%{$keyword}%' ) ";
    $stores = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 AND checked=1 {$condition_store} ORDER BY {$orderStr}", array(':weid' => $weid));
    $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 AND checked=1
{$condition_store} ", array(':weid' => $weid));
} else {
    if ($isposition == 1) {
        $stores = pdo_fetchall("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 AND checked=1 {$condition_store} ORDER BY top DESC, displayorder DESC, dist,status DESC,id DESC  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));
    } else {
        $stores = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 AND checked=1 {$condition_store} ORDER BY {$orderStr} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $weid));
    }

    $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 AND checked=1
{$condition_store} ", array(':weid' => $weid));
    if ($total <= $psize) {
        $no_more_data = 1;
    }
}

$share_image = tomedia($setting['share_image']);
$share_title = empty($setting['share_title']) ? $setting['title'] : $setting['share_title'];
$share_desc = empty($setting['share_desc']) ? $setting['title'] : $setting['share_desc'];
$share_url = empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('list') : $setting['share_url'];
include $this->template($this->cur_tpl . '/list');