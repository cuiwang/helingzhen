<?php
/**
 * @author 3354988381
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class q_3354988381_notifyModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		/**
		 * 此处分为两种情况，新增规则或是修改规则。
		 * 如果rid不为0，则需要查询出此规则对应的回复数据。
		 */
		if (!empty($rid)) {
			$item = pdo_fetch("SELECT * FROM ".tablename('q_3354988381_notify_reply')." WHERE rid = :rid", array(':rid' => $rid));
		}
		// 调用模板页面
		include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_GPC;
		//此处服务端验证表单数据的完整性，直接返回错误信息。
		if (empty($_GPC['content'])) {
			return '请填写回复内容';
		}
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W, $_GPC;
		/*
		 * 此处各种验证通过后，需要进行入库操作。
		 * 入库时需要注意，此处数据可能为更新操作也可能为新增数据。
		 */
		$data = array(
			'rid' => $rid,
			'content' => $_GPC['content'],
		);
		$id = pdo_fetchcolumn("SELECT id FROM ".tablename('q_3354988381_notify_reply')." WHERE rid = :rid", array(':rid' => $rid));
		if (empty($id)) {
			pdo_insert('q_3354988381_notify_reply', $data);
		} else {
			pdo_update('q_3354988381_notify_reply', $data, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		/*
		 * 此处可能需要一些权限及数据方面的判断
		 * 除了表数据可能还需要删除一些附带的图片等资源
		 */
		pdo_delete('q_3354988381_notify_reply', array('rid' => $rid));
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
                if ($this->module['config']['notify_auth_key'] == '') {
                    $notify_auth_key = md5($_W['siteroot'] . time() . rand(0, 10000));
                    $notify_auth_key = substr($notify_auth_key, 16, 16);
                    $this->module['config']['notify_auth_key'] = $notify_auth_key;
                }                
                
                if (checksubmit()) {
                    $dat = array(
                        'user_auto_check' => ($_GPC['user_auto_check'] ? $_GPC['user_auto_check'] : 'N'),
                        'rec_msg_only_auth_user' => ($_GPC['rec_msg_only_auth_user'] ? $_GPC['rec_msg_only_auth_user'] : 'N'),
                        'notify_auth_key' => ($_GPC['notify_auth_key'] ? $_GPC['notify_auth_key'] : $notify_auth_key),
                    );
                    $this->saveSettings($dat);
                    message('配置参数更新成功！', referer(), 'success');
                } 
                if (!$this->module['config']['user_auto_check']) {
                    $this->module['config']['user_auto_check'] = 'N';
                }   
                if (!$this->module['config']['rec_msg_only_auth_user']) {
                    $this->module['config']['rec_msg_only_auth_user'] = 'N';
                }      
                $api_url = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&m=q_3354988381_notify&do=notifyapi";
                
		//这里来展示设置项表单
		include $this->template('settings');
	}
        

}