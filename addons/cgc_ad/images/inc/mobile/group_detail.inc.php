<?php

global $_W,$_GPC;
$weid=$_W['uniacid'];
$quan_id=intval($_GPC['quan_id']);
$id=intval($_GPC['id']);

$op=!empty($_GPC['op'])?$_GPC['op']:"display";


$member=$this->get_member();
$from_user=$member['openid'];
$subscribe=$member['follow'];
$mid=$member['id'];
$quan=$this->get_quan();
$adv=$this->get_adv();

$config = $this ->settings;

$rob_next_time=$this->get_rob_next_time($quan,$member);



if ($op=="display"){	 
    $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$member['id']);    

    if (!empty($_GPC['src'])){
  	  $inviter=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_member')." WHERE weid=$weid AND quan_id=$quan_id  and  id={$_GPC['src']}");
    }
  
	// 如果红包已经开抢，需获取已抢人数和已抢金额
	$rob_cnts=null;
	if($adv['rob_start_time']<time()){
	  $rob_cnts = pdo_fetch("select count(id) as cnt,sum(money) as amount from " . tablename('cgc_ad_red') . " where weid=:uniacid  and quan_id=:quan_id and advid=:advid ",  array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' => $id));
	}

   // 获取我的团队
    if  (!empty($member['id'])){ 	
      $group=pdo_fetch("select * from " . tablename('cgc_ad_group') . " where weid=:uniacid and quan_id=:quan_id and advid=:advid and mine_id=:mine_id ", array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' => $id,':mine_id' => $member['id']));    
    }
			
    $groupers=array();
    $group_result=0;
    
    if(!empty($inviter) && $inviter['id']!=$member['id']){	  	
	  if ($quan['group_guanzhu'] && empty($subscribe)){	
	  	header("location:".$quan['follow_url']);
	  	exit();  		
	    //$this->returnSuccess('先关注才可以组团',$quan['follow_url']);
	  }
    }
    
    if(!empty($group)){
    	
	  // 如果我的团队不为空，则获取我的团队成员
	  $groupers=pdo_fetchall("select * from " . tablename('cgc_ad_group') . " where weid=:uniacid and quan_id=:quan_id and advid=:advid and captain_id=:captain_id ", array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' =>$id,':captain_id' =>$group['captain_id']));
	  
	}else{
	  // 如果我的团队为空，则获取邀请我的人，并加入其团队(前提是还有位置，如果该人还没有团队，则与我自动组成团队)
	  if(!empty($inviter) && $inviter['id']!=$member['id']){	  	  	
	    $group=pdo_fetch("select * from " . tablename('cgc_ad_group') . " where weid=:uniacid and quan_id=:quan_id and advid=:advid and mine_id=:mine_id ", array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' => $id,':mine_id' =>$inviter['id']));
		if(empty($group)){
		  // 如果邀请我的人也没有团队，则两人自动组队(邀请者为队长)
		  // 获取邀请者个人信息
          $invuser=array();
		  pdo_insert('cgc_ad_group', array(
						'weid'=>$_W['uniacid'],
						'quan_id'=>$quan_id,
						'advid'=>$id,
						'captain_id'=>$inviter['id'],
						'mine_id'=>$inviter['id'],
						'user_id'=>$inviter['id'],
						'nickname'=>$inviter['nickname'],
						'headimgurl'=>$inviter['headimgurl'],
						'create_time'=>time()
					));
					
		  pdo_insert('cgc_ad_group', array(
						'weid'=>$_W['uniacid'],
						'quan_id'=>$quan_id,
						'advid'=>$id,
						'captain_id'=>$inviter['id'],
						'mine_id'=>$member['id'],
						'user_id'=>$member['id'],
						'nickname'=>$member['nickname'],
						'headimgurl'=>$member['headimgurl'],
						'create_time'=>time()
					));
			$captain_id=$inviter['id'];	
			$group_result=1;//'您已加入抢钱团伙';
		}else{
		  // 获取该团队人数，判断是否已满
		  $groupernums=pdo_fetchcolumn("select COUNT(id) from " . tablename('cgc_ad_group') . " where weid=:uniacid and quan_id=:quan_id and advid=:advid  and captain_id=:captain_id ", array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' => $id,':captain_id' =>$group['captain_id']));
		  if($groupernums<$adv['group_size']){
		    // 团队未满，我自动加入
		    pdo_insert('cgc_ad_group', array(
							'weid'=>$_W['uniacid'],
							'quan_id'=>$quan_id,
							'advid'=>$id,	
							'captain_id'=>$group['captain_id'],
							'mine_id'=>$member['id'],
							'user_id'=>$member['id'],
							'nickname'=>$member['nickname'],						
							'headimgurl'=>$member['headimgurl'],
							'create_time'=>time()
						));
						$captain_id=$group['captain_id'];
						$group_result=1;//'您已加入抢钱团伙';
						
			  }else{
			   // 团队已满，提示用户
			   $group_result=2;//'好友的团伙满了，重新组团吧';
			  }
	   }
	 // 如果加入成功
	  if($group_result==1){
	     // 获取我的最新团队（队长是邀请我的人）
		 $groupers=pdo_fetchall("select * from " . tablename('cgc_ad_group') . " where weid=:uniacid and quan_id=:quan_id and advid=:advid and captain_id=:captain_id ", array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' => $id,':captain_id' =>$captain_id));
	   }
	 }
  }
		
		
		$gmembercnt=count($groupers);

   $gmembers[]=array(
		'nickname'=>$member['nickname'],
		'headimgurl'=>$member['headimgurl'],	
	 );	

    $group_size_arr=array();
	
	for($i=0;$i<$adv['group_size'];$i++){
	  //如果遇到自己，就跳过，这样自己就可以排在第一位
	  if (!empty($groupers[$i]) && $groupers[$i]['headimgurl']!=$member['headimgurl']){
	    $gmembers[]=$groupers[$i];
	  }
	  $group_size_arr[]=$i;
	}
    
  
	
	

	
  // 抢钱令牌，避免重复提交
  $_SESSION['rob_token'] = md5(microtime(true));
  // 生成我的专属分享链接
   // $share_url=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('foo',array('form'=>'group_detail','op'=>'help','quan_id'=>$quan_id,'pid'=>$member['id'],'id'=>$adv['id'])), 2);
   $share_url=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('group_detail',array('quan_id'=>$quan_id,'id'=>$id,'src'=>($member['id']),'pid'=>($member['id']))), 2);
 
 
                 
  $_msgtotal = pdo_fetchcolumn('SELECT count(id) FROM '.tablename('cgc_ad_message')." WHERE weid=".$weid." AND status=".$quan['is_pl']."  and advid=".$_GPC['id']);
 
  $red=pdo_fetchall("SELECT a.mid,a.money,b.type,b.nicheng,b.thumb,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_red')." as a
  left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
  WHERE a.weid=".$weid." AND a.quan_id=".$adv['quan_id']." AND a.advid=".$id." ORDER BY a.create_time DESC limit 0,5",array(),"mid");


														
  $redcount = pdo_fetchcolumn('SELECT count(id) FROM '.tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$adv['quan_id']." AND advid=".$id);

  $adv['views']=$this->get_view($member,$adv);
 
 
   $_msglist = pdo_fetchall("SELECT a.*,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_message')." a
      left join ".tablename('cgc_ad_member')." b on a.mid=b.id
	  WHERE a.weid=".$weid." AND a.advid=".$id." and a.status=1 order by upbdate desc limit 0,5");
  
      $_msgtotal = pdo_fetchcolumn('SELECT count(id) FROM '.tablename('cgc_ad_message')." WHERE weid=".$weid." AND status=1  and advid=".$_GPC['id']);
                        
  include $this->template('group_detail');
  exit();

} else if($op=="rob"){ 
  if ($_SESSION['rob_token']!=$_GPC['rob_token']){
    $this->returnError('非法提交');
    exit();
  }	
	


  if (!empty($member)){
    $my=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_red')." WHERE weid=".$weid." AND quan_id=".$quan_id." AND advid=".$id." AND mid=".$member['id']);
     if (!empty($my)){
      $this->returnError('本次您已经抢过了，不能重复抢');
	  exit();
     } 
  }
  
  $group=pdo_fetch("select * from " . tablename('cgc_ad_group') . " where weid=:uniacid and quan_id=:quan_id and advid=:advid and mine_id=:mine_id ", array(':uniacid' => $_W['uniacid'],':quan_id' => $quan_id,':advid' => $id,':mine_id' =>$member['id']));
 
  if(empty($group)){
     $this->returnError('你未组队');
   }
 
    
  if($adv['rob_users']>=$adv['total_num']){
    $this->returnError('手慢了，钱被抢光啦！');
   }
   
   if($adv['status']!=1){
     $this->returnError('本次活动已结束');
   }
  

  


  $_SESSION['rob_token'] ="";	
  $ret=cal_red($member,$quan,$adv,$config);
  if ($ret['code']=="0"){
    $this->returnError($ret['msg']);
  } else {
    $this->returnSuccess($ret['msg'],$ret['data']);
   }	
											

														
}else if($op=='get_morered'){	
  $__pages = intval($_GPC['page'])*5;
  $red=pdo_fetchall("SELECT a.mid,a.money,b.type,b.nicheng,b.thumb,b.headimgurl,b.nickname FROM ".tablename('cgc_ad_red')." as a
  left join ".tablename('cgc_ad_member')." as b on a.mid=b.id
  WHERE a.weid=".$weid." AND a.quan_id=".$adv['quan_id']." AND a.advid=".$id." ORDER BY a.create_time DESC limit $__pages,5",array(),"mid");

  if (!empty($red)){

  } else {
     exit(json_encode(array('code'=>0,'data'=>$red)));
  } 
   exit(json_encode(array('code'=>1,'data'=>$red)));
	
} else if($op=='reply_admin'){

}
	



?>