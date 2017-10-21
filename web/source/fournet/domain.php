<?php
defined('IN_IA') or exit('Access Denied');
$config['setting']['domain']['load'] = '1';
$host                                = $_SERVER['HTTP_HOST'];
$domain_set                          = $config['setting']['domain'];
$file                                = IA_ROOT . '/data/domain/' . str_replace('.', '_', $host) . '.php';
if (file_exists($file)) {
    $domain = include $file;
}
unset($file);
if (defined('IN_SYS')) {
    if ($domain_set['protect_web'] && ($host != $domain_set['host'])) {
        if (empty($domain['enable_web'])) {
            header('Content-Type:text/html;charset=utf-8');
            exit('无效域名,禁止访问!');
        } else if (!empty($domain) && !empty($domain['uniacid'])) {
            $_COOKIE[$config['cookie']['pre'] . '__uniacid'] = $domain['uniacid'];
        }
    }
} else {
    if (empty($domain)) {
        if ($domain_set['protect_app'] && ($host != $domain_set['host'])) {
            header('Content-Type:text/html;charset=utf-8');
            exit('无效域名,禁止访问!');
        }
    } else {
        $forward = $host;
        if (!empty($domain['forward'])) {
            $i = 0;
            if (count($domain['forward']) > 1) {
                $i = rand(0, count($domain['forward']));
            }
            if (isset($domain['forward'][$i])) {
                $forward = $domain['forward'][$i];
            }
            unset($i);
        }
        if (defined('IN_MOBILE')) {
            if ($domain['redirect'] && $domain['isaccount'] && $forward != $host) {
                if (!($host == $domain_set['host'] && preg_match('|\&c\=auth\&|', $_SERVER['REQUEST_URI']))) {
                    $url = 'http://' . $forward . $_SERVER['REQUEST_URI'];
                    if (!empty($domain['uniacid'])) {
                        $url .= preg_replace('|i\=\d+?\&|', 'i=' . $domain['uniacid'] . '&', $url);
                    }
                    header('Location: ' . $url);
                    exit;
                }
            }
            if (!empty($domain['uniacid'])) {
                if (preg_match('|i\=(\d+?)\&|', $_SERVER['REQUEST_URI'], $m)) {
                    if ($m[1] != $domain['uniacid']) {
                        header('Content-Type:text/html;charset=utf-8');
                        exit("公众号[{$m[1]}]禁止访问{$host}!");
                    }
                }
                unset($m);
            }
        } else {
            if (empty($_SERVER['QUERY_STRING'])) {
                header("Location: " . $domain['url']);
                exit;
            }
        }
    }
}
unset($url);
unset($domain);
unset($domain_set);