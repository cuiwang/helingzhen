<?php
global $_W, $_GPC;
require_once IA_ROOT . "/addons/cgc_ad/source/common/poster_common.php";
$weid = $_W['uniacid'];
$quan_id = intval($_GPC['quan_id']);
$id = intval($_GPC['id']);
$member = $this->get_member();
$mid=$member['id'];
$from_user = $member['openid'];
$quan = $this->get_quan();
$mid = $member['id'];
$op = empty($_GPC['op'])?"display":$_GPC['op'];
$config = $this->settings;

if ($op=="display"){
  
  $poster = get_post_data($quan_id);
  if (empty($member['qrcode_poster']) || $poster['createtime']>$member['poster_time']){    
    $url=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('foo',array('form'=>'index','quan_id'=>$quan_id,'pid'=>$mid)), 2);
    $qr_image=gen_qr($url);	

    $poster['headimgurl']=$member['headimgurl'];
    $poster['nickname']=$member['nickname'];
    $poster['poster_time']=time();
    
    $ret = createBarcode($poster,$qr_image);
    if ($ret['code'] != 1) {
      message($ret['msg']);
    }
    $cgc_ad_member=new cgc_ad_member();
    $cgc_ad_member->modify($member['id'], array("qrcode_poster"=>$ret['msg'],"poster_time"=>time()));
    $qrcode = tomedia($ret['msg']);
  } else {
    $qrcode=tomedia($member['qrcode_poster']);
  }
  include $this->template('geren_poster');
}


