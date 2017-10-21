<?php
/**
 * 验证码红包模块定义
 *
 * @author 牛牛
 * @url #
 */
defined('IN_IA') or exit('Access Denied');

class Aki_yzmjfModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		//load()->func('logging');
		//logging_run('嵌入一个规则111111111','','rule1');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		//load()->func('logging');
		//logging_run('嵌入一个规则22222222222','','rule1');
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		$uniacid = $_W['uniacid'];
		if (empty($uniacid)) {
			exit();
		}
		$indata = array(
			'uniacid' =>$uniacid,
			'rid' =>$rid,
			'time' =>time('Ymd')
			);
		$sql = 'select id from '.tablename('aki_yzmjf').' where rid =:rid';
		$ds = intval(pdo_fetchcolumn($sql,array(':rid' =>$rid)));
		if (empty($id)) {
			pdo_insert('aki_yzmjf',$indata);
		}else{
			pdo_update('aki_yzmjf', $insert, array(
                'id' => $id
            ));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
			//load()->func('logging');
		//logging_run('嵌入一个规则','','rule4');
	}


}