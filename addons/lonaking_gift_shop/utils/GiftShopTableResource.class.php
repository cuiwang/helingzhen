<?php
/**
 * table 静态工具类
 * @author leon
 *
 */
class GiftShopTableResource
{
    static $table = array(
        'gift' => array(
            'name' => 'lonaking_gift_shop_gift',
            'columns' => 'id,uniacid,name,price,type,num,status,del,description,pic,mode,send_price,createtime,updatetime,mobile_fee_money,hongbao_money,ziling_address,ziling_mobile,sold,hide,limit_num,raffle,hongbao_min,hongbao_max,hongbao_send_num'
        ),
        'gift_order' => array(
            'name' => 'lonaking_gift_shop_gift_order',
            'columns' => 'id,uniacid,openid,order_num,gift,status,name,mobile,target,createtime,updatetime,pay_method,pay_status,trans_num,send_price,order_price,order_hongbao_money'
        ),
        'gift_admin' => array(
            'name' => 'lonaking_gift_shop_gift_admin',
            'columns' => 'id,uniacid,openid,gift_id'
        ),
        'ad' => array(
            'name' => 'lonaking_gift_shop_ad',
            'columns' => 'id,uniacid,title,image,url,type,delay,createtime,updatetime'
        ),
        'tpl_config' => array(
            'name' => 'lonaking_gift_shop_tpl_template_config',
            'columns' => 'id,uniacid,get_notice,check_status_access_notice,check_status_refuse_notice,send_notice'
        )
    );
}
