<?php

global $_W, $_GPC;
require_once IA_ROOT.'/addons/hsh_tools/include/core.class.php';
$hshApi = new WxHelper();
$data = array(
	'first' => "测试内容",
	'orderMoneySum' => "测试内容",
	'orderProductName' => "测试内容",
	'Remark' =>"感谢您使用赤水好生活平台，如有疑问，请与本平台联系，或致电（0852-2853123），祝您愉快~",
);
//$sendResult = json_decode($hshApi->sendTempletMSG("od8tRt-Qp6xhTb750JEIMSok0jGo", "hV3t97xGSA8CTbJEtEHcI4OjAKhq4BQmBiPcC-i-emg", $data),true);
$sendResult = json_decode($hshApi->sendTempletMSG("od8tRt2J8fp2QppgJcgSu2FLbblE", "hV3t97xGSA8CTbJEtEHcI4OjAKhq4BQmBiPcC-i-emg", $data),true);
if($sendResult['errcode'] == 0){
	message('发送成功！', '', 'success');
} else {
	message('发送失败！');
}
