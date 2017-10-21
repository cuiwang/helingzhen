<?php
/**
 * 女神来了导出
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
$uniacid = $_W['uniacid'];
$indexpx = intval($_GPC['indexpx']);
$indexpxf = intval($_GPC['indexpxf']);
$tagid = $_GPC['tagid'];
$tagpid = $_GPC['tagpid'];


if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');
}

if ($_GPC['uni_all_users'] != 1) {
	if ($uniacid != $_GPC['uniacid']) {
		$uni = " AND uniacid = ".$uniacid;
	}
}
if (!empty($tagid)) {
	$where .= " AND tagid = '".$tagid."'";
}elseif (!empty($tagpid)) {
	$where .= " AND tagpid = '".$tagpid."'";
}
$where = '';
$order = '';
$where .= ' rid= '.$rid;
$starttime = $_GPC['start_time'];
$endtime = $_GPC['end_time'];
if (!empty($starttime) && !empty($endtime)) {
	$where .= " AND createtime >= " . $starttime;
	$where .= " AND createtime < " . $endtime;
}

//0 按最新排序 1 按人气排序 3 按投票数排序
if ($indexpx == '-1') {
	$order .= " `createtime` DESC ";
}elseif ($indexpx == '1') {
	$order .= " `hits` + `xnhits` DESC ";
}elseif ($indexpx == '2') {
	$order .= " `photosnum` + `xnphotosnum` DESC ";
}

//0 按最新排序 1 按人气排序 3 按投票数排序  倒叙
if ($indexpxf == '-1') {
	$order .= " `createtime` ASC ";
}elseif ($indexpxf == '1') {
	$order .= " `hits` + `xnhits` ASC ";
}elseif ($indexpxf == '2') {
	$order .= " `photosnum` + `xnphotosnum` ASC ";
}
if (empty($indexpx) && empty($indexpxf)) {
	$order .= " `photosnum` + `xnphotosnum` DESC ";
}
$list = pdo_fetchall('SELECT * FROM '.tablename($this->table_users).' WHERE '.$where.$uni.' ORDER BY '.$order.' ');
$count = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_users).' WHERE '.$where.$uni.' ORDER BY '.$order.' ');
$pagesize = ceil($count/5000);
$header = array('uid' => '用户ID','paiming' => '排名', 'username' => '姓名', 'tags' => '分组', 'mobile' => '手机号', 'photoname' => '宣言', 'photosnum' => '真实票数', 'xnphotosnum' => '虚拟票数', 'hits' => '真实人气', 'xnhits' => '虚拟人气', 'sharenum' => '分享数', 'zans' => '点赞', 'comments' => '评论', 'hylevel' => '活跃等级', 'createtime' => '报名时间');

$keys = array_keys($header);
		$html = "\xEF\xBB\xBF";
		foreach ($header as $li) {
			$html .= $li . "\t ,";
		}
		$html .= "\n";
for ($j = 1; $j <= $pagesize; $j++) {
	$sql = "SELECT * FROM ".tablename($this->table_users)." WHERE ".$where.$uni." ORDER BY " . $order . " LIMIT " .  ($j - 1) * 5000 . ",5000 ";
	$list = pdo_fetchall($sql);
	if (!empty($list)) {
		$size = ceil(count($list) / 500);
		for ($i = 0; $i < $size; $i++) {
					$buffer = array_slice($list, $i * 500, 500);
					$user = array();
					foreach ($buffer as $mid => $row) {
						$row['comments'] = $this->getcommentnum($rid, $uniacid, $row['from_user']);
						$row['hylevel'] = intval($this->fmvipleavel($rid, $uniacid, $row['from_user']));
						$row['tags'] = $this->gettagname($row['tagid'],$row['tagpid'],$row['tagtid'],$rid);

						$row['paiming'] = $mid + 1;
						$row['username'] = $this->getname($rid,$row['from_user']);
						$row['sharenum'] = pdo_fetchcolumn("SELECT COUNT(1) FROM ".tablename($this->table_data)." WHERE tfrom_user = :tfrom_user and rid = :rid", array(':tfrom_user' => $row['from_user'],':rid' => $rid));


						$row['paiming'] = $mid + 1;

						$row['createtime'] = date('Y年m月d日 H:i:s',$row['createtime']);

						foreach ($keys as $key) {
							$data[] = $row[$key];
						}
						$user[] = implode("\t ,", $data) . "\t ,";
						unset($data);
					}
					$html .= implode("\n", $user) . "\n";
				}
			}
		}

$filename = $_GPC['title'].'_排行榜_'.$rid.'_'.$now;

header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=".$filename.".csv");

echo $html;
exit();
