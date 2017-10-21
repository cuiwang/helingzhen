<?php

/**
 * 微预约
 *
 * @author dayu
 * @url QQ18898859
 */
defined('IN_IA') or exit('Access Denied');

class dayu_yuyuebizModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
            $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':reid' => $reply['reid']));
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        global $_GPC;
        $reid = intval($_GPC['activity']);
        if ($reid) {
            $sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . " WHERE `reid`=:reid";
            $params = array();
            $params[':reid'] = $reid;
            $activity = pdo_fetch($sql, $params);
            if (!empty($activity)) {
                return '';
            }
        }
        return '没有选择合适的预约';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC;
        $reid = intval($_GPC['activity']);
        $record = array();
        $record['reid'] = $reid;
        $record['rid'] = $rid;
        $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_reply') . " WHERE rid = :rid", array(':rid' => $rid));
        if ($reply) {
            pdo_update('dayu_yuyuebiz_reply', $record, array('id' => $reply['id']));
        } else {
            pdo_insert('dayu_yuyuebiz_reply', $record);
        }
    }

    public function ruleDeleted($rid) {
        pdo_delete('dayu_yuyuebiz_reply', array('rid' => $rid));
    }

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            $data = array(
				'mode' => intval($_GPC['mode']),
                'smsid' => $_GPC['smsid'],
                'zt' => $_GPC['zt'],
                'list_num' => intval($_GPC['list_num']),
                'thumb' => $_GPC['thumb'],
                'menubg' => $_GPC['menubg'],
                'menucolor' => $_GPC['menucolor'],
                'itembg' => $_GPC['itembg'],
                'itemolor' => $_GPC['itemolor'],
                'datebg' => $_GPC['datebg'],
                'calendarbg' => $_GPC['calendarbg'],
                'dotbg' => $_GPC['dotbg'],
                'dotsbg' => $_GPC['dotsbg'],
                'addressbg' => $_GPC['addressbg'],
                'buttonbg' => $_GPC['buttonbg'],
                'submitbg' => $_GPC['submitbg'],
                'listbg' => $_GPC['listbg'],
                'listsbg' => $_GPC['listsbg'],
            );
            if ($this->saveSettings($data)) {
                message('保存成功', 'refresh');
            }
        }
		if(pdo_tableexists('dayu_sms')) {
			$sms = pdo_fetchall("SELECT * FROM ".tablename('dayu_sms')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
		}
		$title="参数设置";
        include $this->template('setting');
    }

}
