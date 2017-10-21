<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/24
 * Time: 11:02
 */

require_once(dirname(__FILE__) . "/../../model/picConfig.php");
global $_W,$_GPC;
$openid = $_W['openid'];//获取单前用户ID
$weid=$_W['uniacid'];//获取当前公众号ID
$abc_datas_abc=PicConfigModel::getall($weid);//查询当前公众号配置轮播图片
include $this->template('carousel');