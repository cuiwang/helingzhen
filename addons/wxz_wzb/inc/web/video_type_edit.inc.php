<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');

$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('直播id不存在',$this->createWebUrl('video_type_edit',array('rid'=>$rid)),'error');
}
$zhibo_list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));
if(empty($zhibo_list)){
	message('此直播不存在或是已经被删除',$this->createWebUrl('video_type_edit',array('rid'=>$rid)),'error');
}
$id = intval($_GPC['id']);
$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_video_type')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));

if($list){
	$list['settings'] = iunserializer($list['settings']);
}else{
	$list['player_weight'] = '1280';
	$list['player_height'] = '720';
}

if (checksubmit('submit')) {
	$data = array();
	$type = $_GPC['type'];
	$ltype = $_GPC['ltype'];
	$setting = array();
	switch($type){
		case 1:
			if($ltype==1){
				$setting['activity_id'] = $_GPC['activity_id'];
			}elseif($ltype==2){
				$setting['uu'] = $_GPC['uu'];
				$setting['vu'] = $_GPC['vu'];
				$setting['pu'] = $_GPC['pu'];
			}
			$setting['ltype'] = $ltype;
			break;
		case 3:
			$setting['daima'] = $_POST['daima'];
			$setting['ltype'] = $_GPC['dtype'];
			break;
		case 2:
			$setting['rtmp'] = $_GPC['rtmp'];
			$setting['hls'] = $_GPC['hls'];
			$setting['ltype'] = $_GPC['atype'];
			$setting['img'] = $_GPC['img'];
			break;
		case 6:
			$setting['lrtmp'] = $_GPC['lrtmp'];
			$setting['lhls'] = $_GPC['lhls'];
			$setting['lltype'] = $_GPC['lltype'];
			$setting['limg'] = $_GPC['limg'];
			break;
		case 4:
			load()->func('communication');
			$url = 'https://room.api.m.panda.tv/index.php?method=room.shareapi&roomid='.$_GPC['xmroomid'];  
			$response = ihttp_request($url); 
			$roominfo = json_decode($response['content']);
			if(!$roominfo->data->videoinfo){
				message('房间不存在或未直播',$this->createWebUrl('video_type_edit',array('rid'=>$rid)),'error');
			}
			$setting['hls'] = $roominfo->data->videoinfo->address;
			$setting['xmroomid'] = $_GPC['xmroomid'];
			break;
		case 5:
			load()->func('communication');
			$response = ihttp_request('http://h.huajiao.com/l/index?liveid='.$_GPC['hjroomid']);
			preg_match('|"sn":"(.*?)","paused|i',$response['content'],$r);
			$setting['hls'] = 'http://qh.cdn.huajiao.com/live_huajiao_v2/'.$r[1].'/index.m3u8';
			$setting['hjroomid'] = $_GPC['hjroomid'];
			break;
	}
	

	
	$data['type'] = $type;
	$data['uniacid'] = $uniacid;
	$data['rid'] = $rid;
	$data['player_weight'] = $_GPC['player_weight'];
	$data['player_height'] = $_GPC['player_height'];
	$data['settings'] = iserializer($setting);

	if(!empty($id)){
		pdo_update('wxz_wzb_live_video_type',$data,array('rid'=>$rid,'uniacid'=>$uniacid));
		message('编辑成功',$this->createWebUrl('video_type_edit',array('rid'=>$rid)),'success');
	}else{
		$data['dateline'] = time();
		pdo_insert('wxz_wzb_live_video_type',$data);
		message('新增成功',$this->createWebUrl('video_type_edit',array('rid'=>$rid)),'success');
	}
}
$setting = iunserializer($list['settings']);


include $this->template('video_type_edit');