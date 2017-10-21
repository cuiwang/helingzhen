<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);
if(empty($rid)){
	message('活动rid错误！');
}
$xianchang = pdo_fetch("SELECT * FROM ".tablename($this->xc_table)." WHERE rid = :rid AND weid=:weid", array(':rid'=>$rid,':weid'=>$weid));	

if(empty($xianchang)){
	message('活动不存在或是已经被删除！');
}
if(isset($_GPC['basic_style']) && $_GPC['basic_style']){
	pdo_update($this->basic_config_table,array('basic_style'=>$_GPC['basic_style']),array('weid'=>$weid,'rid'=>$rid));
}
$xianchang['controls'] = iunserializer($xianchang['controls']);
$basic_config = pdo_fetch("SELECT * FROM ".tablename($this->basic_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
if(empty($basic_config['top_title'])){
	$basic_config['top_title'] = array('点击右边的二维码关注','请先扫码关注我们的公众号','关注公众号更多精彩等您哦！');
	$basic_config['bottom_words'] = '<p>
    搜索关注'.$_W['account']['name'].'、<span style="color: rgb(255, 192, 0); font-size: 20px;">点击菜单</span>即可参与';
}else{
	$basic_config['top_title'] = explode('#',$basic_config['top_title']); 
}
$small = 0;
if(!$_GPC['small'] || $_GPC['small']==0){
	$small = 0;
}else{
	$small = 1;
}
$bd_config = pdo_fetch("SELECT * FROM ".tablename($this->bd_manage_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
$lottory_show = array();
if(!empty($bd_config)   && $bd_config['show']==1){
	$bd_xm = iunserializer($bd_config['xm']);
	if(!empty($bd_xm) && is_array($bd_xm)){
		foreach($bd_xm as $row){
			if($row['zd_show']==1){
				$lottory_show[] = $row['zd_name'];
			}
		}
		if(!empty($lottory_show)){
			$bd_config['show'] = 1;
		}else{
			$bd_config['show'] = 0;
		}
	}else{
		$bd_config['show'] = 0;
	}
}else{
	$bd_config['show'] = 0;
}