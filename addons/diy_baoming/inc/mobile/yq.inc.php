<?php
require_once IA_ROOT . "/addons/".$this->modulename."/inc/common.php"; 
global $_W, $_GPC;
$uniacid=$_W['uniacid'];
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




if (!empty($_GPC['pid'])){
  setcookie("diy_baoming_parent_".$uniacid."_".$id,$_GPC['pid'], time()+3600*(24*5)); 
}
$share_url=get_random_domain($activity['share_url']);

header("location:".$share_url);        


	    
      
 



        
     