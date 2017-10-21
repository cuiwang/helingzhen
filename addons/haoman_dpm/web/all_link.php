<?php
global $_W  ,$_GPC;
checklogin();
$uniacid = $_W['uniacid'];


if($_W['isajax']){

	$rid = $_GPC['rid'];
	$itemID = $_GPC['itemID'];
	switch ($itemID) {
		case 1: //微信端消息
			$linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=messagesindex&id='.$rid;
			$imgName = "haomandpm_mess".$_W['uniacid'].$rid;
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

//			$this->QRcode($imgName,$linkUrl,$imgUrl);
			$data = array(
		        'flag' => 1,
		        'url' => $linkUrl,
		        'qrcode' => $imgUrl,
		        'msg' => "",
		    );
			break;

		case 2://报名地址
			$imgName = "haomandpm_bm".$_W['uniacid'].$rid;
			$linkUrl = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=haoman_dpm&do=ve_baoming&id=".$rid;
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
		        'flag' => 1,
		        'url' => $linkUrl,
		        'qrcode' => $imgUrl,
		        'msg' => "",
		    );
			break;

		case 3: //签到地址
			$imgName = "haomandpm_qd".$_W['uniacid'].$rid;
			$linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=index&id='.$rid;
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
		        'flag' => 1,
		        'url' => $linkUrl,
		        'qrcode' => $imgUrl,
		        'msg' => "",
		    );
			break;
        case 4: //惩罚大转盘
            $imgName = "haomandpm_punishment".$_W['uniacid'].$rid;
            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=mob_turnplate&id='.$rid;
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
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => $imgUrl,
                'msg' => "",
            );
            break;
		case 26: //大屏幕链接
			$linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=login&id='.$rid;
			$data = array(
		        'flag' => 1,
		        'url' => $linkUrl,
		        'qrcode' => $qrcode,
		        'msg' => "",
		    );
			break;
        case 5: //抢红包
            $imgName = "haomandpm_qhb".$_W['uniacid'].$rid;
            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=qhbIndex&id='.$rid;
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
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => $imgUrl,
                'msg' => "",
            );
            break;
        case 6: //摇一摇
            $imgName = "haomandpm_yyy".$_W['uniacid'].$rid;
            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=yyyIndex&id='.$rid;
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
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => $imgUrl,
                'msg' => "",
            );
            break;
        case 7: //嘉宾
            $imgName = "haomandpm_jb".$_W['uniacid'].$rid;
            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=showjiabin&id='.$rid;
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
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => $imgUrl,
                'msg' => "",
            );
            break;
        case 8: //投票
            $imgName = "haomandpm_tp".$_W['uniacid'].$rid;
            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=go_toupiao&id='.$rid;
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
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => $imgUrl,
                'msg' => "",
            );
            break;
        case 9: //

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_choujiang&rid='.$rid;

            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 10: //

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_qianghongbao&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 11: //

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_yyy&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 12: //

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_index&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 13: //投票

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_index&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 14: //

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_jiabing&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 15: //

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_tanmu&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 16: //

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_wedding&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 17: //

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_xyh&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
            case 18: //

        $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_xysjh&rid='.$rid;
                $data = array(
                    'flag' => 1,
                    'url' => $linkUrl,
                    'qrcode' => '',
                    'msg' => "",
                );
        break;
        case 19: //投票

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_bp&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 20: //投票

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_ddp&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
            case 25: //投票

        $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_dzp&rid='.$rid;
                $data = array(
                    'flag' => 1,
                    'url' => $linkUrl,
                    'qrcode' => '',
                    'msg' => "",
                );
        break;
            case 21: //投票

        $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_cjx&rid='.$rid;
                $data = array(
                    'flag' => 1,
                    'url' => $linkUrl,
                    'qrcode' => '',
                    'msg' => "",
                );
        break;
            case 22: //投票

        $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_messages&rid='.$rid;
                $data = array(
                    'flag' => 1,
                    'url' => $linkUrl,
                    'qrcode' => '',
                    'msg' => "",
                );
        break;
            case 23: //投票

        $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_3dqd&rid='.$rid;
                $data = array(
                    'flag' => 1,
                    'url' => $linkUrl,
                    'qrcode' => '',
                    'msg' => "",
                );
        break;
            case 24: //投票

        $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_vote&rid='.$rid;
                $data = array(
                    'flag' => 1,
                    'url' => $linkUrl,
                    'qrcode' => '',
                    'msg' => "",
                );
        break;
        case 27: //投票

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_shouqian&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 28: //投票

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_new_index2&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
        case 29: //相册

            $linkUrl = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&m=haoman_dpm&do=dpm_photo_wall&rid='.$rid;
            $data = array(
                'flag' => 1,
                'url' => $linkUrl,
                'qrcode' => '',
                'msg' => "",
            );
            break;
		default:
			$data = array(
		        'flag' => 100,
		        'msg' => "请求的参数有误",
		    );
			break;
	}


	echo json_encode($data);
	exit;

}else{

	$_GPC['do']='Link';
	load()->model('reply');
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = "uniacid = :uniacid and `module` = :module";
	$params = array();
	$params[':uniacid'] = $_W['uniacid'];
	$params[':module'] = 'haoman_dpm';

	$list = reply_search($sql, $params, $pindex, $psize, $total);
	$pager = pagination($total, $pindex, $psize);

	if (!empty($list)) {
		foreach ($list as &$item) {
			$condition = "`rid`={$item['id']}";
			$item['keyword'] = reply_keywords_search($condition);
		}
	}else{
		message('您还添加回复规则，请先添加再进行这个操作！', url('platform/reply/post',array('m'=>'haoman_dpm')), 'error');
	}
	include $this->template('link');

}

