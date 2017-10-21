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

    // message($_GPC['cardnum']);
    $keywords = reply_single($_GPC['rulename']);

    $updata = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'bpadmin' => $_GPC['bpadmin'],
        'free_times' => $_GPC['free_times'],
        'admin_name' => $_GPC['admin_name'],
        'admin_mobile' => $_GPC['admin_mobile'],
        'status' => intval($_GPC['status']),
        'set_hb' => intval($_GPC['set_hb']),
//        'createtime' => time(),
    );


    $temp =  pdo_update('haoman_dpm_bpadmin',$updata,array('id'=>$id));

    message("修改管理员成功",$this->createWebUrl('adminshow',array('rid'=>$rid)),"success");


}elseif($operation == 'addadmin'){

    // message($_GPC['cardname']);
    if($_GPC['admin_openid']==''){
        message('管理员OPENID必须填', '', 'error');
    }
$from_user = trim($_GPC['admin_openid']);
    $fans = pdo_fetch("select id from " . tablename('haoman_dpm_fans') . "  where from_user=:from_user ", array(':from_user' => $from_user));
if($fans==false){
    message('未查询到该openid记录,请检查', '', 'error');
}else{
    $admin = pdo_fetch("select id from " . tablename('haoman_dpm_bpadmin') . "  where admin_openid=:admin_openid and rid=:rid", array(':admin_openid' => $from_user,':rid'=>$rid));
    if($admin){
        message('该openid已经是管理员了，请不要重复设置', '', 'error');
    }else{
        $keywords = reply_single($_GPC['rulename']);

        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'bpadmin' => $_GPC['bpadmin'],
            'free_times' => $_GPC['free_times'],
            'admin_name' => $_GPC['admin_name'],
            'admin_mobile' => $_GPC['admin_mobile'],
            'admin_openid' => $from_user,
            'status' => intval($_GPC['status']),
            'set_hb' => intval($_GPC['set_hb']),
            'createtime' => time(),
        );

        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_bpadmin', $updata);

        message("添加管理员成功",$this->createWebUrl('adminshow',array('rid'=>$rid)),"success");
    }
}



}elseif($operation == 'up'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取管理员ID出错，请刷新后重试', '', 'error');
    }
    $item = pdo_fetch("select * from " . tablename('haoman_dpm_bpadmin') . "  where id=:uid ", array(':uid' => $uid));
    $keywords = reply_single($item['rid']);
    include $this->template('updataadmin');

}elseif($operation == 'del'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取管理员ID出错，请刷新后重试', '', 'error');
    }
    pdo_delete('haoman_dpm_bpadmin', array('id' => $uid));
    message("删除管理员成功",$this->createWebUrl('adminshow',array('rid'=>$rid)),"success");

}else{


    include $this->template('newadmin');

}