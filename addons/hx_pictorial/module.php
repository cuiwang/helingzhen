<?php
/**
 * 微画报模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_pictorialModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		if (!empty($rid)) {
			$reply = pdo_fetchall("SELECT * FROM ".tablename('hx_pictorial_reply')." WHERE rid = :rid", array(':rid' => $rid));
			if (!empty($reply)) {
				foreach ($reply as $row) {
					$huabaoids[$row['huabaoid']] = $row['huabaoid'];
				}
				$huabao = pdo_fetchall("SELECT id, title, thumb, content FROM ".tablename('hx_pictorial')." WHERE id IN (".implode(',', $huabaoids).")", array(), 'id');
			}
		}
		include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W, $_GPC;
		if (!empty($_GPC['huabaoid'])) {
			foreach ($_GPC['huabaoid'] as $aid) {
				pdo_insert('hx_pictorial_reply', array(
					'rid' => $rid,
					'huabaoid' => $aid,
				));
			}
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete('hx_pictorial_reply', array('rid' => $rid));
	}


}