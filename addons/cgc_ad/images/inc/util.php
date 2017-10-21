<?php

function __mb_autoload($class_name) {
    $file = MB_ROOT . "/model/{$class_name}.class.php";
    if(is_file($file)) {
        require_once $file;
    }
    return false;
}
spl_autoload_register('__mb_autoload');

function inputRaw($jsonDecode = true) {
    $post = file_get_contents('php://input');
    if($jsonDecode) {
        $post = @json_decode($post, true);
    }
    return $post;
}

function coll_key($ds, $key) {
    if(!empty($ds) && !empty($key)) {
        $ret = array();
        foreach($ds as $row) {
            $ret[$row[$key]] = $row;
        }
        return $ret;
    }
    return array();
}

function coll_neaten($ds, $key) {
    if(!empty($ds) && !empty($key)) {
        $ret = array();
        foreach($ds as $row) {
            $ret[] = $row[$key];
        }
        return $ret;
    }
    return array();
}

function coll_walk($ds, $callback, $key = null) {
    if(!empty($ds) && is_callable($callback)) {
        $ret = array();
        if(!empty($key)) {
            foreach($ds as $k => $row) {
                $ret[$k] = call_user_func($callback, $row[$key]);
            }
        } else {
            foreach($ds as $k => $row) {
                $ret[$k] = call_user_func($callback, $row);
            }
        }
        return $ret;
    }
    return array();
}



/**
 * 该函数从一个数组中取得若干元素。
 * 该函数测试（传入）数组的每个键值是否在（目标）数组中已定义；
 * 如果一个键值不存在，该键值所对应的值将被置为FALSE，
 * 或者你可以通过传入的第3个参数来指定默认的值。
 *
 * @param array $keys 需要筛选的键名列表
 * @param array $src 要进行筛选的数组
 * @param mixed $default 如果原数组未定义某个键，则使用此默认值返回
 * @return array
 */
function coll_elements($keys, $src, $default = false) {
    $return = array();
    if(!is_array($keys)) {
        $keys = array($keys);
    }
    foreach($keys as $key) {
        if(isset($src[$key])) {
            $return[$key] = $src[$key];
        } else {
            $return[$key] = $default;
        }
    }
    return $return;
}

/**
 * 判断一个数是否介于一个区间或将一个数转换为此区间的数.
 *
 * @param string $num 输入参数
 * @param int $downline 参数下限
 * @param int $upline 参数上限
 * @param string $returnNear 对输入参数是判断还是返回
 * @return boolean | number
 * <pre>
 *  boolean 判断输入参数是否介于 $downline 和 $upline 之间
 *  number 将输入参数转换为介于  $downline 和 $upline 之间的整数
 * </pre>
 */
function util_limit($num, $downline, $upline, $returnNear = true) {
    $num = intval($num);
    $downline = intval($downline);
    $upline = intval($upline);
    if($num < $downline){
        return empty($returnNear) ? false : $downline;
    } elseif ($num > $upline) {
        return empty($returnNear) ? false : $upline;
    } else {
        return empty($returnNear) ? true : $num;
    }
}

function util_random($length, $numeric = false) {
    return random($length, $numeric);
}

function util_json($val) {
    header('Content-Type: application/json');
    echo json_encode($val);
}

function get_client_ip($a, $b) {
    return CLIENT_IP;
}

function wechat_build($params, $wechat) {
	global $_W;
	load()->func('communication');
	if (empty($wechat['version']) && !empty($wechat['signkey'])) {
		$wechat['version'] = 1;
	}
	$wOpt = array();
	if ($wechat['version'] == 1) {
		$wOpt['appId'] = $wechat['appid'];
		$wOpt['timeStamp'] = TIMESTAMP;
		$wOpt['nonceStr'] = random(8);
		$package = array();
		$package['bank_type'] = 'WX';
		$package['body'] = $params['title'];
		$package['attach'] = $_W['uniacid'];
		$package['partner'] = $wechat['partner'];
		$package['out_trade_no'] = $params['uniontid'];
		$package['total_fee'] = $params['fee'] * 100;
		$package['fee_type'] = '1';
		$package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
		$package['spbill_create_ip'] = CLIENT_IP;
		$package['time_start'] = date('YmdHis', TIMESTAMP);
		$package['time_expire'] = date('YmdHis', TIMESTAMP + 600);
		$package['input_charset'] = 'UTF-8';
		ksort($package);
		$string1 = '';
		foreach($package as $key => $v) {
			if (empty($v)) {
				continue;
			}
			$string1 .= "{$key}={$v}&";
		}
		$string1 .= "key={$wechat['key']}";
		$sign = strtoupper(md5($string1));

		$string2 = '';
		foreach($package as $key => $v) {
			$v = urlencode($v);
			$string2 .= "{$key}={$v}&";
		}
		$string2 .= "sign={$sign}";
		$wOpt['package'] = $string2;

		$string = '';
		$keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
		sort($keys);
		foreach($keys as $key) {
			$v = $wOpt[$key];
			if($key == 'appKey') {
				$v = $wechat['signkey'];
			}
			$key = strtolower($key);
			$string .= "{$key}={$v}&";
		}
		$string = rtrim($string, '&');

		$wOpt['signType'] = 'SHA1';
		$wOpt['paySign'] = sha1($string);
		return $wOpt;
	} else {
		$package = array();
		$package['appid'] = $wechat['appid'];
		$package['mch_id'] = $wechat['mchid'];
		$package['nonce_str'] = random(8);
		$package['body'] = $params['title'];
		$package['attach'] = $_W['uniacid'];
		$package['out_trade_no'] = $params['uniontid'];
		$package['total_fee'] = $params['fee'] * 100;
		$package['spbill_create_ip'] = CLIENT_IP;
		$package['time_start'] = date('YmdHis', TIMESTAMP);
		$package['time_expire'] = date('YmdHis', TIMESTAMP + 600);
		$package['notify_url'] = $_W['siteroot'] . 'payment/wechat/notify.php';
		$package['trade_type'] = 'JSAPI';
		$package['openid'] = $_W['fans']['from_user'];
		ksort($package, SORT_STRING);
		$string1 = '';
		foreach($package as $key => $v) {
			if (empty($v)) {
				continue;
			}
			$string1 .= "{$key}={$v}&";
		}
		$string1 .= "key={$wechat['signkey']}";
		$package['sign'] = strtoupper(md5($string1));
		$dat = array2xml($package);
		$response = ihttp_request('https://api.mch.weixin.qq.com/pay/unifiedorder', $dat);
		if (is_error($response)) {
			return $response;
		}
		$xml = @isimplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
		if (strval($xml->return_code) == 'FAIL') {
			return error(-1, strval($xml->return_msg));
		}
		if (strval($xml->result_code) == 'FAIL') {
			return error(-1, strval($xml->err_code).': '.strval($xml->err_code_des));
		}
		$prepayid = $xml->prepay_id;
		$wOpt['appId'] = $wechat['appid'];
		$wOpt['timeStamp'] = TIMESTAMP;
		$wOpt['nonceStr'] = random(8);
		$wOpt['package'] = 'prepay_id='.$prepayid;
		$wOpt['signType'] = 'MD5';
		ksort($wOpt, SORT_STRING);
		foreach($wOpt as $key => $v) {
			$string .= "{$key}={$v}&";
		}
		$string .= "key={$wechat['signkey']}";
		$wOpt['paySign'] = strtoupper(md5($string));
		return $wOpt;
	}
}
