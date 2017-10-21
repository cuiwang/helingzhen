<?php
/**
 * Created by PhpStorm.
 * User: chengbin
 * Date: 2016/8/17
 * Time: 10:48
 */
global $_W,$_GPC;
$pcate=$_GPC['pcate'];
$ccate=$_GPC['ccate'];
$uniacid=$_W['uniacid'];
$pindex = max(1, intval($_GPC['page']));
$cfg=$this->module['config'];
$psize=empty($cfg['dg_article_num']) ? 20 : intval($cfg['dg_article_num']);
if(!empty($_GPC['aid'])){
    $sql="select * from ".tablename('dg_article')." where  uniacid=:uniacid and pcate=:pcate and status=2 order by displayorder desc,id desc limit ".intval($pindex-1)*$psize.",".$psize;
    $parms=array(":uniacid"=>$uniacid,":pcate"=>$_GPC['aid']);
}elseif($_GPC['submit']=="true"){
    $sear=$_GPC['search'];
    $pcate=$_GPC['pcate'];
    $ccate=$_GPC['ccate'];
    $condition=" title like '%".$sear."%' and ";
    if(empty($pcate)&&empty($ccate)){
        $sql="select * from ".tablename('dg_article')." where ".$condition." uniacid=:uniacid and status=2 order by displayorder desc,id desc limit ".intval($pindex-1)*$psize.",".$psize;
        $parms=array(":uniacid"=>$uniacid);
    }else{
        $sql="select * from ".tablename('dg_article')." where ".$condition." pcate=:pcate and ccate=:ccate and uniacid=:uniacid and status=2 order by displayorder desc,id desc limit ".intval($pindex-1)*$psize.",".$psize;
        $parms=array(":pcate"=>$pcate,":ccate"=>$ccate,":uniacid"=>$uniacid);
    }
}elseif(!empty($_GPC['pccid'])){
    if(!empty($_GPC['ppcid'])){
        $sql="select * from ".tablename('dg_article')." where  uniacid=:uniacid and pcate=:pcate and ccate=:ccate and status=2 order by displayorder desc,id desc limit ".intval($pindex-1)*$psize.",".$psize;
        $parms=array(":uniacid"=>$uniacid,":pcate"=>$_GPC['pccid'],":ccate"=>$_GPC['ppcid']);
    }else{
        $sql="select * from ".tablename('dg_article')." where  uniacid=:uniacid and pcate=:pcate and status=2 order by displayorder desc,id desc limit ".intval($pindex-1)*$psize.",".$psize;
        $parms=array(":uniacid"=>$uniacid,":pcate"=>$_GPC['pccid']);
    }

}else{
    if(empty($pcate)&&empty($ccate)){
        $sql="select * from ".tablename('dg_article')." where  uniacid=:uniacid and status=2 order by displayorder desc,id desc limit ".intval($pindex-1)*$psize.",".$psize;
        $parms=array(":uniacid"=>$uniacid);
    }else{
        $sql="select * from ".tablename('dg_article')." where pcate=:pcate and ccate=:ccate and uniacid=:uniacid and status=2 order by displayorder desc,id desc limit ".intval($pindex-1)*$psize.",".$psize;
        $parms=array(":pcate"=>$pcate,":ccate"=>$ccate,":uniacid"=>$uniacid);
    }
}
/*
if(!empty($_GPC['submit'])){
    $sear=$_GPC['search'];
    $pcate=$_GPC['pcate'];
    $ccate=$_GPC['ccate'];
    $condition=" title like '%".$sear."%' and ";

    if(empty($pcate)&&empty($ccate)){
        $sql="select * from ".tablename('dg_article')." where ".$condition." uniacid=:uniacid and status=2 order by id desc limit ".intval($pindex-1)*$psize.",".$psize;
        $parms=array(":uniacid"=>$uniacid);
    }else{
        $sql="select * from ".tablename('dg_article')." where ".$condition." pcate=:pcate and ccate=:ccate and uniacid=:uniacid and status=2 order by id desc limit ".intval($pindex-1)*$psize.",".$psize;
        $parms=array(":pcate"=>$pcate,":ccate"=>$ccate,":uniacid"=>$uniacid);
    }
}*/
$list=pdo_fetchall($sql,$parms);
//var_dump($list);
//$count=pdo_fetchcolumn("select count(id) from ".tablename('dg_article')."where pcate=:pcate and ccate=:ccate and uniacid=:uniacid order by id desc",$parms);
foreach($list as &$item) {
    $ausql="select * from ".tablename('dg_article_author')." where uniacid=:uniacid and id=:id";
    $auparms=array(":uniacid"=>$uniacid,":id"=>$item['author_id']);
    $authorinfo=pdo_fetch($ausql,$auparms);
    $item['content']=htmlspecialchars_decode($item['content']);
    $item['thumb'] = tomedia($item['thumb']);
    $item['time']=date('Y-m-d',$item['createtime']);
    if(!empty($authorinfo['nickname'])){
        $item['aname']=$authorinfo['nickname'];
    }
    unset($item);
}
$data=array();
$data['list']=$list;
$data['page']=$pindex;
//$data['count']=$count;
header('Content-type: application/json');
echo json_encode($data);
//$pager = pagination($list, $pindex, $psize);