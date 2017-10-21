<?php
/**
 * 邀请函
 *
 * 作者:微赞
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');

class weisrc_invitativeModule extends WeModule
{
    public $name = 'weisrc_invitativeModule';
    public $title = '邀请函';
    public $ability = '';
    public $tablename = 'weisrc_invitative_reply';
    public $action = 'detail'; //方法
    public $modulename = 'weisrc_invitative'; //模块标识

    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            $sql = 'SELECT * FROM ' . tablename($this->modulename . '_activity') . ' WHERE `weid`=:weid AND `id`=:id';
            $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':id' => $reply['activityid']));
        }
        include $this->template('form');
    }

    public function fieldsFormSubmit($rid = 0)
    {
        global $_GPC;

        $activityid = intval($_GPC['activity']);

        $record = array();

        $record['activityid'] = $activityid;

        $record['rid'] = $rid;

        $reply = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_reply') . " WHERE rid = :rid", array(':rid' => $rid));

        if ($reply) {
            pdo_update($this->modulename . '_reply', $record, array('id' => $reply['id']));
        } else {
            pdo_insert($this->modulename . '_reply', $record);
        }
    }
}