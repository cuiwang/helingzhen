<?php
defined('IN_IA') or exit('Access Denied');
class lwx_loveModule extends WeModule
{
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        $setting = $_W['account']['modules'][$this->_saveing_params['mid']]['config'];
        include $this->template('rule');
    }
    public function fieldsFormSubmit($rid = 0)
    {
        global $_GPC, $_W;
        if (!empty($_GPC['title'])) {
            $data = array(
                'title' => $_GPC['title'],
                'description' => $_GPC['description'],
                'picurl' => $_GPC['thumb-old'],
                'url' => create_url('mobile/module/list', array(
                    'name' => 'shopping',
                    'weid' => $_W['weid']
                ))
            );
            if (!empty($_GPC['thumb'])) {
                $data['picurl'] = $_GPC['thumb'];
                file_delete($_GPC['thumb-old']);
            }
            $this->saveSettings($data);
        }
        return true;
    }
    public function settingsDisplay($settings)
    {
        global $_GPC, $_W;
        if (checksubmit()) {
            $cfg = array(
                'noticeemail' => $_GPC['noticeemail'],
                'shopname' => $_GPC['shopname'],
                'address' => $_GPC['address'],
                'phone' => $_GPC['phone'],
                'officialweb' => $_GPC['officialweb'],
                'description' => htmlspecialchars_decode($_GPC['description'])
            );
            if (!empty($_GPC['logo'])) {
                $cfg['logo'] = $_GPC['logo'];
            }
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        load()->func('tpl');
        include $this->template('setting');
    }
}