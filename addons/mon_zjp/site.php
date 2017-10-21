<?php
/**
 * codeMonkey:631872807
 */
defined('IN_IA') or exit('Access Denied');

define("MON_ZJP", "mon_zjp");
define("MON_ZJP_RES", "../addons/" . MON_ZJP . "/");
require_once IA_ROOT . "/addons/" . MON_ZJP . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_ZJP . "/oauth2.class.php";

/**
 * Class Mon_JggModuleSite
 */
class Mon_ZjpModuleSite extends WeModuleSite
{
    public $weid;
    public $acid;
    public $oauth;


    public static $STATUS_UNDO = 1;//未处理
    public static $STATUS_APPLY = 2;//已申请领奖
    public static $STATUS_COMPLETED_DH = 3;//已发奖
    public static $STATUS_OVER = 4;//已完成

    public static $DEBUG = false;


    public static $MSG_TYPE_DIALOG = 1;
    public static $MSG_TYPE_SUCCESS = 2;
    public static $MSG_TYPE_FAIL = 3;


    function __construct()
    {
        global $_W;
        $this->weid = $_W['uniacid'];

        $this->oauth = new Oauth2("", "");

    }


    /**
     * 活动管理
     */
    public function  doWebZjpManage()
    {

        global $_W, $_GPC;

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {

            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_zjp) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_zjp) . " WHERE weid =:weid ", array(':weid' => $this->weid));
            $pager = pagination($total, $pindex, $psize);
        } else if ($operation == 'delete') {
            $id = $_GPC['id'];
            pdo_delete(CRUD::$table_zjp_record, array("zid" => $id));
            pdo_delete(CRUD::$table_zjp_user, array("zid" => $id));
            pdo_delete(CRUD::$table_zjp_prize, array("zid" => $id));
            pdo_delete(CRUD::$table_zjp, array("id" => $id));

            message('删除成功！', referer(), 'success');
        }


        include $this->template("zjp_manage");

    }


    /**
     * author: codeMonkey QQ:631872807
     * 介绍页
     */
    public function  doMobileIndex()
    {

        global $_W, $_GPC;

        $this->checkmobile();


        $is_follow = false;;
        $zid = $_GPC['zid'];
        $zjp = CRUD::findById(CRUD::$table_zjp, $zid);


        if (empty($zjp)) {
            message("抓奖品活动删除或不存在!");
        }

        $openid = $_W['fans']['from_user'];


        if (empty($openid)) {

            $openid = $_GPC['openid'];
        }

        if (!empty($openid)) {

            $userInfo = $this->setClientUserInfo($openid);
        }

        if (empty($userInfo)) {

            $userInfo = $this->getClientUserInfo();// 从cookie中取

        }

        if (!empty($userInfo) && !empty($userInfo['nickname'])) {//已关注过

            $is_follow = true;

        }




        $prizes = pdo_fetchall("select * from " . tablename(CRUD::$table_zjp_prize) . " where zid=:zid order by sort asc", array(":zid" => $zid));


        include $this->template('index');


    }


    /**
     * author: codeMonkey QQ:631872807
     * 抓奖品页面
     */
    public function  doMobileZjp()
    {

        global $_W, $_GPC;

        $this->checkmobile();

        $zid = $_GPC['zid'];
        $zjp = CRUD::findById(CRUD::$table_zjp, $zid);

        if (empty($zjp)) {
            message("抓奖品活动删除或不存在!");
        }
        $userInfo = $this->getClientUserInfo();// 从cookie中取

        if (empty($userInfo) || (!empty($userInfo) && empty($userInfo['nickname']))) {

            $follow_url = $zjp['follow_url'];
            header("location: $follow_url");
            exit;

        }


        $dbUser = CRUD::findUnique(CRUD::$table_zjp_user, array(":openid" => $userInfo['openid'], ":zid" => $zid));


        if (empty($dbUser)) {//用户没有参加过


            $leftPlayCount = $zjp['play_count'];

            if ($zjp['share_award_enable'] == 1) {//开启了分享奖励设置

                $leftShare = $zjp['share_award_count'];

            } else {
                $leftShare = 0;
            }

            $already_playCount = 0;

        } else {

            $totalLimitCount = $dbUser['play_count'] + $dbUser['award_play_count'];
            $already_playCount = $this->findUserRecordCount($zid, $dbUser['id']);
            $leftPlayCount = $totalLimitCount - $already_playCount;

            if ($zjp['share_award_enable'] == 1) {

                $leftShare = $zjp['share_award_count'] - $dbUser['share_award_count'];

                if ($leftShare < 0) {
                    $leftShare = 0;
                }

            } else {
                $leftShare = 0;

            }


        }


        $ads=$this->getArrayMsg($zjp,Mon_ZjpModuleSite::$MSG_TYPE_DIALOG);


        include $this->template('zjp');


    }


    /**
     * author: codeMonkey QQ:631872807
     * 奖品显示页面
     */
    public function doMobileUserPrize()
    {

        global $_W, $_GPC;

        $this->checkmobile();

        $zid = $_GPC['zid'];
        $zjp = CRUD::findById(CRUD::$table_zjp, $zid);

        if (empty($zjp)) {
            message("抓奖品活动删除或不存在!");
        }

        $userInfo = $this->getClientUserInfo();// 从cookie中取

        if (empty($userInfo) || (!empty($userInfo) && empty($userInfo['nickname']))) {

            $follow_url = $zjp['follow_url'];
            header("location: $follow_url");
            exit;

        }


        $dbUser = CRUD::findUnique(CRUD::$table_zjp_user, array(":openid" => $userInfo['openid'], ":zid" => $zid));


        if (empty($dbUser)) {//用户没有参与过

            $recordCount = 0;
            $limitPlayCount = $zjp['play_count'];

            if ($zjp['share_award_enable'] == 1) {
                // $lockCount=$zjp['share_award_count']*$zjp['share_award_time'];
				  $lockCount = 0;
            } else {
                $lockCount = 0;
            }

        } else {
            $limitPlayCount = $dbUser['play_count'] + $dbUser['award_play_count'];
            $recordCount = $this->findUserRecordCount($zid, $dbUser['id']);

            $playRecords = pdo_fetchall("select a.*,b.pname as pname from " . tablename(CRUD::$table_zjp_record) . " a left join " . tablename(CRUD::$table_zjp_prize) . " b  on a.pid=b.id where a.zid=:zid and a.uid=:uid order by createtime asc", array(':zid' => $zid, ':uid' => $dbUser['id']));

            if ($zjp['share_award_enable'] == 1){
                $lockCount = ($zjp['share_award_count'] - $dbUser['share_award_count']) * $zjp['share_award_time'];
            }else{
                $lockCount = 0;
            }


        }

        include $this->template('prize');

    }


    /**
     * author: codeMonkey QQ:631872807
     * 中奖名单
     */
    public function  doMobileLuckUser()
    {

        global $_W, $_GPC;

        $zid = $_GPC['zid'];

        $zjp = CRUD::findById(CRUD::$table_zjp, $zid);

        if (empty($zjp)) {
            message("抓奖品活动删除或不存在!");
        }


        $luckUsers = pdo_fetchall("select a.*, b.nickname as nickname,b.headimgurl as headimgurl,(select pname from " . tablename(CRUD::$table_zjp_prize) . " c where c.id=a.pid ) as pname from " . tablename(CRUD::$table_zjp_record) . " a left join   " . tablename(CRUD::$table_zjp_user) . " b on a.uid=b.id where a.zid=:zid and a.pid!=0 order by a.createtime desc ", array(":zid" => $zid));


        include $this->template('luck_user');

    }


    /**
     * author: codeMonkey QQ:631872807
     * 注册手机号
     */
    public function  doMobileRegistTel()
    {
        global $_W, $_GPC;

        $tel = $_GPC['tel'];
        $uid = $_GPC['uid'];
        $zid = $_GPC['zid'];
        $zjp = CRUD::findById(CRUD::$table_zjp, $zid);
        $res = array();


        if (empty($zjp)) {
            $res['code'] = 500;
            $res['msg'] = "活动删除或不存在";
            echo json_encode($res);
            exit;
        }

        $user = CRUD::findById(CRUD::$table_zjp_user, $uid);


        if (empty($user)) {
            $res['code'] = 500;
            $res['msg'] = "你还没有参加游戏呢";
            echo json_encode($res);
            exit;
        }


        $dbUser = CRUD::findUnique(CRUD::$table_zjp_user, array(':tel' => $tel, ':zid' => $zid));


        if (!empty($dbUser)) {
            $res['code'] = 500;
            $res['msg'] = "该手机号已绑定过，请换号码吧，亲！";
            echo json_encode($res);
            exit;
        }

        CRUD::updateById(CRUD::$table_zjp_user, array('tel' => $tel), $uid);


        $res['code'] = 200;


        echo json_encode($res);

        exit;


    }


    /**
     * author: codeMonkey QQ:631872807
     * 参与用户
     */
    public function  doWebPlay_user()
    {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if ($operation == 'display') {

            $zid = $_GPC['zid'];

            $zjp = CRUD::findById(CRUD::$table_zjp, $zid);

            if (empty($zjp)) {
                message("抓奖品活动删除或不存在");

            }


            $keyword = $_GPC['keyword'];

            $where = '';
            $params = array(
                ':zid' => $zid
            );


            if (!empty($keyword)) {
                $where .= ' and (nickname like :nickname) or (tel like :tel)';
                $params[':nickname'] = "%$keyword%";
                $params[':tel'] = "%$keyword%";
            }


            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_zjp_user) . " WHERE zid =:zid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_zjp_user) . " WHERE zid =:zid  " . $where, $params);
            $pager = pagination($total, $pindex, $psize);

        } else if ($operation == 'delete') {
            $id = $_GPC['id'];
            pdo_delete(CRUD::$table_zjp_record, array("uid" => $id));
            pdo_delete(CRUD::$table_zjp_user, array("id" => $id));
            message('删除成功！', referer(), 'success');
        }


        include $this->template("user_list");


    }


    /**
     * author: codeMonkey QQ:63187280
     * 抓奖品记录
     */
    public function  doWebzjRecordList()
    {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $zid = $_GPC['zid'];
        $prizes = pdo_fetchall("select * from " . tablename(CRUD::$table_zjp_prize) . " where zid=:zid order by sort asc", array(":zid" => $zid));

        $keyword = $_GPC['keywords'];
        $where = '';
        $params = array(
            ':zid' => $zid

        );

        $pid = 0;
        if (!empty($_GPC['pid'])) {
            $pid = $_GPC['pid'];
        }
        if ($pid != 0) {
            $where = " and r.pid=$pid";
        }

        if (!empty($keyword)) {
            $where .= ' and u.tel like :tel';
            $params[':tel'] = "%$keyword%";
        }

        if (!empty($_GPC['uid'])) {
            $where .= ' and r.uid=:uid';
            $params[':uid'] = $_GPC['uid'];
        }

        $status = $_GPC['status'];
        if ($_GPC['status'] != '') {

            $where .= ' and r.award_status=:status';
            $params[':status'] = $_GPC['status'];
        }


        if ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("select r.*,u.tel as tel, u.nickname as nickname,u.headimgurl as headimgurl,(select pname from " . tablename(CRUD::$table_zjp_prize) . " c where c.id=r.pid ) as pname from " . tablename(CRUD::$table_zjp_record) . " r left join   " . tablename(CRUD::$table_zjp_user) . " u on r.uid=u.id where r.zid=:zid " . $where . " order by r.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_zjp_record) . " r left join " . tablename(CRUD::$table_zjp_user) . " u on r.uid=u.id  WHERE r.zid =:zid " . $where, $params);
            $pager = pagination($total, $pindex, $psize);

        } elseif ($operation == 'delete') {

            $id = $_GPC['id'];
            pdo_delete(CRUD::$table_zjp_record, array('id' => $id));
            message('删除成功！', referer(), 'success');

        } elseif ($operation == 'lq') {

            $id = $_GPC['id'];
            CRUD::updateById(CRUD::$table_zjp_record, array('award_status' => 2), $id);

            message('用户领取奖品成功！', referer(), 'success');

        }


        include $this->template('zj_record_list');

    }


    /**
     * author: codeMonkey QQ:63187280
     * 玩游戏
     */
    public function  doMobilePlay()
    {
        global $_GPC, $_W;

        $zid = $_GPC['zid'];
        $zjp = CRUD::findById(CRUD::$table_zjp, $zid);


        $res = array();

        if (empty($zjp)) {

            $res['code'] = 500;
            $res['msg'] = "抓奖品活动删除或不存在!";
            echo json_encode($res);
            exit;
        }

        if (TIMESTAMP < $zjp['starttime']) {
            $res['code'] = 500;
            $res['msg'] = "活动还未开始呢，歇会再来吧!";
            echo json_encode($res);
            exit;

        }

        if (TIMESTAMP > $zjp['endtime']) {
            $res['code'] = 500;
            $res['msg'] = "活动已结束，下次再来吧!";
            echo json_encode($res);
            exit;
        }


        $userInfo = $this->getClientUserInfo();

        if (empty($userInfo) || (!empty($userInfo) && empty($userInfo['nickname']))) {

            $res['code'] = 500;
            $res['msg'] = "请授权登录后再进行抽检活动!";
            echo json_encode($res);
            exit;

        }


        $user_share_award_count = 0;

        $dbUser = CRUD::findUnique(CRUD::$table_zjp_user, array(":openid" => $userInfo['openid'], ":zid" => $zid));

        if (empty($dbUser)) {//哥们首次玩游戏

            $userdata = array(
                'zid' => $zid,
                'openid' => $userInfo['openid'],
                'nickname' => $userInfo['nickname'],
                'headimgurl' => $userInfo['headimgurl'],
                'share_award_count' => 0,
                'play_count' => $zjp['play_count'],
                'award_play_count' => 0,
                'share_count' => 0,
                'createtime' => TIMESTAMP

            );

            CRUD::create(CRUD::$table_zjp_user, $userdata);

            $userid = pdo_insertid();


            $prize = $this->createPlayRecored($zjp, $userid, $userInfo['openid']);


            $totalLimitCount = $zjp['play_count'];

            $already_playCount = 1;
            $leftCount = $zjp['play_count'] - 1;

        } else {


            $totalLimitCount = $dbUser['play_count'] + $dbUser['award_play_count'];
            $already_playCount = $this->findUserRecordCount($zid, $dbUser['id']);
            $leftCount = $totalLimitCount - $already_playCount;



            if ($leftCount <= 0) {
                $res['code'] = 500;
                $res['msg'] = "您已经没有机会了下次再来吧!";
                echo json_encode($res);
                exit;
            }

            $day_limiCount=$zjp['day_play_count'];
            $user_day_play_count= $this->findUserDayRecordCount($zid,$dbUser['id']);


            if($day_limiCount-$user_day_play_count<=0) {
                $res['code'] = 500;
                $res['msg'] = "您今天的抽奖机会已用完!";
                echo json_encode($res);
                exit;
            }

            $prize = $this->createPlayRecored($zjp, $dbUser['id'], $dbUser['openid']);

            $leftCount = $leftCount - 1;
            $user_share_award_count = $dbUser['share_award_count'];

        }


        $res['code'] = 200;
        $res["result"] = "success";

        if (empty($prize)) {
            $res["hasPrize"] = false;
            $res["name"] = "";
        } else {
            $res["hasPrize"] = true;
            $res["name"] = $prize['pname'];
            if (empty($prize['picon'])) {
                $res["img"] = $_W['attachurl'] . $prize['picon'];
            } else {
                $res["img"] = $this->defaultImg(1);
            }


        }
        $res["playCount"] = $already_playCount;
        $res["surplusCount"] = $leftCount;


        if ($zjp['share_award_enable'] == 1) {

            $res['leftShare'] = $zjp['share_award_count'] - $user_share_award_count;//剩下的分享次数

        } else {
            $res['leftShare'] = 0;

        }

        if ($res['leftShare'] < 0) {
            $res['leftShare'] = 0;
        }

        echo json_encode($res);


    }


    /**
     * author: codeMonkey QQ:631872807
     * @param $dzp
     * @param $uid
     * @param $openid
     * 插入记录
     */

    public function  createPlayRecored($zjp, $uid, $openid)
    {


        $user_awardcount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_zjp_record) . " WHERE  uid=:uid and zid=:zid and award_status=1", array(':uid' => $uid, ":zid" => $zjp['id']));

        if ($user_awardcount < $zjp['u_award_count']) {//用户没有超过中奖次数限制


            $prizes = pdo_fetchall("select * from " . tablename(CRUD::$table_zjp_prize) . " where zid=:zid order by sort asc ", array(":zid" => $zjp['id']));

            $arrayRand = array();
            $totalp = 0;
            for ($index = 0; $index < count($prizes); $index++) {

                $arrayRand[$index] = $prizes[$index]['percent'];

                $totalp += $prizes[$index]['percent'];

            }

            $arrayRand[count($prizes)] = 100 - $totalp;//没有中奖的概率

            $pIndex = $this->get_rand($arrayRand);//随机

            if ($pIndex != count($prizes)) {// 中奖了
                $areadyAwardCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_zjp_record) . " WHERE   zid=:zid and award_status=1 and pid=:pid", array(":zid" => $zjp['id'], ":pid" => $prizes[$pIndex]["id"]));

                if ($areadyAwardCount < $prizes[$pIndex]["count"]) {//中奖数控制

                    $prize = $prizes[$pIndex];//中的奖品
                }


            }


        }


        if (!empty($prize)) {
            $pid = $prize['id'];
            $award_status = 1;
            $msgArray=$this->getArrayMsg($zjp,Mon_ZjpModuleSite::$MSG_TYPE_SUCCESS);
        } else {
            $pid = 0;
            $award_status = 0;
            $msgArray=$this->getArrayMsg($zjp,Mon_ZjpModuleSite::$MSG_TYPE_FAIL);
        }


        $msg=$msgArray[rand(0,count($msgArray)-1)];



        $record_data = array(
            'uid' => $uid,
            'zid' => $zjp['id'],
            'openid' => $openid,
            'pid' => $pid,
            'msg'=>$msg,
            'award_status' => $award_status,
            'stauts' => 0,
            'createtime' => TIMESTAMP

        );


        CRUD::create(CRUD::$table_zjp_record, $record_data);


        return $prize;

    }


    /*
     * 奖励用户分享次数奖励
     */
    public function  doMobileShareAward()
    {
        global $_GPC, $_W;
        $zid = $_GPC['zid'];
        $zjp = CRUD::findById(CRUD::$table_zjp, $zid);
        $userInfo = $this->getClientUserInfo();
        if (!empty($zjp)) {

            if (!empty($userInfo) && !empty($userInfo['nickname'])) {

                $dbUser = CRUD::findUnique(CRUD::$table_zjp_user, array(":openid" => $userInfo['openid'], ":zid" => $zid));

                if (!empty($dbUser) && ($zjp['share_award_enable'] == 1)) {//奖励


                    $updateUserData = array(
                        'share_award_count' => $dbUser['share_award_count'] + 1,
                        'award_play_count' => $dbUser['award_play_count'] + $zjp['share_award_time']

                    );

                    CRUD::updateById(CRUD::$table_zjp_user, $updateUserData, $dbUser['id']);

                }

            }


        }


        if (!empty($dbUser)) {
            $dbUser = CRUD::findUnique(CRUD::$table_zjp_user, array(":openid" => $userInfo['openid'], ":zid" => $zid));//重新数据库查询一次
            $totalLimitCount = $dbUser['play_count'] + $dbUser['award_play_count'];
            $already_playCount = $this->findUserRecordCount($zid, $dbUser['id']);
            $leftCount = $totalLimitCount - $already_playCount;
            $leftShare = $res['leftShare'] = $zjp['share_award_count'] - $dbUser['share_award_count'];//剩下的分享次数
            if ($leftShare < 0) {
                $leftShare = 0;
            }

            $res = array('code' => 200, 'leftShare' => $leftShare, 'leftCount' => $leftCount, 'playCount' => $already_playCount);

            echo json_encode($res);

        } else {

            $res = array('code' => 500, "用户还没有注册");
            echo json_encode($res);

        }


    }


    /**
     * author: codeMonkey QQ:631872807
     * 抓奖品记录导出
     */
    public function  doWebzjdownload()
    {

        require_once 'zjdownload.php';


    }

    /**
     * author: codeMonkey QQ:631872807
     * 用户信息导出
     */
    public function  doWebUDownload()
    {


        require_once 'udownload.php';



    }


    /**
     * 概率计算
     *
     * @param unknown $proArr
     * @return Ambigous <string, unknown>
     */
    function get_rand($proArr)
    {
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


    public function  defaultImg($type)
    {

        switch ($type) {
            case 0://大背景图
                $imgName = "slide01-bg.png";
                break;
            case 1://中奖图片
                $imgName = "slide02-prize01.png";
                break;
            case 2://宣传背景图
                $imgName = "slide01-top.png";
                break;

        }

        return MON_ZJP_RES . "images/" . $imgName;

    }


    public function str_murl($url)
    {
        global $_W;
        return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

    }


    public function  checkmobile()
    {

        if (!Mon_ZjpModuleSite::$DEBUG) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') === false) {
                echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
                exit();
            }
        }


    }


    /**
     * author: codeMonkey QQ:631872807
     * @param $openid
     */
    public function setClientUserInfo($openid)
    {
        global $_W;

        if (!empty($openid)) {


         //   load()->model('account');
         //   $token = WeAccount::token(WeAccount::TYPE_WEIXIN);



            load()->classs('weixin.account');
            $accObj= WeixinAccount::create($_W['acid']);
            $access_token = $accObj->fetch_token();






            if (empty($access_token)) {
                message("获取accessToken失败");
            }
            $userInfo = $this->oauth->getUserInfo($access_token, $openid);
            if (!empty($userInfo)) {
                $cookie = array();
                $cookie['openid'] = $userInfo['openid'];
                $cookie['nickname'] = $userInfo['nickname'];
                $cookie['headimgurl'] = $userInfo['headimgurl'];
                $session = base64_encode(json_encode($cookie));

                isetcookie('__monzjpuser', $session, 24 * 3600 * 365);

            }

            return $userInfo;
        }


    }


    /**
     * author: codeMonkey QQ:631872807
     * 兑换处理
     */
    public function  statusText($stauts)
    {

        $statusText = "未知状态";
        switch ($stauts) {
            case Mon_JggModuleSite::$STATUS_UNDO:
                $statusText = "已中奖（待用户申请领奖）";
                break;
            case Mon_JggModuleSite::$STATUS_APPLY:
                $statusText = "用户申请领奖";
                break;
            case Mon_JggModuleSite::$STATUS_COMPLETED_DH:
                $statusText = "已发奖（待用户确认收奖）";
                break;
            case Mon_JggModuleSite::$STATUS_OVER:
                $statusText = "领奖完成（用户已确认）";
                break;
        }

        return $statusText;

    }


    /**
     * 玩的次数
     * author: codeMonkey QQ:631872807
     * @param $did
     * @param $uid
     * @return bool
     */
    public function  findUserRecordCount($zid, $uid)
    {

        $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_zjp_record) . " WHERE  uid=:uid and zid=:zid ", array(':uid' => $uid, ":zid" => $zid));
        return $count;


    }


    /**
     * author:codeMonkey QQ 631872807
     * 获取哟规划信息
     * @return array|mixed|stdClass
     */
    public function  getClientUserInfo()
    {
        global $_GPC;
        $session = json_decode(base64_decode($_GPC['__monzjpuser']), true);
        return $session;

    }


    /**
     * author: codeMonkey QQ:631872807
     * @param $zjp
     * @param $msg_type
     * 获取数组
     */
    public function  getArrayMsg($zjp, $msg_type)
    {

        if (empty($zjp)) return;


        switch ($msg_type) {
            case Mon_ZjpModuleSite::$MSG_TYPE_DIALOG://对话框
                $content = $zjp['dialog_tips'] == '' ? CRUD::$DIALOG_MSG : $zjp['dialog_tips'];
                break;
            case Mon_ZjpModuleSite::$MSG_TYPE_SUCCESS://抽奖成功
                $content = $zjp['success_award_tips'] == '' ? CRUD::$SUCC_MSG : $zjp['success_award_tips'];
                break;
            case Mon_ZjpModuleSite::$MSG_TYPE_FAIL://抽奖失败
                $content = $zjp['fail_award_tips'] == '' ? CRUD::$FAIL_MSG : $zjp['fail_award_tips'];
                break;

        }


        $content = trim($content);
        $content_arr = explode("\r\n", $content);


        return $content_arr;


    }


    /**
     * author: codeMonkey QQ:631872807
     * 删除奖品
     */
    public function  doWebDeletePrize()
    {
        global $_GPC;

        $pid = $_GPC['pid'];

        $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_zjp_record) . " WHERE  pid=:pid", array(':pid' => $pid));


        $res = array();

        if ($count > 0) {

            $res['code'] = 500;
        } else {
            $res['code'] = 200;
        }


        echo json_encode($res);


    }


    public function  findUserDayRecordCount($zid,$uid)
    {

        $today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
        $today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));

        $count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(CRUD::$table_zjp_record) . " WHERE  uid=:uid and zid=:zid and createtime<=:endtime and  createtime>=:starttime ", array(':uid' =>$uid, ":zid" =>$zid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
        return $count;
    }


}