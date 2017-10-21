<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/24
 * Time: 21:45
 */
require_once(dirname(__FILE__) . "/../../model/picConfig.php");
require_once(dirname(__FILE__) . "/../../model/parConfig.php");
require_once(dirname(__FILE__) . "/../../model/user.php");
require_once(dirname(__FILE__) . "/../../model/order.php");
global $_W,$_GPC;
$img=0;
$weid=$_W['uniacid'];//获取当前公众号ID
$abc_op_abc=$_GPC['abc_op_abc'];//获取操作类型
$abc_user_num_abc=userModel::getUserCount();//获取用户总数
/*$abc_order_count_abc=orderModel::getListCount();//获取订单总数*/
orderModel::updataIstop();//更新所有置顶订单置顶状态

$abc_wx_name_abc=ParConfigModel::getWxName();//获取当前配置的平台名称
$abc_wx_logo_abc=ParConfigModel::getWxLogo();//获取当前微信端LOGO
$abc_pics_abc=PicConfigModel::getall($weid);//获取当前公众号所有轮播图片信息
include $this->template('index');