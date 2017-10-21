<?php
 
function app_error($errcode = 0, $message = '')
{
	exit(json_encode(array('error' => $errcode, 'message' => empty($message) ? AppError::getError($errcode) : $message)));
}

function app_json($result = NULL, $openid)
{
	global $_GPC;
	global $_W;
	$ret = array();

	if (!is_array($result)) {
		$result = array();
	}

	$ret['error'] = 0;
	$key = time() . '@' . $openid;
	$auth = array('authkey' => base64_encode(authcode($key, 'ENCODE', 'ewei_shopv2_wxapp')));
	m('cache')->set($auth['authkey'], 1);
	exit(json_encode(array_merge($ret, $auth, $result)));
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'app/core/error_code.php';
require EWEI_SHOPV2_PLUGIN . 'app/core/wxapp/wxBizDataCrypt.php';
class Wxapp_EweiShopV2Page extends Page
{
	protected $appid;
	protected $appsecret;

	public function __construct()
	{
		$data = m('common')->getSysset('app');
		$this->appid = $data['appid'];
		$this->appsecret = $data['secret'];
	}

	public function login()
	{
		global $_GPC;
		global $_W;
		$code = trim($_GPC['code']);

		if (empty($code)) {
			app_error(AppError::$ParamsError);
		}

		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $this->appid . '&secret=' . $this->appsecret . '&js_code=' . $code . '&grant_type=authorization_code';
		load()->func('communication');
		$resp = ihttp_request($url);

		if (is_error($resp)) {
			app_error(AppError::$SystemError, $resp['message']);
		}

		$arr = @json_decode($resp['content'], true);
		$arr['isclose'] = $_W['shopset']['app']['isclose'];

		if (!empty($_W['shopset']['app']['isclose'])) {
			$arr['closetext'] = $_W['shopset']['app']['closetext'];
		}

		if (!is_array($arr) || !isset($arr['openid'])) {
			app_error(AppError::$WxAppLoginError);
		}

		app_json($arr, $arr['openid']);
	}

	/**
     * 微信小程序登录
     */
	public function auth()
	{
		global $_GPC;
		global $_W;
		$encryptedData = trim($_GPC['data']);
		$iv = trim($_GPC['iv']);
		$sessionKey = trim($_GPC['sessionKey']);
		if (empty($encryptedData) || empty($iv)) {
			app_error(AppError::$ParamsError);
		}

		$pc = new WXBizDataCrypt($this->appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data);

		if ($errCode == 0) {
			$data = json_decode($data, true);
			$member = m('member')->getMember('sns_wa_' . $data['openId']);

			if (empty($member)) {
				$member = array('uniacid' => $_W['uniacid'], 'uid' => 0, 'openid' => 'sns_wa_' . $data['openId'], 'nickname' => !empty($data['nickName']) ? $data['nickName'] : '', 'avatar' => !empty($data['avatarUrl']) ? $data['avatarUrl'] : '', 'gender' => !empty($data['gender']) ? $data['gender'] : '-1', 'openid_wa' => $data['openId'], 'comefrom' => 'sns_wa', 'createtime' => time(), 'status' => 0);
				pdo_insert('ewei_shop_member', $member);
				$id = pdo_insertid();
				$data['id'] = $id;
				$data['uniacid'] = $_W['uniacid'];
			}
			else {
				$updateData = array('nickname' => !empty($data['nickName']) ? $data['nickName'] : '', 'avatar' => !empty($data['avatarUrl']) ? $data['avatarUrl'] : '', 'gender' => !empty($data['gender']) ? $data['gender'] : '-1');
				pdo_update('ewei_shop_member', $updateData, array('id' => $member['id'], 'uniacid' => $member['uniacid']));
				$data['id'] = $member['id'];
				$data['uniacid'] = $member['uniacid'];
			}

			app_json($data, $data['openId']);
		}

		app_error(AppError::$WxAppError, '登录错误, 错误代码: ' . $errCode);
	}

	public function check()
	{
		global $_GPC;
		global $_W;
		$openid = trim($_GPC['openid']);

		if (empty($openid)) {
			app_error(AppError::$ParamsError);
		}

		$wxopenid = 'sns_wa_' . $openid;
		$member = m('member')->getMember($wxopenid);

		if (empty($member)) {
			$member = array('uniacid' => $_W['uniacid'], 'uid' => 0, 'openid' => $wxopenid, 'openid_wa' => $openid, 'comefrom' => 'sns_wa', 'createtime' => time(), 'status' => 0);
			pdo_insert('ewei_shop_member', $member);
			$member['id'] = pdo_insertid();
		}

		app_json(array('uniacid' => $member['uniacid'], 'openid' => $member['openid'], 'id' => $member['id'], 'nickname' => $member['nickname'], 'avatarUrl' => tomedia($member['avatar'])), $member['openid']);
	}
}

?>
