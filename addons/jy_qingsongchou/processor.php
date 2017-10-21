<?php
/**
 * 广告自主投放平台模块处理程序
 *
 * @author Michael Hu
 * @url http://bbs.9ye.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Jy_advModuleProcessor extends WeModuleProcessor {
	public function respond() {
		// $content = $this->message['content'];
		// //这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码

		$rid = $this->rule;

        if ($rid) {
            $reply = pdo_fetch ( "SELECT * FROM " . tablename ('jy_adv_rule') . " WHERE ruleid = :rid", array (':rid' => $rid ) );


            if ($reply) {
                $news = array ();
                $news [] = array ('title' => $reply['news_title'], 'description' =>$reply['news_content'], 'picurl' => $_W ['attachurl'] .$reply ['news_thumb'] , 'url' => $this->createMobileUrl ( 'index',array('quan_id'=>$reply['quan_id']))  );
                return $this->respNews ( $news );
            }
        }
        return null;
	}
}
