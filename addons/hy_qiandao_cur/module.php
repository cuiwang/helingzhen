<?php

/**
 * 签到送话费模块定义
 *
 * @author nfree
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Hy_qiandao_curModule extends WeModule {  
	public function settingsDisplay($settings) {
		global $_W, $_GPC;   
		$reword_order = iunserializer($settings['reword_order']);
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			$ro =  array();
			foreach ($_GPC['reword_order']['day'] as $key => $val)
			{
			    $ro[$key]['day']  = $val   ;
				$ro[$key]['amount']  = $_GPC['reword_order']['amount'][$key]  ;
			}

			$cfg = array(
				'ap_id' => $_GPC['ap_id'],
				'accnum' => $_GPC['accnum'],				
				'ruletxt' => $_GPC['ruletxt'],	
				'signtype' => $_GPC['signtype'],
				'follow' => $_GPC['follow'],
				'shareqdzs' => $_GPC['shareqdzs'],
				'sharetype' => $_GPC['sharetype'],
				'gonggao' => $_GPC['gonggao'],
				'more' => $_GPC['more'],					
				'key' => $_GPC['key'],
                'dcqdzs' => $_GPC['dcqdzs'],
                'scqdzs' => $_GPC['scqdzs'],
                'reword_order' => iserializer($ro)
            );
			//字段验证, 并获得正确的数据$cfg
			$this->saveSettings($cfg);
			message('保存成功', 'refresh');
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}
}