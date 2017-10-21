<?php
global $_W  ,$_GPC;
checklogin();
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];
$_GPC['do']='code';
load()->model('reply');
load()->func('tpl');
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';
$awardtitle = '抢红包';
$turntable = 2;

$rowlist = reply_search($sql, $params);
foreach ($rowlist as $k => $v) {
	$rowlist[$k]['awardstotal'] = pdo_fetchcolumn('select SUM(awardstotal) from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid=:rid and turntable=:turntable', array(':turntable' => $turntable,':uniacid' => $_W['uniacid'],':rid' => $v['id']));
	if(empty($rowlist[$k]['awardstotal'])){
		$rowlist[$k]['awardstotal'] = 0;
	}
	$rowlist[$k]['prizedraw'] = pdo_fetchcolumn('select SUM(prizedraw) from ' . tablename('haoman_dpm_prize') . 'where uniacid = :uniacid and rid=:rid and turntable=:turntable', array(':turntable' => $turntable,':uniacid' => $_W['uniacid'],':rid' => $v['id']));
}
include $this->template('code');