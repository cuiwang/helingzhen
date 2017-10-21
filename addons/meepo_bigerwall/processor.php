<?php
/**
 * MEEPO 超级微现场
 *
 * http://meepo.com.cn 作者QQ 284099857
 */
defined('IN_IA') or exit('Access Denied');

class Meepo_bigerwallModuleProcessor extends WeModuleProcessor {
	
	
	public function respond() {
		return $this->respText('请参照使用说明设置图文消息触发！');
	}
	
}
