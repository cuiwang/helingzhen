<?php
defined('IN_IA') or exit('Access Denied');
include_once IA_ROOT . '/addons/xc_article/define.php';

class XC_ArticleModuleProcessor extends WeModuleProcessor
{
	public function respond()
	{
		global $_W;
		$content = $this->message['content'];
		$from_user = $this->message['from'];
		$isfill = pdo_fetchcolumn("SELECT isfill FROM " . tablename('xc_article_article_reply') . " WHERE rid =:rid AND articleid = '0'", array(':rid' => $this->rule));
		$reply = pdo_fetchall("SELECT * FROM " . tablename('xc_article_article_reply') . " WHERE rid = :rid", array(':rid' => $this->rule));
		if (!empty($reply)) {
			foreach ($reply as $row) {
				$ids[$row['articleid']] = $row['articleid'];
			}
			$article = pdo_fetchall("SELECT id, title, thumb, content, description, linkurl FROM " . tablename('xc_article_article') . " WHERE id IN (" . implode(',', $ids) . ")", array(), 'id');
		}
		if ($isfill && ($count = 8 - count($reply)) > 0) {
			$articlefill = pdo_fetchall("SELECT id, title, thumb, content, description, linkurl FROM " . tablename('xc_article_article') . " WHERE weid = '{$_W['weid']}' AND id NOT IN (" . implode(',', $ids) . ") ORDER BY id DESC LIMIT $count", array(), 'id');
			if (!empty($articlefill)) {
				foreach ($articlefill as $row) {
					$article[$row['id']] = $row;
					$reply[]['articleid'] = $row['id'];
				}
				unset($articlefill);
			}
		}
		if (!empty($reply)) {
			$response = array();
			foreach ($reply as $row) {
				$row = $article[$row['articleid']];
				if (!empty($row)) {
					$response[] = array('title' => htmlspecialchars_decode($row['title']), 'description' => htmlspecialchars_decode($row['description']), 'picurl' => $row['thumb'], 'url' => $this->buildSiteUrl($this->createMobileUrl('detail', array('id' => $row['id'], 'shareby' => $from_user, 'track_type' => 'click', 'linkurl' => $row['linkurl']))),);
				}
			}
		}
		return $this->respNews($response);
	}
}