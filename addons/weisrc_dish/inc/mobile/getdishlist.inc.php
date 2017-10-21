<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $_GPC['from_user'];
$this->_fromuser = $from_user;

if (empty($from_user)) {
    message('会话已过期，请重新发送关键字!');
}

$storeid = intval($_GPC['storeid']);
$categoryid = intval($_GPC['categoryid']);

$week = date("w");


$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = :weid AND status = 1 AND deleted=0 AND storeid=:storeid AND
pcate=:pcate AND find_in_set(".$week.",week) ORDER by displayorder DESC,subcount DESC,id DESC", array(':weid' => $weid, ':storeid' => $storeid, ':pcate' => $categoryid));

$dishcount = $this->getDishCountInCart($storeid);

foreach ($list as $key => $row) {
    $subcount = intval($row['subcount']);
    $data[$key] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'dSpecialPrice' => $row['marketprice'],
        'dmemberprice' => $row['memberprice'],
        'dPrice' => $row['productprice'],
        'dDescribe' => $row['description'], //描述
        'dTaste' => $row['taste'], //口味
        'dSubCount' => $row['subcount'], //被点次数
        'credit' => $row['credit'],
        'thumb' => empty($row['thumb']) ? tomedia('./addons/weisrc_dish/icon.jpg') : tomedia($row['thumb']),
        'unitname' => $row['unitname'],
        'dIsSpecial' => $row['isspecial'],
        'dIsHot' => $subcount > 20 ? 2 : 0,
        'total' => empty($dishcount) ? 0 : intval($dishcount[$row['id']]) //商品数量
    );
}
$result['data'] = $data;
$result['categoryid'] = $categoryid;
message($result, '', 'ajax');