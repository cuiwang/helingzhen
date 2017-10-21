<?php
/**
 * 充值卡模块定义
 *
 * @author mcoder
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Mcoder_rechargeModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$cfg = array(                
                    'web_skip_url' => $_GPC['web_skip_url'],
                    'web_skip_text' => $_GPC['web_skip_text'],
					'web_issecret_skip_url' => $_GPC['web_issecret_skip_url'],
					'web_issecret_skip_text' => $_GPC['web_issecret_skip_text'],
					'qr_skip_url' => $_GPC['qr_skip_url'],
					'qr_skip_text' => $_GPC['qr_skip_text'],
					'is_need_follow' => $_GPC['is_need_follow'],
					
                );
			$this->saveSettings($cfg);
			message('保存成功', 'refresh');
		}
		include $this->template('setting');
	}

}