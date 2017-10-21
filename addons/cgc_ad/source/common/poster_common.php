<?php



function get_post_data($quan_id){
  $cgc_ad_poster=new cgc_ad_poster();
  $poster=$cgc_ad_poster->getOneByQuan($quan_id);
  return $poster;
}

function getRealData($data) {
  $data['left'] = intval(str_replace('px', '', $data['left'])) * 2;
  $data['top'] = intval(str_replace('px', '', $data['top'])) * 2;
  $data['width'] = intval(str_replace('px', '', $data['width'])) * 2;
  $data['height'] = intval(str_replace('px', '', $data['height'])) * 2;
  $data['size'] = intval(str_replace('px', '', $data['size'])) * 2;
  $data['src'] = tomedia($data['src']);
  return $data;
}

function createImage($imgurl) {
  load()->func('communication');
  $resp = ihttp_request($imgurl);
  return imagecreatefromstring($resp['content']);
}


/**创建图片
 * @param $bg 图片路径
 * @return
 */
function imagecreates($bg) {
	$bgImg = @imagecreatefromjpeg($bg);
	if (FALSE == $bgImg) {
		$bgImg = @imagecreatefrompng($bg);
	}
	if (FALSE == $bgImg) {
		$bgImg = @imagecreatefromgif($bg);
	}
	return $bgImg;
}


function hex2rgb($colour) {
	if ($colour[0] == '#') {
		$colour = substr($colour, 1);
	}
	if (strlen($colour) == 6) {
		list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
	} elseif (strlen($colour) == 3) {
		list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
	} else {
		return false;
	}
	$r = hexdec($r);
	$g = hexdec($g);
	$b = hexdec($b);
	return array('red' => $r, 'green' => $g, 'blue' => $b);
}
 


function createImageUrl($param, $qr_file) {
	global $_W;
	load()->func('file');
	load()->func('logging');
	$path = "../attachment/images/" . $param['uniacid'] . '/' . date("Y/m/d");
	if (!is_dir($path)) {
		load()->func('file');
		mkdirs($path);
	}
	$target_file = $path . '/qr-image-' . $param['from_user'] . rand() . '.jpg';

	$bg_file = $param['bg'];
	set_time_limit(0);
	@ ini_set('memory_limit', '256M');

	$size = getimagesize(tomedia($bg_file));
	$target = imagecreatetruecolor($size[0], $size[1]);
	
	$bg = createImage(tomedia($bg_file));

	imagecopy($target, $bg, 0, 0, 0, 0,$size[0], $size[1]);
	imagedestroy($bg);

	$data = json_decode(str_replace('&quot;', "'", $param['data']), true);
	foreach ($data as $d) {
		$d = getRealData($d);
		if ($d['type'] == 'head') {
			$avatar = preg_replace('/\/0$/i', '/96', $param['headimgurl']);
			$target = mergeImage($target, $d, $avatar);
		} else
			if ($d['type'] == 'img') {
				$target = mergeImage($target, $d, $d['src']);
			} else
				if ($d['type'] == 'qr') {

					$target = mergeImage($target, $d, tomedia($qr_file));
				} else
					if ($d['type'] == 'nickname') {
						$target = mergeText($target, $d, $param['nickname']);
					}
	}

	imagejpeg($target, $target_file);

	imagedestroy($target);
	$target_file=tomedia($target_file);
	return $target_file;
}

function mergeImage($target, $data, $imgurl) {
	$img = createImage($imgurl);
	$w = imagesx($img);
	$h = imagesy($img);
	imagecopyresized($target, $img, $data['left'], $data['top'], 0, 0, $data['width'], $data['height'], $w, $h);
	imagedestroy($img);
	return $target;
}

/*function mergeImage($bg, $qr, $out, $param) {
	list ($bgWidth, $bgHeight) = getimagesize($bg);
	list ($qrWidth, $qrHeight) = getimagesize($qr);
	$bgImg = imagez($bg);
	$qrImg = imagez($qr);
	$ret = imagecopyresized($bgImg, $qrImg, $param['left'], $param['top'], 0, 0, $param['width'], $param['height'], $qrWidth, $qrHeight);
	if (!$ret) {
		return false;
	}
	ob_start();
	imagejpeg($bgImg, NULL, 100);
	$contents = ob_get_contents();
	ob_end_clean();
	imagedestroy($bgImg);
	imagedestroy($qrImg);
	$fh = fopen($out, "w+");
	fwrite($fh, $contents);
	fclose($fh);
	return true;
}
*/

/*function mergeImage($target, $imgurl , $data) {
	$img = imagecreates($imgurl);
	$w = imagesx($img);
	$h = imagesy($img);
	imagecopyresized($target, $img, $data['left'], $data['top'], 0, 0, $data['width'], $data['height'], $w, $h);
	imagedestroy($img);
	return $target;
}*/

function mergeText($target, $data, $text) {
	$font = IA_ROOT . '/addons/cgc_ad/msyhbd.ttf';
	$colors = hex2rgb($data['color']);
	$color = imagecolorallocate($target, $colors['red'], $colors['green'], $colors['blue']);
	imagettftext($target, $data['size'], 0, $data['left'], $data['top'] + $data['size'], $color, $font, $text);
	return $target;
}


function imagez($bg) {
	$bgImg = @ imagecreatefromjpeg($bg);
	if (FALSE == $bgImg) {
		$bgImg = @ imagecreatefrompng($bg);
	}
	if (FALSE == $bgImg) {
		$bgImg = @ imagecreatefromgif($bg);
	}
	return $bgImg;
}

function barCodeCreateFixed($barcode) {
	unset ($barcode['expire_seconds']);
	if (empty ($barcode['action_info']['scene']['scene_id']) || empty ($barcode['action_name'])) {
		return error('1', 'Invalid params');
	}
	$token = $this->fetch_token();
	$url = sprintf("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s", $token);
	$response = ihttp_request($url, json_encode($barcode));
	if (is_error($response)) {
		return $response;
	}
	$content = @ json_decode($response['content'], true);
	if (empty ($content)) {
		return error(-1, "接口调用失败, 元数据: {$response['meta']}");
	}
	if (!empty ($content['errcode'])) {
		return error(-1, "访问微信接口错误, 错误代码: {$content['errcode']}, 错误信息: {$content['errmsg']},错误详情：{$this->error_code($content['errcode'])}");
	}
	return $content;
}

function createBarcode($obj, $qrimg) {
	global $_W;
	$ret = array ();

	$qr_img = createImageUrl($obj, $qrimg);
	$promote_qr['qr_img'] = str_replace("../attachment/", "", $qr_img);
	$qr_img = tomedia($qr_img);
	/* if(!empty($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
	     $remotestatus = file_remote_upload($promote_qr['qr_img']); //上传图片到远程
	     if (is_error($remotestatus)) {
	         $ret2 = array('status' => 0, 'info' => '远程附件上传失败，请检查配置并重新上传\'');
	         file_delete($promote_qr['qr_img']);
	         die(json_encode($ret2));
	     } else {
	         file_delete($promote_qr['qr_img']);
	         $url = tomedia($promote_qr['qr_img'],false);  // 远程图片的访问URL
	     }
	 }*/
	// $update['qr_img']=$url;

	$ret = array (
		"code" => "1",
		"msg" => $qr_img
	);

	return $ret;
}

function create_follow_Barcode($obj) {
	global $_W;
	$ret = array ();
	$barcode = array (
		'expire_seconds' => '',
		'action_name' => '',
		'action_info' => array (
			'scene' => array (
				'scene_id' => ''
			),

			
		),

		
	);
	$uniacccount = WeAccount :: create($obj['acid']);

	$qrcode = pdo_fetch("SELECT * FROM " . tablename('xxx') . " WHERE uniacid = '{$obj['uniacid']}' and cardid='{$obj['cardid']}' and openid='{$obj['from_user']}'");
	if (empty ($qrcode)) {
		$sceneid = pdo_fetchcolumn("SELECT qrcid FROM " . tablename('qrcode') . " WHERE acid = :acid and model=2 ORDER BY qrcid DESC LIMIT 1", array (
			':acid' => $obj['acid']
		));
		$barcode['action_info']['scene']['scene_id'] = intval($sceneid) + 1;
		if ($barcode['action_info']['scene']['scene_id'] > 100000) {
			$ret = array (
				"code" => "-1",
				"msg" => '抱歉，永久二维码已经生成最大数量，请先删除一些。'
			);
			return $ret;
		}

		$barcode['action_name'] = 'QR_LIMIT_SCENE';
		$result = $uniacccount->barCodeCreateFixed($barcode);
		if (is_error($result)) {
			$ret = array (
				"code" => "-1",
				"msg" => $result['message']
			);
			return $ret;
		}
		$qrimg = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $result['ticket'];

		$weid = $obj['uniacid'];
		$acid = $obj['acid'];

		$ims_qrcode = array (
			'uniacid' => $weid,
			'acid' => $acid,
			'qrcid' => $barcode['action_info']['scene']['scene_id'],
			"model" => 2,
			"name" => "zz",
			"keyword" => 'zz',
			"expire" => 0,
			"createtime" => time(),
			"status" => 1,
			'url' => $result['url'],
			"ticket" => $result['ticket']
		);
		pdo_insert('qrcode', $ims_qrcode);

		$data = array (
			"uniacid" => $obj['uniacid'],
			'acid' => $acid,
			"openid" => $obj['from_user'],
			"cardid" => $obj['cardid'],
			'sceneid' => $barcode['action_info']['scene']['scene_id'],
			'ticket' => $result['ticket'],
			'qr_img' => $qrimg,
			'status' => 1,
			'createtime' => TIMESTAMP,
			'url' => $result['url']
		);
		$qrcode = pdo_insert("zz", $data);
		if (empty ($qrcode)) {
			$ret = array (
				"code" => "-1",
				"msg" => "插入二维码表错误"
			);
			return $ret;
		}
		$qr['id'] = pdo_insertid();
	} else {
		$qr['id'] = $qrcode['id'];
		$qrimg = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $qrcode['ticket'];
	}
	$update['qr_img'] = createImageUrl($obj, $qrimg);
	$promote_qr['qr_img'] = str_replace("../attachment/", "", $update['qr_img']);
	$url = tomedia($update['qr_img']);
	if (!empty ($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
		$remotestatus = file_remote_upload($promote_qr['qr_img']); //上传图片到远程
		if (is_error($remotestatus)) {
			$ret2 = array (
				'status' => 0,
				'info' => '远程附件上传失败，请检查配置并重新上传\''
			);
			file_delete($promote_qr['qr_img']);
			die(json_encode($ret2));
		} else {
			file_delete($promote_qr['qr_img']);
			$url = tomedia($promote_qr['qr_img'], false); // 远程图片的访问URL
		}
	}
	$update['qr_img'] = $url;

	$ret = array (
		"code" => "1",
		"msg" => $update['qr_img']
	);
	pdo_update('zz', $update, array (
		'id' => $qr['id']
	));
	return $ret;
}

/*function copyfiles($file1, $file2){
    $contentx = @file_get_contents($file1);
    $openedfile = fopen($file2, "w");
    fwrite($openedfile, $contentx);
    fclose($openedfile);
    if ($contentx === FALSE) {
        $status = false;
    } else $status = true;
    return $status;
}*/

function gen_qr($url) {
	global $_GPC;
	$dir = "../attachment/cgc_more_mp/image";
	load()->func('file');
	mkdirs($dir);
	$file_path = $dir . '/' . md5(rand()) . ".jpeg";
	include MB_ROOT . '/source/common/phpqrcode.php';
	QRcode :: png($url, $file_path, QR_ECLEVEL_Q, 4);
	return $file_path;
}

function url_base64_encode($str) {
	$str = base64_encode($str);
	$code = url_encode($str);
	return $code;
}
function url_encode($code) {
	$code = str_replace('+', "!", $code);
	$code = str_replace('/', "*", $code);
	$code = str_replace('=', "", $code);
	return $code;
}
function url_base64_decode($code) {
	$code = url_decode($code);
	$str = base64_decode($code);
	return $str;
}
function url_decode($code) {
	$code = str_replace("!", '+', $code);
	$code = str_replace("*", '/', $code);
	return $code;
}
function pencode($code, $seed = 'undefiend9876543210') {
	$c = url_base64_encode($code);
	$pre = substr(md5($seed . $code), 0, 3);
	return $pre . $c;
}
function pdecode($code, $seed = 'undefiend9876543210') {
	if (empty ($code) || strlen($code) <= 3) {
		return "";
	}
	$pre = substr($code, 0, 3);
	$c = substr($code, 3);
	$str = url_base64_decode($c);
	$spre = substr(md5($seed . $str), 0, 3);
	if ($spre == $pre) {
		return $str;
	} else {
		return "";
	}
}