<?php
    header("content-type:text/html;charset=utf-8");
    $code = $_GET['code'];
    $state = $_GET['state'];
    //换成自己的接口信息
    //$appid = $this->module['config']['fhb_appid'];
    //$appsecret = $this->module['config']['fhb_secret'];    
    $appid = 'wxafca714812a5db91';
    $appsecret = '8b02ba2aa865409ca4d1cb63b44bf8d1';
    if (empty($code)){
    	message("授权失败");
    } 
    $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
    $token = json_decode(file_get_contents($token_url));
    if (isset($token->errcode)) {
        echo '<h1>错误：</h1>'.$token->errcode;
        echo '<br/><h2>错误信息：</h2>'.$token->errmsg;
        exit;
    }
    $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
    //转成对象
    $access_token = json_decode(file_get_contents($access_token_url));
    if (isset($access_token->errcode)) {
        echo '<h1>错误：</h1>'.$access_token->errcode;
        echo '<br/><h2>错误信息：</h2>'.$access_token->errmsg;
        exit;
    }
    $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
    //转成对象
    $user_info = json_decode(file_get_contents($user_info_url));
    if (isset($user_info->errcode)) {
        echo '<h1>错误：</h1>'.$user_info->errcode;
        echo '<br/><h2>错误信息：</h2>'.$user_info->errmsg;
        exit;
    }else{
        header("location:"."http://we7.zssyb.com/app/index.php?i=6&c=entry&do=index&m=wqtgd_tb");  
    }

    //打印用户信息
/*    echo '<pre>';
    print_r($user_info);
    echo '</pre>';*/