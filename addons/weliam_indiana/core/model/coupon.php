<?php
	
if (!defined('IN_IA')) {
	exit('Access Denied');
} 

class Welian_Indiana_coupon{
	public	function coupon_message($goodsid){
		global $_W,$_GPC;
		if(empty($goodsid)){
			$message = '';
		}else{
			$couponid = pdo_fetch("select couponid from".tablename('weliam_indiana_goodslist')."where uniacid = '{$_W['uniacid']}' and id = '{$goodsid}'");
			if($couponid['couponid'] != 0){
				$list = pdo_fetch("select * from".tablename('weliam_indiana_coupon')."where uniacid = '{$_W['uniacid']}' and couponid = '{$couponid['couponid']}'");				
				$merchant = pdo_fetch("select name from".tablename('weliam_indiana_merchant')."where uniacid = '{$_W['uniacid']}' and id = '{$list['merchantid']}'");
				$message = '<div class="g-wrap-bd" style="background:white;color:#999999;margin-bottom:-1px;border:solid #d5d5d5;border-width:1px 0; margin-bottom: 10px;"><div class="m-detail-more" style="padding: 5px 10px 0px 20px;"><li style="list-style-type: none">商家:'.$merchant['name'].'</li><li style="list-style-type: none">优惠券:满'.$list['condition'].'减'.$list['discount'].'</li></div></div>';
				
			}else{
				$message = '';
			}
			
			
		}
		return $message;
		
	}
}

