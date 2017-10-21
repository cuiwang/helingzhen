<?php

/**
 * 大转盘模块
 */
defined('IN_IA') or exit('Access Denied');
include "model.php";

class BigwheelModule extends WeModule {

    public $tablename = 'bigwheel_reply';

    public function fieldsFormDisplay($rid = 0) {
        global $_W;

        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
        if (!$reply) {
            $now = time();
            $reply = array(
                "title" => "幸运大转盘活动开始了!",
                "start_picurl" => "./source/modules/bigwheel/template/style/activity-lottery-start.jpg",
                "description" => "欢迎参加幸运大转盘活动",
                "repeat_lottery_reply" => "亲，继续努力哦~~",
                "ticket_information" => "兑奖请联系我们,电话: 13888888888",
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "end_theme" => "幸运大转盘活动已经结束了",
                "end_instruction" => "亲，活动已经结束，请继续关注我们的后续活动哦~",
                "end_picurl" => "./source/modules/bigwheel/template/style/activity-lottery-end.jpg",
                "most_num_times" => 1,
                "number_times" => 1,
                "probability" => 0,
                "award_times" => 1,
                "sn_code" => 1,
                "sn_rename" => "SN码",
                "tel_rename" => "手机号",
                "show_num" => 1,
                "share_title" => "欢迎参加大转盘活动",
                "share_desc" => "亲，欢迎参加大转盘抽奖活动，祝您好运哦！！ 亲，需要绑定账号才可以参加哦",
                "share_txt" => "&lt;p&gt;1. 关注微信公众账号\"()\"&lt;/p&gt;&lt;p&gt;2. 发送消息\"大转盘\", 点击返回的消息即可参加&lt;/p&gt;",
            );
        }

        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);

        $insert = array(
            'rid' => $rid,
            'weid' => $_W['weid'],
            'title' => $_GPC['title'],
            'content' => $_GPC['content'],
            'ticket_information' => $_GPC['ticket_information'],
            'description' => $_GPC['description'],
            'repeat_lottery_reply' => $_GPC['repeat_lottery_reply'],
            //'start_picurl' => $_GPC['start_picurl'],
            'end_theme' => $_GPC['end_theme'],
            'end_instruction' => $_GPC['end_instruction'],
            //'end_picurl' => $_GPC['end_picurl'],
            'probability' => $_GPC['probability'],
            'c_type_one' => $_GPC['c_type_one'],
            'c_name_one' => $_GPC['c_name_one'],
            'c_num_one' => $_GPC['c_num_one'],
          
            'c_type_two' => $_GPC['c_type_two'],
            'c_name_two' => $_GPC['c_name_two'],
            'c_num_two' => $_GPC['c_num_two'],
            'c_type_three' => $_GPC['c_type_three'],
            'c_name_three' => $_GPC['c_name_three'],
            'c_num_three' => $_GPC['c_num_three'],
            'c_type_four' => $_GPC['c_type_four'],
            'c_name_four' => $_GPC['c_name_four'],
            'c_num_four' => $_GPC['c_num_four'],
            'c_type_five' => $_GPC['c_type_five'],
            'c_name_five' => $_GPC['c_name_five'],
            'c_num_five' => $_GPC['c_num_five'],
            'c_type_six' => $_GPC['c_type_six'],
            'c_name_six' => $_GPC['c_name_six'],
            'c_num_six' => $_GPC['c_num_six'],
            'award_times' => $_GPC['award_times'],
            'number_times' => $_GPC['number_times'],
            'most_num_times' => $_GPC['most_num_times'],
            'sn_code' => $_GPC['sn_code'],
            'sn_rename' => $_GPC['sn_rename'],
            'tel_rename' => $_GPC['tel_rename'],
            'show_num' => $_GPC['show_num'],
            'createtime' => time(),
            'copyright' => $_GPC['copyright'],
            'share_title' => $_GPC['share_title'],
            'share_desc' => $_GPC['share_desc'],
            'share_url' => $_GPC['share_url'],
            'share_txt' => $_GPC['share_txt'],
            'starttime' => strtotime($_GPC['datelimit-start']),
            'endtime' => strtotime($_GPC['datelimit-end']),
              'c_rate_one' => $_GPC['c_rate_one'],
              'c_rate_two' => $_GPC['c_rate_two'],
              'c_rate_three' => $_GPC['c_rate_three'],
              'c_rate_four' => $_GPC['c_rate_four'],
              'c_rate_five' => $_GPC['c_rate_five'],
              'c_rate_six' => $_GPC['c_rate_six'],
        );

        if (!empty($_GPC['start_picurl'])) {
            $insert['start_picurl'] = $_GPC['start_picurl'];
            file_delete($_GPC['start_picurl-old']);
        }

        if (!empty($_GPC['end_picurl'])) {
            $insert['end_picurl'] = $_GPC['end_picurl'];
            file_delete($_GPC['end_picurl-old']);
        }

        $insert['total_num'] = intval($_GPC['c_num_one']) + intval($_GPC['c_num_two']) + intval($_GPC['c_num_three']) + intval($_GPC['c_num_four']) + intval($_GPC['c_num_five']) + intval($_GPC['c_num_six']);

        if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['isshow'] = 1;
            } else {
                $insert['isshow'] = 0;
            }
            $id = pdo_insert($this->tablename, $insert);
        } else {
            pdo_update($this->tablename, $insert, array('id' => $id));
        }
        return true;
    }

    public function ruleDeleted($rid) {
        pdo_delete('bigwheel_award', array('rid' => $rid));
        pdo_delete('bigwheel_reply', array('rid' => $rid));
        pdo_delete('bigwheel_fans', array('rid' => $rid));
    }

    public function doManage() {
        global $_GPC, $_W;
        include model('rule');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "weid = :weid AND `module` = :module";
        $params = array();
        $params[':weid'] = $_W['weid'];
        $params[':module'] = 'bigwheel';

        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        $list = rule_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = rule_keywords_search($condition);
                $bigwheel = pdo_fetch("SELECT fansnum, viewnum,starttime,endtime,isshow FROM " . tablename('bigwheel_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['fansnum'] = $bigwheel['fansnum'];
                $item['viewnum'] = $bigwheel['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $bigwheel['starttime']);
                $endtime = $bigwheel['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($bigwheel['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-red">未开始</span>';
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = '<span class="label label-blue">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($bigwheel['isshow'] == 1) {
                        $item['status'] = '<span class="label label-green">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label ">已暂停</span>';
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $bigwheel['isshow'];
            }
        }
        include $this->template('manage');
    }

    public function dodelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and weid=:weid", array(':id' => $rid, ':weid' => $_W['weid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }


        message('规则操作成功！', create_url('site/module/manage', array('name' => 'bigwheel')), 'success');
    }

    public function dodeleteAll() {
        global $_GPC, $_W;

        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and weid=:weid", array(':id' => $rid, ':weid' => $_W['weid']));
            if (empty($rule)) {
                $this->message('抱歉，要修改的规则不存在或是已经被删除！');
            }
            if (pdo_delete('rule', array('id' => $rid))) {
                pdo_delete('rule_keyword', array('rid' => $rid));
                //删除统计相关数据
                pdo_delete('stat_rule', array('rid' => $rid));
                pdo_delete('stat_keyword', array('rid' => $rid));
                //调用模块中的删除
                $module = WeUtility::createModule($rule['module']);
                if (method_exists($module, 'ruleDeleted')) {
                    $module->ruleDeleted($rid);
                }
            }
        }
        $this->message('规则操作成功！', '', 0);
    }

    public function doawardlist() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid, ':weid' => $_W['weid']);
        if (!empty($_GPC['status'])) {
            $where.=' and a.status=:status';
            $params[':status'] = $_GPC['status'];
        }
        if (!empty($_GPC['keywords'])) {
            if (strlen($_GPC['keywords']) == 11 && is_numeric($_GPC['keywords'])) {
            	$fans = pdo_fetchall("SELECT from_user FROM ".tablename('bigwheel_fans')." WHERE tel = :tel", array(':tel' => $_GPC['keywords']), 'from_user');
            	if (!empty($fans)) {
            		$where .= " AND a.from_user IN ('".implode("','", array_keys($fans))."')";
            	}
            } else {
            	$where.=' and (a.award_sn like :status)';
            	$params[':status'] = "%{$_GPC['keywords']}%";
            }
        }
        $total = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('bigwheel_award') . " a WHERE a.rid = :rid and a.weid=:weid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT a.* FROM " . tablename('bigwheel_award') . " a WHERE a.rid = :rid and a.weid=:weid  " . $where . " ORDER BY a.id DESC " . $limit, $params);
        //一些参数的显示
        $num1 = pdo_fetchcolumn("SELECT total_num FROM " . tablename($this->tablename) . " WHERE rid = :rid", array(':rid' => $rid));
        $num2 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('bigwheel_award') . " WHERE rid = :rid and status=1", array(':rid' => $rid));
        $num3 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('bigwheel_award') . " WHERE rid = :rid and status=2", array(':rid' => $rid));

        include $this->template('awardlist');
    }

    public function dodownload() {
        require_once 'download.php';
    }

    public function dosetshow() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('bigwheel_reply', array('isshow' => $isshow), array('rid' => $rid));
        message('状态设置成功！', create_url('site/module/manage', array('name' => 'bigwheel')), 'success');
    }

    public function dosetstatus() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $status = intval($_GPC['status']);
        if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $p = array('status' => $status);
        if ($status == 2) {
            $p['consumetime'] = TIMESTAMP;
        }
        $temp = pdo_update('bigwheel_award', $p, array('id' => $id, 'weid' => $_W['weid']));
        if ($temp == false) {
            message('抱歉，刚才操作数据失败！', '', 'error');
        } else {
            message('状态设置成功！', create_url('site/module/awardlist', array('name' => 'bigwheel', 'rid' => $_GPC['rid'])), 'success');
        }
    }

    public function dogetphone() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $fans = $_GPC['fans'];
        $tel = pdo_fetchcolumn("SELECT tel FROM " . tablename('bigwheel_fans') . " WHERE rid = " . $rid . " and  from_user='" . $fans . "'");
        if ($tel == false) {
            echo '没有登记';
        } else {
            echo $tel;
        }
    }

    public function message($error, $url = '', $errno = -1) {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }

}
