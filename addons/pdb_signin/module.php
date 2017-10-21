<?php
/**
 * 我要签到模块定义
 * 请遵循开源协议，本模块源码允许二次修改和开发，但必须注明作者和出处，如不遵守，我们将保留追求的权利。
 * @author PHPDB
 * @url http://www.phpdb.net/
 */
defined('IN_IA') or exit('Access Denied');

class Pdb_signinModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		global $_W, $_GPC;
		if (!empty($rid)) {
			
			//读取资源的资料：
			$sql = "select * from ".tablename('pdb_signin').
					" where rid = '{$rid}' and uniacid = '{$_W['uniacid']}' limit 1";
			$item = pdo_fetch($sql);
			// $item['params'] = json_decode($item['params'],true);
			// print_r($item);exit;
			$date_time = array();
			$date_time['start'] = $item['start_time'];
			$date_time['end'] = $item['end_time'];
			
		}else{
			//默认值：
			$item = array();
			$item['times_perday'] = 0;
			$item['credit_first'] = 0;
			$item['times_total'] = 0;
			$item['credit_total'] = 0;
			$item['status'] = 1;
		}
		
		load()->func('tpl');
		include $this->template('rule');
		
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		global $_W, $_GPC;
		
		
		//添加和保存资料：
		$id = intval($_GPC['id']);
		if ($_GPC['date_time']['start']){
			$start_time = $_GPC['date_time']['start'];
		}else{
			$start_time = '';
		}
		
		if ($_GPC['date_time']['end']){
			$end_time = $_GPC['date_time']['end'];
		}else{
			$end_time = '';
		}
		
		//如果为空，设定默认值：
		
		$data = array(
			'uniacid' => intval($_W['uniacid']),
			'rid' => $rid,
			'status' => intval($_GPC['status']),
			'title' => $_GPC['title'],
			
			'times_perday' => intval($_GPC['times_perday']) ,
			'credit_pertime' => intval($_GPC['credit_pertime']) ? intval($_GPC['credit_pertime']) : 1,
			'credit_first' => intval($_GPC['credit_first']),
			'times_total' => intval($_GPC['times_total']),
			'credit_total' => intval($_GPC['credit_total']),
			'start_time' => $start_time,
			'end_time' => $end_time,
			'is_longterm' => intval($_GPC['is_longterm']),
			'notify_message' => $_GPC['notify_message'] ? $_GPC['notify_message'] : '签到成功，谢谢参与！',
			'repeat_message' => $_GPC['repeat_message']?$_GPC['repeat_message']: '您已经签到，不用重复签到，多谢参与！',
			'finished_message' => $_GPC['finished_message'] ? $_GPC['finished_message'] : '活动已经结束，谢谢您的参与！',
			'nostart_message' => $_GPC['nostart_message'] ? $_GPC['nostart_message'] : '活动还没有开始，请稍候！',
			'ad_content' => $_GPC['ad_content'],
			
			
			'create_time' => date("Y-m-d H:i:s"),
			'update_time' => date("Y-m-d H:i:s"),
		);
		// print_r($data);exit;

		if (empty($id)) {
			pdo_insert('pdb_signin', $data);
			$id = pdo_insertid();
		} else {
			unset($data['create_time']);
			pdo_update('pdb_signin', $data, array('id' => $id));
		}
		
	}

	public function ruleDeleted($rid) {
		global $_W, $_GPC;
		//删除对应的数据：
		pdo_delete('pdb_signin',array('uniacid' => intval($_W['uniacid']),'rid' => $rid));
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$this->saveSettings($dat);
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}