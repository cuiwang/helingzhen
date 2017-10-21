<?php

/**
 * 系统设置
 */
load()->func('tpl');

if ($op == 'display') {
    if (checksubmit('submit')) {
        $data = array(
            'uniacid'		=> $_W['uniacid'],
			'stock_config'	=> intval($_GPC['stock_config']),
            'isfollow'		=> intval($_GPC['isfollow']),
			'cash_follow'	=> intval($_GPC['cash_follow']),
            'category_ico'	=> $_GPC['category_ico'],
            'qrcode'		=> $_GPC['qrcode'],
			'is_invoice'	=> intval($_GPC['is_invoice']),
			'poster_type'	=> intval($_GPC['poster_type']),
            'posterbg'		=> trim($_GPC['posterbg']),
            'manageopenid'	=> trim($_GPC['manageopenid']),
            'closespace'	=> intval($_GPC['closespace']),
            'teacher_income' => intval($_GPC['teacher_income']),
            'vipdiscount'	=> intval($_GPC['vipdiscount']),
            'autogood'		=> intval($_GPC['autogood']),
            'print_error'	=> intval($_GPC['print_error']),
            'addtime'		=> time(),
        );

        if ($data['vipdiscount'] > 100) {
            message("VIP会员购买课程折扣不能超过100%");
        }
        if ($data['teacher_income'] > 100) {
            message("讲师课程收入分成不能超过100%");
        }

        if (empty($setting)) {
            pdo_insert($this->table_setting, $data);
        } else {
            unset($data['addtime']);
            pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
        }

        $this->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->全局设置", "编辑全局设置");
        message('操作成功', $this->createWebUrl('setting'), 'success');
    }
} elseif ($op == 'frontshow') {
	$lazyload = unserialize($setting['index_lazyload']);
	$self_diy = unserialize($setting['self_diy']);

	if(empty($setting['front_color'])){
		$front_color = file_get_contents(MODULE_ROOT."/template/mobile/style/common.css");
		$setting['front_color'] = str_replace("../images/ico-search.png", "../addons/fy_lesson/template/mobile/images/ico-search.png", $front_color);
	}
	
    if (checksubmit('submit')) {
		foreach ($_GPC['diy_name'] as $key => $row) {
            $diy_link = $_GPC['diy_link'][$key];
            if (!$row || !$diy_link)
                continue;
            $diy_data[] = array(
                'diy_name' => $row,
                'diy_link' => $diy_link,
            );
        }
		
        $data = array(
            'uniacid' => $_W['uniacid'],
            'sitename' => trim($_GPC['sitename']),
            'copyright' => trim($_GPC['copyright']),
            'logo' => trim($_GPC['logo']),
            'lessonshow' => $_GPC['lessonshow'] ? intval($_GPC['lessonshow']) : '1',
            'footnav' => intval($_GPC['footnav']),
            'teacherlist' => intval($_GPC['teacherlist']),
            'mustinfo' => intval($_GPC['mustinfo']),
            'mobilechange' => intval($_GPC['mobilechange']),
			'self_diy' => serialize($diy_data),
			'front_color' => $_GPC['front_color'],
            'addtime' => time(),
        );
		$data['index_lazyload'] = serialize(
			array(
				'lazyload_switch'=>intval($_GPC['lazyload_switch']),
				'lazyload_image'=>$_GPC['lazyload_image']
			)
		);

        if ($data['vipdiscount'] > 100) {
            message("VIP会员购买课程折扣不能超过100%");
        }
        if ($data['teacher_income'] > 100) {
            message("讲师课程收入分成不能超过100%");
        }

        if (empty($setting)) {
            pdo_insert($this->table_setting, $data);
        } else {
            unset($data['addtime']);
            pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
        }

        $this->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->手机端显示", "编辑手机端显示");
        message('操作成功', $this->createWebUrl('setting', array('op' => 'frontshow')), 'success');
    }
} elseif ($op == 'templatemsg') {
    if (checksubmit('submit')) {
        $data = array(
            'uniacid' => $_W['uniacid'], /* 公众号id */
            'istplnotice' => intval($_GPC['istplnotice']), /* 是否开启模版消息 */
            'buysucc' => trim($_GPC['buysucc']), /* 购买成功 */
            'pastvip' => trim($_GPC['pastvip']), /* 会员服务过期 */
            'cnotice' => trim($_GPC['cnotice']), /* 佣金提醒 */
            'newjoin' => trim($_GPC['newjoin']), /* 三级分销下级加入提醒 */
            'newlesson' => trim($_GPC['newlesson']), /* 课程通知 */
            'neworder' => trim($_GPC['neworder']), /* 提现申请通知(管理员) */
            'newcash' => trim($_GPC['newcash']), /* 订单通知(管理员) */
            'apply_teacher' => trim($_GPC['apply_teacher']), /* 申请讲师入驻审核通知(管理员) */
            'addtime' => time(),
        );

        if (empty($setting)) {
            pdo_insert($this->table_setting, $data);
        } else {
            unset($data['addtime']);
            pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
        }

        $this->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->模版消息", "编辑模版消息");
        message('操作成功', $this->createWebUrl('setting', array('op' => 'templatemsg')), 'success');
    }
} elseif ($op == 'vipservice') {
    /* 会员服务费用 */
    $vip = unserialize($setting['vipserver']);
    $commission = unserialize($setting['viporder_commission']);

    if (checksubmit('submit')) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'vipdesc' => $_GPC['vipdesc'],
            'vip_sale' => $_GPC['vip_sale'],
            'addtime' => time(),
        );

        /* 会员服务 */
        $viptime = $_GPC['vip']['time'];
        $vipmoney = $_GPC['vip']['time'];
        $vipserver = array();
        foreach ($_GPC['viptime'] as $key => $row) {
            $row = floatval($row);
            $vipmoney = floatval($_GPC['vipmoney'][$key]);
            if (!$row || !$vipmoney)
                continue;
            $vipserver[] = array(
                'viptime' => $row,
                'vipmoney' => $vipmoney,
            );
        }
        $data['vipserver'] = iserializer($vipserver);
        $data['viporder_commission'] = iserializer($_GPC['com']);

        if (empty($setting)) {
            pdo_insert($this->table_setting, $data);
        } else {
            unset($data['addtime']);
            pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
        }

        $this->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->会员服务", "编辑会员服务");
        message('操作成功', $this->createWebUrl('setting', array('op' => 'vipservice')), 'success');
    }
} elseif ($op == 'banner') {
    /* banner图 */
    $banner = unserialize($setting['banner']);
    if (checksubmit('submit')) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'banner' => serialize($_GPC['banner']),
            'addtime' => time(),
        );

        if (empty($setting)) {
            pdo_insert($this->table_setting, $data);
        } else {
            unset($data['addtime']);
            pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
        }

        $this->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->首页幻灯片", "编辑首页幻灯片");
        message('操作成功', $this->createWebUrl('setting', array('op' => 'banner')), 'success');
    }
} elseif ($op == 'adv') {
    /* avd图 */
    $adv = unserialize($setting['adv']);
    if (checksubmit('submit')) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'adv' => serialize($_GPC['adv']),
            'addtime' => time(),
        );

        if (empty($setting)) {
            pdo_insert($this->table_setting, $data);
        } else {
            unset($data['addtime']);
            pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
        }

        $this->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->课程页广告", "编辑课程页广告");
        message('操作成功', $this->createWebUrl('setting', array('op' => 'adv')), 'success');
    }
} elseif ($op == 'savetype') {
    /* 存储方式 */
    $qiniu = unserialize($setting['qiniu']);
    $qcloud = unserialize($setting['qcloud']);

    if (checksubmit('submit')) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'savetype' => intval($_GPC['savetype']), /* 存储方式 0.其他 1.七牛 2.腾讯云*/
            'addtime' => time(),
        );

        /* 七牛云存储 */
        $qiniutype = array(
			'bucket'	 => trim($_GPC['qiniu']['bucket']),
            'access_key' => trim($_GPC['qiniu']['access_key']),
            'secret_key' => trim($_GPC['qiniu']['secret_key']),
            'url' => str_replace("http://","",trim($_GPC['qiniu']['url'])),
        );
        $data['qiniu'] = serialize($qiniutype);

        /* 腾讯云存储 */
        $qcloudtype = array(
            'appid' => trim($_GPC['qcloud']['appid']),
            'bucket' => trim($_GPC['qcloud']['bucket']),
            'secretid' => trim($_GPC['qcloud']['secretid']),
            'secretkey' => trim($_GPC['qcloud']['secretkey']),
        );
        $data['qcloud'] = serialize($qcloudtype);

        if (empty($setting)) {
            pdo_insert($this->table_setting, $data);
        } else {
            unset($data['addtime']);
            pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
        }

        $this->addSysLog($_W['uid'], $_W['username'], 3, "基本设置->存储方式", "编辑存储方式");
        message('操作成功', $this->createWebUrl('setting', array('op' => 'savetype')), 'success');
    }
}


include $this->template('setting');
?>