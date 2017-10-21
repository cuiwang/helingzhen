<!--测试-->
<?php 
global $_W , $GPC;

$datam = array(
		"first"=>array( "value"=> "男士内衣","color"=>"#173177"),
		"keyword1"=>array( "value"=> "男士内衣","color"=>"#173177"),
		"keyword2"=>array( "value"=> "男士内衣","color"=>"#173177"),
		"keyword3"=>array( "value"=> "男士内衣","color"=>"#173177"),
		"keyword4"=>array( "value"=> "男士内衣","color"=>"#173177"),
		"keyword5"=>array( "value"=> "男士内衣","color"=>"#173177"),
		"remark"=>array("value"=>'点击查看详情', "color" => "#4a5077"),
);
$template_id = "OPENTM400341301";
$url2 = "www.baidu.com";
$account= WeAccount::create($_W['acid']);
$account -> sendTplNotice($_W["fans"]["openid"], $template_id, $datam, $url2);
/*//客服消息接口
	public function sendnotice($info){ 
	    global $_W, $_GPC;
		
		$acc = mc_notice_init();
        $info1 = $info['title']."\n";
		$info1 .= $info['info']."\n";
		$info1 .= "【备注】{$info['remark']}\n\n";
		$info1 .= "<a href='".$info['url']."'>点击查看</a>";
		$custom = array(
			'msgtype' => 'text', 
			'text' => array('content' => urlencode($info1)),
			'touser' => $info['openid'],
		);
		
		$status = $acc->sendCustomNotice($custom);
      }*/