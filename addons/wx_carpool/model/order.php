<?php

/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/26
 * Time: 1:27
 */

require_once(dirname(__FILE__) . "/parConfig.php");


class orderModel
{

    /*获得查询条件*/
    static function getWhere(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        $where= " where abc_weid_abc= $weid";
        $abc_order_type_abc=-1; /*默认订单类型为全部*/
        if(intval($_GPC['abc_order_type_abc'])>0){
            $abc_order_type_abc=intval($_GPC['abc_order_type_abc']);
        }
        if($abc_order_type_abc==1){
            $where.= " and  abc_order_type_abc= 0 ";
        }
        if($abc_order_type_abc==2){
            $where.= " and  abc_order_type_abc= 1 ";
        }
        return $where;
    }

    /*获得订单的数量*/
    static function getListCount(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        $table_wx_carpool_order=tablename('wx_carpool_order');
        $sql="select count(*) as count from $table_wx_carpool_order ";
        $sql.=self::getWhere();
        $result= pdo_fetch($sql);
        return $result['count'];
    }
  

    /*带分页订单查询*/
    static function getOrderListByPage(){
        global $_W,$_GPC;

        /*取得当前页码*/
        $p=intval($_GPC['p']);
        if($p<=0){
            $p=1;
        }

        
        
        $weid = $_W['uniacid'];               //获取当前公众号ID
        $table_wx_carpool_order=tablename('wx_carpool_order');
        $sql="select  * from $table_wx_carpool_order ";

        $abc_pagesize_abc=10;
        $abc_start_abc=(($p-1)*$abc_pagesize_abc);
        $abc_end_abc=$abc_start_abc+$abc_pagesize_abc;
        $sql.=self::getWhere();
        $sql.=" limit $abc_start_abc , 10";
        $result= pdo_fetchall($sql);
        return $result;
    }


    /**
     * 查询当前用户发布的订单置顶时间排序
     * @param $weid要查询订单的公众号ID
     */
    static function getUserOrder()
    {
        global $_W,$_GPC;
        $abc_openid_abc=$_W['openid'];//获取当前用的OPENID
        $abc_weid_abc = $_W['uniacid'];               //获取当前公众号ID


        return $result=pdo_fetchall("select * from".tablename('wx_carpool_order')." where abc_openid_abc='$abc_openid_abc' AND abc_weid_abc=$abc_weid_abc AND abc_state_for_manager_abc=1 AND abc_state_for_user_abc=1 order by abc_departure_time_abc Desc ,abc_order_create_time_abc Desc");
    }
    /**
     * 查询所有找乘客的订单
     * @param $weid要查询订单的公众号ID
     */
    static function getPassenger()
    {
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
/*        $timestamp=$_W['timestamp'];//获得当前时间戳
        $time=date('Y-m-d H:i:s', $timestamp);//将当前时间戳转化为时间格式*/
        $sql="select * from".tablename('wx_carpool_order')."where abc_weid_abc=$weid ";


        /*是否显示过期信息*/
        $isHidePastInfo = ParConfigModel::getIsHidePastInfo();
        if($isHidePastInfo ==0){
            /*不显示过期信息*/
            $sql.= " AND abc_departure_time_abc  > " .    "'"  .   date('Y-m-d H:i:s', $_W['timestamp'])   ."'";
        }


        $sql.= " AND abc_state_for_user_abc=1 AND abc_state_for_manager_abc=1 AND abc_order_type_abc=1  order by abc_isTop_abc Desc,abc_order_create_time_abc Desc";

        $p=1;
        if(intval($_GPC['p'])>=1){
            $p=intval($_GPC['p']);
        }

        $start=($p-1)*10;
        $sql.=" limit $start,10";

        return $result=pdo_fetchall($sql);
    }

    /**
     * 查询所有找乘客的订单总数
     * @param $weid要查询订单的公众号ID
     */
    static function getPassengerCount()
    {
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        /*        $timestamp=$_W['timestamp'];//获得当前时间戳
                $time=date('Y-m-d H:i:s', $timestamp);//将当前时间戳转化为时间格式*/
        $sql="select count(*) as count from".tablename('wx_carpool_order')."where  abc_weid_abc=$weid ";

        /*是否显示过期信息*/
        $isHidePastInfo = ParConfigModel::getIsHidePastInfo();
        if($isHidePastInfo ==0){
            /*不显示过期信息*/
            $sql.= " AND abc_departure_time_abc  > " .    "'"  .   date('Y-m-d H:i:s', $_W['timestamp'])   ."'";
        }

        $sql .="  AND abc_state_for_user_abc=1 AND abc_state_for_manager_abc=1 AND abc_order_type_abc=1 ";
        $result=pdo_fetch($sql);
        return $result['count'];
    }
    /**
     * 查询所有找司机的订单
     * @param $weid要查询订单的公众号ID
     */
    static function getDriver()
    {
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
/*        $timestamp=$_W['timestamp'];//获得当前时间戳
        $time=date('Y-m-d H:i:s', $timestamp);//将当前时间戳转化为时间格式*/
        $sql="select * from".tablename('wx_carpool_order')."where abc_weid_abc=$weid ";


        /*是否显示过期信息*/
        $isHidePastInfo = ParConfigModel::getIsHidePastInfo();
        if($isHidePastInfo ==0){
            /*不显示过期信息*/
            $sql.= " AND abc_departure_time_abc  > " .    "'"  .   date('Y-m-d H:i:s', $_W['timestamp'])   ."'";
        }

        $sql.=" AND abc_order_type_abc=0  AND abc_state_for_user_abc=1 AND abc_state_for_manager_abc=1  order by abc_isTop_abc Desc,abc_order_create_time_abc Desc";

        $p=1;
        if(intval($_GPC['p'])){
            $p=intval($_GPC['p']);
        }
        $start=($p-1)*10;
        $sql.=" limit $start,10";
        return $result=pdo_fetchall($sql);
    }

    /**
     * 查询所有找司机的订单总数
     * @param $weid要查询订单的公众号ID
     */
    static function getDriverCount()
    {
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        /*        $timestamp=$_W['timestamp'];//获得当前时间戳
                $time=date('Y-m-d H:i:s', $timestamp);//将当前时间戳转化为时间格式*/
        $sql="select count(*) as count from".tablename('wx_carpool_order')."where abc_weid_abc=$weid  ";

        /*是否显示过期信息*/
        $isHidePastInfo = ParConfigModel::getIsHidePastInfo();
        if($isHidePastInfo ==0){
            /*不显示过期信息*/
            $sql.= " AND abc_departure_time_abc  > " .    "'"  .   date('Y-m-d H:i:s', $_W['timestamp'])   ."'";
        }
        $sql.=" AND abc_order_type_abc=0  AND abc_state_for_user_abc=1 AND abc_state_for_manager_abc=1  ";

       $result=pdo_fetch($sql);
        return  $result['count'];
    }

    /**
     * 根据搜索条件查询信息
     * @param $weid要查询订单的公众号ID
     */
    static function getSearch()
    {
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        $abc_fromPlace_abc=$_GPC['fromPlace'];//获取出发地
        $abc_toPlace_abc=$_GPC['toPlace'];//获取出发地
        $where="abc_weid_abc=$weid";

        /*是否显示过期信息*/
        $isHidePastInfo = ParConfigModel::getIsHidePastInfo();
        if($isHidePastInfo ==0){
            /*不显示过期信息*/
            $where.= " AND abc_departure_time_abc  > " .    "'"  .   date('Y-m-d H:i:s', $_W['timestamp'])   ."'";
        }




        if(!empty($abc_fromPlace_abc)){
            $where.=" AND abc_place_of_departure_abc LIKE '$abc_fromPlace_abc'";
        }
        if(!empty($abc_toPlace_abc)){
            $where.=" AND abc_destination_abc LIKE '$abc_toPlace_abc'";
        }
/*        $timestamp=$_W['timestamp'];//获得当前时间戳
        $time=date('Y-m-d H:i:s', $timestamp);//将当前时间戳转化为时间格式*/

        $sql="select * from".tablename('wx_carpool_order')." where ".$where."   AND abc_state_for_user_abc=1 AND abc_state_for_manager_abc=1 order by abc_isTop_abc Desc,abc_order_create_time_abc Desc";

        $p=1;
        if(intval($_GPC['p'])>=1){
            $p=intval($_GPC['p']);
        }

        $start=($p-1)*10;

        $sql.= " limit $start,10 ";
         $result1=pdo_fetchall($sql);

        //统计信息总数
        $sql="select count(*) as count from".tablename('wx_carpool_order')." where ".$where."   AND abc_state_for_user_abc=1 AND abc_state_for_manager_abc=1 ";
        $result2=pdo_fetch($sql);
        return array($result1,$result2);
    }




    /**
     * 根据订单ID查询当前订单
     * @param $abc_id_abc要查询订单的订单ID
     */
    static function getOrder($abc_id_abc,$type=1)
    {
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        if($type==0){
            return $result=pdo_get('wx_carpool_order',array('abc_weid_abc'=>$weid,'abc_id_abc'=>$abc_id_abc));
        }else{
        return $result=pdo_get('wx_carpool_order',array('abc_weid_abc'=>$weid,'abc_id_abc'=>$abc_id_abc,'abc_state_for_manager_abc'=>1,'abc_state_for_user_abc'=>1));
        }
    }

    /**
     * 加一点击量
     * @param $abc_id_abc添加点击量订单的订单ID
     */
    static function AddClick()
    {
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        $abc_id_abc=$_GPC['abc_id_abc'];//获取订单ID
        $sql="UPDATE".tablename('wx_carpool_order')." SET abc_attention_degree_abc=abc_attention_degree_abc+1 WHERE abc_weid_abc=$weid AND abc_id_abc=$abc_id_abc AND abc_state_for_manager_abc=1 AND abc_state_for_user_abc=1 ";
        pdo_fetchall($sql);
    }

    /**
     * 更新所有订单置顶状态
     * @param
     */
    static function updataIstop()
    {
        global $_W,$_GPC;
        $abc_weid_abc = $_W['uniacid'];               //获取当前公众号ID
        $abc_timestamp_abc=$_W['timestamp'];//获得当前时间戳
        $abc_time_one_abc=date('Y-m-d H:i:s', $abc_timestamp_abc-24*60*60);//将当前一天前的时间戳转化为时间格式
        $abc_time_three_abc=date('Y-m-d H:i:s', $abc_timestamp_abc-24*60*60*3);//将当前3天前的时间戳转化为时间格式
        pdo_fetchall("UPDATE ".tablename('wx_carpool_order')." SET abc_isTop_abc=0  WHERE abc_weid_abc=$abc_weid_abc AND abc_isTop_abc=1 AND abc_order_create_time_abc<'$abc_time_one_abc' ");
        pdo_fetchall("UPDATE ".tablename('wx_carpool_order')." SET abc_isTop_abc=0  WHERE abc_weid_abc=$abc_weid_abc AND abc_isTop_abc=2 AND abc_order_create_time_abc<'$abc_time_three_abc' ");
    }

    /**
     * 创建订单
     * @param   $data 要创建订单的值
     */
    static function createOrder($abc_data_abc)
    {
        return pdo_insert('wx_carpool_order',$abc_data_abc);
    }

    /**
     * 用户删除过期订单
     * @param   $abc_id_abc要删除订单的ID
     */
    static function delUserOrder($abc_id_abc,$type=1)
    {
        if ($type==0){
        return pdo_update('wx_carpool_order',
            array("abc_state_for_user_abc"=>-1),
            array("abc_id_abc"=>$abc_id_abc));
        }elseif ($type==1){
            return pdo_update('wx_carpool_order',
                array("abc_state_for_manager_abc"=>-1),
                array("abc_id_abc"=>$abc_id_abc));
        }
    }

    /**
     * 用户删除过期订单
     * @param   $abc_id_abc要恢复订单的ID
     */
    static function getOpenidByOid($abc_id_abc)
    {
       $result=pdo_get('wx_carpool_order',
            array("abc_id_abc"=>$abc_id_abc),
            abc_openid_abc);
        return $result['abc_openid_abc'];
    }

    /**
     * 用户删除过期订单
     * @param   $abc_id_abc要恢复订单的ID
     */
    static function recoveryUserOrder($abc_id_abc)
    {
            return pdo_update('wx_carpool_order',
                array("abc_state_for_manager_abc"=>1),
                array("abc_id_abc"=>$abc_id_abc));
    }

}