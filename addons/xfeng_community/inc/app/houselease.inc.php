<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端房屋租赁
 */
defined('IN_IA') or exit('Access Denied');

	global $_W,$_GPC;
	$title = '房屋租赁';
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$id = intval($_GPC['id']);
	//查类型
	//查租赁子类 租赁主类ID=2
		$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_servicecategory')."WHERE weid='{$_W['weid']}' AND parentid=2");
	//查对应的小区编号
	$member = $this->changemember();	
	if($op == 'post'){
		if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_service')."WHERE id=:id",array(':id'=>'$id'));
		}
		if($_W['ispost']){
			$data = array(
				'weid'                 => $_W['weid'],
				'openid'               => $_W['fans']['from_user'],
				'regionid'             => $member['regionid'],
				'servicesmallcategory' => $_GPC['servicesmallcategory'],
				'contacttype'          => $_GPC['contacttype'],
				'requirement'          => $_GPC['requirement'],
				'remark'               => $_GPC['remark'],
				'createtime'           => $_W['timestamp'],
				'status'               => 0,
				'servicecategory'      => 2,
				'images'			   => serialize($_GPC['picIds']),
			);
			if (empty($id)) {
				pdo_insert('xcommunity_service',$data);
				message('信息发布成功。',$this->createMobileUrl('houselease',array('op' => 'display')),'success');
			}else{
				pdo_update('xcommunity_service',$data,array('id' => $id));
				message('信息编辑成功。',$this->createMobileUrl('houselease',array('op' => 'display')),'success');
			}   			
		}
	}elseif ($op == 'display' || $op == 'more') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$sql  = "select * from".tablename('xcommunity_service')."where weid = '{$_W['weid']}' and servicecategory = 2 and regionid=".$member['regionid']." LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list = pdo_fetchall($sql);
			foreach ($list as $key => $value) {
				$member = pdo_fetch("SELECT mobile FROM".tablename('xcommunity_member')."WHERE openid = '{$value['openid']}'");
				$images = unserialize($value['images']);
				if ($images) {
					$picid = implode(',', $images);
					$imgs = pdo_fetchall("SELECT * FROM".tablename('xfcommunity_images')."WHERE id in({$picid})");
					$list[$key]['img'] = $imgs;
					$list[$key]['mobile'] = $member['mobile'];
				}
				
			}
			if ($op == 'more') {
				include $this->template('houselease_more');
				exit();
			}
	}elseif ($op == 'resolve') {
		pdo_update('xcommunity_service',array('status' => 1),array('id' => $id));
		message('房屋租赁信息完成。',referer(),'success');
	}elseif ($op == 'cancel') {
		pdo_update('xcommunity_service',array('status' => 2),array('id' => $id));
		message('房屋租赁信息取消成功。',referer(),'success');
	}elseif ($op == 'my') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$sql  = "select * from".tablename('xcommunity_service')."where weid = '{$_W['weid']}' and openid='{$_W['fans']['from_user']}' and servicecategory = 2 and regionid=".$member['regionid']." LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list = pdo_fetchall($sql);
		foreach ($list as $key => $value) {
			$member = pdo_fetch("SELECT mobile FROM".tablename('xcommunity_member')."WHERE openid = '{$value['openid']}'");
			$images = unserialize($value['images']);
			if ($images) {
				$picid = implode(',', $images);
				$imgs = pdo_fetchall("SELECT * FROM".tablename('xfcommunity_images')."WHERE id in({$picid})");
				$list[$key]['img'] = $imgs;
				$list[$key]['mobile'] = $member['mobile'];
			}
			
		}
		include $this->template('houselease_my');
		exit();
	}
		include $this->template('houselease');