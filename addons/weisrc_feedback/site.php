<?php
/**
 * 留言板
 *
 * 作者:微赞
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/weisrc_feedback/template/');
include "../addons/weisrc_feedback/model.php";

class weisrc_feedbackModuleSite extends WeModuleSite
{
    public $cur_version = '20140917';
    public $modulename = 'weisrc_feedback';

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
        //$account = account_fetch($this->_weid);
        $account = pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid = '$this->_weid'");

        $this->_auth2_openid = 'auth2_openid_'.$_W['uniacid'];
        $this->_auth2_nickname = 'auth2_nickname_'.$_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_'.$_W['uniacid'];

        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $account['level']; //是否为高级号

        if ($this->_accountlevel == 4) {
            $this->_appid = $account['key'];
            $this->_appsecret = $account['secret'];
        }

        $title = $account['name'];
        //message("维护中公众号{$title}等级=".$this->_accountlevel. 'secret'. $this->_appsecret);
    }

    //首页
    public function doMobileIndex()
    {
        global $_W, $_GPC;
        $from_user = $this->_fromuser;
        $weid = $this->_weid;

        $method = 'index';//method
        $authurl = $_W['siteroot'] .'app/'. $this->createMobileUrl($method, array(), true) . '&authkey=1';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
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

        $setting = pdo_fetch("select * from " . tablename($this->modulename . '_setting') . " where weid =:weid LIMIT 1", array(':weid' => $_W['uniacid']));
        $topimgurl = RES . 'images/logo.png';
        $ischeck = 1;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        if (!empty($setting)) {
            $psize = intval($setting['pagesize']) == 0? 10 : intval($setting['pagesize']);
            if (!empty($setting['topimgurl'])) {
                if (strstr($setting['topimgurl'], 'http')) {
                    $topimgurl = $setting['topimgurl'];
                } else {
                    $topimgurl = $_W['attachurl'].$setting['topimgurl'];
                }
            }
            $ischeck = intval($setting['ischeck']);
        }
        $where = 'AND status=1 AND parentid=0';

        $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE weid=".$_W['uniacid']." {$where} ORDER BY displayorder DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}", array(), 'id');

        $parentids = array_keys($list);
        $childlist = pdo_fetchall("SELECT * FROM ".tablename($this->modulename . '_feedback')." WHERE parentid IN ('".implode("','", is_array($parentids) ? $parentids : array($parentids))."') AND parentid!=0 AND weid=:weid ORDER BY displayorder DESC,id DESC", array(':weid' => $weid));
        foreach ($childlist as $index => $row) {
            if (!empty($row['parentid'])) {
                $children[$row['parentid']][] = $row;
            }
        }

        if (!empty($list)) {
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_feedback') . " WHERE weid=".$_W['uniacid']." {$where}");
            $pager = $this->pagination($total, $pindex, $psize);
        }

        include $this->template('index');
    }

    function pagination($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => '')) {
        global $_W;
        $pdata = array(
            'tcount' => 0,
            'tpage' => 0,
            'cindex' => 0,
            'findex' => 0,
            'pindex' => 0,
            'nindex' => 0,
            'lindex' => 0,
            'options' => ''
        );
        if($context['ajaxcallback']) {
            $context['isajax'] = true;
        }

        $pdata['tcount'] = $tcount;
        $pdata['tpage'] = ceil($tcount / $psize);
        if($pdata['tpage'] <= 1) {
            return '';
        }
        $cindex = $pindex;
        $cindex = min($cindex, $pdata['tpage']);
        $cindex = max($cindex, 1);
        $pdata['cindex'] = $cindex;
        $pdata['findex'] = 1;
        $pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
        $pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
        $pdata['lindex'] = $pdata['tpage'];

        if($context['isajax']) {
            if(!$url) {
                $url = $_W['script_name'] . '?' . http_build_query($_GET);
            }
            $pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
        } else {
            if($url) {
                $pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
                $pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
                $pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
                $pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
            } else {
                $_GET['page'] = $pdata['findex'];
                $pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['pindex'];
                $pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['nindex'];
                $pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['lindex'];
                $pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
            }
        }

        $html = '<div class="pagination">';
        if($pdata['cindex'] > 1) {
            $html .= "<div class=\"left\"><a {$pdata['paa']}>上一页</a></div>";
        } else {
            $html .= "<div class=\"left disabled\"><a href=\"###\">上一页</a></div>";
        }

        $html .= "<div class=\"allpage\"><div class=\"currentpage\"> <span class=\"ipage\">{$pindex}</span> / <span class=\"cpage\">" . $pdata['tpage'] . "</span></div></div>";

        if($pdata['cindex'] < $pdata['tpage']) {
            $html .= "<div class=\"right\"><a {$pdata['naa']}>下一页</a></div>";
        } else {
            $html .= "<div class=\"right disabled\"><a {$pdata['naa']}>下一页</a></div>";
        }
        $html .= '<div class="clr"></div></div>';
        return $html;
    }

    //回复
    public function doMobileSendReply()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = trim($_GPC['from_user']);
        $this->_fromuser = $from_user;

        $parentid = intval($_GPC['parentid']);
        $type = trim($_GPC['type']);

        $username = trim($_GPC['username']);
        $nickname = $_COOKIE[$this->_auth2_nickname];
        $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
        $content = trim($_GPC['content']);

        if (empty($from_user)) {
            //$this->showMessage('会话已过期，请重新发送关键字!');
        }

        if (empty($content)) {
            $this->showMessage('请输入回复内容!');
        }

        if ($type == 'feedback') { //留言
            $parentid = 0;
            if (empty($username)) {
                $this->showMessage('请输入名称!');
            }
        } else { //回复
            $item = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE id=:id AND weid=:weid AND status=1 LIMIT 1", array(':id' => $parentid, ':weid' => $weid));
            if (empty($item)) {
                $this->showMessage('要回复的留言可能已经被删除了!'.$parentid);
            }
            $username = $nickname;
        }

        $setting = pdo_fetch("select * from " . tablename($this->modulename . '_setting') . " where weid =:weid LIMIT 1", array(':weid' => $weid));
        if (empty($setting)) {
            $status = 0;
        } else {
            $status =  intval($setting['ischeck']) == 1 ? 0 : 1;
        }

        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'parentid' => $parentid,
            'username' => $username,
            'nickname' => $nickname,
            'headimgurl' => $headimgurl,
            'status' => $status,
            'content' => $content,
            'dateline' => TIMESTAMP
        );

        pdo_insert('weisrc_feedback_feedback', $data);
        if ($status == 0) {
            $this->showMessage('留言成功,请等待管理员的审核!', 1);
        } else {
            $this->showMessage('留言成功!', 1);
        }
    }

    public function doWebFeedback()
    {
        global $_W, $_GPC;
        checklogin();
        load()->func('tpl');

        $url = $this->createWebUrl('feedback', array('op' => 'display'));
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        $totalcount = pdo_fetchcolumn("SELECT COUNT(1) as count FROM ".tablename($this->modulename . '_feedback')."  WHERE weid = :weid", array(':weid' => $_W['uniacid']));
        $nocheckcount = pdo_fetchcolumn("SELECT COUNT(1) as count FROM ".tablename($this->modulename . '_feedback')."  WHERE weid = :weid AND status=0", array(':weid' => $_W['uniacid']));
        $checkcount = pdo_fetchcolumn("SELECT COUNT(1) as count FROM ".tablename($this->modulename . '_feedback')."  WHERE weid = :weid AND status=1", array(':weid' => $_W['uniacid']));

        if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $item = pdo_fetch("select * from " . tablename($this->modulename . '_feedback') . " where id=:id AND weid =:weid", array(':id' => $id, ':weid' => $_W['uniacid']));

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    //'parentid' => intval($_GPC['parentid']),
                    'nickname' => trim($_GPC['username']),
                    'username' => trim($_GPC['username']),
                    'headimgurl' => trim($_GPC['headimgurl']),
                    'content' => trim($_GPC['content']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'status' => intval($_GPC['status']),
                    'dateline' => TIMESTAMP,
                );

                if (!empty($_GPC['headimgurl'])) {
                    $data['headimgurl'] = $_GPC['headimgurl'];
                    load()->func('file');
                    file_delete($_GPC['headimgurl-old']);
                }

                if (!empty($item)) {
                    unset($data['nickname']);
                    unset($data['dateline']);
                    pdo_update($this->modulename . '_feedback', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    pdo_insert($this->modulename . '_feedback', $data);
                }
                message('操作成功', $url, 'success');
            }
        } elseif ($operation == 'reply') {
            $parentid = intval($_GPC['parentid']);

            $item = pdo_fetch("select * from " . tablename($this->modulename . '_feedback') . " where id=:id AND weid =:weid AND parentid=0 LIMIT 1", array(':id' => $parentid, ':weid' => $_W['uniacid']));
            if (empty($item)) {
                message('数据不存在!');
            }

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'parentid' => $parentid,
                    'nickname' => '管理员',
                    'username' => '管理员',
                    'headimgurl' => '',
                    'content' => trim($_GPC['content']),
                    'displayorder' => 0,
                    'status' => 1,
                    'dateline' => TIMESTAMP,
                );

                pdo_insert($this->modulename . '_feedback', $data);
                message('操作成功', $url, 'success');
            }
        } elseif ($operation == 'display') {
            if (isset($_GPC['status'])) {
                $status = intval($_GPC['status']);
            } else {
                $status = -1;
            }

            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update($this->modulename . '_feedback', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                    }
                }
                message('操作成功!', $url);
            }

            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where = ' AND parentid=0 ';

            if ($status != -1) {
                if ($status == 0) {
                    $where = '';
                }
                $where .= " AND status={$status} ";
            }

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE weid=".$_W['uniacid']." {$where} ORDER BY displayorder DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}", array(), 'id');

            $parentids = array_keys($list);
            $childlist = pdo_fetchall("SELECT * FROM ".tablename($this->modulename . '_feedback')." WHERE parentid IN ('".implode("','", is_array($parentids) ? $parentids : array($parentids))."') AND parentid!=0 AND weid=:weid ORDER BY displayorder DESC,id DESC", array(':weid' => $_W['uniacid']));
            foreach ($childlist as $index => $row) {
                if (!empty($row['parentid'])) {
                    $children[$row['parentid']][] = $row;
                }
            }

            if (!empty($list)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_feedback') . " WHERE weid=".$_W['uniacid']." {$where}");
                $pager = pagination($total, $pindex, $psize);
            }

        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $data = pdo_fetch("SELECT id FROM " . tablename($this->modulename . '_feedback') . " WHERE id = :id", array(':id' => $id));
            if (empty($data)) {
                message('抱歉，不存在或是已经被删除！', $this->createWebUrl('_feedback', array('op' => 'display')), 'error');
            }
            if ($data['parentid'] == 0) {
                pdo_delete($this->modulename . '_feedback', array('parentid' => $id, 'weid' => $_W['weid']));
            }
            pdo_delete($this->modulename . '_feedback', array('id' => $id, 'weid' => $_W['uniacid']));
            message('删除成功！', $this->createWebUrl('feedback', array('op' => 'display')), 'success');
        } elseif ($operation == 'deleteall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)) {
                    $feedback = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE id = :id", array(':id' => $id));
                    if (empty($feedback)) {
                        $notrowcount++;
                        continue;
                    }
                    if ($feedback['parentid'] == 0) {
                        pdo_delete($this->modulename . '_feedback', array('parentid' => $id, 'weid' => $_W['weid']));
                    }
                    pdo_delete($this->modulename . '_feedback', array('id' => $id, 'weid' => $_W['weid']));
                    $rowcount++;
                }
            }
            $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
        } elseif ($operation == 'checkall') {
            $rowcount = 0;
            $notrowcount = 0;
            foreach ($_GPC['idArr'] as $k => $id) {
                $id = intval($id);
                if (!empty($id)) {
                    $feedback = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE id = :id", array(':id' => $id));
                    if (empty($feedback)) {
                        $notrowcount++;
                        continue;
                    }

                    $data = empty($feedback['status']) ? 1 : 0;
                    pdo_update($this->modulename . '_feedback', array('status' => $data), array("id" => $id, "weid" => $_W['weid']));
                    $rowcount++;
                }
            }
            $this->message("操作成功！共审核{$rowcount}条数据,{$notrowcount}条数据不能删除!!", '', 0);
        }

        include $this->template('feedback');
    }

    public function doWebSetting()
    {
        global $_W, $_GPC;
        checklogin();
        load()->func('tpl');

        $item = pdo_fetch("select * from " . tablename($this->modulename . '_setting') . " where weid =:weid", array(':weid' => $_W['uniacid']));

        if (checksubmit('submit')) {
            $data = array(
                'weid' => $_W['uniacid'],
                'topimgurl' => trim($_GPC['topimgurl']),
                'pagecolor' => trim($_GPC['pagecolor']),
                'pagesize' => trim($_GPC['pagesize']),
                'ischeck' => intval($_GPC['ischeck']),
                'dateline' => TIMESTAMP,
            );

            if (!empty($_GPC['topimgurl'])) {
                $data['topimgurl'] = $_GPC['topimgurl'];
                load()->func('file');
                file_delete($_GPC['topimgurl-old']);
            }

            if (!empty($item)) {
                unset($data['dateline']);
                pdo_update($this->modulename . '_setting', $data, array('weid' => $_W['uniacid']));
            } else {
                pdo_insert($this->modulename . '_setting', $data);
            }

            message('操作成功', $this->createWebUrl('setting'), 'success');
        }

        include $this->template('setting');
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

    public function showMessage($msg, $status = 0)
    {
        $result = array('message' => $msg, 'status' => $status);
        echo json_encode($result);
        exit;
    }

    public function message($error, $url = '', $errno = -1) {
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