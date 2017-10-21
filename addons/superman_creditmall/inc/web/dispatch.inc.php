<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebDispatch extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '配送方式';
        $act = in_array($_GPC['act'], array('display', 'post', 'delete', 'displayorder'))?$_GPC['act']:'display';
        if ($act == 'display') {
            if ($_GPC['_method'] == 'switch') {
                $id = $_GPC['id'];
                $value = $_GPC['value'];
                $field = $_GPC['field'];
                pdo_update('superman_creditmall_dispatch', array($field => $value), array('id' => $id));
                echo 'success';
                exit;
            }
            if(checksubmit('submit')) {
                $displayorder = $_GPC['displayorder'];
                if ($displayorder) {
                    foreach ($displayorder as $id=>$val) {
                        pdo_update('superman_creditmall_dispatch', array('displayorder' => $val), array('id' => $id));
                    }
                    message('更新成功', referer(), 'success');
                }
            }
            $filter = array(
              'uniacid' => $_W['uniacid'],
            );
            $list = superman_dispatch_fetchall($filter);
        } else if ($act == 'post') {
            $id = intval($_GPC['id']);
            if ($id) {
                $item = superman_dispatch_fetch($id);
                if ($item) {
                    $item['extend'] = iunserializer($item['extend']);
                    if (!isset($item['extend']['pickup_info'])) {
                        $item['extend']['pickup_info'] = '';
                    }
                }
            }

            if (checksubmit('submit')) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'displayorder' => $_GPC['displayorder'],
                    'title' => trim($_GPC['title']),
                    'fee' => $_GPC['fee'],
                    'isshow' => $_GPC['isshow'],
                    'need_address' => $_GPC['need_address'],
                    'extend' => iserializer($_GPC['extend']),
                );

                if (!$id) {
                    $data['dateline'] = TIMESTAMP;
                    $ret = pdo_insert('superman_creditmall_dispatch', $data);
                } else {
                    $ret = pdo_update('superman_creditmall_dispatch', $data, array('id'=>$id));
                }
                if ($ret===false) {
                    message('更新失败', referer(), 'error');
                }
                message('更新成功', $this->createWebUrl('dispatch'), 'success');
            }
        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            if (!$id) {
                message('配送方式不存在', referer(), 'error');
            }
            pdo_delete('superman_creditmall_dispatch',array('id'=>$id));
            message('删除成功', $this->createWebUrl('dispatch'), 'success');
        }
        include $this->template('web/dispatch');
    }
}

$obj = new Creditmall_doWebDispatch;
$obj->exec();
