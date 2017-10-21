<?php
/**
 * community模块定义
 *
 * @author 
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Xfeng_communityModule extends WeModule {
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
        global $_W,$_GPC;
        if(checksubmit('submit')) {
            ////字段验证, 并获得正确的数据$dat
            $dat = array(
                'thumb' => tomedia($_GPC['thumb']),
                'url' => $_GPC['url'],
                'service_thumb'  => tomedia($_GPC['service_thumb']),
                'xq_pay' => $settings['xq_pay'],
                'xq_alipay' => $settings['xq_alipay'],
                'xq_wechat' => $settings['xq_wechat'],
                'xq_wechat_sub' => $settings['xq_wechat_sub'],
                'leftstext' => $_GPC['leftstext'],
                'leftsthumb' => tomedia($_GPC['leftsthumb']),
                'leftsurl' => $_GPC['leftsurl'],
                'leftxtext' => $_GPC['leftxtext'],
                'leftxthumb' => tomedia($_GPC['leftxthumb']),
                'leftxurl' => $_GPC['leftxurl'],
                'rightstext' => $_GPC['rightstext'],
                'rightsthumb' => tomedia($_GPC['rightsthumb']),
                'rightsurl' => $_GPC['rightsurl'],
                'rightxtext' => $_GPC['rightxtext'],
                'rightxthumb' => tomedia($_GPC['rightxthumb']),
                'rightxurl' => $_GPC['rightxurl'],
                'copyright' => $_GPC['copyright'],
                'copyrighturl' => $_GPC['copyrighturl']

            );
            $this->saveSettings($dat);
            message('提交成功',referer(),'success');
        }
        load()->func('tpl');
        include $this->template('setting');
	
	}

}