<?php
global $_W,$_GPC;
load()->func('communication');

$extra = array();
$extra['User-Agent'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';
$response = ihttp_request('http://webapi.busi.inke.cn/mobile/mobile_share_api?liveid=1493364590430216&uid=39537421&openid=',array(),$extra);

echo "<pre>";
print_r(json_decode($response['content']));exit;

//if (preg_match('|"sn":"(.*?)","paused|i',$response['content'],$r)){
   //echo $r[1];
//}
//print_r($r[1]);exit;




$url = 'https://room.api.m.panda.tv/index.php?method=room.shareapi&roomid=537239';  
$response = ihttp_request($url); 
//if (preg_match('|<title>(.*?)</title>|i',$response['content'],$r)){
   //echo $r[1];
//}
echo "<pre>";
print_r(json_decode($response['content']));exit;

exit;
echo "222";
$extra['User-Agent'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';
$extra['Host'] = 'static.api.m.panda.tv';
$extra['Origin'] = 'https://m.panda.tv';
$response = ihttp_request('https://m.panda.tv/room.html?roomid=167501',array(),array('CURLOPT_HTTPHEADER' => array('Content-Type: text/xml; charset=utf-8','CURLOPT_REFERER' => 'http://www.qq.com')));
print_r($response['content']);exit;
if (preg_match('|<title>(.*?)</title>|i',$response['content'],$r)){
   //echo $r[1];
}
print_r($r);exit;



