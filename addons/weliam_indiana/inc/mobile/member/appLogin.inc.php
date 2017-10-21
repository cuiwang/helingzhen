<?php
global $_W,$_GPC;
m('log')->WL_log('login','GPC',$_GPC,3); 
if(!empty($_POST)){
	$token=json_decode($_POST["token"],1);
    if(!empty($token)){
    	$checkmember = pdo_fetch("select * from".tablename('weliam_indiana_member')." where uniacid=:uniacid and unionid=:unionid",array(':uniacid'=>$_W['uniacid'],':unionid'=>$token['unionid']));
    	if(empty($checkmember)){
    		$checkfans = pdo_fetch("select * from".tablename('mc_mapping_fans')." where uniacid=:uniacid and unionid=:unionid",array(':uniacid'=>$_W['uniacid'],':unionid'=>$token['unionid']));
    		
    		$data['unionid'] = $token['unionid'];
    		$data['openid'] = $token['unionid'];
			$data['uniacid'] = $_W['uniacid'];
			$data['createtime'] = time();
			$data['nickname'] = $token['nickname'];
			$data['gender'] = $token['sex'];
			$data['avatar'] = $token['headimgurl'];
			$data['status'] = 1;
			$data['type'] = 1;
    		if(empty($checkfans)){
				$datam['uniacid'] = $data['uniacid'];
				$datam['createtime'] = $data['createtime'];
				$datam['nickname'] = $data['nickname'];
				$datam['avatar'] = $data['avatar'];
				if(pdo_insert('mc_members',$datam)){
					$dataf['acid'] = $data['uniacid'];
					$dataf['uniacid'] = $data['uniacid'];
					$dataf['uid'] =	pdo_insertid();
					$dataf['openid'] = $data['openid'];
					$dataf['unionid'] = $data['unionid'];
					$dataf['nickname'] = $data['nickname'];
					$dataf['follow'] = 1;
					$dataf['unfollowtime'] = time();
					$dataf['tag'] = '';
					$dataf['updatetime'] = time();
				}
    		}else{
    			if(pdo_fetch("select * from".tablename('weliam_indiana_member')." where uniacid=:uniacid and openid=:openid",array(':uniacid'=>$_W['uniacid'],':openid'=>$checkfans['openid']))){
    				pdo_update('weliam_indiana_member',array('unionid'=>$token['unionid']),array('uniacid'=>$_W['uniacid'],'openid'=>$checkfans['openid']));
    			}else{
    				$data['openid'] = $checkfans['openid'];
    				pdo_insert('weliam_indiana_member',$data);
    			}
    		}
    	}
    }
}else{ 
//	//登录成功后读取用户相关信息
	m('log')->WL_log('login','$info111',$_GPC,3); 
   	$unionid=$_GPC["unionid"];
   	$info = pdo_fetch('select openid,unionid from ' . tablename('weliam_indiana_member') . ' where unionid=:unionid and uniacid=:uniacid limit 1', array(':unionid' => $unionid,':uniacid'=>$_W['uniacid']));
   	m('log')->WL_log('login','$info',$info,3); 
   	isetcookie('unionid',$info['unionid'],3600,true);
   	header("Location: ".app_url('index/go'));
}
?>