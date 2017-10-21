<?php
/**
 * 火眼金睛
 *
 * @author 优企网络
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Tiger_huoyanModuleProcessor extends WeModuleProcessor {
	public $reply = 'Tiger_huoyan_reply';
	public function respond() {
		global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename($this->reply) . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
        if (empty($row['id'])) {
            return array();
        }
        return $this->respNews(array(
                    'Title' => $row['title'],
                    'Description' => $row['description'],
                    'PicUrl' => empty($row['picture']) ? '' : ($_W['attachurl'] . $row['picture']),
                    'Url' => $this->createMobileUrl('index', array('rid' => $rid)),
        ));
	}
}