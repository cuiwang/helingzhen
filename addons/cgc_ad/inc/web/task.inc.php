<?php

   global $_W, $_GPC;  
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $weid=$uniacid=$_W["uniacid"];
   $advid=$_GPC['advid'];
   $quan_id=$_GPC['quan_id'];
   $cgc_ad_task = new cgc_ad_task();
   $config = $this ->settings;
   $cgc_ad_quan = new cgc_ad_quan();
   $quan=$cgc_ad_quan->getOne($quan_id);
   
   if ($op=='display') { 		
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
	    $con="weid=$uniacid AND advid=$advid";
	    if (!empty($_GPC['nickname'])) {
	    	$nickname = $_GPC['nickname'];
	    	$con .= " AND `nickname` like '%$nickname%' ";
	    }
	    
	    $total=0; 
        $list = $cgc_ad_task->getAll($con, $pindex,$psize,$total);
		$pager = pagination($total, $pindex, $psize);	
		 include $this->template('web/task');	
  	}else if ($op=='delete') {
			$id=$_GPC['id'];
			$cgc_ad_task->delete($advid,$id);
			message('删除成功！',referer(), 'success');
	 }else if ($op=='deleteAll') {
			$cgc_ad_task->deleteAll($advid);
			message('删除成功！',referer(), 'success');
     }else if ($op == 'check') {
     		$id=$_GPC['id'];
     		$mid=$_GPC['mid'];
     		$quan_id=$_GPC['quan_id'];
     		$advid=$_GPC['advid'];
     		
     		$data=array("status"=>1);
     		$cgc_ad_member = new cgc_ad_member();
     		$member =$cgc_ad_member->getOne($mid);
     		
     		$cgc_ad_adv = new cgc_ad_adv();
     		$adv =$cgc_ad_adv->getOne($advid);
     	    
     	    $cgc_ad_quan = new cgc_ad_quan($quan_id);
     		$quan =$cgc_ad_quan->getOne($quan_id);
     		
     		$task=$cgc_ad_task->getOne($id);
     
     		$ret=task_cal_red($member,$quan,$adv,$this->settings,$task);
     	    if (($ret['code'])!=1){
     	      message($ret['msg'],referer(), 'error');
     	    }
     	    
     	   if (!empty($config['task_template_id']) && ($member['message_notify'])){
   	  	        $_tdata = array(
	                       'first'=>array('value'=>'任务反馈信息！','color'=>'#173177'),
	                       'keyword1'=>array('value'=>"任务模式红包",'color'=>'#173177'),
	                       'keyword2'=>array('value'=>"你的任务已经审核成功,获得了".$ret['$get_money'],'color'=>'#173177'),	              
	                       'remark'=>array('value'=>'如果你不想接受此类消息，可以在个人中心选择关闭。','color'=>'#173177'),
	                       );
	          $_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_detail',array('quan_id'=>$quan['id'],'id'=>$advid)), 2);	    
              $a = sendTemplate_common($member['openid'],$config['task_template_id'],$_url,$_tdata);	                      
	       }
     	    
     		message('审核成功',referer(), 'success');
     }else if($op=='un_check'){   
   	  $id=$_GPC['id'];
      $mid=$_GPC['mid'];
      $quan_id=$_GPC['quan_id'];
      $cgc_ad_quan=new cgc_ad_quan();
      $quan =$cgc_ad_quan->getOne($quan_id);
      $advid=$_GPC['advid'];
   	  $cgc_ad_member = new cgc_ad_member();
      $member =$cgc_ad_member->getOne($mid);
      $cgc_ad_task=new cgc_ad_task();
      $task=$cgc_ad_task->getOne($id);	
      $ret=$cgc_ad_task->modify($task['id'],array("status"=>2));
      if ($ret){
        if (!empty($config['task_template_id']) && ($member['message_notify'])){
   	  	        $_tdata = array(
	                       'first'=>array('value'=>'任务反馈信息！','color'=>'#173177'),
	                       'keyword1'=>array('value'=>"任务模式红包",'color'=>'#173177'),
	                       'keyword2'=>array('value'=>"你的任务已被拒绝，如有疑问点击这里到任务页面添加好友联系发布者。",'color'=>'#173177'),	              
	                       'remark'=>array('value'=>'如果你不想接受此类消息，可以在个人中心选择关闭。','color'=>'#173177'),
	                       );
	          $_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_detail',array('quan_id'=>$quan['id'],'id'=>$advid)), 2);	    
              $a = sendTemplate_common($member['openid'],$config['task_template_id'],$_url,$_tdata);	                      
	       }
       	
        message('操作成功',referer(), 'success');
       } else {
      	message('操作失败',referer(), 'error');
       }
    
  }else if($op=='batch_check'){
  	
  	$ids = implode(',',$_GPC['id']);
  	
  	if (empty($ids)){
  		message('任务id不得为空',referer(), 'error');
  	}
  	$quan_id = $_GPC['quan_id'];
  	$advid = $_GPC['advid'];
  	
  	
  	$cgc_ad_task=new cgc_ad_task();
  	$tasks=$cgc_ad_task->getByConAll("weid=".$weid." AND quan_id=".$quan_id." AND advid=".$advid." AND id in (".$ids.") and status!=1","mid");
  	 
  	if (empty($tasks)){
  		message('任务表不得为空',referer(), 'error');
  	}
  	
  	$mids=implode(",",array_keys($tasks));
  	 
  	$members=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND   id in (".$mids.")");
  	$total=0;
  	$quan=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_quan')." where weid=$weid and id=$quan_id");
  	
  	foreach ($members as $member){		
  		$adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_adv')." where weid=$weid and id=$advid");
  		$ret=task_cal_red($member,$quan,$adv,$config,$tasks[$member['id']]);
  		if ($ret['code']=="1"){
  			$total+=1;
  			
  			if (!empty($config['task_template_id']) && ($member['message_notify'])){
  				$_tdata = array(
  						'first'=>array('value'=>'任务反馈信息！','color'=>'#173177'),
  						'keyword1'=>array('value'=>"任务模式红包",'color'=>'#173177'),
  						'keyword2'=>array('value'=>"你的任务已经审核成功,获得了".$ret['get_money'],'color'=>'#173177'),
  						'remark'=>array('value'=>'如果你不想接受此类消息，可以在个人中心选择关闭。','color'=>'#173177'),
  				);
  				$_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_detail',array('quan_id'=>$quan['id'],'id'=>$advid)), 2);
  				$a = sendTemplate_common($member['openid'],$config['task_template_id'],$_url,$_tdata);
  			}
  		}
  	}
  	
  	message("批量审核".$total."条",referer(), 'success');
  }else if($op == 'batch_uncheck'){
  	
  	$ids = implode(',',$_GPC['id']);
  	 
  	if (empty($ids)){
  		message('任务id不得为空',referer(), 'error');
  	}
  	$quan_id = $_GPC['quan_id'];
  	$advid = $_GPC['advid'];
  	 
  	 
  	$cgc_ad_task=new cgc_ad_task();
  	$tasks=$cgc_ad_task->getByConAll("weid=".$weid." AND quan_id=".$quan_id." AND advid=".$advid." AND id in (".$ids.") and status=0","mid");
  	
  	if (empty($tasks)){
  		message('任务表不得为空',referer(), 'error');
  	}
  	 
  	$mids=implode(",",array_keys($tasks));
  	
  	$members=pdo_fetchall("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND   id in (".$mids.")");
  	$total=0;
  	$adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_adv')." where weid=$weid and id=$advid");
  	$quan=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_quan')." where weid=$weid and id=$quan_id");
  	foreach ($members as $member){
  		
  		$ret=$cgc_ad_task->modify($tasks[$member['id']]['id'],array("status"=>2));
  		if ($ret){
  			$total+=1;
  			if (!empty($config['task_template_id']) && ($member['message_notify'])){
  				$_tdata = array(
  						'first'=>array('value'=>'任务反馈信息！','color'=>'#173177'),
  						'keyword1'=>array('value'=>"任务模式红包",'color'=>'#173177'),
  						'keyword2'=>array('value'=>"你的任务已经被拒绝。",'color'=>'#173177'),
  						'remark'=>array('value'=>'如果你不想接受此类消息，可以在个人中心选择关闭。','color'=>'#173177'),
  				);
  				$_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_detail',array('quan_id'=>$quan['id'],'id'=>$advid)), 2);
  				$a = sendTemplate_common($member['openid'],$config['task_template_id'],$_url,$_tdata);
  			}
  		
  		}
  	}
  	 
  	message("批量拒绝".$total."条",referer(), 'success');
  }
     
	 
