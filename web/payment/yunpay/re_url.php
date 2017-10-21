<?php
/* *
 * 功能：服务器同通知页面
 */
require_once("yun.config.php");
require_once("lib/yun_md5.function.php");
global $_GPC;
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//计算得出通知验证结果
$yunNotify = md5Verify($_REQUEST['i1'],$_REQUEST['i2'],$_REQUEST['i3'],$yun_config['key'],$yun_config['partner']);
if($yunNotify) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//商户订单号

	$out_trade_no = $_REQUEST['i2'];

	//云支付交易号

	$trade_no = $_REQUEST['i4'];

	//价格
	$yunprice=$_REQUEST['i1'];


    /*
加入您的入库及判断代码;
判断返回金额与实金额是否想同;
判断订单当前状态;
完成以上才视为支付成功
*/

  $order = pdo_fetch("SELECT * FROM ".tablename("buymod_payrecords")." WHERE orderid = :orderid", array(":orderid"=>$out_trade_no));
   
   if(empty($order) || $order["status"] == 1){
            header("location:".$_SERVER['HTTP_HOST']."../../../../../../web/index.php?c=members&a=mgetPayResult&order_no=".$out_trade_no);
            pdo_rollback();
            return;
        }
		
		if(pdo_update("buymod_payrecords",array("status"=>1,"createtime"=>TIMESTAMP, "order_no"=>$trade_no),array("orderid"=>$out_trade_no)) > 0){
			
			$hxmember=pdo_fetch("SELECT * FROM ".tablename("buymod_members")." WHERE uid = :uid", array(":uid"=>$order['uid']));
			
			$totalcredit=$hxmember['credit']+$order["credit"];
			
			if(!empty($hxmember)){
				
				if(pdo_update("buymod_members",array("credit"=>$totalcredit,"createtime"=>TIMESTAMP),array("uid"=>$order["uid"]))>0){
					
					pdo_commit();
                    header("location:".$_SERVER['HTTP_HOST']."../../../../../../web/index.php?c=members&a=mgetPayResult&order_no=".$out_trade_no);
                    return;
					
					}
				
				}
            pdo_rollback();
        }
		
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "验证失败";
}
?>
        <title>云支付接口</title>
	</head>
    <body>
    </body>
</html>