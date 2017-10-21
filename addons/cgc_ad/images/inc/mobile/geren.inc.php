<?php

 global $_W, $_GPC;
 $weid = $_W['uniacid'];
 $quan_id = intval($_GPC['quan_id']);
 $id = intval($_GPC['id']);
 $member = $this -> get_member();
 $from_user = $member['openid'];
 $quan=$this->get_quan();
 $mid = $member['id'];
 $op = $_GPC['op'];
 
 $subscribe= sfgz_user($from_user);

 $config = $this ->settings;

 if($op == 'tx')
{
    
     if(date('G') >= 24 || date('G') < 8){
        
         $this -> returnError('每天24点至次日8点期间暂停转账');
        
         }
         
         if ($quan['tx_follow'] && empty($subscribe)){
            $this -> returnError('必须关注才可以提现',$quan['follow_url'],"3");
         }
    
     // 获取1分钟内提现的次数(避免时间误差，此处限值到55秒)
    $total_cnt = pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_tx') . " where weid=:weid AND create_time>:cold_time ", array(':weid' => $_W['uniacid'], ':cold_time' => (time()-55)));
    
     if($total_cnt > 1800){
        
         $this -> returnError('当前操作人数较多，请稍后再试');
        
         }
    
    
    
     // 获取1分钟内同一用户（这个用户是针对微信的用户，所以是用openid确定）的提现的次数(避免时间误差，此处限值到55秒)
    $user_cnt = pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_tx') . " where weid=:weid AND openid=:openid AND create_time>:cold_time ", array(':weid' => $_W['uniacid'], ':openid' => $from_user, ':cold_time' => (time()-55)));
    
     if($user_cnt >= 1){
        
         $this -> returnError('每分钟只能转账1次，请稍后再试');
        
         }
    
    
    
     // 获取用户当前可用余额(分)
    $money = $member['credit'];
    

     if($money < $quan['tx_min'])
    
    {
        
         $this -> returnError('至少满' . $quan['tx_min'] . $config['uni_text'].'才可转哦~');
        
     }
     
     if($money < 1){
        
         $this -> returnError('至少满1'.$config['uni_text'].'才可转哦~');
        
     }
    
    
     // 如果余额大于200元，则只转200元
    if($money > 200){
        
         $money = 200;
        
    }
    
    
    
     // 先生成转账记录
    pdo_insert('cgc_ad_tx', array(
            
            'weid' => $_W['weid'],
            
             'quan_id' => $member['quan_id'],
            
             'mid' => $mid,
            
             'openid' => $from_user,
            
             'money' => $money,
            
             'money_before' => $member['credit'],
            
             'money_after' => $member['credit'] - $money,
            
             'status' => 0,
            
             'create_time' => time(),
            
             'update_time' => time()
            
            ));
    
     $transfer_id = pdo_insertid();
    
     if(empty($transfer_id)){
        
         $this -> returnError('操作失败，请重试');
        
     }
    
     // 再扣款
    $ret1 = pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET credit=credit-:credit where id=:id', array(':id' => $mid, ':credit' => $money));
    
     if(false === $ret1){
        
         $this -> returnError('操作失败，请重试');
        
     }
    
     $ret2 = $this -> transferByRedpack(array(
            
            'id' => $transfer_id,
            
             'nick_name' => $quan['aname'],
            
             'send_name' => $quan['aname'],
            
             'money' => intval($money * 100),
            
             'openid' => $from_user,
            
             'wishing' => '祝您天天开心！',
            
             'act_name' => '提现红包',
            
             'remark' => '用户微信红包提现'
            
            ));
    
     	if(is_error($ret2)){ // 转账失败
	         // 回款
	        pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET credit=credit+:credit where id=:id', array(':id' => $mid, ':credit' => $money));
	         // 回记录
	        pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=3,update_time=:update_time where id=:id', array(':update_time' => time(), ':id' => $transfer_id));
        
         	$this -> returnError('操作失败：' . $ret2['message']);
         }else{ // 转账成功
         	// 更新记录状态
	        pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=1,channel=:channel,mch_billno=:mch_billno,out_billno=:out_billno,out_money=:out_money,tag=:tag,update_time=:update_time where id=:id', array(':channel' => 1, ':mch_billno' => $ret2['mch_billno'], ':out_billno' => $ret2['out_billno'], ':out_money' => $ret2['out_money'], ':tag' => $ret2['tag'], ':update_time' => time(), ':id' => $transfer_id));
	         $this -> returnSuccess('成功提出' . ($money) . $config['uni_text']);
         }
     }else if($op == 'revice'){
	     $status = $_GPC['status'];
	     $id = $_GPC['id'];
	     $quan_id = $_GPC['quan_id'];
	     pdo_update('cgc_ad_member', array('is_revice' => $status), array('weid' => $weid, 'id' => $mid, 'quan_id' => $quan_id));
	     $this -> returnSuccess('设置成功');
	 }else if($op == 'down'){
	     WeUtility :: logging('cgc_ad geren $down_total', $down_total);
	     $down_data = pdo_fetch("select COUNT(id) down_total, SUM(up_money) as down_money from " . tablename('cgc_ad_member') . " where weid=:weid and quan_id=:quan_id and inviter_id=:inviter_id ", array(
	            ':weid' => $_W['uniacid'],
	             ':quan_id' => $_GPC['quan_id'],
	             ':inviter_id' => $mid
	            ));
	     $down_money = $down_data['down_money'];
	     $down_total = $down_data['down_total'];
	    
	    
	     include $this -> template('geren_down');
     }else if($op == 'profile'){
     	if ($_GPC['submit'] == "save"){      
	         $imgid = $_GPC['avatar_val'];
	        
	         if (!empty($imgid)){
	             $WeiXinAccountService = WeiXinAccount :: create($_W['acid']);
	             $ret = $WeiXinAccountService -> downloadMedia(array(
	                    'media_id' => $imgid,
	                     'type' => 'image'
	                    
	                    ));
	             if (is_error($ret)){
	                 $this -> returnError($ret['message']);
	                 }
	             $_GPC['headimgurl'] = tomedia($ret);
	         }
	             
	         $qrcode_imgid = $_GPC['qrcode_val'];
	         if (!empty($qrcode_imgid)){
	         	if(empty($WeiXinAccountService)){
	         		$WeiXinAccountService = WeiXinAccount :: create($_W['acid']);
	         	}
	         	$ret2 = $WeiXinAccountService -> downloadMedia(array(
	         			'media_id' => $qrcode_imgid,
	         			'type' => 'image'
	         
	         	));
	         	
	         	if (is_error($ret2)){
	         		$this -> returnError($ret2['message']);
	         	}
	         	$_GPC['qr_code'] = tomedia($ret2);
	         }
	        
	         $data = array("nickname" => $_GPC['nickname'], "telphone" => $_GPC['telphone'], "headimgurl" => $_GPC['headimgurl'], "qr_code" => $_GPC['qr_code']);
	         pdo_update('cgc_ad_member', $data, array('weid' => $weid, 'id' => $mid, 'quan_id' => $quan_id));
	         $this -> returnSuccess("保存成功");
     	}
     	include $this -> template('geren_profile');
    }
    else if($op == 'newpiece')
    {
    	 $status = $_GPC['status'];
	     $id = $_GPC['id'];
	     $quan_id = $_GPC['quan_id'];
	     pdo_update('cgc_ad_member', array('message_notify' => $status), array('weid' => $weid, 'id' => $mid, 'quan_id' => $quan_id));
	     $this -> returnSuccess('设置成功');
    }
    else {
	     $down_total = pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_member') . " where weid=:weid and quan_id=:quan_id and inviter_id=:inviter_id ", array(
	            ':weid' => $_W['uniacid'],
	             ':quan_id' => $_GPC['quan_id'],
	             ':inviter_id' => $mid
	            ));
	     include $this -> template('geren');
     }


