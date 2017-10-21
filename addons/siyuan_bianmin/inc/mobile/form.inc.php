<?php
defined('IN_IA') or exit('Access Denied');
class Siyuan_Bianmin_doMobileForm extends Siyuan_BianminModuleSite
{
    public function __construct()
    {
        parent::__construct();
    }
    public function exec()
    {
        global $_W, $_GPC;
        $act    = $_GPC['act'] ? $_GPC['act'] : 'form';
        $flash  = pdo_fetchall("SELECT attachment,url FROM " . tablename('siyuan_bianmin_flash') . " WHERE weid = '{$_W['uniacid']}' ORDER BY id DESC LIMIT 5");
        $set    = pdo_fetch("SELECT title,name,thumb FROM " . tablename('siyuan_bianmin_setting') . " WHERE weid='{$_W['uniacid']}'");
        $fenlei = pdo_fetchall('SELECT id,name,thumb FROM ' . tablename('siyuan_bianmin_fenlei') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC LIMIT 15");
        if ($act == 'form') {
            $data = array(
                'weid' => $_W['weid'],
                'title' => $_GPC['title'],
                'tel' => $_GPC['tel'],
                'weixin' => $_GPC['weixin'],
                'address' => $_GPC['address'],
                'status' => 0
            );
            if ($_W['ispost']) {
                if (empty($_GPC['id'])) {
                    pdo_insert('siyuan_bianmin_tougao', $data);
                    message('发布成功，请等待我们的工作人员处理！', $this->createMobileUrl('index'), 'success');
                }
            }
        }
        include $this->template('form');
    }
}
$obj = new Siyuan_Bianmin_doMobileForm;
$obj->exec();