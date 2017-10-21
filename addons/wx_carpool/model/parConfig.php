<?php
/**
 * Created by PhpStorm.
 * User: start
 * Date: 2016/12/25
 * Time: 13:44
 */




class ParConfigModel{

    /**
     * 获得手机端平台名称
     */
    static function getWxName(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'wx_name','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return " ";
        }else{
            /*返回配置*/
            return  $ret['abc_value_abc'];
        }
    }

    /**
     * 设置手机端平台名称
     */
    static function setWxName($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'wx_name','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'wx_name',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }


    /**
     * 获得手机端配置文本
     */
    static function getWxText(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'wx_role_text','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return " ";
        }else{
            /*返回配置*/
            return  $ret['abc_value_abc'];
        }
    }

    /**
     * 设置手机端配置文本
     */
    static function setWxText($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'wx_role_text','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'wx_role_text',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }









    /**
     * 获得微信端拼车品台LOGO
     */
    static function getWxLogo(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'wx_logo','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return " ";
        }else{
            /*返回配置*/
            return  $ret['abc_value_abc'];
        }
    }

    /**
     * 设置微信端拼车平台LOGO
     */
    static function setWxLogo($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'wx_logo','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'wx_logo',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }


    /**
     * 获得微信关注二维码
     */
    static function getWxCode(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID
        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'wx_code_img','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return " ";
        }else{
            /*返回配置*/
            return  $ret['abc_value_abc'];
        }
    }

    /**
     * 设置微信二维码图片
     */
    static function setWxCode($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'wx_code_img','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'wx_code_img',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }


    /**
     * 获取新用户赠送的积分
     */
    static  function getNewUserPresent(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'new_user_present','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 0;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }



    /**
     * 设置新用户赠送的积分
     */
    static  function setNewUserPresent($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'new_user_present','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'new_user_present',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }


    /**
     * 获取价格1 （车找人的价格）
     */
    static function getPrice1(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'price1','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 1;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }

    /**
     * 设置价格1 （车找人的价格）
     */
    static function stePrice1($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'price1','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'price1',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }
    /**
     * 获取价格 车找人置顶1天价格
     */
    static function getIsTopPrice1(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isTopPrice1','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 5;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }
    /**
     * 设置车找人置顶一天的价格
     */
    static function steIsTopPrice1($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isTopPrice1','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'isTopPrice1',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }

    /**
     * 获取价格车找人置顶3天价格
     */
    static function getIsTopPrice2(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isTopPrice2','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 10;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }
    /**
     * 设置车找人置顶三天的价格
     */
    static function steIsTopPrice2($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isTopPrice2','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'isTopPrice2',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }



    /**
     * 获取价格2 （人找车的价格）
     */
    static function getPrice2(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'price2','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 1;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }

    /**
     * 设置价格2 （人找车的价格）
     */
    static function stePrice2($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'price2','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'price2',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }


    /**
     * 获取价格人找车置顶1天价格
     */
    static function getIsTopPrice3(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isTopPrice3','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 5;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }
    /**
     * 设置人找车置顶一天的价格
     */
    static function steIsTopPrice3($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isTopPrice3','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'isTopPrice3',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }

    /**
     * 获取价格人找车置顶3天价格
     */
    static function getIsTopPrice4(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isTopPrice4','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 10;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }
    /**
     * 设置人找车置顶3天的价格
     */
    static function steIsTopPrice4($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isTopPrice4','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'isTopPrice4',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }

    /**
     * 获取是否打开充值
     */
    static function getIsOpenRecharge(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isOpenRecharge','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 1;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }

    /*
     * 设置是否打开充值
     */
    static function setIsOpenRecharge($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isOpenRecharge','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'isOpenRecharge',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }



    /**
     * 获取是否支持发布置顶信息
     */
    static function getIsStick(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isStick','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 1;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }


    /**
     * 设置是否支持置顶信息的发布
     */
    static function setIsStick($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isStick','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'isStick',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }


    /**
     * 获取是否显示过期信息
     */
    static function  getIsHidePastInfo(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isHidePastInfo','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return 1;
        }else{
            /*返回配置*/
            return  intval($ret['abc_value_abc']);
        }
    }

    /**
     * 设置是否显示过期信息
     */
    static function setIsHidePastInfo($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'isHidePastInfo','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'isHidePastInfo',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }



    /**
     * 获取模版消息ID
     */
    static function  getTplId(){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'TplId','abc_weid_abc'=>$weid ));
        if(!$ret) {
            /*配置不存在返回默认值*/
            return "";
        }else{
            /*返回配置*/
            return  $ret['abc_value_abc'];
        }
    }



    /**
     * 设置模版消息
     */
    static function setTplId($value){
        global $_W,$_GPC;
        $weid = $_W['uniacid'];               //获取当前公众号ID

        /*试图查询配置*/
        $ret= pdo_get('wx_carpool_textconfig', array('abc_text_abc' =>'TplId','abc_weid_abc'=>$weid ));

        if(!$ret){
            /*配置不存在插入新配置*/
            pdo_insert('wx_carpool_textconfig',array(
                'abc_weid_abc'=>$weid,
                'abc_text_abc' =>'TplId',
                'abc_value_abc'=>(string)$value
            ));
        }else{
            /*配置已经存在更配置*/
            pdo_update('wx_carpool_textconfig',
                array('abc_value_abc'=>(string)$value),
                array('abc_id_abc'=>$ret['abc_id_abc']));
        }
    }








}