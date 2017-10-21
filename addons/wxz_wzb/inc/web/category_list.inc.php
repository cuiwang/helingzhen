<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];

$categorys = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_category')." WHERE uniacid=:uniacid ORDER BY sort ASC,dateline DESC",array(':uniacid'=>$uniacid));
$children = array();
foreach($categorys as $key => $v){
	$live = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid and cid=:cid ORDER BY sort ASC,dateline DESC",array(':uniacid'=>$uniacid,':cid'=>$v['id']));
	$wlive = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_wlive_setting')." WHERE uniacid=:uniacid and cid=:cid ORDER BY sort ASC,dateline DESC",array(':uniacid'=>$uniacid,':cid'=>$v['id']));
	$children[$v['id']] = array_merge($live,$wlive);
}
include $this->template('category_list');