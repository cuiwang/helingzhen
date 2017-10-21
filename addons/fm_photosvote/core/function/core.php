<?php
defined('IN_IA') or exit('Access Denied');
class Core extends WeModuleSite
{
    public $title = '女神来了！';
    public $table_reply = 'fm_photosvote_reply';
    public $table_reply_share = 'fm_photosvote_reply_share';
    public $table_reply_huihua = 'fm_photosvote_reply_huihua';
    public $table_reply_display = 'fm_photosvote_reply_display';
    public $table_reply_vote = 'fm_photosvote_reply_vote';
    public $table_reply_body = 'fm_photosvote_reply_body';
    public $table_users = 'fm_photosvote_provevote';
    public $table_pnametag = 'fm_photosvote_pnametag';
    public $table_voteer = 'fm_photosvote_voteer';
    public $table_tags = 'fm_photosvote_tags';
    public $table_users_picarr = 'fm_photosvote_provevote_picarr';
    public $table_users_voice = 'fm_photosvote_provevote_voice';
    public $table_users_name = 'fm_photosvote_provevote_name';
    public $table_log = 'fm_photosvote_votelog';
    public $table_qunfa = 'fm_photosvote_qunfa';
    public $table_shuapiao = 'fm_photosvote_vote_shuapiao';
    public $table_shuapiaolog = 'fm_photosvote_vote_shuapiaolog';
    public $table_bbsreply = 'fm_photosvote_bbsreply';
    public $table_banners = 'fm_photosvote_banners';
    public $table_advs = 'fm_photosvote_advs';
    public $table_gift = 'fm_photosvote_gift';
    public $table_data = 'fm_photosvote_data';
    public $table_iplist = 'fm_photosvote_iplist';
    public $table_iplistlog = 'fm_photosvote_iplistlog';
    public $table_announce = 'fm_photosvote_announce';
    public $table_templates = 'fm_photosvote_templates';
    public $table_designer = 'fm_photosvote_templates_designer';
    public $table_designer_menu = 'fm_photosvote_templates_designer_menu';
    public $table_order = 'fm_photosvote_order';
    public $table_jifen = 'fm_photosvote_jifen';
    public $table_jifen_gift = 'fm_photosvote_jifen_gift';
    public $table_user_gift = 'fm_photosvote_user_gift';
    public $table_user_zsgift = 'fm_photosvote_user_zsgift';
    public $table_msg = 'fm_photosvote_message';
    public $table_orderlog = 'fm_photosvote_orderlog';
    public $table_counter = 'fm_photosvote_counter';
    public $table_qrcode = 'fm_photosvote_qrcode';
    public $table_answer = 'fm_photosvote_answer';
    public $table_source = 'fm_photosvote_source';
    public $table_school = 'fm_photosvote_school';
    public function __construct()
    {
        global $_W, $_GPC;
    }
    public function payResult($params)
    {
        global $_W;
        $item    = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE ordersn='{$params['tid']}' limit 1");
        $uniacid = !empty($item['uniacid']) ? $item['uniacid'] : $_W['uniacid'];
        $fee     = intval($params['fee']);
        $data    = array(
            'status' => $params['result'] == 'success' ? 1 : 0
        );
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
        }
        if ($params['type'] == 'delivery') {
            $data['status'] = 1;
        }
        $data['paytime'] = time();
        if (empty($item['payyz'])) {
            $data['payyz'] = random(8);
        }
        $data['ispayvote'] = 3;
        pdo_update($this->table_order, $data, array(
            'id' => $item['id']
        ));
        if ($item['paytype'] == 3) {
            pdo_update($this->table_users, array(
                'ordersn' => $params['tid']
            ), array(
                'rid' => $item['rid'],
                'from_user' => $item['from_user']
            ));
            if ($_W['account']['level'] == 4) {
                $this->sendMobileRegMsg($item['from_user'], $item['rid'], $uniacid);
            }
        }
        if ($params['from'] == 'return') {
            if ($item['paytype'] < 6) {
                $paydata = array(
                    'paystatus' => 'success',
                    'vote' => '1',
                    'votepay' => 1,
                    'vote_times' => $item['vote_times'],
                    'ordersn' => $params['tid'],
                    'tfrom_user' => $item['tfrom_user'],
                    'payyz' => !empty($data['payyz']) ? $data['payyz'] : $item['payyz']
                );
                $paymore = base64_encode(base64_encode(iserializer($paydata)));
                if ($item['paytype'] == 3) {
                    $payurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('tuser', array(
                        'i' => $uniacid,
                        'rid' => $item['rid'],
                        'tfrom_user' => $item['from_user'],
                        'paymore' => $paymore
                    ));
                } else {
                    $payurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('photosvote', array(
                        'i' => $uniacid,
                        'rid' => $item['rid'],
                        'paymore' => $paymore
                    ));
                }
                header("location:$payurl");
                exit();
            } else {
                if ($item['paytype'] == 6) {
                    if (!empty($item['giftid'])) {
                        $gmgift = 'gmgift';
                    }
                    $payurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('chargeend', array(
                        'i' => $uniacid,
                        'rid' => $item['rid'],
                        'from_user' => $item['from_user'],
                        'jifen' => $item['jifen'],
                        'payyz' => $item['payyz'],
                        'ordersn' => $params['tid'],
                        'type' => $gmgift
                    ));
                    header("location:$payurl");
                    exit();
                }
            }
        }
    }
    public function FM_checkoauth()
    {
        global $_GPC, $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        load()->model('mc');
        $openid   = '';
        $nickname = '';
        $avatar   = '';
        $follow   = '';
        if (!empty($_W['member']['uid'])) {
            $member = mc_fetch(intval($_W['member']['uid']), array(
                'avatar',
                'nickname'
            ));
            if (!empty($member)) {
                $avatar   = $member['avatar'];
                $nickname = $member['nickname'];
            }
        }
        if (empty($avatar) || empty($nickname)) {
            $fan = mc_fansinfo($_W['openid']);
            if (!empty($fan)) {
                $avatar   = $fan['avatar'];
                $nickname = $fan['nickname'];
                $openid   = $fan['openid'];
                $follow   = $fan['follow'];
            }
        }
        if (empty($avatar) || empty($nickname) || empty($openid) || empty($follow)) {
            $userinfo = mc_oauth_userinfo();
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['avatar'])) {
                $avatar = $userinfo['avatar'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['nickname'])) {
                $nickname = $userinfo['nickname'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['openid'])) {
                $openid = $userinfo['openid'];
            }
            if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['follow'])) {
                $follow = $userinfo['follow'];
            }
        }
        if ((empty($avatar) || empty($nickname)) && !empty($_W['member']['uid'])) {
        }
        $oauthuser              = array();
        $oauthuser['avatar']    = $avatar;
        $oauthuser['nickname']  = $nickname;
        $oauthuser['from_user'] = $openid;
        $oauthuser['follow']    = !empty($follow) ? $follow : $_W['fans']['follow'];
        return $oauthuser;
    }
    public function gettoken()
    {
        if ($_W['account']['level'] == 4) {
            $token = WeAccount::token();
        } else {
            $cfg = $this->module['config'];
            load()->classs('weixin.account');
            $accObj = WeixinAccount::create($cfg['u_uniacid']);
            $token  = $accObj->fetch_token();
        }
        return $token;
    }
    public function getAuthFm()
    {
        global $_GPC, $_W;
        load()->func('communication');
    }
    public function doMobileoauth2()
    {
        global $_GPC, $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $rid     = $_GPC['rid'];
        load()->func('communication');
        $fromuser = $_GPC['fromuser'];
        $cfg      = $this->module['config'];
        if ($_W['account']['level'] == 4) {
            $appid  = $_W['account']['key'];
            $secret = $_W['account']['secret'];
        } else {
            $appid  = $cfg['appid'];
            $secret = $cfg['secret'];
        }
        if ($_GPC['code'] == "authdeny") {
            $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('oauth2shouquan', array(
                'rid' => $rid
            ));
            header("location:$url");
            exit;
        }
        if (isset($_GPC['code'])) {
            $code        = $_GPC['code'];
            $grant_type  = 'authorization_code';
            $oauth2_code = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=' . $grant_type . '';
            $content     = ihttp_get($oauth2_code);
            $token       = @json_decode($content['content'], true);
            if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
                echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                exit;
            }
            $openid       = $token['openid'];
            $access_token = $token['access_token'];
            if ($cfg['oauthtype'] == 2) {
                $unionid = $token['unionid'];
                setcookie("user_oauth2_unionid", $unionid, time() + 3600 * 24 * 7);
                $realopenid = pdo_fetch("SELECT openid,follow FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = '{$uniacid}' AND `unionid` = '{$unionid}'");
                if (!empty($realopenid)) {
                    $openid = $realopenid['openid'];
                    $follow = $realopenid['follow'];
                    setcookie("user_oauth2_follow", $follow, time() + 3600 * 24 * 7);
                    setcookie("user_oauth2_unionid", $unionid, time() + 3600 * 24 * 7);
                }
            }
            if ($cfg['oauth_scope'] == 1) {
                $oauth2_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                $content    = ihttp_get($oauth2_url);
                $info       = @json_decode($content['content'], true);
                $follow     = $info['subscribe'];
                $nickname   = $info['nickname'];
                $avatar     = $info['headimgurl'];
                $sex        = $info['sex'];
            } else {
                $oauth2_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                $content    = ihttp_get($oauth2_url);
                $info       = @json_decode($content['content'], true);
                if (empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])) {
                    echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                    exit;
                }
                $avatar   = $info['headimgurl'];
                $nickname = $info['nickname'];
                $sex      = $info['sex'];
            }
            setcookie("user_oauth2_avatar", $avatar, time() + 3600 * 24 * 7);
            setcookie("user_oauth2_nickname", $nickname, time() + 3600 * 24 * 7);
            setcookie("user_oauth2_sex", $sex, time() + 3600 * 24 * 7);
            setcookie("user_oauth2_openid", $openid, time() + 3600 * 24 * 7);
            if (!empty($fromuser) && !isset($_COOKIE["user_fromuser_openid"])) {
                setcookie("user_fromuser_openid", $fromuser, time() + 3600 * 24 * 7);
            }
            if ($fromuser && $_GPC['duli']) {
                $photosvoteviewurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('shareuserdata', array(
                    'rid' => $rid,
                    'fromuser' => $fromuser,
                    'duli' => $_GPC['duli'],
                    'tfrom_user' => $_GPC['tfrom_user']
                ));
            } else {
                $photosvoteviewurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('photosvote', array(
                    'rid' => $rid,
                    'from_user' => $openid
                ));
            }
            header("location:$photosvoteviewurl");
            exit;
        } else {
            echo '<h1>不是高级认证号或网页授权域名设置出错!</h1>';
            exit;
        }
    }
    public function doMobileoauth2shouquan()
    {
        global $_GPC, $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];
        $rid     = $_GPC['rid'];
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT shareurl FROM " . tablename($this->table_share) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
            $url   = $reply['shareurl'];
            header("location:$url");
            exit;
        }
    }
    public function checkoauth2($rid, $oauthopenid, $oauthunionid = '', $fromuser = '', $duli = '')
    {
        global $_W;
        $cfg = $this->module['config'];
        if ($_W['account']['level'] == 4) {
            $appid = $_W['account']['key'];
        } else {
            $appid = $cfg['appid'];
        }
        if ($cfg['oauth_scope'] == 1) {
            $oauth_scope = 'snsapi_base';
        } else {
            $oauth_scope = 'snsapi_userinfo';
        }
        if (!empty($appid)) {
            $url         = $_W['siteroot'] . 'app/' . $this->createMobileUrl('oauth2', array(
                'rid' => $rid,
                'fromuser' => $fromuser,
                'duli' => $duli
            ));
            $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=" . $oauth_scope . "&state=0#wechat_redirect";
            header("location:$oauth2_code");
            exit;
        } else {
            $reguser = $_W['siteroot'] . 'app/' . $this->createMobileUrl('reguser', array(
                'rid' => $rid
            ));
            header("location:$reguser");
            exit;
        }
    }
    public function GetOauth($do, $uniacid, $fromuser, $rid, $gfrom_user = '', $duli)
    {
        global $_W;
        $cfg      = $this->module['config'];
        $rdisplay = pdo_fetch("SELECT hits,xunips FROM " . tablename($this->table_reply_display) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        pdo_update($this->table_reply_display, array(
            'hits' => $rdisplay['hits'] + 1
        ), array(
            'rid' => $rid
        ));
        $is_weixin = $this->is_weixin();
        if ($is_weixin == false && $do != 'stopllq') {
            $stopllq = $_W['siteroot'] . 'app/' . $this->createMobileUrl('stopllq', array(
                'rid' => $rid
            ));
            header("location:$stopllq");
            exit;
        }
        if ($_COOKIE["user_tbsj_time"] < mktime(0, 0, 0)) {
            $tbsj = pdo_fetch("SELECT SUM(photosnum) AS t1,SUM(xnphotosnum) AS t4,SUM(unphotosnum) AS t2, COUNT(1) AS t3 FROM " . tablename($this->table_users) . " WHERE rid = :rid AND status = 1", array(
                ':rid' => $rid
            ));
            pdo_update($this->table_reply_display, array(
                'ljtp_total' => $tbsj['t1'],
                'xunips' => $tbsj['t4'],
                'unphotosnum' => $tbsj['t2'],
                'csrs_total' => $tbsj['t3']
            ), array(
                'rid' => $rid
            ));
            setcookie("user_tbsj_time", time(), time() + 3600 * 24);
        }
        if (empty($_COOKIE["user_lptb_time"])) {
            $this->updatelp($rid);
        }
        if ($cfg['oauthtype'] == 3) {
            $from_user = !empty($_W['openid']) ? $_W['openid'] : $_W['fans']['from_user'];
            if (empty($_COOKIE["user_oauth2_gfollow"]) || empty($_COOKIE["user_oauth2_gfrom_user"])) {
                if (!empty($from_user)) {
                    if (!empty($gfrom_user)) {
                        $follow = 1;
                    } else {
                        $follow = $_W['fans']['follow'];
                    }
                    setcookie("user_oauth2_gfollow", $follow, time() + 3600 * 24);
                    setcookie("user_oauth2_gfrom_user", $from_user, time() + 3600 * 24);
                } else {
                    if (!empty($gfrom_user)) {
                        $from_user = $gfrom_user;
                        $follow    = 1;
                        setcookie("user_oauth2_gfollow", $follow, time() + 3600 * 24);
                        setcookie("user_oauth2_gfrom_user", $from_user, time() + 3600 * 24);
                    }
                }
            }
            $from_user = empty($_COOKIE["user_oauth2_gfrom_user"]) ? $from_user : $_COOKIE["user_oauth2_gfrom_user"];
            $follow    = empty($_COOKIE["user_oauth2_gfollow"]) ? $follow : $_COOKIE["user_oauth2_gfollow"];
            $nickname  = !empty($_W['fans']["nickname"]) ? $_W['fans']["nickname"] : $_W['fans']['tag']['nickname'];
            $avatar    = $_W['fans']['tag']['avatar'];
            $sex       = $_W['fans']['tag']['sex'];
            if ($do != 'shareuserview' && $do != 'shareuserdata' && $do != 'treg' && $do != 'tregs' && $do != 'tvotestart' && $do != 'tbbs' && $do != 'tbbsreply' && $do != 'saverecord' && $do != 'subscribeshare' && $do != 'pagedata' && $do != 'pagedatab' && $do != 'listentry' && $do != 'code' && $do != 'reguser' && $do != 'phdata' && $do != 'stopllq') {
                $sharedata = pdo_fetch("SELECT * FROM " . tablename($this->table_data) . " WHERE fromuser = :fromuser and rid = :rid and from_user = :from_user", array(
                    ':fromuser' => $fromuser,
                    ':from_user' => $from_user,
                    ':rid' => $rid
                ));
                if (empty($sharedata) && !empty($from_user) && !empty($fromuser)) {
                    if (!empty($fromuser) && !isset($_COOKIE["user_fromuser_openid"])) {
                        setcookie("user_fromuser_openid", $fromuser, time() + 3600 * 24 * 7);
                    }
                    $tfrom_user    = $_COOKIE["user_tfrom_user_openid"];
                    $shareuserdata = $_W['siteroot'] . 'app/' . $this->createMobileUrl('shareuserdata', array(
                        'rid' => $rid,
                        'fromuser' => $fromuser,
                        'duli' => $duli,
                        'tfrom_user' => $tfrom_user
                    ));
                    header("location:$shareuserdata");
                    exit;
                }
            }
        } else {
            if ($cfg['oauthtype'] == 1) {
                if ($do != 'shareuserview' && $do != 'shareuserdata' && $do != 'treg' && $do != 'tregs' && $do != 'tvotestart' && $do != 'tbbs' && $do != 'tbbsreply' && $do != 'saverecord' && $do != 'saverecord1' && $do != 'subscribeshare' && $do != 'pagedata' && $do != 'listentry' && $do != 'code' && $do != 'reguser' && $do != 'phdata' && $do != 'stopllq') {
                    $oauthuser = $this->FM_checkoauth();
                }
                $from_user = empty($oauthuser['from_user']) ? $_GPC['from_user'] : $oauthuser['from_user'];
                $avatar    = $oauthuser['avatar'];
                $nickname  = $oauthuser['nickname'];
                $follow    = $oauthuser['follow'];
            } else {
                $from_user = $_COOKIE["user_oauth2_openid"];
                if ($_W['openid'] == 'FMfromUser' || $_W['openid'] == 'FMfromUserasss') {
                    $from_user = $_W['openid'];
                }
                if ($cfg['oauthtype'] == 2) {
                    $rvote   = pdo_fetch("SELECT unimoshi FROM " . tablename($this->table_reply_vote) . " WHERE rid = :rid ORDER BY `id` DESC", array(
                        ':rid' => $rid
                    ));
                    $unionid = $_COOKIE["user_oauth2_unionid"];
                    $f       = pdo_fetch("SELECT follow FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = $uniacid AND unionid = :unionid ", array(
                        ':unionid' => $unionid
                    ));
                    $follow  = $f['follow'];
                    if ($rvote['unimoshi'] == 1) {
                        if (!empty($unionid)) {
                            $user = pdo_fetch("SELECT from_user FROM " . tablename($this->table_users) . " WHERE unionid = :unionid AND rid = $rid", array(
                                ':unionid' => $unionid
                            ));
                            if (!empty($user)) {
                                $from_user = $user['from_user'];
                            }
                        }
                    }
                    if (!empty($gfrom_user)) {
                        $follow = 1;
                    }
                    if ($from_user == 'FMfromUser') {
                    } else {
                        if (empty($_COOKIE["user_oauth2_openid"]) || empty($unionid)) {
                            if ($do != 'shareuserview' && $do != 'shareuserdata' && $do != 'treg' && $do != 'tregs' && $do != 'tvotestart' && $do != 'tbbs' && $do != 'tbbsreply' && $do != 'saverecord' && $do != 'subscribeshare' && $do != 'pagedata' && $do != 'pagedatab' && $do != 'listentry' && $do != 'code' && $do != 'reguser' && $do != 'phdata' && $do != 'stopllq') {
                                $this->checkoauth2($rid, $_COOKIE["user_oauth2_openid"], $unionid, $fromuser, $duli);
                            }
                        }
                    }
                } else {
                    if (!empty($gfrom_user)) {
                        $follow = 1;
                    }
                    if ($from_user == 'FMfromUser') {
                    } else {
                        if (empty($_COOKIE["user_oauth2_openid"])) {
                            if ($do != 'shareuserview' && $do != 'shareuserdata' && $do != 'treg' && $do != 'tregs' && $do != 'tvotestart' && $do != 'tbbs' && $do != 'tbbsreply' && $do != 'saverecord' && $do != 'subscribeshare' && $do != 'pagedata' && $do != 'pagedatab' && $do != 'listentry' && $do != 'code' && $do != 'reguser' && $do != 'phdata' && $do != 'stopllq') {
                                $this->checkoauth2($rid, $_COOKIE["user_oauth2_openid"], '', $fromuser, $duli);
                            }
                        }
                    }
                }
                $follow   = empty($follow) ? $_W['fans']['follow'] : $follow;
                $avatar   = !empty($_COOKIE["user_oauth2_avatar"]) ? $_COOKIE["user_oauth2_avatar"] : $_W['fans']['tag']['avatar'];
                $nickname = !empty($_COOKIE["user_oauth2_nickname"]) ? $_COOKIE["user_oauth2_nickname"] : $_W['fans']['tag']['nickname'];
                $sex      = !empty($_COOKIE["user_oauth2_sex"]) ? $_COOKIE["user_oauth2_sex"] : $_W['fans']['tag']['sex'];
            }
        }
        $usersinfo = array(
            'from_user' => $from_user,
            'unionid' => $unionid,
            'nickname' => $nickname,
            'follow' => $follow,
            'avatar' => $avatar,
            'sex' => $sex
        );
        if (!empty($from_user) && $do != 'stopllq') {
            $this->createvoteer($rid, $uniacid, $from_user, $nickname, $avatar, $sex);
        }
        return $usersinfo;
    }
    public function GetReply($rid, $uniacid)
    {
        $replyarr           = array();
        $replyarr['rbasic'] = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE (find_in_set(" . $uniacid . ", binduniacid) OR uniacid = " . $uniacid . ") AND rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if (empty($replyarr['rbasic'])) {
            if ($_GPC['do'] != 'stopllq') {
                $info      = '没有发现此活动！';
                $urlstatus = $_W['siteroot'] . 'app/' . $this->createMobileUrl('stopllq', array(
                    'rid' => $rid,
                    'info' => $info
                ));
                header("location:$urlstatus");
                die();
            }
        }
        $replyarr['rshare']   = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_share) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $replyarr['rhuihua']  = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_huihua) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $replyarr['rdisplay'] = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_display) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $replyarr['rvote']    = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_vote) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $replyarr['rbody']    = pdo_fetch("SELECT * FROM " . tablename($this->table_reply_body) . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $replyarr['qiniu']    = iunserializer($replyarr['rbasic']['qiniu']);
        return $replyarr;
    }
    public function uploadFile($file, $filetempname, $rid)
    {
        global $_W;
        load()->func('file');
		if (empty($file)) {
			message('没有内容！');
		}
        $filePath = IA_ROOT . '/addons/fm_photosvote/tmp/' . date('Ymd', time()) . '/';
        if (!is_dir($filePath)) {
            mkdirs($filePath, "0777");
        }
        $str = "";
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Reader/Excel5.php';
        $time       = date("Y-m-d-H-i-s");
        $extend     = strrchr($file, '.');
		$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');
		$extend = strtolower($extend);
		$allowExt = array('xls');
		if (!in_array(strtolower($extend), $allowExt) || in_array(strtolower($extend), $harmtype)) {
			message('不允许上传此类文件,只运行xls文件！');
		}
        $name       = $_W['uniacid'] . $time . $extend;
        $uploadfile = $filePath . $name;
        $result     = move_uploaded_file($filetempname, $uploadfile);
        if ($result) {
            $objReader          = PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel        = $objReader->load($uploadfile);
            $sheet              = $objPHPExcel->getSheet(0);
            $highestRow         = $sheet->getHighestRow();
            $highestColumn      = $sheet->getHighestColumn();
            $objWorksheet       = $objPHPExcel->getActiveSheet();
            $highestRow         = $objWorksheet->getHighestRow();
            $highestColumn      = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $headtitle          = array();
            $uniacid            = $_W['uniacid'];
            for ($row = 1; $row <= $highestRow; $row++) {
                $strs = array();
                for ($col = 0; $col < $highestColumnIndex; $col++) {
                    $strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }
                $uid       = $strs['0'];
                $photosarr = explode(',', $strs['19']);
                if (is_numeric($uid)) {
                    $users = pdo_fetch("SELECT id FROM " . tablename($this->table_users) . " WHERE rid = '{$rid}' AND uid = '{$uid}' LIMIT 1 ");
                    if (empty($users)) {
                        if (!empty($strs['10'])) {
                            $tagpid = pdo_fetch("SELECT id FROM " . tablename($this->table_tags) . " WHERE rid = '{$rid}' AND title = :title LIMIT 1 ", array(
                                ':title' => $strs['10']
                            ));
                            $pid    = $tagpid['id'];
                            if (empty($tagpid)) {
                                pdo_insert($this->table_tags, array(
                                    'rid' => $rid,
                                    'uniacid' => $uniacid,
                                    'title' => $strs['10'],
                                    'icon' => '1'
                                ));
                                $pid = pdo_insertid();
                            }
                        }
                        if (!empty($strs['11'])) {
                            $tagid = pdo_fetch("SELECT id FROM " . tablename($this->table_tags) . " WHERE rid = '{$rid}' AND title = :title LIMIT 1 ", array(
                                ':title' => $strs['11']
                            ));
                            $cid   = $tagid['id'];
                            if (empty($tagpid)) {
                                pdo_insert($this->table_tags, array(
                                    'rid' => $rid,
                                    'uniacid' => $uniacid,
                                    'title' => $strs['11'],
                                    'parentid' => $pid,
                                    'icon' => '2'
                                ));
                                $cid = pdo_insertid();
                            }
                        }
                        $from_user = 'FM' . random(16) . time();
                        $data      = array(
                            'rid' => $rid,
                            'uniacid' => $uniacid,
                            'uid' => $uid,
                            'from_user' => $from_user,
                            'nickname' => $strs['1'],
                            'realname' => $strs['2'],
                            'photosnum' => $strs['3'],
                            'xnphotosnum' => $strs['4'],
                            'hits' => $strs['5'],
                            'xnhits' => $strs['6'],
                            'sharenum' => $strs['7'],
                            'zans' => $strs['8'],
                            'sex' => $strs['9'],
                            'tagid' => $cid,
                            'tagpid' => $pid,
                            'mobile' => $strs['12'],
                            'weixin' => $strs['13'],
                            'qqhao' => $strs['14'],
                            'email' => $strs['15'],
                            'address' => $strs['16'],
                            'photoname' => $strs['17'],
                            'description' => $strs['18'],
                            'music' => $strs['20'],
                            'vedio' => $strs['21'],
                            'status' => $strs['22'],
                            'createip' => getip(),
                            'lasttime' => time(),
                            'createtime' => time()
                        );
                        if (!empty($photosarr)) {
                            foreach ($photosarr as $key => $value) {
                                if ($key == 0) {
                                    pdo_insert($this->table_users_picarr, array(
                                        'rid' => $rid,
                                        'uniacid' => $uniacid,
                                        'from_user' => $from_user,
                                        'photos' => $value,
                                        'isfm' => 1,
                                        'createtime' => time()
                                    ));
                                    $data['avatar'] = $value;
                                } else {
                                    pdo_insert($this->table_users_picarr, array(
                                        'rid' => $rid,
                                        'uniacid' => $uniacid,
                                        'from_user' => $from_user,
                                        'photos' => $value,
                                        'createtime' => time()
                                    ));
                                }
                            }
                        }
                        $user = pdo_insert($this->table_users, $data);
                        if ($user) {
                            $msg = '导入成功！';
                        } else {
                            return false;
                            $msg = '导入失败！';
                        }
                    }
                }
            }
        } else {
            $msg = "导入失败！";
        }
        return $msg;
    }
    public function uploadFile_tags($file, $filetempname, $rid)
    {
        global $_W;
        load()->func('file');
		if (empty($file)) {
			message('没有选择文件！');
		}
        $filePath = IA_ROOT . '/addons/fm_photosvote/tmp/' . date('Ymd', time()) . '/';
        if (!is_dir($filePath)) {
            mkdirs($filePath, "0777");
        }
        $str = "";
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Reader/Excel5.php';
        $time       = date("Y-m-d-H-i-s");
        $extend     = strrchr($file, '.');
		$harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');
		$extend = strtolower($extend);
		$allowExt = array('xls');
		if (!in_array(strtolower($extend), $allowExt) || in_array(strtolower($extend), $harmtype)) {
			message('不允许上传此类文件,只运行xls文件！');
		}
        $name       = 'tags_' . $_W['uniacid'] . $time . $extend;
        $uploadfile = $filePath . $name;
        $result     = move_uploaded_file($filetempname, $uploadfile);
        if ($result) {
            $objReader          = PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel        = $objReader->load($uploadfile);
            $sheet              = $objPHPExcel->getSheet(0);
            $highestRow         = $sheet->getHighestRow();
            $highestColumn      = $sheet->getHighestColumn();
            $objWorksheet       = $objPHPExcel->getActiveSheet();
            $highestRow         = $objWorksheet->getHighestRow();
            $highestColumn      = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $headtitle          = array();
            $uniacid            = $_W['uniacid'];
            for ($row = 1; $row <= $highestRow; $row++) {
                $strs = array();
                for ($col = 0; $col < $highestColumnIndex; $col++) {
                    $strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }
                $displayorder = $strs['0'];
                if (is_numeric($displayorder)) {
                    $title = $strs['1'];
                    if (empty($title)) {
                        $title = $strs['2'];
                        if (empty($title)) {
                            $title    = $strs['3'];
                            $tag      = pdo_fetch("SELECT id FROM " . tablename($this->table_tags) . " WHERE rid = '{$rid}' AND icon = 2 ORDER BY parentid DESC, id DESC LIMIT 1 ");
                            $parentid = $tag['id'];
                            $icon     = '3';
                        } else {
                            $tag      = pdo_fetch("SELECT id FROM " . tablename($this->table_tags) . " WHERE rid = '{$rid}' AND icon = 1 ORDER BY id DESC LIMIT 1 ");
                            $parentid = $tag['id'];
                            $icon     = '2';
                        }
                    } else {
                        $parentid = '';
                        $icon     = '1';
                    }
                    $tags = pdo_fetch("SELECT id FROM " . tablename($this->table_tags) . " WHERE rid = '{$rid}' AND title = '{$title}' AND icon < 3 LIMIT 1 ");
                    if (empty($tags)) {
                        $data = array(
                            'rid' => $rid,
                            'uniacid' => $uniacid,
                            'displayorder' => $displayorder,
                            'title' => $title,
                            'parentid' => $parentid,
                            'icon' => $icon,
                            'createtime' => time()
                        );
                        pdo_insert($this->table_tags, $data);
                    } else {
                        $msg = '分组名称重复！';
                    }
                }
            }
        } else {
            $msg = "导入失败！";
        }
        return $msg;
    }
    public function stopip($rid, $uniacid, $from_user, $mineip, $do, $ipturl = '0', $limitip = '2')
    {
        $starttime = mktime(0, 0, 0);
        $endtime   = mktime(23, 59, 59);
        $times     = '';
        $times .= ' AND createtime >=' . $starttime;
        $times .= ' AND createtime <=' . $endtime;
        $iplist  = pdo_fetchall('SELECT * FROM ' . tablename($this->table_iplist) . ' WHERE rid= :rid order by `createtime` desc ', array(
            ':rid' => $rid
        ));
        $totalip = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_log) . ' WHERE rid= :rid AND ip = :ip ' . $times . '  order by `ip` desc ', array(
            ':rid' => $rid,
            ':ip' => $mineip
        ));
        if ($totalip > $limitip && $ipturl == 1) {
            $ipurl = $_W['siteroot'] . $this->createMobileUrl('stopip', array(
                'from_user' => $from_user,
                'rid' => $rid
            ));
            header("location:$ipurl");
            exit();
        }
        $totalip = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_log) . ' WHERE rid= :rid AND ip = :ip  ' . $times . ' order by `ip` desc ', array(
            ':rid' => $rid,
            ':ip' => $mineip
        ));
        $mineipz = sprintf("%u", ip2long($mineip));
        foreach ($iplist as $i) {
            $iparrs  = iunserializer($i['iparr']);
            $ipstart = sprintf("%u", ip2long($iparrs['ipstart']));
            $ipend   = sprintf("%u", ip2long($iparrs['ipend']));
            if ($mineipz >= $ipstart && $mineipz <= $ipend) {
                $ipdate          = array(
                    'rid' => $rid,
                    'uniacid' => $uniacid,
                    'avatar' => $avatar,
                    'nickname' => $nickname,
                    'from_user' => $from_user,
                    'ip' => $mineip,
                    'hitym' => $do,
                    'createtime' => time()
                );
                $ipdate['iparr'] = getiparr($ipdate['ip']);
                pdo_insert($this->table_iplistlog, $ipdate);
                if ($ipturl == 1) {
                    $ipurl = $_W['siteroot'] . $this->createMobileUrl('stopip', array(
                        'from_user' => $from_user,
                        'rid' => $rid
                    ));
                    header("location:$ipurl");
                    exit();
                }
                break;
            }
        }
    }
    public function WaterPoster($saveurl, $groundimage, $markimage, $markminwidth, $markminheight, $markwhere = '0', $markwheret = '0', $marktext = '012wz.com', $fontfamily = '黑体', $fontsize = '15', $fontcolor = "#000", $marktype = 0, $marktypet = 0)
    {
        if (($marktype == 0)) {
            $markimage_info   = getimagesize($markimage);
            $markimage_width  = $markimage_info[0];
            $markimage_height = $markimage_info[1];
            switch ($markimage_info[2]) {
                case 1:
                    $from_markimage = imagecreatefromgif($markimage);
                    break;
                case 2:
                    $from_markimage = imagecreatefromjpeg($markimage);
                    break;
                case 3:
                    $from_markimage = imagecreatefrompng($markimage);
                    break;
                case 4:
                    $from_markimage = imagecreatefromwbmp($markimage);
                    break;
                default:
                    break;
            }
        }
        if (!empty($groundimage)) {
            $groundimage_info   = @getimagesize($groundimage);
            $groundimage_width  = $groundimage_info[0];
            $groundimage_height = $groundimage_info[1];
            switch ($groundimage_info[2]) {
                case 1:
                    $from_groundimage = imagecreatefromgif($groundimage);
                    break;
                case 2:
                    $from_groundimage = imagecreatefromjpeg($groundimage);
                    break;
                case 3:
                    $from_groundimage = imagecreatefrompng($groundimage);
                    break;
                case 4:
                    $from_groundimage = imagecreatefromwbmp($groundimage);
                    break;
                default:
                    break;
            }
        }
        if ($groundimage_width >= $markminwidth && $groundimage_height >= $markminheight) {
            if ($marktype == 0) {
                $markwidth  = $markimage_width;
                $markheight = $markimage_height;
            }
            if ($marktypet == 0) {
                $temp            = @imagettfbbox($fontsize, 0, $fontfamily, $marktext);
                $text_markwidth  = $temp[2] - $temp[6];
                $text_markheight = $temp[3] - $temp[7];
                unset($temp);
            }
            imagealphablending($from_groundimage, true);
            if ($marktype == 0) {
                switch ($markwhere) {
                    case 0:
                        $pos_x = rand(0, ($groundimage_width - $markwidth - 10));
                        $pos_y = rand(0, ($groundimage_height - $markheight - 10));
                        break;
                    case 1:
                        $pos_x = 10;
                        $pos_y = 10;
                        break;
                    case 2:
                        $pos_x = ceil(($groundimage_width - $markwidth) / 2);
                        $pos_y = 10;
                        break;
                    case 3:
                        $pos_x = ceil($groundimage_width - $markwidth - 10);
                        $pos_y = 10;
                        break;
                    case 4:
                        $pos_x = 10;
                        $pos_y = ceil(($groundimage_height - $markheight) / 2);
                        break;
                    case 5:
                        $pos_x = ceil(($groundimage_width - $markwidth) / 2);
                        $pos_y = ceil(($groundimage_height - $markheight) / 2);
                        break;
                    case 6:
                        $pos_x = ceil($groundimage_width - $markwidth - 10);
                        $pos_y = ceil(($groundimage_height - $markheight) / 2);
                        break;
                    case 7:
                        $pos_x = 10;
                        $pos_y = ceil($groundimage_height - $markheight);
                        break;
                    case 8:
                        $pos_x = ceil(($groundimage_width - $markwidth) / 2);
                        $pos_y = ceil($groundimage_height - $markheight - 10);
                        break;
                    case 9:
                        $pos_x = ceil($groundimage_width - $markwidth - 10);
                        $pos_y = ceil($groundimage_height - $markheight - 10);
                        break;
                    default:
                        $pos_x = rand(0, ($groundimage_width - $markwidth - 10));
                        $pos_y = rand(0, ($groundimage_height - $markheight - 10));
                        break;
                }
                imagecopy($from_groundimage, $from_markimage, $pos_x, $pos_y, 0, 0, $markimage_width, $markimage_height);
            }
            if ($marktypet == 0) {
                switch ($markwheret) {
                    case 0:
                        $pos_x_t = mt_rand(10, ($groundimage_width - $text_markwidth - 10));
                        $pos_y_t = mt_rand(10, ($groundimage_height - $text_markheight - 10));
                        break;
                    case 1:
                        $pos_x_t = 10;
                        $pos_y_t = 30;
                        break;
                    case 2:
                        $pos_x_t = ($groundimage_width / 2) - ($text_markwidth / 2);
                        $pos_y_t = 30;
                        break;
                    case 3:
                        $pos_x_t = $groundimage_width - $text_markwidth - 10;
                        $pos_y_t = 30;
                        break;
                    case 4:
                        $pos_x_t = 10;
                        $pos_y_t = ($groundimage_height / 2) - ($text_markheight / 2);
                        break;
                    case 5:
                        $pos_x_t = ($groundimage_width / 2) - ($text_markwidth / 2);
                        $pos_y_t = ($groundimage_height / 2) - ($text_markheight / 2);
                        break;
                    case 6:
                        $pos_x_t = $groundimage_width - $text_markwidth - 10;
                        $pos_y_t = ($groundimage_height / 2) - ($text_markheight / 2);
                        break;
                    case 7:
                        $pos_x_t = 10;
                        $pos_y_t = $groundimage_height - $text_markheight + 5;
                        break;
                    case 8:
                        $pos_x_t = ($groundimage_width / 2) - ($text_markwidth / 2);
                        $pos_y_t = $groundimage_height - $text_markheight + 5;
                        break;
                    case 9:
                        $pos_x_t = $groundimage_width - $text_markwidth - 10;
                        $pos_y_t = $groundimage_height - $text_markheight - 5;
                        break;
                    default:
                        $pos_x_t = mt_rand(10, ($groundimage_width - $text_markwidth - 10));
                        $pos_y_t = mt_rand(10, ($groundimage_height - $text_markheight - 10));
                        break;
                }
                if (!empty($fontcolor) && (strlen($fontcolor) == 7)) {
                    $R = hexdec(substr($fontcolor, 1, 2));
                    $G = hexdec(substr($fontcolor, 3, 2));
                    $B = hexdec(substr($fontcolor, 5));
                } else if (!empty($fontcolor) && (strlen($fontcolor) == 3)) {
                    $R = hexdec(substr($fontcolor, 1, 1));
                    $G = hexdec(substr($fontcolor, 2, 2));
                    $B = hexdec(substr($fontcolor, 3, 3));
                } else {
                    $R = '00';
                    $G = '00';
                    $B = '00';
                }
                imagettftext($from_groundimage, $fontsize, 0, $pos_x_t, $pos_y_t, imagecolorallocate($from_groundimage, $R, $G, $B), $fontfamily, $marktext);
            }
            switch ($groundimage_info[2]) {
                case 1:
                    imagegif($from_groundimage, $saveurl);
                    break;
                case 2:
                    imagejpeg($from_groundimage, $saveurl, 100);
                    break;
                case 3:
                    imagepng($from_groundimage, $saveurl);
                    break;
                case 4:
                    imagewbmp($from_groundimage, $saveurl);
                    break;
                default:
                    break;
            }
        }
        if (isset($markimage_info))
            unset($markimage_info);
        if (isset($groundimage_info))
            unset($groundimage_info);
        if (isset($from_markimage))
            imagedestroy($from_markimage);
        if (isset($from_groundimage))
            imagedestroy($from_groundimage);
    }
    function fmqnimages($nfilename, $qiniu, $mid, $username)
    {
        $fmurl      = 'http://demo.012wz.com/api/qiniu/api.php?';
        $hosts      = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $host       = base64_encode($hosts);
        $visitorsip = base64_encode(getip());
        $fmimages   = array(
            'nfilename' => $nfilename,
            'qiniu' => $qiniu,
            'mid' => $mid,
            'username' => $username
        );
        $fmimages   = base64_encode(base64_encode(iserializer($fmimages)));
        $fmpost     = $fmurl . 'host=' . $host . "&visitorsip=" . $visitorsip . "&webname=" . $webname . "&fmimages=" . $fmimages;
        load()->func('communication');
        $content = ihttp_get($fmpost);
        $fmmv    = @json_decode($content['content'], true);
        if ($mid == 0) {
            $fmdata = array(
                "success" => $fmmv['success'],
                "msg" => $fmmv['msg']
            );
            $fmdata['mid'] == 0;
            $fmdata['imgurl'] = $fmmv['imgurl'];
            return $fmdata;
            exit;
        } else {
            $fmdata                   = array(
                "success" => $fmmv['success'],
                "msg" => $fmmv['msg']
            );
            $fmdata['picarr_' . $mid] = $fmmv['picarr_' . $mid];
            return $fmdata;
            exit;
        }
    }
    function fmqnaudios($nfilename, $qiniu, $upmediatmp, $audiotype, $username)
    {
        $fmurl    = 'http://demo.012wz.com/api/qiniu/api.php?';
        $host     = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $host     = base64_encode($host);
        $clientip = base64_encode($_W['clientip']);
        $fmaudios = array(
            'nfilename' => $nfilename,
            'qiniu' => $qiniu,
            'upmediatmp' => $upmediatmp,
            'audiotype' => $audiotype,
            'username' => $username
        );
        $fmaudios = base64_encode(base64_encode(iserializer($fmaudios)));
        $fmpost   = $fmurl . 'host=' . $host . "&visitorsip=" . $clientip . "&fmaudios=" . $fmaudios;
        load()->func('communication');
        $content            = ihttp_get($fmpost);
        $fmmv               = @json_decode($content['content'], true);
        $fmdata             = array(
            "msg" => $fmmv['msg'],
            "success" => $fmmv['success'],
            "nfilenamefop" => $fmmv['nfilenamefop'],
            "ret" => $fmmv['ret']
        );
        $fmdata[$audiotype] = $fmmv[$audiotype];
        return $fmdata;
        exit();
    }
    public function is_weixin()
    {
        global $_W;
        if (empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false) {
            return true;
        }
        return true;
    }
}
?>