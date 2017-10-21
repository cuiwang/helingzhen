<?php
/**
 * 一元夺宝模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');
class weliam_indianaModule extends WeModule {
	public function welcomeDisplay()
	{
		header("Location: index.php?c=site&a=entry&m=weliam_indiana&do=setting&op=display");
		exit();
	}
}