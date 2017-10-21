<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */

defined('IN_IA') or exit('Access Denied');

load()->model('mc');
load()->classs('wesession');
load()->classs('account');

$dos = array('chats', 'send', 'endchats');
$do = in_array($do , $dos) ? $do : 'chats';
uni_user_permission_check('mc_fans');

if ($do == 'chats') {
	$_W['page']['title'] = '粉丝聊天';
	$openid = addslashes($_GPC['openid']);
	$fans_info = mc_fansinfo($openid);
	if (!empty($fans_info['uid'])) {
		$fans_info['member_info'] = mc_fetch($fans_info['uid']);
	}
	$chat_record = pdo_getslice('mc_chats_record', array('uniacid' => $_W['uniacid'], 'openid' => $openid), array('1', 20), $total, array(), '', 'createtime desc');
	if (!empty($chat_record)) {
		foreach ($chat_record as &$record) {
			if ($record['flag'] == FANS_CHATS_FROM_SYSTEM) {
				$record['content'] = iunserializer($record['content']);
				$record['content'] = urldecode($record['content']['content']);
			}
			$record['createtime'] = date('Y-m-d H:i', $record['createtime']);
		}
	}
}

if ($do == 'send') {
	$type = addslashes($_GPC['type']);
	$content = trim(htmlspecialchars_decode($_GPC['content']), '\"');
	$send['touser'] = trim($_GPC['openid']);
	$send['msgtype'] = $type;
	if ($type == 'text') {
		$send['text'] = array('content' => urlencode($content));
	} elseif ($type == 'image') {
		$send['image'] = array('media_id' => $content);
	} elseif ($type == 'voice') {
		$send['voice'] = array('media_id' => $content);
	} elseif($type == 'video') {
		$content = json_decode($content, true);
		$send['video'] = array(
			'media_id' => $content['mediaid'],
			'thumb_media_id' => '',
			'title' => urlencode($content['title']),
			'description' => ''
		);
	}  elseif($type == 'music') {
		$send['music'] = array(
			'musicurl' => tomedia($_GPC['musicurl']),
			'hqmusicurl' => tomedia($_GPC['hqmusicurl']),
			'title' => urlencode($_GPC['title']),
			'description' => urlencode($_GPC['description']),
			'thumb_media_id' => $_GPC['thumb_media_id'],
		);
	} elseif($type == 'news') {
		$content = json_decode($content, true);
		$send['msgtype'] =  'mpnews';
		$send['mpnews'] = array(
			'media_id' => $content['mediaid']
		);
	}
	$account_api = WeAccount::create($_W['acid']);
	$result = $account_api->sendCustomNotice($send);
	if (is_error($result)) {
		iajax(-1, $result['meaasge']);
	} else {
				$account = account_fetch($_W['acid']);
		$message['from'] = $_W['openid'] = $send['touser'];
		$message['to'] = $account['original'];
		if(!empty($message['to'])) {
			$sessionid = md5($message['from'] . $message['to'] . $_W['uniacid']);
			session_id($sessionid);
			WeSession::start($_W['uniacid'], $_W['openid'], 300);
			$processor = WeUtility::createModuleProcessor('chats');
			$processor->begin(300);
		}

		if($send['msgtype'] == 'mpnews') {
			$material = pdo_getcolumn('wechat_attachment', array('uniacid' => $_W['uniacid'], 'media_id' => $content['mediaid']), 'id');
			$content = urlencode('图文素材');
		}
				pdo_insert('mc_chats_record',array(
			'uniacid' => $_W['uniacid'],
			'acid' => $acid,
			'flag' => 1,
			'openid' => $send['touser'],
			'msgtype' => $send['msgtype'],
			'content' => iserializer($send[$send['msgtype']]),
			'createtime' => TIMESTAMP,
		));
		iajax(0, array('createtime' => date('Y-m-d', time()), 'content' => $content), '');
	}
}

if ($do == 'endchats') {
	$openid = trim($_GPC['openid']);
	if (empty($openid)) {
		iajax(1, '粉丝openid不合法', '');
	}
	$fans_info = mc_fansinfo($openid);
	$account = account_fetch($fans_info['acid']);
	$message['from'] = $_W['openid'] = $openid['openid'];
	$message['to'] = $account['original'];
	if(!empty($message['to'])) {
		$sessionid = md5($message['from'] . $message['to'] . $_W['uniacid']);
		session_id($sessionid);
		WeSession::start($_W['uniacid'], $_W['openid'], 300);
		$processor = WeUtility::createModuleProcessor('chats');
		$processor->end();
	}
	if (is_error($result)) {
		iajax(1, $result);
	}
}
template('mc/chats');