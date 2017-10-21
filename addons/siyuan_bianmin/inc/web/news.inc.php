<?php
defined('IN_IA') or exit('Access Denied');

load()->model('mc');

class Siyuan_Bianmin_doWebNews extends Siyuan_BianminModuleSite
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function exec()
    {
        global $_GPC, $_W;
        $this->checkService();
        $eid    = intval($_GPC['eid']);
        $op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $fenlei = pdo_fetchall("SELECT * FROM " . tablename('siyuan_bianmin_fenlei') . " WHERE weid = '{$_W['weid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');
        
        if ($op == 'display') {
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
            $list  = pdo_fetchall("SELECT * FROM " . tablename('siyuan_bianmin_news') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('siyuan_bianmin_news') . " WHERE weid = '{$_W['weid']}' $condition", $params);
            $pager = pagination($total, $pindex, $psize);
            
            include $this->template('web/news');
        } elseif ($op == 'post') {
            $id       = intval($_GPC['id']);
            $parent   = array();
            $children = array();
            if (!empty($fenlei)) {
                $children = '';
                foreach ($fenlei as $cid => $cate) {
                    if (!empty($cate['parentid'])) {
                        $children[$cate['parentid']][] = $cate;
                    } else {
                        $parent[$cate['id']] = $cate;
                    }
                }
            }
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename('siyuan_bianmin_news') . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，视频不存在或是已经删除！', '', 'error');
                }
                $fenleiid = $item['fenleiid'];
                $slei     = $item['slei'];
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('标题不能为空，请输入标题!');
                }
                $data = array(
                    'weid' => $_W['weid'],
                    'fenleiid' => intval($_GPC['fenlei']['parentid']),
                    'slei' => intval($_GPC['fenlei']['childid']),
                    'title' => $_GPC['title'],
                    'displayorder' => $_GPC['displayorder'],
                    'tel' => $_GPC['tel'],
                    'address' => $_GPC['address'],
                    'weixin' => $_GPC['weixin'],
                    'status' => 1
                );
                if (empty($id)) {
                    pdo_insert('siyuan_bianmin_news', $data);
                } else {
                    pdo_update('siyuan_bianmin_news', $data, array(
                        'id' => $id
                    ));
                }
                message('更新成功！', url('site/entry/news', array(
                    'op' => 'display',
                    'm' => 'siyuan_bianmin'
                )), 'success');
            }
            include $this->template('web/news');
            exit;
        } else if ($op == 'zhiding') {
            $id   = intval($_GPC['id']);
            $sql  = "SELECT * FROM " . tablename('siyuan_bianmin_news') . ' WHERE `id` = ' . $id;
            $item = pdo_fetch($sql, array());
            if (empty($item)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update('siyuan_bianmin_news', array(
                'id' => $id,
                'ding' => 1
            ), array(
                'id' => $id
            ));
            
            message('置顶成功！', referer(), 'success');
            exit;
        } else if ($op == 'quxiao') {
            $id   = intval($_GPC['id']);
            $sql  = "SELECT * FROM " . tablename('siyuan_bianmin_news') . ' WHERE `id` = ' . $id;
            $item = pdo_fetch($sql, array());
            if (empty($item)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_update('siyuan_bianmin_news', array(
                'id' => $id,
                'ding' => 0
            ), array(
                'id' => $id
            ));
            
            message('取消置顶！', referer(), 'success');
            exit;
        } else if ($op == 'delete') {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename('siyuan_bianmin_news') . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete('siyuan_bianmin_news', array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
            exit;
            
        }
    }
}

$obj = new Siyuan_Bianmin_doWebNews;
$obj->exec();

?>