<?php
/**
 * [WeEngine System] Copyright (c) 2014 WeiZan.Com
 
 */
defined('IN_IA') or exit('Access Denied');
load()->model('cloud');
load()->func('cron');
$_W['page']['title'] = '计划任务 - 公众号选项';
$dos = array('list', 'post', 'del', 'run', 'status', 'sync');
$do = in_array($do, $dos) ? $do : 'list';
if($do == 'sync') {
	$id = intval($_GPC['id']);
	$data = pdo_get('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if(empty($data)) {
		message('任务不存在或已经删除', referer(), 'error');
	}
	$result = cloud_cron_get($data['cloudid']);
	$cron = json_decode(base64_decode($result),true);
	if(!is_array($cron)) {
		message('从云服务同步数据出错', referer(), 'error');
	}
	$cron['id'] = $data['id'];
	pdo_update('core_cron', $cron, array('uniacid' => $_W['uniacid'], 'id' => $id));
	message('同步计划任务成功', referer(), 'success');
}

if($do == 'post') {
	$id = intval($_GPC['id']);
	if(!empty($id)) {
		$cron = pdo_get('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $id));
		$cron['url']=base64_decode($cron['url']);
		if(empty($cron)) {
			message('任务不存在或已经删除', referer(), 'error');
		}

	} else {
		$cron = array('weekday' => -1, 'day' => -1, 'hour' => -1, 'type' => 1, 'lastruntime' => TIMESTAMP + 86400, 'minute' => rand(1, 59));
	}

	if(checksubmit('form')) {
		$data=array();
		$data['name'] = trim($_GPC['name']) ? trim($_GPC['name']) : message('请填写任务名称', '', 'error');
		if(empty($_GPC['url'])){
			message('请填写任务脚本文件名称', '', 'error');
		}
		$data['url'] = trim($_GPC['url']);
		$data['url'] =base64_encode($data['url']);
		$data['status'] = intval($_GPC['status']);
		$data['open'] = intval($_GPC['open']);
		if($data['status'] == 1) {
			$data['type']='ds';
			$data['value']=strtotime($_GPC['executetime']);
			if($data['value'] <= TIMESTAMP + 3600) {
				message('定时任务的执行时间不能早于当前时间', '', 'error');
			}
		}
		else{
			if($_GPC['day'] != -1){
				$data['type']='j';
				$data['value']=intval($_GPC['day']);
			}elseif($_GPC['weekday'] != -1){
				$data['type']='w';
				$data['value']=intval($_GPC['weekday']);
			}elseif($_GPC['hour'] != -1){
				$data['type']='G';
				$data['value']=intval($_GPC['hour']);
			}elseif($_GPC['min'] != -1){
				$data['type']='i';
				$data['value']=intval($_GPC['min']);
			}else{
				$data['type']='jg';
				$data['value']=intval($_GPC['jiange']);
			}
		}
		if($id > 0) {
			$data['id'] = $cron['cloudid'];
			$status = cloud_cron_update($data);
			if($status!='SUCCESS') {
				message('更新计划任务失败！', '', 'error');
			}
			$data['id'] = $id;
			pdo_update('core_cron', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
			message('编辑计划任务成功', url('cron/display/list'), 'success');
		} else {
			$status = cloud_cron_update($data);
			if(!is_numeric($status)) {
				message('连接云平台错误，创建计划任务失败', '', 'error');
			}
			$data['cloudid'] = intval($status);
			$data['uniacid'] = $_W['uniacid'];
			pdo_insert('core_cron', $data);
			message('添加计划任务成功', url('cron/display/list'), 'success');
		}
	}
}

if($do == 'list') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('core_cron') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
	$crons = pdo_fetchall('SELECT * FROM ' . tablename('core_cron') . ' WHERE uniacid = :uniacid ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ", {$psize}", array(':uniacid' => $_W['uniacid']));
	$pager = pagination($total, $pindex, $psize);
	$weekday_cn = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
	if(!empty($crons)) {
		foreach($crons as &$cron) {
			$cn = '';
			if($cron['type'] =='j') {
				$cn = '每月' . $cron['value'] . '日';
			} elseif($cron['type'] == 'w') {
				$cn = '每' . $weekday_cn[$cron['value']];
			} elseif($cron['type'] =='G') {
				$cn = '每天'.$cron['value'].'时';
			} elseif($cron['type'] =='ds') {
				$cn = date('y-m-d H:i',$cron['value']);
			}elseif($cron['type'] =='i') {
				$cn = '每时'.$cron['value'].'分';
			}elseif($cron['type'] =='jg') {
				$cn = '每隔'.$cron['value'].'分钟';
			}
			$cron['cn'] = $cn;
		}
	}
}
if($do == 'del') {
	$ids = $_GPC['id'];
	if(!is_array($ids)) {
		$ids = array($ids);
	}
	if(!empty($ids)) {
		foreach($ids as $id) {
			$id = intval($id);
			if($id > 0) {
				$cron = pdo_get('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $id));
				if(!empty($cron)) {
					$result = cloud_cron_remove($cron['cloudid']);
					if($result=='SUCCESS') {
						pdo_delete('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $id));
					} else {
						message("删除{$cron['name']}失败", url('cron/display/list'), 'error');
					}
				}
			}
		}
		message('删除计划任务成功', url('cron/display/list'), 'success');
	} else {
		message('没有选择要删除的任务', referer(), 'error');
	}
}

if($do == 'run') {
	$id = intval($_GPC['id']);
	$cron=pdo_get('core_cron',array('uniacid' => $_W['uniacid'],'id'=>$id));
	$url=base64_decode($cron['url']);
	load()->func('communication');
	ihttp_request($url, '',array(), 1);
	message('执行任务成功', referer(), 'success');
}

if($do == 'status') {
	$id = intval($_GPC['id']);
	$status = intval($_GPC['open']);
	if(!in_array($status, array(0, 1))) {
		exit('状态码错误');
	}
	$cron = pdo_get('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if(empty($cron)) {
		exit('任务不存在或已删除');
	}
	$result = cloud_cron_change_status($cron['cloudid'], $status);
	if($result!='SUCCESS') {
		exit('修改任务状态失败！');
	}
	pdo_update('core_cron', array('open' => $status), array('uniacid' => $_W['uniacid'], 'id' => $id));
	exit('success');
}
template('cron/display');

