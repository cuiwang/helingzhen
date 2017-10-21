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


if ($_POST) {
    $id = $_GPC['id'];
    $url = $this->createWebUrl('shopadd',array('id'=>$id));
    $data = array('uniacid' => $this->_weid, 'typename' => $_GPC['typename'], 'goods_name' => $_GPC['goods_name'],'goods_pic' => $_GPC['goods_pic'],'number' => $_GPC['number'], 'goods_yprice' => $_GPC['goods_yprice'], 'goods_xprice' => $_GPC['goods_xprice'], 'goods_images' => serialize($_GPC['goods_images']), 'goods_info' => serialize($_GPC['goods_info']), 'goods_sort' => $_GPC['goods_sort']);
 
    if ($id) {
        $where['uniacid'] = $this->_weid;
        $where['id'] = $id;

        if (pdo_update($this->shop, $data, $where) === false) {
            message('商品信息更新失败!', $url, 'error');
            return;
        }


        message("商品信息更新成功!", $url, 'success');
        return;
    }


    $result = pdo_insert($this->shop, $data);

    if ($result) {
        message('商品信息保存成功!', $url, 'success');
        return;
    }


    message("商品信息保存失败!", $url, 'error');
    return;
}

$id = intval($_GPC['id']);

if ($id) {
    $where['uniacid'] = $this->_weid;
    $where['id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename($this->shop) . 'WHERE  uniacid = ' . $this->_weid . ' and id =' . $id);
    !count($item) && message('商品信息不存在或以删除!', $url, 'error');
    $goods_info = iunserializer($item['goods_info']);
    $goods_images = iunserializer($item['goods_images']);
}
 
include $this->template('shopadd');
