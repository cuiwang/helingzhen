<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/superman_house/const.php';
require IA_ROOT . '/addons/superman_house/common.func.php';
require IA_ROOT . '/addons/superman_house/model.func.php';
define('SUPERMAN_BAIDU_MAP_AK', 'FpNN8295GdYVL7gpGIjGPDGZ');
class Superman_houseModuleSite extends WeModuleSite
{
    protected $modules_bindings = array();
    protected $navigation = array();
    public function __construct($allowInit = false)
    {
        if (!$allowInit) {
            return;
        }
        global $_GPC, $_W, $do;
        load()->func('tpl');
        load()->func('file');
        load()->model('mc');
        load()->model('module');
        $this->uniacid    = $_W['uniacid'];
        $this->modulename = 'superman_house';
        $this->module     = module_fetch($this->modulename);
        $this->__define   = IA_ROOT . "/addons/{$this->modulename}/module.php";
        $this->inMobile   = defined('IN_MOBILE');
        if (!isset($_GPC['do']) && isset($_GPC['eid']) && $_GPC['eid']) {
            $eid    = intval($_GPC['eid']);
            $sql    = "SELECT `do` FROM " . tablename('modules_bindings') . " WHERE eid=:eid";
            $params = array(
                ':eid' => $eid
            );
            $do     = pdo_fetchcolumn($sql, $params);
        }
        if ($this->inMobile) {
            $this->_share = array();
            if (isset($this->module['config']['base']['share'])) {
                $share_params = $this->module['config']['base']['share'];
                $this->_share = array(
                    'title' => $share_params['title'],
                    'link' => $_W['siteurl'],
                    'imgUrl' => tomedia($share_params['imgurl']),
                    'content' => $share_params['desc']
                );
                unset($share_params);
            }
            $filter   = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1
            );
            $nav_data = superman_navigation_fetchall($filter, '', 0, -1);
            if (!$nav_data) {
                $nav_data = superman_navigation_data($this->module['config']);
            }
            foreach ($nav_data as &$v) {
                if ($v['title'] == '' || $v['url'] == '' || !$v['isshow']) {
                    continue;
                }
                $v['active'] = false;
                $url         = str_replace('./', '/', $v['url']);
                $url         = str_replace('//', '/', $url);
                if (strexists($_W['siteurl'], $url)) {
                    $v['active'] = true;
                }
                $this->navigation[] = $v;
            }
            if ($_W['member']['uid']) {
                $_W['member'] = array_merge($_W['member'], mc_fetch($_W['member']['uid'], array(
                    'nickname',
                    'avatar'
                )));
                $data         = array();
                if (!empty($_W['fans'])) {
                    if (empty($_W['member']['nickname'])) {
                        $data['nickname'] = $_W['fans']['tag']['nickname'];
                    }
                    if (empty($_W['member']['avatar'])) {
                        $data['avatar'] = $_W['fans']['tag']['headimgurl'] ? $_W['fans']['tag']['headimgurl'] : $_W['fans']['tag']['avatar'];
                    }
                } else {
                    $fan = mc_fansinfo($_W['member']['uid']);
                    if ($fan) {
                        if (empty($_W['member']['nickname'])) {
                            $data['nickname'] = $fan['tag']['nickname'];
                        }
                        if (empty($_W['member']['avatar'])) {
                            $data['avatar'] = $fan['tag']['headimgurl'] ? $fan['tag']['headimgurl'] : $fan['tag']['avatar'];
                        }
                    }
                }
                if (!empty($data)) {
                    pdo_update('mc_members', $data, array(
                        'uid' => $_W['member']['uid']
                    ));
                    $_W['member']['nickname'] = $data['nickname'];
                    $_W['member']['avatar']   = $data['avatar'];
                }
            }
        } else {
        }
    }
    public function checkauth()
    {
        global $_W, $_GPC;
        if (!$_W['member']['uid']) {
            if ($_W['container'] == 'wechat') {
                if (!defined('LOCAL_DEVELOPMENT')) {
                    if (defined('ONLINE_DEVELOPMENT')) {
                        WeUtility::logging('debug', '[checkauth] _W[fans]=' . var_export($_W['fans'], true));
                    }
                    if (!empty($_W['fans']['openid'])) {
                        $fan = mc_fansinfo($_W['fans']['openid']);
                        if (defined('ONLINE_DEVELOPMENT')) {
                            WeUtility::logging('debug', '[checkauth] mc_fansinfo fan=' . var_export($fan, true));
                        }
                        if (empty($fan)) {
                            mc_oauth_userinfo();
                        }
                        if (!$fan['follow'] && $this->module['config']['base']['guide_subscribe_open']) {
                            if (defined('ONLINE_DEVELOPMENT')) {
                                WeUtility::logging('debug', '[checkauth] guide subscribe start');
                            }
                            if (!empty($this->module['config']['base']['guide_subscribe_content'])) {
                                @header('Location: ' . $this->createMobileUrl('guide'));
                                exit;
                            } else if (!empty($_W['account']['subscribeurl'])) {
                                @header('Location: ' . $_W['account']['subscribeurl']);
                                exit;
                            } else {
                                echo '您还未关注公众号，请关注后，继续操作。<br><br>关注方法：微信=》添加朋友=》公众号=》搜索 "' . $_W['account']['name'] . '"';
                                exit;
                            }
                        }
                        if (empty($fan['uid'])) {
                            $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(
                                ':uniacid' => $_W['uniacid']
                            ));
                            $salt            = random(8);
                            $data            = array(
                                'uniacid' => $_W['uniacid'],
                                'email' => md5($fan['openid']) . '@012wz.com',
                                'salt' => $salt,
                                'groupid' => $default_groupid,
                                'createtime' => TIMESTAMP,
                                'password' => md5($fan['openid'] . $salt . $_W['config']['setting']['authkey']),
                                'nickname' => stripslashes($fan['tag']['nickname']),
                                'avatar' => $fan['tag']['headimgurl'],
                                'gender' => $fan['tag']['sex'],
                                'nationality' => $fan['tag']['country'],
                                'resideprovince' => $fan['tag']['province'] . '省',
                                'residecity' => $fan['tag']['city'] . '市'
                            );
                            pdo_insert('mc_members', $data);
                            $fan['uid'] = pdo_insertid();
                            if (defined('ONLINE_DEVELOPMENT')) {
                                WeUtility::logging('debug', '[checkauth] init mc_members, uid=' . $fan['uid']);
                            }
                        }
                        if (empty($fan['fanid'])) {
                            $data = array(
                                'openid' => $fan['openid'],
                                'uid' => $fan['uid'],
                                'acid' => $_W['acid'],
                                'uniacid' => $_W['uniacid'],
                                'salt' => random(8),
                                'updatetime' => TIMESTAMP,
                                'nickname' => stripslashes($fan['tag']['nickname']),
                                'follow' => 0,
                                'followtime' => 0,
                                'unfollowtime' => 0,
                                'tag' => base64_encode(iserializer($fan['tag']))
                            );
                            pdo_insert('mc_mapping_fans', $data);
                            $fan['fanid'] = pdo_insertid();
                            if (defined('ONLINE_DEVELOPMENT')) {
                                WeUtility::logging('debug', '[checkauth] init mc_mapping_fans, fanid=' . $fan['fanid']);
                            }
                        }
                        if (!empty($fan['uid']) && _mc_login(array(
                            'uid' => $fan['uid']
                        ))) {
                            if (defined('ONLINE_DEVELOPMENT')) {
                                WeUtility::logging('debug', '[checkauth] _mc_login success');
                            }
                            return true;
                        }
                    } else {
                        if (defined('ONLINE_DEVELOPMENT')) {
                            WeUtility::logging('debug', '[checkauth] mc_oauth_userinfo start');
                        }
                        mc_oauth_userinfo();
                    }
                }
            }
            message('未登录，跳转中...', url("auth/login", array(
                "forward" => base64_encode($_SERVER["QUERY_STRING"])
            )), 'info');
        }
        return true;
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        $ordid   = $params['tid'];
        $data    = array(
            'status' => $params['result'] == 'success' ? 1 : 0
        );
        $paytype = array(
            'credit' => '1',
            'wechat' => '2',
            'alipay' => '2',
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
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
        }
        $data['paytime'] = TIMESTAMP;
        $ret             = pdo_update('supermanfc_house_order', $data, array(
            'ordid' => $ordid
        ));
        if ($ret === false) {
            WeUtility::logging('fatal', '[superman_house] 订单状态更新失败, ordid=' . $ordid . ', data=' . var_export($data, true));
            message('订单状态更新失败，请联系管理员', '', 'error');
        }
        if ($params['result'] == 'success') {
            if ($params['from'] == 'return') {
                $setting = uni_setting($_W['uniacid'], array(
                    'creditbehaviors'
                ));
                $credit  = $setting['creditbehaviors']['currency'];
                if ($params['type'] == $credit) {
                    message('支付成功！', $this->createMobileUrl('myorder', array(
                        'status' => 1
                    )), 'success');
                } else {
                    message('支付成功！', '../../app/' . $this->createMobileUrl('myorder', array(
                        'status' => 1
                    )), 'success');
                }
            }
        }
    }
    public function sendTemplateMessage($message_info)
    {
        global $_W;
        $template_id       = $message_info['template_id'];
        $template_variable = $message_info['template_variable'];
        if (!$_W['acid']) {
            $accounts = uni_accounts();
            foreach ($accounts as $k => $v) {
                $_W['account'] = $v;
                $_W['acid']    = $_W['account']['acid'];
                break;
            }
        }
        if (!$_W['uniacid']) {
            $_W['uniacid'] = $message_info['uniacid'];
        }
        if (!isset($message_info['openid'])) {
            $fans = mc_fansinfo($message_info['receiver_uid']);
            if (!$fans) {
                WeUtility::logging("warning", "sendTemplateMessage failed: 没有找到粉丝信息, uid={$message_info['receiver_uid']}");
                return false;
            }
            if (!$fans['follow']) {
                WeUtility::logging("warning", "sendTemplateMessage failed: 粉丝已取消关注, fans=" . var_export($fans, true));
                return false;
            }
            $message_info['openid'] = $fans['openid'];
        } else {
            $fans = mc_fansinfo($message_info['openid']);
        }
        $account = $this->initAccount();
        if (is_error($account)) {
            WeUtility::logging('fatal', 'sendTemplateMessage failed: account=' . var_export($account, true));
            return $account;
        }
        $message           = array(
            'template_id' => $template_id,
            'postdata' => array(),
            'url' => $message_info['url'],
            'topcolor' => '#008000'
        );
        $template_variable = explode("\n", $template_variable);
        foreach ($template_variable as $line) {
            $arr                                = explode("=", trim($line));
            $message['postdata'][trim($arr[0])] = array(
                'value' => superman_replace_variable(trim($arr[1]), $message_info['vars']),
                'color' => '#173177'
            );
        }
        $ret = $account->sendTplNotice($message_info['openid'], $message['template_id'], $message['postdata'], $message['url'], $message['topcolor']);
        if ($ret !== true) {
            WeUtility::logging("fatal", "sendTemplateMessage failed: openid={$message_info['openid']}, ret=" . var_export($ret, true) . ", message=" . var_export($message, true));
        } else {
        }
        return true;
    }
    public function initAccount()
    {
        global $_W;
        static $account = null;
        if (!is_null($account)) {
            return $account;
        }
        if (empty($_W['account'])) {
            $_W['account'] = uni_fetch($_W['uniacid']);
        }
        if (empty($_W['account'])) {
            return error(-1, '创建公众号操作类失败');
        }
        if ($_W['account']['level'] < 3) {
            return error(-1, '公众号没有经过认证');
        }
        $account = WeAccount::create();
        if (is_null($account)) {
            return error(-1, '创建公众号操作对象失败');
        }
        return $account;
    }
    public function sendCustomerStatusNotice($openid, $changername, $receivername, $customername, $status_title, $url = '', $update_time = TIMESTAMP, $remark = '', $money = 0)
    {
        global $_W;
        $account = $this->initAccount();
        if (is_error($account)) {
            WeUtility::logging('fatal', 'sendCustomerStatusNotice failed: account=' . var_export($account, true));
            return $account;
        }
        $update_time = date('Y-m-d H:i:s', $update_time);
        $text        = "$receivername 您好，客户 $customername 的状态已被 $changername 变更为 ";
        $text .= "$status_title\n";
        if (!empty($remark)) {
            $text .= "备注：$remark\n";
        }
        $text .= "佣金：$money\n";
        $text .= "$update_time";
        $message = array(
            'msgtype' => 'news',
            'news' => array(
                'articles' => array(
                    array(
                        'title' => urlencode('客户状态变更通知'),
                        'description' => urlencode($text),
                        'url' => urlencode($url),
                        'picurl' => ''
                    )
                )
            ),
            'touser' => $openid
        );
        $result  = $account->sendCustomNotice($message);
        if (is_error($result)) {
            WeUtility::logging('fatal', 'sendCustomerStatusNotice failed: result=' . var_export($result, true));
        }
        return $result;
    }
    public function sendRecommendNotice($openid, $url, $housename, $recommendname, $customername, $remark)
    {
        global $_W;
        $account = $this->initAccount();
        if (is_error($account)) {
            WeUtility::logging('fatal', 'sendRecommendNotice failed: account=' . var_export($account, true));
            return $account;
        }
        $text = '您好，【' . $housename . "】有新客户\n";
        $text .= "推荐人：$recommendname\n";
        $text .= "被推荐人：$customername\n";
        $text .= "推荐时间：$remark\n";
        $message = array(
            'msgtype' => 'news',
            'news' => array(
                'articles' => array(
                    array(
                        'title' => urlencode('新客户通知'),
                        'description' => urlencode($text),
                        'url' => urlencode($url),
                        'picurl' => ''
                    )
                )
            ),
            'touser' => $openid
        );
        $result  = $account->sendCustomNotice($message);
        if (is_error($result)) {
            WeUtility::logging('fatal', 'sendRecommendNotice failed: result=' . var_export($result, true));
        }
        return $result;
    }
    public function sendDistributeNotice($openid, $url, $housename, $customername, $updatetime)
    {
        global $_W;
        $account = $this->initAccount();
        if (is_error($account)) {
            WeUtility::logging('fatal', 'sendDistributeNotice failed: account=' . var_export($account, true));
            return $account;
        }
        $text = '您好，【' . $housename . "】有新客户\n";
        $text .= '任务名称：跟踪客户【' . $customername . "】\n";
        $text .= "更新内容：分配新客户\n";
        $text .= "更新时间：$updatetime";
        $message = array(
            'msgtype' => 'news',
            'news' => array(
                'articles' => array(
                    array(
                        'title' => urlencode('任务更新通知'),
                        'description' => urlencode($text),
                        'url' => urlencode($url),
                        'picurl' => ''
                    )
                )
            ),
            'touser' => $openid
        );
        $result  = $account->sendCustomNotice($message);
        if (is_error($result)) {
            WeUtility::logging('fatal', 'sendDistributeNotice failed: result=' . var_export($result, true));
            WeUtility::logging('fatal', 'message' . var_export($message, true));
        }
        return $result;
    }
    public function get_eid()
    {
        global $_W, $_GPC;
        $module = $_GPC['m'];
        $entry  = $_GPC['a'] == 'entry' ? 'menu' : $_GPC['a'];
        $do     = $_GPC['do'];
        if (empty($module) || empty($entry) || empty($do)) {
            return 0;
        }
        $sql    = "SELECT eid FROM " . tablename('modules_bindings') . " WHERE module=:module AND entry=:entry AND do=:do";
        $params = array(
            'module' => $module,
            'entry' => $entry,
            'do' => $do
        );
        return pdo_fetchcolumn($sql, $params);
    }
    private function _check_running_interval_time($filename, $interval = 300)
    {
        $name = substr($filename, strrpos($filename, '/') + 1);
        if (empty($filename)) {
            WeUtility::logging('fatal', "[_check_running_interval_time:$name] filename is null");
            return false;
        }
        if (!file_exists($filename)) {
            $interval = 0;
        }
        $fp = fopen($filename, "a");
        if (!$fp) {
            WeUtility::logging('fatal', "[_check_running_interval_time:$name] fopen failed, filename=$filename");
            return false;
        }
        if (!flock($fp, LOCK_EX | LOCK_NB)) {
            fclose($fp);
            return false;
        }
        if ($interval > 0) {
            clearstatcache();
            $lasttime = filemtime($filename);
            $diff     = TIMESTAMP - $lasttime;
            if ($diff < $interval) {
                if (defined('LOCAL_DEVELOPMENT')) {
                }
                flock($fp, LOCK_UN);
                fclose($fp);
                return false;
            }
        }
        ftruncate($fp, 0);
        rewind($fp);
        $ret = fwrite($fp, (string) TIMESTAMP);
        if ($ret <= 0) {
            WeUtility::logging('fatal', "[_check_running_interval_time:$name] file_put_contents failed(2), ret=$ret");
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }
        if (defined('LOCAL_DEVELOPMENT')) {
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }
}

?>