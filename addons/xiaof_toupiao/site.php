<?php
defined('IN_IA') or exit('Access Denied');
class Xiaof_toupiaoModuleSite extends WeModuleSite
{
    public function doWebTool()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        include $this->template("tool");
    }
    public function doWebGlobalsetting()
    {
        global $_W, $_GPC;
        $l111ll1l111ll1ll11l1111l1111lll = array(
            'openmusic',
            'openshare',
            'openfollow'
        );
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        $llll11lllll111l1l1l1l11111ll111 = md5($_SERVER['HTTP_HOST'] . $this->module['name'] . 'globalsetting');
        $llll1l11l11111l1l111l11l1ll1111 = cache_read("ipaddrr:" . $llll11lllll111l1l1l1l11111ll111);
        $llll1l11l11111l1l111l11l1ll1111 or $llll1l11l11111l1l111l11l1ll1111 = array(
            'openmusic' => 1,
            'openshare' => 1,
            'openfollow' => 1
        );
        if ($_W['isajax']) {
            if (!in_array($_GPC['name'], $l111ll1l111ll1ll11l1111l1111lll)) {
                exit(0);
            }
            $llll1l11l11111l1l111l11l1ll1111[$_GPC['name']] = intval($_GPC['ban']);
            cache_write("ipaddrr:" . $llll11lllll111l1l1l1l11111ll111, $llll1l11l11111l1l111l11l1ll1111);
            exit(1);
        }
        include $this->template("globalsetting");
    }
    public function doWebClearjs()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        $ll1ll1ll1l11l1lll1lll11l111ll11 = uni_setting_load();
        if ($_W['account']['level'] < 3) {
            if (!empty($ll1ll1ll1l11l1lll1lll11l111ll11['jsauth_acid'])) {
                $lll1ll1111ll1l11lllllll1111l111 = $ll1ll1ll1l11l1lll1lll11l111ll11['jsauth_acid'];
            } elseif (!empty($ll1ll1ll1l11l1lll1lll11l111ll11['oauth']['account'])) {
                $lll1ll1111ll1l11lllllll1111l111 = $ll1ll1ll1l11l1lll1lll11l111ll11['oauth']['account'];
            }
        } else {
            $lll1ll1111ll1l11lllllll1111l111 = $_W['acid'];
        }
        if (!empty($lll1ll1111ll1l11lllllll1111l111)) {
            $l1lll11111111l11l1llllll111ll1l           = "jsticket:{$lll1ll1111ll1l11lllllll1111l111}";
            $llllll1111ll1l1111ll1l1lll111l1           = array();
            $llllll1111ll1l1111ll1l1lll111l1['ticket'] = '';
            $llllll1111ll1l1111ll1l1lll111l1['expire'] = 0;
            cache_write($l1lll11111111l11l1llllll111ll1l, $llllll1111ll1l1111ll1l1lll111l1);
            load()->classs('weixin.account');
            $lllll1ll1l111l11ll11111l1l1l1l1 = WeAccount::create($lll1ll1111ll1l11lllllll1111l111);
            $l11l1l1ll1ll11l1l1ll1llllll11ll = $lllll1ll1l111l11ll11111l1l1l1l1->getJsApiTicket();
            if (is_error($l11l1l1ll1ll11l1l1ll1llllll11ll)) {
                message($l11l1l1ll1ll11l1l1ll1llllll11ll['message']);
            } else {
                message('jsApiTicket更新成功');
            }
        } else {
            message('没有获取到要使用的公众号');
        }
    }
    public function doWebClearcache()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        cache_clean('iplongregion');
        cache_clean('ipaddr');
        $l1l11ll1l11lll1llllll111l1l1ll1 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_rule') . " WHERE `uniacid` = '0'");
        foreach ($l1l11ll1l11lll1llllll111l1l1ll1 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            $llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename('rule') . " WHERE `id` = '" . $ll111111l1ll11lll1l1l1111lll11l['rid'] . "'");
            pdo_update("xiaof_toupiao_rule", array(
                "uniacid" => $llll1l11l11111l1l111l11l1ll1111['uniacid']
            ), array(
                "rid" => $ll111111l1ll11lll1l1l1111lll11l['rid']
            ));
        }
        pdo_query("DELETE FROM " . tablename('xiaof_relation') . " WHERE `openid` = '' or `oauth_openid` = ''");
        message('清理完成');
    }
    public function doWebCleartoken()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        $ll1ll1ll1l11l1lll1lll11l111ll11 = uni_setting_load();
        if (!empty($ll1ll1ll1l11l1lll1lll11l111ll11['oauth']['account'])) {
            $lllll1l11ll1l11l1l1111lll11ll11 = $ll1ll1ll1l11l1lll1lll11l111ll11['oauth']['account'];
        } else {
            $lllll1l11ll1l11l1l1111lll11ll11 = $_W['account']['acid'];
        }
        if (!empty($lllll1l11ll1l11l1l1111lll11ll11)) {
            $l1lll11111111l11l1llllll111ll1l           = "accesstoken:{$lllll1l11ll1l11l1l1111lll11ll11}";
            $llllll1111ll1l1111ll1l1lll111l1           = array();
            $llllll1111ll1l1111ll1l1lll111l1['token']  = '';
            $llllll1111ll1l1111ll1l1lll111l1['expire'] = 0;
            cache_write($l1lll11111111l11l1llllll111ll1l, $llllll1111ll1l1111ll1l1lll111l1);
            load()->classs('weixin.account');
            $lllll1ll1l111l11ll11111l1l1l1l1 = WeAccount::create($lllll1l11ll1l11l1l1111lll11ll11);
            $ll1l1lll11l1ll11111lll1l1l111l1 = $lllll1ll1l111l11ll11111l1l1l1l1->getAccessToken();
            if (is_error($ll1l1lll11l1ll11111lll1l1l111l1)) {
                message($ll1l1lll11l1ll11111lll1l1l111l1['message']);
            } else {
                message('accessToken更新成功');
            }
        } else {
            message('没有获取到要使用的公众号');
        }
    }
    public function doWebDiagnose()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        $l1l1llllll11l111l1ll11llll1l1ll = '';
        $l11l1l1111l1l11111111l111l11ll1 = 100;
        if (intval($this->module['config']['openweixin']) == 1) {
            if (pdo_fieldexists("mc_mapping_fans", "unionid")) {
                $l1l1llllll11l111l1ll11llll1l1ll .= '粉丝开放平台数据表<span class="label label-success">正常</span>......<br/>';
                if (pdo_indexexists('mc_mapping_fans', 'unionid')) {
                    $l1l1llllll11l111l1ll11llll1l1ll .= '粉丝开放平台数据表优化<span class="label label-success">正常</span>......<br/>';
                } else {
                    $l1l1llllll11l111l1ll11llll1l1ll .= '粉丝开放平台数据表优化<span class="label label-warning">未优化</span>......<br/>';
                }
            } else {
                $l1l1llllll11l111l1ll11llll1l1ll .= '粉丝开放平台数据表<span class="label label-danger">不存在</span>......<br/>';
            }
            $l1l1llllll11l111l1ll11llll1l1ll .= '----------<br/>';
            $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
                ":uniacid" => $_W['uniacid']
            ));
            $l1lll11l11111ll11111l1l1ll11111 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
            }
            load()->classs('account');
            $l111ll111llllll111l111l111111ll = WeUtility::createModuleReceiver('xiaof_toupiao');
            if (empty($l111ll111llllll111l111l111111ll)) {
                $l1l1llllll11l111l1ll11llll1l1ll .= '模块订阅<span class="label label-danger">错误</span>......<br/>';
            }
            $l111ll111llllll111l111l111111ll->uniacid = $_W['uniacid'];
            $l111ll111llllll111l111l111111ll->acid    = $_W['acid'];
            if (method_exists($l111ll111llllll111l111l111111ll, 'receive')) {
                $l1l1llllll11l111l1ll11llll1l1ll .= '模块订阅<span class="label label-success">正常</span>......<br/>';
            }
            $lll11ll11ll1l1111l1l1lll1llll11 = $_W['setting']['module_receive_ban'];
            if (!is_array($lll11ll11ll1l1111l1l1lll1llll11)) {
                $lll11ll11ll1l1111l1l1lll1llll11 = array();
            }
            if (!isset($lll11ll11ll1l1111l1l1lll1llll11['xiaof_toupiao'])) {
                $l1l1llllll11l111l1ll11llll1l1ll .= '模块订阅消息<span class="label label-success">已经打开</span>......<br/>';
            } else {
                $l1l1llllll11l111l1ll11llll1l1ll .= '模块订阅消息<span class="label label-danger">被关闭</span>......<a target="_blank" href="' . wurl('extension/subscribe/subscribe') . '">开启</a><br/>';
            }
            $l1l1llllll11l111l1ll11llll1l1ll .= '----------<br/>';
            $lll1l1ll111l11l111l11lll1111ll1   = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_acid') . " WHERE `sid` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')");
            $l1l1ll1ll1lll1l11l11lllll1l11ll[] = $_W['uniacid'];
            foreach ($lll1l1ll111l11l111l11lll1111ll1 as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l1ll1ll1lll1l11l11lllll1l11ll[] = $ll111111l1ll11lll1l1l1111lll11l['acid'];
            }
            $l1l1ll1ll1lll1l11l11lllll1l11ll = array_unique($l1l1ll1ll1lll1l11l11lllll1l11ll);
            load()->classs('weixin.account');
            foreach ($l1l1ll1ll1lll1l11l11lllll1l11ll as $ll111111l1ll11lll1l1l1111lll11l) {
                $lll11l11l1l111l11llll1ll1llll11 = uni_accounts($ll111111l1ll11lll1l1l1111lll11l);
                $lll111l1l11111111111l1111l111ll = uni_fetch($ll111111l1ll11lll1l1l1111lll11l);
                $llllll1l1l1ll11l11l11111llll1ll = $lll11l11l1l111l11llll1ll1llll11[$lll111l1l11111111111l1111l111ll['default_acid']];
                if ($l1l11ll11l1111ll111l1ll11ll1l1l = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE `uniacid` = :uniacid ORDER BY `fanid` DESC limit 1", array(
                    ":uniacid" => $ll111111l1ll11lll1l1l1111lll11l
                ))) {
                    if (empty($l1l11ll11l1111ll111l1ll11ll1l1l['unionid'])) {
                        $l1l1llllll11l111l1ll11llll1l1ll .= '公众号【' . $lll111l1l11111111111l1111l111ll['name'] . '】：开放平台数据<span class="label label-danger">有问题</span>......<br/>';
                        if ($llllll1l1l1ll11l11l11111llll1ll['level'] < 3) {
                            $l1l1llllll11l111l1ll11llll1l1ll .= '公众号【' . $lll111l1l11111111111l1111l111ll['name'] . '】：账号类型为未认证，跳过......<br/>';
                        } else {
                            $l11l11l1111111l1l11l1lll1l11l11 = WeixinAccount::create($lll111l1l11111111111l1111l111ll['default_acid']);
                            $l11ll1l1ll11l1l1l111111l1111ll1 = $l11l11l1111111l1l11l1lll1l11l11->fetch_token();
                            $ll1lll1llll1ll1ll1lll111l111ll1 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $l11ll1l1ll11l1l1l111111l1111ll1 . "&openid=" . $l1l11ll11l1111ll111l1ll11ll1l1l['openid'] . "&lang=zh_CN";
                            $l1ll1ll11ll1ll11111ll1lllllll11 = file_get_contents($ll1lll1llll1ll1ll1lll111l111ll1);
                            $l1ll1ll11ll1ll11111ll1lllllll11 = substr(str_replace('\"', '"', json_encode($l1ll1ll11ll1ll11111ll1lllllll11)), 1, -1);
                            $l1l111l11l1llllll1lll1l11ll1ll1 = @json_decode($l1ll1ll11ll1ll11111ll1lllllll11, true);
                            if (isset($l1l111l11l1llllll1lll1l11ll1ll1['unionid'])) {
                                $l1l1llllll11l111l1ll11llll1l1ll .= '公众号【' . $lll111l1l11111111111l1111l111ll['name'] . '】：获取开放平台数据成功，请尝试去该公众号运行获取粉丝信息......<a target="_blank" href="' . $this->createWebUrl('getunionid') . '">进入</a><br/>';
                            } else {
                                $l1l1llllll11l111l1ll11llll1l1ll .= '公众号【' . $lll111l1l11111111111l1111l111ll['name'] . '】：获取开放平台数据<span class="label label-danger">失败</span>，该号未绑定开放平台......<br/>';
                            }
                        }
                    } else {
                        $l1l1llllll11l111l1ll11llll1l1ll .= '公众号【' . $lll111l1l11111111111l1111l111ll['name'] . '】：开放平台<span class="label label-success">数据正常</span>......<br/>';
                    }
                }
            }
            $l1l1llllll11l111l1ll11llll1l1ll .= '----------<br/>';
        } else {
            message('本工具只适用开放平台配置检测。');
        }
        include $this->template("diagnose");
    }
    public function doWebDrawlists()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        if (!empty($_GPC['use'])) {
            if (empty($_GPC['did'])) {
                exit('参数错误');
            }
            pdo_update("xiaof_toupiao_draw", array(
                "uses" => '1',
                "bdelete_at" => time()
            ), array(
                "id" => intval($_GPC['did'])
            ));
        }
        if (!empty($_GPC['del'])) {
            $l1l111l1lll1l1111ll1l1l1ll111ll = array();
            foreach ($_GPC['delete'] as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l111l1lll1l1111ll1l1l1ll111ll[] = intval($ll111111l1ll11lll1l1l1111lll11l);
            }
            pdo_query("DELETE FROM " . tablename('xiaof_toupiao_draw') . " WHERE `id` IN ('" . implode("','", $l1l111l1lll1l1111ll1l1l1ll111ll) . "')");
        }
        if (!empty($_GPC['pass'])) {
            $l1l111l1lll1l1111ll1l1l1ll111ll = array();
            foreach ($_GPC['delete'] as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l111l1lll1l1111ll1l1l1ll111ll[] = intval($ll111111l1ll11lll1l1l1111lll11l);
            }
            pdo_query("UPDATE " . tablename('xiaof_toupiao_draw') . " SET `uses` = '1', `bdelete_at` = '" . time() . "' WHERE `id` IN ('" . implode("','", $l1l111l1lll1l1111ll1l1l1ll111ll) . "')");
        }
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
            ":uniacid" => $_W['uniacid']
        ));
        if (!empty($_GPC['sid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid';
            $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
        } else {
            $l1lll11l11111ll11111l1l1ll11111 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
            }
            $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `sid` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')";
            $l11l1ll1llll11111l11lllll1l1ll1 = array();
        }
        if (!empty($_GPC['key'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND (`uid`=:uid or `uname`=:uname)';
            $l11l1ll1llll11111l11lllll1l1ll1[':uid']   = $_GPC['key'];
            $l11l1ll1llll11111l11lllll1l1ll1[':uname'] = $_GPC['key'];
            $llll11lllll111l1l1l1l11111ll111           = $_GPC['key'];
        }
        if (!empty($_GPC['uses'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `uses`=:uses';
            $l11l1ll1llll11111l11lllll1l1ll1[':uses'] = intval($_GPC['uses']);
        }
        if (!empty($_GPC['attr'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `attr`=:attr';
            $l11l1ll1llll11111l11lllll1l1ll1[':attr'] = intval($_GPC['attr']);
        }
        $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
        $l11ll1l1111l11lll1ll1111lll1111 = 20;
        $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_draw') . $l1l1lll11l111l111ll1l11l1111ll1, $l11l1ll1llll11111l11lllll1l1ll1);
        $ll1111l1l1l11ll1111llll11l1ll11 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_draw') . $l1l1lll11l111l111ll1l11l1111ll1 . " ORDER BY `id` DESC LIMIT " . ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111, $l11l1ll1llll11111l11lllll1l1ll1);
        foreach ($ll1111l1l1l11ll1111llll11l1ll11 as &$l1lllll1l1ll11lll1ll1l111ll1l11) {
            $ll1lllll111ll11l1l1lll1l11l11l1            = $this->l1l1l1l11111lllllll1ll1111111ll();
            $lll1lll1l11l111l1ll1111111l1lll            = pdo_fetchcolumn("SELECT `address` FROM " . tablename('xiaof_relation') . " WHERE `uniacid` IN ('" . implode("','", $ll1lllll111ll11l1l1lll1l11l11l1) . "') AND `openid` = '" . $l1lllll1l1ll11lll1ll1l111ll1l11['openid'] . "' AND `address` != '' ORDER BY `id` DESC limit 1");
            $l1lllll1l1ll11lll1ll1l111ll1l11['address'] = iunserializer($lll1lll1l11l111l1ll1111111l1lll);
        }
        $llll11ll1ll111l1l1ll111l1ll1111 = pagination($l1l1ll1ll1ll11llll1l1l1l111llll, $ll1l11l111l1l11111ll1l11l11ll1l, $l11ll1l1111l11lll1ll1111lll1111);
        include $this->template("drawlists");
    }
    public function doWebGetunionid()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        $l11l1lll1l1l11111l1l1ll1l1l1lll = pdo_fieldexists("mc_mapping_fans", "unionid");
        if ($l11l1lll1l1l11111l1l1ll1l1l1lll && isset($this->module['config']['openweixin']) && $this->module['config']['openweixin'] == 1) {
            $l1l1lll11l11l1llllll111111lllll = intval($_W['acid']);
            $ll1lll1l1l1111llll1l11l1ll1l1ll = intval($_GPC['offset']);
            if (intval($_GPC['page']) == 0) {
                message('正在更新粉丝unionid,请不要关闭浏览器。已有unionid数据的将不再重复更新', $this->createWebUrl('getunionid', array(
                    'page' => 1,
                    'acid' => $l1l1lll11l11l1llllll111111lllll
                )), 'success');
            }
            $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
            $l11ll1l1111l11lll1ll1111lll1111 = 50;
            $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . " WHERE `uniacid` = :uniacid AND `acid` = :acid AND `unionid` = :unionid", array(
                ':uniacid' => $_W['uniacid'],
                ':acid' => $l1l1lll11l11l1llllll111111lllll,
                ':unionid' => ""
            ));
            $llll1111l1l1ll1lll1l1l11l11l111 = pdo_fetchall("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE `uniacid` = :uniacid AND `acid` = :acid AND `unionid` = :unionid ORDER BY `fanid` DESC LIMIT " . $ll1lll1l1l1111llll1l11l1ll1l1ll . ',' . $l11ll1l1111l11lll1ll1111lll1111, array(
                ':uniacid' => $_W['uniacid'],
                ':acid' => $l1l1lll11l11l1llllll111111lllll,
                ':unionid' => ""
            ));
            $l11l11l1111111l1l11l1lll1l11l11 = WeAccount::create($l1l1lll11l11l1llllll111111lllll);
            $l11ll1l1ll11l1l1l111111l1111ll1 = $l11l11l1111111l1l11l1lll1l11l11->fetch_token();
            if (!empty($llll1111l1l1ll1lll1l1l11l11l111)) {
                foreach ($llll1111l1l1ll1lll1l1l11l11l111 as $l1lllll1l1ll11lll1ll1l111ll1l11) {
                    $ll1lll1llll1ll1ll1lll111l111ll1 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $l11ll1l1ll11l1l1l111111l1111ll1 . "&openid=" . $l1lllll1l1ll11lll1ll1l111ll1l11['openid'] . "&lang=zh_CN";
                    $l1ll1l1lll11lll11l1ll1l1ll11l1l = file_get_contents($ll1lll1llll1ll1ll1lll111l111ll1);
                    $l1ll1l1lll11lll11l1ll1l1ll11l1l = substr(str_replace('\"', '"', json_encode($l1ll1l1lll11lll11l1ll1l1ll11l1l)), 1, -1);
                    $l1l111l11l1llllll1lll1l11ll1ll1 = @json_decode($l1ll1l1lll11lll11l1ll1l1ll11l1l, true);
                    if (isset($l1l111l11l1llllll1lll1l11ll1ll1['unionid'])) {
                        pdo_update("mc_mapping_fans", array(
                            "unionid" => $l1l111l11l1llllll1lll1l11ll1ll1['unionid']
                        ), array(
                            "fanid" => $l1lllll1l1ll11lll1ll1l111ll1l11['fanid']
                        ));
                    } else {
                        $ll1lll1l1l1111llll1l11l1ll1l1ll++;
                    }
                }
            }
            $ll1l11l111l1l11111ll1l11l11ll1l++;
            $ll111ll1111l111111lll1ll1111lll = ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111;
            if ($ll1lll1l1l1111llll1l11l1ll1l1ll >= $l1l1ll1ll1ll11llll1l1l1l111llll) {
                message('粉丝unionid更新完成，共更新' . ($ll111ll1111l111111lll1ll1111lll - $ll1lll1l1l1111llll1l11l1ll1l1ll) . ' 条数据，数据获取失败' . $ll1lll1l1l1111llll1l11l1ll1l1ll . '条。', '', 'success');
            } else {
                message('正在更新粉丝unionid,请不要关闭浏览器,已完成更新 ' . ($ll111ll1111l111111lll1ll1111lll - $ll1lll1l1l1111llll1l11l1ll1l1ll) . ' 条数据。', $this->createWebUrl('getunionid', array(
                    'page' => $ll1l11l111l1l11111ll1l11l11ll1l,
                    'acid' => $l1l1lll11l11l1llllll111111lllll,
                    'offset' => $ll1lll1l1l1111llll1l11l1ll1l1ll
                )));
            }
        } else {
            message('您的活动没有开启微信开放平台，请先去设置修改，并公众号需要在微信开放平台绑定', '/web/index.php?c=profile&a=module&do=setting&m=xiaof_toupiao', 'success');
        }
    }
    public function doWebSettinglists()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        if (!empty($_GPC['del'])) {
            $l1l111l1lll1l1111ll1l1l1ll111ll = array();
            foreach ($_GPC['delete'] as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l111l1lll1l1111ll1l1l1ll111ll[] = intval($ll111111l1ll11lll1l1l1111lll11l);
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_drawlog') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_log') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_manage') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_pic') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_rule') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_safe') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_acid') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
            }
            pdo_query("DELETE FROM " . tablename('xiaof_toupiao_setting') . " WHERE `id` IN ('" . implode("','", $l1l111l1lll1l1111ll1l1l1ll111ll) . "')");
        }
        if (!empty($_GPC['pass'])) {
            set_time_limit(0);
            load()->func('file');
            $l1l111l1lll1l1111ll1l1l1ll111ll = array();
            foreach ($_GPC['delete'] as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l111l1lll1l1111ll1l1l1ll111ll[] = intval($ll111111l1ll11lll1l1l1111lll11l);
                $l1lll1l11l111l111ll11111l111111   = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_pic') . " WHERE `sid` = :sid", array(
                    ":sid" => intval($ll111111l1ll11lll1l1l1111lll11l)
                ));
                foreach ($l1lll1l11l111l111ll11111l111111 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                    $ll1lll1ll1l11l111llll1l1l11l1l1 = pathinfo($ll111111l1ll11lll1l1l1111lll11l['url']);
                    file_delete($ll1lll1ll1l11l111llll1l1l11l1l1['dirname'] . '/' . $ll1lll1ll1l11l111llll1l1l11l1l1['filename'] . '-500.' . $ll1lll1ll1l11l111llll1l1l11l1l1['extension']);
                    file_delete($ll1lll1ll1l11l111llll1l1l11l1l1['dirname'] . '/' . $ll1lll1ll1l11l111llll1l1l11l1l1['filename'] . '-240.' . $ll1lll1ll1l11l111llll1l1l11l1l1['extension']);
                    file_delete($ll111111l1ll11lll1l1l1111lll11l['url']);
                }
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_drawlog') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_log') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_manage') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_pic') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_rule') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_safe') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_acid') . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
            }
            pdo_query("DELETE FROM " . tablename('xiaof_toupiao_setting') . " WHERE `id` IN ('" . implode("','", $l1l111l1lll1l1111ll1l1l1ll111ll) . "')");
        }
        $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
        $l11ll1l1111l11lll1ll1111lll1111 = 10;
        $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = " . $_W['uniacid']);
        $lll1l1lllll1111l111ll111111ll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid ORDER BY `id` DESC LIMIT " . ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111, array(
            ":uniacid" => $_W['uniacid']
        ));
        $ll1111l1l1l11ll1111llll11l1ll11 = array();
        foreach ($lll1l1lllll1111l111ll111111ll1l as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            $llll1l11l11111l1l111l11l1ll1111               = array();
            $llll1l11l11111l1l111l11l1ll1111['id']         = $ll111111l1ll11lll1l1l1111lll11l['id'];
            $llll1l11l11111l1l111l11l1ll1111['created_at'] = $ll111111l1ll11lll1l1l1111lll11l['created_at'];
            $ll1111l1l1l11ll1111llll11l1ll11[]             = array_merge($llll1l11l11111l1l111l11l1ll1111, unserialize($ll111111l1ll11lll1l1l1111lll11l['data']));
        }
        $lll11ll11ll1l1111l1l1lll1llll11 = $_W['setting']['module_receive_ban'];
        if (!is_array($lll11ll11ll1l1111l1l1lll1llll11)) {
            $lll11ll11ll1l1111l1l1lll1llll11 = array();
        }
        $ll1ll1ll1l11l1lll1lll11l111ll11 = pdo_fetch("SELECT * FROM " . tablename("uni_settings") . " WHERE `uniacid` = :uniacid limit 1", array(
            ':uniacid' => $_W['uniacid']
        ));
        $ll1ll1ll1l11l1lll1lll11l111ll11 = iunserializer($ll1ll1ll1l11l1lll1lll11l111ll11['oauth']);
        $llll11ll1ll111l1l1ll111l1ll1111 = pagination($l1l1ll1ll1ll11llll1l1l1l111llll, $ll1l11l111l1l11111ll1l11l11ll1l, $l11ll1l1111l11lll1ll1111lll1111);
        include $this->template("settinglists");
    }
    public function doWebSettingedit()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        load()->func('tpl');
        $llll1l11l11111l1l111l11l1ll1111 = array();
        if (isset($_GET['sid'])) {
            $llll1l11l11111l1l111l11l1ll1111 = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        } else {
            $llll11lllll111l1l1l1l11111ll111 = md5($_SERVER['HTTP_HOST'] . $this->module['name'] . 'globalsetting');
            $l1l1l11llllll111l1l1llll1l1ll11 = cache_read("ipaddrr:" . $llll11lllll111l1l1l1l11111ll111);
            $l1l1l11llllll111l1l1llll1l1ll11 or $l1l1l11llllll111l1l1llll1l1ll11 = array(
                'openmusic' => 1,
                'openshare' => 1,
                'openfollow' => 1
            );
            $llll1l11l11111l1l111l11l1ll1111['globalsetting'] = $l1l1l11llllll111l1l1llll1l1ll11;
        }
        $l1l1lll1l1l1111l1l11lll11l111ll = pdo_fetch("SELECT `detail`,`bottom` FROM " . tablename("xiaof_toupiao_setting") . " WHERE `id` = :id", array(
            ":id" => $llll1l11l11111l1l111l11l1ll1111['id']
        ));
        $l1ll11111llll11l111l111l1l1l111 = iunserializer($l1l1lll1l1l1111l1l11lll11l111ll['detail']);
        $l11ll1lll1111l11ll1ll111l111l1l = array();
        foreach ($llll1l11l11111l1l111l11l1ll1111['prize'] as $ll111111l1ll11lll1l1l1111lll11l) {
            $l11ll1lll1111l11ll1ll111l111l1l[] = $ll111111l1ll11lll1l1l1111lll11l['probability'];
        }
        $l1lll11l111ll11111l11ll1ll111ll = array_sum($l11ll1lll1111l11ll1ll111l111l1l);
        $l1lll1llll1l1l1llll111111111ll1 = '01.一个微信号每天只能给同一个选手投一票<br/>		02.一个微信号每天可以给5位选手投票<br/>		03.一个IP最多只允许10个微信号参与<br/>		04.一个IP每天最多只允许50个投票<br/>		05.本次活动仅限**地区参与<br/>		06.报名提交的照片必须拥有所有权或经由所有权人授权，对因照片产生的纠纷由参赛者本人承担。<br/>		07.同一张照片中，不可以出现两个或者两个以上的参赛选手，如遇到两个人或多人在同一张照片中，将视为一人参赛，按1个名额颁发奖品。 <br/>		<br/>		<p>违反规则的投票，主办方有权封ip 剔除非正常数据 取消选手资格等。</p>		<p>本次活动所有解释权归主办方所有</p>';
        $l1ll1ll11ll1l11111l1111l1ll11ll = '本次活动由***公司主办，**公司赞助';
        $lll1l11lllll11llll1l11ll1l1l1l1 = '<p style="text-align: center;">    不管你是宅男、屌丝男、还是貌若潘安的美男子</p><p style="text-align: center;">    不管你是御姐、腐女、萝莉女、还是傲娇的女王</p><p style="text-align: center;">    自己只能刷微信微博看靓照？对这些统统说no！</p><p style="text-align: center;">    只要你有勇气晒出自己的照片并邀请好友来投票，就有机会赢得现金大奖！</p><p style="text-align: center;">    我有我风采，为什么不秀出来！</p><p style="text-align: center;">    晒出手机自拍照，</p><p style="text-align: center;">    用自己的气场HOLD住微信圈，</p><p style="text-align: center;">    让个人的魅力无限扩散！</p><p><br/></p>';
        empty($llll1l11l11111l1l111l11l1ll1111['bodycolor']) && $llll1l11l11111l1l111l11l1ll1111['bodycolor'] = '#6e6e6f';
        empty($llll1l11l11111l1l111l11l1ll1111['boxcolor']) && $llll1l11l11111l1l111l11l1ll1111['boxcolor'] = '#e44f4f';
        empty($llll1l11l11111l1l111l11l1ll1111['titlecolor']) && $llll1l11l11111l1l111l11l1ll1111['titlecolor'] = '#544a4f';
        empty($llll1l11l11111l1l111l11l1ll1111['textcolor']) && $llll1l11l11111l1l111l11l1ll1111['textcolor'] = '#e6d8a1';
        empty($llll1l11l11111l1l111l11l1ll1111['bottomcolor']) && $llll1l11l11111l1l111l11l1ll1111['bottomcolor'] = '#3a3a3a';
        empty($llll1l11l11111l1l111l11l1ll1111['buttoncolor']) && $llll1l11l11111l1l111l11l1ll1111['buttoncolor'] = '#dea543';
        empty($l1ll11111llll11l111l111l1l1l111['rules']) && $l1ll11111llll11l111l111l1l1l111['rules'] = $l1lll1llll1l1l1llll111111111ll1;
        empty($l1ll11111llll11l111l111l1l1l111['detail']) && $l1ll11111llll11l111l111l1l1l111['detail'] = $l1ll1ll11ll1l11111l1111l1ll11ll;
        empty($l1l1lll1l1l1111l1l11lll11l111ll['bottom']) && $l1l1lll1l1l1111l1l11lll11l111ll['bottom'] = $lll1l11lllll11llll1l11ll1l1l1l1;
        empty($llll1l11l11111l1l111l11l1ll1111['double']) && $llll1l11l11111l1l111l11l1ll1111['double'] = 1;
        isset($llll1l11l11111l1l111l11l1ll1111['prize']) or $llll1l11l11111l1l111l11l1ll1111['prize'] = array();
        $llll1l11l11111l1l111l11l1ll1111['prize'] = array_pad($llll1l11l11111l1l111l11l1ll1111['prize'], 10, array(
            'attr' => 0
        ));
        if (count($llll1l11l11111l1l111l11l1ll1111['prize']) >= 1) {
            foreach ($llll1l11l11111l1l111l11l1ll1111['prize'] as &$l1lllll1l1ll11lll1ll1l111ll1l11) {
                $l1lllll1l1ll11lll1ll1l111ll1l11['total'] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid`='" . $llll1l11l11111l1l111l11l1ll1111['id'] . "' AND `attr`='" . $l1lllll1l1ll11lll1ll1l111ll1l11['attr'] . "' AND `name`='" . $l1lllll1l1ll11lll1ll1l111ll1l11['name'] . "'");
            }
        }
        empty($llll1l11l11111l1l111l11l1ll1111['accountqrcode']) && $llll1l11l11111l1l111l11l1ll1111['accountqrcode'] = $_W['account']['qrcode'];
        is_array($llll1l11l11111l1l111l11l1ll1111['thumb']) or $llll1l11l11111l1l111l11l1ll1111['thumb'] = array(
            0 => $llll1l11l11111l1l111l11l1ll1111['thumb']
        );
        is_array($llll1l11l11111l1l111l11l1ll1111['advotepic']) or $llll1l11l11111l1l111l11l1ll1111['advotepic'] = array(
            0 => $llll1l11l11111l1l111l11l1ll1111['advotepic']
        );
        if (!empty($llll1l11l11111l1l111l11l1ll1111['city'])) {
            is_array($llll1l11l11111l1l111l11l1ll1111['city']) or $llll1l11l11111l1l111l11l1ll1111['city'] = array(
                0 => $llll1l11l11111l1l111l11l1ll1111['city']
            );
        }
        $ll1l1ll1lll1lll111l11l11lll111l   = array();
        $ll1l1ll1lll1lll111l11l11lll111l[] = array(
            'sort' => '1',
            'name' => '首页',
            'url' => $_W['siteroot'] . 'app/index.php?c=entry&do=index&m=xiaof_toupiao&i={i}&sid={sid}&wxref=mp.weixin.qq.com#wechat_redirect',
            'icon' => 'fa fa-home',
            'isshow' => '1'
        );
        $ll1l1ll1lll1lll111l11l11lll111l[] = array(
            'sort' => '2',
            'name' => '抽奖',
            'url' => $_W['siteroot'] . 'app/index.php?c=entry&do=creditdraw&m=xiaof_toupiao&i={i}&sid={sid}&wxref=mp.weixin.qq.com#wechat_redirect',
            'icon' => 'fa fa-archive',
            'isshow' => '1'
        );
        $ll1l1ll1lll1lll111l11l11lll111l[] = array(
            'sort' => '3',
            'name' => '报名',
            'url' => $_W['siteroot'] . 'app/index.php?c=entry&do=join&m=xiaof_toupiao&i={i}&sid={sid}&wxref=mp.weixin.qq.com#wechat_redirect',
            'icon' => 'fa fa-edit',
            'isshow' => '1'
        );
        $ll1l1ll1lll1lll111l11l11lll111l[] = array(
            'sort' => '4',
            'name' => '活动详情',
            'url' => $_W['siteroot'] . 'app/index.php?c=entry&do=detail&m=xiaof_toupiao&i={i}&sid={sid}&wxref=mp.weixin.qq.com#wechat_redirect',
            'icon' => 'fa fa-gift',
            'isshow' => '1'
        );
        $ll1l1ll1lll1lll111l11l11lll111l[] = array(
            'sort' => '5',
            'name' => '排行',
            'url' => $_W['siteroot'] . 'app/index.php?c=entry&do=top&m=xiaof_toupiao&i={i}&sid={sid}&wxref=mp.weixin.qq.com#wechat_redirect',
            'icon' => 'fa fa-bar-chart-o',
            'isshow' => '0'
        );
        $ll1l1ll1lll1lll111l11l11lll111l[] = array(
            'sort' => '6',
            'name' => '最新',
            'url' => $_W['siteroot'] . 'app/index.php?c=entry&do=index&type=new&m=xiaof_toupiao&i={i}&sid={sid}&wxref=mp.weixin.qq.com#wechat_redirect',
            'icon' => 'fa fa-bar-chart-o',
            'isshow' => '0'
        );
        $ll1l1ll1lll1lll111l11l11lll111l[] = array(
            'sort' => '7',
            'name' => '我的报名',
            'url' => $_W['siteroot'] . 'app/index.php?c=entry&do=show&m=xiaof_toupiao&i={i}&sid={sid}&wxref=mp.weixin.qq.com#wechat_redirect',
            'icon' => 'fa fa-user',
            'isshow' => '0'
        );
        empty($llll1l11l11111l1l111l11l1ll1111['menu']) && $llll1l11l11111l1l111l11l1ll1111['menu'] = $ll1l1ll1lll1lll111l11l11lll111l;
        if (checksubmit()) {
            if ($_W['isfounder']) {
                $ll11l1111l1ll1111lll1lll1l1llll['copyright'] = $_GPC['copyright'];
            } else {
                $ll11l1111l1ll1111lll1lll1l1llll['copyright'] = $llll1l11l11111l1l111l11l1ll1111['copyright'];
            }
            $ll11l1111l1ll1111lll1lll1l1llll['openmsgvote']      = $_GPC['openmsgvote'];
            $ll11l1111l1ll1111lll1lll1l1llll['title']            = $_GPC['title'];
            $ll11l1111l1ll1111lll1lll1l1llll['describe']         = $_GPC['describe'];
            $ll11l1111l1ll1111lll1lll1l1llll['joinstart']        = $_GPC['jointimes']['start'];
            $ll11l1111l1ll1111lll1lll1l1llll['joinend']          = $_GPC['jointimes']['end'];
            $ll11l1111l1ll1111lll1lll1l1llll['start']            = $_GPC['times']['start'];
            $ll11l1111l1ll1111lll1lll1l1llll['end']              = $_GPC['times']['end'];
            $ll11l1111l1ll1111lll1lll1l1llll['doublestart']      = $_GPC['doubletimes']['start'];
            $ll11l1111l1ll1111lll1lll1l1llll['doubleend']        = $_GPC['doubletimes']['end'];
            $ll11l1111l1ll1111lll1lll1l1llll['double']           = $_GPC['double'];
            $ll11l1111l1ll1111lll1lll1l1llll['newjoindouble']    = intval($_GPC['newjoindouble']);
            $ll11l1111l1ll1111lll1lll1l1llll['maxgoodtime']      = $_GPC['maxgoodtime'];
            $ll11l1111l1ll1111lll1lll1l1llll['maxgoodnum']       = $_GPC['maxgoodnum'];
            $ll11l1111l1ll1111lll1lll1l1llll['verify']           = $_GPC['verify'];
            $ll11l1111l1ll1111lll1lll1l1llll['checkua']          = $_GPC['checkua'];
            $ll11l1111l1ll1111lll1lll1l1llll['openpopularity']   = $_GPC['openpopularity'];
            $ll11l1111l1ll1111lll1lll1l1llll['openvoteuser']     = $_GPC['openvoteuser'];
            $ll11l1111l1ll1111lll1lll1l1llll['indexloadnum']     = $_GPC['indexloadnum'];
            $ll11l1111l1ll1111lll1lll1l1llll['joinendtime']      = intval($_GPC['joinendtime']);
            $ll11l1111l1ll1111lll1lll1l1llll['adminopenid']      = $_GPC['adminopenid'];
            $ll11l1111l1ll1111lll1lll1l1llll['statisticcode']    = $_GPC['statisticcode'];
            $ll11l1111l1ll1111lll1lll1l1llll['binddomain']       = trim($_GPC['binddomain']);
            $ll11l1111l1ll1111lll1lll1l1llll['showshare']        = $_GPC['showshare'];
            $ll11l1111l1ll1111lll1lll1l1llll['noticetitle']      = $_GPC['noticetitle'];
            $ll11l1111l1ll1111lll1lll1l1llll['openvirtualclick'] = $_GPC['openvirtualclick'];
            $ll11l1111l1ll1111lll1lll1l1llll['opengroups']       = $_GPC['opengroups'];
            $ll11l1111l1ll1111lll1lll1l1llll['limitpic']         = intval($_GPC['limitpic']);
            $ll11l1111l1ll1111lll1lll1l1llll['veriftype']        = empty($_GPC['veriftype']) ? array() : $_GPC['veriftype'];
            $ll11l1111l1ll1111lll1lll1l1llll['verifysms']        = $_GPC['verifysms'];
            $ll11l1111l1ll1111lll1lll1l1llll['votejump']         = $_GPC['votejump'];
            $ll11l1111l1ll1111lll1lll1l1llll['minutenum']        = $_GPC['minutenum'];
            $ll11l1111l1ll1111lll1lll1l1llll['releasetime']      = $_GPC['releasetime'];
            $ll11l1111l1ll1111lll1lll1l1llll['ipmaxvote']        = $_GPC['ipmaxvote'];
            $ll11l1111l1ll1111lll1lll1l1llll['vnum']             = $_GPC['vnum'];
            $ll11l1111l1ll1111lll1lll1l1llll['citylevel']        = $_GPC['citylevel'];
            $ll11l1111l1ll1111lll1lll1l1llll['city']             = array_unique($_GPC['city']);
            $ll11l1111l1ll1111lll1lll1l1llll['openwapjoin']      = $_GPC['openwapjoin'];
            $ll11l1111l1ll1111lll1lll1l1llll['votefollow']       = $_GPC['votefollow'];
            $ll11l1111l1ll1111lll1lll1l1llll['joinfollow']       = $_GPC['joinfollow'];
            $ll11l1111l1ll1111lll1lll1l1llll['accountqrcode']    = $_GPC['accountqrcode'];
            $ll11l1111l1ll1111lll1lll1l1llll['advotetype']       = $_GPC['advotetype'];
            $ll11l1111l1ll1111lll1lll1l1llll['advotepic']        = $_GPC['advotepic'];
            $ll11l1111l1ll1111lll1lll1l1llll['advotelink']       = $_GPC['advotelink'];
            $ll11l1111l1ll1111lll1lll1l1llll['limitone']         = $_GPC['limitone'];
            $ll11l1111l1ll1111lll1lll1l1llll['maxvotenum']       = $_GPC['maxvotenum'];
            $ll11l1111l1ll1111lll1lll1l1llll['limitonevote']     = $_GPC['limitonevote'];
            $ll11l1111l1ll1111lll1lll1l1llll['followsetting']    = $_GPC['followsetting'];
            $ll11l1111l1ll1111lll1lll1l1llll['followmode']       = $_GPC['followmode'];
            $ll11l1111l1ll1111lll1lll1l1llll['joinjumplink']     = $_GPC['joinjumplink'];
            $ll11l1111l1ll1111lll1lll1l1llll['audio']            = $_GPC['audio'];
            $ll11l1111l1ll1111lll1lll1l1llll['thumb']            = $_GPC['thumb'];
            $ll11l1111l1ll1111lll1lll1l1llll['thumblink']        = $_GPC['thumblink'];
            $ll11l1111l1ll1111lll1lll1l1llll['template']         = $_GPC['template'];
            $ll11l1111l1ll1111lll1lll1l1llll['bodycolor']        = $_GPC['bodycolor'];
            $ll11l1111l1ll1111lll1lll1l1llll['boxcolor']         = $_GPC['boxcolor'];
            $ll11l1111l1ll1111lll1lll1l1llll['bottomcolor']      = $_GPC['bottomcolor'];
            $ll11l1111l1ll1111lll1lll1l1llll['buttoncolor']      = $_GPC['buttoncolor'];
            $ll11l1111l1ll1111lll1lll1l1llll['titlecolor']       = $_GPC['titlecolor'];
            $ll11l1111l1ll1111lll1lll1l1llll['textcolor']        = $_GPC['textcolor'];
            $ll11l1111l1ll1111lll1lll1l1llll['replythumb']       = $_GPC['replythumb'];
            $ll11l1111l1ll1111lll1lll1l1llll['replytitle']       = $_GPC['replytitle'];
            $ll11l1111l1ll1111lll1lll1l1llll['replycontent']     = $_GPC['replycontent'];
            $ll11l1111l1ll1111lll1lll1l1llll['sharethumb']       = $_GPC['sharethumb'];
            $ll11l1111l1ll1111lll1lll1l1llll['sharetitle']       = preg_replace("#\s#is", '', $_GPC['sharetitle']);
            $ll11l1111l1ll1111lll1lll1l1llll['sharecontent']     = preg_replace("#\s#is", '', $_GPC['sharecontent']);
            $ll11l1111l1ll1111lll1lll1l1llll['mysharetitle']     = $_GPC['mysharetitle'];
            $ll11l1111l1ll1111lll1lll1l1llll['opencredit']       = $_GPC['opencredit'];
            $ll11l1111l1ll1111lll1lll1l1llll['creditnotice']     = $_GPC['creditnotice'];
            $ll11l1111l1ll1111lll1lll1l1llll['dynamicnotice']    = $_GPC['dynamicnotice'];
            $ll11l1111l1ll1111lll1lll1l1llll['joincredit']       = $_GPC['joincredit'];
            $ll11l1111l1ll1111lll1lll1l1llll['verifynotice']     = $_GPC['verifynotice'];
            $ll11l1111l1ll1111lll1lll1l1llll['votecredit']       = $_GPC['votecredit'];
            $ll11l1111l1ll1111lll1lll1l1llll['opendraw']         = $_GPC['opendraw'];
            $ll11l1111l1ll1111lll1lll1l1llll['drawcredit']       = $_GPC['drawcredit'];
            $ll11l1111l1ll1111lll1lll1l1llll['drawlimit']        = $_GPC['drawlimit'];
            $ll11l1111l1ll1111lll1lll1l1llll['prize']            = $_GPC['prize'];
            $ll11l1111l1ll1111lll1lll1l1llll['openposter']       = $_GPC['openposter'];
            $ll11l1111l1ll1111lll1lll1l1llll['qrcodetype']       = $_GPC['qrcodetype'];
            $ll11l1111l1ll1111lll1lll1l1llll['posterbg']         = $_GPC['posterbg'];
            $ll11l1111l1ll1111lll1lll1l1llll['opensound']        = $_GPC['opensound'];
            $ll11l1111l1ll1111lll1lll1l1llll['soundautopaly']    = $_GPC['soundautopaly'];
            $_GPC['joinfield']['sign']                           = array_filter(array_unique($_GPC['joinfield']['sign']));
            foreach ($_GPC['joinfield']['sign'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                $ll1l11lll1llll1llllll11111l1lll                = array();
                $ll1l11lll1llll1llllll11111l1lll['sign']        = $ll111111l1ll11lll1l1l1111lll11l;
                $ll1l11lll1llll1llllll11111l1lll['name']        = $_GPC['joinfield']['name'][$l1lll11llll11ll1lllllllll1l1l11];
                $ll1l11lll1llll1llllll11111l1lll['isnull']      = $_GPC['joinfield']['isnull'][$l1lll11llll11ll1lllllllll1l1l11];
                $ll1l11lll1llll1llllll11111l1lll['isshow']      = $_GPC['joinfield']['isshow'][$l1lll11llll11ll1lllllllll1l1l11];
                $ll11l1111l1ll1111lll1lll1l1llll['joinfield'][] = $ll1l11lll1llll1llllll11111l1lll;
            }
            foreach ($_GPC['menu']['sort'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                if ($ll11l1111l1ll1111lll1lll1l1llll['binddomain'] != $llll1l11l11111l1l111l11l1ll1111['binddomain']) {
                    if (empty($ll11l1111l1ll1111lll1lll1l1llll['binddomain'])) {
                        $_GPC['menu']['url'][$l1lll11llll11ll1lllllllll1l1l11] = str_replace($llll1l11l11111l1l111l11l1ll1111['binddomain'], $_W['siteroot'], $_GPC['menu']['url'][$l1lll11llll11ll1lllllllll1l1l11]);
                    } else {
                        if (empty($llll1l11l11111l1l111l11l1ll1111['binddomain'])) {
                            $_GPC['menu']['url'][$l1lll11llll11ll1lllllllll1l1l11] = str_replace($_W['siteroot'], $ll11l1111l1ll1111lll1lll1l1llll['binddomain'], $_GPC['menu']['url'][$l1lll11llll11ll1lllllllll1l1l11]);
                        } else {
                            $_GPC['menu']['url'][$l1lll11llll11ll1lllllllll1l1l11] = str_replace($llll1l11l11111l1l111l11l1ll1111['binddomain'], $ll11l1111l1ll1111lll1lll1l1llll['binddomain'], $_GPC['menu']['url'][$l1lll11llll11ll1lllllllll1l1l11]);
                        }
                    }
                }
                $l1ll1l11ll1l11l111lll1l11l1l1ll           = array();
                $l1ll1l11ll1l11l111lll1l11l1l1ll['sort']   = $ll111111l1ll11lll1l1l1111lll11l;
                $l1ll1l11ll1l11l111lll1l11l1l1ll['name']   = $_GPC['menu']['name'][$l1lll11llll11ll1lllllllll1l1l11];
                $l1ll1l11ll1l11l111lll1l11l1l1ll['url']    = $_GPC['menu']['url'][$l1lll11llll11ll1lllllllll1l1l11];
                $l1ll1l11ll1l11l111lll1l11l1l1ll['icon']   = $_GPC['menu']['icon'][$l1lll11llll11ll1lllllllll1l1l11];
                $l1ll1l11ll1l11l111lll1l11l1l1ll['isshow'] = $_GPC['menu']['isshow'][$l1lll11llll11ll1lllllllll1l1l11];
                preg_match("#do=(.*)&#iUs", $l1ll1l11ll1l11l111lll1l11l1l1ll['url'], $llll1l11ll1l1111111ll1llllll1l1);
                isset($llll1l11ll1l1111111ll1llllll1l1[1]) && $l1ll1l11ll1l11l111lll1l11l1l1ll['do'] = $llll1l11ll1l1111111ll1llllll1l1[1];
                $ll11l1111l1ll1111lll1lll1l1llll['menu'][] = $l1ll1l11ll1l11l111lll1l11l1l1ll;
            }
            $l1l111l11l1llllll1lll1l11ll1ll1                  = iserializer($ll11l1111l1ll1111lll1lll1l1llll);
            $ll11l111111l1ll1lll111ll11lllll['detail']        = $_GPC['detail'];
            $ll11l111111l1ll1lll111ll11lllll['noticecontent'] = $_GPC['noticecontent'];
            $ll11l111111l1ll1lll111ll11lllll['rules']         = $_GPC['rules'];
            $ll1ll111lll1l111ll111l1l11ll11l                  = iserializer($ll11l111111l1ll1lll111ll11lllll);
            $l11llll11111l1lll1llllllllllll1                  = array();
            $llll1l1111l1l111lll111l1ll1llll                  = 0;
            if (count($llll1l11l11111l1l111l11l1ll1111['groups']) >= 1) {
                $llll1l1111l1l111lll111l1ll1llll = max(array_keys($llll1l11l11111l1l111l11l1ll1111['groups']));
            }
            $_GPC['groupname'] = array_filter(array_unique($_GPC['groupname']));
            foreach ($_GPC['groupname'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                if (empty($_GPC['groupid'][$l1lll11llll11ll1lllllllll1l1l11])) {
                    $llll1l1111l1l111lll111l1ll1llll++;
                    $llllll111l111l1l11111l11l111l11 = $llll1l1111l1l111lll111l1ll1llll;
                } else {
                    $llllll111l111l1l11111l11l111l11 = $_GPC['groupid'][$l1lll11llll11ll1lllllllll1l1l11];
                }
                $l11llll11111l1lll1llllllllllll1[$llllll111l111l1l11111l11l111l11]['name'] = $ll111111l1ll11lll1l1l1111lll11l;
                $l11llll11111l1lll1llllllllllll1[$llllll111l111l1l11111l11l111l11]['sort'] = $_GPC['groupsort'][$l1lll11llll11ll1lllllllll1l1l11];
            }
            $l11llll11111l1lll1llllllllllll1 = iserializer($l11llll11111l1lll1llllllllllll1);
            if ($_GPC['id'] >= 1) {
                pdo_update("xiaof_toupiao_setting", array(
                    "tit" => $_GPC['title'],
                    "data" => $l1l111l11l1llllll1lll1l11ll1ll1,
                    "groups" => $l11llll11111l1lll1llllllllllll1,
                    "unfollow" => intval($_GPC['unfollow']),
                    "detail" => $ll1ll111lll1l111ll111l1l11ll11l,
                    "bottom" => $_GPC['bottom']
                ), array(
                    "id" => intval($_GPC['id'])
                ));
                if (intval($_GPC['clearposter']) == 1) {
                    pdo_update("xiaof_toupiao", array(
                        "poster" => ''
                    ), array(
                        "sid" => intval($_GPC['id'])
                    ));
                }
            } else {
                pdo_insert("xiaof_toupiao_setting", array(
                    "uniacid" => $_W['uniacid'],
                    "tit" => $_GPC['title'],
                    "data" => $l1l111l11l1llllll1lll1l11ll1ll1,
                    "groups" => $l11llll11111l1lll1llllllllllll1,
                    "unfollow" => intval($_GPC['unfollow']),
                    "detail" => $ll1ll111lll1l111ll111l1l11ll11l,
                    "bottom" => $_GPC['bottom'],
                    "created_at" => time()
                ));
                $lll1lll1lll1l11ll1ll1l1ll1l1lll = pdo_insertid();
                message('活动添加成功', $this->createWebUrl('settingedit', array(
                    'sid' => $lll1lll1lll1l11ll1ll1l1ll1l1lll
                )), 'success');
            }
            message('配置参数更新成功！', referer(), 'success');
        }
        include $this->template("settingedit");
    }
    public function doWebBindacid()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        $lll1lll1lll1l11ll1ll1l1ll1l1lll = intval($_GPC['sid']);
        if (empty($lll1lll1lll1l11ll1ll1l1ll1l1lll)) {
            message('参数错误', referer(), 'error');
        }
        if (checksubmit() && isset($_GPC['bindings']['acid'])) {
            $ll1l1ll1lll1lll111l11lll11ll1ll = array();
            $lll1l1ll111l11l111l11lll1111ll1 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_acid') . " WHERE `sid` = :sid", array(
                ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll
            ));
            foreach ($lll1l1ll111l11l111l11lll1111ll1 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                $ll1l1ll1lll1lll111l11lll11ll1ll[] = $ll111111l1ll11lll1l1l1111lll11l['acid'];
            }
            $l1l1l1ll111l1llll1l1ll1l11l11ll = array_unique($_GPC['bindings']['acid']);
            $l11l1lll1l1l111lll1l1l1llll11ll = array_diff($ll1l1ll1lll1lll111l11lll11ll1ll, $l1l1l1ll111l1llll1l1ll1l11l11ll);
            if (count($l11l1lll1l1l111lll1l1l1llll11ll) >= 1) {
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_acid') . " WHERE `sid` = '" . $lll1lll1lll1l11ll1ll1l1ll1l1lll . "' AND `acid` IN ('" . implode("','", $l11l1lll1l1l111lll1l1l1llll11ll) . "')");
            }
            foreach ($l1l1l1ll111l1llll1l1ll1l11l11ll as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                if ($llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_acid") . " WHERE `sid` = :sid AND `acid` = :acid", array(
                    ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                    ":acid" => intval($ll111111l1ll11lll1l1l1111lll11l)
                ))) {
                    pdo_update("xiaof_toupiao_acid", array(
                        "qrcode" => $_GPC['bindings']['qrcode'][$l1lll11llll11ll1lllllllll1l1l11]
                    ), array(
                        "id" => $llll1l11l11111l1l111l11l1ll1111['id']
                    ));
                } else {
                    pdo_insert("xiaof_toupiao_acid", array(
                        "sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                        "acid" => intval($ll111111l1ll11lll1l1l1111lll11l),
                        "qrcode" => $_GPC['bindings']['qrcode'][$l1lll11llll11ll1lllllllll1l1l11]
                    ));
                }
            }
        }
        $lll1l1ll111l11l111l11lll1111ll1 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_acid') . " WHERE `sid` = :sid", array(
            ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll
        ));
        load()->model('account');
        $lll11l11l1l111l11llll1ll1llll11 = uni_owned();
        include $this->template("bindacid");
    }
    public function doWebLists()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        if (!empty($_GPC['verifys'])) {
            if (empty($_GPC['pid'])) {
                exit('参数错误');
            }
            $l1l111l11l1llllll1lll1l11ll1ll1 = array(
                "verify" => intval($_GPC['verifys'])
            );
            $l111ll1l1l11111ll11l1l1ll1l111l = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id", array(
                ":id" => intval($_GET['pid'])
            ));
            if ($lll1111l1l1l111llll1ll1llll1ll1 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_setting") . " WHERE `id` = :id", array(
                ":id" => $l111ll1l1l11111ll11l1l1ll1l111l['sid']
            ))) {
                $lll1l1ll11l111lllll1l1l1l1lll11 = iunserializer($lll1111l1l1l111llll1ll1llll1ll1['data']);
                if ($_GPC['verifys'] == 3) {
                    $l1lll1ll11lllll11ll11111ll1l11l               = intval($lll1l1ll11l111lllll1l1l1l1lll11['releasetime']);
                    $l1l111l11l1llllll1lll1l11ll1ll1['locking_at'] = strtotime('' . $l1lll1ll11lllll11ll11111ll1l11l . ' minute');
                }
                if (intval($lll1l1ll11l111lllll1l1l1l1lll11['verifynotice']) >= 1) {
                    $l111l1111lll1111l111lll11111l1l = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `openid` = :openid limit 1", array(
                        ":openid" => $l111ll1l1l11111ll11l1l1ll1l111l['openid']
                    ));
                    isset($_SESSION) or session_start();
                    $_SESSION['sid'] = $lll1111l1l1l111llll1ll1llll1ll1['id'];
                    if ($_GPC['verifys'] == 1) {
                        $this->ll11111l1l11111l1ll1ll11l111111($l111ll1l1l11111ll11l1l1ll1l111l['openid'], "您参与报名的" . $lll1111l1l1l111llll1ll1llll1ll1['tit'] . "活动，资料审核已经通过。<a href='" . $this->l1111llll1l1llll11l1lll1l11l1ll('show', 'xiaof_toupiao', '&id=' . $l111ll1l1l11111ll11l1l1ll1l111l['id']) . "'>点击查看</a>", $l111l1111lll1111l111lll11111l1l['uniacid']);
                    } else if ($_GPC['verifys'] == 2) {
                        $this->ll11111l1l11111l1ll1ll11l111111($l111ll1l1l11111ll11l1l1ll1l111l['openid'], "您参与报名的" . $lll1111l1l1l111llll1ll1llll1ll1['tit'] . "活动，资料审核未通过。<a href='" . $this->l1111llll1l1llll11l1lll1l11l1ll('show', 'xiaof_toupiao', '&id=' . $l111ll1l1l11111ll11l1l1ll1l111l['id']) . "'>点击查看</a>", $l111l1111lll1111l111lll11111l1l['uniacid']);
                    }
                }
            }
            pdo_update("xiaof_toupiao", $l1l111l11l1llllll1lll1l11ll1ll1, array(
                "id" => intval($_GPC['pid'])
            ));
        }
        if (!empty($_GPC['del'])) {
            $l1l111l1lll1l1111ll1l1l1ll111ll = array();
            foreach ($_GPC['delete'] as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l111l1lll1l1111ll1l1l1ll111ll[] = intval($ll111111l1ll11lll1l1l1111lll11l);
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_pic') . " WHERE `pid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
                pdo_query("DELETE FROM " . tablename('xiaof_toupiao_log') . " WHERE `pid` = '" . $ll111111l1ll11lll1l1l1111lll11l . "'");
            }
            pdo_query("DELETE FROM " . tablename('xiaof_toupiao') . " WHERE `id` IN ('" . implode("','", $l1l111l1lll1l1111ll1l1l1ll111ll) . "')");
        }
        if (!empty($_GPC['pass'])) {
            $l1l111l1lll1l1111ll1l1l1ll111ll = array();
            foreach ($_GPC['delete'] as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l111l1lll1l1111ll1l1l1ll111ll[] = intval($ll111111l1ll11lll1l1l1111lll11l);
            }
            pdo_query("UPDATE " . tablename('xiaof_toupiao') . " SET `verify` = '1' WHERE `id` IN ('" . implode("','", $l1l111l1lll1l1111ll1l1l1ll111ll) . "')");
        }
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
            ":uniacid" => $_W['uniacid']
        ));
        $l11ll11lllllll1l1llll11ll11ll1l = '';
        if (!empty($_GPC['sid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid';
            $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
            $l11ll11lllllll1l1llll11ll11ll1l         = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        } else {
            $l1lll11l11111ll11111l1l1ll11111 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
            }
            $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `sid` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')";
            $l11l1ll1llll11111l11lllll1l1ll1 = array();
        }
        if (!empty($_GPC['groups'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `groups`=:groups';
            $l11l1ll1llll11111l11lllll1l1ll1[':groups'] = intval($_GPC['groups']);
        }
        if (!empty($_GPC['key'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND (`uid`=:uid OR `name` like :name) ';
            $l11l1ll1llll11111l11lllll1l1ll1[':uid']  = intval($_GPC['key']);
            $l11l1ll1llll11111l11lllll1l1ll1[':name'] = "%" . $_GPC['key'] . "%";
            $llll11lllll111l1l1l1l11111ll111          = $_GPC['key'];
        }
        if (!empty($_GPC['verify']) or $_GPC['verify'] === '0') {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `verify`=:verify';
            $l11l1ll1llll11111l11lllll1l1ll1[':verify'] = intval($_GPC['verify']);
        }
        if (empty($_GPC['order'])) {
            $l1lll1l11l11l1lll1llll1l1l11ll1 = 'id';
        } else {
            $l1lll1l11l11l1lll1llll1l1l11ll1 = 'good';
        }
        $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
        $l11ll1l1111l11lll1ll1111lll1111 = 20;
        $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao') . $l1l1lll11l111l111ll1l11l1111ll1, $l11l1ll1llll11111l11lllll1l1ll1);
        $ll1111l1l1l11ll1111llll11l1ll11 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao') . $l1l1lll11l111l111ll1l11l1111ll1 . " ORDER BY `" . $l1lll1l11l11l1lll1llll1l1l11ll1 . "` DESC LIMIT " . ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111, $l11l1ll1llll11111l11lllll1l1ll1);
        $llll11ll1ll111l1l1ll111l1ll1111 = pagination($l1l1ll1ll1ll11llll1l1l1l111llll, $ll1l11l111l1l11111ll1l11l11ll1l, $l11ll1l1111l11lll1ll1111lll1111);
        include $this->template("lists");
    }
    public function doWebLoadgroup()
    {
        global $_GPC;
        if (!isset($_GPC['sid'])) {
            exit('缺少参数');
        }
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        $ll11l1111ll111lll1ll1111l11ll11 = $l1l1llllll11l111l1ll11llll1l1ll = '';
        foreach ($l11ll11lllllll1l1llll11ll11ll1l['groups'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            $l1l1llllll11l111l1ll11llll1l1ll .= '<option value="' . $l1lll11llll11ll1lllllllll1l1l11 . '">' . $ll111111l1ll11lll1l1l1111lll11l['name'] . '</option>';
        }
        foreach ($l11ll11lllllll1l1llll11ll11ll1l['joinfield'] as $l1l1lll111lll1llll1l1l1l1lllll1) {
            $ll11l1111ll111lll1ll1111l11ll11 .= '<div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label" for="">' . $l1l1lll111lll1llll1l1l1l1lllll1['name'] . '</label>
                <div class="col-sm-6"><input type="text" class="form-control" name="' . $l1l1lll111lll1llll1l1l1l1lllll1['sign'] . '" placeholder="' . $l1l1lll111lll1llll1l1l1l1lllll1['name'] . '" /></div>
            </div>';
        }
        echo json_encode(array(
            'groups' => $l1l1llllll11l111l1ll11llll1l1ll,
            'joinfield' => $ll11l1111ll111lll1ll1111l11ll11
        ));
    }
    public function doWebEdit()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        load()->func('tpl');
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
            ":uniacid" => $_W['uniacid']
        ));
        if (isset($_GET['pid'])) {
            $llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id", array(
                ":id" => intval($_GET['pid'])
            ));
            $lll1l1lllll1111l111ll111111ll1l = pdo_fetchall("SELECT `url` FROM " . tablename('xiaof_toupiao_pic') . " WHERE `pid` = :pid", array(
                ":pid" => intval($_GET['pid'])
            ));
            $_GPC['sid']                     = $llll1l11l11111l1l111l11l1ll1111['sid'];
            foreach ($lll1l1lllll1111l111ll111111ll1l as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll1l11l111l111ll11111l111111[] = $ll111111l1ll11lll1l1l1111lll11l['url'];
            }
            $llll1l11l11111l1l111l11l1ll1111['pics'] = $l1lll1l11l111l111ll11111l111111;
            $llll1l11l11111l1l111l11l1ll1111['data'] = iunserializer($llll1l11l11111l1l111l11l1ll1111['data']);
        }
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        if ($_W['ispost']) {
            load()->func('file');
            $ll11ll1l1lll11ll11ll111ll1llll1 = array();
            if (is_array($l11ll11lllllll1l1llll11ll11ll1l['joinfield'])) {
                foreach ($l11ll11lllllll1l1llll11ll11ll1l['joinfield'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                    if (empty($_GPC[$ll111111l1ll11lll1l1l1111lll11l['sign']])) {
                        if (empty($ll111111l1ll11lll1l1l1111lll11l['isnull'])) {
                            message($ll111111l1ll11lll1l1l1111lll11l['name'] . '项不能为空', referer(), 'error');
                        }
                        continue;
                    }
                    $ll11ll1l1lll11ll11ll111ll1llll1[$ll111111l1ll11lll1l1l1111lll11l['sign']] = $_GPC[$ll111111l1ll11lll1l1l1111lll11l['sign']];
                }
            }
            $ll111111lll111l111ll1111l111ll1 = '';
            if (!empty($_GPC['sound'])) {
                $ll11ll1lll111l1lllllllll1l11111 = $_GPC['sound'];
                if (strexists($ll11ll1lll111l1lllllllll1l11111, 'http://') || strexists($ll11ll1lll111l1lllllllll1l11111, 'https://') || !empty($_W['setting']['remote']['type'])) {
                    $ll111111lll111l111ll1111l111ll1 = $ll11ll1lll111l1lllllllll1l11111;
                } else {
                    $l1lll1l111l11llll11lll11lll1ll1 = $this->l11ll1l11l111l1llll11lll1111ll1($_GPC['sound']);
                    if (is_error($l1lll1l111l11llll11lll11lll1ll1)) {
                        $ll111111lll111l111ll1111l111ll1 = '';
                    } else {
                        $ll111111lll111l111ll1111l111ll1 = $l1lll1l111l11llll11lll11lll1ll1;
                    }
                }
            }
            $l11l1lll11ll11l1111ll1l11111l1l = count($_GPC['pics']);
            $l1l1111ll111l1llll11111l1ll1111 = empty($l11ll11lllllll1l1llll11ll11ll1l['limitpic']) ? 5 : intval($l11ll11lllllll1l1llll11ll11ll1l['limitpic']);
            if ($l11l1lll11ll11l1111ll1l11111l1l <= 0) {
                message('至少上传一张照片', referer(), 'error');
            } elseif ($l11l1lll11ll11l1111ll1l11111l1l > $l1l1111ll111l1llll11111l1ll1111) {
                message('照片只允许1-' . $l1l1111ll111l1llll11111l1ll1111 . '张', referer(), 'error');
            }
            if ($_GPC['id'] >= 1) {
                $ll11111ll111l1llllllllll11l11l1 = intval($_GPC['id']);
                if (!empty($_W['setting']['remote']['type'])) {
                    $l1llll1ll1l111ll11111lll11l1ll1 = reset($_GPC['pics']);
                } else {
                    $l1llll1ll1l111ll11111lll11l1ll1 = $this->ll1l11111l1111111lllll11lll11l1(reset($_GPC['pics']), 240);
                }
                pdo_update("xiaof_toupiao", array(
                    "groups" => intval($_GPC['groups']),
                    "pic" => $l1llll1ll1l111ll11111lll11l1ll1,
                    "sound" => $ll111111lll111l111ll1111l111ll1,
                    "phone" => $_GPC['phone'],
                    "name" => $_GPC['name'],
                    "verify" => intval($_GPC['verify']),
                    "describe" => preg_replace("#\s#is", '', $_GPC['describe']),
                    "detail" => $_GPC['detail'],
                    "data" => iserializer($ll11ll1l1lll11ll11ll111ll1llll1)
                ), array(
                    "id" => $ll11111ll111l1llllllllll11l11l1
                ));
                if ($_GPC['pics'] !== $llll1l11l11111l1l111l11l1ll1111['pics']) {
                    pdo_delete('xiaof_toupiao_pic', array(
                        'pid' => $ll11111ll111l1llllllllll11l11l1
                    ));
                    foreach ($_GPC['pics'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                        if (!empty($_W['setting']['remote']['type'])) {
                            $l11lll1ll11l1ll1l1ll111lllll1ll = $ll111111l1ll11lll1l1l1111lll11l;
                        } else {
                            $l11lll1ll11l1ll1l1ll111lllll1ll = $this->ll1l11111l1111111lllll11lll11l1($ll111111l1ll11lll1l1l1111lll11l);
                        }
                        pdo_insert("xiaof_toupiao_pic", array(
                            "pid" => $ll11111ll111l1llllllllll11l11l1,
                            "url" => $ll111111l1ll11lll1l1l1111lll11l,
                            "thumb" => $l11lll1ll11l1ll1l1ll111lllll1ll,
                            "created_at" => time()
                        ));
                    }
                }
            } else {
                $lll1lll1lll1l11ll1ll1l1ll1l1lll = intval($_GPC['sid']);
                if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `phone` = :phone", array(
                    ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                    ":phone" => $_GPC['phone']
                ))) {
                    message('该手机号已经参加本次活动', referer(), 'error');
                }
                if (!empty($_W['setting']['remote']['type'])) {
                    $l1llll1ll1l111ll11111lll11l1ll1 = reset($_GPC['pics']);
                } else {
                    $l1llll1ll1l111ll11111lll11l1ll1 = $this->ll1l11111l1111111lllll11lll11l1(reset($_GPC['pics']), 240);
                }
                pdo_query("LOCK TABLES " . tablename("xiaof_toupiao") . " WRITE");
                if (!$ll11l11llll11lll1ll111l1111ll1l = pdo_fetchcolumn("SELECT `uid` FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid ORDER BY `id` DESC limit 1", array(
                    ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll
                ))) {
                    $ll11l11llll11lll1ll111l1111ll1l = 0;
                }
                pdo_insert("xiaof_toupiao", array(
                    "sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                    "groups" => intval($_GPC['groups']),
                    "uid" => $ll11l11llll11lll1ll111l1111ll1l + 1,
                    "openid" => 'mn-' . md5($lll1lll1lll1l11ll1ll1l1ll1l1lll . $_GPC['phone']),
                    "pic" => $l1llll1ll1l111ll11111lll11l1ll1,
                    "sound" => $ll111111lll111l111ll1111l111ll1,
                    "phone" => $_GPC['phone'],
                    "name" => $_GPC['name'],
                    "verify" => intval($_GPC['verify']),
                    "open" => intval($_GPC['open']),
                    "describe" => preg_replace("#\s#is", '', $_GPC['describe']),
                    "detail" => $_GPC['detail'],
                    "data" => iserializer($ll11ll1l1lll11ll11ll111ll1llll1),
                    "created_at" => time(),
                    "updated_at" => time()
                ));
                $ll11111ll111l1llllllllll11l11l1 = pdo_insertid();
                pdo_query("UNLOCK TABLES");
                foreach ($_GPC['pics'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                    if (!empty($_W['setting']['remote']['type'])) {
                        $l11lll1ll11l1ll1l1ll111lllll1ll = $ll111111l1ll11lll1l1l1111lll11l;
                    } else {
                        $l11lll1ll11l1ll1l1ll111lllll1ll = $this->ll1l11111l1111111lllll11lll11l1($ll111111l1ll11lll1l1l1111lll11l);
                    }
                    pdo_insert("xiaof_toupiao_pic", array(
                        "sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                        "pid" => $ll11111ll111l1llllllllll11l11l1,
                        "thumb" => $l11lll1ll11l1ll1l1ll111lllll1ll,
                        "url" => $ll111111l1ll11lll1l1l1111lll11l,
                        "created_at" => time()
                    ));
                }
            }
            $l1l1lll11l111l111ll1l11l1111ll1 = "";
            if (intval($_GPC['goodvalue']) >= 1) {
                if ($_GPC['goodalgorithm'] == '+') {
                    $l1l1lll11l111l111ll1l11l1111ll1 = "`good` = good+" . intval($_GPC['goodvalue']);
                } elseif ($_GPC['goodalgorithm'] == '-') {
                    $l1l1lll11l111l111ll1l11l1111ll1 = "`good` = good-" . intval($_GPC['goodvalue']);
                }
            }
            if (intval($_GPC['sharevalue']) >= 1) {
                if ($_GPC['sharealgorithm'] == '+') {
                    ($l1l1lll11l111l111ll1l11l1111ll1 != "") && $l1l1lll11l111l111ll1l11l1111ll1 .= " , ";
                    $l1l1lll11l111l111ll1l11l1111ll1 .= "`share` = share+" . intval($_GPC['sharevalue']);
                } elseif ($_GPC['sharealgorithm'] == '-') {
                    ($l1l1lll11l111l111ll1l11l1111ll1 != "") && $l1l1lll11l111l111ll1l11l1111ll1 .= " , ";
                    $l1l1lll11l111l111ll1l11l1111ll1 .= "`share` = share-" . intval($_GPC['sharevalue']);
                }
            }
            if (intval($_GPC['clickvalue']) >= 1) {
                if ($_GPC['clickalgorithm'] == '+') {
                    ($l1l1lll11l111l111ll1l11l1111ll1 != "") && $l1l1lll11l111l111ll1l11l1111ll1 .= " , ";
                    $l1l1lll11l111l111ll1l11l1111ll1 .= "`click` = click+" . intval($_GPC['clickvalue']);
                } elseif ($_GPC['clickalgorithm'] == '-') {
                    ($l1l1lll11l111l111ll1l11l1111ll1 != "") && $l1l1lll11l111l111ll1l11l1111ll1 .= " , ";
                    $l1l1lll11l111l111ll1l11l1111ll1 .= "`click` = click-" . intval($_GPC['clickvalue']);
                }
            }
            if ($l1l1lll11l111l111ll1l11l1111ll1 != "") {
                pdo_query("UPDATE " . tablename("xiaof_toupiao") . " SET " . $l1l1lll11l111l111ll1l11l1111ll1 . " WHERE `id` = '" . $ll11111ll111l1llllllllll11l11l1 . "'");
            }
            if (intval($l11ll11lllllll1l1llll11ll11ll1l['openposter']) == 1 && $lllll111l1ll1l1l1l1l1ll11ll1ll1 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id LIMIT 1", array(
                ":id" => intval($ll11111ll111l1llllllllll11l11l1)
            ))) {
                $l1l1lllll11ll1llll1llll1lll1l11 = $this->l111ll11llllllll111ll1l1l1l111l($lllll111l1ll1l1l1l1l1ll11ll1ll1['name'], $lllll111l1ll1l1l1l1l1ll11ll1ll1['uid'], tomedia(reset($_GPC['pics'])), urlencode($this->l1111llll1l1llll11l1lll1l11l1ll('show', 'xiaof_toupiao', '&id=' . $lllll111l1ll1l1l1l1l1ll11ll1ll1['id'] . '')));
                pdo_update("xiaof_toupiao", array(
                    "poster" => $l1l1lllll11ll1llll1llll1lll1l11
                ), array(
                    "id" => $lllll111l1ll1l1l1l1l1ll11ll1ll1['id']
                ));
            }
            message('信息编辑成功', referer(), 'success');
        }
        include $this->template("edit");
    }
    public function doWebVotelog()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
            ":uniacid" => $_W['uniacid']
        ));
        $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
        $l11ll1l1111l11lll1ll1111lll1111 = 10;
        $l11l1ll1llll11111l11lllll1l1ll1 = array();
        if (!empty($_GPC['sid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
        } else {
            $l1lll11l11111ll11111l1l1ll11111 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
            }
            $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `sid` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')";
        }
        if (!empty($_GPC['uid'])) {
            if ($l11l1ll1l1lll1l1ll1l1l111111111 = pdo_fetchall("SELECT * FROM " . tablename("xiaof_toupiao") . $l1l1lll11l111l111ll1l11l1111ll1 . " AND `uid` = '" . intval($_GPC['uid']) . "'", $l11l1ll1llll11111l11lllll1l1ll1)) {
                $lll11l11ll1l1111l11l11lll1ll1l1 = array();
                foreach ($l11l1ll1l1lll1l1ll1l1l111111111 as $ll111111l1ll11lll1l1l1111lll11l) {
                    $lll11l11ll1l1111l11l11lll1ll1l1[] = $ll111111l1ll11lll1l1l1111lll11l['id'];
                }
                $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `pid` in ('" . implode("','", $lll11l11ll1l1111l11l11lll1ll1l1) . "')";
            }
        } elseif (!empty($_GPC['pid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `pid`=:pid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':pid'] = intval($_GPC['pid']);
        } elseif (!empty($_GPC['ip'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `ip`=:ip ';
            $l11l1ll1llll11111l11lllll1l1ll1[':ip'] = $_GPC['ip'];
        }
        if (!empty($_GPC['vopenid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `openid`=:openid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':openid'] = $_GPC['vopenid'];
        }
        if (!empty($_GPC['valid']) or $_GPC['valid'] == '0') {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `valid`=:valid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':valid'] = $_GPC['valid'];
        }
        $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . $l1l1lll11l111l111ll1l11l1111ll1 . "", $l11l1ll1llll11111l11lllll1l1ll1);
        $l1lll11111l11l1111111ll1l11ll11 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_log') . $l1l1lll11l111l111ll1l11l1111ll1 . " order by `created_at` desc LIMIT " . ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111, $l11l1ll1llll11111l11lllll1l1ll1);
        $ll1111l1l1l11ll1111llll11l1ll11 = array();
        foreach ($l1lll11111l11l1111111ll1l11ll11 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            $llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id", array(
                ":id" => $ll111111l1ll11lll1l1l1111lll11l['pid']
            ));
            if (empty($ll111111l1ll11lll1l1l1111lll11l['nickname'])) {
                if (!empty($ll111111l1ll11lll1l1l1111lll11l['fanid'])) {
                    if ($l1l11ll11l1111ll111l1ll11ll1l1l = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE `fanid` = :fanid limit 1", array(
                        ":fanid" => $ll111111l1ll11lll1l1l1111lll11l['fanid']
                    ))) {
                        $llll1l11l11111l1l111l11l1ll1111['nickname'] = $l1l11ll11l1111ll111l1ll11ll1l1l['nickname'];
                    }
                } else {
                    if ($l1l11ll11l1111ll111l1ll11ll1l1l = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE `openid` = :openid limit 1", array(
                        ":openid" => $ll111111l1ll11lll1l1l1111lll11l['openid']
                    ))) {
                        $llll1l11l11111l1l111l11l1ll1111['nickname'] = $l1l11ll11l1111ll111l1ll11ll1l1l['nickname'];
                    }
                }
            }
            $llll1l11l11111l1l111l11l1ll1111['ocount'] = pdo_fetchcolumn("SELECT COUNT(1) FROM (SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l['sid'] . "' AND `ip` = '" . $ll111111l1ll11lll1l1l1111lll11l['ip'] . "'  group by `openid`) T");
            $llll1l11l11111l1l111l11l1ll1111['count']  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l['sid'] . "' AND `ip` = '" . $ll111111l1ll11lll1l1l1111lll11l['ip'] . "'");
            $llll1l11l11111l1l111l11l1ll1111['hide']   = 0;
            if ($l1l1l1l1ll1ll1ll1l11l111ll1l111 = pdo_fetch("SELECT `id` FROM " . tablename("xiaof_toupiao_safe") . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l['sid'] . "' AND `ip` = :ip ", array(
                ":ip" => $ll111111l1ll11lll1l1l1111lll11l['ip']
            ))) {
                $llll1l11l11111l1l111l11l1ll1111['hide']   = 1;
                $llll1l11l11111l1l111l11l1ll1111['safeid'] = $l1l1l1l1ll1ll1ll1l11l111ll1l111['id'];
            }
            if (!$l1ll1lll1ll1lll1111ll1111llll1l = cache_read('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']))) {
                $ll1111l111l1l1lllll1l1111l1l111 = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . long2ip($ll111111l1ll11lll1l1l1111lll11l['ip']));
                $lllll11ll1lllllll1l1l11lll111ll = json_decode($ll1111l111l1l1lllll1l1111l1l111);
                if (!empty($lllll11ll1lllllll1l1l11lll111ll->code) or $ll111111l1ll11lll1l1l1111lll11l['ip'] == '2147483647') {
                    cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), '未知');
                } else {
                    $l1ll1lll1ll1lll1111ll1111llll1l = $lllll11ll1lllllll1l1l11lll111ll->data->region . $lllll11ll1lllllll1l1l11lll111ll->data->city . $lllll11ll1lllllll1l1l11lll111ll->data->isp . $lllll11ll1lllllll1l1l11lll111ll->data->county;
                    if (empty($l1ll1lll1ll1lll1111ll1111llll1l)) {
                        cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), '未知');
                    } else {
                        cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), $l1ll1lll1ll1lll1111ll1111llll1l);
                    }
                }
            }
            $llll1l11l11111l1l111l11l1ll1111['created_at'] = $ll111111l1ll11lll1l1l1111lll11l['created_at'];
            $llll1l11l11111l1l111l11l1ll1111['ip']         = $ll111111l1ll11lll1l1l1111lll11l['ip'];
            $llll1l11l11111l1l111l11l1ll1111['openid']     = $ll111111l1ll11lll1l1l1111lll11l['openid'];
            $llll1l11l11111l1l111l11l1ll1111['nickname']   = $ll111111l1ll11lll1l1l1111lll11l['nickname'];
            $llll1l11l11111l1l111l11l1ll1111['avatar']     = $ll111111l1ll11lll1l1l1111lll11l['avatar'];
            $llll1l11l11111l1l111l11l1ll1111['dz']         = $l1ll1lll1ll1lll1111ll1111llll1l;
            $ll1111l1l1l11ll1111llll11l1ll11[]             = array_merge($ll111111l1ll11lll1l1l1111lll11l, $llll1l11l11111l1l111l11l1ll1111);
        }
        $llll11ll1ll111l1l1ll111l1ll1111 = pagination($l1l1ll1ll1ll11llll1l1l1l111llll, $ll1l11l111l1l11111ll1l11l11ll1l, $l11ll1l1111l11lll1ll1111lll1111);
        include $this->template("votelog");
    }
    public function doWebExcle()
    {
        set_time_limit(0);
        global $_W, $_GPC;
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=投票日志-" . date('Y-m-d') . ".csv");
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
            ":uniacid" => $_W['uniacid']
        ));
        if (!empty($_GPC['sid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
        } else {
            $l1lll11l11111ll11111l1l1ll11111 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
            }
            $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `sid` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')";
        }
        if (!empty($_GPC['uid'])) {
            if ($l11l1ll1l1lll1l1ll1l1l111111111 = pdo_fetchall("SELECT * FROM " . tablename("xiaof_toupiao") . $l1l1lll11l111l111ll1l11l1111ll1 . " AND `uid` = '" . intval($_GPC['uid']) . "'", $l11l1ll1llll11111l11lllll1l1ll1)) {
                $lll11l11ll1l1111l11l11lll1ll1l1 = array();
                foreach ($l11l1ll1l1lll1l1ll1l1l111111111 as $ll111111l1ll11lll1l1l1111lll11l) {
                    $lll11l11ll1l1111l11l11lll1ll1l1[] = $ll111111l1ll11lll1l1l1111lll11l['id'];
                }
                $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `pid` in ('" . implode("','", $lll11l11ll1l1111l11l11lll1ll1l1) . "')";
            }
        } elseif (!empty($_GPC['pid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `pid`=:pid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':pid'] = intval($_GPC['pid']);
        } elseif (!empty($_GPC['ip'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `ip`=:ip ';
            $l11l1ll1llll11111l11lllll1l1ll1[':ip'] = $_GPC['ip'];
        }
        if (!empty($_GPC['vopenid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `openid`=:openid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':openid'] = $_GPC['vopenid'];
        }
        if (!empty($_GPC['valid']) or $_GPC['valid'] == '0') {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `valid`=:valid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':valid'] = $_GPC['valid'];
        }
        $ll11l1l1ll11ll11ll1l11ll1llll1l = fopen('php://output', 'a');
        $ll111l111l1l1l111111111llll1ll1 = array(
            'uid' => '选手编号',
            'name' => '投票昵称',
            'phone' => '投票openid',
            'type' => '投票ip',
            'num' => '同ip投票数',
            'store_cn' => '同ip登陆微信数',
            'clerk_cn' => 'ip地区',
            'createtime' => '投票时间'
        );
        fputcsv($ll11l1l1ll11ll11ll1l11ll1llll1l, $ll111l111l1l1l111111111llll1ll1);
        $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . $l1l1lll11l111l111ll1l11l1111ll1 . "", $l11l1ll1llll11111l11lllll1l1ll1);
        $l11ll1l1111l11lll1ll1111lll1111 = 10000;
        for ($l1llll111lll1l11lll111ll11l11ll = 0; $l1llll111lll1l11lll111ll11l11ll < intval($l1l1ll1ll1ll11llll1l1l1l111llll / $l11ll1l1111l11lll1ll1111lll1111) + 1; $l1llll111lll1l11lll111ll11l11ll++) {
            $l1lll11111l11l1111111ll1l11ll11 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_log') . $l1l1lll11l111l111ll1l11l1111ll1 . " order by `id` desc  LIMIT " . $l1llll111lll1l11lll111ll11l11ll * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111, $l11l1ll1llll11111l11lllll1l1ll1);
            foreach ($l1lll11111l11l1111111ll1l11ll11 as $ll111111l1ll11lll1l1l1111lll11l) {
                $llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id", array(
                    ":id" => $ll111111l1ll11lll1l1l1111lll11l['pid']
                ));
                if (empty($ll111111l1ll11lll1l1l1111lll11l['nickname'])) {
                    if (!empty($ll111111l1ll11lll1l1l1111lll11l['fanid'])) {
                        if ($l1l11ll11l1111ll111l1ll11ll1l1l = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE `fanid` = :fanid limit 1", array(
                            ":fanid" => $ll111111l1ll11lll1l1l1111lll11l['fanid']
                        ))) {
                            $llll1l11l11111l1l111l11l1ll1111['nickname'] = $l1l11ll11l1111ll111l1ll11ll1l1l['nickname'];
                        }
                    } else {
                        if ($l1l11ll11l1111ll111l1ll11ll1l1l = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE `openid` = :openid limit 1", array(
                            ":openid" => $ll111111l1ll11lll1l1l1111lll11l['openid']
                        ))) {
                            $llll1l11l11111l1l111l11l1ll1111['nickname'] = $l1l11ll11l1111ll111l1ll11ll1l1l['nickname'];
                        }
                    }
                }
                $llll1l11l11111l1l111l11l1ll1111['ocount'] = pdo_fetchcolumn("SELECT COUNT(1) FROM (SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l['sid'] . "' AND `ip` = '" . $ll111111l1ll11lll1l1l1111lll11l['ip'] . "'  group by `openid`) T");
                $llll1l11l11111l1l111l11l1ll1111['count']  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l['sid'] . "' AND `ip` = '" . $ll111111l1ll11lll1l1l1111lll11l['ip'] . "'");
                if (!$l1ll1lll1ll1lll1111ll1111llll1l = cache_read('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']))) {
                    $ll1111l111l1l1lllll1l1111l1l111 = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . long2ip($ll111111l1ll11lll1l1l1111lll11l['ip']));
                    $lllll11ll1lllllll1l1l11lll111ll = json_decode($ll1111l111l1l1lllll1l1111l1l111);
                    if (!empty($lllll11ll1lllllll1l1l11lll111ll->code) or $ll111111l1ll11lll1l1l1111lll11l['ip'] == '2147483647') {
                        cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), '未知');
                    } else {
                        $l1ll1lll1ll1lll1111ll1111llll1l = $lllll11ll1lllllll1l1l11lll111ll->data->region . $lllll11ll1lllllll1l1l11lll111ll->data->city . $lllll11ll1lllllll1l1l11lll111ll->data->isp . $lllll11ll1lllllll1l1l11lll111ll->data->county;
                        if (empty($l1ll1lll1ll1lll1111ll1111llll1l)) {
                            cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), '未知');
                        } else {
                            cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), $l1ll1lll1ll1lll1111ll1111llll1l);
                        }
                    }
                }
                $lllll111l1ll1l1l1l1l1ll11ll1ll1 = array(
                    'uid' => $llll1l11l11111l1l111l11l1ll1111['uid'],
                    'name' => $ll111111l1ll11lll1l1l1111lll11l['nickname'],
                    'phone' => $ll111111l1ll11lll1l1l1111lll11l['openid'],
                    'type' => long2ip($ll111111l1ll11lll1l1l1111lll11l['ip']),
                    'num' => $llll1l11l11111l1l111l11l1ll1111['count'],
                    'store_cn' => $llll1l11l11111l1l111l11l1ll1111['ocount'],
                    'clerk_cn' => $l1ll1lll1ll1lll1111ll1111llll1l,
                    'createtime' => date('Y-m-d H:i:s', $ll111111l1ll11lll1l1l1111lll11l['created_at'])
                );
                fputcsv($ll11l1l1ll11ll11ll1l11ll1llll1l, $lllll111l1ll1l1l1l1l1ll11ll1ll1);
            }
            unset($l1lll11111l11l1111111ll1l11ll11);
            ob_flush();
            flush();
        }
        exit();
    }
    public function doWebExcletop()
    {
        set_time_limit(0);
        global $_W, $_GPC;
        if (empty($_GPC['sid'])) {
            message('没有选择要导出排名的活动', referer(), 'error');
        }
        $l11ll11lllllll1l1llll11ll11ll1l         = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid ';
        $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
        $lll1l1lllll1111l111ll111111ll1l         = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao') . $l1l1lll11l111l111ll1l11l1111ll1 . " AND `verify`!='2' order by `good` desc", $l11l1ll1llll11111l11lllll1l1ll1);
        $l111111ll1l1l1lll11l1ll11111ll1         = array();
        foreach ($lll1l1lllll1111l111ll111111ll1l as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            if ($l1l11ll11l1111ll111l1ll11ll1l1l = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE `openid` = :openid limit 1", array(
                ":openid" => $ll111111l1ll11lll1l1l1111lll11l['openid']
            ))) {
                $ll111111l1ll11lll1l1l1111lll11l['nickname'] = $l1l11ll11l1111ll111l1ll11ll1l1l['nickname'];
            }
            $ll111111l1ll11lll1l1l1111lll11l['data']                           = iunserializer($ll111111l1ll11lll1l1l1111lll11l['data']);
            $l111111ll1l1l1lll11l1ll11111ll1[$l1lll11llll11ll1lllllllll1l1l11] = $ll111111l1ll11lll1l1l1111lll11l;
        }
        $l1lll1ll1l11lll1l11111lll111lll = "\xEF\xBB\xBF";
        $ll111l111l1l1l111111111llll1ll1 = array(
            'top' => '排名',
            'groups' => '分组',
            'uid' => '选手编号',
            'name' => '姓名',
            'phone' => '手机号',
            'openid' => 'openid',
            'nickname' => '微信昵称',
            'good' => '得票数',
            'share' => '分享数',
            'click' => '点击量'
        );
        foreach ($l11ll11lllllll1l1llll11ll11ll1l['joinfield'] as $ll111111l1ll11lll1l1l1111lll11l) {
            $ll111l111l1l1l111111111llll1ll1[$ll111111l1ll11lll1l1l1111lll11l['sign']] = $ll111111l1ll11lll1l1l1111lll11l['name'];
        }
        foreach ($ll111l111l1l1l111111111llll1ll1 as $l1l11l1l1ll1ll11ll1lll11l11l111) {
            $l1lll1ll1l11lll1l11111lll111lll .= $l1l11l1l1ll1ll11ll1lll11l11l111 . "\t,";
        }
        $l1lll1ll1l11lll1l11111lll111lll .= "\n";
        $l1l1lll1ll1ll1l1l1l11l1lllll1ll = 1;
        foreach ($lll1l1lllll1111l111ll111111ll1l as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            if ($l1l11ll11l1111ll111l1ll11ll1l1l = pdo_fetch("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE `openid` = :openid limit 1", array(
                ":openid" => $ll111111l1ll11lll1l1l1111lll11l['openid']
            ))) {
                $ll111111l1ll11lll1l1l1111lll11l['nickname'] = $l1l11ll11l1111ll111l1ll11ll1l1l['nickname'];
            }
            $l1l111l11l1llllll1lll1l11ll1ll1 = iunserializer($ll111111l1ll11lll1l1l1111lll11l['data']);
            empty($l1l111l11l1llllll1lll1l11ll1ll1) or $ll111111l1ll11lll1l1l1111lll11l = array_merge($ll111111l1ll11lll1l1l1111lll11l, $l1l111l11l1llllll1lll1l11ll1ll1);
            foreach ($ll111l111l1l1l111111111llll1ll1 as $llll11lllll111l1l1l1l11111ll111 => $l1l11l1l1ll1ll11ll1lll11l11l111) {
                if ($llll11lllll111l1l1l1l11111ll111 == 'top') {
                    $l1lll1ll1l11lll1l11111lll111lll .= $l1l1lll1ll1ll1l1l1l11l1lllll1ll++ . "\t, ";
                } elseif ($llll11lllll111l1l1l1l11111ll111 == 'groups') {
                    $l1lll1ll1l11lll1l11111lll111lll .= empty($ll111111l1ll11lll1l1l1111lll11l[$llll11lllll111l1l1l1l11111ll111]) ? '未分组' . "\t, " : $l11ll11lllllll1l1llll11ll11ll1l['groups'][$ll111111l1ll11lll1l1l1111lll11l[$llll11lllll111l1l1l1l11111ll111]]['name'] . " \t, ";
                } else {
                    $l1lll1ll1l11lll1l11111lll111lll .= isset($ll111111l1ll11lll1l1l1111lll11l[$llll11lllll111l1l1l1l11111ll111]) ? $ll111111l1ll11lll1l1l1111lll11l[$llll11lllll111l1l1l1l11111ll111] . "\t, " : " \t, ";
                }
            }
            $l1lll1ll1l11lll1l11111lll111lll .= "\n";
        }
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=排名-" . date('Y-m-d') . ".csv");
        echo $l1lll1ll1l11lll1l11111lll111lll;
        exit();
    }
    public function doWebExcledraw()
    {
        set_time_limit(0);
        global $_W, $_GPC;
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
            ":uniacid" => $_W['uniacid']
        ));
        if (!empty($_GPC['sid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid';
            $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
        } else {
            $l1lll11l11111ll11111l1l1ll11111 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
            }
            $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `sid` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')";
            $l11l1ll1llll11111l11lllll1l1ll1 = array();
        }
        if (!empty($_GPC['key'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND (`uid`=:uid or `uname`=:uname)';
            $l11l1ll1llll11111l11lllll1l1ll1[':uid']   = $_GPC['key'];
            $l11l1ll1llll11111l11lllll1l1ll1[':uname'] = $_GPC['key'];
            $llll11lllll111l1l1l1l11111ll111           = $_GPC['key'];
        }
        if (!empty($_GPC['uses'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `uses`=:uses';
            $l11l1ll1llll11111l11lllll1l1ll1[':uses'] = intval($_GPC['uses']);
        }
        if (!empty($_GPC['attr'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `attr`=:attr';
            $l11l1ll1llll11111l11lllll1l1ll1[':attr'] = intval($_GPC['attr']);
        }
        $lll1l1lllll1111l111ll111111ll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_draw') . $l1l1lll11l111l111ll1l11l1111ll1 . " ORDER BY `id` DESC", $l11l1ll1llll11111l11lllll1l1ll1);
        foreach ($lll1l1lllll1111l111ll111111ll1l as &$l1lllll1l1ll11lll1ll1l111ll1l11) {
            $ll1lllll111ll11l1l1lll1l11l11l1          = $this->l1l1l1l11111lllllll1ll1111111ll();
            $lll1lll1l11l111l1ll1111111l1lll          = pdo_fetchcolumn("SELECT `address` FROM " . tablename('xiaof_relation') . " WHERE `uniacid` IN ('" . implode("','", $ll1lllll111ll11l1l1lll1l11l11l1) . "') AND `openid` = '" . $l1lllll1l1ll11lll1ll1l111ll1l11['openid'] . "' AND `address` != '' ORDER BY `id` DESC limit 1");
            $ll1ll11111lll1l1l11llll11l1l11l          = iunserializer($lll1lll1l11l111l1ll1111111l1lll);
            $l1lllll1l1ll11lll1ll1l111ll1l11['names'] = $ll1ll11111lll1l1l11llll11l1l11l['name'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['phone'] = $ll1ll11111lll1l1l11llll11l1l11l['phone'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['addrs'] = $ll1ll11111lll1l1l11llll11l1l11l['addrs'];
        }
        $l1lll1ll1l11lll1l11111lll111lll = "\xEF\xBB\xBF";
        $ll111l111l1l1l111111111llll1ll1 = array(
            'uid' => '用户UID',
            'uname' => '用户昵称',
            'openid' => 'openid',
            'sid' => '活动ID',
            'name' => '奖品名称',
            'uses' => '状态',
            'names' => '姓名',
            'phone' => '手机号',
            'addrs' => '地址',
            'created_at' => '抽取时间'
        );
        foreach ($ll111l111l1l1l111111111llll1ll1 as $l1l11l1l1ll1ll11ll1lll11l11l111) {
            $l1lll1ll1l11lll1l11111lll111lll .= $l1l11l1l1ll1ll11ll1lll11l11l111 . "\t,";
        }
        $l1lll1ll1l11lll1l11111lll111lll .= "\n";
        $l1l1lll1ll1ll1l1l1l11l1lllll1ll = 1;
        foreach ($lll1l1lllll1111l111ll111111ll1l as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            foreach ($ll111l111l1l1l111111111llll1ll1 as $llll11lllll111l1l1l1l11111ll111 => $l1l11l1l1ll1ll11ll1lll11l11l111) {
                if ($llll11lllll111l1l1l1l11111ll111 == 'uses') {
                    $l1lll1ll1l11lll1l11111lll111lll .= ($ll111111l1ll11lll1l1l1111lll11l['uses'] == '1') ? "已核销\t, " : "未核销\t, ";
                } elseif ($llll11lllll111l1l1l1l11111ll111 == 'created_at') {
                    $l1lll1ll1l11lll1l11111lll111lll .= date('Y-m-d H:i:s', $ll111111l1ll11lll1l1l1111lll11l['created_at']) . " \t, ";
                } else {
                    $l1lll1ll1l11lll1l11111lll111lll .= isset($ll111111l1ll11lll1l1l1111lll11l[$llll11lllll111l1l1l1l11111ll111]) ? $ll111111l1ll11lll1l1l1111lll11l[$llll11lllll111l1l1l1l11111ll111] . "\t, " : " \t, ";
                }
            }
            $l1lll1ll1l11lll1l11111lll111lll .= "\n";
        }
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=抽奖记录-" . date('Y-m-d') . ".csv");
        echo $l1lll1ll1l11lll1l11111lll111lll;
        exit();
    }
    public function doWebDisableip()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        if (!empty($_GPC['del'])) {
            $l1l111l1lll1l1111ll1l1l1ll111ll = array();
            foreach ($_GPC['delete'] as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l111l1lll1l1111ll1l1l1ll111ll[] = intval($ll111111l1ll11lll1l1l1111lll11l);
            }
            pdo_query("DELETE FROM " . tablename('xiaof_toupiao_safe') . " WHERE `id` IN ('" . implode("','", $l1l111l1lll1l1111ll1l1l1ll111ll) . "')");
        }
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
            ":uniacid" => $_W['uniacid']
        ));
        if (!empty($_GPC['sid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
        } else {
            $l1lll11l11111ll11111l1l1ll11111 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
            }
            $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `sid` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')";
        }
        if (!$lll11l1l1lll11lll1l1l1l111llll1 = cache_read('xiaof:wxserviceip')) {
            load()->classs('weixin.account');
            $l11l11l1111111l1l11l1lll1l11l11 = WeixinAccount::create($_W['acid']);
            $l11ll1l1ll11l1l1l111111l1111ll1 = $l11l11l1111111l1l11l1lll1l11l11->fetch_token();
            $l1ll1ll11ll1ll11111ll1lllllll11 = file_get_contents('https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $l11ll1l1ll11l1l1l111111l1111ll1);
            $l11lll111l1lll1l11l1lll1lll11l1 = @json_decode($l1ll1ll11ll1ll11111ll1lllllll11, true);
            if (isset($l11lll111l1lll1l11l1lll1lll11l1['errcode'])) {
                $lll11l1l1lll11lll1l1l1l111llll1 = array();
            } else {
                $lll11l1l1lll11lll1l1l1l111llll1 = $l11lll111l1lll1l11l1lll1lll11l1['ip_list'];
            }
            cache_write('xiaof:wxserviceip', iserializer($lll11l1l1lll11lll1l1l1l111llll1));
        }
        $ll1lll1l11l11ll11lll11111l1ll1l = iunserializer($lll11l1l1lll11lll1l1l1l111llll1);
        $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
        $l11ll1l1111l11lll1ll1111lll1111 = 20;
        $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_safe') . $l1l1lll11l111l111ll1l11l1111ll1, $l11l1ll1llll11111l11lllll1l1ll1);
        $ll1111l1l1l11ll1111llll11l1ll11 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_safe') . $l1l1lll11l111l111ll1l11l1111ll1 . " ORDER BY `id` DESC LIMIT " . ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111, $l11l1ll1llll11111l11lllll1l1ll1);
        foreach ($ll1111l1l1l11ll1111llll11l1ll11 as &$ll111111l1ll11lll1l1l1111lll11l) {
            if (!$l1ll1lll1ll1lll1111ll1111llll1l = cache_read('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']))) {
                $ll1111l111l1l1lllll1l1111l1l111 = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . long2ip($ll111111l1ll11lll1l1l1111lll11l['ip']));
                $lllll11ll1lllllll1l1l11lll111ll = json_decode($ll1111l111l1l1lllll1l1111l1l111);
                if (!empty($lllll11ll1lllllll1l1l11lll111ll->code) or $ll111111l1ll11lll1l1l1111lll11l['ip'] == '2147483647') {
                    cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), '未知');
                } else {
                    if (in_array(long2ip($ll111111l1ll11lll1l1l1111lll11l['ip']), $ll1lll1l11l11ll11lll11111l1ll1l)) {
                        $l1ll1lll1ll1lll1111ll1111llll1l = '微信服务器';
                    } else {
                        $l1ll1lll1ll1lll1111ll1111llll1l = $lllll11ll1lllllll1l1l11lll111ll->data->region . $lllll11ll1lllllll1l1l11lll111ll->data->city . $lllll11ll1lllllll1l1l11lll111ll->data->isp . $lllll11ll1lllllll1l1l11lll111ll->data->county;
                    }
                    if (empty($l1ll1lll1ll1lll1111ll1111llll1l)) {
                        cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), '未知');
                    } else {
                        cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), $l1ll1lll1ll1lll1111ll1111llll1l);
                    }
                }
            }
            $ll111111l1ll11lll1l1l1111lll11l['dz'] = $l1ll1lll1ll1lll1111ll1111llll1l;
        }
        $llll11ll1ll111l1l1ll111l1ll1111 = pagination($l1l1ll1ll1ll11llll1l1l1l111llll, $ll1l11l111l1l11111ll1l11l11ll1l, $l11ll1l1111l11lll1ll1111lll1111);
        include $this->template("disableip");
    }
    public function doWebSafe()
    {
        global $_W, $_GPC;
        //$this->l1l11l1l1111l1l111l111l11l11ll1();
        $llll111l11l11l1l1ll1l11ll1lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_setting') . " WHERE `uniacid` = :uniacid", array(
            ":uniacid" => $_W['uniacid']
        ));
        if ($_GPC['hide'] == 'y') {
            if (empty($_GPC['ip'])) {
                exit('参数错误');
            }
            if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_safe") . " WHERE `sid` = :sid AND `ip` = :ip ", array(
                ":sid" => $_GPC['sid'],
                ":ip" => $_GPC['ip']
            ))) {
                message('该ip已经在黑名单中', referer(), 'success');
            }
            pdo_insert("xiaof_toupiao_safe", array(
                "sid" => $_GPC['sid'],
                "ip" => $_GPC['ip'],
                "created_at" => time()
            ));
            message('操作成功', referer(), 'success');
        } elseif ($_GPC['hide'] == 'n') {
            if (empty($_GPC['safeid'])) {
                exit('参数错误');
            }
            pdo_delete('xiaof_toupiao_safe', array(
                'id' => $_GPC['safeid']
            ));
            message('操作成功', referer(), 'success');
        }
        $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
        $l11ll1l1111l11lll1ll1111lll1111 = 10;
        $l11l1ll1llll11111l11lllll1l1ll1 = array();
        if (!empty($_GPC['sid'])) {
            $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid ';
            $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
        } else {
            $l1lll11l11111ll11111l1l1ll11111 = array();
            foreach ($llll111l11l11l1l1ll1l11ll1lll1l as $ll111111l1ll11lll1l1l1111lll11l) {
                $l1lll11l11111ll11111l1l1ll11111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
            }
            $l1l1lll11l111l111ll1l11l1111ll1 = " WHERE `sid` in ('" . implode("','", $l1lll11l11111ll11111l1l1ll11111) . "')";
        }
        if ($_GPC['unum'] == 'y') {
            if (empty($_GPC['ip'])) {
                exit('参数错误');
            }
            $l1l1lll11l111l111ll1l11l1111ll1 .= " AND `ip`=:ip AND `valid` = '1'";
            $l11l1ll1llll11111l11lllll1l1ll1[':ip'] = $_GPC['ip'];
            $l11l1l111llllll1lll1l1111111l11        = pdo_fetchall("SELECT *,COUNT(pid) as nums FROM " . tablename('xiaof_toupiao_log') . $l1l1lll11l111l111ll1l11l1111ll1 . " group by `pid`", $l11l1ll1llll11111l11lllll1l1ll1);
            foreach ($l11l1l111llllll1lll1l1111111l11 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                if ($l11ll1lll11l111111l111l1l1ll1l1 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_manage") . " WHERE `pid` = :pid AND `ip` = :ip ", array(
                    ":pid" => $ll111111l1ll11lll1l1l1111lll11l['pid'],
                    ":ip" => $ll111111l1ll11lll1l1l1111lll11l['ip']
                ))) {
                    pdo_query("UPDATE " . tablename("xiaof_toupiao_manage") . " SET `num` = num+" . $ll111111l1ll11lll1l1l1111lll11l['nums'] . " WHERE `id` = '" . $l11ll1lll11l111111l111l1l1ll1l1['id'] . "'");
                } else {
                    pdo_insert("xiaof_toupiao_manage", array(
                        "sid" => $ll111111l1ll11lll1l1l1111lll11l['sid'],
                        "ip" => $ll111111l1ll11lll1l1l1111lll11l['ip'],
                        "pid" => $ll111111l1ll11lll1l1l1111lll11l['pid'],
                        "num" => $ll111111l1ll11lll1l1l1111lll11l['nums'],
                        "operation" => '剔除票数',
                        "created_at" => time()
                    ));
                }
                pdo_query("UPDATE " . tablename("xiaof_toupiao") . " SET `good` = good-" . $ll111111l1ll11lll1l1l1111lll11l['nums'] . " WHERE `id` = '" . $ll111111l1ll11lll1l1l1111lll11l['pid'] . "'");
                pdo_query("UPDATE " . tablename("xiaof_toupiao_log") . " SET `valid` = '0' WHERE `ip` = '" . $ll111111l1ll11lll1l1l1111lll11l['ip'] . "'");
            }
            message('操作成功', referer(), 'success');
        } elseif ($_GPC['unum'] == 'n') {
            if (empty($_GPC['ip'])) {
                exit('参数错误');
            }
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `ip`=:ip ';
            $l11l1ll1llll11111l11lllll1l1ll1[':ip'] = $_GPC['ip'];
            $l11l1l111llllll1lll1l1111111l11        = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_manage') . $l1l1lll11l111l111ll1l11l1111ll1 . "", $l11l1ll1llll11111l11lllll1l1ll1);
            $llll1lllll1l11111l11ll1ll111111        = array();
            foreach ($l11l1l111llllll1lll1l1111111l11 as $ll111111l1ll11lll1l1l1111lll11l) {
                $llll1lllll1l11111l11ll1ll111111[] = intval($ll111111l1ll11lll1l1l1111lll11l['id']);
                pdo_query("UPDATE " . tablename("xiaof_toupiao") . " SET `good` = good+" . $ll111111l1ll11lll1l1l1111lll11l['num'] . " WHERE `id` = '" . $ll111111l1ll11lll1l1l1111lll11l['pid'] . "'");
            }
            pdo_query("UPDATE " . tablename("xiaof_toupiao_log") . " SET `valid` = '1' WHERE `ip` = '" . $_GPC['ip'] . "'");
            pdo_query("DELETE FROM " . tablename('xiaof_toupiao_manage') . " WHERE `id` IN ('" . implode("','", $llll1lllll1l11111l11ll1ll111111) . "')");
            message('操作成功', referer(), 'success');
        }
        $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn("SELECT COUNT(1) FROM (SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . $l1l1lll11l111l111ll1l11l1111ll1 . " group by `ip`) T", $l11l1ll1llll11111l11lllll1l1ll1);
        $l1lll11111l11l1111111ll1l11ll11 = pdo_fetchall("SELECT *,COUNT(ip) as counts,MAX(created_at) as created_at FROM " . tablename('xiaof_toupiao_log') . $l1l1lll11l111l111ll1l11l1111ll1 . " group by `ip` order by counts desc LIMIT " . ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111, $l11l1ll1llll11111l11lllll1l1ll1);
        if (!$lll11l1l1lll11lll1l1l1l111llll1 = cache_read('xiaof:wxserviceip')) {
            load()->classs('weixin.account');
            $l11l11l1111111l1l11l1lll1l11l11 = WeixinAccount::create($_W['acid']);
            $l11ll1l1ll11l1l1l111111l1111ll1 = $l11l11l1111111l1l11l1lll1l11l11->fetch_token();
            $l1ll1ll11ll1ll11111ll1lllllll11 = file_get_contents('https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $l11ll1l1ll11l1l1l111111l1111ll1);
            $l11lll111l1lll1l11l1lll1lll11l1 = @json_decode($l1ll1ll11ll1ll11111ll1lllllll11, true);
            if (isset($l11lll111l1lll1l11l1lll1lll11l1['errcode'])) {
                $lll11l1l1lll11lll1l1l1l111llll1 = array();
            } else {
                $lll11l1l1lll11lll1l1l1l111llll1 = $l11lll111l1lll1l11l1lll1lll11l1['ip_list'];
            }
            cache_write('xiaof:wxserviceip', iserializer($lll11l1l1lll11lll1l1l1l111llll1));
        }
        $ll1lll1l11l11ll11lll11111l1ll1l = iunserializer($lll11l1l1lll11lll1l1l1l111llll1);
        $ll1111l1l1l11ll1111llll11l1ll11 = array();
        foreach ($l1lll11111l11l1111111ll1l11ll11 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            $llll1l11l11111l1l111l11l1ll1111 = array();
            if (empty($ll111111l1ll11lll1l1l1111lll11l['nickname'])) {
                if (!empty($ll111111l1ll11lll1l1l1111lll11l['fanid'])) {
                    $llll1l11l11111l1l111l11l1ll1111['nickname'] = pdo_fetchcolumn("SELECT `nickname` FROM " . tablename("mc_mapping_fans") . " WHERE `fanid` = :fanid limit 1", array(
                        ":fanid" => $ll111111l1ll11lll1l1l1111lll11l['fanid']
                    ));
                } else {
                    $llll1l11l11111l1l111l11l1ll1111['nickname'] = pdo_fetchcolumn("SELECT `nickname` FROM " . tablename("mc_mapping_fans") . " WHERE `openid` = :openid limit 1", array(
                        ":openid" => $ll111111l1ll11lll1l1l1111lll11l['openid']
                    ));
                }
            }
            $llll1l11l11111l1l111l11l1ll1111['ocount'] = pdo_fetchcolumn("SELECT COUNT(1) FROM (SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l['sid'] . "' AND `ip` = '" . $ll111111l1ll11lll1l1l1111lll11l['ip'] . "'  group by `openid`) T");
            $llll1l11l11111l1l111l11l1ll1111['unum']   = $llll1l11l11111l1l111l11l1ll1111['hide'] = 0;
            if ($l1l1l1l1ll1ll1ll1l11l111ll1l111 = pdo_fetch("SELECT `id` FROM " . tablename("xiaof_toupiao_safe") . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l['sid'] . "' AND `ip` = :ip ", array(
                ":ip" => $ll111111l1ll11lll1l1l1111lll11l['ip']
            ))) {
                $llll1l11l11111l1l111l11l1ll1111['hide']   = 1;
                $llll1l11l11111l1l111l11l1ll1111['safeid'] = $l1l1l1l1ll1ll1ll1l11l111ll1l111['id'];
            }
            $l11ll1lll11l111111l111l1l1ll1l1 = pdo_fetch("SELECT SUM(num) as nums FROM " . tablename("xiaof_toupiao_manage") . " WHERE `sid` = '" . $ll111111l1ll11lll1l1l1111lll11l['sid'] . "' AND `ip` = :ip ", array(
                ":ip" => $ll111111l1ll11lll1l1l1111lll11l['ip']
            ));
            if (!empty($l11ll1lll11l111111l111l1l1ll1l1['nums'])) {
                $llll1l11l11111l1l111l11l1ll1111['unum']  = 1;
                $llll1l11l11111l1l111l11l1ll1111['unnum'] = $l11ll1lll11l111111l111l1l1ll1l1['nums'];
            }
            if (!$l1ll1lll1ll1lll1111ll1111llll1l = cache_read('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']))) {
                $ll1111l111l1l1lllll1l1111l1l111 = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . long2ip($ll111111l1ll11lll1l1l1111lll11l['ip']));
                $lllll11ll1lllllll1l1l11lll111ll = json_decode($ll1111l111l1l1lllll1l1111l1l111);
                if (!empty($lllll11ll1lllllll1l1l11lll111ll->code) or $ll111111l1ll11lll1l1l1111lll11l['ip'] == '2147483647') {
                    cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), '未知');
                } else {
                    if (in_array(long2ip($ll111111l1ll11lll1l1l1111lll11l['ip']), $ll1lll1l11l11ll11lll11111l1ll1l)) {
                        $l1ll1lll1ll1lll1111ll1111llll1l = '微信服务器';
                    } else {
                        $l1ll1lll1ll1lll1111ll1111llll1l = $lllll11ll1lllllll1l1l11lll111ll->data->region . $lllll11ll1lllllll1l1l11lll111ll->data->city . $lllll11ll1lllllll1l1l11lll111ll->data->isp . $lllll11ll1lllllll1l1l11lll111ll->data->county;
                    }
                    if (empty($l1ll1lll1ll1lll1111ll1111llll1l)) {
                        cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), '未知');
                    } else {
                        cache_write('iplongregion:' . md5($ll111111l1ll11lll1l1l1111lll11l['ip']), $l1ll1lll1ll1lll1111ll1111llll1l);
                    }
                }
            }
            $llll1l11l11111l1l111l11l1ll1111['dz'] = $l1ll1lll1ll1lll1111ll1111llll1l;
            $ll1111l1l1l11ll1111llll11l1ll11[]     = array_merge($ll111111l1ll11lll1l1l1111lll11l, $llll1l11l11111l1l111l11l1ll1111);
        }
        $llll11ll1ll111l1l1ll111l1ll1111 = pagination($l1l1ll1ll1ll11llll1l1l1l111llll, $ll1l11l111l1l11111ll1l11l11ll1l, $l11ll1l1111l11lll1ll1111lll1111);
        include $this->template("safe");
    }
    public function doMobileIndex()
    {
        global $_W, $_GPC;
        $this->l1ll1l1111ll11lll1l11111lll111l();
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        $l1l1lll11l111l111ll1l11l1111ll1 = ' WHERE `sid`=:sid';
        $l11l1ll1llll11111l11lllll1l1ll1 = array(
            ':sid' => $l11ll11lllllll1l1llll11ll11ll1l['id']
        );
        if (!empty($_GPC['groups'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `groups`=:groups';
            $l11l1ll1llll11111l11lllll1l1ll1[':groups'] = intval($_GPC['groups']);
        }
        if ($l11ll11lllllll1l1llll11ll11ll1l['verify'] == 1) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `verify`=:verify';
            $l11l1ll1llll11111l11lllll1l1ll1[':verify'] = 1;
        } else {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `verify`!=:verify';
            $l11l1ll1llll11111l11lllll1l1ll1[':verify'] = 2;
        }
        if (!empty($_GPC['key'])) {
            if ($this->module['config']['fuzzysearch'] == 1) {
                $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND (`uid`=:uid OR `name`=:name) ';
                $l11l1ll1llll11111l11lllll1l1ll1[':name'] = $_GPC['key'];
            } else {
                $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND (`uid`=:uid OR `name` like :name) ';
                $l11l1ll1llll11111l11lllll1l1ll1[':name'] = "%" . $_GPC['key'] . "%";
            }
            $l11l1ll1llll11111l11lllll1l1ll1[':uid'] = $_GPC['key'];
            $llll11lllll111l1l1l1l11111ll111         = $_GPC['key'];
        }
        switch ($_GPC['type']) {
            case 'hot':
                $l1lll1l11l11l1lll1llll1l1l11ll1 = 'updated_at';
                break;
            case 'new':
                $l1lll1l11l11l1lll1llll1l1l11ll1 = 'created_at';
                break;
            case 'top':
                $l1lll1l11l11l1lll1llll1l1l11ll1 = 'good';
                break;
            default:
                $l1lll1l11l11l1lll1llll1l1l11ll1 = 'updated_at';
                break;
        }
        $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
        $l11ll1l1111l11lll1ll1111lll1111 = isset($l11ll11lllllll1l1llll11ll11ll1l['indexloadnum']) ? $l11ll11lllllll1l1llll11ll11ll1l['indexloadnum'] : 12;
        $lll1l1lllll1111l111ll111111ll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao') . $l1l1lll11l111l111ll1l11l1111ll1 . " ORDER BY `" . $l1lll1l11l11l1lll1llll1l1l11ll1 . "` DESC LIMIT " . ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111, $l11l1ll1llll11111l11lllll1l1ll1);
        if ($_W['isajax']) {
            include $this->template($l11ll11lllllll1l1llll11ll11ll1l['template'] . "ajaxload");
            exit();
        }
        if (!$l1l11ll1l1l1ll1111ll1l1llll1l11 = $this->l11llllll1ll1lll1ll111l1l1lll1l('indexcount' . $l11ll11lllllll1l1llll11ll11ll1l['id'])) {
            $ll1llll11111l1l1lllll111ll1l1ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            $lll1l1ll1111l111ll11ll11111ll11 = pdo_fetchcolumn("SELECT SUM(click) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            $lll1l1ll1111l111ll11ll11111ll11 = $l11ll11lllllll1l1llll11ll11ll1l['click'] + $lll1l1ll1111l111ll11ll11111ll11;
            empty($lll1l1ll1111l111ll11ll11111ll11) && $lll1l1ll1111l111ll11ll11111ll11 = 0;
            $l1l11l11lll1l11l1l1ll111l1l1ll1 = pdo_fetchcolumn("SELECT SUM(good) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            empty($l1l11l11lll1l11l1l1ll111l1l1ll1) && $l1l11l11lll1l11l1l1ll111l1l1ll1 = 0;
            $l1l11ll1l1l1ll1111ll1l1llll1l11 = array(
                'good' => $ll1llll11111l1l1lllll111ll1l1ll,
                'click' => $lll1l1ll1111l111ll11ll11111ll11,
                'shares' => $l1l11l11lll1l11l1l1ll111l1l1ll1
            );
            $this->llllll1l111l1l1l1l1ll11l1l11lll('indexcount' . $l11ll11lllllll1l1llll11ll11ll1l['id'], $l1l11ll1l1l1ll1111ll1l1llll1l11, 3);
        }
        $ll1llll11111l1l1lllll111ll1l1ll = $l1l11ll1l1l1ll1111ll1l1llll1l11['good'];
        $lll1l1ll1111l111ll11ll11111ll11 = $l1l11ll1l1l1ll1111ll1l1llll1l11['click'];
        $l1l11l11lll1l11l1l1ll111l1l1ll1 = $l1l11ll1l1l1ll1111ll1l1llll1l11['shares'];
        $l1l1l1l11111ll111111llll111ll11 = strtotime($l11ll11lllllll1l1llll11ll11ll1l['pend']) - time();
        include $this->template($l11ll11lllllll1l1llll11ll11ll1l['template'] . 'index');
    }
    public function doMobileTop()
    {
        global $_W, $_GPC;
        $this->l1ll1l1111ll11lll1l11111lll111l();
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        if (!$l1l11ll1l1l1ll1111ll1l1llll1l11 = $this->l11llllll1ll1lll1ll111l1l1lll1l('indexcount' . $l11ll11lllllll1l1llll11ll11ll1l['id'])) {
            $ll1llll11111l1l1lllll111ll1l1ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            $lll1l1ll1111l111ll11ll11111ll11 = pdo_fetchcolumn("SELECT SUM(click) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            $lll1l1ll1111l111ll11ll11111ll11 = $l11ll11lllllll1l1llll11ll11ll1l['click'] + $lll1l1ll1111l111ll11ll11111ll11;
            empty($lll1l1ll1111l111ll11ll11111ll11) && $lll1l1ll1111l111ll11ll11111ll11 = 0;
            $l1l11l11lll1l11l1l1ll111l1l1ll1 = pdo_fetchcolumn("SELECT SUM(good) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            empty($l1l11l11lll1l11l1l1ll111l1l1ll1) && $l1l11l11lll1l11l1l1ll111l1l1ll1 = 0;
            $l1l11ll1l1l1ll1111ll1l1llll1l11 = array(
                'good' => $ll1llll11111l1l1lllll111ll1l1ll,
                'click' => $lll1l1ll1111l111ll11ll11111ll11,
                'shares' => $l1l11l11lll1l11l1l1ll111l1l1ll1
            );
            $this->llllll1l111l1l1l1l1ll11l1l11lll('indexcount' . $l11ll11lllllll1l1llll11ll11ll1l['id'], $l1l11ll1l1l1ll1111ll1l1llll1l11, 3);
        }
        $ll1llll11111l1l1lllll111ll1l1ll         = $l1l11ll1l1l1ll1111ll1l1llll1l11['good'];
        $lll1l1ll1111l111ll11ll11111ll11         = $l1l11ll1l1l1ll1111ll1l1llll1l11['click'];
        $l1l11l11lll1l11l1l1ll111l1l1ll1         = $l1l11ll1l1l1ll1111ll1l1llll1l11['shares'];
        $l1l1l1l11111ll111111llll111ll11         = strtotime($l11ll11lllllll1l1llll11ll11ll1l['pend']) - time();
        $l1l1lll11l111l111ll1l11l1111ll1         = ' WHERE `sid`=:sid ';
        $l11l1ll1llll11111l11lllll1l1ll1[':sid'] = intval($_GPC['sid']);
        if ($l11ll11lllllll1l1llll11ll11ll1l['verify'] == 1) {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `verify`=:verify';
            $l11l1ll1llll11111l11lllll1l1ll1[':verify'] = 1;
        } else {
            $l1l1lll11l111l111ll1l11l1111ll1 .= ' AND `verify`!=:verify';
            $l11l1ll1llll11111l11lllll1l1ll1[':verify'] = 2;
        }
        $ll11l111lll1111111l1l11l1lll111 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao') . $l1l1lll11l111l111ll1l11l1111ll1 . " order by `good` desc limit 100", $l11l1ll1llll11111l11lllll1l1ll1);
        include $this->template($l11ll11lllllll1l1llll11ll11ll1l['template'] . "top");
    }
    public function doMobileShow()
    {
        global $_W, $_GPC;
        $this->l1ll1l1111ll11lll1l11111lll111l();
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        if (!isset($_GPC['id'])) {
            $l1llll111l11lllll11ll111l11l11l = $this->l1l11111llll11llll11l1lll1l11l1();
        } else {
            $l1llll111l11lllll11ll111l11l11l = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id", array(
                ":id" => intval($_GPC['id'])
            ));
        }
        $ll1ll1l11ll1lll11l1lllll11lll1l = pdo_fetch("SELECT (SELECT COUNT(*) FROM " . tablename("xiaof_toupiao") . " WHERE `sid`=k.sid AND `verify`!='2' AND k.good<good ) as top FROM " . tablename("xiaof_toupiao") . " as k WHERE `id` = :id", array(
            ":id" => $l1llll111l11lllll11ll111l11l11l['id']
        ));
        $l111l111lllll111111111llll111ll = pdo_fetchcolumn("SELECT good FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `verify`!='2' AND `good` > :good ORDER BY `good` ASC limit 1", array(
            ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
            ":good" => $l1llll111l11lllll11ll111l11l11l['good']
        ));
        $ll11llll1llll111l1111llll1l111l = pdo_fetchcolumn("SELECT good FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `verify`!='2' AND `good` < :good ORDER BY `good` DESC limit 1", array(
            ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
            ":good" => $l1llll111l11lllll11ll111l11l11l['good']
        ));
        $llll1l1l1llllll1111lll1l11l1lll = pdo_fetch("SELECT (SELECT COUNT(*) FROM " . tablename("xiaof_toupiao") . " WHERE `sid`=k.sid AND `verify`!='2' AND `good`=k.good AND `id`<k.id) as top FROM " . tablename("xiaof_toupiao") . " as k WHERE `id` = :id", array(
            ":id" => $l1llll111l11lllll11ll111l11l11l['id']
        ));
        $llllll1lll11l1ll11111l111l1ll1l = $ll1ll1l11ll1lll11l1lllll11lll1l['top'] + 1 + $llll1l1l1llllll1111lll1l11l1lll['top'];
        $ll1ll1l1ll1lll1l1111llll11lllll = $l1llll111l11lllll11ll111l11l11l['good'] - $ll11llll1llll111l1111llll1l111l;
        $l11l1l1l111l111l1llll1lllllllll = $l111l111lllll111111111llll111ll - $l1llll111l11lllll11ll111l11l11l['good'];
        if (!$l111l111lllll111111111llll111ll) {
            $l11l1l1l111l111l1llll1lllllllll = 0;
        }
        $lll1l1lllll1111l111ll111111ll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_pic') . " WHERE `pid` = :pid", array(
            ":pid" => $l1llll111l11lllll11ll111l11l11l['id']
        ));
        if (intval($l11ll11lllllll1l1llll11ll11ll1l['openposter']) == 1 && empty($l1llll111l11lllll11ll111l11l11l['poster'])) {
            $l1llll111l11lllll11ll111l11l11l['poster'] = $l1l1lllll11ll1llll1llll1lll1l11 = $this->l111ll11llllllll111ll1l1l1l111l($l1llll111l11lllll11ll111l11l11l['name'], $l1llll111l11lllll11ll111l11l11l['uid'], tomedia($lll1l1lllll1111l111ll111111ll1l[0]['url']), urlencode($this->l1111llll1l1llll11l1lll1l11l1ll('show', 'xiaof_toupiao', '&id=' . $l1llll111l11lllll11ll111l11l11l['id'] . '')));
            pdo_update("xiaof_toupiao", array(
                "poster" => $l1l1lllll11ll1llll1llll1lll1l11
            ), array(
                "id" => $l1llll111l11lllll11ll111l11l11l['id']
            ));
        }
        if ($l11ll11lllllll1l1llll11ll11ll1l['openvoteuser'] == 1) {
            $lll1l111llll11lll11lllllll1l111 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_log') . " WHERE `pid` = '" . $l1llll111l11lllll11ll111l11l11l['id'] . "' AND `avatar` != '' order by `id` desc LIMIT 6");
        }
        if (intval($l11ll11lllllll1l1llll11ll11ll1l['opendraw']) == 1) {
            load()->model('mc');
            if ($lllll1111l1ll1l1lll111ll11111l1 = $this->ll1l1l1111l1lllllll1l1l111lll1l()) {
                $llll11l1l1llll1l1l1ll1l111ll1ll = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_drawlog') . " WHERE `sid` = :sid AND `pid` = :pid ORDER BY `id` DESC limit 10", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    ":pid" => $l1llll111l11lllll11ll111l11l11l['id']
                ));
                $l1l111l11l1l1ll11l1l11ll1l1111l = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid`=:sid AND `uid`=:uid AND `uses`='2' AND `attr`=:attr", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    ":uid" => $lllll1111l1ll1l1lll111ll11111l1,
                    ":attr" => 3
                ));
                $l1ll1ll11llll1llll1lllllll1llll = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid`=:sid AND `uid`=:uid AND `uses`='2' AND `attr`=:attr", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    ":uid" => $lllll1111l1ll1l1lll111ll11111l1,
                    ":attr" => 4
                ));
                $l11l1l111ll111l11111111ll1l1111 = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid`=:sid AND `uid`=:uid AND `uses`='2' AND `attr`=:attr", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    ":uid" => $lllll1111l1ll1l1lll111ll11111l1,
                    ":attr" => 5
                ));
                $l1l111ll11ll11ll1l11ll1l1l1111l = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid`=:sid AND `uid`=:uid AND `uses`='2' AND `attr`=:attr", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    ":uid" => $lllll1111l1ll1l1lll111ll11111l1,
                    ":attr" => 6
                ));
            }
        }
        if (empty($l11ll11lllllll1l1llll11ll11ll1l['mysharetitle'])) {
            $llll111l1l11l11l1l1l1111ll1l111 = '我参加了' . $l11ll11lllllll1l1llll11ll11ll1l['title'] . '-' . $l1llll111l11lllll11ll111l11l11l['uid'] . '号-' . $l1llll111l11lllll11ll111l11l11l['name'] . '，请大家多多支持';
        } else {
            $l1ll1llll1ll11l11ll1lllll11l1ll = isset($l11ll11lllllll1l1llll11ll11ll1l['groups'][$l1llll111l11lllll11ll111l11l11l['groups']]) ? $l1ll1llll1ll11l11ll1lllll11l1ll = $l11ll11lllllll1l1llll11ll11ll1l['groups'][$l1llll111l11lllll11ll111l11l11l['groups']]['name'] : '';
            $llll111l1l11l11l1l1l1111ll1l111 = str_replace(array(
                '{title}',
                '{group}',
                '{uid}',
                '{name}'
            ), array(
                $l11ll11lllllll1l1llll11ll11ll1l['title'],
                $l1ll1llll1ll11l11ll1lllll11l1ll,
                $l1llll111l11lllll11ll111l11l11l['uid'],
                $l1llll111l11lllll11ll111l11l11l['name']
            ), $l11ll11lllllll1l1llll11ll11ll1l['mysharetitle']);
        }
        $l1llll111l11lllll11ll111l11l11l['data'] = iunserializer($l1llll111l11lllll11ll111l11l11l['data']);
        $l1l1ll11ll1l11l1ll11lllll1lllll         = array();
        if (is_array($l11ll11lllllll1l1llll11ll11ll1l['joinfield'])) {
            foreach ($l11ll11lllllll1l1llll11ll11ll1l['joinfield'] as $ll111111l1ll11lll1l1l1111lll11l) {
                if ($ll111111l1ll11lll1l1l1111lll11l['isshow'] == 1) {
                    $l1l1ll11ll1l11l1ll11lllll1lllll[] = array(
                        'name' => $ll111111l1ll11lll1l1l1111lll11l['name'],
                        'data' => $l1llll111l11lllll11ll111l11l11l['data'][$ll111111l1ll11lll1l1l1111lll11l['sign']]
                    );
                }
            }
        }
        include $this->template($l11ll11lllllll1l1llll11ll11ll1l['template'] . "show");
    }
    public function doMobileDetail()
    {
        global $_W, $_GPC;
        $this->l1ll1l1111ll11lll1l11111lll111l();
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        if (!$l1l11ll1l1l1ll1111ll1l1llll1l11 = $this->l11llllll1ll1lll1ll111l1l1lll1l('indexcount' . $l11ll11lllllll1l1llll11ll11ll1l['id'])) {
            $ll1llll11111l1l1lllll111ll1l1ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            $lll1l1ll1111l111ll11ll11111ll11 = pdo_fetchcolumn("SELECT SUM(click) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            $lll1l1ll1111l111ll11ll11111ll11 = $l11ll11lllllll1l1llll11ll11ll1l['click'] + $lll1l1ll1111l111ll11ll11111ll11;
            empty($lll1l1ll1111l111ll11ll11111ll11) && $lll1l1ll1111l111ll11ll11111ll11 = 0;
            $l1l11l11lll1l11l1l1ll111l1l1ll1 = pdo_fetchcolumn("SELECT SUM(good) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            empty($l1l11l11lll1l11l1l1ll111l1l1ll1) && $l1l11l11lll1l11l1l1ll111l1l1ll1 = 0;
            $l1l11ll1l1l1ll1111ll1l1llll1l11 = array(
                'good' => $ll1llll11111l1l1lllll111ll1l1ll,
                'click' => $lll1l1ll1111l111ll11ll11111ll11,
                'shares' => $l1l11l11lll1l11l1l1ll111l1l1ll1
            );
            $this->llllll1l111l1l1l1l1ll11l1l11lll('indexcount' . $l11ll11lllllll1l1llll11ll11ll1l['id'], $l1l11ll1l1l1ll1111ll1l1llll1l11, 3);
        }
        $ll1llll11111l1l1lllll111ll1l1ll = $l1l11ll1l1l1ll1111ll1l1llll1l11['good'];
        $lll1l1ll1111l111ll11ll11111ll11 = $l1l11ll1l1l1ll1111ll1l1llll1l11['click'];
        $l1l11l11lll1l11l1l1ll111l1l1ll1 = $l1l11ll1l1l1ll1111ll1l1llll1l11['shares'];
        $l1l1l1l11111ll111111llll111ll11 = strtotime($l11ll11lllllll1l1llll11ll11ll1l['pend']) - time();
        $l1l1lll1l1l1111l1l11lll11l111ll = pdo_fetchcolumn("SELECT `detail` FROM " . tablename("xiaof_toupiao_setting") . " WHERE `id` = :id", array(
            ":id" => $l11ll11lllllll1l1llll11ll11ll1l['id']
        ));
        $l1l1lll1l1l1111l1l11lll11l111ll = iunserializer($l1l1lll1l1l1111l1l11lll11l111ll);
        empty($l11ll11lllllll1l1llll11ll11ll1l['noticecontent']) or $l1l1lll1l1l1111l1l11lll11l111ll['noticecontent'] = $l11ll11lllllll1l1llll11ll11ll1l['noticecontent'];
        empty($l11ll11lllllll1l1llll11ll11ll1l['detail']) or $l1l1lll1l1l1111l1l11lll11l111ll['detail'] = $l11ll11lllllll1l1llll11ll11ll1l['detail'];
        empty($l11ll11lllllll1l1llll11ll11ll1l['rules']) or $l1l1lll1l1l1111l1l11lll11l111ll['rules'] = $l11ll11lllllll1l1llll11ll11ll1l['rules'];
        include $this->template($l11ll11lllllll1l1llll11ll11ll1l['template'] . "detail");
    }
    public function doMobileJoin()
    {
        global $_W, $_GPC;
        $this->l1ll1l1111ll11lll1l11111lll111l();
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        $llll1l11l11111l1l111l11l1ll1111 = $this->l1l11111llll11llll11l1lll1l11l1();
        if (isset($llll1l11l11111l1l111l11l1ll1111['id'])) {
            $lll1l1lllll1111l111ll111111ll1l         = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_pic') . " WHERE `pid` = :pid", array(
                ":pid" => $llll1l11l11111l1l111l11l1ll1111['id']
            ));
            $llll1l11l11111l1l111l11l1ll1111['data'] = iunserializer($llll1l11l11111l1l111l11l1ll1111['data']);
        }
        include $this->template($l11ll11lllllll1l1llll11ll11ll1l['template'] . "join");
    }
    public function doMobileCreditdraw()
    {
        global $_W, $_GPC;
        $this->l1ll1l1111ll11lll1l11111lll111l();
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        load()->model('mc');
        $lllll1111l1ll1l1lll111ll11111l1            = $this->ll1l1l1111l1lllllll1l1l111lll1l();
        $l1ll1l1ll1l1l11lll1ll11ll111l1l['credit1'] = 0;
        if (!$l1l11ll1l1l1ll1111ll1l1llll1l11 = $this->l11llllll1ll1lll1ll111l1l1lll1l('indexcount' . $l11ll11lllllll1l1llll11ll11ll1l['id'])) {
            $ll1llll11111l1l1lllll111ll1l1ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            $lll1l1ll1111l111ll11ll11111ll11 = pdo_fetchcolumn("SELECT SUM(click) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            $lll1l1ll1111l111ll11ll11111ll11 = $l11ll11lllllll1l1llll11ll11ll1l['click'] + $lll1l1ll1111l111ll11ll11111ll11;
            empty($lll1l1ll1111l111ll11ll11111ll11) && $lll1l1ll1111l111ll11ll11111ll11 = 0;
            $l1l11l11lll1l11l1l1ll111l1l1ll1 = pdo_fetchcolumn("SELECT SUM(good) FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            ));
            empty($l1l11l11lll1l11l1l1ll111l1l1ll1) && $l1l11l11lll1l11l1l1ll111l1l1ll1 = 0;
            $l1l11ll1l1l1ll1111ll1l1llll1l11 = array(
                'good' => $ll1llll11111l1l1lllll111ll1l1ll,
                'click' => $lll1l1ll1111l111ll11ll11111ll11,
                'shares' => $l1l11l11lll1l11l1l1ll111l1l1ll1
            );
            $this->llllll1l111l1l1l1l1ll11l1l11lll('indexcount' . $l11ll11lllllll1l1llll11ll11ll1l['id'], $l1l11ll1l1l1ll1111ll1l1llll1l11, 3);
        }
        $ll1llll11111l1l1lllll111ll1l1ll = $l1l11ll1l1l1ll1111ll1l1llll1l11['good'];
        $lll1l1ll1111l111ll11ll11111ll11 = $l1l11ll1l1l1ll1111ll1l1llll1l11['click'];
        $l1l11l11lll1l11l1l1ll111l1l1ll1 = $l1l11ll1l1l1ll1111ll1l1llll1l11['shares'];
        $l1l1l1l11111ll111111llll111ll11 = strtotime($l11ll11lllllll1l1llll11ll11ll1l['pend']) - time();
        $l1ll1llll11lll1l11l11l1l111ll1l = $l11ll11lllllll1l1llll11ll11ll1l['prize'];
        if (!empty($lllll1111l1ll1l1lll111ll11111l1)) {
            $l1ll1l1ll1l1l11lll1ll11ll111l1l = mc_credit_fetch($lllll1111l1ll1l1lll111ll11111l1);
            $lll1l1lllll1111l111ll111111ll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid` = :sid AND `uid` = :uid ORDER BY `id` DESC limit 10", array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                ":uid" => $lllll1111l1ll1l1lll111ll11111l1
            ));
        }
        include $this->template($l11ll11lllllll1l1llll11ll11ll1l['template'] . "credit");
    }
    public function doMobileDrawlist()
    {
        global $_W, $_GPC;
        $this->l1ll1l1111ll11lll1l11111lll111l();
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        load()->model('mc');
        $lllll1111l1ll1l1lll111ll11111l1 = $this->ll1l1l1111l1lllllll1l1l111lll1l();
        if ($l11ll11l1111ll111lll1l1lll1l1ll = $this->l11l1l1lllll11l1l1l11111ll1ll1l('rid')) {
            $lll1lll1l11l111l1ll1111111l1lll = pdo_fetchcolumn("SELECT `address` FROM " . tablename('xiaof_relation') . " WHERE `id` = :id", array(
                ":id" => $l11ll11l1111ll111lll1l1lll1l1ll
            ));
            $lll1lll1l11l111l1ll1111111l1lll = iunserializer($lll1lll1l11l111l1ll1111111l1lll);
        }
        if ($_W['isajax']) {
            if ($_W['ispost'] && (empty($lll1lll1l11l111l1ll1111111l1lll['phone']) or empty($lll1lll1l11l111l1ll1111111l1lll['addrs']))) {
                $ll111111lll1l1l1lll1ll1l11l111l = istrlen($_GPC['addrs']);
                $lll11l1lllllllll1l1l1ll1111l1l1 = istrlen($_GPC['name']);
                if ($ll111111lll1l1l1lll1ll1l11l111l < 5 or $ll111111lll1l1l1lll1ll1l11l111l >= 150) {
                    exit(json_encode(error(102, '收货地址长度为5-150字')));
                } elseif ($lll11l1lllllllll1l1l1ll1111l1l1 < 1 or $lll11l1lllllllll1l1l1ll1111l1l1 >= 5) {
                    exit(json_encode(error(103, '收货姓名长度为1-5字')));
                } elseif (!preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $_GPC['phone'])) {
                    exit(json_encode(error(101, '不是正确手机号')));
                } elseif ($l11ll11l1111ll111lll1l1lll1l1ll) {
                    $lll1lll1l11l111l1ll1111111l1lll = array(
                        'name' => $_GPC['name'],
                        'phone' => $_GPC['phone'],
                        'addrs' => $_GPC['addrs']
                    );
                    $lll1ll1l11ll11l111111ll1l1ll111 = array(
                        'address' => iserializer($lll1lll1l11l111l1ll1111111l1lll)
                    );
                    pdo_update("xiaof_relation", $lll1ll1l11ll11l111111ll1l1ll111, array(
                        "id" => $l11ll11l1111ll111lll1l1lll1l1ll
                    ));
                }
                exit(json_encode(error(0, '成功')));
            }
        }
        $l1l1lll11l111l111ll1l11l1111ll1 = '';
        if (!empty($_GPC['type'])) {
            $l1l1lll11l111l111ll1l11l1111ll1 = "AND `uses` = '" . intval($_GPC['type']) . "'";
        }
        $ll1l11l111l1l11111ll1l11l11ll1l = max(1, intval($_GPC['page']));
        $l11ll1l1111l11lll1ll1111lll1111 = 12;
        $l1l1ll1ll1ll11llll1l1l1l111llll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_draw') . " WHERE `uid` = '" . $lllll1111l1ll1l1lll111ll11111l1 . "' AND `attr` = '1' " . $l1l1lll11l111l111ll1l11l1111ll1 . "");
        $ll1111l1l1l11ll1111llll11l1ll11 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_draw') . " WHERE `uid` = '" . $lllll1111l1ll1l1lll111ll11111l1 . "' AND `attr` = '1' " . $l1l1lll11l111l111ll1l11l1111ll1 . " ORDER BY `id` DESC LIMIT " . ($ll1l11l111l1l11111ll1l11l11ll1l - 1) * $l11ll1l1111l11lll1ll1111lll1111 . ',' . $l11ll1l1111l11lll1ll1111lll1111);
        foreach ($ll1111l1l1l11ll1111llll11l1ll11 as &$l1lllll1l1ll11lll1ll1l111ll1l11) {
            $l1lllll1l1ll11lll1ll1l111ll1l11['pic'] = $l11ll11lllllll1l1llll11ll11ll1l['prize'][$l1lllll1l1ll11lll1ll1l111ll1l11['prizeid']]['pic'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['pic'] = empty($l1lllll1l1ll11lll1ll1l111ll1l11['pic']) ? MODULE_URL . "template/mobile/picture/tpzq.jpg" : tomedia($l1lllll1l1ll11lll1ll1l111ll1l11['pic']);
        }
        $llll11ll1ll111l1l1ll111l1ll1111 = pagination($l1l1ll1ll1ll11llll1l1l1l111llll, $ll1l11l111l1l11111ll1l11l11ll1l, $l11ll1l1111l11lll1ll1111lll1111);
        include $this->template($l11ll11lllllll1l1llll11ll11ll1l['template'] . "drawlist");
    }
    public function doMobileSave()
    {
        global $_W, $_GPC;
        if ($_W['container'] !== "wechat") {
            exit(json_encode(error(101, '请在您的微信里打开本页面参与报名')));
        }
        if (!$l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1()) {
            exit(json_encode(error(101, '报名失败,没有找到要参与的活动')));
        }
        if (time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['joinstart'])) {
            exit(json_encode(error(101, '活动报名未开始，请开始后再参加，开始时间' . $l11ll11lllllll1l1llll11ll11ll1l['joinstart'] . '')));
        }
        if (time() >= strtotime($l11ll11lllllll1l1llll11ll11ll1l['joinend'])) {
            exit(json_encode(error(101, '活动报名已结束，敬请期待下次活动')));
        }
        if ($l11ll11lllllll1l1llll11ll11ll1l['joinfollow'] == 1 && !$this->l1ll1l11111llll11l11111111llll1()) {
            exit(json_encode(error(101, '<p style="text-align:center;">请进入公众号在报名，长按二维码进入</p><img width="100%" src="' . tomedia($l11ll11lllllll1l1llll11ll11ll1l['accountqrcode']) . '"/>')));
        }
        if (empty($_W['openid'])) {
            exit(json_encode(error(101, '错误，没有获取到微信信息')));
        }
        if ($_W['isajax']) {
            $l11l1lll11ll11l1111ll1l11111l1l = count($_GPC['pics']);
            $l1l1111ll111l1llll11111l1ll1111 = empty($l11ll11lllllll1l1llll11ll11ll1l['limitpic']) ? 5 : intval($l11ll11lllllll1l1llll11ll11ll1l['limitpic']);
            if ($l11l1lll11ll11l1111ll1l11111l1l <= 0) {
                exit(json_encode(error(102, '报名失败,没有收到照片')));
            } elseif ($l11l1lll11ll11l1111ll1l11111l1l > $l1l1111ll111l1llll11111l1ll1111) {
                exit(json_encode(error(102, '报名失败,照片只允许1-' . $l1l1111ll111l1llll11111l1ll1111 . '张')));
            }
            load()->func('file');
            $ll11ll1l1lll11ll11ll111ll1llll1 = array();
            foreach ($l11ll11lllllll1l1llll11ll11ll1l['joinfield'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                if (empty($_GPC[$ll111111l1ll11lll1l1l1111lll11l['sign']])) {
                    if (empty($ll111111l1ll11lll1l1l1111lll11l['isnull'])) {
                        exit(json_encode(error(103, $ll111111l1ll11lll1l1l1111lll11l['name'] . '项不能为空')));
                    }
                    continue;
                }
                $ll11ll1l1lll11ll11ll111ll1llll1[$ll111111l1ll11lll1l1l1111lll11l['sign']] = $_GPC[$ll111111l1ll11lll1l1l1111lll11l['sign']];
            }
            if (empty($_GPC['pid'])) {
                if ($_GPC['name'] == "") {
                    exit(json_encode(error(103, '名称不能为空！')));
                }
                $lll11l1lllllllll1l1l1ll1111l1l1 = istrlen($_GPC['name']);
                if ($lll11l1lllllllll1l1l1ll1111l1l1 >= 10 or $lll11l1lllllllll1l1l1ll1111l1l1 < 1) {
                    exit(json_encode(error(103, '名称长度不合法')));
                }
                if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `phone` = :phone", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    ":phone" => $_GPC['phone']
                ))) {
                    exit(json_encode(error(104, '报名失败,一个手机号只能参加一次')));
                }
                if ($this->l1l11111llll11llll11l1lll1l11l1() != false) {
                    exit(json_encode(error(104, '报名失败,一个微信号只能参加一次')));
                }
                krsort($_GPC['pics']);
                $lll1ll11111l11l111l111l1l11ll1l = 0;
                $ll111l1l1l1l1l1l111l1llll1lll1l = '';
                if (!empty($l11ll11lllllll1l1llll11ll11ll1l['newjoindouble'])) {
                    $ll11lllll11l11llll1ll1ll11111l1 = intval($l11ll11lllllll1l1llll11ll11ll1l['newjoindouble']) * 60;
                    $lll1ll11111l11l111l111l1l11ll1l = strtotime("+" . $ll11lllll11l11llll1ll1ll11111l1 . " minute");
                    $ll111l1l1l1l1l1l111l1llll1lll1l = '。当前新报名享双倍投票时间' . intval($l11ll11lllllll1l1llll11ll11ll1l['newjoindouble']) . '小时，双倍期间被投票1票等于2票！';
                }
                $llll1111111l11111111l1l1llllll1         = $this->ll1l11111l1111111lllll11lll11l1(reset($_GPC['pics']), 240);
                $lll11l11lll1111l1lll1ll1lllll11         = array(
                    "sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    "ip" => ip2long(CLIENT_IP),
                    "nickname" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('nickname'),
                    "avatar" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('avatar'),
                    "pic" => $llll1111111l11111111l1l1llllll1,
                    "sound" => $_GPC['sound'],
                    "phone" => $_GPC['phone'],
                    "name" => $_GPC['name'],
                    "describe" => preg_replace("#\s#is", '', $_GPC['describe']),
                    "created_at" => time(),
                    "double_at" => $lll1ll11111l11l111l111l1l11ll1l,
                    "updated_at" => time()
                );
                $lll11l11lll1111l1lll1ll1lllll11['data'] = iserializer($ll11ll1l1lll11ll11ll111ll1llll1);
                if ($l11ll11lllllll1l1llll11ll11ll1l['opengroups'] >= 1) {
                    $lll11l11lll1111l1lll1ll1lllll11['groups'] = intval($_GPC['groups']);
                }
                $l1l11111lllllll1ll1l1l11l11l111           = $this->l11l1l1lllll11l1l1l11111ll1ll1l('openid');
                $lll11l11lll1111l1lll1ll1lllll11['openid'] = $l1l11111lllllll1ll1l1l11l11l111;
                if (empty($l11ll11lllllll1l1llll11ll11ll1l['joinfollow']) && empty($l1l11111lllllll1ll1l1l11l11l111)) {
                    $lll11l11lll1111l1lll1ll1lllll11['openid'] = $_W['openid'];
                }
                pdo_query("LOCK TABLES " . tablename("xiaof_toupiao") . " WRITE");
                if (!$ll11l11llll11lll1ll111l1111ll1l = pdo_fetchcolumn("SELECT `uid` FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid ORDER BY `id` DESC limit 1", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
                ))) {
                    $ll11l11llll11lll1ll111l1111ll1l = 0;
                }
                $lll11l11lll1111l1lll1ll1lllll11['uid'] = $ll11l11llll11lll1ll111l1111ll1l + 1;
                pdo_insert("xiaof_toupiao", $lll11l11lll1111l1lll1ll1lllll11);
                $ll11111ll111l1llllllllll11l11l1 = pdo_insertid();
                pdo_query("UNLOCK TABLES");
                foreach ($_GPC['pics'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                    pdo_insert("xiaof_toupiao_pic", array(
                        "sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                        "pid" => $ll11111ll111l1llllllllll11l11l1,
                        "url" => $ll111111l1ll11lll1l1l1111lll11l,
                        "thumb" => $this->ll1l11111l1111111lllll11lll11l1($ll111111l1ll11lll1l1l1111lll11l),
                        "created_at" => time()
                    ));
                }
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['opencredit']) >= 1 && intval($l11ll11lllllll1l1llll11ll11ll1l['joincredit']) >= 1) {
                    load()->model('mc');
                    if ($lllll1111l1ll1l1lll111ll11111l1 = $this->ll1l1l1111l1lllllll1l1l111lll1l()) {
                        $l1111l1l1ll111l1l111ll1111l1ll1 = mc_credit_update($lllll1111l1ll1l1lll111ll11111l1, 'credit1', intval($l11ll11lllllll1l1llll11ll11ll1l['joincredit']), array(
                            1,
                            $l11ll11lllllll1l1llll11ll11ll1l['title'] . '报名赠送积分',
                            'system'
                        ));
                        if (!is_error($l1111l1l1ll111l1l111ll1111l1ll1) && intval($l11ll11lllllll1l1llll11ll11ll1l['creditnotice']) >= 1) {
                            if ($_W['account']['level'] >= 3) {
                                mc_notice_credit1($this->l11l1l1lllll11l1l1l11111ll1ll1l('openid'), $lllll1111l1ll1l1lll111ll11111l1, intval($l11ll11lllllll1l1llll11ll11ll1l['joincredit']), $l11ll11lllllll1l1llll11ll11ll1l['title'] . '报名赠送积分', '', '谢谢参与');
                            }
                        }
                    }
                }
                if (!empty($l11ll11lllllll1l1llll11ll11ll1l['adminopenid'])) {
                    $this->ll11111l1l11111l1ll1ll11l111111($l11ll11lllllll1l1llll11ll11ll1l['adminopenid'], "有新用户报名了，名称：" . $_GPC['name'] . "。<a href='" . $this->l1111llll1l1llll11l1lll1l11l1ll('show', 'xiaof_toupiao', '&id=' . $ll11111ll111l1llllllllll11l11l1 . '') . "'>点击查看</a>", $l11ll11lllllll1l1llll11ll11ll1l['uniacid']);
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['verify'] == 1) {
                    $l11ll11ll11l1l1lllll11ll1ll11ll = '报名资料已上传，请等待审核';
                } else {
                    $l11ll11ll11l1l1lllll11ll1ll11ll = '报名成功' . $ll111l1l1l1l1l1l111l1llll1lll1l;
                }
            } else {
                $ll11111ll111l1llllllllll11l11l1 = intval($_GPC['pid']);
                if ($ll11l1lll1l11l1l1ll1l1llll1lll1 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id", array(
                    ":id" => $ll11111ll111l1llllllllll11l11l1
                ))) {
                    if ($ll11l1lll1l11l1l1ll1l1llll1lll1['verify'] == 2) {
                        exit(json_encode(error(105, '之前报名资料审核未通过，如需修改资料请联系客服')));
                    }
                } else {
                    exit(json_encode(error(104, '修改失败，没有找到您的报名信息')));
                }
                $l11llll11111l1lll1llllllllllll1 = 0;
                if ($l11ll11lllllll1l1llll11ll11ll1l['opengroups'] >= 1) {
                    $l11llll11111l1lll1llllllllllll1 = intval($_GPC['groups']);
                }
                pdo_update("xiaof_toupiao", array(
                    "verify" => 0,
                    "groups" => $l11llll11111l1lll1llllllllllll1,
                    "sound" => $_GPC['sound'],
                    "pic" => $this->ll1l11111l1111111lllll11lll11l1(reset($_GPC['pics']), 240),
                    "describe" => preg_replace("#\s#is", '', $_GPC['describe']),
                    "data" => iserializer($ll11ll1l1lll11ll11ll111ll1llll1)
                ), array(
                    "id" => $ll11111ll111l1llllllllll11l11l1
                ));
                $lll1l1lllll1111l111ll111111ll1l = pdo_fetchall("SELECT `url` FROM " . tablename('xiaof_toupiao_pic') . " WHERE `pid` = :pid", array(
                    ":pid" => $ll11111ll111l1llllllllll11l11l1
                ));
                $llll1l11l11111l1l111l11l1ll1111 = array();
                foreach ($lll1l1lllll1111l111ll111111ll1l as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                    $llll1l11l11111l1l111l11l1ll1111['pics'][] = $ll111111l1ll11lll1l1l1111lll11l['url'];
                }
                if ($_GPC['pics'] !== $llll1l11l11111l1l111l11l1ll1111['pics']) {
                    pdo_delete('xiaof_toupiao_pic', array(
                        'pid' => $ll11111ll111l1llllllllll11l11l1
                    ));
                    foreach ($_GPC['pics'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                        pdo_insert("xiaof_toupiao_pic", array(
                            "sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                            "pid" => $ll11111ll111l1llllllllll11l11l1,
                            "url" => $ll111111l1ll11lll1l1l1111lll11l,
                            "thumb" => $this->ll1l11111l1111111lllll11lll11l1($ll111111l1ll11lll1l1l1111lll11l),
                            "created_at" => time()
                        ));
                    }
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['verify'] == 1) {
                    $l11ll11ll11l1l1lllll11ll1ll11ll = '资料已上传，请等待审核';
                } else {
                    $l11ll11ll11l1l1lllll11ll1ll11ll = '资料修改成功';
                }
            }
            if (intval($l11ll11lllllll1l1llll11ll11ll1l['openposter']) == 1 && $lllll111l1ll1l1l1l1l1ll11ll1ll1 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id LIMIT 1", array(
                ":id" => intval($ll11111ll111l1llllllllll11l11l1)
            ))) {
                $l1l1lllll11ll1llll1llll1lll1l11 = $this->l111ll11llllllll111ll1l1l1l111l($lllll111l1ll1l1l1l1l1ll11ll1ll1['name'], $lllll111l1ll1l1l1l1l1ll11ll1ll1['uid'], tomedia(reset($_GPC['pics'])), urlencode($this->l1111llll1l1llll11l1lll1l11l1ll('show', 'xiaof_toupiao', '&id=' . $lllll111l1ll1l1l1l1l1ll11ll1ll1['id'] . '')));
                pdo_update("xiaof_toupiao", array(
                    "poster" => $l1l1lllll11ll1llll1llll1lll1l11
                ), array(
                    "id" => $lllll111l1ll1l1l1l1l1ll11ll1ll1['id']
                ));
            }
            exit(json_encode(error(0, $l11ll11ll11l1l1lllll11ll1ll11ll)));
        }
    }
    public function doMobileVote()
    {
        global $_W, $_GPC;
        if (!$l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1()) {
            exit(json_encode(error(101, '投票失败,没有找到要参与的活动')));
        }
        if (time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['start'])) {
            exit(json_encode(error(101, '活动未开始，请开始后再投票，开始时间' . $l11ll11lllllll1l1llll11ll11ll1l['start'] . '')));
        }
        if (time() >= strtotime($l11ll11lllllll1l1llll11ll11ll1l['end'])) {
            exit(json_encode(error(101, '活动已结束，敬请期待下次活动')));
        }
        if (empty($_GPC['id']) or empty($_GPC['type'])) {
            exit(json_encode(error(102, '参数错误')));
        }
        if (!$llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `id` = :id", array(
            ":id" => intval($_GPC['id'])
        ))) {
            exit(json_encode(error(108, '没有找到选手')));
        }
        switch ($_GPC['type']) {
            case 'good':
                $l1l111ll111lllll11l1l111l1l111l = '';
                if (count($l11ll11lllllll1l1llll11ll11ll1l['advotepic']) >= 1) {
                    $l1l111ll111lllll11l1l111l1l111l = '<br/><div class="acid-lists"><ul class="swiper-wrapper">';
                    foreach ($l11ll11lllllll1l1llll11ll11ll1l['advotepic'] as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                        $l1l111ll111lllll11l1l111l1l111l .= '<li class="acid-swiper-slide swiper-slide"><a href="' . $l11ll11lllllll1l1llll11ll11ll1l['advotelinkarr'][$l1lll11llll11ll1lllllllll1l1l11] . '"><img class="acid-qrcode" src="' . tomedia($ll111111l1ll11lll1l1l1111lll11l) . '"/></a></li>';
                    }
                    $l1l111ll111lllll11l1l111l1l111l .= '</ul><div class="swiper-scrollbar"></div></div>';
                }
                if ($_W['container'] != "wechat") {
                    exit(json_encode(error(102, '请在您微信里打开本页面参与投票' . $l1l111ll111lllll11l1l111l1l111l)));
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['verify'] == 1 && $llll1l11l11111l1l111l11l1ll1111['verify'] != 1) {
                    if ($llll1l11l11111l1l111l11l1ll1111['verify'] == 0) {
                        exit(json_encode(error(109, '该选手作品正在审核，暂不接受投票' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                if ($llll1l11l11111l1l111l11l1ll1111['verify'] == 2) {
                    exit(json_encode(error(110, '该选手作品审核未通过，不接受投票' . $l1l111ll111lllll11l1l111l1l111l)));
                }
                if (empty($_W['openid'])) {
                    exit(json_encode(error(101, '错误，没有获取到微信信息' . $l1l111ll111lllll11l1l111l1l111l)));
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['joinendtime'] >= 1) {
                    $lll11l1l11l11111l1ll11l11l11l11 = strtotime('+' . $l11ll11lllllll1l1llll11ll11ll1l['joinendtime'] . ' day', $llll1l11l11111l1l111l11l1ll1111['created_at']);
                    if ($lll11l1l11l11111l1ll11l11l11l11 <= time()) {
                        exit(json_encode(error(101, '每位选手只有' . $l11ll11lllllll1l1llll11ll11ll1l['joinendtime'] . '天投票时间,当前选手于' . date("Y-m-d H:i", $lll11l1l11l11111l1ll11l11l11l11) . '已截止投票。' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['votefollow'] == 1 && !$this->l1ll1l11111llll11l11111111llll1()) {
                    exit(json_encode(error(103, '<p style="text-align:center;">请进入公众号投票，长按二维码进入</p><img width="100%" src="' . tomedia($l11ll11lllllll1l1llll11ll11ll1l['accountqrcode']) . '"/>')));
                }
                if ($llll1l11l11111l1l111l11l1ll1111['verify'] == 3) {
                    if ($llll1l11l11111l1l111l11l1ll1111['locking_at'] >= time() or intval($l11ll11lllllll1l1llll11ll11ll1l['releasetime']) == 0) {
                        exit(json_encode(error(110, '系统检测该选手投票数据异常，已自动锁定，不在接受投票。' . $l1l111ll111lllll11l1l111l1l111l)));
                    } else {
                        pdo_update("xiaof_toupiao", array(
                            'verify' => '0',
                            'locking_at' => '0'
                        ), array(
                            "id" => $llll1l11l11111l1l111l11l1ll1111['id']
                        ));
                    }
                }
                if (count($l11ll11lllllll1l1llll11ll11ll1l['city']) >= 1) {
                    if (in_array('ip', $l11ll11lllllll1l1llll11ll11ll1l['veriftype'])) {
                        if (!$ll1lllll1l11llll1ll1llll11l1111 = cache_read('ipaddr:' . md5(CLIENT_IP))) {
                            $ll1111l111l1l1lllll1l1111l1l111 = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . CLIENT_IP);
                            $ll1lllll1l11llll1ll1llll11l1111 = json_decode($ll1111l111l1l1lllll1l1111l1l111, true);
                            cache_write('ipaddr:' . md5(CLIENT_IP), $ll1lllll1l11llll1ll1llll11l1111);
                        }
                        $ll1l1llll1lll1l111l11ll1l1ll1ll = intval($l11ll11lllllll1l1llll11ll11ll1l['citylevel']);
                        switch ($ll1l1llll1lll1l111l11ll1l1ll1ll) {
                            case '0':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $ll1lllll1l11llll1ll1llll11l1111['data']['region'];
                                break;
                            case '1':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $ll1lllll1l11llll1ll1llll11l1111['data']['city'];
                                break;
                            case '2':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $ll1lllll1l11llll1ll1llll11l1111['data']['city'];
                                break;
                            default:
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $ll1lllll1l11llll1ll1llll11l1111['data']['region'];
                                break;
                        }
                        $l1111ll11111lll1l11ll11111l11l1 = false;
                        foreach ($l11ll11lllllll1l1llll11ll11ll1l['city'] as $ll111111l1ll11lll1l1l1111lll11l) {
                            if (strexists($ll111111l1ll11lll1l1l1111lll11l, $ll111l1lllll1l1l1l1ll11l11l11l1) or strexists($ll111l1lllll1l1l1l1ll11l11l11l1, $ll111111l1ll11lll1l1l1111lll11l)) {
                                $l1111ll11111lll1l11ll11111l11l1 = true;
                                break;
                            }
                        }
                        if (!$l1111ll11111lll1l11ll11111l11l1) {
                            exit(json_encode(error(104, '活动仅限本地区参与投票' . $l1l111ll111lllll11l1l111l1l111l)));
                        }
                    }
                    if (in_array('gps', $l11ll11lllllll1l1llll11ll11ll1l['veriftype'])) {
                        $lllll111ll111lll1lll111l11ll11l = $this->l11l1l1lllll11l1l1l11111ll1ll1l('gps_city');
                        if (empty($lllll111ll111lll1lll111l11ll11l)) {
                            exit(json_encode(error(115, '未进行GPS定位')));
                        }
                        $ll1l1llll1lll1l111l11ll1l1ll1ll = intval($l11ll11lllllll1l1llll11ll11ll1l['citylevel']);
                        switch ($ll1l1llll1lll1l111l11ll1l1ll1ll) {
                            case '0':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['province'];
                                break;
                            case '1':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['city'];
                                break;
                            case '2':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['county'];
                                break;
                            default:
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['province'];
                                break;
                        }
                        $l1111ll11111lll1l11ll11111l11l1 = false;
                        foreach ($l11ll11lllllll1l1llll11ll11ll1l['city'] as $ll111111l1ll11lll1l1l1111lll11l) {
                            if (strexists($ll111111l1ll11lll1l1l1111lll11l, $ll111l1lllll1l1l1l1ll11l11l11l1) or strexists($ll111l1lllll1l1l1l1ll11l11l11l1, $ll111111l1ll11lll1l1l1111lll11l)) {
                                $l1111ll11111lll1l11ll11111l11l1 = true;
                                break;
                            }
                        }
                        if (!$l1111ll11111lll1l11ll11111l11l1) {
                            exit(json_encode(error(104, '活动仅限本地区参与投票' . $l1l111ll111lllll11l1l111l1l111l)));
                        }
                    }
                    if (in_array('fans', $l11ll11lllllll1l1llll11ll11ll1l['veriftype'])) {
                        $lllll111ll111lll1lll111l11ll11l = $this->l11l1l1lllll11l1l1l11111ll1ll1l('fans_city');
                        if (empty($lllll111ll111lll1lll111l11ll11l)) {
                            exit(json_encode(error(116, '未获取到资料地址')));
                        }
                        $ll1l1llll1lll1l111l11ll1l1ll1ll = intval($l11ll11lllllll1l1llll11ll11ll1l['citylevel']);
                        switch ($ll1l1llll1lll1l111l11ll1l1ll1ll) {
                            case '0':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['province'];
                                break;
                            case '1':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['city'];
                                break;
                            case '2':
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['city'];
                                break;
                            default:
                                $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['province'];
                                break;
                        }
                        $l1111ll11111lll1l11ll11111l11l1 = false;
                        foreach ($l11ll11lllllll1l1llll11ll11ll1l['city'] as $ll111111l1ll11lll1l1l1111lll11l) {
                            if (strexists($ll111111l1ll11lll1l1l1111lll11l, $ll111l1lllll1l1l1l1ll11l11l11l1) or strexists($ll111l1lllll1l1l1l1ll11l11l11l1, $ll111111l1ll11lll1l1l1111lll11l)) {
                                $l1111ll11111lll1l11ll11111l11l1 = true;
                                break;
                            }
                        }
                        if (!$l1111ll11111lll1l11ll11111l11l1) {
                            exit(json_encode(error(104, '活动仅限本地区参与投票' . $l1l111ll111lllll11l1l111l1l111l)));
                        }
                    }
                }
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['minutenum']) >= 1) {
                    $l1l1l1l11l1l11111lll11l1l11lll1 = strtotime('-1 minute');
                    $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $l11ll11lllllll1l1llll11ll11ll1l['id'] . "' AND `pid` = '" . $llll1l11l11111l1l111l11l1ll1111['id'] . "' AND `created_at` >= '" . $l1l1l1l11l1l11111lll11l1l11lll1 . "'");
                    if ($ll11llll1l1lll11l111lll111111ll >= $l11ll11lllllll1l1llll11ll11ll1l['minutenum']) {
                        $l1lll1ll11lllll11ll11111ll1l11l = intval($l11ll11lllllll1l1llll11ll11ll1l['releasetime']);
                        pdo_update("xiaof_toupiao", array(
                            'verify' => '3',
                            'locking_at' => strtotime('' . $l1lll1ll11lllll11ll11111ll1l11l . ' minute')
                        ), array(
                            "id" => $llll1l11l11111l1l111l11l1ll1111['id']
                        ));
                        exit(json_encode(error(105, '系统检测该选手投票数据异常，已自动锁定，不在接受投票。' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['verifysms'] == 1) {
                    if (isset($_GPC['verifycode']) && isset($_GPC['phone'])) {
                        if (isset($_SESSION['verifycode'])) {
                            $l11l1l1ll1lll111111l11111111111 = iunserializer($_SESSION['verifycode']);
                            if ($l11l1l1ll1lll111111l11111111111['phone'] != $_GPC['phone'] or $l11l1l1ll1lll111111l11111111111['randcode'] != $_GPC['verifycode']) {
                                exit(json_encode(error(113, '验证码不正确')));
                            } else {
                                if ($l11ll11l1111ll111lll1l1lll1l1ll = $this->l11l1l1lllll11l1l1l11111ll1ll1l('rid')) {
                                    $lll1ll1l11ll11l111111ll1l1ll111 = array(
                                        'phone' => $l11l1l1ll1lll111111l11111111111['phone'],
                                        'city' => iserializer($l11l1l1ll1lll111111l11111111111['addrs'])
                                    );
                                    pdo_update("xiaof_relation", $lll1ll1l11ll11l111111ll1l1ll111, array(
                                        "id" => $l11ll11l1111ll111lll1l1lll1l1ll
                                    ));
                                }
                            }
                        } else {
                            exit(json_encode(error(112, '验证出现错误,请刷新重试')));
                        }
                    } else {
                        $lllll111ll111lll1lll111l11ll11l = $this->l11l1l1lllll11l1l1l11111ll1ll1l('city');
                        if (empty($lllll111ll111lll1lll111l11ll11l)) {
                            exit(json_encode(error(111, '手机号验证')));
                        }
                        if (in_array('sms', $l11ll11lllllll1l1llll11ll11ll1l['veriftype'])) {
                            $ll1l1llll1lll1l111l11ll1l1ll1ll = intval($l11ll11lllllll1l1llll11ll11ll1l['citylevel']);
                            switch ($ll1l1llll1lll1l111l11ll1l1ll1ll) {
                                case '0':
                                    $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['province'];
                                    break;
                                case '1':
                                    $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['city'];
                                    break;
                                case '2':
                                    $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['city'];
                                    break;
                                default:
                                    $ll111l1lllll1l1l1l1ll11l11l11l1 = $lllll111ll111lll1lll111l11ll11l['province'];
                                    break;
                            }
                            $ll111l1lllll1l1l1l1ll11l11l11l1 = trim($ll111l1lllll1l1l1l1ll11l11l11l1);
                            if (empty($ll111l1lllll1l1l1l1ll11l11l11l1)) {
                                exit(json_encode(error(111, '手机号验证')));
                            }
                            if (count($l11ll11lllllll1l1llll11ll11ll1l['city']) >= 1) {
                                $l1111ll11111lll1l11ll11111l11l1 = false;
                                foreach ($l11ll11lllllll1l1llll11ll11ll1l['city'] as $ll111111l1ll11lll1l1l1111lll11l) {
                                    if (strexists($ll111111l1ll11lll1l1l1111lll11l, $ll111l1lllll1l1l1l1ll11l11l11l1)) {
                                        $l1111ll11111lll1l11ll11111l11l1 = true;
                                        break;
                                    }
                                }
                                if (!$l1111ll11111lll1l11ll11111l11l1) {
                                    exit(json_encode(error(114, '活动仅限本地区参与投票，您的手机归属地不在本地区' . $l1l111ll111lllll11l1l111l1l111l)));
                                }
                            }
                        }
                    }
                }
                $l1l11111lllllll1ll1l1l11l11l111 = $this->l11l1l1lllll11l1l1l11111ll1ll1l('openid');
                if (empty($l11ll11lllllll1l1llll11ll11ll1l['votefollow']) && empty($l1l11111lllllll1ll1l1l11l11l111)) {
                    $l1l11111lllllll1ll1l1l11l11l111 = $_W['openid'];
                }
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['limitone']) >= 1) {
                    $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xiaof_toupiao_log") . " WHERE `sid` = :sid AND `pid` = :pid AND `openid` = :openid", array(
                        ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                        ":pid" => $llll1l11l11111l1l111l11l1ll1111['id'],
                        ":openid" => $l1l11111lllllll1ll1l1l11l11l111
                    ));
                    if ($ll11llll1l1lll11l111lll111111ll >= intval($l11ll11lllllll1l1llll11ll11ll1l['limitone'])) {
                        exit(json_encode(error(107, '本次活动期间您对选手编号' . $llll1l11l11111l1l111l11l1ll1111['uid'] . '允许最大投票数达到上限，不能再继续给Ta投票' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['vnum'] >= 1) {
                    $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $l11ll11lllllll1l1llll11ll11ll1l['id'] . "' AND `openid` = '" . $l1l11111lllllll1ll1l1l11l11l111 . "' AND `unique_at` = '" . date(Ymd) . "'");
                    if ($ll11llll1l1lll11l111lll111111ll >= $l11ll11lllllll1l1llll11ll11ll1l['vnum']) {
                        exit(json_encode(error(105, '一个微信号每天只能给' . $l11ll11lllllll1l1llll11ll11ll1l['vnum'] . '个选手投票' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_safe") . " WHERE `sid` = :sid AND `ip` = :ip ", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    ":ip" => ip2long(CLIENT_IP)
                ))) {
                    exit(json_encode(error(106, '抱歉，系统检测到您非正常投票，投票失败。还绿色公平环境，拒绝刷票。如有疑问联系我们的公众号申诉解封' . $l1l111ll111lllll11l1l111l1l111l)));
                }
                if (pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_log") . " WHERE `sid` = :sid AND `pid` = :pid AND `openid` = :openid AND `unique_at` = :unique_at limit 1", array(
                    ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    ":pid" => $llll1l11l11111l1l111l11l1ll1111['id'],
                    ":openid" => $l1l11111lllllll1ll1l1l11l11l111,
                    ":unique_at" => date("Ymd")
                ))) {
                    exit(json_encode(error(107, '您今天已经给编号' . $llll1l11l11111l1l111l11l1ll1111['uid'] . '投过票了，明天再来吧' . $l1l111ll111lllll11l1l111l1l111l)));
                }
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['ipmaxvote']) >= 2) {
                    $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $l11ll11lllllll1l1llll11ll11ll1l['id'] . "' AND `ip` = '" . ip2long(CLIENT_IP) . "' AND `unique_at` = '" . date("Ymd") . "'");
                    if ($ll11llll1l1lll11l111lll111111ll >= $l11ll11lllllll1l1llll11ll11ll1l['ipmaxvote']) {
                        exit(json_encode(error(108, '同一个IP每天只能投' . $l11ll11lllllll1l1llll11ll11ll1l['ipmaxvote'] . '票' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['maxvotenum']) >= 1) {
                    $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $l11ll11lllllll1l1llll11ll11ll1l['id'] . "' AND `openid` = '" . $l1l11111lllllll1ll1l1l11l11l111 . "'");
                    if ($ll11llll1l1lll11l111lll111111ll >= $l11ll11lllllll1l1llll11ll11ll1l['maxvotenum']) {
                        exit(json_encode(error(109, '本次活动您共有' . $l11ll11lllllll1l1llll11ll11ll1l['maxvotenum'] . '票，已经用完，不能再投。' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['maxgoodnum']) >= 1) {
                    if (time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['maxgoodtime']) && $llll1l11l11111l1l111l11l1ll1111['good'] >= $l11ll11lllllll1l1llll11ll11ll1l['maxgoodnum']) {
                        exit(json_encode(error(109, '本次活动' . $l11ll11lllllll1l1llll11ll11ll1l['maxgoodtime'] . '之前，每位选手最多允许被投' . $l11ll11lllllll1l1llll11ll11ll1l['maxgoodnum'] . '票，超出无效。' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['limitonevote'] >= 1) {
                    $l111l1l1l1l1llll1ll111111l1ll1l = $this->l11llll11l11l1ll1l11lll1l111111();
                    $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_log') . " where `sid` = '" . $l11ll11lllllll1l1llll11ll11ll1l['id'] . "' AND `pid` = '" . $llll1l11l11111l1l111l11l1ll1111['id'] . "' AND `unique_at` = '" . date("Ymd") . "' AND `openid` IN ('" . implode("','", $l111l1l1l1l1llll1ll111111l1ll1l) . "')");
                    if ($ll11llll1l1lll11l111lll111111ll >= $l11ll11lllllll1l1llll11ll11ll1l['limitonevote']) {
                        exit(json_encode(error(105, '同一选手每天最多只能给他投' . $l11ll11lllllll1l1llll11ll11ll1l['limitonevote'] . '票' . $l1l111ll111lllll11l1l111l1l111l)));
                    }
                }
                $l1llll11l1ll1ll1l1111l1l1l111l1 = 1;
                $l1l1l11111lll1ll11111l1lllll11l = intval($l11ll11lllllll1l1llll11ll11ll1l['double']);
                if ($l1l1l11111lll1ll11111l1lllll11l >= 2 && time() >= strtotime($l11ll11lllllll1l1llll11ll11ll1l['doublestart']) && time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['doubleend'])) {
                    $l1llll11l1ll1ll1l1111l1l1l111l1 = $l1l1l11111lll1ll11111l1lllll11l;
                } elseif ($llll1l11l11111l1l111l11l1ll1111['double_at'] > time()) {
                    $l1llll11l1ll1ll1l1111l1l1l111l1 = 2;
                }
                $l11l111l11ll1ll1l11lll11lll11l1 = empty($_W['fans']['fanid']) ? 0 : $_W['fans']['fanid'];
                pdo_insert("xiaof_toupiao_log", array(
                    "sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    "pid" => $llll1l11l11111l1l111l11l1ll1111['id'],
                    "fanid" => $l11l111l11ll1ll1l11lll11lll11l1,
                    "nickname" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('nickname'),
                    "avatar" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('avatar'),
                    "num" => $l1llll11l1ll1ll1l1111l1l1l111l1,
                    "openid" => $l1l11111lllllll1ll1l1l11l11l111,
                    "ip" => ip2long(CLIENT_IP),
                    "unique_at" => date("Ymd"),
                    "created_at" => time()
                ));
                $llllll1llll111l1llll1lllll1llll = 1;
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['openvirtualclick']) >= 1) {
                    $ll11111ll1lll11111111l1l1l11l11 = rand(1, 10);
                    $llllll1llll111l1llll1lllll1llll = $llllll1llll111l1llll1lllll1llll + $ll11111ll1lll11111111l1l1l11l11;
                }
                pdo_query("UPDATE " . tablename("xiaof_toupiao") . " SET `good` = good+" . $l1llll11l1ll1ll1l1111l1l1l111l1 . ", `click` = click+" . $llllll1llll111l1llll1lllll1llll . ", `updated_at` = '" . time() . "' WHERE `id` = '" . $llll1l11l11111l1l111l11l1ll1111['id'] . "'");
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['opencredit']) >= 1 && intval($l11ll11lllllll1l1llll11ll11ll1l['votecredit']) >= 1) {
                    load()->model('mc');
                    if ($lllll1111l1ll1l1lll111ll11111l1 = $this->ll1l1l1111l1lllllll1l1l111lll1l()) {
                        $l1111l1l1ll111l1l111ll1111l1ll1 = mc_credit_update($lllll1111l1ll1l1lll111ll11111l1, 'credit1', intval($l11ll11lllllll1l1llll11ll11ll1l['votecredit']), array(
                            1,
                            $l11ll11lllllll1l1llll11ll11ll1l['title'] . '投票赠送积分',
                            'system'
                        ));
                        if (!is_error($l1111l1l1ll111l1l111ll1111l1ll1) && intval($l11ll11lllllll1l1llll11ll11ll1l['creditnotice']) >= 1) {
                            if ($_W['account']['level'] >= 3) {
                                mc_notice_credit1($this->l11l1l1lllll11l1l1l11111ll1ll1l('openid'), $lllll1111l1ll1l1lll111ll11111l1, intval($l11ll11lllllll1l1llll11ll11ll1l['votecredit']), $l11ll11lllllll1l1llll11ll11ll1l['title'] . '投票赠送积分', '', '谢谢参与');
                            }
                        }
                    }
                }
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['dynamicnotice']) >= 1) {
                    if ($l111l1111lll1111l111lll11111l1l = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `oauth_uniacid` = :oauth_uniacid AND `openid` = :openid limit 1", array(
                        ":oauth_uniacid" => $_SESSION['oauth_acid'],
                        ":openid" => $llll1l11l11111l1l111l11l1ll1111['openid']
                    ))) {
                        $ll1ll1l11ll1lll11l1lllll11lll1l = pdo_fetch("SELECT (SELECT COUNT(*) FROM " . tablename("xiaof_toupiao") . " WHERE `sid`=k.sid AND `verify`!='2' AND k.good<good ) as top FROM " . tablename("xiaof_toupiao") . " as k WHERE `id` = :id", array(
                            ":id" => $llll1l11l11111l1l111l11l1ll1111['id']
                        ));
                        $l111l111lllll111111111llll111ll = pdo_fetchcolumn("SELECT `good` FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `verify`!='2' AND `good` > :good ORDER BY `good` ASC limit 1", array(
                            ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                            ":good" => $llll1l11l11111l1l111l11l1ll1111['good'] + $l1llll11l1ll1ll1l1111l1l1l111l1
                        ));
                        $ll11llll1llll111l1111llll1l111l = pdo_fetchcolumn("SELECT `good` FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = :sid AND `verify`!='2' AND `good` < :good ORDER BY `good` DESC limit 1", array(
                            ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                            ":good" => $llll1l11l11111l1l111l11l1ll1111['good'] + $l1llll11l1ll1ll1l1111l1l1l111l1
                        ));
                        $llll1l1l1llllll1111lll1l11l1lll = pdo_fetch("SELECT (SELECT COUNT(*) FROM " . tablename("xiaof_toupiao") . " WHERE `sid`=k.sid AND `verify`!='2' AND `good`=k.good AND `id`<k.id) as top FROM " . tablename("xiaof_toupiao") . " as k WHERE `id` = :id", array(
                            ":id" => $llll1l11l11111l1l111l11l1ll1111['id']
                        ));
                        $llllll1lll11l1ll11111l111l1ll1l = $ll1ll1l11ll1lll11l1lllll11lll1l['top'] + 1 + $llll1l1l1llllll1111lll1l11l1lll['top'];
                        $ll1ll1l1ll1lll1l1111llll11lllll = $llll1l11l11111l1l111l11l1ll1111['good'] - $ll11llll1llll111l1111llll1l111l;
                        $l11l1l1l111l111l1llll1lllllllll = $l111l111lllll111111111llll111ll - $llll1l11l11111l1l111l11l1ll1111['good'];
                        if (!$l111l111lllll111111111llll111ll) {
                            $l11l1l1l111l111l1llll1lllllllll = 0;
                        }
                        $this->ll11111l1l11111l1ll1ll11l111111($llll1l11l11111l1l111l11l1ll1111['openid'], '微信好友：' . $this->l11l1l1lllll11l1l1l11111ll1ll1l('nickname') . '刚刚为您《' . $llll1l11l11111l1l111l11l1ll1111['name'] . '》投了' . $l1llll11l1ll1ll1l1111l1l1l111l1 . '票。当前票数为' . ($llll1l11l11111l1l111l11l1ll1111['good'] + $l1llll11l1ll1ll1l1111l1l1l111l1) . ' 排名' . $llllll1lll11l1ll11111l111l1ll1l . ',距前一名差' . $l11l1l1l111l111l1llll1lllllllll . '票 后一名差' . $ll1ll1l1ll1lll1l1111llll11lllll . '票', $l111l1111lll1111l111lll11111l1l['uniacid']);
                    }
                }
                if ($l11ll11lllllll1l1llll11ll11ll1l['advotetype'] == 1) {
                    $lll1l1ll111l11l111l11lll1111ll1 = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_acid') . " WHERE `sid` = :sid", array(
                        ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
                    ));
                    $l1l111ll111lllll11l1l111l1l111l = '<br/><div class="acid-lists"><ul class="swiper-wrapper">';
                    foreach ($lll1l1ll111l11l111l11lll1111ll1 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                        $l1l111ll111lllll11l1l111l1l111l .= '<li class="acid-swiper-slide swiper-slide"><img class="acid-qrcode" src="' . tomedia($ll111111l1ll11lll1l1l1111lll11l['qrcode']) . '"/></li>';
                    }
                    $l1l111ll111lllll11l1l111l1l111l .= '</ul><div class="swiper-scrollbar"></div></div>';
                    if ($llll1l111lll1l1ll1l11ll111l1111 = pdo_fetch("SELECT * FROM " . tablename('xiaof_toupiao_rule') . " WHERE `sid` = '" . $l11ll11lllllll1l1llll11ll11ll1l['id'] . "' AND `action` = '3' limit 1")) {
                        $l1lll1l1llllll1111ll11ll1ll1ll1 = str_replace(array(
                            '^',
                            '$',
                            '*'
                        ), '', $llll1l111lll1l1ll1l11ll111l1111['keyword']);
                        $l1lll1l1llllll1111ll11ll1ll1ll1 = str_replace('[0-9]', $llll1l11l11111l1l111l11l1ll1111['uid'], $l1lll1l1llllll1111ll11ll1ll1ll1);
                        $l1l111ll111lllll11l1l111l1l111l .= "<span style='text-align: center;'><font color='#FBA02D'>长按上面二维码进入<br/>发送" . $l1lll1l1llllll1111ll11ll1ll1ll1 . "即可为Ta多投一票</font></span>";
                    } else {
                        $l1l111ll111lllll11l1l111l1l111l .= "<font color='#FBA02D'>tips：长按二维码进入其他公众号可为Ta多投一票哦</font>";
                    }
                }
                if ($l1llll11l1ll1ll1l1111l1l1l111l1 >= 2) {
                    exit(json_encode(error(0, '您成功给编号' . $llll1l11l11111l1l111l11l1ll1111['uid'] . '投了' . $l1llll11l1ll1ll1l1111l1l1l111l1 . '票<br/>当前选手多倍投票时间1票等于' . $l1llll11l1ll1ll1l1111l1l1l111l1 . '票' . $l1l111ll111lllll11l1l111l1l111l)));
                }
                exit(json_encode(error(0, '您成功给编号' . $llll1l11l11111l1l111l11l1ll1111['uid'] . '投了1票，谢谢支持' . $l1l111ll111lllll11l1l111l1l111l)));
                break;
            case 'click':
                pdo_update("xiaof_toupiao", array(
                    "click" => $llll1l11l11111l1l111l11l1ll1111['click'] + 1
                ), array(
                    "id" => intval($_GPC['id'])
                ));
                break;
            case 'share':
                pdo_update("xiaof_toupiao", array(
                    "share" => $llll1l11l11111l1l111l11l1ll1111['share'] + 1
                ), array(
                    "id" => intval($_GPC['id'])
                ));
                break;
        }
    }
    public function doMobileUseprop()
    {
        global $_W, $_GPC;
        $this->l1ll1l1111ll11lll1l11111lll111l();
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        load()->model('mc');
        $lllll1111l1ll1l1lll111ll11111l1 = $this->ll1l1l1111l1lllllll1l1l111lll1l();
        if (empty($lllll1111l1ll1l1lll111ll11111l1)) {
            exit(json_encode(error(999, '没有获取到用户信息')));
        }
        $lll1l1l1l11llll1l11111l1l11l11l = pdo_fetch("SELECT * FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid`=:sid AND `uid`=:uid AND `uses`='2' AND `attr`=:attr", array(
            ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
            ":uid" => $lllll1111l1ll1l1lll111ll11111l1,
            ":attr" => intval($_GPC['type'])
        ));
        if ($lll1l1l1l11llll1l11111l1l11l11l) {
            if ($llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename('xiaof_toupiao') . " WHERE `id`=:id", array(
                ":id" => intval($_GPC['pid'])
            ))) {
                if ($llll1l11l11111l1l111l11l1ll1111['double_at'] < time()) {
                    $lll1ll11111l11l111l111l1l11ll1l = strtotime("+" . $lll1l1l1l11llll1l11111l1l11l11l['num'] . " minute");
                } else {
                    $lll1ll11111l11l111l111l1l11ll1l = strtotime("+" . $lll1l1l1l11llll1l11111l1l11l11l['num'] . " minute", $llll1l11l11111l1l111l11l1ll1111['double_at']);
                }
                pdo_insert("xiaof_toupiao_drawlog", array(
                    "sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
                    "uid" => $lllll1111l1ll1l1lll111ll11111l1,
                    "uname" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('nickname'),
                    "avatar" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('avatar'),
                    "pid" => intval($_GPC['pid']),
                    "attr" => $lll1l1l1l11llll1l11111l1l11l11l['attr'],
                    "data" => $lll1l1l1l11llll1l11111l1l11l11l['num'],
                    "created_at" => time()
                ));
                pdo_update("xiaof_toupiao", array(
                    "double_at" => $lll1ll11111l11l111l111l1l11ll1l
                ), array(
                    "id" => $llll1l11l11111l1l111l11l1ll1111['id']
                ));
                pdo_update("xiaof_toupiao_draw", array(
                    "uses" => 1,
                    "bdelete_at" => time()
                ), array(
                    "id" => $lll1l1l1l11llll1l11111l1l11l11l['id']
                ));
                if (intval($l11ll11lllllll1l1llll11ll11ll1l['dynamicnotice']) >= 1) {
                    if ($l111l1111lll1111l111lll11111l1l = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `oauth_uniacid` = :oauth_uniacid AND `openid` = :openid limit 1", array(
                        ":oauth_uniacid" => $_SESSION['oauth_acid'],
                        ":openid" => $llll1l11l11111l1l111l11l1ll1111['openid']
                    ))) {
                        $this->ll11111l1l11111l1ll1ll11l111111($llll1l11l11111l1l111l11l1ll1111['openid'], '微信好友：' . $this->l11l1l1lllll11l1l1l11111ll1ll1l('nickname') . '刚刚为您《' . $llll1l11l11111l1l111l11l1ll1111['name'] . '》使用了双倍投票券' . $lll1l1l1l11llll1l11111l1l11l11l['num'] . '分钟。当前双倍时间至' . date("Y-m-d H:i", $lll1ll11111l11l111l111l1l11ll1l), $l111l1111lll1111l111lll11111l1l['uniacid']);
                    }
                }
                exit(json_encode(error(0, '您成功给编号' . intval($_GPC['uid']) . '使用双倍投票道具<br/>Ta现在投票双倍至' . date("Y-m-d H:i", $lll1ll11111l11l111l111l1l11ll1l) . '结束。</br/>2秒后刷新页面')));
            }
        }
        exit(json_encode(error(999, '您还没有该道具')));
    }
    public function doMobileDraw()
    {
        global $_W, $_GPC;
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        if ($l11ll11lllllll1l1llll11ll11ll1l['opendraw'] != 1) {
            exit(json_encode(error(999, '积分抽奖功能未开启')));
        }
        if (time() <= strtotime($l11ll11lllllll1l1llll11ll11ll1l['start'])) {
            exit(json_encode(error(999, "活动未开始，请开始后再抽奖，开始时间" . $l11ll11lllllll1l1llll11ll11ll1l['start'] . "")));
        }
        if (time() >= strtotime($l11ll11lllllll1l1llll11ll11ll1l['end'])) {
            exit(json_encode(error(999, "抽奖活动已结束，敬请期待下次活动")));
        }
        if (!$this->l1ll1l11111llll11l11111111llll1()) {
            exit(json_encode(error(999, '<p style="text-align:center;">请关注公众号后再抽奖。<br/>长按二维码进入</p><img width="100%" src="' . tomedia($l11ll11lllllll1l1llll11ll11ll1l['accountqrcode']) . '"/>')));
        }
        load()->model('mc');
        $lllll1111l1ll1l1lll111ll11111l1 = $this->ll1l1l1111l1lllllll1l1l111lll1l();
        if (empty($lllll1111l1ll1l1lll111ll11111l1)) {
            exit(json_encode(error(999, '失败，您的积分不足。<br/>抽奖一次需消耗' . intval($l11ll11lllllll1l1llll11ll11ll1l['drawcredit']) . '积分。加油获取积分吧！')));
        }
        $llll1l1l1l1lll11l1ll11l111l111l = strtotime(date('Ymd'));
        $lll111llll1l1111l111l1l111l1l1l = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_draw') . " WHERE `uid` = :uid AND `created_at` BETWEEN " . $llll1l1l1l1lll11l1ll11l111l111l . " AND " . ($llll1l1l1l1lll11l1ll11l111l111l + 86400) . "", array(
            ":uid" => $lllll1111l1ll1l1lll111ll11111l1
        ));
        if (intval($l11ll11lllllll1l1llll11ll11ll1l['drawlimit']) >= 1) {
            if ($l11ll11lllllll1l1llll11ll11ll1l['drawlimit'] <= $lll111llll1l1111l111l1l111l1l1l) {
                exit(json_encode(error(999, '您今日抽奖次数已经达到上限')));
            }
        }
        $l1ll1l1ll1l1l11lll1ll11ll111l1l = mc_credit_fetch($lllll1111l1ll1l1lll111ll11111l1);
        if ($l1ll1l1ll1l1l11lll1ll11ll111l1l['credit1'] < intval($l11ll11lllllll1l1llll11ll11ll1l['drawcredit'])) {
            exit(json_encode(error(999, '失败，您的积分不足。<br/>抽奖一次需消耗' . intval($l11ll11lllllll1l1llll11ll11ll1l['drawcredit']) . '积分。加油获取积分吧！')));
        }
        $l1ll1llll11lll1l11l11l1l111ll1l = $l11ll11lllllll1l1llll11ll11ll1l['prize'];
        $ll11111l1lll1111l11ll1l1ll1l1l1 = $this->ll1llll1l111l11l1ll1ll1111lllll($l1ll1llll11lll1l11l11l1l111ll1l, $l11ll11lllllll1l1llll11ll11ll1l['id']);
        $l1lll1111lll11l1111l1111l1l1l11 = $l1ll1llll11lll1l11l11l1l111ll1l[$ll11111l1lll1111l11ll1l1ll1l1l1];
        if (!isset($l1lll1111lll11l1111l1111l1l1l11['attr'])) {
            exit(json_encode(error(999, '系统错误,抽奖失败。您的积分没有扣除，请稍后再试或联系我们')));
        }
        $lllllll11ll11l1lll11111lll11lll = mc_credit_update($lllll1111l1ll1l1lll111ll11111l1, 'credit1', -intval($l11ll11lllllll1l1llll11ll11ll1l['drawcredit']), array(
            1,
            $l11ll11lllllll1l1llll11ll11ll1l['title'] . '抽奖使用积分',
            'system'
        ));
        if (!is_error($lllllll11ll11l1lll11111lll11lll) && intval($l11ll11lllllll1l1llll11ll11ll1l['creditnotice']) >= 1) {
            if ($_W['account']['level'] >= 3) {
                mc_notice_credit1($this->l11l1l1lllll11l1l1l11111ll1ll1l('openid'), $lllll1111l1ll1l1lll111ll11111l1, -intval($l11ll11lllllll1l1llll11ll11ll1l['drawcredit']), $l11ll11lllllll1l1llll11ll11ll1l['title'] . '抽奖使用积分', '', '谢谢参与');
            }
        }
        $l1ll1ll1l11lll111l1lll11llllll1 = 2;
        $ll1111l111l1l1llll1l11ll11l1l11 = 0;
        switch ($l1lll1111lll11l1111l1111l1l1l11['attr']) {
            case '0':
                $l11l111l1111l11llllllll1l11llll = '谢谢参与，本次没有抽中任何奖品。';
                $l1ll1ll1l11lll111l1lll11llllll1 = 1;
                $l1llll11l1ll1ll1l1111l1l1l111l1 = 1;
                $ll1111l111l1l1llll1l11ll11l1l11 = time();
                break;
            case '1':
                $l11l111l1111l11llllllll1l11llll = '恭喜您抽中了实物奖品' . $l1lll1111lll11l1111l1111l1l1l11['name'] . '';
                $l1llll11l1ll1ll1l1111l1l1l111l1 = $ll11111l1lll1111l11ll1l1ll1l1l1;
                break;
            case '2':
                $l11l111l1111l11llllllll1l11llll = '恭喜您抽中了积分赠送，系统赠送您' . intval($l1lll1111lll11l1111l1111l1l1l11['num']) . '积分';
                $l1111l1l1ll111l1l111ll1111l1ll1 = mc_credit_update($lllll1111l1ll1l1lll111ll11111l1, 'credit1', intval($l1lll1111lll11l1111l1111l1l1l11['num']), array(
                    1,
                    $l11ll11lllllll1l1llll11ll11ll1l['title'] . '抽中了积分奖励',
                    'system'
                ));
                $l1ll1ll1l11lll111l1lll11llllll1 = 1;
                $ll1111l111l1l1llll1l11ll11l1l11 = time();
                if (!is_error($l1111l1l1ll111l1l111ll1111l1ll1) && intval($l11ll11lllllll1l1llll11ll11ll1l['creditnotice']) >= 1) {
                    if ($_W['account']['level'] >= 3) {
                        mc_notice_credit1($this->l11l1l1lllll11l1l1l11111ll1ll1l('openid'), $lllll1111l1ll1l1lll111ll11111l1, intval($l1lll1111lll11l1111l1111l1l1l11['num']), $l11ll11lllllll1l1llll11ll11ll1l['title'] . '抽中了积分奖励', '', '谢谢参与');
                    }
                }
                $l1llll11l1ll1ll1l1111l1l1l111l1 = intval($l1lll1111lll11l1111l1111l1l1l11['num']);
                break;
            case '3':
                $l11l111l1111l11llllllll1l11llll = '恭喜您抽中了双倍投票券半小时，赶紧去使用吧。';
                $l1llll11l1ll1ll1l1111l1l1l111l1 = 30;
                break;
            case '4':
                $l11l111l1111l11llllllll1l11llll = '恭喜您抽中了双倍投票券一小时，赶紧去使用吧。';
                $l1llll11l1ll1ll1l1111l1l1l111l1 = 60;
                break;
            case '5':
                $l11l111l1111l11llllllll1l11llll = '恭喜您抽中了双倍投票券二小时，赶紧去使用吧。';
                $l1llll11l1ll1ll1l1111l1l1l111l1 = 120;
                break;
            case '6':
                $l11l111l1111l11llllllll1l11llll = '恭喜您抽中了双倍投票券八小时，赶紧去使用吧。';
                $l1llll11l1ll1ll1l1111l1l1l111l1 = 480;
                break;
        }
        pdo_insert("xiaof_toupiao_draw", array(
            "sid" => $l11ll11lllllll1l1llll11ll11ll1l['id'],
            "prizeid" => $ll11111l1lll1111l11ll1l1ll1l1l1,
            "uid" => $lllll1111l1ll1l1lll111ll11111l1,
            "uname" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('nickname'),
            "avatar" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('avatar'),
            "attr" => $l1lll1111lll11l1111l1111l1l1l11['attr'],
            "uses" => $l1ll1ll1l11lll111l1lll11llllll1,
            "credit" => $l11ll11lllllll1l1llll11ll11ll1l['drawcredit'],
            "name" => $l1lll1111lll11l1111l1111l1l1l11['name'],
            "num" => $l1llll11l1ll1ll1l1111l1l1l111l1,
            "openid" => $this->l11l1l1lllll11l1l1l11111ll1ll1l('openid'),
            "ip" => ip2long(CLIENT_IP),
            "bdelete_at" => time(),
            "created_at" => time()
        ));
        exit(json_encode(error($ll11111l1lll1111l11ll1l1ll1l1l1, $l11l111l1111l11llllllll1l11llll)));
    }
    private function l111ll11llllllll111ll1l1l1l111l($l1l1l1l1111l111l11lll11l1l11ll1, $lllll1111l1ll1l1lll111ll11111l1, $l1lll1l11l111l111ll11111l111111, $ll1lll1llll1ll1ll1lll111l111ll1, $llll11ll1lll1111ll1l11111ll1l1l = false)
    {
        global $_GPC, $_W;
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        load()->func('file');
        $llll11ll1lll1111ll1l11111ll1l1l && header("Content-type: image/jpeg");
        $ll1l1l1l1lll11l11lll1l111ll1l11 = MODULE_ROOT . '/resources/font/simhei.ttf';
        $llllll1llll11l1l1ll1l11111lll1l = empty($l11ll11lllllll1l1llll11ll11ll1l['posterbg']) ? MODULE_ROOT . '/resources/image/posterbg.jpg' : tomedia($l11ll11lllllll1l1llll11ll11ll1l['posterbg']);
        $lll1ll1l11l111l11l1l11llll11ll1 = ImageCreateFromJPEG($llllll1llll11l1l1ll1l11111lll1l);
        $l1l11ll11l1lll1l1l1lll1111lll1l = ImageColorAllocate($lll1ll1l11l111l11l1l11llll11ll1, 0, 0, 0);
        $lll11lll1l1llll1ll1l11llll11l11 = getimagesize($l1lll1l11l111l111ll11111l111111);
        $l111111111111ll1ll11ll1lll1ll11 = ImageCreateFromJPEG($l1lll1l11l111l111ll11111l111111);
        imagecopyresized($lll1ll1l11l111l11l1l11llll11ll1, $l111111111111ll1ll11ll1lll1ll11, 5, 39, 0, 0, 310, 413, $lll11lll1l1llll1ll1l11llll11l11[0], $lll11lll1l1llll1ll1l11llll11l11[1]);
        if (intval($l11ll11lllllll1l1llll11ll11ll1l['qrcodetype']) == 1 && $_W['account']['level'] == 4 && $lllll1111l1ll1l1lll111ll11111l1 != 0 && $llll1l111lll1l1ll1l11ll111l1111 = pdo_fetch("SELECT * FROM " . tablename('xiaof_toupiao_rule') . " WHERE `sid` = '" . $l11ll11lllllll1l1llll11ll11ll1l['id'] . "' AND `action` = '3' limit 1")) {
            $l1lll1l1llllll1111ll11ll1ll1ll1 = str_replace(array(
                '^',
                '$',
                '*'
            ), '', $llll1l111lll1l1ll1l11ll111l1111['keyword']);
            $l1lll1l1llllll1111ll11ll1ll1ll1 = str_replace('[0-9]', $lllll1111l1ll1l1lll111ll11111l1, $l1lll1l1llllll1111ll11ll1ll1ll1);
            $l1ll1l1l111l111l11l111lll1lll11 = $this->llll111l111ll1ll11ll1ll1l1l111l($l1lll1l1llllll1111ll11ll1ll1ll1);
            $ll1lllll1l1l11l1111llll1l11l111 = ImageCreateFromJPEG($l1ll1l1l111l111l11l111lll1lll11);
        } else {
            $l1ll1l1l111l111l11l111lll1lll11 = $this->l1111llll1l1llll11l1lll1l11l1ll('getqrcode', 'xiaof_toupiao', '&url=' . urlencode($ll1lll1llll1ll1ll1lll111l111ll1) . '');
            $ll1lllll1l1l11l1111llll1l11l111 = ImageCreateFromPNG($l1ll1l1l111l111l11l111lll1lll11);
        }
        $lll111lll11ll1111llll11lll1ll11 = getimagesize($l1ll1l1l111l111l11l111lll1lll11);
        imagecopyresized($lll1ll1l11l111l11l1l11llll11ll1, $ll1lllll1l1l11l1111llll1l11l111, 17, 400, 0, 0, 126, 126, $lll111lll11ll1111llll11lll1ll11[0], $lll111lll11ll1111llll11lll1ll11[1]);
        $l1l1l1l1111l111l11lll11l1l11ll1 = mb_convert_encoding($l1l1l1l1111l111l11lll11l1l11ll1, 'html-entities', 'UTF-8');
        imagettftext($lll1ll1l11l111l11l1l11llll11ll1, 14, 0, 198, 488, $l1l11ll11l1lll1l1l1lll1111lll1l, $ll1l1l1l1lll11l11lll1l111ll1l11, $l1l1l1l1111l111l11lll11l1l11ll1);
        imagettftext($lll1ll1l11l111l11l1l11llll11ll1, 14, 0, 198, 516, $l1l11ll11l1lll1l1l1lll1111lll1l, $ll1l1l1l1lll11l11lll1l111ll1l11, $lllll1111l1ll1l1lll111ll11111l1);
        if ($llll11ll1lll1111ll1l11111ll1l1l) {
            ImageJPEG($lll1ll1l11l111l11l1l11llll11ll1);
            ImageDestroy($ll1lllll1l1l11l1111llll1l11l111);
            ImageDestroy($l111111111111ll1ll11ll1lll1ll11);
            ImageDestroy($lll1ll1l11l111l11l1l11llll11ll1);
        } else {
            $llll1ll11ll1111l1llllll1lll11l1 = 'images/xiaof/' . $l11ll11lllllll1l1llll11ll11ll1l['id'] . '/' . date('Y/m/');
            $ll1l111l1l1llll1l1111l1ll11l11l = random(30) . '.jpg';
            is_dir(ATTACHMENT_ROOT . '/' . $llll1ll11ll1111l1llllll1lll11l1) or mkdirs(ATTACHMENT_ROOT . '/' . $llll1ll11ll1111l1llllll1lll11l1);
            ImageJPEG($lll1ll1l11l111l11l1l11llll11ll1, ATTACHMENT_ROOT . '/' . $llll1ll11ll1111l1llllll1lll11l1 . $ll1l111l1l1llll1l1111l1ll11l11l);
            ImageDestroy($ll1lllll1l1l11l1111llll1l11l111);
            ImageDestroy($l111111111111ll1ll11ll1lll1ll11);
            ImageDestroy($lll1ll1l11l111l11l1l11llll11ll1);
            return $llll1ll11ll1111l1llllll1lll11l1 . $ll1l111l1l1llll1l1111l1ll11l11l;
        }
    }
    public function doMobileGetPoster()
    {
        $this->l111ll11llllllll111ll1l1l1l111l('演示', 0, MODULE_ROOT . '/resources/image/posterpic.jpg', urlencode($this->l1111llll1l1llll11l1lll1l11l1ll('index')), true);
    }
    public function doMobileGetqrcode()
    {
        global $_GPC;
        $ll1lll1llll1ll1ll1lll111l111ll1 = urldecode($_GPC['url']);
        require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
        $lll1l1l11l11lll11l1ll1lll1lll1l = "L";
        $lllll1l11l11lll111ll1ll1l11llll = "4";
        QRcode::png($ll1lll1llll1ll1ll1lll111l111ll1, false, $lll1l1l11l11lll11l1ll1lll1lll1l, $lllll1l11l11lll111ll1ll1l11llll, 2);
        exit();
    }
    private function llll111l111ll1ll11ll1ll1l1l111l($llll11lllll111l1l1l1l11111ll111)
    {
        global $_GPC, $_W;
        $ll1111ll111l111l1lllll1lll1lll1                                     = array(
            'expire_seconds' => '2592000',
            'action_name' => 'QR_SCENE',
            'action_info' => array(
                'scene' => array()
            )
        );
        $l1l1lll11l11l1llllll111111lllll                                     = intval($_W['acid']);
        $l11l1l11l1l1l11l1111llll1l11l11                                     = WeAccount::create($l1l1lll11l11l1llllll111111lllll);
        $l1l11ll1llll1l11111l11ll1l11ll1                                     = pdo_fetchcolumn("SELECT qrcid FROM " . tablename('qrcode') . " WHERE acid = :acid AND model = '1' ORDER BY qrcid DESC LIMIT 1", array(
            ':acid' => $l1l1lll11l11l1llllll111111lllll
        ));
        $ll1111ll111l111l1lllll1lll1lll1['action_info']['scene']['scene_id'] = !empty($l1l11ll1llll1l11111l11ll1l11ll1) ? ($l1l11ll1llll1l11111l11ll1l11ll1 + 1) : 100001;
        $ll1111ll111l111l1lllll1lll1lll1['expire_seconds']                   = 2592000;
        $ll1111ll111l111l1lllll1lll1lll1['action_name']                      = 'QR_SCENE';
        $l1l1llllll11l111l1ll11llll1l1ll                                     = $l11l1l11l1l1l11l1111llll1l11l11->barCodeCreateDisposable($ll1111ll111l111l1lllll1lll1lll1);
        if (!is_error($l1l1llllll11l111l1ll11llll1l1ll)) {
            $lll1lll11ll1l1111l1lll1ll11lll1 = array(
                'uniacid' => $_W['uniacid'],
                'acid' => $l1l1lll11l11l1llllll111111lllll,
                'qrcid' => $ll1111ll111l111l1lllll1lll1lll1['action_info']['scene']['scene_id'],
                'scene_str' => '',
                'keyword' => $llll11lllll111l1l1l1l11111ll111,
                'name' => '投票海报二维码',
                'model' => 1,
                'ticket' => $l1l1llllll11l111l1ll11llll1l1ll['ticket'],
                'url' => $l1l1llllll11l111l1ll11llll1l1ll['url'],
                'expire' => $l1l1llllll11l111l1ll11llll1l1ll['expire_seconds'],
                'createtime' => TIMESTAMP,
                'status' => '1',
                'type' => 'scene'
            );
            pdo_insert('qrcode', $lll1lll11ll1l1111l1lll1ll11lll1);
        }
        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($l1l1llllll11l111l1ll11llll1l1ll['ticket']);
    }
    public function doMobileUploadImg()
    {
        global $_W, $_GPC;
        if (empty($_GPC['serverid'])) {
            exit();
        }
        load()->classs('weixin.account');
        $l11l11l1111111l1l11l1lll1l11l11 = WeiXinAccount::create($_W['account']['jsauth_acid']);
        $l11111l11ll111ll1l11lll11lll11l = isset($_GPC['type']) ? $_GPC['type'] : 'image';
        $llllll11lll11lll11l1ll11l1ll1l1 = $l11l11l1111111l1l11l1lll1l11l11->downloadMedia(array(
            'type' => $l11111l11ll111ll1l11lll11lll11l,
            'media_id' => $_GPC['serverid']
        ));
        if (!is_error($llllll11lll11lll11l1ll11l1ll1l1)) {
            if ($l11111l11ll111ll1l11lll11lll11l == 'voice') {
                $l1lll1l111l11llll11lll11lll1ll1 = $this->l11ll1l11l111l1llll11lll1111ll1($llllll11lll11lll11l1ll11l1ll1l1);
                if (is_error($l1lll1l111l11llll11lll11lll1ll1)) {
                    exit(json_encode(error(102, $l1lll1l111l11llll11lll11lll1ll1['message'])));
                } else {
                    $llllll11lll11lll11l1ll11l1ll1l1 = $l1lll1l111l11llll11lll11lll1ll1;
                }
            }
            echo json_encode(error(0, $llllll11lll11lll11l1ll11l1ll1l1));
        } else {
            echo json_encode(error(102, $llllll11lll11lll11l1ll11l1ll1l1['message']));
        }
    }
    public function doMobileQiniuCallback()
    {
        echo true;
    }
    private function ll1llll1l111l11l1ll1ll1111lllll($l1ll1llll11lll1l11l11l1l111ll1l, $lll1lll1lll1l11ll1ll1l1ll1l1lll)
    {
        $l1l1llllll11l111l1ll11llll1l1ll = '';
        $llll1llll11l1l11lll11llll1ll111 = array();
        foreach ($l1ll1llll11lll1l11l11l1l111ll1l as $llll11lllll111l1l1l1l11111ll111 => $ll1lllll11lll1l11l11ll1ll1lll1l) {
            $llll1llll11l1l11lll11llll1ll111[$llll11lllll111l1l1l1l11111ll111] = $ll1lllll11lll1l11l11ll1ll1lll1l['probability'];
        }
        $l1ll11ll1l111l1lllll111lll1ll1l = array_sum($llll1llll11l1l11lll11llll1ll111);
        foreach ($llll1llll11l1l11lll11llll1ll111 as $llll11lllll111l1l1l1l11111ll111 => $ll1ll1lll1lll1l11lll1111l1ll1l1) {
            $ll1lll111l1l111l1l11l1111l1ll1l = mt_rand(1, $l1ll11ll1l111l1lllll111lll1ll1l);
            if ($ll1lll111l1l111l1l11l1111l1ll1l <= $ll1ll1lll1lll1l11lll1111l1ll1l1) {
                $l1l1llllll11l111l1ll11llll1l1ll = $llll11lllll111l1l1l1l11111ll111;
                break;
            } else {
                $l1ll11ll1l111l1lllll111lll1ll1l -= $ll1ll1lll1lll1l11lll1111l1ll1l1;
            }
        }
        if ($l1ll1llll11lll1l11l11l1l111ll1l[$l1l1llllll11l111l1ll11llll1l1ll]['attr'] == '1') {
            $l111ll1ll1l1ll1111l1lll1llll111 = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_draw') . " WHERE `sid` = :sid AND `attr` = :attr AND `num` = :num", array(
                ":sid" => $lll1lll1lll1l11ll1ll1l1ll1l1lll,
                ":attr" => 1,
                ":num" => $l1l1llllll11l111l1ll11llll1l1ll
            ));
            if ($l111ll1ll1l1ll1111l1lll1llll111 >= $l1ll1llll11lll1l11l11l1l111ll1l[$l1l1llllll11l111l1ll11llll1l1ll]['num']) {
                $l1l1llllll11l111l1ll11llll1l1ll = $this->ll1llll1l111l11l1ll1ll1111lllll($l1ll1llll11lll1l11l11l1l111ll1l, $lll1lll1lll1l11ll1ll1l1ll1l1lll);
            }
        }
        return $l1l1llllll11l111l1ll11llll1l1ll;
    }
    private function l11ll1l11l111l1llll11lll1111ll1($l1l1111l11ll1lll1ll11lll1llll11, $l11111l11ll111ll1l11lll11lll11l = 'mp3', $lll1llllllll11ll11l11l11lllllll = '')
    {
        global $_W, $_GPC;
        require_once IA_ROOT . '/framework/library/qiniu/autoload.php';
        $ll11llll11l1l11111lllll11llll11 = $this->module['config']['qiniuak'];
        $l1ll1l1111l1lllllll11l11l11111l = $this->module['config']['qiniusk'];
        $l11l1l11lll1l11l11l1l1ll11lll1l = new Qiniu\Auth($ll11llll11l1l11111lllll11llll11, $l1ll1l1111l1lllllll11l11l11111l);
        $llll11lllll111l1l1l1l11111ll111 = basename($l1l1111l11ll1lll1ll11lll1llll11);
        $llllll11lll111lllllll111l11l1l1 = array();
        if ($l11111l11ll111ll1l11lll11lll11l == 'mp3') {
            $ll1l111l1l11ll1l1ll1111l11l1lll = random(30) . '.mp3';
            $l1l1ll1111ll1111l1ll11lll1lll1l = Qiniu\base64_urlSafeEncode($this->module['config']['qiniuzone'] . ':' . $ll1l111l1l11ll1l1ll1111l11l1lll);
            $llllll11lll111lllllll111l11l1l1 = array(
                'persistentOps' => 'avthumb/mp3/ab/128k|saveas/' . $l1l1ll1111ll1111l1ll11lll1lll1l,
                'persistentNotifyUrl' => $_W['siteroot'] . "app/index.php?c=entry&do=qiniuCallback&m=xiaof_toupiao&i={$_W['uniacid']}"
            );
            empty($this->module['config']['qiniupipeline']) or $llllll11lll111lllllll111l11l1l1['persistentPipeline'] = $this->module['config']['qiniupipeline'];
        } elseif ($l11111l11ll111ll1l11lll11lll11l == 'img') {
            $ll1lll1ll1l11l111llll1l1l11l1l1 = pathinfo($l1l1111l11ll1lll1ll11lll1llll11);
            $ll1l111l1l11ll1l1ll1111l11l1lll = random(30) . '.' . $ll1lll1ll1l11l111llll1l1l11l1l1['extension'];
            $l1l1ll1111ll1111l1ll11lll1lll1l = Qiniu\base64_urlSafeEncode($this->module['config']['qiniuzone'] . ':' . $ll1l111l1l11ll1l1ll1111l11l1lll);
            $llllll11lll111lllllll111l11l1l1 = array(
                'persistentOps' => $lll1llllllll11ll11l11l11lllllll . '|saveas/' . $l1l1ll1111ll1111l1ll11lll1lll1l,
                'persistentNotifyUrl' => $_W['siteroot'] . "app/index.php?c=entry&do=qiniuCallback&m=xiaof_toupiao&i={$_W['uniacid']}"
            );
            empty($this->module['config']['qiniupipeline']) or $llllll11lll111lllllll111l11l1l1['persistentPipeline'] = $this->module['config']['qiniupipeline'];
        }
        $ll11l11l1lllll11l111l1llllll1ll = $l11l1l11lll1l11l11l1l1ll11lll1l->uploadToken($this->module['config']['qiniuzone'], null, 3600, $llllll11lll111lllllll111l11l1l1);
        $l1ll111l111l11111l1111ll11ll111 = new Qiniu\Storage\UploadManager();
        $llll1ll11ll1111l1llllll1lll11l1 = ATTACHMENT_ROOT . $l1l1111l11ll1lll1ll11lll1llll11;
        list($l111l11111lll1l1111l1ll111l11l1, $ll1lllll1llll1l111lll1llllllll1) = $l1ll111l111l11111l1111ll11ll111->putFile($ll11l11l1lllll11l111l1llllll1ll, $llll11lllll111l1l1l1l11111ll111, $llll1ll11ll1111l1llllll1lll11l1);
        if (empty($ll1lllll1llll1l111lll1llllllll1)) {
            if ($l11111l11ll111ll1l11lll11lll11l == 'mp3') {
                load()->func('file');
                file_delete($l1l1111l11ll1lll1ll11lll1llll11);
            }
            isset($ll1l111l1l11ll1l1ll1111l11l1lll) && $llll11lllll111l1l1l1l11111ll111 = $ll1l111l1l11ll1l1ll1111l11l1lll;
            return "http://{$this->module['config']['qiniudomain']}/{$llll11lllll111l1l1l1l11111ll111}";
        } else {
            return error(-1, '七牛参数配置错误');
        }
    }
    private function ll1l11111l1111111lllll11lll11l1($ll11l11ll11l1llll11ll111l1ll1l1, $l1l1ll11l1llll1l1lll1ll1lll11l1 = 500)
    {
        global $_W;
        $l1l11l1l1l11l1l11ll1ll111l111ll = '/-(240|500)/is';
        $ll11l11ll11l1llll11ll111l1ll1l1 = preg_replace($l1l11l1l1l11l1l11ll1ll111l111ll, '', $ll11l11ll11l1llll11ll111l1ll1l1);
        $ll1lll1ll1l11l111llll1l1l11l1l1 = pathinfo($ll11l11ll11l1llll11ll111l1ll1l1);
        $ll1l111ll1111l1ll111l1lll1ll111 = $ll1lll1ll1l11l111llll1l1l11l1l1['dirname'] . '/' . $ll1lll1ll1l11l111llll1l1l11l1l1['filename'] . '-' . $l1l1ll11l1llll1l1lll1ll1lll11l1 . '.' . $ll1lll1ll1l11l111llll1l1l11l1l1['extension'];
        if ($this->module['config']['imagesaveqiniu'] >= 1) {
            return $this->l11ll1l11l111l1llll11lll1111ll1($ll11l11ll11l1llll11ll111l1ll1l1, 'img', 'imageView2/2/w/' . $l1l1ll11l1llll1l1lll1ll1lll11l1);
        } elseif (!empty($_W['setting']['remote']['type']) && $_W['setting']['remote']['type'] == 2) {
            $ll111l1l11ll111ll1111l11111l1l1 = $ll1lll1ll1l11l111llll1l1l11l1l1['dirname'] . '/' . $ll1lll1ll1l11l111llll1l1l11l1l1['filename'] . '-' . $l1l1ll11l1llll1l1lll1ll1lll11l1 . '_bak.' . $ll1lll1ll1l11l111llll1l1l11l1l1['extension'];
            if (!file_exists(IA_ROOT . '/attachment/' . $ll111l1l11ll111ll1111l11111l1l1)) {
                file_image_thumb(IA_ROOT . '/attachment/' . $ll11l11ll11l1llll11ll111l1ll1l1, IA_ROOT . '/attachment/' . $ll111l1l11ll111ll1111l11111l1l1, $l1l1ll11l1llll1l1lll1ll1lll11l1);
            }
            require_once(IA_ROOT . '/framework/library/alioss/sdk.class.php');
            $llllll1l11llllllll1111ll1ll1l11 = new ALIOSS($_W['setting']['remote']['alioss']['key'], $_W['setting']['remote']['alioss']['secret'], $_W['setting']['remote']['alioss']['ossurl']);
            $l1llll1l1ll11l1l1111l1ll1l1111l = array(
                ALIOSS::OSS_FILE_UPLOAD => ATTACHMENT_ROOT . $ll111l1l11ll111ll1111l11111l1l1,
                ALIOSS::OSS_PART_SIZE => 5242880
            );
            $llllll1l11llllllll1111ll1ll1l11->create_mpu_object($_W['setting']['remote']['alioss']['bucket'], $ll1l111ll1111l1ll111l1lll1ll111, $l1llll1l1ll11l1l1111l1ll1l1111l);
        } else {
            if (!file_exists(IA_ROOT . '/attachment/' . $ll1l111ll1111l1ll111l1lll1ll111)) {
                file_image_thumb(IA_ROOT . '/attachment/' . $ll11l11ll11l1llll11ll111l1ll1l1, IA_ROOT . '/attachment/' . $ll1l111ll1111l1ll111l1lll1ll111, $l1l1ll11l1llll1l1lll1ll1lll11l1);
            }
        }
        return $ll1l111ll1111l1ll111l1lll1ll111;
    }
    public function doMobileVerifyLocation()
    {
        global $_W, $_GPC;
        if ($_W['container'] !== 'wechat' or empty($_W['openid'])) {
            exit(json_encode(error(1, '错误')));
        }
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        if (!$l11ll11lllllll1l1llll11ll11ll1l) {
            exit(json_encode(error(2, '没有获取到活动信息')));
        }
        $ll11lll111111ll1l11llllllll1l1l = $_GPC['latitude'];
        $l1l11111111l1l11111llllllll1l11 = $_GPC['longitude'];
        $ll1lll1llll1ll1ll1lll111l111ll1 = "http://api.map.baidu.com/geocoder/v2/?ak=" . $this->module['config']['baidumapak'] . "&location=" . $ll11lll111111ll1l11llllllll1l1l . "," . $l1l11111111l1l11111llllllll1l11 . "&output=json&pois=0";
        load()->func('communication');
        $lll1ll1lllll1lllll1l11l1ll11l1l = ihttp_get($ll1lll1llll1ll1ll1lll111l111ll1);
        if (!is_error($lll1ll1lllll1lllll1l11l1ll11l1l)) {
            if ($l11ll11l1111ll111lll1l1lll1l1ll = $this->l11l1l1lllll11l1l1l11111ll1ll1l('rid')) {
                $l1l111l11l1llllll1lll1l11ll1ll1 = json_decode($lll1ll1lllll1lllll1l11l1ll11l1l['content'], true);
                if ($l1l111l11l1llllll1lll1l11ll1ll1['status'] == 0) {
                    $lll1llll111l11lll1l1lll11l1l1ll = iserializer(array(
                        'province' => trim($l1l111l11l1llllll1lll1l11ll1ll1['result']['addressComponent']['province']),
                        'city' => trim($l1l111l11l1llllll1lll1l11ll1ll1['result']['addressComponent']['city']),
                        'county' => trim($l1l111l11l1llllll1lll1l11ll1ll1['result']['addressComponent']['district'])
                    ));
                    pdo_update("xiaof_relation", array(
                        'gps_city' => $lll1llll111l11lll1l1lll11l1l1ll
                    ), array(
                        "id" => $l11ll11l1111ll111lll1l1lll1l1ll
                    ));
                    exit(json_encode(error(0, '成功')));
                } else {
                    exit(json_encode(error(5, 'GPS获取地址信息失败')));
                }
            } else {
                exit(json_encode(error(3, '没有获取到用户信息')));
            }
        } else {
            exit(json_encode(error(4, 'GPS获取地址信息失败')));
        }
    }
    public function doMobileGetsms()
    {
        global $_W, $_GPC;
        if ($_W['container'] !== 'wechat' or empty($_GPC['phone']) or empty($_W['openid'])) {
            exit(json_encode(error(1, '错误')));
        }
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        if (!$l11ll11lllllll1l1llll11ll11ll1l) {
            exit(json_encode(error(2, '没有获取到活动信息')));
        }
        if ($l11ll11lllllll1l1llll11ll11ll1l['verifysms'] != 1) {
            exit(json_encode(error(10, '关闭')));
        }
        if (isset($_SESSION['verifycode'])) {
            $l11ll11ll111l1l1l111ll11l1ll11l = iunserializer($_SESSION['verifycode']);
            if (isset($l11ll11ll111l1l1l111ll11l1ll11l['created_at']) && $l11ll11ll111l1l1l111ll11l1ll11l['created_at'] + 60 >= time()) {
                exit(json_encode(error(9, '请60秒后再发送')));
            }
        }
        $l1l1lll11l11ll1l1l11l1ll1l1l111 = $_GPC['phone'];
        $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_smslog') . " where `phone` = '" . $l1l1lll11l11ll1l1l11l1ll1l1l111 . "' AND `unique_at` = '" . date("Ymd") . "'");
        if ($ll11llll1l1lll11l111lll111111ll >= $this->module['config']['smsphonenum']) {
            exit(json_encode(error(6, '该手机号今天允许的短信条数已经发完')));
        }
        $ll11llll1l1lll11l111lll111111ll = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xiaof_toupiao_smslog') . " where `ip` = '" . ip2long(CLIENT_IP) . "' AND `unique_at` = '" . date("Ymd") . "'");
        if ($ll11llll1l1lll11l111lll111111ll >= $this->module['config']['smsipnum']) {
            exit(json_encode(error(7, '该IP今天允许的短信条数已经发完')));
        }
        if (pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `uniacid` = :uniacid AND `phone` = :phone limit 1", array(
            ":uniacid" => $_W['uniacid'],
            ":phone" => $l1l1lll11l11ll1l1l11l1ll1l1l111
        ))) {
            exit(json_encode(error(8, '该手机号已在其他微信号验证')));
        }
        $l1ll11ll11l111l1l1l1ll11l1ll1ll = mt_rand(1000, 9999);
        load()->func('communication');
        $l1ll1ll11ll1ll11111ll1lllllll11 = ihttp_get('http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi?chgmobile=' . $l1l1lll11l11ll1l1l11l1ll1l1l111);
        $l1ll1ll11ll1ll11111ll1lllllll11 = @simplexml_load_string($l1ll1ll11ll1ll11111ll1lllllll11['content'], NULL, LIBXML_NOCDATA);
        $l1l111l11l1llllll1lll1l11ll1ll1 = json_decode(json_encode($l1ll1ll11ll1ll11111ll1lllllll11), true);
        if (in_array('sms', $l11ll11lllllll1l1llll11ll11ll1l['veriftype'])) {
            $ll1l1llll1lll1l111l11ll1l1ll1ll = intval($l11ll11lllllll1l1llll11ll11ll1l['citylevel']);
            switch ($ll1l1llll1lll1l111l11ll1l1ll1ll) {
                case '0':
                    $ll111l1lllll1l1l1l1ll11l11l11l1 = $l1l111l11l1llllll1lll1l11ll1ll1['province'];
                    break;
                case '1':
                    $ll111l1lllll1l1l1l1ll11l11l11l1 = $l1l111l11l1llllll1lll1l11ll1ll1['city'];
                    break;
                case '2':
                    $ll111l1lllll1l1l1l1ll11l11l11l1 = $l1l111l11l1llllll1lll1l11ll1ll1['city'];
                    break;
                default:
                    $ll111l1lllll1l1l1l1ll11l11l11l1 = $l1l111l11l1llllll1lll1l11ll1ll1['province'];
                    break;
            }
            $ll111l1lllll1l1l1l1ll11l11l11l1 = trim($ll111l1lllll1l1l1l1ll11l11l11l1);
            if (empty($ll111l1lllll1l1l1l1ll11l11l11l1)) {
                exit(json_encode(error(3, '没有获取到手机号归属地')));
            }
            if (count($l11ll11lllllll1l1llll11ll11ll1l['city']) >= 1) {
                $l1111ll11111lll1l11ll11111l11l1 = false;
                foreach ($l11ll11lllllll1l1llll11ll11ll1l['city'] as $ll111111l1ll11lll1l1l1111lll11l) {
                    if (strexists($ll111111l1ll11lll1l1l1111lll11l, $ll111l1lllll1l1l1l1ll11l11l11l1)) {
                        $l1111ll11111lll1l11ll11111l11l1 = true;
                        break;
                    }
                }
            } else {
                $l1111ll11111lll1l11ll11111l11l1 = true;
            }
            if (!$l1111ll11111lll1l11ll11111l11l1) {
                exit(json_encode(error(4, '活动仅限本地区参与投票，您的手机归属地不在本地区')));
            }
        }
        $l1l1llllll11l111l1ll11llll1l1ll = $this->ll1ll1llll1l1l11ll1lll1111ll1l1($l1ll11ll11l111l1l1l1ll11l1ll1ll, (string) $l1l1lll11l11ll1l1l11l1ll1l1l111, $l11ll11lllllll1l1llll11ll11ll1l['title']);
        if (isset($l1l1llllll11l111l1ll11llll1l1ll->result->err_code) && $l1l1llllll11l111l1ll11llll1l1ll->result->err_code == 0) {
            pdo_insert("xiaof_toupiao_smslog", array(
                "phone" => $l1l1lll11l11ll1l1l11l1ll1l1l111,
                "ip" => ip2long(CLIENT_IP),
                "unique_at" => date("Ymd"),
                "created_at" => time()
            ));
            $l1lllll1l1ll11lll1ll1l111ll1l11 = array(
                'created_at' => time(),
                'phone' => $l1l1lll11l11ll1l1l11l1ll1l1l111,
                'randcode' => $l1ll11ll11l111l1l1l1ll11l1ll1ll,
                'addrs' => array(
                    'province' => trim($l1l111l11l1llllll1lll1l11ll1ll1['province']),
                    'city' => trim($l1l111l11l1llllll1lll1l11ll1ll1['city'])
                )
            );
            $_SESSION['verifycode']          = iserializer($l1lllll1l1ll11lll1ll1l111ll1l11);
            exit(json_encode(error(0, '短信已发送成功')));
        } else {
            exit(json_encode(error(5, '短信发送失败，请稍后再试')));
        }
    }
    private function ll1ll1llll1l1l11ll1lll1111ll1l1($llll1111l11l1l1l1111l1llll11l1l, $l1l1lll11l11ll1l1l11l1ll1l1l111, $l1l11l1l1ll1ll11ll1lll11l11l111)
    {
        define("TOP_SDK_WORK_DIR", MODULE_ROOT . '/library/Alidayu/');
        define("TOP_AUTOLOADER_PATH", MODULE_ROOT . '/library/Alidayu/');
        require_once MODULE_ROOT . '/library/Alidayu/Autoloader.php';
        $l1lll11lllll1l11lll1ll1l11ll1ll            = new TopClient;
        $l1lll11lllll1l11lll1ll1l11ll1ll->appkey    = $this->module['config']['dayuak'];
        $l1lll11lllll1l11lll1ll1l11ll1ll->secretKey = $this->module['config']['dayusk'];
        $l1ll1111ll1l11111lll1l11l1ll1l1            = new AlibabaAliqinFcSmsNumSendRequest;
        $l1ll1111ll1l11111lll1l11l1ll1l1->setSmsType("normal");
        $l1ll1111ll1l11111lll1l11l1ll1l1->setSmsFreeSignName($this->module['config']['dayusign']);
        $l1ll1111ll1l11111lll1l11l1ll1l1->setSmsParam(json_encode(array(
            'code' => (string) $llll1111l11l1l1l1111l1llll11l1l,
            'product' => $this->module['config']['dayuname'],
            'item' => $l1l11l1l1ll1ll11ll1lll11l11l111
        )));
        $l1ll1111ll1l11111lll1l11l1ll1l1->setRecNum($l1l1lll11l11ll1l1l11l1ll1l1l111);
        $l1ll1111ll1l11111lll1l11l1ll1l1->setSmsTemplateCode($this->module['config']['dayumoduleid']);
        return $l1lll11lllll1l11lll1ll1l11ll1ll->execute($l1ll1111ll1l11111lll1l11l1ll1l1);
    }
    private function l1111llll1l1llll11l1lll1l11l1ll($ll11l1111ll111l11l111ll1111ll11, $l11ll1l1l1l11lll111ll11l1l1l1l1 = 'xiaof_toupiao', $l111lll1ll1l11l1llll111lllll11l = '')
    {
        global $_W, $_GPC;
        if (empty($_GPC['sid'])) {
            isset($_SESSION) or session_start();
            isset($_SESSION['sid']) && $_GPC['sid'] = $_SESSION['sid'];
        }
        return $_W['siteroot'] . "app/index.php?c=entry&do={$ll11l1111ll111l11l111ll1111ll11}&m={$l11ll1l1l1l11lll111ll11l1l1l1l1}&i={$_W['uniacid']}&sid={$_GPC['sid']}{$l111lll1ll1l11l1llll111lllll11l}&wxref=mp.weixin.qq.com#wechat_redirect";
    }
    private function l1l1111l1l111l11111ll11l1111ll1()
    {
        global $_W, $_GPC;
        if (empty($_GPC['sid'])) {
            isset($_SESSION) or session_start();
            isset($_SESSION['sid']) && $_GPC['sid'] = $_SESSION['sid'];
        }
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        $ll111llll11l11l1l11llllll1111l1 = array();
        foreach ($l11ll11lllllll1l1llll11ll11ll1l['menu'] as $ll111111l1ll11lll1l1l1111lll11l) {
            if ($ll111111l1ll11lll1l1l1111lll11l['isshow'] == 1) {
                $ll111111l1ll11lll1l1l1111lll11l['url'] = str_replace(array(
                    '{sid}',
                    '{i}'
                ), array(
                    $_GPC['sid'],
                    $_W['uniacid']
                ), $ll111111l1ll11lll1l1l1111lll11l['url']);
                $ll111llll11l11l1l11llllll1111l1[]      = $ll111111l1ll11lll1l1l1111lll11l;
            }
        }
        return $this->llllll1l1l1ll1111llll11l1llllll($ll111llll11l11l1l11llllll1111l1, 'sort');
    }
    private function llll11ll1lll11111l1ll11ll1l11l1()
    {
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        if (!$l1l1ll11llll111l1l1l1l1111lll1l = $this->l11llllll1ll1lll1ll111l1l1lll1l('mypopularity' . $l11ll11lllllll1l1llll11ll11ll1l['id'])) {
            $l11l1ll1llll11111l11lllll1l1ll1 = array(
                ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
            );
            if ($l11ll11lllllll1l1llll11ll11ll1l['verify'] == 1) {
                $l1l1lll11l111l111ll1l11l1111ll1            = ' AND `verify`=:verify';
                $l11l1ll1llll11111l11lllll1l1ll1[':verify'] = 1;
            } else {
                $l1l1lll11l111l111ll1l11l1111ll1            = ' AND `verify`!=:verify';
                $l11l1ll1llll11111l11lllll1l1ll1[':verify'] = 2;
            }
            $l1l1ll11llll111l1l1l1l1111lll1l = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao') . " WHERE `sid` = :sid " . $l1l1lll11l111l111ll1l11l1111ll1 . " ORDER BY `share` DESC LIMIT 0,6", $l11l1ll1llll11111l11lllll1l1ll1);
            $this->llllll1l111l1l1l1l1ll11l1l11lll('mypopularity' . $l11ll11lllllll1l1llll11ll11ll1l['id'], $l1l1ll11llll111l1l1l1l1111lll1l, 360);
        }
        return $l1l1ll11llll111l1l1l1l1111lll1l;
    }
    private function lll1l11lllll11lll1111lllll11111($l1lll11llll11ll1lllllllll1l1l11)
    {
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        $ll1111lllllll1lllll11ll1111l11l = trim($l11ll11lllllll1l1llll11ll11ll1l['thumblink']);
        $lll1ll1111l1lll11l1l1l1l111lll1 = explode(PHP_EOL, $ll1111lllllll1lllll11ll1111l11l);
        return isset($lll1ll1111l1lll11l1l1l1l111lll1[$l1lll11llll11ll1lllllllll1l1l11]) ? $lll1ll1111l1lll11l1l1l1l111lll1[$l1lll11llll11ll1lllllllll1l1l11] : '';
    }
    private function lll11lll1l1l11l1lll11ll1ll111l1()
    {
        if ($this->setting) {
            return $this->setting;
        }
        global $_GPC, $_W;
        isset($_SESSION) or session_start();
        $lll1lll1lll1l11ll1ll1l1ll1l1lll = intval($_GPC['sid']);
        if (empty($lll1lll1lll1l11ll1ll1l1ll1l1lll) && !empty($_SESSION['sid'])) {
            $lll1lll1lll1l11ll1ll1l1ll1l1lll = $_SESSION['sid'];
        } else {
            $_SESSION['sid'] = $lll1lll1lll1l11ll1ll1l1ll1l1lll;
        }
        if ($lllll111l1ll1l1l1l1l1ll11ll1ll1 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao_setting") . " WHERE `id` = :id", array(
            ":id" => intval($lll1lll1lll1l11ll1ll1l1ll1l1lll)
        ))) {
            $l1l111l11l1llllll1lll1l11ll1ll1               = iunserializer($lllll111l1ll1l1l1l1l1ll11ll1ll1['data']);
            $llll1l11l11111l1l111l11l1ll1111['id']         = $lllll111l1ll1l1l1l1l1ll11ll1ll1['id'];
            $llll1l11l11111l1l111l11l1ll1111['uniacid']    = $lllll111l1ll1l1l1l1l1ll11ll1ll1['uniacid'];
            $llll1l11l11111l1l111l11l1ll1111['click']      = $lllll111l1ll1l1l1l1l1ll11ll1ll1['click'];
            $llll1l11l11111l1l111l11l1ll1111['groups']     = iunserializer($lllll111l1ll1l1l1l1l1ll11ll1ll1['groups']);
            $llll1l11l11111l1l111l11l1ll1111['unfollow']   = $lllll111l1ll1l1l1l1l1ll11ll1ll1['unfollow'];
            $llll1l11l11111l1l111l11l1ll1111['bottom']     = !empty($l1l111l11l1llllll1lll1l11ll1ll1['bottom']) ? $l1l111l11l1llllll1lll1l11ll1ll1['bottom'] : $lllll111l1ll1l1l1l1l1ll11ll1ll1['bottom'];
            $llll1l11l11111l1l111l11l1ll1111['created_at'] = $lllll111l1ll1l1l1l1l1ll11ll1ll1['created_at'];
            if ($l1l111l1111l11l11llll1ll111ll11 = pdo_fetch("SELECT `qrcode` FROM " . tablename("xiaof_toupiao_acid") . " WHERE `sid` = :sid AND `acid` = :acid", array(
                ":sid" => $lllll111l1ll1l1l1l1l1ll11ll1ll1['id'],
                ":acid" => $_W['uniacid']
            ))) {
                $llll1l11l11111l1l111l11l1ll1111['accountqrcode'] = $l1l111l1111l11l11llll1ll111ll11['qrcode'];
            }
            is_array($l1l111l11l1llllll1lll1l11ll1ll1['advotepic']) or $llll1l11l11111l1l111l11l1ll1111['advotepic'] = array(
                0 => $l1l111l11l1llllll1lll1l11ll1ll1['advotepic']
            );
            $llll1l11l11111l1l111l11l1ll1111['thumblinkarr']  = explode(PHP_EOL, trim($l1l111l11l1llllll1lll1l11ll1ll1['thumblink']));
            $llll1l11l11111l1l111l11l1ll1111['advotelinkarr'] = explode(PHP_EOL, trim($l1l111l11l1llllll1lll1l11ll1ll1['advotelink']));
            $llll11lllll111l1l1l1l11111ll111                  = md5($_SERVER['HTTP_HOST'] . $this->module['name'] . 'globalsetting');
            $l1l1l11llllll111l1l1llll1l1ll11                  = cache_read("ipaddrr:" . $llll11lllll111l1l1l1l11111ll111);
            $l1l1l11llllll111l1l1llll1l1ll11 or $l1l1l11llllll111l1l1llll1l1ll11 = array(
                'openmusic' => 1,
                'openshare' => 1,
                'openfollow' => 1
            );
            $llll1l11l11111l1l111l11l1ll1111['globalsetting'] = $l1l1l11llllll111l1l1llll1l1ll11;
            $this->setting                                    = array_merge($l1l111l11l1llllll1lll1l11ll1ll1, $llll1l11l11111l1l111l11l1ll1111);
            if (!isset($_SESSION['xiaofuserinfo']) or isset($_GET['xiaofopenid'])) {
                $this->l11l1l1lllll11l1l1l11111ll1ll1l();
            }
            return $this->setting;
        }
        return $this->setting = false;
    }
    private function ll1l1l1111l1lllllll1l1l111lll1l()
    {
        global $_W;
        if (isset($_SESSION['xiaofuid']) && !empty($_SESSION['xiaofuid'])) {
            return $_SESSION['xiaofuid'];
        }
        if (empty($_W['member']['uid'])) {
            $l1l11111lllllll1ll1l1l11l11l111 = $this->l11l1l1lllll11l1l1l11111ll1ll1l('openid');
            if (empty($l1l11111lllllll1ll1l1l11l11l111)) {
                return false;
            }
            $lllll1111l1ll1l1lll111ll11111l1 = mc_openid2uid($l1l11111lllllll1ll1l1l11l11l111);
            if (empty($lllll1111l1ll1l1lll111ll11111l1) && intval($this->module['config']['openweixin']) == "1") {
                $l11llll1l1l1111l1111ll11ll1l1ll             = $this->l11l1l1lllll11l1l1l11111ll1ll1l('unionid');
                $lll1ll1l11ll11l111111ll1l1ll111             = 'SELECT uid FROM ' . tablename('mc_mapping_fans') . ' WHERE `uniacid` = :uniacid AND `unionid`=:unionid limit 1';
                $l11l1ll1llll11111l11lllll1l1ll1             = array();
                $l11l1ll1llll11111l11lllll1l1ll1[':uniacid'] = $_W['uniacid'];
                $l11l1ll1llll11111l11lllll1l1ll1[':unionid'] = $l11llll1l1l1111l1111ll11ll1l1ll;
                $lllll1111l1ll1l1lll111ll11111l1             = pdo_fetchcolumn($lll1ll1l11ll11l111111ll1l1ll111, $l11l1ll1llll11111l11lllll1l1ll1);
            }
            if (empty($lllll1111l1ll1l1lll111ll11111l1)) {
                $lll1ll1l11ll11l111111ll1l1ll111            = 'SELECT uid FROM ' . tablename('mc_mapping_fans') . ' WHERE `openid`=:openid limit 1';
                $l11l1ll1llll11111l11lllll1l1ll1            = array();
                $l11l1ll1llll11111l11lllll1l1ll1[':openid'] = $l1l11111lllllll1ll1l1l11l11l111;
                $lllll1111l1ll1l1lll111ll11111l1            = pdo_fetchcolumn($lll1ll1l11ll11l111111ll1l1ll111, $l11l1ll1llll11111l11lllll1l1ll1);
            }
        } else {
            $lllll1111l1ll1l1lll111ll11111l1 = $_W['member']['uid'];
        }
        if (!empty($lllll1111l1ll1l1lll111ll11111l1)) {
            $_SESSION['xiaofuid'] = $lllll1111l1ll1l1lll111ll11111l1;
        }
        return empty($lllll1111l1ll1l1lll111ll11111l1) ? false : $lllll1111l1ll1l1lll111ll11111l1;
    }
    private function l11l1l1lllll11l1l1l11111ll1ll1l($llll11lllll111l1l1l1l11111ll111 = null)
    {
        global $_W, $_GPC;
        if ($_W['container'] != 'wechat' or empty($_W['openid'])) {
            return false;
        }
        $l1lllll1l1ll11lll1ll1l111ll1l11 = $l1l111ll1llll11lll1l11l1l1ll111 = array();
        if (isset($_SESSION['xiaofuserinfo']) && is_serialized($_SESSION['xiaofuserinfo'])) {
            $l1l111ll1llll11lll1l11l1l1ll111 = iunserializer($_SESSION['xiaofuserinfo']);
        }
        $l11ll1l1l11lll1l1l111ll1l11ll11 = false;
        if (isset($_GPC['xiaofopenid'])) {
            $l11ll1l1l11lll1l1l111ll1l11ll11 = authcode(base64_decode(urldecode($_GPC['xiaofopenid'])), 'DECODE', $_SERVER['HTTP_HOST'] . 'xi9aofhaha' . $_W['uniacid']);
        }
        if (!is_null($llll11lllll111l1l1l1l11111ll111)) {
            if (isset($l1l111ll1llll11lll1l11l1l1ll111[$llll11lllll111l1l1l1l11111ll111]) && !empty($l1l111ll1llll11lll1l11l1l1ll111[$llll11lllll111l1l1l1l11111ll111])) {
                if ($llll11lllll111l1l1l1l11111ll111 == 'openid' && $l11ll1l1l11lll1l1l111ll1l11ll11) {
                    if ($l1l111ll1llll11lll1l11l1l1ll111[$llll11lllll111l1l1l1l11111ll111] == $l11ll1l1l11lll1l1l111ll1l11ll11) {
                        return $l1l111ll1llll11lll1l11l1l1ll111[$llll11lllll111l1l1l1l11111ll111];
                    }
                } else {
                    return $l1l111ll1llll11lll1l11l1l1ll111[$llll11lllll111l1l1l1l11111ll111];
                }
            }
        }
        if (!empty($l1l111ll1llll11lll1l11l1l1ll111['rid'])) {
            $l1l1l1lllll1l11ll11lllll1l1llll = ' `id` = :id ';
            $l11111l1ll11ll1ll11l111l111llll = array(
                ':id' => $l1l111ll1llll11lll1l11l1l1ll111['rid']
            );
        } else {
            $l1l1l1lllll1l11ll11lllll1l1llll = ' `oauth_uniacid` = :oauth_uniacid AND `uniacid` = :uniacid AND `oauth_openid` = :oauth_openid ORDER BY `id` DESC ';
            $l11111l1ll11ll1ll11l111l111llll = array(
                ":oauth_uniacid" => $_SESSION['oauth_acid'],
                ":uniacid" => $_W['uniacid'],
                ":oauth_openid" => $_SESSION['oauth_openid']
            );
        }
        if ($lllllll111ll1l1l1lll1ll1l11ll11 = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE " . $l1l1l1lllll1l11ll11lllll1l1llll . " limit 1", $l11111l1ll11ll1ll11l111l111llll)) {
            $lll1ll1l11ll11l111111ll1l1ll111 = array();
            if ($l11ll1l1l11lll1l1l111ll1l11ll11 && $l11ll1l1l11lll1l1l111ll1l11ll11 != $lllllll111ll1l1l1lll1ll1l11ll11['openid']) {
                if ($l111l1111lll1111l111lll11111l1l = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `uniacid` = :uniacid AND `oauth_uniacid` = :oauth_uniacid AND `openid` = :openid AND `oauth_openid` = '' limit 1", array(
                    ":uniacid" => $_W['uniacid'],
                    ":oauth_uniacid" => $_SESSION['oauth_acid'],
                    ":openid" => $l11ll1l1l11lll1l1l111ll1l11ll11
                ))) {
                    $lllllll111ll1l1l1lll1ll1l11ll11                 = $l111l1111lll1111l111lll11111l1l;
                    $lll1ll1l11ll11l111111ll1l1ll111['oauth_openid'] = $_SESSION['oauth_openid'];
                }
            }
            $l1lllll1l1ll11lll1ll1l111ll1l11['rid']      = $lllllll111ll1l1l1lll1ll1l11ll11['id'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['openid']   = $lllllll111ll1l1l1lll1ll1l11ll11['openid'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'] = $lllllll111ll1l1l1lll1ll1l11ll11['nickname'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['avatar']   = $lllllll111ll1l1l1lll1ll1l11ll11['avatar'];
            $l1lllll1l1ll11lll1ll1l111ll1l11['unionid']  = $lllllll111ll1l1l1lll1ll1l11ll11['unionid'];
            if (empty($lllllll111ll1l1l1lll1ll1l11ll11['city'])) {
                $lll1ll1l11ll11l111111ll1l1ll111['city'] = $lllllll111ll1l1l1lll1ll1l11ll11['city'] = pdo_fetchcolumn("SELECT `city` FROM " . tablename("xiaof_relation") . " WHERE `oauth_uniacid` = :oauth_uniacid AND `oauth_openid` = :oauth_openid AND `city` != '' limit 1", array(
                    ":oauth_uniacid" => $_SESSION['oauth_acid'],
                    ":oauth_openid" => $_SESSION['oauth_openid']
                ));
            }
            $l1lllll1l1ll11lll1ll1l111ll1l11['city']      = iunserializer($lllllll111ll1l1l1lll1ll1l11ll11['city']);
            $l1lllll1l1ll11lll1ll1l111ll1l11['gps_city']  = iunserializer($lllllll111ll1l1l1lll1ll1l11ll11['gps_city']);
            $l1lllll1l1ll11lll1ll1l111ll1l11['fans_city'] = iunserializer($lllllll111ll1l1l1lll1ll1l11ll11['fans_city']);
            if (count($lll1ll1l11ll11l111111ll1l1ll111) >= 1) {
                pdo_update("xiaof_relation", $lll1ll1l11ll11l111111ll1l1ll111, array(
                    "id" => $lllllll111ll1l1l1lll1ll1l11ll11['id']
                ));
            }
            if (is_null($llll11lllll111l1l1l1l11111ll111)) {
                $_SESSION['xiaofuserinfo'] = iserializer($l1lllll1l1ll11lll1ll1l111ll1l11);
                return $l1lllll1l1ll11lll1ll1l111ll1l11;
            } else {
                if (in_array($llll11lllll111l1l1l1l11111ll111, array(
                    'city',
                    'gps_city'
                ))) {
                    return $l1lllll1l1ll11lll1ll1l111ll1l11[$llll11lllll111l1l1l1l11111ll111];
                } elseif (!empty($l1lllll1l1ll11lll1ll1l111ll1l11[$llll11lllll111l1l1l1l11111ll111])) {
                    return $l1lllll1l1ll11lll1ll1l111ll1l11[$llll11lllll111l1l1l1l11111ll111];
                }
            }
        }
        load()->classs('weixin.account');
        $ll11lllllll11ll1111l11111ll1ll1 = $this->setting['followmode'];
        if ($ll11lllllll11ll1111l11111ll1ll1 == 1 or $ll11lllllll11ll1111l11111ll1ll1 == 3) {
            if (empty($l1lllll1l1ll11lll1ll1l111ll1l11['unionid'])) {
                $l11l11l1111111l1l11l1lll1l11l11 = WeixinAccount::create($_SESSION['oauth_acid']);
                $l11ll1l1ll11l1l1l111111l1111ll1 = $l11l11l1111111l1l11l1lll1l11l11->fetch_token();
                $ll1lll1llll1ll1ll1lll111l111ll1 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $l11ll1l1ll11l1l1l111111l1111ll1 . "&openid=" . $_SESSION['oauth_openid'] . "&lang=zh_CN";
                $l1ll1ll11ll1ll11111ll1lllllll11 = file_get_contents($ll1lll1llll1ll1ll1lll111l111ll1);
                $l1ll1ll11ll1ll11111ll1lllllll11 = substr(str_replace('\"', '"', json_encode($l1ll1ll11ll1ll11111ll1lllllll11)), 1, -1);
                $l1l111l11l1llllll1lll1l11ll1ll1 = json_decode($l1ll1ll11ll1ll11111ll1lllllll11, true);
                isset($l1l111l11l1llllll1lll1l11ll1ll1['nickname']) && $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'] = stripcslashes($l1l111l11l1llllll1lll1l11ll1ll1['nickname']);
                if (isset($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'])) {
                    if (!empty($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'])) {
                        $l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'] = rtrim($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'], '0') . 132;
                    }
                    $l1lllll1l1ll11lll1ll1l111ll1l11['avatar'] = stripcslashes($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl']);
                }
                empty($l1l111l11l1llllll1lll1l11ll1ll1['province']) or $l1lllll1l1ll11lll1ll1l111ll1l11['fans_city'] = array(
                    'province' => trim($l1l111l11l1llllll1lll1l11ll1ll1['province']),
                    'city' => trim($l1l111l11l1llllll1lll1l11ll1ll1['city'])
                );
                isset($l1l111l11l1llllll1lll1l11ll1ll1['unionid']) && $l1lllll1l1ll11lll1ll1l111ll1l11['unionid'] = $l1l111l11l1llllll1lll1l11ll1ll1['unionid'];
            }
        }
        if (empty($l1lllll1l1ll11lll1ll1l111ll1l11['openid'])) {
            if ($l11ll1l1l11lll1l1l111ll1l11ll11) {
                if ($l111l1111lll1111l111lll11111l1l = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `uniacid` = :uniacid AND `oauth_uniacid` = :oauth_uniacid AND `openid` = :openid AND `oauth_openid` = '' limit 1", array(
                    ":uniacid" => $_W['uniacid'],
                    ":oauth_uniacid" => $_SESSION['oauth_acid'],
                    ":openid" => $l11ll1l1l11lll1l1l111ll1l11ll11
                ))) {
                    $lllllll111ll1l1l1lll1ll1l11ll11             = $l111l1111lll1111l111lll11111l1l;
                    $l1lllll1l1ll11lll1ll1l111ll1l11['rid']      = $l111l1111lll1111l111lll11111l1l['id'];
                    $l1lllll1l1ll11lll1ll1l111ll1l11['openid']   = $l11ll1l1l11lll1l1l111ll1l11ll11;
                    $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'] = $lllllll111ll1l1l1lll1ll1l11ll11['nickname'];
                    $l1lllll1l1ll11lll1ll1l111ll1l11['avatar']   = $lllllll111ll1l1l1lll1ll1l11ll11['avatar'];
                    $l1lllll1l1ll11lll1ll1l111ll1l11['unionid']  = $lllllll111ll1l1l1lll1ll1l11ll11['unionid'];
                }
            }
            if (empty($l1lllll1l1ll11lll1ll1l111ll1l11['openid'])) {
                if ($_W['account']['level'] == 4) {
                    $l1lllll1l1ll11lll1ll1l111ll1l11['openid'] = $_W['openid'];
                } elseif ($_W['account']['level'] >= 3) {
                    if (!empty($l1lllll1l1ll11lll1ll1l111ll1l11['unionid'])) {
                        $l1l1lll11l111l111ll1l11l1111ll1             = ' AND `unionid` = :unionid';
                        $l11l1ll1llll11111l11lllll1l1ll1             = array(
                            ":uniacid" => $_W['uniacid']
                        );
                        $l11l1ll1llll11111l11lllll1l1ll1[':unionid'] = $l1lllll1l1ll11lll1ll1l111ll1l11['unionid'];
                        if ($ll1l1111ll1llll11111l1ll1ll1l11 = pdo_fetch("SELECT `openid`,`follow` FROM " . tablename("mc_mapping_fans") . " WHERE `uniacid` = :uniacid " . $l1l1lll11l111l111ll1l11l1111ll1 . " limit 1", $l11l1ll1llll11111l11lllll1l1ll1)) {
                            $l1lllll1l1ll11lll1ll1l111ll1l11['openid'] = $ll1l1111ll1llll11111l1ll1ll1l11['openid'];
                        }
                    }
                }
            }
        }
        if (!empty($l1lllll1l1ll11lll1ll1l111ll1l11['openid']) && (empty($l1lllll1l1ll11lll1ll1l111ll1l11['nickname']) or empty($l1lllll1l1ll11lll1ll1l111ll1l11['avatar']) or empty($l1lllll1l1ll11lll1ll1l111ll1l11['fans_city']))) {
            if ($_W['account']['level'] >= 3) {
                $l11l11l1111111l1l11l1lll1l11l11 = WeixinAccount::create($_W['acid']);
                $l11ll1l1ll11l1l1l111111l1111ll1 = $l11l11l1111111l1l11l1lll1l11l11->fetch_token();
                $ll1lll1llll1ll1ll1lll111l111ll1 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $l11ll1l1ll11l1l1l111111l1111ll1 . "&openid=" . $l1lllll1l1ll11lll1ll1l111ll1l11['openid'] . "&lang=zh_CN";
                $l1ll1ll11ll1ll11111ll1lllllll11 = file_get_contents($ll1lll1llll1ll1ll1lll111l111ll1);
                $l1ll1ll11ll1ll11111ll1lllllll11 = substr(str_replace('\"', '"', json_encode($l1ll1ll11ll1ll11111ll1lllllll11)), 1, -1);
                $l1l111l11l1llllll1lll1l11ll1ll1 = json_decode($l1ll1ll11ll1ll11111ll1lllllll11, true);
                isset($l1l111l11l1llllll1lll1l11ll1ll1['nickname']) && $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'] = stripcslashes($l1l111l11l1llllll1lll1l11ll1ll1['nickname']);
                if (isset($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'])) {
                    if (!empty($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'])) {
                        $l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'] = rtrim($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl'], '0') . 132;
                    }
                    $l1lllll1l1ll11lll1ll1l111ll1l11['avatar'] = stripcslashes($l1l111l11l1llllll1lll1l11ll1ll1['headimgurl']);
                }
                empty($l1l111l11l1llllll1lll1l11ll1ll1['province']) or $l1lllll1l1ll11lll1ll1l111ll1l11['fans_city'] = array(
                    'province' => trim($l1l111l11l1llllll1lll1l11ll1ll1['province']),
                    'city' => trim($l1l111l11l1llllll1lll1l11ll1ll1['city'])
                );
            } else {
                if ($l1l1111lll11l1l11l111llll1l1l11 = pdo_fetch("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `oauth_uniacid` = :oauth_uniacid AND `oauth_openid` = :oauth_openid AND `nickname` != '' limit 1", array(
                    ":oauth_uniacid" => $_SESSION['oauth_acid'],
                    ":oauth_openid" => $_SESSION['oauth_openid']
                ))) {
                    $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'] = $l1l1111lll11l1l11l111llll1l1l11['nickname'];
                    $l1lllll1l1ll11lll1ll1l111ll1l11['avatar']   = $l1l1111lll11l1l11l111llll1l1l11['avatar'];
                }
            }
        }
        if ($lllllll111ll1l1l1lll1ll1l11ll11) {
            $lll1ll1l11ll11l111111ll1l1ll111 = array();
            if (empty($lllllll111ll1l1l1lll1ll1l11ll11['nickname']) && !empty($l1lllll1l1ll11lll1ll1l111ll1l11['nickname'])) {
                $lll1ll1l11ll11l111111ll1l1ll111['nickname'] = $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'];
            }
            if (empty($lllllll111ll1l1l1lll1ll1l11ll11['avatar']) && !empty($l1lllll1l1ll11lll1ll1l111ll1l11['avatar'])) {
                $lll1ll1l11ll11l111111ll1l1ll111['avatar'] = $l1lllll1l1ll11lll1ll1l111ll1l11['avatar'];
            }
            if (empty($lllllll111ll1l1l1lll1ll1l11ll11['unionid']) && !empty($l1lllll1l1ll11lll1ll1l111ll1l11['unionid'])) {
                $lll1ll1l11ll11l111111ll1l1ll111['unionid'] = $l1lllll1l1ll11lll1ll1l111ll1l11['unionid'];
            }
            if (empty($lllllll111ll1l1l1lll1ll1l11ll11['openid']) && !empty($l1lllll1l1ll11lll1ll1l111ll1l11['openid'])) {
                $lll1ll1l11ll11l111111ll1l1ll111['openid'] = $l1lllll1l1ll11lll1ll1l111ll1l11['openid'];
            }
            if (empty($lllllll111ll1l1l1lll1ll1l11ll11['fans_city']) && !empty($l1lllll1l1ll11lll1ll1l111ll1l11['fans_city'])) {
                $lll1ll1l11ll11l111111ll1l1ll111['fans_city'] = iserializer($l1lllll1l1ll11lll1ll1l111ll1l11['fans_city']);
            }
            if (empty($lllllll111ll1l1l1lll1ll1l11ll11['oauth_openid'])) {
                $lll1ll1l11ll11l111111ll1l1ll111['oauth_openid'] = $_SESSION['oauth_openid'];
            }
            if (count($lll1ll1l11ll11l111111ll1l1ll111) >= 1) {
                pdo_update("xiaof_relation", $lll1ll1l11ll11l111111ll1l1ll111, array(
                    "id" => $lllllll111ll1l1l1lll1ll1l11ll11['id']
                ));
            }
        } elseif (!empty($l1lllll1l1ll11lll1ll1l111ll1l11['openid'])) {
            $lll1ll1l11ll11l111111ll1l1ll111 = array(
                "uniacid" => $_W['uniacid'],
                "oauth_uniacid" => $_SESSION['oauth_acid'],
                "openid" => $l1lllll1l1ll11lll1ll1l111ll1l11['openid'],
                "oauth_openid" => $_SESSION['oauth_openid']
            );
            empty($l1lllll1l1ll11lll1ll1l111ll1l11['nickname']) or $lll1ll1l11ll11l111111ll1l1ll111['nickname'] = $l1lllll1l1ll11lll1ll1l111ll1l11['nickname'];
            empty($l1lllll1l1ll11lll1ll1l111ll1l11['avatar']) or $lll1ll1l11ll11l111111ll1l1ll111['avatar'] = $l1lllll1l1ll11lll1ll1l111ll1l11['avatar'];
            empty($l1lllll1l1ll11lll1ll1l111ll1l11['unionid']) or $lll1ll1l11ll11l111111ll1l1ll111['unionid'] = $l1lllll1l1ll11lll1ll1l111ll1l11['unionid'];
            empty($l1lllll1l1ll11lll1ll1l111ll1l11['fans_city']) or $lll1ll1l11ll11l111111ll1l1ll111['fans_city'] = iserializer($l1lllll1l1ll11lll1ll1l111ll1l11['fans_city']);
            pdo_insert("xiaof_relation", $lll1ll1l11ll11l111111ll1l1ll111);
            $l1lllll1l1ll11lll1ll1l111ll1l11['rid'] = pdo_insertid();
        }
        $_SESSION['xiaofuserinfo'] = iserializer($l1lllll1l1ll11lll1ll1l111ll1l11);
        return is_null($llll11lllll111l1l1l1l11111ll111) ? $l1lllll1l1ll11lll1ll1l111ll1l11 : $l1lllll1l1ll11lll1ll1l111ll1l11[$llll11lllll111l1l1l1l11111ll111];
    }
    private function llllll1l1l1ll1111llll11l1llllll($l1lll11l1l1ll11lll1l1ll11l1l1ll, $ll11lll11l11l1l1111llll1lllll11, $l11111l11ll111ll1l11lll11lll11l = 'asc')
    {
        $l1111l111l111l11ll1ll1ll1ll1l1l = $l111ll1lll1l111l1lll1ll11ll11l1 = array();
        foreach ($l1lll11l1l1ll11lll1l1ll11l1l1ll as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            $l1111l111l111l11ll1ll1ll1ll1l1l[$l1lll11llll11ll1lllllllll1l1l11] = $ll111111l1ll11lll1l1l1111lll11l[$ll11lll11l11l1l1111llll1lllll11];
        }
        if ($l11111l11ll111ll1l11lll11lll11l == 'asc') {
            asort($l1111l111l111l11ll1ll1ll1ll1l1l);
        } else {
            arsort($l1111l111l111l11ll1ll1ll1ll1l1l);
        }
        reset($l1111l111l111l11ll1ll1ll1ll1l1l);
        foreach ($l1111l111l111l11ll1ll1ll1ll1l1l as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
            $l111ll1lll1l111l1lll1ll11ll11l1[$l1lll11llll11ll1lllllllll1l1l11] = $l1lll11l1l1ll11lll1l1ll11l1l1ll[$l1lll11llll11ll1lllllllll1l1l11];
        }
        return $l111ll1lll1l111l1lll1ll11ll11l1;
    }
    private function l1111l1l11l1ll1l1111l11l1l111l1($ll11ll11lll1lll111111lll1l1l1ll)
    {
        $l1lll11l1l1ll11lll1l1ll11l1l1ll = array(
            "零",
            "一",
            "双",
            "三",
            "四",
            "五",
            "六",
            "七",
            "八",
            "九",
            "十"
        );
        return $l1lll11l1l1ll11lll1l1ll11l1l1ll[$ll11ll11lll1lll111111lll1l1l1ll];
    }
    private function llllll1l111l1l1l1l1ll11l1l11lll($llll11lllll111l1l1l1l11111ll111, $l1l111l11l1llllll1lll1l11ll1ll1, $ll1l1ll1ll11l1111l11ll111ll1l1l = 60)
    {
        $l1lll11111111l11l1llllll111ll1l = 'xiaof:' . $llll11lllll111l1l1l1l11111ll111;
        $l11111lll1l111ll1ll1l11ll1lllll = array(
            'expires' => time() + $ll1l1ll1ll11l1111l11ll111ll1l1l,
            'data' => $l1l111l11l1llllll1lll1l11ll1ll1
        );
        cache_write($l1lll11111111l11l1llllll111ll1l, $l11111lll1l111ll1ll1l11ll1lllll);
    }
    private function l11llllll1ll1lll1ll111l1l1lll1l($llll11lllll111l1l1l1l11111ll111)
    {
        $l1lll11111111l11l1llllll111ll1l = 'xiaof:' . $llll11lllll111l1l1l1l11111ll111;
        if (!$l11111lll1l111ll1ll1l11ll1lllll = cache_read($l1lll11111111l11l1llllll111ll1l)) {
            return false;
        }
        if ($l11111lll1l111ll1ll1l11ll1lllll['expires'] >= time()) {
            return $l11111lll1l111ll1ll1l11ll1lllll['data'];
        }
        return false;
    }
    private function l1l11111llll11llll11l1lll1l11l1()
    {
        global $_W;
        if (!isset($_SESSION['oauth_openid'])) {
            return false;
        }
        if ($ll1l1111l1l1llll1l1l1lll11llll1 = pdo_fetchall("SELECT A.* FROM " . tablename("xiaof_relation") . " as A, (SELECT MAX(id) as maxid FROM " . tablename("xiaof_relation") . " where `oauth_uniacid` = :oauth_uniacid AND `oauth_openid` = :oauth_openid GROUP BY `uniacid`) as B WHERE A.id = B.maxid", array(
            ":oauth_uniacid" => $_SESSION['oauth_acid'],
            ":oauth_openid" => $_SESSION['oauth_openid']
        ))) {
            $l1l11111lllllll1ll1l1l11l11l111   = array();
            $l1l11111lllllll1ll1l1l11l11l111[] = $_W['openid'];
            $l11ll11lllllll1l1llll11ll11ll1l   = $this->lll11lll1l1l11l1lll11ll1ll111l1();
            foreach ($ll1l1111l1l1llll1l1l1lll11llll1 as $l1lll11llll11ll1lllllllll1l1l11 => $ll111111l1ll11lll1l1l1111lll11l) {
                $l1l11111lllllll1ll1l1l11l11l111[] = $ll111111l1ll11lll1l1l1111lll11l['openid'];
            }
            $l1l11111lllllll1ll1l1l11l11l111 = array_filter($l1l11111lllllll1ll1l1l11l11l111);
            $l1l11111lllllll1ll1l1l11l11l111 = array_unique($l1l11111lllllll1ll1l1l11l11l111);
            if ($llll1l11l11111l1l111l11l1ll1111 = pdo_fetch("SELECT * FROM " . tablename("xiaof_toupiao") . " WHERE `sid` = '" . intval($l11ll11lllllll1l1llll11ll11ll1l['id']) . "' AND `openid` IN ('" . implode("','", $l1l11111lllllll1ll1l1l11l11l111) . "') limit 1")) {
                return $llll1l11l11111l1l111l11l1ll1111;
            }
        }
        return false;
    }
    private function l1ll1l11111llll11l11111111llll1()
    {
        global $_W;
        $l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        $l1l11111lllllll1ll1l1l11l11l111 = $this->l11l1l1lllll11l1l1l11111ll1ll1l('openid');
        if (empty($l1l11111lllllll1ll1l1l11l11l111)) {
            return false;
        }
        $l11l1ll1llll11111l11lllll1l1ll1 = array(
            ":uniacid" => $_W['uniacid'],
            ":openid" => $l1l11111lllllll1ll1l1l11l11l111
        );
        $llll11111l11l1llll11ll1lll1l111 = pdo_fetch("SELECT `follow` FROM " . tablename("mc_mapping_fans") . " WHERE `uniacid` = :uniacid AND `openid` = :openid limit 1", $l11l1ll1llll11111l11lllll1l1ll1);
        if ($llll11111l11l1llll11ll1lll1l111['follow'] == 1) {
            return true;
        }
        return false;
    }
    private function l1l1l1l11111lllllll1ll1111111ll()
    {
        $l11ll11lllllll1l1llll11ll11ll1l   = $this->lll11lll1l1l11l1lll11ll1ll111l1();
        $l1l1ll1ll1lll1l11l11lllll1l11ll[] = $l11ll11lllllll1l1llll11ll11ll1l['uniacid'];
        $lll1l1ll111l11l111l11lll1111ll1   = pdo_fetchall("SELECT * FROM " . tablename('xiaof_toupiao_acid') . " WHERE `sid` = :sid", array(
            ":sid" => $l11ll11lllllll1l1llll11ll11ll1l['id']
        ));
        foreach ($lll1l1ll111l11l111l11lll1111ll1 as $ll111111l1ll11lll1l1l1111lll11l) {
            $l1l1ll1ll1lll1l11l11lllll1l11ll[] = $ll111111l1ll11lll1l1l1111lll11l['acid'];
        }
        return array_unique($l1l1ll1ll1lll1l11l11lllll1l11ll);
    }
    private function l11llll11l11l1ll1l11lll1l111111()
    {
        global $_W, $_GPC;
        $l111l1l1l1l1llll1ll111111l1ll1l = array();
        $ll11lllllll11ll1111l11111ll1ll1 = $this->setting['followmode'];
        if (!isset($_SESSION['oauth_openid'])) {
            return $l111l1l1l1l1llll1ll111111l1ll1l;
        }
        if ($ll11lllllll11ll1111l11111ll1ll1 == 1 or $ll11lllllll11ll1111l11111ll1ll1 == 3) {
            $l11llll1l1l1111l1111ll11ll1l1ll = $this->l11l1l1lllll11l1l1l11111ll1ll1l('unionid');
            if (!empty($l11llll1l1l1111l1111ll11ll1l1ll)) {
                if ($l11lll1l11ll11l111l11lll1l11lll = pdo_fetchall("SELECT `openid` FROM " . tablename("xiaof_relation") . " WHERE `oauth_uniacid` = :oauth_uniacid AND `unionid` = :unionid", array(
                    ":oauth_uniacid" => $_SESSION['oauth_acid'],
                    ":unionid" => $l11llll1l1l1111l1111ll11ll1l1ll
                ))) {
                    foreach ($l11lll1l11ll11l111l11lll1l11lll as $ll111111l1ll11lll1l1l1111lll11l) {
                        $l111l1l1l1l1llll1ll111111l1ll1l[] = $ll111111l1ll11lll1l1l1111lll11l['openid'];
                    }
                }
            }
        }
        if (count($l111l1l1l1l1llll1ll111111l1ll1l) < 1) {
            if ($l11lll1l11ll11l111l11lll1l11lll = pdo_fetchall("SELECT * FROM " . tablename("xiaof_relation") . " WHERE `oauth_uniacid` = :oauth_uniacid AND `oauth_openid` = :oauth_openid", array(
                ":oauth_uniacid" => $_SESSION['oauth_acid'],
                ":oauth_openid" => $_SESSION['oauth_openid']
            ))) {
                foreach ($l11lll1l11ll11l111l11lll1l11lll as $ll111111l1ll11lll1l1l1111lll11l) {
                    $l111l1l1l1l1llll1ll111111l1ll1l[] = $ll111111l1ll11lll1l1l1111lll11l['openid'];
                }
            }
        }
        return $l111l1l1l1l1llll1ll111111l1ll1l;
    }
    private function l1ll1l1111ll11lll1l11111lll111l()
    {
        global $_W;
        if ($l11ll11lllllll1l1llll11ll11ll1l = $this->lll11lll1l1l11l1lll11ll1ll111l1()) {
            empty($l11ll11lllllll1l1llll11ll11ll1l['binddomain']) or $_W['siteroot'] = $l11ll11lllllll1l1llll11ll11ll1l['binddomain'];
            pdo_query("UPDATE " . tablename("xiaof_toupiao_setting") . " SET `click` = click+1 WHERE `id` = '" . $l11ll11lllllll1l1llll11ll11ll1l['id'] . "'");
            if (isset($l11ll11lllllll1l1llll11ll11ll1l['checkua']) && $l11ll11lllllll1l1llll11ll11ll1l['checkua'] == 1 && $_W['container'] !== 'wechat') {
                message('错误，只允许通过微信访问，请在微信打开本链接');
            }
        }
    }
    private function ll11111l1l11111l1ll1ll11l111111($l1l11111lllllll1ll1l1l11l11l111, $l1l111l11l1llllll1lll1l11ll1ll1, $l1l1lll11l11l1llllll111111lllll = null)
    {
        global $_W;
        is_null($l1l1lll11l11l1llllll111111lllll) && $l1l1lll11l11l1llllll111111lllll = $_W['acid'];
        if ($_W['account']['level'] >= 3) {
            load()->classs('weixin.account');
            $l11l11l1111111l1l11l1lll1l11l11 = WeixinAccount::create($l1l1lll11l11l1llllll111111lllll);
            if (is_null($l11l11l1111111l1l11l1lll1l11l11)) {
                return false;
            }
            $l1l1lll1llll1lll11l11l1llll1l1l = array(
                'msgtype' => 'text',
                'text' => array(
                    'content' => urlencode($l1l111l11l1llllll1lll1l11ll1ll1)
                ),
                'touser' => trim($l1l11111lllllll1ll1l1l11l11l111)
            );
            return $l11l11l1111111l1l11l1lll1l11l11->sendCustomNotice($l1l1lll1llll1lll11l11l1llll1l1l);
        }
        return false;
    }
    public function doWebEnableservice()
    {
        $llll11lllll111l1l1l1l11111ll111           = 'ipaddrr:' . md5($_SERVER['HTTP_HOST'] . $this->module['name'] . 'xiaof');
        $ll1llll1llll1lll11l111llll1l11l['sha']    = sha1($_SERVER['HTTP_HOST'] . date("YmdH"));
        $ll1llll1llll1lll11l111llll1l11l['host']   = $_SERVER['HTTP_HOST'];
        $ll1llll1llll1lll11l111llll1l11l['module'] = $this->module['name'];
        $ll1llll1llll1lll11l111llll1l11l['action'] = 'enable';
        load()->func('communication');
        $lllllll1lll11l1l1111l1lll1l11l1 = ihttp_post('http://weixin.puzzlephp.com/service2.php', $ll1llll1llll1lll11l111llll1l11l);
        if ($lllllll1lll11l1l1111l1lll1l11l1['code'] != 200) {
            $lllllll1lll11l1l1111l1lll1l11l1 = ihttp_post('http://demo.puzzlephp.com/service2.php', $ll1llll1llll1lll11l111llll1l11l);
        }
        if ($lllllll1lll11l1l1111l1lll1l11l1['code'] == 200) {
            $ll1llllllllll111lll11l11ll11l1l = iunserializer(base64_decode($lllllll1lll11l1l1111l1lll1l11l1['content']));
            if ($ll1llllllllll111lll11l11ll11l1l['message'] == 9) {
                cache_delete($llll11lllll111l1l1l1l11111ll111);
                message('服务禁用已解除');
            } else {
                message('请先联系作者验证，再尝试解禁');
            }
        } else {
            message('请先联系作者验证，再尝试解禁1');
        }
    }
    private function l1l11l1l1111l1l111l111l11l11ll1()
    {
        global $_W;
        load()->func('communication');
        $llll11lllll111l1l1l1l11111ll111 = 'ipaddrr:' . md5($_SERVER['HTTP_HOST'] . $this->module['name'] . 'xiaof');
        if ($ll11ll1llll1lll1l11l1ll11l11lll = cache_read($llll11lllll111l1l1l1l11111ll111)) {
            $ll11ll1llll1lll1l11l1ll11l11lll = iunserializer(base64_decode($ll11ll1llll1lll1l11l1ll11l11lll));
            if ($ll11ll1llll1lll1l11l1ll11l11lll['time'] + 86400 <= time()) {
                if ($_W['isfounder']) {
                    message('服务已禁用,授权错误。<br/><br/>验证通过后<a class="btn btn-primary span3" href="' . $this->createWebUrl('enableservice') . '">点击解禁</a>');
                } else {
                    message('服务出错,请联系客服');
                }
            }
        }
        $ll1ll1l111ll11ll1l11ll111l1ll1l = IA_ROOT . '/addons/' . $this->module['name'] . '/module.php';
        $l1lllllllll1l1111l1111llll11ll1 = filemtime($ll1ll1l111ll11ll1l11ll111l1ll1l);
        $l1111lll11ll1ll1ll11l111ll11111 = time() - $l1lllllllll1l1111l1111llll11ll1;
        if (!$l1lllllllll1l1111l1111llll11ll1 or $l1111lll11ll1ll1ll11l111ll11111 > 3600) {
            $l1ll1l111llll1ll1111l1l1l1ll11l = md5($_SERVER['HTTP_HOST'] . $this->module['name'] . 'xiaofclean');
            if (!$this->l11llllll1ll1lll1ll111l1l1lll1l($l1ll1l111llll1ll1111l1l1l1ll11l)) {
                pdo_query("DELETE FROM " . tablename('xiaof_relation') . " WHERE `openid` = '' or `oauth_openid` = ''");
                cache_clean('iplongregion');
                cache_clean('ipaddr');
                $this->llllll1l111l1l1l1l1ll11l1l11lll($l1ll1l111llll1ll1111l1l1l1ll11l, 1, 86400);
            }
            $ll1llll1llll1lll11l111llll1l11l['sha']    = sha1($_SERVER['HTTP_HOST'] . date("YmdH"));
            $ll1llll1llll1lll11l111llll1l11l['host']   = $_SERVER['HTTP_HOST'];
            $ll1llll1llll1lll11l111llll1l11l['module'] = $this->module['name'];
            $lllllll1lll11l1l1111l1lll1l11l1           = ihttp_post('http://weixin.puzzlephp.com/service2.php', $ll1llll1llll1lll11l111llll1l11l);
            if ($lllllll1lll11l1l1111l1lll1l11l1['code'] != 200) {
                $lllllll1lll11l1l1111l1lll1l11l1 = ihttp_post('http://demo.puzzlephp.com/service2.php', $ll1llll1llll1lll11l111llll1l11l);
                if ($lllllll1lll11l1l1111l1lll1l11l1['code'] != 200) {
                    $ll1ll1ll11ll11l11l11ll11l11l11l = isset($ll11ll1llll1lll1l11l1ll11l11lll['time']) ? $ll11ll1llll1lll1l11l1ll11l11lll['time'] : time();
                    if (!touch($ll1ll1l111ll11ll1l11ll111l1ll1l)) {
                        $l1l111l11l1llllll1lll1l11ll1ll1 = array(
                            'time' => $ll1ll1ll11ll11l11l11ll11l11l11l,
                            'type' => 'toucherror'
                        );
                        cache_write($llll11lllll111l1l1l1l11111ll111, base64_encode(iserializer($l1l111l11l1llllll1lll1l11ll1ll1)));
                        if ($_W['isfounder']) {
                            message('检测授权不能正常运行，已记录');
                        }
                    }
                    $l1l111l11l1llllll1lll1l11ll1ll1 = array(
                        'time' => $ll1ll1ll11ll11l11l11ll11l11l11l,
                        'type' => 'linkerror'
                    );
                    cache_write($llll11lllll111l1l1l1l11111ll111, base64_encode(iserializer($l1l111l11l1llllll1lll1l11ll1ll1)));
                    if ($_W['isfounder']) {
                        message('与授权服务器通信失败');
                    }
                }
            }
            if ($ll11ll1llll1lll1l11l1ll11l11lll = cache_read($llll11lllll111l1l1l1l11111ll111)) {
                $ll11ll1llll1lll1l11l1ll11l11lll = iunserializer(base64_decode($ll11ll1llll1lll1l11l1ll11l11lll));
                if ($ll11ll1llll1lll1l11l1ll11l11lll['type'] == 'linkerror') {
                    cache_delete($llll11lllll111l1l1l1l11111ll111);
                }
            }
            $ll1llllllllll111lll11l11ll11l1l = iunserializer(base64_decode($lllllll1lll11l1l1111l1lll1l11l1['content']));
            switch ($ll1llllllllll111lll11l11ll11l1l['errno']) {
                case '101':
                    $ll1ll1ll11ll11l11l11ll11l11l11l = isset($ll11ll1llll1lll1l11l1ll11l11lll['time']) ? $ll11ll1llll1lll1l11l1ll11l11lll['time'] : time();
                    if (!touch($ll1ll1l111ll11ll1l11ll111l1ll1l)) {
                        $l1l111l11l1llllll1lll1l11ll1ll1 = array(
                            'time' => $ll1ll1ll11ll11l11l11ll11l11l11l,
                            'type' => 'toucherror'
                        );
                        cache_write($llll11lllll111l1l1l1l11111ll111, base64_encode(iserializer($l1l111l11l1llllll1lll1l11ll1ll1)));
                        if ($_W['isfounder']) {
                            message('检测授权不能正常运行，已记录');
                        }
                    }
                    $l1l111l11l1llllll1lll1l11ll1ll1 = array(
                        'time' => $ll1ll1ll11ll11l11l11ll11l11l11l,
                        'type' => 'iperror'
                    );
                    cache_write($llll11lllll111l1l1l1l11111ll111, base64_encode(iserializer($l1l111l11l1llllll1lll1l11ll1ll1)));
                    if ($_W['isfounder']) {
                        message($ll1llllllllll111lll11l11ll11l1l['message']);
                    }
                    break;
                case '102':
                    $l1l111l11l1llllll1lll1l11ll1ll1 = array(
                        'time' => 0,
                        'type' => 'hosterror'
                    );
                    cache_write($llll11lllll111l1l1l1l11111ll111, base64_encode(iserializer($l1l111l11l1llllll1lll1l11ll1ll1)));
                    if ($_W['isfounder']) {
                        message($ll1llllllllll111lll11l11ll11l1l['message']);
                    }
                    break;
                case '103':
                    $ll1ll1ll11ll11l11l11ll11l11l11l = isset($ll11ll1llll1lll1l11l1ll11l11lll['time']) ? $ll11ll1llll1lll1l11l1ll11l11lll['time'] : time();
                    if (!touch($ll1ll1l111ll11ll1l11ll111l1ll1l)) {
                        $l1l111l11l1llllll1lll1l11ll1ll1 = array(
                            'time' => $ll1ll1ll11ll11l11l11ll11l11l11l,
                            'type' => 'toucherror'
                        );
                        cache_write($llll11lllll111l1l1l1l11111ll111, base64_encode(iserializer($l1l111l11l1llllll1lll1l11ll1ll1)));
                        if ($_W['isfounder']) {
                            message('检测授权不能正常运行，已记录');
                        }
                    }
                    if ($_W['isfounder']) {
                        message($ll1llllllllll111lll11l11ll11l1l['message']);
                    }
                    break;
            }
            if (!touch($ll1ll1l111ll11ll1l11ll111l1ll1l)) {
                $ll1ll1ll11ll11l11l11ll11l11l11l = isset($ll11ll1llll1lll1l11l1ll11l11lll['time']) ? $ll11ll1llll1lll1l11l1ll11l11lll['time'] : time();
                $l1l111l11l1llllll1lll1l11ll1ll1 = array(
                    'time' => $ll1ll1ll11ll11l11l11ll11l11l11l,
                    'type' => 'toucherror'
                );
                cache_write($llll11lllll111l1l1l1l11111ll111, base64_encode(iserializer($l1l111l11l1llllll1lll1l11ll1ll1)));
                if ($_W['isfounder']) {
                    message('检测授权不能正常运行，已记录');
                }
            }
        }
    }
}