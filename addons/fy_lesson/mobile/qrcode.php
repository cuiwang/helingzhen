<?php
/**
 * 二维码推广
 */

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('qrcode');
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

if(!file_exists("../attachment/images/fy_lesson/".$uniacid."_".$openid."_ok.png") || time()-$member['uptime']>604800){
	set_time_limit(60); 
	ignore_user_abort(true); 
	include("../framework/library/qrcode/phpqrcode.php");

	/* 背景图片 */
	$bgimg = $setting['posterbg']?$_W['attachurl'].$setting['posterbg']:MODULE_URL."template/mobile/images/posterbg.jpg";

	/* 二维码图片 */
	$errorCorrectionLevel = 'L';  /* 纠错级别：L、M、Q、H */
	$matrixPointSize = 4;  /* 点的大小：1到10 */
	
	$qrcode = $dirpath.$uniacid."_".$openid."_qrcode".'.png'; /* 生成的文件名 */
	QRcode::png($infourl, $qrcode, $errorCorrectionLevel, $matrixPointSize, 2);

	/* 合成二维码 */
	$savefield = $this->img_water_mark($bgimg, $qrcode, $dirpath, $uniacid."_".$openid.".png", "473", "733");
	
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
	/* 写入文字 */
	$fun = $dirpath.$uniacid."_".$openid.".png";
	imagettftext($image, 24, 0, 210, 728, $color, $font, $fansinfo['nickname']);  
	/* 保存图片 */
	$fun = "image".$type;
	$okfield = $dirpath.$uniacid."_".$openid."_ok.png";
	$fun($image, $okfield);  
	/*销毁图片*/  
	imagedestroy($image);
	
	/* 删除多余文件 */
	unlink("../attachment/images/fy_lesson/".$uniacid."_".$openid.".png");
	unlink("../attachment/images/fy_lesson/".$uniacid."_".$openid."_qrcode.png");
	unlink("../attachment/images/fy_lesson/".$uniacid."_".$openid."_avatar.jpg");
}

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