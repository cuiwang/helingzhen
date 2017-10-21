<?php
defined('IN_IA') or exit('Access Denied');
class Yg_parkourModuleSite extends WeModuleSite
{
    public $table_reply = 'yg_parkour_reply';
    public $table_info = 'yg_parkour_info';
    public $table_recomd = 'yg_parkour_recomd';
    public $table_user = 'yg_parkour_user';
    public $table_oauth = 'yg_parkour_oauth';
    public $userinfo;
    function __construct()
    {
        global $_W, $_GPC;
        $string = $_SERVER['REQUEST_URI'];
        if (strpos($string, 'app') == true) {
            $this->userinfo = $this->jboauth();
        }
    }
    public function doMobilemyranking()
    {
        global $_W, $_GPC;
        $uniacid  = $_W['uniacid'];
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $score    = $_GPC['score'];
        $id       = $_GPC['id'];
        $reply    = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $id
        ));
        $user     = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and openid='{$openid}' and rid ={$reply['rid']}");
        $userall  = pdo_fetchall("SELECT * FROM " . tablename($this->table_recomd) . " where uniacid={$uniacid} and rid ={$reply['rid']} and openid='{$openid}' ORDER BY asnum DESC LIMIT 0,10");
        $usernum  = pdo_fetchall("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and rid ={$reply['rid']} ORDER BY asnum DESC ");
        $i        = 0;
        $mingci   = 0;
        foreach ($usernum as $key => $value) {
            $i++;
            if ($openid == $value['openid']) {
                $mingci = $i;
                break;
            }
        }
        include $this->template('ranking');
    }
    public function doWebUsermge()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $pindex  = max(1, intval($_GPC['page']));
        $psize   = 5;
        if (!empty($_GPC['keyword'])) {
            $condition = " AND nickname LIKE '%{$_GPC['keyword']}%'";
        }
        $user      = pdo_fetchall("select * from " . tablename($this->table_user) . " where 1=1 and uniacid = {$uniacid} $condition ORDER BY asnum DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        $sql_count = "SELECT count(*) FROM " . tablename($this->table_user) . "where 1=1 and uniacid = {$uniacid} $condition";
        $total     = pdo_fetchcolumn($sql_count);
        $pager     = pagination($total, $pindex, $psize);
        include $this->template('wkuser');
    }
    public function checkact()
    {
        global $_W, $_GPC;
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $_GPC['id']
        ));
        if ($reply) {
            if ($reply['starttime'] > time()) {
                echo "本次活动尚未开始,敬请期待！";
                exit;
            } elseif ($reply['endtime'] < time() || $reply['status'] == 0) {
                echo "本次活动已经结束，请关注我们后续的活动！";
                exit;
            } elseif ($reply['status'] == 2) {
                echo "本次活动暂停中";
                exit;
            }
        }
    }
    public function doMobileranking()
    {
        global $_W, $_GPC;
        $uniacid  = $_W['uniacid'];
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $score    = $_GPC['score'];
        $id       = $_GPC['id'];
        $reply    = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $id
        ));
        $user     = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and openid='{$openid}' and rid ={$reply['rid']}");
        $userall  = pdo_fetchall("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and rid ={$reply['rid']} ORDER BY asnum desc LIMIT 0,10");
        $usernum  = pdo_fetchcolumn("SELECT count(*) FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and rid ={$reply['rid']}");
        $maxpage  = intval($usernum / 10 + 1);
        $usernuma = pdo_fetchall("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and rid ={$reply['rid']} ORDER BY asnum desc ");
        $i        = 0;
        $mingci   = 0;
        foreach ($usernuma as $key => $value) {
            $i++;
            if ($openid == $value['openid']) {
                $mingci = $i;
                break;
            }
        }
        include $this->template('ranking');
    }
    public function doMobilesaverecord()
    {
        global $_W, $_GPC;
        $uniacid  = $_W['uniacid'];
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $id       = $_GPC['id'];
        $reply    = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $id
        ));
        $score    = $_GPC['score'];
        $insert   = array(
            'uniacid' => $uniacid,
            'rid' => $reply['rid'],
            'openid' => $openid,
            'asnum' => $score,
            'headimgurl' => $userinfo['mk_headimgurl'],
            'nickname' => $userinfo['mk_nickname'],
            'time' => time()
        );
        $faly     = pdo_insert($this->table_recomd, $insert);
        $member   = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and openid='{$openid}' and rid ={$reply['rid']}");
        if (empty($member)) {
            $data = array(
                'uniacid' => $uniacid,
                'rid' => $reply['rid'],
                'openid' => $openid,
                'asnum' => $score,
                'headimgurl' => $userinfo['mk_headimgurl'],
                'nickname' => $userinfo['mk_nickname'],
                'time' => time(),
                'count' => $reply['pnum'],
                'comparetime' => strtotime(date("Y-m-d", time()))
            );
            $faly = pdo_insert($this->table_user, $data);
        } else {
            if ($score > $member['asnum']) {
                pdo_update($this->table_user, array(
                    'asnum' => $score
                ), array(
                    'id' => $member['id']
                ));
            }
        }
        if ($faly) {
            echo 2;
        } else {
            echo 1;
        }
    }
    public function doMobileindex()
    {
        global $_W, $_GPC;
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $share    = $_GPC['share'];
        $this->checkact();
        $id    = $_GPC['id'];
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $id
        ));
        if ($reply['isfolle'] == 0) {
            if ($share == 1) {
                header("location: " . $reply['follelink']);
                exit;
            }
        }
        $rid = $reply['rid'];
        include $this->template('index');
    }
    public function getOauthCode($data, $key)
    {
        global $_GPC, $_W;
        $forward = urlencode($data);
        $url     = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $key . '&redirect_uri=' . $forward . '&response_type=code&scope=snsapi_userinfo&wxref=mp.weixin.qq.com#wechat_redirect';
        header('location:' . $url);
    }
    public function jboauth()
    {
        global $_GPC, $_W;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
        }
        $serverapp = $_W['account']['level'];
        if ($serverapp == 4) {
            $appid  = $_W['account']['key'];
            $secret = $_W['account']['secret'];
        } else {
            $cfg    = pdo_fetch("select * from " . tablename($this->table_oauth) . " where 1=1 and weid={$_W['weid']}");
            $appid  = $cfg['appid'];
            $secret = $cfg['secret'];
        }
        $info = pdo_fetch("select * from " . tablename($this->table_info) . " where logoopenid = :logoopenid and uniacid =:uniacid", array(
            ':logoopenid' => $_W['openid'],
            ':uniacid' => $_W['uniacid']
        ));
        if (!empty($info)) {
            $user['mk_nickname']   = $info['nickname'];
            $user['mk_openid']     = $info['openid'];
            $user['mk_headimgurl'] = $info['headimgurl'];
        } else {
            $code = $_GPC['code'];
            if (empty($code)) {
                $url = $_W['siteroot'] . $_SERVER['REQUEST_URI'];
                $url = str_replace("//app", "/app", $url);
                $this->getOauthCode($url, $appid);
            } else {
                if (empty($code)) {
                    $url = $_W['siteroot'] . $_SERVER['REQUEST_URI'];
                    $this->getOauthCode($url);
                } else {
                    $key    = $appid;
                    $secret = $secret;
                    $url    = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $key . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
                    $data   = ihttp_get($url);
                    if ($data['code'] != 200) {
                        message('诶呦,网络异常..请稍后再试..');
                    }
                    $temp         = @json_decode($data['content'], true);
                    $access_token = $temp['access_token'];
                    $openid       = $temp['openid'];
                    $user_url     = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid;
                    $user_temp    = ihttp_get($user_url);
                    if ($user_temp['code'] != 200) {
                        message('诶呦,网络异常..请稍后再试..');
                    }
                    $user = @json_decode($user_temp['content'], true);
                    if (!empty($user['errocde']) || $user['errocde'] != 0) {
                        message(account_weixin_code($user['errcode']), '', 'error');
                    }
                    if (empty($fromuser)) {
                        $from_user = $openid;
                    }
                }
                $datainfo = array(
                    'uniacid' => $_W['uniacid'],
                    'logoopenid' => $_W['openid'],
                    'openid' => $user['openid'],
                    'nickname' => $user['nickname'],
                    'headimgurl' => $user['headimgurl']
                );
                if (empty($info)) {
                    pdo_insert($this->table_info, $datainfo);
                } else {
                    $wheredata = array(
                        'id' => $info['id']
                    );
                    pdo_update($this->table_info, $datainfo, $wheredata);
                }
            }
        }
        return $user;
    }
    public function doMobilesaveuserinfo()
    {
        global $_W, $_GPC;
        $uniacid   = $_W['uniacid'];
        $userinfo  = $this->userinfo;
        $openid    = $userinfo['mk_openid'];
        $true_name = $_GPC['true_name'];
        $tel       = $_GPC['phone'];
        $id        = $_GPC['id'];
        $reply     = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $id
        ));
        $member    = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and openid='{$openid}' and rid ={$reply['rid']}");
        if (!empty($member)) {
            pdo_update($this->table_user, array(
                'tel' => $tel,
                'realname' => $true_name
            ), array(
                'id' => $member['id']
            ));
            $data = array(
                'done' => "true"
            );
            echo json_encode($data);
        } else {
            $data = array(
                'done' => "false",
                'msg' => "你没有参加游戏"
            );
            echo json_encode($data);
        }
    }
    public function doMobilemored()
    {
        global $_W, $_GPC;
        $uniacid  = $_W['uniacid'];
        $userinfo = $this->userinfo;
        $openid   = $userinfo['mk_openid'];
        $id       = $_GPC['id'];
        $reply    = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(
            ':id' => $id
        ));
        $pindex   = max(1, intval($_GPC['page']));
        $psize    = 10;
        $userall  = pdo_fetchall("SELECT * FROM " . tablename($this->table_user) . " where uniacid={$uniacid} and rid ={$reply['rid']} ORDER BY asnum DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $html     = "";
        $i        = ($pindex - 1) * $psize;
        foreach ($userall as $key => $value) {
            $i++;
            $html .= "<li><div class='NO'>{$i}</div><div class='Head'><img src=" . $value['headimgurl'] . " width='100%'></div><div class='text'><div class='his'><h1>" . $value['nickname'] . "</h1></div><div class='gift'> <p>" . $value['asnum'] . "<span>个</span></p></div></div></li>";
        }
        if (count($userall) < $psize) {
            $data = array(
                'done' => "true",
                'html' => $html,
                'remove' => "1"
            );
            echo json_encode($data);
        } else {
            $data = array(
                'done' => "true",
                'html' => $html
            );
            echo json_encode($data);
        }
    }
}