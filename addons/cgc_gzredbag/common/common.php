<?php
/**
 * 关注送红包模块处理程序
 * 鬼 狐 源 码 社 区 www.guifox.com
 */
   // 修改用户状态
   function update_gz_user($money,$weid,$openid){
  	 load()->func('logging');

  	 $sql="select count(1) from ".tablename("gzredbag_user")."  where uniacid=".$weid." and openid='{$openid}'";
     $exist=pdo_fetchcolumn($sql);
     if (empty($exist)){
         $sql="INSERT INTO ".tablename("gzredbag_user").
          " (uniacid,openid, money,status,send_status,createtime)" .
          " VALUES ('{$weid}', '{$openid}',{$money},1,1,".TIMESTAMP.")";
          $temp=pdo_query($sql);        
       if ($temp==false) {          
        logging_run("update_user:".$temp."==".$sql); 
       } 
       return $temp;
    } 
   }

  //是否关注
  function sfgz_user($fromuser){
  	global $_W;
  	$uniacid=$_W['uniacid'];
   	$follow=pdo_fetchcolumn("SELECT follow FROM " . tablename('mc_mapping_fans').
          " where uniacid=$uniacid and openid='$fromuser'"); 
   	return $follow;
  }
  
    //总次数改变
  function update_data($weid){
  	load()->func('logging');

    
    $totaldata=pdo_fetchcolumn("select count(1) from ".tablename("gzredbag_count")."  where uniacid=".$weid);  	
    if (empty($totaldata)){
      $temp=pdo_insert("gzredbag_count",
             array("uniacid"=>$weid,
                   "total_count"=>1, 
                   "createtime"=>TIMESTAMP
                  ));
          
    } else {
      $temp=pdo_query("update ".tablename("gzredbag_count").
           " set total_count=total_count+1,createtime=".TIMESTAMP." where uniacid=".$weid);
    
    } 
     
     return $temp;
   }
  
  
  	//是否可以领取红包
	 function web_sflq($settings,$openid){
	  global $_W;
      $ret=array();
	  $ret['code']=0;
      $ret['message']="success";
	  $curtime=date("Y-m-d H:i",time());
	  $starttime=date("Y-m-d H:i",$settings['starttime']);
	  $endtime=date("Y-m-d H:i",$settings['endtime']);

 
      $addr=$settings['addr'];

	  //时间判断
	  if (!empty($starttime) && !empty($endtime)){
	  	if ($starttime>$curtime){
	  	   $ret['code']=-1;
          $ret['message']="活动还没开始";
          return $ret;
	  	}
	  	
	  	if ($curtime>=$endtime){
	  	 $ret['code']=-2;
         $ret['message']="活动已经结束";
         return $ret;
	  	}
	  }
	  
	  
	     if (!empty($settings['start_hour'])   && !empty($settings['end_hour'])){
         	 $Hour = date('G');
             if ($settings['start_hour']>$Hour || $settings['end_hour']<=$Hour){
               return array("code"=>"-2","msg"=>"活动时间为:".$settings['start_hour']."点到".$settings['end_hour']."点"); 
   	         }
         }
	  
	  //是否超过总次数
	   $total_count=pdo_fetchcolumn("select total_count from ".tablename('gzredbag_count').
      " where uniacid={$_W['uniacid']} ");
	   if ($total_count>=$settings['total_count']){
	  	 $ret['code']=-33;
         $ret['message']="超过设定总次数";
         return $ret;
	  	}
	  
	
	  //是否超过红包总金额
	  $total_money=pdo_fetchcolumn("select total_money from ".tablename('gzredbag_money')." where uniacid={$_W['uniacid']}");
	  if ($total_money>=round($settings['total_money']+$settings['amount'],2)){
	  	   $ret['code']=-3;
         $ret['message']="红包已经发放完毕";
         return $ret;
	  	}
	 
	  //是否领取过
	   $status=pdo_fetchcolumn("select status from ".tablename('gzredbag_user')." where uniacid={$_W['uniacid']} and openid='$openid'");
	   if (!empty($status)){
	  	   $ret['code']=-4;
         $ret['message']="你已经领取过红包";
         return $ret;
	  	}


     //地址 
     if (!empty($settings['addr'])){ 
         $message['time']=strtotime("-7 day",time());
         $message['uniacid']=$_W['uniacid'];
         $message['from_user']=$openid;

         $location=getLocation($message);
           if  (empty($location)){
           $ret['code']=-7;
           $ret['message']=empty($settings['addr_error'])?"打开地理定位":$settings['addr_error'];
           return $ret;
         }
         $location['addr']= $settings['addr'];
         $result=getAddr($location);
         if  ($result==false){
           $ret['code']=-8;
           $ret['message']=empty($settings['addr_error'])?"地区不符合":$settings['addr_error'];
           return $ret;
         }
    
      }  

      return $ret;
   }
  
  
//返回百度地址
//根据经纬度返回百度地址：http://api.map.baidu.com/geocoder/v2/?ak=qen1OGw9ddzoDQrTX35gote2&location=26.047125,119.330221&output=json
function getAddr($location){
	if (empty($location)){
       return false;
	}
	if (empty($location['x']) && empty($location['location_x'])){
       return false;
	}
    $loc="";
	if (!empty($location['location_x']) && !empty($location['location_y'])){
		$loc=$location['location_x'].",".$location['location_y'];
	}

	if (!empty($location['x']) && !empty($location['y'])){
       $loc=$location['x'].",".$location['y'];
    }

    if (empty($loc)){
      return false;
    }

    $url="http://api.map.baidu.com/geocoder/v2/?ak=qen1OGw9ddzoDQrTX35gote2&location=".$loc."&output=json";
 
    $ret=json_decode(file_get_contents($url),true);
    logging_run($ret); 
    if ($ret['status']!=0) {
      logging_run("getAddr:error1"); 
      logging_run("url:".$url); 
      return false;
    }
  
    if (strpos($ret['result']['formatted_address'],$location['addr'])===false){
      logging_run("getAddr:error2"); 
      logging_run("location['addr']:".$location['addr']); 
      logging_run($ret['result']); 
      return false;
    } else {
      return true;
    }
	
}

//www.guifox.com
function getLocation($message){
     $sql='select * from '.tablename('stat_msg_history').
      	" where uniacid='".$message['uniacid']."'  and from_user='".$message['from_user']."'"
       ." and createtime>".$message['time']." and type in ('location','trace') order by createtime desc";

      $stat_msg_history=pdo_fetch($sql);
	
      if (!empty($stat_msg_history) && !empty($stat_msg_history['message'])){
        $ret=iunserializer($stat_msg_history['message']);
        return $ret;
      } else {
         logging_run("getLocation:error"); 
        logging_run($sql); 
      }

      return "";

}