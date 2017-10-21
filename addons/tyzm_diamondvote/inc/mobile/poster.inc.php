<?php
/**
 * 分享海报生成
 */
defined('IN_IA') or exit('Access Denied');

/*ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(-1);*/
$retgd = extension_loaded('gd');
if(empty($retgd)) {
	echo "没有启用GD, 无法生成海报，请配置php环境支持GD拓展再试。";
	exit;
} 

is_weixin();
global $_W, $_GPC;
$rid = $_GPC['rid'];
$id  = $_GPC['id'];
//$this->check_browser();
//$color=m('common')->hex2rgb("#a64d79");
//print_r($color);exit;
$reply = pdo_fetch("SELECT id,config,bill_data,endtime,status FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(
    ':rid' => $rid
));
$reply = array_merge($reply, unserialize($reply['config']));
if($reply['apstarttime']> time()){
	$aptime=1;//未开始报名
}elseif($reply['apendtime']<time()){
	$aptime=2;//报名已结束
}
if(empty($reply['isposter'])){
	message('未开启海报功能，请管理员至后台编辑活动开启！', $this->createMobileUrl('index', array('rid' => $rid)),'error');
}


unset($reply['config']);
$userinfo = m('user')->Get_checkoauth();

$voteuser = pdo_fetch("SELECT * FROM " . tablename($this->tablevoteuser) . " WHERE uniacid = :uniacid AND id=:id AND rid=:rid ", array(
    ':uniacid' => $_W['uniacid'],
    ':id' => $id,
    ':rid' => $rid
));
if(!empty($voteuser)){
	$myvoteid=$voteuser['id'];
}
if (empty($voteuser)){
    message('参数错误！', $this->createMobileUrl('index', array('rid' => $rid)),'error');
}
/*
if($userinfo['openid']!=$voteuser['openid']){
    message('无权限访问其他用户海报！', $this->createMobileUrl('index', array('rid' => $rid)),'error');
}
*/
$posterimg = '/images/'.$_W['uniacid'].'/tyzm_diamondvote/'.$rid.'/';
$posterimgname = $voteuser['createtime'].'_' .$voteuser['id'] .'.jpg';
$poster=$posterimg.$posterimgname;



if(file_exists(ATTACHMENT_ROOT .$poster)){
    
}else{
	load()->func('file');
	is_dir(ATTACHMENT_ROOT . $posterimg) or mkdirs(ATTACHMENT_ROOT .$posterimg);	
	$id       = @$voteuser['id'];
	$photo    = @$voteuser['avatar'];
	$nickname = empty($voteuser['name'])?$voteuser['nickname']:$voteuser['name'];
	$img      = @$voteuser['img1'];
	$avatar   = @$voteuser['avatar'];

	$posterBgImage = tomedia($reply['bill_bg']);
	
	
	$posterData    = json_decode($reply['bill_data'], true);
    //print_r($posterData);exit;
	$pigfilearray = getimagesize($posterBgImage);
	$posterData   = array_merge(array(
		'0' => array(
			'left' => '0px',
			'top' => '0px',
			'type' => 'bag',
			'width' => ($pigfilearray[0] / 2) . 'px',
			'height' => ($pigfilearray[1] / 2) . 'px'
		)
	), $posterData);
	$background   = imagecreatetruecolor($pigfilearray[0], $pigfilearray[1]); // 背景图片  
	$color        = imagecolorallocate($background, 202, 201, 201); // 为真彩色画布创建白色背景，再设置为透明  
	imagefill($background, 0, 0, $color);
	imageColorTransparent($background, $color);


	
	foreach ($posterData as $data){
			switch ($data['type']) {
			case 'bag':
				$pic_path = $posterBgImage;
				break;
			case 'img':
				$pic_path = tomedia($img);
				break;
			case 'avatar':
				$pic_path = tomedia($avatar);
				break;
			case 'name':
			case 'number':
			    if($data['type']=='number'){
					$text=$voteuser['noid'];
				}else{
					$text=$nickname;
				}
			    $background=ATTACHMENT_ROOT .$poster;
				$dst = imagecreatefromstring(file_get_contents($background));
				$color=m('common')->hex2rgb($data['color']);
				$black = ImageColorAllocate($dst,$color["r"],$color["g"],$color["b"]); 
				$font = MODULE_ROOT . '/lib/font/font.ttf';
				$name = mb_convert_encoding($text, 'html-entities', 'UTF-8');
				imagettftext($dst, $data['size']*1.5,0, $data['left'] * 2, ($data['top'] * 2+$data['size']*2), $black, $font, $name);
				imagejpeg($dst,ATTACHMENT_ROOT.$poster);	
				break;
			case 'qr':
				require_once(MODULE_ROOT . '/lib/qrcode/phpqrcode.php');
				$QR_path=ATTACHMENT_ROOT . $poster."qr.png";
				
				if($_W['account']['level']==4){
					//生成二维码
					$barcode = array(
						'expire_seconds' => '',
						'action_name' => '',
						'action_info' => array(
							'scene' => array(),
						),
					);	
					$acid = intval($_W['acid']);
					$uniacccount = WeAccount::create($acid);
					$scene_id=$acid."00".$rid."00".$id;
					$qrcode = pdo_fetch("SELECT url FROM ".tablename('qrcode')." WHERE acid = :acid AND qrcid=:qrcid AND model = '1' AND type = 'scene' ORDER BY qrcid DESC LIMIT 1", array(':acid' => $acid,':qrcid' => $scene_id));
					
					if(!empty($qrcode)){
						$qrurl=$qrcode['url'];
					}else{
						$barcode['action_info']['scene']['scene_id'] = $scene_id;
						$barcode['expire_seconds'] = 2592000;
						$barcode['action_name'] = 'QR_SCENE';
						$result = $uniacccount->barCodeCreateDisposable($barcode);
						if(!is_error($result)){
							$insert = array(
								'uniacid' => $_W['uniacid'],
								'acid' => $acid,
								'qrcid' => $barcode['action_info']['scene']['scene_id'],
								'scene_str' => $barcode['action_info']['scene']['scene_str'],
								'keyword' => $reply['posterkey'].$voteuser['noid'],
								'name' => "投票海报".$reply['id'].$nickname.$id,
								'model' => 1,
								'ticket' => $result['ticket'],
								'url' => $result['url'],
								'expire' => $result['expire_seconds'],
								'createtime' => TIMESTAMP,
								'status' => '1',
								'type' => 'scene',
							);
							pdo_insert('qrcode', $insert);
							$qrurl=$result['url'];
						}else{
							$qrurl=$_W['siteroot']."app/".$this->createMobileUrl('view', array('rid' => $rid,'id' => $voteuser['id']));
						}
					}
					//生成二维码来自efwww-com
				}else{
					$qrurl=$_W['siteroot']."app/".$this->createMobileUrl('view', array('rid' => $rid,'id' => $voteuser['id']));
				}
				
				QRcode::png($qrurl, $QR_path, QR_ECLEVEL_L, 5, 3, true);
				$pic_path = tomedia($poster."qr.png");
				
				break;
		}
			// $start_x,$start_y copy图片在背景中的位置  
			// 0,0 被copy图片的位置  
			// $pic_w,$pic_h copy后的高度和宽度  
			if($data['type']!="name"){
				$resource=$this->get_resource($pic_path);
				imagecopyresized($background, $resource, $data['left'] * 2, $data['top'] * 2, 0, 0, $data['width'] * 2, $data['height'] * 2, imagesx($resource), imagesy($resource)); // 最后两个参数为原始图片宽度和高度，倒数两个参数为copy时的图片宽度和高度 
				imagejpeg($background,ATTACHMENT_ROOT .$poster);	
				ImageDestroy($resource);
			}
	}
	ImageDestroy($background);		
	if(file_exists(ATTACHMENT_ROOT .$poster."qr.png")){
		@unlink (ATTACHMENT_ROOT .$poster."qr.png"); 
	}	
}
$_W['page']['sitename'] = "个人海报";
include $this->template('poster');
