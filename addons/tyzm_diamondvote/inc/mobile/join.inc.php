<?php
/**
 * 钻石投票-报名
 *
 * 易/福/源/码/网 www.efwww.com
 * 易/福/源/码/网 www.efwww.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
is_weixin();

$uniacid = intval($_W['uniacid']);
$rid=intval($_GPC['rid']);
$reply = pdo_fetch("SELECT * FROM " . tablename($this->tablereply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
if(empty($reply['status'])){message("活动已禁用");}
if(!$_W['ispost']){
	if(empty($reply['upimgtype'])){
		m('domain')->randdomain($rid,1);
	}else{
		m('domain')->randdomain($rid);
	}
}

if($reply['minupimg']<1){
	$reply['minupimg']=1;
}elseif($reply['minupimg']>5){
	$reply['minupimg']=5;
}
//是否关注
//是否关注
if(($this->oauthuser['follow']!=1 && $reply['isfollow']>=2 ) || empty($this->oauthuser['openid'])){
	$nofollow=1;
}
if($reply['endtime']<time()){
	message('活动已经结束！', $this->createMobileUrl('index', array('rid' => $rid)),'error');
}

//活动未开始
if(empty($reply['status'])){
	message('活动已禁用', $this->createMobileUrl('index', array('rid' => $rid)),'error');
}

if($reply['apstarttime']> time()){
	message('未开始报名！', $this->createMobileUrl('index', array('rid' => $rid)),'error');
}elseif($reply['apendtime']<time()){
	message('已结束报名！', $this->createMobileUrl('index', array('rid' => $rid)),'error');
}

$applydata=@unserialize($reply['applydata']);


$voteuser = pdo_get($this->tablevoteuser, array('rid' => $rid, 'oauth_openid' => $this->oauthuser['oauth_openid']), array('id'));

if(!empty($voteuser)){
    message('已报名成功！', $this->createMobileUrl('view', array('rid' => $rid,'id' => $voteuser['id'])));	
}

if($_W['ispost']){
	if($reply['endtime']<time()){
		exit(json_encode(array('status' => '0', 'msg' => "活动已经结束")));
	}

	//活动未开始
	if(empty($reply['status'])){
		exit(json_encode(array('status' => '0', 'msg' => "活动已禁用")));
	}

	if($reply['apstarttime']> time()){
		exit(json_encode(array('status' => '0', 'msg' => "未开始报名！")));
	}elseif($reply['apendtime']<time()){
		exit(json_encode(array('status' => '0', 'msg' => "已结束报名！")));
	}
	//是否关注
	if($this->oauthuser['follow']!=1 && $reply['isfollow']>=2 || empty($this->oauthuser['openid'])){
		exit(json_encode(array('status' => '500', 'msg' => "没有关注")));
	}
	if(empty($voteuser)){
		if($reply['upimgtype']==1){
			// 自定义上传图片
			$img=$_GPC['picturearr'];
		}else{
			//微信默认上传图片start
			for ($x=0; $x<count($_GPC['picturearr']); $x++) {
			  $reimg=m('attachment')->doMobileMedia(array('media_id'=>$_GPC['picturearr'][$x],'type'=>'image'));
			  if(is_array($reimg)){ 
				 exit(json_encode(array('status' => '0', 'msg' => $reimg['message']."(".$reimg['errno'].")")));
			  }else{
				 $img[]=$reimg; 
			  }
			}  
			//微信默认上传
		}
		$joindata=array();
		foreach ($applydata as $row) {
			$joindata[]=array(
				'name'=>$row['infoname'],
				'val'=>$_GPC[$row['infotype']],
			);
			if($row['notnull'] && empty($_GPC[$row['infotype']])){
				exit(json_encode(array('status' => '0', 'msg' => $row['infoname']."不能为空")));
			}
		}	
		$lastid = pdo_getall($this->tablevoteuser, array('rid' => $rid, 'uniacid' => $_W['uniacid']), array('noid') , '' , 'noid DESC' , array(1));

		if($reply['ischecked']){
			$status=1;
		}else{
			$status=0;
		}
		$joininfo = array(
		    'noid'=>$lastid[0]['noid']+1,
			'rid'=>$rid,
			'uniacid'=>$_W['uniacid'], 
			'oauth_openid'=>$this->oauthuser['oauth_openid'],
			'openid'=>$this->oauthuser['openid'],
			'avatar' =>$this->oauthuser['avatar'],
			'nickname'=>$this->oauthuser['nickname'],
			'user_ip'=>$_W['clientip'],
			'name' =>$_GPC['name'],
			'introduction' =>$_GPC['introduction'],
			'img1'=>$img[0],
			'img2'=>$img[1],
			'img3'=>$img[2],
			'img4'=>$img[3],
			'img5'=>$img[4],
			'joindata'=>iserializer($joindata),
			'formatdata'=>iserializer($_GPC['picturearr']),
			'votenum'=>0,
			'giftcount'=>0,
			'status'=>$status,
			'createtime'=>time()
		);
		
		pdo_insert($this->tablevoteuser,$joininfo);
		$insertid=pdo_insertid();
		//file_put_contents(MODULE_ROOT."/data.txt",json_encode($insertid));exit;
		if($insertid){
			
			if(empty($status)){
				$uservoteurl=$_W['siteroot']."app/".$this->createMobileUrl('view', array('id' =>$insertid,'rid' => $rid));
				$content='您已成功报名【'.$reply['title'].'】活动，待审核官方审核通过后，即可开始拉票。<a href=\"'.$uservoteurl.'\">点击进入详情页面<\/a>';
			}else{
				$uservoteurl=$_W['siteroot']."app/".$this->createMobileUrl('view', array('id' =>$insertid,'rid' => $rid));
				$content='您已成功报名【'.$reply['title'].'】活动，开始拉票吧！<a href=\"'.$uservoteurl.'\">点击进入详情页面<\/a>';
			}
			m('user')->sendkfinfo($this->oauthuser['openid'],$content);	
			
			//赠送积分或其他！
			if(!empty($reply['joingive_num'])){
				m('present')->upcredit($joininfo['openid'],$reply['joingive_type'],$reply['joingive_num'],'tyzm_diamondvote');
			}
			//奖励end			
			exit(json_encode(array('status' => '1', 'errmsg' => "成功",'id'=>$insertid)));
		}else{
			exit(json_encode(array('status' => '0', 'errmsg' => "发生错误，请重试！")));
		}
	}else{
		exit(json_encode(array('status' => '1', 'errmsg' => "已报名过",'id'=>$voteuser['id'])));
	}
}
$tplappye=m('tpl')->tpl_input($applydata);
$_W['page']['sitename']="报名参加";

// if($this->oauthuser['oauth_openid']=='oH10UuDyxYsBMHKd__i614F8L4NY'){
	load()->func('tpl'); 
include $this->template('new_join');
// }else{
// 	include $this->template('join');
// }

