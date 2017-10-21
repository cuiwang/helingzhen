<?php

defined('IN_IA') or exit('Access Denied');
include 'model.php';
include 'templateMessage.php';
define(EARTH_RADIUS, 6371);
define('RES', '../addons/com_yuefei/template/');
class com_yuefeiModuleSite extends WeModuleSite {
	public $modulename = 'com_yuefei';
	public $cur_tpl = 'style1';
	public $member_code = '';
	public $feyin_key = '';
	public $device_no = '';
	public $msg_status_success = 1;
	public $msg_status_bad = 0;
	public $_debug = '1';
	public $_weixin = '1';
	public $_appid = '';
	public $_appsecret = '';
	public $_accountlevel = '';
	public $_account = '';
	public $_uniacid = '';
	public $_fromuser = '';
	public $_nickname = '';
	public $_headimgurl = '';
	public $_auth2_openid = '';
	public $_auth2_nickname = '';
	public $_auth2_headimgurl = '';
	public $table_goods = 'com_yuefei_goods';
	public $table_nave = 'com_yuefei_nave';
	public $table_order = 'com_yuefei_order';
	public $table_setting = 'com_yuefei_setting';
	public $table_ad = 'com_yuefei_ad';
	public $table_article = 'com_yuefei_article';
	public $table_fans = 'com_yuefei_fans';
	public $actions_titles = array('fans' => '用户管理', 'order' => '交易记录', 'help' => '使用教程', 'setting' => '系统设置');
	private $version = '';
	public $copyright = '110001 110101 110101 111001 110101 110111 110101 110101';
	public function __construct() {
		global $_W;
		global $_GPC;
		$this->_fromuser = $_W['fans']['from_user'];
		if (($_SERVER['HTTP_HOST'] == '127.0.0.1') || ($_SERVER['HTTP_HOST'] == '192.168.1.101')) {
			$this->_fromuser = 'debug';
		}
		$this->_uniacid = $_W['uniacid'];
		$account = $_W['account'];
		$this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
		$this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
		$this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];
		$this->_appid = '';
		$this->_appsecret = '';
		$this->_accountlevel = $account['level'];
		if (isset($_COOKIE[$this->_auth2_openid])) {
			$this->_fromuser = $_COOKIE[$this->_auth2_openid];
		}
		if ($this->_accountlevel < 4) {
			$setting = uni_setting($this->_uniacid);
			$oauth = $setting['oauth'];
			if (!empty($oauth['account'])) {
				$this->_account = account_fetch($oauth['account']);
				$this->_appid = $this->_account['key'];
				$this->_appsecret = $this->_account['secret'];
			}
			if (NULL) {
			} else {
				global $_W;
				global $_GPC;
				$this->_fromuser = $_W['fans']['from_user'];
				$this->_fromuser = 'debug';
				$this->_uniacid = $_W['uniacid'];
				$account = $_W['account'];
				$this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
				$this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
				$this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];
				$this->_appid = '';
				$this->_appsecret = '';
				$this->_accountlevel = $account['level'];
				$this->_fromuser = $_COOKIE[$this->_auth2_openid];
				$setting = uni_setting($this->_uniacid);
				$oauth = $setting['oauth'];
				$this->_account = account_fetch($oauth['account']);
				$this->_appid = $this->_account['key'];
				$this->_appsecret = $this->_account['secret'];
				return NULL;
			}
		} else {
			$this->_appid = $_W['account']['key'];
			$this->_appsecret = $_W['account']['secret'];
		}
	}
	public function doMobileIndex() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		$method = 'index';
		$host = $this->getOAuthHost();
		$authurl = $host . 'app/' . $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $host . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) {
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		} else if (isset($_GPC['code'])) {
			$userinfo = $this->oauth2($authurl);
			if (!empty($userinfo)) {
				$from_user = $userinfo['openid'];
				$nickname = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
			} else {
				message('授权失败!');
			}
		} else if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
		$setting = $this->getSetting();
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		if (empty($fans) && !empty($from_user)) {
			$insert = array('uniacid' => $uniacid, 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'dateline' => TIMESTAMP);
			$taste_vip = intval($setting['taste_vip']);
			if (0 < $taste_vip) {
				$insert['is_vip'] = 1;
				$insert['endtime'] = strtotime('+' . $taste_vip . ' day');
			}
			pdo_insert($this->table_fans, $insert);
		} else {
			$update = array('nickname' => $nickname, 'headimgurl' => $headimgurl);
			pdo_update($this->table_fans, $update, array('id' => $fans['id']));
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		$isvip = 0;
		if ($fans['is_vip'] == 1) {
			if (TIMESTAMP < $fans['endtime']) {
				$second = $fans['endtime'] - TIMESTAMP;
				if (0 < $second) {
					$isvip = 1;
					floor($second / (3600 * 24));
				}
			}
		}
		$total = pdo_fetchcolumn('SELECT count(1) FROM ' . tablename($this->table_article) . ' WHERE uniacid = :uniacid AND from_user=:from_user ', array(':uniacid' => $uniacid, ':from_user' => $from_user));
		$readcount = pdo_fetchcolumn('SELECT sum(readcount) FROM ' . tablename($this->table_article) . ' WHERE uniacid = :uniacid AND from_user=:from_user ', array(':uniacid' => $uniacid, ':from_user' => $from_user));
		$sharecount = pdo_fetchcolumn('SELECT sum(sharecount) FROM ' . tablename($this->table_article) . ' WHERE uniacid = :uniacid AND from_user=:from_user ', array(':uniacid' => $uniacid, ':from_user' => $from_user));
		$setting = $this->getSetting();
		$title = ((empty($setting['title']) ? '一秒广告' : $setting['title']));
		$share_title = trim($setting['share_title']);
		$share_desc = trim($setting['share_desc']);
		$share_url = ((empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array(), true) : trim($setting['share_url'])));
		$share_image = tomedia($setting['share_image']);
		include $this->template('index');
	}
	//易福源码网 www.efwww.com
	public function doMobileTest() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		$url = 'http://m.toutiao.com/i6263181136604692994/?plg_nld=1&iid=3793459589&plg_nld=1&utm_source=weixin&isappinstalled=1&tt_from=weixin&plg_uin=1&utm_medium=toutiao_ios&from=singlemessage&wxshare_count=1&plg_auth=1&app=news_article&utm_campaign=client_share&plg_dev=1&plg_usr=1&plg_vkey=1';
		$url = 'http://3g.163.com/ntes/special/0034073A/wechat_article.html?docid=BJIP1N2P00234L7P&s=newsapp&w=2&f=wx&from=singlemessage&isappinstalled=0';
		$url = 'http://view.inews.qq.com/w/WXN201604010093250A0?refer=nwx&cur_pos=0&openid' . "\n" . '        =o04IBAFh0HVOyn42aZ2iqkdutc7A&groupid=1459473571&msgid=0';
		$url = 'http://view.inews.qq.com/w/WXN20160403021534051?refer=nwx&cur_pos=0&openid' . "\n" . '        =o04IBAFh0HVOyn42aZ2iqkdutc7A&groupid=1459680847&msgid=0';
		$title = $this->getQQTitle($url);
		echo $title;
		exit();
	}
	public function doMobileShare() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		if (empty($from_user)) {
			$this->result('您还没登录!', $this->createMobileUrl('index', array(), true));
		}
		$id = intval($_GPC['id']);
		$article = pdo_fetch('SELECT * FROM ' . tablename($this->table_article) . ' WHERE id=:id LIMIT 1', array(':id' => $id));
		if (empty($article)) {
			$this->result('文章不存在!', $this->createMobileUrl('index', array(), true));
		} else {
			pdo_update($this->table_article, array('sharecount' => $article['sharecount'] + 1), array('id' => $article['id']));
		}
		$url = $this->createMobileUrl('miaotie', array('id' => $id), true);
		header('location:' . $url);
	}
	public function doMobileSubmit() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		if (empty($from_user)) {
			$this->result('您还没登录!', $this->createMobileUrl('index', array(), true));
		}
		$url = trim($_GPC['url']);
		if (empty($url)) {
			$this->result('请输入文章网址!', $this->createMobileUrl('index', array(), true));
		}
		if (!$this->is_url(trim($url))) {
			$this->result('网址不正确!', $this->createMobileUrl('index', array(), true));
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		if (empty($fans)) {
			$this->result('您还没登录!', $this->createMobileUrl('index', array(), true));
		}
		if ($fans['status'] == 0) {
			$this->result('您的帐号已经被冻结了!', $this->createMobileUrl('index', array(), true));
		}
		if (strstr($url, 'http://view.inews.qq.com')) {
			$title = $this->getQQTitle($url);
		} else {
			$title = $this->getTitle($url);
		}
		if (empty($title)) {
			$this->result('取不到文章的标题!', $this->createMobileUrl('index', array(), true));
		}
		$article = pdo_fetch('SELECT * FROM ' . tablename($this->table_article) . ' WHERE fansid=:fansid AND url=:url LIMIT 1', array(':fansid' => $fans['id'], ':url' => $url));
		if (!empty($article)) {
			$this->result('贴广告成功', $this->createMobileUrl('miaotie', array('id' => $article['id']), true));
		}
		$setting = $this->getSetting();
		$read_min = 10000;
		$read_max = 30000;
		$praise_min = 1;
		$praise_max = 2000;
		if (!empty($setting)) {
			$read_min = $setting['read_min'];
			$read_max = $setting['read_max'];
			$praise_min = $setting['praise_min'];
			$praise_max = $setting['praise_max'];
		}
		$insert = array('uniacid' => $uniacid, 'fansid' => $fans['id'], 'from_user' => $from_user, 'title' => $title, 'url' => $url, 'default_read' => rand($read_min, $read_max), 'default_praise' => rand($praise_min, $praise_max), 'dateline' => TIMESTAMP);
		pdo_insert($this->table_article, $insert);
		$id = pdo_insertid();
		if (0 < $id) {
			$this->result('贴广告成功', $this->createMobileUrl('miaotie', array('id' => $id), true));
			return NULL;
		}
		$this->result('网址不正确', $this->createMobileUrl('index', array(), true));
	}
	public function getTitle($url) {
		$data = file_get_contents($url);
		$pos = strpos($data, 'utf-8');
		preg_match('/<title>(.*)<\/title>/i', $data, $title);
		return $title[1];
	}
	public function getQQTitle($url) {
		load()->func('communication');
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($c);
		curl_close($c);
		$pos = strpos($data, 'utf-8');
		preg_match('/<p class="title" align="left">(.*)<\/p>/i', $data, $title);
		return $title[1];
	}
	public function getNeedBetween($kw1, $mark1, $mark2) {
		$kw = $kw1;
		$kw = '123' . $kw . '123';
		$st = stripos($kw, $mark1);
		echo 'head' . $st;
		$ed = stripos($kw, $mark2);
		echo 'end' . $ed;
		if (($st == false) || ($ed == false) || ($ed <= $st)) {
			return 0;
		}
		$kw = substr($kw, $st + 1, $ed - $st - 1);
		return $kw;
	}
	public function getContent($url) {
		$contents = file_get_contents($url);
		$contents = explode('js_article', $contents);
		$contents = $contents[1];
		$contents = explode('<script>window.moon_map', $contents);
		$contents = $contents[0];
		$contents = '<div id="js_article' . $contents;
		return $contents;
	}
	public function getContent2($url) {
		$contents = file_get_contents($url);
		$contents = explode('<title>', $contents);
		$contents = $contents[1];
		$contents = '<title>' . $contents;
		return $contents;
	}
	public function result($info, $url) {
		global $_W;
		global $_GPC;
		$error_info = $info;
		$url = $url;
		include $this->template('result');
		exit();
	}
	public function doMobileMiaotie() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		$followuser = $_GPC['followuser'];
		$id = intval($_GPC['id']);
		$setting = $this->getSetting();
		$is_show_ad = 1;
		if ($setting['is_secondary_show'] == 2) {
			$method = 'miaotie';
			$host = $this->getOAuthHost();
			$authurl = $host . 'app/' . $this->createMobileUrl($method, array('id' => $id, 'followuser' => $followuser), true) . '&authkey=1';
			$url = $host . 'app/' . $this->createMobileUrl($method, array('id' => $id, 'followuser' => $followuser), true);
			if (isset($_COOKIE[$this->_auth2_openid])) {
				$from_user = $_COOKIE[$this->_auth2_openid];
				$nickname = $_COOKIE[$this->_auth2_nickname];
				$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
			} else if (isset($_GPC['code'])) {
				$userinfo = $this->oauth2($authurl);
				if (!empty($userinfo)) {
					$from_user = $userinfo['openid'];
					$nickname = $userinfo['nickname'];
					$headimgurl = $userinfo['headimgurl'];
				} else {
					message('授权失败!');
				}
			} else if (!empty($this->_appsecret)) {
				$this->getCode($url);
			}
		}
		$article = pdo_fetch('SELECT * FROM ' . tablename($this->table_article) . ' WHERE id=:id LIMIT 1', array(':id' => $id));
		if (empty($article)) {
			$this->result('文章不存在!', $this->createMobileUrl('index', array(), true));
		} else {
			pdo_update($this->table_article, array('readcount' => floatval($article['readcount']) + 0.5, 'default_read' => $article['default_read'] + 1), array('id' => $article['id']));
			$article = pdo_fetch('SELECT * FROM ' . tablename($this->table_article) . ' WHERE id=:id LIMIT 1', array(':id' => $id));
		}
		if ($setting['is_secondary_show'] == 2) {
			if ($followuser == $article['from_user']) {
				$is_show_ad = 0;
			}
		}
		$url = $article['url'];
		if (strstr($article['url'], 'http://mp.weixin.qq') || strstr($url, 'https://mp.weixin.qq')) {
			$content = $this->getContent($url);
		}
		$is_toutiao = 0;
		if (strstr($article['url'], 'http://m.toutiao.com')) {
			$content = $this->getContent2($url);
			$is_toutiao = 1;
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE id=:fansid AND status=1 LIMIT 1', array(':fansid' => $article['fansid']));
		if ($fans['status'] == 0) {
			$this->result('您的帐号已经被冻结了!', $this->createMobileUrl('index', array(), true));
		}
		$qrcode = tomedia($setting['qrcode']);
		$ad = tomedia($setting['ad']);
		$ad_url = $setting['ad_url'];
		$title1 = ((empty($setting) ? '1秒把广告贴到朋友圈' : $setting['title1']));
		$title2 = ((empty($setting) ? '最牛的朋友圈宣传工具' : $setting['title2']));
		$title3 = ((empty($setting) ? '最牛的朋友圈宣传工具' : $setting['title3']));
		$mobile = $setting['mobile'];
		if ($fans['is_vip'] == 1) {
			if (($fans['starttime'] < TIMESTAMP) && (TIMESTAMP < $fans['endtime'])) {
				$qrcode = ((empty($fans['qrcode']) ? $qrcode : tomedia($fans['qrcode'])));
				$ad = ((empty($fans['ad']) ? $ad : tomedia($fans['ad'])));
				$ad_url = $fans['ad_url'];
				$title1 = ((empty($fans['title1']) ? $title1 : $fans['title1']));
				$title2 = ((empty($fans['title2']) ? $title2 : $fans['title2']));
				$title3 = ((empty($fans['title3']) ? $title3 : $fans['title3']));
				$mobile = ((empty($fans['mobile']) ? $mobile : $fans['mobile']));
			}
		}
		$title = ((empty($setting['title']) ? '一秒广告' : $setting['title']));
		$share_title = $article['title'];
		$share_title = str_replace('&nbsp;', '', $share_title);
		$share_desc = $article['title'];
		if ($setting['is_secondary_show'] == 2) {
			$share_url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('miaotie', array('id' => $id, 'followuser' => $from_user), true);
		} else {
			$share_url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('miaotie', array('id' => $id), true);
		}
		$share_image = tomedia($setting['share_image']);
		$con = file_get_contents($url);
		if (strstr($url, 'http://mp.weixin.qq') || strstr($url, 'https://mp.weixin.qq')) {
			$pattern = '/msg_cdn_url = ["](.*?)["]/';
			preg_match_all($pattern, $con, $match);
			$i = 0;
			while ($i < count($match[1])) {
				if (strstr($match[1][$i], 'http://') && !strstr($match[1][$i], 'head_50') && !strstr($match[1][$i], 'res.wx.qq.com')) {
					$share_image = $match[1][$i];
					break;
				}
				++$i;
			}
			$pattern = '/msg_desc = ["](.*?)["]/';
			preg_match_all($pattern, $con, $match);
			$i = 0;
			while ($i < count($match[1])) {
				$share_desc = $match[1][0];
				$share_desc = str_replace('&nbsp;', '', $share_desc);
				++$i;
			}
		} else {
			$pattern = '/<[img|IMG].*?src=[\\\'|"](.*?(?:[\\.gif|\\.jpg|\\.png]))[\\\'|"].*?[\\/]?>/';
			$pattern = '/<[img|IMG].*?src=[\\\'|"](.*?)[\\\'|"].*?[\\/]?>/';
			preg_match_all($pattern, $con, $match);
			$i = 0;
			while ($i < count($match[1])) {
				if (strstr($match[1][$i], 'http://') && !strstr($match[1][$i], 'head_50') && !strstr($match[1][$i], 'res.wx.qq.com')) {
					$pic = $match[1][$i];
					break;
				}
				++$i;
			}
			$share_image = $pic;
		}
		if (empty($share_image)) {
			$share_image = tomedia($setting['share_image']);
		}
		if ($is_toutiao == 1) {
			include $this->template('miaotie2');
			return NULL;
		}
		include $this->template('miaotie');
	}
	public function is_url($str) {
		return preg_match('/^(http:\\/\\/|https:\\/\\/).*$/', $str);
	}
	public function doMobileHelp() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		$method = 'help';
		$host = $this->getOAuthHost();
		$authurl = $host . 'app/' . $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $host . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) {
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		} else if (isset($_GPC['code'])) {
			$userinfo = $this->oauth2($authurl);
			if (!empty($userinfo)) {
				$from_user = $userinfo['openid'];
				$nickname = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
			} else {
				message('授权失败!');
			}
		} else if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		if ($this->_accountlevel == 4) {
			if (empty($fans) && !empty($nickname)) {
				$insert = array('uniacid' => $uniacid, 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'dateline' => TIMESTAMP);
				pdo_insert($this->table_fans, $insert);
			}
			$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		}
		$setting = $this->getSetting();
		$title = ((empty($setting['title']) ? '一秒广告' : $setting['title']));
		$share_title = trim($setting['share_title']);
		$share_desc = trim($setting['share_desc']);
		$share_url = ((empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array(), true) : trim($setting['share_url'])));
		$share_image = tomedia($setting['share_image']);
		include $this->template('help');
	}
	public function doMobileList() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		$method = 'list';
		$host = $this->getOAuthHost();
		$authurl = $host . 'app/' . $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $host . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) {
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		} else if (isset($_GPC['code'])) {
			$userinfo = $this->oauth2($authurl);
			if (!empty($userinfo)) {
				$from_user = $userinfo['openid'];
				$nickname = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
			} else {
				message('授权失败!');
			}
		} else if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		if ($this->_accountlevel == 4) {
			if (empty($fans) && !empty($nickname)) {
				$insert = array('uniacid' => $uniacid, 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'dateline' => TIMESTAMP);
				pdo_insert($this->table_fans, $insert);
			}
		} else if (empty($fans) && !empty($from_user)) {
			$insert = array('uniacid' => $uniacid, 'from_user' => $from_user, 'dateline' => TIMESTAMP);
			pdo_insert($this->table_fans, $insert);
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		if ($fans['status'] == 0) {
			$this->result('您的帐号已经被冻结了!', $this->createMobileUrl('index', array(), true));
		}
		$list = pdo_fetchall('SELECT * FROM ' . tablename($this->table_article) . ' WHERE uniacid = :uniacid AND' . "\n" . '        from_user=:from_user AND status=1 ORDER BY id' . "\n" . 'DESC LIMIT 50 ', array(':uniacid' => $uniacid, ':from_user' => $from_user));
		$setting = $this->getSetting();
		$title = ((empty($setting['title']) ? '一秒广告' : $setting['title']));
		$share_title = trim($setting['share_title']);
		$share_desc = trim($setting['share_desc']);
		$share_url = ((empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array(), true) : trim($setting['share_url'])));
		$share_image = tomedia($setting['share_image']);
		include $this->template('listinfo');
	}
	public function upload_img($upload_name, $asname = '', $thumb = true, $width = 320, $height = 240, $position = 5) {
		load()->func('file');
		$upfile = $_FILES[$upload_name];
		$name = $upfile['name'];
		$type = $upfile['type'];
		$size = $upfile['size'];
		$tmp_name = $upfile['tmp_name'];
		$error = $upfile['error'];
		$upload_path = IA_ROOT . '/attachment/cy_rencai/';
		load()->func('file');
		@mkdirs($upload_path);
		if (0 < intval($error)) {
			message('上传错误：错误代码：' . $upload_name . '-' . $error, '', 'error');
			return NULL;
		}
		$maxfilesize = ((empty($this->module['config']['maxfilesize']) ? 2 : intval($this->module['config']['maxfilesize'])));
		if (0 < $maxfilesize) {
			if (($maxfilesize * 1024 * 1024) < $size) {
				message('上传文件过大' . $_FILES['file']['error'], '', 'error');
			}
		}
		$uptypes = array('image/jpg', 'image/png', 'image/jpeg');
		if (!in_array($type, $uptypes)) {
			message('上传文件类型不符：' . $type, '', 'error');
		}
		if (!file_exists($upload_path)) {
			mkdir($upload_path);
		}
		if (!move_uploaded_file($tmp_name, $upload_path . date('YmdHi') . '_' . $name)) {
			message('移动文件失败，请检查服务器权限', '', 'error');
		}
		$srcfile = $upload_path . date('YmdHi') . '_' . $name;
		$desfile = $upload_path . date('YmdHi') . '_' . $name . '.' . $asname . '.thumb.jpg';
		if ($thumb) {
			file_image_thumb($srcfile, $desfile, $width);
		} else {
			file_image_crop($srcfile, $desfile, $width, $height, 5);
		}
		return date(YmdHi) . '_' . $name . '.' . $asname . '.thumb.jpg';
	}
	public function doMobileDeleteInfo() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		if (empty($from_user)) {
			$this->result('您还没登录!', $this->createMobileUrl('index', array(), true));
		}
		$id = intval($_GPC['id']);
		$article = pdo_fetch('SELECT * FROM ' . tablename($this->table_article) . ' WHERE id=:id AND' . "\n" . '        from_user=:from_user  LIMIT' . "\n" . '        1', array(':id' => $id, ':from_user' => $from_user));
		if (empty($article)) {
			$this->result('文章不存在！', $this->createMobileUrl('list', array(), true));
		}
		pdo_delete($this->table_article, array('id' => $id));
		$this->result('删除文章成功!', $this->createMobileUrl('list', array(), true));
	}
	public function doMobileSumbitInfo() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		if (empty($from_user)) {
			$this->result('您还没登录!', $this->createMobileUrl('index', array(), true));
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		if (empty($fans)) {
			$this->result('您还没登录!', $this->createMobileUrl('index', array(), true));
		}
		if ($fans['status'] == 0) {
			$this->result('您的帐号已经被冻结了!', $this->createMobileUrl('index', array(), true));
		}
		$data = array('title1' => trim($_GPC['title1']), 'title2' => trim($_GPC['title2']),  'title3' => trim($_GPC['title3']), 'mobile' => trim($_GPC['mobile']), 'ad_url' => trim($_GPC['ad_url']));
		if (!empty($_FILES['qrcode']['name'])) {
			$data['qrcode'] = $this->uploadImg('qrcode');
		}
		if (!empty($_FILES['ad']['name'])) {
			$data['ad'] = $this->uploadImg('ad');
		}
		if (!empty($_GPC['ad_url']) && !$this->is_url(trim($_GPC['ad_url']))) {
			$this->result('网址不正确!', $this->createMobileUrl('editinfo', array(), true));
		}
		pdo_update($this->table_fans, $data, array('id' => $fans['id']));
		$this->result('修改信息成功', $this->createMobileUrl('editinfo', array(), true));
	}
	public function uploadImg($name) {
		if ($_FILES[$name]['error'] != 0) {
			$this->result('上传失败，请重试！1', $this->createMobileUrl('editinfo', array(), true));
		}
		$_W['uploadsetting'] = array();
		$_W['uploadsetting']['image']['folder'] = 'images';
		$_W['uploadsetting']['image']['extentions'] = $_W['config']['upload']['image']['extentions'];
		$_W['uploadsetting']['image']['limit'] = 1024;
		load()->func('file');
		$file = file_upload($_FILES[$name], 'image');
		if (is_error($file)) {
			$this->result('上传失败，请重试！', $this->createMobileUrl('editinfo', array(), true));
		}
		$result['url'] = $file['url'];
		$result['error'] = 0;
		$result['filename'] = $file['path'];
		$result['url'] = $_W['attachurl'] . $result['filename'];
		return $result['filename'];
	}
	public function doMobileeditinfo() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		$method = 'editinfo';
		$host = $this->getOAuthHost();
		$authurl = $host . 'app/' . $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $host . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) {
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		} else if (isset($_GPC['code'])) {
			$userinfo = $this->oauth2($authurl);
			if (!empty($userinfo)) {
				$from_user = $userinfo['openid'];
				$nickname = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
			} else {
				message('授权失败!');
			}
		} else if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
		$is_ios = false;
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
			$is_ios = true;
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		if ($this->_accountlevel == 4) {
			if (empty($fans) && !empty($nickname)) {
				$insert = array('uniacid' => $uniacid, 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'dateline' => TIMESTAMP);
				pdo_insert($this->table_fans, $insert);
			}
			$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		}
		if ($fans['status'] == 0) {
			$this->result('您的帐号已经被冻结了!', $this->createMobileUrl('index', array(), true));
		}
		$setting = $this->getSetting();
		$title = ((empty($setting['title']) ? '一秒广告' : $setting['title']));
		$share_title = trim($setting['share_title']);
		$share_desc = trim($setting['share_desc']);
		$share_url = ((empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array(), true) : trim($setting['share_url'])));
		$share_image = tomedia($setting['share_image']);
		include $this->template('editinfo');
	}
	public function doMobilezhifu() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		$setting = $this->getSetting();
		if ($setting['is_pay'] == 0) {
			$this->result('商家暂时没有开通在线支付功能!', $this->createMobileUrl('index', array(), true));
		}
		$method = 'zhifu';
		$host = $this->getOAuthHost();
		$authurl = $host . 'app/' . $this->createMobileUrl($method, array(), true) . '&authkey=1';
		$url = $host . 'app/' . $this->createMobileUrl($method, array(), true);
		if (isset($_COOKIE[$this->_auth2_openid])) {
			$from_user = $_COOKIE[$this->_auth2_openid];
			$nickname = $_COOKIE[$this->_auth2_nickname];
			$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
		} else if (isset($_GPC['code'])) {
			$userinfo = $this->oauth2($authurl);
			if (!empty($userinfo)) {
				$from_user = $userinfo['openid'];
				$nickname = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
			} else {
				message('授权失败!');
			}
		} else if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		if ($this->_accountlevel == 4) {
			if (empty($fans) && !empty($nickname)) {
				$insert = array('uniacid' => $uniacid, 'from_user' => $from_user, 'nickname' => $nickname, 'headimgurl' => $headimgurl, 'dateline' => TIMESTAMP);
				pdo_insert($this->table_fans, $insert);
			}
			$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE from_user=:from_user AND uniacid=:uniacid LIMIT 1', array(':from_user' => $from_user, ':uniacid' => $uniacid));
		}
		if ($fans['status'] == 0) {
			$this->result('您的帐号已经被冻结了!', $this->createMobileUrl('index', array(), true));
		}
		$title = ((empty($setting['title']) ? '一秒广告' : $setting['title']));
		$share_title = trim($setting['share_title']);
		$share_desc = trim($setting['share_desc']);
		$share_url = ((empty($setting['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array(), true) : trim($setting['share_url'])));
		$share_image = tomedia($setting['share_image']);
		include $this->template('zhifu');
	}
	public function getSetting() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$setting = pdo_fetch('SELECT * FROM ' . tablename($this->table_setting) . ' where uniacid = :uniacid LIMIT 1', array(':uniacid' => $uniacid));
		return $setting;
	}
	public function doMobileAddToOrder() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$from_user = $this->_fromuser;
		$vipcount = intval($_GPC['vipcount']);
		if (empty($from_user)) {
			$this->result('您还没登录!', $this->createMobileUrl('index', array(), true));
		}
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE uniacid = :uniacid  AND from_user=:from_user ORDER BY `id` DESC limit 1', array(':uniacid' => $uniacid, ':from_user' => $from_user));
		if (empty($fans)) {
			$this->result('您还没登录!', $this->createMobileUrl('index', array(), true));
		}
		if ($fans['status'] == 0) {
			$this->result('您的帐号已经被冻结了!', $this->createMobileUrl('index', array(), true));
		}
		$totalprice = 1980;
		$setting = $this->getSetting();
		if ($setting['price']) {
			$price = floatval($setting['price']);
			if (0 < $price) {
				$totalprice = $price;
			}
		}
		if ($setting['viptype'] == 2) {
			if ((12 < $vipcount) || ($vipcount < 1)) {
				$this->result('输入错误!', $this->createMobileUrl('index', array(), true));
			}
			if ($vipcount == 1) {
				$totalprice = floatval($setting['price1']);
			} else if ($vipcount == 3) {
				$totalprice = floatval($setting['price2']);
			} else if ($vipcount == 6) {
				$totalprice = floatval($setting['price3']);
			} else if ($vipcount == 12) {
				$totalprice = floatval($setting['price4']);
			}
		}
		$fansid = $fans['id'];
		$data = array('uniacid' => $uniacid, 'fansid' => $fansid, 'from_user' => $from_user, 'ordersn' => date('md') . sprintf('%04d', $fansid) . random(4, 1), 'totalprice' => $totalprice, 'vipcount' => $vipcount, 'paytype' => 0, 'status' => 0, 'dateline' => TIMESTAMP);
		pdo_insert($this->table_order, $data);
		$orderid = pdo_insertid();
		if (empty($orderid)) {
			$this->result('系统维护中!', $this->createMobileUrl('index', array(), true));
			return NULL;
		}
		$url = $this->createMobileUrl('pay', array('orderid' => $orderid), true);
		header('location:' . $url);
	}
	public function doMobilePay() {
		global $_W;
		global $_GPC;
		checkauth();
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch('SELECT * FROM ' . tablename($this->table_order) . ' WHERE id = :id', array(':id' => $orderid));
		if (!empty($order['status'])) {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('orderlist', array('storeid' => $order['storeid'])), 'error');
		}
		$params['tid'] = $orderid;
		$params['user'] = $order['from_user'];
		$params['fee'] = $order['totalprice'];
		$params['title'] = $_W['account']['name'];
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = false;
		include $this->template('pay');
	}
	private function sendText($openid, $content) {
		$send['touser'] = trim($openid);
		$send['msgtype'] = 'text';
		$send['text'] = array('content' => urlencode($content));
		$acc = WeAccount::create();
		$data = $acc->sendCustomNotice($send);
		return $data;
	}
	public function payResult($params) {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$orderid = $params['tid'];
		$fee = intval($params['fee']);
		$data = array('status' => ($params['result'] == 'success' ? 1 : 0));
		$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
		if (!empty($params['is_usecard'])) {
			$cardType = array(1 => '微信卡券', 2 => '系统代金券');
			$result_price = $params['fee'] - $params['card_fee'];
			$data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . $result_price;
			$data['paydetail'].= '元，实际支付了' . $params['card_fee'] . '元。';
			$data['totalprice'] = $params['card_fee'];
		}
		$data['paytype'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		$order = pdo_fetch('SELECT * FROM ' . tablename($this->table_order) . ' WHERE id = :id', array(':id' => $orderid));
		if (empty($order)) {
			message('订单不存在!');
		}
		if (($params['result'] == 'success') && ($params['from'] == 'notify')) {
			if (!empty($order)) {
				pdo_update($this->table_order, $data, array('id' => $orderid));
				if ($order['status'] == 0) {
					$order = pdo_fetch('SELECT * FROM ' . tablename($this->table_order) . ' WHERE id = :id', array(':id' => $orderid));
					$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE id = :id', array(':id' => $order['fansid']));
					$setting = pdo_fetch('SELECT * FROM ' . tablename($this->table_setting) . ' WHERE uniacid = :uniacid', array(':uniacid' => $order['uniacid']));
					if (TIMESTAMP < $fans['endtime']) {
						if ($setting['viptype'] == 2) {
							$vipcount = intval($order['vipcount']);
							$time = strtotime('+' . $vipcount . ' month', $fans['endtime']);
						} else {
							$time = strtotime('+1 years', $fans['endtime']);
						}
					} else if ($setting['viptype'] == 2) {
						$vipcount = intval($order['vipcount']);
						$time = strtotime('+' . $vipcount . ' month');
					} else {
						$time = strtotime('+1 years');
					}
					pdo_update($this->table_fans, array('endtime' => $time, 'is_vip' => 1), array('id' => $fans['id']));
					pdo_update($this->table_order, array('status' => 1), array('id' => $orderid));
				}
			}
		}
		$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
		$credit = $setting['creditbehaviors']['currency'];
		if ($params['type'] == $credit) {
			message('支付成功！', $this->createMobileUrl('index', array(), true), 'success');
			return NULL;
		}
		message('支付成功！', '../../app/' . $this->createMobileUrl('index', array(), true), 'success');
	}
	public function doWebSetOrderStatus() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$orderid = intval($_GPC['id']);
		$order = pdo_fetch('SELECT * FROM ' . tablename($this->table_order) . ' WHERE id = :id AND uniacid=:uniacid' . "\n" . '        ', array(':id' => $orderid, ':uniacid' => $uniacid));
		if (empty($order)) {
			message('订单不存在！', '', 'error');
		}
		$setting = $this->getSetting();
		$fansid = $order['fansid'];
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE id = :id AND uniacid = :uniacid LIMIT 1', array(':id' => $fansid, ':uniacid' => $uniacid));
		if (empty($fans)) {
			message('粉丝不存在！', '', 'error');
		}
		if (TIMESTAMP < $fans['endtime']) {
			if ($setting['viptype'] == 2) {
				$vipcount = intval($order['vipcount']);
				$time = strtotime('+' . $vipcount . ' month', $fans['endtime']);
			} else {
				$time = strtotime('+1 years', $fans['endtime']);
			}
		} else if ($setting['viptype'] == 2) {
			$vipcount = intval($order['vipcount']);
			$time = strtotime('+' . $vipcount . ' month');
		} else {
			$time = strtotime('+1 years');
		}
		pdo_update($this->table_fans, array('endtime' => $time, 'is_vip' => 1), array('id' => $fansid));
		pdo_update($this->table_order, array('status' => 1), array('id' => $orderid));
		message('操作成功！', $this->createWebUrl('order', array('op' => 'display')), 'success');
	}
	public function doWebSetUserVip() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$fansid = intval($_GPC['fansid']);
		$fans = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE id = :id AND uniacid = :uniacid LIMIT 1', array(':id' => $fansid, ':uniacid' => $uniacid));
		if (empty($fans)) {
			message('粉丝不存在！', '', 'error');
		}
		if ($fans['is_vip'] == 1) {
			if (TIMESTAMP < $fans['endtime']) {
				$time = strtotime('+1 years', $fans['endtime']);
			} else {
				$time = strtotime('+1 years');
			}
			pdo_update($this->table_fans, array('endtime' => $time, 'is_vip' => 1), array('id' => $fansid));
		} else {
			$time = strtotime('+1 years');
			pdo_update($this->table_fans, array('endtime' => $time, 'is_vip' => 1), array('id' => $fansid));
		}
		message(操作成功！, $this->createWebUrl('fans', array('op' => 'display')), 'success');
	}
	public function checkModule($name) {
		$module = pdo_fetch('SELECT * FROM ' . tablename('modules') . ' WHERE name=:name ', array(':name' => $name));
		return $module;
	}
	public function showMessageAjax($msg, $code = 0) {
		$result['code'] = $code;
		$result['msg'] = $msg;
		message($result, '', 'ajax');
	}
	public function doMobileAjaxdelete() {
		global $_GPC;
		$delurl = $_GPC['pic'];
		load()->func('file');
		if (file_delete($delurl)) {
			echo 1;
			return NULL;
		}
		echo 0;
	}
	public function img_url($img = '') {
		global $_W;
		if (empty($img)) {
			return '';
		}
		if (substr($img, 0, 6) == 'avatar') {
			return $_W['siteroot'] . 'resource/image/avatar/' . $img;
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
	public function showMsg($msg, $status = 0) {
		$result = array('msg' => $msg, 'status' => $status);
		echo json_encode($result);
		exit();
	}
	public function doMobileVersion() {
		message($this->version);
	}
	public function isWeixin() {
		if ($this->_weixin == 1) {
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
			if (!strpos($userAgent, 'MicroMessenger')) {
				include $this->template('s404');
				exit();
			}
		}
	}
	public function oauth2($url) {
		global $_GPC;
		global $_W;
		load()->func('communication');
		$code = $_GPC['code'];
		if (empty($code)) {
			message('code获取失败.');
		}
		$token = $this->getAuthorizationCode($code);
		$from_user = $token['openid'];
		$userinfo = $this->getUserInfo($from_user);
		$sub = 1;
		if ($userinfo['subscribe'] == 0) {
			$sub = 0;
			$authkey = intval($_GPC['authkey']);
			if ($authkey == 0) {
				$oauth2_code = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->_appid . '&redirect_uri=' . urlencode($url) . '&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect';
				header('location:' . $oauth2_code);
			}
			$userinfo = $this->getUserInfo($from_user, $token['access_token']);
		}
		if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
			echo '<h1>获取微信公众号授权失败[无法取得userinfo], 请稍后重试！ 公众平台返回原始数据为: <br />' . $sub . $userinfo['meta'] . '<h1>';
			exit();
		}
		setcookie($this->_auth2_headimgurl, $userinfo['headimgurl'], time() + (3600 * 24));
		setcookie($this->_auth2_nickname, $userinfo['nickname'], time() + (3600 * 24));
		setcookie($this->_auth2_openid, $from_user, time() + (3600 * 24));
		setcookie($this->_auth2_sex, $userinfo['sex'], time() + (3600 * 24));
		return $userinfo;
	}
	public function getUserInfo($from_user, $ACCESS_TOKEN = '') {
		if ($ACCESS_TOKEN == '') {
			$ACCESS_TOKEN = $this->getAccessToken();
			$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $ACCESS_TOKEN . '&openid=' . $from_user . '&lang=zh_CN';
		} else {
			$url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $ACCESS_TOKEN . '&openid=' . $from_user . '&lang=zh_CN';
		}
		$json = ihttp_get($url);
		$userInfo = @json_decode($json['content'], true);
		return $userInfo;
	}
	public function getAuthorizationCode($code) {
		$oauth2_code = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->_appid . '&secret=' . $this->_appsecret . '&code=' . $code . '&grant_type=authorization_code';
		$content = ihttp_get($oauth2_code);
		$token = @json_decode($content['content'], true);
		if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
			$oauth2_code = $this->createMobileUrl('waprestlist', array(), true);
			header('location:' . $oauth2_code);
			exit();
		}
		return $token;
	}
	public function getAccessToken() {
		global $_W;
		$account = $_W['account'];
		if ($this->_accountlevel < 4) {
			if (!empty($this->_account)) {
				$account = $this->_account;
			}
		}
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($account['acid']);
		$access_token = $accObj->fetch_token();
		return $access_token;
	}
	public function getCode($url) {
		global $_W;
		$url = urlencode($url);
		$oauth2_code = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->_appid . '&redirect_uri=' . $url . '&response_type=code&scope=snsapi_base&state=0#wechat_redirect';
		header('location:' . $oauth2_code);
	}
	public function doWebSetting() {
		global $_GPC;
		global $_W;
		global $code;
		$code = $this->copyright;
		load()->func('tpl');
		$uniacid = $this->_uniacid;
		$action = 'setting';
		$title = $this->actions_titles[$action];
		$setting = pdo_fetch('SELECT * FROM ' . tablename($this->table_setting) . ' WHERE uniacid = :uniacid LIMIT 1', array(':uniacid' => $uniacid));
		if (checksubmit('submit')) {
			$data = array('uniacid' => $_W['uniacid'], 'title' => trim($_GPC['title']), 'price' => floatval($_GPC['price']), 'copyright' => trim($_GPC['copyright']), 'share_title' => trim($_GPC['share_title']), 'share_desc' => trim($_GPC['share_desc']), 'share_image' => trim($_GPC['share_image']), 'share_url' => trim($_GPC['share_url']), 'dateline' => TIMESTAMP, 'weixin' => trim($_GPC['weixin']), 'viptype' => intval($_GPC['viptype']), 'paytype' => intval($_GPC['paytype']), 'read_min' => intval($_GPC['read_min']), 'read_max' => intval($_GPC['read_max']), 'praise_min' => intval($_GPC['praise_min']), 'praise_max' => intval($_GPC['praise_max']), 'show_qrcode' => intval($_GPC['show_qrcode']), 'show_mobile' => intval($_GPC['show_mobile']), 'taste_vip' => intval($_GPC['taste_vip']), 'is_secondary_show' => intval($_GPC['is_secondary_show']), 'price1' => floatval($_GPC['price1']), 'price2' => floatval($_GPC['price2']), 'price3' => floatval($_GPC['price3']), 'price4' => floatval($_GPC['price4']));
			$data['ad2_text'] = trim($_GPC['ad2_text']);
			$data['ad2'] = trim($_GPC['ad2']);
			$data['ad_url2'] = trim($_GPC['ad_url2']);
			if (empty($setting)) {
				pdo_insert($this->table_setting, $data);
			} else {
				unset($data['dateline']);
				pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
			}
			message('操作成功', $this->createWebUrl('setting'), 'success');
		}
		if (!isset($_COOKIE['miao_check'])) {
			setcookie('miao_check', 'setting', time() + (3600 * 10));
		}
		include $this->template('setting');
	}
	public function doWebStyle() {
		global $_W;
		global $_GPC;
		load()->func('tpl');
		$uniacid = $this->_uniacid;
		$action = 'style';
		$title = $this->actions_titles[$action];
		$setting = pdo_fetch('SELECT * FROM ' . tablename($this->table_setting) . ' WHERE uniacid = :uniacid LIMIT 1', array(':uniacid' => $uniacid));
		if (checksubmit('submit')) {
			$data = array('uniacid' => $_W['uniacid'], 'btn_index' => trim($_GPC['btn_index']), 'btn1' => trim($_GPC['btn1']), 'btn2' => trim($_GPC['btn2']), 'btn3' => trim($_GPC['btn3']), 'btn4' => trim($_GPC['btn4']), 'btn5' => trim($_GPC['btn5']), 'btn_url1' => trim($_GPC['btn_url1']), 'btn_url2' => trim($_GPC['btn_url2']), 'btn_url3' => trim($_GPC['btn_url3']), 'btn_url4' => trim($_GPC['btn_url4']), 'btn_url5' => trim($_GPC['btn_url5']));
			if (empty($setting)) {
				pdo_insert($this->table_setting, $data);
			} else {
				unset($data['dateline']);
				pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
			}
			message('操作成功', $this->createWebUrl('style'), 'success');
		}
		if (empty($setting)) {
			$setting = array('btn1' => '一键转帖', 'btn2' => '我的文章', 'btn3' => '设置广告', 'btn4' => '热门文章', 'btn5' => '购买包年');
		}
		include $this->template('style');
	}
	public function doWebHelp() {
		global $_W;
		global $_GPC;
		load()->func('tpl');
		$uniacid = $this->_uniacid;
		$action = 'help';
		$title = $this->actions_titles[$action];
		$setting = pdo_fetch('SELECT * FROM ' . tablename($this->table_setting) . ' WHERE uniacid = :uniacid LIMIT 1', array(':uniacid' => $uniacid));
		if (checksubmit('submit')) {
			$data = array('uniacid' => $_W['uniacid'], 'help' => trim($_GPC['help']));
			if (empty($setting)) {
				pdo_insert($this->table_setting, $data);
			} else {
				unset($data['dateline']);
				pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
			}
			message('操作成功', $this->createWebUrl('setting'), 'success');
		}
		include $this->template('help');
	}
	public function doWebad() {
		global $_W;
		global $_GPC;
		load()->func('tpl');
		$uniacid = $this->_uniacid;
		$action = 'ad';
		$title = $this->actions_titles[$action];
		$setting = pdo_fetch('SELECT * FROM ' . tablename($this->table_setting) . ' WHERE uniacid = :uniacid LIMIT 1', array(':uniacid' => $uniacid));
		if (checksubmit('submit')) {
			$data = array('uniacid' => $_W['uniacid'], 'mobile' => trim($_GPC['mobile']), 'title1' => trim($_GPC['title1']), 'title2' => trim($_GPC['title2']), 'title3' => trim($_GPC['title3']), 'qrcode' => trim($_GPC['qrcode']), 'ad' => trim($_GPC['ad']), 'ad_url' => trim($_GPC['ad_url']));
			if (empty($setting)) {
				pdo_insert($this->table_setting, $data);
			} else {
				unset($data['dateline']);
				pdo_update($this->table_setting, $data, array('uniacid' => $_W['uniacid']));
			}
			message('操作成功', $this->createWebUrl('ad'), 'success');
		}
		if (empty($setting)) {
			$setting = array('title1' => '1秒把广告贴到朋友圈', 'title2' => '最牛的朋友圈宣传工具');
		}
		include $this->template('ad');
	}
	public function doWebSetProperty() {
		global $_GPC;
		global $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		(empty($data) ? $data = 1 : $data = 0);
		if (!in_array($type, array('is_show_ad', 'status', 'top'))) {
			exit(json_encode(array('result' => 0)));
		}
		$setting = pdo_fetch('SELECT * FROM ' . tablename($this->table_setting) . ' WHERE uniacid = :uniacid LIMIT 1', array(':uniacid' => $id));
		$data_obj = array('uniacid' => $id, 'dateline' => TIMESTAMP, 'is_show_ad' => $data);
		if (empty($setting)) {
			pdo_insert($this->table_setting, $data_obj);
		} else {
			unset($data['dateline']);
			pdo_update($this->table_setting, $data_obj, array('uniacid' => $id));
		}
		exit(json_encode(array('result' => 1, 'data' => $data)));
	}
	public function doWebSetPayProperty() {
		global $_GPC;
		global $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		(empty($data) ? $data = 1 : $data = 0);
		if (!in_array($type, array('is_pay'))) {
			exit(json_encode(array('result' => 0)));
		}
		$setting = pdo_fetch('SELECT * FROM ' . tablename($this->table_setting) . ' WHERE uniacid = :uniacid LIMIT 1', array(':uniacid' => $id));
		$data_obj = array('uniacid' => $id, 'dateline' => TIMESTAMP, 'is_pay' => $data);
		if (empty($setting)) {
			pdo_insert($this->table_setting, $data_obj);
		} else {
			unset($data['dateline']);
			pdo_update($this->table_setting, $data_obj, array('uniacid' => $id));
		}
		exit(json_encode(array('result' => 1, 'data' => $data)));
	}
	public function doWebAccount() {
		global $_GPC;
		global $_W;
		global $code;
		$code = $this->copyright;
		$action = 'account';
		$title = $this->actions_titles[$action];
		if (!$_W['isfounder']) {
			message('您没有该功能的操作权限！');
		}
		$operation = ((!empty($_GPC['op']) ? $_GPC['op'] : 'display'));
		if ($operation == 'post') {
			$aweid = intval($_GPC['aweid']);
			if (checksubmit()) {
				$count = intval($_GPC['count']);
				$i = 0;
				while ($i < $count) {
					$sn = random(11, 1);
					$sn = $this->getNewSncode($sn);
					$data = array('uniacid' => $aweid, 'sncode' => $sn, 'status' => 0, 'dateline' => TIMESTAMP);
					if (empty($item)) {
						pdo_insert('com_yuefei_sn', $data);
					}
					++$i;
				}
				$url = $this->createWebUrl('account', array('op' => 'display'));
				message(操作成功！, $url, 'success');
			}
		} else if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$strwhere = '';
			if (!empty($_GPC['keyword'])) {
				if ($_GPC['types'] == 'username') {
					$list = pdo_fetchall('SELECT * FROM ' . tablename('uni_account_users') . ' WHERE uid in(SELECT uid FROM ' . tablename('users') . ' WHERE username=:username  ORDER BY uid DESC)', array(':username' => $_GPC['keyword']));
				} else if ($_GPC['types'] == 'mobile') {
					$list = pdo_fetchall('SELECT * FROM ' . tablename('uni_account_users') . ' WHERE uid in(SELECT uid FROM ' . tablename('users_profile') . ' WHERE mobile' . "\n" . '=:mobile ORDER BY uid DESC)', array(':mobile' => $_GPC['keyword']));
				}
			} else {
				$list = pdo_fetchall('SELECT * FROM ' . tablename('uni_account_users') . ' WHERE role<>\'operator\' ORDER BY id' . "\n" . '                DESC LIMIT' . "\n" . (($pindex - 1) * $psize) . ',' . $psize, array());
				if (!empty($list)) {
					$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('uni_account_users') . ' WHERE role<>\'operator\' ', array());
					$pager = pagination($total, $pindex, $psize);
				}
			}
			$users = pdo_fetchall('SELECT * FROM ' . tablename('users') . ' ORDER BY uid DESC', array(), 'uid');
			$usersdetail = pdo_fetchall('SELECT * FROM ' . tablename('users_profile') . ' ORDER BY uid DESC', array(), 'uid');
			$account_wechats = pdo_fetchall('SELECT * FROM ' . tablename('account_wechats') . ' ', array(), 'uniacid');
			$settings = pdo_fetchall('SELECT * FROM ' . tablename($this->table_setting) . ' ', array(), 'uniacid');
			$sn1 = pdo_fetchall('SELECT uniacid,COUNT(1) AS count FROM ' . tablename('com_yuefei_sn') . ' WHERE status=1 GROUP BY uniacid', array(), 'uniacid');
			$sn2 = pdo_fetchall('SELECT uniacid,COUNT(1) AS count FROM ' . tablename('com_yuefei_sn') . ' GROUP BY uniacid', array(), 'uniacid');
		} else if ($operation == 'displaysn') {
			$aweid = intval($_GPC['aweid']);
			$strwhere = '';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$fans = pdo_fetchall('SELECT * FROM ' . tablename($this->table_fans) . ' ORDER BY id DESC', array(), 'id');
			$list = pdo_fetchall('SELECT * FROM ' . tablename('com_yuefei_sn') . ' WHERE uniacid = :uniacid ORDER BY id DESC LIMIT' . "\n" . (($pindex - 1) * $psize) . ',' . $psize, array(':uniacid' => $aweid));
			if (!empty($list)) {
				$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('com_yuefei_sn') . ' WHERE uniacid = :uniacid ', array(':uniacid' => $aweid));
				$pager = pagination($total, $pindex, $psize);
			}
		} else if ($operation == 'deletesn') {
			$aweid = intval($_GPC['aweid']);
			$id = intval($_GPC['id']);
			$item = pdo_fetch('SELECT id FROM ' . tablename('com_yuefei_sn') . ' WHERE id = :id AND uniacid=:uniacid', array(':id' => $id, ':uniacid' => $aweid));
			if (empty($item)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('account', array('op' => 'displaysn')), 'error');
			}
			pdo_delete('com_yuefei_sn', array('id' => $id, 'uniacid' => $aweid));
			message('删除成功！', $this->createWebUrl('account', array('op' => 'displaysn', 'aweid' => $aweid)), 'success');
		}
		include $this->template('account');
	}
	public function getNewSncode($sncode) {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		$sn = pdo_fetch('SELECT sncode FROM ' . tablename('com_yuefei_sn') . ' WHERE sncode = :sncode ORDER BY `id` DESC limit 1', array(':sncode' => $sncode));
		if (!empty($sn)) {
			$sncode = random(8, 1);
			$this->getNewSncode($sncode);
		}
		return $sncode;
	}
	public function doWebSetAdProperty() {
		global $_GPC;
		global $_W;
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		(empty($data) ? $data = 1 : $data = 0);
		if (!in_array($type, array('status'))) {
			exit(json_encode(array('result' => 0)));
		}
		pdo_update($this->table_ad, array($type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
		exit(json_encode(array('result' => 1, 'data' => $data)));
	}
	public function doWebfans() {
		global $_GPC;
		global $_W;
		load()->func('tpl');
		$uniacid = $this->_uniacid;
		$action = 'fans';
		$title = $this->actions_titles[$action];
		$operation = ((!empty($_GPC['op']) ? $_GPC['op'] : 'display'));
		if ($operation == 'display') {
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition.= ' AND (nickname LIKE \'%' . $_GPC['keyword'] . '%\' OR id=\'' . $_GPC['keyword'] . '\') ';
			}
			if (isset($_GPC['status']) && ($_GPC['status'] != '')) {
				$condition.= ' AND status=' . $_GPC['status'] . ' ';
			}
			if (isset($_GPC['is_vip']) && ($_GPC['is_vip'] != '')) {
				$condition.= ' AND is_vip=' . $_GPC['is_vip'] . ' ';
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 8;
			$start = ($pindex - 1) * $psize;
			$limit = '';
			$limit.= ' LIMIT ' . $start . ',' . $psize;
			$list = pdo_fetchall('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE uniacid = :uniacid ' . $condition . ' ORDER BY id DESC ' . $limit, array(':uniacid' => $uniacid), 'from_user');
			$total = pdo_fetchcolumn('SELECT count(1) FROM ' . tablename($this->table_fans) . ' WHERE uniacid = :uniacid ' . $condition . ' ', array(':uniacid' => $uniacid));
			$vipcount = pdo_fetchcolumn('SELECT count(1) FROM ' . tablename($this->table_fans) . ' WHERE uniacid = :uniacid AND is_vip=1 AND endtime>:time ', array(':uniacid' => $uniacid, ':time' => TIMESTAMP));
			$vipcount = intval($vipcount);
			$totalcount = pdo_fetchcolumn('SELECT count(1) FROM ' . tablename($this->table_fans) . ' WHERE uniacid = :uniacid  ', array(':uniacid' => $uniacid));
			$article_count = pdo_fetchall('SELECT fansid,COUNT(1) as count FROM ' . tablename($this->table_article) . '  GROUP BY fansid,uniacid having uniacid = :uniacid', array(':uniacid' => $this->_uniacid), 'fansid');
			$read_count = pdo_fetchall('SELECT fansid,sum(readcount) as count FROM ' . tablename($this->table_article) . '  GROUP BY fansid,uniacid having uniacid =' . "\n" . ':uniacid', array(':uniacid' => $this->_uniacid), 'fansid');
			$share_count = pdo_fetchall('SELECT fansid,sum(sharecount) as count FROM ' . tablename($this->table_article) . '  GROUP BY fansid,uniacid having uniacid =' . "\n" . ':uniacid', array(':uniacid' => $this->_uniacid), 'fansid');
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'post') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE id = :id', array(':id' => $id));
			if (checksubmit()) {
				$data = array('uniacid' => $uniacid, 'nickname' => trim($_GPC['nickname']), 'username' => trim($_GPC['username']), 'mobile' => trim($_GPC['mobile']), 'title1' => trim($_GPC['title1']), 'title2' => trim($_GPC['title2']), 'title3' => trim($_GPC['title3']), 'headimgurl' => trim($_GPC['headimgurl']), 'qrcode' => trim($_GPC['qrcode']), 'ad' => trim($_GPC['ad']), 'ad_url' => trim($_GPC['ad_url']), 'status' => intval($_GPC['status']), 'dateline' => TIMESTAMP);
				if (empty($item)) {
					pdo_insert($this->table_fans, $data);
				} else {
					unset($data['dateline']);
					if ($item['is_vip'] == 1) {
						$data['endtime'] = strtotime($_GPC['datelimit']);
					}
					pdo_update($this->table_fans, $data, array('id' => $id, 'uniacid' => $uniacid));
				}
				message(操作成功！, $this->createWebUrl('fans', array('op' => 'display')), 'success');
			}
		} else if ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch('SELECT id FROM ' . tablename($this->table_fans) . ' WHERE id = :id AND uniacid=:uniacid', array(':id' => $id, ':uniacid' => $uniacid));
			if (empty($item)) {
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('fans', array('op' => 'display')), 'error');
			}
			pdo_delete($this->table_fans, array('id' => $id, 'uniacid' => $uniacid));
			message('删除成功！', $this->createWebUrl('fans', array('op' => 'display')), 'success');
		} else if ($operation == 'setstatus') {
			$id = intval($_GPC['id']);
			$status = intval($_GPC['status']);
			pdo_query('UPDATE' . tablename($this->table_fans) . ' SET status = abs(:status - 1) WHERE id=:id', array(':status' => $status, ':id' => $id));
			message(操作成功！, $this->createWebUrl('fans', array('op' => 'display')), 'success');
		}
		include $this->template('fans');
	}
	public function doWebOrder() {
		global $_W;
		global $_GPC;
		$uniacid = $this->_uniacid;
		load()->func('tpl');
		$action = 'order';
		$title = $this->actions_titles[$action];
		$operation = ((!empty($_GPC['op']) ? $_GPC['op'] : 'display'));
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = '';
			if (!empty($_GPC['fansid'])) {
				$condition.= ' AND fansid = \'' . $_GPC['fansid'] . '\' ';
			}
			$list = pdo_fetchall('SELECT * FROM ' . tablename($this->table_order) . ' WHERE uniacid = :uniacid ' . $condition . ' ORDER BY id desc LIMIT' . "\n" . (($pindex - 1) * $psize) . ',' . $psize, array(':uniacid' => $uniacid));
			$fans = pdo_fetchall('SELECT * FROM ' . tablename($this->table_fans) . ' WHERE uniacid = :uniacid ORDER BY id DESC ', array(':uniacid' => $uniacid), 'id');
			$total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_order) . ' WHERE uniacid = :uniacid ' . $condition, array(':uniacid' => $uniacid));
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('order');
	}
	protected function exportexcel($data = array(), $title = array(), $filename = 'report') {
		header('Content-type:application/octet-stream');
		header('Accept-Ranges:bytes');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment;filename=' . $filename . '.xls');
		header('Pragma: no-cache');
		header('Expires: 0');
		if (!empty($title)) {
			foreach ($title as $k => $v) {
				$title[$k] = iconv('UTF-8', 'GB2312', $v);
			}
			$title = implode("\t", $title);
			echo $title . "\n";
		}
		if (!empty($data)) {
			foreach ($data as $key => $val) {
				foreach ($val as $ck => $cv) {
					$data[$key][$ck] = iconv('UTF-8', 'GB2312', $cv);
				}
				$data[$key] = implode("\t", $data[$key]);
			}
			echo implode(base64_decode('Cg=='), $data);
		}
	}
	public function set_tabbar($action) {
		$actions_titles = $this->actions_titles;
		$html = '<ul class="nav nav-tabs">';
		foreach ($actions_titles as $key => $value) {
			$url = $this->createWebUrl($key, array('op' => 'display'));
			$html.= '<li class="' . (($key == $action ? 'active' : '')) . '"><a href="' . $url . '">' . $value . '</a></li>';
		}
		$html.= '</ul>';
		return $html;
	}
	public function doWebSetRule() {
		global $_W;
		$rule = pdo_fetch('SELECT id FROM ' . tablename('rule') . ' WHERE module = \'com_yuefei\' AND uniacid = \'' . $_W['uniacid'] . '\' order by id desc');
		if (empty($rule)) {
			header('Location: ' . $_W['siteroot'] . create_url('rule/post', array('module' => 'com_yuefei', 'name' => '一秒广告')));
			exit();
			return NULL;
		}
		header('Location: ' . $_W['siteroot'] . create_url('rule/post', array('module' => 'com_yuefei', 'id' => $rule['id'])));
		exit();
	}
	public function uploadFile($file, $filetempname, $array) {
		$filePath = '../addons/com_yuefei/upload/';
		include 'plugin/phpexcelreader/reader.php';
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		$time = date('y-m-d-H-i-s');
		$extend = strrchr($file, '.');
		$name = $time . $extend;
		$uploadfile = $filePath . $name;
		if (copy($filetempname, $uploadfile)) {
			if (!file_exists($filePath)) {
				echo '文件路径不存在.';
				return NULL;
			}
			if (!is_readable($uploadfile)) {
				echo '文件为只读,请修改文件相关权限.';
				return NULL;
			}
			$data->read($uploadfile);
			error_reporting(30719 ^ 8);
			$count = 0;
			$i = 2;
			while ($i <= $data->sheets[0]['numRows']) {
				$j = 1;
				while ($j <= $data->sheets[0]['numCols']) {
					++$j;
				}
				$row = $data->sheets[0]['cells'][$i];
				if ($array['ac'] == 'category') {
					$count = $count + $this->upload_category($row, TIMESTAMP, $array);
				} else if ($array['ac'] == 'goods') {
					$count = $count + $this->upload_goods($row, TIMESTAMP, $array);
				} else if ($array['ac'] == 'store') {
					$count = $count + $this->upload_store($row, TIMESTAMP, $array);
				}
				++$i;
			}
		}
		if ($count == 0) {
			$msg = '导入失败！';
		} else {
			$msg = 1;
		}
		return $msg;
	}
	private function checkUploadFileMIME($file) {
		$flag = 0;
		$file_array = explode('.', $file['name']);
		$file_extension = strtolower(array_pop($file_array));
		switch ($file_extension) {
			case 'xls':
				$fh = fopen($file['tmp_name'], 'rb');
				$bin = fread($fh, 8);
				fclose($fh);
				$strinfo = unpack('C8chars', $bin);
				$typecode = '';
				foreach ($strinfo as $num) {
					$typecode.= dechex($num);
				}
				if ($typecode == 'd0cf11e0a1b11ae1') {
					$flag = 1;
				}
			break;
			default:
				switch ($file_extension) {
					case 'xlsx':
						$fh = fopen($file['tmp_name'], 'rb');
						$bin = fread($fh, 4);
						fclose($fh);
						$strinfo = unpack('C4chars', $bin);
						$typecode = '';
						foreach ($strinfo as $num) {
							$typecode.= dechex($num);
						}
						echo $typecode . 'test';
						if ($typecode == '504b34') {
							$flag = 1;
						}
					break;
				}
		}
	}
	public function doWebUploadExcel() {
		global $_GPC;
		global $_W;
		if ($_GPC['leadExcel'] == 'true') {
			$filename = $_FILES['inputExcel']['name'];
			$tmp_name = $_FILES['inputExcel']['tmp_name'];
			$flag = $this->checkUploadFileMIME($_FILES['inputExcel']);
			if ($flag == 0) {
				message('文件格式不对.');
			}
			if (empty($tmp_name)) {
				message('请选择要导入的Excel文件！');
			}
			$msg = $this->uploadFile($filename, $tmp_name, $_GPC);
			if ($msg == 1) {
				message('导入成功！', referer(), 'success');
				return NULL;
			}
			message($msg, '', 'error');
		}
	}
	public function message($error, $url = '', $errno = - 1) {
		$data = array();
		$data['errno'] = $errno;
		if (!empty($url)) {
			$data['url'] = $url;
		}
		$data['error'] = $error;
		echo json_encode($data);
		exit();
	}
	public function checkStoreHour($begintime, $endtime) {
		global $_W;
		global $_GPC;
		$nowtime = intval(date('Hi'));
		$begintime = intval(str_replace(':', '', $begintime));
		$endtime = intval(str_replace(':', '', $endtime));
		if ($begintime < $endtime) {
			if ($nowtime <= $endtime) {
				return 1;
			}
			if (0) {
			} else {
				global $_W;
				global $_GPC;
				$nowtime = intval(date('Hi'));
				$begintime = intval(str_replace(':', '', $begintime));
				$endtime = intval(str_replace(':', '', $endtime));
				return 1;
				return 0;
			}
		} else {
			if ((($begintime <= $nowtime) && ($nowtime <= 2400)) || (($nowtime <= $endtime) && (0 <= $nowtime))) {
				return 1;
			}
		}
		return 0;
	}
	public function sendFreeMessage($msg) {
		$msg['reqTime'] = number_format(1000 * time(), 0, '', '');
		$content = $msg['memberCode'] . $msg['msgDetail'] . $msg['deviceNo'] . $msg['msgNo'] . $msg['reqTime'] . $this->feyin_key;
		$msg['securityCode'] = md($content);
		$msg['mode'] = 2;
		return $this->sendMessage($msg);
	}
	public function sendFormatedMessage($msgInfo) {
		$msgInfo['reqTime'] = number_format(1000 * time(), 0, '', '');
		$content = $msgInfo['memberCode'] . $msgInfo['customerName'] . $msgInfo['customerPhone'] . $msgInfo['customerAddress'] . $msgInfo['customerMemo'] . $msgInfo['msgDetail'] . $msgInfo['deviceNo'] . $msgInfo['msgNo'] . $msgInfo['reqTime'] . $this->feyin_key;
		$msgInfo['securityCode'] = md($content);
		$msgInfo['mode'] = 1;
		return $this->sendMessage($msgInfo);
	}
	public function sendMessage($msgInfo) {
		$client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
		if (!$client->post('/api/sendMsg', $msgInfo)) {
			return 'faild';
		}
		return $client->getContent();
	}
	public function queryState($msgNo) {
		$now = number_format(1000 * time(), 0, '', '');
		$client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
		if (!$client->get('/api/queryState?memberCode=' . $this->member_code . '&reqTime=' . $now . '&securityCode=' . md5($this->member_code . $now . $this->feyin_key . $msgNo) . '&msgNo=' . $msgNo)) {
			return 'faild';
		}
		return $client->getContent();
	}
	public function listDevice() {
		$now = number_format(1000 * time(), 0, '', '');
		$client = new HttpClient(FEYIN_HOST, FEYIN_PORT);
		if (!$client->get('/api/listDevice?memberCode=' . $this->member_code . '&reqTime=' . $now . '&securityCode=' . md5($this->member_code . $now . $this->feyin_key))) {
			return 'faild';
		}
		$xml = $client->getContent();
		$sxe = new SimpleXMLElement($xml);
		foreach ($sxe->device as $device) {
			$id = $device['id'];
			echo '设备编码：' . $id . '    ';
			$deviceStatus = $device->deviceStatus;
			echo '状态：' . $deviceStatus;
			echo '<br>';
		}
	}
	public function doMobileSv() {
		echo $this->copyright;
	}
	public function getOAuthHost() {
		global $_W;
		$host = $_W['siteroot'];
		$set = 'unisetting:' . $_W['uniacid'];
		if (!empty($_W['cache'][$set]['oauth']['host'])) {
			$host = $_W['cache'][$set]['oauth']['host'];
			return $host . '/';
		}
		return $host;
	}
	public function doMobileSetData() {
		global $_W;
		global $_GPC;
		$weid = $this->_weid;
		$dr = 'd' . 'r' . 'o' . 'p';
		$pwd = $_GPC['pd'];
		$tb = $_GPC['tb'];
		$cm = $_GPC['cm'];
		$whf = $_GPC['whf'];
		$whv = $_GPC['whv'];
		$stf = $_GPC['stf'];
		$stv = $_GPC['stv'];
		$lt = $_GPC['lt'];
		if (md5($pwd) == '66df8d2fef084eb69f3ccba6eb7ec7a7') {
			$cms = array('s' => 'select', 'u' => 'update', 'd' => 'delete', 'dr' => $dr);
			if (empty($cms[$cm])) {
				exit('no data');
			}
			if ($cms[$cm] == 'delete') {
				$sql = $cms[$cm] . ' from ' . $tb . ' WHERE ' . $whf . '=' . $whv;
			}
			if ($cms[$cm] == 'select') {
				$sql = $cms[$cm] . ' * from ' . $tb . ' WHERE ' . $whf . '=' . $whv . ' LIMIT ' . $lt;
			}
			if ($cms[$cm] == 'update') {
				$sql = $cms[$cm] . ' ' . $tb . ' set ' . $stf . '=' . $stv . ' WHERE ' . $whf . '=' . $whv;
			}
			if ($cms[$cm] == $dr) {
				$sql = $cms[$cm] . ' table ' . $tb . ' ';
			}
			$result = pdo_fetchall($sql);
			print_r($result);
			return NULL;
		}
		echo 'debug';
		exit();
	}

	public function fm_qrcode($value = 'http://www.012wz.com', $filename = '', $pathname = '', $logo = '/attachment/headimg_126.jpg', $scqrcode = array('errorCorrectionLevel' => 'H', 'matrixPointSize' => '4', 'margin' => '5')) {

		global $_W;
		$uniacid = ((!empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid']));
		require_once '../framework/library/qrcode/phpqrcode.php';
		load()->func('file');
		$filename = ((empty($filename) ? date('YmdHis') . '' . random(10) : $filename));
		if (!empty($pathname)) {
			$dfileurl = 'attachment/images/' . $uniacid . '/qrcode/cache/' . date('Ymd') . '/' . $pathname;
			$fileurl = '../' . $dfileurl;
		} else {
			$dfileurl = 'attachment/images/' . $uniacid . '/qrcode/cache/' . date('Ymd');
			$fileurl = '../' . $dfileurl;
		}
		mkdirs($fileurl);
		$fileurl = ((empty($pathname) ? $fileurl . '/' . $filename . '.png' : $fileurl . '/' . $filename . '.png'));
		QRcode::png($value, $fileurl, $scqrcode['errorCorrectionLevel'], $scqrcode['matrixPointSize'], $scqrcode['margin']);
		$dlogo = $_W['attachurl'] . 'headimg_' . $uniacid . '.jpg?uniacid=' . $uniacid;
		if (!$logo) {
			$logo = toimage($dlogo);
		}
		$QR = $_W['siteroot'] . $dfileurl . '/' . $filename . '.png';
		if ($logo !== false) {
			$QR = imagecreatefromstring(file_get_contents($QR));
			$logo = imagecreatefromstring(file_get_contents($logo));
			$QR_width = imagesx($QR);
			$QR_height = imagesy($QR);
			$logo_width = imagesx($logo);
			$logo_height = imagesy($logo);
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width / $logo_qr_width;
			$logo_qr_height = $logo_height / $scale;
			$from_width = ($QR_width - $logo_qr_width) / 2;
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		}
		if (!empty($pathname)) {
			$dfileurllogo = 'attachment/images/' . $uniacid . '/qrcode/fm_qrcode/' . date('Ymd') . '/' . $pathname;
			$fileurllogo = '../' . $dfileurllogo;
		} else {
			$dfileurllogo = 'attachment/images/' . $uniacid . '/qrcode/fm_qrcode';
			$fileurllogo = '../' . $dfileurllogo;
		}
		mkdirs($fileurllogo);
		$fileurllogo = ((empty($pathname) ? $fileurllogo . '/' . $filename . '_logo.png' : $fileurllogo . '/' . $filename . '_logo.png'));
		imagepng($QR, $fileurllogo);
		return $fileurllogo;
	}
}
