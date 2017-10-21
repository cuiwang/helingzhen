<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];

$reply = pdo_fetch("SELECT is_realname FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));



$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and is_back !=1 and sex !=0 ORDER BY id DESC ",array(':rid'=>$rid,':uniacid'=>$uniacid));

if($reply['is_realname']==1){
    foreach ($list as &$v){
        $v['nickname']= $v['realname'];
        if(empty($v['nickname'])){
            $v['nickname'] = "匿名用户!";
        }
    }
    unset($v);
}

$result = $list;
$this->message($result);