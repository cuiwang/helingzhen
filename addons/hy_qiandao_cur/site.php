<?php

//decode by QQ:32132884
defined('IN_IA') or die('Access Denied');
require_once IA_ROOT . '/addons/hy_qiandao_cur/inc/common.php';
require_once IA_ROOT . '/addons/hy_qiandao_cur/inc/user.php';
require_once IA_ROOT . '/addons/hy_qiandao_cur/inc/cryto.class.php';
class Hy_qiandao_curModuleSite extends WeModuleSite
{
	private $tab_name = "hy_qiandao_cur_log";
	private $tab_account_name = "hy_qiandao_cur_account";
	private $openid;
	public function checkuser()
	{
		global $_W, $_GPC;
		$qiaodaou = new hy_qiaodao_User();
		if ($this->is_weixin()) {
			$this->openid = $qiaodaou->getOpenid();
		} else {
			message("请到微信客户端访问");
		}
		$asdas = mc_fansinfo($this->openid);
	}
	function is_weixin()
	{
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
			return true;
		}
		return false;
	}
	public function doMobileIndex()
	{
		global $_W, $_GPC;
		$userinfo = $this->checkuser();
		$uniacid = $_W["uniacid"];
		$action = $_GPC["action"];
		$openid = $this->openid;
		$signtime = $_GPC["signtime"] ? $_GPC["signtime"] : date('Y-m-d', time());
		$createtime = $_GPC["signtime"] ? strtotime($_GPC["signtime"]) : time();
		$modulelist = uni_modules(false);
		$module = $modulelist['hy_qiandao_cur'];
		$settings_config = iunserializer($module['config']);
		$reword_order = iunserializer($settings_config['reword_order']);
		$cur_date = $_GPC["cur_date"] ? date('Y-m-d', strtotime($_GPC["cur_date"])) : date('Y-m-d', time());
		$userxinxi = mc_fansinfo($openid);
		$uid = $userxinxi['uid'];
		$member = pdo_fetch("select * from " . tablename('mc_members') . " where uniacid={$uniacid} and  uid={$uid} ");
		$binduser = pdo_fetch("select * from " . tablename('hy_qiandao_cur_account') . " where uniacid={$uniacid} and  openid='{$openid}'  ");
		if (empty($binduser)) {
			$insert = array("uniacid" => $uniacid, "uid" => $uid, "openid" => $openid, "nickname" => $member['nickname'], "mobile" => $member['mobile'], "avatar" => $member['avatar'], "createtime" => 0, "lasttime" => 0, "totalday" => 0, "continueday" => 0, "credit1" => 0, "credit2" => 0, "amount" => 0);
			pdo_insert($this->tab_account_name, $insert);
		}
		$mobile = $binduser['mobile'];
		if (empty($settings_config)) {
			message("请联系管理员设置签到参数");
		}
		if (empty($settings_config['dcqdzs'])) {
			message("请设置单次签到赠送金额");
		}
		$cdate = $_GPC['cdate'] ? $_GPC['cdate'] : '';
		$cdate = $cdate ? date('Y-m', strtotime($cdate)) : date('Y-m');
		$year = date('Y', strtotime($cdate));
		$month = date('m', strtotime($cdate));
		$day = date('j');
		$firstDay = date("w", mktime(0, 0, 0, $month, 1, $year));
		$daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));
		$tempDays = $firstDay + $daysInMonth;
		$weeksInMonth = ceil($tempDays / 7);
		for ($j = 0; $j < $weeksInMonth; $j++) {
			for ($i = 0; $i < 7; $i++) {
				$counter++;
				$week[$j][$i] = $counter;
				$week[$j][$i] -= $firstDay;
				if ($week[$j][$i] < 1 || $week[$j][$i] > $daysInMonth) {
					$week[$j][$i] = "";
				}
			}
		}
		$records_list = pdo_fetchall("select signtime  from " . tablename($this->tab_name) . " where uniacid={$uniacid} and  openid='{$openid}' and sharetype=0 order by signtime desc");
		$day_list = array();
		$cday_list = array();
		$qianday_list = array();
		foreach ($records_list as $key => $val) {
			$day_list[$key] = date("Y-m-d", strtotime($val['signtime']));
			$cur_month = date("Y-m", strtotime($val['signtime']));
			if ($cur_month == $cdate) {
				$cday_list[$key] = $day_list[$key];
			}
		}
		$week_counts = 0;
		for ($j = 0; $j < $weeksInMonth; $j++) {
			for ($i = 0; $i < 7; $i++) {
				foreach ($cday_list as $key => $val) {
					if ($week[$j][$i] == intval(date("d", strtotime($val)))) {
						$qianday_list[$week_counts] = $val;
						break;
					} else {
						$qianday_list[$week_counts] = "";
					}
				}
				$week_counts++;
			}
		}
		$ap_id = $settings_config["ap_id"];
		$key = $settings_config["key"];
		$gonggao = $settings_config["gonggao"];
		$signtype = $settings_config['signtype'];
		if (empty($signtype)) {
			$signtype = 1;
		}
		$linkurl = $settings_config["more"];
		$svr_code = "400119";
		$code = rand(111111, 999999);
		$curtime = time();
		$curtimedatestr = date("Y-m-d H:i:s", $curtime);
		$curtimedatestr = str_replace(" ", "", $curtimedatestr);
		$curtimedatestr = str_replace("-", "", $curtimedatestr);
		$curtimedatestr = str_replace(":", "", $curtimedatestr);
		$serialnumber = $curtimedatestr . $code;
		$expire_time = $curtime + 5 * 60;
		$expire_timestr = date("Y-m-d H:i:s", $expire_time);
		$expire_timestr = str_replace(" ", "", $expire_timestr);
		$expire_timestr = str_replace("-", "", $expire_timestr);
		$expire_timestr = str_replace(":", "", $expire_timestr);
		$responsetime = $expire_timestr;
		$cls_common = new cls_common();
		$counts = $binduser['totalday'];
		$prev_record = pdo_fetchcolumn("select signtime  from " . tablename($this->tab_name) . " where uniacid={$uniacid} and  openid='{$openid}' and sharetype=0 order by signtime desc limit 1");
		$prev_date = date("Y-m-d", strtotime($cur_date));
		if ($prev_date == date("Y-m-d", strtotime($prev_record))) {
			$prev_date = date("Y-m-d", strtotime($cur_date) + 3600 * 24);
		}
		$prev_continue = 0;
		$prev_continue = $cls_common->continue_day($prev_date, $day_list);
		$prev_continue = $prev_continue - 1;
		if ($binduser['continueday'] != $prev_continue) {
			$ret = pdo_update($this->tab_account_name, array('continueday' => $prev_continue), array('id' => $binduser['id']));
		}
		$day_continue = 0;
		$day_continue = $cls_common->continue_day($cur_date, $day_list);
		$cur_records_count = pdo_fetchcolumn("select   count(*)  from " . tablename($this->tab_name) . " where uniacid={$uniacid} and  openid='{$openid}' and sharetype=0 and  DATE_FORMAT(signtime,'%Y-%m-%d')='" . date("Y-m-d") . "'");
		if ($action == "add") {
			$err = array();
			$err['code'] = 0;
			if ($cur_records_count > 0) {
				$err['mag'] = "今天已经签过到";
				$err['code'] = 1;
				header('Content-Type:text/html; charset=utf-8');
				die(json_encode($err));
				die;
			}
			$amount = $settings_config['dcqdzs'];
			if (empty($counts)) {
				if ($settings_config['scqdzs']) {
					$amount = $amount + round($settings_config['scqdzs'], 2);
				}
				$insert = array("uniacid" => $uniacid, "openid" => $openid, "createtime" => $createtime, "signtime" => $signtime, "amount" => $amount, "signtype" => $signtype);
				pdo_insert($this->tab_name, $insert);
				$ret = pdo_update($this->tab_account_name, array('createtime' => $createtime), array('id' => $binduser['id']));
			} else {
				foreach ($reword_order as $val) {
					if ($day_continue % $val['day'] == 0) {
						$amount = $amount + $val['amount'];
					}
				}
				$insert = array("uniacid" => $uniacid, "openid" => $openid, "createtime" => $createtime, "signtime" => $signtime, "amount" => $amount, "signtype" => $signtype);
				pdo_insert($this->tab_name, $insert);
			}
			$fee = round($amount, 2);
			if ($signtype == 1) {
				$onrke = array('lasttime' => $createtime, 'totalday' => $binduser['totalday'] + 1, 'continueday' => $binduser['continueday'] + 1, 'credit2' => $binduser['credit2'] + $fee);
				$ret = pdo_update($this->tab_account_name, $onrke, array('id' => $binduser['id']));
				mc_credit_update($uid, 'credit2', $fee, array($uid, '[华易签到]签到奖励: ' . $fee . '元'));
				$err['fee'] = $fee . "元";
			} elseif ($signtype == 2) {
				$onrke = array('lasttime' => $createtime, 'totalday' => $binduser['totalday'] + 1, 'continueday' => $binduser['continueday'] + 1, 'credit1' => $binduser['credit1'] + $fee);
				$ret = pdo_update($this->tab_account_name, $onrke, array('id' => $binduser['id']));
				mc_credit_update($uid, 'credit1', $fee, array($uid, '[华易签到]签到奖励: ' . $fee . '积分'));
				$err['fee'] = $fee . "积分";
			}
			header('Content-Type:text/html; charset=utf-8');
			die(json_encode($err));
			die;
		}
		include $this->template('index');
	}
	public function doMobileRule()
	{
		$userinfo = $this->checkuser();
		$modulelist = uni_modules(false);
		$module = $modulelist['hy_qiandao_cur'];
		$settings_config = iunserializer($module['config']);
		$linkurl = $settings_config["more"];
		$content = nl2br($settings_config['ruletxt']);
		include $this->template('rule');
	}
	public function doMobileTip()
	{
		global $_W, $_GPC;
		$userinfo = $this->checkuser();
		$openid = $this->openid;
		$fee = $_GPC['fee'];
		$uniacid = $_W['uniacid'];
		$action = $_GPC["action"];
		if ($action == "share") {
			$err = array();
			$err['code'] = 0;
			$signtime = $_GPC["signtime"] ? $_GPC["signtime"] : date('Y-m-d', time());
			$createtime = $_GPC["signtime"] ? strtotime($_GPC["signtime"]) : time();
			$modulelist = uni_modules(false);
			$module = $modulelist['hy_qiandao_cur'];
			$settings_config = iunserializer($module['config']);
			$share = $settings_config['shareqdzs'];
			if ($share == 0) {
				$err['mag'] = "管理员未设置分享奖励";
				$err['code'] = 1;
				header('Content-Type:text/html; charset=utf-8');
				die(json_encode($err));
				die;
			}
			$sharetype = $settings_config['sharetype'];
			$counts = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tab_name) . ' WHERE uniacid = :uniacid   AND openid=  :openid and sharetype=:sharetype ', array(':uniacid' => $_W['uniacid'], ':openid' => $openid, ':sharetype' => 1));
			if ($counts != 0) {
				if ($sharetype == 1) {
					$err['mag'] = "您已获得过分享奖励";
					$err['code'] = 1;
					header('Content-Type:text/html; charset=utf-8');
					die(json_encode($err));
					die;
				} elseif ($sharetype == 2) {
					$records_count = pdo_fetchcolumn("select   count(*)  from " . tablename($this->tab_name) . " where uniacid={$uniacid} and  openid='{$openid}' and sharetype=1 and  DATE_FORMAT(signtime,'%Y-%m-%d')='" . date("Y-m-d") . "'");
					if (!empty($records_count)) {
						$err['mag'] = "您今天已获得分享奖励，请明天再来签到吧";
						$err['code'] = 1;
						header('Content-Type:text/html; charset=utf-8');
						die(json_encode($err));
						die;
					}
				}
			}
			$binduser = pdo_fetch("select * from " . tablename('hy_qiandao_cur_account') . " where uniacid={$uniacid} and  openid='{$openid}'  ");
			$signtype = $settings_config['signtype'];
			$fee = $settings_config['shareqdzs'];
			if ($signtype == 1) {
				$insert = array("uniacid" => $uniacid, "openid" => $openid, "createtime" => $createtime, "signtime" => $signtime, "amount" => $fee, "signtype" => $signtype, "sharetype" => 1);
				pdo_insert($this->tab_name, $insert);
				$onrke = array('lasttime' => $createtime, 'credit2' => $binduser['credit2'] + $fee);
				$ret = pdo_update($this->tab_account_name, $onrke, array('id' => $binduser['id']));
				mc_credit_update($uid, 'credit2', $fee, array($uid, '[华易签到]分享签到奖励: ' . $fee . '元'));
				$err['mag'] = "分享成功，您获得" . $fee . "元";
			} elseif ($signtype == 2) {
				$insert = array("uniacid" => $uniacid, "openid" => $openid, "createtime" => $createtime, "signtime" => $signtime, "amount" => $fee, "signtype" => $signtype, "sharetype" => 1);
				pdo_insert($this->tab_name, $insert);
				$onrke = array('credit1' => $binduser['credit1'] + $fee);
				$ret = pdo_update($this->tab_account_name, $onrke, array('id' => $binduser['id']));
				mc_credit_update($uid, 'credit1', $fee, array($uid, '[华易签到]分享签到奖励: ' . $fee . '积分'));
				$err['mag'] = "分享成功，您获得" . $fee . "积分";
			}
			header('Content-Type:text/html; charset=utf-8');
			die(json_encode($err));
			die;
		}
		include $this->template('tip');
	}
	public function doMobileAware()
	{
		global $_W, $_GPC;
		$userinfo = $this->checkuser();
		$modulelist = uni_modules(false);
		$module = $modulelist['hy_qiandao_cur'];
		$settings_config = iunserializer($module['config']);
		$follow = $settings_config["follow"];
		$openid = $this->openid;
		$userxinxi = mc_fansinfo($openid);
		$tip = array();
		$tip['code'] = 0;
		if ($follow == 1) {
			if (empty($userxinxi) || $userxinxi['follow'] == 0) {
				$tip['code'] = 1;
				$tip['msg'] = "如果您想签到，需要您关注我们的公众号，点击【确定】关注后再来签到吧";
			}
		}
		header('Content-Type:text/html; charset=utf-8');
		die(json_encode($tip));
		die;
		include $this->template('aware');
	}
	public function doMobileAction()
	{
		global $_W, $_GPC;
		$adfs = tomedia('qrcode_' . $_W['acid'] . '.jpg');
		$modulelist = uni_modules(false);
		$module = $modulelist['hy_qiandao_cur'];
		$settings_config = iunserializer($module['config']);
		$linkurl = $settings_config["more"];
		$content = $settings_config['ruletxt'];
		include $this->template('action');
	}
	public function doMobileLog()
	{
		global $_W, $_GPC;
		$userinfo = $this->checkuser();
		$modulelist = uni_modules(false);
		$module = $modulelist['hy_qiandao_cur'];
		$settings_config = iunserializer($module['config']);
		$linkurl = $settings_config["more"];
		$uniacid = $_W["uniacid"];
		$openid = $this->openid;
		$wheresql = " WHERE uniacid = :uniacid   AND openid=  :openid ";
		$param = array(':uniacid' => $_W['uniacid'], ':openid' => $openid);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->tab_name) . $wheresql . ' order by createtime desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $param);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tab_name) . $wheresql, $param);
		$pager = pagination($total, $pindex, $psize);
		include $this->template('log');
	}
	public function doMobileRank()
	{
		global $_W, $_GPC;
		$userinfo = $this->checkuser();
		$uniacid = $_W["uniacid"];
		$openid = $this->openid;
		$wheresql = " WHERE uniacid = :uniacid ";
		$param = array(':uniacid' => $_W['uniacid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 200;
		$list = pdo_fetchall("select  * from " . tablename($this->tab_account_name) . $wheresql . " order by totalday desc,continueday desc LIMIT 20", $param);
		include $this->template('rank');
	}
	public function doWebqiandao()
	{
		global $_W, $_GPC;
		$tpltype = $_GPC['tpltype'];
		include $this->template('tpl');
	}
	public function doWebLog()
	{
		global $_W, $_GPC;
		$uniacid = $_W["uniacid"];
		$openid = $this->openid;
		$wheresql = " WHERE uniacid = :uniacid ";
		$param = array(':uniacid' => $_W['uniacid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 200;
		$list = pdo_fetchall("select  * from " . tablename($this->tab_account_name) . $wheresql . " order by lasttime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $param);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tab_account_name) . $wheresql, $param);
		$pager = pagination($total, $pindex, $psize);
		include $this->template('log');
	}
	public function doWebLogcount()
	{
		global $_W, $_GPC;
		$uniacid = $_W["uniacid"];
		$openid = $_GPC['openid'];
		$wheresql = " WHERE uniacid = :uniacid ";
		$param = array(':uniacid' => $_W['uniacid']);
		if (!empty($openid)) {
			$wheresql .= " AND openid = :openid ";
			$param['openid'] = $openid;
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 200;
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->tab_name) . $wheresql . ' order by createtime desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $param);
		foreach ($list as $key => $val) {
			$acc = pdo_fetch("select  * from " . tablename($this->tab_account_name) . " where  openid='" . $val['openid'] . "'   and uniacid=" . $val['uniacid']);
			$list[$key]['nickname'] = $acc['nickname'];
			$list[$key]['mobile'] = $acc['mobile'];
		}
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tab_name) . $wheresql, $param);
		$pager = pagination($total, $pindex, $psize);
		include $this->template('logcount');
	}
}