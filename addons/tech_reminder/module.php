<?php
/**
 * 梦昂--关注提示模块定义
 *
 * @author 梦昂科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Tech_reminderModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W,$_GPC;
		if (!empty($rid)) {
            $item = pdo_fetch("SELECT * FROM ".tablename('tech_reminder_form')." WHERE rid = :rid", array(':rid' => $rid));
            $titles = unserialize($item['stitle']);
			$thumbs = unserialize($item['sthumb']);
			$sdesc = unserialize($item['sdesc']);
			$surl = unserialize($item['surl']);
			foreach ($titles as $key => $value) {
				if (empty($value)) continue;
				$slist[] = array('stitle'=>$value,'sdesc'=>$sdesc[$key],'sthumb'=>$thumbs[$key],'surl'=>$surl[$key]);
			}
        }
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_W,$_GPC;
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		$data['rid'] = $rid;
		$data['wtitle'] = $_GPC['wtitle'];
		$data['wenabled'] = $_GPC['wenabled'];
		$data['stitle'] = serialize($_GPC['stitle']);
		$data['sthumb'] = serialize($_GPC['sthumb']);
		$data['sdesc'] = serialize($_GPC['sdesc']);
		$data['surl'] = serialize($_GPC['surl']);
		$data['senabled'] = $_GPC['senabled'];
		$data['number'] = intval($_GPC['number']);
		$id = pdo_fetchcolumn("SELECT id FROM ".tablename('tech_reminder_form')." WHERE rid = :rid", array(':rid' => $rid));
		if (empty($id)) {
            pdo_insert('tech_reminder_form', $data);
        } else {
            pdo_update('tech_reminder_form', $data, array('id' => $id));
        }
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W,$_GPC;
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