<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/24
 * Time: 13:24
 */

require_once(dirname(__FILE__) . "/../../model/picConfig.php");
global $_GPC,$_W;
$abc_id_abc=$_GPC['abc_id_abc'];//获取图片ID
if (PicConfigModel::del($abc_id_abc)){
    message('操作成功！', '../../web/' .  $this->createWebUrl('carousel'));
}else{
    message('操作失败！', '../../web/' .  $this->createWebUrl('carousel'));
}
