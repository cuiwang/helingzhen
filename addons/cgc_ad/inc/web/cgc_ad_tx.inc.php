<?php

   global $_W, $_GPC;  
   $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
   $weid=$uniacid=$_W["uniacid"];
   $advid=$_GPC['advid'];
   $quan_id=$_GPC['quan_id'];
   $cgc_ad_tx = new cgc_ad_tx();
   $config = $this ->settings;
 
   if ($op=='display') { 
   	 $cgc_ad_quan = new cgc_ad_quan();
     $quan=$cgc_ad_quan->getAll("weid=$weid and del=0",1,10000);		
     $pindex = max(1, intval($_GPC['page']));	
	 $psize= 20;
	 //status=3前台提现失败的，不在这里处理
	 $con="weid=$uniacid and status!=3";
	 $status=$_GPC['status'];
	 $nickname=$_GPC['nickname'];	  
	 
	 if (!empty($quan_id)) {
	   $con .= " AND `quan_id`=$quan_id";
	  }	 
	  
	  
	  if (!empty($nickname)) {
	   $con .= " AND `nickname` like '%$nickname%'";
	  }	 
	  
	  if (!empty($status) || $status==="0") {
	   $con .= " AND `status`=$status";
	  }	 
	  
     $starttime = empty($_GPC['time']['start']) ? strtotime('-30 days') : strtotime($_GPC['time']['start']);
	 $endtime = empty($_GPC['time']['end']) ? TIMESTAMP + 86399 : strtotime($_GPC['time']['end']) + 86399;
	
	 $con  .= "  AND create_time>=$starttime and create_time<=$endtime";
	 
	  $total=0;

	

      $list = $cgc_ad_tx->getAll($con, $pindex,$psize,$total);
	  $pager = pagination($total, $pindex, $psize);	
	  include $this->template('web/cgc_ad_tx');	
  	}else if ($op=='delete') {
	  $id=$_GPC['id'];
	  $quan_id=$_GPC['quan_id'];
	  $cgc_ad_tx->delete($quan_id,$id);
	  message('删除成功！',referer(), 'success');
	 }else if ($op=='deleteAll') {
	  $cgc_ad_tx->deleteAll($advid);
	  message('删除成功！',referer(), 'success');
     }else if($op=='tx'){   
   	  $transfer_id=$_GPC['id'];
   	  $ad_tx=$cgc_ad_tx->getOne($transfer_id);
      if ($ad_tx['status']==1){
        message("已经提现过了");
      }
      $quan_id=$_GPC['quan_id'];
      $cgc_ad_quan=new cgc_ad_quan();
      $quan =$cgc_ad_quan->getOne($quan_id);
      $money=$ad_tx['money'];
      $quan['tx_percent']=empty($quan['tx_percent'])?0:$quan['tx_percent'];
	  $tx_money=$money*((100-$quan['tx_percent'])/100); 
      $mid=$ad_tx['mid'];
      $ret2 = $this -> transferByRedpack(array(          
            'id' => $transfer_id,          
             'nick_name' => $quan['aname'],          
             'send_name' => $quan['aname'],          
             'money' => intval($tx_money * 100),           
             'openid' => $ad_tx['openid'],           
             'wishing' => '祝您天天开心！',           
             'act_name' => '提现红包',            
             'remark' => '用户微信红包提现'            
            ));           

     	if(is_error($ret2)){ // 转账失败	       
	         // 回记录
	        pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=4 where id=:id', array( ':id' => $transfer_id));        
         	message('操作失败：' . $ret2['message'],referer(),"error");
         }else{ // 转账成功
         	// 更新记录状态
	        pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=1,channel=:channel,mch_billno=:mch_billno,out_billno=:out_billno,out_money=:out_money,tag=:tag,update_time=:update_time where id=:id', array(':channel' => 1, ':mch_billno' => $ret2['mch_billno'], ':out_billno' => $ret2['out_billno'], ':out_money' => $ret2['out_money'], ':tag' => $ret2['tag'], ':update_time' => time(), ':id' => $transfer_id));
	        pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET no_account_amount=no_account_amount-:no_account_amount,fee=fee+:fee where id=:id', array(':id' => $mid, ':no_account_amount' => $money, ':fee' => $money-$tx_money));
	         message('成功提出' .$tx_money. $config['uni_text'],referer(),"success");
         }
     
   
  }else if($op=='batch_check'){
  	$quan_id=$_GPC['quan_id'];  
  	$ids = implode(',',$_GPC['select']);
  	echo $ids. var_dump($_GPC['select']);
  	if (empty($ids)){
  	  message('id不得为空',referer(), 'error');
  	}
  	$quan_id = $_GPC['quan_id'];
  	$cgc_ad_tx=new cgc_ad_tx();
  	$ad_tx=$cgc_ad_tx->getByConAll("weid=".$weid." AND id in (".$ids.") and status!=3 and status!=1");
    
    if (empty($ad_tx)){
      message('记录不得为空',referer(), 'error');
  	}
  	$num=0;	 
  	
    foreach ($ad_tx as $tx){ 
      $transfer_id=$tx['id'];
      $quan_id=$tx['quan_id'];
      $money=$tx['money'];
      $mid=$tx['mid'];      
      $openid =$tx['openid']; 
      $cgc_ad_quan=new cgc_ad_quan();
      $quan =$cgc_ad_quan->getOne($quan_id);      
      $quan['tx_percent']=empty($quan['tx_percent'])?0:$quan['tx_percent'];
	  $tx_money=$money*((100-$quan['tx_percent'])/100); 
	         
  	  $ret2 = $this -> transferByRedpack(array(          
            'id' => $transfer_id,          
             'nick_name' => $quan['aname'],          
             'send_name' => $quan['aname'],          
             'money' => intval($tx_money * 100),           
             'openid' => $openid,           
             'wishing' => '祝您天天开心！',           
             'act_name' => '提现红包',            
             'remark' => '用户微信红包提现'            
            )); 
            
       if(is_error($ret2)){ 
           $message=$ret2['message'].",";	       
	       pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=4 where id=:id', array( ':id' => $transfer_id));                
         }else{ 
           $num++;       
	       pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=1,mch_billno=:mch_billno,out_billno=:out_billno,out_money=:out_money,tag=:tag,update_time=:update_time where id=:id', array(':mch_billno' => $ret2['mch_billno'], ':out_billno' => $ret2['out_billno'], ':out_money' => $ret2['out_money'], ':tag' => $ret2['tag'], ':update_time' => time(), ':id' => $transfer_id));
           pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET no_account_amount=no_account_amount-:no_account_amount,fee=fee+:fee where id=:id', array(':id' => $mid, ':no_account_amount' => $money, ':fee' => $money-$tx_money));
         }     
    }
    message("批量审核".$num."条"."成功,返回信息:".$message,$this->createWebUrl('cgc_ad_tx'), 'success');
 
  }

if ($op=='batch_del') {
	$ids=$_GPC['select'];
	if (empty($ids)){
		message('信息不能为空.');
	}

	foreach ($ids as $id){
		$cgc_ad_tx->deleteone($id);
	}
	message('删除成功',$this->createWebUrl('cgc_ad_tx', array('op' => 'display')), 'success');
}
	 
