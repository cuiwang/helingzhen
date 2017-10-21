
<?php
/**
 * 暖心模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/n1ce_love/template/mobile/');
class N1ce_loveModuleSite extends WeModuleSite {

	public function doMobiletel() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		$title = isset($this->module['config']['title']) ? $this->module['config']['title'] : '你有一通来自亲爱的的未接电话！';
		$desc = isset($this->module['config']['desc']) ?$this->module['config']['desc'] : '';
		$pic = isset($this->module['config']['pic']) ? $this->module['config']['pic'] : $_W['siteroot'].'addons/n1ce_love/template/mobile/images/answer.png';
		$pic = tomedia($pic);
		$s_botton = isset($this->module['config']['s_botton']) ? $this->module['config']['s_botton'] : '小媳妇';
		$s_url = isset($this->module['config']['s_url']) ? $this->module['config']['s_url'] : '';
		$m_url = isset($this->module['config']['m_url']) ? $this->module['config']['m_url'] : $_W['siteroot'].'addons/n1ce_love/template/mobile/images/aini.mp3';
		$pageurl = $_W['siteroot'].'app/'.$this->createMobileUrl('tel');
		$ontelurl = $_W['siteroot'].'app/'.$this->createMobileUrl('ontel');
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
			include $this->template('no_sub');
			exit();
		}
		include $this->template('tel');
	}

	public function doMobileontel() {
		global $_W,$_GPC;
		$title = isset($this->module['config']['title']) ? $this->module['config']['title'] : '你有一通来自亲爱的未接电话！';
		$desc = isset($this->module['config']['desc']) ?$this->module['config']['desc'] : '';
		$pic = isset($this->module['config']['pic']) ? $this->module['config']['pic'] : $_W['siteroot'].'addons/n1ce_love/template/mobile/images/answer.png';
		$pic = tomedia($pic);
		$pageurl = $_W['siteroot'].'app/'.$this->createMobileUrl('tel');
		$s_url = isset($this->module['config']['s_url']) ? $this->module['config']['s_url'] : '';
		$s_botton = isset($this->module['config']['s_botton']) ? $this->module['config']['s_botton'] : '小媳妇';
		$m_url = isset($this->module['config']['m_url']) ? $this->module['config']['m_url'] : $_W['siteroot'].'addons/n1ce_love/template/mobile/images/aini.mp3';
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
			include $this->template('no_sub');
			exit();
		}
		include $this->template('ontel');
	}

}
?>