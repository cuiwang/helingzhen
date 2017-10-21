<?php

/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/24
 * Time: 11:10
 */
class PicConfigModel
{
   /**
     * 添加修改图片
     * @param $data 要添加的数据
     */
    static function add($data)
    {

        return pdo_insert('wx_carpool_picconfig', $data);
    }
    /**
     * 更新修改图片
     * @param $data要更新的数据
     */
    static function updata($data)
    {
        global $_W,$_GPC;
        $abc_id_abc=$_GPC['abc_id_abc'];//获取图片ID
        $weid = $_W['uniacid'];               //获取当前公众号ID
        return $result=pdo_update('wx_carpool_picconfig',$data,array('abc_id_abc'=>$abc_id_abc,'abc_weid_abc' => $weid));
    }
    /**
     * 获取图片
     * @param $weid 要获取配置图片的公众号ID
     */
    static function getall($weid)
    {
        return pdo_getall('wx_carpool_picconfig', array('abc_weid_abc' => $weid));
    }
    /**
     * 获取图片
     * @param $id 要获取配置图片的ID
     */
    static function get($id)
    {
        return pdo_get('wx_carpool_picconfig', array('abc_id_abc' => $id));
    }
    /**
     * 删除图片
     * @param $id 要删除配置图片的ID
     */
    static function del($id)
    {
        global $_W;
        $weid=$_W['uniacid'];//获取当前公众号
        return pdo_delete('wx_carpool_picconfig', array('abc_id_abc' => $id,'abc_weid_abc'=>$weid));
    }

}