<?php
defined ( 'IN_IA' ) or exit('Access Denied,your ip is:'.$_SERVER['REMOTE_ADDR'].',We have recorded the source of attack.');
class Main extends WeModuleSite {
	private $paylogtable="netbuffer_creditchangered_paylog";
	public function __construct() {
// 		global $_W;
// 		$this -> autoFinishOrders();
// 		if (is_weixin()) {
// 			m('member') -> checkMember();
// 		}
	}

	protected  function route($do, $web = true) {
		global $_GPC,$_W;
		load()->model('mc');
		load()->func('logging');
		$do = strtolower(substr($do, $web ? 5 : 8));
		$p = trim($_GPC['op']);
		empty($p) && $p ="index";
		if ($web) {
			$file = APP_CLASS_PATH."web/" . $do . "/" . $p . ".php";
		} else {
			if (empty($_W['fans']['tag']['nickname'])) {
				mc_oauth_userinfo();
			}
			$cfg=$this->module['config'];
			$file = APP_CLASS_PATH."mobile/".$do . "/" . $p . ".php";
		}
		if (!is_file($file)) {
			message("未找到 控制器文件 {$do}::{$p} : {$file}");
		}
		include $file;
		exit;
	}
}
