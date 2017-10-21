<?php
global $_GPC, $_W;
$rid= $_GPC['rid'];
$_GPC['do']='newneiding';
$turntable = $_GPC['turntable'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uniacid = $_W['uniacid'];
load()->model('reply');
load()->func('tpl');
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';

$rowlist = reply_search($sql, $params);

$item = pdo_fetchall("select * from " . tablename('haoman_dpm_prize') . "  where rid=:rid and uniacid=:uniacid and turntable=:turntable", array(':rid' => $rid,':uniacid'=>$uniacid,':turntable'=>$turntable));


if($operation == 'updataneiding'){

$id = $_GPC['listid'];

$keywords = reply_single($_GPC['rulename']);
$fans = pdo_fetch("select id from " . tablename('haoman_dpm_fans') . "  where from_user=:from_user ", array(':from_user' => trim($_GPC['openid'])));

$updata = array(
    'rid'=>$rid,
    'rulename' => $keywords['name'],
    'uniacid' => $_W['uniacid'],
    'turntable' => $turntable,
    'openid' =>trim($_GPC['openid']),
    'realname' => $_GPC['realname'],
    'mobile' => $_GPC['mobile'],
    'prizeid' => $_GPC['prizeid'],
    'status' => $_GPC['status'],
    'fansid' => $fans['id'],
    'prizename' => $_GPC['prizename'],
);
$temp =  pdo_update('haoman_dpm_draw_default',$updata,array('id'=>$id));

message("更新内定人员成功",$this->createWebUrl('draw_default',array('rid' => $rid,'turntable'=>$turntable)),"success");


}elseif($operation == 'newneiding'){
$keywords = reply_single($_GPC['rulename']);
$fans = pdo_fetch("select id from " . tablename('haoman_dpm_fans') . "  where from_user=:from_user ", array(':from_user' => trim($_GPC['openid'])));

$updata = array(
    'rid'=>$rid,
    'rulename' => $keywords['name'],
    'uniacid' => $_W['uniacid'],
    'turntable' => $turntable,
    'openid' => trim($_GPC['openid']),
    'realname' => $_GPC['realname'],
    'mobile' => $_GPC['mobile'],
    'prizeid' => $_GPC['prizeid'],
    'status' => $_GPC['status'],
    'fansid' => $fans['id'],
    'prizename' => $_GPC['prizename'],
);

// message($keywords['name']);

$temp = pdo_insert('haoman_dpm_draw_default', $updata);

message("添加内定人员成功",$this->createWebUrl('draw_default',array('rid'=>$rid,'turntable'=>$turntable)),"success");

}elseif($operation == 'up'){

$uid = intval($_GPC['uid']);

$list = pdo_fetch("select * from " . tablename('haoman_dpm_draw_default') . "  where id=:uid ", array(':uid' => $uid));

$prizename = pdo_fetchcolumn("select prizename from " . tablename('haoman_dpm_prize') . "  where rid = :rid and uniacid=:uniacid and turntable=:turntable and id=:id", array(':id'=>$list['prizeid'],':rid' => $rid, ':uniacid' => $_W['uniacid'],':turntable'=>$turntable));

include $this->template('updataneiding');

}elseif($operation == 'del'){

$id = intval($_GPC['id']);
if(empty($id)){
    message('获取内定人员出错，请刷新后重试', '', 'error');
}
pdo_delete('haoman_dpm_draw_default', array('id' => $id));
message("删除内定人员成功",$this->createWebUrl('draw_default',array('rid'=>$rid,'turntable'=>$turntable)),"success");

}else{

include $this->template('newneiding');

}