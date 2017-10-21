<?php

   global $_W, $_GPC;  
   $title = 'VIP交易记录';
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $weid=$uniacid=$_W["uniacid"];
   $advid=$_GPC['advid'];
   $quan_id=$_GPC['quan_id'];
   $id=$_GPC['id'];
   
   $cgc_ad_vip_pay=new cgc_ad_vip_pay();
   
   if ($op=='display') { 		
	   	$cgc_ad_quan = new cgc_ad_quan();
	   	$quan=$cgc_ad_quan->getAll("weid=$weid and del=0",1,10000);
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
	    $con="weid=$uniacid";
	    
	    $status=$_GPC['status'];
	    $nickname=$_GPC['nickname'];
	    
	    if (!empty($quan_id)) {
	    	$con .= " AND `quan_id`=$quan_id";
	    }
	     
	    if (!empty($nickname)) {
	    	$con .= " AND `nickname` like '%$nickname%'";
	    }
	     
	    if (!empty($status)||$status=='0') {
	    	$con .= " AND `status`=$status";
	    }
	     
	    $starttime = empty($_GPC['time']['start']) ? strtotime('-30 days') : strtotime($_GPC['time']['start']);
	    $endtime = empty($_GPC['time']['end']) ? TIMESTAMP + 86399 : strtotime($_GPC['time']['end']) + 86399;
	    
	    $con .= "  AND createtime>=$starttime and createtime<=$endtime";
	    
	    $total=0; 
        $list = $cgc_ad_vip_pay->getAll($con, $pindex,$psize,$total);
        $_vip_total = pdo_fetchcolumn("SELECT SUM(pay) FROM ".tablename('cgc_ad_vip_pay')." WHERE $con AND pay>0"); //vip充值金额
       
        
        
		$pager = pagination($total, $pindex, $psize);		
  	}else if ($op=='delete') {
			$id=$_GPC['id'];
			$cgc_ad_vip_pay->delete($id);
			message('删除成功！',referer(), 'success');
		}else if ($op=='deleteAll') {
			$cgc_ad_vip_pay->deleteAll();
			message('删除成功！',referer(), 'success');
		}
		include $this->template('web/cgc_ad_vip_pay');
