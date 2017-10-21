<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/4
 * Time: 下午12:04
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class GiftShopGiftService extends FlashCommonService
{


    /**
     * GiftService constructor.
     */
    public function __construct()
    {
        $this->plugin_name = 'lonaking_gift_shop';
        $this->table_name = 'lonaking_gift_shop_gift';
        $this->columns = 'id,uniacid,name,price,type,num,status,pic,mode,send_price,del,description,createtime,updatetime,mobile_fee_money,hongbao_money,hongbao_mode,ziling_address,ziling_mobile,check_password,hide,sold,limit_num,raffle,hongbao_min,hongbao_max,hongbao_send_num,raffle_min,raffle_max,raffle_send_num,auto_success';
    }

    /**
     * 根据核销密码查找礼品
     * @param $check_password
     * @return bool
     */
    public function selectByCheckPassword($check_password){
        global $_W;
        $admin = pdo_fetch("SELECT " . $this->columns . " FROM " . tablename($this->table_name) . " WHERE check_password=:check_password AND uniacid=:uniacid", array(':check_password'=>$check_password,':uniacid'=>$_W['uniacid']));
        return $admin;
    }
}