<?php
/**
 * 悟空源 码论 坛bbs . xte c.cc定义
 *
 * @author 微赞
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Creditmall_doMobileAddress extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
        $this->checkauth();
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'post', 'delete', 'wechat_address'))?$_GPC['act']:'display';
        $uid = $_W['member']['uid'];
        if ($act == 'display') {
            $header_title = '收货地址';
            if ($_GPC['isdefault'] == 1) {
                $id = intval($_GPC['id']);
                $row = superman_mc_address_fetch($id);
                if (!$row) {
                    $this->message('地址不存在或已删除！', referer(), 'error');
                }
                //去掉默认地址
                $condition = array(
                    'uid' => $uid,
                    'isdefault' => '1',
                );
                pdo_update('mc_member_address', array('isdefault' => 0), $condition);

                //设置新默认地址
                pdo_update('mc_member_address', array('isdefault' => 1), array('id' => $id));
                if (isset($_GPC['forward']) && $_GPC['forward']) {
                    $this->message('更新成功！', base64_decode($_GPC['forward']), 'success');
                }
                $this->message('更新成功！', referer(), 'success');
            }
            $list = superman_mc_address_fetchall_uid($uid);
            if ($list) {
                foreach ($list as &$ad) {
                    $ad['mobile'] = superman_hide_mobile($ad['mobile']);
                    if ($ad['province'] == $ad['city']) {
                        $ad['address'] = $ad['city'].$ad['district'].$ad['address'];
                    } else {
                        $ad['address'] = $ad['province'].$ad['city'].$ad['district'].$ad['address'];
                    }
                }
                unset($ad);
            }

            //微信版本是否支持共享地址
            $allowShareAddress = Agent::getAgent();
            preg_match('/MicroMessenger\/([\d\.]+)/i', $allowShareAddress, $wechatInfo);
            $wechat_addr_switch = true;
            //微赞支付设置
            $setting = uni_setting($_W['uniacid'], array('payment'));
            if (!isset($_GPC['forward']) || $_GPC['forward'] == ''                              //非来自确认订单页
                || !$wechatInfo || (isset($wechatInfo[1]) && $wechatInfo[1] < '5.0')             //非微信或版本不支持
                || (isset($_GPC['wechat_addr_switch']) && $_GPC['wechat_addr_switch'] == 0)     //获取微信收货地址失败
                //|| !isset($setting['payment']) || !$setting['payment']['wechat']['switch']      //未开启微信支付
                || $_W['account']['level'] != 4) {                                              //非认证服务号
                $wechat_addr_switch = false;
            }
        } else if ($act == 'post') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                $header_title = '编辑地址';
                $item = superman_mc_address_fetch($id);
                if ($item && $item['uid'] == $uid) {
                    if ($item['province'] == $item['city']) {
                        $item['city'] = $item['city'].' '.$item['district'];
                    } else {
                        $item['city'] = $item['province'].' '.$item['city'].' '.$item['district'];
                    }
                } else {
                    $this->json_output(ERRNO::INVALID_REQUEST);
                }
            } else {
                $header_title = '添加地址';
            }
            if (checksubmit('submit')) {
                //表单参数检查
                $username = trim($_GPC['username']);
                if ($username == '') {
                    $this->json_output(ERRNO::USERNAME_NULL);
                }
                $mobile = $_GPC['mobile'];
                if ($mobile == '') {
                    $this->json_output(ERRNO::MOBILE_NULL);
                }
                if (!preg_match('/^([0-9]{11})?$/', $mobile)) {
                    $this->json_output(ERRNO::MOBILE_INVALID);
                }
                $address = trim($_GPC['address']);

                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $uid,
                    'username' => $username,
                    'mobile' => $mobile,
                    'address' => $address,
                    'isdefault' => $_GPC['isdefault']=='on'?1:0
                );

                if ($data['isdefault'] == 1) {
                    //旧默认地址初始化
                    pdo_update('mc_member_address', array('isdefault' => 0), array(
                        'uid' => $uid,
                        'uniacid' => $_W['uniacid'],
                        'isdefault' => 1
                    ));
                }

                $city = trim($_GPC['city']);
                if (!$city) {
                    $this->json_output(ERRNO::CITY_NULL);
                }
                $city = explode(' ',$city);
                if (count($city) == 3) {
                    $data['province'] = $city[0];
                    $data['city'] = $city[1];
                    $data['district'] = $city[2];
                } elseif (count($city)==2) {
                    $data['province'] = $city[0];
                    $data['city'] = $city[0];
                    $data['district'] = $city[1];
                } else {
                    $this->json_output(ERRNO::CITY_INVALID);
                }

                if ($id) {
                    $ret = pdo_update('mc_member_address', $data, array('id' => $id));
                } else {
                    $ret = pdo_insert('mc_member_address', $data);
                }
                if ($ret === false) {
                    $this->json_output(ERRNO::SYSTEM_ERROR, '系统错误，请稍后重试');
                }
                if (isset($_GPC['forward']) && $_GPC['forward']) {
                    $this->json_output(ERRNO::OK, '更新成功，跳转中...', array('url' => base64_decode($_GPC['forward'])));
                }
                $this->json_output(ERRNO::OK, '更新成功，跳转中...', array('url' => $this->createMobileUrl('address', array('act' => 'display'))));
            }
        } else if ($act == 'delete') {
            if ($uid) {
                $id = intval($_GPC['id']);
                if (!$id) {
                    $this->json_output(ERRNO::INVALID_REQUEST);
                }
                $address = superman_mc_address_fetch($id);
                //验证是否本人
                if (!$address || $address['uid'] != $uid) {
                    $this->json_output(ERRNO::ADDRESS_NOT_EXIST);
                }
                $ret = pdo_delete('mc_member_address', array('id' => $id));
                if ($ret === false) {
                    $this->json_output(ERRNO::SYSTEM_ERROR);
                }
                $this->json_output(ERRNO::OK, '删除成功！');
            }
        } else if ($act == 'wechat_address') {
            //只有从确认订单页过来的能获取微信地址，所以$_GPC['forward']一定存在
            if (!isset($_GPC['forward']) || $_GPC['forward'] == '') {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $state = 'superman_creditmall';
            $code = $_GPC['code'];
            $oauth_account = WeAccount::create($_W['oauth_account']);
            if (empty($code)) {     //获取code
                $callback = urlencode($_W['siteurl']);      //$_W['siteurl']中应该有$_GPC[forward]
                $forward = $oauth_account->getOauthCodeUrl($callback, $state);
                @header('Location:'.$forward);
                exit();
            } else {                //获取$accessToken
                $OauthInfo = $oauth_account->getOauthInfo($code);
                if (!isset($OauthInfo['access_token'])) {   //未获取到
                    WeUtility::logging('fatal', 'code未获取到accesstoken，错误原因'.var_export($OauthInfo, true));
                    $url = $this->createMobileUrl('address', array(
                            'act' => 'display',
                            'forward' => $_GPC['forward'],
                            'wechat_addr_switch' => 0)
                    );
                    $this->json_output(ERRNO::SYSTEM_ERROR, '获取微信地址失败，请添加新地址', array('url' => $url));
                }
                $accessToken = $OauthInfo['access_token'];
                $timeStamp = $_W['timestamp'];
                $timeStamp = "$timeStamp";      //时间戳，必须是字符串类型
                $nonceStr = random(16);         //随机字符串
                $String1 = "accesstoken={$accessToken}&appid={$_W['account']['key']}&noncestr={$nonceStr}&timestamp={$timeStamp}&url={$_W['siteurl']}";
                $addrSign = SHA1($String1);
            }
        }
        include $this->template('address');
    }
}

$obj = new Creditmall_doMobileAddress;
$obj->exec();
