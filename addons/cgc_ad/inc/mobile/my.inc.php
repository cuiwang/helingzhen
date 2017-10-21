<?php
global $_W, $_GPC;
$weid = $_W['uniacid'];
$quan_id = intval($_GPC['quan_id']);
$id = intval($_GPC['id']);
$member = $this->get_member();
$from_user = $member['openid'];
$quan = $this->get_quan();
$mid = $member['id'];
$op = empty($_GPC['op']) ? "display" : $_GPC['op'];
$subscribe = $member['follow'];


/*if ($quan['guanzhu_direct']) {
  //$subscribe = $member['follow'];
} else {
  //$subscribe = $member[''];
}*/

$config = $this->settings;
$settings = $this->module['config'];
if ($op == 'display') {
    $_SESSION['rob_token'] = md5(microtime(true));
    $down_total = pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_member') . " where weid=:weid and quan_id=:quan_id and inviter_id=:inviter_id ", array(
        ':weid' => $_W['uniacid'],
        ':quan_id' => $_GPC['quan_id'],
        ':inviter_id' => $mid
    ));
    require_once IA_ROOT . "/addons/cgc_ad/source/common/poster_common.php";
    $poster = get_post_data($quan_id);
    if (empty($quan['templet_id'])){
      include $this->template('geren');
    } else {
      include $this->template('my');
    }
    exit();
}
if ($op == 'tx') {
    if (date('G') < 8) {
      $this->returnError('每天24点至次日8点期间暂停转账');
    }
    
    if (($subscribe) && $_W['account']['level'] > 3) {
      $subscribe = sfgz_user($_W['openid']);
    }
  
    // 获取用户当前可用余额(分)
    $money = $member['credit'];
    if ($money > 10 && $_SESSION['rob_token'] != $_GPC['token']) {
        $this->returnError('重复提交，请重新进入本页面提交。');
    }
    $_SESSION['rob_token'] = "";
    //$money>10 &&
    if ($quan['tx_follow'] && empty($subscribe)) {
        $this->returnError('must_guanzhu');
    }
    if ($money < $quan['tx_min']) {
        $this->returnError('至少满' . $quan['tx_min'] . $config['uni_text'] . '才可转哦~');
    }
    if ($money < 1) {
        $this->returnError('至少满1' . $config['uni_text'] . '才可转哦~');
    }
    // 获取1分钟内提现的次数(避免时间误差，此处限值到55秒)
    $total_cnt = pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_tx') . " where weid=:weid AND create_time>:cold_time ", array(
        ':weid' => $_W['uniacid'],
        ':cold_time' => (time() - 55)
    ));
    if ($total_cnt > 100) {
        $this->returnError('当前忙，请稍后再试');
    }
    $user_cnt = pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_tx') . " where weid=:weid AND openid=:openid and create_time>:cold_time ", array(
        ':weid' => $_W['uniacid'],
        ':openid' => $from_user,
        ':cold_time' => (time() - 1800)
    ));
    
    if ($user_cnt >= 1) {
        $this->returnError('提现频率太高，休息一个小时在来操作吧。');
    }
    
/*    $user_cnt = pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_tx') . " where weid=:weid AND openid=:openid and status=0 ", array(
        ':weid' => $_W['uniacid'],
        ':openid' => $from_user
    ));
    if ($user_cnt >= 1) {
        $this->returnError('有未提现的请求还没处理。');
    }
    */
    if ($quan['tx_num']) {
        $curr_time = mktime(0, 0, 0, date("m", time()) , date("d", time()) , date("y", time()));
        $user_cnt = pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_tx') . " where weid=:weid AND openid=:openid AND status=1 and create_time>:cold_time ", array(
            ':weid' => $_W['uniacid'],
            ':openid' => $from_user,
            ':cold_time' => $curr_time
        ));
        if ($user_cnt >= $quan['tx_num']) {
            $this->returnError('每天只能转账' . $quan['tx_num'] . '次，请稍后再试');
        }
    }
    // 如果余额大于200元，则只转200元
    if ($money > 200) {
        $money = 200;
    }
    if (empty($quan['tx_time'])){
      $tx_time=time();
    } else {
      $tx_time=time()+intval($quan['tx_time'])*60;
    }
    
    // 先生成转账记录
    pdo_insert('cgc_ad_tx', array(
        'weid' => $_W['weid'],
        'quan_id' => $member['quan_id'],
        'mid' => $mid,
        'openid' => $from_user,
        'nickname' => $member['nickname'],
        'headimgurl' => $member['headimgurl'],
        'money' => $money,
        'money_before' => $member['credit'],
        'money_after' => $member['credit'] - $money,
        'status' => 0,
        'create_time' => time() ,
        'update_time' => time(),
        'tx_time'=> $tx_time
    ));
    $transfer_id = pdo_insertid();
    if (empty($transfer_id)) {
        $this->returnError('操作失败，请重试');
    }
    if ($quan['tx_control'] && (!empty($quan['tx_money']) && $money >= $quan['tx_money'])) {
        pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET credit=credit-:credit,no_account_amount=no_account_amount+:no_account_amount where id=:id', array(
            ':id' => $mid,
            ':no_account_amount' => $money,
            ':credit' => $money
        ));
             
        if ($quan['tx_time']){
          $this->returnSuccess('你的提现已经在审核了，大概在'.$quan['tx_time'].'分钟以后打款给你，请到时候在打开本页面查看');
        } else {
          $this->returnSuccess('你的提现已经在审核了，请耐心等待');
        }
    }
    

    
    // 再扣款
    $ret1 = pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET credit=credit-:credit,fee=fee+:fee where id=:id', array(
        ':id' => $mid,
        ':credit' => $money,
        ':fee' => $money - $tx_money
    ));
    if (false === $ret1) {
        $this->returnError('操作失败，请重试');
    }
    $quan['tx_percent'] = empty($quan['tx_percent']) ? 0 : $quan['tx_percent'];
    $tx_money = $money * ((100 - $quan['tx_percent']) / 100);
    if ($settings['tx_type']) {
        $md5 = md5("{$_W['uniacid']}{$_W['config']['setting']['authkey']}");
        $end = $weid;
        if (file_exists(IA_ROOT . '/cert/rootca.pem.' . $md5)) {
            $end = $md5;
        }
        $config['rootca'] = IA_ROOT . '/cert/rootca.pem.' . $end;
        $config['apiclient_cert'] = IA_ROOT . '/cert/apiclient_cert.pem.' . $end;
        $config['apiclient_key'] = IA_ROOT . '/cert/apiclient_key.pem.' . $end;
        $config['pay_desc'] = $quan['aname'] . "的红包。";
        $ret2 = send_qyfk($config, $from_user, $tx_money);
    } else {
        $ret2 = $this->transferByRedpack(array(
            'id' => $transfer_id,
            'nick_name' => $quan['aname'],
            'send_name' => $quan['aname'],
            'money' => intval($tx_money * 100) ,
            'openid' => $from_user,
            'wishing' => '祝您天天开心！',
            'act_name' => '提现红包',
            'remark' => '用户微信红包提现'
        ));
    }
    if (is_error($ret2)) { // 转账失败
        pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET credit=credit+:credit,fee=fee-:fee where id=:id', array(
            ':id' => $mid,
            ':credit' => $money,
            ':fee' => $money - $tx_money
        ));
        // 回记录
        pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=3,update_time=:update_time,create_time=:create_time where id=:id', array(
            ':update_time' => time() ,
            ':create_time' => time() ,
            ':id' => $transfer_id
        ));
        $this->returnError('操作失败：' . $ret2['message']);
    } else { // 转账成功
        // 更新记录状态
        pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=1,channel=:channel,mch_billno=:mch_billno,out_billno=:out_billno,out_money=:out_money,tag=:tag,update_time=:update_time where id=:id', array(
            ':channel' => 1,
            ':mch_billno' => $ret2['mch_billno'],
            ':out_billno' => $ret2['out_billno'],
            ':out_money' => $ret2['out_money'],
            ':tag' => $ret2['tag'],
            ':update_time' => time() ,
            ':id' => $transfer_id
        ));
        $this->returnSuccess('成功提出' . $tx_money . $config['uni_text']);
    }
} else if ($op == 'revice') {
    $status = $_GPC['status'];
    $id = $_GPC['id'];
    $quan_id = $_GPC['quan_id'];
    pdo_update('cgc_ad_member', array(
        'is_revice' => $status
    ) , array(
        'weid' => $weid,
        'id' => $mid,
        'quan_id' => $quan_id
    ));
    $this->returnSuccess('设置成功');
} else if ($op == 'down') {
    require_once IA_ROOT . "/addons/cgc_ad/source/common/poster_common.php";
    $poster = get_post_data($quan_id);
    $down_data = pdo_fetch("select COUNT(id) down_total, SUM(up_money) as down_money from " . tablename('cgc_ad_member') . " where weid=:weid and quan_id=:quan_id and inviter_id=:inviter_id ", array(
        ':weid' => $_W['uniacid'],
        ':quan_id' => $_GPC['quan_id'],
        ':inviter_id' => $mid
    ));
    $down_money = $down_data['down_money'];
    $down_total = $down_data['down_total'];
    $con = " weid=" . $weid . " AND quan_id=" . $quan_id . " AND status=1 AND inviter_id=" . $mid;
    $total = 0;
    $psize = 30;
    $pindex = empty($_GPC['page']) ? 1 : $_GPC['page'];
    $cgc_ad_member = new cgc_ad_member();
    $list = $cgc_ad_member->getAll($con, $pindex, $psize, $total);
    if ($_W['isajax']) {
        exit(json_encode(array(
            "code" => "1",
            "data" => $list,
            "count" => count($list)
        )));
    }
    include $this->template('geren_down');
} else if ($op == 'profile') {
    if ($_GPC['submit'] == "save") {
        $imgid = $_GPC['avatar_val'];
        if (!empty($imgid)) {
            $WeiXinAccountService = WeiXinAccount::create($_W['acid']);
            $ret = $WeiXinAccountService->downloadMedia(array(
                'media_id' => $imgid,
                'type' => 'image'
              ),true);
            if (is_error($ret)) {
                $this->returnError($ret['message']);
            }
            $_GPC['headimgurl'] = tomedia($ret);
        }
        $qrcode_imgid = $_GPC['qrcode_val'];
        if (!empty($qrcode_imgid)) {
            if (empty($WeiXinAccountService)) {
                $WeiXinAccountService = WeiXinAccount::create($_W['oauth_account']);
            }
            $ret2 = $WeiXinAccountService->downloadMedia(array(
                'media_id' => $qrcode_imgid,
                'type' => 'image'
             ),true);
            if (is_error($ret2)) {
                $this->returnError($qrcode_imgid . $ret2['message']);
            }
            $_GPC['qr_code'] = tomedia($ret2);
        }
        $data = array(
            "nickname" => $_GPC['nickname'],
            "telphone" => $_GPC['telphone'],
            "headimgurl" => $_GPC['headimgurl'],
            "qr_code" => $_GPC['qr_code']
        );
        pdo_update('cgc_ad_member', $data, array(
            'weid' => $weid,
            'id' => $mid,
            'quan_id' => $quan_id
        ));
        $this->returnSuccess("保存成功");
    }
    include $this->template('geren_profile');
} else if ($op == 'newpiece') {
    $status = $_GPC['status'];
    $id = $_GPC['id'];
    $quan_id = $_GPC['quan_id'];
    pdo_update('cgc_ad_member', array(
        'message_notify' => $status
    ) , array(
        'weid' => $weid,
        'id' => $mid,
        'quan_id' => $quan_id
    ));
    $this->returnSuccess('设置成功');
} else if ($op == 'couponc') {
	
	include $this->template('geren_couponc');
	
}else if($op=='couponc_list'){	
	$start=$_GPC['start'];// st(start):当前已加载记录数(按类型累计)
	if(!isset($start) || empty($start) || intval($start<=0)){
		$start=0;
	}else{
		$start=intval($start);
	}
	
	$con = '';
	$time = time();
	$tag=$_GPC['tag'];
	if($tag=='1'){//已过期
		$con=" and couponc_valid_date<".$time." and status=0 "; 
	}else if($tag=='2'){//已使用 
		$con=" and status=1 ";
	}else{
		$con=" and couponc_valid_date>=".$time." and status=0 "; 
	}
	
	$limit=20;

	$list = pdo_fetchall("select * from " . tablename('cgc_ad_couponc') . " where weid=:weid and quan_id=:quan_id and mid=:mid ".$con." ORDER BY create_time DESC limit ".$start.",".$limit." ",  array(':weid' => $_W['uniacid'],':quan_id' => $quan_id,':mid'=> $member['id']));
	
	$more=1;
	if(empty($list) || count($list)<$limit){
		$more=0;
	}
	$start+=count($list);

	// 数据业务处理
	if(!empty($list)){
		 // 生成访问地址
		for($i=0;$i<count($list);$i++){
			$list[$i]['_url']=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('couponc',array('quan_id'=>$quan_id,'id'=>$list[$i]['advid'])), 2);
			$list[$i]['_couponc_valid_date']=!empty($list[$i]['couponc_valid_date'])?date('Y年m月d日',$list[$i]['couponc_valid_date']):'';
		}
	}

	$this->returnSuccess('',array(
		'start'=>$start,
		'more'=>$more,
		'list'=>$list,
		'now'=>time()// 下传递服务器时间用于倒计时
	));

} else if ($op=="clear"){ 
  if (empty($quan['tx_time'])){
	  exit();
	}
  $cgc_ad_tx=new  cgc_ad_tx();
  $ret=$cgc_ad_tx->hasExists($quan_id,$from_user);
  if ($ret['code']!=0){
   $this->returnSuccess($ret['msg']);
  }
   
  $transfer_id=$ret['msg']['id'];
  //先直接提交设置成功标记，以免重复提交。
  pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=1,update_time=:update_time where id=:id', array(':update_time' => time(), ':id' => $transfer_id));
  
   $money=$ret['msg']['money'];
   $quan['tx_percent']=empty($quan['tx_percent'])?0:$quan['tx_percent'];
   $tx_money=$money*((100-$quan['tx_percent'])/100); 
   if ($settings['tx_type']) {
     $md5 = md5("{$_W['uniacid']}{$_W['config']['setting']['authkey']}");
     $end = $weid;
     if (file_exists(IA_ROOT . '/cert/rootca.pem.' . $md5)) {
       $end = $md5;
     }
     $config['rootca'] = IA_ROOT . '/cert/rootca.pem.' . $end;
     $config['apiclient_cert'] = IA_ROOT . '/cert/apiclient_cert.pem.' . $end;
     $config['apiclient_key'] = IA_ROOT . '/cert/apiclient_key.pem.' . $end;
     $config['pay_desc'] = $quan['aname'] . "的红包。";
     $ret2 = send_qyfk($config, $from_user, $tx_money);
    } else {
     $ret2 = $this -> transferByRedpack(array(          
            'id' => $transfer_id,          
             'nick_name' => $quan['aname'],          
             'send_name' => $quan['aname'],          
             'money' => intval($tx_money * 100),           
             'openid' => $from_user,           
             'wishing' => '祝您天天开心！',           
             'act_name' => '提现红包',            
             'remark' => '用户微信红包提现'            
         ));  
     }         

    if(is_error($ret2)){ // 转账失败	       
	   // 回记录
	   pdo_query('UPDATE ' . tablename('cgc_ad_tx') . ' SET status=4 where id=:id', array( ':id' => $transfer_id));        
        $this->returnError('操作失败：' . $ret2['message']);
     }else{ // 转账成功
	   pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET no_account_amount=no_account_amount-:no_account_amount,fee=fee+:fee where id=:id', array(':id' => $mid, ':no_account_amount' => $money, ':fee' => $money-$tx_money));
       $this->returnSuccess('成功提出' .$tx_money. $config['uni_text']);
     }
     

}

