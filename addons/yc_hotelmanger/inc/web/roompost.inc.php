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





$hotellist = pdo_fetchall('SELECT * FROM ' . tablename($this->hotel) . ' WHERE uniacid = ' . $this->_weid . ' ORDER BY id DESC');

$hotelid = $_GPC['hotelid'];



if ($_POST) {

    $thumbs = $_GPC['thumbs'];

    $id = $_GPC['id'];

    $url = $this->createWebUrl('roomList', array('hotelid' => $hotelid));

    $data = array(
        'uniacid' => $this->_weid, 
        'hotelid' => $_GPC['hotelid'], 
        'title' => $_GPC['title'], 
        'thumb' => $_GPC['thumb'], 
        'thumbs' => iserializer($thumbs), 
        'area' => $_GPC['area'], 
        'floor' => $_GPC['floor'], 
        'bed' => $_GPC['bed'], 
        'bedadd' => $_GPC['bedadd'], 
        'smoke' => $_GPC['smoke'],
        'mprice'=>intval($_GPC['mprice']), 
        'persons' => $_GPC['persons'], 
        'score' => $_GPC['score'], 
        'sales' => $_GPC['sales'],
        'wifit' => $_GPC['wifit'], 
        'status' => $_GPC['status'],
        'device' =>htmlspecialchars_decode( $_GPC['device']), 
        'isrecommand'=>intval($_GPC['isrecommand']) , //首页推荐
        'isbest'=>intval($_GPC['isbest']) , //特惠
        'ischosen'=>intval($_GPC['ischosen']) , //精选
        'istime' => intval($_GPC['istime']),//限时抢购
        'timestart' => strtotime($_GPC['timestart']),//秒杀开始
		'timeend' => strtotime($_GPC['timeend']),//秒杀结束
        'oprice'=>$_GPC['oprice'] , //门市价
        'credit'=>$_GPC['credit'] , //评分
        'addresstag'=>$_GPC['addresstag'] , //位置标签
        'cold' => intval($_GPC['cold']),//空调
        'hot' => intval($_GPC['hot']),//热水
       
    ); 
       



    if ($id) {

        $where['uniacid'] = $this->_weid;

        $where['hotelid'] = $_GPC['hotelid'];

        $where['id'] = $id;



        if (pdo_update($this->room, $data, $where) === false) {

            message('酒店房型信息编辑失败!', $url, 'error');

            return;

        }





        message("酒店房型信息编辑成功!", $url, 'success');

        return;

    }





    $result = pdo_insert($this->room, $data);



    if ($result) {

        message('酒店房型信息保存成功!', $url, 'success');

        return;

    }





    message("酒店房型信息保存失败!", $url, 'error');

    return;

}

if ($hotelid) {

    $hotel_list = pdo_fetch('SELECT * FROM ' . tablename($this->hotel) . 'WHERE  uniacid = ' . $this->_weid . ' and id = ' . $hotelid);

}





if ($_GPC['id']) {

    $item = pdo_fetch('SELECT * FROM ' . tablename($this->room) . 'WHERE uniacid = ' . $this->_weid . ' and hotelid =' . $_GPC['hotelid'] . ' and id =' . $_GPC['id']);

    !count($item) && message('酒店房型信息不存在或以删除!', '', 'error');

    $piclist = iunserializer($item['thumbs']);

}





$hotelroom = array(

    array('name' => '房间面积', 'danwei' => '平方米', 'value' => ''),

    array('name' => '楼层', 'danwei' => '平方米', 'value' => ''),

    array('name' => '床位', 'danwei' => '2米大床', 'value' => ''),

    array('name' => '是否加床', 'danwei' => '加床说明', 'value' => ''),

    array('name' => '无烟', 'danwei' => '无烟说明', 'value' => ''),

    array('name' => '宽带', 'danwei' => '无线（有线）', 'value' => ''),

    array('name' => '其他', 'danwei' => '其他信息', 'value' => '')

);

include $this->template('roompost');

