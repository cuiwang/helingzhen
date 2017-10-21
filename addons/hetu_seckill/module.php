<?php

defined('IN_IA') || die('Access Denied');
class Hetu_seckillModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W;
        global $_GPC;
        if ($_POST) {
            $input = array_elements(array('auto_take', 'remind_time', 'title', 'index_img', 'index_img_url', 'temp_warning', 'admin_openid', 'num_warning', 'length', 'show_lx', 'device', 'wz_show', 'copyright', 'bb_show', 'pay_temp', 'send_temp', 'low_stocks', 'remind_temp', 'hoaff_temp', 'share_title', 'share_desc', 'share_img', 'auth_code'), $_GPC);
            $setting['cont'] = array('title' => $input['title'], 'index_img' => $input['index_img'], 'index_img_url' => $input['index_img_url'], 'length' => $input['length'], 'show_lx' => $input['show_lx'], 'wz_show' => $input['wz_show'], 'bb_show' => $input['bb_show'], 'remind_time' => trim($input['remind_time']), 'auto_take' => trim($input['auto_take']), 'copyright' => $input['copyright'], 'pay_temp' => $input['pay_temp'], 'send_temp' => $input['send_temp'], 'low_stocks' => $input['low_stocks'], 'remind_temp' => $input['remind_temp'], 'pay_temp' => $input['pay_temp'], 'hoaff_temp' => $input['hoaff_temp'], 'share_title' => $input['share_title'], 'share_desc' => $input['share_desc'], 'share_img' => $input['share_img'], 'num_warning' => $input['num_warning'], 'admin_openid' => $input['admin_openid'], 'temp_warning' => $input['temp_warning'], 'auth_code' => $input['auth_code']);
            if ($this->saveSettings($setting)) {
                message('保存参数成功', 'refresh');
            }
        }
        $item = $this->module['config']['cont'];
        $sql = 'SELECT COUNT(*) FROM ' . tablename('modules') . ' WHERE name = :name ';
        $count = pdo_fetchcolumn($sql, array(':name' => 'hetu_halfoff'));
        include $this->template('setting');
    }
}