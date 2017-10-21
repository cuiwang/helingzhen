<?php
/**
 * 报名朋友圈分享模块处理程序
 *
 * @author 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
//作者海纳百川qq:1120924338
class Cgc_baoming_shareModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微盒子文档来编写你的代码	
		$rid = $this->rule;
		$reply = pdo_fetch("SELECT * FROM " . tablename('cgc_baoming_reply') . " WHERE rid = :id", array(':id' => $rid));
		     
        switch ($reply['type'])
       {
        case 0:
          $form="login";
          break;  
        case 1:
          $form="result";
          break;
        case 2:
          $form="user";
          break;
       }
        
	    $news = array(
					'title' => $reply['pic_title'],
					'description' =>htmlspecialchars_decode($reply['pic_desc']),
					'picurl' =>$reply['pic_thumb'],
					'url' => $this->createMobileUrl("enter", array('id' => $reply['activity_id'],'form'=>$form,'fromuser'=> $this->message['from'])),
				);
				return $this->respNews($news);
		
	}
}