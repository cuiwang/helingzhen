<?php

/**
 * 广告自主投放平台模块微站定义
 *
 * @author Michael Hu And Garcia Lo
 * @url http://bbs.9ye.cc/
 */
defined('IN_IA') or exit('Access Denied');

define('MB_ROOT', IA_ROOT . '/addons/cgc_ad');
define('STYLE_PATH', '../addons/cgc_ad');
require MB_ROOT . '/inc/util.php';
include MB_ROOT . '/wechatapi.php';
include MB_ROOT . '/source/common/common.php';
session_start();

class cgc_adModuleSite extends WeModuleSite
{
    public $settings;
    public $sys_settings;
    public $adv;
    public $quan;
    public $member;
    public $inviter;

    public $model = array(
        'detail' => array(
            'title' => '普通模式',
            'model' => '1'
        ),
        'group_detail' => array(
            'title' => '组团模式',
            'model' => '2'
        ),
        'free_detail' => array(
            'title' => '免费模式',
            'model' => '3'
        ), 'task_detail' => array(
            'title' => '任务模式',
            'model' => '4'
        ), 'share_detail' => array(
            'title' => '分享文章模式',
            'model' => '5'
        ), 'voice_detail' => array(
            'title' => '语音红包模式',
            'model' => '6'
        ), 'psw_detail' => array(
            'title' => '口令模式',
            'model' => '7'
        ), 'couponc_detail' => array(
            'title' => '卡券模式',
            'model' => '8'
        ), 'qr_detail' => array(
            'title' => '二唯码模式',
            'model' => '9'
        )

    );


    public $index_model = array(
        '1' => array(
            'title' => '普',
            'model' => '1'
        ),
        '2' => array(
            'title' => '团',
            'model' => '2'
        ),
        '3' => array(
            'title' => '免',
            'model' => '3'
        ), '4' => array(
            'title' => '任',
            'model' => '4'
        ), '5' => array(
            'title' => '文',
            'model' => '5'
        ), '6' => array(
            'title' => '语',
            'model' => '6'
        ), '7' => array(
            'title' => '令',
            'model' => '7'
        ), '8' => array(
            'title' => '券',
            'model' => '8'
        ), '9' => array(
            'title' => '码',
            'model' => '9'
        )
    );

    public $fabu_model = array(
        'fabu' => array(
            'title' => '普通模式',
            'model' => '1'
        ),
        'group_fabu' => array(
            'title' => '组团模式',
            'model' => '2'
        ),
        'free_fabu' => array(
            'title' => '免费模式',
            'model' => '3'
        ), 'task_fabu' => array(
            'title' => '任务模式',
            'model' => '4'
        ), 'share_fabu' => array(
            'title' => '分享文章模式',
            'model' => '5'
        ), 'voice_fabu' => array(
            'title' => '语音红包模式',
            'model' => '6'
        ), 'psw_fabu' => array(
            'title' => '口令模式',
            'model' => '7'
        ), 'couponc_fabu' => array(
            'title' => '卡券模式',
            'model' => '8'
        ), 'qr_fabu' => array(
            'title' => '二唯码模式',
            'model' => '9'
        )
    );


    //通过任务类型值取得类型名称
    public function get_model($key)
    {
        //静态类变量这样访问
        $model = $this->model;
        $model_name = "";

        foreach ($model as $name => $item) {
            if ($item['model'] == $key) {
                $model_name = $name;
                break;
            }
        }
        if (empty($model_name)) {
            $this->returnError('没定义模式名称');
        }
        return $model_name;
    }

    //通过任务类型值取得类型名称
    public function get_modelName($key)
    {
        //静态类变量这样访问
        $model = $this->model;
        $model_name = "";

        foreach ($model as $name => $item) {
            if ($item['model'] == $key) {
                $model_name = $item;
                break;
            }
        }
        if (empty($model_name)) {
            $this->returnError('没定义模式名称');
        }
        return $model_name['title'];
    }

    public function __construct()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $this->settings = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_setting') . " WHERE weid=" . $weid);
        $this->settings['unit_text'] = empty($this->settings['unit_text']) ? "元" : $this->settings['unit_text'];
        $this->settings['rush_text'] = empty($this->settings['rush_text']) ? "抢钱" : $this->settings['rush_text'];
        $sql = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
        $this->sys_settings = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':module' => "cgc_ad"));
        $this->sys_settings = iunserializer($this->sys_settings);
        if (!empty($dd_settings) && $dd_settings['debug_mode']) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL ^ E_NOTICE);
        } else {
            error_reporting(0);
        }


    }

    //增加阅读数
    protected function get_view($member, $adv)
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $mid = $member['id'];
        $quan = $this->get_quan();
        $id = $_GPC['id'];
        $temp_view = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_pv') . " WHERE mid=" . $mid . " AND weid=" . $weid . " AND advid=" . $id . " AND quan_id=" . $adv['quan_id']);
        if (empty($temp_view)) {
            $data2 = array(
                'weid' => $weid,
                'quan_id' => $adv['quan_id'],
                'advid' => $id,
                'mid' => $mid,
                'createtime' => TIMESTAMP,
            );
            pdo_insert("cgc_ad_pv", $data2);
            $multiple = empty($quan['click_multiple']) ? 1 : $quan['click_multiple'];
            pdo_update("cgc_ad_adv", array('views' => $adv['views'] + $multiple), array('id' => $adv['id']));
            $adv['views'] += $multiple;
        }
        return $adv['views'];
    }

    //获得用户的信息
    protected function get_rob_next_time($quan, $member)
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $rob_next_time = $member['rob_next_time'];
        return $rob_next_time;
    }


    //获得广告信息
    protected function get_adv()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $quan_id = $_GPC['quan_id'];
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $this->returnError('访问错误，缺少参数');
        }

        $adv = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_adv') . " where weid=$weid and id=$id and quan_id=$quan_id and del=0");

        if ($_GPC['do'] == "adv_check" || $_GPC['do'] == "ye_pay") {
            return $adv;
        }

        if (empty($adv)) {
            $this->returnError('你要访问的，已经不见了');
        }

        if ($adv['status'] == 3) {
            $this->returnError('该活动仍未审核通过，请耐心等待');
        }

        if ($adv['status'] == 4) {
            $this->returnError('该活动审核不通过');
        }

        if ($adv['status'] == 5) {
            $this->returnError('该活动审核不通过并已经将广告费退还');
        }

        if ($adv['status'] != 1 && empty($_GPC['cmd'])) {
            $this->returnError('活动未付款');
        }

        //发现模式不对，要进行跳转
        if (!empty($this->model[$_GPC['do']]['model']) && $this->model[$_GPC['do']]['model'] != $adv['model']) {
            $model_name = $this->get_model($adv['model']);
            header("location:" . $this->createMobileUrl($model_name, array('quan_id' => $quan_id, 'id' => $id, 'model' => $adv['model'])));
            exit();
        }

        if ($adv['fl_type'] == 1) {
            $this->settings['unit_text'] = "积分";
        } else if ($adv['fl_type'] == 2) {
            $this->settings['unit_text'] = "余额";
        }

        return $adv;
    }

    //获得卡券信息
    protected function get_couponc()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $quan_id = $_GPC['quan_id'];
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $this->returnError('访问错误，缺少参数');
        }

        $member = $this->get_member();

        $couponc = pdo_fetch("select * from " . tablename('cgc_ad_couponc') . " where weid=:weid and quan_id=:quan_id and advid=:advid and mid=:mid ", array(':weid' => $_W['uniacid'], ':quan_id' => $quan_id, ':advid' => $id, ':mid' => $member['id']));

        return $couponc;
    }


    //获得圈圈信息
    protected function get_quan()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $quan_id = $_GPC['quan_id'];
        $config = $this->module['config'];

        if (empty($_SESSION['enter_control']) && $config['enter_control']) {
            header("location:" . "http://qq.com");
            exit();
        }

        if (!empty($this->quan)) {
            return $this->quan;
        }

        if (empty($quan_id)) {
            $msg = "你访问的网站找不到了";
            $err_title = "温馨提示";
            $label = 'warn';
            include $this->template('error');
            exit();
        }

        $_kf = $this->settings['kf_openid'];
        $URL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $_SESSION['test11'] = empty($_GPC['test11']) ? $_SESSION['test11'] : $_GPC['test11'];
        
        if ($_W['container'] != "wechat") {
          if ((empty($_kf) || !strexists($_kf, $_SESSION['test11'])) && !$config['all_net']) {
            $msg = "非法访问";
            $err_title = "温馨提示";
            $label = 'warn';
            include $this->template('error');
            exit();
           }
        }
        
        $quan = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_quan') . " WHERE weid=" . $weid . " AND id=" . $quan_id);
        $quan['piece_model'] = explode(",", $quan['piece_model']);
        $city_arr = explode('|', $quan['city']);
        $quan['city'] = str_replace("|", "或", $quan['city']);
        if (empty($quan) || $quan['del'] == 1 || $quan['status'] == 0) {
            $msg = $quan['aname'] . "正在维护，请稍后再来吧~";
            $err_title = "温馨提示";
            $label = 'warn';
            include $this->template('error');
            exit();
        }

        $fabu_model = $this->fabu_model[$_GPC['do']];
        
        if (!empty($fabu_model) && !in_array($fabu_model['model'], $quan['piece_model'])) {
            $msg = "没有权限访问" . $fabu_model['title'];
            $err_title = "温馨提示";
            $label = 'warn';
            include $this->template('error');
            exit();
        }

        $quan['guanzhu_qrcode'] = empty($quan['guanzhu_qrcode']) ? $_W['account']['qrcode'] : tomedia($quan['guanzhu_qrcode']);
        $quan['guanzhu_name'] = empty($quan['guanzhu_name']) ? $_W['account']['name'] : $quan['guanzhu_name'];
        if (!empty($quan['yun_rule'])) {
            $quan['yun_rule'] = unserialize($quan['yun_rule']);
        }

        $this->quan = $quan;

        //判断ip地址是否在禁止的区域
        //  if (strexists($_SERVER['HTTP_USER_AGENT'],"mobile")){
        if ($this->quan['forbidden_addr']) {
          $ret = judge_forbidden_addr($this->quan['forbidden_addr']);
          if ($ret == true) {
            message("不符合资格");
           }
        }
        //  }

        return $quan;
    }


    //获得类型信息
    protected function get_type_list($quan_id = 0, $b = 0, $parent_id = 0)
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $con = "";
        if (!empty($b)) {
            $con = " SELECT '0' as id,0 as parent_id,-1 as sequence,'全部' as value, '' as info_type_pic, '' as info_type_color, '' as info_type_icon, '' as info_type_url FROM DUAL union all ";
        }

        $quan_id = empty($quan_id) ? $_GPC['quan_id'] : $quan_id;
        $con1 = "";
        if ($parent_id != -1) {
            if (!empty($parent_id)) {
                $con1 = " AND parent_id = $parent_id";
            } else {
                $con1 = " AND (parent_id=0 or parent_id is null)";
            }
        }

        $con1 .= " order by sequence asc";

        $type_list = pdo_fetchall($con . "select id,parent_id,sequence,info_type_name value,info_type_pic ,info_type_color,info_type_icon,info_type_url from " . tablename('cgc_ad_info_type') . " where quan_id=" . $quan_id . " and status=0 $con1");
        return $type_list;
    }


    public function domobileTwolevel()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $quan_id = $_GPC['quan_id'];
        $con = "";
        $id = intval($_GPC['id']);
        //$con1 = " and parent_id=$id";
        //$type_list =pdo_fetchall("select id,parent_id,info_type_name value,info_type_pic ,info_type_color,info_type_icon,info_type_url from ".tablename('cgc_ad_info_type')." where quan_id=".$quan_id." and status=0 $con1");
        $type_list = gettwolevel($quan_id, $id);

        exit(json_encode($type_list));
    }


    public function doWebTwolevel()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $quan_id = $_GPC['quan_id'];
        $con = "";

        $id = intval($_GPC['id']);
        $type_list = gettwolevel($quan_id, $id);
        exit(json_encode($type_list));
    }


    //获得明细信息
    protected function get_two_level()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $quan_id = $_GPC['quan_id'];
        $config = $this->module['config'];
        $con = "";
        $con1 = " and parent_id>0";
        $type_list = pdo_fetchall("select id,parent_id,info_type_name value,info_type_pic ,info_type_color,info_type_icon,info_type_url from " . tablename('cgc_ad_info_type') . " where quan_id=" . $quan_id . " and status=0 $con1");
        return $type_list;
    }


    //获得明细信息
    protected function get_type_detail($parent_id = 0)
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $quan_id = $_GPC['quan_id'];
        $quan = $this->get_quan();
        if (empty($quan['second_menu_enabled'])) {
            $parent_id = 0;
        }
        $config = $this->module['config'];
        $parent_id = empty($parent_id) ? 0 : $parent_id;
        $con = "";

        $con = " SELECT  '$parent_id' as id, -1 as sequence,$parent_id as parent_id,'全部' as value, '' as info_type_pic, '' as info_type_color, '' as info_type_icon, '' as info_type_url FROM DUAL union all ";
        $con1 = " AND parent_id = $parent_id";

        $con1 .= " order by sequence asc";

        $type_list = pdo_fetchall($con . "select id,sequence,parent_id,info_type_name value,info_type_pic,info_type_color,info_type_icon,info_type_url from " . tablename('cgc_ad_info_type') . " where quan_id=" . $quan_id . " and status=0 $con1");


        return $type_list;
    }


    protected function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    //获得用户的信息
    protected function get_member()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $quan_id = $_GPC['quan_id'];
        $ori_openid = $_W['openid'];
        $config = $this->settings;
        $userinfo = getFromUser($this->settings, $this->modulename, $this->module['config']);
        $userinfo = json_decode($userinfo, true);
        $from_user = $userinfo['openid'];

        if (is_error($userinfo) || empty($from_user)) {
            print_r($userinfo);
            exit();
        }

        $quan = $this->get_quan();
        //神秘防封模块的防封杀
        if ($quan['yun_fkz'] == 1) {
            $uid = $_W['member']['uid'];
            $yun_fkz_member = new yun_fkz_member();
            load()->model('module');
            $module = module_fetch('yun_fkz');
            $fkz_member = $yun_fkz_member->get_jymember($uid);
            if (empty($fkz_member['parent1']) && $uid != $module['config']['uid']) {
                include $this->template('nolevel');
                exit();
            }
        }


        $member = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_member') .
            " WHERE   weid=" . $weid . " AND quan_id=" . $quan_id . " and openid='{$userinfo['openid']}' and openid!='' order by id asc");
        $follow = 0;
        
        if ($member && empty($member['status'])){
          message("系统已经关闭");
        }

        if (empty($member['follow']) && $_W['account']['level'] > 3) {
           if (empty($userinfo['subscribe'])){
             $follow = sfgz_user($_W['openid']);
           } else {
             $follow=1;
           }
        }

        //关注直达
        if ($_GPC['do'] == 'foo' && $_GPC['op'] == 'guanzhu' && $quan['guanzhu_direct'] == 1) {
          $follow = 1;
        }

        if (empty($member)) {
            $data = array('weid' => $weid, 'from_user' => $userinfo['openid'],
                'nickname' => $userinfo['nickname'], 'headimgurl' => $userinfo['headimgurl'],
                'openid' => $userinfo['openid'], 'ori_openid' => $ori_openid, 'quan_id' => $quan_id,
                'rob_next_time' => TIMESTAMP, 'status' => 1,
                'type' => 1, 'credit' => 0, 'fabu' => 0, 'rob' => 0, 'createtime' => TIMESTAMP);

            $data['follow'] = $follow;
            //如果有上线
            $data['inviter_id'] = $_GPC['pid'];
            //是否默认打开消息通知，0不打开，1打开。
            $data['message_notify'] = $quan['message_notify'];

            pdo_insert("cgc_ad_member", $data);
            $member = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_member') .
            " WHERE  weid=" . $weid . " AND quan_id=" . $quan_id . " and openid='{$userinfo['openid']}' order by id asc");
            
            //发送小弟消息
            $_url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('geren', array('quan_id' => $quan['id'], 'op' => 'down')), 2);
            send_xd($data, $quan, $config, $_url);        
        } else {
            if ($member['follow'] != $follow && $follow == 1) {
                $member['follow'] = $follow;
                pdo_update("cgc_ad_member", array("follow" => $follow), array('id' => $member['id']));
            }
        }

        if (empty($member)) {
            $this->returnError('生成会员失败');
            exit();
        }

        if ($member['status'] != 1) {
            $this->returnError('你的账户已被封号，请联系管理员');
        }

        if (!empty($fkz_member['level'])) {
            $member['yun_level'] = $fkz_member['level'];
        }


        $member['location_info'] = 1;
        //地区的处理
        if (!empty($quan['city'])) {
            $quan['city'] = str_replace("市", "", $quan['city']);
            $quan['city'] = str_replace("县", "", $quan['city']);
            $quan['city'] = str_replace("或", "|", $quan['city']);
            $temp_city = 0;
            if (!empty($member['last_city'])) {
                $member['last_city'] = str_replace("市", "", $member['last_city']);
                $member['last_city'] = str_replace("县", "", $member['last_city']);
                $member_city_arr = explode('|', $member['last_city']);
                $city_arr = explode('|', $quan['city']);

                foreach ($member_city_arr as $value) {
                    if (in_array($value, $city_arr)) {
                        $temp_city = 1;
                        break;
                    }
                }
            }
            $member['location_info'] = $temp_city;
        }

        //是否管理人员
        $is_kf = 0;

        $_kf = explode(',', $this->settings['kf_openid']);
        if (!empty($member['openid']) && in_array($member['openid'], $_kf)) {
            $is_kf = 1;
        }

        $_kf = explode(',', $quan['kf_openid']);
        if (!empty($member['openid']) && in_array($member['openid'], $_kf)) {
            $is_kf = 1;
        }

        $member['is_kf'] = $is_kf;

        $fabu_model = $this->fabu_model[$_GPC['do']];
        if (!empty($fabu_model) && !empty($quan['pp_mode'])) {
            $_pp = explode(',', $quan['pp_openid']);
            if (!in_array($member['openid'], $_pp)) {
                $this->returnError('只有指定的人才可以发布信息');
            }
        }


        if (!empty($member['vip_id'])) {
            $cgc_ad_vip_rule = new cgc_ad_vip_rule();
            $rule = $cgc_ad_vip_rule->getOne($member['vip_id']);
            $member['vip'] = $rule;
        }

        if (!empty($fabu_model) && !empty($quan['vip_valid'])) {
            $piece_model = explode(',', $quan['piece_model']);
            $vip_right = false;

            if (in_array($fabu_model['model'], $piece_model)) {
                $vip_right = true;
            }


            $piece_model = explode(',', $member['vip']['piece_model']);
            if (!$vip_right && in_array($fabu_model['model'], $piece_model)) {
                $vip_right = true;
            }

            //普通会员权限
            $piece_model = explode(',', $quan['piece_model_authority']);
            if (!$vip_right && in_array($fabu_model['model'], $piece_model)) {
                $vip_right = true;
            }

            if (!$vip_right) {
                $url = $this->createMobileUrl("vip_recharge", array('quan_id' => $quan['id']));
                $this->returnError('vip权限不足，无法发布' . $fabu_model['title'] . ",点击确定充值vip.", $url);
            }
        }


        if ($quan_id != $member['quan_id']) {
            $this->returnError('用户非法访问');
        }

        return $member;
    }

    public function doMobileAddtest()
    {
        global $_W, $_GPC;

        $weid = $_W['uniacid'];

        $latitude = $_GPC['latitude'];

        $longitude = $_GPC['longitude'];

        $op = $_GPC['op'];
        if ($op == 'add') {
            if (empty($latitude) || empty($longitude)) {
                $this->returnError('位置获取失败');
            }

            // 百度反地址查询接口

            $config = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_setting') . " WHERE weid=" . $weid);

            $url = "http://api.map.baidu.com/geocoder/v2/?ak=" . $config['bd_ak'] . "&location=" . $latitude . "," . $longitude . "&output=json&pois=0";


            load()->func('communication');

            $response = ihttp_get($url);

            if (!is_error($response)) {

                //$data = $response//@json_decode($response, true);

                $data = @json_decode($response['content'], true);


                if (empty($data) || $data['status'] != 0) {

                    $this->returnError('位置获取失败：' . $data['message'] . '(' . $data['status'] . ')');

                } else {

                    $data = $data['result'];

                    // 把地址格式化操作放在服务器端，以便后期更换接口服务方

                    $address = '';

                    // 地址

                    if (!empty($data['addressComponent'])) {

                        $address = $data['addressComponent'];

                    }

                    if (empty($address['city'])) {

                        $this->returnError('城市获取失败');

                    } else {
                        foreach ($address as $key => $value) {
                            if ($key == 'province') {
                                $province = "省份:" . $value . ";";
                            }
                            if ($key == 'city') {
                                $city = "城市:" . $value . ";";
                                $city = str_replace("市", "", $city);
                            }
                            if ($key == 'district') {
                                $district = "区/县:" . $value . ";";
                            }
                        }
                    }
                    $address = $province . $city . $district;

                    // 去掉市字

                    //$city=str_replace("市", "", $city);

                    //$city='南京';


                    $this->returnSuccess('城市定位成功', $address);

                }

            } else {

                $this->returnError('位置获取失败，请重试');

            }
        }

        include $this->template('addtest');
    }


    public function doMobileQr()
    {

        global $_GPC;
        $raw = @base64_decode($_GPC['raw']);
        ob_clean();//这个一定要加上，清除缓冲区
        if (!empty($raw)) {

            include MB_ROOT . '/source/common/phpqrcode.php';

            QRcode::png($raw, false, QR_ECLEVEL_Q, 4);

        }

    }


    public function doWebQrTest()
    {


        $file = $this->gen_qr("http://baidu.com");
        exit($file);
    }


    public function doWebQr()

    {

        global $_GPC;

        $raw = @base64_decode($_GPC['raw']);

        if (!empty($raw)) {

            include MB_ROOT . '/source/common/phpqrcode.php';

            QRcode::png($raw, false, QR_ECLEVEL_Q, 4);

        }

    }


    public function _qxye($mid, $quan_id, $jine)
    {
        global $_W;
        $sql = "SELECT SUM(fee) FROM " . tablename('cgc_ad_adv') . " where weid=" . $_W['uniacid'] . " and quan_id=" . $quan_id . " and mid=" . $mid;
        $_jy = $jine - pdo_fetchcolumn($sql);
        return number_format($_jy, 2);
    }


    protected function transferByRedpack($transfer)
    {
        global $_W;

        $weid = $_W['uniacid'];

        $api = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_setting') . " WHERE weid=" . $weid);

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

        load()->func('communication');

        $pars = array();

        $pars['nonce_str'] = random(32);

        $pars['mch_billno'] = $api['mchid'] . date('Ymd') . $transfer['id'];

        $pars['mch_id'] = $api['mchid'];

        $pars['wxappid'] = $api['appid'];

        $pars['nick_name'] = $transfer['nick_name'];

        $pars['send_name'] = ($transfer['send_name']);

        $pars['re_openid'] = $transfer['openid'];

        $pars['total_amount'] = $transfer['money'];

        $pars['min_value'] = $transfer['money'];

        $pars['max_value'] = $transfer['money'];

        $pars['total_num'] = 1;

        $pars['wishing'] = $transfer['wishing'];

        $pars['client_ip'] = $api['ip'];

        $pars['act_name'] = $transfer['act_name'];

        $pars['remark'] = $transfer['remark'];

        ksort($pars, SORT_STRING);

        $string1 = '';

        foreach ($pars as $k => $v) {

            $string1 .= "{$k}={$v}&";

        }

        $string1 .= "key={$api['password']}";

        $pars['sign'] = strtoupper(md5($string1));

        $xml = array2xml($pars);

        $extras = array();

        $md5 = md5("{$_W['uniacid']}{$_W['config']['setting']['authkey']}");
        $end = $weid;
        if (file_exists(IA_ROOT . '/cert/rootca.pem.' . $md5)) {
            $end = $md5;
        }

        $extras['CURLOPT_CAINFO'] = IA_ROOT . '/cert/rootca.pem.' . $end;

        $extras['CURLOPT_SSLCERT'] = IA_ROOT . '/cert/apiclient_cert.pem.' . $end;

        $extras['CURLOPT_SSLKEY'] = IA_ROOT . '/cert/apiclient_key.pem.' . $end;

        $procResult = null;

        $resp = ihttp_request($url, $xml, $extras);

        if (is_error($resp)) {

            return $resp;

        } else {

            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];

            $dom = new DOMDocument();

            if ($dom->loadXML($xml)) {

                $xpath = new DOMXPath($dom);

                $code = $xpath->evaluate('string(//xml/return_code)');

                $ret = $xpath->evaluate('string(//xml/result_code)');

                if (strtolower($code) == 'success' && strtolower($ret) == 'success') {

                    $mch_billno = $xpath->evaluate('string(//xml/mch_billno)');

                    $out_billno = $xpath->evaluate('string(//xml/send_listid)');

                    $out_money = $xpath->evaluate('string(//xml/total_amount)');


                    $procResult = array('mch_billno' => $mch_billno, 'out_billno' => $out_billno, 'out_money' => $out_money, 'tag' => iserializer($resp));

                } else {

                    $error = $xpath->evaluate('string(//xml/err_code_des)');

                    $procResult = error(-2, $error);

                }

            } else {

                $procResult = error(-1, 'error response');

            }

        }

        return $procResult;

    }

    protected function payz($params = array(), $mine = array())
    {

        global $_W;

        //微信收银台支付
        if ($params['pay_type'] == 2) {
            $pay_params['WIDout_trade_no'] = $params['tid'];
            return $pay_params;
        }

        $params['module'] = $this->module['name'];
        $sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':module'] = $params['module'];
        $pars[':tid'] = $params['tid'];
        $log = pdo_fetch($sql, $pars);
        if (!empty($log) && $log['status'] == '1') {
            $this->returnError('这个订单已经支付成功, 不需要重复支付.');
        }


        if (empty($log)) {
            $log = array(
                'uniacid' => $_W['uniacid'],
                'acid' => $_W['acid'],
                'openid' => $params['openid'],
                'module' => $this->module['name'], //模块名称，请保证$this可用
                'tid' => $params['tid'],
                'fee' => $params['fee'],
                'card_fee' => $params['fee'],
                'status' => '0',
                'is_usecard' => '0',
            );
            pdo_insert('core_paylog', $log);
        }

        //云支付
        if ($params['pay_type'] == 1) {
            $pay_params['WIDout_trade_no'] = $params['tid'];
            $pay_params['WIDsubject'] = $params['title'];
            $pay_params['WIDtotal_fee'] = $params['fee'];
            $pay_params['WIDbody'] = $params['title'];
            $pay_params['WIDno_url'] = $_W['siteroot'] . 'addons/' . $this->modulename . '/yunpay/no_url.php';
            $pay_params['WIDre_url'] = $_W['siteroot'] . 'addons/' . $this->modulename . '/yunpay/re_url.php';
            $pay_params['uniacid'] = $_W['uniacid'];
            return $pay_params;
        }


        return $params;

    }


    public function payResult($params)
    {
        global $_W;
        $config = $this->settings;
        $sys_config = $this->module['config'];

        if ($params['type'] == 'credit' && $config['pay_type'] != 2) {
            message('error!');
        }

        $siteroot = getSiteRoot($_W['siteroot']) . "/";
        if ($params['type'] != 'yun_pay') {
            $siteroot = $_W['siteroot'];
        }

        if ($params['type'] == 'wechat') {
            $wechat_sn = $params['tag']['transaction_id'];
        }
        //vip充值
        if (strpos($params['tid'], 'vip') === 0 && $params['result'] == 'success') {
            $id = substr($params['tid'], 13);
            $vip_pay = pdo_fetch("select * from " . tablename('cgc_ad_vip_pay') . " where id=:id ", array(':id' => $id));

            $vip_rule = pdo_fetch('SELECT * FROM ' . tablename('cgc_ad_vip_rule') . ' WHERE `weid`=:uniacid AND `id`=:id AND `quan_id`=:quan_id',
                array(':uniacid' => $vip_pay['weid'],
                    ':id' => $vip_pay['vip_id'],
                    ':quan_id' => $vip_pay['quan_id']));

            if ($params['result'] == 'success' && ($params['from'] == 'notify' || $params['type'] == 'credit') && empty($vip_pay['status'])) {

                if ($params['fee'] != $vip_pay['vip_recharge']) {
                    message('非法操作！充值vip失败!');
                }
                pdo_update('cgc_ad_vip_pay', array('pay' => $params['fee'], 'status' => 1, 'wechat_sn' => $wechat_sn), array('id' => $id));

                pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET `vip_id`=:vip_id,`vip_name`=:vip_name,`vip_recharge`=:vip_recharge,`vip_rob`=:vip_rob where `weid`=:uniacid and `quan_id`=:quan_id and `id`=:mid',
                    array(':uniacid' => $vip_pay['weid'],
                        ':quan_id' => $vip_pay['quan_id'],
                        ':mid' => $vip_pay['mid'],
                        ':vip_id' => $vip_rule['id'],
                        ':vip_name' => $vip_rule['vip_name'],
                        ':vip_recharge' => $vip_rule['vip_recharge'],
                        ':vip_rob' => $vip_rule['vip_rob']));

                //vip自动洒出红包
                if (!empty($vip_rule['is_spill'])) {
                    $member = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_member') .
                        " WHERE weid=" . $vip_pay['weid'] . " AND quan_id=" . $vip_pay['quan_id'] . " and id=" . $vip_pay['mid']);
                    $content = $vip_rule['spill_prompt'];
                    $content = str_replace("#username#", $member['nickname'], $content);
                    $content = str_replace("#vipfee#", $vip_rule['spill_fee'], $content);
                    $content = str_replace("#vipname#", $vip_rule['vip_name'], $content);
                    $params1 = array();
                    $params1['weid'] = $vip_pay['weid'];
                    $params1['quan_id'] = $vip_pay['quan_id'];
                    $params1['mid'] = $vip_pay['mid'];
                    $params1['nickname'] = $member['nickname'];
                    $params1['headimgurl'] = $member['headimgurl'];
                    $params1['title'] = $content;
                    $params1['content'] = $content;
                    $params1['total_num'] = $vip_rule['spill_number'];
                    $params1['total_amount'] = $vip_rule['spill_fee'];
                    $params1['fabu'] = $member['fabu'];
                    $params1['openid'] = $member['openid'];
                    $this->auto_create_adv($params1);
                }
            }


            if ($params['from'] == 'return' || ($params['sync'] == true && $params['type'] == 'yun_pay')) {


                if ($params['result'] == 'success') {
                    $url = $siteroot . 'app/' . substr($this->createMobileUrl('success', array('quan_id' => $vip_pay['quan_id'], 'op' => 'vip_recharge')), 2);
                } else {
                    $url = $siteroot . 'app/' . substr($this->createMobileUrl('error', array('quan_id' => $vip_pay['quan_id'], 'op' => 'vip_recharge')), 2);
                }
                header("location:" . $url);
                exit();
            }
        } else {


            $id = substr($params['tid'], 10);
            $piece = pdo_fetch("select * from " . tablename('cgc_ad_adv') . " where id=:id ", array(':id' => $id));
            $quan = pdo_fetch("select * from " . tablename('cgc_ad_quan') . " where id=:id ", array(':id' => $piece['quan_id']));

            if ($params['result'] == 'success' && ($params['from'] == 'notify' || $config['pay_type'] == 2) && empty($piece['status'])) {
                $rob_start_time = time() + $piece['hot_time'];
                if ($params['fee'] != $piece['total_pay']) {
                    message('非法操作！发布失败!');
                }
                $status = !empty($quan['shenhe']) ? "3" : "1";
                $data = array('wechat_sn' => $wechat_sn, 'publish_time' => time(), 'rob_start_time' => $rob_start_time, 'pay' => $params['fee'], 'status' => $status);
                $ret = pdo_update('cgc_ad_adv', $data, array('id' => $id));
                if (empty($ret)) {

                }
                pdo_query('UPDATE ' . tablename('cgc_ad_member') . ' SET fabu=fabu+:pay where weid=:uniacid and quan_id=:quan_id and id=:mid', array(':uniacid' => $piece['weid'], ':quan_id' => $piece['quan_id'], ':mid' => $piece['mid'], ':pay' => $params['fee']));
            }

            if ($params['from'] == 'return' || ($params['sync'] == true && $params['type'] == 'yun_pay')) {


                if ($params['result'] == 'success') {
                    $weid = $_W['uniacid'];
                    //云支付的时候，居然出错，所以这里直接屏蔽
                    if (empty($sys_config['pay_after_msg']) && !empty($config['tuisong']) && $params['type'] != 'yun_pay' && empty($quan['shenhe']) && empty($piece['message_send'])) {
                        pdo_update('cgc_ad_adv', array("message_send" => 1), array('id' => $piece['id']));
                        $_userlist = pdo_fetchall('SELECT openid FROM ' . tablename('cgc_ad_member') . " where weid=" . $weid . " and quan_id={$piece['quan_id']} and type=1 and message_notify =1 and status=1 limit 1000");

                        $_url = $siteroot . 'app/' . substr($this->createMobileUrl('foo', array('form' => 'detail', 'quan_id' => $piece['quan_id'], 'id' => $piece['id'], 'model' => $piece['model'])), 2);
                        $htt = str_replace('"', "'", htmlspecialchars_decode($config['tuisong']));
                        //红包通知信息更换 by 20170212
                        $msg = str_replace('{num}', $piece['total_num'], $config['tuisong']);
                        $msg = str_replace('{money}', $piece['total_amount'], $msg);
                        $msg = str_replace('{type}', $this->get_modelName($piece['model']), $msg);
                        $_tdata = array(
                            'first' => array('value' => '系统通知', 'color' => '#173177'),
                            'keyword1' => array('value' => $msg, 'color' => '#173177'),
                            'keyword2' => array('value' => date('Y-m-d H:i:s', time()), 'color' => '#173177'),
                            'keyword3' => array('value' => '抢钱通知', 'color' => '#173177'),
                            'remark' => array('value' => '点击详情进入。如果你不想接受此类消息，可以在个人中心选择关闭。', 'color' => '#173177'),
                        );

                        foreach ($_userlist as $key => $r) {
                            if ($config['is_type'] == 1) {
                                $a = sendTemplate_common($r['openid'], $config['template_id'], $_url, $_tdata);
                            } else {
                                $a = post_send_text($r['openid'], $htt);
                            }
                        }
                    }

                    $_isner = pdo_insert('cgc_ad_paylog', array('weid' => $weid, 'mid' => $piece['mid'], 'quan_id' => $piece['quan_id'], 'advid' => $id, 'total_amount' => $piece['total_amount'], 'upbdate' => time()));

                    //撒红包提成
                    $member = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_member') .
                        " WHERE weid=" . $piece['weid'] . " AND quan_id=" . $piece['quan_id'] . " and id=" . $piece['mid']);
                    send_red_commission($member, $quan, $piece, $config);

                    //是否管理人员
                    $is_kf = 0;

                    $_kf = explode(',', $this->settings['kf_openid']);
                    if (!empty($piece['openid']) && in_array($piece['openid'], $_kf)) {
                        $is_kf = 1;
                    }

                    $_kf = explode(',', $quan['kf_openid']);
                    if (!empty($piece['openid']) && in_array($piece['openid'], $_kf)) {
                        $is_kf = 1;
                    }

                    if (!empty($is_kf)) {//管理员通过审核
                        pdo_update('cgc_ad_adv', array("status" => 1), array('id' => $piece['id']));
                    }


                    $op = empty($quan['shenhe']) ? "pay" : "shenhe";
                    if (!empty($quan['shenhe']) && empty($piece['check_message_send'])) {
                        pdo_update('cgc_ad_adv', array("check_message_send" => 1), array('id' => $piece['id']));
                        $this->check_msg($config, $quan, $piece);
                    }
                    $url = $siteroot . 'app/' . substr($this->createMobileUrl('success', array('quan_id' => $piece['quan_id'], 'model' => $piece['model'], 'id' => $piece['id'], 'op' => $op)), 2);
                } else {
                    $url = $siteroot . 'app/' . substr($this->createMobileUrl('error', array('quan_id' => $piece['quan_id'], 'op' => 'pay')), 2);
                }

                header("location:" . $url);
                exit();
            }
        }
    }

    public function check_msg($config, $quan, $adv)
    {
        global $_W;
        $_url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('adv_check', array('quan_id' => $quan['id'], 'id' => $adv['id'])), 2);
        $htt = "广告需要审核.点击查看:";
        $_tdata = array(
            'first' => array('value' => '系统通知', 'color' => '#173177'),
            'keyword1' => array('value' => $htt, 'color' => '#173177'),
            'keyword2' => array('value' => date('Y-m-d H:i:s', time()), 'color' => '#173177'),
            'keyword3' => array('value' => '通知', 'color' => '#173177'),
            'remark' => array('value' => '点击详情进入', 'color' => '#173177'));
        $_userlist = explode(",", $quan['kf_openid']);
        foreach ($_userlist as $r) {
            if ($config['is_type'] == 1) {
                $a = sendTemplate_common($r, $config['template_id'], $_url, $_tdata);
            } else {
                $a = post_send_text($r, $htt . $_url);
            }

        }

    }


    protected function text_len($text)
    {
        preg_match_all("/./us", $text, $match);
        return count($match[0]);

    }

    protected function time_to_text($s)

    {

        $t = "";


        if (86400 < $s) {

            $t .= intval($s / 86400) . "天";

            $s = $s % 86400;

        }


        if (3600 < $s) {
            $t .= intval($s / 3600) . "小时";
            $s = $s % 3600;
        }


        if (60 < $s) {

            $t .= intval($s / 60) . "分钟";

            $s = $s % 60;

        }


        if (0 < $s) {

            $t .= intval($s) . "秒";

        }


        return $t;

    }

    protected function VP_IMAGE_SAVE($path, $dir = "")

    {

        global $_W, $_GPC;

        require_once '../addons/cgc_ad/libs/vendor/autoload.php';

        $filePath = ATTACHMENT_ROOT . $path;

        $key = $path;

        $weid = $_W['uniacid'];

        $config = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_setting') . " WHERE weid=" . $weid);

        $accessKey = $config["qn_ak"];

        $secretKey = $config["qn_sk"];

        $auth = new Qiniu\Auth($accessKey, $secretKey);

        $bucket = (empty($dir) ? $config["qn_bucket"] : $dir);


        $token = $auth->uploadToken($bucket);

        $uploadMgr = new Qiniu\Storage\UploadManager();

        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

        return array("error" => $err, "image" => empty($ret) ? "" : "http://" . $config['qn_api'] . "/" . $ret["key"]);

    }


    protected function VP_IMAGE_SAVE1($path, $dir = "")
    {

        global $_W, $_GPC;
        $zz = $this->dataTransfer($path);
        return $zz;
        require_once '../addons/cgc_ad/libs/vendor/autoload.php';

        $filePath = ATTACHMENT_ROOT . $path;

        $key = $path;

        $weid = $_W['uniacid'];

        $config = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_setting') . " WHERE weid=" . $weid);

        $accessKey = $config["qn_ak"];

        $secretKey = $config["qn_sk"];

        $auth = new Qiniu\Auth($accessKey, $secretKey);

        $bucket = (empty($dir) ? $config["qn_bucket"] : $dir);
        $key = "3.mp3";
        $policy = array(
            'persistentOps' => 'avthumb/mp3/ab/128k|saveas/' . base64_url($bucket . ':' . $key),
            //  'persistentOps' => 'avthumb/mp3/ab/64k',
            'pipeline' => 'pipeline'
        );

        $token = $auth->uploadToken($bucket, null, 3600, $policy);

        $uploadMgr = new Qiniu\Storage\UploadManager();

        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

        return array("error" => $err, "image" => empty($ret) ? "" : "http://" . $config['qn_api'] . "/" . $ret["key"]);

    }

    public function dataTransfer($path)
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];

        $config = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_setting') . " WHERE weid=" . $weid);

        $access_key = $config["qn_ak"];

        $secret_key = $config["qn_sk"];
        $pipeline = 'pipeline';
        $bucket = (empty($dir) ? $config["qn_bucket"] : $dir);
        $result2 = ATTACHMENT_ROOT . $path;
        $key = "4.mp3";
        $entry = '' . $bucket . ':4.mp3';
        $entry2 = base64_url($entry);
        $data = 'bucket=' . $bucket . '&key=' . $key . '&fops=' . urlencode('avthumb/mp3/ab/128k/ar/44100/acodec/libmp3lame|saveas/' . $entry2 . '') . '&pipeline=' . $pipeline;
        $sign = hash_hmac('sha1', "/pfop/\n" . $data, $secret_key, true);
        $token = $access_key . ':' . base64_url($sign);
        $header = array('Content-Type:application/x-www-form-urlencoded', 'Authorization: QBox ' . $token);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://api.qiniu.com/pfop/');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);
        //$result = $result2;
        print_r($result);
        return $result;
    }


    protected function VP_IMAGE_URL($path, $style = "m", $dir = "", $driver = "")

    {

        global $_W;


        if ("local" == $driver) {

            return $_W["attachurl"] . $path;

        } else {

            $weid = $_W['uniacid'];

            $config = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_setting') . " WHERE weid=" . $weid);

            return "http://" . $config["qn_api"] . "/" . $path;

        }

    }


    protected function VP_AVATAR($src, $size = "s")

    {

        if (empty($src) || empty($size)) {

            return $src;

        } else {

            $parts = parse_url($src);

            if ($parts["host"] == "wx.qlogo.cn") {

                if ($size == "s") {

                    $size = "64";

                } else if ($size == "m") {

                    $size = "132";

                }


                $src = substr($src, 0, strrpos($src, "/")) . "/" . $size;

            } else {

                $src = tomedia($src);

            }


            return $src;

        }

    }


    protected function VP_THUMB($src, $size = 120)

    {

        $ppos = strrpos($src, ".");

        return substr($src, 0, $ppos) . "_" . $size . substr($src, $ppos);

    }

    protected function red_plan($total, $num, $min)

    {

        $total = $total * 100;
        $min = $min * 100;
        $average = $total / $num;//平均金额
        $max = $average * 5;//用户最大可获得平均金额的倍率
        $base_total = $num * $min;//保底分配金额
        $over_total = $total - $base_total;//随机金额


        if ($total * 0.01 == $num) {//红包金额等于红包数量时，直接发放等额红包
            for ($i = 0; $i <= $num; $i++)
                $packs[$i] = $average;
        } else {

            for ($i = 1; $i <= $num; $i++)//第一轮循环，分配小额
            {
                $temp = intval(rand(0, $average - $min));//每人递增  随机 0---平均值-最小额
                $over_total -= $temp;
                $packs[$i] = $temp + $min;
            }

            while ($over_total > 0) {//第二轮，分配剩余金额，出现大额红包
                for ($i = 1; $i <= $num; $i++) {
                    $temp = intval(rand(0, $max - $average - $min));
                    if ($temp > $over_total) $temp = $over_total;
                    $packs[$i] += $temp;
                    $over_total -= $temp;
                    if ($over_total == 0) break;
                }
            }
        }
        shuffle($packs);
        $packs[$num] = $packs[0];

        return $packs;
    }

    //平均分配金额
    protected function red_average_plan($total, $num)
    {
        $total = $total * 100;
        $average = $total / $num;//平均金额

        for ($i = 0; $i <= $num; $i++)
            $packs[$i] = intval($average);

        return ($packs);
    }

    protected function returnError($message, $data = '', $status = 0, $type = '')

    {

        global $_W;

        if ($_W['isajax'] || $type == 'ajax') {

            header('Content-Type:application/json; charset=utf-8');

            $ret = array('status' => $status, 'info' => $message, 'data' => $data);

            exit(json_encode($ret));

        } else {

            return $this->returnMessage($message, $data, 'error');

        }

    }

    protected function returnSuccess($message, $data = '', $status = 1, $type = '')

    {

        global $_W;

        if ($_W['isajax'] || $type == 'ajax') {

            header('Content-Type:application/json; charset=utf-8');

            $ret = array('status' => $status, 'info' => $message, 'data' => $data);

            exit(json_encode($ret));

        } else {

            return $this->returnMessage($message, $data, 'success');

        }

    }

    protected function returnMessage($msg, $redirect = '', $type = '')

    {

        global $_W, $_GPC;

        if ($redirect == 'refresh') {

            $redirect = $_W['script_name'] . '?' . $_SERVER['QUERY_STRING'];

        }

        if ($redirect == 'referer') {

            $redirect = referer();

        }

        if ($redirect == '') {

            $type = in_array($type, array('success', 'error', 'info', 'warn')) ? $type : 'info';

        } else {

            $type = in_array($type, array('success', 'error', 'info', 'warn')) ? $type : 'success';

        }

        if (empty($msg) && !empty($redirect)) {
            header('location: ' . $redirect);
            exit();

        }

        $label = $type;

        if ($type == 'error') {
            $label = 'warn';
        }
        include $this->template('error');
        exit();
    }

    public function get_cardauth($quan, $adv, $member)
    {
        global $_W, $_GPC;
        $quan_id = $quan['id'];
        $id = $adv['id'];
        $weid = $_W['acid'];
        $mid = $member['id'];
        $my = pdo_fetch("SELECT * FROM " . tablename('cgc_ad_red') . " WHERE weid=" . $weid . " AND quan_id=" . $quan_id . " AND advid=" . $id . " AND mid=" . $mid);
        if (empty($my)) {
            $this->returnError("条件不满足", "referer");
        }
        return true;
    }


    public function auto_create_adv($params)
    {

        $plan = $this->red_average_plan($params['total_amount'], $params['total_num']);
        $plan = implode(',', $plan);
        $data = array(
            'weid' => $params['weid'],
            'quan_id' => $params['quan_id'],
            'mid' => $params['mid'],
            'openid' => $params['openid'],
            'title' => $params['title'],
            'content' => $params['content'],
            'total_num' => $params['total_num'],
            'total_amount' => $params['total_amount'],
            'fee' => 0,//
            'total_pay' => 0,//应付金额
            'pay' => 0,//实付总额
            'model' => 1,
            'nickname' => $params['nickname'],
            'headimgurl' => $params['headimgurl'],
            'type' => 1,//1为微信,2为机器人用户,3（官方）,4（商家）
            'allocation_way' => 1,//分钱方式：0随机分配，1平均分配
            'rob_plan' => $plan,
            'status' => 1,//生效状态
            'fl_type' => 0,//红包
            'links' => 0,
            'rob_amount' => 0,
            'rob_users' => 0,
            'publish_time' => time(),
            'create_time' => time(),
        );

        pdo_insert('cgc_ad_adv', $data);
        $id = pdo_insertid();
        pdo_update('cgc_ad_member', array(
            'fabu' => $params['fabu'] + $params['total_amount']
        ), array(
            'id' => $params['mid']
        ));
    }


    protected function payP($params = array(), $mine = array())
    {
        global $_W;
        if (!$this->inMobile) {
            message('支付功能只能在手机上使用');
        }
        $params['module'] = $this->module['name'];
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':module'] = $params['module'];
        $pars[':tid'] = $params['tid'];
        if ($params['fee'] <= 0) {
            $pars['from'] = 'return';
            $pars['result'] = 'success';
            $pars['type'] = '';
            $pars['tid'] = $params['tid'];
            $site = WeUtility::createModuleSite($pars[':module']);
            $method = 'payResult';
            if (method_exists($site, $method)) {
                exit($site->$method($pars));
            }
        }

        $sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
        $log = pdo_fetch($sql, $pars);
        if (!empty($log) && $log['status'] == '1') {
            message('这个订单已经支付成功, 不需要重复支付.');
        }
        $setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
        if (!is_array($setting['payment'])) {
            message('没有有效的支付方式, 请联系网站管理员.');
        }
        $pay = $setting['payment'];
        if (empty($_W['member']['uid'])) {
            $pay['credit']['switch'] = false;
        }
        if (!empty($pay['credit']['switch'])) {
            $credtis = mc_credit_fetch($_W['member']['uid']);
        }
        $you = 0;
        if ($pay['card']['switch'] == 2 && !empty($_W['openid'])) {
            if ($_W['card_permission'] == 1 && !empty($params['module'])) {
                $cards = pdo_fetchall('SELECT a.id,a.card_id,a.cid,b.type,b.title,b.extra,b.is_display,b.status,b.date_info FROM ' . tablename('coupon_modules') . ' AS a LEFT JOIN ' . tablename('coupon') . ' AS b ON a.cid = b.id WHERE a.acid = :acid AND a.module = :modu AND b.is_display = 1 AND b.status = 3 ORDER BY a.id DESC', array(':acid' => $_W['acid'], ':modu' => $params['module']));
                $flag = 0;
                if (!empty($cards)) {
                    foreach ($cards as $temp) {
                        $temp['date_info'] = iunserializer($temp['date_info']);
                        if ($temp['date_info']['time_type'] == 1) {
                            $starttime = strtotime($temp['date_info']['time_limit_start']);
                            $endtime = strtotime($temp['date_info']['time_limit_end']);
                            if (TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
                                continue;
                            } else {
                                $param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
                                $num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
                                if ($num <= 0) {
                                    continue;
                                } else {
                                    $flag = 1;
                                    $card = $temp;
                                    break;
                                }
                            }
                        } else {
                            $deadline = intval($temp['date_info']['deadline']);
                            $limit = intval($temp['date_info']['limit']);
                            $param = array(':acid' => $_W['acid'], ':openid' => $_W['openid'], ':card_id' => $temp['card_id']);
                            $record = pdo_fetchall('SELECT addtime,id,code FROM ' . tablename('coupon_record') . ' WHERE acid = :acid AND openid = :openid AND card_id = :card_id AND status = 1', $param);
                            if (!empty($record)) {
                                foreach ($record as $li) {
                                    $time = strtotime(date('Y-m-d', $li['addtime']));
                                    $starttime = $time + $deadline * 86400;
                                    $endtime = $time + $deadline * 86400 + $limit * 86400;
                                    if (TIMESTAMP < $starttime || TIMESTAMP > $endtime) {
                                        continue;
                                    } else {
                                        $flag = 1;
                                        $card = $temp;
                                        break;
                                    }
                                }
                            }
                            if ($flag) {
                                break;
                            }
                        }
                    }
                }
                if ($flag) {
                    if ($card['type'] == 'discount') {
                        $you = 1;
                        $card['fee'] = sprintf("%.2f", ($params['fee'] * ($card['extra'] / 100)));
                    } elseif ($card['type'] == 'cash') {
                        $cash = iunserializer($card['extra']);
                        if ($params['fee'] >= $cash['least_cost']) {
                            $you = 1;
                            $card['fee'] = sprintf("%.2f", ($params['fee'] - $cash['reduce_cost']));
                        }
                    }
                    load()->classs('coupon');
                    $acc = new coupon($_W['acid']);
                    $card_id = $card['card_id'];
                    $time = TIMESTAMP;
                    $randstr = random(8);
                    $sign = array($card_id, $time, $randstr, $acc->account['key']);
                    $signature = $acc->SignatureCard($sign);
                    if (is_error($signature)) {
                        $you = 0;
                    }
                }
            }
        }

        if ($pay['card']['switch'] == 3 && $_W['member']['uid']) {
            $cards = array();
            if (!empty($params['module'])) {
                $cards = pdo_fetchall('SELECT a.id,a.couponid,b.type,b.title,b.discount,b.condition,b.starttime,b.endtime FROM ' . tablename('activity_coupon_modules') . ' AS a LEFT JOIN ' . tablename('activity_coupon') . ' AS b ON a.couponid = b.couponid WHERE a.uniacid = :uniacid AND a.module = :modu AND b.condition <= :condition AND b.starttime <= :time AND b.endtime >= :time  ORDER BY a.id DESC', array(':uniacid' => $_W['uniacid'], ':modu' => $params['module'], ':time' => TIMESTAMP, ':condition' => $params['fee']), 'couponid');
                if (!empty($cards)) {
                    foreach ($cards as $key => &$card) {
                        $has = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . ' WHERE uid = :uid AND uniacid = :aid AND couponid = :cid AND status = 1' . $condition, array(':uid' => $_W['member']['uid'], ':aid' => $_W['uniacid'], ':cid' => $card['couponid']));
                        if ($has > 0) {
                            if ($card['type'] == '1') {
                                $card['fee'] = sprintf("%.2f", ($params['fee'] * $card['discount']));
                                $card['discount_cn'] = sprintf("%.2f", $params['fee'] * (1 - $card['discount']));
                            } elseif ($card['type'] == '2') {
                                $card['fee'] = sprintf("%.2f", ($params['fee'] - $card['discount']));
                                $card['discount_cn'] = $card['discount'];
                            }
                        } else {
                            unset($cards[$key]);
                        }
                    }
                }
            }
            if (!empty($cards)) {
                $cards_str = json_encode($cards);
            }
        }
        include $this->template('common/paycenter');
    }


    protected function createMobileUrl($do, $query = array(), $noredirect = true)
    {
        global $_W;
        $query['do'] = $do;
        $query['m'] = strtolower($this->modulename);
        $query['random'] = time();

        /*	if (strexists($do,"fabu")){
              $unisetting = uni_setting($_W['uniacid']);
              if (!strexists($_W['siteroot'],$unisetting['oauth']['host'])){
                $url = (!empty($unisetting['oauth']['host']) ? ($unisetting['oauth']['host'] . '/') : $_W['siteroot'] . 'app/') . "index.php?i={$_W['uniacid']}{$str}";
                header($unisetting['oauth']['host']."");
                exit();
              }
            }
    */

        return parent::createMobileUrl($do, $query, $noredirect);
        //return murl('entry', $query, $noredirect);
    }

    public function adv_pagination($total, $pageIndex, $pageSize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => ''))
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

        $pdata['tcount'] = $total;
        $pdata['tpage'] = ceil($total / $pageSize);
        if ($pdata['tpage'] <= 1) {
            return '';
        }
        $cindex = $pageIndex;
        $cindex = min($cindex, $pdata['tpage']);
        $cindex = max($cindex, 1);
        $pdata['cindex'] = $cindex;
        $pdata['findex'] = 1;
        $pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
        $pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
        $pdata['lindex'] = $pdata['tpage'];

        if ($context['isajax']) {
            if (!$url) {
                $url = $_W['script_name'] . '?' . http_build_query($_GET);
            }
            $pdata['faa'] = 'href="javascript:;" page="' . $pdata['findex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', this);return false;"' : '');
            $pdata['paa'] = 'href="javascript:;" page="' . $pdata['pindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', this);return false;"' : '');
            $pdata['naa'] = 'href="javascript:;" page="' . $pdata['nindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', this);return false;"' : '');
            $pdata['laa'] = 'href="javascript:;" page="' . $pdata['lindex'] . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', this);return false;"' : '');
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

        $html = '<div><ul class="pagination pagination-centered">';
        if ($pdata['cindex'] > 1) {
            $html .= "<li><a {$pdata['paa']} class=\"pager-nav\">&laquo;上一页</a></li>";
        }
        if (!$context['before'] && $context['before'] != 0) {
            $context['before'] = 5;
        }
        if (!$context['after'] && $context['after'] != 0) {
            $context['after'] = 4;
        }

        if ($context['after'] != 0 && $context['before'] != 0) {
            $range = array();
            $range['start'] = max(1, $pdata['cindex'] - $context['before']);
            $range['end'] = min($pdata['tpage'], $pdata['cindex'] + $context['after']);
            if ($range['end'] - $range['start'] < $context['before'] + $context['after']) {
                $range['end'] = min($pdata['tpage'], $range['start'] + $context['before'] + $context['after']);
                $range['start'] = max(1, $range['end'] - $context['before'] - $context['after']);
            }
            for ($i = $range['start']; $i <= $range['end']; $i++) {
                if ($context['isajax']) {
                    $aa = 'href="javascript:;" page="' . $i . '" ' . ($callbackfunc ? 'onclick="' . $callbackfunc . '(\'' . $_W['script_name'] . $url . '\', \'' . $i . '\', this);return false;"' : '');
                } else {
                    if ($url) {
                        $aa = 'href="?' . str_replace('*', $i, $url) . '"';
                    } else {
                        $_GET['page'] = $i;
                        $aa = 'href="?' . http_build_query($_GET) . '"';
                    }
                }
                $html .= ($i == $pdata['cindex'] ? '<li class="active"><a href="javascript:;">' . $i . '</a></li>' : "<li><a {$aa}>" . $i . '</a></li>');
            }
        }

        if ($pdata['cindex'] < $pdata['tpage']) {
            $html .= "<li><a {$pdata['naa']} class=\"pager-nav\">下一页&raquo;</a></li>";
        }
        $html .= '</ul></div>';
        return $html;
    }


}
