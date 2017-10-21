<?php
     include   GARCIA_PATH."class/serverAPI.php";

     $ry = new ServerAPI('pgyu6atqypm5u','3BjKOZSjHiBl');


     if($_GPC['dopost']=='getUsInfo'){

       die(json_encode(array('mdg'=>$ry->getUsInfo($_GPC['senderUserId']))));
     }
     $token = $ry->getToken('001'.$this->weid, '宜达家客服', 'http://weiqing2.oss-cn-qingdao.aliyuncs.com/images/19/2016/09/wGVzLC344qr4rVG3LCGz3gJg94TKLL.png');
     $token = json_decode($token,true);
     $token = $token['token'];
    // echo '001'.$this->weid;
 ?>
