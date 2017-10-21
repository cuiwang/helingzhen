<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->model('reply');
load()->func('tpl');

// message($rid);

if($operation == 'addad'){
    $id = intval($_GPC['id']);

    $insert_vote = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'vote_title' =>$_GPC['vote_title'],
        'vote_banner' => $_GPC['vote_banner'],
        'vote_bgcolor' => $_GPC['vote_bgcolor'],
        'vote_opcolor' => $_GPC['vote_opcolor'],
        'vote_status' => $_GPC['vote_status'],
        'type' => intval($_GPC['type']),
        'createtime' => time(),
    );
    if(empty($id)||$id==0){
        $id = pdo_insert('haoman_dpm_newvote_set', $insert_vote);
    }else{
        pdo_update('haoman_dpm_newvote_set', $insert_vote, array('id' => $id));
    }


    message("设置成功",$this->createWebUrl('new_vote',array('rid'=>$rid)),"success");

}else{
     $vote = pdo_fetch("select * from " . tablename('haoman_dpm_newvote_set') . " where rid = :rid order by `id` asc", array(':rid' => $rid));

    include $this->template('setting_vote');

}