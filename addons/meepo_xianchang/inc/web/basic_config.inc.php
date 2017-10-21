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
$leaf_styles = array('1'=>'枫叶','2'=>'飘雪','4'=>'玫瑰','7'=>'元宝');
$basic_config = pdo_fetch("SELECT * FROM ".tablename($this->basic_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($basic_config)){
	$basic_config['bottom_words'] = '<p>
    搜索关注'.$_W['account']['name'].'、<span style="color: rgb(255, 192, 0); font-size: 20px;">点击菜单</span>即可参与
</p>';
	$basic_config['top_font_size'] = '20';
	$basic_config['top_img'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/icon/top.png';
	$basic_config['bottom_img'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/icon/bottom.png';
	$basic_config['basic_style'] = 35;
	$basic_config['show_star'] = 0;
	$basic_config['show_leaf']  = 0;
	$basic_config['leaf_style']  = 1;
	$basic_config['bg_music_on']  = 0;
	$basic_config['bg_music']  = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/bg_music.mp3';

}else{
	if(empty($basic_config['bg_music'])){
		$basic_config['bg_music']  = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/bg_music.mp3';

	}	
}
$filenames = $this->get_filenamesbydir(MODULE_ROOT.'/template/mobile/app/style/icon'); 
$styles = array();

if(!empty($filenames)){  
	foreach ($filenames as $value) {
		$jian = '';
		$jian = str_replace(MODULE_ROOT.'/template/mobile/app/style/icon/','',$value);
		//020d.jpg
		$jian = substr($jian,1,2);
		if(substr($jian,0,1)=='0'){
			$jian = str_replace('0','',$jian);
		}
		$styles[$jian] = str_replace(IA_ROOT,'..',$value);
		
	}
	sort($styles);
}
if(checksubmit('submit')){
	$data = array();
	$data['mp_name'] = $_GPC['mp_name'];
	$data['mp_img'] = $_GPC['mp_img'];
	$data['top_img'] = $_GPC['top_img'];
	$data['top_title'] = $_GPC['top_title'];
	$data['top_font_size'] = $_GPC['top_font_size'];
	$data['bg_img'] = $_GPC['bg_img'];
	$data['bottom_img'] = $_GPC['bottom_img'];
	$data['bottom_words'] = $_GPC['bottom_words'];
	$data['basic_style'] = $_GPC['basic_style'];
	$data['show_star'] = intval($_GPC['show_star']);
	$data['show_leaf'] = intval($_GPC['show_leaf']);
	$data['leaf_style'] = intval($_GPC['leaf_style']);
	$data['bg_music_on'] = intval($_GPC['bg_music_on']);
	$data['bg_music'] = $_GPC['bg_music'];
	$data['bg_vedio'] = $_GPC['bg_vedio'];
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	$basic_config_id = intval($_GPC['basic_config_id']);
	if(empty($basic_config_id)){
		pdo_insert($this->basic_config_table,$data);
		message('保存成功',$this->createWebUrl('basic_config',array('id'=>$id)),"success");
	}else{
		pdo_update($this->basic_config_table,$data,array('id'=>$basic_config_id,'weid'=>$weid));
		message('更新成功',$this->createWebUrl('basic_config',array('id'=>$id)),"success");
	}
	
}
include $this->template('basic_config');
 
      
