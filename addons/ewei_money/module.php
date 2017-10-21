<?php

/**
 * 疯狂划算
 *
 * @author ewei 012wz.com
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_moneyModule extends WeModule {

    public $tablename = 'ewei_money_reply';
    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }

        if ($reply == false) {
            $reply = array(
                'title'=>'数钱数到手抽筋，疯狂开启!',
                'c_rate_one' => 30,
                'c_rate_two' => 25,
                'c_rate_three' => 10,
                'c_rate_four' => 10,
                'c_rate_five' => 5,
                'c_rate_six' => 5,
                'c_rate_seven' => 5,
                'c_rate_eight' => 5,
                'c_rate_nine' => 5,
                'total_remain' => 20,
                'remain' => 3,
                'alltimes' => 20,
                'daytimes' => 3,
                'max_sum' => 200,
                'min_sum' => 100,
                'game_time' => 10,
                'start_picurl' => '../addons/ewei_money/template/money.png',
                'starttime'=>time(),
                'endtime'=>time() + 7 * 86400,
                'remain_stime'=>time(),
                'remain_etime'=>time() + 7 * 86400,
            );
        }
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0) {
        return true;
    }
    public function fieldsFormSubmit($rid = 0) {

        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        $data = array(
            'c_rate_one' => 0,
            'c_rate_two' => 0,
            'c_rate_three' => 0,
            'c_rate_four' => 0,
            'c_rate_five' => 0,
            'c_rate_six' => 0,
            'c_rate_seven' => 0,
            'c_rate_eight' => 0,
            'c_rate_nine' => 0,
        );
        $tatle = 0;
        if ($_GPC['checkbox1']) {
            if (intval($_GPC['c_rate_one'])) {
                if (intval($_GPC['c_rate_one']) > 0) {
                    $data['c_rate_one'] = intval($_GPC['c_rate_one']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_one']);
        }
        if ($_GPC['checkbox2']) {
            if (intval($_GPC['c_rate_two'])) {
                if (intval($_GPC['c_rate_two']) > 0) {
                    $data['c_rate_two'] = intval($_GPC['c_rate_two']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_two']);
        }
        if ($_GPC['checkbox3']) {
            if (intval($_GPC['c_rate_three'])) {
                if (intval($_GPC['c_rate_three']) > 0) {
                    $data['c_rate_three'] = intval($_GPC['c_rate_three']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_three']);
        }
        if ($_GPC['checkbox4']) {
            if (intval($_GPC['c_rate_four'])) {
                if (intval($_GPC['c_rate_four']) > 0) {
                    $data['c_rate_four'] = intval($_GPC['c_rate_four']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_four']);
        }
        if ($_GPC['checkbox5']) {
            if (intval($_GPC['c_rate_five'])) {
                if (intval($_GPC['c_rate_five']) > 0) {
                    $data['c_rate_five'] = intval($_GPC['c_rate_five']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_five']);
        }
        if ($_GPC['checkbox6']) {
            if (intval($_GPC['c_rate_six'])) {
                if (intval($_GPC['c_rate_six']) > 0) {
                    $data['c_rate_six'] = intval($_GPC['c_rate_six']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_six']);
        }
        if ($_GPC['checkbox7']) {
            if (intval($_GPC['c_rate_seven'])) {
                if (intval($_GPC['c_rate_seven']) > 0) {
                    $data['c_rate_seven'] = intval($_GPC['c_rate_seven']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_seven']);
        }
        if ($_GPC['checkbox8']) {
            if (intval($_GPC['c_rate_eight'])) {
                if (intval($_GPC['c_rate_eight']) > 0) {
                    $data['c_rate_eight'] = intval($_GPC['c_rate_eight']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_eight']);
        }
        if ($_GPC['checkbox9']) {
            if (intval($_GPC['c_rate_nine'])) {
                if (intval($_GPC['c_rate_nine']) > 0) {
                    $data['c_rate_nine'] = intval($_GPC['c_rate_nine']);
                } else {
                    message('1角现金劵占比必须大于0', '', 'error');
                }
            }
            $tatle += intval($_GPC['c_rate_nine']);
        }
        if ($tatle != 100) {
            message('现金券面额及占比总和必须等于100', '', 'error');
        }
        if (intval($_GPC['game_time']) < 1) {
            message('游戏时间必须填写,且大于0', '', 'error');
        }
        if (empty($_GPC['start_picurl'])) {
            message('请上传一张活动图片', '', 'error');
        }
        if (intval($_GPC['alltimes']) < 1 || intval($_GPC['daytimes']) < 1) {
            message('总抽奖次数和每天抽奖次数必须大于0', '', 'error');
        }
        if (intval($_GPC['remain']) < 0 || intval($_GPC['total_remain']) < 0) {
            message('现金劵张数必须大于0', '', 'error');
        }
        if (trim($_GPC['remain_sm']) == '') {
            message('请填写兑奖密码', '', 'error');
        }
        if (strlen(trim($_GPC['remain_sm'])) > 15) {
            message('兑奖密码长度必须小于15', '', 'error');
        }
        $insert = array(
            'remain_sm' => trim($_GPC['remain_sm']),
            'valid_time' => $_GPC['datelimit1']['start'].' ~ ' . $_GPC['datelimit1']['end'] ,
            'c_rate_one' => $data['c_rate_one'],
            'c_rate_two' => $data['c_rate_two'],
            'c_rate_three' => $data['c_rate_three'],
            'c_rate_four' => $data['c_rate_four'],
            'c_rate_five' => $data['c_rate_five'],
            'c_rate_six' => $data['c_rate_six'],
            'c_rate_seven' => $data['c_rate_seven'],
            'c_rate_eight' => $data['c_rate_eight'],
            'c_rate_nine' => $data['c_rate_nine'],
            'rid' => $rid,
            'isshow' => 1,
            'isfollow' => intval($_GPC['isfollow']),
            'total_remain' => intval($_GPC['total_remain']),
            'remain' => intval($_GPC['remain']),
            'remain_name' => $_GPC['remain_name'],
            'remain_rule' => $_GPC['remain_rule'],
            'remain_stime' => strtotime($_GPC['datelimit1']['start']),
            'remain_etime' => strtotime($_GPC['datelimit1']['end']),
            'start_picurl' => $_GPC['start_picurl'],
            'reg_first' => intval($_GPC['reg_first']),
            'game_time' => intval($_GPC['game_time']),
            'max_sum' => intval($_GPC['max_sum']),
            'min_sum' => intval($_GPC['min_sum']),
            'alltimes' => intval($_GPC['alltimes']),
            'daytimes' => intval($_GPC['daytimes']),
            'rule' => htmlspecialchars_decode($_GPC['rule']),
            'title'=>$_GPC['title'],
            'description' => htmlspecialchars_decode($_GPC['description']),
            'homeurl' => $_GPC['homeurl'],
            'followurl' => $_GPC['followurl'],
            'homename' => $_GPC['homename'],
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
            'isfollow' => intval($_GPC['isfollow']),
        );

        if (empty($id)) {
            $insert['create_time'] = time();
            pdo_insert($this->tablename, $insert);
        } else {
            unset($insert['rid']);
            pdo_update($this->tablename, $insert, array('id' => $id));
        }
        return true;
    }
    public function ruleDeleted($rid = 0) {
        pdo_delete($this->tablename, array('rid' => $rid));
        pdo_delete('ewei_money_fans', array('rid' => $rid));
        pdo_delete('ewei_money_award', array('rid' => $rid));
        return true;
    }

}
