<?php

global $_W,$_GPC;

$weid=$_W['uniacid'];

$member=$this->get_member();

$op=empty($_GPC['op'])?"display":$_GPC['op'];

$quan=$this->get_quan();


$mid=$member['id'];
$settings =$this->settings;
$config=$settings;


if($op=='display') {
  include $this->template('free_fabu');
  exit();
} 

if($op=='add') {
  $content = $_GPC['content'];
  $link = $_GPC['link'];
  // 内容验证
  if(empty($content)){
    $this->returnError('请说点儿什么吧~');
  }
  
  if($this->text_len($content)>5000){
     $this->returnError('内容不能超过5000字哦~');
   }

  // link格式化
  if(!empty($link)){
    if (!preg_match("/^(http|ftp):/", $link)){
      $link='http://'.$link;
	}
  }
  
  if($this->text_len($link)>500){
    $this->returnError('链接内容超长啦！');
   }
   
  // 处理图片
  $images=$_GPC['images'];
  
  // 从微信服务器下载用户上传的图片
  if(!empty($images) && count($images)>0){
    load()->func('file');
	$down_images=array();
	// 从微信服务器下载图片
	  $WeiXinAccountService = WeiXinAccount :: create($_W['acid']);  
    foreach($images as $imgid){
	  $ret=$WeiXinAccountService->downloadMedia(array(
									'media_id'=>$imgid,
									'type'=>'image'
	 ));

	  if(is_error($ret)){
	    $this->returnError('图片上传失败:'.$ret['message']);
	   }

	  if($settings['is_qniu']==1){
	    $ret=$this->VP_IMAGE_SAVE($ret);
	    if(!empty($ret['error'])){
	      $this->returnError('上传图片失败:'.$ret['error']);
	   }
	   $down_images[]=$ret['image'];
	 }else{
	   $down_images[]=$ret;
	 }
   }
   $images = iserializer($down_images);
  }
  
  
  
  $status=!empty($quan['shenhe'])?"3":"1";
  
  $data=array(
			'weid'=>$weid,
			'quan_id'=>$quan['id'],
			'mid'=>$mid,
			'content'=>$content,
			'images'=>$images,
			'link'=>$link,
			'total_num'=>0,
			'total_amount'=>0,
			'hot_time'=>0,
			'top_level'=>0,
			'fee'=>0,
			'total_pay'=>0,
			'status'=>$status,
			'views'=>0,
			'links'=>0,
			'rob_amount'=>0,
			'rob_users'=>0,
			'create_time'=>time(),
			'publish_time'=>time(),
			'nickname'=>$member['nickname'],
			'headimgurl'=>$member['headimgurl'],
			'openid'=>$member['openid'],
			'model'=>3,
		);
	pdo_insert('cgc_ad_adv', $data);
    $quan_id = pdo_insertid();
    if($quan_id>0){
	  $this->returnSuccess('发布成功！',json_encode(array('id'=>$quan_id)));
	}else{
	  $this->returnError('发表失败，请重试');
	}
 }
 