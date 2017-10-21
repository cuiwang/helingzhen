<?php

function m($name = '') {
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $module = YIKE_MODULE . strtolower($name) . '.php';
    if (!is_file($module)) {
        die(' Module ' . $name . ' Not Found!');
    }
    require $module;
    $class_name = 'Yike_' . ucfirst($name);
    $_modules[$name] = new $class_name();
    return $_modules[$name];
}

function show_json($status = 1, $return = null) {
    $ret = array(
        'status' => $status
    );
    if ($return) {
        $ret['result'] = $return;
    }
    die(json_encode($ret));
}

function is_weixin() {
    if (empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false) {
        return false;
    }
    return true;
}
