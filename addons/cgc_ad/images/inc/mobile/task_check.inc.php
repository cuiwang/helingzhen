<?php

       //任务审核页面
	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']);    
    $member=$this->get_member();
    $from_user=$member['openid'];
    $quan=$this->get_quan();
    $adv=$this->get_adv();
    $mid=$member['id'];
    $op=empty($_GPC['op'])?"display":$_GPC['op'];
    $config = $this ->settings;
    
    if ($adv['openid']!=$member['openid'] && empty($member['is_kf'])){
   	   $this->returnError("没权限");
   	 }	
   	 
    $cgc_ad_task = new cgc_ad_task();
    $task_id = intval($_GPC['task_id']);
    $task = $cgc_ad_task->getOne($task_id);
    
 
    $url=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('task_detail', array('quan_id' => $quan_id,'id'=>$id)), 2);
    
 
    if(empty($task)){
    	$this->returnError('你要审核的任务，已经不见了',$url);
    }
    if($task['status']){
    	$this->returnError('你要审核的任务已经审核',$url);
    }
    if(!$task['task_status']){
    	$this->returnError('你要审核的任务还未提交',$url);
    } 
    if($op=='display'){
      $task_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member').
      " WHERE   weid=".$weid." AND quan_id=".$quan_id." and id='{$task['mid']}'");
      include $this->template('task_check');
      exit();
    }else if ($op == 'msg'){   	
        $task_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member').
        " WHERE   weid=".$weid." AND quan_id=".$quan_id." and id='{$task['mid']}'");
    	$msg = $_GPC['content'];
    	if (empty($msg)){
    		$this->returnError('发送的消息不能为空');
    	}
    	
    	$msg="任务发布人".$adv['nickname']."给您的回复:".$msg;

         if($config['is_type']==1){
	  	   $_tdata = array(
    				'first'=>array('value'=>'任务不合格通知！','color'=>'#173177'),
    				'keyword1'=>array('value'=>'任务模式红包','color'=>'#173177'),
    				'keyword2'=>array('value'=>$msg,'color'=>'#173177'),
    				'remark'=>array('value'=>'请尽快修改任务后，提醒广告主审核。','color'=>'#173177'),
    		);
    		$_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_detail',array('quan_id'=>$quan['id'],'id'=>$id)), 2);
    		$a = sendTemplate_common($task_member['openid'],$config['task_template_id'],$_url,$_tdata);
         } else {
            $a = post_send_text($task_member['openid'],$msg);
         }
            
    
    	if (is_error($a)){
    		$this->returnError("发送消息失败，请重试");
    	}else{
    		$this->returnSuccess("发送消息成功");
    	}
    } else if ($op == 'send_taskmsg'){ 
      include $this->template('send_taskmsg');
      exit();  	
    }
    

					



	
	
	
		
		
		
		
		
		
						



							



						


						



			
						

							