<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan isNOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->func('tpl');
$creditnames = uni_setting($_W['uniacid'], array('creditnames'));
$creditnames = $creditnames['creditnames'];
$credits = array_keys($creditnames);
if($creditnames) {
	foreach($creditnames as $index => $creditname) {
		if(empty($creditname['enabled'])) {
			unset($creditnames[$index]);
		}
	}
	$select_credit = implode(', ', array_keys($creditnames));
} else {
	$select_credit = '';
}
$type = trim($_GPC['type']) ? trim($_GPC['type']) : $credits[0];
$uniacid = $_W['uniacid'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$starttime = strtotime('-7 day');
	$endtime = strtotime('7 day');
		$starttime = strtotime($_GPC['datelimit']['start']);
		$endtime = strtotime($_GPC['datelimit']['end']) + 86399;
		$where1 = 'WHERE uniacid = :uniacid  AND createtime > :start AND createtime < :end  AND num > 0';
		$where2 = 'WHERE r.uniacid = :uniacid  AND r.createtime > :start AND r.createtime < :end  AND num > 0';
		$params = array(':uniacid' => $_W['uniacid'], ':start' => $starttime, ':end' => $endtime);
		
	if($type == 1) {
		$where1 = 'WHERE uniacid = :uniacid';
		$where2 = 'WHERE r.uniacid = :uniacid';
		$params = array(':uniacid' => $_W['uniacid']);

	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('mc_credits_record') . $where1, $params);
	$list = pdo_fetchall("SELECT r.*, u.nickname FROM " . tablename('mc_credits_record') . ' AS r LEFT JOIN ' .tablename('mc_members') . ' AS u ON r.uid = u.uid ' . $where2 . ' ORDER BY id DESC LIMIT ' . ($pindex - 1) * $psize .',' . $psize, $params);
	$pager = pagination($total, $pindex, $psize);
	$list2 = pdo_fetchall("SELECT r.*, u.nickname FROM " . tablename('mc_credits_record') . ' AS r LEFT JOIN ' .tablename('mc_members') . ' AS u ON r.uid = u.uid ' . $where2, $params);
	
if (!empty($_GPC['export']))  {
				$html = "\xEF\xBB\xBF";
				/* 输出表头 */
				$filter = array(
					'id' => 'ID',
					'uid' => '会员编号',
					'nickname' => '会员名',
					'credittype' => '积分类型',
					'num' => '积分数量',
					'remark' => '备注',
				);
				foreach ($filter as $key => $value) {
					$html .= $value . "\t,";
				}
				$html .= "创建时间\t ,\n";
				$html .= "\n";

				if (!empty($list2)) {
					//$status = array('隐藏', '显示');
					foreach ($list2 as $key => $value) {
						foreach ($filter as $index => $title) {
							//if ($index != 'status') {
								$html .= $value[$index] . "\t, ";
							//} else {
							//	$html .= $status[$value[$index]] . "\t, ";
							//}
						}
						$html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
						$html .= "\n";
					}
				}

				/* 输出CSV文件 */
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=全部数据.csv");
				echo $html;
				exit();
}
	
	template('mc/credit_record_all');
	exit;
