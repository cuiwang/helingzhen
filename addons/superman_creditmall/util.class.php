<?php
class SupermanUtil
{
    public static function money_format($number, $places = 2, $symbol = '&#165;', $thousand = ',', $decimal = '.')
    {
        return $symbol . number_format($number, $places, $decimal, $thousand);
    }
    public static function attachment_path()
    {
        global $_W;
        if (!defined('ATTACHMENT_ROOT')) {
            $path = IA_ROOT . '/attachment/';
            define('ATTACHMENT_ROOT', $path);
            return $path;
        }
        if (substr(ATTACHMENT_ROOT, -1, 1) != '/') {
            return ATTACHMENT_ROOT . '/';
        }
        return ATTACHMENT_ROOT;
    }
    public static function img_placeholder($returnsrc = true)
    {
        global $_W;
        $src = $_W['siteroot'] . "addons/superman_creditmall/template/mobile/images/placeholder.gif";
        if ($returnsrc) {
            return $src;
        } else {
            return "<img src='{$src}'/>";
        }
    }
    public static function hide_mobile($mobile)
    {
        return preg_replace('/(\d{3})(\d{4})/', "$1****", $mobile);
    }
    public static function hide_nickname($nickname, $length = 1, $suffix = '**')
    {
        return cutstr($nickname, $length) . $suffix;
    }
    public static function hide_password($password, $suffix = '***')
    {
        $firstStr = mb_substr($password, 0, 1, 'utf-8');
        $lastStr  = mb_substr($password, -1, 1, 'utf-8');
        return $firstStr . $suffix . $lastStr;
    }
    public static function random_float($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
    public static function fix_path($path)
    {
        global $_W;
        $path = strpos($path, 'http://') !== false || strpos($path, 'https://') !== false ? str_replace($_W['attachurl'], '', $path) : $path;
        $path = strpos($path, 'http://') !== false || strpos($path, 'https://') !== false ? str_replace($_W['siteroot'], '', $path) : $path;
        return $path;
    }
    public static function sort_displayorder_desc($m1, $m2)
    {
        if ($m1['displayorder'] == $m2['displayorder']) {
            return 0;
        }
        return ($m1['displayorder'] < $m2['displayorder']) ? 1 : -1;
    }
    public static function sort_displayorder_asc($m1, $m2)
    {
        if ($m1['displayorder'] == $m2['displayorder']) {
            return 0;
        }
        return ($m1['displayorder'] < $m2['displayorder']) ? -1 : 1;
    }
    public static function get_skey($name, $params = null)
    {
        if (strpos($name, ':%') !== false) {
            if (is_array($params)) {
                while ($params) {
                    $name = sprintf($name, array_shift($params));
                }
            } else {
                $name = sprintf($name, $params);
            }
        }
        return $name;
    }
    public static function qrcode_png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 10, $margin = 4, $saveandprint = false)
    {
        include_once(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
        QRcode::png($text, $outfile, $level, $size, $margin, $saveandprint);
    }
    public static function uid2openid($uid)
    {
        $fans = mc_fansinfo($uid);
        return $fans && $fans['openid'] ? $fans['openid'] : '';
    }
    public static function get_thumb_filename($filename, $thumb_suffix = '_thumb')
    {
        $arr = explode('.', $filename);
        return $arr[0] . $thumb_suffix . '.' . $arr[1];
    }
    public static function ip_access($ip, $accesslist)
    {
        return preg_match("/^(" . str_replace(array(
            "\r\n",
            ' '
        ), array(
            '|',
            ''
        ), preg_quote($accesslist, '/')) . ")/", $ip);
    }
    public static function ip2location($ip)
    {
        $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $ip;
        load()->func('communication');
        $result = ihttp_get($url);
        if (is_error($result)) {
            return array();
        }
        $location = @json_decode($result['content'], true);
        return $location ? array(
            'country' => $location['country'],
            'province' => $location['province'],
            'city' => $location['city'],
            'district' => $location['district'],
            'isp' => $location['isp']
        ) : array();
    }
    public static function get_sign($params = array(), $key = '')
    {
        sort($params);
        $str = http_build_query($params);
        $str .= '&key=' . $key;
        return sha1($str);
    }
    public static function get_file_lines($filename)
    {
        $lines = 0;
        $fp    = @fopen($filename, 'r');
        if ($fp) {
            while (!feof($fp) && stream_get_line($fp, 8192, "\r\n") !== false) {
                $lines++;
            }
            @fclose($fp);
        }
        return $lines;
    }
    public static function is_we7_encrypt($filename)
    {
        $content = file_get_contents($filename);
        return strstr($content, 'return;?>') !== false && substr($content, 5, 8) == ' define(' ? true : false;
    }
}

?>