<?php
defined('IN_IA') or exit('Access Denied');

class XC_ArticleModule extends WeModule
{
	public function fieldsFormDisplay($rid = 0)
	{
		global $_W, $_GPC;
		$isfill = false;
		if (!empty($rid)) {
			$reply = pdo_fetchall("SELECT * FROM " . tablename('xc_article_article_reply') . " WHERE rid = :rid AND articleid > 0", array(':rid' => $rid));
			if (!empty($reply)) {
				foreach ($reply as $row) {
					$ids[$row['articleid']] = $row['articleid'];
				}
				$article = pdo_fetchall("SELECT id, title, thumb, content FROM " . tablename('xc_article_article') . " WHERE id IN (" . implode(',', $ids) . ")", array(), 'id');
				$isfill = $reply[0]['isfill'];
			} else {
				$isfill = pdo_fetchcolumn("SELECT isfill FROM " . tablename('xc_article_article_reply') . " WHERE rid = :rid", array(':rid' => $rid));
			}
		}
		include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0)
	{
		return '';
	}

	public function fieldsFormSubmit($rid)
	{
		global $_W, $_GPC;
		if (!empty($_GPC['ids'])) {
			foreach ($_GPC['ids'] as $id) {
				$isexists = pdo_fetchcolumn("SELECT id FROM " . tablename('xc_article_article_reply') . " WHERE rid = :rid AND articleid = :articleid", array(':articleid' => $id, ':rid' => $rid));
				if (!$isexists) {
					pdo_insert('xc_article_article_reply', array('rid' => $rid, 'articleid' => $id, 'isfill' => 0,));
				}
			}
		}
		if (isset($_GPC['isfill'])) {
			$isexists = pdo_fetchcolumn("SELECT id FROM " . tablename('xc_article_article_reply') . " WHERE rid = :rid AND articleid = '0'", array(':rid' => $rid));
			if (empty($isexists)) {
				pdo_insert('xc_article_article_reply', array('rid' => $rid, 'articleid' => 0, 'isfill' => intval($_GPC['isfill']),));
			} else {
				pdo_update('xc_article_article_reply', array('isfill' => intval($_GPC['isfill']),), array('articleid' => 0, 'rid' => $rid));
			}
		}
	}

	public function ruleDeleted($rid)
	{
	}

	public function doDelete()
	{
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$rid = intval($_GPC['rid']);
		if (!empty($id) && !empty($rid)) {
			pdo_delete('xc_article_article_reply', array('id' => $id, 'rid' => $rid));
		}
		message('删除成功！', referer(), 'success');
	}

	public function settingsDisplay($settings)
	{
		global $_GPC, $_W;
		if (checksubmit()) {
			$site_interval = intval($_GPC['site_day']) * 24 * 60 * 60 + intval($_GPC['site_hour']) * 60 * 60 + intval($_GPC['site_min']) * 60;
			$article_interval = intval($_GPC['article_day']) * 24 * 60 * 60 + intval($_GPC['article_hour']) * 60 * 60 + intval($_GPC['article_min']) * 60;
			$cfg = array('duoshuo_short_name' => $_GPC['duoshuo_short_name'], 'enable_comment' => $_GPC['enable_comment'], 'key' => $_GPC['key'], 'secret' => $_GPC['secret'], 'prohibit_site_click_interval' => $site_interval, 'prohibit_article_click_interval' => $article_interval, 'enable_link' => intval($_GPC['enable_link']),);
			if ($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		$site_day = intval($settings['prohibit_site_click_interval'] / (24 * 60 * 60));
		$site_hour = intval(($settings['prohibit_site_click_interval'] - $site_day * (24 * 60 * 60)) / (60 * 60));
		$site_min = intval(($settings['prohibit_site_click_interval'] - $site_day * (24 * 60 * 60) - $site_hour * (60 * 60)) / 60);
		$article_day = intval($settings['prohibit_article_click_interval'] / (24 * 60 * 60));
		$article_hour = intval(($settings['prohibit_article_click_interval'] - $article_day * (24 * 60 * 60)) / (60 * 60));
		$article_min = intval(($settings['prohibit_article_click_interval'] - $article_day * (24 * 60 * 60) - $article_hour * (60 * 60)) / 60);
		include $this->template('setting');
	}
}