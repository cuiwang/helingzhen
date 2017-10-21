<?php 
defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];
$tx_total = $_GPC['tx_total'];
$content = $_GPC['content'];

if($tx_total<=0){

    $data = array(
        'success' => 100,
        'msg' => '您没有可以申请的金额。',
    );

    echo json_encode($data);
    exit;

}



$insertdata = array(
    'uniacid'=>$uniacid,
    'rid'=>$rid,
    'visitorsip'=>$_W['clientip'],
    'content'=>$content,
    'createtime'=>time(),
    'tx_total'=>$tx_total,
    'tx_status'=>0,
);

$temp = pdo_insert('haoman_dpm_paytxlog', $insertdata);
$txlogid = pdo_insertid();

if ($temp === false) {

    $data = array(
        'success' => 100,
        'msg' => '申请失败',
    );
    
   
} else {

    pdo_update('haoman_dpm_pay_order',array('txlogid'=>$txlogid),array('txlogid'=>0,'status'=>2,'pay_type'=>2));
    pdo_update('haoman_dpm_pay_order',array('txlogid'=>$txlogid),array('txlogid'=>0,'status'=>2,'pay_type'=>3));
//    pdo_update('haoman_dpm_pay_order',array('txlogid'=>$txlogid),array('txlogid'=>0,'status'=>2,'pay_type'=>4));

    $data = array(
        'success' => 1,
        'msg' => '申请成功',
    );

}

echo json_encode($data);
