<?php
/**
 * 【超人】抢楼活动模块定义
 *
 * @author 超人
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Superman_floorModule extends WeModule {
	public $tablename = 'superman_floor_award';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if (!empty($rid)) {
            $awards = pdo_fetchall("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `id` ASC", array(':rid' => $rid));
            if ($awards) {
                foreach ($awards as &$val) {
                    $val['delete_url'] = $this->createWebUrl('delete', array(
                        'id' => $val['id'],
                        'rid' => $rid,
                    ));
                }
            }
			$item = pdo_fetch("SELECT * FROM ".tablename('superman_floor')." WHERE rid = :rid", array(':rid' => $rid));
            if ($item) {
                $setting = $item['setting']?unserialize($item['setting']):array();
            }
		}
        load()->func('tpl');
        include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0) {
        global $_GPC, $_W;
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0

        $allowkeys = array(
            'newaward_floor' => '请填写中奖楼层',
            'newaward_title' => '请填写奖品名称',
            'newaward_desc' => '请填写奖品描述',
        );

        foreach ($allowkeys as $k=>$v) {
            if (isset($_GPC[$k]) && empty($_GPC[$k])) {
                return $v;
            }
            /*foreach ($_GPC[$k] as $index => $val) {
                if (empty($_GPC[$k][$index])) {
                    return $v;
                }
            }*/
        }

		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;

		$table = tablename("superman_floor_$rid");
		if (!$this->_table_exist($table)) {
			$sql =<<<EOF
CREATE TABLE IF NOT EXISTS $table (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;
EOF;
			pdo_run($sql);
		}

        $currentprompt = trim($_GPC['currentprompt'])?trim($_GPC['currentprompt']):'当前活动：{RULENAME}，当前楼层是第 {FLOOR} 楼！';
        $awardprompt = trim($_GPC['awardprompt'])?trim($_GPC['awardprompt']):'当前活动：{RULENAME}，恭喜您，当前楼层是第 {FLOOR} 楼，获得 {AWARD} 奖品！';
        $floorprompt = trim($_GPC['floorprompt'])?trim($_GPC['floorprompt']):'当前活动：{RULENAME}，您已参与过本活动，楼层为 {FLOOR} 楼，盖楼时间为 {TIME}，谢谢您的参与！';
        $setting = array(
            'repeat_floor' => $_GPC['repeat_floor']?1:0,
            'exchangekey' => trim($_GPC['exchangekey']),
        );
        $data = array(
            'rid' => $rid,
            'currentprompt' => $currentprompt,
            'awardprompt' => $awardprompt,
            'floorprompt' => $floorprompt,
            'setting' => serialize($setting),
        );
        $id = pdo_fetchcolumn("SELECT id FROM ".tablename('superman_floor')." WHERE rid = :rid", array(':rid' => $rid));
        if (empty($id)) {
            pdo_insert('superman_floor', $data);
        } else {
            pdo_update('superman_floor', $data, array('id' => $id));
        }

        //update
        if (!empty($_GPC['award_floor'])) {
            foreach ($_GPC['award_floor'] as $key => $val) {
                $update_data = array(
                    'floors' => $_GPC['award_floor'][$key],
                    'title' => $_GPC['award_title'][$key],
                    'description' => $_GPC['award_desc'][$key],
                );
                pdo_update($this->tablename, $update_data, array('id' => $key));
            }
        }

        //insert
        if (!empty($_GPC['newaward_floor'])) {
            foreach ($_GPC['newaward_floor'] as $key => $val) {
                $new_data = array(
                    'rid' => $rid,
                    'floors' => $_GPC['newaward_floor'][$key],
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
}
