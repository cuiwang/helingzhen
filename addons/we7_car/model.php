<?php
function news_category($params = array()) {
	global $_GPC, $_W;
	extract($params);
	
	$category = array();
	$result = pdo_fetchall("SELECT * FROM ".tablename('we7car_news_category')." WHERE weid = '{$_W['weid']}' AND status = 1 ORDER BY displayorder DESC ");
	if (!empty($result)) {
		foreach ($result as $row) {
			if (empty($row['parentid'])) {
				$category[$row['id']] = $row;
			} else {
				$category[$row['parentid']]['children'][$row['id']] = $row;
			}
		}
	}
	return $category;
}