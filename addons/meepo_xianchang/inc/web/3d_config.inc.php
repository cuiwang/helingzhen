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
$sd_config = pdo_fetch("SELECT * FROM ".tablename($this->sd_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($sd_config)){
	$sd_config['bg'] = $_W['siteroot'].'addons/meepo_xianchang/template/mobile/app/images/bg.jpg';
	$sd_config['count_down_on']  = 1;
	$sd_config['count_down_time']  = 9;

}else{
	$sd_config['str'] = iunserializer($sd_config['str']);
	$sd_config['placeholder_image_arr'] = iunserializer($sd_config['placeholder_image_arr']);
}
if(checksubmit('submit')){
	$data = array();
	$data['weid'] = $weid;
	$data['rid'] = $rid;
	$data['bg_img'] = $_GPC['bg_img'];
	$data['placeholder_image_arr'] = iserializer($_GPC['placeholder_image_arr']);
	$data['count_down_on']  = intval($_GPC['count_down_on']);
	$data['count_down_time']  = intval($_GPC['count_down_time']);
	$str = array();
	if(!empty($_GPC['type']) && is_array($_GPC['type'])){
		foreach($_GPC['type'] as $key=>$row){
			
			if($row=='Text'){
				$val = $_GPC['text'][$key];
			}elseif($row=='Sphere'){
				$val = '#sphere';
			}elseif($row=='Helix'){
				$val = '#helix';
			}elseif($row=='Torus'){
				$val = '#torus';
			}elseif($row=='Logo'){
				$val = $_GPC['3dLogo'][$key];
				if (!empty($_W['setting']['remote']['type'])) { 
					load()->func('file');
					$t_img = tomedia($val);
					$extension = get_extension($t_img);
					$img_data = file_get_contents($t_img);
					$filename = 'images/'.$weid.'/'.random(32).'.'.$extension;
					file_write($filename, $img_data); 
					$val = $_W['siteroot'].'attachment/'.$filename;
				}
			}elseif($row=='Countdown'){
				$val = intval($_GPC['Countdown'][$key]);
			}
			$str[]=  array('type'=>$row,'value'=>$val);
		}
	}
	$data['str'] = iserializer($str);
	$sd_config_id = intval($_GPC['sd_config_id']);
	if(empty($sd_config_id)){
		pdo_insert($this->sd_config_table,$data);
		message('保存成功',$this->createWebUrl('3d_config',array('id'=>$id)),"success");
	}else{
		pdo_update($this->sd_config_table,$data,array('id'=>$sd_config_id,'weid'=>$weid));
		message('更新成功',$this->createWebUrl('3d_config',array('id'=>$id)),"success");
	}
	
}
function get_extension($file){
	$info = pathinfo($file);
	return $info['extension'];
}
include $this->template('3d_config');
 
      
