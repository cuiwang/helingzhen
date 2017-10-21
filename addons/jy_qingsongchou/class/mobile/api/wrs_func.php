<?php

     /**
      * 微信注册登陆
      */

    if($action=='done_re'){
         $openid = $_GPC['openid'];

         $headimgurl = $_GPC['headimgurl'];
         $nickname = urlencode($_GPC['nickname']);

         if(empty($openid)){
              die(json_encode(array('status_code'=>0,'msg'=>'没有openid')));
         }

         $_pres = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and openid='".$openid."'");

         if(!empty($_pres)){

              die(json_encode(array('status_code'=>1,'msg'=>'登陆成成功2','uid'=>$_pres)));
         }else{
            pdo_insert(GARCIA_PREFIX."member", array('openid'=>$openid,'weid'=>$this->weid,"nickname"=>$nickname,'headimgurl'=>$headimgurl,'type'=>1));
            $inserid = pdo_fetchcolumn('SELECT id FROM '.tablename(GARCIA_PREFIX."member")." where weid=".$this->weid." and openid='".$openid."'");
              die(json_encode(array('status_code'=>1,'msg'=>'登陆成成功','uid'=>$inserid,'in'=>1)));
         }

    }

 ?>
