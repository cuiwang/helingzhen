<?php
/**
 *易福源码网 http://www.efwww.com
 * User: shizhongying
 * Date: 15-3-13
 * Time: 下午8:05
 * To change this template use File | Settings | File Templates.
 */
global $_W,$_GPC;
load()->func('tpl');
$a= !empty($_GPC['a']) ? $_GPC['a'] : 'rent';
load()->func('file');
load()->func('tpl');

$wxid= !empty($_GPC['wxid']) ? $_GPC['wxid'] : $_W['fans']['from_user'];

$data = array(
    'weid'=> $_W['uniacid'],
    'title'=> $_GPC['title'],
    'price'=> $_GPC['price'],
    'square_price'=> $_GPC['square_price'],
    'area'=>$_GPC['area'],
    'house_type'=> $_GPC['house_type'],
    'floor'=> $_GPC['floor'],
    'orientation'=> $_GPC['orientation'],
    'createtime'=> TIMESTAMP,
    'type'=> $_GPC['type'],
    'status'=> 0,
    'recommed'=>0,
    'contacts'=> $_GPC['contacts'],
    'phone'=> $_GPC['phone'],
    'jjrphone'=> $_GPC['jjrphone'],
    'introduction'=> $_GPC['introduction'],
    'openid'=> $wxid,
    'thumb1' => $_GPC['thumb1'],
    'thumb2' => $_GPC['thumb2'],
    'thumb3' => $_GPC['thumb3'],
    'thumb4' => $_GPC['thumb4'],
);

if($a == 'rent') {
    if ($_W['ispost']) {
        pdo_insert('amouse_house',$data);
        message('提交房产信息成功，请等待审核!',$this->createMobileUrl('index',array('type'=>$_GPC['type'])),'success');
    }
    include $this->template('house/rent_new');
}elseif($a='house'){
    if ($_W['ispost']) {
        pdo_insert('amouse_house',$data);
        message('提交房产信息成功，请等待审核!',$this->createMobileUrl('index',array('type'=>$_GPC['type'])),'success');
    }
    include $this->template('house/house_new');
}