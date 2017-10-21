<?php
defined('IN_IA') or exit('Access Denied');
include_once IA_ROOT . '/addons/xc_article/define.php';
include_once INC_PHP . 'model.php';
//require IA_ROOT . '/framework/function/tpl.func.php';
//require_once(IA_ROOT . '/addons/quickcenter/loader.php');

class XC_ArticleModuleSite extends WeModuleSite
{
	public function doWebTest()
	{
		global $_GPC;
		preg_match('/attachment\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
		print_r($match);
		preg_match('/(http|https):\/\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
		print_r($match);
	}

	public function doWebCategory()
	{
		global $_W, $_GPC;
		load()->func('file');
		$mn = strtolower($this->module['name']);
		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		if ($foo == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('xc_article_article_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', 'refresh', 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename('xc_article_article_category') . " WHERE weid = '{$_W['weid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('category');
		} elseif ($foo == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			$mn = strtolower($this->module['name']);
			$template = $this->my_account_template($mn);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('xc_article_article_category') . " WHERE id = '$id'");
				if (!empty($category['nid'])) {
					$nav = pdo_fetch("SELECT * FROM " . tablename('site_nav') . " WHERE id = :id", array(':id' => $category['nid']));
					$nav['css'] = unserialize($nav['css']);
					if (strexists($nav['icon'], 'images/')) {
						$nav['fileicon'] = $nav['icon'];
						$nav['icon'] = '';
					}
				}
			} else {
				$category = array('displayorder' => 0,);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM " . tablename('xc_article_article_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('category', array('foo' => 'display')), 'error');
				}
			}
			if (checksubmit('fileupload-delete')) {
				file_delete($_GPC['fileupload-delete']);
				pdo_update('site_nav', array('icon' => ''), array('id' => $category['nid']));
				message('删除成功！', referer(), 'success');
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['cname'])) {
					message('抱歉，请输入分类名称！');
				}
				$pathinfo = pathinfo($_GPC['file']);
				list($gpc_template, $gpc_templatefile) = explode(':', $_GPC['template'], 2);
				$data = array('weid' => $_W['weid'], 'name' => $_GPC['cname'], 'displayorder' => intval($_GPC['displayorder']), 'parentid' => intval($parentid), 'description' => $_GPC['description'], 'thumb' => $_GPC['thumb'], 'template' => $gpc_template, 'templatefile' => $gpc_templatefile, 'linkurl' => $_GPC['linkurl'], 'ishomepage' => intval($_GPC['ishomepage']),);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('xc_article_article_category', $data, array('id' => $id));
				} else {
					pdo_insert('xc_article_article_category', $data);
					$id = pdo_insertid();
				}
				if (!empty($_GPC['isnav'])) {
					$nav = array('weid' => $_W['weid'], 'name' => $data['name'], 'displayorder' => 0, 'position' => 1, 'url' => $this->createMobileUrl('list', array('cid' => $id)), 'issystem' => 0, 'status' => 1,);
					$nav['css'] = serialize(array('icon' => array('font-size' => $_GPC['icon']['size'], 'color' => $_GPC['icon']['color'], 'width' => $_GPC['icon']['size'], 'icon' => $_GPC['icon']['icon'],),));
					if (!empty($_FILES['icon']['tmp_name'])) {
						file_delete($_GPC['icon_old']);
						$upload = file_upload($_FILES['icon']);
						if (is_error($upload)) {
							message($upload['message'], '', 'error');
						}
						$nav['icon'] = $upload['path'];
					}
					if (empty($category['nid'])) {
						pdo_insert('site_nav', $nav);
						pdo_update('xc_article_article_category', array('nid' => pdo_insertid()), array('id' => $id));
					} else {
						pdo_update('site_nav', $nav, array('id' => $category['nid']));
					}
				} else {
					pdo_delete('site_nav', array('id' => $category['nid']));
					pdo_update('xc_article_article_category', array('nid' => 0), array('id' => $id));
				}
				message('更新分类成功！', $this->createWebUrl('category'), 'success');
			}
			include $this->template('category');
		} elseif ($foo == 'fetch') {
			$category = pdo_fetchall("SELECT id, name FROM " . tablename('xc_article_article_category') . " WHERE parentid = '" . intval($_GPC['parentid']) . "' ORDER BY id ASC, displayorder ASC, id ASC ");
			message($category, '', 'ajax');
		} elseif ($foo == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid, nid FROM " . tablename('xc_article_article_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category'), 'error');
			}
			$navs = pdo_fetchall("SELECT icon, id FROM " . tablename('site_nav') . " WHERE id IN (SELECT nid FROM " . tablename('xc_article_article_category') . " WHERE id = {$id} OR parentid = '$id')", array(), 'id');
			if (!empty($navs)) {
				foreach ($navs as $row) {
					file_delete($row['icon']);
				}
				pdo_query("DELETE FROM " . tablename('site_nav') . " WHERE id IN (" . implode(',', array_keys($navs)) . ")");
			}
			pdo_delete('xc_article_article_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('category'), 'success');
		}
	}

	public function doWebArticle()
	{
		global $_W, $_GPC;
		load()->func('file');
		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		$category = pdo_fetchall("SELECT * FROM " . tablename('xc_article_article_category') . " WHERE weid = '{$_W['weid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');
		if ($foo == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			if (!empty($_GPC['cate_1'])) {
				$cid = intval($_GPC['cate_1']);
				$condition .= " AND pcate = '{$cid}'";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('xc_article_article') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_article_article') . " WHERE weid = '{$_W['weid']}' $condition", $params);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('article');
		} elseif ($foo == 'post') {
			$id = intval($_GPC['id']);
			$parent = array();
			$children = array();
			if (!empty($category)) {
				$children = '';
				foreach ($category as $cid => $cate) {
					if (!empty($cate['parentid'])) {
						$children[$cate['parentid']][] = $cate;
					} else {
						$parent[$cate['id']] = $cate;
					}
				}
			}
			$mn = strtolower($this->module['name']);
			$template = $this->my_account_template($mn);
			$adv_cache = pdo_fetch("SELECT * FROM " . tablename('xc_article_adv_cache') . " WHERE weid={$_W['weid']}");
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('xc_article_article') . " WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('抱歉，文章不存在或是已经删除！', '', 'error');
				}
				$item['type'] = explode(',', $item['type']);
				$pcate = $item['pcate'];
				$ccate = $item['ccate'];
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('标题不能为空，请输入标题!');
				}
				list($gpc_template, $gpc_templatefile) = explode(':', $_GPC['template'], 2);
				$rec_size = count($_GPC['rec-title']);
				$rec = array();
				for ($i = 0; $i < $rec_size; $i++) {
					if (!empty($_GPC['rec-title'])) {
						$rec[] = array('title' => $_GPC['rec-title'][$i], 'url' => $_GPC['rec-url'][$i]);
					}
				}
				$searilaized_recommendation = serialize($rec);
				$data = array('weid' => $_W['weid'], 'iscommend' => intval($_GPC['option']['commend']), 'ishot' => intval($_GPC['option']['hot']), 'pcate' => intval($_GPC['category']['parentid']), 'ccate' => intval($_GPC['category']['childid']), 'template' => $gpc_template, 'templatefile' => $gpc_templatefile, 'title' => $_GPC['title'], 'description' => $_GPC['description'], 'sharethumb' => $_GPC['sharethumb'], 'content' => htmlspecialchars_decode($_GPC['content']), 'source' => $_GPC['source'], 'author' => $_GPC['author'], 'recommendation' => $searilaized_recommendation, 'recommendation_source' => $_GPC['recommendation_source'], 'displayorder' => intval($_GPC['displayorder']), 'follow_url' => $_GPC['follow_url'], 'linkurl' => $_GPC['linkurl'], 'redirect_url' => $_GPC['redirect_url'], 'share_credit' => intval($_GPC['share_credit']), 'click_credit' => intval($_GPC['click_credit']), 'max_credit' => intval($_GPC['max_credit']), 'per_user_credit' => intval($_GPC['per_user_credit']), 'praise_count' => intval($_GPC['praise_count']), 'read_count' => intval($_GPC['read_count']), 'adv_on_off' => $_GPC['adv_on_off'], 'adv_top' => $_GPC['adv_top'], 'adv_status' => $_GPC['adv_status'], 'adv_bottom' => $_GPC['adv_bottom'], 'createtime' => TIMESTAMP,);
				if (!empty($_GPC['thumb'])) {
					$data['thumb'] = $_GPC['thumb'];
					file_delete($_GPC['thumb-old']);
				} elseif (!empty($_GPC['autolitpic'])) {
					$match = array();
					preg_match('/attachment\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
					if (!empty($match[1])) {
						$data['thumb'] = $match[1] . $match[2];
					} else {
						preg_match('/(http|https):\/\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
						$data['thumb'] = $match[0];
					}
				}
				if (empty($id)) {
					pdo_insert('xc_article_article', $data);
				} else {
					unset($data['createtime']);
					pdo_update('xc_article_article', $data, array('id' => $id));
				}
				$adv_data = array('weid' => $_W['weid'], 'adv_on_off' => $_GPC['adv_on_off'], 'adv_top' => $_GPC['adv_top'], 'adv_status' => $_GPC['adv_status'], 'adv_bottom' => $_GPC['adv_bottom']);
				if (empty($adv_cache)) {
					pdo_insert('xc_article_adv_cache', $adv_data);
				} else {
					pdo_update('xc_article_adv_cache', $adv_data, array('weid' => $_W['weid']));
				}
				message('文章更新成功！', $this->createWebUrl('article', array('foo' => 'display')), 'success');
			} else {
				$recommendation = unserialize($item['recommendation']);
				include $this->template('article');
			}
		} elseif ($foo == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id, thumb FROM " . tablename('xc_article_article') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，文章不存在或是已经被删除！');
			}
			if (!empty($row['thumb'])) {
				file_delete($row['thumb']);
			}
			pdo_delete('xc_article_article', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
	}

	public function doWebQuery()
	{
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename('xc_article_article') . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY id DESC LIMIT 0,8';
		$params = array();
		$params[':weid'] = $_W['weid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		foreach ($ds as &$row) {
			$r = array();
			$r['id'] = $row['id'];
			$r['title'] = $row['title'];
			$r['description'] = cutstr(strip_tags($row['content']), 100);
			$r['thumb'] = $row['thumb'];
			$row['entry'] = $r;
		}
		include $this->template('query');
	}

	public function doWebDelete()
	{
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		pdo_delete('xc_article_article_reply', array('id' => $id));
		message('删除成功！', referer(), 'success');
	}

	private function trackRead($detail)
	{
		pdo_update('xc_article_article', array('read_count' => $detail['read_count'] + 1), array('id' => $detail['id']));
	}

	private function trackAccess($detail)
	{
		global $_W, $_GPC;
		$credit_cost = 0;
		if (!isset($_GPC['shareby'])) {
			return;
		}
		$shareby = $_GPC['shareby'];
		$track_type = $_GPC['track_type'];
		$track_msg = $_GPC['track_msg'];
		$credit = 0;
		$clicker_id = $_W['fans']['from_user'];
		$fans = $this->fans_search($shareby);
		if (empty($fans)) {
			return -1;
		}
		if (true) {
			$cookie_name = "xc_article-1-" . $_W['weid'];
			if (isset($_COOKIE[$cookie_name])) {
				return 0;
			} else {
				setcookie($cookie_name, 'killed', TIMESTAMP + $this->module['config']['prohibit_site_click_interval']);
			}
		}
		if (true) {
			$cookie_name = "xc_article-1-" . $_W['weid'] . "-" . $shareby . "-" . $detail['id'];
			if (isset($_COOKIE[$cookie_name])) {
				return 0;
			} else {
				setcookie($cookie_name, 'killed', TIMESTAMP + $this->module['config']['prohibit_single_article_click_interval']);
			}
		}
		$click_history = pdo_fetch("SELECT * FROM  " . tablename('xc_article_share_track') . " WHERE weid=:weid AND shareby=:shareby AND detail_id=:detail_id AND track_type=:track_type AND clicker_id=:clicker_id", array(':weid' => $_W['weid'], ':shareby' => $shareby, ':detail_id' => $detail['id'], ':track_type' => 'click', ':clicker_id' => $clicker_id));
		if (!empty($click_history)) {
			return 0;
		}
		$per_user_credit = pdo_fetch("SELECT SUM(credit) as total_credit FROM " . tablename('xc_article_share_track') . " WHERE detail_id = :detail_id AND shareby=:shareby", array(':detail_id' => $detail['id'], ':shareby' => $shareby));
		if ($track_type == 'click' and $detail['click_credit'] > 0) {
			if ((0 >= $detail['max_credit']) or ($detail['per_user_credit'] > 0 and $per_user_credit['total_credit'] >= $detail['per_user_credit'])) {
				$credit = 0;
			} else {
				$credit = $detail['click_credit'];
				$credit_cost += $credit;
			}
			$this->addCredit($shareby, $credit);
			pdo_insert('xc_article_share_track', array('weid' => $_W['weid'], 'credit' => $credit, 'shareby' => $shareby, 'track_type' => $track_type, 'track_msg' => $track_msg, 'detail_id' => $detail['id'], 'title' => $detail['title'], 'access_time' => TIMESTAMP, 'ip' => getip(), 'clicker_id' => $clicker_id,));
		}
		if ($track_type == 'click' and $detail['share_credit'] > 0 and !empty($shareby)) {
			if ($credit >= $detail['max_credit']) {
				$credit = 0;
			} else {
				$credit = $detail['share_credit'];
				$credit_cost += $credit;
			}
			$share_credit_info = pdo_fetch("SELECT * FROM  " . tablename('xc_article_share_track') . " WHERE weid=:weid AND shareby=:shareby AND detail_id=:detail_id AND track_type=:track_type", array(':weid' => $_W['weid'], ':shareby' => $shareby, ':detail_id' => $detail['id'], ':track_type' => 'share'));
			if (false == $share_credit_info) {
				$this->addCredit($shareby, $credit);
				pdo_insert('xc_article_share_track', array('weid' => $_W['weid'], 'credit' => $credit, 'shareby' => $shareby, 'track_type' => 'share', 'track_msg' => $track_msg, 'detail_id' => $detail['id'], 'title' => $detail['title'], 'access_time' => TIMESTAMP, 'ip' => getip(), 'clicker_id' => $clicker_id,));
			}
		}
		if ($credit_cost > 0 and !empty($detail['id'])) {
			$sql = "UPDATE " . tablename('xc_article_article') . " SET max_credit = max_credit - " . $credit_cost . " WHERE id=:id AND weid=:weid";
			pdo_query($sql, array(":weid" => $_W['weid'], ":id" => $detail['id']));
		}
		WeUtility::logging('byebye ' . $shareby);
	}

	public function doWebShareTrack()
	{
		global $_W, $_GPC;
		$psize = 200;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($op == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			if (!empty($_GPC['shareby'])) {
				$condition .= " AND shareby = :shareby";
				$params[':shareby'] = "{$_GPC['shareby']}";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY access_time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition", $params);
			$users = array();
			foreach ($list as $i_item) {
				$users[] = $i_item['shareby'];
			}
			$fans = $this->fans_search($users);
			$pager = pagination($total, $pindex, $psize);
		} else if ($op == 'display_user_summary') {
			$pindex = max(1, intval($_GPC['page']));
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			$list = pdo_fetchall("SELECT SUM(credit)  as total_credit, shareby, count(credit) as total_click  FROM " . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition GROUP BY shareby ORDER BY access_time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM (SELECT COUNT(*) FROM ' . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition GROUP BY shareby) as tmp", $params);
			$users = array();
			foreach ($list as $item) {
				$users[] = $item['shareby'];
			}
			$fans = $this->fans_search($users);
			$pager = pagination($total, $pindex, $psize);
		} else if ($op == 'display_article_user_summary') {
			$pindex = max(1, intval($_GPC['page']));
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			$list = pdo_fetchall("SELECT SUM(credit) as total_credit, count(credit) as total_click, title, shareby, detail_id  FROM " . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition GROUP BY detail_id, shareby ORDER BY access_time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM (SELECT COUNT(*) FROM ' . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition GROUP BY detail_id, shareby) as tmp", $params);
			$users = array();
			foreach ($list as $item) {
				$users[] = $item['shareby'];
			}
			$fans = $this->fans_search($users);
			$pager = pagination($total, $pindex, $psize);
		} else if ($op == 'display_article_summary') {
			$pindex = max(1, intval($_GPC['page']));
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			$list = pdo_fetchall("SELECT SUM(credit) as total_credit, count(credit) as total_click, title, detail_id  FROM " . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition GROUP BY detail_id ORDER BY access_time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM (SELECT count(*)  FROM " . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition GROUP BY detail_id) as tmp", $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($op == 'delete') {
			$id = intval($_GPC['id']);
			if (empty($id)) {
				message("没有指定记录", "", "error");
			}
			pdo_delete("xc_article_share_track", array("id" => $id));
			message("删除成功", $this->createWebUrl("sharetrack"), "success");
		}
		include $this->template('sharetrack');
	}

	public function doWebHelp()
	{
		global $_W;
		include $this->template('help');
	}

	public function doMobileList()
	{
		global $_GPC, $_W;
		$this->tryLink();
		$cid = intval($_GPC['cid']);
		$category = pdo_fetch("SELECT * FROM " . tablename('xc_article_article_category') . " WHERE id = '{$cid}' ");
		if (empty($category)) {
			message('分类不存在或是已经被删除！');
		}
		if (!empty($category['linkurl'])) {
			header('Location: ' . $category['linkurl']);
			exit;
		}
		$title = $category['name'];
		$_share = array();
		$_share['title'] = $title;
		$_share['imgUrl'] = $_W['attachurl'] . $category['thumb'];
		$_share['desc'] = $category['description'];
		$_share['link'] = $_W['siteroot'] . '/app/' . $this->createMobileUrl('category', array('cid' => $category['id'])) . '&shareby=' . $_W['fans']['from_user'] . "&track_type=click&tpl={$_GPC['tpl']}&file={$_GPC['file']}";
		if (!empty($_GPC['tpl']) && !empty($_GPC['file'])) {
			$_W['account']['template'] = $_GPC['tpl'];
			include $this->template($_GPC['file']);
			exit;
		}
		if (!empty($category['template'])) {
			$_W['account']['template'] = $category['template'];
		}
		if (!empty($category['templatefile'])) {
			include $this->template($category['templatefile']);
			exit;
		}
		include $this->template('list');
	}

	public function doMobilePraise()
	{
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('xc_article_article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id' => $id));
		if (!empty($detail)) {
			if (true) {
				$cookie_name = "xc_article-2-" . $_W['weid'] . "-" . $detail['id'];
				if (isset($_COOKIE[$cookie_name])) {
					die(json_encode(array("result" => 1)));
				} else {
					setcookie($cookie_name, 'read', TIMESTAMP + 60 * 60 * 24 * 7);
				}
			}
			pdo_update('xc_article_article', array('praise_count' => $detail['praise_count'] + 1), array('id' => $id));
			die(json_encode(array("result" => 0)));
		}
		die(json_encode(array("result" => 2)));
	}

	public function doMobileDetail()
	{
		global $_GPC, $_W;
		$this->tryLink();
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('xc_article_article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id' => $id));
		$detail = istripslashes($detail);
		if (!empty($detail)) {
			$this->trackRead($detail);
		}
		if (!empty($detail['redirect_url'])) {
			header('Location: ' . $detail['redirect_url']);
			exit(0);
		}
		if ((!empty($_GPC['shareby'])) and ($_GPC['shareby'] != $_W['fans']['from_user'])) {
			$this->trackAccess($detail);
			if (!empty($_GPC['linkurl']) && strlen($_GPC['linkurl']) > 0) {
				header('Location:' . base64_decode($_GPC['linkurl']));
				exit;
			}
		} else {
		}
		$recommendation = unserialize($detail['recommendation']);
		$recommendation = xc_article_addTplTail($recommendation);
		$detail['thumb'] = trim((strpos($detail['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $detail['thumb'] : $detail['thumb']);
		$detail['title'] = $this->parseTemplate($detail, $detail['title']);
		$detail['source'] = $this->parseTemplate($detail, $detail['source']);
		$detail['author'] = $this->parseTemplate($detail, $detail['author']);
		if ($detail['adv_on_off'] == 'off') {
			$detail['adv_top'] = $detail['adv_status'] = $detail['adv_bottom'] = '';
		} else {
			$detail['adv_top'] = $this->parseTemplate($detail, $detail['adv_top']);
			$detail['adv_status'] = $this->parseTemplate($detail, $detail['adv_status']);
			$detail['adv_bottom'] = $this->parseTemplate($detail, $detail['adv_bottom']);
		}
		$title = $detail['title'];
		$_share = array();
		$_share['title'] = $title;
		$_share['imgUrl'] = $_W['attachurl'] . $detail['sharethumb'];
		$_share['desc'] = $detail['description'];
		$_share['link'] = $_W['siteroot'] . '/app/' . $this->createMobileUrl('detail', array('id' => $detail['id'])) . '&shareby=' . $_W['fans']['from_user'] . "&track_type=click&tpl={$_GPC['tpl']}&file={$_GPC['file']}" . (!empty($detail['linkurl']) ? '&linkurl=' . base64_encode($detail['linkurl']) : '');
		if (!empty($_GPC['tpl']) && !empty($_GPC['file'])) {
			$_W['account']['template'] = $_GPC['tpl'];
			include $this->template($_GPC['file']);
			exit;
		}
		if (!empty($detail['template'])) {
			$_W['account']['template'] = $detail['template'];
		}
		if (!empty($detail['templatefile'])) {
			include $this->template($detail['templatefile']);
			exit;
		}
		include $this->template('detail');
	}

	public function doMobileShareTrack()
	{
		global $_GPC;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('xc_article_article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id' => $id));
		$this->trackAccess($detail);
	}

	public function getCategoryTiles()
	{
		global $_W;
		$category = pdo_fetchall("SELECT id, name FROM " . tablename('xc_article_article_category') . " WHERE enabled = '1' AND weid = '{$_W['weid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ");
		if (!empty($category)) {
			foreach ($category as $row) {
				$urls[] = array('title' => $row['name'], 'url' => $this->createMobileUrl('list', array('cid' => $row['id'])));
			}
			return $urls;
		}
	}

	private function addCredit($from_user, $credit_value)
	{
		global $_GPC, $_W;
		load()->model('mc');
		$uid = mc_openid2uid($from_user);
		mc_credit_update($uid, 'credit1', $credit_value, array($uid, '文章分享'));
	}

	public function doWebAjaxSearch()
	{
		global $_GPC, $_W;
		$result = array();
		if (is_numeric($_GPC['search-keyword'])) {
			$id = intval($_GPC['search-keyword']);
			$cond = "`id` = {$id}";
		} else {
			$keyword = $_GPC['search-keyword'];
			$cond = "`title` LIKE '%{$keyword}%'";
		}
		$sql = "SELECT title, id FROM " . tablename('xc_article_article') . " WHERE weid={$_W['weid']} AND {$cond} LIMIT 1";
		$detail = pdo_fetch($sql, array(':id' => $id));
		if (false != $detail) {
			$result = array('err' => 0, 'title' => $detail['title'], 'url' => $this->createMobileUrl('detail', array('id' => $detail['id'])));
		} else {
			$result = array('err' => 1);
		}
		exit(json_encode($result));
	}

	private function parseTemplate($detail, $str)
	{
		$str = preg_replace('/share_credit/', $detail['share_credit'], $str);
		$str = preg_replace('/click_credit/', $detail['click_credit'], $str);
		$str = preg_replace('/max_credit/', $detail['max_credit'], $str);
		$str = preg_replace('/read_count/', $detail['read_count'], $str);
		$str = preg_replace('/source/', $detail['source'], $str);
		$str = preg_replace('/author/', $detail['author'], $str);
		$str = preg_replace('/title/', $detail['title'], $str);
		$str = preg_replace('/createtime/', date('Y-m-d', $detail['createtime']), $str);
		return $str;
	}

	public function doMobileGetContent()
	{
		global $_W, $_GPC;
		$url = urldecode($_GPC['url']);
		if (!empty($url)) {
			$data = file_get_contents($url);
			include $this->template('content');
			exit(0);
		}
		include $this->template('userform');
	}

	private function fans_search($openid)
	{
		global $_W;
		$result = array();
		$openid_str = implode("','", is_array($openid) ? $openid : array($openid));
		$query = 'SELECT a.openid from_user, b.* FROM ' . tablename('mc_mapping_fans') . ' a LEFT JOIN ' . tablename('mc_members') . ' b ' . ' ON a.uid = b.uid WHERE a.openid IN (\'' . $openid_str . '\') AND a.uniacid=' . $_W['uniacid'];
		if (is_string($openid)) {
			$result = pdo_fetch($query, null, 'from_user');
		} else if (is_array($openid)) {
			$result = pdo_fetchall($query, null, 'from_user');
		}
		return $result;
	}

	protected function my_account_template($mn)
	{
		$templates = array();
		$path = THEME_DIR;
		if (is_dir($path)) {
			if ($handle = opendir($path)) {
				while (false !== ($modulepath = readdir($handle))) {
					$manifest = $this->my_ext_template_manifest($mn, $modulepath);
					if (!empty($manifest)) {
						$templates[] = $manifest;
					}
				}
			}
		}
		return $templates;
	}

	private function my_ext_template_manifest($mn, $tpl)
	{
		$manifest = array();
		$filename = THEME_DIR . $tpl . '/manifest.xml';
		if (!file_exists($filename)) {
			return array();
		}
		$xml = str_replace(array('&'), array('&amp;'), file_get_contents($filename));
		$xml = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		if (empty($xml)) {
			return array();
		}
		$manifest['name'] = strval($xml->identifie);
		if (empty($manifest['name']) || $manifest['name'] != $tpl) {
			return array();
		}
		$manifest['title'] = strval($xml->title);
		if (empty($manifest['title'])) {
			return array();
		}
		$manifest['description'] = strval($xml->description);
		$manifest['author'] = strval($xml->author);
		$manifest['url'] = strval($xml->url);
		if ($xml->settings->item) {
			foreach ($xml->settings->item as $msg) {
				$attrs = $msg->attributes();
				$manifest['settings'][trim(strval($attrs['variable']))] = trim(strval($attrs['content']));
			}
		}
		$manifest['category'] = array();
		if ($xml->category->item) {
			foreach ($xml->category->item as $item) {
				$manifest['category'][] = $item;
			}
		}
		$manifest['article'] = array();
		if ($xml->article->item) {
			foreach ($xml->article->item as $item) {
				$manifest['article'][] = $item;
			}
		}
		return $manifest;
	}

	protected function resource($filename, $subdir)
	{
		global $_W;
		$mn = strtolower($this->modulename);
		if ($this->inMobile) {
			$source = INC_PHP . "template/mobile/themes/{$_W['account']['template']}/{$subdir}/{$filename}";
			if (!is_file($source)) {
				$source = INC_PHP . "template/mobile/{$subdir}/{$filename}";
			}
		} else {
			$source = INC_PHP . "template/web/themes/{$_W['account']['template']}/{$subdir}/{$filename}";
			if (!is_file($source)) {
				$source = INC_PHP . "template/{$subdir}/{$filename}";
			}
		}
		if (!is_file($source)) {
			exit("Error: template source {$source} '{$subdir}' '{$filename}' is not exist!");
		}
		return $source;
	}

	protected function template($filename)
	{
		global $_W;
		$name = strtolower($this->modulename);
		$defineDir = dirname($this->__define);
		if (defined('IN_SYS')) {
			return parent::template($filename);
		} else {
			$source = THEME_DIR . "{$_W['account']['template']}/{$filename}.html";
			$compile = THEME_COMPILE_DIR . "{$name}/{$_W['account']['template']}/{$filename}.tpl.php";
			if (!is_file($source)) {
				$source = DEFAULT_THEME_DIR . "{$filename}.html";
			}
			if (!is_file($source)) {
				if (in_array($filename, array('header', 'footer', 'slide', 'toolbar', 'message'))) {
					$source = IA_ROOT . "/app/themes/default/common/{$filename}.html";
				} else {
					$source = IA_ROOT . "/app/themes/default/{$filename}.html";
				}
			}
		}
		if (!is_file($source)) {
			exit("Error: template source {$source}  - filename  '{$filename}' - name {$name} is not exist!");
		}
		if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
			template_compile($source, $compile, true);
		}
		return $compile;
	}

	protected function tpl_res($filename, $subdir)
	{
		global $_W;
		$mn = strtolower($this->modulename);
		$path = $_W['siteroot'] . "/addons/quicktemplate/xc_article/{$_W['account']['template']}/{$subdir}/{$filename}";
		return $path;
	}

	protected function manifest_resource($manifest, $filename)
	{
		global $_W;
		$mn = strtolower($this->module['name']);
		if (strpos($filename, 'http://') === FALSE) {
			$source = $_W['siteroot'] . "/addons/quicktemplate/xc_article/{$manifest['name']}/{$filename}";
		} else {
			$source = $filename;
		}
		return $source;
	}

	private function tryLink()
	{
		global $_GPC, $_W;
		if ($this->module['config']['enable_link'] == 1) {
			yload()->classs('quicklink', 'translink');
			$_link = new TransLink();
			if ($_GPC['shareby'] != $_W['fans']['from_user']) {
				$_link->instantLink($_W['weid'], $_W['fans'], $_GPC['shareby']);
			}
		}
	}
}