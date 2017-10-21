<?php
/**
 * 我要上推荐模块定义
 *
 * @author 洛杉矶豪哥
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hao_recommendModule extends WeModule {
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
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			// $_GPC 可以用来获取 Cookies,表单中以及地址栏参数
			$dat = $_GPC['dat'];
			// message() 方法用于提示用户操作提示
			empty($dat['share_title']) && message('请填写分享标题');
			empty($dat['share_image']) && message('请填写分享图片');
			empty($dat['share_desc']) && message('请填写分享描述');
			$dat['int_desc'] = htmlspecialchars_decode($dat['int_desc']);
			//字段验证, 并获得正确的数据$dat
			if (!$this->saveSettings($dat)) {
				message('保存信息失败','','error');
			} else {
				$share['uniacid']=$_W['uniacid'];
				$share['share_title']=$dat['share_title'];
				$share['share_image']=$dat['share_image'];
				$share['share_desc']=$dat['share_desc'];
				$share['appid']=$_W['account']['key'];
				$share['appsecret']=$_W['account']['secret'];
				$share['win_mess']=$dat['win_mess'];

				$uniacid=$_W['uniacid'];
				$list = pdo_fetch("SELECT * FROM ".tablename('hao_fool_share')." WHERE uniacid = '{$uniacid}'");
				if (empty($list['uniacid'])) {
					$ret = pdo_insert('hao_fool_share', $share);
				}else{
					$ret = pdo_update('hao_fool_share', $share, array('uniacid'=>$_W['uniacid']));
				}
				message('保存信息成功','','success');
			}
		}
		
		// 模板中需要用到 "tpl" 表单控件函数的话, 记得一定要调用此方法.
		load()->func('tpl');
		
		//这里来展示设置项表单
		include $this->template('setting');
	}

}