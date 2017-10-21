<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->model('reply');
load()->func('tpl');

// message($rid);

if($operation == 'addad'){
    $id = intval($_GPC['punishmentid']);
    $punishment_content = $_GPC['punishment_content'];
    if(empty($punishment_content)){
        message("惩罚项目不能为空",$this->createWebUrl('turnplate_set',array('rid'=>$rid)),"success");
    }
    $insert_punishment = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'is_punishment' => intval($_GPC['is_punishment']),
        'punishment_title' => $_GPC['punishment_title'],
        'punishment_content' => $_GPC['punishment_content'],
        'punishment_bg' => $_GPC['punishment_bg'],
        'punishment_img' => $_GPC['punishment_img'],
        'punishment_pointer' => $_GPC['punishment_pointer'],
        'punishment_music' => $_GPC['punishment_music'],
        'createtime' => time(),
    );
   if(empty($id)){
     pdo_insert('haoman_dpm_punishment', $insert_punishment);
       message("添加成功",$this->createWebUrl('turnplate_set',array('rid'=>$rid)),"success");
   }else{
       pdo_update('haoman_dpm_punishment', $insert_punishment, array('id' => $id));
       message("修改成功",$this->createWebUrl('turnplate_set',array('rid'=>$rid)),"success");
   }




}else{
    $punishment = pdo_fetch("select * from " . tablename('haoman_dpm_punishment') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    if(empty($punishment['punishment_bg'])){
        $punishment['punishment_bg']='../addons/haoman_dpm/img12/mob_turnplate.jpg';
    }
    if(empty($punishment['punishment_img'])){
        $punishment['punishment_img']='../addons/haoman_dpm/img12/turnplate-bg2.png';
    }
    if(empty($punishment['punishment_pointer'])){
        $punishment['punishment_pointer']='../addons/haoman_dpm/img12/turnplate-pointer2.png';
    }
    include $this->template('turnplate_set');

}