<?php

function __mb_autoload($class_name)
{
    $file = MB_ROOT . "/model/{$class_name}.class.php";
    if (is_file($file)) {
        require_once $file;
    }
    return false;
}
spl_autoload_register('__mb_autoload');
function inputRaw($jsonDecode = true)
{
    $post = file_get_contents('php://input');
    if ($jsonDecode) {
        $post = @json_decode($post, true);
    }
    return $post;
}
function coll_key($ds, $key)
{
    if (!empty($ds) && !empty($key)) {
        $ret = array();
        foreach ($ds as $row) {
            $ret[$row[$key]] = $row;
        }
        return $ret;
    }
    return array();
}
function coll_neaten($ds, $key)
{
    if (!empty($ds) && !empty($key)) {
        $ret = array();
        foreach ($ds as $row) {
            $ret[] = $row[$key];
        }
        return $ret;
    }
    return array();
}
function coll_walk($ds, $callback, $key = null)
{
    if (!empty($ds) && is_callable($callback)) {
        $ret = array();
        if (!empty($key)) {
            foreach ($ds as $k => $row) {
                $ret[$k] = call_user_func($callback, $row[$key]);
            }
        } else {
            foreach ($ds as $k => $row) {
                $ret[$k] = call_user_func($callback, $row);
            }
        }
        return $ret;
    }
    return array();
}
function coll_elements($keys, $src, $default = false)
{
    $return = array();
    if (!is_array($keys)) {
        $keys = array($keys);
    }
    foreach ($keys as $key) {
        if (isset($src[$key])) {
            $return[$key] = $src[$key];
        } else {
            $return[$key] = $default;
        }
    }
    return $return;
}
function util_limit($num, $downline, $upline, $returnNear = true)
{
    $num = intval($num);
    $downline = intval($downline);
    $upline = intval($upline);
    if ($num < $downline) {
        return empty($returnNear) ? false : $downline;
    } elseif ($num > $upline) {
        return empty($returnNear) ? false : $upline;
    } else {
        return empty($returnNear) ? true : $num;
    }
}
function util_random($length, $numeric = false)
{
    return random($length, $numeric);
}
function util_json($val)
{
    header('Content-Type: application/json');
    echo json_encode($val);
}
function get_client_ip($a, $b)
{
    return CLIENT_IP;
}
function check_license()
{
    $secret = '6a2984a0d776d70ea1e4aa51fbca448b';
   // $gateway = 'http://x.microb.cn/w/wander/extend/cross_cloud/dock/license';
    $pars = array();
    $pars['product'] = 'mb_swish';
    $pars['host'] = getenv('HTTP_HOST');
    $pars['nonstr'] = random(8);
    $pars['time'] = TIMESTAMP;
    $string1 = '';
    foreach ($pars as $k => $v) {
        $string1 .= "{$k}={$v}&";
    }
    $string1 .= $secret;
    $pars['sign'] = sha1($string1);
    load()->func('communication');
    $resp = ihttp_post($gateway, $pars);
    if (is_error($resp)) {
       // message('不能验证模块授权');
    }
    if ($resp['content'] != 'success') {
        //message($resp['content']);
    }
}
class Net
{
    public static function httpRequest($url, $post = '', $headers = array(), $forceIp = '', $timeout = 60, $options = array())
    {
        load()->func('communication');
        return ihttp_request($url, $post, $options, $timeout);
    }
    public static function httpGet($url, $forceIp = '', $timeout = 60)
    {
        $resp = self::httpRequest($url, '', array(), $forceIp, $timeout);
        if (!is_error($resp)) {
            return $resp['content'];
        }
        return $resp;
    }
    public static function httpPost($url, $data, $forceIp = '', $timeout = 60)
    {
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $resp = self::httpRequest($url, $data, $headers, $forceIp, $timeout);
        if (!is_error($resp)) {
            return $resp['content'];
        }
        return $resp;
    }
}