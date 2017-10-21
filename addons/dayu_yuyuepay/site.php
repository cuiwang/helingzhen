<?php
define('TEMPLATE_PATH', '../addons/dayu_yuyuepay/template/style/');
define('TEMPLATE_WEUI', '../addons/dayu_yuyuepay/template/weui/');
define('MUI', '../addons/dayu_yuyuepay/template/mui/');
defined('IN_IA') or exit('Access Denied');
class dayu_yuyuepayModuleSite extends WeModuleSite
{
    private $tb_yuyue = 'dayu_yuyuepay';
    private $tb_data = 'dayu_yuyuepay_data';
    private $tb_field = 'dayu_yuyuepay_fields';
    private $tb_reply = 'dayu_yuyuepay_reply';
    private $tb_info = 'dayu_yuyuepay_info';
    private $tb_record = 'dayu_yuyuepay_record';
    private $tb_staff = 'dayu_yuyuepay_staff';
    private $tb_item = 'dayu_yuyuepay_xiangmu';
    private $tb_role = 'dayu_yuyuepay_role';
    private $tb_category = 'dayu_yuyuepay_category';
    private $tb_slide = 'dayu_yuyuepay_slide';
    private $tb_group = 'dayu_yuyuepay_group';
    public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';
    public $_account = '';
    public $_weid = '';
    public $_openid = '';
    public $_nickname = '';
    public $_headimgurl = '';
    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';
    function __construct()
    {
        global $_W, $_GPC;
        load()->model('mc');
        $this->_openid           = $_W['openid'];
        $this->_weid             = $_W['uniacid'];
        $account                 = $_W['account'];
        $this->_auth2_openid     = 'auth2_openid_' . $_W['uniacid'];
        $this->_auth2_nickname   = 'auth2_nickname_' . $_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];
        $this->_appid            = $_W['account']['key'];
        $this->_appsecret        = $_W['account']['secret'];
        $this->_accountlevel     = $account['level'];
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $this->_openid = $_COOKIE[$this->_auth2_openid];
        }
        if ($this->_accountlevel < 4) {
            $settings = uni_setting($this->_weid);
            $oauth    = $settings['oauth'];
            if (!empty($oauth) && !empty($oauth['account'])) {
                $this->_account   = account_fetch($oauth['account']);
                $this->_appid     = $this->_account['key'];
                $this->_appsecret = $this->_account['secret'];
            }
        } else {
            $this->_appid     = $_W['account']['key'];
            $this->_appsecret = $_W['account']['secret'];
        }
    }
    public function oauth2($url)
    {
        global $_GPC, $_W;
        load()->func('communication');
        $code = $_GPC['code'];
        if (empty($code)) {
            $this->showMessage('code获取失败.');
        }
        $token     = $this->get_Authorization_Code($code, $url);
        $from_user = $token['openid'];
        $userinfo  = $this->get_User_Info($from_user);
        $state     = 1;
        if ($userinfo['subscribe'] == 0) {
            $state   = 0;
            $authkey = intval($_GPC['authkey']);
            if ($authkey == 0) {
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
            $userinfo = $this->get_User_Info($from_user, $token['access_token']);
        }
        if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
            echo '<h1>获取微信公众号授权失败[无法取得粉丝信息], 请稍后重试！ 公众平台返回原始数据: <br />' . $state . $userinfo['meta'] . '<h1>';
            exit();
        }
        setcookie($this->_auth2_headimgurl, $userinfo['headimgurl'], time() + 3600 * 24);
        setcookie($this->_auth2_nickname, $userinfo['nickname'], time() + 3600 * 24);
        setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
        setcookie($this->_auth2_sex, $userinfo['sex'], time() + 3600 * 24);
        return $userinfo;
    }
    public function get_Access_Token()
    {
        global $_W;
        $account = $_W['account'];
        if ($this->_accountlevel < 4) {
            if (!empty($this->_account)) {
                $account = $this->_account;
            }
        }
        load()->classs('weixin.account');
        $accObj       = WeixinAccount::create($account['acid']);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }
    public function get_Authorization_Code($code, $url)
    {
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
        $error       = ihttp_get($oauth2_code);
        $token       = @json_decode($error['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            $oauth2_code = urlencode($url);
            header("location:$oauth2_code");
            echo '微信授权失败! 公众平台返回原始数据: <br>' . $error['meta'];
            exit;
        }
        return $token;
    }
    public function get_User_Info($from_user, $ACCESS_TOKEN = '')
    {
        if ($ACCESS_TOKEN == '') {
            $ACCESS_TOKEN = $this->get_Access_Token();
            $url          = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        } else {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        }
        $json     = ihttp_get($url);
        $userinfo = @json_decode($json['content'], true);
        return $userinfo;
    }
    public function get_Code($url)
    {
        global $_W;
        $url         = urlencode($url);
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
        header("location:$oauth2_code");
    }
    public function getMenus2()
    {
        $menus = array(
            array(
                'title' => '新建预约主题',
                'url' => $this->createWebUrl('post'),
                'icon' => 'fa fa-plus-square'
            ),
            array(
                'title' => '预约主题列表',
                'url' => $this->createWebUrl('display'),
                'icon' => 'fa fa-list'
            ),
            array(
                'title' => '预约记录管理',
                'url' => $this->createWebUrl('manage'),
                'icon' => 'fa fa-clock-o'
            ),
            array(
                'title' => '幻灯片管理',
                'url' => $this->createWebUrl('slide'),
                'icon' => 'fa fa-photo'
            ),
            array(
                'title' => '客服管理',
                'url' => $this->createWebUrl('staff'),
                'icon' => 'fa fa-wechat'
            ),
            array(
                'title' => '统计',
                'url' => $this->createWebUrl('summary'),
                'icon' => 'fa fa-signal'
            )
        );
        return $menus;
    }
    public function getHomeTiles()
    {
        global $_W;
        $urls = array();
        $list = pdo_fetchall("SELECT title, reid FROM " . tablename($this->tb_yuyue) . " WHERE weid = '{$_W['uniacid']}'");
        if (!empty($list)) {
            foreach ($list as $row) {
                $urls[] = array(
                    'title' => $row['title'],
                    'url' => $_W['siteroot'] . "app/" . $this->createMobileUrl('dayu_yuyuepay', array(
                        'id' => $row['reid']
                    ))
                );
            }
        }
        return $urls;
    }
    public function __web($f_name)
    {
        global $_W, $_GPC;
        checklogin();
        require 'fans.web.php';
        $id = $_GPC['id'];
        load()->func('tpl');
        $op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
        include_once 'web/' . strtolower(substr($f_name, 5)) . '.php';
    }
    public function doWebSummary()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebQuery()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $kwd              = $_GPC['keyword'];
        $sql              = 'SELECT * FROM ' . tablename($this->tb_yuyue) . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY reid DESC LIMIT 0,8';
        $params           = array();
        $params[':weid']  = $_W['uniacid'];
        $params[':title'] = "%{$kwd}%";
        $ds               = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r                = array();
            $r['title']       = $row['title'];
            $r['description'] = cutstr(strip_tags($row['description']), 50);
            $r['thumb']       = $row['thumb'];
            $r['reid']        = $row['reid'];
            $row['entry']     = $r;
        }
        include $this->template('query');
    }
    public function doWebCategory()
    {
        global $_GPC, $_W;
        require 'fans.web.php';
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $pindex   = max(1, intval($_GPC['page']));
            $psize    = 15;
            $category = pdo_fetchall("select * from " . tablename($this->tb_category) . " where weid = :weid ORDER BY id DESC", array(
                ':weid' => $weid
            ));
            $total    = pdo_fetchcolumn("SELECT count(id) FROM " . tablename($this->tb_category) . " where weid = :weid ORDER BY id DESC", array(
                ':weid' => $weid
            ));
            $pager    = pagination($total, $pindex, $psize);
            foreach ($category AS $key => $val) {
                if (is_file('../addons/dayu_yuyuepay/QRcode/' . $weid . '/' . $val['id'] . '.png')) {
                    $category[$key]['qr'] = '<a href="' . MODULE_URL . 'QRcode/' . $weid . '/' . $val['id'] . '.png" target="_blank"><img src="' . MODULE_URL . 'QRcode/' . $weid . '/' . $val['id'] . '.png" data-toggle="tooltip" data-placement="bottom" class="btn btn-xs" title="点击查看二维码" class="media-object" style="width:40px;"></a>';
                }
                $category[$key]['link']  = murl('entry', array(
                    'do' => 'list',
                    'id' => $val['id'],
                    'm' => 'dayu_yuyuepay'
                ), true, true);
                $category[$key]['color'] = !empty($val['color']) ? iunserializer($val['color']) : '';
            }
        } elseif ($operation == 'post') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $cate  = pdo_get($this->tb_category, array(
                    'weid' => $weid,
                    'id' => $id
                ), array());
                $color = !empty($cate['color']) ? iunserializer($cate['color']) : '';
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('抱歉，请输入分类名称！');
                }
                $data = array(
                    'weid' => $weid,
                    'title' => $_GPC['title'],
                    'list' => $_GPC['list']
                );
                if (!empty($_GPC['thumb'])) {
                    $data['icon'] = $_GPC['thumb'];
                    load()->func('file');
                    file_delete($_GPC['thumb-old']);
                }
                $color         = array(
                    'nav_index' => $_GPC['nav_index'],
                    'nav_page' => $_GPC['nav_page'],
                    'nav_btn' => $_GPC['nav_btn']
                );
                $data['color'] = iserializer($color);
                if (!empty($id)) {
                    pdo_update($this->tb_category, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->tb_category, $data);
                    $id = pdo_insertid();
                }
                message('更新分类成功！', $this->createWebUrl('category', array(
                    'op' => 'display'
                )), 'success');
            }
        } elseif ($operation == 'delete') {
            $id       = intval($_GPC['id']);
            $category = pdo_fetch("SELECT * FROM " . tablename($this->tb_category) . " WHERE id = '$id'");
            if (empty($category)) {
                message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array(
                    'op' => 'display'
                )), 'error');
            }
            if (pdo_delete($this->tb_category, array(
                'id' => $id
            )) === false) {
                message('删除分类失败, 请稍后重试.');
                exit();
            }
            message('分类删除成功！', $this->createWebUrl('category', array(
                'op' => 'display'
            )), 'success');
        }
        include $this->template('category');
    }
    public function doWebQRcode()
    {
        global $_GPC, $_W;
        require 'fans.web.php';
        $id          = intval($_GPC['id']);
        $QRcode_path = "../addons/dayu_yuyuepay/QRcode/" . $weid . "/";
        load()->func('file');
        @mkdirs($QRcode_path);
        include "plugin/phpqrcode.php";
        $value                = $_W['siteroot'] . 'app/' . $this->createMobileUrl('list', array(
            'cateid' => $id
        ));
        $errorCorrectionLevel = "L";
        $matrixPointSize      = "20";
        $imgname              = $id . "s.png";
        $imgurl               = $QRcode_path . $imgname;
        QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize);
        $category = pdo_get($this->tb_category, array(
            'weid' => $weid,
            'id' => $id
        ), array());
        $logo     = tomedia($category['icon']);
        $QR       = $QRcode_path . $id . 's.png';
        if ($logo !== FALSE) {
            $QR             = imagecreatefromstring(file_get_contents($QR));
            $logo           = imagecreatefromstring(file_get_contents($logo));
            $QR_width       = imagesx($QR);
            $QR_height      = imagesy($QR);
            $logo_width     = imagesx($logo);
            $logo_height    = imagesy($logo);
            $logo_qr_width  = $QR_width / 5;
            $scale          = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width     = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        $qrname = $id . ".png";
        $qrurl  = $QRcode_path . $qrname;
        imagepng($QR, $qrurl, 9);
        load()->func('file');
        file_delete($imgurl);
        exit();
    }
    public function doWebDetail()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        load()->func('tpl');
        $rerid            = intval($_GPC['id']);
        $sql              = 'SELECT * FROM ' . tablename($this->tb_info) . " WHERE `rerid`=:rerid";
        $params           = array();
        $params[':rerid'] = $rerid;
        $info             = pdo_fetch($sql, $params);
        if (empty($info)) {
            message('访问非法.');
        }
        $hexiao   = "dayu_yuyuepay_shareQrcode" . $_W['uniacid'];
        $activity = $this->get_yuyuepay($info['reid']);
        $role     = $this->get_isrole($info['reid'], $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        $xm = $this->get_xiangmu($info['reid'], $info['xmid']);
        if (empty($activity)) {
            message('非法访问.');
        }
        $par    = iunserializer($activity['par']);
        $status = $this->get_status($info['reid'], $info['status']);
        $state  = array();
        $arr2   = array(
            '0',
            '1',
            '2',
            '3',
            '8',
            '7'
        );
        foreach ($arr2 as $index => $v) {
            $state[$v][] = $this->get_status($info['reid'], $v);
        }
        $sql             = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY `refid`';
        $params          = array();
        $params[':reid'] = $info['reid'];
        $fields          = pdo_fetchall($sql, $params);
        if (empty($fields)) {
            message('非法访问.');
        }
        $ds = $fids = array();
        foreach ($fields as $f) {
            $ds[$f['refid']]['fid']   = $f['title'];
            $ds[$f['refid']]['type']  = $f['type'];
            $ds[$f['refid']]['refid'] = $f['refid'];
            $fids[]                   = $f['refid'];
        }
        $record           = array();
        $record['status'] = $_GPC['status'];
        if (!empty($_GPC['paystatus'])) {
            $record['paystatus'] = intval($_GPC['paystatus']);
        }
        if ($activity['is_time'] == 0) {
            $record['yuyuetime'] = strtotime($_GPC['yuyuetime']);
        }
        $record['kfinfo'] = $_GPC['kfinfo'];
        if (is_array($_GPC['thumb'])) {
            $record['thumb'] = serialize($_GPC['thumb']);
        }
        if ($setting['store'] == 1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($info['sid'])) {
            $store              = $this->get_store($info['sid']);
            $store['score_num'] = $store['score_num'] == 0 ? 5 : round(($store['total_score'] / $store['score_num']), 0);
        }
        if ($_W['ispost']) {
            $kfinfo  = !empty($_GPC['kfinfo']) ? "\n客服回复：" . $_GPC['kfinfo'] : "";
            $status  = $this->get_status($info['reid'], $_GPC['status']);
            $getxm   = $this->get_xiangmu($info['reid'], $info['xmid']);
            $xiangmu = $activity['is_num'] == 1 ? $getxm['title'] . " - " . $getxm['price'] . "元 * " . $num : $getxm['title'] . " - " . $getxm['price'] . "元";
            if ($activity['is_time'] == 0) {
                $times = date('Y-m-d H:i:s', $record['yuyuetime']);
            } elseif ($activity['is_time'] == 2) {
                $times = $info['restime'];
            }
            $url  = $_W['siteroot'] . 'app/' . $this->createMobileUrl('detail', array(
                'reid' => $info['reid'],
                'id' => $info['rerid']
            ));
            $data = array(
                'first' => array(
                    'value' => $par['mfirst'] . "\n",
                    'color' => "#743A3A"
                ),
                'keyword1' => array(
                    'value' => $info['member']
                ),
                'keyword2' => array(
                    'value' => $xiangmu
                ),
                'keyword3' => array(
                    'value' => $times
                ),
                'keyword4' => array(
                    'value' => $status['name']
                ),
                'remark' => array(
                    'value' => $kfinfo . "\n" . $activity['mfoot'],
                    'color' => "#008000"
                )
            );
            if ($par['sms'] != '0' && !empty($activity['smsid']) && $info['status'] == '0') {
                load()->func('communication');
                $content = $activity['title'] . '，' . $par['xmname'] . ':' . $xm['title'] . '，状态：' . $status['name'];
                ihttp_post(murl('entry', array(
                    'do' => 'Notice',
                    'id' => $activity['smsid'],
                    'm' => 'dayu_sms'
                ), true, true), array(
                    'mobile' => $info['mobile'],
                    'mname' => $info['member'],
                    'product' => $xiangmu,
                    'status' => $status['name']
                ));
            }
            if (!empty($activity['m_templateid'])) {
                $acc = WeAccount::create($_W['acid']);
                $acc->sendTplNotice($info['openid'], $activity['m_templateid'], $data, $url, "#FF0000");
            }
            $store_msg = '';
            if ($setting['store'] == 1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($info['sid']) && $_GPC['status'] == '3') {
                $store  = $this->get_store($info['sid']);
                $boss   = $this->get_store_boss($store['bid']);
                $paylog = pdo_get('dayu_yuyuepay_plugin_store_paylog', array(
                    'weid' => $_W['uniacid'],
                    'sid' => $info['sid'],
                    'bid' => $store['bid'],
                    'reid' => $info['reid'],
                    'yid' => $info['rerid']
                ), array());
                if (empty($paylog)) {
                    $paylogdata = array(
                        'weid' => $_W['uniacid'],
                        'sid' => $info['sid'],
                        'bid' => $store['bid'],
                        'reid' => $info['reid'],
                        'yid' => $info['rerid'],
                        'price' => $info['price'],
                        'createtime' => TIMESTAMP
                    );
                    pdo_insert('dayu_yuyuepay_plugin_store_paylog', $paylogdata);
                    pdo_update('dayu_yuyuepay_plugin_store_boss', array(
                        'money' => $boss['money'] + $paylogdata['price']
                    ), array(
                        'id' => $store['bid']
                    ));
                    $store_msg = '店主余额增加' . $paylogdata['price'] . '元，';
                }
            }
            pdo_update($this->tb_info, $record, array(
                'rerid' => $rerid
            ));
            message($store_msg . '修改成功', referer(), 'success');
        }
        $info['yuyuetime'] && $info['yuyuetime'] = date('Y-m-d H:i:s', $info['yuyuetime']);
        $thumb1 = unserialize($info['thumb']);
        $thumb  = array();
        if (is_array($thumb1)) {
            foreach ($thumb1 as $p) {
                $thumb[] = is_array($p) ? $p['attachment'] : $p;
            }
        }
        $fids           = implode(',', $fids);
        $info['fields'] = array();
        $sql            = 'SELECT * FROM ' . tablename($this->tb_data) . " WHERE `reid`=:reid AND `rerid`='{$info['rerid']}' AND `refid` IN ({$fids})";
        $fdatas         = pdo_fetchall($sql, $params);
        foreach ($fdatas as $fd) {
            $info['fields'][$fd['refid']] = $fd['data'];
        }
        foreach ($ds as $value) {
            if ($value['type'] == 'reside') {
                $info['fields'][$value['refid']] = '';
                foreach ($fdatas as $fdata) {
                    if ($fdata['refid'] == $value['refid']) {
                        $info['fields'][$value['refid']] .= $fdata['data'];
                    }
                }
                break;
            }
        }
        include $this->template('detail');
    }
    public function doWebManage()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $reid     = intval($_GPC['id']);
        $activity = $this->get_yuyuepay($reid);
        $par      = iunserializer($activity['par']);
        $role     = $this->get_isrole($reid, $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        $zhuti           = pdo_fetchall("SELECT reid,title FROM " . tablename($this->tb_yuyue) . " WHERE weid = :weid and status=1 ORDER BY `reid` DESC", array(
            ':weid' => $_W['uniacid']
        ));
        $sql             = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY `refid`';
        $params          = array();
        $params[':reid'] = $reid;
        $fields          = pdo_fetchall($sql, $params);
        $cate            = $this->get_category($activity['cid']);
        if (pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($setting['store'])) {
            $store = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay_plugin_store_store') . " WHERE weid = :weid and checked = 1 ORDER BY id DESC", array(
                ':weid' => $weid
            ), 'id');
            foreach ($store AS $key => $val) {
                $store[$key]['reid']  = $val['id'];
                $store[$key]['title'] = $val['name'];
            }
        }
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;
        $status    = $_GPC['status'];
        $paystatus = $_GPC['paystatus'];
        $starttime = empty($_GPC['time']['start']) ? strtotime('-1 month') : strtotime($_GPC['time']['start']);
        $endtime   = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
        $yytime    = urldecode($_GPC['yytime']);
        $stime     = empty($_GPC['yytime']['start']) ? TIMESTAMP : strtotime($_GPC['yytime']['start']);
        $etime     = empty($_GPC['yytime']['end']) ? strtotime('+15 day') : strtotime($_GPC['yytime']['end']) + 86399;
        $where .= 'reid=:reid';
        $params          = array();
        $params[':reid'] = $reid;
        if (!empty($_GPC['time'])) {
            $where .= " AND createtime >= :starttime AND createtime <= :endtime ";
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }
        if ($activity['is_time'] == 2 && !empty($_GPC['yytime'])) {
            $where .= ' AND restime like :yytime';
            $params[':yytime'] = "%{$_GPC['yytime']}%";
        } elseif ($activity['is_time'] == 0 && !empty($_GPC['yytime']['start'])) {
            $where .= ' AND `yuyuetime` > :stime AND `yuyuetime` < :etime';
            $params[':stime'] = $stime;
            $params[':etime'] = $etime;
        }
        if (!empty($_GPC['keywords'])) {
            $where .= ' and (member like :member or mobile like :mobile)';
            $params[':member'] = "%{$_GPC['keywords']}%";
            $params[':mobile'] = "%{$_GPC['keywords']}%";
        }
        if (!empty($_GPC['orderid'])) {
            $where .= ' and (ordersn like :ordersn or transid like :transid)';
            $params[':ordersn'] = "%{$_GPC['orderid']}%";
            $params[':transid'] = "%{$_GPC['orderid']}%";
        }
        if (!empty($_GPC['storeid'])) {
            $where .= ' and sid=:sid';
            $params[':sid'] = "{$_GPC['storeid']}";
        }
        if ($status != '') {
            if ($status == 2) {
                $allstatus .= " and ( status=2 or status=9 )";
            } else {
                $allstatus .= " and status='{$status}'";
            }
        }
        if ($paystatus != '') {
            $allstatus .= " and paystatus='{$paystatus}'";
        }
        $sql     = 'SELECT * FROM ' . tablename($this->tb_info) . " WHERE $where $allstatus ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $list    = pdo_fetchall($sql, $params);
        $total   = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE $where $allstatus", $params);
        $pager   = pagination($total, $pindex, $psize);
        $paytype = array(
            '0' => array(
                'css' => 'default',
                'name' => '未支付'
            ),
            '1' => array(
                'css' => 'success',
                'name' => '在线支付'
            ),
            '2' => array(
                'css' => 'info',
                'name' => '余额支付'
            ),
            '3' => array(
                'css' => 'warning',
                'name' => '其他付款方式'
            ),
            '4' => array(
                'css' => 'info',
                'name' => '免费预约'
            )
        );
        foreach ($list as $index => $row) {
            $list[$index]['user']   = mc_fansinfo($row['openid'], $acid, $_W['uniacid']);
            $list[$index]['xm']     = $this->get_xiangmu($row['reid'], $row['xmid']);
            $list[$index]['store']  = !empty($row['sid']) ? $this->get_store($row['sid']) : '';
            $list[$index]['status'] = $this->get_status($row['reid'], $row['status']);
            $list[$index]['css']    = $paytype[$row['paytype']]['css'];
            if ($list[$index]['paytype'] == 1) {
                if (empty($list[$index]['transid'])) {
                    if ($list[$index]['paystatus'] == 1) {
                        $list[$index]['paytype'] = '';
                    } else {
                        $list[$index]['paytype'] = '支付宝支付';
                    }
                } else {
                    $list[$index]['paytype'] = '微信支付';
                }
            } else {
                $list[$index]['paytype'] = $paytype[$row['paytype']]['name'];
            }
        }
        $sum_price_all       = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . " WHERE $where", $params);
        $sum_price_confirm   = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . " WHERE $where AND status=0 AND paystatus=1", $params);
        $sum_price_pay       = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . " WHERE $where AND status=0 AND paystatus=2", $params);
        $sum_price_finish    = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . " WHERE $where AND status=1 AND paystatus=2", $params);
        $sum_price_cancel    = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . " WHERE $where AND ( status=2 or status=9 )", $params);
        $sum_price_end       = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . " WHERE $where AND status=3", $params);
        $sum_price_refund    = pdo_fetch('SELECT SUM(price) AS sum_money FROM ' . tablename($this->tb_info) . " WHERE $where AND status=7", $params);
        $order_count_all     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE $where", $params);
        $order_count_confirm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE $where AND status=0 AND paystatus=1", $params);
        $order_count_pay     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE $where AND status=0 AND paystatus=2", $params);
        $order_count_finish  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE $where AND status=1 AND paystatus=2", $params);
        $order_count_cancel  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE $where AND ( status=2 or status=9 )", $params);
        $order_count_end     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE $where AND status=3", $params);
        $order_count_refund  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE $where AND status=7", $params);
        if (!empty($_GPC['export'])) {
            $tableheader = pdo_fetchall("SELECT title,f.displayorder FROM " . tablename($this->tb_field) . " AS f JOIN " . tablename($this->tb_info) . " AS r ON f.reid='{$reid}' GROUP BY title ORDER BY refid, f.displayorder desc");
            $tablelength = count($tableheader);
            $sql         = 'SELECT * FROM ' . tablename($this->tb_info) . " WHERE $where $allstatus ORDER BY createtime DESC";
            $list        = pdo_fetchall($sql, $params);
            if (empty($list)) {
                message('暂时没有预约数据');
            }
            foreach ($list as &$r) {
                $sql              = 'SELECT data, refid FROM ' . tablename($this->tb_data) . " WHERE `reid`=:reid AND `rerid`='{$r['rerid']}' ORDER BY redid, displayorder desc";
                $paramss          = array();
                $paramss[':reid'] = $r['reid'];
                $r['fields']      = array();
                $fdatas           = pdo_fetchall($sql, $paramss);
                foreach ($fdatas as $fd) {
                    $r['fields'][$fd['refid']] = $fd['data'];
                }
            }
            $data = array();
            foreach ($list as $key => $value) {
                $data[$key]['member'] = $value['member'];
                $data[$key]['mobile'] = $value['mobile'];
                if (pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($setting['store'])) {
                    $data[$key]['store'] = $this->get_store($value['sid']);
                }
                $data[$key]['xmid']      = $this->get_xiangmu($value['reid'], $value['xmid']);
                $data[$key]['paystatus'] = $value['paystatus'];
                $data[$key]['status']    = $value['status'];
                $data[$key]['price']     = $value['price'];
                if ($activity['is_time'] == 0) {
                    $data[$key]['time'] = strtotime($value['yuyuetime']);
                } elseif ($activity['is_time'] == 2) {
                    $data[$key]['time'] = $value['restime'];
                }
                if (!empty($value['fields'])) {
                    foreach ($value['fields'] as $field) {
                        if (substr($field, 0, 6) == 'images') {
                            $data[$key][] = str_replace(array(
                                "\n",
                                "\r",
                                "\t"
                            ), '', $_W['attachurl'] . $field);
                        } else {
                            $data[$key][] = str_replace(array(
                                "\n",
                                "\r",
                                ",",
                                "\t"
                            ), '，', $field);
                        }
                    }
                }
                $data[$key]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
                $data[$key]['kfinfo']     = $value['kfinfo'];
                $data[$key]['transid']    = $value['transid'];
                $data[$key]['share']      = $value['share'];
            }
            $html = "\xEF\xBB\xBF";
            $html .= "姓名\t,";
            $html .= "手机\t,";
            if (pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($setting['store'])) {
                $html .= "店铺\t,";
            }
            $html .= $par['xmname'] . "\t,";
            $html .= "金额\t,";
            $html .= "预约时间\t,";
            $html .= "付款状态\t,";
            $html .= "支付单号\t,";
            $html .= "预约状态\t,";
            foreach ($tableheader as $value) {
                $html .= $value['title'] . "\t ,";
            }
            $html .= "回复\t,";
            $html .= "提交时间\t,";
            $html .= "分享人\t,";
            $html .= "\n";
            foreach ($data as $value) {
                $paystatus = $value['paystatus'] == '2' ? '已付款' : '未付款';
                $status    = $this->get_status($value['reid'], $value['status']);
                $html .= $value['member'] . "\t,";
                $html .= $value['mobile'] . "\t,";
                if (pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($setting['store'])) {
                    $html .= !empty($value['store']['name']) ? $value['store']['name'] . "\t," : "\t,";
                }
                $html .= $value['xmid']['title'] . "\t,";
                $html .= $value['price'] . "\t,";
                $html .= $value['time'] . "\t,";
                $html .= $paystatus . "\t,";
                $html .= $value['transid'] . "\t,";
                $html .= $status['name'] . "\t,";
                for ($i = 0; $i < $tablelength; $i++) {
                    $html .= $value[$i] . "\t,";
                }
                $html .= $value['kfinfo'] . "\t,";
                $html .= $value['createtime'] . "\t,";
                $html .= $value['share'] . "\t,";
                $html .= "\n";
            }
            $stime = date('Ymd', $starttime);
            $etime = date('Ymd', $endtime);
            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename={$cate['title']} - {$activity['title']}$stime-$etime.csv");
            echo $html;
            exit();
        }
        foreach ($list as $key => &$value) {
            if (is_array($value['fields'])) {
                foreach ($value['fields'] as &$v) {
                    $img = '<div align="center"><img src="';
                    if (substr($v, 0, 6) == 'images') {
                        $v = $img . $_W['attachurl'] . $v . '" style="width:50px;height:50px;"/></div>';
                    }
                }
                unset($v);
            }
        }
        $title = "预约记录管理";
        include $this->template('manage');
    }
    public function doWebBatchrRcord()
    {
        global $_GPC, $_W;
        require 'fans.web.php';
        $reid = intval($_GPC['reid']);
        $role = $this->get_isrole($reid, $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        $reply = pdo_fetch("select reid from " . tablename($this->tb_yuyue) . " where reid = :reid", array(
            ':reid' => $reid
        ));
        if (empty($reply)) {
            $result['status'] = 0;
            $result['msg']    = '抱歉，预约主题不存在或是已经被删除！';
        }
        foreach ($_GPC['idArr'] as $k => $rerid) {
            pdo_delete($this->tb_info, array(
                'rerid' => $rerid,
                'reid' => $reid
            ));
            pdo_delete($this->tb_data, array(
                'rerid' => $rerid,
                'reid' => $reid
            ));
        }
        $result['status'] = 1;
        $result['msg']    = '记录批量删除成功！';
        message($result, '', 'ajax');
    }
    public function doWebSetStatus()
    {
        global $_GPC, $_W;
        require 'fans.web.php';
        $reid = intval($_GPC['reid']);
        $role = $this->get_isrole($reid, $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        $state = intval($_GPC['state']);
        $reply = pdo_fetch("select reid from " . tablename($this->tb_yuyue) . " where reid = :reid", array(
            ':reid' => $reid
        ));
        if (empty($reply)) {
            $result['status'] = 0;
            $result['msg']    = '抱歉，预约主题不存在或是已经被删除！';
        }
        $data = array(
            'status' => $state,
            'yuyuetime' => TIMESTAMP
        );
        foreach ($_GPC['idArr'] as $k => $rerid) {
            pdo_update($this->tb_info, $data, array(
                'reid' => $reid,
                'rerid' => $rerid
            ));
        }
        $result['status'] = 1;
        $result['msg']    = '批量更新成功！';
        message($result, '', 'ajax');
    }
    public function doWebDisplay()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $op              = trim($_GPC['op']) ? trim($_GPC['op']) : '';
        $category        = pdo_fetchall("SELECT id,title FROM " . tablename($this->tb_category) . " WHERE weid = :weid ORDER BY `id` DESC", array(
            ':weid' => $_W['uniacid']
        ));
        $cateid          = intval($_GPC['yuyueid']);
        $role            = pdo_fetchall("SELECT * FROM " . tablename($this->tb_role) . " WHERE weid = :weid and roleid = :roleid  ORDER BY id DESC", array(
            ':roleid' => $_W['user']['uid'],
            ':weid' => $_W['uniacid']
        ), 'reid');
        $roleid          = array_keys($role);
        $where           = 'weid = :weid';
        $params[':weid'] = $_W['uniacid'];
        $pindex          = max(1, intval($_GPC['page']));
        $psize           = 10;
        $status          = $_GPC['status'];
        if ($cateid) {
            $where .= " and cid=" . intval($cateid);
        }
        if ($status != '') {
            $where .= " and status=" . intval($status);
        }
        if (!empty($_GPC['keyword'])) {
            $where .= " AND title LIKE '%{$_GPC['keyword']}%'";
        }
        if ($setting['role'] == 1 && $_W['role'] == 'operator') {
            $where .= " AND reid IN ('" . implode("','", is_array($roleid) ? $roleid : array(
                $roleid
            )) . "')";
        }
        $sql   = 'SELECT * FROM ' . tablename($this->tb_yuyue) . " WHERE $where ORDER BY displayorder DESC,reid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $ds    = pdo_fetchall($sql, $params);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_yuyue) . ' WHERE ' . $where, $params);
        $pager = pagination($total, $pindex, $psize);
        foreach ($ds as &$item) {
            $item['isstart'] = $item['starttime'] > 0;
            $item['switch']  = $item['status'];
            $item['role']    = $this->get_isrole($item['reid'], $_W['user']['uid']);
            $item['par']     = iunserializer($item['par']);
            $item['cate']    = $this->get_category($item['cid']);
            $item['link']    = $item['is_time'] == 2 ? murl('entry', array(
                'do' => 'timelist',
                'id' => $item['reid'],
                'm' => 'dayu_yuyuepay'
            ), true, true) : murl('entry', array(
                'do' => 'dayu_yuyuepay',
                'id' => $item['reid'],
                'm' => 'dayu_yuyuepay'
            ), true, true);
            $item['mylink']  = murl('entry', array(
                'do' => 'mydayu_yuyuepay',
                'id' => $item['reid'],
                'm' => 'dayu_yuyuepay'
            ), true, true);
            $item['malink']  = murl('entry', array(
                'do' => 'manage',
                'id' => $item['reid'],
                'm' => 'dayu_yuyuepay'
            ), true, true);
        }
        if ($op == 'copy') {
            $id   = intval($_GPC['id']);
            $form = pdo_fetch('SELECT * FROM ' . tablename($this->tb_yuyue) . ' WHERE weid = :weid AND reid = :reid', array(
                ':weid' => $_W['uniacid'],
                ':reid' => $id
            ));
            if (empty($form)) {
                message('预约主题不存在或已删除', referer(), 'error');
            }
            $form['title'] = $form['title'] . '_' . random(6);
            unset($form['reid']);
            pdo_insert($this->tb_yuyue, $form);
            $form_id = pdo_insertid();
            if (!$form_id) {
                message('复制预约主题出错', '', 'error');
            } else {
                $fields = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_field) . ' WHERE reid = :reid', array(
                    ':reid' => $id
                ));
                if (!empty($fields)) {
                    foreach ($fields as &$val) {
                        unset($val['refid']);
                        $val['reid'] = $form_id;
                        pdo_insert($this->tb_field, $val);
                    }
                }
                message('复制预约主题成功', $this->createWebUrl('display'), 'success');
            }
        }
        if (checksubmit('submit')) {
            if (!empty($_GPC['ids'])) {
                foreach ($_GPC['ids'] as $k => $v) {
                    $data = array(
                        'displayorder' => intval($_GPC['displayorder'][$k])
                    );
                    pdo_update($this->tb_yuyue, $data, array(
                        'weid' => $_W['uniacid'],
                        'reid' => intval($v)
                    ));
                }
                message('编辑排序成功', $this->createWebUrl('display', array()), 'success');
            }
        }
        if ($_W['ispost']) {
            $reid              = intval($_GPC['reid']);
            $switch            = intval($_GPC['switch']);
            $sql               = 'UPDATE ' . tablename($this->tb_yuyue) . ' SET `status`=:status WHERE `reid`=:reid';
            $params            = array();
            $params[':status'] = $switch;
            $params[':reid']   = $reid;
            pdo_query($sql, $params);
            exit();
        }
        include $this->template('display');
    }
    public function doWebItem()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $op       = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        $weid     = $_W['uniacid'];
        $reid     = intval($_GPC['reid']);
        $activity = $this->get_yuyuepay($reid);
        $par      = iunserializer($activity['par']);
        $role     = $this->get_isrole($reid, $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        if (empty($activity)) {
            message('预约主题不存在或已删除', $this->createWebUrl('Display'), 'error');
        }
        if ($op == 'list') {
            $where           = ' reid = :reid';
            $params[':reid'] = $reid;
            if (!empty($_GPC['keyword'])) {
                $where .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $lists  = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_item) . ' WHERE ' . $where . ' ORDER BY displayorder DESC,id DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params, 'id');
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_item) . ' WHERE ' . $where, $params);
            foreach ($lists AS $key => $val) {
                $lists[$key]['isshow'] = $lists[$key]['isshow'] == 1 ? '<span class="label label-warning">显示</span>' : '<span class="badge">隐藏</span>';
                $lists[$key]['daynum'] = $lists[$key]['daynum'] != 0 ? '<span class="label label-primary">每天' . $val['daynum'] . '人</span>' : '<span class="label label-success">不限制</span>';
                $lists[$key]['type']   = $lists[$key]['type'] == 1 ? '<span class="label label-primary">全额+定金</span>' : '<span class="label label-info">支付金额</span>';
            }
            $pager = pagination($total, $pindex, $psize);
            if (checksubmit('submit')) {
                if (!empty($_GPC['ids'])) {
                    foreach ($_GPC['ids'] as $k => $v) {
                        $data = array(
                            'title' => trim($_GPC['title'][$k]),
                            'price' => $_GPC['price'][$k],
                            'displayorder' => intval($_GPC['displayorder'][$k])
                        );
                        pdo_update($this->tb_item, $data, array(
                            'reid' => $reid,
                            'id' => intval($v)
                        ));
                    }
                    message('编辑成功', $this->createWebUrl('item', array(
                        'op' => 'list',
                        'reid' => $reid
                    )), 'success');
                }
            }
            include $this->template('item');
        } elseif ($op == 'post') {
            $id = intval($_GPC['id']);
            if (!$id) {
                if (checksubmit('submit')) {
                    $data['weid']    = $weid;
                    $data['reid']    = $reid;
                    $data['title']   = $_GPC['title'];
                    $data['thumb']   = $_GPC['thumb'];
                    $data['type']    = intval($_GPC['type']);
                    $data['price']   = $_GPC['price'];
                    $data['prices']  = $_GPC['prices'];
                    $data['daynum']  = intval($_GPC['daynum']);
                    $data['isc']     = intval($_GPC['isc']);
                    $data['content'] = trim($_GPC['content']);
                    $data['isshow']  = intval($_GPC['isshow']);
                    pdo_insert($this->tb_item, $data);
                    message('添加服务项目成功', $this->createWebUrl('item', array(
                        'reid' => $reid,
                        'op' => 'list'
                    )), 'success');
                }
                $item = array(
                    "isshow" => 1
                );
            } else {
                $item = pdo_fetch('SELECT * FROM ' . tablename($this->tb_item) . ' WHERE weid = :weid AND reid = :reid AND id = :id', array(
                    ':weid' => $weid,
                    ':reid' => $reid,
                    ':id' => $id
                ));
                if (checksubmit('submit')) {
                    $data['title']   = $_GPC['title'];
                    $data['thumb']   = $_GPC['thumb'];
                    $data['type']    = intval($_GPC['type']);
                    $data['price']   = $_GPC['price'];
                    $data['prices']  = $_GPC['prices'];
                    $data['daynum']  = intval($_GPC['daynum']);
                    $data['isc']     = intval($_GPC['isc']);
                    $data['content'] = trim($_GPC['content']);
                    $data['isshow']  = intval($_GPC['isshow']);
                    pdo_update($this->tb_item, $data, array(
                        'reid' => $reid,
                        'id' => $id
                    ));
                    message('编辑服务项目成功', $this->createWebUrl('item', array(
                        'reid' => $reid,
                        'op' => 'list'
                    )), 'success');
                }
            }
            include $this->template('item');
        } elseif ($op == 'edit') {
            $id   = intval($_GPC['id']);
            $item = pdo_fetch('SELECT * FROM ' . tablename($this->tb_item) . ' WHERE weid = :weid AND reid = :reid AND id = :id', array(
                ':weid' => $weid,
                ':reid' => $reid,
                ':id' => $id
            ));
            if (checksubmit('submit')) {
                if (!empty($_GPC['title'])) {
                    foreach ($_GPC['title'] as $k => $v) {
                        $v = trim($v);
                        if (empty($v))
                            continue;
                        $data['reid']        = $reid;
                        $data['title']       = $v;
                        $data['title']       = $_GPC['title'][$k];
                        $data['daynum']      = intval($_GPC['daynum'][$k]);
                        $data['weid']        = $_GPC['weid'][$k];
                        $data['description'] = trim($_GPC['description'][$k]);
                        $data['createtime']  = time();
                        pdo_update($this->tb_item, $data, array(
                            'reid' => $reid,
                            'id' => $id
                        ));
                    }
                }
                message('修改服务项目成功', $this->createWebUrl('item', array(
                    'reid' => $reid,
                    'op' => 'list'
                )), 'success');
            }
            include $this->template('item');
        } elseif ($op == 'itemdel') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                pdo_delete($this->tb_item, array(
                    'id' => $id
                ));
            }
            message('删除成功.', referer());
        }
    }
    public function doWebSlide()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_slide) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
            foreach ($list AS $key => $val) {
                $list[$key]['cate'] = $this->get_category($val['cid']);
            }
        } elseif ($operation == 'post') {
            $id = intval($_GPC['id']);
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'title' => $_GPC['title'],
                    'link' => $_GPC['link'],
                    'cid' => $_GPC['cate'],
                    'enabled' => intval($_GPC['enabled']),
                    'displayorder' => intval($_GPC['displayorder'])
                );
                if (!empty($_GPC['thumb'])) {
                    $data['thumb'] = $_GPC['thumb'];
                    load()->func('file');
                    file_delete($_GPC['thumb-old']);
                }
                if (!empty($id)) {
                    pdo_update($this->tb_slide, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->tb_slide, $data);
                    $id = pdo_insertid();
                }
                message('更新幻灯片成功！', $this->createWebUrl('slide', array(
                    'op' => 'display'
                )), 'success');
            }
            $slide    = pdo_fetch("select * from " . tablename($this->tb_slide) . " where id=:id and weid=:weid limit 1", array(
                ":id" => $id,
                ":weid" => $_W['uniacid']
            ));
            $category = pdo_fetchall("select * from " . tablename($this->tb_category) . " where weid = :weid ORDER BY id DESC", array(
                ':weid' => $weid
            ));
        } elseif ($operation == 'delete') {
            $id    = intval($_GPC['id']);
            $slide = pdo_fetch("SELECT id  FROM " . tablename($this->tb_slide) . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
            if (empty($slide)) {
                message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('slide', array(
                    'op' => 'display'
                )), 'error');
            }
            pdo_delete($this->tb_slide, array(
                'id' => $id
            ));
            message('幻灯片删除成功！', $this->createWebUrl('slide', array(
                'op' => 'display'
            )), 'success');
        } else {
            message('请求方式不存在');
        }
        include $this->template('slide', TEMPLATE_INCLUDEPATH, true);
    }
    public function doMobileList22()
    {
        global $_GPC, $_W;
        require 'fans.mobile.php';
        $cateid              = intval($_GPC['cateid']);
        $returnUrl           = urlencode($_W['siteurl']);
        $setting             = $this->module['config'];
        $setting['list_num'] = !empty($setting['list_num']) ? $setting['list_num'] : 3;
        if ($cateid) {
            $where .= " and cid='{$cateid}'";
            $is_list .= " AND is_list = 1";
        }
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_slide) . " WHERE weid = '{$weid}' $where ORDER BY displayorder DESC");
        foreach ($list AS $key => $val) {
            $list[$key]['thumb'] = tomedia($val['thumb']);
        }
        $projects = pdo_fetchAll("SELECT reid,weid,is_time,icon,subtitle,status,description,displayorder FROM" . tablename($this->tb_yuyue) . " WHERE status=1 AND weid='{$weid}' $is_list $where order by displayorder DESC,reid DESC");
        foreach ($projects AS $index => $v) {
            $projects[$index]['link'] = $v['is_time'] == 2 ? $this->createMobileUrl('timelist', array(
                'id' => $v['reid']
            )) : $this->createMobileUrl('dayu_yuyuepay', array(
                'id' => $v['reid']
            ));
        }
        $jquery  = 2;
        $hslists = unserialize($item['hs_pic']);
        $title   = !empty($setting['title']) ? $setting['title'] : "预约";
        include $this->template('list');
    }
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        require 'fans.mobile.php';
        $id        = intval($_GPC['id']);
        $returnUrl = urlencode($_W['siteurl']);
        $setting   = $this->module['config'];
        if ($id) {
            $cate      = pdo_get($this->tb_category, array(
                'weid' => $weid,
                'id' => $id
            ), array());
            $color     = !empty($cate['color']) ? iunserializer($cate['color']) : '';
            $nav_index = $color['nav_index'];
            $nav_page  = $color['nav_page'];
            $nav_btn   = $color['nav_btn'];
            $title     = $cate['title'];
            $where .= " and cid='{$id}'";
            $is_list .= " AND is_list = 1";
            $list_num = !empty($cate['list']) ? $cate['list'] : 1;
            if ($cate['list'] == '3') {
                $list_num = '4';
            } elseif ($cate['list'] == '4') {
                $list_num = '3';
            }
        } else {
            $title     = $setting['title'];
            $nav_index = $setting['color']['nav_index'];
            $nav_page  = $setting['color']['nav_page'];
            $nav_btn   = $setting['color']['nav_btn'];
            $list_num  = !empty($setting['list_num']) ? $setting['list_num'] : 1;
            if ($setting['list_num'] == '3') {
                $list_num = '4';
            } elseif ($setting['list_num'] == '4') {
                $list_num = '3';
            }
        }
        $category = pdo_fetchall("select * from " . tablename($this->tb_category) . " where weid = :weid ORDER BY id DESC", array(
            ':weid' => $weid
        ));
        $yuyue    = pdo_fetchAll("SELECT reid,weid,title,is_time,icon,thumb,subtitle,status,description,content,displayorder FROM" . tablename($this->tb_yuyue) . " WHERE status=1 AND weid='{$weid}' $is_list $where order by displayorder DESC,reid DESC");
        foreach ($yuyue AS $index => $v) {
            $yuyue[$index]['link'] = $v['is_time'] == 2 ? $this->createMobileUrl('timelist', array(
                'id' => $v['reid']
            )) : $this->createMobileUrl('dayu_yuyuepay', array(
                'id' => $v['reid']
            ));
        }
        include $this->template('index');
    }
    public function doMobileList()
    {
        global $_GPC, $_W;
        require 'fans.mobile.php';
        $id        = intval($_GPC['id']);
        $returnUrl = urlencode($_W['siteurl']);
        $setting   = $this->module['config'];
        if ($id) {
            $cate      = pdo_get($this->tb_category, array(
                'weid' => $weid,
                'id' => $id
            ), array());
            $color     = !empty($cate['color']) ? iunserializer($cate['color']) : '';
            $nav_index = $color['nav_index'];
            $nav_page  = $color['nav_page'];
            $nav_btn   = $color['nav_btn'];
            $title     = $cate['title'];
            $where .= " and cid='{$id}'";
            $is_list .= " AND is_list = 1";
            $list_num = !empty($cate['list']) ? $cate['list'] : 1;
            if ($cate['list'] == '3') {
                $list_num = '4';
            } elseif ($cate['list'] == '4') {
                $list_num = '3';
            }
        } else {
            $title     = $setting['title'];
            $nav_index = $setting['color']['nav_index'];
            $nav_page  = $setting['color']['nav_page'];
            $nav_btn   = $setting['color']['nav_btn'];
            $list_num  = !empty($setting['list_num']) ? $setting['list_num'] : 1;
            if ($setting['list_num'] == '3') {
                $list_num = '4';
            } elseif ($setting['list_num'] == '4') {
                $list_num = '3';
            }
        }
        $category = pdo_fetchall("select * from " . tablename($this->tb_category) . " where weid = :weid ORDER BY id", array(
            ':weid' => $weid
        ));
        $slide    = pdo_fetchall("SELECT * FROM " . tablename($this->tb_slide) . " WHERE weid = :weid $where ORDER BY displayorder DESC", array(
            ':weid' => $weid
        ));
        foreach ($slide AS $key => $val) {
            $slide[$key]['thumb'] = tomedia($val['thumb']);
        }
        $yuyue = pdo_fetchAll("SELECT reid,weid,title,is_time,icon,thumb,subtitle,status,description,content,displayorder FROM" . tablename($this->tb_yuyue) . " WHERE status=1 AND weid='{$weid}' $is_list $where order by displayorder DESC,reid DESC");
        foreach ($yuyue AS $index => $v) {
            $yuyue[$index]['link'] = $v['is_time'] == 2 ? $this->createMobileUrl('timelist', array(
                'id' => $v['reid']
            )) : $this->createMobileUrl('dayu_yuyuepay', array(
                'id' => $v['reid']
            ));
        }
        include $this->template('index');
    }
    public function doMobileGetYuyue()
    {
        global $_GPC, $_W;
        $weid            = $_W['uniacid'];
        $yuyue           = pdo_get($this->tb_yuyue, array(
            'weid' => $weid,
            'reid' => $_GPC['id']
        ), array());
        $par             = iunserializer($yuyue['par']);
        $link            = $yuyue['is_time'] == 2 ? $this->createMobileUrl('timelist', array(
            'id' => $yuyue['reid']
        )) : $this->createMobileUrl('dayu_yuyuepay', array(
            'id' => $yuyue['reid']
        ));
        $mylink          = $this->createMobileUrl('mydayu_yuyuepay', array(
            'id' => $yuyue['reid']
        ));
        $result['id']    = $yuyue['reid'];
        $result['mname'] = $yuyue['mname'];
        $thumb           = tomedia($yuyue['thumb']);
        $html            = '
		 <div class="weui-header bg-blue">
			<div class="weui-header-left">
				<a href="javascript:;" class="icon icon-109 f-white close-popup">
					<svg class="icon" aria-hidden="true">
						<use xlink:href="#icon-left"></use>
					</svg>
				</a>
			</div>
			<h1 class="weui-header-title">' . $yuyue['title'] . '</h1>
		</div>
		<div class="weui-weixin">
			<div class="weui-weixin-ui">
				<div class="weui-weixin-page">
					<div class="weui-weixin-img text-center"><img src="' . $thumb . '" id="image" class="center" style="width:100%;"></div>                                        
					<div class="weui-weixin-content">' . htmlspecialchars_decode($yuyue['content']) . '</div>
				</div>
			</div>
		</div>
		';
        $html2           = '
	<div class="weui_tabbar tab-bottom">
		<a href="javascript:;" class="weui_tabbar_item close-popup">
			<div class="weui_tabbar_icon">
				<svg class="icon" aria-hidden="true">
					<use xlink:href="#icon-close"></use>
				</svg>
			</div>
			<p class="weui_tabbar_label">关闭</p>
		</a>
		<a href="' . $link . '" class="weui_tabbar_item">
			<div class="weui_tabbar_icon">
				<svg class="icon" aria-hidden="true">
					<use xlink:href="#icon-overtime"></use>
				</svg>
			</div>
			<p class="weui_tabbar_label">预约</p>
		</a>
		<a href="' . $mylink . '" class="weui_tabbar_item">
			<div class="weui_tabbar_icon">
				<svg class="icon" aria-hidden="true">
					<use xlink:href="#icon-kehu"></use>
				</svg>
			</div>
			<p class="weui_tabbar_label">' . $par['mname'] . '</p>
		</a>
	</div>
		';
        $result['html']  = $html;
        $result['html2'] = $html2;
        message($result, '', 'ajax');
    }
    public function doMobileGetSlider()
    {
        global $_GPC, $_W;
        $weid   = $_W['uniacid'];
        $cateid = intval($_GPC['cateid']);
        if ($cateid) {
            $where .= " and cid='{$cateid}'";
        } else {
            $where .= " and cid=''";
        }
        $slide = pdo_fetchall("SELECT * FROM " . tablename($this->tb_slide) . " WHERE weid = :weid and enabled=1 $where ORDER BY displayorder DESC", array(
            ':weid' => $weid
        ));
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_slide) . " WHERE weid = :weid and enabled=1 $where", array(
            ':weid' => $weid
        ));
        $data  = array();
        foreach ($slide AS $index => $val) {
            $slide[$index]['thumb']     = tomedia($val['thumb']);
            $slide[$index]['indicator'] = $index == '0' ? ' mui-active' : '';
            $data[]                     = array(
                'id' => $val['id'],
                'title' => $val['title'],
                'thumb' => tomedia($val['thumb']),
                'link' => $val['link'],
                'indicator' => '<div class="mui-indicator' . $slide[$index]['indicator'] . '"></div>',
                'slide' => '<div class="mui-slider-item"><a href="' . $slide[$index]['mode'] . '"><img src="' . $slide[$index]['thumb'] . '"><p class="mui-slider-title f-white">' . $val['title'] . '</p></a></div>'
            );
        }
        return '{"list":' . json_encode($data) . ',"total":' . json_encode($total) . '}';
    }
    public function doMobileTest()
    {
        global $_GPC, $_W;
        require 'fans.mobile.php';
        $id        = intval($_GPC['id']);
        $returnUrl = urlencode($_W['siteurl']);
        $setting   = $this->module['config'];
        include $this->template('test');
    }
    public function doMobileGetPeccancy()
    {
        global $_GPC, $_W;
        $field = array(
            "key" => 11,
            "cyid" => 22,
            "startday" => 33,
            "endday" => 44,
            "page" => 55,
            "limit" => 66
        );
        $url   = 'http://115.29.249.214:8080/?cph=%E7%90%BCBG7212&vin=186933&lx=02&username=test&password=test1234';
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field);
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_encode($response, true);
        return $result;
    }
    public function payResult($params)
    {
        global $_W;
        $settings = $this->module['config'];
        $fee      = intval($params['fee']);
        $data     = array(
            'paystatus' => $params['result'] == 'success' ? 2 : 1
        );
        $paytype  = array(
            'credit' => '2',
            'wechat' => '1',
            'alipay' => '1',
            'delivery' => '3'
        );
        if (!empty($params['is_usecard'])) {
            $cardType          = array(
                '1' => '微信卡券',
                '2' => '系统代金券'
            );
            $data['paydetail'] = '使用' . $cardType[$params['card_type']] . '支付了' . ($params['fee'] - $params['card_fee']);
            $data['paydetail'] .= '元，实际支付了' . $params['card_fee'] . '元。';
        }
        $data['paytype'] = $paytype[$params['type']];
        if ($paytype[$params['type']] == '') {
            $data['paytype']   = 4;
            $data['paystatus'] = 2;
        }
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
            $transaction     = "\n支付单号" . $params['tag']['transaction_id'];
        }
        if ($params['type'] == 'delivery') {
            $data['paystatus'] = 1;
        }
        $data['status']    = $settings['paystate'] == 1 ? "1" : "0";
        $data['yuyuetime'] = TIMESTAMP;
        pdo_update($this->tb_info, $data, array(
            'ordersn' => $params['tid']
        ));
        if ($params['from'] == 'return') {
            $order    = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE ordersn = :ordersn", array(
                ':ordersn' => $params['tid']
            ));
            $activity = $this->get_yuyuepay($order['reid']);
            $par      = iunserializer($activity['par']);
            if (!empty($par['credit'])) {
                load()->model('mc');
                $log = $activity['title'] . '-预约奖励' . $par['credit'] * $params['fee'] . '积分';
                mc_credit_update(mc_openid2uid($order['openid']), 'credit1', $par['credit'] * $params['fee'], array(
                    0,
                    $log
                ));
                mc_group_update(mc_openid2uid($row['openid']));
                mc_notice_credit1($order['openid'], mc_openid2uid($order['openid']), $par['credit'] * $params['fee'], $log);
            }
            $status   = $this->get_status($order['reid'], $order['status']);
            $getxm    = $this->get_xiangmu($order['reid'], $order['xmid']);
            $jg       = $getxm['price'] != '0.00' ? ' - ' . $getxm['price'] . '元' : '';
            $f_c      = $getxm['price'] != '0.00' ? '您已成功付款' : '您已成功预约';
            $f_s      = $getxm['price'] != '0.00' ? '订单' . $order['ordersn'] . ' 已付款' : '单号：' . $order['ordersn'];
            $keyword2 = $getxm['price'] != '0.00' ? '在线支付 已付款 ' . $status['name'] : $status['name'];
            $xiangmu  = $activity['is_num'] == 1 ? $getxm['title'] . $jg . " * " . $order['num'] : $getxm['title'] . $jg;
            if ($activity['is_time'] == 0) {
                $ytime = date('Y-m-d H:i:s', $order['yuyuetime']);
            } elseif ($activity['is_time'] == 2) {
                $ytime = $order['restime'];
            }
            $ptime = intval(date('hi', time()));
            $add   = $activity['is_addr'] == 1 ? "\\n地址：" . $order['address'] : "";
            if ($activity['is_time'] != 1) {
                $time = $activity['is_time'] == 2 ? "\\n" . $par['yuyuename'] . "：" . $order['restime'] : "\\n" . $par['yuyuename'] . "：" . date('Y-m-d H:i:s', $order['yuyuetime']);
            }
            if ($par['sms'] != '1' && !empty($activity['smsid'])) {
                load()->func('communication');
                ihttp_post(murl('entry', array(
                    'do' => 'Notice',
                    'id' => $activity['smsid'],
                    'm' => 'dayu_sms'
                ), true, true), array(
                    'mobile' => $activity['mobile'],
                    'mname' => $order['member'],
                    'product' => $xiangmu,
                    'status' => $keyword2
                ));
            }
            if (!empty($order['sid']) && !empty($activity['store']) && !empty($settings['store'])) {
                $store   = $this->get_store($order['sid']);
                $kfid    = $store['openid'];
                $staff   = pdo_fetchall("SELECT `openid` FROM " . tablename('dayu_yuyuepay_plugin_store_store') . " WHERE weid=:weid and id=:id", array(
                    ':weid' => $_W['uniacid'],
                    ':id' => $order['sid']
                ));
                $address = '\\n' . $store['name'] . '：' . $store['loc_p'] . $store['loc_c'] . $store['loc_a'] . $store['address'];
            } else {
                $kfid    = $activity['kfid'];
                $staff   = pdo_fetchall("SELECT `openid` FROM " . tablename($this->tb_staff) . " WHERE weid=:weid and reid=:reid", array(
                    ':weid' => $_W['uniacid'],
                    ':reid' => $order['reid']
                ));
                $address = '';
            }
            if ($settings['stime'] != 0 && $settings['etime'] != 0) {
                if ($settings['stime'] < $ptime && $settings['etime'] > $ptime) {
                    if ($settings['newtemp'] == 1) {
                        if (!empty($activity['k_templateid'])) {
                            $template = array(
                                "touser" => $kfid,
                                "template_id" => $activity['k_templateid'],
                                "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                                    'weid' => $_W['uniacid'],
                                    'id' => $order['rerid'],
                                    'reid' => $order['reid']
                                )),
                                "topcolor" => "#FF0000",
                                "data" => array(
                                    'first' => array(
                                        'value' => urlencode($f_s . "\\n"),
                                        'color' => "#743A3A"
                                    ),
                                    'keyword1' => array(
                                        'value' => urlencode(date('Y年m月d日 H:i', $order['createtime'])),
                                        'color' => '#000000'
                                    ),
                                    'keyword2' => array(
                                        'value' => urlencode($keyword2),
                                        'color' => '#EF4F4F'
                                    ),
                                    'remark' => array(
                                        'value' => urlencode("\\n姓名：" . $order['member'] . "\\n手机：" . $order['mobile'] . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $address . $time),
                                        'color' => "#01579b"
                                    )
                                )
                            );
                            $this->send_template_message(urldecode(json_encode($template)));
                        }
                    } else {
                        if (!empty($activity['k_templateid']) && is_array($staff)) {
                            foreach ($staff as $s) {
                                $template = array(
                                    "touser" => $s['openid'],
                                    "template_id" => $activity['k_templateid'],
                                    "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                                        'weid' => $_W['uniacid'],
                                        'id' => $order['rerid'],
                                        'reid' => $order['reid']
                                    )),
                                    "topcolor" => "#FF0000",
                                    "data" => array(
                                        'first' => array(
                                            'value' => urlencode($f_s . "\\n"),
                                            'color' => "#743A3A"
                                        ),
                                        'keyword1' => array(
                                            'value' => urlencode(date('Y年m月d日 H:i', $order['createtime'])),
                                            'color' => '#000000'
                                        ),
                                        'keyword2' => array(
                                            'value' => urlencode($keyword2),
                                            'color' => '#EF4F4F'
                                        ),
                                        'remark' => array(
                                            'value' => urlencode("\\n姓名：" . $order['member'] . "\\n手机：" . $order['mobile'] . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $address . $time),
                                            'color' => "#01579b"
                                        )
                                    )
                                );
                                $this->send_template_message(urldecode(json_encode($template)));
                            }
                        }
                    }
                }
            } else {
                if ($settings['newtemp'] == 1) {
                    if (!empty($activity['k_templateid'])) {
                        $template = array(
                            "touser" => $kfid,
                            "template_id" => $activity['k_templateid'],
                            "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                                'weid' => $_W['uniacid'],
                                'id' => $order['rerid'],
                                'reid' => $order['reid']
                            )),
                            "topcolor" => "#FF0000",
                            "data" => array(
                                'first' => array(
                                    'value' => urlencode($f_s . "\\n"),
                                    'color' => "#743A3A"
                                ),
                                'keyword1' => array(
                                    'value' => urlencode(date('Y年m月d日 H:i', $order['createtime'])),
                                    'color' => '#000000'
                                ),
                                'keyword2' => array(
                                    'value' => urlencode($keyword2),
                                    'color' => '#EF4F4F'
                                ),
                                'remark' => array(
                                    'value' => urlencode("\\n姓名：" . $order['member'] . "\\n手机：" . $order['mobile'] . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $address . $time),
                                    'color' => "#01579b"
                                )
                            )
                        );
                        $this->send_template_message(urldecode(json_encode($template)));
                    }
                } else {
                    if (!empty($activity['k_templateid']) && is_array($staff)) {
                        foreach ($staff as $s) {
                            $template = array(
                                "touser" => $s['openid'],
                                "template_id" => $activity['k_templateid'],
                                "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                                    'weid' => $_W['uniacid'],
                                    'id' => $order['rerid'],
                                    'reid' => $order['reid']
                                )),
                                "topcolor" => "#FF0000",
                                "data" => array(
                                    'first' => array(
                                        'value' => urlencode($f_s . "\\n"),
                                        'color' => "#743A3A"
                                    ),
                                    'keyword1' => array(
                                        'value' => urlencode(date('Y年m月d日 H:i', $order['createtime'])),
                                        'color' => '#000000'
                                    ),
                                    'keyword2' => array(
                                        'value' => urlencode($keyword2),
                                        'color' => '#EF4F4F'
                                    ),
                                    'remark' => array(
                                        'value' => urlencode("\\n姓名：" . $order['member'] . "\\n手机：" . $order['mobile'] . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $address . $time),
                                        'color' => "#01579b"
                                    )
                                )
                            );
                            $this->send_template_message(urldecode(json_encode($template)));
                        }
                    }
                }
            }
            if (!empty($activity['m_templateid'])) {
                $data = array(
                    "touser" => $order['openid'],
                    "template_id" => $activity['m_templateid'],
                    "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('detail', array(
                        'reid' => $order['reid'],
                        'id' => $order['rerid']
                    )),
                    "topcolor" => "#FF0000",
                    "data" => array(
                        'first' => array(
                            'value' => urlencode($f_c . "\\n"),
                            'color' => "#743A3A"
                        ),
                        'keyword1' => array(
                            'value' => urlencode($order['member']),
                            'color' => '#000000'
                        ),
                        'keyword2' => array(
                            'value' => urlencode($xiangmu),
                            'color' => '#EF4F4F'
                        ),
                        'keyword3' => array(
                            'value' => urlencode($ytime),
                            'color' => '#EF4F4F'
                        ),
                        'keyword4' => array(
                            'value' => urlencode("订单号：" . $params['tid'] . $transaction . $address),
                            'color' => '#EF4F4F'
                        ),
                        'remark' => array(
                            'value' => urlencode("\\n" . $activity['mfoot']),
                            'color' => "#01579b"
                        )
                    )
                );
                $this->send_template_message(urldecode(json_encode($data)));
            }
            if (!empty($activity['mobile']) && $activity['smsid'] != 0) {
                load()->func('communication');
                $yy      = $par['xmname'] . ':' . $xiangmu . ',' . $par['yuyuename'] . ':' . $ytime;
                $content = $activity['smstype'] == 1 ? $activity['title'] . ',' . $yy : $yy . ',' . $smsbody;
                ihttp_post(murl('entry', array(
                    'do' => 'Notice',
                    'id' => $activity['smsid'],
                    'm' => 'dayu_sms'
                ), true, true), array(
                    'mobile' => $activity['mobile'],
                    'mname' => $order['member'],
                    'mmobile' => $order['mobile'],
                    'content' => $content
                ));
            }
            $uni_setting = uni_setting($_W['uniacid'], array(
                'creditbehaviors'
            ));
            $credit      = $uni_setting['creditbehaviors']['currency'];
            $tishi       = $getxm['price'] == '0.00' ? '提交预约成功！' : '支付成功！';
            if ($params['type'] == $credit) {
                $this->showMessage($tishi, $this->createMobileUrl('mydayu_yuyuepay', array(
                    'weid' => $_W['uniacid'],
                    'id' => $order['reid']
                )), 'success');
            } else {
                Message($tishi, $this->createMobileUrl('mydayu_yuyuepay', array(
                    'weid' => $_W['uniacid'],
                    'id' => $order['reid']
                )), 'success');
            }
        }
    }
    public function doMobilePay()
    {
        global $_W, $_GPC;
        $setting = uni_setting($_W['uniacid'], array(
            'payment',
            'creditbehaviors'
        ));
        if (!is_array($setting['payment'])) {
            $this->showMessage('没有有效的支付方式, 请联系网站管理员.');
        }
        $orderid = intval($_GPC['orderid']);
        $order   = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE rerid = :rerid", array(
            ':rerid' => $orderid
        ));
        $xm      = pdo_fetch("SELECT * FROM " . tablename($this->tb_item) . " WHERE weid = :weid and reid = :reid and id = :xmid", array(
            ':weid' => $_W['uniacid'],
            ':reid' => $order['reid'],
            ':xmid' => $order['xmid']
        ));
        if ($order['paystatus'] != '1') {
            $this->showMessage('抱歉，您的预约已经付款或是被关闭，请查看预约列表。', $this->createMobileUrl('mydayu_yuyuepay', array(
                'weid' => $order['weid'],
                'id' => $order['reid']
            )), 'error');
        }
        if ($order['price'] == '0.00') {
            $this->payResult(array(
                'tid' => $order['ordersn'],
                'from' => 'return',
                'type' => 'credit2'
            ));
            exit;
        }
        $title             = $order['num'] > 1 ? $xm['title'] . "×" . $order['num'] : $xm['title'];
        $params['tid']     = $order['ordersn'];
        $params['user']    = $_W['openid'];
        $params['fee']     = $order['price'];
        $params['title']   = $title;
        $params['ordersn'] = $order['ordersn'];
        $params['virtual'] = 1;
        $log               = pdo_get('core_paylog', array(
            'uniacid' => $_W['uniacid'],
            'module' => 'dayu_yuyuepay',
            'tid' => $params['tid']
        ));
        if (empty($log)) {
            $log = array(
                'uniacid' => $_W['uniacid'],
                'acid' => $_W['acid'],
                'openid' => $_W['member']['uid'],
                'module' => $this->module['name'],
                'tid' => $params['tid'],
                'fee' => $params['fee'],
                'card_fee' => $params['fee'],
                'status' => '0',
                'is_usecard' => '0'
            );
            pdo_insert('core_paylog', $log);
        }
        include $this->template('pay');
    }
    public function doWebDelete()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $reid = intval($_GPC['id']);
        $role = $this->get_isrole($reid, $_W['user']['uid']);
        if ($_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        if ($_W['role'] == 'founder' || $_W['role'] == 'manager') {
            $params          = array();
            $params[':reid'] = $reid;
            $sql             = 'DELETE FROM ' . tablename($this->tb_yuyue) . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename($this->tb_info) . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename($this->tb_data) . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            message('操作成功.', referer());
        }
        message('非法访问.');
    }
    public function doWebdayu_yuyuepayDelete()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $id   = intval($_GPC['id']);
        $info = pdo_get($this->tb_info, array(
            'rerid' => $id
        ), array());
        if (empty($info)) {
            message('访问非法.');
        }
        $activity = $this->get_yuyuepay($info['reid']);
        $role     = $this->get_isrole($reid, $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        if (!empty($id)) {
            pdo_delete($this->tb_info, array(
                'rerid' => $id
            ));
            pdo_delete($this->tb_data, array(
                'rerid' => $id
            ));
        }
        message('删除成功.', referer());
    }
    public function doWebPost()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $op = trim($_GPC['op']) ? trim($_GPC['op']) : 'post';
        if ($op == 'post') {
            $reid    = intval($_GPC['id']);
            $hasData = false;
            if ($reid) {
                $sql = 'SELECT COUNT(*) FROM ' . tablename($this->tb_info) . ' WHERE `reid`=' . $reid;
                if (pdo_fetchcolumn($sql) > 0) {
                    $hasData = true;
                }
            }
            load()->model('mc');
            $mgroup = pdo_fetchall('SELECT groupid FROM ' . tablename($this->tb_group) . " WHERE weid = '{$_W['uniacid']}' AND reid = '{$reid}'");
            if (!empty($mgroup)) {
                foreach ($mgroup as $cgroup) {
                    $grouparr[] = $cgroup['groupid'];
                }
            }
            $group = pdo_fetchall('SELECT groupid,title FROM ' . tablename('mc_groups') . " WHERE uniacid = '{$_W['uniacid']}' ");
            if (!empty($grouparr)) {
                foreach ($group as &$g) {
                    if (in_array($g['groupid'], $grouparr)) {
                        $g['groupid_select'] = 1;
                    }
                }
            }
            $role = pdo_fetchall('SELECT roleid FROM ' . tablename($this->tb_role) . " WHERE weid = '{$_W['uniacid']}' AND reid = '{$reid}'");
            if (!empty($role)) {
                foreach ($role as $r) {
                    $rolearr[] = $r['roleid'];
                }
            }
            $permission = pdo_fetchall("SELECT id, uid, role FROM " . tablename('uni_account_users') . " WHERE uniacid = :weid and role != :role  ORDER BY uid ASC, role DESC", array(
                ':role' => 'clerk',
                ':weid' => $weid
            ));
            if (!empty($permission)) {
                foreach ($permission as &$p) {
                    $p['user'] = $this->get_role($p['uid']);
                    if (!empty($role) && in_array($p['uid'], $rolearr)) {
                        $p['select'] = 1;
                    }
                }
            }
            if (checksubmit()) {
                $data                  = array(
                    'today' => $_GPC['today'],
                    'member' => intval($_GPC['member']),
                    'smsid' => intval($_GPC['yzm']),
                    'credit' => intval($_GPC['credit']),
                    'state1' => trim($_GPC['state1']),
                    'state2' => trim($_GPC['state2']),
                    'state3' => trim($_GPC['state3']),
                    'state4' => trim($_GPC['state4']),
                    'state5' => trim($_GPC['state5']),
                    'state8' => trim($_GPC['state8']),
                    'submit' => trim($_GPC['submits']),
                    'mname' => trim($_GPC['mname']),
                    'xmname' => trim($_GPC['xmname']),
                    'numname' => trim($_GPC['numname']),
                    'yuyuename' => trim($_GPC['yuyuename']),
                    'allnum' => intval($_GPC['allnum']),
                    'kfirst' => trim($_GPC['kfirst']),
                    'kfoot' => trim($_GPC['kfoot']),
                    'mfirst' => trim($_GPC['mfirst']),
                    'mfoot' => trim($_GPC['mfoot']),
                    'edit' => intval($_GPC['edit']),
                    'sms' => $_GPC['sms'],
                    'comment' => intval($_GPC['comment'])
                );
                $switch                = array();
                $record                = array();
                $record['title']       = trim($_GPC['activity']);
                $record['subtitle']    = trim($_GPC['subtitle']);
                $record['weid']        = $_W['uniacid'];
                $record['description'] = trim($_GPC['description']);
                $record['content']     = trim($_GPC['content']);
                $record['information'] = trim($_GPC['information']);
                if (!empty($_GPC['thumb'])) {
                    $record['thumb'] = $_GPC['thumb'];
                    load()->func('file');
                    file_delete($_GPC['thumb-old']);
                }
                $record['icon']         = $_GPC['icon'];
                $record['status']       = intval($_GPC['status']);
                $record['inhome']       = intval($_GPC['inhome']);
                $record['pretotal']     = intval($_GPC['pretotal']);
                $record['pay']          = intval($_GPC['pay']);
                $record['starttime']    = strtotime($_GPC['starttime']);
                $record['endtime']      = strtotime($_GPC['endtime']);
                $record['noticeemail']  = trim($_GPC['noticeemail']);
                $record['k_templateid'] = trim($_GPC['k_templateid']);
                $record['m_templateid'] = trim($_GPC['m_templateid']);
                $record['code']         = intval($_GPC['code']);
                $record['mobile']       = trim($_GPC['mobile']);
                $record['skins']        = trim($_GPC['skins']);
                $record['share_url']    = trim($_GPC['share_url']);
                $record['follow']       = intval($_GPC['follow']);
                $record['is_list']      = intval($_GPC['is_list']);
                $record['is_num']       = intval($_GPC['is_num']);
                $record['outlink']      = trim($_GPC['outlink']);
                $record['isdel']        = intval($_GPC['isdel']);
                $record['isthumb']      = intval($_GPC['isthumb']);
                $record['is_addr']      = intval($_GPC['is_addr']);
                $record['is_time']      = intval($_GPC['is_time']);
                $record['iscard']       = intval($_GPC['iscard']);
                $record['timelist']     = intval($_GPC['timelist']);
                $record['srvtime']      = htmlspecialchars_decode($_GPC['srvtime']);
                $record['out1']         = trim($_GPC['out1']);
                $record['out2']         = trim($_GPC['out2']);
                $record['out3']         = trim($_GPC['out3']);
                $record['out4']         = trim($_GPC['out4']);
                $record['out5']         = trim($_GPC['out5']);
                $record['out6']         = trim($_GPC['out6']);
                $record['out7']         = trim($_GPC['out7']);
                $record['cid']          = intval($_GPC['cate']);
                $record['restrict']     = intval($_GPC['restrict']);
                if (!empty($_GPC['remove'])) {
                    $record['remove'] = implode(',', $_GPC['remove']);
                }
                $record['day']     = intval($_GPC['day']);
                $record['daynum']  = intval($_GPC['daynum']);
                $record['smsid']   = $_GPC['smsid'];
                $record['smstype'] = $_GPC['smstype'];
                $record['role']    = intval($_GPC['role']);
                $record['store']   = intval($_GPC['store']);
                $record['par']     = iserializer($data);
                $record['switch']  = iserializer($switch);
                if (empty($reid)) {
                    $record['status']     = 1;
                    $record['createtime'] = TIMESTAMP;
                    pdo_insert($this->tb_yuyue, $record);
                    $reid = pdo_insertid();
                    if (!$reid) {
                        message('保存预约失败, 请稍后重试.');
                    }
                } else {
                    if (pdo_update($this->tb_yuyue, $record, array(
                        'reid' => $reid
                    )) === false) {
                        message('保存预约失败, 请稍后重试.');
                    }
                }
                pdo_delete($this->tb_group, array(
                    'weid' => $_W['uniacid'],
                    'reid' => $reid
                ));
                if ($_GPC['group'] && $reid) {
                    foreach ($_GPC['group'] as $gid) {
                        $gid    = intval($gid);
                        $insert = array(
                            'weid' => $_W['uniacid'],
                            'reid' => $reid,
                            'groupid' => $gid
                        );
                        pdo_insert($this->tb_group, $insert) ? '' : message('抱歉，更新失败！', referer(), 'error');
                        unset($insert);
                    }
                }
                if ($setting['role'] == 1 && ($_W['role'] == 'founder' || $_W['role'] == 'manager')) {
                    pdo_delete($this->tb_role, array(
                        'weid' => $_W['uniacid'],
                        'reid' => $reid
                    ));
                    if ($_GPC['role'] && $reid) {
                        foreach ($_GPC['role'] as $rid) {
                            $rid    = intval($rid);
                            $insert = array(
                                'weid' => $_W['uniacid'],
                                'reid' => $reid,
                                'roleid' => $rid
                            );
                            pdo_insert($this->tb_role, $insert) ? '' : message('抱歉，更新失败！', referer(), 'error');
                            unset($insert);
                        }
                    }
                }
                if (!$hasData) {
                    $sql             = 'DELETE FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid';
                    $params          = array();
                    $params[':reid'] = $reid;
                    pdo_query($sql, $params);
                    foreach ($_GPC['title'] as $k => $v) {
                        $field                 = array();
                        $field['reid']         = $reid;
                        $field['title']        = trim($v);
                        $field['displayorder'] = range_limit($_GPC['displayorder'][$k], 0, 254);
                        $field['type']         = $_GPC['type'][$k];
                        $field['essential']    = $_GPC['essentialvalue'][$k] == 'true' ? 1 : 0;
                        $field['bind']         = $_GPC['bind'][$k];
                        $field['value']        = $_GPC['value'][$k];
                        $field['value']        = urldecode($field['value']);
                        $field['description']  = $_GPC['desc'][$k];
                        $field['image']        = $_GPC['image'][$k];
                        $field['loc']          = $_GPC['loc'][$k];
                        pdo_insert($this->tb_field, $field);
                    }
                }
                message('保存预约成功.', $this->createWebUrl('display'), 'success');
            }
            $types               = array();
            $types['number']     = '数字(number)';
            $types['text']       = '字串(text)';
            $types['textarea']   = '文本(textarea)';
            $types['radio']      = '单选(radio)';
            $types['checkbox']   = '多选(checkbox)';
            $types['select']     = '下拉框(select)';
            $types['calendar']   = '日期(calendar)';
            $types['range']      = '时间(range)';
            $types['email']      = '邮件(email)';
            $types['image']      = '上传图片(image)';
            $types['reside']     = '省市区(reside)';
            $types['photograph'] = '证件照(photo)';
            pdo_tableexists('dayu_tingli') && $types['tingli'] = '听力数据';
            $fields = mc_fields();
            if ($reid) {
                $activity = $this->get_yuyuepay($reid);
                $par      = iunserializer($activity['par']);
                $switch   = iunserializer($activity['switch']);
                $remove   = !empty($activity['remove']) ? explode(',', $activity['remove']) : '';
                $activity['starttime'] && $activity['starttime'] = date('Y-m-d H:i:s', $activity['starttime']);
                $activity['endtime'] && $activity['endtime'] = date('Y-m-d H:i:s', $activity['endtime']);
                if ($activity) {
                    $sql             = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY `displayorder` DESC';
                    $params          = array();
                    $params[':reid'] = $reid;
                    $ds              = pdo_fetchall($sql, $params);
                }
                $role = $this->get_isrole($reid, $_W['user']['uid']);
                if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
                    message('您没有权限进行该操作.');
            }
            if (!$activity) {
                $par      = array(
                    "state1" => "待受理",
                    "state2" => "受理中",
                    "state3" => "已完成",
                    "state4" => "拒绝受理",
                    "state5" => "已取消",
                    "state8" => "退回修改",
                    "mname" => "我的预约",
                    "submit" => "立 即 提 交",
                    "xmname" => "服务项目",
                    "numname" => "数量",
                    "yuyuename" => "预约时间",
                    "kfirst" => "有新的客户预约，请及时确认",
                    "kfoot" => "点击确认预约，或修改预约时间",
                    "mfirst" => "预约结果通知",
                    "mfoot" => "如有疑问，请致电联系我们"
                );
                $activity = array(
                    "information" => "您的预约申请我们已经收到, 请等待客服确认.",
                    "phone" => "手机",
                    "pretotal" => "0",
                    "day" => 12,
                    "status" => 1,
                    "pay" => 2,
                    "follow" => 1,
                    "is_time" => 2,
                    "is_list" => 1,
                    "is_addr" => 0,
                    "daynum" => 0,
                    "timelist" => 1,
                    "skins" => "weui",
                    "isthumb" => 1,
                    "endtime" => date('Y-m-d H:i:s', strtotime('+365 day'))
                );
            }
        } elseif ($op == 'verify') {
            if ($_W['isajax']) {
                $openid   = trim($_GPC['openid']);
                $nickname = trim($_GPC['nickname']);
                if (!empty($openid)) {
                    $sql   = 'SELECT openid,nickname FROM ' . tablename('mc_mapping_fans') . " WHERE acid =:acid AND openid = :openid";
                    $exist = pdo_fetch($sql, array(
                        ':openid' => $openid,
                        ':acid' => $_W['acid']
                    ));
                } else {
                    $sql   = 'SELECT openid,nickname FROM ' . tablename('mc_mapping_fans') . " WHERE acid =:acid AND nickname = :nickname";
                    $exist = pdo_fetch($sql, array(
                        ':nickname' => $nickname,
                        ':acid' => $_W['acid']
                    ));
                }
                if (empty($exist)) {
                    message(error(-1, '未找到对应的粉丝编号，请检查昵称或openid是否有效'), '', 'ajax');
                }
                message(error(0, $exist), '', 'ajax');
            }
        }
        load()->func('tpl');
        if (pdo_tableexists('dayu_sms')) {
            $sms = pdo_fetchall("SELECT * FROM " . tablename('dayu_sms') . " WHERE weid = :weid", array(
                ':weid' => $_W['uniacid']
            ));
        }
        if (pdo_tableexists('dayu_yuyuepay_skins')) {
            $skins = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay_skins') . " WHERE status = 1 ORDER BY id", array());
        }
        if (pdo_tableexists('dayu_comment_category')) {
            $comment = pdo_fetchall("SELECT * FROM " . tablename('dayu_comment_category') . " WHERE weid = :weid", array(
                ':weid' => $_W['uniacid']
            ));
        }
        $category = pdo_fetchall("select * from " . tablename($this->tb_category) . " where weid = :weid ORDER BY id DESC", array(
            ':weid' => $weid
        ));
        include $this->template('post');
    }
    public function doWebStaff()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $op       = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        $weid     = $_W['uniacid'];
        $reid     = intval($_GPC['reid']);
        $activity = $this->get_yuyuepay($reid);
        $par      = iunserializer($activity['par']);
        $role     = $this->get_isrole($reid, $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        if ($op == 'list') {
            $where           = 'weid = :weid and reid = :reid';
            $params[':weid'] = $weid;
            $params[':reid'] = $reid;
            if (!empty($_GPC['keyword'])) {
                $where .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 20;
            $total  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_staff) . ' WHERE ' . $where, $params);
            $lists  = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_staff) . ' WHERE ' . $where . ' ORDER BY createtime DESC,id ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params, 'id');
            $pager  = pagination($total, $pindex, $psize);
            if (checksubmit('submit')) {
                if (!empty($_GPC['ids'])) {
                    foreach ($_GPC['ids'] as $k => $v) {
                        $data = array(
                            'nickname' => trim($_GPC['nickname'][$k]),
                            'openid' => trim($_GPC['openid'][$k]),
                            'weid' => $weid
                        );
                        pdo_update($this->tb_staff, $data, array(
                            'reid' => $reid,
                            'id' => intval($v)
                        ));
                    }
                    message('编辑成功', $this->createWebUrl('staff', array(
                        'op' => 'list',
                        'reid' => $reid
                    )), 'success');
                }
            }
            include $this->template('staff');
        } elseif ($op == 'post') {
            if (checksubmit('submit')) {
                $data['reid']       = $reid;
                $data['nickname']   = $_GPC['nickname'];
                $data['openid']     = $_GPC['openid'];
                $data['weid']       = $weid;
                $data['createtime'] = time();
                pdo_insert($this->tb_staff, $data);
                message('添加客服成功', $this->createWebUrl('staff', array(
                    'reid' => $reid,
                    'op' => 'list'
                )), 'success');
            }
            include $this->template('staff');
        } elseif ($op == 'staffdel') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                pdo_delete($this->tb_staff, array(
                    'weid' => $weid,
                    'id' => $id
                ));
            }
            message('删除成功.', referer());
        }
    }
    public function doWebchangecheckedAjax2()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $id   = $_GPC['id'];
        $role = $this->get_isrole($id, $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        $kfid = $_GPC['kfid'];
        if (!empty($kfid)) {
            if (false !== pdo_update($this->tb_yuyue, array(
                'kfid' => $kfid
            ), array(
                'reid' => intval($id),
                'weid' => $_W['uniacid']
            ))) {
                exit('1');
            } else {
                exit('0');
            }
        }
    }
    public function doWebchangecheckedAjax()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $id     = $_GPC['id'];
        $field  = $_GPC['field'];
        $change = $_GPC['change'];
        if ($field == 'kfid') {
            if (false !== pdo_update($this->tb_yuyue, array(
                'kfid' => $change
            ), array(
                'reid' => intval($id),
                'weid' => $_W['uniacid']
            ))) {
                exit('1');
            } else {
                exit('0');
            }
        }
        if ($field == 'guanli') {
            if (false !== pdo_update($this->tb_yuyue, array(
                'guanli' => $change
            ), array(
                'reid' => intval($id),
                'weid' => $_W['uniacid']
            ))) {
                exit('1');
            } else {
                exit('0');
            }
        }
    }
    public function doMobileTimelist()
    {
        global $_GPC, $_W;
        require 'fans.mobile.php';
        $setting   = $this->module['config'];
        $returnUrl = urlencode($_W['siteurl']);
        $id        = intval($_GPC['id']);
        $activity  = $this->get_yuyuepay($id);
        $par       = iunserializer($activity['par']);
        $datetime  = $_GPC['datetime'];
        $project   = pdo_get($this->tb_yuyue, array(
            'reid' => $id,
            'weid' => $weid,
            'status' => 1
        ), array(
            'reid',
            'weid',
            'is_time',
            'timelist',
            'srvtime',
            'day',
            'remove',
            'follow',
            'out1',
            'out2',
            'out3',
            'out4',
            'out5',
            'out6',
            'out7',
            'restrict',
            'endtime',
            'store'
        ));
        if ($project['is_time'] != 2) {
            header('Location: ' . $this->createMobileUrl('dayu_yuyuepay', array(
                'id' => $project['reid']
            )), true, 301);
        }
        if ($project['follow'] == 1) {
            $this->getFollow();
        }
        $week    = array(
            "7",
            "1",
            "2",
            "3",
            "4",
            "5",
            "6"
        );
        $out1    = array_map('change_to_quotes', explode(',', $project['out1']));
        $out2    = array_map('change_to_quotes', explode(',', $project['out2']));
        $out3    = array_map('change_to_quotes', explode(',', $project['out3']));
        $out4    = array_map('change_to_quotes', explode(',', $project['out4']));
        $out5    = array_map('change_to_quotes', explode(',', $project['out5']));
        $out6    = array_map('change_to_quotes', explode(',', $project['out6']));
        $out7    = array_map('change_to_quotes', explode(',', $project['out7']));
        $link    = $this->createMobileUrl('todayTime', array(
            'id' => $id
        ));
        $mgroup  = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE uniacid = '{$weid}' AND uid = '{$uid}'");
        $yygroup = pdo_fetchall('SELECT groupid FROM ' . tablename($this->tb_group) . " WHERE weid = '{$weid}' AND reid = '{$id}'");
        foreach ($yygroup as $li) {
            $group[] = $li['groupid'];
        }
        if ($yygroup && !in_array($mgroup['groupid'], $group)) {
            $this->showMessage('您所在的用户组没有相关的操作权限');
        }
        $address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(
            ':uid' => $_W['fans']['uid']
        ));
        if ($activity['is_addr'] == 1 && !$address) {
            $this->showMessage('请先完善您的资料', $this->createMobileUrl('address', array(
                'from' => 'dayu_yuyuepay',
                'returnurl' => $returnUrl
            )), 'info');
        }
        $weekarray = array(
            "周日",
            "周一",
            "周二",
            "周三",
            "周四",
            "周五",
            "周六"
        );
        $remove    = explode(',', $project['remove']);
        $out       = !empty($project['remove']) ? "1" : "0";
        $haha      = count($remove);
        $like      = !empty($project['remove']) ? $project['day'] + $haha - $out : $project['day'];
        $timelist  = json_decode($project['srvtime'], TRUE);
        $paytime   = strtotime("now") - $setting['paytime'] * 60;
        $todaytime = !empty($setting['today']) ? $setting['today'] * 60 : 3600;
        if ($project['restrict'] == 1) {
            $havs = pdo_fetchall("SELECT restime,num,SUM(num) AS rescount FROM " . tablename($this->tb_info) . " WHERE ((status='0' and paystatus='2') or (status='0' and paystatus='1' and createtime >= '{$paytime}') or status='1' or status='3') AND reid=:id GROUP BY restime", array(
                ':id' => $id
            ), 'restime');
        } else {
            $havs = pdo_fetchall("SELECT restime,count(reid) as rescount from " . tablename($this->tb_info) . " WHERE ((status='0' and paystatus='2') or (status='0' and paystatus='1' and createtime >= '{$paytime}') or status='1' or status='3') AND reid=:id GROUP BY restime", array(
                ':id' => $id
            ), 'restime');
        }
        $dates = array();
        $now   = new DateTime();
        $today = $par['today'];
        while (count($dates) < $like) {
            if ($today != '0') {
                $now->modify('+1 day');
            }
            if (in_array($now->format('w'), $timelist['weekset'])) {
                $dates[] = $now->format('Y-m-d');
            }
            $today++;
        }
        if (!empty($datetime)) {
            $srvtime = base64_decode($datetime);
            $in      = date("m-d", strtotime($srvtime));
        }
        $isma       = pdo_get($this->tb_yuyue, array(
            'weid' => $weid,
            'kfid' => $openid
        ), array());
        $footer_off = $par['member'] == 1 ? 1 : 0;
        $title      = $activity['title'];
        if ($project['timelist'] == 0) {
            include $this->template('timelist');
        } elseif ($project['timelist'] == 1) {
            include $this->template('timelists');
        } elseif ($project['timelist'] == 2) {
            include $this->template('timelist3');
        }
    }
    public function doMobiletodayTime()
    {
        global $_GPC, $_W;
        require 'fans.mobile.php';
        $setting   = $this->module['config'];
        $returnUrl = urlencode($_W['siteurl']);
        $id        = intval($_GPC['id']);
        $datetime  = $_GPC['datetime'];
        $sql       = "SELECT reid,weid,is_time,timelist,srvtime,day,follow,out1,out2,out3,out4,out5,out6,out7 FROM " . tablename($this->tb_yuyue) . " WHERE status=1 AND weid='{$weid}' AND reid='{$_GPC['id']}'";
        $project   = pdo_fetch($sql);
        if ($project['follow'] == 1) {
            $this->getFollow();
        } else {
            $member = !empty($member) ? $member : $_SESSION['userinfo'];
            if (empty($member['avatar'])) {
                if ($_W['account']['level'] > 3) {
                    $member = mc_oauth_userinfo($_W['acid']);
                } else {
                    $member = mc_oauth_fans($openid, $_W['acid']);
                }
            }
        }
        $week      = array(
            "7",
            "1",
            "2",
            "3",
            "4",
            "5",
            "6"
        );
        $out1      = array_map('change_to_quotes', explode(',', $project['out1']));
        $out2      = array_map('change_to_quotes', explode(',', $project['out2']));
        $out3      = array_map('change_to_quotes', explode(',', $project['out3']));
        $out4      = array_map('change_to_quotes', explode(',', $project['out4']));
        $out5      = array_map('change_to_quotes', explode(',', $project['out5']));
        $out6      = array_map('change_to_quotes', explode(',', $project['out6']));
        $out7      = array_map('change_to_quotes', explode(',', $project['out7']));
        $link      = $_W['siteroot'] . 'app/' . $this->createMobileUrl('Timelist', array(
            'id' => $id
        ));
        $weekarray = array(
            "周日",
            "周一",
            "周二",
            "周三",
            "周四",
            "周五",
            "周六"
        );
        if (empty($datetime)) {
            $timelist = json_decode($project['srvtime'], TRUE);
            if (!in_array(date("w"), $timelist['weekset'])) {
                $this->showMessage('今天休息哦，预约请改天。');
            }
            $paytime   = strtotime("now") - $setting['paytime'] * 60;
            $todaytime = !empty($setting['today']) ? $setting['today'] * 60 : 3600;
            $havs      = pdo_fetchall("SELECT restime,count(reid) as rescount from " . tablename($this->tb_info) . " WHERE ((status=0 and paystatus=2) or (status=0 and paystatus=1 and createtime >= '{$paytime}') or status=1 or status=3) AND reid=:id GROUP BY restime", array(
                ':id' => $id
            ), 'restime');
            $now       = new DateTime();
            $dates     = $now->format('Y-m-d');
            $isma      = pdo_get($this->tb_yuyue, array(
                'weid' => $weid,
                'kfid' => $openid
            ), array());
            $title     = '选择预约时间';
            include $this->template('today');
        } else {
            $srvtime = base64_decode($datetime);
            $member  = mc_fetch($_W['member']['uid'], array(
                'realname',
                'gender',
                'mobile'
            ));
            include $this->template($project['skins']);
        }
    }
    public function doMobiledayu_yuyuepay()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $op        = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
        $returnUrl = urlencode($_W['siteurl']);
        $qqkey     = $setting['qqkey'];
        $reid      = intval($_GPC['id']);
        $activity  = $this->get_yuyuepay($reid);
        $par       = iunserializer($activity['par']);
        $switch    = iunserializer($activity['switch']);
        $submit    = !empty($par['submit']) ? $par['submit'] : '立 即 提 交';
        if ($activity['follow'] == 1) {
            $this->getFollow();
        } else {
            $member = !empty($member) ? $member : $_SESSION['userinfo'];
            if (empty($member['avatar'])) {
                if ($_W['account']['level'] > 3) {
                    $member = mc_oauth_userinfo($_W['acid']);
                } else {
                    $member = mc_oauth_fans($openid, $_W['acid']);
                }
            }
        }
        $repeat = $_COOKIE['r_submit'];
        if (!empty($_GPC['repeat'])) {
            if (!empty($repeat)) {
                if ($repeat == $_GPC['repeat']) {
                    $this->showMessage($activity['information'], $this->createMobileUrl('mydayu_yuyuepay', array(
                        'weid' => $weid,
                        'id' => $reid
                    )));
                } else {
                    setcookie("r_submit", $_GPC['repeat']);
                }
            } else {
                setcookie("r_submit", $_GPC['repeat']);
            }
        }
        $datetime  = $_GPC['datetime'];
        $ii        = $_GPC['ii'];
        $srvtime   = base64_decode($datetime);
        $xms       = pdo_fetchall("SELECT * FROM " . tablename($this->tb_item) . " WHERE weid = :weid and reid = :reid and isshow=1 ORDER BY displayorder DESC,id DESC", array(
            ':weid' => $_W['uniacid'],
            ':reid' => $reid
        ));
        $xmstotal  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_item) . " WHERE weid = :weid and reid = :reid and isshow=1", array(
            ':weid' => $_W['uniacid'],
            ':reid' => $reid
        ));
        $title     = $activity['title'];
        $time      = date('Y-m-d', time());
        $yuyuetime = date('Y-m-d H:i', time() + 3600);
        if ($activity['status'] != '1') {
            $this->showMessage('当前预约已经停止.');
        }
        if (!$activity) {
            $this->showMessage('没找到相应的主题.', '', 'error');
        }
        if ($activity['starttime'] > TIMESTAMP) {
            $this->showMessage('当前预约还未开始！');
        }
        if ($activity['endtime'] < TIMESTAMP) {
            $this->showMessage('当前预约活动已经结束！');
        }
        $activity['thumb'] = tomedia($activity['thumb']);
        if (intval($par['allnum']) != 0) {
            $allnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid and (status=0 or status=1 or status=3)", array(
                ':reid' => $reid
            ));
            if ($allnum >= intval($par['allnum'])) {
                $this->showMessage('名额已满', '', 'info');
            }
        }
        if ($activity['daynum'] != 0) {
            $today    = strtotime('today');
            $tomorrow = strtotime('tomorrow');
            $lognum   = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_info) . " WHERE openid = '{$openid}' AND reid = '{$reid}' AND createtime > " . $today . ' AND createtime < ' . $tomorrow);
            if ($lognum >= $activity['daynum']) {
                $this->showMessage('抱歉,每天只能预约' . $activity['daynum'] . "次！", '', 'info');
            }
        }
        if ($activity['pretotal'] != 0) {
            $pretotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid AND openid = :openid", array(
                ':reid' => $reid,
                ':openid' => $_W['openid']
            ));
            if ($pretotal >= $activity['pretotal'])
                $this->showMessage('抱歉,每人只能预约' . $activity['pretotal'] . "次！", referer(), 'error');
        }
        if ($activity['iscard'] == 1 || $activity['iscard'] == 2) {
            $ishy     = $this->isHy($openid);
            $card_url = $activity['iscard'] == 1 ? $this->createMobileUrl('mycard', array(
                'a' => 'card',
                'c' => 'mc',
                'i' => $weid,
                'returnurl' => $returnUrl
            ), false) : murl('entry', array(
                'do' => 'index',
                'm' => 'dayu_card',
                'returnurl' => $returnUrl
            ), true, true);
            if ($ishy == false) {
                $this->showMessage('请领取您的会员卡.', $card_url, 'info');
            }
        }
        $address = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(
            ':uid' => $_W['fans']['uid']
        ));
        if ($activity['is_addr'] == 1 && !$address) {
            $this->showMessage('请先完善资料', $this->createMobileUrl('address', array(
                'from' => 'dayu_yuyuepay',
                'returnurl' => $returnUrl
            )), 'info');
        }
        $mgroup  = pdo_fetch("SELECT * FROM " . tablename('mc_members') . " WHERE uniacid = '{$weid}' AND uid = '{$uid}'");
        $yygroup = pdo_fetchall('SELECT groupid FROM ' . tablename($this->tb_group) . " WHERE weid = '{$weid}' AND reid = '{$reid}'");
        foreach ($yygroup as $li) {
            $group[] = $li['groupid'];
        }
        if ($yygroup && !in_array($mgroup['groupid'], $group)) {
            $this->showMessage('您所在的用户组没有相关的操作权限');
        }
        $sql             = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid` = :reid ORDER BY `displayorder` DESC';
        $params          = array();
        $params[':reid'] = $reid;
        $ds              = pdo_fetchall($sql, $params);
        if (!$ds) {
            $this->showMessage('自定义字段不能为空.');
        }
        $initRange = $initCalendar = false;
        $binds     = array();
        $profile   = mc_fetch($uid, $binds);
        foreach ($binds as $key => $value) {
            if ($value == 'reside') {
                unset($binds[$key]);
                $binds[] = 'resideprovince';
                $binds[] = 'residecity';
                $binds[] = 'residedist';
                break;
            }
            if ($value == 'birth') {
                unset($binds[$key]);
                $binds[] = 'birthyear';
                $binds[] = 'birthmonth';
                $binds[] = 'birthday';
                break;
            }
        }
        foreach ($ds as &$r) {
            if ($r['type'] == 'range') {
                $initRange = true;
            }
            if ($r['type'] == 'calendar') {
                $initCalendar = true;
            }
            if ($r['value']) {
                $r['options'] = explode(',', $r['value']);
            }
            if ($r['bind']) {
                $binds[$r['type']] = $r['bind'];
            }
            if ($r['type'] == 'reside') {
                $reside = $r;
            }
            if ($r['type'] == 'textarea' && $r['loc'] > 0) {
                $isloc = $r;
            }
            if ($r['type'] == 'image') {
                $r['image']  = !empty($r['image']) ? $r['image'] : TEMPLATE_WEUI . "images/nopic.jpg";
                $r['tixing'] = !empty($r['description']) ? urldecode($r['description']) : "请上传" . $r['title'];
            }
            if (in_array($r['type'], array(
                'text',
                'number',
                'email'
            ))) {
                $r['tixing'] = !empty($r['description']) ? urldecode($r['description']) : "请输入" . $r['title'];
            } elseif ($r['type'] == 'textarea') {
                $r['tixing'] = !empty($r['description']) ? urldecode($r['description']) : "请填写" . $r['title'];
            } else {
                $r['tixing'] = !empty($r['description']) ? urldecode($r['description']) : "请选择" . $r['title'];
            }
            if ($profile['gender']) {
                if ($profile['gender'] == '0')
                    $profile['gender'] = '保密';
                if ($profile['gender'] == '1')
                    $profile['gender'] = '男';
                if ($profile['gender'] == '2')
                    $profile['gender'] = '女';
            }
            if ($profile[$r['bind']]) {
                $r['default'] = $profile[$r['bind']];
            }
            if ($r['type'] == 'tingli' && pdo_tableexists('dayu_tingli')) {
                $r['tingli']    = pdo_fetch("SELECT * FROM " . tablename('dayu_tingli') . " WHERE weid = :weid AND openid = :openid ORDER BY id DESC", array(
                    ':weid' => $weid,
                    ':openid' => $openid
                ));
                $r['tingliurl'] = murl('entry', array(
                    'do' => 'index',
                    'm' => 'dayu_tingli',
                    'returnurl' => $returnUrl
                ), true, true);
                $r['default']   = 'L：' . $r['tingli']['left_250'] . ' ' . $r['tingli']['left_500'] . ' ' . $r['tingli']['left_1k'] . ' ' . $r['tingli']['left_2k'] . ' ' . $r['tingli']['left_4k'] . ' ' . $r['tingli']['left_8k'] . '；R：' . $r['tingli']['right_250'] . ' ' . $r['tingli']['right_500'] . ' ' . $r['tingli']['right_1k'] . ' ' . $r['tingli']['right_2k'] . ' ' . $r['tingli']['right_4k'] . ' ' . $r['tingli']['right_8k'];
            }
            ($r['type'] == 'photograph' && empty($profile[$r['bind']])) && $this->showMessage('请完善' . $r['title'], murl('entry', array(
                'do' => 'index',
                'f' => $r['bind'],
                'm' => 'dayu_photograph',
                'returnurl' => $returnUrl
            ), true, true), 'info');
            pdo_tableexists('dayu_photograph_fields') && $r['photograph_url'] = murl('entry', array(
                'do' => 'index',
                'f' => $r['bind'],
                'm' => 'dayu_photograph',
                'returnurl' => $returnUrl
            ), true, true);
        }
        $xm = pdo_fetch("SELECT * FROM " . tablename($this->tb_item) . " WHERE id = :id", array(
            ':id' => $_GPC['xmid']
        ));
        if (pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($sid) && !empty($activity['store'])) {
            $store              = pdo_get('dayu_yuyuepay_plugin_store_store', array(
                'weid' => $weid,
                'id' => $sid
            ), array());
            $store['score_num'] = $store['score_num'] == 0 ? 5 : round(($store['total_score'] / $store['score_num']), 0);
            if (pdo_tableexists('dayu_ishare_record')) {
                $share_record = pdo_get('dayu_ishare_record', array(
                    'weid' => $weid,
                    'openid' => $openid,
                    'gid' => $sid,
                    'addons' => 'dayu_yuyuepay_plugin_store'
                ), array());
                $get_share    = $share_record['share'];
            }
        }
        $paytime  = strtotime("now") - $setting['paytime'] * 60;
        $timelist = json_decode($activity['srvtime'], TRUE);
        $havsnum  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE ((status=0 and paystatus=2) or (status=0 and paystatus=1 and createtime >= '{$paytime}') or status=1 or status=3) AND reid = :reid AND restime LIKE '%{$srvtime}%'", array(
            ':reid' => $reid
        ));
        $timenum  = $timelist['times'][$ii]['number'];
        if ($_W['ispost']) {
            if ($activity['is_time'] == 2) {
                if ($_GPC['havsnum'] >= $_GPC['timenum']) {
                    $this->showMessage('已约满，重新选择时间', $this->createMobileUrl('timelist', array(
                        'id' => $reid,
                        'datetime' => $datetime
                    )), 'info');
                }
            }
            if (empty($address) && $activity['is_addr'] == 1)
                $this->showMessage('抱歉，联系方式不能为空！');
            if (empty($_GPC['restime']) && $activity['is_time'] == 2)
                $this->showMessage('预约时间不能为空，请点击下面的链接返回选择预约时间', $this->createMobileUrl('timelist', array(
                    'id' => $reid
                )), 'error');
            $num = empty($_GPC['num']) ? 1 : $_GPC['num'];
            if ($par['member'] == 0 && $activity['is_addr'] == 1) {
                $member  = $address['username'];
                $mobile  = $address['mobile'];
                $address = $address['province'] . $address['city'] . $address['district'] . $address['address'];
                $infourl = $this->createMobileUrl('address', array(
                    'from' => 'dayu_yuyuepay',
                    'returnurl' => $returnUrl
                ));
            } else {
                $member  = $_GPC['member'];
                $mobile  = $_GPC['mobile'];
                $address = "";
                $infourl = $this->createMobileUrl('info', array(
                    'from' => 'dayu_yuyuepay',
                    'returnurl' => $returnUrl
                ));
            }
            if (!$mobile || !$member)
                $this->showMessage('请先完善您的资料', $infourl, 'info');
            $row              = array();
            $row['reid']      = $reid;
            $row['member']    = $member;
            $row['mobile']    = $mobile;
            $row['address']   = $address;
            $row['openid']    = $_W['openid'];
            $row['xmid']      = $_GPC['xmid'];
            $row['ordersn']   = date('ymdHis') . random(3, 1);
            $row['num']       = $num;
            $row['price']     = $xm['price'] * $num;
            $row['paystatus'] = 1;
            $row['share']     = $get_share;
            $row['paytype']   = $activity['pay'] == '1' ? intval($_GPC['paytype']) : '1';
            if ($activity['is_time'] == 0) {
                $row['yuyuetime'] = strtotime($_GPC['yuyuetime']);
            } elseif ($activity['is_time'] == 2) {
                $row['restime'] = $_GPC['restime'];
            }
            if (!empty($_GPC['sid']) && !empty($activity['store'])) {
                $row['sid'] = $_GPC['sid'];
            }
            $row['createtime'] = TIMESTAMP;
            $datas             = $fields = $update = array();
            foreach ($ds as $value) {
                $fields[$value['refid']] = $value;
            }
            foreach ($_GPC as $key => $value) {
                if (strexists($key, 'field_')) {
                    $bindFiled = substr(strrchr($key, '_'), 1);
                    if (!empty($bindFiled)) {
                        $update[$bindFiled] = $value;
                    }
                    $refid = intval(str_replace('field_', '', $key));
                    $field = $fields[$refid];
                    if ($refid && $field) {
                        $entry                 = array();
                        $entry['reid']         = $reid;
                        $entry['rerid']        = 0;
                        $entry['refid']        = $refid;
                        $entry['displayorder'] = $field['displayorder'];
                        if (in_array($activity['skins'], array(
                            'weui',
                            'weui_yuntai'
                        ))) {
                            if (in_array($field['type'], array(
                                'number',
                                'text',
                                'calendar',
                                'email',
                                'textarea',
                                'radio',
                                'range',
                                'select',
                                'image',
                                'reside',
                                'checkbox',
                                'photograph',
                                'tingli'
                            ))) {
                                $entry['data'] = strval($value);
                            }
                        } else {
                            if (in_array($field['type'], array(
                                'number',
                                'text',
                                'calendar',
                                'email',
                                'textarea',
                                'radio',
                                'range',
                                'select',
                                'image',
                                'reside',
                                'tingli'
                            ))) {
                                $entry['data'] = strval($value);
                            }
                            if (in_array($field['type'], array(
                                'checkbox'
                            ))) {
                                if (!is_array($value))
                                    continue;
                                $entry['data'] = implode(',', $value);
                            }
                        }
                        $datas[] = $entry;
                    }
                }
            }
            if (!empty($_GPC['reside'])) {
                if (in_array('reside', $binds)) {
                    $update['resideprovince'] = $_GPC['reside']['province'];
                    $update['residecity']     = $_GPC['reside']['city'];
                    $update['residedist']     = $_GPC['reside']['district'];
                }
                foreach ($_GPC['reside'] as $key => $value) {
                    $resideData          = array(
                        'reid' => $reside['reid']
                    );
                    $resideData['rerid'] = 0;
                    $resideData['refid'] = $reside['refid'];
                    $resideData['data']  = $value;
                    $datas[]             = $resideData;
                }
            }
            if (!empty($update)) {
                load()->model('mc');
                mc_update($_W['member']['uid'], $update);
            }
            if (empty($datas)) {
                $this->showMessage('表单内容不能为空.', '', 'error');
            }
            if (pdo_insert($this->tb_info, $row) != 1) {
                $this->showMessage('保存失败.');
            }
            $rerid   = pdo_insertid();
            $orderid = pdo_insertid();
            if (empty($rerid)) {
                $this->showMessage('保存失败.');
            }
            foreach ($datas as &$r) {
                $r['rerid'] = $rerid;
                pdo_insert($this->tb_data, $r);
            }
            if (empty($activity['starttime'])) {
                $record              = array();
                $record['starttime'] = TIMESTAMP;
                pdo_update($this->tb_yuyue, $record, array(
                    'reid' => $reid
                ));
            }
            if ($activity['is_time'] == 0) {
                $times = $_GPC['yuyuetime'];
            } elseif ($activity['is_time'] == 2) {
                $times = $_GPC['restime'];
            }
            foreach ($datas as $row) {
                if (strstr($row['data'], 'images')) {
                    $row['data'] = "有图";
                }
                $bodym .= '　' . $fields[$row['refid']]['title'] . '：' . $row['data'] . "\n";
                $bodnew .= '\\n' . $fields[$row['refid']]['title'] . '：' . $row['data'];
                $body .= '<h4>' . $fields[$row['refid']]['title'] . '：' . $row['data'] . '</h4>';
                $smsbody .= $fields[$row['refid']]['title'] . ':' . $row['data'];
            }
            $getxm   = $this->get_xiangmu($row['reid'], $_GPC['xmid']);
            $xiangmu = $activity['is_num'] == 1 ? $getxm['title'] . " - " . $getxm['price'] . "元 * " . $num : $getxm['title'] . " - " . $getxm['price'] . "元";
            if (!empty($datas) && !empty($activity['noticeemail'])) {
                load()->func('communication');
                ihttp_email($activity['noticeemail'], $activity['title'] . '的预约提醒', '<h4>姓名：' . $member . '</h4><h4>手机：' . $mobile . '</h4><h4>预约时间：' . $times . '</h4><h4>预约项目：' . $xiangmu . '</h4>' . $body);
            }
            $ptime   = intval(date('hi', time()));
            $paytype = $_GPC['paytype'] == 1 ? "在线支付 待支付" : "线下支付";
            $add     = $activity['is_addr'] == 1 ? "\\n地址：" . $address['province'] . $address['city'] . $address['district'] . $address['address'] : "";
            if (!empty($_GPC['sid'])) {
                $store   = pdo_get('dayu_yuyuepay_plugin_store_store', array(
                    'weid' => $weid,
                    'id' => $_GPC['sid']
                ), array());
                $kfid    = $store['openid'];
                $staff   = pdo_fetchall("SELECT `openid` FROM " . tablename('dayu_yuyuepay_plugin_store_store') . " WHERE weid=:weid and id=:id", array(
                    ':weid' => $weid,
                    ':id' => $_GPC['sid']
                ));
                $address = '\\n' . $store['name'] . '：' . $store['loc_p'] . $store['loc_c'] . $store['loc_a'] . $store['address'];
            } else {
                $kfid    = $activity['kfid'];
                $staff   = pdo_fetchall("SELECT `openid` FROM " . tablename($this->tb_staff) . " WHERE weid=:weid and reid=:reid", array(
                    ':weid' => $weid,
                    ':reid' => $reid
                ));
                $address = '';
            }
            if ($activity['is_time'] != 1) {
                $time = $activity['is_time'] == 2 ? "\\n" . $par['yuyuename'] . "：" . $_GPC['restime'] : "\\n" . $par['yuyuename'] . "：" . $_GPC['yuyuetime'];
            }
            if ($setting['stime'] != 0 && $setting['etime'] != 0) {
                if ($setting['stime'] < $ptime && $setting['etime'] > $ptime) {
                    if ($setting['newtemp'] == 1 && $setting['notice'] == 0) {
                        if (!empty($activity['k_templateid'])) {
                            $template = array(
                                "touser" => $kfid,
                                "template_id" => $activity['k_templateid'],
                                "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                                    'weid' => $weid,
                                    'id' => $row['rerid'],
                                    'reid' => $row['reid'],
                                    'sid' => $sid
                                )),
                                "topcolor" => "#FF0000",
                                "data" => array(
                                    'first' => array(
                                        'value' => urlencode("粉丝：" . $fans['nickname'] . "提交了新订单\\n"),
                                        'color' => "#743A3A"
                                    ),
                                    'keyword1' => array(
                                        'value' => urlencode(date('Y年m月d日 H:i', TIMESTAMP)),
                                        'color' => '#000000'
                                    ),
                                    'keyword2' => array(
                                        'value' => urlencode($paytype),
                                        'color' => '#EF4F4F'
                                    ),
                                    'remark' => array(
                                        'value' => urlencode("\\n姓名：" . $member . "\\n手机：" . $mobile . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $address . $time . $bodnew),
                                        'color' => "#01579b"
                                    )
                                )
                            );
                            $this->send_template_message(urldecode(json_encode($template)));
                        }
                    } elseif ($setting['newtemp'] == 0 && $setting['notice'] == 0) {
                        if (!empty($activity['k_templateid']) && is_array($staff)) {
                            foreach ($staff as $s) {
                                $template = array(
                                    "touser" => $s['openid'],
                                    "template_id" => $activity['k_templateid'],
                                    "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                                        'weid' => $weid,
                                        'id' => $row['rerid'],
                                        'reid' => $row['reid'],
                                        'sid' => $sid
                                    )),
                                    "topcolor" => "#FF0000",
                                    "data" => array(
                                        'first' => array(
                                            'value' => urlencode("粉丝：" . $fans['nickname'] . "提交了新订单\\n"),
                                            'color' => "#743A3A"
                                        ),
                                        'keyword1' => array(
                                            'value' => urlencode(date('Y年m月d日 H:i', TIMESTAMP)),
                                            'color' => '#000000'
                                        ),
                                        'keyword2' => array(
                                            'value' => urlencode($paytype),
                                            'color' => '#EF4F4F'
                                        ),
                                        'remark' => array(
                                            'value' => urlencode("\\n姓名：" . $member . "\\n手机：" . $mobile . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $address . $time . $bodnew),
                                            'color' => "#01579b"
                                        )
                                    )
                                );
                                $this->send_template_message(urldecode(json_encode($template)));
                            }
                        }
                    }
                }
            } else {
                if ($setting['newtemp'] == 1 && $setting['notice'] == 0) {
                    if (!empty($activity['k_templateid'])) {
                        $template = array(
                            "touser" => $kfid,
                            "template_id" => $activity['k_templateid'],
                            "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                                'weid' => $_W['uniacid'],
                                'id' => $row['rerid'],
                                'reid' => $row['reid'],
                                'sid' => $sid
                            )),
                            "topcolor" => "#FF0000",
                            "data" => array(
                                'first' => array(
                                    'value' => urlencode("粉丝：" . $fans['nickname'] . "提交了新订单\\n"),
                                    'color' => "#743A3A"
                                ),
                                'keyword1' => array(
                                    'value' => urlencode(date('Y年m月d日 H:i', TIMESTAMP)),
                                    'color' => '#000000'
                                ),
                                'keyword2' => array(
                                    'value' => urlencode($paytype),
                                    'color' => '#EF4F4F'
                                ),
                                'remark' => array(
                                    'value' => urlencode("\\n姓名：" . $member . "\\n手机：" . $mobile . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $address . $time . $bodnew),
                                    'color' => "#01579b"
                                )
                            )
                        );
                        $this->send_template_message(urldecode(json_encode($template)));
                    }
                } elseif ($setting['newtemp'] == 0 && $setting['notice'] == 0) {
                    if (!empty($activity['k_templateid']) && is_array($staff)) {
                        foreach ($staff as $s) {
                            $template = array(
                                "touser" => $s['openid'],
                                "template_id" => $activity['k_templateid'],
                                "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                                    'weid' => $_W['uniacid'],
                                    'id' => $row['rerid'],
                                    'reid' => $row['reid'],
                                    'sid' => $sid
                                )),
                                "topcolor" => "#FF0000",
                                "data" => array(
                                    'first' => array(
                                        'value' => urlencode("粉丝：" . $fans['nickname'] . "提交了新订单\\n"),
                                        'color' => "#743A3A"
                                    ),
                                    'keyword1' => array(
                                        'value' => urlencode(date('Y年m月d日 H:i', TIMESTAMP)),
                                        'color' => '#000000'
                                    ),
                                    'keyword2' => array(
                                        'value' => urlencode($paytype),
                                        'color' => '#EF4F4F'
                                    ),
                                    'remark' => array(
                                        'value' => urlencode("\\n姓名：" . $member . "\\n手机：" . $mobile . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $address . $time . $bodnew),
                                        'color' => "#01579b"
                                    )
                                )
                            );
                            $this->send_template_message(urldecode(json_encode($template)));
                        }
                    }
                }
            }
            $information = !empty($activity['information']) ? $activity['information'] : '3秒后自动前往付款页面...';
            $tishi       = $getxm['price'] == '0.00' ? '正在提交请稍后' : $information;
            if ($_GPC['paytype'] == 1) {
                $this->showMessage($tishi, $this->createMobileUrl('pay', array(
                    'orderid' => $orderid,
                    'weid' => $_GPC['weid'],
                    'id' => $_GPC['id']
                )), 'success');
            } else if ($_GPC['paytype'] == 9 || $activity['pay'] == 1) {
                $this->showMessage($activity['information'], $this->createMobileUrl('mydayu_yuyuepay', array(
                    'weid' => $row['weid'],
                    'id' => $row['reid']
                )), 'success');
            }
        }
        load()->func('tpl');
        $_share['title']   = $activity['title'];
        $_share['content'] = $activity['description'];
        $_share['imgUrl']  = tomedia($activity['thumb']);
        $jquery            = 2;
        include $this->template('skins/' . $activity['skins']);
    }
    public function doMobilechecktime()
    {
        global $_GPC, $_W;
        require 'fans.mobile.php';
        $restime            = base64_decode($_GPC['time']);
        $reid               = $_GPC['reid'];
        $day                = substr($restime, 0, strrpos($restime, ' '));
        $jl                 = $this->get_jl($reid);
        $timelist           = json_decode($jl['srvtime'], TRUE);
        $paytime            = strtotime("now") - $setting['paytime'] * 60;
        $pretotal           = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE ((status='0' and paystatus='2') or (status='0' and paystatus='1' and createtime >= '{$paytime}') or status='1' or status='3') AND reid = :reid AND xmid = :xmid AND restime like '%{$restime}%'", array(
            ':reid' => $reid,
            ':xmid' => $_GPC['xmid'],
            ':restime' => $restime
        ));
        $where              = 'reid=:reid AND openid = :openid AND restime like :restime AND (status=0 or status=1 or status=3)';
        $params             = array();
        $params[':reid']    = $reid;
        $params[':openid']  = $openid;
        $params[':restime'] = '%' . $day . '%';
        $already            = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE $where", $params);
        $total              = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE $where", $params);
        if ($timelist['times'][$_GPC['ii']]['number'] <= $pretotal) {
            $result['status'] = '0';
            $result['msg']    = $restime . '已约满';
        } elseif ($jl['daynum'] != 0 && $total >= $jl['daynum']) {
            $result['status'] = '2';
            $result['title']  = '每天只能预约' . $jl['daynum'] . '次';
            $result['msg']    = '已预约 ' . $already['restime'] . '<br>请选择其他日期';
        } else {
            $result['status'] = '1';
            $result['msg']    = "可以预约";
        }
        message($result, '', 'ajax');
    }
    public function doMobilegetnum()
    {
        global $_GPC, $_W;
        $restime  = $_GPC['restime'];
        $reid     = $_GPC['reid'];
        $sql      = "SELECT reid,weid,is_time,timelist,srvtime,day,follow FROM " . tablename($this->tb_yuyue) . " WHERE status=1 AND weid='{$_W['uniacid']}' AND reid='{$reid}'";
        $project  = pdo_fetch($sql);
        $timelist = json_decode($project['srvtime'], TRUE);
        $pretotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid AND restime = :restime", array(
            ':reid' => $reid,
            ':restime' => $restime
        ));
        if ($timelist['times'][0]['number'] <= $pretotal) {
            $result['status']  = 0;
            $result['restime'] = $restime . '已约满' . $result['status'];
            message($result, '', 'ajax');
        }
        $result['status']  = 1;
        $result['restime'] = "可以预约";
        message($result, '', 'ajax');
    }
    public function doMobilegetprice()
    {
        global $_GPC, $_W;
        $op       = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
        $setting  = $this->module['config'];
        $paytime  = strtotime("now") - $setting['paytime'] * 60;
        $item     = pdo_fetch("SELECT * FROM " . tablename($this->tb_item) . " WHERE weid = :weid AND id = :id AND isshow=1", array(
            ':weid' => $_W['uniacid'],
            ':id' => $_GPC['xmid']
        ));
        $activity = $this->get_yuyuepay($item['reid']);
        if (empty($item)) {
            $result['status'] = 0;
            $result['price']  = '无法获取价格';
            message($result, '', 'ajax');
        }
        $type           = $item['type'] == 1 ? '需付定金：' : '合计:';
        $item['price']  = $item['price'] == '0.00' ? '免费预约' : $type . ' &yen; ' . $item['price'];
        $item['prices'] = '&yen; ' . $item['prices'];
        $itime          = substr($_GPC['time'], 0, 10);
        $info           = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE ((status=0 and paystatus=2) or (status=0 and paystatus=1 and createtime >= '{$paytime}') or status=1 or status=3) AND reid = :reid AND xmid = :xmid AND restime like '%{$itime}%'", array(
            ':reid' => $item['reid'],
            ':xmid' => $item['id']
        ));
        if ($item['daynum'] == 0) {
            $result['status']  = 1;
            $result['type']    = $item['type'];
            $result['price']   = $item['price'];
            $result['prices']  = $item['prices'];
            $result['isc']     = $item['isc'];
            $result['content'] = htmlspecialchars_decode($item['content']);
        } else if ($item['daynum'] != 0 && $info < $item['daynum']) {
            $result['status']  = 1;
            $result['type']    = $item['type'];
            $result['price']   = $item['price'];
            $result['prices']  = $item['prices'];
            $result['isc']     = $item['isc'];
            $result['content'] = htmlspecialchars_decode($item['content']);
        } else {
            $result['status'] = 0;
            $result['title']  = $item['title'] . " 人数已满员";
            $result['msg']    = "请选择其他预约时间/日期或其他 " . $par['xmname'];
        }
        if ($op == 'num') {
            $num  = pdo_fetch("SELECT SUM(num) AS rescount FROM " . tablename($this->tb_info) . " WHERE ((status=0 and paystatus=2) or (status=0 and paystatus=1 and createtime >= '{$paytime}') or status=1 or status=3) AND reid=:id AND restime like '%{$_GPC['time']}%'", array(
                ':id' => $item['reid']
            ));
            $nums = $num['rescount'] + $_GPC['want'];
            $free = $_GPC['nums'] - $num['rescount'];
            if ($_GPC['nums'] < $nums) {
                $total            = "当前剩余名额 " . $free . "位";
                $msg              = $free > 0 ? "请减少名额或选择其他预约时间" : "请选择其他预约时间/日期";
                $result['status'] = 0;
                $result['title']  = $total;
                $result['msg']    = $msg;
            }
        }
        message($result, '', 'ajax');
    }
    public function doMobileMydayu_yuyuepay()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $settings    = $this->module['config'];
        $subject     = !empty($settings['subject']) ? $settings['subject'] : "主题列表";
        $reid        = intval($_GPC['id']);
        $activity    = $this->get_yuyuepay($reid);
        $par         = iunserializer($activity['par']);
        $main_url    = $par['member'] == 0 ? $this->createMobileUrl('list') : $this->createMobileUrl('dayu_yuyuepay', array(
            'id' => $reid
        ));
        $index_url   = !empty($activity['store']) && pdo_tableexists('dayu_yuyuepay_plugin_store_store') ? murl('entry', array(
            'do' => 'store',
            'm' => 'dayu_yuyuepay_plugin_store'
        ), true, true) : $main_url;
        $par         = iunserializer($activity['par']);
        $period      = $_GPC['period'];
        $period_date = ($period == '1') ? date('Y年m月', strtotime('now')) : date('Y年m月', strtotime('now' . ($_GPC['period'] * 1) . ' month'));
        $starttime   = ($period == '1') ? date('Ym01') : date('Ym01', strtotime(1 * $period . "month"));
        $endtime     = date('Ymd', strtotime("$starttime + 1 month"));
        $pindex      = max(1, intval($_GPC['page']));
        $psize       = 10;
        $where       = 'openid = :openid and reid = :reid AND createtime >= :starttime AND createtime <= :endtime';
        $params      = array(
            ':reid' => $reid,
            ':openid' => $openid,
            ':starttime' => strtotime($starttime),
            ':endtime' => strtotime($endtime)
        );
        $status      = $_GPC['status'];
        if ($_GPC['status'] != '') {
            if ($status == 2) {
                $where .= " and ( status=2 or status=9 )";
            } else {
                $where .= " and status=$status";
            }
        }
        $sql       = 'SELECT * FROM ' . tablename($this->tb_info) . " WHERE $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $total     = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE $where ", $params);
        $pager     = $this->pagination($total, $pindex, $psize);
        $rows      = pdo_fetchall($sql, $params);
        $new_array = array();
        foreach ($rows as $v) {
            $new_array[$v['reid']] = 1;
        }
        $last = array();
        foreach ($new_array as $u => $v) {
            $last[] = $u;
        }
        $fids = implode(',', $last);
        if ($fids) {
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_yuyue) . " WHERE weid = :weid and status = 1 and reid in({$fids}) ORDER BY reid DESC", array(
                ':weid' => $weid
            ));
            foreach ($list AS $key => $val) {
                $list[$key]['yuyue'] = $this->get_yuyue($val['reid'], 1);
            }
        }
        $paytype = array(
            '0' => array(
                'css' => 'default',
                'name' => '未支付'
            ),
            '1' => array(
                'css' => 'green',
                'name' => '在线支付'
            ),
            '2' => array(
                'css' => 'blue',
                'name' => '余额支付'
            ),
            '3' => array(
                'css' => 'black ',
                'name' => '其他付款方式'
            ),
            '4' => array(
                'css' => 'orange',
                'name' => '免费预约'
            ),
            '9' => array(
                'css' => 'primary',
                'name' => '线下付款'
            )
        );
        foreach ($rows AS $key => $val) {
            $rows[$key]['status']   = $this->get_status($val['reid'], $val['status']);
            $rows[$key]['activity'] = $this->get_yuyuepay($val['reid']);
            $rows[$key]['store']    = !empty($val['sid']) ? $this->get_store($val['sid']) : '';
            $rows[$key]['item']     = $this->get_xiangmu($val['reid'], $val['xmid']);
            $rows[$key]['time']     = $activity['is_time'] == 2 ? $val['restime'] : date('Y-m-d H:i', $val['createtime']);
            $rows[$key]['css']      = $paytype[$val['paytype']]['css'];
            if ($rows[$key]['paytype'] == 1) {
                if (empty($rows[$key]['transid'])) {
                    if ($rows[$key]['paystatus'] == 1) {
                        $rows[$key]['paytype'] = '';
                    } else {
                        $rows[$key]['paytype'] = '支付宝支付';
                    }
                } else {
                    $rows[$key]['paytype'] = '微信支付';
                }
            } else {
                $rows[$key]['paytype'] = $paytype[$val['paytype']]['name'];
            }
        }
        $title = !empty($activity['title']) ? $activity['title'] : '预约';
        include $this->template('dayu_yuyuepay');
    }
    public function doWebdayu_Delete2()
    {
        global $_W, $_GPC;
        require 'fans.web.php';
        $id   = intval($_GPC['id']);
        $info = pdo_get($this->tb_info, array(
            'rerid' => $id
        ), array());
        $role = $this->get_isrole($info['reid'], $_W['user']['uid']);
        if ($setting['role'] == 1 && $_W['role'] == 'operator' && !$role)
            message('您没有权限进行该操作.');
        if (!empty($id)) {
            pdo_delete($this->tb_info, array(
                'rerid' => $id
            ));
            pdo_delete($this->tb_data, array(
                'rerid' => $id
            ));
        }
        message('操作成功.', referer());
    }
    public function doMobiledetail()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $paytime     = !empty($setting['paytime']) ? $setting['paytime'] : 30;
        $uni_setting = uni_setting($_W['uniacid'], array(
            'payment',
            'recharge'
        ));
        $pay         = $uni_setting['payment'];
        if (!is_array($pay)) {
            $pay = array();
        }
        $line = htmlspecialchars_decode($pay['line']['message']);
        $id   = intval($_GPC['id']);
        $reid = intval($_GPC['reid']);
        $row  = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE openid = :openid AND rerid = :rerid", array(
            ':openid' => $openid,
            ':rerid' => $id
        ));
        if (empty($row)) {
            $this->showMessage('订单不存在或是已经被删除或该订单不归您所有！');
        }
        $activity = $this->get_yuyuepay($row['reid']);
        $par      = iunserializer($activity['par']);
        if (pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($row['sid']) && !empty($activity['store'])) {
            $store              = pdo_get('dayu_yuyuepay_plugin_store_store', array(
                'weid' => $weid,
                'id' => $row['sid']
            ), array());
            $store['score_num'] = $store['score_num'] == 0 ? 5 : round(($store['total_score'] / $store['score_num']), 0);
        }
        $row['createtimes'] = !empty($row['createtime']) ? date('Y-m-d H:i', $row['createtime']) : '时间丢失';
        $row['yuyuetime']   = !empty($row['yuyuetime']) ? date('Y-m-d H:i', $row['yuyuetime']) : '客服尚未受理本订单';
        $row['thumb']       = iunserializer($row['thumb']);
        $row['outtime']     = $row['createtime'] + $paytime * 60;
        $offline            = $row['paytype'];
        $paytype            = array(
            '0' => array(
                'css' => 'default',
                'name' => '未支付'
            ),
            '1' => array(
                'css' => 'green',
                'name' => '在线支付'
            ),
            '2' => array(
                'css' => 'blue',
                'name' => '余额支付'
            ),
            '3' => array(
                'css' => 'black ',
                'name' => '其他付款方式'
            ),
            '4' => array(
                'css' => 'orange',
                'name' => '免费预约'
            ),
            '9' => array(
                'css' => 'primary',
                'name' => '线下付款'
            )
        );
        $row['css']         = $paytype[$row['paytype']]['css'];
        if ($row['paytype'] == 1) {
            if (empty($row['transid'])) {
                if ($row['paystatus'] == 1) {
                    $row['paytypes'] = '';
                } else {
                    $row['paytypes'] = '支付宝支付';
                }
            } else {
                $row['paytypes'] = '微信支付';
            }
        } else {
            $row['paytypes'] = $paytype[$row['paytype']]['name'];
        }
        $info_rd = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_record) . ' WHERE rerid = :rerid ORDER BY id DESC', array(
            ':rerid' => $row['rerid']
        ));
        foreach ($info_rd AS $key => $val) {
            $info_rd[$key]['status']     = $this->get_status($reid, $val['status']);
            $info_rd[$key]['ostatus']    = $this->get_status($reid, $val['ostatus']);
            $info_rd[$key]['thumb']      = !empty($val['thumb']) ? iunserializer($val['thumb']) : '';
            $info_rd[$key]['createtime'] = date('Y-m-d H:i:s', $val['createtime']);
        }
        $qrcode          = $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
            'reid' => $row['reid'],
            'id' => $row['rerid']
        ));
        $qrcodesrc       = tomedia('headimg_' . $_W['acid'] . '.jpg');
        $main_url        = $par['is_list'] == 1 ? $this->createMobileUrl('list') : $this->createMobileUrl('dayu_yuyuepay', array(
            'id' => $row['reid']
        ));
        $index_url       = !empty($activity['store']) && pdo_tableexists('dayu_yuyuepay_plugin_store_store') ? murl('entry', array(
            'do' => 'store',
            'm' => 'dayu_yuyuepay_plugin_store'
        ), true, true) : $main_url;
        $sql             = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY displayorder DESC, refid DESC';
        $params          = array();
        $params[':reid'] = $row['reid'];
        $fields          = pdo_fetchall($sql, $params);
        if (empty($fields)) {
            $this->showMessage('非法访问.');
        }
        $ds = $fids = array();
        foreach ($fields as $f) {
            $ds[$f['refid']]['fid']   = $f['title'];
            $ds[$f['refid']]['type']  = $f['type'];
            $ds[$f['refid']]['refid'] = $f['refid'];
            $fids[]                   = $f['refid'];
        }
        $fids          = implode(',', $fids);
        $row['fields'] = array();
        $sql           = 'SELECT * FROM ' . tablename($this->tb_data) . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
        $fdatas        = pdo_fetchall($sql, $params);
        foreach ($fdatas as $fd) {
            $row['fields'][$fd['refid']] = $fd['data'];
        }
        $xiangmu = $this->get_xiangmu($row['reid'], $row['xmid']);
        $status  = $this->get_status($row['reid'], $row['status']);
        if ($_W['ispost']) {
            $record           = array();
            $record['status'] = $_GPC['status'];
            pdo_update($this->tb_info, $record, array(
                'rerid' => $id
            ));
            $this->showMessage('取消订单成功', referer(), 'success');
        }
        $title      = $activity['title'];
        $footer_off = $par['member'] == 1 ? 1 : 0;
        include $this->template('detail');
    }
    public function doMobileEdit()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $returnUrl  = urlencode($_W['siteurl']);
        $reid       = intval($_GPC['reid']);
        $rerid      = intval($_GPC['rerid']);
        $activity   = $this->get_yuyuepay($reid);
        $par        = iunserializer($activity['par']);
        $submitname = !empty($par['submitname']) ? $par['submitname'] : '立 即 提 交';
        $info       = pdo_get($this->tb_info, array(
            'rerid' => $rerid
        ), array());
        if ($info['openid'] != $openid) {
            $this->showMessage('记录不存在或是已经被删除！');
            exit;
        }
        $binds   = array();
        $profile = mc_fetch($uid, $binds);
        $field   = pdo_getall($this->tb_field, array(
            'reid' => $reid
        ), array(), '', 'displayorder DESC,refid DESC', '');
        if (empty($field)) {
            $this->showMessage('非法访问.');
        }
        if ($par['edit'] != '1' && $info['status'] != '8')
            $this->showMessage('不能修改内容', 'error');
        $ds = $fids = array();
        foreach ($field as $f) {
            if ($f['type'] == 'reside') {
                $reside = $f;
            }
            ($f['type'] == 'photograph' && empty($profile[$f['bind']])) && $this->showMessage('请完善' . $f['title'], murl('entry', array(
                'do' => 'index',
                'f' => $f['bind'],
                'm' => 'dayu_photograph',
                'returnurl' => $returnUrl
            ), true, true), 'info');
            pdo_tableexists('dayu_photograph_fields') && $f['photograph_url'] = murl('entry', array(
                'do' => 'index',
                'f' => $f['bind'],
                'm' => 'dayu_photograph',
                'returnurl' => $returnUrl
            ), true, true);
            if ($f['type'] == 'tingli' && pdo_tableexists('dayu_tingli')) {
                $f['tingli'] = pdo_fetch("SELECT * FROM " . tablename('dayu_tingli') . " WHERE weid = :weid AND openid = :openid", array(
                    ':weid' => $weid,
                    ':openid' => $openid
                ));
                empty($f['tingli']) && $this->showMessage('请完成进行听力测试', murl('entry', array(
                    'do' => 'index',
                    'm' => 'dayu_tingli',
                    'returnurl' => $returnUrl
                ), true, true), 'info');
                $f['default'] = 'L：' . $f['tingli']['left_250'] . ' ' . $f['tingli']['left_500'] . ' ' . $f['tingli']['left_1k'] . ' ' . $f['tingli']['left_2k'] . ' ' . $f['tingli']['left_4k'] . ' ' . $f['tingli']['left_8k'] . '；R：' . $f['tingli']['right_250'] . ' ' . $f['tingli']['right_500'] . ' ' . $f['tingli']['right_1k'] . ' ' . $f['tingli']['right_2k'] . ' ' . $f['tingli']['right_4k'] . ' ' . $f['tingli']['right_8k'];
            }
            if ($profile[$f['bind']]) {
                $f['default'] = $profile[$f['bind']];
            }
            if (in_array($f['type'], array(
                'text',
                'number',
                'email'
            ))) {
                $ds[$f['refid']]['tixing'] = !empty($f['description']) ? urldecode($f['description']) : "请输入" . $f['title'];
            } elseif ($f['type'] == 'textarea') {
                $ds[$f['refid']]['tixing'] = !empty($f['description']) ? urldecode($f['description']) : "请填写" . $f['title'];
            } else {
                $ds[$f['refid']]['tixing'] = !empty($f['description']) ? urldecode($f['description']) : "请选择" . $f['title'];
            }
            $ds[$f['refid']]['options']        = explode(',', $f['value']);
            $ds[$f['refid']]['fid']            = $f['title'];
            $ds[$f['refid']]['type']           = $f['type'];
            $ds[$f['refid']]['refid']          = $f['refid'];
            $ds[$f['refid']]['essential']      = $f['essential'];
            $ds[$f['refid']]['photograph_url'] = $f['photograph_url'];
            $ds[$f['refid']]['default']        = $f['default'];
            $fids[]                            = $f['refid'];
        }
        $fids           = implode(',', $fids);
        $info['fields'] = $info['redid'] = array();
        $sql            = 'SELECT * FROM ' . tablename($this->tb_data) . " WHERE `reid`='{$reid}' AND `rerid`='{$rerid}' AND `refid` IN ({$fids})";
        $fdatas         = pdo_fetchall($sql, $params);
        foreach ($fdatas as $fd) {
            if (strstr($fd['data'], 'images')) {
                $info['fields'][$fd['refid']] = tomedia($fd['data']);
            } else {
                $info['fields'][$fd['refid']] = $fd['data'];
            }
            $info['redid'][$fd['refid']] = $fd['redid'];
        }
        if ($_W['ispost'] || checksubmit('submit')) {
            $datas = array();
            foreach ($_POST AS $key => $value) {
                $entry         = array();
                $entry['data'] = strval($value);
                $datas[]       = array(
                    'content' => $entry,
                    'refid' => $key
                );
                pdo_update($this->tb_data, $entry, array(
                    'redid' => $key
                ));
            }
            pdo_update($this->tb_info, array(
                'status' => '0'
            ), array(
                'rerid' => $rerid
            ));
            if (empty($datas)) {
                $this->showMessage('非法访问，提交数据不能为空', '', 'error');
                exit;
            }
            if (!empty($datas)) {
                foreach ($datas as $row) {
                    if (strstr($row['content']['data'], 'images')) {
                        $row['content']['data'] = "有";
                    }
                    $field      = pdo_get($this->tb_data, array(
                        'redid' => $row['refid']
                    ), array());
                    $row['fid'] = $this->get_fields($field['refid']);
                    $body .= '<h4>' . $row['content']['data'] . '</h4>';
                    $smsbody .= $row['content']['data'] . '，';
                    $bodnew .= '\\n' . $row['fid']['title'] . '：' . $row['content']['data'];
                }
                $ytime   = date('Y-m-d H:i:s', TIMESTAMP);
                $status  = $this->get_status($reid, '0');
                $getxm   = $this->get_xiangmu($reid, $info['xmid']);
                $xiangmu = $activity['is_num'] == 1 ? $getxm['title'] . " - " . $getxm['price'] . "元 * " . $num : $getxm['title'] . " - " . $getxm['price'] . "元";
                $staff   = pdo_fetchall("SELECT `openid` FROM " . tablename($this->tb_staff) . " WHERE reid=:reid AND weid=:weid", array(
                    ':weid' => $_W['uniacid'],
                    ':reid' => $reid
                ));
                if (is_array($staff)) {
                    foreach ($staff as $s) {
                        $template = array(
                            "touser" => $s['openid'],
                            "template_id" => $activity['k_templateid'],
                            "url" => murl('entry', array(
                                'do' => 'manage_detail',
                                'id' => $info['rerid'],
                                'reid' => $info['reid'],
                                'sid' => $info['sid'],
                                'm' => 'dayu_yuyuepay'
                            ), true, true),
                            "topcolor" => "#FF0000",
                            "data" => array(
                                'first' => array(
                                    'value' => urlencode("订单：" . $info['ordersn'] . "已修改\\n"),
                                    'color' => "#743A3A"
                                ),
                                'keyword1' => array(
                                    'value' => urlencode(date('Y年m月d日 H:i', TIMESTAMP)),
                                    'color' => '#000000'
                                ),
                                'keyword2' => array(
                                    'value' => urlencode($status['name']),
                                    'color' => '#000000'
                                ),
                                'keyword3' => array(
                                    'value' => urlencode($ytime),
                                    'color' => '#000000'
                                ),
                                'keyword4' => array(
                                    'value' => urlencode($bodym . "\\n"),
                                    'color' => "#FF0000"
                                ),
                                'remark' => array(
                                    'value' => urlencode("\\n姓名：" . $info['member'] . "\\n手机：" . $info['mobile'] . "\\n" . $par['xmname'] . "：" . $xiangmu . $bodnew),
                                    'color' => "#008000"
                                )
                            )
                        );
                        load()->func('communication');
                        $this->send_template_message(urldecode(json_encode($template)));
                    }
                }
            }
            $this->showMessage('修改成功，请等待受理', $this->createMobileUrl('mydayu_yuyuepay', array(
                'id' => $reid
            )));
        }
        load()->func('tpl');
        $title             = $activity['titles'];
        $_share['title']   = $activity['title'];
        $_share['content'] = $activity['description'];
        $_share['imgUrl']  = tomedia($activity['thumb']);
        $jquery            = 2;
        include $this->template('edit');
    }
    public function get_fields($fid)
    {
        global $_GPC, $_W;
        return pdo_get($this->tb_field, array(
            'refid' => $fid
        ), array());
    }
    public function doMobilemembers()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $returnUrl = urlencode($_W['siteurl']);
        $card      = pdo_get('mc_card_members', array(
            'uniacid' => $weid,
            'openid' => $openid,
            'uid' => $uid
        ), array());
        load()->model('activity');
        $filter      = array();
        $coupons     = activity_coupon_owned($uid, $filter);
        $tokens      = activity_token_owned($uid, $filter);
        $uni_setting = uni_setting($_W['uniacid'], array(
            'creditnames',
            'creditbehaviors',
            'uc',
            'payment',
            'passport'
        ));
        $behavior    = $uni_setting['creditbehaviors'];
        $creditnames = $uni_setting['creditnames'];
        $credits     = mc_credit_fetch($_W['member']['uid'], '*');
        $settings    = $this->module['config'];
        $subject     = !empty($settings['subject']) ? $settings['subject'] : "主题列表";
        $address     = pdo_fetch("SELECT * FROM " . tablename('mc_member_address') . " WHERE isdefault = 1 and uid = :uid limit 1", array(
            ':uid' => $_W['fans']['uid']
        ));
        $sql         = 'SELECT `num` FROM ' . tablename('mc_credits_record') . " WHERE `uid` = :uid";
        $params      = array(
            ':uid' => $uid
        );
        $nums        = pdo_fetchall($sql, $params);
        $pay         = $income = 0;
        foreach ($nums as $value) {
            if ($value['num'] > 0) {
                $income += $value['num'];
            } else {
                $pay += abs($value['num']);
            }
        }
        $pay               = number_format($pay, 2);
        $sql               = 'SELECT `reid` FROM ' . tablename($this->tb_info) . " WHERE openid = :openid ORDER BY rerid DESC";
        $params            = array();
        $params[':openid'] = $openid;
        $rows              = pdo_fetchall($sql, $params);
        $new_array         = array();
        foreach ($rows as $v) {
            $new_array[$v['reid']] = 1;
        }
        $last = array();
        foreach ($new_array as $u => $v) {
            $last[] = $u;
        }
        $fids = implode(',', $last);
        if ($fids) {
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->tb_yuyue) . " WHERE weid = :weid and status = 1 and reid in({$fids}) ORDER BY reid DESC", array(
                ':weid' => $weid
            ));
            foreach ($list AS $key => $val) {
                $list[$key]['yuyue'] = $this->get_yuyue($val['reid'], 1);
            }
        }
        $dhcoupon_url = pdo_tableexists('we7_coupon') ? murl('entry', array(
            'do' => 'activity',
            'type' => 'coupon',
            'm' => 'we7_coupon'
        ), true, true) : url('activity/coupon') . 'wxref=mp.weixin.qq.com#wechat_redirect';
        $mycoupon_url = pdo_tableexists('we7_coupon') ? murl('entry', array(
            'do' => 'activity',
            'op' => 'mine',
            'type' => 'coupon',
            'm' => 'we7_coupon'
        ), true, true) : url('activity/coupon/mine') . 'wxref=mp.weixin.qq.com#wechat_redirect';
        $pay_url      = $setting['pay'] == '1' ? murl('entry', array(
            'do' => 'index',
            'm' => 'dayu_card_plugin_deposit'
        ), true, true) : url('entry', array(
            'm' => 'recharge',
            'do' => 'pay'
        ));
        include $this->template('members');
    }
    public function doMobilemanager()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $this->getFollow();
        if (!$isstaff) {
            $this->showMessage('非法访问！你不是管理员。', $this->createMobileUrl('list'), 'info');
        }
        $list = pdo_fetchall("SELECT s.reid, y.* FROM " . tablename($this->tb_staff) . " s left join " . tablename($this->tb_yuyue) . " y on y.reid=s.reid WHERE y.weid=:weid AND s.openid=:openid ORDER BY y.reid DESC", array(
            ':weid' => $weid,
            ':openid' => $openid
        ), 'reid');
        foreach ($list AS $key => $val) {
            $list[$key]['yuyue'] = $this->get_yuyue($val['reid'], 2);
        }
        $title = "管理中心";
        include $this->template('manager');
    }
    public function doMobilemanage()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        load()->func('tpl');
        $reid     = intval($_GPC['id']);
        $activity = $this->get_yuyuepay($reid);
        if ($setting['store'] == 1 && empty($sid)) {
            $this->showMessage("店铺ID异常", murl('entry', array(
                'do' => 'store',
                'm' => 'dayu_yuyuepay_plugin_store'
            ), true, true), 'error');
        } elseif ($setting['store'] == 1 && !empty($sid) && $openid != $store['openid']) {
            $this->showMessage("你不是店主", murl('entry', array(
                'do' => 'store',
                'm' => 'dayu_yuyuepay_plugin_store'
            ), true, true), 'error');
        } elseif ($setting['store'] != 1) {
            $isstaff = pdo_get($this->tb_staff, array(
                'weid' => $weid,
                'reid' => $reid,
                'openid' => $openid
            ), array(
                'id'
            ));
            if (!$isstaff) {
                $this->showMessage('非法访问！你不是管理员。', $this->createMobileUrl('list'), 'info');
            }
        }
        if ($setting['store'] == 1) {
            $list = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay_plugin_store_store') . " WHERE weid = :weid and checked = 1 and openid = :openid ORDER BY id DESC", array(
                ':weid' => $weid,
                ':openid' => $openid
            ), 'id');
            foreach ($list AS $key => $val) {
                $list[$key]['reid']  = $val['id'];
                $list[$key]['title'] = $val['name'];
            }
            $list_title = '切换店铺';
        } else {
            $list       = pdo_fetchall("SELECT * FROM " . tablename($this->tb_yuyue) . " WHERE weid = :weid and status = 1 and kfid = :openid ORDER BY reid DESC", array(
                ':weid' => $weid,
                ':openid' => $openid
            ), 'reid');
            $list_title = '切换主题';
        }
        $par = iunserializer($activity['par']);
        if (!empty($reid)) {
            $staff       = pdo_fetchall("SELECT * FROM " . tablename($this->tb_staff) . " WHERE reid = :reid ORDER BY `id` DESC", array(
                ':reid' => $reid
            ));
            $period      = $_GPC['period'];
            $period_date = ($period == '1') ? date('Y年m月', strtotime('now')) : date('Y年m月', strtotime('now' . ($_GPC['period'] * 1) . ' month'));
            $starttime   = ($period == '1') ? date('Ym01') : date('Ym01', strtotime(1 * $period . "month"));
            $endtime     = date('Ymd', strtotime("$starttime + 1 month"));
            $pindex      = max(1, intval($_GPC['page']));
            $psize       = 10;
            $where       = 'reid = :reid AND createtime >= :starttime AND createtime <= :endtime';
            $params      = array(
                ':reid' => $reid,
                ':starttime' => strtotime($starttime),
                ':endtime' => strtotime($endtime)
            );
            $status      = $_GPC['status'];
            if ($status != '') {
                if ($status == 2) {
                    $where .= " and ( status=2 or status=9 )";
                } else {
                    $where .= " and status='{$status}'";
                }
            }
            if ($openid != $activity['kfid'] && $setting['store'] != 1 && $activity['guanli'] == '0') {
                $where .= " and kf='{$openid}'";
            }
            if ($setting['store'] == 1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store')) {
                $where .= " and sid='{$sid}'";
            }
            $sql     = 'SELECT * FROM ' . tablename($this->tb_info) . " WHERE $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
            $rows    = pdo_fetchall($sql, $params);
            $total   = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE $where ", $params);
            $pager   = $this->pagination($total, $pindex, $psize);
            $paytype = array(
                '0' => array(
                    'css' => 'default',
                    'name' => '未支付'
                ),
                '1' => array(
                    'css' => 'green',
                    'name' => '在线支付'
                ),
                '2' => array(
                    'css' => 'blue',
                    'name' => '余额支付'
                ),
                '3' => array(
                    'css' => 'black ',
                    'name' => '其他付款方式'
                ),
                '4' => array(
                    'css' => 'orange',
                    'name' => '免费预约'
                ),
                '9' => array(
                    'css' => 'primary',
                    'name' => '线下付款'
                )
            );
            foreach ($rows AS $key => $val) {
                $rows[$key]['status'] = $this->get_status($val['reid'], $val['status']);
                $rows[$key]['item']   = $this->get_xiangmu($val['reid'], $val['xmid']);
                $rows[$key]['store']  = !empty($val['sid']) ? $this->get_store($val['sid']) : '';
                $rows[$key]['kf']     = mc_fansinfo($val['kf'], $acid, $weid);
                $rows[$key]['time']   = $activity['is_time'] == 2 ? $val['restime'] : date('Y-m-d H:i', $val['createtime']);
                $rows[$key]['css']    = $paytype[$val['paytype']]['css'];
                if ($rows[$key]['paytype'] == 1) {
                    if (empty($rows[$key]['transid'])) {
                        if ($rows[$key]['paystatus'] == 1) {
                            $rows[$key]['paytype'] = '';
                        } else {
                            $rows[$key]['paytype'] = '支付宝支付';
                        }
                    } else {
                        $rows[$key]['paytype'] = '微信支付';
                    }
                } else {
                    $rows[$key]['paytype'] = $paytype[$val['paytype']]['name'];
                }
            }
        }
        $itemname = !empty($par['xmname']) ? $par['xmname'] : '服务项目';
        $title    = !empty($activity['title']) ? $activity['title'] : '微预约';
        include $this->template('dayu_manage');
    }
    public function doMobilePower()
    {
        global $_GPC, $_W;
        $reid  = $_GPC['reid'];
        $rerid = $_GPC['rerid'];
        if ($_GPC['table'] == 'manage') {
            $data = array(
                'kfid' => $_GPC['openid']
            );
            if (pdo_update($this->tb_yuyue, $data, array(
                'reid' => $reid,
                'weid' => $_W['uniacid']
            )) === false) {
                $result['status'] = 0;
                $result['msg']    = '转移失败';
            } else {
                $result['status'] = 1;
                $result['msg']    = '转移成功';
            }
            message($result, '', 'ajax');
        }
        if ($_GPC['table'] == 'case') {
            $data = array(
                'kf' => $_GPC['openid']
            );
            if (pdo_update($this->tb_info, $data, array(
                'reid' => $reid,
                'rerid' => $rerid
            )) === false) {
                $result['status'] = 0;
                $result['msg']    = '派单失败';
            } else {
                $activity = pdo_get($this->tb_yuyue, array(
                    'weid' => $_W['uniacid'],
                    'reid' => $reid
                ), array());
                $content  = pdo_get($this->tb_info, array(
                    'reid' => $reid,
                    'rerid' => $rerid
                ), array(
                    'member',
                    'mobile',
                    'address',
                    'xmid',
                    'price',
                    'num',
                    'restime',
                    'yuyuetime',
                    'createtime'
                ));
                $add      = $activity['is_addr'] == 1 ? "\\n地址：" . $content['address'] : "";
                if ($activity['is_time'] != 1) {
                    $time = $activity['is_time'] == 2 ? "\\n" . $par['yuyuename'] . "：" . $content['restime'] : "\\n" . $par['yuyuename'] . "：" . date('Y-m-d H:i:s', $content['yuyuetime']);
                }
                $getxm   = $this->get_xiangmu($reid, $content['xmid']);
                $jg      = $getxm['price'] != '0.00' ? ' - ' . $getxm['price'] . '元' : '';
                $xiangmu = $activity['is_num'] == 1 ? $getxm['title'] . $jg . " * " . $order['num'] : $getxm['title'] . $jg;
                if ($_W['account']['level'] == ACCOUNT_SERVICE_VERIFY && !empty($activity['k_templateid'])) {
                    $template = array(
                        "touser" => $_GPC['openid'],
                        "template_id" => $activity['k_templateid'],
                        "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manage_detail', array(
                            'reid' => $reid,
                            'id' => $rerid
                        )),
                        "topcolor" => "#FF0000",
                        "data" => array(
                            'first' => array(
                                'value' => urlencode("请及时受理\\n"),
                                'color' => "#743A3A"
                            ),
                            'keyword1' => array(
                                'value' => urlencode(date('Y年m月d日 H:i', $content['createtime'])),
                                'color' => '#000000'
                            ),
                            'keyword2' => array(
                                'value' => urlencode("管理员派单"),
                                'color' => '#EF4F4F'
                            ),
                            'remark' => array(
                                'value' => urlencode("\\n姓名：" . $content['member'] . "\\n手机：" . $content['mobile'] . $add . "\\n" . $par['xmname'] . "：" . $xiangmu . $time),
                                'color' => "#01579b"
                            )
                        )
                    );
                    $this->send_template_message(urldecode(json_encode($template)));
                } else {
                    $url  = $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageform', array(
                        'name' => 'dayu_form',
                        'weid' => $_W['uniacid'],
                        'op' => 'detail',
                        'id' => $reid,
                        'rerid' => $rerid
                    ));
                    $info = "【您好，{$activity['title']} 通知】\n\n";
                    $info .= "姓名：{$content['member']}\n手机：{$content['mobile']}\n管理员派单\n\n";
                    $info .= "<a href='{$url}'>点击查看详情</a>";
                    $custom = array(
                        'msgtype' => 'text',
                        'text' => array(
                            'content' => urlencode($info)
                        ),
                        'touser' => $s['openid']
                    );
                    $acc->sendCustomNotice($custom);
                }
                $result['status'] = 1;
                $result['msg']    = '派单成功';
            }
            message($result, '', 'ajax');
        }
    }
    public function doMobilemanage_detail()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $id       = intval($_GPC['id']);
        $reid     = intval($_GPC['reid']);
        $activity = $this->get_yuyuepay($reid);
        $isstaff  = pdo_get($this->tb_staff, array(
            'weid' => $weid,
            'reid' => $reid,
            'openid' => $openid
        ), array(
            'id'
        ));
        $row      = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE rerid = :rerid", array(
            ':rerid' => $id
        ));
        if (empty($row)) {
            $this->showMessage('记录不存在或是已经被删除！');
        }
        $par    = iunserializer($activity['par']);
        $status = $this->get_status($row['reid'], $row['status']);
        $state  = array();
        $arr2   = array(
            '0',
            '1',
            '2',
            '3',
            '8'
        );
        foreach ($arr2 as $index => $v) {
            $state[$v][] = $this->get_status($reid, $v);
        }
        if ($setting['store'] == 1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($row['sid'])) {
            $store = $this->get_store($row['sid']);
        }
        if ($openid == $activity['kfid'] || $openid == $row['kf'] || $openid == $store['openid'] || ($activity['guanli'] == '1' && $isstaff)) {
            $repeat = $_COOKIE['r_submit'];
            if (!empty($_GPC['repeat'])) {
                if (!empty($repeat)) {
                    if ($repeat == $_GPC['repeat']) {
                        $this->showMessage($activity['information'], $this->createMobileUrl('mydayu_yuyuepay', array(
                            'weid' => $weid,
                            'id' => $reid
                        )));
                    } else {
                        setcookie("r_submit", $_GPC['repeat']);
                    }
                } else {
                    setcookie("r_submit", $_GPC['repeat']);
                }
            }
            $staff              = pdo_fetchall("SELECT * FROM " . tablename($this->tb_staff) . " WHERE reid = :reid ORDER BY `id` DESC", array(
                ':reid' => $reid
            ));
            $face               = mc_fansinfo($row['openid'], $acid, $weid);
            $row['createtimes'] = !empty($row['createtime']) ? date('Y-m-d H:i', $row['createtime']) : '时间丢失';
            $yuyuetime          = !empty($row['yuyuetime']) ? date('Y-m-d H:i', $row['yuyuetime']) : '时间丢失';
            $row['thumb']       = iunserializer($row['thumb']);
            $row['yuyuetime']   = date('Y-m-d H:i', $row['yuyuetime']);
            $xiangmu            = $this->get_xiangmu($row['reid'], $row['xmid']);
            $paytype            = array(
                '0' => array(
                    'css' => 'default',
                    'name' => '未支付'
                ),
                '1' => array(
                    'css' => 'green',
                    'name' => '在线支付'
                ),
                '2' => array(
                    'css' => 'blue',
                    'name' => '余额支付'
                ),
                '3' => array(
                    'css' => 'black ',
                    'name' => '其他付款方式'
                ),
                '4' => array(
                    'css' => 'orange',
                    'name' => '免费预约'
                ),
                '9' => array(
                    'css' => 'primary',
                    'name' => '线下付款'
                )
            );
            $row['css']         = $paytype[$row['paytype']]['css'];
            if ($row['paytype'] == 1) {
                if (empty($row['transid'])) {
                    if ($row['paystatus'] == 1) {
                        $row['paytypes'] = '';
                    } else {
                        $row['paytypes'] = '支付宝支付';
                    }
                } else {
                    $row['paytypes'] = '微信支付';
                }
            } else {
                $row['paytypes'] = $paytype[$row['paytype']]['name'];
            }
            $info_rd = pdo_fetchall('SELECT * FROM ' . tablename($this->tb_record) . ' WHERE rerid = :rerid ORDER BY id DESC', array(
                ':rerid' => $row['rerid']
            ));
            foreach ($info_rd AS $key => $val) {
                $info_rd[$key]['manage']     = pdo_get($this->tb_staff, array(
                    'weid' => $weid,
                    'reid' => $reid,
                    'openid' => $val['openid']
                ), array(
                    'nickname'
                ));
                $info_rd[$key]['status']     = $this->get_status($reid, $val['status']);
                $info_rd[$key]['ostatus']    = $this->get_status($reid, $val['ostatus']);
                $info_rd[$key]['thumb']      = !empty($val['thumb']) ? iunserializer($val['thumb']) : '';
                $info_rd[$key]['createtime'] = date('Y-m-d H:i:s', $val['createtime']);
            }
            $sql             = 'SELECT * FROM ' . tablename($this->tb_field) . ' WHERE `reid`=:reid ORDER BY displayorder DESC, refid DESC';
            $params          = array();
            $params[':reid'] = $row['reid'];
            $fields          = pdo_fetchall($sql, $params);
            if (empty($fields)) {
                $this->showMessage('非法访问.');
            }
            $ds = $fids = array();
            foreach ($fields as $f) {
                $ds[$f['refid']]['fid']   = $f['title'];
                $ds[$f['refid']]['type']  = $f['type'];
                $ds[$f['refid']]['refid'] = $f['refid'];
                $fids[]                   = $f['refid'];
            }
            $fids          = implode(',', $fids);
            $row['fields'] = array();
            $sql           = 'SELECT * FROM ' . tablename($this->tb_data) . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
            $fdatas        = pdo_fetchall($sql, $params);
            foreach ($fdatas as $fd) {
                $row['fields'][$fd['refid']] = $fd['data'];
            }
            if ($_W['ispost']) {
                $record           = array();
                $record['status'] = $_GPC['status'];
                $record['kfinfo'] = $_GPC['kfinfo'];
                if (!empty($_GPC['paystatus'])) {
                    $record['paystatus'] = intval($_GPC['paystatus']);
                }
                if (is_array($_GPC['thumb'])) {
                    foreach ($_GPC['thumb'] as $thumb) {
                        $th[] = tomedia($thumb);
                    }
                    $record['thumb'] = iserializer($th);
                }
                $kfinfo = !empty($_GPC['kfinfo']) ? "\n客服回复：" . $_GPC['kfinfo'] : "";
                $status = $this->get_status($row['reid'], $_GPC['status']);
                if ($activity['is_time'] == 0) {
                    $times = $row['yuyuetime'];
                } elseif ($activity['is_time'] == 2) {
                    $times = $row['restime'];
                }
                $url        = $_W['siteroot'] . 'app/' . $this->createMobileUrl('detail', array(
                    'reid' => $row['reid'],
                    'id' => $row['rerid']
                ));
                $data       = array(
                    'first' => array(
                        'value' => $par['mfirst'] . "\n",
                        'color' => "#743A3A"
                    ),
                    'keyword1' => array(
                        'value' => $row['member']
                    ),
                    'keyword2' => array(
                        'value' => $xiangmu['title'] . " - " . $xiangmu['price'] . "元"
                    ),
                    'keyword3' => array(
                        'value' => $times
                    ),
                    'keyword4' => array(
                        'value' => $status['name']
                    ),
                    'remark' => array(
                        'value' => $kfinfo . "\n" . $activity['mfoot'],
                        'color' => "#008000"
                    )
                );
                $recorddata = array(
                    'rerid' => $row['rerid'],
                    'openid' => $openid,
                    'thumb' => $record['thumb'],
                    'info' => $record['kfinfo'],
                    'ostatus' => $row['status'],
                    'status' => $record['status'],
                    'createtime' => TIMESTAMP
                );
                if (pdo_insert($this->tb_record, $recorddata) === false) {
                    $this->showMessage('更新失败, 请稍后重试.');
                    exit();
                }
                if (!empty($activity['m_templateid'])) {
                    $acc = WeAccount::create($_W['acid']);
                    $acc->sendTplNotice($row['openid'], $activity['m_templateid'], $data, $url, "#FF0000");
                }
                if ($par['sms'] != '0' && !empty($activity['smsid']) && $row['status'] == '0') {
                    load()->func('communication');
                    ihttp_post(murl('entry', array(
                        'do' => 'Notice',
                        'id' => $activity['smsid'],
                        'm' => 'dayu_sms'
                    ), true, true), array(
                        'mobile' => $row['mobile'],
                        'mname' => $row['member'],
                        'product' => $xiangmu['title'],
                        'status' => $status['name']
                    ));
                }
                $store_msg = '';
                if ($setting['store'] == 1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($row['sid']) && $_GPC['status'] == '3') {
                    $store  = $this->get_store($row['sid']);
                    $boss   = $this->get_store_boss($store['bid']);
                    $paylog = pdo_get('dayu_yuyuepay_plugin_store_paylog', array(
                        'weid' => $_W['uniacid'],
                        'sid' => $row['sid'],
                        'bid' => $store['bid'],
                        'reid' => $row['reid'],
                        'yid' => $row['rerid']
                    ), array());
                    if (empty($paylog)) {
                        $paylogdata = array(
                            'weid' => $_W['uniacid'],
                            'sid' => $row['sid'],
                            'bid' => $store['bid'],
                            'reid' => $row['reid'],
                            'yid' => $row['rerid'],
                            'price' => $row['price'],
                            'createtime' => TIMESTAMP
                        );
                        pdo_insert('dayu_yuyuepay_plugin_store_paylog', $paylogdata);
                        pdo_update('dayu_yuyuepay_plugin_store_boss', array(
                            'money' => $boss['money'] + $paylogdata['price']
                        ), array(
                            'id' => $store['bid']
                        ));
                        $store_msg = '店主余额增加' . $paylogdata['price'] . '元，';
                    }
                }
                pdo_update($this->tb_info, $record, array(
                    'rerid' => $id
                ));
                if ($setting['store'] == 1 && pdo_tableexists('dayu_yuyuepay_plugin_store_store') && !empty($row['sid'])) {
                    $this->showMessage('修改成功', $this->createMobileUrl('manage', array(
                        'id' => $row['reid'],
                        'sid' => $row['sid'],
                        'status' => 0
                    )), 'success');
                } else {
                    $this->showMessage('修改成功', $this->createMobileUrl('manage', array(
                        'id' => $row['reid'],
                        'status' => 0
                    )), 'success');
                }
            }
        }
        $title = $activity['title'];
        include $this->template('manage_detail');
    }
    public function doMobileUploads()
    {
        global $_W, $_GPC;
        load()->classs('account');
        $result = array(
            'status' => 'error',
            'message' => '',
            'data' => ''
        );
        if (empty($_W['acid'])) {
            $sql        = "SELECT acid FROM " . tablename('mc_mapping_fans') . " WHERE openid = :openid AND uniacid = :uniacid limit 1";
            $params     = array(
                ':openid' => $_W['openid'],
                ':uniacid' => $_W['uniacid']
            );
            $_W['acid'] = pdo_fetchcolumn($sql, $params);
        }
        if (empty($_W['acid'])) {
            $result['status']  = 'error';
            $result['message'] = '没有找到相关公众账号';
        }
        $acid      = $_W['acid'];
        $acc       = WeAccount::create($acid);
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'upload';
        $type      = !empty($_GPC['type']) ? $_GPC['type'] : 'image';
        if ($operation == 'upload') {
            if ($type == 'image') {
                $serverId           = trim($_GPC['serverId']);
                $localId            = trim($_GPC['localId']);
                $media              = array();
                $media['media_id']  = $serverId;
                $media['type']      = $type;
                $result['serverId'] = $serverId;
                $result['localId']  = $localId;
                $filename           = $acc->downloadMedia($media);
                if (is_error($filename)) {
                    $result['status']  = 'error';
                    $result['message'] = '上传失败';
                } else {
                    $result['status']  = 'success';
                    $result['imgurl']  = $_W['attachurl'] . $filename;
                    $result['path']    = $filename;
                    $result['message'] = '上传成功';
                }
            }
            die(json_encode($result));
        } elseif ($operation == 'remove') {
            $file = $_GPC['file'];
            file_delete($file);
            exit(json_encode(array(
                'status' => true
            )));
        }
    }
    public function doMobileUpload()
    {
        global $_W, $_GPC;
        load()->classs('weixin.account');
        $accObj       = WeixinAccount::create($_W['uniacid']);
        $access_token = $accObj->fetch_token();
        $media_id     = $_GET['media_id'];
        $url          = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $media_id;
        load()->func('tpl');
        $reid        = intval($_GPC['id']);
        $activity    = $this->get_yuyuepay($reid);
        $upload_path = "../attachment/";
        $images_path = "dayu_yuyuepay/" . $_W['uniacid'] . "/";
        load()->func('file');
        @mkdirs($upload_path . $images_path);
        if (!file_exists($upload_path . $images_path)) {
            mkdir($upload_path . $images_path);
        }
        if ($_GPC['type'] == 2) {
            $pic    = 'avatar_' . date("YmdHis") . mt_rand(1000, 9999) . '.jpg';
            $picurl = $upload_path . $images_path . $pic;
            $data   = array(
                'avatar' => $images_path . $pic
            );
            load()->model('mc');
            mc_update($_W['member']['uid'], $data);
        } elseif ($_GPC['type'] == 3) {
            $pic    = 'remit_' . date("YmdHis") . mt_rand(1000, 9999) . '.jpg';
            $picurl = $upload_path . $images_path . $pic;
            $data   = array(
                'remit' => $images_path . $pic
            );
            pdo_update($this->tb_info, $data, array(
                'rerid' => $_GPC['id']
            ));
        } elseif (empty($_GPC['type']) && $reid) {
            $pic    = 'yuyue' . $reid . '_' . date("YmdHis") . mt_rand(1000, 9999) . '.jpg';
            $picurl = $upload_path . $images_path . $pic;
        }
        $targetName = $picurl;
        $ch         = curl_init($url);
        $fp         = fopen($targetName, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        if (empty($_GPC['type']) && $reid) {
            echo $images_path . $pic;
        }
        $pathname = $images_path . $pic;
        if (!empty($_W['setting']['remote']['type'])) {
            load()->func('file');
            $remotestatus = file_remote_upload($pathname);
            if (is_error($remotestatus)) {
                message('远程附件上传失败，请检查配置并重新上传');
            } else {
                $remoteurl = $pathname;
                unlink($upload_path . $pathname);
            }
        }
        if (!empty($_GPC['type'])) {
            $this->AjaxMessage('上传成功', 1);
        }
    }
    public function doMobileInfo()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $qqkey     = $setting['qqkey'];
        $returnUrl = urlencode($_W['siteurl']);
        $operation = $_GPC['op'];
        $titles    = '更新资料';
        if ($operation == 'post') {
            $data = array(
                'nickname' => $_GPC['nickname'],
                'realname' => $_GPC['realname'],
                'mobile' => $_GPC['mobile'],
                'resideprovince' => $_GPC['province'],
                'residecity' => $_GPC['city'],
                'residedist' => $_GPC['area'],
                'address' => $_GPC['address'],
                'updatetime' => time()
            );
            load()->model('mc');
            if (mc_update($uid, $data) === false) {
                $result['status'] = 0;
                $result['msg']    = '更新失败';
            } else {
                $result['status'] = 1;
                $result['msg']    = '更新成功';
            }
            message($result, '', 'ajax');
        }
        if ($operation == 'sex') {
            $data = array(
                'gender' => intval($_GPC['sex'])
            );
            load()->model('mc');
            mc_update($uid, $data);
            $gender = $_GPC['sex'] == 1 ? "男士" : "女士";
            $this->AjaxMessage('性别更改为:' . $gender, 1);
        }
        load()->func('tpl');
        include $this->template('info');
    }
    public function doMobileAvatar()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $setting = $this->module['config'];
        $jquery  = 2;
        include $this->template('avatar');
    }
    public function doMobileAddress()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $setting   = $this->module['config'];
        $qqkey     = $setting['qqkey'];
        $operation = $_GPC['op'];
        $title     = '联系方式管理';
        $reid      = intval($_GPC['reid']);
        $activity  = $this->get_yuyuepay($reid);
        if ($operation == 'post') {
            $id   = intval($_GPC['id']);
            $data = array(
                'uniacid' => $_W['uniacid'],
                'uid' => $_W['fans']['uid'],
                'username' => $_GPC['realname'],
                'mobile' => $_GPC['mobile'],
                'province' => $_GPC['province'],
                'city' => $_GPC['city'],
                'district' => $_GPC['area'],
                'address' => $_GPC['address']
            );
            if (empty($data['username']) || empty($data['mobile']) || empty($data['address'])) {
                $this->showMessage('请输完善您的资料！');
            }
            if (!empty($id)) {
                unset($data['uniacid']);
                unset($data['uid']);
                pdo_update('mc_member_address', $data, array(
                    'id' => $id
                ));
                $this->showMessage($id, '', 'ajax');
            } else {
                pdo_update('mc_member_address', array(
                    'isdefault' => 0
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $_W['fans']['uid']
                ));
                $data['isdefault'] = 1;
                pdo_insert('mc_member_address', $data);
                $id = pdo_insertid();
                if (!empty($id)) {
                    message($id, '', 'ajax');
                } else {
                    message(0, '', 'ajax');
                }
            }
        } elseif ($operation == 'default') {
            $id      = intval($_GPC['id']);
            $sql     = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id AND `uniacid` = :uniacid
					 AND `uid` = :uid';
            $params  = array(
                ':id' => $id,
                ':uniacid' => $_W['uniacid'],
                ':uid' => $_W['fans']['uid']
            );
            $address = pdo_fetch($sql, $params);
            if (!empty($address) && empty($address['isdefault'])) {
                pdo_update('mc_member_address', array(
                    'isdefault' => 0
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $_W['fans']['uid']
                ));
                pdo_update('mc_member_address', array(
                    'isdefault' => 1
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $_W['fans']['uid'],
                    'id' => $id
                ));
            }
            message(1, '', 'ajax');
        } elseif ($operation == 'detail') {
            $id  = intval($_GPC['id']);
            $sql = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id';
            $row = pdo_fetch($sql, array(
                ':id' => $id
            ));
            message($row, '', 'ajax');
        } elseif ($operation == 'remove') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $where   = ' AND `uniacid` = :uniacid AND `uid` = :uid';
                $sql     = 'SELECT `isdefault` FROM ' . tablename('mc_member_address') . ' WHERE `id` = :id' . $where;
                $params  = array(
                    ':id' => $id,
                    ':uniacid' => $_W['uniacid'],
                    ':uid' => $_W['fans']['uid']
                );
                $address = pdo_fetch($sql, $params);
                if (!empty($address)) {
                    pdo_delete('mc_member_address', array(
                        'id' => $id
                    ));
                    if ($address['isdefault'] > 0) {
                        $sql = 'SELECT MAX(id) FROM ' . tablename('mc_member_address') . ' WHERE 1 ' . $where;
                        unset($params[':id']);
                        $maxId = pdo_fetchcolumn($sql, $params);
                        if (!empty($maxId)) {
                            pdo_update('mc_member_address', array(
                                'isdefault' => 1
                            ), array(
                                'id' => $maxId
                            ));
                            die(json_encode(array(
                                "result" => 1,
                                "maxid" => $maxId
                            )));
                        }
                    }
                }
            }
            die(json_encode(array(
                "result" => 1,
                "maxid" => 0
            )));
        } else {
            $sql    = 'SELECT * FROM ' . tablename('mc_member_address') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid';
            $params = array(
                ':uniacid' => $_W['uniacid']
            );
            if (empty($_W['member']['uid'])) {
                $params[':uid'] = $_W['fans']['openid'];
            } else {
                $params[':uid'] = $_W['member']['uid'];
            }
            $addresses = pdo_fetchall($sql, $params);
            include $this->template('address');
        }
    }
    public function send_template_message($data)
    {
        global $_W, $_GPC;
        load()->classs('weixin.account');
        load()->func('communication');
        $access_token = WeAccount::token();
        $url          = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $response     = ihttp_request($url, $data);
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty($result)) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty($result['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
        }
        return true;
    }
    public function showMessage($msg, $redirect = '', $type = '')
    {
        global $_W;
        if ($redirect == 'refresh') {
            $redirect = $_W['script_name'] . '?' . $_SERVER['QUERY_STRING'];
        } elseif (!empty($redirect) && !strexists($redirect, 'http://')) {
            $urls     = parse_url($redirect);
            $redirect = $_W['siteroot'] . 'app/index.php?' . $urls['query'];
        }
        if ($redirect == '') {
            $type = in_array($type, array(
                'success',
                'error',
                'info',
                'warning',
                'ajax',
                'sql'
            )) ? $type : 'info';
        } else {
            $type = in_array($type, array(
                'success',
                'error',
                'info',
                'warning',
                'ajax',
                'sql'
            )) ? $type : 'success';
        }
        if ($_W['isajax'] || $type == 'ajax') {
            $vars             = array();
            $vars['message']  = $msg;
            $vars['redirect'] = $redirect;
            $vars['type']     = $type;
            exit(json_encode($vars));
        }
        if (empty($msg) && !empty($redirect)) {
            header('location: ' . $redirect);
        }
        $label = $type;
        if ($type == 'error') {
            $label = 'danger';
            $info  = '出错了，原因：';
        }
        if ($type == 'ajax' || $type == 'sql') {
            $label = 'warning';
            $info  = '访问受限，原因：';
        }
        if ($type == 'info') {
            $label = 'info';
            $info  = '操作提示';
        }
        if ($type == 'success') {
            $info = '操作成功';
        }
        if (defined('IN_API')) {
            exit($msg);
        }
        include $this->template('messages', TEMPLATE_INCLUDEPATH);
        exit();
    }
    public function webmessage($error, $url = '', $errno = -1)
    {
        $data          = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }
    public function doMobilechangeAjax()
    {
        global $_W, $_GPC;
        $id       = intval($_GPC['id']);
        $reid     = intval($_GPC['reid']);
        $activity = $this->get_yuyuepay($reid);
        $row      = pdo_fetch("SELECT * FROM " . tablename($this->tb_info) . " WHERE rerid = :rerid", array(
            ':rerid' => $id
        ));
        $xiangmu  = $this->get_xiangmu($row['reid'], $row['xmid']);
        if ($activity['is_time'] == 0) {
            $times = $row['yuyuetime'];
        } elseif ($activity['is_time'] == 2) {
            $times = $row['restime'];
        }
        $data = array(
            'status' => $_GPC['status']
        );
        if (!empty($id)) {
            $url   = $_W['siteroot'] . 'app/' . $this->createMobileUrl('detail', array(
                'reid' => $row['reid'],
                'id' => $row['rerid']
            ));
            $datas = array(
                'first' => array(
                    'value' => $par['mfirst'] . "\n",
                    'color' => "#743A3A"
                ),
                'keyword1' => array(
                    'value' => $row['member']
                ),
                'keyword2' => array(
                    'value' => $xiangmu['title'] . " - " . $xiangmu['price'] . "元"
                ),
                'keyword3' => array(
                    'value' => $times
                ),
                'keyword4' => array(
                    'value' => $activity['state3']
                ),
                'remark' => array(
                    'value' => "\n" . $activity['mfoot'],
                    'color' => "#008000"
                )
            );
            $acc   = WeAccount::create($_W['acid']);
            $acc->sendTplNotice($row['openid'], $activity['m_templateid'], $datas, $url, "#FF0000");
            pdo_update($this->tb_info, $data, array(
                'rerid' => $id
            ));
            $this->AjaxMessage('更新成功!', 1);
        } else {
            $this->AjaxMessage('更新失败!', 0);
        }
    }
    public function doMobiledayu_Delete()
    {
        global $_W, $_GPC;
        $id   = intval($_GPC['id']);
        $reid = intval($_GPC['reid']);
        if (!empty($id)) {
            pdo_delete($this->tb_info, array(
                'rerid' => $id
            ));
            pdo_delete($this->tb_data, array(
                'rerid' => $id
            ));
        }
        $this->AjaxMessage('删除成功!', 1);
    }
    public function AjaxMessage($msg, $status = 0)
    {
        $result = array(
            'message' => $msg,
            'status' => $status
        );
        echo json_encode($result);
        exit;
    }
    public function get_status($reid, $status)
    {
        global $_W, $_GPC;
        $activity = $this->get_yuyuepay($reid);
        $par      = iunserializer($activity['par']);
        $state1   = !empty($par['state1']) ? $par['state1'] : '待受理';
        $state2   = !empty($par['state2']) ? $par['state2'] : '受理中';
        $state3   = !empty($par['state3']) ? $par['state3'] : '已完成';
        $state4   = !empty($par['state4']) ? $par['state4'] : '拒绝受理';
        $state5   = !empty($par['state5']) ? $par['state5'] : '已取消';
        $state8   = !empty($par['state8']) ? $par['state8'] : '退回修改';
        $state    = array(
            '0' => array(
                'css' => 'weui_btn_default',
                'css2' => 'btn-default',
                'name' => $state1
            ),
            '1' => array(
                'css' => 'weui_btn_primary',
                'css2' => 'btn-success',
                'name' => $state2
            ),
            '2' => array(
                'css' => 'weui_btn_warn',
                'css2' => 'btn-warning',
                'name' => $state4
            ),
            '3' => array(
                'css' => 'bg-blue',
                'css2' => 'btn-primary',
                'name' => $state3
            ),
            '7' => array(
                'css' => 'weui_btn_disabled weui_btn_warn',
                'css2' => 'btn-danger',
                'name' => '已退款'
            ),
            '8' => array(
                'css' => 'bg-orange',
                'css2' => 'btn-warning',
                'name' => $state8
            ),
            '9' => array(
                'css' => 'weui_btn_disabled weui_btn_warn',
                'css2' => 'btn-warning',
                'name' => $state5
            )
        );
        return $state[$status];
    }
    public function get_status_name($reid, $status)
    {
        $status = $this->get_status($reid, $status);
        return $status['name'];
    }
    public function get_store_boss($bid)
    {
        global $_GPC, $_W;
        if (pdo_tableexists('dayu_yuyuepay_plugin_store_store')) {
            return pdo_get('dayu_yuyuepay_plugin_store_boss', array(
                'weid' => $_W['uniacid'],
                'id' => $bid
            ), array());
        }
    }
    public function get_store($sid)
    {
        global $_GPC, $_W;
        if (pdo_tableexists('dayu_yuyuepay_plugin_store_store')) {
            return pdo_get('dayu_yuyuepay_plugin_store_store', array(
                'weid' => $_W['uniacid'],
                'id' => $sid
            ), array());
        }
    }
    public function get_yuyue($reid, $type)
    {
        global $_GPC, $_W;
        if ($type == 1) {
            return pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid and openid = :openid", array(
                ":reid" => $reid,
                ":openid" => $_W['openid']
            ));
        } elseif ($type == 2) {
            return pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->tb_info) . " WHERE reid = :reid", array(
                ":reid" => $reid
            ));
        }
    }
    public function get_skin($sections)
    {
        return pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " WHERE sections = :sections ", array(
            ':sections' => $sections
        ));
    }
    public function get_userinfo($weid, $openid)
    {
        load()->model('mc');
        return mc_fetch($openid);
    }
    public function get_role($uid)
    {
        global $_GPC, $_W;
        return pdo_get('users', array(
            'uid' => $uid
        ), array());
    }
    public function get_isrole($reid, $uid)
    {
        global $_GPC, $_W;
        return pdo_get($this->tb_role, array(
            'weid' => $_W['uniacid'],
            'reid' => $reid,
            'roleid' => $uid
        ), array());
    }
    public function get_staff($openid)
    {
        global $_GPC, $_W;
        return pdo_get($this->tb_staff, array(
            'weid' => $_W['uniacid'],
            'openid' => $openid
        ), array());
    }
    public function get_manger($reid, $openid)
    {
        global $_GPC, $_W;
        return pdo_get($this->tb_yuyue, array(
            'weid' => $_W['uniacid'],
            'reid' => $reid,
            'kfid' => $openid
        ), array());
    }
    public function get_xiangmu($reid, $xmid)
    {
        global $_GPC, $_W;
        return pdo_get($this->tb_item, array(
            'weid' => $_W['uniacid'],
            'id' => $xmid,
            'reid' => $reid,
            'isshow' => 1
        ), array());
    }
    public function get_info($uid)
    {
        global $_W;
        return pdo_get('mc_members', array(
            'uniacid' => $_W['uniacid'],
            'uid' => $uid
        ), array());
    }
    public function get_yuyuepay($reid)
    {
        global $_W;
        return pdo_get($this->tb_yuyue, array(
            'weid' => $_W['uniacid'],
            'reid' => $reid
        ), array());
    }
    public function get_category($id)
    {
        global $_W;
        return pdo_get($this->tb_category, array(
            'weid' => $_W['uniacid'],
            'id' => $id
        ), array());
    }
    public function isHy($openid)
    {
        global $_W;
        load()->model('mc');
        $card = pdo_get('mc_card_members', array(
            'uniacid' => $_W['uniacid'],
            'openid' => $openid
        ), array());
        if (empty($card)) {
            return false;
        } else {
            return true;
        }
    }
    function pagination($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => ''))
    {
        global $_W;
        $pdata = array(
            'tcount' => 0,
            'tpage' => 0,
            'cindex' => 0,
            'findex' => 0,
            'pindex' => 0,
            'nindex' => 0,
            'lindex' => 0,
            'options' => ''
        );
        if ($context['ajaxcallback']) {
            $context['isajax'] = true;
        }
        $pdata['tcount'] = $tcount;
        $pdata['tpage']  = ceil($tcount / $psize);
        if ($pdata['tpage'] <= 1) {
            return '';
        }
        $cindex          = $pindex;
        $cindex          = min($cindex, $pdata['tpage']);
        $cindex          = max($cindex, 1);
        $pdata['cindex'] = $cindex;
        $pdata['findex'] = 1;
        $pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
        $pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
        $pdata['lindex'] = $pdata['tpage'];
        if ($context['isajax']) {
            if (!$url) {
                $url = $_W['script_name'] . '?' . http_build_query($_GET);
            }
            $pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
            $pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
        } else {
            if ($url) {
                $pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
                $pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
                $pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
                $pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
            } else {
                $_GET['page'] = $pdata['findex'];
                $pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['pindex'];
                $pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['nindex'];
                $pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
                $_GET['page'] = $pdata['lindex'];
                $pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
            }
        }
        $html = '<div class="pager">';
        if ($pdata['cindex'] > 1) {
            $html .= "<div class=\"pager-left\">";
            $html .= "<div class=\"pager-first\"><a {$pdata['faa']} class=\"pager-nav\">首页</a></div>";
            $html .= "<div class=\"pager-pre\"><a {$pdata['paa']}>上一页</a></div>";
            $html .= "</div>";
        } else {
            $html .= "<div class=\"pager-left\">";
            $html .= "<div class=\"pager-pre\" style=\"width:100%\"><a href=\"###\">这是第一页</a></div>";
            $html .= "</div>";
        }
        $html .= "<div class=\"pager-cen\">{$pindex} / " . $pdata['tpage'] . "</div>";
        if ($pdata['cindex'] < $pdata['tpage']) {
            $html .= "<div class=\"pager-right\">";
            $html .= "<div class=\"pager-next\"><a {$pdata['naa']} class=\"pager-nav\">下一页</a></div>";
            $html .= "<div class=\"pager-end\"><a {$pdata['laa']} class=\"pager-nav\">尾页</a></div>";
            $html .= "</div>";
        } else {
            $html .= "<div class=\"pager-right\">";
            $html .= "<div class=\"pager-next\" style=\"width:100%\"><a href=\"###\">已是尾页</a></div>";
            $html .= "</div>";
        }
        $html .= '<div class="clr"></div></div>';
        return $html;
    }
    public function doMobileFansUs()
    {
        global $_W, $_GPC;
        $qrcodesrc = tomedia('qrcode_' . $_W['acid'] . '.jpg');
        include $this->template('fans_us');
    }
    public function getFollow()
    {
        global $_GPC, $_W;
        require 'fans.mobile.php';
        $p = pdo_fetch("SELECT follow FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :weid AND openid = :openid LIMIT 1", array(
            ":weid" => $weid,
            ":openid" => $openid
        ));
        if ($p['follow'] == 0) {
            header('Location: ' . $this->createMobileUrl('FansUs'), true, 301);
        } else {
            return true;
        }
    }
    private function checkAuth($weid, $openid)
    {
        global $_W;
        $settings = cache_load('unisetting:' . $weid);
        if (empty($_W['member']['uid']) && empty($settings['passport']['focusreg'])) {
            $fan = pdo_get('mc_mapping_fans', array(
                'uniacid' => $weid,
                'openid' => $openid
            ));
            if (!empty($fan)) {
                $fanid = $fan['fanid'];
            } else {
                if (empty($openid)) {
                    $_W['openid'] = random(28);
                }
                $post = array(
                    'acid' => $_W['acid'],
                    'uniacid' => $weid,
                    'updatetime' => time(),
                    'openid' => $openid,
                    'follow' => 0
                );
                pdo_insert('mc_mapping_fans', $post);
                $fanid = pdo_insertid();
            }
            if (empty($fan['uid'])) {
                pdo_insert('mc_members', array(
                    'uniacid' => $weid
                ));
                $uid                 = pdo_insertid();
                $_W['member']['uid'] = $uid;
                $_W['fans']['uid']   = $uid;
                pdo_update('mc_mapping_fans', array(
                    'uid' => $uid
                ), array(
                    'fanid' => $fanid
                ));
            } else {
                $_W['member']['uid'] = $fan['uid'];
                $_W['fans']['uid']   = $fan['uid'];
            }
        } else {
            checkauth();
        }
    }
    private function checkauth333($openid, $nickname, $headimgurl)
    {
        global $_W, $engine;
        $settings = cache_load('unisetting:' . $_W['uniacid']);
        if (empty($_W['member']['uid']) && empty($settings['passport']['focusreg'])) {
            $fan = pdo_get('mc_mapping_fans', array(
                'uniacid' => $_W['uniacid'],
                'openid' => $openid
            ));
            if (!empty($fan)) {
                $fanid = $fan['fanid'];
            } else {
                $post = array(
                    'acid' => $_W['acid'],
                    'uniacid' => $_W['uniacid'],
                    'nickname' => $nickname,
                    'openid' => $_W['fans']['openid'],
                    'salt' => random(8),
                    'follow' => 0,
                    'updatetime' => TIMESTAMP,
                    'tag' => base64_encode(iserializer($_W['fans']))
                );
                pdo_insert('mc_mapping_fans', $post);
                $fanid = pdo_insertid();
            }
            if (empty($fan['uid'])) {
                $email           = md5($oauth['openid']) . '@vqiyi.cn';
                $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(
                    ':uniacid' => $_W['uniacid']
                ));
                $data            = array(
                    'uniacid' => $_W['uniacid'],
                    'email' => $email,
                    'salt' => random(8),
                    'groupid' => $default_groupid,
                    'createtime' => TIMESTAMP,
                    'password' => md5($message['from'] . $data['salt'] . $_W['config']['setting']['authkey']),
                    'avatar' => $headimgurl,
                    'nickname' => $nickname
                );
                pdo_insert('mc_members', $data);
                $uid                 = pdo_insertid();
                $_W['member']['uid'] = $uid;
                $_W['fans']['uid']   = $uid;
                pdo_update('mc_mapping_fans', array(
                    'uid' => $uid
                ), array(
                    'fanid' => $fanid
                ));
            } else {
                $_W['member']['uid'] = $fan['uid'];
                $_W['fans']['uid']   = $fan['uid'];
            }
        } else {
            checkauth();
        }
    }
    public function doMobileUpthumb()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $mode = $_GPC['mode'];
        if ($mode == 'avatar') {
            $data = array(
                'avatar' => $_GPC['thumb']
            );
            load()->model('mc');
            if (mc_update($openid, $data) === false) {
                $result['status'] = 'error';
                $result['msg']    = '上传失败';
            } else {
                $result['status'] = 'success';
                $result['msg']    = '上传成功';
            }
        }
        message($result, '', 'ajax');
    }
    public function doMobileLocateStore()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $result            = array(
            'error' => 'error',
            'message' => '',
            'data' => ''
        );
        $store             = pdo_fetch("SELECT name,address,mobile,lat,lng FROM " . tablename('dayu_yuyuepay_plugin_store_store') . " WHERE weid = :weid AND id = :id", array(
            ':weid' => $weid,
            ':id' => $_GPC['id']
        ));
        $result['name']    = $store['name'];
        $result['address'] = $store['loc_p'] . $store['loc_c'] . $store['loc_a'] . $store['address'];
        $result['mobile']  = $store['mobile'];
        $result['lat']     = $store['lat'];
        $result['lng']     = $store['lng'];
        $result['message'] = $store['lat'];
        die(json_encode($result));
    }
}
function tpl_field_date($name, $value = '', $is_time, $withtime = false)
{
    $s = '';
    if (!defined('TPL_INIT_DATA')) {
        $s = '
			<script type="text/javascript">
				require(["datetimepicker"], function(){
					$(function(){
						$(".datetimepicker").each(function(){
							var option = {
								lang : "zh",
								step : "10",
								timepicker : ' . (!empty($withtime) ? "true" : "false") . ',closeOnDateSelect : true,
			format : "Y-m-d' . (!empty($withtime) ? ' H:i:s"' : '"') . '};
			$(this).datetimepicker(option);
		});
	});
});
</script>';
        define('TPL_INIT_DATA', true);
    }
    $withtime = empty($withtime) ? false : true;
    if ($is_time == 2) {
        $value = strexists($value, '-') ? strtotime($value) : $value;
    } else {
        $value = TIMESTAMP;
    }
    if (!empty($value)) {
        $value = ($withtime ? date('Y-m-d H:i:s', $value) : date('Y-m-d', $value));
    }
    $s .= '<input type="text" name="' . $name . '"  value="' . $value . '" placeholder="预约日期筛选" class="datetimepicker btn btn-default" style="padding-left:12px;" />';
    return $s;
}
function tpl_app_form_avatar_thumb($name, $value = '', $id, $mode)
{
    global $_W;
    $val = tomedia("headimg_" . $_W['uniacid'] . ".jpg");
    if (!empty($value)) {
        $val = tomedia($value);
    }
    $html = '
			<a class="weui_media_hd js-avatar-' . $name . '" style="padding:0;">
				<img class="weui_media_appmsg_thumb" src="' . $val . '" style="width:100%;height:auto;" class="img-max center">
			</a>
	';
    $href = url('entry', array(
        'do' => 'Upthumb',
        'id' => $id,
        'm' => 'dayu_yuyuepay',
        'mode' => $mode
    ), true, true);
    $html .= "<script>
		util.image($('.js-avatar-{$name}'), function(url){
			$('.js-avatar-{$name} img').attr('src', url.url);
			$.post('" . $href . "', {'thumb' : url.attachment}, function(data) {
				if (data.message.status == 'success') {
					util.toast(data.message.msg);
					setTimeout(function() {
						window.location.href = decodeURIComponent(returnurl);
					}, 2000)
				} else {
					util.toast('更新失败');
				}
			},'json')
		}, {
			crop : true
		});
	</script>";
    return $html;
}
function tpl_form_field_images($name, $value = '', $default = '', $options = array())
{
    global $_W;
    if (empty($default)) {
        $default = './resource/images/nopic.jpg';
    }
    $val = $default;
    if (!empty($value)) {
        $val = tomedia($value);
    }
    if (!empty($options['global'])) {
        $options['global'] = true;
    } else {
        $options['global'] = false;
    }
    if (empty($options['class_extra'])) {
        $options['class_extra'] = '';
    }
    if (isset($options['dest_dir']) && !empty($options['dest_dir'])) {
        if (!preg_match('/^\w+([\/]\w+)?$/i', $options['dest_dir'])) {
            exit('图片上传目录错误,只能指定最多两级目录,如: "store","store/d1"');
        }
    }
    $options['direct']   = true;
    $options['multiple'] = false;
    if (isset($options['thumb'])) {
        $options['thumb'] = !empty($options['thumb']);
    }
    $s = '';
    if (!defined('TPL_INIT_IMAGE')) {
        $s = '
		<script type="text/javascript">
			function showImageDialog(elm, opts, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					var img = ipt.parent().next().children();
					options = ' . str_replace('"', '\'', json_encode($options)) . ';
					util.image(val, function(url){
						if(url.url){
							if(img.length > 0){
								img.get(0).src = url.url;
							}
							ipt.val(url.attachment);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
						}
						if(url.media_id){
							if(img.length > 0){
								img.get(0).src = "";
							}
							ipt.val(url.media_id);
						}
					}, null, options);
				});
			}
			function deleteImage(elm){
				require(["jquery"], function($){
					$(elm).prev().attr("src", "./resource/images/nopic.jpg");
					$(elm).parent().prev().find("input").val("");
				});
			}
		</script>';
        define('TPL_INIT_IMAGE', true);
    }
    $s .= '
		<div class="input-group ' . $options['class_extra'] . '">
			<input type="text" name="' . $name . '" value="' . $value . '"' . ($options['extras']['text'] ? $options['extras']['text'] : '') . ' id="re-image" class="form-control" autocomplete="off">
			<span class="input-group-btn">
				<button class="btn btn-default" type="button" onclick="showImageDialog(this);">选择图片</button>
			</span>
		</div>
		<div class="col-xs-12 ' . $options['class_extra'] . '" style="margin-top:.5em;">
			<em class="close" style="position:absolute; top: 0px; right: -14px;font-size:18px;color:#333;" title="删除这张图片" onclick="deleteImage(this)">× 删除</em>
		</div>';
    return $s;
}
function change_to_quotes($str)
{
    return sprintf('%s', $str);
}

?>