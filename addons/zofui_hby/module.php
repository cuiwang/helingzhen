<?php
/**
 * 红包雨模块定义
 *
 * @author 众惠科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('HBY_ROOT', IA_ROOT . '/addons/zofui_hby');
class Zofui_hbyModule extends WeModule {
	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
		load()->model('account');
		$modules = uni_modules();
		if(checksubmit()) {
			load()->func('file');
            $r = mkdirs(HBY_ROOT . '/cert/'.$_W['uniacid']);
			if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(HBY_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(HBY_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['rootca'])) {
                $ret = file_put_contents(HBY_ROOT.'/cert/'.$_W['uniacid'].'/rootca.pem', trim($_GPC['rootca']));
                $r = $r && $ret;
            }			
			if(!$r) {
                message('证书保存失败, 请保证 /cert/ 目录可写');
            }
			
			
			$dat = array(
				'starttime'=>$_GPC['actitime']['start'],//开始时间
				'endtime'=>$_GPC['actitime']['end'],//结束时间
				'limitarea'=>trim($_GPC['limitarea'],','),//区域限制				
				'prizepro'=>trim($_GPC['prizepro']),//中奖概率
				'prizenum'=>trim($_GPC['prizenum']),//每位用户最多领取红包个数				
				'guanzhu'=>trim($_GPC['guanzhu']),//强制关注	
				'minfee'=>trim($_GPC['minfee']),//最小奖金
				'maxfee'=>trim($_GPC['maxfee']),//最大奖金
				'times'=>trim($_GPC['times']),//点击次数
				'givetimes'=>trim($_GPC['givetimes']),//邀请赠送次数
				'actrule'=>$_GPC['actrule'],//活动规则
				'bgimg'=>$_GPC['bgimg'],//背景图片			
				'bgmusic'=>$_GPC['bgmusic'],//背景音乐					
				'mchid'=>$_GPC['mchid'],
				'apikey'=>$_GPC['apikey']
            );
			if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
		}
		//这里来展示设置项表单
		include $this->template('web/setting');
	}

}