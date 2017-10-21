<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/15
 * Time: 10:54
 */
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
$pindex = max(1, intval($_GPC['page']));
$cfg=$this->module['config'];
$title=empty($cfg['dg_article_title']) ? $_W['account']['name'].'付费阅读' : $cfg['dg_article_title'];
$psize=empty($cfg['dg_article_num']) ? 20 : intval($cfg['dg_article_num']);
$cid=$_GPC['pid'];//分类
$pcid=$_GPC['cid'];
$category=pdo_fetchall("select id,name,parentid from ".tablename('dg_article_category')."where uniacid=:uniacid and parentid=0 order by displayorder desc,id desc",array(":uniacid"=>$uniacid));//所有分类
$count=pdo_fetchcolumn("select count(*) from ".tablename('dg_article')."where uniacid=:uniacid order by id desc",array(':uniacid'=>$uniacid));//总数
if(!empty($cid)){
    if(!empty($pcid)){
        $condition=" and pcate={$cid} and ccate={$pcid}";
    }else{
        $condition=" and pcate={$cid}";
    }
}
$sql="select * from ".tablename('dg_article')."where uniacid={$uniacid}".$condition." and status=2 order by displayorder desc ,id desc limit ".intval($pindex-1).",".$psize;
$article=pdo_fetchall($sql);
foreach($article as &$item){
    $ausql="select * from ".tablename('dg_article_author')." where uniacid=:uniacid and id=:id";
    $auparms=array(":uniacid"=>$uniacid,":id"=>$item['author_id']);
    $authorinfo=pdo_fetch($ausql,$auparms);
    $item['thumb']=tomedia($item['thumb']);
    $item['createtime']=date('Y-m-d',$item['createtime']);
    if(!empty($authorinfo['nickname'])){
        $item['aname']=$authorinfo['nickname'];
    }
    unset($item);
}
//$pager = pagination($count, $pindex, $psize);
$share=pdo_fetch("SELECT * FROM ".tablename("dg_article_share")." WHERE uniacid=:uniacid",array(":uniacid"=>$uniacid));
$shareimg=tomedia($share['shareimg']);
$sharedesc=$share['sharedesc'];
$sharetitle=$share['sharetitle'];

if($_W["account"]["level"]<4){
    $fansinfo=$this->getUserInfo();
    $object = json_decode($fansinfo);
    $openid=$object->openid;
    $nick=$object->nickname;
    $headimg=$object->headimgurl;
}else{
    $openid=$_W['openid'];
    $nick=$_W['fans']['tag']['nickname'];
    $headimg=$_W['fans']['tag']['avatar'];
}
$adv=pdo_fetchall("select * from ".tablename('dg_article_adv')." where uniacid={$uniacid} and adv_status=2 order by displayorder desc");
$rand=rand(000000,999999);
include $this->template('payred_index');
