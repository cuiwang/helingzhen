<?php 
	// 
	//  allshare.inc.php
	//  <project>
	//  中奖用户晒单、自由言论发表、单个商品晒单情况
	//  Created by Administrator on 2016-08-30.
	//  Copyright 2016 Administrator. All rights reserved.
	// 
	global $_W,$_GPC;
	
	$openid = m('user') -> getOpenid();
	
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	/******判定加载页数****/
	$page = !empty($_GPC['page'])?$_GPC['page']:0;
	$pagenum = !empty($_GPC['pagenum'])?$_GPC['pagenum']:8;
	$pagestart = $page * $pagenum;
	if($op == 'display'){
		$type = $_GPC['type'] == 'speech'?'speech':'allshare';
		$goodsid = $_GPC['goodsid'];
		include $this->template('other/allshare');
	}
	if($op == 'allshare'){
		$condition_share = !empty($_GPC['goodsid']) ? ' and goodsid='.$_GPC['goodsid'] : ' ';
		$re_share = pdo_fetchall("select * from".tablename('weliam_indiana_showprize')." where uniacid=:uniacid and type=:type and status=:status {$condition_share} order by createtime desc limit ".$pagestart.",".$pagenum,array(':uniacid'=>$_W['uniacid'],':type'=>0,':status'=>2));
		foreach($re_share as $key => $value){
			$praise = $value['praise'];
			if(empty($praise) || !is_array(unserialize($praise))){
				$praise = array();
			}else{
				$praise = unserialize($praise);
			}
			$re_share[$key]['praise'] = in_array($openid, $praise)?1:0;
			if($value['thumbs'] != 'N;'){
				$re_share[$key]['thumbs'] = unserialize($value['thumbs']);
				for($i=0;$i<sizeof($re_share[$key]['thumbs']);$i++){
					$re_share[$key]['thumbs'][$i] = $this->tomedia_s($re_share[$key]['thumbs'][$i]);
				}
			}else{
				$re_share[$key]['thumbs'] = '';
			}

			$re_share[$key]['createtime'] = date('Y-m-d H:s:i',$value['createtime']);
			$winner = pdo_fetch("select mid,avatar,nickname from".tablename('weliam_indiana_member')."where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],'openid'=>$value['openid']));
			$re_share[$key]['avatar'] = tomedia($winner['avatar']);
			$re_share[$key]['nickname'] = $winner['nickname'];
			$re_share[$key]['mid'] = $winner['mid'];
			$re_share[$key]['op'] = 'share';
			
			$re_share[$key]['goodstitle'] = pdo_fetchcolumn("select title from".tablename('weliam_indiana_goodslist')." where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$value['goodsid']));
		}
		die(json_encode(array('status'=>2,'data'=>$re_share,'msg'=>'晒单查询成功')));
	}	
	
	if($op == 'speech'){
		//言论检索
		$re_speech = pdo_fetchall("select * from".tablename('weliam_indiana_showprize')." where uniacid=:uniacid and type=:type and status=:status order by createtime desc limit ".$pagestart.",".$pagenum,array(':uniacid'=>$_W['uniacid'],':type'=>1,':status'=>2));
		foreach($re_speech as $key => $value){
			$praise = $value['praise'];
			if(empty($praise) || !is_array(unserialize($praise))){
				$praise = array();
			}else{
				$praise = unserialize($praise);
			}
			$re_speech[$key]['praise'] = in_array($openid, $praise)?1:0;
			
			$re_speech[$key]['thumbs'] = unserialize($value['thumbs']);
			for($i=0;$i<sizeof($re_speech[$key]['thumbs']);$i++){
				$re_speech[$key]['thumbs'][$i] = $this->tomedia_s($re_speech[$key]['thumbs'][$i]);
			}
			
			$re_speech[$key]['createtime'] = date('Y-m-d H:s:i',$value['createtime']);
			$winner = pdo_fetch("select mid,avatar,nickname from".tablename('weliam_indiana_member')."where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],'openid'=>$value['openid']));
			$re_speech[$key]['avatar'] = tomedia($winner['avatar']);
			$re_speech[$key]['nickname'] = $winner['nickname'];
			$re_speech[$key]['mid'] = $winner['mid'];
			$re_speech[$key]['op'] = 'speech';
		}
		die(json_encode(array('status'=>2,'data'=>$re_speech,'msg'=>'言论查询成功')));
	}
	