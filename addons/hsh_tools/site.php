<?php
/**
 * 工具箱模块微站定义
 *
 * @author hsh
 * @url http://www.hshcs.com
 */
defined('IN_IA') or exit('Access Denied');
require_once 'include/core.class.php';
class Hsh_toolsModuleSite extends WeModuleSite {
	public function doMobileGnfm() {
		//这个操作被定义用来呈现 功能封面
		require 'inc/web/templatemessage.inc.php';
	}
	public function doWebGzlb() {
		//这个操作被定义用来呈现 规则列表
	}
	public function doWebGlzx() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}
	public function doWebTemplateMessage() {
		//这个操作被定义用来呈现 短信模板
		require 'inc/web/templatemessage.inc.php';
	}
	public function doWebSendMessage() {
		//这个操作被定义用来呈现 短信模板
		require 'inc/web/sendmessage.inc.php';
	}
	public function doWebSendTest() {
		//这个操作被定义用来呈现 短信模板
		require 'inc/web/sendtest.inc.php';
	}
}