<?php
/**
 * Created by PhpStorm.
 * User: start
 * Date: 2016/12/25
 * Time: 16:34
 */
require_once(dirname(__FILE__) . "/parConfig.php");

class userModel{
    /**
     * 通过openid 获得用户信息
     * @param $openId
     *
     */
    static function getUserByOpenId($openId){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        return pdo_get('wx_carpool_user',
            array(
                'abc_weid_abc' => $weid,
                'abc_openid_abc'=>$openId
            ));
    }

    /**
     * 通过openid 和用户ID更新用户余额
     * @param $abc_user_data_abc
     * @param $type = 1 消费  $type = 2 充值
     */
    static function updateBalance($abc_user_data_abc,$type=1){
        global $_W,$_GPC;
        $abc_weid_abc = $_W['uniacid'];               //获取当前公众号ID
        $abc_openid_abc=$_W['openid'];//获取当前用户openid
        $abc_last_time_to_recharge_abc=$abc_user_data_abc['abc_last_time_to_recharge_abc']; //获取更新时间

        if($type==2){

            /*充值操作*/
            $abc_add_price_abc=$abc_user_data_abc['abc_add_price_abc'];
        }else{
            /*消费操作*/
            $abc_add_price_abc=$abc_user_data_abc;
        }

        if($type==2){
            /*充值操作*/
            return pdo_fetch("UPDATE ".tablename('wx_carpool_user')." 
        SET abc_balance_abc=abc_balance_abc+$abc_add_price_abc,abc_last_time_to_recharge_abc='$abc_last_time_to_recharge_abc'  
        WHERE abc_weid_abc=$abc_weid_abc AND abc_openid_abc='$abc_openid_abc'  ");
        }else{
            /*消费操作*/
            return pdo_fetch("UPDATE ".tablename('wx_carpool_user')." 
        SET abc_balance_abc=  $abc_add_price_abc  
        WHERE abc_weid_abc=$abc_weid_abc AND abc_openid_abc='$abc_openid_abc'  ");
        }

    }

    /**
     * 创建一个新用户
     */
    static function createUser(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        $openid=$_W['openid'];
        pdo_insert('wx_carpool_user',array(
            'abc_weid_abc'=>$weid,
            'abc_openid_abc' =>$openid,
            'abc_create_time_abc'=>date('y-m-d h:i:s',time()),
            'abc_balance_abc'=> ParConfigModel::getNewUserPresent()
        ));

    }


    /**
     * 获得用户总数
     * @return int
     */
    static function getUserCount(){
        global $_W,$_GPC;

        $weid=$_W['weid'];

        $abc_table_wx_carpool_user_abc=tablename("wx_carpool_user");
        $abc_sql_abc = "select count(*) as count from $abc_table_wx_carpool_user_abc 
        where abc_weid_abc = $weid ";
        $ret=pdo_fetch($abc_sql_abc);
        return intval($ret['count']);
    }

    /*获得用户列表*/
    static function getUserList(){
        global $_W,$_GPC;
        $weid=$_W['weid'];

        /*获得总数*/
        $count=self::getUserCount();
        /*获得分页信息*/
        $p=1;
        if(isset($_GPC['p'])){
            if(intval($_GPC['p'])>0){
                $p=intval($_GPC['p']);
            }
        }
        $abc_table_wx_carpool_user_abc=tablename("wx_carpool_user");
        $abc_pagesize_abc=10;
        $abc_start_abc=(($p-1)*$abc_pagesize_abc);
        $abc_end_abc=$abc_start_abc+$abc_pagesize_abc;
        $abc_sql_abc="select * from $abc_table_wx_carpool_user_abc
        where abc_weid_abc = $weid
        order by abc_balance_abc desc
        limit $abc_start_abc , $abc_end_abc";
        return pdo_fetchall($abc_sql_abc);
    }

}