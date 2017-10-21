<?php


defined('IN_IA') or exit('Access Denied');
class Cyl_phoneModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $data = $_GPC['data'];
            if (!$this->saveSettings($data)) {
                message('保存信息失败', '', 'error');
            } else {
                message('保存信息成功', '', 'success');
            }
        }
        load()->func('tpl');
        include $this->template('setting');
    }
}