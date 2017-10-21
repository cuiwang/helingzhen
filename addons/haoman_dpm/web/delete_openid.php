<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$del_openid = $_GPC['del_openid'];

$reply = pdo_fetch("select * from " . tablename('haoman_dpm_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
$fans = pdo_fetch("select * from " . tablename('haoman_dpm_fans') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));

$yyyfans = pdo_fetch("select * from " . tablename('haoman_dpm_yyyuser') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
if(ISCUSTOM == 1 && CUSTOM_VERSION == 'ZNL'){
$znlfans = pdo_fetch("select * from " . tablename('haoman_dpm_znl_user') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
}
$datas = pdo_fetchall("select * from " . tablename('haoman_dpm_data') . " where fromuser=:fromuser and rid=:rid", array(':fromuser' => $del_openid,':rid'=>$rid));
$award = pdo_fetchall("select * from " . tablename('haoman_dpm_award') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
$message = pdo_fetchall("select * from " . tablename('haoman_dpm_messages') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
$payorder = pdo_fetchall("select * from " . tablename('haoman_dpm_pay_order') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
$toupiao = pdo_fetchall("select * from " . tablename('haoman_dpm_tp_log') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));
$shouqian = pdo_fetchall("select * from " . tablename('haoman_dpm_shouqian') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));

// $password = pdo_fetchall("select * from " . tablename('haoman_dpm_password') . " where from_user=:from_user and rid=:rid", array(':from_user' => $del_openid,':rid'=>$rid));

if (empty($fans)) {
	$data = array(
		'success' => 0,
		'msg' => '抱歉，要删除的帐号不存在或是已经被删除！',
	);
}
else{
	pdo_delete('haoman_dpm_fans', array('from_user' => $del_openid,'rid'=>$rid));
	// pdo_update('haoman_dpm_reply', array('fansnum' => $reply['fansnum'] - 1), array('id' => $reply['id']));
    if(!empty($yyyfans)){
        pdo_delete('haoman_dpm_yyyuser', array('from_user' => $del_openid,'rid'=>$rid));
    }
	if(!empty($datas)){
		pdo_delete('haoman_dpm_data', array('fromuser' => $del_openid,'rid'=>$rid));
	}
	if(!empty($award)){
		pdo_delete('haoman_dpm_award', array('from_user' => $del_openid,'rid'=>$rid));
	}
	if(!empty($message)){
		pdo_delete('haoman_dpm_messages', array('from_user' => $del_openid,'rid'=>$rid));
	}
    if(!empty($payorder)){
        pdo_delete('haoman_dpm_pay_order', array('from_user' => $del_openid,'rid'=>$rid));
    }
    if(!empty($shouqian)){
        pdo_delete('haoman_dpm_shouqian', array('from_user' => $del_openid,'rid'=>$rid));
    }
    if(!empty($toupiao)){
        foreach ($toupiao as $v){
            $tp = pdo_fetch("select * from " . tablename('haoman_dpm_toupiao') . " where id=:id and rid=:rid", array(':id' => $v['toupiaoip'],':rid'=>$rid));
            pdo_update('haoman_dpm_toupiao',array('get_num'=>$tp['get_num']-1),array('id'=>$tp['id']));
        }

        pdo_delete('haoman_dpm_tp_log', array('from_user' => $del_openid,'rid'=>$rid));
    }
    if(!empty($znlfans)){
        pdo_delete('haoman_dpm_znl_user', array('from_user' => $del_openid,'rid'=>$rid));
    }
    
	$data = array(
		'success' => 1,
		'msg' => '删除成功',
	);

}
echo json_encode($data);