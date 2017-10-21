<?php
/**
 *易福源码网 http://www.efwww.com
 * User: user
 * Date: 15-3-13
 * Time: 下午5:09
 * To change this template use File | Settings | File Templates.
 */

global $_W,$_GPC;
$pindex= max(1, intval($_GPC['page']));
$psize= 20;
$weid=$_W['uniacid'];
$openid=$_W['openid'];
$setting= $this->get_sysset($weid);

$condition= " WHERE `weid`='{$weid}'";
if($setting && $setting['isadjuest']==0){
    $condition .= " AND status='1' ";
}
$type = $_GPC['type'];
if ($type!='') {
    $condition .= " AND `type` ={$type} " ;
} 
$list= pdo_fetchall('SELECT * FROM '.tablename('amouse_house')." $condition ORDER BY createtime DESC LIMIT ".($pindex -1) * $psize.','.$psize); //分页
$total= pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename('amouse_house').$condition);
$slides = pdo_fetchall("select * from ".tablename('amouse_house_slide')." where weid = ".$weid." and isshow = 1 order by listorder desc");
$pager = pagination($total, $pindex, $psize);

$pageend=ceil($total/$psize);
if($total/$psize!=0 && $total>=$psize){
    $pageend++;
}
$url=$_W['siteroot']."app/".substr($this->createMobileUrl('index'),2);
include $this->template('house/index');