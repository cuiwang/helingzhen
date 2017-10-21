<?php

/**
 * 万能表单
 *
 * @author dayu
 */
defined('IN_IA') or exit('Access Denied');

class dayu_formModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $rid = $this->rule;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_form_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            if ($reply) {
                $sql = 'SELECT * FROM ' . tablename('dayu_form') . ' WHERE `weid`=:weid AND `reid`=:reid';
                $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':reid' => $reply['reid']));
                $news = array();
                $news[] = array(
                    'title' => $activity['title'],
                    'description' => strip_tags($activity['description']),
                    'picurl' => tomedia($activity['thumb']),
                    'url' => $this->createMobileUrl('dayu_form',array('id' => $activity['reid'], 'weid' => $_W['uniacid']))
                );
                return $this->respNews($news);
            }
        }
        return null;
    }

}
