<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uniacid = $_W['uniacid'];

//所有奖品类别		
//    $reply = pdo_fetch("select turntable from " . tablename('haoman_dpm_reply') . " where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
$award = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
foreach ($award as $k => $awards) {
	$award[$k]['num'] = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and prizetype='" . $awards['id'] . "'", array(':rid' => $rid));
}
//所有奖品类别


if (empty($rid)) {
	message('抱歉，传递的参数错误！', '', 'error');
}
$where = '';
$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
if (!empty($_GPC['status'])) {
	$where.=' and a.status=:status';
	$params[':status'] = $_GPC['status'];
}
if (!empty($_GPC['nickname'])) {
	$where.=' and a.nickname LIKE :nickname';
	$params[':nickname'] = "%{$_GPC['nickname']}%";
}

$total = pdo_fetchcolumn("select count(a.id) from " . tablename('haoman_dpm_award') . " a where a.rid = :rid and a.uniacid=:uniacid " . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 12;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select a.* from " . tablename('haoman_dpm_award') . " a where a.rid = :rid and a.uniacid=:uniacid  " . $where . " order by a.id desc " . $limit, $params);

//中奖资料
foreach ($list as &$lists) {
	$lists['nickname'] = pdo_fetchcolumn("select nickname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
	$lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
	$lists['address'] = pdo_fetchcolumn("select address from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
	$lists['mobile'] = pdo_fetchcolumn("select mobile from " . tablename('haoman_dpm_fans') . " where from_user = :from_user and rid = :rid ", array(':from_user' => $lists['from_user'],':rid'=>$rid));
	$lists['ptype'] = pdo_fetchcolumn("select ptype from " . tablename('haoman_dpm_prize') . " where id = :id", array(':id' => $lists['prizetype']));
}


//中奖资料	
//一些参数的显示
$num1="";
$prizedraw = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . " where rid =:rid and uniacid = :uniacid",array(':rid' => $rid,'uniacid'=>$uniacid));
foreach($prizedraw as $k){
	$num1+=$k['awardstotal'];
}
$money = pdo_fetchall("select credit,status from " . tablename('haoman_dpm_award') . " where rid = " . $rid . " and turntable=2 and prizetype =0");
$tixian = pdo_fetchall("select awardname from " . tablename('haoman_dpm_cash') . " where rid = " . $rid . " and status !=1 ");


$most_money ='';
$wtx ='';
$tx ='';
foreach ($money as $v){
    $most_money +=$v['credit'];
    if($v['status']==1){
        $wtx += $v['credit'];
    }
}

foreach ($tixian as $k){
    $tx +=$k['awardname']/100;
}
$num0 = $most_money/100;
$numx = $wtx/100;

//     $num0 = pdo_fetchcolumn("select awardpassword from " . tablename('haoman_dpm_reply') . " where rid = :rid", array(':rid' => $rid));
//     $num1 = pdo_fetchcolumn("select count(id)from " . tablename('haoman_dpm_award') . " where rid = :rid", array(':rid' => $rid));
$num2 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and status=1", array(':rid' => $rid));
$num3 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and status=2", array(':rid' => $rid));
$num4 = pdo_fetchcolumn("select count(id) from " . tablename('haoman_dpm_award') . " where rid = :rid and status=0", array(':rid' => $rid));
//一些参数的显示
include $this->template('awardlist');