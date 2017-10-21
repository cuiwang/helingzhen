<?php
/**
 * 360全景
 *
 * 作者:迷失卍国度
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');

class weisrc_panoModuleProcessor extends WeModuleProcessor
{

    public $name = 'weisrc_panoModuleProcessor';

    public function isNeedInitContext()
    {
        return 0;
    }

    public function respond()
    {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('weisrc_pano_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));

        $url = $this->createMobileUrl('view', array('rid' => $row['rid']));
        return $this->respNews(array(
            'Title' => $row['title'],
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
