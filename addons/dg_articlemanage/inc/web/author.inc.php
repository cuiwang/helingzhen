<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/31
 * Time: 10:56
 */
global $_W,$_GPC;
load()->func('tpl');
$kwd = trim($_GPC['keyword']);
$params = array();
$params[':uniacid'] = $_W['uniacid'];
$condition = " and uniacid=:uniacid";
if (!empty($kwd)) {
    $condition .= " AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )";
    $params[':keyword'] = "%{$kwd}%";
}
$ds = pdo_fetchall('SELECT id,avatar,nickname,openid,realname,mobile FROM ' . tablename('dg_article_author') . " WHERE 1 {$condition} order by createtime desc", $params);
include $this->template('author');