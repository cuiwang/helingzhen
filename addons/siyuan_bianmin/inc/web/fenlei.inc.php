<?php
defined('IN_IA') or exit('Access Denied');

load()->model('mc');

class Siyuan_Bianmin_doWebFenlei extends Siyuan_BianminModuleSite
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
            if (!empty($_GPC['displayorder'])) {
                foreach ($_GPC['displayorder'] as $id => $displayorder) {
                    pdo_update('siyuan_bianmin_fenlei', array(
                        'displayorder' => $displayorder
                    ), array(
                        'id' => $id
                    ));
                }
                message('分类排序更新成功！', 'refresh', 'success');
            }
            $children = array();
            $fenlei   = pdo_fetchall("SELECT * FROM " . tablename('siyuan_bianmin_fenlei') . " WHERE weid = '{$_W['weid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ");
            foreach ($fenlei as $index => $row) {
                if (!empty($row['parentid'])) {
                    $children[$row['parentid']][] = $row;
                    unset($fenlei[$index]);
                }
            }
            include $this->template('web/fenlei');
            exit;
        } else if ($op == 'post') {
            $parentid = intval($_GPC['parentid']);
            $id       = intval($_GPC['id']);
            //微站风格模板
            //$template = $this->account_template();
            if (!empty($id)) {
                $fenlei = pdo_fetch("SELECT * FROM " . tablename('siyuan_bianmin_fenlei') . " WHERE id = '$id'");
                if (!empty($fenlei['nid'])) {
                    $nav        = pdo_fetch("SELECT * FROM " . tablename('site_nav') . " WHERE id = :id", array(
                        ':id' => $fenlei['nid']
                    ));
                    $nav['css'] = unserialize($nav['css']);
                    if (strexists($nav['icon'], 'images/')) {
                        $nav['fileicon'] = $nav['icon'];
                        $nav['icon']     = '';
                    }
                }
            } else {
                $fenlei = array(
                    'displayorder' => 0
                );
            }
            if (!empty($parentid)) {
                $parent = pdo_fetch("SELECT id, name FROM " . tablename('siyuan_bianmin_fenlei') . " WHERE id = '$parentid'");
                if (empty($parent)) {
                    message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('fenlei', array(
                        'foo' => 'display'
                    )), 'error');
                }
            }
            if (checksubmit('fileupload-delete')) {
                file_delete($_GPC['fileupload-delete']);
                pdo_update('site_nav', array(
                    'icon' => ''
                ), array(
                    'id' => $fenlei['nid']
                ));
                message('删除成功！', referer(), 'success');
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['cname'])) {
                    message('抱歉，请输入分类名称！');
                }
                $pathinfo = pathinfo($_GPC['file']);
                //list($gpc_template, $gpc_templatefile) = explode(':', $_GPC['template'], 2);
                $data     = array(
                    'weid' => $_W['weid'],
                    'name' => $_GPC['cname'],
                    'displayorder' => intval($_GPC['displayorder']),
                    'parentid' => intval($parentid),
                    //'description' => $_GPC['description'],
                    'thumb' => $_GPC['thumb']
                    //'ishomepage' => intval($_GPC['ishomepage']),
                );
                if (!empty($id)) {
                    unset($data['parentid']);
                    pdo_update('siyuan_bianmin_fenlei', $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert('siyuan_bianmin_fenlei', $data);
                    $id = pdo_insertid();
                }
                if (!empty($_GPC['isnav'])) {
                    $nav        = array(
                        'weid' => $_W['weid'],
                        'name' => $data['name'],
                        'thumb' => $data['thumb'],
                        'displayorder' => 0,
                        'position' => 1,
                        'url' => $this->createMobileUrl('list', array(
                            'cid' => $id
                        )),
                        'issystem' => 0,
                        'status' => 1
                    );
                    $nav['css'] = serialize(array(
                        'icon' => array(
                            'font-size' => $_GPC['icon']['size'],
                            'color' => $_GPC['icon']['color'],
                            'width' => $_GPC['icon']['size'],
                            'icon' => $_GPC['icon']['icon']
                        )
                    ));
                    if (!empty($_FILES['icon']['tmp_name'])) {
                        file_delete($_GPC['icon_old']);
                        $upload = file_upload($_FILES['icon']);
                        if (is_error($upload)) {
                            message($upload['message'], '', 'error');
                        }
                        $nav['icon'] = $upload['path'];
                    }
                    if (empty($fenlei['nid'])) {
                        pdo_insert('site_nav', $nav);
                        pdo_update('siyuan_bianmin_fenlei', array(
                            'nid' => pdo_insertid()
                        ), array(
                            'id' => $id
                        ));
                    } else {
                        pdo_update('site_nav', $nav, array(
                            'id' => $fenlei['nid']
                        ));
                    }
                } else {
                    pdo_delete('site_nav', array(
                        'id' => $fenlei['nid']
                    ));
                    pdo_update('siyuan_bianmin_fenlei', array(
                        'nid' => 0
                    ), array(
                        'id' => $id
                    ));
                }
                message('分类更新成功！', url('site/entry/fenlei', array(
                    'op' => 'display',
                    'm' => 'siyuan_bianmin'
                )), 'success');
            }
            include $this->template('web/fenlei');
            exit;
        } else if ($op == 'delete') {
            $id     = intval($_GPC['id']);
            $fenlei = pdo_fetch("SELECT id, parentid, nid FROM " . tablename('siyuan_bianmin_fenlei') . " WHERE id = '$id'");
            if (empty($fenlei)) {
                message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('fenlei'), 'error');
            }
            $navs = pdo_fetchall("SELECT icon, id FROM " . tablename('site_nav') . " WHERE id IN (SELECT nid FROM " . tablename('siyuan_bianmin_fenlei') . " WHERE id = {$id} OR parentid = '$id')", array(), 'id');
            if (!empty($navs)) {
                foreach ($navs as $row) {
                    file_delete($row['icon']);
                }
                pdo_query("DELETE FROM " . tablename('site_nav') . " WHERE id IN (" . implode(',', array_keys($navs)) . ")");
            }
            pdo_delete('siyuan_bianmin_fenlei', array(
                'id' => $id,
                'parentid' => $id
            ), 'OR');
            message('分类删除成功！', url('site/entry/fenlei', array(
                'op' => 'display',
                'm' => 'siyuan_bianmin'
            )), 'success');
            exit;
            
        }
    }
}

$obj = new Siyuan_Bianmin_doWebFenlei;
$obj->exec();

?>