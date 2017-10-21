<?php


defined('IN_IA') || exit('Access Denied');
require 'jssdk.php';
class Enjoy_recuitModuleSite extends WeModuleSite
{
	public function doWebrefresh()
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$result['res'] = pdo_update('enjoy_recuit_position', array('stime' => TIMESTAMP), array('id' => $id));
		$result['time'] = date('Y-m-d', TIMESTAMP);
		echo json_encode($result);
	}

	public function doWebplay()
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$play = $_GPC['play'];

		if ($play == 0) {
			$splay = 1;
		}
		 else {
			$splay = 0;
		}

		$result['res'] = pdo_update('enjoy_recuit_position', array('play' => $splay), array('id' => $id));
		$result['play'] = $splay;
		echo json_encode($result);
	}

	public function doWebitaly()
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$openid = $_GPC['openid'];
		$italy = $_GPC['italy'];

		if ($italy == 0) {
			$staly = 1;
		}
		 else {
			$staly = 0;
		}

		$result['res'] = pdo_query('update ' . tablename('enjoy_recuit_basic') . ' set italy=' . $staly . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
		$result['italy'] = $staly;
		echo json_encode($result);
	}

	protected function auth()
	{
		global $_W;
		session_start();
		$openid = $_SESSION['__:proxy:openid'];
		$uniacid = $_W['uniacid'];

		if (!empty($openid)) {
			$exists = pdo_fetchcolumn('select * from ' . tablename('enjoy_recuit_fans') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');

			if (!empty($exists)) {
				return $exists;
			}

		}


		if (empty($this->module['config']['appid']) || empty($this->module['config']['appsecret'])) {
			message('系统还未开放');
		}


		$callback = $_W['siteroot'] . 'app' . substr($this->createMobileUrl('auth'), 1);
		$callback = urlencode($callback);
		$state = $_SERVER['REQUEST_URI'];
		$stateKey = substr(md5($state), 0, 8);
		$_SESSION['__:proxy:forward'] = $state;
		$forward = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->module['config']['appid'] . '&redirect_uri=' . $callback . '&response_type=code&scope=snsapi_userinfo&state=' . $stateKey . '#wechat_redirect';
		header('Location: ' . $forward);
		exit();
	}

	public function doMobileAuth()
	{
		global $_GPC;
		global $_W;
		session_start();
		if (empty($this->module['config']['appid']) || empty($this->module['config']['appsecret'])) {
			message('系统还未开放');
		}


		$code = $_GPC['code'];
		load()->func('communication');
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->module['config']['appid'] . '&secret=' . $this->module['config']['appsecret'] . '&code=' . $code . '&grant_type=authorization_code';
		$resp = ihttp_get($url);

		if (is_error($resp)) {
			message('系统错误, 详情: ' . $resp['message']);
		}


		$auth = @json_decode($resp['content'], true);
		if (is_array($auth) && !empty($auth['openid'])) {
			$url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $auth['access_token'] . '&openid=' . $auth['openid'] . '&lang=zh_CN';
			$resp = ihttp_get($url);

			if (is_error($resp)) {
				message('系统错误');
			}


			$info = @json_decode($resp['content'], true);
			if (is_array($info) && !empty($info['openid'])) {
				$user = array();
				$user['uniacid'] = $_W['uniacid'];
				$user['openid'] = $_W['openid'];
				$user['unionid'] = $info['unionid'];
				$user['nickname'] = $info['nickname'];
				$user['gender'] = $info['sex'];
				$user['city'] = $info['city'];
				$user['state'] = $info['province'];
				$user['avatar'] = $info['headimgurl'];
				$user['country'] = $info['country'];

				if (!empty($user['avatar'])) {
					$user['avatar'] = rtrim($user['avatar'], '0');
					$user['avatar'] .= '132';
				}


				$exists = pdo_fetchcolumn('select * from ' . tablename('enjoy_recuit_fans') . ' where openid=\'' . $_W['openid'] . '\' and uniacid=' . $_W['uniacid'] . '');

				if (!empty($exists)) {
				}
				 else {
					pdo_insert('enjoy_recuit_fans', $user);
				}

				$_SESSION['__:proxy:openid'] = $user['openid'];
				$_SESSION['__:proxy:avatar'] = $user['avatar'];
				$forward = $_SESSION['__:proxy:forward'];
				header('Location: ' . $forward);
				exit();
			}

		}


		message('系统错误');
	}

	public function doMobileaddcridet()
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$openid = $_GPC['openid'];
		$credit = pdo_fetchcolumn('select share_credit from ' . tablename('enjoy_recuit_culture') . ' where uniacid=' . $uniacid . '');
		$uid = pdo_fetchcolumn('select uid from ' . tablename('mc_mapping_fans') . ' where openid=\'' . $openid . '\' and uniacid=' . $uniacid . '');
		$update = pdo_query('update ' . tablename('mc_members') . ' set credit1=credit1+' . $credit . ' where uid=' . $uid . '');

		if ($update == 1) {
			$data = array('uid' => $uid, 'uniacid' => $uniacid, 'credittype' => 'credit1', 'num' => $credit, 'operator' => 1, 'createtime' => TIMESTAMP, 'remark' => '分享职位送');
			pdo_insert('mc_credits_record', $data);
			$result['res'] = pdo_insertid();
		}


		echo json_encode($result);
	}

	public function ImgUpload()
	{
		global $_W;
		global $_GPC;

		if (empty($_W['openid'])) {
			message('请从微信端登陆后上传照片..');
		}


		if (empty($_FILES['file'])) {
			message('上传的图片不能为空哦...');
			return;
		}


		if ($_FILES['file']['error'] != 0) {
			message('上传失败,请稍后再试..');
		}


		$size = $_FILES['file']['size'];

		if (2097152 < $size) {
			message('上传的图片大小不能超过2M..');
		}


		$_W['uploadsetting'] = array();
		$_W['uploadsetting']['image']['folder'] = '/images/' . $_W['weid'];
		$_W['uploadsetting']['image']['extentions'] = $_W['config']['upload']['image']['extentions'];
		$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
		load()->func('file');
		$file = file_upload($_FILES['file'], 'image');

		if (is_error($file)) {
			message($file['message']);
		}


		$result['url'] = $file['url'];
		$result['error'] = 0;
		$result['filename'] = $file['path'];
		$result['url'] = $_W['attachurl'] . $result['filename'];
		$res = pdo_query('update ' . tablename('enjoy_recuit_basic') . ' set avatar=\'' . $result['filename'] . '\' where openid=\'' . $_W['openid'] . '\' and uniacid=' . $_W['uniacid'] . '');

		if ($res == 0) {
			message('系统正忙..请喝杯茶等一等好吗..');
		}

	}
}

