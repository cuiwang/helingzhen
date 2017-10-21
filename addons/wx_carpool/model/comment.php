<?php

/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2017/3/5
 * Time: 17:25
 */
class commentModel
{
    //根据订单ID新增一条评论
    static function addComment($data){
        if (!isset($data)) return 0;
        return pdo_insert('wx_carpool_comment',$data);
    }



    /*获得查询条件*/
    static function getWhere(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        $where= " where abc_weid_abc= $weid";
        return $where;
    }


    /*获得订单的数量*/
    static function getListCount(){
        global $_W,$_GPC;
        $table_wx_carpool_comment=tablename('wx_carpool_comment');
        $sql="select count(*) as count from $table_wx_carpool_comment ";
        $sql.=self::getWhere();
        $result= pdo_fetch($sql);
        return $result['count'];
    }


    static function del($id){

        return pdo_delete('wx_carpool_comment',array(
            'abc_id_abc'=>$id
        ));

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
        $table_wx_carpool_order=tablename('wx_carpool_comment');
        $sql="select  * from $table_wx_carpool_order ";
        $abc_pagesize_abc=10;
        $abc_start_abc=(($p-1)*$abc_pagesize_abc);
        $abc_end_abc=$abc_start_abc+$abc_pagesize_abc;
        $sql.=self::getWhere();
        $sql.=" limit $abc_start_abc , 10";
        $result= pdo_fetchall($sql);
        return $result;
    }




    //根据订单ID获取所有评论
    static function allComment($abc_order_id_abc,$abc_num_abc){
        global $_W;
        $abc_weid_abc = $_W['uniacid'];               //获取当前公众号ID
        if (!isset($abc_order_id_abc)) return "";
        $sql="select count(*) as count  from".tablename('wx_carpool_comment')."where abc_order_id_abc=$abc_order_id_abc AND abc_weid_abc=$abc_weid_abc";
        $count=pdo_fetch($sql);
        if ($abc_num_abc==5){
            $sql="select *  from".tablename('wx_carpool_comment')."where abc_order_id_abc=$abc_order_id_abc AND abc_weid_abc=$abc_weid_abc order by abc_create_time_abc DESC limit 0,5";
            $result=pdo_fetchall($sql);
            return array($count['count'],$result);
        }else{
            $sql="select * from".tablename('wx_carpool_comment')."where abc_order_id_abc=$abc_order_id_abc AND abc_weid_abc=$abc_weid_abc order by abc_create_time_abc DESC";
            $result=pdo_fetchall($sql);
            return array($count['count'],$result);
        }
    }

}