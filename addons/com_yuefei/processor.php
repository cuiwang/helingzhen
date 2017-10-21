<?php


defined('IN_IA') or exit('Access Denied');

class weisrc_quickadModuleProcessor extends WeModuleProcessor {

	public $name = 'weisrc_quickadModuleProcessor';

	public function isNeedInitContext() {

		return 0;

	}

	public function respond() {

		global $_W;

		$rid = $this->rule;

		$sql = "SELECT * FROM " . tablename('weisrc_quickad_reply') . " WHERE `rid`=:rid LIMIT 1";

		$row = pdo_fetch($sql, array(':rid' => $rid));

		$site_index = 1;

		$site_store = 2;

		$site_list = 3;

		$site_menu = 4;

		$site_intelligent = 5;

		if (empty($row['id'])) {

			return array();

		}

		$method_name = 'wapindex';

		if ($row['type'] == $site_store) {

			$method_name = 'waprestlist';

		} else if ($row['type'] == $site_list) {

			$method_name = 'waplist';

		} else if ($row['type'] == $site_menu) {

			$method_name = 'wapmenu';

		} else if ($row['type'] == $site_intelligent) {

			$method_name = 'wapselect';

		}

		$url = $this->buildSiteUrl($this->createMobileUrl($method_name, array('storeid' => $row['storeid'])));

		return $this->respNews(array('Title' => $row['title'], 'Description' => $row['description'], 'PicUrl' => !strexists($row['picture'], 'http://') ? $_W['attachurl'] . $row['picture'] : $row['picture'], 'Url' => $url));

	}
//由易福源码网破解发布
	public function isNeedSaveContext() {

		return false;

	}

}

