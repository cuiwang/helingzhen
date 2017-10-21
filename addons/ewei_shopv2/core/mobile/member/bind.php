<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Bind_EweiShopV2Page extends MobileLoginPage
{
	protected $member;

	public function __construct()
	{
		global $_W;
		global $_GPC;
		parent::__construct();
	}

	public function main()
	{
		global $_W;
		global $_GPC;
		@session_start();
		$member = m('member')->getMember($_W['openid']);
		$wapset = m('common')->getSysset('wap');
		$appset = m('common')->getSysset('app');

		if (!p('threen')) {
			if (empty($wapset['open']) && !empty($appset['isclose'])) {
				$this->message('未开启绑定设置');
			}
		}

		$bind = (!empty($member['mobile']) && !empty($member['mobileverify']) ? 1 : 0);

		if ($_W['ispost']) {
			$mobile = trim($_GPC['mobile']);
			$verifycode = trim($_GPC['verifycode']);
			$pwd = trim($_GPC['pwd']);
			$confirm = intval($_GPC['confirm']);
			$key = '__ewei_shopv2_member_verifycodesession_' . $_W['uniacid'] . '_' . $mobile;
			if (!isset($_SESSION[$key]) || ($_SESSION[$key] !== $verifycode) || !isset($_SESSION['verifycodesendtime']) || (($_SESSION['verifycodesendtime'] + 600) < time())) {
				show_json(0, '验证码错误或已过期');
			}

			$member2 = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where mobile=:mobile and uniacid=:uniacid and mobileverify=1 limit 1', array(':mobile' => $mobile, ':uniacid' => $_W['uniacid']));

			if (empty($member2)) {
				$salt = m('account')->getSalt();
				m('bind')->update($member['id'], array('mobile' => $mobile, 'pwd' => md5($pwd . $salt), 'salt' => $salt, 'mobileverify' => 1));
				unset($_SESSION[$key]);
				m('account')->setLogin($member['id']);

				if (p('task')) {
					p('task')->checkTaskReward('member_info', 1, $_W['openid']);
				}

				show_json(1, 'bind success (0)');
			}

			if ($member['id'] == $member2['id']) {
				show_json(0, '此手机号已与当前账号绑定');
			}

			if (m('bind')->iswxm($member) && m('bind')->iswxm($member2)) {
				if ($confirm) {
					$salt = m('account')->getSalt();
					m('bind')->update($member['id'], array('mobile' => $mobile, 'pwd' => md5($pwd . $salt), 'salt' => $salt, 'mobileverify' => 1));
					m('bind')->update($member2['id'], array('mobileverify' => 0));
					unset($_SESSION[$key]);
					m('account')->setLogin($member['id']);

					if (p('task')) {
						p('task')->checkTaskReward('member_info', 1, $_W['openid']);
					}

					show_json(1, 'bind success (1)');
				}
				else {
					show_json(-1, '<center>此手机号已与其他帐号绑定<br>如果继续将会解绑之前帐号<br>确定继续吗？</center>');
				}
			}

			if (!m('bind')->iswxm($member2)) {
				if ($confirm) {
					$result = m('bind')->merge($member2, $member);

					if (empty($result['errno'])) {
						show_json(0, $result['message']);
					}

					$salt = m('account')->getSalt();
					m('bind')->update($member['id'], array('mobile' => $mobile, 'pwd' => md5($pwd . $salt), 'salt' => $salt, 'mobileverify' => 1));
					unset($_SESSION[$key]);
					m('account')->setLogin($member['id']);

					if (p('task')) {
						p('task')->checkTaskReward('member_info', 1, $_W['openid']);
					}

					show_json(1, 'bind success (2)');
				}
				else {
					show_json(-1, '<center>此手机号已通过其他方式注册<br>如果继续将会合并账号信息<br>确定继续吗？</center>');
				}
			}

			if (!m('bind')->iswxm($member)) {
				if ($confirm) {
					$result = m('bind')->merge($member, $member2);

					if (empty($result['errno'])) {
						show_json(0, $result['message']);
					}

					$salt = m('account')->getSalt();
					m('bind')->update($member2['id'], array('mobile' => $mobile, 'pwd' => md5($pwd . $salt), 'salt' => $salt, 'mobileverify' => 1));
					unset($_SESSION[$key]);
					m('account')->setLogin($member2['id']);

					if (p('task')) {
						p('task')->checkTaskReward('member_info', 1, $_W['openid']);
					}

					show_json(1, 'bind success (3)');
				}
				else {
					show_json(-1, '<center>此手机号已通过其他方式注册<br>如果继续将会合并账号信息<br>确定继续吗？</center>');
				}
			}
		}

		$sendtime = $_SESSION['verifycodesendtime'];
		if (empty($sendtime) || (($sendtime + 60) < time())) {
			$endtime = 0;
		}
		else {
			$endtime = 60 - time() - $sendtime;
		}

		include $this->template();
	}
}

?>
