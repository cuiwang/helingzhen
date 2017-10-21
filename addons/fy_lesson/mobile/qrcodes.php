<?php


$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('qrcodes');
if (isset($_COOKIE[$this->_auth2_openid])) {
	$openid = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$openid = $userinfo["openid"];
			$nickname = $userinfo["nickname"];
			$headimgurl = $userinfo["headimgurl"];
		} else {
			message('授权失败!');
		}
	} else {
		if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
	}
}

//基本设置
$setting = pdo_fetch("SELECT is_sale,sharelink,sitename,footnav,copyright,posterbg,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));

if($setting['is_sale']==0){
	message("该功能不存在或已关闭！", "", "warning");
}

$title = "我的推广海报";

load()->model('mc');
$fansinfo = mc_fansinfo($openid, $_W['acid'], $_W['uniacid']);

$member = pdo_fetch("SELECT uptime FROM " .tablename($this->table_member). " WHERE uniacid='{$uniacid}' AND openid='{$openid}'");
if(!empty($member)){
	$infourl = $_W['siteroot'] .'app/'. $this->createMobileUrl('index', array('uid'=>$fansinfo['uid']));
}

$reply = pdo_fetch("SELECT id,rid FROM " .tablename('cover_reply'). " WHERE uniacid='{$uniacid}' AND module='fy_lesson' AND do='qrcode'");
$keyword = pdo_fetch("SELECT content FROM " .tablename('rule_keyword'). " WHERE uniacid='{$uniacid}' AND rid='{$reply['rid']}' AND module='cover'");

$dirpath = "../attachment/images/fy_lesson/";
if(!file_exists($dirpath)){
	mkdir($dirpath, 0777);
}

/* 背景图片 */
$bgimg = $setting['posterbg']?$_W['attachurl'].$setting['posterbg']:MODULE_URL."template/mobile/images/posterbg.jpg";

/* 获取带参数二维码 */
$codeArray = array (
	'expire_seconds' => 2592000,
	'action_name' => QR_SCENE,
	'action_info' => array (
		'scene' => array (
			'scene_id' => $fansinfo['uid'],
		),
	),
);
$account_api = WeAccount::create();
$res = $account_api->barCodeCreateDisposable($codeArray);
if(empty($res['ticket'])){
	message("获取二维码失败，错误信息:".$res['errcode']."，".$res['errmsg']);
}
$qrcodeurl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$res['ticket'];
$qrcodeImage = $this->downLoadImg($qrcodeurl, $dirpath.$uniacid."_".$openid."_qrcode.jpg");
$this->resize($dirpath.$uniacid."_".$openid."_qrcode.jpg", $dirpath.$uniacid."_".$openid."_qrcode.jpg", "150", "150", "100");

/* 合成二维码 */
$savefield = $this->img_water_mark($bgimg, $dirpath.$uniacid."_".$openid."_qrcode.jpg", $dirpath, $uniacid."_".$openid.".png", "473", "733");

/* 合成头像 */
$avatar = $this->saveImage($fansinfo['avatar'].".jpg", $dirpath.$uniacid."_".$openid."_","avatar.jpg");
$this->resize($dirpath.$uniacid."_".$openid."_avatar.jpg", $dirpath.$uniacid."_".$openid."_avatar.jpg", "100", "100", "100");
$savefield = $this->img_water_mark($savefield, $dirpath.$uniacid."_".$openid."_avatar.jpg", "../attachment/images/fy_lesson/", $uniacid."_".$openid."_ok.png", "22", "698");

/* 合成昵称 */
$info = getimagesize($savefield);  
/* 通过编号获取图像类型 */ 
$type = image_type_to_extension($info[2],false);  
/* 图片复制到内存 */
$image = imagecreatefromjpeg($savefield);  
  
/* 设置字体的路径 */
$font = "../addons/fy_lesson/template/mobile/ttf/yahei.ttf";  
/* 设置字体颜色和透明度 */
$color = imagecolorallocatealpha($image, 255, 255, 255, 0);  
/* 写入昵称文字 */
$fun = $dirpath.$uniacid."_".$openid.".png";
imagettftext($image, 24, 0, 210, 728, $color, $font, $fansinfo['nickname']);
/* 写入有效期文字 */
$fun = $dirpath.$uniacid."_".$openid.".png";
imagettftext($image, 10, 0, 483, 930, $color, $font, date('Y-m-d H:i:s',time()+2584800));
/* 保存图片 */
$fun = "image".$type;
$okfield = $dirpath.$uniacid."_".$openid."_ok.png";
$fun($image, $okfield);  
/*销毁图片*/  
imagedestroy($image);

/* 删除多余文件 */
unlink("../attachment/images/fy_lesson/".$uniacid."_".$openid.".png");
unlink("../attachment/images/fy_lesson/".$uniacid."_".$openid."_qrcode.jpg");
unlink("../attachment/images/fy_lesson/".$uniacid."_".$openid."_avatar.jpg");


/* 通过公众号下发海报 */
$acc = WeAccount::create($_W['acid']);
$imagepath = ATTACHMENT_ROOT."images/fy_lesson/".$uniacid."_".$openid."_ok.png";
$data = $acc->uploadMedia($imagepath);


$send = array();
$send['touser']  = $openid;
$send['fromuser']  = $openid;
$send['createtime']  = time();
$send['msgtype'] = 'image';
$send['image'] = array('media_id' => $data['media_id']);
if($_W['acid']) {
	$result = $acc->sendCustomNotice($send);
}


include $this->template('qrcode');

?>