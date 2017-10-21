<?php 
	// 
	//  share_detail.inc.php
	//  <project>
	//  晒单详情，评价评论，讨论详情，讨论评价
	//  Created by Administrator on 2016-08-31.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	
	global $_W,$_GPC;
	$openid = m('user') -> getOpenid();
	
	$op = !empty($_GPC['op'])?$_GPC['op']:'ill';
	$id = $_GPC['id'];
	if($op == 'ill' || empty($id)){
		die(json_encode(array('status'=>-1,'data'=>'','msg'=>'参数错误')));
	}
	
	if($op == 'share' || $op == 'speech'){
		//晒单详情
		$re_sel = pdo_fetch("select * from".tablename('weliam_indiana_showprize')." where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		$re_sel['createtime'] = date('Y-m-d H:s:i',$re_sel['createtime']);
		$re_sel['thumbs'] = unserialize($re_sel['thumbs']);
		for($i=0;$i<sizeof($re_sel['thumbs']);$i++){
			$re_sel['thumbs'][$i] = $this->tomedia_s($re_sel['thumbs'][$i]);
		}
		/******是否已赞*****/
		$praise = $re_sel['praise'];
		if(empty($praise) || !is_array(unserialize($praise))){
			$praise = array();
		}else{
			$praise = unserialize($praise);
		}
		$re_sel['praise'] = in_array($openid, $praise)?1:0;
		/******中奖信息******/
		if($op == 'share'){
			$period = pdo_fetch('select id,openid,periods,nickname,avatar,code,partakes,endtime from'.tablename('weliam_indiana_period')."where uniacid=:uniacid and goodsid=:goodsid and period_number=:period_number",array(':uniacid'=>$_W['uniacid'],':goodsid'=>$re_sel['goodsid'],':period_number'=>$re_sel['period_number']));
			$period['avatar'] = tomedia($period['avatar']);
			$period['endtime'] = date('Y-m-d H:s:i',$period['endtime']);
			$goods = pdo_fetch("select title,id,picarr,price from".tablename('weliam_indiana_goodslist')." where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$re_sel['goodsid']));
			$goods['periods'] = $period['periods'];
			$goods['picarr'] = tomedia($goods['picarr']);
			$goods['periodid'] = $period['id'];
		}
		
		if($op == 'speech'){
			$member = pdo_fetch("select mid,nickname,avatar from".tablename('weliam_indiana_member')." where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$re_sel['openid']));
			$re_sel['nickname'] = $member['nickname'];
			$re_sel['avatar'] = tomedia($member['avatar']);
		}
		include $this->template('other/share_detail');
	}
	
	if($op == 'comment'){
		//评论
		/******判定加载页数****/
		$page = !empty($_GPC['page'])?$_GPC['page']:0;
		$pagenum = !empty($_GPC['pagenum'])?$_GPC['pagenum']:5;
		$pagestart = $page * $pagenum;
		
		$comment = pdo_fetchall("select * from".tablename('weliam_indiana_discuss')." where  uniacid=:uniacid and parentid=:parentid order by createtime desc limit ".$pagestart.",".$pagenum,array(':uniacid'=>$_W['uniacid'],':parentid'=>$id));
		foreach($comment as $key => $value){
			$member = pdo_fetch("select nickname,avatar,openid from".tablename('weliam_indiana_member')."where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$value['openid']));
			$comment[$key]['avatar'] = tomedia($member['avatar']);
			$comment[$key]['nickname'] = $member['nickname'];
			$comment[$key]['createtime'] = date('Y-m-d H:s:i',$value['createtime']);
		}
		die(json_encode(array('status'=>2,'data'=>$comment,'msg'=>'评价查询成功')));
	}
	
	if($op == 'add_comment'){
		//添加评论
		$data['content'] = $_GPC['content'];
		$data['parentid'] = $_GPC['id'];
		$data['status'] = 2;
		$data['createtime'] = time();
		$data['uniacid'] = $_W['uniacid'];
		$data['openid'] = $openid;
		
		if(empty($data['content']) || empty($data['parentid']) || empty($data['openid'])){
			die(json_encode(array('status'=>1,'data'=>'','msg'=>'评价不能为空，或者参数不正确')));
		}
		
		if(pdo_insert('weliam_indiana_discuss',$data)){
			$count = pdo_fetchcolumn("select speechcount from".tablename('weliam_indiana_showprize')." where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['id']));
			pdo_update('weliam_indiana_showprize',array('speechcount'=>$count+1),array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
			die(json_encode(array('status'=>2,'data'=>$comment,'msg'=>'评价成功')));
		}else{
			die(json_encode(array('status'=>1,'data'=>$comment,'msg'=>'评价失败')));
		}
	}
	