<?php

/**
 * 微喜帖
 *
 * @author 大路货
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Lxy_marryModule extends WeModule {

    public $name = 'Lxybuilding';
    public $title = '微喜帖';
    public $ability = '';
    public $table_reply = 'lxy_marry_reply';
    public $table_list = 'lxy_marry_list';

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename($this->table_list) . ' WHERE `weid`=:weid AND `id`=:marry_id';
            $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':marry_id' => $reply['marryid']));
            $showpicurl = $this->getpicurl($activity['art_pic']);
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        global $_W, $_GPC;
        $marryid = intval($_GPC['activity']);
        if (!empty($marryid)) {
            $sql = 'SELECT * FROM ' . tablename($this->table_list) . " WHERE `id`=:marryid";
            $params = array();
            $params[':marryid'] = $marryid;
            $activity = pdo_fetch($sql, $params);
            return;
            if (!empty($activity)) {
                return '';
            }
        }
        return '没有选择合适的喜帖';
    }

    private function getpicurl($url) {
        global $_W;
        if ($url) {
            return tomedia($url);
        } else {
            return $_W['siteroot'] . 'addons/lxy_marry/template/img/art_pic.png';
        }
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC;
        $marryid = intval($_GPC['activity']);
        $record = array();
        $record['marryid'] = $marryid;
        $record['rid'] = $rid;
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
        if ($reply) {
            pdo_update($this->table_reply, $record, array('id' => $reply['id']));
        } else {
            pdo_insert($this->table_reply, $record);
        }
    }

    public function ruleDeleted($rid) {
        pdo_delete($this->table_reply, array('rid' => $rid));
    }

}
