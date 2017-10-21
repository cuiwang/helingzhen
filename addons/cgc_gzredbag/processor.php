<?php
/**
 * 关注送红包模块处理程序
 * 鬼 狐 源 码 社 区 www.guifox.com
 */
load()->func('logging');


require_once IA_ROOT . "/addons/cgc_gzredbag/common/common.php";

class Cgc_gzredbagModuleProcessor extends WeModuleProcessor {
	public function respond() {
	   global $_W;
	 if ($this->message['type']=="trace"){
	 	return;
	 }
	 
     $settings = $this->module['config'];
 
     $content = $this->message['content'];
     $openid=$this->message['from'];
     if(!$this->inContext && !empty($settings['offline'])){
       $this->beginContext(180);//锁定180秒
       return $this->respText("请输入线下中奖码");
     }

     if($this->inContext && !empty($settings['offline'])){
        $settings['content']=$content;  
      }
	       
      $this->endContext();
    
      $amount= mt_rand(($settings["min_money"])*100,($settings["max_money"])*100);
      $settings['amount']=$amount/100;
      $ret=$this->sflq($settings,$openid);  


        if ($ret['code']!=0){
        	if ($ret['code']==-1 && !empty($settings['wks_title'])){
        	     $news []=array(
                        'title'=>$settings['wks_title'],
                        'description'=>$settings['wks_desc'],
                        'picurl'=>tomedia($settings['wks_thumb']),
                        'url' =>$settings['wks_url'],
                    );
              return $this->respNews($news); 
        	}
          return $this->respText($ret['message']);
        }
        
        
        $this->update_user($amount/100,$_W['uniacid'],$openid);
        
         $this->updatemoneydata($_W['uniacid'],$amount/100);	
   
        // 企业付款
        if (empty($settings['sendtype'])){
           $ret=$this->send_qyfk($settings,$openid,$amount,"红包来了");
         } else {
           //现金红包
           $ret=$this->send_xjhb($settings,$openid,$amount,"红包来了");
         }
        if ($ret['code']!=0){
          logging_run($ret); 
          //失败了就把状态回滚,腾讯服务器有时候抽风，这个去掉算了
        //  $this->rollback_user($_W['uniacid'],$openid);
          return $this->respText($ret['message']);
        } else {
          //线下中奖码置设为已领取	
          if (!empty($settings['offline'])){      
            $this->update_offline_user($content,$_W['uniacid'],$openid); 
           
          }
          //$this->updatemoneydata($_W['uniacid'],$amount/100);	
        }

        if (empty($settings['answer_type'])){
           $text=empty($settings['desc'])?"你已经收到".($amount/100)."元红包,请注意查收":$settings['desc'];
           return $this->respText($text);  
        } else {
              $news []=array(
                        'title'=>$settings['title'],
                        'description'=>$settings['desc'],
                        'picurl'=>tomedia($settings['thumb']),
                        'url' =>$settings['url'],
                    );
          return $this->respNews($news); 
        }
       
	}
	

   
   // 回滚
   function rollback_user($weid,$openid){
  	 load()->func('logging');
     $sql="update ".tablename("gzredbag_user").
          " set openid='$openid',status=0,send_status=0,createtime=".TIMESTAMP .
          " where uniacid={$weid} and openid='$openid'";
     $temp=pdo_query($sql);
   }
  
   // 修改用户状态
   function update_user($obj,$weid,$openid){
  	 load()->func('logging');
  	 $sql="select count(1) from ".tablename("gzredbag_user")."  where uniacid=".$weid." and openid='{$openid}'";
     $exist=pdo_fetchcolumn($sql);
     if (empty($exist)){
         $sql="INSERT INTO ".tablename("gzredbag_user").
          " (uniacid,openid, money,status,send_status,createtime)" .
          " VALUES ('{$weid}', '{$openid}',{$obj},1,1,".TIMESTAMP.")";
          $temp=pdo_query($sql);        
       if ($temp==false) {          
        logging_run("update_user:".$temp."==".$sql); 
       } 
    } 
   }
   
     // 修改线下用户状态
   function update_offline_user($hxcode,$weid,$openid){
  	 load()->func('logging');
     $sql="update ".tablename("gzredbag_hx").
          " set openid='$openid',status=1,createtime=".TIMESTAMP .
          " where uniacid={$weid} and hxcode='$hxcode'";
     $temp=pdo_query($sql);
     if ($temp==false) {          
        logging_run("update_offline_user:".$temp."==".$sql); 
      } 
   }
   
   
	
	
	//是否可以领取红包
	public function sflq($settings,$openid){
	  global $_W;
      $ret=array();
	  $ret['code']=0;
      $ret['message']="success";
	  $curtime=date("Y-m-d H:i",time());
	  $starttime=date("Y-m-d H:i",$settings['starttime']);
	  $endtime=date("Y-m-d H:i",$settings['endtime']);
 
      $addr=$settings['addr'];
        
	   if (!empty($settings['start_hour'])   && !empty($settings['end_hour'])){
         	 $Hour = date('G');
             if ($settings['start_hour']>$Hour || $settings['end_hour']<=$Hour){
               return array("code"=>"-1","message"=>"活动时间为:".$settings['start_hour']."点到".$settings['end_hour']."点"); 
   	         }
         }
     
	  //时间判断
	  if (!empty($starttime) && !empty($endtime)){
	  	if ($starttime>$curtime){
	  	   $ret['code']=-1;
          $ret['message']="活动时间为$starttime,当前时间为".$curtime.",耐心等待。";       
          return $ret;
	  	}
	  	
	  	if ($curtime>=$endtime){
	  	 $ret['code']=-2;
         $ret['message']="活动已经结束";
         return $ret;
	  	}
	  }
	
	  //是否超过红包总金额
	  $total_money=pdo_fetchcolumn("select total_money from ".tablename('gzredbag_money')." where uniacid={$_W['uniacid']}");
	  if ($total_money>=round($settings['total_money']+$settings['amount'],2)){
	  	 $ret['code']=-3;
         $ret['message']="红包已经发放完毕";
         return $ret;
	  	}
	 
	  //是否领取过
	   $status=pdo_fetchcolumn("select status from ".tablename('gzredbag_user')." where uniacid={$_W['uniacid']} and openid='$openid'");
	   if (!empty($status)){
	  	   $ret['code']=-4;
         $ret['message']="你已经领取过红包";
         return $ret;
	  	}
	  //线下中奖码	
      if (!empty($settings['offline'])){   
        $gzredbag_hx=pdo_fetch("select openid,status from ".tablename('gzredbag_hx')." where uniacid={$_W['uniacid']} "
         ." and hxcode='{$settings['content']}'");
        if (empty($gzredbag_hx)){
         $ret['code']=-5;
         $ret['message']="中奖代码不存在";
         return $ret;
        }
        if (!empty($gzredbag_hx['status'])){
          $ret['code']=-6;
          $ret['message']="此中奖代码已经被领取";
          return $ret;
        }
      }

     //地址 
     if (!empty($settings['addr'])){ 
         $message['time']=strtotime("-7 day",time());
         $message['uniacid']=$_W['uniacid'];
         $message['from_user']=$openid;

         $location=getLocation($message);
           if  (empty($location)){
           $ret['code']=-7;
           $ret['message']=empty($settings['addr_error'])?"打开地理定位":$settings['addr_error'];
           return $ret;
         }
         $location['addr']= $settings['addr'];
         $result=getAddr($location);
         if  ($result==false){
           $ret['code']=-8;
           $ret['message']=empty($settings['addr_error'])?"地区不符合":$settings['addr_error'];
           return $ret;
         }
    
      }  

      return $ret;
   }
   
       //总金额改变
  function updatemoneydata($weid,$amount){
  	load()->func('logging');
  	$totaldata=pdo_fetchcolumn("select count(1) from ".tablename("gzredbag_money")."  where uniacid=".$weid);  	
    if (empty($totaldata)){
      pdo_insert("gzredbag_money",
             array("uniacid"=>$weid,
                   "total_money"=>$amount, 
                   "createtime"=>TIMESTAMP
                  ));
          
    } else {
      $temp=pdo_query("update ".tablename("gzredbag_money").
           " set total_money=total_money+".$amount.",createtime=".TIMESTAMP." where uniacid=".$weid);
      logging_run("gzredbag_moneyend"); 
    
    } 
   }
   //现金红包接口
   function send_xjhb($settings,$fromUser,$amount,$desc) {
   	   $ret=array();
       $ret['code']=0;
       $ret['message']="success";     
   //  return $ret;  	
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] =random(10). date('Ymd') . random(3);
        $pars['mch_id'] = $settings['mchid'];
        $pars['wxappid'] = $settings['appid'];
        $pars['nick_name'] =   $settings['send_name'];
        $pars['send_name'] = $settings['send_name'];
        $pars['re_openid'] = $fromUser;
        $pars['total_amount'] = $amount;
        $pars['min_value'] = $amount;
        $pars['max_value'] = $amount;
        $pars['total_num'] = 1;
        $pars['wishing'] = $desc;
        $pars['client_ip'] = $settings['ip'];
        $pars['act_name'] =  $settings['act_name'];
        $pars['remark'] = $settings['remark'];

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$settings['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
       
        $extras['CURLOPT_CAINFO']= $settings['rootca'];
        $extras['CURLOPT_SSLCERT'] =$settings['apiclient_cert'];
        $extras['CURLOPT_SSLKEY'] =$settings['apiclient_key'];


        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp["message"];
            $ret['code']=-1;
            $ret['message']=$procResult;
            return $ret;     
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
             if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['message']="success";
               
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['message']=$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['message']="3error3";
                return $ret;
            }
            
        }

     
    }
  
  //企业付款接口
  public function send_qyfk($settings,$fromUser,$amount,$desc){
    $ret=array();
  	$ret['code']=0;
    $ret['message']="success";     
  
    $ret['amount']=$amount;
    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    $pars = array();
    $pars['mch_appid'] =$settings['appid'];
    $pars['mchid'] = $settings['mchid'];
    $pars['nonce_str'] = random(32);
    $pars['partner_trade_no'] = random(10). date('Ymd') . random(3);
    $pars['openid'] =$fromUser;
    $pars['check_name'] = "NO_CHECK";
    $pars['amount'] =$amount;
    $pars['desc'] = $desc;
    $pars['spbill_create_ip'] =$settings['ip']; 
    ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$settings['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO']= $settings['rootca'];
        $extras['CURLOPT_SSLCERT'] =$settings['apiclient_cert'];
        $extras['CURLOPT_SSLKEY'] =$settings['apiclient_key'];
 
     
        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp['message'];
            $ret['code']=-1;
            $ret['message']="-1:".$procResult;
            return $ret;            
         } else {        	
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['message']="success";
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['message']="-2:".$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['message']="error response";
                return $ret;
            }
        }
    
   }
}