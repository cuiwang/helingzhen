<?php
require_once IA_ROOT . "/addons/cgc_gzredbag/common/common.php"; 
global $_W, $_GPC;

$op=!empty($_GPC['op'])?$_GPC['op']:"display";
$from_user=$_W['fans']['from_user'];
$settings=$this->module['config'];
$uniacid=$_W['uniacid'];

$ret=web_sflq($settings,$from_user);

if ($ret['code']!=0){
	message($ret['message']);
}



  $ret= sfgz_user($from_user);
  $gz_url=$settings['gz_url'];
  if (empty($ret)){
  	header("location:$gz_url");
  	exit();
  }
  
        $amount= mt_rand(($settings["min_money"])*100,($settings["max_money"])*100);
     
  
  
  /*   if (empty($settings['sendtype'])){
           $ret=send_qyfk($settings,$from_user,$amount,"红包来了");
         } else {
           //现金红包
           $ret=send_xjhb($settings,$from_user,$amount,"红包来了");
         }*/
        
      $ret['code']=0;
        if ($ret['code']!=0){
           message(json_encode($ret));
        } 
  
 $amount=$amount/100;
  
      $temp=update_gz_user($amount,$_W['uniacid'],$from_user);
      
      if (empty($temp)){
      	message("update_user 失败");
      }
     
      $temp=update_data($uniacid);
      
       if (empty($temp)){
      	message("update_data 失败");
      }
     
      
      $end_url=$settings['end_url'];
      
      header("location:$end_url");
  	exit();
	