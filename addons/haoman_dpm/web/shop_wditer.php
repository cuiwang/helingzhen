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
        'wditer_name' => $_GPC['wditer_name'],
        'wditer_mobile' => $_GPC['wditer_mobile'],
        'status' => intval($_GPC['status']),
        'createtime' => time(),
    );


    $temp =  pdo_update('haoman_dpm_shop_wditer',$updata,array('id'=>$id));

    message("修改服务员成功",$this->createWebUrl('shop_wditer',array('rid'=>$rid)),"success");


}elseif($operation == 'addadmin'){

    // message($_GPC['cardname']);
    if($_GPC['wditer_openid']==''){
        message('OPENID必须填', '', 'error');
    }
    $from_user = trim($_GPC['wditer_openid']);
    $fans = pdo_fetch("select id from " . tablename('haoman_dpm_fans') . "  where from_user=:from_user ", array(':from_user' => $from_user));
    if($fans==false){
        message('未查询到该openid记录,请检查', '', 'error');
    }else{
        $wditer = pdo_fetch("select id from " . tablename('haoman_dpm_shop_wditer') . "  where  rid=:rid and wditer_openid=:wditer_openid ", array(':wditer_openid' => $from_user,':rid'=>$rid));
        if($wditer){
            message('该openid已经是服务员了，请不要重复设置', '', 'error');
        }else{

            $updata = array(
                'rid' => $rid,
                'uniacid' => $_W['uniacid'],
                'wditer_name' => $_GPC['wditer_name'],
                'wditer_mobile' => $_GPC['wditer_mobile'],
                'wditer_openid' => $from_user,
                'status' => intval($_GPC['status']),
                'createtime' => time(),
            );

            // message($keywords['name']);

            $temp = pdo_insert('haoman_dpm_shop_wditer', $updata);

            message("添加服务员成功",$this->createWebUrl('shop_wditer',array('rid'=>$rid)),"success");
        }
    }



}elseif($operation == 'up'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取管理员ID出错，请刷新后重试', '', 'error');
    }
    $item = pdo_fetch("select * from " . tablename('haoman_dpm_shop_wditer') . "  where id=:uid ", array(':uid' => $uid));
    $keywords = reply_single($item['rid']);
    include $this->template('updatashop_wditer');

}elseif($operation == 'del'){
    $uid = intval($_GPC['uid']);
    if(empty($uid)){
        message('获取管理员ID出错，请刷新后重试', '', 'error');
    }
    pdo_delete('haoman_dpm_shop_wditer', array('id' => $uid));
    message("删除管理员成功",$this->createWebUrl('shop_wditer',array('rid'=>$rid)),"success");

}elseif($operation == 'new'){
    $rid = intval($_GPC['rid']);
    if(empty($rid)){
        message('参数错误', '', 'error');
    }
//    $item = pdo_fetch("select * from " . tablename('haoman_dpm_bpadmin') . "  where id=:uid ", array(':uid' => $uid));
//    $keywords = reply_single($item['rid']);
    include $this->template('new_shop_wditer');

}else{


    $t = time();
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $sql = 'select * from ' . tablename('haoman_dpm_shop_wditer') . 'where uniacid = :uniacid and rid = :rid order by `id` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $prarm = array(':uniacid' => $_W['uniacid'] ,':rid' => $rid);
    $list = pdo_fetchall($sql, $prarm);
    $count = pdo_fetchcolumn('select count(*) from ' . tablename('haoman_dpm_shop_wditer') . 'where uniacid = :uniacid and rid = :rid', $prarm);
    $pager = pagination($count, $pindex, $psize);

    foreach ($list as &$k){
        $k['avatar']  = pdo_fetchcolumn("select avatar from " . tablename('haoman_dpm_fans') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid, ':from_user' => $k['wditer_openid']));

    }
    unset($k);
    foreach ($list as $k => $v) {
        $keywords = reply_single($v['rid']);
        $list[$k]['rulename'] = $keywords['name'];
    }

    load()->func('tpl');
    include $this->template('shop_wditer_show');

}