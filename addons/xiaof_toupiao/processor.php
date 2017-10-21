<?php
defined('IN_IA') or exit('Access Denied');
class Xiaof_toupiaoModuleProcessor extends WeModuleProcessor
{
    private $l1l1llll1l11l1l11ll1lllll1l11ll;
    public function respond()
    {
        global $_W;
        $this->ll111l1l1ll11l1ll1llll1ll111ll1();
        if ($this->message['type'] == 'text') {
            $l11l111l1111l11llllllll1l11llll = $this->message['content'];
            if (!empty($this->message['eventkey'])) {
                if ($this->message['event'] == 'SCAN') {
                    $l1l1111l111llll1ll1111l11ll111l = " `qrcid` = '" . $this->message['eventkey'] . "'";
                    $l11l111l1111l11llllllll1l11llll = pdo_fetchcolumn("SELECT `keyword` FROM " . tablename('qrcode') . " WHERE {$l1l1111l111llll1ll1111l11ll111l} AND `uniacid` = '{$_W['uniacid']}' limit 1");
                } else {
                    $l11l111l1111l11llllllll1l11llll = $this->message['eventkey'];
                }
            }
            if ($llll1l111lll1l1ll1l11ll111l1111 = pdo_fetch("SELECT * FROM " . tablename('xiaof_toupiao_rule') . " WHERE `uniacid` = '" . $_W['uniacid'] . "' AND `keyword` = '" . md5($l11l111l1111l11llllllll1l11llll) . "' limit 1")) {
                $this->lll1l1lllll1111ll1l1ll1l11l11ll();
            } elseif ($llll1l111lll1l1ll1l11ll111l1111 = pdo_fetch("SELECT * FROM " . tablename('xiaof_toupiao_rule') . " WHERE `rid` = '" . $this->rule . "' limit 1")) {
            } else {
                return $this->respText("系统没有找到您要参与的活动");
            }
            if ($l11l111l1111l11llllllll1l11llll == '退出' or $l11l111l1111l11llllllll1l11llll == 'quit') {
                $this->lll1l1lllll1111ll1l1ll1l11l11ll();
                $this->endContext();
                return $this->respText("缓存已清除");
            }
            if (empty($_SESSION['xiaofsid']) or $llll1l111lll1l1ll1l11ll111l1111['sid'] != $_SESSION['xiaofsid']) {
                if ($llll1l111llll1l1ll111111ll1ll1l = pdo_fetch("SELECT `id`,`tit`,`data` FROM " . tablename("xiaof_toupiao_setting") . " WHERE `id` = :id", array(
                    ":id" => $llll1l111lll1l1ll1l11ll111l1111['sid']
                ))) {
                    $lll1lll1lll1l11ll1ll1l1ll1l1lll = $_SESSION['xiaofsid'] = $llll1l111llll1l1ll111111ll1ll1l['id'];
                } else {
                    return $this->respText("系统没有找到您要参与的活动。");
                }
            } else {
                $lll1lll1lll1l11ll1ll1l1ll1l1lll = intval($_SESSION['xiaofsid']);
                $llll1l111llll1l1ll111111ll1ll1l = pdo_fetch("SELECT `id`,`tit`,`data` FROM " . tablename("xiaof_toupiao_setting") . " WHERE `id` = :id", array(
                    ":id" => $lll1lll1lll1l11ll1ll1l1ll1l1lll
                ));
            }
            $this->mysetting = $l11ll11lllllll1l1llll11ll11ll1l = iunserializer($llll1l111llll1l1ll111111ll1ll1l['data']);
            if (!empty($_SESSION['xiaofprocess'])) {
                $l1llll1l1l11lll1111111lll1l1l11 = iunserializer($_SESSION['xiaofprocess']);
                if (isset($l1llll1l1l11lll1111111lll1l1l11['vote'])) {
                    if (isset($l1llll1l1l11lll1111111lll1l1l11['join'])) {
                        unset($l1llll1l1l11lll1111111lll1l1l11['join']);
                    }
                    switch ($l1llll1l1l11lll1111111lll1l1l11['vote']['step']) {
                        case 1:
                            if (intval($l11ll11lllllll1l1llll11ll11ll1l['openmsgvote']) >= 1) {
                                return $this->respText("系统关闭了回复投票，请进活动页投票。<a href='" . $this->l1111llll1l1llll11l1lll1l11l1ll('index') . "'>点击进入</a>");
                            }
                            if (!$llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `uid` = :uid", array(
                                ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                                ":uid" => intval($l11l111l1111l11llllllll1l11llll)
                            ))) {
                                return $this->respText("系统没有找到您要投票的选手编号。请重新输入。");
                            }
                            if (intval($l11ll11lllllll1l1llll11ll11ll1l['votejump']) == 1) {
                                $l1ll1l1lll111l1111l11lllllll111 = array(
                                    'vote' => array(
                                        'step' => 1
                                    )
                                );
                                $_SESSION['xiaofprocess']        = iserializer($l1ll1l1lll111l1111l11lllllll111);
                                return $this->l11ll1lll11111llllll111l11l1lll('我是：' . $llll1l11l11111l1l111l11l1ll1111['name'] . '，编号：' . $llll1l11l11111l1l111l11l1ll1111['uid'] . '。点击进入为我投票吧！', $llll1l11l11111l1l111l11l1ll1111['pic'], $llll1l11l11111l1l111l11l1ll1111['id']);
                            }
                            $l1ll11ll11l111l1l1l1ll11l1ll1ll                 = rand(1000, 9999);
                            $l1llll1l1l11lll1111111lll1l1l11['vote']['data'] = array(
                                'rand' => $l1ll11ll11l111l1l1l1ll11l1ll1ll,
                                'pid' => $llll1l11l11111l1l111l11l1ll1111['id']
                            );
                            $l1llll1l1l11lll1111111lll1l1l11['vote']['step'] = 2;
                            $_SESSION['xiaofprocess']                        = iserializer($l1llll1l1l11lll1111111lll1l1l11);
                            return $this->respText("为防刷票请回复四位验证码：" . $l1ll11ll11l111l1l1l1ll11l1ll1ll . "");
                            break;
                        case 2:
                            $l11111ll11l11lll111l1l1l1lll1ll = $l1llll1l1l11lll1111111lll1l1l11['vote']['data'];
                            if ($l11l111l1111l11llllllll1l11llll != $l11111ll11l11lll111l1l1l1lll1ll['rand']) {
                                $l1ll11ll11l111l1l1l1ll11l1ll1ll                         = rand(1000, 9999);
                                $l1llll1l1l11lll1111111lll1l1l11['vote']['data']['rand'] = $l1ll11ll11l111l1l1l1ll11l1ll1ll;
                                $l1llll1l1l11lll1111111lll1l1l11['vote']['step']         = 2;
                                $_SESSION['xiaofprocess']                                = iserializer($l1llll1l1l11lll1111111lll1l1l11);
                                return $this->respText("验证码错误。\n请重新回复四位验证码：" . $l1ll11ll11l111l1l1l1ll11l1ll1ll . "");
                            } else {
                                if (!$llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id", array(
                                    ":id" => intval($l11111ll11l11lll111l1l1l1lll1ll['pid'])
                                ))) {
                                    $l1ll1l1lll111l1111l11lllllll111 = array(
                                        'vote' => array(
                                            'step' => 1
                                        )
                                    );
                                    $_SESSION['xiaofprocess']        = iserializer($l1ll1l1lll111l1111l11lllllll111);
                                    return $this->respText("系统没有找到您要投票的选手编号。请重新输入");
                                }
                                if ($l11ll11lllllll1l1llll11ll11ll1l['verify'] == 1 && $llll1l11l11111l1l111l11l1ll1111['verify'] != 1) {
                                    if ($llll1l11l11111l1l111l11l1ll1111['verify'] == 0) {
                                        unset($_SESSION['xiaofprocess']);
                                        return $this->respText("该选手作品正在审核，暂不接受投票");
                                    }
                                }
                                if ($llll1l11l11111l1l111l11l1ll1111['verify'] == 2) {
                                    unset($_SESSION['xiaofprocess']);
                                    return $this->respText("该选手作品审核未通过，不接受投票");
                                }
                                if ($llll1l11l11111l1l111l11l1ll1111['verify'] == 3) {
                                    if ($llll1l11l11111l1l111l11l1ll1111['locking_at'] >= time() or intval($l11ll11lllllll1l1llll11ll11ll1l['releasetime']) == 0) {
                                        return $this->respText("系统检测该选手投票数据异常，已自动锁定，不在接受投票。");
                                    } else {
                                        pdo_update("xiaof_toupiao", array(
                                            'verify' => '0',
                                            'locking_at' => '0'
                                        ), array(
                                            "id" => $llll1l11l11111l1l111l11l1ll1111['id']
                                        ));
                                    }
                                }
                                if (intval($l11ll11lllllll1l1llll11ll11ll1l['limitone']) >= 1) {
                                    $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xiaof_toupiao_log") . " WHERE `sid` = :sid AND `pid` = :pid AND `openid` = :openid", array(
                                        ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                                        ":pid" => $llll1l11l11111l1l111l11l1ll1111['id'],
                                        ":openid" => $_W['openid']
                                    ));
                                    if ($ll11llll1l1lll11l111lll111111ll >= intval($l11ll11lllllll1l1llll11ll11ll1l['limitone'])) {
                                        unset($_SESSION['xiaofprocess']);
                                        return $this->respText("本次活动期间您对选手编号" . $llll1l11l11111l1l111l11l1ll1111['uid'] . "允许最大投票数达到上限，不能再继续给Ta投票");
                                    }
                                }
                                if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_log") . " WHERE `sid` = :sid AND `pid` = :pid AND `openid` = :openid AND `unique_at` = :unique_at", array(
                                    ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                                    ":pid" => $llll1l11l11111l1l111l11l1ll1111['id'],
                                    ":openid" => $_W['openid'],
                                    ":unique_at" => date("Ymd")
                                ))) {
                                    unset($_SESSION['xiaofprocess']);
                                    return $this->respText("您今天已经给编号" . $llll1l11l11111l1l111l11l1ll1111['uid'] . "投过票了，明天再来吧");
                                }
                                if (intval($l11ll11lllllll1l1llll11ll11ll1l['maxvotenum']) >= 1) {
                                    $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $lll1lll1lll1l11ll1ll1l1ll1l1lll . "' AND `openid` = '" . $_W['openid'] . "'");
                                    if ($ll11llll1l1lll11l111lll111111ll >= $l11ll11lllllll1l1llll11ll11ll1l['maxvotenum']) {
                                        unset($_SESSION['xiaofprocess']);
                                        return $this->respText("本次活动您共有" . $l11ll11lllllll1l1llll11ll11ll1l['maxvotenum'] . "票，已经用完，不能再投");
                                    }
                                }
                                if (intval($l11ll11lllllll1l1llll11ll11ll1l['maxgoodnum']) >= 1) {
                                    if (time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['maxgoodtime']) && $llll1l11l11111l1l111l11l1ll1111['good'] >= $l11ll11lllllll1l1llll11ll11ll1l['maxgoodnum']) {
                                        unset($_SESSION['xiaofprocess']);
                                        return $this->respText("本次活动" . $l11ll11lllllll1l1llll11ll11ll1l['maxgoodtime'] . "之前，每位选手最多允许被投" . $l11ll11lllllll1l1llll11ll11ll1l['maxgoodnum'] . "票，超出无效");
                                    }
                                }
                                $l11l111l11ll1ll1l11lll11lll11l1 = empty($_W['fans']['fanid']) ? 0 : $_W['fans']['fanid'];
                                pdo_insert("xiaof_toupiao_log", array(
                                    "sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                                    "fanid" => $l11l111l11ll1ll1l11lll11lll11l1,
                                    "nickname" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('nickname'),
                                    "avatar" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('avatar'),
                                    "pid" => $llll1l11l11111l1l111l11l1ll1111['id'],
                                    "openid" => $_W['openid'],
                                    "ip" => ip2long(CLIENT_IP),
                                    "unique_at" => date("Ymd"),
                                    "created_at" => time()
                                ));
                                $llllll1llll111l1llll1lllll1llll = 1;
                                if (intval($l11ll11lllllll1l1llll11ll11ll1l['openvirtualclick']) >= 1) {
                                    $ll11111ll1lll11111111l1l1l11l11 = rand(1, 10);
                                    $llllll1llll111l1llll1lllll1llll = $llllll1llll111l1llll1lllll1llll + $ll11111ll1lll11111111l1l1l11l11;
                                }
                                pdo_query("UPDATE " . tablename("xiaof_toupiao") . " SET `good` = good+1, `click` = click+" . $llllll1llll111l1llll1lllll1llll . ", `updated_at` = '" . time() . "' WHERE `id` = '" . $llll1l11l11111l1l111l11l1ll1111['id'] . "'");
                                mc_credit_update($_W['member']['uid'], 'credit1', intval($l11ll11lllllll1l1llll11ll11ll1l['votecredit']), array(
                                    1,
                                    '男神女神投票赠送积分',
                                    'system'
                                ));
                                unset($_SESSION['xiaofprocess']);
                                return $this->l1ll111l1ll1l11llll1lll1lllll11($llll1l11l11111l1l111l11l1ll1111['uid'], $llll1l11l11111l1l111l11l1ll1111['name'], $llll1l11l11111l1l111l11l1ll1111['pic']);
                            }
                            break;
                    }
                } elseif (isset($l1llll1l1l11lll1111111lll1l1l11['join'])) {
                    if (isset($l1llll1l1l11lll1111111lll1l1l11['vote'])) {
                        unset($l1llll1l1l11lll1111111lll1l1l11['vote']);
                    }
                    switch ($l1llll1l1l11lll1111111lll1l1l11['join']['step']) {
                        case 1:
                            if ($l11l111l1111l11llllllll1l11llll == "") {
                                return $this->respText("名称不能为空！且填写后不允许修改。\n请重新输入");
                            }
                            $l1llll1l1l11lll1111111lll1l1l11['join']['data'] = array(
                                'name' => $l11l111l1111l11llllllll1l11llll
                            );
                            $l1llll1l1l11lll1111111lll1l1l11['join']['step'] = 2;
                            $_SESSION['xiaofprocess']                        = iserializer($l1llll1l1l11lll1111111lll1l1l11);
                            return $this->respText("输入手机号码");
                            break;
                        case 2:
                            if (!$this->l1l1lll11l11l1l11l111lll11111l1($l11l111l1111l11llllllll1l11llll)) {
                                return $this->respText("不是正确的手机号，手机号填写后不允许修改。\n请重新输入");
                            }
                            if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `phone` = :phone", array(
                                ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                                ":phone" => $l11l111l1111l11llllllll1l11llll
                            ))) {
                                return $this->respText("错误,一个手机号只能报名一次。\n请重新输入");
                            }
                            $l1llll1l1l11lll1111111lll1l1l11['join']['data']['phone'] = $l11l111l1111l11llllllll1l11llll;
                            $l1llll1l1l11lll1111111lll1l1l11['join']['step']          = 3;
                            $_SESSION['xiaofprocess']                                 = iserializer($l1llll1l1l11lll1111111lll1l1l11);
                            return $this->respText("请回复上传参赛的照片");
                            break;
                        case 4:
                            if (!isset($l1llll1l1l11lll1111111lll1l1l11['join']['data']['phone']) or !isset($l1llll1l1l11lll1111111lll1l1l11['join']['data']['name']) or !isset($l1llll1l1l11lll1111111lll1l1l11['join']['data']['pics'])) {
                                unset($l1llll1l1l11lll1111111lll1l1l11['join']['data']);
                                $l1llll1l1l11lll1111111lll1l1l11['join']['step'] = 1;
                                $_SESSION['xiaofprocess']                        = iserializer($l1llll1l1l11lll1111111lll1l1l11);
                                return $this->respText("资料错误，请重试或联系我们。\n重试请回复名称");
                            }
                            $lll1lll1ll1l11l1l1l1l11ll11l11l = $this->ll1l11111l1111111lllll11lll11l1(reset($l1llll1l1l11lll1111111lll1l1l11['join']['data']['pics']), 240);
                            $lll11l11lll1111l1lll1ll1lllll11 = array(
                                "sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                                "ip" => ip2long(CLIENT_IP),
                                "openid" => $_W['openid'],
                                "nickname" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('nickname'),
                                "avatar" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('avatar'),
                                "pic" => $lll1lll1ll1l11l1l1l1l11ll11l11l[1],
                                "phone" => $l1llll1l1l11lll1111111lll1l1l11['join']['data']['phone'],
                                "name" => $l1llll1l1l11lll1111111lll1l1l11['join']['data']['name'],
                                "created_at" => time(),
                                "updated_at" => time()
                            );
                            pdo_query("LOCK TABLES " . tablename("xiaof_toupiao") . " WRITE");
                            if (!$ll11l11llll11lll1ll111l1111ll1l = pdo_fetchcolumn("SELECT `uid` FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid ORDER BY `id` DESC limit 1", array(
                                ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll
                            ))) {
                                $ll11l11llll11lll1ll111l1111ll1l = 0;
                            }
                            $lll11l11lll1111l1lll1ll1lllll11['uid'] = $ll11l11llll11lll1ll111l1111ll1l + 1;
                            pdo_insert("xiaof_toupiao", $lll11l11lll1111l1lll1ll1lllll11);
                            $ll11111ll111l1llllllllll11l11l1 = pdo_insertid();
                            pdo_query("UNLOCK TABLES");
                            foreach ($l1llll1l1l11lll1111111lll1l1l11['join']['data']['pics'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                                $l111111111111ll1ll11ll1lll1ll11 = $this->ll1l11111l1111111lllll11lll11l1($ll111111l1ll11lll1l1l1111lll11l);
                                pdo_insert("xiaof_toupiao_pic", array(
                                    "sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                                    "pid" => $ll11111ll111l1llllllllll11l11l1,
                                    "url" => $l111111111111ll1ll11ll1lll1ll11[0],
                                    "thumb" => $l111111111111ll1ll11ll1lll1ll11[1],
                                    "created_at" => time()
                                ));
                            }
                            mc_credit_update($_W['member']['uid'], 'credit1', intval($l11ll11lllllll1l1llll11ll11ll1l['joincredit']), array(
                                1,
                                '男神女神报名赠送积分',
                                'system'
                            ));
                            unset($_SESSION['xiaofprocess']);
                            return $this->l1l1l1l1l1ll1l11l1lllll1l11l111($ll11l11llll11lll1ll111l1111ll1l + 1, $lll1lll1ll1l11l1l1l1l11ll11l11l[1], $ll11111ll111l1llllllllll11l11l1);
                            break;
                    }
                } else {
                    return $this->llll11ll1l111l1l1ll1111ll11ll1l($l11ll11lllllll1l1llll11ll11ll1l['replytitle'], $l11ll11lllllll1l1llll11ll11ll1l['replycontent'], $l11ll11lllllll1l1llll11ll11ll1l['replythumb']);
                }
            } else {
                if ($_SESSION['xiaofaction'] != $this->rule) {
                    $this->lll1l1lllll1111ll1l1ll1l11l11ll();
                    $_SESSION['xiaofaction'] = $this->rule;
                }
                if (isset($llll1l111lll1l1ll1l11ll111l1111['action'])) {
                    switch ($llll1l111lll1l1ll1l11ll111l1111['action']) {
                        case '0':
                            return $this->llll11ll1l111l1l1ll1111ll11ll1l($l11ll11lllllll1l1llll11ll11ll1l['replytitle'], $l11ll11lllllll1l1llll11ll11ll1l['replycontent'], $l11ll11lllllll1l1llll11ll11ll1l['replythumb']);
                            break;
                        case '1':
                            if ($l1ll11111l1l1lllllll111ll1l111l = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `openid` = :openid", array(
                                ":sid" => $llll1l111llll1l1ll111111ll1ll1l['id'],
                                ":openid" => $_W['openid']
                            ))) {
                                $this->lll1l1lllll1111ll1l1ll1l11l11ll();
                                return $this->l11ll1lll11111llllll111l11l1lll('您已参与本次活动！名称：' . $l1ll11111l1l1lllllll111ll1l111l['name'] . '，编号：' . $l1ll11111l1l1lllllll111ll1l111l['uid'], $l1ll11111l1l1lllllll111ll1l111l['pic'], $l1ll11111l1l1lllllll111ll1l111l['id']);
                            }
                            if (time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['joinstart'])) {
                                return $this->respText("活动报名未开始，请开始后再报名，开始时间" . $l11ll11lllllll1l1llll11ll11ll1l['joinstart'] . "");
                            }
                            if (time() >= strtotime($l11ll11lllllll1l1llll11ll11ll1l['joinend'])) {
                                return $this->respText("活动已结束，敬请期待下次活动");
                            }
                            if (!$this->inContext) {
                                $this->beginContext();
                            }
                            $lll11l1l11l1l11111111ll1ll1l111 = "报名请回复：您的名称";
                            $l1ll1l1lll111l1111l11lllllll111 = array(
                                'join' => array(
                                    'step' => 1
                                )
                            );
                            $_SESSION['xiaofprocess']        = iserializer($l1ll1l1lll111l1111l11lllllll111);
                            break;
                        case '2':
                            if (intval($l11ll11lllllll1l1llll11ll11ll1l['openmsgvote']) == 1) {
                                return $this->respText("系统关闭了回复投票，请进活动页投票。<a href='" . $this->l1111llll1l1llll11l1lll1l11l1ll('index') . "'>点击进入</a>");
                            }
                            if (time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['start'])) {
                                return $this->respText("活动未开始，请开始后再投票，开始时间" . $l11ll11lllllll1l1llll11ll11ll1l['start'] . "");
                            }
                            if (time() >= strtotime($l11ll11lllllll1l1llll11ll11ll1l['end'])) {
                                return $this->respText("活动已结束，敬请期待下次活动");
                            }
                            if ($l11ll11lllllll1l1llll11ll11ll1l['vnum'] >= 1) {
                                $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `openid` = '" . $_W['openid'] . "' AND `unique_at` = '" . date(Ymd) . "'");
                                if ($ll11llll1l1lll11l111lll111111ll >= $l11ll11lllllll1l1llll11ll11ll1l['vnum']) {
                                    return $this->respText("一个微信号每天只能给" . $l11ll11lllllll1l1llll11ll11ll1l['vnum'] . "个选手投票");
                                }
                            }
                            if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_safe") . " WHERE `sid` = :sid AND `ip` = :ip ", array(
                                ":sid" => $llll1l111llll1l1ll111111ll1ll1l['id'],
                                ":ip" => ip2long(CLIENT_IP)
                            ))) {
                                return $this->respText("抱歉，系统检测到您非正常投票，投票失败。还绿色公平环境，拒绝刷票。如有疑问联系我们的公众号申诉解封");
                            }
                            if (!$this->inContext) {
                                $this->beginContext();
                            }
                            $lll11l1l11l1l11111111ll1ll1l111 = "投票请回复：选手编号";
                            $l1ll1l1lll111l1111l11lllllll111 = array(
                                'vote' => array(
                                    'step' => 1
                                )
                            );
                            $_SESSION['xiaofprocess']        = iserializer($l1ll1l1lll111l1111l11lllllll111);
                            break;
                        case '3':
                            if (intval($l11ll11lllllll1l1llll11ll11ll1l['openmsgvote']) == 1) {
                                return $this->respText("系统关闭了回复投票，请进活动页投票。<a href='" . $this->l1111llll1l1llll11l1lll1l11l1ll('index') . "'>点击进入</a>");
                            }
                            if (time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['start'])) {
                                return $this->respText("活动未开始，请开始后再投票，开始时间" . $l11ll11lllllll1l1llll11ll11ll1l['start'] . "");
                            }
                            if (time() >= strtotime($l11ll11lllllll1l1llll11ll11ll1l['end'])) {
                                return $this->respText("活动已结束，敬请期待下次活动");
                            }
                            if ($l11ll11lllllll1l1llll11ll11ll1l['vnum'] >= 1) {
                                $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `openid` = '" . $_W['openid'] . "' AND `unique_at` = '" . date(Ymd) . "'");
                                if ($ll11llll1l1lll11l111lll111111ll >= $l11ll11lllllll1l1llll11ll11ll1l['vnum']) {
                                    return $this->respText("一个微信号每天只能给" . $l11ll11lllllll1l1llll11ll11ll1l['vnum'] . "个选手投票");
                                }
                            }
                            if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_safe") . " WHERE `ip` = :ip ", array(
                                ":ip" => ip2long(CLIENT_IP)
                            ))) {
                                return $this->respText("抱歉，系统检测到您非正常投票，投票失败。还绿色公平环境，拒绝刷票。如有疑问联系我们的公众号申诉解封");
                            }
                            preg_match("#.*(\d*)$#iUs", $l11l111l1111l11llllllll1l11llll, $llll1l11ll1l1111111ll1llllll1l1);
                            if (!$llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `uid` = :uid", array(
                                ":sid" => $llll1l111llll1l1ll111111ll1ll1l['id'],
                                ":uid" => intval($llll1l11ll1l1111111ll1llllll1l1[1])
                            ))) {
                                return $this->respText("系统没有找到您要投票的选手编号。请重新输入");
                            }
                            if (intval($l11ll11lllllll1l1llll11ll11ll1l['votejump']) == 1) {
                                return $this->l11ll1lll11111llllll111l11l1lll('我是：' . $llll1l11l11111l1l111l11l1ll1111['name'] . '，编号：' . $llll1l11l11111l1l111l11l1ll1111['uid'] . '。点击进入为我投票吧！', $llll1l11l11111l1l111l11l1ll1111['pic'], $llll1l11l11111l1l111l11l1ll1111['id']);
                            }
                            if (!$this->inContext) {
                                $this->beginContext();
                            }
                            $l1ll11ll11l111l1l1l1ll11l1ll1ll                 = rand(1000, 9999);
                            $l1llll1l1l11lll1111111lll1l1l11['vote']['data'] = array(
                                'rand' => $l1ll11ll11l111l1l1l1ll11l1ll1ll,
                                'pid' => $llll1l11l11111l1l111l11l1ll1111['id']
                            );
                            $l1llll1l1l11lll1111111lll1l1l11['vote']['step'] = 2;
                            $_SESSION['xiaofprocess']                        = iserializer($l1llll1l1l11lll1111111lll1l1l11);
                            $lll11l1l11l1l11111111ll1ll1l111                 = "为防刷票请回复四位验证码：" . $l1ll11ll11l111l1l1l1ll11l1ll1ll . "";
                            break;
                        default:
                            return $this->llll11ll1l111l1l1ll1111ll11ll1l($l11ll11lllllll1l1llll11ll11ll1l['replytitle'], $l11ll11lllllll1l1llll11ll11ll1l['replycontent'], $l11ll11lllllll1l1llll11ll11ll1l['replythumb']);
                            break;
                    }
                    return $this->respText($lll11l1l11l1l11111111ll1ll1l111);
                }
                return $this->llll11ll1l111l1l1ll1111ll11ll1l($l11ll11lllllll1l1llll11ll11ll1l['replytitle'], $l11ll11lllllll1l1llll11ll11ll1l['replycontent'], $l11ll11lllllll1l1llll11ll11ll1l['replythumb']);
            }
        } elseif ($this->message['type'] == 'image' && isset($_SESSION['xiaofprocess'])) {
            $l1llll1l1l11lll1111111lll1l1l11 = iunserializer($_SESSION['xiaofprocess']);
            if (isset($l1llll1l1l11lll1111111lll1l1l11['join']['step']) && ($l1llll1l1l11lll1111111lll1l1l11['join']['step'] == 3 or $l1llll1l1l11lll1111111lll1l1l11['join']['step'] == 4)) {
                $lll1lll1lll1l11ll1ll1l1ll1l1lll                 = intval($_SESSION['xiaofsid']);
                $llll1l111llll1l1ll111111ll1ll1l                 = pdo_fetch("SELECT `id`,`tit`,`data` FROM " . tablename("xiaof_toupiao_setting") . " WHERE `id` = :id", array(
                    ":id" => $lll1lll1lll1l11ll1ll1l1ll1l1lll
                ));
                $l11ll11lllllll1l1llll11ll11ll1l                 = iunserializer($llll1l111llll1l1ll111111ll1ll1l['data']);
                $l1111ll1l11llllll1111lllllllll1                 = count($l1llll1l1l11lll1111111lll1l1l11['join']['data']['pics']);
                $llll1l111llll11111lll11111l11l1                 = $l1111ll1l11llllll1111lllllllll1 + 1;
                $l1llll1l1l11lll1111111lll1l1l11['join']['step'] = 4;
                $l1l1111ll111l1llll11111l1ll1111                 = empty($l11ll11lllllll1l1llll11ll11ll1l['limitpic']) ? 5 : $l11ll11lllllll1l1llll11ll11ll1l['limitpic'];
                if ($llll1l111llll11111lll11111l11l1 > $l1l1111ll111l1llll11111l1ll1111) {
                    return $this->respText("最多允许1-" . $l1l1111ll111l1llll11111l1ll1111 . "张，将只使用前" . $l1l1111ll111l1llll11111l1ll1111 . "张。请回复任意文字确定继续报名");
                }
                $l1llll1l1l11lll1111111lll1l1l11['join']['data']['pics'][] = $this->message['picurl'];
                $_SESSION['xiaofprocess']                                  = iserializer($l1llll1l1l11lll1111111lll1l1l11);
                if ($llll1l111llll11111lll11111l11l1 == $l1l1111ll111l1llll11111l1ll1111) {
                    return $this->respText("请回复任意文字确定报名");
                } else {
                    return $this->respText("最多允许1-" . $l1l1111ll111l1llll11111l1ll1111 . "张，当前上传了" . $llll1l111llll11111lll11111l11l1 . "张，您可以继续上传，或回复任意文字确定报名");
                }
            }
        }
    }
    private function ll1l11111l1111111lllll11lll11l1($l111ll1l1ll1ll1ll1llll1l1ll111l, $l1l1ll11l1llll1l1lll1ll1lll11l1 = 500)
    {
        global $_W;
        $l111lllll11lllll1ll1l1lllllllll = $this->l11l111ll111l11lll1lllll11l1l1l();
        $l1lllll1l11llllll1l11l11llll1l1 = ihttp_get($l111ll1l1ll1ll1ll1llll1l1ll111l);
        file_put_contents(ATTACHMENT_ROOT . '/' . $l111lllll11lllll1ll1l1lllllllll, $l1lllll1l11llllll1l11l11llll1l1['content']);
        $ll1lll1ll1l11l111llll1l1l11l1l1 = pathinfo($l111lllll11lllll1ll1l1lllllllll);
        $ll111l1l11ll111ll1111l11111l1l1 = $ll1l111ll1111l1ll111l1lll1ll111 = $ll1lll1ll1l11l111llll1l1l11l1l1['dirname'] . '/' . $ll1lll1ll1l11l111llll1l1l11l1l1['filename'] . '-' . $l1l1ll11l1llll1l1lll1ll1lll11l1 . '.' . $ll1lll1ll1l11l111llll1l1l11l1l1['extension'];
        file_image_thumb(ATTACHMENT_ROOT . '/' . $l111lllll11lllll1ll1l1lllllllll, IA_ROOT . '/attachment/' . $ll1l111ll1111l1ll111l1lll1ll111, $l1l1ll11l1llll1l1lll1ll1lll11l1);
        if (!empty($_W['setting']['remote']['type'])) {
            $lll1ll1l111l1ll1111ll11lll1llll = file_remote_upload($ll1l111ll1111l1ll111l1lll1ll111);
            if (is_error($lll1ll1l111l1ll1111ll11lll1llll)) {
                return array(
                    $l111lllll11lllll1ll1l1lllllllll,
                    $ll111l1l11ll111ll1111l11111l1l1
                );
            }
        }
        return array(
            $l111lllll11lllll1ll1l1lllllllll,
            $ll1l111ll1111l1ll111l1lll1ll111
        );
    }
    private function l11l111ll111l11lll1lllll11l1l1l()
    {
        global $_W;
        $l11111l11ll111ll1l11lll11lll11l = 'image';
        load()->func('file');
        $_W['uploadsetting']                        = array();
        $_W['uploadsetting']['image']['folder']     = 'images/' . $_W['uniacid'];
        $_W['uploadsetting']['image']['extentions'] = $_W['config']['upload']['image']['extentions'];
        $_W['uploadsetting']['image']['limit']      = $_W['config']['upload']['image']['limit'];
        $llll1l111llll1l1ll111111ll1ll1l            = $_W['uploadsetting'];
        $l1l1llllll11l111l1ll11llll1l1ll            = array();
        $l111lllll11lllll1ll1l1lllllllll            = "{$llll1l111llll1l1ll111111ll1ll1l[$l11111l11ll111ll1l11lll11lll11l]['folder']}/" . date('Y/m/');
        mkdirs(ATTACHMENT_ROOT . '/' . $l111lllll11lllll1ll1l1lllllllll);
        do {
            $ll1l111l1l1llll1l1111l1ll11l11l = random(30) . '.jpg';
        } while (file_exists(ATTACHMENT_ROOT . '/' . $l111lllll11lllll1ll1l1lllllllll . $ll1l111l1l1llll1l1111l1ll11l11l));
        $l111lllll11lllll1ll1l1lllllllll .= $ll1l111l1l1llll1l1111l1ll11l11l;
        return $l111lllll11lllll1ll1l1lllllllll;
    }
    private function l1l1lll11l11l1l11l111lll11111l1($llll1l11l1ll1l1llllll11lllll1l1)
    {
        if (!is_numeric($llll1l11l1ll1l1llllll11lllll1l1)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $llll1l11l1ll1l1llllll11lllll1l1) ? true : false;
    }
    private function lll1l1lllll1111ll1l1ll1l11l11ll()
    {
        unset($_SESSION['xiaofprocess']);
        unset($_SESSION['xiaofaction']);
    }
    private function ll111l1l1ll11l1ll1llll1ll111ll1()
    {
        global $_W;
        $ll1ll1ll1l11l1lll1lll11l111ll11 = pdo_fetch("SELECT * FROM " . tablename("uni_settings") . " WHERE `uniacid` = :uniacid limit 1", array(
            ':uniacid' => $_W['uniacid']
        ));
        $ll1ll1ll1l11l1lll1lll11l111ll11 = iunserializer($ll1ll1ll1l11l1lll1lll11l111ll11['oauth']);
        if (!empty($ll1ll1ll1l11l1lll1lll11l111ll11['account'])) {
            $lllll1l11ll1l11l1l1111lll11ll11 = $ll1ll1ll1l11l1lll1lll11l111ll11['account'];
        } elseif ($_W['account']['level'] == 4) {
            $lllll1l11ll1l11l1l1111lll11ll11 = $_W['uniacid'];
        } else {
            return false;
        }
        if (!$l1l111l1ll11ll1l11111l11ll11l11 = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `uniacid` = :uniacid AND `oauth_uniacid` = :oauth_uniacid AND `openid` = :openid limit 1", array(
            ":uniacid" => $_W['uniacid'],
            ":oauth_uniacid" => $lllll1l11ll1l11l1l1111lll11ll11,
            ":openid" => $_W['openid']
        ))) {
            $l1l111ll1llll11lll1l11l1l1ll111 = $this->l11l1l1lllll11l1l1l11111ll1ll1l();
            $lll1ll1l11ll11l111111ll1l1ll111 = array(
                "uniacid" => $_W['uniacid'],
                "openid" => $_W['openid'],
                "oauth_uniacid" => $lllll1l11ll1l11l1l1111lll11ll11,
                "nickname" => $l1l111ll1llll11lll1l11l1l1ll111['nickname'],
                "avatar" => $l1l111ll1llll11lll1l11l1l1ll111['avatar'],
                "unionid" => $l1l111ll1llll11lll1l11l1l1ll111['unionid'],
                "follow" => 1
            );
            pdo_insert("xiaof_relation", $lll1ll1l11ll11l111111ll1l1ll111);
        }
        if ($l1l111l1ll11ll1l11111l11ll11l11['follow'] != 1) {
            pdo_update("xiaof_relation", array(
                'follow' => 1
            ), array(
                "id" => $l1l111l1ll11ll1l11111l11ll11l11['id']
            ));
        }
    }
    private function l11l1l1lllll11l1l1l11111ll1ll1l($llll11lllll111l1l1l1l11111ll111 = null)
    {
        global $_W;
        if (!is_null($llll11lllll111l1l1l1l11111ll111)) {
            if (isset($_SESSION['xiaofuserinfo']) && is_serialized($_SESSION['xiaofuserinfo'])) {
                $l1l111ll1llll11lll1l11l1l1ll111 = iunserializer($_SESSION['xiaofuserinfo']);
                if (isset($l1l111ll1llll11lll1l11l1l1ll111[$llll11lllll111l1l1l1l11111ll111]) && !empty($l1l111ll1llll11lll1l11l1l1ll111[$llll11lllll111l1l1l1l11111ll111])) {
                    return $l1l111ll1llll11lll1l11l1l1ll111[$llll11lllll111l1l1l1l11111ll111];
                }
            }
        }
        if ($lllllll111ll1l1l1lll1ll1l11ll11 = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `uniacid` = :uniacid AND `openid` = :openid limit 1", array(
            ":uniacid" => $_W['uniacid'],
            ":openid" => $_W['openid']
        ))) {
            $l1lllll1l1ll11lll1ll1l111ll1l11['openid']   = $lllllll111ll1l1l1lll1ll1l11ll11['openid'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'] = $lllllll111ll1l1l1lll1ll1l11ll11['nickname'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['avatar']   = $lllllll111ll1l1l1lll1ll1l11ll11['avatar'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['unionid']  = $lllllll111ll1l1l1lll1ll1l11ll11['unionid'];
            if (is_null($llll11lllll111l1l1l1l11111ll111)) {
                $_SESSION['xiaofuserinfo'] = iserializer($l1lllll1l1ll11lll1ll1l111ll1l11);
                return $l1lllll1l1ll11lll1ll1l111ll1l11;
            } else {
                if (!empty($l1lllll1l1ll11lll1ll1l111ll1l11[$llll11lllll111l1l1l1l11111ll111])) {
                    return $l1lllll1l1ll11lll1ll1l111ll1l11[$llll11lllll111l1l1l1l11111ll111];
                }
            }
        }
        if ($_W['account']['level'] <= 2) {
            return '';
        }
        $l11l11l1111111l1l11l1lll1l11l11 = WeixinAccount::create($_W['acid']);
        $l11ll1l1ll11l1l1l111111l1111ll1 = $l11l11l1111111l1l11l1lll1l11l11->fetch_token();
        if (!is_null($l11ll1l1ll11l1l1l111111l1111ll1)) {
            $ll1lll1llll1ll1ll1lll111l111ll1 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $l11ll1l1ll11l1l1l111111l1111ll1 . "&openid=" . $_W['openid'] . "&lang=zh_CN";
            $l1ll1ll11ll1ll11111ll1lllllll11 = file_get_contents($ll1lll1llll1ll1ll1lll111l111ll1);
            $l1ll1ll11ll1ll11111ll1lllllll11 = substr(str_replace('\"', '"', json_encode($l1ll1ll11ll1ll11111ll1lllllll11)), 1, -1);
            $l1l111l11l1llllll1lll1l11ll1ll1 = json_decode($l1ll1ll11ll1ll11111ll1lllllll11, true);
        }
        $l1lllll1l1ll11lll1ll1l111ll1l11 = array(
            'nickname' => '',
            'avatar' => ''
        );
        isset($l1l111l11l1llllll1lll1l11ll1ll1['nickname']) && $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'] = stripcslashes($l1l111l11l1llllll1lll1l11ll1ll1['nickname']);
        if (isset($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'])) {
            if (!empty($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'])) {
                $l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'] = rtrim($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'], '0') . 132;
            }
            $l1lllll1l1ll11lll1ll1l111ll1l11['avatar'] = istripslashes($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl']);
        }
        isset($l1l111l11l1llllll1lll1l11ll1ll1['unionid']) && $l1lllll1l1ll11lll1ll1l111ll1l11['unionid'] = $l1l111l11l1llllll1lll1l11ll1ll1['unionid'];
        $_SESSION['xiaofuserinfo'] = iserializer($l1lllll1l1ll11lll1ll1l111ll1l11);
        return is_null($llll11lllll111l1l1l1l11111ll111) ? $l1lllll1l1ll11lll1ll1l111ll1l11 : $l1lllll1l1ll11lll1ll1l111ll1l11[$llll11lllll111l1l1l1l11111ll111];
    }
    private function llll11ll1l111l1l1ll1111ll11ll1l($l11l1lll1lllll11ll1l1l111l1ll11 = '', $l111l1l1ll11ll1lllllll11lll11ll = '', $lll1l1111l111l111l1l11ll1l1l1l1 = '')
    {
        empty($l11l1lll1lllll11ll1l1l111l1ll11) && $l11l1lll1lllll11ll1l1l111l1ll11 = "欢迎参与" . $this->mysetting['title'] . "活动！";
        empty($l111l1l1ll11ll1lllllll11lll11ll) && $l111l1l1ll11ll1lllllll11lll11ll = "点击进入活动首页。";
        empty($lll1l1111l111l111l1l11ll1l1l1l1) && $lll1l1111l111l111l1l11ll1l1l1l1 = $this->mysetting['thumb'][0];
        return $this->respNews(array(
            'title' => $l11l1lll1lllll11ll1l1l111l1ll11,
            'description' => $l111l1l1ll11ll1lllllll11lll11ll,
            'picurl' => tomedia($lll1l1111l111l111l1l11ll1l1l1l1),
            'url' => $this->l1111llll1l1llll11l1lll1l11l1ll('index')
        ));
    }
    private function l1ll111l1ll1l11llll1lll1lllll11($lllll1111l1ll1l1lll111ll11111l1, $l1l1l1l1111l111l11lll11l1l11ll1, $lll1l1111l111l111l1l11ll1l1l1l1, $l1llll11l1ll1ll1l1111l1l1l111l1 = 1)
    {
        $ll11lll11ll111l111llll11111l11l   = array();
        $ll11lll11ll111l111llll11111l11l[] = array(
            'title' => '恭喜您成功为编号' . $lllll1111l1ll1l1lll111ll11111l1 . '' . $l1l1l1l1111l111l11lll11l1l11ll1 . '投了' . $l1llll11l1ll1ll1l1111l1l1l111l1 . '票',
            'description' => $this->mysetting['title'],
            'picurl' => tomedia($lll1l1111l111l111l1l11ll1l1l1l1),
            'url' => $this->l1111llll1l1llll11l1lll1l11l1ll('index')
        );
        $ll11lll11ll111l111llll11111l11l[] = array(
            'title' => $this->mysetting['title'],
            'description' => $this->mysetting['title'],
            'picurl' => tomedia($this->mysetting['thumb'][0]),
            'url' => $this->l1111llll1l1llll11l1lll1l11l1ll('index')
        );
        return $this->respNews($ll11lll11ll111l111llll11111l11l);
    }
    private function l1l1l1l1l1ll1l11l1lllll1l11l111($lllll1111l1ll1l1lll111ll11111l1, $lll1l1111l111l111l1l11ll1l1l1l1, $ll11111ll111l1llllllllll11l11l1)
    {
        $ll11lll11ll111l111llll11111l11l   = array();
        $ll11lll11ll111l111llll11111l11l[] = array(
            'title' => '恭喜，报名成功！您的参赛编号为' . $lllll1111l1ll1l1lll111ll11111l1 . '',
            'description' => $this->mysetting['title'],
            'picurl' => tomedia($lll1l1111l111l111l1l11ll1l1l1l1),
            'url' => $this->l1111llll1l1llll11l1lll1l11l1ll('show', 'xiaof_toupiao', '&id=' . $ll11111ll111l1llllllllll11l11l1 . '')
        );
        $ll11lll11ll111l111llll11111l11l[] = array(
            'title' => $this->mysetting['title'],
            'description' => $this->mysetting['title'],
            'picurl' => tomedia($this->mysetting['thumb'][0]),
            'url' => $this->l1111llll1l1llll11l1lll1l11l1ll('index')
        );
        return $this->respNews($ll11lll11ll111l111llll11111l11l);
    }
    private function l11ll1lll11111llllll111l11l1lll($l1l11l1l1ll1ll11ll1lll11l11l111, $lll1l1111l111l111l1l11ll1l1l1l1, $ll11111ll111l1llllllllll11l11l1)
    {
        $ll11lll11ll111l111llll11111l11l   = array();
        $ll11lll11ll111l111llll11111l11l[] = array(
            'title' => $l1l11l1l1ll1ll11ll1lll11l11l111,
            'description' => $this->mysetting['title'],
            'picurl' => tomedia($lll1l1111l111l111l1l11ll1l1l1l1),
            'url' => $this->l1111llll1l1llll11l1lll1l11l1ll('show', 'xiaof_toupiao', '&id=' . $ll11111ll111l1llllllllll11l11l1 . '')
        );
        $ll11lll11ll111l111llll11111l11l[] = array(
            'title' => $this->mysetting['title'],
            'description' => $this->mysetting['title'],
            'picurl' => tomedia($this->mysetting['thumb'][0]),
            'url' => $this->l1111llll1l1llll11l1lll1l11l1ll('index')
        );
        return $this->respNews($ll11lll11ll111l111llll11111l11l);
    }
    private function l1111llll1l1llll11l1lll1l11l1ll($ll11l1111ll111l11l111ll1111ll11, $l11ll1l1l1l11lll111ll11l1l1l1l1 = 'xiaof_toupiao', $l111lll1ll1l11l1llll111lllll11l = '')
    {
        global $_W, $_GPC;
        $l1llll11l1l1ll1l11ll1l1l11l11l1 = empty($this->mysetting['binddomain']) ? $_W['siteroot'] : $this->mysetting['binddomain'];
        $ll1llll11l1lll11l1ll1l1lll1l1ll = parse_url($l1llll11l1l1ll1l11ll1l1l11l11l1, PHP_URL_HOST);
        $l1l11111lllllll1ll1l1l11l11l111 = urlencode(base64_encode(authcode($_W['openid'], 'ENCODE', $ll1llll11l1lll11l1ll1l1lll1l1ll . 'xi9aofhaha' . $_W['uniacid'], 43200)));
        return $l1llll11l1l1ll1l11ll1l1l11l11l1 . "app/index.php?c=entry&do={$ll11l1111ll111l11l111ll1111ll11}&m={$l11ll1l1l1l11lll111ll11l1l1l1l1}&i={$_W['uniacid']}&sid={$_SESSION['xiaofsid']}&xiaofopenid={$l1l11111lllllll1ll1l1l11l11l111}{$l111lll1ll1l11l1llll111lllll11l}&wxref=mp.weixin.qq.com#wechat_redirect";
    }
}