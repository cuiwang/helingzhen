<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$bb_gift = !empty($_GPC['bb_gift']) ? $_GPC['bb_gift'] : 'no';
load()->model('reply');
load()->func('tpl');
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';

$rowlist = reply_search($sql, $params);

// message($rid);

if($bb_gift==2){
    $_GPC['do']='表白礼物';
    $bb_gift = 2;
    if($operation == 'updataad'){

        $id = $_GPC['listid'];

        if($_GPC['bb_name']==''){
            message('名称不能为空', '', 'error');
        }
        if($_GPC['bb_pic']==''){
            message('礼物图片不能为空', '', 'error');
        }
        // message($_GPC['cardnum']);
        $keywords = reply_single($rid);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'bb_name' => $_GPC['bb_name'],
            'type' => $bb_gift,
            'bb_price' => $_GPC['bb_price'],
            'bb_pic' => $_GPC['bb_pic'],
            'bb_vodiobg' => $_GPC['bb_vodiobg'],
            'bb_says' => $_GPC['bb_says'],
            'bb_time' => intval($_GPC['bb_time']),
            'status' => $_GPC['status'],
        );


        $temp =  pdo_update('haoman_dpm_bbgift',$updata,array('id'=>$id));

        message("修改表白礼物成功",$this->createWebUrl('dsshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");


    }elseif($operation == 'addad'){

        // message($_GPC['cardname']);
        if($_GPC['bb_name']==''){
            message('名称不能为空', '', 'error');
        }
        if($_GPC['bb_pic']==''){
            message('礼物图片不能为空', '', 'error');
        }
        $keywords = reply_single($rid);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'bb_name' => $_GPC['bb_name'],
            'type' => $bb_gift,
            'bb_price' => $_GPC['bb_price'],
            'bb_pic' => $_GPC['bb_pic'],
            'bb_vodiobg' => $_GPC['bb_vodiobg'],
            'bb_says' => $_GPC['bb_says'],
            'bb_time' => intval($_GPC['bb_time']),
            'status' => $_GPC['status'],
        );

        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_bbgift', $updata);

        message("添加表白礼物成功",$this->createWebUrl('bb_giftshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");

    }elseif($operation == 'up'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取礼物ID出错，请刷新后重试', '', 'error');
        }
        $item = pdo_fetch("select * from " . tablename('haoman_dpm_bbgift') . "  where id=:uid ", array(':uid' => $uid));
        $keywords = reply_single($item['rid']);
        include $this->template('updatabb_gift');

    }elseif($operation == 'del'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取奖品ID出错，请刷新后重试', '', 'error');
        }
        pdo_delete('haoman_dpm_bbgift', array('id' => $uid));

        message("删除礼物成功",$this->createWebUrl('bb_giftshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");

    }else{

//        $ds= pdo_fetch("select * from " . tablename('haoman_dpm_guest') . "  where rid=:rid ", array(':rid' => $rid));

        include $this->template('newbb_gift');

    }
}elseif($bb_gift == 1){
    $_GPC['do']='送礼礼物';
    $bb_gift = 1;
    if($operation == 'updataad'){

        $id = $_GPC['listid'];
        if($_GPC['bb_time']<1){
            message('时间不能小于1秒', '', 'error');
        }
        if($_GPC['bb_price']<0.01){
            message('金额不能小1分', '', 'error');
        }
        if($_GPC['bb_name']==''){
            message('名称不能为空', '', 'error');
        }
        if($_GPC['bb_pic']==''){
            message('礼物图片不能为空', '', 'error');
        }
        // message($_GPC['cardnum']);
        $keywords = reply_single($rid);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'bb_name' => $_GPC['bb_name'],
            'type' => $bb_gift,
            'bb_price' => $_GPC['bb_price'],
            'bb_pic' => $_GPC['bb_pic'],
            'bb_vodiobg' => $_GPC['bb_vodiobg'],
            'bb_says' => $_GPC['bb_says'],
            'bb_time' => intval($_GPC['bb_time']),
            'status' => $_GPC['status'],
        );


        $temp =  pdo_update('haoman_dpm_bbgift',$updata,array('id'=>$id));

        message("修改送礼礼物成功",$this->createWebUrl('bb_giftshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");


    }elseif($operation == 'addad'){

        // message($_GPC['cardname']);
        if($_GPC['bb_time']<1){
            message('时间不能小于1秒', '', 'error');
        }
        if($_GPC['bb_price']<0.01){
            message('金额不能小1分', '', 'error');
        }
        if($_GPC['bb_name']==''){
            message('名称不能为空', '', 'error');
        }
        if($_GPC['bb_pic']==''){
            message('礼物图片不能为空', '', 'error');
        }
        $keywords = reply_single($rid);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'bb_name' => $_GPC['bb_name'],
            'type' => $bb_gift,
            'bb_price' => $_GPC['bb_price'],
            'bb_pic' => $_GPC['bb_pic'],
            'bb_vodiobg' => $_GPC['bb_vodiobg'],
            'bb_says' => $_GPC['bb_says'],
            'bb_time' => intval($_GPC['bb_time']),
            'status' => $_GPC['status'],
        );

        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_bbgift', $updata);

        message("添加送礼礼物成功",$this->createWebUrl('bb_giftshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");

    }elseif($operation == 'up'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取ID出错，请刷新后重试', '', 'error');
        }
        $item = pdo_fetch("select * from " . tablename('haoman_dpm_bbgift') . "  where id=:uid ", array(':uid' => $uid));
        $keywords = reply_single($item['rid']);
        include $this->template('updatabb_gift');

    }elseif($operation == 'del'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取ID出错，请刷新后重试', '', 'error');
        }
        pdo_delete('haoman_dpm_bbgift', array('id' => $uid));
        message("删除送礼礼物成功",$this->createWebUrl('bb_giftshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");

    }else{

//        $ds= pdo_fetch("select * from " . tablename('haoman_dpm_guest') . "  where rid=:rid ", array(':rid' => $rid));

        include $this->template('newbb_gift');

    }
}else{
    $_GPC['do']='表白霸屏';
    $bb_gift = 3;
    if($operation == 'updataad'){

        $id = $_GPC['listid'];
        if($_GPC['bb_time']<1){
            message('时间不能小于1秒', '', 'error');
        }
        if($_GPC['bb_price']<0.01){
            message('金额不能小1分', '', 'error');
        }

        // message($_GPC['cardnum']);
        $keywords = reply_single($rid);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'bb_name' => $_GPC['bb_name'],
            'type' => $bb_gift,
            'bb_price' => $_GPC['bb_price'],
            'bb_pic' => $_GPC['bb_pic'],
            'bb_vodiobg' => $_GPC['bb_vodiobg'],
            'bb_says' => $_GPC['bb_says'],
            'bb_time' => intval($_GPC['bb_time']),
            'status' => $_GPC['status'],
        );


        $temp =  pdo_update('haoman_dpm_bbgift',$updata,array('id'=>$id));

        message("修改表白霸屏时间成功",$this->createWebUrl('bb_giftshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");


    }elseif($operation == 'addad'){

        // message($_GPC['cardname']);
        if($_GPC['bb_time']<1){
            message('时间不能小于1秒', '', 'error');
        }
        if($_GPC['bb_price']<0.01){
            message('金额不能小1分', '', 'error');
        }
        $keywords = reply_single($rid);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'bb_name' => $_GPC['bb_name'],
            'type' => $bb_gift,
            'bb_price' => $_GPC['bb_price'],
            'bb_pic' => $_GPC['bb_pic'],
            'bb_vodiobg' => $_GPC['bb_vodiobg'],
            'bb_says' => $_GPC['bb_says'],
            'bb_time' => intval($_GPC['bb_time']),
            'status' => $_GPC['status'],
        );

        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_bbgift', $updata);

        message("添加表白霸屏时间成功",$this->createWebUrl('bb_giftshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");

    }elseif($operation == 'up'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取ID出错，请刷新后重试', '', 'error');
        }
        $item = pdo_fetch("select * from " . tablename('haoman_dpm_bbgift') . "  where id=:uid ", array(':uid' => $uid));
        $keywords = reply_single($item['rid']);
        include $this->template('updatabb_gift');

    }elseif($operation == 'del'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取ID出错，请刷新后重试', '', 'error');
        }
        pdo_delete('haoman_dpm_bbgift', array('id' => $uid));
        message("删除表白霸屏时间成功",$this->createWebUrl('bb_giftshow',array('rid'=>$rid,'bb_gift'=>$bb_gift)),"success");

    }else{

//        $ds= pdo_fetch("select * from " . tablename('haoman_dpm_guest') . "  where rid=:rid ", array(':rid' => $rid));

        include $this->template('newbb_gift');

    }
}

