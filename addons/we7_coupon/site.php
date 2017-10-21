<?php
/**
 * 微擎卡券模块微站定义
 *
 * @author 微擎团队
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

include "model/activity.mod.php";

class We7_couponModuleSite extends WeModuleSite {

	public $settings;

	public function __construct() {
		global $_W;
		load()->model('mc');
		load()->model('activity');
		load()->model('clerk');
		load()->model('user');
		load()->model('card');
		activity_coupon_type_init();
		$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
		$this->settings = $setting;
	}

	public function BuildCardExt($id, $openid = '', $type = 'coupon') {
		load()->classs('coupon');
		$coupon_api = new Coupon();
		$acid = $coupon_api->account['acid'];
		$card_id = pdo_getcolumn('coupon', array('acid' => $acid, 'id' => $id), 'card_id');
		if (empty($card_id)) {
			return error(-1, '卡券id不合法');
		}
		$time = TIMESTAMP;
		$sign = array($card_id, $time);
		$signature = $coupon_api->SignatureCard($sign);
		if (is_error($signature)) {
			return $signature;
		}
		$cardExt = array('timestamp' => $time, 'signature' => $signature);
		$cardExt = json_encode($cardExt);
		return array('card_id' => $card_id, 'card_ext' => $cardExt);
	}

	public function getHomeTiles() {
		global $_W;
		$urls = array();
		$urls = array(
			array(
				'title' => '我的卡券',
				'url' => $this->createMobileurl('activity', array('op' => 'mine')),	
			),
			array(
				'title' => '我的兑换',
				'url' => $this->createMobileurl('activity', array('activity_type' => 'goods', 'op' => 'mine'))
			),
			array(
				'title' => '消息',
				'url' => $this->createMobileurl('card', array('op' => 'notice'))
			),
			array(
				'title' => '签到',
				'url' => $this->createMobileurl('card', array('op' => 'sign_display'))
			)
		);
		return $urls;
	}

	public function doWebMembercard() {
		global $_GPC, $_W;
		pdo_delete('modules_bindings', array('module' => 'we7_coupon', 'entry' => 'menu', 'do' => 'clerkdeskmenu'));
		$setting = $this->settings;
		load()->model('mc');
		load()->model('activity');
		activity_coupon_type_init();
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'editor';
		$unisetting = uni_setting_load('creditnames');
		if (empty($unisetting['creditnames'])) {
			$unisetting['credit1'] = array('title' => '积分', 'enabled' => 1);
			$unisetting['credit2'] = array('title' => '余额', 'enabled' => 1);
		}
		if ($op == 'editor') {
			if (!empty($_GPC['wapeditor'])) {
				$params = $_GPC['wapeditor']['params'];
				if (empty($params)) {
					message('请您先设计手机端页面.', '', 'error');
				}
				$params = json_decode(ihtml_entity_decode($params), true);
				if (empty($params)) {
					message('请您先设计手机端页面.', '', 'error');
				}
				if (!empty($params)) {
					foreach ($params as $key => &$value) {
						$params_new[$value['id']] = $value;
						if ($value['id'] == 'cardRecharge') {
							$recharges_key = $key;
						}
						if ($value['id'] == 'cardBasic') {
							$value['params']['description'] = str_replace(array("\r\n", "\n"), '<br/>', $value['params']['description']);
							$value['originParams']['description'] = str_replace(array("\r\n", "\n"), '<br/>', $value['originParams']['description']);
						}
					}
					unset($value);
				}
				if (!empty($params[$recharges_key])) {
					foreach ($params[$recharges_key]['params']['recharges'] as &$row) {
						if ($row['backtype'] == '0') {
							$row['backunit'] = '元';
						} else {
							$row['backunit'] = '积分';
						}
					}
					unset($row);
				}
				$html = htmlspecialchars_decode($_GPC['wapeditor']['html'], ENT_QUOTES);
				$html = str_replace(array("{\$_W['uniacid']}", "{\$_W['acid']}"), array($_W['uniacid'], $_W['acid']), $html);
				$basic = $params_new['cardBasic']['params'];
				$activity = $params_new['cardActivity']['params'];
				$nums = $params_new['cardNums']['params'];
				$times = $params_new['cardTimes']['params'];
				$recharges = $params_new['cardRecharge']['params'];
				$title = trim($basic['title']) ? trim($basic['title']) : message('名称不能为空');
				$format_type = 1;
				$format = trim($basic['format']);
				if (!empty($basic['fields'])) {
					foreach($basic['fields'] as $field) {
						if(!empty($field['title']) && !empty($field['bind'])) {
							$fields[] = $field;
						}
					}
				}
				if ($basic['background']['type'] == 'system') {
					$image = pathinfo($basic['background']['image']);
					$basic['background']['image'] = $image['filename'];
				}
				if (!empty($recharges['recharges'])) {
					foreach ($recharges['recharges'] as $row) {
						if ($recharges['recharge_type'] == 1 && ($row['condition'] <= 0 || $row['back'] <= 0)) {
							message('充值优惠设置数值不能为负数或零', referer(), 'error');
						}
					}
				}
				if ($activity['grant_rate'] < 0) {
					message('付款返积分比率不能为负数', referer(), 'error');
				}
				$update = array(
					'title' => $title,
					'format_type' => $basic['format_type'],
					'format' => $format,
					'color' => iserializer($basic['color']),
					'background' => iserializer(array(
						'background' => $basic['background']['type'],
						'image' => $basic['background']['image'],
					)),
					'logo' => $basic['logo'],
					'description' => trim($basic['description']),
					'grant_rate' => intval($activity['grant_rate']),
					'offset_rate' => intval($basic['offset_rate']),
					'offset_max' => intval($basic['offset_max']),
					'fields' => iserializer($fields),
					'grant' => iserializer(
						array(
							'credit1' => intval($basic['grant']['credit1']),
							'credit2' => intval($basic['grant']['credit2']),
					'coupon' => $basic['grant']['coupon'],
						)
					),
					'discount_type' => intval($activity['discount_type']),
					'nums_status' => intval($nums['nums_status']),
					'nums_text' => trim($nums['nums_text']),
					'times_status' => intval($times['times_status']),
					'times_text' => trim($times['times_text']),
					'params' => stripslashes(ijson_encode($params, JSON_UNESCAPED_UNICODE)),
					'html' => $html
				);
				$grant = iunserializer($update['grant']);
				if ($grant['credit1'] < 0 || $grant['credit2'] < 0) {
					message('领卡赠送积分或余额不能为负数', referer(), 'error');
				}
				if ($update['offset_rate'] < 0 || $update['offset_max'] < 0) {
					message('抵现比率的数值不能为负数或零', referer(), 'error');
				}
				if ($update['discount_type'] != 0 && !empty($activity['discounts'])) {
					$update['discount'] = array();
					foreach ($activity['discounts'] as $discount) {
						if ($update['discount_type'] == 1) {
							if (!empty($discount['condition_1']) || !empty($discount['discount_1'])) {
								if ($discount['condition_1'] < 0 || $discount['discount_1'] < 0) {
									message('消费优惠设置数值不能为负数', referer(), 'error');
								}
							}
						} else {
							if (!empty($discount['condition_2']) || !empty($discount['discount_2'])) {
								if ($discount['condition_2'] < 0 || $discount['discount_2'] < 0) {
									message('消费优惠设置数值不能为负数', referer(), 'error');
								}
							}
						}
						$groupid = intval($discount['groupid']);
						if ($groupid <= 0) continue;
						$update['discount'][$groupid] = array(
							'condition_1' => trim($discount['condition_1']),
							'discount_1' => trim($discount['discount_1']),
							'condition_2' => trim($discount['condition_2']),
							'discount_2' => trim($discount['discount_2']),
						);
					}
					$update['discount'] = iserializer($update['discount']);
				}
				if ($update['nums_status'] != 0 && !empty($nums['nums'])) {
					$update['nums'] = array();
					foreach ($nums['nums'] as $row) {
						if ($row['num'] <= 0 || $row['recharge'] <= 0) {
							message('充值返次数设置不能为负数或零', referer(), 'error');
						}
						$num = floatval($row['num']);
						$recharge = trim($row['recharge']);
						if ($num <= 0 || $recharge <= 0) continue;
						$update['nums'][$recharge] = array(
							'recharge' => $recharge,
							'num' => $num
						);
					}
					$update['nums'] = iserializer($update['nums']);
				}
				if ($update['times_status'] != 0 && !empty($times['times'])) {
					$update['times'] = array();
					foreach ($times['times'] as $row) {
						if ($row['time'] <= 0 || $row['recharge'] <= 0) {
							message('充值返时长设置不能为负数或零', referer(), 'error');
						}
						$time = intval($row['time']);
						$recharge = trim($row['recharge']);
						if ($time <= 0 || $recharge <= 0) continue;
						$update['times'][$recharge] = array(
							'recharge' => $recharge,
							'time' => $time
						);
					}
					$update['times'] = iserializer($update['times']);
				}
				if (!empty($setting)) {
					pdo_update('mc_card', $update, array('uniacid' => $_W['uniacid']));
				} else {
					$update['status'] = '1';
					$update['uniacid'] = $_W['uniacid'];
					pdo_insert('mc_card', $update);
				}
				message('会员卡设置成功！', $this->createWeburl('membercard'), 'success');
			}
			$unisetting = uni_setting_load('creditnames');
			$fields_temp = mc_acccount_fields();
			$fields = array();
			foreach ($fields_temp as $key => $val) {
				$fields[$key] = array(
					'title' => $val,
					'bind' => $key
				);
			}
			$params = json_decode($setting['params'], true);
			if (!empty($params)) {
				foreach ($params as $key => &$value) {
					if ($value['id'] == 'cardBasic') {
						$value['params']['description'] = str_replace("<br/>", "\n", $value['params']['description']);
					}
					$card_params[$key] = $value;
					$params_new[$value['id']] = $value;
				}
				unset($value);
			}
			$setting['params'] = json_encode($card_params);
			$discounts_params = $params_new['cardActivity']['params']['discounts'];
			$discounts_temp = array();
			if (!empty($discounts_params)) {
				foreach ($discounts_params as $row) {
					$discounts_temp[$row['groupid']] = $row;
				}
			}
			$discounts = array();
			foreach ($_W['account']['groups'] as $group) {
				$discounts[$group['groupid']] = array(
					'groupid' => $group['groupid'],
					'title' => $group['title'],
					'credit' => $group['credit'],
					'condition_1' => $discounts_temp[$group['groupid']]['condition_1'],
					'discount_1' => $discounts_temp[$group['groupid']]['discount_1'],
					'condition_2' => $discounts_temp[$group['groupid']]['condition_2'],
					'discount_2' => $discounts_temp[$group['groupid']]['discount_2'],
				);
			}
			$setting['params'] = preg_replace('/\n/', '', $setting['params']);
		}
		
		if ($op == 'cardstatus') {
			if (empty($setting)) {
				$open = array(
					'uniacid' => $_W['uniacid'],
					'title' => '我的会员卡',
					'format_type' => 1,
					'fields' => iserializer(array(
						array('title' => '姓名', 'require' => 1, 'bind' => 'realname'),
						array('title' => '手机', 'require' => 1, 'bind' => 'mobile'),
					)),
					'status' => 1,
				);
				pdo_insert('mc_card', $open);
			}
			if (false === pdo_update('mc_card', array('status' => intval($_GPC['status'])), array('uniacid' => $_W['uniacid']))) {
				message(error(-1, ''), '', 'ajax');
			}
			message(error(0, ''), '', 'ajax');
		}
		include $this->template('membercard');
	}

	public function doWebCardmanage() {
		global $_GPC, $_W;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$setting = $this->settings;
		if ($op == 'display') {
			$cardid = intval($_GPC['cardid']);
			if ($_W['ispost']) {
				$status = array('status' => intval($_GPC['status']));
				if (false === pdo_update('mc_card_members', $status, array('uniacid' => $_W['uniacid'], 'id' => $cardid))) {
					exit('error');
				}
				exit('success');
			}
			if ($setting['status'] == 0) {
				message('会员卡功能未开启', referer(), 'error');
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;

			$param = array(':uniacid' => $_W['uniacid']);
			$cardsn = trim($_GPC['cardsn']);
			if (!empty($cardsn)) {
				$where .= ' AND a.cardsn LIKE :cardsn';
				$param[':cardsn'] = "%{$cardsn}%";
			}
			$status = isset($_GPC['status']) ? intval($_GPC['status']) : -1;
			if ($status >= 0) {
				$where .= " AND a.status = :status";
				$param[':status'] = $status;
			}
			$num = isset($_GPC['num']) ? intval($_GPC['num']) : -1;
			if ($num >= 0) {
				if (!$num) {
					$where .= " AND a.nums = 0";
				} else {
					$where .= " AND a.nums > 0";
				}
			}
			$endtime = isset($_GPC['endtime']) ? intval($_GPC['endtime']) : -1;
			if ($endtime >= 0) {
				$where .= " AND a.endtime <= :endtime";
				$param[':endtime'] = strtotime($endtime . 'days');
			}

			$keyword = trim($_GPC['keyword']);
			if (!empty($keyword)) {
				$where .= " AND (b.mobile LIKE '%{$keyword}%' OR b.realname LIKE '%{$keyword}%')";
			}
			$sql = 'SELECT a.*, b.realname, b.groupid, b.credit1, b.credit2, b.mobile FROM ' . tablename('mc_card_members') . " AS a LEFT JOIN " . tablename('mc_members') . " AS b ON a.uid = b.uid WHERE a.uniacid = :uniacid $where ORDER BY a.id DESC LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list = pdo_fetchall($sql, $param);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . " AS a LEFT JOIN " . tablename('mc_members') . " AS b ON a.uid = b.uid WHERE a.uniacid = :uniacid $where", $param);
			$pager = pagination($total, $pindex, $psize);
		} elseif ($op == 'record') {
			$uid = intval($_GPC['uid']);
			$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
			$where = ' WHERE uniacid = :uniacid AND uid = :uid';
			$param = array(':uniacid' => $_W['uniacid'], ':uid' => $uid);
			$type = trim($_GPC['type']);
			if (!empty($type)) {
				$where .= ' AND type = :type';
				$param[':type'] = $type;
			}
			if (empty($_GPC['endtime']['start'])) {
				$starttime = strtotime('-30 days');
				$endtime = TIMESTAMP;
			} else {
				$starttime = strtotime($_GPC['endtime']['start']);
				$endtime = strtotime($_GPC['endtime']['end']) + 86399;
			}
			$where .= ' AND addtime >= :starttime AND addtime <= :endtime';
			$param[':starttime'] = $starttime;
			$param[':endtime'] = $endtime;

			$pindex = max(1, intval($_GPC['page']));
			$psize = 30;
			$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_record') . " {$where}", $param);
			$list = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_record') . " {$where} {$limit}", $param);
			$pager = pagination($total, $pindex, $psize);
		} elseif ($op == 'delete') {
			$cardid = intval($_GPC['cardid']);
			if (pdo_delete('mc_card_members',array('id' =>$cardid))) {
				message('删除会员卡成功', referer(), 'success');
			} else {
				message('删除会员卡失败', referer(), 'error');
			}
		} elseif ($op == 'modal') {
			$uid = intval($_GPC['uid']);
			$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
			$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
			if (empty($card)) {
				exit('error');
			}
			include $this->template('cardmodel');
			exit();
		} elseif ($op == 'submit') {
			load()->model('mc');
			$uid = intval($_GPC['uid']);
			$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
			$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
			if (empty($card)) {
				message('用户会员卡信息不存在', referer(), 'error');
			}
			$type = trim($_GPC['type']);
			if ($type == 'nums_plus') {
				$fee = floatval($_GPC['fee']);
				$tag = intval($_GPC['nums']);
				if (!$fee && !$tag) {
					message('请完善充值金额和充值次数', referer(), 'error');
				}
				$total_num = $card['nums'] + $tag;
				pdo_update('mc_card_members', array('nums' => $total_num), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
				$log = array(
					'uniacid' => $_W['uniacid'],
					'uid' => $uid,
					'type' => 'nums',
					'model' => 1,
					'fee' => $fee,
					'tag' => $tag,
					'addtime' => TIMESTAMP,
					'note' => date('Y-m-d H:i') . "充值{$fee}元，管理员手动添加{$tag}次，添加后总次数为{$total_num}次",
					'remark' => trim($_GPC['remark']),
				);
				pdo_insert('mc_card_record', $log);
				mc_notice_nums_plus($card['openid'], $setting['nums_text'], $tag, $total_num);
			}

			if ($type == 'nums_times') {
				$tag = intval($_GPC['nums']);
				if (!$tag) {
					message('请填写消费次数', referer(), 'error');
				}
				if ($card['nums'] < $tag) {
					message('当前用户的消费次数不够', referer(), 'error');
				}
				$total_num = $card['nums'] - $tag;
				pdo_update('mc_card_members', array('nums' => $total_num), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
				$log = array(
					'uniacid' => $_W['uniacid'],
					'uid' => $uid,
					'type' => 'nums',
					'model' => 2,
					'fee' => 0,
					'tag' => $tag,
					'addtime' => TIMESTAMP,
					'note' => date('Y-m-d H:i') . "消费1次，管理员手动减1次，消费后总次数为{$total_num}次",
					'remark' => trim($_GPC['remark']),
				);
				pdo_insert('mc_card_record', $log);
				mc_notice_nums_times($card['openid'], $card['cardsn'], $setting['nums_text'], $total_num);
			}

			if ($type == 'times_plus') {
				$fee = floatval($_GPC['fee']);
				$endtime = strtotime($_GPC['endtime']);
				$days = intval($_GPC['days']);
				if ($endtime <= $card['endtime'] && !$days) {
					message('服务到期时间不能小于会员当前的服务到期时间或未填写延长服务天数', '', 'error');
				}
				$tag = floor(($endtime - $card['endtime']) / 86400);
				if ($days > 0) {
					$tag = $days;
					if ($card['endtime'] > TIMESTAMP) {
						$endtime = $card['endtime'] + $days * 86400;
					} else {
						$endtime = strtotime($days . 'days');
					}
				}
				pdo_update('mc_card_members', array('endtime' => $endtime), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
				$endtime = date('Y-m-d', $endtime);
				$log = array(
					'uniacid' => $_W['uniacid'],
					'uid' => $uid,
					'type' => 'times',
					'model' => 1,
					'fee' => $fee,
					'tag' => $tag,
					'addtime' => TIMESTAMP,
					'note' => date('Y-m-d H:i') . "充值{$fee}元，管理员手动设置{$setting['times_text']}到期时间为{$endtime},设置之前的{$setting['times_text']}到期时间为".date('Y-m-d', $card['endtime']),
					'remark' => trim($_GPC['remark']),
				);
				pdo_insert('mc_card_record', $log);
				mc_notice_times_plus($card['openid'], $card['cardsn'], $setting['times_text'], $fee, $tag, $endtime);
			}

			if ($type == 'times_times') {
				$endtime = strtotime($_GPC['endtime']);
				if ($endtime > $card['endtime']) {
					message("该会员的{$setting['times_text']}到期时间为：" . date('Y-m-d', $card['endtime']) . ",您当前在进行消费操作，设置到期时间不能超过" . date('Y-m-d', $card['endtime']) , '', 'error');
				}
				$flag = intval($_GPC['flag']);
				if ($flag) {
					$endtime = TIMESTAMP;
				}
				$tag = floor(($card['endtime'] - $endtime) / 86400);
				pdo_update('mc_card_members', array('endtime' => $endtime), array('uniacid' => $_W['uniacid'], 'uid' => $uid));
				$endtime = date('Y-m-d', $endtime);
				$log = array(
					'uniacid' => $_W['uniacid'],
					'uid' => $uid,
					'type' => 'times',
					'model' => 2,
					'fee' => 0,
					'tag' => $tag,
					'addtime' => TIMESTAMP,
					'note' => date('Y-m-d H:i') . "管理员手动设置{$setting['times_text']}到期时间为{$endtime},设置之前的{$setting['times_text']}到期时间为".date('Y-m-d', $card['endtime']),
					'remark' => trim($_GPC['remark']),
				);
				pdo_insert('mc_card_record', $log);
				mc_notice_times_times($card['openid'], "您好，您的{$setting['times_text']}到期时间已变更", $setting['times_text'], $endtime);
			}
			message('操作成功', referer(), 'success');
		}
		include $this->template('cardmanage');
		exit;
	}

	public function doWebMemberproperty() {
		global $_W, $_GPC;
		for ($i = 1; $i <= 24; $i++) {
			$nums[$i] = $i;
		}
		$nums = json_encode($nums);
		$current_property_info = pdo_get('mc_member_property', array('uniacid' => $_W['uniacid']));
		$property = $current_property_info['property'];
		if (empty($property)) {
			$property = json_encode('');
		}
		if ($_W['isajax'] && $_W['ispost']) {
			$member_property = $_GPC['__input'];
			$insert_data = array(
				'property' => json_encode($member_property)
			);
			if (!empty($current_property_info)) {
				$status = pdo_update('mc_member_property', $insert_data, array('id' => $current_property_info['id']));
			} else {
				$insert_data['uniacid'] = $_W['uniacid'];
				$status = pdo_insert('mc_member_property', $insert_data);
			}
			if (is_error($status)) {
				message(error(-1, $status), '', 'ajax');
			}
			message(error(0, ''), '', 'ajax');
		}
		include $this->template('memberproperty');
	}

	public function doWebCouponmanage() {
		global $_W, $_GPC;
		load()->classs('coupon');
		$coupon_api = new coupon();
		$_W['page']['title'] = '卡券管理 - 粉丝营销';
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($op == 'display') {
			$display_location_store = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'status' => 1, 'location_id !=' => ''), array('id', 'location_id', 'business_name', 'branch_name', 'address'));
			$module = uni_modules();
			foreach ($module as $val) {
				if ($val['issystem'] == '0'){
					$display_modules[] = $val;
				}
			}
			$display_groups = mc_fans_groups();
			$pageindex = max(1, $_GPC['page']);
			$psize = 15;
			$condition = array();
			$condition_sql = $join_sql = '';
			$condition_sql = ' c.uniacid = :uniacid';
			$condition[':uniacid'] = $_W['uniacid'];
			$condition_sql .= " AND c.source = :source";
			$condition[':source'] = COUPON_TYPE;
			
			if (!empty($_GPC['status'])) {
				$condition_sql .= " AND c.status = :status";
				$condition[':status'] = intval($_GPC['status']);
			}
			
			if (!empty($_GPC['title'])) {
				$condition_sql .= " AND c.title LIKE :title";
				$condition[':title'] = "%".$_GPC['title']."%";
			}
			
			if (!empty($_GPC['type'])) {
				$condition_sql .= " AND c.type = :type";
				$condition[':type'] = intval($_GPC['type']);
			}
			
			if (!empty($_GPC['groupid'])) {
				$join_sql .= " LEFT JOIN ".tablename('coupon_groups')." AS g ON c.id = g.couponid ";
				$condition_sql .= " AND g.groupid = :groupid";
				$condition[':groupid'] = intval($_GPC['groupid']);
			}
			
			if (!empty($_GPC['storeid'])) {
				$join_sql .= " LEFT JOIN ".tablename('coupon_store')." AS s ON c.id = s.couponid ";
				$condition_sql .= " AND s.storeid = :storeid";
				$condition[':storeid'] = intval($_GPC['storeid']);
			}
			
			if (!empty($_GPC['moduleid'])) {
				$join_sql .= " LEFT JOIN ".tablename('coupon_modules')." AS m ON c.id = m.couponid ";
				$condition_sql .= " AND m.module = :module";
				$condition[':module'] = $_GPC['moduleid'];
			}
			$couponlist = pdo_fetchall("SELECT * FROM " . tablename('coupon') . " AS c " . $join_sql . " WHERE  " . $condition_sql . " ORDER BY c.id DESC LIMIT ".($pageindex - 1) * $psize.','.$psize, $condition);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('coupon') . " AS c " . $join_sql . " WHERE  " . $condition_sql, $condition);
			foreach ($couponlist as &$row) {
				if (empty($row['card_id'])) {
					pdo_delete('coupon', array('id' => $row['id']));
				}
				$row['date_info'] = iunserializer($row['date_info']);
				if ($row['date_info']['time_type'] == 1) {
					$row['date_info'] = $row['date_info']['time_limit_start'].'-'. $row['date_info']['time_limit_end'];
				} elseif ($row['date_info']['time_type'] == 2) {
					$row['date_info'] = '领取后'.$row['date_info']['limit'].'天有效';
				}
				$row['type'] = activity_coupon_type_label($row['type']);
				if ($row['status'] == 1) {
					$card = $coupon_api->fetchCard($row['card_id']);
					$coupon_status = activity_coupon_status();
					$status = $coupon_status[$card[$row['type'][1]]['base_info']['status']];
					pdo_update('coupon', array('status' => $status), array('uniacid' => $_W['uniacid'], 'card_id' => $row['card_id']));
				}
			}
			unset($row);
			$pager = pagination($total, $pageindex, $psize);
		}
		
		if ($op == 'post') {
			$id = intval($_GPC['id']);
			$type = intval($_GPC['type']);
			$coupon_title = activity_coupon_type_label($type);
			$coupon_title = $coupon_title[0]; //卡券类型标题
			$groups = mc_fans_groups();
			if (checksubmit('submit')) {
				$coupon = Card::create($type);
				$coupon->logo_url = empty($_GPC['logo_url']) ? urlencode($setting['logourl']) : urlencode(trim($_GPC['logo_url']));
				$coupon->brand_name = $_GPC['brand_name'];
				$coupon->title = substr(trim($_GPC['title']), 0,27);
				$coupon->sub_title = trim($_GPC['sub_title']);
				$coupon->color = empty($_GPC['color']) ? 'Color082' : $_GPC['color'];
				$coupon->notice = $_GPC['notice'];
				$coupon->service_phone = $_GPC['service_phone'];
				$coupon->description = $_GPC['description'];
				$coupon->get_limit = intval($_GPC['get_limit']);
				$coupon->can_share = intval($_GPC['can_share']) ? true : false;
				$coupon->can_give_friend = intval($_GPC['can_give_friend']) ? true : false;
				//有效期
				if (intval($_GPC['time_type']) == COUPON_TIME_TYPE_RANGE) {
					$coupon->setDateinfoRange($_GPC['time_limit']['start'], $_GPC['time_limit']['end']);
				} else {
					$coupon->setDateinfoFix($_GPC['deadline'], $_GPC['limit']);
				}
				//自定义菜单
				if (!empty($_GPC['promotion_url_name']) && !empty($_GPC['promotion_url'])) {
					$coupon->setPromotionMenu($_GPC['promotion_url_name'], $_GPC['promotion_url_sub_title'], $_GPC['promotion_url']);
				}
				//启用门店
				if (!empty($_GPC['location-select'])) {
					$location_list = explode('-', $_GPC['location-select']);
					if (!empty($location_list)) {
						$coupon->setLocation($location_list);
					}
				}
				
				$coupon->setCustomMenu('立即使用', '', murl('entry', array('m' => 'paycenter', 'do' => 'consume'), true, true));
				$coupon->setQuantity($_GPC['quantity']);
				$coupon->setCodetype($_GPC['code_type']);
				//折扣券
				$coupon->discount = intval($_GPC['discount']);
				//代金券，单位为分
				$coupon->least_cost = $_GPC['least_cost'] * 100;
				$coupon->reduce_cost = $_GPC['reduce_cost'] * 100;
				//礼品券
				$coupon->gift = $_GPC['gift'];
				//团购券
				$coupon->deal_detail = $_GPC['deal_detail'];
				//优惠券
				$coupon->default_detail = $_GPC['default_detail'];
				
				$check = $coupon->validate();
				if (is_error($check)) {
					message($check['message'], '', 'error');
				}
				if (COUPON_TYPE == WECHAT_COUPON) {
					$status = $coupon_api->CreateCard($coupon->getCardData());
					if(is_error($status)) {
						message($status['message'], '', 'error');
					}
					$coupon->card_id = $status['card_id'];
					$coupon->source = 2;
					$coupon->status = 1;
				} else {
					$coupon->status = 3;
					$coupon->source = 1;
					$coupon->setCodetype(3);
					$coupon->card_id = 'AB' . $_W['uniacid'] . date('YmdHis');
				}
				$cardinsert = $coupon->getCardArray();
				$cardinsert['uniacid'] = $_W['uniacid'];
				$cardinsert['acid'] = $_W['acid'];
				$card_exists = pdo_get('coupon', array('card_id' => $coupon->card_id), array('id'));
				if (empty($card_exists)) {
					pdo_insert('coupon', $cardinsert);
					$cardid = pdo_insertid();
				} else {
					$cardid = $card_exists['id'];
					unset($cardinsert['status']);
					pdo_update('coupon', $cardinsert, array('id' => $cardid));
				}
				$_GPC['module-select'] = trim($_GPC['module-select']);
				if (!empty($_GPC['module-select'])) {
					$enabled_modules = explode('@', $_GPC['module-select']);
					pdo_delete('coupon_modules', array('couponid' => $cardid));
					foreach ($enabled_modules as $modulename) {
						$data = array(
							'uniacid' => $_W['uniacid'],
							'acid' => $_W['acid'],
							'couponid' => $cardid,
							'module' => $modulename
						);
						pdo_insert('coupon_modules', $data);
					}
				}
				$_GPC['groups'] = trim($_GPC['groups']);
				if (!empty($_GPC['groups'])) {
					$groups = explode('-', $_GPC['groups']);
					if (is_array($groups)) {
						pdo_delete('coupon_groups', array('couponid' => $cardid));
						foreach ($groups as $group) {
							$data = array(
								'uniacid' => $_W['uniacid'],
								'groupid' => $group,
								'couponid' => $cardid
							);
							pdo_insert('coupon_groups', $data);
						}
					}
				}
				//启用门店
				if (!empty($location_list)) {
					pdo_delete('coupon_store', array('couponid' => $cardid));
					foreach ($location_list as $storeid) {
						$data = array(
							'uniacid' => $_W['uniacid'],
							'storeid' => $storeid,
							'couponid' => $cardid
						);
						pdo_insert('coupon_store', $data);
					}
				}
				message('卡券更新成功', $this->createWeburl('couponmanage'), 'success');
			}
			$location_store = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'status' => 1, 'source' => COUPON_TYPE), array('id', 'location_id', 'business_name', 'branch_name', 'address'));
			$post_module = uni_modules();
		}

		if ($op == 'selfconsume') {
			$id = intval($_GPC['id']);
			$coupon = activity_coupon_info($id);
			if (empty($coupon)) {
				message('抱歉，卡券不存在或是已经被删除！');
			}
			if (empty($coupon['location_id_list'])) {
				message(error(1, '该卡券未设置适用门店,无法设置自助核销'), '', 'ajax');
			}
			
			if ($coupon['source'] == WECHAT_COUPON) {
				$data = array(
					'card_id' => $coupon['card_id'],
					'is_open' => empty($coupon['is_selfconsume']) ? true : false,
				);
				$return = $coupon_api->selfConsume($data);
				if (is_error($return)) {
					message(error(1, '设置自助核销失败，错误为' . $return['message']), '', 'ajax');
				}
			}
			pdo_update('coupon', array('is_selfconsume' => empty($coupon['is_selfconsume']) ? 1 : 0), array('id' => $id));
			message(error(0, '设置自助核销成功'), $this->createWeburl('couponmanage'), 'ajax');
		}

		if ($op == 'publish') {
			$cid = intval($_GPC['cid']);
			$coupon = pdo_get('coupon', array('id' => $cid));
			if (empty($coupon)) {
				return message('卡券不存在或已经删除', '', 'error');
			}
			//二维码投入场景Id,文档中是写的19位的longint型，实际测试大于14位会丢失精度
			$qrcode_sceneid = sprintf('11%012d', $cid);
			$coupon_qrcode = pdo_get('qrcode', array('qrcid' => $qrcode_sceneid, 'type' => 'card'));
			if (empty($coupon_qrcode)) {
				$insert = array(
					'uniacid' => $_W['uniacid'],
					'acid' => $_W['acid'],
					'qrcid' => $qrcode_sceneid,
					'keyword' => '',
					'name' => $coupon['title'],
					'model' => 1,
					'ticket' => '',
					'expire' => '',
					'url' => '',
					'createtime' => TIMESTAMP,
					'status' => '1',
					'type' => 'card',
				);
				pdo_insert('qrcode', $insert);
				$coupon_qrcode['id'] = pdo_insertid();
			}
			$response = ihttp_request($coupon_qrcode['url']);
			if ($response['code'] != '200' || empty($coupon_qrcode['url'])) {
				$coupon_qrcode_image = $coupon_api->QrCard($coupon['card_id'], $qrcode_sceneid);
				if (is_error($coupon_qrcode_image)) {
					if ($coupon_qrcode_image['errno'] == '40078') {
						pdo_update('coupon', array('status' => 2), array('id' => $cid));
					}
					message(error('1', '生成二维码失败，' . $coupon_qrcode_image['message']), '', 'ajax');
				}
				$cid = $coupon_qrcode['id'];
				unset($coupon_qrcode['id']);
				
				$coupon_qrcode['url'] = $coupon_qrcode_image['show_qrcode_url'];
				$coupon_qrcode['ticket'] = $coupon_qrcode_image['ticket'];
				$coupon_qrcode['expire'] = TIMESTAMP + $coupon_qrcode_image['expire_seconds'];
				pdo_update('qrcode', $coupon_qrcode, array('id' => $cid));
			}
			$coupon_qrcode['expire'] = date('Y-m-d H:i:s', $coupon_qrcode['expire']);
			//获取扫码记录
			$qrcode_list = pdo_getslice('qrcode_stat', array('qrcid' => $qrcode_sceneid), 10, $total, array('openid', 'createtime'));
			if (!empty($qrcode_list)) {
				$openids = array();
				foreach ($qrcode_list as &$row) {
					//由于粉丝不多，循环内直接查询
					$fans = mc_fansinfo($row['openid']);
					$row['nickname'] = $fans['nickname'];
					$row['avatar'] = $fans['avatar'];
					$row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
				}
				unset($row);
			}
			message(error('0', array('coupon' => $coupon_qrcode, 'record' => $qrcode_list, 'total' => $total)), '', 'ajax');
		}
		
		if ($op == 'modifystock') {
			$id = intval($_GPC['id']);
			$quantity = intval($_GPC['quantity']);
			$coupon = activity_coupon_info($id);
			if (empty($coupon)) {
				message('抱歉，卡券不存在或是已经被删除！');
			}
			pdo_update('coupon', array('quantity' => $quantity), array('id' => $id, 'uniacid' => $_W['uniacid']));
			//走删除卡券接口
			$modify_quantity = $quantity - $coupon['quantity'];
			if ($coupon['source'] == WECHAT_COUPON) {
				$return = $coupon_api->ModifyStockCard($coupon['card_id'], $modify_quantity);
				if (is_error($return)) {
					message(error(1, '修改卡券库存失败，错误为' . $return['message']), '', 'ajax');
				}
			}
			message(error(0, '修改库存成功'), referer(), 'ajax');
		}

		if ($op == 'toggle') {
			$id = intval($_GPC['id']);
			$display_status = pdo_getcolumn('coupon', array('id' => $id, 'uniacid' => $_W['uniacid']), 'is_display');
			if ($display_status == 1) {
				pdo_update('coupon', array('is_display' => 0), array('uniacid' => $_W['uniacid'], 'id' => $id));
			} else {
				pdo_update('coupon', array('is_display' => 1), array('uniacid' => $_W['uniacid'], 'id' => $id));
			}
			message(error(0, $display_status ? '下架成功' : '上架成功'), referer(), 'ajax');
		}

		if ($op == 'detail') {
			$id = intval($_GPC['id']);
			$type = intval($_GPC['type']);
			$coupon_title = activity_coupon_type_label($type);
			$coupon_title = $coupon_title[0]; //卡券类型标题
			$groups = mc_fans_groups();
			$colors = activity_coupon_colors();
			
			if (!empty($id)) {
				$coupon = activity_coupon_info($id);
				if (empty($coupon)) {
					message('卡券不存在或是已经删除', '', 'error');
				}
			}
			$coupon['location_count'] = count($coupon['location_id_list']);
			if ($coupon['type'] == COUPON_TYPE_CASH) {
				$coupon['detail']['least_cost'] = $coupon['extra']['least_cost'] * 0.01;
				$coupon['detail']['reduce_cost'] = $coupon['extra']['reduce_cost'] * 0.01;
			}
		}

		if ($op == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_get('coupon', array('uniacid' => $_W['uniacid'], 'id' => $id));
			if (empty($row)) {
				message('抱歉，卡券不存在或是已经被删除！');
			}
			if (COUPON_TYPE == WECHAT_COUPON) {
				$return = $coupon_api->DeleteCard($row['card_id']);
				if (is_error($return)) {
					message('删除卡券失败，错误为' . $return['message'], '', 'error');
				}
			}
			pdo_delete('coupon', array('uniacid' => $_W['uniacid'], 'id' => $id));
			pdo_delete('coupon_record', array('uniacid' => $_W['uniacid'], 'couponid' => $id));
			pdo_delete('activity_exchange', array('uniacid' => $_W['uniacid'], 'extra' => $id));
			pdo_delete('coupon_groups', array('uniacid' => $_W['uniacid'],'couponid' => $id));
			pdo_delete('coupon_groups', array('uniacid' => $_W['uniacid'],'couponid' => $id));
			
			message('卡券删除成功！', referer(), 'success');
		}

		if ($op == 'sync') {
			$type = trim($_GPC['type']);
			if ($type == '1') {
				$cachekey = "couponsync:{$_W['uniacid']}";
				$cache = cache_delete($cachekey);
			}
			activity_coupon_sync();
			message(error(0, '更新卡券状态成功'), referer(), 'ajax');
		}

		if ($op == 'download') {
			$compare = ver_compare(IMS_VERSION, '1.0');
			if ($compare == -1) {
				$offset = $_GPC['__input']['offset'];
			} else {
				$offset = $_GPC['offset'];
			}
			$data = array(
				'offset' => $offset,
				'count' => 50
			);
			$card_list = $coupon_api->batchgetCard($data);
			$card_list_total = $card_list['total_num'];
			$download_info = activity_coupon_download($card_list);
			if (is_error($download_info)) {
				message(error(-1, $download_info['message']), '', 'ajax');
			} else {
				$response['offset'] = $offset + 50;
				if ($response['offset'] > $card_list_total) {
					$response['offset'] = $offset + 1;
				}
				$response['total'] = $card_list_total;
				$response['pages'] = ceil($card_list_total / 50);
			}
			message(error(0, $response), '', 'ajax');
		}

		if ($op == 'exchange_coupon_type') {
			$status = pdo_update('uni_settings', array('coupon_type' => intval($_GPC['status'])), array('uniacid' => $_W['uniacid']));
			if (!empty($status)) {
				cache_delete("unisetting:{$_W['uniacid']}");
				message(error(0, '修改成功'), referer(), 'ajax');
			} else {
				message(error(-1, '修改失败'), referer(), 'ajax');
			}
		}
		include $this->template('couponmanage', TEMPLATE_INCLUDEPATH);
	}

	public function doWebCouponconsume() {
		global $_W, $_GPC;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$coupon_api = new coupon();
		if ($op == 'display') {
			$source = (COUPON_TYPE == SYSTEM_COUPON) ? '1' : '2';
			$clerks = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
			$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid']), array('id', 'business_name', 'branch_name'), 'id');
			$store_ids = array_keys($stores);
			$nicknames_info = pdo_getall('mc_mapping_fans', array('uniacid' => $_W['uniacid']), array('nickname', 'openid'), 'openid');
			$condition = '';
			$coupons = pdo_fetchall('SELECT id, title FROM ' . tablename('coupon') . ' WHERE uniacid = :uniacid AND source = :source ORDER BY id DESC', array(':uniacid' => $_W['uniacid'], ':source' => $source), 'id');
			$starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
			$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
			$type = intval($_GPC['type']);
			if (!empty($type)) {
				$condition = "AND b.type = $type";
			}
			$where = " WHERE a.uniacid = {$_W['uniacid']} ".$condition." AND b.source = {$source}";
			$params = array();
			$code = trim($_GPC['code']);
			if (!empty($code)) {
				$where .=' AND a.code=:code';
				$params[':code'] = $code;
			}
			$couponid = intval($_GPC['couponid']);
			if (!empty($couponid)) {
				$where .= " AND a.couponid = {$couponid}";
			}
			$clerk_id = intval($_GPC['clerk_id']);
			if (!empty($clerk_id)) {
				$where .= " AND a.clerk_id = :clerk_id";
				$params[':clerk_id'] = $clerk_id;
			}
			if (!empty($_GPC['nickname'])) {
				$nicknames = pdo_fetchall('SELECT * FROM '. tablename('mc_mapping_fans')." WHERE uniacid = :uniacid AND nickname LIKE :nickname", array(':uniacid' => $_W['uniacid'], ':nickname' => '%'.$_GPC['nickname'].'%'), 'openid');
				$nickname = array_keys($nicknames);
				$nickname = '\''.implode('\',\'', $nickname).'\'';
				$where .= " AND openid in ({$nickname}) ";
			}
			$status = intval($_GPC['status']);
			if (!empty($status)) {
				$where .= " AND a.status = :status";
				$params[':status'] = $status;
			} else {
				$where .= " AND a.status <> :status";
				$params[':status'] = 4;
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT a.status AS rstatus,a.id AS recid, a.*, b.* FROM ".tablename('coupon_record'). ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.couponid = b.id ' . " $where AND a.code <> '' ORDER BY a.addtime DESC, a.status DESC, a.couponid DESC,a.id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('coupon_record') . ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.couponid = b.id '. $where ." AND a.code <> ''", $params);
			if (!empty($list)) {
				$uids = array();
				foreach ($list as &$row) {
					if (empty($row['store_id'])) {
						$row['store_name'] = '系统';
					} else {
						if (!in_array($row['store_id'], $store_ids)) {
							$row['store_name'] = '<span class="label label-danger">门店已删除</span>';
						} else {
							$row['store_name'] = $stores[$row['store_id']]['business_name'];
						}
					}
					$uids[] = $row['uid'];
					if ($row['status'] == 2) {
						$operator = mc_account_change_operator($row['clerk_type'], $row['store_id'], $row['clerk_id']);
						$row['clerk_cn'] = $operator['clerk_cn'];
						$row['store_cn'] = $operator['store_cn'];
					}
					$row['extra'] = iunserializer($row['extra']);
					if ($row['type'] == COUPON_TYPE_DISCOUNT){
						$row['extra_notes'] = $row['extra']['discount'] * 0.1 . '折';
					} elseif ($row['type'] == COUPON_TYPE_CASH){
						$row['extra_notes'] = $row['extra']['reduce_cost'] * 0.01 . '元';
					}
					$date = iunserializer($row['date_info']);
					if ($date['time_type'] == 2) {
						$addtime = strtotime(date('Y-m-d', $row['addtime']));
						$row['starttime'] = $addtime + $date['deadline'] * 86400;
						$row['endtime'] = $addtime + ($date['limit'] - 1) * 86400;
						$row['time'] = strtotime(date('Y-m-d'));
					}
				}
				unset($row);
				$members = mc_fetch($uids, array('uid', 'nickname'));
				foreach ($list as &$row) {
					$row['nickname'] = $members[$row['uid']]['nickname'];
					$row['logo_url'] = tomedia($row['logo_url']);
				}
				unset($row);
			}
			$pager = pagination($total, $pindex, $psize);
			$status = array('1' => '未使用', '2' => '已使用');
			$clerks = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
		} elseif ($op == 'consume') {
			$recid = intval($_GPC['id']);
			$record = pdo_get('coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
			if (empty($record)) {
				message(error(-1, '兑换记录不存在'), referer(), 'ajax');
			}
			$source = intval($_GPC['source']);
			$clerk_name = trim($_W['user']['name']) ? trim($_W['user']['name']) : trim($_W['user']['username']);
			$update = array(
				'status' => 3,
				'usetime' => TIMESTAMP,
				'clerk_id' => $_W['user']['clerk_id'],
				'clerk_type' => $_W['user']['clerk_type'],
				'store_id' => $_W['user']['store_id'],
				'clerk_name' => $clerk_name,
			);
			if ($source == '2') {
				$status = $coupon_api->ConsumeCode(array('code' => $record['code']));
				if (is_error($status)) {
					if (strexists($status['message'], '40127')) {
						$status['message'] = '卡券已失效';
						pdo_update('coupon_record', array('status' => '2'), array('uniacid' => $_W['uniacid'], 'id' => $recid));
					}
					if (strexists($status['message'], '40099')) {
						$status['message'] = '卡券已被核销';
						pdo_update('coupon_record', array('status' => '3'), array('uniacid' => $_W['uniacid'], 'id' => $recid));
					}
					message(error(-1, $status['message']), '', 'ajax');
				}
			}
			$status = pdo_update('coupon_record', $update, array('uniacid' => $_W['uniacid'], 'id' => $recid));
			if (!empty($status)) {
				message(error(0, '核销成功'), referer(), 'ajax');
			}
		} elseif ($op == 'delete') {
			$recid = intval($_GPC['id']);
			$record = pdo_get('coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
			if (empty($record)) {
				message(error(-1, '没有要删除的记录'), '', 'ajax');
			}
			$source = intval($_GPC['source']);
			if ($source == '2') {
				$status = $coupon_api->UnavailableCode(array('code' => $record['code']));
				if (is_error($status)) {
					message(error(-1, $status['message']), referer(), 'ajax');
				}
			}
			$status = pdo_delete('coupon_record', array('uniacid' => $_W['uniacid'], 'id' => $recid));
			if (!empty($status)) {
				message(error(0, '删除成功'), referer(), 'ajax');
			}
		}
		include $this->template('couponconsume');
	}

	public function doWebCouponmarket() {
		global $_W, $_GPC;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$propertys = activity_member_propertys();
		$coupon_api = new coupon();
		$type_names = activity_get_coupon_label();
		if ($op == 'checkcoupon') {
			$coupon_id = intval($_GPC['coupon']);
			$coupon = activity_coupon_info($coupon_id);
			$result = $coupon_api->fetchCard($coupon['card_id']);
			$type = strtolower($result['card_type']);
			if ($result[$type]['base_info']['status'] == 'CARD_STATUS_VERIFY_OK' || empty($coupon_id)) {
				message(error(0, '卡券可用'), '', 'ajax');
			} else {
				message(error(1, $coupon['title']), '', 'ajax');
			}
		}
		if ($op == 'get_member_num') {
			$type = trim($_GPC['type']);
			$param = $_GPC['param'];
			if ($type == 'cash_time') {
				$param['start'] = strtotime($param['start']);
				$param['end'] = strtotime($param['end']);
			}
			$members = we7_coupon_activity_get_member($type, $param);
			message(error(0, $members['total']),'', 'ajax');
		}
		if ($op == 'display') {
			$_W['page']['title'] = '卡券活动列表';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 30;
			$condition = '';
			if (!empty($_GPC['title'])) {
				$condition .= "AND title LIKE '%".$_GPC['title']."%'";
			}
			$list = pdo_fetchall('SELECT * FROM '. tablename('coupon_activity')." WHERE uniacid = {$_W['uniacid']} AND type = ".COUPON_TYPE." ".$condition." ORDER BY id LIMIT ".($pindex-1)*$psize.",". $psize);
			foreach ($list as &$data) {
				$data['members'] = empty($data['members']) ? array() : iunserializer($data['members']);
				if (!empty($data['members'])) {
					if (in_array('group_member', $data['members'])) {
						$groups = pdo_getall('mc_groups', array('uniacid' => $_W['uniacid']), array(), 'groupid');
						$data['members']['group_name'] = $groups[$data['members']['groupid']]['title'];
					}
				}
			}
			unset($data);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('coupon_activity')." WHERE uniacid = {$_W['uniacid']} AND type = 1 ".$condition);
			$pager = pagination($total, $pindex, $psize);
		} elseif ($op == 'post') {
			if (checksubmit('submit')) {
				$post = array(
					'uniacid' => $_W['uniacid'],
					'title' => trim($_GPC['title']),
					'type' => COUPON_TYPE,
					'status' => intval($_GPC['status']),
					'coupons' => intval($_GPC['coupons']),
					'members' => $_GPC['members'],
					'thumb' => empty($_GPC['thumb'])? '' : $_GPC['thumb'],
				);
				if (empty($id)) {
					if (COUPON_TYPE == SYSTEM_COUPON) {
						$post['description'] = trim($_GPC['description']);
					}
					$openids = array();
					$param = array();
					if (in_array('group_member', $post['members'])) {
						$post['members']['groupid'] = $_GPC['groupid'];
						$param['groupid'] = intval($_GPC['groupid']);
					}
					if (in_array('cash_time', $post['members'])) {
						$post['members']['cash_time'] = $_GPC['daterange'];
						$param['start'] = strtotime($_GPC['daterange']['start']);
						$param['end'] = strtotime($_GPC['daterange']['end']);
					}
					if (in_array('openids', $post['members'])) {
						$post['members']['openids'] = json_decode($_COOKIE['fans_openids'.$_W['uniacid']]);
						$compare_array = array();
						for ($i = 0; $i < count($post['members']['openids']); $i++) {
							$compare_array[$i] = '';
						}
						$post['members']['openids'] = array_diff($post['members']['openids'], $compare_array);
						if (empty($post['members']['openids'])) {
							message('请选择粉丝', referer(), 'info');
						}
					}
					$openids = we7_coupon_activity_get_member($post['members'][0], $param);
					$post['members'] = serialize($post['members']);
					$openids = $openids['members'];
					$account_api = WeAccount::create();
					foreach ($openids as $openid) {
						$result = we7_coupon_activity_coupon_grant($post['coupons'], $openid, 3);
						$coupon_info = activity_coupon_info($post['coupons']);
						$info = $_W['account']['name'] . '赠送了您一张' . $coupon_info['title'] . '，请到会员中心查收';
						$send['touser'] = $openid;
						$send['msgtype'] = 'text';
						$send['text'] = array('content' => urlencode($_W['account']['name'].'赠送了您一张'.$coupon_info['title'].'，请到会员中心查收'));
						$data = $account_api->sendCustomNotice($send);
					}
					if (is_array($result)) {
						$post['msg_id'] = $result['errno'];
					}
					pdo_insert('coupon_activity', $post);
					message('添加卡券派发活动成功', referer(), 'success');
				}
			}
			$id = intval($_GPC['id']);
			$coupons = pdo_getall('coupon', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE, 'status' => '3', 'is_display' => '1', 'quantity >' => '0'));
			foreach ($coupons as $key => &$coupon) {
				$coupon = activity_coupon_info($coupon['id']);
				if (strtotime(date('Y-m-d')) < strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_start'])) || strtotime(date('Y-m-d')) > strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_end']))) {
					if ($coupon['date_info']['time_type'] == 1) {
						unset($coupons[$key]);
					}
				}
				$coupon['extra'] = iunserializer($coupon['extra']);
			}
			unset($coupon);
			if (!empty($id)) {
				$item = pdo_get('coupon_activity', array('id' => $id, 'uniacid' => $_W['uniacid']));
				$item['coupons'] =  empty($item['coupons']) ? array() : iunserializer($item['coupons']);
				foreach ($item['coupons'] as $key => $couponid) {
					$couponid = pdo_get('coupon', array('id' => $couponid));
					if (empty($couponid)) {
						unset($item['coupons'][$key]);
						continue;
					}
					unset($item['coupons'][$key]);
					$item['coupons'][$couponid['id']] = $couponid;
				}
				$item['members'] = iunserializer($item['members']);
				if (COUPON_TYPE == SYSTEM_COUPON) {
					if (!empty($item['members']['openids'])) {
						setcookie('fans_openids'.$_W['uniacid'], json_encode($item['members']['openids']));
					} else {
						setcookie('fans_openids'.$_W['uniacid'], '');
					}
				}
			} else {
				setcookie('fans_openids'.$_W['uniacid'], '');
			}
			if (COUPON_TYPE == SYSTEM_COUPON) {
				$groups = pdo_getall('mc_groups', array('uniacid' => $_W['uniacid']), array(), 'groupid');
			} else {
				$groups = mc_fans_groups();
				foreach ($groups as &$group) {
					$group['groupid'] = $group['id'];
				}
				unset($group);
			}
		}
		if ($op == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('coupon_activity', array('id' => $id, 'uniacid' => $_W['uniacid']));
			message('删除活动成功', $this->createWeburl('couponmarket'), 'success');
		}

		if ($op == 'fans') {
			$pindex = intval($_GPC['page']);
			$psize = 20;
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND (nickname LIKE '%" . trim($_GPC['keyword']) . "%' OR openid LIKE '%" . trim($_GPC['keyword']) . "%')";
			}
			$check_fans = json_decode($_COOKIE['fans_openids' . $_W['uniacid']]);
			$check_fans = empty($check_fans) ? array() : $check_fans;
			$fans = pdo_fetchall("SELECT * FROM " . tablename('mc_mapping_fans') ." WHERE uniacid = :uniacid AND acid = :acid AND nickname <> '' " . $condition . " LIMIT " . ($pindex-1) * $psize . " , " . $psize, array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid']));
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND acid = :acid AND nickname <> '' " . $condition, array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid']));
			$pager = pagination($total, $pindex, $psize, '', array('before' => '3', 'after' => '2', 'ajaxcallback' => 'true'));
			include $this->template('fans');
			exit;
		}

		include $this->template('couponmarket');
	}

	public function doWebCouponexchange() {
		global $_W, $_GPC;
		$uni_setting = pdo_get('uni_settings', array('uniacid' => $_W['uniacid']), array('exchange_enable'));
		$cfg = $this->module['config'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($op == 'coupon_info') {
			$coupon = activity_coupon_info(intval($_GPC['id']));
			message(error(0, $coupon), '', 'ajax');
		}
		if ($op == 'change_status') {
			$id = $_GPC['id'];
			$status = intval($_GPC['status']);
			pdo_update('activity_exchange', array('status' => $status),array('uniacid' => $_W['uniacid'], 'id' => $id));
			message(error(0), '', 'ajax');
		}
		if ($op == 'post') {
			if (checksubmit('submit')) {
				$start = $_GPC['coupon_start'];
				$end = $_GPC['coupon_end'];
				$post = array(
					'uniacid' => $_W['uniacid'],
					'extra' => $_GPC['coupon'],
					'status' => intval($_GPC['status']),
					'credittype' => $_GPC['credittype'],
					'credit' => abs(intval($_GPC['credit'])),
					'pretotal' => empty($_GPC['pretotal']) ? 1 : intval($_GPC['pretotal']),
					'status' => $_GPC['status'],
					'starttime' => strtotime($_GPC['date']['start']),
					'endtime' => strtotime($_GPC['date']['end']),
				);
				if ($start && $end) {
					$start = strtotime(str_replace('.', '-', $start));
					$end = strtotime(str_replace('.', '-', $end));
					if ($start > $post['starttime'] || $end < $post['starttime'] || $start > $post['endtime'] || $end < $post['endtime']) {
						message('日期范围超过卡券日期范围', '', 'info');
					}
				}
				$post['type'] = COUPON_TYPE;
				if (empty($id)) {
					pdo_insert('activity_exchange', $post);
					message('添加兑换卡券成功', $this->createWeburl('couponexchange'), 'success');
				}
			}
			$id = intval($_GPC['id']);

			if (!empty($id)) {
				$data = pdo_get('activity_exchange', array('id' => $id, 'uniacid' => $_W['uniacid']));
				$data['coupon'] = pdo_get('coupon', array('uniacid' => $_W['uniacid'], 'id' => $data['extra']));
				$data['coupon']['logo_url'] = tomedia($data['coupon']['logo_url']);
			} else {
				$data['starttime'] = time();
				$data['endtime'] = time();
			}

			$coupons = pdo_fetchall("SELECT * FROM ". tablename('coupon')." WHERE uniacid = :uniacid AND source = ".COUPON_TYPE, array(':uniacid' => $_W['uniacid']), 'id');
			$coupon_exists = pdo_getall('activity_exchange', array('type' => COUPON_TYPE, 'uniacid' => $_W['uniacid']), array(), 'extra');
			$coupon_exists = array_keys($coupon_exists);
			foreach ($coupons as $key => &$coupon) {
				$coupon = activity_coupon_info($coupon['id']);
				if (in_array($key, $coupon_exists)) {
					unset($coupons[$key]);
				}
			}
			unset($coupon);
		}
		if ($op == 'display') {
			$title = trim($_GPC['title']);
			$condition = '';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			if (!empty($title)) {
				$cids = pdo_fetchall("SELECT * FROM ". tablename('coupon')." WHERE uniacid = :uniacid AND title LIKE :title AND source = :source", array('uniacid' => $_W['uniacid'], ':title' => '%'.$title.'%', ':source' => COUPON_TYPE), 'id');
				$cids = implode('\',\'', array_keys($cids));
				$condition = ' AND extra IN(\''.$cids.'\')';
			}
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('activity_exchange')." WHERE uniacid = :uniacid AND type = :type ".$condition, array(':uniacid' => $_W['uniacid'], ':type' => COUPON_TYPE));
			$list = pdo_fetchall("SELECT * FROM ".tablename('activity_exchange')." WHERE uniacid = :uniacid AND type = :type ".$condition." ORDER BY id desc LIMIT ".($pindex - 1)*$psize.','. $psize, array(':uniacid' => $_W['uniacid'], ':type' => COUPON_TYPE));
			if (!empty($list)) {
				foreach($list as &$ex) {
					$ex['coupon'] = activity_coupon_info($ex['extra']);
					$ex['starttime'] = date('Y-m-d', $ex['starttime']);
					$ex['endtime'] = date('Y-m-d', $ex['endtime']);
				}
				unset($ex);
			}
			$pager = pagination($total, $pindex, $psize);
		}
		if ($op == 'delete') {
			$id = intval($_GPC['id']);
			$exist = pdo_get('activity_exchange', array('id' => $id, 'uniacid' => $_W['uniacid']));
			if (empty($exist)) {
				message('兑换卡券不存在', referer(), 'info');
			}
			pdo_delete('activity_exchange', array('id' => $id, 'uniacid' => $_W['uniacid']));
			message('兑换卡券删除成功', referer(), 'success');
		}
		include $this->template('couponexchange');
	}

	public function doWebGoodsexchange() {
		global $_W, $_GPC;
		$uni_setting = pdo_get('uni_settings', array('uniacid' => $_W['uniacid']), array('exchange_enable'));
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		/*获取积分类型*/
		$creditnames = array();
		$unisettings = uni_setting($uniacid, array('creditnames'));
		foreach ($unisettings['creditnames'] as $key=>$credit) {
			if (!empty($credit['enabled'])) {
				$creditnames[$key] = $credit['title'];
			}
		}
		if ($op == 'post') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = pdo_get('activity_exchange', array('id' => $id, 'uniacid' => $_W['uniacid']));
				if (empty($item)) {
					message('未找到指定兑换礼品或已删除.', $this->createWeburl('goodsexchange', array('op' => 'display')),'error');
				} else {
					$item['extra'] = iunserializer($item['extra']);
				}
			} else {
				$item['starttime'] = TIMESTAMP;
				$item['endtime'] = TIMESTAMP + 6 * 86400;
			}
			if (checksubmit('submit')) {
				$data['title'] = !empty($_GPC['title']) ? trim($_GPC['title']) : message('请输入兑换名称！');
				$data['credittype'] = !empty($_GPC['credittype']) ? trim($_GPC['credittype']) : message('请选择积分类型！');
				$data['credit'] = intval($_GPC['credit']);
				if (empty($_GPC['extra']['title'])) {
					message('请输入兑换礼品的名称');
				}
				$data['extra'] = iserializer($_GPC['extra']);
				$data['thumb'] = trim($_GPC['thumb']);
				$data['status'] = trim($_GPC['status']);
				$data['pretotal'] = intval($_GPC['pretotal']) ? intval($_GPC['pretotal']) : message('请输入每人最大兑换次数');
				$data['total'] = intval($_GPC['total']) ? intval($_GPC['total']) : message('请输入兑换总数');
				$data['type'] = 3;
				$data['description'] = !empty($_GPC['description']) ? trim($_GPC['description']) : message('请输入兑换说明！');

				$starttime = strtotime($_GPC['datelimit']['start']);
				$endtime = strtotime($_GPC['datelimit']['end']);
				if ($endtime == $starttime) {
					$endtime = $endtime + 86399;
				}
				$data['starttime'] = $starttime;
				$data['endtime'] = $endtime;
				if (empty($id)) {
					$data['uniacid'] = $_W['uniacid'];
					pdo_insert('activity_exchange', $data);
					message('添加真实物品兑换成功', $this->createWeburl('goodsexchange', array('op' => 'display')), 'success');
				} else {
					pdo_update('activity_exchange', $data, array('id' => $id, 'uniacid'=>$_W['uniacid']));
					message('更新真实物品兑换成功', $this->createWeburl('goodsexchange', array('op' => 'display')), 'success');
				}
			}
		}
		if ($op == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$where = ' WHERE type = 3 AND uniacid = :uniacid ';
			$params = array(':uniacid' => $_W['uniacid']);
			$title = trim($_GPC['keyword']);
			if (!empty($title)) {
				$where .= " AND title LIKE '%{$title}%'";
			}

			$list = pdo_fetchall('SELECT * FROM '.tablename('activity_exchange')." $where ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('activity_exchange'). $where , $params);
			$pager = pagination($total, $pindex, $psize);
			foreach ($list as &$row) {
				$extra = iunserializer($row['extra']);
				$row['extra'] = $extra;
				$row['thumb'] = tomedia($row['thumb']);
			}
			unset($row);
		}
		if ($op == 'delete') {
			$id = intval($_GPC['id']);
			if (!empty($id)){
				$item = pdo_get('activity_exchange', array('id' => $id, 'uniacid' => $_W['uniacid']), array('id'));
			}
			if (empty($item)) {
				message('删除失败,指定兑换不存在或已删除.');
			}
			pdo_delete('activity_exchange', array('id'=>$id, 'uniacid'=>$_W['uniacid']));
			message('删除成功.', referer(),'success');
		}
		//发货记录
		if ($op == 'deliver') {
			$exchanges = pdo_fetchall('SELECT id, title FROM ' . tablename('activity_exchange') . ' WHERE uniacid = :uniacid ORDER BY id DESC', array(':uniacid' => $_W['uniacid']));
			$starttime = empty($_GPC['time']['start']) ? strtotime('-6 month') : strtotime($_GPC['time']['start']);
			$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
			$where = " WHERE a.uniacid=:uniacid AND a.createtime >= :starttime AND a.createtime <= :endtime";
			$params = array(
				':uniacid' => $_W['uniacid'],
				':starttime' => $starttime,
				':endtime' => $endtime,
			);
			$uid = addslashes($_GPC['uid']);
			if (!empty($uid)) {
				$where .= ' AND ((a.name=:uid) or (a.mobile = :uid))';
				$params[':uid'] = $uid;
			}
			$exid = intval($_GPC['exid']);
			if (!empty($exid)) {
				$where .= " AND b.id = {$exid}";
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT a.*, b.title,b.extra,b.thumb FROM ".tablename('activity_exchange_trades_shipping'). ' AS a LEFT JOIN ' . tablename('activity_exchange') . ' AS b ON a.exid = b.id ' . " $where ORDER BY tid DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('activity_exchange_trades_shipping') . ' AS a LEFT JOIN ' . tablename('activity_exchange') . ' AS b ON a.exid = b.id '. $where , $params);
			if (checksubmit('export', true)) {
				$header = array(
					'title' => '标题', 'extra' => '兑换物品', 'name' => '收件人','createtime' => '兑换时间', 'mobile' => '收件人电话', 'zipcode' => '收件人邮编', 'address' => '收件地址', 'status' => '状态'
				);
				$html = "\xEF\xBB\xBF";
				foreach ($header as $li) {
					$html .= $li . "\t ,";
				}
				$html .= "\n";
				foreach ($list as $deliver) {
					foreach ($header as $key => $title) {
						if ($key == 'createtime') {
							$html .= date('Y-m-d', $deliver[$key]) . "\t ,";
						} elseif ($key == 'extra') {
							$extra = iunserializer($deliver[$key]);
							$html .= $extra['title']. "\t ,";
						} elseif ($key == 'status') {
							switch ($deliver['status']) {
								case '0' :
									$status = '待发货';
									break;
								case '1' :
									$status = '待收货';
									break;
								case '2' :
									$status = '已收货';
									break;
								case '-1' :
									$status = '已关闭';
									break;
							}
							$html .= $status . "\t ,";
						} else {
							$html .= $deliver[$key] . "\t ,";
						}
					}
					$html .= "\n";
				}
				$html .= "\n";
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=会员数据.csv");
				echo $html;
				exit();
			}
			if (!empty($list)) {
				$uids = array();
				foreach ($list as $row) {
					$uids[] = $row['uid'];
				}
				$members = mc_fetch($uids, array('uid', 'nickname'));
				foreach ($list as &$row) {
					$row['extra'] = iunserializer($row['extra']);
					$row['nickname'] = $members[$row['uid']]['nickname'];
					$row['thumb'] = tomedia($row['thumb']);
				}
				unset($row);
			}

			$pager = pagination($total, $pindex, $psize);
		}
		if ($op == 'receiver') {
			$id = intval($_GPC['id']);
			$shipping = pdo_get('activity_exchange_trades_shipping', array('uniacid' => $_W['uniacid'], 'tid' => $id));
			if (checksubmit('submit')) {
				$data = array(
					'name'=>$_GPC['realname'],
					'mobile'=>$_GPC['mobile'],
					'province'=>$_GPC['reside']['province'],
					'city'=>$_GPC['reside']['city'],
					'district'=>$_GPC['reside']['district'],
					'address'=>$_GPC['address'],
					'zipcode'=>$_GPC['zipcode'],
					'status'=>intval($_GPC['status'])
				);
				pdo_update('activity_exchange_trades_shipping', $data, array('tid' => $id));
				message('更新发货人信息成功', referer(), 'success');
			}
		}
		if ($op == 'record') {
			$exchanges_info = pdo_getall('activity_exchange', array('uniacid' => $_W['uniacid'], 'type' => '3'), array('id', 'title'));
			$starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
			$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
	
			$where = " WHERE a.uniacid=:uniacid AND a.type = 3 AND a.createtime>=:starttime AND a.createtime<:endtime";
			$params = array(
				':uniacid' => $_W['uniacid'],
				':starttime' => $starttime,
				':endtime' => $endtime,
			);
			$uid = intval($_GPC['uid']);
			if (!empty($uid)) {
				$where .= ' AND a.uid=:uid';
				$params[':uid'] = $uid;
			}
			$exid = intval($_GPC['exid']);
			if (!empty($exid)) {
				$where .= " AND b.id = {$exid}";
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
	
			$list = pdo_fetchall("SELECT a.*, b.title,b.extra,b.thumb FROM ".tablename('activity_exchange_trades'). ' AS a LEFT JOIN ' . tablename('activity_exchange') . ' AS b ON a.exid = b.id ' . " $where ORDER BY tid DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('activity_exchange_trades') . ' AS a LEFT JOIN ' . tablename('activity_exchange') . ' AS b ON a.exid = b.id '. $where , $params);
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				$uids = array();
				foreach ($list as $row) {
					$uids[] = $row['uid'];
				}
				load()->model('mc');
				$members = mc_fetch($uids, array('uid', 'nickname'));
				foreach ($list as &$row) {
					$row['extra'] = iunserializer($row['extra']);
					$row['nickname'] = $members[$row['uid']]['nickname'];
					$row['thumb'] = tomedia($row['thumb']);
				}
				unset($row);
			}
		}
		//删除兑换记录
		if ($op == 'record-del') {
			$tid = intval($_GPC['id']);
			if (empty($tid)) {
				message('没有指定的兑换记录', referer(), 'error');
			}
			pdo_delete('activity_exchange_trades_shipping', array('uniacid' => $_W['uniacid'], 'tid' => $tid));
			pdo_delete('activity_exchange_trades', array('uniacid' => $_W['uniacid'], 'tid' => $tid));
			message('删除兑换记录成功', referer(), 'success');
		}
		include $this->template('goodsexchange');
	}

	public function doWebStorelist() {
		global $_W, $_GPC;
		$coupon_api = new coupon();
		$_W['page']['title'] = '商家设置-粉丝营销';
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($op == 'post') {
			$id = intval($_GPC['id']);
			if ($id > 0) {
				$location = pdo_get('activity_stores', array('id' => $id, 'uniacid' => $_W['uniacid']));
				if (empty($location)) {
					message('商家不存在', referer(), 'info');
				}
				if (COUPON_TYPE == WECHAT_COUPON) {
					$location_info = $coupon_api->LocationGet($location['location_id']);
					if (is_error($location_info)) {
						message("从微信获取门店信息失败,错误详情:{$location_info['message']}", referer(), 'error');
					}
					$update_status = $location_info['business']['base_info']['update_status'];
					$location['open_time_start'] = '8:00';
					$location['open_time_end'] = '24:00';
					$open_time = explode('-', $location['open_time']);
					if (!empty($open_time)) {
						$location['open_time_start'] = $open_time[0];
						$location['open_time_end'] = $open_time[1];
					}
					$location['category'] = iunserializer($location['category']);
					$location['categorys'] = $location['category'];
					$location['category'] = rtrim(implode('-', $location['category']), '-');
					$location['address'] = $location['provice'].$location['city'].$location['district'].$location['address'];
					$location['baidumap'] = array('lng' => $location['longitude'], 'lat' => $location['latitude']);
					$photo_lists = iunserializer($location['photo_list']);
					$location['photo_list'] = array();
					if (!empty($photo_lists)) {
						foreach ($photo_lists as $li) {
							if (!empty($li['photo_url'])) {
								$location['photo_list'][] = $li['photo_url'];
							}
						}
					}
				} else {
					$location['category'] = iunserializer($location['category']);
					$location['photo_list'] = iunserializer($location['photo_list']);
					foreach ($location['photo_list'] as &$photo) {
						$photo = $photo['photo_url'];
					}
					unset($photo);
					$location['opentime'] = explode('-', $location['open_time']);
					$location['open_time_start'] = $location['opentime'][0];
					$location['open_time_end'] = $location['opentime'][1];
					$item = $location;
				}
			} else {
				$item['open_time_start'] = '8:00';
				$item['open_time_end'] = '24:00';
			}
			if (checksubmit('submit')) {
				if (COUPON_TYPE == WECHAT_COUPON && $id) {
					if (empty($location['location_id'])) {
						message('门店正在审核中或审核未通过，不能更新门店信息', referer(), 'error');
					}
					if ($update_status == 1) {
						message('服务信息正在更新中，尚未生效，不允许再次更新', referer(), 'error');
					}
					$data['telephone'] = trim($_GPC['telephone']) ? trim($_GPC['telephone']) : message('门店电话不能为空');
					if (empty($_GPC['photo_list'])) {
						message('门店图片不能为空');
					} else {
						foreach($_GPC['photo_list'] as $val) {
							if (empty($val)) continue;
							$data['photo_list'][] = array('photo_url' => $val);
						}
					}
					$data['avg_price'] = intval($_GPC['avg_price']);
					if (empty($_GPC['open_time_start']) || empty($_GPC['open_time_end'])) {
						message('营业时间不能为空');
					} else {
						$data['open_time'] = $_GPC['open_time_start'] . '-' . $_GPC['open_time_end'];
					}
					$data['recommend'] = urlencode(trim($_GPC['recommend']));
					$data['special'] = trim($_GPC['special']) ? urlencode(trim($_GPC['special'])) : message('特色服务不能为空');
					$data['introduction'] = urlencode(trim($_GPC['introduction']));
					$data['poi_id'] = $location['location_id'];
					$status = $coupon_api->LocationEdit($data);
					if (is_error($status)) {
						message($status['message'], '', 'error');
					} else {
						unset($data['poi_id']);
						$data['photo_list'] = iserializer($data['photo_list']);
						$data['recommend'] = $_GPC['recommend'];
						$data['special'] = trim($_GPC['special']);
						$data['introduction'] = trim($_GPC['introduction']);
						pdo_update('activity_stores', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
						message('门店信息已提交，等待微信审核', $this->createWeburl('storelist'), 'success');
					}
				}
				$store_data = array(
					'business_name' => trim($_GPC['business_name']),
					'branch_name' => trim($_GPC['branch_name']),
					'category' => array(
						'cate' => trim($_GPC['class']['cate']),
						'sub' => trim($_GPC['class']['sub']),
						'clas' => trim($_GPC['class']['clas'])
					),
					'province' => trim($_GPC['reside']['province']),
					'city' => trim($_GPC['reside']['city']),
					'district' => trim($_GPC['reside']['district']),
					'address' => trim($_GPC['address']),
					'longitude' => trim($_GPC['baidumap']['lng']),
					'latitude' => trim($_GPC['baidumap']['lat']),
					'avg_price' => intval($_GPC['avg_price']),
					'telephone' => trim($_GPC['telephone']),
					'open_time' => trim($_GPC['open_time_start']). '-'.trim($_GPC['open_time_end']),
					'recommend' => trim($_GPC['recommend']),
					'special' => trim($_GPC['special']),
					'introduction' => trim($_GPC['introduction']),
				);
				if (empty($store_data['business_name'])) {
					message('门店名称不能为空');
				}
				if (empty($store_data['category']['cate'])) {
					message('门店类目不能为空');
				}
				if (empty($store_data['province']) || empty($store_data['city']) || empty($store_data['district']) || empty($store_data['address'])) {
					message('请设置门店所在省、市、区及详细地址');
				}
				if (empty($store_data['longitude']) || empty($store_data['latitude'])) {
					message('请选择门店所在地理位置坐标');
				}
				if (empty($store_data['telephone'])) {
					message('门店电话不能为空');
				}
				if (empty($store_data['open_time'])) {
					message('请设置营业时间');
				}
				if (empty($_GPC['photo_list'])) {
					message('门店图片不能为空');
				}
				foreach ($_GPC['photo_list'] as $photourl) {
					if (!empty($photourl)) {
						$store_data['photo_list'][] = array('photo_url' => $photourl);
					}
				}
				if (!empty($id)) {
					$insert = $store_data;
					unset($insert['sid']);
					$insert['source'] = COUPON_TYPE;
					$insert['category'] = iserializer($insert['category']);
					$insert['photo_list'] = iserializer($insert['photo_list']);
					pdo_update('activity_stores',$insert,array('id' => $id, 'uniacid' => $_W['uniacid']));
					message('门店信息更新成功', $this->createWeburl('storelist'), 'success');
				} else {
					$insert = $store_data;
					$insert['uniacid'] = $_W['uniacid'];
					$insert['source'] = COUPON_TYPE;
					unset($insert['sid']);
					$insert['category'] = iserializer($insert['category']);
					$insert['photo_list'] = iserializer($insert['photo_list']);
					$insert['status'] = 1;
					$result = pdo_insert('activity_stores', $insert);
					if (COUPON_TYPE == WECHAT_COUPON) {
						$insert['status'] = 2;
						$store_data['sid'] = pdo_insertid();
						$status = $coupon_api->LocationAdd($store_data);
						if (is_error($status)) {
							pdo_delete('activity_stores', array('uniacid' => $_W['uniacid'], 'id' => $store_data['sid']));
							message($status['message'], '', 'error');
						}
					}
				}
				message('门店添加成功', $this->createWeburl('storelist'), 'success');
			}
		}

		if ($op == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$limit = 'ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ", {$psize}";
			$total  = pdo_fetchcolumn('SELECT COUNT(*) FROM '. tablename('activity_stores')." WHERE uniacid = :uniacid AND source = :source", array(':uniacid' => $_W['uniacid'], ':source' => COUPON_TYPE));
			$list = pdo_getslice('activity_stores',  array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array($pindex, $psize));
			$pager = pagination($total,$pindex,$psize);
			foreach ($list as &$key) {
				$key['category'] = iunserializer($key['category']);
				$key['category_'] = implode('-', $key['category']);
			}
			unset($key);
		}
		if ($op =='delete') {
			$id = intval($_GPC['id']);
			$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_clerks') . ' WHERE uniacid = :uniacid AND storeid = :id', array(':id' => $id, ':uniacid' => $_W['uniacid']));
			$count = intval($count);
			if ($count > 0) {
				message("该门店下有{$count}名店员.请将店员变更到其他门店后,再进行删除操作", referer(), 'error');
			}
			pdo_delete('activity_stores',array('id' => $id, 'uniacid' => $_W['uniacid']));
			if (COUPON_TYPE == WECHAT_COUPON) {
				$location = pdo_get('activity_stores', array('uniacid' => $_W['uniacid'], 'id' => $id), array('status', 'location_id'));
				if (!empty($location['location_id'])) {
					$status = $coupon_api->LocationDel($location['location_id']);
				}
				if (is_error($status)) {
					message("删除本地门店数据成功<br>通过微信接口删除微信门店数据失败,请登陆微信公众平台手动删除门店<br>错误原因：{$status['message']}", $this->createWeburl('storelist'), 'error');
				}
			}
			message('删除成功',referer(), 'success');
		}
		if ($op == 'import') {
			$begin = intval($_GPC['begin']);
			$data = $coupon_api->LocationBatchGet(array('begin' => $begin));
			if (is_error($data)) {
				message($data['message'], referer(), 'error');
			}
			if (empty($begin)) {
				pdo_update('activity_stores', array('status' => 3), array('uniacid' => $_W['uniacid'], 'source' => 2));
			}
			$location = $data['business_list'];
			$status2local = array('', 3, 2, 1, 3);
			if (!empty($location)) {
				foreach ($location as $row) {
					$isexist = array();
					$store_info = array();
					$store_info = $row['base_info'];
					//门店是否可用状态。1表示系统错误、2表示审核中、3审核通过、 4审核驳回。
					//如果不是审核通过的门店没有通过审核,优先查找sid,如果没有sid(微信公众平台添加的门店没有sid),则查找poi_id(这个poi_id在审核通过后会变(这里就会造成重复))
					if (!empty($store_info['sid'])) {
						$select_type = 'sid';
						$isexist = pdo_getcolumn('activity_stores', array('uniacid' => $_W['uniacid'], 'id' => $store_info['sid']), 'id');
					}
					if (empty($isexist)) {
						$select_type = 'poi_id';
						$isexist = pdo_get('activity_stores', array('uniacid' => $_W['uniacid'], 'location_id' => $store_info['poi_id']));
					}

					$store_info['uniacid'] = $_W['uniacid'];
					$store_info['status'] = $status2local[$store_info['available_state']];
					$store_info['location_id'] = $store_info['poi_id'];
					$category_temp = explode(',', $store_info['categories'][0]);
					$store_info['category'] = iserializer(array('cate' => $category_temp[0], 'sub' => $category_temp[1], 'clas' => $category_temp[2]));
					$store_info['photo_list'] = iserializer($store_info['photo_list']);
					$store_info['source'] = 2;
					$storeid = $select_type == 'poi_id' ? $store_info['poi_id'] : $store_info['sid'];
					unset($store_info['categories'], $store_info['poi_id'], $store_info['update_status'], $store_info['available_state'],$store_info['offset_type'], $store_info['type'], $store_info['sid'], $store_info['qualification_list'], $store_info['upgrade_comment'], $store_info['upgrade_status'], $store_info['mapid']);
					if (empty($isexist)) {
						pdo_insert('activity_stores', $store_info);
					} else {
						if ($select_type == 'poi_id') {
							$result = pdo_update('activity_stores', $store_info, array('uniacid' => $_W['uniacid'], 'location_id' => $storeid));
						} else {
							$result = pdo_update('activity_stores', $store_info, array('uniacid' => $_W['uniacid'], 'id' => $storeid));
						}
						$result = pdo_update('activity_stores', $store_info, array('uniacid' => $_W['uniacid'], 'id' => $storeid));
					}
				}
				message('正在导入微信门店,请不要关闭浏览器...', $this->createWeburl('storelist', array('op' => 'import', 'begin' => $begin + 50)), 'success');
			}
			message('导入门店成功', $this->createWeburl('storelist'), 'success');
		}
		if ($op == 'sync') {
			$type = trim($_GPC['type']);
			if ($type == '1') {
				$cachekey = "storesync:{$_W['uniacid']}";
				$cache = cache_delete($cachekey);
			}
			we7_coupon_activity_store_sync();
			message(error(0, '更新门店信息成功'), $this->createWeburl('storelist'), 'ajax');
		}
		include $this->template('storelist');
	}

	public function doWebClerklist() {
		global $_W, $_GPC;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		$user_permissions = pdo_getall('users_permission', array('uniacid' => $_W['uniacid'], 'type' => $this->module['name'], 'uid <>' => ''), '', 'uid');
		$uids = !empty($user_permissions) && is_array($user_permissions) ? array_keys($user_permissions) : array();
		$users_lists = array();
		if (!empty($uids)) {
			$users_lists = pdo_getall('users', array('uid' => $uids), '', 'uid');
		}
		$compare = ver_compare(IMS_VERSION, '1.0');
		if ($compare == -1) {
			message('请将微擎系统升级至1.0以上的最新版本', '', 'error');
		}
		$current_module_permission = module_permission_fetch($this->module['name']);
		if (!empty($current_module_permission)) {
			foreach ($current_module_permission as $key => $permission) {
				$permission_name[$permission['permission']] = $permission['title'];
			}
		}
		if (!empty($user_permissions)) {
			foreach ($user_permissions as $key => &$permission) {
				$permission['permission'] = explode('|', $permission['permission']);
				foreach ($permission['permission'] as $k => $val) {
					$permission['permission'][$val] = $permission_name[$val];
					unset($permission['permission'][$k]);
				}
				$permission['user_info'] = $users_lists[$key];
			}
			unset($permission);
		}
		$clerk_list = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), '', 'uid');
		$available_user = $user_permissions;
		foreach ($available_user as $key => $value) {
			if (!empty($clerk_list[$key])) {
				unset($available_user[$key]);
			}
		}
		if ($op == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 30;
			$limit = 'ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ", {$psize}";
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('activity_clerks')." WHERE uniacid = :uniacid ", array(':uniacid' => $_W['uniacid']));
			$list = pdo_fetchall("SELECT * FROM ".tablename('activity_clerks')." WHERE uniacid = :uniacid {$limit}", array(':uniacid' => $_W['uniacid']));
			$uids = array(0);
			foreach ($list as $row) {
				if ($row['uid'] > 0) {
					$uids[] = $row['uid'];
				}
			}
			$uids = implode(',', $uids);
			$users = pdo_fetchall('SELECT username,uid FROM ' . tablename('users') . " WHERE uid IN ({$uids})", array(), 'uid');
			$pager = pagination($total, $pindex, $psize);
			$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid']), array('id', 'business_name', 'branch_name'), 'id');
		}
		if ($op == 'checkname' && $_W['isajax']) {
			$username = trim($_GPC['username']);
			$uid = intval($_GPC['uid']);
			if (!empty($uid)) {
				$exist = pdo_fetch("SELECT * FROM ". tablename('users'). " WHERE uid <> :uid AND username = :username", array(':uid' => $uid, ':username' => trim($_GPC['username'])));
			} else {
				$exist = pdo_get('users', array('username' => $username));
			}
			if (empty($exist)) {
				message(error(1), '', 'ajax');
			} else {
				message(error(0), '', 'ajax');
			}
		}
		if ($op == 'post') {
			$uid = intval($_GPC['uid']);
			$user_info = user_single($uid);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$clerk = pdo_get('activity_clerks', array('id' => $id, 'uniacid' => $_W['uniacid']));
			}
			if (checksubmit()) {
				$name = trim($_GPC['name']) ? trim($_GPC['name']) : message('店员名称不能为空');
				$mobile =  trim($_GPC['mobile']) ? trim($_GPC['mobile']) : message('手机号不能为空');
				$storeid =  intval($_GPC['storeid']) ? intval($_GPC['storeid']) : message('请选择所在门店');
				$password = trim($_GPC['password']);
				if (istrlen($password) < 8) {
					message('必须输入核销密码，且密码长度不得低于8位。');
				}
				$password_exist = pdo_get('activity_clerks', array('uniacid' => $_W['uniacid'], 'password' => $password, 'id <>' => $id));
				if (!empty($password_exist)) {
					message('密码已存在，请重新输入密码');
				}
				$data = array(
					'uniacid' => $_W['uniacid'],
					'storeid' => $storeid,
					'name' => $name,
					'mobile' => $mobile,
					'openid' => trim($_GPC['openid']),
					'nickname' => trim($_GPC['nickname']),
					'uid' => $uid,
					'password' => $_GPC['password']
				);
				if (empty($_GPC['password'])) {
					unset($data['password']);
				}
				if (empty($clerk['id'])) {
					pdo_insert('activity_clerks', $data);
				} else {
					pdo_update('activity_clerks', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
				}
				message('编辑店员资料成功', $this->createWeburl('clerklist'), 'success');
			}
			$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array('id', 'business_name', 'branch_name'));
		}

		if ($op == 'verify') {
			if ($_W['isajax']) {
				$openid = trim($_GPC['openid']);
				$nickname = trim($_GPC['nickname']);
				if (!empty($openid)) {
					$exist = pdo_get('mc_mapping_fans', array('acid' => $_W['acid'], 'openid' => $openid), array('openid', 'nickname'));
				} else {
					$exist = pdo_get('mc_mapping_fans', array('nickname' => $nickname, 'acid' => $_W['acid']), array('openid', 'nickname'));
				}
				if (empty($exist)) {
					message(error(-1, '未找到对应的粉丝编号，请检查昵称或openid是否有效'), '', 'ajax');
				}
				message(error(0, $exist), '', 'ajax');
			}
		}
		if ($op == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('activity_clerks',array('id' => intval($_GPC['id']), 'uniacid' => $_W['uniacid']));
			message("删除成功",referer(),'success');
		}
		if ($op == 'switch') {
			$clerkid = intval($_GPC['id']);
			$clerk = pdo_get('activity_clerks', array('id' => $clerkid, 'uniacid' => $_W['uniacid']));
			$user = user_single(array('uid' => $clerk['uid']));
			$cookie = array();
			$cookie['uid'] = $user['uid'];
			$cookie['lastvisit'] = $user['lastvisit'];
			$cookie['lastip'] = $user['lastip'];
			$cookie['hash'] = md5($user['password'] . $user['salt']);
			$compare = ver_compare(IMS_VERSION, '1.0');
			if ($compare == -1) {
				$session = base64_encode(json_encode($cookie));
			} else {
				$session = authcode(json_encode($cookie), 'encode');
			}
			isetcookie('__session', $session, 7 * 86400);
			header('Location:' . $this->createWeburl('clerkdesk', array('uniacid' => $clerk['uniacid'], 'op' => 'index')));
			exit;
		}
		include $this->template('clerklist');
	}

	public function doWebClerkdeskwelcome() {
		global $_W, $_GPC;
		$_W['page']['title'] = '店员工作台';
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
		if ($op == 'index') {
			$current_user_permission_info = pdo_get('users_permission', array('uniacid' => $_W['uniacid'], 'uid' => $_W['uid'], 'type' => $this->module['name']));
			$current_user_permission = explode('|', $current_user_permission_info['permission']);
			$permissions = array(
				array(
					'title' => '快捷交易',
					'items' => array(
						array(
							'title' => '积分充值',
							'icon' => 'fa fa-bar-chart',
							'type' => 'modal',
							'url' => 'credit1',
							'permission' => 'we7_coupon_permission_mc_credit1'
						),
						array(
							'title' => '余额充值',
							'icon' => 'fa fa-bar-chart',
							'type' => 'modal',
							'url' => 'credit2',
							'permission' => 'we7_coupon_permission_mc_credit2'
						),
						array(
							'title' => '消费',
							'icon' => 'fa fa-bar-chart',
							'type' => 'modal',
							'url' => 'consume',
							'permission' => 'we7_coupon_permission_mc_conusme'
						),
						array(
							'title' => '发放会员卡',
							'icon' => 'fa fa-bar-chart',
							'type' => 'modal',
							'url' => 'card',
							'permission' => 'we7_coupon_permission_mc_card'
						),
					),
				),
				array(
					'title' => '卡券核销',
					'items' => array(
						array(
							'title' => '卡券核销',
							'icon' => 'fa fa-bar-chart',
							'type' => 'modal',
							'url' => 'cardconsume',
							'permission' => 'we7_coupon_permission_coupon_consume'
						),
					)
				)
			);
			if ($_W['user']['type'] == 3) {
				foreach ($permissions as $key => &$row) {
					foreach ($row['items'] as $key1 => $row1) {
						if (!in_array($row1['permission'], $current_user_permission)) {
							unset($row['items'][$key1]);
						}
					}
				}
				unset($row);
			}
		}
		include $this->template('clerkdesk');
	}

	public function doWebPaycenter() {
		global $_W, $_GPC;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'pay';
		$_W['page']['title'] = '刷卡支付-微信收款';
		if ($op == 'pay') {
			if ($_W['isajax']) {
				$post = $_GPC['__input'];
				$fee = trim($post['fee']) ? trim($post['fee']) : message(error(-1, '订单金额不能为空'),  '', 'ajax');
				$body = trim($post['body']) ? trim($post['body']) : message(error(-1, '商品名称不能为空'),  '', 'ajax');
				$code = trim($post['code']);
				$uid = intval($post['member']['uid']);
				
				if ($post['cash'] > 0 && empty($post['code'])) {
					message(error(-1, '授权码不能为空'), '', 'ajax');
				}
				$total = $money = floatval($post['fee']);
				if (!$total) {
					message(error(-1, '消费金额不能为空'), '', 'ajax');
				}
				$log = "系统日志:会员消费【{$total}】元";
				if ($uid > 0) {
					$user = pdo_get('mc_members', array('uniacid' => $_W['uniacid'], 'uid' => $uid));
					if (empty($user)) {
						message(error(-1, '用户不存在'), '', 'ajax');
					}
					$user['groupname'] = $_W['account']['groups'][$user['groupid']]['title'];
					load()->model('card');
					$card = card_setting();
					$member = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $user['uid']));
					if (!empty($card) && $card['status'] == 1 && !empty($member)) {
						$user['discount'] = $card['discount'][$user['groupid']];
						if (!empty($user['discount']) && !empty($user['discount']['discount'])) {
							if ($total >= $user['discount']['condition']) {
								$log .= ",所在会员组【{$user['groupname']}】,可享受满【{$user['discount']['condition']}】元";
								if ($card['discount_type'] == 1) {
									$log .= "减【{$user['discount']['discount']}】元";
									$money = $total - $user['discount']['discount'];
								} else {
									$discount = $user['discount']['discount'] * 10;
									$log .= "打【{$discount}】折";
									$money = $total * $user['discount']['discount'];
								}
								if ($money < 0) {
									$money = 0;
								}
								$log .= ",实收金额【{$money}】元";
							}
						}
						$post_money = strval($post['fact_fee']);
						if ($post_money != $money) {
							message(error(-1, '实收金额错误'),  '', 'ajax');
						}

						$post_credit1 = intval($post['credit1']);
						if ($post_credit1 > 0) {
							if ($post_credit1 > $user['credit1']) {
								message(error(-1, '超过会员账户可用积分'),  '', 'ajax');
							}
						}

						$post_offset_money = trim($post['offset_money']);
						$offset_money = 0;
						if ($post_credit1 && $card['offset_rate'] > 0 && $card['offset_max'] >= 0) {
							if ($card['offset_max'] == '0') {
								$offset_money = $post_credit1/$card['offset_rate'];
							} else {
								$offset_money = min($card['offset_max'], $post_credit1/$card['offset_rate']);
							}
							if ($offset_money != $post_offset_money) {
								message(error(-1, '积分抵消金额错误'),  '', 'ajax');
							}
							$credit1 = $post_credit1;
							$log .= ",使用【{$post_credit1}】积分抵消【{$offset_money}】元";
						}
					}
					$credit2 = floatval($post['credit2']);
					if ($credit2 > 0) {
						if ($credit2 > $user['credit2']) {
							message(error(-1, '超过会员账户可用余额'),  '', 'ajax');
						}
						$log .= ",使用余额支付【{$credit2}】元";
					}
				} else {
					$post['cash'] = $post['fee'];
				}
				$cash = floatval($post['cash']);
				$sum = strval($credit2 + $cash + $offset_money);
				$money = strval($money);
				if ($sum != $money) {
					message(error(-1, '支付金额不等于实收金额'),  '', 'ajax');
				}
				$realname = $post['member']['realname'] ? $post['member']['realname'] :$post['member']['realname'];
				if ($cash <= 0) {
					//直接扣除积分和余额
					$data = array(
						'uniacid' => $_W['uniacid'],
						'uid' => $member['uid'],
						'status' => 0,
						'type' => 'wechat',
						'trade_type' => 'micropay',
						'fee' => $total,
						'final_fee' => $money,
						'credit1' => $post_credit1,
						'credit1_fee' => $offset_money,
						'credit2' => $credit2,
						'cash' => $cash,
						'body' => $body,
						'openid' => $member['openid'],
						'nickname' => $realname,
						'remark' => $log,
						'clerk_id' => $_W['user']['clerk_id'],
						'store_id' => $_W['user']['store_id'],
						'clerk_type' => $_W['user']['clerk_type'],
						'createtime' => TIMESTAMP,
						'status' => 1,
						'paytime' => TIMESTAMP,
						'credit_status' => 1,
					);
					pdo_insert('paycenter_order', $data);
					load()->model('mc');
					if ($post_credit1 > 0) {
						$status = mc_credit_update($member['uid'], 'credit1', -$post_credit1, array(0, "会员刷卡消费,使用积分抵现,扣除{$post_credit1积分}", 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
					}
					if ($credit2 > 0) {
						$status = mc_credit_update($member['uid'], 'credit2', -$credit2, array(0, "会员刷卡消费,使用余额支付,扣除{$credit2}余额", 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
						mc_notice_credit2($member['openid'], $member['uid'], $credit2, '', '收银台消费');
					}
					message(error(0, '支付成功'), $this->createWeburl('paycenter'), 'ajax');
				} else {
					$log .= ",使用刷卡支付【{$cash}】元";
					if (!empty($_GPC['remark'])) {
						$note = "店员备注：{$_GPC['remark']}";
					}
					$log = $note . $log;
					$isexist = pdo_get('paycenter_order', array('uniacid' => $_W['uniacid'], 'auth_code' => $code));
					if ($isexist) {
						message(error(-1, '每个二维码仅限使用一次，请刷新再试'), '', 'ajax');
					}
					$data = array(
						'uniacid' => $_W['uniacid'],
						'uid' => $member['uid'],
						'status' => 0,
						'type' => 'wechat',
						'trade_type' => 'micropay',
						'fee' => $total,
						'final_fee' => $money,
						'credit1' => $post_credit1,
						'credit1_fee' => $offset_money,
						'credit2' => $credit2,
						'cash' => $cash,
						'remark' => $log,
						'body' => $body,
						'openid' => $member['openid'],
						'nickname' => $realname,
						'auth_code' => $code,
						'clerk_id' => $_W['user']['clerk_id'],
						'store_id' => $_W['user']['store_id'],
						'clerk_type' => $_W['user']['clerk_type'],
						'createtime' => TIMESTAMP,
					);
					pdo_insert('paycenter_order', $data);
					$id = pdo_insertid();
					load()->classs('pay');
					$pay = Pay::create();
					$params = array(
						'tid' => $id,
						'module' => 'paycenter',
						'type' => 'wechat',
						'fee' => $cash,
						'body' => $body,
						'auth_code' => $code,
					);
					$pid = $pay->buildPayLog($params);
					if (is_error($pid)) {
						message($pid,  '', 'ajax');
					}
					$log = pdo_get('core_paylog', array('plid' => $pid));
					pdo_update('paycenter_order', array('pid' => $pid, 'uniontid' => $log['uniontid']), array('id' => $id));
					$data = array(
						'out_trade_no' => $log['uniontid'],
						'body' => $body,
						'total_fee' => $log['fee'] * 100,
						'auth_code' => $code,
						'uniontid' => $log['uniontid']
					);
					
					$result = $pay->buildMicroOrder($data);
					if ($result['result_code'] == 'SUCCESS') {
						if (is_error($result)) {
							message($result,  '', 'ajax');
						} else {
							$status = $pay->NoticeMicroSuccessOrder($result);
							if (is_error($status)) {
								message($status, '', 'ajax');
							}
							message(error(0, '支付成功'), $this->createWeburl('paycenter'), 'ajax');
						}
					} else {
						message($result,  '', 'ajax');
					}
				}
				exit();
			}
			$paycenter_records = pdo_fetchall("SELECT * FROM " .tablename('paycenter_order') . " WHERE uniacid = :uniacid AND clerk_id = :clerk_id ORDER BY id DESC LIMIT 0,10", array(':uniacid' => $_W['uniacid'], ':clerk_id' => $_W['user']['clerk_id']));
			$today_credit_total = pdo_fetchall("SELECT credit2 FROM " . tablename('paycenter_order') . " WHERE uniacid = :uniacid AND clerk_id = :clerk_id AND paytime > :starttime AND paytime < :endtime AND credit2 <> ''", array(':uniacid' => $_W['uniacid'], ':clerk_id' => trim($_W['user']['clerk_id']), ':starttime' => strtotime(date('Ymd')), ':endtime' => time()));
			$today_wechat_total = pdo_fetchall("SELECT cash FROM " . tablename('paycenter_order') . " WHERE uniacid = :uniacid AND clerk_id = :clerk_id AND paytime > :starttime AND paytime < :endtime AND cash <> ''", array(':uniacid' => $_W['uniacid'], ':clerk_id' => trim($_W['user']['clerk_id']), ':starttime' => strtotime(date('Ymd')), ':endtime' => time()));
			foreach ($today_wechat_total as $val) {
				$wechat_total += $val['cash'];
			}
			foreach ($today_credit_total as $val) {
				$credit_total += $val['credit2'];
			}
			$wechat_total = $wechat_total ? $wechat_total : '0';
			$credit_total = $credit_total ? $credit_total : '0';
			load()->model('card');
			$card_set = card_setting();
			$card_params = json_decode($card_set['params'], true);
			$grant_rate = $card_set['grant_rate'];
			unset($card_set['params'], $card_set['nums'], $card_set['times'], $card_set['business'], $card_set['html'], $card_set['description'], $card_set['card_id']);
			$card_set_str = json_encode($card_set);
		}

		if ($op == 'query') {
			if ($_W['isajax']) {
				$post = $_GPC['__input'];
				$uniontid = trim($post['uniontid']);
				load()->classs('pay');
				$pay = Pay::create();
				$result = $pay->queryOrder($uniontid, 2);
				if (is_error($result)) {
					message($result, '', 'ajax');
				}
				if ($result['trade_state'] == 'SUCCESS') {
					$status = $pay->NoticeMicroSuccessOrder($result);
					if (is_error($status)) {
						message($status, '', 'ajax');
					}
					message(error(0, '支付成功'), '', 'ajax');
				}
				message(error(-1, '支付失败,当前订单状态:' . $result['trade_state']), '', 'ajax');
			}
		}

		if ($op == 'checkpay') {
			if ($_W['isajax']) {
				$post = $_GPC['__input'];
				$uniontid = trim($post['uniontid']);
				load()->classs('pay');
				$pay = Pay::create();
				$result = $pay->queryOrder($uniontid, 2);
				if (is_error($result)) {
					message($result, '', 'ajax');
				}
				if ($result['trade_state'] == 'SUCCESS') {
					$status = $pay->NoticeMicroSuccessOrder($result);
					if (is_error($status)) {
						message($status, '', 'ajax');
					}
					message($result, '', 'ajax');
				}
				message($result, '', 'ajax');
			}
		}


		if ($op == 'check') {
			$set = card_setting();
			if (is_error($set)) {
				message($set, '', 'ajax');
			}
			$_GPC = $_GPC['__input'];
			$cardsn = trim($_GPC['cardsn']);
			$card_member = pdo_getall('mc_card_members', array('uniacid' => $_W['uniacid'], 'cardsn' => $cardsn));
			if (empty($card_member)) {
				message(error(-1, '卡号不存在或已经删除'), '', 'ajax');
			}
			if (count($card_member) > 1) {
				message(error(-1, '卡号对应用户不唯一'), '', 'ajax');
			}
			$card_member = $card_member[0];
			if ($card_member['status'] != 1) {
				message(error(-1, '该会员卡已被禁用'), '', 'ajax');
			}
			$member = pdo_get('mc_members', array('uniacid' => $_W['uniacid'], 'uid' => $card_member['uid']));
			if (empty($member)) {
				message(error(-1, '会员卡对应的会员不存在'), '', 'ajax');
			}
			$member['openid'] = $card_member['openid'];
			$member['createtime'] = $card_member['createtime'];
			$member['cardsn'] = $card_member['cardsn'];
			$member['groupname'] = $_W['account']['groups'][$member['groupid']]['title'];
			$member['discount_type'] = 0;
			$member['discount'] = array();
			$member['discount_cn'] = '暂无';
			$member['credit1'] = floatval($member['credit1']);
			$member['credit2'] = floatval($member['credit2']);
			$member['offset_rate'] = $set['offset_rate'];
			$member['offset_max'] = $set['offset_max'];
			if ($set['discount_type'] > 0 && !empty($set['discount'])) {
				$discount = $set['discount'][$member['groupid']];
				if (!empty($discount)) {
					$member['discount'] = $discount;
					$member['discount_type'] = $set['discount_type'];
					if ($set['discount_type'] == 1 ) {
						$member['discount_cn'] = "满 {$discount['condition']} 元减 {$discount['discount']}元";
					} else {
						$zhe = $discount['discount'] * 10;
						$member['discount_cn'] = "满 {$discount['condition']} 元打 {$zhe}折";
					}
				}
			}
			message(error(0, $member), '', 'ajax');
		}
		include $this->template('paycenterwxmicro');
	}

	public function doWebSignmanage() {
		global $_W, $_GPC;
		$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'sign-credit';
		if ($op == 'sign-credit') {
			$set = pdo_get('mc_card_credit_set', array('uniacid' => $_W['uniacid']));
			if (empty($set)) {
				$set = array();
			} else {
				$set['sign'] = iunserializer($set['sign']);
			}
			if (checksubmit()) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'sign' => array(
						'everydaynum' => intval($_GPC['sign']['everydaynum']),
						'first_group_day' => intval($_GPC['sign']['first_group_day']),
						'first_group_num' => intval($_GPC['sign']['first_group_num']),
						'second_group_day' => intval($_GPC['sign']['second_group_day']),
						'second_group_num' => intval($_GPC['sign']['second_group_num']),
						'third_group_day' => intval($_GPC['sign']['third_group_day']),
						'third_group_num' => intval($_GPC['sign']['third_group_num']),
						'full_sign_num' => intval($_GPC['sign']['full_sign_num']),
					),
					'content' => htmlspecialchars_decode($_GPC['content']),
				);
				$data['sign'] = iserializer($data['sign']);
				if (empty($set['uniacid'])) {
					pdo_insert('mc_card_credit_set', $data);
				} else {
					pdo_update('mc_card_credit_set', $data, array('uniacid' => $_W['uniacid']));
				}
				message('积分策略更新成功', referer(), 'success');
			}
		}
		if ($op == 'record-list') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename('mc_card_sign_record') . " WHERE uniacid = :uniacid ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $_W['uniacid']));
			foreach ($list as $key => &$value){
				$value['addtime'] = date('Y-m-d H:i:s', $value['addtime']);
				$value['realname'] = pdo_fetchcolumn("SELECT realname FROM ". tablename('mc_members'). ' WHERE uniacid = :uniacid AND uid = :uid', array(':uniacid' => $_W['uniacid'], ':uid' => $value['uid']));
			}
			unset($value);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('mc_card_sign_record'). " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
			$pager = pagination($total, $pindex, $psize);
		}
		if ($op == 'sign-status') {
			if (empty($setting)) {
				message(error(-1, '还没有开启会员卡,请先开启会员卡'), '', 'ajax');
			}
			$field = trim($_GPC['field']);
			if (!in_array($field, array('recommend_status', 'sign_status'))) {
				message(error(-1, '非法操作'), '', 'ajax');
			}
			pdo_update('mc_card', array($field => intval($_GPC['status'])), array('uniacid' => $_W['uniacid']));
			message(error(0, ''), '', 'ajax');
		}
		include $this->template('signmanage');
	}

	public function doWebNoticemanage() {
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
		if ($op == 'list') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 30;
			$limit = " ORDER BY id DESC LIMIT " . ($pindex -1) * $psize . ", {$psize}";

			$addtime = intval($_GPC['addtime']);
			$where = ' WHERE uniacid = :uniacid AND type = 1';
			$param = array(':uniacid' => $_W['uniacid']);

			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_notices') . " {$where}", $param);
			$notices = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_notices') . " {$where} {$limit}", $param);
			$pager = pagination($total, $pindex, $psize);
		}
		if ($op == 'post') {
			$id = intval($_GPC['id']);
			if ($id > 0) {
				$notice = pdo_get('mc_card_notices', array('uniacid' => $_W['uniacid'], 'id' => $id));
				if (empty($notice)) {
					message('通知不存在或已被删除', referer(), 'error');
				}
			}
			if (checksubmit()) {
				$title = trim($_GPC['title']) ? trim($_GPC['title']) : message('通知标题不能为空');
				$content = trim($_GPC['content']) ? trim($_GPC['content']) : message('通知内容不能为空');
				$data = array(
					'uniacid' => $_W['uniacid'],
					'type' => 1,
					'uid' => 0,
					'title' => $title,
					'thumb' => trim($_GPC['thumb']),
					'groupid' => intval($_GPC['groupid']),
					'content' => htmlspecialchars_decode($_GPC['content']),
					'addtime' => TIMESTAMP
				);
				if ($id > 0) {
					pdo_update('mc_card_notices', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
				} else {
					pdo_insert('mc_card_notices', $data);
				}
				message('发布通知成功', $this->createWeburl('noticemanage') , 'success');
			}
		}

		if ($op == 'del') {
			$id = intval($_GPC['id']);
			pdo_delete('mc_card_notices', array('uniacid' => $_W['uniacid'], 'id' => $id));
			message('删除成功', referer(), 'success');
		}
		include $this->template('noticemanage');
	}

	public function doWebStatcredit1() {
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
		load()->model('mc');
		$_W['page']['title'] = "积分统计-会员中心";
		$starttime = empty($_GPC['time']['start']) ? mktime(0, 0, 0, date('m') , 1, date('Y')) : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
		$num = ($endtime + 1 - $starttime) / 86400;

		if ($op == 'index') {
			$clerks = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
			$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array('id', 'business_name', 'branch_name'), 'id');
			$condition = ' WHERE uniacid = :uniacid AND credittype = :credittype AND createtime >= :starttime AND createtime <= :endtime';
			$params = array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => $starttime, ':endtime' => $endtime);
			$num = intval($_GPC['num']);
			if ($num > 0) {
				if ($num == 1) {
					$condition .= ' AND num >= 0';
				} else {
					$condition .= ' AND num <= 0';
				}
			}
			$min = intval($_GPC['min']);
			if ($min > 0 ) {
				$condition .= ' AND abs(num) >= :minnum';
				$params[':minnum'] = $min;
			}

			$max = intval($_GPC['max']);
			if ($max > 0 ) {
				$condition .= ' AND abs(num) <= :maxnum';
				$params[':maxnum'] = $max;
			}
			$clerk_id = intval($_GPC['clerk_id']);
			if (!empty($clerk_id)) {
				$condition .= ' AND clerk_id = :clerk_id';
				$params[':clerk_id'] = $clerk_id;
			}
			$store_id = trim($_GPC['store_id']);
			if (!empty($store_id)) {
				$condition .= " AND store_id = :store_id";
				$params[':store_id'] = $store_id;
			}
			$user = trim($_GPC['user']);
			if (!empty($user)) {
				$condition .= ' AND (uid IN (SELECT uid FROM '.tablename('mc_members').' WHERE uniacid = :uniacid AND (realname LIKE :username OR uid = :uid OR mobile LIKE :mobile)))';
				$params[':username'] = "%{$user}%";
				$params[':uid'] = intval($user);
				$params[':mobile'] = "%{$user}%";
			}

			$psize = 30;
			$pindex = max(1, intval($_GPC['page']));
			$limit = " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ", {$psize}";
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_credits_record') . $condition, $params);
			$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_credits_record') . $condition . $limit, $params);
			if (!empty($data)) {
				$uids = array();
				foreach ($data as &$da) {
					if (!in_array($da['uid'], $uids)) {
						$uids[] = $da['uid'];
					}
					$operator = mc_account_change_operator($da['clerk_type'], $da['store_id'], $da['clerk_id']);
					$da['clerk_cn'] = $operator['clerk_cn'];
					$da['store_cn'] = $operator['store_cn'];
				}
				unset($da);
				$uids = implode(',', $uids);
				$users = pdo_fetchall('SELECT mobile,uid,realname FROM ' . tablename('mc_members') . " WHERE uniacid = :uniacid AND uid IN ($uids)", array(':uniacid' => $_W['uniacid']), 'uid');
			}
			$pager = pagination($total, $pindex, $psize);

			if ($_GPC['export'] != '') {
				$exports = pdo_fetchall('SELECT * FROM ' . tablename('mc_credits_record') . $condition . " ORDER BY id DESC", $params);
				if (!empty($exports)) {
					$uids = array();
					foreach ($exports as &$da) {
						if (!in_array($da['uid'], $uids)) {
							$uids[] = $da['uid'];
						}
						$operator = mc_account_change_operator($da['clerk_type'], $da['store_id'], $da['clerk_id']);
						$da['clerk_cn'] = $operator['clerk_cn'];
						$da['store_cn'] = $operator['store_cn'];
					}
					unset($da);
					$uids = implode(',', $uids);
					$users = pdo_fetchall('SELECT mobile,uid,realname FROM ' . tablename('mc_members') . " WHERE uniacid = :uniacid AND uid IN ($uids)", array(':uniacid' => $_W['uniacid']), 'uid');
				}
				/* 输入到CSV文件 */
				$html = "\xEF\xBB\xBF";

				/* 输出表头 */
				$filter = array(
					'uid' => '会员编号',
					'name' => '姓名',
					'phone' => '手机',
					'type' => '类型',
					'num' => '数量',
					'store_cn' => '消费门店	',
					'clerk_cn' => '操作人	',
					'createtime' => '操作时间	',
					'remark' => '备注'
				);
				foreach ($filter as $title) {
					$html .= $title . "\t,";
				}
				$html .= "\n";
				foreach ($exports as $k => $v) {
					foreach ($filter as $key => $title) {
						if ($key == 'name') {
							$html .= $users[$v['uid']]['realname']. "\t, ";
						} elseif ($key == 'phone') {
							$html .= $users[$v['uid']]['mobile']. "\t, ";
						} elseif ($key == 'type') {
							if ($v['num'] > 0) {
								$html .= "充值\t, ";
							} else {
								$html .= "消费\t, ";
							}
						} elseif ($key == 'num') {
							$html .= abs($v[$key]). "\t, ";
						} elseif ($key == 'store') {
							if ($v['store_id'] > 0) {
								$html .= $stores[$v['store_id']]['business_name']. '-'. $stores[$v['store_id']]['branch_name']. "\t, ";
							} else {
								$html .= "未知\t, ";
							}
						} elseif ($key == 'operator') {
							if ($v['clerk_id'] > 0) {
								$html .= $clerks[$v['clerk_id']]['name']. "\t, ";
							} elseif ($v['clerk_type'] == 1) {
								$html .= "系统\t, ";
							} else {
								$html .= "未知\t, ";
							}
						} elseif ($key == 'createtime') {
							$html .= date('Y-m-d H:i', $v['createtime']). "\t, ";
						} elseif ($key == 'remark') {
							$html .= cutstr($v['remark'], '30', '...'). "\t, ";
						} else {
							$html .= $v[$key]. "\t, ";
						}
					}
					$html .= "\n";
				}
				/* 输出CSV文件 */
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=全部数据.csv");
				echo $html;
				exit();
			}
		}

		if ($op == 'chart') {
			$today_recharge = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num > 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
			$today_consume = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num < 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
			$total_recharge = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num > 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => $starttime, ':endtime' => $endtime)));
			$total_consume = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num < 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => $starttime, ':endtime' => $endtime)));
			if ($_W['isajax']) {
				$stat = array();
				for ($i = 0; $i < $num; $i++) {
					$time = $i * 86400 + $starttime;
					$key = date('m-d', $time);
					$stat['consume'][$key] = 0;
					$stat['recharge'][$key] = 0;
				}
				$data = pdo_fetchall('SELECT id,num,credittype,createtime,uniacid FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit1', ':starttime' => $starttime, ':endtime' => $endtime));

				if (!empty($data)) {
					foreach ($data as $da) {
						$key = date('m-d', $da['createtime']);
						if ($da['num'] > 0) {
							$stat['recharge'][$key] += $da['num'];
						} else {
							$stat['consume'][$key] += abs($da['num']);
						}
					}
				}

				$out['label'] = array_keys($stat['consume']);
				$out['datasets'] = array('recharge' => array_values($stat['recharge']), 'consume' => array_values($stat['consume']));
				exit(json_encode($out));
			}
		}

		include $this->template('statcredit1');
	}

	public function doWebStatcredit2() {
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
		load()->model('mc');
		$_W['page']['title'] = "余额统计-会员中心";
		$starttime = empty($_GPC['time']['start']) ? mktime(0, 0, 0, date('m') , 1, date('Y')) : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
		$num = ($endtime + 1 - $starttime) / 86400;

		if ($op == 'index') {
			$clerks = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
			$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array('id', 'business_name', 'branch_name'), 'id');
			$condition = ' WHERE uniacid = :uniacid AND credittype = :credittype AND createtime >= :starttime AND createtime <= :endtime';
			$params = array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit2', ':starttime' => $starttime, ':endtime' => $endtime);
			if (intval($_W['user']['clerk_type']) == ACCOUNT_OPERATE_CLERK) {
				$condition .= ' AND clerk_id = :clerk_id';
				$params[':clerk_id'] = $_W['user']['clerk_id'];
			}
			$num = intval($_GPC['num']);
			if ($num > 0) {
				if ($num == 1) {
					$condition .= ' AND num >= 0';
				} else {
					$condition .= ' AND num <= 0';
				}
			}
			$min = intval($_GPC['min']);
			if ($min > 0 ) {
				$condition .= ' AND abs(num) >= :minnum';
				$params[':minnum'] = $min;
			}

			$max = intval($_GPC['max']);
			if ($max > 0 ) {
				$condition .= ' AND abs(num) <= :maxnum';
				$params[':maxnum'] = $max;
			}
			$clerk_id = intval($_GPC['clerk_id']);
			if (!empty($clerk_id)) {
				$condition .= ' AND clerk_id = :clerk_id';
				$params[':clerk_id'] = $clerk_id;
			}
			$store_id = trim($_GPC['store_id']);
			if (!empty($store_id)) {
				$condition .= " AND store_id = :store_id";
				$params[':store_id'] = $store_id;
			}

			$user = trim($_GPC['user']);
			if (!empty($user)) {
				$condition .= ' AND (uid IN (SELECT uid FROM '.tablename('mc_members').' WHERE uniacid = :uniacid AND (realname LIKE :username OR uid = :uid OR mobile LIKE :mobile)))';
				$params[':username'] = "%{$user}%";
				$params[':uid'] = intval($user);
				$params[':mobile'] = "%{$user}%";
			}

			$psize = 30;
			$pindex = max(1, intval($_GPC['page']));
			$limit = " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ", {$psize}";
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_credits_record') . $condition, $params);
			$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_credits_record') . $condition . $limit, $params);

			if (!empty($data)) {
				load()->model('clerk');
				$uids = array();
				foreach ($data as &$da) {
					if (!in_array($da['uid'], $uids)) {
						$uids[] = $da['uid'];
					}
					$operator = mc_account_change_operator($da['clerk_type'], $da['store_id'], $da['clerk_id']);
					$da['clerk_cn'] = $operator['clerk_cn'];
					$da['store_cn'] = $operator['store_cn'];
				}
				unset($da);
				$uids = implode(',', $uids);
				$users = pdo_fetchall('SELECT mobile,uid,realname FROM ' . tablename('mc_members') . " WHERE uniacid = :uniacid AND uid IN ($uids)", array(':uniacid' => $_W['uniacid']), 'uid');
			}
			$pager = pagination($total, $pindex, $psize);
			if ($_GPC['export'] != '') {
				$exports = pdo_fetchall('SELECT * FROM '.tablename('mc_credits_record'). $condition . ' ORDER BY id DESC', $params);
				if (!empty($exports)) {
					load()->model('clerk');
					$uids = array();
					foreach ($exports as &$da) {
						if (!in_array($da['uid'], $uids)) {
							$uids[] = $da['uid'];
						}
						$operator = mc_account_change_operator($da['clerk_type'], $da['store_id'], $da['clerk_id']);
						$da['clerk_cn'] = $operator['clerk_cn'];
						$da['store_cn'] = $operator['store_cn'];
					}
					unset($da);
					$uids = implode(',', $uids);
					$user = pdo_fetchall('SELECT mobile,uid,realname FROM ' . tablename('mc_members') . " WHERE uniacid = :uniacid AND uid IN ($uids)", array(':uniacid' => $_W['uniacid']), 'uid');
				}
				
				$html = "\xEF\xBB\xBF";

				
				$filter = array(
					'uid' => '会员编号',
					'name' => '姓名',
					'phone' => '手机',
					'type' => '类型',
					'num' => '数量',
					'store' => '消费门店	',
					'operator' => '操作人	',
					'createtime' => '操作时间	',
					'remark' => '备注'
				);
				foreach ($filter as $title) {
					$html .= $title . "\t,";
				}
				$html .= "\n";
				foreach ($exports as $k => $v) {
					foreach ($filter as $key => $title) {
						if ($key == 'name') {
							$html .= $user[$v['uid']]['realname']. "\t, ";
						} elseif ($key == 'phone') {
							$html .= $user[$v['uid']]['mobile']. "\t, ";
						} elseif ($key == 'type') {
							if ($v['num'] > 0) {
								$html .= "充值\t, ";
							} else {
								$html .= "消费\t, ";
							}
						} elseif ($key == 'num') {
							$html .= abs($v[$key]). "\t, ";
						} elseif ($key == 'store') {
							if ($v['store_id'] > 0) {
								$html .= $stores[$v['store_id']]['business_name']. '-'. $stores[$v['store_id']]['branch_name']. "\t, ";
							} else {
								$html .= "未知\t, ";
							}
						} elseif ($key == 'operator') {
							if ($v['clerk_id'] > 0) {
								$html .= $v['clerk_cn']. "\t, ";
							} elseif ($v['clerk_type'] == 1) {
								$html .= "系统\t, ";
							} else {
								$html .= "未知\t, ";
							}
						} elseif ($key == 'createtime') {
							$html .= date('Y-m-d H:i', $v['createtime']). "\t, ";
						} elseif ($key == 'remark') {
							$html .= cutstr($v['remark'], '30', '...'). "\t, ";
						} else {
							$html .= $v[$key]. "\t, ";
						}
					}
					$html .= "\n";
				}
				
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=全部数据.csv");
				echo $html;
				exit();
			}
		}

		if ($op == 'chart') {
			$today_recharge = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num > 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit2', ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
			$today_consume = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num < 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit2', ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
			$total_recharge = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num > 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit2', ':starttime' => $starttime, ':endtime' => $endtime)));
			$total_consume = floatval(pdo_fetchcolumn('SELECT SUM(num) FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND num < 0 AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit2', ':starttime' => $starttime, ':endtime' => $endtime)));
			if ($_W['isajax']) {
				$stat = array();
				for ($i = 0; $i < $num; $i++) {
					$time = $i * 86400 + $starttime;
					$key = date('m-d', $time);
					$stat['consume'][$key] = 0;
					$stat['recharge'][$key] = 0;
				}
				$data = pdo_fetchall('SELECT id,num,credittype,createtime,uniacid FROM ' . tablename('mc_credits_record') . ' WHERE uniacid = :uniacid AND credittype = :credittype AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':credittype' => 'credit2', ':starttime' => $starttime, ':endtime' => $endtime));

				if (!empty($data)) {
					foreach ($data as $da) {
						$key = date('m-d', $da['createtime']);
						if ($da['num'] > 0) {
							$stat['recharge'][$key] += $da['num'];
						} else {
							$stat['consume'][$key] += abs($da['num']);
						}
					}
				}

				$out['label'] = array_keys($stat['consume']);
				$out['datasets'] = array('recharge' => array_values($stat['recharge']), 'consume' => array_values($stat['consume']));
				exit(json_encode($out));
			}
		}

		include $this->template('statcredit2');
	}

	public function doWebStatcash() {
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
		load()->model('mc');
		$_W['page']['title'] = "现金统计-会员中心";
		$starttime = empty($_GPC['time']['start']) ? mktime(0, 0, 0, date('m') , 1, date('Y')) : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
		$num = ($endtime + 1 - $starttime) / 86400;

		if ($op == 'chart') {
			$today_consume = floatval(pdo_fetchcolumn('SELECT SUM(final_cash) FROM ' . tablename('mc_cash_record') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
			$total_consume = floatval(pdo_fetchcolumn('SELECT SUM(final_cash) FROM ' . tablename('mc_cash_record') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime)));
			if ($_W['isajax']) {
				$stat = array();
				for ($i = 0; $i < $num; $i++) {
					$time = $i * 86400 + $starttime;
					$key = date('m-d', $time);
					$stat['consume'][$key] = 0;
					$stat['recharge'][$key] = 0;
				}

				$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_cash_record') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));

				if (!empty($data)) {
					foreach ($data as $da) {
						$key = date('m-d', $da['createtime']);
						$stat['consume'][$key] += abs($da['final_cash']);
					}
				}
				$out['label'] = array_keys($stat['consume']);
				$out['datasets'] = array('recharge' => array_values($stat['recharge']), 'consume' => array_values($stat['consume']));
				exit(json_encode($out));
			}
		}

		if ($op == 'index') {
			$clerks = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
			$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array('id', 'business_name', 'branch_name'), 'id');

			$condition = ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime';
			$params = array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime);
			$min = intval($_GPC['min']);
			if ($_W['user']['clerk_type'] == '3') {
				$current_clerk_id = $_W['user']['clerk_id'];
				$condition .= " AND clerk_type = 3 AND clerk_id = {$current_clerk_id}";
			}
			if ($min > 0 ) {
				$condition .= ' AND abs(final_fee) >= :minnum';
				$params[':minnum'] = $min;
			}

			$max = intval($_GPC['max']);
			if ($max > 0 ) {
				$condition .= ' AND abs(final_fee) <= :maxnum';
				$params[':maxnum'] = $max;
			}
			$clerk_id = intval($_GPC['clerk_id']);
			if (!empty($clerk_id)) {
				$condition .= ' AND clerk_id = :clerk_id';
				$params[':clerk_id'] = $clerk_id;
			}
			$store_id = trim($_GPC['store_id']);
			if (!empty($store_id)) {
				$condition .= " AND store_id = :store_id";
				$params[':store_id'] = $store_id;
			}

			$user = trim($_GPC['user']);
			if (!empty($user)) {
				$condition .= ' AND (uid IN (SELECT uid FROM '.tablename('mc_members').' WHERE uniacid = :uniacid AND (realname LIKE :username OR uid = :uid OR mobile LIKE :mobile)))';
				$params[':username'] = "%{$user}%";
				$params[':uid'] = intval($user);
				$params[':mobile'] = "%{$user}%";
			}

			$psize = 30;
			$pindex = max(1, intval($_GPC['page']));
			$limit = " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ", {$psize}";
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_cash_record') . $condition, $params);
			$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_cash_record') . $condition . $limit, $params);

			if (!empty($data)) {
				load()->model('clerk');
				$uids = array();
				foreach ($data as &$da) {
					if (!in_array($da['uid'], $uids)) {
						$uids[] = $da['uid'];
					}
					$operator = mc_account_change_operator($da['clerk_type'], $da['store_id'], $da['clerk_id']);
					$da['clerk_cn'] = $operator['clerk_cn'];
					$da['store_cn'] = $operator['store_cn'];
					if (empty($da['clerk_type'])) {
						$da['clerk_cn'] = '本人会员卡付款';
					}
				}
				unset($da);
				$uids = implode(',', $uids);
				$users = pdo_fetchall('SELECT mobile,uid,realname FROM ' . tablename('mc_members') . " WHERE uniacid = :uniacid AND uid IN ($uids)", array(':uniacid' => $_W['uniacid']), 'uid');
			}
			$pager = pagination($total, $pindex, $psize);
			if ($_GPC['export'] != '') {
				$exports = pdo_fetchall ('SELECT * FROM ' . tablename ('mc_cash_record') . $condition. " ORDER BY uid DESC", $params);
				if (!empty($exports)) {
					load ()->model ('clerk');
					$uids = array ();
					foreach ($exports as &$da) {
						if (!in_array ($da['uid'], $uids)) {
							$uids[] = $da['uid'];
						}
						$operator = mc_account_change_operator ($da['clerk_type'], $da['store_id'], $da['clerk_id']);
						$da['clerk_cn'] = $operator['clerk_cn'];
						$da['store_cn'] = $operator['store_cn'];
						if (empty($da['clerk_type'])) {
							$da['clerk_cn'] = '本人会员卡付款';
						}
					}
					unset($da);
					$uids = implode (',', $uids);
					$user = pdo_fetchall ('SELECT mobile,uid,realname FROM ' . tablename ('mc_members') . " WHERE uniacid = :uniacid AND uid IN ($uids)", array (':uniacid' => $_W['uniacid']), 'uid');
				}
				
				$html = "\xEF\xBB\xBF";

				
				$filter = array (
					'uid' => '会员编号',
					'realname' => '姓名',
					'mobile' => '手机',
					'fee' => '消费金额',
					'final_fee' => '实收金额',
					'credit2' => '余额支付	',
					'credit1_fee' => '积分抵消	',
					'final_cash' => '实收现金	',
					'store_cn' => '消费门店',
					'clerk_cn' => '操作人',
					'createtime' => '操作时间'
				);
				foreach ($filter as $title) {
					$html .= $title . "\t,";
				}
				$html .= "\n";
				foreach ($exports as $k => $v) {
					foreach ($filter as $key => $title) {
						if ($key == 'realname') {
							$html .= $user[$v['uid']]['realname'] . "\t, ";
						} elseif ($key == 'mobile') {
							$html .= $user[$v['uid']]['mobile'] . "\t, ";
						}  elseif ($key == 'createtime') {
							$html .= date ('Y-m-d H:i', $v['createtime']) . "\t, ";
						} else {
							$html .= $v[$key] . "\t, ";
						}
					}
					$html .= "\n";
				}
				
				header ("Content-type:text/csv");
				header ("Content-Disposition:attachment; filename=全部数据.csv");
				echo $html;
				exit();
			}
		}

		include $this->template('statcash');
	}

	public function doWebStatcard() {
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
		load()->model('mc');
		$_W['page']['title'] = "会员卡领卡统计-会员中心";
		$starttime = empty($_GPC['time']['start']) ? mktime(0, 0, 0, date('m') , 1, date('Y')) : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
		$num = ($endtime + 1 - $starttime) / 86400;
		if ($_W['isajax']) {
			$stat = array();
			for ($i = 0; $i < $num; $i++) {
				$time = $i * 86400 + $starttime;
				$key = date('m-d', $time);
				$stat[$key] = 0;
			}
			$data = pdo_fetchall('SELECT id,createtime FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));
			if (!empty($data)) {
				foreach ($data as $da) {
					$key = date('m-d', $da['createtime']);
					$stat[$key] += 1;
				}
			}

			$out['label'] = array_keys($stat);
			$out['datasets'] = array_values($stat);
			exit(json_encode($out));
		}

		$total = floatval(pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid'])));
		$today = floatval(pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP)));
		$yesterday = floatval(pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_members') . ' WHERE uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d')))));

		include $this->template('statcard');
	}

	public function doWebStatpaycenter() {
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
		load()->model('mc');
		load()->model('paycenter');
		$_W['page']['title'] = "收银台收银统计-会员中心";

		if ($op == 'index') {
			$clerks = pdo_getall('activity_clerks', array('uniacid' => $_W['uniacid']), array('id', 'name'), 'id');
			$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE), array('id', 'business_name', 'branch_name'), 'id');
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$condition = ' WHERE uniacid = :uniacid AND status = 1';
			$params = array(':uniacid' => $_W['uniacid']);
			$clerk_id = intval($_GPC['clerk_id']);
			if (!empty($clerk_id)) {
				$condition .= ' AND clerk_id = :clerk_id';
				$params[':clerk_id'] = $clerk_id;
			}
			$store_id = trim($_GPC['store_id']);
			if (!empty($store_id)) {
				$condition .= ' AND store_id = :store_id';
				$params[':store_id'] = $store_id;
			}
			$limit = " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ", {$psize}";
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM' . tablename('paycenter_order') . $condition, $params);
			$orders = pdo_fetchall('SELECT * FROM ' . tablename('paycenter_order') . $condition . $limit, $params);
			$pager = pagination($total, $pindex, $psize);
			$status = paycenter_order_status();
			if (!empty($orders)) {
				foreach ($orders as &$value) {
					$operator = mc_account_change_operator($value['clerk_type'], $value['store_id'], $value['clerk_id']);
					$value['clerk_cn'] = $operator['clerk_cn'];
					$value['store_cn'] = $operator['store_cn'];
				}
				unset($value);
			}
		}

		if ($op == 'detail') {
			if ($_W['isajax']) {
				$id = intval($_GPC['id']);
				$order = pdo_get('paycenter_order', array('uniacid' => $_W['uniacid'], 'id' => $id));
				if (empty($order)) {
					$info = '订单不存在';
				} elseif ($order['status'] == 0) {
					$info = '订单尚未支付';
				} else {
					$order['createtime_text'] = date('Y-m-d H:i:s', $order['createtime']);
					$order['paytime_text'] = date('Y-m-d H:i:s', $order['paytime']);
					$types = paycenter_order_types();
					$trade_types = paycenter_order_trade_types();
					$status = paycenter_order_status();
					$info = array('types' => $types, 'trade_types' => $trade_types, 'status' => $status, 'order' => $order);
					// $info = $this->template('payinfo', TEMPLATE_FETCH);
				}
				message(error(0, $info), '', 'ajax');
			}
		}

		if ($op == 'chart') {
			$today_starttime = strtotime(date('Y-m-d'));
			$today_endtime = $today_starttime + 86400;
			$yesterday_starttime = $today_starttime - 86400;
			$yesterday_endtime = $today_starttime;
			$month_starttime = date('Y-m-01', strtotime(date("Y-m-d")));
			$month_endtime = strtotime("$month_starttime + 1month - 1day");
			$today_fee = floatval(pdo_fetchcolumn('SELECT SUM(final_fee) FROM ' . tablename('paycenter_order') . ' WHERE uniacid = :uniacid AND status = 1 AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $today_starttime, ':endtime' => $today_endtime)));
			$yesterday_fee = floatval(pdo_fetchcolumn('SELECT SUM(final_fee) FROM ' . tablename('paycenter_order') . ' WHERE uniacid = :uniacid AND status = 1 AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $yesterday_starttime, ':endtime' => $yesterday_endtime)));
			$month_fee = floatval(pdo_fetchcolumn('SELECT SUM(final_fee) FROM ' . tablename('paycenter_order') . ' WHERE uniacid = :uniacid AND status = 1 AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => strtotime($month_starttime), ':endtime' => $month_endtime)));
			$type = trim($_GPC['type']);
			if ($_W['isajax']) {
				if ($type == 'date') {
					$now = strtotime(date('Y-m-d'));
					$starttime = empty($_GPC['start']) ? $now - 30*86400 : strtotime($_GPC['start']);
					$endtime = empty($_GPC['end']) ? TIMESTAMP : strtotime($_GPC['end']) + 86399;
					$num = ($endtime + 1 - $starttime) / 86400;

					$stat = array(
						'flow1' => array()
					);
					for ($i = 0; $i < $num; $i++) {
						$time = $i * 86400 + $starttime;
						$key = date('m-d', $time);
						$stat['flow1'][$key] = 0;
					}
					$data = pdo_fetchall('SELECT id, final_fee, paytime, uniacid FROM ' . tablename('paycenter_order') . ' WHERE uniacid = :uniacid AND status = 1 AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));
					if (!empty($data)) {
						foreach ($data as $da) {
							$key = date('m-d', $da['paytime']);
							$stat['flow1'][$key] += $da['final_fee'];
						}
					}
					$total = floatval(pdo_fetchcolumn('SELECT SUM(final_fee) FROM ' . tablename('paycenter_order') . ' WHERE uniacid = :uniacid AND status = 1 AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime)));
					$out['total'] = $total;
					$out['label'] = array_keys($stat['flow1']);
					$out['datasets']['flow1'] = array_values($stat['flow1']);
					exit(json_encode($out));
				} elseif($type == 'month') {
					$now = mktime(0,0,0,date('m'),date('t'),date('Y'));
					$end = mktime(23,59,59,date('m'),date('t'),date('Y'));
					$starttime = empty($_GPC['start']) ? strtotime('-6months', $now) : strtotime($_GPC['start']);
					$endtime = empty($_GPC['end']) ? $end : strtotime($_GPC['end']) +  date('t', strtotime($_GPC['end'])) * 86400 - 1;
					$num = ($endtime + 1 - $starttime) / 86400;

					$stat = array(
						'flow1' => array()
					);
					for ($i = 0; $i < $num; $i++) {
						$time = $i * 86400 + $starttime;
						$key = date('Y-m', $time);
						$stat['flow1'][$key] = 0;
					}
					$data = pdo_fetchall('SELECT id, final_fee, paytime, uniacid FROM ' . tablename('paycenter_order') . ' WHERE uniacid = :uniacid AND status = 1 AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime));
					if (!empty($data)) {
						foreach ($data as $da) {
							$key = date('Y-m', $da['paytime']);
							$stat['flow1'][$key] += $da['final_fee'];
						}
					}
					$total = floatval(pdo_fetchcolumn('SELECT SUM(final_fee) FROM ' . tablename('paycenter_order') . ' WHERE uniacid = :uniacid AND status = 1 AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':endtime' => $endtime)));
					$out['total'] = $total;
					$out['label'] = array_keys($stat['flow1']);
					$out['datasets']['flow1'] = array_values($stat['flow1']);
					exit(json_encode($out));
				}
			}
		}

		include $this->template('statpaycenter');
	}

	//app手机端

	public function doMobileCard() {
		global $_W, $_GPC;
		load()->model('user');
		load()->model('card');
		load()->func('tpl');
		checkauth();
		//未读取的消息条数
		$notice_count = card_notice_stat();
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'mycard';
		$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
		if ($op == 'sign_display') {
			$title = '签到-会员卡';
			$credit_set = card_credit_setting();
			$sign_set = $credit_set['sign'];
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_sign_record') . ' WHERE uniacid = :uniacid AND uid = :uid AND addtime >= :addtime', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid'], ':addtime' => strtotime(date("Y-m-1",time()))));
			$current_month_days = date('t', TIMESTAMP);
			$sign_rules = array(
				$sign_set['first_group_day'] => $sign_set['first_group_num'],
				$sign_set['second_group_day'] => $sign_set['second_group_num'],
				$sign_set['third_group_day'] => $sign_set['third_group_num'],
				$current_month_days => $sign_set['full_sign_num'],
			);
			if (!empty($sign_rules[$total])) {
				$today_sign_credit = $sign_rules[$total];
			} else {
				$today_sign_credit = $sign_set['everydaynum'];
			}
			if (!empty($sign_rules[$total + 1])) {
				$tomorrow_sign_credit = $sign_rules[$total + 1];
				$sign_credit = $sign_rules[$total + 1];
			} else {
				$tomorrow_sign_credit = $sign_set['everydaynum'];
				$sign_credit = $sign_set['everydaynum'];
			}
			$data = array(
				'uniacid' => $_W['uniacid'],
				'uid' => $_W['member']['uid'],
				'credit' => $sign_credit,
				'is_grant' => 0,
				'addtime' => TIMESTAMP,
			);
			$today_signed = pdo_get('mc_card_sign_record', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid'], 'addtime >' => strtotime(date('Y-m-d'))), 'id');
			if (empty($today_signed)) {
				$status = pdo_insert('mc_card_sign_record', $data);
				if (!empty($status) && $today_sign_credit > 0) {
					$log = "用户签到赠送【{$today_sign_credit}】积分";
					mc_credit_update($_W['member']['uid'], 'credit1', $today_sign_credit, array(0, $log, 'sign'));
					mc_notice_credit1($_W['openid'], $_W['member']['uid'], $today_sign_credit, $log);
				}
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_sign_record') . ' WHERE uniacid = :uniacid AND uid = :uid', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
			}
		}

		if ($op == 'sign_record') {
			$title = '签到记录-会员卡';
			$psize = 20;
			$pindex = max(1, intval($_GPC['page']));
			$period = intval($_GPC['period']);
			if ($period == '1') {
				$starttime = date('Ym01',strtotime(0));
				$endtime = date('Ymd His', time());
			} elseif ($period == '0') {
				$starttime = date('Ym01', strtotime(1 * $period . "month"));
				$endtime = date('Ymd', strtotime("$starttime + 1 month - 1 day"));
			} else {
				$starttime = date('Ym01', strtotime(1 * $period . "month"));
				$endtime = date('Ymd', strtotime("$starttime + 1 month - 1 day"));
			}
			$where = '';
			$params = array();
			$where = ' WHERE `uniacid` = :uniacid AND `uid` = :uid AND `addtime` >= :starttime AND `addtime` < :endtime';
			$params[':uniacid'] = $_W['uniacid'];
			$params[':uid'] = $_W['member']['uid'];
			$params[':starttime'] = strtotime($starttime);
			$params[':endtime'] = strtotime($endtime);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_sign_record') . $where, $params);
			$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_sign_record') . $where . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize . ", {$psize}", $params);
			$pagenums = ceil($total / $psize);
			foreach ($data as &$value) {
				$value['addtime'] = date('Y.m.d', $value['addtime']);
			}
			unset($value);
			if ($_W['isajax'] && $_W['ispost']) {
				if (!empty($data)){
					message(error(0, $data), '', 'ajax');
				} else {
					message(error(1, 'error'), '', 'ajax');
				}
			}
		}

		if ($op == 'sign_strategy') {
			$set = card_credit_setting();
			$content = $set['content'];
		}

		if ($op == 'notice') {
			$title = '系统消息-会员卡';
			if ($_W['isajax']) {
				$id = intval($_GPC['id']);
				if ($id > 0) {
					pdo_update('mc_card_notices_unread', array('is_new' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid'], 'notice_id' => $id));
					$total = card_notice_stat();
					exit($total);
				}
			}
			$psize = 20;
			$pindex = max(1, intval($_GPC['page']));
			$type = intval($_GPC['type']) ? intval($_GPC['type']) : 1;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_notices_unread') . ' WHERE uniacid = :uniacid AND uid = :uid AND type = :type', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid'], ':type' => $type));
			$data = pdo_fetchall('SELECT a.*,b.* FROM ' . tablename('mc_card_notices_unread') . ' AS a LEFT JOIN ' . tablename('mc_card_notices') . ' AS b ON a.notice_id = b.id WHERE a.uniacid = b.uniacid AND a.uniacid = :uniacid AND a.uid = :uid AND a.type = :type ORDER BY a.notice_id DESC LIMIT ' . ($pindex - 1) * $psize . ", {$psize}", array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid'], ':type' => $type));
			$pager = pagination($total, $pindex, $psize);
		}

		/*会员卡中心*/
		if ($op == 'receive_card') {
			$mcard = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']), array('id'));
			if (!empty($mcard)) {
				header('Location:' . $this->createMobileurl('card', array('op' => 'mycard')));
				exit;
			}
			/*获取当前可领取的会员卡*/
			$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid'], 'status' => '1'));
			if (!empty($setting)) {
				$setting['color'] = iunserializer($setting['color']);
				$setting['background'] = iunserializer($setting['background']);
				$setting['fields'] = iunserializer($setting['fields']);
				$setting['grant'] = iunserializer($setting['grant']);
				if (!empty($setting['grant']['coupon']) && is_array($setting['grant']['coupon'])) {
					foreach ($setting['grant']['coupon'] as $grant_coupon) {
						$coupon_title .= "{$grant_coupon['couponTitle']}|";
					}
				}
				$params = json_decode($setting['params'], true);
				foreach ($params as $key => $value) {
					$params_new[$value['id']] = $value;  
				}
				$basic_info = $params_new['cardBasic'];
			} else {
				message('公众号尚未开启会员卡功能', $this->createMobileurl('card'), 'error');
			}

			if (!empty($setting['fields'])) {
				$fields = array('email');
				foreach ($setting['fields'] as $li) {
					if ($li['bind'] == 'birth') {
						$fields[] = 'birthyear';
						$fields[] = 'birthmonth';
						$fields[] = 'birthday';
					} elseif ($li['bind'] == 'reside') {
						$fields[] = 'resideprovince';
						$fields[] = 'residecity';
						$fields[] = 'residedist';
					} else {
						$fields[] = $li['bind'];
					}
				}
				$member_info = mc_fetch($_W['member']['uid'], $fields);
				$reregister = 0;
				if (strlen($member_info['email']) == 39 && strexists($member_info['email'], '@we7.cc')) {
					$member_info['email'] = '';
					$reregister = 1;
				}
			}
			if ($_W['isajax'] && $_W['ispost']) {
				$data = array();
				$realname = trim($_GPC['realname']);
				if (empty($realname)) {
					message('请输入姓名', referer(), 'info');
				}
				$data['realname'] = $realname;
				$mobile = trim($_GPC['mobile']);
				if (!preg_match(REGULAR_MOBILE, $mobile)) {
					message('手机号有误', referer(), 'info');
				}
				$data['mobile'] = $mobile;
				if (!empty($setting['fields'])) {
					foreach ($setting['fields'] as $row) {
						if (!empty($row['require']) && ($row['bind'] == 'birth' || $row['bind'] == 'birthyear')) {
							if (empty($_GPC['birth']['year']) || empty($_GPC['birth']['month']) || empty($_GPC['birth']['day'])) {
								message('请输入出生日期', referer(), 'info');
							}
							$row['bind'] = 'birth';
						} elseif (!empty($row['require']) && $row['bind'] == 'resideprovince') {
							if (empty($_GPC['reside']['province']) || empty($_GPC['reside']['city']) || empty($_GPC['reside']['district'])) {
								message('请输入居住地', referer(), 'info');
							}
							$row['bind'] = 'reside';
						} elseif (!empty($row['require']) && empty($_GPC[$row['bind']])) {
							message('请输入'.$row['title'].'！', referer(), 'info');
						}
						$data[$row['bind']] = $_GPC[$row['bind']];
					}
				}
				$check = we7_coupon_mc_check($data);
				if (is_error($check)) {
					message($check['message'], referer(), 'error');
				}
				/*判断会员是否已经领取过*/
				$sql = 'SELECT COUNT(*)  FROM ' . tablename('mc_card_members') . " WHERE `uid` = :uid AND `cid` = :cid AND uniacid = :uniacid";
				$count = pdo_fetchcolumn($sql, array(':uid' => $_W['member']['uid'], ':cid' => $_GPC['cardid'], ':uniacid' => $_W['uniacid']));
				if ($count >= 1) {
					message('已领取过该会员卡.', referer(), 'error');
				}

				$record = array(
					'uniacid' => $_W['uniacid'],
					'openid' => $_W['openid'],
					'uid' => $_W['member']['uid'],
					'cid' => $_GPC['cardid'],
					'cardsn' => $data['mobile'],
					'status' => '1',
					'createtime' => TIMESTAMP,
					'endtime' => TIMESTAMP
				);

				if (pdo_insert('mc_card_members', $record)) {
					if (!empty($data)){
						mc_update($_W['member']['uid'], $data);
					}
					//赠送积分.余额.优惠券
					$notice = '';
					if (is_array($setting['grant'])) {
						if ($setting['grant']['credit1'] > 0) {
							$log = array(
								$_W['member']['uid'],
								"领取会员卡，赠送{$setting['grant']['credit1']}积分"
							);
							mc_credit_update($_W['member']['uid'], 'credit1', $setting['grant']['credit1'], $log);
							$notice .= "赠送【{$setting['grant']['credit1']}】积分";
						}
						if ($setting['grant']['credit2'] > 0) {
							$log = array(
								$_W['member']['uid'],
								"领取会员卡，赠送{$setting['credit2']['credit1']}余额"
							);
							mc_credit_update($_W['member']['uid'], 'credit2', $setting['grant']['credit2'], $log);
							$notice .= ",赠送【{$setting['grant']['credit2']}】余额";
						}
						if (!empty($setting['grant']['coupon']) && is_array($setting['grant']['coupon'])) {
							foreach ($setting['grant']['coupon'] as $grant_coupon) {
								$status = we7_coupon_activity_coupon_grant($grant_coupon['coupon'], $_W['member']['uid']);
								if (!is_error($status)) {
									$coupon_title .= ",{$grant_coupon['couponTitle']}";
								}
							}
							$notice .= ",赠送【{$coupon_title}】优惠券";
						}
					}
					$time = date('Y-m-d H:i');
					$url = murl('entry', array('do' => 'card', 'op' => 'mycard', 'm' => 'we7_coupon'), true, true);
					$title = "【{$_W['account']['name']}】- 领取会员卡通知\n";
					$info = "您在{$time}成功领取会员卡，{$notice}。\n\n";
					$info .= "<a href='{$url}'>点击查看详情</a>";
					$status = mc_notice_custom_text($_W['openid'], $title, $info);
					message("领取会员卡成功", $this->createMobileurl('card'), 'success');
				} else {
					message('领取会员卡失败.', referer(), 'error');
				}
			}
		}


		/*我的会员卡*/
		if ($op == 'mycard') {
			$mcard = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
			$title = $setting['title'];
			if (empty($mcard)) {
				header('Location:' . $this->createMobileurl('card', array('op' => 'receive_card')));
			}
			$new_notice_total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_card_notices_unread') . ' WHERE uniacid = :uniacid AND uid = :uid AND type = :type AND is_new = :is_new', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid'], ':type' => '1', ':is_new' => '1'));
			if (empty($mcard['openid']) && !empty($_W['openid'])) {
				pdo_update('mc_card_members', array('openid' => $_W['openid']), array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
			}
			if (!empty($mcard['status'])) {
				$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
				if (!empty($setting)) {
					$setting['color'] = iunserializer($setting['color']);
					$setting['background'] = iunserializer($setting['background']);;
				}
			}
			load()->model('activity');
			$coupons = we7_coupon_activity_coupon_owned();
			$nums_recharge = iunserializer($setting['nums']);
			$times_recharge = iunserializer($setting['times']);
			$total = count($coupons);
			$activity_setting = card_params_setting('cardActivity');
			$notice_count = card_notice_stat();

			$params = json_decode($setting['params'], true);
			if (!empty($params)) {
				foreach ($params as $key => $value) {
					$params_new[$value['id']] = $value;  
				}
			}
			$basic_info = $params_new['cardBasic'];
			$activity_info = $params_new['cardActivity'];
			$recharge_info = $params_new['cardRecharge'];
			$nums_info = $params_new['cardNums'];
			$times_info = $params_new['cardTimes'];
			$activity_description_show = false;
			if ($activity_info['params']['discount_type'] != 0 || $recharge_info['params']['recharge_type'] != 0 || $nums_info['params']['nums_status'] != 0 || $times_info['params']['times_status'] != 0) {
				$activity_description_show = true;
			}

		}

		if ($op == 'activity_description') {
			$params = json_decode($setting['params'], true);
			foreach ($params as $key => $value) {
				$params_new[$value['id']] = $value;  
			}
			$activity_info = $params_new['cardActivity'];
			$recharge_info = $params_new['cardRecharge'];
			$nums_info = $params_new['cardNums'];
			$times_info = $params_new['cardTimes'];
			if ($activity_info['params']['discount_type'] == 0 && $recharge_info['params']['recharge_type'] == 0 && $nums_info['params']['nums_status'] == 0 && $times_info['params']['times_status'] == 0) {
				message('暂无优惠信息', referer(), 'error');
			}
			if ($activity_info['params']['discount_type'] != 0) {
				foreach ($activity_info['params']['discounts'] as $key => $val) {
					$activity_description[$key][0] = $val['title'];
					if ($activity_info['params']['discount_type'] == 1) {
						if (!empty($val['condition_1']) && !empty($val['discount_1'])) {
							$activity_description[$key][1] = '消费 <span class="mui-badge mui-badge-danger">满</span> ' . $val['condition_1'] . ' 元，<span class="mui-badge mui-badge-danger">减</span> ' . $val['discount_1'] . ' 元';
						} else {
							unset($activity_description[$key]);
						}
					} else {
						if (!empty($val['condition_2']) && !empty($val['discount_2'])) {
							$activity_description[$key][1] = '消费 <span class="mui-badge mui-badge-danger">满</span> ' . $val['condition_2'] . ' 元，<span class="mui-badge mui-badge-danger">打</span>'  . $val['discount_2'] . ' 折';
						} else {
							unset($activity_description[$key]);
						}
					}
				}
			}

			if ($recharge_info['params']['recharge_type'] == 1) {
				foreach ($recharge_info['params']['recharges'] as $key => $value) {
					if ($value['backtype'] == '0') {
						$recharge_description[$key] = '<span class="mui-badge mui-badge-danger">充</span> ' . $value['condition'] . ' 元，<span class="mui-badge mui-badge-danger">返</span> ' . $value['back'] . ' 元';
					} else {
						$recharge_description[$key] = '<span class="mui-badge mui-badge-danger">充</span> ' . $value['condition'] . ' 元，<span class="mui-badge mui-badge-danger">返</span> ' . $value['back'] . ' 积分';
					}
				}
			}

			if ($nums_info['params']['nums_status'] == 1) {
				foreach ($nums_info['params']['nums'] as $key => $value) {
					if (!empty($value['recharge']) && !empty($value['num'])) {
						$nums_description[$key] = '<span class="mui-badge mui-badge-danger">充</span> ' . $value['recharge'] . ' 元， ' . $value['num'] . ' 次';
					}
				}
			}

			if ($times_info['params']['times_status'] == 1) {
				foreach ($times_info['params']['times'] as $key => $value) {
					if (!empty($value['recharge']) && !empty($value['time'])) {
						$times_description[$key] = '<span class="mui-badge mui-badge-danger">充</span> ' . $value['recharge'] . ' 元， ' . $value['time'] . ' 天';
					}
				}
			}
		}

		/*增加次数*/
		if ($op == 'add_recharge') {
			$type = trim($_GPC['type']);
			$mcard = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
			$mcard['status'] = '1';
			if (!empty($mcard['status'])) {
				$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
				$setting = iunserializer($setting[$type]);
			}
		}

		if ($op == 'recharge_record') {
			$period = $_GPC['period'];
			$period_date = ($period == '1') ? date('Y.m', strtotime('now')) : date('Y.m', strtotime('now' . ($_GPC['period'] * 1) . ' month'));
			$starttime = ($period == '1') ? date('Ym01') : date('Ym01', strtotime(1*$period . "month"));
			$endtime = date('Ymd', strtotime("$starttime + 1 month - 1 day"));
			$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']), array('nums_text', 'times_text'));
			$card = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
			$type = trim($_GPC['type']);
			$where = ' WHERE uniacid = :uniacid AND uid = :uid AND type = :type AND addtime >= :starttime AND addtime <= :endtime';
			$params = array(
				':uniacid' => $_W['uniacid'],
				':uid' => $_W['member']['uid'],
				':type' => $type,
				':starttime' => strtotime($starttime),
				':endtime' => strtotime($endtime)
			);
			$data = pdo_fetchall('SELECT * FROM ' . tablename('mc_card_record') . $where . ' ORDER BY id DESC ', $params);
		}

		if ($op == 'personal_info') {
			$setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid'], 'status' => '1'));
			if (!empty($setting['fields'])) {
				$mc_fields = iunserializer($setting['fields']);
				foreach ($mc_fields as $key => &$row) {
					if (!empty($row['require']) && ($row['bind'] == 'birth' || $row['bind'] == 'birthyear') || $row['bind'] == 'birthday') {
						$row['bind'] = 'birth';
					} elseif (!empty($row['require']) && ($row['bind'] == 'resideprovince' || $row['bind'] == 'residecity' || $row['bind'] == 'residedist')) {
						$row['bind'] = 'reside';
					}
					if ($row['bind'] == 'mobile') {
						unset($mc_fields[$key]);
					}
				}
			}
			$profile = mc_fetch($_W['member']['uid']);
			if (strexists($profile['email'], '@we7.cc')) {
				$profile['email'] = '';
			}
			$mcard = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
		}

		if ($op == 'consume') {
			$card_settings = card_setting();
			$discount_params = $card_settings['discount'];
			$group = mc_fetch($_W['member']['uid'], array('groupid'));
			$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'source' => COUPON_TYPE, 'status' => '1'), array('id', 'business_name', 'branch_name'));
			$stores_data['data'] = array();
			if (!empty($stores) && is_array($stores)) {
				foreach ($stores as $key => $value) {
					$stores_data['data'][$key]['text'] = $value['business_name'];
					$stores_data['data'][$key]['value'] = $value['id'];
					if (!empty($value['branch_name'])) {
						$stores_data['data'][$key]['text'] = $value['business_name'] . '-' . $value['branch_name'];
					}
				}
			}
			if (checksubmit()) {
				$fee = trim($_GPC['fee']);
				$store_id = intval($_GPC['store_id']);
				$body = '会员卡消费' . $fee . '元';
				if (!empty($stores_data['data'])) {
					if (empty($store_id)) {
						message('请选择门店', '', 'error');
					}
				}
				if (empty($fee) || $fee <= 0) {
					message('收款金额有误', '', 'error');
				}
				$final_fee = card_discount_fee($fee);
				$data = array(
					'uniacid' => $_W['uniacid'],
					'clerk_id' => 0,
					'clerk_type' => 3,
					'store_id' => intval($_GPC['store_id']),
					'body' => $body,
					'fee' => $fee,
					'final_fee' => $final_fee,
					'credit_status' => 1,
					'createtime' => TIMESTAMP,
				);
				pdo_insert('paycenter_order', $data);
				$id = pdo_insertid();
				header('Location:' . murl('entry', array('m' => 'we7_coupon', 'do' => 'pay', 'id' => $id), true, true));
				die;
			}
		}
		include $this->template('card');
	}

	public function doMobileCreditrecord() {
		global $_W, $_GPC;

		$where = '';
		$params = array(':uid' => $_W['member']['uid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		/*默认显示时间*/
		$period = intval($_GPC['period']);
		if ($period == '1') {
			$starttime = date('Ym01',strtotime(0));
			$endtime = date('Ymd His', time());
		} elseif ($period == '0') {
			$starttime = date('Ym01', strtotime(1*$period . "month"));
			$endtime = date('Ymd', strtotime("$starttime + 1 month - 1 day"));
		} else {
			$starttime = date('Ym01', strtotime(1*$period . "month"));
			$endtime = date('Ymd', strtotime("$starttime + 1 month - 1 day"));
		}
		$where = ' AND `createtime` >= :starttime AND `createtime` < :endtime';
		$params[':starttime'] = strtotime($starttime);
		$params[':endtime'] = strtotime($endtime);
		/*获取用户名字和头像*/
		$sql = 'SELECT `realname`, `avatar` FROM ' . tablename('mc_members') . " WHERE `uid` = :uid";
		$user = pdo_fetch($sql, array(':uid' => $_W['member']['uid']));
		if ($_GPC['credittype']) {
			$where .= " AND `credittype` = '{$_GPC['credittype']}'";
		}
		
		/*获取总支出收入情况*/
		$sql = 'SELECT `num` FROM ' . tablename('mc_credits_record') . " WHERE `uid` = :uid $where";
		$nums = pdo_fetchall($sql, $params);
		$pay = $income = 0;
		foreach ($nums as $value) {
			if ($value['num'] > 0) {
				$income += $value['num'];
			} else {
				$pay += abs($value['num']);
			}
		}
		if ($_GPC['credittype'] == 'credit2') {
			$pay = number_format($pay, 2);
			$income = number_format($income, 2);
		}
		/*获取交易记录*/
		if ($_GPC['credittype'] != 'cash') {
			$sql = 'SELECT * FROM ' . tablename('mc_credits_record') . " WHERE `uid` = :uid {$where} ORDER BY `createtime` DESC LIMIT " . ($pindex - 1) * $psize.','. $psize;
			$data = pdo_fetchall($sql, $params);
			foreach ($data as $key=>$value) {
				$data[$key]['credittype'] = $creditnames[$data[$key]['credittype']]['title'];
				$data[$key]['createtime'] = date('Y-m-d H:i', $data[$key]['createtime']);
				$data[$key]['num'] = number_format($value['num'], 2);
				if ($data[$key]['num'] < 0) {
					$data[$key]['color'] = "#000";
				} else {
					$data[$key]['color'] = "#04be02";
					$data[$key]['num'] = "+" . $data[$key]['num'];
				}
				$data[$key]['remark'] = str_replace('credit1', '积分', $data[$key]['remark']);
				$data[$key]['remark'] = str_replace('credit2', '余额', $data[$key]['remark']);
				$data[$key]['remark'] = empty($data[$key]['remark']) ? '未记录' : $data[$key]['remark'];
			}
			/*数据分页*/
			$pagesql = 'SELECT COUNT(*) FROM ' .tablename('mc_credits_record') . "WHERE `uid` = :uid {$where}";
			$total = pdo_fetchcolumn($pagesql, $params);
			$pager = pagination($total, $pindex, $psize, '', array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
			$pagenums = ceil($total / $psize);
		} else {
			$cash_where = ' `uniacid` = :uniacid AND `uid` = :uid AND `createtime` >= :starttime AND `createtime` < :endtime AND `trade_type` != :trade_type';
			$cash_params[':uid'] = $_W['member']['uid'];
			$cash_params[':uniacid'] = $_W['uniacid'];
			$cash_params[':starttime'] = strtotime($starttime);
			$cash_params[':endtime'] = strtotime($endtime);
			$cash_params[':trade_type'] = 'credit';
			$data = pdo_fetchall("SELECT * FROM " . tablename('mc_cash_record') . " WHERE {$cash_where} ORDER BY `createtime` DESC LIMIT " . ($pindex - 1) * $psize.','. $psize, $cash_params);
			foreach ($data as $key=>$value) {
				$data[$key]['credittype'] = $creditnames[$data[$key]['credittype']]['title'];
				$data[$key]['createtime'] = date('Y-m-d H:i', $data[$key]['createtime']);
				$data[$key]['num'] = number_format($value['final_cash'], 2);
				$data[$key]['color'] = "#000";
				$data[$key]['remark'] = '现金消费' . $data[$key]['num'] . '元';
				$data[$key]['num'] = '-' . $data[$key]['num'];
				$data[$key]['remark'] = empty($data[$key]['remark']) ? '未记录' : $data[$key]['remark'];
			}

			/*数据分页*/
			$pagesql = 'SELECT COUNT(*) FROM ' .tablename('mc_cash_record') . "WHERE {$cash_where}";
			$total = pdo_fetchcolumn($pagesql, $cash_params);
			$pager = pagination($total, $pindex, $psize, '', array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
			$pagenums = ceil($total / $psize);
		}
		
		if ($_W['isajax'] && $_W['ispost']) {
			if (!empty($data)){
				exit(json_encode($data));
			} else {
				exit(json_encode(array('state'=>'error'))); 
			}
		}
		$type = trim($_GPC['type']);
		if ($type == 'recorddetail') {
			$id = intval($_GPC['id']);
			$credittype = $_GPC['credittype'];
			if ($credittype != 'cash') {
				$data = pdo_fetch("SELECT r.*, u.username FROM " . tablename('mc_credits_record') . ' AS r LEFT JOIN ' .tablename('users') . ' AS u ON r.operator = u.uid ' . ' WHERE r.id = :id AND r.uniacid = :uniacid AND r.credittype = :credittype ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':uniacid' => $_W['uniacid'], ':id' => $id, ':credittype' => $credittype));
				if ($data['credittype'] == 'credit2') {
					$data['credittype'] = '余额';
				} elseif ($data['credittype'] == 'credit1') {
					$data['credittype'] = '积分';
				}
				$data['remark'] = str_replace('credit1', '积分', $data['remark']);
				$data['remark'] = str_replace('credit2', '余额', $data['remark']);
				$data['remark'] = empty($data['remark']) ? '暂无记录' : $data['remark'];
			} else {
				$data = pdo_fetch("SELECT r.*, u.username FROM " . tablename('mc_cash_record') . ' AS r LEFT JOIN ' .tablename('users') . ' AS u ON r.uid = u.uid ' . ' WHERE r.id = :id AND r.uniacid = :uniacid ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, array(':uniacid' => $_W['uniacid'], ':id' => $id));
				$data['remark'] = '现金消费' . $data['final_cash'] . '元';
				$data['num'] = '-' . $data['final_cash'];
			}
			
		}

		include $this->template('creditrecord');
	}

	public function doMobileActivity() {
		global $_W, $_GPC;
		checkauth();
		load()->model('activity');
		load()->model('mc');
		load()->classs('coupon');
		load()->func('tpl');
		$coupon_api = new coupon();
		//获取公众号积分策略
		$creditnames = array();
		$unisettings = uni_setting($uniacid, array('creditnames', 'coupon_type', 'exchange_enable'));
		if (!empty($unisettings) && !empty($unisettings['creditnames'])) {
			foreach ($unisettings['creditnames'] as $key=>$credit) {
				$creditnames[$key] = $credit['title'];
			}
		}
		/*获取当前公众号是否开启会员卡*/
		$cardstatus = pdo_get('mc_card', array('uniacid' => $_W['uniacid']), array('status'));
		$type_names = activity_coupon_type_label();
		activity_coupon_type_init();
		$colors = activity_coupon_colors();
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';
		$activity_type = trim($_GPC['activity_type']) ? trim($_GPC['activity_type']) : 'coupon';
		$we7_coupon_info = module_fetch('we7_coupon');
		$we7_coupon_settings = $we7_coupon_info['config'];
		if ($activity_type == 'coupon') {
			//兑换列表
			if($op == 'display') {
				if ($we7_coupon_settings['exchange_enable'] != '1') {
					message('未开启兑换功能');
				}
				$user = mc_fetch($_W['member']['uid'], array('groupid'));
				$fan = pdo_get('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
				$groupid = $user['groupid'];
				$exchanges = pdo_getall('activity_exchange', array('type' => COUPON_TYPE, 'uniacid' => $_W['uniacid'], 'status' => 1, 'starttime <=' => strtotime(date('Y-m-d')), 'endtime >=' => strtotime(date('Y-m-d'))), '', 'extra');
				foreach ($exchanges as $key => &$list) {
					$coupon_info = activity_coupon_info($list['extra']);
					$exchange_lists[$list['extra']] = $coupon_info;
					$person_total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('coupon_record')."  WHERE uid = :uid AND uniacid = :uniacid AND couponid = :couponid", array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid'], ':couponid' => $list['extra']));
					if ($person_total >= $list['pretotal'] || $person_total >= $coupon_info['get_limit']){
						unset($exchange_lists[$list['extra']]);
						continue;
					}
					$stock = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('coupon_record')." WHERE uniacid = :uniacid AND couponid = :couponid",  array(':uniacid' => $_W['uniacid'], ':couponid' => $list['extra']));
					if ($stock > $coupon_info['quantity']) {
						unset($exchange_lists[$list['extra']]);
						continue;
					}
					$coupon_groups = pdo_getall('coupon_groups', array('uniacid' => $_W['uniacid'], 'couponid' => $list['extra']), array(), 'groupid');
					$coupon_groups = array_keys($coupon_groups);
					if (COUPON_TYPE == WECHAT_COUPON) {
						$fan_groups = explode(',', $fan['tag']);
						$group = array_intersect($coupon_groups, $fan_groups);
					} else {
						$group = pdo_get('coupon_groups', array('uniacid' => $_W['uniacid'], 'couponid' => $list['extra'], 'groupid' => $groupid));
					}
					if (empty($group) && !empty($coupon_groups)) {
						unset($exchange_lists[$list['extra']]);
						continue;
					}
					if (!empty($_W['current_module'])) {
						$coupon_modules = pdo_getall('coupon_modules', array('uniacid' => $_W['uniacid'], 'couponid' => $list['extra']), array(), 'module');
						if (!empty($coupon_modules) && empty($coupon_modules[$_W['current_module']['name']]) && $_W['current_module']['name'] != 'we7_coupon') {
							unset($exchange_lists[$list['extra']]);
							continue;
						}
					}
					$exchange_lists[$list['extra']]['extra_href'] = $this->createMobileurl('activity', array('op' => 'exchange', 'activity_type' => 'coupon'));
					if (!empty($exchange_lists[$list['extra']])) {
						$exchange_lists[$list['extra']]['extra_func'] = $list;
						$exchange_lists[$list['extra']]['extra_func']['pic'] = 'resource/images/icon-signed.png';
					}
				}
				unset($list);
			}
			//兑换过程
			if ($op == 'exchange') {
				if ($we7_coupon_settings['exchange_enable'] != '1') {
					message(error(-1, '未开启兑换功能'), '', 'ajax');
				}
				$id = intval($_GPC['id']);
				$activity_exchange = pdo_get('activity_exchange', array('extra' => $id));
				$credit = mc_credit_fetch($_W['member']['uid'], array($activity_exchange['credittype']));
				if ($activity_exchange['credit'] < 0) {
					message(error(-1, '兑换' . $creditnames[$activity_exchange['credittype']] . '有误'), '', 'ajax');
				}
				if (intval($credit[$activity_exchange['credittype']]) < $activity_exchange['credit']) {
					message(error(-1, $creditnames[$activity_exchange['credittype']] . '不足'), '', 'ajax');
				}
				$pcount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('coupon_record') . " WHERE `openid` = :openid AND `couponid` = :couponid", array(':couponid' => $coupon['id'], ':openid' => $_W['fans']['openid']));
				if ($pcount > $activity_exchange['pretotal']) {
					message(error(-1, '领取数量超限'), '', 'ajax');
				}
				if ($activity_exchange['starttime'] > strtotime(date('Y-m-d'))) {
					message(error(-1, '活动未开始'), '', 'ajax');
				}
				if ($activity_exchange['endtime'] < strtotime(date('Y-m-d'))) {
					message(error(-1, '活动已结束'), '', 'ajax');
				}
				$status = we7_coupon_activity_coupon_grant($id, $_W['member']['uid']);
				if (is_error($status)) {
					message(error(-1, $status['message']), '', 'ajax');
				} else {
					mc_credit_update($_W['member']['uid'], $activity_exchange['credittype'], -1 * $activity_exchange['credit']);
					if ($activity_exchange['credittype'] == 'credit1') {
						mc_notice_credit1($_W['openid'], $_W['member']['uid'], -1 * $activity_exchange['credit'], '兑换卡券消耗积分');
					} else {
						$card_setting = pdo_get('mc_card', array('uniacid' => $_W['uniacid']));
						$recharges_set = card_params_setting('cardRecharge');
						if (empty($recharges_set['params']['recharge_type'])) {
							$grant_rate = $card_setting['grant_rate'];
							mc_credit_update($_W['member']['uid'], 'credit1', $grant_rate * $activity_exchange['credit']);
						}
						mc_notice_credit2($_W['openid'], $_W['member']['uid'], -1 * $activity_exchange['credit'], $grant_rate * $activity_exchange['credit'], '兑换卡券消耗余额');
					}
					
					message(error(0, '兑换成功'), $this->createMobileurl('activity', array('op' => 'mine', 'activity_type' => 'coupon')), 'ajax');
				}
			}
			//我的代金券
			if ($op == 'mine') {
				$title = '我的卡券';
				$coupon_records = we7_coupon_activity_coupon_owned();
			}
			//使用代金券
			if ($op == 'use') {
				$recid = intval($_GPC['recid']);
				$coupon_record = pdo_get('coupon_record', array('id' => $recid));
				$coupon_info = activity_coupon_info(trim($coupon_record['couponid']));
				$coupon_info['color'] = $colors[$coupon_info['color']] ? $colors[$coupon_info['color']] : '#63b359';
				if ($coupon_info['date_info']['time_type'] == '2') {
					$starttime = strtotime(date('Y-m-d', $coupon_record['addtime'])) + $coupon_info['date_info']['deadline'] * 86400;
					$endtime = $starttime + ($coupon_info['date_info']['limit'] - 1) * 86400;
					$coupon_info['extra_date_info'] = '有效期:' . date('Y.m.d', $starttime) . '-' . date('Y.m.d', $endtime);
				}
			}

			if ($op == 'detail') {
				$couponid = intval($_GPC['couponid']);
				$coupon_record = pdo_get('coupon_record', array('id' => intval($_GPC['recid'])));
				$coupon_info = activity_coupon_info($couponid);
				$coupon_info['description'] = $coupon_info['description'] ? $coupon_info['description'] : '暂无说明';
				if ($coupon_info['type'] == '1') {
					$coupon_info['discount_info'] = '凭此券消费打' . $coupon_info['extra']['discount'] * 0.1 . '折';
				} else {
					$coupon_info['discount_info'] = '价值' . $coupon_info['extra']['reduce_cost'] * 0.01 . '元代金券一张,消费满' . $coupon_info['extra']['least_cost'] * 0.01 . '元可使用';
				}
				if ($coupon_info['date_info']['time_type'] == '1') {
					$coupon_info['detail_date_info'] = $coupon_info['date_info']['time_limit_start'] . '-' . $coupon_info['date_info']['time_limit_end'];
				} else {
					$starttime = $coupon_record['addtime'] + $coupon_info['date_info']['deadline'] * 86400;
					$endtime = $starttime + ($coupon_info['date_info']['limit'] - 1) * 86400;
					$coupon_info['detail_date_info'] = date('Y.m.d', $starttime) . '-' . date('Y.m.d', $endtime);
				}
			}

			if ($op == 'qrcode') {
				$couponid = intval($_GPC['couponid']);
				$recid = intval($_GPC['recid']);
				
				$coupon_info = activity_coupon_info($couponid);
				$coupon_info['color'] = $colors[$coupon_info['color']] ? $colors[$coupon_info['color']] : '#63b359';
				$code_info = pdo_get('coupon_record', array('id' => $recid), array('code'));
				$coupon_info['code'] = $code_info['code'];
			}
			if ($op == 'opencard') {
				$id = intval($_GPC['id']);
				$code = trim($_GPC['code']);
				if ($_W['isajax'] && $_W['ispost']) {
					$card = $this->BuildCardExt($id);
					if (is_error($card)) {
						message(error(1, $card['message']), '', 'ajax');
					} else {
						$openCard['card_id'] = $card['card_id'];
						$openCard['code'] = $code;
						message(error(0, $openCard), '', 'ajax');
					}
				}
			}
			if ($op == 'addcard') {
				$id = intval($_GPC['id']);
				if ($_W['isajax'] && $_W['ispost']) {
					$card = $this->BuildCardExt($id);
					if (is_error($card)) {
						message(error(1, $card['message']), '', 'ajax');
					} else {
						message(error(0, $card), $this->createMobileurl('activity', array('activity_type' => 'coupon', 'op' => 'mine')), 'ajax');
					}
				}
			}
		} elseif ($activity_type == 'goods') {
			$profile = mc_fetch($_W['member']['uid']);
			//真实物品列表
			if ($op == 'display') {
				if ($we7_coupon_settings['exchange_enable'] != '1') {
					message('未开启兑换功能');
				}
				$lists = pdo_fetchall('SELECT id,title,extra,thumb,type,credittype,endtime,description,credit FROM ' . tablename('activity_exchange') . ' WHERE uniacid = :uniacid AND type = :type AND endtime > :endtime AND status = 1 ORDER BY endtime ASC ', array(':uniacid' => $_W['uniacid'], ':type' => 3, ':endtime' => TIMESTAMP));
				foreach ($lists as &$li) {
					$li['extra'] = iunserializer($li['extra']);
					if(!is_array($li['extra'])) {
						$li['extra'] = array();
					}
				}
				unset($li);
			}
			//兑换过程
			if ($op == 'post') {
				if ($we7_coupon_settings['exchange_enable'] != '1') {
					message(error(-1, '未开启兑换功能'), '', 'ajax');
				}
				$id = intval($_GPC['id']); 
				$shipping_data = array(
					'name' => trim($_GPC['username']),
					'mobile' => trim($_GPC['mobile']),
					'zipcode' => trim($_GPC['zipcode']),
					'province' => trim($_GPC['address_province']),
					'city' => trim($_GPC['address_city']),
					'district' => trim($_GPC['address_district']),
					'address' => trim($_GPC['address_addr']),
				);
				foreach ($shipping_data as $val) {
					if (empty($val)) {
						message(error(-1, '请填写收货人信息'), '', 'ajax');
					}
				}
				$goods = activity_exchange_info($id, $_W['uniacid']);
				if (empty($goods)){
					message(error(-1, '没有指定的礼品兑换'), '', 'ajax');
				}
				$credit = mc_credit_fetch($_W['member']['uid'], array($goods['credittype']));
				if ($credit[$goods['credittype']] < $goods['credit']) {
					message(error(-1, "{$creditnames[$goods['credittype']]}不足"), '', 'ajax');
				}
				$ret = activity_goods_grant($_W['member']['uid'], $id);
				if (is_error($ret)) {
					message($ret, '', 'ajax');
				}
				pdo_update('activity_exchange_trades_shipping', $shipping_data, array('tid' => $ret));
				mc_credit_update($_W['member']['uid'], $goods['credittype'], -1 * $goods['credit'], array($_W['member']['uid'], '礼品兑换:' . $goods['title'] . ' 消耗 ' . $creditnames[$goods['credittype']] . ':' . $goods['credit']));
				//微信通知
				if ($goods['credittype'] == 'credit1') {
					mc_notice_credit1($_W['openid'], $_W['member']['uid'], -1 * $goods['credit'], '兑换礼品消耗积分');
				} else {
					mc_notice_credit2($_W['openid'], $_W['member']['uid'], -1 * $goods['credit'], 0, '线上消费，兑换礼品');
				}
				message(error($ret, "兑换成功"), $this->createMobileurl('activity', array('activity_type' => 'goods', 'op' => 'mine')), 'ajax');
			}
			//收获地址
			if ($op == 'deliver') {
				$tid = intval($_GPC['tid']);//收货人信息id
				$id = intval($_GPC['id']);
				$type = trim($_GPC['type']);
				$goods_info = pdo_get('activity_exchange', array('id' => $id));
				$goods_info['reside'] = $goods_info['total'] - $goods_info['num'];
				$goods_info['exp_date'] = '有效期:' . date('Y.m.d', $goods_info['starttime']) . '-' . date('Y.m.d', $goods_info['endtime']);
				$goods_info['description'] = htmlspecialchars_decode($goods_info['description']);
				$credit = mc_credit_fetch($_W['member']['uid'], array($goods_info['credittype']));
				$credit['name'] = $creditnames[$goods_info['credittype']];
				$is_exchange['error'] = '1';
				if ($type == 'edit') {
					$is_exchange['name'] = '修改信息';
				} else {
					if ($goods_info['credit'] > $credit[$goods_info['credittype']]) {
						$is_exchange['name'] = $credit['name'] . '不足';
						$is_exchange['error'] = '-1';
					} else {
						$is_exchange['name'] = '立即兑换';
					}
					
				}
				if (empty($tid)) {
					$address_data = pdo_get('mc_member_address', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
				} else {
					$address_data = pdo_get('activity_exchange_trades_shipping', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid'], 'tid' => $tid));
					$address_data['username'] = $address_data['name'];
				}
				if ($_W['isajax']) {
					$data = array(
						'name' => trim($_GPC['username']),
						'mobile' => trim($_GPC['mobile']),
						'province' => trim($_GPC['address_province']),
						'city' => trim($_GPC['address_city']),
						'district' => trim($_GPC['address_district']),
						'address' => trim($_GPC['address_addr']),
						'zipcode' => trim($_GPC['zipcode']),
					);
					pdo_update('activity_exchange_trades_shipping', $data, array('tid' => intval($_GPC['tid']), 'uid' => $_W['member']['uid']));
					message(error(0,'修改成功'), $this->createMobileurl('activity', array('activity_type' => 'goods', 'op' => 'mine')), 'ajax');
				}
			}
			//我的实物
			if ($op == 'mine') {
				$psize = 10;
				$pindex = max(1, intval($_GPC['page']));
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_exchange_trades_shipping') . ' WHERE uid = :uid', array(':uid' => $_W['member']['uid'])); 
				$lists = pdo_fetchall("SELECT a.*, b.id AS gid,b.title,b.extra,b.thumb,b.type,b.credittype,b.endtime,b.description,b.credit FROM " . tablename('activity_exchange_trades_shipping') . " AS a LEFT JOIN " . tablename('activity_exchange'). " AS b ON a.exid = b.id WHERE a.uid = :uid ORDER BY a.status LIMIT " . ($pindex - 1) * $psize . "," . $psize, array(':uid' => $_W['member']['uid']));
				foreach ($lists as &$list) {
					$list['extra'] = iunserializer($list['extra']);
					if (!is_array($list['extra'])) {
						$list['extra'] = array();
					}
				}
				unset($list);
				$pager = pagination($total, $pindex, $psize);
			}
			//确认收货
			if ($op == 'confirm') {
				if ($_W['isajax']) {
					$tid = intval($_GPC['tid']);
					$ship = pdo_get('activity_exchange_trades_shipping', array('tid' => $tid, 'uid' => $_W['member']['uid']), array('id'));
					if (empty($ship)) {
						message(error(-1,'订单信息不存在'), '', 'ajax');
					}
					pdo_update('activity_exchange_trades_shipping', array('status' => 2), array('uid' => $_W['member']['uid'], 'tid' => $tid));
					message(error(1,'确认收货成功'), $this->createMobileurl('activity', array('activity_type' => 'goods', 'op' => 'mine', 'status' => 2)), 'ajax');
				}
			}
		}
		
		include $this->template('activitycoupon');
	}

	public function doMobileClerk() {
		global $_W, $_GPC;
		load()->model('paycenter');
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'home';
		if ($_GPC['op'] != 'pay') {
			$session = json_decode(base64_decode($_GPC["paycenter_session_{$_W['uniacid']}"]), true);
			if (is_array($session)) {
				load()->model('user');
				$user = user_single(array('uid' => $session['uid']));
				if (is_array($user) && $session['hash'] == md5($user['password'] . $user['salt'])) {
					$clerk = pdo_get('activity_clerks', array('uniacid' => $_W['uniacid'], 'uid' => $user['uid']));
					if (empty($clerk)) {
						message('您没有管理该店铺的权限', referer(), 'error');
					}
					$_W['uid'] = $user['uid'];
					$_W['username'] = $user['username'];
					$_W['user'] = $user;	
				} else {
					isetcookie("paycenter_session_{$_W['uniacid']}", false, -100);
				}
				unset($user);
			} else {
				isetcookie("paycenter_session_{$_W['uniacid']}", false, -100);
			}	
			if (empty($_W['user']) && $_W['openid'] && $_GPC['_wechat_logout'] != '1') {
				$clerk = pdo_get('activity_clerks', array('openid' => $_W['openid'], 'uniacid' => $_W['uniacid']));
				if (!empty($clerk)) {
					$user = pdo_get('users', array('uid' => $clerk['uid']));
					if (!empty($user)) {
						$cookie = array();
						$cookie['uid'] = $user['uid'];
						$cookie['username'] = $user['username'];
						$cookie['hash'] = md5($user['password'] . $user['salt']);
						$session = base64_encode(json_encode($cookie));
						isetcookie("paycenter_session_{$_W['uniacid']}", $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
						$_W['uid'] = $user['uid'];
						$_W['username'] = $user['username'];
						$_W['user'] = $user;
					}
				}
			}	
		}
		
		if ($op == 'login') {
			$this->doMobileClerklogin();
		}
		if ($op == 'logout') {
			$this->doMobileClerklogout();
		}
		if ($op == 'home') {
			$this->doMobileClerkhome();
		}
		if ($op == 'more') {
			$this->doMobileClerkmore();
		}
		if ($op == 'scanpay') {
			$this->doMobileClerkscanpay();
		}
		if ($op == 'paydetail') {
			$this->doMobileClerkpaydetail();
		}
		if ($op == 'cardconsume') {
			$this->doMobileClerkcardconsume();
		}
	}

	public function doMobileClerklogin() {
		global $_W, $_GPC;
		if (!empty($_W['user'])) {
			header('Location:' . $this->createMobileUrl('clerk', array('op' => 'home')));
			exit;
		}
		if ($_W['isajax']) {
			load()->model('user');
			$user['username'] = trim($_GPC['username']);
			$user['password'] = trim($_GPC['password']);

			$user = user_single($user);
			if (empty($user)) {
				message(error(-1, '账号或密码错误'), '', 'ajax');
			}
			if ($user['status'] == 1) {
				message(error(-1, '您的账号正在审核或是已经被系统禁止，请联系网站管理员解决'), '', 'ajax');
			}
			$clerk = pdo_get('activity_clerks', array('uniacid' => $_W['uniacid'], 'uid' => $user['uid']));
			if (empty($clerk)) {
				message(error(-1, '您没有管理该店铺的权限'), '', 'ajax');
			}
			$cookie = array();
			$cookie['uid'] = $user['uid'];
			$cookie['hash'] = md5($user['password'] . $user['salt']);
			$session = base64_encode(json_encode($cookie));
			isetcookie("paycenter_session_{$_W['uniacid']}", $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
			message(error(0, ''), $this->createMobileUrl('clerk', array('op' => 'home')), 'ajax');
		}
		include $this->template('clerklogin');
	}

	public function doMobileClerklogout() {
		global $_W, $_GPC;
		isetcookie("paycenter_session_{$_W['uniacid']}", '', -10000);
		isetcookie('_wechat_logout', '1', 180);
		$forward = $_GPC['forward'];
		if (empty($forward)) {
			$forward = './?refersh';
		}
		header('Location:' . $this->createMobileUrl('clerk', array('op' => 'login')));
		die;
	}

	public function doMobileClerkhome() {
		global $_W, $_GPC;
		we7_coupon_paycenter_check_login();
		$clerk_info = pdo_get('activity_clerks', array('id' => $_W['user']['clerk_id']));
		$fans_info = mc_fansinfo($clerk_info['openid']);
		$headimg = !empty($fans_info['headimgurl']) ? $fans_info['headimgurl'] : './resource/images/avatar.png';
		$today_revenue = $this->revenue(0);
		$yesterday_revenue = $this->revenue(-1);
		$seven_revenue = $this->revenue(-7);
		include $this->template('clerkhome');
	}
	public function doMobileClerkmore() {
		global $_W, $_GPC;
		we7_coupon_paycenter_check_login();
		$store_name = $_W['user']['store_name'];
		$clerk_name = $_W['user']['name'];
		$clerk_info = pdo_get('activity_clerks', array('id' => $_W['user']['clerk_id']));
		$fans_info = mc_fansinfo($clerk_info['openid']);
		$headimg = !empty($fans_info['headimgurl']) ? $fans_info['headimgurl'] : './resource/images/avatar.png';
		include $this->template('clerkmore');
	}
	public function doMobileClerkscanpay() {
		global $_W, $_GPC;
		we7_coupon_paycenter_check_login();
		$scan_type = trim($_GPC['scan_type']) ? trim($_GPC['scan_type']) : 'index';
		if ($_W['account']['level'] != ACCOUNT_SERVICE_VERIFY) {
			message('公众号权限不足', '', 'error');
		}
		if ($scan_type == 'post') {
			if (checksubmit()) {
				$fee = trim($_GPC['fee']) ? trim($_GPC['fee']) : message('收款金额有误', '', 'error');
				$body = trim($_GPC['body']) ? trim($_GPC['body']) : '收银台收款' . $fee;
				$data = array(
					'uniacid' => $_W['uniacid'],
					'clerk_id' => $_W['user']['clerk_id'],
					'clerk_type' => $_W['user']['clerk_type'],
					'store_id' => $_W['user']['store_id'],
					'body' => $body,
					'fee' => $fee,
					'final_fee' => $fee,
					'credit_status' => 1,
					'createtime' => TIMESTAMP,
				);
				pdo_insert('paycenter_order', $data);
				$id = pdo_insertid();
				header('location:' . $this->createMobileUrl('clerk', array('op' => 'scanpay', 'scan_type' => 'qrcode', 'id' => $id)));
				die;
			}
		}

		if ($scan_type == 'qrcode') {
			$id = intval($_GPC['id']);
			$order = pdo_get('paycenter_order', array('uniacid' => $_W['uniacid'], 'id' => $id));
			if(empty($order)) {
				message('订单不存在或已删除', '', 'error');
			}
			if($order['status'] == 1) {
				message('该订单已付款', '', 'error');
			}
		}

		if ($scan_type == 'list') {
			$condition = ' WHERE uniacid = :uniacid AND status = 1 AND clerk_id = :clerk_id ';
			$params = array(':uniacid' => $_W['uniacid'], ':clerk_id' => $_W['user']['clerk_id']);
			$period = intval($_GPC['period']);
			if ($period <= 0) {
				$starttime = strtotime(date('Y-m-d')) + $period * 86400;
				$endtime = $starttime + 86400;
				$condition .= ' AND paytime >= :starttime AND paytime <= :endtime ';
				$params[':starttime'] = $starttime;
				$params[':endtime'] = $endtime;
			}
			$orders = pdo_fetchall('SELECT * FROM ' . tablename('paycenter_order') . $condition . ' ORDER BY paytime DESC ', $params);
		}

		if ($scan_type == 'detail') {
			$id = intval($_GPC['id']);
			$order = pdo_get('paycenter_order', array('uniacid' => $_W['uniacid'], 'id' => $id));
			if (empty($order)) {
				message('订单不存在');
			} else {
				$store_id = $order['store_id'];
				$types = we7_coupon_paycenter_order_types();
				$trade_types = we7_coupon_paycenter_order_trade_types();
				$status = we7_coupon_paycenter_order_status();
				$store_info = pdo_get('activity_stores', array('id' => $store_id), array('business_name'));
			}
		}

		include $this->template('clerkscanpay');
	}
	public function doMobileClerkcardconsume() {
		global $_W, $_GPC;
		load()->model('activity');
		$qrcode = trim($_GPC['code']);
		if($_W['isajax']) {
			$code = trim($_GPC['code']);
			$record = pdo_get('coupon_record', array('code' => $code));
			if(empty($record)) {
				message(error(-1, '卡券记录不存在'), '', 'ajax');
			}
			$status = activity_coupon_use($record['couponid'], $record['id'], 'paycenter');
			if (!is_error($status)) {
				message(error('0', ''), '', 'ajax');
			} else {
				message(error('-1', $status['message']),'' , 'ajax');
			}
		}
		include $this->template('cardconsume');
	}
	public function doMobileClerkpaydetail() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$order = pdo_get('paycenter_order', array('id' => $id, 'uniacid' => $_W['uniacid']));
		if (empty($order)) {
			message('订单不存在或已删除', '', 'error');
		}
		if ($order['store_id'] > 0) {
			$store = pdo_get('activity_stores', array('id' => $order['store_id']), array('business_name'));
		}
		include $this->template('paydetail');
	}
	public function doMobileConsume() {
		global $_GPC, $_W;
		load()->model('activity');
		$colors = activity_coupon_colors();
		$source = trim($_GPC['source']);
		$card_id = trim($_GPC['card_id']);
		$encrypt_code = trim($_GPC['encrypt_code']);
		$openid = trim($_GPC['openid']);
		if (empty($card_id) || empty($encrypt_code)) {
			message('卡券签名参数错误');
		}
		if ($source == '1') {
			$card = pdo_get('coupon', array('uniacid' => $_W['uniacid'], 'id' => $card_id));
		} else {
			$card = pdo_get('coupon', array('uniacid' => $_W['uniacid'], 'card_id' => $card_id));
		}
		if (empty($card)) {
			message('卡券不存在或已删除');
		}
		$card['date_info'] = iunserializer($card['date_info']);
		$card['logo_url'] = tomedia($card['logo_url']);
		$error_code = 0;
		if ($source == '1') {
			$code = $encrypt_code;
		} else {
			load()->classs('coupon');
			$coupon = new coupon($_W['acid']);
			if (is_null($coupon)) {
				message('系统错误');
			}
			$code = $coupon->DecryptCode(array('encrypt_code' => $encrypt_code));
			$code = $code['code'];
		}
		
		if (is_error($code)) {
			$error_code = 1;
		}
		if (checksubmit()) {
			$password = trim($_GPC['password']);
			$clerk = pdo_get('activity_clerks', array('uniacid' => $_W['uniacid'], 'password' => $password));
			$_W['user']['name'] = $clerk['name'];
			$_W['user']['clerk_id'] = $clerk['id'];
			$_W['user']['clerk_type'] = 3;
			$_W['user']['store_id'] = $clerk['storeid'];
			if (empty($clerk)) {
				message('店员密码错误', referer(), 'error');
			}
			if (!$code) {
				message('code码错误', referer(), 'error');
			}
			load()->model('activity');
			$record = pdo_get('coupon_record', array('code' => $code));
			$status = activity_coupon_use($card['id'], $record['id'], 'paycenter');
			if (is_error($status)) {
				message($status['message'], referer(), 'error');
			}
			message('核销卡券成功',  $this->createMobileurl('activity', array('op' => 'mine', 'activity_type' => 'coupon')), 'success');
		}
		include $this->template('consume');
	}
	public function doMobileCouponcode() {
		global $_GPC;
		$code = trim($_GPC['code']);
		include $this->template('couponcode');
	}
	/** 
	* 
	* @param $period 时间周期,默认0为今日营收,-1为昨日营收,-7为七日营收
	* @return $revenus 营收数额
	*/
	public function revenue($period) {
		global $_W;
		if ($period == '0') {
			$starttime = strtotime(date('Y-m-d'));
			$endtime = $starttime + 86400;
		} elseif ($period == '-1') {
			$starttime = strtotime(date('Y-m-d',strtotime($period . 'day')));
			$endtime = strtotime(date('Y-m-d'));
		} else {
			$starttime = strtotime(date('Y-m-d',strtotime($period . 'day')));
			$endtime = strtotime(date('Y-m-d')) + 86400;
		}	
		$condition = "WHERE uniacid = :uniacid AND status = 1 AND paytime >= :starttime AND paytime <= :endtime AND clerk_id = :clerk_id";
		$params = array(':starttime' => $starttime, ':endtime' => $endtime, ':uniacid' => $_W['uniacid'], ':clerk_id' => intval($_W['user']['clerk_id']));
		$revenue = pdo_fetchcolumn("SELECT SUM(final_fee) FROM" . tablename('paycenter_order') . $condition, $params);
		return floatval($revenue);
	}

	public function doMobilePay() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$order = pdo_get('paycenter_order', array('uniacid' => $_W['uniacid'], 'id' => $id));
		if (empty($order)) {
			message('订单不存在或已删除', '', 'error');
		}
		if ($order['status'] == 1) {
			message('该订单已付款', '', 'error');
		}
		if (!empty($_W['member']['uid']) || !empty($_W['fans'])) {
			$update = array(
				'uid' => $_W['member']['uid'],
				'openid' => $_W['openid'],
				'nickname' => $_W['fans']['nickname']
			);
			pdo_update('paycenter_order', $update, array('uniacid' => $_W['uniacid'], 'id' => $id));
			$order['uid'] = $_W['member']['uid'];
		}
		$params['module'] = "paycenter_order";
		$params['tid'] = $order['id'];
		$params['ordersn'] = $order['id'];
		$params['user'] = $order['uid'];
		$params['fee'] = $order['final_fee'];
		$params['title'] = $_W['account']['name'] . $order['body'] ? $order['body'] : '收银台收款';
		$this->pay($params);
	}

	public function payResult($params) {
		global $_W;
		if ($params['type'] == 'delivery') {
			message('请及时付款', $this->createMobileUrl('clerk', array('m' => 'we7_coupon', 'op' => 'paydetail', 'id' => $params['tid'])), 'success');
		}
		if ($params['result'] == 'success' && $params['from'] == 'notify') {
			$order = pdo_get('paycenter_order', array('id' => $params['tid'], 'uniacid' => $_W['uniacid']));
			if (!empty($order)) {
				$log = pdo_get('core_paylog', array('tid' => $params['tid'], 'uniacid' => $_W['uniacid']));
				if ($log['type'] != 'credit') {
					load()->model('mc');
					mc_card_grant_credit($log['openid'], $log['card_fee'], $order['store_id']);
				}
				
				if (!empty($params['tag'])) {
					$params['tag'] = iunserializer($params['tag']);
				}
				$data = array(
					'type' => $params['type'],
					'trade_type' => strtolower($params['trade_type']),
					'status' => 1,
					'paytime' => TIMESTAMP,
					'uniontid' => $params['tag']['uniontid'],
					'transaction_id' => $params['tag']['transaction_id'],
					'follow' => intval($params['follow']),
					'final_fee' => $params['card_fee'],
				);
				if ($params['type'] == 'credit') {
					$data['credit2'] = $params['card_fee'];
				} else {
					$data['cash'] = $params['card_fee'];
				}
				if ($params['is_usecard'] == 1) {
					$discount_fee = $order['fee'] - $params['card_fee'];
					$data['remark'] = "使用优惠券减免{$discount_fee}元";
				}
				pdo_update('paycenter_order', $data, array('id' => $params['tid'], 'uniacid' => $_W['uniacid']));
				$cash_data = array(
					'uniacid' => $_W['uniacid'],
					'uid' => $order['uid'],
					'fee' => $order['fee'],
					'final_fee' => $order['final_fee'],
					'credit1' => $order['credit1'],
					'credit1_fee' => $order['credit1_fee'],
					'credit2' => $data['credit2'],
					'cash' => $params['card_fee'],
					'final_cash' => $data['cash'],
					'return_cash' => 0,
					'remark' => $order['remark'],
					'clerk_id' => $order['clerk_id'],
					'store_id' => $order['store_id'],
					'clerk_type' => $order['clerk_type'],
					'createtime' => TIMESTAMP,
					'trade_type' => $log['type']
				);
				pdo_insert('mc_cash_record', $cash_data);
			}
		}
		if ($params['result'] == 'success' && $params['from'] == 'return') {
			message('支付成功！', $this->createMobileUrl('clerk', array('m' => 'we7_coupon', 'op' => 'paydetail', 'id' => $params['tid'])), 'success');
		}
	}
	public function doWebWxcardreply() {
		global $_W, $_GPC;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		load()->model('reply');
		load()->model('module');
		if ($op == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$cids = $parentcates = $list =  array();
			$types = array('', '等价', '包含', '正则表达式匹配', '直接接管');
			
			$condition = 'uniacid = :uniacid AND `module`=:module';
			$params = array();
			$params[':uniacid'] = $_W['uniacid'];
			$params[':module'] = 'wxcard';
			$status = isset($_GPC['status']) ? intval($_GPC['status']) : -1;
			if ($status != -1){
				$condition .= " AND status = '{$status}'";
			}
			if (isset($_GPC['keyword'])) {
				$condition .= ' AND `name` LIKE :keyword';
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			$replies = reply_search($condition, $params, $pindex, $psize, $total);
			$pager = pagination($total, $pindex, $psize);
			if (!empty($replies)) {
				foreach ($replies as &$item) {
					$condition = '`rid`=:rid';
					$params = array();
					$params[':rid'] = $item['id'];
					$item['keywords'] = reply_keywords_search($condition, $params);
					$entries = module_entries('wxcard', array('rule'),$item['id']);
					if (!empty($entries)) {
						$item['options'] = $entries['rule'];
					}
				}
				unset($item);
			}
		}
		if ($op == 'post') {
			if ($_W['isajax'] && $_W['ispost']) {
				/*检测规则是否已经存在*/
				$sql = 'SELECT `rid` FROM ' . tablename('rule_keyword') . " WHERE `uniacid` = :uniacid  AND `content` = :content";
				$result = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'], ':content' => $_GPC['keyword']));
				if (!empty($result)) {
					$keywords = array();
					foreach ($result as $reply) {
						$keywords[] = $reply['rid'];
					}
					$rids = implode($keywords, ',');
					$sql = 'SELECT `id`, `name` FROM ' . tablename('rule') . " WHERE `id` IN ($rids)";
					$rules = pdo_fetchall($sql);
					exit(@json_encode($rules));
				}
				exit('success');
			}
			$rid = intval($_GPC['rid']);
			if (!empty($rid)) {
				$reply = reply_single($rid);
				if (empty($reply) || $reply['uniacid'] != $_W['uniacid']) {
					message('抱歉，您操作的规则不在存或是已经被删除！', $this->createWebUrl('wxcardreply', array('op' => 'display')), 'error');
				}
				foreach ($reply['keywords'] as &$kw) {
					$kw = array_elements(array('type', 'content'), $kw);
				}
				unset($kw);
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['name'])) {
					message('必须填写回复规则名称.');
				}
				$keywords = @json_decode(htmlspecialchars_decode($_GPC['keywords']), true);
				if (empty($keywords)) {
					message('必须填写有效的触发关键字.');
				}
				$rule = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'module' => 'wxcard',
					'status' => intval($_GPC['status']),
					'displayorder' => intval($_GPC['displayorder_rule']),
				);
				if (!empty($_GPC['istop'])) {
					$rule['displayorder'] = 255;
				} else {
					$rule['displayorder'] = range_limit($rule['displayorder'], 0, 254);
				}
				$module = WeUtility::createModule('wxcard');
				
				if (empty($module)) {
					message('抱歉，模块不存在！');
				}
				$msg = $module->fieldsFormValidate();
				
				if (is_string($msg) && trim($msg) != '') {
					message($msg);
				}
				if (!empty($rid)) {
					$result = pdo_update('rule', $rule, array('id' => $rid));
				} else {
					$result = pdo_insert('rule', $rule);
					$rid = pdo_insertid();
				}
				if (!empty($rid)) {
					//更新，添加，删除关键字
					$sql = 'DELETE FROM '. tablename('rule_keyword') . ' WHERE `rid`=:rid AND `uniacid`=:uniacid';
					$pars = array();
					$pars[':rid'] = $rid;
					$pars[':uniacid'] = $_W['uniacid'];
					pdo_query($sql, $pars);
			
					$rowtpl = array(
						'rid' => $rid,
						'uniacid' => $_W['uniacid'],
						'module' => $rule['module'],
						'status' => $rule['status'],
						'displayorder' => $rule['displayorder'],
					);
					foreach ($keywords as $kw) {
						$krow = $rowtpl;
						$krow['type'] = range_limit($kw['type'], 1, 4);
						$krow['content'] = $kw['content'];
						pdo_insert('rule_keyword', $krow);
					}
					$rowtpl['incontent'] = $_GPC['incontent'];
					$module->fieldsFormSubmit($rid);
					message('回复规则保存成功！', $this->createWebUrl('wxcardreply', array('op' => 'display', 'rid' => $rid)));
				} else {
					message('回复规则保存失败, 请联系网站管理员！');
				}
			} 
		}

		if ($op == 'delete') {
			$rids = $_GPC['rid'];
			if (!is_array($rids)) {
				$rids = array($rids);
			}
			if (empty($rids)) {
				message('非法访问.');
			}
			foreach ($rids as $rid) {
				$rid = intval($rid);
				$reply = reply_single($rid);
				if (empty($reply) || $reply['uniacid'] != $_W['uniacid']) {
					message('抱歉，您操作的规则不在存或是已经被删除！', referer(), 'error');
				}
				//删除回复，关键字及规则
				if (pdo_delete('rule', array('id' => $rid))) {
					pdo_delete('rule_keyword', array('rid' => $rid));
					//删除统计相关数据
					pdo_delete('stat_rule', array('rid' => $rid));
					pdo_delete('stat_keyword', array('rid' => $rid));
					//调用模块中的删除
					$module = WeUtility::createModule($reply['module']);
					if (method_exists($module, 'ruleDeleted')) {
						$module->ruleDeleted($rid);
					}
				}
			}
			message('规则操作成功！', referer(), 'success');
		}

		if ($op == 'stat-trend') {
			$_W['page']['title'] = '关键指标详解 - 数据统计';
			$id = intval($_GPC['id']);
			$starttime = empty($_GPC['time']['start']) ? strtotime(date('Y-m-d')) - 7 * 86400 : strtotime($_GPC['time']['start']);
			$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
			$list = pdo_fetchall("SELECT createtime, hit  FROM " . tablename('stat_rule') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = :rid AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array(':rid' => $id, ':createtime' => $starttime, ':endtime' => $endtime));
			$day = $hit = array();
			if (!empty($list)) {
				foreach ($list as $row) {
					$day[] = date('m-d', $row['createtime']);
					$hit[] = intval($row['hit']);
				}
			}
			/*添加规则默认数据*/
			for ($i = 0; $i = count($hit) < 2; $i++) {
				$day[] = date('m-d', $endtime);
				$hit[] = $day[$i] == date('m-d', $endtime) ? $hit[0] : '0';
			}
			$list = pdo_fetchall("SELECT createtime, hit, rid, kid FROM " . tablename('stat_keyword') . " WHERE uniacid = '{$_W['uniacid']}' AND rid = :rid AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array(':rid' => $id, ':createtime' => $starttime, ':endtime' => $endtime));
			if (!empty($list)) {
				foreach ($list as $row) {
					$keywords[$row['kid']]['hit'][] = $row['hit'];
					$keywords[$row['kid']]['day'][] = date('m-d', $row['createtime']);
				}
				foreach ($keywords as &$value) {
					/*添加所属关键字默认数据*/
					if (count($value['hit']) < 2) {
						$value['hit'][] = $value['day'][0] == date('m-d', $endtime) ? $value['hit'][0] : '0';
						$value['day'][] = date('m-d', $endtime);
					}
				}
				unset($value);
				$keywordnames = pdo_fetchall("SELECT content, id FROM " . tablename('rule_keyword') . " WHERE id IN (" . implode(',', array_keys($keywords)) . ")", array(), 'id');
			}
		}
		include $this->template('wxcardreply');
	}
}
function we7_coupon_tpl_form_field_location_category($name, $values = array(), $del = false) {
	$html = '';
	if (!defined('TPL_INIT_LOCATION_CATEGORY')) {
		$html .= '
		<script type="text/javascript" src="../addons/we7_coupon/template/style/js/location.js"></script>';
		define('TPL_INIT_LOCATION_CATEGORY', true);
	}
	if (empty($values) || !is_array($values)) {
		$values = array('cate'=>'','sub'=>'','clas'=>'');
	}
	if (empty($values['cate'])) {
		$values['cate'] = '';
	}
	if (empty($values['sub'])) {
		$values['sub'] = '';
	}
	if (empty($values['clas'])) {
		$values['clas'] = '';
	}
	$html .= '
		<div class="row row-fix tpl-location-container">
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
				<select name="' . $name . '[cate]" data-value="' . $values['cate'] . '" class="form-control tpl-cate">
				</select>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
				<select name="' . $name . '[sub]" data-value="' . $values['sub'] . '" class="form-control tpl-sub">
				</select>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
				<select name="' . $name . '[clas]" data-value="' . $values['clas'] . '" class="form-control tpl-clas">
				</select>
			</div>';
	if ($del) {
		$html .='
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="padding-top:5px">
				<a title="删除" onclick="$(this).parents(\'.tpl-location-container\').remove();return false;"><i class="fa fa-times-circle"></i></a>
			</div>
		</div>';
	} else {
		$html .= '</div>';
	}

	return $html;
}