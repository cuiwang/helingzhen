<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
$pici = intval($_GPC['pici']);
//$op = $_GPC['op'];
load()->model('reply');
$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if($op=="del_pici"){
    if(empty($pici)){
        message('您要删除的场次不存在，请确认！');
    }
    $rule = pdo_fetchall("select id from " . tablename('haoman_dpm_yyyuser') . " where rid=:rid and  pici = :pici ", array(':pici' => $pici,':rid'=>$rid));
    if (empty($rule)) {
        message('抱歉，参数错误！');
    }
    if (pdo_delete('haoman_dpm_yyyuser', array('pici' => $pici,'rid'=>$rid))) {
        message('删除成功！', referer(), 'success');
    }
}elseif ($op=="del_all"){
    $rule = pdo_fetchall("select id from " . tablename('haoman_dpm_yyyuser') . " where rid=:rid", array(':rid'=>$rid));

    $reply = pdo_fetch("select id from " . tablename('haoman_dpm_yyyreply') . " where rid=:rid", array(':rid'=>$rid));
    if (empty($rule)) {
        message('抱歉，参数错误！');
    }
    if (pdo_delete('haoman_dpm_yyyuser', array('rid'=>$rid))) {
        pdo_update('haoman_dpm_yyyreply', array('status' => 0,'pici'=>0), array('id' => $reply['id']));
        message('删除成功！', referer(), 'success');
    }
}elseif($op=="get_back"){
    $is_back = intval($_GPC['is_back']);
    $yyyid = intval($_GPC['yyyid']);

    if (empty($yyyid)) {
        message('抱歉，找不到该粉丝！', '', 'error');
    }
    $fans = pdo_fetch("select from_user from " . tablename('haoman_dpm_yyyuser') . " where id = :id ", array(':id' => $yyyid));


    pdo_update('haoman_dpm_messages', array('is_back' => $is_back), array('from_user' => $fans['from_user']));

    pdo_update('haoman_dpm_fans', array('is_back' => $is_back), array('from_user' => $fans['from_user']));


    $temp = pdo_update('haoman_dpm_yyyuser', array('is_back' => $is_back), array('id' => $yyyid));

    if($temp){
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


}elseif($op=="all_get_back"){
    foreach ($_GPC['idArr'] as $k=>$rid) {
        $rid = intval($rid);
        if ($rid == 0 ||$rid ==1)
            continue;
        $rule = pdo_fetch("select id,from_user from " . tablename('haoman_dpm_yyyuser') . " where id = :id ", array(':id' => $rid));
        if (empty($rule)) {
            message('抱歉，您选择的粉丝不存在或是已经被删除！', '', 'error');
        }
        pdo_update('haoman_dpm_yyyuser', array('is_back' => 1), array('id' => $rid));
        pdo_update('haoman_dpm_messages', array('is_back' => $is_back), array('from_user' => $rule['from_user']));

        pdo_update('haoman_dpm_fans', array('is_back' => $is_back), array('from_user' => $rule['from_user']));

    }

    $data = array(
        'flag' => 1,
        'msg' => "批量拉黑成功",
    );

    echo json_encode($data);
}