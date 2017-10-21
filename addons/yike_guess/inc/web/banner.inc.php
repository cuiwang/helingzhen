<?php
/**
 * Created by PhpStorm.
 * 幻灯片
 * User: yike
 * Date: 2016/6/15
 * Time: 11:20
 */
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'guess_list';
if ($op == 'banner_list') {
    $list = pdo_fetchall('select * from '.tablename('yike_guess_banner').' where uniacid = :uniacid order by sort desc',array(':uniacid' => $_W['uniacid']));
    load()->func('tpl');
}elseif ($op == 'banner_add') {
    $show_num = pdo_fetchcolumn('select count(*) from '.tablename('yike_guess_banner').'where is_show = 1 and uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
    if($_GPC['id']){
        $banner = pdo_fetch('select * from '.tablename('yike_guess_banner').' where id = :id', array(':id' => $_GPC['id']));
    }
    if (checksubmit()) {
        if(!empty($_GPC['sort'])){
            $sort = $_GPC['sort'];
        }else{
            $sort = 0;
        }
        $data = array(
            'sort' => $sort,
            'name' => $_GPC['name'],
            'image' => $_GPC['image'],
            'href' => $_GPC['href'],
            'is_show' => $_GPC['is_show']
        );
        if($_GPC['id']){
            pdo_update('yike_guess_banner', $data, array(
                'id' => $_GPC['id']
            ));
        }else{
            $data['uniacid'] = $_W['uniacid'];
            pdo_insert('yike_guess_banner', $data);
        }

        message('更新幻灯片成功！', $this->createWebUrl('banner', array(
            'op' => 'banner_list'
        )), 'success');
    }
}elseif ($op == 'banner_delete') {
    $id = intval($_GPC['id']);
    $row = pdo_fetch("SELECT * FROM " . tablename('yike_guess_banner') . " WHERE id = :id", array(':id' => $id));
    if (empty($row)) {
        message('抱歉，幻灯片不存在或是已经被删除！',$this->createWebUrl('banner', array('op' => 'banner_list')),'error');
    }
    pdo_delete('yike_guess_banner', array('id' => $id));
    message('幻灯片删除成功！', $this->createWebUrl('banner', array('op' => 'banner_list')), 'success');
}
include $this->template('web/banner');