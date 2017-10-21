<?php
//$messagetemplate = iunserializer($reply['mtemplates']);
 //$template_id = $mt;//消息模板id 微信的模板id
 $body = "";
 $keyword1 = "";
 if (!empty($template_id)) {
		
	
	$tname = $this->getusernames($tuservote['realname'], $tuservote['nickname'], '15', $tuservote['from_user']);
	$ttime = date('Y-m-d H:i:s', $tuservote['createtime']);
	$rtime = date('Y-m-d H:i:s', $reply['start_time']);
	//$xcps  = $su['photosnum'] + $su['xnphotosnum'] - $u['photosnum'] - $u['xnphotosnum']
    //$body .= "ID：{$u['uid']} \n";
    $dqps = $u['photosnum'] + $u['xnphotosnum'];
    $body .= "投票用户：{$tname} \n";
    $body .= "投票时间：$ttime \n";
    $body .= "当前票数：$dqps 票\n";
   // $body .= "恭喜您又增加一票，距上一名差 $xcps 票  \n";
      
   
	$title = '恭喜您又增加一票！！';
	$datas=array(
		//'name'=>array('value'=>$_W['account']['name'],'color'=>'#428bca'),
		'first'=>array('value'=>$title,'color'=>'#428bca'),
		'keyword1'=>array('value'=>$reply['title'],'color'=>'#000'),
		'keyword2'=>array('value'=>$rtime,'color'=>'#000'),
		'remark'=> array('value'=>$body,'color'=>'#000')
	);
	$tdata=json_encode($datas); //发送的消息模板数据
}
?>
	