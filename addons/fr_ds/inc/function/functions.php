<?php

// +----------------------------------------------------------------------
// | Author: 微赞 
// +----------------------------------------------------------------------
defined('IN_IA') or exit('Access Denied');

/**
 * 微赞
 * @param string $table
 * @param string $istablepre
 * @return string
 */
function fr_table($table, $istablepre = true) {
    $tablename = "fr_ds_" . $table;
    if ($istablepre) {
        $tablename = tablename($tablename);
    }
    return $tablename;
}

function fr_update($table, $data = array(), $params = array(), $glue = "AND") {
    global $_W;
    if (is_array($params) || empty($params)) {
        $params['uniacid'] = $_W['uniacid'];
    } elseif (is_string($params)) {
        $params .= " AND uniacid = {$_W['uniacid']} ";
    }
    $data = fr_facade($table, $data);
    return pdo_update(fr_table($table, FALSE), $data, $params, $glue);
}

function fr_insert($table, $data = array(), $replace = FALSE) {
    $data = fr_facade($table, $data);
    return pdo_insert(fr_table($table, FALSE), $data, $replace);
}

function fr_delete($table, $params = array(), $glue = "AND") {
    global $_W;
    if (is_array($params) || empty($params)) {
        $params['uniacid'] = $_W['uniacid'];
    } elseif (is_string($params)) {
        $params .= " AND uniacid = {$_W['uniacid']} ";
    }
    return pdo_delete(fr_table($table, FALSE), $params, $glue);
}

function fr_facade($table, $data) {
    $fields = pdo_fetchallfields(fr_table($table));
    // 检查数据字段合法性
    if (!empty($fields)) {
        foreach ($data as $key => $val) {
            if (!in_array($key, $fields, true)) {
                unset($data[$key]);
            }
        }
    }
    return $data;
}

/**
 * 获取列表数据
 * @global type $_W
 * @param type $table_name
 * @param type $page
 * @param type $where
 * @param type $order
 * @param type $page_size
 * @return type
 */
function getPageList($table_name, $page = 1, $where = '', $order = 'id DESC', $page_size = 20) {
    global $_W;
    $return = array();
    $uniacid = $_W["uniacid"];
    $pindex = max(1, intval($page));
    $orderby = '';
    if ($order != '') {
        $orderby = ' ORDER BY ' . $order;
    }
    $sql = 'SELECT * FROM ' . fr_table($table_name) . ' WHERE uniacid = :uniacid ' . $where . $orderby . '  LIMIT ' . ($pindex - 1) * $page_size . ',' . $page_size;
    $return['list'] = pdo_fetchall($sql, array(':uniacid' => $uniacid));
    $countSql = 'SELECT COUNT(*) FROM ' . fr_table($table_name) . ' WHERE uniacid = :uniacid ' . $where;
    $return['total'] = pdo_fetchcolumn($countSql, array(':uniacid' => $uniacid));
    $return['pager'] = pagination($return['total'], $pindex, $page_size);
    return $return;
}

/**
 * 根据ID获取某表数据
 * @global type $_W
 * @param string $table_name
 * @param int $id
 * @param string $field
 * @param fixed $default
 * @return array
 */
function getDataById($table_name, $id, $field = NULL, $default = array()) {
    global $_W;
    if (empty($table_name) || empty($id)) {
        return $default;
    }
    $id = intval($id);
    $uniacid = $_W['uniacid'];
    $sql = "SELECT * FROM " . fr_table($table_name) . " WHERE id = :id AND uniacid = :uniacid";
    $params = array(":id" => $id, ":uniacid" => $uniacid);
    $item = pdo_fetch($sql, $params);
    return empty($field) ? $item : (empty($item[$field]) ? $default : $item[$field]);
}

/**
 * 获取一行数据
 * @global array $_W
 * @param string $table_name
 * @param string $where
 * @return array
 */
function getRow($table_name, $where = '', $order = '') {
    global $_W;
    if (empty($table_name)) {
        return array();
    }
    $orderby = '';
    if ($order != '') {
        $orderby = ' ORDER BY ' . $order;
    }
    $uniacid = $_W['uniacid'];
    $sql = "SELECT * FROM " . fr_table($table_name) . " WHERE uniacid = :uniacid {$where} {$orderby} LIMIT 1";
    $params = array(":uniacid" => $uniacid);
    $row = pdo_fetch($sql, $params);
    return $row;
}

/**
 * 
 * @global type $_W
 * @param string $table_name
 * @param string $field
 * @param string $where
 * @param string $order
 * @return string
 */
function getCol($table_name, $field = '*', $where = '', $order = '') {
    global $_W;
    if (empty($table_name)) {
        return '';
    }
    $orderby = '';
    if ($order != '') {
        $orderby = ' ORDER BY ' . $order;
    }
    $uniacid = $_W['uniacid'];
    $sql = "SELECT {$field} FROM " . fr_table($table_name) . " WHERE uniacid = :uniacid {$where} {$orderby} LIMIT 1";
    $params = array(":uniacid" => $uniacid);
    return pdo_fetchcolumn($sql, $params);
}

/**
 * 获取所有数据
 * @global type $_W
 * @param string $table
 * @param string $where
 * @param string $order
 * @param string $field
 * @param string $keyfield
 * @return array
 */
function getAllData($table_name, $where = '', $order = 'id DESC', $field = "*", $keyfield = '') {
    global $_W;
    if (empty($table_name)) {
        return array();
    }
    $orderby = '';
    if ($order != '') {
        $orderby = ' ORDER BY ' . $order;
    }
    $id = intval($id);
    $uniacid = $_W['uniacid'];
    $sql = "SELECT {$field} FROM " . fr_table($table_name) . " WHERE uniacid = :uniacid {$where} {$orderby}";
    $params = array(":uniacid" => $uniacid);
    $item = pdo_fetchall($sql, $params, $keyfield);
    return $item;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else {
        return $output;
    }
}

/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
function redirect($url, $time = 0, $msg = '') {
    //多行URL地址支持
    $url = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg))
        $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0) {
            $str .= $msg;
        }
        exit($str);
    }
}

function fr_log($log, $type = 'normal', $filename = 'fr_ds') {
    load()->func("logging");
    logging_run($log, $type, $filename);
}

/**
 * 生成web端URL
 * @param type $do
 * @param type $query
 * @return type
 */
function __WURL($do, $query = array(), $noredirect = true, $addhost = true) {
    $query['do'] = $do;
    $query['m'] = 'fr_ds';
    return wurl('site/entry', $query, $noredirect, $addhost);
}

/**
 * 生成APP端URL
 * @param type $do
 * @param type $query
 * @param type $noredirect
 * @param type $addhost
 * @return type
 */
function __MURL($do, $query = array(), $noredirect = true, $addhost = true) {
    $query['do'] = $do;
    $query['m'] = 'fr_ds';
    return murl('entry', $query, $noredirect, $addhost);
}

function get_style_url($skin = 'default') {
    $style_url = __CSS__ . "/" . $skin . "/style.css";
    if (!file_exists($style_url)) {
        $style_url = __CSS__ . "/default/style.css";
    }
    return $style_url;
}

/**
 * 生成订单号
 * @return string
 */
function genOrderSN() {
    return date('Ymdhis') . rand(10, 99);
}

function get_url_params($url = '') {
    $result = array();
    if ($url != '') {
        $string = file_get_contents($url);
        //匹配标题
        $pattern = '/<frtitle>(.+?)<\/frtitle>/is';
        preg_match($pattern, $string, $match);
        $result['title'] = $match[1];

        //匹配二级标题
        $pattern = '/<frsubtitle>(.+?)<\/frsubtitle>/is';
        preg_match($pattern, $string, $match);
        $result['sub_title'] = $match[1];

        //匹配作者
        $pattern = '/<frauthor>(.+?)<\/frauthor>/is';
        preg_match($pattern, $string, $match);
        $result['author'] = $match[1];

        //匹配作者头像
        $pattern = '/<fravatar>(.+?)<\/fravatar>/is';
        preg_match($pattern, $string, $match);
        $result['avatar'] = $match[1];
    }
    return array_filter($result);
}

function getMemberAvatar($openid = '') {
    global $_W;
    static $fr_avatar;
    if ($openid == '') {
        return __IMG__ . '/100.gif';
    }
    load()->model('mc');
    $avatar = '';
    $uid = mc_openid2uid($openid);
    if (!empty($uid)) {
        $member = mc_fetch(intval($uid), array('avatar'));
        if (!empty($member)) {
            $avatar = $member['avatar'];
        }
    }
    if (empty($avatar)) {
        $fan = mc_fansinfo($openid);
        if (!empty($fan)) {
            $avatar = $fan['avatar'];
        }
    }
    if (empty($avatar)) {
        $oauth_account = WeAccount::create($_W['account']['oauth']);
        $userinfo = $oauth_account->fansQueryInfo($openid);
        if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['nickname'])) {
            $avatar = $userinfo['headimgurl'];
        }
    }
    if (empty($avatar)) {
        if (empty($fr_avatar)) {
            $fr_avatar = list_dir(MODULE_ROOT . '/resource/avatar', MODULE_URL . 'resource/avatar/');
        }
        $avatar = array_rand($fr_avatar, 1);
        return $fr_avatar[$avatar];
    } else {
        return $avatar;
    }
}

function getNicknameByOpenid($openid) {
    load()->model("mc");
    $member = mc_fetch($openid, array('nickname'));
    return trim($member['nickname']) != '' ? $member['nickname'] : $openid;
}

function list_dir($dir, $url = '') {
    $result = array();
    if (is_dir($dir)) {
        $file_dir = scandir($dir);
        foreach ($file_dir as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            } elseif (is_dir($dir . $file)) {
                $result = array_merge($result, list_dir($dir . $file . '/'));
            } else {
                array_push($result, ($url == '' ? $dir : $url) . $file);
            }
        }
    }
    return $result;
}
