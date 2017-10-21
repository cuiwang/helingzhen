<?php

/**
 * 万能表单提交页皮肤管理
 *
 * @author dayu
 * @url QQ18898859
 */
defined('IN_IA') or exit('Access Denied');

class dayu_form_plugin_skinsModule extends WeModule {

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            $data = array(
            );
            if ($this->saveSettings($data)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }

}
