<?php


    if($action=='getcode'){
        $tid = $_GPC['id'];
        $value = $_W['siteroot'].'app/'.substr($this->createMobileUrl('detail',array('id'=>$tid)),2); //二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        $_res_img =  ATTACHMENT_ROOT.'/qrcode_qsc_xxm_'.md5($this->weid.'_'.$tid).'.png';
        $_res_img_url = "../attachment/qrcode_qsc_xxm_".md5($this->weid."_".$tid).".png";
        if(file_exists($_res_img)){
            // _success(array('res'=>$value));
        }


        //生成二维码图片
         QRcode::png($value,$_res_img, $errorCorrectionLevel, $matrixPointSize, 2);
         $logo = $this->sys['logo'];//准备好的logo图片
        //  $_logo = ATTACHMENT_ROOT.'/qrcode_qsc_logo_'.$this->weid.'.png';
         $img=  file_get_contents(tomedia($logo));
         file_put_contents($_logo,$img);
         $logo = $_logo;
         $QR = $_res_img;//已经生成的原始二维码图
        if ($logo !== FALSE) {
          $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
           $QR_width = imagesx($QR);//二维码图片宽度

          $QR_height = imagesy($QR);//二维码图片高度
          $logo_width = imagesx($logo);//logo图片宽度
          $logo_height = imagesy($logo);//logo图片高度
          $logo_qr_width = $QR_width / 5;
          $scale = $logo_width/$logo_qr_width;
          $logo_qr_height = $logo_height/$scale;
          $from_width = ($QR_width - $logo_qr_width) / 2;
          //重新组合图片并调整大小
          imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
          $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        imagepng($QR,  $_res_img);
      // return $_res_img_url;
      _success(array('res'=>$_res_img_url));
    }
    else{
        _fail(array('msg'=>'not found function'));
    }

 ?>
