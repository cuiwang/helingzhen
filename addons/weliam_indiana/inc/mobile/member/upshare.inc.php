<?php
	// 
	//  upshare.inc.php
	//  <project>
	//  晒单操作、添加讨论操作
	//  Created by Administrator on 2016-09-01.
	//  Copyright 2016 Administrator. All rights reserved.
	// 

	global $_W,$_GPC;
	$openid = m('user') -> getOpenid();
	
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'upshare';
	
	if($op == 'upshare'){
		$period_number = $_GPC['period_number'];
		$check_showprize = pdo_fetch("select id from".tablename('weliam_indiana_showprize')."where uniacid=:uniacid and period_number=:period_number",array(':uniacid'=>$_W['uniacid'],':period_number'=>$period_number));
		if(empty($check_showprize)){
			$check_period = pdo_fetch("select id from".tablename('weliam_indiana_period')."where uniacid=:uniacid and period_number=:period_number",array(':uniacid'=>$_W['uniacid'],':period_number'=>$period_number));
			if(!empty($check_period)){
				include $this->template("member/upshare");
			}else{
				message("未找到该商品",referer(),"error");
			}
		}else{
			message("不能重复晒单",referer(),"error");
		}
	}
	
	if($op == 'upspeech'){
		//上传言论
		include $this->template("member/upshare");
	}
	
	if($op == 'up_picture'){
		//图片处理流程
		load()->classs('weixin.account');
		load()->func('file');
		$access_token = WeAccount::token();

		$picture_url = $_GPC['picture_url'];
		$picture_num = $_GPC['picture_num'];
		$thumb = $_GPC['thumb'];
		$path_a = IA_ROOT.'/attachment/weliam_indiana/';
		$path_b = IA_ROOT.'/attachment/weliam_indiana/storeimage/';
		$path = IA_ROOT.'/attachment/weliam_indiana/storeimage/'.date('Y-m-d',time()).'/';
		$path_url='/attachment/weliam_indiana/storeimage/'.date('Y-m-d',time()).'/'.time().$picture_num.'.jpeg';
		$filepath = 'weliam_indiana/storeimage/'.date('Y-m-d',time()).'/'.time().$picture_num.'.jpeg';
		$url1 = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$picture_url";
		$saveinfo1 = $this->downloadWeiXinFile($url1);
		if (!file_exists($path_a)) {
			mkdir($path_a);
		}
		if (!file_exists($path_b)) {
			mkdir($path_b);
		}
		if (!file_exists($path)) {
			mkdir($path);
		}
		$result1 = $this->saveWeiXinFile(IA_ROOT.$path_url,$saveinfo1);
		if(empty($thumb)){
			$thumb = array($filepath);
		}else{
			array_push($thumb,$filepath);
		}
		$thumbs = $thumb;
		die(json_encode(array('status'=>2,'data'=>$thumbs,'msg'=>'上传成功')));	
	}

	if($op == 'save'){
		//保存上传信息
		$data['uniacid'] = $_W['uniacid'];
		$data['openid'] = $openid;
		$data['title'] = $_GPC['title'];
		$data['detail'] = $_GPC['detail'];
		$this->module['config']['auto_audit'] == 2 ? $data['status'] = 2 : $data['status'] = 1;
		$data['createtime'] = time();
		$data['thumbs'] = serialize($_GPC['thumb']);
		$type = $_GPC['type'];
		if($type == 'upshare'){
			//保存晒单
			$period = pdo_fetch("select id,goodsid from".tablename('weliam_indiana_period')."where uniacid=:uniacid and period_number=:period_number",array(':uniacid'=>$_W['uniacid'],':period_number'=>$_GPC['period_number']));
			if(!empty($period)){
				$data['period_number'] = $_GPC['period_number'];
				$data['goodsid'] = $period['goodsid'];
				$data['goodstitle'] = pdo_fetchcolumn("select title from".tablename('weliam_indiana_goodslist')."where uniacid=:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$period['goodsid']));
				if(pdo_insert('weliam_indiana_showprize',$data)){
					pdo_update("weliam_indiana_period",array('status'=>7),array('uniacid'=>$_W['uniacid'],'period_number'=>$_GPC['period_number']));
					if(empty($this->module['config']['creditBySingle']) || $this->module['config']['creditBySingle']='' || $this->module['config']['creditBySingle']<0) $this->module['config']['creditBySingle'] = 0;
					m('credit')->updateCredit1($openid,$_W['uniacid'],$this->module['config']['creditBySingle'],'晒单送积分');
					die(json_encode(array('status'=>2,'data'=>'','msg'=>'评论成功')));
				}else{
					die(json_encode(array('status'=>1,'data'=>'','msg'=>'评论失败')));
				}
			}else{
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'中奖商品不存在')));
			}
		}
		if($type == 'upspeech'){
			//保存讨论
			$data['type'] = 1;
			if(pdo_insert('weliam_indiana_showprize',$data)){
				die(json_encode(array('status'=>2,'data'=>'','msg'=>'讨论保存成功')));
			}else{
				die(json_encode(array('status'=>1,'data'=>'','msg'=>'讨论保存失败')));
			}
		}
	}