<?php

/**
 * 参数配置相关
 */
require_once(dirname(__FILE__) . "/../../model/parConfig.php");
global $_GPC,$_W;

if(isset($_GPC['abc_name_abc'])){
    ParConfigModel::setWxName($_GPC['abc_name_abc']);
}

if(isset($_GPC['abc_newUserPresent_abc'])){
    ParConfigModel::setNewUserPresent($_GPC['abc_newUserPresent_abc']);
}
if(isset($_GPC['abc_price1_abc'])){
    ParConfigModel::stePrice1   ($_GPC['abc_price1_abc']);
}

if(isset($_GPC['abc_isTop_price1_abc'])){
    ParConfigModel::steIsTopPrice1  ($_GPC['abc_isTop_price1_abc']);
}

if(isset($_GPC['abc_isTop_price2_abc'])){
    ParConfigModel::steIsTopPrice2   ($_GPC['abc_isTop_price2_abc']);
}

if(isset($_GPC['abc_price2_abc'])){
    ParConfigModel::stePrice2($_GPC['abc_price2_abc']);
}

if(isset($_GPC['abc_isTop_price3_abc'])){
    ParConfigModel::steIsTopPrice3   ($_GPC['abc_isTop_price3_abc']);
}

if(isset($_GPC['abc_isTop_price4_abc'])){
    ParConfigModel::steIsTopPrice4   ($_GPC['abc_isTop_price4_abc']);
}

if(isset($_GPC['abc_wx_logo_abc'])){
    ParConfigModel::setWxLogo($_GPC['abc_wx_logo_abc']);
}

if(isset($_GPC['abc_wx_code_abc'])){
    ParConfigModel::setWxCode($_GPC['abc_wx_code_abc']);
}

if(isset($_GPC['abc_role_text_abc'])){
    ParConfigModel::setWxText($_GPC['abc_role_text_abc']);
}

if(isset($_GPC['isOpenRecharge']) ){
    ParConfigModel::setIsOpenRecharge($_GPC['isOpenRecharge']);
}

if(isset($_GPC['isStick']) ){
    ParConfigModel::setIsStick($_GPC['isStick']);
}

if(isset($_GPC['isHidePastInfo']) ){
    ParConfigModel::setIsHidePastInfo($_GPC['isHidePastInfo']);
}

if(isset($_GPC['TplId'])){

    ParConfigModel::setTplId($_GPC['TplId']);
}


/*取出新用户赠送的积分*/
$abc_name_abc=ParConfigModel::getWxName();

/*取出新用户赠送的积分*/
$abc_newUserPresent_abc=ParConfigModel::getNewUserPresent();

/*取出车找人信息发布价格*/
$abc_price1_abc=ParConfigModel::getPrice1();

/*取出车找人置顶1天价格*/
$abc_isTop_price1_abc=ParConfigModel::getIsTopPrice1();

/*取出车找人置顶3天价格*/
$abc_isTop_price2_abc=ParConfigModel::getIsTopPrice2();

/*取出人找车信息发布价格*/
$abc_price2_abc=ParConfigModel::getPrice2();

/*取出人找车置顶1天价格*/
$abc_isTop_price3_abc=ParConfigModel::getIsTopPrice3();

/*取出人找车置顶3天价格*/
$abc_isTop_price4_abc=ParConfigModel::getIsTopPrice4();

/*获得微信二维码图片*/
$abc_wx_code_abc=ParConfigModel::getWxCode();

/*获得微信平台LOGO*/
$abc_wx_logo_abc=ParConfigModel::getWxLogo();

/*取出手机端规则文本*/
$abc_role_text_abc=ParConfigModel::getWxText();

$isOpenRecharge = ParConfigModel::getIsOpenRecharge();

$isStick = ParConfigModel::getIsStick(); /*是否允许发布置顶信息*/

$isHidePastInfo  = ParConfigModel::getIsHidePastInfo(); /*是否显示隐藏信息*/

$TplId = ParConfigModel::getTplId(); /*获得模版消息ID*/


include $this->template('parconf');