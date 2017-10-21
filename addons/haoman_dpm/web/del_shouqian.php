<?php
global $_W  ,$_GPC;
// checklogin();
$rid = intval($_GPC['rid']);
$pici = intval($_GPC['id']);
//$op = $_GPC['op'];
load()->model('reply');
$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if($op=="del_pici"){
    if(empty($pici)){
        message('您要删除的手签不存在，请确认！');
    }
    $rule = pdo_fetch("select id from " . tablename('haoman_dpm_shouqian') . " where rid=:rid and  id = :id ", array(':id' => $pici,':rid'=>$rid));
    if (empty($rule)) {
        message('抱歉，参数错误！');
    }
    if (pdo_delete('haoman_dpm_shouqian', array('id' => $pici,'rid'=>$rid))) {
        message('删除成功！', referer(), 'success');
    }
}elseif ($op=="del_all"){
    $rule = pdo_fetchall("select id from " . tablename('haoman_dpm_shouqian') . " where rid=:rid", array(':rid'=>$rid));

    if (empty($rule)) {
        message('抱歉，参数错误！');
    }
    if (pdo_delete('haoman_dpm_shouqian', array('rid'=>$rid))) {

        message('删除成功！', referer(), 'success');
    }
}