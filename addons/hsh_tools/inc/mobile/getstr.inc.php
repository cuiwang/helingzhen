<?php

global $_W, $_GPC;
$arraySample = array(
	"describe" => array("type" => "textarea", "prompt" => '代购描述'),
	"tel" => array("type" => "text", "prompt" => '联系电话'),
	"address" => array("type" => "text", "prompt" => '地址'),
	"time_type" => array("type" => "select", "prompt" => '限时类型', "options" => array("即时" => "0", "半小时" => "1", "一小时" => "2", "不限" => "3")),
	"state" => array("type" => "select", "prompt" => '状态', "options" => array("未处理" => "0", "备货中" => "1", "已发货" => "2", "交易失败" => "4")),
	"remark" => array("type" => "textarea", "prompt" => '备注'),
);

var_dump(serialize($arraySample));
$tempArray=unserialize('a:17:{s:8:"sitename";s:24:"微赞微信管理系统";s:3:"url";s:17:"http://www.012wz.com";s:8:"statcode";s:0:"";s:10:"footerleft";s:17:"powered by 012wz.com";s:11:"footerright";s:0:"";s:5:"flogo";s:0:"";s:5:"blogo";s:0:"";s:8:"baidumap";a:2:{s:3:"lng";s:10:"116.969267";s:3:"lat";s:9:"33.611475";}s:7:"company";s:39:"深圳市零壹贰网络科技有限公司";s:7:"address";s:68:"深圳民治";s:6:"person";s:9:"微微一跃";s:5:"phone";s:11:"15888888888";s:2:"qq";s:9:"800083075";s:5:"email";s:0:"";s:8:"keywords";s:82:"微赞,微信,微信公众平台,公众平台二次开发,公众平台开源软件";s:11:"description";s:82:"微赞,微信,微信公众平台,公众平台二次开发,公众平台开源软件";s:12:"showhomepage";i:1;}');
var_dump($tempArray);
$tempArray['sitename']='好生活微信管理平台';
$tempArray['url']='w.hshcs.com';
$tempArray['footerright'] ='赤水好生活';
var_dump($tempArray);
echo serialize($tempArray);
return;
