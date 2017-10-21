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
$starttime = time();
$endtime = time()+86400;
// message($rid);

if($operation == 'updataad'){

    $id = $_GPC['listid'];

    // message($_GPC['cardnum']);
    $keywords = reply_single($_GPC['rulename']);

    $updata = array(

        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'vote_starttime' => strtotime($_GPC['times']['start']),
        'vote_endtime' => strtotime($_GPC['times']['end']),
        'vote_name' => $_GPC['vote_name'],
        'vote_times' => $_GPC['vote_times'],
        'vote_img' => $_GPC['vote_img'],
        'vote_pid' => $_GPC['vote_pid'],
        'vote_description' => $_GPC['vote_description'],
        'vote_status' => $_GPC['vote_status'],
        'createtime' => time(),
    );


    $temp =  pdo_update('haoman_dpm_newvote',$updata,array('id'=>$id));

    message("修改项目成功",$this->createWebUrl('voteshow',array('rid'=>$rid)),"success");


}elseif($operation == 'addad'){

    // message($_GPC['cardname']);

//    $keywords = reply_single($_GPC['rulename']);

//    $randcode = $this->genkeyword(4);

    $updata = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'vote_starttime' => strtotime($_GPC['times']['start']),
        'vote_endtime' => strtotime($_GPC['times']['end']),
        'vote_name' => $_GPC['vote_name'],
        'vote_times' => $_GPC['vote_times'],
        'vote_img' => $_GPC['vote_img'],
        'vote_pid' => $_GPC['vote_pid'],
        'vote_people_times' => 0,
        'vote_all_times' => 0,
        'vote_description' => $_GPC['vote_description'],
        'vote_status' => $_GPC['vote_status'],
        'createtime' => time(),
    );


    // message($keywords['name']);

    $temp = pdo_insert('haoman_dpm_newvote', $updata);

    message("添加项目成功",$this->createWebUrl('voteshow',array('rid'=>$rid)),"success");

}elseif($operation == 'up'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取项目ID出错，请刷新后重试', '', 'error');
    }
    $item = pdo_fetch("select * from " . tablename('haoman_dpm_newvote') . "  where id=:uid ", array(':uid' => $uid));
    $keywords = reply_single($item['rid']);
    include $this->template('updatavote');

}elseif($operation == 'del'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取项目ID出错，请刷新后重试', '', 'error');
    }

    pdo_delete('haoman_dpm_newvote', array('id' => $uid));
    message("删除项目成功",$this->createWebUrl('voteshow',array('rid'=>$rid)),"success");

}else{


    include $this->template('newvote');

}