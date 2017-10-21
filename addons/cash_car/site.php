<?php

//decode by QQ:1152993137 http://www.webmili.com/
defined('IN_IA') or die('Access Denied');
class cash_carModuleSite extends WeModuleSite
{
	public $_appid = '';
	public $_appsecret = '';
	public $_accountlevel = '';
	public $_weid = '';
	public $_fromuser = '';
	public $_nickname = '';
	public $_headimgurl = '';
	public $_auth2_openid = '';
	public $_auth2_nickname = '';
	public $_auth2_headimgurl = '';
	public $table_blacklist = 'cash_car_blacklist';
	public $table_cart = 'cash_car_cart';
	public $table_category = 'cash_car_category';
	public $table_goods = 'cash_car_goods';
	public $table_goods_evaluate = 'cash_car_goods_evaluate';
	public $table_member_onecard = 'cash_car_member_onecard';
	public $table_member_onecard_log = 'cash_car_member_onecard_log';
	public $table_nave = 'cash_car_nave';
	public $table_onecard = 'cash_car_onecard';
	public $table_onecard_order = 'cash_car_onecard_order';
	public $table_order = 'cash_car_order';
	public $table_order_goods = 'cash_car_order_goods';
	public $table_setting = 'cash_car_setting';
	public $table_sms_template = 'cash_car_sms_template';
	public $table_store = 'cash_car_stores';
	public $table_worker = 'cash_car_stores_worker';
	public $table_store_time = 'cash_car_store_time';
	public $table_onecard_record = 'cash_car_member_onecard_record';
	function __construct()
	{
		global $_W, $_GPC;
		$this->_fromuser = $_W['fans']['from_user'];
		$this->_weid = $_W['uniacid'];
		$account = $_W['account'];
		$this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
		$this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
		$this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];
		$this->_appid = '';
		$this->_appsecret = '';
		$this->_accountlevel = $account['level'];
		if ($this->_accountlevel == 4) {
			$this->_appid = $account['key'];
			$this->_appsecret = $account['secret'];
		}
		$this->systemCheck();
	}
	public function doMobileStoreList()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileStoreShow()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileGoodslist()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileGoodsdetail()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileCashCar()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileAddOrder()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileOrderList()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileOneCardList()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileAddOnecardOrder()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileGiveCard()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileOnecardRecord()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileCancelOrder()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileFinishOrder()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileEvaluate()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileSelf()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileWorkerOrder()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileWOrderlist()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileSendOrder()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobilePay()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function payResult($params)
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$orderid = $params['tid'];
		$setting = pdo_fetch("SELECT * from " . tablename($this->table_setting) . " where weid =:weid LIMIT 1", array(':weid' => $weid));
		$carorder = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = '{$orderid}'");
		if (!empty($carorder)) {
			$store = pdo_fetch("SELECT title FROM " . tablename($this->table_store) . " WHERE id='{$carorder['storeid']}'");
			if (($params['result'] == 'success' && $params['from'] == 'notify' || $params['type'] == 'credit' || $params['fee'] == 0) && $carorder['status'] == 0) {
				$data = array('status' => $params['result'] == 'success' ? 1 : 0);
				$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
				$data['paytype'] = $paytype[$params['type']];
				if ($params['type'] == 'wechat') {
					$data['transid'] = $params['tag']['transaction_id'];
				}
				if ($params['type'] == 'delivery') {
					$data['status'] = 1;
				}
				$data['paytime'] = time();
				pdo_update($this->table_order, $data, array('id' => $orderid));
				$worker = pdo_fetchall("SELECT * FROM " . tablename($this->table_worker) . " WHERE weid='{$weid}' AND storeid='{$carorder['storeid']}' AND isshow=1");
				$goodsid = pdo_fetchall("SELECT goodsid FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$carorder['id']}'", array(), 'goodsid');
				$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
				foreach ($goods as $val) {
					$goods_name .= $val['title'] . '+';
				}
				$goods_name = trim($goods_name, '+');
				if ($setting['istplnotice'] && ($params['type'] == 'credit' || $params['fee'] == 0) && $_SESSION['number'] == 1) {
					$istplnotice = true;
				} elseif ($setting['istplnotice'] && $params['result'] == 'success' && $params['from'] == 'notify') {
					$istplnotice = true;
				}
				if ($setting['istplnotice'] && ($params['type'] == 'credit' || $params['fee'] == 0)) {
					$istplnotice = true;
				} elseif ($setting['istplnotice'] && $params['result'] == 'success' && $params['from'] == 'notify') {
					$istplnotice = true;
				}
				if ($istplnotice) {
					foreach ($worker as $value) {
						$messageDatas = array('touser' => $value['openid'], 'template_id' => $setting['wmessage'], 'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('SendOrder', array('orderid' => $carorder['id'])), 'topcolor' => "#7B68EE", 'data' => array('first' => array('value' => urlencode("您有一条新的洗车预约通知"), 'color' => "#008000"), 'keyword1' => array('value' => urlencode(date('Y-m-d', $carorder['meal_date'])), 'color' => "#428BCA"), 'keyword2' => array('value' => urlencode($carorder['meal_time']), 'color' => "#428BCA"), 'keyword3' => array('value' => urlencode($carorder['mycard']), 'color' => "#428BCA"), 'remark' => array('value' => urlencode("\\n用户姓名：{$carorder['username']}\\n手机号码：{$carorder['tel']}\\n洗车地址：{$carorder['address']}\\n服务项目：{$goods_name}\\n用户留言：{$carorder['remark']}\\n请点击此处进行接单！"), 'color' => "#428BCA")));
						$this->send_template_message(urldecode(json_encode($messageDatas)));
						$t11 = pdo_fetch("SELECT * FROM " . tablename($this->table_sms_template) . " WHERE weid='{$weid}' AND userscene=11");
						if (!empty($t11['content']) && $t11['status'] == 1) {
							$content = str_replace("【ordersn】", $carorder['ordersn'], $t11['content']);
							$content = str_replace("【username】", $carorder['username'], $content);
							$content = str_replace("【carnum】", $carorder['mycard'], $content);
							$content = str_replace("【storename】", $store['title'], $content);
							$geturl = str_replace("【getmobile】", $value['mobile'], $setting['smsurl']);
							$geturl = str_replace("【getcontent】", $content, $geturl);
							$this->http_request($geturl);
						}
					}
					$umessageDatas = array('touser' => $carorder['from_user'], 'template_id' => $setting['cmessage'], 'url' => $_W['siteroot'] . 'app/' . $this->createMobileUrl('orderlist', array('status' => 'alreadypay')), 'topcolor' => "#7B68EE", 'data' => array('first' => array('value' => urlencode("您的洗车订单已支付成功！"), 'color' => "#008000"), 'OrderSn' => array('value' => urlencode($carorder['ordersn']), 'color' => "#428BCA"), 'OrderStatus' => array('value' => urlencode("已付款"), 'color' => "#428BCA"), 'remark' => array('value' => urlencode("您的订单已支付成功，请等待工作人员接收订单！"), 'color' => "#428BCA")));
					$this->send_template_message(urldecode(json_encode($umessageDatas)));
					$t1 = pdo_fetch("SELECT * FROM " . tablename($this->table_sms_template) . " WHERE weid='{$weid}' AND userscene=1");
					if (!empty($t1['content']) && $t1['status'] == 1) {
						$content = str_replace("【ordersn】", $carorder['ordersn'], $t1['content']);
						$content = str_replace("【username】", $carorder['username'], $content);
						$content = str_replace("【carnum】", $carorder['mycard'], $content);
						$content = str_replace("【storename】", $store['title'], $content);
						$geturl = str_replace("【getmobile】", $carorder['tel'], $setting['smsurl']);
						$geturl = str_replace("【getcontent】", $content, $geturl);
						$this->http_request($geturl);
					}
				}
			}
			if ($params['from'] == 'return') {
				message('支付成功，系统正在为您派单！', $this->createMobileUrl('orderlist', array('op' => 'display', 'status' => 'alreadypay')), 'success');
			}
		} else {
			$onecarorder = pdo_fetch("SELECT * FROM " . tablename($this->table_onecard_order) . " WHERE id = '{$orderid}'");
			if (!empty($onecarorder)) {
				if (($params['result'] == 'success' && $params['from'] == 'notify' || $params['type'] == 'credit') && $onecarorder['status'] == 0) {
					$data = array('status' => $params['result'] == 'success' ? 1 : 0);
					$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
					$data['paytype'] = $paytype[$params['type']];
					pdo_update($this->table_onecard_order, $data, array('id' => $orderid));
					if (!empty($onecarorder['validity'])) {
						$validity = time() + $onecarorder['validity'] * 24 * 3600;
					}
					$cards = pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND onlycard=:onlycard", array(':uid' => $onecarorder['uid'], 'weid' => $weid, ':onlycard' => $onecarorder['onlycard']));
					$new_param = array('uid' => $onecarorder['uid'], 'weid' => $weid, 'from_user' => $onecarorder['from_user'], 'title' => $onecarorder['title'], 'price' => round($onecarorder['amount'] / $onecarorder['number'], 2), 'number' => $onecarorder['number'], 'onlycard' => $onecarorder['onlycard'], 'validity' => $validity);
					$old_param = array('title' => $onecarorder['title'], 'price' => round($onecarorder['amount'] / $onecarorder['number'], 2), 'number' => $onecarorder['number'], 'validity' => $validity);
					if (empty($cards)) {
						pdo_insert($this->table_member_onecard, $new_param);
					} else {
						pdo_update($this->table_member_onecard, $old_param, array('id' => $cards['id']));
					}
					$onecard_record = array('weid' => $weid, 'uid' => $onecarorder['uid'], 'openid' => $onecarorder['from_user'], 'title' => $onecarorder['title'], 'reduce' => $onecarorder['number'], 'total' => $onecarorder['number'], 'remark' => "购买洗车卡[" . $onecarorder['order_sn'] . "]", 'add_time' => time());
					pdo_insert($this->table_onecard_record, $onecard_record);
				}
				if ($params['from'] == 'return') {
					message('支付成功！', $this->createMobileUrl('onecardlist', array('op' => 'mycard')), 'success');
				}
			}
		}
	}
	public function doMobileUpdatecartnumber()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doMobileGetAddrAjax()
	{
		$this->__mobile(__FUNCTION__);
	}
	public function doWebSetting()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebStore()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebOrder()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebGoods()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebCategory()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebBlacklist()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebAllOrder()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebMenlist()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebNewmen()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebOnecard()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebBusiness()
	{
		$this->__web(__FUNCTION__);
	}
	public function doWebSms()
	{
		$this->__web(__FUNCTION__);
	}
	public function __web($f_name)
	{
		global $_W, $_GPC;
		checklogin();
		$weid = $_W['uniacid'];
		$op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid =:weid LIMIT 1", array(':weid' => $weid));
		include_once 'web/' . strtolower(substr($f_name, 5)) . '.php';
	}
	public function __mobile($f_name)
	{
		global $_W, $_GPC;
		$op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid =:weid LIMIT 1", array(':weid' => $_W['uniacid']));
		$sharelink = unserialize($setting['sharelink']);
		include_once 'mobile/' . strtolower(substr($f_name, 8)) . '.php';
	}
	public function showMessageAjax($msg, $code = 0)
	{
		$result['code'] = $code;
		$result['msg'] = $msg;
		message($result, '', 'ajax');
	}
	public function showMsg($msg, $status = 0)
	{
		$result = array('msg' => $msg, 'status' => $status);
		echo json_encode($result);
		die;
	}
	public function oauth2()
	{
		global $_GPC, $_W;
		load()->func('communication');
		$code = $_GPC['code'];
		$token = $this->getAuthorizationCode($code);
		$from_user = $token['openid'];
		$userinfo = $this->getUserInfo($from_user);
		if ($userinfo['subscribe'] == 0) {
			$userinfo = $this->getUserInfo($from_user, $token['access_token']);
		}
		if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
			echo '<h1>获取微信公众号授权失败[无法取得userinfo], 请稍后重试！ 公众平台返回原始数据为: <br />' . $userinfo['meta'] . '<h1>';
			die;
		}
		$headimgurl = $userinfo['headimgurl'];
		$nickname = $userinfo['nickname'];
		setcookie($this->_auth2_headimgurl, $headimgurl);
		setcookie($this->_auth2_nickname, $nickname);
		setcookie($this->_auth2_openid, $from_user);
		return $userinfo;
	}
	public function getUserInfo($from_user, $ACCESS_TOKEN = '')
	{
		if ($ACCESS_TOKEN == '') {
			load()->classs('weixin.account');
			$accObj = WeixinAccount::create($_W['account']['acid']);
			$ACCESS_TOKEN = $accObj->fetch_token();
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
		} else {
			$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
		}
		$json = ihttp_get($url);
		$userInfo = @json_decode($json['content'], true);
		return $userInfo;
	}
	public function getAuthorizationCode($code)
	{
		$oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
		$content = ihttp_get($oauth2_code);
		$token = @json_decode($content['content'], true);
		if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
			echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
			die;
		}
		return $token;
	}
	public function getCode($url)
	{
		global $_W;
		$url = urlencode($url);
		$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
		header("location:{$oauth2_code}");
	}
	public $actions_titles = array('stores' => '返回服务点管理', 'order' => '订单管理');
	public function insert_default_nave($name, $type, $link)
	{
		global $_GPC, $_W;
		checklogin();
		$data = array('weid' => $_W['uniacid'], 'type' => $type, 'name' => $name, 'link' => $link, 'displayorder' => 0, 'status' => 1);
		$nave = pdo_fetch("SELECT * FROM " . tablename($this->table_nave) . " WHERE name = :name AND weid=:weid", array(':name' => $name, ':weid' => $_W['uniacid']));
		if (empty($nave)) {
			pdo_insert($this->table_nave, $data);
		}
		return pdo_insertid();
	}
	public function check_black_list()
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_blacklist) . " WHERE weid=:weid AND from_user=:from_user LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
		if (!empty($item) && $item['status'] == 0) {
			message('你在黑名单中,不能进行相关操作，如有疑问，请联系管理员！');
		}
	}
	public function checkorder($meal_date, $starttime, $storeid, $type, $order)
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid={$weid} LIMIT 1");
		$store = pdo_fetch("SELECT * FROM " . tablename($this->table_store) . " WHERE id=:id", array(':id' => $storeid));
		$today = strtotime("today");
		$istoday = $meal_date == $today ? true : false;
		$dayoff = explode("/", $store['dayoff']);
		$daytimes = date("d", $meal_date);
		if (in_array($daytimes, $dayoff)) {
			$this->showMessageAjax("当前预约日期为服务点休息日，请选择其他预约日期~");
		}
		if ($meal_date >= $today + $setting['book_days'] * 24 * 3600 || $meal_date < $today) {
			if ($type == 'old') {
				pdo_update($this->table_order, array('status' => '-1'), array('id' => $order['id']));
				message('该订单已失效！');
			} elseif ($type == 'new') {
				$this->showMessageAjax('预约日期超出范围！');
			}
		}
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_order) . " WHERE weid = '{$weid}' AND status>0 AND storeid={$storeid} AND meal_date='{$meal_date}' AND meal_timestamp='{$starttime}'");
		if ($istoday && $total >= intval($store['hours_time'])) {
			if ($type == 'old') {
				pdo_update($this->table_order, array('status' => '-1'), array('id' => $order['id']));
				message('该时间段订单量已满！');
			} elseif ($type == 'new') {
				$this->showMessageAjax('该时间段订单已满，试试其他时间段~');
			}
		}
		$min = $store['bookingtime'] > 0 ? (60 - $store['bookingtime']) * 60 : 3600;
		if ($istoday && time() > $starttime + $min) {
			if ($type == 'old') {
				pdo_update($this->table_order, array('status' => '-1'), array('id' => $order['id']));
				message('该时间段预约已结束！');
			} elseif ($type == 'new') {
				$this->showMessageAjax('该时间段预约已结束~');
			}
		}
	}
	public function set_tabbar($action, $storeid)
	{
		$actions_titles = $this->actions_titles;
		$html = '<ul class="nav nav-tabs">';
		foreach ($actions_titles as $key => $value) {
			$url = $this->createWebUrl($key, array('op' => 'display', 'storeid' => $storeid));
			$html .= '<li class="' . ($key == $action ? 'active' : '') . '"><a href="' . $url . '">' . $value . '</a></li>';
		}
		$html .= '</ul>';
		return $html;
	}
	public function message($error, $url = '', $errno = -1)
	{
		$data = array();
		$data['errno'] = $errno;
		if (!empty($url)) {
			$data['url'] = $url;
		}
		$data['error'] = $error;
		echo json_encode($data);
		die;
	}
	private function getDistance($lat1, $lng1, $lat2, $lng2)
	{
		$earthRadius = 6367000;
		$lat1 = $lat1 * pi() / 180;
		$lng1 = $lng1 * pi() / 180;
		$lat2 = $lat2 * pi() / 180;
		$lng2 = $lng2 * pi() / 180;
		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
		$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
		$calculatedDistance = $earthRadius * $stepTwo;
		return round($calculatedDistance);
	}
	private function uploadpic($upfile)
	{
		$cash_car = "../attachment/images/cash_car/";
		$y = $cash_car . date("Y", time()) . "/";
		$m = $y . date("m", time()) . "/";
		$d = $m . date("d", time()) . "/";
		$this->checkdir($cash_car);
		$this->checkdir($y);
		$this->checkdir($m);
		$this->checkdir($d);
		for ($i = 0; $i < 6; $i++) {
			$name = $upfile["name"][$i];
			$type = $upfile["type"][$i];
			$size = $upfile["size"][$i];
			$tmp_name = $upfile["tmp_name"][$i];
			if (!empty($name) && in_array($type, array("image/jpeg", "image/png"))) {
				$error = $upfile["error"][$i];
				$tmp = explode(".", $name);
				$paths = $d . date('YmdHis', time()) . rand(10000, 99999) . "." . $tmp[1];
				move_uploaded_file($tmp_name, $paths);
				$name = str_replace("../attachment/", "", $paths);
				$this->resize($paths, $paths, "1400", "1400", "100");
				$images .= $name . ',';
			}
		}
		return $images;
	}
	private function checkdir($path)
	{
		if (!file_exists($path)) {
			mkdir($path, 511);
		}
	}
	function resize($srcImage, $toFile, $maxWidth = 1024, $maxHeight = 1024, $imgQuality = 100)
	{
		list($width, $height, $type, $attr) = getimagesize($srcImage);
		if ($width < $maxWidth || $height < $maxHeight) {
			return;
		}
		switch ($type) {
			case 1:
				$img = imagecreatefromgif($srcImage);
				break;
			case 2:
				$img = imagecreatefromjpeg($srcImage);
				break;
			case 3:
				$img = imagecreatefrompng($srcImage);
				break;
		}
		$scale = min($maxWidth / $width, $maxHeight / $height);
		if ($scale < 1) {
			$newWidth = floor($scale * $width);
			$newHeight = floor($scale * $height);
			$newImg = imagecreatetruecolor($newWidth, $newHeight);
			imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
			$newName = "";
			$toFile = preg_replace("/(.jpg|.jpeg|.png)/i", "", $toFile);
			switch ($type) {
				case 1:
					if (imagegif($newImg, "{$toFile}{$newName}.gif", $imgQuality)) {
						return "{$newName}.gif";
					}
					break;
				case 2:
					if (imagejpeg($newImg, "{$toFile}{$newName}.jpg", $imgQuality)) {
						return "{$newName}.jpg";
					}
					break;
				case 3:
					if (imagepng($newImg, "{$toFile}{$newName}.png", $imgQuality)) {
						return "{$newName}.png";
					}
					break;
				default:
					if (imagejpeg($newImg, "{$toFile}{$newName}.jpg", $imgQuality)) {
						return "{$newName}.jpg";
					}
					break;
			}
			imagedestroy($newImg);
		}
		imagedestroy($img);
		return false;
	}
	private function object_array($array)
	{
		if (is_object($array)) {
			$array = (array) $array;
		}
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				$array[$key] = $this->object_array($value);
			}
		}
		return $array;
	}
	private function array_sort($arr, $keys, $type = 'asc')
	{
		$keysvalue = $new_array = array();
		foreach ($arr as $k => $v) {
			$keysvalue[$k] = $v[$keys];
		}
		if ($type == 'asc') {
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
	private function send_template_message($messageDatas)
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['account']['acid']);
		$access_token = $accObj->fetch_token();
		$urls = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
		$ress = $this->http_request($urls, $messageDatas);
		return json_decode($ress, true);
	}
	private function http_request($url, $messageDatas = null)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($messageDatas)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $messageDatas);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	private function systemCheck()
	{
		global $_W;
		load()->model('mc');
		$weid = $this->_weid;
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid ORDER BY id DESC LIMIT 1", array(':weid' => $weid));
		if (time() > $setting['lastcheck'] + $setting['check_space'] && $setting['check_space'] > 0) {
			$time = time() - $setting['check_space'];
			$nopay_order = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE weid='{$weid}' AND status=0 AND dateline<'{$time}'");
			foreach ($nopay_order as $item) {
				$order_goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE orderid='{$item[id]}'");
				if ($item['usecard'] == 1) {
					$uid = mc_openid2uid($item['from_user']);
					foreach ($order_goods as $goods) {
						if (!empty($goods['onlycard'])) {
							$membercard = pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid AND onlycard=:onlycard", array(':uid' => $uid, ':weid' => $weid, 'onlycard' => $goods['onlycard']));
							$number = array('number' => $membercard['number'] + 1);
							$condition = array('uid' => $uid, 'weid' => $weid, 'onlycard' => $goods['onlycard']);
							pdo_update($this->table_member_onecard, $number, $condition);
							$onecard_record = array('weid' => $weid, 'uid' => $uid, 'openid' => $item['from_user'], 'title' => $membercard['title'], 'reduce' => '1', 'total' => $membercard['number'] + 1, 'remark' => "取消洗车订单[" . $item['ordersn'] . "]", 'add_time' => time());
							pdo_insert($this->table_onecard_record, $onecard_record);
						}
						unset($membercard);
					}
				}
				pdo_update($this->table_order, array('status' => '-1'), array('id' => $item['id']));
			}
			pdo_update($this->table_setting, array('lastcheck' => time()), array('id' => $setting['id']));
		}
		$refuse = unserialize($setting['refuseorder']);
		if (time() > $refuse['refusespace'] + $refuse['uptime'] && $refuse['refusespace'] > 0) {
			$paytime = time() - $refuse['refusespace'];
			$pay_order = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE weid='{$weid}' AND status=1 AND paytime<'{$paytime}'");
			foreach ($pay_order as $item) {
				$uid = mc_openid2uid($item['from_user']);
				$member = pdo_fetch("SELECT uid,credit2 FROM " . tablename('mc_members') . " WHERE uid='{$uid}'");
				$result = pdo_update($this->table_order, array('status' => '-1'), array('id' => $item['id']));
				if ($result) {
					$order_goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_order_goods) . " WHERE orderid='{$item['id']}'");
					if ($item['status'] == 1) {
						foreach ($order_goods as $goods) {
							if (!empty($goods['onlycard'])) {
								$membercard = pdo_fetch("SELECT * FROM " . tablename($this->table_member_onecard) . " WHERE uid=:uid AND weid=:weid  AND onlycard=:onlycard", array(':uid' => $uid, ':weid' => $weid, 'onlycard' => $goods['onlycard']));
								$number = array('number' => $membercard['number'] + 1);
								$condition = array('uid' => $uid, 'weid' => $weid, 'onlycard' => $goods['onlycard']);
								pdo_update($this->table_member_onecard, $number, $condition);
								$onecard_record = array('weid' => $weid, 'uid' => $uid, 'openid' => $item['from_user'], 'title' => $membercard['title'], 'reduce' => '1', 'total' => $membercard['number'] + 1, 'remark' => "系统取消超时未接订单[" . $item['ordersn'] . "]", 'add_time' => time());
								pdo_insert($this->table_onecard_record, $onecard_record);
							} else {
								$reftotal += $goods['price'];
							}
							unset($membercard);
						}
						if ($reftotal > 0) {
							pdo_update('mc_members', array('credit2' => $member['credit2'] + $reftotal), array('uid' => $uid));
							$credits_log = array('uid' => $uid, 'uniacid' => $weid, 'credittype' => 'credit2', 'num' => +$reftotal, 'operator' => '0', 'createtime' => time(), 'remark' => '系统取消洗车订单：' . $item['id']);
							pdo_insert('mc_credits_record', $credits_log);
						}
						if ($setting['istplnotice']) {
							$meal_date = date('Y-m-d', $item['meal_date']);
							$goodsid = pdo_fetchall("SELECT goodsid FROM " . tablename($this->table_order_goods) . " WHERE orderid = '{$item['id']}'", array(), 'goodsid');
							$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . "  WHERE id IN ('" . implode("','", array_keys($goodsid)) . "')");
							foreach ($goods as $val) {
								$goods_name .= $val['title'] . '+';
							}
							$goods_name = trim($goods_name, '+');
							$userMessage = array('touser' => $item['from_user'], 'template_id' => $setting['cmessage'], 'url' => $_W['siteroot'] . 'app/' . "index.php?i={$weid}&c=entry&do=orderlist&m=cash_car", 'topcolor' => "#7B68EE", 'data' => array('first' => array('value' => urlencode("[系统取消洗车工超时未接订单通知]由于您的预约订单未被接收，系统自动为您取消订单。"), 'color' => "#E43935"), 'OrderSn' => array('value' => urlencode($item['ordersn']), 'color' => "#428BCA"), 'OrderStatus' => array('value' => urlencode("已取消"), 'color' => "#2E3A42"), 'remark' => array('value' => urlencode("\\n订单详细信息\\n用户姓名：{$item['username']}\\n预约日期：{$meal_date}\\n预约时段：{$item['meal_time']}\\n预约车牌：{$item['mycard']}\\n手机号码：{$item['tel']}\\n洗车地址：{$item['address']}\\n服务项目：{$goods_name}"), 'color' => "#428BCA")));
							$this->send_template_message(urldecode(json_encode($userMessage)));
							$t5 = pdo_fetch("SELECT * FROM " . tablename($this->table_sms_template) . " WHERE weid='{$weid}' AND userscene=5");
							if (!empty($t5['content']) && $t5['status'] == 1) {
								$store = pdo_fetch("SELECT title FROM " . tablename($this->table_store) . " WHERE id='{$item['storeid']}'");
								$content = str_replace("【ordersn】", $item['ordersn'], $t5['content']);
								$content = str_replace("【username】", $item['username'], $content);
								$content = str_replace("【carnum】", $item['mycard'], $content);
								$content = str_replace("【storename】", $store['title'], $content);
								$geturl = str_replace("【getmobile】", $item['tel'], $setting['smsurl']);
								$geturl = str_replace("【getcontent】", $content, $geturl);
								$rs = $this->http_request($geturl);
							}
						}
					}
				}
			}
			$refuseorder = serialize(array('refusespace' => $refuse['refusespace'], 'uptime' => time()));
			pdo_update($this->table_setting, array('refuseorder' => $refuseorder), array('id' => $setting['id']));
		}
	}
	protected function exportexcel($data = array(), $title = array(), $filename = 'report')
	{
		header("Content-type:application/octet-stream");
		header("Accept-Ranges:bytes");
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=" . $filename . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		if (!empty($title)) {
			foreach ($title as $k => $v) {
				$title[$k] = iconv("UTF-8", "GB2312", $v);
			}
			$title = implode("\t", $title);
			echo "{$title}\n";
		}
		if (!empty($data)) {
			$totalprice = 0;
			foreach ($data as $key => $val) {
				foreach ($val as $ck => $cv) {
					$data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
				}
				$data[$key] = implode("\t", $data[$key]);
				$totalprice += $val['amount'];
			}
			echo implode("\n", $data);
		}
	}
	function mpagination($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 1, 'after' => 1, 'ajaxcallback' => ''))
	{
		global $_W;
		$pdata = array('tcount' => 0, 'tpage' => 0, 'cindex' => 0, 'findex' => 0, 'pindex' => 0, 'nindex' => 0, 'lindex' => 0, 'options' => '');
		$pdata['tcount'] = $tcount;
		$pdata['tpage'] = ceil($tcount / $psize);
		if ($pdata['tpage'] <= 1) {
			return '';
		}
		$cindex = $pindex;
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
			$pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
			$pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
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
		$html = '<div class="pagination pagination-centered"><ul>';
		if ($pdata['cindex'] > 1) {
			$html .= "<li><a {$pdata['faa']} class=\"pager-nav\">首页</a></li>";
			$html .= "<li><a {$pdata['paa']} class=\"pager-nav\">上一页</a></li>";
		} else {
			$html .= "<li><a class=\"pager-nav no-click\">首页</a></li>";
			$html .= "<li><a class=\"pager-nav no-click\">上一页</a></li>";
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
					$aa = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', ' . $context['ajaxcallback'] . ')"';
				} else {
					if ($url) {
						$aa = 'href="?' . str_replace('*', $i, $url) . '"';
					} else {
						$_GET['page'] = $i;
						$aa = 'href="?' . http_build_query($_GET) . '"';
					}
				}
			}
		}
		if ($pdata['cindex'] < $pdata['tpage']) {
			$html .= "<li><a {$pdata['naa']} class=\"pager-nav\">下一页</a></li>";
			$html .= "<li><a {$pdata['laa']} class=\"pager-nav\">尾页</a></li>";
		} else {
			$html .= "<li><a class=\"pager-nav no-click\">下一页</a></li>";
			$html .= "<li><a class=\"pager-nav no-click\">尾页</a></li>";
		}
		$html .= '</ul></div>';
		return $html;
	}
}