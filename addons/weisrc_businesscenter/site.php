<?php
/**
 * 微商圈
 */
defined('IN_IA') or exit('Access Denied');
define(EARTH_RADIUS, 6371); //地球半径，平均半径为6371km
define('RES', '../addons/weisrc_businesscenter/template');
define('RES3', '../addons/weisrc_businesscenter/template/mobile/style3/themes/');
include "../addons/weisrc_businesscenter/model.php";

class weisrc_businesscenterModuleSite extends WeModuleSite
{
    //模块标识
    public $modulename = 'weisrc_businesscenter';
    public $cur_tpl = 'style1';
    public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';
    public $_account = '';

    public $_debug = '1'; //default:0
    public $_weixin = '0'; //default:1

    public $_weid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';

    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';

    public $_lat = '';
    public $_lng = '';

    public $table_feedback = "weisrc_businesscenter_feedback";
    public $table_category = "weisrc_businesscenter_category";
    public $table_city = "weisrc_businesscenter_city";
    public $table_stores = "weisrc_businesscenter_stores";
    public $table_slide = "weisrc_businesscenter_slide";
    public $table_setting = "weisrc_businesscenter_setting";
    public $table_news = "weisrc_businesscenter_news";
    public $table_area = "weisrc_businesscenter_area";

    public $actions_titles = array(
        'stores' => '商家管理',
        'feedback' => '留言管理',
        'category' => '分类管理',
        'area' => '区域管理',
        'slide' => '广告管理',
        'news' => '优惠资讯',
        'template' => '模版管理',
        'setting' => '系统设置'
    );

    function __construct()
    {
        global $_GPC, $_W;
        $this->_weid = $_W['uniacid'];
        $this->_fromuser = $_W['fans']['from_user'];//debug
        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $_W['account']['level']; //是否为高级号

        $this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
        $this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];

        $this->_lat = 'lat_' . $this->_weid;
        $this->_lng = 'lng_' . $this->_weid;

        if (isset($_COOKIE[$this->_auth2_openid])) {
            $this->_fromuser = $_COOKIE[$this->_auth2_openid];
        }

        if ($this->_accountlevel < 4) {
            $setting = uni_setting($this->_weid);
            $oauth = $setting['oauth'];
            if (!empty($oauth) && !empty($oauth['account'])) {
                $this->_account = account_fetch($oauth['account']);
                $this->_appid = $this->_account['key'];
                $this->_appsecret = $this->_account['secret'];
            }
        } else {
            $this->_appid = $_W['account']['key'];
            $this->_appsecret = $_W['account']['secret'];
        }

        $template = pdo_fetch("SELECT * FROM " . tablename($this->table_template) . " WHERE weid = :weid", array(':weid' => $this->_weid));
        if (!empty($template)) {
            $this->cur_tpl = $template['template_name'];
        }
    }

    /*
    ** 设置切换导航
    */
    public function set_tabbar($action, $storeid = 0)
    {
        $actions_titles = $this->actions_titles;
        $html = '<ul class="nav nav-tabs">';
        foreach ($actions_titles as $key => $value) {
            if ($storeid == 0) {
                $url = $this->createWebUrl($key, array('op' => 'display'));
            } else {
                $url = $this->createWebUrl($key, array('op' => 'display', 'storeid' => $storeid));
            }

            $html .= '<li class="' . ($key == $action ? 'active' : '') . '"><a href="' . $url . '">' . $value . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    //网站入口
    public function doMobileIndex2()
    {
        include $this->template($this->cur_tpl . '/smart/index');
    }

    public function doMobileDebugCode()
    {
        if (isset($_GPC['code'])) {
            $userinfo = $this->getUserInfo($_GPC['code']);
            if (!empty($userinfo)) {
                echo $userinfo["nickname"] . '<br/>';
                $headimgurl = $userinfo["headimgurl"];
                echo "<img src='{$headimgurl}'/>";
                //message($userinfo["nickname"]);
            } else {
                message('调试中勿扰...');
            }
        }
    }

    public function domobileversion()
    {
        message($this->curversion);
    }

    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     *  易 福 源 码 网 
     * @param lng float 经度
     * @param lat float 纬度
     * @param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     * @return array 正方形的四个点的经纬度坐标
     */
    public $curversion = '';
    public function squarePoint($lng, $lat, $distance = 0.5)
    {

        $dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance / EARTH_RADIUS;
        $dlat = rad2deg($dlat);

        return array(
            'left-top' => array('lat' => $lat + $dlat, 'lng' => $lng - $dlng),
            'right-top' => array('lat' => $lat + $dlat, 'lng' => $lng + $dlng),
            'left-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng - $dlng),
            'right-bottom' => array('lat' => $lat - $dlat, 'lng' => $lng + $dlng)
        );
    }

    public function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $radLat1 = $lat1 * M_PI / 180;
        $radLat2 = $lat2 * M_PI / 180;
        $a = $lat1 * M_PI / 180 - $lat2 * M_PI / 180;
        $b = $lng1 * M_PI / 180 - $lng2 * M_PI / 180;

        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * EARTH_RADIUS;
        $s = round($s * 1000);
        if ($len_type > 1) {
            $s /= 1000;
        }
        $s /= 1000;
        return round($s, $decimal);
    }

    public $table_template = "weisrc_businesscenter_template";

    public function insert_default_category($name, $logo, $parent_name = '', $isfirst = 0)
    {
        global $_GPC, $_W;
        $path = '../addons/weisrc_businesscenter/template/themes/images/';
        $path = $path . 'icon_' . $logo . '.png';

        $category_parent = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE name = :name AND weid=:weid AND parentid=0", array(':name' => $parent_name, ':weid' => $_W['uniacid']));

        $parentid = intval($category_parent['id']);

        $data = array(
            'weid' => $_W['uniacid'],
            'name' => $name,
            'logo' => $path,
            'displayorder' => 0,
            'isfirst' => $isfirst,
            'parentid' => $parentid,
        );

        $category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE name = :name AND weid=:weid", array(':name' => $name, ':weid' => $_W['uniacid']));

        if (empty($category)) {
            pdo_insert($this->table_category, $data);
        }
        return pdo_insertid();
    }

    public function checkDatetime($str, $format = "H:i")
    {
        $str_tmp = date('Y-m-d') . ' ' . $str;
        $unixTime = strtotime($str_tmp);
        $checkDate = date($format, $unixTime);
        if ($checkDate == $str) {
            return 1;
        } else {
            return 0;
        }
    }

    public function doWebDelete()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $type = $_GPC['type'];
        $id = intval($_GPC['id']);
        if ($type == 'photo') {
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_slide) . " WHERE id = :id AND weid=:weid", array(':id' => $id, ':weid' => $weid));
                if (empty($item)) {
                    message('图片不存在或是已经被删除！');
                }
                pdo_delete($this->table_slide, array('id' => $item['id'], 'weid' => $weid));
            } else {
                $item['attachment'] = $_GPC['attachment'];
            }

        }
        message('删除成功！', referer(), 'success');
    }

    public function message($error, $url = '', $errno = -1)
    {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }

    function thumn($background, $width, $height, $newfile)
    {
        list($s_w, $s_h) = getimagesize($background); //获取原图片高度、宽度
//        if ($width && ($s_w < $s_h)) {
//            $width = ($height / $s_h) * $s_w;
//        } else {
//            $height = ($width / $s_w) * $s_h;
//        }
        $new = imagecreatetruecolor($width, $height);
        $img = imagecreatefromjpeg($background);
        imagecopyresampled($new, $img, 0, 0, 0, 0, $width, $height, $s_w, $s_h);
        imagejpeg($new, $newfile);
        imagedestroy($new);
        imagedestroy($img);
    }

    //上传图片(裁剪)
    public function doWebUploadPhoto()
    {
        global $_W, $_GPC;
        $weid = intval($_W['uniacid']);
        if (!empty($_FILES['imgFile']['name'])) {
            if ($_FILES['imgFile']['error'] != 0) {
                $result['message'] = '上传失败，请重试！';
                exit(json_encode($result));
            }
            $_W['uploadsetting'] = array();
            $_W['uploadsetting']['image']['folder'] = 'images/' . $_W['uniacid'];
            $_W['uploadsetting']['image']['extentions'] = $_W['config']['upload']['image']['extentions'];
            $_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
            $file = file_upload($_FILES['imgFile'], 'image');
            if (is_error($file)) {
                $result['message'] = $file['message'];
                exit(json_encode($result));
            }
            $result['url'] = $file['url'];
            $result['error'] = 0;
            $result['filename'] = $file['path'];
            $result['url'] = $_W['attachurl'] . $result['filename'];
            $oldfile = $_W['attachurl'] . $result['filename'];

            $pos = strrpos($oldfile, '/'); //寻找位置
            if ($pos) $newpath = substr($oldfile, 0, $pos); //删除后面

            $thum = str_replace(".", "thum.", $result['filename']);
            $result['filename'] = $thum;
            $result['url'] = $_W['attachurl'] . $result['filename'];
            pdo_insert('attachment', array(
                'weid' => $_W['uniacid'],
                'uid' => $_W['uid'],
                'filename' => $_FILES['imgFile']['name'],
                'attachment' => $thum,
                'type' => 1,
                'createtime' => TIMESTAMP,
            ));

            $folder = "resource/attachment/";

            //imagetype
            $width = 300;
            $height = 150;
            $imagetype = $_GPC['imagetype'];
            if ($imagetype == 'store') {
                $width = 300;
                $height = 150;
            } else if ($imagetype == 'category') {
                $width = 134;
                $height = 134;
            } else if ($imagetype == 'childcategory') {
                $width = 120;
                $height = 90;
            }

            $this->thumn($oldfile, $width, $height, $folder . $thum);

            $result['message'] = '上传成功！';
            exit(json_encode($result));
        }
    }

    public function setUserInfo()
    {
        load()->model('mc');
        $userinfo = mc_oauth_userinfo();
        if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['avatar'])) {
            $headimgurl = $userinfo['avatar'];
            $gender = $userinfo['sex'];
            $nickname = $userinfo['nickname'];
            $openid = $userinfo['openid'];

            $time = TIMESTAMP + 3600 * 24;
            setcookie($this->_auth2_headimgurl, $headimgurl, $time);
            setcookie($this->_auth2_nickname, $nickname, $time);
            setcookie($this->_auth2_openid, $openid, $time);
            setcookie($this->_auth2_sex, $gender, $time);
        }
        return $userinfo;
    }
}