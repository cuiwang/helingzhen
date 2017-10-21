<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
if ($id == 0) {
    message('信息不存在!');
}

$news = pdo_fetch("SELECT * FROM " . tablename($this->table_news) . " WHERE weid = :weid AND id=:id LIMIT 1", array(':weid' => $_W['uniacid'], ':id' => $id));

if (empty($news)) {
    message('信息不存在!!');
} else {
    if (!empty($news['thumb'])) {
        $thumb = tomedia($news['thumb']);
    }
}

include $this->template($this->cur_tpl . '/news_detail');