<?php
global $_W, $_GPC;
$openid = $_W['fans']['from_user'];
if(empty($openid)){
	message('请在微信浏览器中打开！');
}
$setting = $this->setting;
$referer = $_SERVER['HTTP_REFERER'];
$qudao = trim($_GPC['qudao']);
$qudaoarray = array("niubeitaoke","renren","super","kefu","zhiyunwuye");
if(!in_array($qudao,$qudaoarray)){
	message('渠道来源不正确！');
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_SANFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND lastcon != '' AND qudao = '{$qudao}'");
	$allpage = ceil($total/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_SANFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND lastcon != '' AND qudao = '{$qudao}' ORDER BY notread DESC,lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	$isajax = intval($_GPC['isajax']);
	if($isajax == 1){
		$html = '';
		foreach($chatlist as $kk=>$vv){
			if($vv['msgtype'] == 2){
				$con = '<span style="color:#900;">[图片消息]</span>';
			}elseif($vv['msgtype'] == 3){
				$con = '<span style="color:green;">[语音消息]</span>';
			}else{
				$con = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $vv['kefulastcon']);
			}
			$mychatbadge = $vv['notread'] > 0 ? '<span class="mychatbadge">'.$vv['notread'].'</span>' : '';
			$html .= '<div class="item">
						<a href="'.$this->createMobileUrl('sanchat',array('toopenid'=>$vv['fansopenid'],'qudao'=>$qudao)).'">
							<div class="left">
								<div class="img">
									<img src="'.$vv['kefuavatar'].'">
									'.$mychatbadge.'
								</div>
								<div class="text">
									<div class="name">
										'.$vv['kefuonickname'].'
										<span style="color:#999;margin-left:0.1rem;font-size:0.23rem;">'.date("Y-m-d H:i:s",$vv['lasttime']).'</span>
									</div>
									<div class="zu">
										'.$con.'
									</div>
								</div>
							</div>
						</a>
						<a onclick="return confirm(\'确认要删除聊天记录吗？\');return false;" href="'.$this->createMobileUrl('disanfang',array('op'=>'delete','toopenid'=>$vv['fansopenid'],'qudao'=>$qudao)).'">
							<div class="right iconfont">&#xe736;</div>
						</a>
					</div>';
		}
		echo $html;
		exit;
	}
	include $this->template('disanfang');
}elseif($operation == 'search'){
	if(empty($_W['fans']['from_user'])){
		$resarr['error'] = 1;
		$resarr['msg'] = '请在微信浏览器中打开！';
		echo json_encode($resarr);
		exit;
	}
	$nickname = trim($_GPC['nickname']);
	$qudao = trim($_GPC['qudao']);
	if(empty($nickname)){
		$resarr['error'] = 1;
		$resarr['msg'] = '请输入昵称查询！';
		echo json_encode($resarr);
		exit;
	}
	$mcmember = pdo_fetch("SELECT uid,nickname,avatar FROM ".tablename('mc_members')." WHERE nickname like '%{$nickname}%' AND uniacid = {$_W['uniacid']} ORDER BY uid DESC");
	if(empty($mcmember)){
		$resarr['error'] = 1;
		$resarr['msg'] = '没有这个用户！';
		echo json_encode($resarr);
		exit;
	}else{
		$fansmember = pdo_fetch("SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uid = {$mcmember['uid']} AND acid = {$_W['uniacid']}");
		if(empty($fansmember)){
			$resarr['error'] = 1;
			$resarr['msg'] = '没有这个用户！';
			echo json_encode($resarr);
			exit;
		}
		$resarr['error'] = 0;
		$resarr['html'] = '<a href="'.$this->createMobileUrl('sanchat',array('toopenid'=>$fansmember['openid'],'qudao'=>$qudao)).'">
							<div class="item">
							<div class="left">
								<div class="img">
									<img src="'.$mcmember['avatar'].'">
								</div>
								<div class="text">
									<div class="name" style="height:1rem;line-height:1rem;">
										'.$mcmember['nickname'].'
									</div>
								</div>
							</div>
							<div class="right iconfont" style="color:#333;">&#xe642;</div>
						</div></a>';
		echo json_encode($resarr);
		exit;
	}
}elseif($operation == 'delete'){
	$toopenid = trim($_GPC['toopenid']);
	$qudao = trim($_GPC['qudao']);
	$sanfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_SANFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$toopenid}' AND fansopenid = '{$openid}' AND qudao = '{$qudao}'");
	$message = pdo_fetch("SELECT * FROM ".tablename(BEST_SANCHAT)." WHERE weid = {$_W['uniacid']} AND sanfkid = {$sanfanskefu['id']}");
	if(empty($message)){
		message('抱歉不存在该聊天记录！请求失败','','error');
	}
	pdo_delete(BEST_SANCHAT,array('sanfkid'=>$sanfanskefu['id']));
	$dataup['lasttime'] = 0;
	$dataup['notread'] = 0;
	$dataup['lastcon'] = '';
	$dataup['seetime'] = 0;
	pdo_update(BEST_SANFANSKEFU,$dataup,array('weid'=>$_W['uniacid'],'id'=>$sanfanskefu['id']));
	message('恭喜您，删除聊天记录成功！',$this->createMobileUrl('disanfang',array('qudao'=>$qudao)),'success');
}
?>