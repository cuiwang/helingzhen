<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端家政服务
 */
defined('IN_IA') or exit('Access Denied');

	global $_GPC,$_W;
	$title = '家政服务';
		$op = !empty($_GPC['op']) ? $_GPC['op']:'display' ;
		$id = $_GPC['id'];
		//查对应的小区编号
		$member = $this->changemember();
		//查家政子类 家政主类ID=1
		$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_servicecategory')."WHERE weid='{$_W['weid']}' AND parentid=1");
		if($op == 'post'){
			if(!empty($id)){
    			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_service')."WHERE id=:id",array(':id' => $id));
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
					'servicecategory'      => 1,
				);
    			if (empty($id)) {
    				pdo_insert('xcommunity_service',$data);
    				message('信息发布成功。',$this->createMobileUrl('homemaking',array('op' => 'display')),'success');
    			}else{
    				pdo_update('xcommunity_service',$data,array('id' => $id));
    				message('信息编辑成功。',$this->createMobileUrl('homemaking',array('op' => 'display')),'success');
    			}	
    		}
		}elseif ($op == 'display' || $op == 'more') {
				$pindex = max(1, intval($_GPC['page']));
				$psize  = 10;
				$list   = pdo_fetchall("select * from".tablename('xcommunity_service')."where weid = '{$_W['weid']}' and servicecategory = 1 and regionid='{$member['regionid']}' LIMIT ".($pindex - 1) * $psize.','.$psize);
				foreach ($list as $key => $value) {
					$member = pdo_fetch("SELECT mobile FROM".tablename('xcommunity_member')."WHERE openid = '{$value['openid']}'");
					$list[$key]['mobile'] = $member['mobile'];
				}
				if ($op == 'more') {
					include $this->template('homemaking_more');
					exit();
				}
		}elseif ($op == 'resolve') {
			if ($id) {
				pdo_update('xcommunity_service',array('status' =>1),array('id' => $id));
				message('家政服务信息完成。',referer(),'success');
			}	
		}elseif ($op == 'cancel') {
			if ($id) {
				pdo_update('xcommunity_service',array('status' =>2),array('id' => $id));
				message('家政服务信息取消成功。',referer(),'success');
			}
		}elseif ($op == 'my') {
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 10;
			$list   = pdo_fetchall("select * from".tablename('xcommunity_service')."where weid = '{$_W['weid']}' and openid='{$_W['fans']['from_user']}' and servicecategory = 1 and regionid='{$member['regionid']}' LIMIT ".($pindex - 1) * $psize.','.$psize);
			foreach ($list as $key => $value) {
				$member = pdo_fetch("SELECT mobile FROM".tablename('xcommunity_member')."WHERE openid = '{$value['openid']}'");
				$list[$key]['mobile'] = $member['mobile'];
			}
			include $this->template('homemaking_my');
			exit();
		}
		include $this->template('homemaking');