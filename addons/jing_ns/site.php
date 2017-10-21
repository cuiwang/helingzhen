<?php
defined('IN_IA') or exit('Access Denied');
define('CSS_PATH', '../addons/jing_ns/template/style/css/');
define('JS_PATH', '../addons/jing_ns/template/style/js/');
define('IMG_PATH', '../addons/jing_ns/template/style/img/');
define('MEDIA_PATH', '../addons/jing_ns/template/style/media/');
class Jing_nsModuleSite extends WeModuleSite {
	public $table_reply = 'jing_ns_reply';

	public function doMobileDetail() {
		global $_W, $_GPC;
		$uniacid=$_W['uniacid'];
		//这个操作被定义用来呈现 微站首页导航图标
		$id = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
		if (!empty($reply)) {
			include $this->template('index');
		}else{
			exit('参数错误');
		}
	}
}
?>