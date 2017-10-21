<?php
defined("IN_IA") or exit("Access Denied");
class Enjoy_cityModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $input = array_elements(array(
                'appid',
                'secret',
                'mchid',
                'password',
                'jgday',
                'mid',
                'mid1',
                'mid2',
                'admin'
            ), $_GPC);
            $input['mid'] = trim($input['mid']);
            $input['mid1'] = trim($input['mid1']);
            $input['mid2'] = trim($input['mid2']);
            $input['jgday'] = trim($input['jgday']);
            $input['admin'] = trim($input['admin']);
            $setting = $this->module['config'];
            $setting['api'] = $input;
            if ($this->saveSettings($setting)) {
                message('保存参数成功', 'refresh');
            }
        }
        $config = $this->module['config']['api'];
        include $this->template('setting');
    }
}