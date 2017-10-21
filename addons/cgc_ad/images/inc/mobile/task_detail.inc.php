<?php
	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']);    
    $member=$this->get_member();
    $from_user=$member['openid'];
    $subscribe=$member['follow'];
    $quan=$this->get_quan();
    $adv=$this->get_adv();
    $config = $this ->settings;      
    $rob_next_time=$member['rob_next_time'];   
    $mid=$member['id'];
    $op=empty($_GPC['op'])?"display":$_GPC['op'];
    $config = $this ->settings;
	$quan['city']=str_replace("|", "或", $quan['city']);
  	
	if($op=='display'){
	  $adv['views']=$this->get_view($member,$adv);
	  $cgc_ad_task=new cgc_ad_task(); 
	  
	  //20160604 言辞是非 清除超时任务
	  if (!empty($adv['job_submission_time']) && $quan['task_submit_switch']){
	    $clear_task=pdo_query("DELETE FROM ".tablename('cgc_ad_task')." WHERE weid=".$weid." AND quan_id=".$adv['quan_id']." AND advid=".$id."  AND task_status=0  and status=0 AND create_time<".(time()-60*$adv['job_submission_time']));  //一小时
	  }
	  
	  $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_task')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$mid);
	  
	  $check_auth=false;
	 
	  if ($member['openid']==$adv['openid'] || $member['is_kf']){
	    $check_auth=true;
	   }

	 $pagesize=50;
	
      $red=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_task')." as a ".
     
      " WHERE a.weid=".$weid." AND a.quan_id=".$adv['quan_id']." AND a.advid=".$id." and status<2 ORDER BY a.create_time DESC limit 0,$pagesize",array(),"mid");

      $_red = pdo_fetchcolumn('SELECT count(id) FROM '.tablename('cgc_ad_task')." WHERE weid=".$weid." AND quan_id=".$adv['quan_id']." AND advid=".$id." and status<2");
   

     $_msglist = pdo_fetchall("SELECT a.*,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_message')." a
      left join ".tablename('cgc_ad_member')." b on a.mid=b.id
	  WHERE a.weid=".$weid." AND a.advid=".$id." and a.status=1 order by upbdate desc limit 0,5");
  
      $_msgtotal = pdo_fetchcolumn('SELECT count(id) FROM '.tablename('cgc_ad_message')." WHERE weid=".$weid." AND status=1  and advid=".$_GPC['id']);
      include $this->template('task_detail');
      exit();
   }
 
   if($op=='check'){   
   	  if ($adv['openid']!=$member['openid'] && empty($member['is_kf'])){
   	     $this->returnError("没权限");
   	  }		
   	
   	  $_GPC['task_id']=rtrim($_GPC['task_id'],",");
   	  if (empty($_GPC['task_id'])){
   	    $this->returnError("任务id不得为空");
   	  }
   	  
   	  $cgc_ad_task=new cgc_ad_task(); 
	  $tasks=$cgc_ad_task->getByConAll("weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND id in (".$_GPC['task_id'].") and status=0","mid");
	  if (empty($tasks)){
	   $this->returnError("任务表不得为空");
	  }
	  
	  if (!empty($task['status'])){
   	    $this->returnError("已经操作过了");
   	  }
   	  
	  
	  $mids=implode(",",array_keys($tasks));
	  
	  $members=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND   id in (".$mids.")");
	  $total=0;	
   	  foreach ($members as $member){	
   	  	$adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_adv')." where weid=$weid and id=$id");
        $ret=task_cal_red($member,$quan,$adv,$config,$tasks[$member['id']]);
        if ($ret['code']=="1"){
          $total+=1;
        }
      }	
      $this->returnSuccess("审核成功".$total."条。");
    
  }	
  
  
     if($op=='un_check'){   
   	  if ($adv['openid']!=$member['openid'] && empty($member['is_kf'])){
   	     $this->returnError("没权限");
   	  }		
   	
   	  if (empty($_GPC['task_id'])){
   	    $this->returnError("任务id不得为空");
   	  }
   	  
      $cgc_ad_task=new cgc_ad_task();
  
   	  $_GPC['task_id']=rtrim($_GPC['task_id'],",");
	  $tasks=$cgc_ad_task->getByConAll("weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND id in (".$_GPC['task_id'].") and status=0","mid"); 	
      if (empty($tasks)){
   	    $this->returnError("任务表为空");
   	  }
   	  
 
	  $total=0;	
   	  foreach ($tasks as $task){
   	    $ret=$cgc_ad_task->modify($task['id'],array("status"=>2));  	    
        if ($ret){
          $total=$total+1;
       	  $notify_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member').
            " WHERE   weid=".$weid." AND quan_id=".$quan_id." and id='{$task['mid']}'");          
       	  if (!empty($config['task_template_id'])){      	  
   	  	    $_tdata = array(
	                       'first'=>array('value'=>'任务反馈信息！','color'=>'#173177'),
	                       'keyword1'=>array('value'=>"任务模式红包",'color'=>'#173177'),
	                       'keyword2'=>array('value'=>"你的任务已经被拒绝。",'color'=>'#173177'),	              
	                       'remark'=>array('value'=>'如果你不想接受此类消息，可以在个人中心选择关闭。','color'=>'#173177'),
	                       );
	          $_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_detail',array('quan_id'=>$quan['id'],'id'=>$id)), 2);	    
              $a = sendTemplate_common($notify_member['openid'],$config['task_template_id'],$_url,$_tdata);	                      
	       }
       }
     }
     $this->returnSuccess("拒绝成功".$total."条。",$ret['data']);
  }	
  
  

  if($_GPC['op']=='get_morered'){
  	$pagesize=50;
    $__pages = intval($_GPC['page'])*$pagesize;
    $red=pdo_fetchall("SELECT a.* FROM ".tablename('cgc_ad_task')." as a 
		  WHERE a.weid=".$weid." AND a.quan_id=".$adv['quan_id']." AND a.advid=".$id." and status<2 ORDER BY a.create_time DESC limit ".$__pages.",$pagesize");
    $ht='';
    
    		
    $check_auth=false;
	 
	if ($member['openid']==$adv['openid'] || $member['is_kf']){
	  $check_auth=true;
	}
    
    foreach ($red as $key => $r) {   	
    	  if ($check_auth){
    	    if ($r['status']==1){
    	      $checkbox='<input  class="weui_cell_sd" type="checkbox" name="checkboxed" disabled="true"/>';
    	   
    	    } else {
    	     $checkbox='<input class="weui_cell_sd" type="checkbox" name="checkbox[]"  value="'.$r['id'].'"/>';
   
    	    }
    	  } 
    	  
    	  if ($r['status']==1){
			 $bottom='<div class="weui_cell_ft">'.$r['money'].$config['unit_text'].'</div>';
    	  } else {
    	     if ($check_auth){
    	       $checkinfo="审核";
    	       if ($quan['task_submit_switch']){
    	         if ($r['task_status']){
    	     	   $checkinfo="查看";
    	     	 } else {
    	     	    $checkinfo="任务中";
    	     	 }
    	       }								   
    	       $bottom=' <div class="weui_cell_ft"><a href="javascript:showmodal('.$r['id'].','.$r['task_status'].');">'.$checkinfo.'</a></div>';
    	     } else {
    	      $bottom=' <div class="weui_cell_ft"><a href="#">待审核</a></div>';
    	     }
    	  }
							    	        	    
          $ht.= '<div class="weui_cell" style="width:87%">'.
          $checkbox.
           ' <div class="weui_cell_hd"><img src="'.$r['headimgurl'].'" style="width:20px;margin-right:5px;display:block"></div>'.
            '<div class="weui_cell_bd weui_cell_primary">'.
            '<p>'.$r['nickname'].'</p>'.
           '</div>'.$bottom;
               					
          $ht.="</div>";
        }
	 if(!empty($ht)){
       exit(json_encode(array('status'=>1,'log'=>$ht)));
     }else{
       exit(json_encode(array('status'=>0)));
     }
   }
   
   
   
   
   //领取任务
   if($_GPC['op']=='task'){ 
   	  // 判断是否已经开枪
    if($adv['rob_start_time']>time()){
  	  $this->returnError('请稍等，还没到开抢时间哦~');
     }
 
   	
   	 if (!empty($quan['city']) && empty($member['location_info'])){
   	  $this->returnError("你不在".$quan['city']);
   	 } 
   	
   	 $total=pdo_fetchcolumn("select count(1) total from " . tablename('cgc_ad_task') . " where weid=:uniacid and quan_id=:quan_id and advid=:advid and status<2", array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' => $id));  	  	
   	 if ($total>=$adv['total_num']){
   	  $this->returnError("满了,请等待");
   	 }
   	
     $task=pdo_fetch("select * from " . tablename('cgc_ad_task') . " where weid=:uniacid and quan_id=:quan_id and advid=:advid and mid=:mid", array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' => $id,':mid' =>$mid));  	
  
   	 if (!empty($task)){
   	   $this->returnError("已经领取");
   	 }
   	 
   	 $cgc_ad_task=new cgc_ad_task(); 
   	 $entity=array(
						'weid'=>$_W['uniacid'],
						'quan_id'=>$quan_id,
						'advid'=>$id,
						'mid'=>$member['id'],	
						'openid'=>$member['openid'],				
						'nickname'=>$member['nickname'],
						'headimgurl'=>$member['headimgurl'],
						'create_time'=>time()
					);
					
   	  $ret=$cgc_ad_task->insert($entity);
   	  
   	  if (empty($ret)){
   	    $this->returnError("插入失败");
   	  } else {
   	    if (!empty($config['task_template_id'])){
   	    	$adv_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member').
            " WHERE   weid=".$weid." AND quan_id=".$quan_id." and openid='{$adv['openid']}'");
   	    	if (($adv_member['message_notify'])){
   	  	      $_tdata = array(
	                       'first'=>array('value'=>'任务反馈信息！','color'=>'#173177'),
	                       'keyword1'=>array('value'=>"任务模式红包",'color'=>'#173177'),
	                       'keyword2'=>array('value'=>"有人接了任务了，快去审核吧，不然在12个小时之内自动审核",'color'=>'#173177'),	              
	                       'remark'=>array('value'=>'如果你不想接受此类消息，可以在个人中心选择关闭。','color'=>'#173177'),
	                       );
	          $_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_detail',array('quan_id'=>$quan['id'],'id'=>$id)), 2);	    
              $a = sendTemplate_common($adv['openid'],$config['task_template_id'],$_url,$_tdata);	                      
	       } 
	       
	       if ($member['message_notify']){
   	  	      $_tdata = array(
	                       'first'=>array('value'=>'任务反馈信息！','color'=>'#173177'),
	                       'keyword1'=>array('value'=>"任务模式红包",'color'=>'#173177'),
	                       'keyword2'=>array('value'=>"请尽快完成任务后，提醒广告主审核",'color'=>'#173177'),	              
	                       'remark'=>array('value'=>'如果你不想接受此类消息，可以在个人中心选择关闭。','color'=>'#173177'),
	                       );
	          $_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_detail',array('quan_id'=>$quan['id'],'id'=>$id)), 2);	    
              $a = sendTemplate_common($member['openid'],$config['task_template_id'],$_url,$_tdata);	                      
	       } 
	       
   	    }
   	  	
   	     $this->returnSuccess("成功");
   	  }
   	}
  

	