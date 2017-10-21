<?php
defined('IN_IA') or exit('Access Denied');
define('MODULE_ROOT', IA_ROOT . '/addons/czt_wechat_visitor/');
require MODULE_ROOT . 'global.php';
class Czt_wechat_visitorModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $input = coll_elements(array(
                'guide',
                'share',
                'force_follow',
                'topics',
                'creat_ad_img',
                'creat_ad_url',
                'index_ad_img',
                'index_ad_url'
            ), $_GPC);
            $this->saveSettings($input);
            message('保存成功', referer());
        }
        if (empty($settings['topics'])) {
            $topics             = array(
                '初恋变这样了，当初真是瞎了',
                '小时候的照片，无法直视啊！！',
                '哥明天就要去迪拜当乞丐啦，不要羡慕我',
                '找到女朋友了，请不要再叫我单身狗',
                '月底要结婚啦，你们都会来吧？',
                '我微信所有帅哥美女都在这里，有看上眼的和我说一声。',
                '在韩国找到失散多年的妹妹，你们要看吗？'
            );
            $settings['topics'] = implode("\n", $topics);
        }
        include $this->template('setting');
    }
}