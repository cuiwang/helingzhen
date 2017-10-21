<?php

global $_W, $_GPC;
$weid = $_W['uniacid'];
$member = $this->get_member();
$op = empty($_GPC['op']) ? "display" : $_GPC['op'];
$quan = $this->get_quan();
$mid = $member['id'];
$settings = $this->settings;
$quan_id = intval($_GPC['quan_id']);
$config = $settings;
$type_list = $this->get_type_list();
if ($op == 'display') {
	$id=$_GPC['id']; 
    if (!empty($id)){
       $adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_adv')." where weid=$weid and id=$id and quan_id=$quan_id and del=0");
    }
    include $this->template('free_fabu');
    exit();
}
if ($op == 'add') {
	$id = $_GPC['id'];
    $content = $_GPC['content'];
    $link = $_GPC['link'];
    // 内容验证
    if (empty($content)) {
        $this->returnError('请说点儿什么吧~');
    }
    if ($this->text_len($content) > 5000) {
        $this->returnError('内容不能超过5000字哦~');
    }
    // link格式化
    if (!empty($link)) {
        if (!preg_match("/^(http|ftp):/", $link)) {
            $link = 'http://' . $link;
        }
    }
    if ($this->text_len($link) > 500) {
        $this->returnError('链接内容超长啦！');
    }
    // 处理图片
    $images = $_GPC['images'];
    // 从微信服务器下载用户上传的图片
    if (!empty($images) && count($images) > 0) {
        load()->func('file');
        $down_images = array();
        // 从微信服务器下载图片
        $WeiXinAccountService = WeiXinAccount::create($_W['acid']);
        foreach ($images as $imgid) {
        	// 需要判断是微信media_id还是已存在的文件路径
			if(strpos($imgid, 'images/')===0){
				$down_images[]=$imgid;
			}else{
	            $ret = $WeiXinAccountService->downloadMedia(array(
	                'media_id' => $imgid,
	                'type' => 'image'
	           ),true);
	            if (is_error($ret)) {
	                $this->returnError('图片上传失败:' . $ret['message']);
	            }
	            if ($settings['is_qniu'] == 1 && empty($_W['setting']['remote']['type'])) {
	                $ret = $this->VP_IMAGE_SAVE($ret);
	                if (!empty($ret['error'])) {
	                    $this->returnError('上传图片失败:' . $ret['error']);
	                }
	                $down_images[] = $ret['image'];
	            } else {
	                $down_images[] = $ret;
	            }
			}
        }
        $images = iserializer($down_images);
    }
    $status = !empty($quan['shenhe']) ? "3" : "1";
    $data = array(
        'weid' => $weid,
        'quan_id' => $quan['id'],
        'mid' => $mid,
        'content' => $content,
        'images' => $images,
        'link' => $link,
        'total_num' => 0,
        'total_amount' => 0,
        'hot_time' => 0,
        'top_level' => 0,
        'fee' => 0,
        'total_pay' => 0,
        'status' => $status,
        'views' => 0,
        'links' => 0,
        'rob_amount' => 0,
        'rob_status' => 1,
        'rob_users' => 0,
        'create_time' => time() ,
        'publish_time' => time() ,
        'nickname' => $member['nickname'],
        'headimgurl' => $member['headimgurl'],
        'openid' => $member['openid'],
        'model' => 3,
         'summary' => trim($_GPC['summary']),
        'title' => trim($_GPC['summary']),
         'qr_code' => $member['qr_code'],
		 'telphone' => $member['telphone'],
    );
    $data['info_type_id'] = $_GPC['info_type_id'];
	$data['parent_type_id'] = $_GPC['parent_type_id'];
    
    if($id>0){
		pdo_update('cgc_ad_adv', $data, array('id'=>$id));
		$adv_id = $id;
	}else{
		pdo_insert('cgc_ad_adv', $data);
		$adv_id = pdo_insertid();
	}
    if ($adv_id > 0) {
    	if(!empty($_GPC['cmd'])){
			if($_GPC['cmd']=='preview'){
				$redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('free_detail',array('cmd'=>'preview','quan_id'=>$quan_id,'id'=>$adv_id,'model'=>3)), 2);
				$this->returnSuccess('',$redirect,2);
			}else if($_GPC['cmd']=='save'){
				//$redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('fabu',array('quan_id'=>$quan_id,'id'=>$adv_id,'model'=>1)), 2);
				$this->returnSuccess('',$adv_id,1);
			}
		}
		
        $data['id'] = $adv_id;
        if (!empty($quan['shenhe'])) {
            $this->check_msg($config, $quan, $data);
        }
        $this->returnSuccess('发布成功！', json_encode(array(
            'id' => $adv_id
        )));
    } else {
        $this->returnError('发表失败，请重试');
    }
}

