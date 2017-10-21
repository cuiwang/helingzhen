<?php

/**
 * 微喜帖
 *
 * @author 大路货
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Lxy_marryModuleProcessor extends WeModuleProcessor {

    public $name = 'LxymarryModuleProcessor';
    public $table_reply = 'lxy_marry_reply';
    public $table_list = 'lxy_marry_list';

    public function respond() {
        global $_W;
        $rid = $this->rule;
        $fromuser = $this->message['from'];

        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
            if ($reply) {
                $sql = 'SELECT * FROM ' . tablename($this->table_list) . ' WHERE `weid`=:weid AND `id`=:marryid';
                $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':marryid' => $reply['marryid']));
                $news = array();
                $news[] = array(
                    'title' => $activity['title'],
                    'description' => trim(strip_tags($activity['word'])),
                    'picurl' => $this->getpicurl($activity['art_pic']),
                    'url' => $this->createMobileUrl('detail', array('id' => $activity['id'])),
                );
                return $this->respNews($news);
            }
        }
        return null;
    }

    private function getpicurl($url) {
        global $_W;
        if ($url) {
            return $_W['attachurl'] . $url;
        } else {
            return $_W['siteroot'] . 'addons/lxy_marry/template/img/art_pic.png';
        }
    }

}
