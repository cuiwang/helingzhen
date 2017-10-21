<?php
defined('IN_IA') or exit('Access Denied');

class Siyuan_BianminModuleSite extends WeModuleSite
{
    protected $modules_bindings;
    public function __construct()
    {
        global $_GPC;
        $this->modulename = 'siyuan_bianmin';
        $this->__define   = IA_ROOT . "/addons/siyuan_bianmin/module.php";
        load()->model('module');
        $this->module           = module_fetch($this->modulename);
        $dos                    = array(
            'index',
            'huodong',
            'partner',
            'my'
        );
        $sql                    = "SELECT eid,do FROM " . tablename('modules_bindings') . "WHERE `do` IN ('" . implode("','", $dos) . "') AND `entry`='cover' AND module='siyuan_bianmin'";
        $this->modules_bindings = pdo_fetchall($sql, array(), 'do');
        load()->func('tpl');
        load()->func('file');
    }
}
?>