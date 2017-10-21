<?php


if (!defined('IN_IA')) {
    exit('Access Denied');
}
function m($name = '')
{
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $model = D_CORE . "model/" . strtolower($name) . '.php';
    if (!is_file($model)) {
		
        die(' Model ' . $name . ' Not Found!');
    }
	
    require $model;
	
    $class_name      = 'Dy_yy_' . ucfirst($name);
    $_modules[$name] = new $class_name();
	
    return $_modules[$name];
}
function p($name = '')
{
    if ($name != 'perm' && !IN_MOBILE) {
        static $_perm_model;
        if (!$_perm_model) {
            $perm_model_file = D_PLUGIN . 'perm/model.php';
            if (is_file($perm_model_file)) {
                require $perm_model_file;
                $perm_class_name = 'PermModel';
                $_perm_model     = new $perm_class_name('perm');
            }
        }
        if ($_perm_model) {
            if (!$_perm_model->check_plugin($name)) {
                return false;
            }
        }
    }
    static $_plugins = array();
    if (isset($_plugins[$name])) {
        return $_plugins[$name];
    }
    $model = Dy_yy_PLUGIN . strtolower($name) . '/model.php';
    if (!is_file($model)) {
        return false;
    }
    require $model;
    $class_name      = ucfirst($name) . 'Model';
    $_plugins[$name] = new $class_name($name);
    return $_plugins[$name];
}
function byte_format($input, $dec = 0)
{
    $prefix_arr = array(
        ' B',
        'K',
        'M',
        'G',
        'T'
    );
    $value      = round($input, $dec);
    $i          = 0;
    while ($value > 1024) {
        $value /= 1024;
        $i++;
    }
    $return_str = round($value, $dec) . $prefix_arr[$i];
    return $return_str;
}
function save_media($url)
{
    load()->func('file');
    $config = array(
        'qiniu' => false
    );
    $plugin = p('qiniu');
    if ($plugin) {
        $config = $plugin->getConfig();
        if ($config) {
            if (strexists($url, $config['url'])) {
                return $url;
            }
            $qiniu_url = $plugin->save(tomedia($url), $config);
            if (empty($qiniu_url)) {
                return $url;
            }
            return $qiniu_url;
        }
        return $url;
    }
    return $url;
}
function save_remote($url)
{
}
function is_array2($array)
{
    if (is_array($array)) {
        foreach ($array as $k => $v) {
            return is_array($v);
        }
        return false;
    }
    return false;
}
function set_medias($list = array(), $fields = null)
{
    if (empty($fields)) {
        foreach ($list as &$row) {
            $row = tomedia($row);
        }
        return $list;
    }
    if (!is_array($fields)) {
        $fields = explode(',', $fields);
    }
    if (is_array2($list)) {
        foreach ($list as $key => &$value) {
            foreach ($fields as $field) {
                if (isset($list[$field])) {
                    $list[$field] = tomedia($list[$field]);
                }
                if (is_array($value) && isset($value[$field])) {
                    $value[$field] = tomedia($value[$field]);
                }
            }
        }
        return $list;
    } else {
        foreach ($fields as $field) {
            if (isset($list[$field])) {
                $list[$field] = tomedia($list[$field]);
            }
        }
        return $list;
    }
}
function get_last_day($year, $month)
{
    return date('t', strtotime("{$year}-{$month} -1"));
}
function show_message($msg = '', $url = '', $type = 'success')
{
    $scripts = "<script language='javascript'>require(['core'],function(core){ core.message('" . $msg . "','" . $url . "','" . $type . "')})</script>";
    die($scripts);
}
function show_json($status = 1, $return = null)
{
    $ret = array(
        'status' => $status
    );
    if ($return) {
        $ret['result'] = $return;
    }
    die(json_encode($ret));
}
function is_weixin()
{
    if (empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false) {
        return false;
    }
    return true;
}
function b64_encode($obj)
{
    if (is_array($obj)) {
        return urlencode(base64_encode(json_encode($obj)));
    }
    return urlencode(base64_encode($obj));
}
function b64_decode($str, $is_array = true)
{
    $str = base64_decode(urldecode($str));
    if ($is_array) {
        return json_decode($str, true);
    }
    return $str;
}
function create_image($img)
{
    $ext = strtolower(substr($img, strrpos($img, '.')));
    if ($ext == '.png') {
        $thumb = imagecreatefrompng($img);
    } else if ($ext == '.gif') {
        $thumb = imagecreatefromgif($img);
    } else {
        $thumb = imagecreatefromjpeg($img);
    }
    return $thumb;
}
function get_authcode()
{
    $auth = get_auth();
    return empty($auth['code']) ? '' : $auth['code'];
}
function get_auth()
{
    global $_W;
    $set  = pdo_fetch('select sets from ' . tablename('Dy_yy_sysset') . ' order by id asc limit 1');
    $sets = iunserializer($set['sets']);
    if (is_array($sets)) {
        return is_array($sets['auth']) ? $sets['auth'] : array();
    }
    return array();
}
function check_shop_auth($url = '', $type = 's')
{
    global $_W, $_GPC;
    if ($_W['ispost'] && $_GPC['do'] != 'auth') {
        $auth = get_auth();
        load()->func('communication');
        $domain  = $_SERVER['HTTP_HOST'];
        $ip      = gethostbyname($domain);
        $setting = setting_load('site');
        $id      = isset($setting['site']['key']) ? $setting['site']['key'] : '0';
        return;
        if (empty($type) || $type == 's') {
            $post_data = array(
                'type' => $type,
                'ip' => $ip,
                'id' => $id,
                'code' => $auth['code'],
                'domain' => $domain
            );
        } else {
            $post_data = array(
                'type' => 'm',
                'm' => $type,
                'ip' => $ip,
                'id' => $id,
                'code' => $auth['code'],
                'domain' => $domain
            );
        }
        $resp   = ihttp_post($url, $post_data, array(), 3);
	if (is_error($resp) && strexists($resp["message"], "timed out")) {
            return;
        }
        $status = $resp['content'];
        if ($status != '1') {
            message(base64_decode('6K+35Yiw5b6u6LWe5a6Y5pa56LSt5LmwLeWkp+aVsOaNruWVhuWfjuaooeWdly1iYnMuMDEyd3ouY29tIQ=='), '', 'error');
        }
    }
}
$my_scenfiles = array();
function my_scandir($dir)
{
    global $my_scenfiles;
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false) {
            if ($file != ".." && $file != ".") {
                if (is_dir($dir . "/" . $file)) {
                    my_scandir($dir . "/" . $file);
                } else {
                    $my_scenfiles[] = $dir . "/" . $file;
                }
            }
        }
        closedir($handle);
    }
}
function shop_template_compile($from, $to, $inmodule = false)
{
    $path = dirname($to);
    if (!is_dir($path)) {
        load()->func('file');
        mkdirs($path);
    }
    $content = shop_template_parse(file_get_contents($from), $inmodule);
    if (IMS_FAMILY == 'x' && !preg_match('/(footer|header|account\/welcome|login|register)+/', $from)) {
        $content = str_replace('微赞', '系统', $content);
    }
    file_put_contents($to, $content);
}
function shop_template_parse($str, $inmodule = false)
{
    $str = template_parse($str, $inmodule);
    $str = preg_replace('/{ifp\s+(.+?)}/', '<?php if(cv($1)) { ?>', $str);
    $str = preg_replace('/{ifpp\s+(.+?)}/', '<?php if(cp($1)) { ?>', $str);
    $str = preg_replace('/{ife\s+(\S+)\s+(\S+)}/', '<?php if( ce($1 ,$2) ) { ?>', $str);
    return $str;
}
function ce($permtype = '', $item = null)
{
    $perm = p('perm');
    if ($perm) {
        return $perm->check_edit($permtype, $item);
    }
    return true;
}
function cv($permtypes = '')
{
    $perm = p('perm');
    if ($perm) {
        return $perm->check_perm($permtypes);
    }
    return true;
}
function ca($permtypes = '')
{
    if (!cv($permtypes)) {
        message('您没有权限操作，请联系管理员!', '', 'error');
    }
}
function cp($pluginname = '')
{
    $perm = p('perm');
    if ($perm) {
        return $perm->check_plugin($pluginname);
    }
    return true;
}
function cpa($pluginname = '')
{
    if (!cp($pluginname)) {
        message('您没有权限操作，请联系管理员!', '', 'error');
    }
}
function plog($type = '', $op = '')
{
    $perm = p('perm');
    if ($perm) {
        $perm->log($type, $op);
    }
}
function filter_dangerous_words($str){
	$str = str_replace("'", "‘", $str);
	$str = str_replace("\"", "“", $str);
	$str = str_replace("<", "《", $str);
	$str = str_replace(">", "》", $str);
	return $str;
}
function createordernum(){
	  //订购日期
 
  $order_date = date('Y-m-d');
 
  //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
 
  $order_id_main = date('YmdHis') . rand(10000000,99999999);
 
  //订单号码主体长度
 
  $order_id_len = strlen($order_id_main);
 
  $order_id_sum = 0;
 
  for($i=0; $i<$order_id_len; $i++){
 
  $order_id_sum += (int)(substr($order_id_main,$i,1));
 
  }

  //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
 
  $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
  
  return $order_id;
}
//二维码请求函数
function api_notice_increment($url, $data){
    $ch = curl_init();
    $header = "Accept-Charset: utf-8";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tmpInfo = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close( $ch );
        return $ch;
    }else{
        curl_close( $ch );
        return $tmpInfo;
    }

}

function _calc_current_frames2(&$frames) {
    global $_W,$_GPC;
    if(!empty($frames) && is_array($frames)) {
        foreach($frames as &$frame) {
            foreach($frame['items'] as &$fr) {
                if(count($fr['actions']) == 2){
                    if($fr['actions']['1'] == $_GPC[$fr['actions']['0']]){
                        $fr['active'] = 'active';
                    }
                }elseif(count($fr['actions']) == 4){
                    if($fr['actions']['1'] == $_GPC[$fr['actions']['0']] && $fr['actions']['3'] == $_GPC[$fr['actions']['2']]){
                        $fr['active'] = 'active';
                    }
                }else{
                    $query = parse_url($fr['url'], PHP_URL_QUERY);
                    parse_str($query, $urls);
                    if(defined('ACTIVE_FRAME_URL')) {
                        $query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
                        parse_str($query, $get);
                    } else {
                        $get = $_GET;
                    }
                    if(!empty($_GPC['a'])) {
                        $get['a'] = $_GPC['a'];
                    }
                    if(!empty($_GPC['c'])) {
                        $get['c'] = $_GPC['c'];
                    }
                    if(!empty($_GPC['do'])) {
                        $get['do'] = $_GPC['do'];
                    }
                    if(!empty($_GPC['ac'])) {
                        $get['ac'] = $_GPC['ac'];
                    }
                    if(!empty($_GPC['status'])) {
                        $get['status'] = $_GPC['status'];
                    }
                    if(!empty($_GPC['op'])) {
                        $get['op'] = $_GPC['op'];
                    }
                    if(!empty($_GPC['m'])) {
                        $get['m'] = $_GPC['m'];
                    }
                    $diff = array_diff_assoc($urls, $get);
                
                    if(empty($diff)) {
                        $fr['active'] = 'active';
                    }else{
                        $fr['active'] = '';
                    }
                }
            }
        }
    }
}

