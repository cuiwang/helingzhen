<?php
//$messagetemplate = iunserializer($reply['mtemplates']);
  $template_id = $msgtemplate['msgtemplate'];//消息模板id 微信的模板id

 if (!empty($template_id)) {
		
	$ttime = date('Y-m-d H:i:s', $msgs['createtime']);
	$datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#173177'),
		'first'=>array('value'=>'您有一条新评论','color'=>'#173177'),
		'keyword1'=>array('value'=>$msgs['content'],'color'=>'#173177'),
		'keyword2'=>array('value'=>$ttime,'color'=>'#173177'),
		//'remark'=> array('value'=>$body,'color'=>'#173177')
	);
	$data=json_encode($datas); //发送的消息模板数据
}
?>
	