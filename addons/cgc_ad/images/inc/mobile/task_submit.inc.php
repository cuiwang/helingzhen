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
		 // 处理图片
     	$images=$_GPC['images'];
																		
		// 从微信服务器下载用户上传的图片
		if(!empty($images) && count($images)>0){
      		load()->func('file');
      		$down_images=array();
      	
      		// 从微信服务器下载图片
      		$WeiXinAccountService = WeiXinAccount::create($_W['acid']);
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
	       		if($config['is_qniu']==1){
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
       		$this->returnSuccess('提交任务成功');
    	}else{
       		$this->returnError('提交失败，请重试');
    	}
  }

					



	
	
	
		
		
		
		
		
		
						



							



						


						



			
						

							