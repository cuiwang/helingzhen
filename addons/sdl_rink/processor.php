<?php

defined('IN_IA') or die('Access Denied');
class Sdl_rinkModuleProcessor extends WeModuleProcessor
{
	public $table_reply = 'sdl_rink_reply';
	public $table_record = 'sdl_rink_record';
	public $table_fans = 'sdl_rink_fans';
	public function respond()
	{
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename($this->table_reply) . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
		if ($row == false) {
			return $this->respText("活动已取消..");
		}
		if ($row['status'] == 0) {
			return $this->respText("活动暂停，请稍后...");
		}
		if ($row['starttime'] > TIMESTAMP) {
			return $this->respText("活动未开始，请等待...");
		}
		if ($row['endtime'] < TIMESTAMP) {
			return $this->respText("活动已结束...");
		} else {
			return $this->respNews(array('Title' => $row['tw_title'], 'Description' => $row['tw_desc'], 'PicUrl' => $_W['siteroot'] . "attachment/" . $row['tw_image'], 'Url' => $this->createMobileUrl('index', array('id' => $rid))));
		}
	}
}