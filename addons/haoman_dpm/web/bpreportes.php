<?php
global $_GPC, $_W;

$uniacid = $_W['uniacid'];
load()->model('reply');
load()->func('tpl');
$_GPC['do'] = 'bpreportes';
$rid = $_GPC['rid'];
$pay_type = $_GPC['pay_type'];
$delete = $_GPC['delete'];

$where = '';
$starttime = mktime(0,0,0,date('m'),1,date('Y'));
$endtime = time();


//昨日数据
$time = date("Y-m-d", strtotime("-1 day"));//获取昨天的时间戳
$ydtime = strtotime($time." 00:00:00");//获取昨天0点的时间戳
$ydtimet = strtotime($time." 23:59:59");//获取昨天0点的时间戳
$yesterday = pdo_fetchcolumn("select SUM(pay_total) from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and status = :status and isadmin != 1 and createtime >= {$ydtime} AND createtime <= {$ydtimet} and pay_type in(2,3) " , array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':status' => 2));
$yesterday = empty($yesterday) ? 0 : $yesterday;


//总收入数据
$totalSum = pdo_fetchcolumn("select SUM(pay_total) from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and status = :status and isadmin != 1 and pay_type in(2,3) " , array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':status' => 2));
$totalSum = empty($totalSum) ? 0 : $totalSum;

//已提现数据
$totalTX = pdo_fetchcolumn("select SUM(tx_total) from " . tablename('haoman_dpm_paytxlog') . " where rid = :rid and uniacid=:uniacid" , array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
$totalTX = empty($totalTX) ? 0 : $totalTX;

//未提现数据
$totalTXNone = pdo_fetchcolumn("select SUM(pay_total) from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and status = :status and isadmin != 1 and txlogid = 0 and pay_type in(2,3) " , array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':status' => 2));
$totalTXNone = empty($totalTXNone) ? 0 : $totalTXNone;

//列出各种最新50条的记录
$BPlists = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and pay_type=:pay_type order by id desc limit 50" , array(':rid' => $rid, ':uniacid' => $_W['uniacid'],'pay_type'=>2));
$DSlists = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and pay_type=:pay_type order by id desc limit 50" , array(':rid' => $rid, ':uniacid' => $_W['uniacid'],'pay_type'=>3));
foreach($DSlists as &$v){
    $v['bptime'] = pdo_fetchcolumn("select ds_time from " . tablename('haoman_dpm_guest') . " where id=:id " , array(':id'=>$v['pay_addr']));

}
unset($v);

$HBlists = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where rid = :rid and uniacid=:uniacid and pay_type=:pay_type order by id desc limit 50" , array(':rid' => $rid, ':uniacid' => $_W['uniacid'],'pay_type'=>4));


$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);

$total = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_paytxlog') . "  where rid = :rid and uniacid=:uniacid" . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 30;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select * from " . tablename('haoman_dpm_paytxlog') . " where rid = :rid and uniacid=:uniacid" . $where . " order by id desc " . $limit, $params);



include $this->template('bpreportes');