<?php
defined('IN_IA') or die('Access Denied');
define('STYLE_PATH', '../addons/mb_swish/static');
define('MB_ROOT', IA_ROOT . '/addons/mb_swish');
require MB_ROOT . '/source/util.php';
class MB_SwishModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        check_license();
        if (checksubmit()) {
            $input = coll_elements(array('title', 'subscribe', 'guide', 'logo', 'share'), $_GPC);
            $setting = $this->module['config'];
            $setting['biz'] = $input;
            $this->saveSettings($setting);
            message('保存参数成功', referer());
        }
        $config = $this->module['config']['biz'];
        include $this->template('setting');
    }
}