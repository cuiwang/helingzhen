<?php
defined('IN_IA') or exit('Access Denied');
class Siyuan_Bianmin_doMobileList extends Siyuan_BianminModuleSite
{
    public function __construct()
    {
        parent::__construct();
    }
    public function exec()
    {
        global $_W, $_GPC;
        $act = $_GPC['act'] ? $_GPC['act'] : 'list';
        if ($act == 'list') {
            $lei    = intval($_GPC['lei']);
            $set    = pdo_fetch("SELECT title,name,thumb FROM " . tablename('siyuan_bianmin_setting') . " WHERE weid='{$_W['uniacid']}'");
            $flash  = pdo_fetchall("SELECT attachment,url FROM " . tablename('siyuan_bianmin_flash') . " WHERE weid = '{$_W['uniacid']}' ORDER BY id DESC LIMIT 5");
            $fenlei = pdo_fetchall('SELECT id,name,thumb FROM ' . tablename('siyuan_bianmin_fenlei') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC LIMIT 15");
        }
        if ($act == 'ajax') {
            global $_GPC, $_W;
            $lei     = intval($_GPC['lei']);
            $page    = intval($_GET['page']);
            $pagenum = 8;
            $start   = ($page - 1) * $pagenum;
            $list    = pdo_fetchall("SELECT id,title,fenleiid,tel,address,weixin,ding,displayorder,weid FROM " . tablename('siyuan_bianmin_news') . " WHERE weid = '{$_W['uniacid']}' and fenleiid = {$lei} ORDER BY ding DESC,displayorder DESC,id DESC LIMIT $start," . $pagenum . "");
            $info    = array();
            if (!empty($list)) {
                foreach ($list as $item) {
                    if ($item['ding'] == '1') {
                        $ding = "<span class=bg_zhiding>置顶</span>";
                    } else {
                        $ding = "";
                    }
                    $arr[] = array(
                        'title' => $item['title'],
                        'tel' => $item['tel'],
                        'ding' => $ding,
                        'address' => $item['address']
                    );
                }
            }
            echo json_encode($arr);
        }
        include $this->template($act);
    }
}
$obj = new Siyuan_Bianmin_doMobileList;
$obj->exec();