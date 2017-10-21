<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');

$_W['page']['title'] = '注册选项 - 用户设置 - 用户管理';
if (empty($_W['setting']['register']['open'])) {
	itoast('本站暂未开启注册功能，请联系管理员！', '', '');
}

$extendfields = pdo_getall('profile_fields', array('available' => 1, 'showinregister' => 1), array('field', 'title', 'description', 'required'), 'field', 'displayorder DESC');
if(checksubmit()) {
	$member = array();
	$member['username'] = trim($_GPC['username']);
	$owner_uid = intval($_GPC['owner_uid']);
	if(!preg_match(REGULAR_USERNAME, $member['username'])) {
		itoast('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。', '', '');
	}
	if(user_check(array('username' => $member['username']))) {
		itoast('非常抱歉，此用户名已经被注册，你需要更换注册名称！', '', '');
	}
	$member['password'] = $_GPC['password'];
	if(istrlen($member['password']) < 8) {
		itoast('必须输入密码，且密码长度不得低于8位。', '', '');
	}
	$profile = array();
 	if (!empty($extendfields)) {
		$fields = array_keys($extendfields);
		if(in_array('birthyear', $fields)) {
			$extendfields[] = array('field' => 'birthmonth', 'title' => '出生生日', 'required' => $extendfields['birthyear']['required']);
			$extendfields[] = array('field' => 'birthday', 'title' => '出生生日', 'required' => $extendfields['birthyear']['required']);
			$_GPC['birthyear'] = $_GPC['birth']['year'];
			$_GPC['birthmonth'] = $_GPC['birth']['month'];
			$_GPC['birthday'] = $_GPC['birth']['day'];
		}
		if(in_array('resideprovince', $fields)) {
			$extendfields[] = array('field' => 'residecity', 'title' => '居住地址', 'required' => $extendfields['resideprovince']['required']);
			$extendfields[] = array('field' => 'residedist', 'title' => '居住地址', 'required' => $extendfields['resideprovince']['required']);
			$_GPC['resideprovince'] = $_GPC['reside']['province'];
			$_GPC['residecity'] = $_GPC['reside']['city'];
			$_GPC['residedist'] = $_GPC['reside']['district'];
		}
		foreach ($extendfields as $row) {
			if (!empty($row['required']) && empty($_GPC[$row['field']])) {
				itoast('“'.$row['title'].'”此项为必填项，请返回填写完整！', '', '');
			}
			$profile[$row['field']] = $_GPC[$row['field']];
		}
	}
	if(!empty($_W['setting']['register']['code'])) {
		if (!checkcaptcha($_GPC['code'])) {
			itoast('你输入的验证码不正确, 请重新输入.', '', '');
		}
	}

	$member['status'] = !empty($_W['setting']['register']['verify']) ? 1 : 2;
	$member['remark'] = '';
	$member['groupid'] = intval($_W['setting']['register']['groupid']);
	if (empty($member['groupid'])) {
		$member['groupid'] = pdo_fetchcolumn('SELECT id FROM '.tablename('users_group').' ORDER BY id ASC LIMIT 1');
		$member['groupid'] = intval($member['groupid']);
	}
	$group = user_group_detail_info($member['groupid']);
	
	$timelimit = intval($group['timelimit']);
	if($timelimit > 0) {
		$member['endtime'] = strtotime($timelimit . ' days');
	}
	$member['starttime'] = TIMESTAMP;
	if (!empty($owner_uid)) {
		$member['owner_uid'] = pdo_getcolumn('users', array('uid' => $owner_uid, 'founder_groupid' => ACCOUNT_MANAGE_GROUP_VICE_FOUNDER), 'uid');
	}
	$uid = user_register($member);
	if($uid > 0) {
		unset($member['password']);
		$member['uid'] = $uid;
		if (!empty($profile)) {
			$profile['uid'] = $uid;
			$profile['createtime'] = TIMESTAMP;
			pdo_insert('users_profile', $profile);
		}
		pdo_update('users_invitation', array('inviteuid' => $uid), array('id' => $invite['id']));
		itoast('注册成功'.(!empty($_W['setting']['register']['verify']) ? '，請等待管理员审核！' : '，请重新登录！'), url('user/login', array('uid' => $uid, 'username' => $member['username'])), 'success');
	}
	itoast('增加用户失败，请稍候重试或联系网站管理员解决！', '', '');
}
template('user/register');