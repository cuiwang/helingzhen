<?php
global $_GPC, $_W;
$is_back = intval($_GPC['is_back']);

$fansid = intval($_GPC['fansid']);

$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if (empty($fansid)) {
    message('抱歉，找不到该粉丝！', '', 'error');
}
$fans = pdo_fetch("select from_user,nickname,mobile from " . tablename('haoman_dpm_fans') . " where id = :id ", array(':id' => $fansid));

if(empty($fans)){
    message('抱歉，找不到该粉丝！', '', 'error');
}

if($operation=='set_admin'){

    $admin = pdo_fetch("select id from " . tablename('haoman_dpm_bpadmin') . "  where admin_openid=:admin_openid and rid=:rid", array(':admin_openid' => $fans['from_user'],':rid'=>$rid));
    if($admin){
        message('该openid已经是管理员了，请不要重复设置', '', 'error');
    }else{


        $updata = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'bpadmin' => 3,
            'free_times' => 0,
            'admin_name' => $fans['nickname'],
            'admin_mobile' => $fans['mobile'],
            'admin_openid' => $fans['from_user'],
            'status' => 0,
            'set_hb' => 0,
            'createtime' => time(),
        );

        // message($keywords['name']);

        $temp = pdo_insert('haoman_dpm_bpadmin', $updata);

        message("添加管理员成功",$this->createWebUrl('adminshow',array('rid'=>$rid)),"success");
    }
}elseif ($operation=='del_admin'){

    pdo_delete('haoman_dpm_bpadmin', array('admin_openid' => $fans['from_user'],'rid'=>$rid));
    message("删除管理员成功",referer(),"success");
}else{
    $temps = pdo_update('haoman_dpm_fans', array('is_back' => $is_back), array('id' => $fansid));

    $temp = pdo_update('haoman_dpm_messages', array('is_back' => $is_back), array('from_user' => $fans['from_user']));
        pdo_update('haoman_dpm_yyyuser', array('is_back' => $is_back), array('from_user' => $fans['from_user']));

    if($temps){
        if($is_back == 1){
            message('拉黑成功！', referer(), 'success');
        }else{
            message('取消拉黑成功！', referer(), 'success');
        }
    }else{
        if($is_back == 1){
            message('拉黑失败！', referer(), 'success');
        }else{
            message('取消拉黑失败！', referer(), 'success');
        }
    }
}




