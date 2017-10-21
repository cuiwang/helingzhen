<?php
/**
 * codeMonkey:631872807
 */
defined('IN_IA') or exit('Access Denied');
define("MON_BATON", "mon_baton");
define("MON_BATON_RES", "../addons/" . MON_BATON . "/");
require_once IA_ROOT . "/addons/" . MON_BATON . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_BATON . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_BATON . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_BATON . "/monUtil.class.php";

/**
 * Class Mon_BatonModuleSite
 */
class Mon_BatonModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__batonwkjuserv8w332";
	public static $USER_CB_PAGE_SIZE=10;



	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$jlSetting =$this->findJlsetting();
		$this->oauth = new Oauth2($jlSetting['appid'], $jlSetting['appsecret']);
	
	}


	/************************************************手机*********************************/
	/**
	 * author: codeMonkey QQ:631872807
	 * 首页
	 */
	public function  doMobileIndex()
	{
		global $_W, $_GPC;
		$bid = $_GPC['bid'];
		MonUtil::checkmobile();
		$this->checkAuth($bid);
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);
		$baton=DBUtil::findById(DBUtil::$TABLE_BATON,$bid);
 		$user=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(':bid'=>$bid,":openid"=>$userInfo['openid']));
		$default=false;
		if(empty($user)||(!empty($user)&&$user['baton_num']==1)) {
			$default=true;
		} else {
			$preUser=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(":bid"=>$bid,":baton_num"=>$user['baton_num']-1));//上一棒哥们
		}
		$follow=false;

		if (!empty($_W['fans']['follow'])){
			 $follow=true;
		}
		//参加人数处理
		$user_list_top2=pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid  ORDER BY baton_num  DESC LIMIT 0,2", array(":bid"=>$bid));
		$user_list_top2_14=pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid  ORDER BY baton_num  DESC LIMIT 2,6", array(":bid"=>$bid));

		/*
		$user_test=array('nickname'=>'我操','headimgurl'=>"http://wx.qlogo.cn/mmopen/icJgTA5cZN2lx9tS0BU0K2x9Cy1l2iceTktc6nDseDc5tg5zNyJgyuyn6QoMC8QSb27TrM2oxpbCtErw6WGHBa5ynmsxDibXn8x/0");
		$user_list_top2_14[]=$user_test;

		$user_list_top2_14[]=$user_test;
		$user_list_top2_14[]=$user_test;
		$user_list_top2_14[]=$user_test;
		$user_list_top2_14[]=$user_test;
		$user_list_top2_14[]=$user_test;
		$user_list_top2_14[]=$user_test;
		$user_list_top2_14[]=$user_test;
		$user_list_top2_14[]=$user_test;

		*/

		$utotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_BATON_USER) . " WHERE bid =:bid", array(":bid"=>$bid));
		$cu_baton=$this->getCurrentBatonNum($bid);
		include $this->template('index');
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 加载更多用户分页
	 */
	public function doMobileUserPage() {
		global $_W, $_GPC;
		$bid=$_GPC['bid'];
		$page_size=6;
		$page = max(1, intval($_GPC['page']));
		$start=8+ ($page - 1) * $page_size;
		$user_total=pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid  ORDER BY baton_num  DESC LIMIT ".$start.",".$page_size, array(":bid"=>$bid));
		/*
		$user_test=array('nickname'=>'我操','headimgurl'=>"http://wx.qlogo.cn/mmopen/icJgTA5cZN2lx9tS0BU0K2x9Cy1l2iceTktc6nDseDc5tg5zNyJgyuyn6QoMC8QSb27TrM2oxpbCtErw6WGHBa5ynmsxDibXn8x/0");
		$user_total[]=$user_test;

		$user_total[]=$user_test;
		$user_total[]=$user_test;
		$user_total[]=$user_test;
		$user_total[]=$user_test;
		$user_total[]=$user_test;
		$user_total[]=$user_test;
		$user_total[]=$user_test;
		$user_total[]=$user_test;
		*/

		if(empty($user_total)) {
			die("nodata");
		}


		include $this->template("index_list_template");
	}



	/**
	 * author: codeMonkey QQ:631872807
	 * 荣誉榜加载更多用户分页
	 */
	public function doMobileRyUserPage() {
		global $_W, $_GPC;
		$bid=$_GPC['bid'];
		$page_size=8;
		$page = max(1, intval($_GPC['page']));
		$start=14+ ($page - 1) * $page_size;
		$user_list_top6_36=pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid  ORDER BY baton  DESC LIMIT ".$start.",".$page_size, array(":bid"=>$bid));

		//$user_test=array('nickname'=>'我操','headimgurl'=>"http://wx.qlogo.cn/mmopen/icJgTA5cZN2lx9tS0BU0K2x9Cy1l2iceTktc6nDseDc5tg5zNyJgyuyn6QoMC8QSb27TrM2oxpbCtErw6WGHBa5ynmsxDibXn8x/0");
		//$user_list_top6_36[]=$user_test;
		if(empty($user_list_top6_36)) {
			die("nodata");
		}


		include $this->template("ryb_list_template");
	}


	/**
	 * author: codeMonkey QQ:631872807
	 * 荣誉榜加载更多用户分页
	 */
	public function doMobileMcbPage() {
		global $_W, $_GPC;
		$bid=$_GPC['bid'];
		$uid=$_GPC['uid'];
		$page_size=10;
		$page = max(1, intval($_GPC['page']));
		$start=10+ ($page - 1) * $page_size;

		$clist = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid and puid=:puid  ORDER BY createtime DESC, id DESC LIMIT " . $start. ',' . $page_size, array(":bid"=>$bid,":puid"=>$uid));

		//$user_test=array('nickname'=>'我操','headimgurl'=>"http://wx.qlogo.cn/mmopen/icJgTA5cZN2lx9tS0BU0K2x9Cy1l2iceTktc6nDseDc5tg5zNyJgyuyn6QoMC8QSb27TrM2oxpbCtErw6WGHBa5ynmsxDibXn8x/0");
		//$clist[]=$user_test;
		//$clist[]=$user_test;
		//$clist[]=$user_test;
		if(empty($clist)) {
			die("nodata");
		}

		include $this->template("mcb_list_template");
	}




	/**
	 * author:codeMonkey QQ:631872807
	 * 我的接力棒
	 */
	public function doMobileUserBaton() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$bid = $_GPC['bid'];
		$this->checkAuth($bid);
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);
		$baton=DBUtil::findById(DBUtil::$TABLE_BATON,$bid);
		$user=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(':bid'=>$bid,":openid"=>$userInfo['openid']));
		$join=false;
		if(!empty($user)) {
			$join=true;
			$preUser=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(":bid"=>$bid,":baton_num"=>$user['baton_num']-1));//上一棒哥们
			if(empty($preUser)) {
				$default_name=$baton['default_name'];
			} else {
				$default_name=$preUser['nickname'];
			}

			//我的传棒

			$clist = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid and puid=:puid  ORDER BY createtime DESC, id DESC LIMIT " . (1 - 1) * $this::$USER_CB_PAGE_SIZE . ',' . $this::$USER_CB_PAGE_SIZE, array(":bid"=>$bid,":puid"=>$user['id']));
			$ctotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_BATON_USER) . " WHERE bid =:bid and puid=:puid", array(":bid"=>$bid,":puid"=>$user['id']));

			$totalS = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_BATON_USER) . " WHERE bid =:bid and baton >:ba", array(":bid"=>$bid,":ba" => $user['baton']));
			$m_order = $totalS+1;
		}

		$cur_baton=$this->getCurrentBatonNum($bid);
		include $this->template("user_baton");
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 首次接棒
	 */
	public function doMobileUserJBSuccess() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$bid=$_GPC['bid'];
		$this->checkAuth($bid);
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);
		$baton=DBUtil::findById(DBUtil::$TABLE_BATON,$bid);
		$user=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(':bid'=>$bid,":openid"=>$userInfo['openid']));
		$preUser=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(":bid"=>$bid,":baton_num"=>$user['baton_num']-1));//上一棒哥们
		if(empty($preUser)) {
			$default_name=$baton['default_name'];
		} else {
			$default_name=$preUser['nickname'];
		}
		if(empty($user)) {
			message("用户没有接棒成功!");
		}

		$cur_baton=$this->getCurrentBatonNum($bid);

		include $this->template("user_jl");
	}
	/**
	 * author:codeMonkey QQ:631872807
	 * 荣誉榜
	 */
	public function doMobileRyb() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$bid = $_GPC['bid'];
		$this->checkAuth($bid);
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);
		$baton=DBUtil::findById(DBUtil::$TABLE_BATON,$bid);
		$user=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(':bid'=>$bid,":openid"=>$userInfo['openid']));
		//$user_test=array('nickname'=>'我操','headimgurl'=>"http://wx.qlogo.cn/mmopen/icJgTA5cZN2lx9tS0BU0K2x9Cy1l2iceTktc6nDseDc5tg5zNyJgyuyn6QoMC8QSb27TrM2oxpbCtErw6WGHBa5ynmsxDibXn8x/0");
		$user_list_top0_3=pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid  ORDER BY baton  DESC LIMIT 0,3", array(":bid"=>$bid));
		//$user_list_top0_3[]=$user_test;
		//$user_list_top0_3[]=$user_test;
		$user_list_top3_6=pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid  ORDER BY baton  DESC LIMIT 3,3", array(":bid"=>$bid));
		//$user_list_top3_6[]=$user_test;
		//$user_list_top3_6[]=$user_test;
		//$user_list_top3_6[]=$user_test;


		$user_list_top6_36=pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid  ORDER BY baton  DESC LIMIT 6,8", array(":bid"=>$bid));


		include $this->template("ryb");
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 活动说明
	 */
	public function doMobileRule() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$bid = $_GPC['bid'];
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);
		$user=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(':bid'=>$bid,":openid"=>$userInfo['openid']));
		$baton=DBUtil::findById(DBUtil::$TABLE_BATON,$bid);
		include $this->template("rule");
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 接棒
	 */
	public function doMobileAjaxJb()
	{
		global $_W, $_GPC;
		$bid = $_GPC['bid'];
		$uname = $_GPC['uname'];
		$tel = $_GPC['tel'];
		$speak=$_GPC['speak'];
		$baton = DBUtil::findById(DBUtil::$TABLE_BATON, $bid);
		$res = array();
		if (empty($baton)) {
			$res['code'] = 500;
			$res['msg'] = "接力活动删除或不存在";
			die(json_encode($res));
		}
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);
		if (empty($userInfo)) {
			$res['code'] = 500;
			$res['msg'] = "请授权登录";
			die(json_encode($res));
		}

		$dbuser=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(':bid'=>$bid,":openid"=>$userInfo['openid']));
		if(!empty($dbuser)) {
			$res['code'] = 500;
			$res['msg'] = "用户已经接力过，不能再接棒了！";
			die(json_encode($res));
		}


		if(TIMESTAMP > $baton['endtime']) {
			$res['code'] = 500;
			$res['msg'] = "活动已经结束了！";
			die(json_encode($res));
		}
		$cu_baton_num = $this->getCurrentBatonNum($bid);
		$user_data = array(
			'bid' => $bid,
			'openid' => $userInfo['openid'],
			'nickname' => $userInfo['nickname'],
			'headimgurl' => $userInfo['headimgurl'],
			'puid' => $userInfo['puid'],
			'baton_num' => $cu_baton_num,
			'speak'=>$speak,
			'uname' => $uname,
			'tel' => $tel,
			'createtime' => TIMESTAMP
		);

		DBUtil::create(DBUtil::$TABLE_BATON_USER,$user_data);
		$this->updatePUser($userInfo['puid']);//更新老帮的传棒数
		$res['code'] = 200;
		$res['baton_num']=$cu_baton_num;
		die(json_encode($res));
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 用户详细信息
	 */
	public function  doMobileUserDeatil() {
		global $_W, $_GPC;
		$bid=$_GPC['bid'];
		$uid=$_GPC['uid'];
		$baton=DBUtil::findById(DBUtil::$TABLE_BATON,$bid);
		$user=DBUtil::findById(DBUtil::$TABLE_BATON_USER,$uid);
		$res=array();
		if(empty($user)) {//用户不存在
			if($baton['default_logo']) {
				$res['headimgurl']=MonUtil::getpicurl($baton['default_logo']);
			} else {
				$res['headimgurl']=MonUtil::defaultImg(MonUtil::$IMG_DEFAULT_LOGO);
			}
			$res['prenickname']=$baton['default_name'];
			$res['nickname']=$baton['default_name'];
			$res['speak']=$baton['speak'];
			$res['time']= date("Y-m-d", $baton['starttime']);
			$res['code']=200;
			die(json_encode($res));
		}
		$res=$user;
		$res['code']=200;
		$res['time']=date("Y-m-d", $user['createtime']);
		$preUser=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(":bid"=>$bid,":baton_num"=>$user['baton_num']-1));//上一棒哥们
		if(empty($preUser)) {
			$res['prenickname']=$baton['default_name'];
		} else {
			$res['prenickname']=$preUser['nickname'];
		}

		die(json_encode($res));

	}
	/**
	 * author: codeMonkey QQ:631872807
	 * 获取当前棒数
	 */
	public function getCurrentBatonNum($bid) {
		$uTotal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_BATON_USER) . " WHERE bid=:bid ",array(":bid"=>$bid));
		if($uTotal == 0) {
			return 1;
		} else {
			$cu_baton_num=pdo_fetchcolumn('SELECT max(baton_num) FROM ' . tablename(DBUtil::$TABLE_BATON_USER) . " WHERE bid=:bid ",array(":bid"=>$bid));
			return $cu_baton_num+1;
		}
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * @param $puid
	 *  更新老爸的传递棒数
	 */
	public function updatePUser($puid) {
		$pUser=DBUtil::findById(DBUtil::$TABLE_BATON_USER,$puid);
		if(!empty($pUser)) {
			DBUtil::updateById(DBUtil::$TABLE_BATON_USER,array('baton'=>$pUser['baton']+1),$puid);
		}
	}
	/**
	 * author:codeMonkey QQ:631872807
	 * @param $bid
	 * 查查权限
	 */
	public function checkAuth($bid) {
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);
		MonUtil::emtpyMsg($userInfo,"授权失败，请授权后再访问!");
		MonUtil::checkmobile();
		$baton=DBUtil::findById(DBUtil::$TABLE_BATON,$bid);
		MonUtil::emtpyMsg($baton,"活动已删除或不存在!");
	}

	public function getShareUrl($bid,$uid)
	{
	    //$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);
		//$user=DBUtil::findUnique(DBUtil::$TABLE_BATON_USER,array(':bid'=>$bid,":openid"=>$userInfo['openid']));
		//$uid = $user['id'];
		return MonUtil::str_murl($this->createMobileUrl('Auth', array('bid' => $bid, 'puid'=>$uid ,'au' => Value::$REDIRECT_INDEX), true));

	}



	public function  doMobileAuth()
	{
		global $_GPC, $_W;
		$au = $_GPC['au'];
		$bid = $_GPC['bid'];
		$puid = $_GPC['puid'];// 上一棒付哥们
		$params = array();
		$params['bid'] = $bid;
		$params['au'] = $au;
		$params['puid'] = $puid;
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $bid);

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
		$bid = $_GPC['bid'];
		$puid = $_GPC['puid'];
		$code = $_GPC ['code'];
		$au = $_GPC['au'];
		$tokenInfo = $this->oauth->getOauthAccessToken($code);
		$userInfo = $this->oauth->getOauthUserInfo($tokenInfo['openid'], $tokenInfo['access_token']);
		$userInfo['puid']=$puid;
		MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY . "" . $bid);//保存到cookie
		$params = array();
		$params['bid'] = $bid;
		$params['au'] = $au;
		$params['openid'] = $tokenInfo['openid'];
		$redirect_uri = $this->getRedirectUrl($au, $params);
		header("location: $redirect_uri");
	}


	/************************************************管理*********************************/

	/**
	 * 活动管理
	 */
	public function  doWebJlManage()
	{

		global $_W, $_GPC;

		$where='';
		$params = array();
		$params[':weid'] = $this->weid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `title` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON). " WHERE weid =:weid ".$where." ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_BATON) . " WHERE weid =:weid ".$where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_BATON_USER, array("bid" => $id));
			pdo_delete(DBUtil::$TABLE_BATON, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("jl_manage");

	}

	/**
	 * 接力用户
	 */
	public function  doWebuserList()
	{

		global $_W, $_GPC;
		$bid=$_GPC['bid'];
		$baton=DBUtil::findById(DBUtil::$TABLE_BATON,$bid);
		$o_der=$_GPC['o_der'];
		if(empty($o_der)) {

			$order=" baton_num asc";
		} else {
			if($o_der=="11"){
				$order=" baton_num asc";
			} else if($o_der=="12") {
				$order=" baton_num desc";
			}else if($o_der=="21") {
				$order="baton desc";
			}else if($o_der=="22") {
				$order="baton desc";
			}

		}

		$where='';
		$params = array();
		$params[':bid'] =$bid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `tel` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid ".$where." ORDER BY ".$order." LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_BATON_USER) . " WHERE bid =:bid ".$where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];

			pdo_delete(DBUtil::$TABLE_BATON_USER, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("user_list");

	}


	/**
	 * 接力用户
	 */
	public function  doWebcbuserList()
	{

		global $_W, $_GPC;
		$bid=$_GPC['bid'];
		$puid=$_GPC['puid'];
		$user=DBUtil::findById(DBUtil::$TABLE_BATON_USER,$puid);
		$where='';
		$params = array();
		$params[':bid'] =$bid;
		$params[':puid']=$puid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `tel` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_BATON_USER). " WHERE bid =:bid and puid=:puid".$where." ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_BATON_USER) . " WHERE bid =:bid and puid=:puid".$where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];

			pdo_delete(DBUtil::$TABLE_BATON_USER, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("cbuser_list");

	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 删除接力
	 */
	public function doWebDeleteJl() {
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_BATON_USER, array("bid" => $id));
			pdo_delete(DBUtil::$TABLE_BATON, array("id" => $id));
		}

		echo json_encode(array('code'=>200));
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 删除用户
	 */
	public function doWebDeleteUser() {
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $uid) {
			$id = intval($uid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_BATON_USER, array("id" => $id));
		}

		echo json_encode(array('code'=>200));
	}


	public function webmessage($error, $url = '', $errno = -1) {
		$data = array();
		$data['errno'] = $errno;
		if (!empty($url)) {
			$data['url'] = $url;
		}
		$data['error'] = $error;
		echo json_encode($data);
		exit;
	}



	/**
	 * author: codeMonkey QQ:631872807
	 * 设置
	 */
	public function doWebJlSetting()
	{
		global $_GPC, $_W;

		$batonsetting = DBUtil::findUnique(DBUtil::$TABLE_BATON_SETTING, array(':weid' => $this->weid));
		if (checksubmit('submit')) {
			$data = array(
				'weid' => $this->weid,
				'appid' => trim($_GPC['appid']),
				'appsecret' => trim($_GPC['appsecret'])
			);
			if (!empty($batonsetting)) {
				DBUtil::updateById(DBUtil::$TABLE_BATON_SETTING, $data, $batonsetting['id']);
			} else {
				DBUtil::create(DBUtil::$TABLE_BATON_SETTING, $data);
			}
			message('参数设置成功！', $this->createWebUrl('JlSetting', array(
				'op' => 'display'
			)), 'success');
		}
		include $this->template("jlsetting");
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





	/**
	 * author: codeMonkey QQ:631872807
	 * @param $type
	 * 获取转向URL
	 */
	public function  getRedirectUrl($type, $parmas = array())
	{
		switch ($type) {

			case Value::$REDIRECT_INDEX://首页
				$redirectUrl = $this->createMobileUrl('index', $parmas, true);
				break;
		}

		return MonUtil::str_murl($redirectUrl);


	}


	public function findJlsetting()
	{
		$jlsetting = DBUtil::findUnique(DBUtil::$TABLE_BATON_SETTING, array(":weid" => $this->weid));
		return $jlsetting;
	}

	function  encode($value)
	{
		return $value;
		return iconv("utf-8", "gb2312", $value);

	}
	
	function pre_name($nickname) {
		$len=$this->strLength($nickname);
		if($len>4) {
			return mb_substr($nickname,0,3,'utf-8')."...";
		}
		return $nickname;
	} 
	function strLength($str,$charset='utf-8'){
		 if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
			$num = strlen($str);
			$cnNum = 0;
			 for($i=0;$i<$num;$i++){
				if(ord(substr($str,$i+1,1))>127){
					$cnNum++;
					 $i++;
				}
			 }
			 $enNum = $num-($cnNum*2);
			$number = ($enNum/2)+$cnNum;
		return ceil($number);
	 }


}