<?php

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
	     	     	 	      
	    $total=0; 
        $cgc_baoming_code=new cgc_baoming_code();
        
        $activity = $cgc_baoming_code->get_activity($con);
        
        $list = $cgc_baoming_code->getAll($con, $pindex,$psize,$total);  
      
		$pager = pagination($total, $pindex, $psize);		
		include $this->template('cgc_baoming_code');
		exit();
  	}
  	
   	 if ($op=='post') {	 
  	     $id=$_GPC['id']; 
  	     $cgc_baoming_code=new cgc_baoming_code();
  	     if (!empty($id)){
            $item = $cgc_baoming_code->getOne($id);  
  	     }
  	     
  	     $con="uniacid=$uniacid";
  	     $activity = $cgc_baoming_code->get_activity($con);
  	      	      
		if (checksubmit('submit')) {
			  $data=array(       
             "code_id"=>$_GPC['code_id'],      	                	                     
             );
                    
			if (!empty($id)) {				
				$temp=$cgc_baoming_code->modify($id,$data); 
			}
									
			message('信息更新成功',$this->createWebUrl('cgc_baoming_code', array('op' => 'display')), 'success');
			}
	     
	      include $this->template('cgc_baoming_code');
		  exit();
		} 
	 
	 if ($op=='delete') {
	 	$id=$_GPC['id'];
	 	$cgc_baoming_code=new cgc_baoming_code();
        $cgc_baoming_code->delete($id); 
        message('删除成功！',referer(), 'success');
	 }
	 
    if ($op=='delete_all') {
           $cgc_baoming_code=new cgc_baoming_code();
           $cgc_baoming_code->deleteAll();  
           message('删除成功！', $this->createWebUrl('cgc_baoming_code', array('op' => 'display')), 'success');
     }
     
 
  	
  	
