<?php

//decode by QQ:270656184 http://www.yunlu99.com/
defined('IN_IA') or die('Access Denied');
function add_announce($announce = array())
{
	$data = array('weid' => $announce['weid'], 'giftid' => $announce['giftid'], 'from_user' => $announce['from_user'], 'type' => $announce['type'], 'title' => $announce['title'], 'content' => $announce['content'], 'levelid' => -1, 'displayorder' => 0, 'updatetime' => TIMESTAMP, 'dateline' => TIMESTAMP);
	pdo_insert('icard_announce', $data);
}