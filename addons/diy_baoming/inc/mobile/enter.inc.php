<?php
require_once IA_ROOT . "/addons/".$this->modulename."/inc/common.php"; 
global $_W, $_GPC;

$op=!empty($_GPC['op'])?$_GPC['op']:"display";
$id=$_GPC['id'];
$cgc_baoming_activity=new cgc_baoming_activity();
  
if (!empty($id)){
  $activity = $cgc_baoming_activity->getOne($id);  
 } else {
  message("活动id不得为空");
}
  
if (empty($activity)){
  message("不存在此活动".$id);
}


$settings=$this->module['config'];

$uniacid=$_W['uniacid'];
$modulename=$this->modulename;
$openid=empty($_W['openid'])?$_GPC['fromuser']:$_W['openid'];
$user_json=getFromUser($settings,$modulename);

$userinfo=json_decode($user_json,true);

$pid=getparent($id);
     
$url=get_random_domain($settings['zdy_domain']);

$form=empty($_GPC['form'])?"login":$_GPC['form'];

if (empty($user_json)){
	exit("user_json error");
}

if (!empty($url)){
  $url =$url . '/app/' . murl('entry', array('m' => $this->module['name'],
  'do' =>$form, 'id' => $id,'sign'=>time(),'ticket'=>$userinfo['openid'],'user_json'=>$user_json,'pid'=>$pid,'fromuser'=>$openid));
} else {
   if($activity['activity_type']=='2'){
   	 $url=$this->createMobileUrl('pay',array('id' =>$id,'sign'=>time(),'ticket'=>$userinfo['openid'],'fromuser'=>$openid));
   }else if($activity['activity_type']=='1'){
   	 $url=$this->createMobileUrl('coupon',array('id' =>$id,'sign'=>time(),'ticket'=>$userinfo['openid'],'fromuser'=>$openid));
   }  else{
   	 $url=$this->createMobileUrl($form,array('id' =>$id,'sign'=>time(),'ticket'=>$userinfo['openid'],'fromuser'=>$openid));
   }
  
}
 header("location:".$url);
	    
      
 



        
     