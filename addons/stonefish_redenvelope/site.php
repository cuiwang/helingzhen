<?php
/**
 * 抢红包模块
 *
 */
defined('IN_IA') or exit('Access Denied');

class Stonefish_redenvelopeModuleSite extends WeModuleSite {
    
	public function gethome() {
        global $_W;
        $articles = pdo_fetchall("SELECT id,rid, title FROM " . tablename('stonefish_redenvelope_reply') . " WHERE uniacid = '{$_W['uniacid']}'");
        if (!empty($articles)) {
            foreach ($articles as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('index', array('id' => $row['rid'])));
            }
            return $urls;
        }
    }
	public function doMobileShare() {
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$rid = intval($_GPC['rid']);
		$from_user = $_W['fans']['from_user'];
		$autype = 1;
		//查询是否为关注用户
		$follow = pdo_fetch("select follow,uid from ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid order by `fanid` desc",array(":openid"=>$from_user,":uniacid"=>$_W['uniacid']));
		if($follow['follow']){
			$appUrl=$this->createMobileUrl('firend', array('rid' => $rid,"from_user" => $from_user,"uid" => $uid,"fid" => $follow['uid']),true);
			$appUrl=substr($appUrl,2);
			$url = $_W['siteroot'] ."app/".$appUrl;
			header("location: $url");
		    exit;
		}else{
			$appUrl= $this->createMobileUrl('auth2', array('au' => 'share','rid' => $rid,'uid' => $uid),true);
		    $appUrl = substr($appUrl,2);
            $redirect_uri = $_W['siteroot'] ."app/".$appUrl ;			
			$appid = $_W['account']['key'];
            $secret = $_W['account']['secret'];
			
		    $setting = $this->module['config'];
            if ($_W['account']['level']!=4 && !empty($setting) && ! empty($setting['appid']) && ! empty($setting['secret'])) { // 判断是否是借用设置
                $appid = $setting['appid'];
                $secret = $setting['secret'];
            }
			if($_W['account']['level']!=4 && (empty($setting) || empty($setting['appid']) || empty($setting['secret']))){// 普通号又没有借用
				if (isset($_COOKIE["user_oauth2_wuopenid"])){
					$user_oauth2_wuopenid = $_COOKIE["user_oauth2_wuopenid"];
				}else{
					$user_oauth2_wuopenid = time();
				}
				$appUrl=$this->createMobileUrl('firend', array('rid' => $rid,"from_user" => $user_oauth2_wuopenid,"uid" => $uid,"fid" => 0),true);
			    $appUrl=substr($appUrl,2);
			    $url = $_W['siteroot'] ."app/".$appUrl;
				//设置cookie信息
				setcookie("user_oauth2_wuopenid", $user_oauth2_wuopenid, time()+3600*24*7);
			    header("location: $url");
		        exit;
			}
            $oauth2_code = "";
            if ($autype == 0) { // base
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
            } elseif ($autype == 1) { // info
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            }		
            header("location: $oauth2_code");
		    exit;
		}
		//查询是否为关注用户
	}
	/**
     * 认证第二部获取 openid和accessToken
     */
    public function doMobileauth2(){
        global $_W, $_GPC;
        $au = $_GPC['au'];
        $code = $_GPC['code'];                
        $rid = $_GPC['rid'];
		$uid = $_GPC['uid'];
		$tokenInfo = $this->getAuthTokenInfo($code);
        $from_user = $tokenInfo['openid'];
        $accessToken = $tokenInfo['access_token'];      
        if ($au == "msg") { // 图文点击进去的
		    $appUrl= $this->createMobileUrl('index', array('rid' => $rid,"from_user" => $from_user,"access_token" => $accessToken),true);
		    $appUrl=substr($appUrl,2);		
            $url = $_W['siteroot'] . "app/".$appUrl;
        } elseif ($au == "share") { // 好友进入认证
            $appUrl=$this->createMobileUrl('firend', array('rid' => $rid,"from_user" => $from_user,"uid" => $uid,"access_token" => $accessToken),true);
			$appUrl=substr($appUrl,2);
			$url = $_W['siteroot'] ."app/".$appUrl;
        }
        header("location: $url");
    }
	/**
     * 获取token信息
     *
     * @param unknown $code
     * @return unknown
     */
    public function getAuthTokenInfo($code){
        global $_GPC, $_W;
		$appid = $_W['account']['key'];
        $secret = $_W['account']['secret'];       
        $setting = $this->module['config'];		
        if (!empty($setting) && !empty($setting['appid']) && !empty($setting['secret'])) { // 判断是否是借用设置
            $appid = $setting['appid'];
            $secret = $setting['secret'];
        }
        load()->func('communication');
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        
        if (empty($token) || ! is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit();
        }        
        return $token;
    }
	/**
     * 获取用户信息
     *
     * @param unknown $openid
     * @param unknown $access_token            
     * @return unknown
     */
    public function getUserInfo($openid, $access_token)    {
		load()->func('communication');
        $tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
        $content = ihttp_get($tokenUrl);
        
        $userInfo = @json_decode($content['content'], true);
        return $userInfo;
    }
    public function doMobileFirend() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        $from_user = $_GPC['from_user'];
		$uid = $_GPC['uid'];
		$fid = $_GPC['fid'];
		$access_token = $_GPC['access_token'];
		if(empty($from_user)) {
		    //没有取得OPENID设置时间为OPENID保存分享记录
		   $from_user = '无OPENID'.TIMESTAMP;
		}else{
			if($fid){
				load()->model('mc');
				$firend = mc_fetch($fid, array('avatar','nickname')); // 好友信息
				$firend['openid'] = $from_user;
				$firend['nickname'] = $firend['nickname'];
				$firend['headimgurl'] = $firend['avatar'];
			}elseif($fid==0){
				$firend = array();
				$firend['openid'] = $from_user;
				$firend['nickname'] = '匿名好友';
				$firend['headimgurl'] = '../addons/stonefish_redenvelope/template/images/avatar.jpg';
			}else{
				$firend = $this->getUserInfo($from_user, $access_token); // 好友信息
			}		
		}
		$fans = pdo_fetch("SELECT * FROM ".tablename('stonefish_redenvelope_fans')." WHERE rid = '".$rid."' and id='".$uid."' and uniacid = '".$_W['uniacid']."'");
		if($from_user==$fans['from_user']){
			header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $this->createMobileUrl('index', array('rid' => $rid)) . "");
            exit();
		}
		$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
		if(empty($acid)){
			$acid = pdo_fetchcolumn("select share_acid from " . tablename('stonefish_redenvelope_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));			
		}
		$share = pdo_fetch("select * from " . tablename('stonefish_redenvelope_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
		$firendExists = false;
		$firendDbUser = pdo_fetchcolumn("SELECT * FROM ".tablename('stonefish_redenvelope_fans')." WHERE rid = '".$rid."' and from_user='".$from_user."' and uniacid = '".$_W['uniacid']."'");;
        if (! empty($firendDbUser)) {
            $firendExists = true; //该用户已注册过
        }
		$firendHelpCount = 0;
        $limitType = $reply['limitType'];//限制类型0为只能一次1为每天一次
        $totallimit = $reply['totallimit'];//好友助力总次数制
        
        if ($limitType == 1){ // 每天限制            
            $firendHelpCount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('stonefish_redenvelope_data') . " WHERE rid = :rid and from_user=:from_user and fromuser=:fromuser and TO_DAYS( DATE_FORMAT( FROM_UNIXTIME(  `visitorstime` ) ,  '%Y-%m-%d' ) ) = TO_DAYS( NOW( ) ) ", array(':rid' => $rid,":from_user" => $from_user,":fromuser" => $fans['from_user']));
        }elseif($limitType == 0){ //只能一次                
                $firendHelpCount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('stonefish_redenvelope_data') . " WHERE rid = :rid and from_user=:from_user and fromuser=:fromuser", array(':rid' => $rid,":from_user" => $from_user,":fromuser" => $fans['from_user']));
        }
        $allow = true;
        if ($limitType == 0 && $firendHelpCount) { // 限制一次
            $allow = false;
            $msg = "亲你的翻牌次数已用完!";
        }
        if ($limitType == 1 && $firendHelpCount>=$totallimit) {//限制最多多少次
            $allow = false;
            $msg = "亲你的翻牌次数已用完!";
        }
		if ($limitType == 1 && $firendHelpCount && $firendHelpCount<$totallimit) {//限制最多多少次
            $allow = false;
            $msg = "今天翻牌翻牌次数已用完，明天再来吧!";
        }
		//分享信息
        $sharelink = $_W['siteroot'] .'app/'.$this->createMobileUrl('share', array('rid' => $rid,'uid' => $uid));
        $sharetitle = empty($share['share_title']) ? '欢迎参加全民抢红包活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加全民抢红包活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($uniacid,$rid,$fans['from_user'],$sharetitle);
		$sharedesc = $this->get_share($uniacid,$rid,$fans['from_user'],$sharedesc);
		if(!empty($share['share_imgurl'])){
		    $shareimg = toimage($share['share_imgurl']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
		include $this->template("firend");
	}
	/**
     * 根据 from_user rid查找用户
     *
     * @param unknown $from_user            
     * @param unknown $rid查找用户            
     */
    public function findUserByOpenid($from_user, $rid){
        $user = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $rid,':from_user' => $from_user));
        return $user;
    }
	/**
     * 好友助力
     */
    public function doMobileFirendFanpai(){
        global $_W, $_GPC;
        $uid = $_GPC['uid'];
        $rid = $_GPC['rid'];
        $fid = $_GPC['fopenid'];
        $fnickname = $_GPC['fnickname'];
        $fheadUrl = $_GPC['fheadUrl'];
        
        $reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        $fans = pdo_fetch("SELECT * FROM ".tablename('stonefish_redenvelope_fans')." WHERE rid = '".$rid."' and id='".$uid."' and uniacid = '".$_W['uniacid']."'");
        $res = array();
        if(empty($reply)) {            
            $res["msg"] = "红包不存在";
            $res["code"] = 501;
        }else{
            $firendHelpCount = 0;
            $limitType = $reply['limitType'];
            $totallimit = $reply['totallimit'];			
            if ($limitType == 1) { // 每天限制                
                $firendHelpCount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('stonefish_redenvelope_data') . " WHERE rid=:rid and fromuser=:fromuser and from_user=:from_user and TO_DAYS( DATE_FORMAT( FROM_UNIXTIME(  `createtime` ) ,  '%Y-%m-%d' ) ) = TO_DAYS( NOW( ) ) ", array(':rid' => $rid,":fromuser" => $fans['from_user'],":from_user" => $fid));
            } elseif ($limitType == 0) { // 只能一次                    
                $firendHelpCount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('stonefish_redenvelope_data') . " WHERE rid=:rid and fromuser=:fromuser and from_user=:from_user", array(':rid' => $rid,":fromuser" => $fans['from_user'],":from_user" => $fid));
            }			
            
            if ($limitType == 0 && $firendHelpCount) { // 限制一次
                $res["msg"] = "亲你的翻牌次数已用完!";
                $res["code"] = 502;
            }
            if ($limitType == 1 && $firendHelpCount>=$totallimit) {//限制最多多少次
                $res["msg"] = "今天翻牌翻牌次数已用完，明天再来吧!";
                $res["code"] = 503;
            }			
            if ($res["code"] == '') {
                    $incomelimit = $reply['incomelimit'];
                    $inpoint = $fans['inpoint'];
					$randompointstart = $reply['randompointstart'];
					$randompointend = $reply['randompointend'];
                    $score = 0;
                    $addp = $reply['addp'];
                    $left = $totallimit-$firendHelpCount;
                    $op = $this->get_rand(array(
                        "+" => $addp,
                        "-" => (100 - $addp)
                    ));
					$score = mt_rand($randompointstart*100,$randompointend*100)/100;
					$score = sprintf("%.2f",$score);
                    if ($op == "+") {
                        $inpoint = $inpoint + $score;
                    } elseif ($op == "-") {
                        $inpoint = $inpoint - $score;
                    }
                    if ($inpoint >= $incomelimit) {
                        $inpoint = $incomelimit;
                    }
                    $lowpoint = pdo_fetchcolumn("SELECT point FROM " . tablename('stonefish_redenvelope_prize') . " WHERE rid = :rid ORDER BY point ASC ", array(':rid' => $rid)); // 最低分数
                    $status=0;
                    if($inpoint>=$lowpoint){//已中奖
                        $status=1;                        
                    }
                    $fincome=0;                   
                    if ($op == "+") {
                    	$fincome=$score;
                    }                    
                    if ($op == "-") {
                    	$fincome=-$score;
                    }
                    $data = array(
                        'rid' => $rid,
						'uniacid' => $_W['uniacid'],
                        'from_user' => $fid,
                        'nickname' => $fnickname,
                        'avatar' => $fheadUrl,
                        'fromuser' => $fans['from_user'],
                        'point'=> $fincome,
						'visitorsip'=> CLIENT_IP,
                        'visitorstime' => TIMESTAMP,
						'viewnum' => 1
                    );
                    pdo_insert('stonefish_redenvelope_data', $data); // 记录助力人
                    $updatedata = array(
                        'sharenum' => $fans['sharenum'] + 1,
                        'inpoint' => $inpoint,
                        'status'=>$status
                    );
                    // 更新user 表数据
                    pdo_update('stonefish_redenvelope_fans', $updatedata, array('id' => $uid));                    
                    $res["code"] = 200;
                    if ($op == "+") {
                        $res["result"] = "+" . $score;
                    }
                    if ($op == "-") {
                        $res["result"] = "-" . $score;
                    }
					if($limitType == 0){
						$res['left'] = 0;
					}else{
						$res['left'] = $left - 1;
					}                    
            }
        }
        echo json_encode($res);
    }
	/**
     * 概率计算
     *
     * @param unknown $proArr            
     * @return Ambigous <string, unknown>
     */
    function get_rand($proArr){
        $result = '';
        // 概率数组的总概率精度
        $proSum = array_sum($proArr);
        // 概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum); // 抽取随机数
            if ($randNum <= $proCur) {
                $result = $key; // 得出结果
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset($proArr);
        return $result;
    }
	function get_share($uniacid,$rid,$fromuser,$title) {
		if (!empty($rid)) {
			//虚拟人数据配置
		    $now = time();
			$reply = pdo_fetch("SELECT xuninum_time,xuninumtime,xuninum,xuninuminitial,xuninumending FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		    if($now-$reply['xuninum_time']>$reply['xuninumtime']){
		        pdo_update('stonefish_redenvelope_reply', array('xuninum_time' => $now,'xuninum' => $reply['xuninum']+mt_rand($reply['xuninuminitial'],$reply['xuninumending'])), array('rid' => $rid));
		    }
		    //虚拟人数据配置
			$total = pdo_fetchcolumn("SELECT xuninum+fansnum as total FROM ".tablename('stonefish_redenvelope_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
		if (!empty($fromuser)) {
			$realname = pdo_fetchcolumn("SELECT realname FROM ".tablename('stonefish_redenvelope_fans')." WHERE uniacid= :uniacid AND rid= :rid AND from_user= :fromuser", array(':uniacid' => $uniacid,':rid' => $rid,':fromuser' => $fromuser));
		}
		$gifttitle = pdo_fetchcolumn("SELECT description FROM " . tablename('stonefish_redenvelope_award') . " WHERE uniacid='" . $uniacid . "' and rid = '" . $rid . "' and from_user='" . $fromuser . "' and status>0 and prize>0 order by prize asc");
		$str = array('#参与人数#'=>$total,'#参与人#'=>$realname,'#奖品#'=>$gifttitle);
		$result = strtr($title,$str);
        return $result;
    }
    public function doMobileindex() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$fansID = $_W['member']['uid'];
        $from_user = $_W['fans']['from_user'];
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
		$running = true;
	    $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }		
        $reply = pdo_fetch("select * from " . tablename('stonefish_redenvelope_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));		
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
		//兑奖参数重命名
		$isfansname = explode(',',$reply['isfansname']);
		//兑奖参数重命名
		if(empty($acid)){
			$acid = pdo_fetchcolumn("select share_acid from " . tablename('stonefish_redenvelope_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));			
		}
		$share = pdo_fetch("select * from " . tablename('stonefish_redenvelope_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
		//首页广告显示控制
		if($reply['homepictime']>0){
			if ($_COOKIE["stonefish_redenvelope_homepictime"]<=time()){
			    setcookie("stonefish_redenvelope_homepictime", mktime(23,59,59,date('d'),date('m'),date('Y')), mktime(23,59,59,date('d'),date('m'),date('Y')));
				include $this->template('homepictime');
				exit;
			}
		}
        //首页广告显示控制
        //获得关键词
        $keyword = pdo_fetch("select content from ".tablename('rule_keyword')." where rid=:rid and type=1",array(":rid"=>$rid));
        $reply['keyword']=  $keyword['content'];
        //获得关键词
        if (empty($from_user)) {
            //301跳转
            if (!empty($share['share_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $share['share_url'] . "");
                exit();
            }
            //message('抱歉，参数错误！','', 'error');
            $isshare = 1;
            $running = false;
            $msg = '请先关注公共号。';
        } else {
		    //查询是否为关注用户
			load()->classs('weixin.account');
		    $accObj= WeixinAccount::create($_W['acid']);
		    $access_token = $accObj->fetch_token();
			load()->func('communication');
			$oauth2_code = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
			$content = ihttp_get($oauth2_code);
			$token = @json_decode($content['content'], true);
			$follow = $token['subscribe'];
			if($follow==0){
			    if (!empty($share['share_url'])) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . $share['share_url'] . "");
                    exit();
                }
                $isshare = 1;
                $running = false;
                $msg = '请先关注公共号。';
			}
            //获得用户资料
		    $profile = mc_fetch($fansID, array('avatar','nickname','realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position'));
			if(empty($profile['avatar'])){
				$profile['avatar'] = $token['headimgurl'];
				mc_update($fansID, array('avatar' => $token['headimgurl']));
			}
			if(empty($profile['nickname'])){
				$profile['nickname'] = $token['nickname'];
				mc_update($fansID, array('nickname' => $token['nickname']));
			}
			if(empty($profile['avatar'])){
				$profile['avatar'] = '../addons/stonefish_redenvelope/template/images/avatar.jpg';
			}
			if(empty($profile['nickname'])){
				$profile['nickname'] = '匿名';
			}
			$fans = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = '" . $rid . "' and fansID='" . $fansID . "' and from_user='" . $from_user . "'");
			if(empty($fans)){
                $running = false;
                $msg = '还没有领取过红包';
				$isfansh = 180;
				$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
				foreach ($ziduan as $ziduans) {
					if($reply['is'.$ziduans]){
						$$ziduans = true;
					    $isfansh += 38;
			        }
				}
				if($realname || $mobile || $qq || $email || $address || $gender || $telephone || $idcard || $company || $occupation || $position){
			       $isfans = true;
				   $isfansh += 50;
			    }else{
				   $isfansh = 180;
				}
			}
			//增加浏览次数
            pdo_update('stonefish_redenvelope_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
			//查询是活动定义的次数还是商户赠送次数
		    if($reply['opportunity']==1){
               //商家赠送机会
			   if(empty($profile['mobile'])){
				    message('还没有注册成为会员，无法进入刮奖', url('entry//member',array('m'=>'stonefish_member','url'=>url('entry//index',array('m'=>'stonefish_redenvelope','rid'=>$rid)))), 'error');
                    exit();
				}
		        $doings = pdo_fetch("SELECT awardcount,districtid,status FROM " . tablename('stonefish_branch_doings') . " WHERE rid = " . $rid . " and mobile='" . $profile['mobile'] . "' and uniacid='".$uniacid."'");
				if(!empty($doings)){
					if ($doings['status']<2) {
                        $running = false;
					    $msg = '抱歉，您的领取红包资格正在审核中';
                    }else{
					    if($doings['awardcount'] == 0){
							$running = false;
					        $msg = '抱歉，您的领取红包资格正在加急审核中';
						}						
				    }				
				    //查询网点资料
				    $business = pdo_fetch("SELECT * FROM " . tablename('stonefish_branch_business') . " WHERE id=" . $doings['districtid'] . "");
				    //更新网点记录到会员中心表
				    pdo_update('mc_members', array('districtid' => $doings['districtid']), array('uid' => $fansID));
				}else{
					$running = false;
					$msg = '抱歉，您的还未获得领取红包资格';
				}
		    }elseif($reply['opportunity']==2){
			    $creditnames = array();
		        $unisettings = uni_setting($uniacid, array('creditnames'));
		        foreach ($unisettings['creditnames'] as $key=>$credit) {
		        	if ($reply['credit_type']==$key) {
			        	$creditnames = $credit['title'];
				    	break;
			        }
		        }
				//积分购买机会
			    $credit = mc_credit_fetch($fansID, array($reply['credit_type']));
				$credit_times = intval($credit[$reply['credit_type']]/$reply['credit_times']);
				if($credit_times==0){
				    $running = false;
					$msg = '抱歉，您的'.$creditnames.'不足以购买领取红包资格';
				}
			}            
        }		
		//查询红包总数以及可中奖总数 是否还有奖品
		if($reply['countlimit']!=0 && $reply['countlimit']<=$reply['fansnum']){
		    //已没有奖品可发放了
			$running = false;
            $msg = '所有红包都发放完了，下次早点来哟!';
		}
		$prizenum = pdo_fetchcolumn("SELECT count(id) FROM ".tablename('stonefish_redenvelope_award')." WHERE uniacid='".$uniacid."' AND rid= '".$rid."' and status>0");
		if($prizenum>=$reply['total_num']){
		    //已没有奖品可发放了
			$running = false;
            $msg = '所有红包都发放完了，下次早点来哟!';
		}
		//好友助力
		$firendlist = pdo_fetchall("SELECT sum(point) as tpoint,nickname,avatar  FROM ".tablename('stonefish_redenvelope_data')." where fromuser=:fromuser group by fromuser order by visitorstime  desc limit 0,10", array(":fromuser" => $from_user));
		//好友助力
        //分享信息
        $sharelink = $_W['siteroot'] .'app/'.$this->createMobileUrl('share', array('rid' => $rid,'uid' => $fans['id']));
        $sharetitle = empty($share['share_title']) ? '欢迎参加全民抢红包活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加全民抢红包活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($uniacid,$rid,$from_user,$sharetitle);
		$sharedesc = $this->get_share($uniacid,$rid,$from_user,$sharedesc);
		if(!empty($share['share_imgurl'])){
		    $shareimg = toimage($share['share_imgurl']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
        include $this->template('index');
    }
	
	public function doMobileSort() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$uid = $_GPC['uid'];		
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
		$acid = $_W['acid'];
		if(empty($acid)){
			$acid = pdo_fetchcolumn("SELECT share_acid FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));			
		}
		$share = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_share') . " WHERE rid = :rid AND acid = :acid", array(':rid' => $rid,':acid' => $acid));
		//获得关键词
        $keyword = pdo_fetch("select content from ".tablename('rule_keyword')." where rid=:rid and type=1",array(":rid"=>$rid));
        $reply['keyword']=  $keyword['content'];
        //获得关键词
		$fans = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = '".$rid."' and id='".$uid."'");
		$sortcount=$reply['awardnum'];
        $list = pdo_fetchall("SELECT @rownum:=@rownum+1 AS rowno ,u.*  FROM (SELECT @rownum:=0 ) r, " . tablename('stonefish_redenvelope_fans') . " u WHERE u.rid=:rid ORDER BY inpoint DESC limit 0,".$sortcount, array(":rid" => $rid));
		$total = pdo_fetchcolumn('SELECT COUNT(id) FROM '.tablename('stonefish_redenvelope_fans')." WHERE inpoint>:inco and rid=:rid and id!=:uid", array(":inco" => $fans['inpoint'],":rid"=>$rid,":uid"=>$uid));
     	$usersort=$total+1;
		//分享信息
        $sharelink = $_W['siteroot'] .'app/'.$this->createMobileUrl('share', array('rid' => $rid,'uid' => $uid));
        $sharetitle = empty($share['share_title']) ? '欢迎参加全民抢红包活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加全民抢红包活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($uniacid,$rid,$fans['from_user'],$sharetitle);
		$sharedesc = $this->get_share($uniacid,$rid,$fans['from_user'],$sharedesc);
		if(!empty($share['share_imgurl'])){
		    $shareimg = toimage($share['share_imgurl']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
        include $this->template('sort');
    }
	
	public function doMobileFirendSort() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$uid = $_GPC['uid'];
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
		$acid = $_W['acid'];
		if(empty($acid)){
			$acid = pdo_fetchcolumn("SELECT share_acid FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));			
		}
		$share = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_share') . " WHERE rid = :rid AND acid = :acid", array(':rid' => $rid,':acid' => $acid));
        $reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
		//获得关键词
        $keyword = pdo_fetch("select content from ".tablename('rule_keyword')." where rid=:rid and type=1",array(":rid"=>$rid));
        $reply['keyword']=  $keyword['content'];
        //获得关键词
		$fans = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = '" . $rid . "' and id='" . $uid . "'");

        $list = pdo_fetchall("SELECT sum(point) as tincome,nickname,avatar FROM " . tablename('stonefish_redenvelope_data') . " where fromuser=:fromuser and rid=:rid group by from_user order by point desc limit 0,10", array(":fromuser" => $fans['from_user'],":rid"=>$rid));
		//分享信息
        $sharelink = $_W['siteroot'] .'app/'.$this->createMobileUrl('share', array('rid' => $rid,'uid' => $uid));
        $sharetitle = empty($share['share_title']) ? '欢迎参加全民抢红包活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加全民抢红包活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($uniacid,$rid,$fans['from_user'],$sharetitle);
		$sharedesc = $this->get_share($uniacid,$rid,$fans['from_user'],$sharedesc);
		if(!empty($share['share_imgurl'])){
		    $shareimg = toimage($share['share_imgurl']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
        include $this->template('firendsort');
    }
	/**
     * 查看好友的钱包
     */
    public function doMobileUserRedEnvelope()
    {
        global $_W, $_GPC;
        $uid = $_GPC['uid'];
        $rid = $_GPC['rid'];
        $reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
        $fans = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = '" . $rid . "' and id='" . $uid . "'");
        $acid = $_W['acid'];
		if(empty($acid)){
			$acid = pdo_fetchcolumn("SELECT share_acid FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));			
		}
		$share = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_share') . " WHERE rid = :rid AND acid = :acid", array(':rid' => $rid,':acid' => $acid));
		//分享信息
        $sharelink = $_W['siteroot'] .'app/'.$this->createMobileUrl('share', array('rid' => $rid,'uid' => $uid));
        $sharetitle = empty($share['share_title']) ? '欢迎参加全民抢红包活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加全民抢红包活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($uniacid,$rid,$fans['from_user'],$sharetitle);
		$sharedesc = $this->get_share($uniacid,$rid,$fans['from_user'],$sharedesc);
		if(!empty($share['share_imgurl'])){
		    $shareimg = toimage($share['share_imgurl']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
        include $this->template("userredenvelope");
    }
	
	public function doMobileRule() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$from_user = $_W['fans']['from_user'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
		$acid = $_W['acid'];
		if(empty($acid)){
			$acid = pdo_fetchcolumn("SELECT share_acid FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));			
		}
		$share = pdo_fetch("SELECT share_txt,share_url FROM " . tablename('stonefish_redenvelope_share') . " WHERE rid = :rid AND acid = :acid", array(':rid' => $rid,':acid' => $acid));
        $reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
		if(!empty($from_user)){
			$fans = pdo_fetch("SELECT id,inpoint,outpoint FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = '" . $rid . "' and from_user='" . $from_user . "'");
		}
		//查询是否为关注用户
		$follow = pdo_fetchcolumn("SELECT follow FROM ".tablename('mc_mapping_fans') ." Where openid=:openid and uniacid=:uniacid ORDER BY `fanid` DESC",array(":openid"=>$from_user,":uniacid"=>$uniacid));
		//查询奖品设置
		if(empty($reply['envelope'])){
			$prize = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redenvelope_prize') . " WHERE rid = :rid ORDER BY `point` asc", array(':rid' => $rid));			
		}
		//查询是否兑换过奖品
		$prizenum = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid=:rid and from_user=:from_user", array(':rid' => $rid,':from_user' => $from_user));	
        include $this->template('rule');
    }
	public function doMobileExchange() {
        global $_GPC, $_W;
        $pid = intval($_GPC['pid']);
		$rid = intval($_GPC['rid']);
		$uid = intval($_GPC['uid']);
		if(!empty($uid)){
			$fans = pdo_fetch("SELECT id,from_user,inpoint,outpoint FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = '" . $rid . "' and id='" . $uid . "'");
		}else{
			
		}
		if(!empty($uid)){
			$prize = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_prize') . " WHERE id='" . $pid . "'");
		}else{
			
		}
		$awardinsert = array(
            'rid' => $rid,
			'uniacid' => $_W['uniacid'],
            'from_user' => $fans['from_user'],
			'name' => $prize['prizetype'],
			'description' => $prize['prizename'],
			'prize' => $pid,
			'prizetype' => '兑换',
			'createtime' => time(),
			'xuni' => $fans['xuni'],
			'status' =>1
        );
        pdo_insert('stonefish_redenvelope_award', $awardinsert);
        $p['id'] = pdo_insertid();
		pdo_update('stonefish_redenvelope_fans', array('zhongjiang' => 1,'outpoint' => $fans['outpoint']+$prize['point']), array('id' => $uid));
		//增加中奖数量
		pdo_update('stonefish_redenvelope_prize', array('prizedraw' => $prize['prizedraw']+1), array('id' => $pid));

		$appUrl=$this->createMobileUrl('exchangelist', array('rid' => $rid,"uid" => $uid),true);
		$appUrl=substr($appUrl,2);
		$url = $_W['siteroot'] ."app/".$appUrl;
        header("location: $url");
	}
	
	public function doMobileExchangelist() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$uid = intval($_GPC['uid']);
		$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		if(!empty($uid)){
			$fans = pdo_fetch("SELECT id,from_user,inpoint,outpoint FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = '" . $rid . "' and id='" . $uid . "'");
		}else{
			
		}
		if(!empty($uid)){
			$award = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = '" . $rid . "' and from_user='" . $fans['from_user'] . "'");
		}else{
			
		}
		foreach ($award as &$awards) {
			$awards['prizes'] = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_prize') . " WHERE id = '".$awards['prize']."'");
		}
        include $this->template('exchangelist');
	}

	public function doMobileget_award() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$fansID = $_W['member']['uid'];
        $from_user = $_W['fans']['from_user'];
		$uniacid = $_W['uniacid'];
		$profile = mc_fetch($fansID, array('avatar','nickname','realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position'));
		if(empty($profile['avatar'])){
				$profile['avatar'] = '../addons/stonefish_redenvelope/template/images/avatar.jpg';
		}
		if(empty($profile['nickname'])){
				$profile['nickname'] = $_GPC['realname'];
		}
        $reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        if ($reply == false) {
             $this->message(array("success"=>2, "msg"=>'规则出错！...'),"");
        }	
        if($reply['isshow'] != 1){
           //活动已经暂停,请稍后...
             $this->message(array("success"=>2, "msg"=>'活动暂停，请稍后...'),"");
        }
        if ($reply['starttime'] > time()) {
            $this->message(array("success"=>2, "msg"=>'活动还没有开始呢，请等待...'),"");
        }
        if ($reply['endtime'] < time()) {
            $this->message(array("success"=>2, "msg"=>'活动已经结束了，下次再来吧！'),"");
        }
        if (empty($_W['fans'])) {
            $this->message(array("success"=>2, "msg"=>'请先关注公共账号再来参与活动！详情请查看规则！'),"");
        }
        //先判断有没有资格领取
		//判断是否为关注用户
		$follow = pdo_fetchcolumn("SELECT follow FROM ".tablename('mc_mapping_fans') ." Where openid=:openid and uniacid=:uniacid ORDER BY `fanid` DESC",array(":openid"=>$from_user,":uniacid"=>$uniacid));
		if($follow==0){
			$this->message(array("success"=>2, "msg"=>'请先关注公共账号再来参与活动！详情请查看规则!'),"");
		}
		//判断是否为关注用户
		//查询是活动定义还是商户赠送
		if($reply['opportunity']==1){
			if(empty($profile['mobile'])){
				$this->message(array("success"=>2, "msg"=>'您没有注册成为会员，不能刮奖!'),"");
			}
			$doings = pdo_fetch("SELECT * FROM " . tablename('stonefish_branch_doings') . " WHERE rid = " . $rid . " and mobile='" . $profile['mobile'] . "' and uniacid='".$uniacid."'");
			if(!empty($doings)){
			    if ($doings['status']<2) {
                    $this->message(array("success"=>2, "msg"=>'抱歉，您的领取红包资格正在审核中!'),"");
                 }else{
				    if ($doings['awardcount'] == 0) {
				        $this->message(array("success"=>2, "msg"=>'抱歉，您的领取红包资格已用完了!'),"");
                    }
			    }
			}else{
				$this->message(array("success"=>2, "msg"=>'抱歉，您还没有获取领取红包资格，不能领取!'),"");
			}			
		}elseif($reply['opportunity']==2){
			load()->model('account');
		    $unisettings = uni_setting($uniacid, array('creditnames'));
		    foreach ($unisettings['creditnames'] as $key=>$credits) {
		    	if ($reply['credit_type']==$key) {
			    	$creditnames = $credits['title'];
					break;
			    }
		    }
		    $credit = mc_credit_fetch($fansID, array($reply['credit_type']));
			$credit_times = intval($credit[$reply['credit_type']]/$reply['credit_times']);
			if($credit_times<1){
			    $this->message(array("success"=>2, "msg"=>'抱歉，您没有'.$creditnames.'兑换红包了!'),"");
			}						
		}
        //查询是活动定义还是商户赠送		
        //是否已关联用户，如果中能中奖一次，判断是否已中奖
        $fans = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = " . $rid . " and fansID=" . $fansID . " and from_user='" . $from_user . "'");
        if ($fans == false) {
			$inpoint = mt_rand($reply['inpointstart']*100,$reply['inpointend']*100)/100;
			$inpoint = sprintf("%.2f",$inpoint);
            $fans = array(
                    'rid' => $rid,
					'uniacid' => $uniacid,
                    'fansID' => $fansID,
                    'from_user' => $from_user,
					'inpoint' => $inpoint,
					'avatar' => $profile['avatar'],
					'nickname' => $profile['nickname'],
                    'createtime' => time(),
            );
            pdo_insert('stonefish_redenvelope_fans', $fans);
            $fans['id'] = pdo_insertid();
			//自动读取会员信息存入FANS表中
			$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
			load()->model('mc');
			foreach ($ziduan as $ziduans){
				if($reply['is'.$ziduans]){
					if(!empty($_GPC[$ziduans])){
				        pdo_update('stonefish_redenvelope_fans', array($ziduans => $_GPC[$ziduans]), array('id' => $fans['id']));
				        if($reply['isfans']){				            
                            mc_update($fansID, array($ziduans => $_GPC[$ziduans]));
				        }
					}
			    }
		    }
		    //自动读取会员信息存入FANS表中
			//增加人数，和浏览次数
            pdo_update('stonefish_redenvelope_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
			//商家赠送增加使用次数
		    if($reply['opportunity']==1){
			    pdo_update('stonefish_branch_doings', array('usecount' =>0,'usetime' => time()), array('id' => $doings['id']));
		    }elseif($reply['opportunity']==2){
			    mc_credit_update($fansID, $reply['credit_type'], -$reply['credit_times'], array($fansID, '兑换幸运抢红包活动消耗：'.$reply['credit_times'].'个'.$creditnames));
			    $credit_now = $credit[$reply['credit_type']]-$reply['credit_times'];
		    }			
			//开始分配红包咯
            if($inpoint){
				//商家赠送添加使用记录
				if($reply['opportunity']==1){
			        $content = '领取红包价值:'.$inpoint;
				    $insert = array(
                		'uniacid' => $uniacid,
                		'rid' => $rid,
                		'module' => 'stonefish_redenvelope',
                		'mobile' => $doings['mobile'],
                		'content' =>$content,
						'prizeid' =>0,
						'createtime' => time()
            	    );
				    pdo_insert('stonefish_branch_doingslist', $insert);
				}
		        //商家赠送添加使用记录
				$data = array(
                    'msg' => $inpoint,
                    'success' => 1,					
					'credit_now' => $credit_now,
                );
            }
        }else{
			$this->message(array("success"=>2, "msg"=>'您领取过红包了！'),"");
		}		
		$this->message($data);
    }	

    public function message($_data = '', $_msg = '') {
        if (!empty($_data['succes']) && $_data['success'] != 2) {
            $this->setfans();
        }
        if (empty($_data)) {
            $_data = array(
                'name' => "谢谢参与",
                'success' => 0,
            );
        }
        if (!empty($_msg)) {
            //$_data['error']='invalid';
            $_data['msg'] = $_msg;
        }
        die(json_encode($_data));
    }

    public function setfans() {
        global $_GPC, $_W;
        //增加fans次数
        //记录用户信息
        $id = intval($_GPC['id']);
        $fansID = $_W['fans']['id'];
        if (empty($fansID) || empty($id))
            return;
        $fans = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = " . $id . " and fansID=" . $fansID . "");
        $nowtime = mktime(0, 0, 0);
        if ($fans['last_time'] < $nowtime) {
            $fans['todaynum'] = 0;
        }
        $update = array(
            'todaynum' => $fans['todaynum'] + 1,
            'totalnum' => $fans['totalnum'] + 1,
            'last_time' => time(),
        );
        pdo_update('stonefish_redenvelope_fans', $update, array('id' => $fans['id']));
    }
    
    public function doWebManage() {
        global $_GPC, $_W;
        //查询是否有商户网点权限
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array('stonefish_branch',$modules_arr)){
		    $stonefish_branch = true;
		}
		//查询是否有商户网点权限
        load()->model('reply');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'stonefish_redenvelope';

        if (!empty($_GPC['keyword'])) {
            $sql .= ' AND `name` LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keyword'] = reply_keywords_search($condition);
                $bigwheel = pdo_fetch("SELECT fansnum, viewnum,starttime,endtime,isshow,envelope FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['envelope'] = $bigwheel['envelope'];
				$item['fansnum'] = $bigwheel['fansnum'];
                $item['viewnum'] = $bigwheel['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $bigwheel['starttime']);
                $endtime = $bigwheel['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($bigwheel['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-warning">未开始</span>';
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = '<span class="label label-default ">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($bigwheel['isshow'] == 1) {
                        $item['status'] = '<span class="label label-success">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label label-default ">已暂停</span>';
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $bigwheel['isshow'];
            }
        }
        include $this->template('manage');
    }
	public function doWebFanslist() {
        global $_GPC, $_W;
		$rid = $_GPC['rid'];
		//查询是否有商户网点权限
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array('stonefish_branch',$modules_arr)){
		    $stonefish_branch = true;
		}
		//查询是否有商户网点权限
		$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['realname'])) {     
            $where.=' and realname=:realname';
            $params[':realname'] = $_GPC['realname'];
        }
		if (!empty($_GPC['mobile'])) {     
            $where.=' and mobile=:mobile';
            $params[':mobile'] = $_GPC['mobile'];
        }
		//导出标题以及参数设置
		if($_GPC['status']==''){
		    $statustitle = '全部';
		}
		if($_GPC['status']==1){
		    $statustitle = '有资格';
			$where.=' and status=1';
		}
		if($_GPC['status']==2){
		     $statustitle = '未兑换';
			$where.=' and status=1 and zhongjiang=0';
		}
		if($_GPC['status']==3){
		     $statustitle = '已提交';
			$where.=' and status=1 and zhongjiang=1';
		}
		if($_GPC['status']==4){
		     $statustitle = '已兑换';
			 $where.=' and status=1 and zhongjiang=2';
		}
		if($_GPC['status']==5){
		     $statustitle = '虚拟奖';
			 $where.=' and status=1 and xuni=1';
		}
		if($_GPC['status']==6){
		     $statustitle = '没资格';
			 $where.=' and status=0';
		}
		//导出标题以及参数设置				
		$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = :rid and uniacid=:uniacid " . $where . " ORDER BY id DESC " . $limit, $params);
		//中奖情况
		foreach ($list as &$lists) {
			$lists['awardinfo'] = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_prize') . "  WHERE rid = :rid and point<=:point", array(':rid' => $rid,':point' => $lists['inpoint']-$lists['outpoint']));
			$lists['share_num'] = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_data') . "  WHERE rid = :rid and fromuser=:fromuser", array(':rid' => $rid,':fromuser' => $lists['from_user']));
		}
		//中奖情况
		//一些参数的显示
        $num = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num1 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE rid = :rid and uniacid=:uniacid and status=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num6 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE rid = :rid and uniacid=:uniacid and status=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num2 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE rid = :rid and uniacid=:uniacid and status=1 and zhongjiang=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num3 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE rid = :rid and uniacid=:uniacid and status=1 and zhongjiang=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num4 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE rid = :rid and uniacid=:uniacid and status=1 and zhongjiang=2", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$num5 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_fans') . "  WHERE rid = :rid and uniacid=:uniacid and status=1 and xuni=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//一些参数的显示
        include $this->template('fanslist');
    }
	public function doWebBranch() {
        global $_GPC, $_W;
		//查询是否有商户网点权限
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array('stonefish_branch',$modules_arr)){
		    $stonefish_branch = true;
		}
		//查询是否有商户网点权限
		$rid = $_GPC['rid'];
		//选择商家
		$district = pdo_fetchall("SELECT * FROM " . tablename('stonefish_branch_district') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY orderid desc, id DESC", array(), 'id');
		$items = pdo_fetchall("SELECT id,title,districtid FROM " . tablename('stonefish_branch_business') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC", array(), 'id');
        if (!empty($items)) {
            $business = '';
            foreach ($items as $cid => $cate) {
                $business[$cate['districtid']][$cate['id']] = array($cate['id'], $cate['title']);
            }
        }
		//选择商家
		$params = array(':module' => 'stonefish_redenvelope', ':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['mobile'])) {     
            $where.=' and mobile=:mobile';
            $params[':mobile'] = $_GPC['mobile'];
        }
		if (!empty($_GPC['districtid'])) {     
            $where.=' and districtid=:districtid';
            $params[':districtid'] = $_GPC['districtid'];
        }elseif(!empty($_GPC['pcate'])){
		    $districts = pdo_fetchall("SELECT id FROM " . tablename('stonefish_branch_business') . "  WHERE districtid=:districtid and  uniacid=:uniacid ORDER BY id DESC", array('districtid' =>$_GPC['pcate'],'uniacid' =>$_W['uniacid']), 'districtid');
			$districtid = '';
            foreach ($districts as $districtss) {
                $districtid .= $districtss['id'].',';
            }
			$districtid = substr($districtid,0,strlen($districtid)-1);
			$where.=' and districtid in(:districtid)';
            $params[':districtid'] = $districtid;
		}
		$total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_branch_doings') . "  WHERE module=:module and rid = :rid and uniacid=:uniacid ".$where."", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT * FROM " . tablename('stonefish_branch_doings') . " WHERE module=:module and rid = :rid and uniacid=:uniacid ".$where." ORDER BY id DESC " . $limit, $params);
		//查询商家
		foreach ($list as &$lists) {
			$lists['shangjia'] = pdo_fetchcolumn("SELECT title FROM " . tablename('stonefish_branch_business') . "  WHERE id = :id", array(':id' => $lists['districtid']));
		}
		//查询商家
        include $this->template('branch');
    }
	public function doWebSetcheck() {

        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];
        $data = intval($_GPC['data']);
       
         if (in_array($type, array('status'))) {
            $data = ($data==2?'1':'2');
            pdo_update("stonefish_branch_doings", array("status" => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
            die(json_encode(array("result" => 1, "data" => $data)));
        }        
        die(json_encode(array("result" => 0)));
        
    }
	public function doWebImporting() {
        global $_GPC, $_W;
		if($_W['isajax']) {
		    $rid = intval($_GPC['rid']);
		    //选择商家
		    $district = pdo_fetchall("SELECT * FROM " . tablename('stonefish_branch_district') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY orderid desc, id DESC", array(), 'id');
		    $items = pdo_fetchall("SELECT id,title,districtid FROM " . tablename('stonefish_branch_business') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC", array(), 'id');
            if (!empty($items)) {
                $business = '';
                foreach ($items as $cid => $cate) {
                    $business[$cate['districtid']][$cate['id']] = array($cate['id'], $cate['title']);
                }
            }
		    //选择商家
			include $this->template('importing');
			exit();
		}
       
    }
	public function doWebImportingsave() {
        global $_GPC, $_W;		
		$rid = intval($_GPC['rid']);
		$districtid = intval($_GPC['districtid']);
		if(!$rid){
		    message('系统出错', url('site/entry/branch',array('rid' => $rid, 'm' => 'stonefish_redenvelope')), 'error');
			exit;
		}
		    if(empty($_FILES["inputExcel"]["tmp_name"])){
			    message('系统出错', url('site/entry/branch',array('rid' => $rid, 'm' => 'stonefish_redenvelope')), 'error');
			    exit;
			}
			$inputFileName = '../addons/stonefish_redenvelope/template/moban/excel/'.$_FILES["inputExcel"]["name"];
			if (file_exists($inputFileName)){
                unlink($inputFileName);    //如果服务器上存在同名文件，则删除
			}
			move_uploaded_file($_FILES["inputExcel"]["tmp_name"],$inputFileName);
            require_once '../framework/library/phpexcel/PHPExcel.php';
            require_once '../framework/library/phpexcel/PHPExcel/IOFactory.php';
            require_once '../framework/library/phpexcel/PHPExcel/Reader/Excel5.php';			
			//设置php服务器可用内存，上传较大文件时可能会用到
			ini_set('memory_limit', '1024M');
			$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
			$objPHPExcel = $objReader->load($inputFileName); 
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow();           //取得总行数 
			$highestColumn = $sheet->getHighestColumn(); //取得总列数
			
			$objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow(); 

            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
            
            $headtitle=array(); 
            for ($row = 2;$row <= $highestRow;$row++){
                $strs=array();
                //注意highestColumnIndex的列数索引从0开始
                for ($col = 0;$col < $highestColumnIndex;$col++){
                $strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }
				//查询是否规定了区域商点
				if(!empty($districtid)){
				    $strs[2] = $districtid;
				}
				//查询是否规定了区域商点
                //插入数据
				$chongfu = pdo_fetch("SELECT id FROM ".tablename('stonefish_branch_doings')." WHERE mobile =:mobile and uniacid=:uniacid and districtid=:districtid", array(':mobile' => $strs[0],':uniacid' => $_W['uniacid'],':districtid' => $strs[2]));
				$data = array(
					'uniacid' => $_W['uniacid'],
					'rid' => $rid,
					'module' => 'stonefish_redenvelope',
					'mobile' => $strs[0],
					'awardcount' => $strs[1],
					'districtid' => $strs[2],
					'status' => 2,
					'createtime' => time()
				);
				if (!empty($chongfu)){
					pdo_update('stonefish_branch_doings', $data, array('id' => $chongfu['id']));
				}else{
				    pdo_insert('stonefish_branch_doings', $data);
				}				
            }
            unlink($inputFileName); //删除上传的excel文件
            message('导入刮奖次数成功', url('site/entry/branch',array('rid' => $rid, 'm' => 'stonefish_redenvelope')));
		    exit;    
    }
	public function doWebEditbranch() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				$data = pdo_fetch("SELECT * FROM " . tablename('stonefish_branch_doings') . ' WHERE id = :id AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
				include $this->template('editbranch');
				exit();
		}
       
    }
	public function doWebEditbranchsave() {
        global $_GPC, $_W;
		$uid = intval($_GPC['uid']);
		$rid = intval($_GPC['rid']);
		$usecount = intval($_GPC['usecount']);
		$awardcount = intval($_GPC['awardcount']);
		$status = intval($_GPC['status']);
		if($usecount>$awardcount){
		    message('修改后的次数少于已使用的次数', url('site/entry/branch',array('rid' => $rid, 'm' => 'stonefish_redenvelope')), 'error');
		}
		if(!$rid){
		    message('系统出错', url('site/entry/branch',array('rid' => $rid, 'm' => 'stonefish_redenvelope')), 'error');
		}
		if($uid) {
		    //刮奖次数
            pdo_update('stonefish_branch_doings', array('awardcount' => $awardcount,'status' => $status), array('id' => $uid));
			message('修改刮奖次数成功', url('site/entry/branch',array('rid' => $rid, 'm' => 'stonefish_redenvelope')));
		} else {
			message('未找到指定用户', url('site/entry/branch',array('rid' => $rid, 'm' => 'stonefish_redenvelope')), 'error');
		}       
    }
	public function doWebAddaward() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				//规则
				$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
				//粉丝数据
				$data = pdo_fetch("SELECT id, fansID, realname, mobile, uniacid  FROM " . tablename('stonefish_redenvelope_fans') . ' WHERE id = :id AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
				if($data['fansID']){
					load()->model('mc');
					$profile = mc_fetch($data['fansID'], array('realname','mobile'));
				}
				//奖品数据
				$awardlist = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redenvelope_prize') . ' WHERE rid = :rid AND uniacid = :uniacid ORDER BY id ASC', array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
				//判断是否还有奖品
				foreach ($awardlist as &$lists) {
			        if($lists['prizetotal']>$lists['prizedraw']){
					    $award = true;
						break; //直接跳出不再继续循环
					}
			    }
				include $this->template('xuniaward');
				exit();
		}
       
    }
	
	public function doWebAddawardsave() {
        global $_GPC, $_W;
		$uid = intval($_GPC['uid']);
		$rid = intval($_GPC['rid']);
		$point = intval($_GPC['point']);
		if(!$point){
		    message('金额必需填写', url('site/entry/fanslist',array('rid' => $rid, 'm' => 'stonefish_redenvelope')), 'error');
		}
		if(!$rid){
		    message('系统出错', url('site/entry/fanslist',array('rid' => $rid, 'm' => 'stonefish_redenvelope')), 'error');
		}
		if($uid) {
		    //规则
			$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			//粉丝数据
			$data = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . ' WHERE id = :id AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
			//添加中奖记录			
            $insert = array(
                    'uniacid' => $_W['uniacid'],
                    'rid' => $rid,
                    'from_user' => '系统虚拟者',
                    'fromuser' => $data['from_user'],
                    'avatar' => '../addons/stonefish_redenvelope/template/images/avatar.jpg',
                    'nickname' => '匿名',
					'visitorsip' => CLIENT_IP,
                    'point' => $point,
                    'visitorstime' => time()
            );
            pdo_insert('stonefish_redenvelope_data', $insert);
			//查询是否有资格兑换奖品
			$lowpoint = pdo_fetchcolumn("SELECT point FROM " . tablename('stonefish_redenvelope_prize') . " WHERE rid = :rid ORDER BY point ASC ", array(':rid' => $rid)); // 最低分数
            $status=0;
            if($data['inpoint'] + $point>=$lowpoint){//已中奖
                $status=1;                        
            }
            //保存中奖人信息到fans中
            pdo_update('stonefish_redenvelope_fans', array('inpoint' => $data['inpoint'] + $point,'xuni' => 1,'status' => $status), array('id' => $data['id']));
			
			message('添加虚拟金额成功', url('site/entry/fanslist',array('rid' => $rid, 'm' => 'stonefish_redenvelope')));
		} else {
			message('未找到指定用户', url('site/entry/fanslist',array('rid' => $rid, 'm' => 'stonefish_redenvelope')), 'error');
		}       
    }
	
	public function doWebAwardfrom() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				//粉丝数据
				$data = pdo_fetch("SELECT id, fansID, realname, mobile, uniacid, inpoint, outpoint  FROM " . tablename('stonefish_redenvelope_fans') . ' WHERE id = :id AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
				if($data['fansID']){
					load()->model('mc');
					$profile = mc_fetch($data['fansID'], array('realname','mobile'));
				}
				$list = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redenvelope_prize') . "  WHERE rid = :rid and uniacid=:uniacid ORDER BY point", array(':uniacid' => $_W['uniacid'], ':rid' => $rid));
				include $this->template('awardfrom');
				exit();
		}
    }
	public function doWebUserinfo() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				$fansID = intval($_GPC['fansID']);
				//兑奖资料
				$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
				$isfansname = explode(',',$reply['isfansname']);
				//粉丝数据
				if($fansID){
					$data = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . ' WHERE fansID = :fansID AND uniacid = :uniacid AND rid = :rid', array(':uniacid' => $_W['uniacid'], ':fansID' => $fansID, ':rid' => $rid));
				}else{
					$data = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_fans') . ' WHERE id = :id AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
				}
				
				include $this->template('userinfo');
				exit();
		}
    }
	public function doWebSharelist() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				//粉丝数据
				$data = pdo_fetch("SELECT id, fansID, realname, mobile, from_user  FROM " . tablename('stonefish_redenvelope_fans') . ' WHERE id = :id AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
				if($data['fansID']){
					load()->model('mc');
					$profile = mc_fetch($data['fansID'], array('realname','mobile'));
				}
				$share = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redenvelope_data') . "  WHERE rid = :rid and uniacid=:uniacid and fromuser=:fromuser ORDER BY id DESC ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':fromuser' => $data['from_user']));
				include $this->template('sharelist');
				exit();
		}
    }
	public function doWebUseinfo() {
        global $_GPC, $_W;
		if($_W['isajax']) {
				$uid = intval($_GPC['uid']);
				$rid = intval($_GPC['rid']);
				//粉丝数据
				$data = pdo_fetch("SELECT id, districtid, mobile, awardcount, usecount  FROM " . tablename('stonefish_branch_doings') . ' WHERE id = :id AND uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
				//商家信息
				$data['shangjiang'] = pdo_fetchcolumn("SELECT title FROM " . tablename('stonefish_branch_business') . "  WHERE id = :id", array(':id' => $data['districtid']));
				$list = pdo_fetchall("SELECT * FROM " . tablename('stonefish_branch_doingslist') . "  WHERE rid = :rid and uniacid=:uniacid and mobile=:mobile ORDER BY id DESC ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':mobile' => $data['mobile']));
				include $this->template('useinfo');
				exit();
		}
       
    }
	
    public function doWebDelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }
        message('活动删除成功！', referer(), 'success');
    }

    public function doWebDeleteAll() {
        global $_GPC, $_W;
        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
            if (empty($rule)) {
                $this->webmessage('抱歉，要修改的规则不存在或是已经被删除！');
            }
            if (pdo_delete('rule', array('id' => $rid))) {
                pdo_delete('rule_keyword', array('rid' => $rid));
                //删除统计相关数据
                pdo_delete('stat_rule', array('rid' => $rid));
                pdo_delete('stat_keyword', array('rid' => $rid));
                //调用模块中的删除
                $module = WeUtility::createModule($rule['module']);
                if (method_exists($module, 'ruleDeleted')) {
                    $module->ruleDeleted($rid);
                }
            }
        }
        $this->webmessage('选择中的活动删除成功！', '', 0);
    }
	public function doWebDeletefans() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("SELECT id, fansnum FROM ".tablename('stonefish_redenvelope_reply')." WHERE rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($reply)) {
            $this->webmessage('抱歉，要修改的活动不存在或是已经被删除！');
        }		
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if ($id == 0)
                continue;
			$fans = pdo_fetch("SELECT fansID,from_user FROM ".tablename('stonefish_redenvelope_fans')." WHERE id = :id", array(':id' => $id));
            if (empty($fans)) {
                $this->webmessage('抱歉，选中的粉丝数据不存在！');
            }
            //删除粉丝中奖记录
			$fansaward = pdo_fetchall("SELECT id,prizetype FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and uniacid=:uniacid and fansID=:fansID", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':fansID' => $fans['fansID']));
			foreach ($fansaward as $fansawards) {
				$prize = pdo_fetch("SELECT id,prizedraw FROM " . tablename('stonefish_redenvelope_prize') . " WHERE id = :id", array(':id' => $fansawards['prizetype']));
				pdo_update('stonefish_redenvelope_prize', array('prizedraw' => $prize['prizedraw']-1), array('id' => $fansawards['prizetype']));
				//查询奖品是否为虚拟积分，如果是则扣除相应的积分
			
			    //查询奖品是否为虚拟积分，如果是则扣除相应的积分
				pdo_delete('stonefish_redenvelope_award', array('id' => $fansawards['id']));
			}
			//删除粉丝中奖记录
			//删除粉丝参与记录
			pdo_delete('stonefish_redenvelope_fans', array('id' => $id));
			//删除粉丝参与记录
			//删除粉丝助力记录
			pdo_delete('stonefish_redenvelope_data', array('fromuser' => $fans['from_user'],'rid' => $rid,'uniacid' => $_W['uniacid']));
			//删除粉丝助力记录
			//减少参与记录
			$reply['fansnum'] = $reply['fansnum']-1;
			pdo_update('stonefish_redenvelope_reply', array('fansnum' => $reply['fansnum']), array('id' => $reply['id']));
			//减少参与记录
        }
        $this->webmessage('粉丝记录删除成功！', '', 0);
    }
	
	public function doWebDeleteaward() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("SELECT id, fansnum FROM ".tablename('stonefish_redenvelope_reply')." WHERE rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($reply)) {
            $this->webmessage('抱歉，要修改的活动不存在或是已经被删除！');
        }		
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if ($id == 0)
                continue;			
            //删除奖品第一步先恢复到奖池中
			$award = pdo_fetch("SELECT id,prize,from_user FROM " . tablename('stonefish_redenvelope_award') . " WHERE id = :id", array(':id' => $id));
			$prize = pdo_fetch("SELECT id,prizedraw,point FROM " . tablename('stonefish_redenvelope_prize') . " WHERE id = :id", array(':id' => $award['prize']));
			pdo_update('stonefish_redenvelope_prize', array('prizedraw' => $prize['prizedraw']-1), array('id' => $award['prize']));			
			//删除奖品第一步先恢复到奖池中
			//查询粉丝是否还有中奖记录，没有则需要改变粉丝状态
			$fansaward = pdo_fetch("SELECT id FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and from_user = :from_user and uniacid=:uniacid and id!=:id", array(':rid' => $rid, ':from_user' => $award['from_user'], ':uniacid' => $_W['uniacid'], ':id' => $id));
			$fans = pdo_fetch("SELECT outpoint FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = :rid and from_user = :from_user and uniacid=:uniacid and id!=:id", array(':rid' => $rid, ':from_user' => $award['from_user'], ':uniacid' => $_W['uniacid'], ':id' => $id));
			if(empty($fansaward)){
				pdo_update('stonefish_redenvelope_fans', array('zhongjiang' => 0,'outpoint' => $fans['outpoint']-$prize['point']), array('from_user' => $award['from_user'],'uniacid' => $_W['uniacid'],'rid' => $rid));
			}else{
				$awardnum = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and uniacid=:uniacid and from_user=:from_user and id!=:id", array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user' => $award['from_user'],':id' => $id));
				pdo_update('stonefish_redenvelope_fans', array('zhongjiang' => 1,'outpoint' => $fans['outpoint']-$prize['point']), array('from_user' => $award['from_user'],'uniacid' => $_W['uniacid'],'rid' => $rid));
			}
			//查询粉丝是否还有中奖记录，没有则需要改变粉丝状态
			//查询奖品是否为虚拟积分，如果是则扣除相应的积分
			
			//查询奖品是否为虚拟积分，如果是则扣除相应的积分
			//删除粉丝中奖记录
			pdo_delete('stonefish_redenvelope_award', array('id' => $id));
			//删除粉丝中奖记录
        }
        $this->webmessage('粉丝中奖记录删除成功！', '', 0);
    }
	
	public function doWebDeletebranch() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("SELECT id, fansnum FROM ".tablename('stonefish_redenvelope_reply')." WHERE rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($reply)) {
            $this->webmessage('抱歉，要修改的活动不存在或是已经被删除！');
        }		
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if ($id == 0)
                continue;			
            //删除使用记录
			$doings = pdo_fetch("SELECT * FROM " . tablename('stonefish_branch_doings') . " WHERE id = :id", array(':id' => $id));
			$doingslist = pdo_fetchall("SELECT * FROM " . tablename('stonefish_branch_doingslist') . " WHERE rid = :rid and uniacid=:uniacid and module=:module and mobile=:mobile", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':module' => $doings['module'], ':mobile' => $doings['mobile']));
			foreach ($doingslist as $doingslists) {
				//删除中奖记录
				//删除奖品第一步先恢复到奖池中
				$award = pdo_fetch("SELECT id,prizetype,fansID FROM " . tablename('stonefish_redenvelope_award') . " WHERE id = :id", array(':id' => $doingslists['prizeid']));
				$prize = pdo_fetch("SELECT id,prizedraw FROM " . tablename('stonefish_redenvelope_prize') . " WHERE id = :id", array(':id' => $award['prizetype']));
				pdo_update('stonefish_redenvelope_prize', array('prizedraw' => $prize['prizedraw']-1), array('id' => $award['prizetype']));			
				//删除奖品第一步先恢复到奖池中
				//查询粉丝是否还有中奖记录，没有则需要改变粉丝状态
				$fansaward = pdo_fetch("SELECT id FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and fansID = :fansID and uniacid=:uniacid and id!=:id", array(':rid' => $rid, ':fansID' => $award['fansID'], ':uniacid' => $_W['uniacid'], ':id' => $doingslists['prizeid']));
				if(empty($fansaward)){
					pdo_update('stonefish_redenvelope_fans', array('zhongjiang' => 0), array('fansID' => $award['fansID'],'uniacid' => $_W['uniacid'],'rid' => $rid));
				}else{
					$awardnum = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and uniacid=:uniacid and fansID=:fansID and id!=:id", array(':rid' => $rid,':uniacid' => $_W['uniacid'],':fansID' => $award['fansID'],':id' => $doingslists['prizeid']));
					pdo_update('stonefish_redenvelope_fans', array('awardnum' => $awardnum,'zhongjiang' => 1), array('fansID' => $award['fansID'],'uniacid' => $_W['uniacid'],'rid' => $rid));
				}
				//查询粉丝是否还有中奖记录，没有则需要改变粉丝状态
				//查询奖品是否为虚拟积分，如果是则扣除相应的积分
			
				//查询奖品是否为虚拟积分，如果是则扣除相应的积分
				//删除粉丝中奖记录
			    pdo_delete('stonefish_redenvelope_award', array('id' => $doingslists['prizeid']));
			    //删除粉丝中奖记录
				//删除中奖记录
				pdo_delete('stonefish_branch_doingslist', array('id' => $doingslists['id']));
			}
			//删除使用记录
			//删除赠送记录
			pdo_delete('stonefish_branch_doings', array('id' => $id));
			//删除赠送记录
        }
        $this->webmessage('商家赠送记录删除成功！', '', 0);
    }

    public function doWebAwardlist() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		//查询是否有商户网点权限
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array('stonefish_branch',$modules_arr)){
		    $stonefish_branch = true;
		}
		//查询是否有商户网点权限
		$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		//所有奖品类别
		$award = pdo_fetchall("SELECT * FROM " . tablename('stonefish_redenvelope_prize') . " WHERE rid = :rid and uniacid=:uniacid ORDER BY `id` asc", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		foreach ($award as $k =>$awards) {
			$award[$k]['num'] = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and uniacid=:uniacid and prizetype='".$awards['id']."'", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		}
		//所有奖品类别
		//导出标题		
		if($_GPC['status']==0){
		    $statustitle = '被取消'.$_GPC['award'];
		}
		if($_GPC['status']==1){
		    $statustitle = '未兑换'.$_GPC['award'];
		}
		if($_GPC['status']==2){
		    $statustitle = '已兑换'.$_GPC['award'];
		}
		if($_GPC['status']==''){
		    $statustitle = '全部'.$_GPC['award'];
		}
		//导出标题        
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
        if (isset($_GPC['status'])) {
            $where.=' and a.status=:status';
            $params[':status'] = $_GPC['status'];
        }
		if (!empty($_GPC['award'])) {
            $where.=' and a.name=:name';
            $params[':name'] = $_GPC['award'];
        }
        if (!empty($_GPC['keywords'])) {
            if (strlen($_GPC['keywords']) == 11 && is_numeric($_GPC['keywords'])) {
            	$members = pdo_fetchall("SELECT uid FROM ".tablename('mc_members')." WHERE mobile = :mobile", array(':mobile' => $_GPC['keywords']), 'uid');
                    if(!empty($members)){
                         $fans = pdo_fetchall("SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uid in ('".implode("','", array_keys($members))."')", array(), 'openid');
                         if(!empty($fans)){
                             $where .= " AND a.from_user IN ('".implode("','", array_keys($fans))."')";
                         }
                    }
            } else {
            	$where.=' and (a.award_sn like :keywords)';
            	$params[':keywords'] = "%{$_GPC['keywords']}%";
            }
        }
        $total = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('stonefish_redenvelope_award') . " a WHERE a.rid = :rid and a.uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT a.* FROM " . tablename('stonefish_redenvelope_award') . " a WHERE a.rid = :rid and a.uniacid=:uniacid  " . $where . " ORDER BY a.id DESC " . $limit, $params);
		//中奖资料
		foreach ($list as &$lists) {
			$lists['realname']=pdo_fetchcolumn("SELECT realname FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = :rid and uniacid=:uniacid and from_user = :from_user", array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user' => $lists['from_user']));
			$lists['mobile']=pdo_fetchcolumn("SELECT mobile FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = :rid and uniacid=:uniacid and from_user = :from_user", array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user' => $lists['from_user']));
			$lists['fid']=pdo_fetchcolumn("SELECT id FROM " . tablename('stonefish_redenvelope_fans') . " WHERE rid = :rid and uniacid=:uniacid and from_user = :from_user", array(':rid' => $rid,':uniacid' => $_W['uniacid'],':from_user' => $lists['from_user']));
		}
		//中奖资料	
        //一些参数的显示
        $num1 = pdo_fetchcolumn("SELECT total_num FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid", array(':rid' => $rid));
        $num2 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and status=1", array(':rid' => $rid));
        $num3 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and status=2", array(':rid' => $rid));
		$num4 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('stonefish_redenvelope_award') . " WHERE rid = :rid and status=0", array(':rid' => $rid));
		//一些参数的显示
        include $this->template('awardlist');
    }

    public function doWebDownload() {
        require_once 'download.php';
    }

    public function doWebSetshow() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('stonefish_redenvelope_reply', array('isshow' => $isshow), array('rid' => $rid));
        message('状态设置成功！', referer(), 'success');
    }

    public function doWebSetstatus() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		$rid = intval($_GPC['rid']);
        $status = intval($_GPC['status']);
        //$reply = pdo_fetch("SELECT * FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $p = array('status' => $status);
        if ($status == 2) {
            $p['consumetime'] = TIMESTAMP;
        }
        if ($status == 1) {
            $p['consumetime'] = '';
			$p['status'] = 1;
        }
        $temp = pdo_update('stonefish_redenvelope_award', $p, array('id' => $id));
        if ($temp == false) {
            message('抱歉，刚才操作数据失败！', '', 'error');
        } else {
		    //修改用户状态
			$from_user = pdo_fetchcolumn("SELECT from_user FROM " . tablename('stonefish_redenvelope_award') . " WHERE id = :id ORDER BY `id` DESC", array(':id' => $id));
			pdo_update('stonefish_redenvelope_fans', array('zhongjiang' => $status+1), array('rid' => $rid,'from_user' => $from_user));
			message('状态设置成功！', $this->createWebUrl('awardlist',array('rid'=>$_GPC['rid'])), 'success');
        }
    }
	
	public function doWebDeleteprize() {
        global $_GPC, $_W;       
            $rid = $_GPC['rid'];
			$id = $_GPC['id'];
			if(empty($id)){
				 message('抱歉，没有找到你要删除的奖品！', '', 'error');
			}
			if(empty($rid)){
				 message('抱歉，活动不存在或已删除了！', '', 'error');
			}
			//查询活动以及奖品数量
			$prizenum = pdo_fetchcolumn("SELECT prizetotal FROM " . tablename('stonefish_redenvelope_prize') . " WHERE id=:id",array('id' => $id));
			$total_num = pdo_fetchcolumn("SELECT total_num FROM " . tablename('stonefish_redenvelope_reply') . " WHERE rid=:rid",array('rid' => $rid));
			pdo_update('stonefish_redenvelope_reply', array('total_num' => $total_num-$prizenum), array('rid' => $rid));
			pdo_delete('stonefish_redenvelope_prize', array('id' => $id));
        message('删除奖品成功！', referer(), 'success');
    }

    public function webmessage($error, $url = '', $errno = -1) {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }
}
