<?php
global $_W,$_GPC;
$id = base64_decode($_GPC['wid']);
$op = $_GPC['op'] ? $_GPC['op'] : 'display';
$cfg = $this->module['config'];

if ($op == 'display'){
	if (!empty($id)){
		$styles = pdo_fetchall("select * from ".tablename($this->modulename.'_style')." where weid='{$_W['uniacid']}' and status=1 order by sort desc, id asc");
		$works = pdo_fetch("select * from ".tablename($this->modulename.'_works')." where weid='{$_W['uniacid']}' and id='{$id}'");
		
		$music = pdo_fetch("select url,title from ".tablename($this->modulename."_music")." where id='{$works['musicid']}'");
		$works['url'] = $music['url'];
		$works['mtitle'] = $music['title'];
		if ($works['preview']){
			$works['styleid'] = $works['preview'];
			$preview = true;
			pdo_update($this->modulename."_works",array('preview'=>0),array('id'=>$works['id']));
		}
		$works['path'] = pdo_fetchcolumn("select path from ".tablename($this->modulename."_style")." where id='{$works['styleid']}'");
		
		if (empty($works)) MSG("该{$cfg['UI']['title']}不存在",'');
		$mem = pdo_fetch("select * from ".tablename($this->modulename.'_member')." where openid='{$works['openid']}' and weid='{$_W['uniacid']}'");
		$session = json_decode(base64_decode($_GPC['__session']), true);
		$uid = $session['uid'];
		unset($session);
		$_psw = $_COOKIE['junsion_simpledaily_cookie'.$id];
		$addread = true;
		
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
			load()->model('mc');
			$info = mc_oauth_userinfo();
		}
		
		if ($_GPC['f'] && ($uid || ($cfg['admin_check'] && $_W['openid']==$cfg['admin_openid'] && $_W['openid']))){
			// 后台或者管理员点击客服消息进入不需要验证
			$addread = false;
		}else{
			if ($works['status'] == 1) MSG("该{$cfg['UI']['title']}已禁止开放，若有疑问请联系管理员",'','w');
			if ($mem['status']==1) MSG('用户已被禁用！','');
			//不是用户自己的简记才需判断权限
			if ($mem['openid']!=$_W['openid'] || empty($_W['openid'])){
				if ($_psw!=$works['psw'] && $works['type']==2){
					header("location: ".$this->createMobileUrl('preview',array('id'=>base64_encode($id))));
				}elseif ($works['type']==1){
					MSG("该{$cfg['UI']['title']}为私有非公开，不能查看",'');
				}
				// 非后台进入增加阅读量
				pdo_query("update ".tablename($this->modulename.'_works')." set `read`=`read`+1 where id='{$id}' and openid!='{$_W['openid']}'");
			}
		}
		
		$works['reward'] = number_format(pdo_fetchcolumn('select sum(price) from '.tablename($this->modulename."_order")." where wid='{$id}' and status=1"),2);
		$cfg = $this->module['config'];
		
		if ($mem['openid']==$_W['openid'] && !empty($mem['openid'])){
			$canedit = $_GPC['canedit'];
			$frommyworks = true;

			//查看是否有记录借权openid
			if ($cfg['auth'] && !$mem['authopenid']){
				if (!empty($_GPC['code'])){
					load()->func('communication');
					$cfg['appid'] = trim($cfg['appid']);
					$cfg['appsecret'] = trim($cfg['appsecret']);
					$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$cfg['appid']}&secret={$cfg['appsecret']}&code={$_GPC['code']}&grant_type=authorization_code";
					$res = ihttp_get($url);
					$res = @json_decode($res['content'],true);
					$oauthopenid = $res['openid'];
					if (empty($oauthopenid)) MSG('借用支付失败：'.json_encode($res));
					pdo_update($this->modulename."_member",array('authopenid'=>$oauthopenid),array('openid'=>$mem['openid'],'weid'=>$_W['uniacid']));
				}else{
					//若红包参数中公众号appid跟当前公众号appid不同时  要保存借用公众号的openid
					if ($_W['account']['key'] != $cfg['appid'] && !empty($cfg['appid'])){
						if (empty($m['authopenid'])){
							$callback = urlencode($_W['siteroot']."app/".$this->createMobileUrl('myWorks',array('wid'=>$_GPC['wid']),true));
							$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$cfg['appid']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
							header('location:'.$url);
							exit;
						}
					}
				}
			}
		}else{
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
					$iswx = false;
			}else{
				$iswx = true;
				$detail = pdo_fetch("select * from ".tablename($this->modulename.'_report_detail')." where weid='{$_W['uniacid']}' and wid='{$id}' and openid='{$_W['openid']}'");
				$is_firstClick = $detail ? false : true;
				$report = pdo_fetchall("select * from ".tablename($this->modulename.'_report')." where weid='{$_W['uniacid']}' and status=1 order by sort desc, id desc");
			}
		}
		
		if ($mem['buy_styleid']){
			$mem['buy_styleid'] = explode(',', $mem['buy_styleid']);
		}
		$allfree = 0;
		foreach ($styles as &$t_s) {
			$allfree += $t_s['price'];
			if ($mem['buy_styleid'] && in_array($t_s['id'], $mem['buy_styleid'])){
				$t_s['buy'] = 1;
			}
		}
		$works['imgs'] = unserialize($works['imgs']);
		if (!empty($works['imgs'])){
			foreach ($works['imgs'] as &$value){
				if (!empty($value['img'])){
					$im = getimagesize($value['img']);
					$value['ratio'] = $im[0]/$im[1];
				}
			}
		}
		
		$reward_money = explode(',', $cfg['reward_money']);
		$title = $works['title'];
		if ($works['type']==2){
			$surl = "{$_W['siteroot']}app/index.php?i=".$_W['uniacid']."&c=entry&do=preview&id=".base64_encode($id)."&m=junsion_simpledaily";
		}else{
			$surl = "{$_W['siteroot']}app/index.php?i=".$_W['uniacid']."&c=entry&do=myworks&wid=".base64_encode($id)."&m=junsion_simpledaily";	
		}
		
		if (!empty($cfg['share_title'])){
			$stitle = str_replace('#昵称#', $mem['nickname'], $cfg['share_title']);
			$stitle = str_replace('#标题#',$title,$stitle);
		}else $stitle = $title;
		$desc = str_replace('#昵称#',$mem['nickname'], $cfg['share_desc']);
		$desc = str_replace('#标题#', $title,$desc);
		$thumb = toimage($works['imgs'][0]['img']);
		if ($cfg['share_thumb']) $thumb = toimage($cfg['share_thumb']);
		$comments = pdo_fetchall("select * from ".tablename($this->modulename.'_comment')." where wid='{$id}' order by createtime desc limit 5");
		$order = pdo_fetchall("select avatar from ".tablename($this->modulename.'_order')." where wid='{$id}' and status=1 and avatar<>'' group by openid limit 24");
		$ordernum = pdo_fetchcolumn("select count(1) from ".tablename($this->modulename.'_order')." where wid='{$id}' and status=1");
		include $this->template('myworks');
	}else{ //作品列表
		$mid = $_GPC['mid'];
		if (empty($mid)){ //自己作品列表
			
			WXLimit();
			
			$info = $_W['fans'];
			if (empty($info['openid']) || empty($info['nickname'])){
				load()->model('mc');
				$info = mc_oauth_userinfo();
				$info['avatar'] = $info['avatar'];
			}
			$mem = pdo_fetch("select * from ".tablename($this->modulename.'_member')." where weid='{$_W['uniacid']}' and openid='{$info['openid']}'");
			if (empty($mem) && $info){
				$mem = array(
						'weid'=>$_W['uniacid'],
						'openid'=>$info['openid'],
						'nickname'=>$info['nickname'],
						'avatar'=>$info['avatar'],
						'createtime'=>time()
				);
				pdo_insert($this->modulename.'_member',$mem);
			}
		}else{ //别人的列表
			$mem = pdo_fetch("select * from ".tablename($this->modulename.'_member')." where id='{$mid}'");
			if ($_W['openid'] == $mem['openid']) $mid = '';
		}
		
		if ($mem['status']==1) MSG('用户已禁止开放，若有疑问请联系管理员','');
		
		$list = pdo_fetchall("select * from ".tablename($this->modulename.'_works')." where weid='{$_W['uniacid']}' and openid='{$mem['openid']}' and status=0 order by createtime desc limit 10");
		$count = pdo_fetchcolumn("select count(1) from ".tablename($this->modulename.'_works')." where openid='{$mem['openid']}' and status=0");
		foreach ($list as &$l){
			$l['wid'] = base64_encode($l['id']);
			$l['createtime'] = date('Y-m-d',$l['createtime']);
			$l['imgs'] = unserialize($l['imgs']);
			$l['cover'] = $l['cover'] ? $l['cover'] : $limgs[0];
			$l['reward'] = number_format(pdo_fetchcolumn('select sum(price) from '.tablename($this->modulename."_order")." where wid='{$l['id']}' and status=1"),2);
		}
		
		$surl = "{$_W['siteroot']}app/index.php?i=".$_W['uniacid']."&c=entry&do=myworks&mid=".$mem['id']."&m=junsion_simpledaily";
		$name = $mem['nickname'];
		$avatar = $mem['avatar'];
		$cfg['ms_title'] = str_replace('#昵称#', $name, $cfg['ms_title']);
		$cfg['ms_desc'] = str_replace('#昵称#', $name, $cfg['ms_desc']);
		include $this->template('myworkslist');
	}
}elseif ($op == 'edit'){

	WXLimit();
	$info = $_W['fans'];
	if (empty($info['openid']) || empty($info['nickname'])){
		load()->model('mc');
		$info = mc_oauth_userinfo();
	}
	
	$works = pdo_fetch("select w.*,s.path,m.url,m.title as mtitle from ".tablename($this->modulename.'_works')." w left join ".tablename($this->modulename.'_style')." s on s.id=w.styleid left join ".tablename($this->modulename.'_music')." m on m.id=w.musicid where w.weid='{$_W['uniacid']}' and w.id='{$id}' and w.openid='{$info['openid']}'");
	$mem = pdo_fetch("select * from ".tablename($this->modulename.'_member')." where openid='{$works['openid']}' and weid='{$_W['uniacid']}'");
	if (empty($works)) MSG("该{$cfg['UI']['title']}不存在",'');
	if ($works['status'] == 1) MSG("该{$cfg['UI']['title']}已禁止开放，若有疑问请联系管理员",'','w');
	if ($mem['status']==1) MSG('用户已禁止开放，若有疑问请联系管理员','','w');
	
	//音乐分类
	$mcates = pdo_fetchall("select * from ".tablename($this->modulename.'_cate')." where weid='{$_W['uniacid']}' and status=1 order by displayorder desc, id desc");
	//音乐
	$musics = pdo_fetchall("select m.* from ".tablename($this->modulename.'_music')." m join ".tablename($this->modulename.'_cate')." c on c.status=1 and c.id=m.cate where m.weid='{$_W['uniacid']}' and m.status=1 order by sort desc, id desc"); 
	$works['imgs'] = unserialize($works['imgs']);
	
	$title = $works['title'];
	if ($works['type']==2){
		$surl = "{$_W['siteroot']}app/index.php?i=".$_W['uniacid']."&c=entry&do=preview&id=".base64_encode($id)."&m=junsion_simpledaily";
	}else{
		$surl = "{$_W['siteroot']}app/index.php?i=".$_W['uniacid']."&c=entry&do=myworks&wid=".base64_encode($id)."&m=junsion_simpledaily";
	}
	
	if (!empty($cfg['share_title'])){
		$stitle = str_replace('#昵称#', $_W['fans']['nickname'], $cfg['share_title']);
		$stitle = str_replace('#标题#',$title,$stitle);
	}else $stitle = $title;
	$desc = str_replace('#昵称#',$_W['fans']['nickname'], $cfg['share_desc']);
	$desc = str_replace('#标题#', $title,$desc);
	$thumb = toimage($works['imgs'][0]['img']);
	if ($cfg['share_thumb']) $thumb = toimage($cfg['share_thumb']);
	include $this->template('myworksedit');
}
