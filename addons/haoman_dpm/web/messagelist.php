<?php
global $_GPC, $_W;
// checklogin();
$uniacid = $_W['uniacid'];
load()->model('reply');
load()->func('tpl');
$_GPC['do'] = 'messageslist';
$rid = $_GPC['rid'];


$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);

$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_messages') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select * from " . tablename('haoman_dpm_messages') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);

foreach($list as &$k){
    if($k['type']==2){
        $k['bptime']=  pdo_fetchcolumn("select ds_time from " . tablename('haoman_dpm_guest') . " where rid = :rid and turntable =2 and id=:id", array(':rid'=>$rid,":id"=>$k['gift_id']));
    }

}
unset($k);
include $this->template('messagelist');