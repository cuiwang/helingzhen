<?php
global $_GPC, $_W;
$rid = intval($_GPC['id']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];
$len = intval($_GPC['_id']);
$bpreply = pdo_fetch("SELECT status FROM " . tablename('haoman_dpm_bpreply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));

if($bpreply['status']==1){
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and status =1 and is_back !=1 and is_xy !=1 and is_bpshow = 0  ORDER BY id DESC limit 200",array(':rid'=>$rid,':uniacid'=>$uniacid));

}else{
    $list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_messages') . " WHERE rid = :rid and uniacid = :uniacid and is_back !=1 and is_xy !=1 and is_bpshow = 0  ORDER BY id DESC limit 200",array(':rid'=>$rid,':uniacid'=>$uniacid));
}

foreach ($list as &$v){
    $v['createtime'] = date("Y-m-d H:i:s", $v['createtime']) ;
    if($v['wordimg']){
        $v['wordimg'] = tomedia($v['wordimg']);
    }

}
unset($v);
if($list){
    $result = array(
        'isResultTrue' => 1,
        'resultMsg' =>$list,
    );
    $this->message($result);
}else{
    $result = array(
        'isResultTrue' => 0,
    );
    $this->message($result);
}