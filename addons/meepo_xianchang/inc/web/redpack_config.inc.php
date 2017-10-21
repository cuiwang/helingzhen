<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 赞木 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$id = $rid = $_GPC['id'];
load()->func('tpl');
$redpack_config = pdo_fetch("SELECT * FROM ".tablename($this->redpack_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($redpack_config)){
	$redpack_config['tip_words'] = '<p>大屏幕倒计时开始，<br/>红包将从大屏幕降落到手机，此时<br/>手指戳红包即可参与<br/>抢红包游戏<br/></p>';
	$redpack_config['guize'] = '<p>1.用户打开微信扫描大屏幕上的二维码进入等待抢红包页面<br/>2.主持人说开始后，大屏幕和手机页面同时落下红包雨<br/>3.用户随机选择落下的红包，并拆开红包。<br/>4.如果倒计时还在继续，那么无论用户是否抢到了，都可以继续抢 直到倒计时完成。</p>';
    $redpack_config['all_nums'] = 2;
	$redpack_config['weixin_pay'] = 0;
	$module_config = $this->module['config'];
	$redpack_config['max_man']  = empty($module_config['redpack_max_man']) ? 200:$module_config['redpack_max_man'];
	$redpack_config['pc_bg'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/bottom_money.png';
	$redpack_config['wechat_bg'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/app/bottom_money.png';
	$redpack_config['red1'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/redenvelop_01.png';
	$redpack_config['red2'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/redenvelop_02.png';
	$redpack_config['red3'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/redenvelop_03.png';
	$redpack_config['red4'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/redenvelop_04.png';
	$redpack_config['red5'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/redenvelop_05.png';
	$redpack_config['red6'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/redenvelop_06.png';
	$redpack_config['red7'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/redenvelop_07.png';
	$redpack_config['money_bg'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/redpack/money_bg.jpg';
	$redpack_config['red1_times'] = 1500;
	$redpack_config['red2_times'] = 1200;
	$redpack_config['red3_times'] = 900;
	$redpack_config['red4_times'] = 1050;
	$redpack_config['red5_times'] = 1000;
	$redpack_config['red6_times'] = 800;
	$redpack_config['red7_times'] = 750;
}
load()->func('file');
if(!empty($_GPC['cert'])) {
	$picurl = "images/cert/".$_W['uniacid']."/apiclient_cert.pem";
	if(file_exists(ATTACHMENT_ROOT . '/'.$picurl)){
	   file_delete($picurl);
	}
	$upload = file_write($picurl,$_GPC['cert']);
}
if(!empty($_GPC['key'])) {
	$picurl = "images/cert/".$_W['uniacid']."/apiclient_key.pem";
	if(file_exists(ATTACHMENT_ROOT . '/'.$picurl)){
	   file_delete($picurl);
	}
	$upload = file_write($picurl,$_GPC['key']);	
}
if(!empty($_GPC['ca'])) {
	$picurl = "images/cert/".$_W['uniacid']."/rootca.pem";
	if(file_exists(ATTACHMENT_ROOT . '/'.$picurl)){
	   file_delete($picurl);
	}
	$upload = file_write($picurl,$_GPC['ca']);	
}
if(checksubmit('submit')){
	$data = array();
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	$data['tip_words'] = $_GPC['tip_words'];
	$data['guize'] = $_GPC['guize'];
	$data['weixin_pay'] = 0;
	$data['appid'] = trim($_GPC['appid']);
	$data['secret'] = trim($_GPC['secret']);
	$data['mchid'] = trim($_GPC['mchid']);
	$data['signkey'] = trim($_GPC['signkey']);
	$data['ip'] = trim($_GPC['ip']);
	$data['_desc'] = $_GPC['_desc'];
	$data['all_nums'] = intval($_GPC['all_nums']);
	$data['max_man'] = intval($_GPC['max_man']);
	$data['pc_bg'] = $_GPC['pc_bg'];
	$data['wechat_bg'] = $_GPC['wechat_bg'];
	$data['red1'] = $_GPC['red1'];
	$data['red2'] = $_GPC['red2'];
	$data['red3'] = $_GPC['red3'];
	$data['red4'] = $_GPC['red4'];
	$data['red5'] = $_GPC['red5'];
	$data['red6'] = $_GPC['red6'];
	$data['red7'] = $_GPC['red7'];
	$data['money_bg'] = $_GPC['money_bg'];
	$data['red1_times'] = intval($_GPC['red1_times']);
	$data['red2_times'] = intval($_GPC['red2_times']);
	$data['red3_times'] = intval($_GPC['red3_times']);
	$data['red4_times'] = intval($_GPC['red4_times']);
	$data['red5_times'] = intval($_GPC['red5_times']);
	$data['red6_times'] = intval($_GPC['red6_times']);
	$data['red7_times'] = intval($_GPC['red7_times']);
	$redpack_config_id = intval($_GPC['redpack_config_id']);
	if(empty($redpack_config_id)){
		pdo_insert($this->redpack_config_table,$data);
		message('保存成功',$this->createWebUrl('redpack_config',array('id'=>$id)),"success");
	}else{
		pdo_update($this->redpack_config_table,$data,array('id'=>$redpack_config_id,'weid'=>$weid));
		message('更新成功',$this->createWebUrl('redpack_config',array('id'=>$id)),"success");
	}
	
}

include $this->template('redpack_config');
 
      
