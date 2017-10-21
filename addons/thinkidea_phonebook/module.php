<?php
/**
 * 电话本模块定义
 *
 * @author Thinkidea
 * @url http://bbs.Thinkidea.net/
 */
defined('IN_IA') or exit('Access Denied');

class Thinkidea_phonebookModule extends WeModule {
	
	private $reply_table = 'thinkidea_phonebook_reply';
	
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->reply_table)." WHERE weid = :weid AND rid = :rid ORDER BY `id` DESC", array(':weid' => $_W['uniacid'], ':rid' => $rid));
		}
		load()->func('tpl');
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$data = array(
			'weid' => $_W['uniacid'],
			'rid' => $rid,
			'title' => $_GPC['title'],
			'avatar' => $_GPC['avatar'],
			'description' => $_GPC['description'],
			'dateline' => time()
		);
		if(empty($id)) {
			pdo_insert($this->reply_table, $data);
		}else {
			pdo_update($this->reply_table, $data, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$dat['colspan'] = $_GPC['colspan'];
			$dat['isopenmap'] = $_GPC['isopenmap'];
			$this->saveSettings($dat);
			message('配置参数更新成功！', referer(), 'success');
		}
		include $this->template('setting');
	}

}