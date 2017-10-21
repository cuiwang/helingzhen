<?php
/**
 * Created by PhpStorm.
 * User: yike
 * Date: 2016/4/27
 * Time: 17:02
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$kwd = trim($_GPC['keyword']);
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$condition = " and m.uniacid=:uniacid";
if (!empty($kwd)) {
    $condition .= " AND ( `m`.`nickname` LIKE :keyword or `m`.`realname` LIKE :keyword or `m`.`mobile` LIKE :keyword )";
    $params[':keyword'] = "%{$kwd}%";
}
$ds = pdo_fetchall('SELECT m.uid,m.avatar,m.nickname,m.realname,m.mobile,f.openid FROM ' . tablename('mc_members') . " m LEFT JOIN ". tablename('mc_mapping_fans')." f ON f.uid = m.uid WHERE 1 {$condition} order by createtime desc", $params);
// var_dump($ds);
include $this->template('web/query');