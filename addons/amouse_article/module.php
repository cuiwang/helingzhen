<?php

defined('IN_IA') or exit('Access Denied');
class Amouse_articleModule extends WeModule
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
        global $_GPC, $_W;
        load()->func('tpl');
        if (checksubmit()) {
            $cfg                 = array();
            $cfg['guanzhuUrl']   = htmlspecialchars_decode($_GPC['guanzhuUrl']);
            $cfg['guanzhutitle'] = $_GPC['guanzhutitle'];
            $cfg['copyright']    = $_GPC['copyright'];
            $cfg['patron']       = $_GPC['patron'];
            $cfg['contact']      = $_GPC['contact'];
            $cfg['gameUrl']      = $_GPC['gameUrl'];
            $cfg['countUrl']     = $_GPC['countUrl'];
            $cfg['bjthumb']      = $_GPC['bjthumb'];
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }
}

?>