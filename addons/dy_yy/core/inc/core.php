<?php


if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Core extends WeModuleSite
{
    public $footer = array();
    public $header = null;
    public function __construct()
    {
       global $_W, $_GPC;

	   if (!is_weixin()) {
         
       //m('member')->checkMember();
	   
        }
        if (is_weixin()) {
         
        m('member')->checkMember();

        }
    }
    public function createMobileUrl($do, $query = array(), $noredirect = true)
    {
        global $_W, $_GPC;
        $do = explode('/', $do);
        if (isset($do[1])) {
            $query = array_merge(array(
                'p' => $do[1]
            ), $query);
        }
        if (empty($query['mid'])) {
            $mid = intval($_GPC['mid']);
            if (!empty($mid)) {
                $query['mid'] = $mid;
            }
        }
        return $_W['siteroot'] . 'app/' . substr(parent::createMobileUrl($do[0], $query, true), 2);
    }
    public function createWebUrl($do, $query = array())
    {
        global $_W;
        $do = explode('/', $do);
        if (count($do) > 1 && isset($do[1])) {
            $query = array_merge(array(
                'p' => $do[1]
            ), $query);
        }
        return $_W['siteroot'] . 'web/' . substr(parent::createWebUrl($do[0], $query, true), 2);
    }
    public function _exec($do, $default = '', $web = true){
        
        global $_GPC;
        $do = strtolower(substr($do, $web ? 5 : 8));
        $p  = trim($_GPC['p']);
        empty($p) && $p = $default;
        if ($web) {
            $file = IA_ROOT . "/addons/dy_yy/core/web/" . $do . "/" . $p . ".php";
        } else {
            
            $file = IA_ROOT . "/addons/dy_yy/core/mobile/" . $do . "/" . $p . ".php";
        }
        if (!is_file($file)) {
            message("未找到 控制器文件 {$do}::{$p} : {$file}");
        }
		
        include $file;
        exit;
    }
    public function template($filename, $type = TEMPLATE_INCLUDEPATH)
    {
        global $_W;
        $name = strtolower($this->modulename);
        if (defined('IN_SYS')) {
            $source  = IA_ROOT . "/web/themes/{$_W['template']}/{$name}/{$filename}.html";
            $compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/{$filename}.tpl.php";
            if (!is_file($source)) {
                $source = IA_ROOT . "/web/themes/default/{$name}/{$filename}.html";
            }
            if (!is_file($source)) {
                $source = IA_ROOT . "/addons/{$name}/template/{$filename}.html";
            }
            if (!is_file($source)) {
                $source = IA_ROOT . "/web/themes/{$_W['template']}/{$filename}.html";
            }
            if (!is_file($source)) {
                $source = IA_ROOT . "/web/themes/default/{$filename}.html";
            }
            if (!is_file($source)) {
                $explode = explode('/', $filename);
                $temp    = array_slice($explode, 1);
                $source  = IA_ROOT . "/addons/{$name}/plugin/" . $explode[0] . "/template/" . implode('/', $temp) . ".html";
            }
        } else {
            $template = m('cache')->getString('template_shop');
            if (empty($template)) {
                $template = "default";
            }
            if (!is_dir(IA_ROOT . '/addons/dy_yy/template/mobile/' . $template)) {
                $template = "default";
            }
            $compile = IA_ROOT . "/data/tpl/app/dy_yy/{$template}/mobile/{$filename}.tpl.php";
            $source  = IA_ROOT . "/addons/{$name}/template/mobile/{$template}/{$filename}.html";
            if (!is_file($source)) {
                $source = IA_ROOT . "/addons/{$name}/template/mobile/default/{$filename}.html";
            }
            if (!is_file($source)) {
                $names      = explode('/', $filename);
                $pluginname = $names[0];
                $ptemplate  = m('cache')->getString('template_' . $pluginname);
                if (empty($ptemplate)) {
                    $ptemplate = "default";
                }
                if (!is_dir(IA_ROOT . '/addons/dy_yy/plugin/' . $pluginname . "/template/mobile/" . $ptemplate)) {
                    $ptemplate = "default";
                }
                $pfilename = $names[1];
                $source    = IA_ROOT . "/addons/dy_yy/plugin/" . $pluginname . "/template/mobile/" . $ptemplate . "/{$pfilename}.html";
            }
            if (!is_file($source)) {
                $source = IA_ROOT . "/app/themes/{$_W['template']}/{$filename}.html";
            }
            if (!is_file($source)) {
                $source = IA_ROOT . "/app/themes/default/{$filename}.html";
            }
        }
        if (!is_file($source)) {
            exit("Error: template source '{$filename}' is not exist!");
        }
        if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
            shop_template_compile($source, $compile, true);
        }
        return $compile;
    }
	
}
