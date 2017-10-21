<?php
	$share_data = $this -> module['config'];
	if($share_data['share_imagestatus']){
		if($share_data['share_imagestatus']==3){
			$shareimage = $share_data['share_image'];
		}elseif($share_data['share_imagestatus']==1){
			$shareimage = $goods['gimg'];
		}elseif($share_data['share_imagestatus']==2){
			$result = mc_fetch($_W['member']['uid'], array('credit1', 'credit2','avatar','nickname'));
			$shareimage = $result['avatar'];
		}
	}
	include $this->template('rules');
?>