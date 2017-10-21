<?php
     
    $sql = "select * from ".tablename('hao_water_setting')." where uniacid=".$_W['uniacid']." "; 
    $setting = pdo_fetch($sql);

    $data['phone'] = $_GPC['phone'];
    if($_SESSION['code'] != null){
    	unset($_SESSION['code']);
    }
    $code = rand(1000,9999);

    $_SESSION['code'] = $code;

    $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
    $smsConf = array(
        'key'   => $setting['APPKEY'], //您申请的APPKEY
        'mobile'    => $data['phone'], //接受短信的用户手机号码
        'tpl_id'    => $setting['TempID'], //您申请的短信模板ID，根据实际情况修改
        'tpl_value' =>'#code#='.$code.'&#company#=微信订水' //您设置的模板变量，根据实际情况修改
    );
     
    $content = juhecurl($sendUrl,$smsConf,1); //请求发送短信
     
    if($content){
        $result = json_decode($content,true);
        $error_code = $result['error_code'];
        if($error_code == 0){
            die(json_encode(true));
        }else{
            die(json_encode(false));
        }
    }else{
        die(json_encode(false));
    }

?>