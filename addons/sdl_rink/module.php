<?php
//易 福 源 码 网
defined('IN_IA') or die('Access Denied');
class Sdl_rinkModule extends WeModule
{
	public $table_reply = 'sdl_rink_reply';
	public $table_record = 'sdl_rink_record';
	public $table_fans = 'sdl_rink_fans';
	public function fieldsFormDisplay($rid = 0)
	{
		global $_W;
		load()->func('tpl');
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}
		$time = date('Y-m-d H:i', TIMESTAMP + 3600 * 24);
		if (!$reply) {
			$now = TIMESTAMP;
			$reply = array("title" => "圣诞溜冰场", "ph_img" => MODULE_URL . "template/mobile/resource/assets/360/title.jpg", "picture" => MODULE_URL . "template/mobile/resource/assets/360/logo_03.png", "mpp" => MODULE_URL . "template/mobile/resource/assets/360/sing.mp3", "isnow" => 1, "rule_txt" => '<p style="line-height: 2em;"><span style="font-size: 18px;">1.打开游戏，点击屏幕开始游戏<br/></span></p>
							<p style="line-height: 2em;"><span style="font-size: 18px;">2.每点击一次屏幕获得1分，收集礼物+2分</span></p>
							<p style="line-height: 2em;"><span style="font-size: 18px;">3.排行榜记录最好成绩，可以不断挑战冲榜噢</span></p>
							<p style="line-height: 2em;"><span style="font-size: 18px;">4.活动结束，按照排行可获得奖励</span></p>
							<p style="line-height: 2em;"><span style="font-size: 18px;">5.奖励自定义</span></p>
							<p style="line-height: 2em;"><span style="font-size: 18px;">本活动解释权归活动主办方所有。</span></p>', "rule_img" => MODULE_URL . "template/mobile/resource/assets/360/rult_top.png", "starttime" => $now, "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)), "share_title" => "圣诞溜冰场，好玩还有奖！", "share_desc" => "圣诞欢乐大比拼，不服来挑战！", "share_image" => MODULE_URL . "template/mobile/resource/assets/360/fx.jpg", "shared_title" => "【nickname】在圣诞溜冰场中得了mark分，快来玩吧！", "shared_desc" => "【nickname】在圣诞溜冰场中得了mark分，不服来挑战！", "shared_image" => MODULE_URL . "template/mobile/resource/assets/360/fx.jpg", "tw_title" => "圣诞溜冰场，欢乐大比拼！", "tw_desc" => "看谁礼物收集的多，有惊喜哦!！", 'addvivt' => 0, "tw_image" => MODULE_URL . "template/mobile/resource/assets/360/tw1.jpg", "follow_url" => "", "isneedfollow" => 0);
		}
		include $this->template('form');
	}
	public function fieldsFormValidate($rid = 0)
	{
		return '';
	}
	public function fieldsFormSubmit($rid)
	{
		global $_W, $_GPC;
		load()->func('file');
		$id = intval($_GPC['reply_id']);
		$reply = array("title" => "圣诞溜冰场", "ph_img" => MODULE_URL . "template/mobile/resource/assets/360/title.jpg", "picture" => MODULE_URL . "template/mobile/resource/assets/360/logo_03.png", "mpp" => MODULE_URL . "template/mobile/resource/assets/360/sing.mp3", "rule_txt" => '<p style="line-height: 2em;"><span style="font-size: 18px;">1.打开游戏，点击屏幕开始游戏<br/></span></p>
						<p style="line-height: 2em;"><span style="font-size: 18px;">2.每点击一次屏幕获得1分，收集礼物+2分</span></p>
						<p style="line-height: 2em;"><span style="font-size: 18px;">3.排行榜记录最好成绩，可以不断挑战冲榜噢</span></p>
						<p style="line-height: 2em;"><span style="font-size: 18px;">4.活动结束，按照排行可获得奖励</span></p>
						<p style="line-height: 2em;"><span style="font-size: 18px;">5.奖励自定义</span></p>
						<p style="line-height: 2em;"><span style="font-size: 18px;">本活动解释权归活动主办方所有。</span></p>', "rule_img" => MODULE_URL . "template/mobile/resource/assets/360/rult_top.png", "share_title" => "圣诞溜冰场，好玩还有奖！", "share_desc" => "圣诞欢乐大比拼，不服来挑战！", "share_image" => MODULE_URL . "template/mobile/resource/assets/360/fx.jpg", "shared_title" => "【nickname】在圣诞溜冰场中得了mark分，快来玩吧！", "shared_desc" => "【nickname】在圣诞溜冰场中得了mark分，不服来挑战！", "shared_image" => MODULE_URL . "template/mobile/resource/assets/360/fx.jpg", "tw_title" => "圣诞溜冰场，欢乐大比拼！", "tw_desc" => "看谁礼物收集的多，有惊喜哦!！", "tw_image" => MODULE_URL . "template/mobile/resource/assets/360/tw1.jpg", "follow_url" => "", "isneedfollow" => 0);
		$insert = array("rid" => $rid, "weid" => $_W['uniacid'], "starttime" => strtotime($_GPC['datelimit']['start']), "isnow" => $_GPC['isnow'], "endtime" => strtotime($_GPC['datelimit']['end']), "share_title" => $_GPC['share_title'], "addvivt" => $_GPC['addvivt'], "shared_title" => $_GPC['shared_title'], "shared_desc" => $_GPC['shared_desc'], "share_desc" => $_GPC['share_desc'], 'follow_url' => $_GPC['follow_url'], "rule_img" => $_GPC['rule_img'], "isneedfollow" => $_GPC['isneedfollow'], "tw_title" => $_GPC['tw_title'], "tw_desc" => $_GPC['tw_desc']);
		if (empty($_GPC['rule_txt'])) {
			$insert['rule_txt'] = $reply['rule_txt'];
		} else {
			$insert['rule_txt'] = $_POST['rule_txt'];
		}
		if (empty($_GPC['tw_image'])) {
			$insert['tw_image'] = $reply['tw_image'];
		} else {
			$insert['tw_image'] = $_GPC['tw_image'];
		}
		if (empty($_GPC['title'])) {
			$insert['title'] = $reply['title'];
		} else {
			$insert['title'] = $_GPC['title'];
		}
		if (empty($_GPC['ph_img'])) {
			$insert['ph_img'] = $reply['ph_img'];
		} else {
			$insert['ph_img'] = $_GPC['ph_img'];
		}
		if (empty($_GPC['mpp'])) {
			$insert['mpp'] = $reply['mpp'];
		} else {
			$insert['mpp'] = $_GPC['mpp'];
		}
		if (empty($_GPC['start_picurl'])) {
			$insert['picture'] = $reply['picture'];
		} else {
			$insert['picture'] = $_GPC['start_picurl'];
		}
		if (empty($_GPC['share_image'])) {
			$insert['share_image'] = $reply['share_image'];
		} else {
			$insert['share_image'] = $_GPC['share_image'];
		}
		if (empty($_GPC['shared_image'])) {
			$insert['shared_image'] = $reply['shared_image'];
		} else {
			$insert['shared_image'] = $_GPC['shared_image'];
		}
		if (empty($id)) {
			if ($insert['starttime'] <= time()) {
				$insert['status'] = 1;
			} else {
				$insert['status'] = 0;
			}
			pdo_insert($this->table_reply, $insert);
		} else {
			pdo_update($this->table_reply, $insert, array('id' => $id));
		}
	}
	public function ruleDeleted($rid)
	{
		pdo_delete($this->table_reply, array('rid' => $rid));
		pdo_delete($this->table_fans, array('rid' => $rid));
		pdo_delete($this->table_record, array('rid' => $rid));
		return true;
	}
	public function settingsDisplay($settings)
	{
		global $_W, $_GPC;
		if (checksubmit()) {
			$this->saveSettings($dat);
		}
		include $this->template('setting');
	}
}