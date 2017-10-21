<?php
/**
 * 小智-微直播（传播版）模块处理程序
 *
 * @author wxz
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Wxz_wzbModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
        $item = pdo_fetch("select * from ".tablename('wxz_wzb_setting')." where rid = ".$this->rule." and uniacid = ".$_W['uniacid']);
        return $this->respNews(array(
            'Title' => $item['title'],
            'Description' => $item['sub_title'],
            'PicUrl' => $item['logo'],
            'Url' => $this->createMobileUrl('index2', array('sub_openid' => $this->message['from'],'rid' => $this->rule)), 
        ));
	}
}