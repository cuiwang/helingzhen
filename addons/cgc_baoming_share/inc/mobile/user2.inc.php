<?php
require_once IA_ROOT . "/addons/".$this->modulename."/inc/common.php"; 
global $_W, $_GPC;

$op=!empty($_GPC['op'])?$_GPC['op']:"display";

$settings=$this->module['config'];
$uniacid=$_W['uniacid'];
$modulename=$this->modulename;
$id=$_GPC['id']; 

$userinfo=getFromUser($settings,$modulename);
 
$userinfo=json_decode($userinfo,true);
$from_user=$userinfo['openid'];
if ($op=="display"){ 
  $pindex = max(1, intval($_GPC['page']));	
  $psize= 50; 
  $con="uniacid=$uniacid";

  $cgc_baoming_activity=new cgc_baoming_activity();
  if (!empty($id)){
    $activity = $cgc_baoming_activity->getOne($id);  
  }
  
  
   $activity['share_title']=str_replace("#nickname#",$userinfo['nickname'],$activity['share_title']);
  
   $activity['share_desc']=str_replace("#nickname#",$userinfo['nickname'],$activity['share_desc']);
     
    $activity['share_url']=get_random_domain($activity['share_url']);
  
  if (!empty($settings['xl'])){
    include $this->template('to_user');
    exit();
  }
  
  $cgc_baoming_user=new cgc_baoming_user();
  if  (empty($activity['yq_mode'])){
    $user=$cgc_baoming_user->selectByUser($from_user,$id);
  } else {
    //多抽奖码的
  	$user=$cgc_baoming_user->selectByConcatUser($from_user,$id);
   }
   
   $con.=" and  cj_code!=''";
   
   if (!empty($id)){
     $con.=" and  activity_id=$id";
	}

   $total=0; 
   $psize=50;
   $pindex=empty($_GPC['page'])?1:$_GPC['page'];
   $cgc_baoming_user=new cgc_baoming_user();
   $list = $cgc_baoming_user->getAll($con, $pindex,$psize,$total); 

   if ($_W['isajax']){
     exit(json_encode(array("code"=>"1","data"=>$list,"count"=>count($list))));
   }
   $pager = pagination($total, $pindex, $psize);	
   

   	
  include $this->template('user2');
	
   exit();	
} 








        
     