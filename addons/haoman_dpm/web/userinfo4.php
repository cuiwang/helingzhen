<?php
global $_GPC, $_W;

$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$starttime = time()-604800;
$endtime = time()+604800;

if ($_W['isajax']) {

    $id = intval($_GPC['fansid']);
    $uniacid = $_W['uniacid'];

    $params = array();
//    $bpadmin = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpadmin') . " WHERE uniacid = :uniacid AND id = :id", array(':uniacid' => $uniacid, ':id' => $id));
    $fanshb = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_hb_log') . " WHERE uniacid = :uniacid AND id = :id", array(':uniacid' => $uniacid, ':id' => $id));

    $condition = 'uniacid=:uniacid and prize = :prize';
//    $params[':uniacid'] = $_W['uniacid'];
    $params = array(':uniacid' => $_W['uniacid'],':prize'=>$id);
    if (!empty($_GPC['datestart'])) {
        $starttime = strtotime($_GPC['datestart']);
        $endtime   = strtotime($_GPC['dateend']);
        $condition .= " and createtime >:starttime and createtime <:endtime";
        $params[':starttime'] = $starttime;
        $params[':endtime']   = $endtime;
    }



    $dsdata = pdo_fetchall("select * from " . tablename('haoman_dpm_hb_award') . " where " . $condition . " order by id desc LIMIT 200 ", $params);



    include $this->template('userinfo4');
    exit();
}