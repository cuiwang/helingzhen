<?php
global $_GPC, $_W;
$weid = $this->_weid;
$action = 'businesssetting';
$title = $this->actions_titles[$action];

$storeid = intval($_GPC['storeid']);
$this->checkStore($storeid);
$this->checkPermission($storeid);

$setting = $this->getSetting();
$store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu($storeid, $action);
$operation = 'detail';

if (!empty($store['business_openid'])) {
    $fans = $this->getFansByOpenid($store['business_openid']);
}

if (checksubmit('submit')) {
    $business_openid = trim($_GPC['from_user']);

    if ($_GPC['business_type'] == 1) {
        if (empty($business_openid)) {
            message('请选择提现的粉丝!');
        }
        if (empty($_GPC['business_wechat'])) {
            message('请输入微信账号!');
        }
    } else {
        if (empty($_GPC['business_alipay'])) {
            message('请输入支付宝账号!');
        }
    }
    if (empty($_GPC['business_username'])) {
        message('请输入账户姓名!');
    }

    $data = array(
        'business_type' => intval($_GPC['business_type']),
        'business_openid' => $business_openid,
        'business_username' => trim($_GPC['business_username']),
        'business_alipay' => trim($_GPC['business_alipay']),
        'business_wechat' => trim($_GPC['business_wechat']),
    );
    pdo_update($this->table_stores, $data, array('id' => $storeid));
    message('操作成功!!!', $this->createWebUrl('businesssetting', array('storeid' => $storeid)), 'success');
}

include $this->template('web/businesscenter');