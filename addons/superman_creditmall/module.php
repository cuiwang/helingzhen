<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/superman_creditmall/common.func.php';
require IA_ROOT . '/addons/superman_creditmall/model.func.php';
class Superman_CreditmallModule extends WeModule
{
    private $_data = array();
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        load()->func('tpl');
        load()->func('file');
        if (checksubmit('reset_submit')) {
            $data = superman_setting_data();
            $this->saveSettings($data);
            message('更新成功！', referer(), 'success');
        }
        $credits = superman_credit_type();
        if (!$this->module['config']['_init']) {
            $setting  = uni_setting($_W['uniacid'], array(
                'payment'
            ));
            $pay      = $setting['payment'];
            $accs     = uni_accounts();
            $accounts = array();
            if (!empty($accs)) {
                foreach ($accs as $acc) {
                    if ($acc['type'] == '1' && $acc['level'] >= '3') {
                        $accounts[$acc['acid']] = array_elements(array(
                            'name',
                            'acid',
                            'key',
                            'secret'
                        ), $acc);
                    }
                }
                if ($pay && isset($pay['wechat']['account'])) {
                    $pay['wechat']['account_setting'] = $accounts[$pay['wechat']['account']];
                }
            }
            $this->_data                       = superman_setting_data();
            $mch_appid                         = $this->module['config']['wxpay']['mch_appid'];
            $mchid                             = $this->module['config']['wxpay']['mchid'];
            $this->_data['wxpay']['mch_appid'] = $mch_appid ? $mch_appid : ($pay && isset($pay['wechat']['account_setting']['key'])) ? $pay['wechat']['account_setting']['key'] : '';
            $this->_data['wxpay']['mchid']     = $mchid ? $mchid : ($pay && isset($pay['wechat']['mchid'])) ? $pay['wechat']['mchid'] : '';
            $this->saveSettings($this->_data);
            load()->model('module');
            $this->module = module_fetch('superman_creditmall');
        } else {
            $this->_data = array(
                '_init' => 1,
                'base' => $this->module['config']['base'],
                'template_message' => $this->module['config']['template_message'],
                'wxpay' => $this->module['config']['wxpay'],
                'share' => $this->module['config']['share'],
                'redpack' => $this->module['config']['redpack'],
                'help' => $this->module['config']['help'],
                'service' => $this->module['config']['service'],
                'subscribe' => $this->module['config']['subscribe'],
                'order' => $this->module['config']['order']
            );
        }
        $update_data = false;
        if (!isset($this->module['config']['template_message']['order_submit_content']) && $this->module['config']['template_message']['order_submit_content'] == '') {
            $this->module['config']['template_message']['order_submit_content'] = "first=您的订单已提交成功，请尽快支付！\nkeyword1={订单编号}\nkeyword2={订单时间}\nkeyword3={订单金额}\nremark=若有疑问，请联系客服";
            $update_data                                                        = true;
        }
        if (!isset($this->module['config']['template_message']['order_pay_content']) && $this->module['config']['template_message']['order_pay_content'] == '') {
            $this->module['config']['template_message']['order_pay_content'] = "first=您的订单已支付成功！\nkeyword1={订单编号}\nkeyword2={订单商品}\nkeyword3={订单金额}\nremark=点击查看订单详情";
            $update_data                                                     = true;
        }
        if (!isset($this->module['config']['template_message']['order_send_content']) && $this->module['config']['template_message']['order_send_content'] == '') {
            $this->module['config']['template_message']['order_send_content'] = "first=您的订单已发货成功！\nkeyword1={商品信息}\nkeyword2={快递公司}\nkeyword3={快递单号}\nkeyword4={收货信息}\nremark=点击查看订单详情";
            $update_data                                                      = true;
        }
        if (!isset($this->module['config']['template_message']['auction_success_content']) && $this->module['config']['template_message']['auction_success_content'] == '') {
            $this->module['config']['template_message']['auction_success_content'] = "first=恭喜，您已竞拍成功！\nkeyword1={商品标题}\nkeyword2={商品价格}\nremark=请尽快领取，点击查看详情";
            $update_data                                                           = true;
        }
        if ($update_data) {
            $this->saveSettings($this->module['config']);
        }
        if (checksubmit('submit')) {
            $this->_setting_base();
            $this->_setting_template_message();
            $this->_setting_wxpay();
            $this->_setting_help();
            $this->_setting_redpack();
            $this->_setting_share();
            $this->_setting_service();
            $this->_setting_subscribe();
            $this->_setting_order();
            $this->saveSettings($this->_data);
            message('更新成功！', referer(), 'success');
        }
        if (in_array($_W['account']['level'], array(
            1,
            2,
            3
        ))) {
            $where  = '';
            $params = array();
            if (empty($_W['isfounder'])) {
                $where          = " WHERE `uniacid` IN (SELECT `uniacid` FROM " . tablename('uni_account_users') . " WHERE `uid`=:uid)";
                $params[':uid'] = $_W['uid'];
            }
            $sql         = "SELECT * FROM " . tablename('uni_account') . $where;
            $uniaccounts = pdo_fetchall($sql, $params);
            $accounts    = array();
            if (!empty($uniaccounts)) {
                foreach ($uniaccounts as $uniaccount) {
                    $accountlist = uni_accounts($uniaccount['uniacid']);
                    if (!empty($accountlist)) {
                        foreach ($accountlist as $account) {
                            if (!empty($account['key']) && !empty($account['secret']) && in_array($account['level'], array(
                                4
                            ))) {
                                $accounts[$account['acid']] = array(
                                    'uniacid' => $account['uniacid'],
                                    'name' => $account['name']
                                );
                            }
                        }
                    }
                }
            }
        }
        include $this->template('web/setting');
    }
    private function _setting_base()
    {
        global $_W, $_GPC;
        $this->_data['base'] = $_GPC['base'];
        if ($_GPC['base']['debug_uids']) {
            $this->_data['base']['debug_uids'] = explode(',', trim($_GPC['base']['debug_uids']));
        }
    }
    private function _setting_template_message()
    {
        global $_W, $_GPC;
        $this->_data['template_message'] = $_GPC['template_message'];
    }
    private function _setting_wxpay()
    {
        global $_W, $_GPC;
        $wxpay                                            = $_GPC['wxpay'];
        $attach_path                                      = superman_attachment_root();
        $_W['setting']['upload']['image']['limit']        = 1000;
        $_W['setting']['upload']['image']['extentions'][] = 'pem';
        $arr                                              = array(
            'apiclient_cert',
            'apiclient_key',
            'rootca'
        );
        $data                                             = array(
            'mch_appid' => $wxpay['mch_appid'],
            'mchid' => $wxpay['mchid']
        );
        foreach ($arr as $k) {
            $data[$k] = isset($this->module['config']['wxpay'][$k]) ? $this->module['config']['wxpay'][$k] : '';
            if (isset($this->module['config']['wxpay'][$k]) && $this->module['config']['wxpay'][$k]) {
                $path[$k] = ATTACHMENT_ROOT . $this->module['config']['wxpay'][$k];
                if ($wxpay['del_' . $k]) {
                    if (file_exists($path[$k])) {
                        @unlink($path[$k]);
                    }
                    $data[$k] = '';
                }
            }
            if (!empty($_FILES['wxpay']['tmp_name'][$k])) {
                $file   = array(
                    'name' => $_FILES['wxpay']['name'][$k],
                    'tmp_name' => $_FILES['wxpay']['tmp_name'][$k],
                    'type' => $_FILES['wxpay']['type'][$k],
                    'error' => $_FILES['wxpay']['error'][$k],
                    'size' => $_FILES['wxpay']['size'][$k]
                );
                $upload = file_upload($file, 'image');
                if (!$upload['success']) {
                    message($upload['errno'] . ':' . $upload['message']);
                }
                if (isset($path[$k]) && file_exists($path[$k])) {
                    @unlink($path[$k]);
                }
                $data[$k] = $upload['path'];
            }
        }
        $this->_data['wxpay'] = $data;
        unset($data);
    }
    private function _setting_redpack()
    {
        global $_W, $_GPC;
        $this->_data['redpack'] = $_GPC['redpack'];
    }
    private function _setting_help()
    {
        global $_W, $_GPC;
        $this->_data['help'] = $_GPC['help'];
    }
    private function _setting_share()
    {
        global $_W, $_GPC;
        $this->_data['share'] = $_GPC['share'];
    }
    private function _setting_service()
    {
        global $_W, $_GPC;
        $this->_data['service'] = $_GPC['service'];
    }
    private function _setting_subscribe()
    {
        global $_W, $_GPC;
        $this->_data['subscribe'] = $_GPC['subscribe'];
    }
    private function _setting_order()
    {
        global $_W, $_GPC;
        $this->_data['order'] = $_GPC['order'];
    }
}
?>