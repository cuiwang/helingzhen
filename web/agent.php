<?php
define('IN_WEB', true);
require '../framework/bootstrap.inc.php';
require_once IA_ROOT . '/addons/weliam_merchant/core/common/defines.php';
require_once PATH_CORE . 'common/autoload.php';
Func_loader::core('global');
$_W['plugin']     = $plugin = !empty($_GPC['p']) ? $_GPC['p'] : 'dashboard';
$_W['controller'] = $controller = !empty($_GPC['ac']) ? $_GPC['ac'] : 'dashboard';
$_W['method']     = $method = !empty($_GPC['do']) ? $_GPC['do'] : 'index';
Func_loader::web('cover');
$_W['wlsetting'] = Setting::wlsetting_load();
new_method($plugin, $controller, $method);
function new_method($plugin, $controller, $method)
{
    $dir  = IA_ROOT . '/addons/' . MODULE_NAME . '/';
    $file = $dir . 'web/controller/' . $plugin . '/' . $controller . '.ctrl.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        $file = $dir . 'plugin/' . $plugin . '/web/controller/' . $controller . '.ctrl.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            trigger_error("访问的模块 {$plugin} 不存在.", E_USER_WARNING);
        }
    }
    $class = ucfirst($controller) . '_WeliamController';
    if (class_exists($class, false)) {
        $instance = new $class();
    } else {
        $instance = new $controller();
    }
    if (!method_exists($instance, $method)) {
        trigger_error('控制器 ' . $controller . ' 方法 ' . $method . ' 未找到!');
    }
    $instance->$method();
    exit();
}