<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2016/12/24
 * Time: 11:26
 */
require_once(dirname(__FILE__) . "/../../model/picConfig.php");
global $_GPC,$_W;
$weid=$_W['uniacid'];//获取当前公众号
$abc_id_abc=$_GPC['abc_id_abc'];//获取图片ID
$abc_path_abc=$_GPC['carousel_pic'];//获取上传图片
$abc_title_abc=$_GPC['title'];//获取输入的图片标题
$abc_link_abc=$_GPC['link'];//获取输入的点击图片跳转链接
$abc_data_abc=array(
    'abc_title_abc'=>$abc_title_abc,
    'abc_path_abc'=>$abc_path_abc,
    'abc_link_abc'=>$abc_link_abc
);
//判断是否存在图片ID
if(empty($abc_id_abc)){
    $abc_data_abc['abc_weid_abc']=$weid;
 PicConfigModel::add($abc_data_abc);//不存在则执行添加
    message('操作成功！', '../../web/' .  $this->createWebUrl('carousel'));
}else{
    PicConfigModel::updata($abc_data_abc);//存在则执行更新
    message('操作成功！', '../../web/' .  $this->createWebUrl('carousel'));
}
