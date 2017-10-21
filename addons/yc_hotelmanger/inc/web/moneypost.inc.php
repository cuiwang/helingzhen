<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $_GPC;
global $_W;

if (!$this->seearr) {
    message('请先配置基本设置!', $this->createWebUrl('Setting'), 'success');
}


$id = $_GPC['id'];

if ($_POST) {
    $url = $this->createWebUrl('roommoney', array('hotelid' => $_GPC['hotelid'], 'roomid' => $_GPC['roomid']));
    $data = array('uniacid' => $this->_weid, 'hotelid' => $_GPC['hotelid'], 'roomid' => $_GPC['roomid'], 'motitle' => $_GPC['motitle'], 'oprice' => $_GPC['oprice'], 'cprice' => $_GPC['cprice'], 'status' => $_GPC['status']);

    if ($id) {
        if (pdo_update($this->momy, $data, array('id' => $id, 'uniacid' => $_W['uniacid'])) === false) {
            message('酒店房型价格编辑失败!', '', 'error');
            return;
        }


        message("酒店房型价格编辑成功!", $url, 'success');
        return;
    }


    $result = pdo_insert($this->momy, $data);

    if ($result) {
        message('酒店房型价格保存成功!', $url, 'success');
        return;
    }


    message("酒店房型价格保存失败!", '', "error");
    return;
}


$hotelid = intval($_GPC['hotelid']);
$roomid = intval($_GPC['roomid']);
$id = $_GPC['id'];

if ($id) {
    $item = pdo_fetch('SELECT * FROM ' . tablename($this->momy) . ' WHERE uniacid = ' . $this->_weid . ' and hotelid = ' . $hotelid . ' and roomid = ' . $roomid . ' and id= ' . $id);
    !count($item) && message('酒店房型信息不存在或以删除!', '', 'error');
}


include $this->template('moneypost');
