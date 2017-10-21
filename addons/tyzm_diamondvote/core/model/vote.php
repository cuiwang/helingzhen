<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Tyzm_Vote{
	public function __construct() {
		global $_W;
	}
	public $tablereply = 'tyzm_diamondvote_reply';
	public $tablevoteuser = 'tyzm_diamondvote_voteuser';
	public $tablevotedata = 'tyzm_diamondvote_votedata';
	public $tablegift = 'tyzm_diamondvote_gift';
	public $tablecount = 'tyzm_diamondvote_count';
	public $table_fans = 'tyzm_diamondvote_fansdata';
    function setvote($userinfo,$rid,$id,$latitude,$longitude,$type=0){
		//投票start
		global $_W , $_GPC;
		$nickname=$userinfo['nickname'];
		$openid=$userinfo['openid'];
		$avatar=$userinfo['avatar'];
		$oauth_openid=$userinfo['oauth_openid'];
		$follow=$userinfo['follow'];
		$reply = pdo_fetch("SELECT starttime,endtime,votestarttime,voteendtime,config,area,status FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));
		if(empty($reply)){
			return array('status' => '0', 'msg' => "参数错误");
		}

		$reply=@array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
		//活动已结束
		if($reply['starttime']>time()){
			return array('status' => '0', 'msg' => "活动还没有开始");
		}

		//活动未开始
		if($reply['endtime']<time()){
			return array('status' => '0', 'msg' => "活动已经结束");
		}

		//活动未开始
		if(empty($reply['status'])){
			return array('status' => '0', 'msg' => "活动已禁用");
		}

		//是否关注
		if($follow!=1 &&($reply['isfollow']==1||$reply['isfollow']==3)){
			return array('status' => '500', 'msg' => "没有关注");
		}

		//投票时间
		if($reply['votestarttime']> time()){
			return array('status' => '0', 'msg' => "未开始投票！");
		}elseif($reply['voteendtime']<time()){
			return array('status' => '0', 'msg' => "已结束投票！");
		}
//验证码

		if(!empty($reply['verifycode']) && empty($type)){
	        $verify = $userinfo['verify'];
			if (empty($verify)) {
				return array('status' => '0', 'msg' => "请输入验证码");
			}
			$result = checkcode($verify);
			if (empty($result)) {
				return array('status' => '0', 'msg' => "输入验证码错误");
			}
		}
		//是否达到最小人数
		if(!empty($reply['minnumpeople'])){
			$condition="";
			if($reply['ischecked']==1){
			  $condition.=" AND status=1 ";
			}
			$jointotal = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevoteuser) . " WHERE   rid = :rid  ".$condition , array(':rid' => $rid));
			if($reply['minnumpeople']>$jointotal){
				return array('status' => '0', 'msg' => "活动还未开始，没有达到最小参赛人数！");
			}
		}

		//城市限制
		if(!empty($reply['area']) && empty($type)){
			$locationStatus = false;
			//return array('status' => '0', 'msg' => $latitude."--".$_W['account']['token']);
			if((empty($latitude) || empty($longitude)) && empty($reply['locationtype'])){
				return array('status' => '0', 'msg' => "没有获得地理位置信息，请打开手机gps，再刷新试试！！（1）");
			}

			$address=m('common') ->Get_address($latitude,$longitude);
			$area = explode(',',$reply['area']);
			foreach ($area as $key => $value){
				if (strpos($address,$value) !== false) {
					$locationStatus = true;
					break;
				}
			}
			//$this->json_exit(0,$address);
			if(empty($locationStatus)){
				return array('status' => '0', 'msg' => "很抱歉，本次活动只在【".$reply['area']."】进行；其他地区暂不能参与。");
			}
		}
        //判断黑名单

        $blacklist=pdo_get('tyzm_diamondvote_blacklist', array('value IN' =>  array($_W['clientip'],$openid,$oauth_openid),'uniacid'=>$_W['uniacid']));
        if($blacklist){
        	return array('status' => '0', 'msg' => "系统检到您投票异常，暂时无法投票！");
        }


		$voteuser = pdo_fetch("SELECT id,noid,name,status,oauth_openid,openid,votenum,giftcount,locktime FROM " . tablename($this->tablevoteuser) . " WHERE id = :id AND rid = :rid  ", array(':id' => $id,':rid' => $rid));
		
		if(empty($voteuser)){
			return array('status' => '0', 'msg' => "没有该编号用户，请检查后再输入！");
		}

		if($voteuser['locktime']>time()){
			return array('status' => '0', 'msg' => "该用户投票被锁定,恢复时间：<br>".date('Y-m-d H:i:s',$voteuser['locktime']) );
		}

		if(!empty($reply['perminute'])&&!empty($reply['perminutevote'])&&!empty($reply['lockminutes'])){
			$pertime=(time()-intval($reply['perminute'])*60);
			$pervotenum = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevotedata) . " WHERE   rid = :rid AND  tid=:tid AND createtime>$pertime" , array(':rid' => $rid,':tid' => $voteuser['id'] ));
			
			if($pervotenum>=$reply['perminutevote']){
				$setlock=array('locktime'=>(time()+$reply['lockminutes']*60));
				pdo_update($this->tablevoteuser, $setlock, array('id' => $voteuser['id']));
				return array('status' => '0', 'msg' => "检测到恶意刷票，系统锁定".$reply['lockminutes']."分钟！" );
			}
		}
		//未审核过程不能投票
		if($voteuser['status']==0){
			return array('status' => '0', 'msg' => "审核中暂时无法投票");
		}
		
		//不能给自己投票
		if($voteuser['openid']==$openid && empty($reply['isoneself'])){
				return array('status' => '0', 'msg' => "不能给自己投票。");
		} 
			//每人最多投
			$everyonevotetotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tablevotedata) . " WHERE rid = :rid AND openid = :openid AND votetype=0 ", array(':rid' => $rid,':openid'=>$openid));
			
			if($everyonevotetotal>=$reply['everyonevote']){
				return array('status' => '0', 'msg' => "您总共已投了".$everyonevotetotal."票，超过最大投票次数，感谢你参与我们的活动");
			}
			//每日每人投票次数
			$dailystarttime=mktime(0,0,0);//当天：00：00：00
			$dailyendtime = mktime(23,59,59);//当天：23：59：59
			$dailytimes = '';
			$dailytimes .= ' AND createtime >=' .$dailystarttime;
			$dailytimes .= ' AND createtime <=' .$dailyendtime;
			$dailyvotetotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tablevotedata) . " WHERE rid = :rid   AND openid = :openid AND votetype=0  ".$dailytimes, array(':rid' => $rid,':openid'=>$openid));
			
			if($dailyvotetotal>=$reply['dailyvote']){
				return array('status' => '0', 'msg' => "每人每日只能投".$reply['dailyvote']."票，明天再来吧");
			}
			$dailyusertotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tablevotedata) . " WHERE tid = :tid   AND rid = :rid   AND openid = :openid AND votetype=0  ".$dailytimes, array(':tid' => $id,':rid' => $rid,':openid'=>$openid));  
			if($dailyusertotal>=$reply['everyoneuser']){
				return array('status' => '0', 'msg' => "今天只能给 TA 投".$reply['everyoneuser']."票，明天再来吧");
			} 
			
			//投票start
			$votedata = array(
					'rid'=>$rid, 
					'tid'=>$id,
					'uniacid'=>$_W['uniacid'],
					'oauth_openid'=>$oauth_openid,
					'openid'=>$openid,
					'avatar' =>$avatar,
					'nickname'=>$nickname,
					'user_ip'=>$_W['clientip'],
					'votetype'=>0,
					'status'=>0,
					'createtime'=>time()
			);
			
			pdo_insert($this->tablevotedata, $votedata);  
			$voteinsertid=pdo_insertid();
			if($voteinsertid){
				//更新票数
				//pdo_update($this->tablereply, $insert, array('id' => $id));
				$setvotesql = 'update ' . tablename($this->tablevoteuser) . ' set votenum=votenum+1,lastvotetime='.time().' where id = '.$id;
				if(pdo_query($setvotesql)){
					//今日票数
					$dailynum=($reply['everyonevote']-$everyonevotetotal-1)<($reply['dailyvote']-$dailyvotetotal-1)?($reply['everyonevote']-$everyonevotetotal-1):($reply['dailyvote']-$dailyvotetotal-1);
					
						if(empty($reply['isvotemsg'])){
							$uservoteurl=$_W['siteroot'].'app/'.murl('entry/module/view',array('m' => 'tyzm_diamondvote','rid'=>$rid,'id' => $id,'i' => $_W['uniacid']));
							$content='您的好友【'.$nickname.'】给你（'.$voteuser['noid'].'）号【'.$voteuser['name'].'】投了一票！目前'.($voteuser['votenum']+1).'票。<a href=\"'.$uservoteurl.'\">点击查看详情<\/a>';
							m('user') ->sendkfinfo($voteuser['openid'],$content);	
						}
								
						//赠送积分或其他！
						if(!empty($reply['votegive_num'])){
							m('present')->upcredit($openid,$reply['votegive_type'],$reply['votegive_num'],'tyzm_diamondvote');
						}
						//奖励end
						if(!empty($reply['isredpack'])){
							$chance=(100/$reply['lotterychance']);
							if(mt_rand(1,intval($chance))==1){
								return array('status' => '1', 'msg' => "投票成功，今天您还可以投".$dailynum."票。",'voteid'=>$voteinsertid);
							}else{
								return array('status' => '1', 'msg' => "投票成功，今天您还可以投".$dailynum."票。");
							}
						}else{
							return array('status' => '1', 'msg' => "投票成功，今天您还可以投".$dailynum."票。");
						}
						 
						
						
						
				}else{
					pdo_delete($this->tablevotedata, array('id' => $voteinsertid));
					return array('status' => '0', 'msg' => "投票失败，请重试！");
				}
			}else{
				return array('status' => '0', 'msg' => "发生错误，请重试！");
			}
			//投票结束
		//投票结束
	}	



	
}