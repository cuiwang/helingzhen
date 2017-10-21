<?php

/**
 * 微预约
 *
 */
defined('IN_IA') or exit('Access Denied');

class dayu_yuyuepayModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
            $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':reid' => $reply['reid']));
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        global $_GPC;
        $reid = intval($_GPC['activity']);
        if ($reid) {
            $sql = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . " WHERE `reid`=:reid";
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
        $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_reply') . " WHERE rid = :rid", array(':rid' => $rid));
        if ($reply) {
            pdo_update('dayu_yuyuepay_reply', $record, array('id' => $reply['id']));
        } else {
            pdo_insert('dayu_yuyuepay_reply', $record);
        }
    }

    public function ruleDeleted($rid) {
        pdo_delete('dayu_yuyuepay_reply', array('rid' => $rid));
    }

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            $data = array(
                'title' => $_GPC['title'],
                'list_num' => $_GPC['list_num'],
                'subject' => $_GPC['subject'],
                'smsid' => intval($_GPC['smsid']),
                'otmpl' => $_GPC['otmpl'],
                'qqkey' => $_GPC['qqkey'],
                'stime' => intval($_GPC['stime']),
                'etime' => intval($_GPC['etime']),
                'notice' => intval($_GPC['notice']),
                'today' => intval($_GPC['today']),
                'paytime' => intval($_GPC['paytime']),
                'paystate' => intval($_GPC['paystate']),
                'newtemp' => intval($_GPC['newtemp']),
                'role' => intval($_GPC['role']),
                'pay' => intval($_GPC['pay']),
                'store' => $_GPC['store'],
				'color'	=> array(
                    'nav_index'	=> $_GPC['nav_index'],
                    'nav_page'	=> $_GPC['nav_page'],
                    'nav_btn'	=> $_GPC['nav_btn']
				),
            );
            if ($this->saveSettings($data)) {
                message('保存成功', 'refresh');
            }
        }
		if(pdo_tableexists('dayu_sms')) {
			$sms = pdo_fetchall("SELECT * FROM ".tablename('dayu_sms')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
		}
		$title = !empty($settings['title']) ? $settings['title'] : "预约";
		$qqkey = !empty($settings['qqkey']) ? $settings['qqkey'] : "GGFBZ-HSW35-7KSI4-QSYAM-ZU64O-M2BSN";
		$subject = !empty($settings['subject']) ? $settings['subject'] : "主题列表";
		$today = !empty($settings['today']) ? $settings['today'] : 60;
		$paytime = !empty($settings['paytime']) ? $settings['paytime'] : 30;
		$modules = uni_modules(false);
        include $this->template('setting');
    }

}
