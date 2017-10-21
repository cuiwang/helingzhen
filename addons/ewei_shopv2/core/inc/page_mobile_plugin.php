<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class PluginMobilePage extends MobilePage
{
	public $model;
	public $set;

	public function __construct()
	{
		parent::__construct();
		$this->model = m('plugin')->loadModel($GLOBALS['_W']['plugin']);
		$this->set = $this->model->getSet();
	}

	public function getSet()
	{
		return $this->set;
	}

	public function qr()
	{
		global $_W;
		global $_GPC;
		$url = trim($_GPC['url']);
		require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
		QRcode::png($url, false, QR_ECLEVEL_L, 16, 1);
	}
}

?>
