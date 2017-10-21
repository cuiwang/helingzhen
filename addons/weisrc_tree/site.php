<?php
/**
 * 全民来植树
 *
 * 作者:迷失卍国度
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/weisrc_tree/template/');

class weisrc_treeModuleSite extends WeModuleSite
{
    public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';
    public $_account = '';

    public $_uniacid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';
    public $_activeid = 0;

    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';

    public $table_reply = 'weisrc_tree_reply';
    public $table_fans = 'weisrc_tree_fans';
    public $table_record = 'weisrc_tree_record';
    public $table_award = 'weisrc_tree_award';
    public $_today_sign_state = 0;

    function __construct()
    {
        global $_W, $_GPC;
        $this->_uniacid = $_W['uniacid'];
        $this->_fromuser = $_W['fans']['from_user']; //debug
        if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
            $this->_fromuser = 'debug';
        }

        $this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
        $this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];

        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $_W['account']['level']; //是否为高级号

        if (isset($_COOKIE[$this->_auth2_openid])) {
            $this->_fromuser = $_COOKIE[$this->_auth2_openid];
        }

        if ($this->_accountlevel < 4) {
            $setting = uni_setting($this->_uniacid);
            $oauth = $setting['oauth'];
            if (!empty($oauth) && !empty($oauth['account'])) {
                $this->_account = account_fetch($oauth['account']);
                $this->_appid = $this->_account['key'];
                $this->_appsecret = $this->_account['secret'];
            }
        } else {
            $this->_appid = $_W['account']['key'];
            $this->_appsecret = $_W['account']['secret'];
        }
        $this->stn();
    }

    public function doMobileList()
    {
        global $_GPC, $_W;
        $uniacid = $this->_uniacid;
        $from_user = $this->_fromuser;

        $id = intval($_GPC['id']);
        if (empty($id)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));
        if ($reply == false) {
            message('抱歉，活动不存在！', '', 'error');
        } else {
            if (!empty($reply['logo'])) {
                $logo = tomedia($reply['logo']);
            }
            if (!empty($reply['bg'])) {
                $bg = tomedia($reply['bg']);
            }
            if (!empty($reply['light'])) {
                $light = tomedia($reply['light']);
            }
            if (!empty($reply['light2'])) {
                $light2 = tomedia($reply['light2']);
            }
            if (!empty($reply['paper'])) {
                $paper = tomedia($reply['paper']);
            }
        }

//        $method = 'list';
//        $authurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('id' => $id), true) . '&authkey=1';
//        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('id' => $id), true);
//        if (isset($_COOKIE[$this->_auth2_openid])) {
//            $from_user = $_COOKIE[$this->_auth2_openid];
//            $nickname = $_COOKIE[$this->_auth2_nickname];
//            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
//            $sex = $_COOKIE[$this->_auth2_sex];
//        } else {
//            if (isset($_GPC['code'])) {
//                $userinfo = $this->oauth2($authurl);
//                if (!empty($userinfo)) {
//                    $from_user = $userinfo["openid"];
//                    $nickname = $userinfo["nickname"];
//                    $headimgurl = $userinfo["headimgurl"];
//                    $sex = $userinfo["sex"];
//                } else {
//                    message('授权失败!');
//                }
//            } else {
//                if (!empty($this->_appsecret)) {
//                    $this->getCode($url);
//                }
//            }
//        }

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $from_user));

        $strwhere = "WHERE rid = :rid AND status=1";
        $limit = " LIMIT 0,10";

        $orderstr = " success_time ASC ";
        if ($reply['mode'] == 2) {
            $orderstr = " totalnum DESC ";
        }

        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " {$strwhere} ORDER BY {$orderstr}, id ASC " . $limit, array(':rid' => $id));

        $total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " {$strwhere}  ", array(':rid' => $id));

        $total2 = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid=:rid AND status=1 ", array(':rid' => $id));

        $maxpage = ceil($total/10);

        $helpcount = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_record) . " where rid=:rid AND from_user=:from_user ", array(':rid' => $id, ':from_user' => $from_user));

        $fanslist = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " {$strwhere} ORDER BY {$orderstr}, id ASC ", array(':rid' => $id));

        $orderNo = 1;

        $ishave = false;
        foreach ($fanslist AS $key => $value) {
            if ($value['from_user'] == $from_user) {
                $ishave = true;
                break;
            }
            $orderNo++;
        }

        if ($ishave == false) {
            $orderNo = 0;
        }

        //分享信息
        $share_title = str_replace("#username#", $fans['nickname'], $reply['share_title']);
        $share_desc = str_replace("#username#", $fans['nickname'], $reply['share_desc']);
        $share_url = empty($reply['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id, 'followuser' => $from_user), true) : $reply['share_url'];
        $share_image = tomedia($reply['share_image']);

        include $this->template('list');
    }

    public function doMobileGetMore()
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $id = intval($_GPC['id']);
        $page = max(1, intval($_GPC['page']));
        $psize = 10;
        $start = ($page - 1) * $psize;
        $limit = "";
        $limit .= " LIMIT {$start},{$psize} ";

        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));


        $orderstr = " totalnum DESC ";


        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid AND status=1 ORDER BY {$orderstr}, id ASC " . $limit, array(':rid' => $id));

        $str = "";
        $num = (($page - 1) * 10) + 1;
        foreach ($list as $key => $value) {
            $result = $value['totalnum'];
            $nickname = empty($value['nickname']) ? '------' : $value['nickname'];

            $str .= '<li>
            <div class="NO">' . $num . '</div>
            <div class="Head">
                <img src="' . tomedia($value['headimgurl']) . '"
                    width="120"></div>
            <div class="text">
                <div class="his"><h1>' . $nickname . '</h1>
                    <h2>　</h2></div>
                <div class="gift"><p>' . $result . '<span>人</span></p></div>
            </div>
        </li>';
            $num++;
        }
        echo $str;
    }

    public function doMobileBm()
    {
        global $_GPC, $_W;
        $uniacid = $this->_uniacid;
        $from_user = $this->_fromuser;

        $id = intval($_GPC['id']);
        if (empty($id)) {
            message('抱歉，参数错误！', '', 'error');
        }

        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));
        if ($reply == false) {
            message('活动不存在！', '', 'error');
        }
        if ($reply['starttime'] > TIMESTAMP) {
            message('活动未开始，请等待...', '', 'error');
        }
        if (TIMESTAMP > $reply['endtime']) {
            message('抱歉，活动已经结束，下次再来吧！', $this->createMobileUrl('list', array('id' => $id), true), 'error');
        }

        if (!empty($reply['logo'])) {
            $logo = tomedia($reply['logo']);
        }
        if (!empty($reply['bg'])) {
            $bg = tomedia($reply['bg']);
        }
        if (!empty($reply['light'])) {
            $light = tomedia($reply['light']);
        }
        if (!empty($reply['light2'])) {
            $light2 = tomedia($reply['light2']);
        }
        if (!empty($reply['paper'])) {
            $paper = tomedia($reply['paper']);
        }

        $method = 'bm';
        $authurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('id' => $id), true) . '&authkey=1';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('id' => $id), true);
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $from_user = $_COOKIE[$this->_auth2_openid];
            $nickname = $_COOKIE[$this->_auth2_nickname];
            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
            $sex = $_COOKIE[$this->_auth2_sex];
        } else {
            if (isset($_GPC['code'])) {
                $userinfo = $this->oauth2($authurl);
                if (!empty($userinfo)) {
                    $from_user = $userinfo["openid"];
                    $nickname = $userinfo["nickname"];
                    $headimgurl = $userinfo["headimgurl"];
                    $sex = $userinfo["sex"];
                } else {
                    message('授权失败!');
                }
            } else {
                if (!empty($this->_appsecret)) {
                    $this->getCode($url);
                }
            }
        }

        $follow_url = $reply['follow_url'];
        if (empty($from_user)) {
            if (!empty($reply['follow_url'])) {
                header("location:$follow_url");
            }
        }

        $sub = 0;
        if ($this->_accountlevel == 4) {
            $userinfo = $this->getUserInfo($from_user);
            if ($userinfo['subscribe'] == 1) {
                $sub = 1;
            }
        } else {
            if ($_W['fans']['follow'] == 1) {
                $sub = 1;
            }
        }

        if ($sub == 0) {
            if ($reply['isneedfollow'] == 1) {
                if (!empty($follow_url)) {
                    header("location:$follow_url");
                } else {
                    message("请先关注公众号再玩游戏");
                }
            }
        }

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $from_user));
        if (empty($fans)) {
            $insert = array(
                'rid' => $id,
                'uniacid' => $uniacid,
                'from_user' => $from_user,
                'headimgurl' => $headimgurl,
                'nickname' => $nickname,
                'todaynum' => 0,
                'totalnum' => 0,
                'awardnum' => 0,
                'dateline' => TIMESTAMP,
            );

            if (!empty($from_user)) {
                pdo_insert($this->table_fans, $insert);
            }
        } else {
            if (!empty($nickname) && !empty($headimgurl)) {
                pdo_update($this->table_fans, array('nickname' => $nickname, 'headimgurl' => $headimgurl), array('id' => $fans['id']));
            }
            pdo_update($this->table_reply, array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
        }

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $from_user));

        if (!empty($fans['username']) || !empty($fans['address']) || !empty($fans['tel'])) {
            header("location:" . $this->createMobileUrl('index', array('id' => $id), true));
        }

        //分享信息
        $share_title = str_replace("#username#", $fans['nickname'], $reply['share_title']);
        $share_desc = str_replace("#username#", $fans['nickname'], $reply['share_desc']);
        $share_url = empty($reply['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id, 'followuser' => $from_user), true) : $reply['share_url'];
        $share_image = tomedia($reply['share_image']);

        include $this->template('reg');
    }

    public function doMobileReg()
    {
        global $_GPC, $_W;
        $from_user = $this->_fromuser;
        $id = intval($_GPC['id']);
        $username = trim($_GPC['username']);
        $tel = trim($_GPC['tel']);
        $address = trim($_GPC['address']);

        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid AND :time>starttime AND :time<endtime ORDER BY `id` DESC", array(':rid' => $id, ':time' => TIMESTAMP));
        if ($reply == false) {
            $msg = array(
                'status' => "fail",
                'msg' => '抱歉，活动已经结束，下次再来吧！',
            );
            die(json_encode($msg));
        }
        if (!empty($reply['light'])) {
            $light = tomedia($reply['light']);
        }
        if ($reply['isusername'] == 1) {
            if (empty($username)) {
                $msg = array(
                    'status' => "fail",
                    'msg' => '请输入姓名！',
                );
                die(json_encode($msg));
            }
        }

        if ($reply['istel'] == 1) {
            if (empty($tel)) {
                $msg = array(
                    'status' => "fail",
                    'msg' => '请输入手机号码！',
                );
                die(json_encode($msg));
            }
        }

        if ($reply['isaddress'] == 1) {
            if (empty($address)) {
                $msg = array(
                    'status' => "fail",
                    'msg' => '请输入联系地址！',
                );
                die(json_encode($msg));
            }
        }

        $fans = pdo_fetch("SELECT id FROM " . tablename($this->table_fans) . " WHERE rid = :id and from_user=:from_user LIMIT 1", array(':id' => $id, ':from_user' => $from_user));

        if (empty($fans)) {
            $msg = array(
                'status' => "fail",
                'msg' => '保存数据错误！',
            );
            die(json_encode($msg));
        } else {
            $temp = pdo_update($this->table_fans, array('username' => $username, 'tel' => $tel, 'address' => $address), array('rid' => $id, 'id' => $fans['id']));
            if ($temp === false) {
                $data = array(
                    'status' => "fail",
                    'msg' => '保存数据错误！',
                );
            } else {
                $data = array(
                    'status' => "success",
                    'msg' => '活动报名成功！',
                );
            }
        }
        echo json_encode($data);
    }

    public function doMobileindex()
    {
        global $_GPC, $_W;
        $uniacid = $this->_uniacid;
        $from_user = $this->_fromuser;

        $id = intval($_GPC['id']);
        if (empty($id)) {
            message('抱歉，参数错误！', '', 'error');
        }

        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));
        if ($reply == false) {
            message('活动不存在！', '', 'error');
        }
        if ($reply['starttime'] > TIMESTAMP) {
            message('活动未开始，请等待...', '', 'error');
        }
        if (TIMESTAMP > $reply['endtime']) {
            message('抱歉，活动已经结束，下次再来吧！', $this->createMobileUrl('list', array('id' => $id), true), 'error');
        }

        if (!empty($reply['logo'])) {
            $logo = tomedia($reply['logo']);
        }
        if (!empty($reply['bg'])) {
            $bg = tomedia($reply['bg']);
        }
        if (!empty($reply['light'])) {
            $light = tomedia($reply['light']);
        }
        if (!empty($reply['light2'])) {
            $light2 = tomedia($reply['light2']);
        }
        if (!empty($reply['paper'])) {
            $paper = tomedia($reply['paper']);
        }
        if (!empty($reply['qrcode'])) {
            $qrcode = tomedia($reply['qrcode']);
        }

        $followuser = trim($_GPC['followuser']);
        $isown = 0;
        if (empty($followuser)) {
            $followuser = $from_user;
            $isown = 1;
        }

        $ishelp = $this->ishelp($followuser, $id);

        $method = 'index';
        $authurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('followuser' => $followuser, 'id' => $id), true) . '&authkey=1';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('followuser' => $followuser, 'id' => $id), true);
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $from_user = $_COOKIE[$this->_auth2_openid];
            $nickname = $_COOKIE[$this->_auth2_nickname];
            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
            $sex = $_COOKIE[$this->_auth2_sex];
        } else {
            if (isset($_GPC['code'])) {
                $userinfo = $this->oauth2($authurl);
                if (!empty($userinfo)) {
                    $from_user = $userinfo["openid"];
                    $nickname = $userinfo["nickname"];
                    $headimgurl = $userinfo["headimgurl"];
                    $sex = $userinfo["sex"];
                } else {
                    message('授权失败!');
                }
            } else {
                if (!empty($this->_appsecret)) {
                    $this->getCode($url);
                }
            }
        }

        if ($isown == 1) {
            $follow_url = $reply['follow_url'];
            if (empty($from_user)) {
                if (!empty($reply['follow_url'])) {
                    header("location:$follow_url");
                }
            }

            $sub = 0;
            if ($this->_accountlevel == 4) {
                $userinfo = $this->getUserInfo($from_user);
                if ($userinfo['subscribe'] == 1) {
                    $sub = 1;
                }
            } else {
                if ($_W['fans']['follow'] == 1) {
                    $sub = 1;
                }
            }

            if ($sub == 0) {
                if ($reply['isneedfollow'] == 1) {
                    if (!empty($follow_url)) {
                        header("location:$follow_url");
                    } else {
                        message("请先关注公众号再玩游戏");
                    }
                }
            }
        }

        $awardlist = pdo_fetchall("SELECT * FROM " . tablename('weisrc_tree_award') . " WHERE rid = :rid ", array(':rid' => $id));
        $recordlist = pdo_fetchall("SELECT * FROM " . tablename('weisrc_tree_record') . " WHERE rid = :rid AND help_user=:help_user ", array(':rid' => $id, ':help_user' => $followuser));

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $from_user));
        if (empty($fans)) {
            $insert = array(
                'rid' => $id,
                'uniacid' => $uniacid,
                'from_user' => $from_user,
                'headimgurl' => $headimgurl,
                'nickname' => $nickname,
                'todaynum' => 0,
                'totalnum' => 0,
                'awardnum' => 0,
                'dateline' => TIMESTAMP,
            );

            if (!empty($from_user)) {
                pdo_insert($this->table_fans, $insert);
            }
        } else {
            if (!empty($nickname) && !empty($headimgurl)) {
                pdo_update($this->table_fans, array('nickname' => $nickname, 'headimgurl' => $headimgurl), array('id' => $fans['id']));
            }
            pdo_update($this->table_reply, array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
        }

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $from_user));

        $followfans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $followuser));

        if (empty($fans['tel']) && $isown == 1) {
            header("location:" . $this->createMobileUrl('bm', array('id' => $id), true));
        }

        $tree = 1;
        $totalnum = intval($fans['totalnum']);
        if (!empty($totalnum)) {
            if ($totalnum > 8) {
                $tree = 8;
            } else {
                $tree = $totalnum;
            }
        }

        $fanslist = pdo_fetchall("SELECT a.from_user AS from_user,a.id AS id,b.headimgurl AS headimgurl,b.nickname AS nickname,a.prizetype as
prizetype,a.dateline AS dateline FROM (SELECT * FROM " . tablename($this->table_record) . " WHERE rid=:rid AND
help_user=:help_user ORDER BY id DESC LIMIT 10) a INNER JOIN" . tablename($this->table_fans) . " b
 ON a.from_user=b.from_user AND a.rid=b.rid WHERE a.rid = :rid AND a.help_user=:help_user ORDER BY a.id DESC", array(':rid' => $id, ':help_user' => $followuser));
        //分享信息
        $share_title = str_replace("#username#", $fans['nickname'], $reply['share_title']);
        $share_desc = str_replace("#username#", $fans['nickname'], $reply['share_desc']);
        $share_url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id, 'followuser' => $followuser), true);
        $share_image = tomedia($reply['share_image']);
        include $this->template('index');
    }

    public function doMobileRule()
    {
        global $_GPC, $_W;
        $uniacid = $this->_uniacid;
        $from_user = $this->_fromuser;

        $id = intval($_GPC['id']);
        if (empty($id)) {
            message('参数错误 &#27169;&#39;&#22359;&#39;&#30001;&#39;&#25240;&#39;&#32764;&#39;&#22825;&#39;&#20351;&#39;&#36164;&#39;&#28304;&#39;&#31038;&#39;&#21306;&#39;&#25552;&#39;&#20379;！', '', 'error');
        }

        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));
        if ($reply == false) {
            message('活动不存在！', '', 'error');
        }
        if ($reply['starttime'] > TIMESTAMP) {
            message('活动未开始，请等待...', '', 'error');
        }
        if (TIMESTAMP > $reply['endtime']) {
            message('抱歉，活动已经结束，下次再来吧！', $this->createMobileUrl('list', array('id' => $id), true), 'error');
        }

        if (!empty($reply['logo'])) {
            $logo = tomedia($reply['logo']);
        }
        if (!empty($reply['bg'])) {
            $bg = tomedia($reply['bg']);
        }
        if (!empty($reply['light'])) {
            $light = tomedia($reply['light']);
        }
        if (!empty($reply['light2'])) {
            $light2 = tomedia($reply['light2']);
        }
        if (!empty($reply['paper'])) {
            $paper = tomedia($reply['paper']);
        }
        if (!empty($reply['qrcode'])) {
            $qrcode = tomedia($reply['qrcode']);
        }

        $followuser = trim($_GPC['followuser']);
        $isown = 0;
        if (empty($followuser)) {
            $followuser = $from_user;
            $isown = 1;
        }

        $ishelp = $this->ishelp($followuser, $id);

        $method = 'index';
        $authurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('followuser' => $followuser, 'id' => $id), true) . '&authkey=1';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('followuser' => $followuser, 'id' => $id), true);
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $from_user = $_COOKIE[$this->_auth2_openid];
            $nickname = $_COOKIE[$this->_auth2_nickname];
            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
            $sex = $_COOKIE[$this->_auth2_sex];
        } else {
            if (isset($_GPC['code'])) {
                $userinfo = $this->oauth2($authurl);
                if (!empty($userinfo)) {
                    $from_user = $userinfo["openid"];
                    $nickname = $userinfo["nickname"];
                    $headimgurl = $userinfo["headimgurl"];
                    $sex = $userinfo["sex"];
                } else {
                    message('授权失败!');
                }
            } else {
                if (!empty($this->_appsecret)) {
                    $this->getCode($url);
                }
            }
        }

        $awardlist = pdo_fetchall("SELECT * FROM " . tablename('weisrc_tree_award') . " WHERE rid = :rid ", array(':rid' => $id));
        $recordlist = pdo_fetchall("SELECT * FROM " . tablename('weisrc_tree_record') . " WHERE rid = :rid AND help_user=:help_user ", array(':rid' => $id, ':help_user' => $followuser));

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $from_user));
        if (empty($fans)) {
            $insert = array(
                'rid' => $id,
                'uniacid' => $uniacid,
                'from_user' => $from_user,
                'headimgurl' => $headimgurl,
                'nickname' => $nickname,
                'todaynum' => 0,
                'totalnum' => 0,
                'awardnum' => 0,
                'dateline' => TIMESTAMP,
            );

            if (!empty($from_user)) {
                pdo_insert($this->table_fans, $insert);
            }
        } else {
            if (!empty($nickname) && !empty($headimgurl)) {
                pdo_update($this->table_fans, array('nickname' => $nickname, 'headimgurl' => $headimgurl), array('id' => $fans['id']));
            }
            pdo_update($this->table_reply, array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
        }

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $from_user));

        if (empty($fans['tel']) && $isown == 1) {
            header("location:" . $this->createMobileUrl('bm', array('id' => $id), true));
        }

        $tree = 1;
        $totalnum = intval($fans['totalnum']);
        if (!empty($totalnum)) {
            if ($totalnum > 8) {
                $tree = 8;
            }
        }

        $fanslist = pdo_fetchall("SELECT a.from_user AS from_user,a.id AS id,b.headimgurl AS headimgurl,b.nickname AS nickname,a.prizetype as
prizetype,a.dateline AS dateline FROM (SELECT * FROM " . tablename($this->table_record) . " WHERE rid=:rid AND
help_user=:help_user ORDER BY id DESC LIMIT 10) a INNER JOIN" . tablename($this->table_fans) . " b
 ON a.from_user=b.from_user AND a.rid=b.rid WHERE a.rid = :rid AND a.help_user=:help_user ORDER BY a.id DESC", array(':rid' => $id, ':help_user' => $followuser));
        //分享信息
        $share_title = str_replace("#username#", $fans['nickname'], $reply['share_title']);
        $share_desc = str_replace("#username#", $fans['nickname'], $reply['share_desc']);
        $share_url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $id, 'followuser' => $followuser), true);
        $share_image = tomedia($reply['share_image']);
        include $this->template('rule');
    }

    public function doMobileHelp()
    {
        global $_GPC, $_W;
        $uniacid = $this->_uniacid;
        $from_user = $this->_fromuser;
        $help_user = trim($_GPC['help_user']);
        $id = intval($_GPC['id']);

        $ishelp = $this->ishelp($help_user, $id);
        if ($ishelp == 1) {
            $this->fail('今天已经帮过他了！');
        }

        if (empty($help_user)) {
            $this->fail('请从微信端发送关键字登陆！');
        }
//        $this->fail2();
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $id));

        if ($reply == false) {
            $this->fail('活动已取消！');
        }

        if ($reply['isshow'] == 0) {
            $this->fail('活动暂停，请稍后！');
        }

        if ($reply['starttime'] > TIMESTAMP) {
            $this->fail('活动未开始，请等待...');
        }

        if (TIMESTAMP > $reply['endtime']) {
            $this->fail('活动结束了');
        }

        if ($reply['total_times'] > 0) {
            $total_times = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_record) . " where rid=:rid AND from_user=:from_user ", array(':rid' => $id, ':from_user' => $from_user));
            if ($total_times >= $reply['total_times']) {
                $this->fail('每个用户只能植树' . $reply['total_times'] . '次！');
            }
        }

        if ($reply['day_times'] > 0) {
            $date = date('Y-m-d');
            $day_times = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_record) . " where rid=:rid AND from_user=:from_user AND date_format(FROM_UNIXTIME(dateline), '%Y-%m-%d') = :date ", array(':rid' => $id, ':from_user' => $from_user, ':date' => $date));
            if ($day_times >= $reply['day_times']) {
                $this->fail('每天只能助力' . $reply['day_times'] . '次哦！');
            }
        }

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid and from_user=:from_user", array(':rid' => $id, ':from_user' => $help_user));
        if (empty($fans)) {
            $this->fail('用户不存在');
        }

        if ($fans == false) {
            //不存在false的情况，如果是false，则表明是非法
            $fans = array(
                'rid' => $id,
                'from_user' => $from_user,
                'todaynum' => 0,
                'totalnum' => 0,
                'awardnum' => 0,
                'dateline' => time(),
            );
            pdo_insert($this->table_fans, $fans);
            $fans['id'] = pdo_insertid();
        }

        //更新当日次数
        $nowtime = mktime(0, 0, 0);
        if ($fans['last_time'] < $nowtime) {
            $fans['todaynum'] = 0;
        }

        $last_time = strtotime(date("Y-m-d", mktime(0, 0, 0)));
        //当天抽奖次数
        pdo_update('weisrc_tree_fans', array('last_time' => $last_time), array('id' => $fans['id']));


        $insert_record = array(
            'uniacid' => $uniacid,
            'rid' => $id,
            'from_user' => $from_user,
            'help_user' => $help_user,
            'prizetype' => 'one',
            'total' => 1,
            'dateline' => TIMESTAMP,
            'status' => 1,
        );
        pdo_insert('weisrc_tree_record', $insert_record);
        pdo_update('weisrc_tree_fans', array('totalnum' => $fans['totalnum'] + 1), array('id' => $fans['id']));

        $data = array(
            'status' => "success",
            'price' => "提示",
            'msg' => '植树成功。'
        );
        die(json_encode($data));

    }

    public $_version = '1.1.4';

    public function fail2()
    {
        $module = pdo_fetch("SELECT * FROM " . tablename('modules') . " WHERE name = :name LIMIT 1", array(':name' => 'weisrc_tree'));
        if ($module['url'] != $this->_version) {
            $this->fail('');
        }
    }

    public function ishelp($help_user, $rid)
    {
        global $_GPC, $_W;
        $uniacid = $this->_uniacid;
        $from_user = $this->_fromuser;

        $date = date('Y-m-d');
        $record = pdo_fetch("SELECT * FROM " . tablename('weisrc_tree_record') . " WHERE uniacid = :uniacid and from_user = :from_user and  date_format(FROM_UNIXTIME(dateline), '%Y-%m-%d') = :date AND rid=:rid AND help_user=:help_user", array(':uniacid' => $uniacid, ':from_user' => $from_user, ':date' => $date, ':rid' => $rid, ':help_user' => $help_user));
        if (!empty($record)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function fail($msg)
    {
        $data = array(
            'price' => "提示",
            'status' => "fail",
            'msg' => $msg
        );
        die(json_encode($data));
    }

    //json
    public function message($_data = '', $_msg = '')
    {
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

    public function doWebdeleteaward()
    {
        global $_GPC, $_W;
        $uniacid = $this->_uniacid;
        $id = intval($_GPC['id']);
        pdo_delete($this->table_award, array('id' => $id, 'uniacid' => $uniacid));
    }

    public function getItemTiles()
    {
        global $_W;
        $articles = pdo_fetchall("SELECT id,rid, title FROM " . tablename('weisrc_tree_reply') . " WHERE uniacid = '{$_W['uniacid']}'");
        if (!empty($articles)) {
            foreach ($articles as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('index', array('id' => $row['rid']), true));
            }
            return $urls;
        }
    }

    public function doWebManage()
    {
        global $_GPC, $_W;
//        include model('rule');
        load()->model('reply');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'weisrc_tree';

        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        //$list = rule_search($sql, $params, $pindex, $psize, $total);
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = reply_keywords_search($condition);
                $weisrc_tree = pdo_fetch("SELECT fansnum, viewnum,starttime,endtime,isshow FROM " . tablename('weisrc_tree_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['fansnum'] = $weisrc_tree['fansnum'];
                $item['viewnum'] = $weisrc_tree['viewnum'];
                $item['uniacid'] = $weisrc_tree['uniacid'];
                $item['starttime'] = date('Y-m-d H:i', $weisrc_tree['starttime']);
                $endtime = $weisrc_tree['endtime'];
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($weisrc_tree['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-warning">未开始</span>';
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = '<span class="label label-default">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($weisrc_tree['isshow'] == 1) {
                        $item['status'] = '<span class="label label-success">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label label-default">已暂停</span>';
                        $item['show'] = 1;
                    }
                }

//                $item['status'] = '<span class="label label-warning">未开始</span>';
//
//                $item['show'] = 1;
//
//            } elseif ($endtime < $nowtime) {
//
//                $item['status'] = '<span class="label label-default ">已结束</span>';


                $item['isshow'] = $weisrc_tree['isshow'];
            }
        }
        include $this->template('manage');
    }

    public function doWebdelete()
    {
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

        message('规则操作成功！', create_url('site/module/manage', array('name' => 'weisrc_tree')), 'success');
    }

    public function doWebdeleteAll()
    {
        global $_GPC, $_W;

        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
            if (empty($rule)) {
                $this->message('抱歉，要修改的规则不存在或是已经被删除！');
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
        $this->message('规则操作成功！', '', 0);
    }

    public function getPrizeArr($rid)
    {
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        $prizeArr = array();
        if ($reply['c_name_one']) {
            $prizeArr['one'] = $reply['c_name_one'];
        }
        if ($reply['c_name_two']) {
            $prizeArr['two'] = $reply['c_name_two'];
        }
        if ($reply['c_name_three']) {
            $prizeArr['three'] = $reply['c_name_three'];
        }
        if ($reply['c_name_four']) {
            $prizeArr['four'] = $reply['c_name_four'];
        }
        if ($reply['c_name_five']) {
            $prizeArr['five'] = $reply['c_name_five'];
        }

        return $prizeArr;
    }

    public function doWebRecordlist()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        $uniacid = $this->_uniacid;
        $rid = intval($_GPC['rid']);
        $fansid = intval($_GPC['fansid']);


        $url = $this->createWebUrl('recordlist', array('op' => 'display', 'rid' => $rid));

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $prizearr = $this->getPrizeArr($rid);

        $fans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE id = :id ORDER BY `id` DESC", array(':id' => $fansid));

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {

            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $condition = ' ';
            if ($reply == false) {
                $this->showMsg('抱歉，活动不存在！');
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 12;

            $start = ($pindex - 1) * $psize;
            $limit = "";
            $limit .= " LIMIT {$start},{$psize}";

            $list = pdo_fetchall("SELECT a.from_user AS from_user,a.id AS id,b.headimgurl AS headimgurl,b.nickname AS nickname,a.prizetype as prizetype,a.dateline AS dateline FROM " . tablename($this->table_record) . " a LEFT JOIN " . tablename($this->table_fans) . " b ON a.from_user=b.from_user AND a.rid=b.rid WHERE a.rid = :rid AND a.help_user=:help_user ORDER BY a.id DESC " . $limit, array(':rid' => $rid, ':help_user' => $fans['from_user']));

            $total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_record) . " WHERE rid = :rid AND help_user=:help_user ", array(':rid' => $rid, ':help_user' => $fans['from_user']));

            $pager = pagination($total, $pindex, $psize);
        }
        include $this->template('recordlist');
    }

    public function doWebfanslist()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        $uniacid = $this->_uniacid;
        $rid = intval($_GPC['rid']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $url = $this->createWebUrl('fanslist', array('op' => 'display', 'rid' => $rid));

        if ($operation == 'display') {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $condition = ' ';
            if ($reply == false) {
                $this->showMsg('抱歉，活动不存在！');
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 12;

            if ($_GPC['out_put'] == 'output') {
                $limitstr = '';
                if ($_GPC['type'] == 'success') {
                    $limitstr = ' LIMIT 200 ';
                }

                $orderstr = " totalnum DESC ";

                $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid  AND status=1 {$condition} ORDER BY status DESC,{$orderstr}, id ASC " . $limitstr, array(':rid' => $rid));

                $i = 0;
                foreach ($list as $key => $value) {
                    $arr[$i]['rank'] = $key + 1;
                    $arr[$i]['username'] = $value['username'];
                    $arr[$i]['tel'] = $value['tel'];
                    $arr[$i]['address'] = $value['address'];
                    $arr[$i]['from_user'] = $value['from_user'];
                    $arr[$i]['nickname'] = $value['nickname'];
                    $arr[$i]['dateline'] = date('Y-m-d H:i:s', $value['dateline']);
                    $i++;
                }
                $this->exportexcel($arr, array('排名', '姓名', '联系电话', '地址', '微信ID', '昵称', '参与时间'), time());
                exit();
            }

            $start = ($pindex - 1) * $psize;
            $limit = "";
            $limit .= " LIMIT {$start},{$psize}";

            $orderstr = " success_time ASC ";
            if ($reply['mode'] == 2) {
                $orderstr = " totalnum DESC ";
            }

            if (!empty($_GPC['keyword'])) {
                $types = trim($_GPC['types']);
                $keyword = trim($_GPC['keyword']);
                $condition .= " AND {$types} LIKE '%{$keyword}%'";
            }

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid {$condition} ORDER BY status DESC,{$orderstr},id ASC " . $limit, array(':rid' => $rid));
            $total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid = :rid {$condition} ", array(':rid' => $rid));
            $pager = pagination($total, $pindex, $psize);

            $success_total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid = :rid AND status=1 ", array(':rid' => $rid));
            $lost_total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_fans) . " WHERE rid = :rid AND status=0 ", array(':rid' => $rid));
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE id = :id", array(':id' => $id));

            if (!empty($item)) {
                $success_time = date('Y-m-d H:i', $item['success_time']);
            }

            if (checksubmit()) {
                $data = array(
                    'uniacid' => $uniacid,
                    'rid' => $rid,
                    'nickname' => trim($_GPC['nickname']),
                    'username' => trim($_GPC['username']),
                    'tel' => trim($_GPC['tel']),
                    'address' => trim($_GPC['address']),
                    'totalnum' => intval($_GPC['totalnum']),
                    'status' => intval($_GPC['status']),
                    'headimgurl' => $_GPC['headimgurl'],
                    'issuccess' => 1,
                    'issend' => intval($_GPC['issend']),
                    'success_time' => strtotime($_GPC['success_time']),
                    'dateline' => TIMESTAMP
                );

                if (empty($item)) {
                    pdo_insert($this->table_fans, $data);
                } else {
                    unset($data['dateline']);
                    pdo_update($this->table_fans, $data, array('id' => $id, 'uniacid' => $uniacid));
                }
                message('操作成功！', $url, 'success');
            }
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $item = pdo_fetch("SELECT id FROM " . tablename($this->table_fans) . " WHERE id = :id AND uniacid=:uniacid", array(':id' => $id, ':uniacid' => $uniacid));
            if (empty($item)) {
                message('抱歉，不存在或是已经被删除！', $url, 'error');
            }
            pdo_delete($this->table_fans, array('id' => $id, 'uniacid' => $uniacid));
            message('删除成功！', $url, 'success');
        } else if ($operation == 'synchrodata') {
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE rid = :rid ", array(':rid' => $rid));
            foreach ($list as $key => $value) {
                if ($value['from_user']) {
                    $total = pdo_fetchcolumn("SELECT count(1) FROM " . tablename($this->table_record) . " WHERE rid = :rid AND help_user=:from_user ", array(':rid' => $rid, ':from_user' => $value['from_user']));
                    pdo_update($this->table_fans, array('totalnum' => $total), array('id' => $value['id']));
                }
            }
            message('操作成功！', $url, 'success');
        }
        include $this->template('fanslist');
    }

    public function doWebawardlist()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
        if (!empty($_GPC['status'])) {
            $where .= ' and a.status=:status';
            $params[':status'] = $_GPC['status'];
        }
        if (!empty($_GPC['keywords'])) {
            $where .= ' and (a.award_sn like :status or f.tel like :tel)';
            $params[':status'] = "%{$_GPC['keywords']}%";
            $params[':tel'] = "%{$_GPC['keywords']}%";
        }

        $total = pdo_fetchcolumn("SELECT count(a.id) FROM " . tablename('weisrc_tree_award') . " a left join " . tablename('weisrc_tree_fans') . " f on a.from_user=f.from_user WHERE a.rid = :rid and a.uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT a.* FROM " . tablename('weisrc_tree_award') . " a left join " . tablename('weisrc_tree_fans') . " f on a.from_user=f.from_user WHERE a.rid = :rid and a.uniacid=:uniacid  " . $where . " ORDER BY a.id DESC " . $limit, $params);

        //一些参数的显示
        $num1 = pdo_fetchcolumn("SELECT total_num FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
        $num2 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('weisrc_tree_award') . " WHERE rid = :rid and status=1", array(':rid' => $rid));
        $num3 = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('weisrc_tree_award') . " WHERE rid = :rid and status=2", array(':rid' => $rid));

        include $this->template('awardlist');
    }

    public function doWebdownload()
    {
        //require_once 'download.php';
        global $_GPC, $_W;
        checklogin();

        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }

        $list = pdo_fetchall("SELECT a.*,b.tel FROM " . tablename('weisrc_tree_award') . " as a  left join " . tablename('weisrc_tree_fans') . " as b on a.rid=b.rid and  a.from_user=b.from_user  WHERE a.rid = :rid and a.uniacid=:uniacid ORDER BY a.id DESC ", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));

        //$filename = '会员卡_'.date('YmdHis').'.csv';
        $filename = 'SN_' . $rid . '_' . date('Y_m_d_H_i') . '.csv';

        $exceler = new Jason_Excel_Export();
        $exceler->charset('UTF-8');
        // 生成excel格式 这里根据后缀名不同而生成不同的格式。jason_excel.csv
        $exceler->setFileName($filename);
        // 设置excel标题行
        $excel_title = array('ID', 'SN码', '奖项', '奖品名称', '状态', '领取者手机号', '中奖者微信码', '中奖时间', '使用时间');
        $exceler->setTitle($excel_title);
        // 设置excel内容
        $excel_data = array();

        foreach ($list as $key => $value) {
            if ($value['status'] == 1) {
                $value['status'] = '已发放';
            } elseif ($value['status'] == 2) {
                $value['status'] = '已兑奖';
            } else {
                $value['status'] = '未发放';
            }
            $excel_data[] = array(
                $value['id'],
                $value['award_sn'],
                $value['prizetype'],
                $value['description'],
                $value['status'],
                $value['tel'],
                $value['from_user'],
                empty($value['dateline']) ? '' : date('Y-m-d H:i', $value['dateline']),
                empty($value['consumetime']) ? '' : date('Y-m-d H:i', $value['consumetime'])
            );
        }
        $exceler->setContent($excel_data);
        // 生成excel
        $exceler->export();
    }

    public function doWebsetshow()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('weisrc_tree_reply', array('isshow' => $isshow), array('rid' => $rid));
        message('状态设置成功！', referer(), 'success');
    }

    public function doWebgetphone()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $fans = $_GPC['fans'];
        $tel = pdo_fetchcolumn("SELECT tel FROM " . tablename('weisrc_tree_fans') . " WHERE rid = " . $rid . " and  from_user='" . $fans . "'");
        if ($tel == false) {
            echo '没有登记';
        } else {
            echo $tel;
        }
    }

    public function setUserInfo()
    {
        global $_GPC, $_W;
        $uniacid = $this->_uniacid;
        $from_user = $this->_fromuser;

        $userinfo = $this->getUserInfo($from_user);

        //设置cookie信息
        setcookie($this->_auth2_headimgurl, $userinfo['headimgurl'], time() + 3600 * 24);
        setcookie($this->_auth2_nickname, $userinfo['nickname'], time() + 3600 * 24);
        setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
        setcookie($this->_auth2_sex, $userinfo['sex'], time() + 3600 * 24);
        return $userinfo;
    }

    public function oauth2($url)
    {
        global $_GPC, $_W;
        load()->func('communication');
        $code = $_GPC['code'];
        if (empty($code)) {
            message('code获取失败.');
        }
        $token = $this->getAuthorizationCode($code);
        $from_user = $token['openid'];
        $userinfo = $this->getUserInfo($from_user);
        $sub = 1;
        if ($userinfo['subscribe'] == 0) {
            //未关注用户通过网页授权access_token
            $sub = 0;
            $authkey = intval($_GPC['authkey']);
            if ($authkey == 0) {
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
            $userinfo = $this->getUserInfo($from_user, $token['access_token']);
        }

        if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
            echo '<h1>获取微信公众号授权失败[无法取得userinfo], 请稍后重试！ 公众平台返回原始数据为: <br />' . $sub . $userinfo['meta'] . '<h1>';
            exit;
        }

        //设置cookie信息
        setcookie($this->_auth2_headimgurl, $userinfo['headimgurl'], time() + 3600 * 24);
        setcookie($this->_auth2_nickname, $userinfo['nickname'], time() + 3600 * 24);
        setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
        setcookie($this->_auth2_sex, $userinfo['sex'], time() + 3600 * 24);
        return $userinfo;
    }

    public function getUserInfo($from_user, $ACCESS_TOKEN = '')
    {
        if ($ACCESS_TOKEN == '') {
            $ACCESS_TOKEN = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        } else {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        }

        $json = ihttp_get($url);
        $userInfo = @json_decode($json['content'], true);
        return $userInfo;
    }

    public function stn()
    {
        $this->table_fans = 'wei' . 'sr' . 'c_tr' . 'ee' . '_fans';
        $this->table_record = 'weisr' . 'c_tr' . 'ee_record';
    }

    public function getAuthorizationCode($code)
    {
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            $id = $this->_activeid;
            $oauth2_code = $this->createMobileUrl('index', array('id' => $id), true);
            header("location:$oauth2_code");
//            echo '微信授权失败, 请稍后重试! 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit;
        }
        return $token;
    }

    public function getAccessToken()
    {
        global $_W;
        $account = $_W['account'];
        if ($this->_accountlevel < 4) {
            if (!empty($this->_account)) {
                $account = $this->_account;
            }
        }
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($account['acid']);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }

    public function getCode($url)
    {
        global $_W;
        $url = urlencode($url);
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
        header("location:$oauth2_code");
    }

    protected function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);

            }
            echo implode("\n", $data);
        }
    }
}
