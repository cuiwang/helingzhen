<?php

defined('IN_IA') or exit('Access Denied');
define('MD_ROOT', '../addons/cy163_customerservice/');
define('BEST_SET', 'messikefu_set');
define('BEST_TPLMESSAGE_SENDLOG', 'messikefu_tplmessage_sendlog');
define('BEST_TPLMESSAGE_TPLLIST', 'messikefu_tplmessage_tpllist');
define('BEST_CHAT', 'messikefu_chat');
define('BEST_CSERVICE', 'messikefu_cservice');
define('BEST_CSERVICEGROUP', 'messikefu_cservicegroup');
define('BEST_BIAOQIAN', 'messikefu_biaoqian');
define('BEST_GROUP', 'messikefu_group');
define('BEST_GROUPMEMBER', 'messikefu_groupmember');
define('BEST_GROUPCONTENT', 'messikefu_groupchat');
define('BEST_FANSKEFU', 'messikefu_fanskefu');
define('BEST_ADV', 'messikefu_adv');
define('BEST_SANFANSKEFU', 'messikefu_sanfanskefu');
define('BEST_SANCHAT', 'messikefu_sanchat');
define('BEST_KEFUANDGROUP', 'messikefu_kefuandgroup');
define('BEST_PINGJIA', 'messikefu_pingjia');
class Cy163_customerserviceModuleSite extends WeModuleSite
{
	public $setting = '';
	public function __construct()
	{
		global $_W;
		$this->setting = pdo_fetch("SELECT * FROM " . tablename(BEST_SET) . " WHERE weid = {$_W['uniacid']}");
	}
	public function doMobileShenqingqun()
	{
		global $_W, $_GPC;
		if (empty($_W['fans']['from_user'])) {
			message('请在微信浏览器中打开！');
		}
		$groupid = intval($_GPC['groupid']);
		if (empty($groupid)) {
			message('参数传输错误！');
		}
		$hasgroup = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE weid = {$_W['uniacid']} AND id = {$groupid}");
		if (empty($hasgroup)) {
			message('不存在该群聊！');
		}
		if ($hasgroup['isguanzhu'] == 1 && $_W['fans']['follow'] == 0) {
			message('关注公众号才能申请进群！');
		}
		if ($hasgroup['maxnum'] != 0) {
			$ingroupnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND groupid = {$groupid}");
			if ($ingroupnum >= $hasgroup['maxnum']) {
				message('该群聊人数已满！');
			}
		}
		$hasshenqing = pdo_fetch("SELECT id FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid}");
		if (!empty($hasshenqing)) {
			message('您已申请加入该群聊！');
		}
		$iscservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
		if (!empty($iscservice)) {
			$data['nickname'] = $iscservice['name'];
			$data['avatar'] = tomedia($iscservice['thumb']);
			$data['type'] = 2;
		} else {
			$data['nickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
			$data['avatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($setting['defaultavatar']) : $_W['fans']['tag']['avatar'];
			$data['type'] = 1;
		}
		$data['groupid'] = $groupid;
		$data['weid'] = $_W['uniacid'];
		$data['openid'] = $_W['fans']['from_user'];
		if ($hasgroup['isshenhe'] == 1) {
			$data['status'] = 1;
		}
		pdo_insert(BEST_GROUPMEMBER, $data);
		if ($hasgroup['isshenhe'] == 0) {
			$texturl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("guanligroup", array('groupid' => $groupid)));
			$texturl = "<a href='" . $texturl . "'>点击查看</a>";
			$concon = $data['nickname'] . '申请加入' . $hasgroup['groupname'] . '！' . $texturl;
			$send['touser'] = $hasgroup['admin'];
			$send['msgtype'] = 'text';
			$send['text'] = array('content' => urlencode($concon));
			$acc = WeAccount::create($_W['uniacid']);
			$res = $acc->sendCustomNotice($send);
		}
		$message = '提交申请成功！';
		include $this->template('shenqingqun');
	}
	public function doWebSetting()
	{
		global $_W, $_GPC;
		$op = trim($_GPC['op']);
		if ($op == 'post') {
			$id = intval($_GPC['id']);
			$data = array('weid' => $_W['uniacid'], 'title' => trim($_GPC['title']), 'istplon' => intval($_GPC['istplon']), 'unfollowtext' => trim($_GPC['unfollowtext']), 'followqrcode' => trim($_GPC['followqrcode']), 'sharetitle' => trim($_GPC['sharetitle']), 'sharedes' => trim($_GPC['sharedes']), 'sharethumb' => trim($_GPC['sharethumb']), 'kefutplminute' => intval($_GPC['kefutplminute']), 'bgcolor' => trim($_GPC['bgcolor']), 'defaultavatar' => trim($_GPC['defaultavatar']), 'issharemsg' => intval($_GPC['issharemsg']), 'isshowwgz' => intval($_GPC['isshowwgz']), 'sharetype' => intval($_GPC['sharetype']), 'mingan' => trim($_GPC['mingan']), 'temcolor' => trim($_GPC['temcolor']), 'candel' => intval($_GPC['candel']), 'copyright' => trim($_GPC['copyright']), 'isgrouptplon' => intval($_GPC['isgrouptplon']), 'grouptplminute' => intval($_GPC['grouptplminute']), 'isgroupon' => intval($_GPC['isgroupon']), 'footertext1' => trim($_GPC['footertext1']), 'footertext2' => trim($_GPC['footertext2']), 'footertext3' => trim($_GPC['footertext3']), 'footertext4' => trim($_GPC['footertext4']), 'qiniuaccesskey' => trim($_GPC['qiniuaccesskey']), 'qiniusecretkey' => trim($_GPC['qiniusecretkey']), 'qiniubucket' => trim($_GPC['qiniubucket']), 'qiniuurl' => trim($_GPC['qiniuurl']), 'isqiniu' => intval($_GPC['isqiniu']), 'httptype' => intval($_GPC['httptype']), 'istxfon' => intval($_GPC['istxfon']), 'ishowgroupnum' => intval($_GPC['ishowgroupnum']), 'chosekefutem' => intval($_GPC['chosekefutem']), 'tulingkey' => trim($_GPC['tulingkey']), 'istulingon' => intval($_GPC['istulingon']));
			if (!empty($id)) {
				pdo_update(BEST_SET, $data, array('id' => $id, 'weid' => $_W['uniacid']));
			} else {
				pdo_insert(BEST_SET, $data);
			}
			message('操作成功！', $this->createWebUrl('setting', array('op' => 'display')), 'success');
		} elseif ($op == 'tongbu') {
			$chatlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND (type = 1 OR type = 4 OR type = 5)");
			foreach ($chatlist as $k => $v) {
				$fansopenid = $v['openid'];
				$kefuopenid = $v['toopenid'];
				$hasfanskefu = pdo_fetch("SELECT id FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$fansopenid}' AND kefuopenid = '{$kefuopenid}'");
				if (empty($hasfanskefu)) {
					$datafanskefu['weid'] = $_W['uniacid'];
					$datafanskefu['fansopenid'] = $fansopenid;
					$datafanskefu['kefuopenid'] = $kefuopenid;
					$datafanskefu['fansavatar'] = $v['avatar'];
					$datafanskefu['fansnickname'] = $v['nickname'];
					$cservice = pdo_fetch("SELECT name,thumb FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND content = '{$kefuopenid}' AND ctype = 1");
					if (!empty($cservice)) {
						$datafanskefu['kefuavatar'] = tomedia($cservice['thumb']);
						$datafanskefu['kefunickname'] = $cservice['name'];
						pdo_insert(BEST_FANSKEFU, $datafanskefu);
						$fkid = pdo_insertid();
						$dataup['fkid'] = $fkid;
						pdo_update(BEST_CHAT, $dataup, array('openid' => $fansopenid, 'weid' => $_W['uniacid'], 'toopenid' => $kefuopenid));
						pdo_update(BEST_CHAT, $dataup, array('openid' => $kefuopenid, 'weid' => $_W['uniacid'], 'toopenid' => $fansopenid));
					}
				} else {
					if ($v['fkid'] == 0) {
						$fkid = $dataup['fkid'] = $hasfanskefu['id'];
						pdo_update(BEST_CHAT, $dataup, array('openid' => $fansopenid, 'weid' => $_W['uniacid'], 'toopenid' => $kefuopenid));
						pdo_update(BEST_CHAT, $dataup, array('openid' => $kefuopenid, 'weid' => $_W['uniacid'], 'toopenid' => $fansopenid));
					} else {
						$fkid = $v['fkid'];
					}
				}
				$fanslastcon = pdo_fetch("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND openid = '{$fansopenid}' AND fkid = {$fkid} AND toopenid = '{$kefuopenid}' AND type != 99 ORDER BY time DESC");
				if (!empty($fanslastcon)) {
					$datalast['lastcon'] = $fanslastcon['content'];
					$datalast['lasttime'] = $fanslastcon['time'];
					$datalast['msgtype'] = $fanslastcon['type'];
					$datalast['fansavatar'] = $fanslastcon['avatar'];
					$datalast['fansnickname'] = $fanslastcon['nickname'];
					pdo_update(BEST_FANSKEFU, $datalast, array('id' => $fkid, 'weid' => $_W['uniacid']));
					pdo_update(BEST_CHAT, array('nickname' => $fanslastcon['nickname'], 'avatar' => $fanslastcon['avatar']), array('openid' => $fansopenid));
				}
			}
			$groupmemberlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']}");
			foreach ($groupmemberlist as $kk => $vv) {
				$mchat = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUPCONTENT) . " WHERE openid = '{$vv['openid']}' ORDER BY time DESC");
				if (!empty($mchat)) {
					$dgroup['nickname'] = $mchat['nickname'];
					$dgroup['avatar'] = $mchat['avatar'];
					pdo_update(BEST_GROUPMEMBER, $dgroup, array('id' => $vv['id']));
				}
			}
			message('同步数据成功！', $this->createWebUrl('setting', array('op' => 'display')), 'success');
		} else {
			$setting = $this->setting;
			include $this->template('web/set');
		}
	}
	public function guolv($content)
	{
		$setting = $this->setting;
		if (!empty($setting['mingan'])) {
			$sensitivewordarr = explode("|", $setting['mingan']);
			$content = str_replace($sensitivewordarr, "***", $content);
		}
		return $content;
	}
	public function doMobileGroupcenter()
	{
		global $_GPC, $_W;
		$op = trim($_GPC['op']);
		$setting = $this->setting;
		$referer = $_SERVER['HTTP_REFERER'];
		if ($op == 'shenqing') {
			if (empty($_W['fans']['from_user'])) {
				$resarr['error'] = 1;
				$resarr['msg'] = '请在微信浏览器中打开！';
				echo json_encode($resarr);
				exit;
			}
			$iscservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
			$groupid = intval($_GPC['groupid']);
			$hasgroup = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE weid = {$_W['uniacid']} AND id = {$groupid}");
			if (empty($hasgroup)) {
				$resarr['error'] = 1;
				$resarr['msg'] = '不存在该群聊！';
				echo json_encode($resarr);
				exit;
			}
			if ($hasgroup['isguanzhu'] == 1 && $_W['fans']['follow'] == 0) {
				$resarr['error'] = 1;
				$resarr['msg'] = '关注公众号才能申请进群！';
				echo json_encode($resarr);
				exit;
			}
			if ($hasgroup['maxnum'] != 0) {
				$ingroupnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND groupid = {$groupid}");
				if ($ingroupnum >= $hasgroup['maxnum']) {
					$resarr['error'] = 1;
					$resarr['msg'] = '该群聊人数已满！';
					echo json_encode($resarr);
					exit;
				}
			}
			$hasshenqing = pdo_fetch("SELECT id FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid}");
			if (!empty($hasshenqing)) {
				$resarr['error'] = 1;
				$resarr['msg'] = '您已申请加入该群聊！';
				echo json_encode($resarr);
				exit;
			}
			if (!empty($iscservice)) {
				$data['nickname'] = $iscservice['name'];
				$data['avatar'] = tomedia($iscservice['thumb']);
				$data['type'] = 2;
			} else {
				$data['nickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
				$data['avatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($setting['defaultavatar']) : $_W['fans']['tag']['avatar'];
				$data['type'] = 1;
			}
			$data['groupid'] = $groupid;
			$data['weid'] = $_W['uniacid'];
			$data['openid'] = $_W['fans']['from_user'];
			if ($hasgroup['isshenhe'] == 1) {
				$data['status'] = 1;
			}
			pdo_insert(BEST_GROUPMEMBER, $data);
			if ($hasgroup['isshenhe'] == 0) {
				$texturl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("guanligroup", array('groupid' => $groupid)));
				$texturl = "<a href='" . $texturl . "'>点击查看</a>";
				$concon = $data['nickname'] . '申请加入' . $hasgroup['groupname'] . '！' . $texturl;
				$send['touser'] = $hasgroup['admin'];
				$send['msgtype'] = 'text';
				$send['text'] = array('content' => urlencode($concon));
				$acc = WeAccount::create($_W['uniacid']);
				$res = $acc->sendCustomNotice($send);
			}
			$resarr['error'] = 0;
			$resarr['msg'] = '提交申请成功！';
			echo json_encode($resarr);
			exit;
		} elseif ($op == 'search') {
			if (empty($_W['fans']['from_user'])) {
				$resarr['error'] = 1;
				$resarr['msg'] = '请在微信浏览器中打开！';
				echo json_encode($resarr);
				exit;
			}
			$qunname = trim($_GPC['qunname']);
			if (empty($qunname)) {
				$resarr['error'] = 1;
				$resarr['msg'] = '请输入群名称查询！';
				echo json_encode($resarr);
				exit;
			}
			$group = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE groupname like '%{$qunname}%' AND weid = {$_W['uniacid']}");
			if (empty($group)) {
				$resarr['error'] = 1;
				$resarr['msg'] = '没有这个群聊！';
				echo json_encode($resarr);
				exit;
			} else {
				$resarr['error'] = 0;
				$resarr['html'] = '<div class="item">
										<div class="left">
											<div class="img">
												<img src="' . tomedia($group['thumb']) . '">
											</div>
											<div class="text">
												<div class="name textellipsis1">' . $group['groupname'] . '</div>
												<div class="zu">创建于' . date("Y-m-d H:i:s", $group['time']) . '</div>
											</div>
										</div>
										<div class="right" onclick="shenqing(' . $group['id'] . ')" style="background:#3ACED8;color:#fff;">申请加入</div>
									</div>';
				echo json_encode($resarr);
				exit;
			}
		} else {
			if (empty($_W['fans']['from_user'])) {
				message("请在微信浏览器中打开！");
			}
			$grouplist = pdo_fetchall("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE weid = {$_W['uniacid']} ORDER BY time DESC");
			$grouplist1 = $grouplist2 = array();
			foreach ($grouplist as $k => $v) {
				$mygrouplist = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = '{$v['id']}'");
				if (empty($mygrouplist)) {
					$grouplist2[] = $grouplist[$k];
				} else {
					$grouplist[$k]['myin'] = $mygrouplist;
					$grouplist1[] = $grouplist[$k];
				}
			}
			$iscservice = pdo_fetch("SELECT id FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
			if (!empty($iscservice)) {
				$notread = pdo_fetchcolumn("SELECT SUM(`notread`) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$_W['fans']['from_user']}'");
			} else {
				$notread = pdo_fetchcolumn("SELECT SUM(`kefunotread`) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$_W['fans']['from_user']}'");
			}
			$setting['shareurl'] = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl('groupcenter'));
			include $this->template('groupcenter');
		}
	}
	public function doMobileGuanligroup()
	{
		global $_GPC, $_W;
		if (empty($_W['fans']['from_user'])) {
			message("请在微信浏览器中打开！");
		}
		$referer = $_SERVER['HTTP_REFERER'];
		$groupid = intval($_GPC['groupid']);
		$isgroupadmin = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE weid = {$_W['uniacid']} AND id = {$groupid} AND admin = '{$_W['fans']['from_user']}'");
		if (empty($isgroupadmin)) {
			message("你不是管理员，不能管理该群聊！");
		}
		$setting = $this->setting;
		$op = trim($_GPC['op']);
		if ($op == '') {
			$groupmemberlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND openid != '{$_W['fans']['from_user']}' ORDER BY status ASC,id DESC");
			include $this->template('guanligroup');
		} elseif ($op == 'del') {
			$groupid = intval($_GPC['groupid']);
			$id = intval($_GPC['memberid']);
			pdo_delete(BEST_GROUPMEMBER, array('id' => $id, 'groupid' => $groupid));
			$resarr['error'] = 0;
			$resarr['msg'] = '操作成功！';
			echo json_encode($resarr);
			exit;
		} elseif ($op == 'shenhe') {
			$groupid = intval($_GPC['groupid']);
			$id = intval($_GPC['memberid']);
			$groupmember = pdo_fetch("SELECT openid FROM " . tablename(BEST_GROUPMEMBER) . " WHERE id = {$id}");
			pdo_update(BEST_GROUPMEMBER, array('status' => 1, 'intime' => TIMESTAMP), array('id' => $id));
			$texturl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("groupchatdetail", array('groupid' => $groupid)));
			$texturl = "<a href='" . $texturl . "'>点击查看</a>";
			$concon = '您已被审核进群！' . $texturl;
			$send['touser'] = $groupmember['openid'];
			$send['msgtype'] = 'text';
			$send['text'] = array('content' => urlencode($concon));
			$acc = WeAccount::create($_W['uniacid']);
			$res = $acc->sendCustomNotice($send);
			$resarr['error'] = 0;
			$resarr['msg'] = '操作成功！';
			echo json_encode($resarr);
			exit;
		}
	}
	public function doMobileGroupchatdetail()
	{
		global $_GPC, $_W;
		if (empty($_W['fans']['from_user'])) {
			message("请在微信浏览器中打开！");
		}
		$groupid = intval($_GPC['groupid']);
		$group = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE id = {$groupid}");
		if (empty($group)) {
			message("不存在该群聊！");
		}
		$isin = pdo_fetch("SELECT id,intime,avatar,nickname FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid} AND status = 1");
		if (empty($isin)) {
			message("您不是该群聊成员！");
		}
		$allmemberlist = pdo_fetchall("SELECT openid FROM " . tablename(BEST_GROUPMEMBER) . " WHERE groupid = {$groupid} AND openid != '{$_W['fans']['from_user']}' AND status = 1");
		$allpeople = count($allmemberlist);
		$allmember = '';
		foreach ($allmemberlist as $k => $v) {
			$allmember .= $v['openid'] . $groupid . "|";
		}
		$allmember = substr($allmember, 0, -1);
		$timestamp = TIMESTAMP;
		$quickcon = empty($group['quickcon']) ? '' : explode("|", $group['quickcon']);
		if ($group['autoreply']) {
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?<<>>“”‘’]))@';
			preg_match_all($regex, $group['autoreply'], $array2);
			if (!empty($array2[0])) {
				foreach ($array2[0] as $kk => $vv) {
					if (!empty($vv)) {
						$group['autoreply'] = str_replace($vv, "<a href='" . $vv . "'>" . $vv . "</a>", $group['autoreply']);
					}
				}
			}
		}
		$groupcontent = pdo_fetchall("SELECT * FROM " . tablename(BEST_GROUPCONTENT) . " WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND time >= {$isin['intime']} ORDER BY time ASC");
		foreach ($groupcontent as $k => $v) {
			$groupcontent[$k]['content'] = $this->guolv($groupcontent[$k]['content']);
			$groupcontent[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?<<>>“”‘’]))@';
			preg_match_all($regex, $groupcontent[$k]['content'], $array2);
			if (!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)) {
				foreach ($array2[0] as $kk => $vv) {
					if (!empty($vv)) {
						$groupcontent[$k]['content'] = str_replace($vv, "<a href='" . $vv . "'>" . $vv . "</a>", $groupcontent[$k]['content']);
					}
				}
			}
			if ($v['type'] == 5) {
				$donetime = $timestamp - $v['time'];
				if ($donetime >= 24 * 3600 * 3) {
					unset($groupcontent[$k]);
				}
			}
		}
		$imglist = pdo_fetchall("SELECT * FROM " . tablename(BEST_GROUPCONTENT) . " WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND type = 3 ORDER BY time ASC");
		$imglistval = '';
		foreach ($imglist as $k => $v) {
			$imglistval .= $v['content'] . ',';
		}
		$imglistval = substr($imglistval, 0, -1);
		$setting = $this->setting;
		include $this->template('groupdetail');
	}
	public function doMobileTuichuqun()
	{
		global $_GPC, $_W;
		$groupid = intval($_GPC['groupid']);
		$openid = $_W['fans']['from_user'];
		if ($groupid == 0 || $openid == '') {
			$resArr['error'] = 1;
			$resArr['msg'] = '参数传输错误！';
			echo json_encode($resArr);
			exit;
		}
		$group = pdo_fetch("SELECT groupname,admin FROM " . tablename(BEST_GROUP) . " WHERE weid = {$_W['uniacid']} AND id = {$groupid}");
		if ($group['admin'] == $openid) {
			$resArr['error'] = 1;
			$resArr['msg'] = '管理员不能退群！';
			echo json_encode($resArr);
			exit;
		}
		$has = pdo_fetch("SELECT id,nickname FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND openid = '{$openid}'");
		if (empty($has)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '您不是群成员！';
			echo json_encode($resArr);
			exit;
		}
		pdo_delete(BEST_GROUPMEMBER, array('weid' => $_W['uniacid'], 'groupid' => $groupid, 'openid' => $openid));
		pdo_delete(BEST_GROUPCONTENT, array('weid' => $_W['uniacid'], 'groupid' => $groupid, 'openid' => $openid));
		$concon = $has['nickname'] . '退出了' . $group['groupname'] . '！';
		$send['touser'] = $group['admin'];
		$send['msgtype'] = 'text';
		$send['text'] = array('content' => urlencode($concon));
		$acc = WeAccount::create($_W['uniacid']);
		$res = $acc->sendCustomNotice($send);
		$resArr['error'] = 1;
		$resArr['msg'] = '退出成功！';
		echo json_encode($resArr);
		exit;
	}
	public function doMobileGetquick()
	{
		global $_GPC, $_W;
		$openid = trim($_GPC['openid']);
		$type = intval($_GPC['type']);
		$qudao = trim($_GPC['qudao']);
		$goodsid = intval($_GPC['goodsid']);
		if ($type == 34) {
			$cservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE (ctype = 4 OR ctype = 3) AND weid = {$_W['uniacid']} AND content = '{$openid}'");
		} else {
			$cservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE ctype = {$type} AND weid = {$_W['uniacid']} AND content = '{$openid}'");
		}
		include $this->template('quick');
	}
	public function doMobileGetdisanfang()
	{
		global $_GPC, $_W;
		$qudao = trim($_GPC['qudao']);
		include $this->template('quickdisanfang');
	}
	public function doMobileGetquickgroup()
	{
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$qudao = trim($_GPC['qudao']);
		$goodsid = intval($_GPC['goodsid']);
		$cservicegroup = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE id = {$id} AND weid = {$_W['uniacid']}");
		include $this->template('quickgroup');
	}
	public function doWebAdv()
	{
		include_once 'inc/web/adv.php';
	}
	public function doWebTongji()
	{
		include_once 'inc/web/tongji.php';
	}
	public function doWebChatlist()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$cservicelist = pdo_fetchall("SELECT content,name,thumb FROM " . tablename(BEST_CSERVICE) . " WHERE weid = '{$_W['uniacid']}' AND ctype = 1 ORDER BY displayorder ASC");
			foreach ($cservicelist as $kk => $vv) {
				$cservicelist[$kk]['weidu'] = pdo_fetchcolumn("SELECT SUM(`guanlinum`) FROM " . tablename(BEST_FANSKEFU) . " WHERE kefuopenid = '{$vv['content']}' AND weid = {$_W['uniacid']}");
			}
			$kefuopenid = trim($_GPC['kefuopenid']);
			$total = 0;
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = TIMESTAMP;
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
			}
			if (!empty($kefuopenid)) {
				$allfkid = pdo_fetchall("SELECT fkid FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND time > {$starttime} AND time < {$endtime} AND toopenid = '{$kefuopenid}'");
				$fkidarr = '(0,';
				foreach ($allfkid as $k => $v) {
					$fkidarr .= $v['fkid'] . ",";
				}
				$fkidarr = substr($fkidarr, 0, -1) . ")";
				pdo_query("UPDATE " . tablename(BEST_FANSKEFU) . " set guanlinum = 0 WHERE id in {$fkidarr}");
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND id in {$fkidarr} AND kefuopenid = '{$kefuopenid}' AND lasttime > 0");
				$allpage = ceil($total / 10) + 1;
				$page = intval($_GPC["page"]);
				$pindex = max(1, $page);
				$psize = 10;
				$fanslist = pdo_fetchall("SELECT * FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND id in {$fkidarr} AND kefuopenid = '{$kefuopenid}' AND lasttime > 0 ORDER BY lasttime DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
				foreach ($fanslist as $k => $v) {
					$fanslist[$k]['chat'] = pdo_fetchall("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND fkid = {$v['id']} ORDER BY time DESC");
				}
				$pager = pagination($total, $pindex, $psize);
				if ($_GPC['export'] == 'export') {
					$fanslistdaochu = pdo_fetchall("SELECT * FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND id in {$fkidarr} AND kefuopenid = '{$kefuopenid}' AND lasttime > 0 ORDER BY lasttime DESC");
					foreach ($fanslistdaochu as $k => $v) {
						$chatlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND fkid = {$v['id']} ORDER BY time DESC");
						$data = array();
						$onetitle = '和' . $v['fansnickname'] . '的记录';
						$twotitle = '和' . $v['fansnickname'] . '聊天内容';
						$titlearray = array($onetitle, $twotitle, '时间');
						foreach ($chatlist as $kk => $vv) {
							$data[$kk]['nickname'] = $vv['nickname'];
							$data[$kk]['con'] = $vv['content'];
							$data[$kk]['time'] = date("Y-m-d H:i:s", $vv['time']);
						}
						$this->exportexcel($data, $titlearray, '', '', $filename = $v['kefunickname'] . '工作记录');
					}
					exit;
				}
			}
			include $this->template('web/chatlist');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$kefuopenid = trim($_GPC['kefuopenid']);
			if (empty($id)) {
				message('抱歉，参数传入错误！', $this->createWebUrl('chatlist', array('op' => 'display')), 'error');
			}
			pdo_query("DELETE FROM " . tablename(BEST_CHAT) . " WHERE fkid = {$id}");
			pdo_query("DELETE FROM " . tablename(BEST_FANSKEFU) . " WHERE id = {$id}");
			message('删除聊天记录成功！', $this->createWebUrl('chatlist', array('kefuopenid' => $kefuopenid)), 'success');
		} elseif ($operation == 'deletedu') {
			$id = intval($_GPC['id']);
			$chat = pdo_fetch("SELECT id FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND id = {$id}");
			if (empty($chat)) {
				$resarr['error'] = 1;
				$resarr['msg'] = '不存在该聊天记录！';
				echo json_encode($resarr);
				exit;
			}
			pdo_delete(BEST_CHAT, array('id' => $id));
			$resarr['error'] = 0;
			$resarr['msg'] = '删除成功！';
			echo json_encode($resarr);
			exit;
		}
	}
	public function exportexcel($data = array(), $title = array(), $header, $footer, $filename = 'report')
	{
		header("Content-type:application/octet-stream");
		header("Accept-Ranges:bytes");
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=" . $filename . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$header = iconv("UTF-8", "GB2312", $header);
		echo $header;
		if (!empty($title)) {
			foreach ($title as $k => $v) {
				$title[$k] = iconv("UTF-8", "GB2312", $v);
			}
			$title = implode("\t", $title);
			echo "{$title}\r\n";
		}
		if (!empty($data)) {
			foreach ($data as $key => $val) {
				foreach ($val as $ck => $cv) {
					$data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
				}
				$data[$key] = implode("\t", $data[$key]);
			}
			echo implode("\n", $data);
		}
		echo "\r\n";
		$footer = iconv("UTF-8", "GB2312", $footer);
		echo $footer;
	}
	public function doWebCservice()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$grouplist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder ASC");
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update(BEST_CSERVICE, array('displayorder' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
				}
				message('客服排序更新成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
			}
			if ($_GPC['groupid']) {
				$kefuandgroup = pdo_fetchall("SELECT kefuid FROM " . tablename(BEST_KEFUANDGROUP) . " WHERE weid = {$_W['uniacid']} AND groupid = {$_GPC['groupid']}");
				$kefuids = "(";
				foreach ($kefuandgroup as $k => $v) {
					$kefuids .= $v['kefuid'] . ",";
				}
				$kefuids .= "0";
				$kefuids .= ")";
				$cservicelist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = '{$_W['uniacid']}' AND (groupid = '{$_GPC['groupid']}' OR id in {$kefuids}) ORDER BY displayorder ASC");
			} else {
				$cservicelist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder ASC");
			}
			foreach ($cservicelist as $k => $v) {
				if ($v['ctype'] == 1) {
					$cservicelist[$k]['serviceurl'] = $_W['siteroot'] . 'app/' . str_replace('./', '', $this->createMobileUrl('chat', array('toopenid' => $v['content'])));
					$scripturl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&openid=' . $v['content'] . '&type=1&do=getquick&m=cy163_customerservice';
					$scripturlrenren = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&openid=' . $v['content'] . '&type=1&do=getquick&m=cy163_customerservice&qudao=renren&goodsid={$goods[\'id\']}';
					$scripturlsuper = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&openid=' . $v['content'] . '&type=1&do=getquick&m=cy163_customerservice&qudao=super&goodsid={$item[\'id\']}';
					$cservicelist[$k]['pingjia'] = pdo_fetchall("SELECT * FROM " . tablename(BEST_PINGJIA) . " WHERE kefuopenid = '{$v['content']}' AND weid = {$_W['uniacid']}");
				}
				if ($v['ctype'] == 2) {
					$cservicelist[$k]['serviceurl'] = "http://wpa.qq.com/msgrd?v=3&uin=" . $v['content'];
					$scripturl = $scripturlrenren = $scripturlsuper = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&openid=' . $v['content'] . '&type=2&do=getquick&m=cy163_customerservice';
				}
				if ($v['ctype'] == 3 || $v['ctype'] == 4) {
					$cservicelist[$k]['serviceurl'] = "tel:" . $v['content'];
					$scripturl = $scripturlrenren = $scripturlsuper = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&openid=' . $v['content'] . '&type=34&do=getquick&m=cy163_customerservice';
				}
				$scripthtml = '<script type="text/javascript" src="../addons/cy163_customerservice/static/mui/js/jquery.min.js"></script>
					<script type="text/javascript">
					$.ajax({   
						url:"' . $scripturl . '",   
						type:"post", 
						data:{},
						dataType:"html",
						success:function(data){  
							$("body").append(data);
						}
					});
					</script>';
				$scripthtmlrenren = '<script type="text/javascript" src="../addons/cy163_customerservice/static/mui/js/jquery.min.js"></script>
					<script type="text/javascript">
					$.ajax({   
						url:"' . $scripturlrenren . '",   
						type:"post", 
						data:{},
						dataType:"html",
						success:function(data){  
							$("body").append(data);
						}
					});
					</script>';
				$scripthtmlsuper = '<script type="text/javascript" src="../addons/cy163_customerservice/static/mui/js/jquery.min.js"></script>
					<script type="text/javascript">
					$.ajax({   
						url:"' . $scripturlsuper . '",   
						type:"post", 
						data:{},
						dataType:"html",
						success:function(data){  
							$("body").append(data);
						}
					});
					</script>';
				$cservicelist[$k]['scripthtml'] = htmlspecialchars($scripthtml);
				$cservicelist[$k]['scripthtmlrenren'] = htmlspecialchars($scripthtmlrenren);
				$cservicelist[$k]['scripthtmlsuper'] = htmlspecialchars($scripthtmlsuper);
			}
			include $this->template('web/cservice');
		} elseif ($operation == 'post') {
			$cservicegrouplist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder ASC");
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$cservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE id = :id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
				$kefuandgroup = pdo_fetchall("SELECT groupid FROM " . tablename(BEST_KEFUANDGROUP) . " WHERE weid = {$_W['uniacid']} AND kefuid = {$id}");
				$groupids = array();
				foreach ($kefuandgroup as $k => $v) {
					$groupids[] = $v['groupid'];
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['name'])) {
					message('抱歉，请输入客服名称！');
				}
				if (empty($_GPC['ctype'])) {
					message('抱歉，请选择客服类型！');
				}
				if (empty($_GPC['content'])) {
					message('抱歉，请输入客服内容！');
				}
				if (empty($_GPC['thumb'])) {
					message('抱歉，请上传客服头像！');
				}
				$ctype = intval($_GPC['ctype']);
				if ($ctype == 1) {
					$starthour = intval($_GPC['starthour']);
					$endhour = intval($_GPC['endhour']);
					$autoreply = trim($_GPC['autoreply']);
				} else {
					$starthour = 0;
					$endhour = 0;
					$autoreply = '';
				}
				$data = array('weid' => $_W['uniacid'], 'name' => trim($_GPC['name']), 'typename' => trim($_GPC['typename']), 'ctype' => $ctype, 'content' => trim($_GPC['content']), 'thumb' => $_GPC['thumb'], 'iskefuqrcode' => intval($_GPC['iskefuqrcode']), 'kefuqrcode' => $_GPC['kefuqrcode'], 'starthour' => $starthour, 'endhour' => $endhour, 'autoreply' => $autoreply, 'displayorder' => intval($_GPC['displayorder']), 'fansauto' => trim($_GPC['fansauto']), 'kefuauto' => trim($_GPC['kefuauto']), 'isautosub' => intval($_GPC['isautosub']), 'qrtext' => trim($_GPC['qrtext']), 'qrcolor' => trim($_GPC['qrcolor']), 'qrbg' => trim($_GPC['qrbg']), 'ishow' => intval($_GPC['ishow']), 'notonline' => trim($_GPC['notonline']), 'lingjie' => intval($_GPC['lingjie']), 'groupid' => 0);
				if (!empty($id)) {
					pdo_delete(BEST_KEFUANDGROUP, array('kefuid' => $id));
					if (!empty($_GPC['groupid'])) {
						foreach ($_GPC['groupid'] as $ck => $cv) {
							$datacc['kefuid'] = $id;
							$datacc['groupid'] = $cv;
							$datacc['weid'] = $_W['uniacid'];
							pdo_insert(BEST_KEFUANDGROUP, $datacc);
						}
					}
					pdo_update(BEST_CSERVICE, $data, array('id' => $id, 'weid' => $_W['uniacid']));
					if ($ctype == 1 && $cservice['content'] == $data['content']) {
						$dataup['kefuavatar'] = $dataup2['avatar'] = $dataup3['avatar'] = $dataup4['avatar'] = tomedia($data['thumb']);
						$dataup['kefunickname'] = $dataup2['nickname'] = $dataup3['nickname'] = $dataup4['nickname'] = $data['name'];
						pdo_update(BEST_FANSKEFU, $dataup, array('weid' => $_W['uniacid'], 'kefuopenid' => $cservice['content']));
						pdo_update(BEST_GROUPCONTENT, $dataup2, array('weid' => $_W['uniacid'], 'openid' => $cservice['content']));
						pdo_update(BEST_GROUPMEMBER, $dataup3, array('weid' => $_W['uniacid'], 'openid' => $cservice['content']));
						pdo_update(BEST_CHAT, $dataup4, array('weid' => $_W['uniacid'], 'openid' => $cservice['content']));
					}
				} else {
					pdo_insert(BEST_CSERVICE, $data);
					$kefuid = pdo_insertid();
					if (!empty($_GPC['groupid'])) {
						foreach ($_GPC['groupid'] as $ck => $cv) {
							$datacc['kefuid'] = $kefuid;
							$datacc['groupid'] = $cv;
							$datacc['weid'] = $_W['uniacid'];
							pdo_insert(BEST_KEFUANDGROUP, $datacc);
						}
					}
				}
				message('操作成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
			}
			include $this->template('web/cservice');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$cservice = pdo_fetch("SELECT id,content,ctype FROM " . tablename(BEST_CSERVICE) . " WHERE id = {$id}");
			if (empty($cservice)) {
				message('抱歉，该客服信息不存在或是已经被删除！', $this->createWebUrl('cservice', array('op' => 'display')), 'error');
			}
			if ($cservice['ctype'] == 1) {
				pdo_delete(BEST_CHAT, array('openid' => $cservice['content']));
				pdo_delete(BEST_CHAT, array('toopenid' => $cservice['content']));
				pdo_delete(BEST_FANSKEFU, array('kefuopenid' => $cservice['content']));
			}
			pdo_delete(BEST_CSERVICE, array('id' => $id));
			message('删除客服信息成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
		}
	}
	public function doWebGroup()
	{
		include_once 'inc/web/group.php';
	}
	public function doWebCservicegroup()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update(BEST_CSERVICEGROUP, array('displayorder' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
				}
				message('客服组排序更新成功！', $this->createWebUrl('cservicegroup', array('op' => 'display')), 'success');
			}
			$cservicegrouplist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder ASC");
			foreach ($cservicegrouplist as $k => $v) {
				$cservicegrouplist[$k]['servicegroupurl'] = $_W['siteroot'] . 'app/' . str_replace('./', '', $this->createMobileUrl('groupchat', array('id' => $v['id'])));
				$scripturl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&id=' . $v['id'] . '&do=getquickgroup&m=cy163_customerservice';
				$scripturlrenren = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&id=' . $v['id'] . '&do=getquickgroup&m=cy163_customerservice&qudao=renren&goodsid={$goods[\'id\']}';
				$scripturlsuper = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&id=' . $v['id'] . '&do=getquickgroup&m=cy163_customerservice&qudao=super&goodsid={$item[\'id\']}';
				$scripthtml = '<script type="text/javascript" src="../addons/cy163_customerservice/static/mui/js/jquery.min.js"></script>
					<script type="text/javascript">
					$.ajax({   
						url:"' . $scripturl . '",   
						type:"post", 
						data:{},
						dataType:"html",
						success:function(data){  
							$("body").append(data);
						}
					});
					</script>';
				$scripthtmlrenren = '<script type="text/javascript" src="../addons/cy163_customerservice/static/mui/js/jquery.min.js"></script>
					<script type="text/javascript">
					$.ajax({   
						url:"' . $scripturlrenren . '",   
						type:"post", 
						data:{},
						dataType:"html",
						success:function(data){  
							$("body").append(data);
						}
					});
					</script>';
				$scripthtmlsuper = '<script type="text/javascript" src="../addons/cy163_customerservice/static/mui/js/jquery.min.js"></script>
					<script type="text/javascript">
					$.ajax({   
						url:"' . $scripturlsuper . '",   
						type:"post", 
						data:{},
						dataType:"html",
						success:function(data){  
							$("body").append(data);
						}
					});
					</script>';
				$cservicegrouplist[$k]['scripthtml'] = htmlspecialchars($scripthtml);
				$cservicegrouplist[$k]['scripthtmlrenren'] = htmlspecialchars($scripthtmlrenren);
				$cservicegrouplist[$k]['scripthtmlsuper'] = htmlspecialchars($scripthtmlsuper);
			}
			include $this->template('web/cservicegroup');
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$cservicegroup = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE id = :id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['name'])) {
					message('抱歉，请输入客服组名称！');
				}
				$data = array('weid' => $_W['uniacid'], 'name' => trim($_GPC['name']), 'typename' => trim($_GPC['typename']), 'thumb' => $_GPC['thumb'], 'displayorder' => intval($_GPC['displayorder']), 'qrtext' => trim($_GPC['qrtext']), 'qrcolor' => trim($_GPC['qrcolor']), 'qrbg' => trim($_GPC['qrbg']), 'ishow' => intval($_GPC['ishow']));
				if (!empty($id)) {
					pdo_update(BEST_CSERVICEGROUP, $data, array('id' => $id, 'weid' => $_W['uniacid']));
				} else {
					pdo_insert(BEST_CSERVICEGROUP, $data);
				}
				message('操作成功！', $this->createWebUrl('cservicegroup', array('op' => 'display')), 'success');
			}
			include $this->template('web/cservicegroup');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$cservicegroup = pdo_fetch("SELECT id FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE id = {$id}");
			if (empty($cservicegroup)) {
				message('抱歉，该客服组不存在或是已经被删除！', $this->createWebUrl('cservicegroup', array('op' => 'display')), 'error');
			}
			pdo_delete(BEST_CSERVICEGROUP, array('id' => $id));
			message('删除客服组成功！', $this->createWebUrl('cservicegroup', array('op' => 'display')), 'success');
		}
	}
	public function doMobileWtest()
	{
		include $this->template('wtest');
	}
	public function doMobileChosekefu()
	{
		global $_W, $_GPC;
		$setting = $this->setting;
		$referer = $_SERVER['HTTP_REFERER'];
		$advlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_ADV) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder ASC");
		$setting['shareurl'] = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl('chosekefu'));
		$cservicelist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ishow = 0 AND groupid = 0 ORDER BY displayorder ASC");
		foreach ($cservicelist as $k => $v) {
			$kefuandgroup = pdo_fetch("SELECT id FROM " . tablename(BEST_KEFUANDGROUP) . " WHERE kefuid = {$v['id']}");
			if (!empty($kefuandgroup)) {
				unset($cservicelist[$k]);
			}
		}
		$cservicegrouplist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = {$_W['uniacid']} AND ishow = 1 ORDER BY displayorder ASC");
		$iscservice = pdo_fetch("SELECT id FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
		if (!empty($iscservice)) {
			$notread = pdo_fetchcolumn("SELECT SUM(`notread`) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$_W['fans']['from_user']}'");
		} else {
			$notread = pdo_fetchcolumn("SELECT SUM(`kefunotread`) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$_W['fans']['from_user']}'");
		}
		if ($setting['chosekefutem'] == 0) {
			include $this->template('chosekefu');
		}
		if ($setting['chosekefutem'] == 1) {
			include $this->template('chosekefu2');
		}
	}
	public function doMobileDisanfang()
	{
		include_once 'inc/mobile/disanfang.php';
	}
	public function doMobileSanchat()
	{
		include_once 'inc/mobile/sanchat.php';
	}
	public function doMobileGroupchat()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		$setting = $this->setting;
		$referer = $_SERVER['HTTP_REFERER'];
		$id = intval($_GPC['id']);
		$setting['shareurl'] = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl('groupchat'));
		$kefuandgroup = pdo_fetchall("SELECT kefuid FROM " . tablename(BEST_KEFUANDGROUP) . " WHERE weid = {$_W['uniacid']} AND groupid = {$id}");
		$kefuids = "(";
		foreach ($kefuandgroup as $k => $v) {
			$kefuids .= $v['kefuid'] . ",";
		}
		$kefuids .= "0";
		$kefuids .= ")";
		$cservicelist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ishow = 0 AND ishow = 0 AND (groupid = {$id} OR id in {$kefuids}) ORDER BY displayorder ASC");
		$cservicegroup = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = {$_W['uniacid']} AND id = {$id}");
		$iscservice = pdo_fetch("SELECT id FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
		if (!empty($iscservice)) {
			$notread = pdo_fetchcolumn("SELECT SUM(`notread`) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$_W['fans']['from_user']}'");
		} else {
			$notread = pdo_fetchcolumn("SELECT SUM(`kefunotread`) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$_W['fans']['from_user']}'");
		}
		$qudao = trim($_GPC['qudao']);
		$goodsid = intval($_GPC['goodsid']);
		if ($setting['chosekefutem'] == 0) {
			include $this->template('groupchat');
		}
		if ($setting['chosekefutem'] == 1) {
			include $this->template('groupchat2');
		}
	}
	public function doMobileChat()
	{
		global $_W, $_GPC;
		if (empty($_W['fans']['from_user'])) {
			$openid = $_W['clientip'];
		} else {
			$openid = $_W['fans']['from_user'];
		}
		$toopenid = trim($_GPC['toopenid']);
		if ($openid == $toopenid) {
			message('不能和自己聊天！', '', 'error');
		}
		$setting = $this->setting;
		$cservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$toopenid}'");
		if (empty($cservice)) {
			message('获取客服信息失败！', '', 'error');
		}
		$hasfanskefu = pdo_fetch("SELECT * FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND kefuopenid = '{$toopenid}'");
		if (empty($hasfanskefu)) {
			$datafanskefu['weid'] = $_W['uniacid'];
			$datafanskefu['fansopenid'] = $openid;
			$datafanskefu['kefuopenid'] = $cservice['content'];
			if (empty($_W['fans']['from_user'])) {
				$datafanskefu['fansavatar'] = tomedia($setting['defaultavatar']);
				$datafanskefu['fansnickname'] = '游客';
			} else {
				$datafanskefu['fansavatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($setting['defaultavatar']) : $_W['fans']['tag']['avatar'];
				$datafanskefu['fansnickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
			}
			$datafanskefu['kefuavatar'] = tomedia($cservice['thumb']);
			$datafanskefu['kefunickname'] = $cservice['name'];
			pdo_insert(BEST_FANSKEFU, $datafanskefu);
			$hasfanskefu = pdo_fetch("SELECT * FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND kefuopenid = '{$toopenid}'");
		}
		if ($cservice['autoreply']) {
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?<<>>“”‘’]))@';
			preg_match_all($regex, $cservice['autoreply'], $array2);
			if (!empty($array2[0])) {
				foreach ($array2[0] as $kk => $vv) {
					if (!empty($vv)) {
						$cservice['autoreply'] = str_replace($vv, "<a href='" . $vv . "'>" . $vv . "</a>", $cservice['autoreply']);
					}
				}
			}
		}
		$chatcon = pdo_fetchall("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE fkid = {$hasfanskefu['id']} AND weid = {$_W['uniacid']} ORDER BY time ASC");
		$timestamp = TIMESTAMP;
		foreach ($chatcon as $k => $v) {
			$chatcon[$k]['content'] = $this->guolv($chatcon[$k]['content']);
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?<<>>“”‘’]))@';
			preg_match_all($regex, $chatcon[$k]['content'], $array2);
			if (!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)) {
				foreach ($array2[0] as $kk => $vv) {
					if (!empty($vv)) {
						$chatcon[$k]['content'] = str_replace($vv, "<a href='" . $vv . "'>" . $vv . "</a>", $chatcon[$k]['content']);
					}
				}
			}
			if ($v['type'] == 5 || $v['type'] == 6) {
				$donetime = $timestamp - $v['time'];
				if ($donetime >= 24 * 3600 * 3) {
					unset($chatcon[$k]);
				}
			}
		}
		$imglist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE fkid = {$hasfanskefu['id']} AND weid = {$_W['uniacid']} AND (type = 3 OR type = 4) ORDER BY time ASC");
		$imglistval = '';
		foreach ($imglist as $k => $v) {
			$imglistval .= $v['content'] . ',';
		}
		$imglistval = substr($imglistval, 0, -1);
		$fansauto = empty($cservice['fansauto']) ? '' : explode("|", $cservice['fansauto']);
		$goodsid = intval($_GPC['goodsid']);
		$qudao = trim($_GPC['qudao']);
		if ($qudao == 'renren' && $goodsid != 0) {
			if (pdo_tableexists('ewei_shop_goods')) {
				$goodsres = pdo_fetch("SELECT title,thumb,id,costprice FROM " . tablename('ewei_shop_goods') . " WHERE id = {$goodsid} AND uniacid = {$_W['uniacid']}");
				$goods['title'] = $goodsres['title'];
				$goods['thumb'] = tomedia($goodsres['thumb']);
				$goods['id'] = $goodsres['id'];
				$goods['price'] = $goodsres['costprice'];
			}
		}
		if ($qudao == 'super' && $goodsid != 0) {
			if (pdo_tableexists('superman_mall_item')) {
				$goodsres = pdo_fetch("SELECT title,cover,id,price FROM " . tablename('superman_mall_item') . " WHERE id = {$goodsid} AND uniacid = {$_W['uniacid']}");
				$goods['title'] = $goodsres['title'];
				$goods['thumb'] = tomedia($goodsres['cover']);
				$goods['id'] = $goodsres['id'];
				$goods['price'] = $goodsres['price'];
			}
		}
		$kefupingfen = pdo_fetch("SELECT * FROM " . tablename(BEST_PINGJIA) . " WHERE weid = {$_W['uniacid']} AND fensiopenid = '{$openid}' AND kefuopenid = '{$toopenid}'");
		include $this->template("newchat");
	}
	public function doMobileAddbiaoqian()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '未获取到您的客服信息！';
			echo json_encode($resArr);
			exit;
		}
		$name = trim($_GPC['content']);
		if (empty($name)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请填写标签内容！';
			echo json_encode($resArr);
			exit;
		}
		$toopenid = trim($_GPC['toopenid']);
		$has = pdo_fetch("SELECT * FROM " . tablename(BEST_BIAOQIAN) . " WHERE kefuopenid = '{$openid}' AND fensiopenid = '{$toopenid}' AND weid = {$_W['uniacid']}");
		if ($has) {
			pdo_update(BEST_BIAOQIAN, array('name' => $name), array('kefuopenid' => $openid, 'fensiopenid' => $toopenid, 'weid' => $_W['uniacid']));
		} else {
			$data['weid'] = $_W['uniacid'];
			$data['kefuopenid'] = $openid;
			$data['fensiopenid'] = $toopenid;
			$data['name'] = $name;
			pdo_insert(BEST_BIAOQIAN, $data);
		}
		$resArr['error'] = 0;
		$resArr['msg'] = '恭喜你添加成功！';
		echo json_encode($resArr);
		exit;
	}
	public function doMobileAddpingjia()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '未获取到您的客服信息！';
			echo json_encode($resArr);
			exit;
		}
		$pingtype = intval($_GPC['pingtype']);
		if ($pingtype <= 0) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请选择评价类型！';
			echo json_encode($resArr);
			exit;
		}
		$toopenid = trim($_GPC['toopenid']);
		$has = pdo_fetch("SELECT * FROM " . tablename(BEST_PINGJIA) . " WHERE kefuopenid = '{$toopenid}' AND fensiopenid = '{$openid}' AND weid = {$_W['uniacid']}");
		if ($has) {
			$data['pingtype'] = $pingtype;
			$data['content'] = $_GPC['content'];
			$data['time'] = TIMESTAMP;
			pdo_update(BEST_PINGJIA, $data, array('kefuopenid' => $toopenid, 'fensiopenid' => $openid, 'weid' => $_W['uniacid']));
		} else {
			$data['weid'] = $_W['uniacid'];
			$data['kefuopenid'] = $toopenid;
			$data['fensiopenid'] = $openid;
			$data['pingtype'] = $pingtype;
			$data['time'] = TIMESTAMP;
			$data['content'] = $_GPC['content'];
			pdo_insert(BEST_PINGJIA, $data);
		}
		$resArr['error'] = 0;
		$resArr['msg'] = '恭喜你评价成功！';
		echo json_encode($resArr);
		exit;
	}
	public function doMobileZhuanjie()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请在微信浏览器中打开！';
			echo json_encode($resArr);
			exit;
		}
		$toopenid = trim($_GPC['toopenid']);
		if (empty($toopenid)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '获取用户数据失败！';
			echo json_encode($resArr);
			exit;
		}
		$content = trim($_GPC['content']);
		if (empty($content)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请选择要转接的客服！';
			echo json_encode($resArr);
			exit;
		}
		$setting = $this->setting;
		$tplcon = '您收到了一条客服转接请求！';
		$hasfanskefu = pdo_fetch("SELECT * FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}' AND kefuopenid = '{$content}'");
		$zhuanjiekefu = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND content = '{$content}'");
		if (empty($hasfanskefu)) {
			$datafanskefu['weid'] = $_W['uniacid'];
			$datafanskefu['fansopenid'] = $toopenid;
			$datafanskefu['kefuopenid'] = $content;
			$account_api = WeAccount::create();
			$fansuser = $account_api->fansQueryInfo($toopenid);
			if (empty($fansuser)) {
				$datafanskefu['fansavatar'] = tomedia($setting['defaultavatar']);
				$datafanskefu['fansnickname'] = '匿名用户';
			} else {
				$datafanskefu['fansavatar'] = empty($fansuser['headimgurl']) ? tomedia($setting['defaultavatar']) : $fansuser['headimgurl'];
				$datafanskefu['fansnickname'] = empty($fansuser['nickname']) ? '匿名用户' : $fansuser['nickname'];
			}
			$datafanskefu['kefuavatar'] = tomedia($zhuanjiekefu['thumb']);
			$datafanskefu['kefunickname'] = $zhuanjiekefu['name'];
			pdo_insert(BEST_FANSKEFU, $datafanskefu);
			$fkid = pdo_insertid();
		} else {
			$fkid = $hasfanskefu['id'];
		}
		$datachat['weid'] = $_W['uniacid'];
		$datachat['fkid'] = $fkid;
		$datachat['openid'] = $content;
		$datachat['toopenid'] = $toopenid;
		$datachat['content'] = '<span class="red">系统提醒：</span><span class="hui">已转接至' . $zhuanjiekefu['name'] . '为您继续提供服务！</span>';
		$datachat['time'] = TIMESTAMPt;
		$datachat['nickname'] = $zhuanjiekefu['name'];
		$datachat['avatar'] = tomedia($zhuanjiekefu['thumb']);
		$datachat['type'] = 1;
		$datachat['time'] = TIMESTAMP;
		pdo_insert(BEST_CHAT, $datachat);
		if ($setting['istplon'] == 1) {
			$tpllist = pdo_fetch("SELECT id,tplbh FROM" . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE tplbh = 'OPENTM202109783' AND uniacid = {$_W['uniacid']}");
			if (!empty($tpllist)) {
				$arrmsg = array('openid' => $content, 'topcolor' => '#980000', 'first' => $tplcon, 'firstcolor' => '#990000', 'keyword1' => '', 'keyword1color' => '', 'keyword2' => $tplcon, 'keyword2color' => '', 'remark' => '转接时间：' . date("Y-m-d H:i:s", TIMESTAMP), 'remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("servicechat", array('toopenid' => $toopenid))));
				$this->sendtemmsg($tpllist['id'], $arrmsg);
			} else {
				$texturl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("servicechat", array('toopenid' => $toopenid)));
				$texturl = "<a href='" . $texturl . "'>点击查看</a>";
				$concon = $tplcon . '。' . $texturl;
				$send['touser'] = $content;
				$send['msgtype'] = 'text';
				$send['text'] = array('content' => urlencode($concon));
				$acc = WeAccount::create($_W['uniacid']);
				$res = $acc->sendCustomNotice($send);
			}
		}
		$resArr['error'] = 0;
		$resArr['toopenid'] = $content;
		$resArr['msg'] = '转接成功';
		echo json_encode($resArr);
		exit;
	}
	public function doMobileServicechat()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			message('请在微信浏览器中打开！', '', 'error');
		}
		$toopenid = trim($_GPC['toopenid']);
		$fansinfo = pdo_fetch("SELECT uid FROM " . tablename('mc_mapping_fans') . " WHERE openid = '{$toopenid}'");
		if (!empty($fansinfo)) {
			$fansmember = pdo_fetch("SELECT nickname,avatar,groupid,realname FROM " . tablename('mc_members') . " WHERE uid = {$fansinfo['uid']}");
			$membergroup = pdo_fetch("SELECT title FROM " . tablename('mc_groups') . " WHERE groupid = {$fansmember['groupid']}");
			if (pdo_tableexists('rhinfo_zyxq_member')) {
				$memberaddress = pdo_fetch("SELECT realname,mobile,address FROM " . tablename('rhinfo_zyxq_member') . " WHERE weid = {$_W['uniacid']} AND openid = '{$toopenid}' AND isdefault=1");
			} else {
				$memberaddress = '';
			}
		} else {
			$fansmember = '';
		}
		$cservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$openid}'");
		if (empty($cservice)) {
			message('你不是客服身份，请联系管理员查看具体信息！', '', 'error');
		}
		$hasfanskefu = pdo_fetch("SELECT * FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}' AND kefuopenid = '{$openid}'");
		if (empty($hasfanskefu)) {
			message('没有获取你和该客户的聊天信息！', '', 'error');
		}
		$othercservice = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content != '{$openid}' ORDER BY displayorder ASC");
		$biaoqian = pdo_fetch("SELECT * FROM " . tablename(BEST_BIAOQIAN) . " WHERE kefuopenid = '{$openid}' AND fensiopenid = '{$toopenid}' AND weid = {$_W['uniacid']}");
		$allbiaoqian = pdo_fetchall("SELECT * FROM " . tablename(BEST_BIAOQIAN) . " WHERE fensiopenid = '{$toopenid}' AND weid = {$_W['uniacid']}");
		$chatcon = pdo_fetchall("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE fkid = {$hasfanskefu['id']} AND weid = {$_W['uniacid']} ORDER BY time ASC");
		$timestamp = TIMESTAMP;
		foreach ($chatcon as $k => $v) {
			$chatcon[$k]['content'] = $this->guolv($chatcon[$k]['content']);
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?<<>>“”‘’]))@';
			preg_match_all($regex, $chatcon[$k]['content'], $array2);
			if (!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)) {
				foreach ($array2[0] as $kk => $vv) {
					if (!empty($vv)) {
						$chatcon[$k]['content'] = str_replace($vv, "<a href='" . $vv . "'>" . $vv . "</a>", $chatcon[$k]['content']);
					}
				}
			}
			if ($v['type'] == 5 || $v['type'] == 6) {
				$donetime = $timestamp - $v['time'];
				if ($donetime >= 24 * 3600 * 3) {
					unset($chatcon[$k]);
				}
			}
		}
		$imglist = pdo_fetchall("SELECT content FROM " . tablename(BEST_CHAT) . " WHERE fkid = {$hasfanskefu['id']} AND weid = {$_W['uniacid']} AND (type = 3 OR type = 4) ORDER BY time ASC");
		$imglistval = '';
		foreach ($imglist as $k => $v) {
			$imglistval .= $v['content'] . ',';
		}
		$imglistval = substr($imglistval, 0, -1);
		$setting = $this->setting;
		$kefuauto = empty($cservice['kefuauto']) ? '' : explode("|", $cservice['kefuauto']);
		$goodsid = intval($_GPC['goodsid']);
		$qudao = trim($_GPC['qudao']);
		if ($qudao == 'renren' && $goodsid != 0) {
			if (pdo_tableexists('ewei_shop_goods')) {
				$goodsres = pdo_fetch("SELECT title,thumb,id,costprice FROM " . tablename('ewei_shop_goods') . " WHERE id = {$goodsid} AND uniacid = {$_W['uniacid']}");
				$goods['title'] = $goodsres['title'];
				$goods['thumb'] = tomedia($goodsres['thumb']);
				$goods['id'] = $goodsres['id'];
				$goods['price'] = $goodsres['costprice'];
			}
		}
		if ($qudao == 'super' && $goodsid != 0) {
			if (pdo_tableexists('superman_mall_item')) {
				$goodsres = pdo_fetch("SELECT title,cover,id,price FROM " . tablename('superman_mall_item') . " WHERE id = {$goodsid} AND uniacid = {$_W['uniacid']}");
				$goods['title'] = $goodsres['title'];
				$goods['thumb'] = tomedia($goodsres['cover']);
				$goods['id'] = $goodsres['id'];
				$goods['price'] = $goodsres['price'];
			}
		}
		include $this->template("newservicechat");
	}
	public function doMobileAllshare()
	{
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		if (empty($openid)) {
			message('请在微信浏览器中打开！', '', 'error');
		}
		$cservice = pdo_fetch("SELECT id,groupid FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND content = '{$openid}'");
		if (empty($cservice)) {
			message('您不是客服！', '', 'error');
		}
		$setting = $this->setting;
		if ($setting['issharemsg'] == 0) {
			message('暂未开通客户记录共享功能，如需要请联系管理员在基本设置中开启！', '', 'error');
		}
		if ($setting['sharetype'] == 1 && $cservice['groupid'] == 0) {
			message('系统开启了客服组内共享功能，您不属于任何客服组！', '', 'error');
		}
		$toopenid = trim($_GPC['toopenid']);
		if ($setting['sharetype'] == 0) {
			$allfanskefu = pdo_fetchall("SELECT id FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}'");
			$fkids = "(";
			foreach ($allfanskefu as $k => $v) {
				$fkids .= $v['id'] . ",";
			}
			$fkids = substr($fkids, 0, -1) . ")";
		} else {
			$groupcservice = pdo_fetchall("SELECT b.content FROM " . tablename(BEST_KEFUANDGROUP) . " as a," . tablename(BEST_CSERVICE) . " as b	WHERE a.weid = {$_W['uniacid']} AND a.groupid = {$cservice['groupid']} AND a.kefuid = b.id AND b.ctype = 1");
			$groupcservicearr = "(";
			foreach ($groupcservice as $kk => $vv) {
				$groupcservicearr .= "'" . $vv['content'] . "',";
			}
			$groupcservicearr = substr($groupcservicearr, 0, -1) . ")";
			$allfanskefu = pdo_fetchall("SELECT id FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}' AND kefuopenid in {$groupcservicearr}");
			$fkids = "(";
			foreach ($allfanskefu as $k => $v) {
				$fkids .= $v['id'] . ",";
			}
			$fkids = substr($fkids, 0, -1) . ")";
		}
		$chatcon = pdo_fetchall("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND fkid in {$fkids} ORDER BY time ASC");
		foreach ($chatcon as $k => $v) {
			if ($v['type'] == 5 || $v['type'] == 6) {
				$donetime = TIMESTAMP - $v['time'];
				if ($donetime >= 24 * 3600 * 3) {
					unset($chatcon[$k]);
				}
			} else {
				$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
				$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?<<>>“”‘’]))@';
				preg_match_all($regex, $chatcon[$k]['content'], $array2);
				if (!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)) {
					foreach ($array2[0] as $kk => $vv) {
						if (!empty($vv)) {
							$chatcon[$k]['content'] = str_replace($vv, "<a href='" . $vv . "'>" . $vv . "</a>", $chatcon[$k]['content']);
						}
					}
				}
				$chatcon[$k]['content'] = $this->guolv($chatcon[$k]['content']);
			}
		}
		$imglist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND fkid in {$fkids} AND (type = 3 OR type = 4) ORDER BY time DESC");
		include $this->template("allshare");
	}
	public function doMobileShuaxinyuyin()
	{
		global $_W, $_GPC;
		$content = trim($_GPC['content']);
		$chat = pdo_fetch("SELECT openid,toopenid FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND content = '{$content}'");
		if ($chat['toopenid'] == $_W['fans']['from_user']) {
			pdo_update(BEST_CHAT, array('hasyuyindu' => 1), array('weid' => $_W['uniacid'], 'content' => $content));
			$resArr['error'] = 0;
			$resArr['msg'] = '语音已读成功！';
			echo json_encode($resArr);
			exit;
		} else {
			$resArr['error'] = 1;
			$resArr['msg'] = '语音已读失败！';
			echo json_encode($resArr);
			exit;
		}
	}
	public function doMobileAddgroupchat()
	{
		global $_W, $_GPC;
		include_once '../addons/cy163_customerservice/emoji/emoji.php';
		$groupid = intval($_GPC['groupid']);
		$group = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE weid = {$_W['uniacid']} AND id = {$groupid}");
		$isgmember = pdo_fetch("SELECT id FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid}");
		if (empty($isgmember)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '您不是群成员！';
			echo json_encode($resArr);
			exit;
		}
		if ($group['jinyan'] == 1 && $_W['fans']['from_user'] != $group['admin']) {
			$resArr['error'] = 1;
			$resArr['msg'] = '该群只能管理员发言！';
			echo json_encode($resArr);
			exit;
		}
		$chatcontent = trim($_GPC['content']);
		if (empty($chatcontent)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入对话内容！';
			echo json_encode($resArr);
			exit;
		}
		$chatcontent = emoji_docomo_to_unified($chatcontent);
		$chatcontent = emoji_unified_to_html($chatcontent);
		$setting = $this->setting;
		$data['openid'] = $_W['fans']['from_user'];
		$data['groupid'] = $groupid;
		$data['time'] = TIMESTAMP;
		$data['content'] = $chatcontent;
		$data['weid'] = $_W['uniacid'];
		$data['type'] = intval($_GPC['type']);
		$data['yuyintime'] = intval($_GPC['yuyintime'] / 1000) + 1;
		$iscservice = pdo_fetch("SELECT name,thumb FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND content = '{$data['openid']}'");
		if (!empty($iscservice)) {
			$data['avatar'] = tomedia($iscservice['thumb']);
			$data['nickname'] = $iscservice['name'];
		} else {
			$data['avatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($setting['defaultavatar']) : $_W['fans']['tag']['avatar'];
			$data['nickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
		}
		pdo_insert(BEST_GROUPCONTENT, $data);
		if ($data['type'] == 3) {
			$tplcon = '图片消息';
		} elseif ($data['type'] == 5) {
			$tplcon = '语音消息';
		} else {
			if (strpos($data['content'], 'span class=')) {
				$tplcon = '表情消息';
			} else {
				$tplcon = $data['content'];
			}
		}
		if ($setting['isgrouptplon'] == 1) {
			$tpllist = pdo_fetch("SELECT id,tplbh FROM" . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE tplbh = 'OPENTM207327169' AND uniacid = {$_W['uniacid']}");
			$allgroupmember = pdo_fetchall("SELECT * FROM " . tablename(BEST_GROUPMEMBER) . " WHERE weid = {$_W['uniacid']} AND groupid = {$data['groupid']} AND openid != '{$data['openid']}' AND status = 1");
			if (!empty($tpllist)) {
				foreach ($allgroupmember as $k => $v) {
					$nowtime = TIMESTAMP;
					$guotime = $nowtime - $group['lasttime'];
					if ($guotime > $setting['grouptplminute']) {
						$arrmsg = array('openid' => trim($v['openid']), 'topcolor' => '#980000', 'first' => '群聊消息提醒', 'firstcolor' => '#990000', 'keyword1' => date("Y-m-d H:i:s", TIMESTAMP), 'keyword1color' => '', 'keyword2' => 1, 'keyword2color' => '', 'remark' => '群聊内容：' . $tplcon, 'remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("groupchatdetail", array('groupid' => $data['groupid']))));
						$this->sendtemmsg($tpllist['id'], $arrmsg);
					}
				}
			} else {
				foreach ($allgroupmember as $k => $v) {
					$nowtime = TIMESTAMP;
					$guotime = $nowtime - $group['lasttime'];
					if ($guotime > $setting['grouptplminute']) {
						$texturl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("groupchatdetail", array('groupid' => $data['groupid'])));
						$texturl = "<a href='" . $texturl . "'>点击查看</a>";
						$concon = '您有新的群聊消息！' . $texturl;
						$send['touser'] = trim($v['openid']);
						$send['msgtype'] = 'text';
						$send['text'] = array('content' => urlencode($concon));
						$acc = WeAccount::create($_W['uniacid']);
						$res = $acc->sendCustomNotice($send);
					}
				}
			}
		}
		pdo_update(BEST_GROUP, array('lasttime' => TIMESTAMP), array('id' => $data['groupid']));
		$resArr['error'] = 0;
		$resArr['msg'] = '';
		$resArr['content'] = $this->doReplacecon($data['content'], $data['type'], $data['yuyintime']);
		$resArr['yuyincon'] = $data['type'] == 5 ? '<span class="miaoshu">' . $data['yuyintime'] . '\'\'</span>' : '';
		$resArr['datetime'] = date("Y-m-d H:i:s", $data['time']);
		$resArr['nickname'] = $data['nickname'];
		$resArr['avatar'] = $data['avatar'];
		echo json_encode($resArr);
		exit;
	}
	public function doMobileSeetime()
	{
		global $_W, $_GPC;
		$fkid = intval($_GPC['fkid']);
		$type = trim($_GPC['type']);
		if ($type == 'fans') {
			$data['fansseetime'] = TIMESTAMP;
			$data['kefunotread'] = 0;
			pdo_update(BEST_FANSKEFU, $data, array('id' => $fkid));
		}
		if ($type == 'kefu') {
			$data['kefuseetime'] = TIMESTAMP;
			$data['notread'] = 0;
			pdo_update(BEST_FANSKEFU, $data, array('id' => $fkid));
		}
	}
	public function doMobileAddchat()
	{
		global $_W, $_GPC;
		include_once '../addons/cy163_customerservice/emoji/emoji.php';
		$chatcontent = trim($_GPC['content']);
		if (empty($chatcontent)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入对话内容！';
			echo json_encode($resArr);
			exit;
		}
		$qudao = trim($_GPC['qudao']);
		$goodsid = intval($_GPC['goodsid']);
		$cservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_GPC['toopenid']}'");
		if ($cservice['lingjie'] == 1) {
			$nowhour = intval(date("H", TIMESTAMP));
			if ($nowhour + 1 > $cservice['endhour'] && $nowhour < $cservice['starthour']) {
				$notonlinemsg = !empty($cservice['notonline']) ? $cservice['notonline'] : '客服不在线哦！';
				$resArr['error'] = 1;
				$resArr['msg'] = $notonlinemsg;
				echo json_encode($resArr);
				exit;
			}
		} else {
			$nowhour = intval(date("H", TIMESTAMP));
			if ($nowhour < $cservice['starthour'] || $nowhour + 1 > $cservice['endhour']) {
				$notonlinemsg = !empty($cservice['notonline']) ? $cservice['notonline'] : '客服不在线哦！';
				$resArr['error'] = 1;
				$resArr['msg'] = $notonlinemsg;
				echo json_encode($resArr);
				exit;
			}
		}
		$chatcontent = emoji_docomo_to_unified($chatcontent);
		$chatcontent = emoji_unified_to_html($chatcontent);
		$setting = $this->setting;
		if (empty($_W['fans']['from_user'])) {
			$data['openid'] = $_W['clientip'];
			$jqruserid = str_replace(".", "", $data['openid']);
			$data['nickname'] = '游客';
			$data['avatar'] = tomedia($setting['defaultavatar']);
		} else {
			$data['openid'] = $_W['fans']['from_user'];
			$jqruserid = $data['openid'];
			$data['nickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
			$data['avatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($setting['defaultavatar']) : $_W['fans']['tag']['avatar'];
		}
		$data['toopenid'] = trim($_GPC['toopenid']);
		$data['time'] = TIMESTAMP;
		$data['content'] = $chatcontent;
		$data['weid'] = $_W['uniacid'];
		$data['fkid'] = intval($_GPC['fkid']);
		$type = intval($_GPC['type']);
		$data['type'] = $type;
		$data['yuyintime'] = intval($_GPC['yuyintime'] / 1000) + 1;
		if ($type == 3 || $type == 4) {
			$tplcon = $data['nickname'] . '发送了图片';
		} elseif ($type == 5 || $type == 6) {
			$tplcon = $data['nickname'] . '发送了语音';
		} else {
			if (strpos($data['content'], 'span class=')) {
				$tplcon = $data['nickname'] . '发送了表情';
			} else {
				$tplcon = $data['content'];
			}
		}
		$tplcon = $this->guolv($tplcon);
		pdo_insert(BEST_CHAT, $data);
		pdo_query("update " . tablename(BEST_FANSKEFU) . " set notread=notread+1,guanlinum=guanlinum+1 where id=:id", array(":id" => $data['fkid']));
		$resArr['jqr'] = '';
		if ($setting['tulingkey'] != '' && $data['type'] == 1 && $setting['istulingon'] == 1 && !empty($_W['fans']['from_user'])) {
			$hzw_key = array('key' => $setting['tulingkey'], 'info' => $data['content'], 'userid' => $jqruserid);
			$hzw_returnData = ihttp_post("http://www.tuling123.com/openapi/api", $hzw_key);
			$hzw_replay = json_decode($hzw_returnData["content"], true);
			$hzw_replay = $hzw_replay["text"];
			$errcodearr = array(40001, 40002, 40004, 40007);
			if ($hzw_replay && !in_array($hzw_replay["code"], $errcodearr) && $hzw_replay != $data['content']) {
				$csrvice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$data['toopenid']}'");
				$datajqr['openid'] = $csrvice['content'];
				$datajqr['nickname'] = $csrvice['name'];
				$datajqr['avatar'] = tomedia($csrvice['thumb']);
				$datajqr['toopenid'] = $data['openid'];
				$datajqr['time'] = TIMESTAMP + 2;
				$datajqr['weid'] = $_W['uniacid'];
				$datajqr['type'] = 2;
				$datajqr['fkid'] = $data['fkid'];
				$datajqr['content'] = $hzw_replay;
				$datajqr['isjqr'] = 1;
				pdo_insert(BEST_CHAT, $datajqr);
				$resArr['jqr'] = $hzw_replay;
			}
		}
		$fanskefu = pdo_fetch("SELECT lasttime,kefuseetime FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$data['toopenid']}' AND fansopenid = '{$data['openid']}'");
		$guotime = TIMESTAMP - $fanskefu['lasttime'];
		$guotime2 = TIMESTAMP - $fanskefu['kefuseetime'];
		if ($setting['istplon'] == 1 && $guotime > $setting['kefutplminute'] && $guotime2 > $setting['kefutplminute']) {
			$tpllist = pdo_fetch("SELECT id,tplbh FROM" . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE tplbh = 'OPENTM207327169' AND uniacid = {$_W['uniacid']}");
			if (!empty($tpllist)) {
				$denghounum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$data['toopenid']}' AND notread > 0");
				$arrmsg = array('openid' => $data['toopenid'], 'topcolor' => '#980000', 'first' => $data['nickname'] . '发起了咨询', 'firstcolor' => '#990000', 'keyword1' => date("Y-m-d H:i:s", TIMESTAMP), 'keyword1color' => '', 'keyword2' => $denghounum, 'keyword2color' => '', 'remark' => $tplcon, 'remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("servicechat", array('toopenid' => $data['openid'], 'qudao' => $qudao, 'goodsid' => $goodsid))));
				$this->sendtemmsg($tpllist['id'], $arrmsg);
			} else {
				$texturl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("servicechat", array('toopenid' => $data['openid'], 'qudao' => $qudao, 'goodsid' => $goodsid)));
				$texturl = "<a href='" . $texturl . "'>点击查看</a>";
				$concon = $data['nickname'] . '发起了咨询！' . $tplcon . '。' . $texturl;
				$send['touser'] = trim($_GPC['toopenid']);
				$send['msgtype'] = 'text';
				$send['text'] = array('content' => urlencode($concon));
				$acc = WeAccount::create($_W['uniacid']);
				$res = $acc->sendCustomNotice($send);
			}
		}
		pdo_query("update " . tablename(BEST_FANSKEFU) . " set lastcon='{$chatcontent}',msgtype={$type},lasttime=:lasttime where id=:id", array(":lasttime" => TIMESTAMP, ":id" => $data['fkid']));
		$resArr['error'] = 0;
		$resArr['msg'] = '';
		$resArr['content'] = $this->doReplacecon($data['content'], $data['type'], $data['yuyintime']);
		$resArr['yuyincon'] = $data['type'] == 5 ? '<span class="miaoshu">' . $data['yuyintime'] . '\'\'</span>' : '';
		$resArr['datetime'] = date("Y-m-d H:i:s", $data['time']);
		$resArr['jqrtime'] = date("Y-m-d H:i:s", $data['time'] + 2);
		echo json_encode($resArr);
		exit;
	}
	public function doMobileAddchat2()
	{
		global $_W, $_GPC;
		include_once '../addons/cy163_customerservice/emoji/emoji.php';
		$chatcontent = trim($_GPC['content']);
		if (empty($chatcontent)) {
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入对话内容！';
			echo json_encode($resArr);
			exit;
		}
		$qudao = trim($_GPC['qudao']);
		$goodsid = intval($_GPC['goodsid']);
		$chatcontent = emoji_docomo_to_unified($chatcontent);
		$chatcontent = emoji_unified_to_html($chatcontent);
		$setting = $this->setting;
		$data['openid'] = $_W['fans']['from_user'];
		$touser = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND content = '{$data['openid']}'");
		$data['nickname'] = $touser['name'];
		$data['avatar'] = tomedia($touser['thumb']);
		$data['toopenid'] = trim($_GPC['toopenid']);
		$data['time'] = TIMESTAMP;
		$data['content'] = $chatcontent;
		$data['weid'] = $_W['uniacid'];
		$data['fkid'] = intval($_GPC['fkid']);
		$type = intval($_GPC['type']);
		$data['type'] = $type;
		$data['yuyintime'] = intval($_GPC['yuyintime'] / 1000) + 1;
		if ($type == 3 || $type == 4) {
			$tplcon = $data['nickname'] . '给您发送了图片';
		} elseif ($type == 5 || $type == 6) {
			$tplcon = $data['nickname'] . '给您发送了语音';
		} else {
			if (strpos($data['content'], 'span class=')) {
				$tplcon = $data['nickname'] . '给您发送了表情';
			} else {
				$tplcon = $data['content'];
			}
		}
		$tplcon = $this->guolv($tplcon);
		$fanskefu = pdo_fetch("SELECT kefulasttime,fansseetime FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$data['openid']}' AND fansopenid = '{$data['toopenid']}'");
		$guotime = TIMESTAMP - $fanskefu['kefulasttime'];
		$guotime2 = TIMESTAMP - $fanskefu['fansseetime'];
		if ($setting['istplon'] == 1 && $guotime > $setting['kefutplminute'] && $guotime2 > $setting['kefutplminute']) {
			$tpllist = pdo_fetch("SELECT id,tplbh FROM" . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE tplbh = 'OPENTM202109783' AND uniacid = {$_W['uniacid']}");
			if (!empty($tpllist)) {
				$lastmsg = pdo_fetch("SELECT content,type FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND openid = '{$data['toopenid']}' AND toopenid = '{$data['openid']}' ORDER BY time DESC");
				if ($lastmsg['type'] == 3 || $lastmsg['type'] == 4) {
					$lastmsgcon = '图片消息';
				} elseif ($lastmsg['type'] == 5 || $lastmsg['type'] == 6) {
					$lastmsgcon = '语音消息';
				} else {
					$lastmsgcon = $lastmsg['content'];
				}
				$arrmsg = array('openid' => $data['toopenid'], 'topcolor' => '#980000', 'first' => $data['nickname'] . '回复了您！', 'firstcolor' => '#990000', 'keyword1' => $lastmsgcon, 'keyword1color' => '', 'keyword2' => $tplcon, 'keyword2color' => '', 'remark' => '回复时间：' . date("Y-m-d H:i:s", TIMESTAMP), 'remarkcolor' => '', 'url' => $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("chat", array('toopenid' => $data['openid'], 'qudao' => $qudao, 'goodsid' => $goodsid))));
				$this->sendtemmsg($tpllist['id'], $arrmsg);
			} else {
				$texturl = $_W["siteroot"] . 'app/' . str_replace("./", "", $this->createMobileUrl("chat", array('toopenid' => $data['openid'], 'qudao' => $qudao, 'goodsid' => $goodsid)));
				$texturl = "<a href='" . $texturl . "'>点击查看</a>";
				$concon = $data['nickname'] . '回复了您！回复内容：' . $tplcon . '。' . $texturl;
				$send['touser'] = trim($_GPC['toopenid']);
				$send['msgtype'] = 'text';
				$send['text'] = array('content' => urlencode($concon));
				$acc = WeAccount::create($_W['uniacid']);
				$res = $acc->sendCustomNotice($send);
			}
		}
		pdo_insert(BEST_CHAT, $data);
		pdo_query("update " . tablename(BEST_FANSKEFU) . " set kefulastcon='{$chatcontent}',kefulasttime=:kefulasttime,kefumsgtype={$type},kefunotread=kefunotread+1,guanlinum=guanlinum+1 where id=:id", array(":kefulasttime" => TIMESTAMP, ":id" => $data['fkid']));
		$resArr['error'] = 0;
		$resArr['msg'] = '';
		$resArr['content'] = $this->doReplacecon($data['content'], $data['type'], $data['yuyintime']);
		$resArr['yuyincon'] = $data['type'] == 6 ? '<span class="miaoshu">' . $data['yuyintime'] . '\'\'</span>' : '';
		$resArr['datetime'] = date("Y-m-d H:i:s", $data['time']);
		echo json_encode($resArr);
		exit;
	}
	public function doReplacecon($content, $msgtype, $yuyintime)
	{
		$content = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $content);
		$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?<<>>“”‘’]))@';
		preg_match_all($regex, $content, $array2);
		if (!empty($array2[0]) && ($msgtype == 1 || $msgtype == 2)) {
			foreach ($array2[0] as $kk => $vv) {
				if (!empty($vv)) {
					$content = str_replace($vv, "<a href='" . $vv . "'>" . $vv . "</a>", $content);
				}
			}
		}
		if ($msgtype == 3 || $msgtype == 4) {
			$content = '<img src="' . $content . '" class="sssbbb" style="max-width:100%;" />';
		} elseif ($msgtype == 5 || $msgtype == 6) {
			$content = '<span class="audio-msg voiceplay" style="width:' . ($yuyintime * 3.5 / 60 + 0.3) . 'rem;" onclick="playvoice(\'' . $content . '\',$(this).parents(\'.txt-con\').next(\'.weidu\'));"><i class="a-icon iconfont">&#xe601;</i></span>';
		}
		return $content;
	}
	public function doMobileGetmedia()
	{
		global $_W, $_GPC;
		$setting = $this->setting;
		$access_token = WeAccount::token();
		$media_id = $_GPC['media_id'];
		if (empty($media_id)) {
			$resarr['error'] = 1;
			$resarr['message'] = '获取微信媒体参数失败！';
			exit(json_encode($resarr));
		}
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $media_id;
		$updir = "../attachment/images/" . $_W['uniacid'] . "/" . date("Y", time()) . "/" . date("m", time()) . "/";
		if (!file_exists($updir)) {
			mkdir($updir, 511, true);
		}
		$randimgurl = "images/" . $_W['uniacid'] . "/" . date("Y", time()) . "/" . date("m", time()) . "/" . date('YmdHis') . rand(1000, 9999) . '.jpg';
		$targetName = "../attachment/" . $randimgurl;
		$ch = curl_init($url);
		$fp = fopen($targetName, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		if (file_exists($targetName)) {
			$resarr['error'] = 0;
			$this->mkThumbnail($targetName, 640, 0, $targetName);
			if ($setting['isqiniu'] == 1) {
				$remotestatus = $this->doQiuniu($randimgurl, true);
				if (is_error($remotestatus)) {
					$resarr['error'] = 1;
					$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
					exit(json_encode($resarr));
				} else {
					$resarr['realimgurl'] = $randimgurl;
					$resarr['imgurl'] = $setting['qiniuurl'] . "/" . $randimgurl;
					$resarr['message'] = '上传成功';
					exit(json_encode($resarr));
				}
			} elseif ($setting['isqiniu'] == 3) {
				if (!empty($_W['setting']['remote']['type'])) {
					load()->func('file');
					$remotestatus = file_remote_upload($randimgurl, true);
					if (is_error($remotestatus)) {
						$resarr['error'] = 1;
						$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
						exit(json_encode($resarr));
					} else {
						$resarr['realimgurl'] = $randimgurl;
						$resarr['imgurl'] = tomedia($randimgurl);
						$resarr['message'] = '上传成功';
						exit(json_encode($resarr));
					}
				}
			}
			$resarr['realimgurl'] = $randimgurl;
			$resarr['imgurl'] = tomedia($randimgurl);
			$resarr['message'] = '上传成功';
		} else {
			$resarr['error'] = 1;
			$resarr['message'] = '上传失败';
		}
		echo json_encode($resarr, true);
		exit;
	}
	public function doQiuniu($filename, $auto_delete_local = true)
	{
		global $_W;
		load()->func('file');
		$setting = $this->setting;
		require_once IA_ROOT . '/framework/library/qiniu/autoload.php';
		$auth = new Qiniu\Auth($setting['qiniuaccesskey'], $setting['qiniusecretkey']);
		$config = new Qiniu\Config();
		$uploadmgr = new Qiniu\Storage\UploadManager($config);
		$putpolicy = Qiniu\base64_urlSafeEncode(json_encode(array('scope' => $setting['qiniubucket'] . ':' . $filename)));
		$uploadtoken = $auth->uploadToken($setting['qiniubucket'], $filename, 3600, $putpolicy);
		list($ret, $err) = $uploadmgr->putFile($uploadtoken, $filename, ATTACHMENT_ROOT . '/' . $filename);
		if ($auto_delete_local) {
			file_delete($filename);
		}
		if ($err !== null) {
			$resarr['error'] = 1;
			$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
			exit(json_encode($resarr));
		} else {
			return true;
		}
	}
	public function doMobileGetvoice()
	{
		global $_W, $_GPC;
		$access_token = WeAccount::token();
		$media_id = $_GPC['media_id'];
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $media_id;
		$updir = "../attachment/audios/" . $_W['uniacid'] . "/" . date("Y", time()) . "/" . date("m", time()) . "/";
		if (!file_exists($updir)) {
			mkdir($updir, 511, true);
		}
		$randvoiceurl = "audios/" . $_W['uniacid'] . "/" . date("Y", time()) . "/" . date("m", time()) . "/" . date('YmdHis') . rand(1000, 9999) . '.amr';
		$targetName = "../attachment/" . $randvoiceurl;
		$ch = curl_init($url);
		$fp = fopen($targetName, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		if (file_exists($targetName)) {
			$resarr['error'] = 0;
			if (!empty($_W['setting']['remote']['type'])) {
				load()->func('file');
				$remotestatus = file_remote_upload($randvoiceurl, true);
				if (is_error($remotestatus)) {
					$resarr['error'] = 1;
					$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
					file_delete($randvoiceurl);
					exit(json_encode($resarr));
				} else {
					file_delete($randvoiceurl);
					$resarr['realvoiceurl'] = $randvoiceurl;
					$resarr['voiceurl'] = tomedia($randvoiceurl);
					$resarr['message'] = '上传成功';
					exit(json_encode($resarr));
				}
			}
			$resarr['realvoiceurl'] = $randvoiceurl;
			$resarr['voiceurl'] = tomedia($randvoiceurl);
			$resarr['message'] = '上传成功';
		} else {
			$resarr['error'] = 1;
			$resarr['message'] = '上传失败';
		}
		echo json_encode($resarr, true);
		exit;
	}
	public function doWebTpllist()
	{
		global $_W;
		$list = pdo_fetchall("SELECT * FROM " . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE uniacid = {$_W['uniacid']} ORDER BY id ASC");
		include $this->template('web/tpllist');
	}
	public function doWebCreatetpl()
	{
		global $_GPC, $_W;
		$tplbh = trim($_GPC['tplbh']);
		$istplbh = pdo_fetch("SELECT * FROM " . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE uniacid = {$_W['uniacid']} AND tplbh = '{$tplbh}'");
		if (!empty($istplbh)) {
			message('您已添加该模板消息！', $this->createWebUrl('Tpllist'), 'error');
		} else {
			$account_api = WeAccount::create();
			$token = $account_api->getAccessToken();
			if (is_error($token)) {
				message('获取access token 失败');
			}
			$url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token={$token}";
			$postdata = array('template_id_short' => $tplbh);
			$response = ihttp_request($url, urldecode(json_encode($postdata)));
			$errarr = json_decode($response['content'], true);
			if ($errarr['errcode'] == 0) {
				$data = array('tplbh' => $tplbh, 'tpl_id' => $errarr['template_id'], 'uniacid' => $_W['uniacid']);
				pdo_insert(BEST_TPLMESSAGE_TPLLIST, $data);
				message('添加模板消息成功！', $this->createWebUrl('Tpllist'), 'success');
				return;
			} else {
				message($errarr['errmsg'], $this->createWebUrl('Tpllist'), 'error');
			}
		}
	}
	public function doWebdeltpl()
	{
		global $_GPC, $_W;
		$tplid = trim($_GPC['tplid']);
		$istplbh = pdo_fetch("SELECT * FROM " . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE uniacid = {$_W['uniacid']} AND tpl_id = '{$tplid}'");
		if (empty($istplbh)) {
			message('没有该模板消息！', $this->createWebUrl('Tpllist'), 'error');
		} else {
			if (empty($istplbh['tpl_key'])) {
				message('该该模板消息没有同步，不能删除！', $this->createWebUrl('Tpllist'), 'error');
			}
			$account_api = WeAccount::create();
			$token = $account_api->getAccessToken();
			if (is_error($token)) {
				message('获取access token 失败');
			}
			$url = "https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token={$token}";
			$postjson = '{"template_id":"' . $tplid . '"}';
			$response = ihttp_request($url, $postjson);
			$errarr = json_decode($response['content'], true);
			if ($errarr['errcode'] == 0) {
				pdo_delete(BEST_TPLMESSAGE_TPLLIST, array('tpl_id' => $tplid));
				message('删除模板消息成功！', $this->createWebUrl('Tpllist'), 'success');
				return;
			} else {
				message($errarr['errmsg'], $this->createWebUrl('Tpllist'), 'error');
			}
		}
	}
	public function doWebdeltpl2()
	{
		global $_GPC, $_W;
		$tplid = trim($_GPC['tplid']);
		$istplbh = pdo_fetch("SELECT * FROM " . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE uniacid = {$_W['uniacid']} AND tpl_id = '{$tplid}'");
		if (empty($istplbh)) {
			message('没有该模板消息！', $this->createWebUrl('Tpllist'), 'error');
		} else {
			pdo_delete(BEST_TPLMESSAGE_TPLLIST, array('tpl_id' => $tplid));
			message('删除模板消息数据成功！', $this->createWebUrl('Tpllist'), 'success');
		}
	}
	public function doWebUpdateTpl()
	{
		global $_W;
		$success = 0;
		$account_api = WeAccount::create();
		$token = $account_api->getAccessToken();
		if (is_error($token)) {
			message('获取access token 失败');
		}
		$url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$token}";
		$response = ihttp_request($url, urldecode(json_encode($data)));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$list = json_decode($response['content'], true);
		if (empty($list['template_list'])) {
			message('访问公众平台接口失败, 错误: 模板列表返回为空');
		}
		foreach ($list['template_list'] as $k => $v) {
			$template_id = $v['template_id'];
			$data['tpl_title'] = $v['title'];
			preg_match_all('/{{(.*?)\.DATA}}/', $v['content'], $key);
			preg_match_all('/}}\n*(.*?){{/', $v['content'], $title);
			$keys = $this->formatTplKey($key[1], $title[1]);
			$data['tpl_key'] = serialize($keys);
			$data['tpl_example'] = $v['example'];
			pdo_update(BEST_TPLMESSAGE_TPLLIST, $data, array('tpl_id' => $template_id));
		}
		message('更新完闭！', $this->createWebUrl('Tpllist'), 'success');
	}
	public function formatTplKey($key, $title)
	{
		$keys = array();
		for ($i = 0; $i < count($key); $i++) {
			if (empty($key[$i])) {
				continue;
			}
			if ($i == 0) {
				$keys[$i]['title'] = "首标题：";
				$keys[$i]['key'] = $key[$i];
				continue;
			}
			if ($i == count($key) - 1) {
				$keys[$i]['title'] = "尾备注：";
				$keys[$i]['key'] = $key[$i];
				continue;
			}
			$keys[$i]['title'] = $title[$i - 1];
			$keys[$i]['key'] = $key[$i];
		}
		return $keys;
	}
	public function doWebSendone()
	{
		global $_W, $_GPC;
		$tpllist = pdo_fetchall("SELECT * FROM " . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE uniacid = {$_W['uniacid']} ORDER BY id");
		if (empty($tpllist)) {
			message("请先同步模板！", $this->createWebUrl('Tpllist'), 'error');
			exit;
		}
		$data['tplid'] = empty($_GPC['tplid']) ? $tpllist[0]['id'] : $_GPC['tplid'];
		$tpldetailed = pdo_fetch("SELECT * FROM " . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE id = {$data['tplid']} LIMIT 1");
		$tplkeys = unserialize($tpldetailed['tpl_key']);
		include $this->template('web/sendone');
	}
	public function doWebSendOneSumbit()
	{
		global $_W, $_GPC;
		$account_api = WeAccount::create();
		$token = $account_api->getAccessToken();
		if (is_error($token)) {
			message('获取access token 失败');
		}
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
		$tpldetailed = pdo_fetch("SELECT * FROM " . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE id = {$_GPC['tplid']} LIMIT 1");
		$tplkeys = unserialize($tpldetailed['tpl_key']);
		$postData = array();
		$postData['template_id'] = $tpldetailed['tpl_id'];
		$postData['url'] = $_GPC['url'];
		$postData['topcolor'] = $_GPC['topcolor'];
		foreach ($tplkeys as $value) {
			$postData['data'][$value['key']]['value'] = $_GPC[$value['key']];
			$postData['data'][$value['key']]['color'] = $_GPC[$value['key'] . 'color'];
		}
		pdo_insert(BEST_TPLMESSAGE_SENDLOG, array('tpl_id' => $_GPC['tplid'], 'tpl_title' => $tpldetailed['tpl_title'], 'message' => serialize($postData), 'time' => time(), 'uniacid' => $_W['uniacid'], 'target' => implode(",", $_GPC['openid']), 'type' => 1));
		$tid = pdo_insertid();
		if ($tid <= 0) {
			message('抱歉,请求失败', 'referer', 'error');
		}
		$openid = $_GPC['openid'];
		$success = 0;
		$fail = 0;
		$error = "";
		foreach ($openid as $value) {
			$postData['touser'] = $value;
			$res = ihttp_post($url, json_encode($postData));
			$re = json_decode($res['content'], true);
			if ($re['errmsg'] == 'ok') {
				$success++;
			} else {
				$fail++;
				$error .= $value . ",";
			}
		}
		pdo_update(BEST_TPLMESSAGE_SENDLOG, array('success' => $success, 'fail' => $fail, 'error' => $error, 'status' => 1), array('id' => $tid));
		if ($success <= 0) {
			message("发送失败！", 'referer', 'error');
		}
		message("发送成功，总计：" . ($success + $fail) . "人，成功：{$success} 人，失败：{$fail} 人", $this->createWebUrl('SendOnelog'), 'success');
	}
	public function doWebSendOnelog()
	{
		global $_W, $_GPC;
		$page = empty($_GPC['page']) ? 1 : $_GPC['page'];
		$pagesize = 20;
		$total = pdo_fetch("SELECT COUNT(id) AS num FROM " . tablename(BEST_TPLMESSAGE_SENDLOG) . " WHERE type = 1 AND uniacid = {$_W['uniacid']} ");
		$list = pdo_fetchall("SELECT a.id,a.success,a.fail,a.time,a.target,a.status,a.tpl_title as title,a.error FROM " . tablename(BEST_TPLMESSAGE_SENDLOG) . " AS a WHERE a.type = 1 AND a.uniacid = {$_W['uniacid']} ORDER BY time DESC LIMIT " . ($page - 1) * $pagesize . "," . $pagesize);
		$pagination = pagination($total['num'], $page, $pagesize);
		include $this->template("web/sendonelog");
	}
	public function sendtemmsg($tplid, $arrmsg)
	{
		global $_W, $_GPC;
		$account_api = WeAccount::create();
		$token = $account_api->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
		$tpldetailed = pdo_fetch("SELECT * FROM " . tablename(BEST_TPLMESSAGE_TPLLIST) . " WHERE id = {$tplid} LIMIT 1");
		$tplkeys = unserialize($tpldetailed['tpl_key']);
		$postData = array();
		$postData['template_id'] = $tpldetailed['tpl_id'];
		$postData['url'] = $arrmsg['url'];
		$postData['topcolor'] = $arrmsg['topcolor'];
		foreach ($tplkeys as $value) {
			$postData['data'][$value['key']]['value'] = $arrmsg[$value['key']];
			$postData['data'][$value['key']]['color'] = $arrmsg[$value['key'] . 'color'];
		}
		pdo_insert(BEST_TPLMESSAGE_SENDLOG, array('tpl_id' => $tplid, 'tpl_title' => $tpldetailed['tpl_title'], 'message' => serialize($postData), 'time' => time(), 'uniacid' => $_W['uniacid'], 'target' => $arrmsg['openid'], 'type' => 1));
		$tid = pdo_insertid();
		$success = 0;
		$fail = 0;
		$error = "";
		$postData['touser'] = $arrmsg['openid'];
		$res = ihttp_post($url, json_encode($postData));
		$re = json_decode($res['content'], true);
		if ($re['errmsg'] == 'ok') {
			$success++;
		} else {
			$fail++;
			$error .= $openid;
		}
		pdo_update(BEST_TPLMESSAGE_SENDLOG, array('success' => $success, 'fail' => $fail, 'error' => $error, 'status' => 1), array('id' => $tid));
	}
	public function doMobileMychat()
	{
		global $_W, $_GPC;
		if (empty($_W['fans']['from_user'])) {
			$openid = $_W['clientip'];
		} else {
			$openid = $_W['fans']['from_user'];
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$setting = $this->setting;
		if ($operation == 'display') {
			$isservice = pdo_fetch("SELECT id FROM " . tablename(BEST_CSERVICE) . " WHERE ctype = 1 AND weid = {$_W['uniacid']} AND content = '{$openid}'");
			if (!empty($isservice)) {
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND lastcon != ''");
				$allpage = ceil($total / 10) + 1;
				$page = intval($_GPC["page"]);
				$pindex = max(1, $page);
				$psize = 10;
				$chatlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}'  AND lastcon != '' ORDER BY notread DESC,lasttime DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
			} else {
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND lastcon != ''");
				$allpage = ceil($total / 10) + 1;
				$page = intval($_GPC["page"]);
				$pindex = max(1, $page);
				$psize = 10;
				$chatlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_FANSKEFU) . " WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND lastcon != '' ORDER BY notread DESC,lasttime DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
			}
			$isajax = intval($_GPC['isajax']);
			if ($isajax == 1) {
				$html = '';
				foreach ($chatlist as $kk => $vv) {
					if (!empty($isservice)) {
						if ($vv['msgtype'] == 4) {
							$con = '<span style="color:#900;">[图片消息]</span>';
						} elseif ($vv['msgtype'] == 5) {
							$con = '<span style="color:green;">[语音消息]</span>';
						} else {
							$con = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $vv['lastcon']);
						}
						$mychatbadge = $vv['notread'] > 0 ? '<span class="mychatbadge">' . $vv['notread'] . '</span>' : '';
						$html .= '<div class="item">
									<a href="' . $this->createMobileUrl('servicechat', array('toopenid' => $vv['fansopenid'])) . '">
										<div class="left">
											<div class="img">
												<img src="' . $vv['fansavatar'] . '">
												' . $mychatbadge . '
											</div>
											<div class="text">
												<div class="name">
													' . $vv['fansnickname'] . '
													<span style="color:#999;margin-left:0.1rem;font-size:0.23rem;">' . date("Y-m-d H:i:s", $vv['lasttime']) . '</span>
												</div>
												<div class="zu">
													' . $con . '
												</div>
											</div>
										</div>
									</a>
									<a onclick="return confirm(\'确认要删除聊天记录吗？\');return false;" href="' . $this->createMobileUrl('mychat', array('op' => 'delete', 'fkid' => $vv['id'])) . '">
										<div class="right iconfont">&#xe736;</div>
									</a>
								</div>';
					} else {
						if ($vv['kefumsgtype'] == 3) {
							$con = '<span style="color:#900;">[图片消息]</span>';
						} elseif ($vv['kefumsgtype'] == 6) {
							$con = '<span style="color:green;">[语音消息]</span>';
						} else {
							$con = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $vv['kefulastcon']);
						}
						$mychatbadge = $vv['kefunotread'] > 0 ? '<span class="mychatbadge">' . $vv['kefunotread'] . '</span>' : '';
						$html .= '<div class="item">
									<a href="' . $this->createMobileUrl('servicechat', array('toopenid' => $vv['kefuopenid'])) . '">
										<div class="left">
											<div class="img">
												<img src="' . $vv['kefuavatar'] . '">
												' . $mychatbadge . '
											</div>
											<div class="text">
												<div class="name">
													' . $vv['kefuonickname'] . '
													<span style="color:#999;margin-left:0.1rem;font-size:0.23rem;">' . date("Y-m-d H:i:s", $vv['kefuolasttime']) . '</span>
												</div>
												<div class="zu">
													' . $con . '
												</div>
											</div>
										</div>
									</a>
									<a onclick="return confirm(\'确认要删除聊天记录吗？\');return false;" href="' . $this->createMobileUrl('mychat', array('op' => 'delete', 'fkid' => $vv['id'])) . '">
										<div class="right iconfont">&#xe736;</div>
									</a>
								</div>';
					}
				}
				echo $html;
				exit;
			}
			include $this->template('mychat');
		} elseif ($operation == 'delete') {
			if ($setting['candel'] == 1) {
				message('管理员关闭了聊天记录删除功能！请求失败', '', 'error');
			}
			$fkid = intval($_GPC['fkid']);
			$message = pdo_fetch("SELECT * FROM " . tablename(BEST_CHAT) . " WHERE weid = {$_W['uniacid']} AND fkid = {$fkid}");
			if (empty($message)) {
				message('抱歉不存在该聊天记录！请求失败', '', 'error');
			}
			pdo_delete(BEST_CHAT, array('fkid' => $fkid));
			$dataup['lasttime'] = 0;
			$dataup['notread'] = 0;
			$dataup['lastcon'] = '';
			$dataup['kefulasttime'] = 0;
			$dataup['kefulastcon'] = '';
			$dataup['kefunotread'] = 0;
			$dataup['kefuseetime'] = 0;
			$dataup['fansseetime'] = 0;
			pdo_update(BEST_FANSKEFU, $dataup, array('weid' => $_W['uniacid'], 'id' => $fkid));
			message('恭喜您，删除聊天记录成功！', $this->createMobileUrl('mychat'), 'success');
		}
	}

	public function mkThumbnail($src, $width = null, $height = null, $filename = null)
	{
		if (!isset($width) && !isset($height)) {
			return false;
		}
		if (isset($width) && $width <= 0) {
			return false;
		}
		if (isset($height) && $height <= 0) {
			return false;
		}
		$size = getimagesize($src);
		if (!$size) {
			return false;
		}
		list($src_w, $src_h, $src_type) = $size;
		$src_mime = $size['mime'];
		switch ($src_type) {
			case 1:
				$img_type = 'gif';
				break;
			case 2:
				$img_type = 'jpeg';
				break;
			case 3:
				$img_type = 'png';
				break;
			case 15:
				$img_type = 'wbmp';
				break;
			default:
				return false;
		}
		if (!isset($width)) {
			$width = $src_w * ($height / $src_h);
		}
		if (!isset($height)) {
			$height = $src_h * ($width / $src_w);
		}
		$imagecreatefunc = 'imagecreatefrom' . $img_type;
		$src_img = $imagecreatefunc($src);
		$dest_img = imagecreatetruecolor($width, $height);
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);
		$imagefunc = 'image' . $img_type;
		if ($filename) {
			$imagefunc($dest_img, $filename);
		} else {
			header('Content-Type: ' . $src_mime);
			$imagefunc($dest_img);
		}
		imagedestroy($src_img);
		imagedestroy($dest_img);
		return true;
	}
}