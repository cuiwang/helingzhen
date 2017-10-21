<?php
defined('IN_IA') or exit('Access Denied');
class Fy_lessonModule extends WeModule
{
	public function settingsDisplay($settings)
	{
		global $_W, $_GPC;
		if (checksubmit()) {
			$dat = array('ucenter_bg' => $_GPC['ucenter_bg'], 'teacher_bg' => $_GPC['teacher_bg'], 'buynow_name' => trim($_GPC['buynow_name']), 'index_name' => trim($_GPC['index_name']), 'index_link' => trim($_GPC['index_link']), 'index_icon' => trim($_GPC['index_icon']), 'search_name' => trim($_GPC['search_name']), 'search_link' => trim($_GPC['search_link']), 'search_icon' => trim($_GPC['search_icon']), 'teacher_name' => trim($_GPC['teacher_name']), 'teacher_link' => trim($_GPC['teacher_link']), 'teacher_icon' => trim($_GPC['teacher_icon']), 'lesson_name' => trim($_GPC['lesson_name']), 'lesson_link' => trim($_GPC['lesson_link']), 'lesson_icon' => trim($_GPC['lesson_icon']), 'self_name' => trim($_GPC['self_name']), 'self_link' => trim($_GPC['self_link']), 'self_icon' => trim($_GPC['self_icon']));
			$this->saveSettings($dat);
			message("保存成功", refresh, 'success');
		}
		include $this->template('settings');
	}
}
