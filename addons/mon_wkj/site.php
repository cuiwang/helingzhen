<?php
/**
 * codeMonkey:631872807
 */
defined('IN_IA') or exit('Access Denied');
define("MON_WKJ", "mon_wkj");
define("MON_WKJ_RES", "../addons/" . MON_WKJ . "/");
require_once IA_ROOT . "/addons/" . MON_WKJ . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_WKJ . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_WKJ . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_WKJ . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_WKJ . "/WxPayPubHelper/WxPayPubHelper.php";

/**
 * Class Mon_WkjModuleSite
 */
class Mon_WkjModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__monwkjuserv1";

	public static $KJ_STATUS_WKS = 0;//未开始
	public static $KJ_STATUS_ZC = 1;//正常
	public static $KJ_STATUS_JS = 2;//结束
	public static $KJ_STATUS_XD = 3;//已下单
	public static $KJ_STATUS_GM = 4;//已支付
	public static $KJ_STATUS_YFH = 5;//已发货

	public static $TIP_DIALOG = 1;//对话框
	public static $TIP_U_FIRST = 2;//首次
	public static $TIP_U_ALREADY = 3;//已经砍过了
	public static $TIP_RANK = 4;//页面
	public static $TIP_FK_FIRST = 5;//好友
	public static $TIP_FK_ALREADY = 6;//好友已看
	public $kjSetting;

	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$this->kjSetting = $this->findKJsetting();
		$this->oauth = new Oauth2($this->kjSetting['appid'], $this->kjSetting['appsecret']);
	}


	/************************************************手机*********************************/
	/**
	 * author: codeMonkey QQ:631872807
	 * 用户砍价页
	 */
	public function  doMobileIndex()
	{
		global $_W, $_GPC;
		$kid = $_GPC['kid'];
		$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
		MonUtil::emtpyMsg($wkj, "砍价活动不存在或已删除");
		$join = false;
		MonUtil::checkmobile();
		$openid = $_GPC['openid'];
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $kid);
		$joinUser = $this->findJoinUser($kid, $openid);
		if (!empty($joinUser)) {
			$join = true;
		}
		$date_time_array = getdate($wkj['endtime']);
		$hours = $date_time_array["hours"];
		$minutes = $date_time_array["minutes"];
		$seconds = $date_time_array["seconds"];
		$month = $date_time_array["mon"];
		$day = $date_time_array["mday"];
		$year = $date_time_array["year"];
		$shareUrl = $this->getShareUrl($wkj['id']);
		$status = $this->getStatus($wkj, $joinUser);
		$statusText = $this->getStatusText($status);
		$u_fist_tip = $this->getTipMsg($wkj, $this::$TIP_U_FIRST);
		$u_already_tip = $this->getTipMsg($wkj, $this::$TIP_U_ALREADY);
		// include $this->template("zf_index");


		header("Cache-control:no-cache,no-store,must-revalidate");
		header("Pragma:no-cache");
		header("Expires:0");

		include $this->template('index');

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * 支付
	 */
	public function doMobileZf_Demo()
	{
		global $_W;
		$jsApi = new JsApi_pub($this->kjSetting);
		if (!isset($_GET['code'])) {
			//触发微信返回code码
			$reurl = MonUtil::str_murl($this->createMobileUrl('Zf_Demo', array(), true));
			$url = $jsApi->createOauthUrlForCode(urlencode($reurl));
			Header("Location: $url");
		} else {

//获取code码，以获取openid
			$code = $_GET['code'];
			$jsApi->setCode($code);
			$openid = $jsApi->getOpenId();
		}


		$unifiedOrder = new UnifiedOrder_pub($this->kjSetting);


		$unifiedOrder->setParameter("openid", "$openid");//商品描述
		$unifiedOrder->setParameter("body", "测试贡献一分钱");//商品描述
//自定义订单号，此处仅作举例
		$timeStamp = time();
		$out_trade_no = WxPayConf_pub::APPID . "$timeStamp";
		$unifiedOrder->setParameter("out_trade_no", "$out_trade_no");//商户订单号
		$unifiedOrder->setParameter("total_fee", 1);//总金额
		$notifyUrl = $_W['siteroot'] . "addons/" . MON_WKJ . "/notify.php";

		$unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
		$unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型


//非必填参数，商户可根据实际情况选填
//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
//$unifiedOrder->setParameter("device_info","XXXX");//设备号
//$unifiedOrder->setParameter("attach","XXXX");//附加数据
//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
//$unifiedOrder->setParameter("openid","XXXX");//用户标识
//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

		$prepay_id = $unifiedOrder->getPrepayId();


//=========步骤3：使用jsapi调起支付============

		$jsApi->setPrepayId($prepay_id);

		$jsApiParameters = $jsApi->getParameters();


		include $this->template("zf");
	}


	/**
	 * author: codeMonkey QQ:631872807
	 * 砍价页面
	 */
	public function doMobileKj()
	{

		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$fopenid = $_GPC['openid'];
		$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
		MonUtil::emtpyMsg($wkj, "砍价活动不存在或已删除!");
		$joinUser = DBUtil::findById(DBUtil::$TABLE_WKJ_USER, $uid);
		MonUtil::emtpyMsg($joinUser, "用户删除或不存在!");
		$firend = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $kid);
		if ($firend['openid'] == $joinUser['openid']) {//自己点了自己分析的链接
			$indexUrl = MonUtil::str_murl($this->createMobileUrl('index', array('kid' => $kid, 'openid' => $fopenid), true));
			header("location: $indexUrl");
		}

		$joinFirend = $this->findJoinUser($kid, $firend['openid']);
		$firendJoined = false;
		if (!empty($joinFirend)) {
			$firendJoined = true;// 哥们自己有参加过活动
		}

		$dbFirend = $this->findHelpFirend($uid, $firend['openid']);
		$helped = false;
		if (!empty($dbFirend)) {
			$helped = true;//已经帮好友看过了。。。。。
		}

		$date_time_array = getdate($wkj['endtime']);
		$hours = $date_time_array["hours"];
		$minutes = $date_time_array["minutes"];
		$seconds = $date_time_array["seconds"];
		$month = $date_time_array["mon"];
		$day = $date_time_array["mday"];
		$year = $date_time_array["year"];


		$shareUrl = $this->getShareUrl($wkj['id']);


		$status = $this->getStatus($wkj, $joinUser);
		$statusText = $this->getStatusText($status);


		$fk_fist_tip = $this->getTipMsg($wkj, $this::$TIP_FK_FIRST);
		$fk_already_tip = $this->getTipMsg($wkj, $this::$TIP_FK_ALREADY);

		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

		include $this->template("firend_kj");


	}


	public function getShareUrl($kid)
	{

		return MonUtil::str_murl($this->createMobileUrl('Auth', array('kid' => $kid, 'au' => Value::$REDIRECT_KJ), true));


	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 砍价
	 */
	public function  doMobileAjax()
	{

		global $_GPC;
		MonUtil::checkmobile();
		$action = $_GPC['action'];
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		if ($action == 'kanjia') {//砍价
			echo $this->doKj($kid, $uid);
		} else if ($action == 'passgoumai') {//购买

			$gmCount = $this->getOrderCount1($kid);
			$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
			$leftCount = $wkj['p_kc'] - $gmCount;

			if ($leftCount <= 0) {
				echo '{"code":200,"msg":"剩余商品为0了，下次再来参与活动吧!","status":0,"dingdanid":"119201504151139359","ret":1,"action":"passgoumai"}';

				exit;
			}

			echo '{"code":200,"msg":"已成功下单，等待你的付款!","status":1,"dingdanid":"119201504151139359","ret":1,"action":"passgoumai"}';
		} else if ($action == 'zf') {
			$gmCount = $this->getOrderCount1($kid);
			$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
			$leftCount = $wkj['p_kc'] - $gmCount;

			if ($leftCount <= 0) {
				echo '{"code":500,"msg":"剩余商品为0了，下次再来购买吧!"}';

				exit;
			}

			echo '{"code":200}';

		}

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @return string
	 * 砍价方法
	 */
	public function  doKj($kid, $uid)
	{
		global $_W;
		$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
		$res = array();
		if (empty($wkj)) {
			$res['code'] = 501;
			$res['msg'] = "活动删除或不存在";
			return json_encode($res);

		}

		if (TIMESTAMP < $wkj['starttime']) {
			$res['code'] = 502;
			$res['msg'] = "活动还未开始呢!";
			return json_encode($res);

		}

		if (TIMESTAMP > $wkj['endtime']) {
			$res['code'] = 503;
			$res['msg'] = "活动已经结束了，下次再参加吧!";
			return json_encode($res);

		}
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $kid);
		if(empty($userInfo)) {
			$res['code'] = 504;
			$res['msg'] = "请授权登录后再进行砍价!";
			return json_encode($res);
		}
		if (empty($uid)) {//首次参加

			$dbJoinUser=DBUtil::findUnique(DBUtil::$TABLE_WKJ_USER,array(":kid"=>$kid,":openid"=>$userInfo['openid']));
			if(empty($dbJoinUser)) {
				$userData = array(
					'kid' => $wkj['id'],
					'openid' => $userInfo['openid'],
					'nickname' => $userInfo['nickname'],
					'headimgurl' => $userInfo['headimgurl'],
					'price' => $wkj['p_y_price'],
					'ip' => $_W['clientip'],
					'createtime' => TIMESTAMP
				);
				DBUtil::create(DBUtil::$TABLE_WKJ_USER, $userData);//注册用户
				$uid = pdo_insertid();//用户ID

				$kj_res = $this->createFirendKj($wkj, $uid, $userInfo['openid'], $userInfo['nickname'], $userInfo['headimgurl']);
			} else {
				$uid=$dbJoinUser['id'];
				$kj_res=array("0","你已经参加过了，不能再给自己砍了，请重新进入活动或刷新要页面");
			}


		} else {
			$kj_res = $this->createFirendKj($wkj, $uid, $userInfo['openid'], $userInfo['nickname'], $userInfo['headimgurl']);
		}

		$json = '{"code":200,"uid":' . $uid . ',"msg":"' . $kj_res[1] . '","ret":1,"status":1,"kanjiaPrice":' . $kj_res[0] . ',"action":"kanjia"}';


		return $json;

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $uid
	 * @param $fopenid
	 * @param $fnickname
	 * @param $fheadimgurl
	 * 砍价信息
	 */
	public function  createFirendKj($wkj, $uid, $fopenid, $fnickname, $fheadimgurl)
	{
		global $_W;
		$helpFriendCount = $this->getHelpFriendCount($wkj['id'], $fopenid);
		if ($helpFriendCount >= $wkj['friend_help_limit']) {
			return array(0, "您已帮助".$wkj['friend_help_limit']."好友砍价了，不能再继续帮助啦！");
		}

		$dbFirend = $this->findHelpFirend($uid, $fopenid);
		$user = DBUtil::findById(DBUtil::$TABLE_WKJ_USER, $uid);
		if ($user['price'] <= $wkj['p_low_price']) {
			return array(0, "好友砍价过猛已经最低价啦，下次再来帮好友砍吧！");
		}
		$k_price = $this->getKjPrice($wkj, $user['price']);
		if ($user['price'] - $k_price < $wkj['p_low_price']) {//最低价控制
			//$k_price = 0;
		}
		if (empty($dbFirend)) {


			$leftPrice = $user['price'] - $k_price;
			if ($leftPrice <= $wkj['p_low_price']) {
				$leftPrice = $wkj['p_low_price'];
			}
			$helpFirend = array(
				'kid' => $wkj['id'],
				'uid' => $uid,
				'openid' => $fopenid,
				'nickname' => $fnickname,
				'headimgurl' => $fheadimgurl,
				'k_price' => $k_price,
				'kh_price' => $leftPrice,
				'ip' => $_W['clientip'],
				'createtime' => TIMESTAMP
			);

			DBUtil::create(DBUtil::$TABLE_WKJ_FIREND, $helpFirend);
			DBUtil::updateById(DBUtil::$TABLE_WKJ_USER, array('price' => $leftPrice), $uid);
		} else{
			return array(0, "已经帮好友砍过价了，下次再继续吧！！");
		}


		return array($k_price, $this->getTipMsg($wkj, $this::$TIP_DIALOG));

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * 排行
	 */
	public function doMobileRanking()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
		$user = DBUtil::findById(DBUtil::$TABLE_WKJ_USER, $uid);
		$date_time_array = getdate($wkj['endtime']);
		$hours = $date_time_array["hours"];
		$minutes = $date_time_array["minutes"];
		$seconds = $date_time_array["seconds"];
		$month = $date_time_array["mon"];
		$day = $date_time_array["mday"];
		$year = $date_time_array["year"];
		$params = array(':kid' => $kid, ':uid' => $uid);
		$firends = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_WKJ_FIREND) . " WHERE kid =:kid and uid=:uid  ORDER BY createtime DESC, id DESC ", $params);
		$ftotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_WKJ_FIREND) . " WHERE kid =:kid and  uid=:uid", $params);
		$fopenid = $_GPC['fopenid'];

		$firendJoined = false;
		if (!empty($fopenid)) {
			$joinFirend = $this->findJoinUser($kid, $fopenid);//查找该帮助人有没有参加过活动
			if (!empty($joinFirend)) {
				$firendJoined = true;// 哥们自己有参加过活动
			}

		}

		$status = $this->getStatus($wkj, $user);
		$statusText = $this->getStatusText($status);
		$rank_tip = $this->getTipMsg($wkj, $this::$TIP_RANK);

		if ($status == $this::$KJ_STATUS_XD || $status == $this::$KJ_STATUS_GM) {
			$orderInfo = $this->findOrderInfo($kid, $uid);
		}

		if ($wkj['pay_type'] == 1) {//立即支付
			$leftCount = $wkj['p_kc'] - $this->getOrderCount1($kid);
		}

		if ($wkj['pay_type'] == 2) {//货到付款
			$leftCount = $wkj['p_kc'] - $this->getOrderCount($kid);
		}

		if ($leftCount < 0) {
			$leftCount = 0;
		}

		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		$shareUrl = $this->getShareUrl($wkj['id']);
		include $this->template("ranking");

	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 订单
	 */
	public function  doMobileOrder()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
		$user = DBUtil::findById(DBUtil::$TABLE_WKJ_USER, $uid);
		MonUtil::emtpyMsg($wkj, "砍价活动删除或不存在");
		MonUtil::emtpyMsg($user, "用户删除或不存在");
		$zfPrice = $user['price'] + $wkj['yf_price'];
		$p_models=explode('|',$wkj['p_model']);
		include $this->template('order');

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * 订单提交
	 */
	public function  doMobileOrderSubmit()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$uid = $_GPC['uid'];
		$kid = $_GPC['kid'];
		$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
		$user = DBUtil::findById(DBUtil::$TABLE_WKJ_USER, $uid);
		$uname = $_GPC['uname'];
		$address = $_GPC['address'];
		$p_model=$_GPC['p_model'];
		$tel = $_GPC['tel'];
		MonUtil::emtpyMsg($wkj, "砍价活动不存在或已删除");
		MonUtil::emtpyMsg($user, "用户不存在或已删除");
		$orderInfo = $this->findOrderInfo($kid, $uid);

		if ($wkj['pay_type'] == 2) {//货到付款
			$leftCount = $wkj['p_kc'] - $this->getOrderCount($kid)-1;
			if ($leftCount < 0) {
				message("对不起库存已不足，请下次再来参加活动吧。");
			}

		}

		if (empty($orderInfo)) {//没有该用户的订单 信息

			$order_no = $this->getOrderNo($kid, $uid);
			$order_array = array(
				'kid' => $wkj['id'],
				'uid' => $user['id'],
				'order_no' => $order_no,
				'uname' => $uname,
				'address' => $address,
				'tel' => $tel,
				'openid' => $user['openid'],
				'y_price' => $wkj['p_y_price'],
				'kh_price' => $user['price'],
				'yf_price' => $wkj['yf_price'],
				'total_price' => $user['price'] + $wkj['yf_price'],
				'status' => $this::$KJ_STATUS_XD,
				'p_model'=>$p_model,
				'createtime' => TIMESTAMP

			);

			DBUtil::create(DBUtil::$TABLE_WJK_ORDER, $order_array);
			$oid = pdo_insertid();
			$orderInfo = DBUtil::findById(DBUtil::$TABLE_WJK_ORDER, $oid);

		}


		if ($orderInfo['status'] == $this::$KJ_STATUS_XD && $wkj['pay_type'] == 1) {//立即支付
			$jsApi = new JsApi_pub($this->kjSetting);
			$jsApi->setOpenId($user['openid']);
			$unifiedOrder = new UnifiedOrder_pub($this->kjSetting);
			$unifiedOrder->setParameter("openid", $user['openid']);//商品描述
			$unifiedOrder->setParameter("body", "砍价商品" . $wkj['p_name']);//商品描述
			$out_trade_no = $this->getOut_trade_no($orderInfo['id']);
			$unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
			$unifiedOrder->setParameter("total_fee", $orderInfo['total_price'] * 100);//总金额
			//$unifiedOrder->setParameter("total_fee", 1);//总金额
			$notifyUrl = $_W['siteroot'] . "addons/" . MON_WKJ . "/notify.php";
			$unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
			$unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
			$prepay_id = $unifiedOrder->getPrepayId();
			$jsApi->setPrepayId($prepay_id);
			//DBUtil::updateById(DBUtil::$TABLE_WJK_ORDER, array('order_no' => $out_trade_no), $orderInfo['id']);
			$jsApiParameters = $jsApi->getParameters();

			$gmCount = $this->getOrderCount1($kid);
			$leftCount = $wkj['p_kc'] - $gmCount;


		} else if ($orderInfo['status'] == $this::$KJ_STATUS_XD && $wkj['pay_type'] == 2) {//货到付款
			//$out_trade_no = $this->getOrderNo($kid, $uid);
			//DBUtil::updateById(DBUtil::$TABLE_WJK_ORDER, array('order_no' => $out_trade_no), $orderInfo['id']);
		}

		$orderInfo = $this->findOrderInfo($kid, $uid);
		include $this->template('order_submit');

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $kid
	 * @param $uid
	 * @return string
	 * 订单号
	 */
	public function  getOrderNo($kid, $uid)
	{
		return date('YmdHis', time()) . "k" . $kid . "u" . $uid;
	}

	public function getOut_trade_no($oid) {
		return date('YmdHis', time()) . "o" . $oid;
	}

	public function  doMobileAuth()
	{
		global $_GPC, $_W;
		$au = $_GPC['au'];
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$params = array();
		$params['kid'] = $kid;
		$params['au'] = $au;
		$params['uid'] = $uid;
		$userInfo = MonUtil::getClientCookieUserInfo(Mon_WkjModuleSite::$USER_COOKIE_KEY . "" . $kid);
		if (empty($userInfo)) {//授权

			$redirect_uri = MonUtil::str_murl($this->createMobileUrl('Auth2', $params, true));

			$this->oauth->authorization_code($redirect_uri, Oauth2::$SCOPE_USERINFO, 1);//进行授权

		} else {
			$params['openid'] = $userInfo['openid'];
			$redirect_uri = $this->getRedirectUrl($au, $params);
			header("location: $redirect_uri");
		}

	}

	public function  doMobileAuth2()
	{
		global $_GPC;
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$code = $_GPC ['code'];
		$au = $_GPC['au'];
		$tokenInfo = $this->oauth->getOauthAccessToken($code);
		$userInfo = $this->oauth->getOauthUserInfo($tokenInfo['openid'], $tokenInfo['access_token']);
		MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY . "" . $kid);//保存到cookie
		$params = array();
		$params['kid'] = $kid;
		$params['au'] = $au;
		$params['uid'] = $uid;
		$params['openid'] = $tokenInfo['openid'];
		$redirect_uri = $this->getRedirectUrl($au, $params);
		header("location: $redirect_uri");
	}


	/************************************************管理*********************************/

	/**
	 * 活动管理
	 */
	public function  doWebKjManage()
	{

		global $_W, $_GPC;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_WKJ) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_WKJ) . " WHERE weid =:weid ", array(':weid' => $this->weid));
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_WJK_ORDER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_WKJ_FIREND, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_WKJ_USER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_WKJ, array('id' => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("wkj_manage");

	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 设置
	 */
	public function doWebKjSetting()
	{
		global $_GPC, $_W;

		$kjsetting = DBUtil::findUnique(DBUtil::$TABLE_WKJ_SETTING, array(':weid' => $this->weid));
		if (checksubmit('submit')) {
			$data = array(
				'weid' => $this->weid,
				'appid' => trim($_GPC['appid']),
				'appsecret' => trim($_GPC['appsecret']),
				'mchid' => trim($_GPC['mchid']),
				'shkey' => trim($_GPC['shkey'])
			);
			if (!empty($kjsetting)) {
				DBUtil::updateById(DBUtil::$TABLE_WKJ_SETTING, $data, $kjsetting['id']);
			} else {
				DBUtil::create(DBUtil::$TABLE_WKJ_SETTING, $data);
			}
			message('参数设置成功！', $this->createWebUrl('KjSetting', array(
				'op' => 'display'
			)), 'success');
		}
		include $this->template("kjsetting");
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 砍价 订单
	 */

	public function  doWebOrderList()
	{

		global $_W, $_GPC;


		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$kid = $_GPC['kid'];
		if ($operation == 'display') {

			

			$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);

			if (empty($wkj)) {
				message("砍价活动删除或不存在");

			}

			$keyword = $_GPC['keyword'];
			$where = '';
			$params = array(
				':kid' => $kid
			);


			if (!empty($keyword)) {
				$where .= ' and (order_no like :keyword) or (tel like :keyword)';
				$params[':keyword'] = "%$keyword%";

			}

			if ($_GPC['status'] != 0) {
				$where .= ' and status =:status';
				$params[':status'] = $_GPC['status'];
			}

			$status =  $_GPC['status'];

			$order_time_get = $_GPC['order_time'];// 结构为: array('start'=>?, 'end'=>?)
			$starttime = empty($order_time_get['start']) ? strtotime('-1 month') : strtotime($order_time_get['start']);
			$endtime   = empty($order_time_get['end'])   ? TIMESTAMP : strtotime($order_time_get['end']);

			$where .= ' and createtime >=:starttime and createtime <=:endtime';

			$params[':starttime'] = $starttime;
			$params[':endtime'] = $endtime;

			$order_time =  array(
				'start' => date("Y-m-d H:i", $starttime),
				'end'   => date("Y-m-d H:i", $endtime),
			);

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_WJK_ORDER) . " WHERE kid =:kid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_WJK_ORDER) . " WHERE kid =:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_WJK_ORDER, array("id" => $id));
			message('删除成功！', $this->createWebUrl('OrderList',array('kid'=>$kid)), 'success');
		} else if($operation == 'fh') {
			$id = $_GPC['id'];
			DBUtil::updateById(DBUtil::$TABLE_WJK_ORDER, array('status'=>$this::$KJ_STATUS_YFH), $id );
			message('发货成功！', $this->createWebUrl('OrderList',array('kid'=>$kid)), 'success');
		}


		include $this->template("order_list");
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 订单详细
	 */
	public function  doWebOrderDetail()
	{
		global $_W, $_GPC;
		$kid = $_GPC['kid'];
		$oid = $_GPC['oid'];
		$order = DBUtil::findById(DBUtil::$TABLE_WJK_ORDER, $oid);
		$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);
		include $this->template("order_detail");
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 参与用户
	 */
	public function  doWebJoinUser()
	{
		global $_W, $_GPC;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {

			$kid = $_GPC['kid'];

			$wkj = DBUtil::findById(DBUtil::$TABLE_WKJ, $kid);

			if (empty($wkj)) {
				message("砍价活动删除或不存在");

			}

			$keyword = $_GPC['keyword'];
			$where = '';
			$params = array(
				':kid' => $kid
			);


			if (!empty($keyword)) {
				$where .= ' and (nickname like :nickname)';
				$params[':nickname'] = "%$keyword%";

			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_WKJ_USER) . " WHERE kid =:kid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_WKJ_USER) . " WHERE kid =:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_WJK_ORDER, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_WKJ_FIREND, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_WKJ_USER, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}


		include $this->template("user_list");


	}


	/**
	 * author: codeMonkey QQ:63187280
	 * 抓奖品记录
	 */
	public function doWebhelpFirend()
	{
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$kid = $_GPC['kid'];


		$keyword = $_GPC['keywords'];
		$where = '';
		$params = array(
			':kid' => $kid

		);


		if (!empty($keyword)) {
			$where .= ' and f.nickname like :nickname';
			$params[':nickname'] = "%$keyword%";
		}

		if (!empty($_GPC['uid'])) {
			$where .= ' and f.uid=:uid';
			$params[':uid'] = $_GPC['uid'];
		}


		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from " . tablename(DBUtil::$TABLE_WKJ_FIREND) . " f where f.kid=:kid " . $where . " order by f.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_WKJ_FIREND) . " f where f.kid=:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} elseif ($operation == 'delete') {

			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_WKJ_FIREND, array('id' => $id));
			message('删除成功！', referer(), 'success');

		}


		include $this->template('kj_firends');

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * 抓奖品记录导出
	 */
	public function  doWeborderDownload()
	{
		require_once 'orderdownload.php';
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 用户信息导出
	 */
	public function  doWebUDownload()
	{
		require_once 'udownload.php';
	}








	/***************************函数********************************/

	/**
	 * author: codeMonkey QQ:631872807
	 * @param $kid
	 * @param $status
	 * @return bool数量
	 */
	public function getOrderCount($kid)
	{
		$orderCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_WJK_ORDER) . " WHERE kid =:kid", array(":kid" => $kid));
		return $orderCount;
	}
	
	public function getOrderCount1($kid)
	{
		$orderCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_WJK_ORDER) . " WHERE kid =:kid and (status=4 or status=5)", array(":kid" => $kid));
		return $orderCount;
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 查找参加用户
	 */
	public function  findJoinUser($kid, $openid)
	{

		return DBUtil::findUnique(DBUtil::$TABLE_WKJ_USER, array(':kid' => $kid, ':openid' => $openid));

	}

	/**
	 * author: codeMonkey QQ:631872807
	 * @param $uid
	 * @param $fopenid
	 * @return bool
	 * 超找帮助用户
	 */
	public function  findHelpFirend($uid, $fopenid)
	{
		return DBUtil::findUnique(DBUtil::$TABLE_WKJ_FIREND, array(':uid' => $uid, ':openid' => $fopenid));
	}

	/**
	 * 获取帮助还有数量
	 * @param $kid
	 * @param $fopenid
	 */
	public function getHelpFriendCount($kid, $fopenid) {
		return $helpFriendCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_WKJ_FIREND) . " WHERE kid =:kid and openid=:openid", array(":kid" => $kid, ':openid' => $fopenid));

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $type
	 * 获取转向URL
	 */
	public function  getRedirectUrl($type, $parmas = array())
	{
		switch ($type) {

			case Value::$REDIRECT_USER_INDEX://首页
				$redirectUrl = $this->createMobileUrl('index', $parmas, true);
				break;
			case Value::$REDIRECT_KJ:
				$redirectUrl = $this->createMobileUrl('kj', $parmas, true);
				breka;


		}

		return MonUtil::str_murl($redirectUrl);


	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $status
	 * 状态文字
	 */
	public function  getStatusText($status)
	{
		switch ($status) {
			case $this::$KJ_STATUS_WKS:
				return "未开始";
				break;
			case $this::$KJ_STATUS_ZC:
				return "正常";
				break;
			case $this::$KJ_STATUS_JS:
				return "已结束";
				break;
			case $this::$KJ_STATUS_XD:
				return "已下单";
				break;
			case $this::$KJ_STATUS_GM:
				return "已支付购买";
			break;
			case $this::$KJ_STATUS_YFH:
			return "已发货";
			break;
		}

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $wkj
	 * @param $joinUser
	 * @return int
	 * 状态获取
	 */
	public function getStatus($wkj, $joinUser)
	{


		if (TIMESTAMP < $wkj['starttime']) {
			return $this::$KJ_STATUS_WKS;
		}
		if (TIMESTAMP > $wkj['endtime']) {
			return $this::$KJ_STATUS_JS;
		}

		$orderInfo = $this->findOrderInfo($wkj['id'], $joinUser['id']);


		if (empty($orderInfo)) {

			return $this::$KJ_STATUS_ZC;
		} else {
			return $orderInfo['status'];
		}


	}

	/**
	 * author: codeMonkey QQ:631872807
	 * @param $kid
	 * @param $uid
	 * @return bool 超找 订单信息
	 */
	public function findOrderInfo($kid, $uid)
	{
		$orderInfo = DBUtil::findUnique(DBUtil::$TABLE_WJK_ORDER, array(':kid' => $kid, ':uid' => $uid));
		return $orderInfo;
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * @param $wkj
	 * @param $user
	 * 获取砍价的价格
	 */
	public function getKjPrice($wkj, $userNowPrice)
	{

		if (empty($wkj['kj_rule'])) {
			return 0;
		}

		if ($userNowPrice <= $wkj['p_low_price']) {
			return 0;
		}

		$kj_rule = unserialize($wkj['kj_rule']);
		$kj_price = 0;
		$inRule = false;
		foreach ($kj_rule as $rule) {

			if ($userNowPrice >= $rule['rule_pice']) {
				$kj_price = rand($rule['rule_start'] * 10, $rule['rule_end'] * 10) / 10;
				$inRule = true;
				break;
			}

		}

		if (!$inRule) {
			$kj_price = rand(1 * 10, 2 * 10) / 10;
		}

		return $kj_price;
	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $wkj
	 * @param $msg_type
	 * @return mixed
	 * 获取随机文字信息
	 */
	public function  getTipMsg($wkj, $msg_type)
	{

		if (empty($wkj)) {
			reutrn;
		}
		switch ($msg_type) {
			case $this::$TIP_DIALOG:
				$msgContent = $wkj['kj_dialog_tip'];
				break;
			case $this::$TIP_U_FIRST:
				$msgContent = $wkj['u_fist_tip'];
				break;
			case $this::$TIP_U_ALREADY:
				$msgContent = $wkj['u_already_tip'];
				break;
			case $this::$TIP_RANK:
				$msgContent = $wkj['rank_tip'];
				break;
			case $this::$TIP_FK_FIRST:
				$msgContent = $wkj['fk_fist_tip'];
				break;
			case $this::$TIP_FK_ALREADY:
				$msgContent = $wkj['fk_already_tip'];
				break;
		}
		$msgContent = trim($msgContent);
		$msg_arr = explode("\r\n", $msgContent);
		if (count($msg_arr) == 1) {
			return $msg_arr[0];
		} else {
			return $msg_arr[rand(0, count($msg_arr) - 1)];
		}
	}

	public function findKJsetting()
	{
		$kjsetting = DBUtil::findUnique(DBUtil::$TABLE_WKJ_SETTING, array(":weid" => $this->weid));
		return $kjsetting;
	}

	function  encode($value)
	{
		return $value;
		return iconv("utf-8", "gb2312", $value);

	}

}