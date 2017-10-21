<?php
function autoload($class_name)
{
    $file = MODULE_ROOT . "class/{$class_name}.class.php";
    if (is_file($file)) {
        require $file;
    }
    return false;
}
spl_autoload_register('autoload');
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
        $keys = array(
            $keys
        );
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
    $num      = intval($num);
    $downline = intval($downline);
    $upline   = intval($upline);
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
function get_client_ip()
{
    return CLIENT_IP;
}
function check_license()
{
    $check_url      = '';
    $pars           = array();
    $pars['module'] = 'czt_wechat_visitor';
    $pars['host']   = getenv('HTTP_HOST');
    $string1        = '';
    foreach ($pars as $k => $v) {
        $string1 .= "{$k}={$v}&";
    }
    $pars['sign'] = sha1($string1);
    if (file_exists(MODULE_ROOT . 'license')) {
        if (file_get_contents(MODULE_ROOT . 'license') == $pars['sign']) {
            return;
        } else {
            message('不能验证模块授权');
        }
    }
    load()->func('communication');
    $resp = ihttp_post($check_url, $pars);
    if (is_error($resp)) {
        message('请求错误，不能验证模块授权');
    }
    if ($resp['content'] != 'success') {
        message($resp['content']);
    } else {
        file_put_contents(MODULE_ROOT . 'license', $pars['sign']);
    }
}