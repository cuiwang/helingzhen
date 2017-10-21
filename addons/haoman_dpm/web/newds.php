<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$turntable = !empty($_GPC['turntable']) ? $_GPC['turntable'] : 'no';
load()->model('reply');
load()->func('tpl');
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';

$rowlist = reply_search($sql, $params);

// message($rid);

if($turntable==2){
    $_GPC['do']='礼物';
    $turntable = 2;
    if($operation == 'updataad'){

        $id = $_GPC['listid'];

        if($_GPC['ds_money']<=0){
            message('打赏金额最小值为0.01元，请留意', '', 'error');
        }
        if($_GPC['ds_time']<=0){
            message('打赏时间最小值为1秒，请留意', '', 'error');
        }
        // message($_GPC['cardnum']);
        $keywords = reply_single($_GPC['rulename']);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['gift_name'],
            'turntable' => $turntable,
            'price' => $_GPC['ds_money'],
            'ds_pic' => $_GPC['ds_pic'],
            'ds_vodiobg' => $_GPC['ds_vodiobg'],
            'pic' => $_GPC['pic'],
            'says' => $_GPC['ds_says'],
            'ds_time' => intval($_GPC['ds_time']),
            'status' => $_GPC['status'],
        );


        $temp =  pdo_update('haoman_dpm_guest',$updata,array('id'=>$id));

        message("修改礼物成功",$this->createWebUrl('dsshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");


    }elseif($operation == 'addad'){

        // message($_GPC['cardname']);
        if($_GPC['ds_money']<=0){
            message('打赏金额最小值为0.01元，请留意', '', 'error');
        }
        if($_GPC['ds_time']<=0){
            message('打赏时间最小值为1秒，请留意', '', 'error');
        }
        $keywords = reply_single($_GPC['rulename']);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['gift_name'],
            'turntable' => $turntable,
            'price' => $_GPC['ds_money'],
            'ds_pic' => $_GPC['ds_pic'],
            'ds_vodiobg' => $_GPC['ds_vodiobg'],
            'pic' => $_GPC['pic'],
            'says' => $_GPC['ds_says'],
            'ds_time' => intval($_GPC['ds_time']),
            'status' => $_GPC['status'],
        );

        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_guest', $updata);

        message("添加礼物成功",$this->createWebUrl('dsshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");

    }elseif($operation == 'up'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取礼物ID出错，请刷新后重试', '', 'error');
        }
        $item = pdo_fetch("select * from " . tablename('haoman_dpm_guest') . "  where id=:uid ", array(':uid' => $uid));
        $keywords = reply_single($item['rid']);
        include $this->template('updatads');

    }elseif($operation == 'del'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取奖品ID出错，请刷新后重试', '', 'error');
        }
        pdo_delete('haoman_dpm_guest', array('id' => $uid));
        pdo_update('haoman_dpm_messages',array('gift_id'=>0),array('gift_id'=>$uid));

        message("删除礼物成功",$this->createWebUrl('dsshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");

    }else{

//        $ds= pdo_fetch("select * from " . tablename('haoman_dpm_guest') . "  where rid=:rid ", array(':rid' => $rid));

        include $this->template('newds');

    }
}else{
    $_GPC['do']='项目';
    $turntable = 1;
    if($operation == 'updataad'){

        $id = $_GPC['listid'];

        // message($_GPC['cardnum']);
        $keywords = reply_single($_GPC['rulename']);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['gift_name'],
            'turntable' => $turntable,
            'pic' => $_GPC['pic'],
            'says' => $_GPC['ds_says'],
            'status' => $_GPC['status'],
        );


        $temp =  pdo_update('haoman_dpm_guest',$updata,array('id'=>$id));

        message("修改项目成功",$this->createWebUrl('dsshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");


    }elseif($operation == 'addad'){

        // message($_GPC['cardname']);

        $keywords = reply_single($_GPC['rulename']);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['gift_name'],
            'turntable' => $turntable,
            'pic' => $_GPC['pic'],
            'says' => $_GPC['ds_says'],
            'status' => $_GPC['status'],
        );

        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_guest', $updata);

        message("添加项目成功",$this->createWebUrl('dsshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");

    }elseif($operation == 'up'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取项目ID出错，请刷新后重试', '', 'error');
        }
        $item = pdo_fetch("select * from " . tablename('haoman_dpm_guest') . "  where id=:uid ", array(':uid' => $uid));
        $keywords = reply_single($item['rid']);
        include $this->template('updatads');

    }elseif($operation == 'del'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取项目ID出错，请刷新后重试', '', 'error');
        }
        pdo_delete('haoman_dpm_guest', array('id' => $uid));
        message("删除项目成功",$this->createWebUrl('dsshow',array('rid'=>$rid,'turntable'=>$turntable)),"success");

    }else{

//        $ds= pdo_fetch("select * from " . tablename('haoman_dpm_guest') . "  where rid=:rid ", array(':rid' => $rid));

        include $this->template('newds');

    }
}

