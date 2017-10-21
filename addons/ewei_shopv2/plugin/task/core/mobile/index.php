<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class index_EweiShopV2Page extends PluginMobilePage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$member = m('member')->getMember($_W['openid']);
		$list1 = $this->model->getUserTaskList(1);
		$list2 = $this->model->getUserTaskList(2);
		$poster = $this->taskposter();
		$bgimg = pdo_get('ewei_shop_task_default', array('uniacid' => $_W['uniacid']), array('bgimg'));
		include $this->template();
	}

	public function newtask()
	{
		global $_W;
		global $_GPC;
		$id = $_GPC['id'];
		$res = $this->model->getNewTask($id);

		if (is_string($res)) {
			show_json(0, $res);
		}

		show_json(1, $res);
	}

	public function mytask()
	{
		global $_W;
		$dolist = $this->model->getMyTaskList();
		$donelist = $this->model->getMyTaskList('>');
		$poster = $this->taskposter();
		$fail = $this->model->failTask();
		include $this->template();
	}

	public function detail()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$poster = intval($_GPC['poster']);

		if ($poster) {
			$sql = 'SELECT * FROM ' . tablename('ewei_shop_task_poster') . ' WHERE id = :id';
			$detail = pdo_fetch($sql, array(':id' => $id));
			$reward_data = unserialize($detail['reward_data']);
		}
		else {
			$sql = 'SELECT * FROM ' . tablename('ewei_shop_task') . ' WHERE id = :id';
			$detail = pdo_fetch($sql, array(':id' => $id));
			$reward_data = unserialize($detail['reward_data']);
			$require_data = unserialize($detail['require_data']);
		}

		include $this->template();
	}

	public function taskposter()
	{
		global $_W;
		global $_GPC;
		$tabpage = $_GPC['tabpage'];
		$openid = trim($_W['openid']);
		$is_menu = $this->model->getdefault('menu_state');
		$member = m('member')->getMember($openid);
		$now_time = time();
		$task_sql = 'SELECT * FROM ' . tablename('ewei_shop_task_poster') . ' WHERE timestart<=' . $now_time . ' AND timeend>' . $now_time . ' AND uniacid=' . $_W['uniacid'] . ' AND `status`=1 AND `is_delete`=0 ORDER BY `createtime` DESC';
		$task_list = pdo_fetchall($task_sql);

		foreach ($task_list as $key => $val) {
			if ($val['poster_type'] == 1) {
				$val['reward_data'] = unserialize($val['reward_data']);
				$recward = $val['reward_data']['rec'];
				if (isset($recward['credit']) && (0 < $recward['credit'])) {
					$task_list[$key]['is_credit'] = 1;
				}

				if (isset($recward['money']['num']) && (0 < $recward['money']['num'])) {
					$task_list[$key]['is_money'] = 1;
				}

				if (isset($recward['bribery']) && (0 < $recward['bribery'])) {
					$task_list[$key]['is_bribery'] = 1;
				}

				if (isset($recward['goods']) && count($recward['goods'])) {
					$task_list[$key]['is_goods'] = 1;
				}

				if (isset($recward['coupon']['total']) && (0 < $recward['coupon']['total'])) {
					$task_list[$key]['is_coupon'] = 1;
				}
			}
			else {
				if ($val['poster_type'] == 2) {
					$val['reward_data'] = unserialize($val['reward_data']);
					$recward = $val['reward_data']['rec'];

					foreach ($recward as $k => $v) {
						if (isset($v['credit']) && (0 < $v['credit'])) {
							$task_list[$key]['is_credit'] = 1;
						}

						if (isset($v['money']['num']) && (0 < $v['money']['num'])) {
							$task_list[$key]['is_money'] = 1;
						}

						if (isset($v['bribery']) && (0 < $v['bribery'])) {
							$task_list[$key]['is_bribery'] = 1;
						}

						if (isset($v['goods']) && count($v['goods'])) {
							$task_list[$key]['is_goods'] = 1;
						}

						if (isset($v['coupon']['total']) && (0 < $v['coupon']['total'])) {
							$task_list[$key]['is_coupon'] = 1;
						}
					}
				}
			}
		}

		$running_sql = 'SELECT `join`.*,`task`.title,`task`.reward_data AS `poster_reward`,`task`.titleicon,`task`.poster_type FROM ' . tablename('ewei_shop_task_join') . ' AS `join` LEFT JOIN ' . tablename('ewei_shop_task_poster') . ' AS `task` ON `join`.task_id=`task`.`id` WHERE `join`.`failtime`>' . $now_time . ' AND `join`.`join_user`="' . $openid . '" AND `join`.uniacid=' . $_W['uniacid'] . ' AND `join`.`is_reward` = 0 AND `task`.`is_delete` = 0 ORDER BY `join`.`addtime` DESC LIMIT 0,15';
		$task_running = pdo_fetchall($running_sql);

		foreach ($task_running as $key => $val) {
			if ($val['poster_type'] == 1) {
				$val['reward_data'] = unserialize($val['poster_reward']);
				$recward = $val['reward_data']['rec'];
				if (isset($recward['credit']) && (0 < $recward['credit'])) {
					$task_running[$key]['is_credit'] = 1;
				}

				if (isset($recward['money']['num']) && (0 < $recward['money']['num'])) {
					$task_running[$key]['is_money'] = 1;
				}

				if (isset($recward['bribery']) && (0 < $recward['bribery'])) {
					$task_running[$key]['is_bribery'] = 1;
				}

				if (isset($recward['goods']) && count($recward['goods'])) {
					$task_running[$key]['is_goods'] = 1;
				}

				if (isset($recward['coupon']['total']) && (0 < $recward['coupon']['total'])) {
					$task_running[$key]['is_coupon'] = 1;
				}
			}
			else {
				if ($val['poster_type'] == 2) {
					$val['reward_data'] = unserialize($val['poster_reward']);
					$recward = $val['reward_data']['rec'];

					foreach ($recward as $k => $v) {
						if (isset($v['credit']) && (0 < $v['credit'])) {
							$task_running[$key]['is_credit'] = 1;
						}

						if (isset($v['money']['num']) && (0 < $v['money']['num'])) {
							$task_running[$key]['is_money'] = 1;
						}

						if (isset($v['bribery']) && (0 < $v['bribery'])) {
							$task_running[$key]['is_bribery'] = 1;
						}

						if (isset($v['goods']) && count($v['goods'])) {
							$task_running[$key]['is_goods'] = 1;
						}

						if (isset($v['coupon']['total']) && (0 < $v['coupon']['total'])) {
							$task_running[$key]['is_coupon'] = 1;
						}
					}
				}
			}
		}

		$complete_sql = 'SELECT `join`.*,`task`.title,`task`.titleicon,`task`.poster_type FROM ' . tablename('ewei_shop_task_join') . ' AS `join` LEFT JOIN ' . tablename('ewei_shop_task_poster') . ' AS `task` ON `join`.task_id=`task`.`id` WHERE `join`.uniacid=' . $_W['uniacid'] . ' AND `join`.`join_user`="' . $openid . '" AND `join`.`is_reward`=1 AND `task`.`is_delete` = 0 ORDER BY `join`.`addtime` DESC LIMIT 0,15';
		$task_complete = pdo_fetchall($complete_sql);

		foreach ($task_complete as $key => $val) {
			if ($val['poster_type'] == 1) {
				$task_complete[$key]['reward_data'] = unserialize($val['reward_data']);
				$val['reward_data'] = unserialize($val['reward_data']);
				$recward = $val['reward_data'];
				if (isset($recward['credit']) && (0 < $recward['credit'])) {
					$task_complete[$key]['is_credit'] = 1;
				}

				if (isset($recward['money']['num']) && (0 < $recward['money']['num'])) {
					$task_complete[$key]['is_money'] = 1;
				}

				if (isset($recward['bribery']) && (0 < $recward['bribery'])) {
					$task_complete[$key]['is_bribery'] = 1;
				}

				if (isset($recward['goods']) && count($recward['goods'])) {
					$task_complete[$key]['is_goods'] = 1;
				}

				if (isset($recward['coupon']['total']) && (0 < $recward['coupon']['total'])) {
					$task_complete[$key]['is_coupon'] = 1;
				}
			}
			else {
				if ($val['poster_type'] == 2) {
					$val['reward_data'] = unserialize($val['reward_data']);
					$recward = $val['reward_data'];

					foreach ($recward as $k => $v) {
						if (isset($v['credit']) && (0 < $v['credit'])) {
							$task_complete[$key]['is_credit'] = 1;
						}

						if (isset($v['money']['num']) && (0 < $v['money']['num'])) {
							$task_complete[$key]['is_money'] = 1;
						}

						if (isset($v['bribery']) && (0 < $v['bribery'])) {
							$task_complete[$key]['is_bribery'] = 1;
						}

						if (isset($v['goods']) && count($v['goods'])) {
							$task_complete[$key]['is_goods'] = 1;
						}

						if (isset($v['coupon']['total']) && (0 < $v['coupon']['total'])) {
							$task_complete[$key]['is_coupon'] = 1;
						}
					}
				}
			}
		}

		$faile_sql = 'SELECT `join`.*,`task`.title,`task`.reward_data AS `poster_reward`,`task`.titleicon,`task`.poster_type FROM ' . tablename('ewei_shop_task_join') . ' AS `join` LEFT JOIN ' . tablename('ewei_shop_task_poster') . ' AS `task` ON `join`.task_id=`task`.`id` WHERE `join`.`failtime`<=' . $now_time . ' AND `join`.`join_user`="' . $openid . '" AND `join`.uniacid=' . $_W['uniacid'] . ' AND `join`.`is_reward`=0 AND `task`.`is_delete` = 0 ORDER BY `join`.`addtime` DESC LIMIT 0,15';
		$faile_complete = pdo_fetchall($faile_sql);

		foreach ($faile_complete as $key => $val) {
			if ($val['poster_type'] == 1) {
				$val['reward_data'] = unserialize($val['poster_reward']);
				$recward = $val['reward_data']['rec'];
				if (isset($recward['credit']) && (0 < $recward['credit'])) {
					$faile_complete[$key]['is_credit'] = 1;
				}

				if (isset($recward['money']['num']) && (0 < $recward['money']['num'])) {
					$faile_complete[$key]['is_money'] = 1;
				}

				if (isset($recward['bribery']) && (0 < $recward['bribery'])) {
					$faile_complete[$key]['is_bribery'] = 1;
				}

				if (isset($recward['goods']) && count($recward['goods'])) {
					$faile_complete[$key]['is_goods'] = 1;
				}

				if (isset($recward['coupon']['total']) && (0 < $recward['coupon']['total'])) {
					$faile_complete[$key]['is_coupon'] = 1;
				}
			}
			else {
				if ($val['poster_type'] == 2) {
					$val['reward_data'] = unserialize($val['poster_reward']);
					$recward = $val['reward_data']['rec'];

					foreach ($recward as $k => $v) {
						if (isset($v['credit']) && (0 < $v['credit'])) {
							$faile_complete[$key]['is_credit'] = 1;
						}

						if (isset($v['money']['num']) && (0 < $v['money']['num'])) {
							$faile_complete[$key]['is_credit'] = 1;
						}

						if (isset($v['bribery']) && (0 < $v['bribery'])) {
							$faile_complete[$key]['is_money'] = 1;
						}

						if (isset($v['goods']) && count($v['goods'])) {
							$faile_complete[$key]['is_goods'] = 1;
						}

						if (isset($v['coupon']['total']) && (0 < $v['coupon']['total'])) {
							$faile_complete[$key]['is_coupon'] = 1;
						}
					}
				}
			}
		}

		return array($task_list, $task_running, $task_complete, $faile_complete);
	}

	public function gettask()
	{
		global $_W;
		global $_GPC;
		$content = trim($_GPC['content']);
		$timeout = 10;
		$url = mobileUrl('task/build', array('timestamp' => TIMESTAMP), true);
		ihttp_request($url, array('openid' => $_W['openid'], 'content' => urlencode($content)), array(), $timeout);
		show_json(1);
	}

	public function getreward()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$rewarded = pdo_get('ewei_shop_task_extension_join', array('uniacid' => $_W['uniacid'], 'id' => $_GPC['id']), array('rewarded'));
		$rewarded = unserialize($rewarded['rewarded']);
		$this->model->sendReward($rewarded, 1);
		show_json(1, '奖励已发放');
	}

	private function getpostericon($id)
	{
		global $_W;
		global $_GPC;
		return pdo_fetchcolumn('SELECT titleicon FROM ' . tablename('ewei_shop_task_poster') . ' WHERE id = :id AND uniacid = :uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
	}

	private function checkJoined($taskid)
	{
		global $_W;
		$sql = 'SELECT COUNT(*) FROM ' . tablename('ewei_shop_task_extension_join') . ' WHERE openid = :openid AND taskid = :taskid';
		return pdo_fetchcolumn($sql, array(':openid' => $_W['openid'], ':taskid' => $taskid));
	}

	private function getDesc()
	{
		global $_W;
		$sql = 'SELECT `data` FROM ' . tablename('ewei_shop_task_default') . ' WHERE uniacid = :uniacid';
		$data = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid']));
		$arr = unserialize($data);
		return unserialize($arr['taskinfo']);
	}
}

?>
