<?php
//分享文章模式
global $_W, $_GPC;
$weid = $_W['uniacid'];
$quan_id = intval($_GPC['quan_id']);
$id = intval($_GPC['id']);
$member = $this->get_member();
$from_user = $member['openid'];
$quan = $this->get_quan();
$type_list = $this->get_type_list();
$config = $this->settings;
$settings = $this->module['config'];
//手续费
$handling_fee = floatval($quan['handling_fee5']);
if($handling_fee>0){
	$quan['percent'] = $handling_fee;
}
$mid = $member['id'];
$op = empty($_GPC['op']) ? "display" : $_GPC['op'];
$config = $this->settings;
if ($op == 'display') {
	$id=$_GPC['id']; 
    if (!empty($id)){
       $adv=pdo_fetch("SELECT * FROM ".tablename('cgc_ad_adv')." where weid=$weid and id=$id and quan_id=$quan_id and del=0");
    }
    $yure = pdo_fetchall("SELECT * FROM " . tablename('cgc_ad_yure') . " WHERE weid=" . $weid . " AND quan_id=" . $quan_id . " ORDER BY fee ASC ");
    $yure_arr = array();
    if (!empty($yure)) {
        foreach ($yure as $key => $value) {
            $yure_arr[$value['fee']] = $value['time'];
        }
    }
    include $this->template('share_fabu');
    exit();
}
if ($op == 'add') {
	$id = $_GPC['id'];
    $content = $_GPC['content'];
    $link = $_GPC['link'];
    $telphone = $_GPC['telphone']; // 联系电话
    $total_num = intval($_GPC['total_num']); // 红包数（个）
    $total_amount = floatval($_GPC['total_amount']); // 红包总额（元）
    $hot_time = intval($_GPC['hot_time']); // 预热时间（秒）
    $fee = floatval($_GPC['fee']); // 服务费（元）
    $total_pay = floatval($_GPC['total_pay']); // 应付总额（元）
    if($hot_time>60){
		$this -> returnError('预热时间不能超过60秒~');
	}
    // 内容验证
    if (empty($content)) {
        $this->returnError('请说点儿什么吧~');
    }
    if ($this->text_len($content) > 5000) {
        $this->returnError('内容不能超过5000字哦~');
    }
    if ($this->text_len($link) > 500) {
        $this->returnError('链接内容超长啦！');
    }
    if ($link && !preg_match("/^(http|ftp):/", $link)) {
        $link = 'http://' . $link;
    }
    if ($this->text_len($_GPC['share_desc']) > 33) {
        $this->returnError('描述字符太长');
    }
    // 广告总额验证
    if (empty($total_amount) || $total_amount <= 0) {
        $this->returnError('请填广告金额');
    }
    $quan['total_min5'] = empty($quan['total_min5']) ? 1 : $quan['total_min5'];
    $quan['total_max5'] = empty($quan['total_max5']) ? 200 : $quan['total_max5'];
    $quan['avg_min5'] = empty($quan['avg_min5']) ? 0.01 : $quan['avg_min5'];
    if ($total_amount < $quan['total_min5']) {
        $this->returnError('广告金额不能低于' . $quan['total_min5'] . '元');
    }
    if ($total_amount > $quan['total_max5']) {
        $this->returnError('广告金额不能超过' . $quan['total_max5'] . '元');
    }
    // 红包个数验证
    if (empty($total_num) || $total_num < 1) {
        $this->returnError('请填写份数');
    }
    if ($total_num > intval($total_amount / $quan['avg_min5'])) {
        $this->returnError($total_amount . '元最多可分' . intval($total_amount / $quan['avg_min5']) . '份');
    }
    $least_amount = max($quan['total_min5'], $total_num * $quan['avg_min5']);
    if ($total_amount < $least_amount) {
        $this->returnError('红包总金额不能低于' . $least_amount . '元');
    }
    $read_fee = intval($_GPC['read_num'])*($_GPC['read_unit_price']);
    $the_fee = (($total_amount+$read_fee) * $quan['percent']) / 100;
   /* if ($the_fee != $fee) {
        $this->returnError('服务费已变化，请刷新后重新发布');
    }*/
    // 应付总额验证
    $the_pay = $total_amount + $read_fee + $the_fee;
   /* if (abs($the_pay - $total_pay) > 0.1) {
      $this->returnError($the_pay . '金额计算出错，请刷新后重新发布' . $total_pay);
    }*/
    // 如果广告总额大于等于置顶线，则需要设置置顶级别
    $top_level = 0;
    if ($quan['top_line'] > 0 && ($total_amount) >= $quan['top_line']) {
        $top_level = $total_amount * 100;
    }
    // 处理图片
    $images = $_GPC['images'];
    // 从微信服务器下载用户上传的图片
    if (!empty($images) && count($images) > 0) {
        load()->func('file');
        $down_images = array();
        // 从微信服务器下载图片
        $WeiXinAccountService = WeiXinAccount::create($_W['oauth_account']);
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
	            if ($config['is_qniu'] == 1 && !preg_match("/^(http):/", $ret) && empty($_W['setting']['remote']['type'])) {
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
    if ($_GPC['allocation_way'] == '1') {
        //生成平均分配方案
        $plan = $this->red_average_plan($total_amount, $total_num);
    } else {
        // 生成随机分配方案
        $plan = $this->red_plan($total_amount, $total_num, $quan['avg_min5']);
    }
    $plan = implode(',', $plan);
    $data = array(
        'weid' => $weid,
        'quan_id' => $quan['id'],
        'mid' => $mid,
        'content' => $content,
        'images' => $images,
        'link' => $link, //详情直接就是分享链接
        'telphone' => $telphone,
        'total_num' => $total_num,
        'total_amount' => $the_pay-$the_fee,
        'hot_time' => $hot_time,
        'top_level' => $top_level,
        'fee' => $the_fee,
        'total_pay' => $the_pay,
        'status' => 0,
        'views' => 0,
        'links' => 0,
        'rob_amount' => 0,
        'rob_users' => 0,
        'rob_plan' => $plan,
        'create_time' => time() ,
        'openid' => $member['openid'],
        'nickname' => $member['nickname'],
        'headimgurl' => $member['headimgurl'],
        'model' => 5,
        'share_desc' => $_GPC['share_desc'],
        'allocation_way' => $_GPC['allocation_way'],
        'title' => $_GPC['title'],
        'share_desc' => $_GPC['share_desc'],
        'city' => $_GPC['city'],
        'qr_code' => $member['qr_code'],
        'read_num' => intval($_GPC['read_num']),
        'read_unit_price' => ($_GPC['read_unit_price']),
         'summary' => trim($_GPC['summary']),
        'title' => trim($_GPC['summary']),
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
				$redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('share_detail',array('cmd'=>'preview','quan_id'=>$quan_id,'id'=>$adv_id,'model'=>5)), 2);
				$this->returnSuccess('',$redirect,2);
			}else if($_GPC['cmd']=='save'){
				//$redirect=$_W['siteroot'] . 'app/' . substr($this->createMobileUrl('fabu',array('quan_id'=>$quan_id,'id'=>$adv_id,'model'=>1)), 2);
				$this->returnSuccess('',$adv_id,1);
			}
		}
    	
        $tid = TIMESTAMP . $adv_id;
        $params = array(
            'tid' => $tid, //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            'ordersn' => $adv_id, //收银台中显示的订单号
            'title' => $quan['aname'] . '投放广告', //收银台中显示的标题
            'fee' => $the_pay, //收银台中显示需要支付的金额,只能大于 0
            'openid' => $from_user,
            'pay_type' => $this->settings['pay_type']
        );
        
        //微信余额支付 
		if (!empty($settings['ye_pay']) && $the_pay <= $member['credit']) {
			$params['adv_id'] = $adv_id;
			$this->returnSuccess('', json_encode($params), 2,'ajax');
		}
        
        //调用pay方法
        $params = $this->payz($params);
        if ($this->settings['pay_type'] == 1 || $config['pay_type'] == 2) {
            $this->returnSuccess('', json_encode($params));
        }
        $this->returnSuccess('', base64_encode(json_encode($params)));
    } else {
        $this->returnError('发表失败，请重试');
    }
}

