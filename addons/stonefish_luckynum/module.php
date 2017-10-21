<?php
/**
 * 幸运数字活动模块定义
 *
 * @author 微赞
 * @url http://www.00393.com/
 */
defined('IN_IA') or exit('Access Denied');

class stonefish_luckynumModule extends WeModule {
	public $tablename = 'stonefish_luckynum_award';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if (!empty($rid)) {
			$awards = pdo_fetchall("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `id` ASC", array(':rid' => $rid));
            if ($awards) {
                foreach ($awards as &$val) {
                    $val['delete_url'] = $this->createWebUrl('deleteaward', array(
                        'id' => $val['id'],
                        'rid' => $rid,
                    ));
                    //查询是否已有用户中此奖品
                    $isaward = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('stonefish_luckynum_fans')." where award_id=:award_id",array(':award_id' => $val['id']));
                }
            }
			$reply = pdo_fetch("SELECT * FROM ".tablename('stonefish_luckynum')." WHERE rid = :rid", array(':rid' => $rid));
		}
		if (!$reply) {
            $now = time();
            $reply = array(                
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
				"end_instruction" => "亲，活动已经结束，请继续关注我们的后续活动哦~",
				"show_instruction" => "活动已暂时停止，请稍候再试！",
				"time_instruction" => "亲，活动还没有开始呢！",
				"limit_instruction" => "您已参与过此活动了！",
				"awardnum_instruction" => "您的中奖次数已达到我们的最高设置，给别人留点奖品吧亲！",
				"award_instruction" => "恭喜您已中奖了，请提交领取奖品信息，方便我们工作人员联系您！",
				"ticketinfo" => "请输入详细资料，兑换奖品",
				"isrealname" => 1,
				"ismobile" => 1,
				"isfans" => 1,
				"luckynumstart" => 1,
				"limittype" => 1,
				"awardnum" =>1,
				"isfansname" => "真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位"
            );
        }
        load()->func('tpl');
        include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0) {
        global $_GPC, $_W;
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0

        $allowkeys = array(
            'newaward_number' => '请填写中奖数字',
            'newaward_title' => '请填写奖品名称',
            'newaward_desc' => '请填写奖品描述',
        );

        foreach ($allowkeys as $k=>$v) {
            if (isset($_GPC[$k]) && empty($_GPC[$k])) {
                return $v;
            }            
        }

		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
        $currentprompt = trim($_GPC['currentprompt'])?trim($_GPC['currentprompt']):'当前数字是 {LUCKYNUM}！';
        $awardprompt = trim($_GPC['awardprompt'])?trim($_GPC['awardprompt']):'恭喜您，当前数字是 {LUCKYNUM}，获得 {AWARD} 奖品！';
        $data = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'luckynumstart' => $_GPC['luckynumstart'],
			'luckynumfilter' => $_GPC['luckynumfilter'],
			'show_instruction' => $_GPC['show_instruction'],
			'time_instruction' => $_GPC['time_instruction'],
			'limit_instruction' => $_GPC['limit_instruction'],
			'end_instruction' => $_GPC['end_instruction'],
			'awardnum_instruction' => $_GPC['awardnum_instruction'],
			'award_instruction' => $_GPC['award_instruction'],
			'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
			'limittype' => $_GPC['limittype'],
			'awardnum' => $_GPC['awardnum'],
			'ticketinfo' => $_GPC['ticketinfo'],
			'isrealname' => $_GPC['isrealname'],
			'ismobile' => $_GPC['ismobile'],
			'isqq' => $_GPC['isqq'],
			'isemail' => $_GPC['isemail'],
			'isaddress' => $_GPC['isaddress'],
			'isgender' => $_GPC['isgender'],
			'istelephone' => $_GPC['istelephone'],
			'isidcard' => $_GPC['isidcard'],
			'iscompany' => $_GPC['iscompany'],
			'isoccupation' => $_GPC['isoccupation'],
			'isposition' => $_GPC['isposition'],
			'isfans' => $_GPC['isfans'],
			'isfansname' => $_GPC['isfansname'],
			'currentprompt' => $currentprompt,
            'awardprompt' => $awardprompt,
			'sponsors1' => $_GPC['sponsors1'],
			'sponsors1link' => $_GPC['sponsors1link'],
			'sponsors2' => $_GPC['sponsors2'],
			'sponsors2link' => $_GPC['sponsors2link'],
			'sponsors3' => $_GPC['sponsors3'],
			'sponsors3link' => $_GPC['sponsors3link'],
			'sponsors4' => $_GPC['sponsors4'],
			'sponsors4link' => $_GPC['sponsors4link'],
			'sponsors5' => $_GPC['sponsors5'],
			'sponsors5link' => $_GPC['sponsors5link'],
			'ruletext' => $_GPC['ruletext'],
			'title' => $_GPC['title'],
			'shareimg' => $_GPC['shareimg'],
			'sharetitle' => $_GPC['sharetitle'],
			'sharedesc' => $_GPC['sharedesc'],
        );
        $id = pdo_fetchcolumn("SELECT id FROM ".tablename('stonefish_luckynum')." WHERE rid = :rid", array(':rid' => $rid));

		    if (empty($id)) {
                $data['isshow'] = 1;
				pdo_insert('stonefish_luckynum', $data);
            } else {
                pdo_update('stonefish_luckynum', $data, array('id' => $id));
            }
			
        if (!empty($_GPC['award_number'])) {
            foreach ($_GPC['award_number'] as $key => $val) {
                $update_data = array(
                    'numbers' => $_GPC['award_number'][$key],
                    'title' => $_GPC['award_title'][$key],
                    'description' => $_GPC['award_desc'][$key],
                );
		
                    pdo_update($this->tablename, $update_data, array('id' => $key));
				
            }
        }

        //insert
        if (!empty($_GPC['newaward_number'])) {
            foreach ($_GPC['newaward_number'] as $key => $val) {
                $new_data = array(
                    'rid' => $rid,
					'uniacid' => $_W['uniacid'],
                    'numbers' => $_GPC['newaward_number'][$key],
                    'title' => $_GPC['newaward_title'][$key],
                    'description' => $_GPC['newaward_desc'][$key],
                    'dateline' => $_W['timestamp'],
                );
				
                    pdo_insert($this->tablename, $new_data);
				
            }
        }
        
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete('stonefish_luckynum_award', array('rid' => $rid));
        pdo_delete('stonefish_luckynum_fans', array('rid' => $rid));
        pdo_delete('stonefish_luckynum', array('rid' => $rid));		
		return true;
	}

	private function _table_exist($tablename) {
		$exist = false;
		$tables = pdo_fetchall('SHOW TABLES');
		if ($tables) {
			foreach ($tables as $table) {
				$table = array_values($table);
				if ($table[0] == $tablename) {
					$exist = true;	
					break;
				}
			}	
		}
		return $exist;
	}
	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			
			message('配置参数更新成功！', referer(), 'success');
		}
		//这里来展示设置项表单
		include $this->template('settings');
	}
}
