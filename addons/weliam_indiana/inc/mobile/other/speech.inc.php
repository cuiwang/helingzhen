<?php
	// 
	//  speech.inc.php
	//  <project>
	//  评论，言论
	//  Created by Administrator on 2016-08-29.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	global $_W,$_GPC;
	
	$op = !empty($_GPC['op'])?$_GPC['op']:'ill';
	$openid = m('user') -> getOpenid();
	
	if($op == 'ill'){
		die(json_encode(array('status'=>1,'data'=>'','msg'=>'非法请求')));
	}
	
	if($op == 'zan'){
		$id = $_GPC['id'];
		if(empty($id)){
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'参数错误')));
		}
		$re = pdo_fetch("select * from".tablename('weliam_indiana_showprize')."where uniacid = :uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		if(empty($re['praise']) || !is_array(unserialize($re['praise']))){
			$praise = array();
		}else{
			$praise = unserialize($re['praise']);
		}

		if(in_array($openid, $praise)){
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'该用户已经点过赞')));
		}else{
			array_push($praise,$openid);
			$count = $re['count']+1;
			pdo_update('weliam_indiana_showprize',array('praise'=>serialize($praise),'count'=>$count),array('uniacid'=>$_W['uniacid'],'id'=>$id));
			die(json_encode(array('status'=>2,'data'=>$count,'msg'=>'赞成功')));
		}
	}

	if($op == 'commont'){
		//发表评论
		$id = $_GPC['id'];
		if(empty($id)){
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'参数错误')));
		}
		
		$commont = $_GPC['commont'];
		if(empty($commont)){
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'未填写评论')));
		}	
		
		$nickname = pdo_fetchcolumn("select nickname from".tablename('weliam_indiana_member')." where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		
		$data['openid'] = $openid;
		$data['nickname'] = $nickname;
		$data['content'] = $commont;
		$data['speechid'] = $id;
		$data['createtime'] = time();
		$data['status'] = 2;
		
		$re_in = pdo_insert('weliam_indiana_discuss',$data);
		if($re_in){
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'评论成功')));
		}else{
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'评论失败')));
		}
	}

	if($op == 'speech'){
		//发表言论
		if(checksubmit()){
			$speech_count = pdo_fetchcolumn('select count(id) from'.tablename('weliam_indiana_showprize')." where uniacid=:uniacid and openid=:openid and type=:type",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':type'=>2));
			$max_count = $this->module['config']['speech_count'];
			if($speech_count < $max_count){
				$data['uniacid'] = $_W['uniacid'];
				$data['openid'] = $openid;
				$data['title'] = $_GPC['title'];
				$data['detail'] = $_GPC['detail'];
				$data['type'] = 1;
				
				$re_in = pdo_insert('weliam_indiana_showprize',$data);
				if($re_in){
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'评论成功')));
				}else{
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'评论失败')));
				}
			}else{
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'超过上限')));
			}
		}else{
			include $this->template('other/speech');		
		}
	}
	
