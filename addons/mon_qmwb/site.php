<?php
/**
 * codeMonkey:2463619823
 */
defined('IN_IA') or exit('Access Denied');
define("MON_QMWB", "mon_qmwb");
define("MON_QMWB_RES", "../addons/" . MON_QMWB . "/");
require_once IA_ROOT . "/addons/" . MON_QMWB . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_QMWB . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_QMWB . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_QMWB . "/monUtil.class.php";

/**
 * Class Mon_WkjModuleSite
 */
class Mon_QmwbModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__monqmwbuservss3ss";

	public static $KJ_STATUS_WKS = 0;//未开始
	public static $KJ_STATUS_ZC = 1;//正常
	public static $KJ_STATUS_JS = 2;//结束
	public static $KJ_STATUS_XD = 3;//已下单
	public static $KJ_STATUS_GM = 4;//已支付
	public static $KJ_STATUS_YFH = 5;//已发货

	public static $STATUS_UNWIN = 1;//未中奖
    public static $STATUS_WIN = 2;//已中奖
	public static $SATUS_WIN_COMPLETE = 3;//已兑换


	public static $TIP_DIALOG = 1;//对话框
	public static $TIP_U_FIRST = 2;//首次
	public static $TIP_U_ALREADY = 3;//已经砍过了
	public static $TIP_RANK = 4;//页面
	public static $TIP_FK_FIRST = 5;//好友
	public static $TIP_FK_ALREADY = 6;//好友已看
	public static $GCODE = 1000;
	public $qmwbSetting;

	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$this->qmwbSetting = $this->findWMWbsetting();
		$this->oauth = new Oauth2($this->qmwbSetting['appid'], $this->qmwbSetting['appsecret']);
	}




	public function  getCookidUerInfo($qid) {
		//return array('openid'=>'o_-Hajq-MxgT-pvJX7gRMswH8_eM', 'nickname'=> "codeMonkey");

		//return array('openid'=>'o_-HajnJvvy-DqNuQp8q4a-m3T98', 'nickname'=>'大黑');
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $qid);
		return $userInfo;
	}


	/**
	 * author: codeMonkey QQ:2463619823
	 * 首页
	 */
	public function doMobileIndex() {
		global $_W, $_GPC;
		$qid = $_GPC['qid'];
		$userInfo = $this->getCookidUerInfo($qid);
		$qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB, $qid);
		MonUtil::emtpyMsg($qmwb, "藏宝活动不存在或已删除");
		MonUtil::checkmobile();
		$userInfo = $this->getCookidUerInfo($qid);

		$wins = $this->findWinPrize($qmwb['id']);



		$sqlPrize = "(select pname from ".tablename(DBUtil::$TABLE_QMWB_PRIZE)." p where p.id = r.pid) as pname";
		$sqlTel = "(select tel from ".tablename(DBUtil::$TABLE_QMWB_USER)." u1 where u1.id = r.uid) as tel";
		$sqlUname = "(select uname from ".tablename(DBUtil::$TABLE_QMWB_USER)." u2 where u2.id = r.uid) as uname";
		$sqlNickname = "(select nickname from ".tablename(DBUtil::$TABLE_QMWB_USER)." u3 where u3.id = r.uid) as nickname";
		$topWins = pdo_fetchall("SELECT r.*, ".$sqlPrize.", ".$sqlTel.", ".$sqlUname.", ".$sqlNickname." FROM "
			. tablename(DBUtil::$TABLE_QMWB_RECORD) . "  r WHERE pid <> 0 and qid=:qid"  . "  ORDER BY createtime DESC limit 0, ". $qmwb['index_show_win'], array(":qid" => $qid));
		include $this->template('index');
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 *
	 */
	public function doMobileBox() {
		global $_W, $_GPC;
		MonUtil::checkmobile();

		$qid = $_GPC['qid'];
		$userInfo = $this->getCookidUerInfo($qid);
		$qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB, $qid);
		MonUtil::emtpyMsg($qmwb, "藏宝活动不存在或已删除");
        $addresses = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMWB_ADDRESS) . " WHERE qid =:qid  ORDER BY createtime  ", array(':qid' =>$qid));

		$wins = $this->findWinPrize($qmwb['id']);
		include $this->template('box');
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 挖宝
	 */
	public function doMobileWB() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$aid = $_GPC['aid'];
		$address = DBUtil::findById(DBUtil::$TABLE_QMWB_ADDRESS, $aid);
		MonUtil::emtpyMsg($address, "藏宝地址不存在");
		$qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB, $address['qid']);

        $userInfo = $this->getCookidUerInfo($qmwb['id']);
		$dbUser = $this->findUser($userInfo['openid'], $qmwb['id']);

		$follow = 1;
		if (!empty($_W['fans']['follow'])){
			$follow = 2;
		}
		//$follow = 1;
		if (!empty($dbUser)) {
			//查挖宝记录
			$dbRecord = DBUtil::findUnique(DBUtil::$TABLE_QMWB_RECORD, array(':qid'=> $qmwb['id'], ':uid'=>$dbUser['id'], ':aid'=> $aid));

			if (!empty($dbRecord)) {
				//已挖宝

				$limitHelpUser = $address['kb_help_count'];
				$leftHelpUser = $limitHelpUser;
                if ($dbRecord['help_count']>= $limitHelpUser) {
					if ($dbRecord['pid'] != 0) {
                       $prize = DBUtil::findById(DBUtil::$TABLE_QMWB_PRIZE, $dbRecord['pid']);
					}

					$leftHelpUser = 0;
				} else {
					$leftHelpUser = $limitHelpUser-$dbRecord['help_count'];
				}


				include $this->template("wbalready");


			} else {
				include $this->template("wb");
			}

		} else {
			include $this->template("wb");
		}
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 我的挖宝记录，挖宝箱
	 */
	public function doMobileMyBox() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$qid = $_GPC['qid'];
		$userInfo = $this->getCookidUerInfo($qid);
        $qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB, $qid);
		$user = $this->findUser($userInfo['openid'], $qid);
		$sqlAdress = "(select box_name from ".tablename(DBUtil::$TABLE_QMWB_ADDRESS)." a where a.id = r.aid) as box_name";
		$limtCountSql = "(select kb_help_count from ".tablename(DBUtil::$TABLE_QMWB_ADDRESS)." a where a.id = r.aid) as kb_help_count";
		$pnameSql = "(select pname from ".tablename(DBUtil::$TABLE_QMWB_PRIZE)." p where p.id = r.pid) as pname";
		$records = pdo_fetchall("select r.*, ".$limtCountSql.", ".$pnameSql." from " . tablename(DBUtil::$TABLE_QMWB_RECORD)." r where uid=:uid and qid = :qid order by createtime desc", array(':uid'=> $user['id'], ':qid'=> $qid));
		$wins = $this->findWinPrize($qmwb['id']);
		include $this->template("mybox");

	}

	public function doMobilefirendKb() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$qid = $_GPC['qid'];
		$rid = $_GPC['rid'];
		$qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB, $qid);
		$record = DBUtil::findById(DBUtil::$TABLE_QMWB_RECORD, $rid);
		$dbRecord = $record;
		if (!empty($record['pid'])) {
			$prize = DBUtil::findById(DBUtil::$TABLE_QMWB_PRIZE, $record['pid']);
		}

		$address = DBUtil::findById(DBUtil::$TABLE_QMWB_ADDRESS, $record['aid']);
		if (empty($record)) {
			message("挖宝记录删除或不存在");
		}
		$userInfo = DBUtil::findById(DBUtil::$TABLE_QMWB_USER, $record['uid']);
        $address = DBUtil::findById(DBUtil::$TABLE_QMWB_ADDRESS, $record['aid']);
        $firendInfo = $this->getCookidUerInfo($qid);

		if (empty($firendInfo)) {
			message("请授权登录后再帮助好后挖宝");
		}
		//自己点开了自己的挖宝链接。。。。。
		if ($firendInfo['openid'] == $userInfo['openid']) {
			$redirectUrl = MonUtil::str_murl($this->createMobileUrl('WB', array('qid'=> $qid, 'aid'=> $address['id'])));
			header("location: $redirectUrl");
		} else {

			$follow = 1;
			if (!empty($_W['fans']['follow'])){
				$follow = 2;
			}

			$dbFirend = $this->findUser($firendInfo['openid'], $qid);
			$helpFirend = DBUtil::findUnique(DBUtil::$TABLE_QMWB_FIREND, array(":rid"=>$rid, ":qid"=> $qid, ':openid'=> $firendInfo['openid']));
			$helpUserCount = $record['help_count'];
			$leftHelpCount = $address['kb_help_count'] - $helpUserCount;
			if (empty($helpFirend)) {
				include $this->template("firend_wb");
			} else {
				include $this->template("firend_kbalready");
			}
		}

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 注册
	 */
	public function doMobileRegist() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$qid = $_GPC['qid'];
		$tel = $_GPC['tel'];
		$uname = $_GPC['uname'];
		$qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB,$qid);

		$res = array();

		if (empty($qmwb)) {
			$res['code'] = 500;
			$res['msg'] = "活动删除或不存在";
			die(json_encode($res));
		}

		if (TIMESTAMP < $qmwb['starttime']) {
			$res['code'] = 501;
			$res['msg'] = "活动还未开始";
			die(json_encode($res));
		}

		if (TIMESTAMP < $qmwb['starttime']) {
			$res['code'] = 502;
			$res['msg'] = "活动已结束";
			die(json_encode($res));
		}


		$userInfo = $this->getCookidUerInfo($qid);

		if (empty($userInfo)) {
			$res['code'] = 503;
			$res['msg'] = "请授权登录后再参加哦！";
			die(json_encode($res));
		}

		$dbUser = $this->findUser($userInfo['openid'], $qmwb['id']);
		if (!empty($dbUser)) {
			$res['code'] = 504;
			$res['msg'] = "用户已注册了，不能重复注册哦！";
			die(json_encode($res));
		}

		$userData = array(
			'qid' => $qid,
			'tel'=> $tel,
			'uname' => $uname,
			'openid'=> $userInfo['openid'],
			'nickname' => $userInfo['nickname'],
			'headimgurl' => $userInfo['headimgurl'],
			'createtime' => TIMESTAMP,
			'uopenid' => $_W['fans']['from_user'],
			'ip' => $_W['clientip']
		);
		DBUtil::create(DBUtil::$TABLE_QMWB_USER, $userData);
		$res['code'] = 200;
		die(json_encode($res));
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 用户参加挖宝
	 */
	public function doMobileUserWb() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$qid = $_GPC['qid'];
		$aid = $_GPC['aid'];
		$uid = $_GPC['uid'];

		$qmwb= DBUtil::findById(DBUtil::$TABLE_QMWB, $qid);
		$address = DBUtil::findById(DBUtil::$TABLE_QMWB_ADDRESS, $aid);
		$user = DBUtil::findById(DBUtil::$TABLE_QMWB_USER, $uid);
		$res = array();


		if (empty($qmwb)) {
			$res['code'] = 500;
			$res['msg'] = "活动删除或不存在";
			die(json_encode($res));
		}

		if (TIMESTAMP < $qmwb['starttime']) {
			$res['code'] = 501;
			$res['msg'] = "活动还未开始";
			die(json_encode($res));
		}

		if (TIMESTAMP > $qmwb['endtime']) {
			$res['code'] = 502;
			$res['msg'] = "活动已结束";
			die(json_encode($res));
		}


		if (empty($address)) {
			$res['code'] = 503;
			$res['msg'] = "挖宝地址不存在";
			die(json_encode($res));
		}

		if (empty($user)) {
			$res['code'] = 504;
			$res['msg'] = "请注册后再进行挖宝哦!";
			die(json_encode($res));
		}
		$dbRecord = DBUtil::findUnique(DBUtil::$TABLE_QMWB_RECORD, array(':qid'=> $qid, ':uid'=>$uid, ':aid'=> $aid));
		if (!empty($dbRecord)) {
			$res['code'] = 505;
			$res['msg'] = "次藏宝地点用户已经挖过宝了哦，不能再挖了哦，换个地点吧！";
			die(json_encode($res));
		}

		$wbCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_RECORD) . " WHERE  qid=:qid and uid=:uid  ", array(':qid' => $qid, ":uid" => $uid));

		if ($wbCount >= $qmwb['user_limit']) {
			$res['code'] = 506;
			$res['msg'] = "对不起，您的挖宝次数已用完了，感谢您参加我们的活动！";
			die(json_encode($res));
		}

		$recordData = array(
			'qid' => $qid,
			'aid' => $aid,
			'uid' => $uid,
			'help_count' => 0,
			'pid' => 0,
			'status' => $this::$STATUS_UNWIN,
			'djcreatetime' => 0,
			'createtime' => TIMESTAMP,
			'ip'=>$_W['clientip']


		);

		DBUtil::create(DBUtil::$TABLE_QMWB_RECORD, $recordData);
		$res['code'] = 200;
		$res['msg'] = "恭喜您挖宝成功，赶快要求您的小伙伴帮您开宝吧";
		die(json_encode($res));
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 好友帮助挖宝
	 */
	public function doMobileHelpWb() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$rid = $_GPC['rid'];
        $record = DBUtil::findById(DBUtil::$TABLE_QMWB_RECORD, $rid);
        $qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB, $record['qid']);
		$address  = DBUtil::findById(DBUtil::$TABLE_QMWB_ADDRESS, $record['aid']);
		$user = DBUtil::findById(DBUtil::$TABLE_QMWB_USER, $record['uid']);
		$res = array();
		if (empty($record)) {
			$res['code'] = 500;
			$res['msg'] = "挖宝记录删除或不存在";
			die(json_encode($res));
		}

		if (empty($qmwb)) {
			$res['code'] = 501;
			$res['msg'] = "活动删除或不存在";
			die(json_encode($res));
		}

		if (TIMESTAMP < $qmwb['starttime']) {
			$res['code'] = 502;
			$res['msg'] = "活动还未开始";
			die(json_encode($res));
		}

		if (TIMESTAMP > $qmwb['endtime']) {
			$res['code'] = 503;
			$res['msg'] = "活动已结束";
			die(json_encode($res));
		}

		if (empty($address)) {
			$res['code'] = 504;
			$res['msg'] = "挖宝地址不存在";
			die(json_encode($res));
		}

		if (empty($user)) {
			$res['code'] = 505;
			$res['msg'] = "主人不存在或已删除!";
			die(json_encode($res));
		}

		$firendInfo = $this->getCookidUerInfo($qmwb['id']);
	  	$helpFirend = DBUtil::findUnique(DBUtil::$TABLE_QMWB_FIREND, array(":rid"=>$rid, ":qid"=> $qmwb['id'], ':openid'=> $firendInfo['openid']));
		if (!empty($helpFirend)) {
			$res['code'] = 506;
			$res['msg'] = "已帮好友此地开过宝了哦!";
			die(json_encode($res));
		}

		if ($record['help_count'] >= $address['kb_help_count']) {
			$res['code'] = 507;
			$res['msg'] = "已无名额帮好友开宝了哦！";
			die(json_encode($res));
		}

		$firendData = array(
			'qid' => $qmwb['id'],
			'uid' => $user['id'],
			'rid' => $record['id'],
			'openid'=> $firendInfo['openid'],
			'nickname' => $firendInfo['nickname'],
			'headimgurl' => $firendInfo['headimgurl'],
			'createtime' => TIMESTAMP,
			'ip' => $_W['clientip']
		);

		//正好哥们是最后一个开宝的好友,计算奖品
		if ($record['help_count'] + 1 == $address['kb_help_count']) {
			$pid = $this->getPid($qmwb,$address, $user);
             if ($pid != 0) {
				 $status = $this::$STATUS_WIN;
			 } else {
				 $status = $this::$STATUS_UNWIN;
			 }
		} else {
			$status = $this::$STATUS_UNWIN;
			$pid = 0;
		}

		DBUtil::create(DBUtil::$TABLE_QMWB_FIREND, $firendData);
		//更新记录表
		DBUtil::updateById(DBUtil::$TABLE_QMWB_RECORD, array('pid'=> $pid, 'status'=> $status, 'help_count'=> $record['help_count'] + 1), $rid);
		$res['code'] = 200;
		if (!empty($pid)) {
			$prize = DBUtil::findById(DBUtil::$TABLE_QMWB_PRIZE, $pid);
			$res['msg'] = "感谢您帮好友抽中了". $prize['pname']."!";
		} else {
			$res['msg'] = "感谢您帮好友开宝!";
		}

		$this->sendTemplateMsg($qmwb, $prize['pname'], $firendInfo['nickname'], $user['uopenid']);
		die(json_encode($res));
	}


	public function doMobileFindWin() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$qid = $_GPC['qid'];
		$tel = $_GPC['tel'];
		$user =  DBUtil::findUnique(DBUtil::$TABLE_QMWB_USER, array(":tel" => $tel, ":qid"=>$qid));
		$res = array();
		if (empty($user)) {
			$res['code'] = 500;
			$res['msg'] = "没有此用户信息";
			die(json_encode($res));
		}

		$sqlPrize = "(select pname from ".tablename(DBUtil::$TABLE_QMWB_PRIZE)." p where p.id = r.pid) as pname";
		$winList = pdo_fetchall("SELECT r.*, ".$sqlPrize."  FROM "
			. tablename(DBUtil::$TABLE_QMWB_RECORD) . "  r WHERE pid <> 0 and qid=:qid and uid=:uid"  . "  ORDER BY createtime DESC", array(":qid" => $qid, ':uid' => $user['id']));

		if (empty($winList)) {
			$res['code'] = 501;
			$res['msg'] = "没有中奖";
			die(json_encode($res));
		} else {
			$prizes   = '';
			foreach($winList as $win) {
				$prizes .= $win['pname'] ."<br/>";
			}
			$res['code'] = 200;
			$res['msg'] = $prizes;
			die(json_encode($res));
		}
	}


	public function  doMobileQrcode() {
		global $_W, $_GPC;
		$rid = $_GPC['rid'];
		$record = DBUtil::findById(DBUtil::$TABLE_QMWB_RECORD, $rid);
		$prize = DBUtil::findById(DBUtil::$TABLE_QMWB_PRIZE, $record['pid']);
		$qrcode = $this->getScanCode($rid);
		if ($record['status'] == $this::$STATUS_WIN) {
			$statusText = '未兑换';
		} else if ($record['status'] == $this::$SATUS_WIN_COMPLETE) {
			$statusText = '已兑换';
		}

		include $this->template("qrcode");

	}
	/**
	 * author: codeMonkey QQ:2463619823
	 * 中奖名单
	 */
	public function findWinPrize($qid) {
		$sqlPrize = "(select pname from ".tablename(DBUtil::$TABLE_QMWB_PRIZE)." p where p.id = r.pid) as pname";
		$sqlTel = "(select tel from ".tablename(DBUtil::$TABLE_QMWB_USER)." u1 where u1.id = r.uid) as tel";
		$sqlUname = "(select uname from ".tablename(DBUtil::$TABLE_QMWB_USER)." u2 where u2.id = r.uid) as uname";
		$sqlNickname = "(select nickname from ".tablename(DBUtil::$TABLE_QMWB_USER)." u3 where u3.id = r.uid) as nickname";
		$winList = pdo_fetchall("SELECT r.*, ".$sqlPrize.", ".$sqlTel.", ".$sqlUname.", ".$sqlNickname." FROM "
			. tablename(DBUtil::$TABLE_QMWB_RECORD) . "  r WHERE pid <> 0 and qid=:qid"  . "  ORDER BY createtime DESC limit 0, 200", array(":qid" => $qid));
		return $winList;
	}

	public function getShareUrl($qid, $rid)
	{
		if (empty($rid)) {
			return MonUtil::str_murl($this->createMobileUrl('Auth', array('qid' => $qid, 'au' => Value::$REDIRECT_USER_INDEX), true));
		} else {
			return MonUtil::str_murl($this->createMobileUrl('Auth', array('qid' => $qid, 'rid' => $rid, 'au' => Value::$REDIRECT_KB), true));
		}
	}

	/**
	 * 计算奖品
	 * author: codeMonkey QQ:2463619823
	 * @param $qid
	 * @param $aid
	 * @param $uid
	 */
	public function getPid($qmwb, $address , $user) {

		$prizes = pdo_fetchall("select * from " . tablename(DBUtil::$TABLE_QMWB_PRIZE) . " where aid=:aid order by percent asc ", array(":aid" => $address['id']));


		$userAwardCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_RECORD) . " WHERE qid =:qid and pid <> 0 and uid=:uid", array(":qid" =>$qmwb['id'], ":uid"=> $user['id']));

		if ($userAwardCount >= $qmwb['user_award_limit'] ) {
			return 0;
		}

		if (empty($prizes)) {
			return 0;
		} else {
			$arrayRand = array();
			$totalRand = 0;
			for ($index = 0; $index < count($prizes); $index++) {
				$arrayRand[$index] = $prizes[$index]['percent'];
				$totalRand += $arrayRand[$index];
			}
			$arrayRand[count($prizes)] = 10000 - $totalRand;//不中奖概率计算

			$pIndex = $this->get_rand($arrayRand);//随机
			if ($pIndex == count($prizes)) { //没有中奖
				return 0;
			} else {
				$prize = $prizes[$pIndex];
				$przie_count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_RECORD) . " WHERE  qid=:qid and pid=:pid ", array(':qid' => $qmwb['id'], ":pid" => $prize['id']));

				if ($przie_count >= $prize['pcount'] ) { //超过数量了
					return 0;
				} else {
					return $prize['id'];
				}

			}

		}
	}

	/**
	 * 概率计算
	 *
	 * @param unknown $proArr
	 * @return Ambigous <string, unknown>
	 */
	function get_rand($proArr)
	{
		$result = '';
		// 概率数组的总概率精度
		$proSum = array_sum($proArr);
		// 概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum); // 抽取随机数
			if ($randNum <= $proCur) {
				$result = $key; // 得出结果
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset($proArr);
		return $result;
	}


	/************************************************管理*********************************/
	public function  doMobileAuth()
	{
		global $_GPC, $_W;
		$au = $_GPC['au'];
		$qid = $_GPC['qid'];
		$rid = $_GPC['rid'];
		$params = array();
		$params['qid'] = $qid;
		$params['au'] = $au;
		$params['rid'] = $rid;

		$userInfo = $this->getCookidUerInfo($qid);
		if (empty($userInfo)) {//授权
			$redirect_uri = MonUtil::str_murl($this->createMobileUrl('Auth2', $params, true));
			$this->oauth->authorization_code($redirect_uri, Oauth2::$SCOPE_USERINFO, 1);//进行授权
		} else {
			$params['openid'] = $userInfo['openid'];
			$redirect_uri = $this->getRedirectUrl($au, $params);
			header("location: $redirect_uri");
		}

	}

	public function  doMobileAuth2() {
		global $_GPC;
		$qid = $_GPC['qid'];
		$rid = $_GPC['rid'];
		$code = $_GPC ['code'];
		$au = $_GPC['au'];
		$tokenInfo = $this->oauth->getOauthAccessToken($code);
		$userInfo = $this->oauth->getOauthUserInfo($tokenInfo['openid'], $tokenInfo['access_token']);
		MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY . "" . $qid);//保存到cookie
		$params = array();
		$params['qid'] = $qid;
		$params['au'] = $au;
		$params['rid'] = $rid;
		$params['openid'] = $tokenInfo['openid'];
		$redirect_uri = $this->getRedirectUrl($au, $params);
		header("location: $redirect_uri");
	}


	/************************************************管理*********************************/

	/**
	 * 活动管理
	 */
	public function  doWebQmwbMange() {
		global $_W, $_GPC;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMWB) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB) . " WHERE weid =:weid ", array(':weid' => $this->weid));
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_RECORD, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_USER, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_PRIZE, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_ADDRESS, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB, array('id' => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("qmwb_manage");

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 藏宝管理
	 */
	public function doWebCbManage() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$qid = $_GPC['qid'];
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMWB_ADDRESS) . " WHERE qid =:qid  ORDER BY createtime DESC limit " . ($pindex - 1) * $psize . ',' . $psize, array(':qid' =>$qid));
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_ADDRESS) . " WHERE qid =:qid ", array(':qid' => $qid));
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			$wbRecord = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_RECORD) . " WHERE aid =:aid ", array(':aid' =>$id));
			if ($wbRecord > 0) {
				message("此地点有挖宝用户存在记录，删除失败，请先删除挖宝记录后再删除藏宝地点");
			} else {
				//删除记录
				pdo_delete(DBUtil::$TABLE_QMWB_PRIZE, array("aid" => $id));
				pdo_delete(DBUtil::$TABLE_QMWB_ADDRESS, array("id" => $id));
				message('删除成功！', referer(), 'success');
			}

		}

		include $this->template("cb_manage");

	}


	/**
	 * author: codeMonkey QQ:2463619823
	 * 藏宝编辑
	 */
	public function doWebCbEdit() {
		global $_W, $_GPC;
		$operation = $_GPC['op'];
		$qid = $_GPC['qid'];
		$aid = $_GPC['aid'];

		if (!empty($aid)) {
			$item = DBUtil::findById(DBUtil::$TABLE_QMWB_ADDRESS, $aid);
			$prizes=pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_QMWB_PRIZE)." where aid=:aid order by displayorder asc ",array(":aid"=>$aid));
		}


		if (checksubmit('submit')) {

			  $addressData = array(
                  'qid' => $qid,
				  'box_img' => $_GPC['box_img'],
				  'box_name' => $_GPC['box_name'],
				  'kw_bg' => $_GPC['kw_bg'],
				  'btn_kw_name' => $_GPC['btn_kw_name'],
				  'btn_kw_img' => $_GPC['btn_kw_img'],
				  'kb_help_count' => $_GPC['kb_help_count']
			  );


			if (empty($aid)) {
				$addressData['createtime'] = TIMESTAMP;
				DBUtil::create(DBUtil::$TABLE_QMWB_ADDRESS, $addressData);
				$aid =  pdo_insertid();
			} else {
				DBUtil::updateById(DBUtil::$TABLE_QMWB_ADDRESS, $addressData, $aid);
			}


			$prizids = array();
			$pids = $_GPC['pids'];
			$display_orders = $_GPC['display_orders'];
			$pnames = $_GPC['pnames'];
			$ptypes = $_GPC['ptypes'];
			$jfs = $_GPC['jfs'];
			$pcounts = $_GPC['pcounts'];
			$percents = $_GPC['percents'];

			if (is_array($pids)) {
				foreach ($pids as $key => $value) {
					$value = intval($value);
					$d = array(
						"aid" => $aid,
						"qid" => $qid,
						'displayorder' => $display_orders[$key],
						'pname' => $pnames[$key],
						'pcount' => $pcounts[$key],
						'ptype' => $ptypes[$key],
						'percent' => $percents[$key],
						'jf' => $jfs[$key],
						"createtime" => TIMESTAMP
					);

					if (empty($value)) {
						DBUtil::create(DBUtil::$TABLE_QMWB_PRIZE, $d);
						$prizids[] = pdo_insertid();
					} else {
						DBUtil::updateById(DBUtil::$TABLE_QMWB_PRIZE, $d, $value);
						$prizids[] = $value;
					}

				}

				if (count($prizids) > 0) {
					pdo_query("delete from " . tablename(DBUtil::$TABLE_QMWB_PRIZE) . " where aid='{$aid}' and id not in (" . implode(",", $prizids) . ")");
				} else {
					pdo_query("delete from " . tablename(DBUtil::$TABLE_QMWB_PRIZE) . " where aid='{$aid}'");
				}



			}


			if (!empty($aid)) {

				message('添加藏宝成功XX！', $this->createWebUrl('CbManage', array(
					'qid' => $qid
				)), 'success');

			} else {
				message('更新藏宝成功！', referer(), 'success');
			}

		}

		load()->func('tpl');

		include $this->template("cb_edit");


	}


	public function  doWebDeletePrize() {
		global $_GPC;
		$pid = $_GPC['pid'];
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_RECORD) . " WHERE  pid=:pid", array(':pid' => $pid));
		$res = array();
		if ($count > 0) {
			$res['code'] = 500;
		} else {
			$res['code'] = 200;
		}
		echo json_encode($res);

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 设置
	 */
	public function doWebqmwbMangeSetting()
	{
		global $_GPC, $_W;

		$qmwbSetting = DBUtil::findUnique(DBUtil::$TABLE_QMWB_SETTING, array(':weid' => $this->weid));
		if (checksubmit('submit')) {
			$data = array(
				'weid' => $this->weid,
				'appid' => trim($_GPC['appid']),
				'appsecret' => trim($_GPC['appsecret'])
			);
			if (!empty($qmwbSetting)) {
				DBUtil::updateById(DBUtil::$TABLE_QMWB_SETTING, $data, $qmwbSetting['id']);
			} else {
				DBUtil::create(DBUtil::$TABLE_QMWB_SETTING, $data);
			}
			message('参数设置成功！', $this->createWebUrl('qmwbMangeSetting', array(
				'op' => 'display'
			)), 'success');
		}
		include $this->template("qmwbsetting");
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 *  挖宝记录
	 */

	public function  doWebRecordList()
	{

		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$qid = $_GPC['qid'];
		$aid = $_GPC['aid'];
		$addresses = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMWB_ADDRESS) . " WHERE qid =:qid  ORDER BY createtime  ", array(':qid' =>$qid));
		if ($operation == 'display') {

			$qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB, $qid);

			if (empty($qmwb)) {
				message("藏宝活动删除或不存在");

			}
			$where = 'qid =:qid';
			$params = array(
				':qid' => $qid
			);

			if ($_GPC['uid']!='') {
				$where .= ' and uid =:uid';
				$params[':uid'] = $_GPC['uid'];
			}


			if ($aid!='' && $aid != 0) {
				$where .= ' and aid =:aid';
				$params[':aid'] = $aid;
			}

            $status = $_GPC['status'];
			if ($_GPC['status'] != '') {
				$where .= ' and status =:status';
				$params[':status'] = $_GPC['status'];
			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$sqlAdress = "(select box_name from ".tablename(DBUtil::$TABLE_QMWB_ADDRESS)." a where a.id = r.aid) as box_name";
			$sqlPrize = "(select pname from ".tablename(DBUtil::$TABLE_QMWB_PRIZE)." p where p.id = r.pid) as pname";
			$sqlTel = "(select tel from ".tablename(DBUtil::$TABLE_QMWB_USER)." u1 where u1.id = r.uid) as tel";
			$sqlUname = "(select uname from ".tablename(DBUtil::$TABLE_QMWB_USER)." u2 where u2.id = r.uid) as uname";
			$sqlNickname = "(select nickname from ".tablename(DBUtil::$TABLE_QMWB_USER)." u3 where u3.id = r.uid) as nickname";
			$list = pdo_fetchall("SELECT r.*,".$sqlAdress.", ".$sqlPrize.", ".$sqlTel.", ".$sqlUname.", ".$sqlNickname." FROM "
				. tablename(DBUtil::$TABLE_QMWB_RECORD) .

				"  r WHERE " . $where . "  ORDER BY createtime DESC limit " . ($pindex - 1) * $psize . ',' . $psize, $params);

			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_RECORD) . " r  WHERE  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_QMWB_RECORD, array("id" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("rid" => $id));
			message('删除成功！', referer(), 'success');

		} else if($operation == 'dh') {
			$id = $_GPC['id'];

			$record = DBUtil::findById(DBUtil::$TABLE_QMWB_RECORD, $id);

			$prize = DBUtil::findById(DBUtil::$TABLE_QMWB_PRIZE, $record['pid']);

			if ($prize['ptype'] == 1) { //实物
				$this->updateRecoredDJ($id);
				message('兑换' . $prize['pname']."成功！", referer(), 'success');
			} else if ($prize['ptype'] == 2 || $prize['ptype'] == 3){
				$user = DBUtil::findById(DBUtil::$TABLE_QMWB_USER, $record['uid']);
				if (empty($user['uopenid'])) {
					message('兑换失败 用户 粉丝 openid 为空!', referer(), 'success');
				} else {
					load()->model('mc');
					$uid = mc_openid2uid($user['uopenid']);
					if ($prize['ptype'] == 2) {
						$result = mc_credit_update($uid, 'credit1', $prize['jf'], array($uid,'全民挖宝兑换积分'));
					} else if ($prize['ptype'] == 3) {
						$result = mc_credit_update($uid, 'credit2', $prize['jf'], array($uid,'全民挖宝兑换金额成功'));
					}

					if ($result) {
						$this->updateRecoredDJ($id);
						message('兑换成功', referer(), 'success');
					} else {
						message($result, referer(), 'success');
					}
				}

			}

		}

		include $this->template("record_list");
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 好友信息
	 */
	public function doWebhelpFriendList() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$rid = $_GPC['rid'];
		$qid = $_GPC['qid'];
		if ($operation == 'display') {

			$record = DBUtil::findById(DBUtil::$TABLE_QMWB_RECORD, $rid);
			$address = DBUtil::findById(DBUtil::$TABLE_QMWB_ADDRESS, $record['aid']);
			$user = DBUtil::findById(DBUtil::$TABLE_QMWB_USER, $record['uid']);

			if (empty($record)) {
				message("记录删除或不存在 ");

			}

			$where = 'rid =:rid';
			$params = array(
				':rid' => $rid
			);

			$keyword = $_GPC['keyword'];

			if (!empty($keyword)) {
				$where .= ' and (nickname like :nickname)';
				$params[':nickname'] = "%$keyword%";
			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;

			$list = pdo_fetchall("SELECT * from " . tablename(DBUtil::$TABLE_QMWB_FIREND) . " where " . $where . " ORDER BY createtime DESC limit "
				. ($pindex - 1) * $psize . "," . $psize, $params
			);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_FIREND) . "   WHERE  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("friend_list");
	}



	/**
	 * author: codeMonkey QQ:2463619823
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
	 * author: codeMonkey QQ:2463619823
	 * 参与用户
	 */
	public function  doWebJoinUser()
	{
		global $_W, $_GPC;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {

			$qid = $_GPC['qid'];

			$qmwb = DBUtil::findById(DBUtil::$TABLE_QMWB, $qid);

			if (empty($qmwb)) {
				message("挖宝删除或不存在");

			}

			$keyword = $_GPC['keyword'];
			$where = '';
			$params = array(
				':qid' => $qid
			);

			if (!empty($keyword)) {
				$where .= ' and (tel like :tel)';
				$params[':tel'] = "%$keyword%";

			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMWB_USER) . " WHERE qid =:qid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMWB_USER) . " WHERE qid =:qid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_QMWB_RECORD, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_USER, array("id" => $id));
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
	 * author: codeMonkey QQ:631872807
	 * 抓奖品记录导出
	 */
	public function  doWebRecordDownload() {
		global $_W, $_GPC;
		$dc = $_GPC['dc'];
		$qid = $_GPC['qid'];
		$aid = $_GPC['aid'];
		$where = 'qid =:qid';
		$params = array(
			':qid' => $qid
		);
		if ($_GPC['uid']!='') {
			$where .= ' and uid =:uid';
			$params[':uid'] = $_GPC['uid'];
		}

		if ($aid!='' && $aid != 0) {
			$where .= ' and aid =:aid';
			$params[':aid'] = $aid;
		}
		$status = $_GPC['status'];
		if ($_GPC['status'] != '') {
			$where .= ' and status =:status';
			$params[':status'] = $_GPC['status'];
		}
		$sqlAdress = "(select box_name from ".tablename(DBUtil::$TABLE_QMWB_ADDRESS)." a where a.id = r.aid) as box_name";
		$sqlPrize = "(select pname from ".tablename(DBUtil::$TABLE_QMWB_PRIZE)." p where p.id = r.pid) as pname";
		$sqlTel = "(select tel from ".tablename(DBUtil::$TABLE_QMWB_USER)." u1 where u1.id = r.uid) as tel";
		$sqlUname = "(select uname from ".tablename(DBUtil::$TABLE_QMWB_USER)." u2 where u2.id = r.uid) as uname";
		$sqlNickname = "(select nickname from ".tablename(DBUtil::$TABLE_QMWB_USER)." u3 where u3.id = r.uid) as nickname";
		$list = pdo_fetchall("SELECT r.*,".$sqlAdress.", ".$sqlPrize.", ".$sqlTel.", ".$sqlUname.", ".$sqlNickname." FROM "
				. tablename(DBUtil::$TABLE_QMWB_RECORD) . "  r WHERE " . $where . "  ORDER BY createtime DESC", $params);



		$i = 0;
		foreach ($list as $key => $value) {
			$arr[$i]['box_name'] = $value['box_name'];
			$arr[$i]['nickname'] = $value['nickname'];
			$arr[$i]['uname'] = $value['uname'];
			$arr[$i]['tel'] = $value['tel'];
			$arr[$i]['help_count'] = $value['help_count'];
			if ($value['status'] == $this::$STATUS_UNWIN || $value['status'] == 0) {
				$arr[$i]['status'] = '未中奖';
			}

			if ($value['status'] == $this::$STATUS_WIN) {
				$arr[$i]['status'] = '已中奖';
			}

			if ($value['status'] == $this::$SATUS_WIN_COMPLETE) {
				$arr[$i]['status'] = '已兑奖';
			}


			if (!empty($value['pname'])) {
				$arr[$i]['pname'] = $value['pname'];
			} else {
				$arr[$i]['pname'] = '-';
			}
			$arr[$i]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
			if (!empty($value['djcreatetime'])) {
				$arr[$i]['djcreatetime'] = date('Y-m-d H:i:s', $value['djcreatetime']);
			} else {
				$arr[$i]['djcreatetime'] = '-';
			}

			$i++;
		}

		MonUtil::exportexcel($arr, array('藏宝地点', '昵称', '姓名', '手机', '帮助开宝人数','状态', '奖品','开挖时间','兑奖时间'),$dc ,'参加用户');
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 用户信息导出
	 */
	public function  doWebUDownload() {
		global $_W, $_GPC;
		$qid = $_GPC['qid'];
		$dc = $_GPC['dc'];
		$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMWB_USER) . " WHERE qid =:qid   ORDER BY createtime DESC, id DESC ", array(":qid" => $qid));
		$i = 0;
		foreach ($list as $key => $value) {
			$arr[$i]['openid'] = $value['openid'];
			$arr[$i]['nickname'] = $value['nickname'];
			$arr[$i]['uname'] = $value['uname'];
			$arr[$i]['tel'] = $value['tel'];
			$arr[$i]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
			$i++;
		}
		MonUtil::exportexcel($arr, array('openID', '昵称', '姓名', '手机', '参加时间'),$dc ,'参加用户');
	}

	public function updateRecoredDJ($rid) {
		DBUtil::updateById(DBUtil::$TABLE_QMWB_RECORD, array(status=> $this::$SATUS_WIN_COMPLETE, 'djcreatetime'=> TIMESTAMP), $rid);
	}

	/***************************函数********************************/

	public function getJoinCount($xkwkj) {
		$userCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid", array(":kid" => $xkwkj['id']));
		return $userCount + $xkwkj['v_user'];
	}
	/**
	 * author: codeMonkey QQ:631872807
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
	 * author: codeMonkey QQ:2463619823
	 * @param $kid
	 * 查找剩余数量
	 */
	public function getLeftCount($xkwkj) {

		$orderCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid=:kid ", array( ":kid" => $xkwkj['id']));

		return $xkwkj['p_kc'] - $orderCount;
	}

	/**
	 * author: codeMonkey QQ:2463619823
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
	 * author: codeMonkey QQ:2463619823
	 * @param $kid
	 * @param $fopenid
	 *
	 */
    public function findHelpFirendLimit($kid, $fopenid) {
		return DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_FIREND, array(':kid' => $kid, ':openid' => $fopenid));
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * @param $type
	 * 获取转向URL
	 * 获取转向URL
	 */
	public function  getRedirectUrl($type, $parmas = array())
	{
		switch ($type) {

			case Value::$REDIRECT_USER_INDEX://首页
				$redirectUrl = $this->createMobileUrl('index', $parmas, true);
				break;
			case Value::$REDIRECT_KB:
				$redirectUrl = $this->createMobileUrl('firendKb', $parmas, true);
				breka;
		}

		return MonUtil::str_murl($redirectUrl);


	}

	public function findWMWbsetting()
	{
		$xkkjsetting = DBUtil::findUnique(DBUtil::$TABLE_QMWB_SETTING, array(":weid" => $this->weid));
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

	public function doWebDeleteQmwb()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $qid) {
			$id = intval($qid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_RECORD, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_USER, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_PRIZE, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_ADDRESS, array("qid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB, array('id' => $id));
		}
		echo json_encode(array('code' => 200));
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 删除用户信息
	 */
	public function doWebDeleteUser()
	{
		global $_GPC, $_W;
		foreach ($_GPC['idArr'] as $k => $uid) {
			$id = intval($uid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_QMWB_RECORD, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_USER, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}

	public function doWebDeleteRecord()
	{
		global $_GPC, $_W;
		foreach ($_GPC['idArr'] as $k => $oid) {
			$id = intval($oid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_QMWB_RECORD, array("id" => $id));
			pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("rid" => $id));

		}
		echo json_encode(array('code' => 200));
	}

    public function doWebDeleteFriend() {
		global $_GPC, $_W;
		foreach ($_GPC['idArr'] as $k => $oid) {
			$id = intval($oid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_QMWB_FIREND, array("id" => $id));

		}
		echo json_encode(array('code' => 200));
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 对接兑换中心 url
	 */
	public function doMobileExchangeApi() {
		global $_GPC, $_W;

		$rid = $_GPC['rid'];
		$record = DBUtil::findById(DBUtil::$TABLE_QMWB_RECORD, $rid);
		$res = array();
		if (empty($record)) {
			$res['res'] = 'fail';
			$res['msg'] = '挖宝记录删除或不存在';
			die(json_encode($res));
		}

		if ($record['status'] == $this::$SATUS_WIN_COMPLETE) {
			$res['res'] = 'fail';
			$res['msg'] = '奖品已兑换，不能重复兑奖！';
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
		$result = ihttp_post($tokenUrl, array('token' =>$token));
		$resultJson = json_decode(substr($result['content'], 3), true);

		if (empty($resultJson)) {
			$res['res'] = 'fail';
			$res['msg'] = '验证核销人员返回为空';
			die(json_encode($res));
		} else {
			if ($resultJson['code'] == 200) {
				//开始执行核销
				$prize = DBUtil::findById(DBUtil::$TABLE_QMWB_PRIZE, $record['pid']);
				if ($prize['ptype'] == 1) { //实物
					$this->updateRecoredDJ($rid);
					$user = DBUtil::findById(DBUtil::$TABLE_QMWB_USER, $record['uid']);
					$res['res'] = 'success';
					$res['uname'] = $user['uname'];
					$res['unickname'] = $user['nickname'];
					$res['utel'] = $user['tel'];
					$res['pname'] = $prize['pname'];
					$res['remark'] = '兑换成功';
					die(json_encode($res));
				} else if ($prize['ptype'] == 2 || $prize['ptype'] == 3){
					$user = DBUtil::findById(DBUtil::$TABLE_QMWB_USER, $record['uid']);
					if (empty($user['uopenid'])) {
						$res['res'] = 'fail';
						$res['msg'] = '验证核销人员返回为空';
						die(json_encode($res));
					} else {
						load()->model('mc');
						$uid = mc_openid2uid($user['uopenid']);
						if ($prize['ptype'] == 2) {
							$result = mc_credit_update($uid, 'credit1', $prize['jf'], array($uid,'全民挖宝兑换积分'));
						} else if ($prize['ptype'] == 3) {
							$result = mc_credit_update($uid, 'credit2', $prize['jf'], array($uid,'全民挖宝兑换金额成功'));
						}

						if ($result) {
							$this->updateRecoredDJ($rid);
							$res['res'] = 'success';
							$res['uname'] = $user['uname'];
							$res['nickname'] = $user['nickname'];
							$res['utel'] = $user['tel'];
							$res['pname'] = $prize['pname'];
							$res['remark'] = '兑换积分成功';
							die(json_encode($res));
						} else {
							$this->updateRecoredDJ($rid);
							$res['res'] = 'success';
							$res['uname'] = $user['uname'];
							$res['unickname'] = $user['nickname'];
							$res['utel'] = $user['tel'];
							$res['pname'] = $prize['pname'];
							$res['remark'] = '兑换积分成功';
							die(json_encode($res));

							//message($result, referer(), 'success');
						}
					}
				}
			} else {
				$res['res'] = 'fail';
				$res['msg'] = '核销人员删除或不存在!';
				die(json_encode($res));
			}
		}
	}

	public function getScanCode($rid) {
		$codeArray = array(
			'exeUrl' => MonUtil::str_murl($this->createMobileUrl('ExchangeApi', array('rid'=> $rid), true)),
		    'gcode' => $this::$GCODE
		);
		return base64_encode(json_encode($codeArray));
	}


	/**
	 * author: codeMonkey QQ:2463619823
	 * 超找用户
	 * @param $openid
	 * @param $qid
	 * @return
	 */
	function findUser($openid, $qid) {
		return DBUtil::findUnique(DBUtil::$TABLE_QMWB_USER, array(":openid" => $openid, ":qid"=>$qid));
	}

	public function sendTemplateMsg($qmwb, $pname, $fname, $uopenid) {
		$templateMsg = array();
		if ($qmwb['tmp_enable'] == 1) {
			$templateMsg['template_id'] = $qmwb['tmpId'];
			$templateMsg['touser'] = $uopenid;
			$templateMsg['url'] = MonUtil::str_murl( $this->createMobileUrl ('auth',array('qid'=>$qmwb['id'],'au'=>Value::$REDIRECT_USER_INDEX), true));
			$templateMsg['topcolor'] = '#FF0000';
			$data = array();
			$data['first'] = array('value'=>$fname. "好友帮您开宝了", 'color'=>'#173177');

			$data['keyword1'] = array('value'=> $qmwb['title'], 'color'=>'#173177');
			if (!empty($pname)) {
				$data['keyword2'] = array('value'=> $pname, 'color'=>'#173177');
				$data['remark'] = array('value'=>"赶快联系管理员兑换奖品吧!", 'color'=>'#173177');
			} else {
				$data['keyword2'] = array('value'=> '未中奖', 'color'=>'#173177');

			}

			$templateMsg['data'] = $data;
			$jsonData = json_encode($templateMsg);
			WeUtility::logging('info',"发送模板消息全民挖宝".$jsonData);
			load()->func('communication');
			$acessToken = $this->getAccessToken();
			$apiUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$acessToken;
			$result = ihttp_request($apiUrl, $jsonData);
			WeUtility::logging('info',"发送模板消息全民挖宝返回".$result);

			WeUtility::logging('info',"发送模板消息全民挖宝返回".json_encode($result));
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