<?php
defined('IN_IA') or exit('Access Denied');

load()->model('mc');

class Siyuan_Bianmin_doWebForm extends Siyuan_BianminModuleSite
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function exec()
    {
        global $_GPC, $_W;
        $eid = intval($_GPC['eid']);
        $op  = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        
        if ($op == 'display') {
            $pindex    = max(1, intval($_GPC['page']));
            $psize     = 30;
            $condition = '';
            $params    = array();
            if (!empty($_GPC['keyword'])) {
                $condition .= " AND title LIKE :keyword";
                $params[':keyword'] = "%{$_GPC['keyword']}%";
            }
            if (!empty($_GPC['cate_1'])) {
                $cid = intval($_GPC['cate_1']);
                $condition .= " AND blei = '{$cid}'";
            }
            $list  = pdo_fetchall("SELECT * FROM " . tablename('siyuan_bianmin_tougao') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY id DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('siyuan_bianmin_tougao') . " WHERE weid = '{$_W['weid']}' $condition", $params);
            $pager = pagination($total, $pindex, $psize);
            
            include $this->template('web/form');
        } else if ($op == 'delete') {
            $id   = intval($_GPC['id']);
            $sql  = "SELECT * FROM " . tablename('siyuan_bianmin_tougao') . ' WHERE `id` = ' . $id;
            $item = pdo_fetch($sql, array());
            if (empty($item)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete('siyuan_bianmin_tougao', array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
            exit;
        } else if ($op == 'chuli') {
            $id   = intval($_GPC['id']);
            $sql  = "SELECT * FROM " . tablename('siyuan_bianmin_tougao') . ' WHERE `id` = ' . $id;
            $item = pdo_fetch($sql, array());
            if (empty($item)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update('siyuan_bianmin_tougao', array(
                'id' => $id,
                'status' => 1
            ), array(
                'id' => $id
            ));
            
            message('更新成功！', referer(), 'success');
            exit;
        }
    }
}

$obj = new Siyuan_Bianmin_doWebForm;
$obj->exec();

?>