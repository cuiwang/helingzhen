<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;
$uid = $_POST['uid'];
$couponid = $_POST['couponid'];
$type = $_POST['typ'];
load()->model("mc");
load()->model("activity");
$user = mc_credit_fetch($uid);
if ($type == 2) {
    $list = activity_token_info($couponid, $this->_weid);
    $hh = '代金券';
} else {
    $list = activity_coupon_info($couponid, $this->_weid);
    $hh = '折扣券';
}
if (!$list) {
    exit('0');
}

if ($list['endtime'] < time()) {
    exit('1');
}

if (!$list['residue']) {
    exit('2');
}

if ($user['credit1'] < $list['credit']) {
    exit('3');
}

if ($type == 2) {
    $test = activity_token_grant($uid, $couponid, '', '用户使用' . $list['credit'] . '积分兑换');
} else {
    $test = activity_coupon_grant($uid, $couponid, '', '用户使用' . $list['credit'] . '积分兑换');
}
if ($test) {
    if ($list['credit']) {
        $data['credit1'] = $user['credit1'] - $list['credit'];
        pdo_update($this->members, $data, array('uid' => $uid, 'uniacid' => $this->_weid));
        $arr = array('uid' => $uid, 'uniacid' => $this->_weid, 'credittype' => 'credit1', 'num' => '-' . $list['credit'], 'operator' => $uid, 'module' => 'yc_hotelmanger', 'clerk_id' => 0, 'store_id' => 0, 'createtime' => time(), 'remark' => 'yc_hotel兑换' . $hh, 'clerk_type' => 1);
        pdo_insert($this->credits_record, $arr);
    }

    exit('4');
    return;
}

exit('5');
