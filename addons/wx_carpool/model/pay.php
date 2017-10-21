<?php

/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/31
 * Time: 13:32
 */
class payModel
{
    /**
     * 创建一个新的支付记录
     * @param    $abc_data_abc要插入新支付记录的数据
     */
    static function createPay($abc_data_abc)
    {
        return pdo_insert('wx_carpool_pay',$abc_data_abc);
    }

    /**
     * 更新支付记录支付状态
     * @param    $abc_data_abc要更新的支付状态
     */
    static function updatePay($abc_data_abc)
    {
        global $_GPC,$_W;
        $abc_weid_abc=$_W['uniacid'];//获取当前公众号ID
        $abc_openid_abc=$_W['openid'];//获得当前用户ID
        $abc_pid_abc=$_GPC['abc_pid_abc'];//获取支付ID

/*        var_dump($abc_data_abc);*/

        return pdo_update('wx_carpool_pay',$abc_data_abc,
            array(
/*                'abc_openid_abc'=>$abc_openid_abc,*/
                'abc_weid_abc'=>$abc_weid_abc,
                'abc_order_id_abc'=>$abc_pid_abc,
            ));
    }

    /**
     * 获取支付订单充值金额信息
     * @param
     */
    static function getPay()
    {
        global $_GPC,$_W;
        $abc_weid_abc=$_W['uniacid'];//获取当前公众号ID
        $abc_pid_abc=$_GPC['abc_pid_abc'];//获取支付ID
        return pdo_get('wx_carpool_pay',array(
            'abc_order_id_abc'=>$abc_pid_abc,
            'abc_weid_abc'=>$abc_weid_abc,
        ),'abc_num_abc');
    }



    /**
     * 获取支付订单总数
     * @param
     */
    static function getPayCount($start,$end)
    {
        global $_W;
        $abc_weid_abc=$_W['uniacid'];//获取当前公众号ID

        $where="where abc_weid_abc=$abc_weid_abc" ;
        if (isset($start)){
            $where.=" AND abc_create_time_abc>='$start'";
        }
        if (isset($end)){
            $where.=" AND abc_create_time_abc<='$end'";
        }
        $sql="select count(*) as count from ".tablename('wx_carpool_pay').$where;
        return pdo_fetch($sql);
    }
    /**
     * 根据分页获取所有支付订单信息
     * @param
     */
    static function getPayPaging($abc_start_abc,$abc_end_abc)
    {
        global $_GPC,$_W;
        $abc_weid_abc=$_W['uniacid'];//获取当前公众号ID
        $where=" where abc_weid_abc=$abc_weid_abc";
        if(isset($abc_start_abc)){
            $where.=" AND abc_create_time_abc>='$abc_start_abc'";
        }
        if(isset($abc_end_abc)){
            $where.=" AND abc_create_time_abc<='$abc_end_abc'";

        }


        $sql="select * from ".tablename('wx_carpool_pay').$where;
        $p=1;
        if (intval($_GPC['p']>=1)){
            $p=intval($_GPC['p']);
        }
        $start=($p-1)*10;
        $sql.=" limit $start,10";
        return pdo_fetchall($sql);

    }

}