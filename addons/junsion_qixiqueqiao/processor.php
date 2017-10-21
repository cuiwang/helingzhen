<?php
/**
 * 七夕鹊桥模块处理程序
 *
 * @author junsion
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Junsion_qixiqueqiaoModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		$rid = $this->rule;
		$rule = pdo_fetch('select * from '.tablename($this->modulename.'_rule')." where rid='{$rid}'");
		if (!empty($rule)){
			return $this->respNews(array(array('title'=>$rule['stitle'],'description'=>$rule['sdesc'],'picurl'=>toimage($rule['sthumb']),'url'=>$this->createMobileUrl('index',array('rid'=>$rid)))));
		}
	}
}