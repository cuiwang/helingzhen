<?php
/**
 * 课程详情页
 * ============================================================================
 */
 
/* 课程id */
$id = intval($_GPC['id']);
/* 点播章节id */
$sectionid = intval($_GPC['sectionid']);
$uid = intval($_GPC['uid']);

$url = $_W['siteroot'] .'app/'. $this->createMobileUrl('lesson', array('id'=>$id,'sectionid'=>$sectionid,'uid'=>$uid));
if (isset($_COOKIE[$this->_auth2_openid])) {
	$openid = $_COOKIE[$this->_auth2_openid];
	$nickname = $_COOKIE[$this->_auth2_nickname];
	$headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
	if (isset($_GPC['code'])) {
		$userinfo = $this->oauth2();
		if (!empty($userinfo)) {
			$openid = $userinfo["openid"];
			$nickname = $userinfo["nickname"];
			$headimgurl = $userinfo["headimgurl"];
		} else {
			message('授权失败!');
		}
	} else {
		if (!empty($this->_appsecret)) {
			$this->getCode($url);
		}
	}
}


/* 基本设置 */
$setting = pdo_fetch("SELECT isfollow,qiniu,qcloud,print_error,sharelesson,adv,sitename,vipdiscount,footnav,stock_config,copyright,qrcode,is_sale,front_color,teacherlist FROM " . tablename($this->table_setting) . " WHERE uniacid =:uniacid LIMIT 1", array(':uniacid' => $uniacid));
$config = $this->module['config'];

/* 更新微课程会员信息 */
$this->updatelessonmember($openid,$uid);

/* 微课堂会员信息 */
$lessonmember = pdo_fetch("SELECT a.*,b.follow FROM " .tablename($this->table_member). " a LEFT JOIN " .tablename('mc_mapping_fans'). " b ON a.openid=b.openid WHERE a.uniacid='{$uniacid}' AND a.openid='{$openid}'");

$lesson = pdo_fetch("SELECT a.*,b.teacher,b.qq,b.qqgroup,b.qqgroupLink,b.weixin_qrcode,b.teacherphoto,b.teacherdes FROM " .tablename($this->table_lesson_parent). " a LEFT JOIN " .tablename($this->table_teacher). " b ON a.teacherid=b.id WHERE a.uniacid='{$uniacid}' AND a.id='{$id}' AND a.status!=0 LIMIT 1");
if(empty($lesson)){
	message("该课程已下架，您可以看看其他课程~", "", "error");
}

$poster = json_decode($lesson['poster']);
$rand = rand(0, count($poster)-1);
$poster = $poster[$rand];

/* 显示课程价格 */
$isdiscount = 0;
if($lessonmember['vip']==1){
	if($lesson['vipview']==1){
		$buybtn = 'close';
	}

	if($setting['vipdiscount']>0){ /* 折扣开启 */
		if($lesson['price']>0){
			if($lesson['isdiscount']==1){/* 课程开启折扣 */
				if($lesson['vipdiscount']>0){/* 使用课程单独折扣 */
					$price = sprintf("%.2f", $lesson['price']*$lesson['vipdiscount']*0.01, 2);
				}else{/* 使用课程全局折扣 */
					$price = sprintf("%.2f", $lesson['price']*$setting['vipdiscount']*0.01, 2);
				}
				$isdiscount = 1;
			}else{
				$price = $lesson['price']; /* 课程单方面关闭折扣 */
			}
		}else{
			$price = 0;
		}
	}else{
		$price = $lesson['price']; /* 课程全局关闭折扣 */
	}
}

/* 查询是否收藏该课程 */
$collect = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_collect). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND outid='{$id}' AND ctype=1 LIMIT 1");

/* 查询是否购买该课程 */
$isbuy = pdo_fetch("SELECT * FROM " .tablename($this->table_order). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lessonid='{$id}' AND status>=1 AND paytime>0 LIMIT 1");
if(empty($isbuy) && $lesson['status']=='-1'){
	message("该课程已下架，您可以看看其他课程~");
}

/* 增加会员课程足迹 */
load()->model('mc');
$fansinfo = mc_fansinfo($openid, $_W['acid'], $_W['uniacid']);
$history = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_history). " WHERE uniacid='{$uniacid}' AND lessonid='{$id}' AND openid='{$openid}' LIMIT 1");
if(empty($history) && !empty($openid)){
	$insertdata = array(
		'uniacid'  => $uniacid,
		'uid'	   => $fansinfo['uid'],
		'openid'   => $openid,
		'lessonid' => $id,
		'addtime'  => time(),
	);
	pdo_insert($this->table_lesson_history, $insertdata);
}else{
	pdo_update($this->table_lesson_history, array('addtime'=>time()), array('uniacid'=>$uniacid,'lessonid'=>$id,'openid'=>$openid));
}

/* 标题 */
$title = $lesson['bookname'];

/* 章节列表 */
$section_list = pdo_fetchall("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE uniacid='{$uniacid}' AND parentid='{$id}' AND status=1 ORDER BY displayorder DESC, id ASC");

if($sectionid>0){
	/* 点播章节 */
	$section = pdo_fetch("SELECT * FROM " .tablename($this->table_lesson_son). " WHERE uniacid='{$uniacid}' AND parentid='{$id}' AND id='{$sectionid}' AND status=1 LIMIT 1");
	if(empty($section)){
		message("该章节不存在或已被删除！", "", "error");
	}

	if($lesson['vipview']==1 && $lessonmember['vip']==1){
		$play = true;
	}
	if($section['is_free']==1 || $lesson['price']==0){
		$play = true;
	}
	if($isbuy){
		if($isbuy['validity']==0){
			$play = true;
		}else{
			if($isbuy['validity']>time()){
				$play = true;
			}
		}
	}
	
	if(!$play){
		message("请先购买课程后再学习！", $this->createMobileUrl('lesson', array('id'=>$id)), "warning");
	}

	/**
	 * 视频课程格式
	 * @sectiontype 1.视频章节 2.图文章节 3.音频课程
	 * @savetype	0.其他存储 1.七牛存储 2.内嵌播放代码模式 3.腾讯云存储
	 */
	if(in_array($section['sectiontype'], array('1','3'))){
		$section['audiobg'] = $section['audiobg']?$_W['attachurl'].$section['audiobg']:'../addons/fy_lesson/template/mobile/images/audiobg.jpg';
		if($section['savetype']==1){
			$qiniu = unserialize($setting['qiniu']);
			$section['videourl'] = $this->privateDownloadUrl($qiniu['access_key'],$qiniu['secret_key'],$section['videourl']);

			$rsarr = get_headers($section['videourl']);
			if(!preg_match('/200/',$rsarr[0])){ 
				$errmsg = $this->http_request($section['videourl']);
				if($setting['print_error']){
					echo "<script type=text/javascript>alert('获取视频信息失败，错误代码：{$rsarr[0]}！');</script>";
					print_r(get_headers($section['videourl'],1));
				}
			}
		}elseif($section['savetype']==3){
			$qcloud		 = unserialize($setting['qcloud']);
			$appid		 = $qcloud['appid'];
			$bucket		 = $qcloud['bucket'];
			$secret_id   = $qcloud['secretid'];
			$secret_key  = $qcloud['secretkey'];
			$expired	 = time() + 7200;
			$onceExpired = 0;
			$current	 = time();
			$rdm		 = rand();
			$userid		 = "0";
			$explode	 = explode("/", $section['videourl']);
			$tmp		 = array_reverse($explode);
			$fileid		 = "/".$appid."/".$bucket."/".$tmp[0];

			$srcStr = 'a='.$appid.'&b='.$bucket.'&k='.$secret_id.'&e='.$expired.'&t='.$current.'&r='.$rdm.'&f=';
			$srcStrOnce = 'a='.$appid.'&b='.$bucket.'&k='.$secret_id.'&e='.$onceExpired .'&t='.$current.'&r='.$rdm.'&f='.$fileid;
			$signStr = base64_encode(hash_hmac('SHA1', $srcStr, $secret_key, true).$srcStr);
			$section['videourl'] .= "?sign={$signStr}"; 
		}
	}
	
}else{
	/* 进去章节列表 */
	$record = pdo_fetch("SELECT sectionid FROM " .tablename($this->table_playrecord). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lessonid='{$id}'");

	if($record['sectionid']>0){
		$hissection = pdo_fetch("SELECT title FROM " .tablename($this->table_lesson_son). " WHERE uniacid='{$uniacid}' AND id='{$record['sectionid']}'");
		$hisplayurl = $this->createMobileUrl("lesson",array('id'=>$id,'sectionid'=>$record['sectionid']));
	}
}

/* 脚部广告 */
$avd = unserialize($setting['adv']);
if(!empty($avd)){
	foreach($avd as $key=>$ad){
		if(empty($ad['img'])){
			unset($avd[$key]);
		}
	}
	$advs = array_rand($avd,1);
	$advs = $avd[$advs];
}

/* 分享信息 */
$sharelesson = unserialize($setting['sharelesson']);
$sharelesson['desc'] = $sharelesson['title']?str_replace("【课程名称】","《".$title."》",$sharelesson['title']):substr(strip_tags(htmlspecialchars_decode($lesson['descript'])), 0, 240);
if(empty($section)){
	$sharelesson['title'] = $lesson['bookname'].' - '.$setting['sitename'];
}else{
	$sharelesson['title'] = $section['title'].' - '.$lesson['bookname'].' - '.$setting['sitename'];
}


/* 评价列表 */
$pindex =max(1,$_GPC['page']);
$psize = 5;

$evaluate_list = pdo_fetchall("SELECT a.lessonid,a.bookname,a.nickname,a.grade,a.content,a.reply,a.addtime, b.avatar FROM " .tablename($this->table_evaluate). " a LEFT JOIN " .tablename('mc_members'). " b ON a.uid=b.uid WHERE a.uniacid='{$uniacid}' AND a.lessonid='{$id}' ORDER BY a.addtime DESC, a.id DESC LIMIT " . ($pindex-1) * $psize . ',' . $psize);
foreach($evaluate_list as $key=>$value){
	if($value['grade']==1){
		$evaluate_list[$key]['grade'] = "好评";
		$evaluate_list[$key]['ico'] = " ";
	}elseif($value['grade']==2){
		$evaluate_list[$key]['grade'] = "中评";
		$evaluate_list[$key]['ico'] = "s2";
	}elseif($value['grade']==3){
		$evaluate_list[$key]['grade'] = "差评";
		$evaluate_list[$key]['ico'] = "s3";
	}
	if($value['avatar']=='132' || empty($value['avatar'])){
		$evaluate_list[$key]['avatar'] = MODULE_URL."template/mobile/images/default_avatar.gif";
	}
	$evaluate_list[$key]['addtime'] = date('Y-m-d', $value['addtime']);
}

$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_evaluate) . " WHERE uniacid='{$uniacid}' AND lessonid='{$id}'");

if($op=='display'){
	/* 评价开关 */
	if($isbuy['status']==1){
		$allow_evaluate = true;
		$evaluate_url   = $this->createMobileUrl("evaluate",array('op'=>'display',"orderid"=>$isbuy['id']));
	}else{
		/* 课程价格为免费 或 会员为VIP身份且课程权限为VIP会员免费观看 */
		if($lesson['price']==0 || ($lessonmember['vip']==1 && $lesson['vipview']==1)){
			$already_evaluate = pdo_fetch("SELECT id FROM " .tablename($this->table_evaluate). " WHERE uniacid='{$uniacid}' AND openid='{$openid}' AND lessonid='{$id}' AND orderid=0 ");
			if(empty($already_evaluate)){
				$allow_evaluate = true;
				$evaluate_url   = $this->createMobileUrl("evaluate",array('op'=>'freeorder',"lessonid"=>$id));
			}
		}
	}

	if($section['sectiontype']==2 && $sectionid>0){/* 图文章节 */
		include $this->template('lesson_article');
	}else{
		include $this->template('lesson');
	}
}elseif($op=='ajaxgetlist'){
	echo json_encode($evaluate_list);
}


?>