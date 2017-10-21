<?php
/**
 * 自动分组模块
 *
 * @author 微赞
 * @url http://www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Fm_autogroupModule extends WeModule {
	
	public function fieldsFormDisplay($rid = 0) {
		load()->func('tpl');
		global $_W;

		//$setting = $_W['account']['modules'][$this->_saveing_params['mid']]['config'];
		if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('fm_autogroup_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
		include $this->template('form');

	}
	public function fieldsFormValidate($rid = 0) {
		
		global $_GPC;
		//此处服务端验证表单数据的完整性，直接返回错误信息。
		if (empty($_GPC['title'])) {
			return '请填写标题';
		}
		return '';
	}


	public function fieldsFormSubmit($rid = 0) {

		global $_GPC, $_W;

		$id = intval($_GPC['reply_id']);

        $insert = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'content' => $_GPC['content'],
            'description' => $_GPC['description'],
            'createtime' => TIMESTAMP,
            'isshow' => 1,
        );
		if (!empty($_GPC['logo'])) {
            $insert['logo'] = $_GPC['logo'];
        }
        if (empty($id)) {
            $id = pdo_insert('fm_autogroup_reply', $insert);
        } else {
            pdo_update('fm_autogroup_reply', $insert, array('id' => $id));
        }

		return true;

	}

		
	public function dodelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }


        message('规则操作成功！', create_url('site/module/index', array('name' => 'fm_autogroup')), 'success');
    }


}
