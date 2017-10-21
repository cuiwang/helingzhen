<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/25
 * Time: 12:42
 */
require_once(dirname(__FILE__) . "/../../model/parConfig.php");
require_once(dirname(__FILE__) . "/../../model/user.php");
global $_GPC,$_W;
$img=1;
$level = $_W['account']['level'];//获取公众号类型
$abc_openid_abc=$_W['openid'];//获取用户openid
$abc_information_abc = $_W['fans'];//获取用户信息
if ($level != 4) $abc_information_abc = mc_fansinfo($openid);
if(empty($abc_openid_abc)||$abc_information_abc['follow']=='0'){
    $this->doMobilePrompt();
    exit;
}
$abc_user_abc=userModel::getUserByOpenId($abc_openid_abc);//获取当前用户信息
$abc_price_looking_for_passengers_abc=ParConfigModel::getPrice1();//获取车找人价格
$abc_isTop_price1_abc=ParConfigModel::getIsTopPrice1();//获取车找人置顶1天价格
$abc_isTop_price2_abc=ParConfigModel::getIsTopPrice2();//获取车找人置顶3天价格
$abc_price_looking_for_driver_abc=ParConfigModel::getPrice2();//获取人找车价格
$abc_isTop_price3_abc=ParConfigModel::getIsTopPrice3();//获取人找车置顶1天价格
$abc_isTop_price4_abc=ParConfigModel::getIsTopPrice4();//获取人找车置顶3天价格

$abc_wx_name_abc=ParConfigModel::getWxName();//获取当前配置的平台名称
$abc_role_text_abc=ParConfigModel::getWxText();//获取配置规则文本

$isOpenRecharge= ParConfigModel::getIsOpenRecharge(); //获得是否打开充值提示


$isStick = ParConfigModel::getIsStick(); //获得是否允许发布置顶信息





include $this->template('form');