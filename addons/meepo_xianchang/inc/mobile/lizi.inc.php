<?php
/**
 * MEEPO 米波现场
 *
 * 官网 http://meepo.com.cn 作者QQ 284099857
 */
include MODULE_ROOT.'/inc/mobile/pc_init.php';
$sd_config = pdo_fetch("SELECT * FROM ".tablename($this->sd_config_table)." WHERE weid = :weid AND rid=:rid",array(':weid'=>$weid,':rid'=>$rid));
$temp_str = iunserializer($sd_config['str']);
$placeholder_image_arr = iunserializer($sd_config['placeholder_image_arr']);
if(!empty($placeholder_image_arr)){
	foreach($placeholder_image_arr as &$row){
			$row = tomedia($row);
	}
	unset($row);
}
if($sd_config['count_down_on']==0){
	$str = '';
}else{
	$str = "#countdown ".$sd_config['count_down_time']."|";
}
if(!empty($temp_str) && is_array($temp_str)){
		foreach($temp_str as $key=>$row){
		 if($key+1!=count($temp_str)){
			  if($row['type']!='Logo'){
				  if($row['type']=='Countdown'){
					$str .= "#countdown ".$row['value'].'|';
				  }else{
					$str .= $row['value'].'|';
				  }
			  }else{
				$str .= "#icon ".tomedia($row['value']).'|';
			  }
		 }else{
			  if($row['type']!='Logo'){
				  if($row['type']=='Countdown'){
					$str .= "#countdown ".$row['value'];
				  }else{
					$str .= $row['value'];
				  }
			  }else{
				$str .= "#icon ".tomedia($row['value']);
			  }
		 }
		}
}
$qds = pdo_fetchall("SELECT `nick_name`,`avatar` FROM ".tablename($this->qd_table)." WHERE weid = :weid AND rid = :rid AND level = :level ORDER BY createtime ASC",array(':weid'=>$weid,':rid'=>$rid,':level'=>'1'));
$qd_maxid = pdo_fetchcolumn("SELECT max(id) FROM ".tablename($this->qd_table)." WHERE weid = :weid AND rid = :rid AND level = :level",array(':weid'=>$weid,':rid'=>$rid,':level'=>'1'));
include $this->template('lizi');