<?php
class WX_message{
	 public function WX_request($url,$data=null){
       $curl = curl_init(); // 启动一个CURL会话
       curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
       curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
       if($data != null){
           curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
           curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
       }
       curl_setopt($curl, CURLOPT_TIMEOUT, 300); // 设置超时限制防止死循环
       curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
       $info = curl_exec($curl); // 执行操作
       if (curl_errno($curl)) {
           echo 'Errno:'.curl_getinfo($curl);//捕抓异常
           dump(curl_getinfo($curl));
       }
       return $info;
   }
 public function myrefund($transid){
 			global $_W,$_GPC;
			include_once 'WxPay.Api.php';
			$WxPayApi = new WxPayApi();
			$input = new WxPayRefund();
			load() -> func('communication');
			load()->model('account');
			$accounts = uni_accounts();
				$acid = $_W['uniacid'];
				$path_cert = '../addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_cert.pem';//证书路径
				$path_key = '../addons/feng_fightgroups/cert/'.$_W['uniacid'].'/apiclient_key.pem';//证书路径
				$key=$this->module['config']['apikey'];//商户支付秘钥（API秘钥）
				$appid=$accounts[$acid]['key'];//身份标识（appid）
//	 			$appsecret = $accounts[$acid]['secret'];//身份密钥（appsecret）
	 			$mchid=$this->module['config']['mchid'];//微信支付商户号(mchid)
	 			$order_out = pdo_fetch("select * from".tablename('tg_order') . "where transid = '{$transid}'");
				$fee = $order_out['price']*100;//退款金额
				$refundid = $transid;//微信订单号
				message("key=".$key."appid=".$appid."mchid=".$mchid."fee=".$fee."refundid=".$refundid);exit;
				/*$input：退款必须要的参数*/
				$input->SetAppid($appid);
				$input->SetMch_id($mchid);
				$input->SetOp_user_id($mchid);
				$input->SetOut_refund_no($mchid.date("YmdHis"));
				$input->SetRefund_fee($fee);
				$input->SetTotal_fee($fee);
				$input->SetTransaction_id($refundid);
				$result=$WxPayApi->refund($input,6,$path_cert,$path_key,$key);
				if($result['return_code'] == 'SUCCESS'){
					return 'success';
				}else{
					return 'fail';
				}
	
}
}

?>