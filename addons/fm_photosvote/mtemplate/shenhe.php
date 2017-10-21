<?php

 $template_id = $replyhh['shmessagetemplate'];//消息模板id 微信的模板id
 $body = "";
 $keyword1 = "";
 if (!empty($template_id)) {
	if (!empty($userinfo['realname'])) {
		$uname = $userinfo['realname'];
	}else {
		$uname = $userinfo['nickname'];
	}			
			
    //$body .= "ID：{$userinfo['uid']} \n";
    //$body .= "姓名：$uname \n";
   // $body .= "宣言：{$userinfo['photoname']} \n";
    $body .= "想要自己充满人气吗，那就来赶快告诉你的小伙伴吧！";
      
    //发送格式
    //此格式的消息模板为：
    //		您好，您已购买成功。
	//		商品信息：{{name.DATA}}
	//		{{remark.DATA}}
	
	$title = "恭喜".$uname.", 审核通过！";
	$k2 = "您报名的“".$reply['title']."”,已审核通过！";
	$k3 = date('Y-m-d H:i:s', $userinfo['lasttime']);
	$datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>$title,'color'=>'#173177'),
		'keyword1'=>array('value'=>'审核通过！','color'=>'#173177'),
		'keyword2'=>array('value'=>$k2,'color'=>'#173177'),
		'keyword3'=>array('value'=>$k3,'color'=>'#173177'),
		'remark'=> array('value'=>$body,'color'=>'#173177')
	);
	$data=json_encode($datas); //发送的消息模板数据
}
?>
	