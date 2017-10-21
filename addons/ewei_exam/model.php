<?php

defined('IN_IA') or exit('Access Denied');

if(!function_exists('get_init_param')) {
	function get_init_param() {
		$data = array();
		//4 填空 5 解答
		$data['types_config'] = array('2' => '单选题', '3' => '多选题', '1' => '判断题');
		$data['answer_array'] = array('A', 'B', 'C', 'D', 'E', 'F');
		//$answer_array = array('A', 'B', 'C', 'D', 'E', 'F');
		return $data;
	}
}

	function img_url($img = '') {
		global $_W;
		if (empty($img)) {
			return "";
		}
		if (substr($img, 0, 6) == 'avatar') {
			return $_W['siteroot'] . "resource/image/avatar/" . $img;
		}
		if (substr($img, 0, 8) == './themes') {
			return $_W['siteroot'] . $img;
		}
		if (substr($img, 0, 1) == '.') {
			return $_W['siteroot'] . substr($img, 2);
		}
		if (substr($img, 0, 5) == 'http:') {
			return $img;
		}
		return $_W['attachurl'] . $img;
	}

if(!function_exists('get_ewei_exam_sysset')) {
	function get_ewei_exam_sysset()
	{
		global $_W;
		$weid = $_W['uniacid'];
		$set = pdo_fetch("select classopen, login_flag from " . tablename('ewei_exam_sysset') . " where weid=:weid limit 1", array(":weid" => $weid));
		return $set;
	}
}

//获取试卷所有题目
if(!function_exists('get_paper_question_list')) {
	function get_paper_question_list($paperid)
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];

		$sql = "SELECT q.*, pq.displayorder FROM " . tablename('ewei_exam_question') . " as q";
		$sql .= " LEFT JOIN " . tablename('ewei_exam_paper_question') . " as pq ON q.id = pq.questionid";
		$sql .= " WHERE pq.paperid = :id AND q.weid = :weid";
		$sql .= " ORDER BY q.type, pq.displayorder DESC, q.id";
		$question_item = pdo_fetchall($sql, array(':id' => $paperid, ':weid' => $weid));

	//    foreach ($question_item as $key => $value) {
	//        if (!empty($value['items'])) {
	//            $question_item[$key]['items'] = unserialize($value['items']);
	//        }
	//        $question = $question_item[$key]['question'];
	//        if(strpos("------", $question)){
	//            $qq = explode('------',$question);
	//            $question_item[$key]['question']= $qq[1];
	//        }
	//    
	//    }
		foreach($question_item as &$value){
			if (!empty($value['items'])) {
				$value['items'] = unserialize($value['items']);
			}
			if ($value['isimg'] == 1) {
				$value['img_items'] = unserialize($value['img_items']);
			}
			$question = $value['question'];
			if(strpos($question,"------")){
				$qq = explode('------',$question);
				$value['question']= $qq[1];
			}
		}
		unset($value);
		return $question_item;
	}
}

//获取一道题目
if(!function_exists('get_one_question')) {
	function get_one_question($questionid)
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$sql = "SELECT * FROM " . tablename('ewei_exam_question');
		$sql .= " WHERE id = :id AND weid = :weid LIMIT 1";
		$question_item = pdo_fetch($sql, array(':id' => $questionid, ':weid' => $weid));
		return $question_item;
	}
}

//获取用户做过的题一道题目
if(!function_exists('get_one_member_question')) {
	function get_one_member_question($array)
	{
		global $_GPC, $_W;
		$sql = "SELECT * FROM " . tablename('ewei_exam_paper_member_data');
		$sql .= " WHERE recordid = :recordid ";
		$sql .= " AND paperid = :paperid ";
		$sql .= " AND questionid = :questionid ";
		$sql .= " AND memberid = :memberid ";
		$sql .= " AND weid = :weid LIMIT 1";
		$question_item = pdo_fetch($sql, $array);
		return $question_item;
	}
}

//获取该用户当前试卷的做题情况
if(!function_exists('get_count_one_paper_record')) {
	function get_count_one_paper_record($array)
	{
		global $_GPC, $_W;
		unset($array[':questionid']);

		$sql = "SELECT COUNT(id) FROM " . tablename('ewei_exam_paper_member_data');
		$sql .= " WHERE recordid = :recordid ";
		$sql .= " AND paperid = :paperid ";
		$sql .= " AND memberid = :memberid ";
		$sql .= " AND weid = :weid";

		$total = pdo_fetchcolumn($sql, $array);
		return $total;
	}
}

//获取使用的时间
if(!function_exists('format_use_time')) {
	function format_use_time($time)
	{
		$array = Sec2Time($time);
		return $array['string'];
	}
}

if(!function_exists('Sec2Time')) {
	function Sec2Time($time)
	{
		if (is_numeric($time)) {
			$value = array(
				"years" => 0, "days" => 0, "hours" => 0,
				"minutes" => 0, "seconds" => 0,"string" => ''
			);
			if ($time >= 31556926) {
				$value["years"] = floor($time / 31556926);
				$time = ($time % 31556926);
			}
			if ($time >= 86400) {
				$value["days"] = floor($time / 86400);
				$time = ($time % 86400);
			}
			if ($time >= 3600) {
				$value["hours"] = floor($time / 3600);
				if (!empty($value["hours"])) {
					$value["string"] .= $value["hours"] . "小时";
				}
				$time = ($time % 3600);
			}
			if ($time >= 60) {
				$value["minutes"] = floor($time / 60);
				if (!empty($value["minutes"])) {
					$value["string"] .= $value["minutes"] . "分";
				}
				$time = ($time % 60);
			}
			$value["seconds"] = floor($time);
			if (!empty($value["seconds"])) {
				$value["string"] .= $value["seconds"] . "秒";
			}
			return (array)$value;
		} else {
			return (bool)FALSE;
		}
	}
}


/**
 * @param int $flag 0注册，1登录
 * @param array $member 用户数据
 * @return string
 */
if(!function_exists('exam_set_userinfo')) {
	function exam_set_userinfo($flag = 0, $member)
	{
		global $_GPC, $_W;
		insert_cookie('__exam_member', $member);
	}
}

if(!function_exists('exam_get_userinfo')) {
	function exam_get_userinfo()
	{
		global $_W;
		$key = '__exam_member';
		return get_cookie($key);
	}
}

if(!function_exists('get_cookie')) {
	function get_cookie($key)
	{
		global $_W;
		$key = $_W['config']['cookie']['pre'] . $key;
		return json_decode(base64_decode($_COOKIE[$key]), true);
	}
}

if(!function_exists('insert_cookie')) {
	function insert_cookie($key, $data)
	{
		global $_W, $_GPC;
		$session = base64_encode(json_encode($data));
		isetcookie($key, $session, !empty($_GPC['rember']) ? 7 * 86400 : 0);
	}
}

//检查用户是否登录
if(!function_exists('check_hotel_user_login')) {
	function check_hotel_user_login()
	{
		global $_W;
		$weid = $_W['uniacid'];
		$from_user = $_W['fans']['from_user'];

		$user_info = hotel_get_userinfo();
		if (empty($user_info['id'])) {
			return 0;
		} else {
			if (($from_user == $user_info['from_user']) && ($weid == $user_info['weid'])) {
				return 1;
			} else {
				return 0;
			}
		}
	}
}

/**
 * 计算用户密码hash
 * @param string $input 输入字符串
 * @param string $salt 附加字符串
 * @return string
 */
if(!function_exists('hotel_member_hash')) {
	function hotel_member_hash($input, $salt)
	{
		global $_W;
		$input = "{$input}-{$salt}-{$_W['config']['setting']['authkey']}";
		return sha1($input);
	}
}

/**
 * 用户注册
 * PS:密码字段不要加密
 * @param array $member 用户注册信息，需要的字段必须包括 username, password, remark
 * @return int 成功返回新增的用户编号，失败返回 0
 */
if(!function_exists('hotel_member_check')) {
	function hotel_member_check($member)
	{
		$sql = 'SELECT `password`,`salt` FROM ' . tablename('hotel2_member') . " WHERE 1";
		$params = array();
		if (!empty($member['uid'])) {
			$sql .= ' AND `uid`=:uid';
			$params[':uid'] = intval($member['uid']);
		}
		if (!empty($member['username'])) {
			$sql .= ' AND `username`=:username';
			$params[':username'] = $member['username'];
		}
		if (!empty($member['from_user'])) {
			$sql .= ' AND `from_user`=:from_user';
			$params[':from_user'] = $member['from_user'];
		}
		if (!empty($member['status'])) {
			$sql .= " AND `status`=:status";
			$params[':status'] = intval($member['status']);
		}
		if (!empty($member['id'])) {
			$sql .= " AND `id`!=:id";
			$params[':id'] = intval($member['id']);
		}
		$sql .= " LIMIT 1";
		$record = pdo_fetch($sql, $params);
		if (!$record || empty($record['password']) || empty($record['salt'])) {
			return false;
		}
		if (!empty($member['password'])) {
			$password = hotel_member_hash($member['password'], $record['salt']);
			return $password == $record['password'];
		}
		return true;
	}
}


/**
 * 获取单条用户信息，如果查询参数多于一个字段，则查询满足所有字段的用户
 * PS:密码字段不要加密
 * @param array $member 要查询的用户字段，可以包括  uid, username, password, status
 * @param bool 是否要同时获取状态信息
 * @return array 完整的用户信息
 */
if(!function_exists('hotel_member_single')) {
	function hotel_member_single($member)
	{
		$sql = 'SELECT * FROM ' . tablename('hotel2_member') . " WHERE 1";
		$params = array();
		if (!empty($member['from_user'])) {
			$sql .= ' AND `from_user`=:from_user';
			$params[':from_user'] = $member['from_user'];
		}
		if (!empty($member['username'])) {
			$sql .= ' AND `username`=:username';
			$params[':username'] = $member['username'];
		}
		if (!empty($member['status'])) {
			$sql .= " AND `status`=:status";
			$params[':status'] = intval($member['status']);
		}

		$sql .= " LIMIT 1";
		$record = pdo_fetch($sql, $params);
		if (!$record) {
			return false;
		}
		if (!empty($member['password'])) {
			$password = hotel_member_hash($member['password'], $record['salt']);
			if ($password != $record['password']) {
				return false;
			}
		}
		return $record;
	}
}

if(!function_exists('get_hotel_set')) {
	function get_hotel_set()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$set = pdo_fetch("select * from " . tablename('hotel2_set') . " where weid=:weid limit 1", array(":weid" => $weid));
		if (!$set) {
			$set = array(
				"user" => 1,
				"bind" => 1,
				"reg" => 1,
				"ordertype" => 1,
				"regcontent" => "",
				"paytype1" => 0,
				"paytype2" => 0,
				"paytype3" => 0,
				"is_unify" => 0,
				"version" => 0,
				"tel" => "",
			);
		}
		return $set;
	}
}

//获取登录用户
if(!function_exists('get_login_user')) {
	function get_login_user()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		if (isset($_SESSION['hotel2_member'])) {
			return json_decode($_SESSION['hotel2_member']);
		}
		$member = pdo_fetch("select * from " . tablename('hotel2_member') . " where weid=:weid and from_user=:from_user and islogin=1 limit 1", array(":weid" => $weid, ":from_user" => $_W['fans']['from_user']));
		session_start();
		$_SESSION['hotel2_member'] = json_encode($member);
		return $member;

	}
}

if(!function_exists('check_orderinfo')) {
	function check_orderinfo($member)
	{
		global $_GPC, $_W;

		$sql = "SELECT ID FROM " . tablename('hotel2_order') . " WHERE 1 = 1";

		if (!empty($member['hotelid'])) {
			$sql .= ' AND `hotelid`=:hotelid';
			$params[':hotelid'] = $member['hotelid'];
		}

		if (!empty($member['openid'])) {
			$sql .= ' AND `openid`=:openid';
			$params[':openid'] = $member['openid'];
		}

		if (!empty($member['roomid'])) {
			$sql .= ' AND `roomid`=:roomid';
			$params[':roomid'] = $member['roomid'];
		}

		if (!empty($member['memberid'])) {
			$sql .= ' AND `memberid`=:memberid';
			$params[':memberid'] = $member['memberid'];
		}

		if (!empty($member['name'])) {
			$sql .= ' AND `name`=:name';
			$params[':name'] = $member['name'];
		}

		if (!empty($member['contact_name'])) {
			$sql .= ' AND `contact_name`=:contact_name';
			$params[':contact_name'] = $member['contact_name'];
		}

		if (!empty($member['mobile'])) {
			$sql .= ' AND `mobile`=:mobile';
			$params[':mobile'] = $member['mobile'];
		}

		if (!empty($member['btime'])) {
			$sql .= ' AND `btime`=:btime';
			$params[':btime'] = $member['btime'];
		}

		if (!empty($member['etime'])) {
			$sql .= ' AND `etime`=:etime';
			$params[':etime'] = $member['etime'];
		}

		if (!empty($member['nums'])) {
			$sql .= ' AND `nums`=:nums';
			$params[':nums'] = $member['nums'];
		}

		if (!empty($member['sum_price'])) {
			$sql .= ' AND `sum_price`=:sum_price';
			$params[':sum_price'] = $member['sum_price'];
		}

		$sql .= " LIMIT 1";

		$record = pdo_fetch($sql, $params);
		if ($record) {
			return 1;
		} else {
			return 0;
		}
	}
}

/**
 * 生成分页数据
 * @param int $currentPage 当前页码
 * @param int $totalCount 总记录数
 * @param string $url 要生成的 url 格式，页码占位符请使用 *，如果未写占位符，系统将自动生成
 * @param int $pageSize 分页大小
 * @return string 分页HTML
 */
if(!function_exists('get_page_array')) {
	function get_page_array($tcount, $pindex, $psize = 15)
	{
		global $_W;
		$pdata = array(
			'tcount' => 0,
			'tpage' => 0,
			'cindex' => 0,
			'findex' => 0,
			'pindex' => 0,
			'nindex' => 0,
			'lindex' => 0,
			'options' => ''
		);

		$pdata['tcount'] = $tcount;
		$pdata['tpage'] = ceil($tcount / $psize);
		if ($pdata['tpage'] <= 1) {
			$pdata['isshow'] = 0;
			return $pdata;
		}

		$cindex = $pindex;
		$cindex = min($cindex, $pdata['tpage']);
		$cindex = max($cindex, 1);
		$pdata['cindex'] = $cindex;
		$pdata['findex'] = 1;
		$pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
		$pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
		$pdata['lindex'] = $pdata['tpage'];

		if ($pdata['cindex'] == $pdata['lindex']) {
			$pdata['isshow'] = 0;
			$pdata['islast'] = 1;
		} else {
			$pdata['isshow'] = 1;
			$pdata['islast'] = 0;
		}

		return $pdata;
	}
}

//0升序 1降序
if(!function_exists('array_sort')) {
	function array_sort($arr, $keys, $type = 0)
	{
		$keysvalue = $new_array = array();
		foreach ($arr as $k => $v) {
			$keysvalue[$k] = $v[$keys];
		}
		if ($type == 0) {
			asort($keysvalue);
		} else {
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k => $v) {
			$new_array[$k] = $arr[$k];
		}
		return $new_array;
	}
}

if(!function_exists('check_userid')) {
	function check_userid($array, $id)
	{
		global $_W;

		$params = array();
		$params[':userid'] = $array['userid'];
		$params[':weid'] = $_W['uniacid'];

		$sql = "SELECT id FROM " . tablename('ewei_exam_member') . " WHERE weid = :weid AND userid = :userid";

		if (!empty($id)) {
			$sql .= " AND id != :id";
			$params[':id'] = $id;
		}
		$sql .= " LIMIT 1";
		$item = pdo_fetch($sql, $params);

		//print_r($check_flag);exit;

		if ($item) {
			return 1;
		} else {
			return 0;
		}
	}
}

if(!function_exists('check_question')) {
	function check_question($array, $id)
	{
		global $_W;

		$params = array();
		$params[':question'] = $array['question'];
		$params[':type'] = $array['type'];
		$params[':weid'] = $_W['uniacid'];

		$sql = "SELECT id FROM " . tablename('ewei_exam_question') . " WHERE weid = :weid AND question = :question AND type = :type";

		if (!empty($id)) {
			$sql .= " AND id != :id";
			$params[':id'] = $id;
		}
		$sql .= " LIMIT 1";
		$item = pdo_fetch($sql, $params);

		//print_r($check_flag);exit;

		if ($item) {
			return 1;
		} else {
			return 0;
		}
	}
}

//取得用户做的题数统计
if(!function_exists('get_user_question_count')) {
	function get_user_question_count($memberid, $type)
	{
		global $_W;

		$params = array();
		$params[':memberid'] = $memberid;
		$params[':weid'] = $_W['uniacid'];

		$sql = "SELECT count(id) as num FROM " . tablename('ewei_exam_paper_member_data') . " WHERE memberid = :memberid AND weid = :weid ";

		if ($type != 2) {
			$sql .= " AND isright = :isright";
			$params[':isright'] = $type;
		}
		$total = pdo_fetchcolumn($sql, $params);
		return $total;
	}
}

//根据id取得用户信息
if(!function_exists('get_user_info')) {
	function get_user_info($memberid)
	{
		global $_W;

		$params = array();
		$params[':id'] = $memberid;
		$params[':weid'] = $_W['uniacid'];

		$sql = "SELECT username, userid FROM " . tablename('ewei_exam_member') . " WHERE id = :id AND weid = :weid ";

		$item = pdo_fetch($sql, $params);
		return $item;
	}
}

if(!function_exists('upload_member')) {
	function upload_member($strs, $time)
	{
		global $_W;

		$userid = $strs[0];
		$username = $strs[1];

		if (empty($userid) || empty($username)) {
			return 0;
		}
		$insert = array();
		$insert['userid'] = $userid;

		$flag = check_userid($insert, 0);

		if ($flag == 0) {
			$insert['username'] = $username;
			$insert['weid'] = $_W['uniacid'];
			$insert['createtime'] = $time;
			$insert['status'] = 1;
			pdo_insert('ewei_exam_member', $insert);
		}
	}
}

if(!function_exists('upload_question')) {
	function upload_question($strs, $time, $array)
	{
		global $_W;

		//print_r($array);exit;

		$question_type = $strs[0];
		$level = $strs[1];
		$question = $strs[2];
		$answer = $strs[3];
		$answer1 = $strs[4];
		$answer2 = $strs[5];
		$answer3 = $strs[6];
		$answer4 = $strs[7];
		$answer5 = $strs[8];
		$answer6 = $strs[9];
		$explain = $strs[10];

		$row_num = $array['row_num'];
		
		$insert = array();
		//$insert['userid'] = $userid;

		if (empty($question_type) || empty($question) || empty($answer)) {
			return 0;
		}
		if ($question_type == '单选题') {
			$type = 2;
			$insert['answer'] = $answer;
		} elseif ($question_type == '多选题') {
			$type = 3;
			$insert['answer'] = $answer;
		} elseif ($question_type == '判断题') {
			$type = 1;
			if ($answer == '正确') {
				$insert['answer'] = 1;
			} else {
				$insert['answer'] = 0;
			}
		}
		if ($type > 1) {
			$answer_array = array($answer1, $answer2, $answer3, $answer4,$answer5,$answer6);
			$insert['items'] = serialize($answer_array);
		}
		$insert['type'] = $type;
		$insert['question'] = $question;

		$flag = check_question($insert, 0);
		if ($flag == 0) {
			if (!empty($array['poolid'])) {
				$insert['poolid'] = $array['poolid'];
			}
			$insert['level'] = $level;
			$insert['explain'] = $explain;
			$insert['weid'] = $_W['uniacid'];
			pdo_insert('ewei_exam_question', $insert);
		}
	}
}

if(!function_exists('uploadFile')) {
	function uploadFile($file, $filetempname, $array)
	{
		if ($result = file_get_contents($filetempname)) {
			$result = iconv('gbk', 'utf-8', $result);
			if (empty($result)) {
				return $msg = '导入失败';
			}
			$result = preg_replace('/\n/', 'h--h', $result);
			$result = explode('h--h', $result);
			foreach ($result as $key => $ques) {
				$ques  = explode(',', $ques);
				if ($key == 0) {
					continue;
				}
				$strs = array();
				foreach ($ques as $questi) {
					$strs[] = preg_replace('/\s/', '', trim($questi, "\""));
					if ($array['ac'] == 'question') {
						upload_question($strs, time(), $array);
					}elseif ($array['ac'] == 'member') {
						upload_member($strs, time());
					}
				}
			}
			$msg = '1';
		} else {
			$msg = "导入失败！";
		}
		return $msg;
	}
}