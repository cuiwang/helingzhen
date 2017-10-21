<?php
global $_GPC, $_W;
$rid = intval($_GPC['rid']);
$id = intval($_GPC['id']);
$num = intval($_GPC['num']);
$from_user = $_GPC['from_user'];
$djtitle = $_GPC['djtitle'];
//		//网页授权借用开始（特殊代码）
//
load()->model('account');
$_W['account'] = account_fetch($_W['acid']);

if ($_W['account']['level'] != 4) {
	$cookieid = '__cookie_haoman_dpm_201610186_' . $rid;
	$cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
	$from_user = $cookie['openid'];
	$avatar = $cookie['avatar'];
	$nickname = $cookie['nickname'];

}else{

	$from_user = $_W['fans']['from_user'];
	$avatar = $_W['fans']['tag']['avatar'];
	$nickname = $_W['fans']['nickname'];
}
//
//		//网页授权借用结束（特殊代码）

if (empty($from_user)) {
	$this->message(array("success" => 2, "msg" => '获取不到您的OpenID,请从新进入活动页面'), "");
}

$imgName = "haomandpm".$_W['uniacid'].$id;
$linkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=hexiao&rid=".$rid."&id=".$id;
$imgUrl = "../addons/haoman_dpm/qrcode/".$imgName.".png";

load()->func('file');
mkdirs(ROOT_PATH . '/qrcode');
$dir = $imgUrl;
$flag = file_exists($dir);

if($flag == false){
	//生成二维码图片
	$errorCorrectionLevel = "L";
	$matrixPointSize = "4";
	QRcode::png($linkUrl,$imgUrl,$errorCorrectionLevel,$matrixPointSize);
	//生成二维码图片
}

$data = array(
	'success' => 1,
	'msg' => $imgUrl,
	'djtitle' => $djtitle,
);

echo json_encode($data);