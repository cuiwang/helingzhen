<?php

defined('IN_IA') or exit('Access Denied');

class Tim_cowModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		
		$news = array();
		$news['title'] = "树形产品展示";
		$news['picurl'] = "http://wqtim.sinaapp.com/addons/product_show/page.png";
		$news['description'] = "一款树形产品展示特效，让你的产品更吸引";
		$news['url'] = $this->createMobileUrl('index');
		return $this->respNews($news);
		
	}
    
}