<?php
/**
 * 单品代销模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class Tc_consignmentModule extends WeModule {
	private $tablename = "tc_singleproduct_goods";
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		load()->func('tpl');
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}		
		if (!$reply) {
			$now = time();
			$reply = array("status" => 1);
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);	
		$insert = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'gname' => htmlspecialchars($_GPC['gname']),
			'shopname' => htmlspecialchars($_GPC['shopname']),
			'type' => htmlspecialchars($_GPC['gtype']),
			'desciption' => $_GPC['desciption'],			
			'price' => floatval($_GPC['price']),
			'count' => intval($_GPC['count']),
			'gstatus' => $_GPC['gstatus'] == "on" ? 1 : 0 ,
			'pic' => $_GPC['pic'],
			'sharepic' => $_GPC['sharepic'],
			'sharetitle' => $_GPC['sharetitle'],
			'sharedesc' => $_GPC['sharedesc'],
		);

		if (empty($id)) {
			$insert['createtime'] = time();
			$id = pdo_insert($this->tablename, $insert);
		} else {
			pdo_update($this->tablename, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete($this->tablename, array('rid' => $rid));
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$this->saveSettings($dat);
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}