<?php

  $fid = $_GPC['fid'];


  $sql = "SELECT a.*,b.nickname,b.headimgurl as avatar,b.is_shouc FROM ".tablename(GARCIA_PREFIX.'fabu')." a
  LEFT JOIN ".tablename(GARCIA_PREFIX."member")." b on a.openid=b.openid
  where a.weid=".$this->weid." and a.id=".$fid;
  $config = pdo_fetch($sql);
  $_res_img =  ATTACHMENT_ROOT.'/qrcode_qsc_'.$this->weid.'_'.$fid.'.png';
  $_res_img_url = "qrcode_qsc_".$this->weid.'_'.$fid.'.png';
  if(!file_exists($_res_img)){
    $errorCorrectionLevel = 'L';//容错级别
    $matrixPointSize = 6;//生成图片大小
    $value = $_W['siteroot'].'/'.$this->createMobileUrl('detail',array('id'=>$fid)); //二维码内容
    //生成二维码图片
     QRcode::png($value,$_res_img, $errorCorrectionLevel, $matrixPointSize, 2);
     //输出图片
     imagepng($QR,  $_res_img);
  }

  $url = tomedia($_res_img_url);


   include $this->template('qrcode/qrcode');

 ?>
