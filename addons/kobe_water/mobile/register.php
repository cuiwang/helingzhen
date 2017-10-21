<?php

    $uniacid = $_W['uniacid'];
    $fans = $_W['fans'];
    if(empty($fans['nickname'])){
	  load()->model('mc');
	  $fans = mc_oauth_userinfo();
    } 
    $openid = $fans['openid'];
    $image = $fans['tag']['avatar'];
    $nickname = $_POST['nickname'];
    $number = $_POST['number'];
    $phone = $_POST['phone'];
    if($number == $_SESSION['code']){
    	$data['uniacid'] = $uniacid;
    	$data['openid'] = $openid;
    	$data['member_nickname'] = $nickname;
    	$data['member_phone'] = $phone;
    	$data['member_image'] = $image;
    	$res = pdo_insert('hao_water_member',$data);
    	if($res){
    		$result = true;
    	    echo $result;
    	}else{
            $result = false;
            echo $result;
    	} 	
    }else{
    	$result = false;
        echo $result;
    }

?>