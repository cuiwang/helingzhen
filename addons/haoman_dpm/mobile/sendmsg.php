<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$uid = $_GPC['uid'];
$uniacid = $_W['uniacid'];


$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where rid = '" . $rid . "' and from_user='" . $uid . "'");


$content = $_GPC['content'];

$image = $_GPC['image'];

$insert = array(
    'uniacid' => $uniacid,
    'avatar' => $fans['avatar'],
    'nickname' => $fans['nickname'],
    'from_user' => $fans['from_user'],
    'word' => $content,
    'wordimg' => $image,
    'rid' => $rid,
    'status' => 1,
    'is_back' => $fans['is_back'],
    'is_xy' =>0,
    'is_bp' =>0,
    'type' =>0,
    'gift' =>0,
    'createtime' => time(),
);
$temp = pdo_insert('haoman_dpm_messages',$insert);
pdo_update('haoman_dpm_fans', array('last_onlinetime' => time()), array('id' => $fans['id']));

$result = array(
    'code' => 1,
    'data' => "提交成功！",

);

$this->message($result);