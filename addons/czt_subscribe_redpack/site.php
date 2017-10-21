<?php
defined('IN_IA') or exit('Access Denied');
class Czt_subscribe_redpackModuleSite extends WeModuleSite
{
    public function doWebApi()
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $path = ATTACHMENT_ROOT . '/czt_subscribe_redpack/cert';
            if (!file_exists($path)) {
                load()->func('file');
                mkdirs($path);
            }
            $r = true;
            if (!empty($_GPC['cert'])) {
                $ret = file_put_contents($path . '/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
                $r   = $r && $ret;
            }
            if (!empty($_GPC['key'])) {
                $ret = file_put_contents($path . '/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
                $r   = $r && $ret;
            }
            if (!empty($_GPC['ca'])) {
                $ret = file_put_contents($path . '/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
                $r   = $r && $ret;
            }
            if (!$r) {
                message('证书保存失败, 请保证 ' . $path . ' 目录可写');
            }
            $input             = array_elements(array(
                'appid',
                'secret',
                'mchid',
                'password'
            ), $_GPC);
            $input['appid']    = trim($input['appid']);
            $input['secret']   = trim($input['secret']);
            $input['mchid']    = trim($input['mchid']);
            $input['password'] = trim($input['password']);
            $setting           = $this->module['config'];
            $setting['api']    = $input;
            if ($this->saveSettings($setting)) {
                message('保存参数成功', 'refresh');
            }
        }
        $config = $this->module['config']['api'];
        include $this->template('api');
    }
    public function doWebRecord()
    {
        global $_W, $_GPC;
        if ($_GPC['op'] == 'empty') {
            pdo_delete('czt_subscribe_redpack_records', array(
                'uniacid' => $_W['uniacid']
            ));
            message('删除数据成功', $this->createWebUrl('record'), 'success');
        }
        $pageindex = max(intval($_GPC['page']), 1);
        $pagesize  = 20;
        $where     = '';
        if (!empty($_GPC['openid'])) {
            $where .= ' and openid=\'' . $_GPC['openid'] . '\' ';
        }
        if (!empty($_GPC['status'])) {
            $where .= ' and status=' . intval($_GPC['status']);
        }
        $sql   = 'SELECT COUNT(*) FROM ' . tablename('czt_subscribe_redpack_records') . ' where uniacid = :uniacid' . $where;
        $total = pdo_fetchcolumn($sql, array(
            ':uniacid' => $_W['uniacid']
        ));
        $pager = pagination($total, $pageindex, $pagesize);
        $sql   = 'SELECT * FROM ' . tablename('czt_subscribe_redpack_records') . " where uniacid = :uniacid" . $where . " ORDER BY id asc LIMIT " . (($pageindex - 1) * $pagesize) . ',' . $pagesize;
        $list  = pdo_fetchall($sql, array(
            ':uniacid' => $_W['uniacid']
        ), 'id');
        include $this->template('records');
    }
    public function doWebHelp()
    {
        include $this->template('help');
    }
    public function doMobileQiniu_callback()
    {
        global $_W, $_GPC;
        $callbackBody = @file_get_contents('php://input');
        $callbackBody = @json_decode($callbackBody, true);
        $id           = $_GPC['id'];
        isetcookie('qiniu_stat', base64_encode($_W['config']['setting']['authkey'] . TIMESTAMP . $id), 3600 * 24, true);
        pdo_query('update ' . tablename('czt_subscribe_redpack_records') . ' set `create_time`=' . TIMESTAMP . ' where id=' . $id);
    }
    public function doWebActivity()
    {
        global $_W, $_GPC;
        if ($_W['ispost']) {
            $input                  = array_elements(array(
                'limit_area',
                'title',
                'limit_amount',
                'provider',
                'wish',
                'remark',
                'fee',
                'time',
                'reply',
                'userlimit',
                'err_tip',
                'map_key'
            ), $_GPC);
            $input['time']['start'] = strtotime($input['time']['start'] . ':00');
            $input['time']['end']   = strtotime($input['time']['end'] . ':59');
            if (!empty($input['reply'])) {
                $input['reply'] = htmlspecialchars_decode($input['reply']);
            }
            $input['limit_type'] = in_array(intval($_GPC['limit_type']), array(
                1,
                2,
                3
            )) ? intval($_GPC['limit_type']) : 1;
            $setting             = $this->module['config'];
            $setting['activity'] = $input;
            if ($this->saveSettings($setting)) {
                message('保存红包设置成功', 'refresh');
            }
        }
        $activity = $this->module['config']['activity'];
        if (empty($activity)) {
            $activity                    = array();
            $activity['fee']['downline'] = '1';
            $activity['fee']['upline']   = '1';
        }
        if (!is_array($activity['fee'])) {
            $fee                         = $activity['fee'];
            $activity['fee']             = array();
            $activity['fee']['downline'] = $fee;
            $activity['fee']['upline']   = $fee;
        }
        if (!is_array($activity['time'])) {
            $activity['time'] = array(
                'start' => TIMESTAMP,
                'end' => TIMESTAMP + 3600 * 24
            );
        }
        $activity['time']['start'] = date('Y-m-d H:i', $activity['time']['start']);
        $activity['time']['end']   = date('Y-m-d H:i', $activity['time']['end']);
        include $this->template('activity');
    }
    public function doMobileLimit_area()
    {
        global $_W, $_GPC;
        $activity = $this->module['config']['activity'];
        $api      = $this->module['config']['api'];
        if ($_W['isajax']) {
            if (empty($_GPC['latitude']) || empty($_GPC['longitude'])) {
                die(json_encode(array(
                    'status' => 1,
                    'msg' => 'params error'
                )));
            }
            load()->func('communication');
            $result = ihttp_get("http://apis.map.qq.com/ws/geocoder/v1/?location={$_GPC['latitude']},{$_GPC['longitude']}&output=json&key=CAVBZ-ARQRX-ZY64Z-TYC6P-E65VE-BRBOH&coord_type=1");
            if ($result['code'] == 200) {
                $content = json_decode($result['content'], true);
                if ($content['status'] == 0) {
                    $limit_area = explode('|', $activity['limit_area']);
                    $result     = false;
                    foreach ($limit_area as $value) {
                        if (!(empty($content['result']['address_component']['province'])) && strpos($content['result']['address_component']['province'], $value) !== false) {
                            $result = true;
                            break;
                        }
                        if (!(empty($content['result']['address_component']['city'])) && strpos($content['result']['address_component']['city'], $value) !== false) {
                            $result = true;
                            break;
                        }
                        if (!(empty($content['result']['address_component']['city'])) && strpos($content['result']['address_component']['district'], $value) !== false) {
                            $result = true;
                            break;
                        }
                    }
                    if (!$result) {
                        die(json_encode(array(
                            'status' => 1,
                            'msg' => '抱歉，您不符合参加本次活动的条件'
                        )));
                    } else {
                        if (!empty($_GPC['openid'])) {
                            $id = $this->creat_record($_GPC['openid']);
                            if (!empty($id)) {
                                $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('redpack', array(
                                    'id' => $id,
                                    'openid' => empty($_GPC['oauth_openid']) ? $_GPC['openid'] : $_GPC['oauth_openid']
                                ), false);
                                die(json_encode(array(
                                    'status' => 0,
                                    'msg' => 'ok',
                                    'url' => $url
                                )));
                            } else {
                                die(json_encode(array(
                                    'status' => 1,
                                    'msg' => 'creat_record error'
                                )));
                            }
                        } else {
                            die(json_encode(array(
                                'status' => 1,
                                'msg' => 'openid not found'
                            )));
                        }
                    }
                } else {
                    die(json_encode(array(
                        'status' => 1,
                        'msg' => 'api error'
                    )));
                }
            } else {
                die(json_encode(array(
                    'status' => 1,
                    'msg' => 'ihttp_get error'
                )));
            }
            die();
        }
        $openid = empty($_GPC['openid']) ? $_W['openid'] : $_GPC['openid'];
        if (empty($activity['limit_area'])) {
            message('区域未设置');
        }
        if (!empty($openid)) {
            if ($_W['account']['level'] >= 3 && $_W['fans']['follow'] != 1) {
                message('未关注公众号');
            }
            if ($_W['account']['key'] != $api['appid']) {
                $oauth_openid = $this->oauth_openid();
            }
            if ($activity['limit_type'] == 2) {
                load()->func('communication');
                $ret = ihttp_get("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=" . $_W['clientip']);
                if ($ret['code'] == 200) {
                    $r          = json_decode($ret['content'], true);
                    $limit_area = explode('|', $activity['limit_area']);
                    $result     = false;
                    foreach ($limit_area as $value) {
                        if (strpos($r['province'], $value) === false && strpos($r['city'], $value) === false) {
                            $result = false;
                        } else {
                            $result = true;
                            break;
                        }
                    }
                    if ($result) {
                        $id = $this->creat_record($openid);
                        if (!empty($id)) {
                            $this->redpack(empty($oauth_openid) ? $openid : $oauth_openid, $id);
                        } else {
                            message('creat_record error');
                        }
                    } else {
                        message('抱歉，您不在本次活动参与范围内');
                    }
                } else {
                    message('ihttp_get error');
                }
            } else if ($activity['limit_type'] == 3) {
                include $this->template('limit_area');
            }
        } else {
            message('openid not found');
        }
    }
    public function doMobileGps_limit()
    {
        global $_W, $_GPC;
        $activity = $this->module['config']['activity'];
        if ($_W['isajax']) {
            if (empty($_GPC['latitude']) || empty($_GPC['longitude'])) {
                die(json_encode(array(
                    'status' => 1,
                    'msg' => 'params error'
                )));
            }
            if (empty($activity['map_key'])) {
                die(json_encode(array(
                    'status' => 1,
                    'msg' => 'map key not exists'
                )));
            }
            load()->func('communication');
            $result = ihttp_get("http://apis.map.qq.com/ws/geocoder/v1/?location={$_GPC['latitude']},{$_GPC['longitude']}&output=json&key={$activity['map_key']}&coord_type=1");
            if ($result['code'] == 200) {
                $content = json_decode($result['content'], true);
                if ($content['status'] == 0) {
                    $limit_area = explode('|', $activity['limit_area']);
                    $result     = false;
                    foreach ($limit_area as $value) {
                        if (!(empty($content['result']['address_component']['province'])) && strpos($content['result']['address_component']['province'], $value) !== false) {
                            $result = true;
                            break;
                        }
                        if (!(empty($content['result']['address_component']['city'])) && strpos($content['result']['address_component']['city'], $value) !== false) {
                            $result = true;
                            break;
                        }
                        if (!(empty($content['result']['address_component']['city'])) && strpos($content['result']['address_component']['district'], $value) !== false) {
                            $result = true;
                            break;
                        }
                    }
                    if (!$result) {
                        die(json_encode(array(
                            'status' => 1,
                            'msg' => '抱歉，您不符合参加本次活动的条件'
                        )));
                    } else {
                        if (!empty($_GPC['openid'])) {
                            $_SESSION['latitude']  = $_GPC['latitude'];
                            $_SESSION['longitude'] = $_GPC['longitude'];
                            $url                   = $_W['siteroot'] . 'app/' . $this->createMobileUrl('redpack', array(
                                'openid' => $_GPC['openid'],
                                'token' => md5($_SESSION['latitude'] . $_SESSION['longitude'] . $_W['config']['setting']['authkey'])
                            ), false);
                            die(json_encode(array(
                                'status' => 0,
                                'msg' => 'ok',
                                'url' => $url
                            )));
                        } else {
                            die(json_encode(array(
                                'status' => 1,
                                'msg' => 'openid not found'
                            )));
                        }
                    }
                } else {
                    die(json_encode(array(
                        'status' => 1,
                        'msg' => !empty($content['message']) ? $content['message'] : 'api error'
                    )));
                }
            } else {
                die(json_encode(array(
                    'status' => 1,
                    'msg' => 'ihttp_get error'
                )));
            }
        }
    }
    public function ip_limit($limit_area)
    {
        global $_W, $_GPC;
        load()->func('communication');
        $ret = ihttp_get("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=" . $_W['clientip']);
        if ($ret['code'] == 200) {
            $r          = json_decode($ret['content'], true);
            $limit_area = explode('|', $limit_area);
            $result     = false;
            foreach ($limit_area as $value) {
                if (strpos($r['province'], $value) === false && strpos($r['city'], $value) === false) {
                    $result = false;
                } else {
                    $result = true;
                    break;
                }
            }
            if ($result) {
                return true;
            } else {
                message('抱歉，您不在本次活动参与范围内');
            }
        } else {
            message('ihttp_get error');
        }
    }
    public function doMobileRedpack()
    {
        global $_W, $_GPC;
        $activity = $this->module['config']['activity'];
        $api      = $this->module['config']['api'];
        if (TIMESTAMP > $activity['time']['start'] && TIMESTAMP < $activity['time']['end']) {
            if (!empty($activity['limit_amount']) && $activity['limit_amount'] > 0) {
                $amount = pdo_fetchcolumn('select sum(fee)  from ' . tablename('czt_subscribe_redpack_records') . ' where uniacid=' . $_W['uniacid']);
                if (floatval($amount) > floatval($activity['limit_amount'])) {
                    message('活动已经结束了！');
                }
            }
        } else {
            if (TIMESTAMP < $activity['time']['start']) {
                message('活动未开始！');
            } else {
                message('活动已经结束了！');
            }
        }
        $userlimit = intval($activity['userlimit']);
        if ($userlimit > 0) {
            $today = getCurTimeRange();
            $num   = pdo_fetchcolumn('select count(*)  from ' . tablename('czt_subscribe_redpack_records') . ' where uniacid=' . $_W['uniacid'] . ' and status=1 and create_time >= ' . $today['s'] . ' and create_time <= ' . $today['e']);
            if ($num >= $userlimit) {
                message('抱歉今天领取名额已满，请明天再来吧！');
            }
        }
        if ($_W['account']['key'] != $api['appid']) {
            if (!empty($_GPC['openid'])) {
                load()->classs('weixin.account');
                $accObj = new WeixinAccount($_W['account']);
                $fan    = $accObj->fansQueryInfo($_GPC['openid']);
                if (!is_error($fan)) {
                    if ($fan['subscribe'] == 1) {
                        if (!empty($activity['limit_area']) && empty($_GPC['token'])) {
                            if ($activity['limit_type'] == 1) {
                                if (empty($fan['province']) || empty($fan['city'])) {
                                    message('用户信息不完整');
                                }
                                $limit_area = explode('|', $activity['limit_area']);
                                $result     = false;
                                foreach ($limit_area as $value) {
                                    if (strpos($fan['province'], $value) === false && strpos($fan['city'], $value) === false) {
                                        $result = false;
                                    } else {
                                        $result = true;
                                        break;
                                    }
                                }
                                if (!$result) {
                                    message('抱歉，您不在本次活动参与范围内');
                                }
                            } else if ($activity['limit_type'] == 2) {
                                $this->ip_limit($activity['limit_area']);
                            } else if ($activity['limit_type'] == 3) {
                                include $this->template('limit_area');
                                die();
                            }
                        }
                        if ((!empty($_GPC['token']) && !empty($_SESSION['latitude']) && !empty($_SESSION['longitude']) && $_GPC['token'] == md5($_SESSION['latitude'] . $_SESSION['longitude'] . $_W['config']['setting']['authkey'])) || empty($_GPC['token'])) {
                            $oauth_openid = $this->oauth_openid();
                            $this->redpack($_GPC['openid'], $oauth_openid);
                        }
                    } else {
                        message('未关注公众号');
                    }
                } else {
                    message('获取用户信息出错');
                }
            }
        } else {
            if (!empty($_W['openid']) && !empty($_GPC['openid']) && $_W['openid'] == $_GPC['openid'] && !empty($activity['limit_area'])) {
                if ($_W['fans']['follow'] != 1) {
                    message('未关注公众号');
                }
                if ($activity['limit_type'] == 2) {
                    if ($this->ip_limit($activity['limit_area'])) {
                        $this->redpack($_W['openid']);
                    }
                } else if ($activity['limit_type'] == 3) {
                    if (!empty($_GPC['token']) && !empty($_SESSION['latitude']) && !empty($_SESSION['longitude']) && $_GPC['token'] == md5($_SESSION['latitude'] . $_SESSION['longitude'] . $_W['config']['setting']['authkey'])) {
                        $this->redpack($_W['openid']);
                    } else {
                        include $this->template('limit_area');
                    }
                }
            }
        }
    }
    public function oauth_openid()
    {
        global $_W, $_GPC;
        $openid = $_SESSION['czt_oauth'][$_W['uniacid']]['openid'];
        if (empty($openid) || empty($_SESSION['czt_oauth'][$_W['uniacid']]['time']) || TIMESTAMP - $_SESSION['czt_oauth'][$_W['uniacid']]['time'] > 3600) {
            load()->classs('weixin.account');
            $api    = $this->module['config']['api'];
            $accObj = new WeixinAccount(array(
                'acid' => 1,
                'key' => $api['appid'],
                'secret' => $api['secret']
            ));
            $ret    = $accObj->getOauthInfo();
            if (!is_error($ret)) {
                $openid                                        = $_SESSION['czt_oauth'][$_W['uniacid']]['openid'] = $ret['openid'];
                $_SESSION['czt_oauth'][$_W['uniacid']]['time'] = TIMESTAMP;
            } else {
                message($ret['message']);
            }
        }
        return $openid;
    }
    public function redpack($openid, $oauth_openid = '')
    {
        global $_W;
        $activity         = $this->module['config']['activity'];
        $api              = $this->module['config']['api'];
        $pars             = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if (empty($oauth_openid)) {
            $pars[':openid'] = $openid;
            $record          = pdo_fetch("SELECT * FROM " . tablename('czt_subscribe_redpack_records') . " WHERE `uniacid`=:uniacid AND `openid`=:openid", $pars);
        } else {
            $pars[':oauth_openid'] = $oauth_openid;
            $record                = pdo_fetch("SELECT * FROM " . tablename('czt_subscribe_redpack_records') . " WHERE `uniacid`=:uniacid AND `oauth_openid`=:oauth_openid", $pars);
        }
        if (empty($record)) {
            $fee                  = rand($activity['fee']['downline'] * 100, $activity['fee']['upline'] * 100);
            $data                 = array();
            $data['uniacid']      = $_W['uniacid'];
            $data['openid']       = $openid;
            $data['create_time']  = TIMESTAMP;
            $data['oauth_openid'] = $oauth_openid;
            $data['fee']          = sprintf('%.2f', $fee / 100);
            if (pdo_insert('czt_subscribe_redpack_records', $data)) {
                $record           = $data;
                $record['status'] = 0;
                $record['id']     = pdo_insertid();
            } else {
                die('pdo_insert error');
            }
        }
        if ($record['status'] == 1) {
            message('您已经领过红包了');
        } else {
            $params = array(
                'openid' => empty($oauth_openid) ? $openid : $oauth_openid,
                'fee' => $record['fee'],
                'desc' => '关注送红包',
                'trade_no' => $api['mchid'] . date('Ymd') . sprintf('%010d', $record['id']),
                'wish' => $activity['wish'],
                'provider' => $activity['provider'],
                'title' => $activity['title']
            );
            $path   = ATTACHMENT_ROOT . '/czt_subscribe_redpack/cert';
            $wechat = array(
                'appid' => $api['appid'],
                'mchid' => $api['mchid'],
                'signkey' => $api['password'],
                'rootca' => $path . '/rootca.pem.' . $_W['uniacid'],
                'apiclient_cert' => $path . '/apiclient_cert.pem.' . $_W['uniacid'],
                'apiclient_key' => $path . '/apiclient_key.pem.' . $_W['uniacid']
            );
            $ret    = wx_redpack($params, $wechat);
            if (is_error($ret)) {
                @file_put_contents(MODULE_ROOT . '/log.txt', date('Y-m-d H:i:s') . ':' . $ret['message'] . PHP_EOL, FILE_APPEND);
                if (empty($activity['err_tip'])) {
                    message('出错，未能领到红包:(');
                } else {
                    message($activity['err_tip']);
                }
            } else {
                pdo_update('czt_subscribe_redpack_records', array(
                    'status' => 1,
                    'success_time' => TIMESTAMP
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'id' => $record['id']
                ));
                include $this->template('redpack');
            }
        }
    }
}
function wx_pay($params, $wechat)
{
    global $_W, $_GPC;
    $url                      = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    $pars                     = array();
    $pars['mch_appid']        = $wechat['appid'];
    $pars['nonce_str']        = random(32);
    $pars['partner_trade_no'] = $params['trade_no'];
    $pars['mchid']            = $wechat['mchid'];
    $pars['openid']           = $params['openid'];
    $pars['check_name']       = 'NO_CHECK';
    $pars['amount']           = $params['fee'] * 100;
    $pars['desc']             = $params['desc'];
    $pars['spbill_create_ip'] = CLIENT_IP;
    ksort($pars, SORT_STRING);
    $string1 = '';
    foreach ($pars as $k => $v) {
        $string1 .= "{$k}={$v}&";
    }
    $string1 .= "key={$wechat['signkey']}";
    $pars['sign']              = strtoupper(md5($string1));
    $xml                       = array2xml($pars);
    $extras                    = array();
    $extras['CURLOPT_CAINFO']  = $wechat['rootca'];
    $extras['CURLOPT_SSLCERT'] = $wechat['apiclient_cert'];
    $extras['CURLOPT_SSLKEY']  = $wechat['apiclient_key'];
    load()->func('communication');
    $procResult = null;
    $resp       = ihttp_request($url, $xml, $extras);
    if (is_error($resp)) {
        $procResult = $resp;
    } else {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
        $dom = new \DOMDocument();
        if ($dom->loadXML($xml)) {
            $xpath = new \DOMXPath($dom);
            $code  = $xpath->evaluate('string(//xml/return_code)');
            $ret   = $xpath->evaluate('string(//xml/result_code)');
            if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                $payment_no   = $xpath->evaluate('string(//xml/payment_no)');
                $payment_time = $xpath->evaluate('string(//xml/payment_time)');
                $procResult   = array(
                    'payment_no' => $payment_no,
                    'payment_time' => strtotime($payment_time)
                );
            } else {
                $error      = $xpath->evaluate('string(//xml/err_code_des)');
                $procResult = error(-2, $error);
            }
        } else {
            $procResult = error(-1, 'error response');
        }
    }
    return $procResult;
}
function wx_redpack($params, $wechat)
{
    global $_W, $_GPC;
    $url                  = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
    $pars                 = array();
    $pars['nonce_str']    = random(32);
    $pars['mch_billno']   = $params['trade_no'];
    $pars['mch_id']       = $wechat['mchid'];
    $pars['wxappid']      = $wechat['appid'];
    $pars['send_name']    = $params['provider'];
    $pars['re_openid']    = $params['openid'];
    $pars['total_amount'] = $params['fee'] * 100;
    $pars['total_num']    = 1;
    $pars['wishing']      = $params['wish'];
    $pars['client_ip']    = CLIENT_IP;
    $pars['act_name']     = $params['title'];
    $pars['remark']       = '关注送红包';
    ksort($pars, SORT_STRING);
    $string1 = '';
    foreach ($pars as $k => $v) {
        $string1 .= "{$k}={$v}&";
    }
    $string1 .= "key={$wechat['signkey']}";
    $pars['sign']              = strtoupper(md5($string1));
    $xml                       = array2xml($pars);
    $extras                    = array();
    $extras['CURLOPT_CAINFO']  = $wechat['rootca'];
    $extras['CURLOPT_SSLCERT'] = $wechat['apiclient_cert'];
    $extras['CURLOPT_SSLKEY']  = $wechat['apiclient_key'];
    load()->func('communication');
    $procResult = null;
    $resp       = ihttp_request($url, $xml, $extras);
    if (is_error($resp)) {
        $procResult = $resp;
    } else {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
        $dom = new \DOMDocument();
        if ($dom->loadXML($xml)) {
            $xpath = new \DOMXPath($dom);
            $code  = $xpath->evaluate('string(//xml/return_code)');
            $ret   = $xpath->evaluate('string(//xml/result_code)');
            if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                $payment_no   = $xpath->evaluate('string(//xml/payment_no)');
                $payment_time = $xpath->evaluate('string(//xml/payment_time)');
                $procResult   = array(
                    'payment_no' => $payment_no,
                    'payment_time' => strtotime($payment_time)
                );
            } else {
                $error      = $xpath->evaluate('string(//xml/err_code_des)');
                $procResult = error(-2, $error);
            }
        } else {
            $procResult = error(-1, 'error response');
        }
    }
    return $procResult;
}
function getCurTimeRange()
{
    $y     = date("Y");
    $m     = date("m");
    $d     = date("d");
    $start = mktime(0, 0, 0, $m, $d, $y);
    $end   = mktime(23, 59, 59, $m, $d, $y);
    return array(
        's' => $start,
        'e' => $end
    );
}

?>