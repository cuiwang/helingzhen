<?php
global $_GPC, $_W;

$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$starttime = time()-604800;
$endtime = time()+604800;

if ($_W['isajax']) {

    $id = intval($_GPC['fansid']);
    $uniacid = $_W['uniacid'];

    $params = array();
    $bpadmin = pdo_fetch("SELECT * FROM " . tablename('haoman_dpm_bpadmin') . " WHERE uniacid = :uniacid AND id = :id", array(':uniacid' => $uniacid, ':id' => $id));
    $fans = pdo_fetch("SELECT avatar FROM " . tablename('haoman_dpm_fans') . " WHERE uniacid = :uniacid AND from_user = :from_user", array(':uniacid' => $uniacid, ':from_user' => $bpadmin['admin_openid']));

    $condition = 'uniacid=:uniacid and isadmin = :isadmin and status=:status';
//    $params[':uniacid'] = $_W['uniacid'];
    $params = array(':uniacid' => $_W['uniacid'],':isadmin'=>1,':status'=>2);
    if (!empty($_GPC['datestart'])) {
        $starttime = strtotime($_GPC['datestart']);
        $endtime   = strtotime($_GPC['dateend']);
        $condition .= " and createtime >:starttime and createtime <:endtime";
        $params[':starttime'] = $starttime;
        $params[':endtime']   = $endtime;
    }



    $dsdata = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where " . $condition . " order by id desc LIMIT 50 ", $params);

    $dsmoney =  pdo_fetch("select SUM(pay_total) as most_money FROM ".tablename('haoman_dpm_pay_order')." WHERE uniacid=:uniacid and status = :status and isadmin=:isadmin", array(':uniacid' => $_W['uniacid'],':isadmin'=>1,':status'=>2));
    $dsmoney = $dsmoney['most_money'];

//    $dsdata = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_pay_order') . " WHERE ".$condition."  ORDER BY `id` LIMIT 50",$params);

            foreach($dsdata as &$k){
//                $k['category'] = pdo_fetchcolumn('SELECT `name` FROM ' . tablename('haoman_virtuamall_category') . ' WHERE `weid` = :weid and id = :id',array(":weid" => $uniacid,':id'=>$k['category']));
                if($k['pay_type']==3){
                    $k['bptime']	= pdo_fetchcolumn('SELECT `ds_time` FROM ' . tablename('haoman_dpm_guest') . ' WHERE `id` = :id ',array(":id" => $k['pay_addr']));

                }
//                $k['says']	= pdo_fetchcolumn('SELECT `mobile` FROM ' . tablename('haoman_virtuamall_order') . ' WHERE `weid` = :weid and tandid = :tandid',array(":weid" => $uniacid,':tandid'=>$k['orderid']));

            }
            unset($k);

    include $this->template('userinfo3');
    exit();
}