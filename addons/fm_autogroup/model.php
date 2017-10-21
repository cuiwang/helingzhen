<?php
function fm_autogroup_article_search($cid, $type = '', $psize = 20, $orderby = 'displayorder DESC, id DESC') {
	global $_GPC, $_W;
	$pindex = max(1, intval($_GPC['page']));
	$result = array();
	$condition = " WHERE weid = '{$_W['weid']}' AND ";
	if(!empty($cid)) {
		$group = pdo_fetch("SELECT parentid FROM ".tablename('fm_autogroup_grouplist')." WHERE id = '{$cid}'");
		if (!empty($group['parentid'])) {
			$condition .= "ccate = '{$cid}'";
		} else {
			$condition .= "pcate = '{$cid}'";
		}
	}
	if(!empty($cid) && !empty($type)) $condition .= " OR ";
	if (!empty($type)) {
		if ($type == 'f') {
			return site_slide_search(array('limit' => 4));
		}
	}
	$sql = "SELECT * FROM ".tablename('fm_autogroup_article'). $condition. ' ORDER BY '. $orderby;
	$result['list'] = pdo_fetchall($sql . " LIMIT " . ($pindex - 1) * $psize .',' .$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_article') . $condition);
	$result['pager'] = pagination($total, $pindex, $psize);
	return $result;
}

function fm_autogroup_article($params = array()) {
	global $_GPC, $_W;
	extract($params);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$result = array();
	
	$condition = " WHERE weid = '{$_W['weid']}'";
	if (!empty($cid)) {
		$group = pdo_fetch("SELECT parentid FROM ".tablename('fm_autogroup_grouplist')." WHERE id = '{$cid}'");
		if (!empty($group['parentid'])) {
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
	$sql = "SELECT * FROM ".tablename('fm_autogroup_article'). $condition. ' ORDER BY displayorder DESC, id DESC';
	$result['list'] = pdo_fetchall($sql . " LIMIT " . ($pindex - 1) * $psize .',' .$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('fm_autogroup_article') . $condition);
	$result['pager'] = pagination($total, $pindex, $psize);
	if (!empty($result['list'])) {
		foreach ($result['list'] as &$row) {
			$row['url'] = create_url('mobile/module/detail', array('name' => 'site', 'id' => $row['id'], 'weid' => $_W['weid']));
		}
	}
	return $result;
}

function fm_autogroup_grouplist($params = array()) {
	global $_GPC, $_W;
	extract($params);
	
	if (!isset($parentid)) {
		$condition = "";
	} else {
		$condition = " AND parentid = '$parentid'"; 
	}
	
	$user = pdo_fetch("SELECT * FROM ".tablename('fm_autogroup_members')." WHERE weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}' ");
	$daihao = $user['gname'];
	$condition = " AND daihao = '$daihao'"; 
	$group = array();
	$result = pdo_fetchall("SELECT * FROM ".tablename('fm_autogroup_grouplist')." WHERE weid = '{$_W['weid']}' $condition ORDER BY parentid ASC, displayorder ASC, id ASC ");
	if (!isset($parentid)) {
		if (!empty($result)) {
			foreach ($result as $row) {
				if (empty($row['parentid'])) {
					$group[$row['id']] = $row;
				} else {
					$group[$row['parentid']]['children'][$row['id']] = $row;
				}
			}
		}
	} else {
		$group = $result;
	}
	return $group;
}