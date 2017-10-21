<?php
/**
 * 金表数据模块定义
 *
 * 易 福 源 码 网 www.efwww.com
 */
defined('IN_IA') or exit('Access Denied');

class Cjwy_surveyModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$row = pdo_fetch("SELECT * FROM " . tablename('cjwy_survey') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
		if( empty($row) ){
			return array();
		}
		$news = array();
		$news[] = array(
			'title'	=>	$row['title'],
			'description'	=>	$row['description'],
			'picurl'	=>	tomedia($row['thumb']),
			'url'	=>	$this->createMobileUrl('reply', array('sid'=>$row['sid'])),
		);
		return $this->respNews( $news );
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
	}
}