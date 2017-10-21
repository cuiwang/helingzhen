<?php
/**
 * 邀请帖
 *
 * 作者:微赞
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');

class weisrc_feedbackModuleProcessor extends WeModuleProcessor
{

    public $name = 'weisrc_feedbackModuleProcessor';

    public function isNeedInitContext()
    {
        return 0;
    }

    public function respond()
    {
        global $_W;
        $rid = $this->rule;

        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('weisrc_feedback_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            if ($reply) {
                $sql = 'SELECT * FROM ' . tablename('weisrc_feedback_activity') . ' WHERE status=1 AND `weid`=:weid AND `id`=:id';
                $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':id' => $reply['activityid']));
                $news = array();
                $news[] = array(
                    'title' => $activity['title'],
                    'description' => strip_tags($activity['description']),
                    'picurl' => $_W['attachurl'] . $activity['thumb'],
                    'url' => $this->createMobileUrl('index', array('id' => $activity['id']))
                );
                return $this->respNews($news);
            }
        }
        return null;
    }

    public function isNeedSaveContext()
    {
        return false;
    }
}
