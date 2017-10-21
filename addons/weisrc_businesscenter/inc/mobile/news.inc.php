<?php
global $_GPC, $_W;
$weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']);
$title = "微商圈";
$modulename = $this->modulename;
$cid = intval($_GPC['cid']);
$no_more_data = 0;
$condition_store = '';

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $weid));
//#share
if (strpos($setting['share_image'], 'http') === false) {
    $share_image = $_W['attachurl'] . $setting['share_image'];
} else {
    $share_image = $setting['share_image'];
}
$share_title = empty($setting['share_title']) ? $setting['title'] : $setting['share_title'];
$share_desc = empty($setting['share_desc']) ? $setting['title'] : $setting['share_desc'];
$share_url = empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('settled') : $setting['share_url'];

$pindex = max(1, intval($_GPC['page']));
$psize = empty($setting) ? 5 : intval($setting['pagesize']);

//商家列表 //搜索处理
$keyword = trim($_GPC['keyword']);
$orderStr = " ORDER BY top DESC,displayorder DESC,status DESC,id DESC ";

$stores = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND status<>0 AND checked=1 ", array(':weid' => $weid), 'id');

if (!empty($keyword)) {
    $condition_store = " AND (title like '%{$keyword}%' OR address like '%{$keyword}%' ) ";
    $news = pdo_fetchall("SELECT * FROM " . tablename($this->table_news) . " WHERE weid = :weid AND status<>0 AND checked=1 {$condition_store} {$orderStr}", array(':weid' => $weid));

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_news) . " WHERE weid = :weid AND status<>0 AND checked=1 {$condition_store} ", array(':weid' => $weid));
} else {
    $news = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_news') . " WHERE weid = :weid AND status<>0 AND checked=1 {$condition_store} {$orderStr} LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $weid));
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_news) . " WHERE weid = :weid AND status<>0 AND checked=1 {$condition_store} ", array(':weid' => $weid));
    if ($total <= $psize) {
        $no_more_data = 1;
    }
}
if ($this->cur_tpl == 'style2') {
    message('对不起，这套模版没有优惠资讯的界面!');
}
include $this->template($this->cur_tpl . '/news');