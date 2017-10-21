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


  $openid = empty($_W['openid']) ? $_GPC['fromuser'] : $_W['openid'];
  ///  $follow = sfgz_user($openid);
    
/*  $url=murl('entry', array('m' => $this->module['name'], 'do' => 'enter', 'id' => $id,'form'=>'result'),true,true);
  if (!empty($follow)){
    header("location:".$url);
    exit();
   }*/
  
  $activity['succ_url']=get_random_domain($activity['succ_url']);
  
  
  
  if (!empty($activity['succ_url'])){
    header("location:".$activity['succ_url']);
    exit();
  }
  
  $activity['share_url'] = get_share_url($activity, $activity['share_url'], $from_user, $settings);
   
   include $this->template('succ');
   exit();	
} 





        
     