<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
//$table_name = 'weixin_bahe_team';
$data = array();
if($_W['isajax']){
	$data['team1_cnt'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1'));
	$data['team2_cnt'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2'));
	$data['data1'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team ORDER BY createtime DESC LIMIT 0,7",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1'));
	$data['data2'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team ORDER BY createtime DESC LIMIT 0,7 ",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2'));
	$data['game_tug_del_user_1'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team AND old_team=:old_team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2',':old_team'=>'1'));
	$data['game_tug_del_user_2'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team AND old_team=:old_team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1',':old_team'=>'2'));
	die(json_encode($data));
}