<?php
/**
 * 微医疗模块定义
 */
defined('IN_IA') or exit('Access Denied');

class bm_hospitalModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
    	$rid = $this->rule;
    	$sql = "SELECT * FROM " . tablename('bmhospital_reply') . " WHERE `rid`=:rid LIMIT 1";
    	$row = pdo_fetch($sql, array(':rid' => $rid));
    	if (empty($row['id'])) {
    		return $this->respText("请维护科室信息") ;
    	}
    	return $this->respNews(array(
    				'Title' => $row['title'],
    				'Description' => $row['cosmtment_info'],
    				'PicUrl' => $_W['attachurl'] . $row['picurl'],
    				'Url' => $this->createMobileUrl('index',array('id' => $row['id'])),
    		));
	}
}