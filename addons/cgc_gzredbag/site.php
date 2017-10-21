<?php
/**
 * 关注送红包模块处理程序
 * 
 */
defined('IN_IA') or exit('Access Denied');

require_once IA_ROOT . "/addons/cgc_gzredbag/WxPay/WxPayPubHelper.php";

class Cgc_gzredbagModuleSite extends WeModuleSite {
  function doWebDatamanage(){
  	global $_W, $_GPC;
  	load()->func('tpl');
  	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
  	$uniacid=$_W["uniacid"];
  	$id=$_GPC['id'];
  	if ($op=='display') {
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
		if (!empty($_GPC['openid'])){
			$con=" and openid='{$_GPC['openid']}'";
		}
		$list = pdo_fetchall("SELECT *  from ".tablename('gzredbag_user')."  where uniacid=$uniacid  $con order by id desc LIMIT ". ($pindex -1) * $psize . ',' .$psize);
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('gzredbag_user')."  where uniacid=$uniacid $con");
		$pager = pagination($total, $pindex, $psize);
  	}
  	if ($op=='post') {
  		if (!empty($id)) {
  		$item = pdo_fetch("SELECT *  from ".tablename('gzredbag_user')."  where uniacid=$uniacid and id=$id");
  		if (empty($item)) {
				message('抱歉，红包不存在或是已经删除！', '', 'error');
			}
		}
		if (checksubmit('submit')) {
			$data = array(
			    'uniacid' => $_W['uniacid'],
			    'openid'=> $_GPC['openid'],
				'nickname' => $_GPC['nickname'],
				'headimgurl' => $_GPC['headimgurl'],
				'status' => $_GPC['status'],
				'money' => $_GPC['money'],
				'createtime' => time()
			);
				pdo_update('gzredbag_user', $data, array('id' => $id));
			
			message('红包更新成功！', $this->createWebUrl('Datamanage'), 'success');
			}
		} 
		if ($op=='delete') {
			pdo_delete('gzredbag_user', array('id' => $id));
            message('删除成功！', $this->createWebUrl('Datamanage', array('op' => 'display')), 'success');
		}
    if ($op=='delete_all') {
      pdo_delete('gzredbag_user');
            message('删除成功！', $this->createWebUrl('Datamanage', array('op' => 'display')), 'success');
    }

  	include $this->template('datamanage');
  }

   function doWebgzredbag_money(){
   	global $_W, $_GPC;
   	$settings=$this->module['config'];
   	$total_mmoney=$settings["total_money"];
  	load()->func('tpl');
  	$uniacid=$_W["uniacid"];
  	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
  	$id=intval($_GPC['id']);
  	if ($op=='display') {
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
		$list = pdo_fetchall("SELECT *  from ".tablename('gzredbag_money')."  where uniacid=$uniacid order by id desc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('gzredbag_money')."  where uniacid=$uniacid ");
		$pager = pagination($total, $pindex, $psize);
  	}

  	if ($op=='delete') {
			pdo_delete('gzredbag_money', array('id' => $id));
            message('删除成功！', $this->createWebUrl('gzredbag_money', array('op' => 'display')), 'success');
	}
	
	if ($op=='post') {
	   $item = pdo_fetch("SELECT *  from ".tablename('gzredbag_money')."  where uniacid=$uniacid and id=$id");
	  
		if (checksubmit('submit')) {
		$data=array("total_money"=>$_GPC['total_money']);
		pdo_update('gzredbag_money',$data, array('id' => $id));
        message('更新成功！', $this->createWebUrl('gzredbag_money', array('op' => 'display')), 'success');
	  }
	}
   	include $this->template('gzredbag_money');
   }

   function doWebgzredbag_hx(){
  	global $_W, $_GPC;
  	load()->func('tpl');
  	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
  	$uniacid=$_W["uniacid"];
  	$id=$_GPC['id'];
  	if ($op=='display') {
  		$pindex = max(1, intval($_GPC['page']));	
		$psize= 20;
		$list = pdo_fetchall("SELECT *  from ".tablename('gzredbag_hx')."  where uniacid=$uniacid order by id desc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('gzredbag_hx')."  where uniacid=$uniacid ");
		$pager = pagination($total, $pindex, $psize);
  	}
  	if ($op=='post') {
  		if (!empty($id)) {
  		$item = pdo_fetch("SELECT *  from ".tablename('gzredbag_hx')."  where uniacid=$uniacid and id=$id");
  		if (empty($item)) {
				message('抱歉，红包不存在或是已经删除！', '', 'error');
			}
		}
		if (checksubmit('submit')) {
			$data = array(
			    'uniacid' => $_W['uniacid'],
			    'openid'=> $_GPC['openid'],
				'hxcode' => $_GPC['hxcode'],
				'status' => $_GPC['status'],
				'createtime' => time()
			);

			if(empty($id)){
				pdo_insert('gzredbag_hx', $data);
			}
			else{
				pdo_update('gzredbag_hx', $data, array('id' => $id));
			}
			message('红包更新成功！', $this->createWebUrl('gzredbag_hx'), 'success');
			}
		} 
		if ($op=='delete') {
			pdo_delete('gzredbag_hx', array('id' => $id));
            message('删除成功！', $this->createWebUrl('gzredbag_hx', array('op' => 'display')), 'success');
		}

  	include $this->template('gzredbag_hx');
  }

   public function import_csv($file_name){
    	global $_W,$_GPC;
  	 	$uniacid = $_W['uniacid'];
        $file = fopen($file_name,'r'); 
		while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
		$tel_list[] = $data;
		}		
		array_splice($tel_list,0,1);
		
		foreach ($tel_list as $arr){
		 	$list = array(
		 		'uniacid'=>$uniacid,
		 		'openid'=>'',
		 		'hxcode' =>$arr[0],
		 		'status'=>'0',
		 		'createtime'=>time()
		 		);
 	     pdo_insert('gzredbag_hx',$list);
	 } 
	  fclose($file);
	  message('导入成功！', referer(), 'success');
	}
    
  	 public function doWebImport() {
  	 	 $file = $_FILES["file"];
  	
         if (end(explode('.', $file['name']))!="csv"){
         	message('请导入csv文件！', referer(), 'error');
         }
  	 
  	 	$this->import_csv($file['tmp_name']);
  	 }
  	 
  	 public function doMobileCode(){
  	 	global $_W;
  	 	load()->model('account');
        $_W['account'] = account_fetch($_W['uniacid']);
        $package=array();
        $package['appid'] =$_W['account']['key'];
       
        $settings = uni_setting($_W['uniacid'], array('payment'));
        if(!is_array($settings['payment'])) {
	      exit('没有设定支付参数.');
        }
        $wechat = $settings['payment']['wechat'];
    
        $package['mch_id'] =$wechat['mchid'];
        $package['nonce_str'] ="f6808210402125e30663234f94c87a8c";
  	 	$package['product_id'] ="1";
  	 	$package['time_stamp'] ="1415949957";
  	 	
  	 	ksort($package, SORT_STRING);
	    $string1 = '';
	    foreach($package as $key => $v) {
		 $string1 .= "{$key}={$v}&";
	    }
	    $string1 .= "key={$wechat['signkey']}";
	    $package['sign'] = strtoupper(md5($string1));
  	 	
  	 	$zz="weixin://wxpay/bizpayurl?appid={$package['appid']}&mch_id={$package['mch_id']}&".
        "nonce_str=f6808210402125e30663234f94c87a8c&product_id=1&time_stamp=1415949957&sign={$package['sign']}";
         
        require_once '../framework/library/qrcode/phpqrcode.php';
        $url =$zz;
        QRcode::png($url);
       
  	 }

     public function doWebQrcode(){
        global $_GPC;
        require_once '../framework/library/qrcode/phpqrcode.php';
        $qr_url=($_GPC['qr_url']);
        QRcode::png($qr_url);
     }
  	 
  	 
  	 public function doMobileTest(){
  	 	//此代码为本地调试作用，并无实际作用，特此说明
  	 	return;	
		global $_W;
		$input = file_get_contents('php://input');

      if (!empty($input)) {
	    $obj = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
	    $data = json_decode(json_encode($obj), true);

        }
        load()->func('logging');
        logging_run($data); 
        
		$input = array();
        $input['body']="body";
        $input['attach']="attach";
        $input['out_trade_no']=date("YmdHis");
        $input['total_fee']=1;
        $input['time_start']=date("YmdHis");
        $input['time_expire']=date("YmdHis", time() + 600);
        $input['goods_tag']="test";
        $input['trade_type']="NATIVE";
        $input['product_id']=$data['product_id'];
        $input['openid']=$data['openid'];
        $notifyUrl = $_W['siteroot'] . "addons/" . $this->modulename . "/WxPay/notify.php";
                
       
        
        $input['notify_url']=$notifyUrl;
        logging_run("notify"); 
       logging_run($input); 
        $settings = uni_setting($_W['uniacid'], array('payment'));
        if(!is_array($settings['payment'])) {
	      exit('没有设定支付参数.');
        }
       $wechat = $settings['payment']['wechat'];
       $sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
       $row = pdo_fetch($sql, array(':acid' => $wechat['account']));
       $wechat['appid'] = $row['key'];
       $wechat['secret'] = $row['secret'];
   
       $result = wechat_build($input, $wechat);
       if (is_error($result)) {
       	 logging_run("抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。"); 
	     exit;
      }
       logging_run("result"); 
       logging_run($result); 
        $input = array();
        $input['return_code']="SUCCESS";
        $input['appid']=$wechat['appid'];
        $input['mch_id']=$wechat['mchid'];
        $input['nonce_str']=$data['nonce_str'];
        $input['prepay_id']=$result['prepay_id'];
        
        $input['result_code']='SUCCESS';
        
        ksort($input, SORT_STRING);
	    $string1 = '';
	    foreach($input as $key => $v) {
			$string1 .= "{$key}={$v}&";
	    }
	    $string1 .= "key={$wechat['signkey']}";
	    $input['sign'] = strtoupper(md5($string1));
	    logging_run("input"); 
	    logging_run($input); 
        exit(array2xml($input));
   }
  	 
  
  	 
  	 
  	public function doMobileZf(){	
		global $_W;
		$input = array();
        $input['body']="body";
        $input['attach']="attach";
        $input['out_trade_no']=date("YmdHis");
        $input['total_fee']=1;
        $input['time_start']=date("YmdHis");
        $input['time_expire']=date("YmdHis", time() + 600);
        $input['goods_tag']="test";
        $input['trade_type']="NATIVE";
        $notifyUrl = $_W['siteroot'] . "addons/" . $this->modulename . "/WxPay/notify.php";
      
        $input['notify_url']=$notifyUrl;
        $settings = uni_setting($_W['uniacid'], array('payment'));
        if(!is_array($settings['payment'])) {
	      exit('没有设定支付参数.');
        }
       $wechat = $settings['payment']['wechat'];
       $sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
       $row = pdo_fetch($sql, array(':acid' => $wechat['account']));
       $wechat['appid'] = $row['key'];
       $wechat['secret'] = $row['secret'];
 
        $result = wechat_build($input, $wechat);
       if (is_error($result)) {
         message("抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。");
	     exit;
      } else {
        require_once '../framework/library/qrcode/phpqrcode.php';
        $url = $result['code_url'];
        QRcode::png($url);
     }
   }

   function doWebduizhang(){
    global $_W, $_GPC;
    load()->func('communication');
    
    $uniacid=$_W["uniacid"];
    $id=$_GPC['id'];
    $settings=$this->module['config'];
    $password=$settings['password'];
    $appid=$settings['appid'];
    $mch_id=$settings['mchid'];
    $item = pdo_fetch("SELECT *  from ".tablename('gzredbag_wxpay_order')."  where uniacid=$uniacid and id=$id");
    $package = array();
    $package['appid'] =$appid;
    $package['mch_id'] = $mch_id;
    $package['nonce_str'] = random(8);
    $package['out_trade_no'] = $item['out_trade_no'];

    ksort($package, SORT_STRING);
    $string1 = '';
    foreach($package as $key => $v) {
      $string1 .= "{$key}={$v}&";
    }
    $string1 .= "key={$password}";
    $package['sign'] = strtoupper(md5($string1));
    $dat = array2xml($package);
    //www.guifox.com
    $response = ihttp_request('https://api.mch.weixin.qq.com/pay/orderquery', $dat);
    if (is_error($response)) {
      return $response;
    }
    $xml = @simplexml_load_string($response['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
    if(($xml->trade_state)=='SUCCESS'){
      pdo_update('gzredbag_wxpay_order',array('pay_status' =>1),array('id' =>$id));
      message('你已付款', $this->createWebUrl('wxpay_item'), 'success');
    }
    else{
      message('未付款', $this->createWebUrl('wxpay_item'), 'error');
    }
   } 
    /*public function http_request($url, $data=NULL){
    $curl=curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//CURLOPT_SSL_VERIFYPEER(默认值为2)被启用，CURLOPT_SSL_VERIFYHOST需要被设置成TRUE否则设置为FALSE。
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);//1 检查服务器SSL证书中是否存在一个公用名(common name)
    if (!empty($data)) {
      curl_setopt($curl, CURLOPT_POST, 1);//  启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//全部数据使用HTTP协议中的"POST"操作来发送。
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//  将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
    $output=curl_exec($curl);
    curl_close($curl);
    return $output;
  }*/

}