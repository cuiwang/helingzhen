<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端二手交易
 */
defined('IN_IA') or exit('Access Denied');

	global $_W,$_GPC;
	$title = '二手市场';
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$member = $this->changemember();
	if ($op == 'display' || $op == 'more') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$keyword = "%{$_GPC['keyword']}%";
			$condition = " AND title LIKE '{$keyword}'";
		}
		$list = pdo_fetchAll('SELECT * FROM'.tablename('xcommunity_fled')."WHERE weid='{$_W['weid']}' AND status = 0 AND regionid='{$member['regionid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize);
		foreach ($list as $key => $value) {
			if ($value['images']) {
				$images = unserialize($value['images']);
				if ($images) {
					$picid  = implode(',', $images);
					$imgs   = pdo_fetchall("SELECT * FROM".tablename('xfcommunity_images')."WHERE id in({$picid})");
				}
				$list[$key]['img'] = $imgs;
			}
			if ($value['regionid']) {
				$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id='{$value['regionid']}'");
				$list[$key]['regionname'] = $region['title'];
			}
		}

		if ($op == 'more') {
			include $this->template('fledindex_more');
			exit();
		}
		include $this->template('fledindex');
	}elseif ($op == 'post') {
		if (!empty($_GPC['id'])) {
			$good = pdo_fetch("SELECT * FROM".tablename('xcommunity_fled')."WHERE id=:id",array(':id' => $_GPC['id']));
		}
		$data = array(
				'weid'        => $_W['weid'],
				'openid'      => $_W['fans']['from_user'],
				'rolex'       => $_GPC['rolex'],
				'title'       => $_GPC['title'],
				'category'    => $_GPC['category'],
				'yprice'      => $_GPC['yprice'],
				'zprice'      => $_GPC['zprice'],
				'description' => $_GPC['description'],
				'realname'    => $_GPC['realname'],
				'mobile'      => $_GPC['mobile'],
				'createtime'  => TIMESTAMP,
				'regionid'    => $member['regionid'],
			);
		if ($_GPC['picIds']) {
			$data['images'] = serialize($_GPC['picIds']);
		}
		if ($_W['ispost']) {
			if (empty($_GPC['id'])) {
				pdo_insert('xcommunity_fled',$data);
			}else{
				pdo_update('xcommunity_fled',$data,array('id' => $_GPC['id']));
			}
			message('发布成功',$this->createMobileUrl('fled',array('op' => 'display')),'success');
		}

		include $this->template('fled_add');
	}elseif ($op == 'detail') {
		$id = intval($_GPC['id']);
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_fled')."WHERE id=:id",array(':id' => $id));
			if ($item['images']) {
				$images = unserialize($item['images']);
				if ($images) {
					$picid  = implode(',', $images);
					$imgs   = pdo_fetchall("SELECT * FROM".tablename('xfcommunity_images')."WHERE id in({$picid})");
				}
				
			}
			
		}
		
		include $this->template('fled_detail');
	}elseif($op == 'my'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$keyword = "%{$_GPC['keyword']}%";
			$condition = " AND title LIKE '{$keyword}'";
		}
		$list = pdo_fetchAll('SELECT * FROM'.tablename('xcommunity_fled')."WHERE weid='{$_W['weid']}' AND status = 0 AND regionid='{$member['regionid']}' AND openid='{$_W['fans']['from_user']}' LIMIT ".($pindex - 1) * $psize.','.$psize);
		foreach ($list as $key => $value) {
			if ($value['images']) {
				$images = unserialize($value['images']);
				if ($images) {
					$picid  = implode(',', $images);
					$imgs   = pdo_fetchall("SELECT * FROM".tablename('xfcommunity_images')."WHERE id in({$picid})");
				}
				$list[$key]['img'] = $imgs;
			}
			if ($value['regionid']) {
				$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id='{$value['regionid']}'");
				$list[$key]['regionname'] = $region['title'];
			}
		}
		include $this->template('fledindex');
	}elseif ($op == 'delete') {
		$id = $_GPC['id'];
		if (pdo_delete('xcommunity_fled',array('id'=>$id))) {
			$result['state'] = 0;
			message($result, '', 'ajax');
		}
	}
