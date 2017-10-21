<?php
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/common.php";
global $_W, $_GPC;
$settings = $this->module['config'];
$uniacid = $_W['uniacid'];
$modulename = $this->modulename;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$id = $_GPC['id'];

if(empty($id)){
    message("活动id不得为空");
}

if ($op == 'display') {
  $list = pdo_fetchall("select u.openid,u.headimgurl,u.nickname,sum(cj_counter) as num from ".tablename('cgc_baoming_user')." u WHERE u.activity_id=$id and u.uniacid=$uniacid and cj_code!='' group by u.openid,u.headimgurl,u.nickname order by num desc limit 100");
  include $this->template('ranking');
}
