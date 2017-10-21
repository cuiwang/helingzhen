<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端投诉页面
 */
defined('IN_IA') or exit('Access Denied');

	global $_GPC,$_W;
	$title = '投诉服务';
	$op = !empty($_GPC['op'])?$_GPC['op']:'display';
	//查投诉子类 投诉主类ID=4
	$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_servicecategory')."WHERE weid='{$_W['weid']}' AND parentid=4");
	//查小区编号
	$member = $this->changemember();
	if($op == 'post'){
		if (checksubmit('submit')) {
			$data  = array(
				'openid'      => $_W['fans']['from_user'],
				'weid'        => $_W['weid'],
				'regionid'    => $member['regionid'],
				'type'        => 2,
				'category'    => $_GPC['category'],
				'content'     => $_GPC['content'],
				'createtime'  => $_W['timestamp'],
				'status'      => 0,
				'rank'        => 0,
				'comment'     => 0,
				'requirement' => '无',
				'resolve'     => '',
				'resolver'    => '',
				'resolvetime' => '',
				'images' => serialize($_GPC['picIds']),
				);
			//无线打印
			if($this->module['config']['print_status']){
				if (empty($this->module['config']['print_type']) || $this->module['config']['print_type'] == '2') {
					$data['print_sta'] = -1;
$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
				$msgNo = time()+1;
				$freeMessage = array(
		'memberCode'=>$this->module['config']['member_code'], 
		'msgDetail'=>
'
    '.$this->module['config']['cname'].'物业公司欢迎您投诉

内容：'.$_GPC['content'].'
-------------------------

地址：'.$member['address'].'
业主：'.$member['realname'].'
电话：'.$member['mobile'].'
时间：'.$createtime.'
',
		'deviceNo'=>$this->module['config']['deviceNo'], 
		'msgNo'=>$msgNo,
	);

	echo $this->sendFreeMessage($freeMessage);				




}

				}
			pdo_insert("xcommunity_report",$data);
			//短信提醒
			$content = $_GPC['content'];
			$mobile = $member['mobile'];
			if ($this->module['config']['report_type']) {
				$result = $this->Resms($content,$mobile);
			}
			//微信提醒
			$notice = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_wechat_notice')."WHERE regionid='{$member['regionid']}'");
			foreach ($notice as $key => $value) {
				if ($value['report_status'] == 2) {
					$openid = $value['fansopenid'];
					$url = $this->createMobileUrl('report',array('op' => 'display'));
					$template_id = $this->module['config']['report_tplid'];
					$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
					$content = array(
							'first' => array(
									'value' => '新投诉通知',
								),
							'keyword1' => array(
									'value' => $member['realname'],
								),
							'keyword2' => array(
									'value' => $member['mobile'],
								),
							'keyword3'	=> array(
									'value' => $member['address'],
								),
							'keyword4'    => array(
									'value' => $_GPC['content'],
								),
							'keyword5'    => array(
									'value' => $createtime,
								),
							'remark'    => array(
								'value' => '请尽快联系客户。',
							),	
						);
					$this->sendtpl($openid,$url,$template_id,$content);
				}
			}
			message('投诉成功,请查看"我的投诉"等待工作人员联系。',$this->createMobileUrl('report',array('op'=>'display')),'success');
		}
	}elseif ($op == 'display' ||$op=='more') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		//投诉记录查询
		//修改报修，改为管理员和自己可见
		if ($member['manage_status']) {
			$list    = pdo_fetchall("select * from ".tablename("xcommunity_report")."where weid='{$_W['weid']}' and type=2 LIMIT ".($pindex - 1) * $psize.','.$psize);

		}else{
			$list    = pdo_fetchall("select * from ".tablename("xcommunity_report")."where weid='{$_W['weid']}' and type=2  AND openid = '{$_W['fans']['from_user']}' LIMIT ".($pindex - 1) * $psize.','.$psize);

		}		
		if($op!='more'||!empty($list)){
			if ($member['manage_status']) {
				$total = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_report")."where  weid='{$_W['weid']}' and type=2 ");
			}else{
				$total = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_report")."where  weid='{$_W['weid']}' and type=2  AND openid = '{$_W['fans']['from_user']}'");

			}
			$pager = pagination($total, $pindex, $psize);
		}
		foreach ($list as $key => $value) {
			$images = unserialize($value['images']);
			if ($images) {
				$picid = implode(',', $images);
				$imgs = pdo_fetchall("SELECT * FROM".tablename('xfcommunity_images')."WHERE id in({$picid})");
				$list[$key]['img'] = $imgs;
			}

		}
		if($op=='more'){
			include $this->template('report_more');
			exit;
		}
	}elseif ($op == 'cancel') {
		//取消投诉
		$id   = $_GPC['id'];
		if ($id) {
			pdo_update("xcommunity_report",array('status' => 2),array('id'=>$id));
		}
	}elseif ($op == 'my') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		//投诉记录查询
		$list = pdo_fetchall("select * from ".tablename("xcommunity_report")."where openid='{$_W['fans']['from_user']}' and weid='{$_W['weid']}' and type=2 LIMIT ".($pindex - 1) * $psize.','.$psize);
		include $this->template('report_my');
		exit();
	}
	include $this->template('report');