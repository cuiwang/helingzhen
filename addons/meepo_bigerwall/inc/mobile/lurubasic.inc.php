<?php
global $_GPC, $_W;
			if($_W['ispost']){
				$rid = intval($_GPC['rid']);
			   if(!empty($_W['fans']['from_user'])){
						 
			       if(!empty($_GPC['username']) && !empty($_GPC['password'])){
								$lurucheck = pdo_fetchcolumn("SELECT `lurucheck` FROM ".tablename('weixin_wall_reply')." WHERE weid=:weid AND rid = :rid", array(':weid'=>$_W['uniacid'],':rid'=>$rid));
								if(!$lurucheck){
										pdo_update('weixin_flag',array('mobile'=>$_GPC['password'],'realname'=>$_GPC['username']),array('weid'=>$_W['uniacid'],'openid'=>$_W['fans']['from_user'],'rid'=>$rid));
										$data['msg'] = 'success';
								}else{
										$check = pdo_fetchcolumn("SELECT `id` FROM ".tablename('weixin_mobile_manage')." WHERE weid=:weid AND rid=:rid AND mobile=:mobile AND realname=:realname",array(':weid'=>$_W['uniacid'],':rid'=>$rid,':mobile'=>$_GPC['password'],':realname'=>$_GPC['username']));
										if($check){
											pdo_update('weixin_flag',array('mobile'=>$_GPC['password'],'realname'=>$_GPC['username']),array('weid'=>$_W['uniacid'],'openid'=>$_W['fans']['from_user'],'rid'=>$rid));
											$data['msg'] = 'success';
										}else{
											$data['msg'] = 'error';
										}
								}
								
				   }else{
				      $data['msg'] = '1';
				   }
			   }else{
			     $data['msg'] = '1';
			   }
			}else{
			     $data['msg'] = '1';
			}
			 echo json_encode($data); 