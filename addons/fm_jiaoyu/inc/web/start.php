<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
global $_GPC, $_W;

$weid = $_W['uniacid'];
$this1 = 'no1';
$action = 'start';

$GLOBALS['frames'] = $this->getNaveMenu($_GPC['schoolid'],$action);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$logo = pdo_fetch("SELECT logo,title,is_cost,tpic,spic FROM " . tablename($this->table_index) . " WHERE id = '{$schoolid}'");

if ($operation == 'display') {
    if(!empty($_GPC['addtime'])) {
        $starttime = strtotime($_GPC['addtime']['start']);
        $endtime = strtotime($_GPC['addtime']['end']) + 86399;
        $condition1 .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
        $condition5 .= " AND createtime > '{$_GPC['addtime']['start']}' AND createtime < '{$_GPC['addtime']['end']}'";
        $condition6 .= " AND createdate > '{$starttime}' AND createdate < '{$endtime}'";
        $condition7 .= " AND jiontime > '{$starttime}' AND jiontime < '{$endtime}'";
        $condition2 .= " AND paytime > '{$starttime}' AND paytime < '{$endtime}'";
    } else {
        $starttime = strtotime('-180 day');
        $endtime = TIMESTAMP;
    }

    $start = mktime(0,0,0,date("m"),date("d"),date("Y"));
    $end = $start + 86399;
    $condition3 .= " AND createtime > '{$start}' AND createtime < '{$end}'";
    $condition4 .= " AND paytime > '{$start}' AND paytime < '{$end}'";
    $params[':start'] = $starttime;
    $params[':end'] = $endtime;
    $bm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ");
    $bjq = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ");
    $kq = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ");
    $dd = pdo_fetchall('SELECT SUM(cose) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND status = 2 ");

    $ddzj = $dd[0]['SUM(cose)'];
    $baom = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition3");
    $bjqz = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition3");
    $checklog = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' And schoolid = '{$schoolid}' And isconfirm = 1 $condition3");
    $cost = pdo_fetchall('SELECT SUM(cose) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND status = 2 $condition4");
    $cose = $cost[0]['SUM(cose)'];
    $ybjs = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_user) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND sid = 0 $condition5");
    $ybxs = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_user) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND tid = 0 $condition5");
    $baomzj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition1");
    $bjqzj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition1");
    $checklogzj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition1");
    $xczj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition1");
    $jszj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_teachers) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition7");
    $xszj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_students) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition6");
    $cost1 = pdo_fetchall('SELECT SUM(cose) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' AND status = 2 $condition2");
    $cose1 = $cost1[0]['SUM(cose)'];
    $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}'  $condition2 ");
    $data = pdo_fetchall('SELECT * FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition2 ORDER BY paytime DESC LIMIT 0,20");
    $total = array();
    if(!empty($data)) {
        foreach($data as &$da) {
            $total_price = $da['cose'];
            $key = date('Y-m-d', $da['paytime']);
            $return[$key]['cose'] += $total_price;
            $return[$key]['count'] += 1;
            $total['total_price'] += $total_price;
            $total['total_count'] += 1;
            if($da['paytype'] == '1') {
                $return[$key]['1'] += $total_price;
                $total['total_alipay'] += $total_price;
            } elseif($da['paytype'] == '2') {
                $return[$key]['2'] += $total_price;
                $total['total_wechat'] += $total_price;
            }
        }
    }

    $lastbjq = pdo_fetchall("SELECT uid,shername,createtime,content,isopen,bj_id1 FROM " . tablename($this->table_bjq) . " where schoolid = '{$schoolid}' And weid = '{$weid}' And type = 0 ORDER BY createtime DESC LIMIT 0,10");
    foreach ($lastbjq as $index => $row) {
        $member = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid And uid = :uid ORDER BY uid ASC", array(':uniacid' => $weid, ':uid' => $row['uid']));
        $bj = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id1']));
        $lastbjq[$index]['bjname'] = $bj['sname'];
        $lastbjq[$index]['avatar'] = $member['avatar'];
        $lastbjq[$index]['time'] = sub_day($row['createtime']);
    }
    $lasttz = pdo_fetchall("SELECT * FROM ".tablename($this->table_notice)." WHERE weid = '{$weid}' And schoolid = '{$schoolid}' ORDER BY createtime DESC LIMIT 0,10");
    foreach($lasttz as $key => $row){
        $bj = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $row['bj_id']));
        $ls = pdo_fetch("SELECT thumb FROM " . tablename($this->table_teachers) . " WHERE id = :id", array(':id' => $row['tid']));
        $lasttz[$key]['bjname'] = $bj['sname'];
        $lasttz[$key]['thumb'] = $ls['thumb'];
        $lasttz[$key]['time'] = sub_day($row['createtime']);
    }
    $lastkq = pdo_fetchall("SELECT * FROM " . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' ORDER BY createtime DESC LIMIT 0,10");
    foreach($lastkq as $index =>$row){
        $student = pdo_fetch("SELECT s_name,icon FROM " . tablename($this->table_students) . " WHERE id = '{$row['sid']}' ");
        $teacher = pdo_fetch("SELECT tname FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['tid']}' ");
        $qdtid = pdo_fetch("SELECT tname,thumb FROM " . tablename($this->table_teachers) . " WHERE id = '{$row['qdtid']}' ");
        $idcard = pdo_fetch("SELECT pname FROM " . tablename($this->table_idcard) . " WHERE idcard = '{$row['cardid']}' ");
        $mac = pdo_fetch("SELECT name FROM " . tablename($this->table_checkmac) . " WHERE schoolid = '{$row['schoolid']}' And id = '{$row['macid']}' ");
        $banji = pdo_fetch("SELECT sname FROM " . tablename($this->table_classify) . " WHERE sid = '{$row['bj_id']}' ");
        $lastkq[$index]['s_name'] = $student['s_name'];
        $lastkq[$index]['sicon'] = $student['icon'];
        $lastkq[$index]['tname'] = $teacher['tname'];
        $lastkq[$index]['thumb'] = $teacher['thumb'];
        $lastkq[$index]['qdtname'] = $qdtid['tname'];
        $lastkq[$index]['mac'] = $mac['name'];
        $lastkq[$index]['pname'] = $idcard['pname'];
        $lastkq[$index]['bj_name'] = $banji['sname'];
        $lastkq[$index]['time'] = sub_day($row['createtime']);
    }

    include $this->template ( 'web/start' );
}
if($operation == 'a') {
    if(!empty($_GPC['start'])) {
        $starttime = strtotime($_GPC['start']);
        $endtime = strtotime($_GPC['end']) + 86399;
    } else {
        $starttime = 0;
        $endtime = TIMESTAMP;
    }
    if($_W['isajax'] && $_W['ispost']) {
        $datasets = array(
            'unionpay' => array('name' => '银联支付', 'value' => 0),
            'alipay' => array('name' => '支付宝支付', 'value' => 0),
            'baifubao' => array('name' => '百付宝支付', 'value' => 0),
            'wechat' => array('name' => '微信支付', 'value' => 0),
            'cash' => array('name' => '现金支付', 'value' => 0),
            'credit' => array('name' => '余额支付', 'value' => 0)
        );
        $data = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . 'WHERE weid = :weid AND schoolid = :schoolid and status = 2 and paytime >= :starttime and paytime <= :endtime', array(':weid' => $weid, ':schoolid' => $schoolid, ':starttime' => $starttime, 'endtime' => $endtime));
        foreach($data as $da) {
            if(in_array($da['pay_type'], array_keys($datasets))) {
                $datasets[$da['pay_type']]['value'] += 1;
            }
        }
        $datasets = array_values($datasets);
        message(error(0, $datasets), '', 'ajax');
    }
}
if($operation == 'b') {
    if(!empty($_GPC['start'])) {
        $starttime = strtotime($_GPC['start']);
        $endtime = strtotime($_GPC['end']) + 86399;
        $condition .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
    } else {
        $starttime = strtotime('-30 day');
        $endtime = TIMESTAMP;
        $condition .= "";
    }
    $bjq = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_bjq) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ");
    $bm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_signup) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ");
    $xc = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_media) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' And type = 2 $condition ");
    $tz = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_notice) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ");
    $kq = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_checklog) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' $condition ");
    $ly = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' And isliuyan = 0 $condition ");
    $qj = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_leave) . " WHERE weid = '{$weid}' AND schoolid = '{$schoolid}' And isliuyan = 1 And isfrist = 1 $condition ");
    if($_W['isajax'] && $_W['ispost']) {
        $datasets = array(
            'bjq' => array('name' => '班级圈', 'value' => $bjq),
            'bm' => array('name' => '在线报名', 'value' => $bm),
            'tz' => array('name' => '通知公告', 'value' => $tz),
            'kq' => array('name' => '打卡考勤', 'value' => $kq),
            'ly' => array('name' => '在线留言', 'value' => $ly),
            'xc' => array('name' => '相册', 'value' => $xc),
            'qj' => array('name' => '在线请假', 'value' => $qj)
        );
        $datasets = array_values($datasets);
        message(error(0, $datasets), '', 'ajax');
    }
}

?>