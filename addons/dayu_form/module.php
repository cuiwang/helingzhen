<?php

/**
 * 万能表单
 *
 * @author dayu
 */
defined('IN_IA') or exit('Access Denied');

class dayu_formModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_form_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename('dayu_form') . ' WHERE `weid`=:weid AND `reid`=:reid';
            $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':reid' => $reply['reid']));
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        global $_GPC;
        $reid = intval($_GPC['activity']);
        if ($reid) {
            $sql = 'SELECT * FROM ' . tablename('dayu_form') . " WHERE `reid`=:reid";
            $params = array();
            $params[':reid'] = $reid;
            $activity = pdo_fetch($sql, $params);
            if (!empty($activity)) {
                return '';
            }
        }
        return '没有选择合适的表单';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC;
        $reid = intval($_GPC['activity']);
        $record = array();
        $record['reid'] = $reid;
        $record['rid'] = $rid;
        $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_form_reply') . " WHERE rid = :rid", array(':rid' => $rid));
        if ($reply) {
            pdo_update('dayu_form_reply', $record, array('id' => $reply['id']));
        } else {
            pdo_insert('dayu_form_reply', $record);
        }
    }

    public function ruleDeleted($rid) {
        pdo_delete('dayu_form_reply', array('rid' => $rid));
    }

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            $data = array(
                'qiniu'      => array(
                    'ak'     => $_GPC['ak'],
                    'sk'     => $_GPC['sk'],
                    'bucket' => $_GPC['bucket'],
                    'host'   => (!empty($_GPC['host']) && substr($_GPC['host'], 0, 7) != 'http://') ? 'http://' . $_GPC['host'] : $_GPC['host'],
                    'pipeline'=>$_GPC['pipeline']
                ),
                'qqkey' => $_GPC['qqkey'],
                'role' => intval($_GPC['role']),
            );
            if ($this->saveSettings($data)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }

}
