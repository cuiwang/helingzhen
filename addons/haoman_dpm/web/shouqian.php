<?php
global $_W  ,$_GPC;
checklogin();
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];
$_GPC['do']='shouqian';
load()->model('reply');
load()->func('tpl');
$sql = "uniacid = :uniacid and `module` = :module";
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$params[':module'] = 'haoman_dpm';

$rowlist = reply_search($sql, $params);

foreach ($rowlist as $k => $v) {
    $rowlist[$k]['awardstotal'] = pdo_fetchcolumn('select count(id) from ' . tablename('haoman_dpm_shouqian') . 'where uniacid = :uniacid and rid=:rid', array(':uniacid' => $_W['uniacid'],':rid' => $v['id']));
    if(empty($rowlist[$k]['awardstotal'])){
        $rowlist[$k]['awardstotal'] = 0;
    }
}
include $this->template('shouqianlist');