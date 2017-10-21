<?php

/**
 * 微考试
 *
 */
defined('IN_IA') or exit('Access Denied');

include "../addons/ewei_exam/model.php";
class Ewei_examModuleSite extends WeModuleSite
{
	public $_member = array();
	public $_from_user = '';
	public $_types_config = '';
	public $_img_url = '../addons/ewei_exam/images/';
	public $_script_url = '../addons/ewei_exam/style/';
	public $_search_key = '__exam_search';
	public $_set_info = array();
	public $_answer_array = array();

	function __construct()
	{
		global $_W;
		$this->_weid = $_W['uniacid'];
		$this->_member = exam_get_userinfo();
		$this->_from_user = $_W['fans']['openid'];
		$this->_set_info = get_ewei_exam_sysset();
		$init_param = get_init_param();
		$this->_types_config = $init_param['types_config'];
		$this->_answer_array = $init_param['answer_array'];
	}

	public function getItemTiles()
	{
		global $_W;
		$urls = array();
		$urls[] = array('title' => "首页", 'url' => $this->createMobileUrl('index'));
		$urls[] = array('title' => "我的预约", 'url' => $this->createMobileUrl('reservelist'));
		$list = pdo_fetchall("SELECT id,title FROM " . tablename('ewei_exam_paper') . " WHERE weid = '{$_W['uniacid']}'");
		if (!empty($list)) {
			foreach ($list as $row) {
				$urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('ready', array('id' => $row['id'])));
			}
		}
		return $urls;
	}

	public function doWebQuery() {
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename('ewei_exam_paper') . ' WHERE `weid`=:weid AND `title` LIKE :title';
		$params = array();
		$params[':weid'] = $_W['uniacid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		foreach ($ds as &$row) {
			$r = array();
			$r['id'] = $row['id'];
			$r['title'] = $row['title'];
			$r['content'] = cutstr($row['description'], 30, '...');
			$r['thumb'] = toimage( $row['thumb'] );
			$row['entry'] = $r;
		}
		include $this->template('query');
	}

	public function doWebdelete() {
		global $_GPC, $_W;

		$id = intval($_GPC['id']);
		pdo_delete('ewei_exam_reply', array('id' => $id));
		message("删除成功!", referer(), "success");
	}

	public function doWebCourse()
	{
		global $_GPC, $_W;

		$op = $_GPC['op'];
		$weid = $_W['uniacid'];

		//echo tpl_form_field_daterange('datelimit', array('starttime'=>$item['starttime'],'endtime'=>$item['endtime']), array('time'=>true));

		//exit;

		if ($op == 'edit') {
			//编辑
			$id = intval($_GPC['id']);
			//$tid = intval($_GPC['tid']);

			if (checksubmit()) {
				$insert = array(
					'weid' => $weid,
					'displayorder' => $_GPC['displayorder'],
					'title' => $_GPC['title'],
					'ccate' => $_GPC['ccate'],
					'ctype' => $_GPC['ctype'],
					'ctotal' => $_GPC['ctotal'],
					'teachers' => $_GPC['teachers'],
					'starttime' => strtotime($_GPC['datelimit']['start']),
					'endtime' => strtotime($_GPC['datelimit']['end']),
					'coursetime' => strtotime($_GPC['coursetime']),
					'times' => $_GPC['times'],
					'week' => $_GPC['week'],
					'thumb' => trim($_GPC['thumb']),
					'description' => $_GPC['description'],
					'content' => $_GPC['content'],
					'address' => $_GPC['address'],
					'week' => $_GPC['week'],
					'location_p' => $_GPC['district']['province'],
					'location_c' => $_GPC['district']['city'],
					'location_a' => $_GPC['district']['dist'],
					'lat' => $_GPC['baidumap']['lat'],
					'lng' => $_GPC['baidumap']['lng'],
					'status' => $_GPC['status'],
				);


				if (empty($id)) {
					pdo_insert('ewei_exam_course', $insert);
				} else {
					pdo_update('ewei_exam_course', $insert, array('id' => $id));
				}
				message("课程信息保存成功!", $this->createWebUrl('course'), "success");
			}
			if (!empty($id)) {
				$item = pdo_fetch("select * from " . tablename('ewei_exam_course') . " where id=:id limit 1", array(":id" => $id));
			} else {
				$item = array(
					'starttime' => TIMESTAMP,
					'endtime' => TIMESTAMP + 86400 * 30
				);
			}

			if(!empty($item)){
				$paper_category = pdo_fetch("select id, cname as title from ".tablename('ewei_exam_course_category')." where id=:id limit 1",array(':id'=>$item['ccate']));
			}

			//print_r($paper_category);exit;
			include $this->template('course_form');

		} else if ($op == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete("ewei_exam_course", array("id" => $id));
			message("课程信息删除成功!", referer(), "success");

		} else if ($op == 'deleteall') {
			foreach ($_GPC['idArr'] as $k => $id) {
				$id = intval($id);
				pdo_delete("ewei_exam_course", array("id" => $id));
			}
			$this->web_message('课程信息删除成功！', '', 0);
			exit();
		} else if ($op == 'showall') {
			if ($_GPC['show_name'] == 'showall') {
				$show_status = 1;
			} else {
				$show_status = 0;
			}

			foreach ($_GPC['idArr'] as $k => $id) {
				$id = intval($id);

				if (!empty($id)) {
					pdo_update('ewei_exam_course', array('status' => $show_status), array('id' => $id));
				}
			}
			//message('操作成功！', '', 0);
			exit();
		} else if ($op == 'status') {

			$id = intval($_GPC['id']);
			if (empty($id)) {
				message('抱歉，传递的参数错误！', '', 'error');
			}
			$temp = pdo_update('ewei_exam_course', array('status' => $_GPC['status']), array('id' => $id));
			if ($temp == false) {
				message('抱歉，刚才操作数据失败！', '', 'error');
			} else {
				message('状态设置成功！', referer(), 'success');
			}
		} else {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$sql = "";
			$params = array();
			if (!empty($_GPC['title'])) {
				$sql .= ' AND `title` LIKE :keywords';
				$params[':keywords'] = "%{$_GPC['title']}%";
			}

			if (!empty($_GPC['ccate'])) {
				$ccate = intval($_GPC['ccate']);
				//判断是否为一级分类
				$cate_sql = "SELECT id FROM " .tablename('ewei_exam_course_category');
				$cate_sql .=  " WHERE parentid = " . $ccate;
				$cate_sql .=  " AND weid = " . $weid;
				//$cate_sql .= " AND status = 1";

				$item = pdo_fetchall($cate_sql);
				$cate_num = count($item);

				if ($cate_num == 0) {
					$sql .= " AND ccate = :ccate";
					$params[':ccate'] = $ccate;
				} else if ($cate_num > 0) {
					$item[$cate_num]['id'] = $ccate;
					$cate_str = '';
					foreach ($item as $k => $v) {
						$cate_str .= $v['id'] . ",";
					}
					$cate_str = trim($cate_str, ",");
					$sql .= " AND ccate in (" . $cate_str . ")";
				}
			}

			$select_sql = "SELECT * FROM " . tablename('ewei_exam_course') . " as c";
			$select_sql .= " WHERE c.weid = '{$_W['uniacid']}'  $sql ORDER BY displayorder DESC,id  LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($select_sql, $params);

			$category = pdo_fetchall("SELECT * FROM " . tablename('ewei_exam_course_category') . " WHERE weid = '{$_W['uniacid']}' AND status = 1 ORDER BY parentid ASC, displayorder DESC");

			//print_r($category);exit;

			$count_sql = "SELECT COUNT(c.id) FROM " . tablename('ewei_exam_paper') . " as c";
			$count_sql .= " WHERE c.weid = '{$_W['uniacid']}'" . $sql;

			$total = pdo_fetchcolumn($count_sql, $params);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('course');
		}

	}

	public function doWebCourse_category()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('ewei_exam_course_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', $this->createWebUrl('course_category', array('op' => 'display')), 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename('ewei_exam_course_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('course_category');
		} elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('ewei_exam_course_category') . " WHERE id = '$id'");
			} else {
				$item = array(
					'displayorder' => 0,
				);
			}

			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, cname FROM " . tablename('ewei_exam_course_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['cname'])) {
					message('抱歉，请输入分类名称！');
				}

				$data = array(
					'weid' => $_W['uniacid'],
					'cname' => $_GPC['cname'],
					'displayorder' => intval($_GPC['displayorder']),
					'parentid' => intval($parentid),
					'description' => $_GPC['description'],
					'status' => intval($_GPC['status'])
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('ewei_exam_course_category', $data, array('id' => $id));
				} else {
					pdo_insert('ewei_exam_course_category', $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('course_category', array('op' => 'display')), 'success');
			}
			include $this->template('course_category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('ewei_exam_course_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('course_category', array('op' => 'display')), 'error');
			}
			pdo_delete('ewei_exam_course_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('course_category', array('op' => 'display')), 'success');
		} else if ($operation == 'query') {
			$kwd = trim($_GPC['keyword']);

			$sql = 'SELECT id, cname as title FROM ' . tablename('ewei_exam_course_category') . ' WHERE `weid`=:weid';
			$orderbysql = ' ORDER BY parentid ASC, displayorder DESC';
			$params = array();
			$params[':weid'] = $_W['uniacid'];
			if (!empty($kwd)) {
				$sql .= " AND `cname` LIKE :cname";
				$params[':cname'] = "%{$kwd}%";
			}
			$sql .= $orderbysql;
			$ds = pdo_fetchall($sql, $params);
			include $this->template('course_category_query');
		}
	}

	public function doWebPaper_category()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('ewei_exam_paper_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', $this->createWebUrl('paper_category', array('op' => 'display')), 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename('ewei_exam_paper_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('paper_category');
		} elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('ewei_exam_paper_category') . " WHERE id = '$id'");
			} else {
				$item = array(
					'displayorder' => 0,
				);
			}

			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, cname FROM " . tablename('ewei_exam_paper_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['cname'])) {
					message('抱歉，请输入分类名称！');
				}

				$data = array(
					'weid' => $_W['uniacid'],
					'cname' => $_GPC['cname'],
					'displayorder' => intval($_GPC['displayorder']),
					'parentid' => intval($parentid),
					'description' => $_GPC['description'],
					'status' => intval($_GPC['status'])
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('ewei_exam_paper_category', $data, array('id' => $id));
				} else {
					pdo_insert('ewei_exam_paper_category', $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', $this->createWebUrl('paper_category', array('op' => 'display')), 'success');
			}
			include $this->template('paper_category');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('ewei_exam_paper_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('paper_category', array('op' => 'display')), 'error');
			}
			pdo_delete('ewei_exam_paper_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('paper_category', array('op' => 'display')), 'success');
		} else if ($operation == 'query') {
			$kwd = trim($_GPC['keyword']);
			$sql = 'SELECT id, cname as title FROM ' . tablename('ewei_exam_paper_category') . ' WHERE `weid`=:weid';
			$ordersql = ' ORDER BY parentid ASC, displayorder DESC';
			$params = array();
			$params[':weid'] = $_W['uniacid'];
			if (!empty($kwd)) {
				$sql .= " AND `cname` LIKE :cname";
				$params[':cname'] = "%{$kwd}%";
			}
			$sql = $sql . $ordersql;
			$ds = pdo_fetchall($sql, $params);
			include $this->template('paper_category_query');

		}
	}

	public function doWebReserve()
	{
		global $_GPC, $_W;

		$weid = $_W['uniacid'];

		$op = $_GPC['op'];
		if($op=='edit'){
			$id = intval($_GPC['id']);
			if (!empty($id)) {

				$params = array();
				$params[':weid'] = $weid;
				$params[':id'] = $id;

				$sql = "SELECT p.courseid, p.msg, p.username, p.mobile, p.email, p.status as reserve_stauts, p.createtime as reserve_createtime, c.*";
				$sql .= " FROM " .tablename('ewei_exam_course_reserve') ." AS p";
				$sql .= " LEFT JOIN " .tablename('ewei_exam_course') . " AS c ON p.courseid = c.id";

				$sql .= " WHERE 1 = 1";
				$sql .= " AND p.weid = :weid";
				$sql .= " AND p.id = :id";

				$item = pdo_fetch($sql, $params);

				if (empty($item)) {
					message('抱歉，订单不存在或是已经删除！', '', 'error');
				}
			} else {
				message('抱歉，参数错误！', '', 'error');
			}

			if (checksubmit('submit')) {
				$old_status = $_GPC['oldstatus'];

				$data = array(
					'status' => $_GPC['status'],
					'msg' => $_GPC['msg'],
					'mngtime' => time(),
				);

				//订单确认
				if ($data['status'] == 1 && $old_status != 1) {
					pdo_query("update " . tablename('ewei_exam_course') . " set fansnum = fansnum + 1 where id=:id", array(":id" => $item['courseid']));
				}

				//订单取消
				if ($old_status == 1 && ($data['status'] != 1)) {
					if ($item['fansnum'] > 0) {
						pdo_query("update " . tablename('ewei_exam_course') . " set fansnum = fansnum - 1 where id=:id", array(":id" => $item['courseid']));
					}
				}

				pdo_update('ewei_exam_course_reserve', $data, array('id' => $id));
				message('订单信息处理完成！', $this->createWebUrl('reserve') , 'success');
			}


			include $this->template('reserve_form');
		}
		else if($op=='delete'){
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT id FROM " . tablename('ewei_exam_course_reserve') . " WHERE id = :id LIMIT 1", array(':id' => $id));

			if (empty($item)) {
				message('抱歉，订单不存在或是已经删除！', '', 'error');
			}
			pdo_delete('ewei_exam_course_reserve', array('id' => $id));
			message('删除成功！', referer(), 'success');
		} else if ($op == 'deleteall') {
			foreach ($_GPC['idArr'] as $k => $id) {
				$id = intval($id);
				pdo_delete('ewei_exam_course_reserve', array('id' => $id));
			}
			//$this->web_message('订单删除成功！', '', 0);
			exit();
		} else{

			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;

			$title = $_GPC['title'];
			$username = $_GPC['username'];
			$mobile = $_GPC['mobile'];
			$ordersn = $_GPC['ordersn'];
			$status =  $_GPC['status'];
			$ctype =  $_GPC['ctype'];

			$params = array();
			$params[':weid'] = $weid;

			$sql = "SELECT p.*, c.title, c.coursetime, c.ctype";
			$count_sql = "SELECT COUNT(p.id)";

			$where = '';
			$where .= " FROM " .tablename('ewei_exam_course_reserve') ." AS p";
			$where .= " LEFT JOIN " .tablename('ewei_exam_course') . " AS c ON p.courseid = c.id";

			$where .= " WHERE 1 = 1";
			$where .= " AND p.weid = :weid";

			if (!empty($title)) {
				$where .= ' AND c.title LIKE :title';
				$params[':title'] = "%{$title}%";
			}

			if (!empty($username)) {
				$where .= ' AND p.username LIKE :username';
				$params[':username'] = "%{$username}%";
			}

			if (!empty($mobile)) {
				$where .= ' AND p.mobile LIKE :mobile';
				$params[':mobile'] = "%{$mobile}%";
			}

			if (!empty($ordersn)) {
				$where .= ' AND p.ordersn LIKE :ordersn';
				$params[':ordersn'] = "%{$ordersn}%";
			}

			if($status != ''){
				$where.=" and p.status=".intval($status);
			}

			if($ctype != ''){
				$where.=" and c.ctype=".intval($ctype);
			}

			$sql .= $where;
			$sql .= " ORDER BY id DESC";
			if($pindex > 0) {
				// 需要分页
				$start = ($pindex - 1) * $psize;
				$sql .= " LIMIT {$start},{$psize}";
			}

			$count_sql .= $where;
			$list = pdo_fetchall($sql, $params);
			$total = pdo_fetchcolumn($count_sql, $params);

			$page_array = get_page_array($total, $pindex, $psize);

			//print_r($ctype);exit;

//

//
//            $roomtitle = $_GPC['roomtitle'];
//            $hoteltitle = $_GPC['hoteltitle'];
//            $condition = '';
//            $params = array();
//            if (!empty($hoteltitle)) {
//                $condition .= ' AND h.title LIKE :hoteltitle';
//                $params[':hoteltitle'] = "%{$hoteltitle}%";
//            }
//            if (!empty($roomtitle)) {
//                $condition .= ' AND r.title LIKE :roomtitle';
//                $params[':roomtitle'] = "%{$roomtitle}%";
//            }
//
//            if (!empty($realname)) {
//                $condition .= ' AND o.name LIKE :realname';
//                $params[':realname'] = "%{$realname}%";
//            }
//            if (!empty($mobile)) {
//                $condition .= ' AND o.mobile LIKE :mobile';
//                $params[':mobile'] = "%{$mobile}%";
//            }

//            if(!empty($hotelid)){
//                $condition.=" and o.hotelid=".$hotelid;
//            }
//            if(!empty($roomid)){
//                $condition.=" and o.roomid=".$roomid;
//            }

//
//            $pindex = max(1, intval($_GPC['page']));
//            $psize = 20;
//            $list = pdo_fetchall("SELECT o.*,h.title as hoteltitle,r.title as roomtitle FROM " . tablename('hotel2_order') . " o left join " . tablename('hotel2') .
//                "h on o.hotelid=h.id left join ".tablename("hotel2_room")." r on r.id = o.roomid  WHERE o.weid = '{$_W['uniacid']}' $condition ORDER BY o.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,$params);
//            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM  ' . tablename('hotel2_order') . " o left join " . tablename('hotel2') .
//                "h on o.hotelid=h.id left join ".tablename("hotel2_room")." r on r.id = o.roomid  WHERE o.weid = '{$_W['uniacid']}' $condition",$params);


			//$pager = pagination($total, $pindex, $psize);
			include $this->template('order');
		}
	}

	public function doWebQuestion()
	{
		global $_GPC, $_W;

		$op = $_GPC['op'];
		$weid = $_W['uniacid'];
		$types_config = $this->_types_config;

		load()->func('file');
		load()->func('tpl');

		if ($op == 'edit') {
			//编辑
			$id = intval($_GPC['id']);
			$paperid = intval($_GPC['paperid']);
			$referer = intval($_GPC['referer']);
			$isimg = intval($_GPC['isimg']);

			$answer_array = $this->_answer_array;

			if ($_W['ispost']) {
				$is_next = $_GPC['is_next'];

				$insert = array(
					'weid' => $weid,
					'question' => $_GPC['question'],
					'type' => $_GPC['type'],
					'paperid' => $_GPC['paperid'],
					'level' => $_GPC['level'],
					'poolid' => $_GPC['poolid'],
					'explain' => $_GPC['explain'],
				);

				$check_flag = 1;
				if (!empty($id) && ($_GPC['old_type'] == $_GPC['type'])) {
					$check_flag = 0;
				}

				if (empty($_GPC['paperid'])) {
					$check_flag = 0;
				}

				if ($check_flag == 1) {
					$data = $this->checkAddQuestion($insert);
					if ($data['result'] != 1) {
						message($data['msg'], '', 'error');
					}
				}

				unset($insert['paperid']);
				$type = $insert['type'];
				$items = "";
				$answer = "";
				if ($type == 1) {
					$answer = $_GPC['answer1'];
				} else if ($type == 2) {
					$answer = $_GPC['answer2'];
					$items = serialize($_GPC['items2']);
				} else if ($type == 3) {
					$arr = $_GPC['answer3'];
					$answer = implode("", $arr);
					$items = serialize($_GPC['items3']);
				} else if ($type == 4) {
					$answer = $_GPC['answer4'];
				} else if ($type == 5) {
					$answer = $_GPC['answer5'];
				}
				$insert['answer'] = $answer;
				$insert['items'] = $items;
				$insert['thumb'] = $_GPC['thumb'];
				$insert['isimg'] = $isimg;

				load()->func('file');
				if ($isimg == 1 && ($type == 2 || $type == 3)) {
					$img_items = array();
					$item_name = "img_items" . $type . "_";

					foreach ($answer_array as $key => $value) {
						$img_items[$key] = $_GPC[$item_name . $key];

						if (!empty($_GPC[$item_name . $key . '-old'])) {
							file_delete($_GPC[$item_name . $key . '-old']);
						}
					}
					$insert['img_items'] = serialize($img_items);
				}

				//print_r($insert);exit;

				if (!empty($_GPC['thumb-old'])) {
					file_delete($_GPC['thumb-old']);
				}
				//tpl_form_field_image(1,2);

				if (empty($id)) {
					pdo_insert('ewei_exam_question', $insert);

					//排序表
					if (!empty($paperid)) {
						$paper_question_data = array();
						$paper_question_data['displayorder'] = 0;
						$paper_question_data['paperid'] = $paperid;
						$paper_question_data['questionid'] = pdo_insertid();
						pdo_insert('ewei_exam_paper_question', $paper_question_data);
						$this->check_paper_full($paperid);
					}

				} else {
					pdo_update('ewei_exam_question', $insert, array('id' => $id));

					//排序表
//                    if (!empty($paperid)) {
//                        $paper_question_data['questionid'] = pdo_insertid();
//                        pdo_insert('exam_paper_question', $paper_question_data);
//                    } else {
//                        $order_id = pdo_fetch("select id from " . tablename('ewei_exam_paper_question') . " where questionid=:id limit 1", array(":id" => $id));
//                        if ($order_id) {
//                            pdo_update('exam_paper_question', $paper_question_data, array('questionid' => $id));
//                        } else {
//                            $paper_question_data['questionid'] = $id;
//                            pdo_insert('exam_paper_question', $paper_question_data);
//                        }
//                    }

				}

				if ($is_next) {
					$array = array();
					$array['op'] = 'edit';
					$array['poolid'] = $insert['poolid'];
					$array['paperid'] = $paperid;

					$url = $this->createWebUrl('question', $array);
				} else {
					if ($referer == 1) {
						session_start();
						$url = $_SESSION['last_url'];
					} else {
						$url = $this->createWebUrl('question');
					}
				}

				message("试题信息保存成功!", $url, "success");
			}

			if (!empty($id)) {
				//修改试题
				$item = pdo_fetch("select * from " . tablename('ewei_exam_question') . " where id=:id limit 1", array(":id" => $id));

				if (!empty($item)) {
					$item['items'] = unserialize($item['items']);
					$item['img_items'] = unserialize($item['img_items']);
					//$paperid = $item['paperid'];
					$pool_id = $item['poolid'];
				}
			} else {
				//添加试题
				//$paperid = $_GPC['paperid'];
				$pool_id = $_GPC['poolid'];
				$item['type'] = intval($_GPC['type_key']);
			}

			if (!empty($paperid)) {
				$paper = pdo_fetch("select id,title from " . tablename('ewei_exam_paper') . " where id=:id limit 1", array(':id' => $paperid));
				$paper_info = $this->getPaperInfo($paperid);

				$d_question = $this->getDefaultPaperQuestion($paper_info);
				$now_question_data = $d_question['data'];

				$submit_array = array();
				$pager_str = '该试卷包含 ';
				foreach ($paper_info['types'] as $k => $v) {
					if ($v['has'] == 1) {
						$pager_str .= $types_config[$k] . "(" .$now_question_data[$k]['num'] . "/". $v['num'] .")道 ";
						if ($now_question_data[$k]['num'] < $v['num']) {
							//正常可以添加
							$submit_array[$k]['status'] = 1;
						} else {
							//试卷此题型已满
							$submit_array[$k]['status'] = 2;
						}
					} else {
						//试卷不包含此题型
						$submit_array[$k]['status'] = 3;
					}
				}
				//$now_score = $d_question['score'];
				//$score = $paper_info['score'];
			}

			if (!empty($pool_id)) {
				$pool = pdo_fetch("select id,title from " . tablename('ewei_exam_pool') . " where id=:id limit 1", array(':id' => $pool_id));
			}



			include $this->template('question_form');
		} else if ($op == 'deleteFromPaper') {
			$id = intval($_GPC['id']);
			$paperid = intval($_GPC['paperid']);

			if (!empty($id) && !empty($paperid)) {
				pdo_delete("ewei_exam_paper_question", array("questionid" => $id, "paperid" => $paperid));
				$this->check_paper_full($paperid);
				//pdo_update("ewei_exam_paper_question",array("paperid" => 0),array("questionid" => $id, "paperid" => $paperid));
			}

			message("试题已经从该试卷删除!", referer(), "success");
		} else if ($op == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete("ewei_exam_question", array("id" => $id));
			pdo_delete("ewei_exam_paper_question", array("questionid" => $id));
			$this->check_paper_full_by_questionid($id);

			message("试题信息删除成功!", referer(), "success");
		} else if ($op == 'deleteall') {
			foreach ($_GPC['idArr'] as $k => $id) {
				$id = intval($id);
				pdo_delete("ewei_exam_question", array("id" => $id));
				pdo_delete("ewei_exam_paper_question", array("questionid" => $id));
				$this->check_paper_full_by_questionid($id);
			}
			$this->web_message('试题信息删除成功！', '', 0);
			exit();
		} else if ($op == 'query') {
			$kwd = trim($_GPC['keyword']);
			$type = intval($_GPC['type']);
			$poolid = intval($_GPC['poolid']);
			$sql = 'SELECT id,title FROM ' . tablename('ewei_exam_question') . ' WHERE `weid`=:weid';
			$params = array();
			$params[':weid'] = $_W['uniacid'];
			if (!empty($kwd)) {
				$sql .= " AND `title` LIKE :title";
				$params[':title'] = "%{$kwd}%";
			}
			if (!empty($type)) {
				$sql .= " and type=:type";
				$params[':type'] = $type;
			}
			if (!empty($poolid)) {
				$sql .= " and poolid=:poolid";
				$params[':poolid'] = $poolid;
			}
			$ds = pdo_fetchall($sql, $params);
			include $this->template('question_query');
		} else if ($op == 'checkaddquestion') {
			$array = array();
			$array['type'] = $_GPC['type'];
			$array['paperid'] = $_GPC['paperid'];
			$data = $this->checkAddQuestion($array);

			die(json_encode($data));
		} else if ($op == 'addquestion') {
			$array = array();
			$id = $_GPC['id'];
			$idArr = $_GPC['idArr'];
			if (empty($id) && empty($idArr)) {
				message('参数错误', '', 'ajax');
			}

			$array['paperid'] = intval($_GPC['paperid']);
			$array['type'] = intval($_GPC['type']);

			if (empty($array['paperid']) || empty($array['type'])) {
				message('参数错误', '', 'ajax');
			}

			$data = $this->checkAddQuestion($array);

			//print_r($data);exit;

			if ($data['result'] == 1) {
				//pdo_update("ewei_exam_question",array("paperid" => $array['paperid']),array("id" => $id));
				//pdo_update("ewei_exam_paper_question",array("paperid" => $array['paperid']),array("questionid" => $id));
				if (!empty($id)) {
					pdo_insert("ewei_exam_paper_question", array("paperid" => $array['paperid'], "questionid" => $id, "displayorder" => 0));
				}

				if (!empty($idArr)) {

					$item = pdo_fetch("select tid from " . tablename('ewei_exam_paper') . " where id=:id limit 1", array(":id" => $array['paperid']));
					$tid = $item['tid'];

					$type_item = pdo_fetch("select * from " . tablename('ewei_exam_paper_type') . " where id=:id limit 1", array(':id' => $tid));
					$types = unserialize($type_item['types']);


					$question_array = array();
					$question_array['id'] = $array['paperid'];
					$question_array['types'] = $array['type'];
					$d_question = $this->getDefaultPaperQuestion($question_array);
					$now_question_data = $d_question['data'];

					foreach ($types as $key => $value) {
						if ($value['has'] == 1) {
							$now_question_data[$key]['need'] = $value['num'] - $now_question_data[$key]['num'];
						} else {
							$now_question_data[$key]['need'] = 0;
						}
					}
					$num = $now_question_data[$array['type']]['need'];

					if ($num) {
						$i = 0;
						foreach ($idArr as $k => $arrid) {
							if ($arrid == 'on') {
								continue;
							}
							if($i >= $num) {
								break;
							}
							pdo_insert("ewei_exam_paper_question", array("paperid" => $array['paperid'], "questionid" => $arrid, "displayorder" => 0));
							$i++;
						}
					}
				}

				$this->check_paper_full($array['paperid']);
				if ($_W['isajax']) {
					$this->web_message('试题已经添加到试卷中!', referer(), 0);
				} else {
					message("试题已经添加到试卷中!", referer(), "success");
				}

			} else {
				if ($_W['isajax']) {
					$this->web_message($data['msg']);
				} else {
					message($data['msg'], '', "error");
				}
			}
		} else {
			$where = ' FROM ' . tablename('ewei_exam_question') . ' AS `q` LEFT JOIN ' . tablename('ewei_exam_pool') .
				' AS `p` ON `q`.`poolid` = `p`.`id` WHERE `q`.`weid` = :weid';
			$params = array(':weid' => $_W['uniacid']);

			if (!empty($_GPC['poolid'])) {
				$where .= ' AND q.poolid=:poolid';
				$params[':poolid'] = intval($_GPC['poolid']);
			}
			if (!empty($_GPC['type'])) {
				$where .= ' AND q.type=:type';
				$params[':type'] = intval($_GPC['type']);
			}
			if (!empty($_GPC['question'])) {
				$where .= ' AND q.question LIKE :question';
				$params[':question'] = "%{$_GPC['question']}%";
			}

			if ($_GPC['add_paper'] == 1 && !empty($_GPC['paperid'])) {
				$add_paper = 1;
				session_start();
				$url = $_SESSION['last_url'];
				$paperid = intval($_GPC['paperid']);
				$where .= ' AND q.id NOT IN(SELECT questionid FROM ' . tablename('ewei_exam_paper_question') . 'WHERE paperid =:paperid)';
				$params[':paperid'] = $paperid;
			} else {
				$add_paper = 0;
			}

			$sql = 'SELECT COUNT(q.id) ' . $where;
			$total = pdo_fetchcolumn($sql, $params);
			if ($total > 0) {
				$pindex = max(1, intval($_GPC['page']));
				$psize = 10;

				$sql = 'SELECT `q`.*, `p`.`title` AS `pooltitle` ' . $where . ' ORDER BY `q`.`id` DESC LIMIT ' .
						($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);

				foreach ($list as &$row) {
					$row['type_name'] = $this->_types_config[$row['type']];
					$row['percent'] = round($row['correctnum'] / (empty($row['fansnum']) ? 1 : $row['fansnum']), 3) * 100;
				}
				unset($row);

				$pager = pagination($total, $pindex, $psize);
			}

			// 导出数据
			if (!empty($_GPC['export'])) {
				/* 输入到CSV文件 */
				$html = "\xEF\xBB\xBF";

				/* 输出表头 */
				$filter = array(
					'question' => '试题',
					'pooltitle' => '所属题库',
					'type_name' => '类型',
					'level' => '难度',
					'fansnum' => '答题数',
					'percent' => '正确率',
				);

				foreach ($filter as $key => $value) {
					$html .= $value . "\t,";
				}
				$html .= "\n";

				foreach ($list as $key => $value) {
					foreach ($filter as $index => $title) {
						$html .= trim($value[$index]) . "\t, ";
					}
					$html .= "\n";
				}

				/* 输出CSV文件 */
				header("Content-type:text/csv");
				header("Content-Disposition:attachment; filename=全部数据.csv");
				echo $html;
				exit();
			}

			$sql = 'SELECT * FROM ' . tablename('ewei_exam_pool') . ' WHERE `weid` = :weid';
			$pools = pdo_fetchall($sql, array(':weid' => $_W['uniacid']));

			include $this->template('question');
		}
	}

	//检查试卷是否可以添加该类型考题
	public function checkAddQuestion($array)
	{
		global $_GPC, $_W;

		$paperid = $array['paperid'];
		$type = $array['type'];

		if (!empty($paperid)) {
			$paper = pdo_fetch("select id,title from " . tablename('ewei_exam_paper') . " where id=:id limit 1", array(':id' => $paperid));
			$paper_info = $this->getPaperInfo($paperid);

			$d_question = $this->getDefaultPaperQuestion($paper_info);
			$now_question_data = $d_question['data'];

			$types = $paper_info['types'][$type];
			$msg = '';

			if ($types['has'] == 1) {
				if ($now_question_data[$type]['num'] < $types['num']) {
					//正常可以添加
					$result = 1;
				} else {
					//试卷此题型已满
					$result = 2;
					$msg = "试卷该题型已满，不能再添加了！";
				}
			} else {
				//试卷不包含此题型
				$result = 3;
				$msg = "试卷不包含该题型，不能添加！";
			}
			$data = array();
			$data['result'] = $result;
			$data['msg'] = $msg;

			return $data;
		}
	}

	//检查试卷中是否已经存在该试题
	public function checkQuestion($paperid, $questionid)
	{
		global $_GPC, $_W;

		$params = array();
		$params[':paperid'] = $paperid;
		$params[':questionid'] = $questionid;

		$sql = "SELECT id FROM " . tablename('ewei_exam_paper_question') . " WHERE paperid = :paperid AND questionid = :questionid LIMIT 1";
		$item = pdo_fetchall($sql, $params);

		if ($item) {
			return 1;
		} else {
			return 0;
		}
	}

	//检查试卷中是否已经存在该试题
	public function addQuestion($paperid, $questionid, $check = 0)
	{
		global $_GPC, $_W;

		$insert = array();
		$insert['paperid'] = $paperid;
		$insert['questionid'] = $questionid;

		pdo_insert('ewei_exam_paper_question', $insert);
	}

	//检查试卷中是否已经存在该试题
	public function autoAddQuestion($paperid, &$list, $count, $num)
	{
//        if ($num == 0) {
//            return 1;
//        }

		$rand = rand(1, $count) - 1;
		$questionid = $list[$rand]['id'];
		$this->addQuestion($paperid, $questionid);
		return 1;

//        $check = $this->checkQuestion($paperid, $questionid);
//        if w($check) {
//            $num--;
//            $this->autoAddQuestion($paperid, $list, $count, $num);
//        } else {
//            $this->addQuestion($paperid, $questionid);
//            return 1;
//        }
	}

	//获取试卷中当前的考题和分数情况
	public function getDefaultPaperQuestion($array)
	{
		global $_GPC, $_W;

		$weid = $_W['uniacid'];
		$score_array = array();
		$score = 0;
		$params = array();
		$params[':paperid'] = $array['id'];

		foreach ($this->_types_config as $key => $value) {
			$params[':type'] = $key;
			$count_sql = "SELECT COUNT(q.id) FROM " . tablename('ewei_exam_question') . " as q";
			$count_sql .= " LEFT JOIN " . tablename('ewei_exam_paper_question') . " as pq ON q.id = pq.questionid";
			$count_sql .= " WHERE pq.paperid = :paperid AND q.type = :type";
			$total = pdo_fetchcolumn($count_sql, $params);
			$score_array[$key]['num'] = $total;
			$score_array[$key]['score'] = $total * $array['types'][$key]['one_score'];
			$score += $score_array[$key]['score'];
		}

		$data = array();
		$data['data'] = $score_array;
		$data['score'] = $score;

		return $data;
	}

	public function doWebPool()
	{
		global $_GPC, $_W;

		$op = $_GPC['op'];
		$weid = $_W['uniacid'];

		if ($op == 'edit') {
			//编辑
			$id = intval($_GPC['id']);
			if (checksubmit()) {
				$insert = array(
					'weid' => $weid,
					'title' => $_GPC['title'],
					'description' => $_GPC['description'],
				);
				if (empty($id)) {
					pdo_insert('ewei_exam_pool', $insert);
				} else {
					pdo_update('ewei_exam_pool', $insert, array('id' => $id));
				}
				message("题库信息保存成功!", $this->createWebUrl('pool'), "success");
			}
			$item = pdo_fetch("select * from " . tablename('ewei_exam_pool') . " where id=:id limit 1", array(":id" => $id));
			include $this->template('pool_form');
		} else if ($op == 'delete') {
			$id = intval($_GPC['id']);
			pdo_update("ewei_exam_question", array("poolid" => 0), array("poolid" => $id));
			pdo_delete("ewei_exam_pool", array("id" => $id));
			message("题库信息删除成功!", referer(), "success");

		} else if ($op == 'deleteall') {
			foreach ($_GPC['idArr'] as $k => $id) {
				$id = intval($id);
				pdo_update("ewei_exam_question", array("poolid" => 0), array("poolid" => $id));
				pdo_delete("ewei_exam_pool", array("id" => $id));
			}
			$this->web_message('题库信息删除成功！', '', 0);
			exit();
		} else if ($op == 'query') {
			$kwd = trim($_GPC['keyword']);

			$sql = 'SELECT id,title FROM ' . tablename('ewei_exam_pool') . ' WHERE `weid`=:weid';
			$params = array();
			$params[':weid'] = $_W['uniacid'];
			if (!empty($kwd)) {
				$sql .= " AND `title` LIKE :title";
				$params[':title'] = "%{$kwd}%";
			}
			$ds = pdo_fetchall($sql, $params);
			include $this->template('pool_query');

		} else if ($op == 'addquestion') {
			//自动生成试题
			$data = array();
			$idArr = $_GPC['idArr'];
			if(empty($id) && empty($idArr)){
				$data['errno'] = '参数错误';
				die(json_encode($data));
			}

			$paperid = intval($_GPC['paperid']);
			//$array = array();
			//$array['type'] = intval($_GPC['type']);

			if (empty($paperid)) {
				$data['errno'] = '参数错误';
				die(json_encode($data));
			}

			//print_r($idArr);exit;

			$item = pdo_fetch("select tid from " . tablename('ewei_exam_paper') . " where id=:id limit 1", array(":id" => $paperid));
			$tid = $item['tid'];

			$type_item = pdo_fetch("select * from " . tablename('ewei_exam_paper_type') . " where id=:id limit 1", array(':id' => $tid));
			$types = unserialize($type_item['types']);

			$question_array = array();
			$question_array['id'] = $paperid;
			$question_array['types'] = $types;
			$d_question = $this->getDefaultPaperQuestion($question_array);
			$now_question_data = $d_question['data'];

			foreach ($types as $key => $value) {
				if ($value['has'] == 1) {
					$now_question_data[$key]['need'] = $value['num'] - $now_question_data[$key]['num'];
				} else {
					$now_question_data[$key]['need'] = 0;
				}
			}

			$id_count = 0;
			$in = "(";
			foreach ($idArr as $k => $arrid) {
				if ($arrid != 'on') {
					$id_count++;
					$in .= $arrid .",";
				}
			}
			$in = trim($in, ",");
			$in .= ")";

			if ($id_count == 0) {
				$data['errno'] = '请选择要从中填充试题的题库!';
				die(json_encode($data));
			}

			$params = array();
			$params[':weid'] = $this->_weid;

			$sql = " SELECT id FROM " . tablename('ewei_exam_question');
			$sql .= " WHERE weid = :weid";
			$sql .= " AND poolid IN " . $in;

			foreach ($now_question_data as $key => $value) {
				$need = $value['need'];

				if ($need > 0) {
					$limit_num = $need + 300;

					$question_sql = $sql;
					$question_sql .= " AND type = " . $key;
					$question_sql .= " AND id NOT IN (SELECT questionid FROM " . tablename('ewei_exam_paper_question') . " WHERE paperid = " . $paperid . ")";
					$question_sql .= " limit 0," . $limit_num;

					$list = pdo_fetchall($question_sql, $params);
					$count = count($list);

					if ($count == 0) {
						continue;
					}

					if ($need < $count) {
						for ($i = 0; $i < $need; $i++) {
							$this->autoAddQuestion($paperid, $list, $count, $count * 2);
						}

					} else {
						foreach ($list as $k => $v) {
							$this->addQuestion($paperid, $v['id']);
//                            $check = $this->checkQuestion($paperid, $v['id']);
//                            if ($check) {
//                                continue;
//                            } else {
//                                $this->addQuestion($paperid, $v['id']);
//                            }
						}
					}
				}
			}

			$this->check_paper_full($paperid);

			session_start();
			$url = $_SESSION['last_url'];

			$data['errno'] = 0;
			$data['url'] = $url;
			die(json_encode($data));
			// message("试题添加成功!", $url, "success");
		} else {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$sql = "";
			$params = array();
			if (!empty($_GPC['title'])) {
				$sql .= ' AND `title` LIKE :title';
				$params[':title'] = "%{$_GPC['title']}%";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('ewei_exam_pool') . " WHERE weid = '{$_W['uniacid']}'  $sql ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			foreach($list as &$r){
				//计算试题数
				$r['nums'] = pdo_fetchcolumn("select count(*) from ".tablename('ewei_exam_question')." WHERE weid = '{$_W['uniacid']}' and poolid=".$r['id']);
			}
			unset($r);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_exam_pool') . " WHERE weid = '{$_W['uniacid']}' $sql", $params);
			$pager = pagination($total, $pindex, $psize);

			if ($_GPC['add_paper'] == 1 && !empty($_GPC['paperid'])) {
				$add_paper = 1;
				session_start();
				$url = $_SESSION['last_url'];
				$paperid = intval($_GPC['paperid']);

			} else {
				$add_paper = 0;
			}

			//print_r($add_paper);exit;

			include $this->template('pool');
		}
	}

	//根据试题id检查试卷试题是否完整
	function check_paper_full_by_questionid($questionid)
	{
		if (!empty($questionid)) {
			$sql = "SELECT paperid FROM " . tablename('ewei_exam_paper_question') . " WHERE questionid =" . $questionid;
			$list = pdo_fetchall($sql);
			foreach($list as $k => $v) {
				if (!empty($v['paperid'])) {
					$this->check_paper_full($v['paperid']);
				}
			}
		}
	}

	//检查试卷试题是否完整
	function check_paper_full($paperid)
	{
		if (!empty($paperid)) {
			$paper_info = $this->getPaperInfo($paperid);
			$d_question = $this->getDefaultPaperQuestion($paper_info);
			$now_question_data = $d_question['data'];
			$flag = 1;
			foreach ($paper_info['types'] as $k => $v) {
				if ($v['has'] == 1) {
					if ($now_question_data[$k]['num'] != $v['num']) {
						$flag = 0;
						break;
					}
				}
			}
			pdo_update('ewei_exam_paper', array("isfull" => $flag), array('id' => $paperid));
		}
	}

	public function doWebPaper_member()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$paperid = intval($_GPC['id']);
		$username = $_GPC['username'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$params = array();
		$params[':weid'] = $weid;
		$params[':paperid'] = $paperid;
		$select_sql = "SELECT r.*, m.username, m.mobile, p.title";
		$count_sql = "SELECT COUNT(r.id)";
		$sql = " FROM " . tablename('ewei_exam_paper_member_record') . " AS r";
		$sql .= " LEFT JOIN  " . tablename('ewei_exam_member') . " as m ON r.memberid = m.id";
		$sql .= " LEFT JOIN  " . tablename('ewei_exam_paper') . " as p ON p.id = r.paperid";
		$sql .= " WHERE r.paperid = :paperid AND r.weid = :weid AND m.weid = :weid AND did = 1";
		if (!empty($username)) {
			$sql .= ' AND m.username LIKE :username';
			$params[':username'] = "%{$username}%";
		}
		$select_sql .= $sql;
		$count_sql .= $sql;
		$select_sql .= " ORDER BY r.score DESC, r.id DESC, r.times";
		if($pindex > 0) {
			// 需要分页
			$start = ($pindex - 1) * $psize;
			$select_sql .= " LIMIT {$start},{$psize}";
		}
		$list = pdo_fetchall($select_sql, $params);
		$sql = 'SELECT `memberid`, AVG(`times`) AS `avgtime`, AVG(`score`) AS `avgscore` FROM ' . tablename('ewei_exam_paper_member_record') .
			" WHERE `weid` = :weid GROUP BY `memberid`";
		$avgs = pdo_fetchall($sql, array(':weid' => $_W['uniacid']), 'memberid');
		foreach ($list as &$member) {
			$member['avtimes'] = round($avgs[$member['memberid']]['avgtime'], 2);
			$member['avscore'] = round($avgs[$member['memberid']]['avgscore'], 2);
		}
		$total = pdo_fetchcolumn($count_sql, $params);
		$pager = pagination($total, $pindex, $psize);
		include $this->template('paper_member');
	}

	public function doWebPaper()
	{
		global $_GPC, $_W;

		$op = $_GPC['op'];
		$weid = $_W['uniacid'];
		$types_config = $this->_types_config;
		if ($op == 'edit') {
			//编辑
			$id = intval($_GPC['id']);
			$tid = intval($_GPC['tid']);
			$year_array = array();
			for ($i = date("Y"); $i >= 2000; $i--) {
				$year_array[] = $i;
			}

			if (checksubmit()) {
				$insert = array(
					'weid' => $weid,
					'displayorder' => $_GPC['displayorder'],
					'title' => $_GPC['title'],
					'level' => $_GPC['level'],
					'year' => $_GPC['year'],
					'tid' => $_GPC['tid'],
					'description' => $_GPC['description'],
					'status' => $_GPC['status'],
					'pcate' => $_GPC['pcate'],
				);

				if (empty($id)) {
					pdo_insert('ewei_exam_paper', $insert);
				} else {
					pdo_update('ewei_exam_paper', $insert, array('id' => $id));
				}
				message("试卷信息保存成功!", $this->createWebUrl('paper'), "success");
			}
			if (!empty($id)) {
				$item = pdo_fetch("select * from " . tablename('ewei_exam_paper') . " where id=:id limit 1", array(":id" => $id));
				$tid = $item['tid'];
			}

			if(!empty($item)){
				$paper_category = pdo_fetch("select id, cname as title from ".tablename('ewei_exam_paper_category')." where id=:id limit 1",array(':id'=>$item['pcate']));
			}

//            if(!empty($item)){
//                $paper = pdo_fetch("select id,title from ".tablename('ewei_exam_paper')." where id=:id limit 1",array(':id'=>$item['paperid']));
//            }

			$type_item = pdo_fetch("select * from " . tablename('ewei_exam_paper_type') . " where id=:id limit 1", array(':id' => $tid));
			$types = unserialize($type_item['types']);

			if (!empty($id)) {
				$question_array = array();
				$question_array['id'] = $id;
				$question_array['types'] = $types;
				$d_question = $this->getDefaultPaperQuestion($question_array);
				$now_question_data = $d_question['data'];
			}
			//print_r($question_item);exit;
			include $this->template('paper_form');

		} else if ($op == 'editquestion') {
			session_start();
			$_SESSION['last_url'] = $_SERVER['REQUEST_URI'];

			//编辑
			$id = intval($_GPC['id']);
			$tid = intval($_GPC['tid']);

			if (checksubmit()) {
				//更改排序
				foreach ($_GPC['displayorder'] as $k => $v) {
					if (empty($v)) {
						$v = 0;
					}
					pdo_update('ewei_exam_paper_question', array('displayorder' => $v), array('paperid' => $id, 'questionid' => $k));
				}
			}

			if (!empty($id)) {
				$item = pdo_fetch("select * from " . tablename('ewei_exam_paper') . " where id=:id limit 1", array(":id" => $id));
				$tid = $item['tid'];
			}

			if(!empty($item)){
				$paper_category = pdo_fetch("select id, cname as title from ".tablename('ewei_exam_paper_category')." where id=:id limit 1",array(':id'=>$item['pcate']));
			}

			$type_item = pdo_fetch("select * from " . tablename('ewei_exam_paper_type') . " where id=:id limit 1", array(':id' => $tid));
			$types = unserialize($type_item['types']);

			if (!empty($id)) {
				$question_array = array();
				$question_array['id'] = $id;
				$question_array['types'] = $types;
				$d_question = $this->getDefaultPaperQuestion($question_array);
				$now_question_data = $d_question['data'];
			}

			$question_item = get_paper_question_list($id);
			//print_r($question_item);exit;

			include $this->template('paper_question_form');
		}else if ($op == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete("ewei_exam_paper_question", array("paperid" => $id));
			//pdo_delete("ewei_exam_paper_member", array("questionid" => $id));
			//pdo_delete("ewei_exam_paper_member_data", array("questionid" => $id));
			pdo_delete("ewei_exam_paper", array("id" => $id));
			message("试题信息删除成功!", referer(), "success");

		} else if ($op == 'deleteall') {
			foreach ($_GPC['idArr'] as $k => $id) {
				$id = intval($id);
				pdo_delete("ewei_exam_paper_question", array("paperid" => $id));
				//pdo_delete("ewei_exam_paper_member", array("questionid" => $id));
				//pdo_delete("ewei_exam_paper_member_data", array("questionid" => $id));
				pdo_delete("ewei_exam_paper", array("id" => $id));
			}
			$this->web_message('试题信息删除成功！', '', 0);
			exit();
		} else if ($op == 'showall') {
			if ($_GPC['show_name'] == 'showall') {
				$show_status = 1;
			} else {
				$show_status = 0;
			}

			foreach ($_GPC['idArr'] as $k => $id) {
				$id = intval($id);

				if (!empty($id)) {
					pdo_update('ewei_exam_paper', array('status' => $show_status), array('id' => $id));
				}
			}
			//message('操作成功！', '', 0);
			exit();
		} else if ($op == 'status') {

			$id = intval($_GPC['id']);
			if (empty($id)) {
				message('抱歉，传递的参数错误！', '', 'error');
			}
			$temp = pdo_update('ewei_exam_paper', array('status' => $_GPC['status']), array('id' => $id));
			if ($temp == false) {
				message('抱歉，刚才操作数据失败！', '', 'error');
			} else {
				message('状态设置成功！', referer(), 'success');
			}
		} else if ($op == 'query') {
			$kwd = trim($_GPC['keyword']);

			$sql = "SELECT p.id, p.title, p.description,t.types, t.score FROM " . tablename('ewei_exam_paper') . " AS p";
			$sql .= " LEFT JOIN " . tablename('ewei_exam_paper_type') . " AS t on p.tid = t.id";
			$sql .= " WHERE p.weid = :weid";
			$params = array();
			$params[':weid'] = $_W['uniacid'];
			if (!empty($kwd)) {
				$sql .= " AND p.title LIKE :title";
				$params[':title'] = "%{$kwd}%";
			}
			$ds = pdo_fetchall($sql, $params);
			foreach ($ds as $key => $value) {
				$value['types'] = unserialize($value['types']);
				$d_question = $this->getDefaultPaperQuestion($value);
				$ds[$key]['now_score'] = $d_question['score'];
				$now_question_data = $d_question['data'];

				$pager_str = '该试卷包含 ';
				foreach ($value['types'] as $k => $v) {
					if ($v['has'] == 1) {
						$pager_str .= $types_config[$k] . "(" .$now_question_data[$k]['num'] . "/". $v['num'] .")道 ";
					}
				}
				$ds[$key]['pager_str'] = $pager_str;
			}

			include $this->template('paper_query');

		} else {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$sql = "";
			$params = array();
			if (!empty($_GPC['title'])) {
				$sql .= ' AND p.title LIKE :keywords';
				$params[':keywords'] = "%{$_GPC['title']}%";
			}
			if (!empty($_GPC['level'])) {
				$sql .= ' AND p.level=:level';
				$params[':level'] = intva($_GPC['level']);
			}

			if (!empty($_GPC['pcate'])) {
				$pcate = intval($_GPC['pcate']);
				//判断是否为一级分类
				$cate_sql = "SELECT id FROM " .tablename('ewei_exam_paper_category');
				$cate_sql .=  " WHERE parentid = " . $pcate;
				$cate_sql .=  " AND weid = " . $weid;
				//$cate_sql .= " AND status = 1";

				$item = pdo_fetchall($cate_sql);
				$cate_num = count($item);

				if ($cate_num == 0) {
					$sql .= " AND p.pcate = :pcate";
					$params[':pcate'] = $pcate;
				} else if ($cate_num > 0) {
					$item[$cate_num]['id'] = $pcate;
					$cate_str = '';
					foreach ($item as $k => $v) {
						$cate_str .= $v['id'] . ",";
					}
					$cate_str = trim($cate_str, ",");
					$sql .= " AND p.pcate in (" . $cate_str . ")";
				}
			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;

			$select_sql = "SELECT p.*, t.score, t.times FROM " . tablename('ewei_exam_paper') . " as p";
			$select_sql .= " LEFT JOIN " . tablename('ewei_exam_paper_type') . " as t on p.tid = t.id";
			$select_sql .= " WHERE p.weid = '{$_W['uniacid']}'  $sql ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($select_sql, $params);

			$count_sql = "SELECT COUNT(p.id) FROM " . tablename('ewei_exam_paper') . " as p";
			$count_sql .= " LEFT JOIN " . tablename('ewei_exam_paper_type') . " as t on p.tid = t.id";
			$count_sql .= " WHERE p.weid = '{$_W['uniacid']}'" . $sql;

			$category = pdo_fetchall("SELECT * FROM " . tablename('ewei_exam_paper_category') . " WHERE weid = '{$_W['uniacid']}' AND status = 1 ORDER BY parentid ASC, displayorder DESC");
			//print_r($category);exit;

			$total = pdo_fetchcolumn($count_sql, $params);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('paper');
		}
	}


	public function doWebPaper_type()
	{
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$types_config = $this->_types_config;
		if ($operation == 'display') {
			$types = pdo_fetchall("SELECT * FROM " . tablename('ewei_exam_paper_type') . " WHERE weid = '{$_W['uniacid']}' ORDER BY id desc");
			foreach ($types as $key => $value) {
				$types[$key]['types'] = unserialize($types[$key]['types']);
			}

			//print_r($types);exit;

			include $this->template('paper_type');

		} elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {

				//print_r($_GPC);exit;

				if (empty($_GPC['has'])) {
					message('抱歉，请至少选择一种类型！');
				} else {
					$has = $_GPC['has'];
				}

				$num = $_GPC['num'];
				$one_score = $_GPC['one_score'];

				$array = array();
				foreach ($types_config as $key => $value) {
					if (array_key_exists($key, $has)) {
						$array[$key]['has'] = 1;
						$array[$key]['num'] = $num[$key];
						$array[$key]['one_score'] = $one_score[$key];
						$array[$key]['sum_score'] = $num[$key] * $one_score[$key];
					} else {
						$array[$key]['has'] = 0;
						$array[$key]['num'] = 0;
						$array[$key]['one_score'] = 0;
						$array[$key]['sum_score'] = 0;
					}
				}

				$data = array(
					'weid' => $_W['uniacid'],
					'title' => $_GPC['title'],
					'times' => $_GPC['times'],
					'score' => $_GPC['score'],
					'types' => serialize($array),
				);

				//print_r($data);exit;

				if (!empty($id)) {
					pdo_update('ewei_exam_paper_type', $data, array('id' => $id));
				} else {
					pdo_insert('ewei_exam_paper_type', $data);
					$id = pdo_insertid();
				}
				message('更新类型成功！', $this->createWebUrl('paper_type', array('op' => 'display')), 'success');
			}
			$item = pdo_fetch("select * from " . tablename('ewei_exam_paper_type') . " where id=:id limit 1", array(":id" => $id));

			$arr = array();
			if (!empty($item)) {
				$arr = unserialize($item['types']);
			}
			if (count($arr) <= 0) {
				foreach ($types_config as $key => $value) {
					$arr[$key]['has'] = 0;
					$arr[$key]['num'] = 0;
					$arr[$key]['one_score'] = 0;
					$arr[$key]['sum_score'] = 0;

//                    $arr[] =array(
//                        "key"=>$key,
//                        "value"=>$value,
//                        "num"=>0,
//                        "score"=>0
//                    );
				}
			}

			include $this->template('paper_type');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);

			if (!empty($id)) {
				$item = pdo_fetch("SELECT id FROM " . tablename('ewei_exam_paper') . " WHERE tid = :tid LIMIT 1", array(':tid' => $id));
				if (!empty($item)) {
					message('抱歉，请先删除该类型下的试卷,再删除该类型！', '', 'error');
				}
			} else{
				message('抱歉，参数错误！', '', 'error');
			}

			pdo_delete('ewei_exam_paper_type', array('id' => $id));
			message('类型删除成功！', $this->createWebUrl('paper_type', array('op' => 'display')), 'success');
		}
	}

	public function doWebMember() {
		global $_GPC, $_W;
		$op = $_GPC['op'];
		if ($op == 'edit') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('ewei_exam_member') . " WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('抱歉，用户不存在或是已经删除！', '', 'error');
				}
			}

			if (checksubmit('submit')) {
				if (empty($_GPC['username'])) {
					message('抱歉，姓名不能为空！', '', 'error');
				}

//                if (empty($_GPC['userid'])) {
//                    message('抱歉，用户名不能为空！', '', 'error');
//                }

				$data = array(
					'weid' => $_W['uniacid'],
					'username' => $_GPC['username'],
					'mobile' => $_GPC['mobile'],
					'email'=>$_GPC['email'],
					'status'=>$_GPC['status'],
				);

				if (!empty($_GPC['userid'])) {
					$data['userid'] = $_GPC['userid'];
				}

				$check_flag = check_userid($data, $id);
				if ($check_flag) {
					message('抱歉，用户名已经存在！', '', 'error');
				}
				//print_r($check_flag);exit;

				if (empty($id)) {
					$data['createtime'] = time();
					pdo_insert('ewei_exam_member', $data);
				} else {
					unset($data['weid']);
					pdo_update('ewei_exam_member', $data, array('id' => $id));
				}
				message('用户信息更新成功！', $this->createWebUrl('member'), 'success');
			}
			include $this->template('member_form');

		} else if ($op == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('ewei_exam_member', array('id' => $id));

			message('删除成功！', referer(), 'success');

		}  else if ($op == 'deleteall') {
			foreach ($_GPC['idArr'] as $k => $id) {

				$id = intval($id);
				pdo_delete('ewei_exam_member', array('id' => $id));

			}
			exit();
			//message('规则操作成功！', '', 0);
			//exit();
		} else if ($op == 'showall') {
			if ($_GPC['show_name'] == 'showall') {
				$show_status = 1;
			} else {
				$show_status = 0;
			}

			foreach ($_GPC['idArr'] as $k => $id) {
				$id = intval($id);

				if (!empty($id)) {
					pdo_update('ewei_exam_member', array('status' => $show_status), array('id' => $id));
				}
			}
			//message('操作成功！', '', 0);
			//exit();
		} else if ($op == 'status') {

			$id = intval($_GPC['id']);
			if (empty($id)) {
				message('抱歉，传递的参数错误！', '', 'error');
			}
			$temp = pdo_update('ewei_exam_member', array('status' => $_GPC['status']), array('id' => $id));

			if ($temp == false) {
				message('抱歉，刚才操作数据失败！', '', 'error');
			} else {
				message('状态设置成功！', referer(), 'success');
			}
		} else {
			$where = ' WHERE `weid` = :weid';
			$params = array(':weid' => $_W['uniacid']);

			if (!empty($_GPC['username'])) {
				$where .= ' AND `username` LIKE :username';
				$params[':username'] = "%{$_GPC['username']}%";
			}
			if (!empty($_GPC['userid'])) {
				$where .= ' AND `userid` LIKE :userid';
				$params[':userid'] = "%{$_GPC['userid']}%";
			}
			if (!empty($_GPC['mobile'])) {
				$where .= ' AND `mobile` LIKE :mobile';
				$params[':mobile'] = "%{$_GPC['mobile']}%";
			}

			$sql = 'SELECT COUNT(*) FROM ' . tablename('ewei_exam_member') . $where;
			$total = pdo_fetchcolumn($sql, $params);

			if ($total > 0) {
				$pindex = max(1, intval($_GPC['page']));
				$psize = 15;

				$sql = 'SELECT * FROM ' . tablename('ewei_exam_member') . $where . ' ORDER BY `id` DESC LIMIT ' .
						($pindex - 1) * $psize . ',' . $psize;
				$list = pdo_fetchall($sql, $params);

				$pager = pagination($total, $pindex, $psize);
			}

			include $this->template('member');
		}
	}


	public function doWebUploadExcel()
	{
		global $_GPC, $_W;


		if($_GPC['leadExcel'] == "true")
		{
			$filename = $_FILES['inputExcel']['name'];
			$tmp_name = $_FILES['inputExcel']['tmp_name'];

			$msg = uploadFile($filename, $tmp_name, $_GPC);

			//print_r($msg);exit;
			if ($msg == 1) {
				message('导入成功！', referer(), 'success');
			} else {
				message($msg, '', 'error');
			}
		}
	}

	public function doWebUpload_question()
	{
		global $_GPC, $_W;
		$poollist = pdo_fetchall("SELECT id, title from " . tablename('ewei_exam_pool') ." WHERE weid = :weid", array(':weid' => $this->_weid));

		include $this->template('upload_question');
	}

	public function doWebSysset()
	{
		global $_GPC, $_W;

		$id = intval($_GPC['id']);

		if (checksubmit('submit')) {
			$data = array();
			$data['weid'] = $this->_weid;
			$data['about'] = htmlspecialchars_decode($_GPC['about']);
			$data['classopen'] =intval($_GPC['classopen']);
			$data['login_flag'] =intval($_GPC['login_flag']);

			if (!empty($id)) {
				pdo_update("ewei_exam_sysset", $data, array("id" => $id));
			} else {
				pdo_insert("ewei_exam_sysset", $data);
			}
			message("保存设置成功!", referer(), "success");
		}

		$item = pdo_fetch("select * from " . tablename('ewei_exam_sysset') . " where weid=:weid limit 1", array(":weid" => $_W['uniacid']));
		include $this->template('sysset');
	}


	public function web_message($error,$url='',$errno=-1){
		$data=array();
		$data['errno']=$errno;
		if(!empty($url)){
			$data['url']=$url;
		}
		$data['error']=$error;
		echo json_encode($data);
		exit;
	}

	private function _message($err, $msg = '', $ispost = false)
	{
		if (!empty($err)) {
			if ($ispost) {
				die(json_encode(array("result" => 0, "error" => $err)));
			} else {
				message($err, '', 'error');
			}
		}
		if (!empty($msg)) {

			die(json_encode(array("result" => 1, "msg" => $msg)));
		}
	}

	//登录页
	public function doMobilelogin()
	{
		global $_GPC, $_W;;
		if (checksubmit()) {
			$member = array();
			$username = trim($_GPC['username']);
			$userid = $_GPC['userid'];
			if (empty($username)) {
				die(json_encode(array("result" => 2, "error" => "请输入姓名")));
			}
			if (empty($userid)) {
				die(json_encode(array("result" => 2, "error" => "请输入用户名")));
			}
			$member['username'] = $username;
			$member['userid'] = $userid;
			$params = array();
			$params[':username'] = $member['username'];
			$params[':userid'] = $member['userid'];
			$params[':weid'] = $this->_weid;
			$sql = "SELECT * FROM " . tablename('ewei_exam_member') . " WHERE weid = :weid AND username = :username AND userid = :userid LIMIT 1";
			$item = pdo_fetch($sql, $params);
			if ($item['id']) {
				if ($item['status'] == 0) {
					die(json_encode(array("result" => 2, "error" => "抱歉，你的姓名和用户名被禁用，无法使用")));
				}
				$data = array();
				$data['realname'] = $username;
				fans_update($this->_from_user, $data);
				pdo_update('ewei_exam_member', array('from_user' => $this->_from_user), array('id' => $item['id']));
				$url = $this->createMobileUrl('index');
				exam_set_userinfo(1, $item);
				die(json_encode(array("result" => 1, "url" => $url)));
			} else {
				die(json_encode(array("result" => 2, "error" => "抱歉，你输入的姓名和用户名不在本系统中，无法使用")));
			}
		} else {
			include $this->template('login');
		}
	}

	public function check_member() {
		global $_W;
		if ($this->_member) {
			return true;
		}

		// 开启登录
		if ($this->_set_info['login_flag'] > 0) {
			header('Location:' . $this->createMobileUrl('login'));
		}

		$sql = 'SELECT * FROM ' . tablename('ewei_exam_member') . ' WHERE `weid` = :weid AND `from_user` = :from_user';
		$params = array(':weid' => $this->_weid, ':from_user' => $this->_from_user);
		$member = pdo_fetch($sql, $params);

		if ($member) {
			if ($member['status'] < 1) {
				message('帐号被禁用，请联系管理员', '', 'error');
			}
		} else {
			if (empty($_W['fans']['openid'])) {
				message('请先关注公众号再来参加考试吧！');
			}

			$member = array(
				'weid' => $this->_weid,
				'from_user' => $this->_from_user,
				'createtime' => TIMESTAMP,
				'status' => 1
			);
			pdo_insert('ewei_exam_member', $member);
			$member['id'] = pdo_insertid();
		}

		exam_set_userinfo(0, $member);
	}


	//获取酒店列表
	public function doMobilesortlist()
	{
		global $_GPC, $_W;

		$ac = $_GPC['ac'];
		if ($ac == "getDate") {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;

			$data = array();
			$data['result'] = 1;

			$params = array();
			$params[':weid'] = $this->_weid;

			$sql = "SELECT distinct(memberid)";
			$sql .= " FROM " .tablename('ewei_exam_paper_member_record');
			$sql .= " WHERE 1 = 1";
			$sql .= " AND weid = :weid";
			//$sql .= " AND did = 1";

			$member_list = pdo_fetchall($sql, $params);

			//print_r($member_list);exit;

			foreach ($member_list as $key => $value) {
				$user_info = get_user_info($value['memberid']);
				$member_list[$key]['username'] = $user_info['username'];
				$member_list[$key]['userid'] = $user_info['userid'];

				$member_list[$key]['total'] = get_user_question_count($value['memberid'], 2);
				$member_list[$key]['right'] = get_user_question_count($value['memberid'], 1);
				$member_list[$key]['rate'] = round(($member_list[$key]['right'] / $member_list[$key]['total']) * 100, 2);
				//$member_list[$key]['rate'] = round((3 / 7) * 100, 2);
			}

			$member_list = array_sort($member_list, 'rate', 1);
			$member_list = array_values($member_list);

			$total = count($member_list);

			//最多显示10页
			if ($pindex > 10) {
				$data['total'] = $total;
				$data['isshow'] = 0;
				die(json_encode($data));
			}


			if ($total <= $psize) {
				$list = $member_list;
			} else {
				// 需要分页
				if($pindex > 0) {
					$list_array = array_chunk($member_list, $psize);
					$list = $list_array[($pindex-1)];
				} else {
					$list = $member_list;
				}
			}

			$sort_num = ($pindex - 1) * $psize;
			//$sort_num += 100;

			$page_array = get_page_array($total, $pindex, $psize);

			ob_start();
			include $this->template('sort_crumb');
			$data['code'] = ob_get_contents();
			ob_clean();

			$data['total'] = $total;
			$data['isshow'] = $page_array['isshow'];

			if ($page_array['isshow'] == 1) {
				$data['nindex'] = $page_array['nindex'];
			}

			die(json_encode($data));
			//print_r($member_list);exit;
		}

		include $this->template('sortlist');
	}


	//获取课程列表
	public function doMobileCourselist()
	{
		global $_GPC, $_W;

		$this->check_member();
		$weid = $this->_weid;

		$params = array();
		$params[':weid'] = $weid;

		$ac = $_GPC['ac'];
		if ($ac == "getDate") {
			//$ccate = max(1, intval($_GPC['ccate']));
			$ccate = intval($_GPC['ccate']);

			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;

			$sql = "SELECT p.id, p.title, p.displayorder";
			$count_sql = "SELECT COUNT(p.id)";

			$where = '';
			$where .= " FROM " .tablename('ewei_exam_course') ." AS p";
			if (!empty($pcate)){
				$where .= " LEFT JOIN " .tablename('ewei_exam_course_category') . " AS c ON p.pcate = c.id";
			}
			$where .= " WHERE 1 = 1";
			$where .= " AND p.weid = :weid";

			$where .= " AND p.status = 1";
			if (!empty($ccate)){
				$where .= " AND c.weid = :weid";

				//判断是否为一级分类
				$cate_sql = "SELECT id FROM " .tablename('ewei_exam_course_category');
				$cate_sql .=  " WHERE parentid = " . $ccate;
				$cate_sql .=  " AND weid = " . $weid;

				$item = pdo_fetchall($cate_sql);
				$cate_num = count($item);

				if ($cate_num == 0) {
					$where .= " AND c.id = :pcate";
					$params[':ccate'] = $ccate;
				} else if ($cate_num > 0) {
					$item[$cate_num]['id'] = $ccate;
					$cate_str = '';
					foreach ($item as $k => $v) {
						$cate_str .= $v['id'] . ",";
					}
					$cate_str = trim($cate_str, ",");
					$where .= " AND c.id in (" . $cate_str . ")";
				}
			}

			$sql .= $where;
			$sql .= " ORDER BY p.displayorder DESC";
			if($pindex > 0) {
				// 需要分页
				$start = ($pindex - 1) * $psize;
				$sql .= " LIMIT {$start},{$psize}";
			}

			$count_sql .= $where;
			$list = pdo_fetchall($sql, $params);
			$total = pdo_fetchcolumn($count_sql, $params);

			$page_array = get_page_array($total, $pindex, $psize);

			//print_r($list);exit;

			$data = array();
			$data['result'] = 1;

			ob_start();
			include $this->template('course_crumb');
			$data['code'] = ob_get_contents();
			ob_clean();

			$data['total'] = $total;
			$data['isshow'] = $page_array['isshow'];
			if ($page_array['isshow'] == 1) {
				$data['nindex'] = $page_array['nindex'];
			}
			die(json_encode($data));
		} else {

			$year_array = array();
			for ($i = date("Y"); $i >= 2000; $i--) {
				$year_array[] = $i;
			}

//            $sql = "SELECT id, cname, parentid FROM " .tablename('ewei_exam_paper_category');
//            $sql .= " WHERE weid = :weid AND status = 1";
//            $sql .= " ORDER BY displayorder DESC,parentid";
//            $cate_list = pdo_fetchall($sql, $params);

			//print_r($cate_list);exit;

			include $this->template('courselist');
		}
	}


	//获取课程列表
	public function doMobileCourse()
	{
		global $_GPC, $_W;
		$this->check_member();
		$id = intval($_GPC['id']);
		if (empty($id)) {
			exit;
		}
		$weid = $_W['uniacid'];
		$member_info = $this->getMemberInfo();
		if (!empty($id)) {
			$item = pdo_fetch("select * from " . tablename('ewei_exam_course') . " where id=:id AND status = 1 limit 1", array(":id" => $id));
		}
		$is_reserve = 0;
		if ($item['ctype']) {
			if ($item['fansnum'] < $item['ctotal']) {
				$is_reserve = 1;
			}
		} else {
			$time = time();
			if ($time >= $item['starttime'] && $time <= $item['endtime']) {
				$is_reserve = 1;
			}
		}
		// 查看当前用户是否已经预约过该课程
		$params = array('courseid' => $id);
		$sql = 'SELECT `id` FROM ' . tablename('ewei_exam_course_reserve') . " WHERE `weid` = :weid AND `courseid` = :courseid AND `memberid` = :memberid";
		$params[':weid'] = $weid;
		$params[':memberid'] = $member_info['id'];
		$reserved = pdo_fetchcolumn($sql, $params);
		if (checksubmit()) {
			if (!empty($reserved)) {
				die(json_encode(array("result" => 2, "error" => "抱歉，该课程您已经预约过了！")));
			}
			$username = trim($_GPC['username']);
			$mobile = trim($_GPC['mobile']);
			$email = trim($_GPC['email']);
			$data = array(
				'realname' => $username,
				'mobile' => $mobile,
			);
			//更新用户信息
			$array = array();
			$array['username'] = $username;
			$array['mobile'] = $mobile;
			$array['email'] = $email;

			$params = array();
			$params['from_user'] = $this->_from_user;
			$params['weid'] = $weid;
			pdo_update('ewei_exam_member', $array, $params);
			//插入学员考试记录
			$data = array();
			$data['weid'] = $weid;
			$data['ordersn'] = date('md') . sprintf("%04d", $_W['fans']['id']) . random(4, 1);
			$data['courseid'] = $id;
			$data['memberid'] = $member_info['id'];
			$data['username'] = $username;
			$data['mobile'] = $mobile;
			$data['email'] = $email;
			$data['times'] = 0;
			$data['createtime'] = time();
			$data['times'] = 0;
			pdo_insert('ewei_exam_course_reserve', $data);
			$reserveid = pdo_insertid();
			$url = $this->createMobileUrl('reserve', array('id' => $reserveid));
			die(json_encode(array("result" => 1, "url" => $url)));
		} else {
			$fans = fans_search($_W['fans']['from_user'],array('nickname','email','mobile'));
			//更新访问人数记录
			$this->updateCourseMemberNum($id, 0);
			include $this->template('course');
		}
	}


	//获取预定课程
	public function doMobileReserve()
	{
		global $_GPC, $_W;

		$this->check_member();
		$weid = $_W['uniacid'];

		$id = intval($_GPC['id']);
		if (empty($id)) {
			exit;
		}

		$params = array();
		$params[':weid'] = $weid;
		$params[':id'] = $id;

		$sql = "SELECT p.username, p.mobile, p.email, p.status as reserve_stauts, c.*";
		$sql .= " FROM " .tablename('ewei_exam_course_reserve') ." AS p";
		$sql .= " LEFT JOIN " .tablename('ewei_exam_course') . " AS c ON p.courseid = c.id";

		$sql .= " WHERE 1 = 1";
		$sql .= " AND p.weid = :weid";
		$sql .= " AND p.id = :id";

		$item = pdo_fetch($sql, $params);

		$data = array();
		$data['result'] = 1;

		include $this->template('reserve');
	}


	//获取预定课程列表
	public function doMobileReservelist()
	{
		global $_GPC, $_W;

		$this->check_member();
		$weid = $_W['uniacid'];
		$member_info = $this->getMemberInfo();
		if(empty($member_info)) {
			message('获取用户信息出错', '', 'error');
		}


		$ac = $_GPC['ac'];
		if ($ac == "getDate") {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;

			$params = array();
			$params[':weid'] = $weid;
			$params[':memberid'] = intval($member_info['id']);

			$sql = "SELECT p.*, c.title, c.coursetime";
			$count_sql = "SELECT COUNT(p.id)";

			$where = '';
			$where .= " FROM " .tablename('ewei_exam_course_reserve') ." AS p";
			$where .= " LEFT JOIN " .tablename('ewei_exam_course') . " AS c ON p.courseid = c.id";

			$where .= " WHERE 1 = 1";
			$where .= " AND p.weid = :weid";
			$where .= " AND p.memberid = :memberid";

			$sql .= $where;
			$sql .= " ORDER BY id DESC";
			if($pindex > 0) {
				// 需要分页
				$start = ($pindex - 1) * $psize;
				$sql .= " LIMIT {$start},{$psize}";
			}

			$count_sql .= $where;
			$list = pdo_fetchall($sql, $params);
			$total = pdo_fetchcolumn($count_sql, $params);

			$page_array = get_page_array($total, $pindex, $psize);

			//print_r($list);exit;

			$data = array();
			$data['result'] = 1;

			ob_start();
			include $this->template('reserve_crumb');
			$data['code'] = ob_get_contents();
			ob_clean();

			$data['total'] = $total;
			$data['isshow'] = $page_array['isshow'];
			if ($page_array['isshow'] == 1) {
				$data['nindex'] = $page_array['nindex'];
			}
			die(json_encode($data));

		} else {
			include $this->template('reservelist');
		}
	}


	//获取试卷列表
	public function doMobilepaperlist()
	{
		global $_GPC, $_W;

		$this->check_member();

		$weid = $_W['uniacid'];
		$search_array = get_cookie($this->_search_key);
		//print_r($search_array);exit;
		$member = $this->getMemberInfo();
		$params = array();
		$params[':weid'] = $weid;
		//$this->check_login();

		if(empty($search_array['year_value'])) {
			$year = 0;
			$year_title = "年份";
		} else {
			$year = $search_array['year_value'];
			$year_title = $year . "年";
		}

		if(empty($search_array['cate_value'])) {
			$pcate = 0;
			$cate_title = "分类";
		} else {
			$pcate = $search_array['cate_value'];
			$cate_title = $search_array['cate_name'];
		}

		$ac = $_GPC['ac'];
		if ($ac == "getDate") {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;

			$sql = "SELECT p.id, p.title, p.displayorder";
			$count_sql = "SELECT COUNT(p.id)";

			$where = '';
			$where .= " FROM " .tablename('ewei_exam_paper') ." AS p";
			if (!empty($pcate)){
				$where .= " LEFT JOIN " .tablename('ewei_exam_paper_category') . " AS c ON p.pcate = c.id";
			}
			$where .= " WHERE 1 = 1";
			$where .= " AND p.weid = :weid";
			if (!empty($year)){
				$where .= " AND p.year = :year";
				$params[':year'] = $year;
			}
			$where .= " AND p.status = 1";
			if (!empty($pcate)){
				$where .= " AND c.status = 1";

				//判断是否为一级分类
				$cate_sql = "SELECT id FROM " .tablename('ewei_exam_paper_category');
				$cate_sql .=  " WHERE parentid = " . $pcate;
				$cate_sql .=  " AND weid = " . $weid;
				//$cate_sql .= " AND status = 1";

				$item = pdo_fetchall($cate_sql);
				$cate_num = count($item);

				if ($cate_num == 0) {
					$where .= " AND c.id = :pcate";
					$params[':pcate'] = $pcate;
				} else if ($cate_num > 0) {
					$item[$cate_num]['id'] = $pcate;
					$cate_str = '';
					foreach ($item as $k => $v) {
						$cate_str .= $v['id'] . ",";
					}
					$cate_str = trim($cate_str, ",");
					$where .= " AND c.id in (" . $cate_str . ")";
				}
			}

			$sql .= $where;
			$sql .= " ORDER BY p.displayorder DESC";
			if($pindex > 0) {
				// 需要分页
				$start = ($pindex - 1) * $psize;
				$sql .= " LIMIT {$start},{$psize}";
			}

			$count_sql .= $where;
			$list = pdo_fetchall($sql, $params);
			foreach($list as &$row){
				$r = pdo_fetch("select did from ".tablename('ewei_exam_paper_member_record')." where did=1  and weid=:weid and paperid=:paperid and memberid=:memberid limit 1",
					array(":weid"=>$_W['uniacid'],
						":memberid"=>$member['id'],
						":paperid"=>$row['id']));
				$row['did'] = !empty($r);
			}
			unset($row);
			$total = pdo_fetchcolumn($count_sql, $params);

			$page_array = get_page_array($total, $pindex, $psize);

			//print_r($total);exit;

			$data = array();
			$data['result'] = 1;

			ob_start();
			include $this->template('paper_crumb');
			$data['code'] = ob_get_contents();
			ob_clean();

			$data['total'] = $total;
			$data['isshow'] = $page_array['isshow'];
			if ($page_array['isshow'] == 1) {
				$data['nindex'] = $page_array['nindex'];
			}
			die(json_encode($data));
		} else {

			$year_array = array();
			for ($i = date("Y"); $i >= 2000; $i--) {
				$year_array[] = $i;
			}

			$sql = "SELECT id, cname, parentid FROM " .tablename('ewei_exam_paper_category');
			$sql .= " WHERE weid = :weid AND status = 1";
			$sql .= " ORDER BY displayorder DESC,parentid";
			$cate_list = pdo_fetchall($sql, $params);

			//print_r($cate_list);exit;

			include $this->template('paperlist');
		}
	}

	/**
	 * 继续上次考试
	 */
	public function doMobileContinue()
	{
		global $_GPC, $_W;
		$this->check_member();
		$weid = $_W['uniacid'];
		$member_info = $this->getMemberInfo();
		$sql = "SELECT * FROM " . tablename('ewei_exam_paper_member_record');
		$sql .= " WHERE memberid = :memberid AND weid = :weid AND did = 0";
		$sql .= " ORDER BY id DESC LIMIT 1";
		$params = array();
		$params[':memberid'] = $member_info['id'];
		$params[':weid'] = $weid;
		$item = pdo_fetch($sql, $params);
		if ($item) {
			$params[':recordid'] = $item['id'];
			$sql = "SELECT id, questionid, pageid FROM " . tablename('ewei_exam_paper_member_data');
			$sql .= " WHERE recordid = :recordid AND memberid = :memberid AND weid = :weid";
			$sql .= " ORDER BY pageid DESC LIMIT 1";
			$question_item = pdo_fetch($sql, $params);
			if ($question_item) {
				$pageid = $question_item['pageid'];
			} else {
				$pageid = 1;
			}
			$url = $this->createMobileUrl('start', array('paperid' => $item['paperid'], 'recordid' => $item['id'], 'page' => $pageid));
			header("Location:" . $url);
		}
		message('抱歉，没有查询到您有未完成的考试！', referer(), 'error');
	}

	/**
	 * 开始考试
	 */
	public function doMobileReady()
	{
		global $_GPC, $_W;
		$this->check_member();
		$id = intval($_GPC['id']);
		$member = $this->getMemberInfo();
		$paper_info = $this->getPaperInfo($id);

		if (!$paper_info['types']) {
			message('试卷不存在或已经被删除！');
		}

		if (checksubmit('submit')) {
			// 更新用户信息
			$update = array(
				'username' => trim($_GPC['username']),
				'mobile' => trim($_GPC['mobile']),
				'email' => trim($_GPC['email'])
			);
			$params = array(
				'weid' => $_W['uniacid'],
				'from_user' => $_W['fans']['openid']
			);

			pdo_update('ewei_exam_member', $update, $params);

			// 更新考试人数记录
			$this->updatePaperMemberNum($id, 1);

			// 增加学员考试记录
			$data = array(
				'weid' => $_W['uniacid'],
				'paperid' => $id,
				'memberid' => $member['id'],
				'times' => 0,
				'countdown' => $paper_info['times'] * 60,
				'score' => 0,
				'did' => 0,
				'createtime' => TIMESTAMP
			);

			pdo_insert('ewei_exam_paper_member_record', $data);
			$recordid = pdo_insertid();
			$url = $this->createMobileUrl('start', array('paperid' => $id, 'recordid' => $recordid, 'page' => 1));
			message(array("result" => 1, "url" => $url), '', 'ajax');
		}

		// 更新访问人数记录
		$this->updatePaperMemberNum($id, 0);
		include $this->template('ready');
	}



	//准备考试
	public function doMobileAnswer()
	{
		global $_GPC, $_W;

		$this->check_member();

		$weid = $_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 1;
		$paperid = intval($_GPC['paperid']);
		$recordid = intval($_GPC['recordid']);
		$types_config = $this->_types_config;
		$answer_array = $this->_answer_array;

		$ac = $_GPC['ac'];
		//获取题目信息
		if ($ac == "getDate") {

			$question_item = get_paper_question_list($paperid);
			$total = count($question_item);

			$member_info = $this->getMemberInfo();

			$data = array();
			$data['result'] = 1;

			if ($pindex > $total) {
				$data['result'] = 0;
				$data['error'] = "抱歉，题数参数错误";
				die(json_encode($data));
			}

			$question_info = $question_item[($pindex - 1)];

			if (!$question_info) {
				$data['result'] = 0;
				$data['error'] = "抱歉，试题错误";
				die(json_encode($data));
			}

			//判断用户是否回答过
			$params = array();
			$params[':weid'] = $weid;
			$params[':paperid'] = $paperid;
			$params[':memberid'] = $member_info['id'];
			$params[':recordid'] = $recordid;
			$params[':questionid'] = $question_info['id'];
			$item = get_one_member_question($params);

			if ($item) {
				//已经回答过
				$is_has = 1;
			} else {
				//还没有回答过
				$is_has = 0;
			}

			$page_array = get_page_array($total, $pindex, $psize);

			//print_r($total);exit;

			ob_start();
			include $this->template('question_answer_form');
			$data['code'] = ob_get_contents();
			ob_clean();

			$data['total'] = $total;
			$data['isshow'] = $page_array['isshow'];
			if ($page_array['isshow'] == 1) {
				$data['nindex'] = $page_array['nindex'];
			}
			die(json_encode($data));

		} else {
			$paper_info = $this->getPaperInfo($paperid);
			include $this->template('question_answer');
		}

	}


	/**
	 * 考试进行中
	 */
	public function doMobileStart()
	{
		global $_GPC, $_W;
		$this->check_member();
		$weid = $_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 1;
		$paperid = intval($_GPC['paperid']);
		$recordid = intval($_GPC['recordid']);
		$types_config = $this->_types_config;
		$answer_array = $this->_answer_array;
		if (empty($paperid) || empty($recordid)) {
			echo "传递参数错误";
			exit;
		}
		$record_info = $this->getRecordInfo($recordid);
		//该试卷已经完成，不能再继续了
		if ($record_info['did'] == 1) {
			echo "该试卷已经完成，不能再继续了";
			exit;
		}
		$member_info = $this->getMemberInfo();
		//提交答案
		if (checksubmit('submit')) {
			$data = array();
			$data['result'] = 1;
			$count_flag = intval($_GPC['count_flag']);
			$questionid = intval($_GPC['questionid']);
			$now_page = intval($_GPC['now_page']);
			$btime = intval($_GPC['btime']);
			$type = intval($_GPC['type']);
			$items = "";
			$answer = "";
			$now_time = time();
			//试题类型
			switch ($type)
			{
				case 1:
					//判断题
					$answer = $_GPC['answer1'];
					break;
				case 2:
					//单选题
					$answer = $_GPC['answer2'];
					break;
				case 3:
					//多选题
					$arr = $_GPC['answer3'];
					if (empty($arr)) {
						$answer = '';
					} else {
						$answer = implode("", $arr);
					}
					break;
			}
			//判断答案是否正确
			$now_question_info = get_one_question($questionid);
			if (empty($answer)) {
				$isright = 0;
			} else {
				if ($now_question_info['answer'] == $answer) {
					$isright = 1;
				} else {
					$isright = 0;
				}
			}
			//判断用户是否回答过
			$params = array();
			$params[':weid'] = $weid;
			$params[':paperid'] = $paperid;
			$params[':memberid'] = $member_info['id'];
			$params[':recordid'] = $recordid;
			$params[':questionid'] = $questionid;
			$item = get_one_member_question($params);

			//要添加或者更新的数据
			$array = array();
			$array['isright'] = $isright;
			$array['answer'] = $answer;
			$array['type'] = $type;
			$array['pageid'] = $now_page;
			if ($item) {
				//已经回答过
				pdo_update('ewei_exam_paper_member_data', $array, array('id' => $item['id']));
				if ($isright == 1 && $item['isright'] == 0) {
					//多少人正确+1
					$this->updateQuestionMemberNum($questionid, 2);
				}
			} else {
				//还没有回答过
				$array['weid'] = $weid;
				$array['paperid'] = $paperid;
				$array['memberid'] = $member_info['id'];
				$array['recordid'] = $recordid;
				$array['questionid'] = $questionid;
				$array['createtime'] = $now_time;
				pdo_insert('ewei_exam_paper_member_data', $array);
				if ($isright) {
					//多少人做过,正确+1
					$this->updateQuestionMemberNum($questionid, 3);
				} else {
					//多少人做过+1
					$this->updateQuestionMemberNum($questionid, 1);
				}
			}

			//统计该用户当前试卷的做题情况
			if ($count_flag) {
				$paper_info = $this->getPaperInfo($paperid);
				$total = $paper_info['total'];
				$now_total = get_count_one_paper_record($params);
				if($now_total == $total) {
					$msg = "共" . $total . "题，您已全部做完";
				} else {
					$msg = "共" . $total . "题，您做了" . $now_total . "题，还剩" . ($total - $now_total) . "题未做";
				}
				$data['count_msg'] = $msg;
			}
			die(json_encode($data));

		} else {
			$question_item = get_paper_question_list($paperid);
			$total = count($question_item);
			$ac = $_GPC['ac'];
			//获取题目信息
			if ($ac == "getDate") {
				$data = array();
				$data['result'] = 1;
				if ($pindex > $total) {
					$data['result'] = 0;
					$data['error'] = "抱歉，题数参数错误";
					echo 12345;exit;
					die(json_encode($data));
				}

				$question_info = $question_item[($pindex - 1)];
				if (!$question_info) {
					$data['result'] = 0;
					$data['error'] = "抱歉，试题错误";
					die(json_encode($data));
				}

				//判断用户是否回答过
				$params = array();
				$params[':weid'] = $weid;
				$params[':paperid'] = $paperid;
				$params[':memberid'] = $member_info['id'];
				$params[':recordid'] = $recordid;
				$params[':questionid'] = $question_info['id'];
				$item = get_one_member_question($params);
				if ($item) {
					//已经回答过
					$is_has = 1;
				} else {
					//还没有回答过
					$is_has = 0;
				}
				$page_array = get_page_array($total, $pindex, $psize);

				ob_start();
				include $this->template('question_form');
				$data['code'] = ob_get_contents();
				ob_clean();
				$data['total'] = $total;
				$data['isshow'] = $page_array['isshow'];
				if ($page_array['isshow'] == 1) {
					$data['nindex'] = $page_array['nindex'];
				}
				die(json_encode($data));

			} else if ($ac == "close_exam") {
				$paper_info = $this->getPaperInfo($paperid);
				$now_question = $this->getRecordQuestion($paper_info, $recordid);
				//结束本次考试记录
				$data = array();
				$data['score'] = $now_question['score'];
				$data['did'] = 1;
				pdo_update('ewei_exam_paper_member_record', $data, array('id' => $recordid));
				//计算平均分和用时
				$sql = "SELECT AVG(score) as avg_score, AVG(times) as avg_times FROM " . tablename('ewei_exam_paper_member_record');
				$sql .= " WHERE paperid = :paperid AND weid = :weid AND did = 1";
				$question_item = pdo_fetch($sql, array(':paperid' => $paperid, ':weid' => $weid));
				$avg_score = round($question_item['avg_score'], 2);
				$avg_times = $question_item['avg_times'];

				//更新到试卷信息表中
				$data = array();
				$data['avscore'] = $avg_score;
				$data['avtimes'] = $avg_times;
				pdo_update('ewei_exam_paper', $data, array('id' => $paperid));
				$url = $this->createMobileUrl('score', array('paperid' => $paperid, 'recordid' => $recordid));
				die(json_encode(array("result" => 1, "url" => $url)));

			} else if ($ac == "update_countdown") {
				$paper_info = $this->getPaperInfo($paperid);
				if ($record_info['countdown'] > 0) {
					$countdown = intval($_GPC['total_time']);
					//更新考试剩余时间
					$data = array();
					$data['countdown'] = $countdown;
					$data['times'] = $paper_info['times'] * 60 - $countdown;
					pdo_update('ewei_exam_paper_member_record', $data, array('id' => $recordid));
				} else {
					$data['times'] = $paper_info['times'] * 60;
					pdo_update('ewei_exam_paper_member_record', $data, array('id' => $recordid));
				}
			} else {
				$paper_info = $this->getPaperInfo($paperid);
				include $this->template('question');
			}
		}
	}

	//准备考试
	public function doMobileScore()
	{
		global $_GPC, $_W;
		$this->check_member();
		$weid = $_W['uniacid'];
		$paperid = intval($_GPC['paperid']);
		$recordid = intval($_GPC['recordid']);
		$sql = "SELECT * FROM " . tablename('ewei_exam_paper_member_record');
		$sql .= " WHERE id = :id AND weid = :weid AND did = 1";
		$record_info = pdo_fetch($sql, array(':id' => $recordid, ':weid' => $weid));
		$paper_info = $this->getPaperInfo($paperid);
		$sql = "SELECT r.*, m.userid, m.username FROM " . tablename('ewei_exam_paper_member_record') . " AS r";
		$sql .= " LEFT JOIN  " . tablename('ewei_exam_member') . " as m ON r.memberid = m.id";
		$sql .= " WHERE r.paperid = :paperid AND r.weid = :weid AND m.weid = :weid AND did = 1";
		$sql .= " ORDER BY r.score DESC, r.times ASC LIMIT 10";
		$order_info = pdo_fetchall($sql, array(':paperid' => $paperid, ':weid' => $weid));
		$url_answer = $this->createMobileUrl('answer', array('paperid' => $paperid, 'recordid' => $recordid, 'page' => 1));
		$url = $this->createMobileUrl('ready', array('id' => $paperid));
		include $this->template('score');
	}


	//获取试卷中当前的考题和分数情况
	public function getRecordQuestion($array, $recordid)
	{
		global $_GPC, $_W;

		$weid = $_W['uniacid'];
		$params = array();
		$params[':paperid'] = $array['id'];
		$params[':recordid'] = $recordid;
		$params[':weid'] = $weid;

		$score_array = array();
		$score = 0;

		foreach ($array['types'] as $key => $value) {
			if ($value['has'] == 1) {
				$params[':type'] = $key;

				$count_sql = "SELECT COUNT(id) FROM " . tablename('ewei_exam_paper_member_data');
				$count_sql .= " WHERE recordid = :recordid ";
				$count_sql .= " AND paperid = :paperid ";
				//$count_sql .= " AND memberid = :memberid ";
				$count_sql .= " AND weid = :weid";
				$count_sql .= " AND type = :type";
				$count_sql .= " AND isright = 1";
				$total = pdo_fetchcolumn($count_sql, $params);

				$score_array[$key]['num'] = $total;
				$score_array[$key]['score'] = $total * $array['types'][$key]['one_score'];
				$score += $score_array[$key]['score'];
			}
		}

		$data = array();
		$data['data'] = $score_array;
		$data['score'] = $score;

		//print_r($data);exit;

		return $data;
	}

	//获取试卷和试卷类型的信息
	public function getPaperInfo($paper_id)
	{

		global $_GPC, $_W;

		$weid = $_W['weid'];
		$sql = "SELECT p.id, p.title, p.level, p.year, p.avscore, p.avtimes, t.score, t.types, t.times,p.description FROM " . tablename('ewei_exam_paper') . " AS p";
		$sql .= " LEFT JOIN " . tablename('ewei_exam_paper_type') . " AS t on p.tid = t.id";
		$sql .= " WHERE p.id = :id AND p.weid = :weid";
		$sql .= " LIMIT 1";

		$params = array();
		$params[':id'] = intval($paper_id);
		$params[':weid'] = $weid;

		$item = pdo_fetch($sql, $params);
		$item['types'] = unserialize($item['types']);

		$total = 0;
		if (!empty($item['types'])) {
			foreach ($item['types'] as $k => $v) {
				if ($v['has'] == 1) {
					$total += $v['num'];
				}
			}
		}

		$item['total'] = $total;
		return $item;

	}

	//获取试卷和试卷类型的信息
	public function getRecordInfo($recordid)
	{
		global $_W;
		$weid = $_W['uniacid'];
		$sql = "SELECT * FROM " . tablename('ewei_exam_paper_member_record');
		$sql .= " WHERE id = :id AND weid = :weid";
		$sql .= " LIMIT 1";

		$params = array();
		$params[':id'] = intval($recordid);
		$params[':weid'] = $weid;
		return pdo_fetch($sql, $params);
	}

	// 获取用户信息
	public function getMemberInfo() {
		global $_W;
		$sql = 'SELECT * FROM ' . tablename('ewei_exam_member') . ' WHERE `weid` = :weid AND `from_user` = :from_user';
		$params = array(':weid' => $_W['uniacid'], ':from_user' => $_W['fans']['openid']);
		return pdo_fetch($sql, $params);
	}

	//ajax数据处理,包含 年份选择 分类选择
	public function doMobileajaxData()
	{
		global $_GPC, $_W;
		$referer = $_GPC['referer'];
		$data = $this->getSearchArray();
		$key = $this->_search_key;
		switch ($_GPC['ac'])
		{
			//选择年份选，分类
			case 'year':
				$data['year_value'] = intval($_GPC['year_value']);
				$data['cate_value']= intval($_GPC['cate_value']);
				$data['cate_name'] = $_GPC['cate_name'];

				insert_cookie($key, $data);
				die(json_encode(array("result" => 1)));
				break;
		}
	}


	function getSearchArray(){
		$search_array = get_cookie($this->_search_key);
		if (empty($search_array)) {
			//默认搜索参数
			$search_array['year_value'] = 0;
			$search_array['cate_value'] = 0;
			$search_array['cate_name'] = '';

			insert_cookie($this->_search_key, $search_array);
		}

		return $search_array;
	}

	//更新试卷统计 $type 0访问人数 1报名人数
	public function updateCourseMemberNum($courseid, $type)
	{
		global $_GPC, $_W;

		switch ($type)
		{
			case 1:
				//报名人数+1
				$set = " fansnum = fansnum + 1";
				break;
			case 0:
				//访问人数确+1
				$set = " viewnum = viewnum + 1";
				break;
		}
		pdo_query("update " . tablename('ewei_exam_course') . " set " . $set . " where id=:id", array(":id" => $courseid));
	}

	//更新试卷统计 $type 0访问人数 1考试人数
	public function updatePaperMemberNum($paperid, $type)
	{
		global $_GPC, $_W;

		switch ($type)
		{
			case 1:
				//报名人数+1
				$set = " fansnum = fansnum + 1";
				break;
			case 0:
				//访问人数确+1
				$set = " viewnum = viewnum + 1";
				break;
		}
		pdo_query("update " . tablename('ewei_exam_paper') . " set " . $set . " where id=:id", array(":id" => $paperid));
	}

	//更新试题统计
	public function updateQuestionMemberNum($questionid, $type)
	{
		switch ($type)
		{
			case 1:
				//多少人做过+1
				$set = " fansnum = fansnum + 1";
				break;
			case 2:
				//多少人正确+1
				$set = " correctnum = correctnum + 1";
				break;
			case 3:
				//多少人做过,正确+1
				$set = " fansnum = fansnum + 1, correctnum = correctnum + 1";
				break;
		}

		pdo_query("update " . tablename('ewei_exam_question') . " set " . $set . " where id=:id", array(":id" => $questionid));
	}

	public function doMobileabout()
	{
		global $_W, $_GPC;
		$item = pdo_fetch("select about from " . tablename('ewei_exam_sysset') . " where weid=:weid limit 1", array(":weid" => $_W['uniacid']));
		$about = $item['about'];
		$this->check_member();
		include $this->template('about');
	}

	public function doMobileindex()
	{
		global $_W, $_GPC;
		$this->check_member();
		$set = $this->_set_info;
		include $this->template('index');
	}

}
