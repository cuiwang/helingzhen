<?php
defined('IN_IA') or exit('Access Denied');
require_once '../framework/library/qrcode/phpqrcode.php';
define('ROOT_PATH', str_replace('module.php', '', str_replace('\\', '/', __FILE__)));


class haoman_qjbModule extends WeModule {

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        load()->func('tpl');
		$creditnames = array();
		$unisettings = uni_setting($uniacid, array('creditnames'));
		foreach ($unisettings['creditnames'] as $key=>$credit) {
			if (!empty($credit['enabled'])) {
				$creditnames[$key] = $credit['title'];
			}
		}
        
    }


    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
       


    }

    public function ruleDeleted($rid) {
       
    }
	public function settingsDisplay($setting) {

	}
}
