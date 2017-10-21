<?php
defined('IN_IA') or exit('Access Denied');

load()->model('mc');

class Siyuan_Bianmin_doWebSet extends Siyuan_BianminModuleSite
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function exec()
    {
        global $_W, $_GPC;
        $this->checkService();
        $weid = $_W['uniacid'];
        $set  = pdo_fetch("SELECT * FROM " . tablename('siyuan_bianmin_setting') . " WHERE weid='{$_W['uniacid']}'", array(
            ':weid' => $weid
        ));
        if (checksubmit('submit')) {
            $data = array(
                'weid' => $_W['uniacid'],
                'title' => $_GPC['title'],
                'thumb' => $_GPC['thumb'],
                'gao' => $_GPC['gao'],
                'name' => $_GPC['name']
            );
            if (!empty($set)) {
                pdo_update('siyuan_bianmin_setting', $data, array(
                    'id' => $set['id']
                ));
            } else {
                pdo_insert('siyuan_bianmin_setting', $data);
            }
            message('更新系统设置成功！', 'refresh');
        }
        include $this->template('web/set');
    }
}
$obj = new Siyuan_Bianmin_doWebSet;
$obj->exec();

?>