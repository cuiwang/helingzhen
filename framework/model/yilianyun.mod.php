<?php 
header("Content-type: text/html; charset=utf-8"); 
$msg          = $_REQUEST['printContent']; //打印内容
$apiKey       = $_REQUEST['appkey'];//apiKey
$mKey         = $_REQUEST['serect'];//秘钥
$partner      = $_REQUEST['num'];//用户id
$machine_code = $_REQUEST['code'];//打印机终端号
$ti = time();
    $params = array(
            'partner'=>$partner,
            'machine_code'=>$machine_code,
            'time'=>$ti
            
    );
    $sign = generateSign($params,$apiKey,$mKey);

    $params['sign'] = $sign;
    $params['content'] = $msg;

  
  $url = 'open.10ss.net:8888';//接口端点

    $p = '';
    foreach ($params as $k => $v) {
        $p .= $k.'='.$v.'&';
    }
    $data = rtrim($p, '&');
	
   liansuo_post($url,$data);
function liansuo_post($url,$data){ // 模拟提交数据函数      
    $curl = curl_init(); // 启动一个CURL会话      
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址                  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在      
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); //解决数据包大不能提交     
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转      
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer      
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求      
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包      
    curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']); // 读取上面所储存的Cookie信息      
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循     
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容      
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回 
           
    $tmpInfo = curl_exec($curl); // 执行操作      
    if (curl_errno($curl)) {      
       echo 'Errno'.curl_error($curl);      
    }      
    curl_close($curl); // 关键CURL会话      
    return $tmpInfo; // 返回数据      
}    

function generateSign($params, $apiKey, $msign)
{
    //所有请求参数按照字母先后顺序排
    ksort($params);
    //定义字符串开始所包括的字符串
    $stringToBeSigned = $apiKey;
    //把所有参数名和参数值串在一起
    foreach ($params as $k => $v)
    {
        $stringToBeSigned .= urldecode($k.$v);
    }
    unset($k, $v);
    //定义字符串结尾所包括的字符串
    $stringToBeSigned .= $msign;
    //使用MD5进行加密，再转化成大写
    return strtoupper(md5($stringToBeSigned));
}
?>