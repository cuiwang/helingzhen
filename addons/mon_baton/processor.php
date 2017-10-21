<?php

defined('IN_IA') or exit('Access Denied');
define("MON_BATON", "mon_baton");
require_once IA_ROOT . "/addons/" . MON_BATON . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_BATON . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_BATON . "/value.class.php";

class Mon_BatonModuleProcessor extends WeModuleProcessor
{

	private $sae = false;

	public function respond()
	{
		$rid = $this->rule;


		$baton = pdo_fetch("select * from " . tablename(DBUtil::$TABLE_BATON) . " where rid=:rid", array(":rid" => $rid));

		if (!empty($baton)) {
			if (TIMESTAMP < $baton['starttime']) {
				return $this->respText("接力活动还未开始!");
			}
			$news = array();
			$news [] = array('title' => $baton['new_title'], 'description' => $baton['new_content'], 'picurl' => MonUtil::getpicurl($baton ['new_icon']), 'url' => $this->createMobileUrl('auth', array('bid' => $baton['id'], 'au' => Value::$REDIRECT_INDEX)));
			return $this->respNews($news);
		} else {
			return $this->respText("接力活动不存在");
		}

		return null;


	}


}
