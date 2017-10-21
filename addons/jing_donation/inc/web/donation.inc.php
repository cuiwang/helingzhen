<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$condition = ' WHERE `uniacid` = :uniacid';
	$params = array(':uniacid' => $_W['uniacid']);
	if (!empty($_GPC['keyword'])) {
		$condition .= ' AND `title` LIKE :title';
		$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
	}

	if (isset($_GPC['enabled'])) {
		$condition .= ' AND `enabled` = :enabled';
		$params[':enabled'] = intval($_GPC['enabled']);
	}
	$sql = 'SELECT COUNT(*) FROM ' . tablename($this->t_donation) . $condition;
	$total = pdo_fetchcolumn($sql, $params);
	if (!empty($total)) {
		$sql = 'SELECT * FROM ' . tablename($this->t_donation) . $condition . ' ORDER BY `enabled` ASC,
				`id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		$pager = pagination($total, $pindex, $psize);
	}
	include $this->template('donation');
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM " . tablename($this->t_donation) . " WHERE id = '$id'");
	} else {
		$item = array(
			'title' => '募捐活动开始啦',
			'thumb' => './addons/jing_donation/template/style/images/banner.jpg',
			'starttime' => time(),
			'endtime' => time() + 10 * 84400,
			'tip' => '关注，保护每一个孩子',
			'fixed_money1' => 10,
			'fixed_money2' => 20,
			'fixed_money3' => 100,
			'fixed_money4' => 200,
			'circle_name' => '校友',
			'text1' => '捐款',
			'text2' => '一起捐',
			'money' => 1,
			'xieyi' => '捐赠协议',
			'thanks' => '感谢您的捐赠',
			'numbers' => 10,
			'enabled' => 1
		);
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('抱歉，请输入募捐活动名称！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'enabled' => intval($_GPC['enabled']),
			'thumb' => $_GPC['thumb'],
			'video' => $_GPC['video'],
			'description' => $_GPC['description'],
			'starttime' => strtotime($_GPC['time'][start]),
			'endtime' => strtotime($_GPC['time'][end]),
			'content' => htmlspecialchars_decode($_GPC['content']),
			'company' => $_GPC['company'],
			'account' => $_GPC['account'],
			'fixed_money1' => $_GPC['fixed_money1'],
			'fixed_money2' => $_GPC['fixed_money2'],
			'fixed_money3' => $_GPC['fixed_money3'],
			'fixed_money4' => $_GPC['fixed_money4'],
			'tip' => $_GPC['tip'],
			'share_content1' => $_GPC['share_content1'],
			'share_content2' => $_GPC['share_content2'],
			'logo' => $_GPC['logo'],
			'share_title' => $_GPC['share_title'],
			'share_pic' => $_GPC['share_pic'],
			'share_des' => $_GPC['share_des'],
			'circle_name' => $_GPC['circle_name'],
			'text1' => $_GPC['text1'],
			'text2' => $_GPC['text2'],
			'money' => $_GPC['money'],
			'thanks' => $_GPC['thanks'],
			'xieyi' => htmlspecialchars_decode($_GPC['xieyi']),
			'numbers' => intval($_GPC['numbers']),
			'need_remark' => intval($_GPC['need_remark']),
			'need_name' => intval($_GPC['need_name']),
			'need_mobile' => intval($_GPC['need_mobile']),
			'createtime' => time(),
		);
		if (!empty($id)) {
			unset($data['createtime']);
			pdo_update($this->t_donation, $data, array('id' => $id));
		} else {
			pdo_insert($this->t_donation, $data);
			$id = pdo_insertid();
		}
		message('更新活动成功！', $this->createWebUrl('donation', array('op' => 'display')), 'success');
	}
	include $this->template('donation');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$donation = pdo_fetch("SELECT id FROM " . tablename($this->t_donation) . " WHERE id = '$id'");
	if (empty($donation)) {
		message('抱歉，活动不存在或是已经被删除！', $this->createWebUrl('donation', array('op' => 'display')), 'error');
	}
	pdo_delete($this->t_donation, array('id' => $id));
	message('活动删除成功！', $this->createWebUrl('donation', array('op' => 'display')), 'success');
}
?>