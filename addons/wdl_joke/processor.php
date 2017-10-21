<?php
/**
 * 笑话大全模块处理程序
 *
 * @author 微赞科技
 * @url http://www.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Wdl_jokeModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		
		$api = 'http://www.weiduola.cn/api/joke.php?mod=get&type=1';
		$data = file_get_contents($api);
		$data = json_decode($data);
		
		//if ($data['type'] > 1) {
		  //  $reply = '拿到一条图片'.file_get_contents($api);
		    //return $this->respText($reply);
		//} else {
		    $reply = $data->text;
		    return $this->respText($reply);
		//}
	}
}