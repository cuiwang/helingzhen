<?php

defined('IN_IA') or exit('Access Denied');
define('MODULE_NAME', 'dayu_form');
define('TEMPLATE_PATH', '../addons/dayu_form/template/style/');
define('TEMPLATE_WEUI', '../addons/dayu_form/template/weui/');
class dayu_formModuleSite extends WeModuleSite
{
	private $tb_form = 'dayu_form';
	private $tb_data = 'dayu_form_data';
	private $tb_field = 'dayu_form_fields';
	private $tb_reply = 'dayu_form_reply';
	private $tb_info = 'dayu_form_info';
	private $tb_staff = 'dayu_form_staff';
	private $tb_custom = 'dayu_form_custom';
	private $tb_linkage = 'dayu_form_linkage';
	private $tb_role = 'dayu_form_role';
	private $tb_category = 'dayu_form_category';
	private $tb_slide = 'dayu_form_slide';
	public $_appid = '';
	public $_appsecret = '';
	public $_accountlevel = '';
	public $_account = '';
	public $_weid = '';
	public $_openid = '';
	public $_nickname = '';
	public $_headimgurl = '';
	public $_auth2_openid = '';
	public $_auth2_nickname = '';
	public $_auth2_headimgurl = '';
	function __construct()
	{
		global $_W, $_GPC;
		load()->model('mc');
		$this->_openid = $_W['openid'];
		$this->_weid = $_W['uniacid'];
		$account = $_W['account'];
		$this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
		$this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
		$this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];
		$this->_appid = $_W['account']['key'];
		$this->_appsecret = $_W['account']['secret'];
		$this->_accountlevel = $account['level'];
		if (isset($_COOKIE[$this->_auth2_openid])) {
			$this->_openid = $_COOKIE[$this->_auth2_openid];
		}
		if ($this->_accountlevel < 4) {
			$settings = uni_setting($this->_weid);
			$oauth = $settings['oauth'];
			if (!empty($oauth) && !empty($oauth['account'])) {
				$this->_account = account_fetch($oauth['account']);
				$this->_appid = $this->_account['key'];
				$this->_appsecret = $this->_account['secret'];
			}
		} else {
			$this->_appid = $_W['account']['key'];
			$this->_appsecret = $_W['account']['secret'];
		}
	}
	public function oauth2($url)
	{
		global $_GPC, $_W;
		load()->func('communication');
		$code = $_GPC['code'];
		if (empty($code)) {
			$this->showMessage('code获取失败.');
		}
		$token = $this->get_Authorization_Code($code, $url);
		$from_user = $token['openid'];
		$userinfo = $this->get_User_Info($from_user);
		$state = 1;
		if ($userinfo['subscribe'] == 0) {
			$state = 0;
			$authkey = intval($_GPC['authkey']);
			if ($authkey == 0) {
				$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
				header("location:$oauth2_code");
			}
			$userinfo = $this->get_User_Info($from_user, $token['access_token']);
		}
		if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
			echo '<h1>获取微信公众号授权失败[无法取得粉丝信息], 请稍后重试！ 公众平台返回原始数据: <br />' . $state . $userinfo['meta'] . '<h1>';
			exit;
		}
		setcookie($this->_auth2_headimgurl, $userinfo['headimgurl'], time() + 3600 * 24);
		setcookie($this->_auth2_nickname, $userinfo['nickname'], time() + 3600 * 24);
		setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
		setcookie($this->_auth2_sex, $userinfo['sex'], time() + 3600 * 24);
		return $userinfo;
	}
	public function get_Access_Token()
	{
		global $_W;
		$account = $_W['account'];
		if ($this->_accountlevel < 4) {
			if (!empty($this->_account)) {
				$account = $this->_account;
			}
		}
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($account['acid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}
	public function get_Authorization_Code($code, $url)
	{
		$oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
		$error = ihttp_get($oauth2_code);
		$token = @json_decode($error['content'], true);
		if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
			$oauth2_code = $url;
			header("location:$oauth2_code");
			echo '微信授权失败! 公众平台返回原始数据: <br>' . $error['meta'];
			exit;
		}
		return $token;
	}
	public function get_User_Info($from_user, $ACCESS_TOKEN = '')
	{
		if ($ACCESS_TOKEN == '') {
			$ACCESS_TOKEN = $this->get_Access_Token();
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
		} else {
			$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
		}
		$json = ihttp_get($url);
		$userinfo = @json_decode($json['content'], true);
		return $userinfo;
	}
	public function get_Code($url)
	{
		global $_W;
		$url = urlencode($url);
		$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
		header("location:$oauth2_code");
	}
	public function getHomeTiles()
	{
		global $_W;
		$urls = array();
		$list = pdo_fetchall("SELECT title, reid FROM " . tablename($this->tb_form) . " WHERE weid = '{$_W['uniacid']}'");
		if (!empty($list)) {
			foreach ($list as $row) {
				$urls[] = array('title' => $row['title'], 'url' => $_W['siteroot'] . "app/" . $this->createMobileUrl('dayu_form', array('id' => $row['reid'])));
			}
		}
		return $urls;
	}
	public function __web($f_name)
	{
		global $_W, $_GPC;
		checklogin();
		require 'fans.web.php';
		$id = $_GPC['id'];
		load()->func('tpl');
		$op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		include_once 'inc/web/' . strtolower(substr($f_name, 5)) . '.php';
	}
	public function doWebCategory()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebSlide()
	{
		$this->__web(__FUNCTION__);
	}
	public function __mobile($f_name)
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		$returnUrl = urlencode($_W['siteurl']);
		include_once 'inc/mobile/' . strtolower(substr($f_name, 8)) . '.php';
	}
	public function doWebQuery()
	{
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY reid DESC LIMIT 0,8';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		foreach ($ds as &$row) {
			$r = array();
			$r['title'] = $row['title'];
			$r['description'] = cutstr(strip_tags($row['description']), 50);
			$r['thumb'] = $row['thumb'];
			$r['reid'] = $row['reid'];
			$row['entry'] = $r;
		}
		include $this->template('query');
	}
	public function doWebLinkage()
	{
		global $_GPC, $_W;
		load()->func('tpl');
		require 'fans.web.php';
		$reid = intval($_GPC['reid']);
		$role = $this->get_isrole($reid, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		$activity = pdo_get($this->tb_form, array('weid' => $_W['uniacid'], 'reid' => $reid), array('title', 'linkage'));
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$la = iunserializer($activity['linkage']);
			if (checksubmit('submit')) {
				$data = array('l1' => $_GPC['la1'], 'l2' => $_GPC['la2']);
				$record = array();
				$record['linkage'] = iserializer($data);
				pdo_update($this->tb_form, $record, array('reid' => $reid));
				message('保存成功', $this->createWebUrl('linkage', array('op' => 'display', 'reid' => $reid)), 'success');
			}
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update($this->tb_linkage, array('displayorder' => $displayorder), array('id' => $id));
				}
				message('联动排序更新成功！', $this->createWebUrl('linkage', array('op' => 'display', 'reid' => $reid)), 'success');
			}
			$children = array();
			$linkage = pdo_fetchall("SELECT * FROM " . tablename($this->tb_linkage) . " WHERE reid = '{$reid}' ORDER BY parentid ASC, displayorder desc");
			foreach ($linkage as $index => $item) {
				if (!empty($item['parentid'])) {
					$children[$item['parentid']][] = $item;
					unset($linkage[$index]);
				}
			}
			include $this->template('linkage');
		} elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$linkage = pdo_fetch("SELECT * FROM " . tablename($this->tb_linkage) . " WHERE id = '$id'");
			} else {
				$linkage = array('displayorder' => 0);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, title FROM " . tablename($this->tb_linkage) . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级联动不存在或是已经被删除！', $this->createWebUrl('linkage', array('op' => 'post', 'reid' => $reid)), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('抱歉，请输入联动标题！');
				}
				$data = array('reid' => $reid, 'title' => $_GPC['title'], 'parentid' => intval($parentid), 'displayorder' => intval($_GPC['displayorder']));
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update($this->tb_linkage, $data, array('id' => $id));
				} else {
					pdo_insert($this->tb_linkage, $data);
					$id = pdo_insertid();
				}
				message('更新联动成功！', $this->createWebUrl('linkage', array('op' => 'display', 'reid' => $reid)), 'success');
			}
			include $this->template('linkage');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$linkage = pdo_fetch("SELECT * FROM " . tablename($this->tb_linkage) . " WHERE id = '$id'");
			if (empty($linkage)) {
				message('抱歉，联动不存在或是已经被删除！', $this->createWebUrl('linkage', array('op' => 'display', 'reid' => $reid)), 'error');
			}
			pdo_delete($this->tb_linkage, array('id' => $id));
			message('联动删除成功！', $this->createWebUrl('linkage', array('op' => 'display', 'reid' => $reid)), 'success');
		}
	}
	public function doMobileGetLinkage()
	{
		global $_GPC, $_W;
		$jss = pdo_fetchall("SELECT * FROM " . tablename($this->tb_linkage) . " WHERE parentid = :parentid ORDER BY displayorder desc, id DESC", array(':parentid' => $_GPC['linkage1']));
		if (empty($jss)) {
			$result['status'] = 0;
			$result['jss'] = '没有下级内容';
			message($result, '', 'ajax');
		}
		$result['status'] = 1;
		$result['jss'] = $jss;
		message($result, '', 'ajax');
	}
	public function doWebStaff()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
		$weid = $_W['uniacid'];
		$reid = intval($_GPC['reid']);
		$role = $this->get_isrole($reid, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		$activity = pdo_fetch('SELECT reid,title,kfid FROM ' . tablename($this->tb_form) . ' WHERE weid = :weid AND reid = :reid', array(':weid' => $weid, ':reid' => $reid));
		if (empty($activity)) {
			message('表单不存在或已删除', $this->createWebUrl('display'), 'error');
		}
		if ($op == 'list') {
			$where = ' reid = :reid';
			$params[':reid'] = $reid;
			if (!empty($_GPC['keyword'])) {
				$where .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_staff) . ' WHERE ' . $where, $params);
			$lists = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_staff) . ' WHERE ' . $where . ' ORDER BY createtime DESC,id ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params, 'id');
			$pager = pagination($total, $pindex, $psize);
			if (checksubmit('submit')) {
				if (!empty($_GPC['ids'])) {
					foreach ($_GPC['ids'] as $k => $v) {
						$data = array('nickname' => trim($_GPC['nickname'][$k]), 'openid' => trim($_GPC['openid'][$k]), 'weid' => trim($_GPC['weid'][$k]));
						pdo_update('dayu_form_staff', $data, array('reid' => $reid, 'id' => intval($v)));
					}
					message('编辑成功', $this->createWebUrl('staff', array('op' => 'list', 'reid' => $reid)), 'success');
				}
			}
			include $this->template('staff');
		} elseif ($op == 'post') {
			if (checksubmit('submit')) {
				$data['reid'] = $reid;
				$data['nickname'] = $_GPC['nickname'];
				$data['openid'] = $_GPC['openid'];
				$data['weid'] = $_GPC['weid'];
				$data['createtime'] = time();
				pdo_insert('dayu_form_staff', $data);
				message('添加客服成功', $this->createWebUrl('staff', array('reid' => $reid, 'op' => 'list')), 'success');
			}
			include $this->template('staff');
		} elseif ($op == 'staffdel') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				pdo_delete('dayu_form_staff', array('id' => $id));
			}
			message('删除成功.', referer());
		}
	}
	public function doWebchangecheckedAjax()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$id = $_GPC['id'];
		$kfid = $_GPC['kfid'];
		$role = $this->get_isrole($id, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		if (false !== pdo_update('dayu_form', array('kfid' => $kfid), array('reid' => intval($id), 'weid' => $_W['uniacid']))) {
			exit('1');
		} else {
			exit('0');
		}
	}
	public function doWebEditkf()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		if ($_GPC['dopost'] == "update") {
			$reid = $_GPC['reid'];
			$nickname = $_GPC['nickname'];
			$openid = $_GPC['openid'];
			$role = $this->get_isrole($reid, $_W['user']['uid']);
			if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
				message('您没有权限进行该操作.');
			}
			if (is_array($reid)) {
				foreach ($reid as $k => $v) {
					$actid = $v . ",";
				}
			}
			$actid = substr($actid, 0, strlen($actid) - 1);
			$a = pdo_update('dayu_form_staff', array('reid' => $actid, 'nickname' => $nickname, 'openid' => $openid), array('id' => $_GPC['id']));
			message("更改成功!", referer());
			exit;
		}
		$fff = pdo_fetchall("SELECT reid,title FROM " . tablename($this->tb_form));
		$config = pdo_fetch("SELECT * from " . tablename($this->tb_staff) . " where id=" . $_GPC['id']);
		$fun = explode(',', $config['reid']);
		include $this->template('kf_edit');
	}
	public function doWebDetail()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$settings = $this->module['config'];
		$rerid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_info) . " WHERE `rerid`=:rerid";
		$params = array();
		$params[':rerid'] = $rerid;
		$row = pdo_fetch($sql, $params);
		if (empty($row)) {
			message('访问非法.');
		}
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $row['reid'];
		$activity = pdo_fetch($sql, $params);
		if (empty($activity)) {
			message('非法访问.');
		}
		$role = $this->get_isrole($row['reid'], $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		$row['thumb'] = iunserializer($row['thumb']);
		$row['voices'] = $row['voice'];
		$row['revoices'] = $row['revoice'];
		$la = iunserializer($activity['linkage']);
		$linkage = iunserializer($row['linkage']);
		$linkage['l1'] = $this->get_linkage($linkage['l1'], '');
		$linkage['l2'] = $this->get_linkage($linkage['l2'], '');
		$row['file'] = iunserializer($row['file']);
		$par = iunserializer($activity['par']);
		$status = $this->get_status($row['reid'], $row['status']);
		$state = array();
		$arr2 = array('0', '1', '2', '3', '8', '7');
		foreach ($arr2 as $index => $v) {
			$state[$v][] = $this->get_status($row['reid'], $v);
		}
		if (pdo_tableexists('dayu_kami') && !empty($par['kami']) && $row['kid']) {
			$kami = pdo_get('dayu_kami', array('weid' => $_W['uniacid'], 'id' => $row['kid']), array());
		}
		if (pdo_tableexists('dayu_sendkami') && !empty($par['sendkami']) && $row['kid']) {
			$kami = pdo_get('dayu_sendkami', array('weid' => $_W['uniacid'], 'id' => $row['kid']), array());
		}
		if (!empty($par['comment']) && pdo_tableexists('dayu_comment') && !empty($row['commentid'])) {
			$comment = pdo_get('dayu_comment', array('weid' => $_W['uniacid'], 'id' => $row['commentid']), array());
		}
		$sql = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY `refid`';
		$params = array();
		$params[':reid'] = $row['reid'];
		$fields = pdo_fetchall($sql, $params);
		if (empty($fields)) {
			message('非法访问.');
		}
		$custom = pdo_fetchall("SELECT * FROM " . tablename($this->tb_custom) . " WHERE weid = :weid ORDER BY displayorder DESC", array(':weid' => $_W['uniacid']));
		$ds = $fids = array();
		foreach ($fields as $f) {
			$ds[$f['refid']]['fid'] = $f['title'];
			$ds[$f['refid']]['type'] = $f['type'];
			$ds[$f['refid']]['refid'] = $f['refid'];
			$ds[$f['refid']]['loc'] = $f['loc'];
			$fids[] = $f['refid'];
		}
		$fids = implode(',', $fids);
		$row['fields'] = array();
		$sql = 'SELECT * FROM ' . tablename($this->tb_data) . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
		$fdatas = pdo_fetchall($sql, $params);
		foreach ($fdatas as $fd) {
			$row['fields'][$fd['refid']] = $fd['data'];
		}
		foreach ($ds as $value) {
			if ($value['type'] == 'reside') {
				$row['fields'][$value['refid']] = '';
				foreach ($fdatas as $fdata) {
					if ($fdata['refid'] == $value['refid']) {
						$row['fields'][$value['refid']] .= $fdata['data'];
					}
				}
				break;
			}
		}
		$record = array();
		$record['status'] = intval($_GPC['status']);
		$record['yuyuetime'] = strtotime($_GPC['yuyuetime']);
		if ($activity['isrethumb']) {
			$record['rethumb'] = $_GPC['rethumb'];
		}
		$record['kfinfo'] = $_GPC['kfinfo'];
		if (!empty($_GPC['file'])) {
			foreach ($_GPC['file'] as $file) {
				$th[] = $file;
			}
			$record['file'] = iserializer($th);
		}
		if ($_GPC['status'] == '3' && $par['icredit'] == '1') {
			$record['icredit'] = 1;
		}
		$row['user'] = mc_fansinfo($row['openid'], $acid, $weid);
		$row['member'] = !empty($row['member']) ? $row['member'] : $row['user']['nickname'];
		$ytime = date('Y-m-d H:i:s', TIMESTAMP);
		$outurl = !empty($par['noticeurl']) ? $par['noticeurl'] : $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_form', array('op' => 'detail', 'rerid' => $rerid, 'id' => $row['reid']));
		$testfile = $_FILES['upfile'];
		if (checksubmit('submit')) {
			$kfinfo = !empty($_GPC['kfinfo']) ? "\n客服回复：" . $_GPC['kfinfo'] : "";
			$state = $this->get_status($row['reid'], $_GPC['status']);
			$huifu = $state['name'] . $kfinfo . $revoice;
			if ($activity['custom_status'] == 1) {
				$url = $outurl;
				$info = "【您好，受理结果通知】\n\n";
				$info .= "姓名：{$row['member']}\n手机：{$row['mobile']}\n受理结果：{$huifu}\n\n";
				$info .= "<a href='{$url}'>点击查看详情</a>";
				$custom = array('msgtype' => 'text', 'text' => array('content' => urlencode($info)));
				$custom['touser'] = trim($row['openid']);
				$acc = WeAccount::create($_W['acid']);
				$CustomNotice = $acc->sendCustomNotice($custom);
				if (is_error($status)) {
					message('发送失败，原因为' . $status['message']);
				}
			} elseif ($activity['custom_status'] == '0' && !empty($activity['mfirst'])) {
				$template = array("touser" => $row['openid'], "template_id" => $activity['m_templateid'], "url" => $outurl, "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($activity['mfirst']), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($row['member']), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($row['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($_GPC['yuyuetime']), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($huifu), 'color' => "#FF0000"), 'remark' => array('value' => urlencode("\\n" . $activity['mfoot']), 'color' => "#008000")));
				$this->send_template_message(urldecode(json_encode($template)));
			}
			if ($par['sms'] != '0' && $par['paixu'] != '2' && !empty($activity['smsnotice'])) {
				load()->func('communication');
				$content = '状态：' . $state['name'];
				ihttp_post(murl('entry', array('do' => 'Notice', 'id' => $activity['smsnotice'], 'm' => 'dayu_sms'), true, true), array('mobile' => $row['mobile'], 'mname' => $row['member'], 'content' => $content));
			}
			if ($row['icredit'] != '1' && $par['icredit'] == '1' && $activity['credit'] != '0.00' && $_GPC['status'] == '3') {
				$settings = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors'));
				$behavior = $settings['creditbehaviors'];
				mc_credit_update(mc_openid2uid($row['openid']), $behavior['activity'], $activity['credit'], array(0, $activity['title']));
				mc_group_update(mc_openid2uid($row['openid']));
				$log = $activity['title'] . '-' . $activity['credit'] . '积分';
				mc_notice_credit1($row['openid'], mc_openid2uid($row['openid']), $activity['credit'], $log);
			}
			pdo_update('dayu_form_info', $record, array('rerid' => $rerid));
			message('修改成功', referer(), 'success');
		}
		$row['yuyuetime'] && ($row['yuyuetime'] = date('Y-m-d H:i:s', $row['yuyuetime']));
		load()->func('file');
		load()->func('tpl');
		$title = "表单详情";
		include $this->template('detail');
	}
	public function doWebupfile()
	{
		global $_W, $_GPC;
		$max_file_size = 2000000;
		$destination_folder = "../attachment/dayu_form/" . $_W['uniacid'] . "/file/";
		if (!is_uploaded_file($_FILES["upfile"][tmp_name])) {
			echo 'nothing';
			exit;
		}
		$file = $_FILES["upfile"];
		if ($max_file_size < $file["size"]) {
			echo 'size';
			exit;
		}
		if (!file_exists($destination_folder)) {
			mkdir($destination_folder);
		}
		$filename = $file["tmp_name"];
		$image_size = getimagesize($filename);
		$pinfo = pathinfo($file["name"]);
		$ftype = $pinfo['extension'];
		$destination = $destination_folder . time() . "." . $ftype;
		if (file_exists($destination) && $overwrite != true) {
			echo 'name';
			exit;
		}
		if (!move_uploaded_file($filename, $destination)) {
			echo 'move';
			exit;
		}
		$pinfo = pathinfo($destination);
		$fname = $pinfo[basename];
		echo $destination_folder . $fname;
	}
	public function doWebManage()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$reid = intval($_GPC['id']);
		$activity = pdo_get($this->tb_form, array('weid' => $_W['uniacid'], 'reid' => $reid));
		if (empty($activity)) {
			message('非法访问.');
		}
		$role = $this->get_isrole($reid, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		$par = iunserializer($activity['par']);
		$la = iunserializer($activity['linkage']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY `refid`, displayorder desc';
		$params = array();
		$params[':reid'] = $reid;
		$fields = pdo_fetchall($sql, $params, 'refid');
		if (empty($fields)) {
			message('非法访问.');
		}
		$ds = array();
		foreach ($fields as $f) {
			$ds[$f['refid']] = $f['title'];
		}
		$select = array();
		if (!empty($_GPC['select'])) {
			foreach ($_GPC['select'] as $field) {
				if (isset($ds[$field])) {
					$select[] = $field;
				}
			}
		} elseif (!empty($_GPC['export'])) {
			$select = array_keys($fields);
		}
		$status = $_GPC['status'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
		$where = 'reid = :reid';
		$params = array(':reid' => $reid);
		if (!empty($_GPC['time'])) {
			$where .= " AND createtime >= :starttime AND createtime <= :endtime ";
			$params[':starttime'] = $starttime;
			$params[':endtime'] = $endtime;
		}
		if (!empty($_GPC['keywords'])) {
			$where .= ' and (member like :member or mobile like :mobile)';
			$params[':member'] = "%{$_GPC['keywords']}%";
			$params[':mobile'] = "%{$_GPC['keywords']}%";
		}
		if (!empty($_GPC['kf'])) {
			$where .= " and kf LIKE '%{$_GPC['kf']}%'";
		}
		if ($status != '') {
			$where .= " and status='{$status}'";
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE $where", $params);
		if (!empty($total)) {
			$allTotal = pdo_fetchall("SELECT `reid` FROM " . tablename($this->tb_info) . " WHERE reid = :reid GROUP BY openid", array(':reid' => $reid));
			$where .= ' ORDER BY `createtime` DESC';
			if (empty($_GPC['export'])) {
				$where .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_info) . " WHERE $where", $params, 'rerid');
			load()->model('mc');
			foreach ($list as &$r) {
				$sql = 'SELECT `nickname` FROM ' . tablename('mc_mapping_fans') . ' WHERE `uniacid` = :uniacid AND `openid` = :openid';
				$params = array(':uniacid' => $_W['uniacid'], ':openid' => $r['openid']);
				$r['nickname'] = pdo_fetchcolumn($sql, $params);
				$r['user'] = mc_fansinfo($r['openid']);
				$r['kf'] = mc_fansinfo($r['kf'], $acid, $_W['uniacid']);
				$r['voices'] = strstr($r['voice'], 'http://') ? $r['voice'] : $setting['qiniu']['host'] . '/' . $r['voice'];
				$r['revoices'] = strstr($r['revoice'], 'http://') ? $r['revoice'] : $setting['qiniu']['host'] . '/' . $r['revoice'];
				$r['groupid'] = mc_fetch($r['user']['uid'], array('groupid'));
				$r['state'] = $this->get_status($r['reid'], $r['status']);
				$r['comment'] = !empty($par['comment']) && pdo_tableexists('dayu_comment') && !empty($r['commentid']) ? $this->get_comment($r['commentid']) : '';
			}
			$rerid = array_keys($list);
			$children = array();
			$childlist = pdo_fetchall("SELECT * FROM " . tablename($this->tb_data) . " WHERE rerid IN ('" . implode("','", is_array($rerid) ? $rerid : array($rerid)) . "') AND `reid`=:reid", array(':reid' => $reid));
			foreach ($childlist as $reply => $d) {
				if (!empty($d['rerid'])) {
					$children[$d['rerid']][] = $d;
					unset($children[$reply]);
				}
			}
			$pager = pagination($total, $pindex, $psize);
		}
		if ($select) {
			$fids = implode(',', $select);
			$params = array(':reid' => $reid);
			if (!empty($list)) {
				foreach ($list as &$r) {
					$r['fields'] = array();
					$sql = 'SELECT data, refid FROM ' . tablename($this->tb_data) . " WHERE `reid` = :reid AND `rerid`='{$r['rerid']}' AND `refid` IN ({$fids}) ORDER BY `refid` ASC, displayorder desc";
					$fdatas = pdo_fetchall($sql, $params);
					foreach ($fdatas as $fd) {
						if (false == array_key_exists($fd['refid'], $r['fields'])) {
							$r['fields'][$fd['refid']] = $fd['data'];
						} else {
							$r['fields'][$fd['refid']] .= '-' . $fd['data'];
						}
					}
				}
			}
		}
		if (!empty($_GPC['export'])) {
			$filter = array();
			$tablelength = count($fields) + 1;
			$data = array();
			foreach ($list as $key => $value) {
				$data[$key]['openid'] = $value['openid'];
				$data[$key]['member'] = $value['member'];
				$data[$key]['mobile'] = $value['mobile'];
				if (!empty($value['fields'])) {
					foreach ($value['fields'] as $field) {
						if (strstr($field, 'images') || strstr($field, 'dayu_form')) {
							$data[$key][] = str_replace(array("\n", "\r", "\t"), '', $_W['attachurl'] . $field);
						} else {
							$data[$key][] = str_replace(array("\n", "\r", ",", "\t"), '，', $field);
						}
					}
				}
				$data[$key]['var1'] = $value['var1'];
				$data[$key]['var2'] = $value['var2'];
				$data[$key]['var3'] = $value['var3'];
				$data[$key]['thumb'] = iunserializer($value['thumb']);
				$data[$key]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
				$data[$key]['status'] = $value['status'];
				$data[$key]['kfinfo'] = $value['kfinfo'];
			}
			$html = "\xEF\xBB\xBF";
			$html .= "粉丝编号\t,";
			$html .= $activity['member'] . "\t,";
			$html .= $activity['phone'] . "\t,";
			foreach ($select as $s) {
				foreach ($fields as $field => $key) {
					if ($field == $s) {
						$html .= $key['title'] . "\t ,";
					}
				}
			}
			if (!empty($la)) {
				$html .= $la['l1'] . "\t,";
				$html .= $la['l2'] . "\t,";
			}
			if ($activity['plural']) {
				$html .= $activity['pluraltit'] . "\t,";
			}
			if ($par['var1']) {
				$html .= $par['var1t'] . "\t,";
			}
			if ($par['var2']) {
				$html .= $par['var2t'] . "\t,";
			}
			if ($par['var3']) {
				$html .= $par['var3t'] . "\t,";
			}
			$html .= "状态\t,";
			$html .= "回复\t,";
			$html .= "提交时间\t ,\n";
			if (!empty($list)) {
				foreach ($list as $value) {
					$status = $this->get_status($value['reid'], $value['status']);
					$html .= $value['openid'] . "\t,";
					$html .= $value['member'] . "\t,";
					$html .= $value['mobile'] . "\t,";
					foreach ($value['fields'] as $key => $field) {
						if (strstr($field, 'images') || strstr($field, 'dayu_form')) {
							$html .= str_replace(array("\n", "\r", "\t"), '', $_W['attachurl'] . $field) . "\t,";
						} else {
							$html .= str_replace(array("\n", "\r", ",", "\t"), '，', $field) . "\t,";
						}
					}
					if ($activity['plural']) {
						if (!empty($value['thumb'])) {
							$data = '';
							$value['thumb'] = iunserializer($value['thumb']);
							foreach (iunserializer($value['thumb']) as $pic) {
								$data .= tomedia($pic) . "，";
							}
						} else {
							$data .= "无";
						}
						$html .= $data . "\t,";
					}
					if (!empty($la)) {
						$value['la'] = iunserializer($value['linkage']);
						$value['l1'] = $this->get_linkage($value['la']['l1'], '');
						$value['l2'] = $this->get_linkage($value['la']['l2'], '');
						$html .= $value['l1']['title'] . "\t,";
						$html .= $value['l2']['title'] . "\t,";
					}
					if ($par['var1']) {
						$html .= $value['var1'] . "\t,";
					}
					if ($par['var2']) {
						$html .= $value['var2'] . "\t,";
					}
					if ($par['var3']) {
						$html .= $value['var3'] . "\t,";
					}
					$html .= $status['name'] . "\t,";
					$html .= $value['kfinfo'] . "\t,";
					$html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
					$html .= "\n";
				}
			}
			$stime = date('Ymd', $starttime);
			$etime = date('Ymd', $endtime);
			header("Content-type:text/csv");
			header("Content-Disposition:attachment; filename={$activity['title']}==$stime-$etime.csv");
			echo $html;
			exit;
		}
		if (!empty($list)) {
			foreach ($list as $key => &$value) {
				if (is_array($value['fields'])) {
					foreach ($value['fields'] as &$v) {
						$img = '<img src="';
						if (strstr($field, 'images') || strstr($field, 'dayu_form')) {
							$v = $img . tomedia($v) . '" style="width:50px;height:50px;"/>';
						}
					}
					unset($v);
				}
			}
		}
		$staff = pdo_fetchall("SELECT * FROM " . tablename($this->tb_staff) . " WHERE reid = :reid ORDER BY `id` DESC", array(':reid' => $reid));
		include $this->template('manage');
	}
	public function doWebbatchrecord()
	{
		global $_GPC, $_W;
		require 'fans.web.php';
		$role = $this->get_isrole($reid, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		$reid = intval($_GPC['reid']);
		$reply = pdo_fetch("select reid from " . tablename($this->tb_form) . " where reid = :reid", array(':reid' => $reid));
		if (empty($reply)) {
			message('抱歉，表单主题不存在或是已经被删除！');
		}
		foreach ($_GPC['idArr'] as $k => $rerid) {
			$rerid = intval($rerid);
			pdo_delete('dayu_form_info', array('rerid' => $rerid, 'reid' => $reid));
			pdo_delete('dayu_form_data', array('rerid' => $rerid));
		}
		message('记录删除成功！', '', 0);
	}
	public function doWebupdategroup()
	{
		global $_GPC, $_W;
		if ($_W['isajax']) {
			$groupid = intval($_GPC['groupid']);
			$openid = trim($_GPC['openid']);
			if (!empty($openid)) {
				$acc = WeAccount::create($_W['acid']);
				$data = $acc->updateFansGroupid($openid, $groupid);
				if (is_error($data)) {
					exit(json_encode(array('status' => 'error', 'mess' => $data['message'])));
				} else {
					pdo_update('mc_mapping_fans', array('groupid' => $groupid), array('uniacid' => $_W['uniacid'], 'acid' => $_W['acid'], 'openid' => $openid));
					exit(json_encode(array('status' => 'success')));
				}
			} else {
				exit(json_encode(array('status' => 'error', 'mess' => '粉丝openid错误')));
			}
		}
	}
	public function doWebDisplay()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : '';
		if ($_W['ispost']) {
			$reid = intval($_GPC['reid']);
			$switch = intval($_GPC['switch']);
			$sql = 'UPDATE ' . tablename($this->tb_form) . ' SET `status`=:status WHERE `reid`=:reid';
			$params = array();
			$params[':status'] = $switch;
			$params[':reid'] = $reid;
			pdo_query($sql, $params);
			exit;
		}
		if ($op == 'copy') {
			$id = intval($_GPC['id']);
			$form = pdo_fetch('SELECT * FROM ' . tablename($this->tb_form) . ' WHERE weid = :weid AND reid = :reid', array(':weid' => $_W['uniacid'], ':reid' => $id));
			if (empty($form)) {
				message('表单不存在或已删除', referer(), 'error');
			}
			$form['title'] = $form['title'] . '_' . random(6);
			$form['createtime'] = TIMESTAMP;
			unset($form['reid']);
			pdo_insert($this->tb_form, $form);
			$form_id = pdo_insertid();
			if (!$form_id) {
				message('复制表单出错', '', 'error');
			} else {
				$fields = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_field) . ' WHERE reid = :reid', array(':reid' => $id));
				if (!empty($fields)) {
					foreach ($fields as &$val) {
						unset($val['refid']);
						$val['reid'] = $form_id;
						pdo_insert($this->tb_field, $val);
					}
				}
				message('复制表单成功', $this->createWebUrl('display'), 'success');
			}
		}
		$category = pdo_fetchall("SELECT id,title FROM " . tablename($this->tb_category) . " WHERE weid = :weid ORDER BY `id` DESC", array(':weid' => $_W['uniacid']));
		$role = pdo_fetchall("SELECT * FROM " . tablename($this->tb_role) . " WHERE weid = :weid and roleid = :roleid  ORDER BY id DESC", array(':roleid' => $_W['user']['uid'], ':weid' => $weid), 'reid');
		$roleid = array_keys($role);
		$cateid = intval($_GPC['formid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$where = 'weid = :weid';
		$status = $_GPC['status'];
		if ($cateid) {
			$where .= " and cid=" . intval($cateid);
		}
		if ($status != '') {
			$where .= " and status=" . intval($status);
		}
		if (!empty($_GPC['keyword'])) {
			$where .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		if ($setting['role'] == 1 && $_W['role'] == 'operator') {
			$where .= " AND reid IN ('" . implode("','", is_array($roleid) ? $roleid : array($roleid)) . "')";
		}
		$params = array(':weid' => $weid);
		$ds = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_form) . ' WHERE ' . $where . ' ORDER BY status DESC,reid DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_form) . ' WHERE ' . $where, $params);
		$pager = pagination($total, $pindex, $psize);
		foreach ($ds as &$item) {
			if (!empty($item['var1'])) {
				$var1 = '&' . $item['var1'] . '=变量1';
			}
			if (!empty($item['var2'])) {
				$var2 = '&' . $item['var2'] . '=变量2';
			}
			if (!empty($item['var3'])) {
				$var3 = '&' . $item['var3'] . '=变量3';
			}
			$item['par'] = iunserializer($item['par']);
			$item['isstart'] = $item['starttime'] > 0;
			$item['switch'] = $item['status'];
			$item['cate'] = $this->get_category($item['cid']);
			$item['la'] = $this->get_linkage($item['reid'], 1);
			$item['role'] = $this->get_isrole($item['reid'], $_W['user']['uid']);
			$item['isvar'] = $item['isget'] == 1 ? '<span class="btn btn-success btn-sm">启用</span>' : '<span class="btn btn-default btn-sm">关闭</span>';
			$item['link'] = $item['isget'] == 1 ? murl('entry', array('do' => 'dayu_form', 'id' => $item['reid'], 'm' => 'dayu_form'), true, true) . $var1 . $var2 . $var3 : murl('entry', array('do' => 'dayu_form', 'id' => $item['reid'], 'm' => 'dayu_form'), true, true);
			$item['record'] = $item['isget'] == 1 ? murl('entry', array('do' => 'record', 'id' => $item['reid'], 'm' => 'dayu_form'), true, true) . $var1 . $var2 . $var3 : murl('entry', array('do' => 'record', 'id' => $item['reid'], 'm' => 'dayu_form'), true, true);
			$item['mylink'] = murl('entry', array('do' => 'Mydayu_form', 'id' => $item['reid'], 'weid' => $item[weid], 'm' => 'dayu_form'), true, true);
		}
		$title = "表单列表";
		include $this->template('display');
	}
	public function doWebDelete()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$reid = intval($_GPC['id']);
		$role = $this->get_isrole($reid, $_W['user']['uid']);
		if ($_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		if ($reid > 0) {
			$params = array();
			$params[':reid'] = $reid;
			$sql = 'DELETE FROM ' . tablename($this->tb_form) . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			$sql = 'DELETE FROM ' . tablename($this->tb_info) . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			$sql = 'DELETE FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			$sql = 'DELETE FROM ' . tablename($this->tb_data) . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			$sql = 'DELETE FROM ' . tablename($this->tb_staff) . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			message('操作成功.', referer());
		}
		message('非法访问.');
	}
	public function doWebdayu_formDelete()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$id = intval($_GPC['id']);
		$role = $this->get_isrole($id, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		if (!empty($id)) {
			pdo_delete('dayu_form_info', array('rerid' => $id));
			pdo_delete('dayu_form_data', array('rerid' => $id));
		}
		message('操作成功.', referer());
	}
	public function doMobiledayu_formDelete()
	{
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$openid = intval($_GPC['openid']);
		$reid = intval($_GPC['reid']);
		$form = pdo_fetch("SELECT rerid, openid FROM " . tablename($this->tb_info) . " WHERE rerid = '$id'");
		if (!empty($id) && $openid == $form['openid']) {
			pdo_delete('dayu_form_info', array('rerid' => $id));
			pdo_delete('dayu_form_data', array('rerid' => $id));
			$this->showMessage('删除成功.', $this->createMobileUrl('mydayu_form', array('weid' => $_W['uniacid'], 'id' => $reid)));
		} else {
			$this->showMessage('删除失败，原因：该记录不在您的名下.', $this->createMobileUrl('mydayu_form', array('weid' => $_W['uniacid'], 'id' => $reid)));
		}
	}
	public function doWebPost()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'post';
		$modules = uni_modules(false);
		if ($op == 'post') {
			$reid = intval($_GPC['id']);
			$hasData = false;
			if ($reid) {
				$sql = 'SELECT COUNT(*) FROM ' . tablename($this->tb_info) . ' WHERE `reid`=' . $reid;
				if (pdo_fetchcolumn($sql) > 0) {
					$hasData = true;
				}
			}
			load()->model('mc');
			$settings = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors', 'uc', 'payment', 'passport'));
			$behavior = $settings['creditbehaviors'];
			$creditnames = $settings['creditnames'];
			$groups = mc_groups();
			$role = pdo_fetchall('SELECT roleid FROM ' . tablename($this->tb_role) . " WHERE weid = '{$_W['uniacid']}' AND reid = '{$reid}'");
			if (!empty($role)) {
				foreach ($role as $r) {
					$rolearr[] = $r['roleid'];
				}
			}
			$permission = pdo_fetchall("SELECT id, uid, role FROM " . tablename('uni_account_users') . " WHERE uniacid = :weid and role != :role  ORDER BY uid ASC, role DESC", array(':role' => 'clerk', ':weid' => $weid));
			if (!empty($permission)) {
				foreach ($permission as &$p) {
					$p['user'] = $this->get_role($p['uid']);
					if (!empty($role) && in_array($p['uid'], $rolearr)) {
						$p['select'] = 1;
					}
				}
			}
			if (checksubmit()) {
				$data = array('edit' => intval($_GPC['edit']), 'isdel' => intval($_GPC['isdel']), 'follow' => intval($_GPC['follow']), 'replace' => intval($_GPC['replace']), 'card' => intval($_GPC['card']), 'pretotal' => intval($_GPC['pretotal']), 'daynum' => intval($_GPC['daynum']), 'allnum' => intval($_GPC['allnum']), 'header' => intval($_GPC['header']), 'state1' => trim($_GPC['state1']), 'state2' => trim($_GPC['state2']), 'state3' => trim($_GPC['state3']), 'state4' => trim($_GPC['state4']), 'state5' => trim($_GPC['state5']), 'state8' => trim($_GPC['state8']), 'var1t' => trim($_GPC['var1t']), 'var1' => trim($_GPC['var1']), 'var2t' => trim($_GPC['var2t']), 'var2' => trim($_GPC['var2']), 'var3t' => trim($_GPC['var3t']), 'var3' => trim($_GPC['var3']), 'title' => trim($_GPC['titles']), 'mname' => trim($_GPC['mname']), 'submitname' => trim($_GPC['submitname']), 'business' => trim($_GPC['business']), 'address' => trim($_GPC['address']), 'tel' => trim($_GPC['tel']), 'lat' => $_GPC['baidumap']['lat'], 'lng' => $_GPC['baidumap']['lng'], 'noticeurl' => trim($_GPC['noticeurl']), 'kami' => intval($_GPC['kami']), 'sendkami' => intval($_GPC['sendkami']), 'comment' => intval($_GPC['comment']), 'icredit' => intval($_GPC['icredit']), 'onlytit' => trim($_GPC['onlytit']), 'sms' => $_GPC['sms'], 'subtitle' => trim($_GPC['subtitle']), 'icon' => $_GPC['icon']);
				$record = array();
				$record['list'] = intval($_GPC['list']);
				$record['title'] = trim($_GPC['activity']);
				$record['weid'] = $_W['uniacid'];
				$record['description'] = trim($_GPC['description']);
				$record['content'] = trim($_GPC['content']);
				$record['information'] = trim($_GPC['information']);
				if (!empty($_GPC['thumb'])) {
					$record['thumb'] = $_GPC['thumb'];
					load()->func('file');
					file_delete($_GPC['thumb-old']);
				}
				$record['status'] = intval($_GPC['status']);
				$record['custom_status'] = intval($_GPC['custom_status']);
				$record['inhome'] = intval($_GPC['inhome']);
				$record['member'] = trim($_GPC['member']);
				$record['phone'] = trim($_GPC['phone']);
				$record['starttime'] = strtotime($_GPC['starttime']);
				$record['endtime'] = strtotime($_GPC['endtime']);
				$record['noticeemail'] = trim($_GPC['noticeemail']);
				$record['k_templateid'] = trim($_GPC['k_templateid']);
				$record['kfirst'] = trim($_GPC['kfirst']);
				$record['kfoot'] = trim($_GPC['kfoot']);
				$record['mfirst'] = trim($_GPC['mfirst']);
				$record['mfoot'] = trim($_GPC['mfoot']);
				$record['m_templateid'] = trim($_GPC['m_templateid']);
				$record['mobile'] = trim($_GPC['mobile']);
				$record['skins'] = trim($_GPC['skins']);
				$record['outlink'] = trim($_GPC['outlink']);
				$record['mbgroup'] = $_GPC['mbgroup'];
				$record['isinfo'] = intval($_GPC['isinfo']);
				$record['isrethumb'] = intval($_GPC['isrethumb']);
				$record['isvoice'] = intval($_GPC['isvoice']);
				$record['isrevoice'] = intval($_GPC['isrevoice']);
				$record['voice'] = trim($_GPC['voice']);
				$record['voicedec'] = trim($_GPC['voicedec']);
				$record['ivoice'] = intval($_GPC['ivoice']);
				$record['isloc'] = intval($_GPC['isloc']);
				$record['isget'] = intval($_GPC['isget']);
				$record['credit'] = $_GPC['credit'];
				$record['smsid'] = $_GPC['smsid'];
				$record['smsnotice'] = $_GPC['smsnotice'];
				$record['smstype'] = $_GPC['smstype'];
				$record['agreement'] = trim($_GPC['agreement']);
				$record['paixu'] = intval($_GPC['paixu']);
				$record['pluraltit'] = trim($_GPC['pluraltit']);
				$record['plural'] = intval($_GPC['plural']);
				$record['cid'] = intval($_GPC['cate']);
				$record['par'] = iserializer($data);
				if (empty($reid)) {
					$record['status'] = 1;
					$record['createtime'] = TIMESTAMP;
					pdo_insert('dayu_form', $record);
					$reid = pdo_insertid();
					if (!$reid) {
						message('保存表单失败1, 请稍后重试.');
					}
				} else {
					if (pdo_update('dayu_form', $record, array('reid' => $reid)) === false) {
						message('保存表单失败2, 请稍后重试.');
					}
				}
				if ($setting['role'] == 1 && ($_W['role'] == 'founder' || $_W['role'] == 'manager')) {
					pdo_delete($this->tb_role, array('weid' => $_W['uniacid'], 'reid' => $reid));
					if ($_GPC['role'] && $reid) {
						foreach ($_GPC['role'] as $rid) {
							$rid = intval($rid);
							$insert = array('weid' => $_W['uniacid'], 'reid' => $reid, 'roleid' => $rid);
							pdo_insert($this->tb_role, $insert) ? '' : message('抱歉，更新失败！', referer(), 'error');
							unset($insert);
						}
					}
				}
				if (!$hasData) {
					$sql = 'DELETE FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid';
					$params = array();
					$params[':reid'] = $reid;
					pdo_query($sql, $params);
					foreach ($_GPC['title'] as $k => $v) {
						$field = array();
						$field['reid'] = $reid;
						$field['title'] = trim($v);
						$field['displayorder'] = range_limit($_GPC['displayorder'][$k], 0, 254);
						$field['type'] = $_GPC['type'][$k];
						$field['essential'] = $_GPC['essentialvalue'][$k] == 'true' ? 1 : 0;
						$field['only'] = $_GPC['only'][$k];
						$field['bind'] = $_GPC['bind'][$k];
						$field['value'] = $_GPC['value'][$k];
						$field['value'] = urldecode($field['value']);
						$field['description'] = $_GPC['desc'][$k];
						$field['image'] = $_GPC['image'][$k];
						$field['loc'] = $_GPC['loc'][$k];
						pdo_insert('dayu_form_fields', $field);
					}
				}
				message('保存成功.', 'refresh');
			}
			$types = array();
			$types['number'] = '数字(number)';
			$types['text'] = '字符串(text)';
			$types['textarea'] = '文本(textarea)';
			$types['radio'] = '单选(radio)';
			$types['checkbox'] = '多选(checkbox)';
			$types['select'] = '下拉框(select)';
			$types['calendar'] = '日历(calendar)';
			$types['email'] = '电子邮件(email)';
			$types['image'] = '上传图片(image)';
			$types['range'] = '日期时间(range)';
			$types['reside'] = '省市区(reside)';
			pdo_tableexists('dayu_photograph_fields') && ($types['photograph'] = '证件照(photo)');
			$fields = mc_fields();
			if ($reid) {
				$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
				$params = array();
				$params[':weid'] = $_W['uniacid'];
				$params[':reid'] = $reid;
				$activity = pdo_fetch($sql, $params);
				$activity['starttime'] && ($activity['starttime'] = date('Y-m-d H:i:s', $activity['starttime']));
				$activity['endtime'] && ($activity['endtime'] = date('Y-m-d H:i:s', $activity['endtime']));
				$par = iunserializer($activity['par']);
				$par['map'] = array('lat' => $par['lat'], 'lng' => $par['lng']);
				if ($activity) {
					$sql = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY `displayorder` DESC,`refid`';
					$params = array();
					$params[':reid'] = $reid;
					$ds = pdo_fetchall($sql, $params);
				}
				$var1 = !empty($par['var1']) ? '&' . $par['var1'] . '=自定义变量1' : '';
				$var2 = !empty($par['var2']) ? '&' . $par['var2'] . '=自定义变量2' : '';
				$var3 = !empty($par['var3']) ? '&' . $par['var3'] . '=自定义变量3' : '';
				$links = murl('entry', array('do' => 'dayu_form', 'id' => $reid, 'm' => 'dayu_form'), true, true) . $var1 . $var2 . $var3;
			} else {
				$links = "保存表单后生成链接";
			}
			$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
			$params = array();
			$params[':weid'] = $_W['uniacid'];
			$params[':reid'] = $reid;
			$reply = pdo_fetch($sql, $params);
			if (!$reply) {
				$par = array("state1" => "待受理", "state2" => "受理中", "state3" => "已完成", "state4" => "拒绝受理", "state5" => "已取消", "state8" => "退回修改", "mname" => "往期记录", "submitname" => "立 即 提 交", "header" => "1");
				$activity = array("kfirst" => "有客户提交新的表单，请及时跟进", "kfoot" => "点击处理客户提交的表单。", "mfirst" => "受理结果通知", "mfoot" => "如有疑问，请致电联系我们。", "information" => "您提交申请我们已经收到, 请等待客服跟进.", "adds" => "联系地址", "voice" => "录音", "voicedec" => "录音说明", "pluraltit" => "上传图片", "status" => 1, "credit" => 0, "member" => "姓名", "phone" => "手机", "endtime" => date('Y-m-d H:i:s', strtotime('+365 day')));
			}
		} elseif ($op == 'verify') {
			if ($_W['isajax']) {
				$openid = trim($_GPC['openid']);
				$nickname = trim($_GPC['nickname']);
				if (!empty($openid)) {
					$sql = 'SELECT openid,nickname FROM ' . tablename('mc_mapping_fans') . " WHERE acid =:acid AND openid = :openid";
					$exist = pdo_fetch($sql, array(':openid' => $openid, ':acid' => $_W['acid']));
				} else {
					$sql = 'SELECT openid,nickname FROM ' . tablename('mc_mapping_fans') . " WHERE acid =:acid AND nickname = :nickname";
					$exist = pdo_fetch($sql, array(':nickname' => $nickname, ':acid' => $_W['acid']));
				}
				if (empty($exist)) {
					message(error(-1, '未找到对应的粉丝编号，请检查昵称或openid是否有效'), '', 'ajax');
				}
				message(error(0, $exist), '', 'ajax');
			}
		}
		if (pdo_tableexists('dayu_sms')) {
			$sms = pdo_fetchall("SELECT * FROM " . tablename('dayu_sms') . " WHERE weid = :weid", array(':weid' => $_W['uniacid']));
		}
		if (pdo_tableexists('dayu_kami_category')) {
			$kami = pdo_fetchall("SELECT * FROM " . tablename('dayu_kami_category') . " WHERE weid = :weid", array(':weid' => $_W['uniacid']));
		}
		if (pdo_tableexists('dayu_sendkami_category')) {
			$sendkami = pdo_fetchall("SELECT * FROM " . tablename('dayu_sendkami_category') . " WHERE weid = :weid", array(':weid' => $_W['uniacid']));
		}
		if (pdo_tableexists('dayu_comment_category')) {
			$comment = pdo_fetchall("SELECT * FROM " . tablename('dayu_comment_category') . " WHERE weid = :weid", array(':weid' => $_W['uniacid']));
		}
		if (pdo_tableexists('dayu_form_skins')) {
			$skins = pdo_fetchall("SELECT * FROM " . tablename('dayu_form_skins') . " WHERE status = 1 ORDER BY id", array());
			if (!empty($skins)) {
				foreach ($skins as &$s) {
					$s['weid'] = !empty($s['ids']) ? explode(',', $s['ids']) : '';
				}
			}
		}
		$category = pdo_fetchall("select * from " . tablename($this->tb_category) . " where weid = :weid ORDER BY id DESC", array(':weid' => $weid));
		$title = !empty($_GPC['id']) ? "编辑表单" : "新建表单";
		include $this->template('post');
	}
	public function doWebBatchrRcord()
	{
		global $_GPC, $_W;
		require 'fans.web.php';
		$reid = intval($_GPC['reid']);
		$role = $this->get_isrole($reid, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		$reply = pdo_fetch("select reid from " . tablename($this->tb_form) . " where reid = :reid", array(':reid' => $reid));
		if (empty($reply)) {
			$result['status'] = 0;
			$result['msg'] = '抱歉，表单主题不存在或是已经被删除！';
		}
		foreach ($_GPC['idArr'] as $k => $rerid) {
			pdo_delete($this->tb_info, array('rerid' => $rerid, 'reid' => $reid));
			pdo_delete($this->tb_data, array('rerid' => $rerid, 'reid' => $reid));
		}
		$result['status'] = 1;
		$result['msg'] = '记录批量删除成功！';
		message($result, '', 'ajax');
	}
	public function doWebRecordSet()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$reid = intval($_GPC['reid']);
		$role = $this->get_isrole($reid, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		$record = iunserializer($activity['fields']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY `displayorder` DESC,`refid`';
		$params = array();
		$params[':reid'] = $reid;
		$ds = pdo_fetchall($sql, $params);
		if (checksubmit()) {
			$record = array();
			$record['field'] = intval($_GPC['field']);
			$record['avatar'] = intval($_GPC['avatar']);
			$record['bcolor'] = $_GPC['bcolor'];
			if (!empty($_GPC['fields'])) {
				foreach ($_GPC['fields'] as $fields) {
					$th[] = $fields;
				}
				$record['fields'] = iserializer($th);
			}
			if (pdo_update('dayu_form', $record, array('reid' => $reid)) === false) {
				message('保存表单失败, 请稍后重试.');
			}
			message('保存成功.', 'refresh');
		}
		include $this->template('recordset');
	}
	public function doWebCustom()
	{
		global $_W, $_GPC;
		require 'fans.web.php';
		$id = $_GPC['id'];
		$role = $this->get_isrole($id, $_W['user']['uid']);
		if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role) {
			message('您没有权限进行该操作.');
		}
		load()->func('tpl');
		$op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update($this->tb_custom, array('displayorder' => $displayorder), array('id' => $id));
				}
				message('快捷回复内容排序更新成功！', $this->createWebUrl('custom', array('op' => 'display')), 'success');
			}
			$custom = pdo_fetchall("SELECT * FROM " . tablename($this->tb_custom) . " WHERE weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid));
			include $this->template('custom');
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$custom = pdo_fetch("SELECT * FROM " . tablename($this->tb_custom) . " WHERE id = '$id'");
			} else {
				$city = array('displayorder' => 0);
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['raply'])) {
					message('抱歉，请输入快捷回复内容！');
				}
				$data = array('weid' => $weid, 'displayorder' => intval($_GPC['displayorder']), 'raply' => $_GPC['raply']);
				if (!empty($id)) {
					pdo_update($this->tb_custom, $data, array('id' => $id));
				} else {
					pdo_insert($this->tb_custom, $data);
					$id = pdo_insertid();
				}
				message('更新快捷回复内容成功！', $this->createWebUrl('custom', array('op' => 'display')), 'success');
			}
			include $this->template('custom');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$custom = pdo_fetch("SELECT * FROM " . tablename($this->tb_custom) . " WHERE id = '$id'");
			if (empty($custom)) {
				message('抱歉，快捷回复内容不存在或是已经被删除！', $this->createWebUrl('custom', array('op' => 'display')), 'error');
			}
			pdo_delete($this->tb_custom, array('id' => $id));
			message('快捷回复内容删除成功！', $this->createWebUrl('custom', array('op' => 'display')), 'success');
		}
	}
	public function doMobilerecord()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		if (!$activity) {
			$this->showMessage('非法访问，主题不存在', '', 'error');
		}
		$record = iunserializer($activity['fields']);
		$fids = implode(',', $record);
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_form) . " WHERE weid = :weid and kfid = :openid and status = 1 ORDER BY reid DESC", array(':weid' => $weid, ':openid' => $openid), 'reid');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$where .= " and status=3";
		$sql = 'SELECT * FROM ' . tablename($this->tb_info) . " WHERE reid=:reid $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$params = array();
		$params[':reid'] = $reid;
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid $where ", $params);
		$pager = $this->pagination($total, $pindex, $psize);
		$rows = pdo_fetchall($sql, $params, 'rerid');
		foreach ($rows as $index => $row) {
			$rows[$index]['user'] = mc_fansinfo($row['openid'], $acid, $weid);
			$rows[$index]['thumb'] = iunserializer($row['thumb']);
			$rows[$index]['thumbs'] = iunserializer($row['thumb']);
			if (is_array($rows[$index]['thumbs'])) {
				foreach ($rows[$index]['thumbs'] as $p) {
					$piclist .= is_array($p) ? $p['attachment'] : tomedia($p) . ',';
				}
			}
			$maps = $piclist;
		}
		$rerid = array_keys($rows);
		$children = array();
		$childlist = pdo_fetchall("SELECT * FROM " . tablename($this->tb_data) . " WHERE rerid IN ('" . implode("','", is_array($rerid) ? $rerid : array($rerid)) . "') AND `reid`=:reid AND `refid` IN ({$fids})", array(':reid' => $reid));
		foreach ($childlist as $reply => $r) {
			if (!empty($r['rerid'])) {
				$children[$r['rerid']][] = $r;
				unset($children[$reply]);
			}
		}
		$title = $activity['title'];
		$state = !empty($activity['state3']) ? $activity['state3'] : '已完成';
		include $this->template('record');
	}
	public function doMobileCheckOnly()
	{
		global $_W, $_GPC;
		$activity = pdo_get($this->tb_form, array('weid' => $_W['uniacid'], 'reid' => $_GPC['reid']), array('par'));
		$par = iunserializer($activity['par']);
		$msg = !empty($par['onlytit']) ? $par['onlytit'] : '存在相同内容，请重新填写';
		$data = pdo_fetch("SELECT * FROM " . tablename($this->tb_data) . " WHERE reid = :reid AND refid = :refid and (status='0' or status='1' or status='3')", array(':reid' => $_GPC['reid'], ':refid' => $_GPC['refid']));
		if ($_GPC['content'] == $data['data']) {
			$result['status'] = 0;
			$result['msg'] = $_GPC['title'] . $msg;
		} else {
			$result['status'] = 1;
			$result['msg'] = '可使用';
		}
		message($result, '', 'ajax');
	}
	public function doMobileEdit()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$returnUrl = urlencode($_W['siteurl']);
		$reid = intval($_GPC['reid']);
		$rerid = intval($_GPC['rerid']);
		$activity = pdo_get($this->tb_form, array('weid' => $weid, 'reid' => $reid), array());
		$par = iunserializer($activity['par']);
		$submitname = !empty($par['submitname']) ? $par['submitname'] : '立 即 提 交';
		$info = pdo_get($this->tb_info, array('rerid' => $rerid), array());
		if ($info['openid'] != $openid) {
			$this->showMessage('记录不存在或是已经被删除！');
			exit;
		}
		$binds = array();
		$profile = mc_fetch($uid, $binds);
		$field = pdo_getall($this->tb_field, array('reid' => $reid), array(), '', 'displayorder DESC,refid DESC', '');
		if (empty($field)) {
			$this->showMessage('非法访问.');
		}
		if ($par['edit'] != '1' && $info['status'] != '8') {
			$this->showMessage('不能修改内容', 'error');
		}
		$ds = $fids = array();
		foreach ($field as $f) {
			if ($f['type'] == 'reside') {
				$reside = $f;
			}
			if ($profile[$f['bind']]) {
				$f['default'] = $profile[$f['bind']];
			}
			if (in_array($f['type'], array('text', 'number', 'email'))) {
				$ds[$f['refid']]['tixing'] = !empty($f['description']) ? urldecode($f['description']) : "请输入" . $f['title'];
			} elseif ($f['type'] == 'textarea') {
				$ds[$f['refid']]['tixing'] = !empty($f['description']) ? urldecode($f['description']) : "请填写" . $f['title'];
			} else {
				$ds[$f['refid']]['tixing'] = !empty($f['description']) ? urldecode($f['description']) : "请选择" . $f['title'];
			}
			if (!empty($f['loc']) && pdo_tableexists('dayu_form_plugin_radio') && ($f['type'] == 'radio' || $f['type'] == 'checkbox')) {
				$ds[$f['refid']]['dayu_radio'] = pdo_fetchall("SELECT * FROM " . tablename('dayu_form_plugin_radio') . " WHERE weid = :weid and cid = :cid and status=1 ORDER BY id DESC", array(':weid' => $weid, ':cid' => $f['loc']));
			}
			if ($f['type'] == 'image') {
				$ds[$f['refid']]['image'] = !empty($f['image']) ? $f['image'] : TEMPLATE_WEUI . "images/nopic.jpg";
			}
			$ds[$f['refid']]['options'] = explode(',', $f['value']);
			$ds[$f['refid']]['fid'] = $f['title'];
			$ds[$f['refid']]['type'] = $f['type'];
			$ds[$f['refid']]['refid'] = $f['refid'];
			$ds[$f['refid']]['essential'] = $f['essential'];
			$ds[$f['refid']]['photograph_url'] = $f['photograph_url'];
			$ds[$f['refid']]['default'] = $f['default'];
			$ds[$f['refid']]['loc'] = $f['loc'];
			$fids[] = $f['refid'];
		}
		$fids = implode(',', $fids);
		$info['fields'] = $info['redid'] = array();
		$sql = 'SELECT * FROM ' . tablename($this->tb_data) . " WHERE `reid`='{$reid}' AND `rerid`='{$rerid}' AND `refid` IN ({$fids})";
		$fdatas = pdo_fetchall($sql, $params);
		foreach ($fdatas as $fd) {
			if (strstr($fd['data'], 'images')) {
				$info['fields'][$fd['refid']] = tomedia($fd['data']);
			} else {
				$info['fields'][$fd['refid']] = $fd['data'];
			}
			$info['redid'][$fd['refid']] = $fd['redid'];
		}
		if ($_W['ispost'] || checksubmit('submit')) {
			$datas = array();
			foreach ($_POST as $key => $value) {
				$entry = array();
				$entry['data'] = strval($value);
				$datas[] = array('content' => $entry, 'refid' => $key);
				pdo_update($this->tb_data, $entry, array('redid' => $key));
			}
			pdo_update($this->tb_info, array('status' => '0'), array('rerid' => $rerid));
			if (empty($datas)) {
				$this->showMessage('非法访问，提交数据不能为空', '', 'error');
				exit;
			}
			if (!empty($datas)) {
				foreach ($datas as $row) {
					if (strstr($row['content']['data'], 'images')) {
						$row['data'] = "有";
					}
					$field = pdo_get($this->tb_data, array('redid' => $row['refid']), array());
					$row['fid'] = $this->get_fields($field['refid']);
					$bodym .= '\\n　' . $row['content']['data'];
					$body .= '<h4>' . $row['content']['data'] . '</h4>';
					$smsbody .= $row['content']['data'] . '，';
					$bodnew .= !empty($row['content']['data']) ? '\\n' . $row['fid']['title'] . '：' . $row['content']['data'] : '';
				}
				if (!empty($activity['noticeemail'])) {
					load()->func('communication');
					ihttp_email($activity['noticeemail'], $activity['title'] . '的表单提醒', '<h4>姓名：' . $info['member'] . '</h4><h4>手机：' . $info['mobile'] . '</h4>' . $body);
				}
				$ytime = date('Y-m-d H:i:s', TIMESTAMP);
				$status = $this->get_status($reid, '0');
				$staff = pdo_fetchall("SELECT `openid` FROM " . tablename($this->tb_staff) . " WHERE reid=:reid AND weid=:weid", array(':weid' => $_W['uniacid'], ':reid' => $reid));
				if ($activity['custom_status'] == 0 && $staff) {
					if (is_array($staff)) {
						foreach ($staff as $s) {
							$template = array("touser" => $s['openid'], "template_id" => $activity['k_templateid'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageform', array('name' => 'dayu_form', 'id' => $reid)), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($activity['kfirst'] . "\\n"), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($info['member']), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($info['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($ytime), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($status['name'] . '\\n' . $bodnew . "\\n"), 'color' => "#FF0000"), 'remark' => array('value' => urlencode($activity['kfoot']), 'color' => "#008000")));
							$this->send_template_message(urldecode(json_encode($template)));
						}
					}
				} else {
					if (is_array($staff)) {
						foreach ($staff as $s) {
							$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageform', array('name' => 'dayu_form', 'id' => $reid));
							$info = "【您好，有新的消息】\n\n";
							$info .= "姓名：{$_GPC['member']}\n手机：{$_GPC['mobile']}\n内容：{$bodym}\n\n";
							$info .= "<a href='{$url}'>点击查看详情</a>";
							$custom = array('msgtype' => 'text', 'text' => array('content' => urlencode($info)), 'touser' => $s['openid']);
							$acc = WeAccount::create($_W['acid']);
							$CustomNotice = $acc->sendCustomNotice($custom);
						}
					}
				}
			}
			$outlink = !empty($activity['outlink']) ? $activity['outlink'] : $this->createMobileUrl('mydayu_form', array('op' => 'detail', 'rerid' => $rerid, 'id' => $reid));
			$this->showMessage($activity['information'], $outlink);
		}
		load()->func('tpl');
		$title = $par['header'] == 1 ? $activity['title'] : $activity['titles'];
		$_share['title'] = $activity['title'];
		$_share['content'] = $activity['description'];
		$_share['imgUrl'] = tomedia($activity['thumb']);
		include $this->template('edit');
	}
	public function doMobiledayu_form()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$returnUrl = urlencode($_W['siteurl']);
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $weid;
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		$par = iunserializer($activity['par']);
		$la = iunserializer($activity['linkage']);
		if ($par['follow'] == 1) {
			$this->getFollow();
		} else {
			$member = !empty($member) ? $member : $_SESSION['userinfo'];
			if (empty($member['avatar'])) {
				if ($_W['account']['level'] > 3) {
					$member = mc_oauth_userinfo($_W['acid']);
				} else {
					$member = mc_oauth_fans($openid, $_W['acid']);
				}
			}
		}
		$set = $this->module['config'];
		$qqkey = $set['qqkey'];
		$repeat = $_COOKIE['r_submit'];
		if (!empty($_GPC['repeat'])) {
			if (!empty($repeat)) {
				if ($repeat == $_GPC['repeat']) {
					$this->showMessage($activity['information'], $this->createMobileUrl('mydayu_form', array('id' => $reid)));
				} else {
					setcookie("r_submit", $_GPC['repeat']);
				}
			} else {
				setcookie("r_submit", $_GPC['repeat']);
			}
		}
		if ($activity['status'] == 0) {
			$this->showMessage('活动已经停止.');
		}
		if (!$activity) {
			$this->showMessage('非法访问，主题不存在');
		}
		if ($activity['starttime'] > TIMESTAMP) {
			$this->showMessage('活动还未开始！<br>开始时间：' . date('Y-m-d H:i:s', $activity['starttime']));
		}
		if ($activity['endtime'] < TIMESTAMP) {
			$this->showMessage('活动已经结束！<br>截至时间：' . date('Y-m-d H:i:s', $activity['endtime']));
		}
		$acc = notice_init();
		if (is_error($acc)) {
			return error(-1, $acc['message']);
		}
		$settings = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors', 'uc', 'payment', 'passport'));
		$behavior = $settings['creditbehaviors'];
		$creditnames = $settings['creditnames'];
		$credits = mc_credit_fetch($_W['member']['uid'], '*');
		$ycredit = $credits[$behavior['activity']] + $activity['credit'];
		$activity['thumb'] = tomedia($activity['thumb']);
		$time = date('Y-m-d', time());
		$yuyuetime = date('Y-m-d H:i', time() + 3600);
		$group = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE uniacid = '{$weid}' AND uid = '{$uid}'");
		$groupid = $group['groupid'];
		if ($activity['mbgroup'] != 0) {
			if ($groupid != $activity['mbgroup']) {
				$this->showMessage('您所在会员组没有相关的操作权限！', '', 'info');
			}
		}
		$sql = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid` = :reid ORDER BY `displayorder` DESC';
		$params = array();
		$params[':reid'] = $reid;
		$ds = pdo_fetchall($sql, $params);
		if (!$ds) {
			$this->showMessage('表单不完整，缺少自定义项目，无法正常访问.');
		}
		if ($par['card'] == 1 || $par['card'] == 2) {
			$ishy = $this->isHy($openid);
			$to_card = $par['card'] == 1 ? $this->createMobileUrl('mycard', array('a' => 'card', 'c' => 'mc', 'i' => $weid, 'returnurl' => $returnUrl), false) : murl('entry', array('do' => 'index', 'm' => 'dayu_card', 'returnurl' => $returnUrl), true, true);
			if ($ishy == false) {
				$this->showMessage('您还不是会员,请先领取您的会员卡.', $to_card, 'info');
			}
		}
		if (intval($par['allnum']) != 0) {
			$allnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid and (status=0 or status=1 or status=3)", array(':reid' => $reid));
			if ($allnum >= intval($par['allnum'])) {
				$this->showMessage('名额已满', '', 'info');
			}
		}
		if (intval($par['pretotal']) != 0) {
			$pretotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid AND openid = :openid", array(':reid' => $reid, ':openid' => $openid));
			if ($pretotal >= intval($par['pretotal'])) {
				$this->showMessage('抱歉,每人只能提交' . intval($par['pretotal']) . "次！", '', 'info');
			}
		}
		if (intval($par['daynum']) != 0) {
			$today = strtotime('today');
			$tomorrow = strtotime('tomorrow');
			$lognum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE openid = '{$openid}' AND reid = '{$reid}' AND createtime > " . $today . ' AND createtime < ' . $tomorrow);
			if ($lognum >= intval($par['daynum'])) {
				$this->showMessage('抱歉,每天只能提交' . intval($par['daynum']) . "次！", $this->createMobileUrl('mydayu_form', array('name' => 'dayu_form', 'weid' => $weid, 'id' => $reid)), 'info');
			}
		}
		$activity['smsid'] && empty($member['mobile']) && $this->showMessage('请完善手机号', murl('entry', array('do' => 'index', 'id' => $activity['smsid'], 'm' => 'dayu_sms', 'form' => $_W['current_module']['name'], 'returnurl' => $returnUrl), true, true), 'info');
		$mname = !empty($par['mname']) ? $par['mname'] : "往期记录";
		$submitname = !empty($par['submitname']) ? $par['submitname'] : '立 即 提 交';
		$linkage = pdo_fetchall("SELECT * FROM " . tablename($this->tb_linkage) . " WHERE reid = :reid and parentid = 0 ORDER BY displayorder desc, id DESC", array(':reid' => $reid));
		$initRange = $initCalendar = false;
		$binds = array();
		$profile = mc_fetch($uid, $binds);
		foreach ($binds as $key => $value) {
			if ($value == 'reside') {
				unset($binds[$key]);
				$binds[] = 'resideprovince';
				$binds[] = 'residecity';
				$binds[] = 'residedist';
				break;
			}
			if ($value == 'birth') {
				unset($binds[$key]);
				$binds[] = 'birthyear';
				$binds[] = 'birthmonth';
				$binds[] = 'birthday';
				break;
			}
		}
		foreach ($ds as &$r) {
			if ($r['type'] == 'range') {
				$initRange = true;
			}
			if ($r['type'] == 'calendar') {
				$initCalendar = true;
			}
			if ($r['value']) {
				$r['options'] = explode(',', $r['value']);
			}
			if ($r['bind']) {
				$binds[$r['type']] = $r['bind'];
			}
			if ($r['type'] == 'reside') {
				$reside = $r;
			}
			if ($r['type'] == 'text' && $r['loc'] > 0) {
				$isloc = $r;
			}
			if ($r['type'] == 'image') {
				$r['image'] = !empty($r['image']) ? $r['image'] : TEMPLATE_WEUI . "images/nopic.jpg";
			}
			if (!empty($r['loc']) && pdo_tableexists('dayu_form_plugin_radio') && ($r['type'] == 'radio' || $r['type'] == 'checkbox')) {
				$r['dayu_radio'] = pdo_fetchall("SELECT * FROM " . tablename('dayu_form_plugin_radio') . " WHERE weid = :weid and cid = :cid and status=1 ORDER BY id DESC", array(':weid' => $weid, ':cid' => $r['loc']));
			}
			if (in_array($r['type'], array('text', 'number', 'email'))) {
				$r['tixing'] = !empty($r['description']) ? urldecode($r['description']) : "请输入" . $r['title'];
			} elseif ($r['type'] == 'textarea') {
				$r['tixing'] = !empty($r['description']) ? urldecode($r['description']) : "请填写" . $r['title'];
			} else {
				$r['tixing'] = !empty($r['description']) ? urldecode($r['description']) : "请选择" . $r['title'];
			}
			if ($profile['gender']) {
				if ($profile['gender'] == '0') {
					$profile['gender'] = '保密';
				}
				if ($profile['gender'] == '1') {
					$profile['gender'] = '男';
				}
				if ($profile['gender'] == '2') {
					$profile['gender'] = '女';
				}
			}
			if ($profile[$r['bind']]) {
				$r['default'] = $profile[$r['bind']];
			}
			$activity['smsid'] && $r['bind'] == 'mobile' && empty($profile[$r['bind']]) && $this->showMessage('请完善手机号', murl('entry', array('do' => 'index', 'id' => $activity['smsid'], 'm' => 'dayu_sms', 'form' => $_W['current_module']['name'], 'returnurl' => $returnUrl), true, true), 'info');
			$r['type'] == 'photograph' && empty($profile[$r['bind']]) && $this->showMessage('请完善' . $r['title'], murl('entry', array('do' => 'index', 'f' => $r['bind'], 'm' => 'dayu_photograph', 'returnurl' => $returnUrl), true, true), 'info');
			pdo_tableexists('dayu_photograph_fields') && ($r['photograph_url'] = murl('entry', array('do' => 'index', 'f' => $r['bind'], 'm' => 'dayu_photograph', 'returnurl' => $returnUrl), true, true));
		}
		if ($_W['ispost'] || checksubmit('submit')) {
			$row = array();
			if (pdo_tableexists('dayu_kami') && !empty($par['kami'])) {
				$kami = pdo_get('dayu_kami', array('weid' => $weid, 'number' => $_GPC['kami'], 'cid' => $par['kami']), array('id', 'status', 'number', 'password'));
				if (empty($kami)) {
					$this->showMessage('卡号不存在', '', 'error');
					exit;
				} elseif ($kami['status'] == 1) {
					$this->showMessage($_GPC['kami'] . ' 已使用', '', 'error');
					exit;
				} elseif ($kami['password'] != $_GPC['pass']) {
					$this->showMessage($kami['password'] . '卡号与密码不匹配', '', 'error');
					exit;
				}
				$row['kid'] = $kami['id'];
			}
			if (pdo_tableexists('dayu_sendkami') && !empty($par['sendkami'])) {
				$sendkami = pdo_get('dayu_sendkami', array('weid' => $weid, 'cid' => $par['sendkami'], 'status' => '0'), array('id', 'number', 'password'));
				$row['kid'] = $sendkami['id'];
			}
			if (!empty($linkage)) {
				$lg = array('l1' => intval($_GPC['linkage1']), 'l2' => intval($_GPC['linkage2']));
				$row['linkage'] = iserializer($lg);
			}
			$row['reid'] = $reid;
			$row['member'] = $_GPC['member'];
			$row['mobile'] = $_GPC['mobile'];
			$row['address'] = $_GPC['address'];
			$row['loc_y'] = $_GPC['loc_y'];
			$row['loc_x'] = $_GPC['loc_x'];
			$row['openid'] = $openid;
			if (is_array($_GPC['thumb'])) {
				foreach ($_GPC['thumb'] as $thumb) {
					$th[] = tomedia($thumb);
				}
				$row['thumb'] = iserializer($th);
			}
			$row['voice'] = !empty($_GPC['voice']) ? $setting['qiniu']['host'] . '/' . $_GPC['voice'] : '';
			$row['var1'] = $_GPC[$par['var1']];
			$row['var2'] = $_GPC[$par['var2']];
			$row['var3'] = $_GPC[$par['var3']];
			$row['createtime'] = TIMESTAMP;
			$datas = $fields = $update = array();
			foreach ($ds as $value) {
				$fields[$value['refid']] = $value;
			}
			foreach ($_GPC as $key => $value) {
				if (strexists($key, 'field_')) {
					$bindFiled = substr(strrchr($key, '_'), 1);
					if (!empty($bindFiled)) {
						$update[$bindFiled] = $value;
					}
					$refid = intval(str_replace('field_', '', $key));
					$field = $fields[$refid];
					if ($refid && $field) {
						$entry = array();
						$entry['reid'] = $reid;
						$entry['rerid'] = 0;
						$entry['refid'] = $refid;
						$entry['displayorder'] = $field['displayorder'];
						if (in_array($activity['skins'], array('weui', 'weui3', 'weui_pg', 'weui_yuntai', 'weui_huahua', 'weui_ju'))) {
							if (in_array($field['type'], array('number', 'text', 'calendar', 'email', 'textarea', 'radio', 'range', 'select', 'image', 'reside', 'checkbox', 'photograph'))) {
								$entry['data'] = strval($value);
							}
						} else {
							if (in_array($field['type'], array('number', 'text', 'calendar', 'email', 'textarea', 'radio', 'range', 'select', 'image', 'reside', 'photograph'))) {
								$entry['data'] = strval($value);
							}
							if (in_array($field['type'], array('checkbox'))) {
								if (!is_array($value)) {
									continue;
								}
								$entry['data'] = implode(',', $value);
							}
						}
						$datas[] = $entry;
					}
				}
			}
			if ($_FILES) {
				foreach ($_FILES as $key => $file) {
					if (strexists($key, 'field_')) {
						$refid = intval(str_replace('field_', '', $key));
						$field = $fields[$refid];
						if ($refid && $field && $file['name'] && $field['type'] == 'image') {
							$upfile = $file;
							$name = $upfile['name'];
							$type = $upfile['type'];
							$size = $upfile['size'];
							$tmp_name = $upfile['tmp_name'];
							$error = $upfile['error'];
							$upload_path = "../attachment/dayu_form/" . $weid . "/";
							load()->func('file');
							@mkdirs($upload_path);
							if (intval($error) > 0) {
								message('上传错误：错误代码：' . $error, referer(), 'error');
							} else {
								$upfilesize = !empty($activity['filesize']) ? $activity['filesize'] : 12;
								$maxfilesize = $upfilesize;
								if ($maxfilesize > 0) {
									if ($size > $maxfilesize * 1024 * 1024) {
										message('上传文件过大' . $_FILES["file"]["error"], referer(), 'error');
									}
								}
								$uptypes = array('image/jpg', 'image/png', 'image/jpeg');
								if (!in_array($type, $uptypes)) {
									message('上传文件类型不符：' . $type, referer(), 'error');
								}
								if (!file_exists($upload_path)) {
									mkdir($upload_path);
								}
								$source_filename = 'form' . $reid . '_' . date("YmdHis") . mt_rand(10, 99) . '.jpg';
								$target_filename = 'form' . $reid . '_' . date("YmdHis") . mt_rand(10, 99) . '.thumb.jpg';
								if (!move_uploaded_file($tmp_name, $upload_path . $source_filename)) {
									message('移动文件失败，请检查服务器权限', referer(), 'error');
								}
								$srcfile = $upload_path . $source_filename;
								$desfile = $upload_path . $target_filename;
								$imginfo = getimagesize($srcfile);
								$imgtype = image_type_to_extension($imginfo[2], false);
								$fun = "imagecreatefrom" . $imgtype;
								$image = $fun($srcfile);
								$color = imagecolorallocatealpha($image, 255, 0, 0, 50);
								$content = date('Y-m-d H:i:s', TIMESTAMP);
								imagestring($image, 5, 10, 10, $content, $color);
								imagejpeg($image, $srcfile);
								$avatarsize = !empty($activity['upsize']) ? $activity['upsize'] : 640;
								$ret = file_image_thumb($srcfile, $desfile, $avatarsize);
								$entry = array();
								$entry['reid'] = $reid;
								$entry['rerid'] = 0;
								$entry['refid'] = $refid;
								if (!is_array($ret)) {
									$entry['data'] = $upload_path . $target_filename;
								}
								$datas[] = $entry;
							}
						}
					}
					unlink($srcfile);
				}
			}
			if (!empty($_GPC['reside'])) {
				if (in_array('reside', $binds)) {
					$update['resideprovince'] = $_GPC['reside']['province'];
					$update['residecity'] = $_GPC['reside']['city'];
					$update['residedist'] = $_GPC['reside']['district'];
				}
				foreach ($_GPC['reside'] as $key => $value) {
					$resideData = array('reid' => $reside['reid']);
					$resideData['rerid'] = 0;
					$resideData['refid'] = $reside['refid'];
					$resideData['data'] = $value;
					$datas[] = $resideData;
				}
			}
			if ($activity['paixu'] != '2') {
				$update['realname'] = $_GPC['member'];
				$update['mobile'] = $_GPC['mobile'];
			}
			if ($par['replace'] && !empty($update)) {
				load()->model('mc');
				mc_update($_W['member']['uid'], $update);
			}
			if (empty($datas)) {
				$this->showMessage('非法访问，提交数据不能为空', '', 'error');
			}
			if (pdo_insert('dayu_form_info', $row) != 1) {
				$this->showMessage('保存失败.');
			}
			$rerid = pdo_insertid();
			if (empty($rerid)) {
				$this->showMessage('保存失败。');
			}
			foreach ($datas as &$r) {
				$r['rerid'] = $rerid;
				pdo_insert('dayu_form_data', $r);
			}
			if (empty($activity['starttime'])) {
				$record = array();
				$record['starttime'] = TIMESTAMP;
				pdo_update('dayu_form', $record, array('reid' => $reid));
			}
			if (pdo_tableexists('dayu_kami') && !empty($par['kami'])) {
				$kamidata = array('reid' => $reid, 'infoid' => $rerid, 'addons' => 'dayu_form', 'openid' => $openid, 'name' => $row['member'], 'mobile' => $row['mobile'], 'status' => 1, 'updatetime' => TIMESTAMP);
				pdo_update('dayu_kami', $kamidata, array('weid' => $weid, 'id' => $row['kid']));
				$kamiinfo = !empty($row['kid']) ? "\\n　卡号：" . $kami['number'] : "";
			}
			$fans['user'] = mc_fansinfo($openid, $acid, $weid);
			$member = !empty($row['member']) ? $row['member'] : $fans['user']['nickname'];
			$ytime = date('Y-m-d H:i:s', TIMESTAMP);
			$voice = !empty($_GPC['voice']) ? "\\n　有" . $activity['voice'] : "";
			if (pdo_tableexists('dayu_sendkami') && !empty($par['sendkami'])) {
				$sendkamidata = array('reid' => $reid, 'infoid' => $rerid, 'addons' => 'dayu_form', 'openid' => $openid, 'name' => $row['member'], 'mobile' => $row['mobile'], 'status' => 1, 'updatetime' => TIMESTAMP);
				pdo_update('dayu_sendkami', $sendkamidata, array('weid' => $weid, 'id' => $row['kid']));
				$sendkami = pdo_get('dayu_sendkami', array('weid' => $weid, 'cid' => $par['sendkami'], 'id' => $row['kid']), array('id', 'number', 'password'));
				$kamiinfo = !empty($row['kid']) ? "\\n　卡号：" . $sendkami['number'] . "\\n　密码：" . $sendkami['password'] : "";
				$template = array("touser" => $row['openid'], "template_id" => $activity['k_templateid'], "url" => murl('entry', array('do' => 'mydayu_form', 'op' => 'detail', 'id' => $row['reid'], 'rerid' => $rerid, 'm' => 'dayu_form'), true, true), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($activity['mfirst']), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($member), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($row['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($ytime), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($kamiinfo), 'color' => "#FF0000"), 'remark' => array('value' => urlencode($activity['mfoot']), 'color' => "#008000")));
				$this->send_template_message(urldecode(json_encode($template)));
			}
			if (!empty($datas)) {
				foreach ($datas as $row) {
					if (strstr($row['data'], 'images')) {
						$row['data'] = "有";
					}
					$bodym .= '\\n　' . $fields[$row['refid']]['title'] . ':' . $row['data'];
					$body .= '<h4>' . $fields[$row['refid']]['title'] . '：' . $row['data'] . '</h4>';
					$smsbody .= $fields[$row['refid']]['title'] . ':' . $row['data'] . '，';
				}
				if (!empty($activity['noticeemail'])) {
					load()->func('communication');
					ihttp_email($activity['noticeemail'], $activity['title'] . '的表单提醒', '<h4>姓名：' . $member . '</h4><h4>手机：' . $_GPC['mobile'] . '</h4>' . $body . $voice . $kamiinfo);
				}
				if (!empty($activity['mobile']) && $activity['smsnotice'] != '0' && $par['sms'] != '1') {
					load()->func('communication');
					$content = $activity['smstype'] == 1 ? $activity['title'] : $smsbody;
					ihttp_post(murl('entry', array('do' => 'Notice', 'id' => $activity['smsnotice'], 'm' => 'dayu_sms'), true, true), array('mobile' => $activity['mobile'], 'mname' => $member, 'mmobile' => $_GPC['mobile'], 'content' => $content));
				}
				$staff = pdo_fetchall("SELECT `openid` FROM " . tablename($this->tb_staff) . " WHERE reid=:reid AND weid=:weid", array(':weid' => $_W['uniacid'], ':reid' => $row['reid']));
				if ($activity['custom_status'] == 0 && $staff && $activity['k_templateid']) {
					if (is_array($staff)) {
						foreach ($staff as $s) {
							$template = array("touser" => $s['openid'], "template_id" => $activity['k_templateid'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageform', array('name' => 'dayu_form', 'weid' => $row['weid'], 'id' => $row['reid'])), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($activity['kfirst'] . "\\n"), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($member), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($_GPC['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($ytime), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($bodym . $voice . $kamiinfo . "\\n"), 'color' => "#FF0000"), 'remark' => array('value' => urlencode($activity['kfoot']), 'color' => "#008000")));
							$this->send_template_message(urldecode(json_encode($template)));
						}
					}
				} else {
					if (is_array($staff)) {
						foreach ($staff as $s) {
							$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageform', array('name' => 'dayu_form', 'weid' => $row['weid'], 'id' => $row['reid']));
							$info = "【您好，有新的消息】\n\n";
							$info .= "姓名：{$member}\n手机：{$_GPC['mobile']}\n内容：{$bodym}{$voice}{$kamiinfo}\n\n";
							$info .= "<a href='{$url}'>点击查看详情</a>";
							$custom = array('msgtype' => 'text', 'text' => array('content' => urlencode($info)), 'touser' => $s['openid']);
							$acc = WeAccount::create($_W['acid']);
							$CustomNotice = $acc->sendCustomNotice($custom);
						}
					}
				}
			}
			if ($activity['credit'] != "0.00" && $par['icredit'] == '0') {
				mc_credit_update($uid, $behavior['activity'], $activity['credit'], array(0, $activity['title']));
				mc_group_update($uid);
				$log = $activity['title'] . '-' . $activity['credit'] . '积分';
				mc_notice_credit1($openid, $uid, $activity['credit'], $log);
			}
			$outlink = !empty($activity['outlink']) ? $activity['outlink'] : $this->createMobileUrl('mydayu_form', array('name' => 'dayu_form', 'weid' => $row['weid'], 'id' => $row['reid']));
			$this->showMessage($activity['information'], $outlink);
		}
		load()->func('tpl');
		if (!empty($par['comment']) && pdo_tableexists('dayu_comment')) {
			$comment = pdo_fetchall("SELECT * FROM " . tablename('dayu_comment') . " WHERE weid = :weid and reid = :reid ORDER BY reid DESC", array(':weid' => $weid, ':reid' => $reid));
		}
		$title = $par['title'] ? $par['title'] : $activity['title'];
		$jquery = 1;
		$_share['title'] = $title;
		$_share['content'] = $activity['description'];
		$_share['imgUrl'] = tomedia($activity['thumb']);
		include $this->template('skins/' . $activity['skins']);
	}
	public function doMobileUploads()
	{
		global $_W, $_GPC;
		load()->classs('account');
		$result = array('status' => 'error', 'message' => '123', 'data' => '');
		if (empty($_W['acid'])) {
			$sql = "SELECT acid FROM " . tablename('mc_mapping_fans') . " WHERE openid = :openid AND uniacid = :uniacid limit 1";
			$params = array(':openid' => $_W['openid'], ':uniacid' => $_W['uniacid']);
			$_W['acid'] = pdo_fetchcolumn($sql, $params);
		}
		if (empty($_W['acid'])) {
			$result['status'] = 'error';
			$result['message'] = '没有找到相关公众账号';
		}
		$acid = $_W['acid'];
		$acc = WeAccount::create($acid);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'upload';
		$type = !empty($_GPC['type']) ? $_GPC['type'] : 'image';
		if ($operation == 'upload') {
			if ($type == 'image') {
				$serverId = trim($_GPC['serverId']);
				$localId = trim($_GPC['localId']);
				$media = array();
				$media['media_id'] = $serverId;
				$media['type'] = $type;
				$result['serverId'] = $serverId;
				$result['localId'] = $localId;
				$filename = $acc->downloadMedia($media);
				if (is_error($filename)) {
					$result['status'] = 'error';
					$result['message'] = '上传失败';
				} else {
					$result['status'] = 'success';
					$result['message'] = '上传成功';
					$result['imgurl'] = $_W['attachurl'] . $filename;
					$result['path'] = $filename;
				}
			}
			die(json_encode($result));
		} elseif ($operation == 'remove') {
			$file = $_GPC['file'];
			file_delete($file);
			exit(json_encode(array('status' => true)));
		}
	}
	public function doMobileUpload()
	{
		global $_W, $_GPC;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['uniacid']);
		$access_token = $accObj->fetch_token();
		$media_id = $_GET['media_id'];
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $media_id;
		load()->func('tpl');
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $weid;
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		$upload_path = "../attachment/";
		$images_path = "dayu_form/" . $_W['uniacid'] . "/";
		load()->func('file');
		@mkdirs($upload_path . $images_path);
		if (!file_exists($upload_path . $images_path)) {
			mkdir($upload_path . $images_path);
		}
		if ($_GPC['type'] == 2) {
			$pic = 'avatar_' . date("YmdHis") . mt_rand(1000, 9999) . '.jpg';
			$picurl = $upload_path . $images_path . $pic;
			$data = array('avatar' => $images_path . $pic);
			load()->model('mc');
			mc_update($_W['member']['uid'], $data);
		} elseif ($reid) {
			$pic = 'form' . $reid . '_' . date("YmdHis") . mt_rand(1000, 9999) . '.jpg';
			$picurl = $upload_path . $images_path . $pic;
		}
		$targetName = $picurl;
		$ch = curl_init($url);
		$fp = fopen($targetName, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		$pathname = $images_path . $pic;
		if (!empty($_W['setting']['remote']['type'])) {
			load()->func('file');
			$remotestatus = file_remote_upload($pathname);
			if (is_error($remotestatus)) {
				message('远程附件上传失败，请检查配置并重新上传');
			} else {
				$remoteurl = $pathname;
				unlink($upload_path . $pathname);
			}
		}
		echo $pathname;
	}
	public function download_voice($media_id, $retry = 0)
	{
		global $_W, $_GPC;
		if ($media_id) {
			$access_token = WeAccount::token();
			load()->func('communication');
			$voice = ihttp_get('http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=' . $access_token . '&media_id=' . $media_id);
			if (isset($voice['headers']['Content-disposition'])) {
				return $voice['content'];
			} else {
				if ($retry === 0) {
					$this->download_voice($media_id, 1);
				}
				return false;
			}
		}
	}
	public function upload_qiniu_voice($filename, $content)
	{
		require MODULE_ROOT . '/Qiniu.class.php';
		$setting = $this->module['config'];
		if (!empty($setting['qiniu'])) {
			$qiniu = new Qiniu($setting['qiniu']);
			$pipeline = $setting['qiniu']['pipeline'];
			$r = $qiniu->putContent($filename, $content, $pipeline);
			if ($r === false) {
				return '';
			} else {
				if (isset($r['persistentId']) && !empty($r['persistentId'])) {
					return $r['persistentId'];
				} else {
					return '';
				}
			}
		}
	}
	public function doMobileUploadvoice()
	{
		global $_W, $_GPC;
		if (empty($_GPC['serverId'])) {
			$this->showMessage('serverId为空');
		}
		$setting = $this->module['config'];
		$content = $this->download_voice($_GPC['serverId'], '');
		if ($content) {
			$filename = 'dayu_form_' . $_W['uniacid'] . '_' . $_GPC['serverId'] . '.mp3';
			$r = $this->upload_qiniu_voice($filename, $content);
		}
	}
	public function showMessage($msg, $redirect = '', $type = '')
	{
		global $_W;
		if ($redirect == 'refresh') {
			$redirect = $_W['script_name'] . '?' . $_SERVER['QUERY_STRING'];
		} elseif (!empty($redirect) && !strexists($redirect, 'http://')) {
			$urls = parse_url($redirect);
			$redirect = $_W['siteroot'] . 'app/index.php?' . $urls['query'];
		}
		if ($redirect == '') {
			$type = in_array($type, array('success', 'error', 'info', 'warning', 'ajax', 'sql')) ? $type : 'info';
		} else {
			$type = in_array($type, array('success', 'error', 'info', 'warning', 'ajax', 'sql')) ? $type : 'success';
		}
		if ($_W['isajax'] || $type == 'ajax') {
			$vars = array();
			$vars['message'] = $msg;
			$vars['redirect'] = $redirect;
			$vars['type'] = $type;
			exit(json_encode($vars));
		}
		if (empty($msg) && !empty($redirect)) {
			header('location: ' . $redirect);
		}
		$label = $type;
		if ($type == 'error') {
			$label = 'danger';
			$info = '出错了，原因：';
		}
		if ($type == 'ajax' || $type == 'sql') {
			$label = 'warning';
			$info = '访问受限，原因：';
		}
		if ($type == 'info') {
			$label = 'info';
			$info = '操作提示';
		}
		if ($type == 'success') {
			$info = '提交成功';
		}
		if (defined('IN_API')) {
			exit($msg);
		}
		include $this->template('messages', TEMPLATE_INCLUDEPATH);
		exit;
	}
	public function doMobileMydayu_form()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		if (!$openid) {
			$this->showMessage('非法访问');
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		$par = iunserializer($activity['par']);
		$mname = !empty($par['mname']) ? $par['mname'] : "往期记录";
		if ($par['follow'] == 1) {
			$this->getFollow();
		} else {
			$member = !empty($member) ? $member : $_SESSION['userinfo'];
			if (empty($member['avatar'])) {
				if ($_W['account']['level'] > 3) {
					$member = mc_oauth_userinfo($_W['acid']);
				} else {
					$member = mc_oauth_fans($openid, $_W['acid']);
				}
			}
		}
		if ($operation == 'display') {
			if ($reid) {
				$list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_form) . " WHERE weid = :weid and status = 1 ORDER BY reid DESC", array(':weid' => $weid), 'reid');
				$pindex = max(1, intval($_GPC['page']));
				$psize = 10;
				$status = intval($_GPC['status']);
				if ($_GPC['status'] != '') {
					if ($status == 2) {
						$where .= " and ( status=2 or status=-1 )";
					} else {
						$where .= " and status=$status";
					}
				}
				$rows = pdo_fetchall("SELECT * FROM " . tablename($this->tb_info) . " WHERE openid = :openid and reid = :reid $where ORDER BY rerid DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}", array(':openid' => $_W['openid'], ':reid' => $reid), 'rerid');
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE openid = :openid and reid = :reid $where", array(':openid' => $_W['openid'], ':reid' => $reid));
				foreach ($rows as $key => $val) {
					$rows[$key]['status'] = $this->get_status($val['reid'], $val['status']);
					$rows[$key]['user'] = mc_fansinfo($val['openid'], $acid, $weid);
					$rows[$key]['kf'] = mc_fansinfo($val['kf'], $acid, $weid);
					$rows[$key]['comment'] = !empty($par['comment']) && pdo_tableexists('dayu_comment') && !empty($val['commentid']) ? $this->get_comment($val['commentid']) : '';
				}
				$rerid = array_keys($rows);
				$children = array();
				$childlist = pdo_fetchall("SELECT * FROM " . tablename($this->tb_data) . " WHERE rerid IN ('" . implode("','", is_array($rerid) ? $rerid : array($rerid)) . "') AND `reid`=:reid", array(':reid' => $reid));
				foreach ($childlist as $reply => $r) {
					if (!empty($r['rerid'])) {
						$children[$r['rerid']][] = $r;
						unset($children[$reply]);
					}
				}
			} else {
				$sql = 'SELECT `reid` FROM ' . tablename($this->tb_info) . " WHERE openid = :openid ORDER BY rerid DESC";
				$params = array();
				$params[':openid'] = $openid;
				$rows = pdo_fetchall($sql, $params);
				$new_array = array();
				foreach ($rows as $v) {
					$new_array[$v['reid']] = 1;
				}
				$last = array();
				foreach ($new_array as $u => $v) {
					$last[] = $u;
				}
				$fids = implode(',', $last);
				if ($fids) {
					$list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_form) . " WHERE weid = :weid and reid in({$fids}) and status = 1 ORDER BY reid DESC", array(':weid' => $weid), 'reid');
					$pindex = max(1, intval($_GPC['page']));
					$psize = 10;
					$status = intval($_GPC['status']);
					if ($_GPC['status'] != '') {
						if ($status == 2) {
							$where .= " and ( status=2 or status=-1 )";
						} else {
							$where .= " and status=$status";
						}
					}
					$rows = pdo_fetchall("SELECT * FROM " . tablename($this->tb_info) . " WHERE openid = :openid $where ORDER BY rerid DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}", array(':openid' => $_W['openid']));
					$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE openid = :openid $where ", array(':openid' => $_W['openid']));
				}
			}
			$pager = $this->pagination($total, $pindex, $psize);
			$user_footer = 1;
		} elseif ($operation == 'detail') {
			$setting = $this->module['config'];
			$rerid = intval($_GPC['rerid']);
			$row = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE openid = :openid AND rerid = :rerid", array(':openid' => $_W['openid'], ':rerid' => $rerid));
			if (empty($row)) {
				$this->showMessage('记录不存在或是已经被删除！');
			}
			$la = iunserializer($activity['linkage']);
			$linkage = iunserializer($row['linkage']);
			$linkage['l1'] = $this->get_linkage($linkage['l1'], '');
			$linkage['l2'] = $this->get_linkage($linkage['l2'], '');
			$row['createtime'] = !empty($row['createtime']) ? date('Y年m月d日 H:i', $row['createtime']) : '时间丢失';
			$row['yuyuetime'] = !empty($row['yuyuetime']) ? date('Y年m月d日 H:i', $row['yuyuetime']) : '请等待客服受理';
			$row['thumb'] = iunserializer($row['thumb']);
			$row['voices'] = $row['voice'];
			$row['revoices'] = $row['revoice'];
			$row['rethumbs'] = tomedia($row['rethumb']);
			$row['file'] = iunserializer($row['file']);
			$row['user'] = mc_fansinfo($row['openid'], $acid, $weid);
			$status = $this->get_status($row['reid'], $row['status']);
			if (pdo_tableexists('dayu_kami') && !empty($par['kami']) && $row['kid']) {
				$kami = pdo_get('dayu_kami', array('weid' => $weid, 'id' => $row['kid']), array());
			}
			if (pdo_tableexists('dayu_sendkami') && !empty($par['sendkami']) && $row['kid']) {
				$kami = pdo_get('dayu_sendkami', array('weid' => $weid, 'id' => $row['kid']), array());
			}
			if (!empty($par['comment']) && pdo_tableexists('dayu_comment') && !empty($row['commentid'])) {
				$comment = pdo_get('dayu_comment', array('weid' => $weid, 'id' => $row['commentid']), array());
			}
			$sql = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY displayorder DESC, refid DESC';
			$params = array();
			$params[':reid'] = $row['reid'];
			$fields = pdo_fetchall($sql, $params);
			if (empty($fields)) {
				$this->showMessage('非法访问.');
			}
			$ds = $fids = array();
			foreach ($fields as $f) {
				$ds[$f['refid']]['fid'] = $f['title'];
				$ds[$f['refid']]['type'] = $f['type'];
				$ds[$f['refid']]['refid'] = $f['refid'];
				$ds[$f['refid']]['loc'] = $f['loc'];
				$fids[] = $f['refid'];
			}
			$fids = implode(',', $fids);
			$row['fields'] = array();
			$sql = 'SELECT * FROM ' . tablename($this->tb_data) . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
			$fdatas = pdo_fetchall($sql, $params);
			foreach ($fdatas as $fd) {
				$row['fields'][$fd['refid']] = $fd['data'];
			}
			foreach ($ds as $value) {
				if ($value['type'] == 'reside') {
					$row['fields'][$value['refid']] = '';
					foreach ($fdatas as $fdata) {
						if ($fdata['refid'] == $value['refid']) {
							$row['fields'][$value['refid']] .= $fdata['data'];
						}
					}
					break;
				}
			}
			$dayu_form['content'] = htmlspecialchars_decode($dayu_form['content']);
			$jquery = 1;
		}
		$title = $activity['title'];
		include $this->template('dayu_form');
	}
	public function doMobileIndex()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileGetForm()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$form = pdo_get($this->tb_form, array('weid' => $weid, 'reid' => $_GPC['id']), array());
		$par = iunserializer($form['par']);
		$link = $this->createMobileUrl('dayu_form', array('id' => $form['reid']));
		$mylink = $this->createMobileUrl('mydayu_form', array('id' => $form['reid']));
		$result['id'] = $form['reid'];
		$result['mname'] = $form['mname'];
		$thumb = tomedia($form['thumb']);
		$html = '
		 <div class="weui-header bg-blue">
			<div class="weui-header-left">
				<a href="javascript:;" class="icon icon-109 f-white close-popup">
					<svg class="icon" aria-hidden="true">
						<use xlink:href="#icon-left"></use>
					</svg>
				</a>
			</div>
			<h1 class="weui-header-title">' . $form['title'] . '</h1>
		</div>
		<div class="weui-weixin">
			<div class="weui-weixin-ui">
				<div class="weui-weixin-page">
					<div class="weui-weixin-img text-center"><img src="' . $thumb . '" id="image" class="center" style="width:100%;"></div>                                        
					<div class="weui-weixin-content">' . htmlspecialchars_decode($form['content']) . '</div>
				</div>
			</div>
		</div>
		';
		$html2 = '
	<div class="weui_tabbar tab-bottom">
		<a href="javascript:;" class="weui_tabbar_item close-popup">
			<div class="weui_tabbar_icon">
				<svg class="icon" aria-hidden="true">
					<use xlink:href="#icon-close"></use>
				</svg>
			</div>
			<p class="weui_tabbar_label">关闭</p>
		</a>
		<a href="' . $link . '" class="weui_tabbar_item">
			<div class="weui_tabbar_icon">
				<svg class="icon" aria-hidden="true">
					<use xlink:href="#icon-xinzeng"></use>
				</svg>
			</div>
			<p class="weui_tabbar_label">' . $form['title'] . '</p>
		</a>
		<a href="' . $mylink . '" class="weui_tabbar_item">
			<div class="weui_tabbar_icon">
				<svg class="icon" aria-hidden="true">
					<use xlink:href="#icon-jihuajindu"></use>
				</svg>
			</div>
			<p class="weui_tabbar_label">' . $par['mname'] . '</p>
		</a>
	</div>
		';
		$result['html'] = $html;
		$result['html2'] = $html2;
		message($result, '', 'ajax');
	}
	public function doMobilelist()
	{
		global $_W, $_GPC;
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_form) . " WHERE weid = :weid and status = 1 ORDER BY reid DESC", array(':weid' => $_W['uniacid']), 'reid');
		include $this->template('list');
	}
	public function doMobilePower()
	{
		global $_GPC, $_W;
		$reid = $_GPC['reid'];
		$rerid = $_GPC['rerid'];
		if ($_GPC['table'] == 'manage') {
			$data = array('kfid' => $_GPC['openid']);
			if (pdo_update($this->tb_form, $data, array('reid' => $reid, 'weid' => $_W['uniacid'])) === false) {
				$result['status'] = 0;
				$result['msg'] = '转移失败';
			} else {
				$result['status'] = 1;
				$result['msg'] = '转移成功';
			}
			message($result, '', 'ajax');
		}
		if ($_GPC['table'] == 'case') {
			$data = array('kf' => $_GPC['openid']);
			if (pdo_update($this->tb_info, $data, array('reid' => $reid, 'rerid' => $rerid)) === false) {
				$result['status'] = 0;
				$result['msg'] = '派单失败';
			} else {
				$activity = pdo_get($this->tb_form, array('weid' => $_W['uniacid'], 'reid' => $reid), array('title', 'k_templateid', 'kfirst', 'kfoot'));
				$content = pdo_get($this->tb_info, array('reid' => $reid, 'rerid' => $rerid), array('member', 'mobile', 'createtime'));
				if ($_W['account']['level'] == ACCOUNT_SERVICE_VERIFY && !empty($activity['k_templateid'])) {
					$template = array("touser" => $_GPC['openid'], "template_id" => $activity['k_templateid'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageform', array('name' => 'dayu_form', 'weid' => $_W['uniacid'], 'op' => 'detail', 'id' => $reid, 'rerid' => $rerid)), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($activity['kfirst'] . "\\n"), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($content['member']), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($content['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode(date('Y-m-d H:i:s', $content['createtime'])), 'color' => '#000000'), 'keyword4' => array('value' => urlencode("管理员派单\\n"), 'color' => "#FF0000"), 'remark' => array('value' => urlencode($activity['kfoot']), 'color' => "#008000")));
					$this->send_template_message(urldecode(json_encode($template)));
				} else {
					$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageform', array('name' => 'dayu_form', 'weid' => $_W['uniacid'], 'op' => 'detail', 'id' => $reid, 'rerid' => $rerid));
					$info = "【您好，{$activity['title']} 通知】\n\n";
					$info .= "姓名：{$content['member']}\n手机：{$content['mobile']}\n管理员派单\n\n";
					$info .= "<a href='{$url}'>点击查看详情</a>";
					$custom = array('msgtype' => 'text', 'text' => array('content' => urlencode($info)), 'touser' => $s['openid']);
					$acc = WeAccount::create($_W['acid']);
					$CustomNotice = $acc->sendCustomNotice($custom);
				}
				$result['status'] = 1;
				$result['msg'] = '派单成功';
			}
			message($result, '', 'ajax');
		}
	}
	public function doMobilemanageform()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		if (!$openid) {
			$this->showMessage('非法访问，空');
		}
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		$par = iunserializer($activity['par']);
		$staff = pdo_fetchall("SELECT * FROM " . tablename($this->tb_staff) . " WHERE reid = :reid ORDER BY `id` DESC", array(':reid' => $reid));
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_form) . " WHERE weid = :weid and kfid = :openid and status = 1 ORDER BY reid DESC", array(':weid' => $weid, ':openid' => $openid), 'reid');
		if ($operation == 'display') {
			$title = $activity['title'];
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$status = $_GPC['status'];
			if ($status != '') {
				if ($status == 2) {
					$where .= " and ( status=2 or status=-1 )";
				} else {
					$where .= " and status=$status";
				}
			}
			if ($openid != $activity['kfid']) {
				$where .= " and kf='{$openid}'";
			}
			$sql = 'SELECT * FROM ' . tablename($this->tb_info) . " WHERE reid=:reid $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$params = array();
			$params[':reid'] = $reid;
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid $where ", $params);
			$pager = $this->pagination($total, $pindex, $psize);
			$rows = pdo_fetchall($sql, $params, 'rerid');
			foreach ($rows as $key => $val) {
				$rows[$key]['status'] = $this->get_status($val['reid'], $val['status']);
				$rows[$key]['user'] = mc_fansinfo($val['openid'], $acid, $weid);
				$rows[$key]['kf'] = mc_fansinfo($val['kf'], $acid, $weid);
				$rows[$key]['comment'] = !empty($par['comment']) && pdo_tableexists('dayu_comment') && !empty($val['commentid']) ? $this->get_comment($val['commentid']) : '';
			}
			$rerid = array_keys($rows);
			$children = array();
			$childlist = pdo_fetchall("SELECT * FROM " . tablename($this->tb_data) . " WHERE rerid IN ('" . implode("','", is_array($rerid) ? $rerid : array($rerid)) . "') AND `reid`=:reid", array(':reid' => $reid));
			foreach ($childlist as $reply => $r) {
				if (!empty($r['rerid'])) {
					$children[$r['rerid']][] = $r;
					unset($children[$reply]);
				}
			}
			$manage_footer = 1;
		} elseif ($operation == 'detail') {
			$setting = $this->module['config'];
			$rerid = intval($_GPC['rerid']);
			$row = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE rerid = :rerid", array(':rerid' => $rerid));
			$la = iunserializer($activity['linkage']);
			$linkage = iunserializer($row['linkage']);
			if (pdo_tableexists('dayu_kami') && $row['kid']) {
				$kami = pdo_get('dayu_kami', array('weid' => $_W['uniacid'], 'id' => $row['kid']), array());
			}
			if (pdo_tableexists('dayu_sendkami') && !empty($par['sendkami']) && $row['kid']) {
				$kami = pdo_get('dayu_sendkami', array('weid' => $weid, 'id' => $row['kid']), array());
			}
			if (!empty($par['comment']) && pdo_tableexists('dayu_comment') && !empty($row['commentid'])) {
				$comment = pdo_get('dayu_comment', array('weid' => $weid, 'id' => $row['commentid']), array());
			}
			$linkage['l1'] = $this->get_linkage($linkage['l1'], '');
			$linkage['l2'] = $this->get_linkage($linkage['l2'], '');
			if ($openid == $activity['kfid'] || $openid == $row['kf']) {
				$custom = pdo_fetchall("SELECT * FROM " . tablename($this->tb_custom) . " WHERE weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid));
				if (empty($row)) {
					$this->showMessage('记录不存在或是已经被删除！');
				}
				$face = mc_fansinfo($row['openid'], $acid, $weid);
				$dayu_form = pdo_fetch("SELECT * FROM " . tablename($this->tb_form) . " WHERE reid = :reid", array(':reid' => $row['reid']));
				$dayu_form['content'] = htmlspecialchars_decode($dayu_form['content']);
				$sql = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY displayorder DESC, refid DESC';
				$params = array();
				$params[':reid'] = $row['reid'];
				$fields = pdo_fetchall($sql, $params);
				if (empty($fields)) {
					$this->showMessage('非法访问.');
				}
				$ds = $fids = array();
				foreach ($fields as $f) {
					$ds[$f['refid']]['fid'] = $f['title'];
					$ds[$f['refid']]['type'] = $f['type'];
					$ds[$f['refid']]['refid'] = $f['refid'];
					$ds[$f['refid']]['loc'] = $f['loc'];
					$fids[] = $f['refid'];
				}
				$fids = implode(',', $fids);
				$row['fields'] = array();
				$sql = 'SELECT * FROM ' . tablename($this->tb_data) . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
				$fdatas = pdo_fetchall($sql, $params);
				foreach ($fdatas as $fd) {
					$row['fields'][$fd['refid']] = $fd['data'];
				}
				foreach ($ds as $value) {
					if ($value['type'] == 'reside') {
						$row['fields'][$value['refid']] = '';
						foreach ($fdatas as $fdata) {
							if ($fdata['refid'] == $value['refid']) {
								$row['fields'][$value['refid']] .= $fdata['data'];
							}
						}
						break;
					}
				}
				$yuyuetime = !empty($row['yuyuetime']) ? date('Y-m-d H:i', $row['yuyuetime']) : date('Y-m-d H:i', TIMESTAMP);
				$row['createtime'] = !empty($row['createtime']) ? date('Y年m月d日 H:i', $row['createtime']) : '时间丢失';
				$row['yuyuetime'] = !empty($row['yuyuetime']) ? date('Y年m月d日 H:i', $row['yuyuetime']) : '客服尚未受理';
				$row['thumb'] = iunserializer($row['thumb']);
				$row['voices'] = $row['voice'];
				$row['revoices'] = $row['revoice'];
				$row['rethumbs'] = !empty($row['rethumb']) ? tomedia($row['rethumb']) : TEMPLATE_WEUI . "images/nopic.jpg";
				$row['file'] = iunserializer($row['file']);
				$row['user'] = mc_fansinfo($row['openid'], $acid, $weid);
				$row['member'] = !empty($row['member']) ? $row['member'] : $row['user']['nickname'];
				$status = $this->get_status($row['reid'], $row['status']);
				$state = array();
				$arr2 = array('0', '1', '2', '3', '8');
				foreach ($arr2 as $index => $v) {
					$state[$v][] = $this->get_status($reid, $v);
				}
				$repeat = $_COOKIE['r_submit'];
				if (!empty($_GPC['repeat'])) {
					if (!empty($repeat)) {
						if ($repeat == $_GPC['repeat']) {
							$this->showMessage($activity['information'], $this->createMobileUrl('mydayu_form', array('id' => $reid)));
						} else {
							setcookie("r_submit", $_GPC['repeat']);
						}
					} else {
						setcookie("r_submit", $_GPC['repeat']);
					}
				}
				if ($_W['ispost']) {
					$record = array();
					$record['status'] = intval($_GPC['status']);
					$record['yuyuetime'] = strtotime($_GPC['yuyuetime']);
					$record['kf'] = $openid;
					$record['kfinfo'] = $_GPC['kfinfo'];
					$record['revoice'] = !empty($_GPC['revoice']) ? $setting['qiniu']['host'] . '/' . $_GPC['revoice'] : '';
					$record['rethumb'] = !empty($_GPC['rethumb']) ? $_GPC['rethumb'] : '';
					if ($_GPC['status'] == '3' && $par['icredit'] == '1') {
						$record['icredit'] = 1;
					}
					$kfinfo = !empty($record['kfinfo']) ? "\\n客服回复：" . $record['kfinfo'] : "";
					$revoice = !empty($_GPC['revoice']) ? '\\n有语音答复' : '';
					$status = $this->get_status($reid, $_GPC['status']);
					$huifu = $status['name'] . $kfinfo . $revoice;
					$ytime = date('Y-m-d H:i:s', $yuyuetime);
					$outurl = !empty($par['noticeurl']) ? $par['noticeurl'] : $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_form', array('op' => 'detail', 'rerid' => $rerid, 'id' => $row['reid']));
					$template = array("touser" => $row['openid'], "template_id" => $dayu_form['m_templateid'], "url" => $outurl, "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($dayu_form['mfirst']), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($row['member']), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($row['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($_GPC['yuyuetime']), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($huifu), 'color' => "#FF0000"), 'remark' => array('value' => urlencode($dayu_form['mfoot']), 'color' => "#008000")));
					if ($dayu_form['custom_status'] == 1) {
						$acc = notice_init();
						if (is_error($acc)) {
							return error(-1, $acc['message']);
						}
						$url = $outurl;
						$info = "【您好，受理结果通知】\n\n";
						$info .= "姓名：{$row['member']}\n手机：{$row['mobile']}\n受理结果：{$huifu}\n\n";
						$info .= "<a href='{$url}'>点击查看详情</a>";
						$custom = array('msgtype' => 'text', 'text' => array('content' => urlencode($info)), 'touser' => $row['openid']);
						$acc = WeAccount::create($_W['acid']);
						$CustomNotice = $acc->sendCustomNotice($custom);
					} else {
						$this->send_template_message(urldecode(json_encode($template)));
					}
					if ($row['icredit'] != '1' && $par['icredit'] == '1' && $activity['credit'] != '0.00' && $_GPC['status'] == '3') {
						$settings = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors'));
						$behavior = $settings['creditbehaviors'];
						mc_credit_update(mc_openid2uid($row['openid']), $behavior['activity'], $activity['credit'], array(0, $activity['title']));
						mc_group_update(mc_openid2uid($row['openid']));
						$log = $activity['title'] . '-' . $activity['credit'] . '积分';
						mc_notice_credit1($row['openid'], mc_openid2uid($row['openid']), $activity['credit'], $log);
					}
					if ($par['sms'] != '0' && $par['paixu'] != '2' && !empty($activity['smsnotice'])) {
						load()->func('communication');
						$content = '状态：' . $status['name'];
						ihttp_post(murl('entry', array('do' => 'Notice', 'id' => $activity['smsnotice'], 'm' => 'dayu_sms'), true, true), array('mobile' => $row['mobile'], 'mname' => $row['member'], 'content' => $content));
					}
					pdo_update('dayu_form_info', $record, array('rerid' => $rerid));
					$this->showMessage('修改成功', referer(), 'success');
				}
			} else {
				$this->showMessage('非法访问！你不是管理员。');
			}
			$titles = $activity['title'];
		}
		include $this->template('manage_form');
	}
	public function isHy($openid)
	{
		global $_W;
		load()->model('mc');
		$card = pdo_fetch("SELECT * FROM " . tablename("mc_card_members") . " WHERE uniacid=:uniacid AND openid = :openid ", array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
		if (empty($card)) {
			return false;
		} else {
			return true;
		}
	}
	public function send_template_message($data)
	{
		global $_W, $_GPC;
		load()->classs('weixin.account');
		load()->func('communication');
		$access_token = WeAccount::token();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
		$response = ihttp_request($url, $data);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 原数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'],$result['errmsg'])}");
		}
		return true;
	}
	public function AjaxMessage($msg, $status = 0)
	{
		$result = array('message' => $msg, 'status' => $status);
		echo json_encode($result);
		exit;
	}
	public function doMobilechangeAjax()
	{
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$reid = intval($_GPC['reid']);
		$sql = 'SELECT * FROM ' . tablename($this->tb_form) . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		$par = iunserializer($activity['par']);
		$row = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE rerid = :rerid", array(':rerid' => $id));
		$status = $_GPC['status'];
		$data = array('status' => $status);
		if (!empty($id)) {
			$url = !empty($par['noticeurl']) ? $par['noticeurl'] : $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_form', array('op' => 'detail', 'rerid' => $id, 'id' => $reid));
			$data = array('first' => array('value' => $activity['mfirst'] . "\n", 'color' => "#743A3A"), 'keyword1' => array('value' => $row['member']), 'keyword2' => array('value' => $row['mobile']), 'keyword3' => array('value' => date('Y-m-d H:i:s', TIMESTAMP)), 'keyword4' => array('value' => $activity['state3']), 'remark' => array('value' => "\n" . $activity['mfoot'], 'color' => "#008000"));
			$acc = WeAccount::create($_W['acid']);
			$acc->sendTplNotice($row['openid'], $activity['m_templateid'], $data, $url, "#FF0000");
			pdo_update('dayu_form_info', $data, array('rerid' => $id));
			$this->AjaxMessage('更新成功!', 1);
		} else {
			$this->AjaxMessage('更新失败!', 0);
		}
	}
	function pagination($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => ''))
	{
		global $_W;
		$pdata = array('tcount' => 0, 'tpage' => 0, 'cindex' => 0, 'findex' => 0, 'pindex' => 0, 'nindex' => 0, 'lindex' => 0, 'options' => '');
		if ($context['ajaxcallback']) {
			$context['isajax'] = true;
		}
		$pdata['tcount'] = $tcount;
		$pdata['tpage'] = ceil($tcount / $psize);
		if ($pdata['tpage'] <= 1) {
			return '';
		}
		$cindex = $pindex;
		$cindex = min($cindex, $pdata['tpage']);
		$cindex = max($cindex, 1);
		$pdata['cindex'] = $cindex;
		$pdata['findex'] = 1;
		$pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
		$pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
		$pdata['lindex'] = $pdata['tpage'];
		if ($context['isajax']) {
			if (!$url) {
				$url = $_W['script_name'] . '?' . http_build_query($_GET);
			}
			$pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
		} else {
			if ($url) {
				$pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
				$pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
				$pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
				$pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
			} else {
				$_GET['page'] = $pdata['findex'];
				$pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['pindex'];
				$pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['nindex'];
				$pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
				$_GET['page'] = $pdata['lindex'];
				$pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			}
		}
		$html = '<div class="pager">';
		if ($pdata['cindex'] > 1) {
			$html .= "<div class=\"pager-left\">";
			$html .= "<div class=\"pager-first\"><a {$pdata['faa']} class=\"pager-nav\">首页</a></div>";
			$html .= "<div class=\"pager-pre\"><a {$pdata['paa']}>上一页</a></div>";
			$html .= "</div>";
		} else {
			$html .= "<div class=\"pager-left\">";
			$html .= "<div class=\"pager-pre\" style=\"width:100%\"><a href=\"###\">这是第一页</a></div>";
			$html .= "</div>";
		}
		$html .= "<div class=\"pager-cen\">{$pindex} / " . $pdata['tpage'] . "</div>";
		if ($pdata['cindex'] < $pdata['tpage']) {
			$html .= "<div class=\"pager-right\">";
			$html .= "<div class=\"pager-next\"><a {$pdata['naa']} class=\"pager-nav\">下一页</a></div>";
			$html .= "<div class=\"pager-end\"><a {$pdata['laa']} class=\"pager-nav\">尾页</a></div>";
			$html .= "</div>";
		} else {
			$html .= "<div class=\"pager-right\">";
			$html .= "<div class=\"pager-next\" style=\"width:100%\"><a href=\"###\">已是尾页</a></div>";
			$html .= "</div>";
		}
		$html .= '<div class="clr"></div></div>';
		return $html;
	}
	public function doMobileFansUs()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$qrcodesrc = tomedia('qrcode_' . $_W['acid'] . '.jpg');
		include $this->template('fans_us');
	}
	public function getFollow()
	{
		global $_GPC, $_W;
		require 'fans.mobile.php';
		$p = pdo_fetch("SELECT follow FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :weid AND openid = :openid LIMIT 1", array(":weid" => $_W['uniacid'], ":openid" => $_W['openid']));
		if (intval($p['follow']) == 0) {
			header('Location: ' . $this->createMobileUrl('FansUs'), true, 301);
		} else {
			return true;
		}
	}
	private function checkauth($openid, $nickname, $headimgurl)
	{
		global $_W, $engine;
		$settings = cache_load('unisetting:' . $_W['uniacid']);
		if (empty($_W['member']['uid']) && empty($settings['passport']['focusreg'])) {
			$fan = pdo_get('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'openid' => $openid));
			if (!empty($fan)) {
				$fanid = $fan['fanid'];
			} else {
				$post = array('acid' => $_W['acid'], 'uniacid' => $_W['uniacid'], 'nickname' => $nickname, 'openid' => $_W['fans']['openid'], 'salt' => random(8), 'follow' => 0, 'updatetime' => TIMESTAMP, 'tag' => base64_encode(iserializer($_W['fans'])));
				pdo_insert('mc_mapping_fans', $post);
				$fanid = pdo_insertid();
			}
			if (empty($fan['uid'])) {
				$email = md5($oauth['openid']) . '@vqiyi.cn';
				$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
				$data = array('uniacid' => $_W['uniacid'], 'email' => $email, 'salt' => random(8), 'groupid' => $default_groupid, 'createtime' => TIMESTAMP, 'password' => md5($message['from'] . $data['salt'] . $_W['config']['setting']['authkey']), 'avatar' => $headimgurl, 'nickname' => $nickname);
				pdo_insert('mc_members', $data);
				$uid = pdo_insertid();
				$_W['member']['uid'] = $uid;
				$_W['fans']['uid'] = $uid;
				pdo_update('mc_mapping_fans', array('uid' => $uid), array('fanid' => $fanid));
			} else {
				$_W['member']['uid'] = $fan['uid'];
				$_W['fans']['uid'] = $fan['uid'];
			}
		} else {
			checkauth();
		}
	}
	private function checkAuth2()
	{
		global $_W;
		$setting = cache_load('unisetting:' . $_W['uniacid']);
		if (empty($_W['member']['uid']) && empty($setting['passport']['focusreg'])) {
			$fan = pdo_get('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid']));
			if (!empty($fan)) {
				$fanid = $fan['fanid'];
			} else {
				$post = array('acid' => $_W['acid'], 'uniacid' => $_W['uniacid'], 'openid' => $_W['openid'], 'updatetime' => time(), 'follow' => 0);
			}
			if (empty($fan['uid'])) {
				pdo_insert('mc_members', array('uniacid' => $_W['uniacid']));
				$uid = pdo_insertid();
				$_W['member']['uid'] = $uid;
				$_W['fans']['uid'] = $uid;
				pdo_update('mc_mapping_fans', array('uid' => $uid), array('fanid' => $fanid));
			} else {
				$_W['member']['uid'] = $fan['uid'];
				$_W['fans']['uid'] = $fan['uid'];
			}
		} else {
			checkauth();
		}
	}
	public function get_status($reid, $status)
	{
		global $_W, $_GPC;
		$activity = $this->get_form($reid);
		$par = iunserializer($activity['par']);
		$state1 = !empty($par['state1']) ? $par['state1'] : '待受理';
		$state2 = !empty($par['state2']) ? $par['state2'] : '受理中';
		$state3 = !empty($par['state3']) ? $par['state3'] : '已完成';
		$state4 = !empty($par['state4']) ? $par['state4'] : '拒绝受理';
		$state5 = !empty($par['state5']) ? $par['state5'] : '已取消';
		$state8 = !empty($par['state8']) ? $par['state8'] : '退回修改';
		$state = array('0' => array('css' => 'weui_btn_default', 'css2' => 'btn-default', 'name' => $state1), '1' => array('css' => 'weui_btn_primary', 'css2' => 'btn-success', 'name' => $state2), '2' => array('css' => 'weui_btn_warn', 'css2' => 'btn-warning', 'name' => $state4), '3' => array('css' => 'bg-blue', 'css2' => 'btn-primary', 'name' => $state3), '7' => array('css' => 'weui_btn_disabled weui_btn_warn', 'css2' => 'btn-danger', 'name' => '已退款'), '8' => array('css' => 'bg-orange', 'css2' => 'btn-warning', 'name' => $state8), '9' => array('css' => 'weui_btn_disabled weui_btn_warn', 'css2' => 'btn-warning', 'name' => $state5));
		return $state[$status];
	}
	public function get_status_name($reid, $status)
	{
		$status = $this->get_status($reid, $status);
		return $status['name'];
	}
	public function get_category($id)
	{
		global $_W;
		return pdo_get($this->tb_category, array('weid' => $_W['uniacid'], 'id' => $id), array());
	}
	public function get_form($reid)
	{
		global $_W;
		return pdo_get($this->tb_form, array('weid' => $_W['uniacid'], 'reid' => $reid), array());
	}
	public function get_info($reid, $type)
	{
		global $_GPC, $_W;
		if ($type == 1) {
			return pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid and openid = :openid", array(":reid" => $reid, ":openid" => $_W['openid']));
		} elseif ($type == 2) {
			return pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid", array(":reid" => $reid));
		}
	}
	public function get_fields($fid)
	{
		global $_GPC, $_W;
		return pdo_get($this->tb_field, array('refid' => $fid), array());
	}
	public function get_comment($commentid)
	{
		global $_GPC, $_W;
		return pdo_get('dayu_comment', array('weid' => $_W['uniacid'], 'id' => $commentid), array());
	}
	public function get_linkage($id, $type)
	{
		if ($type == 1) {
			return pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_linkage) . " WHERE reid = :reid", array(":reid" => $id));
		} else {
			return pdo_fetch("SELECT * FROM " . tablename($this->tb_linkage) . " WHERE id = :id LIMIT 1", array(":id" => $id));
		}
	}
	public function get_role($uid)
	{
		global $_GPC, $_W;
		return pdo_fetch("SELECT username, uid FROM " . tablename('users') . " WHERE uid = :uid LIMIT 1", array(":uid" => $uid));
	}
	public function get_isrole($reid, $uid)
	{
		global $_GPC, $_W;
		return pdo_fetch("SELECT * FROM " . tablename($this->tb_role) . " WHERE weid = :weid AND reid = :reid AND roleid = :uid LIMIT 1", array(":weid" => $_W['uniacid'], ":reid" => $reid, ":uid" => $uid));
	}
	public function error_code($code, $errmsg = '未知错误')
	{
		$errors = array('-1' => '系统繁忙', '0' => '请求成功', '40001' => '获取access_token时AppSecret错误，或者access_token无效', '40002' => '不合法的凭证类型', '40003' => '不合法的OpenID', '40004' => '不合法的媒体文件类型', '40005' => '不合法的文件类型', '40006' => '不合法的文件大小', '40007' => '不合法的媒体文件id', '40008' => '不合法的消息类型', '40009' => '不合法的图片文件大小', '40010' => '不合法的语音文件大小', '40011' => '不合法的视频文件大小', '40012' => '不合法的缩略图文件大小', '40013' => '不合法的APPID', '40014' => '不合法的access_token', '40015' => '不合法的菜单类型', '40016' => '不合法的按钮个数', '40017' => '不合法的按钮个数', '40018' => '不合法的按钮名字长度', '40019' => '不合法的按钮KEY长度', '40020' => '不合法的按钮URL长度', '40021' => '不合法的菜单版本号', '40022' => '不合法的子菜单级数', '40023' => '不合法的子菜单按钮个数', '40024' => '不合法的子菜单按钮类型', '40025' => '不合法的子菜单按钮名字长度', '40026' => '不合法的子菜单按钮KEY长度', '40027' => '不合法的子菜单按钮URL长度', '40028' => '不合法的自定义菜单使用用户', '40029' => '不合法的oauth_code', '40030' => '不合法的refresh_token', '40031' => '不合法的openid列表', '40032' => '不合法的openid列表长度', '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符', '40035' => '不合法的参数', '40038' => '不合法的请求格式', '40039' => '不合法的URL长度', '40050' => '不合法的分组id', '40051' => '分组名字不合法', '41001' => '缺少access_token参数', '41002' => '缺少appid参数', '41003' => '缺少refresh_token参数', '41004' => '缺少secret参数', '41005' => '缺少多媒体文件数据', '41006' => '缺少media_id参数', '41007' => '缺少子菜单数据', '41008' => '缺少oauth code', '41009' => '缺少openid', '42001' => 'access_token超时', '42002' => 'refresh_token超时', '42003' => 'oauth_code超时', '43001' => '需要GET请求', '43002' => '需要POST请求', '43003' => '需要HTTPS请求', '43004' => '需要接收者关注', '43005' => '需要好友关系', '44001' => '多媒体文件为空', '44002' => 'POST的数据包为空', '44003' => '图文消息内容为空', '44004' => '文本消息内容为空', '45001' => '多媒体文件大小超过限制', '45002' => '消息内容超过限制', '45003' => '标题字段超过限制', '45004' => '描述字段超过限制', '45005' => '链接字段超过限制', '45006' => '图片链接字段超过限制', '45007' => '语音播放时间超过限制', '45008' => '图文消息超过限制', '45009' => '接口调用超过限制', '45010' => '创建菜单个数超过限制', '45015' => '回复时间超过限制', '45016' => '系统分组，不允许修改', '45017' => '分组名字过长', '45018' => '分组数量超过上限', '45056' => '创建的标签数过多，请注意不能超过100个', '45057' => '该标签下粉丝数超过10w，不允许直接删除', '45058' => '不能修改0/1/2这三个系统默认保留的标签', '45059' => '有粉丝身上的标签数已经超过限制', '45157' => '标签名非法，请注意不能和其他标签重名', '45158' => '标签名长度超过30个字节', '45159' => '非法的标签', '46001' => '不存在媒体数据', '46002' => '不存在的菜单版本', '46003' => '不存在的菜单数据', '46004' => '不存在的用户', '47001' => '解析JSON/XML内容错误', '48001' => 'api功能未授权', '50001' => '用户未授权该api', '40070' => '基本信息baseinfo中填写的库存信息SKU不合法。', '41011' => '必填字段不完整或不合法，参考相应接口。', '40056' => '无效code，请确认code长度在20个字符以内，且处于非异常状态（转赠、删除）。', '43009' => '无自定义SN权限，请参考开发者必读中的流程开通权限。', '43010' => '无储值权限,请参考开发者必读中的流程开通权限。', '43011' => '无积分权限,请参考开发者必读中的流程开通权限。', '40078' => '无效卡券，未通过审核，已被置为失效。', '40079' => '基本信息base_info中填写的date_info不合法或核销卡券未到生效时间。', '45021' => '文本字段超过长度限制，请参考相应字段说明。', '40080' => '卡券扩展信息cardext不合法。', '40097' => '基本信息base_info中填写的参数不合法。', '49004' => '签名错误。', '43012' => '无自定义cell跳转外链权限，请参考开发者必读中的申请流程开通权限。', '40099' => '该code已被核销。', '61005' => '缺少接入平台关键数据，等待微信开放平台推送数据，请十分钟后再试或是检查“授权事件接收URL”是否写错（index.php?c=account&amp;a=auth&amp;do=ticket地址中的&amp;符号容易被替换成&amp;amp;）', '61023' => '请重新授权接入该公众号');
		$code = strval($code);
		if ($code == '40001' || $code == '42001') {
			$cachekey = "accesstoken:{$this->account['acid']}";
			cache_delete($cachekey);
			return '微信公众平台授权异常, 系统已修复这个错误, 请刷新页面重试.';
		}
		if ($errors[$code]) {
			return $errors[$code];
		} else {
			return $errmsg;
		}
	}
}

function dayu_fans_form($field, $value = '')
{
	switch ($field) {
		case 'reside':
		case 'resideprovince':
		case 'residecity':
		case 'residedist':
			$html = dayu_form_field_district('reside', $value);
			break;
	}
	return $html;
}
function dayu_form_field_district($name, $values = array())
{
	$html = '';
	if (!defined('TPL_INIT_DISTRICT')) {
		$html .= '
		<script type="text/javascript">
			require(["jquery", "district"], function($, dis){
				$(".tpl-district-container").each(function(){
					var elms = {};
					elms.province = $(this).find(".tpl-province")[0];
					elms.city = $(this).find(".tpl-city")[0];
					elms.district = $(this).find(".tpl-district")[0];
					var vals = {};
					vals.province = $(elms.province).attr("data-value");
					vals.city = $(elms.city).attr("data-value");
					vals.district = $(elms.district).attr("data-value");
					dis.render(elms, vals, {withTitle: true});
				});
			});
		</script>';
		define('TPL_INIT_DISTRICT', true);
	}
	if (empty($values) || !is_array($values)) {
		$values = array('province' => '', 'city' => '', 'district' => '');
	}
	if (empty($values['province'])) {
		$values['province'] = '';
	}
	if (empty($values['city'])) {
		$values['city'] = '';
	}
	if (empty($values['district'])) {
		$values['district'] = '';
	}
	$html .= '
		<div class="tpl-district-container" style="display: block;">
			<div class="col-lg-4">
				<select name="' . $name . '[province]" data-value="' . $values['province'] . '" class="tpl-province">
				</select><i></i>
			</div>
			<div class="col-lg-4">
				<select name="' . $name . '[city]" data-value="' . $values['city'] . '" class="tpl-city">
				</select><i></i>
			</div>
			<div class="col-lg-4">
				<select name="' . $name . '[district]" data-value="' . $values['district'] . '" class="tpl-district">
				</select><i></i>
			</div>
		</div>';
	return $html;
}
function notice_init()
{
	global $_W;
	$acc = WeAccount::create();
	if (is_null($acc)) {
		return error(-1, '创建公众号操作对象失败');
	}
	return $acc;
}
function tpl_form_field_images($name, $value = '', $default = '', $options = array())
{
	global $_W;
	if (empty($default)) {
		$default = './resource/images/nopic.jpg';
	}
	$val = $default;
	if (!empty($value)) {
		$val = tomedia($value);
	}
	if (!empty($options['global'])) {
		$options['global'] = true;
	} else {
		$options['global'] = false;
	}
	if (empty($options['class_extra'])) {
		$options['class_extra'] = '';
	}
	if (isset($options['dest_dir']) && !empty($options['dest_dir'])) {
		if (!preg_match('/^\w+([\/]\w+)?$/i', $options['dest_dir'])) {
			exit('图片上传目录错误,只能指定最多两级目录,如: "store","store/d1"');
		}
	}
	$options['direct'] = true;
	$options['multiple'] = false;
	if (isset($options['thumb'])) {
		$options['thumb'] = !empty($options['thumb']);
	}
	$s = '';
	if (!defined('TPL_INIT_IMAGE')) {
		$s = '
		<script type="text/javascript">
			function showImageDialog(elm, opts, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					var img = ipt.parent().next().children();
					options = ' . str_replace('"', '\'', json_encode($options)) . ';
					util.image(val, function(url){
						if(url.url){
							if(img.length > 0){
								img.get(0).src = url.url;
							}
							ipt.val(url.attachment);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
						}
						if(url.media_id){
							if(img.length > 0){
								img.get(0).src = "";
							}
							ipt.val(url.media_id);
						}
					}, null, options);
				});
			}
			function deleteImage(elm){
				require(["jquery"], function($){
					$(elm).prev().attr("src", "./resource/images/nopic.jpg");
					$(elm).parent().prev().find("input").val("");
				});
			}
		</script>';
		define('TPL_INIT_IMAGE', true);
	}
	$s .= '
		<div class="input-group ' . $options['class_extra'] . '">
			<input type="text" name="' . $name . '" value="' . $value . '"' . ($options['extras']['text'] ? $options['extras']['text'] : '') . ' id="re-image" class="form-control" autocomplete="off">
			<span class="input-group-btn">
				<button class="btn btn-default" type="button" onclick="showImageDialog(this);">选择图片</button>
			</span>
		</div>
		<div class="col-xs-12 ' . $options['class_extra'] . '" style="margin-top:.5em;">
			<em class="close" style="position:absolute; top: 0px; right: -14px;font-size:18px;color:#333;" title="删除这张图片" onclick="deleteImage(this)">× 删除</em>
		</div>';
	return $s;
}