<?php

   /**
    * 接口文件
    * url http://tupian.9yetech.com/app/index.php?i=19&c=entry&do=api&m=jy_qingsongchou
    */

    if(!GARCIA_APP){ include  $this->template('bad'); exit;}
    $apikey = 'yidajia_app_'.$this->weid;

    $apikey = md5($apikey);
    $func = $_GPC['func'];
    $action = $_GPC['action'];

    $is_memache = $this->sys['is_memache'];

    if($is_memache){
         $memcache_obj = memcache_connect($this->sys['memcachelink'], $this->sys['memcacheprot']);

    }

   if($_GPC['apikey']!=$apikey){
     die(json_encode(array('status_code'=>0,'msg'=>$apikey)));
   }
   else if(!empty($func)){
     if(!file_exists(GARCIA_PATH."/class/mobile/api/".$func."_func.php")){
        _fail(array('msg'=>'not found file'));
     }

       include GARCIA_PATH."/class/mobile/api/".$func."_func.php";

   }else{
       die(json_encode(array('status_code'=>0,'msg'=>'No Founds Function')));
   }



   function _fail($data){
      $data['status_code'] = 0;
      die(json_encode($data));
   }
   function _success($data){
     $data['status_code'] = 1;
     die(json_encode($data));
   }

 ?>
