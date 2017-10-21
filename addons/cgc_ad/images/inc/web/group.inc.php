<?php

   global $_W, $_GPC;  
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $uniacid=$_W["uniacid"];
   $advid=$_GPC['advid'];
   
   $cgc_ad_group = new cgc_ad_group();
   
   if ($op=='display') { 		
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
	    $con="weid=$uniacid AND advid=$advid";
	    if (!empty($_GPC['nickname'])) {
	    	$nickname = $_GPC['nickname'];
	    	$con .= " AND `nickname` like '%$nickname%' ";
	    }
	    
	   if (!empty($_GPC['captain_nickname'])) {
	    	$captain_nickname = $_GPC['captain_nickname'];
	    	$con .= " AND `captain_nickname` like '%$captain_nickname%' ";
	    }
	    
	    if (!empty($_GPC['captain_id'])) {
	    	$captain_id = $_GPC['captain_id'];
	    	$con .= " AND `captain_id` = $captain_id";
	    }
	    
	    $total=0; 
        $list = $cgc_ad_group->getAll($con, $pindex,$psize,$total);
		$pager = pagination($total, $pindex, $psize);	
		 include $this->template('web/group');	
  	}
		
	  if ($op=='delete') {
			$id=$_GPC['id'];
			$cgc_ad_group->delete($id);
			message('删除成功！',referer(), 'success');
	  }
	  
	  if ($op=='deleteAll') {
			$cgc_ad_group->deleteAll();
			message('删除成功！',referer(), 'success');
		} 	
	 
