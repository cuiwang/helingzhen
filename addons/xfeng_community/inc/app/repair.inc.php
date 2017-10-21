<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端报修
 */
defined('IN_IA') or exit('Access Denied');

	global $_GPC,$_W;
	$title = '报修服务';
	$op = !empty($_GPC['op'])?$_GPC['op']:'display';
	$categories = array(
			'1'=>'水暖',
			'2'=>'公共设施',
			'3'=>'电器设施',
			);
	//查小区编号
	$member = $this->changemember();
	//查报修子类 报修主类ID=3
	$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_servicecategory')."WHERE weid='{$_W['weid']}' AND parentid=3");
	if($op == 'post'){
		if ($_W['ispost']) {
			$data  = array(
				'openid'      => $_W['fans']['from_user'],
				'weid'        => $_W['weid'],
				'regionid'    => $member['regionid'],
				'type'        => 1,
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
    '.$this->module['config']['cname'].'物业公司欢迎您报修

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
			$id = pdo_insertid();
			//短信提醒
			$content = $_GPC['content'];
			$mobile = $member['mobile'];
			if ($this->module['config']['report_type']) {
				$result = $this->Resms($content,$mobile);
			}
			//微信提醒
			$notice = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_wechat_notice')."WHERE regionid='{$member['regionid']}'");
			foreach ($notice as $key => $value) {
				if ($value['repair_status'] == 2) {
					$openid = $value['fansopenid'];
					$url = $this->createMobileUrl('repair',array('op' => 'display'));
					$template_id = $this->module['config']['repair_tplid'];
					$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
					$content = array(
							'first' => array(
									'value' => '新报修通知',
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

				
			
			message('报修申请提交成功,请查看"我的报修"等待工作人员联系。',$this->createMobileUrl('repair',array('op'=>'display')),'success');
		}
	}elseif ($op == 'display'||$op=='more') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		//通过Id查出回复记录，在一起组装一个新的二维数组
		//修改报修，改为管理员和自己可见
		if ($member['manage_status']) {
			$list    = pdo_fetchall("select * from ".tablename("xcommunity_report")."where weid='{$_W['weid']}' and type=1 LIMIT ".($pindex - 1) * $psize.','.$psize);

		}else{
			$list    = pdo_fetchall("select * from ".tablename("xcommunity_report")."where weid='{$_W['weid']}' and type=1  AND openid = '{$_W['fans']['from_user']}' LIMIT ".($pindex - 1) * $psize.','.$psize);

		}
		if($op!='more'||!empty($list)){
			if ($member['manage_status']) {
				$total = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_report")."where  weid='{$_W['weid']}' and type=1 ");
			}else{
				$total = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_report")."where  weid='{$_W['weid']}' and type=1  AND openid = '{$_W['fans']['from_user']}'");

			}
			$pager = pagination($total, $pindex, $psize);
		}
		foreach ($list as $key => $value) {
			$list[$key]['reply'] = pdo_fetchall('select * from'.tablename("xcommunity_reply")."where weid=:weid AND reportid=:reportid",array(':weid'=>$_W['weid'],':reportid'=>$value['id']));
			$images = unserialize($value['images']);
			if ($images) {
				$picid = implode(',', $images);
				$imgs = pdo_fetchall("SELECT * FROM".tablename('xfcommunity_images')."WHERE id in({$picid})");
				$list[$key]['img'] = $imgs;
			}

		}
		if($op=='more'){
			include $this->template('repair_more');
			exit;
		}
	}elseif ($op == 'resolve') {
		//业主完成报修申请
		$id   = intval($_GPC['id']);
		$item = pdo_fetch("select * from".tablename("xcommunity_report")."where id=:id AND weid=:weid",array(':weid'=>$_W['weid'],':id'=>$id));
		$update = array(
			'status'  => 1,
			'rank'    => $_GPC['rank'],
			'comment' => $_GPC['comment'],
			);
		if($_W['ispost']){
			pdo_update("xcommunity_report",$update,array('id' => $id));
			message('谢谢评价',$this->createMobileUrl('repair',array('op' => 'display')));
	 	}
	}elseif ($op == 'cancel') {
		//取消报修申请
		$id = intval($_GPC['id']);
		pdo_update("xcommunity_report",array('status' => 2),array('id'=>$id));
		message('已取消');
	}elseif ($op == 'my') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		//通过Id查出回复记录，在一起组装一个新的二维数组
		$list    = pdo_fetchall("select * from ".tablename("xcommunity_report")."where weid='{$_W['weid']}' and openid='{$_W['fans']['from_user']}' and type=1 LIMIT ".($pindex - 1) * $psize.','.$psize);
		if($op!='more'||!empty($list)){
			$total = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_report")."where  weid='{$_W['weid']}' and type=1 ");
			$pager = pagination($total, $pindex, $psize);
		}
		foreach ($list as $key => $value) {
			$list[$key]['reply'] = pdo_fetchall('select * from'.tablename("xcommunity_reply")."where weid=:weid AND reportid=:reportid",array(':weid'=>$_W['weid'],':reportid'=>$value['id']));
			$images = unserialize($value['images']);
			if ($images) {
				$picid = implode(',', $images);
				$imgs = pdo_fetchall("SELECT * FROM".tablename('xfcommunity_images')."WHERE id in({$picid})");
				$list[$key]['img'] = $imgs;
			}

		}

		include $this->template('repair_my');exit();

	}	
	include $this->template('repair');

