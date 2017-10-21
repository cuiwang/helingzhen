<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端小区拼车
 */
defined('IN_IA') or exit('Access Denied');

	global $_W,$_GPC;
	$title = '小区拼车';
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$member = $this->changemember();
	if($op == 'display' || $op == 'more'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = '';
		$keyword = $_GPC['keyword'];
		if ($keyword) {
			$keyword = "%{$_GPC['keyword']}%";
			$condition = " AND start_position LIKE '{$keyword}' OR end_position LIKE '{$keyword}'";
		}
		$list = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_carpool')."WHERE weid='{$_W['weid']}' AND status = 0 AND regionid='{$member['regionid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize);
		if ($op == 'more') {
			include $this->template('carindex_more');
		}

		
		include $this->template('car_index');
	}elseif($op == 'post'){
		if (!empty($_GPC['id'])) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_carpool')."WHERE id=:id",array(':id' => $_GPC['id']));
		}
		$data = array(
				'weid'           => $_W['weid'],
				'openid'         => $_W['fans']['from_user'],
				'start_position' => $_GPC['start_position'],
				'end_position'   => $_GPC['end_position'],
				'startMinute'    => $_GPC['startMinute'],
				'startSeconds'   => $_GPC['startSeconds'],
				'enable'         => 1,
				'content'        => $_GPC['content'],
				'createtime'     => TIMESTAMP,
				'license_number' => $_GPC['license_number'],
				'car_model'      => $_GPC['car_model'],
				'car_brand'		 => $_GPC['car_brand'],
				'title'          => $_GPC['title'],
				'seat'			 => $_GPC['seat'],
				'sprice'         => $_GPC['sprice'],
				'contact'		 => $_GPC['contact'],
				'mobile'		 => $_GPC['mobile'],
				'month'			 => $_GPC['month'],
				'yday'			 => $_GPC['yday'],
				'regionid'       => $member['regionid'],
		);
		if ($_W['ispost']) {
			if (empty($_GPC['id'])) {
				pdo_insert('xcommunity_carpool',$data);
			}else{
				pdo_update('xcommunity_carpool',$data,array('id' => $_GPC['id']));
			}
			message('发布成功',$this->createMobileUrl('car',array('op' => 'display')),'success');
		}

		include $this->template('caradd');
	}elseif ($op == 'detail') {
		$id = $_GPC['id'];
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_carpool')."WHERE id=:id",array(':id' => $id));
		}

		include $this->template('cardetail');
	}elseif ($op == 'my') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = '';
		$keyword = $_GPC['keyword'];
		if ($keyword) {
			$keyword = "%{$_GPC['keyword']}%";
			$condition = " AND start_position LIKE '{$keyword}' OR end_position LIKE '{$keyword}'";
		}
		$list = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_carpool')."WHERE weid='{$_W['weid']}' AND status = 0 AND regionid='{$member['regionid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize);
		if ($op == 'more') {
			include $this->template('carindex_more');
		}
		include $this->template('car_index');
		exit();
	}elseif ($op == 'delete') {
		if ($_GPC['id']) {
			if (pdo_delete('xcommunity_carpool',array('id' => $_GPC['id']))) {
				$result['state'] = 0;
				message($result, '', 'ajax');
			}
		}
	}















