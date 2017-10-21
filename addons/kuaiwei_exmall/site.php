<?php
defined('IN_IA') or exit('Access Denied');
define('ROOT_PATH', str_replace('site.php', '', str_replace('\\', '/', __FILE__)));//获取site所在目录的路径
require_once "jssdk.php";


class Kuaiwei_exmallModuleSite extends WeModuleSite
{

    public $tablebase = 'kuaiwei_exmall_base';
    public $tablefans = 'kuaiwei_exmall_fans';
    public $tabledata = 'kuaiwei_exmall_data';
    public $tablegoods = 'kuaiwei_exmall_goods';
    public $tablead = 'kuaiwei_exmall_ad';
    public $tableaward = 'kuaiwei_exmall_award';

    public function get_sysset() {

        global $_W, $_GPC;
        $path = "/addons/kuaiwei_exmall";
        $filename = IA_ROOT . $path . "/data/sysset_" . $_W['uniacid'] . ".txt";
        if (is_file($filename)) {
            $content = file_get_contents($filename);
            if (empty($content)) {
                return false;
            }
            return json_decode(base64_decode($content), true);
        }
        return pdo_fetch("SELECT * FROM " . tablename('kuaiwei_exmall_sysset') . " WHERE uniacid = :uniacid limit 1", array(':uniacid' => $_W['uniacid']));
    }

    public function doWebSysset() {
        global $_W, $_GPC;
        $set = $this->get_sysset();
        if (checksubmit('submit')) {
            
            $appid = trim($_GPC['appid']);
            $appsecret = trim($_GPC['appsecret']);

            $data = array(
                'uniacid' => $_W['uniacid'],
                'appid' => $appid,
                'appsecret' => $appsecret,
                'appid_share' => $appid,
                'appsecret_share' => $appsecret,
            );
            if (!empty($set)) {
                pdo_update('kuaiwei_exmall_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('kuaiwei_exmall_sysset', $data);
            }
            $this->write_cache("sysset_" . $_W['uniacid'], $data);
            message('更新借用接口成功！', 'refresh');
        }

        include $this->template('sysset');
    }

    public function oath_openid($id, $code, $type)
    {
        global $_GPC, $_W;
        load()->func('communication');
        load()->model('account');
        $_W['account'] = account_fetch($_W['acid']);//获取指定子公号信息
        $appid = $_W['account']['key'];//公众号appid
        $appsecret = $_W['account']['secret'];//公众号appsecret

        if ($_W['account']['level'] != 4) {//公众号级别，普通订阅号1，普通服务号2，认证订阅号3，认证服务号4
            //不是认证服务号
            $set = $this->get_sysset();
            if (!empty($set['appid']) && !empty($set['appsecret'])) {
                $appid = $set['appid'];
                $appsecret = $set['appsecret'];
            } else {
                //如果没有借用，判断是否认证服务号
                message('请使用认证服务号进行活动，或借用其他认证服务号权限!');
            }
        }
        if (empty($appid) || empty($appsecret)) {
            message('请到管理后台设置完整的 AppID 和AppSecret !');
        }

        if (!isset($code)) {//判断变量是否已配置，变量未配置则执行
            // $this->get_code($id, $appid,$urltype);
            if (empty($type)) {

                $url = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&m=kuaiwei_exmall&do=exmall&id={$id}";//返回助力页面

            } else {
                $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('id' => $id, 'from_user' => $type));//进入分享页面
            }
            $oauth2_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";//路径
            header("location: $oauth2_url");//弹出授权页面，可通过openid拿到昵称、性别、所在地；获取code
            exit();

        }
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";//通过链接，获取access_token
        $content = ihttp_get($oauth2_code);//封装的 GET 请求方法
        $token = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            message('未获取到 openid , 请刷新重试!', refeer(), 'error');
        }
        return $token;
    }


    public function oath_UserInfo($id, $code, $type)
    {
        global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
        load()->func('communication');
        $token = $this->oath_openid($id, $code, $type);//获取access_token，openid
        $accessToken = $token['access_token'];
        $openid = $token['openid'];
        $tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $accessToken . "&openid=" . $openid . "&lang=zh_CN";//获取用户信息
        $content = ihttp_get($tokenUrl);//封装的 GET 请求方法
        $userInfo = @json_decode($content['content'], true);//解压
        $cookieid = '__cookie_kuaiwei_exmall_20160928_' . $uniacid;
        $cookie = array("nickname" => $userInfo['nickname'], 'avatar' => $userInfo['headimgurl'], 'openid' => $userInfo['openid']);//存入缓存
        setcookie($cookieid, base64_encode(json_encode($cookie)), time() + 3600 * 24 * 365);
        return $userInfo;
    }


    public function checkfans()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $user_id = $_W['openid'];

        if (empty($user_id)) {

            $nofollow = 1;

        } else {

            //查询是否为关注用户
            $follow = pdo_fetch("select * from " . tablename('mc_mapping_fans') . " where openid=:openid and uniacid=:uniacid order by `fanid` desc", array(":openid" => $user_id, ":uniacid" => $uniacid));
            if ($follow['follow'] != 1) {

                $nofollow = 1;

            }
			else
			{
				 $nofollow=0;
			}
        }

		return $nofollow;
    }



    public function doMobileShare()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];
        $pagefromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');//获取分享人的信息。
        load()->model('mc');

        $user_agent = $_SERVER['HTTP_USER_AGENT'];//检测浏览器使用的操作系统
        if (strpos($user_agent, 'MicroMessenger') === false) {//数查找字符串在另一字符串中第一次出现的位置
            message('本页面仅支持微信访问!非微信浏览器禁止浏览!', '', 'error');
        }

        //网页特殊授权开始

        $cookieid = '__cookie_kuaiwei_exmall_20160928_' . $uniacid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        $from_user = $_W['fans']['from_user'];//当前粉丝标识
        $avatar = $_W['fans']['tag']['avatar'];//头像
        $nickname = $_W['fans']['nickname'];//昵称

        $code = $_GPC['code'];//
        //$type = '';//
		$type = $_GPC['from_user'];
        if (empty($from_user)) {
                $userinfo = $this->oath_UserInfo($id, $code, $type);
                $from_user = $userinfo['openid'];
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
        }

        //网页特殊授权结束


        if ($from_user != $pagefromuser) {

            $sharedata = pdo_fetch("select id from " . tablename($this->tabledata) . " where rid = '" . $id . "' and from_user = '" . $from_user . "' and fromuser = '" . $pagefromuser . "' and uniacid = '" . $uniacid . "'  limit 1");

            $fans = pdo_fetch("SELECT sharenum,fansID FROM " . tablename($this->tablefans) . " WHERE rid = " . $id . " and from_user='" . $pagefromuser . "'");
			
			if($fans)
				$fansID = $fans['fansID'];

            $base = pdo_fetch(" SELECT * FROM ". tablename($this->tablebase) ." where uniacid = '" . $uniacid . "'");
			
			$sumdata = pdo_fetchall("select id from " . tablename($this->tabledata) . " where rid = '" . $id . "' and fromuser = '" . $pagefromuser . "' and uniacid = '" . $uniacid . "'");

            if($base['share_top']<=0){//转发赠送上限
                $share_credit = 0;//增加的积分
                $sharetrue = false;//判断是否执行
            }else{
                $share_credit = $base['share_num'];
                $sharetrue = true;
            }

			//message( date('YmdHis').'=='.sprintf('%d', time()));


            //记录分享
            $insert = array(
                'rid' => $id,
                'uniacid' => $_W['uniacid'],
                'from_user' => $from_user,
                'fromuser' => $pagefromuser,
                'avatar' => $avatar,
                'nickname' => $nickname,
                'visitorsip' => CLIENT_IP,
                'visitorstime' => TIMESTAMP,
                'viewnum' => 1,
                'sharecreditnum' => $share_credit,
            );
            if (empty($sharedata)) {
                pdo_insert($this->tabledata, $insert);

                //更新分享数量和积分
                if((count($sumdata) <= $base['share_top']) && $base['share_top']>0 && $sharetrue){
//                    if($fans['sharenum'] + $share_credit > $cs['share_top'])
//                    {
//                        pdo_update($this->tablefans, array('sharenum' => $cs['share_top']), array('from_user' => $pagefromuser,'rid'=>$id));
//                    }
//                    else
//                    {
						 load()->model('mc');
						$uid = mc_openid2uid($pagefromuser);
						mc_credit_update($uid, 'credit1', $share_credit, array($uid, '使用积分商城,赠送'.$share_credit.'积分'));
                        //pdo_update($this->tablefans, array('sharenum' => $fans['sharenum'] + $share_credit), array('from_user' => $pagefromuser,'rid'=>$id));
//                    }
                }

            }

        }


        header("HTTP/1.1 301 Moved Permanently");//301永久重定向
        header("Location: " . $this->createMobileUrl('exmall', array('id' => $id)) . "");//返回首页
        exit();

    }


    public function doMobileMycredit()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];
        $fansID = $_W['member']['uid'];


         $user_agent = $_SERVER['HTTP_USER_AGENT'];
         if (strpos($user_agent, 'MicroMessenger') === false) {
             message('本页面仅支持微信访问!非微信浏览器禁止浏览!', '', 'error');
         }

        //网页授权借用与非借用开始

         load()->model('account');
         $_W['account'] = account_fetch($_W['acid']);
         $cookieid = '__cookie_kuaiwei_exmall_20160928_' . $uniacid;
         $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        $code = $_GPC['code'];
        $type = '';
        if (empty($avatar) || !isset($cookie['openid'])) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid'])) {
                $userinfo = $this->oath_UserInfo($id, $code, $type);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
            }
        }

        //网页授权借用与非借用结束

        //JS权限开始
        $jssdk = new JSSDK();
        $package = $jssdk->getSignPackage();
        //JS权限结束

        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

        load()->model('mc');
		

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE from_user='" . $from_user . "'");
        $data = pdo_fetchall("SELECT * FROM " . tablename($this->tabledata) . " WHERE uniacid = :uniacid AND fromuser='" . $from_user . "'", array(':uniacid' => $_W['uniacid']));

        if ($fans == false) {
            $insert = array(
                'fansID' => $fansID,
                'from_user' => $from_user,
                'todaynum' => 0,
                'totalnum' => 0,
                'awardnum' => 0,
                'avatar' => $avatar,
                'fname' => $nickname,
                'createtime' => time(),
            );
            $temp = pdo_insert($this->tablefans, $insert);
            $newId = pdo_insertid();

            if ($temp == false) {
                message('抱歉，刚才操作数据失败！', '', 'error');
            }

        }





        $list = pdo_fetch("SELECT * FROM ". tablename($this->tablefans) ." WHERE from_user='" . $from_user . "'");

        $credit = mc_credit_fetch($fansID, array('credit1'));
        $credit1 = intval($credit['credit1']);

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->tabledata)." WHERE uniacid = :uniacid AND fromuser = '". $from_user ."'", array('uniacid' => $_W['uniacid']));


        $basecs = pdo_fetch("SELECT * FROM " . tablename($this->tablebase) . " WHERE uniacid = :uniacid ORDER BY `id` DESC", array('uniacid' => $_W['uniacid']));
		
		$erwematype = $basecs['share_type'];
		if(empty($from_user))
			$erwematype =1;

        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array( 'from_user' => $page_from_user));
        $sharetitle = empty($basecs['share_title']) ? '欢迎参加赚积分换礼品！' : $basecs['share_title'];
        $sharedesc = empty($basecs['share_desc']) ? '欢迎参加赚积分换礼品活动！' : str_replace("\r\n", " ", $basecs['share_desc']);
        if (!empty($basecs['share_img'])) {
            $shareimg = toimage($basecs['share_img']);
        }
		if($basecs['guanzhu_img'])
			$erwemaimg = toimage($basecs['guanzhu_img']);
		if($basecs['guanzhu_txt'])
			$erwemaword = $basecs['guanzhu_txt'];
        $nofollow = $this->checkfans();


        include $this->template('mycredit');
    }

   public function doMobileExlist()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];
        $fansID = $_W['member']['uid'];


         $user_agent = $_SERVER['HTTP_USER_AGENT'];
         if (strpos($user_agent, 'MicroMessenger') === false) {
             message('本页面仅支持微信访问!非微信浏览器禁止浏览!', '', 'error');
         }

        //网页授权借用与非借用开始

         load()->model('account');
         $_W['account'] = account_fetch($_W['acid']);
         $cookieid = '__cookie_kuaiwei_exmall_20160928_' . $uniacid;
         $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        $code = $_GPC['code'];
        $type = '';
        if (empty($avatar) || !isset($cookie['openid'])) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid'])) {
                $userinfo = $this->oath_UserInfo($id, $code, $type);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
            }
        }

        //网页授权借用与非借用结束

        //JS权限开始
        $jssdk = new JSSDK();
        $package = $jssdk->getSignPackage();
        //JS权限结束

        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

        load()->model('mc');

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE from_user='" . $from_user . "'");
        $data = pdo_fetchall("SELECT * FROM " . tablename($this->tabledata) . " WHERE uniacid = :uniacid AND fromuser='" . $from_user . "'", array(':uniacid' => $_W['uniacid']));

        if ($fans == false) {
            $insert = array(
                'fansID' => $fansID,
                'from_user' => $from_user,
                'todaynum' => 0,
                'totalnum' => 0,
                'awardnum' => 0,
                'avatar' => $avatar,
                'fname' => $nickname,
                'createtime' => time(),
            );
            $temp = pdo_insert($this->tablefans, $insert);
            $newId = pdo_insertid();

            if ($temp == false) {
                message('抱歉，刚才操作数据失败！', '', 'error');
            }

        }





        $list = pdo_fetch("SELECT * FROM ". tablename($this->tablefans) ." WHERE from_user='" . $from_user . "'");
		$awdata = pdo_fetchall("SELECT * FROM ".tablename($this->tableaward)." where uniacid=:uniacid AND from_user=:from_user ORDER BY createtime DESC",array(':uniacid' => $_W['uniacid'],'from_user' => $from_user));
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($this->tabledata)." WHERE uniacid = :uniacid AND fromuser = '". $from_user ."'", array('uniacid' => $_W['uniacid']));


        $basecs = pdo_fetch("SELECT * FROM " . tablename($this->tablebase) . " WHERE uniacid = :uniacid ORDER BY `id` DESC", array('uniacid' => $_W['uniacid']));
		$erwematype = $basecs['share_type'];
		if(empty($from_user))
			$erwematype =1;	

		$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array( 'from_user' => $page_from_user));
        $sharetitle = empty($basecs['share_title']) ? '欢迎参加赚积分换礼品！' : $basecs['share_title'];
        $sharedesc = empty($basecs['share_desc']) ? '欢迎参加赚积分换礼品活动！' : str_replace("\r\n", " ", $basecs['share_desc']);
        if (!empty($basecs['share_img'])) {
            $shareimg = toimage($basecs['share_img']);
        }

		if($basecs['guanzhu_img'])
			$erwemaimg = toimage($basecs['guanzhu_img']);
		if($basecs['guanzhu_txt'])
			$erwemaword = $basecs['guanzhu_txt'];
        $nofollow = $this->checkfans();


        include $this->template('exlist');
    }

    public function domobileExmall(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];
        $fansID = $_W['member']['uid'];



         $user_agent = $_SERVER['HTTP_USER_AGENT'];
         if (strpos($user_agent, 'MicroMessenger') === false) {
             message('本页面仅支持微信访问!非微信浏览器禁止浏览!', '', 'error');
         }

        //网页授权借用与非借用开始

         load()->model('account');
         $_W['account'] = account_fetch($_W['acid']);
         $cookieid = '__cookie_kuaiwei_exmall_20160928_' . $uniacid;
         $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        $code = $_GPC['code'];
        $type = '';
        if (empty($avatar) || !isset($cookie['openid'])) {
            if (!is_array($cookie) || !isset($cookie['avatar']) || !isset($cookie['openid'])) {
                $userinfo = $this->oath_UserInfo($id, $code, $type);
                $nickname = $userinfo['nickname'];
                $avatar = $userinfo['headimgurl'];
            } else {
                $avatar = $cookie['avatar'];
                $nickname = $cookie['nickname'];
            }
        }

        //网页授权借用与非借用结束

        //JS权限开始
        $jssdk = new JSSDK();
        $package = $jssdk->getSignPackage();
        //JS权限结束

        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE from_user='" . $from_user . "'");

        if ($fans == false) {
            $insert = array(
                'fansID' => $fansID,
                'from_user' => $from_user,
                'todaynum' => 0,
                'totalnum' => 0,
                'awardnum' => 0,
                'avatar' => $avatar,
                'fname' => $nickname,
                'createtime' => time(),
            );
            $temp = pdo_insert($this->tablefans, $insert);
            $newId = pdo_insertid();

            if ($temp == false) {
                message('抱歉，刚才操作数据失败！', '', 'error');
            }

        }
		else
		{
			if(($fans['sharenum']-$fans['todaycredit'])>0&&$from_user)
			{
				$sys_credit = $fans['sharenum']-$fans['todaycredit'];
				$res = pdo_update($this->tablefans, array('sharenum' => 0,'todaycredit'=>0), array('from_user' => $from_user));
				if($res)
				{
					load()->model('mc');
					$uid = mc_openid2uid($from_user);
					mc_credit_update($uid, 'credit1', $sys_credit, array($uid, '移植积分商城系统剩余积分，打入'.$sys_credit.'积分'));
				}
                				
			}
		}


        $list = pdo_fetchall("SELECT * FROM ". tablename($this->tablegoods) ." WHERE uniacid = :uniacid AND status = '1' ", array(':uniacid' => $_W['uniacid']));

        $basecs = pdo_fetch("SELECT * FROM " . tablename($this->tablebase) . " WHERE uniacid = :uniacid ORDER BY `id` DESC", array('uniacid' => $_W['uniacid']));
		$erwematype = $basecs['share_type'];
		$title = "积分兑换商城";
		if($basecs['title'])
			$title = $basecs['title'];
		if(empty($from_user))
			$erwematype =1;	

		$sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array( 'from_user' => $page_from_user));
        $sharetitle = empty($basecs['share_title']) ? '欢迎参加赚积分换礼品！' : $basecs['share_title'];
        $sharedesc = empty($basecs['share_desc']) ? '欢迎参加赚积分换礼品活动！' : str_replace("\r\n", " ", $basecs['share_desc']);
        if (!empty($basecs['share_img'])) {
            $shareimg = toimage($basecs['share_img']);
        }

		if($basecs['guanzhu_img'])
			$erwemaimg = toimage($basecs['guanzhu_img']);
		if($basecs['guanzhu_txt'])
			$erwemaword = $basecs['guanzhu_txt'];
        $nofollow = $this->checkfans();

        include $this->template('exmall');
    }

    public function doMobileCheckjf()
    {
        global $_GPC, $_W;
        $ids = $_GPC['spid'];
        $id = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];
        $fansID = $_W['member']['uid'];
        load()->model('mc');

        $cookieid = '__cookie_kuaiwei_exmall_20160928_' . $uniacid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        //$fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE from_user='" . $from_user . "'");

        $credit = mc_credit_fetch($fansID, array('credit1'));
        $credit1 = intval($credit['credit1']);

        $splist = pdo_fetch("SELECT * FROM ". tablename($this->tablegoods) ." WHERE  uniacid = :uniacid AND id = ". $ids ." ", array(':uniacid' => $_W['uniacid']));

		$data = array(
                    'success' => 1,
                    'msg' => '可以兑换！',
        );

        if ($credit1 == 0) {
            $data = array(
                'success' => 100,
                'msg' => '该用户还未获得积分，分享给好友或者朋友圈可获得积分！',
            );

        } else {
            if($credit1 < $splist['sp_integrals'])
            {
                $data = array(
                    'success' => 100,
                    'msg' => '积分不够，无法兑换！',
                );
            }
		}
		echo json_encode($data);
	}

    public function doMobileExchange()
    {
        global $_GPC, $_W;
        $ids = $_GPC['ids'];
        $id = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];
        $fansID = $_W['member']['uid'];
        load()->model('mc');

        $cookieid = '__cookie_kuaiwei_exmall_20160928_' . $uniacid;
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]),true);
        $from_user = $_W['fans']['from_user'];
        $avatar = $_W['fans']['tag']['avatar'];
        $nickname = $_W['fans']['nickname'];

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->tablefans) . " WHERE from_user='" . $from_user . "'");
		
        $credit = mc_credit_fetch($fansID, array('credit1'));
        $credit1 = intval($credit['credit1']);

        $splist = pdo_fetch("SELECT * FROM ". tablename($this->tablegoods) ." WHERE  uniacid = :uniacid AND id = ". $ids ." ", array(':uniacid' => $_W['uniacid']));

        if ($credit1 == 0) {
            $data = array(
                'success' => 100,
                'msg' => '该用户还未获得积分，分享给好友或者朋友圈可获得积分！',
            );

        } else {
            if($credit1 < $splist['sp_integrals'])
            {
                $data = array(
                    'success' => 100,
                    'msg' => '积分不够，无法兑换！',
                );
            }
            else
            {
                $phonedata = array(
                    'tel' => $_GPC['tel'],
                    'fname' => $_GPC['realname'],
                    'faddr' => $_GPC['addr'],
                );//'todaycredit' => $fans['todaycredit'] + $splist['sp_integrals'],

				mc_credit_update($fansID, 'credit1', -intval($splist['sp_integrals']), array($fansID, '礼品兑换: '.$splist['sp_title'].' 消耗积分：'.$splist['sp_integrals']));
                $temp = pdo_update($this->tablefans, $phonedata, array( 'from_user' => $from_user));

                if ($temp === false) {

                    $data = array(
                        'success' => 100,
                        'msg' => '保存数据错误！',
                    );
                } else {
                    load()->model('mc');
                    mc_update($fansID, array('mobile' => $_GPC['tel'],'nickname' => $_GPC['realname']));
                    $awarddata = array(
                        'uniacid' => $_W['uniacid'],
                        'fansID' => $fansID,
                        'from_user' => $from_user,
                        'fname' => $_GPC['realname'],
                        'tel' => $_GPC['tel'],
                        'faddr' => $_GPC['addr'],
                        'avatar' => $fans['avatar'],
                        'todaycredit' => $fans['todaycredit'],
                        'sharenum' => $credit1,
                        'spname' => $splist['sp_title'],
                        'sp_integrals' => $splist['sp_integrals'],
                        'states' => 1,
                        'createtime' => time(),
                    );

                    $award = pdo_insert($this->tableaward,$awarddata);
                    if($awarddata == false)
                    {
                        $data = array(
                            'success' => 100,
                            'msg' => '奖品表插入失败',
                        );
                    }
                    else
                    {
                        pdo_update($this->tablegoods, array('sp_numbers' =>($splist['sp_numbers']-1)), array('id' => $ids, 'uniacid' => $_W['uniacid']));
						$data = array(
                            'success' => 1,
                            'msg' => '兑换成功！您花了'.$splist['sp_integrals'].'积分，成功兑换了'.$splist['sp_title'],
                        );
                    }
                }
            }
        }
        echo json_encode($data);
    }


    public  function  doWebBaseset()
    {
        global $_GPC, $_W;
        $set = $this->get_baseset();
        if (checksubmit('submit')) {
            $data = array(
                'uniacid' => $_W['uniacid'],
				'title' => $_GPC['title'],
                'share_type' => $_GPC['share_type'],
                'guanzhu_txt' => $_GPC['guanzhu_txt'],
                'guanzhu_img' => $_GPC['guanzhu_img'],
                'share_title' => $_GPC['share_title'],
                'share_desc' => $_GPC['share_desc'],
                'share_img' => $_GPC['share_img'],
                'btm_adtype' => $_GPC['btm_adtype'],
                'share_top' => $_GPC['share_top'],
                'share_num' => $_GPC['share_num'],
                'top_adtitle' => $_GPC['top_adtitle'],
                'top_adimg' => $_GPC['top_adimg'],
                'top_adurl' => $_GPC['top_adurl'],
                'btm_adtitle' => $_GPC['btm_adtitle'],
                'btm_adimg' => $_GPC['btm_adimg'],
                'btm_adurl' => $_GPC['btm_adurl'],
            );
            if (!empty($set)) {
                pdo_update($this->tablebase, $data, array('id' => $set['id']));
            } else {
                pdo_insert($this->tablebase, $data);
            }
            $this->write_cache("baseset_" . $_W['uniacid'], $data);
            message('提交参数成功！', 'refresh');
        }
        include $this->template('baseset');
    }


    public function get_baseset() {

        global $_W, $_GPC;
        $path = "/addons/kuaiwei_exmall";
        $filename = IA_ROOT . $path . "/data/baseset_" . $_W['uniacid'] . ".txt";


        if (is_file($filename)) {
            $content = file_get_contents($filename);
            if (empty($content)) {
                return false;
            }
            return json_decode(base64_decode($content), true);

        }
        return pdo_fetch("SELECT * FROM " . tablename($this->tablebase) . " WHERE uniacid = :uniacid limit 1", array(':uniacid' => $_W['uniacid']));

    }


    //json数据发送
    public function message($_data = '', $_msg = '')
    {
        if (!empty($_data['runcode']) && $_data['runcode'] != 2) {
            $this->setfans();
        }
        if (empty($_data)) {
            $_data = array(
                'name' => "马上就要中奖了哦",
                'runcode' => 100,
            );
        }
        if (!empty($_msg)) {
            $_data['msg'] = $_msg['msg'];
            $_data['runcode'] = $_msg['runcode'];
        }
        die(json_encode($_data));
    }

    public function doWebDeleteAll()
    {
        global $_GPC, $_W;
        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $flow = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['uniacid']));
            if (empty($flow)) {
                $this->webmessage('抱歉，要修改的规则不存在或是已经被删除！');
            }
            if (pdo_delete('rule', array('id' => $rid))) {
                pdo_delete('rule_keyword', array('rid' => $rid));
                //删除统计相关数据
                pdo_delete('stat_rule', array('rid' => $rid));
                pdo_delete('stat_keyword', array('rid' => $rid));
                //调用模块中的删除
                $module = WeUtility::createModule($flow['module']);
                if (method_exists($module, 'ruleDeleted')) {
                    $module->ruleDeleted($rid);
                }
            }
        }
        $this->webmessage('规则操作成功！', '', 0);
    }



    //兑换商品设置开始

    public function  doWebSpmanage()
    {
        global $_GPC, $_W;

        $list = pdo_fetchall("SELECT * FROM ". tablename($this->tablegoods) ." WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));

        $total = pdo_fetchcolumn("SELECT * FROM ". tablename($this->tablegoods) ." WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);

        include $this->template('spmanage');
    }


    public function doWebSpnew(){

        global $_GPC, $_W;

        $insert = array(
            'uniacid' => $_W['uniacid'],
            'sp_img' => $_GPC['sp_img'],
            'sp_title' =>$_GPC['sp_title'],
            'sp_url' =>$_GPC['sp_url'],
            'sp_numbers' => $_GPC['sp_numbers'],
            'sp_integrals' => $_GPC['sp_integrals'],
            'status' =>1,
        );

        $temp = pdo_insert($this->tablegoods, $insert);

        if($temp == false)
        {
            $data = array(
                'success' => 100,
                'msg' => '保存失败',
            );
        }
        else
        {
            $data = array(
                'success' => 1,
                'msg' => '保存数据成功',
            );
        }

        echo json_encode($data);

    }



    public function doWebSpupdata(){

        global $_GPC, $_W;

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if($operation == 'updata'){

            $id = $_GPC['listid'];

            $updata = array(
                'sp_img' => $_GPC['sp_img'],
                'sp_title' =>$_GPC['sp_title'],
                'sp_url' =>$_GPC['sp_url'],
                'sp_numbers' => $_GPC['sp_numbers'],
                'sp_integrals' => $_GPC['sp_integrals'],
                'status' =>$_GPC['status'],
            );


            $temp =  pdo_update($this->tablegoods,$updata,array('id'=>$id));

            message("更新商品成功",$this->createWebUrl('spmanage'),"success");


        }else{

            $id = $_GPC['id'];

            $list = pdo_fetch("SELECT * FROM " . tablename($this->tablegoods) . " WHERE uniacid = :uniacid and id = :id", array(':uniacid' => $_W['uniacid'],':id' => $id));

//            load()->model('reply');
//            $sql = "uniacid = :uniacid and `module` = :module";
//            $params = array();
//            $params[':uniacid'] = $_W['uniacid'];
//            $params[':module'] = 'ruifan_zhuli';
//
//            $rules = reply_search($sql, $params);

            include $this->template('spupdata');
        }
    }


    public function doWebDeletesp()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $tjsp = pdo_fetch("SELECT id FROM " . tablename($this->tablegoods) . " WHERE id = :id and uniacid=:uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));
        if (empty($tjsp)) {
            message('抱歉，要修改的商品不存在或是已经被删除！');
        }
        if (pdo_delete($this->tablegoods, array('id' => $id))) {
            message('规则操作成功！', referer(), 'success');
        }

    }


    public function doWebSetspshow()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $status = intval($_GPC['status']);

        if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update($this->tablegoods, array('status' => $status), array('id' => $id));
        message('状态设置成功！', referer(), 'success');
    }


    //兑换商品设置结束


    //底部广告设置开始

    public function doWebAdmanage()
    {
        global $_GPC, $_W;

        $list = pdo_fetchall("SELECT * FROM " . tablename($this->tablead) . " WHERE uniacid = :uniacid ", array(':uniacid' => $_W['uniacid']));
        $total = pdo_fetchcolumn("SELECT count(id) FROM " . tablename($this->tablead) . " WHERE uniacid = :uniacid ", array(':uniacid' => $_W['uniacid']));
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);

        include $this->template('admanage');
    }


    public function doWebAdnew(){

        global $_GPC, $_W;

        $insert = array(
            'uniacid' => $_W['uniacid'],
            'adtitle' => $_GPC['adtitle'],
            'adimg' =>$_GPC['adimg'],
            'adurl' =>$_GPC['adurl'],
            'status' =>1,
        );

        pdo_insert($this->tablead, $insert);

        $data = array(
            'success' => 1,
            'msg' => '保存数据成功',
        );

        echo json_encode($data);

    }


    public function doWebAdupdata(){

        global $_GPC, $_W;

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if($operation == 'updata'){

            $id = $_GPC['listid'];

            $updata = array(
                'adtitle' => $_GPC['adtitle'],
                'adimg' => $_GPC['adimg'],
                'adurl' => $_GPC['adurl'],
                'status' => $_GPC['status'],
            );


            $temp =  pdo_update($this->tablead,$updata,array('id'=>$id));

            message("更新广告成功",$this->createWebUrl('admanage'),"success");


        }else{

            $id = $_GPC['id'];

            $list = pdo_fetch("SELECT * FROM " . tablename($this->tablead) . " WHERE uniacid = :uniacid and id = :id", array(':uniacid' => $_W['uniacid'],':id' => $id));

            include $this->template('adupdata');
        }
    }



    public function doWebDeletead()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $rule = pdo_fetch("SELECT id FROM " . tablename($this->tablead) . " WHERE id = :id and uniacid=:uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete($this->tablead, array('id' => $id))) {

        }
        message('规则操作成功！', referer(), 'success');
    }


    public function doWebSetadshow()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $status = intval($_GPC['status']);

        if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update($this->tablead, array('status' => $status), array('id' => $id));
        message('状态设置成功！', referer(), 'success');
    }

    //底部广告设置结束

    public function doWebJfmanage()
    {
        global $_GPC, $_W;

        $list = pdo_fetchall("SELECT * FROM ".tablename($this->tableaward)." where uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
        include $this->template('jfmanage');
    }

    public function doWebSetdhconf()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $states = $_GPC['states'];
        $fansid = $_GPC['fansID'];

        if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
//        $award = pdo_fetch("SELECT * FROM ". tablename($this->tableaward) ." WHERE id = '". $id ."' ");
        $temp = pdo_update($this->tableaward, array('states' => $states,'consumetime' => time()), array('id' => $id));
        message('状态设置成功！', referer(), 'success');
    }




    public function webmessage($error, $url = '', $errno = -1)
    {
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
