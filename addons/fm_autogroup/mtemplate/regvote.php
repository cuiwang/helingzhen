<?php

 $template_id = $reply['regmessagetemplate'];//消息模板id 微信的模板id
 $body = "";
 $keyword1 = "";
 if (!empty($template_id)) {
		
	if (!empty($userinfo['realname'])) {
		$uname = $userinfo['realname'];
	}else {
		$uname = $userinfo['nickname'];
	}	
	
    $body .= "ID：{$userinfo['id']} \n";
    $body .= "姓名：$uname \n";
    $body .= "宣言：{$userinfo['photoname']} \n";
    $body .= "恭喜您报名成功，想要自己充满人气吗，那就来赶快告诉你的小伙伴吧！";
      
    //发送格式
    //此格式的消息模板为：
    //		您好，您已购买成功。
	//		商品信息：{{name.DATA}}
	//		{{remark.DATA}}
	$regtime = date('Y-m-d H:i:s', $userinfo['createtime']);
	$title = "恭喜您报名".$reply['title']."成功！";
	$datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>$title,'color'=>'#173177'),
		'keyword1'=>array('value'=>$userinfo['photoname'],'color'=>'#173177'),
		'keyword2'=>array('value'=>$regtime,'color'=>'#173177'),
		'remark'=> array('value'=>$body,'color'=>'#173177')
	);
	$data=json_encode($datas); //发送的消息模板数据
}
?>
	