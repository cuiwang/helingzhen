<?php
global $_W,$_GPC;
$rid = intval($_GPC['id']);
$type = $_GPC['type'];
$uniacid = $_W['uniacid'];

if (empty($rid)) {
	message('抱歉，参数错误！!', '', 'error');//调试代码
}


$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

$rule_keyword = pdo_fetch("select * from " . tablename('rule_keyword') . " where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
$key_word = $rule_keyword['content'];
$send_name = $this->substr_cut($_W['account']['name'],30);
include $this->template('other');