<?php

       //提交任务页面
	global $_W,$_GPC;
    $weid=$_W['uniacid'];
    $quan_id=intval($_GPC['quan_id']);
    $id=intval($_GPC['id']);    
    $member=$this->get_member();
    $from_user=$member['openid'];
    $quan=$this->get_quan();
    $adv=$this->get_adv();
    $config=$this->settings;
    $mid=$member['id'];
    $op=empty($_GPC['op'])?"display":$_GPC['op'];
    $config = $this ->settings;
    $cgc_ad_task = new cgc_ad_task();
    $task_id = intval($_GPC['task_id']);
    $task = $cgc_ad_task->getOne($task_id);
    if(empty($task)){
    	$this->returnError('你要提交的任务，已经不见了');
    }
    if($task['status']){
    	$this->returnError('你要提交的任务已经审核');
    }
   
    if($op=='display'){
      $cgc_ad_task = new cgc_ad_task();
      $task_id = intval($_GPC['task_id']);
      $task = $cgc_ad_task->getOne($task_id);
      include $this->template('task_submit');
      exit();
    }else if ($op=='add'){    	
      $content = $_GPC['content'];       
      //可以不提交内容      
      if($this->text_len($content)>5000){
        $this->returnError('内容不能超过5000字哦~');
      }		
		 
	  //处理图片
      $images=$_GPC['images'];
     	
      if(empty($images)){
        $this->returnError('请提交图片');
       }
																		
		// 从微信服务器下载用户上传的图片
		if(!empty($images) && count($images)>0){
      		load()->func('file');
      		$down_images=array();
     	
      		// 从微信服务器下载图片
      		$WeiXinAccountService = WeiXinAccount::create($_W['oauth_account']);
      		foreach($images as $imgid){
      			//  $this->returnError($imgid.'上传图片失败:'); 
      		  if (preg_match("/^(http):/", $imgid)){
      		    $down_images[]=$imgid;
      		   } else {
        		$ret=$WeiXinAccountService->downloadMedia(array(
                             'media_id'=>$imgid,
                             'type'=>'image'
                            ));
	       		
	       		if(is_error($ret)){
		          $this->returnError($imgid.'图片上传失败:'.$ret['message']);
		        }
		        
	       		if($config['is_qniu']==1  &&  empty($_W['setting']['remote']['type'])){
	         		$ret=$this->VP_IMAGE_SAVE($ret);
	         		if(!empty($ret['error'])){
			           $this->returnError('上传图片失败:'.$ret['error']); 
			         }
	         		$down_images[]=$ret['image'];
		       	}else{
		         	$down_images[]=$ret;
		       	}
     		 }
      		}
     		$images = iserializer($down_images);
    	}
    
	    $data=array(
	      'content'=>$content,
	      'images'=>$images,
	      'task_status'=>1
		 );
    
     	$count = $cgc_ad_task->modify($task_id,$data);
     	if($count>0){
     	 
     	  if (!empty($config['task_template_id']) && !empty($quan['task_submit_switch'])){
     	  	$adv_member=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member').
            " WHERE   weid=".$weid." AND quan_id=".$quan_id." and openid='{$adv['openid']}'");
     	    if  ($adv_member['message_notify']) {   	  
   	  	       $_tdata = array(
	                       'first'=>array('value'=>'任务反馈信息！','color'=>'#173177'),
	                       'keyword1'=>array('value'=>"任务模式红包",'color'=>'#173177'),
	                       'keyword2'=>array('value'=>$member['nickname']."提交了任务了，快去审核吧，不然在12个小时之内自动审核",'color'=>'#173177'),	              
	                       'remark'=>array('value'=>'如果你不想接受此类消息，可以在个人中心选择关闭。','color'=>'#173177'),
	                       );
	           $_url= $_W['siteroot']."app/".substr($this->createMobileUrl('task_check',array('quan_id'=>$quan['id'],'id'=>$id,'task_id'=>$task_id)), 2);	    
               $a = sendTemplate_common($adv['openid'],$config['task_template_id'],$_url,$_tdata);	                      
	         } 
     	    }
       		$this->returnSuccess('提交任务成功');
    	}else{
       		$this->returnError('提交失败，请重试');
    	}
  }

					



	
	
	
		
		
		
		
		
		
						



							



						


						



			
						

							