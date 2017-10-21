<?php
global $_GPC, $_W;

$rid = intval($_GPC['rid']);
$turntable = intval($_GPC['turntable']);

$uniacid = $_W['uniacid'];

$data = $_GPC['submitForm'];

foreach($data as $k=>$id){
    $newdata .= $id.',';
}

$picid = pdo_fetch("SELECT max(pici) FROM ".tablename('haoman_dpm_xyhm')." WHERE rid = :rid and uniacid = :uniacid", array(':uniacid' => $_W['uniacid'],':rid'=>$rid));
$picid = $picid['max(pici)'];

$pici = !empty($picid) ? ($picid+1) : 1;

$insert = array(
    'rid' => $rid,
    'uniacid' => $_W['uniacid'],
    'turntable' => $turntable,
    'number' => $newdata,
    'pici' => $pici,
    'createtime' => time(),

);
$stem = pdo_insert('haoman_dpm_xyhm', $insert);
$idata = array(
    'ret' => 1,
    'msg' => '保存成功！',
);

echo json_encode($idata);
// message('批量审核成功！', referer(), 'success');