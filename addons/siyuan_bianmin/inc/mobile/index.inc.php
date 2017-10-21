<?php
defined('IN_IA') or exit('Access Denied');
class Siyuan_Bianmin_doMobileIndex extends Siyuan_BianminModuleSite
{
    public function __construct()
    {
        parent::__construct();
    }
    public function exec()
    {
        global $_W, $_GPC;
        $act    = $_GPC['act'] ? $_GPC['act'] : 'index';
        $flash  = pdo_fetchall("SELECT attachment,url FROM " . tablename('siyuan_bianmin_flash') . " WHERE weid = '{$_W['uniacid']}' ORDER BY id DESC LIMIT 5");
        $set    = pdo_fetch("SELECT title,name,thumb,gao FROM " . tablename('siyuan_bianmin_setting') . " WHERE weid='{$_W['uniacid']}'");
        $fenlei = pdo_fetchall('SELECT id,name,thumb FROM ' . tablename('siyuan_bianmin_fenlei') . " WHERE weid = '{$_W['uniacid']}' and parentid = '0' ORDER BY displayorder DESC LIMIT 8");
        if ($act == 'index') {
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 20;
            $condition = '';
            $params    = array();
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE :keyword";
                $params[':keyword'] = "%{$_GPC['keyword']}%";
            }
            if (!empty($_GPC['cate_1'])) {
                $cid = intval($_GPC['cate_1']);
                $condition .= " AND fenleiid = '{$cid}'";
            }
            $list = pdo_fetchall('SELECT id,title,tel,address,weixin FROM ' . tablename('siyuan_bianmin_news') . " WHERE weid = '{$_W['uniacid']}' $condition and status = 1 ORDER BY id DESC LIMIT  " . ($pindex - 1) * $psize . ',' . $psize, $params);
        }
        if ($act == 'list') {
            $fenleiid = $_GPC['fenleiid'];
            $slei     = $_GPC['slei'];
            if (!empty($fenleiid)) {
                $leilist = pdo_fetchall('SELECT id,name,thumb,parentid FROM ' . tablename('siyuan_bianmin_fenlei') . " WHERE weid = '{$_W['uniacid']}' and parentid = $fenleiid ORDER BY displayorder DESC LIMIT 8");
            }
        }
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
                    pdo_insert('siyuan_bianmin_news', $data);
                    message('发布成功，请等待我们的工作人员处理！', $this->createMobileUrl('index'), 'success');
                }
            }
        }
        if ($act == 'ajax') {
            global $_GPC, $_W;
            $fenleiid = $_GPC['fenleiid'];
            $slei     = $_GPC['slei'];
            $page     = intval($_GET['page']);
            $pagenum  = 8;
            $start    = ($page - 1) * $pagenum;
            if (!empty($fenleiid)) {
                $condition = " AND fenleiid = $fenleiid";
            } else {
                $condition = " AND slei = $slei";
            }
            $list = pdo_fetchall("SELECT id,title,fenleiid,slei,tel,address,weixin,ding,displayorder,weid FROM " . tablename('siyuan_bianmin_news') . " WHERE weid = '{$_W['uniacid']}' $condition and status = 1 ORDER BY ding DESC,displayorder DESC,id DESC LIMIT $start," . $pagenum . "");
            $info = array();
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
$obj = new Siyuan_Bianmin_doMobileIndex;
$obj->exec();

?>