<?php
/**
 * 二零四八模块微站定义
 *
 * @author suone
 * @url http://www.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('SUONE', '../addons/suone_elsb/template/');

class Suone_elsbModuleSite extends WeModuleSite {

	public function doMobileElsbru() {
		//这个操作被定义用来呈现 功能封面
        global $_GPC, $_W;
        include $this->template('elsbru');
        
       
	}


}