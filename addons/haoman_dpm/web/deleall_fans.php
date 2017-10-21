<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
foreach ($_GPC['idArr'] as $k=>$id) {
    $id = intval($id);
    if ($id == 0 ||$id ==1)
        continue;
    $rule = pdo_fetch("select id,from_user from " . tablename('haoman_dpm_fans') . " where id = :id ", array(':id' => $id));
    if (empty($rule)) {
        message('抱歉，您选择的粉丝不存在或是已经被删除！', '', 'error');
    }
    $del_openid = $rule['from_user'];
    $reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
    $yyyfans = pdo_fetch("select * from " . tablename('haoman_dpm_yyyuser') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
    if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){
        $znlfans = pdo_fetch("select * from " . tablename('haoman_dpm_znl_user') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
    }
    $datas = pdo_fetchall("select * from " . tablename('haoman_dpm_data') . " where fromuser=:fromuser and rid=:rid", array(':fromuser' => $del_openid,':rid'=>$rid));
    $award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
    $message = pdo_fetchall("select * from " . tablename('haoman_dpm_messages') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
    $payorder = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
    $toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_tp_log') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
    pdo_delete('haoman_dpm_fans', array('id' => $id));

         pdo_update('haoman_dpm_reply', array('fansnum' => $reply['fansnum'] - 1), array('id' => $reply['id']));
        if (!empty($yyyfans)) {
            pdo_delete('haoman_dpm_yyyuser', array('from_user' => $del_openid, 'rid' => $rid));
        }
        if (!empty($datas)) {
            pdo_delete('haoman_dpm_data', array('fromuser' => $del_openid, 'rid' => $rid));
        }
        if (!empty($award)) {
            pdo_delete('haoman_dpm_award', array('from_user' => $del_openid, 'rid' => $rid));
        }
        if (!empty($message)) {
            pdo_delete('haoman_dpm_messages', array('from_user' => $del_openid, 'rid' => $rid));
        }
        if (!empty($payorder)) {
            pdo_delete('haoman_dpm_pay_order', array('from_user' => $del_openid, 'rid' => $rid));
        }
        if (!empty($toupiao)) {
            foreach ($toupiao as $v) {
                $tp = pdo_fetch("select * from " . tablename('haoman_dpm_toupiao') . " where id=:id and rid=:rid", array(':id' => $v['toupiaoip'], ':rid' => $rid));
                pdo_update('haoman_dpm_toupiao', array('get_num' => $tp['get_num'] - 1), array('id' => $tp['id']));
            }

            pdo_delete('haoman_dpm_tp_log', array('from_user' => $del_openid, 'rid' => $rid));
        }
        if (!empty($znlfans)) {
            pdo_delete('haoman_dpm_znl_user', array('from_user' => $del_openid, 'rid' => $rid));
        }

}

$data = array(
    'flag' => 1,
    'msg' => "批量删除成功",
);

echo json_encode($data);
// message('批量审核成功！', referer(), 'success');