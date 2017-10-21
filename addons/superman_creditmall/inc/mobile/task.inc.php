<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileTask extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = $this->_share;
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'get', 'complete'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '任务中心';
            $alltypes = superman_task_type();
            $type = in_array($_GPC['type'], array_keys($alltypes))?$_GPC['type']:1;
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'type' => $type,
                'isshow' => 1,
            );
            $total = superman_task_count($filter);
            if ($total > 0) {
                $mytasks = array();
                if ($_W['member']['uid']) {
                    $mytasks = superman_mytask_fetchall(array(
                        'uniacid' => $_W['uniacid'],
                        'uid' => $_W['member']['uid'],
                    ), '', 0, -1);
                }
                $pindex = max(1, intval($_GPC['page']));
                $pagesize = -1; //all
                $start = ($pindex - 1) * $pagesize;
                $list = superman_task_fetchall($filter, '', $start, $pagesize);
                foreach ($list as &$item) {
                    superman_task_set($item);
                    $item['abs_url'] = superman_task_url($item);
                    $item['status'] = isset($mytasks[$item['id']])?$mytasks[$item['id']]['status']:'';
                    $item['groups'] = array();
                    if ($item['applyperm']) {
                        $item['groups'] = superman_mc_groups_fetchall(array(
                            'uniacid' => $_W['uniacid'],
                            'groupid' => $item['applyperm'],
                        ), '', 0, -1);
                    }
                }
                unset($item);
                //$pager = pagination($total, $pindex, $pagesize);
            }
            //print_r($list);

            //ad list
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
                'time' => TIMESTAMP,
                'position_id' => 7,
            );
            $adlist = superman_ad_fetchall_posid($filter);
            //print_r($adlist);
        } else if ($act == 'get') {
            if (!$_W['member']['uid']) {
                $this->json_output(ERRNO::NOT_LOGIN);
            }
            $task_id = intval($_GPC['task_id']);
            $task = superman_task_fetch($task_id);
            if (!$task) {
                $this->json_output(ERRNO::TASK_NO_FOUND);
            }
            superman_task_set($task);
            //是否启用
            if (!$task['isshow']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            //时间检查
            if ($task['starttime'] && strtotime($task['starttime']) > TIMESTAMP) {
                $this->json_output(ERRNO::TASK_NOT_BEGIN);
            }
            if ($task['endtime'] && strtotime($task['endtime']) < TIMESTAMP) {
                $this->json_output(ERRNO::TASK_END);
            }
            //申请限制
            if ($task['limits']) {
                $filter = array(
                    'task_id' => $task_id,
                );
                $real_applied = superman_mytask_count($filter);
                if ($real_applied > $task['limits']) {
                    $this->json_output(ERRNO::TASK_LIMIT_OUT);
                }
            }
            //会员组限制
            if ($task['applyperm'] && $task['applyperm'][0] != 0 && !in_array($_W['member']['groupid'], $task['applyperm'])) {
                $this->json_output(ERRNO::TASK_MEMBER_GROUP_LIMIT);
            }

            //领取任务
            $mytask = superman_mytask_fetch_uid($_W['member']['uid'], $task_id);
            if (!$mytask) {
                $credit = mt_rand($task['credit_min'], $task['credit_max']);
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $_W['member']['uid'],
                    'credit' => $credit,
                    'credit_type' => $task['credit_type'],
                    'task_id' => $task_id,
                    'status' => 0,
                    'applytime' => TIMESTAMP,
                );
                $new_id = superman_mytask_insert($data);
                if (!$new_id) {
                    $this->json_output(ERRNO::SYSTEM_ERROR);
                }

                //更新任务申请人次
                superman_task_update_count($task['id'], 'applied');
            }

            //任务跳转
            $ret = superman_task_url($task);
            if ($task['name'] == 'superman_creditmall_task6') { //关注公众号任务，领取后无需跳转
                if (!strexists($ret, 'http')) {
                    $this->json_output($ret);
                } else {
                    $this->json_output(ERRNO::OK, '领取成功');
                }
            } else {
                if (!strexists($ret, 'http')) {
                    $this->json_output($ret);
                } else {
                    $this->json_output(ERRNO::OK, '领取成功，跳转中...', array('url' => $ret));
                }
            }
        } else if ($act == 'complete') {
            if (!$_W['member']['uid']) {
                $this->json_output(ERRNO::NOT_LOGIN);
            }
            $task_id = intval($_GPC['task_id']);
            $task = superman_task_fetch($task_id);
            if (!$task) {
                $this->json_output(ERRNO::TASK_NO_FOUND);
            }
            superman_task_set($task);
            $mytask = superman_mytask_fetch_uid($_W['member']['uid'], $task_id);
            if (!$mytask) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            if ($mytask['status'] == '1') {
                $this->json_output(ERRNO::TASK_FINISH);
            }
            if ($task['builtin']) {
                $method = "do_{$task['name']}";
                if (method_exists($this, $method)) {
                    $ret = $this->$method();
                    if ($ret === false) {
                        $url = superman_task_url($task);
                        $this->json_output(ERRNO::TASK_NO_FINISH, '', array('url' => $url));
                    }

                    //增加积分
                    $log = array(
                        $_W['member']['uid'],
                        "【{$task['title']}】任务奖励",
                        'superman_creditmall',
                    );
                    $ret = mc_credit_update($_W['member']['uid'], $task['credit_type'], $task['credit'], $log);
                    if (is_error($ret)) {
                        $this->json_output(ERRNO::INVALID_REQUEST, '', array('ret'=>$ret[0].':'.$ret[1]));
                    }

                    //更新任务状态
                    $data = array(
                        'status' => 1,
                    );
                    $condition = array(
                        'id' => $mytask['id'],
                    );
                    superman_mytask_update($data, $condition);

                    //更新任务完成人次
                    superman_task_update_count($task['id'], 'completed');

                    $this->json_output(ERRNO::OK, '', array('award'=>$task['credit_title'].' +'.$task['credit']));
                }
            }
            $this->json_output(ERRNO::INVALID_REQUEST);
        }
        include $this->template('task');
    }

    //上传头像
    private function do_superman_creditmall_task1() {
        global $_W;
        $sql = "SELECT avatar FROM ".tablename('mc_members')." WHERE uid=:uid";
        $params = array(
            ':uid' => $_W['member']['uid'],
        );
        return pdo_fetchcolumn($sql, $params)?true:false;
    }

    //设置昵称
    private function do_superman_creditmall_task2() {
        global $_W;
        $sql = "SELECT nickname FROM ".tablename('mc_members')." WHERE uid=:uid";
        $params = array(
            ':uid' => $_W['member']['uid'],
        );
        return pdo_fetchcolumn($sql, $params)?true:false;
    }

    //绑定手机号
    private function do_superman_creditmall_task3() {
        global $_W;
        $sql = "SELECT mobile FROM ".tablename('mc_members')." WHERE uid=:uid";
        $params = array(
            ':uid' => $_W['member']['uid'],
        );
        return pdo_fetchcolumn($sql, $params)?true:false;
    }

    //绑定邮箱
    private function do_superman_creditmall_task4() {
        global $_W;
        $sql = "SELECT email FROM ".tablename('mc_members')." WHERE uid=:uid";
        $params = array(
            ':uid' => $_W['member']['uid'],
        );
        $email = pdo_fetchcolumn($sql, $params);
        //邮箱判断方式参考微赞系统代码 app/source/mc/profile.ctrl.php
        if (empty($email) || (!empty($email) && substr($email, -6) == '012wz.com' && strlen($email) == 39)) {
            return false;
        }
        return true;
    }

    //关注公众号
    private function do_superman_creditmall_task6() {
        global $_W;
        $fans = mc_fansinfo($_W['member']['uid']);
        return $fans['follow']?true:false;
    }
}
$obj = new Creditmall_doMobileTask;
$obj->exec();
