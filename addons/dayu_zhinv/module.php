<?php
/**
 * 拯救织女
 *
 */
defined('IN_IA') or exit('Access Denied');

class dayu_zhinvModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		$reply = pdo_fetch("SELECT * FROM ".tablename('dayu_zhinv_reply')." WHERE rid = :rid", array(':rid' => $rid));		
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		return '';
	}

	public function fieldsFormSubmit($rid) {
		global $_W, $_GPC;
		$reid = intval($_GPC['reply_id']);
		
		$data = array(
			'rid' => $rid,
			'cover' => $_GPC['cover'],
			'title' => $_GPC['title'],
			'description' => $_GPC['description']
		);
		if (empty($reid)) {
			pdo_insert('dayu_zhinv_reply', $data);
		} else {
			pdo_update('dayu_zhinv_reply', $data, array('id' => $reid));
		}
	}

	public function ruleDeleted($rid) {
		pdo_delete('dayu_zhinv_reply', array('rid' => $rid));
	}


}