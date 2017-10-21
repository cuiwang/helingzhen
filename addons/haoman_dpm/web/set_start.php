<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->model('reply');
load()->func('tpl');
//$sql = "uniacid = :uniacid and `module` = :module";
//$params = array();
//$params[':uniacid'] = $_W['uniacid'];
//$params[':module'] = 'haoman_dpm';
//
//$rowlist = reply_search($sql, $params);

if(empty($rid)){
    message('参数错误！', '', 'error');
}
// message($rid);

if($operation == 'updataad'){

    $id = intval($_GPC['id']);
    $yyyid = intval($_GPC['yyyid']);
    $xysid = intval($_GPC['xysid']);
    $bpid = intval($_GPC['bpid']);
    $xyhid = intval($_GPC['xyhid']);
    $cjxid = intval($_GPC['cjxid']);
    $fanshb = intval($_GPC['fhbid']);
    $shouqianid = intval($_GPC['shouqianid']);

    if(empty($id)||empty($yyyid)||empty($xysid)||empty($bpid)||empty($xyhid)||empty($cjxid)||empty($fanshb)||empty($shouqianid)){
        message('参数错误，请先保存一次规则在来设置', '', 'error');
    }

    // message($_GPC['cardnum']);
    $keywords = reply_single($_GPC['rulename']);

    $insert = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'timenum' => $_GPC['timenum'],
        'ismessage' => intval($_GPC['ismessage']),
        'createtime' => time(),
        'isqd' => $_GPC['isqd'],
        'ischoujiang' => $_GPC['ischoujiang'],
        'isqhb' => $_GPC['isqhb'],
        'isjiabin' => $_GPC['isjiabin'],
        'share_type' => $_GPC['share_type'],
        'isbaoming' => $_GPC['isbaoming'],
        'istoupiao' => $_GPC['istoupiao'],
        'award_times' => $_GPC['award_times'],
        'most_num_times' => $_GPC['most_num_times'],
    );
    $insert_yyy = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'isyyy' => intval($_GPC['isyyy']),
    );
    $insert_xys = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'isxys' => intval($_GPC['isxys']),
        'is_pair' => $_GPC['is_pair'],
        'is_turntable' => $_GPC['is_turntable'],
    );
    $insert_bp = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'isbp' => intval($_GPC['isbp']),
        'isds' => intval($_GPC['isds']),
        'ismbp' => intval($_GPC['ismbp']),
        'isvo' => intval($_GPC['isvo']),
        'isbb' => intval($_GPC['isbb']),
        'is_gift' => intval($_GPC['is_gift']),
        'is_mf' => intval($_GPC['is_mf']),
        'is_img' => intval($_GPC['is_img']),
        'bd_starttime' => strtotime($_GPC['bd_times']['start']),
        'bd_endtime' => strtotime($_GPC['bd_times']['end']),
    );

    $insert_xyh = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'is_xyh' => intval($_GPC['is_xyh']),
        'is_xysjh' => intval($_GPC['is_xysjh']),
    );

    $insert_cjx = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'isCjxStart' => intval($_GPC['isCjxStart']),
    );


    $insert_fanshb = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'isfanshb' => intval($_GPC['isfanshb']),
        'hbtype' => intval($_GPC['hbtype']),
        'is_setadmin' => intval($_GPC['is_setadmin']),
        'is_ty' => intval($_GPC['is_ty']),
    );

    $insert_shouqian = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'status' => $_GPC['status'],
        'pm_status' => $_GPC['pm_status'],
    );

    pdo_update('haoman_dpm_reply', $insert, array('id' => $id));
    pdo_update('haoman_dpm_yyyreply', $insert_yyy, array('id' => $yyyid));
    pdo_update('haoman_dpm_xysreply', $insert_xys, array('id' => $xysid));
    pdo_update('haoman_dpm_bpreply', $insert_bp, array('id' => $bpid));
    pdo_update('haoman_dpm_xyhreply', $insert_xyh, array('id' => $xyhid));
    pdo_update('haoman_dpm_cjxreply', $insert_cjx, array('id' => $cjxid));
    pdo_update('haoman_dpm_hb_setting', $insert_fanshb, array('id' => $fanshb));
    pdo_update('haoman_dpm_shouqianBase', $insert_shouqian, array('id' => $shouqianid));


    $insert_mysql = array(
        'rid' => $rid,
        'uniacid' => $_W['uniacid'],
        'type' =>1,
        'ip' => CLIENT_IP,
        'createtime' => time(),
    );
    pdo_insert('haoman_dpm_change_mysql', $insert_mysql);

    message("修改成功",$this->createWebUrl('is_start',array('rid'=>$rid)),"success");


}else{

    $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
    $yyy = pdo_fetch("select * from " . tablename('haoman_dpm_yyyreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    $xys = pdo_fetch("select * from " . tablename('haoman_dpm_xysreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    $bp = pdo_fetch("select * from " . tablename('haoman_dpm_bpreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    $xyh = pdo_fetch("select * from " . tablename('haoman_dpm_xyhreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    $cjx = pdo_fetch("select * from " . tablename('haoman_dpm_cjxreply') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    $fanshb = pdo_fetch("select * from " . tablename('haoman_dpm_hb_setting') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
    $shouqian = pdo_fetch("select * from " . tablename('haoman_dpm_shouqianBase') . " where rid = :rid order by `id` asc", array(':rid' => $rid));

    include $this->template('newstart');

}