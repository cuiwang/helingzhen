<?php
/**
 * 音乐盒子
 *
 * 作者:迷失卍国度
 *
 * qq:15595755
 */
defined('IN_IA') or exit('Access Denied');

class weisrc_audioModuleProcessor extends WeModuleProcessor
{
    public $name = 'weisrc_audioModuleProcessor';

    public function isNeedInitContext()
    {
        return 0;
    }

    public function respond()
    {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('weisrc_audio_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
        if (empty($row['id'])) {
            return array();
        }

        $title = pdo_fetchcolumn("SELECT name FROM " . tablename('rule') . " WHERE id = :rid LIMIT 1", array(':rid' => $rid));
        $url = $_W['siteroot'] . createMobileUrl('index', array('rid' => $row['rid'], 'from_user' => base64_encode(authcode($this->message['from'], 'ENCODE'))));
        return $this->respNews(array(
            'Title' => $title,
            'Description' => $row['description'],
            'PicUrl' => !strexists($row['picture'], 'http://') ? $_W['attachurl'] . $row['picture'] : $row['picture'],
            'Url' => $url
        ));
    }

    public function isNeedSaveContext()
    {
        return false;
    }
}
