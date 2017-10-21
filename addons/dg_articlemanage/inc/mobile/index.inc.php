<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/10
 * Time: 12:25
 */
global $_W,$_GPC;
$cid = intval($_GPC['cid']);
$uniacid=$_W['uniacid'];
if(empty($cid)){
    $cid = pdo_fetchcolumn("SELECT id FROM " . tablename('dg_article_category') . " where parentid=0 and uniacid=$uniacid limit 1");
}
$category = pdo_fetch("SELECT * FROM " . tablename('dg_article_category') . " WHERE id = '{$cid}'");
$title = $category['name'];
$op = $_GPC['op'];
if (!empty($category['thumb'])) {
    $shareimg = toimage($category['thumb']);
} else {
    $shareimg = IA_ROOT . '/addons/dg_articlemanage/icon.jpg';
}
$result = pdo_fetchall("SELECT * FROM " . tablename('dg_article_category') . " WHERE uniacid =$uniacid AND parentid = $cid ORDER BY displayorder desc, id ASC ");
if ($cid > 0) {
    //$sql = "SELECT id FROM " . tablename('dg_article_category') . " WHERE uniacid =$uniacid AND parentid = $cid ORDER BY createtime ASC limit 1";
    $defaultid = intval($_GPC['pid']);
   if ($defaultid>0) {
       $list = pdo_fetchall("SELECT * FROM " . tablename('dg_article') . " WHERE uniacid={$uniacid} AND pcate={$defaultid} AND ccate={$cid} ORDER BY displayorder desc,id desc  ");
    } else {
       $list = pdo_fetchall("SELECT * FROM " . tablename('dg_article') . " WHERE uniacid={$uniacid} AND pcate={$cid} ORDER BY displayorder desc,id desc ");

    }
} else {
    $list = pdo_fetchall("SELECT * FROM " . tablename('dg_article') . " WHERE uniacid=:uniacid ORDER BY displayorder desc, id desc ", array(':uniacid' => $uniacid));
}

$wechat = pdo_fetch("SELECT * FROM " . tablename('account_wechats') . " WHERE acid=:acid AND uniacid=:uniacid limit 1", array(':acid' => $uniacid, ':uniacid' => $uniacid));
$url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('Index', array('cid' => $cid, 'uniacid' => $uniacid), true), 2);
$openid=$_W['openid'];

include $this->template('list');

?>