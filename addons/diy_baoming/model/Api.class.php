<?php

class Api {
    

    
    public function auth($scope = 'openid') {
        global $_W, $_GPC;
        session_start();
        $from = getenv('REQUEST_URI');
        $urls = parse_url($from);
        parse_str($urls['query'], $querys);
        unset($querys['code']);
        unset($querys['state']);
        $from = $urls['path'] . '?' . http_build_query($querys);
        if($scope == 'openid') {
            $openid = $_SESSION['__:proxy:openid'];
            if(!empty($openid)) {
                return $openid;
            }

            $code = $_GPC['code'];
            if(!empty($code)) {
                $fan = $this->oAuthGetUser($code);
                if(empty($fan) || is_error($fan)) {
                    return error(-1, '微信接口错误, 无法确认您的身份');
                }
                $_SESSION['__:proxy:openid'] = $fan['openid'];
                return $fan['openid'];
            }

            $callback = $_W['siteroot'] . substr($from, 1);
            $callback = urlencode($callback);
            $state = '';
            $forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->account['appid']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
            header('Location: ' . $forward);
            exit;
        }
    }

    private function oAuthGetUser($code) {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->account['appid']}&secret={$this->account['secret']}&code={$code}&grant_type=authorization_code";
        $content = Net::httpGet($url);
        if(is_error($content)) {
            return error(-1, '微信通信失败(GetUserInfo), 请稍后重试！错误详情: ' . $content['message']);
        }
        $token = @json_decode($content, true);
        if(empty($token) || !is_array($token) || empty($token['openid'])) {
            return error(-2, '微信通信失败(GetUserInfo), 请稍后重试！错误详情: ' . $content);
        }
        $user = array();
        $user['openid'] = $token['openid'];
        if($token['scope'] == 'snsapi_userinfo') {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid={$user['openid']}&lang=zh_CN";
            $content = Net::httpGet($url);
            $info = @json_decode($content, true);
            if(!empty($info) && is_array($info) && !empty($info['openid'])) {
                $user['openid']          = $info['openid'];
                $user['unionid']         = $info['unionid'];
                $user['nickname']        = $info['nickname'];
                $user['gender']          = '保密';
                if($info['sex'] == '1') {
                    $user['gender'] = '男';
                }
                if($info['sex'] == '2') {
                    $user['gender'] = '女';
                }
                $user['city']            = $info['city'];
                $user['state']           = $info['province'];
                $user['avatar']          = $info['headimgurl'];
                $user['country']         = $info['country'];
                if(!empty($user['avatar'])) {
                    $user['avatar'] = rtrim($user['avatar'], '0');
                    $user['avatar'] .= '132';
                }
                $user['original'] = $info;
            }
        }
        return $user;
    }

    /**
     * 创建在线支付订单
     * @param string $openid
     *
     * @param array $order
     *          tid:  订单编号
     *          fee:  订单金额
     *        title:  商品名称
     *   attachment:  附加数据
     *
     * @param array $config
     *         mchid:  商户号
     *      password:  支付密钥
     *
     * @return array | error
     */
    public function payCreateOrder($openid, $order, $config = array()) {
        $pars = array();
        $pars['appid'] = $config['appid'];
        $pars['mch_id'] = $config['mchid'];
        $pars['nonce_str'] = util_random(32);
        $pars['body'] = $order['title'];
        if(!empty($order['attachment'])) {
            $pars['attach'] = $order['attachment'];
        }
        $pars['out_trade_no'] = $order['sn'];
        $pars['total_fee'] = $order['money'] * 100;
        $pars['spbill_create_ip'] = get_client_ip(0, true);
        $pars['notify_url'] = $config['notify'];
        $pars['trade_type'] = 'JSAPI';
        $pars['openid'] = $openid;

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$config['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = '<xml>';
        foreach($pars as $k => $v) {
            $xml .= "<{$k}>{$v}</{$k}>";
        }
        $xml .= '</xml>';
  
        load()->func('communication');
	    $response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml);

	    if (is_error($response)) {
		  return $response;
	   } else {
	   	    $resp=$response['content'];
	   	  
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp;
            $dom = new \DOMDocument();
            if($dom->loadXML($xml, LIBXML_DTDLOAD | LIBXML_DTDATTR)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $prepay = $xpath->evaluate('string(//xml/prepay_id)');
                    $params = array();
                    $params['appId'] = $config['appid'];
                    $params['timeStamp'] = TIMESTAMP;
                    $params['nonceStr'] = util_random(32);
                    $params['package'] = "prepay_id={$prepay}";
                    $params['signType'] = 'MD5';

                    ksort($params, SORT_STRING);
                    $string1 = '';
                    foreach($params as $k => $v) {
                        $string1 .= "{$k}={$v}&";
                    }
                    $string1 .= "key={$config['password']}";
                    $r = array();
                    $r['appid'] = $config['appid'];
                    $r['timestamp'] = $params['timeStamp'];
                    $r['nonce'] = $params['nonceStr'];
                    $r['package'] = $params['package'];
                    $r['signature'] = strtoupper(md5($string1));

                    return $r;
                } else {
                    $error = $xpath->evaluate('string(//xml/return_msg)');

                    return error(-2, $error);
                }
            } else {
                return error(-1, 'error response');
            }
        }
    }

    /**
     * 确认在线支付订单结果
     * @param string $input 支付结果元数据
     *
     * @param array $config
     *         mchid:  商户号
     *      password:  支付密钥
     *
     * @return array | error
     */
    public function payConfirmOrderold($input, $config = array()) {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $input;
        $dom = new \DOMDocument();
        if($dom->loadXML($xml, LIBXML_DTDLOAD | LIBXML_DTDATTR)) {
            $xpath = new \DOMXPath($dom);
            $code = $xpath->evaluate('string(//xml/return_code)');
            $ret = $xpath->evaluate('string(//xml/result_code)');
            if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                $pars = array();
                $vals = $xpath->query('//xml/*');
                foreach($vals as $val) {
                    $pars[$val->tagName] = $val->nodeValue;
                }

                ksort($pars, SORT_STRING);
                $string1 = '';
                foreach($pars as $k => $v) {
                    if(!in_array($k, array('sign'))) {
                        $string1 .= "{$k}={$v}&";
                    }
                }
                $string1 .= "key={$config['password']}";
                if($pars['sign'] == strtoupper(md5($string1))) {
                    $ret = array();
                    $ret['openid'] = $pars['openid'];
                    $ret['tid'] = $pars['out_trade_no'];
                    $ret['fee'] = $pars['total_fee'] / 100;
                    $ret['attachment'] = $pars['attach'];
                    $ret['original'] = $pars;
                    return $ret;
                }
            } else {
                $error = $xpath->evaluate('string(//xml/return_msg)');
                return error(-2, $error);
            }
        } else {
            return error(-1, 'error response');
        }
    }
    
    
      public function payConfirmOrder($input, $config = array()) {
              ksort($input, SORT_STRING);
              $string1 = '';
              foreach($input as $k => $v) {
                if(!in_array($k, array('sign'))) {
                  $string1 .= "{$k}={$v}&";
                 }
              }
              $string1 .= "key={$config['password']}";
              if($input['sign'] == strtoupper(md5($string1))) {
                $ret = array();
                $ret['openid'] = $input['openid'];
                $ret['tid'] = $input['out_trade_no'];
                $ret['fee'] = $input['total_fee'] / 100;
                $ret['attachment'] = $input['attach'];
                $ret['original'] = $input;
                return $ret;
              } else {
                return error(-1, 'error response');
              }
      } 
  
    /**
     * 创建在线支付订单退款
     *
     * @param array $order
     *          tid:  订单编号
     *          fee:  订单金额
     *
     * @param array $config
     *         mchid:  商户号
     *      password:  支付密钥
     *            ca:  CA证书
     *          cert:  Cert证书
     *           key:  Key证书
     *
     * @return true | error
     */
    public function payRefund($order, $config = array()) {
        $pars = array();
        $pars['appid'] = $this->account['appid'];
        $pars['mch_id'] = $config['mchid'];
        $pars['nonce_str'] = util_random(32);
        
        $pars['out_trade_no'] = $order['tid'];
        $pars['out_refund_no'] = $order['tid'];
        $pars['total_fee'] = $order['fee'] * 100;
        $pars['refund_fee'] = $order['fee'] * 100;
        
        $pars['op_user_id'] = $config['mchid'];

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$config['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = '<xml>';
        foreach($pars as $k => $v) {
            $xml .= "<{$k}>{$v}</{$k}>";
        }
        $xml .= '</xml>';

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $resp = Net::httpPost($url, $xml);
        if(is_error($resp)) {
            return $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp;
            $dom = new \DOMDocument();
            if($dom->loadXML($xml, LIBXML_DTDLOAD | LIBXML_DTDATTR)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    return true;
                } else {
                    $error = $xpath->evaluate('string(//xml/return_msg)');
                    return error(-2, $error);
                }
            } else {
                return error(-1, 'error response');
            }
        }
    }

    /**
     * 发放红包
     * @param string $openid
     * @param array $packet
     *         type:  fission - 裂变红包; basic - 单个现金红包
     *          tid:  红包序号
     *          fee:  红包金额
     *       sender:  红包发送方
     *         wish:  红包祝福语
     *     activity:  红包活动名称
     *       remark:  红包备注说明
     * 
     *        total:  裂变红包数量
     *
     * @param array $config
     *         mchid:  商户号
     *            ip:  服务器IP
     *      password:  支付密钥
     *            ca:  CA证书
     *          cert:  Cert证书
     *           key:  Key证书
     *
     * @return true | error
     */
    public function payRedpacket($openid, $packet, $config = array()) {
        $pars = array();
        $pars['nonce_str'] = util_random(32);
        if(empty($trade['tid'])) {
            $trade['tid'] = rand(0, 9999999999);
        }
        $pars['mch_billno'] = $config['mchid'] . date('Ymd') . sprintf('%010d', $trade['tid']);
        $pars['mch_id'] = $config['mchid'];
        $pars['wxappid'] = $this->account['appid'];
        $pars['send_name'] = $packet['sender'];
        $pars['re_openid'] = $openid;
        $pars['total_amount'] = $packet['fee'] * 100;
        $pars['wishing'] = $packet['wish'];
        $pars['act_name'] = $packet['activity'];
        $pars['remark'] = $packet['remark'];
        if($packet['type'] == 'fission') {
            $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
            $pars['amt_type'] = 'ALL_RAND';
            $pars['total_num'] = $packet['total'];
        } else {
            $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
            $pars['client_ip'] = $config['ip'];
            $pars['total_num'] = 1;
        }

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$config['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = '<xml>';
        foreach($pars as $k => $v) {
            $xml .= "<{$k}>{$v}</{$k}>";
        }
        $xml .= '</xml>';

        $extras = array();
        $extras['CURLOPT_CAINFO'] = $config['ca'];
        $extras['CURLOPT_SSLCERT'] = $config['cert'];
        $extras['CURLOPT_SSLKEY'] = $config['key'];

        $procResult = null;
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $resp = Net::httpRequest($url, $xml, $headers, '', 60, $extras);
        if(is_error($resp)) {
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if($dom->loadXML($xml, LIBXML_DTDLOAD | LIBXML_DTDATTR)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }

        if(is_error($procResult)) {
            return $procResult;
        } else {
            return true;
        }
    }

    public function push($openid, $packet) {
        $token = $this->fetchToken();
        if(is_error($token)){
            return $token;
        }
        $pars = array();
        $pars['touser'] = $openid;
        if(!empty($packet['kf'])) {
            $pars['customservice']['kf_account'] = $packet['kf']['account'];
        }
        if($packet['type'] == Platform::PACKET_TEXT) {
            $pars['msgtype'] = 'text';
            $pars['text']['content'] = urlencode($packet['content']);
        }
        if($packet['type'] == Platform::PACKET_IMAGE) {
            $pars['msgtype'] = 'image';
            $media = $packet['image'];
            if(is_file($packet['image'])) {
                $ret = $this->uploadMedia('image', $packet['image']);
                if(is_error($ret)) {
                    return $ret;
                }
                $media = $ret['media'];
            }
            $pars['image']['media_id'] = $media;
        }
        if($packet['type'] == Platform::PACKET_NEWS) {
            $pars['msgtype'] = 'news';
            if(count($packet['news']) > 10) {
                $packet['news'] = array_rand($packet['news'], 10);
            }
            foreach($packet['news'] as $article) {
                $pars['news']['articles'][] = array(
                    'title' => urlencode($article['title']),
                    'description' => urlencode($article['description']),
                    'url' => $article['url'],
                    'picurl' => $article['image']
                );
            }
        }
        if($packet['type'] == Platform::PACKET_CARD) {
            $pars['msgtype'] = 'wxcard';
            $filter = array();
            $filter['card'] = $packet['card'];
            $card = $this->cardDataCreate($filter, 'ext');
            $pars['wxcard']['card_id'] = $card['card'];
            $ext = array();
            $ext['code'] = '';
            $ext['openid'] = '';
            $ext['timestamp'] = $card['timestamp'];
            $ext['signature'] = $card['signature'];
            $pars['wxcard']['card_ext'] = json_encode($ext);
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}";
        $dat = json_encode($pars);
        $resp = Net::httpPost($url, urldecode($dat));
        if(is_error($resp)) {
            return error(-1, "访问公众平台接口失败, 错误: {$resp['message']}");
        }
        $result = @json_decode($resp, true);
        if(empty($result)) {
            return error(-2, "接口调用失败, 错误信息: {$resp}");
        } elseif (!empty($result['errcode'])) {
            return error($result['errcode'], "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}");
        }
        return true;
    }

    public function shareDataCreate($forceUrl = '') {
        $ticket = $this->core->module['config']['jsticket'];
        if(is_array($ticket) && !empty($ticket['ticket']) && !empty($ticket['expire']) && $ticket['expire'] > TIMESTAMP) {
            $t = $ticket['ticket'];
        } else {
            $token = $this->fetchToken();
            if(is_error($token)) {
                return $token;
            }
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
            $resp = Net::httpGet($url);
            if(is_error($resp)) {
                return error(-1, "访问公众平台接口失败, 错误: {$resp['message']}");
            }
            $result = @json_decode($resp, true);
            if(empty($result)) {
                return error(-2, "接口调用失败, 错误信息: {$resp}");
            } elseif (!empty($result['errcode'])) {
                return error($result['errcode'], "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}");
            }

            $record = array();
            $record['ticket'] = $result['ticket'];
            $record['expire'] = TIMESTAMP + $result['expires_in'];

            $setting = $this->core->module['config'];
            $setting['jsticket'] = $record;
            $this->core->saveSettings($setting);
            $t = $record['ticket'];
        }

        $share = array();
        $share['appid'] = $this->account['appid'];
        $share['timestamp'] = TIMESTAMP;
        $share['nonce'] = util_random(32);
        if(empty($forceUrl)) {
            $forceUrl = __HOST__ . $_SERVER['REQUEST_URI'];
        }

        $string1 = "jsapi_ticket={$t}&noncestr={$share['nonce']}&timestamp={$share['timestamp']}&url={$forceUrl}";
        $share['signature'] = sha1($string1);
        return $share;
    }

    public function cardDataCreate($filter = array(), $type = 'choose') {
        $ticket = $this->core->module['config']['cardticket'];
        if(is_array($ticket) && !empty($ticket['ticket']) && !empty($ticket['expire']) && $ticket['expire'] > TIMESTAMP) {
            $t = $ticket['ticket'];
        } else {
            $token = $this->fetchToken();
            if(is_error($token)) {
                return $token;
            }
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=wx_card";
            $resp = Net::httpGet($url);
            if(is_error($resp)) {
                return error(-1, "访问公众平台接口失败, 错误: {$resp['message']}");
            }
            $result = @json_decode($resp, true);
            if(empty($result)) {
                return error(-2, "接口调用失败, 错误信息: {$resp}");
            } elseif (!empty($result['errcode'])) {
                return error($result['errcode'], "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}");
            }

            $record = array();
            $record['ticket'] = $result['ticket'];
            $record['expire'] = TIMESTAMP + $result['expires_in'];

            $setting = $this->core->module['config'];
            $setting['cardticket'] = $record;
            $this->core->saveSettings($setting);
            $t = $record['ticket'];
        }

        $card = array();
        if($type == 'choose') {
            $filter = coll_elements(array('shop', 'card', 'type'), $filter, '');
            $pars = array();
            $pars['app_id'] = $this->account['appid'];
            $pars['api_ticket'] = $t;
            $pars['timestamp'] = TIMESTAMP;
            $pars['nonce_str'] = util_random(32);

            $signPars = array_values($pars);
            sort($signPars, SORT_STRING);
            $string1 = implode($signPars);

            $card['shop'] = $filter['shop'];
            $card['type'] = $filter['type'];
            $card['card'] = $filter['card'];
            $card['timestamp'] = $pars['timestamp'];
            $card['nonce'] = $pars['nonce_str'];
            $card['signature'] = sha1($string1);
            $card['original'] = $string1;
        }
        if($type == 'ext') {
            $filter = coll_elements(array('card', 'code', 'openid'), $filter, '');
            $pars = array();
            $pars['api_ticket'] = $t;
            $pars['timestamp'] = TIMESTAMP;
            $pars['card_id'] = $filter['card'];
            $pars['code'] = $filter['code'];
            $pars['openid'] = $filter['openid'];

            $signPars = array_values($pars);
            sort($signPars, SORT_STRING);
            $string1 = implode($signPars);

            $card['card'] = $filter['card'];
            $card['code'] = $filter['code'];
            $card['openid'] = $filter['openid'];
            $card['timestamp'] = $pars['timestamp'];
            $card['signature'] = sha1($string1);
        }
        return $card;
    }
}
