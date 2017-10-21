<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->model('reply');
load()->func('tpl');

// message($rid);

if($operation == 'addad'){
    $id = intval($_GPC['id']);
    $bpid = intval($_GPC['bpid']);
    $vedioid = intval($_GPC['vedioid']);
    $fanshb = intval($_GPC['fhbid']);
    if(empty($bpid)||empty($vedioid)||empty($fanshb)||empty($id)){
        message('参数错误!！', '', 'error');
    }
    $insert = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'mobtitle' => $_GPC['mobtitle'],

    );
    $insert_video = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'vodio_bg11' => $_GPC['vodio_bg11'],
    );
    $insert_bp = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'ishb' => intval($_GPC['ishb']),
        'bp_bg' => $_GPC['bp_bg'],
        'bp_title' => $_GPC['bp_title'],
        'bp_music' => $_GPC['bp_music'],
        'bp_voice' => $_GPC['bp_voice'],
        'isbd' => intval($_GPC['isbd']),
        'bp_maxnum' => intval($_GPC['bp_maxnum']),
        'ispmd' => intval($_GPC['ispmd']),
        'bp_keyword' => $_GPC['bp_keyword'],
        'bp_listword' => $_GPC['bp_listword'],
    );


   if($_GPC['bp_opacity']>1||$_GPC['bp_opacity']<0){
       $_GPC['bp_opacity']=1;
   }
    $insert_fanshb = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'top_bg' => $_GPC['top_bg'],
        'bp_logo' => $_GPC['bp_logo'],
        'is_messages' => $_GPC['is_messages'],
        'bp_type' => $_GPC['bp_type'],
        'big_mobtitle' => $_GPC['big_mobtitle'],
        'bb_bgcoclor' => $_GPC['bb_bgcoclor'],
        'bp_opacity' => empty($_GPC['bp_opacity'])?1:$_GPC['bp_opacity'],
    );
    pdo_update('haoman_dpm_reply', $insert, array('id' => $id));
    pdo_update('haoman_dpm_mp4', $insert_video, array('id' => $vedioid));
    pdo_update('haoman_dpm_bpreply', $insert_bp, array('id' => $bpid));
    pdo_update('haoman_dpm_hb_setting', $insert_fanshb, array('id' => $fanshb));

    message("修改成功",$this->createWebUrl('bapin',array('rid'=>$rid)),"success");

}else{
    $video = pdo_fetch("select * from " . tablename('haoman_dpm_mp4') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));

    $bp = pdo_fetch("select * from " . tablename('haoman_dpm_bpreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    $fanshb = pdo_fetch("select * from " . tablename('haoman_dpm_hb_setting') . " where rid = :rid order by `id` asc", array(':rid' => $rid));

    include $this->template('setting_bp');

}