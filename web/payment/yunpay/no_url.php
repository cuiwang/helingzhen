<?php
/* *
 * 功能：服务器异步通知页面
 */
require '../../../../framework/bootstrap.inc.php';

require_once("yun.config.php");
require_once("lib/yun_md5.function.php");
global $_GPC;
//计算得出通知验证结果
$yunNotify = md5Verify($_REQUEST['i1'],$_REQUEST['i2'],$_REQUEST['i3'],$yun_config['key'],$yun_config['partner']);

if($yunNotify) {//验证成功
	/////////////////////////////////////////////////////////
	
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
            header("location:".$_W["siteroot"]."web/index.php?c=members&a=mgetPayResult&order_no=".$out_trade_no);
            pdo_rollback();
            return;
        }else{
			
			$hxmember=pdo_fetch("SELECT * FROM ".tablename("buymod_members")." WHERE uid = :uid", array(":uid"=>$order['uid']));
			
			$totalcredit=$hxmember['credit']+$order["credit"];
			
			if(!empty($hxmember)){
				
				if(pdo_update("buymod_members",array("credit"=>$totalcredit,"createtime"=>TIMESTAMP, "order_no"=>$trade_no),array("uid"=>$order["uid"]))>0){
					
					pdo_commit();
                    header("location:".$_W["siteroot"]."web/index.php?c=members&a=mgetPayResult&order_no=".$out_trade_no);
                    return;
					
					}
				
				}
			
			}
		
    
        
	echo "success";		//请不要修改或删除
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";//请不要修改或删除

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>