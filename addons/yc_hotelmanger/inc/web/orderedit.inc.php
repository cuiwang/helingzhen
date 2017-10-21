<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;

if ($_POST) {
   if($_GPC['op']=='hexiao'){
       $success=1;
       $msg="顶替";
       exec(json_decode(array('success'=>$success,'msg'=>$msg)));
   }
    $seearr = $this->seearr;
    $order_id = $_GPC['order_id'];
    $list = pdo_fetch('SELECT * FROM ' . tablename($this->order) . ' WHERE uniacid = ' . $this->_weid . ' and order_id =' . $order_id);
    load()->model("mc");
    $uid = $result = mc_openid2uid($list['openid']);
    $members = mc_credit_fetch($uid);
    $momy['credit2'] = $members['credit2'] + $list['totalcpice'];
    $arr = array('uid' => $uid, 'uniacid' => $this->_weid, 'credittype' => 'credit2', 'num' => $list['totalcpice'], 'operator' => $uid, 'module' => 'yc_hotelmanger', 'clerk_id' => 0, 'store_id' => 0, 'createtime' => time(), 'remark' => 'yc_hotel订单退款' . $list['totalcpice'], 'clerk_type' => 2);
    $data['order_status'] = $_GPC['order_status'];
    if ($data['order_status'] == 3) {
        $data['sjsintdate'] = time();
    } else if ($data['order_status'] == 4) {
        $data['sjsoutdate'] = time();
    }
    if (pdo_update($this->order, $data, array('uniacid' => $this->_weid, 'order_id' => $order_id)) === false) {
        message('设置失败!', '', 'error');
        return;
    }


    if ($data['order_status'] == 7) {
        $list = pdo_fetch('SELECT * FROM ' . tablename($this->order) . ' WHERE uniacid = ' . $this->_weid . ' and order_id =' . $order_id);
        $mem_list = pdo_update($this->members, $momy, array('uid' => $uid, 'uniacid' => $this->_weid));

        if ($mem_list) {
            pdo_insert($this->credits_record, $arr);

            if ($seearr['istplnotice'] == 1) {
                $this->get_hoteluserkk($seearr, $order_id, 2);
            }
        }
    }


    message("设置成功！", referer(), "success");
    return;
}


$order_id = $_GPC['order_id'];

if (!$this->seearr) {
    message('请先配置基本设置!', $this->createWebUrl('Setting'), 'success');
}


$list = pdo_fetch('SELECT * FROM ' . tablename($this->order) . ' WHERE uniacid = ' . $this->_weid . ' and order_id =' . $order_id);

if (!$list) {
    message('该订单信息不存在！', '', 'error');
}


include $this->template('orderedit');
