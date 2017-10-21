<?php

    $uniacid = $_W['uniacid'];
    $fans = $_W['fans'];
    if(empty($fans['nickname'])){
	  load()->model('mc');
	  $fans = mc_oauth_userinfo();
    } 
    $openid = $fans['openid'];
    $member = pdo_fetch("SELECT * FROM ".tablename('hao_water_member')." WHERE openid = :openid AND uniacid = :uniacid LIMIT 1", array(':openid' => $openid,':uniacid' => $uniacid));
    if(!empty($member)){
    	$cart = pdo_fetch("SELECT * FROM ".tablename('hao_water_cart')." WHERE openid = :openid AND shop_id = :shop_id AND uniacid = :uniacid LIMIT 1", array(':openid' => $openid,':shop_id'=>$_POST['shop_id'],':uniacid' => $uniacid));
    	if(!empty($cart)){
            $data['shop_count'] = intval($_POST['shop_count']) + intval($cart['shop_count']);
			$result = pdo_update('hao_water_cart', $data, array('id' => $cart['id']));
			if (!empty($result)) {
			   $info['result'] = 'success';
			   echo json_encode($info);
			}else{
			   $info['result'] = 'fail';
		       echo json_encode($info);
			}
    	}else{
	    	$data['member_id'] = $member['id'];
		    $data['shop_id'] = $_POST['shop_id'];
		    $data['shop_count'] = $_POST['shop_count'];
		    $data['time'] = time();
		    $data['uniacid'] = $uniacid;
		    $data['openid'] = $member['openid'];
			$result = pdo_insert('hao_water_cart', $data);
			if ($result) {
			   $info['result'] = 'success';
			   echo json_encode($info);
			}else{
			   $info['result'] = 'fail';
		       echo json_encode($info);
			}
		}
    }else{
    	$info['result'] = 'register';
    	echo json_encode($info);
    }
?>