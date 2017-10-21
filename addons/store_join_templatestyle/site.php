<?php
/**
 * 招商加盟模块微站定义
 */
defined('IN_IA') or exit('Access Denied');
defined('IN_IA') or exit('Access Denied');
define('MODULE_NAME', 'store_join_templatestyle');
define('MODULE_ROOT', IA_ROOT . '/addons/' . MODULE_NAME . '/');
define('RESOURCE_URL', '../addons/' . MODULE_NAME . '/template/mobile/');
define('CSS_URL', RESOURCE_URL . 'css/');
define('JS_URL', RESOURCE_URL . 'js/');
define('IMAGES_URL', RESOURCE_URL . 'images/');
require MODULE_ROOT . 'global.php';

class Store_join_templatestyleModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W, $_GPC;
		$weid = $_W['uniacid'];
		$openid = $_SESSION['openid'];
		//		echo $openid;
		$items = pdo_fetch("SELECT * FROM " . my_tablename('setting') . " WHERE weid=" . $weid);
		include $this -> template('index');
	}

	/*
	 *
	 *留言
	 * */
	public function doMobileMessage() {
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		$id = intval($_GPC['id']);
		
		if (!empty($_GPC['zname']) && !empty($_GPC['zphone']) && !empty($_GPC['zmessage']) && $_GPC['zmessage']!='请在此处输入您的留言 …') {
			$leaving = array('cardsid' => $id, 'openid' => $_GPC['open'], 'title' => $_GPC['title'],'zname' => $_GPC['zname'], 'zphone' => $_GPC['zphone'], 'zmessage' => $_GPC['zmessage'], 'uniacid' => $uniacid, );
			$leaving['zcreate_time'] = TIMESTAMP;
			pdo_insert(my_tablename('leaving', 0), $leaving);
			$this -> templateMessageReturn($_GPC['zname'], $_GPC['zphone'], $_GPC['zmessage']);
			echo 1;
		} else {
			echo 2;
		}

	}

	/*
	 * 消息模板消息
	 */

	public function templateMessageReturn($zname, $zphone, $zmessage) {
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		$items = pdo_fetch("SELECT * FROM " . my_tablename('template_message') . " WHERE uniacid=" . $uniacid);
		$access_token = WeAccount::token();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
		$data = array('touser' => $items['openid'], 'template_id' => $items['template_id'], 'url' => "", 'data' => array('first' => array('value' => urlencode("有朋友在您的名片上留言"), 'color' => "#743A3A", ), 'keyword1' => array('value' => urlencode($zname), 'color' => "#FF0000", ), 'keyword2' => array('value' => urlencode("留言处理" . " | 手机：" . $zphone), 'color' => "#FF0000", ), 'keyword3' => array('value' => urlencode($zmessage), 'color' => "#0000FF", ), 'keyword4' => array('value' => urlencode(date('Y-m-d H:i:s', TIMESTAMP)), 'color' => "#0000FF", ), 'remark' => array('value' => urlencode("请及时和您朋友交流！"), 'color' => "#008000", ), ));
		$response = ihttp_request($url, urldecode(json_encode($data)));

	}

	//基本设置
	public function dowebsetting() {
		global $_W, $_GPC;
		$weid = $_W['uniacid'];
		$items = pdo_fetch("SELECT * FROM " . my_tablename('setting') . " WHERE weid=" . $weid);
                pdo_fetch("ALTER TABLE ims_store_join_templatestyle_setting  modify column nav_url varchar(255) NOT NULL DEFAULT '' ");
		pdo_fetch("ALTER TABLE ims_store_join_templatestyle_setting  modify column copyright varchar(255) NOT NULL DEFAULT '' ");
		if (checksubmit()) {
			$data = array('weid' => $weid, 'uniacid' => $_W['uniacid'], 'background_images' => $_GPC['background_images'], 'logo' => $_GPC['logo'], 'button_color' => $_GPC['button_color'], 'link_color' => $_GPC['link_color'],'link_color1' => $_GPC['link_color1'], 'nav_size' => $_GPC['nav_size'], 'nav_icon' => $_GPC['nav_icon'], 'nav_link' => $_GPC['nav_link'], 'nav_url' => $_GPC['nav_url'], 'tel_icon' => $_GPC['tel_icon'], 'tel_link' => $_GPC['tel_link'], 'tel_url' => $_GPC['tel_url'], 'copyright' => $_GPC['copyright'], 'context' => $_GPC['context'], 'title1' => $_GPC['title1'], 'title2' => $_GPC['title2'], 'top_title' => $_GPC['top_title'], 'reply_color' => $_GPC['reply_color'], 'share_context' => $_GPC['share_context'], 'share_title' => $_GPC['share_title'], 'share_logo' => $_GPC['share_logo'], 'updatetime' => TIMESTAMP, );		
				if (empty($items['id'])) {
					$data['createtime'] = time();
					pdo_insert(my_tablename('setting', 0), $data);
				} else {
					pdo_update(my_tablename('setting', 0), $data, array('weid' => $weid));
				}
				message("设置成功!", $this -> createWebUrl('setting'), 'success');
			} 

		include $this -> template('setting');
	}

	//模板消息
	public function dowebtemplist() {
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		$items = pdo_fetch("SELECT * FROM " . my_tablename('template_message') . " WHERE uniacid=" . $uniacid);
		if (checksubmit()) {
			$data = array('uniacid' => $_W['uniacid'], 'openid' => $_GPC['openid'], 'template_id' => $_GPC['template_id'], 'replyid' => $_GPC['replyid'], 'notice' => $_GPC['notice'], 'zcreate_time' => TIMESTAMP, );
			if (empty($items['id'])) {
				$data['zcreate_time'] = time();
				pdo_insert(my_tablename('template_message', 0), $data);
			} else {
				pdo_update(my_tablename('template_message', 0), $data, array('uniacid' => $uniacid));
			}
			message("设置成功!", $this -> createWebUrl('templist'), 'success');
		}

		include $this -> template('templist');
	}

	//留言列表
	public function dowebmessage() {
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		$items = pdo_fetchall("SELECT a.*,b.order FROM " . my_tablename('leaving') . " a left join " . my_tablename('template_notice') . "b on a.id=b.sid WHERE a.uniacid=" . $uniacid);

		include $this -> template('message');
	}

	//商家回复
	public function doweboperation() {
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		$sid = intval($_GPC['sid']);
		$items = pdo_fetch("SELECT * FROM " . my_tablename('template_notice') . " WHERE sid=" . $sid);
		$openid = $_GPC['openid'];
		if (checksubmit('submit')) {
			$data = array('uniacid' => $_W['uniacid'], 'sid' => $sid, 'name' => $_GPC['name'], 'type' => $_GPC['type'], 'content' => $_GPC['content'], 'order' => 1, 'create_time' => TIMESTAMP, );
			$data['create_time'] = time();
			if (empty($items['sid'])) {
				pdo_insert(my_tablename('template_notice', 0), $data);
			} else {
				pdo_update(my_tablename('template_notice', 0), $data, array('sid' => $sid));
			}
			$this -> templateNotice($_GPC['name'], $_GPC['type'], $_GPC['content'],$_GPC['zmessage'], $_GPC['openid']);
			message("设置成功!", $this -> createWebUrl('message'), 'success');
		}
		include $this -> template('notice');
	}

	/*
	 * 消息回复提醒
	 * 易/福/源/码/网
	 * */
	public function templateNotice($name, $type, $content,$zmessage,$openid) {
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		$items = pdo_fetch("SELECT * FROM " . my_tablename('template_message') . " WHERE uniacid=" . $uniacid);
		$item = pdo_fetch("SELECT * FROM " . my_tablename('setting') . " WHERE uniacid=" . $uniacid);
		$access_token = WeAccount::token();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
		$data = array('touser' => $openid, 'template_id' => $items['replyid'], 'url' => "", 'data' => array('first' => array('value' => urlencode("您的反馈与投诉已由客服处理"), 'color' => "#743A3A", ), 'keyword1' => array('value' => urlencode(date('Y-m-d H:i:s', TIMESTAMP)), 'color' => "#0000FF", ), 'keyword2' => array('value' => urlencode($zmessage), 'color' => "#FF0000", ), 'keyword3' => array('value' => urlencode($content), 'color' => "#0000FF", ), 'remark' => array('value' => urlencode($item['reply_color']), 'color' => "#008000", ), ));
		$response = ihttp_request($url, urldecode(json_encode($data)));
	}

}
