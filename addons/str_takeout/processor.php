<?php
defined('IN_IA') or exit('Access Denied');

class Str_takeoutModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('str_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
		if(empty($row)) {
			return '';
		}
		$store = pdo_get('str_store', array('uniacid' => $_W['uniacid'], 'id' => $row['sid']));
		if(empty($store)) {
			return '';
		}
		$sid = $store['id'];
		if($row['type'] == 3) {
			//扫桌号
			$table = pdo_get('str_tables', array('uniacid' => $_W['uniacid'], 'id' => $row['table_id']));
			if(empty($table)) {
				return '';
			}
			$account = WeAccount::create($_W['acid']);
			$fans = $account->fansQueryInfo($_W['openid']);
			$data = array(
				'uniacid' => $_W['uniacid'],
				'sid' => $row['sid'],
				'table_id' => $row['table_id'],
				'openid' => $_W['openid'],
				'nickname' => $fans['nickname'],
				'avatar' => $fans['headimgurl'],
				'createtime' => TIMESTAMP,
			);
			pdo_insert('str_tables_scan', $data);
			pdo_update('str_tables', array('scan_num' => $table['scan_num'] + 1), array('uniacid' => $_W['uniacid'], 'id' => $row['table_id']));
			$url = murl('entry', array('m' => 'str_takeout', 'do' => 'dish', 'tid' => $row['table_id'], 'sid' => $sid, 'mode' => 1), true, true);
			$news = array();
			$news[] = array(
				'title' => $store['title'] . "-{$table['title']}号桌",
				'description' => "欢迎光临{$store['title']}, 您当前在{$table['title']}号桌点餐",
				'picurl' => $store['thumb'],
				'url' => $url
			);
			return $this->respNews($news);
		} elseif($row['type'] == 2) {
			//排号二维码
			if($store['is_assign'] == 2) {
				return $this->respText("{$store['title']} 已关闭排号功能,请联系商家");
			}
			$url = murl('entry', array('m' => 'str_takeout', 'do' => 'assign', 'sid' => $sid), true, true);
			$news = array();
			$news[] = array(
				'title' => $store['title'] . "-点击进入排号",
				'description' => $store['content'],
				'picurl' => $store['thumb'],
				'url' => $url
			);
			return $this->respNews($news);
		} elseif($row['type'] == 1) {
			//商家二维码
			$url = murl('entry', array('m' => 'str_takeout', 'do' => 'dish', 'sid' => $sid), true, true);
			$news = array();
			$news[] = array(
				'title' => $store['title'],
				'description' => $store['content'],
				'picurl' => $store['thumb'],
				'url' => $url
			);
			return $this->respNews($news);
		}
	}
}
