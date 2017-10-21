<?php

   $url = 'http://connect.yidajia.cc/app/index.php?c=entry&do=api&m=jy_qingsongchou';

   $data = array(
      'i'=>4,
      'func'=>'pay',
      'apikey'=>md5('yidajia_app_4'),
      'fee'=>10,
      'action'=>'getField',
      'target_code'=>'64204e49822ad159f67db4373c4313f0'
   );

   
   $ch = curl_init ();
   // print_r($ch);
   curl_setopt ( $ch, CURLOPT_URL, $url );
   curl_setopt ( $ch, CURLOPT_POST, 1 );
   curl_setopt ( $ch, CURLOPT_HEADER, 0 );
   curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
   curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
   $return = curl_exec ( $ch );
   curl_close ( $ch );
      var_dump($return);
   $return = json_decode($return,true);
   echo "<pre>";
   var_dump($return);
   echo "</pre>";

 ?>
