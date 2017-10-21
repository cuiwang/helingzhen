<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doWebTask extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '任务中心';
        $act = in_array($_GPC['act'], array('display', 'add', 'edit', 'delete', 'get_sign', 'get_house', 'log', 'detail', 'complete'))?$_GPC['act']:'display';
        if ($act == 'display') {
            //排序
            if(checksubmit('submit')) {
                $displayorder = $_GPC['displayorder'];
                if ($displayorder) {
                    foreach ($displayorder as $id=>$val) {
                        pdo_update('superman_creditmall_task', array('displayorder' => $val), array('id' => $id));
                    }
                    message('操作成功！', referer(), 'success');
                }
            }
            //是否开启
            if ($_GPC['_method'] == 'switch') {
                $id = $_GPC['id'];
                $value = $_GPC['value'];
                $field = $_GPC['field'];
                $data = array(
                    $field => $value,
                );
                $condition = array(
                    'id' => $id,
                );
                superman_task_update($data, $condition);
                echo 'success';
                exit;
            }
            $types = superman_task_type();
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            if (isset($_GPC['type']) && $_GPC['type'] > 0) {
                $filter['type'] = intval($_GPC['type']);
            }
            $total = superman_task_count($filter);
            if ($total > 0) {
                $pindex = max(1, intval($_GPC['page']));
                $pagesize = 20;
                $start = ($pindex - 1) * $pagesize;
                $list = superman_task_fetchall($filter, '', $start, $pagesize);
                foreach ($list as &$item) {
                    superman_task_set($item);
                }
                unset($item);
                $pager = pagination($total, $pindex, $pagesize);
            }
            //print_r($list);
        } else if ($act == 'add') {
            if (checksubmit('submit', true)) {
                $name = $_GPC['name'];
                $task = array();
                $data = superman_task_data();
                if ($data) {
                    foreach ($data as $item) {
                        if ($item['name'] == $name) {
                            $task = $item;
                            break;
                        }
                    }
                    unset($task['allow_repeat']);
                    $task['uniacid'] = $_W['uniacid'];
                    if (isset($task['url']) && $task['url'] != '') {
                        $task['url'] .= '&i='.$_W['uniacid'];
                    }
                    $task_id = superman_task_insert($task);
                    message('操作成功！', $this->createWebUrl('task'), 'success');
                    /*@header('Location: '.$this->createWebUrl('task', array('act' => 'edit', 'id' => $task_id)));
                    exit;*/
                }
                message('任务数据不存在', referer(), 'error');
            }
            $list = superman_task_data();
            if ($list) {
                foreach ($list as &$item) {
                    superman_task_set($item);
                    $row = superman_task_fetch_name($item['name'], $_W['uniacid']);
                    $item['allow_add'] = !$item['allow_repeat']&&$row?false:true;
                }
                unset($item);
            }
            //print_r($list);
        } else if ($act == 'edit') {
            $id = intval($_GPC['id']);
            $item = superman_task_fetch($id);
            if (!$item) {
                message('任务不存在或已删除！', referer(), 'error');
            }
            superman_task_set($item);
            $types = superman_task_type();
            $groups = superman_mc_groups_fetchall(array(
                'uniacid' => $_W['uniacid'],
            ), '', 0, -1);
            $credits = superman_credit_type();
            if (checksubmit()) {
                $start_time = strtotime($_GPC['task_time']['start']);
                $end_time = strtotime($_GPC['task_time']['end']);
                $data = array(
                    'type' => $_GPC['type'],
                    'title' => trim($_GPC['title']),
                    'description' => trim($_GPC['description']),
                    'icon' => superman_fix_path(trim($_GPC['icon'])),
                    'credit_type' => trim($_GPC['credit_type']),
                    'credit_min' => floatval($_GPC['credit_min']),
                    'credit_max' => floatval($_GPC['credit_max']),
                    'starttime' => !isset($_GPC['time_limit'])?$start_time:0,
                    'endtime' => !isset($_GPC['time_limit'])?$end_time:0,
                    'isshow' => $_GPC['isshow']?1:0,
                    'applied' => intval($_GPC['applied']),
                    'completed' => intval($_GPC['completed']),
                    'limits' => intval($_GPC['limits']),
                    'extend' => isset($_GPC['extend'])?iserializer($_GPC['extend']):'',
                    'displayorder' => intval($_GPC['displayorder']),
                    'showdata' => intval($_GPC['showdata']),
                    'applyperm' => $_GPC['applyperm']?iserializer($_GPC['applyperm']):'',
                );
                if (!$item['builtin']) {
                    $data['name'] = trim($_GPC['name']);
                    $data['url'] = trim($_GPC['url']);
                }
                superman_task_update($data, array('id' => $id));
                message('操作成功！', referer(), 'success');
            }
            if (!$item['starttime'] && !$item['endtime']) {
                $item['task_time'] = array(
                    'start' => date('Y-m-d H:i'),
                    'end' => date('Y-m-d H:i', strtotime('+1 month')),
                );
            }
            $house_name = '';
            if ($item['name'] == 'superman_house' && isset($item['extend']['houseid'])) {
                $sql = "SELECT name FROM ".tablename('supermanfc_house')." WHERE id=:id";
                $params = array(
                    ':id' => $item['extend']['houseid'],
                );
                $house_name = pdo_fetchcolumn($sql, $params);
            }
            $rule_name = '';
            if ($item['name'] == 'superman_sign' && isset($item['extend']['rid'])) {
                $sql = "SELECT name FROM ".tablename('rule')." WHERE id=:rid";
                $params = array(
                    ':rid' => $item['extend']['rid'],
                );
                $rule_name = pdo_fetchcolumn($sql, $params);
            }

        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            $item = superman_task_fetch($id);
            if (!$item) {
                message('任务不存在或已删除！', referer(), 'error');
            }
            superman_task_delete($id);
            message('操作成功！', referer(), 'success');
        } else if ($act == 'get_sign') {
            if (pdo_tableexists('superman_sign_reply')) {
                $sql = "SELECT a.*,b.name FROM ".tablename('superman_sign_reply')." AS a,".tablename('rule')." AS b WHERE a.uniacid=:uniacid AND a.rid=b.id";
                $params = array(
                    ':uniacid' => $_W['uniacid'],
                );
                $list = pdo_fetchall($sql, $params);
                if ($list) {
                    foreach ($list as &$li) {
                        $li['setting'] = $li['setting']?iunserializer($li['setting']):array();
                    }
                    unset($li);
                }
                echo json_encode($list);
                exit;
            }
        } else if ($act == 'get_house') {
            if (pdo_tableexists('supermanfc_house')) {
                $sql = 'SELECT id,name FROM '.tablename('supermanfc_house').' WHERE uniacid=:uniacid AND credit>0 ORDER BY displayorder DESC';
                $params = array(
                    ':uniacid' => $_W['uniacid'],
                );
                $list = pdo_fetchall($sql, $params);
                echo json_encode($list);
                exit;
            }
        } else if ($act == 'log') {
            $task_list = M::t('superman_creditmall_task')->fetchall(array(
                'uniacid' => $_W['uniacid'],
            ), '', 0, -1);
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            if (isset($_GPC['task_id']) && $_GPC['task_id'] > 0) {
                $filter['task_id'] = intval($_GPC['task_id']);
            }
            $total = M::t('superman_creditmall_mytask')->count($filter);
            if ($total > 0) {
                $list = M::t('superman_creditmall_mytask')->fetchall($filter, '', $start, $pagesize);
                if ($list) {
                    $task_status_style = array(
                        '-1' => 'label label-danger',
                        '0' => 'label label-default',
                        '1' => 'label label-success',
                    );
                    foreach ($list as &$item) {
                        $item['member'] = mc_fetch($item['uid'], array('avatar', 'nickname'));
                        $item['task'] = M::t('superman_creditmall_task')->fetch($item['task_id']);
                        $item['applytime'] = date('Y-m-d H:i:s', $item['applytime']);
                        $item['completetime'] = $item['completetime']?date('Y-m-d H:i:s', $item['completetime']):'';
                        $item['status_title'] = superman_task_status_title($item['status']);
                    }
                    unset($item);
                }
                $pager = pagination($total, $pindex, $pagesize);
            }
            //print_r($list);
        } else if ($act == 'detail') {
            $id = intval($_GPC['id']);
            $task_status_style = array(
                '-1' => 'label label-danger',
                '0' => 'label label-default',
                '1' => 'label label-success',
            );
            $item = M::t('superman_creditmall_mytask')->fetch($id);
            $item['member'] = mc_fetch($item['uid'], array('avatar', 'nickname'));
            $item['task'] = M::t('superman_creditmall_task')->fetch($item['task_id']);
            $item['applytime'] = date('Y-m-d H:i:s', $item['applytime']);
            $item['completetime'] = $item['completetime']?date('Y-m-d H:i:s', $item['completetime']):'';
            $item['status_title'] = superman_task_status_title($item['status']);
            $item['credit'] = superman_format_price($item['credit']);
            $item['credit_title'] = superman_credit_type($item['credit_type']);
            $item['extend'] = $item['extend']?iunserializer($item['extend']):'';
        } else if ($act == 'complete') {
            $id = intval($_GPC['id']);
            $mytask = M::t('superman_creditmall_mytask')->fetch($id);
            if (!$mytask) {
                message('数据不存在或已删除！', referer(), 'error');
            }
            $task = M::t('superman_creditmall_task')->fetch($mytask['task_id']);
            if (!$task) {
                message('任务不存在或已删除！', referer(), 'error');
            }
            //增加积分
            $log = array(
                $_W['uid'],
                "【{$task['title']}】任务奖励",
                'superman_creditmall',
            );
            $ret = mc_credit_update($mytask['uid'], $mytask['credit_type'], $mytask['credit'], $log);
            if (is_error($ret)) {
                message('更新积分失败！result='.var_export($ret, true), '', 'error');
            }
            //更新状态
            $data = array(
                'status' => 1,
                'completetime' => TIMESTAMP,
            );
            $condition = array(
                'id' => $id,
            );
            M::t('superman_creditmall_mytask')->update($data, $condition);
            //更新任务完成人次
            M::t('superman_creditmall_task')->increment(array(
                'completed' => 1,
            ), array(
                'id' => $task['id'],
            ));
            message('操作成功！', referer(), 'success');
        }
        include $this->template('web/task');
    }
}

$obj = new Creditmall_doWebTask;
$obj->exec();
