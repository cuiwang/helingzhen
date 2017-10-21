<?php

   require_once IA_ROOT . "/addons/".$this->modulename."/inc/download.php"; 
   
   global $_W, $_GPC;  
   $settings=$this->module['config'];  
   load()->func('tpl');
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $uniacid=$_W["uniacid"];
   $id=$_GPC['id']; 	
   if ($op=='display') { 		
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
	    $con="uniacid=$uniacid";
	     
			  

	    $nickname=$_GPC['nickname'];
      	
	    if (!empty($nickname)){
	      $con.=" and nickname like '%$nickname%'";
	    }
	    
	    $share_status=$_GPC['share_status'];
	  
	      if (!empty($share_status) || $share_status=='0'){
	      $con.=" and share_status=$share_status";
	    }
	    
	    
	    $zj_status=$_GPC['zj_status'];
	    if (!empty($zj_status) || $zj_status=='0'){
	      $con.=" and zj_status=$zj_status";
	    }
	    	      
	    $total=0; 
        $cgc_baoming_fans=new cgc_baoming_fans();
  
	  
        
        $list = $cgc_baoming_fans->getAll($con, $pindex,$psize,$total);  
       
		$pager = pagination($total, $pindex, $psize);		
		include $this->template('cgc_baoming_fans');
		exit();
  	}
  	
   	 if ($op=='post') {
  	     $id=$_GPC['id']; 
  	     $cgc_baoming_fans=new cgc_baoming_fans();
  	     if (!empty($id)){
            $item = $cgc_baoming_fans->getOne($id);  
  	     }	      	     
		if (checksubmit('submit')) {
			  $data=array("uniacid"=>$_W['uniacid'],
             	   "openid"=>$_GPC['openid'],        
             	   "nickname"=>$_GPC['nickname'],  
             	    "headimgurl"=>$_GPC['headimgurl'],  
             	   "city"=>$_GPC['city'], 
                   "province"=>$_GPC['province'],  
             	   "sex"=>$_GPC['sex'],  
             	             	                     	        
                   "createtime" =>TIMESTAMP,
             );
                    
			if (!empty($id)) {				
				$temp=$cgc_baoming_fans->modify($id,$data); 
			}
			else{
			   $temp=$cgc_baoming_fans->insert($data); 
			}						
			message('信息更新成功',$this->createWebUrl('cgc_baoming_fans'), 'success');
			}
	     
	      include $this->template('cgc_baoming_fans');
		  exit();
		} 
		
		
	
	 
	 if ($op=='delete') {
	 	$id=$_GPC['id'];
	 	$cgc_baoming_fans=new cgc_baoming_fans();
        $cgc_baoming_fans->delete($id); 
        message('删除成功！',referer(), 'success');
	 }
	 
    if ($op=='delete_all') {
    		$id=$_GPC['activity_id'];
           $cgc_baoming_fans=new cgc_baoming_fans();
           $cgc_baoming_fans->deleteAll($id);  
           message('删除成功！', $this->createWebUrl('cgc_baoming_fans'), 'success');
     }
     
     
   
