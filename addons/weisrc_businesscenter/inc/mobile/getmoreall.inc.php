<?php
global $_GPC, $_W;
$weid = $this->_weid;
$cid = intval($_GPC['cid']); //类别
$aid = intval($_GPC['aid']); //类别

$curlat = $_GPC['curlat'];
$curlng = $_GPC['curlng'];

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
$page = intval($_GPC['page']); //页码
$pindex = max(1, intval($_GPC['page']));
$psize = empty($setting) ? 5 : intval($setting['pagesize']);
$condition = ' AND (isvip=0 OR (isvip=1 AND unix_timestamp(now()) > vip_start AND unix_timestamp(now()) < vip_end)) ';

if (!empty($cid)) {
    $category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE 1=1 AND id={$cid}");
    if ($category['parentid'] == 0) {
        //属于父级
        $condition .= " AND pcate={$cid} ";
    } else {
        $condition .= " AND ccate={$cid} ";
    }
}

if (!empty($aid)) {
    $condition .= " AND aid={$aid} ";
}

//商家列表
$stores = pdo_fetchall("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND status=1 {$condition} ORDER BY top DESC, displayorder DESC, dist,status DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $weid, ':lat' => $curlat, ':lng' => $curlng));

$level_star = array(
    '1' => '★',
    '2' => '★★',
    '3' => '★★★',
    '4' => '★★★★',
    '5' => '★★★★★'
);
$result_str = '';
foreach ($stores as $key => $value) {
    if (strstr($value['logo'], 'http') || strstr($value['logo'], '../addons/')) {
        $logo = $value['logo'];
    } else {
        $logo = $_W['attachurl'] . $value['logo'];
    }
    $level = $level_star[$value['level']];
    $error_img = " onerror=\"this.src='" . RES . "/themes/images/nopic.jpeg'\"";

    if ($this->cur_tpl == 'style1') {
        if (!empty($value['discounts'])) {
            $discounts = '<section class="tn-Powered-by-XIUMI line"></section>
                    <p class="VIPzhekou"><span style="color:#50849C;font-size: 15px;">会员折扣：</span><span style="color:rgb(255, 129, 36);text-shadow: 0px -1px 0px rgba(255, 255, 255, 0.5);font-size: 13px;">' . $value['discounts'] . '</span></p>
                    <section class="tn-Powered-by-XIUMI line"></section>';
        }

        $result_str .= '<div class="J-wsq-shoplist">
                <a href="' . $this->createMobileurl('shop', array('id' => $value['id'])) . '" style="overflow:hidden;">
                    <img src="' . $logo . '" ' . $error_img . '>
                    <p class="tt">' . $value['title'] . '</p>
                    <p class="address">' . $value['address'] . '</p>
                    <p class="address">' . $this->getDistance($curlat, $curlng, $value['lat'], $value['lng']) . ' km</p>
                </a>
                <p class="bar_box">
                    <a href="' . $this->createMobileurl('shop', array('id' => $value['id'])) . '"><i class="icon-login"></i>详情</a>
                    <a href="http://api.map.baidu.com/marker?location=' . $value['lat'] . ',' . $value['lng'] . '&title=' . $value['title'] . '&name=' . $value['title'] . '&content=' . $value['address'] . '&output=html&src=wzj|wzj"><i
                        class="icon-location-2"></i>导航</a><a href="tel:' . $value['tel'] . '"><i class="icon-phone-3"></i>预定</a>
                </p>';
        if ($value['top'] == 1) {
            $result_str .= '<em class="tj_b"></em>';
        }
        $result_str .= '</div>';
    } else if ($this->cur_tpl == 'style2') {
        $result_str .= '<a href="' . $this->createMobileurl('shop', array('id' => $value['id'])) . '">';
        $result_str .= '<div class="main01">';
        $result_str .= '<div class="main1">';
        $result_str .= '<div class="list00" style="position:relative">';
        $result_str .= '<h3><span class="fr" style="color:#ccc;">' . $this->getDistance($curlat, $curlng, $value['lat'], $value['lng']) . 'km</span>' . $value['title'] . '</h3>';
        $result_str .= '<p>'.$value['content'].'</p>';
        if (!empty($value['discount'])) {
            $result_str .= '<span class="fl  red">'.$value['discount'].'折</span>';
        }
        $result_str .= '</div>';
        $result_str .= '<div class="box2 d">';
        $result_str .= '<img src="' . $logo . '" ' . $error_img . '  class="f">';
        if ($value['top'] == 1) {
            $result_str .= '<div class="qing"></div>';
        }
        $result_str .= '</div></div></div></a>';
    }
}

if ($result_str == '') {
    echo json_encode(0);
} else {
    echo json_encode($result_str);
}