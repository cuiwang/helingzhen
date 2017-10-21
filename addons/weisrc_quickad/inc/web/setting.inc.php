<?php
global $_GPC, $_W, $code;
$code = $this->copyright;
load()->func('tpl');
$uniacid = $this->_uniacid;
$action  = 'setting';
$title   = $this->actions_titles[$action];
$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE uniacid = :uniacid LIMIT 1", array(
    ':uniacid' => $uniacid
));
$fans    = $this->getFansByOpenid($setting['tpluser']);
if (checksubmit('submit')) {
    $data             = array(
        'uniacid' => $_W['uniacid'],
        'title' => trim($_GPC['title']),
        'price' => floatval($_GPC['price']),
        'copyright' => trim($_GPC['copyright']),
        'share_title' => trim($_GPC['share_title']),
        'share_desc' => trim($_GPC['share_desc']),
        'share_image' => trim($_GPC['share_image']),
        'share_url' => trim($_GPC['share_url']),
        'dateline' => TIMESTAMP,
        'weixin' => trim($_GPC['weixin']),
        'viptype' => intval($_GPC['viptype']),
        'paytype' => intval($_GPC['paytype']),
        'read_min' => intval($_GPC['read_min']),
        'read_max' => intval($_GPC['read_max']),
        'praise_min' => intval($_GPC['praise_min']),
        'praise_max' => intval($_GPC['praise_max']),
        'show_qrcode' => intval($_GPC['show_qrcode']),
        'show_mobile' => intval($_GPC['show_mobile']),
        'qq' => intval($_GPC['qq']),
        'apptitle' => trim($_GPC['apptitle']),
        'is_check' => intval($_GPC['is_check']),
        'istplnotice' => intval($_GPC['istplnotice']),
        'tplneworder' => trim($_GPC['tplneworder']),
        'notice_openid' => intval($_GPC['notice_openid']),
        'wechat' => intval($_GPC['wechat']),
        'alipay' => intval($_GPC['alipay']),
        'credit' => intval($_GPC['credit']),
        'delivery' => intval($_GPC['delivery']),
        'weixin_qrcode' => trim($_GPC['weixin_qrcode']),
        'taste_vip' => intval($_GPC['taste_vip']),
        'is_secondary_show' => intval($_GPC['is_secondary_show']),
        'price1' => floatval($_GPC['price1']),
        'price2' => floatval($_GPC['price2']),
        'price3' => floatval($_GPC['price3']),
        'price4' => floatval($_GPC['price4'])
    );
    //$data['ad2_text'] = trim($_GPC['ad2_text']);
    //$data['ad2']      = trim($_GPC['ad2']);
    //$data['ad_url2']  = trim($_GPC['ad_url2']);
    if (empty($setting)) {
        pdo_insert($this->table_setting, $data);
    } else {
        unset($data['dateline']);
        pdo_update($this->table_setting, $data, array(
            'uniacid' => $_W['uniacid']
        ));
    }
    message('操作成功', $this->createWebUrl('setting'), 'success');
}
include $this->template('setting');