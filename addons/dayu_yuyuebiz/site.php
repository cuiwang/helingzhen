<?php

//decode by QQ:8225001 http://www.guifox.com/
include 'plugin/sms.php';
define('TEMPLATE_PATH', '../addons/dayu_yuyuebiz/template/style/');
defined('IN_IA') or die('Access Denied');
class dayu_yuyuebizModuleSite extends WeModuleSite
{
	private static $COOKIE_DAYS = 7;
	public function getMenus()
	{
		$menus = array(array('title' => '门店与预约', 'url' => $this->createWebUrl('display', array('op' => 'simple')), 'icon' => 'fa fa-clock-o'), array('title' => '新建门店预约', 'url' => $this->createWebUrl('post'), 'icon' => 'fa fa-plus-square'), array('title' => '服务项目分类', 'url' => $this->createWebUrl('category'), 'icon' => 'fa fa-list-ol'), array('title' => '服务项目', 'url' => $this->createWebUrl('item'), 'icon' => 'fa fa-briefcase'), array('title' => '服务项目幻灯片', 'url' => $this->createWebUrl('slide'), 'icon' => 'fa fa-photo'), array('title' => '门店列表幻灯片', 'url' => $this->createWebUrl('slides'), 'icon' => 'fa fa-building-o'));
		return $menus;
	}
	private $category_table = 'dayu_yuyuebiz_category';
	public function getHomeTiles()
	{
		global $_W;
		$urls = array();
		$list = pdo_fetchall("SELECT title, reid FROM " . tablename('dayu_yuyuebiz') . " WHERE weid = '{$_W['uniacid']}'");
		if (!empty($list)) {
			foreach ($list as $row) {
				$urls[] = array('title' => $row['title'], 'url' => $_W['siteroot'] . "app/" . $this->createMobileUrl('dayu_yuyuebiz', array('id' => $row['reid'])));
			}
		}
		return $urls;
	}
	public function doWebQuery()
	{
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY reid DESC LIMIT 0,8';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		foreach ($ds as &$row) {
			$r = array();
			$r['title'] = $row['title'];
			$r['description'] = cutstr(strip_tags($row['description']), 50);
			$r['thumb'] = $row['thumb'];
			$r['reid'] = $row['reid'];
			$row['entry'] = $r;
		}
		include $this->template('query');
	}
	public function doWebDetail()
	{
		global $_W, $_GPC;
		$rerid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_info') . " WHERE `rerid`=:rerid";
		$params = array();
		$params[':rerid'] = $rerid;
		$row = pdo_fetch($sql, $params);
		if (empty($row)) {
			message('访问非法.');
		}
		$hexiao = "dayu_yuyuebiz_shareQrcode" . $_W['uniacid'];
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $row['reid'];
		$activity = pdo_fetch($sql, $params);
		$yxiangmu = $this->get_xiangmu($_W['uniacid'], $row['xmid']);
		if (empty($activity)) {
			message('非法访问.');
		}
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
		$params = array();
		$params[':reid'] = $row['reid'];
		$fields = pdo_fetchall($sql, $params);
		if (empty($fields)) {
			message('非法访问.');
		}
		$ds = $fids = array();
		foreach ($fields as $f) {
			$ds[$f['refid']]['fid'] = $f['title'];
			$ds[$f['refid']]['type'] = $f['type'];
			$ds[$f['refid']]['refid'] = $f['refid'];
			$fids[] = $f['refid'];
		}
		$record = array();
		$record['status'] = intval($_GPC['status']);
		if (!empty($_GPC['paystatus'])) {
			$record['paystatus'] = intval($_GPC['paystatus']);
		}
		$record['yuyuetime'] = TIMESTAMP;
		$record['kfinfo'] = $_GPC['kfinfo'];
		if ($_GPC['status'] == '0') {
			$huifu = '等待客服确认：' . $_GPC['kfinfo'];
		} elseif ($_GPC['status'] == '1') {
			$huifu = '客服已确认：' . $_GPC['kfinfo'];
		} elseif ($_GPC['status'] == '2') {
			$huifu = '客服已拒绝：' . $_GPC['kfinfo'];
		} elseif ($_GPC['status'] == '3') {
			$huifu = '服务完成';
		}
		$yxiangmu = $this->get_xiangmu($_W['uniacid'], $row['xmid']);
		$template = array("touser" => $row['openid'], "template_id" => $activity['m_templateid'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_yuyuebiz', array('weid' => $row['weid'], 'id' => $row['reid'])), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($activity['mfirst'] . "\\n "), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($row['member']), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($yxiangmu['title'] . " - " . $yxiangmu['price'] . "元"), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($row['restime']), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($huifu . "\\n "), 'color' => "#FF0000"), 'remark' => array('value' => urlencode($activity['mfoot']), 'color' => "#008000")));
		if ($_W['ispost']) {
			load()->func('communication');
			$this->send_template_message(urldecode(json_encode($template)));
			pdo_update('dayu_yuyuebiz_info', $record, array('rerid' => $rerid));
			message('修改成功', referer(), 'success');
		}
		$row['yuyuetime'] && ($row['yuyuetime'] = date('Y-m-d H:i:s', $row['yuyuetime']));
		$fids = implode(',', $fids);
		$row['fields'] = array();
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_data') . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
		$fdatas = pdo_fetchall($sql, $params);
		foreach ($fdatas as $fd) {
			$row['fields'][$fd['refid']] = $fd['data'];
		}
		foreach ($ds as $value) {
			if ($value['type'] == 'reside') {
				$row['fields'][$value['refid']] = '';
				foreach ($fdatas as $fdata) {
					if ($fdata['refid'] == $value['refid']) {
						$row['fields'][$value['refid']] .= $fdata['data'];
					}
				}
				break;
			}
		}
		load()->func('tpl');
		include $this->template('detail');
	}
	public function doWebManage()
	{
		global $_W, $_GPC;
		$_accounts = $accounts = uni_accounts();
		load()->model('mc');
		if (empty($accounts) || !is_array($accounts) || count($accounts) == 0) {
			message('请指定公众号');
		}
		if (!isset($_GPC['acid'])) {
			$account = array_shift($_accounts);
			if ($account !== false) {
				$acid = intval($account['acid']);
			}
		} else {
			$acid = intval($_GPC['acid']);
			if (!empty($acid) && !empty($accounts[$acid])) {
				$account = $accounts[$acid];
			}
		}
		reset($accounts);
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'super';
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		if (empty($activity)) {
			message('非法访问.');
		}
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
		$params = array();
		$params[':reid'] = $reid;
		$fields = pdo_fetchall($sql, $params);
		if (empty($fields)) {
			message('非法访问.');
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$status = $_GPC['status'];
		$paystatus = $_GPC['paystatus'];
		$starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
		$where .= 'reid=:reid';
		$params = array();
		$params[':reid'] = $reid;
		if (!empty($_GPC['time'])) {
			$where .= " AND createtime >= :starttime AND createtime <= :endtime ";
			$params[':starttime'] = $starttime;
			$params[':endtime'] = $endtime;
		}
		if ($activity['is_time'] == 2 && !empty($_GPC['yytime'])) {
			$where .= ' AND restime like :yytime';
			$params[':yytime'] = "%{$_GPC['yytime']}%";
		} elseif ($activity['is_time'] == 0 && !empty($_GPC['yytime']['start'])) {
			$where .= ' AND `yuyuetime` > :stime AND `yuyuetime` < :etime';
			$params[':stime'] = $stime;
			$params[':etime'] = $etime;
		}
		if (!empty($_GPC['keywords'])) {
			$where .= ' and (member like :member or mobile like :mobile)';
			$params[':member'] = "%{$_GPC['keywords']}%";
			$params[':mobile'] = "%{$_GPC['keywords']}%";
		}
		if (!empty($_GPC['orderid'])) {
			$where .= ' and (ordersn like :ordersn or transid like :transid)';
			$params[':ordersn'] = "%{$_GPC['orderid']}%";
			$params[':transid'] = "%{$_GPC['orderid']}%";
		}
		if ($status != '') {
			if ($status == 2) {
				$allstatus .= " and ( status=2 or status=-1 )";
			} else {
				$allstatus .= " and status='{$status}'";
			}
		}
		if ($paystatus != '') {
			$allstatus .= " and paystatus='{$paystatus}'";
		}
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_info') . " WHERE {$where} {$allstatus} ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuebiz_info') . " WHERE {$where} {$allstatus}", $params);
		$pager = pagination($total, $pindex, $psize);
		$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'success', 'name' => '在线支付'), '2' => array('css' => 'info', 'name' => '余额/积分'), '3' => array('css' => 'warning', 'name' => '其他付款方式'), '4' => array('css' => 'info', 'name' => '无需支付'));
		foreach ($list as $index => $row) {
			$list[$index]['user'] = mc_fansinfo($row['openid'], $acid, $_W['uniacid']);
			$list[$index]['xm'] = $this->get_xiangmu($_W['uniacid'], $row['xmid']);
			$list[$index]['css'] = $paytype[$row['paytype']]['css'];
			if ($list[$index]['paytype'] == 1) {
				if (empty($list[$index]['transid'])) {
					if ($list[$index]['paystatus'] == 1) {
						$list[$index]['paytype'] = '';
					} else {
						$list[$index]['paytype'] = '支付宝支付';
					}
				} else {
					$list[$index]['paytype'] = '微信支付';
				}
			} else {
				$list[$index]['paytype'] = $paytype[$row['paytype']]['name'];
			}
		}
		$sum_price_all = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where}", $params);
		$sum_price_confirm = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND status=0 AND paystatus=1", $params);
		$sum_price_pay = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND status=0 AND paystatus=2", $params);
		$sum_price_finish = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND status=1 AND paystatus=2", $params);
		$sum_price_cancel = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND ( status=2 or status=-1 )", $params);
		$sum_price_end = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND status=3", $params);
		$order_count_all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where}", $params);
		$order_count_confirm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND status=0 AND paystatus=1", $params);
		$order_count_pay = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND status=0 AND paystatus=2", $params);
		$order_count_finish = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND status=1 AND paystatus=2", $params);
		$order_count_cancel = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND ( status=2 or status=-1 )", $params);
		$order_count_end = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuebiz_info') . "  WHERE {$where} AND status=3", $params);
		if (!empty($_GPC['export'])) {
			$tableheader = pdo_fetchall("SELECT title FROM " . tablename('dayu_yuyuebiz_fields') . " AS f JOIN " . tablename('dayu_yuyuebiz_info') . " AS r ON f.reid='{$reid}' GROUP BY title ORDER BY refid");
			$tablelength = count($tableheader);
			$tableheaders[] = array('title' => '姓名');
			$tableheaders[] = array('title' => '手机');
			$tableheaders[] = array('title' => '预约项目');
			$tableheaders[] = array('title' => '付款状态');
			$tableheaders[] = array('title' => '预约状态');
			$tableheaders[] = array('title' => '预约时间');
			$tableheader[] = array('title' => '提交时间');
			$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_info') . " WHERE {$where} {$allstatus} ORDER BY createtime DESC";
			$list = pdo_fetchall($sql, $params);
			if (empty($list)) {
				message('暂时没有预约数据');
			}
			foreach ($list as &$r) {
				$sql = 'SELECT data, refid FROM ' . tablename('dayu_yuyuebiz_data') . " WHERE `reid`=:reid AND `rerid`='{$r['rerid']}' ORDER BY redid";
				$paramss = array();
				$paramss[':reid'] = $r['reid'];
				$r['fields'] = array();
				$fdatas = pdo_fetchall($sql, $paramss);
				foreach ($fdatas as $fd) {
					if (false == array_key_exists($fd['refid'], $r['fields'])) {
						$r['fields'][$fd['refid']] = $fd['data'];
					} else {
						$r['fields'][$fd['refid']] .= '-' . $fd['data'];
					}
				}
			}
			$data = array();
			foreach ($list as $key => $value) {
				$data[$key]['member'] = $value['member'];
				$data[$key]['mobile'] = $value['mobile'];
				$data[$key]['xmid'] = $this->get_xiangmu($_W['uniacid'], $value['xmid']);
				$data[$key]['paystatus'] = $value['paystatus'];
				$data[$key]['status'] = $value['status'];
				$data[$key]['price'] = $value['price'];
				$data[$key]['time'] = $value['restime'];
				if (!empty($value['fields'])) {
					foreach ($value['fields'] as $field) {
						if (substr($field, 0, 6) == 'images') {
							$data[$key][] = str_replace(array("\n", "\r", "\t"), '', $_W['attachurl'] . $field);
						} else {
							$data[$key][] = str_replace(array("\n", "\r", "\t"), '', $field);
						}
					}
				}
				$data[$key]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
			}
			$html = "\xEF\xBB\xBF";
			foreach ($tableheaders as $value) {
				$html .= $value['title'] . "\t ,";
			}
			foreach ($tableheader as $value) {
				$html .= $value['title'] . "\t ,";
			}
			$html .= "\n";
			foreach ($data as $value) {
				if ($value['paystatus'] == 1) {
					$paystatus = '未付款';
				} elseif ($value['paystatus'] == 2) {
					$paystatus = '已付款';
				}
				if ($value['status'] == '0') {
					$huifu = '等待确认';
				} elseif ($value['status'] == '1') {
					$huifu = '已确认';
				} elseif ($value['status'] == '2') {
					$huifu = '已拒绝';
				} elseif ($value['status'] == '3') {
					$huifu = '已完成';
				} elseif ($value['status'] == '-1') {
					$huifu = '客户取消';
				}
				$html .= $value['member'] . "\t ,";
				$html .= $value['mobile'] . "\t ,";
				$html .= $value['xmid']['title'] . "-" . $value['price'] . "\t ,";
				$html .= $paystatus . "\t ,";
				$html .= $huifu . "\t ,";
				$html .= $value['time'] . "\t ,";
				for ($i = 0; $i < $tablelength; $i++) {
					$html .= $value[$i] . "\t ,";
				}
				$html .= $value['createtime'] . "\t ,";
				$html .= "\n";
			}
			$stime = date('Ymd', $starttime);
			$etime = date('Ymd', $endtime);
			header("Content-type:text/csv");
			header("Content-Disposition:attachment; filename=预约数据{$stime}-{$etime}.csv");
			echo $html;
			die;
		}
		foreach ($list as $key => &$value) {
			if (is_array($value['fields'])) {
				foreach ($value['fields'] as &$v) {
					$img = '<div align="center"><img src="';
					if (substr($v, 0, 6) == 'images') {
						$v = $img . $_W['attachurl'] . $v . '" style="width:50px;height:50px;"/></div>';
					}
				}
				unset($v);
			}
		}
		if ($op == 'super') {
			$title = "预约记录管理 - 高级权限";
			include $this->template('manages');
		} elseif ($op == 'simple') {
			$title = "预约记录管理";
			include $this->template('manage');
		}
	}
	public function doWebbatchrecord()
	{
		global $_GPC, $_W;
		$reid = intval($_GPC['reid']);
		$reply = pdo_fetch("select reid from " . tablename('dayu_yuyuebiz') . " where reid = :reid", array(':reid' => $reid));
		if (empty($reply)) {
			message('抱歉，表单主题不存在或是已经被删除！');
		}
		foreach ($_GPC['idArr'] as $k => $rerid) {
			$rerid = intval($rerid);
			pdo_delete('dayu_yuyuebiz_info', array('rerid' => $rerid, 'reid' => $reid));
			pdo_delete('dayu_yuyuebiz_data', array('rerid' => $rerid, 'reid' => $reid));
		}
		message('记录批量删除成功！', '', 0);
	}
	public function doWebcategory()
	{
		global $_GPC, $_W;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update($this->category_table, array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename($this->category_table) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder desc, id desc");
			foreach ($category as $index => $item) {
				$category[$index]['link'] = murl('entry', array('do' => 'list', 'typeid' => $item['id'], 'm' => 'dayu_yuyuebiz'), true, true);
			}
			include $this->template('category');
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename($this->category_table) . " WHERE id = '{$id}'");
			} else {
				$category = array('displayorder' => 0);
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['typename'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array('weid' => $_W['uniacid'], 'list' => intval($_GPC['list']), 'title' => $_GPC['typename'], 'displayorder' => intval($_GPC['displayorder']));
				if (!empty($id)) {
					pdo_update($this->category_table, $data, array('id' => $id));
				} else {
					pdo_insert($this->category_table, $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
			}
			include $this->template('category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT * FROM " . tablename($this->category_table) . " WHERE id = '{$id}'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
			}
			pdo_delete($this->category_table, array('id' => $id));
			message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
		}
	}
	public function doWebDisplay()
	{
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'super';
		if ($_W['ispost']) {
			$reid = intval($_GPC['reid']);
			$switch = intval($_GPC['switch']);
			$sql = 'UPDATE ' . tablename('dayu_yuyuebiz') . ' SET `status`=:status WHERE `reid`=:reid';
			$params = array();
			$params[':status'] = $switch;
			$params[':reid'] = $reid;
			pdo_query($sql, $params);
			die;
		}
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid';
		$status = $_GPC['status'];
		if ($status != '') {
			$sql .= " and status=" . intval($status);
		}
		$ds = pdo_fetchall($sql, array(':weid' => $_W['uniacid']));
		foreach ($ds as &$item) {
			$item['isstart'] = $item['starttime'] > 0;
			$item['switch'] = $item['status'];
			$item['link'] = murl('entry', array('do' => 'timelists', 'id' => $item['reid'], 'm' => 'dayu_yuyuebiz'), true, true);
			$item['mylink'] = murl('entry', array('do' => 'mydayu_yuyuebiz', 'id' => $item['reid'], 'weid' => $item[weid], 'm' => 'dayu_yuyuebiz'), true, true);
		}
		if ($op == 'super') {
			include $this->template('displays');
		} elseif ($op == 'simple') {
			include $this->template('display');
		}
	}
	public function doWebItem()
	{
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
		$weid = $_W['uniacid'];
		if ($op == 'list') {
			$where = ' weid = :weid';
			$params[':weid'] = $weid;
			if (!empty($_GPC['keyword'])) {
				$where .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuebiz_xiangmu') . ' WHERE ' . $where, $params);
			$lists = pdo_fetchall('SELECT * FROM ' . tablename('dayu_yuyuebiz_xiangmu') . ' WHERE ' . $where . ' ORDER BY id DESC,displayorder ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params, 'id');
			foreach ($lists as $key => $val) {
				$lists[$key]['isshow'] = $lists[$key]['isshow'] == 1 ? '<span class="label label-warning">显示</span>' : '<span class="badge">隐藏</span>';
				$lists[$key]['category'] = $this->get_category($val['typeid']);
			}
			$pager = pagination($total, $pindex, $psize);
			if (checksubmit('submit')) {
				if (!empty($_GPC['ids'])) {
					foreach ($_GPC['ids'] as $k => $v) {
						$data = array('title' => trim($_GPC['title'][$k]), 'price' => $_GPC['price'][$k], 'displayorder' => intval($_GPC['displayorder'][$k]));
						pdo_update('dayu_yuyuebiz_xiangmu', $data, array('id' => intval($v)));
					}
					message('编辑成功', $this->createWebUrl('item', array('op' => 'list')), 'success');
				}
			}
			include $this->template('item');
		} elseif ($op == 'post') {
			load()->func('tpl');
			$id = intval($_GPC['id']);
			if ($id) {
				$item = pdo_fetch('SELECT * FROM ' . tablename('dayu_yuyuebiz_xiangmu') . ' WHERE weid = :weid AND id = :id', array(':weid' => $weid, ':id' => $id));
			}
			$category = pdo_fetchall("SELECT * FROM " . tablename($this->category_table) . " WHERE weid = :weid ORDER BY id", array(':weid' => $weid));
			if (empty($category)) {
				message('请先添加分类！', $this->createWebUrl('category', array('op' => 'post')), 'error');
			}
			if (checksubmit('submit')) {
				$data = array('weid' => $weid, 'title' => $_GPC['title'], 'typeid' => $_GPC['typeid'], 'price' => $_GPC['price'], 'content' => $_GPC['content'], 'displayorder' => intval($_GPC['displayorder']), 'isshow' => intval($_GPC['isshow']));
				if (!empty($_GPC['thumb'])) {
					$data['thumb'] = $_GPC['thumb'];
					load()->func('file');
					file_delete($_GPC['thumb-old']);
				}
				if ($id) {
					pdo_update('dayu_yuyuebiz_xiangmu', $data, array('id' => $id));
				} else {
					if (!$id) {
						pdo_insert('dayu_yuyuebiz_xiangmu', $data);
					}
				}
				message('添加服务项目成功', $this->createWebUrl('item', array('op' => 'list')), 'success');
			}
			include $this->template('item');
		} elseif ($op == 'edit') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch('SELECT * FROM ' . tablename('dayu_yuyuebiz_xiangmu') . ' WHERE weid = :weid AND id = :id', array(':weid' => $weid, ':id' => $id));
			if (checksubmit('submit')) {
				if (!empty($_GPC['title'])) {
					foreach ($_GPC['title'] as $k => $v) {
						$v = trim($v);
						if (empty($v)) {
							continue;
						}
						$data['title'] = $v;
						$data['title'] = $_GPC['title'][$k];
						$data['num'] = intval($_GPC['num'][$k]);
						$data['weid'] = $_GPC['weid'][$k];
						if (!empty($_GPC['thumb'][$k])) {
							$data['thumb'] = $_GPC['thumb'][$k];
							load()->func('file');
							file_delete($_GPC['thumb-old'][$k]);
						}
						$data['description'] = trim($_GPC['description'][$k]);
						$data['createtime'] = time();
						pdo_update('dayu_yuyuebiz_xiangmu', $data, array('id' => $id));
					}
				}
				message('修改服务项目成功', $this->createWebUrl('item', array('op' => 'list')), 'success');
			}
			include $this->template('item');
		} elseif ($op == 'itemdel') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				pdo_delete('dayu_yuyuebiz_xiangmu', array('id' => $id));
			}
			message('删除成功.', referer());
		}
	}
	public function doWebnotice()
	{
		global $_W, $_GPC;
		load()->model('mc');
		$weid = $_W['uniacid'];
		$reid = intval($_GPC['id']);
		$credit = intval($_GPC['credit']);
		$activity = pdo_fetch('SELECT reid,title,m_templateid FROM ' . tablename('dayu_yuyuebiz') . ' WHERE weid = :weid AND reid = :reid', array(':weid' => $weid, ':reid' => $reid));
		$yytime = urldecode($_GPC['yytime']);
		$setting = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors', 'uc', 'payment', 'passport'));
		$behavior = $setting['creditbehaviors'];
		$creditnames = $setting['creditnames'];
		if (checksubmit('submit')) {
			$time = date('Y-m-d H:i', time());
			$where .= 'reid=:reid';
			$params = array();
			$params[':reid'] = $reid;
			if (empty($_GPC['yytime'])) {
				message('预约时间不能空.', referer(), 'error');
			} elseif (!empty($_GPC['yytime'])) {
				$where .= ' AND restime like :yytime';
				$params[':yytime'] = "%{$_GPC['yytime']}%";
			}
			$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_info') . " WHERE {$where} ORDER BY createtime DESC";
			$member = pdo_fetchall($sql, $params);
			if (is_array($member)) {
				foreach ($member as $s) {
					$xiangmu = $this->get_xiangmu($_W['uniacid'], $s['xmid']);
					$template = array("touser" => $s['openid'], "template_id" => $_GPC['notice'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_yuyuebiz', array('weid' => $_W['uniacid'], 'id' => $s['reid'])), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode("{$_GPC['first']}\\n"), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($s['member']), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($s['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($s['restime']), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($xiangmu['title'] . " - " . $xiangmu['price'] . "元"), 'color' => "#FF0000"), 'remark' => array('value' => urlencode("\\n{$_GPC['remark']}"), 'color' => "#008000")));
					$this->send_template_message(urldecode(json_encode($template)));
				}
				message('群发通知完成.', $this->createWebUrl('display'));
			}
		}
		include $this->template('notice');
	}
	public function doWebSlide()
	{
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz_slide') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array('weid' => $_W['uniacid'], 'title' => $_GPC['title'], 'link' => $_GPC['link'], 'enabled' => intval($_GPC['enabled']), 'displayorder' => intval($_GPC['displayorder']));
				if (!empty($_GPC['thumb'])) {
					$data['thumb'] = $_GPC['thumb'];
					load()->func('file');
					file_delete($_GPC['thumb-old']);
				}
				if (!empty($id)) {
					pdo_update('dayu_yuyuebiz_slide', $data, array('id' => $id));
				} else {
					pdo_insert('dayu_yuyuebiz_slide', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('slide', array('op' => 'display')), 'success');
			}
			$slide = pdo_fetch("select * from " . tablename('dayu_yuyuebiz_slide') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$slide = pdo_fetch("SELECT id  FROM " . tablename('dayu_yuyuebiz_slide') . " WHERE id = '{$id}' AND weid=" . $_W['uniacid'] . "");
			if (empty($slide)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('slide', array('op' => 'display')), 'error');
			}
			pdo_delete('dayu_yuyuebiz_slide', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('slide', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('slide', TEMPLATE_INCLUDEPATH, true);
	}
	public function doWebSlides()
	{
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz_slides') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array('weid' => $_W['uniacid'], 'title' => $_GPC['title'], 'link' => $_GPC['link'], 'enabled' => intval($_GPC['enabled']), 'displayorder' => intval($_GPC['displayorder']));
				if (!empty($_GPC['thumb'])) {
					$data['thumb'] = $_GPC['thumb'];
					load()->func('file');
					file_delete($_GPC['thumb-old']);
				}
				if (!empty($id)) {
					pdo_update('dayu_yuyuebiz_slides', $data, array('id' => $id));
				} else {
					pdo_insert('dayu_yuyuebiz_slides', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('slides', array('op' => 'display')), 'success');
			}
			$slide = pdo_fetch("select * from " . tablename('dayu_yuyuebiz_slides') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$slide = pdo_fetch("SELECT id  FROM " . tablename('dayu_yuyuebiz_slides') . " WHERE id = '{$id}' AND weid=" . $_W['uniacid'] . "");
			if (empty($slide)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('slides', array('op' => 'display')), 'error');
			}
			pdo_delete('dayu_yuyuebiz_slides', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('slides', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('slides', TEMPLATE_INCLUDEPATH, true);
	}
	public function doMobileList()
	{
		global $_GPC, $_W;
		require 'fans.mobile.php';
		$oauth_openid = "dayu_yuyuebiz_" . $weid;
		if (empty($_COOKIE[$oauth_openid])) {
			$this->getCode($reid);
		}
		$setting = $this->module['config'];
		if ($setting['mode'] == 0 || $_GPC['mode'] == 1) {
			$setting['list_num'] = !empty($setting['list_num']) ? $setting['list_num'] : 3;
			$xmid = $_GPC['xmid'];
			$list = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz_slide') . " WHERE weid=:weid ORDER BY displayorder DESC", array(':weid' => $_W['uniacid']));
			foreach ($list as $key => $val) {
				$list[$key]['thumb'] = tomedia($val['thumb']);
			}
			$category = pdo_fetchall("SELECT * FROM " . tablename($this->category_table) . " WHERE weid = :weid ORDER BY displayorder DESC, id DESC", array(':weid' => $_W['uniacid']));
			$rcategory = array();
			foreach ($category as &$c) {
				$c['yylist'] = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz_xiangmu') . " WHERE weid = :weid and typeid=:typeid ORDER BY displayorder desc, id desc", array(':weid' => $_W['uniacid'], ':typeid' => $c['id']));
				$rcategory[] = $c;
			}
			$projects = pdo_fetchAll("SELECT * FROM" . tablename('dayu_yuyuebiz_xiangmu') . " WHERE isshow=1 AND weid=:weid order by displayorder desc, id desc", array(':weid' => $_W['uniacid']));
			foreach ($projects as $index => $v) {
				$projects[$index]['link'] = $this->createMobileUrl('view', array('xmid' => $v['id']));
			}
			$hslists = unserialize($item['hs_pic']);
		} else {
			$setting['thumb'] = tomedia($setting['thumb']);
		}
		include $this->template('list');
	}
	public function doMobileView()
	{
		global $_GPC, $_W;
		require 'fans.mobile.php';
		$oauth_openid = "dayu_yuyuebiz_" . $weid;
		if (empty($_COOKIE[$oauth_openid])) {
			$this->getCode($reid);
		}
		$setting = $this->module['config'];
		$xmid = $_GPC['xmid'];
		$projects = pdo_fetch("SELECT * FROM" . tablename('dayu_yuyuebiz_xiangmu') . " WHERE isshow=1 AND id=:xmid order by id desc", array(':xmid' => $xmid));
		$title = $projects['title'];
		include $this->template('view');
	}
	public function doMobileyList()
	{
		global $_GPC, $_W;
		require 'fans.mobile.php';
		$oauth_openid = "dayu_yuyuebiz_" . $weid;
		if (empty($_COOKIE[$oauth_openid])) {
			$this->getCode($reid);
		}
		$setting = $this->module['config'];
		$xmid = $_GPC['xmid'];
		$mode = $_GPC['list'];
		$list = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz_slides') . " WHERE weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid));
		foreach ($list as $key => $val) {
			$list[$key]['thumb'] = tomedia($val['thumb']);
		}
		$yuyue = pdo_fetchAll("SELECT * FROM" . tablename('dayu_yuyuebiz') . " WHERE status=1 AND is_list=1 AND weid=:weid order by reid desc", array(':weid' => $weid));
		foreach ($yuyue as $index => $v) {
			$yuyue[$index]['link'] = $this->createMobileUrl('timelist', array('id' => $v['reid'], 'xmid' => $xmid));
			$yuyue[$index]['icon'] = !empty($v['icon']) ? '<img src="' . $_W['attachurl'] . $v['icon'] . '" class="am-radius">' : '<img src="' . $_W['attachurl'] . '/headimg_' . $weid . '.jpg" class="am-radius">';
		}
		$hslists = unserialize($item['hs_pic']);
		$title = '选择门店';
		include $this->template('yuyue');
	}
	public function payResult($params)
	{
		global $_W;
		$fee = intval($params['fee']);
		$data = array('paystatus' => $params['result'] == 'success' ? 2 : 1);
		$paytype = array('credit' => '2', 'wechat' => '1', 'alipay' => '1', 'delivery' => '3');
		if (!empty($params['is_usecard'])) {
			$cardType = array('1' => '微信卡券', '2' => '系统代金券');
			$data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . ($params['fee'] - $params['card_fee']);
			$data['paydetail'] .= '元，实际支付了' . $params['card_fee'] . '元。';
		}
		$data['paytype'] = $paytype[$params['type']];
		if ($paytype[$params['type']] == '') {
			$data['paytype'] = 4;
			$data['paystatus'] = 2;
		}
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
			$transaction = "\n支付单号" . $params['tag']['transaction_id'];
		}
		if ($params['type'] == 'delivery') {
			$data['paystatus'] = 1;
		}
		pdo_update('dayu_yuyuebiz_info', $data, array('ordersn' => $params['tid']));
		if ($params['from'] == 'return') {
			$order = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_info') . " WHERE ordersn = :ordersn", array(':ordersn' => $params['tid']));
			$activity = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz') . " WHERE reid = :reid", array(':reid' => $order['reid']));
			$xiangmu = $this->get_xiangmu($_W['uniacid'], $order['xmid']);
			$ytime = date('Y-m-d H:i:s', $order['yuyuetime']);
			$template = array("touser" => $activity['kfid'], "template_id" => $activity['k_templateid'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array('id' => $order['rerid'], 'reid' => $order['reid'])), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode("用户已付款\\n"), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($order['member']), 'color' => '#FF0000'), 'keyword2' => array('value' => urlencode($order['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($order['restime']), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($activity['title'] . ' - ' . $xiangmu['title'] . ' - ' . $order['price']), 'color' => '#FF0000'), 'remark' => array('value' => urlencode("\\n请及时受理"), 'color' => "#008000")));
			load()->func('communication');
			$this->send_template_message(urldecode(json_encode($template)));
			$setting = $this->module['config'];
			$status = $setting['zt'] == 1 ? 1 : 0;
			if ($setting['zt'] == 1) {
				$record = array();
				$record['status'] = 1;
				pdo_update('dayu_yuyuebiz_info', $record, array('rerid' => $order['rerid']));
			}
			$settings = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $settings['creditbehaviors']['currency'];
			$outlink = !empty($activity['outlink']) ? $activity['outlink'] : $this->createMobileUrl('mydayu_yuyuebiz', array('weid' => $_W['uniacid'], 'id' => $order['reid']));
			if ($params['type'] == $credit) {
				message('支付成功！', $outlink, 'success');
			} else {
				message('支付成功！', $outlink, 'success');
			}
		}
	}
	public function doMobilePay()
	{
		global $_W, $_GPC;
		checkauth();
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if (!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.');
		}
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_info') . " WHERE rerid = :rerid", array(':rerid' => $orderid));
		$xm = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_xiangmu') . " WHERE weid = :weid and id = :xmid", array(':weid' => $_W['uniacid'], ':xmid' => $order['xmid']));
		if ($order['paystatus'] != '1') {
			message('抱歉，您的预约已经付款或是被关闭，请查看预约列表。', $this->createMobileUrl('mydayu_yuyuebiz', array('weid' => $row['weid'], 'id' => $row['reid'])), 'error');
		}
		if ($order['price'] == '0.00') {
			$this->payResult(array('tid' => $order['ordersn'], 'from' => 'return', 'type' => 'credit2'));
			die;
		}
		$title = $order['num'] > 1 ? $xm['title'] . "×" . $order['num'] : $xm['title'];
		$params['tid'] = $order['ordersn'];
		$params['user'] = $_W['openid'];
		$params['fee'] = $order['price'];
		$params['title'] = $title;
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = 1;
		$log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => 'dayu_yuyuebiz', 'tid' => $params['tid']));
		if (empty($log)) {
			$log = array('uniacid' => $_W['uniacid'], 'acid' => $_W['acid'], 'openid' => $_W['member']['uid'], 'module' => $this->module['name'], 'tid' => $params['tid'], 'fee' => $params['fee'], 'card_fee' => $params['fee'], 'status' => '0', 'is_usecard' => '0');
			pdo_insert('core_paylog', $log);
		}
		include $this->template('pay');
	}
	public function doWebDelete()
	{
		global $_W, $_GPC;
		$reid = intval($_GPC['id']);
		if ($reid > 0) {
			$params = array();
			$params[':reid'] = $reid;
			$sql = 'DELETE FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			$sql = 'DELETE FROM ' . tablename('dayu_yuyuebiz_info') . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			$sql = 'DELETE FROM ' . tablename('dayu_yuyuebiz_fields') . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			$sql = 'DELETE FROM ' . tablename('dayu_yuyuebiz_data') . ' WHERE `reid`=:reid';
			pdo_query($sql, $params);
			message('操作成功.', referer());
		}
		message('非法访问.');
	}
	public function doWebdayu_yuyuebizDelete()
	{
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		if (!empty($id)) {
			pdo_delete('dayu_yuyuebiz_info', array('rerid' => $id));
			pdo_delete('dayu_yuyuebiz_data', array('rerid' => $id));
		}
		message('删除成功.', referer());
	}
	public function doWebPost()
	{
		global $_W, $_GPC;
		$reid = intval($_GPC['id']);
		$hasData = false;
		if ($reid) {
			$sql = 'SELECT COUNT(*) FROM ' . tablename('dayu_yuyuebiz_info') . ' WHERE `reid`=' . $reid;
			if (pdo_fetchcolumn($sql) > 0) {
				$hasData = true;
			}
		}
		if (checksubmit()) {
			$record = array();
			$record['title'] = trim($_GPC['activity']);
			$record['subtitle'] = trim($_GPC['subtitle']);
			$record['weid'] = $_W['uniacid'];
			$record['description'] = trim($_GPC['description']);
			$record['content'] = trim($_GPC['content']);
			$record['information'] = trim($_GPC['information']);
			$record['thumb'] = $_GPC['thumb'];
			$record['icon'] = $_GPC['icon'];
			$record['status'] = intval($_GPC['status']);
			$record['inhome'] = intval($_GPC['inhome']);
			$record['upsize'] = intval($_GPC['upsize']);
			$record['pretotal'] = intval($_GPC['pretotal']);
			$record['pay'] = intval($_GPC['pay']);
			$record['xmshow'] = intval($_GPC['xmshow']);
			$record['xmname'] = trim($_GPC['xmname']);
			$record['numname'] = trim($_GPC['numname']);
			$record['yuyuename'] = trim($_GPC['yuyuename']);
			$record['starttime'] = strtotime($_GPC['starttime']);
			$record['endtime'] = strtotime($_GPC['endtime']);
			$record['noticeemail'] = trim($_GPC['noticeemail']);
			$record['k_templateid'] = trim($_GPC['k_templateid']);
			$record['kfid'] = trim($_GPC['kfid']);
			$record['m_templateid'] = trim($_GPC['m_templateid']);
			$record['address'] = trim($_GPC['address']);
			$record['tel'] = trim($_GPC['tel']);
			$record['loc_x'] = $_GPC['baidumap']['lat'];
			$record['loc_y'] = $_GPC['baidumap']['lng'];
			$record['code'] = intval($_GPC['code']);
			$record['kfirst'] = trim($_GPC['kfirst']);
			$record['kfoot'] = trim($_GPC['kfoot']);
			$record['mfirst'] = trim($_GPC['mfirst']);
			$record['mfoot'] = trim($_GPC['mfoot']);
			$record['mobile'] = trim($_GPC['mobile']);
			$record['accountsid'] = trim($_GPC['accountsid']);
			$record['tokenid'] = trim($_GPC['tokenid']);
			$record['appId'] = trim($_GPC['appId']);
			$record['templateId'] = trim($_GPC['templateId']);
			$record['mname'] = trim($_GPC['mname']);
			$record['skins'] = trim($_GPC['skins']);
			$record['outlink'] = trim($_GPC['outlink']);
			$record['share_url'] = trim($_GPC['share_url']);
			$record['kaishi'] = intval($_GPC['kaishi']);
			$record['jieshu'] = intval($_GPC['jieshu']);
			$record['tianshu'] = intval($_GPC['tianshu']);
			$record['follow'] = intval($_GPC['follow']);
			$record['is_list'] = intval($_GPC['is_list']);
			$record['is_num'] = intval($_GPC['is_num']);
			$record['is_addr'] = intval($_GPC['is_addr']);
			$record['iscard'] = intval($_GPC['iscard']);
			$record['timelist'] = intval($_GPC['timelist']);
			$record['srvtime'] = htmlspecialchars_decode($_GPC['srvtime']);
			$record['day'] = intval($_GPC['day']);
			if (empty($reid)) {
				$record['status'] = 1;
				$record['createtime'] = TIMESTAMP;
				pdo_insert('dayu_yuyuebiz', $record);
				$reid = pdo_insertid();
				if (!$reid) {
					message('保存预约失败, 请稍后重试.');
				}
			} else {
				if (pdo_update('dayu_yuyuebiz', $record, array('reid' => $reid)) === false) {
					message('保存预约失败, 请稍后重试.');
				}
			}
			if (!$hasData) {
				$sql = 'DELETE FROM ' . tablename('dayu_yuyuebiz_fields') . ' WHERE `reid`=:reid';
				$params = array();
				$params[':reid'] = $reid;
				pdo_query($sql, $params);
				foreach ($_GPC['title'] as $k => $v) {
					$field = array();
					$field['reid'] = $reid;
					$field['title'] = trim($v);
					$field['displayorder'] = range_limit($_GPC['displayorder'][$k], 0, 254);
					$field['type'] = $_GPC['type'][$k];
					$field['essential'] = $_GPC['essentialvalue'][$k] == 'true' ? 1 : 0;
					$field['bind'] = $_GPC['bind'][$k];
					$field['value'] = $_GPC['value'][$k];
					$field['value'] = urldecode($field['value']);
					$field['description'] = $_GPC['desc'][$k];
					pdo_insert('dayu_yuyuebiz_fields', $field);
				}
			}
			message('保存预约成功.', $this->createWebUrl('display'), 'success');
		}
		$types = array();
		$types['number'] = '数字(number)';
		$types['text'] = '字串(text)';
		$types['textarea'] = '文本(textarea)';
		$types['radio'] = '单选(radio)';
		$types['checkbox'] = '多选(checkbox)';
		$types['select'] = '下拉框(select)';
		$types['calendar'] = '日期(calendar)';
		$types['range'] = '时间(range)';
		$types['email'] = '邮件(email)';
		$types['image'] = '上传图片(image)';
		$types['reside'] = '省市区(reside)';
		$fields = fans_fields();
		if ($reid) {
			$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
			$params = array();
			$params[':weid'] = $_W['uniacid'];
			$params[':reid'] = $reid;
			$activity = pdo_fetch($sql, $params);
			$activity['starttime'] && ($activity['starttime'] = date('Y-m-d H:i:s', $activity['starttime']));
			$activity['endtime'] && ($activity['endtime'] = date('Y-m-d H:i:s', $activity['endtime']));
			$activity['map'] = array('lat' => $activity['loc_x'], 'lng' => $activity['loc_y']);
			if ($activity) {
				$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_fields') . ' WHERE `reid`=:reid ORDER BY `displayorder` DESC';
				$params = array();
				$params[':reid'] = $reid;
				$ds = pdo_fetchall($sql, $params);
			}
		}
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':reid'] = $reid;
		$reply = pdo_fetch($sql, $params);
		if (!$reply) {
			$activity = array("mname" => "我的预约", "xmname" => "服务项目", "numname" => "数量", "yuyuename" => "预约时间", "kfirst" => "有新的客户预约，请及时确认", "kfoot" => "点击确认预约，或修改预约时间", "mfirst" => "预约结果通知", "mfoot" => "如有疑问，请致电联系我们", "information" => "您的预约申请我们已经收到, 请等待客服确认.", "pretotal" => "100", "kaishi" => 1, "jieshu" => 22, "tianshu" => 15, "day" => 5, "status" => 1, "is_list" => 1, "is_addr" => 1, "upsize" => 640, "skins" => "flat", "endtime" => date('Y-m-d H:i:s', strtotime('+30 day')));
		}
		load()->func('tpl');
		include $this->template('post');
	}
	public function doMobileTimelist()
	{
		global $_GPC, $_W;
		require 'fans.mobile.php';
		$id = intval($_GPC['id']);
		$datetime = $_GPC['datetime'];
		$sql = "SELECT * FROM " . tablename('dayu_yuyuebiz') . " WHERE status=1 AND weid='{$weid}' AND reid='{$_GPC['id']}'";
		$project = pdo_fetch($sql);
		if ($project['follow'] == 1) {
			$oauth_openid = "dayu_yuyuebiz_" . $weid;
			if (empty($_COOKIE[$oauth_openid])) {
				$this->getCode($id);
			}
			$this->getFollow();
		} else {
			$userinfo_oauth = mc_oauth_userinfo();
			if (!is_error($userinfo_oauth) && !empty($userinfo_oauth) && is_array($userinfo_oauth) && !empty($userinfo_oauth['avatar'])) {
				$avatar = $userinfo_oauth['avatar'];
			}
		}
		$setting = $this->module['config'];
		$xmid = $_GPC['xmid'];
		$link = $_W['siteroot'] . 'app/' . $this->createMobileUrl('todaytime', array('id' => $id, 'xmid' => $xmid));
		$weekarray = array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
		$timelist = json_decode($project['srvtime'], TRUE);
		$paytime = strtotime("now") - 1800;
		$havs = pdo_fetchall("SELECT restime,count(reid) as rescount from " . tablename('dayu_yuyuebiz_info') . " WHERE ((status=0 and paystatus=2) or (status=0 and paystatus=1 and createtime >= '{$paytime}') or status=1 or status=3) AND reid=:id GROUP BY restime", array(':id' => $id), 'restime');
		$dates = array();
		$now = new DateTime();
		while (count($dates) < $project['day']) {
			$now->modify('+1 days');
			if (in_array($now->format('w'), $timelist['weekset'])) {
				$dates[] = $now->format('Y-m-d');
			}
		}
		$title = '选择预约时间';
		if ($project['timelist'] == 0) {
			include $this->template('timelist');
		} elseif ($project['timelist'] == 1) {
			include $this->template('timelists');
		}
	}
	public function doMobiletodayTime()
	{
		global $_GPC, $_W;
		require 'fans.mobile.php';
		$id = intval($_GPC['id']);
		$xmid = $_GPC['xmid'];
		$setting = $this->module['config'];
		$sql = "SELECT * FROM " . tablename('dayu_yuyuebiz') . " WHERE status=1 AND weid='{$weid}' AND reid='{$_GPC['id']}'";
		$project = pdo_fetch($sql);
		if ($project['follow'] == 1) {
			$oauth_openid = "dayu_yuyuebiz_" . $weid;
			if (empty($_COOKIE[$oauth_openid])) {
				$this->getCode($id);
			}
			$this->getFollow();
		} else {
			$userinfo_oauth = mc_oauth_userinfo();
			if (!is_error($userinfo_oauth) && !empty($userinfo_oauth) && is_array($userinfo_oauth) && !empty($userinfo_oauth['avatar'])) {
				$avatar = $userinfo_oauth['avatar'];
			}
		}
		$link = $_W['siteroot'] . 'app/' . $this->createMobileUrl('timelist', array('id' => $id, 'xmid' => $xmid));
		$weekarray = array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
		$timelist = json_decode($project['srvtime'], TRUE);
		$paytime = strtotime("now") - 1800;
		$havs = pdo_fetchall("SELECT restime,count(reid) as rescount from " . tablename('dayu_yuyuebiz_info') . " WHERE ((status=0 and paystatus=2) or (status=0 and paystatus=1 and createtime >= '{$paytime}') or status=1 or status=3) AND reid=:id GROUP BY restime", array(':id' => $id), 'restime');
		$now = new DateTime();
		$dates = $now->format('Y-m-d');
		$title = '选择预约时间';
		if ($project['timelist'] == 0) {
			include $this->template('timelist');
		} elseif ($project['timelist'] == 1) {
			include $this->template('today');
		}
	}
	public function doMobiledayu_yuyuebiz()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$settings = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors', 'uc', 'payment', 'passport'));
		$behavior = $settings['creditbehaviors'];
		$creditnames = $settings['creditnames'];
		$credits = mc_credit_fetch($_W['member']['uid'], '*');
		$userinfo = $this->get_userinfo($weid, $openid);
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $weid;
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		if ($activity['follow'] == 1) {
			$oauth_openid = "dayu_yuyuebiz_" . $weid;
			if (empty($_COOKIE[$oauth_openid])) {
				$this->getCode($reid);
			}
			$this->getFollow();
		} else {
			$userinfo_oauth = mc_oauth_userinfo();
			if (!is_error($userinfo_oauth) && !empty($userinfo_oauth) && is_array($userinfo_oauth) && !empty($userinfo_oauth['avatar'])) {
				$avatar = $userinfo_oauth['avatar'];
			}
		}
		if ($activity['iscard'] == 1) {
			$ishy = $this->isHy($weid, $openid);
			if ($ishy == false) {
				message('您还不是会员,请先领取您的会员卡.', $this->createMobileUrl('mycard', array('a' => 'card', 'c' => 'mc', 'i' => $weid), false), 'info');
			}
		}
		$repeat = $_COOKIE['r_submit'];
		if (!empty($_GPC['repeat'])) {
			if (!empty($repeat)) {
				if ($repeat == $_GPC['repeat']) {
					message($activity['information'], $this->createMobileUrl('mydayu_yuyuebiz', array('weid' => $weid, 'id' => $reid)));
				} else {
					setcookie("r_submit", $_GPC['repeat']);
				}
			} else {
				setcookie("r_submit", $_GPC['repeat']);
			}
		}
		$setting = $this->module['config'];
		$datetime = $_GPC['datetime'];
		$ii = $_GPC['ii'];
		$srvtime = base64_decode($datetime);
		$sid = $_GPC['xmid'];
		$xms = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz_xiangmu') . " WHERE weid = :weid and isshow=1 ORDER BY displayorder DESC,id DESC", array(':weid' => $weid));
		$xiangmu = $this->get_xiangmu($weid, $sid);
		$title = $activity['title'];
		$yuyuetime = date('Y-m-d H:i', time() + 3600);
		if ($activity['status'] != '1') {
			message('当前预约已经停止.');
		}
		if (!$activity) {
			message('非法访问.');
		}
		if ($activity['starttime'] > TIMESTAMP) {
			message('当前预约还未开始！');
		}
		$activity['thumb'] = tomedia($activity['thumb']);
		$address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(':uid' => $_W['fans']['uid']));
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_fields') . ' WHERE `reid` = :reid ORDER BY `displayorder` DESC';
		$params = array();
		$params[':reid'] = $reid;
		$ds = pdo_fetchall($sql, $params);
		if (!$ds) {
			message('非法访问.');
		}
		$initRange = $initCalendar = false;
		$binds = array();
		foreach ($ds as &$r) {
			if ($r['type'] == 'range') {
				$initRange = true;
			}
			if ($r['type'] == 'calendar') {
				$initCalendar = true;
			}
			if ($r['value']) {
				$r['options'] = explode(',', $r['value']);
			}
			if ($r['bind']) {
				$binds[$r['type']] = $r['bind'];
			}
			if ($r['type'] == 'reside') {
				$reside = $r;
			}
		}
		$xm = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_xiangmu') . " WHERE id = :id", array(':id' => $_GPC['xmid']));
		$resideprovince = !empty($userinfo['resideprovince']) ? $userinfo['resideprovince'] : '广东省';
		$residecity = !empty($userinfo['residecity']) ? $userinfo['residecity'] : '广州市';
		if (checksubmit('submit')) {
			$pretotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuebiz_info') . " WHERE reid = :reid AND openid = :openid", array(':reid' => $reid, ':openid' => $_W['openid']));
			if ($pretotal >= $activity['pretotal']) {
				message('抱歉,每人只能预约' . $activity['pretotal'] . "次！", referer(), 'error');
			}
			if (empty($address) && $activity['is_addr'] == 1) {
				message('抱歉，联系方式不能为空！');
			}
			if (empty($_GPC['restime'])) {
				message('预约时间不能为空，请点击下面的链接返回选择预约时间', $this->createMobileUrl('timelist', array('id' => $reid, 'xmid' => $sid)), 'error');
			}
			$paytime = strtotime("now") - 1800;
			$timelist = json_decode($activity['srvtime'], TRUE);
			$recordnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuebiz_info') . " WHERE ((status=0 and paystatus=2) or (status=0 and paystatus=1 and createtime >= '{$paytime}') or status=1 or status=3) AND reid = :reid AND restime LIKE '%{$srvtime}%'", array(':reid' => $reid));
			if ($timelist['times'][$ii]['number'] <= $recordnum) {
				message('已约满，重新选择时间', $this->createMobileUrl('timelist', array('id' => $reid, 'datetime' => $datetime)), 'error');
			}
			$row['price'] = $items['price'] + $_GPC['prices'];
			$num = empty($_GPC['num']) ? 1 : $_GPC['num'];
			$price = !empty($sid) ? $xiangmu['price'] : $xm['price'];
			$member = $activity['is_addr'] == 1 ? $address['username'] : $_GPC['member'];
			$mobile = $activity['is_addr'] == 1 ? $address['mobile'] : $_GPC['mobile'];
			$row = array();
			$row['reid'] = $reid;
			$row['weid'] = $weid;
			$row['member'] = $member;
			$row['mobile'] = $mobile;
			$row['address'] = $address['province'] . $address['city'] . $address['district'] . $address['address'] . '|' . $address['zipcode'];
			$row['openid'] = $_W['openid'];
			$row['xmid'] = $_GPC['xmid'];
			$row['ordersn'] = date('mdHis') . random(5, 1);
			$row['num'] = $num;
			$row['price'] = $price * $num;
			$row['paystatus'] = 1;
			$row['paytype'] = intval($_GPC['paytype']);
			$row['restime'] = $_GPC['restime'];
			$row['createtime'] = TIMESTAMP;
			$datas = $fields = $update = array();
			foreach ($ds as $value) {
				$fields[$value['refid']] = $value;
			}
			foreach ($_GPC as $key => $value) {
				if (strexists($key, 'field_')) {
					$bindFiled = substr(strrchr($key, '_'), 1);
					if (!empty($bindFiled)) {
						$update[$bindFiled] = $value;
					}
					$refid = intval(str_replace('field_', '', $key));
					$field = $fields[$refid];
					if ($refid && $field) {
						$entry = array();
						$entry['reid'] = $reid;
						$entry['rerid'] = 0;
						$entry['refid'] = $refid;
						if (in_array($field['type'], array('number', 'text', 'calendar', 'email', 'textarea', 'radio', 'range', 'select', 'image'))) {
							$entry['data'] = strval($value);
						}
						if (in_array($field['type'], array('checkbox'))) {
							if (!is_array($value)) {
								continue;
							}
							$entry['data'] = implode(';', $value);
						}
						$datas[] = $entry;
					}
				}
			}
			if ($_FILES) {
				foreach ($_FILES as $key => $file) {
					if (strexists($key, 'field_')) {
						$refid = intval(str_replace('field_', '', $key));
						$field = $fields[$refid];
						if ($refid && $field && $file['name'] && $field['type'] == 'image') {
							$upfile = $file;
							$name = $upfile['name'];
							$type = $upfile['type'];
							$size = $upfile['size'];
							$tmp_name = $upfile['tmp_name'];
							$error = $upfile['error'];
							$upload_path = "../attachment/dayu_yuyuebiz/" . $weid . "/";
							load()->func('file');
							@mkdirs($upload_path);
							if (intval($error) > 0) {
								message('上传错误：错误代码：' . $error, referer(), 'error');
							} else {
								$maxfilesize = 18;
								if ($maxfilesize > 0) {
									if ($size > $maxfilesize * 1024 * 1024) {
										message('上传文件过大' . $_FILES["file"]["error"], referer(), 'error');
									}
								}
								$uptypes = array('image/jpg', 'image/png', 'image/jpeg');
								if (!in_array($type, $uptypes)) {
									message('上传文件类型不符：' . $type, referer(), 'error');
								}
								if (!file_exists($upload_path)) {
									mkdir($upload_path);
								}
								$source_filename = 'yuyue' . $reid . '_' . date("YmdHis") . mt_rand(10, 99);
								$target_filename = 'yuyue' . $reid . '_' . date("YmdHis") . mt_rand(10, 99) . '.thumb.jpg';
								if (!move_uploaded_file($tmp_name, $upload_path . $source_filename)) {
									message('移动文件失败，请检查服务器权限', referer(), 'error');
								}
								$srcfile = $upload_path . $source_filename;
								$desfile = $upload_path . $target_filename;
								$avatarsize = !empty($activity['upsize']) ? $activity['upsize'] : 640;
								$ret = file_image_thumb($srcfile, $desfile, $avatarsize);
								$entry = array();
								$entry['reid'] = $reid;
								$entry['rerid'] = 0;
								$entry['refid'] = $refid;
								if (!is_array($ret)) {
									$entry['data'] = $upload_path . $target_filename;
								}
								$datas[] = $entry;
							}
						}
					}
					unlink($srcfile);
				}
			}
			if (!empty($_GPC['reside'])) {
				if (in_array('reside', $binds)) {
					$update['resideprovince'] = $_GPC['reside']['province'];
					$update['residecity'] = $_GPC['reside']['city'];
					$update['residedist'] = $_GPC['reside']['district'];
				}
				foreach ($_GPC['reside'] as $key => $value) {
					$resideData = array('reid' => $reside['reid']);
					$resideData['rerid'] = 0;
					$resideData['refid'] = $reside['refid'];
					$resideData['data'] = $value;
					$datas[] = $resideData;
				}
			}
			$update['realname'] = $_GPC['member'];
			$update['mobile'] = $_GPC['mobile'];
			if (empty($datas)) {
				message('非法访问.', '', 'error');
			}
			if (pdo_insert('dayu_yuyuebiz_info', $row) != 1) {
				message('保存失败.');
			}
			$rerid = pdo_insertid();
			$orderid = pdo_insertid();
			if (empty($rerid)) {
				message('保存失败.');
			}
			foreach ($datas as &$r) {
				$r['rerid'] = $rerid;
				pdo_insert('dayu_yuyuebiz_data', $r);
			}
			if (empty($activity['starttime'])) {
				$record = array();
				$record['starttime'] = TIMESTAMP;
				pdo_update('dayu_yuyuebiz', $record, array('reid' => $reid));
			}
			foreach ($datas as $row) {
				$img = "<img src='{$_W['attachurl']}";
				if (substr($row['data'], 14, 15) == 'dayu_yuyuebiz') {
					$body = $fields[$row['refid']]['title'] . ':' . $img . $row['data'] . " ' width='90';height='120'/>";
				}
				$body .= '<h4>' . $fields[$row['refid']]['title'] . ':' . $row['data'] . '</h4>';
				$bodym .= $fields[$row['refid']]['title'] . ':' . $row['data'] . ',';
			}
			if (!empty($datas) && !empty($activity['noticeemail'])) {
				load()->func('communication');
				ihttp_email($activity['noticeemail'], $activity['title'] . '的预约提醒', '<h4>姓名：' . $member . '</h4><h4>手机：' . $mobile . '</h4><h4>预约时间：' . $_GPC['restime'] . '</h4><h4>预约项目：' . $xiangmu['title'] . '</h4>' . $body);
			}
			if (!empty($activity['mobile'])) {
				$this->SendSms($activity['mobile'], $member, $mobile, $xiangmu['title'], $activity['accountsid'], $activity['tokenid'], $activity['appId'], $activity['templateId']);
			}
			if (!empty($datas) && !empty($activity['kfid']) && !empty($activity['k_templateid'])) {
				$template = array("touser" => $activity['kfid'], "template_id" => $activity['k_templateid'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage', array('weid' => $row['weid'], 'id' => $row['reid'])), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode("{$activity['kfirst']}\\n"), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($member), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($mobile), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($_GPC['restime']), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($xiangmu['title'] . " - " . $xiangmu['price'] . "元"), 'color' => "#FF0000"), 'remark' => array('value' => urlencode("\\n{$activity['kfoot']}"), 'color' => "#008000")));
				load()->func('communication');
				$this->send_template_message(urldecode(json_encode($template)));
			}
			$outlink = !empty($activity['outlink']) ? $activity['outlink'] : $this->createMobileUrl('mydayu_yuyuebiz', array('weid' => $row['weid'], 'id' => $row['reid']));
			if ($_GPC['paytype'] == 1) {
				message('预约提交成功，点击确定前去付款', $this->createMobileUrl('pay', array('orderid' => $orderid, 'weid' => $_GPC['weid'], 'id' => $_GPC['id'])), 'success');
			} else {
				if ($_GPC['paytype'] == 9 || $activity['pay'] == 1) {
					message($activity['information'], $outlink);
				}
			}
		}
		foreach ($binds as $key => $value) {
			if ($value == 'reside') {
				unset($binds[$key]);
				$binds[] = 'resideprovince';
				$binds[] = 'residecity';
				$binds[] = 'residedist';
				break;
			}
			if ($value == 'birth') {
				unset($binds[$key]);
				$binds[] = 'birthyear';
				$binds[] = 'birthmonth';
				$binds[] = 'birthday';
				break;
			}
		}
		if (!empty($openid) && !empty($binds)) {
			$profile = fans_search($_W['openid'], $binds);
			if ($profile['gender']) {
				if ($profile['gender'] == '0') {
					$profile['gender'] = '保密';
				}
				if ($profile['gender'] == '1') {
					$profile['gender'] = '男';
				}
				if ($profile['gender'] == '2') {
					$profile['gender'] = '女';
				}
			}
			foreach ($ds as &$r) {
				if ($profile[$r['bind']]) {
					$r['default'] = $profile[$r['bind']];
				}
			}
		}
		load()->func('tpl');
		$_share['title'] = $activity['title'];
		$_share['content'] = $activity['description'];
		$_share['imgUrl'] = tomedia($activity['thumb']);
		include $this->template($activity['skins']);
	}
	public function doMobilegetprice()
	{
		global $_GPC, $_W;
		$price = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz_xiangmu') . " WHERE weid = :weid AND id = :id AND isshow=1", array(':weid' => $_W['uniacid'], ':id' => $_GPC['xmid']));
		if (empty($price)) {
			$result['status'] = 0;
			$result['price'] = '无法获取价格';
			message($result, '', 'ajax');
		}
		$result['status'] = 1;
		$result['price'] = $price;
		message($result, '', 'ajax');
	}
	public function doMobileMydayu_yuyuebiz()
	{
		global $_W, $_GPC;
		checkauth();
		require 'fans.mobile.php';
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $weid;
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		$status = intval($_GPC['status']);
		$setting = $this->module['config'];
		$list = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz') . " WHERE weid = :weid and status = 1 ORDER BY reid DESC", array(':weid' => $weid), 'reid');
		$tmp = array();
		foreach ($list as $act) {
			array_push($tmp, $act['reid']);
		}
		$freid = implode(',', $tmp);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$status = intval($_GPC['status']);
		if ($status != '') {
			if ($status == 2) {
				$where .= " and ( status=2 or status=-1 )";
			} else {
				$where .= " and status={$status}";
			}
		}
		if ($reid) {
			$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_info') . " WHERE openid = :openid and reid = :reid {$where} ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$params = array();
			$params[':reid'] = $reid;
			$params[':openid'] = $openid;
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuebiz_info') . " WHERE openid = :openid and reid = :reid {$where} ", $params);
		} else {
			$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_info') . " WHERE openid = :openid and reid IN ({$freid}) {$where} ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$params = array();
			$params[':openid'] = $openid;
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuebiz_info') . " WHERE openid = :openid {$where} ", $params);
		}
		$pager = dayupagination($total, $pindex, $psize);
		$rows = pdo_fetchall($sql, $params);
		$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'success', 'name' => '在线支付'), '2' => array('css' => 'secondary', 'name' => '余额/积分'), '3' => array('css' => 'warning', 'name' => '其他付款方式'), '4' => array('css' => 'secondary', 'name' => '无需支付'), '9' => array('css' => 'secondary', 'name' => '线下支付'));
		foreach ($rows as $key => $val) {
			$rows[$key]['activity'] = $this->get_yuyuepay($weid, $val['reid']);
			$rows[$key]['item'] = $this->get_xiangmu($_W['uniacid'], $val['xmid']);
			$rows[$key]['css'] = $paytype[$val['paytype']]['css'];
			if ($rows[$key]['paytype'] == 1) {
				if (empty($rows[$key]['transid'])) {
					if ($rows[$key]['paystatus'] == 1) {
						$rows[$key]['paytype'] = '';
					} else {
						$rows[$key]['paytype'] = '支付宝支付';
					}
				} else {
					$rows[$key]['paytype'] = '微信支付';
				}
			} else {
				$rows[$key]['paytype'] = $paytype[$val['paytype']]['name'];
			}
		}
		$itemname = !empty($activity['xmname']) ? $activity['xmname'] : '服务项目';
		$title = !empty($activity['title']) ? $activity['title'] : '预约';
		include $this->template('dayu_yuyuebiz');
	}
	public function doMobiledetail()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$setting = $this->module['config'];
		$id = intval($_GPC['id']);
		$reid = intval($_GPC['reid']);
		$row = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_info') . " WHERE openid = :openid AND rerid = :rerid", array(':openid' => $openid, ':rerid' => $id));
		if (empty($row)) {
			message('订单不存在或是已经被删除或该订单不归您所有！');
		}
		$row['create'] = !empty($row['createtime']) ? date('Y-m-d H:i', $row['createtime']) : '时间丢失';
		$row['yuyuetime'] = !empty($row['yuyuetime']) ? date('Y-m-d H:i', $row['yuyuetime']) : '客服尚未受理本订单';
		$row['outtime'] = $row['createtime'] + 1800;
		$offline = $row['paytype'];
		$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'success', 'name' => '在线支付'), '2' => array('css' => 'secondary', 'name' => '余额/积分'), '3' => array('css' => 'warning', 'name' => '其他付款方式'), '4' => array('css' => 'secondary', 'name' => '无需支付'), '9' => array('css' => 'secondary', 'name' => '线下支付'));
		$row['css'] = $paytype[$row['paytype']]['css'];
		if ($row['paytype'] == 1) {
			if (empty($row['transid'])) {
				if ($row['paystatus'] == 1) {
					$row['paytype'] = '';
				} else {
					$row['paytype'] = '支付宝支付';
				}
			} else {
				$row['paytype'] = '微信支付';
			}
		} else {
			$row['paytype'] = $paytype[$row['paytype']]['name'];
		}
		$qrcode = $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array('reid' => $row['reid'], 'id' => $row['rerid']));
		$qrcodesrc = tomedia('headimg_' . $_W['acid'] . '.jpg');
		$activity = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz') . " WHERE reid = :reid", array(':reid' => $row['reid']));
		$activity['fields'] = pdo_fetchall("SELECT a.title, a.type, b.data FROM " . tablename('dayu_yuyuebiz_fields') . " AS a LEFT JOIN " . tablename('dayu_yuyuebiz_data') . " AS b ON a.refid = b.refid WHERE a.reid = :reid AND b.rerid = :rerid", array(':reid' => $row['reid'], ':rerid' => $id));
		$xiangmu = $this->get_xiangmu($_W['uniacid'], $row['xmid']);
		if ($_W['ispost']) {
			$record = array();
			$record['status'] = intval($_GPC['status']);
			pdo_update('dayu_yuyuebiz_info', $record, array('rerid' => $id));
			message('取消订单成功', referer(), 'success');
		}
		$title = $activity['title'];
		include $this->template('detail');
	}
	public function doMobilemanage()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$setting = $this->module['config'];
		load()->func('tpl');
		$reid = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $weid;
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		$list = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuebiz') . " WHERE weid = :weid and status = 1 and kfid = :openid ORDER BY reid DESC", array(':weid' => $weid, ':openid' => $openid), 'reid');
		if (!empty($reid)) {
			if ($openid != $activity['kfid']) {
				message('非法访问！你不是管理员。');
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$status = intval($_GPC['status']);
			if ($status != '') {
				if ($status == 2) {
					$where .= " and ( status=2 or status=-1 )";
				} else {
					$where .= " and status={$status}";
				}
			}
			$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_info') . " WHERE reid=:reid {$where} ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$params = array();
			$params[':reid'] = $reid;
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuebiz_info') . " WHERE reid = :reid {$where} ", $params);
			$pager = dayupagination($total, $pindex, $psize);
			$rows = pdo_fetchall($sql, $params);
			$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'success', 'name' => '在线支付'), '2' => array('css' => 'secondary', 'name' => '余额/积分'), '3' => array('css' => 'warning', 'name' => '其他付款方式'), '4' => array('css' => 'secondary', 'name' => '无需支付'), '9' => array('css' => 'secondary', 'name' => '线下支付'));
			foreach ($rows as $key => $val) {
				$rows[$key]['item'] = $this->get_xiangmu($_W['uniacid'], $val['xmid']);
				$rows[$key]['createtime'] = date('Y-m-d H:i', $val['createtime']);
				$rows[$key]['css'] = $paytype[$val['paytype']]['css'];
				if ($rows[$key]['paytype'] == 1) {
					if (empty($rows[$key]['transid'])) {
						if ($rows[$key]['paystatus'] == 1) {
							$rows[$key]['paytype'] = '';
						} else {
							$rows[$key]['paytype'] = '支付宝支付';
						}
					} else {
						$rows[$key]['paytype'] = '微信支付';
					}
				} else {
					$rows[$key]['paytype'] = $paytype[$val['paytype']]['name'];
				}
			}
			$itemname = !empty($activity['xmname']) ? $activity['xmname'] : '服务项目';
			$title = !empty($activity['title']) ? $activity['title'] : '微预约';
		}
		include $this->template('dayu_manage');
	}
	public function doMobilemanage_detail()
	{
		global $_W, $_GPC;
		require 'fans.mobile.php';
		$setting = $this->module['config'];
		$id = intval($_GPC['id']);
		$reid = intval($_GPC['reid']);
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz') . ' WHERE `weid`=:weid AND `reid`=:reid';
		$params = array();
		$params[':weid'] = $weid;
		$params[':reid'] = $reid;
		$activity = pdo_fetch($sql, $params);
		if ($openid != $activity['kfid']) {
			message('非法访问！你不是管理员。');
		}
		$row = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_info') . " WHERE rerid = :rerid", array(':rerid' => $id));
		if (empty($row)) {
			message('记录不存在或是已经被删除！');
		}
		$row['createtime'] = !empty($row['createtime']) ? date('Y-m-d H:i', $row['createtime']) : '时间丢失';
		$yuyuetime = !empty($row['yuyuetime']) ? date('Y-m-d H:i', $row['yuyuetime']) : '时间丢失';
		$row['yuyuetime'] = !empty($row['yuyuetime']) ? date('Y-m-d H:i', $row['yuyuetime']) : '尚未受理本订单';
		$xiangmu = $this->get_xiangmu($_W['uniacid'], $row['xmid']);
		$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'success', 'name' => '在线支付'), '2' => array('css' => 'secondary', 'name' => '余额/积分'), '3' => array('css' => 'warning', 'name' => '其他付款方式'), '4' => array('css' => 'secondary', 'name' => '无需支付'), '9' => array('css' => 'secondary', 'name' => '线下支付'));
		$row['css'] = $paytype[$row['paytype']]['css'];
		if ($row['paytype'] == 1) {
			if (empty($row['transid'])) {
				if ($row['paystatus'] == 1) {
					$row['paytype'] = '';
				} else {
					$row['paytype'] = '支付宝支付';
				}
			} else {
				$row['paytype'] = '微信支付';
			}
		} else {
			$row['paytype'] = $paytype[$row['paytype']]['name'];
		}
		$activity['fields'] = pdo_fetchall("SELECT a.title, a.type, b.data FROM " . tablename('dayu_yuyuebiz_fields') . " AS a LEFT JOIN " . tablename('dayu_yuyuebiz_data') . " AS b ON a.refid = b.refid WHERE a.reid = :reid AND b.rerid = :rerid", array(':reid' => $row['reid'], ':rerid' => $row['rerid']));
		$sql = 'SELECT * FROM ' . tablename('dayu_yuyuebiz_fields') . ' WHERE `reid` = :reid ORDER BY `displayorder` DESC';
		$params = array();
		$params[':reid'] = $reid;
		$ds = pdo_fetchall($sql, $params);
		if (!$ds) {
			message('非法访问.');
		}
		if (checksubmit('submit')) {
			$record = array();
			$record['status'] = intval($_GPC['status']);
			$record['kfinfo'] = $_GPC['kfinfo'];
			if (!empty($_GPC['paystatus'])) {
				$record['paystatus'] = intval($_GPC['paystatus']);
			}
			$record['yuyuetime'] = strtotime($_GPC['yuyuetime']);
			if ($_GPC['status'] == '0') {
				$huifu = '等待客服确认（' . $_GPC['kfinfo'] . '）';
			} elseif ($_GPC['status'] == '1') {
				$huifu = '客服已确认（' . $_GPC['kfinfo'] . '）';
			} elseif ($_GPC['status'] == '2') {
				$huifu = '拒绝受理（' . $_GPC['kfinfo'] . '）';
			} elseif ($_GPC['status'] == '3') {
				$huifu = '服务完成。';
			}
			if (!empty($activity['m_templateid'])) {
				$template = array("touser" => $row['openid'], "template_id" => $activity['m_templateid'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('detail', array('reid' => $row['reid'], 'id' => $row['rerid'])), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode("{$activity['mfirst']}\\n"), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($row['member']), 'color' => '#FF0000'), 'keyword2' => array('value' => urlencode($xiangmu['title'] . " - " . $xiangmu['price'] . "元"), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($row['restime']), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($huifu), 'color' => '#000000'), 'remark' => array('value' => urlencode("\\n{$activity['mfoot']}"), 'color' => "#008000")));
				load()->func('communication');
				$this->send_template_message(urldecode(json_encode($template)));
			}
			pdo_update('dayu_yuyuebiz_info', $record, array('rerid' => $id));
			message('修改成功', $this->createMobileUrl('manage', array('id' => $row['reid'], 'status' => 0)), 'success');
		}
		$title = $activity['title'];
		include $this->template('manage_detail');
	}
	public function doMobileAddress()
	{
		global $_W, $_GPC;
		checkauth();
		$setting = $this->module['config'];
		$operation = $_GPC['op'];
		$title = '联系方式管理';
		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$data = array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid'], 'username' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'province' => $_GPC['province'], 'city' => $_GPC['city'], 'district' => $_GPC['area'], 'address' => $_GPC['address']);
			if (empty($data['username']) || empty($data['mobile']) || empty($data['address'])) {
				message('请输完善您的资料！');
			}
			if (!empty($id)) {
				unset($data['uniacid']);
				unset($data['uid']);
				pdo_update('mc_member_address', $data, array('id' => $id));
				message($id, '', 'ajax');
			} else {
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				$data['isdefault'] = 1;
				pdo_insert('mc_member_address', $data);
				$id = pdo_insertid();
				if (!empty($id)) {
					message($id, '', 'ajax');
				} else {
					message(0, '', 'ajax');
				}
			}
		} elseif ($operation == 'default') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id AND `uniacid` = :uniacid
								 AND `uid` = :uid';
			$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
			$address = pdo_fetch($sql, $params);
			if (!empty($address) && empty($address['isdefault'])) {
				pdo_update('mc_member_address', array('isdefault' => 0), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid']));
				pdo_update('mc_member_address', array('isdefault' => 1), array('uniacid' => $_W['uniacid'], 'uid' => $_W['fans']['uid'], 'id' => $id));
			}
			message(1, '', 'ajax');
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id';
			$row = pdo_fetch($sql, array(':id' => $id));
			message($row, '', 'ajax');
		} elseif ($operation == 'remove') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$where = ' AND `uniacid` = :uniacid AND `uid` = :uid';
				$sql = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id' . $where;
				$params = array(':id' => $id, ':uniacid' => $_W['uniacid'], ':uid' => $_W['fans']['uid']);
				$address = pdo_fetch($sql, $params);
				if (!empty($address)) {
					pdo_delete('mc_member_address', array('id' => $id));
					if ($address['isdefault'] > 0) {
						$sql = 'SELECT MAX(id) FROM ' . tablename('mc_member_address') . ' WHERE 1 ' . $where;
						unset($params[':id']);
						$maxId = pdo_fetchcolumn($sql, $params);
						if (!empty($maxId)) {
							pdo_update('mc_member_address', array('isdefault' => 1), array('id' => $maxId));
							die(json_encode(array("result" => 1, "maxid" => $maxId)));
						}
					}
				}
			}
			die(json_encode(array("result" => 1, "maxid" => 0)));
		} else {
			$sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid';
			$params = array(':uniacid' => $_W['uniacid']);
			if (empty($_W['member']['uid'])) {
				$params[':uid'] = $_W['fans']['openid'];
			} else {
				$params[':uid'] = $_W['member']['uid'];
			}
			$addresses = pdo_fetchall($sql, $params);
			include $this->template('address');
		}
	}
	public function isHy($openid)
	{
		global $_W;
		load()->model('mc');
		$card = pdo_fetch("SELECT * FROM " . tablename("mc_card_members") . " WHERE uniacid=:uniacid AND openid = :openid ", array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
		if (empty($card)) {
			return false;
		} else {
			return true;
		}
	}
	public function send_template_message($data)
	{
		global $_W, $_GPC;
		$atype = 'weixin';
		$account_code = "account_weixin_code";
		load()->classs('weixin.account');
		$access_token = WeAccount::token();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
		$response = ihttp_request($url, $data);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
		}
		return true;
	}
	public function SendSms($telephone, $member, $mobile, $yxiangmu, $accountsid, $tokenid, $appId, $templateId)
	{
		$result['state'] = 0;
		$options['accountsid'] = $accountsid;
		$options['token'] = $tokenid;
		$ucpass = new Ucpaas($options);
		$appId = $appId;
		$to = $telephone;
		$templateId = $templateId;
		$yxiangmu = $yxiangmu;
		$member = $member;
		${$mobile} = ${$mobile};
		$param = "{$member},{$mobile},{$yxiangmu}";
		$iscg = $ucpass->templateSMS($appId, $to, $templateId, $param);
	}
	public function get_skin($sections)
	{
		return pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " WHERE sections = :sections ", array(':sections' => $sections));
	}
	public function get_userinfo($weid, $from_user)
	{
		load()->model('mc');
		return mc_fetch($from_user);
	}
	public function get_xiangmu($weid, $xmid)
	{
		return pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz_xiangmu') . " WHERE weid = :weid and id = :id and isshow=1", array(':weid' => $weid, ':id' => $xmid));
	}
	public function get_yuyuepay($weid, $reid)
	{
		return pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuebiz') . " WHERE weid = :weid and reid = :reid and status=1", array(':weid' => $weid, ':reid' => $reid));
	}
	public function get_category($id)
	{
		global $_GPC, $_W;
		return pdo_fetch("SELECT * FROM " . tablename($this->category_table) . " WHERE id = :id", array(':id' => $id));
	}
	public function doMobileFansUs()
	{
		global $_W, $_GPC;
		$qrcodesrc = tomedia('qrcode_' . $_W['acid'] . '.jpg');
		include $this->template('fans_us');
	}
	public function get_curl($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return json_decode($data, 1);
	}
	public function post_curl($url, $post = '')
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return json_decode($data, 1);
	}
	private function getCode($id)
	{
		global $_GPC, $_W;
		$appid = $_W['account']['key'];
		$secret = $_W['account']['secret'];
		$level = $_W['account']['level'];
		if ($level == 4) {
			$oauth_openid = "dayu_yuyuebiz_" . $_W['uniacid'];
			if (empty($_COOKIE[$oauth_openid])) {
				$redirect_uri = url('entry&do=GetToken&m=dayu_yuyuebiz&id=' . $id, '', true);
				$redirect_uri = $_W['siteroot'] . 'app/' . $redirect_uri;
				$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=snsapi_base&state=0#wechat_redirewct';
				header('Location: ' . $url, true, 301);
			}
		} else {
			return '';
		}
	}
	public function doMobileGetToken()
	{
		global $_GPC, $_W;
		$appid = $_W['account']['key'];
		$secret = $_W['account']['secret'];
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $_GPC['code'] . '&grant_type=authorization_code';
		$data = $this->get_curl($url);
		if (empty($data)) {
			$data = file_get_contents($url);
			$data = json_decode($data, 1);
		}
		$oauth_openid = "dayu_yuyuebiz_" . $_W['uniacid'];
		setcookie($oauth_openid, $data['openid'], time() + self::$COOKIE_DAYS * 24 * 60 * 60);
		header('Location:' . $this->createMobileUrl('list'), true, 301);
	}
	public function getFollow()
	{
		global $_GPC, $_W;
		$p = pdo_fetch("SELECT follow FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :weid AND openid = :openid LIMIT 1", array(":weid" => $_W['uniacid'], ":openid" => $_W['openid']));
		if (intval($p['follow']) == 0) {
			header('Location: ' . $this->createMobileUrl('FansUs'), true, 301);
		} else {
			return true;
		}
	}
}
function tpl_form_field_dateyy($name, $value = array(), $ishour = false)
{
	$s = '';
	if (!defined('INCLUDE_DATE')) {
		$s = '
				<link type="text/css" rel="stylesheet" href="/addons/dayu_yuyuebiz/template/mobile/time/datetimepicker.css" />
				<script type="text/javascript" src="/addons/dayu_yuyuebiz/template/mobile/time/datetimepicker.js"></script>';
	}
	define('INCLUDE_DATE', true);
	if (strexists($name, '[')) {
		$id = str_replace(array('[', ']'), '_', $name);
	} else {
		$id = $name;
	}
	$value = empty($value) ? date('Y-m-d', mktime(0, 0, 0)) : $value;
	$ishour = empty($ishour) ? 2 : 0;
	$s .= '
		<input type="text" id="datepicker_' . $id . '" name="' . $name . '" value="' . $value . '" class="datetimepickers datetimepicker" readonly="readonly" />
		<script type="text/javascript">
			$("#datepicker_' . $id . '").datetimepicker({
				format: "yyyy-mm-dd hh:ii",
				minView: "' . $ishour . '",
				//pickerPosition: "top-right",
				autoclose: true
			});
		</script>';
	return $s;
}
function dayu_fans_form($field, $value = '')
{
	switch ($field) {
		case 'reside':
		case 'resideprovince':
		case 'residecity':
		case 'residedist':
			$html = dayu_form_field_district('reside', $value);
			break;
	}
	return $html;
}
function dayu_form_field_district($name, $values = array())
{
	$html = '';
	if (!defined('TPL_INIT_DISTRICT')) {
		$html .= '
				<script type="text/javascript">
					require(["jquery", "district"], function($, dis){
						$(".tpl-district-container").each(function(){
							var elms = {};
							elms.province = $(this).find(".tpl-province")[0];
							elms.city = $(this).find(".tpl-city")[0];
							elms.district = $(this).find(".tpl-district")[0];
							var vals = {};
							vals.province = $(elms.province).attr("data-value");
							vals.city = $(elms.city).attr("data-value");
							vals.district = $(elms.district).attr("data-value");
							dis.render(elms, vals, {withTitle: true});
						});
					});
				</script>';
		define('TPL_INIT_DISTRICT', true);
	}
	if (empty($values) || !is_array($values)) {
		$values = array('province' => '', 'city' => '', 'district' => '');
	}
	if (empty($values['province'])) {
		$values['province'] = '';
	}
	if (empty($values['city'])) {
		$values['city'] = '';
	}
	if (empty($values['district'])) {
		$values['district'] = '';
	}
	$html .= '
			<div class="tpl-district-container">
				<div class="col-lg-4">
					<select name="' . $name . '[province]" data-value="' . $values['province'] . '" class="tpl-province">
					</select><i></i>
				</div>
				<div class="col-lg-4">
					<select name="' . $name . '[city]" data-value="' . $values['city'] . '" class="tpl-city">
					</select><i></i>
				</div>
				<div class="col-lg-4">
					<select name="' . $name . '[district]" data-value="' . $values['district'] . '" class="tpl-district">
					</select><i></i>
				</div>
			</div>';
	return $html;
}
function dayu_fans_form_class($field, $value = '')
{
	switch ($field) {
		case 'reside':
		case 'resideprovince':
		case 'residecity':
		case 'residedist':
			$html = dayu_form_field_district_class('reside', $value);
			break;
	}
	return $html;
}
function dayu_form_field_district_class($name, $values = array())
{
	$html = '';
	if (!defined('TPL_INIT_DISTRICT')) {
		$html .= '
				<script type="text/javascript">
					require(["jquery", "district"], function($, dis){
						$(".tpl-district-container").each(function(){
							var elms = {};
							elms.province = $(this).find(".tpl-province")[0];
							elms.city = $(this).find(".tpl-city")[0];
							elms.district = $(this).find(".tpl-district")[0];
							var vals = {};
							vals.province = $(elms.province).attr("data-value");
							vals.city = $(elms.city).attr("data-value");
							vals.district = $(elms.district).attr("data-value");
							dis.render(elms, vals, {withTitle: true});
						});
					});
				</script>';
		define('TPL_INIT_DISTRICT', true);
	}
	if (empty($values) || !is_array($values)) {
		$values = array('province' => '', 'city' => '', 'district' => '');
	}
	if (empty($values['province'])) {
		$values['province'] = '';
	}
	if (empty($values['city'])) {
		$values['city'] = '';
	}
	if (empty($values['district'])) {
		$values['district'] = '';
	}
	$html .= '
				<div class="form-group">
					<select name="' . $name . '[province]" data-value="' . $values['province'] . '" class="tpl-province">
					</select><i></i>
				</div>
				<div class="form-group">
					<select id="service_cities" name="' . $name . '[city]" data-value="' . $values['city'] . '" class="tpl-city city">
					</select><i></i>
				</div>
				<div class="form-group">
					<select id="service_districts" name="' . $name . '[district]" data-value="' . $values['district'] . '" class="tpl-district district">
					</select><i></i>
				</div>';
	return $html;
}
function dayupagination($total, $pageIndex, $pageSize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => ''))
{
	global $_W;
	$pdata = array('tcount' => 0, 'tpage' => 0, 'cindex' => 0, 'findex' => 0, 'pindex' => 0, 'nindex' => 0, 'lindex' => 0, 'options' => '');
	if ($context['ajaxcallback']) {
		$context['isajax'] = true;
	}
	$pdata['tcount'] = $total;
	$pdata['tpage'] = ceil($total / $pageSize);
	if ($pdata['tpage'] <= 1) {
		return '';
	}
	$cindex = $pageIndex;
	$cindex = min($cindex, $pdata['tpage']);
	$cindex = max($cindex, 1);
	$pdata['cindex'] = $cindex;
	$pdata['findex'] = 1;
	$pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
	$pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
	$pdata['lindex'] = $pdata['tpage'];
	if ($context['isajax']) {
		if (!$url) {
			$url = $_W['script_name'] . '?' . http_build_query($_GET);
		}
		$pdata['faa'] = 'href="javascript:;" page="' . $pdata['findex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', this);return false;"' : '');
		$pdata['paa'] = 'href="javascript:;" page="' . $pdata['pindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', this);return false;"' : '');
		$pdata['naa'] = 'href="javascript:;" page="' . $pdata['nindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', this);return false;"' : '');
		$pdata['laa'] = 'href="javascript:;" page="' . $pdata['lindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', this);return false;"' : '');
	} else {
		if ($url) {
			$pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
			$pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
			$pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
			$pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
		} else {
			$_GET['page'] = $pdata['findex'];
			$pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['pindex'];
			$pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['nindex'];
			$pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['lindex'];
			$pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
		}
	}
	$html = '<ul class="am-pagination am-pagination-centered">';
	if ($pdata['cindex'] > 1) {
		$html .= "<li><a {$pdata['faa']} class=\"pager-nav\">&laquo;</a></li>";
		$html .= "<li><a {$pdata['paa']} class=\"pager-nav\">Prev</a></li>";
	}
	if (!$context['before'] && $context['before'] != 0) {
		$context['before'] = 5;
	}
	if (!$context['after'] && $context['after'] != 0) {
		$context['after'] = 4;
	}
	if ($context['after'] != 0 && $context['before'] != 0) {
		$range = array();
		$range['start'] = max(1, $pdata['cindex'] - $context['before']);
		$range['end'] = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
		if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
			$range['end'] = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
			$range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
		}
		for ($i = $range['start']; $i <= $range['end']; $i++) {
			if ($context['isajax']) {
				$aa = 'href="javascript:;" page="' . $i . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', this);return false;"' : '');
			} else {
				if ($url) {
					$aa = 'href="?' . str_replace('*', $i, $url) . '"';
				} else {
					$_GET['page'] = $i;
					$aa = 'href="?' . http_build_query($_GET) . '"';
				}
			}
			$html .= $i == $pdata['cindex'] ? '<li class="am-active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>';
		}
	}
	if ($pdata['cindex'] < $pdata['tpage']) {
		$html .= "<li><a {$pdata['naa']} class=\"pager-nav\">Next</a></li>";
		$html .= "<li><a {$pdata['laa']} class=\"pager-nav\">&raquo;</a></li>";
	}
	$html .= '</ul>';
	return $html;
}
function tpl_field_date($name, $value = '', $is_time, $withtime = false)
{
	$s = '';
	if (!defined('TPL_INIT_DATA')) {
		$s = '
					<script type="text/javascript">
						require(["datetimepicker"], function(){
							$(function(){
								$(".datetimepicker").each(function(){
									var option = {
										lang : "zh",
										step : "10",
										timepicker : ' . (!empty($withtime) ? "true" : "false") . ',closeOnDateSelect : true,
					format : "Y-m-d' . (!empty($withtime) ? ' H:i:s"' : '"') . '};
					$(this).datetimepicker(option);
				});
			});
		});
		</script>';
		define('TPL_INIT_DATA', true);
	}
	$withtime = empty($withtime) ? false : true;
	if ($is_time == 2) {
		$value = strexists($value, '-') ? strtotime($value) : $value;
	} else {
		$value = TIMESTAMP;
	}
	if (!empty($value)) {
		$value = $withtime ? date('Y-m-d H:i:s', $value) : date('Y-m-d', $value);
	}
	$s .= '<input type="text" name="' . $name . '"  value="' . $value . '" placeholder="预约日期筛选" class="datetimepicker btn btn-default" style="padding-left:12px;" />';
	return $s;
}