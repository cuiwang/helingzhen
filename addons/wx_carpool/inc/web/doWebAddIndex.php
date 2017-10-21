<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/24
 * Time: 13:26
 */
require_once(dirname(__FILE__) . "/../../model/picConfig.php");
global $_W,$_GPC;
$abc_id_abc=$_GPC['abc_id_abc'];//获取图片ID
$abc_data_abc=PicConfigModel::get($abc_id_abc);//根据图片ID 获取图信息
include $this->template('addcarousel');