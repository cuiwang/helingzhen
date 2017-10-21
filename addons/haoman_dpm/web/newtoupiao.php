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
//
//$vote = !empty($_GPC['vote']) ? $_GPC['vote'] : '1';


$vote_id = !empty($_GPC['vote_id']) ? $_GPC['vote_id'] : '0';

if($vote_id!=0){
    if($operation == 'updataad'){

        $id = $_GPC['listid'];

        // message($_GPC['cardnum']);
        $keywords = reply_single($_GPC['rulename']);

        $updata = array(

            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'starttime' => strtotime($_GPC['times']['start']),
            'endtime' => strtotime($_GPC['times']['end']),
            'back_time' => intval($_GPC['back_time']),
            'pid' => $_GPC['pid'],
            'name' => $_GPC['name'],
            'description' => $_GPC['description'],
            'avatar' => $_GPC['avatar'],
            'img' => $_GPC['img'],
            'status' => $_GPC['status'],
        );


        $temp =  pdo_update('haoman_dpm_toupiao',$updata,array('id'=>$id));

        message("修改投票成功",$this->createWebUrl('toupiaoshow',array('rid'=>$rid,'vote_id'=>$vote_id)),"success");


    }elseif($operation == 'addad'){

        // message($_GPC['cardname']);


        $randcode = $this->genkeyword(4);

        $updata = array(
            'number' => $randcode,
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'starttime' => strtotime($_GPC['times']['start']),
            'endtime' => strtotime($_GPC['times']['end']),
            'back_time' => intval($_GPC['back_time']),
            'pid' => $_GPC['pid'],
            'name' => $_GPC['name'],
            'description' => $_GPC['description'],
            'avatar' => $_GPC['avatar'],
            'img' => $_GPC['img'],
            'status' => $_GPC['status'],
            'vote_id' => $vote_id,
        );


        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_toupiao', $updata);

        message("添加投票成功",$this->createWebUrl('toupiaoshow',array('rid'=>$rid,'vote_id'=>$vote_id)),"success");

    }elseif($operation == 'up'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取投票ID出错，请刷新后重试', '', 'error');
        }
        $item = pdo_fetch("select * from " . tablename('haoman_dpm_toupiao') . "  where id=:uid ", array(':uid' => $uid));
        $keywords = reply_single($item['rid']);
        include $this->template('updatatoupiao');

    }elseif($operation == 'del'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取投票ID出错，请刷新后重试', '', 'error');
        }

        pdo_delete('haoman_dpm_toupiao', array('id' => $uid));
        message("删除投票成功",$this->createWebUrl('voteshow',array('rid'=>$rid)),"success");

    }else{


        include $this->template('newtoupiao');

    }
}else{

    if($operation == 'updataad'){

        $id = $_GPC['listid'];

        // message($_GPC['cardnum']);
        $keywords = reply_single($_GPC['rulename']);

        $updata = array(

            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'starttime' => strtotime($_GPC['times']['start']),
            'endtime' => strtotime($_GPC['times']['end']),
            'back_time' => intval($_GPC['back_time']),
            'pid' => $_GPC['pid'],
            'name' => $_GPC['name'],
            'description' => $_GPC['description'],
            'avatar' => $_GPC['avatar'],
            'img' => $_GPC['img'],
            'status' => $_GPC['status'],
            'vote_id' => 0,
        );


        $temp =  pdo_update('haoman_dpm_toupiao',$updata,array('id'=>$id));

        message("修改投票成功",$this->createWebUrl('toupiaoshow',array('rid'=>$rid)),"success");


    }elseif($operation == 'addad'){

        // message($_GPC['cardname']);

        $keywords = reply_single($_GPC['rulename']);

        $randcode = $this->genkeyword(4);

        $updata = array(
            'number' => $randcode,
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'starttime' => strtotime($_GPC['times']['start']),
            'endtime' => strtotime($_GPC['times']['end']),
            'back_time' => intval($_GPC['back_time']),
            'pid' => $_GPC['pid'],
            'name' => $_GPC['name'],
            'description' => $_GPC['description'],
            'avatar' => $_GPC['avatar'],
            'img' => $_GPC['img'],
            'status' => $_GPC['status'],
            'vote_id' => 0,
        );


        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_toupiao', $updata);

        message("添加投票成功",$this->createWebUrl('toupiaoshow',array('rid'=>$rid)),"success");

    }elseif($operation == 'up'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取投票ID出错，请刷新后重试', '', 'error');
        }
        $item = pdo_fetch("select * from " . tablename('haoman_dpm_toupiao') . "  where id=:uid ", array(':uid' => $uid));
        $keywords = reply_single($item['rid']);
        include $this->template('updatatoupiao');

    }elseif($operation == 'del'){
        $uid = intval($_GPC['uid']);
        if(empty($uid)){
            message('获取投票ID出错，请刷新后重试', '', 'error');
        }

        pdo_delete('haoman_dpm_toupiao', array('id' => $uid));
        message("删除投票成功",$this->createWebUrl('toupiaoshow',array('rid'=>$rid)),"success");

    }else{


        include $this->template('newtoupiao');

    }
}

