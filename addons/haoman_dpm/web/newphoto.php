<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->model('reply');
load()->func('tpl');
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';

$rowlist = reply_search($sql, $params);

// message($rid);

if($operation == 'updataad'){

    $id = $_GPC['listid'];


    $keywords = reply_single($_GPC['rulename']);

    $updata = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'photo_pid' => $_GPC['photo_pid'],
        'photo' => $_GPC['photo'],
    );

    $temp =  pdo_update('haoman_dpm_photo_add',$updata,array('id'=>$id));

    message("修改图片成功",$this->createWebUrl('photoshow',array('rid'=>$rid)),"success");


}elseif($operation == 'addad'){

    $keywords = reply_single($_GPC['rulename']);

    $updata = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'photo_pid' => $_GPC['photo_pid'],
        'photo' => $_GPC['photo'],
    );

    // message($keywords['name']);

    $temp = pdo_insert('haoman_dpm_photo_add', $updata);

    message("添加图片成功",$this->createWebUrl('photoshow',array('rid'=>$rid)),"success");

}elseif($operation == 'up'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取ID出错，请刷新后重试', '', 'error');
    }
    $item = pdo_fetch("select * from " . tablename('haoman_dpm_photo_add') . "  where id=:uid ", array(':uid' => $uid));
    $keywords = reply_single($item['rid']);
    include $this->template('updataphoto');

}elseif($operation == 'del'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取ID出错，请刷新后重试', '', 'error');
    }
    pdo_delete('haoman_dpm_photo_add', array('id' => $uid));
    message("删除图片成功",$this->createWebUrl('photoshow',array('rid'=>$rid)),"success");

}else{


    include $this->template('newphoto');

}