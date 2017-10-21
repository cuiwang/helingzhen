<?php
global $_W,$_GPC;
$uniacid = $_W['uniacid'];
$rid = $_GPC['rid'];
$categorys = pdo_fetchall("SELECT * FROM ".tablename('wxz_wzb_category')." WHERE uniacid=:uniacid",array(':uniacid'=>$uniacid));
	if(empty($categorys)){
		message('请先添加分类',$this->createWebUrl('category_list'),'error');
	}
	load()->func('tpl');
	$rid = intval($_GPC['rid']);
	$list = pdo_fetch("SELECT * FROM ".tablename('wxz_wzb_live_setting')." WHERE uniacid=:uniacid AND rid=:rid",array(':uniacid'=>$uniacid,':rid'=>$rid));

	if (checksubmit('submit')) {
		$data = array();
		$data['uniacid'] = $uniacid;
		$data['img'] = $_GPC['img'];
		$data['title'] = $_GPC['title'];
		$data['rule'] = $_GPC['rule'];

		$data['logo'] = $_GPC['live_logo'];
		$data['theme'] = $_GPC['theme'];
		
		$data['isshow'] = intval($_GPC['isshow']);
		$data['cid'] = intval($_GPC['categoryid']);
		
		//$data['player_height'] = intval($_GPC['player_height']);
		$data['cid'] = intval($_GPC['cid']);

		if($data['cid']=='0'){
			message('请选择直播分类');
		}
		
		$data['start_at'] = strtotime($_GPC['activity']['start']);
		$data['end_at'] = strtotime($_GPC['activity']['end']);
		$data['sort'] = intval($_GPC['sort']);
		
		$data['desc'] = $_GPC['desc'];
		$data['is_auth'] = $_GPC['is_auth'];
		$data['base_num'] = $_GPC['base_num'];
		$data['limit'] = $_GPC['limit'];
		$data['password'] = $_GPC['password'];
		$data['num_float'] = $_GPC['num_float'];
		$data['float_type'] = $_GPC['float_type'];
		$data['reward'] = $_GPC['reward'];
		$data['delayed'] = $_GPC['delayed'];
		$data['amount'] = $_GPC['amount'];
		$data['bgcolor'] =$_GPC['bgcolor'];
		$data['color'] =$_GPC['color'];
		$data['timecolor'] =$_GPC['timecolor'];
		$data['button_show'] =$_GPC['button_show'];
		$data['istruenum'] =$_GPC['istruenum'];
		if(!empty($rid)){
			pdo_update('wxz_wzb_live_setting',$data,array('rid'=>$rid,'uniacid'=>$uniacid));
			message('编辑成功',referer(),'success');
		}else{
			$data['dateline'] = time();
			pdo_insert('wxz_wzb_live_setting',$data);
			message('新增成功',$this->createWebUrl('livesetting'),'success');
		}

	}
include $this->template('live_setting');
?>