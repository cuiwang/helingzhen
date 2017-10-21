<?php


global $_W;
global $_GPC;
$operation = ((!empty($_GPC['op']) ? $_GPC['op'] : 'display'));

if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$lists = pdo_fetchall('select a.*,b.pid,b.maxage,b.minage,b.maxsalary,b.minsalary,b.minexper,b.maxexper from ' . tablename('enjoy_recuit_position') . ' as a left join ' . tablename('enjoy_recuit_position_range') . ' as b on a.id=b.pid WHERE a.uniacid = \'' . $_W['uniacid'] . '\' order by hot desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('enjoy_recuit_position') . ' WHERE uniacid = \'' . $_W['uniacid'] . '\'');
	$pager = pagination($total, $pindex, $psize);

	foreach ($lists as $k => $v ) {
		$list[] = $v;
		$list[$k]['realrem'] = pdo_fetchcolumn('select count(*) from ' . tablename('enjoy_recuit_deliver') . "\r\n" . '        where pid=' . $list[$k]['id'] . '');
	}
}
 else if ($operation == 'post') {
	load()->func('tpl');
	$id = intval($_GPC['id']);
	$item = pdo_fetch('select * from ' . tablename('enjoy_recuit_position') . ' as a left join ' . tablename('enjoy_recuit_position_range') . ' as b on a.id=b.pid WHERE a.id = ' . $id . ' and a.uniacid = \'' . $_W['uniacid'] . '\'');
	$mposition = pdo_fetch('select * from ' . tablename('enjoy_recuit_position') . ' as a left join ' . tablename('enjoy_recuit_position_range') . ' as b on a.id=b.pid WHERE a.id = ' . $id . ' and a.uniacid = \'' . $_W['uniacid'] . '\'');

	if (checksubmit('submit')) {
		$data = array('uniacid' => $_W['uniacid'], 'pname' => $_GPC['pname'], 'hot' => intval($_GPC['hot']), 'sex' => $_GPC['sex'], 'ed' => $_GPC['ed'], 'type' => $_GPC['type'], 'key' => $_GPC['key'], 'num' => intval($_GPC['num']), 'place' => $_GPC['place'], 'way' => $_GPC['way'], 'descript' => $_GPC['descript'], 'competence' => $_GPC['competence'], 'stime' => TIMESTAMP);

		if (!empty($id)) {
			pdo_update('enjoy_recuit_position', $data, array('id' => $id));
			$range_data = array('uniacid' => $_W['uniacid'], 'pid' => $id, 'maxage' => intval($_GPC['maxage']), 'minage' => intval($_GPC['minage']), 'maxsalary' => intval($_GPC['maxsalary']), 'minsalary' => intval($_GPC['minsalary']), 'maxexper' => intval($_GPC['maxexper']), 'minexper' => intval($_GPC['minexper']));
			pdo_update('enjoy_recuit_position_range', $range_data, array('id' => $id));
			$message = '更新职位成功！';
		}
		 else {
			pdo_insert('enjoy_recuit_position', $data);
			$id = pdo_insertid();
			$range_data = array('uniacid' => $_W['uniacid'], 'pid' => $id, 'maxage' => intval($_GPC['maxage']), 'minage' => intval($_GPC['minage']), 'maxsalary' => intval($_GPC['maxsalary']), 'minsalary' => intval($_GPC['minsalary']), 'maxexper' => intval($_GPC['maxexper']), 'minexper' => intval($_GPC['minexper']));
			pdo_insert('enjoy_recuit_position_range', $range_data);
			$message = '创建职位成功！';
		}

		message($message, $this->createWebUrl('Mposition', array('op' => 'display')), 'success');
	}

}
 else if ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$mposition = pdo_fetch('select id from ' . tablename('enjoy_recuit_position') . ' where id=' . $id . ' and uniacid=' . $_W['uniacid'] . '');

	if (empty($mposition)) {
		message('抱歉,职位不存在或是已经被删除！', $this->createWebUrl('Mposition', array('op' => 'display')), 'error');
	}
	 else {
		pdo_delete('enjoy_recuit_position', array('id' => $id));
		pdo_delete('enjoy_recuit_position_range', array('pid' => $id));
		message('职位删除成功！', $this->createWebUrl('Mposition', array('op' => 'display')), 'success');
	}
}


include $this->template('mposition', TEMPLATE_INCLUDEPATH, true);
