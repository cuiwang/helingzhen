<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

class NewsModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('news_reply') . " WHERE rid = :id AND parent_id = -1 ORDER BY displayorder DESC, id ASC LIMIT 8";
		$commends = pdo_fetchall($sql, array(':id' => $rid));
		if (empty($commends)) {
						$sql = "SELECT * FROM " . tablename('news_reply') . " WHERE rid = :id AND parent_id = 0 ORDER BY RAND()";
			$main = pdo_fetch($sql, array(':id' => $rid));
			if(empty($main['id'])) {
				return false;
			}
			$sql = "SELECT * FROM " . tablename('news_reply') . " WHERE id = :id OR parent_id = :parent_id ORDER BY parent_id ASC, displayorder DESC, id ASC LIMIT 8";
			$commends = pdo_fetchall($sql, array(':id'=>$main['id'], ':parent_id'=>$main['id']));
		}
		if(empty($commends)) {
			return false;
		}
		$news = array();
		foreach($commends as $c) {
			$row = array();
			$row['title'] = $c['title'];
			$row['description'] = $c['description'];
			!empty($c['thumb']) && $row['picurl'] = tomedia($c['thumb']);
			$row['url'] = empty($c['url']) ? $this->createMobileUrl('detail', array('id' => $c['id'])) : $c['url'];
			$news[] = $row;
		}
		return $this->respNews($news);
	}
}
