<?php
/**
 * Created by PhpStorm.
 * User: DoubleWei
 * Date: 2017/3/5
 * Time: 11:52
 */
require_once(dirname(__FILE__) . "/../../model/comment.php");
require_once(dirname(__FILE__) . "/../../model/parConfig.php");
require_once(dirname(__FILE__) . "/../../model/order.php");


global $_GPC,$_W;

$weid = $_W['uniacid'];               //获取当前公众号ID
$abc_openid_abc=$_W['openid'];//获取用户openid
$abc_information_abc = $_W['fans'];//获取用户信息
$img = isset($abc_information_abc['tag']['avatar'])?$abc_information_abc['tag']['avatar']:"";
$abc_order_id_abc=$_GPC['orderId'];
$abc_comment_abc=$_GPC['content'];
$nickname  = $_W['fans']['nickname'];
$abc_timestamp_abc=$_W['timestamp'];//获得当前时间戳
$abc_time_one_abc=date('Y-m-d H:i:s', $abc_timestamp_abc);//将当前一天前的时间戳转化为时间格式
if (isset($abc_openid_abc)){
    $data=array(
        'abc_weid_abc'=>$weid,
        'abc_order_id_abc'=>$abc_order_id_abc,
        'abc_comment_abc'=>$abc_comment_abc,
        'abc_create_time_abc'=>$abc_time_one_abc,
        'abc_comment_user_openid_abc'=>$abc_openid_abc,
        'abc_nickname_abc'=>$nickname,
        'abc_img_abc'=>$img
    );
    $result=commentModel::addComment($data);
    if ($result){

        $tplid= ParConfigModel::getTplId();

        if($tplid!=""){
            $order = orderModel::getOrder($abc_order_id_abc,0);
            $openid = $order['abc_openid_abc'];
            $content = array(
                'first'    => array(
                    'value' => '你好，你有一条客户留言',
                ),
                'keyword1' => array(
                    'value' => $abc_comment_abc,
                ),
                'keyword2' => array(
                    'value' => date("Y-m-d H:i,TIMESTAMP"),
                ),
                'remark'   => array(
                    'value' => '请登录“我的消息”页面进行处理，谢谢。！',
                ),
            );

            $this->sendtpl($openid,'',$tplid,$content);
        }


        echo json_encode(array('status'=>true,'result'=>"评论成功"));
    }else{
        echo json_encode(array('status'=>false,'result'=>'评论错误请重试'));
    }

}else{
    echo json_encode(array('status'=>false,'result'=>'请先关注公众号'));
}