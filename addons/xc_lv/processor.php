<?php
/**
 * 语音回复处理类
 */
defined('IN_IA') or exit('Access Denied');

class Xc_lvModuleProcessor extends WeModuleProcessor {
	
	public function respond() {
		global $_W;
		$this->module['config']['picurl'] = $_W['attachurl'] . $this->module['config']['picurl'];
		return $this->respNews($this->module['config']);
	}
}
