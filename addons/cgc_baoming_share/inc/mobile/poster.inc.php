<?php
global $_W, $_GPC;
require_once IA_ROOT . "/addons/" . $this->modulename . "/inc/poster_common.php";
$uniacid = $_W['uniacid'];
$id = intval($_GPC['id']);
$op = empty($_GPC['op'])?"display":$_GPC['op'];
$config = $this->settings;
$user_json = getFromUser($settings, $modulename);
$userinfo = json_decode($user_json, true);
$from_user = $userinfo['openid'];

if ($op=="display"){
  if(empty($id)){
   	  message('参数出错！');
  }

  $member = pdo_fetch("SELECT * FROM ". tablename("cgc_baoming_user") 
." WHERE uniacid=$uniacid and activity_id=:activity_id and openid=:openid",
array(':openid'=>$from_user,':activity_id'=>$id));	
  
  if(!$member){
  	  message('用户不存在');
  }
   
  $poster = get_post_data($id);
  if(!$poster){
  	 message('不存在海报设计，请联系管理员！');
  }
  if (empty($member['qrcode_poster']) || $poster['createtime']>$member['poster_time']){    
    $url=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('enter',array('form'=>'login','id'=>$id)), 2);
    $qr_image=gen_qr($url);	
	
	$poster['openid']=$member['openid'];
    $poster['headimgurl']=$member['headimgurl'];
    $poster['nickname']=$member['nickname'];
    $poster['poster_time']=time();
    
    $ret = createBarcode($poster,$qr_image);
    if ($ret['code'] != 1) {
      message($ret['msg']);
    }
    $cgc_baoming_user=new cgc_baoming_user();
    $cgc_baoming_user->modify($member['id'], array("qrcode_poster"=>$ret['msg'],"poster_time"=>time()));
    $qrcode = tomedia($ret['msg']);
  } else {
    $qrcode=tomedia($member['qrcode_poster']);
  }
  include $this->template('poster');
}


