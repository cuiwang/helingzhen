<?php
/**
 * 最牛积分商城模块定义
 *
 * @author lonaking
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Lonaking_gift_shopModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$data = $_GPC['data'];
		// 点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		// 在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if (checksubmit()) {
			load()->func('file');
			$appid=$data['appid'];
			$apiclient_cert=$data['apiclient_cert'];
			$apiclient_key=$data['apiclient_key'];
			$rootca=$data['rootca'];

			file_write("./certs/index.html", "");
			file_write("./certs/".$appid."apiclient_cert.pem", $apiclient_cert);
			file_write("./certs/".$appid."apiclient_key.pem", $apiclient_key);
			file_write("./certs/".$appid."rootca.pem", $rootca);

			// 字段验证, 并获得正确的数据$dat
			empty($data['title']) && message("标题不能为空");
			$flag = $this->saveSettings($data);
			if ($flag) {
				message("信息保存成功", "", "success");
			} else {
				message("信息保存失败", "", "error");
			}
		}
		load()->func('tpl');
		include $this->template('setting');
	}

}