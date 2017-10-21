<?php

 $template_id = $fmqftemplate['fmqftemplate'];//消息模板id 微信的模板id
 $body = "";
 $keyword1 = "";

 if (!empty($template_id)) {
	
	$uname = $u['nickname'];
	
	$ttime = date('Y-m-d H:i:s', $a['createtime']);
  //  $body .= "您的姓名：{$u['nickname']} \n";
 //   $body .= "被投票ID：{$u['uid']} \n";
  //  $body .= "被投票用户：$uname \n";
    $body .= "发布时间：$ttime \n";
    $body .= "更多精彩的内容正在等着您，快来查看吧 ☟";
      
   
	$title = $u['nickname'].'  “'.$a['title'].'”已经发布。';
	$k2 = $a['description'];
	$datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#000'),
		'first'=>array('value'=>$title,'color'=>'#1587CD'),
		'keyword1'=>array('value'=>$a['title'],'color'=>'#1587CD'),
		'keyword2'=>array('value'=>$k2,'color'=>'#2D6A90'),
		'keyword3'=>array('value'=>$ttime,'color'=>'#173177'),
		'remark'=> array('value'=>$body,'color'=>'#FF9E05')
	);
	$data=json_encode($datas); //发送的消息模板数据
}
?>
	