<?php
/**
 * 秘室逃离模块微站定义
 *
 * @author 800083075
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class wdl_mishiModuleSite extends WeModuleSite {


    /**
     * 玩游戏主页
     */
	public function doMobilePlay() {
	    global $_W, $_GPC;

	    $html = array(
	        'jsconfig' => $_W['account']['jssdkconfig'],
	        'web_config' => $this->module['config'],
	        'share_logo' => $_W['attachurl'].$this->module['config']['share_pic'],
	    );
	    
	    
	    include $this->template('index');
	}

}