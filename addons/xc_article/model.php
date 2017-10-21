<?php
function xc_article_addTplTail($list)
{
	global $_GPC;
	foreach ($list as &$item) {
		if (FALSE === strpos($item['url'], 'http://')) {
			if (!empty($_GPC['tpl']) && !empty($_GPC['file']) && !empty($item['url'])) {
				$item['url'] .= "&tpl={$_GPC['tpl']}&file={$_GPC['file']}";
			}
		}
	}
	return $list;
}

function xc_article_site_article_search($cid, $type = '', $psize = 20, $orderby = 'displayorder DESC, id DESC')
{
	global $_GPC, $_W;
	$pindex = max(1, intval($_GPC['page']));
	$result = array();
	$condition = " WHERE weid = '{$_W['weid']}' AND ";
	if (!empty($cid)) {
		$category = pdo_fetch("SELECT parentid FROM " . tablename('xc_article_article_category') . " WHERE id = '{$cid}'");
		if (!empty($category['parentid'])) {
			$condition .= "ccate = '{$cid}'";
		} else {
			$condition .= "pcate = '{$cid}'";
		}
	}
	if (!empty($cid) && !empty($type)) $condition .= " OR ";
	if (!empty($type)) {
		if ($type == 'f') {
			return site_slide_search(array('limit' => 4));
		}
	}
	$sql = "SELECT * FROM " . tablename('xc_article_article') . $condition . ' ORDER BY ' . $orderby;
	$result['list'] = pdo_fetchall($sql . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_article_article') . $condition);
	$result['pager'] = pagination($total, $pindex, $psize);
	if (!empty($result['list'])) {
		foreach ($result['list'] as &$row) {
			$row['url'] = murl('entry/module/detail', array('m' => 'xc_article', 'id' => $row['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
			$row['thumb'] = (strpos($row['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $row['thumb'] : $row['thumb'];
		}
	}
	$result['list'] = xc_article_addTplTail($result['list']);
	return $result;
}

function xc_article_site_article_hot($params = array())
{
	global $_GPC, $_W;
	$result = array();
	$condition = " WHERE weid = '{$_W['weid']}'";
	$sql = "SELECT * FROM " . tablename('xc_article_article') . $condition . ' ORDER BY read_count DESC';
	$result = pdo_fetchall($sql . " LIMIT 10");
	if (!empty($result)) {
		foreach ($result as &$row) {
			$row['url'] = murl('entry/module/detail', array('m' => 'xc_article', 'id' => $row['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
			$row['thumb'] = (strpos($row['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $row['thumb'] : $row['thumb'];
		}
	}
	$result['list'] = xc_article_addTplTail($result['list']);
	return $result;
}

function xc_article_site_article_random($params = array())
{
	global $_GPC, $_W;
	$result = array();
	$condition = " WHERE weid = '{$_W['weid']}'";
	$sql = "SELECT * FROM " . tablename('xc_article_article') . $condition . ' ORDER BY displayorder DESC, id DESC';
	$result['list'] = pdo_fetchall($sql . " LIMIT 10");
	if (!empty($result['list'])) {
		foreach ($result['list'] as &$row) {
			$row['url'] = murl('entry/module/detail', array('m' => 'xc_article', 'id' => $row['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
			$row['thumb'] = (strpos($row['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $row['thumb'] : $row['thumb'];
		}
	}
	$result['list'] = xc_article_addTplTail($result['list']);
	return $result;
}

function xc_article_site_article($params = array())
{
	global $_GPC, $_W;
	extract($params);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$result = array();
	$condition = " WHERE weid = '{$_W['weid']}'";
	if (!empty($cid)) {
		$category = pdo_fetch("SELECT parentid FROM " . tablename('xc_article_article_category') . " WHERE id = '{$cid}'");
		if (!empty($category['parentid'])) {
			$condition .= " AND ccate = '{$cid}'";
		} else {
			$condition .= " AND pcate = '{$cid}'";
		}
	}
	if ($iscommend == 'true') {
		$condition .= " AND iscommend = '1'";
	}
	if ($ishot == 'true') {
		$condition .= " AND ishot = '1'";
	}
	$sql = "SELECT * FROM " . tablename('xc_article_article') . $condition . ' ORDER BY displayorder DESC, id DESC';
	$result['list'] = pdo_fetchall($sql . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_article_article') . $condition);
	$result['pager'] = pagination($total, $pindex, $psize);
	$result['nextindex'] = $pindex + 1;
	$result['maxindex'] = intval(($total - 1) / $psize) + 1;
	if (!empty($result['list'])) {
		foreach ($result['list'] as &$row) {
			$row['url'] = murl('entry//detail', array('m' => 'xc_article', 'id' => $row['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
			$row['thumb'] = (strpos($row['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $row['thumb'] : $row['thumb'];
		}
	}
	$result['list'] = xc_article_addTplTail($result['list']);
	return $result;
}

function xc_article_site_get_next($params = array())
{
	global $_GPC, $_W;
	extract($params);
	$condition = "";
	if (isset($pcate)) {
		$condition .= " AND pcate = $pcate";
	}
	if (isset($id)) {
		$condition .= " AND id > $id ";
	}
	$result = pdo_fetch("SELECT * FROM " . tablename('xc_article_article') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY id ASC LIMIT 1");
	if (!empty($result)) {
		$result['url'] = murl('entry/module/detail', array('m' => 'xc_article', 'id' => $result['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
		$result['thumb'] = (strpos($result['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $result['thumb'] : $result['thumb'];
	}
	return $result;
}

function xc_article_site_get_prev($params = array())
{
	global $_GPC, $_W;
	extract($params);
	$condition = "";
	if (isset($pcate)) {
		$condition .= " AND pcate = $pcate";
	}
	if (isset($id)) {
		$condition .= " AND id < $id ";
	}
	$result = pdo_fetch("SELECT * FROM " . tablename('xc_article_article') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY id DESC LIMIT 1");
	if (!empty($result)) {
		$result['url'] = murl('entry/module/detail', array('m' => 'xc_article', 'id' => $result['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
		$result['thumb'] = (strpos($result['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $result['thumb'] : $result['thumb'];
	}
	return $result;
}

function xc_article_site_get_last($params = array())
{
	global $_GPC, $_W;
	extract($params);
	$condition = "";
	if (isset($pcate)) {
		$condition .= " AND pcate = $pcate";
	}
	$result = pdo_fetch("SELECT * FROM " . tablename('xc_article_article') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY id DESC LIMIT 1");
	if (!empty($result)) {
		$result['url'] = murl('entry/module/detail', array('m' => 'xc_article', 'id' => $result['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
		$result['thumb'] = (strpos($result['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $result['thumb'] : $result['thumb'];
	}
	return $result;
}

function xc_article_site_get_first($params = array())
{
	global $_GPC, $_W;
	extract($params);
	$condition = "";
	if (isset($pcate)) {
		$condition .= " AND pcate = $pcate";
	}
	$result = pdo_fetch("SELECT * FROM " . tablename('xc_article_article') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY id ASC LIMIT 1");
	if (!empty($result)) {
		$result['url'] = murl('entry/module/detail', array('m' => 'xc_article', 'id' => $result['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
		$result['thumb'] = (strpos($result['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $result['thumb'] : $result['thumb'];
	}
	return $result;
}

function xc_article_site_category($params = array())
{
	global $_GPC, $_W;
	extract($params);
	if (!isset($parentid)) {
		$condition = "";
	} else {
		$condition = " AND parentid = '$parentid'";
	}
	$category = array();
	$result = pdo_fetchall("SELECT * FROM " . tablename('xc_article_article_category') . " WHERE weid = '{$_W['weid']}' $condition ORDER BY parentid ASC, displayorder ASC, id ASC ");
	if (!isset($parentid)) {
		if (!empty($result)) {
			foreach ($result as $row) {
				$row['thumb'] = (strpos($row['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $row['thumb'] : $row['thumb'];
				if (empty($row['parentid'])) {
					$category[$row['id']] = $row;
				} else {
					$category[$row['parentid']]['children'][$row['id']] = $row;
				}
			}
		}
	} else {
		$category = $result;
	}
	return $category;
}

function xc_article_site_article_recommend($cid, $article_ids = null, $psize = 20, $orderby = 'displayorder DESC, id DESC')
{
	global $_GPC, $_W;
	$pindex = max(1, intval($_GPC['page']));
	$result = array();
	$condition = " WHERE weid = '{$_W['weid']}' AND ";
	if (!empty($cid)) {
		$category = pdo_fetch("SELECT parentid FROM " . tablename('xc_article_article_category') . " WHERE id = '{$cid}'");
		if (!empty($category['parentid'])) {
			$condition .= "ccate = '{$cid}'";
		} else {
			$condition .= "pcate = '{$cid}'";
		}
	}
	$sql = "SELECT * FROM " . tablename('xc_article_article') . $condition . ' ORDER BY ' . $orderby;
	$result['list'] = pdo_fetchall($sql . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_article_article') . $condition);
	$result['pager'] = pagination($total, $pindex, $psize);
	if (!empty($result['list'])) {
		foreach ($result['list'] as &$row) {
			$row['url'] = murl('entry/module/detail', array('m' => 'xc_article', 'id' => $row['id'], 'weid' => $_W['weid'], 'shareby' => $_W['fans']['from_user'], 'track_type' => 'click'));
			$row['thumb'] = (strpos($row['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $row['thumb'] : $row['thumb'];
		}
	}
	return $result;
}

function xc_article_site_slide_search($params = array())
{
	global $_GPC, $_W;
	extract($params);
	$sql = "SELECT * FROM " . tablename('site_slide') . " WHERE weid = '{$_W['weid']}' ORDER BY displayorder DESC, id DESC LIMIT $limit";
	$list = pdo_fetchall($sql);
	if (!empty($list)) {
		foreach ($list as &$row) {
			$row['url'] = strexists($row['url'], 'http') ? $row['url'] : $_W['siteroot'] . $row['url'];
			$row['thumb'] = toimage($row['thumb']);
		}
	}
	return $list;
}