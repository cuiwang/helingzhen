<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Index_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$params = array(':uniacid' => $_W['uniacid']);
		$condition = ' and uniacid=:uniacid and `is_delete`=0 ';

		if (!empty($_GPC['keyword'])) {
			$_GPC['keyword'] = trim($_GPC['keyword']);
			$condition .= ' AND `title` LIKE :title';
			$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
		}

		$list = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_task_poster') . ' WHERE 1 ' . $condition . ' ORDER BY createtime desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
		unset($row);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_task_poster') . ' where 1 ' . $condition . ' ', $params);
		$pager = pagination2($total, $pindex, $psize);

		foreach ($list as $key => $val) {
			$viewcount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_task_joiner') . ' where uniacid=:uniacid and task_id=:task_id and task_type=' . $val['poster_type'], array(':uniacid' => $_W['uniacid'], ':task_id' => $val['id']));
			$list[$key]['viewcount'] = $viewcount;
		}

		include $this->template();
	}

	public function add()
	{
		global $_GPC;

		if ($_GPC['task_type'] == 1) {
			$this->post();
		}
		else {
			if ($_GPC['task_type'] == 2) {
				$this->rankpost();
			}
		}
	}

	public function edit()
	{
		global $_GPC;

		if ($_GPC['task_type'] == 1) {
			$this->post();
		}
		else {
			if ($_GPC['task_type'] == 2) {
				$this->rankpost();
			}
		}
	}

	protected function post()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_task_poster') . ' WHERE id =:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));

		if (!empty($item)) {
			$data = json_decode(str_replace('&quot;', '\'', $item['data']), true);
		}

		if ($_W['ispost']) {
			if (!empty($id)) {
				if (intval($_GPC['needcount']) < $item['needcount']) {
					$task_count = pdo_fetchcolumn('select COUNT(*) from ' . tablename('ewei_shop_task_join') . ' where uniacid=:uniacid and task_id=:task_id and task_type=1 and failtime>' . time(), array(':uniacid' => $_W['uniacid'], ':task_id' => $id));

					if ($task_count) {
						show_json(0, '由于已经有人参加所以推广目标人数不能低于当前人数');
						exit();
					}
				}
			}

			load()->model('account');
			$data = array('uniacid' => $_W['uniacid'], 'days' => intval($_GPC['days']) * 24 * 3600, 'reward_days' => intval($_GPC['reward_days']) * 24 * 3600, 'title' => trim($_GPC['title']), 'titleicon' => trim($_GPC['titleicon']), 'poster_banner' => trim($_GPC['poster_banner']), 'keyword' => trim($_GPC['keyword']), 'bg' => save_media($_GPC['bg']), 'data' => htmlspecialchars_decode($_GPC['data']), 'resptype' => trim($_GPC['resptype']), 'resptext' => trim($_GPC['resptext']), 'resptitle' => trim($_GPC['resptitle']), 'respthumb' => trim($_GPC['respthumb']), 'respdesc' => trim($_GPC['respdesc']), 'respurl' => trim($_GPC['respurl']), 'createtime' => time(), 'oktext' => trim($_GPC['oktext']), 'waittext' => trim($_GPC['waittext']), 'bedown' => intval($_GPC['bedown']), 'beagent' => intval($_GPC['beagent']), 'getposter' => trim($_GPC['getposter']), 'timestart' => strtotime($_GPC['time']['start']), 'timeend' => strtotime($_GPC['time']['end']), 'is_repeat' => intval($_GPC['is_repeat']), 'status' => intval($_GPC['status']), 'is_goods' => intval($_GPC['is_goods']), 'autoposter' => intval($_GPC['autoposter']), 'starttext' => trim($_GPC['starttext']), 'endtext' => trim($_GPC['endtext']), 'needcount' => intval($_GPC['needcount']), 'poster_type' => isset($_GPC['poster_type']) ? intval($_GPC['poster_type']) : 1);
			$reward = array();
			$rec_reward = htmlspecialchars_decode($_GPC['rec_reward_data']);
			$rec_reward = json_decode($rec_reward, 1);
			$rec_data = array();

			if (!empty($rec_reward)) {
				foreach ($rec_reward as $val) {
					if ($val['type'] == 1) {
						$rec_data['credit'] = intval($val['num']);
					}
					else if ($val['type'] == 2) {
						$rec_data['money']['num'] = intval($val['num']);
						$rec_data['money']['type'] = intval($val['moneytype']);
					}
					else if ($val['type'] == 3) {
						$rec_data['bribery'] = intval($val['num']);
					}
					else if ($val['type'] == 4) {
						$goods_id = intval($val['goods_id']);
						$goods_name = trim($val['goods_name']);
						$goods_price = floatval($val['goods_price']);
						$goods_total = intval($val['goods_total']);
						$goods_spec = intval($val['goods_spec']);
						$goods_specname = trim($val['goods_specname']);

						if (isset($rec_data['goods'][$goods_id]['spec'])) {
							$oldspec = $rec_data['goods'][$goods_id]['spec'];
						}
						else {
							$oldspec = array();
						}

						$rec_data['goods'][$goods_id] = array('id' => $goods_id, 'title' => $goods_name, 'marketprice' => $goods_price, 'total' => $goods_total, 'spec' => $oldspec);

						if (0 < $goods_spec) {
							$rec_data['goods'][$goods_id]['spec'][$goods_spec] = array('goods_spec' => $goods_spec, 'goods_specname' => $goods_specname, 'marketprice' => $goods_price, 'total' => $goods_total);
						}
						else {
							$rec_data['goods'][$goods_id]['spec'] = '';
						}
					}
					else {
						if ($val['type'] == 5) {
							$coupon_id = intval($val['coupon_id']);
							$coupon_name = trim($val['coupon_name']);
							$coupon_num = intval($val['coupon_num']);
							$rec_data['coupon'][$coupon_id] = array('id' => $coupon_id, 'couponname' => $coupon_name, 'couponnum' => $coupon_num);

							if (isset($rec_data['coupon']['total'])) {
								$rec_data['coupon']['total'] += $coupon_num;
							}
							else {
								$rec_data['coupon']['total'] = 0;
								$rec_data['coupon']['total'] += $coupon_num;
							}
						}
					}
				}
			}

			$sub_reward = htmlspecialchars_decode($_GPC['sub_reward_data']);
			$sub_reward = json_decode($sub_reward, 1);
			$sub_data = array();

			if (!empty($sub_reward)) {
				foreach ($sub_reward as $val) {
					if ($val['type'] == 1) {
						$sub_data['credit'] = intval($val['num']);
					}
					else if ($val['type'] == 2) {
						$sub_data['money']['num'] = intval($val['num']);
						$sub_data['money']['type'] = intval($val['moneytype']);
					}
					else if ($val['type'] == 3) {
						$sub_data['bribery'] = intval($val['num']);
					}
					else {
						if ($val['type'] == 5) {
							$coupon_id = intval($val['coupon_id']);
							$coupon_name = trim($val['coupon_name']);
							$coupon_num = intval($val['coupon_num']);
							$sub_data['coupon'][$coupon_id] = array('id' => $coupon_id, 'couponname' => $coupon_name, 'couponnum' => $coupon_num);

							if (isset($sub_data['coupon']['total'])) {
								$sub_data['coupon']['total'] += $coupon_num;
							}
							else {
								$sub_data['coupon']['total'] = 0;
								$sub_data['coupon']['total'] += $coupon_num;
							}
						}
					}
				}
			}

			$reward['rec'] = $rec_data;
			$reward['sub'] = $sub_data;
			$data['reward_data'] = serialize($reward);
			$keyword = m('common')->keyExist($data['keyword']);
			if (($item['keyword'] != $data['keyword']) && !empty($keyword)) {
				if ($keyword['name'] != ('ewei_shopv2:task:' . $id)) {
					show_json(0, '关键字已存在!');
				}
			}

			if (!empty($id)) {
				pdo_update('ewei_shop_task_poster', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
				$updatesql = 'UPDATE ' . tablename('ewei_shop_task_join') . ' SET  failtime = addtime+' . $data['days'] . ',needcount=:needcount WHERE uniacid = :uniacid AND task_id=:task_id AND task_type=1 AND failtime>' . time();
				pdo_query($updatesql, array(':needcount' => $data['needcount'], ':uniacid' => $_W['uniacid'], ':task_id' => $id));
				plog('task.edit', '修改活动海报 ID: ' . $id . '<br>' . ($data['isopen'] ? '允许非分销商生成自己的海报 -- 是<br>' : '允许非分销商生成自己的海报 -- 否<br>') . ($data['bedown'] ? '扫码关注成为下线 -- 是<br>' : '扫码关注成为下线 -- 否<br>') . ($data['beagent'] ? '扫码关注成为分销商 -- 是' : '扫码关注成为分销商 -- 否'));
			}
			else {
				pdo_insert('ewei_shop_task_poster', $data);
				$id = pdo_insertid();
				plog('task.add', '修改活动海报 ID: ' . $id . '<br>' . ($data['isopen'] ? '允许非分销商生成自己的海报<br>' : '不允许非分销商生成自己的海报<br>') . ($data['bedown'] ? '扫码关注成为下线 -- 是<br>' : '扫码关注成为下线 -- 否<br>') . ($data['beagent'] ? '扫码关注成为分销商 -- 是' : '扫码关注成为分销商 -- 否'));
			}

			$rule = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shopv2', ':name' => 'ewei_shopv2:task:' . $id));
			$rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shopv2:task:' . $id, 'module' => 'ewei_shopv2', 'displayorder' => 0, 'status' => $data['status']);
			$keyword_data = array('uniacid' => $_W['uniacid'], 'module' => 'ewei_shopv2', 'content' => trim($data['keyword']), 'type' => 1, 'displayorder' => 0, 'status' => $data['status']);

			if (empty($rule)) {
				pdo_insert('rule', $rule_data);
				$keyword_data['rid'] = pdo_insertid();
				pdo_insert('rule_keyword', $keyword_data);
			}
			else {
				pdo_update('rule_keyword', $keyword_data, array('rid' => $rule['id']));
			}

			$ruleauto = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shopv2', ':name' => 'ewei_shopv2:task:auto'));

			if (empty($ruleauto)) {
				$rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shopv2:task:auto', 'module' => 'ewei_shopv2', 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule', $rule_data);
				$rid = pdo_insertid();
				$keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'ewei_shopv2', 'content' => 'EWEI_SHOPV2_TASK', 'type' => 1, 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule_keyword', $keyword_data);
			}

			show_json(1, array('url' => webUrl('task')));
		}

		$imgroot = $_W['attachurl'];

		if (empty($_W['setting']['remote'])) {
			setting_load('remote');
		}

		if (!empty($_W['setting']['remote']['type'])) {
			$imgroot = $_W['attachurl_remote'];
		}

		if (empty($item['timestart'])) {
			$starttime = time();
			$endtime = strtotime(date('Y-m-d H:i', $starttime) . '+30 days');
		}
		else {
			$type = $item['coupontype'];
			$starttime = $item['timestart'];
			$endtime = $item['timeend'];
		}

		if (!empty($item)) {
			$reward = unserialize($item['reward_data']);
			$rec_reward = $reward['rec'];
			$sub_reward = $reward['sub'];
		}
		else {
			$rec_reward = '';
			$sub_reward = '';
		}

		$default_text = pdo_fetchcolumn('SELECT `data` FROM ' . tablename('ewei_shop_task_default') . ' WHERE uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

		if (!empty($default_text)) {
			$default_text = unserialize($default_text);

			if (empty($item['starttext'])) {
				$item['starttext'] = $default_text['poster']['starttext'];
			}

			if (empty($item['endtext'])) {
				$item['endtext'] = $default_text['poster']['endtext'];
			}

			if (empty($item['waittext'])) {
				$item['waittext'] = $default_text['poster']['waittext'];
			}

			if (empty($item['opentext'])) {
				$item['opentext'] = $default_text['poster']['opentext'];
			}
		}

		include $this->template();
	}

	protected function rankpost()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_task_poster') . ' WHERE id =:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));

		if (!empty($item)) {
			$data = json_decode(str_replace('&quot;', '\'', $item['data']), true);
		}

		if ($_W['ispost']) {
			load()->model('account');
			$data = array('uniacid' => $_W['uniacid'], 'days' => intval($_GPC['days']) * 24 * 3600, 'reward_days' => intval($_GPC['reward_days']) * 24 * 3600, 'title' => trim($_GPC['title']), 'titleicon' => trim($_GPC['titleicon']), 'poster_banner' => trim($_GPC['poster_banner']), 'keyword' => trim($_GPC['keyword']), 'bg' => save_media($_GPC['bg']), 'data' => htmlspecialchars_decode($_GPC['data']), 'resptype' => trim($_GPC['resptype']), 'resptext' => trim($_GPC['resptext']), 'resptitle' => trim($_GPC['resptitle']), 'respthumb' => trim($_GPC['respthumb']), 'respdesc' => trim($_GPC['respdesc']), 'respurl' => trim($_GPC['respurl']), 'createtime' => time(), 'oktext' => trim($_GPC['oktext']), 'waittext' => trim($_GPC['waittext']), 'bedown' => intval($_GPC['bedown']), 'beagent' => intval($_GPC['beagent']), 'getposter' => trim($_GPC['getposter']), 'timestart' => strtotime($_GPC['time']['start']), 'timeend' => strtotime($_GPC['time']['end']), 'is_repeat' => intval($_GPC['is_repeat']), 'status' => intval($_GPC['status']), 'is_goods' => intval($_GPC['is_goods']), 'autoposter' => intval($_GPC['autoposter']), 'starttext' => trim($_GPC['starttext']), 'endtext' => trim($_GPC['endtext']), 'needcount' => 0, 'poster_type' => isset($_GPC['poster_type']) ? intval($_GPC['poster_type']) : 1);
			$reward = array();
			$rec_reward = htmlspecialchars_decode($_GPC['rec_reward_data']);
			$rec_reward_rank = htmlspecialchars_decode($_GPC['rec_reward_rank']);
			$rec_reward = json_decode($rec_reward, 1);
			$rec_reward_rank = json_decode($rec_reward_rank, 1);
			$rec_data = array();

			if (!empty($rec_reward)) {
				foreach ($rec_reward as $val) {
					$rank = intval($val['rank']);

					if ($val['type'] == 1) {
						$rec_data[$rank]['credit'] = intval($val['num']);
					}
					else if ($val['type'] == 2) {
						$rec_data[$rank]['money']['num'] = intval($val['num']);
						$rec_data[$rank]['money']['type'] = intval($val['moneytype']);
					}
					else if ($val['type'] == 3) {
						$rec_data[$rank]['bribery'] = intval($val['num']);
					}
					else if ($val['type'] == 4) {
						$goods_id = intval($val['goods_id']);
						$goods_name = trim($val['goods_name']);
						$goods_price = floatval($val['goods_price']);
						$goods_total = intval($val['goods_total']);
						$goods_spec = intval($val['goods_spec']);
						$goods_specname = trim($val['goods_specname']);

						if (isset($rec_data[$rank]['goods'][$goods_id]['spec'])) {
							$oldspec = $rec_data[$rank]['goods'][$goods_id]['spec'];
						}
						else {
							$oldspec = array();
						}

						$rec_data[$rank]['goods'][$goods_id] = array('id' => $goods_id, 'title' => $goods_name, 'marketprice' => $goods_price, 'total' => $goods_total, 'spec' => $oldspec);

						if (0 < $goods_spec) {
							$rec_data[$rank]['goods'][$goods_id]['spec'][$goods_spec] = array('goods_spec' => $goods_spec, 'goods_specname' => $goods_specname, 'marketprice' => $goods_price, 'total' => $goods_total);
						}
						else {
							$rec_data[$rank]['goods'][$goods_id]['spec'] = '';
						}
					}
					else {
						if ($val['type'] == 5) {
							$coupon_id = intval($val['coupon_id']);
							$coupon_name = trim($val['coupon_name']);
							$coupon_num = intval($val['coupon_num']);
							$rec_data[$rank]['coupon'][$coupon_id] = array('id' => $coupon_id, 'couponname' => $coupon_name, 'couponnum' => $coupon_num);

							if (isset($rec_data[$rank]['coupon']['total'])) {
								$rec_data[$rank]['coupon']['total'] += $coupon_num;
							}
							else {
								$rec_data[$rank]['coupon']['total'] = 0;
								$rec_data[$rank]['coupon']['total'] += $coupon_num;
							}
						}
					}
				}
			}

			if (!empty($rec_reward_rank)) {
				$rank_count = 1;

				foreach ($rec_reward_rank as $key => $value) {
					$rank_state = intval($value['rank_state']);

					if ($rank_state == 0) {
						unset($rec_data[$value['rank']]);
					}
					else {
						if ($rank_count < intval($value['rank'])) {
							$rank_count = intval($value['rank']);
						}

						$needcount = intval($value['needcount']);

						if (0 < $needcount) {
							$rec_data[$value['rank']]['needcount'] = $needcount;
							$rec_data[$value['rank']]['rank'] = intval($value['rank']);
						}
						else {
							unset($rec_data[$value['rank']]);
						}
					}
				}

				$i = 1;

				while ($i <= $rank_count) {
					if (!isset($rec_data[$i])) {
						$rec_data[$i] = array();
					}

					++$i;
				}
			}

			if (!empty($rec_data)) {
				ksort($rec_data);
			}

			$sub_reward = htmlspecialchars_decode($_GPC['sub_reward_data']);
			$sub_reward = json_decode($sub_reward, 1);
			$sub_data = array();

			if (!empty($sub_reward)) {
				foreach ($sub_reward as $val) {
					if ($val['type'] == 1) {
						$sub_data['credit'] = intval($val['num']);
					}
					else if ($val['type'] == 2) {
						$sub_data['money']['num'] = intval($val['num']);
						$sub_data['money']['type'] = intval($val['moneytype']);
					}
					else if ($val['type'] == 3) {
						$sub_data['bribery'] = intval($val['num']);
					}
					else {
						if ($val['type'] == 5) {
							$coupon_id = intval($val['coupon_id']);
							$coupon_name = trim($val['coupon_name']);
							$coupon_num = intval($val['coupon_num']);
							$sub_data['coupon'][$coupon_id] = array('id' => $coupon_id, 'couponname' => $coupon_name, 'couponnum' => $coupon_num);

							if (isset($sub_data['coupon']['total'])) {
								$sub_data['coupon']['total'] += $coupon_num;
							}
							else {
								$sub_data['coupon']['total'] = 0;
								$sub_data['coupon']['total'] += $coupon_num;
							}
						}
					}
				}
			}

			$reward['rec'] = $rec_data;
			$reward['sub'] = $sub_data;
			$data['reward_data'] = serialize($reward);
			$keyword = m('common')->keyExist($data['keyword']);
			if (($item['keyword'] != $data['keyword']) && !empty($keyword)) {
				if ($keyword['name'] != ('ewei_shopv2:task:' . $id)) {
					show_json(0, '关键字已存在!');
				}
			}

			if (!empty($id)) {
				pdo_update('ewei_shop_task_poster', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
				$updatesql = 'UPDATE ' . tablename('ewei_shop_task_join') . ' SET  failtime = addtime+' . $data['days'] . ',needcount=:needcount WHERE uniacid = :uniacid AND task_id=:task_id AND task_type=1 AND failtime>' . time();
				pdo_query($updatesql, array(':needcount' => $data['needcount'], ':uniacid' => $_W['uniacid'], ':task_id' => $id));
				plog('task.edit', '修改活动海报 ID: ' . $id . '<br>' . ($data['isopen'] ? '允许非分销商生成自己的海报 -- 是<br>' : '允许非分销商生成自己的海报 -- 否<br>') . ($data['bedown'] ? '扫码关注成为下线 -- 是<br>' : '扫码关注成为下线 -- 否<br>') . ($data['beagent'] ? '扫码关注成为分销商 -- 是' : '扫码关注成为分销商 -- 否'));
			}
			else {
				pdo_insert('ewei_shop_task_poster', $data);
				$id = pdo_insertid();
				plog('task.add', '修改活动海报 ID: ' . $id . '<br>' . ($data['isopen'] ? '允许非分销商生成自己的海报<br>' : '不允许非分销商生成自己的海报<br>') . ($data['bedown'] ? '扫码关注成为下线 -- 是<br>' : '扫码关注成为下线 -- 否<br>') . ($data['beagent'] ? '扫码关注成为分销商 -- 是' : '扫码关注成为分销商 -- 否'));
			}

			$rule = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shopv2', ':name' => 'ewei_shopv2:task:' . $id));
			$rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shopv2:task:' . $id, 'module' => 'ewei_shopv2', 'displayorder' => 0, 'status' => $data['status']);
			$keyword_data = array('uniacid' => $_W['uniacid'], 'module' => 'ewei_shopv2', 'content' => trim($data['keyword']), 'type' => 1, 'displayorder' => 0, 'status' => $data['status']);

			if (empty($rule)) {
				pdo_insert('rule', $rule_data);
				$keyword_data['rid'] = pdo_insertid();
				pdo_insert('rule_keyword', $keyword_data);
			}
			else {
				pdo_update('rule_keyword', $keyword_data, array('rid' => $rule['id']));
			}

			$ruleauto = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'ewei_shopv2', ':name' => 'ewei_shopv2:task:auto'));

			if (empty($ruleauto)) {
				$rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'ewei_shopv2:task:auto', 'module' => 'ewei_shopv2', 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule', $rule_data);
				$rid = pdo_insertid();
				$keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'ewei_shopv2', 'content' => 'EWEI_SHOPV2_TASK', 'type' => 1, 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule_keyword', $keyword_data);
			}

			show_json(1, array('url' => webUrl('task')));
		}

		$imgroot = $_W['attachurl'];

		if (empty($_W['setting']['remote'])) {
			setting_load('remote');
		}

		if (!empty($_W['setting']['remote']['type'])) {
			$imgroot = $_W['attachurl_remote'];
		}

		if (empty($item['timestart'])) {
			$starttime = time();
			$endtime = strtotime(date('Y-m-d H:i', $starttime) . '+30 days');
		}
		else {
			$type = $item['coupontype'];
			$starttime = $item['timestart'];
			$endtime = $item['timeend'];
		}

		if (!empty($item)) {
			$reward = unserialize($item['reward_data']);
			$rec_reward = $reward['rec'];
			$sub_reward = $reward['sub'];
		}
		else {
			$rec_reward = '';
			$sub_reward = '';
		}

		$default_text = pdo_fetchcolumn('SELECT `data` FROM ' . tablename('ewei_shop_task_default') . ' WHERE uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

		if (!empty($default_text)) {
			$default_text = unserialize($default_text);

			if (empty($item['starttext'])) {
				$item['starttext'] = $default_text['poster']['starttext'];
			}

			if (empty($item['endtext'])) {
				$item['endtext'] = $default_text['poster']['endtext'];
			}

			if (empty($item['waittext'])) {
				$item['waittext'] = $default_text['poster']['waittext'];
			}

			if (empty($item['opentext'])) {
				$item['opentext'] = $default_text['poster']['opentext'];
			}
		}

		include $this->template('task/rankpost');
	}

	public function delete()
	{
		global $_GPC;
		global $_W;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = (is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0);
		}

		$posters = pdo_fetchall('SELECT id,title,keyword FROM ' . tablename('ewei_shop_task_poster') . ' WHERE id in ( ' . $id . ' ) and uniacid=' . $_W['uniacid']);

		foreach ($posters as $poster) {
			$rule = pdo_fetchall('SELECT id,rid FROM ' . tablename('rule_keyword') . ' WHERE uniacid=:uniacid AND content IN (\'' . $poster['keyword'] . '\')', array(':uniacid' => $_W['uniacid']), 'rid');
			$rule = array_keys($rule);
			m('common')->delrule($rule);
			pdo_delete('ewei_shop_task_poster', array('id' => $poster['id'], 'uniacid' => $_W['uniacid']));
			pdo_delete('ewei_shop_task_log', array('taskid' => $poster['id'], 'task_type' => 1, 'uniacid' => $_W['uniacid']));
			pdo_delete('ewei_shop_task_join', array('taskid' => $poster['id'], 'task_type' => 1, 'uniacid' => $_W['uniacid']));
			plog('task.delete', '删除任务海报 ID: ' . $id . ' 海报名称: ' . $poster['title']);
		}

		show_json(1, array('url' => webUrl('task')));
	}

	public function clear()
	{
		global $_W;
		global $_GPC;
		load()->func('file');
		@rmdirs(IA_ROOT . '/addons/ewei_shopv2/data/task/poster/' . $_W['uniacid']);
		@rmdirs(IA_ROOT . '/addons/ewei_shopv2/data/task/qrcode/' . $_W['uniacid']);
		$acid = pdo_fetchcolumn('SELECT acid FROM ' . tablename('account_wechats') . ' WHERE `uniacid`=:uniacid LIMIT 1', array(':uniacid' => $_W['uniacid']));
		pdo_update('ewei_shop_task_poster_qr', array('mediaid' => ''), array('acid' => $acid));
		plog('task.clear', '清除任务海报缓存');
		show_json(1, array('url' => webUrl('task', array('op' => 'display'))));
	}

	public function delreward()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$type = intval($_GPC['del_type']);
		$datatype = intval($_GPC['data_type']);
		$data = $_GPC['data_value'];

		if ($type == 1) {
			$item = pdo_fetch('SELECT `id`,`reward_data` FROM ' . tablename('ewei_shop_task_poster') . ' WHERE id =:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
			$item['reward_data'] = unserialize($item['reward_data']);

			if ($datatype == 1) {
				$item['reward_data']['rec']['credit'] = 0;
			}

			if ($datatype == 2) {
				$item['reward_data']['rec']['money']['num'] = 0;
			}

			if ($datatype == 3) {
				$item['reward_data']['rec']['bribery'] = 0;
			}

			if ($datatype == 4) {
				$is_spec = strpos($data, '-');

				if ($is_spec) {
					$data = explode('_', $data);

					if (isset($item['reward_data']['rec']['goods'][$data[0]]['spec'][$data[1]])) {
						unset($item['reward_data']['rec']['goods'][$data[0]]['spec'][$data[1]]);
					}

					if (isset($item['reward_data']['rec']['goods'][$data[0]]['spec']) && empty($item['reward_data']['rec']['goods'][$data[0]]['spec'])) {
						unset($item['reward_data']['rec']['goods'][$data[0]]);
					}
				}
				else {
					if (isset($item['reward_data']['rec']['goods'][$data[0]])) {
						unset($item['reward_data']['rec']['goods'][$data[0]]);
					}
				}
			}

			if ($datatype == 5) {
				if (isset($item['reward_data']['rec']['coupon'][$data])) {
					unset($item['reward_data']['rec']['coupon'][$data]);
				}
			}

			$item['reward_data'] = serialize($item['reward_data']);
			pdo_update('ewei_shop_task_poster', array('reward_data' => $item['reward_data']), array('id' => $id, 'uniacid' => $_W['uniacid']));
			$item['reward_data'] = unserialize($item['reward_data']);
			echo json_encode(array('status' => 1, 'info' => $item['reward_data']['rec']));
			exit();
		}
		else if ($type == 2) {
			$item = pdo_fetch('SELECT `id`,`reward_data` FROM ' . tablename('ewei_shop_task_poster') . ' WHERE id =:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
			$item['reward_data'] = unserialize($item['reward_data']);

			if ($datatype == 1) {
				$item['reward_data']['sub']['credit'] = 0;
			}

			if ($datatype == 2) {
				$item['reward_data']['sub']['money']['num'] = 0;
			}

			if ($datatype == 3) {
				$item['reward_data']['sub']['bribery'] = 0;
			}

			if ($datatype == 5) {
				if (isset($item['reward_data']['sub']['coupon'][$data])) {
					unset($item['reward_data']['sub']['coupon'][$data]);
				}
			}

			$item['reward_data'] = serialize($item['reward_data']);
			pdo_update('ewei_shop_task_poster', array('reward_data' => $item['reward_data']), array('id' => $id, 'uniacid' => $_W['uniacid']));
			$item['reward_data'] = unserialize($item['reward_data']);
			echo json_encode(array('status' => 1, 'info' => $item['reward_data']['sub']));
			exit();
		}
		else {
			echo json_encode(array('status' => 0, 'info' => '删除类型不存在'));
			exit();
		}
	}
}

?>
