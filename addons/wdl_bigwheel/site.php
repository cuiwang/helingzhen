<?php

/**
 * 大转盘抽奖模块
 *
 */
defined('IN_IA') or exit('Access Denied');

class wdl_bigwheelModuleSite extends WeModuleSite {

	public $tablename = 'bigwheel_reply';
	public $tablefans = 'bigwheel_fans';

	public function getItemTiles() {
		global $_W;
		$articles = pdo_fetchall("SELECT id,rid, title FROM " . tablename('bigwheel_reply') . " WHERE weid = '{$_W['uniacid']}'");
		if (!empty($articles)) {
			foreach ($articles as $row) {
				$urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('index', array('id' => $row['rid'])));
			}
			return $urls;
		}
	}

	public function doMobileindex() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		if (empty($id)) {
			message('抱歉，参数错误！', '', 'error');
		}
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));
		if ($reply == false) {
			message('抱歉，活动已经结束，下次再来吧！', '', 'error');
		}

		//获得关键词
		$keyword = pdo_fetch("select content from ".tablename('rule_keyword')." where rid=:rid and type=1",array(":rid"=>$id));
		$reply['keyword']=  $keyword['content'];

		if (empty($_W['fans']['follow']) || $_GPC['share'] == 1) {
			$isshare = 1;
			$running = false;
			$msg = '请先关注公众号。';
		} else {
			checkauth();
			$fansID = $_W['member']['uid'];
			$from_user = $_W['fans']['from_user'];
			$fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE rid = " . $id . " and fansID='" . $fansID . "' and from_user='" . $from_user . "'");

			if ($fans == false) {
				$insert = array(
					'rid' => $id,
					'fansID' => $fansID,
					'from_user' => $from_user,
					'todaynum' => 0,
					'totalnum' => 0,
					'awardnum' => 0,
					'createtime' => time(),
				);
				$temp = pdo_insert($this->tablefans, $insert);
				if ($temp == false) {
					message('抱歉，刚才操作数据失败！', '', 'error');
				}
				//增加人数，和浏览次数
				pdo_update($this->tablename, array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
			} else {
				//增加浏览次数
				pdo_update($this->tablename, array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
			}
			//判断是否获奖
			$award = pdo_fetchall("SELECT * FROM " . tablename('bigwheel_award') . " WHERE weid=" . $_W['uniacid'] . " and rid = " . $id . " and fansID='" . $fansID . "' and from_user='" . $from_user . "' order by id desc");
			if ($award != false) {
				$awardone = $award[0];
			}
			$running = true;
			//判断是否可以刮刮
			if ($awardone && empty($fans['tel'])) {
				$running = false;
				$msg = '请先填写用户资料';
			}

			//判断用户抽奖次数
			$nowtime = mktime(0, 0, 0);
			if ($fans['last_time'] < $nowtime) {
				$fans['todaynum'] = 0;
			}
			//判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
			if ($running && $reply['starttime'] > time()) {
				$running = false;
				$msg = '活动还没有开始呢！';
			}
			//判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
			if ($running && $reply['endtime'] < time()) {
				$running = false;
				$msg = '活动已经结束了，下次再来吧！';
			}
			//判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
			if ($running && $fans['totalnum'] >= $reply['number_times'] && $reply['number_times'] > 0) {
				$running = false;
				$msg = '您已经超过抽奖总限制次数，无法抽奖了!';
			}
			//判断当日是否超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
			if ($running && $fans['todaynum'] >= $reply['most_num_times'] && $reply['most_num_times'] > 0) {
				$running = false;
				$msg = '您已经超过今天的抽奖次数，明天再来吧!';
			}
		}

		$cArr = array('one', 'two', 'three', 'four', 'five', 'six');
		$awardstr = '';
		foreach ($cArr as $c) {
			if (empty($reply['c_type_' . $c])) {
				break;
			}
			$awardstr.='<p>' . $reply['c_type_' . $c] . '：' . $reply['c_name_' . $c];
			if ($reply['show_num'] == 1) {
				$awardstr.='  奖品数量： ' . intval($reply['c_num_' . $c] - $reply['c_draw_' . $c]);
			}
			$awardstr.='</p>';
		}

		if ($reply['most_num_times'] > 0 && $reply['number_times'] > 0) {
			$detail = '本次活动共可以转' . $reply['number_times'] . '次，每天可以转 ' . intval($reply['most_num_times']) . ' 次! 你共已经转了 <span id="totalcount">' . intval($fans['totalnum']) . '</span> 次 ，今天转了<span id="count">' . intval($fans['todaynum']). '</span> 次.';
			$Tcount = $reply['most_num_times'];
			$Lcount = $reply['most_num_times'] - $fans['todaynum'];
		} elseif ($reply['most_num_times'] > 0) {
			$detail = '本次活动每天可以转 ' . $reply['most_num_times'] . ' 次卡!你共已经转了 <span id="totalcount">' . intval($fans['totalnum']) . '</span> 次 ，今天转了<span id="count">' . intval($fans['todaynum']) . '</span> 次.';
			$Tcount = $reply['most_num_times'];
			$Lcount = $reply['most_num_times'] - $fans['todaynum'];
		} elseif ($reply['number_times'] > 0) {
			$detail = '本次活动共可以转' . $reply['number_times'] . '次卡!你共已经转了 <span id="totalcount">' . intval($fans['totalnum']) . '</span> 次。';
			$Tcount = $reply['number_times'];
			$Lcount = $reply['number_times'] - $fans['totalnum'];
		} else {
			$detail = '您很幸运，本次活动没有任何限制，您可以随意转!你共已经转了 <span id="totalcount">' . intval($fans['totalnum']) . '</span> 次。';
			$Tcount = 10000;
			$Lcount = 10000;
		}

		$detail .=  htmlspecialchars_decode($reply['content']);
		if (empty($reply['sn_rename'])) {
			$reply['sn_rename'] = 'SN码';
		}
		if (empty($reply['tel_rename'])) {
			$reply['tel_rename'] = '手机号';
		}
		if (empty($reply['repeat_lottery_reply'])) {
			$reply['repeat_lottery_reply'] = '亲，继续努力哦！';
		}
		if (empty($fans['todaynum'])) {
			$fans['todaynum'] = 0;
		}
		if (empty($fans['totalnum'])) {
			$fans['totalnum'] = 0;
		}

		//分享信息
		$sharelink = empty($reply['share_url']) ? ($_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id, 'name' => 'bigwheel', 'share' => 1))) : $reply['share_url'];
		$sharelink = str_replace('./', '', $sharelink);
		$sharetitle = empty($reply['share_title']) ? '欢迎参加大转盘活动' : $reply['share_title'];
		$sharedesc = empty($reply['share_desc']) ? '亲，欢迎参加大转盘抽奖活动，祝您好运哦！！' : str_replace("\r\n"," ", $reply['share_desc']);
		$shareimg = tomedia($reply['start_picurl']);

		include $this->template('index');
	}

	public function doMobilegetaward() {
		global $_GPC, $_W;

		$id = intval($_GPC['id']);
		//开始抽奖咯
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));

		if (empty($reply)) {
			$this->message(array('success' => 2, 'msg' => '活动已经结束了，下次再来吧！...'), '');
		}

		if($reply['isshow'] != 1){
			//活动已经暂停,请稍后...
			$this->message(array("success"=>2, "msg"=>'活动暂停，请稍后...'),"");
		}

		if ($reply['starttime'] > time()) {
			$this->message(array("success"=>2, "msg"=>'活动还没有开始呢，请等待...'),"");
		}

		$endtime = $reply['endtime'] + 68399;
		if ($endtime < time()) {
			$this->message(array("success"=>2, "msg"=>'活动已经结束了，下次再来吧！'),"");
		}

		//先判断有没有资格领取
		if (empty($_W['fans']['follow'])) {
			$this->message(array("success"=>2, "msg"=>'请先关注公众号再来参与活动！详情请查看参与方法。'),"");
		}
		$fansID = $_W['member']['uid'];
		$from_user = $_W['fans']['from_user'];


		//第一步，判断有没有已经领取奖品了，如果领取了，则不能再领取了
		$fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE rid = " . $id . " and fansID='" . $fansID . "' and from_user='" . $from_user . "'");
		if ($fans == false) {
			//不存在false的情况，如果是false，则表明是非法
			$fans = array(
				'rid' => $id,
				'fansID' => $fansID,
				'from_user' => $_W['fans']['from_user'],
				'todaynum' => 0,
				'totalnum' => 0,
				'awardnum' => 0,
				'createtime' => time(),
			);
			pdo_insert($this->tablefans, $fans);
			$fans['id'] = pdo_insertid();
		}

		//更新当日次数
		$nowtime = mktime(0, 0, 0);
		if ($fans['last_time'] < $nowtime) {
			$fans['todaynum'] = 0;
		}
		//判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
		if ($fans['totalnum'] >= $reply['number_times'] && $reply['number_times'] > 0) {
			// $this->message('', '超过抽奖总限制次数');
			$this->message(array("success"=>2, "msg"=>'您超过抽奖总次数了，不能抽奖了!'),"");
		}
		//判断当日是否超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
		if ($fans['todaynum'] >= $reply['most_num_times'] && $reply['most_num_times'] > 0) {
			//$this->message('', '超过当日限制次数');
			$this->message(array("success"=>2, "msg"=>'您超过当日抽奖次数了，不能抽奖了!'),"");
		}

		$last_time = strtotime( date("Y-m-d",mktime(0,0,0)));
		//当天抽奖次数
		pdo_update('bigwheel_fans', array('todaynum' => $fans['todaynum'] + 1,'last_time'=>$last_time), array('id' => $fans['id']));
		//总抽奖次数
		pdo_update('bigwheel_fans', array('totalnum' => $fans['totalnum'] + 1), array('id' => $fans['id']));

		$gifts = array(
				"one" => array("name" => $reply['c_name_one'], "type" => $reply['c_type_one'], "probalilty" => $reply['c_rate_one'], "total" => $reply['c_num_one'], "draw" => $reply['c_draw_one']),
				"two" => array("name" => $reply['c_name_two'], "type" => $reply['c_type_two'], "probalilty" => $reply['c_rate_two'], "total" => $reply['c_num_two'], "draw" => $reply['c_draw_two']),
				"three" => array("name" => $reply['c_name_three'], "type" => $reply['c_type_three'], "probalilty" => $reply['c_rate_three'], "total" => $reply['c_num_three'], "draw" => $reply['c_draw_three']),
				"four" => array("name" => $reply['c_name_four'], "type" => $reply['c_type_four'], "probalilty" => $reply['c_rate_four'], "total" => $reply['c_num_four'], "draw" => $reply['c_draw_four']),
				"five" => array("name" => $reply['c_name_five'], "type" => $reply['c_type_five'], "probalilty" => $reply['c_rate_five'], "total" => $reply['c_num_five'], "draw" => $reply['c_draw_five']),
				"six" => array("name" => $reply['c_name_six'], "type" => $reply['c_type_six'], "probalilty" => $reply['c_rate_six'], "total" => $reply['c_num_six'], "draw" => $reply['c_draw_six']),
		);

		$awards = array();
		foreach ($gifts as $key => $gift) {
			if ($gift['total'] <= $gift['draw']) {
				unset($gifts[$key]);
				continue;
			}
			if (empty($gift['probalilty'])) {
				unset($gifts[$key]);
				continue;
			}
			$gifts[$key]['random'] = mt_rand(1, 100 / $gift['probalilty']);
			if (mt_rand(1, 100 / $gift['probalilty']) == mt_rand(1, 100 / $gift['probalilty'])) {
				$gift['type'] = $key;
				$awards[] = $gift;
			}
		}

		$prizetype = array();
		if (count($awards) > 0){
			mt_srand((double) microtime() * 1000000);
			$randid = mt_rand(0, count($awards) - 1);
			$prizetype = $awards[$randid];
		}
		if ($reply['award_times'] == '0') {
			$reply['award_times'] = $fans['awardnum'] + 1;
		}
		if( (!empty($prizetype)) && ($fans['awardnum'] < $reply['award_times']) ){
			//中奖
			$sn = random(16);
			pdo_update('bigwheel_reply', array('c_draw_' . $prizetype['type'] => $reply['c_draw_' . $prizetype['type']] + 1), array('id' => $reply['id']));
			//保存sn到award中
			$insert = array(
				'weid' => $_W['uniacid'],
				'rid' => $id,
				'fansID' => $fansID,
				'from_user' => $_W['fans']['from_user'],
				'name' => $reply['c_type_' . $prizetype['type']],
				'description' => $prizetype['name'],
				'prizetype' => $prizetype['type'],
				'award_sn' => $sn,
				'createtime' => time(),
				'status' => 1,
			);
			$temp = pdo_insert('bigwheel_award', $insert);
			//保存中奖人信息到fans中
			pdo_update('bigwheel_fans', array('awardnum' => $fans['awardnum'] + 1), array('id' => $fans['id']));

			$statusCode = array('one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6);
			$data = array(
				'name' => $reply['c_type_' . $prizetype['type']],
				'award' => $prizetype['name'],
				'sn' => $sn,
				'success' => 1,
				'prizetype' => $statusCode[$prizetype['type']]
			);
			$this->message($data);
		}
		$this->message();
	}

	public function doMobilesettel() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$fansID =$_W['member']['uid'];
		$from_user = $_W['fans']['from_user'];
		$fans = pdo_fetch("SELECT id FROM " . tablename($this->tablefans) . " WHERE rid = " . $id . " and fansID=" . $fansID . " and from_user='" . $from_user . "'");
		if ($fans == false) {
			$data = array(
				'success' => 0,
				'msg' => '保存数据错误！',
			);
		} else {
			$temp = pdo_update($this->tablefans, array('tel' => $_GPC['tel']), array('rid' => $id, 'fansID' => $fansID));

			if ($temp === false) {

				$data = array(
					'success' => 0,
					'msg' => '保存数据错误！',
				);
			} else {
				load()->model('mc');
				mc_update($fansID, array('mobile' => $_GPC['tel']));
				$data = array(
					'success' => 1,
					'msg' => '成功提交数据',
				);
			}
		}
		echo json_encode($data);
	}

	//json
	public function message($_data = '', $_msg = '') {
		if (!empty($_data['succes']) && $_data['success'] != 2) {
			$this->setfans();
		}
		if (empty($_data)) {
			$_data = array(
				'name' => "谢谢参与",
				'success' => 0,
			);
		}
		if (!empty($_msg)) {
			//$_data['error']='invalid';
			$_data['msg'] = $_msg;
		}
		die(json_encode($_data));
	}

	public function setfans() {
		global $_GPC, $_W;
		//增加fans次数
		//记录用户信息
		$id = intval($_GPC['id']);
		$fansID = $_W['fans']['id'];
		if (empty($fansID) || empty($id))
			return;
		$fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE rid = " . $id . " and fansID=" . $fansID . "");
		$nowtime = mktime(0, 0, 0);
		if ($fans['last_time'] < $nowtime) {
			$fans['todaynum'] = 0;
		}
		$update = array(
			'todaynum' => $fans['todaynum'] + 1,
			'totalnum' => $fans['totalnum'] + 1,
			'last_time' => time(),
		);
		pdo_update($this->tablefans, $update, array('id' => $fans['id']));
	}





	public function doWebManage() {
		global $_GPC, $_W;

		load()->model('reply');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "uniacid = :weid AND `module` = :module";
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':module'] = 'wdl_bigwheel';

		if (isset($_GPC['keywords'])) {
			$sql .= ' AND `name` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keywords']}%";
		}
		$list = reply_search($sql, $params, $pindex, $psize, $total);
		$pager = pagination($total, $pindex, $psize);

		if (!empty($list)) {
			foreach ($list as &$item) {
				$condition = "`rid`={$item['id']}";
				$item['keywords'] = reply_keywords_search($condition);
				$bigwheel = pdo_fetch("SELECT fansnum, viewnum,starttime,endtime,isshow FROM " . tablename('bigwheel_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
				$item['fansnum'] = $bigwheel['fansnum'];
				$item['viewnum'] = $bigwheel['viewnum'];
				$item['starttime'] = date('Y-m-d H:i', $bigwheel['starttime']);
				$endtime = $bigwheel['endtime'] + 86399;
				$item['endtime'] = date('Y-m-d H:i', $endtime);
				$nowtime = time();
				if ($bigwheel['starttime'] > $nowtime) {
					$item['status'] = '<span class="label label-warning">未开始</span>';
					$item['show'] = 1;
				} elseif ($endtime < $nowtime) {
					$item['status'] = '<span class="label label-default ">已结束</span>';
					$item['show'] = 0;
				} else {
					if ($bigwheel['isshow'] == 1) {
						$item['status'] = '<span class="label label-success">已开始</span>';
						$item['show'] = 2;
					} else {
						$item['status'] = '<span class="label label-default ">已暂停</span>';
						$item['show'] = 1;
					}
				}
				$item['isshow'] = $bigwheel['isshow'];
			}
		}
		include $this->template('manage');
	}

	public function doWebDelete() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['uniacid']));
		if (empty($rule)) {
			message('抱歉，要修改的规则不存在或是已经被删除！');
		}
		if (pdo_delete('rule', array('id' => $rid))) {
			pdo_delete('rule_keyword', array('rid' => $rid));
			//删除统计相关数据
			pdo_delete('stat_rule', array('rid' => $rid));
			pdo_delete('stat_keyword', array('rid' => $rid));
			//调用模块中的删除
			$module = WeUtility::createModule($rule['module']);
			if (method_exists($module, 'ruleDeleted')) {
				$module->ruleDeleted($rid);
			}
		}


		message('规则操作成功！', referer(), 'success');
	}

	public function doWebDeleteAll() {
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $rid) {
			$rid = intval($rid);
			if ($rid == 0)
				continue;
			$rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['uniacid']));
			if (empty($rule)) {
				$this->webmessage('抱歉，要修改的规则不存在或是已经被删除！');
			}
			if (pdo_delete('rule', array('id' => $rid))) {
				pdo_delete('rule_keyword', array('rid' => $rid));
				//删除统计相关数据
				pdo_delete('stat_rule', array('rid' => $rid));
				pdo_delete('stat_keyword', array('rid' => $rid));
				//调用模块中的删除
				$module = WeUtility::createModule($rule['module']);
				if (method_exists($module, 'ruleDeleted')) {
					$module->ruleDeleted($rid);
				}
			}
		}
		$this->webmessage('规则操作成功！', '', 0);
	}

	public function doWebAwardlist() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		if (empty($rid)) {
			message('抱歉，传递的参数错误！', '', 'error');
		}
		$where = '';
		$params = array(':rid' => $rid, ':weid' => $_W['uniacid']);
		if (!empty($_GPC['status'])) {
			$where.=' and a.status=:status';
			$params[':status'] = $_GPC['status'];
		}
		if (!empty($_GPC['keywords'])) {
			if (strlen($_GPC['keywords']) == 11 && is_numeric($_GPC['keywords'])) {
				$members = pdo_fetchall("SELECT uid FROM ".tablename('mc_members')." WHERE mobile = :mobile", array(':mobile' => $_GPC['keywords']), 'uid');
				if(!empty($members)){
					$fans = pdo_fetchall("SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uid in ('".implode("','", array_keys($members))."')", array(), 'openid');
					if(!empty($fans)){
						$where .= " AND a.from_user IN ('".implode("','", array_keys($fans))."')";
					}
				}
			} else {
				$where.=' and (a.award_sn like :keywords)';
				$params[':keywords'] = "%{$_GPC['keywords']}%";
			}
		}
		$total = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('bigwheel_award') . " a WHERE a.rid = :rid and a.weid=:weid " . $where . "", $params);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 12;
		$pager = pagination($total, $pindex, $psize);
		$start = ($pindex - 1) * $psize;
		$limit = " LIMIT {$start},{$psize}";

		$sql = 'SELECT `a`.*, `f`.`nickname` FROM ' . tablename('bigwheel_award') . ' AS `a` LEFT JOIN ' .
				tablename('mc_mapping_fans') . ' AS `f` ON `a`.`from_user` = `f`.`openid` WHERE `a`.`rid` = :rid AND
				`f`.`uniacid` = :weid ' . $where . ' ORDER BY `a`.`id` DESC ' . $limit;
		$list = pdo_fetchall($sql, $params);

		//一些参数的显示
		$num1 = pdo_fetchcolumn("SELECT total_num FROM " . tablename($this->tablename) . " WHERE rid = :rid", array(':rid' => $rid));
		$num2 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('bigwheel_award') . " WHERE rid = :rid and status=1", array(':rid' => $rid));
		$num3 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('bigwheel_award') . " WHERE rid = :rid and status=2", array(':rid' => $rid));

		include $this->template('awardlist');
	}

	public function doWebDownload() {
		require_once 'download.php';
	}

	public function doWebSetshow() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$isshow = intval($_GPC['isshow']);

		if (empty($rid)) {
			message('抱歉，传递的参数错误！', '', 'error');
		}
		$temp = pdo_update('bigwheel_reply', array('isshow' => $isshow), array('rid' => $rid));
		message('状态设置成功！', referer(), 'success');
	}

	public function doWebSetstatus() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$status = intval($_GPC['status']);
		if (empty($id)) {
			message('抱歉，传递的参数错误！', '', 'error');
		}
		$p = array('status' => $status);
		if ($status == 2) {
			$p['consumetime'] = TIMESTAMP;
		}
		$temp = pdo_update('bigwheel_award', $p, array('id' => $id, 'weid' => $_W['uniacid']));
		if ($temp == false) {
			message('抱歉，刚才操作数据失败！', '', 'error');
		} else {
			message('状态设置成功！', $this->createWebUrl('awardlist',array('rid'=>$_GPC['rid'])), 'success');
		}
	}

	public function doWebGetphone() {
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$fans = $_GPC['fans'];
		$tel = pdo_fetchcolumn("SELECT tel FROM " . tablename('bigwheel_fans') . " WHERE rid = " . $rid . " and  from_user='" . $fans . "'");
		if ($tel == false) {
			echo '没有登记';
		} else {
			echo $tel;
		}
	}

	public function webmessage($error, $url = '', $errno = -1) {
		$data = array();
		$data['errno'] = $errno;
		if (!empty($url)) {
			$data['url'] = $url;
		}
		$data['error'] = $error;
		echo json_encode($data);
		exit;
	}
}
