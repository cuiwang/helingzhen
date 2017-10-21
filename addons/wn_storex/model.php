<?php
/**
 * 万能小店
 *
 * @author WeEngine Team & ewei
 * @url
 */
defined('IN_IA') or exit('Access Denied');


function mload() {
	static $mloader;
	if (empty($mloader)) {
		$mloader = new Mloader();
	}
	return $mloader;
}
class Mloader {
	private $cache = array();
	function func($name) {
		if (isset($this->cache['func'][$name])) {
			return true;
		}
		$file = IA_ROOT . '/addons/wn_storex/function/' . $name . '.func.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['func'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Helper Function /addons/wn_storex/function/' . $name . '.func.php', E_USER_ERROR);
			return false;
		}
	}

	function model($name) {
		if (isset($this->cache['model'][$name])) {
			return true;
		}
		$file = IA_ROOT . '/addons/wn_storex/model/' . $name . '.mod.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['model'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Model /addons/wn_storex/model/' . $name . '.mod.php', E_USER_ERROR);
			return false;
		}
	}

	function classs($name) {
		if (isset($this->cache['class'][$name])) {
			return true;
		}
		$file = IA_ROOT . '/addons/wn_storex/class/' . $name . '.class.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['class'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Class /addons/wn_storex/class/' . $name . '.class.php', E_USER_ERROR);
			return false;
		}
	}
}


/**
 * 计算用户密码hash
 * @param int $flag 0注册，1登录
 * @param array $member 用户数据
 * @return string
 */
if (!function_exists('hotel_set_userinfo')) {
	function hotel_set_userinfo($flag = 0, $member) {
		global $_GPC, $_W;
		unset($member['password']);
		unset($member['salt']);
		insert_cookie('__hotel_member', $member);
	}
}



if (!function_exists('hotel_get_userinfo')) {
	function hotel_get_userinfo() {
		global $_W;
		$key = '__hotel_member';
		return get_cookie($key);
	}
}



if (!function_exists('get_cookie')) {
	function get_cookie($key) {
		global $_W;
		$key = $_W['config']['cookie']['pre'] . $key;
		return json_decode(base64_decode($_COOKIE[$key]), true);
	}
}



if (!function_exists('insert_cookie')) {
	function insert_cookie($key, $data) {
		global $_W, $_GPC;
		$session = base64_encode(json_encode($data));
		setcookie($_W['config']['cookie']['pre'] . $key, $session);
	}
}

//检查用户是否登录
if (!function_exists('check_hotel_user_login')) {
	function check_hotel_user_login($set) {
		global $_W;
		$weid = $_W['uniacid'];
		$from_user = $_W['fans']['from_user'];
		$user_info = hotel_get_userinfo();
		if (empty($user_info['id'])) {
			return 0;
		} else {
			if ($weid != $user_info['weid']) {
				return 0;
			}
			if ($from_user == $user_info['from_user']) {
				if ($set['user'] == 2 && $user_info['user_set'] != 2) {
					return 0;
				} else {
					return 1;
				}
			} else {
				if ($set['bind'] == 1) {
					return 1;
				} elseif ($set['bind'] == 2) {
					return 0;
				} elseif ($set['bind'] == 3) {
					if ($user_info['userbind'] == 0) {
						return 1;
					} else {
						return 0;
					}
				}
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
if (!function_exists('hotel_member_hash')) {
	function hotel_member_hash($input, $salt) {
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
if (!function_exists('hotel_member_check')) {
	function hotel_member_check($member) {
		$sql = "SELECT `password`,`salt` FROM " . tablename('storex_member') . " WHERE 1";
		$params = array();
		if (!empty($member['uid'])) {
			$sql .= " AND `uid` = :uid";
			$params[':uid'] = intval($member['uid']);
		}
		if (!empty($member['weid'])) {
			$sql .= " AND `weid` = :weid";
			$params[':weid'] = intval($member['weid']);
		}
		if (!empty($member['username'])) {
			$sql .= " AND `username` = :username";
			$params[':username'] = $member['username'];
		}
		if (!empty($member['from_user'])) {
			$sql .= " AND `from_user` = :from_user";
			$params[':from_user'] = $member['from_user'];
		}
		if (!empty($member['status'])) {
			$sql .= " AND `status` = :status";
			$params[':status'] = intval($member['status']);
		}
		if (!empty($member['id'])) {
			$sql .= " AND `id` != :id";
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
if (!function_exists('hotel_member_single')) {
	function hotel_member_single($member) {
		$sql = "SELECT * FROM " . tablename('storex_member') . " WHERE 1";
		$params = array();
		if (!empty($member['weid'])) {
			$sql .= " AND `weid` = :weid";
			$params[':weid'] = $member['weid'];
		}
		if (!empty($member['from_user'])) {
			$sql .= " AND `from_user` = :from_user";
			$params[':from_user'] = $member['from_user'];
		}
		if (!empty($member['username'])) {
			$sql .= " AND `username` = :username";
			$params[':username'] = $member['username'];
		}
		if (!empty($member['status'])) {
			$sql .= " AND `status` = :status";
			$params[':status'] = intval($member['status']);
		}
		$sql .= " LIMIT 1";
		$record = pdo_fetch($sql, $params);
		if (empty($record)) {
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



if (!function_exists('get_storex_set')) {
	function get_storex_set() {
		global $_GPC, $_W;
		$cachekey = "wn_storex_set:{$_W['uniacid']}";
		$set = cache_load($cachekey);
		if (!empty($set)) {
			return $set;
		}
		$set = pdo_get('storex_set', array('weid' => intval($_W['uniacid'])));
		if (empty($set)) {
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
		cache_write($cachekey, $set);
		return $set;
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
if (!function_exists('get_page_array')) {
	function get_page_array($tcount, $pindex, $psize = 15) {
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
//完成订单后加售出数量
if (!function_exists('add_sold_num')) {
	function add_sold_num($room) {
		if (intval($_GPC['store_type']) == 1) {
			pdo_update('storex_room', array('sold_num' => ($room['sold_num']+1)), array('id' => $room['id']));
		} else {
			pdo_update('storex_goods', array('sold_num' => ($room['sold_num']+1)), array('id' => $room['id']));
		}
	}
}
//获取房型某天的记录
if (!function_exists('getRoomPrice')) {
	function getRoomPrice($hotelid, $roomid, $date) {
		global $_W;
		$btime = strtotime($date);
		$roomprice = pdo_get('storex_room_price', array('weid' => intval($_W['uniacid']), 'hotelid' => $hotelid, 'roomid' => $roomid, 'roomdate' => $btime));
		if (empty($roomprice)) {
			$room = pdo_get('storex_room', array('hotelid' => $hotelid, 'id' => $roomid, 'weid' => intval($_W['uniacid'])));
			$roomprice = array(
				"weid" => $_W['uniacid'],
				"hotelid" => $hotelid,
				"roomid" => $roomid,
				"oprice" => $room['oprice'],
				"cprice" => $room['cprice'],
				"roomdate" => strtotime($date),
				"thisdate" => $date,
				"num" => "-1",
				"status" => 1,
			);
		}
		return $roomprice;
	}
}

if (!function_exists('gettablebytype')) {
	function gettablebytype($store_type) {
		if ($store_type == 1) {
			return 'storex_room';
		} else {
			return 'storex_goods';
		}
	}
}

//获取订单的商户订单号
if (!function_exists('getOrderUniontid')) {
	function getOrderUniontid(&$lists) {
		if (!empty($lists)) {
			foreach ($lists as $orderkey=>$orderinfo) {
				$paylog = pdo_get('core_paylog', array('uniacid' => $orderinfo['weid'], 'tid' => $orderinfo['id'], 'module' => 'wn_storex'), array('uniacid', 'uniontid', 'tid'));
				if (!empty($paylog)) {
					$lists[$orderkey]['uniontid'] = $paylog['uniontid'];
				}
			}
		}
		return $list;
	}
}

if (!function_exists('format_list')) {
	function format_list($category, $list) {
		if (!empty($category) && !empty($list)) {
			$cate = array();
			foreach ($category as $category_info) {
				$cate[$category_info['id']] = $category_info;
			}
			foreach ($list as $k => $info) {
				if (!empty($cate[$info['pcate']])) {
					$list[$k]['pcate'] = $cate[$info['pcate']]['name'];
				}
				if (!empty($cate[$info['ccate']])) {
					$list[$k]['ccate'] = $cate[$info['ccate']]['name'];
				}
			}
		}
		return $list;
	}
}

if (!function_exists('express_name')) {
	function express_name () {
		return array(
			"shunfeng" => "顺丰",
			"shentong" => "申通",
			"yunda" => "韵达快运",
			"tiantian" => "天天快递",
			"yuantong" => "圆通速递",
			"zhongtong" => "中通速递",
			"ems" => "ems快递",
			"huitongkuaidi" => "汇通快运",
			"quanfengkuaidi" => "全峰快递",
			"zhaijisong" => "宅急送",
			"aae" => "aae全球专递",
			"anjie" => "安捷快递",
			"anxindakuaixi" => "安信达快递",
			"biaojikuaidi" => "彪记快递",
			"bht" => "bht",
			"baifudongfang" => "百福东方国际物流",
			"coe" => "中国东方（COE）",
			"changyuwuliu" => "长宇物流",
			"datianwuliu" => "大田物流",
			"debangwuliu" => "德邦物流",
			"dhl" => "dhl",
			"dpex" => "dpex",
			"dsukuaidi" => "d速快递",
			"disifang" => "递四方",
			"fedex" => "fedex（国外）",
			"feikangda" => "飞康达物流",
			"fenghuangkuaidi" => "凤凰快递",
			"feikuaida" => "飞快达",
			"guotongkuaidi" => "国通快递",
			"ganzhongnengda" => "港中能达物流",
			"guangdongyouzhengwuliu" => "广东邮政物流",
			"gongsuda" => "共速达",
			"hengluwuliu" => "恒路物流",
			"huaxialongwuliu" => "华夏龙物流",
			"haihongwangsong" => "海红",
			"haiwaihuanqiu" => "海外环球",
			"jiayiwuliu" => "佳怡物流",
			"jinguangsudikuaijian" => "京广速递",
			"jixianda" => "急先达",
			"jjwl" => "佳吉物流",
			"jymwl" => "加运美物流",
			"jindawuliu" => "金大物流",
			"jialidatong" => "嘉里大通",
			"jykd" => "晋越快递",
			"kuaijiesudi" => "快捷速递",
			"lianb" => "联邦快递（国内）",
			"lianhaowuliu" => "联昊通物流",
			"longbanwuliu" => "龙邦物流",
			"lijisong" => "立即送",
			"lejiedi" => "乐捷递",
			"minghangkuaidi" => "民航快递",
			"meiguokuaidi" => "美国快递",
			"menduimen" => "门对门",
			"ocs" => "OCS",
			"peisihuoyunkuaidi" => "配思货运",
			"quanchenkuaidi" => "全晨快递",
			"quanjitong" => "全际通物流",
			"quanritongkuaidi" => "全日通快递",
			"quanyikuaidi" => "全一快递",
			"rufengda" => "如风达",
			"santaisudi" => "三态速递",
			"shenghuiwuliu" => "盛辉物流",
			"sue" => "速尔物流",
			"shengfeng" => "盛丰物流",
			"saiaodi" => "赛澳递",
			"tiandihuayu" => "天地华宇",
			"tnt" => "tnt",
			"ups" => "ups",
			"wanjiawuliu" => "万家物流",
			"wenjiesudi" => "文捷航空速递",
			"wuyuan" => "伍圆",
			"wxwl" => "万象物流",
			"xinbangwuliu" => "新邦物流",
			"xinfengwuliu" => "信丰物流",
			"yafengsudi" => "亚风速递",
			"yibangwuliu" => "一邦速递",
			"youshuwuliu" => "优速物流",
			"youzhengguonei" => "邮政包裹挂号信",
			"youzhengguoji" => "邮政国际包裹挂号信",
			"yuanchengwuliu" => "远成物流",
			"yuanweifeng" => "源伟丰快递",
			"yuanzhijiecheng" => "元智捷诚快递",
			"yuntongkuaidi" => "运通快递",
			"yuefengwuliu" => "越丰物流",
			"yad" => "源安达",
			"yinjiesudi" => "银捷速递",
			"zhongtiekuaiyun" => "中铁快运",
			"zhongyouwuliu" => "中邮物流",
			"zhongxinda" => "忠信达",
			"zhimakaimen" => "芝麻开门",
		);
	}
}