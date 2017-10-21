<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$from_user = $_W['openid'];
$uniacid = $_W['uniacid'];

$reply = pdo_fetch("SELECT is_realname FROM " . tablename('haoman_dpm_reply') . " WHERE uniacid=:uniacid AND rid = :rid LIMIT 1", array(':uniacid' => $uniacid, ':rid' => $rid));



$list = pdo_fetchall("SELECT * FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and is_back !=1  ORDER BY id DESC ",array(':rid'=>$rid,':uniacid'=>$uniacid));

$res = pdo_fetchall("SELECT from_user FROM " . tablename('haoman_dpm_award') . " WHERE rid = :rid and uniacid = :uniacid and turntable = :turntable and probalilty = :probalilty ORDER BY id DESC",array(':rid'=>$rid,':uniacid'=>$uniacid,':turntable'=>3,':probalilty'=>1));

    if(!empty($res)){

        foreach ($res as $k => $v) {
           // $list = array_merge(array_diff($list, $v['from_user']));

            $ckres[$k] = $v['from_user'];
        }
                foreach ($list as $k => $v) {
                    if(in_array($v['from_user'],$ckres)){

                        unset($list[$k]);


                    }
                }
        $list=array_values($list);
    }

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