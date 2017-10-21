<?php
global $_GPC, $_W;
// checklogin();
$uniacid = $_W['uniacid'];
load()->model('reply');
load()->func('tpl');
$_GPC['do'] = 'bporderlist';
$rid = $_GPC['rid'];
$pay_type = $_GPC['pay_type'];
$delete = $_GPC['delete'];

$where = '';
$starttime = mktime(0,0,0,date('m'),1,date('Y'));
$endtime = time();

$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid'],'pay_type'=>$pay_type);
if (!empty($_GPC['status'])) {
    $where .= ' and status=:status';
    $params[':status'] = $_GPC['status'];
}

if(!empty($_GPC['nickname'])){
    $where .= ' and nickname=:nickname';
    $params[':nickname'] = $_GPC['nickname'];
}
if (!empty($_GPC['time'])) {
    $starttime = strtotime($_GPC['time']['start']);
    $endtime   = strtotime($_GPC['time']['end']);
    $where .= " AND createtime >= :starttime AND createtime <= :endtime";
    $params[':starttime'] = $starttime;
    $params[':endtime']   = $endtime;
}

$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_pay_order') . "  where rid = :rid and uniacid=:uniacid  and pay_type=:pay_type" . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and pay_type=:pay_type" . $where . " order by id desc " . $limit, $params);

if($pay_type==3){
    foreach($list as &$v){
        $v['bptime'] = pdo_fetchcolumn("select ds_time from " . tablename('haoman_dpm_guest') . " where id=:id " , array(':id'=>$v['pay_addr']));

    }
unset($v);
}

$lists = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and pay_type=:pay_type order by id desc " , $params);
$num1 ='';
$num2 ='';
foreach ($lists as $v){
    if($v['pay_type']==$pay_type&&$v['status']==2){

        $num1+=$v['pay_total'];
    if($v['isadmin']!=1){
        $num2+=$v['pay_total'];
    }
    }
}

include $this->template('bporderlist');