<?php

 //$template_id="lyA6P6SI8OkJvqXof2-K4rfNZKAJxRspgeSKjL7L5ys";//消息模板id 微信的模板id
 $template_id="Nh3-MMR6JeA1_bcHHbC7MEXxSl6P7YeHvOYg3A5D_SI";//消息模板id 微信的模板id
 $body = "";
 $keyword1 = "";
 /**
 {{first.DATA}}
	文件标题：{{keyword1.DATA}}
	发起单位：{{keyword2.DATA}}
	日期：{{keyword3.DATA}}
	{{remark.DATA}}
 */
 
 
 if (!empty($template_id)) {
		
	
	$uname = $u['nickname'];
	
	$ttime = date('Y-m-d H:i:s', $a['createtime']);
  //  $body .= "您的姓名：{$u['nickname']} \n";
 //   $body .= "被投票ID：{$u['id']} \n";
  //  $body .= "被投票用户：$uname \n";
    $body .= "发布时间：$ttime \n";
    $body .= "更多精彩的内容正在等着您，快来查看吧 ☟";
      
   
	$title = $u['nickname'].',您好。新文章“'.$a['title'].'”已经发布。';
	$k2 = $a['description'];
	$datas=array(
		'name'=>array('value'=>$_W['account']['name'],'color'=>'#000'),
		'first'=>array('value'=>$title,'color'=>'#000'),
		'keyword1'=>array('value'=>$a['title'],'color'=>'#000'),
		'keyword2'=>array('value'=>$k2,'color'=>'#000'),
		//'keyword3'=>array('value'=>$ttime,'color'=>'#173177'),
		'remark'=> array('value'=>$body,'color'=>'#000')
	);
	$data=json_encode($datas); //发送的消息模板数据
}
?>
	