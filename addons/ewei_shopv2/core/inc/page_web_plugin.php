<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class PluginWebPage extends WebPage
{
	public $pluginname;
	public $model;
	public $plugintitle;
	public $set;

	public function __construct($_init = true)
	{
		parent::__construct($_init);
		global $_W;

		if (!com('perm')->check_plugin($_W['plugin'])) {
			$this->message('你没有相应的权限查看');
		}

		$this->pluginname = $_W['plugin'];
		$this->modulename = 'ewei_shopv2';
		$this->plugintitle = m('plugin')->getName($this->pluginname);
		$this->model = m('plugin')->loadModel($this->pluginname);
		$this->set = $this->model->getSet();

		if ($_W['ispost']) {
			rc($this->pluginname);
		}
	}

	public function getSet()
	{
		return $this->set;
	}

	public function updateSet($data = array())
	{
		$this->model->updateSet($data);
	}
}

?>
