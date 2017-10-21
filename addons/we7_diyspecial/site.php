<?php
/**
 * 专题页面模块微站定义
 *
 * @author 微擎团队
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class We7_diyspecialModuleSite extends WeModuleSite {

	public function __construct() {
		load()->model('site');
	}

	public function doWebIndex() {
		global $_W, $_GPC;
			$_W['page']['title'] = '专题页面';
			$page = max(1, intval($_GPC['page']));
			$pagesize = 20;
			$list = pdo_fetchall("SELECT * FROM ".tablename('site_page')." WHERE type = '1' AND uniacid = :uniacid LIMIT ".(($page-1) * $pagesize).','.$pagesize, array(':uniacid' => $_W['uniacid']));
			if (!empty($list)) {
				foreach ($list as &$row) {
					$row['params'] = json_decode($row['params'], true);
				}
				unset($row);
			}
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('site_page')." WHERE type = '1' AND uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
			$pager = pagination($total, $page, $pagesize);
			include $this->template('display');
	}

	public function doWebPost() {
		global $_W, $_GPC;
		$_W['page']['title'] = '专题页面';
		$multiid = intval($_GPC['multiid']);
		$id = intval($_GPC['id']);

		if (!empty($_GPC['wapeditor'])) {
			$params = $_GPC['wapeditor']['params'];
			if (empty($params)) {
				message('请您先设计手机端页面.', referer(), 'error');
			}
			$params = json_decode(ihtml_entity_decode($params), true);
			if (empty($params)) {
				message('请您先设计手机端页面.', referer(), 'error');
			}
			$page = $params[0]['property'][0];
			$html = htmlspecialchars_decode($_GPC['wapeditor']['html'], ENT_QUOTES);
			$html = str_replace(array('<?', '<%', '<?php', '{php'), '_', $html);
			$html = preg_replace('/<\s*?script.*(src|language)+/i', '_', $html);
			$multipage = htmlspecialchars_decode($_GPC['wapeditor']['multipage'], ENT_QUOTES);
			$data = array(
				'uniacid' => $_W['uniacid'],
				'multiid' => '0',
				'title' => $page['params']['title'],
				'description' => $page['params']['description'],
				'type' => 1,
				'status' => 1,
				'params' => json_encode($params),
				'html' => $html,
				'multipage' => $multipage,
				'createtime' => TIMESTAMP,
			);
			if (empty($id)) {
				pdo_insert('site_page', $data);
				$id = pdo_insertid();
			} else {
				pdo_update('site_page', $data, array('id' => $id));
			}
			if (!empty($page['params']['keyword'])) {
				$cover = array(
					'uniacid' => $_W['uniacid'],
					'title' => $page['params']['title'],
					'keyword' => $page['params']['keyword'],
					'url' => murl('home/page', array('id' => $id), true, false),
					'description' => $page['params']['description'],
					'thumb' => $page['params']['thumb'],
					'module' => 'page',
					'multiid' => $id,
				);
				site_cover($cover);
			}
			message('页面保存成功.', referer(), 'success');
		} else {
			$page = pdo_fetch("SELECT * FROM ".tablename('site_page')." WHERE id = :id", array(':id' => $id));
			$page['multipage'] = preg_replace('/<(\/)?script(.+)?>/U', '&lt;$1script$2&gt;', $page['multipage']);
			$page['multipage'] = preg_replace('/background\-image\:(\s)*url\(\"(.*)\"\)/U', 'background-image: url($2)', $page['multipage']);
			include $this->template('post');
		}
	}

	public function doWebDel() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$exist = pdo_get('site_page', array('id' => $id, 'uniacid' => $_W['uniacid'], 'type' => 1));
		if ($exist) {
			pdo_delete('site_page', array('id' => $id, 'uniacid' => $_W['uniacid'], 'type' => 1));
			site_cover_delete($id);
			message('删除微页面成功', referer(), 'success');
		} else {
			message('微页面不存在或已经删除！', referer(), 'error');
		}
	}
}