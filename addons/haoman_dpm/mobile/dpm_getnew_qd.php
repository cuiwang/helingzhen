<?php
global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$max_id = intval($_GPC['max_id']);

$list = pdo_fetchall("SELECT id,avatar,nickname,qdword FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid and avatar != '' and id > {$max_id} ORDER BY id  limit 1",array(':rid'=>$rid,':uniacid'=>$uniacid));
$number = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('haoman_dpm_fans') . " WHERE rid = :rid and uniacid = :uniacid ",array(':rid'=>$rid,':uniacid'=>$uniacid));
//foreach ($list as $k => $v) {
//    $numStr = strlen(trim(strrchr(tomedia($v['avatar']), '/'),'/'));
//    $list[$k]['thumb_image'] = substr_replace(tomedia($v['avatar']),'64',-$numStr);
//    $list[$k]['thumb_image_46'] = substr_replace(tomedia($v['avatar']),'46',-$numStr);
//}

    $data = array(
        'ret' => 0,
        'msg' => "success1",
        "max_id"=>$list[0]['id'],
        "record"=>$list,
        "number"=>$number
    );

echo json_encode($data);