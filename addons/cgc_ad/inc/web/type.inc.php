<?php

   global $_W, $_GPC;  
   $settings=$this->module['config'];  
   load()->func('tpl');
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $uniacid=$_W["uniacid"];

   $quan=pdo_fetchall("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$uniacid." AND del=0");
   //$filename=(basename(__file__,'.inc.php'));
   $filename="type";
   $data = new cgc_ad_info_type();
	if ($op=='display') { 		
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 200;
	    $con="weid=$uniacid";	     	
	    
	    if (!empty ($_GPC['quan_id'])) {
			$con .= " AND quan_id= " . intval($_GPC['quan_id']);
		}
	    
	    $info_type_name = $_GPC['info_type_name'];
	    if(!empty($info_type_name)){
	      $con.=" and info_type_name like '%$info_type_name%'";
	    }		
	    $total=0; 
        $list = $data->getAll($con, $pindex,$psize,$total);  
		$pager = pagination($total, $pindex, $psize);
		
		$children = array();
		foreach ($list as $index => $row) {
			if (!empty($row['parent_id'])) {
				$children[$row['parent_id']][] = $row;
				unset($list[$index]);
			}
		}
		

		include $this->template("web/".$filename);

		exit();
  	}
  	
	 if ($op=='post') {
	 	$parent_id = intval($_GPC['parent_id']);
	    $id=$_GPC['id']; 
	    if (!empty($id)){
	       	$page_data= $data->getOne($id); 
	    }
	    
	    if (!empty($parent_id)) {
			$parent = $data->getOne($parent_id);
			$quan=pdo_fetch("SELECT id,aname FROM ".tablename('cgc_ad_quan')." WHERE weid=".$uniacid." AND id=". $parent['quan_id']." AND del=0");
			if (empty($parent)) {
				message('抱歉，上级分类不存在或是已经被删除！',referer(), 'error');
			}
		}
	    	  	    	         	     
		if (checksubmit('submit')) {	
		    
		    $input =array();
		    $input=$_GPC['page_data'];
		    $input['weid'] = $uniacid; 
	        $input['create_time'] = TIMESTAMP; 
	                
			if (!empty($id)) {				
				$temp=$data->modify($id,$input); 
			}
			else{
				$temp=$data->insert($input); 
			}						
			message('信息更新成功',$this->createWebUrl($filename, array('op' => 'display')), 'success');
		}
	     

	    include $this->template("web/".$filename);

		exit();
	} 
 
	 if ($op=='delete') {
	 	$id=$_GPC['id'];
	    $data->delete($id); 
	    message('删除成功',$this->createWebUrl($filename, array('op' => 'display')), 'success');
	 }
	 
	 if ($op=='delete_all') {
	    $data->deleteAll(); 
	    message('删除成功',$this->createWebUrl($filename, array('op' => 'display')), 'success');
	 }
