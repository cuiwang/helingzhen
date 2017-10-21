<?php
/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/2
 * Time: 下午4:46
 * Exception 5开头
 */
require_once dirname(__FILE__).'/../../../lonaking_flash/FlashCommonService.php';
class GiftShopUserService{

    /**
     * 更新用户积分
     * @param $score
     * @param $openid
     */
    public function updateUserScore($score, $openid){
        load()->model('mc');
        $uid = mc_openid2uid($openid);
        //'credit1','credit2'  1=> 积分 2=>金额
        mc_credit_update($uid,'credit1',$score);
    }

    /**
     * 获取用户积分
     * @param $openid
     */
    public function fetchUserScore($openid){
        load()->model('mc');
        $uid = mc_openid2uid($openid);
        //'credit1','credit2'  1=> 积分 2=>金额
        $credits = mc_credit_fetch($uid,array('credit1'));
        return $credits['credit1'];
    }

    /**
     * 获取用户余额
     * @param $openid
     * @return mixed
     */
    public function fetchUserMoney($openid){
        load()->model('mc');
        $uid = mc_openid2uid($openid);
        //'credit1','credit2'  1=> 积分 2=>金额
        $credits = mc_credit_fetch($uid,array('credit2'));
        return $credits['credit2'];
    }

    /**
     * 获取用户积分，得到一个数组
     * @param $openid
     * @return array
     */
    public function fetchUserCredit($openid){
        load()->model('mc');
        $uid = mc_openid2uid($openid);
        //'credit1','credit2'  1=> 积分 2=>金额
        $credits = mc_credit_fetch($uid);
        return $credits;
    }
}