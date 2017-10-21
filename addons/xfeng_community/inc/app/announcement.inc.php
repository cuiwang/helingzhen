<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 微信端公告页面
 */
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;			
	$title  = '小区公告';
	$op = !empty($_GPC['op'])?$_GPC['op']:'display';
	$id = intval($_GPC['id']);
	if($op == 'display' || $op == 'more'){
		//是否是管理员操作
		$member = $this->changemember();
		//显示公告列表  status 1禁用，2启用
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		//如果是管理员，显示所有公告，否则显示启用的公告
		$condition = '';
		if (!$member['manage_status']) {
			$condition = "and status = 2";
		}
		$sql = "select * from ".tablename("xcommunity_announcement")."where weid='{$_W['weid']}' $condition and regionid='{$member['regionid']}' order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list  = pdo_fetchall($sql);
		if ($op == 'more') {
			include $this->template('announcement_more');exit();
		}
	}elseif($op =='detail'){
		$item  = pdo_fetch("select * from ".tablename("xcommunity_announcement")."where weid='{$_W['weid']}' and id =:id",array(':id' => $id));	
	}elseif ($op == 'delete') {
		pdo_delete("xcommunity_announcement",array('id' => $id ,'weid' => $_W['weid']));
		message('删除成功',referer(),'success');
	}elseif ($op == 'update') {
		//添加更新公告
		if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_announcement')."WHERE id=:id",array(':id' => $id));
		}
		//查小区编号
		$member = $this->changemember();
		$data = array(
					'weid'       => $_W['uniacid'],
					'regionid'   =>$member['regionid'],
					'title'      =>$_GPC['title'],
					'createtime' =>$_W['timestamp'],
					'status'     =>$_GPC['status'],
					'enable'     =>$_GPC['enable'],
					'datetime'   =>$_GPC['datetime'],
					'location'   =>$_GPC['location'],
					'reason'     =>$_GPC['reason'],
					'remark'     =>$_GPC['remark'],
				);
		if(checksubmit('submit')){
			if (empty($id)) {
				pdo_insert("xcommunity_announcement",$data);
				$id = pdo_insertid();
			}else{
	    		pdo_update("xcommunity_announcement",$data,array('id' => $id,'weid' => $_W['weid'] ));
			}
			//是否启用模板消息
			if ($_GPC['status'] == 2) {

				load()->classs('weixin.account');
				load()->func('communication');
				$obj = new WeiXinAccount();
				$access_token = $obj->fetch_available_token();
				$templates =pdo_fetch("SELECT * FROM".tablename('xcommunity_notice_setting')."WHERE uniacid='{$_W['uniacid']}'");
				$key = 'template_id_'.$_GPC['enable'];
				$template_id = $templates[$key];
				$openids = pdo_fetchall("SELECT openid FROM".tablename('xcommunity_member')."WHERE weid='{$_W['uniacid']}' AND regionid='{$member['regionid']}'");
				$url = $_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&id={$id}&op=detail&do=announcement&m=xfeng_community";
				foreach ($openids as $key => $value) {
					$data = array(
							'touser' => $value['openid'],
							'template_id' => $template_id,
							'url' => $url,
							'topcolor' => "#FF0000",
							'data' => array(
									'first' => array(
											'value' => $_GPC['title'],
										),
									'time' => array(
											'value' => $_GPC['datetime'],
										),
									'location'	=> array(
											'value' => $_GPC['location'],
										),
									'reason'    => array(
											'value' => $_GPC['reason'],
										),
									'remark'    => array(
											'value' => $_GPC['remark'],
										),	
								)
						);
					$json = json_encode($data);
					$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
					$ret = ihttp_post($url,$json);
				}
			}
			message('提交成功',$this->createMobileUrl('announcement',array('op' => 'display' )),'success');

		}
	}elseif($op == 'verify'){
		//公告状态
		$status = $_GPC['status'];
		pdo_query("update".tablename("xcommunity_announcement")." set status='{$status}' where id =:id and weid=:weid",array(':id' => $id,':weid' => $_W['weid']));
		message('操作成功',referer(),'success');
	}
	include $this->template('announcement');