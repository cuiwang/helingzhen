<?php
defined('IN_IA') or exit('Access Denied');
class Czt_subscribe_redpackModuleProcessor extends WeModuleProcessor
{
    public function reply($c)
    {
        global $_W, $activity, $openid;
        if (!empty($activity['reply'])) {
            sendCustom($openid, $c);
            return $this->respText($activity['reply']);
        } else {
            return $this->respText($c);
        }
    }
    public function respond()
    {
        global $_W, $activity, $openid;
        $activity = $this->module['config']['activity'];
        $api      = $this->module['config']['api'];
        $openid   = $this->message['from'];
        if (TIMESTAMP > $activity['time']['start'] && TIMESTAMP < $activity['time']['end']) {
            if (!empty($activity['limit_amount']) && $activity['limit_amount'] > 0) {
                $amount = pdo_fetchcolumn('select sum(fee)  from ' . tablename('czt_subscribe_redpack_records') . ' where uniacid=' . $_W['uniacid']);
                if (floatval($amount) > floatval($activity['limit_amount'])) {
                    return $this->reply('活动已经结束了！');
                }
            }
            $userlimit = intval($activity['userlimit']);
            if ($userlimit > 0) {
                $today = getCurTimeRange();
                $num   = pdo_fetchcolumn('select count(*)  from ' . tablename('czt_subscribe_redpack_records') . ' where uniacid=' . $_W['uniacid'] . ' and status=1 and create_time >= ' . $today['s'] . ' and create_time <= ' . $today['e']);
                if ($num >= $userlimit) {
                    return $this->reply('抱歉今天领取名额已满，请明天再来吧！');
                }
            }
            if (!empty($activity['limit_area'])) {
                if ($activity['limit_type'] == 1 && $_W['account']['key'] == $api['appid']) {
                    load()->classs('weixin.account');
                    $accObj = new WeixinAccount($_W['account']);
                    $ret    = $accObj->fansQueryInfo($openid);
                    if (!is_error($ret)) {
                        if (empty($ret['province']) || empty($ret['city'])) {
                            return $this->reply('用户信息不完整');
                        }
                        $limit_area = explode('|', $activity['limit_area']);
                        $result     = false;
                        foreach ($limit_area as $value) {
                            if (strpos($ret['province'], $value) === false && strpos($ret['city'], $value) === false) {
                                $result = false;
                            } else {
                                $result = true;
                                break;
                            }
                        }
                        if (!$result) {
                            return $this->reply('抱歉，您不在本次活动参与范围内');
                        }
                    } else {
                        return $this->reply('获取用户信息出错');
                    }
                } elseif ($activity['limit_type'] >= 2) {
                    $pars             = array();
                    $pars[':uniacid'] = $_W['uniacid'];
                    $pars[':openid']  = $openid;
                    $ret              = pdo_fetch("SELECT * FROM " . tablename('czt_subscribe_redpack_records') . " WHERE `uniacid`=:uniacid AND `openid`=:openid and status=1", $pars);
                    if (empty($ret)) {
                        return $this->reply('<a href="' . $this->buildSiteUrl($this->createMobileUrl('redpack', array(
                            'openid' => $openid
                        ))) . '">点击领取红包</a>');
                    } else {
                        return $this->reply('您已经领过红包了！');
                    }
                }
            }
            if ($_W['account']['key'] != $api['appid']) {
                $pars             = array();
                $pars[':uniacid'] = $_W['uniacid'];
                $pars[':openid']  = $openid;
                $ret              = pdo_fetch("SELECT * FROM " . tablename('czt_subscribe_redpack_records') . " WHERE `uniacid`=:uniacid AND `openid`=:openid and status=1", $pars);
                if (empty($ret)) {
                    if (!empty($activity['limit_area'])) {
                        return $this->reply('<a href="' . $this->buildSiteUrl($this->createMobileUrl('redpack', array(
                            'openid' => $openid
                        ))) . '">点击领取红包</a>');
                    } else {
                        return $this->reply('<a href="' . $this->buildSiteUrl($this->createMobileUrl('redpack', array(
                            'openid' => $openid
                        ))) . '">恭喜您得到一个红包，点击领取</a>');
                    }
                } else {
                    return $this->reply('您已经领过红包了！');
                }
            }
            $r = $this->send($openid);
            if ($r === true) {
                return $this->reply('恭喜您领到一个红包了！');
            }
            if ($r === 'success') {
                return $this->reply('您已经领过红包了！');
            }
            if (empty($activity['err_tip'])) {
                return $this->reply('出错，未能领到红包:(');
            } else {
                return $this->reply($activity['err_tip']);
            }
        } else {
            if (TIMESTAMP < $activity['time']['start']) {
                return $this->reply('活动未开始！');
            } else {
                return $this->reply('活动已经结束了！');
            }
        }
    }
    public function send($openid)
    {
        global $_W;
        if (empty($openid)) {
            return;
        }
        $activity = $this->module['config']['activity'];
        if (empty($activity)) {
            return;
        }
        $api = $this->module['config']['api'];
        if (empty($api)) {
            return;
        }
        $condition        = "`uniacid`=:uniacid AND `openid`=:openid";
        $pars             = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':openid']  = $openid;
        $sql              = "SELECT * FROM " . tablename('czt_subscribe_redpack_records') . " WHERE {$condition}";
        $ret              = pdo_fetch($sql, $pars);
        if (!empty($ret) && $ret['status'] == 1) {
            return 'success';
        }
        if (empty($ret)) {
            $fee              = rand($activity['fee']['downline'] * 100, $activity['fee']['upline'] * 100);
            $r                = array();
            $r['uniacid']     = $_W['uniacid'];
            $r['openid']      = $openid;
            $r['create_time'] = TIMESTAMP;
            $r['fee']         = sprintf('%.2f', $fee / 100);
            $ret              = pdo_insert('czt_subscribe_redpack_records', $r);
            if (!empty($ret)) {
                $record_id = pdo_insertid();
            } else {
                return;
            }
        } else {
            $record_id = $ret['id'];
            $fee       = $ret['fee'] * 100;
        }
        $url                  = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars                 = array();
        $pars['nonce_str']    = random(32);
        $pars['mch_billno']   = $api['mchid'] . date('Ymd') . sprintf('%010d', $record_id);
        $pars['mch_id']       = $api['mchid'];
        $pars['wxappid']      = $api['appid'];
        $pars['send_name']    = $activity['provider'];
        $pars['re_openid']    = $openid;
        $pars['total_amount'] = $fee;
        $pars['total_num']    = 1;
        $pars['wishing']      = $activity['wish'];
        $pars['client_ip']    = CLIENT_IP;
        $pars['act_name']     = $activity['title'];
        $pars['remark']       = '关注送红包';
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$api['password']}";
        $pars['sign']              = strtoupper(md5($string1));
        $xml                       = array2xml($pars);
        $extras                    = array();
        $path                      = ATTACHMENT_ROOT . '/czt_subscribe_redpack/cert';
        $extras['CURLOPT_CAINFO']  = $path . '/rootca.pem.' . $_W['uniacid'];
        $extras['CURLOPT_SSLCERT'] = $path . '/apiclient_cert.pem.' . $_W['uniacid'];
        $extras['CURLOPT_SSLKEY']  = $path . '/apiclient_key.pem.' . $_W['uniacid'];
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
                    $procResult = true;
                } else {
                    $error      = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }
        if (is_error($procResult)) {
            @file_put_contents(MODULE_ROOT . '/log.txt', date('Y-m-d H:i:s') . ':' . $procResult['message'] . PHP_EOL, FILE_APPEND);
            return $procResult;
        } else {
            $filters             = array();
            $filters['uniacid']  = $_W['uniacid'];
            $filters['id']       = $record_id;
            $rec                 = array();
            $rec['status']       = 1;
            $rec['success_time'] = time();
            pdo_update('czt_subscribe_redpack_records', $rec, $filters);
            return true;
        }
    }
}
function sendCustom($openid, $data, $type = 'text')
{
    global $_W;
    load()->classs('weixin.account');
    $account = new WeiXinAccount($_W['account']);
    switch ($type) {
        case 'text':
            preg_match_all("/[\x{0080}-\x{ffff}]+/u", $data, $match);
            if (!empty($match[0]) && is_array($match[0])) {
                foreach ($match[0] as $key => $value) {
                    $data = str_replace($value, urlencode($value), $data);
                }
            }
            $data = array(
                'touser' => $openid,
                'msgtype' => 'text',
                'text' => array(
                    'content' => ($data)
                )
            );
            break;
        case 'image':
            $data = array(
                "touser" => $openid,
                "msgtype" => "image",
                "image" => array(
                    "media_id" => $data
                )
            );
            break;
        case 'voice':
            $data = array(
                "touser" => $openid,
                "msgtype" => "voice",
                "voice" => array(
                    "media_id" => $data
                )
            );
            break;
        case 'news':
            $data = array(
                "touser" => $openid,
                "msgtype" => "news",
                "news" => array(
                    "articles" => $data
                )
            );
            break;
        case 'wxcard':
            $data = array(
                "touser" => $openid,
                "msgtype" => "wxcard",
                "wxcard" => array(
                    "card_id" => $data
                )
            );
            break;
    }
    $ret = $account->sendCustomNotice($data);
    if (is_error($ret)) {
        file_put_contents(MODULE_ROOT . "/error.log", date('Y-m-d H:i:s') . ':' . $ret['message'] . PHP_EOL, FILE_APPEND);
    }
    return $ret;
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
function enable_log()
{
    ini_set("display_errors", 0);
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set("log_errors", "On");
    ini_set("error_log", MODULE_ROOT . "/error.log");
}

?>