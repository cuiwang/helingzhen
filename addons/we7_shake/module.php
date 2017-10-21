<?php



/**

 * 摇一摇中奖模块定义

 *

 * @author weizan Team

 * @url http://012wz.com

 */

defined('IN_IA') or exit('Access Denied');



class We7_shakeModule extends WeModule {



	public function fieldsFormDisplay($rid = 0) {

		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0

		global $_W;

		if (!empty($rid)) {

			$reply = pdo_fetch("SELECT * FROM " . tablename('shake_reply') . " WHERE rid = :rid", array(':rid' => $rid));

		} else {

			$reply = array(

				'countdown' => 10,

				'speed' => 3000,

				'speedandroid' => 8000,

				'interval' => 100,

				'maxshake' => 100,

				'maxwinner' => 10,

			);

		}

		include $this->template('form');

	}



	public function fieldsFormValidate($rid = 0) {

		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0

		return '';

	}



	public function fieldsFormSubmit($rid) {

		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号

		global $_W, $_GPC;

		$reid = intval($_GPC['reply_id']);



		$data = array(

			'rid' => $rid,

			'maxshake' => intval($_GPC['maxshake']),

			'maxwinner' => intval($_GPC['maxwinner']),

			'countdown' => intval($_GPC['countdown']),

			'qrcode' => $_GPC['qrcode'],

			'cover' => $_GPC['cover'],

			'background' => $_GPC['background'],

			'logo' => $_GPC['logo'],

			'description' => $_GPC['description'],

			'rule' => $_GPC['rule'],

			'speed' => intval($_GPC['speed']),

			'speedandroid' => intval($_GPC['speedandroid']),

			'interval' => intval($_GPC['interval']),

			'status' => intval($_GPC['shakestatus']),

			'maxjoin' => intval($_GPC['maxjoin']),

			'joinprobability' => intval($_GPC['joinprobability']),

		);

		if (empty($reid)) {

			pdo_insert('shake_reply', $data);

		} else {

			pdo_update('shake_reply', $data, array('id' => $reid));

		}

	}



	public function ruleDeleted($rid) {

		//删除规则时调用，这里 $rid 为对应的规则编号

		pdo_delete('shake_reply', array('rid' => $rid));

	}



}

