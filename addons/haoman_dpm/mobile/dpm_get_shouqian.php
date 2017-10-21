<?php

global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
$signID = intval($_GPC['signID']);

$shouqian = pdo_fetchall("select * from " . tablename('haoman_dpm_shouqian') . " where rid = :rid AND status = 0 AND id > {$signID} order by `id` desc limit 30", array(':rid' => $rid));

if(empty($shouqian)){

    $data = array(
        'flag'=>100,
    );

}else{
   foreach ($shouqian as $k => $v) {
        $imgs[$k] = $v['img'];
    }


    $data = array(
        'flag'=>1,
        'signID'=>$shouqian[0]['id'],
        'imgs'=>$imgs,
    ); 
}

echo json_encode($data);
