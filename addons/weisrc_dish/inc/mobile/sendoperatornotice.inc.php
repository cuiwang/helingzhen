<?php
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$mode = 1;
$storeid = intval($_GPC['storeid']);
$tablesid = intval($_GPC['tablesid']);
$type = intval($_GPC['type']);
$setting = $this->getSetting();

if ($tablesid == 0) {
    exit('餐桌不存在！');
}

if ($storeid == 0) {
    exit('门店不存在！');
}

if (empty($from_user)) {
    message('会话已过期，请重新发送关键字!');
}

$fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE from_user=:from_user AND weid=:weid LIMIT 1", array(':from_user' => $from_user, ':weid' => $weid));
if (empty($fans)) {
    message('数据不存在');
}

if ($fans['noticetime'] != 0) {
    $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . "  WHERE weid=:weid AND id=:id ORDER BY id DESC LIMIT 1", array(':weid' => $weid, ':id' => $storeid));
    $notice_space_time = intval($store['notice_space_time']);
    $space_time = TIMESTAMP - $fans['noticetime'];
    if ($space_time <= $notice_space_time) {
        $need_time = $notice_space_time - $space_time;
        message('每次通知的间隔时间是'.$notice_space_time.'秒，还剩' . $need_time . '秒才能做下次操作');
    }
}

if ($setting['istplnotice'] == 1 && !empty($setting['tploperator'])) {
//    if ($setting['is_notice'] == 1) {
//        if (!empty($setting['tpluser'])) {
//            $tousers = explode(',', $setting['tpluser']);
//            foreach ($tousers as $key => $value) {
//                $this->sendAdminOperatorNotice(trim($value), $tablesid, $type, $setting, $storeid);
//            }
//        }
//    }
    $accounts = pdo_fetchall("SELECT * FROM " . tablename($this->table_account) . " WHERE weid = :weid AND storeid=:storeid AND status=2 AND is_notice_service=1 ORDER BY id DESC ", array(':weid' => $this->_weid, ':storeid' => $storeid));
    foreach ($accounts as $key => $value) {
        if (!empty($value['from_user'])) {
            $this->sendAdminOperatorNotice($value['from_user'], $tablesid, $type, $setting, $storeid);
        }
    }
}
//$tablesid
$table = pdo_fetch("SELECT * FROM " . tablename($this->table_tables) . " where weid = :weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $tablesid));
if (empty($table)) {
    exit('餐桌不存在！');
} else {
    $tablename = $table['title'];
}
$content = "{$tablename}号桌";
if ($type == 1) {
    $content .= "呼叫服务员";
} else if ($type == 2) {
    $content .= "要打包";
}
pdo_insert($this->table_service_log, array('storeid' => $storeid, 'weid' => $weid,'from_user' => $from_user,
    'content' => $content, 'dateline' => TIMESTAMP,'status' => 0));
pdo_update($this->table_fans, array('noticetime' => TIMESTAMP), array('id' => $fans['id']));
$url = $this->createMobileUrl('waplist', array('storeid' => $storeid, 'mode' => $mode, 'tablesid' => $tablesid), true);
message('已经通知服务员，请耐心等待!', $url, 'success');