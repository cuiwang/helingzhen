<?php
require_once IA_ROOT . "/addons/".$this->modulename."/inc/common.php"; 
global $_W, $_GPC;
$op=!empty($_GPC['op'])?$_GPC['op']:"display";
$settings=$this->module['config'];
$uniacid=$_W['uniacid'];
$modulename=$this->modulename;
$id=$_GPC['id']; 

if ($op=="test"){ 
  $cgc_baoming_activity=new cgc_baoming_activity();
  if (!empty($id)){
    $activity = $cgc_baoming_activity->getOne($id);  
  }
  $activity['logo']=empty($activity['logo'])?STYLE_PATH."/images/test.jpg":tomedia($activity['logo']);
  include $this->template('succ');
  exit();
}




if ($op=="display"){ 
  $cgc_baoming_activity=new cgc_baoming_activity();
  if (!empty($id)){
    $activity = $cgc_baoming_activity->getOne($id);  
  } else {
    message("没有这个活动");
  }

 // $this->forward($id,$from_user);
  
  $activity['succ_url']=get_random_domain($activity['succ_url']);
  
  if (!empty($activity['succ_url'])){
    header("location:".$activity['succ_url']);
    exit();
  }
   
   include $this->template('succ');
   exit();	
} 





        
     