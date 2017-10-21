<?php
class Index_EweiShopV2Page extends PluginWebPage{
    function main()
    {
        global $_W;
        header("location: " . webUrl("pc/shop"));
        exit();
    }
    function shop()
    {
        global $_W, $_GPC;
        $data = m('common') -> getSysset('shop');
        $url = mobileUrl('pc', null, true);
        $qrcode = m('qrcode') -> createQrcode($url);
        if ($_W['ispost']){
            ca('sysset.shop.edit');
            $data = is_array($_GPC['data'])? $_GPC['data'] : array();
            $data['name'] = trim($data['name']);
            $data['img'] = save_media($data['img']);
            $data['logo'] = save_media($data['logo']);
            $data['signimg'] = save_media($data['signimg']);
            $data['diycode'] = $_POST['data']['diycode'];
            m("common") -> updateSysset(array("shop" => $data));
            plog("sysset.shop.edit", "修改系统设置-商城设置");
            show_json(1);
        }
        include $this -> template();
    }
}
?>