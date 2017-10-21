
<?php
/**
 */
defined('IN_IA') or exit('Access Denied');

class N1ce_loveModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$cfg = array(
                'title' => $_GPC['title'],
                's_botton' => $_GPC['s_botton'],
                'pic' => $_GPC['pic'],
                's_url' => $_GPC['s_url'],
				'm_url' => $_GPC['m_url'],
				'desc' => $_GPC['desc'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		if (empty($settings['title'])) {
            $settings['title'] = '你有一通来自亲爱的的未接电话！';
        }
		if (empty($settings['s_url'])) {
            $settings['s_url'] = '';
        }
		if (empty($settings['m_url'])) {
            $settings['m_url'] = $_W['siteroot'].'addons/n1ce_love/template/mobile/images/aini.mp3';
        }
        if (empty($settings['s_botton'])) {
            $settings['s_botton'] = '媳妇';
        }
        if (empty($settings['pic'])) {
            $settings['pic'] = $_W['siteroot'].'addons/n1ce_love/template/mobile/images/answer.png';
        }
		include $this->template('setting');
	}
}
?>