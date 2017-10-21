<?php

/**
 * 大转盘模块
 */
defined('IN_IA') or exit('Access Denied');

class wdl_bigwheelModule extends WeModule {

	public $tablename = 'bigwheel_reply';

	public function fieldsFormDisplay($rid = 0) {
		global $_W;

		load()->func('tpl');
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}
		if (!$reply) {
			$now = time();
			$reply = array(
				"title" => "幸运大转盘活动开始了!",
				"start_picurl" => "../addons/wdl_bigwheel/template/style/activity-lottery-start.jpg",
				"description" => "欢迎参加幸运大转盘活动",
				"repeat_lottery_reply" => "亲，继续努力哦~~",
				"ticket_information" => "兑奖请联系我们,电话: 13888888888",
				"starttime" => $now,
				"endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
				"end_theme" => "幸运大转盘活动已经结束了",
				"end_instruction" => "亲，活动已经结束，请继续关注我们的后续活动哦~",
				"end_picurl" => "../addons/wdl_bigwheel/template/style/activity-lottery-end.jpg",
				"most_num_times" => 1,
				"number_times" => 1,
				"probability" => 0,
				"award_times" => 1,
				"sn_code" => 1,
				"sn_rename" => "SN码",
				"tel_rename" => "手机号",
				"show_num" => 1,
				"share_title" => "欢迎参加大转盘活动",
				"share_desc" => "亲，欢迎参加大转盘抽奖活动，祝您好运哦！！ 亲，需要绑定账号才可以参加哦",
				"share_txt" => "&lt;p&gt;1. 关注微信公众账号\"()\"&lt;/p&gt;&lt;p&gt;2. 发送消息\"大转盘\", 点击返回的消息即可参加&lt;/p&gt;",
			);
		}

		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);

		$insert = array(
			'rid' => $rid,
			'weid' => $_W['weid'],
			'title' => $_GPC['title'],
			'content' => $_GPC['content'],
			'ticket_information' => $_GPC['ticket_information'],
			'description' => $_GPC['description'],
			'repeat_lottery_reply' => $_GPC['repeat_lottery_reply'],
			'start_picurl' => $_GPC['start_picurl'],
			'end_theme' => $_GPC['end_theme'],
			'end_instruction' => $_GPC['end_instruction'],
			'end_picurl' => $_GPC['end_picurl'],
			'probability' => $_GPC['probability'],
			'c_type_one' => $_GPC['c_type_one'],
			'c_name_one' => $_GPC['c_name_one'],
			'c_num_one' => $_GPC['c_num_one'],
			'c_type_two' => $_GPC['c_type_two'],
			'c_name_two' => $_GPC['c_name_two'],
			'c_num_two' => $_GPC['c_num_two'],
			'c_type_three' => $_GPC['c_type_three'],
			'c_name_three' => $_GPC['c_name_three'],
			'c_num_three' => $_GPC['c_num_three'],
			'c_type_four' => $_GPC['c_type_four'],
			'c_name_four' => $_GPC['c_name_four'],
			'c_num_four' => $_GPC['c_num_four'],
			'c_type_five' => $_GPC['c_type_five'],
			'c_name_five' => $_GPC['c_name_five'],
			'c_num_five' => $_GPC['c_num_five'],
			'c_type_six' => $_GPC['c_type_six'],
			'c_name_six' => $_GPC['c_name_six'],
			'c_num_six' => $_GPC['c_num_six'],
			'award_times' => $_GPC['award_times'],
			'number_times' => $_GPC['number_times'],
			'most_num_times' => $_GPC['most_num_times'],
			'sn_code' => $_GPC['sn_code'],
			'sn_rename' => $_GPC['sn_rename'],
			'tel_rename' => $_GPC['tel_rename'],
			'show_num' => $_GPC['show_num'],
			'createtime' => time(),
			'copyright' => $_GPC['copyright'],
			'share_title' => $_GPC['share_title'],
			'share_desc' => $_GPC['share_desc'],
			'share_url' => $_GPC['share_url'],
			'share_txt' => $_GPC['share_txt'],
			'starttime' => strtotime($_GPC['datelimit']['start']),
			'endtime' => strtotime($_GPC['datelimit']['end']),
			'c_rate_one' => $_GPC['c_rate_one'],
			'c_rate_two' => $_GPC['c_rate_two'],
			'c_rate_three' => $_GPC['c_rate_three'],
			'c_rate_four' => $_GPC['c_rate_four'],
			'c_rate_five' => $_GPC['c_rate_five'],
			'c_rate_six' => $_GPC['c_rate_six'],
		);
		$insert['total_num'] = intval($_GPC['c_num_one']) + intval($_GPC['c_num_two']) + intval($_GPC['c_num_three']) + intval($_GPC['c_num_four']) + intval($_GPC['c_num_five']) + intval($_GPC['c_num_six']);
		if (empty($id)) {
			if ($insert['starttime'] <= time()) {
				$insert['isshow'] = 1;
			} else {
				$insert['isshow'] = 0;
			}
			$id = pdo_insert($this->tablename, $insert);
		} else {
			pdo_update($this->tablename, $insert, array('id' => $id));
		}
		return true;
	}

	public function ruleDeleted($rid) {
		pdo_delete('bigwheel_award', array('rid' => $rid));
		pdo_delete('bigwheel_reply', array('rid' => $rid));
		pdo_delete('bigwheel_fans', array('rid' => $rid));
	}

}
