<?php
/**
 * 邀请函
 *
 * 作者:微赞
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/weisrc_invitative/template/');
//define('LOCK', 'Li4vYWRkb25zL3dlaXNyY19kaXNoL3RlbXBsYXRlL2ltYWdlcy92ZXJzaW9uLmNzcw==');
include "../addons/weisrc_invitative/model.php";

class weisrc_invitativeModuleSite extends WeModuleSite
{
    public $cur_version = '20140917';
    //模块标识
    public $modulename = 'weisrc_invitative';

    public $_debug = '1'; //default:0
    public $_weixin = '1'; //default:1

    public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';

    public $_weid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';

    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';

    function __construct()
    {
        global $_W, $_GPC;
        $this->_fromuser = $_W['fans']['from_user'];//debug
        if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
            $this->_fromuser = 'debug';
        }
        $this->_weid = $_W['uniacid'];

        $account = account_fetch($this->_weid);

        $this->_auth2_openid = 'auth2_openid_'.$_W['uniacid'];
        $this->_auth2_nickname = 'auth2_nickname_'.$_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_'.$_W['uniacid'];

        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $account['level']; //是否为高级号

        //$lock_path = base64_decode(LOCK);
        //if (!file_exists($lock_path)) {

       // } else {
            //$file_content = file_get_contents($lock_path);
            //$validation_code = $this->authorization();
            //$this->code_compare($file_content, $validation_code);
       // }

        if ($this->_accountlevel == 4) {
            $this->_appid = $account['key'];
            $this->_appsecret = $account['secret'];
        }
    }

    //首页
    public function doMobileIndex()
    {
        global $_W, $_GPC;
        $from_user = $this->_fromuser;
        $weid = $this->_weid;

        $id = intval($_GPC['id']);
        $bg = RES.'images/bg.jpg';//背景
        $cardbg = RES.'images/card_images/card_01.png';//邀请卡版式
        $musicurl = '';

        $method = 'index';//method
        $authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array('id' => $id), true) . '&authkey=1';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('id' => $id), true);
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $from_user = $_COOKIE[$this->_auth2_openid];
            $nickname = $_COOKIE[$this->_auth2_nickname];
            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
        } else {
            if (isset($_GPC['code'])) {
                $userinfo = $this->oauth2($authurl);

                if (!empty($userinfo)) {
                    $from_user = $userinfo["openid"];
                    $nickname = $userinfo["nickname"];
                    $headimgurl = $userinfo["headimgurl"];
                } else {
                    message('授权失败!');
                }
            } else {
                if (!empty($this->_appsecret)) {
                    $this->toAuthUrl($url);
                }
            }
        }

        $item = pdo_fetch("select * from " . tablename($this->modulename . '_activity') . " where id=:id AND weid =:weid LIMIT 1", array(':id' => $id, ':weid' => $_W['uniacid']));

        if ($item['cardtype'] == 1) {
            if (!empty($item['cardbg'])) {
                $cardbg = RES. 'images/card_images/' . $item['cardbg'];
            }
        } else {
            if (!empty($item['cardbg'])) {
                if (strstr($item['cardbg'], 'http')) {
                    $cardbg = $item['cardbg'];
                } else {
                    $cardbg = $_W['attachurl'].$item['cardbg'];
                }
            }
        }

        if (!empty($item['bg'])) {
            if (strstr($item['bg'], 'http')) {
                $bg = $item['bg'];
            } else {
                $bg = $_W['attachurl'].$item['bg'];
            }
        }

        if (!empty($item['musicurl'])) {
            if (strstr($item['musicurl'], 'http')) {
                $musicurl = $item['musicurl'];
            } else {
                $musicurl = $_W['attachurl'].$item['musicurl'];
            }
        }

        $item['thumbs'] = unserialize($item['thumbs']);

        include $this->template('index');
    }

    public function doMobileCheckSignupStatus()
    {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        $from_user = trim($_GPC['from_user']);

        $item = pdo_fetch("select * from " . tablename($this->modulename . '_user') . " where activityid=:activityid AND weid =:weid AND from_user=:from_user LIMIT 1", array(':activityid' => $id, ':weid' => $_W['uniacid'], ':from_user' => $from_user));

        if (!empty($item)) {
            $this->showMessage('已经报名！', 1);
        } else {
            $this->showMessage('未报名！');
        }
    }

    public function doMobileGuestinfo()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = trim($_GPC['from_user']);
        $this->_fromuser = $from_user;

        $activityid = intval($_GPC['activityid']);
        $username = trim($_GPC['username']);
        $nickname = $_COOKIE[$this->_auth2_nickname];
        $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
        $tel = trim($_GPC['tel']);
        $company = trim($_GPC['company']);
        $position = trim($_GPC['position']);

        if (empty($from_user)) {
            $this->showMessage('会话已过期，请重新发送关键字!');
        }

        $user = pdo_fetch("select * from " . tablename($this->modulename . '_user') . " where activityid=:activityid AND weid =:weid AND from_user=:from_user LIMIT 1", array(':activityid' => $activityid, ':weid' => $_W['uniacid'], ':from_user' => $from_user));

        if (!empty($user)) {
            $this->showMessage('您已经报过名了!');
        }

        if ($activityid == 0) {
            $this->showMessage('该活动不存在!');
        } else {
            $activity = pdo_fetch("select * from " . tablename($this->modulename . '_activity') . " where status=1 AND id=:id AND weid =:weid", array(':id' => $activityid, ':weid' => $_W['uniacid']));
            if (empty($activity)) {
                $this->showMessage('该活动不存在!');
            } else if(TIMESTAMP < $activity['starttime'] || TIMESTAMP > $activity['endtime']) {
                $this->showMessage('对不起，报名时间已过!');
            }
        }

        if (empty($username)) {
            $this->showMessage('请输入用户名!');
        }

        if (empty($tel)) {
            $this->showMessage('请输入联系电话!');
        }

        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'activityid' => $activityid,
            'nickname' => $nickname,
            'headimgurl' => $headimgurl,
            'username' => $username,
            'tel' => $tel,
            'company' => $company,
            'position' => $position,
            'dateline' => TIMESTAMP
        );

        pdo_insert('weisrc_invitative_user', $data);
        $this->showMessage('操作成功!', 1);
    }

    public function doWebActivity()
    {
        global $_W, $_GPC;
        //checklogin();
        load()->func('tpl');

        $url = $this->createWebUrl('activity', array('op' => 'display'));
        $tpl = dir(IA_ROOT . '/addons/weisrc_invitative/template/images/card_images/');
        $tpl->handle;
        $theme_array = array();
        while ($entry = $tpl->read()) {
            if (preg_match("/^[a-zA-Z0-9]/", $entry) && $entry != 'common' && $entry != 'photo') {
                array_push($theme_array, $entry);
            }
        }
        $tpl->close();

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $item = pdo_fetch("select * from " . tablename($this->modulename . '_activity') . " where id=:id AND weid =:weid", array(':id' => $id, ':weid' => $_W['uniacid']));
            if (!empty($item)) {
                $thumbs = unserialize($item['thumbs']);
                $starttime = date('Y-m-d H:i', $item['starttime']);
                $endtime = date('Y-m-d H:i', $item['endtime']);

                if ($item['cardtype'] == 1) {
                    $cardbg_tmp = empty($item['cardbg'])? 'card_01.png' : $item['cardbg'];
                    $page_cardbg = '../addons/weisrc_invitative/template/images/card_images/' . $cardbg_tmp;
                }
            } else {
                $starttime = date('Y-m-d H:i');
                $endtime = date('Y-m-d H:i', TIMESTAMP+86400*30);
            }

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'reply_title' => trim($_GPC['reply_title']),
                    'title' => trim($_GPC['title']),
                    'description' => trim($_GPC['description']),
                    'organizers' => trim($_GPC['organizers']),
                    'cardtype' => intval($_GPC['cardtype']),
                    'musicurl' => trim($_GPC['musicurl']),
                    'content' => htmlspecialchars_decode($_GPC['content']),
                    'address' => trim($_GPC['address']),
                    'tel' => trim($_GPC['tel']),
                    'lat' => $_GPC['baidumap']['lat'],
                    'lng' => $_GPC['baidumap']['lng'],
                    'starttime' => strtotime($_GPC['starttime']),
                    'endtime' => strtotime($_GPC['endtime']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'status' => intval($_GPC['status']),
                    'dateline' => TIMESTAMP,
                );

                if ($data['cardtype'] == 1) { //默认
                    if (empty($_GPC['select_theme'])) {
                        $data['cardbg'] = 'card_01.png';
                    } else {
                        $data['cardbg'] = $_GPC['select_theme'];
                    }
                } else { //自定义
                    $data['cardbg'] = $_GPC['cardbg'];
                }

                if (!empty($_GPC['thumb'])) {
                    $data['thumb'] = $_GPC['thumb'];
                    load()->func('file');
                    file_delete($_GPC['thumb-old']);
                }
                if (!empty($_GPC['bg'])) {
                    $data['bg'] = $_GPC['bg'];
                    load()->func('file');
                    file_delete($_GPC['bg-old']);
                }
                if (is_array($_GPC['thumbs'])) {
                    $data['thumbs'] = serialize($_GPC['thumbs']);
                }

                if (!empty($item)) {
                    unset($data['dateline']);
                    pdo_update($this->modulename . '_activity', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    pdo_insert($this->modulename . '_activity', $data);
                }

                message('操作成功', $this->createWebUrl('activity'), 'success');
            }
        } elseif ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update($this->modulename . '_activity', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                    }
                }
                message('操作成功!', $url);
            }

            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $where = '';
            $keyword = trim($_GPC['keyword']);

            if (!empty($keyword)) {
                $where = " AND (title like '%{$keyword}%') ";
            }

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_activity') . " WHERE weid=".$_W['uniacid']." {$where} ORDER BY displayorder DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($list)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_activity') . " WHERE weid=".$_W['uniacid']." {$where}");
                $pager = pagination($total, $pindex, $psize);
            }

            $users = pdo_fetchall("SELECT activityid,COUNT(1) as count FROM ".tablename($this->modulename . '_user')."  GROUP BY activityid,weid having weid = :weid", array(':weid' => $_W['uniacid']), 'activityid');

        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $data = pdo_fetch("SELECT id FROM " . tablename($this->modulename . '_activity') . " WHERE id = :id", array(':id' => $id));
            if (empty($data)) {
                message('抱歉，不存在或是已经被删除！', $this->createWebUrl('activity', array('op' => 'display')), 'error');
            }
            pdo_delete($this->modulename . '_user', array('activityid' => $id, 'weid' => $_W['uniacid']));
            pdo_delete($this->modulename . '_activity', array('id' => $id, 'weid' => $_W['uniacid']));
            message('删除成功！', $this->createWebUrl('activity', array('op' => 'display')), 'success');
        }

        include $this->template('activity');
    }

    public function doWebUser()
    {
        global $_W, $_GPC;
        //checklogin();

        $activityid = intval($_GPC['activityid']);
        $url = $this->createWebUrl('activity', array('op' => 'display'));
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $item = pdo_fetch("select * from " . tablename($this->modulename . '_user') . " where id=:id AND weid =:weid", array(':id' => $id, ':weid' => $_W['uniacid']));

        } elseif ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update($this->modulename . '_user', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                    }
                }
                message('操作成功!', $url);
            }

            $keyword = trim($_GPC['keyword']);
            $where = " WHERE weid=".$_W['uniacid'];
            if ($activityid != 0) {
                $where .= " AND activityid={$activityid} ";
            }
            if (!empty($keyword)) {
                $where .= " AND (username like '%{$keyword}%') ";
            }

            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_user') . " {$where} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($list)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_user') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $data = pdo_fetch("SELECT id FROM " . tablename($this->modulename . '_user') . " WHERE id = :id", array(':id' => $id));
            if (empty($data)) {
                message('抱歉，不存在或是已经被删除！', $this->createWebUrl('activity', array('op' => 'display')), 'error');
            }
            pdo_delete($this->modulename . '_user', array('id' => $id, 'weid' => $_W['uniacid']));
            message('删除成功！', $this->createWebUrl('activity', array('op' => 'display')), 'success');
        }

        include $this->template('user');
    }

    public function doWebQuery()
    {
        global $_W, $_GPC;

        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename($this->modulename . '_activity') . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY id DESC LIMIT 0,8';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':title'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['title'] = $row['title'];
            $r['description'] = cutstr(strip_tags($row['description']), 50);
            $r['thumb'] = $row['thumb'];
            $r['activityid'] = $row['id'];
            $row['entry'] = $r;
        }
        include $this->template('query');
    }

    public function showMessage($msg, $status = 0)
    {
        $result = array('message' => $msg, 'status' => $status);
        echo json_encode($result);
        exit;
    }

    function authorization()
    {
        $host = get_domain();
        return base64_encode($host);
    }

    function code_compare($a, $b)
    {
        if ($this->_debug == 1) {
            if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
                return true;
            }
        }
        if ($a != $b) {
            message(base64_decode("5a+55LiN6LW377yM5oKo5L2/55So55qE57O757uf5piv55Sx6Z2e5rOV5rig6YGT5Lyg5pKt55qE77yM6K+35pSv5oyB5q2j54mI44CC6LSt5Lmw6L2v5Lu26K+36IGU57O7UVExNTU5NTc1NeOAgg=="));
        }
    }

    function isWeixin()
    {
        if ($this->_weixin == 1) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            if (!strpos($userAgent, 'MicroMessenger')) {
                include $this->template('s404');
                exit();
            }
        }
    }

    //auth2
    public function toAuthUrl($url)
    {
        global $_W;
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
        header("location:$oauth2_code");
    }

    public function oauth2($authurl)
    {
        global $_GPC, $_W;
        load()->func('communication');
        $state = $_GPC['state']; //1为关注用户, 0为未关注用户
        $code = $_GPC['code'];

        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->_appid . "&secret=" . $this->_appsecret . "&code=" . $code . "&grant_type=authorization_code";

        $content = ihttp_get($oauth2_code);

        $token = @json_decode($content['content'], true);

        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit;
        }
        $from_user = $token['openid'];

        if ($this->_accountlevel != 2) { //普通号
            $authkey = intval($_GPC['authkey']);
            if ($authkey == 0) {
                $url = $authurl;
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
        } else {
            //再次查询是否为关注用户

            $follow = pdo_fetchcolumn("SELECT follow FROM ".tablename('mc_mapping_fans')." WHERE openid = :openid AND acid = :acid", array(':openid' => $from_user, ':acid' => $_W['uniacid']));

            //$profile = fans_search($from_user);
            //message('showinfo:'.$follow);
            if ($follow == 1) { //关注用户直接获取信息
                $state = 1;
            } else { //未关注用户跳转到授权页
                $url = $authurl;
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
        }

        //未关注用户和关注用户取全局access_token值的方式不一样
        if ($state == 1) {
            $oauth2_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->_appid . "&secret=" . $this->_appsecret . "";
            $content = ihttp_get($oauth2_url);
            $token_all = @json_decode($content['content'], true);
            if (empty($token_all) || !is_array($token_all) || empty($token_all['access_token'])) {
                echo '<h1>获取微信公众号授权失败[无法取得access_token], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                exit;
            }
            $access_token = $token_all['access_token'];
            $oauth2_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $from_user . "&lang=zh_CN";
        } else {
            $access_token = $token['access_token'];
            $oauth2_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $from_user . "&lang=zh_CN";
        }

        //使用全局ACCESS_TOKEN获取OpenID的详细信息
        $content = ihttp_get($oauth2_url);
        $info = @json_decode($content['content'], true);
        if (empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])) {
            echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>' . 'state:' . $state . 'nickname' . 'weid:';
            exit;
        }
        $headimgurl = $info['headimgurl'];
        $nickname = $info['nickname'];
        //设置cookie信息

        setcookie($this->_auth2_headimgurl, $headimgurl, time() + 3600 * 24);
        setcookie($this->_auth2_nickname, $nickname, time() + 3600 * 24);
        setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
        return $info;
    }
}