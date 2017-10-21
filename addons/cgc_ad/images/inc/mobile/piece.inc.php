<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;

$this->_doMobileAuth();
$user=$this->_user;
$is_user_infoed=$this->_is_user_infoed;

$this->_doMobileInitialize();
$cmd=$this->_cmd;
$wall=$this->_wall;
$wall_status=$this->_wall_status;
$mine=$this->_mine;

$config = $this ->settings;


// 获取当前进入的红包
$piid = $_GPC['piid'];
if(empty($piid)){
	$this->returnError('访问错误，缺少参数');
}
$piid=pdecode($piid);
if(empty($piid)){
	$this->returnError('访问错误，参数错误');
}
$piid = intval($piid);
if($piid<=0){
	$this->returnError('访问错误，参数有误');
}
$piece = pdo_fetch("select * from " . tablename('gandl_wall_piece') . " where uniacid=:uniacid and wall_id=:wall_id and id=:id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':id' => $piid));
if(empty($piece)){
	$this->returnError('你要访问的，已经不见了');
}

// 获取我与该红包的关系(只有抢到的人才有，所以该变量可能会为空)
$mine_rob = pdo_fetch("select * from " . tablename('gandl_wall_rob') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':user_id' => $user['uid']));

// 该内容是否可访问
if($piece['op']==1){
	if(!($mine['admin']>0)){
		$this->returnError('该内容已被禁止访问，原因：'.$piece['op_remark']);
	}
}

$cmd=$_GPC['cmd']; // 请求命令
if($cmd=='rob'){ // 抢红包
	// 只有登录后才能进入抢红包页面
	if($is_user_infoed==0){
		$this->returnError('登录后才能抢钱哦~');
	}

	// 判断我是否符合地域限制
	if(!($mine['admin']>0)){ // 非管理员有限制
		if((!empty($wall['province']) || !empty($wall['city']) || !empty($wall['district']))){
			if($mine['in_position']!=1){
				$this->returnError('抱歉，您的位置不在'.$wall['province'].$wall['city'].$wall['district'].'，无法参与抢钱');
			}
		}
	}

	// 判断是否已经开枪
	if($piece['rob_start_time']>time()){
		$this->returnError('请稍等，还没到开抢时间哦~');
	}

	// 判断我现在是否可抢
	if($mine['rob_next_time']>time()){
		$this->returnError('rob_cold');
	}

	// 判断我又没有抢过
	if($_GPC['rob_token'] !== $_SESSION['gandl_wall_rob_token']){
		$this->returnError('不能重复抢哦~');
	}
	if(!empty($mine_rob)){
		$this->returnError('本次您已经抢过了，不能重复抢');
	}

	// 判断有没有抢完
	if($piece['rob_users']>=$piece['total_num']){
		$this->returnError('手慢了，钱被抢光啦！');
	}
	// 判断红包状态是否可抢
	if($piece['status']!=1){
		$this->returnError('本次已哄抢结束');
	}
	
	// 如果是口令模式
	if($piece['model']==2){
		if(!empty($piece['password'])){ // 口令抢钱
			$rob_password=$_GPC['rob_password'];
			$rob_password=trim($rob_password);
			if(empty($rob_password)){
				$this->returnError('请输入口令');
			}
			if($rob_password!=$piece['password']){
				$this->returnError('口令错误，请重输');
			}
		}
	}

	// 如果是组团模式
	if($piece['model']==3){
		// 获取我所在的团队人数
		$group=pdo_fetch("select * from " . tablename('gandl_wall_group') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and mine_id=:mine_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':mine_id' => $mine['id']));
		if(empty($group)){
			$this->returnError('您还没有组队，不能抢哦');
		}
		$groupnum=pdo_fetchcolumn("select COUNT(id) from " . tablename('gandl_wall_group') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and captain_id=:captain_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':captain_id' =>$group['captain_id']));
		if($groupnum<$piece['group_size']){
			$this->returnError('您团队人数不够，不能抢哦');
		}
	}


	// 可抢,根据我的顺序，按照分配方案获取我应得的金额
	$rob_index=$piece['rob_users']+1; // 我的序号（从1开始）
	$rob_plan=explode(',',$piece['rob_plan']);
	$rob_money=$rob_plan[$rob_index-1]; // 因为方案的序号是从0开始，所以此处-1
	// 以防万一验证
	if(empty($rob_money) || $rob_money<=0 || $rob_money>$piece['total_amount']){
		$this->returnError('哎呀~没抢到');
	}
	// 更新红包金额等状态 ！！！ 此处使用乐观锁方案防止钱被多抢，使用rob_users作为乐观锁条件 ！！！ 
	// 是否是最后一个
	if($rob_index>=$piece['total_num']){
		$ret1=pdo_query('UPDATE '.tablename('gandl_wall_piece') .' SET rob_amount=rob_amount+:rob_money,rob_users=rob_users+1,rob_end_time=:rob_end_time,status=2 where id=:piece_id and rob_users=:rob_users', array(':piece_id' => $piece['id'],':rob_users' => $piece['rob_users'],':rob_money'=>$rob_money,':rob_end_time'=>time()));
		if(false===$ret1 || 0==$ret1){
			$this->returnError('真可惜~没抢到');
		}
	}else{
		$ret1=pdo_query('UPDATE '.tablename('gandl_wall_piece') .' SET rob_amount=rob_amount+:rob_money,rob_users=rob_users+1 where id=:piece_id and rob_users=:rob_users', array(':piece_id' => $piece['id'],':rob_users' => $piece['rob_users'],':rob_money'=>$rob_money));
		if(false===$ret1 || 0==$ret1){
			$this->returnError('真可惜~没抢到');
		}
	}


	
	/** 旧的计算方式
	// 抢成功，更新我的账户余额,记录我的抢钱记录
	$rob_next_time=time()+ ((!empty($fan) && $fan['follow']==1)?$wall['cold_time']/2:$wall['cold_time']); // 粉丝只需等待一半的冷却时间
	// 冷却时间还需要减去我的冷却加速器时间
	$rob_next_time=$rob_next_time-$mine['rob_fast'];
	**/

	// 新的计算方式
	$mine_cold_time=($mine['follow']==1?($wall['cold_time']-$wall['task_follow']):$wall['cold_time']);
	$mine_cold_time=$mine_cold_time-($wall['task_invite']==0?0:($mine['rob_fast']>$wall['task_invite_max']?$wall['task_invite_max']:$mine['rob_fast']));
	
	$rob_next_time=time()+$mine_cold_time;

	// 管理员无冷却时间
	if($mine['admin']>0){
		$rob_next_time=0;
	}
	
	$get_money=$rob_money;
	$up_money=0;
	// 如果当前开启上级提成，并且我有上级，则需要计算上级提成
	if($wall['up_rob_fee']>0 && $mine['inviter_id']>0){
		$up_money=intval(intval($rob_money)*intval($wall['up_rob_fee'])/100);
		if($up_money>0 && $up_money<$rob_money){
			$get_money=$rob_money-$up_money;
		}else{
			$up_money=0;
		}
	}

	$ret2=pdo_query('UPDATE '.tablename('gandl_wall_user') .' SET money=money+:rob_money,rob_times=rob_times+1,rob_total=rob_total+:rob_total,rob_last_time=:rob_last_time,rob_next_time=:rob_next_time,rob_luck=rob_luck+:rob_luck where uniacid=:uniacid and wall_id=:wall_id and user_id=:user_id and id=:id', array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':user_id' => $user['uid'],':id' => $mine['id'],':rob_money'=>$get_money,':rob_total'=>$get_money,':rob_luck'=>$get_money,':rob_last_time'=>time(),':rob_next_time'=>$rob_next_time));
	if(false===$ret2){
		$this->returnError('好可惜~没抢到');
	}
	pdo_insert('gandl_wall_rob', array(
		'uniacid'=>$_W['uniacid'],
		'wall_id'=>$wall['id'],
		'piece_id'=>$piece['id'],
		'user_id'=>$user['uid'],
		'money'=>$rob_money,
		'get_money'=>$get_money,
		'up_money'=>$up_money,
		'is_luck'=>0,
		'is_shit'=>0,
		'create_time'=>time()
	));
	
	// 如果当前开启上级提成，并且我有上级，则需要记录上级提成记录
	if($wall['up_rob_fee']>0 && $mine['inviter_id']>0 && $up_money>0){
		$ret3=pdo_query('UPDATE '.tablename('gandl_wall_user') .' SET money=money+:up_money where uniacid=:uniacid and wall_id=:wall_id and id=:id', array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':id' => $mine['inviter_id'],':up_money'=>$up_money));
		if(false!==$ret3){
			pdo_insert('gandl_wall_up_rob', array(
				'uniacid'=>$_W['uniacid'],
				'wall_id'=>$wall['id'],
				'piece_id'=>$piece['id'],
				'mine_id'=>$mine['id'],
				'user_id'=>$user['uid'],
				'up_id'=>$mine['inviter_id'],
				'up_fee'=>$wall['up_rob_fee'],
				'up_money'=>$up_money,
				'rob_money'=>$rob_money,
				'create_time'=>time()
			));
		}
	}


	//TODO 如果是最后一个，需要扫描生成手气最佳和手气最差(也可以考虑暂时不做该功能，直接前台排倒序)

	$this->returnSuccess('抢到'.($rob_money/100).'元！',array(
		'rob_money'=>$rob_money,
		'up_money'=>$up_money,
		'last_num'=>$piece['total_num']-$rob_index
	));

}else if($cmd=='robs'){ // 抢到该红包的人

	$start=$_GPC['start'];// start:当前已加载记录数(按类型累计)
	if(!isset($start) || empty($start) || intval($start<=0)){
		$start=0;
	}else{
		$start=intval($start);
	}
	$limit=20;

	$reply=$_GPC['reply'];// reply:是否只显示有回复的

	if($reply==1){
		// 如果当前用户没有管理权限，则仅显示可见的回复
		$replys_filt=($mine['admin']>0 || ($wall['reply_mana']==1 && $mine['user_id']==$piece['user_id']) ?"":" and status=2 ");

		$replys = pdo_fetchall("select id,user_id,content,status from " . tablename('gandl_wall_reply') . "  where uniacid=:uniacid  and wall_id=:wall_id and piece_id=:piece_id ".$replys_filt." ORDER BY create_time DESC limit ".$start.",".$limit." ",  array(':uniacid' => $_W['uniacid'],':wall_id'=>$wall['id'],':piece_id'=>$piece['id']),'user_id');

		$more=1;
		if(empty($replys) || count($replys)<$limit){
			$more=0;
		}
		$start+=count($replys);
		
		$list=array();
		// 数据业务处理
		if(!empty($replys)){
			// 附加抢钱用户信息
			$uids=array();
			foreach($replys as $v){
				$uids[]=$v['user_id'];
			}
			$users = mc_fetch($uids, array('nickname','avatar'));
			// 附加抢钱用户和抢到钱的信息
			$list=pdo_fetchall("select id,user_id,money,is_luck,is_shit,create_time from " . tablename('gandl_wall_rob') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and user_id IN(".implode(",",$uids).") ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id']));

			for($i=0;$i<count($list);$i++){
				$u=$users[$list[$i]['user_id']];
				$u['avatar']=VP_AVATAR($u['avatar'],'s');
				$list[$i]['_user']=$u;

				$r=$replys[$list[$i]['user_id']];
				if($r){
					$list[$i]['_reply']=$r;
				}

				//$list[$i]['use_time']=time_to_text($list[$i]['use_time']);
			}
		}

		returnSuccess('',array(
			'start'=>$start,
			'more'=>$more,
			'list'=>$list,
			'now'=>time()// 下传递服务器时间用于倒计时
		));	
	
	}else{
		$list = pdo_fetchall("select id,user_id,money,is_luck,is_shit,create_time from " . tablename('gandl_wall_rob') . "  where uniacid=:uniacid  and wall_id=:wall_id and  piece_id=:piece_id ORDER BY create_time DESC limit ".$start.",".$limit." ",  array(':uniacid' => $_W['uniacid'],':wall_id'=>$wall['id'],':piece_id'=>$piece['id']));

		$more=1;
		if(empty($list) || count($list)<$limit){
			$more=0;
		}
		$start+=count($list);

		// 数据业务处理
		if(!empty($list)){
			// 附加抢钱用户信息
			$uids=array();
			foreach($list as $v){
				$uids[]=$v['user_id'];
			}
			$users = mc_fetch($uids, array('nickname','avatar'));
			// 附加抢钱用户评论信息
			// 如果当前用户没有管理权限，则仅显示可见的回复
			$replys_filt=($mine['admin']>0 || ($wall['reply_mana']==1 && $mine['user_id']==$piece['user_id']) ?"":" and status=2 ");
			$replys=pdo_fetchall("select id,user_id,content,status from " . tablename('gandl_wall_reply') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and user_id IN(".implode(",",$uids).") ".$replys_filt." ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id']),'user_id');

			for($i=0;$i<count($list);$i++){
				$u=$users[$list[$i]['user_id']];
				$u['avatar']=VP_AVATAR($u['avatar'],'s');
				$list[$i]['_user']=$u;

				$r=$replys[$list[$i]['user_id']];
				if($r){
					$list[$i]['_reply']=$r;
				}

				//$list[$i]['use_time']=time_to_text($list[$i]['use_time']);
			}
		}

		returnSuccess('',array(
			'start'=>$start,
			'more'=>$more,
			'list'=>$list,
			'now'=>time()// 下传递服务器时间用于倒计时
		));
	}

}else if($cmd=='reply'){
	if($wall['reply_on']==0){
		$this->returnError('评论功能已暂时关闭');
	}

	// 获取当前用户对当前动态的回复
	$reply=pdo_fetch("select * from " . tablename('gandl_wall_reply') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and mine_id=:mine_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':mine_id' => $mine['id']));

	$submit = $_GPC['submit'];
	if($submit=='save'){
		$content=$_GPC['content'];
		// 内容验证
		if(empty($content)){
			$this->returnError('请说点儿什么吧~');
		}
		if(text_len($content)>5000){
			$this->returnError('内容不能超过5000字哦~');
		}
		if(empty($reply)){// 新增
			$status=($wall['reply_verify']==0?2:1);
			pdo_insert('gandl_wall_reply', array(
				'uniacid'=>$_W['uniacid'],
				'wall_id'=>$wall['id'],
				'piece_id'=>$piece['id'],
				'mine_id'=>$mine['id'],
				'user_id'=>$mine['user_id'],
				'content'=>$content,
				'create_time'=>time(),
				'update_time'=>time(),
				'status'=>$status,
				'status_time'=>time()
			));
			$this->returnSuccess('评论成功！',$status);
		}else{// 修改
			// TODO 如果原评论已被审核不通过，则需要设为待审核，否则状态根据审核设置来
			$status=($wall['reply_verify']==0?2:1);
			if($reply['status']==3){
				$status=0;
			}
			pdo_query('UPDATE '.tablename('gandl_wall_reply') .' SET content=:content,update_time=:update_time,status=:status,status_time=:status_time where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and mine_id=:mine_id and id=:id', array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':mine_id' => $mine['id'],':id'=>$reply['id'],':content'=>$content,':update_time'=>time(),':status'=>$status,':status_time'=>time()));
			$this->returnSuccess('评论成功！',$status);
		}
		
	}else{
		include $this->template('piece_reply');
	}
}else if($cmd=='reply_admin'){
	// 鉴权
	if(!($mine['admin']>0) && !($wall['reply_mana']==1 && $piece['user_id']==$mine['user_id'])){
		$this->returnError('目前只允许管理员操作');
	}
	
	$reid = $_GPC['reid'];
	if(empty($reid)){
		$this->returnError('请选择要操作的内容');
	}
	$status = $_GPC['status'];
	if(!in_array($status,array(1,2,3))){
		$this->returnError('操作错误');
	}

	pdo_query('UPDATE '.tablename('gandl_wall_reply') .' SET status=:status,status_time=:status_time,op_id=:op_id,op_time=:op_time where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and id=:id', array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':id'=>$reid,':status'=>$status,':status_time'=>time(),'op_id'=>$mine['id'],'op_time'=>time()));

	$this->returnSuccess('操作成功');

}else{
	
	// 只有登录后才能进入抢红包页面
	if($is_user_infoed==0){
		$this->doMobileLogin();
	}
	

	// 记录阅读数【缓存】
	$piece_views_cache_key='gandl_wall_piece_views:'.$piece['id'];
	$piece_views_cache = cache_load($piece_views_cache_key);
	if(empty($piece_views_cache) || $piece_views_cache==0){
		$piece_views_cache=1;
	}else{
		$piece_views_cache+=1;
	}
	if($piece_views_cache>=3){ // 每阅读3次更新
		pdo_query('UPDATE '.tablename('gandl_wall_piece') .' SET views=views+:views where uniacid=:uniacid and wall_id=:wall_id and id=:piece_id', array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':views'=>$piece_views_cache));
		$piece_views_cache=0;
	}
	cache_write($piece_views_cache_key, $piece_views_cache);

	

	// 获取当前有效的红包(最多只显示50个)
	
	// 获取当前红包的主人
	load()->model('mc');
	$host=$this->vp_users($piece['user_id'], 'id,user_id,nickname,avatar,who,home');
	if(!empty($host) && !empty($host['nickname'])){
		$host['avatar']=VP_IMAGE_URL($host['avatar']);
	}else{
		$host = mc_fetch($piece['user_id'], array('nickname','avatar'));
		$host['avatar']=VP_AVATAR($host['avatar'],'s');
	}


	$piece['_user']=$host;

	// 如果红包已经开抢，需获取已抢人数和已抢金额
	$rob_cnts=null;
	if($piece['rob_start_time']<time()){
		$rob_cnts = pdo_fetch("select count(id) as cnt,sum(money) as amount from " . tablename('gandl_wall_rob') . " where uniacid=:uniacid  and wall_id=:wall_id and piece_id=:piece_id ",  array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id']));
	}


	// 如果是组团抢钱模式，需获取该用户的团队信息
	if($piece['model']==3){
		// 获取我的团队
		$group=pdo_fetch("select * from " . tablename('gandl_wall_group') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and mine_id=:mine_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':mine_id' => $mine['id']));
		$groupers=array();
		$group_result=0;
		if(!empty($group)){
			// 如果我的团队不为空，则获取我的团队成员
			$groupers=pdo_fetchall("select * from " . tablename('gandl_wall_group') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and captain_id=:captain_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':captain_id' =>$group['captain_id']));
		}else{
			// 如果我的团队为空，则获取邀请我的人，并加入其团队(前提是还有位置，如果该人还没有团队，则与我自动组成团队)
			if(!empty($this->_inviter) && $this->_inviter['id']!=$mine['id']){
				$group=pdo_fetch("select * from " . tablename('gandl_wall_group') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and mine_id=:mine_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':mine_id' => $this->_inviter['id']));
				if(empty($group)){
					// 如果邀请我的人也没有团队，则两人自动组队(邀请者为队长)
					// 获取邀请者个人信息
					$invuser = mc_fetch($this->_inviter['user_id'], array('nickname','avatar'));

					pdo_insert('gandl_wall_group', array(
						'uniacid'=>$_W['uniacid'],
						'wall_id'=>$wall['id'],
						'piece_id'=>$piece['id'],
						'captain_id'=>$this->_inviter['id'],
						'mine_id'=>$this->_inviter['id'],
						'user_id'=>$this->_inviter['user_id'],
						'nickname'=>$invuser['nickname'],
						'avatar'=>$invuser['avatar'],
						'create_time'=>time()
					));
					pdo_insert('gandl_wall_group', array(
						'uniacid'=>$_W['uniacid'],
						'wall_id'=>$wall['id'],
						'piece_id'=>$piece['id'],
						'captain_id'=>$this->_inviter['id'],
						'mine_id'=>$mine['id'],
						'user_id'=>$mine['user_id'],
						'nickname'=>$user['nickname'],
						'avatar'=>$user['avatar'],
						'create_time'=>time()
					));
					$group_result=1;//'您已加入抢钱团伙';
				}else{
					// 获取该团队人数，判断是否已满
					$groupernums=pdo_fetchcolumn("select COUNT(id) from " . tablename('gandl_wall_group') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and captain_id=:captain_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':captain_id' =>$group['captain_id']));
					if($groupernums<$piece['group_size']){
						// 团队未满，我自动加入
						pdo_insert('gandl_wall_group', array(
							'uniacid'=>$_W['uniacid'],
							'wall_id'=>$wall['id'],
							'piece_id'=>$piece['id'],
							'captain_id'=>$group['captain_id'],
							'mine_id'=>$mine['id'],
							'user_id'=>$mine['user_id'],
							'nickname'=>$user['nickname'],
							'avatar'=>$user['avatar'],
							'create_time'=>time()
						));
						$group_result=1;//'您已加入抢钱团伙';
					}else{
						// 团队已满，提示用户
						$group_result=2;//'好友的团伙满了，重新组团吧';
					}
				}
				
				// 如果加入成功
				if($group_result==1){
					// 获取我的最新团队（队长是邀请我的人）
					$groupers=pdo_fetchall("select * from " . tablename('gandl_wall_group') . " where uniacid=:uniacid and wall_id=:wall_id and piece_id=:piece_id and captain_id=:captain_id ", array(':uniacid' => $_W['uniacid'],':wall_id' => $wall['id'],':piece_id' => $piece['id'],':captain_id' =>$this->_inviter['id']));
				}
				
			}
		}
		
		// 格式化团员信息用于前台展示(如果团员为空，把“我”放到第一个)
		$gmembercnt=count($groupers);
		$gmembers=array();
		for($i=0;$i<$piece['group_size'];$i++){
			if($i>=count($groupers) || empty($groupers[$i])){
				if($i==0){
					$gmembers[]=array(
						'mine_id'=>$mine['id'],
						'user_id'=>$mine['user_id'],
						'nickname'=>$user['nickname'],
						'avatar'=>$user['avatar'],	
					);
				}else{
					$gmembers[]=-1;
				}
			}else{
				$gmembers[]=$groupers[$i];
			}
		}

	}




	// 抢钱令牌，避免重复提交
	$_SESSION['gandl_wall_rob_token'] = md5(microtime(true));
	
	// 生成我的专属分享链接
	$share_url=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('piece',array('pid'=>pencode($wall['id']),'piid'=>pencode($piece['id']),'src'=>pencode($mine['id']))), 2);

	include $this->template('piece');
}


?>