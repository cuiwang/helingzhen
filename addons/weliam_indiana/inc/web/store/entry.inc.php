<?php 
defined('IN_IA') or exit('Access Denied');
load()->model('reply');
require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';

$op = $_GPC['op'];
$url1 = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=".$op."&m=weliam_indiana";
$allcovers = array('index' => array('url' => $url1, 'name' => '首页'),'person' => array('url' => $url1, 'name' => '个人中心'),'rule' => array('url' => $url1, 'name' => '计算规则'));

$url = $allcovers[$op]['url'];
$name = $allcovers[$op]['name'];

if($op != 'qr'){
	$rule = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'cover', ':name' => 'weliam_indiana' . $name . '入口设置'));

	if (!empty($rule)) {
		$keyword = pdo_fetch('select * from ' . tablename('rule_keyword') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rule['id']));
		$cover = pdo_fetch('select * from ' . tablename('cover_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rule['id']));
	}
	
	if (checksubmit('submit')) {
		$data = (is_array($_GPC['cover']) ? $_GPC['cover'] : array());
	
		if (empty($data['keyword'])) {
			message('请输入关键词!');
		}
		$keyword1 = keyExist($data['keyword']);
		if (!empty($keyword1)) {
			if ($keyword1['name'] != ('weliam_indiana' . $name . '入口设置')) {
				message('关键字已存在!');
			}
		}
		if (!empty($rule)) {
			pdo_delete('rule', array('id' => $rule['id'], 'uniacid' => $_W['uniacid']));
			pdo_delete('rule_keyword', array('rid' => $rule['id'], 'uniacid' => $_W['uniacid']));
			pdo_delete('cover_reply', array('rid' => $rule['id'], 'uniacid' => $_W['uniacid']));
		}
	
		$rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'weliam_indiana' . $name . '入口设置', 'module' => 'cover', 'displayorder' => 0, 'status' => intval($data['status']));
		pdo_insert('rule', $rule_data);
		$rid = pdo_insertid();
		
		$keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'cover', 'content' => trim($data['keyword']), 'type' => 1, 'displayorder' => 0, 'status' => intval($data['status']));
		pdo_insert('rule_keyword', $keyword_data);
		
		$cover_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'weliam_indiana', 'title' => trim($data['title']), 'description' => trim($data['desc']), 'thumb' => $data['thumb'], 'url' => $url);
		pdo_insert('cover_reply', $cover_data);
		message('保存成功！');
	}
	
	$cover = array('rule' => $rule, 'cover' => $cover, 'keyword' => $keyword, 'url' => $url,'name' => $name);
	
	include $this->template('entry');
}

if($op == 'qr'){
	$url = $_GPC['url'];
	QRcode :: png($url, false, QR_ECLEVEL_H, 4);
}
