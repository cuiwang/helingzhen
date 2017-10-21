<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/28
 * Time: 17:44
 */
require_once(dirname(__FILE__) . "/../../model/parConfig.php");
$abc_pic_abc=ParConfigModel::getWxCode();//获取公众号二维码
include $this->template('prompt');