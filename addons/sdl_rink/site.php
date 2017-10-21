<?php

defined('IN_IA') or die('Access Denied');
class Sdl_rinkModuleSite extends WeModuleSite
{
	public $table_reply = 'sdl_rink_reply';
	public $table_record = 'sdl_rink_record';
	public $table_fans = 'sdl_rink_fans';
	public function starts($ruleid, $weid)
	{
		global $_W;
		cache_delete('tr');
		cache_delete('phoneUser');
		$weid = $_W['uniacid'];
		$sql = "select * from " . tablename($this->table_reply) . " where rid=:id and weid=:weid";
		$pp = array(":id" => $ruleid, ":weid" => $weid);
		$tr = pdo_fetch($sql, $pp);
		cache_write('tr', $tr);
		if ($tr) {
			$id = $tr['id'];
			$sql = " UPDATE " . tablename($this->table_reply) . " SET `vivt` = vivt+1 WHERE `id` = 	{$id}";
			pdo_query($sql);
		}
		return $tr;
	}
	public function doMobileIndex()
	{
		global $_W, $_GPC;
		$ruleid = intval($_GPC['id']);
		$tr = $this->starts($ruleid, $weid);
		$weid = $_W['uniacid'];
		if ($tr == false) {
			message('活动不存在', '');
		}
		$this->check_bowser();
		$this->check_follow($tr);
		if ($tr['starttime'] > TIMESTAMP) {
			message('活动未开始，请等待...');
		}
		if ($tr['endtime'] < TIMESTAMP) {
			message('活动已结束,点击查看排行榜!', $this->createMobileUrl('rank', array('id' => $ruleid), true));
		}
		$openid = $_W['openid'];
		$sqd = "select * from " . tablename($this->table_fans) . " where rid=:id and weid=:weid  and from_user=:op ";
		$qo = array(":id" => $ruleid, ":weid" => $weid, ':op' => $openid);
		$psd = pdo_fetch($sqd, $qo);
		if ($psd['isblack'] == 1) {
			message('用户在黑名单中...');
		}
		$isnotuser = "no";
		if ($tr['isnow'] == 1) {
			if ($psd == FALSE || empty($psd['username']) || empty($psd['tel'])) {
				$isnotuser = "yes";
			}
		}
		if (strstr($tr['shared_desc'], "nickname")) {
			$tr['shared_desc'] = str_replace("nickname", $_W['fans']['nickname'], $tr['shared_desc']);
		}
		if (strstr($tr['shared_title'], "nickname")) {
			$tr['shared_title'] = str_replace("nickname", $_W['fans']['nickname'], $tr['shared_title']);
		}
		include $this->template('index');
	}
	public function check_bowser()
	{
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
		}
	}
	public function check_follow($tr)
	{
		global $_W, $_GPC;
		load()->model('mc');
		$openid = $_W['openid'];
		if ($tr['isneedfollow'] == 1) {
			if (empty($openid)) {
				message("必须先关注本公众号才能进行游戏 ", $tr['follow_url'], 'success');
			}
			$fans = pdo_fetch(" SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE openid='" . $openid . "' AND uniacid=" . $_W['uniacid'] . " ");
			if (empty($fans) || $fans['follow'] == 0) {
				message(" 必须先关注本公众号才能进行游戏 ", $tr['follow_url'], 'success');
			}
			if (empty($_W['fans']['nickname'])) {
				mc_oauth_userinfo();
			}
		}
	}
	public function doMobileGetjp()
	{
		global $_W, $_GPC;
		$info['num'] = 1001;
		$info['msg'] = "参数错误";
		$rid = $_GPC['rid'];
		$username = $_GPC['name'];
		$tel = $_GPC['tel'];
		$opeind = $_W['openid'];
		if ($_W['isajax'] && $_W['openid'] && $username && $tel) {
			$weid = $_W['uniacid'];
			$sqd = "select * from " . tablename($this->table_reply) . " where rid=:id and weid=:weid";
			$qo = array(":id" => $rid, ":weid" => $weid);
			$pp = pdo_fetch($sqd, $qo);
			if ($pp) {
				$sqd = "select * from " . tablename($this->table_fans) . " where rid=:id and weid=:weid and from_user=:op ";
				$qo[':op'] = $opeind;
				$pssr = pdo_fetch($sqd, $qo);
				$info['oim'] = $pssr;
				if ($pssr) {
					$info['num'] = 1002;
					$info['msg'] = "请勿重复提交数据";
				} else {
					$ddp['rid'] = $rid;
					$ddp['weid'] = $weid;
					$ddp['from_user'] = $opeind;
					$ddp['nickname'] = $_W['fans']['tag']['nickname'];
					$ddp['headimgurl'] = $_W['fans']['tag']['avatar'];
					$ddp['username'] = $username;
					$ddp['tel'] = $tel;
					$ddp['lastplay_date'] = TIMESTAMP;
					$ddp['lastshare_date'] = TIMESTAMP;
					$account = pdo_insert($this->table_fans, $ddp);
					$info['num'] = 1003;
					$info['msg'] = "提交成功";
				}
			} else {
				$info['num'] = 1002;
				$info['msg'] = "活动不存在";
			}
		}
		echo json_encode($info);
		die;
	}
	public function doMobileJoin()
	{
		global $_W, $_GPC;
		$arr = array();
		$dd = array();
		$ddp = array();
		$info['num'] = 1001;
		$info['msg'] = "参数错误";
		$info['post'] = $_GPC;
		$rid = $_GPC['rid'];
		$tr = cache_load('tr');
		if ($_W['isajax'] && $_W['openid']) {
			$info['isajax'] = "yse";
			$weid = $_W['uniacid'];
			$opeind = $_W['openid'];
			$sqd = "select * from " . tablename($this->table_reply) . " where rid=:id and weid=:weid";
			$qo = array(":id" => $rid, ":weid" => $weid);
			$pp = pdo_fetch($sqd, $qo);
			if ($pp) {
				$dd['score'] = $_GPC['fs'];
				$dd['from_user'] = $opeind;
				$dd['playtime'] = TIMESTAMP;
				$dd['rid'] = $rid;
				$dd['weid'] = $weid;
				$account = pdo_insert($this->table_record, $dd);
				$sqd = "select * from " . tablename($this->table_fans) . " where rid=:id and weid=:weid and from_user=:op ";
				$qo[':op'] = $opeind;
				$pssr = pdo_fetch($sqd, $qo);
				$info['oim'] = $pssr;
				if ($pssr) {
					if ($pssr['best_score'] < $_GPC['fs']) {
						$ddp['best_score'] = $_GPC['fs'];
						$ddp['lastshare_date'] = TIMESTAMP;
						$info['pp'] = $ddp;
						$ppqs = pdo_update($this->table_fans, $ddp, array('id' => $pssr['id']));
						if ($ppqs) {
							$info['num'] = 1005;
							$info['msg'] = "打破记录......";
						} else {
							$info['num'] = 1006;
							$info['msg'] = "更新错误......";
						}
					} else {
						$info['num'] = 1005;
						$info['msg'] = "继续努力......";
					}
				} else {
					$ddp['rid'] = $rid;
					$ddp['weid'] = $weid;
					$ddp['from_user'] = $opeind;
					$ddp['nickname'] = $_W['fans']['tag']['nickname'];
					$ddp['headimgurl'] = $_W['fans']['tag']['avatar'];
					$ddp['best_score'] = $_GPC['fs'];
					$ddp['lastplay_date'] = TIMESTAMP;
					$ddp['lastshare_date'] = TIMESTAMP;
					$account = pdo_insert($this->table_fans, $ddp);
					$info['num'] = 1003;
					$info['msg'] = "创造记录......";
				}
			} else {
				$info['num'] = 1002;
				$info['msg'] = "活动不存在";
			}
		}
		echo json_encode($info);
		die;
	}
	public function doMobileGz()
	{
		global $_W, $_GPC;
		$ruleid = $_GPC['id'];
		$weid = $_W['uniacid'];
		$opeind = $_W['openid'];
		$sql = "select * from " . tablename($this->table_reply) . " where rid=:id and weid=:weid";
		$pp = array(":id" => $ruleid, ":weid" => $weid);
		$tr = pdo_fetch($sql, $pp);
		if ($tr) {
			$this->check_bowser();
			$this->check_follow($tr);
			include $this->template('M3');
		} else {
			message('活动不存在', '');
		}
	}
	public function doMobileRank()
	{
		global $_W, $_GPC;
		$ruleid = $_GPC['id'];
		$weid = $_W['uniacid'];
		$opeind = $_W['openid'];
		$sql = "select * from " . tablename($this->table_reply) . " where rid=:id and weid=:weid";
		$pp = array(":id" => $ruleid, ":weid" => $weid);
		$tr = pdo_fetch($sql, $pp);
		$this->check_bowser();
		$this->check_follow($tr);
		if ($tr) {
			$sqd = "select mm.* from (select @counter:=@counter+1 AS Rank,u.* from  " . tablename($this->table_fans) . " as u ,(SELECT @counter:=0) AS t  where u.rid=:id and u.weid=:weid and u.isblack=0 ORDER by u.best_score DESC )  mm WHERE mm.from_user=:op ";
			$qo = array(":id" => $ruleid, ":weid" => $weid, ':op' => $opeind);
			$psd = pdo_fetch($sqd, $qo);
			if (empty($psd['headimgurl'])) {
				$psd['headimgurl'] = MODULE_URL . "template/mobile/css/noavatar_middle.jpg";
			}
			include $this->template('M2');
		} else {
			message('活动不存在', '');
		}
	}
	public function ys($date)
	{
		global $_W;
		$arr = '	<ul id="tblMain"><!-- 5个-->';
		if ($date) {
			foreach ($date as $k => $v) {
				$arr = $arr . '<li><div class="left"><div style="margin-right: 0.15rem">';
				if ($v['headimgurl']) {
					if (strstr($v['headimgurl'], "://")) {
						$arr .= '	<img src="' . $v["headimgurl"] . '"/>';
					} else {
						$arr .= '<img src="' . $_W['attachurl'] . $v["headimgurl"] . '"/>';
					}
				} else {
					$arr .= '<img src="' . MODULE_URL . "template/mobile/css/noavatar_middle.jpg" . '"/>';
				}
				$arr .= '</div><div><span>' . mb_substr($v["nickname"], 0, 8, 'utf-8') . '</span><span>' . date("m/d", $v["lastplay_date"]) . '</span></div></div><div class="center">' . $v['best_score'] . '分</div><div class="right"><div class="medal">第' . $v["Rank"] . '名</div></li>';
			}
		} else {
			$arr = $arr . "<li class='li2'>暂无数据</li>";
		}
		$arr = $arr . "</ul>";
		return $arr;
	}
	function ysl($page)
	{
		$a = '<div class="paging"><div>';
		$p = $page['p'];
		$fi = $p - 1;
		$la = $p + 1;
		if ($fi >= 1) {
			$a = $a . ' <a class="prev" onclick="gopage(' . $fi . ');">上一页</a>';
		}
		$q = $page['tp'];
		if ($p + 4 < $q) {
			for ($i = $p; $i <= $p + 4; $i++) {
				if ($i == $page['p']) {
					$a = $a . ' <a class="curr" onclick="gopage(' . $i . ');">' . $i . '</a>';
				} else {
					$a = $a . '<a onclick="gopage(' . $i . ');">' . $i . '</a>';
				}
			}
		} elseif ($q - 4 > 0) {
			for ($i = $q - 4; $i <= $q; $i++) {
				if ($i == $page['p']) {
					$a = $a . '<a class="curr" onclick="gopage(' . $i . ');">' . $i . '</a>';
				} else {
					$a = $a . '<a onclick="gopage(' . $i . ');">' . $i . '</a>';
				}
			}
		} else {
			for ($i = 1; $i <= $q; $i++) {
				if ($i == $page['p']) {
					$a = $a . '<a class="curr" onclick="gopage(' . $i . ');">' . $i . '</a>';
				} else {
					$a = $a . '<a onclick="gopage(' . $i . ');">' . $i . '</a>';
				}
			}
		}
		if ($la <= $page['tp']) {
			$a = $a . '<a class="next" onclick="gopage(' . $la . ');"> 下一页</li>';
		}
		$a = $a . "</div></div>";
		return $a;
	}
	public function doMobileRan()
	{
		global $_W, $_GPC;
		$info['num'] = 1001;
		$info['msg'] = "<ul id='tblMain'><li class='li2'>参数错误</li></ul>";
		$info['get'] = $_GPC;
		$ruleid = $_GPC['r'];
		$tr = cache_load('tr');
		if ($_W['isajax']) {
			$uscountSql = "select count(1) AS num from " . tablename($this->table_fans) . " u where u.rid=:id and u.weid=:weid and u.isblack=0";
			$qo = array(":id" => $_GPC['r'], ":weid" => $_W['uniacid']);
			$psd = pdo_fetch($uscountSql, $qo);
			$pag = $psd['num'];
			$psize = $_GPC['psize'];
			$p = $_GPC['p'];
			$tp = ceil($pag / $psize);
			$tm = ($p - 1) * $psize;
			$usxqSql = "select  @counter:=@counter+1 AS Rank,u.* from " . tablename($this->table_fans) . " as  u  ,(SELECT @counter:={$tm}) AS t where u.rid=:id and u.weid=:weid and u.isblack=0 ORDER by u.best_score DESC,lastplay_date  limit {$tm},{$psize}";
			$info['po'] = $qo;
			$date = pdo_fetchall($usxqSql, $qo);
			$info['pol'] = $date;
			$pp = $this->ys($date);
			$pages['p'] = $p;
			$pages['tp'] = $tp;
			$apr = "";
			if ($tp > 1) {
				$apr = $this->ysl($pages);
			}
			$info['msg'] = $pp . $apr;
		}
		echo json_encode($info);
		die;
	}
	public function doWebDelete()
	{
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
		if (empty($rule)) {
			message('抱歉，要修改的规则不存在或是已经被删除！');
		}
		if (pdo_delete('rule', array('id' => $rid))) {
			pdo_delete('rule_keyword', array('rid' => $rid));
			pdo_delete('stat_rule', array('rid' => $rid));
			pdo_delete('stat_keyword', array('rid' => $rid));
			$module = WeUtility::createModule($rule['module']);
			if (method_exists($module, 'ruleDeleted')) {
				$module->ruleDeleted($rid);
			}
		}
		message('活动删除成功！', referer(), 'success');
	}
	public function doWebManage()
	{
		global $_GPC, $_W;
		load()->model('reply');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$sql = "uniacid = :weid AND `module` = :module";
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':module'] = 'sdl_rink';
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
				$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ", array(':rid' => $item['id']));
				$item['viewnum'] = $reply['viewnum'];
				$item['tit'] = $reply['title'];
				$item['isnot'] = $reply['isnot'];
				$item['starttime'] = date('Y-m-d H:i', $reply['starttime']);
				$endtime = $reply['endtime'];
				$item['endtime'] = date('Y-m-d H:i', $endtime);
				$nowtime = time();
				if ($reply['starttime'] > $nowtime) {
					$item['show'] = '<span class="label label-warning">未开始</span>';
				} elseif ($endtime < $nowtime) {
					$item['show'] = '<span class="label label-default">已结束</span>';
				} else {
					if ($reply['status'] == 1) {
						$item['show'] = '<span class="label label-success">已开始</span>';
					} else {
						$item['show'] = '<span class="label label-default">已暂停</span>';
					}
				}
				$item['status'] = $reply['status'];
				$item['weid'] = $reply['weid'];
			}
		}
		include $this->template('manage');
	}
	protected function exportexcel($data = array(), $title = array(), $filename = 'report')
	{
		header("Content-type:application/octet-stream");
		header('Accept-Ranges:bytes');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename=' . $filename . ".xls");
		header('Pragma: no-cache');
		header('Expires: 0');
		if (!empty($title)) {
			foreach ($title as $k => $v) {
				$title[$k] = iconv("UTF-8", "GB2312", $v);
			}
			$title = implode("\t", $title);
			echo "{$title}\n";
		}
		if (!empty($data)) {
			foreach ($data as $key => $val) {
				foreach ($val as $ck => $cv) {
					echo iconv("UTF-8", "GB2312", $cv) . "\t";
				}
				echo "\n";
			}
		}
	}
	public function doWebFanslist()
	{
		global $_GPC, $_W;
		load()->func('tpl');
		$weid = $_W['uniacid'];
		$rid = intval($_GPC['rid']);
		if (empty($rid)) {
			message('抱歉，传递的参数错误！', '', 'error');
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$url = $this->createWebUrl('fanslist', array('op' => 'display', 'rid' => $rid));
		if ($operation == 'display') {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			$condition = ' best_score ';
			if ($reply == false) {
				$this->showMsg('抱歉，活动不存在！');
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 12;
			if ($_GPC['out_put'] == 'output') {
				$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid AND isblack=0 ORDER BY {$condition} desc,lastplay_date ", array(':rid' => $rid));
				$i = 0;
				foreach ($list as $key => $value) {
					$arr[$i]['rank'] = $key + 1;
					$arr[$i]['realname'] = $value['nickname'];
					$arr[$i]['username'] = $value['username'];
					$arr[$i]['tel'] = $value['tel'];
					$arr[$i]['phone'] = $value['headimgurl'];
					$arr[$i]['best_score'] = $value['best_score'];
					$arr[$i]['dateline'] = date('Y-m-d H:i:s', $value['lastplay_date']);
					$i++;
				}
				$this->exportexcel($arr, array('排名', '昵称', '姓名', '电话', '头像', '最佳成绩', '参与时间'), date('Y-m-d', time()));
				die;
			}
			$start = ($pindex - 1) * $psize;
			$limit = "";
			$limit .= " LIMIT {$start},{$psize}";
			$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid ORDER BY {$condition} desc,lastplay_date " . $limit, array(':rid' => $rid));
			$total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid = :rid  ", array(':rid' => $rid));
			$gametotal = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid = :rid AND isblack = 0", array(':rid' => $rid));
			$blacktotal = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid = :rid AND isblack = 1 ", array(':rid' => $rid));
			$pager = pagination($gametotal, $pindex, $psize);
		} else {
			if ($operation == 'post') {
				$id = intval($_GPC['id']);
				$item = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE id = :id", array(':id' => $id));
				if (checksubmit()) {
					$data = array('weid' => $weid, 'rid' => $rid, 'username' => trim($_GPC['username']), 'tel' => trim($_GPC['tel']), 'nickname' => trim($_GPC['nickname']), 'headimgurl' => trim($_GPC['headimgurl']), 'best_score' => floatval($_GPC['best_score']), 'lastshare_date' => TIMESTAMP);
					if (empty($item)) {
						$data["lastplay_date"] = TIMESTAMP;
						pdo_insert($this->table_fans, $data);
					} else {
						unset($data['dateline']);
						pdo_update($this->table_fans, $data, array('id' => $id, 'weid' => $weid));
					}
					message('操作成功！', $url, 'success');
				}
			} else {
				if ($operation == 'delete') {
					$id = intval($_GPC['id']);
					$item = pdo_fetch("SELECT id FROM " . tablename($this->table_fans) . " WHERE id = :id AND weid=:weid", array(':id' => $id, ':weid' => $weid));
					if (empty($item)) {
						message('抱歉，不存在或是已经被删除！', $url, 'error');
					}
					pdo_delete($this->table_fans, array('id' => $id, 'weid' => $weid));
					message('删除成功！', $url, 'success');
				} else {
					if ($operation == 'displayblack') {
						$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
						$condition = ' best_score ';
						if ($reply == false) {
							$this->showMsg('抱歉，活动不存在！');
						}
						$pindex = max(1, intval($_GPC['page']));
						$psize = 12;
						$start = ($pindex - 1) * $psize;
						$limit = "";
						$limit .= " LIMIT {$start},{$psize}";
						$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid AND best_score<>0 AND isblack=1 ORDER BY {$condition} asc,id DESC " . $limit, array(':rid' => $rid));
						$total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid = :rid  ", array(':rid' => $rid));
						$gametotal = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid = :rid AND best_score<>0 AND isblack=1 ", array(':rid' => $rid));
						$pager = pagination($gametotal, $pindex, $psize);
					} else {
						if ($operation == 'blackcheck') {
							$id = intval($_GPC['id']);
							$black = intval($_GPC['black']);
							$item = pdo_fetch("SELECT id FROM " . tablename($this->table_fans) . " WHERE id = :id AND weid=:weid", array(':id' => $id, ':weid' => $weid));
							if (empty($item)) {
								message('抱歉，不存在或是已经被删除！', $url, 'error');
							}
							$url = $this->createWebUrl('fanslist', array('op' => 'displayblack', 'rid' => $rid));
							pdo_update($this->table_fans, array('isblack' => $black), array('id' => $id, 'weid' => $weid));
							message('操作成功！', $url, 'success');
						}
					}
				}
			}
		}
		include $this->template('fanslist');
	}
}