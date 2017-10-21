<?php

//decode by QQ:270656184 http://www.yunlu99.com/
defined('IN_IA') or die('Access Denied');
class Dg_articlemanageModule extends WeModule
{
	public function fieldsFormDisplay($rid = 0)
	{
	}
	public function fieldsFormValidate($rid = 0)
	{
		return '';
	}
	public function fieldsFormSubmit($rid)
	{
	}
	public function ruleDeleted($rid)
	{
	}
	public function settingsDisplay($settings)
	{
		global $_W, $_GPC;
		empty($settings['dg_article_recharge']) && ($settings['dg_article_recharge'] = "5.00");
		empty($settings['dg_article_scale']) && ($settings['dg_article_scale'] = "0.3");
		empty($settings['center_intro']) && ($settings['center_intro'] = "成为会员可以免费阅读需要付费的文章");
		empty($settings['dg_article_title']) && ($settings['dg_article_title'] = $_W['account']['name'] . "付费阅读");
		empty($settings['dg_article_num']) && ($settings['dg_article_num'] = 20);
		if (checksubmit()) {
			$dat['dg_article_recharge'] = $_GPC['dg_article_recharge'];
			$dat['dg_article_scale'] = $_GPC['dg_article_scale'];
			$dat['dg_article_title'] = $_GPC['dg_article_title'];
			$dat['dg_article_num'] = $_GPC['dg_article_num'];
			$dat['center_intro'] = $_GPC['center_intro'];
			if (!$this->saveSettings($dat)) {
				message('设置失败', '', 'error');
			} else {
				message('设置成功', '', 'success');
			}
		}
		include $this->template('setting');
	}
}