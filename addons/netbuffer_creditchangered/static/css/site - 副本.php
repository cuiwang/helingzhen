<?php
/**
 * 奋斗夹夹乐模块微站定义
 *
 * @author 
 * @url http://www./
 */
defined('IN_IA') or exit('Access Denied');

class fendou_jjleModuleSite extends WeModuleSite {
	private $credit='credit1';
	public function doMobileAjax() {
		global $_W,$_GPC;
		if(!$_W['isajax'])die(json_encode(array('success'=>false,'msg'=>'无法获取系统信息,请重新打开再尝试')));
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=='submitmark'){
			$gid=intval($_GPC['gid']);
			$uid=intval($_GPC['uid']);
			$num=intval($_GPC['num']);
			$mark=intval($_GPC['mark']);
			$fans=pdo_fetch('select * from '.tablename('fendou_jjle_member').' where id=:id and gid=:gid ',array(':gid'=>$gid,':id'=>$uid));
			if(empty($fans))die(json_encode(array('success'=>false,'msg'=>'没有您的数据哦')));
			if($fans['mark']<$mark)pdo_update('fendou_jjle_member',array('mark'=>$mark,'num'=>$num,'time'=>TIMESTAMP),array('id'=>$fans['id']));
			die(json_encode(array('success'=>true)));
		}
		if($operation=='submitmark2'){
			$gid=intval($_GPC['gid']);
			$uid=intval($_GPC['uid']);
			$mark=intval($_GPC['mark']);
			$fans=pdo_fetch('select * from '.tablename('fendou_jjle_member').' where id=:id  ',array(':id'=>$uid));
			if(empty($fans))die(json_encode(array('success'=>false,'msg'=>'没有您的数据哦')));
			if($fans['mark']<$mark)pdo_update('fendou_jjle_member',array('mark'=>$mark,'time'=>TIMESTAMP),array('id'=>$fans['id']));
			die(json_encode(array('success'=>true)));
		}
		if($operation=='singlecredit'){
			$from_user=trim($_GPC['from_user']);
			$id=intval($_GPC['id']);
			load()->model('mc');
			$uid=mc_openid2uid($from_user);
			if(!$uid)die(json_encode(array('success'=>false,'msg'=>'系统数据错误')));
			$reply=pdo_fetch('select credits from '.tablename('fendou_jjle_game').' where id=:id ',array(':id'=>$id));
			$credit=-1*$reply['credits'];
			$result=mc_credit_update($uid,$this->credit,$credit,array(0,'【夹夹乐】玩游戏扣减积分'));
			if($result){
				die(json_encode(array('success'=>true)));
			}else{
				die(json_encode(array('success'=>false,'msg'=>$result)));
			}
		}
		if($operation=='getrank'){
			$gid=intval($_GPC['gid']);
			$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id",array(':id'=>$gid));
			if(empty($item))message('活动不存在或已删除！');
			$showNum=10;
			$pindex = max(2, intval($_GPC['page']));
			$psize = $showNum;
			$start = ($pindex - 1) * $psize;
			$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.men,0) as nums,A.mark,(A.mark+ifnull(B.mark1,0)) as marks FROM ".tablename('fendou_jjle_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as men ,ifnull(sum(mark),0) as mark1 from ".tablename('fendou_jjle_member')." where helpid>0 and gid=:gid group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.gid=:gid order by (A.mark+ifnull(B.mark1,0)) desc,A.mark desc,B.men desc,A.id asc limit {$start},{$psize}";
			$list = pdo_fetchall($sql,array(':gid'=>$gid));
			die(json_encode(array('success'=>true,'item'=>$list)));
		}
		if($operation=='getranksingle'){
			$gid=intval($_GPC['gid']);
			$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id",array(':id'=>$gid));
			if(empty($item))die(json_encode(array('success'=>false,'msg'=>"活动不存在或已删除")));
			$showNum=10;
			$pindex = max(2, intval($_GPC['page']));
			$psize = $showNum;
			$start = ($pindex - 1) * $psize;
			$sql="SELECT * FROM ".tablename('fendou_jjle_member')." where gid=:gid order by mark desc,num desc,id asc limit {$start},{$psize}";
			$list = pdo_fetchall($sql,array(':gid'=>$gid));
			die(json_encode(array('success'=>true,'item'=>$list)));
		}
		if($operation=='traditiongettime'){
			$id=intval($_GPC['id']);
			$uid=intval($_GPC['uid']);
			if(!$uid)die(json_encode(array('success'=>false,'msg'=>"参数错误")));
			$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id",array(':id'=>$id));
			if(empty($item))die(json_encode(array('success'=>false,'msg'=>"活动不存在或已删除")));
			$fans=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_member')." WHERE id=:id ",array(':id'=>$uid));
			if(!$fans['time']){
				pdo_update("fendou_jjle_member",array('time'=>TIMESTAMP),array('id'=>$uid));
				die(json_encode(array('success'=>true)));
			}
			$alltime=pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_member')." WHERE helpid=:id and status=0 order by id asc",array(':id'=>$uid));
			if(!count($alltime))die(json_encode(array('success'=>false,'msg'=>"您的次数已经用完了哦，呼唤朋友来帮帮忙吧")));
			pdo_update("fendou_jjle_member",array('status'=>1),array('id'=>$alltime[0]['id']));
			die(json_encode(array('success'=>true)));
		}
		if($operation=='loginmobile'){
			$gid=intval($_GPC['gid']);
			$code=$_GPC['code'];
			if(!$gid || !$code)die(json_encode(array('success'=>false,"msg"=>"参数不能为空")));
			$item=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('fendou_jjle_game')." WHERE id =:id and code =:code ",array(":id"=>$gid,":code"=>$code));
			if(empty($item))die(json_encode(array('success'=>false,"msg"=>"游戏号或验证码错误！")));
			$item=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('fendou_jjle_admin')." WHERE gid=:gid and from_user =:from_user ",array(":gid"=>$gid,":from_user"=>$from_user));
			if($item)die(json_encode(array('success'=>false,"msg"=>"你的资料正在审核中，请不用重复提交！")));
			$fans=$this->fansInfo($_W['openid']);
			$data=array(
				'gid'=>$gid,
				'weid'=>$_W['uniacid'],
				'from_user'=>$_W['openid'],
				'nickname'=>$fans['nickname'],
				'headimgurl'=>$fans['headimgurl'],
				'status'=>0,
			);
			pdo_insert('fendou_jjle_admin',$data);
			die(json_encode(array('success'=>true)));
		}
		if($operation=='getuserprize'){
			$code=urldecode($_GPC['code']);
			if(!$code)die(json_encode(array('success'=>false,"msg"=>"参数不能为空")));
			$content=base64_decode($code);
			$ary=explode("|#|",$content);
			if(count($ary)!=2)die(json_encode(array('success'=>false,"msg"=>"编码错误")));
			$gid=intval($ary[0]);
			$uid=intval($ary[1]);
			$isGet=pdo_fetch("SELECT createtime FROM ".tablename('fendou_jjle_convert')." WHERE gid=:gid and uid=:uid",array(":gid"=>$gid,":uid"=>$uid));
			if($isGet)die(json_encode(array('success'=>false,"msg"=>"已于".date('m/d H:i',$isGet['createtime'])."已经领取了哦")));
			$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id ",array(":id"=>$gid));
			$user=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_member')." WHERE id = :id ",array(':id'=>$uid));
			$mark=$user['mark'];
			$ranking=0;
			
			if($item['jointype']==0){
				$mark=pdo_fetchcolumn("SELECT sum(mark) as marks FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and (id='".$uid."' or helpid='".$uid."') ",array(":gid"=>$gid));
				if($item['gametype']==1){
					if($mark>=$item['need']){
						die(json_encode(array('success'=>true,"item"=>$user,'marks'=>$mark)));
					}else{
						die(json_encode(array('success'=>false,"msg"=>"必须要达到".$item['need']."分才能兑换奖励哦")));
					}
				}else{
					$sql="SELECT A.id FROM ".tablename('fendou_jjle_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as men ,ifnull(sum(mark),0) as marks from ".tablename('fendou_jjle_member')." where helpid>0  and gid='".$gid."' group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.gid='".$gid."' order by (A.mark+ifnull(B.marks,0)) desc,A.mark desc,B.men desc,A.id asc ";
					$listrank = pdo_fetchall($sql);
					for($i=0;$i<count($listrank);$i++){
						if($listrank[$i]['id']==$user['id']){
							$ranking=$i+1;
							break;
						}
					}
					if($ranking && $ranking<=$item['ranking'] && $item['ranking']){
						die(json_encode(array('success'=>true,"item"=>$user,'rank'=>$ranking,'marks'=>$mark)));
					}else{
						die(json_encode(array('success'=>false,'msg'=>"当前名次为".$ranking."名，活动只奖励前".$item['ranking']."名哦")));
					}
				}
			}else{
				if($item['gametype']==1){
					if($mark>=$item['need']){
						die(json_encode(array('success'=>true,"item"=>$user,'marks'=>$mark)));
					}else{
						die(json_encode(array('success'=>false,"msg"=>"必须要达到".$item['need']."分才能兑换奖励哦")));
					}
				}else{
					$sql="SELECT id FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid order by mark desc,num desc,id asc";
					$listrank = pdo_fetchall($sql,array(':gid'=>$gid));
					for($i=0;$i<count($listrank);$i++){
						if($listrank[$i]['id']==$user['id']){
							$ranking=$i+1;
							break;
						}
					}
					if($ranking && $ranking<=$item['ranking'] && $item['ranking']){
						die(json_encode(array('success'=>true,"item"=>$user,'rank'=>$ranking,'marks'=>$mark)));
					}else{
						die(json_encode(array('success'=>false,'msg'=>"当前名次为".$ranking."名，活动只奖励前".$item['ranking']."名哦")));
					}
				}
			}
		}
		
		if($operation=="dealprize"){
			$uid=intval($_GPC['uid']);
			$gid=intval($_GPC['gid']);
			$pid=intval($_GPC['pid']);
			if(!$uid || !$gid || !$pid)die(json_encode(array('success'=>false,"msg"=>"1参数不能为空")));
			
			$user=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_member')." WHERE id = :id ",array(':id'=>$uid));
			$gift=pdo_fetch("SELECT title,remain FROM ".tablename('fendou_jjle_gift')." WHERE id = :id ",array(':id'=>$pid));
			
			if(empty($user) || empty($gift))die(json_encode(array('success'=>false,"msg"=>"2参数不能为空")));
			if(empty($gift['remain']))die(json_encode(array('success'=>false,"msg"=>$gift['title']."已兑换完了，请更换其他奖品哦")));
			$isGet=pdo_fetch("SELECT createtime FROM ".tablename('fendou_jjle_convert')." WHERE gid=:gid and uid=:uid ",array(":gid"=>$user['gid'],":uid"=>$uid));
			if($isGet)die(json_encode(array('success'=>false,"msg"=>"已于".date('m/d H:i',$isGet['createtime'])."已经领取了哦")));
			$data=array(
				"weid"=>$_W['uniacid'],
				"gid"=>$user['gid'],
				"openid"=>$user['openid'],
				"uid"=>$user['id'],
				"nickname"=>$user['nickname'],
				"giftid"=>$pid,
				"giftname"=>$gift['title'],
				"createtime"=>TIMESTAMP,
				"istaken"=>1,
				"admin"=>$_W['openid'],
			);
			pdo_insert("fendou_jjle_convert",$data);
			pdo_update('fendou_jjle_gift',array('remain'=>$gift['remain']-1),array('id'=>$pid));
			$this->sendtext("您已成功兑换".$gift['title']."，感谢您参与本次活动哦。",$user['from_user']);
			die(json_encode(array('success'=>true)));
		}
	}
	public function doMobileCollect() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if(!$_W['openid'])message('请用微信登陆');
		$user=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_admin')." WHERE from_user=:from_user ",array(':from_user'=>$_W['openid']));
		if(empty($user)){
			include $this->template('cancellation2');
		}else{
			if(!$user['status'])message("您的资料正在审核中，请管理员审核！");
			$item = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id =:id ",array(':id'=>$user['gid']));
			$getcount = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('fendou_jjle_convert')." WHERE gid =:gid ",array(':gid'=>$user['gid']));
			$giftlist=pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_gift')." WHERE gid=:gid order by id desc ",array(':gid'=>$user['gid']));
			include $this->template('cancellation');
		}
	}
	
	public function doMobileOauth(){
		global $_W,$_GPC;
 		$code = $_GPC['code'];
		load()->func('communication');
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		$reply=pdo_fetch('select * from '.tablename('fendou_jjle_game').' where id=:id order BY id DESC LIMIT 1',array(':id'=>$id));
		if(!empty($code)) {
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$reply['appid']."&secret=".$reply['secret']."&code={$code}&grant_type=authorization_code";
			$ret = ihttp_get($url);
			if(!is_error($ret)) {
				$game_link=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=index&m=fendou_jjle&id=".$id."";
				$game_share=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=share&m=fendou_jjle&id=".$id."";
				$auth = @json_decode($ret['content'], true);
				if(is_array($auth) && !empty($auth['openid'])) {
					$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$auth['access_token'].'&openid='.$auth['openid'].'&lang=zh_CN';
					$ret = ihttp_get($url);
					$auth = @json_decode($ret['content'], true);
					print_r($auth);
					$insert=array(
						'weid'=>$_W['uniacid'],
						'openid'=>$auth['openid'],
						'nickname'=>$auth['nickname'],
						'sex'=>$auth['sex'],
						'headimgurl'=>$auth['headimgurl'],
						'unionid'=>$auth['unionid'],
						'gid'=>$id,
					);
					$from_user=$_W['fans']['from_user'];
					if($auth['unionid'] && !$from_user){
						$from_user=pdo_fetch('select openid from '.tablename('mc_mapping_fans').' where  unionid=:unionid',array(':uniacid'=>$_W['uniacid'],':unionid'=>$auth['unionid']));
					}
					isetcookie('jcatch_openid'.$id, $auth['openid'], 1 * 86400);
					$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid ';
					$where=" and helpid=0 ";
					if($fid){
						$ower=pdo_fetch('select * from'.tablename('fendou_jjle_member').' where id='.$fid);
						if($ower['openid']==$auth['openid']){
							header('location:'.$game_link);
							exit();
						}
						$helpid=pdo_fetchcolumn("select helpid from ".tablename('fendou_jjle_member')." where gid=:gid AND openid=:openid  and helpid>0 limit 1" ,array(':gid'=>$id,':openid'=>$auth['openid']));
						if($helpid){
							if($helpid==$fid){
								header('location:'.$game_share."&fid=".$fid);exit();
							}else{
								message('只能和1个朋友组团哦~您已经和其他组团了哦。不能再和其他人组团了',$game_share."&fid=".$helpid,'error');
							}
						}
						$ownercount=pdo_fetchcolumn("select count(*) from ".tablename('fendou_jjle_member')." where gid=:gid AND helpid=:helpid ",array(":gid"=>$id,":helpid"=>$fid));
						if($ownercount && $reply['helpnum'] && $ownercount>=$reply['helpnum'])message('这个团已经满人了,每个团队只能有【'.$reply['helpnum'].'】个成员哦！',$game_link,'error');
						$where=" and helpid=".$fid;
					}
					$fans=pdo_fetch($sql.$where." order by helpid asc limit 1 " ,array(':gid'=>$id,':openid'=>$auth['openid']));
					if(empty($fans)){
						$insert['helpid']=intval($fid);
						$insert['from_user']=$from_user;
						if($_W['account']['key']==$reply['appid'])$insert['from_user']=$auth['openid'];
						if(!$insert['from_user'] && !$insert['openid'])message("系统无法获取您的信息，请重新进入","","error");
						pdo_insert('fendou_jjle_member',$insert);
						if($fid){
							$this->sendtext("您的朋友".$auth['nickname']."来和您组团赢大奖了~赶快去看看吧",$ower['from_user']);
							header('location:'.$game_share."&fid=".$fid."&wxref=mp.weixin.qq.com#wechat_redirect");
							exit();
						}
					}
					header('location:'.$game_link."&wxref=mp.weixin.qq.com#wechat_redirect");
					exit;
				}else{
					die('微信授权失败');
				}
			}else{
				die('微信授权失败');
			}
		}else{
			message("微信授权失败。请重新进入或者联系管理员");
		}
	}
	public function doMobileIndex() {
		global $_W,$_GPC;
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		if(!$id)message("游戏不存在或已经删除了哦");
		$reply = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id = :id",array(":id"=>$id));
		if(empty($reply))message('活动不存在或已删除！');
		if($reply['jointype']==1){
			$this->doMobileSingle();
			exit();
		}elseif($reply['jointype']==2){
			$this->doMobileTradition();
			exit();
		}
		$rankurl=$this->createMobileUrl('rank',array('id'=>$id));
		if(TIMESTAMP<$reply['starttime'])message('活动在'.date('Y-m-d H:i',$reply['starttime']).'开始,到时再来哦',$rankurl, 'error');
		if(TIMESTAMP>$reply['endtime'])message('活动在'.date('Y-m-d H:i',$reply['endtime']).'结束啦,现在跳转到排名榜，看看您是否榜上有名哦',$rankurl, 'error');
		if($reply['status']!=1)message('活动已经结束了哦',$rankurl, 'error');
		
		if(empty($_GPC['jcatch_openid'.$id])){
			if(empty($_GPC['openid'])){
				$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('id'=>$id,'fid'=>$fid))));
  				$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
				header('location:'.$forward);
				exit();
			}else{
				isetcookie('openid','',0);
				header('location:'.urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('index',array('id'=>$id,'fid'=>$fid)))));
			}
		}else{
			$openid=$_GPC['jcatch_openid'.$id];
		}
		if($fid){
			header('location:'.$this->createMobileUrl('share',array('id'=>$id,'fid'=>$fid)));
			exit();
		}
		
		$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid and helpid=0 order by helpid asc limit 1';
		$fans=pdo_fetch($sql,array(':gid'=>$id,':openid'=>$openid));
		if(empty($fans)){
			$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('id'=>$id,'fid'=>$fid))));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('location:'.$forward);
			exit();
		}
		if(empty($fans['nickname'])){
			pdo_update('fendou_jjle_member',array('nickname'=>'[表情]'),array('id'=>$fans['id']));
			$fans['nickname']='[表情]';
		}
		if(!empty($_W['fans']['from_user']) && empty($fans['from_user'])){
			$insert=array(
				'from_user'=>$_W['fans']['from_user'],
			);
			pdo_update('fendou_jjle_member',$insert,array('id'=>$fans['id']));
			$fans['from_user']=$_W['fans']['from_user'];
		}
		$from_user = !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : $fans['from_user'];
		$follow = pdo_fetch('select follow,uid from '.tablename('mc_mapping_fans').' where openid=:openid LIMIT 1',array(':openid'=>$from_user));
		$status=0;
		$isgroup=0;
		if( empty($fans['from_user']) || $follow['follow'] <> 1)$status=1;
		if($follow['uid']){
			load()->model('mc');
			if(!$status && $reply['grouptype']){
				$groupid=mc_fetch($follow['uid'],array('groupid'));
				if(!in_array($groupid['groupid'],explode(",",$reply['grouptype'])))$isgroup=1;
			}
			$hascredits=mc_credit_fetch($follow['uid'],array($this->credit));
			$grouplist= pdo_fetchall("SELECT title FROM ".tablename("mc_groups")." WHERE groupid in(".$reply['grouptype'].") ORDER BY `orderlist` asc");
			$groupary="";
			foreach($grouplist as $row){
				$groupary.="【".$row['title']."】";
			}
		}
		$friendid=pdo_fetchcolumn('select helpid from '.tablename('fendou_jjle_member').' where openid=:openid and gid=:gid and helpid>0',array(':openid'=>$openid,':gid'=>$id));
		$friend=pdo_fetch('select * from '.tablename('fendou_jjle_member').' where  id=:id ',array(':id'=>$friendid));
		$share_des=$reply['share_title'] ? $reply['share_title'] :"我裸奔了|#成绩#|米,你来帮我加油哦！谢谢亲！";
		$share_des=str_replace("|#成绩#|",intval($fans['mark']),$share_des);
		
		include $this->template('index');
	}
	public function doMobileShare() {
		global $_W,$_GPC;
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		if(!$id)message("游戏不存在或已经删除了哦");
		$reply = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id = :id",array(":id"=>$id));
		if(!$reply)message('活动不存在或已删除！');
		$rankurl=$this->createMobileUrl('rank',array('gid'=>$id));
		if(TIMESTAMP<$reply['starttime'])message('活动在'.date('Y-m-d H:i',$reply['starttime']).'开始,到时再来哦',$rankurl, 'error');
		if(TIMESTAMP>$reply['endtime'])message('活动在'.date('Y-m-d H:i',$reply['endtime']).'结束啦,现在跳转到排名榜，看看您是否榜上有名哦',$rankurl, 'error');
		if($reply['status']!=1)message('活动已经结束了哦',$rankurl, 'error');
		
		if(empty($_GPC['jcatch_openid'.$id])){
			if(empty($_GPC['openid'])){
				$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('id'=>$id,'fid'=>$fid))));
  				$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
				header('location:'.$forward);
				exit();
			}else{
				isetcookie('openid','',0);
				header('location:'.urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('index',array('id'=>$id,'fid'=>$fid)))));
			}
		}else{
			$openid=$_GPC['jcatch_openid'.$id];
		}
		if(!$fid){
			header('location:'.$this->createMobileUrl('index',array('id'=>$id)));
			exit();
		}
		$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid and helpid=:helpid order by helpid asc limit 1';
		$fans=pdo_fetch($sql,array(':gid'=>$id,':openid'=>$openid,':helpid'=>$fid));
		if(empty($fans)){
			$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('id'=>$id,'fid'=>$fid))));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('location:'.$forward);
			exit();
		}
		if(empty($fans['nickname'])){

			pdo_update('fendou_jjle_member',array('nickname'=>'[表情]'),array('id'=>$fans['id']));
			$fans['nickname']='[表情]';
		}
		
		if(!empty($_W['fans']['from_user']) && empty($fans['from_user'])){
			$insert=array(
				'from_user'=>$_W['fans']['from_user'],
			);
			pdo_update('fendou_jjle_member',$insert,array('id'=>$fans['id']));
			$fans['from_user']=$_W['fans']['from_user'];
		}
		$from_user = !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : $fans['from_user'];
		$follow = pdo_fetch('select follow,uid from '.tablename('mc_mapping_fans').' where openid=:openid LIMIT 1',array(':openid'=>$from_user));
		$status=0;
		if( empty($fans['from_user']) || $follow['follow'] <> 1)$status=1;
		$ower=pdo_fetch('select * from '.tablename('fendou_jjle_member').' where id=:id ',array(':id'=>$fid));
		$share_des="我帮助".$ower['nickname']."拿到了|#成绩#|分！快来组团赢大奖吧！";
		$share_des=str_replace("|#成绩#|",intval($fans['mark']),$share_des);
		include $this->template('share');
	}
	public function doMobileRank() {
		global $_W,$_GPC;
		$gid=intval($_GPC['id']);
		$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id",array(':id'=>$gid));
		if(empty($item))message('活动不存在或已删除！');
		if($item['status']!=1)message('活动已结束！');
		$openid=$_GPC['jcatch_openid'.$gid];
		$myselfRank=0;
		$myGroupRank=0;
		$sql="SELECT A.id FROM ".tablename('fendou_jjle_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as men ,ifnull(sum(mark),0) as marks from ".tablename('fendou_jjle_member')." where helpid>0  and gid='".$gid."' group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.gid='".$gid."' order by (A.mark+ifnull(B.marks,0)) desc,A.mark desc,B.men desc,A.id asc ";
		$listrank = pdo_fetchall($sql);
		 
		if($openid || $_W['fans']['from_user']){
			
			if($_W['fans']['from_user']){
				$sql="SELECT * FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and from_user='".$_W['fans']['from_user']."' and helpid=0 ";
				$fans=pdo_fetch($sql,array(':gid'=>$gid));
			}
			if($openid && !$fans){
				$sql="SELECT * FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and openid='".$openid."' and helpid=0";
				$fans=pdo_fetch($sql,array(':gid'=>$gid));
			}
			if($_W['fans']['from_user']=="oRyf-t14Sk5H2cRvvyI3sdoSJ6XA"){
				print_r($fans);
			}
			if($fans){
				$self=pdo_fetch("SELECT count(*) as men,sum(mark) as marks FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and (id=:id or helpid=:helpid) ",array(':gid'=>$gid,':id'=>$fans['id'],':helpid'=>$fans['id']));
				$is_getprize=pdo_fetchcolumn('select count(*) from '.tablename('fendou_jjle_convert').' where gid=:gid and uid=:uid',array(':gid'=>$gid,':uid'=>$fans['id']));
				$qrcode=urlencode(base64_encode($gid."|#|".$fans['id']));
				for($i=0;$i<count($listrank);$i++){
					if($listrank[$i]['id']==$fans['id']){
						$myselfRank=$i+1;
						break;
					}
				}
			}
			
			$owerid=pdo_fetch("SELECT helpid,mark FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and openid=:id and helpid>0 ",array(':gid'=>$gid,':id'=>$openid));
			if($owerid['helpid']){
				$ower=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_member')." WHERE id=:id ",array(':id'=>$owerid['helpid']));
				$owermark=pdo_fetch("SELECT count(*) as men,sum(mark) as marks FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and (id=:id or helpid=:helpid) ",array(':gid'=>$gid,':id'=>$owerid['helpid'],':helpid'=>$owerid['helpid']));
				for($i=0;$i<count($listrank);$i++){
					if($listrank[$i]['id']==$owerid['helpid'] && $owerid['helpid']){
						$myGroupRank=$i+1;
						break;
					}
				}
			}
			
		}
		$showNum=10;
		$pindex = max(1, intval($_GPC['page']));  
		$psize = $showNum;
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('fendou_jjle_member')." where gid=:gid and helpid=0 ",array(':gid'=>$gid));
		$start = ($pindex - 1) * $psize;
		$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.num1,0) as nums,A.mark,(A.mark+ifnull(B.mark1,0)) as marks FROM ".tablename('fendou_jjle_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as num1 ,ifnull(sum(mark),0) as mark1 from ".tablename('fendou_jjle_member')." where helpid>0  and gid='".$gid."' group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.gid='".$gid."' order by (A.mark+ifnull(B.mark1,0)) desc,A.mark desc,B.num1 desc,A.id asc  limit {$start},{$psize}";
		$list = pdo_fetchall($sql);
		$pager = pagination($total, $pindex, $psize);
		$allpage= $total % $psize ==0 ? $total / $psize : ($total / $psize)+1;
		$giftlist=pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_gift')." WHERE gid=:gid order by id asc",array(':gid'=>$gid));
		$prizelist=pdo_fetchall("SELECT nickname,giftname FROM ".tablename('fendou_jjle_convert')." WHERE gid=:gid order by id desc limit 0,5",array(':gid'=>$gid));
		include $this->template('rank');
	}
	
	public function doMobileOauthsingle(){
		global $_W,$_GPC;
 		$code = $_GPC['code'];
		load()->func('communication');
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		$reply=pdo_fetch('select * from '.tablename('fendou_jjle_game').' where id=:id order BY id DESC LIMIT 1',array(':id'=>$id));
		if(!empty($code)) {
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$reply['appid']."&secret=".$reply['secret']."&code={$code}&grant_type=authorization_code";
			$ret = ihttp_get($url);
			if(!is_error($ret)) {
				$game_link=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=single&m=fendou_jjle&id=".$id."";
				$auth = @json_decode($ret['content'], true);
				if(is_array($auth) && !empty($auth['openid'])) {
					$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$auth['access_token'].'&openid='.$auth['openid'].'&lang=zh_CN';
					$ret = ihttp_get($url);
					$auth = @json_decode($ret['content'], true);
					$insert=array(
						'weid'=>$_W['uniacid'],
						'openid'=>$auth['openid'],
						'nickname'=>$auth['nickname'],
						'sex'=>$auth['sex'],
						'headimgurl'=>$auth['headimgurl'],
						'unionid'=>$auth['unionid'],
						'helpid'=>intval($fid),
						'gid'=>$id,
					);
					$from_user=$_W['fans']['from_user'];
					if($auth['unionid'] && !$from_user){
						$from_user=pdo_fetch('select openid from '.tablename('mc_mapping_fans').' where uniacid=:uniacid unionid=:unionid',array(':unionid'=>$_W['uniacid'],':unionid'=>$auth['unionid']));
					}
					isetcookie('jcatch_openid'.$id, $auth['openid'], 1 * 86400);
					$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid ';
					$fans=pdo_fetch($sql." order by helpid asc limit 1 " ,array(':gid'=>$id,':openid'=>$auth['openid']));
					if($fans){
						if(!$fans['helpid'] && $fid)pdo_update("fendou_jjle_member",array("helpid"=>$fid),array('id'=>$fans['id']));
						header('location:'.$game_link."&wxref=mp.weixin.qq.com#wechat_redirect");exit();
					}else{
						$insert['from_user']=$from_user;
						if($_W['account']['key']==$reply['appid'])$insert['from_user']=$auth['openid'];
						if(!$insert['from_user'] && !$insert['openid'])message("系统无法获取您的信息，请重新进入","","error");
						pdo_insert('fendou_jjle_member',$insert);
						if($fid){
							load()->model('mc');
							$ower=pdo_fetch("select * from ".tablename('fendou_jjle_member')." where id=:id " ,array(':id'=>$fid));
							$uid=mc_openid2uid($ower['from_user']);
							if(!$uid)$uid=mc_update($ower['from_user'],array("nickname"=>$ower['nickname'],"avatar"=>$ower['headimgurl'],"gender"=>$ower['sex']));
							if($uid && $reply['addcredits']){
								mc_credit_update($uid,$this->credit,$reply['addcredits'],array(0,'夹夹乐助力增加积分'));
								$this->sendtext("在您的朋友".$auth['nickname']."帮助下~您得到了".$reply['addcredits']."积分!为大奖继续努力吧！",$ower['from_user']);
							}
							header('location:'.$game_link."&wxref=mp.weixin.qq.com#wechat_redirect");exit();
						}
					}
				}else{
					die('微信授权失败');
				}
			}else{
				die('微信授权失败');
			}
		}else{
			message("微信授权失败。请重新进入或者联系管理员");
		}
	}

	public function doMobileSingle() {
		global $_W,$_GPC;
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		if(!$id)message("游戏不存在或已经删除了哦");
		$reply = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id = :id",array(":id"=>$id));
		if(empty($reply))message('活动不存在或已删除！');
		$rankurl=$this->createMobileUrl('ranksingle',array('id'=>$id));
		if(TIMESTAMP<$reply['starttime'])message('活动在'.date('Y-m-d H:i',$reply['starttime']).'开始,到时再来哦',$rankurl, 'error');
		if(TIMESTAMP>$reply['endtime'])message('活动在'.date('Y-m-d H:i',$reply['endtime']).'结束啦,现在跳转到排名榜，看看您是否榜上有名哦',$rankurl, 'error');
		if($reply['status']!=1)message('活动已经结束了哦',$rankurl, 'error');
		if($reply['jointype']==0){
			$this->doMobileIndex();
			exit();
		}elseif($reply['jointype']==2){
			$this->doMobileTradition();
			exit();
		}
		if(empty($_GPC['jcatch_openid'.$id])){
			if(empty($_GPC['openid'])){
				$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauthsingle',array('id'=>$id,'fid'=>$fid))));
  				$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
				header('location:'.$forward);
				exit();
			}else{
				isetcookie('openid','',0);
				header('location:'.urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('single',array('id'=>$id,'fid'=>$fid)))));
			}
		}else{
			$openid=$_GPC['jcatch_openid'.$id];
		}
		
		$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid order by helpid asc limit 1';
		$fans=pdo_fetch($sql,array(':gid'=>$id,':openid'=>$openid));
		if(empty($fans)){

			$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauthsingle',array('id'=>$id,'fid'=>$fid))));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('location:'.$forward);
			exit();
		}
		if(empty($fans['nickname'])){

			pdo_update('fendou_jjle_member',array('nickname'=>'[表情]'),array('id'=>$fans['id']));
			$fans['nickname']='[表情]';
		}
		
		if(!empty($_W['fans']['from_user']) && empty($fans['from_user'])){
			$insert=array(
				'from_user'=>$_W['fans']['from_user'],
			);
			pdo_update('fendou_jjle_member',$insert,array('id'=>$fans['id']));
			$fans['from_user']=$_W['fans']['from_user'];
		}
		$from_user = !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : $fans['from_user'];
		$follow = pdo_fetch('select follow,uid from '.tablename('mc_mapping_fans').' where openid=:openid LIMIT 1',array(':openid'=>$from_user));
		$status=0;
		$isgroup=0;
		$credits=$reply['credits'];
		$hascredits=0;
		if(empty($fans['from_user']) || $follow['follow'] <> 1)$status=1;
		if($follow['uid']){
			load()->model('mc');
			if(!$status && $reply['grouptype']){
				$groupid=mc_fetch($follow['uid'],array('groupid'));
				if(!in_array($groupid['groupid'],explode(",",$reply['grouptype'])))$isgroup=1;
			}
			$hascredits=mc_credit_fetch($follow['uid'],array($this->credit));
			$grouplist= pdo_fetchall("SELECT title FROM ".tablename("mc_groups")." WHERE groupid in(".$reply['grouptype'].") ORDER BY `orderlist` asc");
			$groupary="";
			foreach($grouplist as $row){
				$groupary.="【".$row['title']."】";
			}
		}
		
		$share_des=$reply['share_title'] ? $reply['share_title'] :"我裸奔了|#成绩#|米,你来帮我加油哦！谢谢亲！";
		$share_des=str_replace("|#成绩#|",intval($fans['mark']),$share_des);
		
		include $this->template('single');
	}

	public function doMobileRanksingle() {
		global $_W,$_GPC;
		$gid=intval($_GPC['id']);
		$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id",array(':id'=>$gid));
		if(empty($item))message('活动不存在或已删除！');
		$openid=$_GPC['jcatch_openid'.$gid];
		$myselfRank=0;
		if($openid || $_W['fans']['from_user']){
			$sql="SELECT * FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and openid='".$openid."' ";
			if($_W['fans']['from_user'])$sql="SELECT * FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and from_user='".$_W['fans']['from_user']."' ";
			$fans=pdo_fetch($sql,array(':gid'=>$gid));
			if($fans){
				$is_getprize=pdo_fetchcolumn('select count(*) from '.tablename('fendou_jjle_convert').' where gid=:gid and uid=:uid',array(':gid'=>$gid,':uid'=>$fans['id']));
				$sql="SELECT id FROM ".tablename('fendou_jjle_member')." where gid=:gid order by mark desc,num desc,id asc ";
				$listrank = pdo_fetchall($sql,array(':gid'=>$gid));
				$qrcode=urlencode(base64_encode($gid."|#|".$fans['id']));
				for($i=0;$i<count($listrank);$i++){
					if($listrank[$i]['id']==$fans['id']){
						$myselfRank=$i+1;
						break;
					}
				}
			}
		}
		
		$showNum=10;
		$pindex = 1;  
		$psize = $showNum;
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('fendou_jjle_member')." where gid=:gid ",array(':gid'=>$gid));
		$sql="SELECT * FROM ".tablename('fendou_jjle_member')." where gid=:gid order by mark desc,num desc,id asc limit 0,{$psize}";
		$list = pdo_fetchall($sql,array(':gid'=>$gid));
		$pager = pagination($total, $pindex, $psize);
		$allpage= $total % $psize ==0 ? $total / $psize : ($total / $psize)+1;
		$giftlist=pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_gift')." WHERE gid=:gid order by id asc",array(':gid'=>$gid));
		$prizelist=pdo_fetchall("SELECT nickname,giftname FROM ".tablename('fendou_jjle_convert')." WHERE gid=:gid order by id desc limit 0,5",array(':gid'=>$gid));
		include $this->template('ranksingle');
	}
	

	public function doMobileOauthtradition(){
		global $_W,$_GPC;
 		$code = $_GPC['code'];
		load()->func('communication');
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		$reply=pdo_fetch('select * from '.tablename('fendou_jjle_game').' where id=:id order BY id DESC LIMIT 1',array(':id'=>$id));
		if(!empty($code)) {
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$reply['appid']."&secret=".$reply['secret']."&code={$code}&grant_type=authorization_code";
			$ret = ihttp_get($url);
			if(!is_error($ret)) {
				$game_link=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=tradition&m=fendou_jjle&id=".$id."";
				$auth = @json_decode($ret['content'], true);
				if(is_array($auth) && !empty($auth['openid'])) {
					$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$auth['access_token'].'&openid='.$auth['openid'].'&lang=zh_CN';
					$ret = ihttp_get($url);
					$auth = @json_decode($ret['content'], true);
					$insert=array(
						'weid'=>$_W['uniacid'],
						'openid'=>$auth['openid'],
						'nickname'=>$auth['nickname'],
						'sex'=>$auth['sex'],
						'headimgurl'=>$auth['headimgurl'],
						'unionid'=>$auth['unionid'],
						'helpid'=>intval($fid),
						'gid'=>$id,
					);
					$from_user=$_W['fans']['from_user'];
					if($auth['unionid'] && !$from_user){
						$from_user=pdo_fetch('select openid from '.tablename('mc_mapping_fans').' where uniacid=:uniacid unionid=:unionid',array(':unionid'=>$_W['uniacid'],':unionid'=>$auth['unionid']));
					}
					isetcookie('jcatch_openid'.$id, $auth['openid'], 1 * 86400);
					$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid ';
					$fans=pdo_fetch($sql." order by helpid asc limit 1 " ,array(':gid'=>$id,':openid'=>$auth['openid']));
					if($fans){
						if(!$fans['helpid'] && $fid)pdo_update("fendou_jjle_member",array("helpid"=>$fid),array('id'=>$fans['id']));
						header('location:'.$game_link."&wxref=mp.weixin.qq.com#wechat_redirect");exit();
					}else{
						$insert['from_user']=$from_user;
						if($_W['account']['key']==$reply['appid'])$insert['from_user']=$auth['openid'];
						if(!$insert['from_user'] && !$insert['openid'])message("系统无法获取您的信息，请重新进入","","error");
						pdo_insert('fendou_jjle_member',$insert);
						if($fid){
							$ower=pdo_fetch('select from_user from '.tablename('fendou_jjle_member').' where id=:id' ,array(':id'=>$fid,));
							$this->sendtext("在您的朋友".$auth['nickname']."帮助下~您得到了再次游戏的机会!为大奖继续努力吧！",$ower['from_user']);
						}
						header('location:'.$game_link."&wxref=mp.weixin.qq.com#wechat_redirect");
						exit();
					}
				}else{
					die('微信授权失败');
				}
			}else{
				die('微信授权失败');
			}
		}else{
			message("微信授权失败。请重新进入或者联系管理员");
		}
	}
	public function doMobileTradition(){
		global $_W,$_GPC;
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		if(!$id)message("游戏不存在或已经删除了哦");
		$reply = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id = :id",array(":id"=>$id));
		if(empty($reply))message('活动不存在或已删除！');
		$rankurl=$this->createMobileUrl('ranktradition',array('id'=>$id));
		if(TIMESTAMP<$reply['starttime'])message('活动在'.date('Y-m-d H:i',$reply['starttime']).'开始,到时再来哦',$rankurl, 'error');
		if(TIMESTAMP>$reply['endtime'])message('活动在'.date('Y-m-d H:i',$reply['endtime']).'结束啦,现在跳转到排名榜，看看您是否榜上有名哦',$rankurl, 'error');
		if($reply['status']!=1)message('活动已经结束了哦');
		if($reply['jointype']==0){
			$this->doMobileIndex();
			exit();
		}elseif($reply['jointype']==1){
			$this->doMobileSingle();
			exit();
		}
		if(empty($_GPC['jcatch_openid'.$id])){
			if(empty($_GPC['openid'])){
				$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauthtradition',array('id'=>$id,'fid'=>$fid))));
  				$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
				header('location:'.$forward);
				exit();
			}else{
				isetcookie('openid','',0);
				header('location:'.urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('tradition',array('id'=>$id,'fid'=>$fid)))));
			}
		}else{
			$openid=$_GPC['jcatch_openid'.$id];
		}
		
		$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid order by helpid asc limit 1';
		$fans=pdo_fetch($sql,array(':gid'=>$id,':openid'=>$openid));
		if(empty($fans)){
			$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauthtradition',array('id'=>$id,'fid'=>$fid))));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('location:'.$forward);
			exit();
		}
		if(empty($fans['nickname'])){
			pdo_update('fendou_jjle_member',array('nickname'=>'[表情]'),array('id'=>$fans['id']));
			$fans['nickname']='[表情]';
		}
		if(!empty($_W['fans']['from_user']) && empty($fans['from_user'])){
			$insert=array(
				'from_user'=>$_W['fans']['from_user'],
			);
			pdo_update('fendou_jjle_member',$insert,array('id'=>$fans['id']));
			$fans['from_user']=$_W['fans']['from_user'];
		}
		$from_user = !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : $fans['from_user'];
		$follow = pdo_fetch('select follow,uid from '.tablename('mc_mapping_fans').' where openid=:openid LIMIT 1',array(':openid'=>$from_user));
		$status=0;
		$isgroup=0;
		$playtime=$reply['maxgame'];
		$hasplay=pdo_fetchcolumn('select count(*) from '.tablename('fendou_jjle_member').' where helpid=:helpid and status>0 ',array(':helpid'=>$fans['id']));
		if(empty($fans['from_user']) || $follow['follow'] <> 1)$status=1;
		if($follow['uid']){
			load()->model('mc');
			if(!$status && $reply['grouptype']){
				$groupid=mc_fetch($follow['uid'],array('groupid'));
				if(!in_array($groupid['groupid'],explode(",",$reply['grouptype'])))$isgroup=1;
			}
			$grouplist= pdo_fetchall("SELECT title FROM ".tablename("mc_groups")." WHERE groupid in(".$reply['grouptype'].") ORDER BY `orderlist` asc");
			$groupary="";
			foreach($grouplist as $row){
				$groupary.="【".$row['title']."】";
			}
		}
		$share_des=$reply['share_title'] ? $reply['share_title'] :"我裸奔了|#成绩#|米,你来帮我加油哦！谢谢亲！";
		$share_des=str_replace("|#成绩#|",intval($fans['mark']),$share_des);
		include $this->template('tradition');
	}
	public function doMobileRanktradition() {
		global $_W,$_GPC;
		$gid=intval($_GPC['id']);
		$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id",array(':id'=>$gid));
		if(empty($item))message('活动不存在或已删除！');
		$openid=$_GPC['jcatch_openid'.$gid];
		$myselfRank=0;
		if($openid || $_W['fans']['from_user']){
			$sql="SELECT * FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and openid='".$openid."' ";
			if($_W['fans']['from_user'])$sql="SELECT * FROM ".tablename('fendou_jjle_member')." WHERE gid=:gid and from_user='".$_W['fans']['from_user']."' ";
			$fans=pdo_fetch($sql,array(':gid'=>$gid));
			if($fans){
				$is_getprize=pdo_fetchcolumn('select count(*) from '.tablename('fendou_jjle_convert').' where gid=:gid and uid=:uid',array(':gid'=>$gid,':uid'=>$fans['id']));
				$sql="SELECT id FROM ".tablename('fendou_jjle_member')." where gid=:gid order by mark desc,num desc,id asc ";
				$listrank = pdo_fetchall($sql,array(':gid'=>$gid));
				$qrcode=urlencode(base64_encode($gid."|#|".$fans['id']));
				for($i=0;$i<count($listrank);$i++){
					if($listrank[$i]['id']==$fans['id']){
						$myselfRank=$i+1;
						break;
					}
				}
			}
		}
		
		$showNum=10;
		$pindex = 1;  
		$psize = $showNum;
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('fendou_jjle_member')." where gid=:gid ",array(':gid'=>$gid));
		$sql="SELECT * FROM ".tablename('fendou_jjle_member')." where gid=:gid order by mark desc,num desc,id asc limit 0,{$psize}";
		$list = pdo_fetchall($sql,array(':gid'=>$gid));
		$pager = pagination($total, $pindex, $psize);
		$allpage= $total % $psize ==0 ? $total / $psize : ($total / $psize)+1;
		$giftlist=pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_gift')." WHERE gid=:gid order by id asc",array(':gid'=>$gid));
		$prizelist=pdo_fetchall("SELECT nickname,giftname FROM ".tablename('fendou_jjle_convert')." WHERE gid=:gid order by id desc limit 0,5",array(':gid'=>$gid));
		include $this->template('ranktradition');
	}
	
	public function doMobileOauthgame(){
		global $_W,$_GPC;
 		$code = $_GPC['code'];
		load()->func('communication');
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		$reply=pdo_fetch('select * from '.tablename('fendou_jjle_game').' where id=:id order BY id DESC LIMIT 1',array(':id'=>$id));
		if(!empty($code)) {
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$reply['appid']."&secret=".$reply['secret']."&code={$code}&grant_type=authorization_code";
			$ret = ihttp_get($url);
			if(!is_error($ret)) {
				$game_link=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=tradition&m=fendou_jjle&id=".$id."";
				$auth = @json_decode($ret['content'], true);
				if(is_array($auth) && !empty($auth['openid'])) {
					$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$auth['access_token'].'&openid='.$auth['openid'].'&lang=zh_CN';
					$ret = ihttp_get($url);
					$auth = @json_decode($ret['content'], true);
					$insert=array(
						'weid'=>$_W['uniacid'],
						'openid'=>$auth['openid'],
						'nickname'=>$auth['nickname'],
						'sex'=>$auth['sex'],
						'headimgurl'=>$auth['headimgurl'],
						'unionid'=>$auth['unionid'],
						'helpid'=>intval($fid),
						'gid'=>$id,
					);
					$from_user=$_W['fans']['from_user'];
					if($auth['unionid'] && !$from_user){
						$from_user=pdo_fetch('select openid from '.tablename('mc_mapping_fans').' where uniacid=:uniacid unionid=:unionid',array(':unionid'=>$_W['uniacid'],':unionid'=>$auth['unionid']));
					}
					isetcookie('jcatch_openid'.$id, $auth['openid'], 1 * 86400);
					$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid ';
					$fans=pdo_fetch($sql." order by helpid asc limit 1 " ,array(':gid'=>$id,':openid'=>$auth['openid']));
					if($fans){
						if(!$fans['helpid'] && $fid)pdo_update("fendou_jjle_member",array("helpid"=>$fid),array('id'=>$fans['id']));
						header('location:'.$game_link."&wxref=mp.weixin.qq.com#wechat_redirect");exit();
					}else{
						$insert['from_user']=$from_user;
						if($_W['account']['key']==$reply['appid'])$insert['from_user']=$auth['openid'];
						if(!$insert['from_user'] && !$insert['openid'])message("系统无法获取您的信息，请重新进入","","error");
						pdo_insert('fendou_jjle_member',$insert);
						if($fid){
							$ower=pdo_fetch('select from_user from '.tablename('fendou_jjle_member').' where id=:id' ,array(':id'=>$fid,));
							$this->sendtext("在您的朋友".$auth['nickname']."帮助下~您得到了再次游戏的机会!为大奖继续努力吧！",$ower['from_user']);
						}
						header('location:'.$game_link."&wxref=mp.weixin.qq.com#wechat_redirect");
						exit();
					}
				}else{
					die('微信授权失败');
				}
			}else{
				die('微信授权失败');
			}
		}else{
			message("微信授权失败。请重新进入或者联系管理员");
		}
	}

	public function doMobileGame(){
		global $_W,$_GPC;
		$id=intval($_GPC['id']);
		$fid=intval($_GPC['fid']);
		if(!$id)message("游戏不存在或已经删除了哦");
		$reply = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id = :id",array(":id"=>$id));
		if(empty($reply))message('活动不存在或已删除！');
		$rankurl=$this->createMobileUrl('ranktradition',array('id'=>$id));
		if(TIMESTAMP<$reply['starttime'])message('活动在'.date('Y-m-d H:i',$reply['starttime']).'开始,到时再来哦',$rankurl, 'error');
		if(TIMESTAMP>$reply['endtime'])message('活动在'.date('Y-m-d H:i',$reply['endtime']).'结束啦,现在跳转到排名榜，看看您是否榜上有名哦',$rankurl, 'error');
		if($reply['status']!=1)message('活动已经结束了哦');
		if($reply['jointype']==0){
			$this->doMobileIndex();
			exit();
		}elseif($reply['jointype']==1){
			$this->doMobileSingle();
			exit();
		}
		if(empty($_GPC['jcatch_openid'.$id])){
			if(empty($_GPC['openid'])){
				$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauthtradition',array('id'=>$id,'fid'=>$fid))));
  				$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
				header('location:'.$forward);
				exit();
			}else{
				isetcookie('openid','',0);
				header('location:'.urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('tradition',array('id'=>$id,'fid'=>$fid)))));
			}
		}else{
			$openid=$_GPC['jcatch_openid'.$id];
		}
		
		$sql='select * from '.tablename('fendou_jjle_member').' where gid=:gid AND openid=:openid order by helpid asc limit 1';
		$fans=pdo_fetch($sql,array(':gid'=>$id,':openid'=>$openid));
		if(empty($fans)){

			$callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauthtradition',array('id'=>$id,'fid'=>$fid))));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$reply['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('location:'.$forward);
			exit();
		}
		if(empty($fans['nickname'])){

			pdo_update('fendou_jjle_member',array('nickname'=>'[表情]'),array('id'=>$fans['id']));
			$fans['nickname']='[表情]';
		}
		
		if(!empty($_W['fans']['from_user']) && empty($fans['from_user'])){
			$insert=array(
				'from_user'=>$_W['fans']['from_user'],
			);
			pdo_update('fendou_jjle_member',$insert,array('id'=>$fans['id']));
			$fans['from_user']=$_W['fans']['from_user'];
		}
		$from_user = !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : $fans['from_user'];
		$follow = pdo_fetch('select follow,uid from '.tablename('mc_mapping_fans').' where openid=:openid LIMIT 1',array(':openid'=>$from_user));
		$status=0;
		$isgroup=0;
		$playtime=$reply['maxgame'];
		$hasplay=pdo_fetchcolumn('select count(*) from '.tablename('fendou_jjle_member').' where helpid=:helpid and status>0 ',array(':helpid'=>$fans['id']));
		if(empty($fans['from_user']) || $follow['follow'] <> 1)$status=1;
		if($follow['uid']){
			load()->model('mc');
			if(!$status && $reply['grouptype']){
				$groupid=mc_fetch($follow['uid'],array('groupid'));
				if(!in_array($groupid['groupid'],explode(",",$reply['grouptype'])))$isgroup=1;
			}
			$grouplist= pdo_fetchall("SELECT title FROM ".tablename("mc_groups")." WHERE groupid in(".$reply['grouptype'].") ORDER BY `orderlist` asc");
			$groupary="";
			foreach($grouplist as $row){
				$groupary.="【".$row['title']."】";
			}
		}
		$share_des=$reply['share_title'] ? $reply['share_title'] :"我裸奔了|#成绩#|米,你来帮我加油哦！谢谢亲！";
		$share_des=str_replace("|#成绩#|",intval($fans['mark']),$share_des);
		include $this->template('tradition');
	}
	public function doWebManage() {
		global $_W,$_GPC;
		
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE weid = '{$_W['uniacid']}' order by id asc");
			$grouplist= pdo_fetchall("SELECT * FROM ".tablename("mc_groups")." WHERE uniacid = '".$_W['uniacid']."' ORDER BY `orderlist` asc");
			$groupary=array();
			$groupary[0]="不限制等级";
			foreach($grouplist as $row){
				$groupary[$row['groupid']]=$row['title'];
			}
			
		}elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if(!empty($id)){
				$reply = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id = :id",array(":id"=>$id));
				$adlist=@explode(',',$reply['adlist']);
				$groupary=@explode(',',$reply['grouptype']);
			}
			$adlists = pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_ad')." WHERE weid = '{$_W['uniacid']}' order by id asc");
			$grouplist= pdo_fetchall("SELECT * FROM ".tablename("mc_groups")." WHERE uniacid = '".$_W['uniacid']."' ORDER BY `orderlist` asc");
			
			if (checksubmit('submit')) {
				$insert = array(
					'weid'=> $_W['uniacid'],
					'title' => $_GPC['title'],
					'rule' => htmlspecialchars_decode($_GPC['rule']),
					'info' => htmlspecialchars_decode($_GPC['info']),
					'starttime' => strtotime($_GPC['starttime']),
					'endtime' => strtotime($_GPC['endtime']),
					'status' => intval($_GPC['status']),
					'need' => intval($_GPC['need']),
					'gzurl' => $_GPC['gzurl'],
					'ranking' => intval($_GPC['ranking']),
					'credits' => intval($_GPC['credits']),
					'jointype' => intval($_GPC['jointype']),
					'difficulty' => intval($_GPC['difficulty']),
					'game_bg' => trim($_GPC['game_bg']),
					'game_playbg' => trim($_GPC['game_playbg']),
					'game_role1_n' => trim($_GPC['game_role1_n']),
					'game_role1_h' => trim($_GPC['game_role1_h']),
					'game_role1_m' => intval($_GPC['game_role1_m']),
					'game_role2_n' => trim($_GPC['game_role2_n']),
					'game_role2_h' => trim($_GPC['game_role2_h']),
					'game_role2_m' => intval($_GPC['game_role2_m']),
					'game_role3_n' => trim($_GPC['game_role3_n']),
					'game_role3_h' => trim($_GPC['game_role3_h']),
					'game_role3_m' => intval($_GPC['game_role3_m']),
					'game_role4_n' => trim($_GPC['game_role4_n']),
					'game_role4_h' => trim($_GPC['game_role4_h']),
					'game_role4_m' => intval($_GPC['game_role4_m']),
					'game_role4_md' => intval($_GPC['game_role4_md']),
					'grouptype' => implode(',',$_GPC['grouptype']),
					'adlist' => implode(',',$_GPC['adlist']),
					'code' => $_GPC['code'],
					'gametype' => intval($_GPC['gametype']),
					'gametime' => intval($_GPC['gametime']),
					'share_title' => trim($_GPC['share_title']) ? trim($_GPC['share_title']):"我夹到了|#成绩#|分,你来帮我加油哦！谢谢亲！",
					'helpnum' => intval($_GPC['helpnum']),
					'appid' => trim($_GPC['appid']),
					'secret' => trim($_GPC['secret']),
					
					'thumb_share' => $_GPC['thumb_share'],
					'share_link' => $_GPC['share_link'],
					'gameend_title' => $_GPC['gameend_title'],
					'gameend2_title' => $_GPC['gameend2_title'],
					'jfurl' => $_GPC['jfurl'],
					'sjurl' => $_GPC['sjurl'],
					'maxgame' => intval($_GPC['maxgame']),
					'addcredits' => intval($_GPC['addcredits']),
					'info_1' => trim($_GPC['info_1']),
					'info_2' => trim($_GPC['info_2']),
					'info_3' => trim($_GPC['info_3']),
					'info_4' => trim($_GPC['info_4']),
				);
				if (empty($id)) {
					pdo_insert('fendou_jjle_game', $insert);
				} else {
					pdo_update('fendou_jjle_game', $insert, array('id' => $id));
				}
				message('更新成功！', $this->createWebUrl('manage', array('op' => 'display')), 'success');
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('fendou_jjle_game', array('id' => $id));
			pdo_delete('fendou_jjle_reply', array('gid' => $id));
			pdo_delete('fendou_jjle_convert', array('gid' => $id));
			pdo_delete('fendou_jjle_member', array('gid' => $id));
			pdo_delete('fendou_jjle_admin', array('gid' => $id));
			message('删除成功！', $this->createWebUrl('manage', array('op' => 'display',)), 'success');
		}
		include $this->template('manage');
	}
	public function doWebUser() {
		global $_GPC,$_W;
		$gid=intval($_GPC['gid']);
		$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id ",array(':id'=>$gid));
		$op=empty($_GPC['op'])?'display':$_GPC['op'];
		if($op=='display'){
			$pindex = max(1, intval($_GPC['page']));  
			$psize = 20;
			$where="";
			$where2="";
			if($_GPC['keyword'])$where.=" and nickname like '%".$_GPC['keyword']."%'";
			if($_GPC['keyword'])$where2.=" and A.nickname like '%".$_GPC['keyword']."%'";
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('fendou_jjle_member')." where gid=:gid and helpid=0 $where",array(':gid'=>$gid));
			$start = ($pindex - 1) * $psize;
			$where="";
			$sql="SELECT A.id,A.nickname,A.sex,A.headimgurl,ifnull(B.men,0) as nums,A.mark,(A.mark+ifnull(B.mark1,0)) as marks FROM ".tablename('fendou_jjle_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as men ,ifnull(sum(mark),0) as mark1 from ".tablename('fendou_jjle_member')." where helpid>0 and gid='".$gid."' group by helpid ) AS B ON A.ID=B.helpid where  A.helpid=0 $where2 and A.gid='".$gid."' order by (A.mark+ifnull(B.mark1,0)) desc,A.mark desc,B.men desc ,A.id asc limit {$start},{$psize} ";
			if($item['jointype'])$sql="SELECT * FROM ".tablename('fendou_jjle_member')." where gid='".$gid."' $where2 order by mark desc,num desc,id asc limit {$start},{$psize}";
			$list = pdo_fetchall($sql);
			$pager = pagination($total, $pindex, $psize);
		}elseif($op=='post'){
			$id=intval($_GPC['id']);
			if(empty($id))message('参数错误，请确认操作');
			$owner=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_member')." where id=:id ",array(':id'=>$id));
			
			$sql="SELECT * FROM ".tablename('fendou_jjle_member')." where gid='".$gid."' order by mark desc,num desc,id asc";
			if(!$item['jointype']){
				$list = pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_member')." where gid=:gid and (id=:id or helpid=:helpid) order by id asc",array(':gid'=>$gid,':id'=>$id,':helpid'=>$id));
				$allmark=pdo_fetch("SELECT sum(mark) as marks,sum(num) as nums FROM ".tablename('fendou_jjle_member')." where gid=:gid and (id=:id or helpid=:helpid)",array(':gid'=>$gid,':id'=>$id,':helpid'=>$id));
				$sql="SELECT A.id FROM ".tablename('fendou_jjle_member')." AS A LEFT JOIN (select helpid,ifnull(count(*),0) as men ,ifnull(sum(mark),0) as mark1 from ".tablename('fendou_jjle_member')." where helpid>0 and gid='".$gid."' group by helpid ) AS B ON A.ID=B.helpid where A.helpid=0 and A.gid='".$gid."' order by (A.mark+ifnull(B.mark1,0)) desc,A.mark desc,B.men desc ,A.id asc ";
			}
			$listrank = pdo_fetchall($sql);
			$ranking=0;
			for($i=0;$i<count($listrank);$i++){
				if($listrank[$i]['id']==$owner['id']){
					$ranking=$i+1;
					break;
				}
			}
			
		}elseif($op=='delete'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			if(!$item['jointype']){
				pdo_delete('fendou_jjle_member',array('id'=>$id,'helpid'=>$id),' or ');
			}else{
				pdo_delete('fendou_jjle_member',array('id'=>$id));
			}
			message('删除数据成功！',$this->createWeburl('user',array('gid'=>$gid)), 'success'); 
		}
		include $this->template('adv_user');
	}
	public function doWebAdmin() {
		global $_GPC,$_W;
		$gid=intval($_GPC['gid']);
		$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_game')." WHERE id=:id ",array(':id'=>$gid));
		$op=empty($_GPC['op'])?'display':$_GPC['op'];
		if($op=='display'){
			$list = pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_admin')." where gid=:gid order by id desc",array(':gid'=>$gid));
		}elseif($op=='ok'){
			$id=intval($_GPC['id']);
			$status=intval($_GPC['st']);
			if(empty($id))message('参数错误，请确认操作');
			pdo_update('fendou_jjle_admin',array('status'=>$status),array('id'=>$id));
			if($status){
				$user=pdo_fetchcolumn("SELECT from_user FROM ".tablename('fendou_jjle_admin')." WHERE id=:id ",array(':id'=>$id));
				$this->sendtext("您申请的核销管理资格已通过！请您再次登录手机核销端即可对中奖人员进行兑奖。",$user);
			}
			message('处理完成！',$this->createWeburl('admin',array('gid'=>$gid)), 'success');
		}elseif($op=='delete'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			pdo_delete('fendou_jjle_admin',array('id'=>$id));
			message('删除数据成功！',$this->createWeburl('admin',array('gid'=>$gid)), 'success');
		}
		include $this->template('admin');
	}
	public function doWebAdvert() {
		global $_GPC, $_W;
		$gid=intval($_GPC['gid']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$category = pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_ad')." WHERE weid = '{$_W['uniacid']}' order by id desc");
		} elseif ($operation == 'post') {
			load()->func('tpl');
			$id = intval($_GPC['id']);
			if(!empty($id)) {
				$category = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_ad')." WHERE id = '$id'");
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) message('抱歉，请输入广告名称！');
				$data = array(
					'weid' => $_W['uniacid'],
					'title' => $_GPC['title'],
					'thumb' => $_GPC['thumb'],
					'description' => $_GPC['description'],
					'url' => $_GPC['url'],
				);
				if (!empty($id)) {
					pdo_update('fendou_jjle_ad', $data, array('id' => $id));
				} else {
					pdo_insert('fendou_jjle_ad', $data);
				}
				message('更新广告成功！', $this->createWebUrl('advert', array('op' => 'display')), 'success');
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('fendou_jjle_ad', array('id' => $id));
			message('广告删除成功！', $this->createWebUrl('advert', array('op' => 'display',)), 'success');
		}
		include $this->template('advert');
	}
	public function doWebGift() {
		global $_GPC, $_W;
		$gid=intval($_GPC['gid']);
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$category = pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_gift')." WHERE gid ='".$gid."' order by id desc");
		} elseif ($operation == 'post') {
			load()->func('tpl');
			$id = intval($_GPC['id']);
			if(!empty($id)) {
				$category = pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_gift')." WHERE id = '$id'");
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) message('抱歉，请输入名称！');
				$data = array(
					'weid' => $_W['uniacid'],
					'gid' => intval($_GPC['gid']),
					'title' => $_GPC['title'],
					'description' => $_GPC['description'],
					'thumb' => $_GPC['thumb'],
					'thumb2' => $_GPC['thumb2'],
					'total' => intval($_GPC['total']),
					'need' => intval($_GPC['need']),
					'remain' => intval($_GPC['remain']),
					'probalilty' => intval($_GPC['probalilty']),
				);
				if (!empty($id)) {
					pdo_update('fendou_jjle_gift', $data, array('id' => $id));
				} else {
					$data['remain']=$data['total'];
					pdo_insert('fendou_jjle_gift', $data);
				}
				message('更新成功！', $this->createWebUrl('gift', array('op' => 'display','gid' => $gid)), 'success');
			}
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			pdo_delete('fendou_jjle_gift', array('id' => $id));
			message('删除成功！', $this->createWebUrl('gift', array('op' => 'display','gid' => $gid)), 'success');
		}
		include $this->template('gift');
	}
	public function doWebRecord() {
		global $_GPC,$_W;
		$gid=intval($_GPC['gid']);
		$item=pdo_fetch("SELECT * FROM ".tablename('fendou_jjle_reply')." WHERE id=:id ",array(':id'=>$gid));
		$op=empty($_GPC['op'])?'display':$_GPC['op'];
		if($op=='display'){
			$pindex = max(1, intval($_GPC['page']));  
			$psize = 20;
			$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('fendou_jjle_convert')." where gid=:gid order by id desc",array(':gid'=>$gid));
			$start = ($pindex - 1) * $psize;
			$list = pdo_fetchall("SELECT * FROM ".tablename('fendou_jjle_convert')." where gid=:gid order by id desc limit {$start},{$psize}",array(':gid'=>$gid));
			$giftlist=pdo_fetchall("SELECT id,thumb FROM ".tablename('fendou_jjle_gift')." where gid=:gid order by id desc ",array(':gid'=>$gid));
			$userlist=pdo_fetchall("SELECT id,headimgurl FROM ".tablename('fendou_jjle_member')." where id in(SELECT uid FROM ".tablename('fendou_jjle_convert')." where gid=:gid) ",array(':gid'=>$gid));
			$giftary=array();
			foreach($giftlist as $row){
				$giftary[$row['id']]=$row['thumb'];
			}
			
			$userary=array();
			foreach($userlist as $row){
				$userary[$row['id']]=$row['headimgurl'];
			}
			$pager = pagination($total, $pindex, $psize);
		}elseif($op=='delete'){  
			$id=intval($_GPC['id']);  
			if(empty($id))message('参数错误，请确认操作');
			pdo_delete('fendou_jjle_convert',array('id'=>$id));
			message('删除数据成功！',$this->createWeburl('record',array('gid'=>$gid)), 'success');
		}
		include $this->template('adv_records');
	}
	private function sendtext($txt,$openid){
		global $_W;
		$acid=$_W['account']['acid'];
		if(!$acid){
			$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		}
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;
	}	
	private function fansInfo($openid){
		global $_W;
		$acid=$_W['account']['acid'];
		if(!$acid){
			$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		}
		$acc = WeAccount::create($acid);
		$data = $acc->fansQueryInfo($openid);
		return $data;
	}
	private function _sendpack($_openid,$id=0,$reget=0){
		global $_W;
		$reply=pdo_fetch('select * from '.tablename('j_sceneredpack_game').' where id=:id and weid=:weid ',array(':id'=>$id,':weid'=>$_W['uniacid']));
		if(empty($_openid))return false;
		$min=$reply['firstmin']>=100? $reply['firstmin']:100;
		$man=$reply['firstmax']>=100? $reply['firstmax']:105;
		if($man<$min)$man=$min+1;
		$fee = mt_rand($min,$man);
 		$url='https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
		$pars['nonce_str'] =random(32);
		$pars['mch_billno'] =time().random(3,1);
		$pars['mch_id']=$reply['mchid'];
		$pars['wxappid'] =$reply['appid'];
		$pars['send_name'] =$reply['send_name'];
		$pars['re_openid'] =$_openid;
		$pars['total_amount'] =$fee;
		$pars['total_num'] =1;
		$pars['wishing'] =(empty($reply['wishing'])?'没什么，就是想送你一个红包':$reply['wishing']);
		$pars['client_ip'] =$reply['ip'];
		$pars['act_name'] =$reply['title'];
		$pars['remark'] =$reply['title'];
		
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key=".$reply['signkey'];
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = IA_ROOT.'/addons/j_sceneredpack/cert_2/'.$reply['id'].'/rootca.pem';
        $extras['CURLOPT_SSLCERT'] =IA_ROOT.'/addons/j_sceneredpack/cert_2/'.$reply['id'].'/apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY'] =IA_ROOT.'/addons/j_sceneredpack/cert_2/'.$reply['id'].'/apiclient_key.pem';
		$procResult = null;
		load()->func('communication');
        $resp = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult = $resp;
        } else {
			$arr=json_decode(json_encode((array) simplexml_load_string($resp['content'])), true);
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult =  array('errno'=>0,'error'=>'success');
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = array('errno'=>-2,'error'=>$error);
                }
            } else {
				$procResult = array('errno'=>-1,'error'=>'未知错误');				
            }
        }
		$rec = array();
		$rec['log'] = $error;
		$rec['weid']=$_W['uniacid'];
		$rec['from_user']=$_openid;
		$rec['fee']=$fee;
		$rec['gid']=$id;
		$rec['createtime']=time();
		$rec['status']=$_status;
        if ($procResult['errno']!=0) {
			if(!$reget){
				$rec['completed']=$procResult['errno'];
				pdo_insert('j_sceneredpack_records',$rec);
			}else{
				pdo_update('j_sceneredpack_records',$rec,array('id'=>$rid));
			}
        } else {
			$rec['completed']=1;
			if(!$reget){
				pdo_insert('j_sceneredpack_records',$rec);
			}else{
				$rec['createtime']=time();
				pdo_update('j_sceneredpack_records',$rec,array('id'=>$id));
			}
        }
		return $procResult;
	}
}