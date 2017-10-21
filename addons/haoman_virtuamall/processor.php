<?php
/**
 * 语音回复处理类
 * 
 * [WeEngine System] Copyright (c) 2013 wdlcms.com
 */
defined('IN_IA') or exit('Access Denied');

class haoman_virtuamallModuleProcessor extends WeModuleProcessor {
	
	public function respond() {
		global $_W;
		$this->module['config']['picurl'] = $_W['attachurl'] . $this->module['config']['picurl'];
		return $this->respNews($this->module['config']);
	}
}
