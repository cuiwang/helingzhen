<?php
/**
* 微夜店
* qq : 2752529588
*/ defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/weisrc_nightclub/template/');
include "model.php";
include "templateMessage.php";
class weisrc_nightclubModuleSite extends WeModuleSite 
{
	public $modulename = 'weisrc_nightclub';
	public $cur_version = '20150917';
	public $_appid = '';
	public $_appsecret = '';
	public $_accountlevel = '';
	public $_account = '';
	public $_weid = '';
	public $_fromuser = '';
	public $_nickname = '';
	public $_headimgurl = '';
	public $_verify = false;
	public $_debug = '1';
	//default:0
public $_weixin = '1';
	//default:1
public $_auth2_openid = '';
	public $_auth2_nickname = '';
	public $_auth2_headimgurl = '';
	public $table_setting = 'weisrc_nightclub_setting';
	public $table_introduce = 'weisrc_nightclub_introduce';
	public $table_activity = 'weisrc_nightclub_activity';
	public $table_activity_user = 'weisrc_nightclub_activity_user';
	public $table_activity_feedback = 'weisrc_nightclub_activity_feedback';
	public $table_photo = 'weisrc_nightclub_photo';
	public $table_savewine_log = 'weisrc_nightclub_savewine_log';
	public $table_category = 'weisrc_nightclub_category';
	public $table_goods = 'weisrc_nightclub_goods';
	public $table_savewine = 'weisrc_nightclub_savewine';
	public $table_neighbor_user = 'weisrc_nightclub_neighbor_user';
	public $table_neighbor_feedback = 'weisrc_nightclub_neighbor_feedback';
	function __construct() 
	{
		global $_GPC, $_W;
		$this->_weid = $_W['uniacid'];
		$this->_fromuser = $_W['fans']['from_user'];
		if ($_SERVER['HTTP_HOST'] == '127.0.0.1') 
		{
			$this->_fromuser = 'debug';
		}
		$this->_appid = '';
		$this->_appsecret = '';
		$this->_accountlevel = $_W['account']['level'];
		$this->_auth2_openid = 'auth2_openid_'.$_W['uniacid'];
		$this->_auth2_nickname = 'auth2_nickname_'.$_W['uniacid'];
		$this->_auth2_headimgurl = 'auth2_headimgurl_'.$_W['uniacid'];
		$lock_path = ",??";
		if (!file_exists($lock_path)) 
		{
		}
		else 
		{
			$file_content = file_get_contents($lock_path);
			$validation_code = $this->authorization();
		}
		if($this->_accountlevel < 4)
		{
			$setting = uni_setting($this->_weid);
			$oauth = $setting['oauth'];
			if (!empty($oauth) && !empty($oauth['account'])) 
			{
				$this->_account = account_fetch($oauth['account']);
				$this->_appid = $this->_account['key'];
				$this->_appsecret = $this->_account['secret'];
			}
		}
		else 
		{
			$this->_appid = $_W['account']['key'];
			$this->_appsecret = $_W['account']['secret'];
		}
	}
	public function doMobileGame() 
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		if (isset($_COOKIE[$this->_auth2_openid])) 
		{
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		}
		else 
		{
			$method = 'game';
			$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
			$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method);
			if (isset($_GPC['code'])) 
			{
				$userinfo = $this->oauth2($authurl);
				if (!empty($userinfo)) 
				{
					$from_user = $userinfo["openid"];
					$nickname = $userinfo["nickname"];
					$headimgurl = $userinfo["headimgurl"];
				}
				else 
				{
					message('授权失败!');
				}
			}
			else 
			{
				if (!empty($this->_appsecret)) 
				{
					$this->getCode($url);
				}
			}
		}
		include $this->template('game/index');
	}
	public function doMobileIndex() 
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		$do = 'index';
		$title = '微夜店';
		$method = 'index';
		$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) 
		{
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		}
		else 
		{
			if (isset($_GPC['code'])) 
			{
				$userinfo = $this->oauth2($authurl);
				if (!empty($userinfo)) 
				{
					$from_user = $userinfo["openid"];
					$nickname = $userinfo["nickname"];
					$headimgurl = $userinfo["headimgurl"];
				}
				else 
				{
					message('授权失败!');
				}
			}
			else 
			{
				if (!empty($this->_appsecret)) 
				{
					$this->getCode($url);
				}
			}
		}
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $_W['uniacid']));
		if (empty($setting)) 
		{
			$bg = '../addons/weisrc_nightclub/template/images/nurse.jpg';
		}
		else 
		{
			$title = $setting['title'];
			if (!empty($setting['bg'])) 
			{
				$bg = tomedia($setting['bg']);
			}
		}
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_introduce) . " WHERE weid=:weid", array(':weid' => $_W['uniacid']));
		include $this->template('index');
	}
	private $version = '2|8|4|4|6|1|2|8|0';
	public function doMobileVersion() 
	{
		message($this->version);
	}
	public function doMobileIntroduce() 
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		$do = 'introduce';
		$method = 'introduce';
		$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) 
		{
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		}
		else 
		{
			if (isset($_GPC['code'])) 
			{
				$userinfo = $this->oauth2($authurl);
				if (!empty($userinfo)) 
				{
					$from_user = $userinfo["openid"];
					$nickname = $userinfo["nickname"];
					$headimgurl = $userinfo["headimgurl"];
				}
				else 
				{
					message('授权失败!');
				}
			}
			else 
			{
				if (!empty($this->_appsecret)) 
				{
					$this->getCode($url);
				}
			}
		}
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $_W['uniacid']));
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_introduce) . " WHERE weid=:weid", array(':weid' => $_W['uniacid']));
		include $this->template('introduce');
	}
	public function doMobileActivity() 
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		$do = 'activity';
		$method = 'activity';
		$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) 
		{
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		}
		else 
		{
			if (isset($_GPC['code'])) 
			{
				$userinfo = $this->oauth2($authurl);
				if (!empty($userinfo)) 
				{
					$from_user = $userinfo["openid"];
					$nickname = $userinfo["nickname"];
					$headimgurl = $userinfo["headimgurl"];
				}
				else 
				{
					message('授权失败!');
				}
			}
			else 
			{
				if (!empty($this->_appsecret)) 
				{
					$this->getCode($url);
				}
			}
		}
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity) . " WHERE weid=:weid AND status=1 ORDER BY displayorder DESC,id DESC LIMIT 10", array(':weid' => $_W['uniacid']));
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
		include $this->template('activity');
	}
	public function doMobileActivityDetail() 
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		$do = 'activity';
		$id = intval($_GPC['id']);
		$flag = false;
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
		$method = 'neighbor';
		$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) 
		{
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		}
		else 
		{
			if (isset($_GPC['code'])) 
			{
				$userinfo = $this->oauth2($authurl);
				if (!empty($userinfo)) 
				{
					$from_user = $userinfo["openid"];
					$nickname = $userinfo["nickname"];
					$headimgurl = $userinfo["headimgurl"];
				}
				else 
				{
					message('授权失败!');
				}
			}
			else 
			{
				if (!empty($this->_appsecret)) 
				{
					$this->getCode($url);
				}
			}
		}
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_activity) . " WHERE weid=:weid AND id=:id ORDER BY displayorder DESC,id DESC LIMIT 1", array(':weid' => $_W['uniacid'], ':id' => $id));
		$userlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity_user) . " WHERE weid=:weid AND status=1 AND activityid=:activityid ORDER BY displayorder DESC,id DESC LIMIT 30", array(':weid' => $_W['uniacid'], ':activityid' => $id));
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_user) . " WHERE weid=:weid AND from_user=:from_user AND activityid=:activityid ORDER BY id DESC LIMIT 1", array(':weid' => $_W['uniacid'], ':from_user' => $from_user, ':activityid' => $id));
		if (!empty($user)) 
		{
			$flag = true;
		}
		$feedback = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity_feedback) . " WHERE weid=:weid AND status=1 AND activityid=:activityid ORDER BY displayorder DESC,id DESC LIMIT 30", array(':weid' => $_W['uniacid'], ':activityid' => $id));
		include $this->template('activitydetail');
	}
	public function doMobileUserRegist() 
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		$activityId = intval($_GPC['activityId']);
		$nickname = $_GPC['nickname'];
		$headimgurl = $_GPC['headimgurl'];
		if (empty($from_user)) 
		{
			$this->showMessageAjax('会话已过期，请发送关键字触发进入!', 1);
		}
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_activity) . " WHERE weid=:weid AND id=:activityId LIMIT 1", array(':weid' => $weid, ':activityId' => $activityId));
		if (empty($item)) 
		{
			$this->showMessageAjax('活动不存在!', 1);
		}
		else 
		{
			if ($item['end_time'] < TIMESTAMP) 
			{
				$this->showMessageAjax('活动已经结束!', 1);
			}
		}
		$user = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_user) . " WHERE weid=:weid AND from_user=:from_user AND activityId=:activityId LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user, ':activityId' => $activityId));
		if (!empty($user)) 
		{
			$this->showMessageAjax('您已经报过名了!', 1);
		}
		$data = array( 'weid' => intval($_W['uniacid']), 'activityId' => $activityId, 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'title' => '', 'username' => '', 'tel' => '', 'displayorder' => 0, 'status' => 1, 'dateline' => TIMESTAMP );
		pdo_insert($this->table_activity_user, $data);
		$this->showMessageAjax('报名成功!', 0);
	}
	public function doMobileFeedback() 
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		$activityId = intval($_GPC['activityId']);
		$nickname = $_GPC['nickname'];
		$headimgurl = $_GPC['headimgurl'];
		$content = trim($_GPC['content']);
		if (empty($from_user)) 
		{
			$this->showMessageAjax('会话已过期，请发送关键字触发进入!', 1);
		}
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_activity) . " WHERE weid=:weid AND id=:activityId LIMIT 1", array(':weid' => $weid, ':activityId' => $activityId));
		if (empty($item)) 
		{
			$this->showMessageAjax('活动不存在!' . $activityId, 1);
		}
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
		if (!empty($setting)) 
		{
			if ($setting['feedback_check_enable'] == 1) 
			{
				$data['status'] = 0;
			}
			else 
			{
				$data['status'] = 1;
			}
		}
		else 
		{
			$data['status'] = 1;
		}
		$data = array( 'weid' => intval($_W['uniacid']), 'activityId' => $activityId, 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'content' => $content, 'displayorder' => 0, 'dateline' => TIMESTAMP );
		pdo_insert($this->table_activity_feedback, $data);
		$this->showMessageAjax('留言成功!', 0, 1);
	}
	public function doMobileNeighbor() 
	{
		global $_W, $_GPC;
		$weid = $this->_weid;
		$from_user = $this->_fromuser;
		$do = 'neighbor';
		$inseat = 0;
		$method = 'neighbor';
		$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) 
		{
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		}
		else 
		{
			if (isset($_GPC['code'])) 
			{
				$userinfo = $this->oauth2($authurl);
				if (!empty($userinfo)) 
				{
					$from_user = $userinfo["openid"];
					$nickname = $userinfo["nickname"];
					$headimgurl = $userinfo["headimgurl"];
				}
				else 
				{
					message('授权失败!');
				}
			}
			else 
			{
				if (!empty($this->_appsecret)) 
				{
					$this->getCode($url);
				}
			}
		}
		$time_condition = " AND (dateline BETWEEN unix_timestamp(DATE_ADD(current_timestamp, INTERVAL -1 MONTH)) AND unix_timestamp(current_timestamp)) ";
		$flag = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE weid=:weid AND from_user=:from_user {$time_condition}
	LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
	$inseat = !empty($flag) ? 1 : 0;
	$user = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE weid=:weid AND from_user=:from_user LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
	$userlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE weid=:weid AND status=1 {$time_condition}
ORDER BY id DESC LIMIT 30", array(':weid' => $weid));
$feedbacklist = pdo_fetchall("SELECT * FROM " . tablename($this->table_neighbor_feedback) . " WHERE weid=:weid AND status=1 ORDER BY id DESC LIMIT 30", array(':weid' => $weid));
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $weid));
if (strpos($setting['share_image'], 'http') === false) 
{
	$share_image = $_W['attachurl'] . $setting['share_image'];
}
else 
{
	$share_image = $setting['share_image'];
}
$share_title = empty($setting['share_title']) ? $setting['title'] : $setting['share_title'];
$share_desc = empty($setting['share_desc']) ? $setting['title'] : $setting['share_desc'];
$share_cancel = $setting['share_cancel'];
$follow_url = $setting['follow_url'];
$share_url = empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('neighbor') : $setting['share_url'];
include $this->template('neighbor');
}
public function doMobileNeighborFeedback() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$nickname = $_GPC['nickname'];
$headimgurl = $_GPC['headimgurl'];
$content = trim($_GPC['content']);
if (empty($from_user)) 
{
	$this->showMessageAjax('会话已过期，请发送关键字触发进入!', 1);
}
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
if (!empty($setting)) 
{
	if ($setting['feedback_check_enable'] == 1) 
	{
		$data['status'] = 0;
	}
	else 
	{
		$data['status'] = 1;
	}
}
else 
{
	$data['status'] = 1;
}
$data = array( 'weid' => intval($_W['uniacid']), 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'content' => $content, 'displayorder' => 0, 'dateline' => TIMESTAMP );
pdo_insert($this->table_neighbor_feedback, $data);
$this->showMessageAjax('留言成功!', 0, 1);
}
public function doMobileInseat() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$nickname = $_GPC['nickname'];
$headimgurl = $_GPC['headimgurl'];
$weixin = trim($_GPC['weixin']);
$qq = trim($_GPC['qq']);
$tel = trim($_GPC['tel']);
if (empty($from_user)) 
{
	$this->showMessageAjax('会话已过期，请发送关键字触发进入!', 1);
}
$time_condition = " AND (dateline BETWEEN unix_timestamp(DATE_ADD(current_timestamp, INTERVAL -12 HOUR)) AND unix_timestamp(current_timestamp)) ";
$user = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE weid=:weid AND from_user=:from_user {$time_condition}
LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
if (!empty($user)) 
{
$this->showMessageAjax('您已经就座了!', 1);
}
$data = array( 'weid' => intval($_W['uniacid']), 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'username' => '', 'weixin' => $weixin, 'tel' => $tel, 'qq' => $qq, 'displayorder' => 0, 'status' => 1, 'dateline' => TIMESTAMP );
$user = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE weid=:weid AND from_user=:from_user LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
if (!empty($user)) 
{
pdo_update($this->table_neighbor_user, $data);
}
else 
{
pdo_insert($this->table_neighbor_user, $data);
}
$this->showMessageAjax('报名成功!', 0, 1);
}
function format_date($time) 
{
$t = time() - $time;
$f = array( '31536000' => '年', '2592000' => '个月', '604800' => '星期', '86400' => '天', '3600' => '小时', '60' => '分钟', '1' => '秒' );
foreach ($f as $k => $v) 
{
if (0 != $c = floor($t / (int)$k)) 
{
	return $c . $v . '前';
}
}
}
public function doMobilePhoto() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$do = 'photo';
$method = 'photo';
$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
if (isset($_COOKIE[$this->_auth2_openid])) 
{
$from_user = $_COOKIE[$this->_auth2_openid];
$nickname = $_COOKIE[$this->_auth2_nickname];
$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
}
else 
{
if (isset($_GPC['code'])) 
{
	$userinfo = $this->oauth2($authurl);
	if (!empty($userinfo)) 
	{
		$from_user = $userinfo["openid"];
		$nickname = $userinfo["nickname"];
		$headimgurl = $userinfo["headimgurl"];
	}
	else 
	{
		message('mitusky提醒:授权失败!');
	}
}
else 
{
	if (!empty($this->_appsecret)) 
	{
		$this->getCode($url);
	}
}
}
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $_W['uniacid']));
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_photo) . " WHERE weid=:weid AND status=1 ORDER BY displayorder DESC,id DESC LIMIT 100", array(':weid' => $_W['uniacid']));
include $this->template('photo');
}
public function doMobileUploadPhoto() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$nickname = $_GPC['nickname'];
if (empty($from_user)) 
{
$this->showMessageAjax('会话已过期，请发送关键字触发进入!', 1);
}
$time_condition = " AND (dateline BETWEEN unix_timestamp(DATE_ADD(current_timestamp, INTERVAL -24 HOUR)) AND unix_timestamp(current_timestamp)) ";
$flag = pdo_fetch("SELECT * FROM " . tablename($this->table_photo) . " WHERE weid=:weid AND from_user=:from_user {$time_condition}
LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $weid));
$photo_check_enable = intval($setting['photo_check_enable']);
if (!empty($_FILES['attachFile']['name'])) 
{
if ($_FILES['attachFile']['error'] != 0) 
{
$this->showMessageAjax('mitusky提醒:上传失败，请重试！', 1);
}
$_W['uploadsetting'] = array();
$_W['uploadsetting']['image']['folder'] = 'images/' . $_W['uniacid'];
$_W['uploadsetting']['image']['extentions'] = $_W['config']['upload']['image']['extentions'];
$_W['uploadsetting']['image']['limit'] = 1024;
load()->func('file');
$file = file_upload($_FILES['attachFile'], 'image');
if (is_error($file)) 
{
$this->showMessageAjax('上传失败，请重试！' . $file['message'], 1);
}
$result['url'] = $file['url'];
$result['error'] = 0;
$result['filename'] = $file['path'];
$result['url'] = $_W['attachurl'] . $result['filename'];
$insert_photo = array( 'weid' => $weid, 'title' => $nickname, 'from_user' => $from_user, 'description' => $nickname, 'nickname' => $nickname, 'attachment' => $result['filename'], 'mode' => 1, 'url' => '', 'isfirst' => 0, 'displayorder' => 0 );
if ($photo_check_enable == 1) 
{
$insert_photo['status'] = 0;
}
else 
{
$insert_photo['status'] = 1;
}
pdo_insert($this->table_photo, $insert_photo);
$this->showMessageAjax('上传成功！', $photo_check_enable, 1);
}
else 
{
$this->showMessageAjax('请选择要上传的音频文件！', 1);
}
}
public function doMobileSavewine() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$do = 'savewine';
$flag = false;
$method = 'savewine';
$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
if (isset($_COOKIE[$this->_auth2_openid])) 
{
$from_user = $_COOKIE[$this->_auth2_openid];
$nickname = $_COOKIE[$this->_auth2_nickname];
$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
}
else 
{
if (isset($_GPC['code'])) 
{
$userinfo = $this->oauth2($authurl);
if (!empty($userinfo)) 
{
	$from_user = $userinfo["openid"];
	$nickname = $userinfo["nickname"];
	$headimgurl = $userinfo["headimgurl"];
}
else 
{
	message('mitusky提醒:授权失败!');
}
}
else 
{
if (!empty($this->_appsecret)) 
{
	$this->getCode($url);
}
}
}
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $weid));
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_introduce) . " WHERE weid=:weid LIMIT 1", array(':weid' => $weid));
$savelog = pdo_fetch("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE weid=:weid AND from_user=:from_user AND status<>-1 LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
if (!empty($savelog)) 
{
$flag = true;
}
include $this->template('savewine');
}
public function doMobileSaveWinding() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$nickname = $_GPC['nickname'];
$headimgurl = $_GPC['headimgurl'];
$savenumber = $this->get_save_number($weid);
$result = array('status' => 1);
if (empty($from_user)) 
{
$result = array('status' => 0);
echo json_encode($result);
exit;
}
else 
{
}
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE weid=:weid AND from_user=:from_user AND status<>-1 LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
if (!empty($item)) 
{
$result = array('status' => 0);
echo json_encode($result);
exit;
}
$data = array( 'weid' => $weid, 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'savenumber' => $savenumber, 'title' => '', 'remark' => '', 'displayorder' => 0, 'status' => 0, 'dateline' => TIMESTAMP );
pdo_insert($this->table_savewine_log, $data);
echo json_encode($result);
}
public function get_save_number($weid) 
{
global $_W, $_GPC;
$save_number = pdo_fetch("select savenumber from " . tablename($this->table_savewine_log) . " where weid =" . $weid . " order by id desc limit 1");
if (!empty($save_number)) 
{
return intval($save_number['savenumber']) + 1;
}
else 
{
return 100;
}
}
public function doMobileCabinet() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$do = 'cabinet';
$cid = intval($_GPC['cid']);
$condition = '';
if ($cid != 0) 
{
$condition = " AND pcate={$cid}
";
}
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $weid));
$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = :weid AND status=1 ORDER BY displayorder DESC,id DESC", array(':weid' => $weid), 'id');
$pindex = max(1, intval($_GPC['page']));
$psize = 6;
$goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = :weid AND status=1 {$condition}
ORDER BY displayorder DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $weid), 'id');
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_goods) . " WHERE weid = '{$weid}
' AND status=1 $condition");
$pager = pagination($total, $pindex, $psize);
include $this->template('cabinet');
}
public function doMobileinvitation() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$do = 'invitation';
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $weid));
include $this->template('invitation');
}
public function doMobileInvitationDetail() 
{
global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$do = 'invitation';
$cur = $_GPC['cur'];
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $weid));
$invitation_bg = RES . '/images/invitation-0' . $cur . '.png';
$method = 'InvitationDetail';
$authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
if (isset($_COOKIE[$this->_auth2_openid])) 
{
$from_user = $_COOKIE[$this->_auth2_openid];
$nickname = $_COOKIE[$this->_auth2_nickname];
$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
}
else 
{
if (isset($_GPC['code'])) 
{
$userinfo = $this->oauth2($authurl);
if (!empty($userinfo)) 
{
$from_user = $userinfo["openid"];
$nickname = $userinfo["nickname"];
$headimgurl = $userinfo["headimgurl"];
}
else 
{
message('mitusky提醒:授权失败!');
}
}
else 
{
if (!empty($this->_appsecret)) 
{
$this->getCode($url);
}
}
}
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid", array(':weid' => $weid));
if (strpos($setting['share_image'], 'http') === false) 
{
$share_image = $_W['attachurl'] . $setting['share_image'];
}
else 
{
$share_image = $setting['share_image'];
}
$share_title = empty($setting['share_title']) ? $setting['title'] : $setting['share_title'];
$share_desc = empty($setting['share_desc']) ? $setting['title'] : $setting['share_desc'];
$share_cancel = $setting['share_cancel'];
$follow_url = $setting['follow_url'];
$share_url = empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index') : $setting['share_url'];
include $this->template('invitationdetail');
}
function formatTime($date) 
{
$str = '';
$timer = strtotime($date);
$diff = $_SERVER['REQUEST_TIME'] - $timer;
$day = floor($diff / 86400);
$free = $diff % 86400;
if ($day > 0) 
{
return $day . "天前";
}
else 
{
if ($free > 0) 
{
$hour = floor($free / 3600);
$free = $free % 3600;
if ($hour > 0) 
{
return $hour . "小时前";
}
else 
{
if ($free > 0) 
{
$min = floor($free / 60);
$free = $free % 60;
if ($min > 0) 
{
return $min . "分钟前";
}
else 
{
if ($free > 0) 
{
	return $free . "秒前";
}
else 
{
	return '刚刚';
}
}
}
else 
{
return '刚刚';
}
}
}
else 
{
return '刚刚';
}
}
}
public function showMessageAjax($msg, $status = 0, $success = 0) 
{
$result = array('Message' => $msg, 'Status' => $status, 'IsSuccess' => $success);
echo json_encode($result);
exit;
}
function authorization() 
{
$host = get_domain();
return base64_encode($host);
}
function code_compare($a, $b) 
{
if ($this->_debug == 1) 
{
if ($_SERVER['HTTP_HOST'] == '127.0.0.1') 
{
return true;
}
}
if ($a != $b) 
{
message("悟空源码网：www.5kym.com");
}
return true;
}
function isWeixin() 
{
if ($this->_weixin==1) 
{
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (!strpos($userAgent, 'MicroMessenger')) 
{
include $this->template('s404');
exit();
}
}
}
public function doWebIntroduce() 
{
global $_W, $_GPC;
checklogin();
load()->func('tpl');
$title = '夜店介绍';
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_introduce) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
if (!empty($item)) 
{
if (!empty($item['logo'])) 
{
$logo = tomedia($item['logo']);
}
}
if (checksubmit('submit')) 
{
$data = array();
$data['weid'] = intval($_W['uniacid']);
$data['title'] = trim($_GPC['title']);
$data['logo'] = trim($_GPC['logo']);
$data['info'] = trim($_GPC['info']);
$data['content'] = trim($_GPC['content']);
$data['savewinerule'] = trim($_GPC['savewinerule']);
$data['tel'] = trim($_GPC['tel']);
$data['address'] = trim($_GPC['address']);
$data['logo'] = trim($_GPC['logo']);
$data['location_p'] = trim($_GPC['location_p']);
$data['location_c'] = trim($_GPC['location_c']);
$data['location_a'] = trim($_GPC['location_a']);
$data['status'] = intval($_GPC['status']);
$data['contact'] = trim($_GPC['contact']);
$data['place'] = trim($_GPC['place']);
$data['hours'] = trim($_GPC['hours']);
$data['consume'] = trim($_GPC['consume']);
$data['wifi'] = trim($_GPC['wifi']);
$data['lng'] = trim($_GPC['lng']);
$data['lat'] = trim($_GPC['lat']);
$data['dateline'] = TIMESTAMP;
if (empty($item)) 
{
pdo_insert($this->table_introduce, $data);
}
else 
{
unset($data['dateline']);
pdo_update($this->table_introduce, $data, array('weid' => $_W['uniacid']));
}
message('操作成功', $this->createWebUrl('introduce'), 'success');
}
include $this->template('introduce');
}
public function doWebNeighborFeedback() 
{
global $_W, $_GPC;
checklogin();
load()->func('tpl');
$modulename = $this->modulename;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_feedback) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('抱歉，数据不存在或是已经删除！！', '', 'error');
}
if (!empty($item['headimgurl'])) 
{
if (strpos($item['headimgurl'], 'http') === false) 
{
$headimgurl = $_W['attachurl'] . $item['headimgurl'];
}
else 
{
$headimgurl = $item['headimgurl'];
}
}
}
else 
{
$item = array( 'dateline' => TIMESTAMP, 'status' => 1, );
}
if (checksubmit('submit')) 
{
$data = array( 'weid' => intval($_W['uniacid']), 'nickname' => trim($_GPC['nickname']), 'content' => trim($_GPC['content']), 'headimgurl' => trim($_GPC['headimgurl']), 'top' => intval($_GPC['top']), 'status' => intval($_GPC['status']), 'displayorder' => intval($_GPC['displayorder']), 'dateline' => TIMESTAMP, );
if (empty($data['nickname'])) 
{
message('请输入昵称！');
}
if (empty($id)) 
{
pdo_insert($this->table_neighbor_feedback, $data);
}
else 
{
unset($data['dateline']);
unset($data['activityId']);
pdo_update($this->table_neighbor_feedback, $data, array('id' => $id));
}
message('数据更新成功！', $this->createWebUrl('NeighborFeedback', array('op' => 'display')), 'success');
}
}
elseif ($operation == 'display') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_neighbor_feedback, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('NeighborFeedback', array('op' => 'display')), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = " WHERE weid = '{$_W['uniacid']}
' ";
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_neighbor_feedback) . " $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_neighbor_feedback) . " $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'delete') 
{
$id = intval($_GPC['id']);
$row = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_feedback) . " WHERE id = :id", array(':id' => $id));
if (empty($row)) 
{
message('抱歉，数据不存在或是已经被删除！');
}
pdo_delete($this->table_neighbor_feedback, array('id' => $id));
message('删除成功！', referer(), 'success');
}
elseif ($operation == 'check') 
{
//审核
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_neighbor_feedback, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('NeighborFeedback', array('op' => 'display')), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($_GPC['keyword'])) 
{
$condition .= " AND title LIKE '%{$_GPC['keyword']}
%'";
}
if (!empty($_GPC['category_id'])) 
{
$cid = intval($_GPC['category_id']);
$condition .= " AND pcate = '{$cid}
'";
}
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_neighbor_feedback) . " WHERE weid = '{$_W['uniacid']}
' AND mode=1 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_neighbor_feedback) . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'deleteall') 
{
$rowcount = 0;
$notrowcount = 0;
foreach ($_GPC['idArr'] as $k => $id) 
{
$id = intval($id);
if (!empty($id)) 
{
$feedback = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_feedback) . " WHERE id = :id", array(':id' => $id));
if (empty($feedback)) 
{
$notrowcount++;
continue;
}
pdo_delete($this->table_neighbor_feedback, array('id' => $id, 'weid' => $_W['uniacid']));
$rowcount++;
}
}
$this->message("操作成功！共删除{$rowcount}
条数据,{$notrowcount}
条数据不能删除!", '', 0);
}
elseif ($operation == 'checkall') 
{
$rowcount = 0;
$notrowcount = 0;
foreach ($_GPC['idArr'] as $k => $id) 
{
$id = intval($id);
if (!empty($id)) 
{
$feedback = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_feedback) . " WHERE id = :id", array(':id' => $id));
if (empty($feedback)) 
{
$notrowcount++;
continue;
}
$data = empty($feedback['status']) ? 1 : 0;
pdo_update($this->table_neighbor_feedback, array('status' => $data), array("id" => $id, "weid" => $_W['uniacid']));
$rowcount++;
}
}
$this->message("操作成功！共审核{$rowcount}
条数据,{$notrowcount}
条数据不能删除!!", '', 0);
}
include $this->template('neighbor_feedback');
}
public function doWebNeighbor() 
{
global $_W, $_GPC;
checklogin();
load()->func('tpl');
$modulename = $this->modulename;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('抱歉，数据不存在或是已经删除！！', '', 'error');
}
if (!empty($item['headimgurl'])) 
{
$headimgurl = tomedia($item['headimgurl']);
}
}
else 
{
$item = array( 'dateline' => TIMESTAMP, 'status' => 1, );
}
if (checksubmit('submit')) 
{
$data = array( 'weid' => intval($_W['uniacid']), 'from_user' => trim($_GPC['from_user']), 'nickname' => trim($_GPC['nickname']), 'weixin' => trim($_GPC['weixin']), 'tel' => trim($_GPC['tel']), 'headimgurl' => trim($_GPC['headimgurl']), 'status' => intval($_GPC['status']), 'displayorder' => intval($_GPC['displayorder']), 'dateline' => TIMESTAMP, );
if (empty($data['nickname'])) 
{
message('请输入昵称！');
}
if (empty($id)) 
{
pdo_insert($this->table_neighbor_user, $data);
}
else 
{
unset($data['dateline']);
pdo_update($this->table_neighbor_user, $data, array('id' => $id));
}
message('数据更新成功！', $this->createWebUrl('Neighbor', array('op' => 'display')), 'success');
}
}
elseif ($operation == 'display') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_neighbor_user, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('Neighbor', array('op' => 'display')), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = " WHERE weid = '{$_W['uniacid']}
' ";
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_neighbor_user) . " $condition ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_neighbor_user) . " $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'delete') 
{
$id = intval($_GPC['id']);
$row = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE id = :id", array(':id' => $id));
if (empty($row)) 
{
message('抱歉，数据不存在或是已经被删除！');
}
pdo_delete($this->table_neighbor_user, array('id' => $id));
message('删除成功！', referer(), 'success');
}
elseif ($operation == 'check') 
{
//审核
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_neighbor_user, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('Neighbor', array('op' => 'display')), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($_GPC['keyword'])) 
{
$condition .= " AND title LIKE '%{$_GPC['keyword']}
%'";
}
if (!empty($_GPC['category_id'])) 
{
$cid = intval($_GPC['category_id']);
$condition .= " AND pcate = '{$cid}
'";
}
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE weid = '{$_W['uniacid']}
' AND mode=1 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_neighbor_user) . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'deleteall') 
{
$rowcount = 0;
$notrowcount = 0;
foreach ($_GPC['idArr'] as $k => $id) 
{
$id = intval($id);
if (!empty($id)) 
{
$user = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE id = :id", array(':id' => $id));
if (empty($user)) 
{
$notrowcount++;
continue;
}
pdo_delete($this->table_neighbor_user, array('id' => $id, 'weid' => $_W['uniacid']));
$rowcount++;
}
}
$this->message("操作成功！共删除{$rowcount}
条数据,{$notrowcount}
条数据不能删除!", '', 0);
}
elseif ($operation == 'checkall') 
{
$rowcount = 0;
$notrowcount = 0;
foreach ($_GPC['idArr'] as $k => $id) 
{
$id = intval($id);
if (!empty($id)) 
{
$user = pdo_fetch("SELECT * FROM " . tablename($this->table_neighbor_user) . " WHERE id = :id", array(':id' => $id));
if (empty($user)) 
{
$notrowcount++;
continue;
}
$data = empty($user['status']) ? 1 : 0;
pdo_update($this->table_neighbor_user, array('status' => $data), array("id" => $id, "weid" => $_W['uniacid']));
$rowcount++;
}
}
$this->message("操作成功！共审核{$rowcount}
条数据,{$notrowcount}
条数据不能删除!!", '', 0);
}
include $this->template('neighbor_user');
}
public function doWebSetting() 
{
global $_W, $_GPC;
$weid = $_W['uniacid'];
load()->func('tpl');
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $_W['uniacid']));
if (!empty($setting)) 
{
if (!empty($setting['share_image'])) 
{
$share_image = tomedia($setting['share_image']);
}
if (!empty($setting['bg'])) 
{
$bg = tomedia($setting['bg']);
}
else 
{
$bg = '../addons/weisrc_nightclub/template/images/nurse.jpg';
}
}
if (checksubmit('submit')) 
{
$data = array( 'weid' => $_W['uniacid'], 'title' => trim($_GPC['title']), 'pagesize' => intval($_GPC['pagesize']), 'topcolor' => trim($_GPC['topcolor']), 'topbgcolor' => trim($_GPC['topbgcolor']), 'announcebordercolor' => trim($_GPC['announcebordercolor']), 'announcebgcolor' => trim($_GPC['announcebgcolor']), 'announcecolor' => trim($_GPC['announcecolor']), 'storestitlecolor' => trim($_GPC['storestitlecolor']), 'storesstatuscolor' => trim($_GPC['storesstatuscolor']), 'showcity' => intval($_GPC['showcity']), 'settled' => intval($_GPC['settled']), 'feedback_show_enable' => intval($_GPC['feedback_show_enable']), 'feedback_check_enable' => intval($_GPC['feedback_check_enable']), 'photo_check_enable' => intval($_GPC['photo_check_enable']), 'scroll_announce_enable' => intval($_GPC['scroll_announce_enable']), 'scroll_announce' => trim($_GPC['scroll_announce']), 'scroll_announce_link' => trim($_GPC['scroll_announce_link']), 'copyright' => trim($_GPC['copyright']), 'copyright_link' => trim($_GPC['copyright_link']), 'scroll_announce_speed' => intval($_GPC['scroll_announce_speed']), 'appid' => trim($_GPC['appid']), 'secret' => trim($_GPC['secret']), 'tplinfowine' => trim($_GPC['tplinfowine']), 'share_title' => $_GPC['share_title'], 'share_desc' => $_GPC['share_desc'], 'share_cancel' => $_GPC['share_cancel'], 'share_url' => $_GPC['share_url'], 'follow_url' => $_GPC['follow_url'], 'share_image' => trim($_GPC['share_image']), 'bg' => trim($_GPC['bg']), 'dateline' => TIMESTAM, );
if (empty($setting)) 
{
pdo_insert($this->table_setting, $data);
}
else 
{
unset($data['dateline']);
pdo_update($this->table_setting, $data, array('weid' => $_W['uniacid']));
}
message('操作成功', $this->createWebUrl('setting'), 'success');
}
include $this->template('setting');
}
public function doWebPhoto() 
{
global $_W, $_GPC;
checklogin();
$weid = $_W['uniacid'];
$modulename = $this->modulename;
$mode = intval($_GPC['mode']);
$status = intval($_GPC['status']);
$pindex = max(1, intval($_GPC['page']));
$psize = 5;
$condition = '';
if ($mode == 1) 
{
$condition .= " AND mode=1 AND status={$status}
";
}
$photos = pdo_fetchall("SELECT * FROM " . tablename($this->table_photo) . " WHERE weid=:weid  {$condition}
ORDER BY displayorder DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $weid));
$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_photo) . " WHERE weid = :weid $condition", array(':weid' => $weid));
$photo_total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_photo) . " WHERE weid = :weid ", array(':weid' => $weid));
$photo_pass_total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_photo) . " WHERE weid = :weid AND mode=1 AND status=1", array(':weid' => $weid));
$photo_nopass_total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_photo) . " WHERE weid = :weid AND mode=1 AND status=0", array(':weid' => $weid));
$pager = pagination($total, $pindex, $psize);
if (checksubmit('submit')) 
{
if (!empty($_GPC['attachment-new'])) 
{
foreach ($_GPC['attachment-new'] as $index => $row) 
{
if (empty($row)) 
{
continue;
}
$data = array( 'weid' => $_W['uniacid'], 'title' => $_GPC['title-new'][$index], 'description' => $_GPC['description-new'][$index], 'attachment' => $_GPC['attachment-new'][$index], 'url' => $_GPC['url-new'][$index], 'isfirst' => 0, 'mode' => 0, 'status' => $_GPC['status-new'][$index], 'displayorder' => $_GPC['displayorder-new'][$index], );
pdo_insert($this->table_photo, $data);
}
}
if (!empty($_GPC['attachment'])) 
{
foreach ($_GPC['attachment'] as $index => $row) 
{
if (empty($row)) 
{
continue;
}
$data = array( 'weid' => $_W['uniacid'], 'title' => $_GPC['title'][$index], 'description' => $_GPC['description'][$index], 'attachment' => $_GPC['attachment'][$index], 'url' => $_GPC['url'][$index], 'isfirst' => intval($_GPC['isfirst'][$index]), 'displayorder' => $_GPC['displayorder'][$index], 'status' => $_GPC['status'][$index], );
pdo_update($this->table_photo, $data, array('id' => $index));
}
}
message('幻灯片更新成功！', $this->createWebUrl('photo'));
}
include $this->template('photo');
}
public function doWebPhotoDelete() 
{
global $_W, $_GPC;
$type = $_GPC['type'];
$id = intval($_GPC['id']);
if ($type == 'photo') 
{
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_photo) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('图片不存在或是已经被删除！');
}
pdo_delete($this->table_photo, array('id' => $item['id']));
}
else 
{
$item['attachment'] = $_GPC['attachment'];
}
load()->func('file');
file_delete($item['attachment']);
}
message('删除成功！', referer(), 'success');
}
public function doWebActivity() 
{
global $_W, $_GPC;
checklogin();
load()->func('tpl');
$modulename = $this->modulename;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_activity) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('抱歉，活动不存在或是已经删除！', '', 'error');
}
}
if (!empty($item)) 
{
$logo = tomedia($item['logo']);
$starttime = date('Y-m-d H:i', $item['start_time']);
$endtime = date('Y-m-d H:i', $item['end_time']);
}
else 
{
$starttime = date('Y-m-d H:i');
$endtime = date('Y-m-d H:i', TIMESTAMP+86400*30);
}
if (checksubmit('submit')) 
{
$data = array( 'weid' => intval($_W['uniacid']), 'title' => trim($_GPC['title']), 'logo' => trim($_GPC['logo']), 'content' => trim($_GPC['content']), 'tel' => trim($_GPC['tel']), 'address' => trim($_GPC['address']), 'isfirst' => intval($_GPC['isfirst']), 'start_time' => strtotime($_GPC['start_time']), 'end_time' => strtotime($_GPC['end_time']), 'status' => intval($_GPC['status']), 'dateline' => TIMESTAMP, 'displayorder' => intval($_GPC['displayorder']), );
if (empty($data['title'])) 
{
message('请输入活动名称！');
}
if ($data['endtime'] < $data['starttime']) 
{
message('请输入正确的时间区间！');
}
if (empty($id)) 
{
pdo_insert($this->table_activity, $data);
}
else 
{
unset($data['dateline']);
pdo_update($this->table_activity, $data, array('id' => $id));
}
message('数据更新成功！', $this->createWebUrl('activity', array('op' => 'display')), 'success');
}
}
elseif ($operation == 'display') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_activity, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('activity', array('op' => 'display')), 'success');
}
$feedback = pdo_fetchall("SELECT activityid,COUNT(1) as count FROM ".tablename($this->table_activity_feedback)."  GROUP BY activityid,weid having weid = :weid", array(':weid' => $_W['uniacid']), 'activityid');
$user = pdo_fetchall("SELECT activityid,COUNT(1) as count FROM ".tablename($this->table_activity_user)."  GROUP BY activityid,weid having weid = :weid", array(':weid' => $_W['uniacid']), 'activityid');
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($_GPC['keyword'])) 
{
$condition .= " AND title LIKE '%{$_GPC['keyword']}
%'";
}
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity) . " WHERE weid = '{$_W['uniacid']}
' $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_activity) . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'delete') 
{
$id = intval($_GPC['id']);
$row = pdo_fetch("SELECT * FROM " . tablename($this->table_activity) . " WHERE id = :id", array(':id' => $id));
if (empty($row)) 
{
message('抱歉，数据不存在或是已经被删除！');
}
if (!empty($row['logo'])) 
{
file_delete($row['logo']);
}
pdo_delete($this->table_activity, array('id' => $id));
message('删除成功！', referer(), 'success');
}
elseif ($operation == 'check') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_activity, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('activity', array('op' => 'display')), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($_GPC['keyword'])) 
{
$condition .= " AND title LIKE '%{$_GPC['keyword']}
%'";
}
if (!empty($_GPC['category_id'])) 
{
$cid = intval($_GPC['category_id']);
$condition .= " AND pcate = '{$cid}
'";
}
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity) . " WHERE weid = '{$_W['uniacid']}
' AND mode=1 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_activity) . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
else if ($operation == 'checkdetail') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_activity) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('抱歉，商家不存在或是已经删除！', '', 'error');
}
}
if (checksubmit('submit')) 
{
$data = array( 'checked' => intval($_GPC['checked']), 'status' => intval($_GPC['status']), );
pdo_update($this->table_activity, $data, array('id' => $id));
message('数据更新成功！', $this->createWebUrl('activity', array('op' => 'check')), 'success');
}
}
include $this->template('activity');
}
public function doWebFeedback() 
{
global $_W, $_GPC;
checklogin();
load()->func('tpl');
$modulename = $this->modulename;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$activityId = intval($_GPC['activityId']);
if ($operation == 'post') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_feedback) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('抱歉，数据不存在或是已经删除！！', '', 'error');
}
if (!empty($item['headimgurl'])) 
{
if (strpos($item['headimgurl'], 'http') === false) 
{
$headimgurl = $_W['attachurl'] . $item['headimgurl'];
}
else 
{
$headimgurl = $item['headimgurl'];
}
}
}
else 
{
$item = array( 'dateline' => TIMESTAMP, 'status' => 1, );
}
if (checksubmit('submit')) 
{
$data = array( 'weid' => intval($_W['uniacid']), 'activityId' => $activityId, 'nickname' => trim($_GPC['nickname']), 'content' => trim($_GPC['content']), 'top' => intval($_GPC['top']), 'status' => intval($_GPC['status']), 'headimgurl' => trim($_GPC['headimgurl']), 'displayorder' => intval($_GPC['displayorder']), 'dateline' => TIMESTAMP, );
if (empty($data['nickname'])) 
{
message('请输入昵称！');
}
if (empty($id)) 
{
pdo_insert($this->table_activity_feedback, $data);
}
else 
{
unset($data['dateline']);
unset($data['activityId']);
pdo_update($this->table_activity_feedback, $data, array('id' => $id));
}
message('数据更新成功！', $this->createWebUrl('feedback', array('op' => 'display', 'activityId' => $activityId)), 'success');
}
}
elseif ($operation == 'display') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_activity_feedback, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('feedback', array('op' => 'display', 'activityId' => $activityId)), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = " WHERE weid = '{$_W['uniacid']}
' AND activityId={$activityId}
";
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity_feedback) . " $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_activity_feedback) . " $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'delete') 
{
$id = intval($_GPC['id']);
$row = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_feedback) . " WHERE id = :id", array(':id' => $id));
if (empty($row)) 
{
message('抱歉，数据不存在或是已经被删除！');
}
pdo_delete($this->table_activity_feedback, array('id' => $id));
message('删除成功！', referer(), 'success');
}
elseif ($operation == 'check') 
{
//审核
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_activity_feedback, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('feedback', array('op' => 'display', 'activityId' => $activityId)), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($_GPC['keyword'])) 
{
$condition .= " AND title LIKE '%{$_GPC['keyword']}
%'";
}
if (!empty($_GPC['category_id'])) 
{
$cid = intval($_GPC['category_id']);
$condition .= " AND pcate = '{$cid}
'";
}
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity_feedback) . " WHERE weid = '{$_W['uniacid']}
' AND mode=1 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_activity_feedback) . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'deleteall') 
{
$rowcount = 0;
$notrowcount = 0;
foreach ($_GPC['idArr'] as $k => $id) 
{
$id = intval($id);
if (!empty($id)) 
{
$feedback = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_feedback) . " WHERE id = :id", array(':id' => $id));
if (empty($feedback)) 
{
$notrowcount++;
continue;
}
pdo_delete($this->table_activity_feedback, array('id' => $id, 'weid' => $_W['uniacid']));
$rowcount++;
}
}
$this->message("操作成功！共删除{$rowcount}
条数据,{$notrowcount}
条数据不能删除!", '', 0);
}
elseif ($operation == 'checkall') 
{
$rowcount = 0;
$notrowcount = 0;
foreach ($_GPC['idArr'] as $k => $id) 
{
$id = intval($id);
if (!empty($id)) 
{
$feedback = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_feedback) . " WHERE id = :id", array(':id' => $id));
if (empty($feedback)) 
{
$notrowcount++;
continue;
}
$data = empty($feedback['status']) ? 1 : 0;
pdo_update($this->table_activity_feedback, array('status' => $data), array("id" => $id, "weid" => $_W['uniacid']));
$rowcount++;
}
}
$this->message("操作成功！共审核{$rowcount}
条数据,{$notrowcount}
条数据不能删除!!", '', 0);
}
include $this->template('feedback');
}
public function doWebUser() 
{
global $_W, $_GPC;
checklogin();
load()->func('tpl');
$modulename = $this->modulename;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$activityId = intval($_GPC['activityId']);
if ($operation == 'post') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_user) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('抱歉，数据不存在或是已经删除！！', '', 'error');
}
if (!empty($item['headimgurl'])) 
{
if (strpos($item['headimgurl'], 'http') === false) 
{
$headimgurl = $_W['attachurl'] . $item['headimgurl'];
}
else 
{
$headimgurl = $item['headimgurl'];
}
}
}
else 
{
$item = array( 'dateline' => TIMESTAMP, 'status' => 1, );
}
if (checksubmit('submit')) 
{
$data = array( 'weid' => intval($_W['uniacid']), 'activityId' => $activityId, 'nickname' => trim($_GPC['nickname']), 'headimgurl' => trim($_GPC['headimgurl']), 'status' => intval($_GPC['status']), 'displayorder' => intval($_GPC['displayorder']), 'dateline' => TIMESTAMP, );
if (empty($data['nickname'])) 
{
message('请输入昵称！');
}
if (empty($id)) 
{
pdo_insert($this->table_activity_user, $data);
}
else 
{
unset($data['dateline']);
unset($data['activityId']);
pdo_update($this->table_activity_user, $data, array('id' => $id));
}
message('数据更新成功！', $this->createWebUrl('user', array('op' => 'display', 'activityId' => $activityId)), 'success');
}
}
elseif ($operation == 'display') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_activity_user, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('user', array('op' => 'display', 'activityId' => $activityId)), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = " WHERE weid = '{$_W['uniacid']}
' AND activityId={$activityId}
";
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity_user) . " $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_activity_user) . " $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'delete') 
{
$id = intval($_GPC['id']);
$row = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_user) . " WHERE id = :id", array(':id' => $id));
if (empty($row)) 
{
message('抱歉，数据不存在或是已经被删除！');
}
pdo_delete($this->table_activity_user, array('id' => $id));
message('删除成功！', referer(), 'success');
}
elseif ($operation == 'check') 
{
//审核
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_activity_user, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('user', array('op' => 'display', 'activityId' => $activityId)), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($_GPC['keyword'])) 
{
$condition .= " AND title LIKE '%{$_GPC['keyword']}
%'";
}
if (!empty($_GPC['category_id'])) 
{
$cid = intval($_GPC['category_id']);
$condition .= " AND pcate = '{$cid}
'";
}
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_activity_user) . " WHERE weid = '{$_W['uniacid']}
' AND mode=1 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_activity_user) . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'deleteall') 
{
$rowcount = 0;
$notrowcount = 0;
foreach ($_GPC['idArr'] as $k => $id) 
{
$id = intval($id);
if (!empty($id)) 
{
$user = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_user) . " WHERE id = :id", array(':id' => $id));
if (empty($user)) 
{
$notrowcount++;
continue;
}
pdo_delete($this->table_activity_user, array('id' => $id, 'weid' => $_W['uniacid']));
$rowcount++;
}
}
$this->message("操作成功！共删除{$rowcount}
条数据,{$notrowcount}
条数据不能删除!", '', 0);
}
elseif ($operation == 'checkall') 
{
$rowcount = 0;
$notrowcount = 0;
foreach ($_GPC['idArr'] as $k => $id) 
{
$id = intval($id);
if (!empty($id)) 
{
$user = pdo_fetch("SELECT * FROM " . tablename($this->table_activity_user) . " WHERE id = :id", array(':id' => $id));
if (empty($user)) 
{
$notrowcount++;
continue;
}
$data = empty($user['status']) ? 1 : 0;
pdo_update($this->table_activity_user, array('status' => $data), array("id" => $id, "weid" => $_W['uniacid']));
$rowcount++;
}
}
$this->message("操作成功！共审核{$rowcount}
条数据,{$notrowcount}
条数据不能删除!!", '', 0);
}
include $this->template('user');
}
public function doWebSaveWine() 
{
global $_W, $_GPC;
checklogin();
load()->func('tpl');
$modulename = $this->modulename;
$status = !isset($_GPC['status']) ? -2 : $_GPC['status'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('抱歉，活动不存在或是已经删除！', '', 'error');
}
}
if (!empty($item)) 
{
if (!empty($item['headimgurl'])) 
{
$headimgurl = tomedia($item['headimgurl']);
}
}
if (checksubmit('submit')) 
{
$data = array( 'weid' => intval($_W['uniacid']), 'savenumber' => trim($_GPC['savenumber']), 'nickname' => trim($_GPC['nickname']), 'headimgurl' => trim($_GPC['headimgurl']), 'title' => trim($_GPC['title']), 'username' => trim($_GPC['username']), 'tel' => trim($_GPC['tel']), 'remark' => trim($_GPC['remark']), 'status' => intval($_GPC['status']), 'dateline' => TIMESTAMP, );
if ($status == 1) 
{
$data['savetime'] = TIMESTAMP;
}
if ($status == -1) 
{
$data['takeouttime'] = TIMESTAMP;
}
if (empty($id)) 
{
pdo_insert($this->table_savewine_log, $data);
}
else 
{
unset($data['dateline']);
pdo_update($this->table_savewine_log, $data, array('id' => $id));
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid ", array(':weid' => $this->_weid));
if (!empty($setting['tplinfowine'])) 
{
$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array(), true);
$templateid = $setting['tplinfowine'];
$noticeuser = $item['from_user'];
$notictitle = '';
if ($status == 1) 
{
$notictitle = '你好,您已成功寄存酒水.';
}
if ($status == -1) 
{
$notictitle = '你好,您已成功取出酒水.';
}
if (!empty($notictitle)) 
{
$template = array( 'touser' => $noticeuser, 'template_id' => $templateid, 'url' => $url, 'topcolor' => "#000000", 'data' => array( 'first' => array( 'value' => urlencode($notictitle), 'color' => '#000000' ), 'keyword1' => array( 'value' => urlencode($data['savenumber']),//酒卡号
'color' => '#FF0000' ), 'keyword2' => array( 'value' => urlencode(date('Y-m-d')),//时间
'color' => '#000000' ), 'remark' => array( 'value' => urlencode("欢迎再次光临."), 'color' => '#000000' ), ) );
$account = $this->get_account($_W['uniacid']);
$this->_appid = $account['key'];
$this->_appsecret = $account['secret'];
$templateMessage = new class_templateMessage($this->_appid, $this->_appsecret);
$access_token = WeAccount::token();
$templateMessage->send_template_message($template, $access_token);
}
}
}
message('数据更新成功！', $this->createWebUrl('savewine', array('op' => 'display')), 'success');
}
}
elseif ($operation == 'display') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_savewine, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('savewine', array('op' => 'display')), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($_GPC['keyword'])) 
{
$condition .= " AND (title LIKE '%{$_GPC['keyword']}
%' OR savenumber LIKE '%{$_GPC['keyword']}
%' OR username LIKE '%{$_GPC['keyword']}
%') ";
}
if ($status != -2) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE weid = '{$_W['uniacid']}
' $condition ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_savewine_log) . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'delete') 
{
$id = intval($_GPC['id']);
$row = pdo_fetch("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE id = :id", array(':id' => $id));
if (empty($row)) 
{
message('抱歉，数据不存在或是已经被删除！');
}
if (!empty($row['logo'])) 
{
file_delete($row['logo']);
}
pdo_delete($this->table_savewine_log, array('id' => $id));
message('删除成功！', referer(), 'success');
}
elseif ($operation == 'check') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_savewine_log, array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('savewine', array('op' => 'display')), 'success');
}
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($_GPC['keyword'])) 
{
$condition .= " AND title LIKE '%{$_GPC['keyword']}
%'";
}
if (!empty($_GPC['category_id'])) 
{
$cid = intval($_GPC['category_id']);
$condition .= " AND pcate = '{$cid}
'";
}
if ($status != -2) 
{
$condition .= " AND status = '" . intval($_GPC['status']) . "'";
}
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE weid = '{$_W['uniacid']}
' AND mode=1 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_savewine_log) . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
else if ($operation == 'checkdetail') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM " . tablename($this->table_savewine_log) . " WHERE id = :id", array(':id' => $id));
if (empty($item)) 
{
message('抱歉，商家不存在或是已经删除！', '', 'error');
}
}
if (checksubmit('submit')) 
{
$data = array( 'checked' => intval($_GPC['checked']), 'status' => intval($_GPC['status']), );
pdo_update($this->table_savewine_log, $data, array('id' => $id));
message('数据更新成功！', $this->createWebUrl('savewine', array('op' => 'check')), 'success');
}
}
include $this->template('savewine');
}
public function get_account($weid) 
{
$account = pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid = '$weid' LIMIT 1");
return $account;
}
public function doWebSetActivityProperty() 
{
global $_GPC, $_W;
$id = intval($_GPC['id']);
$type = $_GPC['type'];
$data = intval($_GPC['data']);
empty($data) ? ($data = 1) : $data = 0;
if (!in_array($type, array('isfirst', 'status', 'top'))) 
{
die(json_encode(array("result" => 0)));
}
pdo_update($this->table_activity, array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
die(json_encode(array("result" => 1, "data" => $data)));
}
public function doWebCategory() 
{
global $_GPC, $_W;
checklogin();
$action = 'category';
$title = '商品类别';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->table_category, array('displayorder' => $displayorder), array('id' => $id));
}
message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
}
$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = '{$_W['uniacid']}
' ORDER BY displayorder DESC,id DESC");
}
elseif ($operation == 'post') 
{
$id = intval($_GPC['id']);
$category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE id = '$id'");
if (!empty($parentid)) 
{
$parent = pdo_fetch("SELECT id, name FROM " . tablename($this->table_category) . " WHERE id = '$parentid'");
if (empty($parent)) 
{
message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
}
}
if (checksubmit('submit')) 
{
if (empty($_GPC['catename'])) 
{
message('抱歉，请输入分类名称！');
}
$data = array( 'weid' => $_W['uniacid'], 'name' => $_GPC['catename'], 'displayorder' => intval($_GPC['displayorder']), 'status' => intval($_GPC['status']), );
if (!empty($id)) 
{
pdo_update($this->table_category, $data, array('id' => $id));
}
else 
{
pdo_insert($this->table_category, $data);
$id = pdo_insertid();
}
message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
}
}
elseif ($operation == 'delete') 
{
$id = intval($_GPC['id']);
$category = pdo_fetch("SELECT id, parentid FROM " . tablename($this->table_category) . " WHERE id = '$id'");
if (empty($category)) 
{
message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
}
pdo_delete($this->table_category, array('id' => $id, 'parentid' => $id), 'OR');
message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
}
include $this->template('category');
}
public function doWebCabinet() 
{
global $_GPC, $_W;
checklogin();
load()->func('tpl');
$action = 'goods';
$category = pdo_fetchall("SELECT * FROM ".tablename($this->modulename.'_category')." WHERE weid = '{$_W['uniacid']}
' ORDER BY displayorder DESC,id DESC", array(), 'id');
$count = count($category);
if ($count == 0) 
{
message('请先添加分类！', $this->createWebUrl('category', array('op' => 'post')), 'error');
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') 
{
$id = intval($_GPC['id']);
if (!empty($id)) 
{
$item = pdo_fetch("SELECT * FROM ".tablename($this->modulename.'_goods')." WHERE id = :id" , array(':id' => $id));
}
if (!empty($item)) 
{
if (!empty($item['thumb'])) 
{
if (strpos($item['thumb'], 'http') === false) 
{
$thumb = $_W['attachurl'] . $item['thumb'];
}
else 
{
$thumb = $item['thumb'];
}
}
}
if (checksubmit('submit')) 
{
$data = array( 'weid' => intval($_W['uniacid']), 'title' => trim($_GPC['goodsname']), 'pcate' => intval($_GPC['pcate']), 'ccate' => intval($_GPC['ccate']), 'thumb' => trim($_GPC['thumb']), 'description' => trim($_GPC['description']), 'marketprice' => trim($_GPC['marketprice']), 'productprice' => trim($_GPC['productprice']), 'status' => intval($_GPC['status']), 'displayorder' => intval($_GPC['displayorder']), 'dateline' => TIMESTAMP, );
if (empty($data['title'])) 
{
message('请输入商品名称！');
}
if (empty($data['pcate'])) 
{
message('请选择商品分类！');
}
if (empty($id)) 
{
pdo_insert($this->modulename.'_goods', $data);
}
else 
{
unset($data['createtime']);
pdo_update($this->modulename.'_goods', $data, array('id' => $id));
}
message('商品更新成功！', $this->createWebUrl('cabinet', array('op' => 'display')), 'success');
}
}
elseif ($operation == 'display') 
{
if (!empty($_GPC['displayorder'])) 
{
foreach ($_GPC['displayorder'] as $id => $displayorder) 
{
pdo_update($this->modulename.'_goods', array('displayorder' => $displayorder), array('id' => $id));
}
message('排序更新成功！', $this->createWebUrl('cabinet', array('op' => 'display')), 'success');
}
$keyword = trim($_GPC['keyword']);
$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$condition = '';
if (!empty($keyword)) 
{
$condition .= " AND title LIKE '%{$keyword}
%'";
}
if (!empty($_GPC['category_id'])) 
{
$cid = intval($_GPC['category_id']);
$condition .= " AND pcate = '{$cid}
'";
}
if (isset($_GPC['status'])) 
{
$condition .= " AND status = '".intval($_GPC['status'])."'";
}
$list = pdo_fetchall("SELECT * FROM ".tablename($this->modulename.'_goods')." WHERE weid = '{$_W['uniacid']}
' $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->modulename.'_goods') . " WHERE weid = '{$_W['uniacid']}
' $condition");
$pager = pagination($total, $pindex, $psize);
}
elseif ($operation == 'delete') 
{
$id = intval($_GPC['id']);
$row = pdo_fetch("SELECT id, thumb FROM ".tablename($this->modulename.'_goods')." WHERE id = :id", array(':id' => $id));
if (empty($row)) 
{
message('抱歉，数据不存在或是已经被删除！');
}
if (!empty($row['thumb'])) 
{
file_delete($row['thumb']);
}
pdo_delete($this->modulename.'_goods', array('id' => $id));
message('删除成功！', referer(), 'success');
}
include $this->template('goods');
}
public function set_tabbar($action, $storeid) 
{
$actions_titles = $this->actions_titles;
$html = '<ul class="nav nav-tabs">';
foreach ($actions_titles as $key => $value) 
{
$url = 'site.php?act=module&do='.$key.'&name='.$this->modulename.'&storeid='.$storeid;
$html .= '<li class="'. ($key == $action ? 'active' : '') .'"><a href="'.$url.'">'.$value.'</a></li>';
}
$html .= '</ul>';
return $html;
}
public function doSetRule() 
{
global $_W;
$rule = pdo_fetch("SELECT id FROM ".tablename('rule')." WHERE module = 'weisrc_nightclub' AND weid = '{$_W['uniacid']}
' order by id desc");
if (empty($rule)) 
{
header('Location: '.$_W['siteroot'] . create_url('rule/post', array('module' => 'weisrc_nightclub', 'name' => '微夜店')));
exit;
}
else 
{
header('Location: '.$_W['siteroot'] . create_url('rule/post', array('module' => 'weisrc_nightclub', 'id' => $rule['id'])));
exit;
}
}
public function message($error, $url = '', $errno = -1) 
{
$data = array();
$data['errno'] = $errno;
if (!empty($url)) 
{
$data['url'] = $url;
}
$data['error'] = $error;
echo json_encode($data);
exit;
}
public function oauth2($url) 
{
global $_GPC, $_W;
load()->func('communication');
$code = $_GPC['code'];
$token = $this->getAuthorizationCode($code);
$from_user = $token['openid'];
$userinfo = $this->getUserInfo($from_user);
if ($userinfo['subscribe'] == 0) 
{
$authkey = intval($_GPC['authkey']);
if ($authkey == 0) 
{
$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
header("location:$oauth2_code");
}
$userinfo = $this->getUserInfo($from_user, $token['access_token']);
}
if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) 
{
echo '<h1>获取微信公众号授权失败[无法取得userinfo], 请稍后重试！公众平台返回原始数据为: <br />' . $userinfo['meta'] . '<h1>';
exit;
}
$headimgurl = $userinfo['headimgurl'];
$nickname = $userinfo['nickname'];
$time = TIMESTAMP + 3600 * 24;
setcookie($this->_auth2_headimgurl, $headimgurl, $time);
setcookie($this->_auth2_nickname, $nickname, $time);
setcookie($this->_auth2_openid, $from_user, $time);
return $userinfo;
}
public function getUserInfo($from_user, $ACCESS_TOKEN = '') 
{
if ($ACCESS_TOKEN == '') 
{
$ACCESS_TOKEN = $this->getAccessToken();
$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}
&openid={$from_user}
&lang=zh_CN";
}
else 
{
$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}
&openid={$from_user}
&lang=zh_CN";
}
$json = ihttp_get($url);
$userInfo = @json_decode($json['content'], true);
return $userInfo;
}
public function getAuthorizationCode($code) 
{
$oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}
&secret={$this->_appsecret}
&code={$code}
&grant_type=authorization_code";
$content = ihttp_get($oauth2_code);
$token = @json_decode($content['content'], true);
if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) 
{
echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
exit;
}
return $token;
}
public function getAccessToken() 
{
global $_W;
$account = $_W['account'];
if($this->_accountlevel < 4)
{
if (!empty($this->_account)) 
{
$account = $this->_account;
}
}
load()->classs('weixin.account');
$accObj= WeixinAccount::create($account['acid']);
$access_token = $accObj->fetch_token();
return $access_token;
}
public function getCode($url) 
{
global $_W;
$url = urlencode($url);
$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}
&redirect_uri={$url}
&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
header("location:$oauth2_code");
}
}
?>