<?php

//decode by QQ:3213288469 http://www.zheyitianshi.com/
defined('IN_IA') or die('Access Denied');
define('CURR_UI_DIR', '../addons/q_3354988381_notify/ui/');
session_start();
class q_3354988381_notifyModuleSite extends WeModuleSite
{
	private $_user_openid = 0;
	private static $COOKIE_DAYS = 15;
	private $_weid = '';
	private $_debug_flag = 0;
	private function hdmkqxflag()
	{
		return;
		load()->classs('cloudapi');
		$api = new CloudApi();
		$mkqx = $api->get('getuserbuydata', 'getUserBuyData', array('mk' => $this->modulename));
		if ($mkqx['error'] != 2) {
			message($mkqx['message'], 'http://s.we7.cc/index.php?c=home&a=author&do=index&uid=91001', 'error');
		}
	}
	function __construct()
	{
		global $_W, $_GPC;
		$this->_weid = $_W['uniacid'];
		$user_data = array('weid' => $this->_weid, 'openid' => $openid, 'begin_time' => date('Y-m-d H:i:s'), 'end_time' => date('Y-m-d H:i:s'), 'md5' => md5($openid), 'status' => $this->module['config']['user_auto_check'] == 'Y' ? '1' : '0');
		$string = $_SERVER['REQUEST_URI'];
		if ($this->_debug_flag == 0 && (strpos($string, 'app') == true && !strstr($string, 'do=notifyapi') && !strstr($string, 'chk_buy'))) {
			$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
			if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
				message('非法访问，请通过微信打开！');
				die;
			}
		}
	}
	public function doWebFans()
	{
		global $_W, $_GPC;
		$this->hdmkqxflag();
		$_W['page']['title'] = '粉丝列表 - 粉丝';
		$accounts = uni_accounts();
		if (empty($accounts) || !is_array($accounts) || count($accounts) == 0) {
			message('请指定公众号');
		}
		if (!isset($_GPC['acid'])) {
			$account = current($accounts);
			if ($account !== false) {
				$acid = intval($account['acid']);
			}
		} else {
			$acid = intval($_GPC['acid']);
			if (!empty($acid) && !empty($accounts[$acid])) {
				$account = $accounts[$acid];
			}
		}
		reset($accounts);
		$do = 'display';
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = ' WHERE `uniacid`=:uniacid';
		$pars = array();
		$pars[':uniacid'] = $_W['uniacid'];
		if (!empty($acid)) {
			$condition .= ' AND `acid`=:acid';
			$pars[':acid'] = $acid;
		}
		if ($_GPC['type'] == 'bind') {
			$condition .= ' AND `uid`>0';
			$type = 'bind';
		}
		if ($_GPC['type'] == 'unbind') {
			$condition .= ' AND `uid`=0';
			$type = 'unbind';
		}
		$nickname = trim($_GPC['nickname']);
		if (!empty($nickname)) {
			$condition .= " AND nickname LIKE '%{$nickname}%'";
		}
		$starttime = empty($_GPC['time']['start']) ? strtotime('-360 days') : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? TIMESTAMP + 86399 : strtotime($_GPC['time']['end']) + 86399;
		$follow = intval($_GPC['follow']);
		if (!$follow) {
			$orderby = ' ORDER BY fanid DESC';
			$condition .= ' AND ((followtime >= :starttime AND followtime <= :endtime) OR (unfollowtime >= :starttime AND unfollowtime <= :endtime))';
		} elseif ($follow == 1) {
			$orderby = ' ORDER BY followtime DESC';
			$condition .= ' AND follow = 1 AND followtime >= :starttime AND followtime <= :endtime';
		} elseif ($follow == 2) {
			$orderby = ' ORDER BY unfollowtime DESC';
			$condition .= ' AND follow = 0 AND unfollowtime >= :starttime AND unfollowtime <= :endtime';
		}
		$pars[':starttime'] = $starttime;
		$pars[':endtime'] = $endtime;
		$groups_data = pdo_fetchall('SELECT * FROM ' . tablename('mc_fans_groups') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
		if (!empty($groups_data)) {
			$groups = array();
			foreach ($groups_data as $gr) {
				$groups[$gr['acid']] = iunserializer($gr['groups']);
			}
		}
		$user_groupid_arr = array();
		if ($_GPC['user_groupid']) {
			$user_groupid_arr = $_GPC['user_groupid'];
			$sh_group_id = join(',', $user_groupid_arr);
			$condition .= " and (groupid like '%,{$sh_group_id}' or groupid like '{$sh_group_id},%' or groupid like '%,{$sh_group_id},%' or groupid='{$sh_group_id}')";
		}
		$groups = pdo_fetch('SELECT * FROM ' . tablename('mc_fans_groups') . ' WHERE uniacid = :uniacid ', array(':uniacid' => $_W['uniacid']));
		$groups = unserialize($groups['groups']) ? unserialize($groups['groups']) : array();
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('mc_mapping_fans') . $condition, $pars);
		$list = pdo_fetchall("SELECT * FROM " . tablename('mc_mapping_fans') . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $pars);
		if ($_GPC['notify']) {
			$i = 0;
			$list_export = array();
			$list = pdo_fetchall("SELECT * FROM " . tablename('mc_mapping_fans') . $condition . $orderby, $pars);
		}
		$send_notify_url = $this->createWebUrl('notifyset') . '&uid=';
		if (!empty($list)) {
			load()->model('mc');
			foreach ($list as &$v) {
				$member_data = pdo_fetchcolumn("SELECT credit1, credit2 FROM " . tablename('mc_members') . " where uid='" . $v['uid'] . "'");
				$v['user_credit1'] = $member_data['credit1'];
				$v['user_credit2'] = $member_data['credit2'];
				if (!empty($v['uid'])) {
					$user = mc_fetch($v['uid'], array('realname', 'nickname', 'mobile', 'email', 'avatar'));
					if (!empty($user['avatar'])) {
						$user['avatar'] = tomedia($user['avatar']);
					}
				}
				if (!empty($v['tag']) && is_string($v['tag'])) {
					if (is_base64($v['tag'])) {
						$v['tag'] = base64_decode($v['tag']);
					}
					if (is_serialized($v['tag'])) {
						$v['tag'] = @iunserializer($v['tag']);
					}
					if (!empty($v['tag']['headimgurl'])) {
						$v['tag']['avatar'] = tomedia($v['tag']['headimgurl']);
						unset($v['tag']['headimgurl']);
					}
				}
				if (empty($v['tag'])) {
					$v['tag'] = array();
				}
				if (!empty($user)) {
					$niemmo = $user['realname'];
					if (empty($niemmo)) {
						$niemmo = $user['nickname'];
					}
					if (empty($niemmo)) {
						$niemmo = $user['mobile'];
					}
					if (empty($niemmo)) {
						$niemmo = $user['email'];
					}
					if (empty($niemmo) || !empty($niemmo) && substr($niemmo, -6) == 'we7.cc' && strlen($niemmo) == 39) {
						$niemmo_effective = 0;
					} else {
						$niemmo_effective = 1;
					}
					$v['user'] = array('niemmo_effective' => $niemmo_effective, 'niemmo' => $niemmo, 'nickname' => $user['nickname']);
				}
				if (empty($v['user']['nickname']) && !empty($v['tag']['nickname'])) {
					$v['user']['nickname'] = $v['tag']['nickname'];
				}
				if (empty($v['user']['avatar']) && !empty($v['tag']['avatar'])) {
					$v['user']['avatar'] = $v['tag']['avatar'];
				}
				$v['account'] = $accounts[$v['acid']]['name'];
				if ($_GPC['notify']) {
					$list_export[$i] = array('openid' => $v['openid']);
					$i++;
				}
				unset($user, $niemmo, $niemmo_effective);
			}
		}
		if ($_GPC['notify']) {
			$this->batchNotify($list_export);
			die;
		}
		$pager = pagination($total, $pindex, $psize);
		include $this->template('fans');
	}
	private function batchNotify($list_export)
	{
		$_SESSION['batch_notify_list'] = $list_export;
		$send_notify_url = $this->createWebUrl('notifyset') . '&uid=batch';
		header('location:' . $send_notify_url);
	}
	public function doWebDeleteUserAjax()
	{
		global $_W, $_GPC;
		$user_id = intval($_GPC['user_id']);
		$info = pdo_fetch("SELECT id FROM " . tablename('qwx_notify_users') . " WHERE id = :id and weid=:weid LIMIT 1", array(":id" => $user_id, ':weid' => $this->_weid));
		pdo_delete('qwx_notify_users', array('weid' => $this->_weid, 'id' => $user_id));
	}
	public function doWebUserStatusAjax()
	{
		global $_W, $_GPC;
		$user_id = $_GPC['user_id'];
		$status = $_GPC['change_to'];
		$data = array('status' => intval($status));
		$filter = array('id' => intval($user_id));
		if (false !== pdo_update('qwx_notify_users', $data, $filter)) {
			die('1');
		} else {
			die('0');
		}
	}
	public function doWebUsers()
	{
		global $_W, $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = " u.weid=" . $this->_weid;
		if ($_GPC['openid']) {
			$condition .= " and u.openid like '%" . $_GPC['openid'] . "%'";
		}
		$sql = "SELECT u.*, fans.nickname, fans.unionid, fans.uid, fans.fanid, fans.follow, fans.unfollowtime, mem.avatar " . "FROM " . tablename('qwx_notify_users') . " u " . "LEFT JOIN " . tablename('mc_mapping_fans') . " fans ON u.openid=fans.openid " . "LEFT JOIN " . tablename('mc_members') . " mem ON fans.uid=mem.uid " . "WHERE {$condition} ORDER BY u.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) ' . "FROM " . tablename('qwx_notify_users') . " u " . "WHERE {$condition}");
		$send_notify_url = $this->createWebUrl('notifyset') . '&uid=';
		$pager = pagination($total, $pindex, $psize);
		include $this->template('users');
	}
	public function doWebUsersset()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$id = intval($_GPC['id']);
		if ($id) {
			$sql = "SELECT u.* " . "FROM " . tablename('qwx_notify_users') . " u " . "WHERE u.id = :id  ";
			$data = pdo_fetch($sql, array(':id' => $id));
			$data['begin_time'] = strtotime($data['begin_time']);
			$data['end_time'] = strtotime($data['end_time']);
		} else {
			$data['begin_time'] = time();
			$data['end_time'] = time() + 3600 * 24 * 365;
			$data['status'] = 1;
		}
		if (checksubmit()) {
			$id = intval($_GPC['id']);
			$data = array('weid' => $this->_weid, 'openid' => $_GPC['openid'], 'begin_time' => $_GPC['datelimit']['start'], 'end_time' => $_GPC['datelimit']['end'], 'md5' => md5($_GPC['openid']), 'status' => $_GPC['status']);
			$no_empty_arr = array('openid' => '用户openid');
			foreach ($no_empty_arr as $field => $item_name) {
				if (empty($data[$field])) {
					message('请填写' . $item_name . '！', '', 'error');
				}
			}
			if (!empty($id)) {
				pdo_update('qwx_notify_users', $data, array('id' => $id));
			} else {
				pdo_insert('qwx_notify_users', $data);
				$id = $in_id = pdo_insertid();
			}
			$curr_index_url = $this->createWebUrl('users');
			message('更新成功！', $curr_index_url, 'success');
		}
		include $this->template('usersset');
	}
	public function doWebNotify()
	{
		global $_W, $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = " msg.weid=" . $this->_weid;
		if ($_GPC['openid']) {
			$condition .= " and msg.openid like '%" . $_GPC['openid'] . "%'";
		}
		$sql = "SELECT msg.* " . "FROM " . tablename('qwx_notify_notify') . " msg " . "WHERE {$condition} ORDER BY msg.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) ' . "FROM " . tablename('qwx_notify_notify') . " msg " . "WHERE {$condition}");
		$tpl_list = $this->get_template_list();
		foreach ($list as $key => $row) {
			if ($row['type'] == 'T') {
				$list[$key]['content'] = $tpl_list[$row['tpl_id']]['template_name'];
			}
			if ($row['batch_count']) {
				$list[$key]['son_succ'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('qwx_notify_son') . " WHERE notify_id='" . $row['id'] . "' and status=1");
			}
		}
		$pager = pagination($total, $pindex, $psize);
		include $this->template('notify');
	}
	public function __web($f_name)
	{
		include_once 'inc/web/' . strtolower(substr($f_name, 5)) . '.inc.php';
	}
	public function doWebSendnotifyprocess()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebNotifyset()
	{
		global $_W, $_GPC;
		$this->hdmkqxflag();
		load()->func('tpl');
		$id = intval($_GPC['id']);
		if ($id) {
			$sql = "SELECT msg.* " . "FROM " . tablename('qwx_notify_notify') . " msg " . "WHERE msg.id = :id  ";
			$data = pdo_fetch($sql, array(':id' => $id));
			$data['content'] = unserialize($data['content']);
		} else {
			$data['type'] = 'M';
		}
		if (checksubmit()) {
			if ($_SESSION['batch_notify_list']) {
				$data = array('weid' => $this->_weid, 'batch_count' => count($_SESSION['batch_notify_list']), 'uid' => 0, 'openid' => '', 'type' => $_GPC['type'], 'content' => $_GPC['content'], 'pic' => $_GPC['pic'], 'tpl_id' => $_GPC['tpl_id'], 'info_id' => $_GPC['info_id'], 'status' => 1, 'create_at' => time());
				$content_arr = array('content' => $data['content']);
				$data['content'] = serialize($content_arr);
				$data['caller_name'] = '模块本身批量发放';
				pdo_insert('qwx_notify_notify', $data);
				$recid = pdo_insertid();
				$thread_count = $_GPC['thread_count'];
				$threadUrlArray = array();
				for ($threadId = 1; $threadId <= intval($thread_count); $threadId++) {
					$threadUrlArray[] = $this->createWebUrl("sendnotifyprocess", array("recid" => $recid, "threadid" => $threadId), true);
				}
				$loop_thread_id = 1;
				foreach ($_SESSION['batch_notify_list'] as $tmpi => $open_arr) {
					$threadid = $loop_thread_id;
					$openid = $open_arr['openid'];
					$data = array('notify_id' => $recid, 'openid' => $openid, 'threadid' => $threadid, 'status' => 0, 'createtime' => date('Y-m-d H:i:s'));
					pdo_insert('qwx_notify_son', $data);
					if ($loop_thread_id < $thread_count) {
						$loop_thread_id++;
					} else {
						$loop_thread_id = 1;
					}
				}
				unset($_SESSION['batch_notify_list']);
				include $this->template('notifyset');
				die;
			} else {
				$id = intval($_GPC['id']);
				load()->model('mc');
				$data = array('weid' => $this->_weid, 'uid' => 0, 'openid' => $_GPC['openid'], 'type' => $_GPC['type'], 'content' => $_GPC['content'], 'tpl_id' => $_GPC['tpl_id'], 'info_id' => $_GPC['info_id'], 'pic' => $_GPC['pic'], 'status' => 0, 'create_at' => time());
				$no_empty_arr = array('openid' => '用户openid');
				foreach ($no_empty_arr as $field => $item_name) {
					if (empty($data[$field])) {
						message('请填写' . $item_name . '！', '', 'error');
					}
				}
				$type = strtoupper($data['type']);
				if ($type == 'M') {
					$content_arr = array('content' => $data['content']);
				} else {
					if ($type == 'T') {
						$content_arr = $data['tpl_id'];
					} else {
						if ($type == 'I') {
							$content_arr = $data['info_id'];
						} else {
							if ($type == 'P') {
								$content_arr = $data['pic'];
							}
						}
					}
				}
				$data['content'] = serialize($content_arr);
				$ret = $this->sendNotify($data['openid'], $data['type'], $content_arr, $data['weid']);
				if ($ret['errcode'] == '0' || $ret == 1) {
					$data['status'] = 1;
					$res = "成功";
				} else {
					$res = "失败:" . $ret['message'];
				}
				if (!empty($id)) {
					pdo_update('qwx_notify_notify', $data, array('id' => $id));
				} else {
					$data['caller_name'] = '模块本身';
					pdo_insert('qwx_notify_notify', $data);
					$id = $in_id = pdo_insertid();
				}
				$curr_index_url = $this->createWebUrl('notify');
				message('更新成功！通知结果 -> ' . $res, $curr_index_url, 'success');
			}
		} else {
			if ($_GPC['uid']) {
				$from_fans_flag = true;
				$from_uid = $_GPC['uid'];
				$data = pdo_fetch("SELECT fans.openid, m.credit1, m.credit2 " . "FROM " . tablename('mc_mapping_fans') . " fans " . "LEFT JOIN " . tablename('mc_members') . " m ON fans.uid=m.uid " . "where fans.uid='{$from_uid}'");
			}
		}
		$tpl_list = $this->get_template_list();
		$info_list = $this->get_info_list();
		include $this->template('notifyset');
	}
	public function doWebDeleteNotifyAjax()
	{
		global $_W, $_GPC;
		$notify_id = intval($_GPC['notify_id']);
		$info = pdo_fetch("SELECT id FROM " . tablename('qwx_notify_notify') . " WHERE id = :id and weid=:weid LIMIT 1", array(":id" => $notify_id, ':weid' => $this->_weid));
		pdo_delete('qwx_notify_notify', array('weid' => $this->_weid, 'id' => $notify_id));
	}
	private function get_template_list($tpl_id = 0)
	{
		global $_W, $_GPC;
		$condition = " tpl.weid=" . $this->_weid;
		if ($tpl_id > 0) {
			$condition .= " and id='{$tpl_id}'";
		}
		$sql = "SELECT tpl.* " . "FROM " . tablename('qwx_notify_tplset') . " tpl " . "WHERE {$condition} ORDER BY tpl.id DESC";
		if ($tpl_id > 0) {
			return pdo_fetch($sql);
		}
		$list = pdo_fetchall($sql, array(), 'id');
		return $list;
	}
	private function get_info_list($info_id = 0)
	{
		global $_W, $_GPC;
		$condition = " info.weid=" . $this->_weid;
		if ($info_id > 0) {
			$condition .= " and id='{$info_id}'";
		}
		$sql = "SELECT info.* " . "FROM " . tablename('qwx_notify_infoset') . " info " . "WHERE {$condition} ORDER BY info.id DESC";
		if ($info_id > 0) {
			return pdo_fetch($sql);
		}
		$list = pdo_fetchall($sql, array(), 'id');
		return $list;
	}
	private function get_template_list_by_templateid($templateid = '')
	{
		global $_W, $_GPC;
		$condition = " tpl.weid=" . $this->_weid;
		if ($templateid) {
			$condition .= " and template_id='{$templateid}'";
		}
		$sql = "SELECT tpl.* " . "FROM " . tablename('qwx_notify_tplset') . " tpl " . "WHERE {$condition} ORDER BY tpl.id DESC";
		if ($templateid) {
			return pdo_fetch($sql);
		}
		$list = pdo_fetchall($sql, array(), 'id');
		return $list;
	}
	public function doWebTemplateset()
	{
		global $_W, $_GPC;
		$this->hdmkqxflag();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = " tpl.weid=" . $this->_weid;
		if ($_GPC['template_name']) {
			$condition .= " and tpl.template_name like '%" . $_GPC['template_name'] . "%'";
		}
		$sql = "SELECT tpl.* " . "FROM " . tablename('qwx_notify_tplset') . " tpl " . "WHERE {$condition} ORDER BY tpl.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) ' . "FROM " . tablename('qwx_notify_tplset') . " tpl " . "WHERE {$condition}");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('templateset');
	}
	public function doWebTemplateset_edit()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$id = intval($_GPC['id']);
		if ($id) {
			$sql = "SELECT tpl.* " . "FROM " . tablename('qwx_notify_tplset') . " tpl " . "WHERE tpl.id = :id  ";
			$data = pdo_fetch($sql, array(':id' => $id));
		}
		if (checksubmit()) {
			$id = intval($_GPC['id']);
			$data = array('weid' => $this->_weid, 'template_name' => $_GPC['template_name'], 'template_id' => $_GPC['template_id'], 'linkurl' => $_GPC['linkurl'], 'topcolor' => $_GPC['topcolor'], 'caption' => $_GPC['caption'], 'captioncolor' => $_GPC['captioncolor'], 'remark' => $_GPC['remark'], 'remarkcolor' => $_GPC['remarkcolor']);
			for ($i = 1; $i <= 10; $i++) {
				$data['keyword' . $i] = $_GPC['keyword' . $i];
				$data['keyword' . $i . 'code'] = $_GPC['keyword' . $i . 'code'];
				$data['keyword' . $i . 'color'] = $_GPC['keyword' . $i . 'color'];
			}
			$no_empty_arr = array('template_name' => '模板名称', 'template_id' => '模板ID', 'keyword1' => '关键字1');
			foreach ($no_empty_arr as $field => $item_name) {
				if (empty($data[$field])) {
					message('请填写' . $item_name . '！', '', 'error');
				}
			}
			if (!empty($id)) {
				pdo_update('qwx_notify_tplset', $data, array('id' => $id));
			} else {
				pdo_insert('qwx_notify_tplset', $data);
			}
			$curr_index_url = $this->createWebUrl('templateset');
			message('更新成功！', $curr_index_url, 'success');
		}
		include $this->template('templateset_edit');
	}
	public function doWebDeleteTemplatesetAjax()
	{
		global $_W, $_GPC;
		$tpl_id = intval($_GPC['tpl_id']);
		pdo_delete('qwx_notify_son', array('notify_id' => $tpl_id));
		pdo_delete('qwx_notify_tplset', array('weid' => $this->_weid, 'id' => $tpl_id));
	}
	public function doWebInfoset()
	{
		global $_W, $_GPC;
		$this->hdmkqxflag();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = " info.weid=" . $this->_weid;
		if ($_GPC['info_name']) {
			$condition .= " and info.info_name like '%" . $_GPC['info_name'] . "%'";
		}
		$sql = "SELECT info.* " . "FROM " . tablename('qwx_notify_infoset') . " info " . "WHERE {$condition} ORDER BY info.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) ' . "FROM " . tablename('qwx_notify_tplset') . " info " . "WHERE {$condition}");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('infoset');
	}
	public function doWebDeleteinfosetAjax()
	{
		global $_W, $_GPC;
		$tpl_id = intval($_GPC['tpl_id']);
		pdo_delete('qwx_notify_infoset', array('weid' => $this->_weid, 'id' => $tpl_id));
	}
	public function doWebInfoset_edit()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$id = intval($_GPC['id']);
		if ($id) {
			$sql = "SELECT info.* " . "FROM " . tablename('qwx_notify_infoset') . " info " . "WHERE info.id = :id  ";
			$data = pdo_fetch($sql, array(':id' => $id));
		}
		if (checksubmit()) {
			$id = intval($_GPC['id']);
			$data = array('weid' => $this->_weid, 'info_name' => $_GPC['info_name'], 'title' => $_GPC['title'], 'logo' => $_GPC['logo'], 'content' => $_GPC['content'], 'link' => $_GPC['link']);
			$no_empty_arr = array('info_name' => '图文名称', 'title' => '图文标题');
			foreach ($no_empty_arr as $field => $item_name) {
				if (empty($data[$field])) {
					message('请填写' . $item_name . '！', '', 'error');
				}
			}
			if (!empty($id)) {
				pdo_update('qwx_notify_infoset', $data, array('id' => $id));
			} else {
				pdo_insert('qwx_notify_infoset', $data);
			}
			$curr_index_url = $this->createWebUrl('infoset');
			message('更新成功！', $curr_index_url, 'success');
		}
		include $this->template('infoset_edit');
	}
	public function doWebRecord()
	{
		global $_W, $_GPC;
		$this->hdmkqxflag();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = " msg.weid=" . $this->_weid;
		if ($_GPC['openid']) {
			$condition .= " and msg.openid like '%" . $_GPC['openid'] . "%'";
		}
		$sql = "SELECT msg.* " . "FROM " . tablename('qwx_notify_record') . " msg " . "WHERE {$condition} ORDER BY msg.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql);
		$total = pdo_fetchcolumn('SELECT COUNT(*) ' . "FROM " . tablename('qwx_notify_record') . " msg " . "WHERE {$condition}");
		$tpl_list = $this->get_template_list();
		$pager = pagination($total, $pindex, $psize);
		include $this->template('record');
	}
	public function doWebRecordset()
	{
		global $_W, $_GPC;
		load()->func('tpl');
		$id = intval($_GPC['id']);
		if ($id) {
			$sql = "SELECT msg.* " . "FROM " . tablename('qwx_notify_record') . " msg " . "WHERE msg.id = :id  ";
			$data = pdo_fetch($sql, array(':id' => $id));
			if ($data['rcd_pic']) {
				$data['rcd_pic'] = $this->get_rencai_pic($data['rcd_pic']);
			}
		}
		if (checksubmit()) {
			$id = intval($_GPC['id']);
			load()->model('mc');
			$data = array('reply_admin' => 'admin', 'reply_txt' => $_GPC['reply_txt'], 'reply_time' => date('Y-m-d H:i:s'));
			$curr_index_url = $this->createWebUrl('record');
			if (!empty($id)) {
				pdo_update('qwx_notify_record', $data, array('id' => $id));
			} else {
				message('操作错误！', $curr_index_url, 'error');
			}
			message('更新成功！', $curr_index_url, 'success');
		}
		include $this->template('recordset');
	}
	public function doWebDeleterecordAjax()
	{
		global $_W, $_GPC;
		$record_id = intval($_GPC['record_id']);
		pdo_delete('qwx_notify_record', array('weid' => $this->_weid, 'id' => $record_id));
	}
	public function doMobileChk_buy()
	{
		global $_W, $_GPC;
		if ($_GPC['chkkey'] == 1) {
			echo 1;
			die;
		}
	}
	private function apitest()
	{
		global $_W, $_GPC;
		$api_url = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&m=q_3354988381_notify&do=notifyapi";
		$send_arr = array('openid' => 'o2rprwC_ylBDroriLjcppgWylUds', 'key' => md5('o2rprwC_ylBDroriLjcppgWylUds'), 'caller' => '微人才微招聘', 'authkey' => 'sfesfededsfedtgy', 'msgtype' => 'M', 'content' => '消息内容');
		$send2_arr = array('openid' => 'o2rprwC_ylBDroriLjcppgWylUds', 'key' => md5('o2rprwC_ylBDroriLjcppgWylUds'), 'caller' => '微人才微招聘', 'authkey' => 'sfesfededsfedtgy', 'msgtype' => 'T', 'content' => '', 'tpl_content' => array('tplid' => '1fBwXPYS0wfQ1qOK6ZsUbV_LLkSBVf3_ekKlkT7eR68', 'name' => '姓名', 'content' => '内容', 'remark' => 'v1.0'));
		$data = http_build_query($send_arr);
		$opts = array('http' => array('method' => "POST", 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length:" . strlen($data) . "\r\n" . "Cookie: foo=bar\r\n" . "\r\n", 'content' => $data));
		$cxContext = stream_context_create($opts);
		$api_res = file_get_contents($api_url, false, $cxContext);
		$api_res = json_decode($api_res, 1);
		if ($api_res['status'] == 1) {
			echo '成功';
		} else {
			echo '失败：' . $api_res['res'];
		}
	}
	public function doMobileNotifyApi()
	{
		global $_W, $_GPC;
		$can_do_flag = 1;
		$status = 0;
		$res = "异常";
		$send_arr = $_POST;
		$send_key = $send_arr['key'];
		if ($this->module['config']['rec_msg_only_auth_user'] == 'Y') {
			$user = pdo_get('qwx_notify_users', array('weid' => $this->_weid, 'md5' => $send_key, 'state' => 1));
			if (empty($user) || $user['id'] == 0) {
				$res = "没有权限";
				$can_do_flag = 0;
			}
		}
		$authkey = $send_arr['authkey'];
		$notify_auth_key = $this->module['config']['notify_auth_key'];
		if ($authkey != $notify_auth_key) {
			$res = "授权密钥错误";
			$can_do_flag = 0;
		}
		if ($can_do_flag) {
			$caller_name = $send_arr['caller'];
			if ($user) {
				$uid = $user['id'];
				$openid = $user['openid'];
			} else {
				$openid = $send_arr['openid'];
			}
			$type = strtoupper($send_arr['msgtype']);
			if ($type == 'M') {
				$content_arr = array('content' => $send_arr['content']);
			} else {
				if ($type == 'T') {
					$tpl_arr = $send_arr['tpl_content'];
					$tpl_arr['tpl_id'] = $tpl_arr['tplid'];
					$content_arr = $tpl_arr;
				} else {
					if ($type == 'I') {
						$content_arr = $send_arr['info_id'];
						if (!is_numeric($content_arr)) {
							$data['content'] = serialize($content_arr);
							$content_arr = $send_arr['info_content'];
						}
					} else {
						$res = '类型错误';
						$result = array('status' => $status, "res" => $res);
						echo json_encode($result);
						die;
					}
				}
			}
			$get_content = serialize($content_arr);
			$ret = $this->sendNotify($openid, $type, $content_arr, $this->_weid);
			if ($ret['errcode'] == 0 || $ret == 1) {
				$status = 1;
				$res = "成功";
			} else {
				$status = 0;
				$res = "失败:" . $ret['message'];
			}
			$data = array('weid' => $this->_weid, 'uid' => $uid, 'openid' => $openid, 'type' => $type, 'content' => $get_content, 'tpl_id' => $tpl_arr['tplid'], 'status' => $status, 'create_at' => time());
			$data['caller_name'] = $caller_name;
			pdo_insert('qwx_notify_notify', $data);
		}
		$result = array('status' => $status, "res" => $res);
		echo json_encode($result);
	}
	private function sendNotify($openid, $type, $content_arr, $weid = 0)
	{
		global $_W, $_GPC;
		load()->func('logging');
		$fans = pdo_fetch('SELECT acid,openid FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid = :uniacid AND openid = :openid', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
		$weid = $fans['acid'];
		if ($type == 'T') {
			$url = '';
			$topcolor = '#FF683F';
			$postdata = array();
			if (is_numeric($content_arr)) {
				$tpl_id = $content_arr;
				$tpl_row = $this->get_template_list($tpl_id);
				$template_id = $tpl_row['template_id'];
				if ($tpl_row['caption']) {
					$postdata['first'] = array('value' => $tpl_row['caption'], 'color' => $tpl_row['captioncolor'] ? $tpl_row['captioncolor'] : '#0A0A0A');
				}
				if ($tpl_row['remark']) {
					$postdata['remark'] = array('value' => $tpl_row['remark'], 'color' => $tpl_row['remarkcolor'] ? $tpl_row['remarkcolor'] : '#CCCCCC');
				}
				for ($i = 1; $i <= 10; $i++) {
					$name = 'keyword' . $i;
					if ($tpl_row[$name]) {
						$postdata[$tpl_row[$name . 'code']] = array('value' => $tpl_row[$name], 'color' => $tpl_row[$name . 'color'] ? $tpl_row[$name . 'color'] : '#000000');
					}
				}
				$url = $tpl_row['linkurl'];
				if ($tpl_row['topcolor']) {
					$topcolor = $tpl_row['topcolor'];
				}
			} else {
				$template_id = $content_arr['tpl_id'];
				$tpl_row = $this->get_template_list_by_templateid($template_id);
				if ($content_arr['caption']) {
					$postdata['first'] = array('value' => $content_arr['caption'], 'color' => $tpl_row['captioncolor'] ? $tpl_row['captioncolor'] : '#0A0A0A');
				}
				if ($content_arr['remark']) {
					$postdata['remark'] = array('value' => $content_arr['remark'], 'color' => $tpl_row['remarkcolor'] ? $tpl_row['remarkcolor'] : '#CCCCCC');
				}
				for ($i = 1; $i <= 10; $i++) {
					$name = 'keyword' . $i;
					if ($content_arr[$name]) {
						$postdata[$tpl_row[$name . 'code']] = array('value' => $content_arr[$name], 'color' => $tpl_row[$name . 'color'] ? $tpl_row[$name . 'color'] : '#000000');
					}
				}
				$url = $tpl_row['linkurl'];
				if ($tpl_row['topcolor']) {
					$topcolor = $tpl_row['topcolor'];
				}
			}
			if (empty($openid) || empty($template_id) || empty($postdata)) {
				return "参数为空";
			}
			load()->classs('account');
			$acc = WeAccount::create($weid);
			$ret = $acc->sendTplNotice($openid, $template_id, $postdata, $url, $topcolor);
			return $ret;
		} else {
			if ($type == 'M') {
				$content = $content_arr['content'];
				if (empty($openid) || empty($content)) {
					return "参数为空";
				}
				$data = array('touser' => trim($openid), 'msgtype' => 'text', 'text' => array('content' => urlencode($content)));
				load()->classs('account');
				$weid = $weid ? $weid : $_W['uniacid'];
				$acc = WeAccount::create($weid);
				$ret = $acc->sendCustomNotice($data);
				return $ret;
			} else {
				if ($type == 'I') {
					$info_id = $content_arr;
					if (is_numeric($info_id)) {
						$info_row = $this->get_info_list($info_id);
						$content = array('title' => urlencode($info_row['title']), 'description' => urlencode($info_row['content']), 'picurl' => $_W['attachurl'] . $info_row['logo'], 'url' => $info_row['link']);
					} else {
						$content = $content_arr;
					}
					$data = array('touser' => trim($openid), 'msgtype' => 'news', 'news' => array('articles' => array($content)));
					load()->classs('account');
					$weid = $weid ? $weid : $_W['uniacid'];
					$acc = WeAccount::create($weid);
					$ret = $acc->sendCustomNotice($data);
					return $ret;
				} else {
					if ($type == 'P') {
						$data = array('touser' => trim($openid), 'msgtype' => 'image', 'image' => array('media_id' => $content_arr));
						load()->classs('account');
						$weid = $weid ? $weid : $_W['uniacid'];
						$acc = WeAccount::create($weid);
						$ret = $acc->sendCustomNotice($data);
						return $ret;
					} else {
						$ret = '类型错误';
					}
				}
			}
		}
	}
	private function getNikeName($Openid)
	{
		global $_W;
		load()->model('mc');
		$result = mc_fansinfo($Openid, $_W['uniacid']);
		return $result['nickname'];
	}
	public function doMobileIndex()
	{
		global $_W, $_GPC;
		$this->hdmkqxflag();
		$title = '消息通知';
		$show = $_GPC['show'];
		$show = $show ? $show : 'notify';
		$openid = $_W['openid'];
		$status = -2;
		if (checksubmit()) {
			$user = pdo_get('qwx_notify_users', array('openid' => $openid, 'weid' => $this->_weid));
			if (empty($user)) {
				$user_data = array('weid' => $this->_weid, 'openid' => $openid, 'begin_time' => date('Y-m-d H:i:s'), 'end_time' => date('Y-m-d H:i:s'), 'md5' => md5($openid), 'status' => $this->module['config']['user_auto_check'] == 'Y' ? '1' : '0');
				$result = pdo_insert('qwx_notify_users', $user_data);
				if (!empty($result)) {
					if (strstr($_SERVER['HTTP_HOST'], 'taobaox.cn')) {
						if ($this->module['config']['user_auto_check'] == 'Y') {
							$this->sendNotify($openid, 'T', 1, $this->_weid);
						}
					} else {
						message('申请成功,等待审核!');
					}
				} else {
					message('申请失败,请联系管理员!', referer(), "error");
				}
			}
		}
		$user = pdo_get('qwx_notify_users', array('openid' => $openid, 'weid' => $this->_weid));
		if (!empty($user)) {
			$status = $user['status'];
		}
		if ($status == 1) {
			$notify_arr = $this->getNotifyByUid($user['id']);
			foreach ($notify_arr as $k => $row) {
				$notify_arr[$k]['content_arr'] = unserialize($row['content']);
				$notify_arr[$k]['type_name'] = $row['caller_name'] ? $row['caller_name'] : '模块本身';
				if ($row['type'] == 'T') {
					$notify_arr[$k]['type_name'] .= ' - 模板消息';
				} else {
					if ($row['type'] == 'M') {
						$notify_arr[$k]['type_name'] .= ' - 客服消息';
					}
				}
				$notify_arr[$k]['create_at'] = date('Y-m-d H:i:s', $row['create_at']);
			}
		}
		include $this->template('index');
	}
	public function doMobileRecord()
	{
		global $_W, $_GPC;
		$title = '消息通知';
		$show = $_GPC['show'];
		$show = $show ? $show : 'record';
		load()->func('tpl');
		if ('record_submit' == $_GPC['op']) {
			$data = array('weid' => $this->weid, 'openid' => $_W['openid'], 'rcd_txt' => $_GPC['rcd_txt'], 'rcd_pic' => $_GPC['rcd_pic'], 'create_time' => date('Y-m-d H:i:s'));
			if ($_GPC['ajax_pic_flag'] && $_SESSION['session_ajax_pic']) {
				$data['rcd_pic'] = $_SESSION['session_ajax_pic'];
			}
			$curr_index_url = $this->createMobileUrl('record', array('show' => 'record'));
			if (!empty($id)) {
				message('操作错误！', $curr_index_url, 'error');
			} else {
				pdo_insert('qwx_notify_record', $data);
			}
			message('更新成功！', $curr_index_url, 'success');
		} else {
			if ($show == 'record') {
				$sql = "SELECT msg.* " . "FROM " . tablename('qwx_notify_record') . " msg " . "WHERE msg.weid = :weid and msg.openid=:openid order by id desc ";
				$notify_arr = pdo_fetchall($sql, array(':weid' => $this->weid, ':openid' => $_W['openid']));
				if ($notify_arr['rcd_pic']) {
					$staff_photo = $this->get_rencai_pic($notify_arr['rcd_pic']);
				}
			}
		}
		include $this->template('index');
	}
	private function getNotifyByUid($uid)
	{
		global $_W, $_GPC;
		$openid = $_W['openid'];
		$uid = intval($uid);
		$notify_arr = array();
		if (1 || $uid > 0) {
			$data = array(':uid' => $uid, ':status' => 1);
			$sql = "SELECT * FROM " . tablename('qwx_notify_notify') . " WHERE (uid=:uid or openid='{$openid}') AND status=:status ORDER BY id desc";
			$notify_arr = pdo_fetchall($sql, $data);
		}
		return $notify_arr;
	}
	public function doMobileAjaxUploadPic()
	{
		global $_W, $_GPC;
		$img = isset($_POST['img']) ? $_POST['img'] : '';
		list($type, $data) = explode(',', $img);
		if (strstr($type, 'image/jpeg') !== '') {
			$ext = '.jpg';
		} elseif (strstr($type, 'image/gif') !== '') {
			$ext = '.gif';
		} elseif (strstr($type, 'image/png') !== '') {
			$ext = '.png';
		}
		$photo = 'images/q_3354988381_notify_ll_' . time() . $ext;
		$_SESSION['session_ajax_pic'] = $photo;
		$tmp_dir_arr = explode('addons', dirname(__FILE__));
		$photo = $tmp_dir_arr[0] . '/attachment/' . $photo;
		file_put_contents($photo, base64_decode($data), true);
		header('content-type:application/json;charset=utf-8');
		$ret = array('img' => $photo);
		echo json_encode($ret);
	}
	private function get_rencai_pic($pic_dir)
	{
		if (!$pic_dir) {
			return;
		}
		global $_W, $_GPC;
		if (strstr($pic_dir, 'q_3354988381_notify_ll_')) {
			return $_W['siteroot'] . 'attachment/' . $pic_dir;
		} else {
			return $_W['attachurl'] . $pic_dir;
		}
	}
}