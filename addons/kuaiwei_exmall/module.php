<?php
defined('IN_IA') or exit('Access Denied');

class Kuaiwei_exmallModule extends WeModule {

	public function fieldsFormDisplay($rid = 0) {
	
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid = 0) {

		return true;
	}

	public function ruleDeleted($rid) {
		pdo_delete('kuaiwei_exmall_base', array('rid' => $rid));
		pdo_delete('kuaiwei_exmall_data', array('rid' => $rid));
		pdo_delete('kuaiwei_exmall_fans', array('rid' => $rid));
		pdo_delete('kuaiwei_exmall_goods', array('rid' => $rid));
		pdo_delete('kuaiwei_exmall_ad', array('rid' => $rid));
		pdo_delete('kuaiwei_exmall_award', array('rid' => $rid));
	}

}
