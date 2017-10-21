<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/9/30
 * Time: 10:40
 */
global $_W,$_GPC;
load()->func('tpl');
$uniacid=$_W['uniacid'];
$aid=$_GPC['aid'];//作者的id
$data=array(
    'uniacid'=>$uniacid,
    'author_id'=>$aid
);
$sql="select * from ".tablename('dg_article')." where author_id=$aid and uniacid=$uniacid order by id desc limit 1";
$arit=pdo_fetch($sql);
if(!empty($arit)){
    if(empty($arit['content'])){
        $id=$arit['id'];
    }else{
        $result=pdo_insert('dg_article',$data);
        if(!empty($result)){
            $id=pdo_insertid();
        }
    }
}else{
    $result=pdo_insert('dg_article',$data);
    if(!empty($result)){
        $id=pdo_insertid();
    }
}
$str='abcdefjhigklmnopqrstuvwxyz';
$key="$id";
for($i=0;$i<6;$i++){
    $start=rand(0,strlen($str));
    $key.=substr($str,$start,1);
}
pdo_update('dg_article',array("key"=>$key),array('id'=>$id));
$articleurl= $_W['siteroot'].'web/setarticle.php?key='.$key;
include $this->template('pubarticle');