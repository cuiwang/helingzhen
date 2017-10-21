<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";

global $_W, $_GPC;
load()->func('tpl');
$settings = $this->module['config'];
$op = !empty ($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W["uniacid"];
$activity_id = $_GPC['activity_id'];

if (empty($activity_id)) {
    message("活动id不得为空");
}

if ($op == 'display') {
    $cgc_baoming_activity = new cgc_baoming_activity();
    if (!empty ($activity_id)) {
        $activity = $cgc_baoming_activity->getOne($activity_id);
    }

    $nickname = $_GPC['nickname'];
    if (!empty ($nickname)) {
        $con .= " and nickname like '%$nickname%'";
    }

    $list = pdo_fetchall("select u.openid,u.headimgurl,u.nickname,count(1) as num from " . tablename('cgc_baoming_user') . " u WHERE u.activity_id=$activity_id and u.uniacid=$uniacid $con group by u.openid,u.headimgurl,u.nickname order by count(1) desc");

    include $this->template('ranking');
}
