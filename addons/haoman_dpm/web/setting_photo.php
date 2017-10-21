<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->model('reply');
load()->func('tpl');

// message($rid);

if($operation == 'addad'){
    $photoid = intval($_GPC['photoid']);


    $insert_photo = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'photo_status' => $_GPC['photo_status'],
        'photo_type' => $_GPC['photo_type'],
        'photo_bg' => $_GPC['photo_bg'],
        'photo_voice' => $_GPC['photo_voice'],
        'photo_music' => $_GPC['photo_music'],
        'photo_title' => $_GPC['photo_title'],
        'photo_color' => $_GPC['photo_color'],
        'changetime' => $_GPC['changetime'],
        'is_phone' => $_GPC['is_phone'],
        'creattime' => time(),
    );


    if(empty($photoid)){
        pdo_insert('haoman_dpm_photo_setting', $insert_photo);
        message("添加成功",$this->createWebUrl('photo',array('rid'=>$rid)),"success");
    }else{
        pdo_update('haoman_dpm_photo_setting', $insert_photo, array('id' => $photoid));
        message("修改成功",$this->createWebUrl('photo',array('rid'=>$rid)),"success");
    }





}else{

    $photo = pdo_fetch("select * from " . tablename('haoman_dpm_photo_setting') . " where rid = :rid order by `id` asc", array(':rid' => $rid));

    include $this->template('setting_photo');

}