<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端小区活动
 */
defined('IN_IA') or exit('Access Denied');
	
	global $_GPC,$_W;
	$title = '小区活动';
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	//判断是否注册，只有注册后，才能进入
	$member = $this->changemember();
	if($op == 'display' || $op == 'more'){
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$condition = '';
		$rows = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_activity')."WHERE weid='{$_W['weid']}' order by status desc LIMIT ".($pindex - 1) * $psize.','.$psize);
		$list = array();
		foreach ($rows as $key => $value) {
			$regions = unserialize($value['regionid']);
			if (@in_array($member['regionid'], $regions)) {
				$list[$key]['title'] = $value['title'];
				$list[$key]['starttime'] = $value['starttime'];
				$list[$key]['endtime'] = $value['endtime'];
				$list[$key]['resnumber'] = $value['resnumber'];
				$list[$key]['createtime'] = $value['createtime'];
				$list[$key]['id'] = $value['id'];
				$list[$key]['picurl'] = $value['picurl'];
			}
		}
		if ($op == 'more') {
			
			include $this->template('activity_index_more');
		}

		include $this->template('activity_index');
	}elseif($op == 'detail'){
		$id = intval($_GPC['id']);
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_activity')."WHERE id=:id",array(':id' => $id));
			$enddate = strtotime($item['enddate']);
		}
		include $this->template('activity_detail');
	}elseif($op == 'res'){
		$rid = intval($_GPC['rid']);
		$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_activity')."WHERE id=:rid",array(':rid' => $rid));
		if ($_W['ispost']) {
			$data = array(
					'weid'       => $_W['weid'],
					'openid'     => $_W['fans']['from_user'], 
					'truename'   => $_GPC['truename'],
					'sex'        => $_GPC['sex'],
					'mobile'     => $_GPC['mobile'],
					'num'        => $_GPC['num'],
					'rid'        => $_GPC['rid'],
					'createtime' => TIMESTAMP,
				);
			pdo_insert('xcommunity_res',$data);
			pdo_query("UPDATE ".tablename('xcommunity_activity')." SET resnumber=resnumber+'{$_GPC['num']}' WHERE id=:rid",array(':rid' => $rid));
			message('报名成功',$this->createMobileUrl('activity',array('op' => 'display')),'success');
		}
		include $this->template('activity_res');
	}