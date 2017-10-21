<?php
/**
 * 单品代销模块处理程序
 *
 */
defined('IN_IA') or exit('Access Denied');

class Tc_consignmentModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看文档来编写你的代码
		$rid = $this->rule;
		$result = pdo_fetch("SELECT id, pic, sharetitle, sharedesc FROM ".tablename('tc_singleproduct_goods')."WHERE rid = :rid ORDER BY id DESC", array(':rid' => $rid));
		if($result){
			return $this->respNews(array(
				'Title' => $result['sharetitle'],
				'Description' => $result['sharedesc'],
				'PicUrl' => tomedia($result['pic']),
				'Url' =>$this->createMobileUrl('index', array('id' => $result['id'])),
			));
		}else{
			return $this->respText("找不到相应的商品记录，请联系管理员！");
		}	
	}
}