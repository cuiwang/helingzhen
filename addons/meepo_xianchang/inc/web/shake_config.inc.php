<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */

global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
$shake = pdo_fetch("SELECT * FROM ".tablename($this->shake_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($shake)){
	$shake['paodao_color'] = '#70B405';
	$shake['ready_time'] = 5;
	$shake['point'] = 100;
	$shake['app_bg'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/wheel.png';
	$shake['app_shake_img'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/hand.png';
	$shake['app_bg_color'] = '#ff9900';
	$shake['title'] = '摇一摇';
	$shake['slogan'] = '再大力！#再大力,再大力！#再大力,再大力,再大力！#摇，大力摇#快点摇啊，别停！摇啊，摇啊，摇啊';
	$shake['pp_img'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/car.png';
	$shake['shake_music'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/v4.mp3';
	$shake['award_again'] = 2;
	$shake['user_1'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma2.png';
	$shake['user_2'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma2.png';
	$shake['user_3'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma3.png';
	$shake['user_4'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma4.png';
	$shake['user_5'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma5.png';
	$shake['user_6'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma6.png';
	$shake['user_7'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma7.png';
	$shake['user_8'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma8.png';
	$shake['user_9'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma9.png';
	$shake['user_10'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma10.png';
	$shake['bg_music'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/bg_music.mp3';
	$module_config = $this->module['config'];
	$shake['max_man']  = empty($module_config['shake_max_man']) ? 200:$module_config['shake_max_man'];
	$shake['maxsize'] = 20;
}else{
	if(empty($shake['app_shake_img'])){
		$shake['app_shake_img'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/hand.png';
	}
	
	if(empty($shake['bg_music'])){
		$shake['bg_music'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/bg_music.mp3';
	}
	if(empty($shake['shake_music'])){
		$shake['shake_music'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/v4.mp3';
	}
	if(empty($shake['app_bg_color'])){
		$shake['app_bg_color'] = '#ff9900';
	}
	
	if(empty($shake['app_bg'])){
		$shake['app_bg'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/wheel.png';
	}
	if(empty($shake['slogan'])){
		$shake['slogan'] = '再大力！#再大力,再大力！#再大力,再大力,再大力！#摇，大力摇#快点摇啊，别停！摇啊，摇啊，摇啊';
	}
	if(empty($shake['shake_music'])){
		$shake['shake_music'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/v4.mp3';
	}
	if(empty($shake['user_1'])){
		$shake['user_1'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma2.png';
	}
	if(empty($shake['user_2'])){
		$shake['user_2'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma2.png';
	}
	if(empty($shake['user_3'])){
		$shake['user_3'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma3.png';
	}
	if(empty($shake['user_4'])){
		$shake['user_4'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma4.png';
	}
	if(empty($shake['user_5'])){
		$shake['user_5'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma5.png';
	}
	if(empty($shake['user_6'])){
		$shake['user_6'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma6.png';
	}
	if(empty($shake['user_7'])){
		$shake['user_7'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma7.png';
	}
	if(empty($shake['user_8'])){
		$shake['user_8'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma8.png';
	}
	if(empty($shake['user_9'])){
		$shake['user_9'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma9.png';
	}
	if(empty($shake['user_10'])){
		$shake['user_10'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/shake/ma10.png';
	}
	
	
}
if(checksubmit('submit')){
	$data = array();
	$data['bg_music'] = $_GPC['bg_music'];
	$data['user_1'] = $_GPC['user_1'];
	$data['user_2'] = $_GPC['user_2'];
	$data['user_3'] = $_GPC['user_3'];
	$data['user_4'] = $_GPC['user_4'];
	$data['user_5'] = $_GPC['user_5'];
	$data['user_6'] = $_GPC['user_6'];
	$data['user_7'] = $_GPC['user_7'];
	$data['user_8'] = $_GPC['user_8'];
	$data['user_9'] = $_GPC['user_9'];
	$data['user_10'] = $_GPC['user_10'];
	$data['award_again'] = intval($_GPC['award_again']);
	$data['app_bg'] = $_GPC['app_bg'];
	$data['point'] = intval($_GPC['point']);
	$data['slogan'] = $_GPC['slogan'];
	$data['ready_time'] = intval($_GPC['ready_time']);
	$data['paodao_color'] = $_GPC['paodao_color'];
	$data['pp_img'] = $_GPC['pp_img'];
	$data['shake_music'] = $_GPC['shake_music'];
	$data['socket_url'] = $_GPC['socket_url'];
	$data['app_shake_img'] = $_GPC['app_shake_img'];
	$data['app_bg_color'] = $_GPC['app_bg_color'];
	$data['qr_code'] = $_GPC['qr_code'];
	$data['shake_copyright'] = $_GPC['shake_copyright'];
	$data['max_man'] = intval($_GPC['max_man']);
	$data['maxsize'] = intval($_GPC['maxsize']);
	$data['title'] = $_GPC['title'];
	$data['weid'] = $weid;
	$data['rid'] = $rid;

	$shake_config_id = intval($_GPC['shake_config_id']);
	if(empty($shake_config_id)){
		pdo_insert($this->shake_config_table,$data);
	}else{
		pdo_update($this->shake_config_table,$data,array('id'=>$shake_config_id,'weid'=>$weid));
	}
	message('保存成功',$this->createWebUrl('shake_config',array('id'=>$id)),"success");
}
include $this->template('shake_config');