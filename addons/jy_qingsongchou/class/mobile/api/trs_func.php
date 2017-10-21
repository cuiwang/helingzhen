<?php
  /**
   * 手机注册
   */

     include GARCIA_TAOBAO."TopSdk.php";
     if($action=='getcode'){
           $phone = $_GPC['phone'];
           if(!empty($phone)){
                  $_pres = pdo_fetch('SELECT id,mobile_status,mobile_code FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and mobile='".$phone."'");


                  $code = (string)rand(1000,9999);
                  $_config = array('code'=>$code,'product'=>$this->sys['sitename']);
                  $_config = json_encode($_config);
                  $c = new TopClient;
                  $c->appkey = $this->sys['dayu_appkey'];
                  $c->secretKey = $this->sys['dayu_secretkey'];
                  $req = new AlibabaAliqinFcSmsNumSendRequest;
                  $req->setExtend(123456);
                  $req->setSmsType("normal");
                  $req->setSmsFreeSignName($this->sys['dayu_sign']);
                  $req->setSmsParam($_config);
                  $req->setRecNum($phone);
                  $req->setSmsTemplateCode($this->sys['dayu_temp']);
                  $resp = $c->execute($req);
                  if($resp->code!=0){
                      die(json_encode(array('status_code'=>0,'msg'=>$resp->sub_msg)));
                  }else{
                      if(!empty($_pres['id'])){
                          pdo_update(GARCIA_PREFIX."member",array('mobile_code'=>$code),array('id'=>$_pres['id']));
                      }else{
                         pdo_insert(GARCIA_PREFIX."member",array('mobile'=>$phone,
                         'mobile_code'=>$code,'mobile_status'=>0,'weid'=>$this->weid));
                      }

                      die(json_encode(array('status_code'=>1,'msg'=>'短信发送成功')));
                  }

           }else{
               die(json_encode(array('status_code'=>0,'msg'=>'电话号码不能为空')));
           }
     }else if($action=='done_re'){
         $code = $_GPC['code'];
         $phone = $_GPC['phone'];
         if(empty($code)&&$code=='0'){
             die(json_encode(array('status_code'=>0,'msg'=>'请输入验证码')));
         }
        $_pres = pdo_fetch('SELECT id,mobile_status,mobile_code FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and mobile='".$phone."'");

        if($_pres['mobile_code']!=$code){
            die(json_encode(array('status_code'=>0,'msg'=>'验证码错误')));
        }

        pdo_update(GARCIA_PREFIX."member",array('mobile_status'=>1,'type'=>'2','mobile_code'=>''),array('id'=>$_pres['id']));
        die(json_encode(array('status_code'=>1,'msg'=>'登陆成功','uid'=>$_pres['id'])));
     }
     else if($action=='done_re2'){
        //for pc or web
         $code = $_GPC['code'];
         $phone = $_GPC['phone'];
          $_pres = pdo_fetch('SELECT id,mobile_status,mobile_code FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and mobile='".$phone."'");
         $userStatus = $this->cookies->get('userStatus');
         if($userStatus==1){
                   pdo_update(GARCIA_PREFIX."member",array('mobile_status'=>1,'type'=>'2','mobile_code'=>''),array('id'=>$_pres['id']));
           _success(array('msg'=>'你已经成功登陆'));
         }
         if(empty($code)&&$code=='0'){
             die(json_encode(array('status_code'=>0,'msg'=>'请输入验证码')));
         }
        $_pres = pdo_fetch('SELECT id,mobile_status,mobile_code FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and mobile='".$phone."' order by openid desc");

        if($_pres['mobile_code']!=$code){
            die(json_encode(array('status_code'=>0,'msg'=>'验证码错误')));
        }
        // setcookie("userStatus", "1", time()+(3600*24*7));
        $this->cookies->set('userStatus','1',(3600*24*7));
        $this->cookies->set('userDatas',json_encode(array('uid'=>$_pres['id'])),(3600*24*7));
        // setcookie("userStatus", "1", time()+(3600*24*7));
        pdo_update(GARCIA_PREFIX."member",array('mobile_status'=>1,'type'=>'2','mobile_code'=>''),array('id'=>$_pres['id']));
        die(json_encode(array('status_code'=>1,'msg'=>'登陆成功','uid'=>$_pres['id'])));
     }
     else if($action=='bd'){
          $uid = $_GPC['uid'];
          $tel = pdo_fetchcolumn('SELECT mobile FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and id=".$uid);
          $id = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and tel='".$tel."'");
          if($id){
             _success(array('id'=>$id,'tel'=>substr($tel,0,4)."****".substr($tel,8),'url'=>$this->createMobileUrl('bangding',array('dopost'=>'relogin','mid'=>$id))));
          }else{

              _fail(array('id'=>$id));
          }

     }
     else{
         _fail(array('msg'=>'not found function'));
     }

    //  echo GARCIA_TAOBAO;

 ?>
