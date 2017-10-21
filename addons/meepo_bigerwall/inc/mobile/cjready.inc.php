<?php
			 global $_GPC, $_W;
			 $weid = $_W['uniacid'];
			 $rid = intval($_GPC['rid']);
			 $ridwall = pdo_fetch("SELECT `lurumobile`,`cj_config` FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid", array(':weid'=>$weid,':rid'=>$rid));
			 if(!$ridwall['lurumobile']){
					if($ridwall['cj_config']==1){
						$data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid  AND award_id = :award_id  AND isblacklist != :isblacklist",array(":weid"=>$weid,':rid'=>$rid,':award_id'=>'meepo',':isblacklist'=>1)); 
					}else{
						$tem_data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid  AND award_id = :award_id  AND isblacklist != :isblacklist",array(":weid"=>$weid,':rid'=>$rid,':award_id'=>'meepo',':isblacklist'=>1));
						$data = array();
						if(!empty($tem_data) && is_array($tem_data)){
							foreach($tem_data as $row){
								 $check = pdo_fetch("SELECT `id` FROM ".tablename('weixin_signs')." WHERE openid=:openid AND rid=:rid AND status=:status",array(':openid'=>$row['openid'],':rid'=>$rid,':status'=>'1'));
								 if(!empty($check)){
										$data[] = $row;
								 }
							}
						}
					}
			 }else{
						if($ridwall['cj_config']==1){
							$data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid AND mobile !=:mobile AND award_id = :award_id  AND isblacklist != :isblacklist",array(":weid"=>$weid,':rid'=>$rid,':mobile'=>'',':award_id'=>'meepo',':isblacklist'=>1)); 	
						}else{
							$tem_data = pdo_fetchall("SELECT * FROM ".tablename('weixin_flag')." WHERE weid=:weid AND rid=:rid AND mobile !=:mobile AND award_id = :award_id  AND isblacklist != :isblacklist",array(":weid"=>$weid,':rid'=>$rid,':mobile'=>'',':award_id'=>'meepo',':isblacklist'=>1));
							$data = array();
							if(!empty($tem_data) && is_array($tem_data)){
								foreach($tem_data as $row){
									 $check = pdo_fetch("SELECT `id` FROM ".tablename('weixin_signs')." WHERE openid=:openid AND rid=:rid AND status=:status",array(':openid'=>$row['openid'],':rid'=>$rid,':status'=>'1'));
									 if(!empty($check)){
											$data[] = $row;
									 }
								}
							}
						}
			 }
			 $arr = array();
				if(!empty($data)){ 
						foreach($data as $v){
								$v['nickname'] = !empty($v['realname']) ? $v['realname']:$v['nickname'];
							 if($v['cjstatu']){
									
									$that = $v['nickname'].'|'.$v['id'].'|'.$v['avatar'].'|'.$v['openid'].'|'.$v['cjstatu'];
							 }else{
									$that = $v['nickname'].'|'.$v['id'].'|'.$v['avatar'].'|'.$v['openid'];
							 }
							$arr[] = $that;
						}
				}
				echo json_encode($arr); 