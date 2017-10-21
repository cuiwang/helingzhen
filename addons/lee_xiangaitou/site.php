<?php
//decode by QQ:270656184 http://www.yunlu99.com/
defined('IN_IA')or exit('Access Denied');
class Lee_xiangaitouModuleSite extends WeModuleSite {public $_debug ='1';
public $_weixin ='1';
public $_appid ='';
public $_appsecret ='';
public $_accountlevel ='';
public $_uniacid ='';
public $_fromuser ='';
public $_nickname ='';
public $_headimgurl ='';
public $_auth2_openid ='';
public $_auth2_nickname ='';
public $_auth2_headimgurl ='';
function __construct()
{
	global $_W, $_GPC;
$this->_fromuser =$_W['fans']['from_user'];
if ($_SERVER['HTTP_HOST'] == '127.0.0.1')
{
	$this->_fromuser ='debug';
}
$this->_uniacid =$_W['uniacid'];
$account =account_fetch($this->_uniacid);
$this->_auth2_openid ='auth2_openid_'.$_W['uniacid'];
$this->_auth2_nickname ='auth2_nickname_'.$_W['uniacid'];
$this->_auth2_headimgurl ='auth2_headimgurl_'.$_W['uniacid'];
$this->_appid =$this->module['config']['appid'];
$this->_appsecret =$this->module['config']['secret'];
$this->_accountlevel =$account['level'];
if ($this->_accountlevel == 4)
{
	$this->_appid =$account['key'];
	$this->_appsecret =$account['secret'];
	}
	}
	public function doWebManage()
	{global $_W,$_GPC;
	$uniacid =$_W['uniacid'];
	$operation =!empty($_GPC['op'])? $_GPC['op'] : 'display';if ($operation == 'display'){$pindex =max(1, intval($_GPC['page']));$psize =20;$list =pdo_fetchall("SELECT * FROM " . tablename('lee_xiangaitou'). " WHERE uniacid =:uniacid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1)* $psize . ',' . $psize, array(':uniacid' => $uniacid));$total =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lee_xiangaitou'). " WHERE uniacid =:uniacid ", array(':uniacid' => $uniacid));$pager =pagination($total, $pindex, $psize);}else if ($operation == 'delete'){$id =$_GPC['id'];pdo_delete('lee_xiangaitou', array('id' => $id));message('删除成功！', referer(), 'success');}include $this->template("manage");}public function doWebUserlist(){global $_W,$_GPC;$operation =!empty($_GPC['op'])? $_GPC['op'] : 'display';if ($operation == 'display'){$xgtid=$_GPC['xgtid'];$xgt =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou')."WHERE id = :id",array(":id" => $xgtid));if(empty($xgt)){message("掀盖头活动删除或不存在");}$itemid=$_GPC['itemid'];$keyword=$_GPC['keyword'];$where ='';$params =array(':xgtid' => $xgtid );if(!empty($keyword)){$where .= ' and nickname like :nickname';$params[':nickname']="%$keyword%";}$pindex =max(1, intval($_GPC['page']));$psize =20;$list =pdo_fetchall("SELECT * FROM " . tablename('lee_xiangaitou_user')." WHERE xgtid =:xgtid ".$where."  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1)* $psize . ',' . $psize, $params);$total =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lee_xiangaitou_user'). " WHERE xgtid =:xgtid  ".$where, $params);$pager =pagination($total, $pindex, $psize);}else if ($operation == 'delete'){$id =$_GPC['id'];pdo_delete('lee_xiangaitou_user_record', array('uid' => $id));pdo_delete('lee_xiangaitou_user_award', array('uid' => $id));pdo_delete('lee_xiangaitou_user', array('id' => $id));message('删除成功！', referer(), 'success');}include $this->template("userlist");}public function doWebRecordList(){global $_W,$_GPC;$operation =!empty($_GPC['op'])? $_GPC['op'] : 'display';$xgtid=$_GPC['xgtid'];$uid=$_GPC['uid'];$xgt =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou')."WHERE id = :id",array(":id" => $xgtid));$where ='';$params =array(':xgtid' => $xgtid );if($_GPC['uid']!=''){$where.=" and r.uid=:uid";$params[':uid']=$uid;}$keyword =$_GPC['keywords'];if (!empty($keyword)){$where .= ' and (u.nickname like :nickname) or (u.mobile like :mobile)';$params[':nickname'] ="%$keyword%";$params[':mobile'] ="%$keyword%";}if ($operation == 'display'){$pindex =max(1, intval($_GPC['page']));$psize =20;$list =pdo_fetchall("SELECT r.*,u.nickname,u.mobile,u.address,u.realname FROM " . tablename('lee_xiangaitou_user_record'). "  r left join ".tablename('lee_xiangaitou_user')." u on r.uid=u.id WHERE r.xgtid =:xgtid  ".$where." ORDER BY  id DESC LIMIT " . ($pindex - 1)* $psize . ',' . $psize,$params);$total =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lee_xiangaitou_user_record'). " r left join ".tablename('lee_xiangaitou_user')."  u  on r.uid=u.id WHERE r.xgtid =:xgtid  " .$where, $params);$pager =pagination($total, $pindex, $psize);}elseif ($operation == 'delete'){$id =$_GPC['id'];pdo_delete('lee_xiangaitou_user_record', array('id' => $id));message('删除成功！', referer(), 'success');}load()->func('tpl');include $this->template('recordlist');}public function doWebAwardList(){global $_W,$_GPC;$operation =!empty($_GPC['op'])? $_GPC['op'] : 'display';$xgtid=$_GPC['xgtid'];$uid=$_GPC['uid'];$xgt =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou')."WHERE id = :id",array(":id" => $xgtid));$params =array(':xgtid' => $xgtid );$stauts=0;if(!empty($_GPC['status'])){$stauts=$_GPC['status'];}if($stauts!=0){$where=" and a.status=$stauts";}if($_GPC['uid']!=''){$where.=" and a.uid=:uid";$params[':uid']=$uid;}$keyword =$_GPC['keywords'];if (!empty($keyword)){$where .= ' and (u.nickname like :nickname) or (u.mobile like :mobile)';$params[':nickname'] ="%$keyword%";$params[':mobile'] ="%$keyword%";}if ($operation == 'display'){$pindex =max(1, intval($_GPC['page']));$psize =20;$list =pdo_fetchall("SELECT a.*,u.nickname,u.mobile,u.address,u.realname FROM " . tablename('lee_xiangaitou_user_award'). " a left join ".tablename('lee_xiangaitou_user')." u on a.uid=u.id  WHERE a.xgtid =:xgtid ".$where." ORDER BY  a.id DESC LIMIT " . ($pindex - 1)* $psize . ',' . $psize, $params);$total =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lee_xiangaitou_user_award'). " a left join ".tablename('lee_xiangaitou_user')." u on a.uid=u.id  WHERE a.xgtid =:xgtid  ".$where, $params);$pager =pagination($total, $pindex, $psize);}elseif ($operation == 'delete'){$id =$_GPC['id'];pdo_delete('lee_xiangaitou_user_award', array('id' => $id));message('删除成功！', referer(), 'success');}elseif($operation=='fj'){$id =$_GPC['id'];pdo_update('lee_xiangaitou_user_award',array('status'=>3),array('id' => $id));message('发奖处理成功！', referer(), 'success');}load()->func('tpl');include $this->template('awardlist');}public function statusText($stauts){$statusText="未知状态";switch($stauts){case 1: $statusText="未领奖";break;case 2: $statusText="申请领奖";break;case 3: $statusText="已领奖";break;}return $statusText;}public function doMobileIndex(){global $_W, $_GPC;$uniacid =$this->_uniacid;$xgtid =$_GPC['xgtid'];$method ='index';$authurl =$_W['siteroot'] .'app/'. $this->createMobileUrl($method, array('xgtid'=>$xgtid), true). '&authkey=1';$url =$_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('xgtid'=>$xgtid), true);if (isset($_COOKIE[$this->_auth2_openid])){$from_user =$_COOKIE[$this->_auth2_openid];$nickname =$_COOKIE[$this->_auth2_nickname];$headimgurl =$_COOKIE[$this->_auth2_headimgurl];}else {if (isset($_GPC['code'])){$userinfo =$this->oauth2($authurl);if (!empty($userinfo)){$from_user =$userinfo["openid"];$nickname =$userinfo["nickname"];$headimgurl =$userinfo["headimgurl"];}else {message('授权失败!');}}else {if (!empty($this->_appsecret)){$this->toAuthUrl($url);}}}$isuser =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou_user')."WHERE xgtid = :xgtid AND from_user = :from_user",array('xgtid'=>$xgtid,":from_user" => $from_user));if(empty($isuser)){$user_data =array("xgtid" => $xgtid, "from_user" => $from_user, "nickname" => $nickname, "headimgurl" => $headimgurl, "createtime"=>TIMESTAMP );pdo_insert('lee_xiangaitou_user',$user_data);}$user =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou_user')."WHERE xgtid = :xgtid AND from_user = :from_user",array('xgtid'=>$xgtid,":from_user" => $from_user));$xgt =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou')."WHERE id = :id",array(":id" => $xgtid));if(empty($xgt)){message("掀盖头活动删除或不存在!");}include $this->template('index');
	}
	public function doMobilePlay()
	{
		global $_GPC,$_W;
		$uniacid =$_W['uniacid'];
		$xgtid=$_GPC['xgtid'];
		$uid=$_GPC['uid'];
		$from_user =$token['openid'];
		$xgt =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou')."WHERE id = :id",array(":id" => $xgtid));
		$user =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou_user')."WHERE id = :uid",array(":uid" => $uid));
		$limitCount=$xgt['day_play_count'];$clientUser =$this->checkUser();
		$playCount=$this->findUserRecordCount($xgtid,$uid);$limitCount=$xgt['day_play_count'];
		$leftPlayCount=$limitCount-$playCount;
		$res=array();
		if(empty($xgt))
		{
			$res['code']=0;$res['msg']="掀盖头活动删除或不存在!";
			}elseif(TIMESTAMP<$xgt['starttime'])
			{
				$res['code']=0;$res['msg']="活动还未开始呢，歇会再来吧!";
	}
	elseif(TIMESTAMP>$xgt['endtime'])
	{
		$res['code']=0;
		$res['msg']="活动已结束，下次再来吧!";
	}
	//elseif(empty($user))
	//{
	//	$res['code']=0;
	//	$res['msg']="用户删除或不存在!";
	//	}
		//elseif (empty($clientUser))
		//{
		//	$res['code'] =0;
		//$res['msg'] ="请授权登录后再进行抽奖哦！";
		//}
elseif($leftPlayCount<=0)
{
	$res['code'] =0;$res['msg'] ="今天没有机会了，明天再来抽奖吧!";}else{$res['code'] =1;}echo json_encode($res);exit;}public function checkUser(){global $_GPC;$session =$_COOKIE[$this->_auth2_openid];return $session;}public function doMobileRule(){global $_GPC, $_W;$uniacid =$_W['uniacid'];$xgtid =$_GPC['xgtid'];$xgt =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou')."WHERE id = :id",array(":id" => $xgtid));include $this->template('rule');}public function doMobileMyaward(){global $_GPC, $_W;$uniacid =$_W['uniacid'];$xgtid =$_GPC['xgtid'];$uid =$_GPC['uid'];$xgt =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou')."WHERE id = :id",array(":id" => $xgtid));$user =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou_user')."WHERE id = :uid",array(":uid" => $uid));$awards =pdo_fetchall("SELECT * FROM".tablename('lee_xiangaitou_user_award')."WHERE xgtid = :xgtid AND uid = :uid",array(":xgtid" => $xgtid,":uid" => $uid));include $this->template('myaward');}public function doMobilemyPrizeServlet(){global $_W, $_GPC;$uniacid =$_W['uniacid'];$xgtid =$_GPC['xgtid'];$uid =$_GPC['uid'];file_put_contents('D:text.php',$xgtid.$uid);$xgt_user =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou_user_award')."WHERE xgtid = :xgtid AND uid = :uid",array('xgtid'=>$xgtid,":uid" => $uid));$arr =array('award'=>$xgt_user['level'], 'award_name'=>$xgt_user['award_name'], 'award_level'=>$xgt_user['award_level'] );die(json_encode($arr));
}
public function doMobileselectPrizeServlet()
{
	global $_W, $_GPC;
	$uniacid =$_W['uniacid'];
	$xgtid =$_GPC['xgtid'];
	$uid =$_GPC['uid'];
	$from_user =$_GPC['from_user'];
	$xgt =pdo_fetch("SELECT * FROM".tablename('lee_xiangaitou')."WHERE id = :id",array(":id" => $xgtid));
	$prize=$this->createPlayRecored($xgt,$uid,$from_user);
	$arr =array('remark'=>$prize['remark'], 'level'=>$prize['level'], 'award_name'=>$prize['award_name'], 'award_level'=>$prize['award_level'] );
	die(json_encode($arr));
	}
	public function createPlayRecored($xgt,$uid,$openid)
	{
		$prize =$this->get_rand(array("0" => $xgt['prize_p_0'], "1" => $xgt['prize_p_1'], "2" => $xgt['prize_p_2'], "3" => $xgt['prize_p_3'], "4" => $xgt['prize_p_4'], "5" => $xgt['prize_p_5'] ));
		$user_ward_count=$this->findUserAwardCount($uid);
		if($user_ward_count>=$xgt['award_count']){$prize=0;}
		if($prize!=0)
		{
			$already_award_count=$this->findAwardLevelCount($xgt['id'],$prize);switch($prize){case 1: $prize_count=$xgt['prize_num_1'];break;case 2: $prize_count=$xgt['prize_num_2'];break;case 3: $prize_count=$xgt['prize_num_3'];break;case 4: $prize_count=$xgt['prize_num_4'];break;case 5: $prize_count=$xgt['prize_num_5'];break;}if($already_award_count>=$prize_count){$prize=0;}}switch($prize){case 0: $award_name=$xgt['prize_name_0'];$award_level='没中奖';break;case 1: $award_name=$xgt['prize_name_1'];$award_level=$xgt['prize_level_1'];break;case 2: $award_name=$xgt['prize_name_2'];$award_level=$xgt['prize_level_2'];break;case 3: $award_name=$xgt['prize_name_3'];$award_level=$xgt['prize_level_3'];break;case 4: $award_name=$xgt['prize_name_4'];$award_level=$xgt['prize_level_4'];break;case 5: $award_name=$xgt['prize_name_5'];$award_level=$xgt['prize_level_5'];break;}$recordData=array('xgtid'=>$xgt['id'], 'openid'=>$openid, 'uid'=>$uid, 'award_level'=>$award_level, 'award_name'=>$award_name, 'level'=>$prize, 'createtime'=>TIMESTAMP );pdo_insert('lee_xiangaitou_user_record',$recordData);if($prize!=0){$nonceStr =$this->createNonceStr();$award_record=array('xgtid'=>$xgt['id'], 'openid'=>$openid, 'uid'=>$uid, 'award_level'=>$award_level, 'award_name'=>$award_name, 'status'=>1, 'level'=>$prize, 'remark'=>$nonceStr, 'createtime'=>TIMESTAMP );pdo_insert('lee_xiangaitou_user_award',$award_record);}$prize_award =array('remark'=>$nonceStr, 'level'=>$prize, 'award_level'=>$award_level, 'award_name'=>$award_name );return $prize_award;}function get_rand($proArr){$result ='';$proSum =array_sum($proArr);foreach ($proArr as $key => $proCur){$randNum =mt_rand(1, $proSum);if ($randNum <= $proCur){$result =$key;break;}else {$proSum -= $proCur;}}unset($proArr);return $result;}public function findUserRecordCount($xgtid,$uid){$today_beginTime =strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));$today_endTime =strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));$count =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lee_xiangaitou_user_record'). " WHERE  uid=:uid and xgtid=:xgtid and createtime<=:endtime and  createtime>=:starttime ", array(':uid' => $uid, ":xgtid" =>$xgtid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));return $count;
			}
			public function findUserAwardCount($uid)
			{
				$count =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lee_xiangaitou_user_award'). " WHERE  uid=:uid", array(':uid' => $uid));return $count;}public function findAwardLevelCount($xgtid,$level){$count =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lee_xiangaitou_user_award'). " WHERE  level=:level and xgtid=:xgtid", array(':level' => $level,':xgtid'=>$xgtid));return $count;}public function doMobilesaveInfoServlet()
				{
					global $_W, $_GPC;
				$uniacid =$_W['uniacid'];
				$xgtid =$_GPC['xgtid'];
				$uid =$_GPC['uid'];
				$from_user =$_GPC['from_user'];
				$realname =$_GPC['realname'];
				$mobile =$_GPC['mobile'];$address =$_GPC['address'];$remark =$_GPC['remark'];
				$user_info=array('realname'=>$realname, 'mobile'=>$mobile, 'address'=>$address );
				file_put_contents('D:userinfo.php',$realname.$mobile.$address."++++++++++".$uid.$remark);
pdo_update('lee_xiangaitou_user',$user_info,array('id'=>$uid));
if(pdo_update('lee_xiangaitou_user_award',array('status'=>2),array('remark'=>$remark)))
{
	$arr =array('ret'=>1 );
}else{
	$arr =array('ret'=>0 );}die(json_encode($arr));
	}
	public function toAuthUrl($url)
	{
		global $_W;
		$oauth2_code ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url). "&response_type=code&scope=snsapi_base&state=0#wechat_redirect";header("location:$oauth2_code");}public function oauth2($authurl){global $_GPC, $_W;load()->func('communication');
		$state =$_GPC['state'];
		$code =$_GPC['code'];
		$oauth2_code ="https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->_appid . "&secret=" . $this->_appsecret . "&code=" . $code . "&grant_type=authorization_code";$content =ihttp_get($oauth2_code);
		$token =@json_decode($content['content'], true);if (empty($token)|| !is_array($token)|| empty($token['access_token'])|| empty($token['openid'])){echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';exit;
		}
		$from_user =$token['openid'];
		if ($this->_accountlevel != 2){$authkey =intval($_GPC['authkey']);if ($authkey == 0){$url =$authurl;$oauth2_code ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url). "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";header("location:$oauth2_code");
		}
		}
		else {
			$follow =pdo_fetchcolumn("SELECT follow FROM ".tablename('mc_mapping_fans')." WHERE openid = :openid AND acid = :acid", array(':openid' => $from_user, ':acid' => $_W['uniacid']));if ($follow == 1){$state =1;}else {$url =$authurl;$oauth2_code ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url). "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";header("location:$oauth2_code");}}if ($state == 1){$oauth2_url ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->_appid . "&secret=" . $this->_appsecret . "";$content =ihttp_get($oauth2_url);$token_all =@json_decode($content['content'], true);if (empty($token_all)|| !is_array($token_all)|| empty($token_all['access_token'])){echo '<h1>获取微信公众号授权失败[无法取得access_token], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';exit;}$access_token =$token_all['access_token'];$oauth2_url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $from_user . "&lang=zh_CN";}else {$access_token =$token['access_token'];$oauth2_url ="https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $from_user . "&lang=zh_CN";}$content =ihttp_get($oauth2_url);$info =@json_decode($content['content'], true);if (empty($info)|| !is_array($info)|| empty($info['openid'])|| empty($info['nickname'])){echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>' . 'state:' . $state . 'nickname' . 'uniacid:';exit;}$headimgurl =$info['headimgurl'];$nickname =$info['nickname'];setcookie($this->_auth2_headimgurl, $headimgurl, time()+ 3600 * 24);setcookie($this->_auth2_nickname, $nickname, time()+ 3600 * 24);setcookie($this->_auth2_openid, $from_user, time()+ 3600 * 24);return $info;}private function createNonceStr($length =16){$chars ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";$str ="";
for ($i =0;$i < $length;$i++)
{
	$str .= substr($chars, mt_rand(0, strlen($chars)- 1), 1);
}
return $str;
}
}