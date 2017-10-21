<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/6 0006
 * Time: 下午 1:46
 */
require_once(dirname(__FILE__) . "/../../model/comment.php");

global $_GPC;

$id =$_GPC['id'];


if (commentModel::del($id)){
    message('操作成功','../web/'.$this->createWebUrl('manager7'));
}else{
    message('操作失败');
}
